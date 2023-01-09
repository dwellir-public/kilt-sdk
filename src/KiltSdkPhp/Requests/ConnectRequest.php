<?php

namespace KiltSdkPhp\Requests;

use Assert\Assert;
use Exception;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class ConnectRequest extends BaseRequest
{
    protected ?string $blockchainRpcWsUrl;

    public function __construct(string $blockchainRpcWsUrl = null)
    {
        parent::__construct('connect');

        $this->addString('blockchainRpcWsUrl', $blockchainRpcWsUrl, true);
    }
}
