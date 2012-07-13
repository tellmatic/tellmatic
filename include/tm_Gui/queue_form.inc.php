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

$InputName_Submit="submit";
$InputName_Reset="reset";

//Form
$Form=new tm_SimpleForm();
$FormularName="queue_new";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("neuen Q erstellen"));
//variable content aus menu als hidden field!
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
//////////////////
//add inputfields and buttons....
//////////////////

//NL
$Form->new_Input($FormularName,$InputName_NL,"select", "");
$Form->set_InputJS($FormularName,$InputName_NL," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_NL,$nl_id);
$Form->set_InputStyleClass($FormularName,$InputName_NL,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_NL,___("Newsletter wählen"));
$Form->set_InputReadonly($FormularName,$InputName_NL,false);
$Form->set_InputOrder($FormularName,$InputName_NL,1);
$Form->set_InputLabel($FormularName,$InputName_NL,"");
$Form->set_InputSize($FormularName,$InputName_NL,0,5);
$Form->set_InputMultiple($FormularName,$InputName_NL,false);
//add Data
//nur aktive, keine templates
$NL=$NEWSLETTER->getNL(0,0,0,0,0,$sortIndex="id",$sortType=1,Array("aktiv"=>1, "is_template"=>0));
$nc=count($NL);
for ($ncc=0; $ncc<$nc; $ncc++)
{
	//nur nl mit existierenden templates f. html/textparts
	if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html") 
		&& file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt")) {
		$NLOpt=display($NL[$ncc]['subject']);
		if ($NL[$ncc]['massmail']==1) {
			$NLOpt .="   ".___("(Massenmailing)");
		} else {
			$NLOpt .="   ".___("(personalisiert)");
		}
		$Form->add_InputOption($FormularName,$InputName_NL,$NL[$ncc]['id'],$NLOpt);
	}
}


//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Gruppen wählen, STRG/CTRL gedrückt halten und klicken f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,2);
$Form->set_InputLabel($FormularName,$InputName_Group,"");
$Form->set_InputSize($FormularName,$InputName_Group,0,5);
$Form->set_InputMultiple($FormularName,$InputName_Group,true);
//add Data
$GRP=$ADDRESS->getGroup(0,0,0,1,Array("aktiv"=>1, "prod"=>1));
$acg=count($GRP);
for ($accg=0; $accg<$acg; $accg++)
{
	$valid_adr_c=$ADDRESS->countValidADR($GRP[$accg]['id']);
	if ($valid_adr_c>0) {
		$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],display($GRP[$accg]['name'])." (".$GRP[$accg]['adr_count']." / ".$valid_adr_c." ".___("Adressen").")");
	}
}

//use blacklist?
	$Form->new_Input($FormularName,$InputName_Blacklist,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Blacklist," onChange=\"flash('submit','#ff0000');\"");
	$Form->set_InputDefault($FormularName,$InputName_Blacklist,1);
	$Form->set_InputStyleClass($FormularName,$InputName_Blacklist,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Blacklist,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Blacklist,___("Blacklist prüfen"));
	$Form->set_InputReadonly($FormularName,$InputName_Blacklist,false);
	$Form->set_InputOrder($FormularName,$InputName_Blacklist,6);
	$Form->set_InputLabel($FormularName,$InputName_Blacklist,"");

//Proof
	$Form->new_Input($FormularName,$InputName_Proof,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Proof," onChange=\"flash('submit','#ff0000');\"");
	$Form->set_InputDefault($FormularName,$InputName_Proof,1);
	$Form->set_InputStyleClass($FormularName,$InputName_Proof,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Proof,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Proof,___("Versand automatisch starten / Empfängerliste automatisch aktualisieren"));
	$Form->set_InputReadonly($FormularName,$InputName_Proof,false);
	$Form->set_InputOrder($FormularName,$InputName_Proof,6);
	$Form->set_InputLabel($FormularName,$InputName_Proof,"");

//use_inline_images
	$Form->new_Input($FormularName,$InputName_UseInlineImages,"select", "");
	$Form->set_InputJS($FormularName,$InputName_UseInlineImages," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_UseInlineImages,$$InputName_UseInlineImages);
	$Form->set_InputStyleClass($FormularName,$InputName_UseInlineImages,"mFormSelect","mFormSelectFocus");
	$Form->set_InputDesc($FormularName,$InputName_UseInlineImages,___("Inline Images"));
	$Form->set_InputReadonly($FormularName,$InputName_UseInlineImages,false);
	$Form->set_InputOrder($FormularName,$InputName_UseInlineImages,11);
	$Form->set_InputLabel($FormularName,$InputName_UseInlineImages,"");
	$Form->set_InputSize($FormularName,$InputName_UseInlineImages,0,1);
	$Form->set_InputMultiple($FormularName,$InputName_UseInlineImages,false);
	//add Data
	$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"-1",___("Benutze Newsletter Einstellung"));
	$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"0",___("Aus"));
	$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"1",___("nur lokale Bilder"));
	#$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"2",___("alle Bilder als Inline"));


//send now
	$Form->new_Input($FormularName,$InputName_Send,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Send," onChange=\"flash('submit','#ff0000');\"");
	$Form->set_InputDefault($FormularName,$InputName_Send,1);
	$Form->set_InputStyleClass($FormularName,$InputName_Send,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Send,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Send,___("Versandliste sofort aktivieren"));
	$Form->set_InputReadonly($FormularName,$InputName_Send,false);
	$Form->set_InputOrder($FormularName,$InputName_Send,8);
	$Form->set_InputLabel($FormularName,$InputName_Send,"");

//Autogen
	$Form->new_Input($FormularName,$InputName_Autogen,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Autogen," onChange=\"flash('submit','#ff0000');\"");
	$Form->set_InputDefault($FormularName,$InputName_Autogen,1);
	$Form->set_InputStyleClass($FormularName,$InputName_Autogen,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Autogen,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Autogen,___("Versand automatisch starten / Empfängerliste automatisch aktualisieren"));
	$Form->set_InputReadonly($FormularName,$InputName_Autogen,false);
	$Form->set_InputOrder($FormularName,$InputName_Autogen,7);
	$Form->set_InputLabel($FormularName,$InputName_Autogen,"");

//send_at_date
$Form->new_Input($FormularName,$InputName_SendAt,"text", $send_at_date);
$Form->set_InputJS($FormularName,$InputName_SendAt," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_SendAt,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SendAt,20,30);
$Form->set_InputDesc($FormularName,$InputName_SendAt,___("Datum des terminierten Versands"));
$Form->set_InputReadonly($FormularName,$InputName_SendAt,false);
$Form->set_InputOrder($FormularName,$InputName_SendAt,3);
$Form->set_InputLabel($FormularName,$InputName_SendAt,"");



//send_at_h
$Form->new_Input($FormularName,$InputName_SendAtTimeH,"select", "");
$Form->set_InputJS($FormularName,$InputName_SendAtTimeH," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SendAtTimeH,$$InputName_SendAtTimeH);
$Form->set_InputStyleClass($FormularName,$InputName_SendAtTimeH,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SendAtTimeH,___("Stunde"));
$Form->set_InputReadonly($FormularName,$InputName_SendAtTimeH,false);
$Form->set_InputOrder($FormularName,$InputName_SendAtTimeH,4);
$Form->set_InputLabel($FormularName,$InputName_SendAtTimeH,"");
$Form->set_InputSize($FormularName,$InputName_SendAtTimeH,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SendAtTimeH,false);
//add Data
for ($h=0; $h<24; $h++)
{
		$Form->add_InputOption($FormularName,$InputName_SendAtTimeH,$h,$h." h");
}

//send_at_m
$Form->new_Input($FormularName,$InputName_SendAtTimeM,"select", "");
$Form->set_InputJS($FormularName,$InputName_SendAtTimeM," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SendAtTimeM,$$InputName_SendAtTimeM);
$Form->set_InputStyleClass($FormularName,$InputName_SendAtTimeM,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SendAtTimeM,___("Minute"));
$Form->set_InputReadonly($FormularName,$InputName_SendAtTimeH,false);
$Form->set_InputOrder($FormularName,$InputName_SendAtTimeM,5);
$Form->set_InputLabel($FormularName,$InputName_SendAtTimeM,"");
$Form->set_InputSize($FormularName,$InputName_SendAtTimeM,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SendAtTimeM,false);
//add Data
for ($m=0; $m<60; $m++)
{
		$Form->add_InputOption($FormularName,$InputName_SendAtTimeM,$m,$m."");
}

//offset
$Form->new_Input($FormularName,$InputName_Offset,"text", $$InputName_Offset);
$Form->set_InputJS($FormularName,$InputName_Offset," onChange=\"flash('submit','#ff0000');\" onKeyUp=\"RemoveInvalidChars(this, '[^0-9]');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Offset,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Offset,20,8);
$Form->set_InputDesc($FormularName,$InputName_Offset,___("Offset"));
$Form->set_InputReadonly($FormularName,$InputName_Offset,false);
$Form->set_InputOrder($FormularName,$InputName_Offset,10);
$Form->set_InputLabel($FormularName,$InputName_Offset,"");

//limit
$Form->new_Input($FormularName,$InputName_Limit,"text", $$InputName_Limit);
$Form->set_InputJS($FormularName,$InputName_Limit," onChange=\"flash('submit','#ff0000');\" onKeyUp=\"RemoveInvalidChars(this, '[^1-90]');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Limit,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Limit,20,8);
$Form->set_InputDesc($FormularName,$InputName_Limit,___("Limit"));
$Form->set_InputReadonly($FormularName,$InputName_Limit,false);
$Form->set_InputOrder($FormularName,$InputName_Limit,11);
$Form->set_InputLabel($FormularName,$InputName_Limit,"");

//HOST
$Form->new_Input($FormularName,$InputName_Host,"select", "");
$Form->set_InputJS($FormularName,$InputName_Host," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Host,$$InputName_Host);
$Form->set_InputStyleClass($FormularName,$InputName_Host,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_Host,1,1);
$Form->set_InputDesc($FormularName,$InputName_Host,___("SMTP Server auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_Host,false);
$Form->set_InputOrder($FormularName,$InputName_Host,9);
$Form->set_InputLabel($FormularName,$InputName_Host,"");
$Form->set_InputMultiple($FormularName,$InputName_Host,false);
#Hostliste....
//smtp hosts
$HOST_=$HOSTS->getHost(0,Array("aktiv"=>1, "type"=>"smtp"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_Host,$HOST_[$hccg]['id'],display($HOST_[$hccg]['name']));
}

//ImapHost
$Form->new_Input($FormularName,$InputName_HostIDImap,"select", "");
$Form->set_InputJS($FormularName,$InputName_HostIDImap," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_HostIDImap,$$InputName_HostIDImap);
$Form->set_InputStyleClass($FormularName,$InputName_HostIDImap,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_HostIDImap,1,1);
$Form->set_InputDesc($FormularName,$InputName_HostIDImap,___("IMAP Server auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_HostIDImap,false);
$Form->set_InputOrder($FormularName,$InputName_HostIDImap,9);
$Form->set_InputLabel($FormularName,$InputName_HostIDImap,"");
$Form->set_InputMultiple($FormularName,$InputName_HostIDImap,false);
#Hostliste....
//imap hosts
$HOST_IMAP_=$HOSTS->getHost(0,Array("aktiv"=>1, "type"=>"imap")); 
$hci=count($HOST_IMAP_);
for ($hcci=0; $hcci<$hci; $hcci++)
{
	if (!empty($HOST_IMAP_[$hcci]['imap_folder_sent'])) {
		$Form->add_InputOption($FormularName,$InputName_HostIDImap,$HOST_IMAP_[$hcci]['id'],display($HOST_IMAP_[$hcci]['name']." (".$HOST_IMAP_[$hcci]['imap_folder_sent'].")"));
	}
}

//Save in Imap folder?
	$Form->new_Input($FormularName,$InputName_SaveImap,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_SaveImap," onChange=\"flash('submit','#ff0000');\"");
	$Form->set_InputDefault($FormularName,$InputName_SaveImap,0);
	$Form->set_InputStyleClass($FormularName,$InputName_SaveImap,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_SaveImap,48,256);
	$Form->set_InputDesc($FormularName,$InputName_SaveImap,___("Speichere Kopie ausgehender Mails auf IMAP Server"));
	$Form->set_InputReadonly($FormularName,$InputName_SaveImap,false);
	$Form->set_InputOrder($FormularName,$InputName_SaveImap,7);
	$Form->set_InputLabel($FormularName,$InputName_SaveImap,"");


//touch
$Form->new_Input($FormularName,$InputName_Touch,"select", "");
$Form->set_InputJS($FormularName,$InputName_Touch," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Touch,$$InputName_Touch);
$Form->set_InputStyleClass($FormularName,$InputName_Touch,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_Touch,1,1);
$Form->set_InputDesc($FormularName,$InputName_Touch,___("Touch-Opt-In"));
$Form->set_InputReadonly($FormularName,$InputName_Touch,false);
$Form->set_InputOrder($FormularName,$InputName_Touch,9);
$Form->set_InputLabel($FormularName,$InputName_Touch,"");
$Form->set_InputMultiple($FormularName,$InputName_Touch,false);
//values
$Form->add_InputOption($FormularName,$InputName_Touch,0,___("An alle validen Adressen versenden (OHNE Touch-Opt-In)"));
$Form->add_InputOption($FormularName,$InputName_Touch,1,___("An alle validen Adressen versenden (inkl. Touch-Opt-In)"));
$Form->add_InputOption($FormularName,$InputName_Touch,2,___("An alle Touch-Opt-In Adressen versenden"));


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
$Form->set_InputDesc($FormularName,$InputName_Reset,"Reset");
$Form->set_InputReadonly($FormularName,$InputName_Reset,false);
$Form->set_InputOrder($FormularName,$InputName_Reset,999);
$Form->set_InputLabel($FormularName,$InputName_Reset,"");

#$_MAIN_OUTPUT.= "<br>".tm_icon("car.png",___("Schnelles einfügen"))."&nbsp;".___("Schnelles einfügen");
#$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Fast]['html']."&nbsp;&nbsp;";
/*
$_MAIN_OUTPUT.= "<br>";
$_MAIN_OUTPUT.= tm_icon("control_fastforward.png",___("Offset"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Offset").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Offset]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= tm_icon("control_end.png",___("Limit"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Limit").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Limit]['html']."&nbsp;&nbsp;";
*/

?>