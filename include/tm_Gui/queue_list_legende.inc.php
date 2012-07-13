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

//Legende
$_MAIN_OUTPUT.="<br><b><a href=\"#\" title=\"".___("Legende / Status ein-ausblenden")."\" id=\"toggle_legende\">".tm_icon("rainbow.png",___("Legende / Status"))."&nbsp;".___("Legende / Status")."</a></b>\n";
$_MAIN_OUTPUT.= "<div id=\"legende\" class=\"legende\">\n";
$_MAIN_OUTPUT.= "<h3>".___("Legende")."</h3>\n";
$_MAIN_OUTPUT.= tm_icon("arrow_refresh.png",___("Ansicht aktualisieren"))."&nbsp;".___("Ansicht aktualisieren")."<br>\n";
$_MAIN_OUTPUT.= "<strong>".___("Eigenschaften")."</strong><br>\n";
$_MAIN_OUTPUT.= tm_icon("ruby.png",___("Blacklist prüfen"))."&nbsp;".___("Blacklist prüfen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("medal_gold_1.png",___("Proofing"))."&nbsp;".___("Proofing")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_green.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png")."&nbsp;";
$_MAIN_OUTPUT.= tm_icon("bullet_yellow.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png")."&nbsp;";
$_MAIN_OUTPUT.= tm_icon("bullet_black.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png")."&nbsp;".___("Empfängerliste automatisch erstellen/aktualisieren und Q starten (geplant, aktiv, beendet)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("user_delete.png",___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)"))."&nbsp;".___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("user.png",___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)"))."&nbsp;".___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("user_add.png",___("Nur an Touch-Opt-In Adressen senden."))."&nbsp;".___("Nur an Touch-Opt-In Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)")."<br>\n";

$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___("Inline Images deaktiviert"),"","","","picture.png")."&nbsp;".___("Inline Images deaktiviert")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_add.png",___("Inline Images (lokal)"),"","","","picture.png")."&nbsp;".___("Inline Images (lokal)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png")."&nbsp;".___("Inline Images")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("email_add.png",___("Eine Kopie ausgehender Mails wird auf dem IMAP Server gespeichert"))."&nbsp;".___("Eine Kopie ausgehender Mails wird auf dem IMAP Server gespeichert")."<br>\n";

$_MAIN_OUTPUT.= "<br><strong>".___("Aktionen")."</strong><br>\n";
$_MAIN_OUTPUT.= tm_icon("error_go.png",___("Q mit fehlgeschlagenen und übersprungenen Adressen neu starten"),"","","","")."&nbsp;".___("Q mit fehlgeschlagenen und übersprungenen Adressen neu starten")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("arrow_switch.png",___("Empfängerliste aktualisieren"),"","","","email_go.png")."&nbsp;".___("Adressen nachfassen / Empfängerliste aktualisieren")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("script.png",___("Logfile anzeigen")).tm_icon("script_lightning.png",___("Logfile anzeigen"))."&nbsp;".___("Logfile anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("script_delete.png",___("Logfile loeschen"))."&nbsp;".___("Logfile löschen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_star.png",___("Diesen Eintrag versenden"),"","","","email_go.png")."&nbsp;".___("Diesen Eintrag versenden")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("control_stop.png",___("Anhalten"))."&nbsp;".___("Anhalten")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("control_play.png",___("Fortfahren"))."&nbsp;".___("Fortfahren")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("cross.png",___("Löschen"))."&nbsp;".___("Q löschen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___("Q komplett Löschen"),"","","","cross.png")."&nbsp;".___("Q Komplett löschen, auch Historie")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("script.png",___("Logbuch anzeigen"))."&nbsp;".___("Logbuch anzeigen")."<br>\n";
$_MAIN_OUTPUT.= "<br><strong>".___("Status")."</strong><br>\n";
$sc=count($STATUS['q']['status']);
for ($scc=1; $scc<=$sc; $scc++)//0
{
	$_MAIN_OUTPUT.= tm_icon($STATUS['q']['statimg'][$scc], display($STATUS['q']['descr'][$scc]))."&nbsp;".display($STATUS['q']['descr'][$scc])."<br>\n";
}
$_MAIN_OUTPUT.= "</div><br><br>\n";
$_MAIN_OUTPUT.= "<script type=\"text/javascript\">\n";
if ($user_is_expert) {
	$_MAIN_OUTPUT.= "	//switchSection('legende');\n".
					"toggleSlide('toggle_legende','legende',1);\n";
} else {
	$_MAIN_OUTPUT.= "	toggleSlide('toggle_legende','legende',0);\n";
}
$_MAIN_OUTPUT.= "</script>\n";
?>