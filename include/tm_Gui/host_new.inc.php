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

$_MAIN_DESCR=___("Neuen Mailserver eintragen");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$h_id=getVar("h_id");
$created=date("Y-m-d H:i:s");
$standard=0;
#$author=$LOGIN->USER['name'];

$InputName_Name="name";
$$InputName_Name=getVar($InputName_Name);

$InputName_Host="host";
$$InputName_Host=getVar($InputName_Host);

$InputName_Port="port";
$$InputName_Port=getVar($InputName_Port);

$InputName_Type="type";
$$InputName_Type=getVar($InputName_Type);

$InputName_Options="options";
$$InputName_Options=getVar($InputName_Options);

$InputName_SMTPAuth="smtp_auth";
$$InputName_SMTPAuth=getVar($InputName_SMTPAuth);

$InputName_SMTPDomain="smtp_domain";
$$InputName_SMTPDomain=getVar($InputName_SMTPDomain);

$InputName_SMTPSSL="smtp_ssl";
$$InputName_SMTPSSL=getVar($InputName_SMTPSSL);

$InputName_SMTPMaxRcpt="smtp_max_piped_rcpt";
$$InputName_SMTPMaxRcpt=getVar($InputName_SMTPMaxRcpt);

$InputName_User="user";
$$InputName_User=getVar($InputName_User);

$InputName_Pass="pass";
$$InputName_Pass=getVar($InputName_Pass);

$InputName_MaxMails="max_mails_atonce";
$$InputName_MaxMails=getVar($InputName_MaxMails);

$InputName_MaxMailsBcc="max_mails_bcc";
$$InputName_MaxMailsBcc=getVar($InputName_MaxMailsBcc);

$InputName_SenderName="sender_name";//name
$$InputName_SenderName=getVar($InputName_SenderName);

$InputName_SenderEMail="sender_email";//name
$$InputName_SenderEMail=getVar($InputName_SenderEMail);

$InputName_ReturnMail="return_mail";
$$InputName_ReturnMail=getVar($InputName_ReturnMail);

$InputName_ReplyTo="reply_to";
$$InputName_ReplyTo=getVar($InputName_ReplyTo);

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Delay="delay";
$$InputName_Delay=getVar($InputName_Delay);//delay between messages send to this host in send_it.php!

//we MUST remove leading slashes from imap options, if not we get invalid remote specification error. 
if (strpos($options, "/")==0) $options=substr_replace($options, '', 0, 1);

if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Der Name darf nicht leer sein."));}
	if (empty($port)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Der Port muss angegeben werden."));}
	if (empty($host)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Der Hostname oder IP-Adresse muss angegeben werden."));}
	if ($type=="smtp") {
		if (empty($sender_email)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die Absender E-Mail-Adresse darf nicht leer sein").".");}
		$check_mail=checkEmailAdr($sender_email,$EMailcheck_Intern);
		if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die Absender E-Mail-Adresse ist nicht gültig.")." ".$check_mail[1]);}
		if (empty($sender_name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Absender-Name darf nicht leer sein").".");}
		if (empty($reply_to)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse für Antworten darf nicht leer sein").".");}
		$check_mail=checkEmailAdr($reply_to,$EMailcheck_Intern);
		if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die Antwort-E-Mail-Adresse ist nicht gültig.")." ".$check_mail[1]);}
		if (empty($return_mail)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse für Fehlermeldungen darf nicht leer sein").".");}
		$check_mail=checkEmailAdr($return_mail,$EMailcheck_Intern);
		if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse für Fehlermeldungen ist nicht gültig.")." ".$check_mail[1]);}
	}

	if ($check) {
			if (!tm_DEMO()) {
				$HOSTS=new tm_HOST();
				$HOSTS->addHost(Array(
							"siteid"=>TM_SITEID,
							"name"=>$name,
							"aktiv"=>$aktiv,
							"host"=>$host,
							"port"=>$port,
							"options"=>$options,
							"smtp_auth"=>$smtp_auth,
							"smtp_domain"=>$smtp_domain,
							"smtp_ssl"=>$smtp_ssl,
							"smtp_max_piped_rcpt"=>$smtp_max_piped_rcpt,
							"type"=>$type,
							"user"=>$user,
							"pass"=>$pass,
							"max_mails_atonce"=>$max_mails_atonce,
							"max_mails_bcc"=>$max_mails_bcc,
							"sender_name"=>$sender_name,
							"sender_email"=>$sender_email,
							"return_mail"=>$return_mail,
							"reply_to"=>$reply_to,
							"delay"=>$delay,
							"imap_folder_sent"=>"",
							"imap_folder_trash"=>""
							));
		}//demo
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neuer Mailserver %s wurde angelegt."),"'".$name."'"));
		$action="host_list";
		require_once (TM_INCLUDEPATH_GUI."/host_list.inc.php");
	} else {//check
		require_once (TM_INCLUDEPATH_GUI."/host_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/host_form_show.inc.php");
	}//check
} else {//set==save
	require_once (TM_INCLUDEPATH_GUI."/host_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/host_form_show.inc.php");
}//set==save
?>