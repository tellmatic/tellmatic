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

$_MAIN_DESCR=___("Blacklist verwalten");
$_MAIN_MESSAGE.="";
$BLACKLIST=new tm_BLACKLIST();

$bl_id=getVar("bl_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

$InputName_Type="type";
$$InputName_Type=getVar($InputName_Type);
if (empty($$InputName_Type)) {
	$$InputName_Type="expr";
}

//sort array:
$sortIndex=getVar("si");
if (empty($sortIndex)) {
	$sortIndex="type";
}
$sortType=getVar("st");
if (empty($sortType)) {
	$sortType="0";//asc
}
//offset und limit
$offset=getVar("offset");
if (empty($offset) || $offset<0) {
	$offset=0;
}
$limit=getVar("limit");
if (empty($limit) || $limit<10) {
	$limit=10;
}

if ($set=="aktiv") {
	if (!tm_DEMO()) $BLACKLIST->setAktiv($bl_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $BLACKLIST->delBL($bl_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}

if ($user_is_manager && $set=="export_blacklist") {
	$_MAIN_MESSAGE.=tm_message_success(___("Blacklist wird exportiert."));
	require_once(TM_INCLUDEPATH_GUI."/bl_export.inc.php");
}

$entrys_total=$BLACKLIST->countBL(Array("type"=>$type));

$mSTDURL->addParam("type",$type);

$editURLPara=tmObjCopy($mSTDURL);

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("set","aktiv");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("set","delete");

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("act","bl_list");
$sortURLPara_=$sortURLPara->getAllParams();

$firstURLPara=tmObjCopy($mSTDURL);
$firstURLPara->addParam("act","bl_list");
$firstURLPara->addParam("offset",0);
$firstURLPara->addParam("st",$sortType);
$firstURLPara->addParam("si",$sortIndex);
$firstURLPara_=$firstURLPara->getAllParams();

$lastURLPara=tmObjCopy($mSTDURL);
$lastURLPara->addParam("act","bl_list");
$lastURLPara->addParam("offset",($entrys_total-$limit));
$lastURLPara->addParam("st",$sortType);
$lastURLPara->addParam("si",$sortIndex);
$lastURLPara_=$lastURLPara->getAllParams();

$nextURLPara=tmObjCopy($mSTDURL);
$nextURLPara->addParam("act","bl_list");
$nextURLPara->addParam("offset",($offset+$limit));
$nextURLPara->addParam("limit",$limit);
$nextURLPara->addParam("st",$sortType);
$nextURLPara->addParam("si",$sortIndex);
$nextURLPara_=$nextURLPara->getAllParams();

$prevURLPara=tmObjCopy($mSTDURL);
$prevURLPara->addParam("act","bl_list");
$prevURLPara->addParam("offset",($offset-$limit));
$prevURLPara->addParam("limit",$limit);
$prevURLPara->addParam("st",$sortType);
$prevURLPara->addParam("si",$sortIndex);
$prevURLPara_=$prevURLPara->getAllParams();

$exportURLPara=tmObjCopy($mSTDURL);
$exportURLPara->addParam("set","export_blacklist");
$exportURLPara_=$exportURLPara->getAllParams();

$BL=$BLACKLIST->getBL(0,Array("type"=>$type),$offset,$limit);
$BL=sort_array($BL,$sortIndex,$sortType);
$bc=count($BL);
$entrys=$bc; // fuer pager.inc!!!

$pagesURLPara=tmObjCopy($mSTDURL);
//will be defined and use in pager.inc.php

//show log summary
//search for logs, only section
$search_log['object']="bl";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

require_once(TM_INCLUDEPATH_GUI."/bl_list_form.inc.php");
require_once(TM_INCLUDEPATH_GUI."/bl_list_form_show.inc.php");
//pager
require(TM_INCLUDEPATH_GUI."/pager.inc.php");

//table
$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td><b>".___("Typ")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=type&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=type&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("E-Mail/Domain/Expr")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=expr&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=expr&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Aktiv")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td>...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";
						
for ($bcc=0;$bcc<$bc;$bcc++) {
	if ($bcc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($BL[$bcc]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	$editURLPara->addParam("act","bl_edit");
	$editURLPara->addParam("bl_id",$BL[$bcc]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$aktivURLPara->addParam("act","bl_list");
	$aktivURLPara->addParam("bl_id",$BL[$bcc]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$delURLPara->addParam("act","bl_list");
	$delURLPara->addParam("bl_id",$BL[$bcc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$_MAIN_OUTPUT.= "<tr id=\"row_".$bcc."\"  bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$bcc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$bcc."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_host_list_".$BL[$bcc]['id']."')\" onmouseout=\"hideToolTip();\">";

	if ($BL[$bcc]['type']=="email") {
		$_MAIN_OUTPUT.=  tm_icon("ruby_key.png",___("E-Mail"))."&nbsp;".___("E-Mail");
	}
	if ($BL[$bcc]['type']=="domain") {
		$_MAIN_OUTPUT.=  tm_icon("ruby_link.png",___("Domain"))."&nbsp;".___("Domain");
	}
	if ($BL[$bcc]['type']=="expr") {
		$_MAIN_OUTPUT.=  tm_icon("ruby_gear.png",___("Regulaerer Ausdruck"))."&nbsp;".___("RegEx");
	}

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_user_list_".$BL[$bcc]['id']."')\" onmouseout=\"hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\"  title=\"".___("Eintrag bearbeiten")."\">".display($BL[$bcc]['expr'])."</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_user_list_".$BL[$bcc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.=___("Typ").": ".display($BL[$bcc]['type'])."</b>";

	$_MAIN_OUTPUT.= "<br>".___("Ausdruck").":<font size=\"-1\">".display($BL[$bcc]['expr'])."</font>";
	$_MAIN_OUTPUT.= "<br>ID: ".$BL[$bcc]['id']." ";
	if ($BL[$bcc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}

	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	if ($BL[$bcc]['aktiv']==1) {
		//aktiv
		$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Aktiv"))."&nbsp;";
	} else {
		//inaktiv
		$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
	}
	$_MAIN_OUTPUT.= "</a>";

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Eintrag bearbeiten")."\">".tm_icon("pencil.png",___("Eintrag bearbeiten"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Eintrag %s löschen?"),display($BL[$bcc]['expr']))."')\" title=\"".___("Eintrag löschen")."\">".tm_icon("cross.png",___("Eintrag löschen"))."</a>";

	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$BL[$bcc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}
$_MAIN_OUTPUT.= "</tbody></table>";

require(TM_INCLUDEPATH_GUI."/pager.inc.php");
require_once(TM_INCLUDEPATH_GUI."/bl_list_legende.inc.php");
?>