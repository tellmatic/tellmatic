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

#$set=getVar("set");
$InputName_Set="set";//

$InputName_Obj="s_obj";//
$$InputName_Obj=getVar($InputName_Obj);

$InputName_Author="s_author";//
$$InputName_Author=getVar($InputName_Author);

$InputName_Action="s_action";//
$$InputName_Action=getVar($InputName_Action);

$InputName_EditID="s_edit_id";//
$$InputName_EditID=checkset_int(getVar($InputName_EditID));

$InputName_Limit="limit";//range from

$InputName_SI0="si0";//
$$InputName_SI0=getVar($InputName_SI0);

$InputName_SI1="si1";//
$$InputName_SI1=getVar($InputName_SI1);

$InputName_SI2="si2";//
$$InputName_SI2=getVar($InputName_SI2);

$sort_search=false;
if (!isset($sortIndex)) {
	$sortIndex=getVar("si");
}
if (!empty($si0)) {
	$sortIndex.=$si0.",";
	$sort_search=true;
}
if (!empty($si1)) {
	$sortIndex.=$si1.",";
	$sort_search=true;
}
if (!empty($si2)) {
	$sortIndex.=$si2.",";
	$sort_search=true;
}

if (empty($sortIndex)) {
	$sortIndex="id";
}
if ($sort_search) { //abschliessend nach id sortieren!
	$sortIndex.="id";
}

$sortType=getVar("st");
if (empty($sortType)) {
	$sortType="1";//asc
}

require_once (TM_INCLUDEPATH_GUI."/log_search_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/log_search_form_show.inc.php");

	$search['object']=$s_obj;
	$search['action']=$s_action;
	$search['author_id']=$s_author;
	$search['edit_id']=$s_edit_id;
?>