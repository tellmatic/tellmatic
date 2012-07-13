<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006-2011 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

$_MAIN_DESCR=___("Link bearbeiten");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$lnk_id=getVar("lnk_id");
$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$offset=getVar("offset");
$limit=getVar("limit");
$s_name=getVar("s_name");
$s_url=getVar("s_url");
$s_aktiv=getVar("s_aktiv");
$lnk_grp_id=getVar("lnk_grp_id");
$st=getVar("st");
$si=getVar("si");

//field names for query
$InputName_Group="lnk_grp";
pt_register("POST","lnk_grp");
if (!isset($lnk_grp)) {
	$lnk_grp=Array();
	$lnk_grp[0]=0;
}

$InputName_Name="name";//
$$InputName_Name=getVar($InputName_Name);

$InputName_Aktiv="aktiv";//
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_URL="url";//
$$InputName_URL=getVar($InputName_URL);

$InputName_Short="short";
$$InputName_Short=getVar($InputName_Short);

$InputName_Descr="descr";
$$InputName_Descr=getVar($InputName_Descr);

$LINK=new tm_LNK();
$LNK=$LINK->get($lnk_id);

$check=true;
if ($set=="save") {
	//checkinput
	if (empty($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht leer sein."));}
	if (!empty($short) && is_numeric($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht numerisch sein."));}
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name sollte nicht leer sein."));}
	if (!check_dbid($lnk_grp[0])) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Bitte mindestens eine Gruppe wählen."));}
	//dublettencheck, kuerzel sollte eindeutig sein!
	//$LNK=$LINK->get(0,Array("short"=>$short));
	//if (count($LNK)>1) {$check=false;$_MAIN_MESSAGE.="<br>".___("Eine Eintrag mit diesem Kürzel existiert bereits. Das Kürzel muss eindeutig sein.");}

	if ($check) {
		$LINK->update(Array(
				"id"=>$lnk_id,
				"name"=>$name,
				"short"=>$short,
				"url"=>$url,
				"descr"=>$descr,
				"aktiv"=>$aktiv,
				"created"=>$created,
				"author"=>$author,
				),
				$lnk_grp);
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Eintrag %s (%s) wurde aktualisiert."),"'".$name."'","'".$short."'"));
		$action="link_list";
		require_once ("link_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/link_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/link_form_show.inc.php");
	}//check

} else {
	$lnk_grp=$LINK->getGroupID(0,Array("item_id"=>$lnk_id));
	$name=$LNK[0]['name'];
	$short=$LNK[0]['short'];
	$url=$LNK[0]['url'];
	$aktiv=$LNK[0]['aktiv'];
	$descr=$LNK[0]['descr'];
	require_once (TM_INCLUDEPATH_GUI."/link_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/link_form_show.inc.php");
}
?>