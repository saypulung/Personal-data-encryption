<?php

define('AES_METHOD', 'aes-256-cbc');

function encrypt($message)
{
    if (OPENSSL_VERSION_NUMBER <= 268443727) {
        throw new RuntimeException('OpenSSL Version too old, vulnerability to Heartbleed');
    }
    $key = substr(hash('sha256', $_ENV['ENCRYPTION_KEY']), 0, 32);
    $iv = substr($key, 0, 16);
    $ciphertext     = openssl_encrypt($message, AES_METHOD, $key, 0, $iv);
    return base64_encode($ciphertext);
}
function decrypt($ciphered) {
    $key = substr(hash('sha256', $_ENV['ENCRYPTION_KEY']), 0, 32);
    $iv = substr($key, 0, 16);
    return openssl_decrypt(base64_decode($ciphered), AES_METHOD, $key, 0, $iv);
}



function performLikeSearch(String $url, String $value) : array {
    $curlInit = curl_init($url);
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
    $apiResponse = curl_exec($curlInit);
    curl_close($curlInit);
    return json_decode($apiResponse, true);
}

function sendToBlindServer(String $url, array $data, String $method = 'post') : bool {
    $curlInit = curl_init($url);
    switch ( strtolower($method)) {
        case 'post':
            curl_setopt($curlInit, CURLOPT_POST, 1);
            break;
        case 'put':
        case 'delete':
        case 'patch':
            curl_setopt($curlInit, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            break;
        default:
            break;
    }
    curl_setopt($curlInit, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlInit, CURLOPT_VERBOSE, true);

    $apiResponse = curl_exec($curlInit);
    $code = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
    curl_close($curlInit);
    return $apiResponse === 'OK';
}