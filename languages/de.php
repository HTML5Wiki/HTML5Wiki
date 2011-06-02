<?php
/**
 * This file is part of the HTML5Wiki Project.
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mweibel@hsr.ch so we can send you a copy immediately.
 *
 * @author Michael Weibel <mweibel@hsr.ch>
 * @copyright (c) HTML5Wiki Team 2011
 * @package Html5Wiki
 * @subpackage Language
 */

/**
 * German language file
 */
return array(
	'timestampFormat' => 'd.m.Y H:i'
	,'timeFormat' => 'H:i'
	,'dateFormat' => 'd.m.Y'
	
	,'read' => 'Lesen'
	,'edit' => 'Bearbeiten'
	,'history' => 'Änderungsgeschichte'
	,'homepage' => 'Startseite'
	,'recentChanges' => 'Neuste Änderungen'
	,'search' => 'Suche'
	,'searchResultsFor' => 'Suchergebnisse f&uuml;r "%s"'
	,'articleContentLegend' => 'Artikelinhalt'
	,'content' => 'Inhalt'
	,'save' => 'Speichern'
	,'deleteArticle' => 'Alle Versionen dieses Artikels l&ouml;schen'
	,'close' => 'Schliessen'
	
	,'title' => 'Titel'
	,'lastChanged' => 'Zuletzt ge&auml;ndert'
	
	,'tags' => 'Tags'
	,'tag' => 'Tag'
	,'searchForOtherObjectsWithTag' => 'Andere Objekte mit dem Tag \'%s\' suchen...'
	,'noArticleWithPermalink' => 'Kein Artikel mit dieser Adresse'
	,'desiredArticleWithPermalinkNotFound' => 'Der gew&uuml;nschte Artikel mit der Adresse \'%s\' konnte leider nicht gefunden werden.<br/>M&ouml;chten Sie einen neuen Artikel mit diesem Titel erstellen?'
	,'create' => 'Ja, ich m&ouml;chte einen neuen Artikel erstellen'
	,'noDontCreate' => 'Nein, danke'
	,'matchedOn' => 'Gefunden in'
	,'noSearchResultsTitle' => 'Nichts passendes gefunden'
	,'noSearchResultsText' => 'Leider konnte in der gesamten Wiki nichts passendes zum Begriff "%s" gefunden werden.<br/>Haben Sie einen &auml;hnlichen Begriff bereits versucht?'
	
	,'compareVersions' => 'Versionen vergleichen'
	,'bothVersionsAreEqual' => 'Die beiden Versionen sind identisch.'
	
	,'preview' => 'Vorschau'
	,'rollback' => 'Version wiederherstellen'
	,'rollbackTo' => 'Version vom %s wiederherstellen'
	,'rollbackToQuestion' => 'M&ouml;chten Sie die Version vom %s wirklich wiederherstellen?<br/>Die momentan aktuelle Version wird als alte Version abgelegt und geht somit nicht verloren.'
	,'yesRollback' => 'Ja, Version wiederherstellen'
	,'noDontRollback' => 'Nein, nichts unternehmen'
	,'restoredFrom' => 'wiederhergestellt von %s',
	
	'restore' => 'Wiederherstellen',
	'restoreQuestion' => 'M&ouml;chten Sie den urspr&uuml;nglichen Titel wiederherstellen?'
	
	,'yes' => 'Ja'
	,'no'  => 'Nein'
	,'close' => 'Schliessen'

	,'taggingLegend' => 'Tagging'
	,'taggingText' => 'Ein Artikel kann mit verschiedenen Tags versehen werden, welche sp&auml;ter das Auffinden &uuml;ber die Suche erleichtern k&ouml;nnen.<br/> <em>Tipp:</em> Geben Sie mehrere Tags auf einmal getrennt durch ein Komma ein und bestätigen Sie mit der <em>Eingabetaste</em>.'

	,'authorInformationLegend' => 'Autoreninformation'
	,'authorName' => 'Ihr Name'
	,'authorEmail' => 'Ihre E-Mailadresse'
	,'authorInformationText' => 'Ihr <em>Name</em> sowie Ihre <em>E-Mailadresse</em> werden nur zur internen Identifikation resp. Versionskontrolle abgelegt.<br/>Ihre Daten werden weder weitergegeben noch anderweitig ausgewertet.'

	,'versionCommentLegend' => 'Versionskommentar'
	,'versionCommentText' => 'Kommentar zur Version <em>(optional)</em>:'

	,'hasIntermediateVersionText' => '%s hat bereits eine neue Version zum aktuellen Artikel verfasst.<br/>M&ouml;chten Sie diese neue Version mit Ihren &Auml;nderungen ersetzen?'
	,'overwrite' => 'Mit meinen &Auml;nderungen ersetzen'
	,'rejectChanges' => 'Meine &Auml;nderungen verwerfen'
	,'newVersion' => 'Neuere Version'
	,'myVersion' => 'Meine Version'
		
	,'delete' => 'Alle Versionen l&ouml;schen'
	,'deleteQuestion' => 'M&ouml;chten Sie wirklich alle Versionen von "%s" l&ouml;schen? Der Artikel wird anschliessend nicht mehr zug&auml;nglich sein.'
	,'yesDelete' => 'Ja, alle Versionen l&ouml;schen'
	
	,'systemError' => 'Systemfehler'
	,'systemErrorText' => 'Leider kam es zu unerwarteten Problemen. Das ist aber nicht weiter schlimm: Auf der <a href="%s">Startseite</a> geht es sofort wieder weiter.'
	,'internalError' => 'Interner Fehler'
	,'internalErrorText' => 'Leider kam es zu unerwarteten Problemen. Das ist aber nicht weiter schlimm: Auf der <a href="%s">Startseite</a> geht es sofort wieder weiter.'
	,'additionalErrorInfo' => 'N&auml;here Fehlerinformationen'
	
	,'wrongInput' => 'Falsche Eingaben'
	
	,'newArticle' => 'Neuer Artikel'
	,'clickOnTitleToEdit' => 'Auf den Titel klicken um zu ihn zu bearbeiten.'
	
	// Zend_Validate_StringLength
    ,"Invalid type given. String expected" => "Ungültiger Typ angegeben. String erwartet"
    ,"'%value%' is less than %min% characters long" => "'%value%' ist weniger als %min% Zeichen lang"
    ,"'%value%' is more than %max% characters long" => "'%value%' ist mehr als %max% Zeichen lang"

	// Zend_Validate_Regex
    ,"Invalid type given. String, integer or float expected" => "Ungültiger Typ angegeben. String, Integer oder Float erwartet"
    ,"'%value%' does not match against pattern '%pattern%'" => "'%value%' scheint nicht auf das Pattern '%pattern%' zu passen"
    ,"There was an internal error while using the pattern '%pattern%'" => "Es gab einen internen Fehler bei der Verwendung des Patterns '%pattern%'"

	// Zend_Validate_NotEmpty
    ,"Invalid type given. String, integer, float, boolean or array expected" => "Ungültiger Typ angegeben. String, Integer, Float, Boolean oder Array erwartet"
    ,"Value is required and can't be empty" => "Es wird ein Wert benötigt. Dieser darf nicht leer sein"

	// Zend_Validate_Alpha
    ,"Invalid type given. String expected" => "Ungültiger Typ angegeben. String erwartet"
    ,"'%value%' contains non alphabetic characters" => "'%value%' enthält Zeichen welche keine Buchstaben sind"
    ,"'%value%' is an empty string" => "'%value%' ist ein leerer String"

	// Zend_Validate_EmailAddress
    ,"Invalid type given. String expected" => "Ungültiger Typ angegeben. String erwartet"
    ,"'%value%' is no valid email address in the basic format local-part@hostname" => "'%value%' ist keine gültige Emailadresse im Basisformat local-part@hostname"
    ,"'%hostname%' is no valid hostname for email address '%value%'" => "'%hostname%' ist kein gültiger Hostname für die Emailadresse '%value%'"
    ,"'%hostname%' does not appear to have a valid MX record for the email address '%value%'" => "'%hostname%' scheint keinen gültigen MX Eintrag für die Emailadresse '%value%' zu haben"
    ,"'%hostname%' is not in a routable network segment. The email address '%value%' should not be resolved from public network" => "'%hostname%' ist in keinem routebaren Netzwerksegment. Die Emailadresse '%value%' sollte nicht vom öffentlichen Netz aus aufgelöst werden"
    ,"'%localPart%' can not be matched against dot-atom format" => "'%localPart%' passt nicht auf das dot-atom Format"
    ,"'%localPart%' can not be matched against quoted-string format" => "'%localPart%' passt nicht auf das quoted-string Format"
    ,"'%localPart%' is no valid local part for email address '%value%'" => "'%localPart%' ist kein gültiger lokaler Teil für die Emailadresse '%value%'"
    ,"'%value%' exceeds the allowed length" => "'%value%' ist länger als erlaubt"

 	// Zend_Validate_Hostname
    ,"Invalid type given. String expected" => "Ungültiger Typ angegeben. String erwartet"
    ,"'%value%' appears to be an IP address, but IP addresses are not allowed" => "'%value%' scheint eine IP Adresse zu sein, aber IP Adressen sind nicht erlaubt"
    ,"'%value%' appears to be a DNS hostname but cannot match TLD against known list" => "'%value%' scheint ein DNS Hostname zu sein, aber die TLD wurde in der bekannten Liste nicht gefunden"
    ,"'%value%' appears to be a DNS hostname but contains a dash in an invalid position" => "'%value%' scheint ein DNS Hostname zu sein, enthält aber einen Bindestrich an einer ungültigen Position"
    ,"'%value%' appears to be a DNS hostname but cannot match against hostname schema for TLD '%tld%'" => "'%value%' scheint ein DNS Hostname zu sein, passt aber nicht in das Hostname Schema für die TLD '%tld%'"
    ,"'%value%' appears to be a DNS hostname but cannot extract TLD part" => "'%value%' scheint ein DNS Hostname zu sein, aber der TLD Teil konnte nicht extrahiert werden"
    ,"'%value%' does not match the expected structure for a DNS hostname" => "'%value%' passt nicht in die erwartete Struktur für einen DNS Hostname"
    ,"'%value%' does not appear to be a valid local network name" => "'%value%' scheint kein gültiger lokaler Netzerkname zu sein"
    ,"'%value%' appears to be a local network name but local network names are not allowed" => "'%value%' scheint ein lokaler Netzwerkname zu sein, aber lokale Netzwerknamen sind nicht erlaubt"
    ,"'%value%' appears to be a DNS hostname but the given punycode notation cannot be decoded" => "'%value%' scheint ein DNS Hostname zu sein, aber die angegebene Punycode Schreibweise konnte nicht dekodiert werden"

	// Zend_Validate_Alnum
    ,"'%value%' contains characters which are non alphabetic and no digits" => "'%value%' enthält Zeichen welche keine Buchstaben und keine Ziffern sind"
);
?>
