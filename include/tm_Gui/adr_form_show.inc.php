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

/*DISPLAY*/
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['set']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['adr_id']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['offset']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['limit']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_email']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_status']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_author']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_aktiv']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['adr_grp_id']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['st']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si0']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si1']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si2']['html'];
$_MAIN_OUTPUT.= "<table border=0>";

if (!empty($adr_id)) {
	$_MAIN_OUTPUT.= "<tr>\n";
	$_MAIN_OUTPUT.= "<td colspan=\"2\">\n";
	$_MAIN_OUTPUT.= "ID: <b>".$ADR[0]['id']."</b>\n";
	$_MAIN_OUTPUT.= "<br>\n";
	$_MAIN_OUTPUT.= sprintf(___("Erstellt am: %s von %s"),"<b>".$ADR[0]['created']."</b>","<b>".$ADR[0]['author']."</b>")."\n";
	$_MAIN_OUTPUT.= "<br>\n";
	$_MAIN_OUTPUT.= sprintf(___("Bearbeitet am: %s von %s"),"<b>".$ADR[0]['updated']."</b>","<b>".$ADR[0]['editor']."</b>")."\n";
	$_MAIN_OUTPUT.= "<br><br>\n";
	$_MAIN_OUTPUT.= "</td>\n";
	$_MAIN_OUTPUT.= "</tr>\n";
}

$_MAIN_OUTPUT.= "<tr>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\">\n";
$_MAIN_OUTPUT.= tm_icon("email.png",___("E-Mail"))."&nbsp;".___("E-Mail")."\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\">\n";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Name]['html']."\n";
$_MAIN_OUTPUT.= "</td>\n";

$_MAIN_OUTPUT.= "<td valign=\"top\" rowspan=13 align=left>\n";
$_MAIN_OUTPUT.= tm_icon("group.png",___("Gruppen"))."&nbsp;".___("Gruppen")."<br>\n";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Group]['html']."\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "</tr>\n";

$_MAIN_OUTPUT.= "<tr>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\">\n";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv")).tm_icon("cancel.png",___("Inaktiv"))."&nbsp;".___("Aktiv")."\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\" align=left>\n";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Aktiv]['html']."\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "</tr>\n";

$_MAIN_OUTPUT.= "<tr>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\">\n";
$_MAIN_OUTPUT.= tm_icon("lightbulb.png",___("Status"))."&nbsp;".___("Status")."\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\" align=left>\n";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Status]['html']."\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "</tr>\n";

//F, neu f0-9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc;
	$_MAIN_OUTPUT.= "<tr>\n";
	$_MAIN_OUTPUT.= "<td valign=\"top\">\n";
	$_MAIN_OUTPUT.= "F".$fc."\n";
	$_MAIN_OUTPUT.= "</td>\n";
	$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1 align=left>\n";
	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$$FInputName]['html']."\n";
	$_MAIN_OUTPUT.= "</td>\n";
	$_MAIN_OUTPUT.= "</tr>\n";

}

$_MAIN_OUTPUT.= "<tr>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>\n";
$_MAIN_OUTPUT.= tm_icon("layout.png",___("Memo"))."&nbsp;".___("Memo")."\n";
$_MAIN_OUTPUT.= "</td>\n";

$_MAIN_OUTPUT.= "<td valign=\"top\" align=\"left\"colspan=2>\n";
$_MAIN_OUTPUT.= tm_icon("pencil.png",___("Memo einblenden/bearbeiten"),___("Memo einblenden/bearbeiten"),"toggle_adrmemo")."&nbsp;".___("Memo einblenden/bearbeiten")."\n";
$_MAIN_OUTPUT.= "</td>\n";

$_MAIN_OUTPUT.= "</tr>\n";
$_MAIN_OUTPUT.= "<tr>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>\n";
$_MAIN_OUTPUT.= "<div id=\"adr_memo\">\n";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Memo]['html']."\n";
$_MAIN_OUTPUT.= "</div>\n";
$_MAIN_OUTPUT.= "<script type=\"text/javascript\">\n".
		"toggleSlide('toggle_adrmemo','adr_memo',1);\n".
		"</script>\n";
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "</tr>\n";

$_MAIN_OUTPUT.= "<tr>\n";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3><br>\n";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html']."\n";
//$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Reset]['html'];
$_MAIN_OUTPUT.= "</td>\n";
$_MAIN_OUTPUT.= "</tr>\n";
$_MAIN_OUTPUT.= "</table>\n";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot']."\n";
?>