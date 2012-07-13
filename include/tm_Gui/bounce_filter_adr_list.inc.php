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

$Bounces=Array();
$Mail=Array();
$bcmatch=0;
for ($mcc=0;$mcc<$mc;$mcc++) {
	$Mail=$Bounce->filterBounces($Mailer->getMail($mailno[$mcc]),$checkHeader,$checkBody);//$Messages , checkHeader=1, checkBody, returnOnlyBounces..., filter to:
	if (!empty($Mail[0]['bounce'])) {
		$Bounces = array_merge($Bounces,$Mail[0]['bounce']);
		$bcmatch++;
	}
}
$bctotal=count($Bounces);
$Bounces=unify_array($Bounces);
$bc=count($Bounces);
$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("Es wurden %s Mails durchsucht."),$mc)).
								tm_message_notice(sprintf(___("%s Mails ergaben einen Treffer."),$bcmatch)).
								tm_message_notice(sprintf(___("Es wurden aus %s Adressen %s (eindeutige) potentiell fehlerhafte Adressen erkannt."),$bctotal,$bc));
if ($bc) { //bc>0
	$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=100%>";
	$_MAIN_OUTPUT.= "<thead>".
							"<tr>".
							"<td></td>".
							"<td>".___("Adresse")."</td>".
							"<td>".___("Details")."</td>".
							"</thead>".
							"<tbody>";
	for ($bcc=0;$bcc<$bc;$bcc++) {
		$search['email']=$Bounces[$bcc];
		$search['email_exact_match']=true;
		$ADR=$ADDRESS->getAdr(0,0,0,0,$search,"",0,0);
		if ($bcc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
		$_MAIN_OUTPUT.= "<tr id=\"row2_".$bcc."\" bgcolor=\"".$bgcolor."\" onmousemove=\"showToolTip('tt_bouncemail_adr_list_".$bcc."')\" onmouseover=\"setBGColor('row2_".$bcc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row2_".$bcc."','".$bgcolor."');hideToolTip();\">";
		$_MAIN_OUTPUT.= "<td>";
		$Form->set_InputValue($FormularName,$InputName_Adr,$Bounces[$bcc]);
		if (isset($ADR[0]['id'])) {
			$Form->set_InputDefault($FormularName,$InputName_Adr,$Bounces[$bcc]);
		}
		$Form->render_Input($FormularName,$InputName_Adr);
		$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Adr]['html'];
		$_MAIN_OUTPUT.= "<div id=\"tt_bouncemail_adr_list_".$bcc."\" class=\"tooltip\">";
		$_MAIN_OUTPUT.= "".$Bounces[$bcc]."";
		if (isset($ADR[0]['id'])) {
			$_MAIN_OUTPUT.= "<br>ID: ".$ADR[0]['id']."&nbsp;&nbsp;<br>";
			if ($ADR[0]['aktiv']==1) {
				$_MAIN_OUTPUT.=   tm_icon("tick.png",___("Aktiv"))."&nbsp;";
			} else {
				$_MAIN_OUTPUT.=   tm_icon("cancel.png",___("Aktiv"))."&nbsp;";
			}
			$_MAIN_OUTPUT.= "<br>"
											.tm_icon($STATUS['adr']['statimg'][$ADR[0]['status']], display($STATUS['adr']['descr'][$ADR[0]['status']]))
											."&nbsp;".display($STATUS['adr']['status'][$ADR[0]['status']]);			
			
			$_MAIN_OUTPUT.= "<br>".
								"<br>".sprintf(___("Newsletter Gesamt: %s"),$ADR[0]['newsletter']).
								"<br>".sprintf(___("Views: %s"),$ADR[0]['views']).
								"<br>".sprintf(___("Clicks: %s"),$ADR[0]['clicks']).
								"<br>".sprintf(___("Sendefehler: %s"),$ADR[0]['errors'])."&nbsp;";
		} else {
			$_MAIN_OUTPUT.= "<br>".___("Unbekannt / nicht in der Datenbank");
		}
		$_MAIN_OUTPUT.= "</div>";
		$_MAIN_OUTPUT.= "</td>";
		$_MAIN_OUTPUT.= "<td>";
		$_MAIN_OUTPUT.= "<b>".$Bounces[$bcc]."</b>";
		$_MAIN_OUTPUT.= "</td>";
		$_MAIN_OUTPUT.= "<td>";
		if (isset($ADR[0]['id'])) {
			$_MAIN_OUTPUT.= "ID: ".$ADR[0]['id']."&nbsp;&nbsp;";
			if ($ADR[0]['aktiv']==1) {
				$_MAIN_OUTPUT.=   tm_icon("tick.png",___("Aktiv"))."&nbsp;";
			} else {
				$_MAIN_OUTPUT.=   tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
			}
			$_MAIN_OUTPUT.= "<br>"
											.tm_icon($STATUS['adr']['statimg'][$ADR[0]['status']],display($STATUS['adr']['descr'][$ADR[0]['status']]))
											."&nbsp;".display($STATUS['adr']['status'][$ADR[0]['status']]);			
		} else {
			$_MAIN_OUTPUT.= "&nbsp;".___("Unbekannt / nicht in der Datenbank");
		}
		$_MAIN_OUTPUT.= "</td>";
		$_MAIN_OUTPUT.= "</tr>";
	}
		$_MAIN_OUTPUT.= "<tr>";
		$_MAIN_OUTPUT.= "<td colspan=3>";
		$_MAIN_OUTPUT.= "<a href=\"javascript:checkAllForm('".$FormularName."');\" title=\"".___("Markierung für alle angezeigten Adressen umkehren")."\">".___("Alle auswählen / Markierung für alle angezeigten Adressen umkehren")."</a><br><br>";
	//	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ActionAdr]['html'];
		$_MAIN_OUTPUT.= "</td>";
		$_MAIN_OUTPUT.= "</tr>";
	$_MAIN_OUTPUT.= "</tbody></table><br>";
} //if bc
?>