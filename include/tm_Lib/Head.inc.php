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

$_SITEID="";
if ($logged_in) $_SITEID=TM_SITEID;
$_USER="";
if ($logged_in) $_USER=$LOGIN->USER['name'];

//new Template
$_Tpl_Head=new tm_Template();
$_Tpl_Head->setTemplatePath(TM_TPLPATH."/".$Style);
$_Tpl_Head->setParseValue("_SITEID", $_SITEID);
$_Tpl_Head->setParseValue("_USER", $_USER);
$_HEAD=$_Tpl_Head->renderTemplate("Head.html");
?>
