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
$_MAIN_OUTPUT.= tm_icon("page_white_lightning.png",___("Standardformular"))."&nbsp;".___("Standardformular (kann nicht de-aktiviert und gelöscht werden)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("user_green.png",___("Neuangemeldete Adressen sind aktiv"))."&nbsp;/&nbsp;<img src=\"".$tm_iconURL."/user_red.png\" border=\"0\" title=\"".___("Neuangemeldete Adressen sind inaktiv")."\" alt=\"".___("Neuangemeldete Adressen sind inaktiv")."\">&nbsp;".___("Neuangemeldete Adressen sind aktiv/deaktiviert")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("sport_8ball.png",___("Captcha Spamschutz aktiviert"))."&nbsp;".___("Captcha Spamschutz aktiviert")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("ruby.png",___("Blacklist prüfen"))."&nbsp;".___("Blacklist Überprüfung aktiv")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("medal_gold_1.png",___("Proofing aktiv"))."&nbsp;".___("Proofing aktiv")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("arrow_refresh.png",___("Double-Opt-In"))."&nbsp;".___("Double-Opt-In aktiviert")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("group_error.png",___("Auswahl öffentlicher Gruppen erzwingen"))."&nbsp;".___("Auswahl öffentlicher Gruppen erzwingen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("group_gear.png",___("öffentliche Gruppen Referenzen überschreiben"))."&nbsp;/&nbsp;". tm_icon("group_link.png",___("Nur neue öffentliche Gruppen Referenzen hinzufügen"))."&nbsp;".___("öffentliche Gruppen Referenzen überschreiben")."&nbsp;/&nbsp;".___("Nur neue öffentliche Gruppen Referenzen hinzufügen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("page_white_go.png",___("Dieses Formular als Standardformular definieren"))."&nbsp;".___("Dieses Formular als Standardformular definieren")."<br>\n";
$_MAIN_OUTPUT.= "<br><strong>".___("Aktionen")."</strong><br>\n";
//link zur dynamischen onlineversion!
$_MAIN_OUTPUT.= tm_icon("eye.png",___("Online"))."&nbsp;".___("Dynamische Onlineversion anzeigen:")." subscribe.php?fid=[FORMULAR-ID]<br>\n";
//link zum template
$_MAIN_OUTPUT.= tm_icon("page_white_code.png",___("Template"))."&nbsp;".___("Template anzeigen:")." ".$tm_formdir."/Form_[FORMULAR-ID].html<br>\n";
//link zur geparsten Version
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv"));
$_MAIN_OUTPUT.= tm_icon("cancel.png",___("Inaktiv"))."&nbsp;".___("Formular ist Aktiv/Inaktiv (Klick=Deaktivieren/Aktivieren)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("pencil.png",___("Bearbeiten"))."&nbsp;".___("Bearbeiten")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("add.png",___("Kopieren"))."&nbsp;".___("Formular kopieren")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("chart_pie.png",___("Statistik"))."&nbsp;".___("Statistik anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("cross.png",___("Löschen"))."&nbsp;".___("Formular löschen")."<br>\n";
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