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
$FormularName="subscribe".$FRM[0]['id'];
//make new Form

//action url: 
if (!empty($FRM[0]['action_url'])) {
	$FURL=$FRM[0]['action_url'];
} else {
	$FURL=$_SERVER["PHP_SELF"];
}
$Form->new_Form($FormularName,$FURL,"post","_self");
//add a Description
$Form->set_FormDesc($FormularName,"subscribe");
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"fid", "hidden", $frm_id);
$Form->new_Input($FormularName,"cpt", "hidden", $captcha_md5);
//////////////////
//add inputfields and buttons....
//////////////////
//EMAIL
$Form->new_Input($FormularName,$InputName_Name,"text", display($$InputName_Name));
$Form->set_InputStyleClass($FormularName,$InputName_Name,"tm_form_email","tm_form_focus_email");
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
//MEMO
$Form->new_Input($FormularName,$InputName_Memo,"textarea", display($$InputName_Memo));
$Form->set_InputDefault($FormularName,$InputName_Memo,$$InputName_Memo);
$Form->set_InputStyleClass($FormularName,$InputName_Memo,"tm_form_memo","tm_form_focus_memo");
$Form->set_InputSize($FormularName,$InputName_Memo,48,5);
$Form->set_InputDesc($FormularName,$InputName_Memo,"");
$Form->set_InputReadonly($FormularName,$InputName_Memo,false);
$Form->set_InputOrder($FormularName,$InputName_Memo,777);
$Form->set_InputLabel($FormularName,$InputName_Memo,"");

//F, neu f0-9
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc;
	if ($FRM[0]['f'.$fc.'_type']=="checkbox") {
		//das bewirkt das die checkbox per default nicht angewaehlt ist, und der wert beim naechsten aufruf vorhanden ist...
		$Form->new_Input($FormularName,$$FInputName,$FRM[0]['f'.$fc.'_type'], 1);
	} else {
		$Form->new_Input($FormularName,$$FInputName,$FRM[0]['f'.$fc.'_type'], display($$$FInputName));
	}
	$Form->set_InputDefault($FormularName,$$FInputName,display($$$FInputName));
	$Form->set_InputStyleClass($FormularName,$$FInputName,"tm_form_f".$fc,"tm_form_focus_f".$fc);
	$Form->set_InputSize($FormularName,$$FInputName,48,256);
	$Form->set_InputDesc($FormularName,$$FInputName,"");
	$Form->set_InputReadonly($FormularName,$$FInputName,false);
	$Form->set_InputOrder($FormularName,$$FInputName,($fc+2));
	$Form->set_InputLabel($FormularName,$$FInputName,"");
	if ($FRM[0]['f'.$fc.'_type']=="select" && !empty($FRM[0]['f'.$fc.'_value'])) {
		$Form->set_InputMultiple($FormularName,$$FInputName,false);
		$Form->set_InputSize($FormularName,$$FInputName,1,1);
		$val=Array();
		$val=explode(";",$FRM[0]['f'.$fc.'_value']);
		foreach ($val as $value) {
			$Form->add_InputOption($FormularName,$$FInputName,$value,display($value));
		}
		unset($val);
	}//if type=select && !empty value
}//for fc

//Public Groups Checkboxes....
//$InputName_GroupPub="adr_grp_pub";
//Public Group, subscriber can choose
$Form->new_Input($FormularName,$InputName_GroupPub,"select", "");
$Form->set_InputJS($FormularName,$InputName_GroupPub," onChange=\"flash('submit','#ff0000');\" ");
//$Form->set_InputDefault($FormularName,$InputName_GroupPub,$adr_grp_pub);
$Form->set_InputStyleClass($FormularName,$InputName_GroupPub,"tm_form_group_select","tm_form_focus_group_select");
#$Form->set_InputDesc($FormularName,$InputName_GroupPub,___("Gruppen wählen, STRG/CTRL gedrückt halten und klicken f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_GroupPub,false);
$Form->set_InputOrder($FormularName,$InputName_GroupPub,111);
$Form->set_InputLabel($FormularName,$InputName_GroupPub,"");
//allow multiple public groups?
if ($FRM[0]['multiple_pubgroup'] == 1) {
	$Form->set_InputMultiple($FormularName,$InputName_GroupPub,TRUE);
} else {
	$Form->set_InputMultiple($FormularName,$InputName_GroupPub,FALSE);
}
//add Data
$ADDRESS=new tm_ADR();
$GRPPUB=$ADDRESS->getGroup(0,0,$frm_id,0,Array("public_frm_ref"=>1,"aktiv"=>1,"public"=>1));
$acgp=count($GRPPUB);
//set size after counting... no more than 5 rows (only if select, not option. we do not have options yet, but may come)
if ($acgp <5) {
	$Form->set_InputSize($FormularName,$InputName_GroupPub,0,5);
} else {
	$Form->set_InputSize($FormularName,$InputName_GroupPub,0,$acgp);
}

for ($accgp=0; $accgp<$acgp; $accgp++)
{
		$Form->add_InputOption($FormularName,$InputName_GroupPub,$GRPPUB[$accgp]['id'],$GRPPUB[$accgp]['public_name']);
		
		$FGROUPDESCR.="<strong>".display($GRPPUB[$accgp]['public_name'])."</strong><br>";
		if (!empty($GRPPUB[$accgp]['descr'])) $FGROUPDESCR.=display($GRPPUB[$accgp]['descr'])."<br>";
}

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",display($FRM[0]['submit_value']));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"tm_form_submit","tm_form_focus_submit");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");
//a reset button
$Form->new_Input($FormularName,$InputName_Reset,"reset",display($FRM[0]['reset_value']));
$Form->set_InputStyleClass($FormularName,$InputName_Reset,"tm_form_reset","tm_form_focus_reset");
$Form->set_InputDesc($FormularName,$InputName_Reset,"Reset");
$Form->set_InputReadonly($FormularName,$InputName_Reset,false);
$Form->set_InputOrder($FormularName,$InputName_Reset,999);
$Form->set_InputLabel($FormularName,$InputName_Reset,"");

/*RENDER FORM*/
$Form->render_Form($FormularName);
/*DISPLAY*/
//form head
$FHEAD= $Form->FORM[$FormularName]['head'];
//+some hidden fields
$FHEAD.= $Form->INPUT[$FormularName]['set']['html'];
$FHEAD.= $Form->INPUT[$FormularName]['fid']['html'];//form id
$FHEAD.= $Form->INPUT[$FormularName]['cpt']['html'];//captcha
//email
$FEMAIL= $Form->INPUT[$FormularName][$InputName_Name]['html'];
//captcha
$FCAPTCHA= $Form->INPUT[$FormularName][$InputName_Captcha]['html'];;
//render f0-9, set template vars
for ($fc=0;$fc<=9;$fc++) {
	$FInputName="InputName_F".$fc;
	$F="F".$fc;
	$$F="";
	if (!empty($FRM[0]['f'.$fc])) {
		$$F= $Form->INPUT[$FormularName][$$FInputName]['html'];
	}
}
//memo
$FMEMO= $Form->INPUT[$FormularName][$InputName_Memo]['html'];
//memo
$FGROUP= $Form->INPUT[$FormularName][$InputName_GroupPub]['html'];
//submit button
$FSUBMIT= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
//reset button
$FRESET= $Form->INPUT[$FormularName][$InputName_Reset]['html'];
//form foot
$FFOOT=$Form->FORM[$FormularName]['foot'];
?>