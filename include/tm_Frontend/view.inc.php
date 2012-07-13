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

$nl_id=getVar("nl_id");//nl id
$q_id=getVar("q_id");//queue id
$a_id=getVar("a_id");//adr id
$h_id=getVar("h_id");//history id

//at first we need the newsletter id!
//we dont continue without a valid nl_id
//fetch newsletter and check if active, only active newsletters are valid
//check if nl is personalized
//check if link id is set and valid, otherwise replace with link1!!!
//count clicks and views
//do not add memo, we dont really need that
//instead we have the linktracker which can give much better statistics

$personalized=FALSE;//is personalized mailing?
$track_personalized=FALSE;//is personalized tracking? (click on links etc, blindimage)
$valid_nl=FALSE;//valid nl record?
$valid_adr=FALSE;//valid address record?
$valid_h=FALSE;//do we have a valid history entry?

$_NEWSLETTER="";
$tm_debug="";

//check valid nl id
if (check_dbid($nl_id)) {
	//checkid()?
	$NEWSLETTER=new tm_NL();
	//Link holen
	$NL=$NEWSLETTER->getNL($nl_id,0,0,0,1);
	//check for valid newsletter, must be active and not a template
	if (isset($NL[0]) && $NL[0]['aktiv']==1 && $NL[0]['is_template']!=1) {
		$valid_nl=TRUE;
		if (tm_DEBUG()) $tm_debug.=tm_message_debug("nl is valid");
		
		//check if newsletter and tracking is personalized, massmail==0 track-personalized==1 need at least adr or history id here 
		if ($NL[0]['massmail']==0 && (check_dbid($a_id) || check_dbid($h_id)) ) {
			$personalized=TRUE;
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("nl is personalized");
		}
		if ($personalized && $NL[0]['track_personalized']==1 ) {
			$track_personalized=TRUE;
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("tracking is personalized");
		}


		//check for valid record in history/rcpt-list
		if (check_dbid($h_id) && $personalized) {
			$QUEUE=new tm_Q();
			$H=$QUEUE->getH($h_id);
			if (isset($H[0])) {
				$valid_h=TRUE;
				//now we can check if we have a valid address record... if not, we can get one from the history table... :O
				if (!check_dbid($a_id) && check_dbid($H[0]['adr_id']) ) {
					//if a_id is not valid yet, set a_id to a_id from history table
					//must be done before address checking ;)
					$a_id=$H[0]['adr_id'];
					if (tm_DEBUG()) $tm_debug.=tm_message_debug("invalid adr id, gettind adr id from h_a_id:".$a_id);
				}
				//if we have valid adr_id yet, lets check if it differs from the adr_i in hostory table... if not, we dont have a valid h record
				if (check_dbid($a_id) && $a_id!=$H[0]['adr_id']) {
					$valid_h=FALSE;
					if (tm_DEBUG()) $tm_debug.=tm_message_debug("adr isset but does not match h_a_id");
				}
				//also check if newsletter matches history!
				if ($valid_nl && $nl_id!=$H[0]['nl_id']) {
					$valid_h=FALSE;
					if (tm_DEBUG()) $tm_debug.=tm_message_debug("nl is valid but does not match h_nl_id");
				}

				//ok, if we have a valid h record, lets do some tracking and logging
			}//isset H
		}//check_dbid h_id && personalized

		if ($valid_h) {
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("h is valid");
		} else {
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("h is invalid");		
		}

		
		//check for valid address record
		if (check_dbid($a_id) && $personalized) {
			$ADDRESS=new tm_ADR();
			$ADR=$ADDRESS->getAdr($a_id);
			//check if adr isset , active and not unsubscribed, status !=11
			if (isset($ADR[0]) && $ADR[0]['aktiv']==1 && $ADR[0]['status']!=11)
				$valid_adr=TRUE;
				if (tm_DEBUG()) $tm_debug.=tm_message_debug("adr isset, aktiv==1, status !=11");
			}//isset ADR && aktiv
			//but wait :)
			//we checked for valid h record before, so if we maybe now have a new adr id if none was set, we will check again if the adr id is still the same as the given a_id if we have a valid h record! hehe
			if ($valid_adr && $valid_h && $H[0]['adr_id']!=$a_id) {
				$valid_adr=FALSE;
				if (tm_DEBUG()) $tm_debug.=tm_message_debug("a_id != h_a_id , ".$a_id." != ".$H[0]['adr_id']);
			}
		}//a_id && personalized


		if ($valid_adr) {
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("adr is valid");
		} else {
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("adr is invalid");		
		}


		//set status and count click of adr record
		if ($valid_adr) {
			//only set view status if not waiting status or unsubscribed // !5 && !11
			if ($ADR[0]['status']!=5 && $ADR[0]['status']!=11) {
				$ADDRESS->setStatus($a_id,4);	//view
				if (tm_DEBUG()) $tm_debug.=tm_message_debug("set adr staus to 4, view");
			}
		}//valid_adr
		
		//set ip and status on history
		if ($valid_h) {
			//nur der erste aufruf wird mit der ip versehen! ansonsten wuerde diese jedesmal ueberschrieben wenn der leser oder ein anderer das nl anschaut. i pwird seit 1088 auch in der linkclicktabelle gespeichert!
			if (empty($H[0]['ip']) || $H[0]['ip']=='0.0.0.0') {
				if (tm_DEBUG()) echo "h ip empty || 0.0.0.0:".$H[0]['ip']."<br>";
				if ($QUEUE->setHIP($h_id, getIP())) {	//save ip
					$H=$QUEUE->getH($h_id);
					if (tm_DEBUG()) $tm_debug.=tm_message_debug("saved h ip:".$H[0]['ip']."/".getIP());
				}
			}//ip
			if ($H[0]['status']!=7) { //7:unsubscribed
				$QUEUE->setHStatus($h_id,3);	//view
				if (tm_DEBUG()) $tm_debug.=tm_message_debug("set h status 3, view")	;
			}//status
		}//valid_h

		//now parse Newsletter:
		if ($NL[0]['content_type']=="html" || $NL[0]['content_type']=="text/html") {
			$content_type="html";
			header('Content-type: text/html');
		} else {
			$content_type="text";
			header('Content-type: text/plain');
		}
		if (tm_DEBUG()) $tm_debug.=tm_message_debug("content type = ".$content_type);
		if ($personalized && $valid_adr && $valid_h) {
			//personalisiert
			$_NEWSLETTER.=$NEWSLETTER->parseNL(Array('nl'=>$NL[0],'adr'=>$ADR[0],'h'=>Array('id'=>$H[0]['id']),'q'=>Array('id'=>$H[0]['q_id'])),$content_type);
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("personalized");
		} else {
			if (tm_DEBUG()) $tm_debug.=tm_message_debug("no valid adr OR no valid h OR not personalized");
			//hierdurch wird noch erreicht das nur unpersonalisierte nl als solche ausgegeben werden 
			if (!$personalized && $NL[0]['massmail']==1) {
				//anonymous
				$_NEWSLETTER.=$NEWSLETTER->parseNL(Array('nl'=>$NL[0],'q'=>Array('id'=>$q_id)),$content_type);
				if (tm_DEBUG()) $tm_debug.=tm_message_debug("massmail");
			} else {
				if (tm_DEBUG()) $tm_debug.=tm_message_debug("this is no massmail");
			}
			//ansonsten bleibt die ausgabe einfach leer!
		}
}//valid nl

if (tm_DEBUG()) {
	if (!$valid_nl) {
		$tm_debug.=tm_message_debug("invalid nl");
	}
	if (!$valid_h) {
		$tm_debug.=tm_message_debug("invalid h");
	}
	if (!$valid_adr) {
		$tm_debug.=tm_message_debug("invalid adr");
	}
	echo $tm_debug;
	echo "<hr>";
}

echo $_NEWSLETTER;

exit;
?>