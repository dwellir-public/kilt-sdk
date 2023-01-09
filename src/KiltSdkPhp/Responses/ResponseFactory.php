<?php

namespace KiltSdkPhp\Responses;

use KiltSdkPhp\Exceptions\CommunicationErrorException;

class ResponseFactory
{
    public static function newResponse(\stdClass $source): BaseResponse
    {
        switch ($source->function) {
            case 'connect':
                return new ConnectResponse($source);
            case 'disconnect':
                return new DisconnectResponse($source);
            case 'exit':
                return new ExitResponse($source);
            case 'did.queryByWeb3Name':
                return new QueryByWeb3NameResponse($source);
            case 'did.resolve':
                return new ResolveResponse($source);
            case 'did.verifyDidSignature':
                return new VerifyDidSignatureResponse($source);
            default:
                throw new CommunicationErrorException('Recieved response of type ' . $source->function . ', but that type is not supported');
        }
    }
}
