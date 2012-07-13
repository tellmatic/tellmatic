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

//Q's vorbereiten // status=1, autogen=1, startet zwischen jetzt und +xx stunden!
$QP=$QUEUE->getQtoPrepare(Array("limit"=>1));
$qpc=count($QP);//wieviel zu sendende q eintraege gibt es?
//Schleife Qs
for ($qpcc=0;$qpcc<$qpc;$qpcc++) {
	$skip_send=TRUE;
	$logfilename_send_it_q="q_".$QP[$qpcc]['id']."_".$QP[$qpcc]['grp_id']."_".date_convert_to_string($QP[$qpcc]['created']).".log.html";
	
	//set log_q_id, nl_id and adr_id
	$log_q_id=$QP[$qpcc]['id'];
	$log_nl_id=$QP[$qpcc]['nl_id'];
	$log_grp_id=$QP[$qpcc]['grp_id'];
	
	send_log("Preparing ".($qpcc+1)." of $qpc Qs");
	send_log("begin");
	send_log("QID=".$QP[$qpcc]['id']);
	send_log("Status=".$QP[$qpcc]['status']);
	send_log("nl_id=".$QP[$qpcc]['nl_id']);
	send_log("grp_id=".$QP[$qpcc]['grp_id']);
	send_log("host_id=".$QP[$qpcc]['host_id']);
	send_log("send_at=".$QP[$qpcc]['send_at']);
	send_log("autogen=".$QP[$qpcc]['autogen']);
	send_log("proof=".$QP[$qpcc]['proof']);
	send_log("touch=".$QP[$qpcc]['touch']);
	$G=$ADDRESS->getGroup($QP[$qpcc]['grp_id']);
	$NL=$NEWSLETTER->getNL($QP[$qpcc]['nl_id']);
	$HOST=$HOSTS->getHost($QP[$qpcc]['host_id'],Array("aktiv"=>1,"type"=>"smtp"));
	send_log("q status=1, q autogen =1");
	send_log("creating recipients list:");
	$h_refresh=$QUEUE->addHQ( Array ( 'nl_id' => $QP[$qpcc]['nl_id'],
																				'q_id' => $QP[$qpcc]['id'],
																				'grp_id' =>$QP[$qpcc]['grp_id'],
																				'host_id' =>$QP[$qpcc]['host_id'],
																				'status' => 1,
																				'created' => date("Y-m-d H:i:s")
																				)
																		);

	#proof?!
	if ($C[0]['proof']==1) {
		if (tm_DEBUG()) $MESSAGE.=send_log("proofing global enabled");
		if ($QP[$qpcc]['proof']==1) {
			if (tm_DEBUG()) $MESSAGE.=send_log("proofing for this q enabled");
			$ADDRESS->proof();
		}	else {
			if (tm_DEBUG()) $MESSAGE.=send_log("proofing for this q disabled");		
		}
	}	

	//$created_date=strftime("%d-%m-%Y %H:%M:%S",mk_microtime($QP[$qpcc]['created']));
	$created_date=$QP[$qpcc]['created'];
	$ReportMail_HTML="";
	$ReportMail_Subject="Tellmatic: ".___("Empfängerliste vorbereiten")." (QId: ".$QP[$qpcc]['id']." / ".$QP[$qpcc]['created']." / ".display($G[0]['name']).") ".display($NL[0]['subject']);
	
	if($QP[$qpcc]['touch']==0) {
		$ReportMail_HTML.= "<br>".___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)")."\n";
	}
	if($QP[$qpcc]['touch']==1) {
		$ReportMail_HTML.= "<br>".___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)")."\n";
	}
	if($QP[$qpcc]['touch']==2) {
		$ReportMail_HTML.= "<br>".___("Nur an Touch-Opt-In Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)")."\n";
	}

	if ($h_refresh[0]) {
		# if $h_refresh[2] >0.... checken
		if (tm_DEBUG()) $MESSAGE.=send_log("h_refresh[2] :".$h_refresh[2]);
		$ReportMail_HTML.="<br>AutoGen=1";
		$ReportMail_HTML.="<br>".___("Die Empfängerliste wurde automatisch erzeugt!",0);
		$ReportMail_HTML.="<br>".sprintf(___("Es wurden %s Adressen für Gruppe %s eingetragen",0),$h_refresh[2],display($G[0]['name']));
		$ReportMail_HTML.="<br>SMTP: ".$HOST[0]['name']." / ".$HOST[0]['user'].":[pass]@".$HOST[0]['host'].":".$HOST[0]['port'];
		send_log($h_refresh[2]." adresses for group ".$G[0]['name']." inserted in recipients list");
		send_log("set q status=2, started!");
		$QUEUE->setStatus($QP[$qpcc]['id'],2);//gestartet
	} else {
		$ReportMail_HTML.="<br>".___("Fehler beim aktualisieren der Empfängerliste.",0);
		send_log("Error refreshing recipients list!");
	}
	send_log("q status =1, new status=2, sending mail to admin");
	//report an sender....

	$ReportMail_HTML.="<br><b>".$created_date."</b>";
	$ReportMail_HTML.="<br>".sprintf(___("Der Versand des Newsletter %s an die Gruppe %s wurde vorbereitet.",0),"<b>".display($NL[0]['subject'])."</b>","<b>".display($G[0]['name'])."</b>");
	$ReportMail_HTML.="<br>";
	$ReportMail_HTML.="<br>".sprintf(___("Der Versand terminiert für: %s",0),$QP[$qpcc]['send_at']);
	$ReportMail_HTML.="<br>Log: ".$tm_URL_FE."/".$tm_logdir."/".$logfilename_send_it_q;
	if (!tm_DEMO()) @SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$HOST[0]['sender_email'],$HOST[0]['sender_name'],$ReportMail_Subject,clear_text($ReportMail_HTML),$ReportMail_HTML,Array(),$HOST);
	//sendmail_smtp[0]=true/false [1]=""/errormessage
	
	send_log("write Log to ".$tm_URL_FE."/".$tm_logdir."/".$logfilename_send_it_q);
}//for qpc

?>