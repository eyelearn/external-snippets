# Variante 1:

## Statische Daten:
1. tokenversion - typ: int |-> kann hardgecoded werden! - ist aktuell 1
2. masterID - typ: int |-> sollte nicht hardgecoded werden -> config File!
3. secret - base64 encoded value |-> sollte nicht hardgecoded werden -> config File!
4. secret + masterID sind für jeden benutzen origin verschieden! Auch für Testserver etc.

## Dynamische Daten:
1. Für jeden Benutzer im Moodle / ... -Sytsem muss der eyelearnnutzername hinterlegt werden. Dieser ist ein String mit maximaler länge 255.


## Generierung der URL:
1. Hole eylearn nutzername von aktuell eingeloggten Nutzer -> username
2. Ist username leer oder null -> gebe Meldung aus "wende dich an ... um eylearn Nutzen zu können" oder zeige Button / Iframe etc. nicht an

3. Erzeuge ein JWT. Siehe https://datatracker.ietf.org/doc/html/rfc7519 und jwt.io wie das geht und nutze eine entsprechende Bibliothek !!SCHREIBE EINEN JWT-GENERATOR NICHT SELBER!! - Für PHP nutzen wir https://github.com/lcobucci/jwt aber jede Bibliothek die HS512 bzew SHA512 unterstützt ist möglich.
3.1 Nutze für den JWT HS512 bzw SHA512
3.2 Als key nutze secret
3.3 Schreibe in den Header als feld tokenversion den wert tokenversion
3.4 Als issued by (iss) setze den Origin der Seite (ohne / am ende)
3.5 Als audience (aud) setze https://eyesee.eyelearn.at
3.6 Setze issued at (iat) setze den jetzigen Zeitstempel (Aktuelle Unix Zeit)
3.7 Setze expires at (eat) auf jetzt +5min
3.8 Setze als Claim username den username.
4. Speichere den JWT local as jwt
5. Erzeuge eine URL wie folgt:
5.1 Origin ist https://eyesee.eyelearn.at
5.2 GET parameter sind:
5.2.1 action: externallogin
5.2.2 master: masterID
5.2.3 version: tokenversion
5.2.4 token: jwt
6. Gebe url zurück

## Iframe einbindung:
1. hole die url wie bei "Generierung der URL" beschrieben
2. Erzeuge das iframe mit
2.1 src=url
2.2 referrerpolicy="origin-when-cross-origin"
2.3 allow="fullscreen autoplay picture-in-picture web-share" es kann da noch was hinzukommen. Das wird sich bei tests zeigen.
2.4 sandbox darf NICHT gesetzt werden

##Optimale Button / Link einbindung (theoretisch kann die Zwischenurl weggelassen werden)
Es wird eine URL festgelegt die als Zwischenschritt aufgerufen wird. z.B. https://moodle.partner.com/eylearn-weiterleitung

### Aufruf der Zwischenurl:
1. Gerneriere url wie bei "Generierung der URL" beschrieben
2. Leite mit http code 307 weiter zu url

### Einbindung des Buttons
1. Erzeuge Button
2. Bei click des Buttons leite zur zwischenurl weiter
3. Stelle sicher das rel="noopener" oder die entsprechende JS option gesetzt ist.





