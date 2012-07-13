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

$_MAIN_DESCR=___("Adressgruppe bearbeiten");
$_MAIN_MESSAGE.="";

$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$set=getVar("set");
$adr_grp_id=getVar("adr_grp_id");

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
//wenn sich public aender loeschen wir dennoch keine referenzen von public groups zu den formularen, das wird in subscribe eh ueberprueft und beim naechsten bearbeiten des formulares bereinigt! (da edit methode alte refs loescht und komplett neu anlegt!)

$InputName_Prod="prod";//range from
$$InputName_Prod=getVar($InputName_Prod);


$ADDRESS=new tm_ADR();
$GRP=$ADDRESS->getGroup($adr_grp_id,0,0,0);
$standard=$GRP[0]['standard'];
if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name der Gruppe sollte nicht leer sein."));}
	if ($check) {
		$ADDRESS=new tm_ADR();
		$ADDRESS->updateGrp(Array(
					"id"=>$adr_grp_id,
					"name"=>$name,
					"public"=>$public,
					"public_name"=>$public_name,
					"descr"=>$descr,
					"aktiv"=>$aktiv,
					"prod"=>$prod,
					"created"=>$created,
					"author"=>$author
					));
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Adressgruppe %s wurde aktualisiert."),"'".$name."'"));
		$action="adr_grp_list";
		require_once (TM_INCLUDEPATH_GUI."/adr_grp_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/adr_grp_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/adr_grp_form_show.inc.php");
	}
} else {
	$name=$GRP[0]['name'];
	$public=$GRP[0]['public'];
	$public_name=$GRP[0]['public_name'];
	$descr=$GRP[0]['descr'];
	$aktiv=$GRP[0]['aktiv'];
	$prod=$GRP[0]['prod'];
	require_once (TM_INCLUDEPATH_GUI."/adr_grp_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/adr_grp_form_show.inc.php");
}
?>