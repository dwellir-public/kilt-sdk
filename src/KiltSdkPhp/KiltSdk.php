<?php

namespace KiltSdkPhp;

use KiltSdkPhp\Modules\Did\Did;
use KiltSdkPhp\Requests\ConnectRequest;
use KiltSdkPhp\Requests\DisconnectRequest;
use KiltSdkPhp\Requests\ExitRequest;
use KiltSdkPhp\Responses\ConnectResponse;
use KiltSdkPhp\Responses\DisconnectResponse;
use KiltSdkPhp\Responses\ExitResponse;

class KiltSdk
{
    public function __construct()
    {
        $this->processHelper = new ProcessHelper();

        $this->did = new Did($this->processHelper);
    }

    private ProcessHelper $processHelper;

    public Did $did;

    public function connect(?string $uri = null): ConnectResponse
    {
        $response = $this->processHelper->transmit(new ConnectRequest($uri));
        assert($response instanceof ConnectResponse);
        return $response;
    }

    public function disconnect(): DisconnectResponse
    {
        $response = $this->processHelper->transmit(new DisconnectRequest());
        assert($response instanceof DisconnectResponse);
        return $response;
    }

    public function exit(): ExitResponse
    {
        $response = $this->processHelper->transmit(new ExitRequest());
        assert($response instanceof ExitResponse);
        return $response;
    }
}
