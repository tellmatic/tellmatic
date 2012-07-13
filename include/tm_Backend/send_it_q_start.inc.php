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
	
			$G=$ADDRESS->getGroup($Q[$qcc]['grp_id']);
			//hier adressen nachfassen! fuer status=2, q_id und grp_id etc.
			if ($Q[$qcc]['autogen']==1) {
				//adressen nachfassen
				send_log("q status=2, q autogen =1");
				send_log("touch=".$Q[$qcc]['touch']);
				send_log("refreshing recipients list:");
				$h_refresh=$QUEUE->addHQ( Array ( 'nl_id' => $Q[$qcc]['nl_id'],
																				'q_id' => $Q[$qcc]['id'],
																				'grp_id' =>$Q[$qcc]['grp_id'],
																				'host_id' =>$Q[$qcc]['host_id'],
																				'status' => 1,
																				'created' => date("Y-m-d H:i:s")
																				)
																		);
				$ReportMail_HTML="";
				$ReportMail_Subject="Tellmatic: ".___("Versand starten")." (QId: ".$Q[$qcc]['id']." / ".$Q[$qcc]['created']." / ".display($G[0]['name']).") ".display($NL[0]['subject']);
				
				if($Q[$qcc]['touch']==0) {
					$ReportMail_HTML.= "<br>".___("An alle validen Adressen ohne Touch-Opt-In senden. (alle validen Adressen exkl. Touch-Opt-In)")."<br>\n";
				}
				if($Q[$qcc]['touch']==1) {
					$ReportMail_HTML.= "<br>".___("An alle Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)")."<br>\n";
				}
				if($Q[$qcc]['touch']==2) {
					$ReportMail_HTML.= "<br>".___("Nur an Touch-Opt-In Adressen senden. (alle validen Adressen inkl. Touch-Opt-In)")."<br>\n";
				}				
				
				if ($h_refresh[0]) {
					send_log("h_refresh[2] :".$h_refresh[2]);
					send_log($h_refresh[2]." adresses for group ".$G[0]['name']." inserted in recipients list");
					# if $h_refresh[2] >0.... checken
					$ReportMail_HTML.="<br>AutoGen=1";
					$ReportMail_HTML.="<br>".___("Die Empfängerliste wurde automatisch erzeugt!");
					$ReportMail_HTML.="<br>".sprintf(___("Es wurden %s Adressen für Gruppe %s eingetragen"),$h_refresh[2],display($G[0]['name']));
					$ReportMail_HTML.="<br>SMTP: ".$HOST[0]['name']." / ".$HOST[0]['user'].":[pass]@".$HOST[0]['host'].":".$HOST[0]['port'];
				} else {
					$ReportMail_HTML.="<br>".___("Fehler beim aktualisieren der Empfängerliste.");
					send_log("Error refreshing recipients list!");
				}				
			}

			send_log("q status =2, new status=3, sending mail to admin (".$HOST[0]['sender_email'].")");
			//report an admin, from addres of active q host....
			$ReportMail_HTML.="<br><b>".$created_date."</b>";
			$ReportMail_HTML.="<br>".sprintf(___("Der Versand des Newsletter %s an die Gruppe %s wurde gestartet."),"<b>".display($NL[0]['subject'])."</b>","<b>".display($G[0]['name'])."</b>");
			$ReportMail_HTML.="<br>SMTP: ".$HOST[0]['name']." / ".$HOST[0]['user'].":[pass]@".$HOST[0]['host'].":".$HOST[0]['port'];
			$ReportMail_HTML.="<br>".sprintf(___("Der Versand terminiert für: %s"),$Q[$qcc]['send_at']);
			$ReportMail_HTML.="<br>Log: ".$tm_URL_FE."/".$tm_logdir."/".$logfilename_send_it_q;

			if (!tm_DEMO()) @SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$HOST[0]['sender_email'],$HOST[0]['sender_name'],$ReportMail_Subject,clear_text($ReportMail_HTML),$ReportMail_HTML,Array(),$HOST);
			//sendmail_smtp[0]=true/false [1]=""/errormessage

?>