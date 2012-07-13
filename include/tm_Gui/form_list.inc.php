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

$_MAIN_DESCR=___("Formulare verwalten");
$_MAIN_MESSAGE.="";

$FORMULAR=new tm_FRM();
$ADDRESS=new tm_ADR();
$HOSTS=new tm_Host();

$adr_grp_id=getVar("adr_grp_id");

$frm_id=getVar("frm_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

if ($set=="aktiv") {
	$FORMULAR->setAktiv($frm_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}
if ($set=="standard") {
	$FORMULAR->setStd($frm_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Neues Standardformular wurde definiert."));
}
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $FORMULAR->delForm($frm_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}
if ($set=="copy" && $doit==1) {
	$FORMULAR->copyForm($frm_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde kopiert."));
}

if ($adr_grp_id!=0)
{
	$FRM=$FORMULAR->getForm(0,0,0,$adr_grp_id);//id,offset,limit,group
} else {
	$FRM=$FORMULAR->getForm(0,0,0,0);//id,offset,limit,group
}
$ac=count($FRM);

$sortIndex=getVar("si");
if (empty($sortIndex)) {
	$sortIndex="id";
}
$sortType=getVar("st");
if (empty($sortType)) {
	$sortType="0";//asc
}

$FRM=sort_array($FRM,$sortIndex,$sortType);

$stdURLPara=tmObjCopy($mSTDURL);
$stdURLPara->addParam("act","form_list");
$stdURLPara->addParam("set","standard");

$editTplPara=tmObjCopy($mSTDURL);
$editTplPara->addParam("act","filemanager_file_edit");
$editTplPara->addParam("file_section","frm");

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("act","form_list");
$sortURLPara_=$sortURLPara->getAllParams();

$editURLPara=tmObjCopy($mSTDURL);
$editURLPara->addParam("act","form_edit");

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("act","form_list");
$aktivURLPara->addParam("set","aktiv");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("act","form_list");
$delURLPara->addParam("set","delete");

$copyURLPara=tmObjCopy($mSTDURL);
$copyURLPara->addParam("act","form_list");
$copyURLPara->addParam("set","copy");

$statURLPara=tmObjCopy($mSTDURL);
$statURLPara->addParam("act","statistic");
$statURLPara->addParam("set","frm");

//show log summary
//search for logs, only section
$search_log['object']="frm";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td width=150><b>".___("ID")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Name")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=name&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=name&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("Beschreibung")."</b>".
						"</td>".
						"<td>".___("Gruppen")."</td>".
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
	if ($FRM[$acc]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	/*
	$created_date=strftime("%d-%m-%Y",mk_microtime($FRM[$acc]['created']));
	$updated_date=strftime("%d-%m-%Y",mk_microtime($FRM[$acc]['updated']));
	*/
	$created_date=$FRM[$acc]['created'];
	$updated_date=$FRM[$acc]['updated'];

	$author=$FRM[$acc]['author'];
	$editor=$FRM[$acc]['editor'];

	$editTplPara->addParam("file_name","Form_".$FRM[$acc]['id'].".html");
	$editTplPara_=$editTplPara->getAllParams();
	
	$editURLPara->addParam("frm_id",$FRM[$acc]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$aktivURLPara->addParam("frm_id",$FRM[$acc]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$stdURLPara->addParam("frm_id",$FRM[$acc]['id']);
	$stdURLPara_=$stdURLPara->getAllParams();
	
	$delURLPara->addParam("frm_id",$FRM[$acc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$copyURLPara->addParam("frm_id",$FRM[$acc]['id']);
	$copyURLPara_=$copyURLPara->getAllParams();

	$statURLPara->addParam("frm_id",$FRM[$acc]['id']);
	$statURLPara_=$statURLPara->getAllParams();

	$_MAIN_OUTPUT.= "<tr id=\"row_".$acc."\"  bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$acc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');\">";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_list_".$FRM[$acc]['id']."')\" onmouseout=\"hideToolTip();\" width=120>";
	$_MAIN_OUTPUT.= "<b>".$FRM[$acc]['id']."</b>&nbsp;&nbsp;";

	//wenn standard, dann icon anzeigen
	if ($FRM[$acc]['standard']==1) {
		$_MAIN_OUTPUT.= "&nbsp;".tm_icon("page_white_lightning.png",___("Diese Formular ist das Standardformular"));
	}
	//markierung aktiv bei subscribe
	if ($FRM[$acc]['subscribe_aktiv']==1) {
		$_MAIN_OUTPUT.=  tm_icon("user_green.png",___("Aktiv"))."&nbsp;";
	} else {
		$_MAIN_OUTPUT.=  tm_icon("user_red.png",___("Inaktiv"))."&nbsp;";
	}
	//markierung captcha
	if ($FRM[$acc]['use_captcha']==1) {
		$_MAIN_OUTPUT.=  tm_icon("sport_8ball.png",___("Captcha")).$FRM[$acc]['digits_captcha']."&nbsp;";
	}
	//markierung blacklist
	if ($FRM[$acc]['check_blacklist']==1) {
		$_MAIN_OUTPUT.=  tm_icon("ruby.png",___("Blacklist"))."&nbsp;";
	}
	//markierung proofing
	if ($FRM[$acc]['proof']==1) {
		$_MAIN_OUTPUT.=  tm_icon("medal_gold_1.png",___("Proofing aktiv"))."&nbsp;";
	}
	//markierung doubleoptin
	if ($FRM[$acc]['double_optin']==1) {
		$_MAIN_OUTPUT.=  tm_icon("arrow_refresh_small.png",___("Double-Opt-In"))."&nbsp;";
	}

	//allow multiple public groups?
	if ($FRM[$acc]['multiple_pubgroup']==1) {
		$_MAIN_OUTPUT.=  tm_icon("group.png",___("Mehrere Gruppen"))."&nbsp;";
	}
	
	//force pubgroup
	if ($FRM[$acc]['force_pubgroup']==1) {
		$_MAIN_OUTPUT.=  tm_icon("group_error.png",___("Auswahl öffentlicher Gruppen erzwingen"))."&nbsp;";
	} else {
		#$_MAIN_OUTPUT.=  tm_icon("group.png",___("Auswahl öffentlicher Gruppen nicht erzwingen"));
	}

	//update/overwrite pubgroup refs
	if ($FRM[$acc]['force_pubgroup']==1) {
		$_MAIN_OUTPUT.=  tm_icon("group_gear.png",___("Gruppen überschreiben"))."&nbsp;";
	} else {
		$_MAIN_OUTPUT.=  tm_icon("group_link.png",___("Gruppen aktualisieren, nur Neue hinzufügen"))."&nbsp;";
	}

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_list_".$FRM[$acc]['id']."')\" onmouseout=\"hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\"  title=\"".___("Formular bearbeiten")."\">".display($FRM[$acc]['name'])."</a>";
	$_MAIN_OUTPUT.= "<div id=\"tt_adr_list_".$FRM[$acc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.= "<table border=0 width=600><tbody><tr><td width=200 valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.= "<font size=-1>";
	$_MAIN_OUTPUT.= "<b>".display($FRM[$acc]['name'])."</b>";
	$_MAIN_OUTPUT.= "<br>".display($FRM[$acc]['descr']);
	
	if ($FRM[$acc]['standard']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("page_white_lightning.png",___("Dieses Formular ist das Standardformular"))."&nbsp;".___("Standardformular");
	}
		
	if (!empty($FRM[$acc]['action_url'])) {
		$_MAIN_OUTPUT.="<br>".___("URL").":&nbsp;".display($FRM[$acc]['action_url']);
	} else {
		$_MAIN_OUTPUT.="<br>".___("URL").":&nbsp;[PHP_SELF]";
	}

	$_MAIN_OUTPUT.= "<br>ID: ".$FRM[$acc]['id']." ";
	if ($FRM[$acc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"));
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"));
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}
	if ($FRM[$acc]['subscribe_aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("user_green.png",___("Aktiv"));
		$_MAIN_OUTPUT.=  ___("Neuangemeldete Adressen sind aktiv");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("user_red.png",___("Inaktiv"));
		$_MAIN_OUTPUT.=  ___("Neuangemeldete Adressen sind deaktiviert");
	}
	//captcha
	if ($FRM[$acc]['use_captcha']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("sport_8ball.png",___("Captcha"))."&nbsp;".___("Captcha").",&nbsp;";
		$_MAIN_OUTPUT.=  sprintf(___("%s Ziffern"),$FRM[$acc]['digits_captcha']);
		$_MAIN_OUTPUT.=  "<br>&nbsp;&nbsp;&nbsp;".___("Fehlermeldung").": <em>".display($FRM[$acc]['captcha_errmsg'])."</em>";
	}
	//markierung blacklist
	if ($FRM[$acc]['check_blacklist']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("ruby.png",___("Blacklist"))."&nbsp;".___("Blacklist prüfen").",&nbsp;";
		$_MAIN_OUTPUT.=  "<br>&nbsp;&nbsp;&nbsp;".___("Fehlermeldung").": <em>".display($FRM[$acc]['blacklist_errmsg'])."</em>";
	}

	if ($FRM[$acc]['proof']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("medal_gold_1.png",___("Proofing aktiv"))."&nbsp;".___("Proofing aktiv")."";
	}

	//markierung doubleoptin
	if ($FRM[$acc]['double_optin']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("arrow_refresh_small.png",___("Double-Opt-In"))."&nbsp;".___("Double-Opt-In");
	}

	//allow multiple public groups?
	if ($FRM[$acc]['multiple_pubgroup']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("group.png",___("Mehrere Gruppen"))."&nbsp;".___("Besucher kann mehrere Gruppen wählen");
	}

	//force pubgroup
	if ($FRM[$acc]['force_pubgroup']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("group_error.png",___("Auswahl öffentlicher Gruppen erzwingen"))."&nbsp;".___("Auswahl öffentlicher Gruppen erzwingen").",&nbsp;";
		$_MAIN_OUTPUT.=  "<br>&nbsp;&nbsp;&nbsp;".___("Fehlermeldung").": <em>".display($FRM[$acc]['pubgroup_errmsg'])."</em>";
	} else {
		#$_MAIN_OUTPUT.=  "<br>".tm_icon("group.png",___("Auswahl öffentlicher Gruppen nicht erzwingen"))."&nbsp;".___("Auswahl öffentlicher Gruppen nicht erzwingen").",&nbsp;";
	}

	//update/overwrite pubgroup refs
	if ($FRM[$acc]['force_pubgroup']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("group_gear.png",___("Gruppen überschreiben"))."&nbsp;".___("Gruppen überschreiben").",&nbsp;";
	} else {
		#$_MAIN_OUTPUT.=  "<br>".tm_icon("group_link.png",___("Gruppen aktualisieren, nur Neue hinzufügen"))."&nbsp;".___("Gruppen aktualisieren, nur Neue hinzufügen").",&nbsp;";
	}
	
	//newsletters that are sent as double-optin mail and greeting mail!

	$NEWSLETTER=new tm_NL();
	
	$_MAIN_OUTPUT.= "<br>".tm_icon("newspaper_link.png",___("Double Opt-in Mail"))."&nbsp;".___("Newsletter welcher als Double-Opt-In Mail verschickt wird").":";

	if (check_dbid($FRM[$acc]['nl_id_doptin'])) {
		$NLDOptin=$NEWSLETTER->getNL($FRM[$acc]['nl_id_doptin']);
		$_MAIN_OUTPUT.= "<br>".display($NLDOptin[0]['subject']);
	} else {
		$_MAIN_OUTPUT.= ___("-- STANDARD --");
	}

	$_MAIN_OUTPUT.= "<br>".tm_icon("newspaper_add.png",___("Bestätigungs-Mail"))."&nbsp;".___("Newsletter welcher als Bestätigungs-Mail verschickt wird").":";

	if (check_dbid($FRM[$acc]['nl_id_greeting'])) {
		$NLGreeting=$NEWSLETTER->getNL($FRM[$acc]['nl_id_greeting']);
		$_MAIN_OUTPUT.= "<br>".display($NLGreeting[0]['subject']);
	} else {
		$_MAIN_OUTPUT.= ___("-- STANDARD --");
	}

	$_MAIN_OUTPUT.= "<br>".tm_icon("newspaper_go.png",___("Aktulisierungs-Mail"))."&nbsp;".___("Newsletter welcher als Aktualisierungs-Mail verschickt wird").":";
	
	if (check_dbid($FRM[$acc]['nl_id_update'])) {
		$NLUpdate=$NEWSLETTER->getNL($FRM[$acc]['nl_id_update']);
		$_MAIN_OUTPUT.= "<br>".display($NLUpdate[0]['subject']);
	} else {
		$_MAIN_OUTPUT.= ___("-- STANDARD --");
	}
	$_MAIN_OUTPUT.= 	"<br><br>".sprintf(___("Anmeldungen: %s"),$FRM[$acc]['subscriptions']);
	$_MAIN_OUTPUT.= "</font>";	
	$_MAIN_OUTPUT.= "</td><td valign=\"top\" align=\"left\">";	
	$_MAIN_OUTPUT.= "<font size=-1>";	
	$_MAIN_OUTPUT.= "<br><br>email= ".display($FRM[$acc]['email'])." &nbsp; [] &nbsp; <em>".display($FRM[$acc]['email_errmsg'])."</em>".
							"<br><br>f0= ".display($FRM[$acc]['f0'])." &nbsp; [".display($FRM[$acc]['f0_value'])."] &nbsp; <em>".display($FRM[$acc]['f0_errmsg'])."</em>".
							"<br>f1= ".display($FRM[$acc]['f1'])." &nbsp; [".display($FRM[$acc]['f1_value'])."] &nbsp; <em>".display($FRM[$acc]['f1_errmsg'])."</em>".
							"<br>f2= ".display($FRM[$acc]['f2'])." &nbsp; [".display($FRM[$acc]['f2_value'])."] &nbsp; <em>".display($FRM[$acc]['f2_errmsg'])."</em>".
							"<br>f3= ".display($FRM[$acc]['f3'])." &nbsp; [".display($FRM[$acc]['f3_value'])."] &nbsp; <em>".display($FRM[$acc]['f3_errmsg'])."</em>".
							"<br>f4= ".display($FRM[$acc]['f4'])." &nbsp; [".display($FRM[$acc]['f4_value'])."] &nbsp; <em>".display($FRM[$acc]['f4_errmsg'])."</em>".
							"<br>f5= ".display($FRM[$acc]['f5'])." &nbsp; [".display($FRM[$acc]['f5_value'])."] &nbsp; <em>".display($FRM[$acc]['f5_errmsg'])."</em>".
							"<br>f6= ".display($FRM[$acc]['f6'])." &nbsp; [".display($FRM[$acc]['f6_value'])."] &nbsp; <em>".display($FRM[$acc]['f6_errmsg'])."</em>".
							"<br>f7= ".display($FRM[$acc]['f7'])." &nbsp; [".display($FRM[$acc]['f7_value'])."] &nbsp; <em>".display($FRM[$acc]['f7_errmsg'])."</em>".
							"<br>f8= ".display($FRM[$acc]['f8'])." &nbsp; [".display($FRM[$acc]['f8_value'])."] &nbsp; <em>".display($FRM[$acc]['f8_errmsg'])."</em>".
							"<br>f9= ".display($FRM[$acc]['f9'])." &nbsp; [".display($FRM[$acc]['f9_value'])."] &nbsp; <em>".display($FRM[$acc]['f9_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "</font>";
	$_MAIN_OUTPUT.= "</td><td valign=\"top\" align=\"left\">";
	$_MAIN_OUTPUT.= "<font size=-1>";
	$_MAIN_OUTPUT.= "<br>".___("SMTP").": ";
	if (check_dbid($FRM[$acc]['host_id'])) {
		$HOST=$HOSTS->getHost($FRM[$acc]['host_id']);		
		$_MAIN_OUTPUT.= display($HOST[0]['name'])." (ID:".$FRM[$acc]['host_id'].")";
	} else {
		$_MAIN_OUTPUT.= ___("-- STANDARD --");
	}
	$_MAIN_OUTPUT.= "<br>".sprintf(___("Erstellt am: %s von %s"),$created_date,$author).
							"<br>".sprintf(___("Bearbeitet am: %s von %s"),$updated_date,$editor);
	$_MAIN_OUTPUT.= "<br><br>".___("Anmeldungen über dieses Formular werden in die folgenden Gruppen eingeordnet:")."<br>";
	$GRP=$ADDRESS->getGroup(0,0,$FRM[$acc]['id'],0,Array("public_frm_ref"=>0));
	$acg=count($GRP);
	for ($accg=0;$accg<$acg;$accg++) {
		$_MAIN_OUTPUT.= "".display($GRP[$accg]['name'])."<br>";
	}
	$_MAIN_OUTPUT.= "<br><br>".___("Der Abonnent kann aus folgenden Gruppen wählen:")."<br>";
	$GRP=$ADDRESS->getGroup(0,0,$FRM[$acc]['id'],0,Array("public_frm_ref"=>1));
	$acg=count($GRP);
	for ($accg=0;$accg<$acg;$accg++) {
		$_MAIN_OUTPUT.= "".display($GRP[$accg]['name'])."<br>";
	}

	$GRP=$ADDRESS->getGroup(0,0,$FRM[$acc]['id']);
	$acg=count($GRP);

	$_MAIN_OUTPUT.= "</font>";

	$_MAIN_OUTPUT.= "</td></tr></tbody></table>";
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= display($FRM[$acc]['descr']);
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";

	for ($accg=0;$accg<$acg;$accg++) {
		//pretag
		if ($GRP[$accg]['aktiv']!=1) {
			$_MAIN_OUTPUT.= "<span style=\"color:#ff0000;\">";
		}
		if ($GRP[$accg]['prod']==1 && $GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.= "<span style=\"color:#009900;\">";
		}

		if ($GRP[$accg]['public_frm_ref']==1) {
			$_MAIN_OUTPUT.= "<em>";
		}
		if ($GRP[$accg]['public']==0 && $GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.= "<strong>";
		}

		//name of group
		$_MAIN_OUTPUT.= display($GRP[$accg]['name']);

		//posttag
		if ($GRP[$accg]['public']==0 && $GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.= "</strong>";
		}
		if ($GRP[$accg]['public_frm_ref']==1) {
			$_MAIN_OUTPUT.= "</em>";// (p)
		}
		if ($GRP[$accg]['prod']==1 && $GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.= "</span>";//(pr)
		}
		if ($GRP[$accg]['aktiv']!=1) {
			$_MAIN_OUTPUT.= "</span>";// (na)
		}

		$_MAIN_OUTPUT.= ", ";

	}

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";

	if ($FRM[$acc]['standard']!=1) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	}
	if ($FRM[$acc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Aktiv"));
	} else {
		$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"));
	}
	//link schliessen
	if ($FRM[$acc]['standard']!=1) {
		$_MAIN_OUTPUT.= "</a>";
	}

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td width=140>";
	//link zur dynamischen onlineversion!
	$_MAIN_OUTPUT.= "&nbsp;&nbsp;<a href=\"".$tm_URL_FE."/subscribe.php?fid=".$FRM[$acc]['id']."\" target=\"_preview\" title=\"".sprintf(___("Dynamische Onlineversion anzeigen: %s"),"subscribe.php?fid=".$FRM[$acc]['id'])."\">".tm_icon("eye.png",___("Online"))."</a>";
	//link zum template
	if (file_exists($tm_formpath."/Form_".$FRM[$acc]['id'].".html")) {
		$_MAIN_OUTPUT.= "&nbsp;&nbsp;<a href=\"".$tm_URL_FE."/".$tm_formdir."/Form_".$FRM[$acc]['id'].".html\" target=\"_preview\" title=\"".sprintf(___("Template anzeigen: %s"),$tm_formdir."/Form_".$FRM[$acc]['id'].".html")."\">".tm_icon("page_white_code.png",___("Template"))."</a>";
	//template bearbeiten
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$editTplPara_."\" title=\"".___("Template bearbeiten")."\">".tm_icon("page_edit.png",___("Template bearbeiten"))."</a>";
	}
	if (file_exists($tm_formpath."/Form_".$FRM[$acc]['id']."_success.html")) {
		$_MAIN_OUTPUT.= "&nbsp;&nbsp;<a href=\"".$tm_URL_FE."/".$tm_formdir."/Form_".$FRM[$acc]['id']."_success.html\" target=\"_preview\" title=\"".sprintf(___("Template anzeigen: %s"),$tm_formdir."/Form_".$FRM[$acc]['id']."_success.html")."\">".tm_icon("page_white_code.png",___("Template"))."</a>";
	}
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Formular bearbeiten")."\">".tm_icon("pencil.png",___("Bearbeiten"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$copyURLPara_."\" onclick=\"return confirmLink(this, '".___("Formular kopieren")."')\" title=\"".___("Formular kopieren")."\">".tm_icon("add.png",___("Kopieren"))."</a>";
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$statURLPara_."\" title=\"".___("Statistik anzeigen")."\">".tm_icon("chart_pie.png",___("Statistik"))."</a>";
	
	if ($FRM[$acc]['standard']==1) {
	} else {
		if ($FRM[$acc]['aktiv']==1) {
			$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$stdURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Formular %s als Standard definieren."),display($FRM[$acc]['name']))."')\" title=\"".___("Dieses Formular als Standardformular")."\">".tm_icon("page_white_go.png",___("Dieses Formular als Standardformular"))."</a>";
		}
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".sprintf(___("Formular %s löschen"),display($FRM[$acc]['name']))."')\" title=\"".___("Formular löschen")."\">".tm_icon("cross.png",___("Löschen"))."</a>";
	}
	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$FRM[$acc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require_once(TM_INCLUDEPATH_GUI."/form_list_legende.inc.php");
?>