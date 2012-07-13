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
/********************************************************************************/

/*
ERR 0: no valid form id, not a real error, not shown, use default form!
ERR 1: form not exists
ERR 2: form not active
ERR 3: invalid email
ERR 4: no valid newsletter found for double optin mail!
ERR 5: no valid newsletter found for greeting mail!
ERR 6: no valid newsletter found for update mail! 
*/
//if subscribe.php is included in your script, please set frm_id to Form ID and $called_via_url=false; $_CONTENT holds the html output
//if called via url, use fid=

/*
check for valid form: use_form=true
set default smtp
if we have a valid form, use the configured smtp host if it is valid!
check if double optin confirm, someone clicked on a subscribe link in a newsletter or double optin mail: doptin_check=true
if valid double optin, update address record and show welcome message and send greeting newsletter
if no double optin and valid form:
  set vars
  if set==save, form was submitted:
    set date and author
    check input: check=true
	if checking input was not ok, show form
    if checking input was ok:
      we check if the adr record, email, already exists and prepare for update or new record, set new groups references
      if adr already exists, update adr record
      if adr not exists, update adr record
      fetch adr record again
      if adr not exists and double optin is used, send double optin mail to user/admin
      if adr not exists and no double optin is used, send notify mail to user/admin
      if adr already exists, send update mail to user/admin (optional)
      if adr not exists, count/add subscription to form
      show successmessage      $Form_Filename
if we have a valid form and set is not set to 'save' yet, no double optin confirmation or form input not ok: show form 
*/

if (!isset($_CONTENT)) {$_CONTENT="";}
if (!isset($called_via_url)) {$called_via_url=true;}

$HOSTS=new tm_HOST();
$NEWSLETTER= new tm_NL();
$FORMULAR=new tm_FRM();
$ADDRESS=new tm_ADR();
$_Tpl_FRM=new tm_Template();
	
$MESSAGE="";
$OUTPUT="";

if (tm_DEBUG() && $called_via_url) $OUTPUT.=tm_message_debug("called via url");
if (tm_DEBUG() && !$called_via_url) $OUTPUT.=tm_message_debug("included");

$date_sub=date(TM_NL_DATEFORMAT);//used in template and email, formatted
	
if (!isset($frm_id) || empty($frm_id)) {
	$frm_id=getVar("fid",0,0);//formular id, slashes, default=0
}

$set=getVar("set");
$doptin=getVar("doptin");//opt in click, bestaetigung, aus email f. double optin
$email=getVar("email");
#$touch=getVar("touch");//touch=1 wenn erster kontakt und benutzer prueft gegen....
//$adr_id=getVar("adr_id");
$code=getVar("code");//recheck code
$check=true;
//opt in click?
$doptin_check=FALSE;
$doptin_success=FALSE;
$adr_exists=false;

$_Tpl_FRM->setTemplatePath(TM_TPLPATH);//set path for default template Form_0_os.html!
	
//use form, form values? valid form?
$use_form=FALSE;

//check for valid form
//remember: we dont have a form id if we use touch optin!, but we have a frm_id for double_optin links in newsletters sent by the form!
if (check_dbid($frm_id)) {
	//formular aus db holen
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("fetch form by id");
	$FRM=$FORMULAR->getForm($frm_id);
} else {
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("fetch default form");
	#$OUTPUT.="ERR 0";
	$FRM=$FORMULAR->getStd();
}

if (isset($FRM[0])) {
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("form id is ".$FRM[0]['id']);
	if ($FRM[0]['aktiv']==1) {
		if (tm_DEBUG()) $OUTPUT.=tm_message_debug("use_form=true");
		//set new form id, in the case we use standard form
		$frm_id=$FRM[0]['id'];
		$use_form=TRUE;
	} else {//aktiv
		if (tm_DEBUG()) $OUTPUT.=tm_message_debug("form not active");
		$OUTPUT.="ERR 2";
	}//aktiv
} else {//isset FRM[0]
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("no valid form exists");
	$OUTPUT.="ERR 1";
}

if ($doptin==1) {
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("doptin=1");
}

//if valid form, set filenames for templates
if ($use_form) {
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("valid form id :)");
	//formular template --> id
	$Form_Filename="Form_".$frm_id.".html";//formular
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("use template ".$Form_Filename);
	$Form_Success_Filename="Form_".$frm_id."_success.html";//formular
	if (tm_DEBUG()) $OUTPUT.=tm_message_debug("use template ".$Form_Success_Filename);
	//set new tpl path
	$_Tpl_FRM->setTemplatePath($tm_formpath);
}

//get default smtp host, default, as fallback
if (tm_DEBUG()) $MESSAGE.=tm_message_debug("fetch default smtp host");
$HOST=$HOSTS->getStdSMTPHost();
$use_frm_smtphost=FALSE;
//get host form valid form
if ($use_form) {// && !$doptin_check
	//get host
	if (check_dbid($FRM[0]['host_id'])) {
		//valid host id found
		$HOST_FRM=$HOSTS->getHost($FRM[0]['host_id']);
		if ($HOST_FRM[0]['aktiv']==1 && $HOST_FRM[0]['type']=='smtp') {
			$use_frm_smtphost=TRUE;
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("found valid smtp host for this form");
		} else {
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("found no valid smtp host for this form, continue use default host");		
		}
	}	
}
if ($use_frm_smtphost) {
	//if found a valid host for this form, set new host array.....
	$HOST=$HOST_FRM;
}

if ($use_form && $FRM[0]['use_captcha']==1) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("using captcha");
}
if ($use_form && $FRM[0]['double_optin']==1) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("using double-opt-in");
}
if ($use_form && $FRM[0]['force_pubgroup']==1) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("using force public group");
}
if ($use_form && $FRM[0]['check_blacklist']==1) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("using blacklist");
}

/********************************************************************************/
//check if double optin confirmation: user clicked on subscribe link in doptin mail, doptin==1 etc
if (tm_DEBUG()) $MESSAGE.=tm_message_debug("checking for double-/touch-optin click: subscribe_doptin_confirm");
require_once (TM_INCLUDEPATH_FE."/subscribe_doptin_confirm.inc.php");
/********************************************************************************/

if (!$doptin_check) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("no double optin click, continue");
}

//set vars if we have a valid form and no double optin
if ($use_form && !$doptin_check) {
	//formularfelder definieren, mForm
	$InputName_Name="email";//email
	$$InputName_Name=getVar($InputName_Name);
	$InputName_Captcha="fcpt";//einbgegebener Captcha Code
	$$InputName_Captcha=getVar($InputName_Captcha);
	$cpt=getVar("cpt");//zu pruefender captchacode, hidden field, $captcha_code
	$InputName_F0="f0";
	$$InputName_F0=getVar($InputName_F0);
	$InputName_F1="f1";
	$$InputName_F1=getVar($InputName_F1);
	$InputName_F2="f2";
	$$InputName_F2=getVar($InputName_F2);
	$InputName_F3="f3";
	$$InputName_F3=getVar($InputName_F3);
	$InputName_F4="f4";
	$$InputName_F4=getVar($InputName_F4);
	$InputName_F5="f5";
	$$InputName_F5=getVar($InputName_F5);
	$InputName_F6="f6";
	$$InputName_F6=getVar($InputName_F6);
	$InputName_F7="f7";
	$$InputName_F7=getVar($InputName_F7);
	$InputName_F8="f8";
	$$InputName_F8=getVar($InputName_F8);
	$InputName_F9="f9";
	$$InputName_F9=getVar($InputName_F9);
	$InputName_Memo="memo";
	$$InputName_Memo=clear_text(getVar($InputName_Memo));
	//public groups the subscriber can choose....
	$InputName_GroupPub="adr_grp_pub";
	pt_register("POST",$InputName_GroupPub);
	if (!isset($$InputName_GroupPub)) {
		$$InputName_GroupPub=Array();
	}
}//use form, no doptin

//kein doptin, form ok, set=save == abgeschickt
if ($use_form && !$doptin_check && $set=="save") {
	$created=date("Y-m-d H:i:s");
	//$date_sub=strftime("%d-%m-%Y %H:%M:%S",mk_microtime($created));
	#$date_sub=$created;
	$author=$FRM[0]['id'];
	//double optin
	if ($FRM[0]['double_optin']==1) {
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("double opt in");
		$status=5;//warten auf recheck
	} else {
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("simple single opt in");
		$status=1;//neu
	}
	$MESSAGE="";
	/********************************************************************************/
	//checkinput
	//prueft eingaben $FRM[0] auf gueltigkeit abhaengig der einstellungen
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("checking form input: subscribe_form_check");
	require_once (TM_INCLUDEPATH_FE."/subscribe_form_check.inc.php");
	/********************************************************************************/
}

//if all ok, set new groups and update or add adr record to db
if ($use_form && !$doptin_check && $set=="save" && $check) {
	/********************************************************************************/
	//check if adr already exists and create new adr-group array if necessary
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("check if email already exists or not, set new group refs: subscribe_adr_check");
	require_once (TM_INCLUDEPATH_FE."/subscribe_adr_check.inc.php");
	/********************************************************************************/
	//if all ok, update? create new?
	//adr not exists yet, create new
	if (!$adr_exists) {
		/********************************************************************************/
		//create new adr record
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("adr record not yet exists, create new: subscribe_new");
		require_once (TM_INCLUDEPATH_FE."/subscribe_new.inc.php");
		/********************************************************************************/
	}
	//adr already exists, update
	if ($adr_exists) {
		/********************************************************************************/
		//update adr record
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("adr record already exists, initiate update: subscribe_update");
		require_once (TM_INCLUDEPATH_FE."/subscribe_update.inc.php");
		/********************************************************************************/
	}
	//next we fetch current new adr record (again)
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("fetch adr record again:");
	$ADR=$ADDRESS->getAdr($newADRID);
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug(print_r($ADR,TRUE));

	//now send mails and show messages
	if (!$adr_exists && $FRM[0]['double_optin']==1) {
		/********************************************************************************/
		//send double optin confirmation mail with subscribe/confirmation link
		$nl_id_index="nl_id_doptin";
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("new record, send double optin confirmation email: subscribe_doptin_mail, nl_id_index=".$nl_id_index);
		require_once (TM_INCLUDEPATH_FE."/subscribe_mail.inc.php");
		/********************************************************************************/
		//show double optin message
		#$MESSAGE.=display($FRM[0]['message_doptin']);
		$MESSAGE.=display($NEWSLETTER->parseSubject( Array('text'=>$FRM[0]['message_greeting'], 'adr'=>$ADR[0], 'date'=>$date_sub) ) );
	}
	//send greeting mail if adr not exists and doptin not set in form
	if (!$adr_exists  && $FRM[0]['double_optin']!=1) {
		/********************************************************************************/
		//send greeting mail
		$nl_id_index="nl_id_greeting";
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("new record, no double optin, send greeting email: subscribe_mail, nl_id_index=".$nl_id_index);
		require_once (TM_INCLUDEPATH_FE."/subscribe_mail.inc.php");
		/********************************************************************************/
		//show welcome message
		#$MESSAGE.=display($FRM[0]['message_greeting']);
		$MESSAGE.=display($NEWSLETTER->parseSubject( Array('text'=>$FRM[0]['message_greeting'], 'adr'=>$ADR[0], 'date'=>$date_sub) ) );
	}
	//if address already exists, send update mail
	if ($adr_exists) {
		/********************************************************************************/
		//send update mail
		$nl_id_index="nl_id_update";
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("update, send update mail: subscribe_mail, nl_id_index=".$nl_id_index);
		require_once (TM_INCLUDEPATH_FE."/subscribe_mail.inc.php");
		/********************************************************************************/
		//show update message
		#$MESSAGE.=display($FRM[0]['message_update']);
			$MESSAGE.=display($NEWSLETTER->parseSubject( Array('text'=>$FRM[0]['message_update'], 'adr'=>$ADR[0], 'date'=>$date_sub) ) );	
	}

	//add subscription
	if (!$adr_exists) {
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("count subscription");
		$FORMULAR->addSub($frm_id,$newADRID);
	}
	//all ok, input checked and saved	
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("check TRUE AND set == save");
}//use form no doptin, check ok, etc

//if double optin and double optin was successfull, show message and send greeting
if ($doptin_check && $doptin_success) {
	/********************************************************************************/
	//ok send greeting mail
	$nl_id_index="nl_id_greeting";
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("double optin succeeded. send greeting mail: subscribe_mail, nl_id_index=".$nl_id_index);
	require_once (TM_INCLUDEPATH_FE."/subscribe_mail.inc.php");
	/********************************************************************************/
	#$MESSAGE.=display($FRM[0]['message_greeting']);
	$MESSAGE.=display($NEWSLETTER->parseSubject( Array('text'=>$FRM[0]['message_greeting'], 'adr'=>$ADR[0], 'date'=>$date_sub) ) );
}

//we have a valid form, no double optin confirmation click, and input is not checked yet or invalid or form not yet submitted
if ($use_form && !$doptin_check && (!$check || $set!="save")) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("valid Form && No double optin confirm AND (check FALSE OR set != save)");
	/********************************************************************************/
	//show form
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("show form: subscribe_form_show");
	require_once (TM_INCLUDEPATH_FE."/subscribe_form_show.inc.php");
	/********************************************************************************/
}

if ($use_form && ( ($doptin_check && $doptin_success) || ($set=="save" && $check) ) ) {
	/********************************************************************************/
	//ok, parse template, show a "welcome to our newsletter" message
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("parseform show message: subscribe_success");
	require_once (TM_INCLUDEPATH_FE."/subscribe_success.inc.php");
	/********************************************************************************/
}

//output
if ($called_via_url) {
	//wenn direkt aufgerufen, ausgabe
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("called via URL, echo OUTPUT");
	echo $OUTPUT;
} else {
	//wenn included, ausgabe in variable _CONTENT speichern
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("save output to OUTPUT");
	$_CONTENT.= $OUTPUT;
}
?>