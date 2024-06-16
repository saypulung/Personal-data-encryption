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



function performLikeSearch(array $cols, String $value) : array {

}

function sendToBlindServer(String $url, array $data) : bool {
    $curlInit = curl_init($url);
    curl_setopt($curlInit, CURLOPT_POST, 1);
    curl_setopt($curlInit, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

    $apiResponse = curl_exec($curlInit);
    curl_close($curlInit);
    return $apiResponse === 'OK';
}