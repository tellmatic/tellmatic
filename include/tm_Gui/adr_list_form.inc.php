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
$_MAIN_OUTPUT.="\n\n<!-- adr_list_form.inc -->\n\n";

$InputName_AdrID="adr_id_arr";//
pt_register("POST",$InputName_AdrID);
if (!isset($$InputName_AdrID)) {
	$$InputName_AdrID=Array();
}

$InputName_Action="set";
$$InputName_Action=getVar($InputName_Action);
if (empty($$InputName_Action)) {
	$$InputName_Action="";
}

$InputName_Status="status_multi";//
$$InputName_Status=getVar($InputName_Status);

$InputName_Group="adr_grp_id_multi";//
#$$InputName_Group=getVar($InputName_Group);
pt_register("POST",$InputName_Group);
if (!isset($$InputName_Group)) {
	$$InputName_Group=Array();
}

#print_r($$InputName_Group);

$InputName_Submit="submit_adr";
$InputName_Reset="reset";

//Form
$Form=new tm_SimpleForm();
$FormularName="adr_list";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");

$Form->set_FormJS($FormularName," onSubmit=\"return confirmLink(this, '".___("Wirklich?")."');switchSection('div_loader');\" OnChange=\"checkAdrListForm();\" onClick=\"checkAdrListForm();\" ");

//$this->set_FormStyle($FormularName,"font-size:10pt;font-color=red;");
////$Form->set_FormStyleClass($FormularName,"mForm");
////$Form->set_FormInputStyleClass($FormularName,"mFormText","mFormTextFocus");
//add a Description
$Form->set_FormDesc($FormularName,___("Adressen"));

$Form->new_Input($FormularName,"act", "hidden", $action);
//hidden felder f. sortierung, search, gruppe etc.....
$Form->new_Input($FormularName,"si", "hidden", $sortIndex);
$Form->new_Input($FormularName,"si0", "hidden", $si0);
$Form->new_Input($FormularName,"si1", "hidden", $si1);
$Form->new_Input($FormularName,"si2", "hidden", $si2);
$Form->new_Input($FormularName,"st", "hidden", $sortType);
$Form->new_Input($FormularName,"offset", "hidden", $offset);
$Form->new_Input($FormularName,"limit", "hidden", $limit);
$Form->new_Input($FormularName,"s_status", "hidden", $s_status);
$Form->new_Input($FormularName,"s_author", "hidden", $s_author);
$Form->new_Input($FormularName,"s_email", "hidden", $s_email);
$Form->new_Input($FormularName,"f0_9", "hidden", $f0_9);
$Form->new_Input($FormularName,"adr_grp_id", "hidden", $adr_grp_id);

//////////////////
//add inputfields and buttons....
//////////////////

//AdrIDs
$Form->new_Input($FormularName,$InputName_AdrID,"checkbox", "");
$Form->set_InputJS($FormularName,$InputName_AdrID," onChange=\"flash('submit_adr','#ff0000'); checkAdrListForm();\" onClick=\"checkAdrListForm();\" ");
$Form->set_InputDefault($FormularName,$InputName_AdrID,$$InputName_AdrID);
$Form->set_InputStyleClass($FormularName,$InputName_AdrID,"mFormCheckbox","mFormCheckboxFocus");
$Form->set_InputDesc($FormularName,$InputName_AdrID,___("Auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_AdrID,false);
$Form->set_InputOrder($FormularName,$InputName_AdrID,1);
$Form->set_InputLabel($FormularName,$InputName_AdrID,"");
$Form->set_InputMultiple($FormularName,$InputName_AdrID,true);

//Aktion
$Form->new_Input($FormularName,$InputName_Action,"select", "");
$Form->set_InputJS($FormularName,$InputName_Action," onChange=\"flash('submit_adr','#ff0000'); checkAdrListForm();\" onClick=\"checkAdrListForm();\" ");
$Form->set_InputDefault($FormularName,$InputName_Action,$$InputName_Action);
$Form->set_InputStyleClass($FormularName,$InputName_Action,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Action,___("Aktion ausführen"));
$Form->set_InputReadonly($FormularName,$InputName_Action,false);
$Form->set_InputOrder($FormularName,$InputName_Action,1);
$Form->set_InputLabel($FormularName,$InputName_Action,___("Aktion")."<br>");
$Form->set_InputSize($FormularName,$InputName_Action,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Action,false);
$Form->add_InputOption($FormularName,$InputName_Action,"check_syntax_multi",___("E-Mail Syntax prüfen"));
$Form->add_InputOption($FormularName,$InputName_Action,"check_mx_multi",___("E-Mail MX/DNS prüfen"));
$Form->add_InputOption($FormularName,$InputName_Action,"check_validate_multi",___("E-Mail Validieren"));
$Form->add_InputOption($FormularName,$InputName_Action,"aktiv_1_multi",___("aktivieren"));
$Form->add_InputOption($FormularName,$InputName_Action,"aktiv_0_multi",___("deaktivieren"));
$Form->add_InputOption($FormularName,$InputName_Action,"set_status_multi",___("Status setzen"));
$Form->add_InputOption($FormularName,$InputName_Action,"delete_multi",___("löschen"));
$Form->add_InputOption($FormularName,$InputName_Action,"move_grp_multi",___("In gewählte Gruppen verschieben"));
$Form->add_InputOption($FormularName,$InputName_Action,"copy_grp_multi",___("Zu gewählten Gruppen hinzufügen"));
$Form->add_InputOption($FormularName,$InputName_Action,"delete_grp_multi",___("Aus gewählten Gruppen austragen"));
if ($user_is_manager) {
	$Form->add_InputOption($FormularName,$InputName_Action,"blacklist_multi",___("Ausgewählte Adressen in Blacklist eintragen"));
	$Form->add_InputOption($FormularName,$InputName_Action,"blacklist_domain_multi",___("Domains der ausgewählten Adressen in Blacklist eintragen"));
	$Form->add_InputOption($FormularName,$InputName_Action,"delete_history_multi",___("Historie für ausgewählte Adressen löschen"));
}

//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit_adr','#ff0000'); checkAdrListForm();\" onClick=\"checkAdrListForm();\" ");
$Form->set_InputDefault($FormularName,$InputName_Group,$$InputName_Group);
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Gruppen wählen, STRG/CTRL gedrückt halten und klicken f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,6);
$Form->set_InputLabel($FormularName,$InputName_Group,___("Gruppen")."<br>");
$Form->set_InputSize($FormularName,$InputName_Group,0,3);
$Form->set_InputMultiple($FormularName,$InputName_Group,true);
//add Data
$ADDRESS=new tm_ADR();
$GRP=$ADDRESS->getGroup();
$acg=count($GRP);
for ($accg=0; $accg<$acg; $accg++)
{
	$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],$GRP[$accg]['name']);
}

//Status
$Form->new_Input($FormularName,$InputName_Status,"select", "");
$Form->set_InputJS($FormularName,$InputName_Status," onChange=\"flash('submit_adr','#ff0000'); checkAdrListForm();\" onClick=\"checkAdrListForm();\" ");
$Form->set_InputDefault($FormularName,$InputName_Status,$$InputName_Status);
$Form->set_InputStyleClass($FormularName,$InputName_Status,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Status,___("Status"));
$Form->set_InputReadonly($FormularName,$InputName_Status,false);
$Form->set_InputOrder($FormularName,$InputName_Status,6);
$Form->set_InputLabel($FormularName,$InputName_Status,___("Status")."<br>");
$Form->set_InputSize($FormularName,$InputName_Status,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Status,false);
//add Data
$sc=count($STATUS['adr']['status']);

for ($scc=1; $scc<=$sc; $scc++)//0
{
	$Form->add_InputOption($FormularName,$InputName_Status,$scc,display($STATUS['adr']['status'][$scc]));
}

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Aktion ausführen"));
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

/*RENDER FORM*/
$Form->render_Form($FormularName);
//then you dont have to render the head and foot .....
/*DISPLAY*/
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si0']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si1']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['si2']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['st']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['offset']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['limit']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_status']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_author']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['s_email']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['f0_9']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['adr_grp_id']['html'];
?>