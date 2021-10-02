# Variante 1:

## Statische Daten:
1. tokenversion - typ: int |-> kann hardgecoded werden! - ist aktuell 1
1. masterID - typ: int |-> sollte nicht hardgecoded werden -> config File!
1. secret - base64 encoded value |-> sollte nicht hardgecoded werden -> config File!
1. secret + masterID sind für jeden benutzen origin verschieden! Auch für Testserver etc.

## Dynamische Daten:
1. Für jeden Benutzer im Moodle / ... -Sytsem muss der eyelearnnutzername hinterlegt werden. Dieser ist ein String mit maximaler länge 251.


## Generierung der URL:
1. Hole eylearn nutzername von aktuell eingeloggten Nutzer -> username
1. Ist username leer oder null -> gebe Meldung aus "wende dich an ... um eylearn Nutzen zu können" oder zeige Button / Iframe etc. nicht an

1. Erzeuge ein JWT. Siehe https://datatracker.ietf.org/doc/html/rfc7519 und jwt.io wie das geht und nutze eine entsprechende Bibliothek !!SCHREIBE EINEN JWT-GENERATOR NICHT SELBER!! - Für PHP nutzen wir https://github.com/lcobucci/jwt aber jede Bibliothek die HS512 bzew SHA512 unterstützt ist möglich.
1.1 Nutze für den JWT HS512 bzw SHA512
1.1 Als key nutze secret
1.1 Schreibe in den Header als feld tokenversion den wert tokenversion
1.1 Als issued by (iss) setze den Origin der Seite (ohne / am ende)
1.1 Als audience (aud) setze https://eyesee.eyelearn.at
1.1 Setze issued at (iat) setze den jetzigen Zeitstempel (Aktuelle Unix Zeit)
1.1 Setze expires at (eat) auf jetzt +5min
1.1 Setze als Claim username den username.
1. Speichere den JWT local as jwt
1. Erzeuge eine URL wie folgt:
1.1 Origin ist https://eyesee.eyelearn.at
1.1 GET parameter sind:
1.1.1 action: externallogin
1.1.1 master: masterID
1.1.1 version: tokenversion
1.1.1 token: jwt
1. Gebe url zurück

## Iframe einbindung:
1. hole die url wie bei "Generierung der URL" beschrieben
1. Erzeuge das iframe mit
1.1 src=url
1.1 referrerpolicy="origin-when-cross-origin"
1.1 allow="fullscreen autoplay picture-in-picture web-share" es kann da noch was hinzukommen. Das wird sich bei tests zeigen.
1.1 sandbox darf NICHT gesetzt werden

## Optimale Button / Link einbindung (theoretisch kann die Zwischenurl weggelassen werden)
Es wird eine URL festgelegt die als Zwischenschritt aufgerufen wird. z.B. https://moodle.partner.com/eylearn-weiterleitung

### Aufruf der Zwischenurl:
1. Gerneriere url wie bei "Generierung der URL" beschrieben
1. Leite mit http code 307 weiter zu url

### Einbindung des Buttons
1. Erzeuge Button
1. Bei click des Buttons leite zur zwischenurl weiter
1. Stelle sicher das rel="noopener" oder die entsprechende JS option gesetzt ist.

