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

$InputName_Submit="submit";
$InputName_Reset="reset";

//Form
$Form=new tm_SimpleForm();
$FormularName="edit_host";
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" OnChange=\"checkHostType();\" onClick=\"checkHostType();\" ");
$Form->set_FormDesc($FormularName,___("Mailserver bearbeiten"));

//variable content aus menu als hidden field!
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"h_id", "hidden", $h_id);
//////////////////
//add inputfields and buttons....
//////////////////

//Name
$Form->new_Input($FormularName,$InputName_Name,"text",display($$InputName_Name));
$Form->set_InputJS($FormularName,$InputName_Name," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\ \_\.\-\@]');\"");
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,48,256);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Name"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"");


//HOSTName/IP
$Form->new_Input($FormularName,$InputName_Host,"text",display($$InputName_Host));
$Form->set_InputJS($FormularName,$InputName_Host," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_Host,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Host,48,256);
$Form->set_InputDesc($FormularName,$InputName_Host,___("Hostname / IP-Adresse"));
$Form->set_InputReadonly($FormularName,$InputName_Host,false);
$Form->set_InputOrder($FormularName,$InputName_Host,3);
$Form->set_InputLabel($FormularName,$InputName_Host,"");

//Port
$Form->new_Input($FormularName,$InputName_Port,"text",display($$InputName_Port));
$Form->set_InputJS($FormularName,$InputName_Port," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^0-9]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_Port,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Port,8,8);
$Form->set_InputDesc($FormularName,$InputName_Port,___("Port"));
$Form->set_InputReadonly($FormularName,$InputName_Port,false);
$Form->set_InputOrder($FormularName,$InputName_Port,5);
$Form->set_InputLabel($FormularName,$InputName_Port,"");

//Options
$Form->new_Input($FormularName,$InputName_Options,"text",display($$InputName_Options));
$Form->set_InputJS($FormularName,$InputName_Options," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9_\.\/\-]');\"");
$Form->set_InputStyleClass($FormularName,$InputName_Options,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Options,48,256);
$Form->set_InputDesc($FormularName,$InputName_Options,___("Options"));
$Form->set_InputReadonly($FormularName,$InputName_Options,false);
$Form->set_InputOrder($FormularName,$InputName_Options,8);
$Form->set_InputLabel($FormularName,$InputName_Options,"");

//predefined options, static!
$Form->new_Input($FormularName,'predefined_imap_options',"select", "");
$Form->set_InputJS($FormularName,'predefined_imap_options'," onChange=\"copyselectedoption('options',this,'/','');flash('submit','#ff0000');\"");
$Form->set_InputDefault($FormularName,'predefined_imap_options',$$InputName_SMTPAuth);
$Form->set_InputStyleClass($FormularName,'predefined_imap_options',"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,'predefined_imap_options',___("IMAP/POP3 Optionen"));
$Form->set_InputReadonly($FormularName,'predefined_imap_options',false);
$Form->set_InputOrder($FormularName,'predefined_imap_options',9999);
$Form->set_InputLabel($FormularName,'predefined_imap_options',"");
$Form->set_InputSize($FormularName,'predefined_imap_options',0,1);
$Form->set_InputMultiple($FormularName,'predefined_imap_options',false);
//add Data
$Form->add_InputOption($FormularName,'predefined_imap_options',"","-- Option(en) auswählen --");
$Form->add_InputOption($FormularName,'predefined_imap_options',"novalidate-cert","novalidate-cert");
$Form->add_InputOption($FormularName,'predefined_imap_options',"validate-cert","validate-cert");
$Form->add_InputOption($FormularName,'predefined_imap_options',"ssl","ssl");
$Form->add_InputOption($FormularName,'predefined_imap_options',"tls","tls");
$Form->add_InputOption($FormularName,'predefined_imap_options',"notls","notls");
$Form->add_InputOption($FormularName,'predefined_imap_options',"readonly","readonly");
if (tm_DEBUG()) $Form->add_InputOption($FormularName,'predefined_imap_options',"debug","debug");

//SMTP-AuthType
$Form->new_Input($FormularName,$InputName_SMTPAuth,"select", "");
$Form->set_InputJS($FormularName,$InputName_SMTPAuth," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SMTPAuth,$$InputName_SMTPAuth);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPAuth,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SMTPAuth,___("SMTP-Auth"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPAuth,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPAuth,13);
$Form->set_InputLabel($FormularName,$InputName_SMTPAuth,"");
$Form->set_InputSize($FormularName,$InputName_SMTPAuth,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SMTPAuth,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"LOGIN","LOGIN");
$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"PLAIN","PLAIN");
$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"CRAM-MD5","CRAM-MD5");
#$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"Digest","Digest");
#$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"NTML","NTML");

//SMTP-Domain
$Form->new_Input($FormularName,$InputName_SMTPDomain,"text",display($$InputName_SMTPDomain));
$Form->set_InputJS($FormularName,$InputName_SMTPDomain," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\.\-]');\"");
$Form->set_InputStyleClass($FormularName,$InputName_SMTPDomain,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPDomain,48,256);
$Form->set_InputDesc($FormularName,$InputName_SMTPDomain,___("SMTP-Domain"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPDomain,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPDomain,14);
$Form->set_InputLabel($FormularName,$InputName_SMTPDomain,"");


//Type
$Form->new_Input($FormularName,$InputName_Type,"select", "");
$Form->set_InputJS($FormularName,$InputName_Type," onChange=\"flash('submit','#ff0000');checkHostType();\" ");
$Form->set_InputDefault($FormularName,$InputName_Type,$$InputName_Type);
$Form->set_InputStyleClass($FormularName,$InputName_Type,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Type,___("Typ"));
$Form->set_InputReadonly($FormularName,$InputName_Type,false);
$Form->set_InputOrder($FormularName,$InputName_Type,4);
$Form->set_InputLabel($FormularName,$InputName_Type,"");
$Form->set_InputSize($FormularName,$InputName_Type,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Type,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Type,"smtp","SMTP");
$Form->add_InputOption($FormularName,$InputName_Type,"pop3","POP3");
$Form->add_InputOption($FormularName,$InputName_Type,"imap","IMAP4");

//User
$Form->new_Input($FormularName,$InputName_User,"text", display($$InputName_User));
$Form->set_InputJS($FormularName,$InputName_User," onChange=\"flash('submit','#ff0000');\"");//onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\.\-\@-]');\" removed due to special chars may appear in usernames
$Form->set_InputStyleClass($FormularName,$InputName_User,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_User,48,256);
$Form->set_InputDesc($FormularName,$InputName_User,___("Benutzername"));
$Form->set_InputReadonly($FormularName,$InputName_User,false);
$Form->set_InputOrder($FormularName,$InputName_User,6);
$Form->set_InputLabel($FormularName,$InputName_User,"");

//passwd
$Form->new_Input($FormularName,$InputName_Pass,"password", display($$InputName_Pass));
$Form->set_InputJS($FormularName,$InputName_Pass," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Pass,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Pass,48,256);
$Form->set_InputDesc($FormularName,$InputName_Pass,___("Passwort"));
$Form->set_InputReadonly($FormularName,$InputName_Pass,false);
$Form->set_InputOrder($FormularName,$InputName_Pass,7);
$Form->set_InputLabel($FormularName,$InputName_Pass,"");

//Mails at once
$Form->new_Input($FormularName,$InputName_MaxMails,"select", "");
$Form->set_InputJS($FormularName,$InputName_MaxMails," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_MaxMails,$$InputName_MaxMails);
$Form->set_InputStyleClass($FormularName,$InputName_MaxMails,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_MaxMails,___("Maximale Anzahl Mails pro Durchgang"));
$Form->set_InputReadonly($FormularName,$InputName_MaxMails,false);
$Form->set_InputOrder($FormularName,$InputName_MaxMails,17);
$Form->set_InputLabel($FormularName,$InputName_MaxMails,"");
$Form->set_InputSize($FormularName,$InputName_MaxMails,0,1);
$Form->set_InputMultiple($FormularName,$InputName_MaxMails,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_MaxMails,1,"  1 - single");
$Form->add_InputOption($FormularName,$InputName_MaxMails,5,"  5 - very low");
$Form->add_InputOption($FormularName,$InputName_MaxMails,10," 10 - low Traffic");
$Form->add_InputOption($FormularName,$InputName_MaxMails,25," 25 - small Newsletter");
$Form->add_InputOption($FormularName,$InputName_MaxMails,35," 35 - normal *");
$Form->add_InputOption($FormularName,$InputName_MaxMails,50," 50 - bulk mail");
$Form->add_InputOption($FormularName,$InputName_MaxMails,75," 75 - fast");
$Form->add_InputOption($FormularName,$InputName_MaxMails,100,"100 - fast");
$Form->add_InputOption($FormularName,$InputName_MaxMails,150,"150 - high");
$Form->add_InputOption($FormularName,$InputName_MaxMails,250,"250 - very high");
$Form->add_InputOption($FormularName,$InputName_MaxMails,500,"500 - too much");
$Form->add_InputOption($FormularName,$InputName_MaxMails,1000,"1000 - 2fast4u");

//Mails Bcc
$Form->new_Input($FormularName,$InputName_MaxMailsBcc,"select", "");
$Form->set_InputJS($FormularName,$InputName_MaxMailsBcc," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_MaxMailsBcc,$$InputName_MaxMailsBcc);
$Form->set_InputStyleClass($FormularName,$InputName_MaxMailsBcc,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_MaxMailsBcc,___("Maximale Anzahl BCC-Adressen pro Mail für Massenmailing"));
$Form->set_InputReadonly($FormularName,$InputName_MaxMailsBcc,false);
$Form->set_InputOrder($FormularName,$InputName_MaxMailsBcc,18);
$Form->set_InputLabel($FormularName,$InputName_MaxMailsBcc,"");
$Form->set_InputSize($FormularName,$InputName_MaxMailsBcc,0,1);
$Form->set_InputMultiple($FormularName,$InputName_MaxMailsBcc,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,1,"  1");
/*$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,5,"  5");
$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,10," 10");
$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,25," 25");
$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,50," 50");
$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,75," 75");
$Form->add_InputOption($FormularName,$InputName_MaxMailsBcc,100,"100");
*/

//Aktiv
if ($standard==1) {
	$Form->new_Input($FormularName,$InputName_Aktiv, "hidden", 1);
} else {
	$Form->new_Input($FormularName,$InputName_Aktiv,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Aktiv," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Aktiv,$$InputName_Aktiv);
	$Form->set_InputStyleClass($FormularName,$InputName_Aktiv,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Aktiv,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Aktiv,___("Aktiv"));
	$Form->set_InputReadonly($FormularName,$InputName_Aktiv,false);
	$Form->set_InputOrder($FormularName,$InputName_Aktiv,2);
	$Form->set_InputLabel($FormularName,$InputName_Aktiv,"");
}

//SMTP SSL
$Form->new_Input($FormularName,$InputName_SMTPSSL,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_SMTPSSL," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SMTPSSL,$$InputName_SMTPSSL);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPSSL,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPSSL,48,256);
$Form->set_InputDesc($FormularName,$InputName_SMTPSSL,___("SSL"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPSSL,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPSSL,15);
$Form->set_InputLabel($FormularName,$InputName_SMTPSSL,"");

//Type
$Form->new_Input($FormularName,$InputName_SMTPMaxRcpt,"select", $$InputName_SMTPMaxRcpt);
$Form->set_InputJS($FormularName,$InputName_SMTPMaxRcpt," onChange=\"flash('submit','#ff0000');checkHostType();\" ");
$Form->set_InputDefault($FormularName,$InputName_SMTPMaxRcpt,$$InputName_SMTPMaxRcpt);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPMaxRcpt,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SMTPMaxRcpt,___("max. rcpt to"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPMaxRcpt,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPMaxRcpt,16);
$Form->set_InputLabel($FormularName,$InputName_SMTPMaxRcpt,"");
$Form->set_InputSize($FormularName,$InputName_SMTPMaxRcpt,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SMTPMaxRcpt,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_SMTPMaxRcpt,1,"1");
$Form->add_InputOption($FormularName,$InputName_SMTPMaxRcpt,5,"5");
$Form->add_InputOption($FormularName,$InputName_SMTPMaxRcpt,10,"10");
$Form->add_InputOption($FormularName,$InputName_SMTPMaxRcpt,25,"25");
$Form->add_InputOption($FormularName,$InputName_SMTPMaxRcpt,50,"50");
$Form->add_InputOption($FormularName,$InputName_SMTPMaxRcpt,100,"100");

//sender_name, email etc
$Form->new_Input($FormularName,$InputName_SenderName,"text", $$InputName_SenderName);
$Form->set_InputJS($FormularName,$InputName_SenderName," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_SenderName,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SenderName,48,256);
$Form->set_InputDesc($FormularName,$InputName_SenderName,___("Erscheint als Absender-Name in der E-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_SenderName,false);
$Form->set_InputOrder($FormularName,$InputName_SenderName,10);
$Form->set_InputLabel($FormularName,$InputName_SenderName,"");

//sender_name, email etc
$Form->new_Input($FormularName,$InputName_SenderEMail,"text", $$InputName_SenderEMail);
$Form->set_InputJS($FormularName,$InputName_SenderEMail," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_SenderEMail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SenderEMail,48,256);
$Form->set_InputDesc($FormularName,$InputName_SenderEMail,___("Erscheint als Absender-E-Mail-Adresse in der E-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_SenderEMail,false);
$Form->set_InputOrder($FormularName,$InputName_SenderEMail,9);
$Form->set_InputLabel($FormularName,$InputName_SenderEMail,"");

//reply_to
$Form->new_Input($FormularName,$InputName_ReplyTo,"text", $$InputName_ReplyTo);
$Form->set_InputJS($FormularName,$InputName_ReplyTo," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_ReplyTo,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ReplyTo,48,256);
$Form->set_InputDesc($FormularName,$InputName_ReplyTo,___("Reply-to, Adresse für E-Mail Antworten"));
$Form->set_InputReadonly($FormularName,$InputName_ReplyTo,false);
$Form->set_InputOrder($FormularName,$InputName_ReplyTo,11);
$Form->set_InputLabel($FormularName,$InputName_ReplyTo,"");

//return_mail etc
$Form->new_Input($FormularName,$InputName_ReturnMail,"text", $$InputName_ReturnMail);
$Form->set_InputJS($FormularName,$InputName_ReturnMail," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_ReturnMail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ReturnMail,48,256);
$Form->set_InputDesc($FormularName,$InputName_ReturnMail,___("Return-Path, Adresse für E-Mail Fehlermeldungen"));
$Form->set_InputReadonly($FormularName,$InputName_ReturnMail,false);
$Form->set_InputOrder($FormularName,$InputName_ReturnMail,12);
$Form->set_InputLabel($FormularName,$InputName_ReturnMail,"");

//Delay
$Form->new_Input($FormularName,$InputName_Delay,"select", "");
$Form->set_InputJS($FormularName,$InputName_Delay," onChange=\"flash('submit','#ff0000');checkHostType();\" ");
$Form->set_InputDefault($FormularName,$InputName_Delay,$$InputName_Delay);
$Form->set_InputStyleClass($FormularName,$InputName_Delay,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Delay,___("Pause"));
$Form->set_InputReadonly($FormularName,$InputName_Delay,false);
$Form->set_InputOrder($FormularName,$InputName_Delay,4);
$Form->set_InputLabel($FormularName,$InputName_Delay,"");
$Form->set_InputSize($FormularName,$InputName_Delay,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Delay,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Delay,0,"0 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,100000,"0.1 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,250000,"0.25 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,500000,"0.5 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,750000,"0.75 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,1000000,"1 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,1500000,"1.5 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,2000000,"2 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,2500000,"2.5 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,3000000,"3 ".___("Sekunden"));
$Form->add_InputOption($FormularName,$InputName_Delay,5000000,"5 ".___("Sekunden"));

//needed for trash and sent folder list
if (!tm_DEMO() && $action=="host_edit"  && $type=="imap") {
	$Mailer=new tm_Mail();
	$Mailer->Connect($HOST[0]['host'], $HOST[0]['user'], $HOST[0]['pass'],$HOST[0]['type'],$HOST[0]['port'],$HOST[0]['options']);
	if (empty($Mailer->Error)) {
		$ImapFolders=$Mailer->getFolders();
	}
}

//Imap Sent Folder
if ($action=="host_edit" && $type=="imap") {
	$Form->new_Input($FormularName,$InputName_ImapFolderSent,"select", "");
	$Form->set_InputJS($FormularName,$InputName_ImapFolderSent," onChange=\"flash('submit','#ff0000');checkHostType();\" ");
	$Form->set_InputDefault($FormularName,$InputName_ImapFolderSent,$$InputName_ImapFolderSent);
	$Form->set_InputStyleClass($FormularName,$InputName_ImapFolderSent,"mFormSelect","mFormSelectFocus");
	$Form->set_InputDesc($FormularName,$InputName_ImapFolderSent,___("Ordner für ausgehende Mails"));
	$Form->set_InputReadonly($FormularName,$InputName_ImapFolderSent,false);
	$Form->set_InputOrder($FormularName,$InputName_ImapFolderSent,4);
	$Form->set_InputLabel($FormularName,$InputName_ImapFolderSent,"");
	$Form->set_InputSize($FormularName,$InputName_ImapFolderSent,0,1);
	$Form->set_InputMultiple($FormularName,$InputName_ImapFolderSent,false);

	if (!tm_DEMO()) {
		if (!empty($Mailer->Error)) {
			#$Error=tm_message_error($Mailer->Error);
			$Form->add_InputOption($FormularName,$InputName_ImapFolderSent,"",$Mailer->Error);
		} else {
			#$_MAIN_OUTPUT .=tm_message_success(sprintf(___("Gesamt: %s Mails"),$Mailer->count_msg));
			//add Data
			$Form->add_InputOption($FormularName,$InputName_ImapFolderSent,"","");
			//get and add folders to list
			foreach ($ImapFolders as $Folder) {
				/*
				$ImapStatus=$Mailer->ImapStatus($Folder['mapname']);
				$ImapStatus['recent'];
				$ImapStatus['unseen'];
				$ImapStatus['messages'];
				*/
				$Form->add_InputOption($FormularName,$InputName_ImapFolderSent,$Folder['mapname'],$Folder['mapname']);
			}
		}
	} else {
		$Form->add_InputOption($FormularName,$InputName_ImapFolderSent,"","");
	}//demo
}//host_edit

//Imap Trash Folder
if ($action=="host_edit" &&  $type=="imap") {
	$Form->new_Input($FormularName,$InputName_ImapFolderTrash,"select", "");
	$Form->set_InputJS($FormularName,$InputName_ImapFolderTrash," onChange=\"flash('submit','#ff0000');checkHostType();\" ");
	$Form->set_InputDefault($FormularName,$InputName_ImapFolderTrash,$$InputName_ImapFolderTrash);
	$Form->set_InputStyleClass($FormularName,$InputName_ImapFolderTrash,"mFormSelect","mFormSelectFocus");
	$Form->set_InputDesc($FormularName,$InputName_ImapFolderTrash,___("Ordner für gelöschte Mails"));
	$Form->set_InputReadonly($FormularName,$InputName_ImapFolderTrash,false);
	$Form->set_InputOrder($FormularName,$InputName_ImapFolderTrash,4);
	$Form->set_InputLabel($FormularName,$InputName_ImapFolderTrash,"");
	$Form->set_InputSize($FormularName,$InputName_ImapFolderTrash,0,1);
	$Form->set_InputMultiple($FormularName,$InputName_ImapFolderTrash,false);

	if (!tm_DEMO()) {
		if (!empty($Mailer->Error)) {
			#$Error=tm_message_error($Mailer->Error);
			$Form->add_InputOption($FormularName,$InputName_ImapFolderTrash,"",$Mailer->Error);
		} else {
			#$_MAIN_OUTPUT .=tm_message_success(sprintf(___("Gesamt: %s Mails"),$Mailer->count_msg));
			//add Data
			$Form->add_InputOption($FormularName,$InputName_ImapFolderTrash,"","");
			//get and add folders to list
			foreach ($ImapFolders as $Folder) {
				/*
				$ImapStatus=$Mailer->ImapStatus($Folder['mapname']);
				$ImapStatus['recent'];
				$ImapStatus['unseen'];
				$ImapStatus['messages'];
				*/
				$Form->add_InputOption($FormularName,$InputName_ImapFolderTrash,$Folder['mapname'],$Folder['mapname']);
			}
		}
	} else {
		$Form->add_InputOption($FormularName,$InputName_ImapFolderTrash,"","");
	}//demo
}//host_edit
//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Speichern"));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");

//a reset button
$Form->new_Input($FormularName,$InputName_Reset,"reset",___("Reset"));
$Form->set_InputStyleClass($FormularName,$InputName_Reset,"mFormReset","mFormResetFocus");
$Form->set_InputDesc($FormularName,$InputName_Reset,___("Reset"));
$Form->set_InputReadonly($FormularName,$InputName_Reset,false);
$Form->set_InputOrder($FormularName,$InputName_Reset,999);
$Form->set_InputLabel($FormularName,$InputName_Reset,"");
?>