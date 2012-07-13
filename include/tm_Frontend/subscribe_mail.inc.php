<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/10 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/*******************************************************************************/

#$nl_id_index="nl_id_doptin";
#$nl_id_index="nl_id_greeting";
#$nl_id_index="nl_id_update";

$use_nl_mail=FALSE;

if (tm_DEBUG()) $MESSAGE.=tm_message_debug("nl_id index is ".$nl_id_index);

if (check_dbid($FRM[0][$nl_id_index])) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("nl_id for mail found ".$nl_id_index."=".$FRM[0][$nl_id_index]);
	//get newsletter
	$NLSubscribeMail=$NEWSLETTER->getNL($FRM[0][$nl_id_index]);
	//check / validate newsletter, must be active, personalized and template
	if (isset($NLSubscribeMail[0])) {
		if ($NLSubscribeMail[0]['massmail']==0) {
			if ($NLSubscribeMail[0]['aktiv']==1) {
				if ($NLSubscribeMail[0]['is_template']==1) {
					if (tm_DEBUG()) $MESSAGE.=tm_message_debug("valid newsletter found, send as mail");
					$use_nl_mail=TRUE;
				} else {
					if (tm_DEBUG()) $MESSAGE.=tm_message_debug("not a valid newsletter, nl is not a template!");									
				}//is_template==1
			} else {
					if (tm_DEBUG()) $MESSAGE.=tm_message_debug("not a valid newsletter, nl is not active!");
			}//aktiv==1
		} else {
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("not a valid newsletter, nl is massmail!");
		}//massmail==0
	} else {
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("not a valid newsletter, nl not exists!");
	}//isset [0]
} else {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("no valid newsletter found to send as mail, invalid id");
}//check_dbid nl_id_doptin

$SubscribeMail_Subject="";
$SubscribeMail_HTML="";
$SubscribeMail_TEXT="";
$RCPT_Name="";

if (!$use_nl_mail) {
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("no valid newsletter found to send as mail!");
	$OUTPUT.="ERR 3";
}

if ($use_nl_mail) {
	//use new method, send selected newsletter as double optin mail!
	//parse newsletter
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("create mail");	
	$SubscribeMail_Subject= $NEWSLETTER->parseSubject( Array('text'=>$NLSubscribeMail[0]['subject'], 'adr'=>$ADR[0], 'date'=>$date_sub) );
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("subject (parsed): ".$SubscribeMail_Subject);
		
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("to: ".$ADR[0]['email']);
	
	if (!empty($NLSubscribeMail[0]['rcpt_name'])) {
		$RCPT_Name_TMP=$NLSubscribeMail[0]['rcpt_name'];
	} else {
		if (!empty($C[0]['rcpt_name'])) {
			$RCPT_Name_TMP=$C[0]['rcpt_name'];
		} else {
			$RCPT_Name_TMP="Tellmatic Newsletter";
		}
	}
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("rcpt-name tmp: ".$RCPT_Name_TMP);
	$RCPT_Name = $NEWSLETTER->parseSubject( Array('text'=>$RCPT_Name_TMP, 'adr'=>$ADR[0], 'date'=>$date_sub) );
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("rcpt-name: ".$RCPT_Name);
	//we have to replace date before parsing {DATE} --> $date_sub
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("parse body");
	$NLSubscribeMail[0]['body']=str_replace("{DATE}",$date_sub,$NLSubscribeMail[0]['body']);
	$NLSubscribeMail[0]['body_text']=str_replace("{DATE}",$date_sub,$NLSubscribeMail[0]['body_text']);
	//now parse newsletter:
	if ($NLSubscribeMail[0]['content_type']=="html" || $NLSubscribeMail[0]['content_type']=="text/html") {
		$SubscribeMail_HTML=$NEWSLETTER->parseNL(Array('nl'=>$NLSubscribeMail[0],'adr'=>$ADR[0],'frm'=>Array('id'=>$frm_id) ),"html");
	}
	if ($NLSubscribeMail[0]['content_type']=="text" || $NLSubscribeMail[0]['content_type']=="text/html") {
		$SubscribeMail_TEXT=$NEWSLETTER->parseNL(Array('nl'=>$NLSubscribeMail[0],'adr'=>$ADR[0],'frm'=>Array('id'=>$frm_id) ),"text");
	}
	//2do: create text part from html if nl is html only
	//convert2Text? in ClassNL... test
		
	//sendmail_smtp[0]=true/false [1]=""/errormessage
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("send mail to ".$ADR[0]['email']);
	@SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$ADR[0]['email'],$RCPT_Name,$SubscribeMail_Subject,$SubscribeMail_TEXT,$SubscribeMail_HTML,Array(),$HOST);
	
	if ($C[0]['notify_subscribe']==1) {
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("send notification mail to admin! ".$C[0]['notify_mail']." , ".$HOST[0]['sender_name']);
		@SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$C[0]['notify_mail'],$HOST[0]['sender_name'],$SubscribeMail_Subject,$SubscribeMail_TEXT,$SubscribeMail_HTML,Array(),$HOST);
	}
 }//use nl_doptin
?>