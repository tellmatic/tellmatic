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

$HOST_T=$HOSTS->getHost($h_id);
$Mailer=new tm_Mail();

$host_test=FALSE;
$Error="";

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td><b>".display($HOST_T[0]['name'])."</b>".
						"</td>".
						"</tr>".
						"</thead>".
						"<tbody>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td>";
$_MAIN_OUTPUT.= "<font size=\"-1\">".display($HOST_T[0]['host'])."</font>";
$_MAIN_OUTPUT.= "<br>ID: ".$HOST_T[0]['id']." ";
if (!tm_DEMO()) {
	$_MAIN_OUTPUT.= "<br>".___("Host").": ".$HOST_T[0]['host']." ";
} else {
	$_MAIN_OUTPUT.= "<br>".___("Host").": mail.my-domain.tld ";
}
$_MAIN_OUTPUT.= "<br>".___("Type").": ".$HOST_T[0]['type']." ";
$_MAIN_OUTPUT.= "<br>".___("Port").": ".$HOST_T[0]['port']." ";
$_MAIN_OUTPUT.= "<br>".___("Options").": ".$HOST_T[0]['options']." ";
$_MAIN_OUTPUT.= "<br>".___("SMTP-Auth").": ".$HOST_T[0]['smtp_auth']." ";
if (!tm_DEMO()) {
	$_MAIN_OUTPUT.= "<br>".___("User").": ".$HOST_T[0]['user']." ";
} else {
	$_MAIN_OUTPUT.= "<br>".___("User").": my-username ";
}
if ($HOST_T[0]['aktiv']==1) {
	$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
	$_MAIN_OUTPUT.=  ___("(aktiv)");
} else {
	$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
	$_MAIN_OUTPUT.=  ___("(inaktiv)");
}
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td>";
if (!tm_DEMO()) {
	$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Verbindung zum Server %s wird aufgebaut..."),$HOST_T[0]['name']." (".$HOST_T[0]['host'].":".$HOST_T[0]['port']."/".$HOST_T[0]['type'].")"));
} else {
	$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Verbindung zum Server %s wird aufgebaut..."),$HOST_T[0]['name']." (mail.my-domain.tld:".$HOST_T[0]['port']."/".$HOST_T[0]['type'].")"));
}

//POP3 IMAP testen
if ($HOST_T[0]['type']=="imap" || $HOST_T[0]['type']=="pop3")	{
	if (!tm_DEMO()) {
		$Mailer->Connect($HOST_T[0]['host'], $HOST_T[0]['user'], $HOST_T[0]['pass'],$HOST_T[0]['type'],$HOST_T[0]['port'],$HOST_T[0]['options']);
		if (!empty($Mailer->Error)) {
			$Error=tm_message_error($Mailer->Error);
			$host_test=FALSE;
		} else {
			$host_test=TRUE;
			$_MAIN_OUTPUT .=tm_message_success(sprintf(___("Gesamt: %s Mails"),$Mailer->count_msg));
			#$Error=
		}
	}//demo
	if (tm_DEMO()) $host_test=TRUE;
}//type==pop3/imap

if ($HOST_T[0]['type']=="imap")	{
	if (!tm_DEMO()) {
		$_MAIN_OUTPUT .= "<br><strong>".___("Imap Quota").":</strong>";
		$ImapQuota=$Mailer->getQuota($HOST_T[0]['user']);
		$_MAIN_OUTPUT .= "<br>".$ImapQuota['STORAGE']['usage']."KB / ".$ImapQuota['STORAGE']['limit']." KB";
		
		$_MAIN_OUTPUT .= "<table border=1 bordercolor=\"#eeeeee\" width=\"100%\" cellpadding=0 cellspacing=0><tbody><tr><td align=\"left\" bgcolor=\"#00ff00\">";
		$quota_pc=round($ImapQuota['STORAGE']['usage']/($ImapQuota['STORAGE']['limit']/100));
		$_MAIN_OUTPUT .= "<table border=1 bordercolor=\"#eeeeee\" width=\"".$quota_pc."%\" cellpadding=0 cellspacing=0><tbody><tr><td align=\"left\" bgcolor=\"#ff0000\">";
		
		$_MAIN_OUTPUT .="<strong>".$quota_pc."%"."</strong>";
		$_MAIN_OUTPUT .="</td></tr></tbody></table>";
		$_MAIN_OUTPUT .="</td></tr></tbody></table>";
		
		$_MAIN_OUTPUT .= "<br><strong>".___("Imap Ordner").":</strong>";
		
		$_MAIN_OUTPUT .= "<table border=1 bordercolor=\"#eeeeee\" width=\"100%\" cellpadding=1 cellspacing=1>";
		$_MAIN_OUTPUT .= "<thead>";
		$_MAIN_OUTPUT .= "<tr><td align=\"left\">".___("Imap Ordner")."</td><td align=\"left\" width=30>".___("Neue")."</td><td align=\"left\" width=30>".___("Ungelesen")."</td><td align=\"left\" width=30>".___("Gesamt")."</td></tr>";
		$_MAIN_OUTPUT .= "</thead>";
		$_MAIN_OUTPUT .= "<tbody>";
		$ImapFolders=$Mailer->getFolders();
		foreach ($ImapFolders as $Folder) {
			$ImapStatus=$Mailer->ImapStatus($Folder['mapname']);
			$_MAIN_OUTPUT .= "<tr><td align=\"left\">";
			$_MAIN_OUTPUT .= $Folder['mapname'];
			$_MAIN_OUTPUT .= "</td><td align=\"left\">";
			$_MAIN_OUTPUT .= $ImapStatus['recent'];
			$_MAIN_OUTPUT .= "</td><td align=\"left\">";
			$_MAIN_OUTPUT .= $ImapStatus['unseen'];
			$_MAIN_OUTPUT .= "</td><td align=\"left\">";
			$_MAIN_OUTPUT .= $ImapStatus['messages'];
			$_MAIN_OUTPUT .= "</td></tr>";
		}
		$_MAIN_OUTPUT .= "</tbody>";
		$_MAIN_OUTPUT .= "</table>";
		$_MAIN_OUTPUT .= $Mailer->Error;

		#$_MAIN_OUTPUT .= "<br><strong>".___("Status").":</strong>";
		#$ImapStatus=$Mailer->ImapStatus("INBOX");
		#$_MAIN_OUTPUT .= "<br>".___("Nachrichten Gesamt").": ".$ImapStatus['messages'];
		#$_MAIN_OUTPUT .= "<br>".___("Nachrichten Neu").": ".$ImapStatus['recent'];
		#$_MAIN_OUTPUT .= "<br>".___("Nachrichten Ungelesen").": ".$ImapStatus['unseen'];
		#$_MAIN_OUTPUT .= $Mailer->Error;
	}
}

//SMTP testen
if ($HOST_T[0]['type']=="smtp")	{
	if (!tm_DEMO()) {

		$TestMessage=___("Hallo");
		$TestMessage.="<br><br>";
		$TestMessage.=sprintf(___("Hier ist %s"),TM_APPTEXT);
		$TestMessage.="<br>";
		$TestMessage.=sprintf(___("Wenn Sie diese Nachricht empfangen haben, war der SMTP-Server-Test für %s erfolgreich."),"'".$HOST_T[0]['name']."'");
		$TestMessage.="<br>";
		$TestMessage.=sprintf(___("Danke das Sie %s benutzen"),TM_APPTEXT);
		$TestMessage.="<hr>v.";

		$TestMessage_Text=___("Hallo",0);
		$TestMessage_Text.="\n\n";
		$TestMessage_Text.=sprintf(___("Hier ist %s",0),TM_APPTEXT);
		$TestMessage_Text.="\n";
		$TestMessage_Text.=sprintf(___("Wenn Sie diese Nachricht empfangen haben, war der SMTP-Server-Test für %s erfolgreich.",0),"'".$HOST_T[0]['name']."'");
		$TestMessage_Text.="\n\n";
		$TestMessage_Text.=sprintf(___("Danke das Sie %s benutzen",0),TM_APPTEXT);
		$TestMessage_Text.="\n-------------\nv.";


		$subject_t=sprintf(___("Tellmatic SMTP-Server-Test (%s)"),$HOST_T[0]['name']);

		$smtp_err=SendMail_smtp($HOST_T[0]['sender_email'],$HOST_T[0]['sender_name'],$LOGIN->USER['email'],$LOGIN->USER['name'],$subject_t,$TestMessage_Text,$TestMessage,$AttmFiles=Array(),$HOST_T);

		
		if (!$smtp_err[0]) {
			$host_test=FALSE;
			$Error.=$smtp_err[1];#$email_obj->debug_msg;
		} else {
			$Error.=tm_message_success(sprintf(___("Eine Testmail wurde an die E-Mail-Adresse %s %s gesendet."),$LOGIN->USER['name'],$LOGIN->USER['email']));
			$host_test=TRUE;
		}
	}//demo
	if (tm_DEMO()) {
		$Error.=tm_message_success(sprintf(___("Eine Testmail wurde an die E-Mail-Adresse %s %s gesendet."),$LOGIN->USER['name'],$LOGIN->USER['email']));
		$host_test=TRUE;
	}
	$Error.=tm_message_notice(sprintf(___("Wenn Sie diese Nachricht empfangen haben, war der SMTP-Server-Test für %s erfolgreich."),"'".	$HOST_T[0]['name']."'"));
}//type==smtp

$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td>";
#$_MAIN_OUTPUT .= "".display($Error)."";
$_MAIN_OUTPUT .= $Error;
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td>";
if (!$host_test)  {
	#$_MAIN_OUTPUT.=  "".tm_icon("cancel.png",___("Fehler"))."&nbsp;";
	$_MAIN_OUTPUT .= tm_message_error(___("Der Test war nicht erfolgreich."));
}
if ($host_test)  {
	#$_MAIN_OUTPUT.=  "".tm_icon("tick.png",___("OK"))."&nbsp;";
	$_MAIN_OUTPUT .= tm_message_success(___("Der Test war erfolgreich."));
}
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td>";
$_MAIN_OUTPUT.= "&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "</tbody></table>";
?>