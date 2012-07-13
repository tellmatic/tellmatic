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

$_MAIN_DESCR=___("Newslettergruppen verwalten");
$_MAIN_MESSAGE.="";

$NEWSLETTER=new tm_NL();

$nl_grp_id=getVar("nl_grp_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

if ($set=="aktiv") {
	$NEWSLETTER->setGRPAktiv($nl_grp_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}
if ($set=="standard") {
	$NEWSLETTER->setGRPStd($nl_grp_id,$val);//val???
	$_MAIN_MESSAGE.=tm_message_success(___("Neue Standardgruppe wurde definiert."));
}
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $NEWSLETTER->delGRP($nl_grp_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}


$GRP=$NEWSLETTER->getGroup(0,0,1);
$ncg=count($GRP);

$editURLPara=tmObjCopy($mSTDURL);
$editURLPara->addParam("act","nl_grp_edit");

$shownlURLPara=tmObjCopy($mSTDURL);
$shownlURLPara->addParam("act","nl_list");

$addnlURLPara=tmObjCopy($mSTDURL);
$addnlURLPara->addParam("act","nl_new");

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("act","nl_grp_list");
$aktivURLPara->addParam("set","aktiv");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("act","nl_grp_list");
$delURLPara->addParam("set","delete");

$stdURLPara=tmObjCopy($mSTDURL);
$stdURLPara->addParam("act","nl_grp_list");
$stdURLPara->addParam("set","standard");

$statURLPara=tmObjCopy($mSTDURL);
$statURLPara->addParam("act","statistic");
$statURLPara->addParam("set","nlg");

//show log summary
//search for logs, only section
$search_log['object']="nl_grp";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td><b>&nbsp;</b>".
						"</td>".
						"<td><b>".___("Name")."</b>".
						"</td>".
						"<td><b>".___("Beschreibung")."</b>".
						"</td>".
						"<td><b>".___("Aktiv")."</b>".
						"</td>".
						"<td>...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";

for ($nccg=0;$nccg<$ncg;$nccg++) {
	if ($nccg%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($GRP[$nccg]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	$editURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$shownlURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$shownlURLPara_=$shownlURLPara->getAllParams();

	$addnlURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$addnlURLPara_=$addnlURLPara->getAllParams();

	$aktivURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$delURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$stdURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$stdURLPara_=$stdURLPara->getAllParams();

	$statURLPara->addParam("nl_grp_id",$GRP[$nccg]['id']);
	$statURLPara_=$statURLPara->getAllParams();

	$_MAIN_OUTPUT.= "<tr id=\"row_".$nccg."\" bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$nccg."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$nccg."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_grp_list_".$GRP[$nccg]['id']."')\" onmouseout=\"hideToolTip();\">";
	//wenn standardgruppe, dann icon anzeigen
	if ($GRP[$nccg]['standard']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("page_white_lightning.png",___("Diese Gruppe ist die Standardgruppe"));
	}
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_grp_list_".$GRP[$nccg]['id']."')\" onmouseout=\"hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Newslettergruppe bearbeiten")."\">".display($GRP[$nccg]['name'])."</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_adr_grp_list_".$GRP[$nccg]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="<b>".display($GRP[$nccg]['name'])."</b>";
	$_MAIN_OUTPUT.= "<br><font size=\"-1\">".display($GRP[$nccg]['descr'])."</font>";
	$_MAIN_OUTPUT.= "<br>ID: ".$GRP[$nccg]['id']." ";

	if ($GRP[$nccg]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png", ___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png", ___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}
	//wenn standardgruppe, dann icon anzeigen
	if ($GRP[$nccg]['standard']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("page_white_lightning.png",___("Diese Gruppe ist die Standardgruppe"))."&nbsp;".___("Standardgruppe");
	}
	$_MAIN_OUTPUT.="<br>".$GRP[$nccg]['nl_count']." ".___("Newsletter").
									"<br>".sprintf(___("Erstellt am: %s von %s"),$GRP[$nccg]['created'],$GRP[$nccg]['author']).
									"<br>".sprintf(___("Bearbeitet am: %s von %s"),$GRP[$nccg]['updated'],$GRP[$nccg]['editor']).
									"";

	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($GRP[$nccg]['descr']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	//wenn gruppe keine standardgruppe ist, dann link zum deaktivieren, deaktivierete gruppen koennen naemlich keine standardgruppe sein
	if ($GRP[$nccg]['standard']!=1) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	}
	if ($GRP[$nccg]['aktiv']==1) {
		//aktiv
		$_MAIN_OUTPUT.=  tm_icon("tick.png", ___("Aktiv"));
	} else {
		//inaktiv
		$_MAIN_OUTPUT.=  tm_icon("cancel.png", ___("Inaktiv"));
	}
	//link schliessen
	if ($GRP[$nccg]['standard']!=1) {
		$_MAIN_OUTPUT.= "</a>";
	}
	
	$nlcount_notpl=$NEWSLETTER->countNL($GRP[$nccg]['id'],Array("is_template"=>0));
	$nlcount_tpl=$NEWSLETTER->countNL($GRP[$nccg]['id'],Array("is_template"=>1));	
	
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Newslettergruppe bearbeiten")."\">".tm_icon("pencil.png",___("Newslettergruppe bearbeiten"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$addnlURLPara_."\" title=\"".___("Neuen Newsletter in dieser Gruppe erstellen")."\">".tm_icon("newspaper_add.png",___("Neuen Newsletter in dieser Gruppe erstellen"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$shownlURLPara_."\" title=\"".___("Alle Newsletter in dieser Gruppe anzeigen")."\">".tm_icon("newspaper_go.png",___("Alle Newsletter in dieser Gruppe anzeigen"))."&nbsp;"."</a>";//.$GRP[$nccg]['nl_count']
	$_MAIN_OUTPUT.=$nlcount_notpl."/".$nlcount_tpl;
	//$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$statURLPara_."\" title=\"Statistik anzeigen\"><img src=\"".$tm_iconURL."/chart_pie.png\" border=\"0\"></a>";
	if ($GRP[$nccg]['standard']==1) {
		//wenn gruppe standard ist, dann bildchen anzeigen, wird auch neben id angezeigt
		//$_MAIN_OUTPUT.=  "&nbsp;<img src=\"".$tm_iconURL."/page_white_lightning.png\" border=\"0\">";
	} else {
		//wenn gruppe aktiv ist, dann darf man sie als standard definieren
		if ($GRP[$nccg]['aktiv']==1) {
			$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$stdURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Newslettergruppe %s als Standard definieren"),display($GRP[$nccg]['name']))."')\" title=\"".___("Diese Newslettergruppe als Standardgruppe")."\">".tm_icon("page_white_go.png",___("Diese Newslettergruppe als Standardgruppe"))."</a>";
		}
		//loeschen
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Newslettergruppe %s löschen und Newsletter dieser Gruppe der Standardgruppe zuordnen?"),display($GRP[$nccg]['name']))."')\" title=\"".___("Newslettergruppe löschen")."\">".tm_icon("cross.png",___("Newslettergruppe löschen"))."</a>";
	}

	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$GRP[$nccg]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require_once(TM_INCLUDEPATH_GUI."/nl_grp_list_legende.inc.php");
?>