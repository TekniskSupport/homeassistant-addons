<?php

namespace TekniskSupport\LimitedGuestAccess\Admin;

class Actions
{

    const API_URL  = 'http://supervisor/core/api/';
    const DATA_DIR = '/data/links/';
    public $externalUrl;
    protected array|bool|null $allLinks = null;
    protected bool $isDirty  = false;

    public function __construct()
    {
        $this->boot();
    }

    protected function boot(): void
    {
        date_default_timezone_set($_SERVER["TZ"]);
        $this->initializeDataDirectory();
        $this->loadConfiguration();
        $this->handleRequest();
        $this->getAllLinks();
    }

    protected function initializeDataDirectory(): void
    {
        if (!file_exists(self::DATA_DIR) || !is_dir(self::DATA_DIR)) {
            mkdir('/data/links', 0755, true);
        }
    }

    protected function loadConfiguration(): void
    {
        $options = json_decode(file_get_contents('/data/options.json'));
        $this->externalUrl = $options->external_url ?? '';
    }

    public function getAllLinks(): bool|array
    {
        if ($this->isDirty || !isset($this->allLinks)) {
            $this->allLinks = glob(self::DATA_DIR . '*.json');
        }

        return $this->allLinks;
    }

    protected function getId(): string
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($id === null) {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        
        if ($id && ctype_xdigit($id)) {
            return $id;
        } elseif ($id && preg_match('/^([a-zA-Z0-9_-]+)$/', $id)) {
            return $id;
        } else {
            throw new \Exception('No ID given!');
        }
    }

    protected function handleRequest(): self
    {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (!$action) {
            return $this;
        }

        switch ($action) {
            case 'createNamedLink':
                $linkPath = filter_input(INPUT_POST, 'linkPath', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? $this->generateHash();
                $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
                $theme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'default';
                
                $hashedPassword = !empty($password) 
                    ? password_hash($password, PASSWORD_DEFAULT)
                    : null;

                $this->generateNewLink($theme, $linkPath, $hashedPassword)->redirect();
                break;
            case 'generateNewLink':
                $this->generateNewLink()->redirect();
                break;
            case 'deleteLink':
                $hash = $this->getId();
                $this->deleteLink($hash)->redirect();
                break;
            case 'removeAction':
                $actionId = filter_input(INPUT_GET, 'action_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $this->removeAction($this->getId(), $actionId)->redirect();
                break;
            case 'addActionToLink':
                $this->addActionToLink($this->getId())->redirect();
                break;
            case 'editAction':
                $actionId = filter_input(INPUT_GET, 'action_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $this->addActionToLink($this->getId(), $actionId)->redirect();
                break;
            case 'modifyPassword':
                $this->modifyPassword($this->getId())->redirect();
                break;
            case 'manageStyle':
                $this->redirect('?page=style');
                break;
            case 'saveStyle':
                $this->saveStyle()->redirect('?page=style&saved=true');
                break;
        }

        return $this;
    }

    protected function addActionToLink(
        string $hash,
        ?string $id = null
    ): self
    {
        $link = json_decode(file_get_contents(self::DATA_DIR . $hash . '.json'), true);
        if (!$link) {
            $link = [];
        }
        
        $id = $id ?? uniqid();
        
        // Sanitize input data
        $friendlyName = filter_input(INPUT_POST, 'friendly_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $serviceCall = filter_input(INPUT_POST, 'service_call', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $validFrom = filter_input(INPUT_POST, 'valid_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $expiryTime = filter_input(INPUT_POST, 'expiry_time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $oneTimeUse = filter_input(INPUT_POST, 'one_time_use', FILTER_VALIDATE_BOOLEAN);
        
        $newData[$id] = [
            'friendly_name'   => $friendlyName,
            'service_call'    => $serviceCall,
            'valid_from'      => $validFrom ?? 0,
            'expiry_time'     => $expiryTime ?? null,
            'one_time_use'    => $oneTimeUse ? 1 : 0,
        ];

        // Handle dynamic fields
        $dynamicFields = filter_input_array(INPUT_POST, [
            'dynamic_field' => [
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        ]);
        
        if ($dynamicFields && isset($dynamicFields['dynamic_field'])) {
            foreach ($dynamicFields['dynamic_field'] as $key => $additionalField) {
                $newData[$id]['service_call_data'][$key] = $additionalField;
            }
        }

        $json = json_encode(array_merge($link, $newData));
        file_put_contents(self::DATA_DIR . $hash . '.json', $json);

        return $this;
    }

    protected function removeAction(string $hash, string $actionId): self
    {
        $json = json_decode(file_get_contents(self::DATA_DIR . $hash . '.json'),true);
        unset($json[$actionId]);
        $json = json_encode($json);
        file_put_contents(self::DATA_DIR . $hash . '.json', $json);

        return $this;
    }

    protected function generateNewLink(
        string $theme = 'default',
        ?string $linkPath = null,
        ?string $password = null
    ): self
    {
        if (!$linkPath) {
            $hash = $this->generateHash();
        } else {
            $hash = $linkPath;
        }

        if (touch(self::DATA_DIR . $hash . '.json')) {
            $this->isDirty = true;
        }

        $linkData = ['linkData' =>
             [
                'password' => $password,
                'theme'    => $theme
            ]
        ];

        file_put_contents(
            self::DATA_DIR . $hash . '.json',
            json_encode($linkData)
        );

        return $this;
    }

    protected function deleteLink(string $hash): self
    {
        if (unlink(self::DATA_DIR . $hash . '.json')) {
            $this->isDirty = true;
        } else {
            throw new \Exception('Unable to delete file');
        }

        return $this;
    }

    protected function modifyPassword(string $hash): self
    {
        $newPassword = filter_input(INPUT_POST, 'new_password', FILTER_UNSAFE_RAW);
        
        if (!empty($newPassword)) {
            $linkData = json_decode(file_get_contents(self::DATA_DIR . $hash . '.json'), true);
            $linkData['linkData']['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            file_put_contents(self::DATA_DIR . $hash . '.json', json_encode($linkData));
        }

        return $this;
    }

    protected function redirect(?string $location = null): self
    {
        $redirectLocation = $location ?? '?';
        header("Location: " . $redirectLocation);

        return $this;
    }

    protected function generateHash(): string
    {
        do {
            $hash = bin2hex(random_bytes(7));
        } while (file_exists(self::DATA_DIR . $hash . '.json'));

        return $hash;
    }

    public function getServiceData(): string|bool
    {
        $ch = curl_init(self::API_URL . 'services');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$_SERVER['SUPERVISOR_TOKEN']}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function getStates(): string|bool
    {
        $ch = curl_init(self::API_URL . 'states');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$_SERVER['SUPERVISOR_TOKEN']}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    protected function saveStyle(): self
    {
        $customCss = filter_input(INPUT_POST, 'custom_css', FILTER_UNSAFE_RAW);
        $customHeader = filter_input(INPUT_POST, 'custom_header', FILTER_UNSAFE_RAW);
        $customFooter = filter_input(INPUT_POST, 'custom_footer', FILTER_UNSAFE_RAW);
        $customJs = filter_input(INPUT_POST, 'custom_js', FILTER_UNSAFE_RAW);

        if ($customCss !== null) {
            file_put_contents('/data/style.css', $customCss);
        }

        if ($customHeader !== null) {
            file_put_contents('/data/header.htm', $customHeader);
        }

        if ($customFooter !== null) {
            file_put_contents('/data/footer.htm', $customFooter);
        }

        if ($customJs !== null) {
            file_put_contents('/data/script.js', $customJs);
        }

        return $this;
    }
}
