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

$_MAIN_DESCR=___("Blacklist bearbeiten");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$bl_id=getVar("bl_id");
$created=date("Y-m-d H:i:s");
#$author=$LOGIN->USER['name'];

$InputName_Expr="expr";
$$InputName_Expr=getVar($InputName_Expr);

$InputName_Type="type";
$$InputName_Type=getVar($InputName_Type);

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv);

$BLACKLIST=new tm_BLACKLIST();
$BL=$BLACKLIST->getBL($bl_id);

if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($expr)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Der Ausdruck darf nicht leer sein."));}
	//syntaxcheck wenn email
	$check_mail=checkEmailAdr($expr,$EMailcheck_Intern);
	if ($type=="email" && !$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(sprintf(___("E-Mail %s hat ein falsches Format."),$expr)." ".$check_mail[1]);}
	if ($check) {
		if (!tm_DEMO()) {
			//den dublettencheck spar ich mir hier aber.... erstmal....
			$BLACKLIST->updateBL(Array(
				"id"=>$bl_id,
				"type"=>$type,
				"expr"=>$expr,
				"aktiv"=>$aktiv
				));
		}
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Eintrag %s wurde bearbeitet."),"'".$expr."'"));
		$action="bl_list";
		include_once ("bl_list.inc.php");
	} else {//check
		require_once (TM_INCLUDEPATH_GUI."/bl_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/bl_form_show.inc.php");
	}//check
} else {//save
	$expr=$BL[0]['expr'];
	$aktiv=$BL[0]['aktiv'];
	$type=$BL[0]['type'];
	require_once (TM_INCLUDEPATH_GUI."/bl_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/bl_form_show.inc.php");
}
?>