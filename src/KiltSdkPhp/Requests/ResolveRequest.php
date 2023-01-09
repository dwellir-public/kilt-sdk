<?php

namespace KiltSdkPhp\Requests;

use Assert\Assert;
use Exception;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class ResolveRequest extends BaseRequest
{
    protected string $did;

    public function __construct(string $did)
    {
        parent::__construct('did.resolve');

        $this->addString('did', $did);
    }
}
