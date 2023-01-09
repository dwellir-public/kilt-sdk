<?php

namespace KiltSdkPhp\Responses;

use KiltSdkPhp\Exceptions\BackendResponseException;

class QueryByWeb3NameResponse extends BaseResponse
{
    public \stdClass $response;

    public function __construct(\stdClass $source)
    {
        parent::__construct($source);
        if ($this->status == 'error') {
            throw new BackendResponseException($this->error . ': ' . $this->message);
        }

        $this->response = $source->response;
    }
}
