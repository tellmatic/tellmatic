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
$FormularName="adr_import";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" OnChange=\"checkImport();\" onClick=\"checkImport();\"");
//add a Description
$Form->set_FormDesc($FormularName,___("Addressen aus CSV-Datei importieren"));
$Form->set_FormType($FormularName,"multipart/form-data");
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "import");

//////////////////
//add inputfields and buttons....
//////////////////
//File 1, csv
$Form->new_Input($FormularName,$InputName_File,"file", "");
$Form->set_InputJS($FormularName,$InputName_File," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_File,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_File,48,256);
$Form->set_InputDesc($FormularName,$InputName_File,___("CSV-Datei hochladen und importieren"));
$Form->set_InputReadonly($FormularName,$InputName_File,false);
$Form->set_InputOrder($FormularName,$InputName_File,1);
$Form->set_InputLabel($FormularName,$InputName_File,"");

//Select existing file
$Form->new_Input($FormularName,$InputName_FileExisting,"select","");
$Form->set_InputJS($FormularName,$InputName_FileExisting," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_FileExisting,$$InputName_FileExisting);
$Form->set_InputStyleClass($FormularName,$InputName_FileExisting,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_FileExisting,___("CSV-Datei auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_FileExisting,false);
$Form->set_InputOrder($FormularName,$InputName_FileExisting,2);
$Form->set_InputLabel($FormularName,$InputName_FileExisting,"");
$Form->set_InputSize($FormularName,$InputName_FileExisting,0,1);
$Form->set_InputMultiple($FormularName,$InputName_FileExisting,false);
//add data
$Form->add_InputOption($FormularName,$InputName_FileExisting,"","--");
$Import_Files=getFiles($tm_datapath) ;
foreach ($Import_Files as $field) {
	$btsort[]=$field['name'];
}
@array_multisort($btsort, SORT_ASC, $Import_Files, SORT_ASC);
$ic= count($Import_Files);
for ($icc=0; $icc < $ic; $icc++) {
	if ($Import_Files[$icc]['name']!=".htaccess" && $Import_Files[$icc]['name']!="index.php" && $Import_Files[$icc]['name']!="index.html") {
		$Form->add_InputOption($FormularName,$InputName_FileExisting,$Import_Files[$icc]['name'],display($Import_Files[$icc]['name']));
	}
}

//Bulk
$Form->new_Input($FormularName,$InputName_Bulk,"textarea", $$InputName_Bulk);
$Form->set_InputDefault($FormularName,$InputName_Bulk,$$InputName_Bulk);
$Form->set_InputStyleClass($FormularName,$InputName_Bulk,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Bulk,64,5);
$Form->set_InputDesc($FormularName,$InputName_Bulk,"");
$Form->set_InputReadonly($FormularName,$InputName_Bulk,false);
$Form->set_InputOrder($FormularName,$InputName_Bulk,3);
$Form->set_InputLabel($FormularName,$InputName_Bulk,"");

//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Gruppen wählen, STRG/CTRL gedrückt halten und klicken f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,4);
$Form->set_InputLabel($FormularName,$InputName_Group,"");
$Form->set_InputSize($FormularName,$InputName_Group,0,20);
$Form->set_InputMultiple($FormularName,$InputName_Group,true);
//add Data
$ADDRESS=new tm_ADR();
$GRP=$ADDRESS->getGroup(0,0,0,1);
$acg=count($GRP);
for ($accg=0; $accg<$acg; $accg++)
{
	$grp_option_text=$GRP[$accg]['name'];
	$grp_option_text.=" (".$GRP[$accg]['adr_count'].")";
	if ($GRP[$accg]['aktiv']!=1) {
		$grp_option_text.=" (na)";
	}
	if ($GRP[$accg]['prod']==1) {
		$grp_option_text.=" (pro)";
	}
	$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],$grp_option_text);
	
}

//merge groups?
$Form->new_Input($FormularName,$InputName_GroupsMerge,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_GroupsMerge," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputDefault($FormularName,$InputName_GroupsMerge,1);
$Form->set_InputStyleClass($FormularName,$InputName_GroupsMerge,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_GroupsMerge,48,256);
$Form->set_InputDesc($FormularName,$InputName_GroupsMerge,___("Gruppen hinzufügen"));
$Form->set_InputReadonly($FormularName,$InputName_GroupsMerge,false);
$Form->set_InputOrder($FormularName,$InputName_GroupsMerge,14);
$Form->set_InputLabel($FormularName,$InputName_GroupsMerge,"");

//Status
$Form->new_Input($FormularName,$InputName_Status,"select", "");
$Form->set_InputJS($FormularName,$InputName_Status," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Status,1);
$Form->set_InputStyleClass($FormularName,$InputName_Status,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Status,___("Adress-Status f. neue Adressen"));
$Form->set_InputReadonly($FormularName,$InputName_Status,false);
$Form->set_InputOrder($FormularName,$InputName_Status,10);
$Form->set_InputLabel($FormularName,$InputName_Status,"");
$Form->set_InputSize($FormularName,$InputName_Status,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Status,false);
//add Data
$sc=count($STATUS['adr']['status']);
for ($scc=1; $scc<=$sc; $scc++) {
	$Form->add_InputOption($FormularName,$InputName_Status,$scc,display($STATUS['adr']['status'][$scc])." (".display($STATUS['adr']['descr'][$scc]).")");
}

//StatusEx
$Form->new_Input($FormularName,$InputName_StatusEx,"select", "");
$Form->set_InputJS($FormularName,$InputName_StatusEx," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_StatusEx,0);
$Form->set_InputStyleClass($FormularName,$InputName_StatusEx,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_StatusEx,___("Adress-Status f. existierende Adressen"));
$Form->set_InputReadonly($FormularName,$InputName_StatusEx,false);
$Form->set_InputOrder($FormularName,$InputName_StatusEx,12);
$Form->set_InputLabel($FormularName,$InputName_StatusEx,"");
$Form->set_InputSize($FormularName,$InputName_StatusEx,0,1);
$Form->set_InputMultiple($FormularName,$InputName_StatusEx,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_StatusEx,0,___("Keine Änderungen"));
$sc=count($STATUS['adr']['status']);
for ($scc=1; $scc<=$sc; $scc++) {
	$Form->add_InputOption($FormularName,$InputName_StatusEx,$scc,display($STATUS['adr']['status'][$scc])." (".display($STATUS['adr']['descr'][$scc]).")");
}

//aktiv neue adr
$Form->new_Input($FormularName,$InputName_AktivNew,"select", "");
$Form->set_InputJS($FormularName,$InputName_AktivNew," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_AktivNew,1);
$Form->set_InputStyleClass($FormularName,$InputName_AktivNew,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_AktivNew,___("Neue Adressen de-/aktivieren"));
$Form->set_InputReadonly($FormularName,$InputName_AktivNew,false);
$Form->set_InputOrder($FormularName,$InputName_AktivNew,11);
$Form->set_InputLabel($FormularName,$InputName_AktivNew,"");
$Form->set_InputSize($FormularName,$InputName_AktivNew,0,1);
$Form->set_InputMultiple($FormularName,$InputName_AktivNew,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_AktivNew,1,___("Aktiv"));
$Form->add_InputOption($FormularName,$InputName_AktivNew,0,___("De-Aktiviert"));

//aktiv existierende adr
$Form->new_Input($FormularName,$InputName_AktivEx,"select", "");
$Form->set_InputJS($FormularName,$InputName_AktivEx," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputDefault($FormularName,$InputName_AktivEx,-1);
$Form->set_InputStyleClass($FormularName,$InputName_AktivEx,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_AktivEx,___("Existierende Adressen bei Update de-/aktivieren"));
$Form->set_InputReadonly($FormularName,$InputName_AktivEx,false);
$Form->set_InputOrder($FormularName,$InputName_AktivEx,13);
$Form->set_InputLabel($FormularName,$InputName_AktivEx,"");
$Form->set_InputSize($FormularName,$InputName_AktivEx,0,1);
$Form->set_InputMultiple($FormularName,$InputName_AktivEx,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_AktivEx,-1,___("Keine Änderungen"));
$Form->add_InputOption($FormularName,$InputName_AktivEx,1,___("Aktiv"));
$Form->add_InputOption($FormularName,$InputName_AktivEx,0,___("De-Aktiviert"));

//trennzeichen
$Form->new_Input($FormularName,$InputName_Delimiter,"select", "");
$Form->set_InputJS($FormularName,$InputName_Delimiter," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Delimiter,$$InputName_Delimiter);
$Form->set_InputStyleClass($FormularName,$InputName_Delimiter,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Delimiter,___("Trennzeichen"));
$Form->set_InputReadonly($FormularName,$InputName_Delimiter,false);
$Form->set_InputOrder($FormularName,$InputName_Delimiter,20);
$Form->set_InputLabel($FormularName,$InputName_Delimiter,"");
$Form->set_InputSize($FormularName,$InputName_Delimiter,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Delimiter,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Delimiter,",",",");
$Form->add_InputOption($FormularName,$InputName_Delimiter,";",";");
$Form->add_InputOption($FormularName,$InputName_Delimiter,"|","|");

//delete
$Form->new_Input($FormularName,$InputName_Delete,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Delete," onChange=\"flash('submit','#ff0000');checkImport();\" onClick=\"checkImport();\"");
$Form->set_InputDefault($FormularName,$InputName_Delete,$$InputName_Delete);
$Form->set_InputStyleClass($FormularName,$InputName_Delete,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Delete,48,256);
$Form->set_InputDesc($FormularName,$InputName_Delete,___("Importierte Adressen löschen"));
$Form->set_InputReadonly($FormularName,$InputName_Delete,false);
$Form->set_InputOrder($FormularName,$InputName_Delete,15);
$Form->set_InputLabel($FormularName,$InputName_Delete,"");

//mark recheck
$Form->new_Input($FormularName,$InputName_MarkRecheck,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_MarkRecheck," onChange=\"flash('submit','#ff0000');checkImport();\" onClick=\"checkImport();\"");
$Form->set_InputDefault($FormularName,$InputName_MarkRecheck,$$InputName_MarkRecheck);
$Form->set_InputStyleClass($FormularName,$InputName_MarkRecheck,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_MarkRecheck,48,256);
$Form->set_InputDesc($FormularName,$InputName_MarkRecheck,___("Importierte Adressen zur Prüfung vormerken"));
$Form->set_InputReadonly($FormularName,$InputName_MarkRecheck,false);
$Form->set_InputOrder($FormularName,$InputName_MarkRecheck,18);
$Form->set_InputLabel($FormularName,$InputName_MarkRecheck,"");


//blacklist
$Form->new_Input($FormularName,$InputName_Blacklist,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Blacklist," onChange=\"flash('submit','#ff0000');checkImport();\" onClick=\"checkImport();\"");
$Form->set_InputDefault($FormularName,$InputName_Blacklist,$$InputName_Blacklist);
$Form->set_InputStyleClass($FormularName,$InputName_Blacklist,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Blacklist,48,256);
$Form->set_InputDesc($FormularName,$InputName_Blacklist,___("Importierte Adressen in die Blacklist eintragen"));
$Form->set_InputReadonly($FormularName,$InputName_Blacklist,false);
$Form->set_InputOrder($FormularName,$InputName_Blacklist,17);
$Form->set_InputLabel($FormularName,$InputName_Blacklist,"");

//blacklist domains
$Form->new_Input($FormularName,$InputName_BlacklistDomains,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_BlacklistDomains," onChange=\"flash('submit','#ff0000');checkImport();\" onClick=\"checkImport();\"");
$Form->set_InputDefault($FormularName,$InputName_BlacklistDomains,$$InputName_BlacklistDomains);
$Form->set_InputStyleClass($FormularName,$InputName_BlacklistDomains,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_BlacklistDomains,48,256);
$Form->set_InputDesc($FormularName,$InputName_BlacklistDomains,___("Domains in die Blacklist eintragen"));
$Form->set_InputReadonly($FormularName,$InputName_BlacklistDomains,false);
$Form->set_InputOrder($FormularName,$InputName_BlacklistDomains,17);
$Form->set_InputLabel($FormularName,$InputName_BlacklistDomains,"");

//Dublettencheck
$Form->new_Input($FormularName,$InputName_DoubleCheck,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_DoubleCheck," onChange=\"flash('submit','#ff0000');");
$Form->set_InputDefault($FormularName,$InputName_DoubleCheck,1);
$Form->set_InputStyleClass($FormularName,$InputName_DoubleCheck,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_DoubleCheck,48,256);
$Form->set_InputDesc($FormularName,$InputName_DoubleCheck,___("Auf doppelt vorhandene Adressen prüfen"));
$Form->set_InputReadonly($FormularName,$InputName_DoubleCheck,false);
$Form->set_InputOrder($FormularName,$InputName_DoubleCheck,17);
$Form->set_InputLabel($FormularName,$InputName_DoubleCheck,"");

//skip exiting ?
$Form->new_Input($FormularName,$InputName_SkipEx,"checkbox", 1);
#$Form->set_InputJS($FormularName,$InputName_Delete," onChange=\"flash('submit','#ff0000');\" onClick=\"del=1; document.form.".$InputName_AktivEx."; document.form.".$InputName_AktivNew."; document.form.".$InputName_StatusEx."; document.form.".$InputName_Group."; \"");
$Form->set_InputJS($FormularName,$InputName_SkipEx," onChange=\"flash('submit','#ff0000');\"");
#onFocus="if (!activ)this.blur();" onChange="if (!activ)this.blur();"
#http://www.javarea.de/index.php3?opencat=Javascript&subcat=Formulare-Button&id=266
$Form->set_InputDefault($FormularName,$InputName_SkipEx,1);
$Form->set_InputStyleClass($FormularName,$InputName_SkipEx,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SkipEx,48,256);
$Form->set_InputDesc($FormularName,$InputName_SkipEx,___("Existierende Adressen überspringen, kein Update"));
$Form->set_InputReadonly($FormularName,$InputName_SkipEx,false);
$Form->set_InputOrder($FormularName,$InputName_SkipEx,18);
$Form->set_InputLabel($FormularName,$InputName_SkipEx,"");

//emailcheck...
$Form->new_Input($FormularName,$InputName_ECheckImport,"select", "");
$Form->set_InputJS($FormularName,$InputName_ECheckImport," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ECheckImport,$EMailcheck_Intern);
$Form->set_InputStyleClass($FormularName,$InputName_ECheckImport,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ECheckImport,"");
$Form->set_InputReadonly($FormularName,$InputName_ECheckImport,false);
$Form->set_InputOrder($FormularName,$InputName_ECheckImport,22);
$Form->set_InputLabel($FormularName,$InputName_ECheckImport,"");
$Form->set_InputSize($FormularName,$InputName_ECheckImport,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ECheckImport,false);
//add Data
$sc=count($EMAILCHECK['intern']);
for ($scc=0; $scc<$sc; $scc++)//0
{
	$Form->add_InputOption($FormularName,$InputName_ECheckImport,$scc,$EMAILCHECK['intern'][$scc]);
}

//offset
$Form->new_Input($FormularName,$InputName_Offset,"text", $$InputName_Offset);
$Form->set_InputJS($FormularName,$InputName_Offset," onChange=\"flash('submit','#ff0000');\" onKeyUp=\"RemoveInvalidChars(this, '[^0-9]');\"");
$Form->set_InputStyleClass($FormularName,$InputName_Offset,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Offset,20,10);
$Form->set_InputDesc($FormularName,$InputName_Offset,___("Offset: Anzahl der Datensätze die übersprungen werden sollen."));
$Form->set_InputReadonly($FormularName,$InputName_Offset,false);
$Form->set_InputOrder($FormularName,$InputName_Offset,30);
$Form->set_InputLabel($FormularName,$InputName_Offset,"");

//limit
$Form->new_Input($FormularName,$InputName_Limit,"text", $$InputName_Limit);
$Form->set_InputJS($FormularName,$InputName_Limit," onChange=\"flash('submit','#ff0000');\" onKeyUp=\"RemoveInvalidChars(this, '[^0-9]');\"");
$Form->set_InputStyleClass($FormularName,$InputName_Limit,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Limit,20,10);
$Form->set_InputDesc($FormularName,$InputName_Limit,___("Limit: Anzahl maximal zu exportierender Datensätze."));
$Form->set_InputReadonly($FormularName,$InputName_Limit,false);
$Form->set_InputOrder($FormularName,$InputName_Limit,31);
$Form->set_InputLabel($FormularName,$InputName_Limit,"");

//proof
$Form->new_Input($FormularName,$InputName_Proof,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Proof," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputDefault($FormularName,$InputName_Proof,1);
$Form->set_InputStyleClass($FormularName,$InputName_Proof,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Proof,48,256);
$Form->set_InputDesc($FormularName,$InputName_Proof,___("Proofing"));
$Form->set_InputReadonly($FormularName,$InputName_Proof,false);
$Form->set_InputOrder($FormularName,$InputName_Proof,40);
$Form->set_InputLabel($FormularName,$InputName_Proof,"");


//new group
$Form->new_Input($FormularName,$InputName_GroupNew,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_GroupNew," onChange=\"flash('submit','#ff0000');\"");
$Form->set_InputDefault($FormularName,$InputName_GroupNew,0);
$Form->set_InputStyleClass($FormularName,$InputName_GroupNew,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_GroupNew,48,256);
$Form->set_InputDesc($FormularName,$InputName_GroupNew,___("Neue Gruppe"));
$Form->set_InputReadonly($FormularName,$InputName_GroupNew,false);
$Form->set_InputOrder($FormularName,$InputName_GroupNew,40);
$Form->set_InputLabel($FormularName,$InputName_GroupNew,"");

//new group name
$Form->new_Input($FormularName,$InputName_GroupNewName,"text", $$InputName_GroupNewName);
$Form->set_InputJS($FormularName,$InputName_GroupNewName," onChange=\"flash('submit','#ff0000');\" onKeyUp=\"RemoveInvalidChars(this, '[^0-9a-zA-Z\-_]');\"");
$Form->set_InputStyleClass($FormularName,$InputName_GroupNewName,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_GroupNewName,20,10);
$Form->set_InputDesc($FormularName,$InputName_GroupNewName,___("Name der neuen Gruppe"));
$Form->set_InputReadonly($FormularName,$InputName_GroupNewName,false);
$Form->set_InputOrder($FormularName,$InputName_GroupNewName,30);
$Form->set_InputLabel($FormularName,$InputName_GroupNewName,"");



//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Adressen Importieren"));
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