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
$_MAIN_OUTPUT.="<br><b><a href=\"#\" title=\"".___("Legende f. Variablen ein-ausblenden")."\" id=\"toggle_legende_nlvar\">".tm_icon("rainbow.png",___("Legende / Variablen"))."&nbsp;".___("Legende / Variablen")."</a></b>\n";
$_MAIN_OUTPUT.= "<div id=\"legende_nlvar\" class=\"legende\">\n";
$_MAIN_OUTPUT.= "<h3>".___("Legende")."</h3>\n";
$_MAIN_OUTPUT.= "<strong>".___("Variablen")."</strong><br>\n";


$_MAIN_OUTPUT.= 
						sprintf(___("%s enthält den Betreff"),"<b>{SUBJECT}</b>")."<br>".
						sprintf(___("%s enthält den das aktuelle Datum zum Zeitpunkt des Versandes. Das Datumsformat wird in tm_lib.inc.php voreingestellt (TM_NL_DATEFORMAT). Standard: d.m.Y, Aktuell: %s"),"<b>{DATE}</b>",TM_NL_DATEFORMAT)."<br>".
						sprintf(___("%s enthält den Titel zur Anzeige auf der Webseite | %s enthält den Untertitel zur Anzeige auf der Webseite"),"<b>{TITLE}</b>","<b>{TITLE_SUB}</b>")."<br>".
						sprintf(___("%s enthält die Zusammenfasung zur Anzeige auf der Webseite"),"<b>{SUMMARY}</b>")."<br>".
						sprintf(___("%s enthält das hochgeladene Bild 'img src' | %s enthält nur die URL zum Bild"),"<b>{IMAGE1}</b>","<b>{IMAGE1_URL}</b>")."<br>".
						sprintf(___("%s enthält den angegebenen Link 'a href' | %s enthält nur die URL des Links"),"<b>{LINK1}</b>","<b>{LINK1_URL}</b>")."<br>".
						sprintf(___("%s / %s erstellt einen Link anhand eines Kurznamen oder ID"),sprintf("<b>{LNK:[%s][,%s]}</b>",___("kurz"),___("Parameter") ),"<b>{LNKID:[id][,".___("Parameter")."]}</b>")."<br>".						
						sprintf(___("%s / %s erstellt einen 'a href' anhand eines Kurznamen oder ID"),sprintf("<b>{LNK_AHREF:[%s][,%s]}</b>",___("kurz"),___("Parameter") ),"<b>{LNKID_AHREF:[id][,".___("Parameter")."]}</b>")."<br>".						
						sprintf(___("%s / %s zeigt die URL anhand eines Kurznamen oder ID"),sprintf("<b>{LNK_URL:[%s]}</b>",___("kurz")),"<b>{LNKID_URL:[id]}</b>")."<br>".						
						sprintf(___("%s / %s zeigt die RAW-URL anhand eines Kurznamen oder ID"),sprintf("<b>{LNK_URLRAW:[%s]}</b>",___("kurz")),"<b>{LNKID_URLRAW:[id]}</b>")."<br>".
						sprintf(___("%s / %s zeigt eine Liste von Links aus einer Gruppe anhand eines Kurznamen oder ID"),sprintf("<b>{LNKGRP:[%s][,%s]}</b>",___("kurz"),___("Parameter")),"<b>{LNKGRPID:[id][,".___("Parameter")."]}</b>")."<br>".
						sprintf(___("%s enthält die Links zu den Attachements"),"<b>{ATTACHEMENTS}</b>")."<br>".
						sprintf(___("%s enthält den Link zum abmelden |   %s enthält nur die URL zum Abmeldeformular"),"<b>{UNSUBSCRIBE}</b>","<b>{UNSUBSCRIBE_URL}</b>")."<br>".
						sprintf(___("%s enthält den Link zur Onlineversion 'a href' |  %s enthält nur die URL zur Onlineversion"),"<b>{NLONLINE}</b>","<b>{NLONLINE_URL}</b>")."<br>".
						sprintf(___("%s enthält das schliessende TAG '/a' fuer die Links"),"<b>{CLOSELINK}</b>")."<br>".
						"<u>".___("Nur für personalisierten Newsleter:")."</u><br>".
						sprintf(___("%s enthält das Tracker-Bild (für View-Tracking, nur HTML-Mails)"),"<b>{BLINDIMAGE}</b>")."<br>".
						sprintf(___("%s enthält die e-Mailadresse des Empfaengers"),"<b>{EMAIL}</b>")."<br>".
						sprintf(___("%s bis %s enthält die Felder aus den Formularen/Adressen"),"<b>{F0}</b>","<b>{F9}</b>")."<br>".
						sprintf(___("%s enthält den kompletten Link 'a href' zum Aktivierungslink ('(1st)-Touch-Opt-In) | %s enthält nur die URL"),"<b>{SUBSCRIBE}</b>","<b>{SUBSCRIBE_URL}</b>")."<br>".
						sprintf(___("%s enthält die Liste der Gruppen an denen der Empfänger angemeldet ist."),"<b>{GROUP}</b>")."<br>".
						"";
//						sprintf(___("%s enthält den Link zum Attachement 'a href' |  %s enthält nur die URL zum Attachement"),"<b>{ATTACH1}</b>","<b>{ATTACH1_URL}</b>")."<br>".

$_MAIN_OUTPUT.= "</div><br><br>\n";
$_MAIN_OUTPUT.= "<script type=\"text/javascript\">\n";
if ($user_is_expert) {
	$_MAIN_OUTPUT.= "	//switchSection('legende_nlvar');\n".
				"toggleSlide('toggle_legende_nlvar','legende_nlvar',1);\n";
} else {
	$_MAIN_OUTPUT.= "	toggleSlide('toggle_legende_nlvar','legende_nlvar',0);\n";
}
$_MAIN_OUTPUT.= "</script>\n";
?>