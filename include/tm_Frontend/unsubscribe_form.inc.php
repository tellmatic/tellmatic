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
$FormularName="unsubscribe";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
//add a Description
$Form->set_FormDesc($FormularName,"unsubscribe");
$Form->new_Input($FormularName,"set", "hidden", "unsubscribe");
$Form->new_Input($FormularName,"a_id", "hidden", $a_id);
$Form->new_Input($FormularName,"h_id", "hidden", $h_id);
$Form->new_Input($FormularName,"nl_id", "hidden", $nl_id);
$Form->new_Input($FormularName,"code", "hidden", $code);

$Form->new_Input($FormularName,"cpt", "hidden", $captcha_md5);

$Form->set_InputID($FormularName,"set", "set_u");
$Form->set_InputID($FormularName,"email", "email_u");
$Form->set_InputID($FormularName,"fcpt", "fcpt_u");
$Form->set_InputID($FormularName,"submit", "submit_u");
//////////////////
//add inputfields and buttons....
//////////////////
//EMAIL
$Form->new_Input($FormularName,$InputName_Name,"text", display($$InputName_Name));
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,48,256);
$Form->set_InputDesc($FormularName,$InputName_Name,"");
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"");

//Captcha
$Form->new_Input($FormularName,$InputName_Captcha,"text", "");
$Form->set_InputStyleClass($FormularName,$InputName_Captcha,"tm_form_captcha","tm_form_focus_captcha");
$Form->set_InputSize($FormularName,$InputName_Captcha,12,12);
$Form->set_InputDesc($FormularName,$InputName_Captcha,"");
$Form->set_InputReadonly($FormularName,$InputName_Captcha,false);
$Form->set_InputOrder($FormularName,$InputName_Captcha,888);
$Form->set_InputLabel($FormularName,$InputName_Captcha,"");

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit","Abmelden");
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");
?>