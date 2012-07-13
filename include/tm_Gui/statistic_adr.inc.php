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

/////////////////////////////////////
//Statistik fuer Adressen
/////////////////////////////////////
if ($set=="adr" && check_dbid($adr_id)) {
	//nl_id
	$ADR=$ADDRESS->getADR($adr_id);

	////////////////////////////////////////////////////////////////////////////////////////
	//allg. Infos
	////////////////////////////////////////////////////////////////////////////////////////
	$_MAIN_OUTPUT.=sprintf(___("Statistik fuer Adresse %s"),"<b>".display($ADR[0]['email'])."</b>");
	$_MAIN_OUTPUT.= "<br>ID: ".$ADR[0]['id']." / ".$ADR[0]['d_id']." ";
	if ($ADR[0]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}

	$_MAIN_OUTPUT.= "".___("Gespeicherte Daten:")."";
	$_MAIN_OUTPUT.= "f0: ".$ADR[0]['f0'];
	$_MAIN_OUTPUT.= "<br>f1: ".$ADR[0]['f1'];
	$_MAIN_OUTPUT.= "<br>f2: ".$ADR[0]['f2'];
	$_MAIN_OUTPUT.= "<br>f3: ".$ADR[0]['f3'];
	$_MAIN_OUTPUT.= "<br>f4: ".$ADR[0]['f4'];
	$_MAIN_OUTPUT.= "<br>f5: ".$ADR[0]['f5'];
	$_MAIN_OUTPUT.= "<br>f6: ".$ADR[0]['f6'];
	$_MAIN_OUTPUT.= "<br>f7: ".$ADR[0]['f7'];
	$_MAIN_OUTPUT.= "<br>f8: ".$ADR[0]['f8'];
	$_MAIN_OUTPUT.= "<br>f9: ".$ADR[0]['f9'];
	$_MAIN_OUTPUT.="";
	/*
	$created_date=strftime("%d-%m-%Y",mk_microtime($ADR[0]['created']));
	$updated_date=strftime("%d-%m-%Y",mk_microtime($ADR[0]['updated']));
	*/
	$created_date=$ADR[0]['created'];
	$updated_date=$ADR[0]['updated'];

	$author=$ADR[0]['author'];
	$editor=$ADR[0]['editor'];

	if (is_numeric($author)) {
		$author="Form_".$author;
	}
	if (is_numeric($editor)) {
		$editor="Form_".$editor;
	}

	$_MAIN_OUTPUT.= " ".___("Status:")."&nbsp;".tm_icon($STATUS['adr']['statimg'][$ADR[0]['status']],display($STATUS['adr']['status'][$ADR[0]['status']])).display($STATUS['adr']['status'][$ADR[0]['status']]);
	$nlc=$QUEUE->countH(0,0,0,$ADR[0]['id'],0);
	$_MAIN_OUTPUT.= "<br>CODE: ".$ADR[0]['code']." &nbsp;".
							"<br>".sprintf(___("erstellt am: %s von: %s"),$created_date,$author).
							"<br>".sprintf(___("bearbeitet am: %s von %s"),$updated_date,$editor).
							"<br>".sprintf(___("Newsletter Aktuell: %s"),$nlc).
							"<br>".sprintf(___("Newsletter Gesamt: %s"),$ADR[0]['newsletter']).
							"<br>".sprintf(___("Views: %s"),$ADR[0]['views']).
							"<br>".sprintf(___("Clicks: %s"),$ADR[0]['clicks']).
							"<br>".sprintf(___("Sendefehler: %s"),$ADR[0]['errors'])." &nbsp;";

	////////////////////////////////////////////////////////////////////////////////////////
	//H Status
	////////////////////////////////////////////////////////////////////////////////////////
	//prepare graph
	$chart = new HorizontalChart(640,200);
	$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
	//	function countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0) {	//countH($nl_id=0,$grp_id=0,$status=0)
	$hc=$QUEUE->countH(0,0,0,$adr_id,0);
	//add total value to graph
	$chart->addPoint(new Point(___("Summe",0)." (100%)", $hc));
	$_MAIN_OUTPUT.="<b>$hc</b> ".___("Sendeaufträge insgesamt:");
	$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/adr_".$adr_id.".png\"><br>";
	$_MAIN_OUTPUT.="<br>".___("Auftragsstatus:")."";
	$hsc=count($STATUS['h']['status']);
	//countQ($nl_id=0,$grp_id=0,$status=0)
	for ($hscc=1; $hscc<=$hsc; $hscc++) {
		$hcs=$QUEUE->countH(0,0,0,$adr_id,$hscc);
		if ($hcs>0) {
			$_MAIN_OUTPUT.="	&nbsp;".$hcs.
							"&nbsp;".tm_icon($STATUS['h']['statimg'][$hscc],display($STATUS['h']['status'][$hscc])).
							"&nbsp;".display($STATUS['h']['status'][$hscc]).
							"&nbsp;(".display($STATUS['h']['descr'][$hscc]).")<br>";
			//add values to chart
			$ac_pc=$hcs/($hc/100);//anteil in prozent
			$chart->addPoint(new Point($STATUS['h']['status'][$hscc]." (".number_format($ac_pc, 2, ',', '')."%)", $hcs));
		}
	}
	//create chart
	$chart->setTitle(sprintf(___("Empfänger %s",0),"\"".$ADR[0]['email']."\""));
	$chart->render($tm_reportpath."/adr_".$adr_id.".png");

	$_MAIN_OUTPUT.="<br>";
	//function getH($id=0,$offset=0,$limit=0,$q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0) {

	////////////////////////////////////////////////////////////////////////////////////////
	//Versand Detail, empfangene/an Empf. gesendete Newsletter
	////////////////////////////////////////////////////////////////////////////////////////
	$_MAIN_OUTPUT.="".___("Versand-Details:")."";

	$shownlURLPara=tmObjCopy($mSTDURL);
	$shownlURLPara->addParam("s","s_menu_nl,s_menu_st");
	$shownlURLPara->addParam("act","statistic");
	$shownlURLPara->addParam("set","nl");

	$showadrgURLPara=tmObjCopy($mSTDURL);
	$showadrgURLPara->addParam("act","statistic");
	$showadrgURLPara->addParam("s","s_menu_adr,s_menu_st");
	$showadrgURLPara->addParam("set","adrg");

	$H=$QUEUE->getH(0,0,0,0,0,0,$adr_id,0);
	$hc=count($H);
	for ($hcc=0;$hcc<$hc;$hcc++) {
		$NL=$NEWSLETTER->getNL($H[$hcc]['nl_id']);
		$G=$ADDRESS->getGroup($H[$hcc]['grp_id']);
		$Q=$QUEUE->getQ($H[$hcc]['q_id']);

		$showadrgURLPara->addParam("adr_grp_id",$H[$hcc]['grp_id']);
		$showadrgURLPara_=$showadrgURLPara->getAllParams();

		$shownlURLPara->addParam("nl_id",$H[$hcc]['nl_id']);
		$shownlURLPara_=$shownlURLPara->getAllParams();

		//ggf. ist die Gruppe geloescht worden...:
		if (!isset($G[0])){
			$G[0]['name']=___("k.A. - Gruppe gelöscht!");
		}
			$_MAIN_OUTPUT.="<br><br><a href=\"".$tm_URL."/".$shownlURLPara_."\"><b>".display($NL[0]['subject'])."</b> (".tm_icon("chart_pie.png",___("Statistik anzeigen")).")</a>";
			$_MAIN_OUTPUT.=	"<br>".___("an Adressgruppe")." <a href=\"".$tm_URL."/".$showadrgURLPara_."\"><b>".display($G[0]['name'])."</b> (".tm_icon("chart_pie.png",___("Statistik anzeigen")).")</a>:";

			$_MAIN_OUTPUT.="<br>&nbsp;".tm_icon($STATUS['h']['statimg'][$H[$hcc]['status']],display($STATUS['h']['status'][$H[$hcc]['status']])).
									"&nbsp;".display($STATUS['h']['status'][$H[$hcc]['status']]).
									"&nbsp;(".display($STATUS['h']['descr'][$H[$hcc]['status']]).")";
			$_MAIN_OUTPUT.="<br>".sprintf(___("Erstellt am: %s"),$H[$hcc]['created']);
			if (!empty($Q[0]['send_at'])) {
				$_MAIN_OUTPUT.="<br>".sprintf(___("Q Versand (start): %s"),$Q[0]['send_at']);
			}
			if (!empty($H[$hcc]['sent'])) {
				$_MAIN_OUTPUT.="<br>".sprintf(___("Versendet: %s"),$H[$hcc]['sent']);
			}
			$_MAIN_OUTPUT.="";
	}
	$_MAIN_OUTPUT.="";
}//adr
?>