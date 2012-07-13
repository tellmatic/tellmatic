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

$_MAIN_DESCR=___("Top X");
$_MAIN_MESSAGE.="";

$NEWSLETTER=new tm_NL();
$ADDRESS=new tm_ADR();
$QUEUE=new tm_Q();
$FORMULAR=new tm_FRM();

$shownlURLPara=tmObjCopy($mSTDURL);
$shownlURLPara->addParam("s","s_menu_nl,s_menu_st");

$showformURLPara=tmObjCopy($mSTDURL);
$showformURLPara->addParam("s","s_menu_frm,s_menu_st");

$showadrURLPara=tmObjCopy($mSTDURL);
$showadrURLPara->addParam("act","statistic");
$showadrURLPara->addParam("s","s_menu_adr,s_menu_st");
$showadrURLPara->addParam("set","adr");

$InputName_TopX="topx";
$show_top_x=getVar("topx");
if (empty($show_top_x)) {
	$show_top_x=5;
}

////////////////////////////////////////////////////////////////////////////////////////
require_once (TM_INCLUDEPATH_GUI."/status_top_x_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/status_top_x_form_show.inc.php");
////////////////////////////////////////////////////////////////////////////////////////

$_MAIN_OUTPUT.="<center>";
$_MAIN_OUTPUT.="<table border=0 ><tr><td valign=top align=left>";

////////////////////////////////////////////////////////////////////////////////////////
//NL Views
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="<h2>".sprintf(___("TOP %s - Newsletter"),$show_top_x)."</h2>";
$_MAIN_OUTPUT.="<b>".___("Views")."</b>";
$shownlURLPara->addParam("act","statistic");
$shownlURLPara->addParam("set","nl");
$NL=$NEWSLETTER->getNL(0,0,$show_top_x,0,0,"views",1);
$nc=count($NL);
for ($ncc=0;$ncc<$nc;$ncc++) {
	if (!empty($NL[$ncc]['views'])) {
		$shownlURLPara->addParam("nl_id",$NL[$ncc]['id']);
		$shownlURLPara_=$shownlURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".($ncc+1).".) <a href=\"".$tm_URL."/".$shownlURLPara_."\">".$NL[$ncc]['subject']."</a> (".$NL[$ncc]['views'].")";
	}
}

////////////////////////////////////////////////////////////////////////////////////////
//NL - Clicks
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="<br><b>".___("Klicks")."</b>";
$NL=$NEWSLETTER->getNL(0,0,$show_top_x,0,0,"clicks",1);
$nc=count($NL);
for ($ncc=0;$ncc<$nc;$ncc++) {
	if (!empty($NL[$ncc]['clicks'])) {
		$shownlURLPara->addParam("nl_id",$NL[$ncc]['id']);
		$shownlURLPara_=$shownlURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".($ncc+1).".) <a href=\"".$tm_URL."/".$shownlURLPara_."\">".$NL[$ncc]['subject']."</a> (".$NL[$ncc]['clicks'].")";
	}
}

////////////////////////////////////////////////////////////////////////////////////////
//FORMS
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="<h2>".sprintf(___("TOP %s - Formulare"),$show_top_x)."</h2>";
$_MAIN_OUTPUT.="<b>".___("Anmeldungen")."</b>";
$showformURLPara->addParam("act","statistic");
$showformURLPara->addParam("set","frm");
$FRM=$FORMULAR->getForm(0,0,$show_top_x,0,"subscriptions",1);
$fc=count($FRM);
for ($fcc=0;$fcc<$fc;$fcc++) {
	if (!empty($FRM[$fcc]['subscriptions'])) {
		$showformURLPara->addParam("frm_id",$FRM[$fcc]['id']);
		$showformURLPara_=$showformURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".($fcc+1).".) <a href=\"".$tm_URL."/".$showformURLPara_."\">".$FRM[$fcc]['name']."</a> (".$FRM[$fcc]['subscriptions'].")";
	}
}

////////////////////////////////////////////////////////////////////////////////////////
//LESER - Views
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="</td></tr><tr><td valign=top align=left>";// width=50%

$_MAIN_OUTPUT.="<h2>".sprintf(___("TOP %s - Abonnenten"),$show_top_x)."</h2>";
$_MAIN_OUTPUT.="<b>".___("Views")."</b>";
$ADR=$ADDRESS->getAdr(0,0,$show_top_x,0,Array(),"views",1);
$ac=count($ADR);
for ($acc=0;$acc<$ac;$acc++) {
	if (!empty($ADR[$acc]['views'])) {
		$showadrURLPara->addParam("email",$ADR[$acc]['email']);
		$showadrURLPara->addParam("adr_id",$ADR[$acc]['id']);
		$showadrURLPara_=$showadrURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".($acc+1).".) <a href=\"".$tm_URL."/".$showadrURLPara_."\">".$ADR[$acc]['email']."</a> (".$ADR[$acc]['views'].")";
	}
}
////////////////////////////////////////////////////////////////////////////////////////
//LESER - Clicks
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="<br><b>".___("Klicks")."</b>";
$ADR=$ADDRESS->getAdr(0,0,$show_top_x,0,Array(),"clicks",1);
$ac=count($ADR);
for ($acc=0;$acc<$ac;$acc++) {
	if (!empty($ADR[$acc]['clicks'])) {
		$showadrURLPara->addParam("email",$ADR[$acc]['email']);
		$showadrURLPara->addParam("adr_id",$ADR[$acc]['id']);
		$showadrURLPara_=$showadrURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".($acc+1).".) <a href=\"".$tm_URL."/".$showadrURLPara_."\">".$ADR[$acc]['email']."</a> (".$ADR[$acc]['clicks'].")";
	}
}
////////////////////////////////////////////////////////////////////////////////////////
//LESER - NL
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="<br><b>".___("Anz. empfangene NL")."</b>";
$ADR=$ADDRESS->getAdr(0,0,$show_top_x,0,Array(),"newsletter",1);
$ac=count($ADR);
for ($acc=0;$acc<$ac;$acc++) {
	if (!empty($ADR[$acc]['newsletter'])) {
		$showadrURLPara->addParam("email",$ADR[$acc]['email']);
		$showadrURLPara->addParam("adr_id",$ADR[$acc]['id']);
		$showadrURLPara_=$showadrURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".($acc+1).".) <a href=\"".$tm_URL."/".$showadrURLPara_."\">".$ADR[$acc]['email']."</a> (".$ADR[$acc]['newsletter'].")";
	}
}
$_MAIN_OUTPUT.="</td></tr>".
						"<tr><td align=center><font size=-1>(".sprintf(___("Die TOP %s Liste  zählt auch doppelte Klicks und Views!"),$show_top_x).")</font></td></tr>".
						"</table></center>";

$_MAIN_OUTPUT.= "<br><br>";
?>