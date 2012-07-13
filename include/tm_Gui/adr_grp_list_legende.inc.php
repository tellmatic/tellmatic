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
$_MAIN_OUTPUT.= "<strong>".___("Eigenschaften")."</strong><br>\n";
$_MAIN_OUTPUT.= tm_icon("page_white_lightning.png",___("Standardgruppe"))."&nbsp;".___("Standardgruppe (kann nicht de-aktiviert und gelöscht werden)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("cup.png",___("Gruppe ist öffentlich"))."&nbsp;".___("Gruppe ist öffentlich.")."<br>\n";

$_MAIN_OUTPUT.= "<br><strong>".___("Aktionen")."</strong><br>\n";

$_MAIN_OUTPUT.= tm_icon("tick.png",___("Gruppe ist Aktiv"))."&nbsp;";
$_MAIN_OUTPUT.= tm_icon("cancel.png",___("Gruppe ist Inaktiv"))."&nbsp;";
$_MAIN_OUTPUT.=___("Gruppe ist Aktiv/Inaktiv (Klick=Deaktivieren/Aktivieren)")."<br>\n";

$_MAIN_OUTPUT.= tm_icon("rosette.png",___("Gruppe ist Produktiv"))."&nbsp;";
$_MAIN_OUTPUT.= tm_icon("bullet_error.png",___("Nicht Produktiv"),"","","","rosette.png")."&nbsp;";
$_MAIN_OUTPUT.= ___("Gruppe ist Produktiv / nicht Produktiv (Klick=Ändern)")."<br>\n";

$_MAIN_OUTPUT.= tm_icon("pencil.png",___("Bearbeiten"))."&nbsp;".___("Bearbeiten")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("vcard_add.png",___("Adresse in dieser Gruppe erstellen"))."&nbsp;".___("Adresse in dieser Gruppe erstellen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("group_go.png",___("Adressen dieser Gruppe anzeigen"))."&nbsp;".___("Adressen dieser Gruppe anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("chart_pie.png",___("Statistik anzeigen"))."&nbsp;".___("Statistik anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("page_white_go.png",___("Diese Gruppe als Standardgruppe definieren"))."&nbsp;".___("Diese Gruppe als Standardgruppe definieren")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("cross.png",___("Gruppe löschen und Adressen der Standardgruppe zuordnen"))."&nbsp;".___("Gruppe löschen und Adressen der Standardgruppe zuordnen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bomb.png",___("Gruppe löschen und Adressen der Gruppe löschen"))."&nbsp;".___("Gruppe löschen und Adressen der Gruppe löschen (Adressen werden komplett gelöscht und auch aus allen anderen Gruppen entfernt!)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("hourglass_go.png",___("Q für diese Gruppe anzeigen"))."&nbsp;".___("Q für diese Gruppe anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("arrow_switch.png",___("Empfängerliste aktualisieren"),"","","","email_go.png")."&nbsp;".___("Adressen nachfassen / Empfängerliste aktualisieren")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("script.png",___("Logbuch anzeigen"))."&nbsp;".___("Logbuch anzeigen")."<br>\n";
$_MAIN_OUTPUT.= "</div><br><br>\n";
$_MAIN_OUTPUT.= "<script type=\"text/javascript\">\n";
if ($user_is_expert) {
	$_MAIN_OUTPUT.= "	//switchSection('legende');\n".
				"toggleSlide('toggle_legende','legende',1);";
} else {
	$_MAIN_OUTPUT.= "	toggleSlide('toggle_legende','legende',0);\n";
}
$_MAIN_OUTPUT.= "</script>\n";
?>