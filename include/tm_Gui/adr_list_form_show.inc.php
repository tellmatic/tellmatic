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
$_MAIN_OUTPUT.="\n\n<!-- adr_list_form_show.inc.php -->\n\n";

if ($ac>0) {
	$_MAIN_OUTPUT.= "<br><a href=\"javascript:checkAllForm('".$FormularName."');\" title=\"".___("Auswahl umkehren")."\">".tm_icon("arrow_refresh.png",___("Auswahl umkehren"))."&nbsp;".___("Alle auswählen / Auswahl umkehren")."</a><br>";
	$_MAIN_OUTPUT.= "<table border=0>";
	$_MAIN_OUTPUT.= "<tr>";
	$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>";
	$_MAIN_OUTPUT.=  tm_icon("exclamation.png",___("Aktion"))."&nbsp;".$Form->INPUT[$FormularName][$InputName_Action]['html'];
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>";
	$_MAIN_OUTPUT.= tm_icon("lightbulb.png",___("Status"))."&nbsp;".$Form->INPUT[$FormularName][$InputName_Status]['html'];
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>";
	$_MAIN_OUTPUT.= tm_icon("group.png",___("Gruppe"))."&nbsp;".$Form->INPUT[$FormularName][$InputName_Group]['html'];
	$_MAIN_OUTPUT.= "</td>";
#	$_MAIN_OUTPUT.= "</tr>";
#	$_MAIN_OUTPUT.= "<tr>";
#	$_MAIN_OUTPUT.= "<td valign=\"bottom\" colspan=3 align=\"right\">";
	$_MAIN_OUTPUT.= "<td valign=\"bottom\" colspan=1>";
	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
	//$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Reset]['html'];
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
	$_MAIN_OUTPUT.= "</table>";
}
	$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];

?>