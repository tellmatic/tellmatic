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

$_MAIN_DESCR=___("Benutzereinstellungen ändern");
$_MAIN_MESSAGE.="";

$set=getVar("set");

//field names for query
$InputName_Pass="pass";//
$$InputName_Pass=getVar($InputName_Pass);
$InputName_Pass2="pass2";//
$$InputName_Pass2=getVar($InputName_Pass2);

$InputName_Style="style";
$$InputName_Style=getVar($InputName_Style);

$InputName_Lang="lang";
$$InputName_Lang=getVar($InputName_Lang);

$InputName_Expert="expert";
$$InputName_Expert=getVar($InputName_Expert);

$InputName_Startpage="startpage";
$$InputName_Startpage=getVar($InputName_Startpage);

$InputName_EMail="email";
$$InputName_EMail=getVar($InputName_EMail);

$InputName_Debug="debug";
$$InputName_Debug=getVar($InputName_Debug);

$InputName_DebugLang="debug_lang";
$$InputName_DebugLang=getVar($InputName_DebugLang);

$InputName_DebugLangLevel="debug_lang_level";
$$InputName_DebugLangLevel=getVar($InputName_DebugLangLevel);

$check=true;
if ($set=="save") {
	//checkinput
	$pwchanged=0;
	$usr_message="";
	if (empty($pass)) {$check=false;$usr_message.=tm_message_error(___("Kein Passwort angegeben."));}
	if (strlen($pass)<$minlength_pw) {$check=false;$usr_message.=tm_message_error(sprintf(___("Passwort sollte mindestens %s Zeichen haben."),$minlength_pw));}
	if ($pass != $pass2) {$check=false;$usr_message.=tm_message_error(___("Bitte geben Sie zweimal das gleiche Passwort an."));}

	$check_mail=checkEmailAdr($email,$EMailcheck_Intern);
	if (!$check_mail[0]) {$check=false;$usr_message.=tm_message_error(___("E-Mail-Adresse ist nicht gültig.")." ".$check_mail[1]);}
	$USER=new tm_CFG();
	if ($check) {
		if (!tm_DEMO()) {
			$pass_hash=md5(TM_SITEID.$LOGIN->USER['name'].$pass);
			$USER->setPasswd($LOGIN->USER['name'],$pass_hash,crypt($pass,CRYPT_EXT_DES));
			$_SESSION['user_pw_md5']=$pass_hash;
			//neue .htpasswd schreiben!
			$tm_htpasswd="";
			//userliste holen
			$USERS=$USER->getUsers();
			$uc=count($USERS);
			for ($ucc=0;$ucc<$uc;$ucc++) {
				#$tm_htpasswd.=$USERS[$ucc]['name'].":".crypt($USERS[$ucc]['passwd'],CRYPT_EXT_DES)."\n";
				$tm_htpasswd.=$USERS[$ucc]['name'].":".$USERS[$ucc]['crypt']."\n";
			}
			//neue .htpasswd schreiben!
			write_file(TM_INCLUDEPATH,".htpasswd",$tm_htpasswd);
			$usr_message.=tm_message_success(___("Eine neue .htpasswd Datei wurde erzeugt."));
			$pwchanged=1;
		}
	} else {
		$usr_message.=tm_message_notice(___("Das Passwort wurde nicht geändert."));
	}
	#$_MAIN_MESSAGE.=$usr_message;
	if (!tm_DEMO()) $USER->setEMail($LOGIN->USER['name'],$email);
	if (!tm_DEMO()) $USER->setStyle($LOGIN->USER['name'],$style);
	if (!tm_DEMO()) $USER->setLang($LOGIN->USER['name'],$lang);
	if (!tm_DEMO()) $USER->setDebugLang($LOGIN->USER['name'],$debug_lang,$debug_lang_level);
	if (!tm_DEMO() && $user_is_admin) $USER->setDebug($LOGIN->USER['name'],$debug);
	$USER->setExpert($LOGIN->USER['name'],$expert);
	if (!tm_DEMO()) $USER->setStartpage($LOGIN->USER['name'],$startpage);
	$check_val=0;
	if ($check) $check_val=1;
	#header('Location: '.$tm_URL."/?act=user&pwchanged=".$pwchanged."&cryptpw=".crypt($LOGIN->USER['passwd']));
	header('Location: '.$tm_URL."/?act=user&amp;pwchanged=".$pwchanged."&amp;check=".$check_val."&amp;usr_message=".urlencode($usr_message));
	exit;
}

//read css directories and check for stylesheets and template directories
$CSSDirs=Array();
$CSSDirsTmp=getCSSDirectories(TM_PATH."/css");
$css_c=count($CSSDirsTmp);
$css_i=0;
for ($css_cc=0; $css_cc < $css_c; $css_cc++) {
	$css_file=TM_PATH."/css/".$CSSDirsTmp[$css_cc]."/tellmatic.css";
	$tpl_dir=TM_TPLPATH."/".$CSSDirsTmp[$css_cc];
	if (file_exists($css_file)) {
		if (is_dir($tpl_dir)) {
			$CSSDirs[$css_i]["dir"]=$CSSDirsTmp[$css_cc];
			$CSSDirs[$css_i]["name"]=$CSSDirsTmp[$css_cc];
			$css_i++;
		}
	}
}
unset($CSSDirsTmp);

#print_r($CSSDirsTmp);
#print_r($CSSDirs);

$$InputName_Style=$LOGIN->USER['style'];
$$InputName_Lang=$LOGIN->USER['lang'];
$$InputName_Expert=$LOGIN->USER['expert'];
$$InputName_Startpage=$LOGIN->USER['startpage'];
$$InputName_Debug=$LOGIN->USER['debug'];
$$InputName_DebugLang=$LOGIN->USER['debug_lang'];
$$InputName_DebugLangLevel=$LOGIN->USER['debug_lang_level'];
require_once (TM_INCLUDEPATH_GUI."/user_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/user_form_show.inc.php");
?>