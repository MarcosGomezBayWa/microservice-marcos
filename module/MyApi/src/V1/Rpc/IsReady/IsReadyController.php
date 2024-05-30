<?php

namespace MyApi\V1\Rpc\IsReady;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class IsReadyController extends AbstractActionController
{
    public function isReadyAction(): JsonModel
    {
        return new JsonModel(['status' => 'OK']);
    }
}
