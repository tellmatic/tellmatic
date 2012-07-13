<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006-2011 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/
$_MAIN_OUTPUT.="\n\n<!-- link_search_form.inc -->\n\n";

$InputName_Submit="submit";
$InputName_Reset="reset";

//Form
$Form=new tm_SimpleForm();
$FormularName="lnk_search";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"get","_self");

$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");

//add a Description
$Form->set_FormDesc($FormularName,___("Einträge suchen und filtern"));
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set_search", "hidden", "search");

//////////////////
//add inputfields and buttons....
//////////////////
//Name
$Form->new_Input($FormularName,$InputName_Name,"text",$$InputName_Name);
$Form->set_InputJS($FormularName,$InputName_Name," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,12,256);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Suche nach Name"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"Name<br>");

// URL
$Form->new_Input($FormularName,$InputName_URL,"text",$$InputName_URL);
$Form->set_InputJS($FormularName,$InputName_URL," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputStyleClass($FormularName,$InputName_URL,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_URL,12,256);
$Form->set_InputDesc($FormularName,$InputName_URL,___("Suche nach URL"));
$Form->set_InputReadonly($FormularName,$InputName_URL,false);
$Form->set_InputOrder($FormularName,$InputName_URL,1);
$Form->set_InputLabel($FormularName,$InputName_URL,"URL<br>");

//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Group,$lnk_grp_id);
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Suche nach Gruppenzugehörigkeit"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,3);
$Form->set_InputLabel($FormularName,$InputName_Group,"Gruppe<br>");
$Form->set_InputSize($FormularName,$InputName_Group,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Group,false);
//add Data
$LINK=new tm_LNK();
$GRP=$LINK->getGroup(0,Array("count"=>1));
$acg=count($GRP);

$Form->add_InputOption($FormularName,$InputName_Group,"","-- alle");
for ($accg=0; $accg<$acg; $accg++)
{
	$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],$GRP[$accg]['name']." (".$GRP[$accg]['item_count'].")");
}


//Limit
$Form->new_Input($FormularName,$InputName_Limit,"select", "");
$Form->set_InputJS($FormularName,$InputName_Limit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Limit,$limit);
$Form->set_InputStyleClass($FormularName,$InputName_Limit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Limit,___("Maximale Anzahl Einträge die auf einmal angezeigt werden."));
$Form->set_InputReadonly($FormularName,$InputName_Limit,false);
$Form->set_InputOrder($FormularName,$InputName_Limit,10);
$Form->set_InputLabel($FormularName,$InputName_Limit,___("zeige max.")."<br>");
$Form->set_InputSize($FormularName,$InputName_Limit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Limit,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Limit,5,"5 ".___("Einträge"));
$Form->add_InputOption($FormularName,$InputName_Limit,10,"10 ".___("Einträge"));
$Form->add_InputOption($FormularName,$InputName_Limit,25,"25 ".___("Einträge"));
$Form->add_InputOption($FormularName,$InputName_Limit,50,"50 ".___("Einträge"));

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Suchen"));
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