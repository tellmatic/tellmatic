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

/*RENDER FORM*/

$Form->render_Form($FormularName);

/*DISPLAY*/
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
$_MAIN_OUTPUT.= "<table border=0>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">".tm_icon("email.png",___("E-Mail"))."&nbsp;".___("E-Mail (leer = alle! ; wildcard = '*' oder '%'")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Name]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">".tm_icon("group.png",___("Gruppe"))."&nbsp;".___("aus der Gruppe:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Group]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=2>".tm_icon("lightbulb.png",___("Status"))."&nbsp;".___("mit dem Status:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Status]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=2>".tm_icon("exclamation.png",___("Aktion"))."&nbsp;".___("Aktion:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Set]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">".tm_icon("lightbulb.png",___("Status"))."&nbsp;".___("neuer Status:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_StatusDst]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>".tm_icon("group.png",___("Gruppen"))."&nbsp;".___("gewählte Gruppen:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_GroupDst]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>".tm_icon("ruby.png",___("Blacklist"))."&nbsp;".___("Adressen zur Blacklist hinzufügen:")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Blacklist]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>".tm_icon("text_columns.png",___("Dubletten entfernen"))."&nbsp;".___("Dubletten suchen und entfernen:")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_RemoveDups]['html'];
$_MAIN_OUTPUT.= "&nbsp;".$Form->INPUT[$FormularName][$InputName_RemoveDupsMethod]['html'];
$_MAIN_OUTPUT.= "&nbsp;".___("Limit")."&nbsp;".$Form->INPUT[$FormularName][$InputName_RemoveDupsLimit]['html'];
$_MAIN_OUTPUT.= "&nbsp;".___("Details")."&nbsp;".$Form->INPUT[$FormularName][$InputName_RemoveDupsDetails]['html'];
$_MAIN_OUTPUT.= "&nbsp;".___("Export")."&nbsp;".$Form->INPUT[$FormularName][$InputName_RemoveDupsExport]['html'];

$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"bottom\" colspan=2 align=left>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Reset]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];
?>