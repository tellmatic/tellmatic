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

$_MAIN_DESCR=___("Formluar bearbeiten");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$frm_id=getVar("frm_id");
$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

//field names for query
//default groups the subscriber gets added to....
$InputName_Group="adr_grp";
pt_register("POST",$InputName_Group);
if (!isset($$InputName_Group)) {
	$$InputName_Group=Array();
}

//public groups the subscriber can choose....
$InputName_GroupPub="adr_grp_pub";
pt_register("POST",$InputName_GroupPub);
if (!isset($$InputName_GroupPub)) {
	$$InputName_GroupPub=Array();
}

$InputName_Name="name";//betreff
$$InputName_Name=getVar($InputName_Name);

$InputName_ActionUrl="action_url";//url
$$InputName_ActionUrl=getVar($InputName_ActionUrl);

$InputName_Descr="descr";
$$InputName_Descr=getVar($InputName_Descr,0);//varname,slashes? 0=no add slashes

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_DoubleOptin="double_optin";
$$InputName_DoubleOptin=getVar($InputName_DoubleOptin);

$InputName_UseCaptcha="use_captcha";
$$InputName_UseCaptcha=getVar($InputName_UseCaptcha);

$InputName_DigitsCaptcha="digits_captcha";
$$InputName_DigitsCaptcha=getVar($InputName_DigitsCaptcha);

$InputName_SubAktiv="subscribe_aktiv";
$$InputName_SubAktiv=getVar($InputName_SubAktiv);

$InputName_Blacklist="check_blacklist";
$$InputName_Blacklist=getVar($InputName_Blacklist);

$InputName_Proof="proof";
$$InputName_Proof=getVar($InputName_Proof);

$InputName_ForcePubGroup="force_pubgroup";
$$InputName_ForcePubGroup=getVar($InputName_ForcePubGroup);

$InputName_MultiPubGroup="multiple_pubgroup";
$$InputName_MultiPubGroup=getVar($InputName_MultiPubGroup);

$InputName_OverwritePubgroup="overwrite_pubgroup";
$$InputName_OverwritePubgroup=getVar($InputName_OverwritePubgroup);

$InputName_NLDOptin="nl_id_doptin";
$$InputName_NLDOptin=getVar($InputName_NLDOptin);

$InputName_NLGreeting="nl_id_greeting";
$$InputName_NLGreeting=getVar($InputName_NLGreeting);

$InputName_NLUpdate="nl_id_update";
$$InputName_NLUpdate=getVar($InputName_NLUpdate);

$InputName_MessageDOptin="message_doptin";
$$InputName_MessageDOptin=getVar($InputName_MessageDOptin);

$InputName_MessageGreeting="message_greeting";
$$InputName_MessageGreeting=getVar($InputName_MessageGreeting);

$InputName_MessageUpdate="message_update";
$$InputName_MessageUpdate=getVar($InputName_MessageUpdate);

$InputName_SubmitValue="submit_value";
$$InputName_SubmitValue=getVar($InputName_SubmitValue);

$InputName_ResetValue="reset_value";
$$InputName_ResetValue=getVar($InputName_ResetValue);

$InputName_Host="host_id";
$$InputName_Host=getVar($InputName_Host);

$InputName_email="email";
$$InputName_email=getVar($InputName_email);

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

$InputName_F0_type="f0_type";
$$InputName_F0_type=getVar($InputName_F0_type);
$InputName_F1_type="f1_type";
$$InputName_F1_type=getVar($InputName_F1_type);
$InputName_F2_type="f2_type";
$$InputName_F2_type=getVar($InputName_F2_type);
$InputName_F3_type="f3_type";
$$InputName_F3_type=getVar($InputName_F3_type);
$InputName_F4_type="f4_type";
$$InputName_F4_type=getVar($InputName_F4_type);
$InputName_F5_type="f5_type";
$$InputName_F5_type=getVar($InputName_F5_type);
$InputName_F6_type="f6_type";
$$InputName_F6_type=getVar($InputName_F6_type);
$InputName_F7_type="f7_type";
$$InputName_F7_type=getVar($InputName_F7_type);
$InputName_F8_type="f8_type";
$$InputName_F8_type=getVar($InputName_F8_type);
$InputName_F9_type="f9_type";
$$InputName_F9_type=getVar($InputName_F9_type);

$InputName_F0_required="f0_required";
$$InputName_F0_required=getVar($InputName_F0_required);
$InputName_F1_required="f1_required";
$$InputName_F1_required=getVar($InputName_F1_required);
$InputName_F2_required="f2_required";
$$InputName_F2_required=getVar($InputName_F2_required);
$InputName_F3_required="f3_required";
$$InputName_F3_required=getVar($InputName_F3_required);
$InputName_F4_required="f4_required";
$$InputName_F4_required=getVar($InputName_F4_required);
$InputName_F5_required="f5_required";
$$InputName_F5_required=getVar($InputName_F5_required);
$InputName_F6_required="f6_required";
$$InputName_F6_required=getVar($InputName_F6_required);
$InputName_F7_required="f7_required";
$$InputName_F7_required=getVar($InputName_F7_required);
$InputName_F8_required="f8_required";
$$InputName_F8_required=getVar($InputName_F8_required);
$InputName_F9_required="f9_required";
$$InputName_F9_required=getVar($InputName_F9_required);

$InputName_F0_value="f0_value";
$$InputName_F0_value=getVar($InputName_F0_value);
$InputName_F1_value="f1_value";
$$InputName_F1_value=getVar($InputName_F1_value);
$InputName_F2_value="f2_value";
$$InputName_F2_value=getVar($InputName_F2_value);
$InputName_F3_value="f3_value";
$$InputName_F3_value=getVar($InputName_F3_value);
$InputName_F4_value="f4_value";
$$InputName_F4_value=getVar($InputName_F4_value);
$InputName_F5_value="f5_value";
$$InputName_F5_value=getVar($InputName_F5_value);
$InputName_F6_value="f6_value";
$$InputName_F6_value=getVar($InputName_F6_value);
$InputName_F7_value="f7_value";
$$InputName_F7_value=getVar($InputName_F7_value);
$InputName_F8_value="f8_value";
$$InputName_F8_value=getVar($InputName_F8_value);
$InputName_F9_value="f9_value";
$$InputName_F9_value=getVar($InputName_F9_value);

$InputName_email_errmsg="email_errmsg";
$$InputName_email_errmsg=getVar($InputName_email_errmsg);
$InputName_captcha_errmsg="captcha_errmsg";
$$InputName_captcha_errmsg=getVar($InputName_captcha_errmsg);
$InputName_Blacklist_errmsg="blacklist_errmsg";
$$InputName_Blacklist_errmsg=getVar($InputName_Blacklist_errmsg);
$InputName_PubGroup_errmsg="pubgroup_errmsg";
$$InputName_PubGroup_errmsg=getVar($InputName_PubGroup_errmsg);

$InputName_F0_errmsg="f0_errmsg";
$$InputName_F0_errmsg=getVar($InputName_F0_errmsg);
$InputName_F1_errmsg="f1_errmsg";
$$InputName_F1_errmsg=getVar($InputName_F1_errmsg);
$InputName_F2_errmsg="f2_errmsg";
$$InputName_F2_errmsg=getVar($InputName_F2_errmsg);
$InputName_F3_errmsg="f3_errmsg";
$$InputName_F3_errmsg=getVar($InputName_F3_errmsg);
$InputName_F4_errmsg="f4_errmsg";
$$InputName_F4_errmsg=getVar($InputName_F4_errmsg);
$InputName_F5_errmsg="f5_errmsg";
$$InputName_F5_errmsg=getVar($InputName_F5_errmsg);
$InputName_F6_errmsg="f6_errmsg";
$$InputName_F6_errmsg=getVar($InputName_F6_errmsg);
$InputName_F7_errmsg="f7_errmsg";
$$InputName_F7_errmsg=getVar($InputName_F7_errmsg);
$InputName_F8_errmsg="f8_errmsg";
$$InputName_F8_errmsg=getVar($InputName_F8_errmsg);
$InputName_F9_errmsg="f9_errmsg";
$$InputName_F9_errmsg=getVar($InputName_F9_errmsg);

$InputName_F0_expr="f0_expr";
$$InputName_F0_expr=getVar($InputName_F0_expr);
$InputName_F1_expr="f1_expr";
$$InputName_F1_expr=getVar($InputName_F1_expr);
$InputName_F2_expr="f2_expr";
$$InputName_F2_expr=getVar($InputName_F2_expr);
$InputName_F3_expr="f3_expr";
$$InputName_F3_expr=getVar($InputName_F3_expr);
$InputName_F4_expr="f4_expr";
$$InputName_F4_expr=getVar($InputName_F4_expr);
$InputName_F5_expr="f5_expr";
$$InputName_F5_expr=getVar($InputName_F5_expr);
$InputName_F6_expr="f6_expr";
$$InputName_F6_expr=getVar($InputName_F6_expr);
$InputName_F7_expr="f7_expr";
$$InputName_F7_expr=getVar($InputName_F7_expr);
$InputName_F8_expr="f8_expr";
$$InputName_F8_expr=getVar($InputName_F8_expr);
$InputName_F9_expr="f9_expr";
$$InputName_F9_expr=getVar($InputName_F9_expr);


	$FORMULAR=new tm_FRM();
	$FRM=$FORMULAR->getForm($frm_id);


$check=true;
if ($set=="save") {
	//checkinput
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name sollte nicht leer sein."));}
	if (!check_dbid($nl_id_doptin)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Wählen Sie ein Newsletter für die Double-Opt-In Mail."));}
	if (!check_dbid($nl_id_greeting)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Wählen Sie ein Newsletter für die Bestätigungsmail."));}
	if (!check_dbid($nl_id_update)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Wählen Sie ein Newsletter für Updates."));}
	if ($check) {
		$FORMULAR=new tm_FRM();
		$FORMULAR->updateForm(Array(
				"id"=>$frm_id,
				"name"=>$name,
				"action_url"=>$action_url,
				"descr"=>$descr,
				"aktiv"=>$aktiv,
				"created"=>$created,
				"author"=>$author,
				"use_captcha"=>$use_captcha,
				"digits_captcha"=>$digits_captcha,
				"double_optin"=>$double_optin,
				"subscribe_aktiv"=>$subscribe_aktiv,
				"check_blacklist"=>$check_blacklist,
				"proof"=>$proof,
				"force_pubgroup"=>$force_pubgroup,
				"overwrite_pubgroup"=>$overwrite_pubgroup,
				"multiple_pubgroup"=>$multiple_pubgroup,
				"nl_id_doptin"=>$nl_id_doptin,	
				"nl_id_greeting"=>$nl_id_greeting,
				"nl_id_update"=>$nl_id_update,
				"message_doptin"=>$message_doptin,	
				"message_greeting"=>$message_greeting,
				"message_update"=>$message_update,
				"submit_value"=>$submit_value,
				"reset_value"=>$reset_value,
				"host_id"=>$host_id,
				"email"=>$email,
				"f0"=>$f0,
				"f1"=>$f1,
				"f2"=>$f2,
				"f3"=>$f3,
				"f4"=>$f4,
				"f5"=>$f5,
				"f6"=>$f6,
				"f7"=>$f7,
				"f8"=>$f8,
				"f9"=>$f9,
				"f0_type"=>$f0_type,
				"f1_type"=>$f1_type,
				"f2_type"=>$f2_type,
				"f3_type"=>$f3_type,
				"f4_type"=>$f4_type,
				"f5_type"=>$f5_type,
				"f6_type"=>$f6_type,
				"f7_type"=>$f7_type,
				"f8_type"=>$f8_type,
				"f9_type"=>$f9_type,
				"f0_required"=>$f0_required,
				"f1_required"=>$f1_required,
				"f2_required"=>$f2_required,
				"f3_required"=>$f3_required,
				"f4_required"=>$f4_required,
				"f5_required"=>$f5_required,
				"f6_required"=>$f6_required,
				"f7_required"=>$f7_required,
				"f8_required"=>$f8_required,
				"f9_required"=>$f9_required,
				"f0_value"=>$f0_value,
				"f1_value"=>$f1_value,
				"f2_value"=>$f2_value,
				"f3_value"=>$f3_value,
				"f4_value"=>$f4_value,
				"f5_value"=>$f5_value,
				"f6_value"=>$f6_value,
				"f7_value"=>$f7_value,
				"f8_value"=>$f8_value,
				"f9_value"=>$f9_value,
				"email_errmsg"=>$email_errmsg,
				"captcha_errmsg"=>$captcha_errmsg,
				"blacklist_errmsg"=>$blacklist_errmsg,
				"pubgroup_errmsg"=>$pubgroup_errmsg,
				"f0_errmsg"=>$f0_errmsg,
				"f1_errmsg"=>$f1_errmsg,
				"f2_errmsg"=>$f2_errmsg,
				"f3_errmsg"=>$f3_errmsg,
				"f4_errmsg"=>$f4_errmsg,
				"f5_errmsg"=>$f5_errmsg,
				"f6_errmsg"=>$f6_errmsg,
				"f7_errmsg"=>$f7_errmsg,
				"f8_errmsg"=>$f8_errmsg,
				"f9_errmsg"=>$f9_errmsg,
				"f0_expr"=>$f0_expr,
				"f1_expr"=>$f1_expr,
				"f2_expr"=>$f2_expr,
				"f3_expr"=>$f3_expr,
				"f4_expr"=>$f4_expr,
				"f5_expr"=>$f5_expr,
				"f6_expr"=>$f6_expr,
				"f7_expr"=>$f7_expr,
				"f8_expr"=>$f8_expr,
				"f9_expr"=>$f9_expr
				),
				$adr_grp,
				$adr_grp_pub);//+öffentliche gruppen
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Formular %s wurde bearbeitet."),"'".$name."'"));
		$action="form_list";
		include_once (TM_INCLUDEPATH_GUI."/form_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/form_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/form_form_show.inc.php");
	}

} else {

	$ADDRESS=new tm_ADR();
	$adr_grp_pub=$ADDRESS->getGroupID(0,0,$frm_id,Array("public_frm_ref"=>1));
	$adr_grp=$ADDRESS->getGroupID(0,0,$frm_id);
	//hier diffen, da die adressen die als public gewaehlt sind nict als default markiert werden sollen...., öhm, ja!
	//und adr_grp neu arrangieren
	$adr_grp=array_diff($adr_grp,$adr_grp_pub);
	$adr_grp=array_values($adr_grp);
	$name=$FRM[0]['name'];
	$action_url=$FRM[0]['action_url'];
	$descr=$FRM[0]['descr'];
	$aktiv=$FRM[0]['aktiv'];
	$double_optin=$FRM[0]['double_optin'];
	$use_captcha=$FRM[0]['use_captcha'];
	$digits_captcha=$FRM[0]['digits_captcha'];
	$subscribe_aktiv=$FRM[0]['subscribe_aktiv'];
	$check_blacklist=$FRM[0]['check_blacklist'];
	$proof=$FRM[0]['proof'];
	$force_pubgroup=$FRM[0]['force_pubgroup'];
	$overwrite_pubgroup=$FRM[0]['overwrite_pubgroup'];
	$multiple_pubgroup=$FRM[0]['multiple_pubgroup'];
	$nl_id_doptin=$FRM[0]['nl_id_doptin'];
	$nl_id_greeting=$FRM[0]['nl_id_greeting'];
	$nl_id_update=$FRM[0]['nl_id_update'];
	$message_doptin=$FRM[0]['message_doptin'];
	$message_greeting=$FRM[0]['message_greeting'];
	$message_update=$FRM[0]['message_update'];
	$submit_value=$FRM[0]['submit_value'];
	$reset_value=$FRM[0]['reset_value'];
	$host_id=$FRM[0]['host_id'];
	$email=$FRM[0]['email'];
	$f0=$FRM[0]['f0'];
	$f1=$FRM[0]['f1'];
	$f2=$FRM[0]['f2'];
	$f3=$FRM[0]['f3'];
	$f4=$FRM[0]['f4'];
	$f5=$FRM[0]['f5'];
	$f6=$FRM[0]['f6'];
	$f7=$FRM[0]['f7'];
	$f8=$FRM[0]['f8'];
	$f9=$FRM[0]['f9'];
	$f0_type=$FRM[0]['f0_type'];
	$f1_type=$FRM[0]['f1_type'];
	$f2_type=$FRM[0]['f2_type'];
	$f3_type=$FRM[0]['f3_type'];
	$f4_type=$FRM[0]['f4_type'];
	$f5_type=$FRM[0]['f5_type'];
	$f6_type=$FRM[0]['f6_type'];
	$f7_type=$FRM[0]['f7_type'];
	$f8_type=$FRM[0]['f8_type'];
	$f9_type=$FRM[0]['f9_type'];
	$f0_required=$FRM[0]['f0_required'];
	$f1_required=$FRM[0]['f1_required'];
	$f2_required=$FRM[0]['f2_required'];
	$f3_required=$FRM[0]['f3_required'];
	$f4_required=$FRM[0]['f4_required'];
	$f5_required=$FRM[0]['f5_required'];
	$f6_required=$FRM[0]['f6_required'];
	$f7_required=$FRM[0]['f7_required'];
	$f8_required=$FRM[0]['f8_required'];
	$f9_required=$FRM[0]['f9_required'];
	$f0_value=$FRM[0]['f0_value'];
	$f1_value=$FRM[0]['f1_value'];
	$f2_value=$FRM[0]['f2_value'];
	$f3_value=$FRM[0]['f3_value'];
	$f4_value=$FRM[0]['f4_value'];
	$f5_value=$FRM[0]['f5_value'];
	$f6_value=$FRM[0]['f6_value'];
	$f7_value=$FRM[0]['f7_value'];
	$f8_value=$FRM[0]['f8_value'];
	$f9_value=$FRM[0]['f9_value'];
	$email_errmsg=$FRM[0]['email_errmsg'];
	$captcha_errmsg=$FRM[0]['captcha_errmsg'];
	$blacklist_errmsg=$FRM[0]['blacklist_errmsg'];
	$pubgroup_errmsg=$FRM[0]['pubgroup_errmsg'];
	$f0_errmsg=$FRM[0]['f0_errmsg'];
	$f1_errmsg=$FRM[0]['f1_errmsg'];
	$f2_errmsg=$FRM[0]['f2_errmsg'];
	$f3_errmsg=$FRM[0]['f3_errmsg'];
	$f4_errmsg=$FRM[0]['f4_errmsg'];
	$f5_errmsg=$FRM[0]['f5_errmsg'];
	$f6_errmsg=$FRM[0]['f6_errmsg'];
	$f7_errmsg=$FRM[0]['f7_errmsg'];
	$f8_errmsg=$FRM[0]['f8_errmsg'];
	$f9_errmsg=$FRM[0]['f9_errmsg'];
	$f0_expr=$FRM[0]['f0_expr'];
	$f1_expr=$FRM[0]['f1_expr'];
	$f2_expr=$FRM[0]['f2_expr'];
	$f3_expr=$FRM[0]['f3_expr'];
	$f4_expr=$FRM[0]['f4_expr'];
	$f5_expr=$FRM[0]['f5_expr'];
	$f6_expr=$FRM[0]['f6_expr'];
	$f7_expr=$FRM[0]['f7_expr'];
	$f8_expr=$FRM[0]['f8_expr'];
	$f9_expr=$FRM[0]['f9_expr'];
	require_once (TM_INCLUDEPATH_GUI."/form_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/form_form_show.inc.php");
}
?>