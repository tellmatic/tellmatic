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

$_MAIN_DESCR=___("Adressen importieren (CSV-Import)");
$set=getVar("set");

//status f. neue adr
$InputName_Status="status_new";//
$status_new=getVar("status_new");
//status fuer existierende adr.
$InputName_StatusEx="status_exists";//
$status_ex=getVar("status_exists");

//aktiv fuer neue adr.
$InputName_AktivNew="aktiv_new";//
$$InputName_AktivNew=getVar($InputName_AktivNew);

//aktiv fuer existierende adr.
$InputName_AktivEx="aktiv_existing";//
$$InputName_AktivEx=getVar($InputName_AktivEx);

//Dublettencheck on off
$InputName_DoubleCheck="check_double";//
$$InputName_DoubleCheck=getVar($InputName_DoubleCheck);

//bestehende adressen ueberspringen? skip existing adr? no update!
$InputName_SkipEx="skip_existing";//
$$InputName_SkipEx=getVar($InputName_SkipEx);

//trennzeichen
$InputName_Delimiter="delimiter";//
$$InputName_Delimiter=getVar($InputName_Delimiter);

//array mit gruppen
$adr_grp=Array();
$InputName_Group="adr_grp";//
pt_register("POST","adr_grp");

$InputName_File="file_new";//datei
pt_register("POST","file_new");

//trennzeichen
$InputName_Bulk="bulk";//
$$InputName_Bulk=getVar($InputName_Bulk,0);

$InputName_FileExisting="file_existing";//trackimage auswahl
$$InputName_FileExisting=getVar($InputName_FileExisting);

//delete
$InputName_Delete="delete";//
$$InputName_Delete=getVar($InputName_Delete,0);

//blacklist
$InputName_Blacklist="blacklist";//
$$InputName_Blacklist=getVar($InputName_Blacklist,0);

//blacklist domains
$InputName_BlacklistDomains="blacklist_domains";//
$$InputName_BlacklistDomains=getVar($InputName_BlacklistDomains,0);

//merge groups
$InputName_GroupsMerge="merge_groups";//
$$InputName_GroupsMerge=getVar($InputName_GroupsMerge);

//emailcheck
$InputName_ECheckImport="check_mail_import";//
$$InputName_ECheckImport=getVar($InputName_ECheckImport);

//mark recheck
$InputName_MarkRecheck="mark_recheck";//
$$InputName_MarkRecheck=getVar($InputName_MarkRecheck);

//usr limit
$InputName_Limit="import_limit_user";//
$$InputName_Limit=getVar($InputName_Limit);
if (empty($$InputName_Limit)) $$InputName_Limit=1000;

//usr offset
$InputName_Offset="import_offset_user";//
$$InputName_Offset=getVar($InputName_Offset);
if (empty($$InputName_Offset)) $$InputName_Offset=0;

//proof
$InputName_Proof="proof";//
$$InputName_Proof=getVar($InputName_Proof);

//new group?
$InputName_GroupNew="group_new";//
$$InputName_GroupNew=getVar($InputName_GroupNew,0);

//new group name
$InputName_GroupNewName="group_new_name";//
$$InputName_GroupNewName=getVar($InputName_GroupNewName,0);


$uploaded_file_new=false;

$IMPORT_MESSAGE="";
$IMPORT_LOG="";

if ($set=="import") {
	$ADDRESS=new tm_ADR();
	$BLACKLIST=new tm_BLACKLIST();
	$created=date("Y-m-d H:i:s");
	$author=$LOGIN->USER['name'];
	$CSV_Filename="import_".date_convert_to_string($created).".csv";
	$check=false;

	$EMailcheck_Import=$check_mail_import;

	if ($blacklist_domains==1) {
		$bl_domains=Array();//array mit domainnamen in blacklist
	}

	if (empty($adr_grp)) {
		$IMPORT_MESSAGE.= tm_message_warning(___("Keine Gruppe gewählt."));
	} else {
		$check=true;
	}

	if (!is_numeric($import_offset_user)) {
		$IMPORT_MESSAGE.= tm_message_error(sprintf(___("Offset '%s' ist kein gültiger Wert."),$import_offset_user));
	} else {
		$check=true;
	}

	if (!is_numeric($import_limit_user)) {
		$IMPORT_MESSAGE.= tm_message_error(sprintf(___("Limit '%s' ist kein gültiger Wert."),$import_limit_user));
	} else {
		$check=true;
	}

	//upload prozedur
	if($check && is_uploaded_file($_FILES["file_new"]["tmp_name"])) {
		$uploaded_file_new=true;
		// Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
		if(preg_match("/\." . $allowed_csv_filetypes . "$/i", $_FILES["file_new"]["name"])) {
			// Datei auch nicht zu groß
			if($_FILES["file_new"]["size"] <= $max_upload_size) {
				// Alles OK -> Datei kopieren
				if (move_uploaded_file($_FILES["file_new"]["tmp_name"], $tm_datapath."/".$CSV_Filename)) {
					$IMPORT_MESSAGE.= tm_message_success(___("CSV-Datei erfolgreich hochgeladen."));
					$IMPORT_MESSAGE.= "<ul>".$_FILES["file_new"]["name"];
					$IMPORT_MESSAGE.= " / ".$_FILES["file_new"]["size"]." Byte";
					$IMPORT_MESSAGE.= ", ".$_FILES["file_new"]["type"];
					$IMPORT_MESSAGE.= tm_message(sprintf(___("Datei gespeichert unter: %s"),$tm_datadir."/".$CSV_Filename));
					$IMPORT_MESSAGE.= "<a href=\"".$tm_URL_FE."/".$tm_datadir."/".$CSV_Filename."\" target=\"_preview\">".$tm_datadir."/".$CSV_Filename."</a>";
					$IMPORT_MESSAGE.= "</ul>";
					$check=true;
				} else {
					$IMPORT_MESSAGE.= tm_message_error(___("CSV-Datei konnte nicht hochgeladen werden."));
					$check=false;
				}//copy
			} else {
				$IMPORT_MESSAGE.= tm_message_error(sprintf(___("Die CSV-Datei darf nur eine Grösse von max. %s Byte besitzen."),$max_byte_size));
				$check=false;
			}//max size
		} else {
			$IMPORT_MESSAGE.= tm_message_error(___("Die CSV-Datei besitzt eine ungültige Endung."));
			$check=false;
		}//extension
	} else {
		$IMPORT_MESSAGE.= tm_message_warning(___("Keine CSV-Datei zum Hochladen angegeben."));
		$check=false;
	}//no file
	//ende upload

	if (!$uploaded_file_new) {
		if (!empty($file_existing)) {
			$IMPORT_MESSAGE.=tm_message_notice(sprintf(___("Importiere bestehende CSV-Datei %s"),"'".$tm_datadir."/".$CSV_Filename."'"));
			$CSV_Filename=$file_existing;
			$check=true;
		}
	}

#######################
//new group?
	if ($check && $group_new==1 && $delete!=1 && $blacklist!=1 && $blacklist_domains!=1) {
		if (empty($group_new_name)) {
			$_MAIN_MESSAGE.=tm_message_notice(___("Name der Gruppe nicht angegeben."));
			$group_new_name="Import ".$created;
		}
		$new_group_id=$ADDRESS->addGrp(Array(
						"name"=>$group_new_name,
						"public"=>0,
						"public_name"=>"",
						"descr"=>$group_new_name,
						"aktiv"=>1,
						"prod"=>0,
						"created"=>$created,
						"author"=>$author
						));
			$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neue Addressgruppe %s wurde erstellt."),"'".$group_new_name."'"));
			//gruppe zu ausgewaehlten hinzufuegen
			$adr_grp[count($adr_grp)]=$new_group_id;
	}

#######################

	if ($check && $delete!=1 && $blacklist!=1 && $blacklist_domains!=1) {
		$IMPORT_MESSAGE.=tm_message_notice(___("Status für neue Adressen: "));
		$IMPORT_MESSAGE.=tm_icon($STATUS['adr']['statimg'][$status_new],display($STATUS['adr']['descr'][$status_new]));
		$IMPORT_MESSAGE.= "  ".display($STATUS['adr']['status'][$status_new])."  (".display($STATUS['adr']['descr'][$status_new]).")";

		$IMPORT_MESSAGE.=tm_message_notice(___("Status für bestehende Adressen: "));
		if ($status_ex>0) {
		$IMPORT_MESSAGE.=tm_icon($STATUS['adr']['statimg'][$status_ex],display($STATUS['adr']['descr'][$status_ex]));
			$IMPORT_MESSAGE.= "  ".display($STATUS['adr']['status'][$status_ex])."  (".display($STATUS['adr']['descr'][$status_ex]).")";
		} else {
			$IMPORT_MESSAGE.= " ".___("Keine Änderung");
		}

		
		if ($mark_recheck==1) {
			$IMPORT_MESSAGE.=tm_message_notice(___("Adressenprüfung:"));		
			$IMPORT_MESSAGE.="<br>".tm_icon("spellcheck.png",___("Adressen werden zur automatischen Prüfung vorgemerkt."));
			$IMPORT_MESSAGE.= "&nbsp;".___("Adressen werden zur automatischen Prüfung vorgemerkt.");
		}
		
		$IMPORT_MESSAGE.=tm_message_notice(___("Dublettenprüfung:"));
		if ($check_double==1) {
			$IMPORT_MESSAGE.="<br>".tm_icon("key_go.png",___("Adressen werden auf Eindeutigkeit geprüft."));
			$IMPORT_MESSAGE.= "&nbsp;".___("Adressen werden auf Eindeutigkeit geprüft.");
		} else {
			$IMPORT_MESSAGE.="<br>".tm_icon("key_delete.png",___("Adressen werden nicht auf Eindeutigkeit geprüft."));
			$IMPORT_MESSAGE.= "&nbsp;".___("Adressen werden nicht auf Eindeutigkeit geprüft.");
		}

		if ($aktiv_new==1) {
			$IMPORT_MESSAGE.="<br>".tm_icon("tick.png",___("Aktiv"));
			$IMPORT_MESSAGE.= "&nbsp;".___("Neue Adressen sind aktiv.");
		} else {
			$IMPORT_MESSAGE.="<br>".tm_icon("cancel.png",___("Inaktiv"));
			$IMPORT_MESSAGE.= "&nbsp;".___("Neue Adressen sind inaktiv.");
		}

		if ($aktiv_existing==1) {
			$IMPORT_MESSAGE.="<br>".tm_icon("tick.png",___("Aktiv"));
			$IMPORT_MESSAGE.= "&nbsp;".___("Bestehende Adressen werden aktiviert.");
		}
		if ($aktiv_existing==0) {
			$IMPORT_MESSAGE.="<br>".tm_icon("cancel.png",___("Inaktiv"));
			$IMPORT_MESSAGE.= "&nbsp;".___("Bestehende Adressen werden de-aktiviert.");
		}
		if ($skip_existing==1) {
			$IMPORT_MESSAGE.= tm_message_notice(___("Bestehende Adressen werden übesprungen."));
		} else {
			$IMPORT_MESSAGE.= tm_message_notice(___("Bestehende Adressen werden aktualisiert."));		
		}
		
	#proof?!
		if ($C[0]['proof']==1) {
			if ($proof==1) {
				$IMPORT_MESSAGE.= tm_message_notice(___("Proofing aktiv"));
				$ADDRESS->proof();
			}
		}	
	

	}//check && delete !=1 && blacklist!=1 && blacklist_domains!=1

/******************************************************************************/
	/*
	//anzahl adressen die auf einmal in ein array gepackt und importiert werden sollen, abhaengig vom Speicher fuer PHP.
	$import_mem=calc_bytes(ini_get("memory_limit"));
	$import_limit_run=500;//default limit adressen im array pro durchgang

	if ($import_mem >= (8*1024*1024)) { //8M
		$import_limit_run=1000;
	}
	if ($import_mem >= (16*1024*1024)) {
		$import_limit_run=2000;
	}
	if ($import_mem >= (24*1024*1024)) {
		$import_limit_run=3000;
	}
	if ($import_mem >= (32*1024*1024)) {
		$import_limit_run=4000;
	}
	if ($import_mem >= (48*1024*1024)) {
		$import_limit_run=6000;
	}
	if ($import_mem >= (64*1024*1024)) {
		$import_limit_run=8000;
	}
	if (tm_DEBUG()) $IMPORT_MESSAGE.="<br>import_mem=$import_mem Byte";
	if (tm_DEBUG()) $IMPORT_MESSAGE.="<br>import_limit_run=$import_limit_run";
	*/
/******************************************************************************/

	$author=$LOGIN->USER['name'];
	$code=0;
	$lines=0;//zaehler zeilen gesamt
	$lines_b=0;//zaehler zeilen bulk
	$lines_f=0;//zaehler zeilen aus datei

	$ifail=0;
/******************************************************************************/
//Bulk Import
/******************************************************************************/
	if (!empty($bulk)) {
		//jede zeile eine email...
	  	$IMPORT_MESSAGE.= tm_message_notice(___("Importiere E-Mail-Adressen aus Textfeld"));
		$lines_bulk = explode("\n", $bulk);
		$lc=count($lines_bulk);
		for ($lcc=0; $lcc<$lc; $lcc++) {
			$row=$lines_bulk[$lcc];
			#$fields=explode($delimiter, $row);
	    	//bugged, use function splitWithEscape ($str, $delimiterChar = ',', $escapeChar = '"')  instead!
			$fields=splitWithEscape($row, $delimiter,'"');//escape char is "
			if (isset($fields[0]) && !empty($fields[0])) {
		    	$field_0=str_replace("\"","",trim($fields[0]));
		    	$check_mail=checkEmailAdr($field_0,$EMailcheck_Import);
				if ($check_mail[0]) {
					$addr[$lines]['email']=$field_0;
					if (isset($fields[1]) && !empty($fields[1])) {
						$addr[$lines]['f0']=str_replace("\"","",trim($fields[1]));
					} else {
						$addr[$lines]['f0']="";
					}
					if (isset($fields[2]) && !empty($fields[2])) {
						$addr[$lines]['f1']=str_replace("\"","",trim($fields[2]));
					} else {
						$addr[$lines]['f1']="";
					}
					if (isset($fields[3]) && !empty($fields[3])) {
						$addr[$lines]['f2']=str_replace("\"","",trim($fields[3]));
					} else {
						$addr[$lines]['f2']="";
					}
					if (isset($fields[4]) && !empty($fields[4])) {
						$addr[$lines]['f3']=str_replace("\"","",trim($fields[4]));
					} else {
						$addr[$lines]['f3']="";
					}
					if (isset($fields[5]) && !empty($fields[5])) {
						$addr[$lines]['f4']=str_replace("\"","",trim($fields[5]));
					} else {
						$addr[$lines]['f4']="";
					}
					if (isset($fields[6]) && !empty($fields[6])) {
						$addr[$lines]['f5']=str_replace("\"","",trim($fields[6]));
					} else {
						$addr[$lines]['f5']="";
					}
					if (isset($fields[7]) && !empty($fields[7])) {
						$addr[$lines]['f6']=str_replace("\"","",trim($fields[7]));
					} else {
						$addr[$lines]['f6']="";
					}
					if (isset($fields[8]) && !empty($fields[8])) {
						$addr[$lines]['f7']=str_replace("\"","",trim($fields[8]));
					} else {
						$addr[$lines]['f7']="";
					}
					if (isset($fields[9]) && !empty($fields[9])) {
						$addr[$lines]['f8']=str_replace("\"","",trim($fields[9]));
					} else {
						$addr[$lines]['f8']="";
					}
					if (isset($fields[10]) && !empty($fields[10])) {
						$addr[$lines]['f9']=str_replace("\"","",trim($fields[10]));
					} else {
					   	$addr[$lines]['f9']="";
					}
				} else {//checkemail
					$IMPORT_LOG.=tm_message_warning(sprintf(___("Bulk Zeile %s: E-Mail %s hat ein falsches Format."),($lines+1),"'".$fields[0]."'")." ".$check_mail[1]);
					$ifail++;
				}//check email
			}//isset fields[0]
	    	$lines++;
			$lines_b++;
		}
		unset($fields);
	     $IMPORT_MESSAGE.=tm_message_success(sprintf(___("%s Einträge gefunden!"),$lines_b));
	}//!empty bulk

/******************************************************************************/
//CSV Datei einlesen:
/******************************************************************************/
	if ($check && file_exists($tm_datapath."/".$CSV_Filename)) {
		$uf=fopen($tm_datapath."/".$CSV_Filename,"r");
		if ($uf) {
		  	$IMPORT_MESSAGE.= tm_message_notice(sprintf(___("Importiere Datei %s"),"'".$CSV_Filename."'"));
		  	$IMPORT_MESSAGE.= tm_message_notice(___("Offset")." ".$import_offset_user);
		  	$IMPORT_MESSAGE.= tm_message_notice(___("Limit")." ".$import_limit_user);
		  	$lines_tmp=0;
			//offset
			if (tm_DEBUG()) $IMPORT_MESSAGE.=tm_message_debug("import_offset_user=".$import_offset_user);
			if ($import_offset_user) {
				if (tm_DEBUG()) $IMPORT_MESSAGE.=tm_message_debug("Jump to row ".($import_offset_user+1).": ");
				//zeilen auslesen und vergessen
			  	while(!feof($uf) && $lines_tmp < $import_offset_user) {
				  	$tmp=fgets($uf);//, 4096
				  	#unset($tmp);
					#if (tm_DEBUG()) $_MAIN_MESSAGE.=$lines_tmp.".";
					$lines_tmp++;
			  	}//while
			  	unset($tmp);
			}//import offset user
			//zeilen auslesen bis limit erreicht
			while(!feof($uf) && $lines_f < $import_limit_user) {
				$row=fgets($uf, 4096);
		    	#$fields=explode($delimiter, $row);
		    	//bugged, use function splitWithEscape ($str, $delimiterChar = ',', $escapeChar = '"')  instead!
				$fields=splitWithEscape($row, $delimiter,'"');//escape char is "
				//erstes feld, emil, muss gefuellt sein!
				//adr in array speichern
		    	if (isset($fields[0]) && !empty($fields[0])) {
			    	$field_0=str_replace("\"","",trim($fields[0]));
			    	$check_mail=checkEmailAdr($field_0,$EMailcheck_Import);
					if ($check_mail[0]) {
					      $addr[$lines]['email']=$field_0;
					      if (isset($fields[1]) && !empty($fields[1])) {
						      $addr[$lines]['f0']=str_replace("\"","",trim($fields[1]));
					      } else {
						      $addr[$lines]['f0']="";
					      }
					      if (isset($fields[2]) && !empty($fields[2])) {
						      $addr[$lines]['f1']=str_replace("\"","",trim($fields[2]));
					      } else {
						      $addr[$lines]['f1']="";
					      }
					      if (isset($fields[3]) && !empty($fields[3])) {
				  		      $addr[$lines]['f2']=str_replace("\"","",trim($fields[3]));
					      } else {
						      $addr[$lines]['f2']="";
					      }
					      if (isset($fields[4]) && !empty($fields[4])) {
				  		      $addr[$lines]['f3']=str_replace("\"","",trim($fields[4]));
					      } else {
						      $addr[$lines]['f3']="";
					      }
					      if (isset($fields[5]) && !empty($fields[5])) {
				  		      $addr[$lines]['f4']=str_replace("\"","",trim($fields[5]));
					      } else {
						      $addr[$lines]['f4']="";
					      }
					      if (isset($fields[6]) && !empty($fields[6])) {
				  		      $addr[$lines]['f5']=str_replace("\"","",trim($fields[6]));
					      } else {
						      $addr[$lines]['f5']="";
					      }
					      if (isset($fields[7]) && !empty($fields[7])) {
				  		      $addr[$lines]['f6']=str_replace("\"","",trim($fields[7]));
					      } else {
						      $addr[$lines]['f6']="";
					      }
					      if (isset($fields[8]) && !empty($fields[8])) {
				  		      $addr[$lines]['f7']=str_replace("\"","",trim($fields[8]));
					      } else {
						      $addr[$lines]['f7']="";
					      }
					      if (isset($fields[9]) && !empty($fields[9])) {
				  		      $addr[$lines]['f8']=str_replace("\"","",trim($fields[9]));
					      } else {
						      $addr[$lines]['f8']="";
					      }
					      if (isset($fields[10]) && !empty($fields[10])) {
				  		      $addr[$lines]['f9']=str_replace("\"","",trim($fields[10]));
					      } else {
						      $addr[$lines]['f9']="";
					      }
					} else {//check email
						$IMPORT_LOG.=tm_message_warning(sprintf(___("Datei Zeile %s: E-Mail %s hat ein falsches Format."),($lines+1),"'".$fields[0]."'")." ".$check_mail[1]);
						$ifail++;
					}//check email
				}//isset fields[0]
				$lines++;
				$lines_f++;
			}//while
			unset($fields);
			$IMPORT_MESSAGE.=tm_message_success(sprintf(___("%s Einträge gefunden!"),($lines_f-1)));
	    } else {//if uf, fopen
			$IMPORT_MESSAGE.= tm_message_error(sprintf(___("Die Import-Datei %s konnte nicht geöffnet werden."),$tm_datapath."/".$CSV_Filename));
		}
	}//check && file exists

//neue addressen anlegen
	//wenn min. 1 adresse gefudnen wurde//lines=anzahl adressen
	if ($lines>0) { //!empty($adr_grp) && // check nicht pruefen, sonst werden keine bulkadressen aus dem textfeld importiert
		$iok=0;
		#$ifail=0;//oben!!! vor dem einlesen, da email check schon beim einlesen
		$idouble=0;
		$iskipdouble=0;		
		$idelete=0;
		$iblacklist=0;
		
		#proof?!
		/*
		if ($C[0]['proof']==1) {
				if ($QP[$qpcc]['proof']==1) {
				$ADDRESS->proof();
			}
		}	
		*/
		
		srand((double)microtime()*1000000);#aus der schleife rausgenommen
		for ($i=0;$i<$lines;$i++) {
			$code=rand(111111,999999);
			//eintragen des datensatzes
			if (isset($addr[$i]['email'])) {
				//email auf gültigkeit prüfen
					$new_adr_grp=$adr_grp; //default
					$adr_exists=false;
					//adressen auf dubletten pruefen?
					//auch pruefen wenn delete, da adressen anhand id oder email gefunden werden sollen
					//achtung, gruppen zusammenfuehren geht natuerlich nur mit dublettencheck!! ;)
					if ($check_double == 1 || $delete==1) {
						//dublettencheck
						$search['email']=$addr[$i]['email'];
						$search['email_exact_match']=true;
						//auf existenz pruefen und wenn email noch nicht existiert dann eintragen.
						//	function getAdr($id=0,$offset=0,$limit=0,$group_id=0,$search=Array(),$sortIndex="",$sortType=0,$Details=1) {
						$ADR=$ADDRESS->getAdr(0,0,0,0,$search,"","",0);
						$ac=count($ADR);//anzahl gefundener adressen
						//wenn adr gefunden und existierende nicht ueberspungen werden sollen						
						if ($ac>0 && $skip_existing!=1) {
							//gruppen zusammenfuehren, nur wenn nicht geloescht werden soll
							if ($merge_groups==1 && $delete !=1) {
								//wir diffen die gruppen und fuegen nur die referenzen hinzu die noch nicht existieren!
								//$check=false;
								//gruppen denen die adr bereits  angehoert
								$old_adr_grp = $ADDRESS->getGroupID(0,$ADR[0]['id'],0);//alte gruppen
								//neue gruppen nur die die neu sind, denen die adr noch nicht angehoert!
								//adr_grp=gruppen aus dem formular
								
								//old:
								#$new_adr_grp = array_diff($adr_grp,$old_adr_grp);//nur neue gruppen
								#$all_adr_grp = array_merge($old_adr_grp, $new_adr_grp);//alte+neue gruppen zusammenfuegen
								//next we should use method mergeGroups
								$all_adr_grp=$ADDRESS->mergeGroups($adr_grp,$old_adr_grp);//testing!
							} else {// merge groups
									$all_adr_grp=$new_adr_grp;//gruppe aus formular uebernehmen, ueberschreiben!
							}//merge
						}//ac>0 && $skip_existing!=1
						if ($ac>0) {
							$adr_exists=true;//adresse existiert
						}
					}//check_double
					//////////////////////
					//oh! adresse ist bereits vorhanden!
					if ($adr_exists) {
					//wenn adresse existiert,
							if ($delete!=1 && $skip_existing==1) {
								$IMPORT_LOG.=tm_message_notice(sprintf(___("Zeile %s: E-Mail %s existiert Bereits und wird übersprungen."),($import_offset_user+$i+1),"'".$addr[$i]['email']."'"));
								$iskipdouble++;
							}

						//und nicht loeschen... und nicht ubespringen:
						if ($delete!=1 && $skip_existing!=1) {
							//adressdaten updaten!
							$code=$ADR[0]['code'];//code
							if ($aktiv_existing==-1) {
								$aktiv_update=$ADR[0]['aktiv'];//aktiv übernehmen
							} else {
								$aktiv_update=$aktiv_existing;//aktiv updaten
							}
							//adresse aktualisieren
							$ADDRESS->updateAdr(Array(
								"id"=>$ADR[0]['id'],
								"email"=>$addr[$i]['email'],
								"aktiv"=>$aktiv_update,
								"created"=>$created,
								"author"=>$author,
								"memo"=>"import update: ".$ADR[0]['memo'],
								"f0"=>$addr[$i]['f0'],
								"f1"=>$addr[$i]['f1'],
								"f2"=>$addr[$i]['f2'],
								"f3"=>$addr[$i]['f3'],
								"f4"=>$addr[$i]['f4'],
								"f5"=>$addr[$i]['f5'],
								"f6"=>$addr[$i]['f6'],
								"f7"=>$addr[$i]['f7'],
								"f8"=>$addr[$i]['f8'],
								"f9"=>$addr[$i]['f9']
								),
								$all_adr_grp);
								if ($mark_recheck==1) {
									$ADDRESS->markRecheck($ADR[0]['id'],1);
								}
								$IMPORT_LOG.=tm_message_notice(sprintf(___("Zeile %s: E-Mail %s existiert Bereits und wurde aktualisiert und ggf. in neue Gruppen eingetragen."),($import_offset_user+$i+1),"'".$addr[$i]['email']."'"));
							//wenn status_ex >0 dann aendern! status fuer bestehende adressen
							if ($status_ex>0) {
								$ADDRESS->setStatus($ADR[0]['id'],$status_ex);
							}
							//und neue referenzen zu neuen gruppen hinzufügen
							//$ADDRESS->addRef($ADR[0]['id'],$new_adr_grp);
							// ^^^ nur fuer den fall das daten nicht geupdated werden!!! sondern nur referenzen hinzugefuegt!
							//optional nachzuruesten und in den settings einstellbar :)
							// ok: merge
							$idouble++;
						} // delete != 1

						//importierte Adressen loeschen?
						if ($delete==1) {
							if (!tm_DEMO()) $ADDRESS->delAdr($ADR[0]['id']);
							$IMPORT_LOG.=tm_message_notice(sprintf(___("Zeile %s: E-Mail %s wurde gelöscht."),($i+1),"'".$addr[$i]['email']."'"));
							$idelete++;
						}

					}//adr_exists true

					if ($blacklist==1) {
							if (!$BLACKLIST->isBlacklisted($addr[$i]['email'],"email",0)) {//only_active=0, also alle, nicht nur aktive, was default waere
								$BLACKLIST->addBL(Array(
										"siteid"=>TM_SITEID,
										"expr"=>$addr[$i]['email'],
										"aktiv"=>1,
										"type"=>"email"
										));
								$IMPORT_LOG.=tm_message_notice(sprintf(___("Zeile %s: E-Mail %s wurde zur Blacklist hinzugefügt."),($i+1),"'".$addr[$i]['email']."'"));
								$iblacklist++;
							} else {
								$IMPORT_LOG.=tm_message_notice(sprintf(___("Zeile %s: E-Mail %s ist bereits in der Blacklist vorhanden."),($i+1),"'".$addr[$i]['email']."'"));
							}
					}
					if ($delete==1 && !$adr_exists) { // not exists
						$IMPORT_LOG.=tm_message_warning(sprintf(___("Zeile %s: E-Mail %s existiert nicht."),($i+1),"'".$addr[$i]['email']."'"));
					}
					if (!$adr_exists) { // not exists
						//nur importieren und neu eintragen wenn auch ene gruppe gewaehlt wurde, sonst enstehen datenleichen ohne gruppe! das waere sinnlos!
						//und nur einfuegen wenn nicht geloescht werden soll, ne, is klar!
						if ($delete != 1 && !empty($adr_grp)) {
							//wenn adresse noch nicht existiert , neu anlegen
							$new_adr_id=$ADDRESS->addAdr(Array(
								"email"=>$addr[$i]['email'],
								"aktiv"=>$aktiv_new,
								"created"=>$created,
								"author"=>$author,
								"status"=>$status_new,
								"code"=>$code,
								"memo"=>"import",
								"source"=>"import",
								"source_id"=>$LOGIN->USER['id'],
								"source_extern_id"=>0,
								"f0"=>$addr[$i]['f0'],
								"f1"=>$addr[$i]['f1'],
								"f2"=>$addr[$i]['f2'],
								"f3"=>$addr[$i]['f3'],
								"f4"=>$addr[$i]['f4'],
								"f5"=>$addr[$i]['f5'],
								"f6"=>$addr[$i]['f6'],
								"f7"=>$addr[$i]['f7'],
								"f8"=>$addr[$i]['f8'],
								"f9"=>$addr[$i]['f9']
								),
								$new_adr_grp);
								if ($mark_recheck==1) {
									$ADDRESS->markRecheck($new_adr_id,1);
								}
								$IMPORT_LOG.=tm_message_success(sprintf(___("Zeile %s: E-Mail %s  wurde hinzugefügt und in gewählten Gruppen eingetragen."),($import_offset_user+$i+1),"'".$addr[$i]['email']."'"));
							$iok++;
						} // ! delete
					}//adr exists false

					//importierte Adressen loeschen?
					/*
					if ($delete==1) {
						if (!tm_DEMO()) $ADDRESS->delAdr($ADR[0]['id']);
						$IMPORT_LOG.="<br>".sprintf(___("Zeile %s: E-Mail %s wurde gelöscht."),($i+1),"<em>".$addr[$i]['email']."</em>");
						$idelete++;
					}
					*/
				/*
				//^^^prüfen schon beim einlesen
				} else {//emailcheck
					$IMPORT_LOG.="<br>".sprintf(___("Zeile %s: E-Mail %s hat ein falsches Format."),($i+1),"<em>".$addr[$i]['email']."</em>");
					$ifail++;
				*/
				#}//emailcheck// prüfen schon beim einlesen!
				
				//domains der liste hinzufuegen fuer blacklisting der domains
				if ($blacklist_domains==1) {
					$bl_domains[$i]=getDomainFromEMail($addr[$i]['email']);
					
				}

			}//isset email
		}//for

		//adressen vergessen
		unset ($addr);

		if ($blacklist_domains==1) {
			$bl_domains=array_unique($bl_domains);//unify!
			foreach ($bl_domains as $bl_domainname) {
				//dublettencheck! per getBL
				if (!empty($bl_domainname)) {
					$BL=$BLACKLIST->getBL(0,Array("type"=>"domain","expr"=>$bl_domainname));
					//wenn nix gefunden, eintragen:
					if (count($BL)<1) {
						$BLACKLIST->addBL(Array(
										"siteid"=>TM_SITEID,
										"expr"=>$bl_domainname,
										"aktiv"=>1,
										"type"=>"domain"
										));
						$IMPORT_MESSAGE.=tm_message_success(sprintf(___("Die Domain %s wurde in die Blacklist eingetragen."),$bl_domainname));
					} else {
						$IMPORT_MESSAGE.=tm_message_notice(sprintf(___("Die Domain %s ist bereits in der Blacklist vorhanden."),$bl_domainname));
					}//if count<1
				}//!empty
			}//foreach bl_domains as bl_domain
		
		
			$IMPORT_MESSAGE.=tm_message_success(sprintf(___("%s Domains wurden zur Blacklist hinzgefügt."),count($bl_domains)));
		}

		if ($blacklist==1) {
			$IMPORT_MESSAGE.= tm_message_success(sprintf(___("Es wurden %s von %s Einträgen in die Blacklist eingefügt."),$iblacklist,($i)));//i-1
		}
		if ($delete==1) {
			$IMPORT_MESSAGE.= tm_message_success(sprintf(___("Es wurden %s von %s Einträgen gelöscht."),$idelete,($i)));//i-1
		} else {
			$IMPORT_MESSAGE.= tm_message_success(sprintf(___("Es wurden %s von %s Einträgen importiert und in die gewählten Gruppen eingetragen."),$iok,$i));//i-1
			$IMPORT_MESSAGE.= tm_message_warning(sprintf(___("%s Einträge waren Fehlerhaft und wurden NICHT importiert."),$ifail));
			
			if ($skip_existing!=1) {
				$IMPORT_MESSAGE.= tm_message_success(sprintf(___("%s Eintraege sind bereits vorhanden und wurden aktualisiert."),$idouble));
			} else {
				$IMPORT_MESSAGE.= tm_message_notice(sprintf(___("%s Eintraege sind bereits vorhanden und wurden übersprungen."),$iskipdouble));//
			}

		}

		$IMPORT_MESSAGE.= tm_message_notice(sprintf(___("Bearbeitungszeit: %s Sekunden"),number_format($T->MidResult(), 2, ',', '')));
		$action="adr_import";

		//import messages vor log einfuegen
		$IMPORT_LOG=$IMPORT_MESSAGE.$IMPORT_LOG;
		//logdatei schreiben:
		$logfilename="import_".date_convert_to_string($created).".html";
		//$IMPORT_LOG nach text convertieren
	    #$htmlToText=new Html2Text($IMPORT_LOG, 1024);
	    #$IMPORT_LOG=$htmlToText->convert();
		//logdatei speichern
		write_file($tm_logpath,$logfilename,$IMPORT_LOG);
		$IMPORT_MESSAGE.= tm_message_success(___("Logdatei für Import wurde gespeichert unter:").$tm_logdir."/".$logfilename);
		$IMPORT_MESSAGE.=" <a href=\"".$tm_URL_FE."/".$tm_logdir."/".$logfilename."\" target=\"_preview\">".$tm_logdir."/".$logfilename."</a>";

	} else {//if lines>0
	}
} else {
}
$_MAIN_MESSAGE.=$IMPORT_MESSAGE;

$group_new_name="";
require_once (TM_INCLUDEPATH_GUI."/adr_import_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/adr_import_form_show.inc.php");
?>