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

$_MAIN_DESCR=___("Adressen exportieren (CSV-Export)");
#$_MAIN_MESSAGE="";

//status
$InputName_Status="status";//
$$InputName_Status=getVar($InputName_Status);
//trennzeichen
$InputName_Delimiter="delimiter";//
$$InputName_Delimiter=getVar($InputName_Delimiter);
//export dateiname
$InputName_File="filename";//
$$InputName_File=clear_text(getVar($InputName_File));
//status
$InputName_Status="status";//
$$InputName_Status=getVar($InputName_Status);
//blacklist
$InputName_Blacklist="check_blacklist";//
$$InputName_Blacklist=getVar($InputName_Blacklist);

//usr limit
$InputName_Limit="export_limit_user";//
$$InputName_Limit=getVar($InputName_Limit);
if (empty($$InputName_Limit)) $$InputName_Limit=0;

//usr offset
$InputName_Offset="export_offset_user";//
$$InputName_Offset=getVar($InputName_Offset);
if (empty($$InputName_Offset)) $$InputName_Offset=0;

$InputName_FileExisting="file_existing";//datei auswahl
$$InputName_FileExisting=getVar($InputName_FileExisting);

//append//an bestehende datei anfügen
$InputName_Append="append";//
$$InputName_Append=getVar($InputName_Append);

//aaaaand action
$set=getVar("set");
//adr gruppe
$InputName_Group="adr_grp_id";//gruppen id
$$InputName_Group=getVar($InputName_Group);

//ausgabedatei:
//standard name aus datum fuer export generieren
$created=date("Y-m-d H:i:s");
//default:
$Export_Filename="export_".date_convert_to_string($created)."";
if (!empty($$InputName_File)) {
	//wenn dateiname angegeben...:
	$CSV_Filename=$$InputName_File;
} else {
	//wenn kein name --> default
	$CSV_Filename=$Export_Filename;
}
//extension .csv
$CSV_Filename=$CSV_Filename.".csv";
//oder
//wenn bestehende datei ausgewaehlt, nehmen wir diese!
if (!empty($file_existing)) {
	$CSV_Filename=$file_existing;
}
//neuer name im formular ist default fuer naechsten aufruf
$$InputName_File=$Export_Filename;


//export und gruppe gewaehlt?
if ($set=="export" && $adr_grp_id>0) {
/*


exportiere
	export_total adressen
	ab export_offset
		in paketen zu export_limit_run stueck

export_total=anzahl adressen gesamt
offset=anzahl eintraege die uebersprungen werden
limit_usr=maximale anzahl zu exportierender adressen, benutzerdefiniert
limit_run=anzahl adressen pro durchlauf
run_max=Anzahl durchlaeufe zu je limit_run eintraege die maximal die noetig sind um export_total eintraege zu exportieren

//wenn limit_usr angegeben ist, und kleiner oder gleich export_total(anzahl eintraege gesamt), dann setze export_total=limit_usr
if (limit_usr > 0 && !empty && limit_usr <= export_total) export_total=limit_usr
//wenn limit_usr kleiner der anzahl zu exportierender eintraege pro durchlauf, dann setze limit_run=limit_usr
if (limit_usr < limit_run) limit_run=limit_usr
//anzahl maximaler durchlaeufe
run_max=(int)(export_total / limit_run)
//schleife
for (run=0; run <= run_max; run++)
	//wenn anz durchlaeufe * limit_run >= export_total, limit anpassen!
	if ( (run+1) * limit_run) >= export_total)
		//limit_run justieren: gesamt - durchlaeufe * anzahl_pro_durchlauf
		limit_run = export_total - ( run * limit_run)
	if limit_run >0
	//hole adressen
	get(offset,limit_run)
	offset +=limit_run
//

Beispiel:

export_total=9999
offset=0
limit_run=500

1)
limit_usr=333
--> export_total=333
--> limit_run=333
--> run_max=333/333=1
run:0
	--> 1*333= 333 OK
	get(0,333)
run:1
	-->2*333>333 !
		limit_run=333-1*333=0
	get(333,0)

2)
limit_usr=666
--> export_total=666
--> limit_run=500
--> run_max=666/500=1,xxxx
run:0
	--> 1*500< 666
	get(0,500)
run:1
	--> 2*500> 666
		limit_run=666-1*500=166
	get(500,166)

3)
limit_usr=20000
--> export_total=9999
--> limit_run=3500
--> run_max=9999/3500=2,xxxx
run:0
	--> 1*3500< 9999
	get(0,3500)
run:1
	--> 2*3500< 9999
	get(3500,3500)
run:2
	--> 3*3500> 9999
		limit_run=9999-2*3500=9999-7000=2999
	get(7000,2999)


3)
offset=5000
limit_usr=20000
--> export_total=9999
--> limit_run=3500
--> run_max=9999/3500=2,xxxx
run:0
	--> 1*3500< 9999
	get(5000,3500)
run:1
	--> 2*3500< 9999
	get(8500,3500)
run:2
	--> 3*3500> 9999
		limit_run=9999-2*3500=9999-7000=2999
	get(11000,2999)

*/
	//anzahl adressen die auf einmal in ein array gepackt und geschrieben werden sollen, abhaengig vom Speicher fuer PHP. wird definiert in tm_lib
	$export_limit_run=$adr_row_limit;//default limit adressen im array pro durchgang
	//addressen initialisieren
	$ADDRESS=new tm_ADR();
	$BLACKLIST=new tm_BLACKLIST();
	//ggf nach status filtern?
	$search['status']=$status;
	$code=0;
	/*********************************************/
	//gesamtanzahl adressen ermitteln
	$adc=$ADDRESS->countAdr($adr_grp_id,$search);//grp_id,search//
	$export_total=$adc;
	if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_total=".$export_total);
	//wenn limit_usr angegeben ist, und kleiner oder gleich export_total(anzahl eintraege gesamt), dann setze export_total=limit_usr
	//if (limit_usr > 0 && !empty && limit_usr <= export_total) export_total=limit_usr
	if ($export_limit_user > 0 && $export_limit_user <= $export_total)	{
		if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_limit_user (".$export_limit_user.") > 0 && >= export_total (".$export_total.")");
		$export_total=$export_limit_user;
		if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("new export_total = export_limit_user (".$export_limit_user.")");
	}
	//wenn limit_usr kleiner der anzahl zu exportierender eintraege pro durchlauf, dann setze limit_run=limit_usr
	//if (limit_usr < limit_run) limit_run=limit_usr
	if ($export_limit_user >0 && $export_limit_user < $export_limit_run)	{
		if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_limit_user (".$export_limit_user.") < export_limit_run (".$export_limit_run.")");
		$export_limit_run=$export_limit_user;
		if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("new export_limit_run = export_limit_user (".$export_limit_user.")");
	}
	//anzahl maximaler durchlaeufe
	//run_max=(int)(export_total / limit_run)
	$export_run_max=(int)($export_total / $export_limit_run);
	if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_run_max = (int) export_total (".$export_total.") / export_limit_run (".$export_limit_run.") = ".$export_run_max);
	/*********************************************/
	//hat der user einen offset gesetzt?
	$export_offset=0;
	if ($export_offset_user>0)	{
		$export_offset=$export_offset_user;
	}
	/*********************************************/
	//Gruppe auslesen
	$adr_grp=$ADDRESS->getGroup($adr_grp_id);
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("gewählte Gruppe: %s"),$adr_grp[0]['name']));
	$_MAIN_MESSAGE.=tm_message_notice(sprintf(___("gewählter Status")));
	$status_message=___("Alle");
	if (!empty($status)) {
		$status_message="&nbsp;<img src=\"".$tm_iconURL."/".$STATUS['adr']['statimg'][$status]."\" border=\"0\">".
								"&nbsp;".display($STATUS['adr']['status'][$status]).
								"&nbsp;(".display($STATUS['adr']['descr'][$status]).")";
	}
	$_MAIN_MESSAGE.="<br>".sprintf(___("mit Status: %s"),$status_message);
	$_MAIN_MESSAGE.= "<br>".sprintf(___("%s Einträge gesamt."),$adc);
	$_MAIN_MESSAGE.= "<br>".sprintf(___("Maximal %s Einträge werden exportiert."),($export_total));
	$_MAIN_MESSAGE.= "<br>".sprintf(___("Offset: %s"),$export_offset_user);

	if ($check_blacklist=="all") {
		$_MAIN_MESSAGE.= tm_message_notice(___("Keine Überprüfung der Blacklist. Alle Adressen werden exportiert."));
	}
	if ($check_blacklist=="blacklisted") {
		$_MAIN_MESSAGE.= tm_message_notice(___("Überprüfung der Blacklist, nur Adressen auf der Blacklist werden exportiert."));
	}
	if ($check_blacklist=="not_blacklisted") {
		$_MAIN_MESSAGE.= tm_message_notice(___("Überprüfung der Blacklist, nur Adressen die nicht auf der Blacklist stehen werden exportiert."));
	}

	if ($export_total>0) {	//wenn min 1 eintrag:
		 $_MAIN_MESSAGE.= tm_message_notice(sprintf(___("Exportiere %s"),"'".$CSV_Filename."'"));
		//adressen haeppchenweise auslesen
		//File oeffnen!
		if ($append==1) {
			$fp = fopen($tm_datapath."/".$CSV_Filename,"a");
			 $_MAIN_MESSAGE.= tm_message_notice(sprintf(___("Daten werden an bestehende Datei angefügt: %s"),"'".$CSV_Filename."'"));
		} else {
			$fp = fopen($tm_datapath."/".$CSV_Filename,"w");
			 $_MAIN_MESSAGE.= tm_message_notice(sprintf(___("Daten werden in neue Datei gespeichert: %s"),"'".$CSV_Filename."'"));
		}
		if (!$fp) {
			 $_MAIN_MESSAGE.= tm_message_error(sprintf(___("Export-Datei kann nicht geöffnet werden %s"),"'".$CSV_Filename."'"));
		} else {
			if ($append!=1) {
				//CSV Headline
				$CSV=$ADDRESS->genCSVHeader($delimiter);
				/*
				"\"email\"$delimiter\"f0\"$delimiter\"f1\"$delimiter\"f2\"$delimiter\"f3\"$delimiter\"f4\"$delimiter\"f5\"$delimiter\"f6\"$delimiter\"f7\"$delimiter\"f8\"$delimiter\"f9\"$delimiter\"id\"$delimiter\"created\"$delimiter\"author\"$delimiter\"updated\"$delimiter\"editor\"$delimiter\"aktiv\"$delimiter\"status\"$delimiter\"code\"$delimiter\"errors\"$delimiter\"clicks\"$delimiter\"views\"$delimiter\"newsletter\"$delimiter\"memo\"\n";
				*/
				//write header
				if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("Schreibe CSV-Header");
				if (!tm_DEMO()) fputs($fp,$CSV,strlen($CSV));
			}
			//anzahl wirklich exportierter eintraege:
			$exported=0;
			//loop 1
			if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("Do Loop:");
			for ($export_run=0;$export_run <= $export_run_max; $export_run++) { //<=
			//schleife
			//for (run=0; run <= run_max; run++)
				if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_run (".$export_run.") <;= export_run_max (".$export_run_max.")");
				//wenn anz durchlaeufe * limit_run >= export_total, limit anpassen!
				//if ( (run+1) * limit_run) > export_total)
				if ( (($export_run +1) * $export_limit_run  ) >= $export_total ) {
					if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_run+1 (".($export_run+1).") * export_limit_run (".$export_limit_run.") >= export_total (".$export_total.")");
					//limit_run justieren: gesamt - durchlaeufe * anzahl_pro_durchlauf
					//limit_run = export_total - ( run * limit_run)
					$export_limit_run_prev=$export_limit_run;
					$export_limit_run = $export_total - ($export_run * $export_limit_run);
					if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("new export_limit_run (".$export_limit_run.") = export_total (".$export_total.") - (export_run(".$export_run.") * export_limit_run(".$export_limit_run_prev."))");
				}
				if ($export_limit_run>0) {
					if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_limit_run (".$export_limit_run.") >0");
					//hole adressen
					//get(offset,limit_run)
					// getAdr($id=0,$offset=0,$limit=0,$group_id=0,$search=Array(),$sortIndex="",$sortType=0)
					//adressen auslesen
					if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("get(".$export_offset.",".$export_limit_run.")");
					$ADR=$ADDRESS->getAdr(0,$export_offset,$export_limit_run,$adr_grp_id,$search);//id,offset,limit,group
					$ac=count($ADR);//aktuelle anzahl ermittelte adressen
					if ($ac>0) {
						if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("export_run ".$export_run." von ".$export_run_max." , Found: ".$ac." Adr: ".$export_offset." - ".(".$export_offset."+".$ac.")." , Total: ".$adc." Adr, Limit: ".$export_limit_run.", Offset: ".$export_offset."");
						//loop 2
						for ($acc=0;$acc<$ac;$acc++) {
							//blacklist einstellungen pruefen
							//wenn "alle" exportieren, oder "blacklisted" und isblacklisted=true oder nur not_blacklisted und isblacklisted=false
							$blacklisted=false;
							if ($check_blacklist=="blacklisted" || $check_blacklist=="not_blacklisted") {
								$blacklisted=$BLACKLIST->isBlacklisted($ADR[$acc]['email'],"email");
							}
							if ($check_blacklist=="all" || 
								($check_blacklist=="blacklisted" && $blacklisted) || 
								($check_blacklist=="not_blacklisted" && !$blacklisted) 
								) {
									//CSV Zeile erstellen:
									$CSV=$ADDRESS->genCSVline($ADR[$acc],$delimiter);
									//free some memory ;-)
									unset($ADR[$acc]);
									//und in file schreiben:
									if (!tm_DEMO()) fputs($fp,$CSV,strlen($CSV));
									$exported++;
							}
						}//for	$acc
						//datei schreiben:	war: append_file($tm_datapath,$CSV_Filename,$CSV);, verbraucht aber da die werte in CSV gesammelt werde zu viel RAM, wir schreiben innerhalb der schleife einfach direkt ins file
						//neuer offset:
						//offset +=limit_run
						$export_offset +=$export_limit_run;
						if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("new export_offset = ".$export_offset);
						if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("---------");
						#$CSV="";
					}//if ac>0
				}//export_limit_run >0
			}//for $export run
			unset($CSV);
			unset($ADR);
			//close file
			fclose($fp);
			//chmod
			chmod ($tm_datapath."/".$CSV_Filename, 0664);
		}//if $fp
		$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Es wurden insgesamt %s Einträge exportiert."),$exported));
		$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Datei gespeichert unter: %s"),"'".$tm_datadir."/".$CSV_Filename."'"));
		$_MAIN_MESSAGE.= "<a href=\"".$tm_URL_FE."/".$tm_datadir."/".$CSV_Filename."\" target=\"_preview\">".$tm_datadir."/".$CSV_Filename."</a>";
	}//adc>0
}//export
require_once (TM_INCLUDEPATH_GUI."/adr_export_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/adr_export_form_show.inc.php");
?>