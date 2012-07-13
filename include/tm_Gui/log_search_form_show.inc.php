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
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
#$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['set']['html'];
$_MAIN_OUTPUT.= "<table border=0>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= tm_icon("group.png",___("Filtern nach Objekt")).$Form->INPUT[$FormularName][$InputName_Obj]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= tm_icon("page_white.png",___("Filtern nach Aktion")).$Form->INPUT[$FormularName][$InputName_Action]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= tm_icon("group.png",___("Filtern nach Author")).$Form->INPUT[$FormularName][$InputName_Author]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= tm_icon("key.png",___("Filtern nach ID")).$Form->INPUT[$FormularName][$InputName_EditID]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= tm_icon("bullet_wrench.png",___("Zeige maximal N Einträge pro Seite")).$Form->INPUT[$FormularName][$InputName_Limit]['html'];
$_MAIN_OUTPUT.= "</td>";

$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= tm_icon("bullet_wrench.png",___("Aktion")).$Form->INPUT[$FormularName][$InputName_Set]['html'];
$_MAIN_OUTPUT.= "</td>";

#$_MAIN_OUTPUT.= "</tr>";
#$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=bottom colspan=6>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];
?>