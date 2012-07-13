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

$_MAIN_DESCR=___("Adresse bearbeiten");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$adr_id=getVar("adr_id");
$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$offset=getVar("offset");
$limit=getVar("limit");
$s_email=getVar("s_email");
$s_status=getVar("s_status");
$s_author=getVar("s_author");
$s_aktiv=getVar("s_aktiv");
$adr_grp_id=getVar("adr_grp_id");
$st=getVar("st");
$si=getVar("si");
$si0=getVar("si0");
$si1=getVar("si1");
$si2=getVar("si2");

//field names for query
$InputName_Group="adr_grp";//range from
pt_register("POST","adr_grp");
if (!isset($adr_grp)) {
	$adr_grp=Array();
}

$InputName_Name="email";//
$$InputName_Name=getVar($InputName_Name);

$InputName_Aktiv="aktiv";//
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Status="status";//
$$InputName_Status=getVar($InputName_Status);

$InputName_F0="f0";//range from
$$InputName_F0=getVar($InputName_F0);
$InputName_F1="f1";//range from
$$InputName_F1=getVar($InputName_F1);
$InputName_F2="f2";//range from
$$InputName_F2=getVar($InputName_F2);
$InputName_F3="f3";//range from
$$InputName_F3=getVar($InputName_F3);
$InputName_F4="f4";//range from
$$InputName_F4=getVar($InputName_F4);
$InputName_F5="f5";//range from
$$InputName_F5=getVar($InputName_F5);
$InputName_F6="f6";//range from
$$InputName_F6=getVar($InputName_F6);
$InputName_F7="f7";//range from
$$InputName_F7=getVar($InputName_F7);
$InputName_F8="f8";//range from
$$InputName_F8=getVar($InputName_F8);
$InputName_F9="f9";//range from
$$InputName_F9=getVar($InputName_F9);

$InputName_Memo="memo";
$$InputName_Memo=getVar($InputName_Memo);

$ADDRESS=new tm_ADR();
$ADR=$ADDRESS->getAdr($adr_id);

$check=true;
if ($set=="save") {
	//checkinput
	if (empty($email)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse darf nicht leer sein."));}
	//email auf gueltigkeit pruefen
	$check_mail=checkEmailAdr($email,$EMailcheck_Intern);
	if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse ist nicht gültig.")." ".$check_mail[1]);}
	if ($check) {
		$ADDRESS=new tm_ADR();
		$ADDRESS->updateAdr(Array(
				"id"=>$adr_id,
				"email"=>$email,
				"aktiv"=>$aktiv,
				"created"=>$created,
				"author"=>$author,
				"f0"=>$f0,
				"f1"=>$f1,
				"f2"=>$f2,
				"f3"=>$f3,
				"f4"=>$f4,
				"f5"=>$f5,
				"f6"=>$f6,
				"f7"=>$f7,
				"f8"=>$f8,
				"f9"=>$f9
				),
				$adr_grp);
		//"memo"=>$memo,
		//hier newmemo benutzen da memo sonst doppelt!
		$ADDRESS->newMemo($ADR[0]['id'],$memo);
		$ADDRESS->setStatus($adr_id,$status);

		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Adresse %s wurde bearbeitet."),"'".$email."'"));
		$action="adr_list";
		require_once ("adr_list.inc.php");
	} else {//check
		require_once (TM_INCLUDEPATH_GUI."/adr_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/adr_form_show.inc.php");
	}//check

} else {
	$adr_grp=$ADDRESS->getGroupID(0,$adr_id,0);
	$email=$ADR[0]['email'];
	$aktiv=$ADR[0]['aktiv'];
	$status=$ADR[0]['status'];
	$f0=$ADR[0]['f0'];
	$f1=$ADR[0]['f1'];
	$f2=$ADR[0]['f2'];
	$f3=$ADR[0]['f3'];
	$f4=$ADR[0]['f4'];
	$f5=$ADR[0]['f5'];
	$f6=$ADR[0]['f6'];
	$f7=$ADR[0]['f7'];
	$f8=$ADR[0]['f8'];
	$f9=$ADR[0]['f9'];
	$memo=$ADR[0]['memo'];
	require_once (TM_INCLUDEPATH_GUI."/adr_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/adr_form_show.inc.php");
}
?>