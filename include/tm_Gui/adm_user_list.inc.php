<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/11 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

$_MAIN_DESCR=___("Benutzer/Authoren verwalten");
$_MAIN_MESSAGE.="";

$USERS=new tm_CFG();

$u_id=getVar("u_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

if ($set=="aktiv") {
	if (!tm_DEMO()) $USERS->setUSERAktiv($u_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Benutzer wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Benutzer wurde de-aktiviert."));
	}
}
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $USERS->delUSER($u_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Benutzer wurde gelöscht."));
}

$USER=$USERS->getUsers();
$uc=count($USER);

$editURLPara=tmObjCopy($mSTDURL);

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("set","aktiv");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("set","delete");

#$statURLPara=tmObjCopy($mSTDURL);
#$statURLPara->addParam("act","statistic");
#$statURLPara->addParam("set","user");

//show log summary
//search for logs, only section
$search_log['object']="usr";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td><b>&nbsp;</b>".
						"</td>".
						"<td><b>".___("Name")."</b>".
						"</td>".
						"<td><b>".___("E-Mail")."</b>".
						"</td>".
						"<td><b>".___("Layout")."</b>".
						"</td>".
						"<td><b>".___("Sprache")."</b>".
						"</td>".
						"<td><b>".___("Startseite")."</b>".
						"</td>".
						"<td><b>".___("Aktiv")."</b>".
						"</td>".
						"<td>...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";

for ($ucc=0;$ucc<$uc;$ucc++) {
	if ($ucc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($USER[$ucc]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	$editURLPara->addParam("act","adm_user_edit");
	if ($USER[$ucc]['name']==$LOGIN->USER['name']) {
		$editURLPara->addParam("act","user");
	}
	$editURLPara->addParam("u_id",$USER[$ucc]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$aktivURLPara->addParam("act","adm_user_list");
	if ($USER[$ucc]['name']==$LOGIN->USER['name']) {
		$aktivURLPara->addParam("act","user");
	}
	$aktivURLPara->addParam("u_id",$USER[$ucc]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$delURLPara->addParam("act","adm_user_list");
	if ($USER[$ucc]['name']==$LOGIN->USER['name']) {
		$delURLPara->addParam("act","user");
	}
	$delURLPara->addParam("u_id",$USER[$ucc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	#$statURLPara->addParam("adr_grp_id",$USER[$ucc]['id']);
	#$statURLPara_=$statURLPara->getAllParams();

	$_MAIN_OUTPUT.= "<tr id=\"row_".$ucc."\"  bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$ucc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$ucc."','".$bgcolor."');\">";
	#$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_grp_list_".$USER[$ucc]['id']."')\" onmouseout=\"hideToolTip();\">";
	$_MAIN_OUTPUT.= "<td>";
	//wenn standardgruppe, dann icon anzeigen
	if ($USER[$ucc]['name']==$LOGIN->USER['name']) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("star.png",___("Ihr Bentzer"));
	}

	if ($USER[$ucc]['admin']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("user_gray.png",___("Admin"));
	}
	if ($USER[$ucc]['manager']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("user_red.png",___("Verwalter"));
	}
	if ($USER[$ucc]['expert']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("tux.png",___("Erfahrener Benutzer"));
	}

	if ($USER[$ucc]['debug']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("application_view_detail.png",___("Debugging"));
	}
	if ($USER[$ucc]['debug_lang']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("application_view_list.png",___("Debugging für Übersetzungen"));
	}

	
	if ($USER[$ucc]['demo']==1) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("emoticon_smile.png",___("Demo Benutzer"));
	}

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_user_list_".$USER[$ucc]['id']."')\" onmouseout=\"hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\"  title=\"".___("Benutzer bearbeiten")."\">".display($USER[$ucc]['name'])."</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_user_list_".$USER[$ucc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="<b>".display($USER[$ucc]['name'])."</b>";
	$_MAIN_OUTPUT.= "<br><font size=\"-1\">".display($USER[$ucc]['email'])."</font>";
	$_MAIN_OUTPUT.= "<br>ID: ".$USER[$ucc]['id']." ";
	$_MAIN_OUTPUT.= "<br>".___("Layout").": ".$USER[$ucc]['style']." ";
	$_MAIN_OUTPUT.= "<br>".___("Startseite").": ".$USER[$ucc]['startpage']." ";
	$_MAIN_OUTPUT.= "<br>".___("Sprache").": ".$USER[$ucc]['lang']." ";
	if ($USER[$ucc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}
	if ($USER[$ucc]['name']==$LOGIN->USER['name']) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("star.png",___("Ihr Bentzer"))."&nbsp;";
	}
	if ($USER[$ucc]['admin']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("user_gray.png",___("Admin"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Admin");
	}
	if ($USER[$ucc]['manager']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("user_red.png",___("Verwalter"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Verwalter");
	}
	if ($USER[$ucc]['expert']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tux.png",___("Erfahrener Benutzer"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Erfahrener Benutzer");
	}
	if ($USER[$ucc]['debug']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("application_view_detail.png",___("Debugging"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Debugging");
	}
	if ($USER[$ucc]['debug_lang']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("application_view_list.png",___("Debugging für Übersetzungen"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Debugging für Übersetzungen");
	}
	if ($USER[$ucc]['demo']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("emoticon_smile.png",___("Demo Benutzer"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Demo Benutzer");
	}

	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"mailto:".$USER[$ucc]['email']."\" title=\"".___("Benutzer kontaktieren")."\">".display($USER[$ucc]['email'])."</a>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($USER[$ucc]['style']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($USER[$ucc]['lang']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($USER[$ucc]['startpage']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	//wenn gruppe keine standardgruppe ist, dann link zum deaktivieren, deaktivierete gruppen koennen naemlich keine standardgruppe sein
	if ($USER[$ucc]['admin']!=1) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	}

	if ($USER[$ucc]['aktiv']==1) {
		//aktiv
		$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Aktiv"))."&nbsp;";
	} else {
		//inaktiv
		$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
	}

	if ($USER[$ucc]['admin']!=1) {
		$_MAIN_OUTPUT.= "</a>";
	}

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Benutzer bearbeiten")."\">".tm_icon("pencil.png",___("Benutzer bearbeiten"))."</a>";
	#$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$statURLPara_."\" title=\"".___("Statistik anzeigen")."\">".tm_icon("chart_pie.png",___("Statistik anzeigen"))."</a>";
	//loeschen
	if ($USER[$ucc]['admin']!=1) {
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Benutzer %s löschen?"),display($USER[$ucc]['name']))."')\" title=\"".___("Benutzer löschen")."\">".tm_icon("cross.png",___("Benutzer löschen"))."</a>";
	}

	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$USER[$ucc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require_once(TM_INCLUDEPATH_GUI."/adm_user_list_legende.inc.php");
?>