<?php
/**
 * Developer Access Restriction | update_access.php | ?get=log
 * @version 0.7
 * 
 * Zum temporaeren Sperren eines Server-Verzeichnis gegen externe Zugriffe
 * Der Entwickler kann ungehindert arbeiten, indem er uber einen DynDNS-Dienst
 * seine IP diesem Script bekannt gibt. Das Skript generiert bei Aufruf eine 
 * .htaccess-Datei, welche alle Zugriffe blockt. Zugriffe von der ueber DynDNS
 * mitgeteilten IP koennen passieren. Alle nicht-authorisierten Zugreifer erhalten
 * einen Benutzername/Passwort-Dialog (HTTP-Authentifizierung).
 *
 * Konfiguration:
 * 
 * 1. DynDNS-Domain eintragen
 * 2. relativer Server-Pfad zur Passwort-Datei eintragen
 *    ( Wer sich nicht mit Linux auskennt kann hier die Eintraege fuer die .htpasswd-Datei 
 *    erstellen: @link http://p4r.ch/htpasswd )
 * 3. Falls benoetigt: Nachricht fuer Passwort-Abfrage aendern.
 * 
 * Weitere Informationen & Vorgehensweisen:
 * 
 * - Zum Debuggen der aktuellen DynDNS-IP kann diese Datei 
 *   mit update_access.php?get=log aufgerufen werden
 * - Zum Aktualisieren der .htaccess-Datei mit der aktuellen DynDNS-IP
 *   muss das Skript direkt im Browser aufgerufen werden.
 *   Alternativ kann ein Cronjob angelegt werden.
 *
 * Moegliche Probleme & Fehlerquellen:
 *
 * - php_safemode ON kann das Skript behindern, die .htaccess-Datei zu schreiben.
 * - Datei-Berechtigungen (CHMOD): im Idealfall sollte ein CHMOD 644 für das Skript reichen
 * - Ist die .htaccess nach dem ersten Aufruf geschrieben? Im Zweifelsfall eine leere 
 *   .htaccess-Datei anlegen und die Berechtigung ebenfalls auf CHMOD 644 setzen.
 *
 *
 * Copyright (c) 2010  Kevin Schmitt
 *
 *    GNU GENERAL PUBLIC LICENSE
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @author Kevin Schmitt, kevin@parched-art.de @link http://blog.parched-art.de/
 * @tag dev, php, htaccess, htpasswd
 */
 

/**
 * DynDNS Domain welche von z.B. deinem Router geupdated wird 
 * (im Format subdomain.domain.tld, domain.tld etc.).
 *
 * @var string
 */
$dyndns = 'example.dyndns.org';

/**
 * Lokaler relativer Server-Pfad zur Passwort-Datei.
 * Sollte in einem nicht vom Web zugaenglichen Verzeichnis sein.
 *
 * @var string
 */
$local_path_to_pw_file = '/path/to/your/.htpasswd';

/**
 * Diese Nachricht wird in den meisten Browsern im Passwort-Eingabefeld mit angezeigt
 *
 * @var string
 */
$auth_name = 'Restricted Access';


/**
* Do not change below this line, 
* unless you know what you are doing!
* ============================================
*/


$dyndns = gethostbyname($dyndns);

if( isset($_GET['get']) && $_GET['get'] == 'log' ) { var_dump($dyndns); }

# make .htaccess
$htaccess_txt = 'Satisfy Any' . "\n";
$htaccess_txt .= 'order deny,allow' . "\n";
$htaccess_txt .= 'deny from all' . "\n";
$htaccess_txt .= 'require valid-user' . "\n";
$htaccess_txt .= 'allow from ' . $dyndns . "\n";
$htaccess_txt .= '# Last Update: ' . date('d.m.Y - H:i:s') . "\n";
$htaccess_txt .= '<Files ' . basename($_SERVER['PHP_SELF']) . '>' . "\n";
$htaccess_txt .= 'allow from all' . "\n";
$htaccess_txt .= '</Files>' . "\n";
$htaccess_txt .= '<Files .htaccess>' . "\n";
$htaccess_txt .= 'deny from all' . "\n";
$htaccess_txt .= '</Files>' . "\n";
$htaccess_txt .= 'AuthUserFile ' . $local_path_to_pw_file . "\n";
$htaccess_txt .= 'AuthName "' . $auth_name . '"' . "\n";
$htaccess_txt .= 'AuthType Basic' . "\n";

# save files
$htaccess= fopen('.htaccess', 'w');
fputs($htaccess, $htaccess_txt);
fclose($htaccess);
# output
?>