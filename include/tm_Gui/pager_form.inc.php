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
$_MAIN_OUTPUT.="\n\n<!-- pager_form.inc.php -->\n\n";

$InputName_Submit_P="submit_pager";
$InputName_Reset_P="reset_pager";

//Form
$Form_P=new tm_SimpleForm();
$FormularName_P="pager_form";
//make new Form
$Form_P->new_Form($FormularName_P,$_SERVER["PHP_SELF"],"post","_self");

//add a Description
$Form_P->set_FormDesc($FormularName_P,___("Seite"));

$Form_P->new_Input($FormularName_P,"act", "hidden", $action);
//hidden felder f. sortierung, search, gruppe etc.....

//////////////////
//add inputfields and buttons....
//////////////////
//Typ
$Form_P->new_Input($FormularName_P,$InputName_Page,"select", "");
$Form_P->set_InputJS($FormularName_P,$InputName_Page," onChange=\"flash('submit_bl','#ff0000');submit();switchSection('div_loader');\" ");

$Form_P->set_InputDefault($FormularName_P,$InputName_Page,$$InputName_Page);
$Form_P->set_InputStyleClass($FormularName_P,$InputName_Page,"mFormSelect","mFormSelectFocus");
$Form_P->set_InputDesc($FormularName_P,$InputName_Page,___("Gehe zu Seite"));
$Form_P->set_InputReadonly($FormularName_P,$InputName_Page,false);
$Form_P->set_InputOrder($FormularName_P,$InputName_Page,1);
$Form_P->set_InputLabel($FormularName_P,$InputName_Page,___("Seite").":");
$Form_P->set_InputSize($FormularName_P,$InputName_Page,0,1);
$Form_P->set_InputMultiple($FormularName_P,$InputName_Page,false);

for ($page_c=0;$page_c<$page_total;$page_c++) {
	$Form_P->add_InputOption($FormularName_P,$InputName_Page,$page_c,$page_c);
}
//submit button
$Form_P->new_Input($FormularName_P,$InputName_Submit_P,"submit",___("Aktion ausführen"));
$Form_P->set_InputStyleClass($FormularName_P,$InputName_Submit_P,"mFormSubmit","mFormSubmitFocus");
$Form_P->set_InputDesc($FormularName_P,$InputName_Submit_P,"");
$Form_P->set_InputReadonly($FormularName_P,$InputName_Submit_P,false);
$Form_P->set_InputOrder($FormularName_P,$InputName_Submit_P,998);
$Form_P->set_InputLabel($FormularName_P,$InputName_Submit_P,"");

//a reset button
$Form_P->new_Input($FormularName_P,$InputName_Reset_P,"reset",___("Reset"));
$Form_P->set_InputStyleClass($FormularName_P,$InputName_Reset_P,"mFormReset","mFormResetFocus");
$Form_P->set_InputDesc($FormularName_P,$InputName_Reset_P,___("Reset"));
$Form_P->set_InputReadonly($FormularName_P,$InputName_Reset_P,false);
$Form_P->set_InputOrder($FormularName_P,$InputName_Reset_P,999);
$Form_P->set_InputLabel($FormularName_P,$InputName_Reset_P,"");
?>