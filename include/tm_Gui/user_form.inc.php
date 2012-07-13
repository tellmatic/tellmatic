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
$FormularName="user";
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("Benutzereinstellungen ändern"));
//variable content aus menu als hidden field!
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
//////////////////
//add inputfields and buttons....
//////////////////

//EMAIL
$Form->new_Input($FormularName,$InputName_EMail,"text",$LOGIN->USER['email']);
$Form->set_InputJS($FormularName,$InputName_EMail," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_EMail,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_EMail,48,256);
$Form->set_InputDesc($FormularName,$InputName_EMail,___("E-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_EMail,false);
$Form->set_InputOrder($FormularName,$InputName_EMail,4);
$Form->set_InputLabel($FormularName,$InputName_EMail,"");

//passwd
$Form->new_Input($FormularName,$InputName_Pass,"password", "");
$Form->set_InputJS($FormularName,$InputName_Pass," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Pass,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Pass,48,256);
$Form->set_InputDesc($FormularName,$InputName_Pass,___("Passwort"));
$Form->set_InputReadonly($FormularName,$InputName_Pass,false);
$Form->set_InputOrder($FormularName,$InputName_Pass,5);
$Form->set_InputLabel($FormularName,$InputName_Pass,"");

//passwd 2
$Form->new_Input($FormularName,$InputName_Pass2,"password", "");
$Form->set_InputJS($FormularName,$InputName_Pass2," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Pass2,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Pass2,48,256);
$Form->set_InputDesc($FormularName,$InputName_Pass2,___("Passwort"));
$Form->set_InputReadonly($FormularName,$InputName_Pass2,false);
$Form->set_InputOrder($FormularName,$InputName_Pass2,5);
$Form->set_InputLabel($FormularName,$InputName_Pass2,"");

//Style
$Form->new_Input($FormularName,$InputName_Style,"select", "");
$Form->set_InputJS($FormularName,$InputName_Style," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Style,$$InputName_Style);
$Form->set_InputStyleClass($FormularName,$InputName_Style,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Style,___("Layout / Style"));
$Form->set_InputReadonly($FormularName,$InputName_Style,false);
$Form->set_InputOrder($FormularName,$InputName_Style,2);
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
$Form->set_InputOrder($FormularName,$InputName_Lang,1);
$Form->set_InputLabel($FormularName,$InputName_Lang,"");
$Form->set_InputSize($FormularName,$InputName_Lang,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Lang,false);
//add Data
$lc=count($LANGUAGES['lang']);
for ($lcc=0;$lcc<$lc;$lcc++) {
	$Form->add_InputOption($FormularName,$InputName_Lang,$LANGUAGES['lang'][$lcc],$LANGUAGES['text'][$lcc]);
}

//Expert
$Form->new_Input($FormularName,$InputName_Expert,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Expert," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Expert,$$InputName_Expert);
$Form->set_InputStyleClass($FormularName,$InputName_Expert,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Expert,48,256);
$Form->set_InputDesc($FormularName,$InputName_Expert,___("Experten-Modus"));
$Form->set_InputReadonly($FormularName,$InputName_Expert,false);
$Form->set_InputOrder($FormularName,$InputName_Expert,3);
$Form->set_InputLabel($FormularName,$InputName_Expert,"");

//Startpage
$Form->new_Input($FormularName,$InputName_Startpage,"select", "");
$Form->set_InputJS($FormularName,$InputName_Startpage," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Startpage,$$InputName_Startpage);
$Form->set_InputStyleClass($FormularName,$InputName_Startpage,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Startpage,___("Startseite"));
$Form->set_InputReadonly($FormularName,$InputName_Startpage,false);
$Form->set_InputOrder($FormularName,$InputName_Startpage,1);
$Form->set_InputLabel($FormularName,$InputName_Startpage,"");
$Form->set_InputSize($FormularName,$InputName_Startpage,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Startpage,false);
//add Data
	$Form->add_InputOption($FormularName,$InputName_Startpage,"Welcome",___("Tellmatic default"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"Help",___("Hilfe"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"Doc",___("Dokumentation"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"nl_grp_list",___("Newsletter: Gruppen: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"nl_list",___("Newsletter: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"nl_list_tpl",___("Newsletter: Liste: Vorlagen"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"nl_new",___("Newsletter: Neu"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"adr_list_search",___("Adressen: Suche"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"adr_list",___("Adressen: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"adr_new",___("Adressen: Neu"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"form_list",___("Formulare: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"link_grp_list",___("Links: Gruppen: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"link_list",___("Links: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"queue_list",___("Queue/Aufträge: Liste"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"status",___("Status"));	
	$Form->add_InputOption($FormularName,$InputName_Startpage,"status_top_x",___("Status: Top X"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"status_map",___("Status: Map"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"bounce",___("Verwaltung: Bouncemanagement"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"adr_clean",___("Verwaltung: DB Bereinigen"));
	$Form->add_InputOption($FormularName,$InputName_Startpage,"log_list",___("Logbuch: Liste"));

//debug 
//can only set by admin
if ($user_is_admin) {
	//debug 
	$Form->new_Input($FormularName,$InputName_Debug,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Debug," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Debug,$$InputName_Debug);
	$Form->set_InputStyleClass($FormularName,$InputName_Debug,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Debug,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Debug,___("Debugging"));
	$Form->set_InputReadonly($FormularName,$InputName_Debug,false);
	$Form->set_InputOrder($FormularName,$InputName_Debug,3);
	$Form->set_InputLabel($FormularName,$InputName_Debug,"");
}

//debug_lang
$Form->new_Input($FormularName,$InputName_DebugLang,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_DebugLang," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_DebugLang,$$InputName_DebugLang);
$Form->set_InputStyleClass($FormularName,$InputName_DebugLang,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DebugLang,48,256);
$Form->set_InputDesc($FormularName,$InputName_DebugLang,___("Debugging für Übersetzungen"));
$Form->set_InputReadonly($FormularName,$InputName_DebugLang,false);
$Form->set_InputOrder($FormularName,$InputName_DebugLang,3);
$Form->set_InputLabel($FormularName,$InputName_DebugLang,"");

//debug_lang_level
$Form->new_Input($FormularName,$InputName_DebugLangLevel,"select", "");
$Form->set_InputJS($FormularName,$InputName_DebugLangLevel," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_DebugLangLevel,$$InputName_DebugLangLevel);
$Form->set_InputStyleClass($FormularName,$InputName_DebugLangLevel,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_DebugLangLevel,___("Debugging für Übersetzungen"));
$Form->set_InputReadonly($FormularName,$InputName_DebugLangLevel,false);
$Form->set_InputOrder($FormularName,$InputName_DebugLangLevel,1);
$Form->set_InputLabel($FormularName,$InputName_DebugLangLevel,"");
$Form->set_InputSize($FormularName,$InputName_DebugLangLevel,0,1);
$Form->set_InputMultiple($FormularName,$InputName_DebugLangLevel,false);
//add Data
for ($dllc=1;$dllc<=3;$dllc++) {
	$Form->add_InputOption($FormularName,$InputName_DebugLangLevel,$dllc,$debug_lang_levels_arr[$dllc]." (".$dllc.")");
}


//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",sprintf(___("Einstellungen für Benutzer %s ändern"),$LOGIN->USER['name']));
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