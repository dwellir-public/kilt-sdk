<?php

namespace KiltSdkPhp\Responses;

use Assert\Assert;
use KiltSdkPhp\Exceptions\RequestValueException;
use KiltSdkPhp\Exceptions\CommunicationErrorException;
use KiltSdkPhp\Exceptions\BlockchainApiMissingErrorException;

/**
* @SuppressWarnings(PHPMD.BooleanArgumentFlag)
*/
abstract class BaseResponse implements \JsonSerializable
{
    protected \stdClass $source;
    public string $status;
    public int $id;
    public string $function;
    public string $error;
    public string $message;

    public function __construct(\stdClass $source)
    {
        $this->source = $source;
        $this->status = $this->source->status;

        if ($this->status == 'error') {
            $this->error = $source->error;
            $this->message = $source->message;

            if (in_array($this->error, ['unableToDecodeInput', 'idMissing', 'functionMissing', 'functionNotSupported'])) {
                throw new CommunicationErrorException('Malformed response from wrapper: ' . $source->message);
            }

            if ($this->error == 'BlockchainApiMissingError') {
                throw new BlockchainApiMissingErrorException($this->message);
            }
        }

        $this->id = $source->id;
        $this->function = $source->function;
    }

    public function jsonSerialize()
    {
        $response = ['status' => $this->status, 'function' => $this->function, 'id' => $this->id];
        if ($this->status == 'error') {
            $response['error'] = $this->error;
            $response['message'] = $this->message;
        }

        $response['response'] = $this->response ?? null;

        return (object)$response;
    }
}
