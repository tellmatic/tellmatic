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

$_MAIN_DESCR=___("Datei bearbeiten");
$_MAIN_MESSAGE.="";

$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$set=getVar("set");
$file_section=getVar("file_section");

//field names for query
$InputName_Name="file_name";
$$InputName_Name=getVar($InputName_Name);

$InputName_Content="content";
$$InputName_Content=getVar($InputName_Content);

$file=$file_path['path'][$file_section]."/".$file_name;

if ($set=="save") {
	$check=true;
	//checkinput
	if (empty($file_name)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Name sollte nicht leer sein."));}
	if ($check) {
		$success=write_file($file_path['path'][$file_section],$file_name,$content);
		if ($success) $_MAIN_MESSAGE.=tm_message_success(sprintf(___("Datei %s wurde gespeichert."),$file_name));
		if (!$success) $_MAIN_MESSAGE.=tm_message_error(sprintf(___("Fehler bem speichern der Datei %s."),$file_name));
		#$action="file_list";
		require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit_form.inc.php");//file_list
		require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit_form_show.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit_form_show.inc.php");
	}
} else {
	$content=file_get_contents($file);
	require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit_form_show.inc.php");
}
?>