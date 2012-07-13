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

$_MAIN_DESCR=___("Neuen Link eintragen");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$lnk_id=0;
$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$offset=getVar("offset");
$limit=getVar("limit");
$s_url=getVar("s_url");
$s_name=getVar("s_name");
$lnk_grp_id=getVar("lnk_grp_id");
$st=getVar("st");
$si=getVar("si");

//field names for query
$InputName_Group="lnk_grp";//range from
pt_register("POST","lnk_grp");
if (!isset($lnk_grp[0])) {
	$lnk_grp[0]=getVar("lnk_grp_id");
}

$InputName_Short="short";
$$InputName_Short=getVar($InputName_Short);

$InputName_Name="name";
$$InputName_Name=getVar($InputName_Name);

$InputName_URL="url";
$$InputName_URL=getVar($InputName_URL);

$InputName_Descr="descr";
$$InputName_Descr=getVar($InputName_Descr);

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv,0,1);

##werte aus sessions uebernehmen... oder uebergebene werte
//nur wenn nicht gespeichert wird, also nur bei neuaufruf des formulares
if ($set!="save") { 
 if (!empty($s_url)) {
 	$url=$s_url;
 } 
 if (!empty($s_name)) {
 	$name=$s_name;
 } 
}

$check=true;
$LINK=new tm_LNK();
if ($set=="save") {
	//checkinput
	if (empty($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht leer sein."));}
	if (!empty($short) && is_numeric($short)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Kürzel sollte nicht numerisch sein."));}
	if (empty($name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name sollte nicht leer sein."));}
	if (!check_dbid($lnk_grp[0])) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Bitte mindestens eine Gruppe wählen."));}
	//dublettencheck, kuerzel sollte eindeutig sein!
	$LNK=$LINK->get(0,Array("short"=>$short));
	if (count($LNK)>0) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Eine Eintrag mit diesem Kürzel existiert bereits. Das Kürzel muss eindeutig sein."));}

	if ($check) {
		$LINK->add(Array(
					"short"=>$short,
					"name"=>$name,
					"url"=>$url,
					"descr"=>$descr,
					"aktiv"=>$aktiv,
					"created"=>$created,
					"author"=>$author
					),
					$lnk_grp);
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neuer Eintrag %s (%s) wurde erstellt."),"'".$name."'","'".$short."'"));
		$action="link_list";
		require_once (TM_INCLUDEPATH_GUI."/link_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/link_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/link_form_show.inc.php");
	}
} else {
	srand((double)microtime()*1000000);
	$randomval=rand(1111,9999);

	$$InputName_Aktiv=1;
	$$InputName_Short="lnk".$randomval;
	$$InputName_Name=___("Neuer Link");
	$$InputName_URL=___("http://");
	$$InputName_Descr=___("Beschreibung/Title");
	require_once (TM_INCLUDEPATH_GUI."/link_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/link_form_show.inc.php");
}
?>