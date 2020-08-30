<?php
namespace TekniskSupport\LimitedGuestAccess\User;

class Actions {
    const     DATA_DIR = '/data/links/';
    const     API_URL  = 'http://supervisor/core/api/';
    protected $data;
    public    $actionName;

    public function __construct()
    {
        if (!file_exists(self::DATA_DIR . $this->getLink() . '.json')) {
            http_response_code(401);
            throw new \Exception('Not allowed');
        } else {
            $this->data = json_decode(
                file_get_contents(self::DATA_DIR . $this->getLink() . '.json')
            );
        }

        if (isset($_GET['action'])) {
            $availableActions = $this->getFilteredActions();
            $actionData       = $availableActions->{$this->getAction()};
            if (!$actionData) {
                throw new \Exception('unknown action');
            }

            $this
                ->performAction($actionData)
                ->invalidateAction($actionData, $this->getAction())
                ->redirect('?performedAction='. urlencode($actionData->friendly_name));
        }
    }

    public function getAllActions()
    {

        return $this->data;
    }

    public function getFilteredActions()
    {
        $filteredActions = (object)[];
        $allActions = $this->getAllActions();
        foreach ($allActions as $id => $action) {
            if ($this->validateTime($action)) {
                $filteredActions->{$id} = $action;
            }
        }

        return $filteredActions;
    }

    protected function validateTime($actionData)
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

    protected function invalidateAction($actionData, $actionId)
    {
        if ($actionData->one_time_use) {
            $actions = (array)$this->getAllActions();
            unset($actions[$actionId]);
            file_put_contents(self::DATA_DIR . $this->getLink() . '.json', json_encode($actions));
        }

        return $this;
    }

    protected function performAction($actionData)
    {
        $data           = ['entity_id' => $actionData->entity_id] ?? [];
        $additionalData = json_decode($actionData->additional_data, 1) ?? [];
        $data           = json_encode(array_merge($data, $additionalData));
        $serviceCall    = explode('.',$actionData->service_call);

        $ch = curl_init(self::API_URL . 'services/' . $serviceCall[0]. '/'. $serviceCall[1]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                           "Authorization: Bearer {$_SERVER['SUPERVISOR_TOKEN']}",
                           'Content-Type: application/json',
                           'Content-Length: ' . strlen($data)
                       ]
        );
        curl_exec($ch);

        return $this;
    }

    protected function getLink()
    {
        if (isset($_REQUEST['link']) && ctype_xdigit($_REQUEST['link']))
            return $_REQUEST['link'];
        else
            throw new \Exception('No ID given!');
    }

    protected function getAction()
    {
        if (isset($_REQUEST['action']))
            return $_REQUEST['action'];
        else
            throw new \Exception('No action given!');
    }

    protected function redirect($path)
    {
        header("Location: ". $path);

        return $this;
    }
}