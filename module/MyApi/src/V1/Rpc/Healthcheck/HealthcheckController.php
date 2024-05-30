<?php

namespace MyApi\V1\Rpc\Healthcheck;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class HealthcheckController extends AbstractActionController
{
    public function healthcheckAction(): JsonModel
    {
        return new JsonModel(['status' => 'OK']);
    }
}
