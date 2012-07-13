<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/11 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

/*RENDER FORM*/

$Form->render_Form($FormularName);
//then you dont have to render the head and foot .....

/*DISPLAY*/
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['set']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['frm_id']['html'];
$_MAIN_OUTPUT.= "<table border=0>";

if (!empty($frm_id)) {
	$_MAIN_OUTPUT.= "<tr>";
	$_MAIN_OUTPUT.= "<td colspan=\"2\">";
	$_MAIN_OUTPUT.= "ID: <b>".$FRM[0]['id']."</b>";
	$_MAIN_OUTPUT.= "<br>";
	$_MAIN_OUTPUT.= sprintf(___("Erstellt am: %s von %s"),"<b>".$FRM[0]['author']."</b>","<b>".$FRM[0]['created']."</b>");
	$_MAIN_OUTPUT.= "<br>";
	$_MAIN_OUTPUT.= sprintf(___("Bearbeitet am: %s von %s"),"<b>".$FRM[0]['editor']."</b>","<b>".$FRM[0]['updated']."</b>");
	$_MAIN_OUTPUT.= "<br><br>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("folder.png",___("Name"))."&nbsp;".___("Name");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Name]['html'];
$_MAIN_OUTPUT.= "</td>";

$_MAIN_OUTPUT.= "<td valign=top rowspan=14>";
$_MAIN_OUTPUT.= tm_icon("group.png",___("Gruppen"))."&nbsp;".___("Gruppen")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Group]['html'];
$_MAIN_OUTPUT.= "<br>".tm_icon("group.png",___("Gruppen"))."&nbsp;".___("öffentliche Gruppen")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_GroupPub]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("world.png",___("URL"))."&nbsp;".___("URL");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ActionUrl]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv")).tm_icon("cancel.png",___("Inaktiv"))."&nbsp;".___("Aktiv");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Aktiv]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("arrow_refresh.png",___("Double Opt-in"))."&nbsp;".___("Double Opt-in");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_DoubleOptin]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("sport_8ball.png",___("Captcha"))."&nbsp;".___("Captcha/Spamcode prüfen");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_UseCaptcha]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_DigitsCaptcha]['html'];
$_MAIN_OUTPUT.= ___("Ziffern");
$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung")." ".$Form->INPUT[$FormularName][$InputName_captcha_errmsg]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("ruby.png",___("Blacklist"))."&nbsp;".___("Blacklist prüfen");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Blacklist]['html'];
$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung")." ".$Form->INPUT[$FormularName][$InputName_Blacklist_errmsg]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("medal_gold_1.png",___("Proofing"))."&nbsp;".___("Proofing");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Proof]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("group_error.png",___("Auswahl für Gruppen erzwingen"))."&nbsp;".___("Auswahl für Gruppen erzwingen");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ForcePubGroup]['html'];
$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung")." ".$Form->INPUT[$FormularName][$InputName_PubGroup_errmsg]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("group_link.png",___("Gruppen aktualisieren, nur Neue hinzu"))."&nbsp;".tm_icon("group_gear.png",___("Gruppen überschreiben"))."&nbsp;".___("Gruppen Auswahl");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_OverwritePubgroup]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("group.png",___("Besucher kann mehrere Gruppen wählen"))."&nbsp;".___("Mehrere Gruppen");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_MultiPubGroup]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv")).tm_icon("cancel.png",___("Inaktiv"))."&nbsp;".___("Neuanmeldungen sind aktiv");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SubAktiv]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("newspaper_link.png",___("Double Opt-in Mail"))."&nbsp;".___("Double Opt-in Mail");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_NLDOptin]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("newspaper_add.png",___("Bestätigungs-Mail"))."&nbsp;".___("Bestätigungs-Mail");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_NLGreeting]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("newspaper_go.png",___("Aktualisierungs-Mail"))."&nbsp;".___("Aktualisierungs-Mail");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_NLUpdate]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("server.png",___("SMTP-Server"))."&nbsp;".___("SMTP-Server");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Host]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=3>". tm_icon("layout.png",___("Beschreibung"))."&nbsp;".___("Beschreibung");
$_MAIN_OUTPUT.= "&nbsp;(<a href=\"javascript:switchSection('frm_descr');\" >".___("Ein-/Ausblenden")."</a>)<br>";
$_MAIN_OUTPUT.= "<div id=\"frm_descr\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Descr]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
#$_MAIN_OUTPUT.= "<td valign=top colspan=3  style=\"border-top:1px dashed #666666\">E-Mail ";
$_MAIN_OUTPUT.= "<td valign=top  colspan=3 >".___("E-Mail-Adresse");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_email]['html'];
$_MAIN_OUTPUT.= "&nbsp;".___("Fehler")." ".$Form->INPUT[$FormularName][$InputName_email_errmsg]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=3  style=\"border-top:1px dashed #666666\">".___("Eingabefelder")."&nbsp;";
$_MAIN_OUTPUT.= "(<a href=\"javascript:switchSection('frm_finput');\" >".___("Ein-/Ausblenden")."</a>)";

$_MAIN_OUTPUT.= "<br><div id=\"frm_finput\">";
$_MAIN_OUTPUT.= "<table border=0 cellspacing=0 cellpadding=0>";
$_MAIN_OUTPUT.= "<tr>";
#$_MAIN_OUTPUT.= "<td valign=top colspan=3 style=\"border-top:1px solid #000000\">".___("Fn / Pflichtfeld / Typ / Name");
$_MAIN_OUTPUT.= "<td valign=top>".___("Fn / Pflichtfeld / Typ / Name");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

//render form, F0-F9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc;
	$_MAIN_OUTPUT.= "<tr>";
#	$_MAIN_OUTPUT.= "<td valign=top colspan=3  style=\"border-top:1px dashed #666666\">F".$fc." ";
	$_MAIN_OUTPUT.= "<td valign=top style=\"border-top:1px dashed #666666\">F".$fc." ";
	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$$FInputName."_required"]['html'];
	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$$FInputName."_type"]['html'];
	$_MAIN_OUTPUT.= " ".___("Name")." ".$Form->INPUT[$FormularName][$$FInputName]['html'];
	$_MAIN_OUTPUT.= " ".___("Werte")." ".$Form->INPUT[$FormularName][$$FInputName."_value"]['html'];
	$_MAIN_OUTPUT.= "<br>".___("Fehler")." ".$Form->INPUT[$FormularName][$$FInputName."_errmsg"]['html'];
	$_MAIN_OUTPUT.= " ".___("RegExpr")." ".$Form->INPUT[$FormularName][$$FInputName."_expr"]['html'];
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= "</div>";

$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


//FSubmit value
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1  style=\"border-top:1px dashed #666666\">".tm_icon("tag_blue.png",___("Submit"))."&nbsp;".___("Submit");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=2 align=\"left\"  style=\"border-top:1px dashed #666666\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SubmitValue]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

//FReset value
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>".tm_icon("tag_red.png",___("Reset"))."&nbsp;".___("Reset");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=2 align=\"left\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ResetValue]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

//Messgae doptin
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>".tm_icon("script.png",___("Meldung bei Double-Opt-In"))."&nbsp;".___("Meldung bei Double-Opt-In");
$_MAIN_OUTPUT.= "<br>(<a href=\"javascript:switchSection('frm_msg_doptin');\" >".___("Ein-/Ausblenden")."</a>)";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=2 align=\"left\">";
$_MAIN_OUTPUT.= "<div id=\"frm_msg_doptin\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_MessageDOptin]['html'];
$_MAIN_OUTPUT.= "</div>";

$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
//Messgae greeting
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>".tm_icon("script.png",___("Meldung bei Neueintrag"))."&nbsp;".___("Meldung bei Neueintrag");
$_MAIN_OUTPUT.= "<br>(<a href=\"javascript:switchSection('frm_msg_new');\" >".___("Ein-/Ausblenden")."</a>)";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=2 align=\"left\">";
$_MAIN_OUTPUT.= "<div id=\"frm_msg_new\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_MessageGreeting]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
//Messgae update
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>".tm_icon("script.png",___("Meldung bei Aktualisierung"))."&nbsp;".___("Meldung bei Aktualisierung");
$_MAIN_OUTPUT.= "<br>(<a href=\"javascript:switchSection('frm_msg_update');\" >".___("Ein-/Ausblenden")."</a>)";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=2 align=\"left\">";
$_MAIN_OUTPUT.= "<div id=\"frm_msg_update\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_MessageUpdate]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

//Submit, save form
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=3  style=\"border-top:1px solid #000000\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
//$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Reset]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];


	$_MAIN_OUTPUT.= "
		<script language=\"javascript\" type=\"text/javascript\">
		switchSection('frm_msg_doptin');
		switchSection('frm_msg_update');
		switchSection('frm_msg_new');
		switchSection('frm_descr');
		switchSection('frm_finput');
		</script>
	";
	#include_once (TM_INCLUDEPATH_LIB."/wysiwyg.inc.php");
?>