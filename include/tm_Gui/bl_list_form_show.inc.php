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

$_MAIN_OUTPUT.= "<table border=0 width=\"100%\">";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>";
$_MAIN_OUTPUT.=  tm_icon("ruby_gear.png",___("Typ"))."&nbsp;".$Form->INPUT[$FormularName][$InputName_Type]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$_MAIN_OUTPUT.= "</td>";
//export blacklist
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1 align=\"right\">";
if ($type=="email") { $bl_typename=tm_icon("ruby_key.png",$type)."&nbsp;".___("E-Mail");}
if ($type=="domain") { $bl_typename=tm_icon("ruby_link.png",$type)."&nbsp;".___("Domain");}
if ($type=="expr") { $bl_typename=tm_icon("ruby_gear.png",$type)."&nbsp;".___("regulärer Ausdruck");}
$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$exportURLPara_."\">".tm_icon("disk.png",___("Blacklist exportieren"))."&nbsp;".sprintf(___("Blacklist vom Typ %s exportieren"),"'".$bl_typename."'")."</a><br>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];
?>