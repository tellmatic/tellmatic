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
$_MAIN_OUTPUT.="\n\n<!-- adr_list.inc -->\n\n";

$_MAIN_DESCR=___("Adressen verwalten");
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

$ADDRESS=new tm_ADR();
$QUEUE=new tm_Q();
$BLACKLIST=new tm_BLACKLIST();

$adr_grp_id=getVar("adr_grp_id");
$adr_id=getVar("adr_id");
$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()
if (!isset($search)) {
	$search=Array();
}

require_once (TM_INCLUDEPATH_GUI."/adr_search.inc.php");

if ($no_list!=1) {

if ($set=="aktiv") {
	$ADDRESS->setAktiv($adr_id,$val);
	if ($val==1) {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde aktiviert."));
	} else  {
		$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde de-aktiviert."));
	}
}//aktiv single
if ($set=="delete" && $doit==1) {
	if (!tm_DEMO()) $ADDRESS->delAdr($adr_id);
	$_MAIN_MESSAGE.=tm_message_success(___("Eintrag wurde gelöscht."));
}//del single
if ($set=="check") {
	$ADR_check=$ADDRESS->getAdr($adr_id);
	$_MAIN_MESSAGE.=tm_message_notice(___("Eintrag wird geprüft:")." '".$ADR_check[0]['email']);
	$check_mail=checkEmailAdr($ADR_check[0]['email'],$EMailcheck_Intern);
	if (!$check_mail[0]) {
		$_MAIN_MESSAGE.=tm_message_warning(___("Die E-Mail-Adresse ist nicht gültig."));
		$_MAIN_MESSAGE.=tm_message_success(___("E-Mail wurde als fehlerhaft markiert."));
		$_MAIN_MESSAGE.="&nbsp;".tm_icon($STATUS['adr']['statimg'][9],display($STATUS['adr']['status'][9]))."&nbsp;\"<b>".display($STATUS['adr']['status'][9])."</b>\"";
		$ADDRESS->setStatus($adr_id,9);
		$ADDRESS->addMemo($adr_id,$check_mail[1]);
	} else {
		$_MAIN_MESSAGE.=tm_message_success(___("OK"));
	}
	$ADDRESS->markRecheck($adr_id,0);
	$_MAIN_MESSAGE.="<br><pre><em>".display($check_mail[1])."</em></pre>";
	
}//check single
if ($user_is_manager  && $set=="delete_history" && $doit==1) {
	if (!tm_DEMO()) $QUEUE->clearH(Array("adr_id"=>$adr_id));
	$_MAIN_MESSAGE.=tm_message_success(___("Historie wurde gelöscht."));
}//del history single

if ($user_is_manager && $set=="blacklist") {
				$ADR_BL=$ADDRESS->getAdr($adr_id);
				//dublettencheck
				//	function isBlacklisted($str,$type="all")
				if (!$BLACKLIST->isBlacklisted($ADR_BL[0]['email'],"email",0)) {//only_active=0, also alle, nicht nur aktive, was default waere
					$BLACKLIST->addBL(Array(
							"siteid"=>TM_SITEID,
							"expr"=>$ADR_BL[0]['email'],
							"aktiv"=>1,
							"type"=>"email"
							));
					$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die E-Mail-Adresse %s wurde in die Blacklist eingetragen."),$ADR_BL[0]['email']));
				} else {
					$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("Die E-Mail-Adresse %s ist bereits in der Blacklist vorhanden."),$ADR_BL[0]['email']));
				}
}//blacklist single
if ($user_is_manager && $set=="blacklist_domain") {
				$ADR_BL=$ADDRESS->getAdr($adr_id);
				$bl_domainname=getDomainFromEMail($ADR_BL[0]['email']);
				//dublettencheck
				//	function isBlacklisted($str,$type="all")
				if (!$BLACKLIST->isBlacklisted($ADR_BL[0]['email'],"domain",0)) {//only_active=0, also alle, nicht nur aktive, was default waere
					//wenn noch nicht vorhanden einfuegen:
					$BLACKLIST->addBL(Array(
							"siteid"=>TM_SITEID,
							"expr"=>$bl_domainname,
							"aktiv"=>1,
							"type"=>"domain"
							));
					$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die Domain %s wurde in die Blacklist eingetragen."),$bl_domainname));
				} else {
					$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("Die Domain %s ist bereits in der Blacklist vorhanden."),$bl_domainname));
				}
}//blacklist domain single
//delete email from blacklist
if ($user_is_manager && $set=="blacklist_del") {
				$ADR_BL=$ADDRESS->getAdr($adr_id);
				$BL=$BLACKLIST->getBL(0,Array("expr"=>$ADR_BL[0]['email'], "type"=>"email"));
				$BLACKLIST->delBL($BL[0]['id']);
				$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die E-Mail-Adresse %s wurde aus der Blacklist gelöscht."),$ADR_BL[0]['email']));
}//blacklist adr delete
//delete domain from adr from blacklist!
if ($user_is_manager && $set=="blacklist_domain_del") {
				$ADR_BL=$ADDRESS->getAdr($adr_id);
				$bl_domainname=getDomainFromEMail($ADR_BL[0]['email']);
				$BL=$BLACKLIST->getBL(0,Array("expr"=>$bl_domainname, "type"=>"domain"));
				$BLACKLIST->delBL($BL[0]['id']);
				$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die Domain %s wurde aus der Blacklist gelöscht."),$bl_domainname));
}//blacklist domain delete



require_once(TM_INCLUDEPATH_GUI."/adr_list_form.inc.php");

$ac_multi=count($adr_id_arr);

if ($ac_multi>0) { // wenn min 1 adr gewaehlt
	// && $doit==1
	//meldungen ausgeben
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("%s Adressen ausgewählt."),$ac_multi));
	if ($set=="check_syntax_multi" || $set=="check_mx_multi" || $set=="check_validate_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden geprüft"));
		$_MAIN_MESSAGE.=tm_message_notice(___("Status für fehlerhafte Adressen wird geändert."));
		$_MAIN_MESSAGE.="&nbsp;".tm_icon($STATUS['adr']['statimg'][9],display($STATUS['adr']['status'][9]))."&nbsp;\"<b>".display($STATUS['adr']['status'][9])."</b>\"";
		$_MAIN_MESSAGE.=tm_message_notice(___("E-Mail-Prüfung:"));
		if ($set=="check_syntax_multi") $_MAIN_MESSAGE.="<br>".___("Syntax");
		if ($set=="check_mx_multi") $_MAIN_MESSAGE.="<br>".___("Syntax, MX/DNS");
		if ($set=="check_validate_multi") $_MAIN_MESSAGE.="<br>".___("Syntax, MX/DNS + Validate");

		if ($set=="check_syntax_multi") $EMailcheck=1;
		if ($set=="check_mx_multi") $EMailcheck=2;
		if ($set=="check_validate_multi") $EMailcheck=3;
	}	
	if ($set=="aktiv_1_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden aktiviert"));
	}
	if ($set=="aktiv_0_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden deaktiviert."));
	}
	if ($set=="set_status_multi") {
		$_MAIN_MESSAGE.="<br>".sprintf(___("Setze neuen Status für ausgewählte Adressen auf %s"),tm_icon($STATUS['adr']['statimg'][$status_multi],display($STATUS['adr']['status'][$status_multi]))."&nbsp;\"<b>".display($STATUS['adr']['status'][$status_multi]))."</b>\"";
	}
	if ($set=="delete_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden gelöscht."));
	}
	if ($user_is_manager  && $set=="delete_history_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Historie ausgewählter Adressen werden gelöscht."));
	}
	if ($set=="copy_grp_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden in die gewählten Gruppen kopiert."));
	}
	if ($set=="move_grp_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden in die gewählten Gruppen verschoben."));
	}
	if ($set=="delete_grp_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden aus den gewählten Gruppen gelöscht."));
	}
	if ($user_is_manager && $set=="blacklist_multi") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden zur Blacklist hinzgefügt."));
	}
	if ($user_is_manager && $set=="blacklist_domain_multi") {
		$bl_domains=Array();//array mit domainnamen
		$_MAIN_MESSAGE.=tm_message_notice(___("Domains der ausgewählten Adressen werden zur Blacklist hinzgefügt."));
	}
	//array durchwandern
	for ($acc_m=0;$acc_m<$ac_multi;$acc_m++) {
		#$_MAIN_OUTPUT.="<br>ID".$adr_id_arr[$acc_m];

//check adr syntax, mx, validate
		if ($set=="check_syntax_multi" || $set=="check_mx_multi" || $set=="check_validate_multi") {
			$ADR_check=$ADDRESS->getAdr($adr_id_arr[$acc_m]);
			$check_mail=checkEmailAdr($ADR_check[0]['email'],$EMailcheck);
			if (!$check_mail[0]) {
				$_MAIN_MESSAGE.="<br><em>".display($ADR_check[0]['email'])."</em> <font color=\"red\">".___("FEHLER")."</font>";
				//$_MAIN_MESSAGE.="<br>".___("Die E-Mail-Adresse ist nicht gültig.");
				//$_MAIN_MESSAGE.="<br><pre><em>".display($check_mail[1])."</em></pre>";
				$ADDRESS->setStatus($adr_id_arr[$acc_m],9);
				$ADDRESS->addMemo($adr_id_arr[$acc_m],$check_mail[1]);
			} else {
				$_MAIN_MESSAGE.="<br><em>".display($ADR_check[0]['email'])."</em> <font color=\"green\">".___("OK")."</font>";
			}
			$ADDRESS->markRecheck($adr_id_arr[$acc_m],0);
		}//check multi
		//activate adr
		if ($set=="aktiv_1_multi") {
			$ADDRESS->setAktiv($adr_id_arr[$acc_m],1);
		}//aktiv 1 multi
		//deactivate adr
		if ($set=="aktiv_0_multi") {
			$ADDRESS->setAktiv($adr_id_arr[$acc_m],0);
		}//aktiv 0 multi
		//set status
		if ($set=="set_status_multi") {
			$ADDRESS->setStatus($adr_id_arr[$acc_m],$status_multi);
		}//status multi
		//del adr
		if ($set=="delete_multi") {
			if (!tm_DEMO()) $ADDRESS->delAdr($adr_id_arr[$acc_m]);
		}//del multi
		//del hostiry multi
		if ($user_is_manager  &&  $set=="delete_history_multi") {
			if (!tm_DEMO()) $QUEUE->clearH(Array("adr_id"=>$adr_id_arr[$acc_m]));
		}//del history single

		//copy adr to selected grps
		if ($set=="copy_grp_multi") {
			//get old groups
			$adr_groups=$ADDRESS->getGroupID(0,$adr_id_arr[$acc_m],0);
			//set new groups
			$ADDRESS->setGroup($adr_id_arr[$acc_m],$adr_grp_id_multi,$adr_groups,1);//set groups, merge=1=merge groups
		}//copy grp multi
		//move adr to selected grps
		if ($set=="move_grp_multi") {
			//set new groups
			$ADDRESS->setGroup($adr_id_arr[$acc_m],$adr_grp_id_multi,0);//merge=0=move
		}//move grp multi
		//delete adr ref from selected grps
		if ($set=="delete_grp_multi") {
			//get old groups
			$adr_groups=$ADDRESS->getGroupID(0,$adr_id_arr[$acc_m],0);
			//set new groups
			$ADDRESS->setGroup($adr_id_arr[$acc_m],$adr_grp_id_multi,$adr_groups,2);//set groups, merge=2=diff
		}//del grp multi
		if ($user_is_manager && $set=="blacklist_multi") {
				$ADR_BL=$ADDRESS->getAdr($adr_id_arr[$acc_m]);
				//dublettencheck
				//	function isBlacklisted($str,$type="all")
				if (!$BLACKLIST->isBlacklisted($ADR_BL[0]['email'],"email",0)) {//only_active=0, also alle, nicht nur aktive, was default waere
					//wenn noch nicht vorhanden einfuegen:
					$BLACKLIST->addBL(Array(
							"siteid"=>TM_SITEID,
							"expr"=>$ADR_BL[0]['email'],
							"aktiv"=>1,
							"type"=>"email"
							));
					$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die E-Mail-Adresse %s wurde in die Blacklist eingetragen."),$ADR_BL[0]['email']));
				} else {
					$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die E-Mail-Adresse %s ist bereits in der Blacklist vorhanden."),$ADR_BL[0]['email']));
				}//if blacklisted
		}//blacklist multi
		if ($user_is_manager && $set=="blacklist_domain_multi") {
				//hier wird ein aray erzeugt mit allen domainnamen, auch doppelte!
				$ADR_BL=$ADDRESS->getAdr($adr_id_arr[$acc_m]);
				$bl_domains[$acc_m]=getDomainFromEMail($ADR_BL[0]['email']);
		}//blacklist domain multi
	}//for acc_m

	if ($user_is_manager && $set=="blacklist_domain_multi") {	
		//hier nun blacklist domains array unifien und dann erst eintragen!
		$bl_domains=array_unique($bl_domains);//unify!
		foreach ($bl_domains as $bl_domainname) {
			//dublettencheck! per getBL diesmal da wir nur domainnamen haben, is blacklisted sich aber auf email bezieht!
			$BL=$BLACKLIST->getBL(0,Array("type"=>"domain","expr"=>$bl_domainname));
			//wenn nix gefunden, eintragen:
			if (count($BL)<1) {
				$BLACKLIST->addBL(Array(
								"siteid"=>TM_SITEID,
								"expr"=>$bl_domainname,
								"aktiv"=>1,
								"type"=>"domain"
								));
				$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die Domain %s wurde in die Blacklist eingetragen."),$bl_domainname));
			} else {
				$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Die Domain %s ist bereits in der Blacklist vorhanden."),$bl_domainname));
			}//if count<1
		}//foreach bl_domains as bl_domain
	}//blacklist domain multi
} else {
		#$_MAIN_MESSAGE.="<br>".___("Es wurden keine Adressen ausgewählt.");
}//if ac_multi>0

//übersicht
$_MAIN_OUTPUT.="<div class=\"adr_summary\">";#ccdddd
$_MAIN_OUTPUT.=tm_icon("information.png",___("Übersicht"),___("Übersicht"))."&nbsp;<b>".___("Übersicht").":</b>";

//anzahl alle
$entrys_all=$ADDRESS->countADR();//anzahl eintraege, alles
$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Adressen Gesamt"),$entrys_all));

//anzahl recheck alle
$search_recheck=Array();
$search_recheck['recheck']=1;
$entrys_recheck=$ADDRESS->countADR(0,$search_recheck);//anzahl eintraege die zur pruefung markiert sind
if ($entrys_recheck>0) $_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Adressen sind zur Prüfung vorgemerkt"),$entrys_recheck));

//anzahl inaktiv/deaktivert
$search_inaktiv=Array();
$search_inaktiv['aktiv']='0';
$entrys_inaktiv=$ADDRESS->countADR(0,$search_inaktiv);
if ($entrys_inaktiv>0) $_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Adressen sind deaktiviert"),$entrys_inaktiv));

$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Dubletten"),$ADDRESS->count_duplicates()));

if ($adr_grp_id>0)
{
	$ADR=$ADDRESS->getAdr(0,$offset,$limit,$adr_grp_id,$search,$sortIndex,$sortType);//id,offset,limit,group,$search_array
	$adr_grp=$ADDRESS->getGroup($adr_grp_id);
	$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("gewählte Gruppe: %s"),"'".$adr_grp[0]['name']."'"));
} else {
	$ADR=$ADDRESS->getAdr(0,$offset,$limit,0,$search,$sortIndex,$sortType);//id,offset,limit,group,$search_array
}

if ($s_status >0) {
	$_MAIN_OUTPUT.=tm_message_notice(___("gewählter Status"));
	$_MAIN_OUTPUT.=":&nbsp;".tm_icon($STATUS['adr']['statimg'][$s_status],display($STATUS['adr']['status'][$s_status]))."&nbsp;\"<b>".display($STATUS['adr']['status'][$s_status])."</b>\"";
}

$ac=count($ADR);
$entrys=$ac; // fuer pager.inc!!! // aktuelle anzahl angezeigter eintraege

$entrys_total=$ADDRESS->countADR($adr_grp_id,$search);//anzahl eintraege aktuell gewaehlt mit filter
$search_recheck_selected=$search;
$search_recheck_selected['recheck']=1;
$entrys_recheck_selected=$ADDRESS->countADR($adr_grp_id,$search_recheck_selected);//anzahl eintraege die zur pruefung markiert sind mit aktuellem suchfilter

//anzahl (zu pruefender) adressen
if ($entrys_total!=$entrys_all) {
	$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Adressen (gefiltert)"),$entrys_total));
	if ($entrys_recheck_selected>0)$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Adressen sind zur Prüfung vorgemerkt, gefiltert)"),$entrys_recheck_selected));
	$_MAIN_OUTPUT.="";
}

//anzahl deaktiverte adressen in der aktuellen auswahl gesamt
if (!isset($search['aktiv']) || $search['aktiv'] ==="") {
	$search_inaktiv_selected=$search;
	$search_inaktiv_selected['aktiv']='0';//use a string!
	$entrys_inaktiv_selected=$ADDRESS->countADR($adr_grp_id,$search_inaktiv_selected);//anzahl inaktive adr
	if ($entrys_inaktiv_selected>0)$_MAIN_OUTPUT.=tm_message_notice(sprintf(___("%s Adressen sind deaktivert (gefiltert)"),$entrys_inaktiv_selected));
}
$_MAIN_OUTPUT.="<br></div>";


//wir sortieren ueber die db, sortIndex und Type werden uebergeben an get ADR
//damit die sortierung auch mit limits klappt.... sonst werden nur die gezeigten eintraege im array sortiert, nichjt aber die geamte adressliste in der db... etc
//sortierung ueber array:
//$ADR=sort_array($ADR,$sortIndex,$sortType);


//globale parameter die angefuegt werden sollen!
$mSTDURL->addParam("offset",$offset);
$mSTDURL->addParam("limit",$limit);
$mSTDURL->addParam("no_list",0);
$mSTDURL->addParam("reset_adr_search_values",0);

#$mSTDURL->addParam("st",$sortType);
#$mSTDURL->addParam("si",$sortIndex);
if (isset($s_email)) {
	$mSTDURL->addParam("s_email",$s_email);
}
if (isset($s_author)) {
	$mSTDURL->addParam("s_author",$s_author);
}
if (isset($f0_9)) {
	$mSTDURL->addParam("f0_9",$f0_9);
}
if (isset($s_status)) {
	$mSTDURL->addParam("s_status",$s_status);
}
if (isset($s_aktiv)) {
	$mSTDURL->addParam("s_aktiv",$s_aktiv);
}
if ($set_search=="search") {
	$mSTDURL->addParam("set",$set_search);
}

$mSTDURL->addParam("adr_grp_id",$adr_grp_id);
$mSTDURL->addParam("st",$sortType);
$mSTDURL->addParam("si",$sortIndex);

$firstURLPara=tmObjCopy($mSTDURL);
$firstURLPara->addParam("act","adr_list");
$firstURLPara->addParam("offset",0);
$firstURLPara_=$firstURLPara->getAllParams();

$lastURLPara=tmObjCopy($mSTDURL);
$lastURLPara->addParam("act","adr_list");
$lastURLPara->addParam("offset",($entrys_total-$limit));
$lastURLPara_=$lastURLPara->getAllParams();

$nextURLPara=tmObjCopy($mSTDURL);
//neuer offset!
$nextURLPara->addParam("offset",($offset+$limit));
$nextURLPara->addParam("act","adr_list");
$nextURLPara_=$nextURLPara->getAllParams();

$prevURLPara=tmObjCopy($mSTDURL);
$prevURLPara->addParam("adr_grp_id",$adr_grp_id);
//neuer offset!
$prevURLPara->addParam("offset",($offset-$limit));
$prevURLPara->addParam("act","adr_list");
$prevURLPara_=$prevURLPara->getAllParams();

$pagesURLPara=tmObjCopy($mSTDURL);
//will be defined and use in pager.inc.php

$sortURLPara=tmObjCopy($mSTDURL);
$sortURLPara->addParam("act","adr_list");
$sortURLPara_=$sortURLPara->getAllParams();

$editURLPara=tmObjCopy($mSTDURL);
$editURLPara->addParam("adr_id",$adr_id);
$editURLPara->addParam("act","adr_edit");

$aktivURLPara=tmObjCopy($mSTDURL);
$aktivURLPara->addParam("adr_grp_id",$adr_grp_id);
$aktivURLPara->addParam("offset",$offset);
$aktivURLPara->addParam("limit",$limit);
$aktivURLPara->addParam("act","adr_list");
$aktivURLPara->addParam("set","aktiv");

$checkURLPara=tmObjCopy($mSTDURL);
$checkURLPara->addParam("adr_grp_id",$adr_grp_id);
$checkURLPara->addParam("offset",$offset);
$checkURLPara->addParam("limit",$limit);
$checkURLPara->addParam("act","adr_list");
$checkURLPara->addParam("set","check");

$delURLPara=tmObjCopy($mSTDURL);
$delURLPara->addParam("adr_grp_id",$adr_grp_id);
$delURLPara->addParam("offset",$offset);
$delURLPara->addParam("limit",$limit);
$delURLPara->addParam("act","adr_list");
$delURLPara->addParam("set","delete");

$delHistoryURLPara=tmObjCopy($mSTDURL);
$delHistoryURLPara->addParam("adr_grp_id",$adr_grp_id);
$delHistoryURLPara->addParam("offset",$offset);
$delHistoryURLPara->addParam("limit",$limit);
$delHistoryURLPara->addParam("act","adr_list");
$delHistoryURLPara->addParam("set","delete_history");

$statURLPara=tmObjCopy($mSTDURL);
$statURLPara->addParam("act","statistic");
$statURLPara->addParam("set","adr");

$blacklistURLPara=tmObjCopy($mSTDURL);
$blacklistURLPara->addParam("adr_grp_id",$adr_grp_id);
$blacklistURLPara->addParam("offset",$offset);
$blacklistURLPara->addParam("limit",$limit);
$blacklistURLPara->addParam("set","blacklist");
$blacklistURLPara->addParam("act","adr_list");

$blacklistDomainURLPara=tmObjCopy($blacklistURLPara);
$blacklistDomainURLPara->addParam("set","blacklist_domain");

$blacklistDelURLPara=tmObjCopy($blacklistURLPara);
$blacklistDelURLPara->addParam("set","blacklist_del");

$blacklistDomainDelURLPara=tmObjCopy($blacklistURLPara);
$blacklistDomainDelURLPara->addParam("set","blacklist_domain_del");

//show log summary
//search for logs, only section
$search_log['object']="adr";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

//show a link to create new entry if list is empty, e.g. if searching email doesnt match, etc.
if ($ac==0) {
	$newURLPara=tmObjCopy($mSTDURL);
	$newURLPara->addParam("adr_grp_id",$adr_grp_id);
	$newURLPara->addParam("email",$s_email);
	$newURLPara->addParam("act","adr_new");
	$newURLPara_=$newURLPara->getAllParams();
			$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$newURLPara_."\" title=\"".___("Neue Adresse anlegen")."\">".tm_icon("vcard_add.png",___("Neue Adresse anlegen"))."&nbsp;".___("Neue Adresse anlegen")."</a>";
}
require(TM_INCLUDEPATH_GUI."/pager.inc.php");

$_MAIN_OUTPUT.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
$_MAIN_OUTPUT.= "<thead>".
						"<tr>".
						"<td width=100><b>".___("ID")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=id&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td><b>".___("E-Mail")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=email&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=email&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td>&nbsp;".
						"</td>".
						"<td width=100><b>".___("Status")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=status&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=status&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td>".___("Gruppen")."</td>".
						"<td><b>".___("Aktiv")."</b>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=0\">".$img_arrowup."</a>".
						"<a href=\"".$tm_URL."/".$sortURLPara_."&amp;si=aktiv&amp;st=1\">".$img_arrowdown."</a>".
						"</td>".
						"<td width=145>...</td>".
						"</tr>".
						"</thead>".
						"<tbody>";


for ($acc=0;$acc<$ac;$acc++) {
	if ($acc%2==0) {$bgcolor=$row_bgcolor;} else {$bgcolor=$row_bgcolor2;}
	if ($ADR[$acc]['aktiv']!=1) {
		$bgcolor=$row_bgcolor_inactive;
		$new_aktiv=1;
	} else {
		$new_aktiv=0;
	}
	if ($ADR[$acc]['status']==11 || $ADR[$acc]['errors']>=($C[0]['max_mails_retry'])) {	
		$bgcolor=$row_bgcolor_fail;
		//$bgcolor=$STATUS['adr']['color'][$ADR[$acc]['status']];
	}
	
	$created_date=$ADR[$acc]['created'];
	$updated_date=$ADR[$acc]['updated'];

	$author=$ADR[$acc]['author'];
	$editor=$ADR[$acc]['editor'];

	if (is_numeric($author)) {
		$author="Form_".$author;
	}
	if (is_numeric($editor)) {
		$editor="Form_".$editor;
	}

	$nlc=$QUEUE->countH(0,0,0,$ADR[$acc]['id'],0);

	$editURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$editURLPara->addParam("adr_d_id",$ADR[$acc]['d_id']);
	$editURLPara_=$editURLPara->getAllParams();

	$aktivURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$aktivURLPara->addParam("val",$new_aktiv);
	$aktivURLPara_=$aktivURLPara->getAllParams();

	$checkURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$checkURLPara_=$checkURLPara->getAllParams();

	$delURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$delURLPara_=$delURLPara->getAllParams();

	$delHistoryURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$delHistoryURLPara_=$delHistoryURLPara->getAllParams();

	$statURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$statURLPara_=$statURLPara->getAllParams();

	$blacklistURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$blacklistURLPara_=$blacklistURLPara->getAllParams();

	$blacklistDomainURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$blacklistDomainURLPara_=$blacklistDomainURLPara->getAllParams();

	$blacklistDelURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$blacklistDelURLPara_=$blacklistDelURLPara->getAllParams();

	$blacklistDomainDelURLPara->addParam("adr_id",$ADR[$acc]['id']);
	$blacklistDomainDelURLPara_=$blacklistDomainDelURLPara->getAllParams();

	#$row_bgcolor_hilite;

	$_MAIN_OUTPUT.= "<tr id=\"row_".$acc."\" bgcolor=\"".$bgcolor."\" onmouseover=\"setBGColor('row_".$acc."','".$row_bgcolor_hilite."');\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');\">";

	//checkbox
	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_list_id_".$ADR[$acc]['id']."')\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');hideToolTip();\">";
	$Form->set_InputValue($FormularName,$InputName_AdrID,$ADR[$acc]['id']);
	$Form->render_Input($FormularName,$InputName_AdrID);
	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_AdrID]['html'];
	
	if ($ADR[$acc]['recheck']==1) {	
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("spellcheck.png",___("E-Mail Prüfen"));
	}	
	
	//BLACKLIST Details
	//zeigt an ob adresse AKTIV!!!!! geblacklisted ist:
	if ($BLACKLIST->isBlacklisted($ADR[$acc]['email'])) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("ruby.png",___("Blacklist"));
	} else {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_white.png","Blacklisting nicht aktiv");
	}

	//zeigt an ob adresse geblacklisted ist und welchen typs:
	$blacklisted=$BLACKLIST->checkBL($ADR[$acc]['email'],"",0);
	$bl_email=false;//flag f. edit links
	$bl_domain=false;//flag f. edit links
	$bl_expr=false;//flag f. edit links
	if ($blacklisted[0]) {
		$bl_types=Array();
		foreach($blacklisted[1] as $blacklist_entry) {
			$bl_types[]=$blacklist_entry['type'];
		}//foreach as ...
		//unify!
		$bl_types=array_unique($bl_types);
		//sort
		sort($bl_types);
		foreach ($bl_types as $bl_type) {
			if ($bl_type=="email") {
				$bl_email=true;//flag f. edit links
				$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("ruby_key.png",___("Blacklist E-Mail"));
			}
			# else {
			#	$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_white.png","--");
			#}//email
		
			if ($bl_type=="domain") {
				$bl_domain=true;//flag f. edit links
				$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("ruby_link.png",___("Blacklist Domain"));
			}
			# else {
			#	$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_white.png","--");
			#}//domain
			if ($bl_type=="expr") {
				$bl_expr=true;//flag f. edit links
				$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("ruby_gear.png",___("Blacklist RegEx"));
			}
			# else {
			#	$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_white.png","--");
			#}//expr
		}//foreach
	}//blacklisted 0 == true

	$_MAIN_OUTPUT.= "<div id=\"tt_adr_list_id_".$ADR[$acc]['id']."\" class=\"tooltip\">";
	$_MAIN_OUTPUT.="ID:".$ADR[$acc]['id'];
	//show blacklist details in mouseover div
	if ($blacklisted[0]) {
		$_MAIN_OUTPUT.= "<br><b>".___("Blacklist")."</b>:";
		foreach($blacklisted[1] as $blacklist_entry) {
			$_MAIN_OUTPUT.= "<br>";
			if ($blacklist_entry['type']=="email") {
				$_MAIN_OUTPUT.=  tm_icon("ruby_key.png",___("Blacklist E-Mail"));
				$_MAIN_OUTPUT.= "&nbsp;".___("E-Mail").":";
			}
			if ($blacklist_entry['type']=="domain") {
				$_MAIN_OUTPUT.=  tm_icon("ruby_link.png",___("Blacklist Domain"));
				$_MAIN_OUTPUT.= "&nbsp;".___("Domain").":";
			}
			if ($blacklist_entry['type']=="expr") {
				$_MAIN_OUTPUT.=  tm_icon("ruby_gear.png",___("Blacklist RegEx"));
				$_MAIN_OUTPUT.= "&nbsp;".___("RegEx").":";
			}
			$_MAIN_OUTPUT.= display($blacklist_entry['expr']);
			if ($blacklist_entry['aktiv']==1) {
				$_MAIN_OUTPUT.= "&nbsp;".tm_icon("tick.png",___("Aktiv"));
			} else {
				$_MAIN_OUTPUT.= "&nbsp;".tm_icon("cancel.png",___("Inaktiv"))."(na)";
			}
		}//foreach as ...
	}			
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td onmousemove=\"showToolTip('tt_adr_list_".$ADR[$acc]['id']."')\" onmouseout=\"setBGColor('row_".$acc."','".$bgcolor."');hideToolTip();\">";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\">".$ADR[$acc]['email']."</a>";
	if ($ADR[$acc]['errors']>=($C[0]['max_mails_retry']-1)) {
		$_MAIN_OUTPUT.=  "&nbsp;".tm_icon("bullet_error.png",sprintf(___("Adresse hat mindestens %s Sendefehler!"),($C[0]['max_mails_retry']-1)));
	}
	$_MAIN_OUTPUT.= "<div id=\"tt_adr_list_".$ADR[$acc]['id']."\" class=\"tooltip\">";

	$_MAIN_OUTPUT.= "<b>".$ADR[$acc]['email']."</b>";
	$_MAIN_OUTPUT.= "<br><font size=-1>".$ADR[$acc]['f0'].", ".$ADR[$acc]['f1'].", ".$ADR[$acc]['f2'].", ".$ADR[$acc]['f3'].", ".$ADR[$acc]['f4'].", ".$ADR[$acc]['f5'].", ".$ADR[$acc]['f6'].", ".$ADR[$acc]['f7'].", ".$ADR[$acc]['f8'].", ".$ADR[$acc]['f9']."</font>";
	$_MAIN_OUTPUT.= "<br>ID: ".$ADR[$acc]['id']." / ".$ADR[$acc]['d_id']." ";
	if ($ADR[$acc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(Inaktiv)");
	}

	$_MAIN_OUTPUT.= "<br>".sprintf(___("Status: %s"),tm_icon($STATUS['adr']['statimg'][$ADR[$acc]['status']],display($STATUS['adr']['status'][$ADR[$acc]['status']])));

	$_MAIN_OUTPUT.= "<br>CODE: ".$ADR[$acc]['code']." &nbsp;".
							"<br>".sprintf(___("Erstellt am: %s von %s"),$created_date,$author).
							"<br>".sprintf(___("Bearbeitet am: %s von %s"),$updated_date,$editor).
							"<br>".sprintf(___("Newsletter Aktuell: %s"),$nlc).
							"<br>".sprintf(___("Newsletter Gesamt: %s"),$ADR[$acc]['newsletter']).
							"<br>".sprintf(___("Views: %s"),$ADR[$acc]['views']).
							"<br>".sprintf(___("Clicks: %s"),$ADR[$acc]['clicks']).
							"<br>".sprintf(___("Sendefehler: %s"),$ADR[$acc]['errors']);

	$_MAIN_OUTPUT.= "<br>".___("Mitglied in den Gruppen:");
	$_MAIN_OUTPUT.= "<ul>";
	$GRP=$ADDRESS->getGroup(0,$ADR[$acc]['id']);
	$acg=count($GRP);
	for ($accg=0;$accg<$acg;$accg++) {
		$_MAIN_OUTPUT.= "<li>".$GRP[$accg]['name']."</li>";
	}
	$_MAIN_OUTPUT.= "</ul>";
	$_MAIN_OUTPUT.= "<br>".___("Memo:")."<font size=\"1\">".str_replace("\n","<br>",display($ADR[$acc]['memo']))."</font>";
	$_MAIN_OUTPUT.= "</div>";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td bgcolor=\"".$STATUS['adr']['color'][$ADR[$acc]['status']]."\">&nbsp;";
	$_MAIN_OUTPUT.= "</td>";

	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= tm_icon($STATUS['adr']['statimg'][$ADR[$acc]['status']],display($STATUS['adr']['descr'][$ADR[$acc]['status']]))."&nbsp;".display($STATUS['adr']['status'][$ADR[$acc]['status']]);
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	for ($accg=0;$accg<$acg;$accg++) {
		//pretag
		if ($GRP[$accg]['aktiv']!=1) {
			$_MAIN_OUTPUT.= "<span style=\"color:#ff0000;\">";
		}
		if ($GRP[$accg]['public']==0 && $GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.= "<strong>";
		}

		//name of group
		$_MAIN_OUTPUT.= "".display($GRP[$accg]['name']);

		//posttag
		if ($GRP[$accg]['public']==0 && $GRP[$accg]['aktiv']==1) {
			$_MAIN_OUTPUT.= "</strong>";
		}
		if ($GRP[$accg]['aktiv']!=1) {
			$_MAIN_OUTPUT.= " (na)</span>";
		}
		#		$_MAIN_OUTPUT.= "".$GRP[$accg]['name'].", ";
		$_MAIN_OUTPUT.= ", ";
	}
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$aktivURLPara_."\" title=\"".___("aktivieren/de-aktivieren")."\">";
	if ($ADR[$acc]['aktiv']==1) {
		$_MAIN_OUTPUT.=  tm_icon("tick.png",___("Aktiv"))."&nbsp;";
	} else {
		$_MAIN_OUTPUT.=  tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
	}
	$_MAIN_OUTPUT.= "</a>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$checkURLPara_."\" title=\"".___("E-Mail prüfen")."\">".tm_icon("spellcheck.png",___("E-Mail prüfen"))."</a>";
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$editURLPara_."\" title=\"".___("Adresse bearbeiten")."\">".tm_icon("pencil.png",___("Adresse bearbeiten"))."</a>";
	$_MAIN_OUTPUT.=  "&nbsp;<a href=\"".$tm_URL."/".$statURLPara_."\" title=\"".___("Statistik anzeigen")."\">".tm_icon("chart_pie.png",___("Statistik anzeigen"))."</a>";
	if ($user_is_manager) {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$delHistoryURLPara_."\" onclick=\"return confirmLink(this, '".___("Historie löschen")."')\" title=\"".___("Historie löschen")."\">".tm_icon("chart_bar_delete.png",___("Historie löschen"))."</a>";
	}


	if ($user_is_manager && !$bl_email) {
		//wenn adr noch nicht in blacklist, flag wird oben gesetzt
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$blacklistURLPara_."\" title=\"".___("Adresse in Blacklist eintragen")."\">".tm_icon("bullet_add.png",___("Adresse in Blacklist eintragen"),"","","","ruby_key.png")."</a>";
	}
	if ($user_is_manager && $bl_email) {
		//wenn adr noch nicht in blacklist, flag wird oben gesetzt
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$blacklistDelURLPara_."\" title=\"".___("Adresse aus Blacklist löschen")."\">".tm_icon("bullet_delete.png",___("Adresse aus Blacklist löschen"),"","","","ruby_key.png")."</a>";
	}
	if ($user_is_manager && !$bl_domain) {
		//wenn adr noch nicht in blacklist, flag wird oben gesetzt
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$blacklistDomainURLPara_."\" title=\"".___("Domain in Blacklist eintragen")."\">".tm_icon("bullet_add.png",___("Domain in Blacklist eintragen"),"","","","ruby_link.png")."</a>";
	}
	if ($user_is_manager && $bl_domain) {
		//wenn adr noch nicht in blacklist, flag wird oben gesetzt
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$blacklistDomainDelURLPara_."\" title=\"".___("Domain aus Blacklist löschen")."\">".tm_icon("bullet_delete.png",___("Domain aus Blacklist löschen"),"","","","ruby_link.png")."</a>";
	}
	$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$delURLPara_."\" onclick=\"return confirmLink(this, '".___("Adresse löschen")."')\" title=\"".___("Adresse löschen")."\">".tm_icon("cross.png",___("Adresse löschen"))."</a>";

	//show log summary
	//search for logs, section and entry!
	//$search_log['object']="xxx"; <-- is set above in section link to logbook
	$search_log['edit_id']=$ADR[$acc]['id'];
	require(TM_INCLUDEPATH_GUI."/log_summary_section_entry.inc.php");

	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";
}

$_MAIN_OUTPUT.= "</tbody></table>";

require_once(TM_INCLUDEPATH_GUI."/adr_list_form_show.inc.php");

require(TM_INCLUDEPATH_GUI."/pager.inc.php");

}//no_list !=1

require_once(TM_INCLUDEPATH_GUI."/adr_list_legende.inc.php");
?>