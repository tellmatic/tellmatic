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
$_MAIN_OUTPUT.= tm_icon("spellcheck.png",___("E-Mail prüfen"))."&nbsp;".___("zur Prüfung vorgemerkt")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("ruby.png",___("Blacklist"))."&nbsp;".___("Blacklist aktiv")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("ruby_key.png",___("Blacklist E-Mail"))."&nbsp;".___("Blacklist E-Mail")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("ruby_link.png",___("Blacklist Domain"))."&nbsp;".___("Blacklist Domain")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("ruby_gear.png",___("Blacklist RegEx"))."&nbsp;".___("Blacklist RegEx")."<br>\n";
$_MAIN_OUTPUT.= "<br><strong>".___("Aktionen")."</strong><br>\n";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv"))."&nbsp;";
$_MAIN_OUTPUT.= tm_icon("cancel.png",___("Inaktiv"))."&nbsp;".___("Adresse ist Aktiv/Inaktiv (Klick=Deaktivieren/Aktivieren)")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("spellcheck.png",___("E-Mail Prüfen"))."&nbsp;".___("E-Mail prüfen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("pencil.png",___("Bearbeiten"))."&nbsp;".___("Bearbeiten")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("chart_pie.png",___("Statistik"))."&nbsp;".___("Statistik anzeigen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("chart_bar_delete.png",___("Historie löschen"))."&nbsp;".___("Historie löschen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_add.png",___("Adresse in Blacklist eintragen"),"","","","ruby_key.png")."&nbsp;".___("Adresse in Blacklist eintragen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___("Adresse aus Blacklist löschen"),"","","","ruby_key.png")."&nbsp;".___("Adresse aus Blacklist löschen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_add.png",___("Domain der Adresse in Blacklist eintragen"),"","","","ruby_link.png")."&nbsp;".___("Domain der Adresse in Blacklist eintragen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___("Domain der Adresse aus Blacklist löschen"),"","","","ruby_link.png")."&nbsp;".___("Domain der Adresse aus Blacklist löschen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("cross.png",___("Löschen"))."&nbsp;".___("Adresse löschen")."<br>\n";
$_MAIN_OUTPUT.= tm_icon("script.png",___("Logbuch anzeigen"))."&nbsp;".___("Logbuch anzeigen")."<br>\n";
$_MAIN_OUTPUT.= "<br><strong>".___("Status")."</strong><br>\n";
$sc=count($STATUS['adr']['status']);
for ($scc=1; $scc<=$sc; $scc++)//0
{
	$_MAIN_OUTPUT.= "<span style=\"width:24px;background-color:".$STATUS['adr']['color'][$scc].";\">&nbsp;&nbsp;</span>&nbsp;";
	$_MAIN_OUTPUT.= tm_icon($STATUS['adr']['statimg'][$scc],display($STATUS['adr']['descr'][$scc]));
	$_MAIN_OUTPUT.= "&nbsp; ".display($STATUS['adr']['status'][$scc])."  (".display($STATUS['adr']['descr'][$scc]).")<br>\n";
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