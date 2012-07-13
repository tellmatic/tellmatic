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
$FormularName="frm_new";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("neues Formular erstellen"));
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"frm_id", "hidden", $frm_id);
//////////////////
//add inputfields and buttons....
//////////////////
//Name
$Form->new_Input($FormularName,$InputName_Name,"text", display($$InputName_Name));
$Form->set_InputJS($FormularName,$InputName_Name," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,48,1024);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Name"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"");

//Url
$Form->new_Input($FormularName,$InputName_ActionUrl,"text", display($$InputName_ActionUrl));
$Form->set_InputJS($FormularName,$InputName_ActionUrl," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_ActionUrl,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ActionUrl,48,1024);
$Form->set_InputDesc($FormularName,$InputName_ActionUrl,___("URL"));
$Form->set_InputReadonly($FormularName,$InputName_ActionUrl,false);
$Form->set_InputOrder($FormularName,$InputName_ActionUrl,2);
$Form->set_InputLabel($FormularName,$InputName_ActionUrl,"");


//Aktiv
	$Form->new_Input($FormularName,$InputName_Aktiv,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Aktiv," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Aktiv,$$InputName_Aktiv);
	$Form->set_InputStyleClass($FormularName,$InputName_Aktiv,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Aktiv,48,1024);
	$Form->set_InputDesc($FormularName,$InputName_Aktiv,___("Aktiv"));
	$Form->set_InputReadonly($FormularName,$InputName_Aktiv,false);
	$Form->set_InputOrder($FormularName,$InputName_Aktiv,3);
	$Form->set_InputLabel($FormularName,$InputName_Aktiv,"");

//Subscribed Adresses Aktiv
	$Form->new_Input($FormularName,$InputName_SubAktiv,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_SubAktiv," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_SubAktiv,$$InputName_SubAktiv);
	$Form->set_InputStyleClass($FormularName,$InputName_SubAktiv,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_SubAktiv,48,1024);
	$Form->set_InputDesc($FormularName,$InputName_SubAktiv,___("Anmeldungen sind aktiv/inaktiv"));
	$Form->set_InputReadonly($FormularName,$InputName_SubAktiv,false);
	$Form->set_InputOrder($FormularName,$InputName_SubAktiv,13);
	$Form->set_InputLabel($FormularName,$InputName_SubAktiv,"");

//Check Blacklist
	$Form->new_Input($FormularName,$InputName_Blacklist,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Blacklist," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Blacklist,$$InputName_Blacklist);
	$Form->set_InputStyleClass($FormularName,$InputName_Blacklist,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Blacklist,48,1024);
	$Form->set_InputDesc($FormularName,$InputName_Blacklist,___("Blacklist"));
	$Form->set_InputReadonly($FormularName,$InputName_Blacklist,false);
	$Form->set_InputOrder($FormularName,$InputName_Blacklist,7);
	$Form->set_InputLabel($FormularName,$InputName_Blacklist,"");

//do proofing
	$Form->new_Input($FormularName,$InputName_Proof,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_Proof," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_Proof,$$InputName_Proof);
	$Form->set_InputStyleClass($FormularName,$InputName_Proof,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_Proof,48,1024);
	$Form->set_InputDesc($FormularName,$InputName_Proof,___("Proofing"));
	$Form->set_InputReadonly($FormularName,$InputName_Proof,false);
	$Form->set_InputOrder($FormularName,$InputName_Proof,7);
	$Form->set_InputLabel($FormularName,$InputName_Proof,"");


//Force Publicgroups selection
	$Form->new_Input($FormularName,$InputName_ForcePubGroup,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_ForcePubGroup," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_ForcePubGroup,$$InputName_ForcePubGroup);
	$Form->set_InputStyleClass($FormularName,$InputName_ForcePubGroup,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_ForcePubGroup,48,1024);
	$Form->set_InputDesc($FormularName,$InputName_ForcePubGroup,___("Auswahl erzwingen"));
	$Form->set_InputReadonly($FormularName,$InputName_ForcePubGroup,false);
	$Form->set_InputOrder($FormularName,$InputName_ForcePubGroup,9);
	$Form->set_InputLabel($FormularName,$InputName_ForcePubGroup,"");

//Overwrite Publicgroups selection or update?
$Form->new_Input($FormularName,$InputName_OverwritePubgroup,"select", "");
$Form->set_InputJS($FormularName,$InputName_OverwritePubgroup," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_OverwritePubgroup,$$InputName_OverwritePubgroup);
$Form->set_InputStyleClass($FormularName,$InputName_OverwritePubgroup,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_OverwritePubgroup,___("Auswahl überschreiben oder aktualisieren?"));
$Form->set_InputReadonly($FormularName,$InputName_OverwritePubgroup,false);
$Form->set_InputOrder($FormularName,$InputName_OverwritePubgroup,11);
$Form->set_InputLabel($FormularName,$InputName_OverwritePubgroup,"");
$Form->set_InputSize($FormularName,$InputName_OverwritePubgroup,0,1);
$Form->set_InputMultiple($FormularName,$InputName_OverwritePubgroup,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_OverwritePubgroup,0,___("Nur Neue Gruppen hinzufügen"));
$Form->add_InputOption($FormularName,$InputName_OverwritePubgroup,1,___("Gruppenauswahl überschreiben"));
//Use Captcha
	$Form->new_Input($FormularName,$InputName_UseCaptcha,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_UseCaptcha," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_UseCaptcha,$$InputName_UseCaptcha);
	$Form->set_InputStyleClass($FormularName,$InputName_UseCaptcha,"mFormText","mFormTextFocus");
	$Form->set_InputDesc($FormularName,$InputName_UseCaptcha,___("Captcha prüfen"));
	$Form->set_InputReadonly($FormularName,$InputName_UseCaptcha,false);
	$Form->set_InputOrder($FormularName,$InputName_UseCaptcha,5);
	$Form->set_InputLabel($FormularName,$InputName_UseCaptcha,"");

//DigitsCaptcha
$Form->new_Input($FormularName,$InputName_DigitsCaptcha,"select", "");
$Form->set_InputJS($FormularName,$InputName_DigitsCaptcha," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_DigitsCaptcha,$$InputName_DigitsCaptcha);
$Form->set_InputStyleClass($FormularName,$InputName_DigitsCaptcha,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_DigitsCaptcha,___("Ziffern"));
$Form->set_InputReadonly($FormularName,$InputName_DigitsCaptcha,false);
$Form->set_InputOrder($FormularName,$InputName_DigitsCaptcha,6);
$Form->set_InputLabel($FormularName,$InputName_DigitsCaptcha,"");
$Form->set_InputSize($FormularName,$InputName_DigitsCaptcha,0,1);
$Form->set_InputMultiple($FormularName,$InputName_DigitsCaptcha,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,4,"4");
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,5,"5");
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,6,"6");
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,7,"7");
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,8,"8");
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,9,"9");
$Form->add_InputOption($FormularName,$InputName_DigitsCaptcha,10,"10");


//double optin
	$Form->new_Input($FormularName,$InputName_DoubleOptin,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_DoubleOptin," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_DoubleOptin,$$InputName_DoubleOptin);
	$Form->set_InputStyleClass($FormularName,$InputName_DoubleOptin,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_DoubleOptin,0,1);
	$Form->set_InputDesc($FormularName,$InputName_DoubleOptin,___("Aktiv"));
	$Form->set_InputReadonly($FormularName,$InputName_DoubleOptin,false);
	$Form->set_InputOrder($FormularName,$InputName_DoubleOptin,4);
	$Form->set_InputLabel($FormularName,$InputName_DoubleOptin,"");


//Multiple Pubgroups?
	$Form->new_Input($FormularName,$InputName_MultiPubGroup,"checkbox", 1);
	$Form->set_InputJS($FormularName,$InputName_MultiPubGroup," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$InputName_MultiPubGroup,$$InputName_MultiPubGroup);
	$Form->set_InputStyleClass($FormularName,$InputName_MultiPubGroup,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$InputName_MultiPubGroup,48,1024);
	$Form->set_InputDesc($FormularName,$InputName_MultiPubGroup,___("Besucher kann mehrere Gruppen wählen"));
	$Form->set_InputReadonly($FormularName,$InputName_MultiPubGroup,false);
	$Form->set_InputOrder($FormularName,$InputName_MultiPubGroup,3);
	$Form->set_InputLabel($FormularName,$InputName_MultiPubGroup,"");

//NL send for double optin
$Form->new_Input($FormularName,$InputName_NLDOptin,"select", "");
$Form->set_InputJS($FormularName,$InputName_NLDOptin," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_NLDOptin,$nl_id_doptin);
$Form->set_InputStyleClass($FormularName,$InputName_NLDOptin,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_NLDOptin,___("Newsletter wählen"));
$Form->set_InputReadonly($FormularName,$InputName_NLDOptin,false);
$Form->set_InputOrder($FormularName,$InputName_NLDOptin,14);
$Form->set_InputLabel($FormularName,$InputName_NLDOptin,"");
$Form->set_InputSize($FormularName,$InputName_NLDOptin,0,1);
$Form->set_InputMultiple($FormularName,$InputName_NLDOptin,false);
//add Data
$NEWSLETTER=new tm_NL();
#$NL=$NEWSLETTER->getNL();
//nur aktive, keine templates
$NL=$NEWSLETTER->getNL(0,0,0,0,0,$sortIndex="id",$sortType=1,Array("aktiv"=>1, "is_template"=>1));//, "massmail"=>0 does not work yet
$nc=count($NL);
$Form->add_InputOption($FormularName,$InputName_NLDOptin,0,"--");
for ($ncc=0; $ncc<$nc; $ncc++)
{
	//only use personalized mailing!
	if ($NL[$ncc]['massmail']==0) {
		//nur nl mit existierenden templates f. html/textparts
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html") 
			&& file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt")) {
			$NLSubj=display($NL[$ncc]['subject']);
			$Form->add_InputOption($FormularName,$InputName_NLDOptin,$NL[$ncc]['id'],$NLSubj);
		}
	}
}

//NL send as greeting mail
$Form->new_Input($FormularName,$InputName_NLGreeting,"select", "");
$Form->set_InputJS($FormularName,$InputName_NLGreeting," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_NLGreeting,$nl_id_greeting);
$Form->set_InputStyleClass($FormularName,$InputName_NLGreeting,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_NLGreeting,___("Newsletter wählen"));
$Form->set_InputReadonly($FormularName,$InputName_NLGreeting,false);
$Form->set_InputOrder($FormularName,$InputName_NLGreeting,15);
$Form->set_InputLabel($FormularName,$InputName_NLGreeting,"");
$Form->set_InputSize($FormularName,$InputName_NLGreeting,0,1);
$Form->set_InputMultiple($FormularName,$InputName_NLGreeting,false);
//add Data
$NEWSLETTER=new tm_NL();
//nur aktive, keine templates
$NL=$NEWSLETTER->getNL(0,0,0,0,0,$sortIndex="id",$sortType=1,Array("aktiv"=>1, "is_template"=>1));//, "massmail"=>0 does not work yet
$nc=count($NL);
$Form->add_InputOption($FormularName,$InputName_NLGreeting,0,"--");
for ($ncc=0; $ncc<$nc; $ncc++)
{
	//only use personalized mailing!
	if ($NL[$ncc]['massmail']==0) {
		//nur nl mit existierenden templates f. html/textparts
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html") 
			&& file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt")) {
			$NLSubj=display($NL[$ncc]['subject']);
			$Form->add_InputOption($FormularName,$InputName_NLGreeting,$NL[$ncc]['id'],$NLSubj);
		}
	}
}

//NL send as update mail
$Form->new_Input($FormularName,$InputName_NLUpdate,"select", "");
$Form->set_InputJS($FormularName,$InputName_NLUpdate," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_NLUpdate,$nl_id_update);
$Form->set_InputStyleClass($FormularName,$InputName_NLUpdate,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_NLUpdate,___("Newsletter wählen"));
$Form->set_InputReadonly($FormularName,$InputName_NLUpdate,false);
$Form->set_InputOrder($FormularName,$InputName_NLUpdate,15);
$Form->set_InputLabel($FormularName,$InputName_NLUpdate,"");
$Form->set_InputSize($FormularName,$InputName_NLUpdate,0,1);
$Form->set_InputMultiple($FormularName,$InputName_NLUpdate,false);
//add Data
$NEWSLETTER=new tm_NL();
//nur aktive, keine templates
$NL=$NEWSLETTER->getNL(0,0,0,0,0,$sortIndex="id",$sortType=1,Array("aktiv"=>1, "is_template"=>1));//, "massmail"=>0 does not work yet
$nc=count($NL);
$Form->add_InputOption($FormularName,$InputName_NLUpdate,0,"--");
for ($ncc=0; $ncc<$nc; $ncc++)
{
	//only use personalized mailing!
	if ($NL[$ncc]['massmail']==0) {
		//nur nl mit existierenden templates f. html/textparts
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html") 
			&& file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt")) {
			$NLSubj=display($NL[$ncc]['subject']);
			$Form->add_InputOption($FormularName,$InputName_NLUpdate,$NL[$ncc]['id'],$NLSubj);
		}
	}
}

//Beschreibung
$Form->new_Input($FormularName,$InputName_Descr,"textarea", display($$InputName_Descr));
$Form->set_InputJS($FormularName,$InputName_Descr," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Descr,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_Descr,20,3);
$Form->set_InputDesc($FormularName,$InputName_Descr,___("Beschreibung"));
$Form->set_InputReadonly($FormularName,$InputName_Descr,false);
$Form->set_InputOrder($FormularName,$InputName_Descr,13);
$Form->set_InputLabel($FormularName,$InputName_Descr,"");

//Double Optin Message
$Form->new_Input($FormularName,$InputName_MessageDOptin,"textarea", display($$InputName_MessageDOptin));
$Form->set_InputJS($FormularName,$InputName_MessageDOptin," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_MessageDOptin,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_MessageDOptin,20,3);
$Form->set_InputDesc($FormularName,$InputName_MessageDOptin,___("Meldung bei Double-Opt-In"));
$Form->set_InputReadonly($FormularName,$InputName_MessageDOptin,false);
$Form->set_InputOrder($FormularName,$InputName_MessageDOptin,13);
$Form->set_InputLabel($FormularName,$InputName_MessageDOptin,"");
//Welcome Message
$Form->new_Input($FormularName,$InputName_MessageGreeting,"textarea", display($$InputName_MessageGreeting));
$Form->set_InputJS($FormularName,$InputName_MessageGreeting," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_MessageGreeting,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_MessageGreeting,20,3);
$Form->set_InputDesc($FormularName,$InputName_MessageGreeting,___("Meldung bei Neueintrag"));
$Form->set_InputReadonly($FormularName,$InputName_MessageGreeting,false);
$Form->set_InputOrder($FormularName,$InputName_MessageGreeting,13);
$Form->set_InputLabel($FormularName,$InputName_MessageGreeting,"");
//Update Message
$Form->new_Input($FormularName,$InputName_MessageUpdate,"textarea", display($$InputName_MessageUpdate));
$Form->set_InputJS($FormularName,$InputName_MessageUpdate," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_MessageUpdate,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_MessageUpdate,20,3);
$Form->set_InputDesc($FormularName,$InputName_MessageUpdate,___("Meldung bei Aktualisierung"));
$Form->set_InputReadonly($FormularName,$InputName_MessageUpdate,false);
$Form->set_InputOrder($FormularName,$InputName_MessageUpdate,13);
$Form->set_InputLabel($FormularName,$InputName_MessageUpdate,"");

//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Gruppen wählen, STRG/CTRL gedrückt halten und klicken f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,12);
$Form->set_InputLabel($FormularName,$InputName_Group,"");
$Form->set_InputSize($FormularName,$InputName_Group,0,10);
$Form->set_InputMultiple($FormularName,$InputName_Group,true);
//add Data
$ADDRESS=new tm_ADR();
$GRP=$ADDRESS->getGroup(0,0,0,1);
$acg=count($GRP);
for ($accg=0; $accg<$acg; $accg++)
{
	$grp_name=$GRP[$accg]['name'];
	if ($GRP[$accg]['aktiv']!=1) $grp_name.=" (na)";
	if ($GRP[$accg]['public']==1) $grp_name.=" (p)";
	if ($GRP[$accg]['prod']==1) $grp_name.=" (pr)";
	$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],$grp_name." (".$GRP[$accg]['adr_count'].")");
}

//Public Group, subscriber can choose
$Form->new_Input($FormularName,$InputName_GroupPub,"select", "");
$Form->set_InputJS($FormularName,$InputName_GroupPub," onChange=\"flash('submit','#ff0000');\" ");
//$Form->set_InputDefault($FormularName,$InputName_GroupPub,$adr_grp_pub);
$Form->set_InputStyleClass($FormularName,$InputName_GroupPub,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_GroupPub,___("Gruppen wählen, STRG/CTRL gedrückt halten und klicken f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_GroupPub,false);
$Form->set_InputOrder($FormularName,$InputName_GroupPub,13);
$Form->set_InputLabel($FormularName,$InputName_GroupPub,"");
$Form->set_InputSize($FormularName,$InputName_GroupPub,0,5);
$Form->set_InputMultiple($FormularName,$InputName_GroupPub,true);
//add Data
$ADDRESS=new tm_ADR();
$GRPPUB=$ADDRESS->getGroup(0,0,0,1,Array("public"=>1));
$acgp=count($GRPPUB);
for ($accgp=0; $accgp<$acgp; $accgp++)
{
		$grp_name=$GRPPUB[$accgp]['name'];
		if ($GRPPUB[$accgp]['aktiv']!=1) $grp_name.=" (na)";
		if ($GRP[$accgp]['prod']==1) $grp_name.=" (pr)";
		$Form->add_InputOption($FormularName,$InputName_GroupPub,$GRPPUB[$accgp]['id'],$grp_name." [".$GRPPUB[$accgp]['public_name']."] (".$GRPPUB[$accgp]['adr_count'].")");
}

//SubmitValue
$Form->new_Input($FormularName,$InputName_SubmitValue,"text", display($$InputName_SubmitValue));
$Form->set_InputJS($FormularName,$InputName_SubmitValue," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_SubmitValue,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_SubmitValue,32,255);
$Form->set_InputDesc($FormularName,$InputName_SubmitValue,"");
$Form->set_InputReadonly($FormularName,$InputName_SubmitValue,false);
$Form->set_InputOrder($FormularName,$InputName_SubmitValue,300);
$Form->set_InputLabel($FormularName,$InputName_SubmitValue,"");

//ResetValue
$Form->new_Input($FormularName,$InputName_ResetValue,"text", display($$InputName_ResetValue));
$Form->set_InputJS($FormularName,$InputName_ResetValue," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_ResetValue,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ResetValue,32,255);
$Form->set_InputDesc($FormularName,$InputName_ResetValue,"");
$Form->set_InputReadonly($FormularName,$InputName_ResetValue,false);
$Form->set_InputOrder($FormularName,$InputName_ResetValue,301);
$Form->set_InputLabel($FormularName,$InputName_ResetValue,"");

//HOST
$Form->new_Input($FormularName,$InputName_Host,"select", "");
$Form->set_InputJS($FormularName,$InputName_Host," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Host,$$InputName_Host);
$Form->set_InputStyleClass($FormularName,$InputName_Host,"mFormSelect","mFormSelectFocus");
$Form->set_InputSize($FormularName,$InputName_Host,1,1);
$Form->set_InputDesc($FormularName,$InputName_Host,___("SMTP Server auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_Host,false);
$Form->set_InputOrder($FormularName,$InputName_Host,9);
$Form->set_InputLabel($FormularName,$InputName_Host,"");
$Form->set_InputMultiple($FormularName,$InputName_Host,false);
#Hostliste....
//smtp hosts
$HOSTS=new tm_Host();
$HOST_=$HOSTS->getHost(0,Array("aktiv"=>1, "type"=>"smtp"));//id,filter
$hcg=count($HOST_);
for ($hccg=0; $hccg<$hcg; $hccg++)
{
		$Form->add_InputOption($FormularName,$InputName_Host,$HOST_[$hccg]['id'],display($HOST_[$hccg]['name']));
}

//Femailname
$Form->new_Input($FormularName,$InputName_email,"text", display($$InputName_email));
$Form->set_InputJS($FormularName,$InputName_email," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_email,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_email,32,255);
$Form->set_InputDesc($FormularName,$InputName_email,"email");
$Form->set_InputReadonly($FormularName,$InputName_email,false);
$Form->set_InputOrder($FormularName,$InputName_email,21);
$Form->set_InputLabel($FormularName,$InputName_email,"");

//F, FName, f0-f9 new:
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc;
	$Form->new_Input($FormularName,$$FInputName,"text", display($$$FInputName));
	$Form->set_InputJS($FormularName,$$FInputName," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputStyleClass($FormularName,$$FInputName,"mFormText","mFormTextFocus");
	$Form->set_InputSize($FormularName,$$FInputName,32,1024);
	$Form->set_InputDesc($FormularName,$$FInputName,"F".$fc);
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,100+($fc*10)+2);
	$Form->set_InputLabel($FormularName,$$FInputName,"");
}


/////////////////////////// f type
//Typ //checkbox, text,select, new, f0-f9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc."_type";
	$Form->new_Input($FormularName,$$FInputName,"select", "");
	$Form->set_InputJS($FormularName,$$FInputName," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$$FInputName,$$$FInputName);
	$Form->set_InputStyleClass($FormularName,$$FInputName,"mFormSelect","mFormSelectFocus");
	$Form->set_InputDesc($FormularName,$$FInputName,___("Typ wählen"));
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,100+($fc*10)+1);
	$Form->set_InputLabel($FormularName,$$FInputName,"");
	//$Form->set_InputValue($FormularName,$$FInputName,"");
	$Form->set_InputSize($FormularName,$$FInputName,0,1);
	$Form->set_InputMultiple($FormularName,$$FInputName,false);
	//add Data
	$Form->add_InputOption($FormularName,$$FInputName,"text","TEXT");
	$Form->add_InputOption($FormularName,$$FInputName,"checkbox","CHECKBOX");
	$Form->add_InputOption($FormularName,$$FInputName,"select","SELECT");
}

/////////////////////////// f required
//required, new, f0-f9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc."_required";
	$Form->new_Input($FormularName,$$FInputName,"checkbox", 1);
	$Form->set_InputJS($FormularName,$$FInputName," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputDefault($FormularName,$$FInputName,$$$FInputName);
	$Form->set_InputStyleClass($FormularName,$$FInputName,"mFormText","mFormTextFocus");
	$Form->set_InputDesc($FormularName,$$FInputName,___("Pflichtfeld"));
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,100+($fc*10)+0);
	$Form->set_InputLabel($FormularName,$$FInputName,"");
}

/////////////////////////////////////////f values
//Values, new, f0-f9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc."_value";
	$Form->new_Input($FormularName,$$FInputName,"text", display($$$FInputName));
	$Form->set_InputJS($FormularName,$$FInputName," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputStyleClass($FormularName,$$FInputName,"mFormText","mFormTextFocus_wide");
	$Form->set_InputSize($FormularName,$$FInputName,32,8192);
	$Form->set_InputDesc($FormularName,$$FInputName,___("Werte, (Trennzeichen ; (Semikolon)"));
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,100+($fc*10)+3);
	$Form->set_InputLabel($FormularName,$$FInputName,"");
}
////////////////////////////////// error messages
//Email ErrMsg
$Form->new_Input($FormularName,$InputName_email_errmsg,"text", display($$InputName_email_errmsg));
$Form->set_InputJS($FormularName,$InputName_email_errmsg," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_email_errmsg,"mFormText","mFormTextFocus_wide");
$Form->set_InputSize($FormularName,$InputName_email_errmsg,32,255);
$Form->set_InputDesc($FormularName,$InputName_email_errmsg,___("Fehlermeldung"));
$Form->set_InputReadonly($FormularName,$InputName_email_errmsg,false);
$Form->set_InputOrder($FormularName,$InputName_email_errmsg,22);
$Form->set_InputLabel($FormularName,$InputName_email_errmsg,"");

//Captcha ErrMsg
$Form->new_Input($FormularName,$InputName_captcha_errmsg,"text", display($$InputName_captcha_errmsg));
$Form->set_InputJS($FormularName,$InputName_captcha_errmsg," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_captcha_errmsg,"mFormText","mFormTextFocus_wide");
$Form->set_InputSize($FormularName,$InputName_captcha_errmsg,32,255);
$Form->set_InputDesc($FormularName,$InputName_captcha_errmsg,___("Fehlermeldung"));
$Form->set_InputReadonly($FormularName,$InputName_captcha_errmsg,false);
$Form->set_InputOrder($FormularName,$InputName_captcha_errmsg,7);
$Form->set_InputLabel($FormularName,$InputName_captcha_errmsg,"");

//Blacklist ErrMsg
$Form->new_Input($FormularName,$InputName_Blacklist_errmsg,"text", display($$InputName_Blacklist_errmsg));
$Form->set_InputJS($FormularName,$InputName_Blacklist_errmsg," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Blacklist_errmsg,"mFormText","mFormTextFocus_wide");
$Form->set_InputSize($FormularName,$InputName_Blacklist_errmsg,32,255);
$Form->set_InputDesc($FormularName,$InputName_Blacklist_errmsg,___("Fehlermeldung"));
$Form->set_InputReadonly($FormularName,$InputName_Blacklist_errmsg,false);
$Form->set_InputOrder($FormularName,$InputName_Blacklist_errmsg,9);
$Form->set_InputLabel($FormularName,$InputName_Blacklist_errmsg,"");

//PubgGroup ErrMsg, i force_pubgroup=1
$Form->new_Input($FormularName,$InputName_PubGroup_errmsg,"text", display($$InputName_PubGroup_errmsg));
$Form->set_InputJS($FormularName,$InputName_PubGroup_errmsg," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_PubGroup_errmsg,"mFormText","mFormTextFocus_wide");
$Form->set_InputSize($FormularName,$InputName_PubGroup_errmsg,32,255);
$Form->set_InputDesc($FormularName,$InputName_PubGroup_errmsg,___("Fehlermeldung"));
$Form->set_InputReadonly($FormularName,$InputName_PubGroup_errmsg,false);
$Form->set_InputOrder($FormularName,$InputName_PubGroup_errmsg,11);
$Form->set_InputLabel($FormularName,$InputName_PubGroup_errmsg,"");
/////////////////////////////////////////
//F ErrMsg, new, f0-f9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc."_errmsg";
	$Form->new_Input($FormularName,$$FInputName,"text", display($$$FInputName));
	$Form->set_InputJS($FormularName,$$FInputName," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputStyleClass($FormularName,$$FInputName,"mFormText","mFormTextFocus_wide");
	$Form->set_InputSize($FormularName,$$FInputName,32,255);
	$Form->set_InputDesc($FormularName,$$FInputName,___("Fehlermeldung"));
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,100+($fc*10)+4);
	$Form->set_InputLabel($FormularName,$$FInputName,"");
}

////////////////////////////////////

//F Expression, new, f0-f9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc."_expr";
	$Form->new_Input($FormularName,$$FInputName,"text", display($$$FInputName));
	$Form->set_InputJS($FormularName,$$FInputName," onChange=\"flash('submit','#ff0000');\" ");
	$Form->set_InputStyleClass($FormularName,$$FInputName,"mFormText","mFormTextFocus_wide");
	$Form->set_InputSize($FormularName,$$FInputName,32,255);
	$Form->set_InputDesc($FormularName,$$FInputName,___("Regulärer Ausdruck"));
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,100+($fc*10)+5);
	$Form->set_InputLabel($FormularName,$$FInputName,"");
}

/////////////////////////////////////

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
$Form->set_InputDesc($FormularName,$InputName_Reset,___("Eingaben zurücksetzen"));
$Form->set_InputReadonly($FormularName,$InputName_Reset,false);
$Form->set_InputOrder($FormularName,$InputName_Reset,999);
$Form->set_InputLabel($FormularName,$InputName_Reset,"");
?>