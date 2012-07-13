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
$FormularName="adr_grp_new";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("Neue Adressgruppe erstellen"));//variable content aus menu als hidden field!
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"adr_grp_id", "hidden", $adr_grp_id);
//////////////////
//add inputfields and buttons....
//////////////////
//Name
$Form->new_Input($FormularName,$InputName_Name,"text", display($$InputName_Name));
$Form->set_InputJS($FormularName,$InputName_Name," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,48,256);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Name"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"");

//Aktiv
//wenn standardgruppe, dann kann sie nicht deaktiviert werden!!!
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

//PUB
	$Form->new_Input($FormularName,$InputName_Public,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Public," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Public,$$InputName_Public);
	$Form->set_InputStyleClass($FormularName,$InputName_Public,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Public,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Public,___("Öffentlich"));
	$Form->set_InputReadonly($FormularName,$InputName_Public,false);
	$Form->set_InputOrder($FormularName,$InputName_Public,3);
	$Form->set_InputLabel($FormularName,$InputName_Public,"");

//PUBName
$Form->new_Input($FormularName,$InputName_PublicName,"text", display($$InputName_PublicName));
$Form->set_InputJS($FormularName,$InputName_PublicName," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_PublicName,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_PublicName,48,256);
$Form->set_InputDesc($FormularName,$InputName_PublicName,___("Name (öffentlich)"));
$Form->set_InputReadonly($FormularName,$InputName_PublicName,false);
$Form->set_InputOrder($FormularName,$InputName_PublicName,4);
$Form->set_InputLabel($FormularName,$InputName_PublicName,"");
//Descr
$Form->new_Input($FormularName,$InputName_Descr,"textarea", display($$InputName_Descr));
$Form->set_InputJS($FormularName,$InputName_Descr," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Descr,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_Descr,20,5);
$Form->set_InputDesc($FormularName,$InputName_Descr,___("Beschreibung"));
$Form->set_InputReadonly($FormularName,$InputName_Descr,false);
$Form->set_InputOrder($FormularName,$InputName_Descr,5);
$Form->set_InputLabel($FormularName,$InputName_Descr,"");

//Produtcion group
	$Form->new_Input($FormularName,$InputName_Prod,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Prod," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Prod,$$InputName_Prod);
	$Form->set_InputStyleClass($FormularName,$InputName_Prod,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Prod,48,256);
	$Form->set_InputDesc($FormularName,$InputName_Prod,___("Produktiv"));
	$Form->set_InputReadonly($FormularName,$InputName_Prod,false);
	$Form->set_InputOrder($FormularName,$InputName_Prod,3);
	$Form->set_InputLabel($FormularName,$InputName_Prod,"");



//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Speichern"));
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