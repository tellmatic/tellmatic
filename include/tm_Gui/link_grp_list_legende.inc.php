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
$_MAIN_OUTPUT.= "<br><strong>".___("Aktionen")."</strong><br>\n";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Gruppe ist Aktiv"))."&nbsp;";
$_MAIN_OUTPUT.= tm_icon("cancel.png",___("Gruppe ist Inaktiv"))."&nbsp;".___("Gruppe ist Aktiv/Inaktiv (Klick=Deaktivieren/Aktivieren)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("pencil.png",___("Bearbeiten"))."&nbsp;".___("Bearbeiten")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("link_add.png",___("Eintrag in dieser Gruppe erstellen"))."&nbsp;".___("Eintrag in dieser Gruppe erstellen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("link_go.png",___("Einträge in dieser Gruppe anzeigen"))."&nbsp;".___("Einträge in dieser Gruppe anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("page_white_go.png",___("Diese Gruppe als Standardgruppe definieren"))."&nbsp;".___("Diese Gruppe als Standardgruppe definieren")."<br>\n";

$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___(""),"","","","calculator.png")."&nbsp;".___("Klicks für Links in dieser Gruppe zurücksetzen auf 0 (Reset)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___(""),"","","","chart_line.png")."&nbsp;".___("Gesamte Klick-Statistik für Links in dieser Gruppe löschen (Flush)")."<br>\n";


$_MAIN_OUTPUT.= tm_icon("cross.png",___("Gruppe löschen und Einträge der Standardgruppe zuordnen"))."&nbsp;".___("Gruppe löschen und Einträge der Standardgruppe zuordnen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bomb.png",___("Gruppe löschen und Einträge der Gruppe löschen"))."&nbsp;".___("Gruppe löschen und Einträge der Gruppe löschen (Einträge werden komplett gelöscht und auch aus allen anderen Gruppen entfernt!)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("script.png",___("Logbuch anzeigen"))."&nbsp;".___("Logbuch anzeigen")."<br>\n";
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