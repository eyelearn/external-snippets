# Version 1

> Automatischer login über Link oder iframe.

## Install
Ausführen von.

```sh
composer install
```

## Benutzung


Das Snippet enthält 2 Funktionen:

```php
function generateToken(string $username):string {/*...*/}
function generateUrl(string $username):string {/*...*/}
```

Beide funktionen erhalten als Argument als String den Bennutzernamen aus dem eyelearn System. Und geben einen String zurück.

Wichtig ist von den beiden Funktionen vorallem `generateUrl` diese Funktion ist beim erstellen eines Buttons zu eyelearn oder dem Anzeigen eines iframes aufzurufen. `generateUrl` gibt dabei direkt eine URL zurück die so in das `href` eines Links oder `src` des iframes geschrieben werden können.

Das beschaffen vom Username des entsprechenden aktuell eingeloggten Nutzer ist selber zu programmieren.

### iframe
Wir benötigen einige Rechte um das beste Erlebnis in einem iframe den Nutzer anzubieten. Daher sind 3 Attribute zu beachten:

1. referrerpolicy="origin-when-cross-origin"
1. allow="fullscreen autoplay picture-in-picture web-share" es kann da noch was hinzukommen. Das wird sich bei tests zeigen.
1. sandbox darf NICHT gesetzt werden auch nicht leer.


### Link
Hier sind 2 Attribute zu setzen:
1. referrerpolicy="origin-when-cross-origin"
1. rel="noopener"

## JWT-Bibliothek
benutze für die JWT Tokens https://github.com/lcobucci/jwt das ist von JWT Bibliotheken für PHP die mit den meisten Sternen auf GitHub (> 6000).
Generell ist diese Bibliothek austauschbar und auch andere Bibliotheken könnten genutzt werden. Wichtig ist, dass das Verfahren über SHA512 benutzt wird und alle Daten wie im snippet gesetzt werden.

Wir laden die Bibliothek über composer.

Sie kann installiert werden mit dem Befehl
```sh
composer require lcobucci/jwt
```

## Config
Wir haben einige config-Daten die zum generieren Notwendig sind.

Diese sind in der Variablen `$EYELEARN_DATA` gespeichert.

Wofür sind die einzelnen Werte?
1. tokenversion brauchen wir damit wir auch an dem System weiterentwickeln können aber durch die versionsnummer trotzdem systeme auf einer "alten" version noch unterstützen können
1. secret ist ein GEHEIMER code mit dem die JWT signiert werden. Er darf NICHT im versionskontrollsystem (git) oder für Nutzer zugänglich gespeichert werden. (Wir empfehlen eine extra Datei für die ganze konfiguration mit .gitignore oder eine .env Datei)
1. masterID ist bei uns intern eine nummer um die Daten schneller zuzuordnen.
1. hostedOn sollte der origin der Seite sein in die das IFrame eingebunden ist. Wir haben da einen kleinen zusätzlichen Schutz.

Diese Daten hier sind nur testdaten diese werden später über z.B. https://onetimesecret.com/ ausgetauscht.

### Speicherort
Die Daten in `$EYELEARN_DATA` sind NICHT in einer Versionskontrolle (z.B. git) oder öffentlich zu speichern.

> Tipp: Nutzt eine extra php Datei für die Daten und .gitignore oder eine .env Datei.


