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
include_once (TM_INCLUDEPATH_LIB_EXT."/libchart/libchart.php");

$_MAIN_DESCR=___("Statistik");

$set=getVar("set");
$nl_id=getVar("nl_id");
$adr_grp_id=getVar("adr_grp_id");
$adr_id=getVar("adr_id");
$frm_id=getVar("frm_id");

$FORMULAR=new tm_FRM();
$QUEUE=new tm_Q();
$ADDRESS=new tm_ADR();
$NEWSLETTER=new tm_NL();

$_MAIN_MESSAGE.="";
include_once (TM_INCLUDEPATH_GUI."/statistic_nl.inc.php");
include_once (TM_INCLUDEPATH_GUI."/statistic_adr.inc.php");
include_once (TM_INCLUDEPATH_GUI."/statistic_adrgrp.inc.php");
include_once (TM_INCLUDEPATH_GUI."/statistic_frm.inc.php");
$_MAIN_OUTPUT.= "<br><br>";
?>