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

//if unsubscribe.php is included in your script, please set $called_via_url=false; $_CONTENT holds the html output

//aufruf: unsubscribe.php?h_id=&nl_id=&a_id=
//oder auch ohne parameter
//da wir ja ein formular haben
//und die email abfragen da wir bei massenmails keinen hinweis haben
//und ein massenmailing kein personalisiertes newsletter ist.....

$ADDRESS=new tm_ADR();
$BLACKLIST=new tm_BLACKLIST();
	
$MESSAGE="";
$FMESSAGE="";
$OUTPUT="";
$set=getVar("set");
$h_id=getVar("h_id");
$nl_id=getVar("nl_id");
$a_id=getVar("a_id");
$code=getVar("code");

if (tm_DEBUG()) {
	$FMESSAGE.=tm_message_debug("DEBUG enabled");
}
if (!isset($_CONTENT)) {$_CONTENT="";}
if (!isset($called_via_url)) {
	if (tm_DEBUG()) $FMESSAGE.=tm_message_debug("valled_via_url = true");
	$called_via_url=true;
}

$HOSTS=new tm_HOST();
#$HOST=$HOSTS->getStdSMTPHost();
if (tm_DEBUG()) $FMESSAGE.=tm_message_debug("select smtp host with id ".$C[0]['unsubscribe_host']);
$HOST=$HOSTS->getHost($C[0]['unsubscribe_host']);

$check=true;

$InputName_Name="email";//email
$$InputName_Name=getVar($InputName_Name);

$InputName_Captcha="fcpt";//einbgegebener Captcha Code
$$InputName_Captcha=getVar($InputName_Captcha);
$cpt=getVar("cpt");//zu pruefender captchacode, hidden field, $captcha_code
		
//if isset $a_id and adr exists and adr code=c, then prefill email!
if (check_dbid($a_id)) {
	$ADR_TMP=$ADDRESS->getAdr($a_id);
	//found entry?
	if (count($ADR_TMP)>0) {
		if (tm_DEBUG()) {
			$FMESSAGE.=tm_message_debug("Found entry with id $a_id");
		}
		//additionally check code!
		if ($ADR_TMP[0]['code']==$code) {
			if (tm_DEBUG()) {
				$FMESSAGE.=tm_message_debug("Code OK");
				$FMESSAGE.=tm_message_debug("email: ".display($ADR_TMP[0]['email']));
			}
			//ok, prefill value with email fetched from db via adr_id
			$$InputName_Name=$ADR_TMP[0]['email'];
		} else {
			if (tm_DEBUG()) {
				$FMESSAGE.=tm_message_debug("CODE NOT OK");
			}
		}
	}
}

//check input
$check_mail=checkEmailAdr($email,1);
if ($set=='unsubscribe' && !$check_mail[0]) {
	$check=false;
	$FMESSAGE.="<br>".___("Ungültige E-Mail-Adresse")."<br>";#.$check_mail[1];
}

$captcha_code="";
$captcha_md5="";
$FCAPTCHAIMG="";

if ($C[0]['unsubscribe_use_captcha']==1) {
	if ($set=='unsubscribe' && ( !is_numeric($fcpt) || empty($fcpt) || md5($fcpt)!=$cpt ) ) {
		$check=false;
		$FMESSAGE.=___("Ungültiger Captcha Code!");
	}
	//create captcha code
	//captcha digits werden einzeln erzeugt ....
	for ($digits=0;$digits<$C[0]['unsubscribe_digits_captcha'];$digits++) {
		if ($digits>0) {
			$captcha_code .= rand(0,9);
		} else {
			$captcha_code .= rand(1,9);//wenn digits=0 == erste stelle, dann keine fuehrende 0!!! bei 1 beginnen.
		}
	}
	//der md5 wird im formular uebergeben und dann mit dem md5 der eingabe verglichen
	$captcha_md5=md5($captcha_code);
	//erzeugt neuen css captcha
	$captcha_text = new Number( $captcha_code );
	//rendert den css captcha
	$FCAPTCHAIMG=$captcha_text->printNumber();
	//$FCAPTCHAIMG ist jetzt der html code fuer den css captcha...
}

if ($check==false) {
	$email="";
}

//unsubscribe
if ($check && $set=="unsubscribe") {
//unbedingt ^^^ pruefen auf gueltige email!
//sonst findet getAdr alle adressen!!! da search - email null ist / leer ist
	//adr anhand email suchen!
	$search['email']=$email;
	$search['email_exact_match']=true;
	$ADR=$ADDRESS->getAdr(0,0,1,0,$search);
	//print_r($ADR);
	if (count($ADR)>0) {
		//noch nicht abgemeldet?
		if ($ADR[0]['status']!=11) {
			/*
			if ($ADR[0]['code']==$code) {
			*/
				if (checkid($h_id)) {
					$QUEUE=new tm_Q();
					$QUEUE->setHStatus($h_id,7);	//unsubscribe!
				}
				$created=date("Y-m-d H:i:s");
				//im memo speichern wir den namen des newsletter etc.
				$memo="unsubscribed";
				$NEWSLETTER=new tm_NL();
				$NL=$NEWSLETTER->getNL($nl_id);
				if (count($NL)>0) {
					$memo.=" (".$NL[0]['subject'].")";
				}
				//set status adresse, set editor...
				$author="unsubscribe";
				//always unsubscribe ...
				if ($ADDRESS->unsubscribe($ADR[0]['id'],$author)) {
					$ADDRESS->setAktiv($ADR[0]['id'],0);
					$ADDRESS->addMemo($ADR[0]['id'],$memo);
					//unsubscribed
					
					if ($C[0]['unsubscribe_action']=="blacklist" || $C[0]['unsubscribe_action']=="blacklist_delete") {
						//add adr to blacklist
							if (!$BLACKLIST->isBlacklisted($ADR[0]['email'],"email",0)) {//only_active=0, also alle, nicht nur aktive, was default waere
								$BLACKLIST->addBL(Array(
										"siteid"=>TM_SITEID,
										"expr"=>$ADR[0]['email'],
										"aktiv"=>1,
										"type"=>"email"
										));
							}
					
					}
					if ($C[0]['unsubscribe_action']=="delete" || $C[0]['unsubscribe_action']=="blacklist_delete") {
						$ADDRESS->delAdr($ADR[0]['id']);					
					}

					if ($C[0]['notify_unsubscribe']==1) {
						//email bei subscrption an admin....
						$SubscriptionMail_Subject="Tellmatic: Abmeldung / Unsubscribe";
						$SubscriptionMail_HTML="";
						$SubscriptionMail_HTML.="<br><b>".$created."</b>\n".
														"<br>'<b>".$memo."</b>'\n".
														"<br>AID: <b>".$ADR[0]['id']."</b>\n".
														"<br>\n".
														"<br>Folgender Benutzer hat sich aus der Verteilerliste ausgetragen und moechte kein Newsletter mehr erhalten:\n".
														"<br>The following user has unsubscribed:\n".
														"<ul>Daten:\n".
														"<li>e-Mail: <b>".$ADR[0]['email']."</b></li>\n".
														"<li>F0: <b>".$ADR[0]['f0']."</b></li>\n".
														"<li>F1: <b>".$ADR[0]['f1']."</b></li>\n".
														"<li>F2: <b>".$ADR[0]['f2']."</b></li>\n".
														"<li>F3: <b>".$ADR[0]['f3']."</b></li>\n".
														"<li>F4: <b>".$ADR[0]['f4']."</b></li>\n".
														"<li>F5: <b>".$ADR[0]['f5']."</b></li>\n".
														"<li>F6: <b>".$ADR[0]['f6']."</b></li>\n".
														"<li>F7: <b>".$ADR[0]['f7']."</b></li>\n".
														"<li>F8: <b>".$ADR[0]['f8']."</b></li>\n".
														"<li>F9: <b>".$ADR[0]['f9']."</b></li>\n".
														"</ul>\n".
														"<br>\n".
														"Code: <b>".$code."</b>\n".
														"<br>\n".
														"<br>\n".
														"Der Datensatz wurde de-aktiviert und markiert (Unsubscribed) und wurde ab sofort aus der Empfaengerliste ausgeschlossen.\n".
														"<br>The Address has been deactivated and marked as unsubscribed and will be excluded from recipients list.\n";
						@SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$C[0]['notify_mail'],$HOST[0]['sender_name'],$SubscriptionMail_Subject,clear_text($SubscriptionMail_HTML),$SubscriptionMail_HTML,Array(),$HOST);//fixed, now uses defaulthost
						//now use smtp directly
						//sendmail_smtp[0]=true/false [1]=""/errormessage
						if ($C[0]['unsubscribe_sendmail']==1) {
							//unsubscribe mail to subscriber
							$UnsubscribeMail_Subject="Tellmatic: Abmeldung / Unsubscribe";
							$_Tpl_UnsubscribeMail=new tm_Template();
							$_Tpl_UnsubscribeMail->setTemplatePath(TM_TPLPATH);
							$_Tpl_UnsubscribeMail->setParseValue("EMAIL", $email);
							$_Tpl_UnsubscribeMail->setParseValue("DATE", date(TM_NL_DATEFORMAT));
							$UnsubscribeMail_HTML=$_Tpl_UnsubscribeMail->renderTemplate("Unsubscribe_mail.html");
							@SendMail_smtp($HOST[0]['sender_email'],$HOST[0]['sender_name'],$email,"<__".$email."__>",$UnsubscribeMail_Subject,clear_text($UnsubscribeMail_HTML),$UnsubscribeMail_HTML,Array(),$HOST);//fixed, now uses defaulthost
						//now use smtp directly
						//sendmail_smtp[0]=true/false [1]=""/errormessage
						}
						$email="";
					}//send notify
					$FMESSAGE.= ___("Ihre Adresse wurde aus unserem Verteiler entfernt.");
				} else {//unsubscribe()
					//sonstiger fehler
					$email="";
					$FMESSAGE.= ___("Ein Fehler ist aufgetreten");
				}
			/*
			} else {//code=code
				//code ungueltig
				$email="";
				$FMESSAGE.= ___("Ein Fehler ist aufgetreten");
			}
			*/
		} else {//status!=11
			//bereits abgemeldet
			$email="";
			$FMESSAGE.= ___("Ihre Adresse ist (nicht mehr) angemeldet.");
		}
	} else {//count adr
		//adresse existiert nicht, nix gefunden
		$email="";
		$FMESSAGE.= ___("Ungültige E-Mail-Adresse");
	}
} else {
 //keine eingabe
 $email="";
 $FMESSAGE.= "";
}

require_once(TM_INCLUDEPATH_FE."/unsubscribe_form.inc.php");
require_once(TM_INCLUDEPATH_FE."/unsubscribe_form_show.inc.php");
//new Template
$_Tpl_FRM=new tm_Template();
$_Tpl_FRM->setTemplatePath(TM_TPLPATH);
$_Tpl_FRM->setParseValue("FMESSAGE", $FMESSAGE);
$_Tpl_FRM->setParseValue("FHEAD", $FHEAD);
$_Tpl_FRM->setParseValue("FFOOT", $FFOOT);
$_Tpl_FRM->setParseValue("FSUBMIT", $FSUBMIT);
$_Tpl_FRM->setParseValue("FEMAIL", $FEMAIL);
if ($C[0]['unsubscribe_use_captcha']==1) {
	$_Tpl_FRM->setParseValue("FCAPTCHA", $FCAPTCHA);
	$_Tpl_FRM->setParseValue("FCAPTCHAIMG", $FCAPTCHAIMG);
} else {
	$_Tpl_FRM->setParseValue("FCAPTCHA", "");
	$_Tpl_FRM->setParseValue("FCAPTCHAIMG", "");
}
$OUTPUT=$_Tpl_FRM->renderTemplate("Unsubscribe.html");

//anzeige
if ($called_via_url) {
	echo $OUTPUT;
} else {
	$_CONTENT.= $OUTPUT;	
}
?>