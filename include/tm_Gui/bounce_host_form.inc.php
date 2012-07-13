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
$FormularName="bounce_host";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//$this->set_FormStyle($FormularName,"font-size:10pt;font-color=red;");
////$Form->set_FormStyleClass($FormularName,"mForm");
////$Form->set_FormInputStyleClass($FormularName,"mFormText","mFormTextFocus");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("Host wählen"));
//variable content aus menu als hidden field!
//$Form->new_Input($FormularName,"type", "hidden", $content);
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "connect");
$Form->new_Input($FormularName,"val", "hidden", "list");

//////////////////
//add inputfields and buttons....
//////////////////
//HOST
$Form->new_Input($FormularName,$InputName_Host,"select", "");
$Form->set_InputJS($FormularName,$InputName_Host," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Host,$$InputName_Host);
$Form->set_InputStyleClass($FormularName,$InputName_Host,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_Host,1,1);
$Form->set_InputDesc($FormularName,$InputName_Host,___("Host auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_Host,false);
$Form->set_InputOrder($FormularName,$InputName_Host,1);
$Form->set_InputLabel($FormularName,$InputName_Host,"");
$Form->set_InputMultiple($FormularName,$InputName_Host,false);
#Hostliste....
//pop3 hosts
$HOST_=$HOSTS->getHost("",Array("aktiv"=>1, "type"=>"imap"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_Host,$HOST_[$hccg]['id'],$HOST_[$hccg]['name']);
}
//imap hosts
$HOST_=$HOSTS->getHost("",Array("aktiv"=>1, "type"=>"pop3"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_Host,$HOST_[$hccg]['id'],$HOST_[$hccg]['name']);
}
//Offset
$Form->new_Input($FormularName,$InputName_Offset,"select", "");
$Form->set_InputJS($FormularName,$InputName_Offset," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Offset,$$InputName_Offset);
$Form->set_InputStyleClass($FormularName,$InputName_Offset,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Offset,___("Anzahl Mails die übersprungen werden."));
$Form->set_InputReadonly($FormularName,$InputName_Offset,false);
$Form->set_InputOrder($FormularName,$InputName_Offset,2);
$Form->set_InputLabel($FormularName,$InputName_Offset,"");
$Form->set_InputSize($FormularName,$InputName_Offset,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Offset,false);
//add data
$Form->add_InputOption($FormularName,$InputName_Offset,10,"10");
for ($occ=0;$occ<=500;$occ=$occ+25) {
	$Form->add_InputOption($FormularName,$InputName_Offset,$occ,$occ." ");
}
//Limit
$Form->new_Input($FormularName,$InputName_Limit,"select", "");
$Form->set_InputJS($FormularName,$InputName_Limit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Limit,$$InputName_Limit);
$Form->set_InputStyleClass($FormularName,$InputName_Limit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Limit,___("max. Anzahl Mails die durchsucht/angezeigt werden."));
$Form->set_InputReadonly($FormularName,$InputName_Limit,false);
$Form->set_InputOrder($FormularName,$InputName_Limit,3);
$Form->set_InputLabel($FormularName,$InputName_Limit,"");
$Form->set_InputSize($FormularName,$InputName_Limit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Limit,false);
//add data
$Form->add_InputOption($FormularName,$InputName_Limit,"10","10 Mails");
$Form->add_InputOption($FormularName,$InputName_Limit,"15","15 Mails");
$Form->add_InputOption($FormularName,$InputName_Limit,"20","20 Mails");
$Form->add_InputOption($FormularName,$InputName_Limit,"25","25 Mails");
$Form->add_InputOption($FormularName,$InputName_Limit,"50","50 Mails");
$Form->add_InputOption($FormularName,$InputName_Limit,"75","75 Mails");
$Form->add_InputOption($FormularName,$InputName_Limit,"100","100 Mails");

//nur bounces
$Form->new_Input($FormularName,$InputName_Bounce,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Bounce," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Bounce,$$InputName_Bounce);
$Form->set_InputStyleClass($FormularName,$InputName_Bounce,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Bounce,48,48);
$Form->set_InputDesc($FormularName,$InputName_Bounce,___("Nur Bouncemails anzeigen"));
$Form->set_InputReadonly($FormularName,$InputName_Bounce,false);
$Form->set_InputOrder($FormularName,$InputName_Bounce,7);
$Form->set_InputLabel($FormularName,$InputName_Bounce,"");

//bounce method, type: header, body, body&header
$Form->new_Input($FormularName,$InputName_BounceType,"select", "");
$Form->set_InputJS($FormularName,$InputName_BounceType," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_BounceType,$$InputName_BounceType);
$Form->set_InputStyleClass($FormularName,$InputName_BounceType,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_BounceType,___("Was soll durchsucht werden?"));
$Form->set_InputReadonly($FormularName,$InputName_BounceType,false);
$Form->set_InputOrder($FormularName,$InputName_BounceType,6);
$Form->set_InputLabel($FormularName,$InputName_BounceType,"");
$Form->set_InputSize($FormularName,$InputName_BounceType,0,1);
$Form->set_InputMultiple($FormularName,$InputName_BounceType,false);
$Form->add_InputOption($FormularName,$InputName_BounceType,"header",___("nur E-MailHeader"));
$Form->add_InputOption($FormularName,$InputName_BounceType,"body",___("nur E-Mail-Body"));
$Form->add_InputOption($FormularName,$InputName_BounceType,"headerbody","E-Mail-Header und -Body");

//to adresse filtern nach return path fuer den host
	$Form->new_Input($FormularName,$InputName_FilterTo,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_FilterTo," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_FilterTo,$$InputName_FilterTo);
	$Form->set_InputStyleClass($FormularName,$InputName_FilterTo,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_FilterTo,48,48);
	$Form->set_InputDesc($FormularName,$InputName_FilterTo,___("Nur E-Mails an die Fehleradresse anzeigen"));
	$Form->set_InputReadonly($FormularName,$InputName_FilterTo,false);
	$Form->set_InputOrder($FormularName,$InputName_FilterTo,4);
	$Form->set_InputLabel($FormularName,$InputName_FilterTo,"");
//Preselect TO from ReturnPath from SMTP Servers
$Form->new_Input($FormularName,$InputName_FilterToSMTPReturnPath,"select", "");
$Form->set_InputJS($FormularName,$InputName_FilterToSMTPReturnPath," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_FilterToSMTPReturnPath,$$InputName_FilterToSMTPReturnPath);
$Form->set_InputStyleClass($FormularName,$InputName_FilterToSMTPReturnPath,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_FilterToSMTPReturnPath,1,1);
$Form->set_InputDesc($FormularName,$InputName_FilterToSMTPReturnPath,___("Return-Path Adresse auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_FilterToSMTPReturnPath,false);
$Form->set_InputOrder($FormularName,$InputName_FilterToSMTPReturnPath,5);
$Form->set_InputLabel($FormularName,$InputName_FilterToSMTPReturnPath,"");
$Form->set_InputMultiple($FormularName,$InputName_FilterToSMTPReturnPath,false);
//smtp hosts
$HOST_=$HOSTS->getHost(0,Array("type"=>"smtp"));//id,filter
$hcr=count($HOST_);
for ($hccr=0; $hccr<$hcr; $hccr++)
{
		$Form->add_InputOption($FormularName,$InputName_FilterToSMTPReturnPath,$HOST_[$hccr]['return_mail'],display($HOST_[$hccr]['return_mail']),display($HOST_[$hccr]['name']),"background-color:#aacc00;");
		if ($HOST_[$hccr]['sender_email'] != $HOST_[$hccr]['return_mail']) {
			$Form->add_InputOption($FormularName,$InputName_FilterToSMTPReturnPath,$HOST_[$hccr]['sender_email'],display($HOST_[$hccr]['sender_email']),display($HOST_[$hccr]['name']),"background-color:#ffff00;");
		}
		if ($HOST_[$hccr]['sender_email'] != $HOST_[$hccr]['return_mail'] &&
			$HOST_[$hccr]['return_mail'] != $HOST_[$hccr]['reply_to']		
			) {
			$Form->add_InputOption($FormularName,$InputName_FilterToSMTPReturnPath,$HOST_[$hccr]['reply_to'],display($HOST_[$hccr]['reply_to']),display($HOST_[$hccr]['name']),"background-color:#ffcc00;");
		}
}


//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Verbinden und e-Mails abrufen"));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");

//a reset button
$Form->new_Input($FormularName,$InputName_Reset,"reset","Reset");
$Form->set_InputStyleClass($FormularName,$InputName_Reset,"mFormReset","mFormResetFocus");
$Form->set_InputDesc($FormularName,$InputName_Reset,"Reset");
$Form->set_InputReadonly($FormularName,$InputName_Reset,false);
$Form->set_InputOrder($FormularName,$InputName_Reset,999);
$Form->set_InputLabel($FormularName,$InputName_Reset,"");
?>