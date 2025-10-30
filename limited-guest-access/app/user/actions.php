<?php
namespace TekniskSupport\LimitedGuestAccess\User;

class Actions {
    const     DATA_DIR           = '/data/links/';
    const     INJECT_DIR         = ['/data/', '/share/limited-guest-access/'];
    const     API_URL            = 'http://supervisor/core/api/';
    public    bool $passwordProtected = false;
    public    bool $authenticated     = false;
    public    bool $authFailed        = false;
    protected object $linkData;
    protected ?object $data;
    public    ?string $theme = null;

    public function __construct()
    {
        $this->boot();
    }

    protected function boot(): void
    {
        date_default_timezone_set($_SERVER["TZ"]);
        
        // Load and validate link data
        $this->loadLinkData();
        
        // Handle authentication
        $this->handleAuthentication();
        
        // Handle actions if present
        $this->handleAction();
    }

    protected function loadLinkData(): void
    {
        $link = $this->getLink();
        
        if ($link === null) {
            $this->displayError("No link ID was provided. Please make sure you are accessing a valid link.");
        }

        $filePath = self::DATA_DIR . $link . '.json';
        
        if (!file_exists($filePath)) {
            $this->displayError("The requested link does not exist or is not authorized.");
        }
        
        $this->data = json_decode(file_get_contents($filePath));
        
        if (isset($this->data->linkData->theme)) {
            $this->theme = $this->data->linkData->theme;
        }
    }

    protected function displayError(string $message): void
    {
        http_response_code(401);
        echo "<!DOCTYPE html><html><head><title>Error</title></head><body><h1>Error</h1><p>{$message}</p></body></html>";
        exit;
    }

    protected function handleAuthentication(): void
    {
        if (isset($this->data->linkData->password) && !empty($this->data->linkData->password)) {
            $this->passwordProtected = true;
            
            if (isset($_POST['password'])) {
                if (password_verify($_POST['password'], $this->data->linkData->password)) {
                    $this->authenticated = true;
                } else {
                    $this->authFailed = true;
                }
            }
        }
    }

    protected function handleAction(): void
    {
        if (isset($_GET['action'])) {
            $availableActions = $this->getFilteredActions();
            $actionData = $availableActions->{$this->getAction()} ?? null;
            
            if (!$actionData) {
                throw new \Exception('Unknown action');
            }

            $this
                ->performAction($actionData)
                ->addLog($this->getAction())
                ->invalidateAction($actionData, $this->getAction())
                ->redirect('?performedAction=' . urlencode($actionData->friendly_name));
        }
    }

    public function getAllActions(): object
    {

        return $this->data;
    }

    public function getFilteredActions(): object
    {
        $filteredActions = (object)[];
        $allActions = $this->getAllActions();
        if (isset($allActions->linkData)) {
            $this->linkData = $allActions->linkData;
            unset($allActions->linkData);
        }
        foreach ($allActions as $id => $action) {
            if ($this->validateTime($action)) {
                $filteredActions->{$id} = $action;
            }
        }

        return $filteredActions;
    }

    protected function validateTime(object $actionData): bool
    {
        $now        = time();
        $validFrom  = strtotime($actionData->valid_from);
        $expiryTime = strtotime($actionData->expiry_time);


        if ($expiryTime && $expiryTime <= $now) {
            return false;
        }

        if ($validFrom && $validFrom >= $now) {
            return false;
        }

        return true;
    }

    protected function addLog(string $actionId): self
    {
        $time = new \DateTime();
        $actions = $this->getAllActions();
        if (!isset($actions->$actionId->last_used)) {
            $actions->$actionId->last_used = [];
        }
        $actions->$actionId->last_used[] = $time->format('U');
        file_put_contents(self::DATA_DIR . $this->getLink() . '.json', json_encode($actions));

        return $this;
    }

    protected function invalidateAction(object $actionData, string $actionId): self
    {
        if ($actionData->one_time_use) {
            $actions = (array)$this->getAllActions();
            unset($actions[$actionId]);
            file_put_contents(self::DATA_DIR . $this->getLink() . '.json', json_encode($actions));
        }

        return $this;
    }

    protected function performAction(object $actionData): self
    {
        $data = (object) array_filter((array) $actionData->service_call_data) ?? [];
        $data = json_encode($data);
        $serviceCall = explode('.', $actionData->service_call);

        $ch = curl_init(self::API_URL . 'services/' . $serviceCall[0] . '/' . $serviceCall[1]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$_SERVER['SUPERVISOR_TOKEN']}",
            'Content-Type: application/json',
            'Content-Length: ' . mb_strlen($data)
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return $this;
    }

    protected function getLink(): ?string
    {
        $link = filter_input(INPUT_GET, 'link', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($link === null) {
            $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        
        if ($link && ctype_xdigit($link)) {
            return $link;
        } elseif ($link && preg_match('/^([a-zA-Z0-9_-]+)$/', $link)) {
            return $link;
        } else {
            return null; // Return null instead of throwing an exception
        }
    }

    protected function getAction(): string
    {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($action) {
            return $action;
        } else {
            throw new \Exception('No action given!');
        }
    }

    protected function redirect(string $path): self
    {
        header("Location: ". $path);

        return $this;
    }

    public function getState(string $entityId): bool|string
    {
        $ch = curl_init(self::API_URL . 'states/'. $entityId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$_SERVER['SUPERVISOR_TOKEN']}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function injectHeader(): ?string
    {
        $headerFile = '/data/header.htm';
        if (file_exists($headerFile)) {
            return file_get_contents($headerFile);
        }
        $headerFile = '/share/limited-guest-access/header.htm';
        if (file_exists($headerFile)) {
            return file_get_contents($headerFile);
        }
        return null;
    }

    public function injectFooter(): ?string
    {
        $footerFile = '/data/footer.htm';
        if (file_exists($footerFile)) {
            return file_get_contents($footerFile);
        }
        $footerFile = '/share/limited-guest-access/footer.htm';
        if (file_exists($footerFile)) {
            return file_get_contents($footerFile);
        }
        return null;
    }

    public function injectFile(string $file): ?string
    {
        foreach (self::INJECT_DIR as $injectDirectory) {
            if (file_exists($injectDirectory . $file)) {
                if (!preg_match('/^[\w.]+$/', $file)) {
                    break;
                }

                return file_get_contents($injectDirectory . $file);
            }
        }
        return null;
    }

    public function getCustomCss(): ?string
    {
        $cssFile = '/data/style.css';
        if (file_exists($cssFile)) {
            return file_get_contents($cssFile);
        }
        
        return null;
    }
}
