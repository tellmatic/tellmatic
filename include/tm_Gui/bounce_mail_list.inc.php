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

$_MAIN_OUTPUT.="\n\n<!-- bounce_mail_list.inc -->\n\n";
//$Mail=$Bounce->filterBounces($Mail,1,1);//$Messages , checkHeader=1, checkBody
/*
//$Bounce->checkHeader($Mail[]);
//$Bounce->checkBody($Mail[]);
*/
$Bounces=Array();
$bcmatch=0;

//hier uebergeben wir das mailarray an bounce und lassen uns die bounces filtern, aus body und header
/*
//tun wir aber bereits in getmail!!!
if ($filter_to==1) { // wenn 1 dann filtern wir die emails nach return-path im to feld! am besten aber schon in getMail.... ueber search-array
	$Mail=$Bounce->filterBounces($Mail,1,1,$bounce,$C[0]['return_mail']);//$Messages , checkHeader=1, checkBody, returnOnlyBounces..., filter to:
} else {
	$Mail=$Bounce->filterBounces($Mail,1,1,$bounce);//$Messages , checkHeader=1, checkBody, returnOnlyBounces..., filter to:
}
*/

//bouncemails filtern
$Mail=$Bounce->filterBounces($Mail,$checkHeader,$checkBody,$bounce,"");//$Messages , checkHeader=1, checkBody, returnOnlyBounces..., filter to:
$mc=count($Mail);

$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Gesamt: %s Mails"),$Mailer->count_msg));
$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Gefiltert: %s Mails"),$mc));
$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Angezeigt werden max. %s Mails"),$limit));

if ($mc>0) {
	$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Mail Nr. %s bis %s"),($offset+1),($offset+$mc)));
	$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=100%>";
	$_MAIN_OUTPUT.= "<thead>".
							"<tr>".
							"<td style=\"width:80px;\">&nbsp;".
							"</td>".
							"<td style=\"width:240px;\"><b>".___("Betreff / Text")."</b>".
							"</td>".
							"<td><b>".___("Adressen")."</b>".
							"</td>".
							"</tr>".
							"</thead>".
							"<tbody>";
	for ($mcc=0;$mcc<$mc;$mcc++) {
	#echo $Mail[$mcc]['body'];echo "<br>";
		//hier zaehlen wir dann die bounces aus dem array bounce im mailarray.....
		if (!empty($Mail[$mcc]['bounce'])) {
			$Bounces = array_merge($Bounces,$Mail[$mcc]['bounce']);
			$bcmatch++;
		}
	/*alt:
		//bounce holen
		if (!empty($Mail[$mcc]['bounce'])) {
			$Mail=$Bounce->filterBounces($Mail,1,1);//$Messages , checkHeader=1, checkBody
			$Bounces = array_merge($Bounces,$Mail[$mcc]['bounce']);
			$bcmatch++;
		}
	*/
		if ($mcc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
		$_MAIN_OUTPUT.= "<tr id=\"row_".$mcc."\" bgcolor=\"".$bgcolor."\" onmousemove=\"showToolTip('tt_bouncemail_list_".$mcc."')\" onmouseover=\"setBGColor('row_".$mcc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$mcc."','".$bgcolor."');hideToolTip();\">";
		$_MAIN_OUTPUT.= "<td valign=\"top\">";
		if ($Mail[$mcc]['to']==$filter_to_smtp_return_path || $Mail[$mcc]['is_bouncemail']==1) {
			$Form->set_InputDefault($FormularName,$InputName_Mail,$Mail[$mcc]['no']);
		}
		$Form->set_InputValue($FormularName,$InputName_Mail,$Mail[$mcc]['no']);
		$Form->render_Input($FormularName,$InputName_Mail);
		$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Mail]['html'];
		$_MAIN_OUTPUT.= $Mail[$mcc]['no'].".";
		#print_r($Mail[$mcc]);
		if ($Mail[$mcc]['to']==$filter_to_smtp_return_path) {
			$_MAIN_OUTPUT.=  tm_icon("status_offline.png",___("Return Mail"));
		}
		if ($Mail[$mcc]['is_bouncemail']==1) {
			$_MAIN_OUTPUT.=  tm_icon("sport_soccer.png",___("Bounce Mail erkannt"));
		}
		$_MAIN_OUTPUT.= "<div id=\"tt_bouncemail_list_".$mcc."\" class=\"tooltip\">";
		$_MAIN_OUTPUT.= "NR  <b>".$Mail[$mcc]['no']."</b>";
		$_MAIN_OUTPUT.= "<br><b>".$Mail[$mcc]['subject']."</b>";
		$_MAIN_OUTPUT.= "<br>Datum: <b>".$Mail[$mcc]['date']."</b>";
		$_MAIN_OUTPUT.= "<br>To: <b>".$Mail[$mcc]['to']."</b>";
		$_MAIN_OUTPUT.= "<br>From: <b>".$Mail[$mcc]['from']."</b>";
		$_MAIN_OUTPUT.= "<br>Grösse: <b>".$Mail[$mcc]['size']."</b>";
		$_MAIN_OUTPUT.= "</div>";
		$_MAIN_OUTPUT.= "</td>";

		$_MAIN_OUTPUT.= "<td valign=\"top\">";
		$_MAIN_OUTPUT.= "<div>";
		$_MAIN_OUTPUT.= "<a href=\"javascript:switchSection('tt_bouncemail_head_".$mcc."')\" title=\"".___("Text und Details anzeigen")."\">".tm_icon("page_white.png",___("Text und Details anzeigen"))."</a>";
		$_MAIN_OUTPUT.= "&nbsp;";
		$_MAIN_OUTPUT.= "<a href=\"javascript:switchSection('tt_bouncemail_text_".$mcc."')\" title=\"".___("E-Mail Header")."\">".tm_icon("page.png",___("E-Mail Header"))."</a>";
		$_MAIN_OUTPUT.= "<font size=-1>";
		$_MAIN_OUTPUT.= "".$Mail[$mcc]['subject']."";
		$_MAIN_OUTPUT.= "</font>";
		$_MAIN_OUTPUT.= "</div>";

		//div fuer mailheader
		$_MAIN_OUTPUT.= "<div id=\"tt_bouncemail_head_".$mcc."\">";
		$_MAIN_OUTPUT.= "<br><b>".___("E-Mail-Header").":</b>";
		#$_MAIN_OUTPUT.= "<br><br><font size=-2>".str_replace("\n","<br>",clear_text(substr(str_replace("<br>","\n",$Mail[$mcc]['header']),0,1024)))." ...............etc.</font>";
		$_MAIN_OUTPUT.= "<br><font size=-2>".str_replace("\n","<br>",clear_text(str_replace("<br>","\n",display($Mail[$mcc]['header']))))."</font>";
		$_MAIN_OUTPUT.= "</div>";

		$_MAIN_OUTPUT.= "<script type=\"text/javascript\">switchSection('tt_bouncemail_head_".$mcc."');</script>";



		//div fuer mailtext
		$_MAIN_OUTPUT.= "<div id=\"tt_bouncemail_text_".$mcc."\">";
		$_MAIN_OUTPUT.= "<br><b>".___("E-Mail-Body").":</b>";
		$_MAIN_OUTPUT.= "<br>".$Mail[$mcc]['date']."";
		$_MAIN_OUTPUT.= "<br>Von: ".$Mail[$mcc]['from']."";
		$_MAIN_OUTPUT.= "<br>An: ".$Mail[$mcc]['to']."";
		#$_MAIN_OUTPUT.= "<br><br><font size=-2>".str_replace("\n","<br>",clear_text(substr(str_replace("<br>","\n",$Mail[$mcc]['body']),0,1024)))."...............</font>";
		$_MAIN_OUTPUT.= "<br><font size=-2><em>".str_replace("\n","<br>",display(substr(str_replace("<br>","\n",$Mail[$mcc]['body']),0,1024)))."</em>     ...etc</font>";
		$_MAIN_OUTPUT.= "</div>";

		$_MAIN_OUTPUT.= "<script type=\"text/javascript\">switchSection('tt_bouncemail_text_".$mcc."');</script>";
		$_MAIN_OUTPUT.= "</td>";

		$_MAIN_OUTPUT.= "<td valign=\"top\">";
		//wenn bounces .....
		if (count($Mail[$mcc]['bounce'])) {
			$_MAIN_OUTPUT.= "<div>";
			$_MAIN_OUTPUT.= "<a href=\"javascript:switchSection('tt_bouncemail_adr_".$mcc."')\" title=\"".___("Adressen anzeigen")."\">".tm_icon("user_orange.png",___("Adressen anzeigen"))."</a>";
			$_MAIN_OUTPUT.= "</div>";
			//div fuer adressen
			$_MAIN_OUTPUT.= "<div id=\"tt_bouncemail_adr_".$mcc."\">";
			$_MAIN_OUTPUT.= "<font size=-1>";
			foreach ($Mail[$mcc]['bounce'] as $badr) {
				$_MAIN_OUTPUT.= $badr.", ";
			}
			$_MAIN_OUTPUT.= "</font>";
			$_MAIN_OUTPUT.= "</div>";
			$_MAIN_OUTPUT.= "<script type=\"text/javascript\">switchSection('tt_bouncemail_adr_".$mcc."');</script>";
		}
		$_MAIN_OUTPUT.= "&nbsp;</td>";

		$_MAIN_OUTPUT.= "</tr>";
	}

	$_MAIN_OUTPUT.= "<tr>";
	$_MAIN_OUTPUT.= "<td colspan=4>";
	$_MAIN_OUTPUT.= tm_icon("arrow_refresh.png",___("Auswahl umkehren"))."&nbsp;<a href=\"javascript:checkAllForm('".$FormularName."');\" title=\"".___("Markierung für alle angezeigten E-Mails umkehren")."\">".___("Alle auswählen / Markierung für alle angezeigten E-Mails umkehren")."</a>";
	//$_MAIN_OUTPUT.= "<a href=\"javascript:checkByID('".$InputName_Mail."');\">alle auswählen</a><br>";
	//$_MAIN_OUTPUT.= "<a href=\"javascript:checkAll();\">alle auswählen</a><br>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";

	$_MAIN_OUTPUT.= "</tbody></table><br>";

	$bctotal=count($Bounces);
	$Bounces=unify_array($Bounces);
	$bc=count($Bounces);
	$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("Es wurden %s Mails durchsucht."),$mc)).
								tm_message_notice(sprintf(___("%s Mails ergaben einen Treffer."),$bcmatch)).
								tm_message_notice(sprintf(___("Es wurden aus %s Adressen %s potentiell Fehlerhafte Adressen erkannt."),$bctotal,$bc));

}//mc>0
?>