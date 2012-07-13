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

$_MAIN_DESCR=___("Linkgruppe bearbeiten");
$_MAIN_MESSAGE.="";

$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$set=getVar("set");
$lnk_grp_id=getVar("lnk_grp_id");

//field names for query
$InputName_Name="name";//range from
$$InputName_Name=getVar($InputName_Name);

$InputName_Descr="descr";//range from
$$InputName_Descr=getVar($InputName_Descr);

$InputName_Aktiv="aktiv";//range from
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Short="short";//range from
$$InputName_Short=getVar($InputName_Short);

$LNKGRP=new tm_LNK();
$GRP=$LNKGRP->getGroup($lnk_grp_id);
$standard=$GRP[0]['standard'];
if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht leer sein."));}
	if (!empty($short) && is_numeric($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht numerisch sein."));}
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name sollte nicht leer sein."));}
	
	//dublettencheck, kuerzel sollte eindeutig sein!
	//$G=$LNKGRP->getGroup(0,Array("short"=>$short));
	//hier >1, da edit, und dieser eintrag ja bereits existiert, was ja ok ist
	//if (count($G)>1) {$check=false;$_MAIN_MESSAGE.="<br>".___("Eine Gruppe mit diesem Kürzel existiert bereits. Das Kürzel muss eindeutig sein.");}

	if ($check) {
		$LNKGRP->updateGrp(Array(
					"id"=>$lnk_grp_id,
					"short"=>$short,
					"name"=>$name,
					"descr"=>$descr,
					"aktiv"=>$aktiv,
					"created"=>$created,
					"author"=>$author
					));
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Gruppe %s (%s) wurde aktualisiert."),"'".$name."'","'".$short."'"));
		$action="link_grp_list";
		require_once (TM_INCLUDEPATH_GUI."/link_grp_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/link_grp_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/link_grp_form_show.inc.php");
	}
} else {
	$short=$GRP[0]['short'];
	$name=$GRP[0]['name'];
	$descr=$GRP[0]['descr'];
	$aktiv=$GRP[0]['aktiv'];
	require_once (TM_INCLUDEPATH_GUI."/link_grp_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/link_grp_form_show.inc.php");
}
?>