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
//check if double optin
if ($doptin==1 && !empty($code) && !empty($email)) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("double opt in click!");
	$doptin_check=TRUE;
	//check for valid email
	$check_mail=checkEmailAdr($email,$EMailcheck_Subscribe);
}
//invalid email
if ($doptin_check && !$check_mail[0]) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("invalid email!");
	if ($use_form) {
		$OUTPUT.=$FRM[0]['email_errmsg']."<br>";
	} else {
		$OUTPUT.="ERR 3<br>";
	}
}
if ($doptin_check && $check_mail[0]) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("validating email");
	//adresse pruefen:
	//double optin bestaetigung:
	//adr suchen, code vergleichen, wenn ok, weiter, sonst ...... leere seite! -?
	$search['email']=$email;
	$search['code']=$code;
	$search['email_exact_match']=true;
	//harte pruefung, nur wenn noch nicht bestaetigt:	$search['status']=5; oder touch
	//limit=1: adr holen
	$ADR=$ADDRESS->getAdr(0,0,1,0,$search);
	if (isset($ADR[0]) && check_dbid($ADR[0]['id']) && $ADR[0]['code']==$code) {
		//ja, code muesste man nicht nochmal pruefen, wird ja in search bereits in der db gesucht....
		//setstatus adr_id = 3
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("email found, now setting status to 3: confirmed");
		$ADDRESS->setStatus($ADR[0]['id'],3);
		$doptin_success=true;
	}
}
?>