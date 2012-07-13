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

$_MAIN_DESCR=___("Hilfe / Dokumentation");
$_MAIN_MESSAGE.="";//<br>Hier wird Ihnen hoffentlich geholfen :)";

//new Template
$_Tpl_Help=new tm_Template();
$_Tpl_Help->setTemplatePath(TM_TPLPATH);
//$_Tpl_Help->setParseValue("_MENU", $_MENU);

$_MAIN_OUTPUT.=$_Tpl_Help->renderTemplate("Help.html");
?>