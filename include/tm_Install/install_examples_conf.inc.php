<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        	*/
/* tellmatic, the newslettermachine                                             	*/
/* tellmatic, die Newslettermaschine                                            */
/* 2006/2011 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

$example_nl_body_html=	"\n".
			"<a name=\"top\"></a>\n".
			"{TITLE}<br>\n".
			"{TITLE_SUB}<br><br>\n".
			"{SUMMARY}<br><br>\n".
			"Hallo {F0} {F1} {F2}<br>\n".
			"<br>\n".
			"Attachements<br>\n".
			"{ATTACHEMENTS}<br>\n".
			"<br>\n".
			"Link-URL<br>\n".
			"{LINK1_URL}<br>\n".
			"<br>\n".
			"Link mit Link<br>\n".
			"{LINK1}{LINK1_URL}{CLOSELINK}<br>\n".
			"<br>\n".
			"Links:<br>\n".
			"<br>\n".
			"show link from shortname<br>\n".
			"{LNK:tm.home}<br>\n".
			"show link url from shortname<br>\n".
			"{LNK_URL:tm.home}<br>\n".
			"show link group<br>\n".
			"{LNKGRP:tellmatic}<br>\n".
			"show link by id<br>\n".
			"{LNKID:1}<br>\n".
			"show link url by id<br>\n".
			"{LNKID_URL:1}<br>\n".
			"show raw link url by id<br>\n".
			"{LNKID_URLRAW:1}<br>\n".
			"show raw link url by shortname<br>\n".
			"{LNK_URLRAW:tm.home}<br>\n".
			"show link group by shortname<br>\n".
			"{LNKGRP:tm}<br>\n".
			"show link group by shortname<br>\n".
			"{LNKGRP:index}<br>\n".
			"show link group by id<br>\n".
			"{LNKGRPID:1}<br>\n".
			"<br>\n".
			"Bild-URL<br>\n".
			"{IMAGE1_URL}<br>\n".
			"<br>\n".
			"Bild<br>\n".
			"{IMAGE1}<br>\n".
			"<br>\n".
			"Bild mit Link<br>\n".
			"{LINK1}{IMAGE1}{CLOSELINK}<br>\n".
			"<br>\n".
			"Online-URL<br>\n".
			"{NLONLINE_URL}<br>\n".
			"<br>\n".
			"Online Link<br>\n".
			"{NLONLINE} {NLONLINE_URL} {CLOSELINK}<br>\n".
			"<br>\n".
			"Ihre bei uns gespeicherten Daten:<br>\n".
			"{F3}, {F4}, {F5}, {F6}, {F7}, {F8}, {F9}<br>\n".
			"Die E-Mail-Adresse mit der Sie bei unserem Newsletter angemeldet sind lautet: {EMAIL}<br>\n".
			"Wenn Sie unseren Newsletter nicht mehr erhalten möchten, koennen Sie sich<br>\n".
			"{UNSUBSCRIBE_URL}<br>\n".
			"{UNSUBSCRIBE}HIER{CLOSELINK} abmelden.<br>\n".
			"{UNSUBSCRIBE}{UNSUBSCRIBE_URL}{CLOSELINK}<br>\n".
			"<br>\n".
			"Url zum Blindimage:<br>\n".
			" {BLINDIMAGE_URL}<br>\n".
			"<br>\n".
			"Blindimage:<br>\n".
			"{BLINDIMAGE}<br>\n".
			"Der Link zum Bestätigen des Newsletter Empfangs f. 1st-touch-opt-in:<br>\n".
			"{SUBSCRIBE_URL}<br>\n".
			"<br>\n".
			"{SUBSCRIBE}{SUBSCRIBE_URL}{CLOSELINK}<br>\n".
			"<br>\n".
			"Ihre bei uns gespeicherten Daten:<br>\n".
			"<br>\n".
			"{MEMO}\n".
			"Viel Spass mit tellmatic! :-)<br>\n".
			"<a name=\"bottom\"></a>".
			"\n";
			
$example_nl_body_text="{TITLE}\n".
			"{TITLE_SUB}\n\n".
			"{SUMMARY}\n\n".
			"Hallo {F0} {F1} {F2}\n".
			"\n".
			"Attachements\n".
			"{ATTACHEMENTS}\n".
			"Link-URL\n".
			"{LINK1_URL}\n".
			"Online-URL\n".
			"{NLONLINE_URL}\n".
			"Ihre bei uns gespeicherten Daten:\n".
			"{F3}, {F4}, {F5}, {F6}, {F7}, {F8}, {F9}\n".
			"Die E-Mail-Adresse mit der Sie bei unserem Newsletter angemeldet sind lautet: {EMAIL}\n".
			"Wenn Sie unseren Newsletter nicht mehr erhalten möchten, können Sie sich unter folgender URL abmelden:\n".
			"{UNSUBSCRIBE_URL}\n".
			"Der Link zum Bestätigen des Newsletter Empfangs f. 1st-touch-opt-in:\n".
			"{SUBSCRIBE_URL}\n".
			"\n".
			"Ihre bei uns gespeicherten Daten:\n".
			"\n".
			"{MEMO}\n".

			"\n".
			"Viel Spass mit tellmatic! :-)\n";

$example_nl_update_body_html="
<p>{DATE}</p>
<p>&nbsp;</p>
<p>Hallo {F0} {F1} {F2}</p>
<p>&nbsp;</p>
<p>Ihre Daten wurden aktualisiert</p>
&nbsp;
<hr />
<p>Ihre Daten: </p>
<p>{EMAIL}</p>
<p>{F0} {F1} {F2} </p>
<p>{F3}</p>
<p>
{F4}</p>
<p>
{F5}</p>
<p>
{F6}</p>
<p>
{F7}</p>
<p>
{F8}</p>
<p>
{F9} </p>
<p>&nbsp;</p>
<hr />
<p>{LNKGRP:tellmatic}</p>
<p>&nbsp;</p>
<p>{UNSUBSCRIBE}Abmelden / Austragen{CLOSELINK} </p>
<p>{UNSUBSCRIBE_URL} </p>
<p>&nbsp;</p>
";

$example_nl_update_body_text="
{DATE}\n\n
Hallo {F0} {F1} {F2}\n\n
Ihre Daten wurden aktualisiert\n
Ihre Daten:\n
{EMAIL}\n
{F0} {F1} {F2}\n
{F3}\n{F4}\n{F5}\n{F6}\n{F7}\n{F8}\n{F9}\n\n
{LNKGRP:tellmatic}\n\n
{UNSUBSCRIBE}Abmelden / Austragen{CLOSELINK}\n
{UNSUBSCRIBE_URL}\n
";


$example_nl_welcome_body_html="
<p>{DATE}</p>
<p> </p>
<p>Hallo {F0} {F1} {F2}</p>
<p> </p>
<p>Vielen Dank das Sie sich an unserem Newsletter angemeldet haben. </p>
<p>Sie erhalten bis auf Widerruf unseren regelmaessigen Newsletter</p>
<p> </p>
<hr />
<p>Ihre Daten: </p>
<p>{EMAIL}</p>
<p>{F0} {F1} {F2} </p>
<p>{F3}</p>
<p>
{F4}</p>
<p>
{F5}</p>
<p>
{F6}</p>
<p>
{F7}</p>
<p>
{F8}</p>
<p>
{F9} </p>
<p> </p>
<hr />
<p>{LNKGRP:tellmatic}</p>
<p> </p>
<p>{UNSUBSCRIBE}Abmelden / Austragen{CLOSELINK} </p>
<p>{UNSUBSCRIBE_URL} </p>
<p> </p>
";

$example_nl_welcome_body_text="
{DATE}\n
Hallo {F0} {F1} {F2}\n
Vielen Dank das Sie sich an unserem Newsletter angemeldet haben.\n
Sie erhalten bis auf Widerruf unseren regelmaessigen Newsletter\n\n
Ihre Daten:\n
{F0} {F1} {F2}\n
{F3}\n{F4}\n{F5}\n{F6}\n{F7}\n{F8}\n{F9}\n\n
{LNKGRP:tellmatic}\n\n
{UNSUBSCRIBE}Abmelden / Austragen{CLOSELINK}\n
{UNSUBSCRIBE_URL} 
";

$example_nl_doptin_body_html="
<p>{DATE}</p>
<p> </p>
<p>Hallo {F0} {F1} {F2}</p>
<p> </p>
<p>Vielen Dank das Sie sich an unserem Newsletter angemeldet haben. </p>
<p>Um Missbrauch zu vermeiden, bestaetigen Sie bitte Ihre Anmeldung mit einem Klick auf den folgenden Link: </p>
<p>{SUBSCRIBE}Anmeldung bestätigen{CLOSELINK} </p>
<p>{SUBSCRIBE_URL} </p>
<p>
<hr />
</p>
<p>Ihre Daten: </p>
<p>{F0} {F1} {F2} </p>
<p>{F3}</p>
<p>
{F4}</p>
<p>
{F5}</p>
<p>
{F6}</p>
<p>
{F7}</p>
<p>
{F8}</p>
<p>
{F9} </p>
<p>
<hr />
</p>
<p>{LNKGRP:tellmatic}</p>
";

$example_nl_doptin_body_text="
{DATE}\n
Hallo {F0} {F1} {F2}\n\n
Vielen Dank das Sie sich an unserem Newsletter angemeldet haben.\n
Um Missbrauch zu vermeiden, bestaetigen Sie bitte Ihre Anmeldung mit einem Klick auf den folgenden Link:\n\n
{SUBSCRIBE}Anmeldung bestätigen{CLOSELINK}\n
{SUBSCRIBE_URL}\n\n
Ihre Daten:\n
{F0} {F1} {F2}\n
{F3}\n{F4}\n{F5}\n{F6}\n{F7}\n{F8}\n{F9}\n
{LNKGRP:tellmatic}\n
";

?>