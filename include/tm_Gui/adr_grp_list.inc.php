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

$_MAIN_DESCR=___("Adressgruppen verwalten");
$_MAIN_MESSAGE.="";

$ADDRESS=new tm_ADR();
$QUEUE=new tm_Q();
$adr_grp_id=getVar("adr_grp_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

if ($set=="aktiv") {
	$ADDRESS->setGRPAktiv($adr_grp_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}

if ($set=="prod" && $doit==1) {
	$ADDRESS->setGRPProd($adr_grp_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag ist Produktiv."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag ist nicht Produktiv."));
	}
}


if ($set=="standard") {
	$ADDRESS->setGRPStd($adr_grp_id,$val);
	$_MAIN_MESSAGE.=tm_message_success(___("Neue Standardgruppe wurde definiert."));
}
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $ADDRESS->delGRP($adr_grp_id,1,0);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}
if ($set=="deleteall" && $doit==1) {
	if (!tm_DEMO()) $ADDRESS->delGRP($adr_grp_id,0,1);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag und die zugeordneten Adressen wurden gelöscht."));
}
$GRP=$ADDRESS->getGroup(0,0,0,1);

//sort array:
	$sortIndex=getVar("si");
if (empty($sortIndex)) {
	$sortIndex="id";
}
$sortType=getVar("st");
if (empty($sortType)) {
	$sortType="0";//asc
}
$GRP=sort_array($GRP,$sortIndex,$sortType);

//count entries:
$acg=count($GRP);

$editURLPara=tmObjCopy($mSTDURL);
$editURLPara->addParam("act","adr_grp_edit");

$addadrURLPara=tmObjCopy($mSTDURL);
$addadrURLPara->addParam("act","adr_new");

$showadrURLPara=tmObjCopy($mSTDURL);
$showadrURLPara->addParam("act","adr_list");

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("act","adr_grp_list");
$aktivURLPara->addParam("set","aktiv");

$prodURLPara=tmObjCopy($mSTDURL);
$prodURLPara->addParam("act","adr_grp_list");
$prodURLPara->addParam("set","prod");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("act","adr_grp_list");
$delURLPara->addParam("set","delete");

$delallURLPara=tmObjCopy($mSTDURL);
$delallURLPara->addParam("act","adr_grp_list");
$delallURLPara->addParam("set","deleteall");

$stdURLPara=tmObjCopy($mSTDURL);
$stdURLPara->addParam("act","adr_grp_list");
$stdURLPara->addParam("set","standard");

$statURLPara=tmObjCopy($mSTDURL);
$statURLPara->addParam("act","statistic");
$statURLPara->addParam("set","adrg");

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("act","adr_grp_list");
$sortURLPara_=$sortURLPara->getAllParams();

$refreshRCPTListURLPara=tmObjCopy($mSTDURL);
$refreshRCPTListURLPara->addParam("act","queue_send");
$refreshRCPTListURLPara->addParam("set","adrg");

$showqURLPara=tmObjCopy($mSTDURL);
$showqURLPara->addParam("act","queue_list");
//vars loeschen da q liste sonst bei limit offset sort etc beeintraechtigt wird.
$showqURLPara->delParam("offset");
$showqURLPara->delParam("limit");
$showqURLPara->delParam("st");
$showqURLPara->delParam("si");


//show log summary
//search for logs, only section
$search_log['object']="adr_grp";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");


$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td><b>ID</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Name")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=name&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=name&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Name (öffentlich)")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=public_name&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=public_name&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Beschreibung")."</b>".
						"</td>".
						"<td><b>".___("Adressen")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=adr_count&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=adr_count&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Aktiv")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Pro")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=prod&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=prod&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".

						"<td width=\"100\">...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";

for ($accg=0;$accg<$acg;$accg++) {
	if ($accg%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($GRP[$accg]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	if ($GRP[$accg]['prod']!=1) {
		$new_prod=1;
	} else {
		$new_prod=0;
	}

	//anz. gueltige adressen
	$valid_adr_c=$ADDRESS->countValidADR($GRP[$accg]['id']);

	$editURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$addadrURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$addadrURLPara_=$addadrURLPara->getAllParams();

	$showadrURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$showadrURLPara_=$showadrURLPara->getAllParams();

	$aktivURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$prodURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$prodURLPara->addParam("val",$new_prod);
	$prodURLPara_=$prodURLPara->getAllParams();

	$delURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$delallURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$delallURLPara_=$delallURLPara->getAllParams();

	$stdURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$stdURLPara_=$stdURLPara->getAllParams();

	$statURLPara->addParam("adr_grp_id",$GRP[$accg]['id']);
	$statURLPara_=$statURLPara->getAllParams();

	$refreshRCPTListURLPara->addParam("grp_id",$GRP[$accg]['id']);
	$refreshRCPTListURLPara_=$refreshRCPTListURLPara->getAllParams();
	
	$showqURLPara->addParam("grp_id",$GRP[$accg]['id']);
	$showqURLPara_=$showqURLPara->getAllParams();

	$_MAIN_OUTPUT.= "<tr id=\"row_".$accg."\"  bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$accg."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$accg."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_grp_list_".$GRP[$accg]['id']."')\" onmouseout=\"hideToolTip();\">";

	//wenn standardgruppe, dann icon anzeigen
	$_MAIN_OUTPUT.=  $GRP[$accg]['id'];
	if ($GRP[$accg]['standard']==1) {
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("page_white_lightning.png",___("Diese Gruppe ist die Standardgruppe"));
	}
	/*
	if ($GRP[$accg]['prod']==1) {
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("rosette.png",___("Diese Gruppe ist produktiv"));
	}
	*/
	if ($GRP[$accg]['public']==1) {
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("cup.png",___("Diese Gruppe ist öffentlich"));
	}
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_grp_list_".$GRP[$accg]['id']."')\" onmouseout=\"hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\"  title=\"".___("Adressgruppe bearbeiten")."\">".display($GRP[$accg]['name'])."</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_adr_grp_list_".$GRP[$accg]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="<b>".display($GRP[$accg]['name'])."</b>";
	if ($GRP[$accg]['public']==1) {
		$_MAIN_OUTPUT.= "<br>".tm_icon("cup.png",___("Diese Gruppe ist öffentlich"))."&nbsp;".___("Diese Gruppe ist öffentlich");
		$_MAIN_OUTPUT.="<br>".___("Name (öffentlich)").": <b>".display($GRP[$accg]['name'])."</b>";
	}

	if ($GRP[$accg]['prod']==1) {
		$_MAIN_OUTPUT.= "<br>".tm_icon("rosette.png",___("Diese Gruppe ist produktiv"))."&nbsp;".___("Diese Gruppe ist produktiv");
	} else {
		$_MAIN_OUTPUT.= "<br>".tm_icon("bullet_error.png",___("Diese Gruppe ist nicht produktiv"),"","","","rosette.png")."&nbsp;".___("Diese Gruppe ist nicht produktiv");
	}


	$_MAIN_OUTPUT.= "<br><font size=\"-1\">".display($GRP[$accg]['descr'])."</font>";
	$_MAIN_OUTPUT.= "<br>ID: ".$GRP[$accg]['id']." ";
	if ($GRP[$accg]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}
	//wenn standardgruppe, dann icon anzeigen
	if ($GRP[$accg]['standard']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("page_white_lightning.png",___("Diese Gruppe ist die Standardgruppe"))."&nbsp;".___("Standardgruppe");
	}
	$_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen"),display($GRP[$accg]['adr_count'])).
									"&nbsp (".sprintf(___("%s gültige Adressen"),"<b>".display($valid_adr_c)."</b>").")".
									"<br>".sprintf(___("Erstellt am: %s von %s"),display($GRP[$accg]['created']),display($GRP[$accg]['author'])).
									"<br>".sprintf(___("Bearbeitet am: %s von %s"),display($GRP[$accg]['updated']),display($GRP[$accg]['editor'])).
									"";

	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($GRP[$accg]['public_name']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($GRP[$accg]['descr']);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($GRP[$accg]['adr_count'])." / <b>".display($valid_adr_c)."</b>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	//wenn gruppe keine standardgruppe ist, dann link zum deaktivieren, deaktivierete gruppen koennen naemlich keine standardgruppe sein
	if ($GRP[$accg]['standard']!=1) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	}
	if ($GRP[$accg]['aktiv']==1) {
		//aktiv
		$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Aktiv"))."&nbsp;";
	} else {
		//inaktiv
		$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
	}
	//link schliessen
	if ($GRP[$accg]['standard']!=1) {
		$_MAIN_OUTPUT.= "</a>";
	}
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	//wenn gruppe keine standardgruppe ist, dann link zum deaktivieren, deaktivierete gruppen koennen naemlich keine standardgruppe sein
	
 	
	
	if ($GRP[$accg]['prod']==1) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$prodURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Adressgruppe %s als nicht produktiv markieren?"),display($GRP[$accg]['name']))."')\" title=\"".sprintf(___("Adressgruppe %s als nicht produktiv markieren?"),display($GRP[$accg]['name']))."\">";
		$_MAIN_OUTPUT.= tm_icon("rosette.png",sprintf(___("Adressgruppe %s als nicht produktiv markieren?"),display($GRP[$accg]['name'])))."&nbsp;";
		$_MAIN_OUTPUT.= "</a>";
	} else {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$prodURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Adressgruppe %s als produktiv markieren?"),display($GRP[$accg]['name']))."')\" title=\"".sprintf(___("Adressgruppe %s als produktiv markieren?"),display($GRP[$accg]['name']))."\">";
		$_MAIN_OUTPUT.= tm_icon("bullet_error.png",sprintf(___("Adressgruppe %s als produktiv markieren?"),display($GRP[$accg]['name'])),"","","","rosette.png")."&nbsp;";
		$_MAIN_OUTPUT.= "</a>";
	}
	//link schliessen
	$_MAIN_OUTPUT.= "</a>";
	$_MAIN_OUTPUT.= "</td>";


	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Adressgruppe bearbeiten")."\">".tm_icon("pencil.png",___("Adressgruppe bearbeiten"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$addadrURLPara_."\" title=\"".___("Neue Adresse in dieser Gruppe anlegen")."\">".tm_icon("vcard_add.png",___("Neue Adresse in dieser Gruppe anlegen"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$showadrURLPara_."\" title=\"".___("Alle Adressen in dieser Gruppe anzeigen")."\">".tm_icon("group_go.png",___("Alle Adressen in dieser Gruppe anzeigen"))."</a>";
	#."&nbsp;".$GRP[$accg]['adr_count']."</a>";
	$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$statURLPara_."\" title=\"".___("Statistik anzeigen")."\">".tm_icon("chart_pie.png",___("Statistik anzeigen"))."</a>";
	if ($GRP[$accg]['standard']==1) {
		//wenn gruppe standard ist, dann bildchen anzeigen, wird auch neben id angezeigt
		//$_MAIN_OUTPUT.=  "&nbsp;<img src=\"".$tm_iconURL."/page_white_lightning.png\" border=\"0\">";
	} else {
		//wenn gruppe aktiv ist, dann darf man sie als standard definieren
		if ($GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$stdURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Adressgruppe %s als Standard definieren."),display($GRP[$accg]['name']))."')\" title=\"".___("Diese Adressgruppe als Standardgruppe")."\">".tm_icon("page_white_go.png",___("Diese Adressgruppe als Standardgruppe"))."</a>";
		}
		//loeschen
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Adressgruppe %s löschen und Adressen dieser Gruppe der Standardgruppe zuordnen und Verknüpfungen zu Formularen aufheben?"),display($GRP[$accg]['name']))."')\" title=\"".___("Adressgruppe löschen")."\">".tm_icon("cross.png",___("Adressgruppe löschen"))."</a>";
		//bomb! gruppe UND Adressen loeschen!!!
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delallURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Adressgruppe %s UND Adressen dieser Gruppe löschen?"),display($GRP[$accg]['name']))."')\" title=\"".___("Adressgruppe und Adressen löschen")."\">".tm_icon("bomb.png",___("Adressgruppe und Adressen löschen"))."</a>";

	}
	if ($QUEUE->countQ($nl_id=0,$GRP[$accg]['id']) >0) {
				$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$showqURLPara_."\" title=\"".___("Q für diese Gruppe anzeigen")."\">".tm_icon("hourglass_go.png",___("Q für diese Gruppe anzeigen"))."&nbsp;";
	}
	#ok, aber nur fuer queues die noch nicht versendet wurden etc pp, und die noch nich gestartet wurden etc.
	if (($QUEUE->countQ($nl_id=0,$GRP[$accg]['id'],$status=2) + $QUEUE->countQ($nl_id=0,$GRP[$accg]['id'],$status=5)) > 0) {
		#$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$sendFastURLPara_."\" title=\"".___("Adressen nachfassen / Empfängerliste aktualisieren")."\">".tm_icon("arrow_switch.png",___("Adressen nachfassen / Empfängerliste aktualisieren"),"","","","email_go.png")."</a>";
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$refreshRCPTListURLPara_."\" title=\"".___("Adressen nachfassen / Empfängerliste aktualisieren")."\">".tm_icon("arrow_switch.png",___("Adressen nachfassen / Empfängerliste aktualisieren"),"","","","email_go.png")."</a>";
	}


	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$GRP[$accg]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");


	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require_once(TM_INCLUDEPATH_GUI."/adr_grp_list_legende.inc.php");
?>