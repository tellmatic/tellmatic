<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        	*/
/* tellmatic, the newslettermachine                                             	*/
/* tellmatic, die Newslettermaschine                                            */
/* 2006/7 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

/***********************************************************/
//init
/***********************************************************/
$check=true;
$ERR_MESSAGE="";
/***********************************************************/
//php v5/6?
/***********************************************************/
if (version_compare(phpversion(), "5.0", ">=")) {
	$usePhp5=true;
} else {
	$usePhp5=false;
}

define("PHP5",$usePhp5);

/***********************************************************/
//check
/***********************************************************/
/***********************************************************/
//check if tm_config already exists!
/***********************************************************/
if (file_exists(TM_INCLUDEPATH."/tm_config.inc.php")) {
	if (!tm_DEMO()) $ERR_MESSAGE.=tm_message_error(sprintf(___("%s exisitert bereits."),TM_INCLUDEPATH."/tm_config.inc.php"));
	if (!tm_DEMO()) $check=false;
}

//check for available memory
if ($mem==0) {
	//woot, unlimited memory...
	//i wouldn't recommend it to set to unlimited, ... but ok
}
if ($exec_time==0) {
	//yeah, unlimited time to execute php...
}

if (!PHP5) {
	$ERR_MESSAGE.=tm_message_error(___("FEHLER! Tellmatic benoetigt mindestens PHP Version 5.2"));
	$check=false;
}

if (ini_get("register_globals")=='on') {
	$ERR_MESSAGE.=tm_message_warning(sprintf(___("Register Globals ist %s."),___("AN")));
} else {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("Register Globals ist %s."),___("AUS")));
}
if (!ini_get("safe_mode")=='off') {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("Safe Mode ist %s."),___("AUS")));
} else {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("Safe Mode ist %s."),___("AN")));
}
if (ini_get("magic_quotes_gpc")=='on') {
	$ERR_MESSAGE.=tm_message_warning(sprintf(___("Magic Quotes ist %s."),___("AN")));
} else {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("Magic Quotes ist %s."),___("AUS")));
}
if(!function_exists('file_get_contents')) {
	$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist nicht aktiviert."),"file_get_contents"));
	$check=false;
} else {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("%s ist verfügbar."),"file_get_contents"));
}


if (!ini_get("allow_url_fopen")) {
	$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist nicht aktiviert."),"allow_url_fopen"));
	$check=false;
} else {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("%s ist aktiviert."),"allow_url_fopen"));
}
	$ERR_MESSAGE.=tm_message_config("allow_url_fopen:".ini_get("allow_url_fopen"));


if ($mem>0 && $mem<8*1024*1024) {
	$ERR_MESSAGE.=tm_message_error(sprintf(___("FEHLER! Für Tellmatic sollten mindestens %s Speicher für PHP zur Verfügung stehen, besser mehr"),"8MB"));
	$check=false;
}
$ERR_MESSAGE.=tm_message_config(sprintf(___("Speicher für PHP: %s Byte."),$mem));

if ($exec_time> 0 && $exec_time<15) {
	$ERR_MESSAGE.=tm_message_error(sprintf(___("FEHLER! Für Tellmatic sollten mindestens %s Sekunden Ausführungszeit für PHP zur Verfügung stehen, besser mehr"),"15"));
	$check=false;
}
$ERR_MESSAGE.=tm_message_config(sprintf(___("Ausführungszeit für PHP: %s sek."),$exec_time));

if ($mem<16*1024*1024) {
}
if ($exec_time<30) {
}


//if check is true (enough memory, exec time and php version)
if ($check) {
/***********************************************************/
	//check for windows
/***********************************************************/
	if (PHPWIN) {
		$ERR_MESSAGE.=tm_message_error(___("Sie verwenden Windows."));
		$check=false;
	}
/***********************************************************/
//check for imap_open
/***********************************************************/
if (!function_exists('imap_open')) {
	$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist nicht aktiviert."),"IMAP library"));
	$ERR_MESSAGE.=tm_message_warning(___("Die Funktion imap_open() existiert nicht. Bouncemanagement und Mailbox wird nicht funktionieren. Weitere Details finden Sie den den Dateien README und INSTALL oder der FAQ."));
	$check=false;
} else {
	$ERR_MESSAGE.=tm_message_config(sprintf(___("%s ist verfügbar."),"IMAP library"));
}


/***********************************************************/
//check dir permissions
/***********************************************************/
	if (!is_writeable(TM_ADMINPATH_TMP)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),TM_ADMINPATH_TMP));
		$check=false;
	}
	
	if (!is_writeable(TM_TPLPATH)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),TM_TPLPATH));
		$check=false;
	}
	if (!is_writeable(TM_INCLUDEPATH)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),TM_INCLUDEPATH));
		$check=false;
	}
	if (!is_writeable($tm_datapath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_datapath));
		$check=false;
	}
	if (!is_writeable($tm_tmppath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_tmppath));
		$check=false;
	}
	if (!is_writeable($tm_nlpath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_nlpath));
		$check=false;
	}
	if (!is_writeable($tm_nlattachpath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_nlattachpath));
		$check=false;
	}
	if (!is_writeable($tm_nlimgpath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_nlimgpath));
		$check=false;
	}
	if (!is_writeable($tm_logpath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_logpath));
		$check=false;
	}
	if (!is_writeable($tm_formpath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_formpath));
		$check=false;
	}
	if (!is_writeable($tm_reportpath)) {
		$ERR_MESSAGE.=tm_message_error(sprintf(___("%s ist Schreibgeschützt. Weitere Details finden Sie den den Dateien README und INSTALL"),$tm_reportpath));
		$check=false;
	}
} // check
/***********************************************************/

/***********************************************************/
if (!$check) {
	$ERR_MESSAGE.=tm_message_warning(___("Es sind Fehler aufgetreten."));
	$ERR_MESSAGE.=tm_message_error(___("Tellmatic kann nicht installiert werden."));
	$ERR_MESSAGE.="<a href=\"".$_SERVER['PHP_SELF']."?lang=".$lang."&amp;accept=".$accept."\" target=\"_self\">".
								___("Neu laden")."</a>";
}
$MESSAGE.=$ERR_MESSAGE;
$ERR_MESSAGE="";
?>