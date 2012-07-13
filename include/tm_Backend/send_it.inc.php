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

/***********************************************************/
//include send_it config, prepare some objects, set u pfinction sendlog()
/***********************************************************/
require_once(TM_INCLUDEPATH_BE."/send_it_config.inc.php");//sendlog() defined here! not a global function yet
send_log("...loaded send_it_config.inc.php");
send_log("---------------------------------------------------------------------------------------");

/***********************************************************/
//prepare new qs
/***********************************************************/
send_log("send_it_q_prepare.inc.php ...");
require_once(TM_INCLUDEPATH_BE."/send_it_q_prepare.inc.php");
send_log("---------------------------------------------------------------------------------------");
/***********************************************************/
//get a q to run
//jetzt aktuelle q's zum versenden holen...:
//Q holen
/***********************************************************/
$Q=Array();
if (!$skip_send) {
	$Q=$QUEUE->getQtoSend(0,0,$limitQ,0);//id offset limit nl-id
}
$qc=count($Q);//wieviel zu sendende q eintraege gibt es?
send_log("qc=".$qc);
/***********************************************************/
//Schleife Qs
//Loop Qs
/***********************************************************/
send_log("for qcc=0;qcc<$qc;qcc++");
send_log("---------------------------------------------------------------------------------------");
for ($qcc=0;$qcc<$qc;$qcc++) {
	/***********************************************************/
	//prepare logging
	/***********************************************************/
	//set log_q_id, nl_id and adr_id
	$log_q_id=$Q[$qcc]['id'];
	$log_nl_id=$Q[$qcc]['nl_id'];
	$log_grp_id=$Q[$qcc]['grp_id'];
	$logfilename_send_it_q="q_".$Q[$qcc]['id']."_".$Q[$qcc]['grp_id']."_".date_convert_to_string($Q[$qcc]['created']).".log.html";
	send_log("Running ".($qcc+1)." of $qc Qs");
	send_log("begin");
	send_log("QID=".$Q[$qcc]['id']);
	send_log("Status=".$Q[$qcc]['status']);
	send_log("nl_id=".$Q[$qcc]['nl_id']);
	send_log("grp_id=".$Q[$qcc]['grp_id']);
	send_log("host_id=".$Q[$qcc]['host_id']);
	send_log("send_at=".$Q[$qcc]['send_at']);
	send_log("autogen=".$Q[$qcc]['autogen']);
	send_log("proof=".$Q[$qcc]['proof']);
	send_log("touch=".$Q[$qcc]['touch']);

	if($Q[$qcc]['touch']==0) {
		send_log("Send to all adr BUT NOT adr status 12 (touch)");
	}
	if($Q[$qcc]['touch']==1) {
		send_log("Send to all valid include adr status 12 (touch)");
	}
	if($Q[$qcc]['touch']==2) {
		send_log("Send only to touch adr, adr status 12 (touch)");
	}				

	/***********************************************************/
	//get smtp host for this q
	/***********************************************************/
	$HOST=$HOSTS->getHost($Q[$qcc]['host_id'],Array("aktiv"=>1,"type"=>"smtp"));
	/***********************************************************/
	//check if we have a valid smtp host, host isset and active
	//if not stop q
	/***********************************************************/
	if (!isset($HOST[0]))	{ //wenn kein gueltiger smtp host, filter: aktiv=1 und typ=smtp
		send_log("host id:".$Q[$qcc]['host_id']." inactive / not from type smtp or does not exist! skipping!");
		$QUEUE->setStatus($Q[$qcc]['id'],5);//stopped
		send_log("Q ID ".$Q[$qcc]['host_id']." stopped!");
	}
	/***********************************************************/
	//check if we have a valid smtp host
	/***********************************************************/
	if (isset($HOST[0]))	{ //wenn gueltiger smtp host, filter: aktiv=1 und typ=smtp
		send_log("hostname/ip=".$HOST[0]['name']."(".$HOST[0]['host'].":".$HOST[0]['port'].")");

		//2do: check if host login is ok, check 'checked' test flag		
		
		$max_mails_atonce=$HOST[0]['max_mails_atonce'];
		send_log("max_mails_atonce=".$max_mails_atonce);
		#disabled in 19090: $max_mails_bcc=$HOST[0]['max_mails_bcc'];//==1
		#max_mails_bcc is always 1
		if ($HOST[0]['smtp_ssl']) send_log("SMTP uses SSL");
		//Newsletter holen

		/***********************************************************/
		//Fetch the newsletter and set status to started
		/***********************************************************/
		send_log("get nl");
		$NL=$NEWSLETTER->getNL($Q[$qcc]['nl_id'],0,0,0,1);//mit content!!!
		
//2do: check if NL[0] isset		
		
		//status fuer nl auf 3=running setzen
		send_log("set nl status=3");
		$NEWSLETTER->setStatus($NL[0]['id'],3);//versand gestartet
		$created_date=$Q[$qcc]['created'];

		/***********************************************************/
		//Check if we have a q that should be started now
		/***********************************************************/
		send_log("q status=".$Q[$qcc]['status']);
		//wenn q status==2, neu... dann mail an admin das versenden gestartet wurde....
		if ($Q[$qcc]['status']==2) {//ist status=2, neu und in aktueller getQtosend-liste!  //neuer status ist schon 3 running!!! wurde oben bereits gemacht
			send_log("send_it_q_start.inc.php ...");
			require_once(TM_INCLUDEPATH_BE."/send_it_q_start.inc.php");
		}
		//set status = running =3
		//erst hier, da addHQ den status 1 oder 2 verlangt! und es schon 3 waere wenn wir dat oben machen
		send_log("set q status=3, running");
		$QUEUE->setStatus($Q[$qcc]['id'],3);//running

		/***********************************************************/
		//Initialize imap connection for saving a copy of outgoing mails
		/***********************************************************/
		$imap_connected=false;
		if ($Q[$qcc]["save_imap"]==1) {
					send_log("Q is configured to save outgoing mails in imap folder");
					$HOST_IMAP=$HOSTS->getHost($Q[$qcc]['host_id_imap']);
					send_log("IMAP Host: ".$HOST_IMAP[0]['name']);
					send_log($HOST_IMAP[0]['type']."://".$HOST_IMAP[0]['user'].":[passwd]@".$HOST_IMAP[0]['host'].":".$HOST_IMAP[0]['port']."/".$HOST_IMAP[0]['options'].":".$HOST_IMAP[0]['imap_folder_sent']);
					$Mailer=new tm_Mail();
					$Mailer->Connect($HOST_IMAP[0]['host'], $HOST_IMAP[0]['user'], $HOST_IMAP[0]['pass'],$HOST_IMAP[0]['type'],$HOST_IMAP[0]['port'],$HOST_IMAP[0]['options']);
					if (!empty($Mailer->Error)) {
						send_log("Mailer Error ".$Mailer->Error);
					} else {//Mailer->Error
						$imap_connected=true;
						$ImapQuota=$Mailer->getQuota($HOST_IMAP[0]['user']);
						send_log("Configured Sent Folder: ".$HOST_IMAP[0]['imap_folder_sent']);
					}//Mailer->Error
		}//if save_imap

		/***********************************************************/
		//Set up email object
		/***********************************************************/
		send_log("prepare E-Mail-Object");
		send_log("From: ".$HOST[0]['sender_email']." (".$HOST[0]['sender_name'].")");
		send_log("Subject (raw): ".display($NL[0]['subject']));
		//emailobjekt vorbereiten, wird dann kopiert, hier globale einstellungen
		$email_obj=new smtp_message_class;//use SMTP!
		send_log("set E-Mail-Object parameters");
		send_log("auth=".$HOST[0]['smtp_auth']);
		$email_obj->authentication_mechanism=$HOST[0]['smtp_auth'];
		send_log("ssl=".$HOST[0]['smtp_ssl']);
		$email_obj->ssl=$HOST[0]['smtp_ssl'];
		send_log("localhost/smtp_domain=".$HOST[0]['smtp_domain']);
		$email_obj->localhost=$HOST[0]['smtp_domain'];
		send_log("user@host:port=".$HOST[0]['user']."@".$HOST[0]['host'].":".$HOST[0]['port']);
		$email_obj->smtp_host=$HOST[0]['host'];
		$email_obj->smtp_port=$HOST[0]['port'];
		$email_obj->smtp_user=$HOST[0]['user'];
		$email_obj->smtp_password=$HOST[0]['pass'];		
		$email_obj->smtp_realm="";
		$email_obj->smtp_workstation="";
		$email_obj->smtp_pop3_auth_host="";
		//important! max 1 rcpt to before waiting for ok, tarpiting!
		send_log("smtp_max_piped_rcpt=".$HOST[0]['smtp_max_piped_rcpt']);
		$email_obj->maximum_piped_recipients=$HOST[0]['smtp_max_piped_rcpt'];//sends only XX rcpt to before waiting for ok from server!
		#if ($SMTPPopB4SMTP==1)	$email_obj->smtp_pop3_auth_host=$HOST[0]['host'];
		$email_obj->mailer=TM_APPTEXT;

		send_log("default_charset=".$encoding);
		$email_obj->default_charset=$encoding;
		send_log("SetBulkMail=1, cache_body=0");
		$email_obj->SetBulkMail=1;
		$email_obj->cache_body=0;

		//anable smtp debugging?
		if (TM_DEBUG_SMTP) {
			send_log("TM_DEBUG_SMTP enabled");
			$email_obj->smtp_debug=1;
			$email_obj->smtp_html_debug=($called_via_url) ? 1 : 0;
			send_log("email_obj.smtp_html_debug (=1 if called via url ) = ".$email_obj->smtp_html_debug);
		} else {
			send_log("TM_DEBUG_SMTP not enabled");
			$email_obj->smtp_debug=0;
			$email_obj->smtp_html_debug=0;
		}

		/***********************************************************/
		//add main email headers
		/***********************************************************/
		$email_obj->SetEncodedEmailHeader("From",$HOST[0]['sender_email'],$HOST[0]['sender_name']);

//2do: reply-to adr configurable

		$email_obj->SetEncodedEmailHeader("Reply-To",$HOST[0]['reply_to'],$HOST[0]['sender_name']);
		$email_obj->SetHeader("Return-Path",$HOST[0]['return_mail']);
		$email_obj->SetEncodedEmailHeader("Errors-To",$HOST[0]['return_mail'],$HOST[0]['return_mail']);
		//set additional headers
		//list unsubscribe!
//2do: //make list unsuncribe url  an option in host settings or nl or q???
		$email_obj->SetEncodedHeader("List-Unsubscribe",$tm_URL_FE."/unsubscribe.php");
		//date
		$email_obj->SetEncodedHeader("X-TM_DATE",date("Y-m-d H:i:s"));
		//queue id
		$email_obj->SetHeader("X-TM_QID",$Q[$qcc]['id']);
		//newsletter id		
		$email_obj->SetHeader("X-TM_NLID",$NL[0]['id']);
		//adr grp id		
		$email_obj->SetHeader("X-TM_GRPID",$Q[$qcc]['grp_id']);
		//		

//2do: add custom headers

		//H holen
		//Sendeliste Eintraege die versendet werden duerfen, also kein fehler etc, nur 'neu' , status 1
		//limit $max_mails_atonce
		//nur fuer gewahlte Q, achtung: dadurch muessen wir nl nur einmal holen und auslesen!!!
		//ansonsten muesste man jedesmal nl holen aus hist. nl_id

		//bei massenmail faktor max_mails_bcc!
		//$max_mails=($max_mails_atonce * $max_mails_bcc);
		//bei personalisierter mail ist:
		//$max_mails=$max_mails_atonce;


		//set max_mails to max_mail_atonce, no more massmail bcc!
		$max_mails=($max_mails_atonce);// * $max_mails_bcc);//maximale anzahl zu bearbeitender mails/empfaenger insgesamt! faktor max_mails_bcc

		/***********************************************************/
		//now, check entrys to process!
		/***********************************************************/
		send_log("checking entries in H, creating list");
		//aktuel offene versandauftraege
		$H=$QUEUE->getHtoSend(0,0,$max_mails,$Q[$qcc]['id']);//id , offset, limit, q_id !!!, ....
		$hc=count($H);//wieviel sendeeintraege

		send_log($hc." Entrys found\n");

		$time=$T->MidResult();
		send_log("time: ".$time);

		if ($hc > 0) {
			send_log("hc>0");
			send_log("working on max $max_mails addresses in $max_mails_atonce mails with $max_mails_bcc recipients for each mail");
	
			//Schleife Versandliste h....
			//bei massenmail..... immer um max_mails_bcc erhoehen!
			//(max_mails_bcc) * (hcc/max_mails_bcc) ??? limit... hc
			//for ($hcc=0;$hcc<$hc;$hcc=$hcc+$max_mails_bcc) {
	
			/***********************************************************/
			//Loop through all entries
			/***********************************************************/
			send_log("start loop from hcc to hc");
			for ($hcc=0;$hcc<$hc;$hcc++) {
				send_log("running Entry hcc:$hcc to $hc");
				/***********************************************************/
				//process this entry! and send mail etc
				/***********************************************************/
				send_log("send_it_q_run.inc.php ...");
				send_log("---------------------------------------------------------------------------------------");
				require(TM_INCLUDEPATH_BE."/send_it_q_run.inc.php");//hier darf natuerlich kein require_once stehen :))
				send_log("---------------------------------------------------------------------------------------");	
				//add optional smtp host delay between two mails!
				if ($HOST[0]['delay']>0) {
					send_log("host delay >0, sleeping: ".($HOST[0]['delay']/1000000)." seconds before processing next entry.");
					usleep($HOST[0]['delay'])	;
				} else {
					send_log("sleeping: 0 seconds, no pause between two mails");
				}
			}//hcc
		}//hc>0
		send_log("---------------------------------------------------------------------------------------");		
		send_log("$hc Entrys have been processed");
		$time_total=$T->Result();
		send_log("time total: ".$time_total);
	} //isset HOST[0]!!!!
	
	send_log("---------------------------------------------------------------------------------------");
	/***********************************************************/
	//if no entrys found, hc==0, then end q
	/***********************************************************/
	//Q finished? n omore records in nl_h found?
	if ($hc==0) {
		send_log("hc==0");
		$created=date("Y-m-d H:i:s");
		//set Q status = finished =4
		send_log("Q ".$Q[$qcc]['id']." finished!");
		/***********************************************************/
		//end queue, set status and send mail to admin
		/***********************************************************/
		send_log("send_it_q_finish.inc.php ...");		
		require_once(TM_INCLUDEPATH_BE."/send_it_q_finish.inc.php");
		send_log("---------------------------------------------------------------------------------------");
	}//hc==0
	send_log(($qcc+1)." of $qc Qs");
	send_log("end q ".$qcc);
	send_log("write/append Log to ".$tm_URL_FE."/".$tm_logdir."/".$logfilename_send_it_q);
	send_log("---------------------------------------------------------------------------------------");
}//$qcc

send_log($_MAIN_MESSAGE);//wegen debug_sql

/***********************************************************/
//finally check if we run the script via browser/url and output a html refresh meta and message...
/***********************************************************/
	
if ($called_via_url) {
	echo "</pre></font>";
	echo	"<meta http-equiv=\"refresh\" content=\"".$reload_intervall."; URL=".TM_DOMAIN.$_SERVER["PHP_SELF"]."\">\n";
	if ($qc==0) {
		echo "<br>".___("Zur Zeit gibt es keine zu verarbeitenden Versandaufträge.");
	}
	echo	"<br>".sprintf(___("Die Seite wird in %s Sekunden automatisch neu geladen."),$reload_intervall).
				"<br>\n".
				___("Klicken Sie auf 'Neu laden' wenn Sie diese Seite erneut aufrufen möchten.").
				"<a href=\"".TM_DOMAIN.$_SERVER["PHP_SELF"]."\"><br>".
				___("Neu laden").
				"</a>";
	echo "\n</body>\n</html>";
}
?>