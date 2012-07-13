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
$_MAIN_OUTPUT.="\n\n<!-- adr_search.inc -->\n\n";

$set_search=getVar("set_search");
$search_array=Array();

$InputName_Name="s_email";//
$$InputName_Name=stripslashes(getVar($InputName_Name));

$InputName_F="f0_9";//
$$InputName_F=getVar($InputName_F);

$InputName_Status="s_status";//
$$InputName_Status=getVar($InputName_Status);
#pt_register("POST","s_status");
#if (!isset($s_status)) {
#	$s_status[0]=getVar("s_status");
#}

$InputName_Aktiv="s_aktiv";//
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Group="adr_grp_id";//
$$InputName_Group=getVar($InputName_Group);
//pt_register("POST","adr_grp");
/*
if (!isset($adr_grp)) {
	$adr_grp[0]=getVar("adr_grp_id");
}
*/
$InputName_Author="s_author";//
$$InputName_Author=getVar($InputName_Author);

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
	$sortType="0";//asc
}

//werte in sessions speichern! bzw loeschen
$reset_adr_search_values=getVar("reset_adr_search_values");

$InputName_SaveSearch="save_adr_search_values";//
$$InputName_SaveSearch=getVar($InputName_SaveSearch);

if ($reset_adr_search_values!=1 && $save_adr_search_values==1) {
	$_SESSION['s_email']=$s_email;
	$_SESSION['s_status']=$s_status;
	$_SESSION['s_author']=$s_author;
	$_SESSION['f0_9']=$f0_9;
	$_SESSION['limit']=$limit;
	$_SESSION['adr_grp_id']=$adr_grp_id;
	$_SESSION['s_aktiv']="$s_aktiv";
}
if ($reset_adr_search_values==1) {
	$_SESSION['s_email']="";
	$s_email="";
	$_SESSION['s_status']=0;
	$s_status=0;
	$_SESSION['s_author']=0;
	$s_author=0;
	$_SESSION['f0_9']="";
	$f0_9="";
	$_SESSION['limit']=$limit;
	$_SESSION['adr_grp_id']=0;
	$adr_grp_id=0;
	$_SESSION['s_aktiv']="";
	$s_aktiv="";
}

###
//parameters and link to reset all values to default, empty
$resetSearchValuesURL=tmObjCopy($mSTDURL);
$resetSearchValuesURL->addParam("act","adr_list");
$resetSearchValuesURL->addParam("no_list",1);
$resetSearchValuesURL->addParam("reset_adr_search_values",1);
//eigentlich voellig ueberfluessig:
$resetSearchValuesURL->addParam("s_email","*");// * weil wenn leer wieder der session wert genommen wird.... 
$resetSearchValuesURL->addParam("s_status",0);
$resetSearchValuesURL->addParam("adr_grp_id",0);
$resetSearchValuesURL->addParam("s_author",0);
$resetSearchValuesURL->addParam("f0_9","*");// * weil wenn leer wieder der session wert genommen wird....
$resetSearchValuesURL->addParam("s_aktiv","");

$resetSearchValuesURL_=$resetSearchValuesURL->getAllParams();
//$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$resetSearchValuesURL_."\" title=\"".___("Such-Parameter zurücksetzen")."\">".tm_icon("cancel.png",___("Such-Parameter zurücksetzen"))."&nbsp;".___("Such-Parameter zurücksetzen")."</a>";

require_once (TM_INCLUDEPATH_GUI."/adr_search_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/adr_search_form_show.inc.php");

$search['email']=str_replace("*","%",$s_email);
$search['status']=$s_status;
$search['author']=$s_author;
$search['f0_9']=str_replace("*","%",$f0_9);
$search['group']=$adr_grp_id;
$search['aktiv']="$s_aktiv";
?>