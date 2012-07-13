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
			$QUEUE->setStatus($Q[$qcc]['id'],4);//fertig
			$QUEUE->setSentDate($Q[$qcc]['id'],$created);//fertig
			$NEWSLETTER->setStatus($Q[$qcc]['nl_id'],4);

			//Daten fuer den Report aus der History holen.....
			$H=$QUEUE->getH(0,0,0,$Q[$qcc]['id']);//getHtoSend($id=0,$offset=0,$limit=0,$q_id=0,$nl_id=0,$grp_id=0,$adr_id=0)
			$hc=count($H);
			$hc_new=0;
			$hc_fail=0;
			$hc_view=0;
			$hc_ok=0;
			for ($hcc=0;$hcc<$hc;$hcc++) {
				if ($H[$hcc]['status']==1)	{
					$hc_new++;
				}
				if ($H[$hcc]['status']==2)	{
					$hc_ok++;
				}
				if ($H[$hcc]['status']==3)	{
					$hc_view++;
					$hc_ok++;
				}
				if ($H[$hcc]['status']==4)	{
					$hc_fail++;
				}
			}//$hc==0, no (more) entries
			

			//report
			//should use a template..... :P
			$G=$ADDRESS->getGroup($Q[$qcc]['grp_id']);
			$ReportMail_Subject="Tellmatic: ".___("Versand abgeschlossen")." (QId: ".$Q[$qcc]['id']." / ".$Q[$qcc]['created']." / ".display($G[0]['name']).") ".display($NL[0]['subject']);
			$ReportMail_HTML="";
			$created_date=$Q[$qcc]['created'];
			$sent_date=$created;
			$ReportMail_HTML.="<br><b>".$sent_date."</b>";
			$ReportMail_HTML.="<br>".sprintf(___("Der Versand des Newsletter %s an die Gruppe %s ist abgeschlossen."),"<b>".display($NL[0]['subject'])."</b>","<b>".display($G[0]['name'])."</b>");
			$ReportMail_HTML.="<br>".___("Adressen").": ".$hc;
			$ReportMail_HTML.="<br>".___("Gesendet").": ".$hc_ok;
			$ReportMail_HTML.="<br>".___("Fehler").": ".$hc_fail;
			$ReportMail_HTML.="<br>Log: ".$tm_URL_FE."/".$tm_logdir."/".$logfilename;

			if (!tm_DEMO()) @SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$HOST[0]['sender_email'],$HOST[0]['sender_name'],$ReportMail_Subject,clear_text($ReportMail_HTML),$ReportMail_HTML,Array(),$HOST);
			//sendmail_smtp[0]=true/false [1]=""/errormessage

?>