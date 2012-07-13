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

$_MAIN_DESCR=___("Neuen Benutzer anlegen");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$u_id=getVar("u_id");
$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$InputName_Name="name";
$$InputName_Name=getVar($InputName_Name);

$InputName_EMail="email";
$$InputName_EMail=getVar($InputName_EMail);

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Expert="expert";
$$InputName_Expert=getVar($InputName_Expert);

$InputName_Admin="admin";
$$InputName_Admin=getVar($InputName_Admin);

$InputName_Manager="manager";
$$InputName_Manager=getVar($InputName_Manager);

$InputName_Startpage="startpage";
$$InputName_Startpage=getVar($InputName_Startpage);

$InputName_Style="style";
$$InputName_Style=getVar($InputName_Style);

$InputName_Lang="lang";
$$InputName_Lang=getVar($InputName_Lang);

$InputName_Demo="demo";
$$InputName_Demo=getVar($InputName_Demo);

$InputName_Debug="debug";
$$InputName_Debug=getVar($InputName_Debug);

$InputName_DebugLang="debug_lang";
$$InputName_DebugLang=getVar($InputName_DebugLang);

$InputName_DebugLangLevel="debug_lang_level";
$$InputName_DebugLangLevel=getVar($InputName_DebugLangLevel);

$InputName_Pass="pass";//
$$InputName_Pass=getVar($InputName_Pass);
$InputName_Pass2="pass2";//
$$InputName_Pass2=getVar($InputName_Pass2);

if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($pass)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kein Passwort angegeben."));}
	if (strlen($pass)<$minlength_pw) {$check=false;$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Passwort sollte mindestens %s Zeichen haben."),$minlength_pw));}
	if ($pass != $pass2) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Bitte geben Sie zweimal das gleiche Passwort an."));}
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Der Name darf nicht leer sein."));}
	if (empty($email)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse darf nicht leer sein."));}
	//email auf gueltigkeit pruefen
	$check_mail=checkEmailAdr($email,$EMailcheck_Intern);
	if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse ist nicht gültig.")." ".$check_mail[1]);}
	if ($check) {
			if (!tm_DEMO()) {
				$USERS=new tm_CFG();
				$pass_hash=md5(TM_SITEID.$name.$pass);
				$USERS->addUSER(Array(
							"siteid"=>TM_SITEID,
							"name"=>$name,
							"passwd"=>$pass_hash,
							"crypt"=>crypt($pass,CRYPT_EXT_DES),
							"email"=>$email,
							"aktiv"=>$aktiv,
							"admin"=>$admin,
							"manager" =>$manager,
							"startpage"=>$startpage,
							"style"=>$style,
							"lang"=>$lang,
							"expert"=>0,
							"demo"=>$demo,
							"debug"=>$debug,
							"debug_lang"=>$debug_lang,
							"debug_lang_level"=>$debug_lang_level
							));
				//neue .htpasswd schreiben!
				$tm_htpasswd="";
				//userliste holen
				$ALL_USERS=$USERS->getUsers();
				$uc=count($ALL_USERS);
				for ($ucc=0;$ucc<$uc;$ucc++) {
					$tm_htpasswd.=$ALL_USERS[$ucc]['name'].":".$ALL_USERS[$ucc]['crypt']."\n";
				}//for
				//neue .htpasswd schreiben!
				write_file(TM_INCLUDEPATH,".htpasswd",$tm_htpasswd);
				unset($ALL_USERS);
			}//demo
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neuer Benutzer %s wurde angelegt."),"'".$name."'"));
		$_MAIN_MESSAGE.=tm_message_success(___("Eine neue .htpasswd Datei wurde erzeugt."));
		$action="adm_user_list";
		require_once (TM_INCLUDEPATH_GUI."/adm_user_list.inc.php");
	} else {//check
		require_once (TM_INCLUDEPATH_GUI."/adm_user_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/adm_user_form_show.inc.php");
	}//check
} else {//set==save
	require_once (TM_INCLUDEPATH_GUI."/adm_user_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/adm_user_form_show.inc.php");
}//set==save
?>