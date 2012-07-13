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

$_MAIN_DESCR=___("neue Linkgruppe erstellen");
$_MAIN_MESSAGE.="";

$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$set=getVar("set");
$lnk_grp_id=0;
$standard=0;

//field names for query
$InputName_Short="short";
$$InputName_Short=getVar($InputName_Short);

$InputName_Name="name";
$$InputName_Name=getVar($InputName_Name);

$InputName_Descr="descr";
$$InputName_Descr=getVar($InputName_Descr);

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv);

$LNKGRP=new tm_LNK();

if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht leer sein."));}
	if (!empty($short) && is_numeric($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht numerisch sein."));}
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name sollte nicht leer sein."));}

	//dublettencheck, kuerzel sollte eindeutig sein!
	$G=$LNKGRP->getGroup(0,Array("short"=>$short));
	if (count($G)>0) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Eine Gruppe mit diesem Kürzel existiert bereits. Das Kürzel muss eindeutig sein."));}
	
	if ($check) {
		$LNKGRP->addGrp(Array(
					"short"=>$short,
					"name"=>$name,
					"descr"=>$descr,
					"aktiv"=>$aktiv,
					"created"=>$created,
					"author"=>$author
					));
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neue Gruppe %s (%s) wurde erstellt."),"'".$name."'","'".$short."'"));
		$action="link_grp_list";
		require_once (TM_INCLUDEPATH_GUI."/link_grp_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/link_grp_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/link_grp_form_show.inc.php");
	}

} else {
	$$InputName_Aktiv=1;
	$$InputName_Name=___("Neue Gruppe");
	$$InputName_Descr=___("Neue Gruppe");
	require_once (TM_INCLUDEPATH_GUI."/link_grp_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/link_grp_form_show.inc.php");
}
?>