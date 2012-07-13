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
$FormularName="log_search";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"get","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");

//add a Description
$Form->set_FormDesc($FormularName,___("Logbucheinträge suchen und filtern"));
$Form->new_Input($FormularName,"act", "hidden", $action);
#$Form->new_Input($FormularName,"set", "hidden", "search");

//////////////////
//add inputfields and buttons....
//////////////////


//Obj
$Form->new_Input($FormularName,$InputName_Obj,"select", "");
$Form->set_InputJS($FormularName,$InputName_Obj," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Obj,$$InputName_Obj);
$Form->set_InputStyleClass($FormularName,$InputName_Obj,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Obj,___("Suche nach Objekt"));
$Form->set_InputReadonly($FormularName,$InputName_Obj,false);
$Form->set_InputOrder($FormularName,$InputName_Obj,6);
$Form->set_InputLabel($FormularName,$InputName_Obj,___("Objekt")."<br>");
$Form->set_InputSize($FormularName,$InputName_Obj,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Obj,false);

$Form->add_InputOption($FormularName,$InputName_Obj,"","-- alle");
$Form->add_InputOption($FormularName,$InputName_Obj,"adr",___("Adressen")." (adr)");
$Form->add_InputOption($FormularName,$InputName_Obj,"adr*",___("Adressen")."* (adr*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"adr_grp",___("Adress-Gruppen")." (adr_grp)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"adr_grp*",___("Adress-Gruppen")."* (adr_grp*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"nl",___("Newsletter")." (nl)");
$Form->add_InputOption($FormularName,$InputName_Obj,"nl*",___("Newsletter")."* (nl*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"nl_grp",___("Newsletter-Gruppen")." (nl_grp)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"nl_grp*",___("Newsletter-Gruppen")."* (nl_grp*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"lnk",___("Links")." (lnk)");
$Form->add_InputOption($FormularName,$InputName_Obj,"lnk_grp",___("Link-Gruppen")." (lnk_grp)");
$Form->add_InputOption($FormularName,$InputName_Obj,"frm",___("Formulare")." (frm)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"frm*",___("Formulare")."* (frm*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"bl",___("Blacklist")." (bl)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"bl*",___("Blacklist")."* (bl*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"q",___("Queue")." (q)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"q*",___("Queue")."* (q*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"usr",___("Benutzer")." (usr)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"usr*",___("Benutzer")."* (usr*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"host",___("Mail-Server")." (host)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"host*",___("Mail-Server")."* (host*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"cfg",___("Einstellungen")." (cfg)");
#$Form->add_InputOption($FormularName,$InputName_Obj,"cfg*",___("Einstellungen")."* (cfg*)");
$Form->add_InputOption($FormularName,$InputName_Obj,"log",___("Logbuch")." (log)");

//Action
$Form->new_Input($FormularName,$InputName_Action,"select", "");
$Form->set_InputJS($FormularName,$InputName_Action," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Action,$$InputName_Action);
$Form->set_InputStyleClass($FormularName,$InputName_Action,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Action,___("Suche nach Aktion"));
$Form->set_InputReadonly($FormularName,$InputName_Action,false);
$Form->set_InputOrder($FormularName,$InputName_Action,6);
$Form->set_InputLabel($FormularName,$InputName_Action,___("Aktion")."<br>");
$Form->set_InputSize($FormularName,$InputName_Action,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Action,false);

$Form->add_InputOption($FormularName,$InputName_Action,"",___("Alle"));
$Form->add_InputOption($FormularName,$InputName_Action,"memo",___("Memos"));
$Form->add_InputOption($FormularName,$InputName_Action,"usage",___("Benutzerverhalten"));
$Form->add_InputOption($FormularName,$InputName_Action,"new",___("Neuanlage"));
$Form->add_InputOption($FormularName,$InputName_Action,"edit",___("Bearbeitet"));
$Form->add_InputOption($FormularName,$InputName_Action,"delete",___("Gelöscht"));

//Author
$Form->new_Input($FormularName,$InputName_Author,"select", "");
$Form->set_InputJS($FormularName,$InputName_Author," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Author,$$InputName_Author);
$Form->set_InputStyleClass($FormularName,$InputName_Author,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Author,___("Suche nach Author/Editor"));
$Form->set_InputReadonly($FormularName,$InputName_Author,false);
$Form->set_InputOrder($FormularName,$InputName_Author,6);
$Form->set_InputLabel($FormularName,$InputName_Author,___("Author")."<br>");
$Form->set_InputSize($FormularName,$InputName_Author,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Author,false);
//add Data
$USERS=$LOGIN->getUsers();
$uc=count($USERS);
$Form->add_InputOption($FormularName,$InputName_Author,"","-- alle");
for ($ucc=0; $ucc<$uc; $ucc++)
{
	$Form->add_InputOption($FormularName,$InputName_Author,$USERS[$ucc]['id'],$USERS[$ucc]['name']);
}

//ID
$Form->new_Input($FormularName,$InputName_EditID,"text", display($$InputName_EditID));
$Form->set_InputJS($FormularName,$InputName_EditID," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_EditID,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_EditID,5,10);
$Form->set_InputDesc($FormularName,$InputName_EditID,___("OID"));
$Form->set_InputReadonly($FormularName,$InputName_EditID,false);
$Form->set_InputOrder($FormularName,$InputName_EditID,1);
$Form->set_InputLabel($FormularName,$InputName_EditID,___("OID")."<br>");


//Limit
$Form->new_Input($FormularName,$InputName_Limit,"select", "");
$Form->set_InputJS($FormularName,$InputName_Limit," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Limit,$limit);
$Form->set_InputStyleClass($FormularName,$InputName_Limit,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Limit,___("Maximale Anzahl Einträge die auf einmal angezeigt werden."));
$Form->set_InputReadonly($FormularName,$InputName_Limit,false);
$Form->set_InputOrder($FormularName,$InputName_Limit,6);
$Form->set_InputLabel($FormularName,$InputName_Limit,___("Limit")."<br>");
$Form->set_InputSize($FormularName,$InputName_Limit,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Limit,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Limit,5,"5 ");
$Form->add_InputOption($FormularName,$InputName_Limit,10,"10 ");
$Form->add_InputOption($FormularName,$InputName_Limit,25,"25 ");
$Form->add_InputOption($FormularName,$InputName_Limit,50,"50 ");
$Form->add_InputOption($FormularName,$InputName_Limit,100,"100 ");

//Anzeigen oder Auswahl löschen?
$Form->new_Input($FormularName,$InputName_Set,"select", "");
$Form->set_InputJS($FormularName,$InputName_Set," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Set,"search");//$$InputName_Set
$Form->set_InputStyleClass($FormularName,$InputName_Set,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Set,___("Aktion"));
$Form->set_InputReadonly($FormularName,$InputName_Set,false);
$Form->set_InputOrder($FormularName,$InputName_Set,20);
$Form->set_InputLabel($FormularName,$InputName_Set,___("Aktion")."<br>");
$Form->set_InputSize($FormularName,$InputName_Set,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Set,false);
$Form->add_InputOption($FormularName,$InputName_Set,"search",___("Auswahl anzeigen"));
$Form->add_InputOption($FormularName,$InputName_Set,"delete",___("Auswahl löschen"));


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