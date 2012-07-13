<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/10 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/*******************************************************************************/
#$AGroups=$ADDRESS->getGroup(0,$ADR[0]['id'],0,0,Array("public_frm_ref"=>1,"aktiv"=>1,"public"=>1));
$AGroups=$ADDRESS->getGroup(0,$ADR[0]['id'],$frm_id,0,Array("public_frm_ref"=>1,"aktiv"=>1,"public"=>1));
$FGROUP="";
foreach ($AGroups as $AGroup) {
	$FGROUP .= display($AGroup['name'])."<br>";
}

$_Tpl_FRM->setParseValue("FMESSAGE", $MESSAGE);
$_Tpl_FRM->setParseValue("FNAME", $FRM[0]['name']);
$_Tpl_FRM->setParseValue("FDESCR", $FRM[0]['descr']);
$_Tpl_FRM->setParseValue("FEMAIL", display($ADR[0]['email']));
$_Tpl_FRM->setParseValue("FEMAILNAME", $FRM[0]['email']);
$_Tpl_FRM->setParseValue("FGROUP", $FGROUP);

$_Tpl_FRM->setParseValue("F0", display($ADR[0]['f0']));
$_Tpl_FRM->setParseValue("F1", display($ADR[0]['f1']));
$_Tpl_FRM->setParseValue("F2", display($ADR[0]['f2']));
$_Tpl_FRM->setParseValue("F3", display($ADR[0]['f3']));
$_Tpl_FRM->setParseValue("F4", display($ADR[0]['f4']));
$_Tpl_FRM->setParseValue("F5", display($ADR[0]['f5']));
$_Tpl_FRM->setParseValue("F6", display($ADR[0]['f6']));
$_Tpl_FRM->setParseValue("F7", display($ADR[0]['f7']));
$_Tpl_FRM->setParseValue("F8", display($ADR[0]['f8']));
$_Tpl_FRM->setParseValue("F9", display($ADR[0]['f9']));
#$_Tpl_FRM->setParseValue("MEMO", display($ADR[0]['memo'])); // full memo from db
$_Tpl_FRM->setParseValue("MEMO", display($$InputName_Memo));// only new usermemo

$_Tpl_FRM->setParseValue("F0NAME", display($FRM[0]['f0']));
$_Tpl_FRM->setParseValue("F1NAME", display($FRM[0]['f1']));
$_Tpl_FRM->setParseValue("F2NAME", display($FRM[0]['f2']));
$_Tpl_FRM->setParseValue("F3NAME", display($FRM[0]['f3']));
$_Tpl_FRM->setParseValue("F4NAME", display($FRM[0]['f4']));
$_Tpl_FRM->setParseValue("F5NAME", display($FRM[0]['f5']));
$_Tpl_FRM->setParseValue("F6NAME", display($FRM[0]['f6']));
$_Tpl_FRM->setParseValue("F7NAME", display($FRM[0]['f7']));
$_Tpl_FRM->setParseValue("F8NAME", display($FRM[0]['f8']));
$_Tpl_FRM->setParseValue("F9NAME", display($FRM[0]['f9']));

if (tm_DEBUG()) $MESSAGE.=tm_message_debug("set OUTPUT and parse from template: $Form_Success_Filename");
$OUTPUT=$_Tpl_FRM->renderTemplate($Form_Success_Filename);
?>