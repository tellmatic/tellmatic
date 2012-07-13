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
$FormularName="nl_grp_new";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
//$this->set_FormStyle($FormularName,"font-size:10pt;font-color=red;");
////$Form->set_FormStyleClass($FormularName,"mForm");
////$Form->set_FormInputStyleClass($FormularName,"mFormText","mFormTextFocus");

$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");

//add a Description
$Form->set_FormDesc($FormularName,___("Neue Newslettergruppe erstellen"));
//variable content aus menu als hidden field!
//$Form->new_Input($FormularName,"type", "hidden", $content);
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"nl_grp_id", "hidden", $nl_grp_id);
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

//Name
$Form->new_Input($FormularName,$InputName_Descr,"textarea", display($$InputName_Descr));
$Form->set_InputJS($FormularName,$InputName_Descr," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Descr,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_Descr,20,5);
$Form->set_InputDesc($FormularName,$InputName_Descr,___("Beschreibung"));
$Form->set_InputReadonly($FormularName,$InputName_Descr,false);
$Form->set_InputOrder($FormularName,$InputName_Descr,3);
$Form->set_InputLabel($FormularName,$InputName_Descr,"");

//COLOR!
$Form->new_Input($FormularName,$InputName_Color,"text", $$InputName_Color);
$Form->set_InputJS($FormularName,$InputName_Color," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Color,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Color,8,8);
$Form->set_InputDesc($FormularName,$InputName_Color,___("Farbe: Auswaehlen und klicken um Farbe zu uebernehmen"));
$Form->set_InputReadonly($FormularName,$InputName_Color,true);
$Form->set_InputOrder($FormularName,$InputName_Color,1);
$Form->set_InputLabel($FormularName,$InputName_Color,"");

//COLOR!
$Form->new_Input($FormularName,$InputName_ColorView,"text", "");
$Form->set_InputStyleClass($FormularName,$InputName_ColorView,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ColorView,3,1);
$Form->set_InputDesc($FormularName,$InputName_ColorView,___("Farbe: Auswaehlen und klicken um Farbe zu uebernehmen"));
$Form->set_InputReadonly($FormularName,$InputName_ColorView,true);
$Form->set_InputOrder($FormularName,$InputName_ColorView,1);
$Form->set_InputLabel($FormularName,$InputName_ColorView,"");


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
/*
$_MAIN_OUTPUT.= "<br>";
include (TM_INCLUDEPATH_GUI."/colors.inc.php");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ColorView]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Color]['html'];
*/
?>