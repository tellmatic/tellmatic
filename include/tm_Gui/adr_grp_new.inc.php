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

$_MAIN_DESCR=___("neue Adressgruppe erstellen");
$_MAIN_MESSAGE.="";

$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$set=getVar("set");
$adr_grp_id=0;
$standard=0;

//field names for query
$InputName_Name="name";//range from
$$InputName_Name=getVar($InputName_Name);

$InputName_Descr="descr";//range from
$$InputName_Descr=getVar($InputName_Descr);

$InputName_Aktiv="aktiv";//range from
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Public="public";//range from
$$InputName_Public=getVar($InputName_Public);

$InputName_PublicName="public_name";//range from
$$InputName_PublicName=getVar($InputName_PublicName);

$InputName_Prod="prod";//range from
$$InputName_Prod=getVar($InputName_Prod);

$check=true;
if ($set=="save") {
	//checkinput
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name der Gruppe sollte nicht leer sein."));}
	if ($check) {
		$ADDRESS=new tm_ADR();
		$ADDRESS->addGrp(Array(
					"name"=>$name,
					"public"=>$public,
					"public_name"=>$public_name,
					"descr"=>$descr,
					"aktiv"=>$aktiv,
					"prod"=>$prod,
					"created"=>$created,
					"author"=>$author
					));
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neue Addressgruppe %s wurde erstellt."),"'".$name."'"));
		$action="adr_grp_list";
		require_once (TM_INCLUDEPATH_GUI."/adr_grp_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/adr_grp_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/adr_grp_form_show.inc.php");
	}

} else {
	$$InputName_Aktiv=1;
	$$InputName_Prod=0;
	$$InputName_Name=___("Neue Gruppe");
	$$InputName_PublicName=___("Neue Gruppe");
	$$InputName_Descr=___("Neue Gruppe");
	require_once (TM_INCLUDEPATH_GUI."/adr_grp_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/adr_grp_form_show.inc.php");
}
?>