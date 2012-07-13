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
$Form_P->render_Form($FormularName_P);
//then you dont have to render the head and foot .....
/*DISPLAY*/
$_MAIN_OUTPUT.= $Form_P->FORM[$FormularName_P]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form_P->INPUT[$FormularName_P]['act']['html'];

$_MAIN_OUTPUT.= "<table border=0 width=\"100%\">";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=1>";
$_MAIN_OUTPUT.=  tm_icon("page_go.png",___("Seite"))."&nbsp;".$Form_P->INPUT[$FormularName_P][$InputName_Page]['html'];
$_MAIN_OUTPUT.= $Form_P->INPUT[$FormularName_P][$InputName_Submit_P]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form_P->FORM[$FormularName_P]['foot'];
?>