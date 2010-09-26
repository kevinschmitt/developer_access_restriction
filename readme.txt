Developer Access Restriction | update_access.php | ?get=log
@version 0.7

Zum temporaeren Sperren eines Server-Verzeichnis gegen externe Zugriffe
Der Entwickler kann ungehindert arbeiten, indem er uber einen DynDNS-Dienst
seine IP diesem Script bekannt gibt. Das Skript generiert bei Aufruf eine 
.htaccess-Datei, welche alle Zugriffe blockt. Zugriffe von der ueber DynDNS
mitgeteilten IP koennen passieren. Alle nicht-authorisierten Zugreifer erhalten
einen Benutzername/Passwort-Dialog (HTTP-Authentifizierung).

Konfiguration:

1. DynDNS-Domain eintragen
2. relativer Server-Pfad zur Passwort-Datei eintragen
   ( Wer sich nicht mit Linux auskennt kann hier die Eintraege fuer die .htpasswd-Datei 
   erstellen: @link http://p4r.ch/htpasswd )
3. Falls benoetigt: Nachricht fuer Passwort-Abfrage aendern.

Weitere Informationen & Vorgehensweisen:

- Zum Debuggen der aktuellen DynDNS-IP kann diese Datei 
  mit update_access.php?get=log aufgerufen werden
- Zum Aktualisieren der .htaccess-Datei mit der aktuellen DynDNS-IP
  muss das Skript direkt im Browser aufgerufen werden.
  Alternativ kann ein Cronjob angelegt werden.

Moegliche Probleme & Fehlerquellen:

- php_safemode ON kann das Skript behindern, die .htaccess-Datei zu schreiben.
- Datei-Berechtigungen (CHMOD): im Idealfall sollte ein CHMOD 644 für das Skript reichen
- Ist die .htaccess nach dem ersten Aufruf geschrieben? Im Zweifelsfall eine leere 
  .htaccess-Datei anlegen und die Berechtigung ebenfalls auf CHMOD 644 setzen.