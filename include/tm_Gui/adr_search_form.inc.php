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
$_MAIN_OUTPUT.="\n\n<!-- adr_search_form.inc -->\n\n";

$InputName_Submit="submit";
$InputName_Reset="reset";

//Form
$Form=new tm_SimpleForm();
$FormularName="adr_search";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"get","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");

//add a Description
$Form->set_FormDesc($FormularName,___("Adressen suchen und filtern"));
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set_search", "hidden", "search");

//////////////////
//add inputfields and buttons....
//////////////////
//EMAIL
$Form->new_Input($FormularName,$InputName_Name,"text",$$InputName_Name);
$Form->set_InputJS($FormularName,$InputName_Name," onChange=\"flash('submit','#ff0000');\" onkeyup=\"RemoveInvalidChars(this, '[^A-Za-z0-9\*\_\@\.\-]'); ForceLowercase(this);\"");
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,12,256);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Suche nach e-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"e-Mail<br>");

//F
$Form->new_Input($FormularName,$InputName_F,"text", $$InputName_F);
$Form->set_InputJS($FormularName,$InputName_F," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_F,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_F,12,256);
$Form->set_InputDesc($FormularName,$InputName_F,___("Suche in den Feldern F0-F9"));
$Form->set_InputReadonly($FormularName,$InputName_F,false);
$Form->set_InputOrder($FormularName,$InputName_F,2);
$Form->set_InputLabel($FormularName,$InputName_F,"F0-F9<br>");


//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Group,$adr_grp_id);
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Suche nach Gruppenzugehörigkeit"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,3);
$Form->set_InputLabel($FormularName,$InputName_Group,"Gruppe<br>");
$Form->set_InputSize($FormularName,$InputName_Group,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Group,false);
//add Data
$ADDRESS=new tm_ADR();
$GRP=$ADDRESS->getGroup(0,0,0,1);
$acg=count($GRP);

$Form->add_InputOption($FormularName,$InputName_Group,"","-- alle");
for ($accg=0; $accg<$acg; $accg++)
{
	$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],$GRP[$accg]['name']." (".$GRP[$accg]['adr_count'].")");
}

//Author bzw Formular! Form_[ID]
$Form->new_Input($FormularName,$InputName_Author,"select", "");
$Form->set_InputJS($FormularName,$InputName_Author," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Author,$$InputName_Author);
$Form->set_InputStyleClass($FormularName,$InputName_Author,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Author,___("Herkunft / Quelle"));
$Form->set_InputReadonly($FormularName,$InputName_Author,false);
$Form->set_InputOrder($FormularName,$InputName_Author,6);
$Form->set_InputLabel($FormularName,$InputName_Author,___("Herkunft / Quelle")."<br>");
$Form->set_InputSize($FormularName,$InputName_Author,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Author,false);
//add Data
$FORMULAR=new tm_FRM();
$FRM=$FORMULAR->getForm();
$fcg=count($FRM);

$Form->add_InputOption($FormularName,$InputName_Author,"","-- ".___("Alle"));
for ($fccg=0; $fccg<$fcg; $fccg++)
{
//	$Form->add_InputOption($FormularName,$InputName_Author,$FRM[$fccg]['id'],$FRM[$fccg]['name']."(".$FRM[$fccg]['id'].")");
	$Form->add_InputOption($FormularName,$InputName_Author,$FRM[$fccg]['id'],"Formular ID ".$FRM[$fccg]['id']);
}

//Status
$Form->new_Input($FormularName,$InputName_Status,"select", "");
$Form->set_InputJS($FormularName,$InputName_Status," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Status,$$InputName_Status);
$Form->set_InputStyleClass($FormularName,$InputName_Status,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Status,___("Suche nach Adress-Status"));
$Form->set_InputReadonly($FormularName,$InputName_Status,false);
$Form->set_InputOrder($FormularName,$InputName_Status,4);
$Form->set_InputLabel($FormularName,$InputName_Status,___("Status")."<br>");
$Form->set_InputSize($FormularName,$InputName_Status,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Status,false);
//add Data
$sc=count($STATUS['adr']['status']);

$Form->add_InputOption($FormularName,$InputName_Status,"","-- ".___("Alle"));
for ($scc=1; $scc<=$sc; $scc++)//0
{
	$Form->add_InputOption($FormularName,$InputName_Status,$scc,display($STATUS['adr']['status'][$scc]));
}


//Aktiv
$Form->new_Input($FormularName,$InputName_Aktiv,"select", "");
$Form->set_InputJS($FormularName,$InputName_Aktiv," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Aktiv,$$InputName_Aktiv);//$s_aktiv
$Form->set_InputStyleClass($FormularName,$InputName_Aktiv,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Aktiv,___("Nach aktiven/deaktivierten Adressen filtern"));
$Form->set_InputReadonly($FormularName,$InputName_Aktiv,false);
$Form->set_InputOrder($FormularName,$InputName_Aktiv,5);
$Form->set_InputLabel($FormularName,$InputName_Aktiv,___("Aktiv")."<br>");
$Form->set_InputSize($FormularName,$InputName_Aktiv,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Aktiv,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Aktiv,1,"".___("Aktiv"));
$Form->add_InputOption($FormularName,$InputName_Aktiv,0,"".___("Inaktiv"));
$Form->add_InputOption($FormularName,$InputName_Aktiv,"","".___("Alle"));

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
$Form->add_InputOption($FormularName,$InputName_Limit,5,"5 ".___("Adressen"));
$Form->add_InputOption($FormularName,$InputName_Limit,10,"10 ".___("Adressen"));
$Form->add_InputOption($FormularName,$InputName_Limit,25,"25 ".___("Adressen"));
$Form->add_InputOption($FormularName,$InputName_Limit,50,"50 ".___("Adressen"));
$Form->add_InputOption($FormularName,$InputName_Limit,100,"100 ".___("Adressen"));
$Form->add_InputOption($FormularName,$InputName_Limit,250,"250 ".___("Adressen"));


//SaveSearch
$Form->new_Input($FormularName,$InputName_SaveSearch,"checkbox", 1);
$Form->set_InputDefault($FormularName,$InputName_SaveSearch,$$InputName_SaveSearch);
$Form->set_InputJS($FormularName,$InputName_SaveSearch," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_SaveSearch,"mFormText","mFormTextFocus");
$Form->set_InputDesc($FormularName,$InputName_SaveSearch,___("Suchparameter speichern"));
$Form->set_InputReadonly($FormularName,$InputName_SaveSearch,false);
$Form->set_InputOrder($FormularName,$InputName_SaveSearch,11);
$Form->set_InputLabel($FormularName,$InputName_SaveSearch,___("Suche speichern")."<br>");

//Sort-multi - 0
$Form->new_Input($FormularName,$InputName_SI0,"select", "");
$Form->set_InputJS($FormularName,$InputName_SI0," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SI0,$si0);
$Form->set_InputStyleClass($FormularName,$InputName_SI0,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SI0,___("Sortieren"));
$Form->set_InputReadonly($FormularName,$InputName_SI0,false);
$Form->set_InputOrder($FormularName,$InputName_SI0,6);
$Form->set_InputLabel($FormularName,$InputName_SI0,___("sortiere nach")."<br>");
$Form->set_InputSize($FormularName,$InputName_SI0,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SI0,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_SI0,"","--");
$Form->add_InputOption($FormularName,$InputName_SI0,"email",___("E-Mail-Adresse"));
$Form->add_InputOption($FormularName,$InputName_SI0,"aktiv",___("Aktiv"));
$Form->add_InputOption($FormularName,$InputName_SI0,"status",___("Status"));
$Form->add_InputOption($FormularName,$InputName_SI0,"created",___("Erstellungs-Datum"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f0",___("F0"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f1",___("F1"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f2",___("F2"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f3",___("F3"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f4",___("F4"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f5",___("F5"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f6",___("F6"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f7",___("F7"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f8",___("F8"));
$Form->add_InputOption($FormularName,$InputName_SI0,"f9",___("F9"));

//Sort-multi - 0
$Form->new_Input($FormularName,$InputName_SI1,"select", "");
$Form->set_InputJS($FormularName,$InputName_SI1," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SI1,$si1);
$Form->set_InputStyleClass($FormularName,$InputName_SI1,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SI1,___("Sortieren"));
$Form->set_InputReadonly($FormularName,$InputName_SI1,false);
$Form->set_InputOrder($FormularName,$InputName_SI1,6);
$Form->set_InputLabel($FormularName,$InputName_SI1,___("sortiere nach")."<br>");
$Form->set_InputSize($FormularName,$InputName_SI1,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SI1,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_SI1,"","--");
$Form->add_InputOption($FormularName,$InputName_SI1,"email",___("E-Mail-Adresse"));
$Form->add_InputOption($FormularName,$InputName_SI1,"aktiv",___("Aktiv"));
$Form->add_InputOption($FormularName,$InputName_SI1,"status",___("Status"));
$Form->add_InputOption($FormularName,$InputName_SI1,"created",___("Erstellungs-Datum"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f0",___("F0"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f1",___("F1"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f2",___("F2"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f3",___("F3"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f4",___("F4"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f5",___("F5"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f6",___("F6"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f7",___("F7"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f8",___("F8"));
$Form->add_InputOption($FormularName,$InputName_SI1,"f9",___("F9"));

//Sort-multi - 2
$Form->new_Input($FormularName,$InputName_SI2,"select", "");
$Form->set_InputJS($FormularName,$InputName_SI2," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_SI2,$si2);
$Form->set_InputStyleClass($FormularName,$InputName_SI2,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_SI2,___("Sortieren"));
$Form->set_InputReadonly($FormularName,$InputName_SI2,false);
$Form->set_InputOrder($FormularName,$InputName_SI2,6);
$Form->set_InputLabel($FormularName,$InputName_SI2,___("sortiere nach")."<br>");
$Form->set_InputSize($FormularName,$InputName_SI2,0,1);
$Form->set_InputMultiple($FormularName,$InputName_SI2,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_SI2,"","--");
$Form->add_InputOption($FormularName,$InputName_SI2,"email",___("E-Mail-Adresse"));
$Form->add_InputOption($FormularName,$InputName_SI2,"aktiv",___("Aktiv"));
$Form->add_InputOption($FormularName,$InputName_SI2,"status",___("Status"));
$Form->add_InputOption($FormularName,$InputName_SI2,"created",___("Erstellungs-Datum"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f0",___("F0"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f1",___("F1"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f2",___("F2"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f3",___("F3"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f4",___("F4"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f5",___("F5"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f6",___("F6"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f7",___("F7"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f8",___("F8"));
$Form->add_InputOption($FormularName,$InputName_SI2,"f9",___("F9"));


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
//$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Status]['html'];
//$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_F]['html'];

/*
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SI0]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SI1]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=top colspan=1>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SI2]['html'];
$_MAIN_OUTPUT.= "</td>";
*/

#$_MAIN_OUTPUT.= "<div id=\"avail\" name=\"avail\" style=\"overflow:auto; height:100px; margin:5px; background-color:#ffffff;\">avail:<br>&nbsp;</div>";
?>