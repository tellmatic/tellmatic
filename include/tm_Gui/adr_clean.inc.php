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

$_MAIN_DESCR=___("Adressdatenbank bereinigen");
$_MAIN_MESSAGE.="";
$ADDRESS=new tm_ADR();

$InputName_Set="set";//
$$InputName_Set=getVar($InputName_Set);

$InputName_Blacklist="blacklist";//
$$InputName_Blacklist=getVar($InputName_Blacklist);

$InputName_Name="email";//
$$InputName_Name=getVar($InputName_Name);

$InputName_Status="status";//
$$InputName_Status=getVar($InputName_Status);

$InputName_Group="adr_grp_id";//
$$InputName_Group=getVar($InputName_Group);

$InputName_StatusDst="status_multi";//
$$InputName_StatusDst=getVar($InputName_StatusDst);

$InputName_GroupDst="adr_grp_id_multi";//
pt_register("POST",$InputName_GroupDst);
if (!isset($$InputName_GroupDst)) {
	$$InputName_GroupDst=Array();
}

$InputName_RemoveDups="remove_duplicates";//
$$InputName_RemoveDups=getVar($InputName_RemoveDups);//aerch and remove duplicates?

$InputName_RemoveDupsDetails="remove_duplicates_details";
$$InputName_RemoveDupsDetails=getVar($InputName_RemoveDupsDetails);//show details when removing dups

$InputName_RemoveDupsMethod="remove_duplicates_method";
$$InputName_RemoveDupsMethod=getVar($InputName_RemoveDupsMethod);//duplicate remove method, keep first last random

$InputName_RemoveDupsLimit="remove_duplicates_limit";
$$InputName_RemoveDupsLimit=getVar($InputName_RemoveDupsLimit);//limit, remove xxx dups at once

$InputName_RemoveDupsExport="remove_duplicates_export";//export dups?
$$InputName_RemoveDupsExport=getVar($InputName_RemoveDupsExport);//export dups?

$showGroupUrlPara=tmObjCopy($mSTDURL);
$showGroupStatusUrlPara=tmObjCopy($mSTDURL);

$showGroupUrlPara->addParam("act","adr_list");
$showGroupStatusUrlPara->addParam("act","adr_list");

if (!empty($set)) {
	$GRP=$ADDRESS->getGroup($adr_grp_id);
	$search['email']=str_replace("*","%",$email);
	$search['status']=$status;
	$search['group']=$adr_grp_id;
	$ac=$ADDRESS-> countAdr(0,$search);
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("%s Einträge werden bearbeitet."),$ac));
}

/*
//DISABLED!!!!:
//alle fehlerhaften!
if ($set=="delete" && $status=="delete_all") { // && $doit==1
	//	6
	$search['status']=6;
	$ac=$ADDRESS-> countAdr(0,$search);
	if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	$_MAIN_MESSAGE.="<br>".sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),"<b>".$ac."</b>","<b>".$GRP[0]['name']."</b>","<b>".$STATUS['adr']['status'][$search['status']]."</b>");
	//	7
	$search['status']=7;
	$ac=$ADDRESS-> countAdr(0,$search);
	if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	$_MAIN_MESSAGE.="<br>".sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),"<b>".$ac."</b>","<b>".$GRP[0]['name']."</b>","<b>".$STATUS['adr']['status'][$search['status']]."</b>");
	//	8
	$search['status']=8;
	$ac=$ADDRESS-> countAdr(0,$search);
	if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	$_MAIN_MESSAGE.="<br>".sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),"<b>".$ac."</b>","<b>".$GRP[0]['name']."</b>","<b>".$STATUS['adr']['status'][$search['status']]."</b>");
	//	9
	$search['status']=9;
	$ac=$ADDRESS-> countAdr(0,$search);
	if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	$_MAIN_MESSAGE.="<br>".sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),"<b>".$ac."</b>","<b>".$GRP[0]['name']."</b>","<b>".$STATUS['adr']['status'][$search['status']]."</b>");
	#//	10
	#$search['status']=10;
	#$ac=$ADDRESS-> countAdr(0,$search);
	#if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	#$_MAIN_MESSAGE.="<br>".sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),"<b>".$ac."</b>","<b>".$GRP[0]['name']."</b>","<b>".$STATUS['adr']['status'][$search['status']]."</b>");
	#
	//	11
	$search['status']=11;
	$ac=$ADDRESS-> countAdr(0,$search);
	if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	$_MAIN_MESSAGE.="<br>".sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),"<b>".$ac."</b>","<b>".$GRP[0]['name']."</b>","<b>".$STATUS['adr']['status'][$search['status']]."</b>");
}
*/

if (((!empty($set) && $set!="delete") || $blacklist==1) && $ac>0 && $remove_duplicates!=1) { // wenn min 1 adr gefunden
	//meldungen ausgeben
	if ($set=="aktiv_1") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden aktiviert"));
	}
	if ($set=="aktiv_0") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden deaktiviert."));
	}
	if ($set=="set_status") {
		$_MAIN_MESSAGE.="<br>".sprintf(___("Setze neuen Status für ausgewählte Adressen auf %s"),tm_icon($STATUS['adr']['statimg'][$status_multi],display($STATUS['adr']['status'][$status_multi]))."&nbsp;\"<b>".display($STATUS['adr']['status'][$status_multi]))."</b>\"";
	}
	if ($set=="copy_grp") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden in die gewählten Gruppen kopiert."));
	}
	if ($set=="move_grp") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden in die gewählten Gruppen verschoben."));
	}
	if ($set=="delete_grp") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden aus den gewählten Gruppen gelöscht."));
	}
	if ($blacklist==1) {
		$BLACKLIST=new tm_BLACKLIST();
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden zur Blacklist hinzugefügt."));
	}
	if ($set=="delete_history") {
		$QUEUE=new tm_Q();
		$_MAIN_MESSAGE.=tm_message_notice(___("Historie ausgewählter Adressen werden gelöscht."));
	}
	if ($set=="check") {
		$_MAIN_MESSAGE.=tm_message_notice(___("Ausgewählte Adressen werden zur automatischen Prüfung vorgemerkt."));
	}
//adressids holen ud in adr_id_arr speichern
	//function getAdrID($group_id=0,$search=Array())
	$adr_id_arr=$ADDRESS->getAdrID(0,$search);

	//adrarray durchwandern
	for ($acc_m=0;$acc_m<$ac;$acc_m++) {
		//blacklist? MUSS vor anderen aktionen stehen wegen getAdr!!
		if ($blacklist==1) {//hier wird naemlich auch nich $set abgefragt! sondern eben $blacklist als eigenes flag! (checkbox), soll aber auch verfuegbar sein wenn set==delete 
		//ist.... sonst muesste man zum blacklisten alle anderen aktionen kombinieren :O
		//andere loesung waere checkboxen, die aber per js auf gueltigkeit geprueft werden muessen da verschieben und loeschen nix bringt!
			//get adr
			$ADR_BL=$ADDRESS->getAdr($adr_id_arr[$acc_m]);
			//checkblacklist
			if (!$BLACKLIST->isBlacklisted($ADR_BL[0]['email'],"email")) {
			//blacklist adr
				$BLACKLIST->addBL(Array(
						"siteid"=>TM_SITEID,
						"expr"=>$ADR_BL[0]['email'],
						"aktiv"=>1,
						"type"=>"email"
						));
			}
		}
		//activate adr
		if ($set=="aktiv_1") {
			$ADDRESS->setAktiv($adr_id_arr[$acc_m],1);
		}
		//deactivate adr
		if ($set=="aktiv_0") {
			$ADDRESS->setAktiv($adr_id_arr[$acc_m],0);
		}
		//set status
		if ($set=="set_status") {
			$ADDRESS->setStatus($adr_id_arr[$acc_m],$status_multi);
		}
		//copy adr to selected grps
		if ($set=="copy_grp") {
			//get old groups
			$adr_groups=$ADDRESS->getGroupID(0,$adr_id_arr[$acc_m],0);
			//set new groups
			$ADDRESS->setGroup($adr_id_arr[$acc_m],$adr_grp_id_multi,$adr_groups,1);//set groups, merge=1=merge groups
		}
		//move adr to selected grps
		if ($set=="move_grp") {
			//set new groups
			$ADDRESS->setGroup($adr_id_arr[$acc_m],$adr_grp_id_multi,0);//merge=0=move
		}
		//delete adr ref from selected grps
		if ($set=="delete_grp") {
			//get old groups
			$adr_groups=$ADDRESS->getGroupID(0,$adr_id_arr[$acc_m],0);
			//set new groups
			$ADDRESS->setGroup($adr_id_arr[$acc_m],$adr_grp_id_multi,$adr_groups,2);//set groups, merge=2=diff
		}
		//delete history
		if ($set=="delete_history") {
			$QUEUE->clearH(Array("adr_id"=>$adr_id_arr[$acc_m]));
		}

	}
}

//muss nach blacklisting kommen!!! wegen getAdr abfrage in blacklist, siehe oben
if ($set=="delete") {// && $status!="delete_all"
	if (!tm_DEMO()) $ADDRESS->cleanAdr($search);
	if ($status==0) $_MAIN_MESSAGE.=tm_message_success(sprintf(___("%s Einträge aus der Gruppe %s wurden gelöscht."),$ac,"'".$GRP[0]['name']."'"));
	if ($status>0) $_MAIN_MESSAGE.=tm_message_success(sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden gelöscht."),$ac,"'".$GRP[0]['name']."'","'".$STATUS['adr']['status'][$search['status']]."'"));
}

if ($set=="check") {
	$ADDRESS->markRecheck(0,1,$search);
	if ($status==0) $_MAIN_MESSAGE.=tm_message_success(sprintf(___("%s Einträge aus der Gruppe %s wurden zur Prüfung vorgemerkt."),$ac,"'".$GRP[0]['name']."'"));
	if ($status>0) $_MAIN_MESSAGE.=tm_message_success(sprintf(___("%s Einträge aus der Gruppe %s mit dem Status %s wurden zur Prüfung vorgemerkt."),$ac,"'".$GRP[0]['name']."'","'".$STATUS['adr']['status'][$search['status']]."'"));
}

if ($remove_duplicates==1) {
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Duplikate aus ALLEN Gruppen werden ermittelt und geloescht")));
	if ($remove_duplicates_limit>0) {	
		$_MAIN_MESSAGE.=tm_message(___("Limit").": ".$remove_duplicates_limit);
	}
	//if not exporting,just use delete dups method
	if ($remove_duplicates_export!=1) {
		$ADDRESS->remove_duplicates(Array('method'=>$remove_duplicates_method, 'limit' => $remove_duplicates_limit));
	}
	//otherwise, if we want to export addresses first, we have to fetch, export and delete the adr ourselv
	if ($remove_duplicates_export==1) {
		$created=date("Y-m-d H:i:s");
		$CSV_Filename="duplicates_".date_convert_to_string($created)."";
		//extension .csv
		$CSV_Filename=$CSV_Filename.".csv";
		$delimiter=",";
		$fp = fopen($tm_datapath."/".$CSV_Filename,"a");
		if ($fp) {
			$CSV=$ADDRESS->genCSVHeader($delimiter);
			if (!tm_DEMO()) fputs($fp,$CSV,strlen($CSV));
			$ADDRESS->fetch_duplicates(Array('method'=>$remove_duplicates_method, 'limit' => $remove_duplicates_limit));
			//now export and delete each entry
			foreach ($ADDRESS->DUPLICATES['dups'] as $DUPDEL) {
				foreach ($DUPDEL['del'] as $adr_dupdel_id) {
	
					//fetch data for export
					$ADRDUP=$ADDRESS->getAdr($adr_dupdel_id,0,0,0,Array(),"",0,1);//with details
					//CSV Zeile erstellen:
					$CSV=$ADDRESS->genCSVline($ADRDUP[0],$delimiter);
					//und in file schreiben:
					if (!tm_DEMO()) fputs($fp,$CSV,strlen($CSV));
					//finally delete duplicate from database
					if (!tm_DEMO()) $ADDRESS->delADR($adr_dupdel_id);
				}
			}
			fclose($fp);
		}
	}
	
	
		
	if ($remove_duplicates_method=='first') {
		$_MAIN_MESSAGE.=tm_message_notice(___("Es wird jeweils der erste/älteste Eintrag erhalten"));
	}
	if ($remove_duplicates_method=='last') {
		$_MAIN_MESSAGE.=tm_message_notice(___("Es wird jeweils der letzte/neueste Eintrag erhalten"));
	}
	if ($remove_duplicates_method=='random') {
		$_MAIN_MESSAGE.=tm_message_notice(___("Es wird jeweils ein zufälliger Eintrag erhalten"));
	}
	#$_MAIN_MESSAGE.="<br>";
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("Es wurden %s Adressen mit insgesamt %s Dubletten gefunden"),$ADDRESS->DUPLICATES['count'],$ADDRESS->DUPLICATES['count_dup']));
	if ($remove_duplicates_details==1) {
		$_MAIN_MESSAGE.="<br>".___("Details").":";
		foreach ($ADDRESS->DUPLICATES['dups'] as $DUP) {
			$_MAIN_MESSAGE.="<br>".sprintf(___("E-Mail %s hat %s Duplikate"),"<b>".$DUP['email']."</b>","<b>".$DUP['qty']."</b>");
			$_MAIN_MESSAGE.="<br>&nbsp;&nbsp;&nbsp;".sprintf(___("Eintrag mit ID %s wird erhalten."),"<b>".$DUP['id'][$DUP['keep']]."</b>");
			$dupdelids="";
			foreach ($DUP['del'] as $delid) {
				$dupdelids.=$delid." ";
			}
			
			$_MAIN_MESSAGE.="<br>&nbsp;&nbsp;&nbsp;".sprintf(___("Einträge mit den IDs %s wurden gelöscht"),"<b>".$dupdelids."</b>");
		}
	}
	$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Es wurden %s Duplikate entfernt"),($ADDRESS->DUPLICATES['count_dup'] - $ADDRESS->DUPLICATES['count'])));
	if ($remove_duplicates_export==1) {
		$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Datei gespeichert unter: %s"),$tm_datadir."/".$CSV_Filename));
		$_MAIN_MESSAGE.= "<a href=\"".$tm_URL_FE."/".$tm_datadir."/".$CSV_Filename."\" target=\"_preview\">".$tm_datadir."/".$CSV_Filename."</a>";
	}
	#if (tm_DEBUG()) $_MAIN_MESSAGE.="<pre>".print_r($ADDRESS->DUPLICATES,true)."</pre>";
}

//uebersicht anzeigen!
$_MAIN_OUTPUT.="<div class=\"adr_summary\">";#ccdddd
$_MAIN_OUTPUT.=tm_icon("information.png",___("Übersicht"),___("Übersicht"))."&nbsp;<b>".___("Übersicht").":</b>";
$entrys_all=$ADDRESS->countADR();//anzahl eintraege, alles
$_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen Gesamt"),"<b>".$entrys_all."</b>");
//recheck
$search_recheck=Array();
$search_recheck['recheck']=1;
$entrys_recheck=$ADDRESS->countADR(0,$search_recheck);//anzahl eintraege die zur pruefung markiert sind
if ($entrys_recheck>0) $_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen sind zur Prüfung vorgemerkt"),"<b>".$entrys_recheck."</b>");
//inaktiv
$search_inaktiv=Array();
$search_inaktiv['aktiv']='0';
$entrys_inaktiv=$ADDRESS->countADR(0,$search_inaktiv);//anzahl eintraege die zur pruefung markiert sind
if ($entrys_inaktiv>0) $_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen sind deaktiviert"),"<b>".$entrys_inaktiv."</b>");
$_MAIN_OUTPUT.="<br>".sprintf(___("%s Dubletten"),"<b>".($ADDRESS->count_duplicates())."</b>");

$_MAIN_OUTPUT.="<br><br></div><br>";
	$AG=$ADDRESS->getGroup();
	$agc=count($AG);
	for ($agcc=0;$agcc<$agc;$agcc++) {
	
		$showGroupUrlPara->addParam("adr_grp_id",$AG[$agcc]['id']);
		$showGroupStatusUrlPara->addParam("adr_grp_id",$AG[$agcc]['id']);
		$showGroupUrlPara_=$showGroupUrlPara->getAllParams();
		
		$_MAIN_OUTPUT.= "<a  href=\"javascript:switchSection('g_".$AG[$agcc]['id']."')\" title=\"".___("Details einblenden")."\">";
		$_MAIN_OUTPUT.=tm_icon("add.png",___("Details einblenden"));
		$_MAIN_OUTPUT.="&nbsp;'<b>".$AG[$agcc]['name']."</b>'</a>&nbsp;&nbsp;";
		//Gesamtstatus, anzahl per adr-status
		$ac=$ADDRESS->countADR($AG[$agcc]['id']);
		$_MAIN_OUTPUT.=sprintf(___("%s Adressen"),"<b>".$ac."</b>");
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$showGroupUrlPara_."\" title=\"".___("Adressen anzeigen")."\">".tm_icon("eye.png",___("Adressen anzeigen"))."</a>";
		$_MAIN_OUTPUT.="<div id=\"g_".$AG[$agcc]['id']."\" style=\"border:1px dashed #cccccc; background-color:#dddddd; -moz-border-radius:0em 0em 2em 2em; padding:8px;\">";
		$search_recheck_group=Array();
		$search_recheck_group['recheck']=1;
		$entrys_recheck_group=$ADDRESS->countADR($AG[$agcc]['id'],$search_recheck_group);//anzahl eintraege die zur pruefung markiert sind
		if ($entrys_recheck_group>0) { 
			$_MAIN_OUTPUT.="<div class=\"adr_summary\">";#ccdddd
			$_MAIN_OUTPUT.="".sprintf(___("%s Adressen sind zur Prüfung vorgemerkt"),"<b>".$entrys_recheck_group."</b>");
			$_MAIN_OUTPUT.="</div>";
		}
	//inaktiv
	$search_inaktiv_group=Array();
	$search_inaktiv_group['aktiv']='0';
	$entrys_inaktiv_group=$ADDRESS->countADR($AG[$agcc]['id'],$search_inaktiv_group);//anzahl eintraege die zur pruefung markiert sind
	if ($entrys_inaktiv_group>0) $_MAIN_OUTPUT.="<br>".sprintf(___("%s Adressen sind deaktiviert"),"<b>".$entrys_inaktiv_group."</b>");
		//per adr status:
		$asc=count($STATUS['adr']['status']);
		for ($ascc=1; $ascc<=$asc; $ascc++) {
			$showGroupStatusUrlPara->addParam("s_status",$ascc);
			$showGroupStatusUrlPara_=$showGroupStatusUrlPara->getAllParams();
			unset($search);
			$search['status']=$ascc;
			$ac=$ADDRESS->countADR($AG[$agcc]['id'],$search);
			if ($ac>0) {
				$_MAIN_OUTPUT.="<br><b>".$ac."</b>".
								"&nbsp;".tm_icon($STATUS['adr']['statimg'][$ascc],display($STATUS['adr']['status'][$ascc])).
								"&nbsp;".display($STATUS['adr']['status'][$ascc]).
								"&nbsp;(".display($STATUS['adr']['descr'][$ascc]).")";
				$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$showGroupStatusUrlPara_."\" title=\"".___("Adressen anzeigen")."\">".tm_icon("eye.png",___("Adressen anzeigen"))."</a>";
			}
		}
		$_MAIN_OUTPUT.="</div>";
		$_MAIN_OUTPUT.= "<script type=\"text/javascript\">switchSection('g_".$AG[$agcc]['id']."');</script>";
		$_MAIN_OUTPUT.="<br>";

	}
$_MAIN_OUTPUT.="<br>";
require_once (TM_INCLUDEPATH_GUI."/adr_clean_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/adr_clean_form_show.inc.php");
?>