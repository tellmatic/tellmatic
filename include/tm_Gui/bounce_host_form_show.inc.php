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
$_MAIN_OUTPUT.="\n\n<!-- bounce_host_form.inc -->\n\n";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['set']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['val']['html'];
$_MAIN_OUTPUT.= "<table border=0>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>".tm_icon("computer.png",___("Host"))."&nbsp;".___("Host").":&nbsp;";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Host]['html'];
$_MAIN_OUTPUT.= "&nbsp;".tm_icon("control_fastforward.png",___("Offset"))."&nbsp;".___("Offset").":&nbsp;";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Offset]['html'];
$_MAIN_OUTPUT.= "&nbsp;".tm_icon("control_end.png",___("Limit"))."&nbsp;".___("Limit").":&nbsp;";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Limit]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=3>";
$_MAIN_OUTPUT.= tm_icon("status_offline.png",___("Returnmails"))."&nbsp;".___("Nur Returnmails")."&nbsp;".$Form->INPUT[$FormularName][$InputName_FilterTo]['html']."&nbsp;";
$_MAIN_OUTPUT.= ___("TO:")."&nbsp;".$Form->INPUT[$FormularName][$InputName_FilterToSMTPReturnPath]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=3>";
$_MAIN_OUTPUT.= tm_icon("sport_soccer.png",___("Bouncemails"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Adressen suchen in:")."&nbsp;".$Form->INPUT[$FormularName][$InputName_BounceType]['html'];
$_MAIN_OUTPUT.= ___("Nur Bouncemails anzeigen")."&nbsp;".$Form->INPUT[$FormularName][$InputName_Bounce]['html']."&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=bottom colspan=3>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];
?>