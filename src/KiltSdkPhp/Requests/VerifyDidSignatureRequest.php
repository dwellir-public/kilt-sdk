<?php

namespace KiltSdkPhp\Requests;

use Assert\Assert;
use KiltSdkPhp\Modules\Did\DidSignatureVerificationInput;

/**
* @SuppressWarnings(PHPMD.UnusedPrivateField)
* @SuppressWarnings(PHPMD.LongVariable)
*/
class VerifyDidSignatureRequest extends BaseRequest
{
    protected ?bool $allowUpgraded;
    protected ?string $didResolveKey; // DidResolveKey
    protected ?string $expectedSigner; // DidUri
    /** @var array<string, 'authentication' | 'capabilityDelegation' | 'assertionMethod'> */
    protected ?array $expectedVerificationMethod; //VerificationKeyRelationship
    protected string $keyUri; // DidResourceUri
    protected string $message; // string | Uint8Array;
    protected string $signature; // Uint8Array

    public function __construct(DidSignatureVerificationInput $input)
    {
        parent::__construct('did.verifyDidSignature');

        $this->addBool('allowUpgraded', $input->allowUpgraded, true);
        $this->addString('didResolveKey', $input->didResolveKey, true);
        $this->addString('expectedSigner', $input->expectedSigner, true);
        $this->addArray('expectedVerificationMethod', $input->expectedVerificationMethod, true);
        $this->addString('keyUri', $input->keyUri);
        $this->addString('message', $input->message);
        $this->addString('signature', $input->signature);
    }
}
