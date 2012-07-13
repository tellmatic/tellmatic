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

$_MAIN_DESCR=___("Newsletter verwalten");
$_MAIN_MESSAGE.="";

$NEWSLETTER=new tm_NL();
$QUEUE=new tm_Q();

$nl_grp_id=getVar("nl_grp_id");

$nl_id=getVar("nl_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

//sucharray parameter, vorerst nur is_template!
$search_nl=Array();
$s_nl_istemplate=getVar("s_nl_istemplate");


if ($set=="aktiv") {
	$NEWSLETTER->setAktiv($nl_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $NEWSLETTER->delNL($nl_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}

if ($user_is_manager  && $set=="delete_history" && $doit==1) {
	if (!tm_DEMO()) $QUEUE->clearH(Array("nl_id"=>$nl_id,"not_running"=>1));
	$_MAIN_MESSAGE.=tm_message_success(___("Historie wurde gelöscht."));
	//und nun wie unten bei delete_queue:
	//aber stattdessen setzen wir einfach set auf =queue_delete
	#NEIN: $set="queue_delete";
	//deswegen MUSS das vor queue_delete stehen!!!
}//del history single

if ($set=="delete_img" && $doit==1) {
	$NL=$NEWSLETTER->getNL($nl_id);
	if (!tm_DEMO() && @unlink($tm_nlimgpath."/nl_".date_convert_to_string($NL[0]['created'])."_1.jpg")) {
		$_MAIN_MESSAGE.=tm_message_success(___("Bild wurde gelöscht."));
	} else {
		$_MAIN_MESSAGE.=tm_message_warning(___("Bild konnte nicht gelöscht werden."));
	}
}

if ($set=="delete_html" && $doit==1) {
	$NL=$NEWSLETTER->getNL($nl_id);
	if (!tm_DEMO() && @unlink($tm_nlpath."/nl_".date_convert_to_string($NL[0]['created']).".html")) {
		$_MAIN_MESSAGE.=tm_message_success(___("HTML Datei wurde gelöscht."));
	} else {
		$_MAIN_MESSAGE.=tm_message_warning(___("HTML Datei konnte nicht gelöscht werden."));
	}
}
if ($set=="queue_delete" && $doit==1) {
	$QDel=$QUEUE->getQID($nl_id);
	$qdc=count($QDel);
	for ($qdcc=0;$qdcc<$qdc;$qdcc++) {
		$QUEUE->delQ($QDel[$qdcc]);
	}
	$NL=$NEWSLETTER->setStatus($nl_id,5);//Archiv
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("%s Queues für diese Newsletter wurden gelöscht."),$qdc));
}
if ($set=="copy" && $doit==1) {
	$NEWSLETTER->copyNL($nl_id,0);//,0 = without files
	$s_nl_istemplate=0;
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde kopiert."));
}
if ($set=="copyall" && $doit==1) {
	$NEWSLETTER->copyNL($nl_id,1);//,1 = with files
	$s_nl_istemplate=0;
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag und dazugehörige Dateien wurde kopiert."));
}

$offset=getVar("offset");
if (empty($offset) || $offset<0) {
	$offset=0;
}
$limit=getVar("limit");
if (empty($limit) || $limit<10) {
	$limit=10;
}

//sortierindex, name des arraykeys!
$sortIndex=getVar("si");
if (empty($sortIndex)) {
	$sortIndex="id";
}
//art der sortierung, asc, desc
$sortType=getVar("st",1,1);//varname, addslashes, default_if_not_set
if ($sortType!=0 && $sortType!=1) {
	$sortType=1;
}

//suchparameter setzen, erstmal nur wegen templates
if (isset($is_template)) {
	$s_nl_istemplate=$is_template;
}
$search_nl['is_template']=$s_nl_istemplate;

if ($nl_grp_id!=0)
{
	$NL=$NEWSLETTER->getNL(0,$offset,$limit,$nl_grp_id,0,$sortIndex,$sortType,$search_nl);//
	$nl_grp=$NEWSLETTER->getGroup($nl_grp_id);
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("Gewählte Gruppe: %s"),$nl_grp[0]['name']));
} else {
	$NL=$NEWSLETTER->getNL(0,$offset,$limit,0,0,$sortIndex,$sortType,$search_nl);//
}

$nc=count($NL);
$entrys=$nc; // fuer pager.inc!!!
$entrys_total=$NEWSLETTER->countNL($nl_grp_id,$search_nl);

$NL=sort_array($NL,$sortIndex,$sortType);
//defaults
$mSTDURL->addParam("act","nl_list");
$mSTDURL->addParam("offset",$offset);
$mSTDURL->addParam("limit",$limit);
$mSTDURL->addParam("st",$sortType);
$mSTDURL->addParam("si",$sortIndex);
$mSTDURL->addParam("s_nl_istemplate",$s_nl_istemplate);

$firstURLPara=tmObjCopy($mSTDURL);
$firstURLPara->addParam("nl_grp_id",$nl_grp_id);
$firstURLPara->addParam("offset",0);
$firstURLPara_=$firstURLPara->getAllParams();

$lastURLPara=tmObjCopy($mSTDURL);
$lastURLPara->addParam("act","nl_list");
$lastURLPara->addParam("offset",($entrys_total-$limit));
$lastURLPara_=$lastURLPara->getAllParams();

$nextURLPara=tmObjCopy($mSTDURL);
$nextURLPara->addParam("nl_grp_id",$nl_grp_id);
$nextURLPara->addParam("offset",($offset+$limit));
$nextURLPara_=$nextURLPara->getAllParams();

$prevURLPara=tmObjCopy($mSTDURL);
$prevURLPara->addParam("nl_grp_id",$nl_grp_id);
$prevURLPara->addParam("offset",($offset-$limit));
$prevURLPara_=$prevURLPara->getAllParams();

$pagesURLPara=tmObjCopy($mSTDURL);
//will be defined and use in pager.inc.php

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("nl_grp_id","$nl_grp_id");
$sortURLPara->delparam("st");
$sortURLPara->delparam("si");
$sortURLPara_=$sortURLPara->getAllParams();

$editURLPara=tmObjCopy($mSTDURL);
$editURLPara->addParam("act","nl_edit");
$editURLPara->addParam("nl_grp_id","$nl_grp_id");

$addqURLPara=tmObjCopy($mSTDURL);
$addqURLPara->addParam("act","queue_new");

$delqURLPara=tmObjCopy($mSTDURL);
$delqURLPara->addParam("set","queue_delete");
$delqURLPara->addParam("nl_grp_id","$nl_grp_id");

$delHistoryURLPara=tmObjCopy($mSTDURL);
$delHistoryURLPara->addParam("set","delete_history");
$delHistoryURLPara->addParam("nl_grp_id","$nl_grp_id");

$showqURLPara=tmObjCopy($mSTDURL);
$showqURLPara->addParam("act","queue_list");
//vars loeschen da q liste sonst bei limit offset sort etc beeintraechtigt wird.
$showqURLPara->delParam("offset");
$showqURLPara->delParam("limit");
$showqURLPara->delParam("st");
$showqURLPara->delParam("si");

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("nl_grp_id",$nl_grp_id);
$aktivURLPara->addParam("set","aktiv");

$sendFastURLPara=tmObjCopy($mSTDURL);
$sendFastURLPara->addParam("act","queue_send");
$sendFastURLPara->addParam("set","nl");
$sendFastURLPara->addParam("startq",1);

$refreshRCPTListURLPara=tmObjCopy($mSTDURL);
$refreshRCPTListURLPara->addParam("act","queue_send");
$refreshRCPTListURLPara->addParam("set","nl");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("nl_grp_id",$nl_grp_id);
$delURLPara->addParam("set","delete");

$delimgURLPara=tmObjCopy($mSTDURL);
$delimgURLPara->addParam("nl_grp_id",$nl_grp_id);
$delimgURLPara->addParam("set","delete_img");

$delhtmlURLPara=tmObjCopy($mSTDURL);
$delhtmlURLPara->addParam("nl_grp_id",$nl_grp_id);
$delhtmlURLPara->addParam("set","delete_html");

$copyURLPara=tmObjCopy($mSTDURL);
$copyURLPara->addParam("nl_grp_id",$nl_grp_id);
$copyURLPara->addParam("set","copy");

$copyallURLPara=tmObjCopy($mSTDURL);
$copyallURLPara->addParam("nl_grp_id",$nl_grp_id);
$copyallURLPara->addParam("set","copyall");

$statURLPara=tmObjCopy($mSTDURL);
$statURLPara->addParam("act","statistic");
$statURLPara->addParam("set","nl");

//show log summary
//search for logs, only section
$search_log['object']="nl";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

require(TM_INCLUDEPATH_GUI."/pager.inc.php");

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";
$_MAIN_OUTPUT.= "<thead>\n".
						"<tr>\n".
						"<td><b>".___("Datum")."</b>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=created&amp;st=0\">".$img_arrowup."</a>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=created&amp;st=1\">".$img_arrowdown."</a>\n".
						"</td>\n".
						"<td><b>".___("Titel")."</b>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=subject&amp;st=0\">".$img_arrowup."</a>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=subject&amp;st=1\">".$img_arrowdown."</a>\n".
						"</td>\n".
						"<td>&nbsp\n".
						"</td>\n".
						"<td><b>".___("Status")."</b>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=status&amp;st=0\">".$img_arrowup."</a>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=status&amp;st=1\">".$img_arrowdown."</a>\n".
						"</td>\n".
						"<td><b>".___("Gruppe")."</b>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=grp_id&amp;st=0\">".$img_arrowup."</a>\n".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=grp_id&amp;st=1\">".$img_arrowdown."</a>\n".
						"</td>\n".
						"<td>...</td>\n".
						"</tr>\n".
						"</thead>\n".
						"<tbody>\n";

for ($ncc=0;$ncc<$nc;$ncc++) {

	if ($ncc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($NL[$ncc]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}

	$created_date=$NL[$ncc]['created'];
	$updated_date=$NL[$ncc]['updated'];

	$author=$NL[$ncc]['author'];
	$editor=$NL[$ncc]['editor'];

	$editURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$editURLPara_=$editURLPara->getAllParams();

	$addqURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$addqURLPara_=$addqURLPara->getAllParams();

	$delqURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$delqURLPara_=$delqURLPara->getAllParams();

	$delHistoryURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$delHistoryURLPara_=$delHistoryURLPara->getAllParams();
	
	$showqURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$showqURLPara_=$showqURLPara->getAllParams();

	$sendFastURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$sendFastURLPara_=$sendFastURLPara->getAllParams();

	$refreshRCPTListURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$refreshRCPTListURLPara_=$refreshRCPTListURLPara->getAllParams();

	$aktivURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$delURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$delimgURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$delimgURLPara_=$delimgURLPara->getAllParams();

	$delhtmlURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$delhtmlURLPara_=$delhtmlURLPara->getAllParams();

	$copyURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$copyURLPara_=$copyURLPara->getAllParams();

	$copyallURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$copyallURLPara_=$copyallURLPara->getAllParams();

	$statURLPara->addParam("nl_id",$NL[$ncc]['id']);
	$statURLPara_=$statURLPara->getAllParams();

	$hc=$QUEUE->countH(0,$NL[$ncc]['id']);
	$hc_new=$QUEUE->countH(0,$NL[$ncc]['id'],0,0,1);
	$hc_ok=$QUEUE->countH(0,$NL[$ncc]['id'],0,0,2);
	$hc_view=$QUEUE->countH(0,$NL[$ncc]['id'],0,0,3);
	$hc_fail=$QUEUE->countH(0,$NL[$ncc]['id'],0,0,4);
	$hc_current=$QUEUE->countH(0,$NL[$ncc]['id'],0,0,5);//status 5: currently working on this adr
	$hc_skip=$QUEUE->countH(0,$NL[$ncc]['id'],0,0,6);//status 6 : canceled, blacklisted at sending time etc, skipped!

	//gibt es ueberhaupt q eintraege fuer das nl? zum anzeigen
	$Q=$QUEUE->getQID($NL[$ncc]['id']);
	$qc=count($Q);
	//gibt es zu versendende eintraege in der q? status=1, neu
	$Qnew=$QUEUE->getQID($NL[$ncc]['id'],0,1);
	$qc_new=count($Qnew);

	$_MAIN_OUTPUT.= "<tr id=\"row_".$ncc."\" bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$ncc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$ncc."','".$bgcolor."');\">\n";

	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_nl_list_".$NL[$ncc]['id']."')\" onmouseout=\"hideToolTip();\">\n";
	if ($NL[$ncc]['is_template']==1) {
		$_MAIN_OUTPUT.=  tm_icon("textfield_rename.png",___("Vorlage"));
	}	
	if ($NL[$ncc]['massmail']==1) {
		$_MAIN_OUTPUT.=  tm_icon("lorry.png",___("Massenmailing"));
	} else {
		if ($NL[$ncc]['track_personalized']==1) {
			$_MAIN_OUTPUT.=  tm_icon("bullet_star.png",___("personalisiertes Tracking")."&nbsp;/&nbsp;".___("personalisiertes Tracking"),"","","","user_suit.png");
		} else {
			$_MAIN_OUTPUT.=  tm_icon("user_suit.png",___("anonymes Tracking")."&nbsp;/&nbsp;".___("anonymes Tracking"));
		}
	}
	if ($NL[$ncc]['content_type']=="text/html") {
		$_MAIN_OUTPUT.=  tm_icon("page_white_office.png",___("TEXT/HTML"));
	}
	if ($NL[$ncc]['content_type']=="html") {
		$_MAIN_OUTPUT.=  tm_icon("page_white_h.png",___("HTML"));
	}
	if ($NL[$ncc]['content_type']=="text") {
		$_MAIN_OUTPUT.=  tm_icon("page_white_text.png",___("TEXT"));
	}

//inline images
	if ($NL[$ncc]['use_inline_images']==0) {
		#$_MAIN_OUTPUT.=  tm_icon("bullet_delete.png",___("Inline Images deaktiviert"),"","","","picture.png");
	}

	if ($NL[$ncc]['use_inline_images']==1) {
		$_MAIN_OUTPUT.=  tm_icon("bullet_add.png",___("Inline Images (lokal)"),"","","","picture.png");
	}

	if ($NL[$ncc]['use_inline_images']==2) {
		$_MAIN_OUTPUT.=  tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png");
	}


	$_MAIN_OUTPUT.= "<br>".$created_date;

	$_MAIN_OUTPUT.= "</td>\n";

	$_MAIN_OUTPUT.= "<td>\n";
	if ($NL[$ncc]['status']==4) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Newsletter bearbeiten")."\" onclick=\"return confirmLink(this, '".___("Der Newsletter wurde bereits versendet, wollen Sie ihn wirklich bearbeiten? Es wird empfohlen eine Kopie zu erstellen und diese zu bearbeiten.")."')\">";
	} else {
		if ($NL[$ncc]['status']!=3) {
			$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Newsletter bearbeiten")."\">";
		}
	}
	$_MAIN_OUTPUT.=display($NL[$ncc]['subject']);
	if ($NL[$ncc]['status']!=3 || $NL[$ncc]['status']==4) {
		$_MAIN_OUTPUT.="</a>\n";
	}

	$_MAIN_OUTPUT.= "<div id=\"tt_nl_list_".$NL[$ncc]['id']."\" class=\"tooltip\">\n";

	if ($NL[$ncc]['is_template']==1) {
		$_MAIN_OUTPUT.= tm_icon("textfield_rename.png",___("Vorlage"))."&nbsp;".___("Dieses NL ist eine Vorlage für neue NL und kann nicht versendet werden.")."<br>";
	}	

	
	$_MAIN_OUTPUT.= "<br>ID: ".$NL[$ncc]['id']." \n";	
	$_MAIN_OUTPUT.= "<br><b>".display($NL[$ncc]['subject'])."</b>\n";
	$_MAIN_OUTPUT.= "<br><em>".display($NL[$ncc]['title'])."</em>\n";
	$_MAIN_OUTPUT.= "<br><em>".display($NL[$ncc]['title_sub'])."</em>\n";
	
	$_MAIN_OUTPUT.=  "<br>".tm_icon("user_comment.png",___("Empfängername"))."&nbsp;".___("Empfängername").":".display($NL[$ncc]['rcpt_name']);
	if ($NL[$ncc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}
	if ($NL[$ncc]['massmail']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("lorry.png",___("Massenmailing"))."&nbsp;".___("Massenmailing");
	} else {
		#$_MAIN_OUTPUT.=  "<br>".tm_icon("user_suit.png",___("personalisierter Newsletter"))."&nbsp;".___("personalisierter Newsletter");
		if ($NL[$ncc]['track_personalized']==1) {
			$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_star.png",___("personalisiertes Tracking"),"","","","user_suit.png")."&nbsp;".___("personalisiertes Tracking")."&nbsp;/&nbsp;".___("personalisiertes Tracking");
		} else {
			$_MAIN_OUTPUT.=  "<br>".tm_icon("user_suit.png",___("anonymes Tracking"))."&nbsp;".___("anonymes Tracking")."&nbsp;/&nbsp;".___("anonymes Tracking");
		}
	}


	if ($NL[$ncc]['content_type']=="text/html") {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("page_white_office.png",___("TEXT/HTML"))."&nbsp;".___("TEXT/HTML");
	}
	if ($NL[$ncc]['content_type']=="html") {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("page_white_h.png",___("HTML"))."&nbsp;".___("HTML");
	}
	if ($NL[$ncc]['content_type']=="text") {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("page_white_text.png",___("TEXT"))."&nbsp;".___("TEXT");
	}

	$_MAIN_OUTPUT.=  "<br>".___("Tracking Bild").": \"".$NL[$ncc]['track_image']."\"";

//inline images
	if ($NL[$ncc]['use_inline_images']==0) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_delete.png",___("Inline Images deaktiviert"),"","","","picture.png")."&nbsp;".___("Inline Images deaktiviert");
	}

	if ($NL[$ncc]['use_inline_images']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_add.png",___("Inline Images (lokal)"),"","","","picture.png")."&nbsp;".___("Inline Images (lokal)");
	}

	if ($NL[$ncc]['use_inline_images']==2) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png")."&nbsp;".___("Inline Images");
	}
	//status
	$_MAIN_OUTPUT.= "<br>".tm_icon($STATUS['nl']['statimg'][$NL[$ncc]['status']],display($STATUS['nl']['status'][$NL[$ncc]['status']]))."&nbsp;".display($STATUS['nl']['status'][$NL[$ncc]['status']]);
	$_MAIN_OUTPUT.= "<br>".sprintf(___("Erstellt am: %s von %s"),$created_date,$author).
							"<br>".sprintf(___("Bearbeitet am: %s von %s"),$updated_date,$editor);
	$_MAIN_OUTPUT.= "<br>".___("Gruppe:")." ";

	$GRP=$NEWSLETTER->getGroup($NL[$ncc]['grp_id'],0);
	if (isset($GRP[0]['name'])) {
		$_MAIN_OUTPUT.= display($GRP[0]['name']);
	}
	$attachements=$NL[$ncc]['attachements'];
	if (count($attachements)>0) {
		$_MAIN_OUTPUT.= "<br>".tm_icon("disk.png",___("Anhänge"))."&nbsp;".___("Anhänge").":\n<br>\n";
		foreach ($attachements as $file) {
			$_MAIN_OUTPUT.=$file['file'];
			$_MAIN_OUTPUT.= "<br>\n";
		}
	}
	if ($hc>0) {
		$_MAIN_OUTPUT.= "<br>".sprintf(___("Link: %s"),$NL[$ncc]['link']).
					"<br>".sprintf(___("Views: %s"),$NL[$ncc]['views']).
					"<br>".sprintf(___("Clicks: %s"),$NL[$ncc]['clicks']).
					"<br><b>".___("Versand an:")."</b><br>".
					sprintf(___("insgesamt %s Adressen"),$hc)."<br>".
					sprintf(___("Bearbeitet: %s"),($hc_ok+$hc_fail+$hc_skip+$hc_view))."<br>".							
					sprintf(___("Wartend: %s"),$hc_new)."<br>".
					sprintf(___("Gesendet: %s"),$hc_ok)."<br>".
					sprintf(___("Übersprungen: %s"),$hc_skip)."<br>".
					sprintf(___("Angezeigt: %s"),$hc_view)."<br>".
					sprintf(___("Fehler: %s"),$hc_fail)."<br>".
					"";
	}
	$_MAIN_OUTPUT.= "</div>\n";
	$_MAIN_OUTPUT.= "</td>\n";

	if ($NL[$ncc]['is_template']!=1) {
		$_MAIN_OUTPUT.= "<td bgcolor=\"".$STATUS['nl']['color'][$NL[$ncc]['status']]."\">&nbsp;\n";
		$_MAIN_OUTPUT.= "</td>\n";
	}
	if ($NL[$ncc]['is_template']==1) {
		$_MAIN_OUTPUT.= "<td bgcolor=\"#aabbcc\">&nbsp;\n";
		$_MAIN_OUTPUT.= "</td>\n";
	}

	$_MAIN_OUTPUT.= "<td>\n";
	if ($NL[$ncc]['is_template']!=1) {
		$_MAIN_OUTPUT.= tm_icon($STATUS['nl']['statimg'][$NL[$ncc]['status']],display($STATUS['nl']['descr'][$NL[$ncc]['status']]))."&nbsp;".display($STATUS['nl']['status'][$NL[$ncc]['status']]);
	}	
	if ($NL[$ncc]['is_template']==1) {
		$_MAIN_OUTPUT.= tm_icon("textfield_rename.png",___("Vorlage"))."&nbsp;".___("Vorlage");
	}
	$_MAIN_OUTPUT.= "</td>\n";
	$_MAIN_OUTPUT.= "<td>\n";
	if (isset($GRP[0]['name'])) {
		$_MAIN_OUTPUT.= display($GRP[0]['name']);
	}
	$_MAIN_OUTPUT.= "</td>\n";

	$_MAIN_OUTPUT.= "<td>\n";
	//link fuer aktionen / bearbeiten menue:

	if ($NL[$ncc]['is_template']==1) {
		$_MAIN_OUTPUT.= "<a class=\"list_edit_entry_head\" href=\"".$tm_URL."/".$copyallURLPara_."\" onclick=\"return confirmLink(this, '".___("Newsletter und Dateien kopieren")."')\" title=\"".___("Newsletter und Dateien kopieren")."\">".tm_icon("add.png",___("Newsletter und Dateien kopieren"))."&nbsp;</a>\n";
	}

	#$_MAIN_OUTPUT.= "<a  class=\"list_edit_entry_head\" href=\"javascript:switchSection('list_edit_".$ncc."')\" title=\"".___("Bearbeiten / Aktionen anzeigen")."\">".tm_icon("wrench_orange.png",___("Aktionen anzeigen"))."...</a>\n";
	$_MAIN_OUTPUT.= "<a  class=\"list_edit_entry_head\" id=\"toggle_nlentry_".$ncc."\" href=\"#\" title=\"".___("Bearbeiten / Aktionen anzeigen")."\">".tm_icon("wrench_orange.png",___("Aktionen anzeigen"))."...</a>\n";
	$_MAIN_OUTPUT.= "</td>\n";
	$_MAIN_OUTPUT.= "</tr>\n";
	$_MAIN_OUTPUT.= "<tr onmouseover=\"setBGColor('row_".$ncc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$ncc."','".$bgcolor."');\">\n";
	$_MAIN_OUTPUT.= "<td colspan=6>\n";
	//div fuer aktionen etc
		$_MAIN_OUTPUT.= "<div id=\"list_edit_".$ncc."\" class=\"list_edit\">\n";//align=\"right\"
		$_MAIN_OUTPUT.= "<font size=-1>\n";
		//q hinzufügen
		if ($NL[$ncc]['aktiv']==1 && $NL[$ncc]['is_template']==0) {
			if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html") 
			&& file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt")
			) {

				$_MAIN_OUTPUT.= "<a  class=\"list_edit_entry\" href=\"".$tm_URL."/".$addqURLPara_."\" title=\"".___("Neue Q")."\">".tm_icon("hourglass_add.png",___("Neue Q"))."&nbsp;";
				#if (!$user_is_expert) $_MAIN_OUTPUT.= ___("Versandauftrag anlegen");
				$_MAIN_OUTPUT.= "</a>\n";
			}
		}
		//queued? queuelist button anzeigen // status >1
		if ($qc>0 && $NL[$ncc]['is_template']==0 && ($NL[$ncc]['status']==2 || $NL[$ncc]['status']==3 || $NL[$ncc]['status']==4  || $NL[$ncc]['status']==6)) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$delqURLPara_."\"  onclick=\"return confirmLink(this, '".___("Alle Q-Einträge für diesen Newsletter löschen? Achtung es werden auch laufende Sendeaufträge gelöscht!")."')\" title=\"".___("Q für diesen Newsletter löschen")."\">".tm_icon("hourglass_delete.png",___("Q für diesen Newsletter löschen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.= ___("Versandaufträge löschen");
			$_MAIN_OUTPUT.= "</a>\n";
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$showqURLPara_."\" title=\"".___("Q für dieses Newsletter anzeigen")."\">".tm_icon("hourglass_go.png",___("Q für dieses Newsletter anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.= ___("Versandaufträge anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//queued? senden button anzeigen // status =2 oder 3 oder 4
		//	if (($NL[$ncc]['status']==2  || $NL[$ncc]['status']==3 || $NL[$ncc]['status']==4) && $NL[$ncc]['aktiv']==1) {
		if ($qc_new>0 && $NL[$ncc]['aktiv']==1 && $NL[$ncc]['is_template']==0) {
			#$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$sendURLPara_."\" title=\"".___("Newsletter versenden")."\">".tm_icon("email_go.png",___("Newsletter versenden"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Versand starten");
			#$_MAIN_OUTPUT.= "</a>\n";
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$sendFastURLPara_."\" title=\"".___("Newsletter versenden")."\">".tm_icon("bullet_star.png",___("Newsletter versenden"),"","","","email_go.png")."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Versand starten");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//link zum Template!
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html")) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL_FE."/".$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html\" target=\"_preview\" title=\"".___("Template des Newsletter anzeigen: ").$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_n.html\">".tm_icon("eye.png",___("Template anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Template anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//link zur textversion
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt")) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL_FE."/".$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt\" target=\"_preview\" title=\"".___("Text-Template des Newsletter anzeigen: ").$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_t.txt\">".tm_icon("page_white.png",___("Textversion anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Template anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//link zur geparsten onlineversion!
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_p.html")) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL_FE."/".$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_p.html\" target=\"_preview\" title=\"".___("Onlineversion des Newsletter anzeigen: ").$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_p.html\">".tm_icon("world_go.png",___("Onlineversion anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Onlineversion anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//link zum bild
		if (file_exists($tm_nlimgpath."/nl_".date_convert_to_string($NL[$ncc]['created'])."_1.jpg")) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL_FE."/".$tm_nlimgdir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_1.jpg\" target=\"_preview\" title=\"".___("Bild für diesen Newsletter anzeigen: ").$tm_nlimgdir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_1.jpg\">".tm_icon("photo.png",___("Bild Newsletter anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Bild anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$delimgURLPara_."\"  onclick=\"return confirmLink(this, '".___("Bild löschen").": ".$tm_nlimgdir."/nl_".date_convert_to_string($NL[$ncc]['created'])."_1.jpg')\" title=\"".___("Bild löschen")."\">".tm_icon("photo_delete.png",___("Bild löschen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Bild löschen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//link zur html datei!
		if (file_exists($tm_nlpath."/nl_".date_convert_to_string($NL[$ncc]['created']).".html")) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL_FE."/".$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created']).".html\" target=\"_preview\" title=\"".___("HTML-Datei anzeigen").": ".$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created']).".html\">".tm_icon("page_white_world.png",___("HTML-Datei anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("HTML-Datei anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$delhtmlURLPara_."\"  onclick=\"return confirmLink(this, 'HTML Datei löschen: ".$tm_nldir."/nl_".date_convert_to_string($NL[$ncc]['created']).".html')\" title=\"".___("HTML Datei löschen")."\">".tm_icon("page_white_delete.png",___("HTML Datei löschen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("HTML-Datei löschen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//link zum link
		if (!empty($NL[$ncc]['link'])) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$NL[$ncc]['link']."\" target=\"_link\" title=\"".$NL[$ncc]['link']."\">".tm_icon("page_white_link.png",___("Link anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Link anzeigen");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		//aktiv
		$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
		if ($NL[$ncc]['aktiv']==1) {
			$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Deaktivieren"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Deaktivieren");
		} else {
			$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Aktivieren"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Aktivieren");
		}
		$_MAIN_OUTPUT.= "</a>\n";
		//...
		$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".___("Newsletter löschen")."')\" title=\"".___("Newsletter löschen")."\">".tm_icon("cross.png",___("Newsletter löschen"))."&nbsp;";
		#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Löschen");
		$_MAIN_OUTPUT.= "</a>\n";
		if ($NL[$ncc]['status']==4) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$editURLPara_."\" onclick=\"return confirmLink(this, '".___("Der Newsletter wurde bereits versendet, wollen Sie ihn wirklich bearbeiten? Es wird empfohlen eine Kopie zu erstellen und diese zu bearbeiten.")."')\" title=\"".___("Newsletter bearbeiten")."\">".tm_icon("pencil.png",___("Newsletter bearbeiten"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Bearbeiten");
			$_MAIN_OUTPUT.= "</a>\n";
		} else {
			if ($NL[$ncc]['status']!=3) {
				$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Newsletter bearbeiten")."\">".tm_icon("pencil.png",___("Newsletter bearbeiten"))."&nbsp;";
				#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Bearbeiten");
				$_MAIN_OUTPUT.= "</a>\n";
			}
		}
		
		$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$copyURLPara_."\" onclick=\"return confirmLink(this, '".___("Newsletter kopieren")."')\" title=\"".___("Newsletter kopieren")."\">".tm_icon("bullet_add.png",___("Newsletter kopieren"))."&nbsp;";
		#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Kopieren");
		$_MAIN_OUTPUT.= "</a>\n";
		$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$copyallURLPara_."\" onclick=\"return confirmLink(this, '".___("Newsletter und Dateien kopieren")."')\" title=\"".___("Newsletter und Dateien kopieren")."\">".tm_icon("add.png",___("Newsletter und Dateien kopieren"))."&nbsp;";
		#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Kopieren(+Dateien)");
		$_MAIN_OUTPUT.= "</a>\n";
		#if ($NL[$ncc]['status']>2) {
		if ($hc>0 && $NL[$ncc]['is_template']==0) {
			$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$statURLPara_."\" title=\"".___("Statistik anzeigen")."\">".tm_icon("chart_pie.png",___("Statistik anzeigen"))."&nbsp;";
			#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Statistik");
			$_MAIN_OUTPUT.= "</a>\n";
		}
		if ($hc>0 && $NL[$ncc]['status']!=2  && $NL[$ncc]['status']!=3 && $NL[$ncc]['is_template']==0) {
			if ($user_is_manager) {
				$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$delHistoryURLPara_."\" onclick=\"return confirmLink(this, '".___("Historie löschen")."')\" title=\"".___("Historie löschen")."\">".tm_icon("chart_bar_delete.png",___("Historie löschen"))."&nbsp;";
				#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Historie löschen");
				$_MAIN_OUTPUT.= "</a>\n";
			}
		}

	#ok, aber nur fuer queues die noch nicht versendet wurden etc pp, und die noch nich gestartet wurden etc.
	if ($NL[$ncc]['is_template']==0 && ($QUEUE->countQ($NL[$ncc]['id'],0,$status=2) + $QUEUE->countQ($NL[$ncc]['id'],0,$status=5)) > 0) {
		$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL."/".$refreshRCPTListURLPara_."\" title=\"".___("Adressen nachfassen / Empfängerliste aktualisieren")."\">".tm_icon("arrow_switch.png",___("Adressen nachfassen / Empfängerliste aktualisieren"),"","","","email_go.png")."&nbsp;";
		#if (!$user_is_expert) $_MAIN_OUTPUT.=___("Nachfassen");
		$_MAIN_OUTPUT.= "</a>\n";
	}

	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$NL[$ncc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

		//attachements schoen anzeigen :)
		$attachements=$NL[$ncc]['attachements'];
		$attm_c=count($attachements);
		if ($attm_c>0) {
			$attmsize_total=0;
			$_MAIN_OUTPUT.= "<table border=0 width= \"400\">\n";
			$_MAIN_OUTPUT.= "<thead>";
			$_MAIN_OUTPUT.= "<tr><td colspan=2 align=\"left\">".tm_icon("disk.png",___("Anhänge"))."&nbsp;".$attm_c."&nbsp;".___("Anhänge")."</td></tr>\n";
			$_MAIN_OUTPUT.= "<tr><td align=\"left\">".___("Dateiname:")."</td><td align=\"right\">".___("Grösse:")."</td></tr></thead>\n";
			foreach ($attachements as $file) {
				$_MAIN_OUTPUT.= "<tr>";
				//pruefen ob datei vorhanden ist...
				if (file_exists($tm_nlattachpath."/".$file['file'])) {
					$attmsize=filesize($tm_nlattachpath."/".$file['file']);
					$attmsize_total +=$attmsize;
					$_MAIN_OUTPUT.= "<td align=\"left\">";
					$_MAIN_OUTPUT.= "<a class=\"list_edit_entry\" href=\"".$tm_URL_FE."/".$tm_nlattachdir."/".$file['file']."\" target=\"_preview\" title=\"".___("Anhang für diesen Newsletter laden").": ".$tm_nlattachdir."/".$file['file']."\">".tm_icon("attach.png",___("Anhang laden"))."&nbsp;";
					$_MAIN_OUTPUT.=$file['file'];
					$_MAIN_OUTPUT.= "</a>"; 
					$_MAIN_OUTPUT.= "</td>";
					$_MAIN_OUTPUT.= "<td align=\"right\">";
					$_MAIN_OUTPUT.= formatFileSize($attmsize);
					$_MAIN_OUTPUT.= "</td>\n";
				} else {
					$_MAIN_OUTPUT.= "<td align=\"left\">";
					$_MAIN_OUTPUT.= tm_icon("bullet_delete.png",___("Datei existiert nicht!"))."&nbsp;<font color=\"#ff0000\">".$file['file']."</font>\n";
					$_MAIN_OUTPUT.= "</td>";
					$_MAIN_OUTPUT.= "<td align=\"right\">&nbsp;--&nbsp;</td>";
				}//file_exists
				$_MAIN_OUTPUT.= "</tr>";
			}//foreach
			//gesamtgroesse anzeigen
			$_MAIN_OUTPUT.= "<tr><td align=\"right\">".___("Total")."</td><td align=\"right\" style=\"border-top:2px solid #aaaaaa;\"><strong>".formatFileSize($attmsize_total)."</strong></td></tr>\n";
			$_MAIN_OUTPUT.="</table>";
		}//count
		//ende attachements
		$_MAIN_OUTPUT.= "\n</font><br>\n";
		$_MAIN_OUTPUT.= "</div>\n";
		$_MAIN_OUTPUT.= "</td>\n";
		$_MAIN_OUTPUT.= "</tr>\n";
}//for

$_MAIN_OUTPUT.= "<script language=\"javascript\" type=\"text/javascript\">";
for ($ncc=0;$ncc<$nc;$ncc++) {
		$_MAIN_OUTPUT.= "toggleSlide('toggle_nlentry_".$ncc."','list_edit_".$ncc."',1);";
}

$_MAIN_OUTPUT.= "</script>	";
$_MAIN_OUTPUT.= "<tr><td colspan=5>&nbsp;";
$_MAIN_OUTPUT.= "</td></tr>\n";
$_MAIN_OUTPUT.= "</tbody></table>\n";
require(TM_INCLUDEPATH_GUI."/pager.inc.php");
require_once(TM_INCLUDEPATH_GUI."/nl_list_legende.inc.php");
?>