'use strict';

const crypto = require('crypto');

const AES_METHOD = 'aes-256-cbc';
const IV_LENGTH = 16; // For AES, this is always 16, checked with php

const password = 'lbwyBzfgzUIvXZFShJuikaWvLJhIVq36'; // Must be 256 bytes (32 characters)

exports.encrypt = function (message, password) {
    if (crypto.constants.OPENSSL_VERSION_NUMBER <= 268443727) {
    throw new Error('OpenSSL Version too old, vulnerability to Heartbleed');
    }
    const key = crypto.createHash('sha256').update(password).digest('hex').substr(0, 32);
    const iv = key.substr(0, 16);
    const cipher = crypto.createCipheriv('aes-256-cbc', Buffer.from(key), Buffer.from(iv));
    let encrypted = cipher.update(message, 'utf8', 'base64');
    encrypted += cipher.final('base64');
    return Buffer.from(encrypted).toString('base64');
};

exports.decrypt =  function (text, password) {
    const key = crypto.createHash('sha256').update(password).digest('hex').substr(0, 32);
    const iv = key.substr(0, 16);

    const encryptedText = Buffer.from(text, 'base64').toString('ascii');

    let decipher = crypto.createDecipheriv('aes-256-cbc', Buffer.from(key), Buffer.from(iv));
    decipher.update(encryptedText, 'base64');
    const final = decipher.final('utf8');
    return final;
}