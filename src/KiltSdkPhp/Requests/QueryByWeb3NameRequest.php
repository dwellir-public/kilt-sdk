<?php

namespace KiltSdkPhp\Requests;

use Assert\Assert;
use Exception;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class QueryByWeb3NameRequest extends BaseRequest
{
    protected string $name;

    public function __construct(string $name)
    {
        parent::__construct('did.queryByWeb3Name');

        $this->addString('name', $name);
    }
}
