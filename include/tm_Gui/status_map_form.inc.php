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
$FormularName="map";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");

//add a Description
$Form->set_FormDesc($FormularName,___("Karten generieren"));
//variable content aus menu als hidden field!
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"create", "hidden", "1");

//////////////////
//add inputfields and buttons....
//////////////////
$Form->new_Input($FormularName,$InputName_CreateMap,"select", "");
$Form->set_InputJS($FormularName,$InputName_CreateMap," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_CreateMap,"");
$Form->set_InputStyleClass($FormularName,$InputName_CreateMap,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_CreateMap,___("Erstelle Karte (neu)"));
$Form->set_InputReadonly($FormularName,$InputName_CreateMap,false);
$Form->set_InputOrder($FormularName,$InputName_CreateMap,6);
$Form->set_InputLabel($FormularName,$InputName_CreateMap,"");
$Form->set_InputSize($FormularName,$InputName_CreateMap,0,1);
$Form->set_InputMultiple($FormularName,$InputName_CreateMap,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_CreateMap,"subscriptions",___("Anmeldungen"));
$Form->add_InputOption($FormularName,$InputName_CreateMap,"readers",___("Leser"));
$Form->add_InputOption($FormularName,$InputName_CreateMap,"readers_rad",___("Leser - gewichtet"));
//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Karte erstellen/aktualisieren"));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");
?>