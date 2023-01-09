<?php

namespace KiltSdkPhp\Responses;

use KiltSdkPhp\Exceptions\BackendResponseException;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class ConnectResponse extends BaseResponse
{
    public bool $response;

    public function __construct(\stdClass $source)
    {
        parent::__construct($source);
        if ($this->status == 'error') {
            throw new BackendResponseException($this->error . ': ' . $this->message);
        }
        // todo: this requests returns an ApiPromise in the js-sdk. How to work with that?
        // https://kiltprotocol.github.io/sdk-js/modules/_kiltprotocol_core.html#connect
    }
}
