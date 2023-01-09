<?php

namespace KiltSdkPhp\Modules\Did;

/**
* @SuppressWarnings(PHPMD.LongVariable)
*/
class DidSignatureVerificationInput
{
    public ?bool $allowUpgraded;
    public ?string $didResolveKey; // DidResolveKey
    public ?string $expectedSigner; // DidUri
    /** @var array<string, 'authentication' | 'capabilityDelegation' | 'assertionMethod'> */
    public ?array $expectedVerificationMethod; //VerificationKeyRelationship
    public string $keyUri; // DidResourceUri
    public string $message; // string | Uint8Array;
    public string $signature; // Uint8Array

    /**
     * @param array<string, mixed> $input
     */
    public function __construct(array $input)
    {
        $this->allowUpgraded = $input['allowUpgraded'] ?? null;
        $this->didResolveKey = $input['didResolveKey'] ?? null;
        $this->expectedSigner = $input['expectedSigner'] ?? null;
        $this->expectedVerificationMethod = $input['expectedVerificationMethod'] ?? null;
        $this->keyUri = $input['keyUri'];
        $this->message = $input['message'];
        $this->signature = $input['signature'];
    }
}
