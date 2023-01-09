<?php

namespace KiltSdkPhp\Responses;

use KiltSdkPhp\Exceptions\BackendResponseException;
use KiltSdkPhp\Exceptions\InvalidDidFormatErrorException;

class ResolveResponse extends BaseResponse
{
    public ?\stdClass $response;

    public function __construct(\stdClass $source)
    {
        parent::__construct($source);
        if ($this->status == 'error' && $this->error == 'InvalidDidFormatError') {
            throw new InvalidDidFormatErrorException($this->message);
        }

        if ($this->status == 'error') {
            throw new BackendResponseException($this->error . ': ' . $this->message);
        }

        $this->response = $this->source->response;

        if (isset($this->response->document->authentication[0]->publicKey)) {
            foreach ($this->response->document->authentication as $i => $authentication) {
                $chars = array_map("chr", (array)$authentication->publicKey);
                $bin = join($chars);
                $this->response->document->authentication[$i]->publicKeyHex = bin2hex($bin);
            }
        }
        if (isset($this->response->document->keyAgreement[0]->publicKey)) {
            foreach ($this->response->document->keyAgreement as $i => $keyAgreement) {
                $chars = array_map("chr", (array)$keyAgreement->publicKey);
                $bin = join($chars);
                $this->response->document->keyAgreement[$i]->publicKeyHex = bin2hex($bin);
            }
        }
    }
}
