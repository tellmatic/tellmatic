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
//Statistik fuer Newsletter
/////////////////////////////////////
if ($set=="nl" && check_dbid($nl_id)) {
	//nl_id
	$N=$NEWSLETTER->getNL($nl_id);
	$_MAIN_OUTPUT.=sprintf(___("Statistik für Newsletter %s"),"<b>".display($N[0]['subject'])."</b>");

	////////////////////////////////////////////////////////////////////////////////////////
	//allg. infos
	////////////////////////////////////////////////////////////////////////////////////////
	$_MAIN_OUTPUT.="<br><br>".sprintf(___("Erstellt am: %s von %s"),"<b>".$N[0]['created']."</b>","<b>".$N[0]['author']."</b>");
	$_MAIN_OUTPUT.="<br>".sprintf(___("Bearbeitet am: %s von %s"),"<b>".$N[0]['created']."</b>","<b>".$N[0]['editor']."</b>");
	$_MAIN_OUTPUT.="<br>".sprintf(___("Inhalt-Typ: %s"),"");
	if ($N[0]['content_type']=="text/html") {
		$_MAIN_OUTPUT.=  tm_icon("page_white_office.png",___("TEXT/HTML"))."&nbsp;";
		$_MAIN_OUTPUT.="<b>".___("TEXT/HTML")."</b>";
	}
	if ($N[0]['content_type']=="html") {
		$_MAIN_OUTPUT.=  tm_icon("page_white_h.png",___("HTML"))."&nbsp;";
		$_MAIN_OUTPUT.="<b>".___("HTML")."</b>";
	}
	if ($N[0]['content_type']=="text") {
		$_MAIN_OUTPUT.=  tm_icon("page_white_text.png",___("TEXT"))."&nbsp;";
		$_MAIN_OUTPUT.="<b>".___("TEXT")."</b>";
	}

	$_MAIN_OUTPUT.="<br>".sprintf(___("Versandart: %s"),"");
	if ($N[0]['massmail']==1) {
		$_MAIN_OUTPUT.=  tm_icon("lorry.png",___("Massenmailing"))."&nbsp;";
		$_MAIN_OUTPUT.="<b>".___("Massenmailing")."</b>";
	} else {
		$_MAIN_OUTPUT.=  tm_icon("user_suit.png",___("personalisierter Newsletter"))."&nbsp;";
		$_MAIN_OUTPUT.="<b>".___("personalisierter Newsletter")."</b>";
	}
	$_MAIN_OUTPUT.="<br>".sprintf(___("Angezeigt: %s"),"<b>".$N[0]['views']."</b>");
	$_MAIN_OUTPUT.="<br>".sprintf(___("Klicks: %s"),"<b>".$N[0]['clicks']."</b>");

	////////////////////////////////////////////////////////////////////////////////////////
	//Q Status / Versandauftragsstatus Gesamt
	////////////////////////////////////////////////////////////////////////////////////////
	//countQ($nl_id=0,$grp_id=0,$status=0)
	$qc=$QUEUE->countQ($nl_id);
	$_MAIN_OUTPUT.="<br><br>".sprintf(___("%s Sendeaufträge insgesamt:"),"<b>$qc</b>");
	$_MAIN_OUTPUT.="<br>&nbsp;&nbsp;&nbsp;".___("Auftragsstatus:");
	$qsc=count($STATUS['q']['status']);
	//countQ($nl_id=0,$grp_id=0,$status=0)
	for ($qscc=1; $qscc<=$qsc; $qscc++) {
		$qc=$QUEUE->countQ($nl_id,0,$qscc);
		if ($qc>0) {
			$_MAIN_OUTPUT.="<br>".
							"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$qc.
							"&nbsp;".tm_icon($STATUS['q']['statimg'][$qscc],display($STATUS['q']['status'][$qscc])).
							"&nbsp;".display($STATUS['q']['status'][$qscc]).
							"&nbsp;(".display($STATUS['q']['descr'][$qscc]).")";
		}
	}
	$_MAIN_OUTPUT.="<br>";

	////////////////////////////////////////////////////////////////////////////////////////
	//H Status
	////////////////////////////////////////////////////////////////////////////////////////
	//prepare graph
	$chart = new HorizontalChart(640,200);
	$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
	#$chart_serie = new XYDataSet();
	$hc=$QUEUE->countH(0,$nl_id);
	//add total value to graph
	$chart->addPoint(new Point(___("Summe",0)." (100%)", $hc));
	#$chart_serie->addPoint(new Point(___("Summe")." (100%)", $hc));
	//countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0)
	$_MAIN_OUTPUT.="<br>".sprintf(___("Versand an insgesamt %s Adressen:"),"<b>$hc</b>");
	$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/nl_status_".$nl_id.".png\"><br>";
	$_MAIN_OUTPUT.="<br>&nbsp;&nbsp;&nbsp;".___("Versandstatus:");
	$hsc=count($STATUS['h']['status']);
	for ($hscc=1; $hscc<=$hsc; $hscc++) {
		$hcs=$QUEUE->countH(0,$nl_id,0,0,$hscc);
		if ($hcs>0) {
			$_MAIN_OUTPUT.="<br>".
							"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$hcs.
							"&nbsp;".tm_icon($STATUS['h']['statimg'][$hscc],display($STATUS['h']['status'][$hscc])).
							"&nbsp;".display($STATUS['h']['status'][$hscc]).
							"&nbsp;(".display($STATUS['h']['descr'][$hscc]).")";
			//add values to chart
			$qc_pc=$hcs/($hc/100);//anteil in prozent
			$chart->addPoint(new Point($STATUS['h']['status'][$hscc]." (".number_format($qc_pc, 2, ',', '')."%)", $hcs));
			#$chart_serie->addPoint(new Point($STATUS['h']['status'][$hscc]." (".number_format($qc_pc, 2, ',', '')."%)", $hcs));
		}
	}
//create chart
	#$chart_dataSet = new XYSeriesDataSet();
	#$chart_dataSet->addSerie($N[0]['subject'], $chart_serie);
	#$chart->setDataSet($chart_dataSet);
	$chart->setTitle(sprintf(___("Newsletter %s",0),"\"".$N[0]['subject']."\""));
	$chart->render($tm_reportpath."/nl_status_".$nl_id.".png");

	////////////////////////////////////////////////////////////////////////////////////////
	//Details, per Q, per Group, per Status
	////////////////////////////////////////////////////////////////////////////////////////
	$_MAIN_OUTPUT.="<hr>";
	$_MAIN_OUTPUT.="<br><br><b>".___("Details:")."</b>";

	$showadrgURLPara=tmObjCopy($mSTDURL);
	$showadrgURLPara->addParam("act","statistic");
	$showadrgURLPara->addParam("s","s_menu_adr,s_menu_st");
	$showadrgURLPara->addParam("set","adrg");
	/*
	zu jeder Q Status anzeigen
		Gruppen holen
			Gruppen anzeigen
				H Status fuer Gruppe!
	*/
	$Q=$QUEUE->getQ(0,0,0,$nl_id);
	//getQ($id=0,$offset=0,$limit=0,$nl_id=0,$grp_id=0,$status=0)
	$qc=count($Q);
	for ($qcc=0;$qcc<$qc;$qcc++) {
		$AG=$ADDRESS->getGroup($Q[$qcc]['grp_id']);
		$showadrgURLPara->addParam("adr_grp_id",$AG[0]['id']);
		$showadrgURLPara_=$showadrgURLPara->getAllParams();
		$_MAIN_OUTPUT.="<br>".
							"<br>&nbsp;&nbsp;".
							sprintf(___("Versand an Gruppe %s"),"<a href=\"".$tm_URL."/".$showadrgURLPara_."\"><b>".display($AG[0]['name'])."</b> (".tm_icon("chart_pie.png",___("Statistik anzeigen")).")</a>");
		$_MAIN_OUTPUT.=":&nbsp;".tm_icon($STATUS['q']['statimg'][$Q[$qcc]['status']],display($STATUS['q']['status'][$Q[$qcc]['status']])).
							"&nbsp;".display($STATUS['q']['status'][$Q[$qcc]['status']]).
							"&nbsp;(".display($STATUS['q']['descr'][$Q[$qcc]['status']]).")";
		$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/nl_".$N[0]['id']."_adrgrp_".$AG[0]['id']."_".$Q[$qcc]['id'].".png\"><br>";
		$_MAIN_OUTPUT.="&nbsp;&nbsp;".sprintf(___("Erstellt am: %s von %s"),"<b>".$Q[$qcc]['created']."</b>","<b>".$Q[$qcc]['author']."</b>");
		$_MAIN_OUTPUT.="<br>&nbsp;&nbsp;".sprintf(___("Versand gestartet: %s"),"<b>".$Q[$qcc]['send_at']."</b>");
		$_MAIN_OUTPUT.="<br>&nbsp;&nbsp;".sprintf(___("Versand abgeschlossen: %s"),"<b>".$Q[$qcc]['sent']."</b>");
		//countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0)
		$hsc=count($STATUS['h']['status']);
		//prepare graph
		$chart = new HorizontalChart(640,200);
		$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
		$hc=$QUEUE->countH($Q[$qcc]['id'],$Q[$qcc]['nl_id'],$AG[0]['id']);
		//add total value to graph
		$chart->addPoint(new Point(___("Summe",0)." (100%)", $hc));

		for ($hscc=1; $hscc<=$hsc; $hscc++) {
			$hcs=$QUEUE->countH($Q[$qcc]['id'],$Q[$qcc]['nl_id'],$AG[0]['id'],0,$hscc);
			if ($hcs>0) {
				$_MAIN_OUTPUT.="<br>".
								"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$hcs.
								"&nbsp;".tm_icon($STATUS['h']['statimg'][$hscc],display($STATUS['h']['status'][$hscc])).
								"&nbsp;".display($STATUS['h']['status'][$hscc]).
								"&nbsp;(".display($STATUS['h']['descr'][$hscc]).")";
				//add values to chart
				$qc_pc=$hcs/($hc/100);//anteil in prozent
				$chart->addPoint(new Point($STATUS['h']['status'][$hscc]." (".number_format($qc_pc, 2, ',', '')."%)", $hcs));
			}
		}
		$_MAIN_OUTPUT.="<hr>";
		//create chart
		$chart->setTitle(sprintf(___("Newsletter %s an Gruppe %s",0),"\"".$N[0]['subject']."\"","\"".$AG[0]['name']."\""));
		$chart->render($tm_reportpath."/nl_".$N[0]['id']."_adrgrp_".$AG[0]['id']."_".$Q[$qcc]['id'].".png");

	}//for qcc
}//nl
?>