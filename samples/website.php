<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KiltSdkPhp\KiltSdk;
use KiltSdkPhp\Modules\Did\DidSignatureVerificationInput;
use KiltSdkPhp\Responses\QueryByWeb3NameResponse;
use KiltSdkPhp\Responses\VerifyDidSignatureResponse;

session_start();

if (!isset($_GET['method'])) {
    ?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>KILT sample login site</title>

</head>

<body>
  <div id="message"></div>
  <script src="https://unpkg.com/@kiltprotocol/sdk-js@dev/dist/sdk-js.min.umd.js"></script>
  <script
    src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
    crossorigin="anonymous"></script>
  <script>
    $.get({
        url: '?method=getUsername',
        success: function(res) {
            // need to authenticate
            if (res == '') {
                const username = prompt("Enter your username");
                $.get({
                    url: '?method=getChallenge&username=' + username,
                    success: function(res) {

                        kilt.sporran.signWithDid(res).then( function(signature) {
                            $.get({
                                url: '?method=authenticate&response=' + btoa(JSON.stringify(signature)),
                                success: function(res) {
                                    if(res == '1') {
                                        $.get({
                                            url: '?method=getUsername',
                                            success: function(res) {
                                                $('#message').text('Welcome back ' + res + '. ');
                                                $('<a>').attr('href', '#').text('logout').click(e => {
                                                  e.preventDefault();
                                                  $.get('?method=logout').then($('#message').text('logged out'));
                                                }).appendTo($('#message'));
                                            }
                                        });
                                    } else {
                                        $('#message').text('Auth failed');
                                    }
                                }
                            });
                        });
                    }
                });
            // already authenticated
            } else {
              $('#message').text('Welcome back ' + res + '. ');
              $('<a>').attr('href', '#').text('logout').click(e => {
                e.preventDefault();
                $.get('?method=logout').then($('#message').text('logged out'));
              }).appendTo($('#message'));
            }
        }
    });
  </script>
</body>
</html>

    <?php
    exit;
}

if ($_GET['method'] == 'getChallenge') {
    $username = $_GET['username'];
    $timestamp = time();
    $salt = bin2hex(random_bytes(128));
    $challenge = hash('sha256', $username . $timestamp . $salt);
    session_unset();
    $_SESSION['username'] = $username;
    $_SESSION['timestamp'] = $timestamp;
    $_SESSION['chanllenge'] = $challenge;
    $_SESSION['authenticated'] = false;
    echo $challenge;
} elseif ($_GET['method'] == 'logout') {
    session_unset();
} elseif ($_GET['method'] == 'authenticate') {
    $response = json_decode(base64_decode($_GET['response']));

    $kilt = new KiltSdk();
    $res = $kilt->connect('wss://spiritnet.kilt.io');

    $res = $kilt->did->queryByWeb3Name($_SESSION['username']);
    $ident = 'did:kilt:' . $res->response->identifier;

    $keyUri = $response->didKeyUri;
    $message = $_SESSION['chanllenge'];
    $signature = $response->signature;

    $res = $kilt->did->verifyDidSignature(new DidSignatureVerificationInput([
        'keyUri' => $keyUri,
        'message' => $message,
        'signature' => $signature,
        'expectedSigner' => $ident
    ]));
    $kilt->disconnect();
    $kilt->exit();

    $_SESSION['authenticated'] = (int)($res->status === 'success');
    echo $_SESSION['authenticated'] ? '1' : '0';
} elseif ($_GET['method'] == 'getUsername') {
    if ($_SESSION['authenticated']) {
        echo $_SESSION['username'];
    } else {
        echo '';
    }
}

