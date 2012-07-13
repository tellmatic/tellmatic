<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/7 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

$_MAIN_DESCR=___("Versand manuell starten");

$_MAIN_OUTPUT.= "<strong>".___("Manueller Versand")."</strong><br>";

if (!$user_is_manager) {
	$_MAIN_MESSAGE.="<br>".___("Sie haben keine Berechtigung dies zu tun");
	$LOGIN->Logout();
	//hilft aber nix gegen aufruf direkt.....
}

if ($user_is_manager) {
	###
	$tmp_scriptname="send_it.php";
	###
	
	$_MAIN_OUTPUT.="<br>".sprintf(___("Sie möchten das Script %s über den Browser ausführen."),"<em>".$tmp_scriptname."</em>");
	$_MAIN_OUTPUT.="<br><br>".___("Bevor Sie die Datei ausführen können, müssen Sie den exit; Befehl in der Datei  entfernen und die Einstellugen überprüfen.");
	$_MAIN_OUTPUT.="<br><br>".___("Es wird empfohlen das Sie das Script über einen sogenannten Cronjob per PHP-Command-Line-Interface (PHP-CLI) ausführen.");
	$_MAIN_OUTPUT.="<br><br>".___("Zum Ausführen des Scripts per Cronjob ist es unbedingt erforderlich das Sie den Pfad zur Tellmatic Konfigurationsdatei angeben.");
	if (!tm_DEMO()) $_MAIN_OUTPUT.="<br><br>".sprintf(___("Der vollständige Pfad lautet: %s"),"<br><br><em>".TM_INCLUDEPATH."/tm_config.inc.php</em>");
	if (!tm_DEMO()) $_MAIN_OUTPUT.="<br><br>".___("Beispiel für einen Cronjob:");
	if (!tm_DEMO()) $_MAIN_OUTPUT.="<br><br><em>*/5 * * * * /usr/bin/php-cli ".TM_INCLUDEPATH."/".$tmp_scriptname."</em>";
	###
	$tmp_link1="<a href=\"http://www.tellmatic.org/\" title=\"INSTALL.DE/EN\" target=\"_blank\">INSTALL</a>";
	$tmp_link2="<a href=\"http://doc.tellmatic.org/\" title=\"doc.tellmatic.org\" target=\"_blank\">doc.tellmatic.org</a>";
	###
	$_MAIN_OUTPUT.="<br><br>".sprintf(___("Weitere Infos hierzu finden Sie in der %s Datei oder unter %s."),$tmp_link1,$tmp_link2);
	$tmp_url3=$tm_URL_FE."/".TM_INCLUDEDIR."/".$tmp_scriptname;
	$tmp_link3="<a href=\"".$tmp_url3."\" title=\"".$tmp_scriptname."\" target=\"_blank\">".$tmp_scriptname."</a>";
	$tmp_link4="<a href=\"".$tmp_url3."\" title=\"".$tmp_scriptname."\" target=\"_blank\">".$tmp_url3."</a>";
	$_MAIN_OUTPUT.="<br><br>".sprintf(___("Wenn Sie jetzt das Script %s über den Browser starten wollen, klicken Sie hier:"),"<em>".$tmp_scriptname."</em>");
	$_MAIN_OUTPUT.="<center><br>".$tmp_link3;
	$_MAIN_OUTPUT.="<br>".$tmp_link4;
	$_MAIN_OUTPUT.="<br></center><br>".___("Sie müssen ggf. Ihren Benutzernamen und Passwort erneut eingeben.");
}//user_is_manager
?>