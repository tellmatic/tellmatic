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
$_MAIN_OUTPUT.="\n\n<!-- link_search.inc -->\n\n";

$set_search=getVar("set_search");
$search_array=Array();

$InputName_Name="s_name";//
$$InputName_Name=stripslashes(getVar($InputName_Name));

$InputName_URL="s_url";//
$$InputName_URL=stripslashes(getVar($InputName_URL));

$InputName_Group="lnk_grp_id";//
$$InputName_Group=getVar($InputName_Group);

$InputName_Limit="limit";//range from

if (!isset($sortIndex)) {
	$sortIndex=getVar("si");
}
if (empty($sortIndex)) {
	$sortIndex="id";
}
$sortType=getVar("st");
if (empty($sortType)) {
	$sortType="0";//asc
}

require_once (TM_INCLUDEPATH_GUI."/link_search_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/link_search_form_show.inc.php");

$search['name']=str_replace("*","%",$s_name);
$search['url']=str_replace("*","%",$s_url);
$search['group']=$lnk_grp_id;
?>