<?php

/*
 * Ich benutze für die JWT Tokens https://github.com/lcobucci/jwt das ist von JWT Bibliotheken für PHP die mit den meisten Sternen auf GitHub (> 6000).
 * Generell sind auch andere Bibliotheken möglich. Wichtig ist, dass das Verfahren über SHA512 benutzt wird.
 * 
 * Der Inhalt von eyelearn_data wird sich noch verändern und es kann durchaus notwendig werden die Daten in der Zukunft mal anzupassen.
 * 
 * Kurze Erklärung 
 * 1. tokenversion brauchen wir damit wir auch an dem System weiterentwickeln können aber durch die versionsnummer trotzdem systeme auf einer "alten" version noch unterstützen können
 * 2. secret ist ein GEHEIMER code mit dem die JWT signiert werden. Er darf NICHT im versionskontrollsystem (git) oder für Nutzer zugänglich gespeichert werden. (Wir empfehlen eine extra Datei für die ganze konfiguration mit .gitignore oder eine .env Datei)
 * 3. masterID ist bei uns intern eine nummer um die Daten schneller zuzuordnen.
 * 4. hostedOn sollte der origin der Seite sein in die das IFrame eingebunden ist. Wir haben da einen kleinen zusätzlichen Schutz.
 * 
 * Das IFrame benötigt Attribute die (außer src) zu setzen bzw. nicht zu setzen sind:
 * 1. referrerpolicy="origin-when-cross-origin"
 * 2. allow="fullscreen autoplay picture-in-picture web-share" es kann da noch was hinzukommen. Das wird sich bei tests zeigen.
 * 3. sandbox darf NICHT gesetzt werden auch nicht leer.
 */

chdir(__DIR__);
require '../vendor/autoload.php';

use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;

// VORLÄUFIGE DATEN.
// ECHTE DATEN DÜRFEN NICHT INS GIT / ÖFFENTLICH SEIN!
$eyelearn_data = [
    "tokenversion" => 1,
    "secret" => "Secret-Key",
    "masterID" => 42,
    "hostedOn" => 'https://moodle.mydomain.at'
];

function generateToken(string $username)
{
    global $eyelearn_data;

    $configuration = Configuration::forSymmetricSigner(new Sha512(), InMemory::plainText($eyelearn_data['secret']));

    $now = new DateTimeImmutable();

    $token = $configuration->builder()
        ->issuedBy($eyelearn_data['hostedOn'])
        ->permittedFor('https://eyesee.eyelearn.at')
        ->issuedAt($now)
        // 5min reichen vollkommen aus muss nur für den Login vorgang valid sein.
        ->expiresAt($now->modify('+5 minutes'))
        ->withClaim('username', $username)
        ->withHeader('tokenVersion', $eyelearn_data['tokenversion'])
        ->getToken($configuration->signer(), $configuration->signingKey());

    return $token->toString();
}


function generateUrl(string $username)
{
    global $eyelearn_data;

    return "https://eyesee.eyelearn.at/?action=externallogin&master=" . $eyelearn_data['masterID'] . '&token=' . generateToken($username) . '&version=' . $eyelearn_data['tokenversion'];
}


echo generateUrl('user1234');
