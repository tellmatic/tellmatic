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

$_MAIN_DESCR=___("Status");
$_MAIN_MESSAGE.="";

$NEWSLETTER=new tm_NL();
$ADDRESS=new tm_ADR();
$QUEUE=new tm_Q();
$FORMULAR=new tm_FRM();

$shownlgURLPara=tmObjCopy($mSTDURL);
$shownlgURLPara->addParam("act","nl_grp_list");
$shownlgURLPara->addParam("s","s_menu_nl,s_menu_st");
$shownlgURLPara_=$shownlgURLPara->getAllParams();

$shownlURLPara=tmObjCopy($mSTDURL);
$shownlURLPara->addParam("s","s_menu_nl,s_menu_st");

$showformURLPara=tmObjCopy($mSTDURL);
$showformURLPara->addParam("s","s_menu_frm,s_menu_st");

$showadrURLPara=tmObjCopy($mSTDURL);
$showadrURLPara->addParam("act","statistic");
$showadrURLPara->addParam("s","s_menu_adr,s_menu_st");
$showadrURLPara->addParam("set","adr");

$showadrgURLPara=tmObjCopy($mSTDURL);
$showadrgURLPara->addParam("act","statistic");
$showadrgURLPara->addParam("s","s_menu_adr,s_menu_st");
$showadrgURLPara->addParam("set","adrg");

$showgrpURLPara=tmObjCopy($mSTDURL);
$showgrpURLPara->addParam("act","adr_grp_list");
$showgrpURLPara->addParam("s","s_menu_adr,s_menu_st");
//$showadrURLPara->addParam("set","search");//wird nicht mehr benoteigt.... suchmaske bestandteil der liste!
$showadrURLPara_=$showadrURLPara->getAllParams();
$showgrpURLPara_=$showgrpURLPara->getAllParams();

//übersicht
$_MAIN_OUTPUT.="<div class=\"adr_summary\">";#ccdddd
$_MAIN_OUTPUT.=tm_icon("information.png",___("Übersicht"),___("Übersicht"))."&nbsp;<b>".___("Übersicht").":</b>";

//anzahl alle
$entrys_all=$ADDRESS->countADR();//anzahl eintraege, alles
$_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen Gesamt"),"<b>".$entrys_all."</b>");

//anzahl recheck alle
$search_recheck=Array();
$search_recheck['recheck']=1;
$entrys_recheck=$ADDRESS->countADR(0,$search_recheck);//anzahl eintraege die zur pruefung markiert sind
if ($entrys_recheck>0) $_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen sind zur Prüfung vorgemerkt"),"<b>".$entrys_recheck."</b>");

//anzahl inaktiv/deaktivert
$search_inaktiv=Array();
$search_inaktiv['aktiv']='0';
$entrys_inaktiv=$ADDRESS->countADR(0,$search_inaktiv);
if ($entrys_inaktiv>0) $_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen sind deaktiviert"),"<b>".$entrys_inaktiv."</b>");

$_MAIN_OUTPUT.="<br>".sprintf(___("%s Dubletten"),"<b>".($ADDRESS->count_duplicates())."</b>");
$_MAIN_OUTPUT.="<br></div>";




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Gesamt nach Status
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//$_MAIN_OUTPUT.="<br><br>";

$_MAIN_OUTPUT.="<br><center><table border=0><tr><td valign=\"top\" align=\"left\">";// width=50%<h1>".___("Gesamtstatus")."</h1><br>
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
//Adressen-Gruppen
////////////////////////////////////////////////////////////////////////////////////////
//prepare chart
#$chart = new PieChart(640,480);
$chart = new HorizontalChart(640,360);
$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/status_adrg_total_".TM_TODAY.".png\"><br>";
//	function getGroup($id=0,$adr_id=0,$frm_id=0,$count=0) {
$AG=$ADDRESS->getGroup(0,0,0,1);//count!
$agc=count($AG);
$ac=$ADDRESS->countAdr();
$chart->addPoint(new Point(___("Summe",0)." (100%)", $ac));
$showadrURLPara->delParam("email");
$showadrURLPara->delParam("adr_id");
$showadrURLPara->addParam("act","adr_list");
$showadrURLPara_=$showadrURLPara->getAllParams();
$_MAIN_OUTPUT.="<br><center>";
#$_MAIN_OUTPUT.="<h3>".___("Adressgruppen ").TM_TODAY."</h3>";
$_MAIN_OUTPUT.="<table border=0 width=\"100%\" style=\"border:1px solid #eeeeee;\">";
$_MAIN_OUTPUT.="<thead>";
$_MAIN_OUTPUT.="<tr>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$showgrpURLPara_."\">".sprintf(___("%s Gruppen"),$agc)."&nbsp;".tm_icon("folder_go.png",___("Liste anzeigen"))."</a>";
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$showadrURLPara_."\"> ".sprintf(___("%s Adressen"),$ac)."&nbsp;".tm_icon("folder_go.png",___("Liste anzeigen"))."</a>";
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.=tm_icon("chart_pie.png",___("Statistik anzeigen"));
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="</tr>";
$_MAIN_OUTPUT.="</thead>";
for ($agcc=0; $agcc<$agc; $agcc++) {
	if ($agcc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	$showadrgURLPara->addParam("adr_grp_id",$AG[$agcc]['id']);
	$showadrgURLPara_=$showadrgURLPara->getAllParams();
	//create table
	$_MAIN_OUTPUT.= "<tr id=\"row_g".$agcc."\"  bgcolor=\"".$bgcolor."\"  onmouseover=\"setBGColor('row_g".$agcc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_g".$agcc."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.=display($AG[$agcc]['name']);
	$_MAIN_OUTPUT.="</td>";
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.=$AG[$agcc]['adr_count']." ".___("Adressen")."<br>";
	$_MAIN_OUTPUT.="</td>";
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$showadrgURLPara_."\">".tm_icon("chart_pie.png",___("Statistik anzeigen"))."</a>";
	$_MAIN_OUTPUT.="</td>";
	$_MAIN_OUTPUT.="</tr>";
	//add values to chart
	$adc_pc=$AG[$agcc]['adr_count']/($ac/100);//anteil in prozent
	$chart->addPoint(new Point($AG[$agcc]['name']." (".number_format($adc_pc, 2, ',', '')."%)", $AG[$agcc]['adr_count']));
}
$_MAIN_OUTPUT.="</table>";
//create chart
$chart->setTitle(___("Adressgruppen",0)." ".TM_TODAY);
$chart->render($tm_reportpath."/status_adrg_total_".TM_TODAY.".png");

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

$_MAIN_OUTPUT.="</td></tr><tr><td valign=\"top\" align=\"left\" style=\"border-top:1px solid #000000\">";// width=50%

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
//Adressen:
////////////////////////////////////////////////////////////////////////////////////////
//prepare chart
#$chart = new PieChart(640,480);
$chart = new HorizontalChart(640,360);
$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/status_adr_total_".TM_TODAY.".png\"><br>";
//
$AG=$ADDRESS->getGroup();
$agc=count($AG);
#$ac=$ADDRESS->countADR();
$chart->addPoint(new Point(___("Summe",0)." (100%)", $ac));
//countADR($group_id=0,$search=Array())
$showadrURLPara->delParam("email");
$showadrURLPara->delParam("adr_id");
$showadrURLPara->addParam("act","adr_list");
$showadrURLPara_=$showadrURLPara->getAllParams();
#$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$showadrURLPara_."\">".sprintf(___("%s Adressen"),$ac)."</a> ::: <a href=\"".$tm_URL."/".$showgrpURLPara_."\">".sprintf(___("%s Gruppen"),$agc)."</a>";
$asc=count($STATUS['adr']['status']);
$_MAIN_OUTPUT.="<br><center>";
#$_MAIN_OUTPUT.="<h3>".___("Adressen ").TM_TODAY."</h3>";
$_MAIN_OUTPUT.="<table border=0 width=\"100%\" style=\"border:1px solid #eeeeee;\">";
$_MAIN_OUTPUT.="<thead>";
$_MAIN_OUTPUT.="<tr>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.=___("Adressen");
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.=___("Status");
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.=tm_icon("folder_go.png",___("Anzeigen"));
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="</tr>";
$_MAIN_OUTPUT.="</thead>";
for ($ascc=1; $ascc<=$asc; $ascc++)//0
{
	if ($ascc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	$search['status']=$ascc;
	$showadrURLPara->addParam("s_status",$ascc);
	$showadrURLPara_=$showadrURLPara->getAllParams();
	$adc=$ADDRESS->countADR(0,$search);
	$_MAIN_OUTPUT.= "<tr id=\"row_s".$ascc."\"  bgcolor=\"".$bgcolor."\"  onmouseover=\"setBGColor('row_s".$ascc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_s".$ascc."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.=$adc;
	$_MAIN_OUTPUT.="</td>";	
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.=tm_icon($STATUS['adr']['statimg'][$ascc],display($STATUS['adr']['status'][$ascc]))."&nbsp;".display($STATUS['adr']['status'][$ascc])."&nbsp;(".display($STATUS['adr']['descr'][$ascc]).")	";
	$_MAIN_OUTPUT.="</td>";	
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$showadrURLPara_."\" title=\"".___("Anzeigen")."\">".tm_icon("folder_go.png",___("Anzeigen"))."</a>";
	$_MAIN_OUTPUT.="</td>";	
/*
	$_MAIN_OUTPUT.="<br>".
						"&nbsp;&nbsp;&nbsp;<a href=\"".$tm_URL."/".$showadrURLPara_."\">".$adc."</a>".
							"&nbsp;".tm_icon($STATUS['adr']['statimg'][$ascc],$STATUS['adr']['status'][$ascc]).
							"&nbsp;".$STATUS['adr']['status'][$ascc].
							"&nbsp;(".$STATUS['adr']['descr'][$ascc].")	";
*/
	$_MAIN_OUTPUT.="</td>";
	$_MAIN_OUTPUT.="</tr>";
	
	//add values to chart
	$adc_pc=$adc/($ac/100);//anteil in prozent
	$chart->addPoint(new Point($STATUS['adr']['status'][$ascc]." (".number_format($adc_pc, 2, ',', '')."%)", $adc));
}
$_MAIN_OUTPUT.="</table>";
//create chart
$chart->setTitle(___("Adressen",0)." ".TM_TODAY);
$chart->render($tm_reportpath."/status_adr_total_".TM_TODAY.".png");

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

$_MAIN_OUTPUT.="</td></tr><tr><td valign=\"top\" align=\"left\" style=\"border-top:1px solid #000000\">";// width=50%

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
//Newsletter Queue:
////////////////////////////////////////////////////////////////////////////////////////
//prepare chart
#$chart = new PieChart(640,480);
$chart = new HorizontalChart(640,360);
$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/status_q_total_".TM_TODAY.".png\"><br>";
$NG=$NEWSLETTER->getGroup();
$nlgc=count($NG);
$N=$NEWSLETTER->getNLID();//$group
$nlc=count($N);
$hc=$QUEUE->countH();
//add total value to graph
$chart->addPoint(new Point(___("Summe",0)." (100%)", $hc));
//countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0)
$shownlURLPara->addParam("act","nl_list");
$shownlURLPara->addParam("set","");
$shownlURLPara->delParam("nl_id","");
$shownlURLPara_=$shownlURLPara->getAllParams();
$hsc=count($STATUS['h']['status']);
$_MAIN_OUTPUT.="<br><center>";
#$_MAIN_OUTPUT.="<h3>".___("Newsletter Queue")." ".TM_TODAY."</h3>";
$_MAIN_OUTPUT.="<table border=0 width=\"100%\" style=\"border:1px solid #eeeeee;\">";
$_MAIN_OUTPUT.="<thead>";
$_MAIN_OUTPUT.="<tr>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\" colspan=2>";
$_MAIN_OUTPUT.="<a href=\"".$tm_URL."/".$shownlURLPara_."\">".sprintf(___("%s Newsletter"),$nlc)." (".tm_icon("folder_go.png",___("Liste anzeigen")).")</a> ::: <a href=\"".$tm_URL."/".$shownlgURLPara_."\">".sprintf(___("%s Gruppen"),$nlgc)." (".tm_icon("folder_go.png",___("Liste anzeigen")).")</a>";
$_MAIN_OUTPUT.="<br><br><b>".sprintf(___("Insgesamt %s Mails im Versand:"),$hc)."</b>";
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="</tr>";
$_MAIN_OUTPUT.="<tr>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.=___("Mails");
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
$_MAIN_OUTPUT.=___("Status");
$_MAIN_OUTPUT.="</td>";
$_MAIN_OUTPUT.="</tr>";
$_MAIN_OUTPUT.="</thead>";

for ($hscc=1; $hscc<=$hsc; $hscc++)//0
{
	if ($hscc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	$qc=$QUEUE->countH(0,0,0,0,$hscc);
	$_MAIN_OUTPUT.= "<tr id=\"row_h".$hscc."\"  bgcolor=\"".$bgcolor."\"  onmouseover=\"setBGColor('row_h".$hscc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_h".$hscc."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.=$qc;
	$_MAIN_OUTPUT.="</td>";	
	$_MAIN_OUTPUT.="<td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.=tm_icon($STATUS['h']['statimg'][$hscc],display($STATUS['h']['status'][$hscc])).
										"&nbsp;".display($STATUS['h']['status'][$hscc]).
										"&nbsp;(".display($STATUS['h']['descr'][$hscc]).")";
	$_MAIN_OUTPUT.="</td>";	
	$_MAIN_OUTPUT.="</tr>";	

/*
	$_MAIN_OUTPUT.="<br>".
						"&nbsp;&nbsp;&nbsp;".$qc.
							"&nbsp;".tm_icon($STATUS['h']['statimg'][$hscc],$STATUS['h']['status'][$hscc]).
							"&nbsp;".$STATUS['h']['status'][$hscc].
							"&nbsp;(".$STATUS['h']['descr'][$hscc].")";
*/
	//add values to chart
	$qc_pc=$qc/($hc/100);//anteil in prozent
	$chart->addPoint(new Point($STATUS['h']['status'][$hscc]." (".number_format($qc_pc, 2, ',', '')."%)", $qc));
}
$_MAIN_OUTPUT.="</table>";	
//create chart
$chart->setTitle(___("Newsletter Queue",0)." ".TM_TODAY);
$chart->render($tm_reportpath."/status_q_total_".TM_TODAY.".png");

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
$_MAIN_OUTPUT.="</td></tr>".
					"</table>".
					"</center>";

$_MAIN_OUTPUT.= "<br><br>";
?>