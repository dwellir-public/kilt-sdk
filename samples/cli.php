<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KiltSdkPhp\KiltSdk;
use KiltSdkPhp\Modules\Did\DidSignatureVerificationInput;

$kilt = new KiltSdk();

// try to talk to the backend without first connecting
// this will throw an exception
try {
    $res = $kilt->did->queryByWeb3Name('johanmlg');
    echo json_encode($res) . "\n\n";
} catch (\Exception $e) {
    echo json_encode($e->getMessage()) . "\n\n";
}

// connect
$res = $kilt->connect('wss://spiritnet.kilt.io');
echo json_encode($res) . "\n\n";

// redo the request, this will work
$res = $kilt->did->queryByWeb3Name('johanmlg');
echo json_encode($res) . "\n\n";

// but better check, just in case
if (!isset($res->response)) {
    throw new \Exception('web3name not found');
}

// i think there is an sdk function for this that should be used
$ident = 'did:kilt:' . $res->response->identifier;

// another call
$res = $kilt->did->resolve($ident);
echo json_encode($res) . "\n\n";

// verifying a signature
$res = $kilt->did->verifyDidSignature(new DidSignatureVerificationInput([
    'keyUri' => 'did:kilt:4qdRc9FnUcUQnohEeQp8638LVagmAcMnGyCGKgAaVpvePTN3#0x6fdaca9b542e3f97fcf843681b11caa41fb72ae96383ca086b39e0fe22655f1e',
    'message' => 'test',
    'signature' => '0xc882887804f890d60effe1a5bff8c0291d93858cf33f5de7d9c9de77e28b4e5b825014bcd590f7e5ee05ee1dc694935ab8d337f3f38ed350b24cf31eab833a86',
    'expectedSigner' => $ident
]));
echo json_encode($res) . "\n\n";

// if the signature isnt correct the verifyDidSignature throws an exception.
try {
    $res = $kilt->did->verifyDidSignature(new DidSignatureVerificationInput([
        'keyUri' => 'did:kilt:4qdRc9FnUcUQnohEeQp8638LVagmAcMnGyCGKgAaVpvePTN3#0x6fdaca9b542e3f97fcf843681b11caa41fb72ae96383ca086b39e0fe22655f1e',
        'message' => 'testa',
        'signature' => '0xc882887804f890d60effe1a5bff8c0291d93858cf33f5de7d9c9de77e28b4e5b825014bcd590f7e5ee05ee1dc694935ab8d337f3f38ed350b24cf31eab833a86',
        'expectedSigner' => $ident
    ]));
    echo json_encode($res) . "\n\n";
} catch (\Exception $e) {
    echo json_encode('Could not verify signature') . "\n\n";
}

// when were done, disconnect
$res = $kilt->disconnect();
echo json_encode($res) . "\n\n";

// and we also need to explicitly shut own the node process
$res = $kilt->exit();
echo json_encode($res) . "\n\n";
