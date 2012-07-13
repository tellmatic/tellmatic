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

//Form
$Form=new tm_SimpleForm();
$FormularName="tm_i_data";
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormDesc($FormularName,___("Tellmatic Installation - Daten eingeben"));
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"accept", "hidden", $accept);
//////////////////
//add inputfields and buttons....
//////////////////
//name
$Form->new_Input($FormularName,$InputName_Name,"text", $$InputName_Name);
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,40,40);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Benutzername"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,___("Benutzername").":<br>");

//pass
$Form->new_Input($FormularName,$InputName_Pass,"password", $$InputName_Pass);
$Form->set_InputStyleClass($FormularName,$InputName_Pass,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Pass,40,40);
$Form->set_InputDesc($FormularName,$InputName_Pass,___("Passwort"));
$Form->set_InputReadonly($FormularName,$InputName_Pass,false);
$Form->set_InputOrder($FormularName,$InputName_Pass,2);
$Form->set_InputLabel($FormularName,$InputName_Pass,___("Passwort").":<br>");

//pass2
$Form->new_Input($FormularName,$InputName_Pass2,"password", $$InputName_Pass2);
$Form->set_InputStyleClass($FormularName,$InputName_Pass2,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Pass2,40,40);
$Form->set_InputDesc($FormularName,$InputName_Pass2,___("Passwort"));
$Form->set_InputReadonly($FormularName,$InputName_Pass2,false);
$Form->set_InputOrder($FormularName,$InputName_Pass2,3);
$Form->set_InputLabel($FormularName,$InputName_Pass2,___("Passwort wiederholen").":<br>");

//lang
$Form->new_Input($FormularName,$InputName_Lang,"select", "");
$Form->set_InputDefault($FormularName,$InputName_Lang,$$InputName_Lang);
$Form->set_InputStyleClass($FormularName,$InputName_Lang,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Lang,___("Sprache"));
$Form->set_InputReadonly($FormularName,$InputName_Lang,false);
$Form->set_InputOrder($FormularName,$InputName_Lang,3);
$Form->set_InputLabel($FormularName,$InputName_Lang,___("Sprache").":<br>");
$Form->set_InputSize($FormularName,$InputName_Lang,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Lang,false);
//add Data
$lc=count($LANGUAGES['lang']);
for ($lcc=0;$lcc<$lc;$lcc++) {
	$Form->add_InputOption($FormularName,$InputName_Lang,$LANGUAGES['lang'][$lcc],$LANGUAGES['text'][$lcc]);
}

//email
$Form->new_Input($FormularName,$InputName_EMail,"text", $$InputName_EMail);
$Form->set_InputStyleClass($FormularName,$InputName_EMail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_EMail,40,40);
$Form->set_InputDesc($FormularName,$InputName_EMail,___("E-Mail-Adresse"));
$Form->set_InputReadonly($FormularName,$InputName_EMail,false);
$Form->set_InputOrder($FormularName,$InputName_EMail,4);
$Form->set_InputLabel($FormularName,$InputName_EMail,___("E-Mail-Adresse").":<br>");

//dbs
$Form->new_Input($FormularName,$InputName_DBHost,"text", $$InputName_DBHost);
$Form->set_InputStyleClass($FormularName,$InputName_DBHost,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBHost,40,255);
$Form->set_InputDesc($FormularName,$InputName_DBHost,___("Datenbank Servername oder IP-Adresse"));
$Form->set_InputReadonly($FormularName,$InputName_DBHost,false);
$Form->set_InputOrder($FormularName,$InputName_DBHost,5);
$Form->set_InputLabel($FormularName,$InputName_DBHost,___("Datenbank Servername oder IP-Adresse")."<br>");

//dbs
$Form->new_Input($FormularName,$InputName_DBPort,"text", $$InputName_DBPort);
$Form->set_InputStyleClass($FormularName,$InputName_DBPort,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBPort,40,40);
$Form->set_InputDesc($FormularName,$InputName_DBPort,___("Datenbank Port"));
$Form->set_InputReadonly($FormularName,$InputName_DBPort,false);
$Form->set_InputOrder($FormularName,$InputName_DBPort,6);
$Form->set_InputLabel($FormularName,$InputName_DBPort,___("Datenbank Port")."<br>");

//dbs
$Form->new_Input($FormularName,$InputName_DBSocket,"checkbox", 1);
$Form->set_InputDefault($FormularName,$InputName_DBSocket,$$InputName_DBSocket);
$Form->set_InputStyleClass($FormularName,$InputName_DBSocket,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBSocket,48,256);
$Form->set_InputDesc($FormularName,$InputName_DBSocket,___("Benutze Socket statt TCP-Port"));
$Form->set_InputReadonly($FormularName,$InputName_DBSocket,false);
$Form->set_InputOrder($FormularName,$InputName_DBSocket,2);
$Form->set_InputLabel($FormularName,$InputName_DBSocket,___("Benutze Socket"));

//DBType
$Form->new_Input($FormularName,$InputName_DBType,"select", "");
$Form->set_InputDefault($FormularName,$InputName_DBType,$$InputName_DBType);
$Form->set_InputStyleClass($FormularName,$InputName_DBType,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_DBType,___("Datenbank Typ"));
$Form->set_InputReadonly($FormularName,$InputName_DBType,false);
$Form->set_InputOrder($FormularName,$InputName_DBType,16);
$Form->set_InputLabel($FormularName,$InputName_DBType,"Typ:");
$Form->set_InputSize($FormularName,$InputName_DBType,0,1);
$Form->set_InputMultiple($FormularName,$InputName_DBType,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_DBType,"MyISAM","MyISAM");
$Form->add_InputOption($FormularName,$InputName_DBType,"InnoDB","InnoDB");

//dbs
$Form->new_Input($FormularName,$InputName_DBName,"text", $$InputName_DBName);
$Form->set_InputStyleClass($FormularName,$InputName_DBName,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBName,40,40);
$Form->set_InputDesc($FormularName,$InputName_DBName,___("Datenbank Name"));
$Form->set_InputReadonly($FormularName,$InputName_DBName,false);
$Form->set_InputOrder($FormularName,$InputName_DBName,7);
$Form->set_InputLabel($FormularName,$InputName_DBName,___("Datenbank Name")."<br>");

//dbs
$Form->new_Input($FormularName,$InputName_DBUser,"text", $$InputName_DBUser);
$Form->set_InputStyleClass($FormularName,$InputName_DBUser,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBUser,40,40);
$Form->set_InputDesc($FormularName,$InputName_DBUser,___("Datenbank Benutzername"));
$Form->set_InputReadonly($FormularName,$InputName_DBUser,false);
$Form->set_InputOrder($FormularName,$InputName_DBUser,8);
$Form->set_InputLabel($FormularName,$InputName_DBUser,___("Datenbank Benutzername")."<br>");

//dbs
$Form->new_Input($FormularName,$InputName_DBPass,"password", $$InputName_DBPass);
$Form->set_InputStyleClass($FormularName,$InputName_DBPass,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBPass,40,40);
$Form->set_InputDesc($FormularName,$InputName_DBPass,___("Datenbank Paswort"));
$Form->set_InputReadonly($FormularName,$InputName_DBPass,false);
$Form->set_InputOrder($FormularName,$InputName_DBPass,9);
$Form->set_InputLabel($FormularName,$InputName_DBPass,___("Datenbank Paswort")."<br>");

//DBTablePrefix
$Form->new_Input($FormularName,$InputName_DBTablePrefix,"text", $$InputName_DBTablePrefix);
$Form->set_InputStyleClass($FormularName,$InputName_DBTablePrefix,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DBTablePrefix,40,40);
$Form->set_InputDesc($FormularName,$InputName_DBTablePrefix,___("Tabellen Prefix"));
$Form->set_InputReadonly($FormularName,$InputName_DBTablePrefix,false);
$Form->set_InputOrder($FormularName,$InputName_DBTablePrefix,10);
$Form->set_InputLabel($FormularName,$InputName_DBTablePrefix,___("Tabellen Prefix")."<br>");

//smtp
$Form->new_Input($FormularName,$InputName_SMTPHost,"text", $$InputName_SMTPHost);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPHost,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPHost,40,40);
$Form->set_InputDesc($FormularName,$InputName_SMTPHost,___("SMTP Servername oder IP-Adresse"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPHost,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPHost,11);
$Form->set_InputLabel($FormularName,$InputName_SMTPHost,___("SMTP Servername oder IP-Adresse")."<br>");

//smtp
$Form->new_Input($FormularName,$InputName_SMTPPort,"text", $$InputName_SMTPPort);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPPort,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPPort,40,40);
$Form->set_InputDesc($FormularName,$InputName_SMTPPort,___("SMTP Port, normalerweise 25 o. 587"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPPort,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPPort,12);
$Form->set_InputLabel($FormularName,$InputName_SMTPPort,___("SMTP Port, normalerweise 25 o. 587")."<br>");

//smtp
$Form->new_Input($FormularName,$InputName_SMTPUser,"text", $$InputName_SMTPUser);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPUser,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPUser,40,40);
$Form->set_InputDesc($FormularName,$InputName_SMTPUser,___("SMTP Benutzername"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPUser,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPUser,13);
$Form->set_InputLabel($FormularName,$InputName_SMTPUser,___("SMTP Benutzername")."<br>");

//smtp
$Form->new_Input($FormularName,$InputName_SMTPPass,"password", $$InputName_SMTPPass);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPPass,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPPass,40,40);
$Form->set_InputDesc($FormularName,$InputName_SMTPPass,___("SMTP Passwort"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPPass,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPPass,14);
$Form->set_InputLabel($FormularName,$InputName_SMTPPass,___("SMTP Passwort")."<br>");

//smtp
$Form->new_Input($FormularName,$InputName_SMTPDomain,"text", $$InputName_SMTPDomain);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPDomain,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SMTPDomain,40,40);
$Form->set_InputDesc($FormularName,$InputName_SMTPDomain,___("SMTP Domainname"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPDomain,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPDomain,15);
$Form->set_InputLabel($FormularName,$InputName_SMTPDomain,___("SMTP Domainname")."<br>");

//SMTP-AuthType
$Form->new_Input($FormularName,$InputName_SMTPAuth,"select", "");
$Form->set_InputJS($FormularName,$InputName_SMTPAuth," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SMTPAuth,$$InputName_SMTPAuth);
$Form->set_InputStyleClass($FormularName,$InputName_SMTPAuth,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SMTPAuth,___("SMTP-Auth"));
$Form->set_InputReadonly($FormularName,$InputName_SMTPAuth,false);
$Form->set_InputOrder($FormularName,$InputName_SMTPAuth,16);
$Form->set_InputLabel($FormularName,$InputName_SMTPAuth,"");
$Form->set_InputSize($FormularName,$InputName_SMTPAuth,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SMTPAuth,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"LOGIN","LOGIN");
$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"PLAIN","PLAIN");
$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"CRAM-MD5","CRAM-MD5");
#$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"Digest","Digest");
#$Form->add_InputOption($FormularName,$InputName_SMTPAuth,"NTML","NTML");

//regtext

$Form->new_Input($FormularName,$InputName_RegMsg,"textarea", $MESSAGE_REG);
$Form->set_InputStyleClass($FormularName,$InputName_RegMsg,"mFormTextArea","mFormTextAreaFocus");
$Form->set_InputSize($FormularName,$InputName_RegMsg,80,10);
$Form->set_InputDesc($FormularName,$InputName_RegMsg,"Info");
$Form->set_InputReadonly($FormularName,$InputName_RegMsg,false);
$Form->set_InputOrder($FormularName,$InputName_RegMsg,1);
$Form->set_InputLabel($FormularName,$InputName_RegMsg,___("Informationen")."<br>");

//reg
$Form->new_Input($FormularName,$InputName_Reg,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Reg," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Reg,$$InputName_Accept);
$Form->set_InputStyleClass($FormularName,$InputName_Reg,"mFormCheckbox","mFormCheckboxFocus");
$Form->set_InputSize($FormularName,$InputName_Reg,1,1);
$Form->set_InputDefault($FormularName,$InputName_Reg,1);
$Form->set_InputDesc($FormularName,$InputName_Reg,___("Informationen an den Entwickler von Tellmatic senden"));
$Form->set_InputReadonly($FormularName,$InputName_Reg,false);
$Form->set_InputOrder($FormularName,$InputName_Reg,3);
$Form->set_InputLabel($FormularName,$InputName_Reg,___("Informationen an den Entwickler von Tellmatic senden"));

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Installieren"));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");

?>