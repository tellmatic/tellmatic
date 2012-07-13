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

$_MAIN_DESCR=___("Newsletter Warteschlange (Q)");
$_MAIN_MESSAGE.="";

$QUEUE=new tm_Q();
$HOSTS=new tm_HOST();
$ADDRESS=new tm_ADR();
$NEWSLETTER=new tm_NL();

$set=getVar("set");
$val=getVar("val");
$q_id=getVar("q_id");
$nl_id=getVar("nl_id");
$grp_id=getVar("grp_id");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

//logfile
if (check_dbid($q_id)) {
	$Q=$QUEUE->getQ($q_id);
	$logfilename="q_.log.html";
	if (isset($Q[0]['id']))	{
		$logfilename="q_".$Q[0]['id']."_".$Q[0]['grp_id']."_".date_convert_to_string($Q[0]['created']).".log.html";
	}
}
/* Actions */
//stop queue
if ($set=="stop" && $doit==1 && check_dbid($q_id)) {
	$_MAIN_MESSAGE.=tm_message_success(___("Q wurde angehalten."));	
	$QUEUE->setStatus($q_id,5);
	$LOG="[".microtime(TRUE)."][0],".date("Y-m-d H:i:s").",q:".$q_id.",n:0,g:0,a:0,t: Q ID $q_id halted\n";
	update_file($tm_logpath,$logfilename,$LOG);
}

//restart queue with failed or canceled/skipped records
if ($set=="restart_failed" && $doit==1 && check_dbid($q_id)) {
	$_MAIN_MESSAGE.=tm_message_success(___("Q wurde neu gestartet (nur Fehlgeschlagene Einträge)."));
	$QUEUE->restart_failed($q_id);
	$QUEUE->setStatus($q_id,1);
}

//continue stopped queue
if ($set=="continue" && $doit==1 && check_dbid($q_id)) {
	$_MAIN_MESSAGE.=tm_message_success(___("Q wird fortgesetzt."));
	$QUEUE->setStatus($q_id,2);
	$LOG="[".microtime(TRUE)."][0],".date("Y-m-d H:i:s").",q:".$q_id.",n:0,g:0,a:0,t: Q ID $q_id continues\n";
	update_file($tm_logpath,$logfilename,$LOG);
}

//delete, delete all
if ( ($set=="delete" || $set=="delete_all") && $doit==1 && check_dbid($q_id)) {
	//nl auf status queued setzen =2
	//Q holen
	$Q=$QUEUE->getQ($q_id);
	if (isset($Q[0]['id']))	{
		//und q fuer newsletter aus aktueller q holen
		//eintraege zaehlen, wieviele qs fuer newsletter dieser q
		$QNL=$QUEUE->getQ(0,0,0,$Q[0]['nl_id']);
		$nqc=count($QNL);
		if ($nqc>0) {
		}
		//wenn hoechstens 1 eintrag, dann status des nl auf archiv setzen, ...5=archiv
		//ansonsten weiter
		if ($nqc<=1) {
			$NEWSLETTER->setStatus($Q[0]['nl_id'],5);
		}
		//q loeschen
		if ($set=="delete") {
			if (!tm_DEMO()) $QUEUE->delQ($q_id);
			$_MAIN_MESSAGE.=tm_message_success(___("Q Eintrag wurde gelöscht."));
		}
		if ($set=="delete_all") {
			if (!tm_DEMO()) $QUEUE->delQ($q_id,1);//, 1delH=1, auch historie loeschen
			$_MAIN_MESSAGE.=tm_message_success(___("Q Eintrag und Historie wurde gelöscht."));
		}
	}//isset Q[id]
}//delete |< delete_all && doit
//delete logfile
if ($set=="delete_log" && $doit==1) {
	$Q=$QUEUE->getQ($q_id);
	if (isset($Q[0]['id']))	{
		if (!tm_DEMO() && @unlink($tm_logpath."/".$logfilename)) {
			$_MAIN_MESSAGE.=tm_message_success(___("Logfile wurde gelöscht."));
		} else {
			$_MAIN_MESSAGE.=tm_message_error(___("Logfile konnte nicht gelöscht werden."));
		}
	}//isset Q[id]
}

//if newsletter id is set
//q f. liste holen
if (check_dbid($nl_id)) {
	$NL=$NEWSLETTER->getNL($nl_id);
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("gewähltes Newsletter: %s"),"'".$NL[0]['subject']."'"));
}

//if address group id is set
if (check_dbid($grp_id)) {
	$GRP=$ADDRESS->getGroup($grp_id);
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("gewählte Gruppe: %s"),"'".$GRP[0]['name']."'"));
}

//get Queue Entry
$Q=$QUEUE->getQ($id=0,$offset=0,$limit=0,$nl_id,$grp_id,$status=0,$search=Array());
//count entries
$qc=count($Q);

//Action URLS
$reloadURLPara=tmObjCopy($mSTDURL);
$reloadURLPara->addParam("act","queue_list");
$reloadURLPara->addParam("nl_id",$nl_id);

$showhistURLPara=tmObjCopy($mSTDURL);
$showhistURLPara->addParam("act","queue_list");
$showhistURLPara->addParam("nl_id",$nl_id);


$sendFastURLPara=tmObjCopy($mSTDURL);
$sendFastURLPara->addParam("act","queue_send");
$sendFastURLPara->addParam("set","q");
$sendFastURLPara->addParam("nl_id",$nl_id);
$sendFastURLPara->addParam("startq",1);

$restartQFailedURLPara=tmObjCopy($mSTDURL);
$restartQFailedURLPara->addParam("act","queue_list");
$restartQFailedURLPara->addParam("set","restart_failed");

$refreshRCPTListURLPara=tmObjCopy($mSTDURL);
$refreshRCPTListURLPara->addParam("act","queue_send");
$refreshRCPTListURLPara->addParam("set","q");
$refreshRCPTListURLPara->addParam("nl_id",$nl_id);

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("act","queue_list");
$delURLPara->addParam("set","delete");
$delURLPara->addParam("nl_id",$nl_id);

$delAllURLPara=$delURLPara;
$delAllURLPara->addParam("set","delete_all");
$delAllURLPara->addParam("nl_id",$nl_id);

$dellogURLPara=tmObjCopy($mSTDURL);
$dellogURLPara->addParam("act","queue_list");
$dellogURLPara->addParam("set","delete_log");
$dellogURLPara->addParam("nl_id",$nl_id);

$stopURLPara=tmObjCopy($mSTDURL);
$stopURLPara->addParam("act","queue_list");
$stopURLPara->addParam("set","stop");
$stopURLPara->addParam("nl_id",$nl_id);

$continueURLPara=tmObjCopy($mSTDURL);
$continueURLPara->addParam("act","queue_list");
$continueURLPara->addParam("set","continue");
$continueURLPara->addParam("nl_id",$nl_id);

$statURLPara=tmObjCopy($mSTDURL);
$statURLPara->addParam("act","statistic");
$statURLPara->addParam("set","queue");

$showadrURLPara=tmObjCopy($mSTDURL);
$showadrURLPara->addParam("act","adr_list");

//show log summary
//search for logs, only section
$search_log['object']="q";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");


$reloadURLPara_=$reloadURLPara->getAllParams();
//refresh list
$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$reloadURLPara_."\" title=\"".___("Anzeige aktualisieren")."\">".tm_icon("arrow_refresh.png",___("Anzeige aktualisieren"))."&nbsp;".___("Anzeige aktualisieren")."</a><br><br>";

//show table
$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td>&nbsp;".
						"</td>".
						"<td><b>".___("Datum")."</b>".
						"</td>".
						"<td><b>".___("Newsletter")."</b>".
						"</td>".
						"<td><b>".___("Adressgruppe")."</b>".
						"</td>".
						"<td><b>".___("Host")."</b>".
						"</td>".
						"<td><b>".___("Status")."</b>".
						"</td>".
						"<td>...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";



for ($qcc=0;$qcc<$qc;$qcc++) {
	if ($qcc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	$hc_new=0;
	$HOST=$HOSTS->getHost($Q[$qcc]['host_id']);
	if (!isset($HOST[0]) || $HOST[0]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
	}
	$valid_adr_c=$ADDRESS->countValidADR($Q[$qcc]['grp_id']);
	//wenn q status=2 or 3 // run oder fertig
	//dann holen wir uns die daten fuer die q eintraege! vorher ist eh null... :)
	#if ($Q[$qcc]['status']>1) {
		//wenn status > neu, also gestartet, versendet etc, dann summary anzeigen....
		/*
		countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0)
		*/
		$hc=$QUEUE->countH($Q[$qcc]['id']);
		$hc_new=$QUEUE->countH($Q[$qcc]['id'],0,0,0,1);//new entry
		$hc_ok=$QUEUE->countH($Q[$qcc]['id'],0,0,0,2);//ok, done
		$hc_view=$QUEUE->countH($Q[$qcc]['id'],0,0,0,3);//ok,done,viewed
		$hc_fail=$QUEUE->countH($Q[$qcc]['id'],0,0,0,4);//error, failed
		$hc_current=$QUEUE->countH($Q[$qcc]['id'],0,0,0,5);//status 5: currently working on this adr
		$hc_skip=$QUEUE->countH($Q[$qcc]['id'],0,0,0,6);//status 6 : canceled, blacklisted at sending time etc, skipped!
	#}

	//status %
	//1%
	$one_percent=($hc / 100);
	//erledigt gesamt
	$percent_done_formatted= ($one_percent > 0)  ? DisplayDouble( ( ($hc_ok + $hc_fail + $hc_view + $hc_skip) / $one_percent ), 2,",",".") : 0;
	//anteil ok	
	$percent_ok_formatted=($one_percent > 0) ? DisplayDouble( ($hc_ok / $one_percent) , 2,",",".") : 0;
	//anteil ok+view	
	$percent_view_formatted=($one_percent > 0) ? DisplayDouble( ($hc_view / $one_percent) , 2,",",".") : 0;
	//anteil skip	
	$percent_skip_formatted=($one_percent > 0) ? DisplayDouble( ($hc_skip / $one_percent) , 2,",",".") : 0;
	//anteil failed
	$percent_fail_formatted=($one_percent > 0) ? DisplayDouble( ($hc_fail / $one_percent) , 2,",",".") : 0;
	//anteil failed aktuell
	$percent_done_fail_formatted=($hc_fail > 0 || $hc_ok > 0) ? DisplayDouble( ($hc_fail / ( ($hc_fail + $hc_ok) / 100 ) ) , 2,",",".") : 0;


	$GRP=$ADDRESS->getGroup($Q[$qcc]['grp_id']);
	$NL=$NEWSLETTER->getNL($Q[$qcc]['nl_id']);
	$send_at=$Q[$qcc]['send_at'];//ist datetime feld!!! deswegen keine umformatierung!
	//$created_date=strftime("%d-%m-%Y %H:%M:%S",mk_microtime($Q[$qcc]['created']));
	$created_date=$Q[$qcc]['created'];
	$sent_date="--";
	if (!empty($Q[$qcc]['sent'])) {
		//$sent_date=strftime("%d-%m-%Y %H:%M:%S",mk_microtime($Q[$qcc]['sent']));
		$sent_date=$Q[$qcc]['sent'];
	}

	$author=$Q[$qcc]['author'];

	$logfilename="q_".$Q[$qcc]['id']."_".$Q[$qcc]['grp_id']."_".date_convert_to_string($Q[$qcc]['created']).".log.html";
	$logfile=$tm_logpath."/".$logfilename;
	$logfileURL=$tm_URL_FE."/".$tm_logdir."/".$logfilename;
#	$sendURLPara->addParam("q_id",$Q[$qcc]['id']);
#	$sendURLPara_=$sendURLPara->getAllParams();

	$sendFastURLPara->addParam("q_id",$Q[$qcc]['id']);
	$sendFastURLPara_=$sendFastURLPara->getAllParams();

	$restartQFailedURLPara->addParam("q_id",$Q[$qcc]['id']);
	$restartQFailedURLPara_=$restartQFailedURLPara->getAllParams();

	$refreshRCPTListURLPara->addParam("q_id",$Q[$qcc]['id']);
	$refreshRCPTListURLPara_=$refreshRCPTListURLPara->getAllParams();

	$showhistURLPara->addParam("q_id",$Q[$qcc]['id']);
	$showhistURLPara_=$showhistURLPara->getAllParams();

	$delURLPara->addParam("q_id",$Q[$qcc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$delAllURLPara->addParam("q_id",$Q[$qcc]['id']);
	$delAllURLPara_=$delAllURLPara->getAllParams();

	$dellogURLPara->addParam("q_id",$Q[$qcc]['id']);
	$dellogURLPara_=$dellogURLPara->getAllParams();

	$stopURLPara->addParam("q_id",$Q[$qcc]['id']);
	$stopURLPara_=$stopURLPara->getAllParams();

	$continueURLPara->addParam("q_id",$Q[$qcc]['id']);
	$continueURLPara_=$continueURLPara->getAllParams();

	$statURLPara->addParam("q_id",$Q[$qcc]['id']);
	$statURLPara_=$statURLPara->getAllParams();

	$showadrURLPara->addParam("adr_grp_id",$Q[$qcc]['grp_id']);
	$showadrURLPara_=$showadrURLPara->getAllParams();

	$_MAIN_OUTPUT.= "<tr id=\"row_".$qcc."\"  bgcolor=\"".$bgcolor."\"  onmouseover=\"setBGColor('row_".$qcc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$qcc."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.= "<td width=50>";
	if ($Q[$qcc]['check_blacklist']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("ruby.png",___("Blacklist"));
	}
	if ($Q[$qcc]['proof']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("medal_gold_1.png",___("Proofing aktiv"));
	}
	if ($Q[$qcc]['autogen']==1) {
		if ($Q[$qcc]['status']==1) {
			$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_green.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png");
		}
		if ($Q[$qcc]['status']==2) {
			$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_star.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png");
		}
		if ($Q[$qcc]['status']>2) {
			$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_black.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png");
		}
	}
	
	if ($Q[$qcc]['touch']==0) {
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("user_delete.png",___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)"));
	}
	if ($Q[$qcc]['touch']==1) {	
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("user.png",___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)"));
	}
	if ($Q[$qcc]['touch']==2) {
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("user_add.png",___("Nur an Touch-Opt-In Adressen senden."));
	}

//inline images
	if ($Q[$qcc]['use_inline_images']==0) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_delete.png",___("Inline Images deaktiviert"),"","","","picture.png");
	}

	if ($Q[$qcc]['use_inline_images']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_add.png",___("Inline Images (lokal)"),"","","","picture.png");
	}

	if ($Q[$qcc]['use_inline_images']==2) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png");
	}

	if ($Q[$qcc]['save_imap']==1 && check_dbid($Q[$qcc]['host_id_imap'])) {
		$_MAIN_OUTPUT.="&nbsp;".tm_icon("email_add.png",___("Speichere Kopie ausgehender Mails auf IMAP Server"));
	}


	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_q_list_".$Q[$qcc]['id']."')\" onmouseout=\"hideToolTip();\">";

	//wenn q running der fertig, history daten anzeigen
	if ($Q[$qcc]['status']>1) {
	}


	$_MAIN_OUTPUT.=sprintf(___("Versand startet: %s"),"<b>".$send_at."</b>");
	//wenn versendet
	if ($Q[$qcc]['status']==4) {
		$_MAIN_OUTPUT.=" / <b>".$sent_date."</b>";
	}


	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($NL[0]['subject']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$showadrURLPara_."\" title=\"".___("Alle Adressen in dieser Gruppe anzeigen")."\">";
	$_MAIN_OUTPUT.= tm_icon("group_go.png",___("Alle Adressen in dieser Gruppe anzeigen"))."&nbsp;";
	$_MAIN_OUTPUT.= display($GRP[0]['name'])."&nbsp;";
	$_MAIN_OUTPUT.= "</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_q_list_".$Q[$qcc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="<b>".display($NL[0]['subject'])."</b>";
	$_MAIN_OUTPUT.="<br>".sprintf(___("An Gruppe: %s"),"<b>".display($GRP[0]['name'])."</b>");
	$_MAIN_OUTPUT.="&nbsp (".sprintf(___("%s gültige Adressen"),"<b>".display($valid_adr_c)."</b>").")";
	$_MAIN_OUTPUT.= "<br>ID: ".$Q[$qcc]['id']." ";
	$_MAIN_OUTPUT.= "<br>".tm_icon($STATUS['q']['statimg'][$Q[$qcc]['status']],display($STATUS['q']['status'][$Q[$qcc]['status']]))."&nbsp;".display($STATUS['q']['status'][$Q[$qcc]['status']]);
	
//inline images
	if ($Q[$qcc]['use_inline_images']==0) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_delete.png",___("Inline Images deaktiviert"),"","","","picture.png")."&nbsp;".___("Inline Images deaktiviert");
	}

	if ($Q[$qcc]['use_inline_images']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_add.png",___("Inline Images (lokal)"),"","","","picture.png")."&nbsp;".___("Inline Images (lokal)");
	}

	if ($Q[$qcc]['use_inline_images']==2) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png")."&nbsp;".___("Inline Images");
	}
	
	
	$_MAIN_OUTPUT.="<br>".sprintf(___("Erstellt am: %s"),"<b>".display($created_date)."</b>");
	$_MAIN_OUTPUT.="<br>".sprintf(___("Versand startet am/um: %s"),"<b>".display($send_at)."</b>");
	$_MAIN_OUTPUT.="<br>".sprintf(___("Erstellt von: %s"),"<b>".display($Q[$qcc]['author'])."</b>");

	if ($Q[$qcc]['check_blacklist']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("ruby.png",___("Blacklist"))."&nbsp;".___("Blacklist");
	}
	if ($Q[$qcc]['proof']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("medal_gold_1.png",___("Proofing"))."&nbsp;".___("Proofing");
	}
	if ($Q[$qcc]['autogen']==1) {
		if ($Q[$qcc]['status']==1) {
			$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_green.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png")."&nbsp;".___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten");
		}
		if ($Q[$qcc]['status']==2) {
			$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_star.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png")."&nbsp;".___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten");
		}
		if ($Q[$qcc]['status']>2) {
			$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_black.png",___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten"),"","","","cog.png")."&nbsp;".___("Empfängerliste automatisch erstellen / aktualisieren  und Q starten");
		}
	}

	if ($Q[$qcc]['touch']==0) {
		$_MAIN_OUTPUT.= "<br>".tm_icon("user_delete.png",___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)"))."&nbsp;".___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)");
	}
	if ($Q[$qcc]['touch']==1) {	
		$_MAIN_OUTPUT.= "<br>".tm_icon("user.png",___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)"))."&nbsp;".___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)");
	}
	if ($Q[$qcc]['touch']==2) {
		$_MAIN_OUTPUT.= "<br>".tm_icon("user_add.png",___("Nur an Touch-Opt-In Adressen senden."))."&nbsp;".___("Nur an Touch-Opt-In Adressen senden.");
	}

	if ($Q[$qcc]['save_imap']==1 && check_dbid($Q[$qcc]['host_id_imap'])) {
		$HOST_IMAP=$HOSTS->getHost($Q[$qcc]['host_id_imap']);
		$_MAIN_OUTPUT.="<br>".tm_icon("email_add.png",___("Speichere Kopie ausgehender Mails auf IMAP Server"))."&nbsp;".___("Eine Kopie ausgehender Mails wird auf dem IMAP Server gespeichert").":".display($HOST_IMAP[0]['name']."(".$HOST_IMAP[0]['imap_folder_sent'].")");
	}

	if (isset($HOST[0])) {
		$_MAIN_OUTPUT.="<br>".sprintf(___("Mail-Server: %s"),display($HOST[0]['name']));
		if ($HOST[0]['aktiv']!=1) {
			$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
			$_MAIN_OUTPUT.= "<font color=\"red\">".___("Mail-Server ist nicht aktiv!");
			$_MAIN_OUTPUT.= "</font>";
		}
	} else {
			$_MAIN_OUTPUT.= "<br><font color=\"red\">".___("Mail-Server wurde gelöscht!")."</font>";
	}
	#if ($Q[$qcc]['status']>1) {
		$_MAIN_OUTPUT .="<br>".
								___("Adressen: ").$hc.
								"<br>".___("Bearbeitet: ").($hc_ok+$hc_fail+$hc_skip+$hc_view)." = ".$percent_done_formatted."%".								
								"<br>".___("Wartend: ").$hc_new.
								"<br>".___("Übersprungen: ").$hc_skip." = ".$percent_skip_formatted."%".
								"<br>".___("Gesendet: ").$hc_ok." = ".$percent_ok_formatted."%".
								"<br>".___("Angezeigt: ").$hc_view." = ".$percent_view_formatted."%".
								"<br>".___("Fehler: ").$hc_fail." = ".$percent_fail_formatted."%"." / ".$percent_done_fail_formatted."%".
								"<br>".___("versendet am: ").$sent_date.
								"";
	#}
//evtl noch zeit anzeigen wann der auftrag bei der aktuellen geschwindigkeit beendet ist, sinnloser wert wenn der versand enmal fuer laenger unterbrochen wurde, aber weils spass macht koennte man es hier hinzufuegen, datum versand start in microtime umrechnen, erledigte adressen von damals bis jetzt, zeit pro adr ermitteln und mit dem rest multiplizieren, dann zurueckrechnen wie lange es noch dauert in stunden/minuten und zeit wann es beendet sein wird.


	$_MAIN_OUTPUT.="<br>".sprintf(___("%s erledigt, davon: %s Übersprungen, %s Versendet + %s Versendet u. Angezeigt, %s Adressen mit Fehler beim Versand (entspricht aktuell %s aller verarbeiteten Adressen)"),"<br><strong>".$percent_done_formatted."%</strong>","&nbsp;<font color=\"orange\">".$percent_skip_formatted."%</font>","&nbsp;<font color=\"green\">".$percent_ok_formatted."%</font>","&nbsp;<font color=\"green\">".$percent_view_formatted."%</font>"," <font color=\"red\">".$percent_fail_formatted."%</font>","&nbsp;<font color=\"red\">".$percent_done_fail_formatted."%</font>");


	$_MAIN_OUTPUT.= "</div>";

	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	if (isset($HOST[0])){
		if ($HOST[0]['aktiv']!=1) {
			$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
			$_MAIN_OUTPUT.= "<font color=\"red\">";
		}
		$_MAIN_OUTPUT.= display($HOST[0]['name']);
		if ($HOST[0]['aktiv']!=1) {
			$_MAIN_OUTPUT.= "</font>";
		}
	} else {//isset host
		$_MAIN_OUTPUT.= "<font color=\"red\">";
		$_MAIN_OUTPUT.= "--";
		$_MAIN_OUTPUT.= "</font>";
	}

	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	if ($Q[$qcc]['status']==3) {
		$_MAIN_OUTPUT.= "<blink>";
	}
	$_MAIN_OUTPUT.= tm_icon($STATUS['q']['statimg'][$Q[$qcc]['status']],display($STATUS['q']['descr'][$Q[$qcc]['status']]))."&nbsp;".display($STATUS['q']['status'][$Q[$qcc]['status']]);
	if ($Q[$qcc]['status']==3) {
		$_MAIN_OUTPUT.= "</blink>";
	}

	$_MAIN_OUTPUT.="<br><strong>".$percent_done_formatted."%</strong>&nbsp;<font color=\"orange\">".$percent_skip_formatted."%</font>".
						" <font color=\"green\">".$percent_ok_formatted."%</font>&nbsp;<font color=\"green\">".$percent_view_formatted."%</font>".
						" <font color=\"red\">".$percent_fail_formatted."%</font>&nbsp;<font color=\"red\">".$percent_done_fail_formatted."%</font>";

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	//wenn status q ok, =1 , neu, und newsletter aktiv, dann einzelnen sendebutton anzeigen!
	if ($Q[$qcc]['status']==1 && $NL[0]['aktiv']==1  && isset($HOST[0]) && $HOST[0]['aktiv']==1) {
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$sendFastURLPara_."\" title=\"".___("Newsletter an gewählte Gruppe versenden")."\">".tm_icon("bullet_star.png",___("Senden"),"","","","email_go.png")."</a>";
	}
	if ($Q[$qcc]['status']==4) {
//		$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$statURLPara_."\" title=\"Statistik anzeigen\"><img src=\"".$tm_iconURL."/chart_pie.png\" border=\"0\"></a>";
	}

//adressen nachfassen!
//status 2,5, mehr adr in countvalidadr fuer grp_id als gezaehlte f.d. gruppe
if ($hc_new < $valid_adr_c) {
	if (($Q[$qcc]['status']==2 || $Q[$qcc]['status']==5) && isset($HOST[0]) && $HOST[0]['aktiv']==1) {
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$refreshRCPTListURLPara_."\" title=\"".___("Adressen nachfassen / Empfängerliste aktualisieren")."\">".tm_icon("arrow_switch.png",___("Adressen nachfassen / Empfängerliste aktualisieren"),"","","","email_go.png")."</a>";
	}
}

//restart failed, if q finished, active and hc_fail / skip >0
if ($Q[$qcc]['status']==4 && ($hc_skip>0 || $hc_fail>0)) {
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$restartQFailedURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Q mit fehlgeschlagenen und übersprungenen Adressen neu starten."),$Q[$qcc]['id'])."')\" title=\"".___("Q mit fehlgeschlagenen und übersprungenen Adressen neu starten")."\">".tm_icon("error_go.png",___("Q mit fehlgeschlagenen und übersprungenen Adressen neu starten"))."</a>";
}

//anhalten, weitermachen
	if ($Q[$qcc]['status']==5 && isset($HOST[0]) && $HOST[0]['aktiv']==1) {
		//angehalten, weitermachen
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$continueURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Q ID: %s starten ?"),$Q[$qcc]['id'])."')\" title=\"".___("Q erneut starten und fortfahren")."\">".tm_icon("control_play.png",___("Fortfahren"))."</a>";
	}
	if ($Q[$qcc]['status']==2 || $Q[$qcc]['status']==3) { //gestartet oder running!
		//q laeuft, anhalten
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$stopURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Q ID: %s anhalten und Versand stoppen?"),$Q[$qcc]['id'])."')\" title=\"".___("Q stoppen")."\">".tm_icon("control_stop.png",___("Q stoppen"))."</a>";
	}


	if (file_exists($logfile)) {
		if ($Q[$qcc]['status']==3) {
			$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$logfileURL."\" target=\"_blank\" title=\"".___("Logfile anzeigen (nicht vollständig)")."\">".tm_icon("script_lightning.png",___("Logfile anzeigen"))."</a>";
		} else {
			$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$logfileURL."\" target=\"_blank\" title=\"".___("Logfile anzeigen")."\">".tm_icon("script.png",___("Logfile anzeigen"))."</a>";
			$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$dellogURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Logfile fuer Q %s löschen?"),$Q[$qcc]['id'])."')\"  title=\"".___("Logfile löschen")."\">".tm_icon("script_delete.png",___("Logfile löschen"))."</a>";
		}
	}

	//loeschen
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Q ID: %s löschen?"),$Q[$qcc]['id'])."')\" title=\"".___("Q löschen")."\">".tm_icon("cross.png",___("Q löschen"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delAllURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Q ID: %s und Historie löschen?"),$Q[$qcc]['id'])."')\" title=\"".___("Q löschen")."\">".tm_icon("bullet_delete.png",___("Q komplett Löschen"),"","","","cross.png")."</a>";
 
	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$Q[$qcc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require_once(TM_INCLUDEPATH_GUI."/queue_list_legende.inc.php")
?>