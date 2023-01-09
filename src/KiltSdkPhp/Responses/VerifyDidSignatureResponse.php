<?php

namespace KiltSdkPhp\Responses;

use KiltSdkPhp\Exceptions\BackendResponseException;
use KiltSdkPhp\Exceptions\InvalidDidFormatErrorException;
use KiltSdkPhp\Exceptions\SignatureUnverifiableErrorException;
use KiltSdkPhp\Exceptions\ErrorException;
use KiltSdkPhp\Exceptions\DidNotFoundErrorException;

class VerifyDidSignatureResponse extends BaseResponse
{
    public ?\stdClass $response;

    /**
    * @SuppressWarnings(PHPMD.CyclomaticComplexity)
    */
    public function __construct(\stdClass $source)
    {
        parent::__construct($source);
        if ($this->status == 'error' && $this->error == 'InvalidDidFormatError') {
            throw new InvalidDidFormatErrorException($this->message);
        }
        if ($this->status == 'error' && $this->error == 'SignatureUnverifiableError') {
            throw new SignatureUnverifiableErrorException($this->message);
        }
        if ($this->status == 'error' && $this->error == 'DidNotFoundError') {
            throw new DidNotFoundErrorException($this->message);
        }
        if ($this->status == 'error' && $this->error == 'Error') {
            throw new ErrorException($this->message);
        }

        if ($this->status == 'error') {
            throw new BackendResponseException($this->error . ': ' . $this->message);
        }

        $this->response = $source->response;
    }
}
