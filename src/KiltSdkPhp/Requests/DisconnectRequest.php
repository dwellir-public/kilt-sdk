<?php

namespace KiltSdkPhp\Requests;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class DisconnectRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct('disconnect');
    }
}
