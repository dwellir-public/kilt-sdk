<?php

namespace KiltSdkPhp\Responses;

use KiltSdkPhp\Exceptions\BackendResponseException;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class DisconnectResponse extends BaseResponse
{
    public bool $response;

    public function __construct(\stdClass $source)
    {
        parent::__construct($source);
        if ($this->status == 'error') {
            throw new BackendResponseException($this->error . ': ' . $this->message);
        }
        $this->response = $source->response;
    }
}
