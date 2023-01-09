<?php

namespace KiltSdkPhp\Modules\Did;

use KiltSdkPhp\ProcessHelper;
use KiltSdkPhp\Requests\QueryByWeb3NameRequest;
use KiltSdkPhp\Requests\ResolveRequest;
use KiltSdkPhp\Requests\VerifyDidSignatureRequest;
use KiltSdkPhp\Modules\Did\DidSignatureVerificationInput;
use KiltSdkPhp\Responses\QueryByWeb3NameResponse;
use KiltSdkPhp\Responses\VerifyDidSignatureResponse;
use KiltSdkPhp\Responses\ResolveResponse;

class Did
{
    public function __construct(ProcessHelper $processHelper)
    {
        $this->processHelper = $processHelper;
    }

    private ProcessHelper $processHelper;

    public function resolve(string $did): ResolveResponse
    {
        $response = $this->processHelper->transmit(new ResolveRequest($did));
        assert($response instanceof ResolveResponse);
        return $response;
    }

    public function queryByWeb3Name(string $name): QueryByWeb3NameResponse
    {
        $response = $this->processHelper->transmit(new QueryByWeb3NameRequest($name));
        assert($response instanceof QueryByWeb3NameResponse);
        return $response;
    }

    public function verifyDidSignature(DidSignatureVerificationInput $input): VerifyDidSignatureResponse
    {
        $response = $this->processHelper->transmit(new VerifyDidSignatureRequest($input));
        assert($response instanceof VerifyDidSignatureResponse);
        return $response;
    }
}
