<?php

namespace TekniskSupport\LimitedGuestAccess\Admin;

class Actions
{
    protected $api_url  = 'http://supervisor/core/api/';
    const DATA_DIR = '/data/links/';
    public $externalUrl;
    protected $token;
    protected $allLinks = null;
    protected $isDirty  = false;

    function __construct()
    {
        if (!file_exists(self::DATA_DIR) || !is_dir(self::DATA_DIR)) {
            mkdir('/data/links');
        }
        $options = json_decode(file_get_contents('/data/options.json'));
        $this->externalUrl = $options->external_url;
        $this->token = $options->home_assistant_token;
        $this->api_url = $options->api_url;

        $this->handleRequest();
        $this->getAllLinks();
    }

    public function getAllLinks()
    {
        if ($this->isDirty || !isset($this->allLinks) || is_null($this->allLinks)) {
            $this->allLinks = glob(self::DATA_DIR . '*.json');
        }

        return $this->allLinks;
    }

    protected function getId()
    {
        if (isset($_REQUEST['id']) && ctype_xdigit($_REQUEST['id']))
            return $_REQUEST['id'];
        else
            throw new \Exception('No ID given!');
    }

    protected function handleRequest()
    {
        if (!isset($_GET['action'])) {

            return $this;
        }

        switch ($_GET['action']) {
            case 'generateNewLink':
                $this->generateNewLink()->redirect();
                break;
            case 'deleteLink':
                $hash = $this->getId();
                $this->deleteLink($hash)->redirect();
                break;
            case 'removeAction':
                $this->removeAction($this->getId(), $_GET['action_id'])->redirect();
                break;
            case 'addActionToLink':
                $this->addActionToLink($this->getId())->redirect();
                break;
            case 'editAction':
                $this->addActionToLink($this->getId(), $_GET['action_id'])->redirect();
                break;
        }
    }

    protected function addActionToLink($hash, $id = false)
    {
        $link    = json_decode(file_get_contents(self::DATA_DIR . $hash . '.json'), true);
        if (!$link) {
            $link = [];
        }
        $id = ($id) ? $id : uniqid();
        $newData[$id] = [
            'friendly_name'   => $_POST['friendly_name'],
            'service_call'    => $_POST['service_call'],
            'valid_from'      => $_POST['valid_from']         ?? 0,
            'expiry_time'     => $_POST['expiry_time']        ?? null,
            'one_time_use'    => (isset($_POST['one_time_use']))? 1: 0,
        ];

        foreach ($_POST['dynamic_field'] as $key => $additionalField) {
            $newData[$id]['service_call_data'][$key] = $additionalField;
        }

        if (json_encode($newData)) {
            $json = json_encode(array_merge($link, $newData));
        }

        file_put_contents(self::DATA_DIR . $hash . '.json', $json);

        return $this;
    }

    protected function removeAction($hash, $actionId)
    {
        $json = json_decode(file_get_contents(self::DATA_DIR . $hash . '.json'),true);
        unset($json[$actionId]);
        $json = json_encode($json);
        file_put_contents(self::DATA_DIR . $hash . '.json', $json);

        return $this;
    }

    protected function generateNewLink()
    {
        $hash = $this->generateHash();
        if (touch(self::DATA_DIR . $hash . '.json')) {
            $this->isDirty = true;
        }

        return $this;
    }

    protected function deleteLink($hash)
    {
        if (unlink(self::DATA_DIR . $hash . '.json')) {
            $this->isDirty = true;
        } else {
            throw new \Exception('Unable to delete file');
        }

        return $this;
    }

    protected function redirect()
    {
        header("Location: ?");

        return $this;
    }

    protected function generateHash()
    {
        $hash = substr(md5(time()), 0, 6);
        if (file_exists(self::DATA_DIR . $hash . '.json')) {
            $hash = $this->generateHash();
        }

        return $hash;
    }

    public function getServiceData()
    {
        $ch = curl_init($this->api_url . 'services');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                           "Authorization: Bearer {$this->token}"
                       ]
        );

        return curl_exec($ch);
    }

    public function getStates()
    {
        $ch = curl_init($this->api_url . 'states');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                           "Authorization: Bearer {$this->token}"
                       ]
        );

        return curl_exec($ch);
    }
}