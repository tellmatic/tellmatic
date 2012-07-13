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
//Statistik fuer Adressgruppe
/////////////////////////////////////
if ($set=="adrg" && check_dbid($adr_grp_id)) {

	$showadrURLPara=tmObjCopy($mSTDURL);
	$showadrURLPara->addParam("act","adr_list");
	$showadrURLPara->addParam("adr_grp_id",$adr_grp_id);
	$showadrURLPara->addParam("s","s_menu_adr,s_menu_st");

	//prepare graph
	$chart = new HorizontalChart(640,200);
	$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png
	//adr_grp_id
	$AG=$ADDRESS->getGroup($adr_grp_id);


	$_MAIN_OUTPUT.=sprintf(___("Statistik für Adressgruppe %s"),"<b>".display($AG[0]['name'])."</b>");

	////////////////////////////////////////////////////////////////////////////////////////
	//Gesamtstatus, anzahl per adr-status
	////////////////////////////////////////////////////////////////////////////////////////
	$ac=$ADDRESS->countADR($adr_grp_id);
	//add total value to graph
	$chart->addPoint(new Point(___("Summe",0)." (100%)", $ac));
	$_MAIN_OUTPUT.="<br>".sprintf(___("Insgesamt %s Adressen:"),"<b>".$ac)."</b>";
	$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/adrg_".$adr_grp_id.".png\"><br>";
	//per adr status:
	$asc=count($STATUS['adr']['status']);
	for ($ascc=1; $ascc<=$asc; $ascc++) {
		$search['status']=$ascc;
		$showadrURLPara->addParam("s_status",$search['status']);
		$showadrURLPara_=$showadrURLPara->getAllParams();
		$acs=$ADDRESS->countADR($adr_grp_id,$search);
		if ($acs>0) {
			$_MAIN_OUTPUT.="<br>".
							"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$tm_URL."/".$showadrURLPara_."\">".$acs.
							"&nbsp;".tm_icon($STATUS['adr']['statimg'][$ascc],display($STATUS['adr']['status'][$ascc])).
							"&nbsp;".display($STATUS['adr']['status'][$ascc])."</a>".
							"&nbsp;(".display($STATUS['adr']['descr'][$ascc]).")";
			//add values to chart
			$ac_pc=$acs/($ac/100);//anteil in prozent
			$chart->addPoint(new Point($STATUS['adr']['status'][$ascc]." (".number_format($ac_pc, 2, ',', '')."%)", $acs));
		}
	}
	//create chart
	$chart->setTitle(sprintf(___("Empfängergruppe %s",0),"\"".$AG[0]['name']."\""));
	$chart->render($tm_reportpath."/adrg_".$adr_grp_id.".png");

	$_MAIN_OUTPUT.="<hr>";

	////////////////////////////////////////////////////////////////////////////////////////
	//Q Status per Newsletter
	////////////////////////////////////////////////////////////////////////////////////////
	$shownlURLPara=tmObjCopy($mSTDURL);
	$shownlURLPara->addParam("s","s_menu_nl,s_menu_st");
	$shownlURLPara->addParam("act","statistic");
	$shownlURLPara->addParam("set","nl");

	$_MAIN_OUTPUT.="<br><br>".___("Versandstatus:");
	$_MAIN_OUTPUT.="<br><br>";
	$Q=$QUEUE->getQ(0,0,0,0,$adr_grp_id);
	//getQ($id=0,$offset=0,$limit=0,$nl_id=0,$grp_id=0,$status=0)
	$qc=count($Q);
	for ($qcc=0;$qcc<$qc;$qcc++) {
		$NL=$NEWSLETTER->getNL($Q[$qcc]['nl_id']);
		//prepare graph
		$chart = new HorizontalChart(640,200);
		$chart->setLogo(TM_IMGPATH."/blank.png");//tellmatic_logo_256.png

		$shownlURLPara->addParam("nl_id",$NL[0]['id']);
		$shownlURLPara_=$shownlURLPara->getAllParams();

		$_MAIN_OUTPUT.="<br>".sprintf(___("Versand von %s"),"<a href=\"".$tm_URL."/".$shownlURLPara_."\"><b>".display($NL[0]['subject'])."</b> (".tm_icon("chart_pie.png",___("Statistik anzeigen")).")</a>");
		$_MAIN_OUTPUT.=":&nbsp;".tm_icon($STATUS['q']['statimg'][$Q[$qcc]['status']],display($STATUS['q']['status'][$Q[$qcc]['status']])).
							"&nbsp;".display($STATUS['q']['status'][$Q[$qcc]['status']]).
							"&nbsp;(".display($STATUS['q']['descr'][$Q[$qcc]['status']]).")";

		$_MAIN_OUTPUT.="<br>".sprintf(___("Inhalt-Typ: %s"),"");
		if ($NL[0]['content_type']=="text/html") {
			$_MAIN_OUTPUT.=  tm_icon("page_white_office.png",___("TEXT/HTML"))."&nbsp;";
			$_MAIN_OUTPUT.="<b>".___("TEXT/HTML")."</b>";
		}
		if ($NL[0]['content_type']=="html") {
			$_MAIN_OUTPUT.=  tm_icon("page_white_h.png",___("HTML"))."&nbsp;";
			$_MAIN_OUTPUT.="<b>".___("HTML")."</b>";
		}
		if ($NL[0]['content_type']=="text") {
			$_MAIN_OUTPUT.=  tm_icon("page_white_text.png",___("TEXT"))."&nbsp;";
			$_MAIN_OUTPUT.="<b>".___("TEXT")."</b>";
		}

		$_MAIN_OUTPUT.="<br>".sprintf(___("Versandart: %s"),"");
		if ($NL[0]['massmail']==1) {
			$_MAIN_OUTPUT.=  tm_icon("lorry.png",___("Massenmailing"))."&nbsp;";
			$_MAIN_OUTPUT.="<b>".___("Massenmailing")."</b>";
		} else {
			$_MAIN_OUTPUT.=  tm_icon("user_suit.png",___("personalisierter Newsletter"))."&nbsp;";
			$_MAIN_OUTPUT.="<b>".___("personalisierter Newsletter")."</b>";
		}

		//countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0)
		$hc=$QUEUE->countH($Q[$qcc]['id'],$Q[$qcc]['nl_id'],$AG[0]['id']);
		$hsc=count($STATUS['h']['status']);
		//add total value to graph
		$chart->addPoint(new Point(___("Summe",0)." (100%)", $hc));
		$_MAIN_OUTPUT.="<br>".sprintf(___("Insgesamt %s Adressen"),"<b>".$hc)."</b>";
		$_MAIN_OUTPUT.="<br>".sprintf(___("Erstellt am: %s von %s"),"<b>".$Q[$qcc]['created']."</b>","<b>".$Q[$qcc]['author']."</b>");
		$_MAIN_OUTPUT.="<br>".sprintf(___("Versand gestartet: %s"),"<b>".$Q[$qcc]['send_at']."</b>");
		$_MAIN_OUTPUT.="<br>".sprintf(___("Versand abgeschlossen: %s"),"<b>".$Q[$qcc]['sent']."</b>");

		$_MAIN_OUTPUT.= "<br><img alt=\"Chart\"  src=\"".$tm_URL_FE."/".$tm_reportdir."/adrg_".$adr_grp_id."_nl_".$Q[$qcc]['nl_id'].".png\"><br>";
		for ($hscc=1; $hscc<=$hsc; $hscc++) {
			$hcs=$QUEUE->countH($Q[$qcc]['id'],$Q[$qcc]['nl_id'],$AG[0]['id'],0,$hscc);
			if ($hcs>0) {
				$_MAIN_OUTPUT.="<br>".
								"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$hcs.
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
		$chart->setTitle(sprintf(___("Newsletter %s an Gruppe %s",0),"\"".$NL[0]['subject']."\"","\"".$AG[0]['name']."\""));
		$chart->render($tm_reportpath."/adrg_".$adr_grp_id."_nl_".$Q[$qcc]['nl_id'].".png");

	}//qcc
}
?>