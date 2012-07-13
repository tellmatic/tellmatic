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
$_MAIN_OUTPUT.="\n\n<!-- link_list.inc -->\n\n";

$_MAIN_DESCR=___("Links verwalten");
$_MAIN_MESSAGE.="";

if (!isset($offset)) {
	$offset=getVar("offset");
}
if (empty($offset) || $offset<0) {
	$offset=0;
}
if (!isset($limit)) {
	$limit=getVar("limit");
}
if (empty($limit)) {
	$limit=25;
}

$no_list=getVar("no_list");

//sort und sorttype nach search verschoben

$LINK=new tm_LNK();

$lnk_grp_id=getVar("lnk_grp_id");
$lnk_id=getVar("lnk_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()
if (!isset($search)) {
	$search=Array();
}

require_once (TM_INCLUDEPATH_GUI."/link_search.inc.php");

if ($set=="aktiv") {
	$LINK->setAktiv($lnk_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}//aktiv single

if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $LINK->del($lnk_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}//del single

if ($set=="resetClicks" && $doit==1) {
	if (!tm_DEMO()) $LINK->resetClicks($lnk_id);
	$LNK_Reset=$LINK->get($lnk_id);
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Klicks für %s wurden auf 0 gesetzt. (Reset)"),"'".$LNK_Reset[0]['name']."'"));
}//reset single

if ($set=="flushClicks" && $doit==1) {
	$flush_items_c=0;
	if (!tm_DEMO()) $flush_items_c=$LINK->flushClicks($lnk_id);
	$LNK_Flush=$LINK->get($lnk_id);
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Klicks für %s wurden auf 0 gesetzt. (Reset)"),$LNK_Flush[0]['name']));
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Klick Statistik für %s wurde gelöscht. (Flush)"),$LNK_Flush[0]['name']));
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("%s Einträge wurden entfernt"),$flush_items_c));
}//flush single

$search['offset']=$offset;
$search['limit']=$limit;
$search['sortIndex']=$sortIndex;
$search['sortType']=$sortType;

if ($lnk_grp_id>0)
{
	$search['group']=$lnk_grp_id;
	$LNK=$LINK->get(0,$search);
	$lnk_grp=$LINK->getGroup($lnk_grp_id);
	$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("gewählte Gruppe: %s"),"'".$lnk_grp[0]['name']."'"));
} else {
	$LNK=$LINK->get(0,$search);
}

$ac=count($LNK);
$entrys=$ac; // fuer pager.inc!!! // aktuelle anzahl angezeigter eintraege

$entrys_total=$LINK->count($lnk_grp_id,$search);//anzahl eintraege aktuell gewaehlt mit filter

//globale parameter die angefuegt werden sollen!
$mSTDURL->addParam("offset",$offset);
$mSTDURL->addParam("limit",$limit);
if (isset($s_name)) {
	$mSTDURL->addParam("s_name",$s_name);
}
if (isset($s_url)) {
	$mSTDURL->addParam("s_url",$s_url);
}

$mSTDURL->addParam("lnk_grp_id",$lnk_grp_id);
$mSTDURL->addParam("st",$sortType);
$mSTDURL->addParam("si",$sortIndex);

$firstURLPara=tmObjCopy($mSTDURL);
$firstURLPara->addParam("act","link_list");
$firstURLPara->addParam("offset",0);
$firstURLPara_=$firstURLPara->getAllParams();

$lastURLPara=tmObjCopy($mSTDURL);
$lastURLPara->addParam("act","link_list");
$lastURLPara->addParam("offset",($entrys_total-$limit));
$lastURLPara_=$lastURLPara->getAllParams();

$nextURLPara=tmObjCopy($mSTDURL);
//neuer offset!
$nextURLPara->addParam("offset",($offset+$limit));
$nextURLPara->addParam("act","link_list");
$nextURLPara_=$nextURLPara->getAllParams();

$prevURLPara=tmObjCopy($mSTDURL);
$prevURLPara->addParam("lnk_grp_id",$lnk_grp_id);
//neuer offset!
$prevURLPara->addParam("offset",($offset-$limit));
$prevURLPara->addParam("act","link_list");
$prevURLPara_=$prevURLPara->getAllParams();

$pagesURLPara=tmObjCopy($mSTDURL);
//will be defined and use in pager.inc.php

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("act","link_list");
$sortURLPara_=$sortURLPara->getAllParams();

$editURLPara=tmObjCopy($mSTDURL);
$editURLPara->addParam("lnk_id",$lnk_id);
$editURLPara->addParam("act","link_edit");

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("lnk_grp_id",$lnk_grp_id);
$aktivURLPara->addParam("offset",$offset);
$aktivURLPara->addParam("limit",$limit);
$aktivURLPara->addParam("act","link_list");
$aktivURLPara->addParam("set","aktiv");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("lnk_grp_id",$lnk_grp_id);
$delURLPara->addParam("offset",$offset);
$delURLPara->addParam("limit",$limit);
$delURLPara->addParam("act","link_list");
$delURLPara->addParam("set","delete");

$resetURLPara=tmObjCopy($mSTDURL);
$resetURLPara->addParam("lnk_grp_id",$lnk_grp_id);
$resetURLPara->addParam("offset",$offset);
$resetURLPara->addParam("limit",$limit);
$resetURLPara->addParam("act","link_list");
$resetURLPara->addParam("set","resetClicks");

$flushURLPara=tmObjCopy($mSTDURL);
$flushURLPara->addParam("lnk_grp_id",$lnk_grp_id);
$flushURLPara->addParam("offset",$offset);
$flushURLPara->addParam("limit",$limit);
$flushURLPara->addParam("act","link_list");
$flushURLPara->addParam("set","flushClicks");

//show log summary
//search for logs, only section
$search_log['object']="lnk";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

//include pager
require(TM_INCLUDEPATH_GUI."/pager.inc.php");

//data table
$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td><b>".___("ID")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Kurz")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=short&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=short&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Name")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=name&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=name&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Klicks")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=clicks&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=clicks&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Gruppen")."</b></td>".
						"<td><b>".___("Aktiv")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td>...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";


for ($acc=0;$acc<$ac;$acc++) {
	if ($acc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($LNK[$acc]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	$created_date=$LNK[$acc]['created'];
	$updated_date=$LNK[$acc]['updated'];

	$author=$LNK[$acc]['author'];
	$editor=$LNK[$acc]['editor'];

	$editURLPara->addParam("lnk_id",$LNK[$acc]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$aktivURLPara->addParam("lnk_id",$LNK[$acc]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$delURLPara->addParam("lnk_id",$LNK[$acc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$resetURLPara->addParam("lnk_id",$LNK[$acc]['id']);
	$resetURLPara_=$resetURLPara->getAllParams();

	$flushURLPara->addParam("lnk_id",$LNK[$acc]['id']);
	$flushURLPara_=$flushURLPara->getAllParams();

	#$row_bgcolor_hilite;

	$_MAIN_OUTPUT.= "<tr id=\"row_".$acc."\" bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$acc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');\">";

	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_lnk_list_id_".$LNK[$acc]['id']."')\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');hideToolTip();\">";
	
	$_MAIN_OUTPUT.= "<a href=\"".$LNK[$acc]['url']."\" title=\"".display($LNK[$acc]['descr'])."\" target=\"_blank\">".tm_icon("link_go.png",display($LNK[$acc]['descr']))."</a>";//___("Link öffnen")
	$_MAIN_OUTPUT.="&nbsp;";
	$_MAIN_OUTPUT.=$LNK[$acc]['id'];

	$_MAIN_OUTPUT.= "<div id=\"tt_lnk_list_id_".$LNK[$acc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="ID:".$LNK[$acc]['id'];
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_lnk_list_".$LNK[$acc]['id']."')\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\">".display($LNK[$acc]['short'])."</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_lnk_list_".$LNK[$acc]['id']."\" class=\"tooltip\">";

	$_MAIN_OUTPUT.= "<b>".display($LNK[$acc]['name'])."</b>";
	$_MAIN_OUTPUT.= "<br><em>".display($LNK[$acc]['short'])."</em>";
	$_MAIN_OUTPUT.= "<br>".display($LNK[$acc]['url']);
	$_MAIN_OUTPUT.= "<br><font size=\"-1\">".display($LNK[$acc]['descr'])."</font>";
	if ($LNK[$acc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(Inaktiv)");
	}
	$_MAIN_OUTPUT.= "<br>".sprintf(___("Klicks: %s"),$LNK[$acc]['clicks']);
	$_MAIN_OUTPUT.= "<br>".sprintf(___("Erstellt am: %s von %s"),$created_date,$author).
							"<br>".sprintf(___("Bearbeitet am: %s von %s"),$updated_date,$editor);
	$_MAIN_OUTPUT.= "<br>".___("Eingetragen in den Gruppen:");
	$_MAIN_OUTPUT.= "<ul>";
	$GRP=$LINK->getGroup(0,Array("item_id"=>$LNK[$acc]['id']));
	$acg=count($GRP);
	for ($accg=0;$accg<$acg;$accg++) {
		$_MAIN_OUTPUT.= "<li>".display($GRP[$accg]['name'])."</li>";
	}
	$_MAIN_OUTPUT.= "</ul>";
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "".display($LNK[$acc]['name']);
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "".display($LNK[$acc]['clicks']);
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	for ($accg=0;$accg<$acg;$accg++) {
		//pretag
		if ($GRP[$accg]['aktiv']!=1) {
			$_MAIN_OUTPUT.= "<span style=\"color:#ff0000;\">";
		}
		//name of group
		$_MAIN_OUTPUT.= "".display($GRP[$accg]['name']);

		//posttag
		if ($GRP[$accg]['aktiv']!=1) {
			$_MAIN_OUTPUT.= " (na)</span>";
		}
		$_MAIN_OUTPUT.= ", ";
	}
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	if ($LNK[$acc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Aktiv"))."&nbsp;";
	} else {
		$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
	}
	$_MAIN_OUTPUT.= "</a>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Eintrag bearbeiten")."\">".tm_icon("pencil.png",___("Eintrag bearbeiten"))."</a>";

	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$resetURLPara_."\" onclick=\"return confirmLink(this, '".___("Klicks für diesen Link zurücksetzen auf 0 (Reset)")."')\" title=\"".___("Klicks für diesen Link zurücksetzen auf 0 (Reset)")."\">".tm_icon("bullet_delete.png",___("Klicks für diesen Link zurücksetzen auf 0 (Reset)"),"","","","calculator.png")."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$flushURLPara_."\" onclick=\"return confirmLink(this, '".___("Gesamte Klick-Statistik für diesen Link löschen (Flush)")."')\" title=\"".___("Gesamte Klick-Statistik für diesen Link löschen (Flush)")."\">".tm_icon("bullet_delete.png",___("Gesamte Klick-Statistik für diesen Link löschen (Flush)"),"","","","chart_line.png")."</a>";
	
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".___("Eintrag löschen")."')\" title=\"".___("Eintrag löschen")."\">".tm_icon("cross.png",___("Eintrag löschen"))."</a>";

	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$LNK[$acc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require(TM_INCLUDEPATH_GUI."/pager.inc.php");
require_once(TM_INCLUDEPATH_GUI."/link_list_legende.inc.php");
?>