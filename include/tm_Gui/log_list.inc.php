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

$_MAIN_DESCR=___("Logbuch");
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

//sort und sorttype nach search verschoben

$LOGS=new tm_LOG();

$log_id=getVar("log_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()
if (!isset($search)) {
	$search=Array();
}

require_once (TM_INCLUDEPATH_GUI."/log_search.inc.php");

if ($set=="delete") {
	$delcount=$LOGS->count($search);
	if (!tm_DEMO()) $LOGS->del($search);
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("%s Einträge aus dem Logbuch wurden gelöscht."),$delcount));
}//del single
if ($set=="flush" && $doit==1) {
	$delcount=$LOGS->count();
	if (!tm_DEMO()) $LOGS->flush();
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Alle Einträge (%s) aus dem Logbuch wurden gelöscht."),$delcount));
}//del all, flush

//neu: search statt einzelner paras
$search['offset']=$offset;
$search['limit']=$limit;
$search['sort_index']=$sortIndex;
$search['sort_type']=$sortType;

#print_r($search);exit;
$LOG=$LOGS->get(0,$search);
#$_MAIN_MESSAGE.="LC:".$LOGS->count($search);
$ac=count($LOG);#$LOGS->count($search);#;
$entrys=$ac;// fuer pager.inc!!!
$entrys_total=$LOGS->count($search);
#if (isset($LOG[0]['count'])) {
#	$entrys_total=$LOG[0]['count'];
#}

$mSTDURL->addParam("offset",$offset);
$mSTDURL->addParam("limit",$limit);
if (isset($s_action)) {
	$mSTDURL->addParam("s_action",$s_action);
}
if (isset($s_obj)) {
	$mSTDURL->addParam("s_obj",$s_obj);
}
if (isset($s_name)) {
	$mSTDURL->addParam("s_author",$s_author);
}

if (isset($s_name)) {
	$mSTDURL->addParam("s_edit_id",$s_edit_id);
}

if ($set=="search") {
	$mSTDURL->addParam("set",$set);
}

$mSTDURL->addParam("st",$sortType);
$mSTDURL->addParam("si",$sortIndex);

$firstURLPara=tmObjCopy($mSTDURL);
$firstURLPara->addParam("act","log_list");
$firstURLPara->addParam("offset",0);
$firstURLPara_=$firstURLPara->getAllParams();

$lastURLPara=tmObjCopy($mSTDURL);
$lastURLPara->addParam("act","log_list");
$lastURLPara->addParam("offset",($entrys_total-$limit));
$lastURLPara_=$lastURLPara->getAllParams();

$nextURLPara=tmObjCopy($mSTDURL);
$nextURLPara->addParam("offset",($offset+$limit));
$nextURLPara->addParam("act","log_list");
$nextURLPara_=$nextURLPara->getAllParams();

$prevURLPara=tmObjCopy($mSTDURL);
$prevURLPara->addParam("offset",($offset-$limit));
$prevURLPara->addParam("act","log_list");
$prevURLPara_=$prevURLPara->getAllParams();

$pagesURLPara=tmObjCopy($mSTDURL);
//will be defined and use in pager.inc.php

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("act","log_list");
$sortURLPara_=$sortURLPara->getAllParams();

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("offset",$offset);
$delURLPara->addParam("limit",$limit);
$delURLPara->addParam("act","log_list");
$delURLPara->addParam("set","delete");

$editIDURLPara=tmObjCopy($mSTDURL);
$editIDURLPara->addParam("offset",$offset);
$editIDURLPara->addParam("limit",$limit);
$editIDURLPara->addParam("act","log_list");

$flushURLPara=tmObjCopy($mSTDURL);
$flushURLPara->addParam("act","log_list");
$flushURLPara->addParam("set","flush");
$flushURLPara_=$flushURLPara->getAllParams();

//show log summary
//search for logs, only section
$search_log['object']="log";
#require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");


//flush link
 	if ($user_is_admin) {
		$_MAIN_OUTPUT.= "<br><a href=\"".$tm_URL."/".$flushURLPara_."\" onclick=\"return confirmLink(this, '".___("Alle Logbucheinträge löschen")."')\" title=\"".___("Alle Logbucheinträge löschen")."\">".tm_icon("cross.png",___("Alle Logbucheinträge löschen"),"","","","script.png")."&nbsp;".___("Alle Logbucheinträge löschen")."</a><br>";
	}


require(TM_INCLUDEPATH_GUI."/pager.inc.php");

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td width=80><b>".___("ID")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=200><b>".___("Datum")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=date&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=date&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=100><b>".___("Objekt")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=object&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=object&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=50><b>".___("Aktion")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=action&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=action&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=50><b>".___("Objekt ID")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=edit_id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=edit_id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=100><b>".___("Name")."</b>".
						"</td>".
						"<td width=100><b>".___("Author")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=author_id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=author_id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=50><b>".___("Bytes")."</b>".
						"</td>".
						"<!--<td>...</td>-->".
						"</tr>".
						"</thead>".
						"<tbody>";

for ($acc=0;$acc<$ac;$acc++) {
	if ($acc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}

	$delURLPara->addParam("log_id",$LOG[$acc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$editIDURLPara->addParam("s_edit_id",$LOG[$acc]['edit_id']);
	$editIDURLPara->addParam("s_obj",$LOG[$acc]['object']);
	$editIDURLPara_=$editIDURLPara->getAllParams();
	
	$L_Data=Array();
	$L_Data=unserialize($LOG[$acc]['data']);
	$O_Name="";
	if (isset($L_Data['name'])) {
		$O_Name.=$L_Data['name']." ";
	}
	if (isset($L_Data['email'])) {
		$O_Name.=$L_Data['email']." ";
	}
	if (isset($L_Data['subject'])) {
		$O_Name.=$L_Data['subject'];
	}
	if (isset($L_Data['expr'])) {
		$O_Name.=$L_Data['expr'];
	}
	
	$L_DETAILS="";
	if (!empty($LOG[$acc]['memo'])) $L_DETAILS.="<em>".display($LOG[$acc]['memo'])."</em><br>";
	$L_DETAILS.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";
	#$L_DETAILS.="<pre>".print_r($L_Data,TRUE)."</pre>";
	foreach ($L_Data as $data_field => $data_value) {
		$L_DETAILS.="<tr>";
		$L_DETAILS.="<td align=\"right\" valign=\"top\" width=100 style=\"font-size:7pt;\">\n";
		$L_DETAILS.="<b>".display($data_field)."</b>";
		$L_DETAILS.="</td>\n";
		$L_DETAILS.="<td align=\"center\" width=10 valign=\"top\" style=\"font-size:7pt;\">\n";
		$L_DETAILS.="=";
		$L_DETAILS.="</td>\n";
		$L_DETAILS.="<td align=\"left\" valign=\"top\" style=\"font-size:7pt;\">\n";
		//max 2 array depth, e.g. for adr groups, form groups, form pubgroups
		if (!is_array($data_value)) {
			$L_DETAILS.="<em>".display($data_value)."</em>\n";
		}
		if (is_array($data_value)) {
			foreach ($data_value as $data_sub_field => $data_sub_value) {
				if (is_array($data_sub_value)) {
					foreach ($data_sub_value as $data_subsub_field => $data_subsub_value) {
						$L_DETAILS.="<b>".display($data_subsub_field)."</b>";
						$L_DETAILS.=" = ";				
						$L_DETAILS.="<em>".display($data_subsub_value)."</em>, \n";
					}
					$L_DETAILS.="<br>";
				} else {
					$L_DETAILS.="<b>".display($data_sub_field)."</b>";
					$L_DETAILS.=" = ";				
					$L_DETAILS.="<em>".display($data_sub_value)."</em>, \n";
				}
			}
		}
		
		$L_DETAILS.="</td>\n";
		$L_DETAILS.="</tr>\n";
	}
	$L_DETAILS.="</table>\n";

	$_MAIN_OUTPUT.= "<tr id=\"row_".$acc."\" bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$acc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');\">";

	$_MAIN_OUTPUT.= "<td>";//onmousemove=\"showToolTip('tt_item_list_id_".$LOG[$acc]['id']."')\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');hideToolTip();\"
	$_MAIN_OUTPUT.= "<div id=\"tt_item_list_id_".$LOG[$acc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="ID:".$LOG[$acc]['id'];
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "<a id=\"toggle_entry_".$acc."\" href=\"#\" title=\"".___("Details anzeigen")."\">".tm_icon("eye.png",___("Details anzeigen"))."</a>\n".$LOG[$acc]['id'];	
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";// onmousemove=\"showToolTip('tt_item_list_".$LOG[$acc]['id']."')\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');hideToolTip();\"
	$_MAIN_OUTPUT.= display($LOG[$acc]['date']);

	$_MAIN_OUTPUT.= "<div id=\"tt_item_list_".$LOG[$acc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.= display($L_DETAILS);
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($LOG[$acc]['object']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($LOG[$acc]['action']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editIDURLPara_."\" title=\"".___("Filter")."\">";
	$_MAIN_OUTPUT.= checkset_int($LOG[$acc]['edit_id']);
	$_MAIN_OUTPUT.= "</a></td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<em>".$O_Name."</em>";	
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($LOGIN->getUserName($LOG[$acc]['author_id']));
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= strlen($LOG[$acc]['data']);
	$_MAIN_OUTPUT.= "</td>";


/*
	$_MAIN_OUTPUT.= "<td>";
 	if ($user_is_admin) {
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".___("löschen")."')\" title=\"".___("löschen")."\">".tm_icon("cross.png",___("löschen"))."</a>";
	}
	$_MAIN_OUTPUT.= "</td>";
*/
	$_MAIN_OUTPUT.= "</tr>";

	//row+div fuer details	
	$_MAIN_OUTPUT.= "<tr onmouseover=\"setBGColor('row_".$acc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');\">\n";
	$_MAIN_OUTPUT.= "<td colspan=8>\n";
	$_MAIN_OUTPUT.= "<div id=\"list_entry_".$acc."\" class=\"list_edit\">\n";//align=\"right\"
	$_MAIN_OUTPUT.= "<font size=-1>\n";
	$_MAIN_OUTPUT.=$L_DETAILS;
	$_MAIN_OUTPUT.= "</font>\n";
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";

}

$_MAIN_OUTPUT.= "</tbody></table>";

$_MAIN_OUTPUT.= "<script language=\"javascript\" type=\"text/javascript\">";
for ($acc=0;$acc<$ac;$acc++) {
		$_MAIN_OUTPUT.= "toggleSlide('toggle_entry_".$acc."','list_entry_".$acc."',1);";
}

$_MAIN_OUTPUT.= "</script>	";
require(TM_INCLUDEPATH_GUI."/pager.inc.php");
require_once(TM_INCLUDEPATH_GUI."/log_list_legende.inc.php");
?>