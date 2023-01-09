<?php

namespace KiltSdkPhp\Requests;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class ExitRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct('exit');
    }
}
