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
$FormularName="adm_set";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormType($FormularName,"multipart/form-data");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("Systemeinstellungen ändern"));
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
//////////////////
//add inputfields and buttons....
//////////////////

//Style
$Form->new_Input($FormularName,$InputName_Style,"select", "");
$Form->set_InputJS($FormularName,$InputName_Style," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Style,$$InputName_Style);
$Form->set_InputStyleClass($FormularName,$InputName_Style,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Style,___("Layout / Style"));
$Form->set_InputReadonly($FormularName,$InputName_Style,false);
$Form->set_InputOrder($FormularName,$InputName_Style,3);
$Form->set_InputLabel($FormularName,$InputName_Style,"");
$Form->set_InputSize($FormularName,$InputName_Style,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Style,false);
//add Data
$css_c=count($CSSDirs);
for ($css_cc=0; $css_cc < $css_c; $css_cc++) {
	$Form->add_InputOption($FormularName,$InputName_Style,$CSSDirs[$css_cc]['dir'],$CSSDirs[$css_cc]['name']);
}

//lang
$Form->new_Input($FormularName,$InputName_Lang,"select", "");
$Form->set_InputJS($FormularName,$InputName_Lang," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Lang,$$InputName_Lang);
$Form->set_InputStyleClass($FormularName,$InputName_Lang,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Lang,___("Sprache"));
$Form->set_InputReadonly($FormularName,$InputName_Lang,false);
$Form->set_InputOrder($FormularName,$InputName_Lang,2);
$Form->set_InputLabel($FormularName,$InputName_Lang,"");
$Form->set_InputSize($FormularName,$InputName_Lang,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Lang,false);
//add Data
$lc=count($LANGUAGES['lang']);
for ($lcc=0;$lcc<$lc;$lcc++) {
	$Form->add_InputOption($FormularName,$InputName_Lang,$LANGUAGES['lang'][$lcc],$LANGUAGES['text'][$lcc]);
}

//emailcheck...intern
$Form->new_Input($FormularName,$InputName_ECheckIntern,"select", "");
$Form->set_InputJS($FormularName,$InputName_ECheckIntern," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ECheckIntern,$$InputName_ECheckIntern);
$Form->set_InputStyleClass($FormularName,$InputName_ECheckIntern,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ECheckIntern,"");
$Form->set_InputReadonly($FormularName,$InputName_ECheckIntern,false);
$Form->set_InputOrder($FormularName,$InputName_ECheckIntern,3);
$Form->set_InputLabel($FormularName,$InputName_ECheckIntern,"");
$Form->set_InputSize($FormularName,$InputName_ECheckIntern,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ECheckIntern,false);
//add Data
$sc=count($EMAILCHECK['intern']);
for ($scc=0; $scc<$sc; $scc++) {
	$Form->add_InputOption($FormularName,$InputName_ECheckIntern,$scc,$EMAILCHECK['intern'][$scc]);
}

//emailcheck...subscribe
$Form->new_Input($FormularName,$InputName_ECheckSubscribe,"select", "");
$Form->set_InputJS($FormularName,$InputName_ECheckSubscribe," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ECheckSubscribe,$$InputName_ECheckSubscribe);
$Form->set_InputStyleClass($FormularName,$InputName_ECheckSubscribe,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ECheckSubscribe,"");
$Form->set_InputReadonly($FormularName,$InputName_ECheckSubscribe,false);
$Form->set_InputOrder($FormularName,$InputName_ECheckSubscribe,8);
$Form->set_InputLabel($FormularName,$InputName_ECheckSubscribe,"");
$Form->set_InputSize($FormularName,$InputName_ECheckSubscribe,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ECheckSubscribe,false);
//add Data
$sc=count($EMAILCHECK['subscribe']);
for ($scc=1; $scc<=$sc; $scc++)//0
{
	$Form->add_InputOption($FormularName,$InputName_ECheckSubscribe,$scc,$EMAILCHECK['subscribe'][$scc]);
}

//emailcheck...sendit
$Form->new_Input($FormularName,$InputName_ECheckSendit,"select", "");
$Form->set_InputJS($FormularName,$InputName_ECheckSendit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ECheckSendit,$$InputName_ECheckSendit);
$Form->set_InputStyleClass($FormularName,$InputName_ECheckSendit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ECheckSendit,"");
$Form->set_InputReadonly($FormularName,$InputName_ECheckSendit,false);
$Form->set_InputOrder($FormularName,$InputName_ECheckSendit,4);
$Form->set_InputLabel($FormularName,$InputName_ECheckSendit,"");
$Form->set_InputSize($FormularName,$InputName_ECheckSendit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ECheckSendit,false);
//add Data
$sc=count($EMAILCHECK['sendit']);
for ($scc=1; $scc<=$sc; $scc++)//0
{
	$Form->add_InputOption($FormularName,$InputName_ECheckSendit,$scc,$EMAILCHECK['sendit'][$scc]);
}

//emailcheck...checkit
$Form->new_Input($FormularName,$InputName_ECheckCheckit,"select", "");
$Form->set_InputJS($FormularName,$InputName_ECheckCheckit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ECheckCheckit,$$InputName_ECheckCheckit);
$Form->set_InputStyleClass($FormularName,$InputName_ECheckCheckit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ECheckCheckit,"");
$Form->set_InputReadonly($FormularName,$InputName_ECheckCheckit,false);
$Form->set_InputOrder($FormularName,$InputName_ECheckCheckit,16);
$Form->set_InputLabel($FormularName,$InputName_ECheckCheckit,"");
$Form->set_InputSize($FormularName,$InputName_ECheckCheckit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ECheckCheckit,false);
//add Data
$sc=count($EMAILCHECK['checkit']);
for ($scc=1; $scc<=$sc; $scc++)//0
{
	$Form->add_InputOption($FormularName,$InputName_ECheckCheckit,$scc,$EMAILCHECK['checkit'][$scc]);
}


//notify
$Form->new_Input($FormularName,$InputName_NotifySubscribe,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_NotifySubscribe," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_NotifySubscribe,$$InputName_NotifySubscribe);
$Form->set_InputStyleClass($FormularName,$InputName_NotifySubscribe,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_NotifySubscribe,48,256);
$Form->set_InputDesc($FormularName,$InputName_NotifySubscribe,___("Nachricht bei Anmeldung"));
$Form->set_InputReadonly($FormularName,$InputName_NotifySubscribe,false);
$Form->set_InputOrder($FormularName,$InputName_NotifySubscribe,9);
$Form->set_InputLabel($FormularName,$InputName_NotifySubscribe,"");

//notify
$Form->new_Input($FormularName,$InputName_NotifyUnsubscribe,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_NotifyUnsubscribe," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_NotifyUnsubscribe,$$InputName_NotifyUnsubscribe);
$Form->set_InputStyleClass($FormularName,$InputName_NotifyUnsubscribe,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_NotifyUnsubscribe,48,256);
$Form->set_InputDesc($FormularName,$InputName_NotifyUnsubscribe,___("Nachricht bei Abmeldung"));
$Form->set_InputReadonly($FormularName,$InputName_NotifyUnsubscribe,false);
$Form->set_InputOrder($FormularName,$InputName_NotifyUnsubscribe,10);
$Form->set_InputLabel($FormularName,$InputName_NotifyUnsubscribe,"");

//notify_mail etc
$Form->new_Input($FormularName,$InputName_NotifyMail,"text", $$InputName_NotifyMail);
$Form->set_InputJS($FormularName,$InputName_NotifyMail," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_NotifyMail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_NotifyMail,48,256);
$Form->set_InputDesc($FormularName,$InputName_NotifyMail,___("E-Mail-Adresse für Benachrichtigungen"));
$Form->set_InputReadonly($FormularName,$InputName_NotifyMail,false);
$Form->set_InputOrder($FormularName,$InputName_NotifyMail,11);
$Form->set_InputLabel($FormularName,$InputName_NotifyMail,"");

//Retries
$Form->new_Input($FormularName,$InputName_MaxRetry,"select", "");
$Form->set_InputJS($FormularName,$InputName_MaxRetry," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_MaxRetry,$$InputName_MaxRetry);
$Form->set_InputStyleClass($FormularName,$InputName_MaxRetry,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_MaxRetry,___("Maximale Anzahl Sendeversuche pro Adresse"));
$Form->set_InputReadonly($FormularName,$InputName_MaxRetry,false);
$Form->set_InputOrder($FormularName,$InputName_MaxRetry,5);
$Form->set_InputLabel($FormularName,$InputName_MaxRetry,"");
$Form->set_InputSize($FormularName,$InputName_MaxRetry,0,1);
$Form->set_InputMultiple($FormularName,$InputName_MaxRetry,false);
//add Data
$rt=10;
for ($rtc=1; $rtc<=$rt; $rtc=$rtc+2)
{
	$Form->add_InputOption($FormularName,$InputName_MaxRetry,$rtc,$rtc);
}

//check version and show news
$Form->new_Input($FormularName,$InputName_CheckVersion,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_CheckVersion," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_CheckVersion,$$InputName_CheckVersion);
$Form->set_InputStyleClass($FormularName,$InputName_CheckVersion,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_CheckVersion,48,256);
$Form->set_InputDesc($FormularName,$InputName_CheckVersion,___("Aktuelle Version und News auf der Startseite einblenden"));
$Form->set_InputReadonly($FormularName,$InputName_CheckVersion,false);
$Form->set_InputOrder($FormularName,$InputName_CheckVersion,1);
$Form->set_InputLabel($FormularName,$InputName_CheckVersion,"");

//Select existing Trackimage
$Form->new_Input($FormularName,$InputName_TrackImageExisting,"select", "");
$Form->set_InputJS($FormularName,$InputName_TrackImageExisting," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_TrackImageExisting,basename($C[0]['track_image']));
$Form->set_InputStyleClass($FormularName,$InputName_TrackImageExisting,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_TrackImageExisting,___("Blind-/Tracking-Bild auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_TrackImageExisting,false);
$Form->set_InputOrder($FormularName,$InputName_TrackImageExisting,6);
$Form->set_InputLabel($FormularName,$InputName_TrackImageExisting,"");
$Form->set_InputSize($FormularName,$InputName_TrackImageExisting,0,1);
$Form->set_InputMultiple($FormularName,$InputName_TrackImageExisting,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_TrackImageExisting,"_blank","-- BLANK --");
$TrackImg_Files=getFiles($tm_nlimgpath) ;
foreach ($TrackImg_Files as $field) {
	$btsort[]=$field['name'];
}
@array_multisort($btsort, SORT_ASC, $TrackImg_Files, SORT_ASC);
$ic= count($TrackImg_Files);
for ($icc=0; $icc < $ic; $icc++) {
	if ($TrackImg_Files[$icc]['name']!=".htaccess" && $TrackImg_Files[$icc]['name']!="index.php" && $TrackImg_Files[$icc]['name']!="index.html") {
		$Form->add_InputOption($FormularName,$InputName_TrackImageExisting,$TrackImg_Files[$icc]['name'],display($TrackImg_Files[$icc]['name']));
	}
}

//upload new trackingimage
$Form->new_Input($FormularName,$InputName_TrackImageNew,"file", "");
$Form->set_InputJS($FormularName,$InputName_TrackImageNew," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_TrackImageNew,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_TrackImageNew,48,48);
$Form->set_InputDesc($FormularName,$InputName_TrackImageNew,___("neues Bild hochladen"));
$Form->set_InputReadonly($FormularName,$InputName_TrackImageNew,false);
$Form->set_InputOrder($FormularName,$InputName_TrackImageNew,7);
$Form->set_InputLabel($FormularName,$InputName_TrackImageNew,"");

//rcpt_name etc
$Form->new_Input($FormularName,$InputName_RCPTName,"text", $$InputName_RCPTName);
$Form->set_InputJS($FormularName,$InputName_RCPTName," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_RCPTName,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_RCPTName,48,256);
$Form->set_InputDesc($FormularName,$InputName_RCPTName,___("Erscheint als Empfängername in der E-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_RCPTName,false);
$Form->set_InputOrder($FormularName,$InputName_RCPTName,2);
$Form->set_InputLabel($FormularName,$InputName_RCPTName,"");

//capctha unsub
$Form->new_Input($FormularName,$InputName_UnsubUseCaptcha,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_UnsubUseCaptcha," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_UnsubUseCaptcha,$$InputName_UnsubUseCaptcha);
$Form->set_InputStyleClass($FormularName,$InputName_UnsubUseCaptcha,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_UnsubUseCaptcha,48,256);
$Form->set_InputDesc($FormularName,$InputName_UnsubUseCaptcha,___("Captcha im Abmeldeformular"));
$Form->set_InputReadonly($FormularName,$InputName_UnsubUseCaptcha,false);
$Form->set_InputOrder($FormularName,$InputName_UnsubUseCaptcha,12);
$Form->set_InputLabel($FormularName,$InputName_UnsubUseCaptcha,"");

//DigitsCaptcha
$Form->new_Input($FormularName,$InputName_UnsubDigitsCaptcha,"select", "");
$Form->set_InputJS($FormularName,$InputName_UnsubDigitsCaptcha," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_UnsubDigitsCaptcha,$$InputName_UnsubDigitsCaptcha);
$Form->set_InputStyleClass($FormularName,$InputName_UnsubDigitsCaptcha,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_UnsubDigitsCaptcha,___("Ziffern"));
$Form->set_InputReadonly($FormularName,$InputName_UnsubDigitsCaptcha,false);
$Form->set_InputOrder($FormularName,$InputName_UnsubDigitsCaptcha,13);
$Form->set_InputLabel($FormularName,$InputName_UnsubDigitsCaptcha,"");
$Form->set_InputSize($FormularName,$InputName_UnsubDigitsCaptcha,0,1);
$Form->set_InputMultiple($FormularName,$InputName_UnsubDigitsCaptcha,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,4,"4");
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,5,"5");
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,6,"6");
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,7,"7");
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,8,"8");
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,9,"9");
$Form->add_InputOption($FormularName,$InputName_UnsubDigitsCaptcha,10,"10");

//unsub sendmail
$Form->new_Input($FormularName,$InputName_UnsubSendMail,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_UnsubSendMail," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_UnsubSendMail,$$InputName_UnsubSendMail);
$Form->set_InputStyleClass($FormularName,$InputName_UnsubSendMail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_UnsubSendMail,48,256);
$Form->set_InputDesc($FormularName,$InputName_UnsubSendMail,___("Bestätigungsmail senden"));
$Form->set_InputReadonly($FormularName,$InputName_UnsubSendMail,false);
$Form->set_InputOrder($FormularName,$InputName_UnsubSendMail,12);
$Form->set_InputLabel($FormularName,$InputName_UnsubSendMail,"");

//unsub action
$Form->new_Input($FormularName,$InputName_UnsubAction,"select", "");
$Form->set_InputJS($FormularName,$InputName_UnsubAction," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_UnsubAction,$$InputName_UnsubAction);
$Form->set_InputStyleClass($FormularName,$InputName_UnsubAction,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_UnsubAction,___("Aktion"));
$Form->set_InputReadonly($FormularName,$InputName_UnsubAction,false);
$Form->set_InputOrder($FormularName,$InputName_UnsubAction,13);
$Form->set_InputLabel($FormularName,$InputName_UnsubAction,"");
$Form->set_InputSize($FormularName,$InputName_UnsubAction,0,1);
$Form->set_InputMultiple($FormularName,$InputName_UnsubAction,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_UnsubAction,"unsubscribe",___("Abmelden"));
$Form->add_InputOption($FormularName,$InputName_UnsubAction,"blacklist",___("Blacklist/Robinsonliste"));
$Form->add_InputOption($FormularName,$InputName_UnsubAction,"delete",___("Löschen"));
$Form->add_InputOption($FormularName,$InputName_UnsubAction,"blacklist_delete",___("Blacklist/Robinsonliste & Löschen"));

//unsub hosts
$Form->new_Input($FormularName,$InputName_UnsubHost,"select", "");
$Form->set_InputJS($FormularName,$InputName_UnsubHost," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_UnsubHost,$$InputName_UnsubHost);
$Form->set_InputStyleClass($FormularName,$InputName_UnsubHost,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_UnsubHost,1,1);
$Form->set_InputDesc($FormularName,$InputName_UnsubHost,___("SMTP Server auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_UnsubHost,false);
$Form->set_InputOrder($FormularName,$InputName_UnsubHost,13);
$Form->set_InputLabel($FormularName,$InputName_UnsubHost,"");
$Form->set_InputMultiple($FormularName,$InputName_UnsubHost,false);
#Hostliste....
//smtp hosts
$HOSTS=new tm_Host();
$HOST_=$HOSTS->getHost(0,Array("aktiv"=>1, "type"=>"smtp"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_UnsubHost,$HOST_[$hccg]['id'],display($HOST_[$hccg]['name']));
}


//CheckitLimit
$Form->new_Input($FormularName,$InputName_CheckitLimit,"select", "");
$Form->set_InputJS($FormularName,$InputName_CheckitLimit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_CheckitLimit,$$InputName_CheckitLimit);
$Form->set_InputStyleClass($FormularName,$InputName_CheckitLimit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_CheckitLimit,___("Limit"));
$Form->set_InputReadonly($FormularName,$InputName_CheckitLimit,false);
$Form->set_InputOrder($FormularName,$InputName_CheckitLimit,13);
$Form->set_InputLabel($FormularName,$InputName_CheckitLimit,"");
$Form->set_InputSize($FormularName,$InputName_CheckitLimit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_CheckitLimit,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,1,"1");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,10,"10");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,25,"25");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,50,"50");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,100,"100");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,250,"250");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,500,"500");
$Form->add_InputOption($FormularName,$InputName_CheckitLimit,1000,"1000");

//BounceitLimit
$Form->new_Input($FormularName,$InputName_BounceitLimit,"select", "");
$Form->set_InputJS($FormularName,$InputName_BounceitLimit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_BounceitLimit,$$InputName_BounceitLimit);
$Form->set_InputStyleClass($FormularName,$InputName_BounceitLimit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_BounceitLimit,___("Limit"));
$Form->set_InputReadonly($FormularName,$InputName_BounceitLimit,false);
$Form->set_InputOrder($FormularName,$InputName_BounceitLimit,18);
$Form->set_InputLabel($FormularName,$InputName_BounceitLimit,"");
$Form->set_InputSize($FormularName,$InputName_BounceitLimit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_BounceitLimit,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,1,"1");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,10,"10");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,25,"25");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,50,"50");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,100,"100");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,250,"250");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,500,"500");
$Form->add_InputOption($FormularName,$InputName_BounceitLimit,1000,"1000");


//checkit_mail_from etc
$Form->new_Input($FormularName,$InputName_CheckitFromEmail,"text", $$InputName_CheckitFromEmail);
$Form->set_InputJS($FormularName,$InputName_CheckitFromEmail," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_CheckitFromEmail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_CheckitFromEmail,48,256);
$Form->set_InputDesc($FormularName,$InputName_CheckitFromEmail,___("E-Mail-Adresse für Prüfung (Validate/MAIL FROM:)"));
$Form->set_InputReadonly($FormularName,$InputName_CheckitFromEmail,false);
$Form->set_InputOrder($FormularName,$InputName_CheckitFromEmail,16);
$Form->set_InputLabel($FormularName,$InputName_CheckitFromEmail,"");

//checkit reset error
$Form->new_Input($FormularName,$InputName_CheckitAdrResetError,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_CheckitAdrResetError," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_CheckitAdrResetError,$$InputName_CheckitAdrResetError);
$Form->set_InputStyleClass($FormularName,$InputName_CheckitAdrResetError,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_CheckitAdrResetError,48,256);
$Form->set_InputDesc($FormularName,$InputName_CheckitAdrResetError,___("Wenn OK, Fehler zurücksetzen"));
$Form->set_InputReadonly($FormularName,$InputName_CheckitAdrResetError,false);
$Form->set_InputOrder($FormularName,$InputName_CheckitAdrResetError,17);
$Form->set_InputLabel($FormularName,$InputName_CheckitAdrResetError,"");

//checkit reset status
$Form->new_Input($FormularName,$InputName_CheckitAdrResetStatus,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_CheckitAdrResetStatus," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_CheckitAdrResetStatus,$$InputName_CheckitAdrResetStatus);
$Form->set_InputStyleClass($FormularName,$InputName_CheckitAdrResetStatus,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_CheckitAdrResetStatus,48,256);
$Form->set_InputDesc($FormularName,$InputName_CheckitAdrResetStatus,___("Wenn OK, Status zurücksetzen"));
$Form->set_InputReadonly($FormularName,$InputName_CheckitAdrResetStatus,false);
$Form->set_InputOrder($FormularName,$InputName_CheckitAdrResetStatus,18);
$Form->set_InputLabel($FormularName,$InputName_CheckitAdrResetStatus,"");

//Bounce HOST
$Form->new_Input($FormularName,$InputName_BounceitHost,"select", "");
$Form->set_InputJS($FormularName,$InputName_BounceitHost," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_BounceitHost,$$InputName_BounceitHost);
$Form->set_InputStyleClass($FormularName,$InputName_BounceitHost,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_BounceitHost,1,1);
$Form->set_InputDesc($FormularName,$InputName_BounceitHost,___("Host auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_BounceitHost,false);
$Form->set_InputOrder($FormularName,$InputName_BounceitHost,20);
$Form->set_InputLabel($FormularName,$InputName_BounceitHost,"");
$Form->set_InputMultiple($FormularName,$InputName_BounceitHost,false);
#Hostliste....
//pop3 hosts
$HOST_=$HOSTS->getHost("",Array("aktiv"=>1, "type"=>"imap"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_BounceitHost,$HOST_[$hccg]['id'],$HOST_[$hccg]['name']);
}
//imap hosts
$HOST_=$HOSTS->getHost("",Array("aktiv"=>1, "type"=>"pop3"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_BounceitHost,$HOST_[$hccg]['id'],$HOST_[$hccg]['name']);
}

//Aktion Bounce
$Form->new_Input($FormularName,$InputName_BounceitAction,"select", "");
$Form->set_InputDefault($FormularName,$InputName_BounceitAction,$$InputName_BounceitAction);
$Form->set_InputStyleClass($FormularName,$InputName_BounceitAction,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_BounceitAction,___("Aktion ausführen"));
$Form->set_InputReadonly($FormularName,$InputName_BounceitAction,false);
$Form->set_InputOrder($FormularName,$InputName_BounceitAction,22);
$Form->set_InputLabel($FormularName,$InputName_BounceitAction,"");
$Form->set_InputSize($FormularName,$InputName_BounceitAction,0,1);
$Form->set_InputMultiple($FormularName,$InputName_BounceitAction,false);
$Form->add_InputOption($FormularName,$InputName_BounceitAction,"auto",___("Adressen automatisch bearbeiten"));
$Form->add_InputOption($FormularName,$InputName_BounceitAction,"error",___("Adressen als Fehlerhaft markieren"));
$Form->add_InputOption($FormularName,$InputName_BounceitAction,"unsubscribe",___("Adressen abmelden und deaktivieren"));
$Form->add_InputOption($FormularName,$InputName_BounceitAction,"aktiv",___("Adressen deaktivieren"));
$Form->add_InputOption($FormularName,$InputName_BounceitAction,"delete",___("Adressen löschen"));

//bounce filter to?
$Form->new_Input($FormularName,$InputName_BounceitFilterTo,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_BounceitFilterTo," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_BounceitFilterTo,$$InputName_BounceitFilterTo);
$Form->set_InputStyleClass($FormularName,$InputName_BounceitFilterTo,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_BounceitFilterTo,48,256);
$Form->set_InputDesc($FormularName,$InputName_BounceitFilterTo,___("nur Mails an TO: bearbeiten"));
$Form->set_InputReadonly($FormularName,$InputName_BounceitFilterTo,false);
$Form->set_InputOrder($FormularName,$InputName_BounceitFilterTo,24);
$Form->set_InputLabel($FormularName,$InputName_BounceitFilterTo,"");

//bounce email to adr
$Form->new_Input($FormularName,$InputName_BounceitFilterToEmail,"text", $$InputName_BounceitFilterToEmail);
$Form->set_InputJS($FormularName,$InputName_BounceitFilterToEmail," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_BounceitFilterToEmail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_BounceitFilterToEmail,48,256);
$Form->set_InputDesc($FormularName,$InputName_BounceitFilterToEmail,___("Filter E-Mail-Adresse für Bouncemanagement)"));
$Form->set_InputReadonly($FormularName,$InputName_BounceitFilterToEmail,false);
$Form->set_InputOrder($FormularName,$InputName_BounceitFilterToEmail,25);
$Form->set_InputLabel($FormularName,$InputName_BounceitFilterToEmail,"");

//bounce method, type: header, body, body&header
$Form->new_Input($FormularName,$InputName_BounceitSearch,"select", "");
$Form->set_InputJS($FormularName,$InputName_BounceitSearch," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_BounceitSearch,$$InputName_BounceitSearch);
$Form->set_InputStyleClass($FormularName,$InputName_BounceitSearch,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_BounceitSearch,___("Bounce: suche nach Adressen"));
$Form->set_InputReadonly($FormularName,$InputName_BounceitSearch,false);
$Form->set_InputOrder($FormularName,$InputName_BounceitSearch,23);
$Form->set_InputLabel($FormularName,$InputName_BounceitSearch,"");
$Form->set_InputSize($FormularName,$InputName_BounceitSearch,0,1);
$Form->set_InputMultiple($FormularName,$InputName_BounceitSearch,false);
$Form->add_InputOption($FormularName,$InputName_BounceitSearch,"header",___("nur E-MailHeader"));
$Form->add_InputOption($FormularName,$InputName_BounceitSearch,"body",___("nur E-Mail-Body"));
$Form->add_InputOption($FormularName,$InputName_BounceitSearch,"headerbody","E-Mail-Header und -Body");

################
# proof
################

//proof enable
$Form->new_Input($FormularName,$InputName_Proof,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Proof," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Proof,$$InputName_Proof);
$Form->set_InputStyleClass($FormularName,$InputName_Proof,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Proof,48,256);
$Form->set_InputDesc($FormularName,$InputName_Proof,___("Proofing aktiviert"));
$Form->set_InputReadonly($FormularName,$InputName_Proof,false);
$Form->set_InputOrder($FormularName,$InputName_Proof,40);
$Form->set_InputLabel($FormularName,$InputName_Proof,"");

//proof url
$Form->new_Input($FormularName,$InputName_ProofURL,"text", $$InputName_ProofURL);
$Form->set_InputJS($FormularName,$InputName_ProofURL," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputStyleClass($FormularName,$InputName_ProofURL,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ProofURL,48,255);
$Form->set_InputDesc($FormularName,$InputName_ProofURL,___("Proofing URL"));
$Form->set_InputReadonly($FormularName,$InputName_ProofURL,false);
$Form->set_InputOrder($FormularName,$InputName_ProofURL,41);
$Form->set_InputLabel($FormularName,$InputName_ProofURL,"");

//Proof triger
$Form->new_Input($FormularName,$InputName_ProofTrigger,"select", "");
$Form->set_InputJS($FormularName,$InputName_ProofTrigger," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ProofTrigger,$$InputName_ProofTrigger);
$Form->set_InputStyleClass($FormularName,$InputName_ProofTrigger,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ProofTrigger,___("Proofing Trigger"));
$Form->set_InputReadonly($FormularName,$InputName_ProofTrigger,false);
$Form->set_InputOrder($FormularName,$InputName_ProofTrigger,42);
$Form->set_InputLabel($FormularName,$InputName_ProofTrigger,"");
$Form->set_InputSize($FormularName,$InputName_ProofTrigger,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ProofTrigger,false);

$Form->add_InputOption($FormularName,$InputName_ProofTrigger,1,"1");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,10,"10");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,50,"50");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,100,"100");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,250,"250");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,500,"500");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,1000,"1000");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,2500,"2500");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,5000,"5000");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,7500,"7500");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,10000,"10000");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,20000,"20000");
$Form->add_InputOption($FormularName,$InputName_ProofTrigger,25000,"25000");

//Proof percent
$Form->new_Input($FormularName,$InputName_ProofPc,"select", "");
$Form->set_InputJS($FormularName,$InputName_ProofPc," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ProofPc,$$InputName_ProofPc);
$Form->set_InputStyleClass($FormularName,$InputName_ProofPc,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ProofPc,___("Proofing Anteil"));
$Form->set_InputReadonly($FormularName,$InputName_ProofPc,false);
$Form->set_InputOrder($FormularName,$InputName_ProofPc,43);
$Form->set_InputLabel($FormularName,$InputName_ProofPc,"");
$Form->set_InputSize($FormularName,$InputName_ProofPc,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ProofPc,false);

$Form->add_InputOption($FormularName,$InputName_ProofPc,1,"1");
$Form->add_InputOption($FormularName,$InputName_ProofPc,5,"5");
$Form->add_InputOption($FormularName,$InputName_ProofPc,10,"10");
$Form->add_InputOption($FormularName,$InputName_ProofPc,20,"20");
$Form->add_InputOption($FormularName,$InputName_ProofPc,25,"25");
$Form->add_InputOption($FormularName,$InputName_ProofPc,30,"30");
$Form->add_InputOption($FormularName,$InputName_ProofPc,40,"40");
$Form->add_InputOption($FormularName,$InputName_ProofPc,50,"50");
$Form->add_InputOption($FormularName,$InputName_ProofPc,75,"75");
$Form->add_InputOption($FormularName,$InputName_ProofPc,100,"100 (alle)");

################
# submit / reset
################


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