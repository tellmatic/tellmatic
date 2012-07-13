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
$FormularName="edit_bl";
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" OnChange=\"checkHostType();\" onClick=\"checkHostType();\"");
$Form->set_FormDesc($FormularName,___("Blacklist bearbeiten"));

//variable content aus menu als hidden field!
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"bl_id", "hidden", $bl_id);
//////////////////
//add inputfields and buttons....
//////////////////

//Expression/Ausdruck
$Form->new_Input($FormularName,$InputName_Expr,"text",display($$InputName_Expr));
$Form->set_InputJS($FormularName,$InputName_Expr," onChange=\"flash('submit','#ff0000');\" ");//onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\ \_\.\-]');\"
$Form->set_InputStyleClass($FormularName,$InputName_Expr,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Expr,48,256);
$Form->set_InputDesc($FormularName,$InputName_Expr,___("Ausdruck (E-Mail/Domain/RegEx)"));
$Form->set_InputReadonly($FormularName,$InputName_Expr,false);
$Form->set_InputOrder($FormularName,$InputName_Expr,2);
$Form->set_InputLabel($FormularName,$InputName_Expr,"");

//Type
$Form->new_Input($FormularName,$InputName_Type,"select", "");
$Form->set_InputJS($FormularName,$InputName_Type," onChange=\"flash('submit','#ff0000');\" ");//checkHostType();
$Form->set_InputDefault($FormularName,$InputName_Type,$$InputName_Type);
$Form->set_InputStyleClass($FormularName,$InputName_Type,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Type,___("Typ"));
$Form->set_InputReadonly($FormularName,$InputName_Type,false);
$Form->set_InputOrder($FormularName,$InputName_Type,1);
$Form->set_InputLabel($FormularName,$InputName_Type,"");
$Form->set_InputSize($FormularName,$InputName_Type,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Type,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Type,"email",___("E-Mail"));
$Form->add_InputOption($FormularName,$InputName_Type,"domain",___("Domain"));
$Form->add_InputOption($FormularName,$InputName_Type,"expr",___("Regulärer Ausdruck"));

//Aktiv
$Form->new_Input($FormularName,$InputName_Aktiv,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Aktiv," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Aktiv,$$InputName_Aktiv);
$Form->set_InputStyleClass($FormularName,$InputName_Aktiv,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Aktiv,48,256);
$Form->set_InputDesc($FormularName,$InputName_Aktiv,___("Aktiv"));
$Form->set_InputReadonly($FormularName,$InputName_Aktiv,false);
$Form->set_InputOrder($FormularName,$InputName_Aktiv,3);
$Form->set_InputLabel($FormularName,$InputName_Aktiv,"");

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