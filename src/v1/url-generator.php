<?php

chdir(__DIR__);
require '../../vendor/autoload.php';

use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;

$EYELEARN_DATA = [
    "tokenversion" => 1,
    "secret" => "Secret-Key",
    "masterID" => 42,
    "hostedOn" => 'https://moodle.mydomain.at'
];

function generateToken(string $username):string {
    global $EYELEARN_DATA;

    $configuration = Configuration::forSymmetricSigner(new Sha512(), InMemory::plainText($EYELEARN_DATA['secret']));

    $now = new DateTimeImmutable();

    $token = $configuration->builder()
        ->issuedBy($EYELEARN_DATA['hostedOn'])
        ->permittedFor('https://eyesee.eyelearn.at')
        ->issuedAt($now)
        // 5min reichen vollkommen aus muss nur für den Login vorgang valid sein.
        ->expiresAt($now->modify('+5 minutes'))
        ->withClaim('username', $username)
        ->withHeader('tokenVersion', $EYELEARN_DATA['tokenversion'])
        ->getToken($configuration->signer(), $configuration->signingKey());

    return $token->toString();
}

function generateUrl(string $username):string {
    global $EYELEARN_DATA;

    return "https://eyesee.eyelearn.at/?action=externallogin&master=" . $EYELEARN_DATA['masterID'] . '&token=' . generateToken($username) . '&version=' . $EYELEARN_DATA['tokenversion'];
}

echo generateUrl('user1234');
