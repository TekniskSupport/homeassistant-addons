<?php

namespace TekniskSupport\LimitedGuestAccess\Admin;

class Actions
{
    const DATA_DIR = '/data/links/';
    public $externalUrl;
    protected $allLinks = null;
    protected $isDirty  = false;

    function __construct()
    {
        if (!file_exists(self::DATA_DIR) || !is_dir(self::DATA_DIR)) {
            mkdir('/data/links');
        }
        $options = json_decode(file_get_contents('/data/options.json'));
        $this->externalUrl = $options->external_url;

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
        }
    }

    protected function addActionToLink($hash)
    {
        $link    = json_decode(file_get_contents(self::DATA_DIR . $hash . '.json'), true);
        if (!$link) {
            $link = [];
        }

        $newData[uniqid()] = [
            'friendly_name'   => $_POST['friendly_name'],
            'service_call'    => $_POST['service_call'],
            'entity_id'       => $_POST['entity_id']          ?? '',
            'additional_data' => $_POST['additional_data']    ?? '',
            'valid_from'      => $_POST['valid_from']         ?? 0,
            'expiry_time'     => $_POST['expiry_time']        ?? null,
            'one_time_use'    => (isset($_POST['one_time_use']))? 1: 0,
        ];

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
}