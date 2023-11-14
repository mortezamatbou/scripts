<?php

use Jose\Factory\JWEFactory;
use Jose\Factory\JWKFactory;
use Jose\Loader;

function is_expire($token)
{
    $current_time = time();
    $exp = $token['exp'];
    $nbf = $token['nbf'];

    if ($current_time < $nbf) {
        return TRUE;
    }

    if ($current_time > $exp) {
        return TRUE;
    }

    return FALSE;
}

function encJWE($data)
{
    // We create our key object (JWK) using a public RSA key stored in a file
    // Additional parameters ('kid' and 'use') are set for this key.
    $key = JWKFactory::createFromValues([
        'kty' => 'oct',
        'k' => JWKCode,
        'alg' => 'A256GCM',
    ]);

    // We want to encrypt a very important message
    $json = json_encode($data);
    $jweEnc = JWEFactory::createJWEToCompactJSON(
        $json,
        $key,
        [
            'alg' => 'dir',
            'enc' => 'A256GCM',
            'zip' => 'DEF',
        ]
    );
    return $jweEnc;
}

function decJWE($data)
{
    $key = JWKFactory::createFromValues([
        'kty' => 'oct',
        'k' => JWKCode,
        'alg' => 'A256GCM'
    ]);

    $input = $data;

    try {
        $loader = new Loader();
        $jwe = $loader->loadAndDecryptUsingKey(
            $input,
            $key,
            ['dir'],
            ['A256GCM']

        );
        $payload = $jwe->getPayload();
    } //catch exception
    catch (Exception $e) {
        $payload = false;
    }


    return $payload;
}
