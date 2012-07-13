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

$_MAIN_OUTPUT.="\n\n<!-- bounce.inc -->\n\n";

$_MAIN_DESCR=___("Bounce Management");
$_MAIN_MESSAGE.="";

$HOSTS=new tm_HOST();
$set=getVar("set");
/*do not connect by default
if (empty($set)) {
	$set="connect";
}
*/
$InputName_Limit="limit";
$$InputName_Limit=getVar($InputName_Limit);
if ($$InputName_Limit <1 || empty($$InputName_Limit)) {
	$$InputName_Limit=10;
}

$InputName_Offset="offset";
$$InputName_Offset=getVar($InputName_Offset);
if ($$InputName_Offset <0 || empty($$InputName_Offset)) {
	$$InputName_Offset=0;
}

//export
$InputName_Export="export";//
$$InputName_Export=getVar($InputName_Export);

$InputName_Bounce="bounce";//
$$InputName_Bounce=getVar($InputName_Bounce,1);
//
$InputName_BounceType="bounce_type";//
$$InputName_BounceType=getVar($InputName_BounceType,1);
//
$InputName_FilterTo="filter_to";//
$$InputName_FilterTo=getVar($InputName_FilterTo,1);
//
$InputName_Host="host";//
$$InputName_Host=getVar($InputName_Host,0);

$InputName_FilterToSMTPReturnPath="filter_to_smtp_return_path";//
$$InputName_FilterToSMTPReturnPath=getVar($InputName_FilterToSMTPReturnPath);

$InputName_Mail="mailno";//
pt_register("POST","mailno");
if (!isset($mailno)) {
	$mailno=Array();
}

$InputName_Adr="adr";//
pt_register("POST","adr");
if (!isset($adr)) {
	$adr=Array();
}

$InputName_Action="val";
$$InputName_Action=getVar($InputName_Action);
if (empty($val)) {
	$val="list";
}

$InputName_ActionAdr="val2";
$$InputName_ActionAdr=getVar($InputName_ActionAdr);

//if ($val!="filter" && $val!="filter_delete") {
	require_once (TM_INCLUDEPATH_GUI."/bounce_host_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/bounce_host_form_show.inc.php");
//}
//server ausgewaehlt, wir connecten
if ($set=="connect") {

	$Mailer=new tm_Mail();
	$Bounce=new tm_Bounce();
	$ADDRESS=new tm_ADR();
	$HOST=$HOSTS->getHost($host);

	$search_mail=Array();
	//filter? emails suchen?
	if ($filter_to==1) {
		//nur mails an aktuelle return adesse fuer host
		$search_mail['to']=$filter_to_smtp_return_path;
		$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Es werden nur E-Mails an die Adresse %s angezeigt."),$filter_to_smtp_return_path));
	}

	#$_MAIN_OUTPUT .= "<br>".sprintf(___("Verbindung zum Server %s wird aufgebaut..."),$HOST[0]['name']." (".$HOST[0]['host'].":".$HOST[0]['port'].")");
	$_MAIN_OUTPUT .= tm_message_notice(sprintf(___("Verbindung zum Server %s wird aufgebaut..."),$HOST[0]['name']." (".$HOST[0]['host'].":".$HOST[0]['port']."/".$HOST[0]['type'].")"));
	//	function connect($Host, $User, $Pass,$type="pop3",$port="110",$options="novalidate-cert")  {
	#$Mailer->Connect($SMTPHost, $SMTPUser, $SMTPPasswd,"pop3",110,"");
	#$Mailer->Connect($SMTPHost, $SMTPUser, $SMTPPasswd);
	
	$Mailer->Connect($HOST[0]['host'], $HOST[0]['user'], $HOST[0]['pass'],$HOST[0]['type'],$HOST[0]['port'],$HOST[0]['options']);

	//$folders = imap_listmailbox ($Mailer->mbox, "{".$Mailer->Srv."}INBOX", "*");
	if (!empty($Mailer->Error)) {
		$_MAIN_MESSAGE .= tm_message_error(sprintf(___("Servermeldung: %s"),$Mailer->Error));
	}

	//Mails auslesen
	$Mail=$Mailer->getMail(0,$offset,$limit,$search_mail);
	//typ
	$checkHeader=0;
	$checkBody=0;
	if ($bounce_type=="header") {
		$_MAIN_OUTPUT .= tm_message_notice(___("E-Mail-Header wird nach potentiellen Adressen durchsucht"));
		$checkHeader=1;
	}
	if ($bounce_type=="body") {
		$_MAIN_OUTPUT .= tm_message_notice(___("E-Mail-Body wird nach potentiellen Adressen durchsucht"));
		$checkBody=1;
	}
	if ($bounce_type=="headerbody") { 
		$_MAIN_OUTPUT .= tm_message_notice(___("E-Mail-Header und Body werden nach potentiellen Adressen durchsucht"));
		$checkHeader=1;
		$checkBody=1;
	}
	
	//Liste der Mails anzeigen
	if ($val=="list") {
		require_once (TM_INCLUDEPATH_GUI."/bounce_mail_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/bounce_mail_list.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/bounce_mail_form_show.inc.php");
	}
	if ($val=="filter" || $val=="filter_delete") {
		$mc=count($mailno);
		if ($mc>0) {
			require_once (TM_INCLUDEPATH_GUI."/bounce_filter_form.inc.php");//formularokpf und felder
			#include_once (TM_INCLUDEPATH_GUI."/bounce_filter_list.inc.php");//liste der durchsuchten emails, ausgelagert und zusammengefasst in list!
			require_once (TM_INCLUDEPATH_GUI."/bounce_filter_adr_list.inc.php");//liste der aressen mit checkboxen
			require_once (TM_INCLUDEPATH_GUI."/bounce_filter_form_show.inc.php");//render formular! aktion waehlen etc
		} else {
			$_MAIN_MESSAGE.= tm_message_warning(___("Es wurden keine Mails zum Bearbeiten ausgewählt."));
			$val="list";
		}
	}


	if ($val=="delete" || $val=="filter_delete") {
		$mc=count($mailno);
		if ($mc>0) {
			$_MAIN_MESSAGE .= "<br>".___("Lösche Mail.");
			for ($mcc=0;$mcc<$mc;$mcc++) {
				$_MAIN_MESSAGE .= "".$mailno[$mcc]." ";
				if (!tm_DEMO()) $Mailer->delete($mailno[$mcc]);
			}
			//mailbox aufraeumen
			$_MAIN_MESSAGE .= tm_message_success(___("Mailbox aufräumen, als gelöscht markierte Mails wurden entfernt."));
			$Mailer->expunge();
			//todo: reconnect! damit bekommen wir aktuelle servermeldungen etc.
			//Mails neu auslesen
			$Mail=$Mailer->getMail(0,$offset,$limit,$search_mail);
		} else {
			$_MAIN_MESSAGE.= tm_message_warning(___("Es wurden keine Mails zum Löschen ausgewählt."));
			$val="list";
		}
		//nur bei delete die liste wieder anzeigen, bei filter oder filter_delete setzen wir es hinterher im hiddenfield auf list, bzw zeigen vorher noch das adressformular an!
		if ($val=="delete") {
			$val="list";
		}
	}
	//val2==..... aktionen fuer die adressen
	if (!empty($val2)) {
		$ac=count($adr);
		if ($val2 == "none") {
			$_MAIN_MESSAGE.= tm_message_notice(___("Keine Aktion."));
		}
		if ($export==1) {
			$_MAIN_MESSAGE.= tm_message_notice(___("Adressen werden exportiert."));
			//ausgabedatei:
			//standard name aus datum fuer export generieren
			$created=date("Y-m-d H:i:s");
			//default:
			$Export_Filename="bounce_".date_convert_to_string($created).".csv";
			$fp = fopen($tm_datapath."/".$Export_Filename,"w");
		}
		if ($ac>0) {
			$_MAIN_MESSAGE.= tm_message_notice(sprintf(___("%s Adressen zum Bearbeiten ausgewählt."),$ac));

			for ($acc=0;$acc<$ac;$acc++) {
				$search['email']=$adr[$acc];
				$search['email_exact_match']=true;
		//	function getAdr($id=0,$offset=0,$limit=0,$group_id=0,$search=Array(),$sortIndex="",$sortType=0,$Details=1) {
				if ($export==1) {
					$CSV="\"".$adr[$acc]."\"\n";
					fputs($fp,$CSV,strlen($CSV));
				}
				$A=$ADDRESS->getAdr(0,0,0,0,$search,"",0,0);
				if (isset($A[0]['id'])) {

					if ($val2 == "delete") {
						if (!tm_DEMO()) $ADDRESS->delAdr($A[0]['id']);
						$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Die Adresse %s wurde gelöscht."),$A[0]['email']));
					}

					if ($val2 == "error") {
						$ADDRESS->setStatus($A[0]['id'],9);
						$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Die Adresse %s wurde als Fehlerhaft markiert."),$A[0]['email']));
					}

					if ($val2 == "aktiv") {
						$ADDRESS->setAktiv($A[0]['id'],0);
						$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Die Adresse %s wurde deaktiviert."),$A[0]['email']));
					}

					if ($val2 == "unsubscribe") {
						$ADDRESS->unsubscribe($A[0]['id'],"Bounce");
						$ADDRESS->setAktiv($A[0]['id'],0);
						$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Die Adresse %s wurde abgemeldet und deaktivert."),$A[0]['email']));
					}
					if ($val2 == "auto") {
						$_MAIN_MESSAGE.= tm_message_success($A[0]['email'].": ");
						//wenn erros noch unter dem limit...
						//if ($A[0]['errors'] <= $max_mails_retry) {
						//$C[0]['max_mails_retry']
							//fehler zaehlen
							$ADDRESS->setAError($A[0]['id'],($A[0]['errors']+1));
							$_MAIN_MESSAGE.= tm_message(" -- ".sprintf(___("Fehler: %s von max. %s"),($A[0]['errors']+1),$C[0]['max_mails_retry']));

							//wenn adresse noch nicht abgemeldet!!!!!
							if ($A[0]['status']!=11) {
								//wenn erros das limit ueberschritten hat:
								if (($A[0]['errors']+1) > $C[0]['max_mails_retry'])  {
									//unsubscribe und deaktivieren
									$ADDRESS->setStatus($A[0]['id'],9);
									$ADDRESS->setAktiv($A[0]['id'],0);
									$_MAIN_MESSAGE.= tm_message(" -- ".sprintf(___("Als fehlerhaft markiert und deaktivert (Sendefehler >%s)"),$C[0]['max_mails_retry']));
								} else {
									//wenn errors limit noch nicht ueberschritten
									//dann als sendefehler markieren
									//das auch nur wenn status nicht warten ist.
									//oben ist status warten ok, da der fehler gezaehlt wird, kommen so und so viele bouncemails wegen der optin mail, so wird er deaktiviert etc und als fehelrhaft markiert
									if ($A[0]['status']!=5) {
										$ADDRESS->setStatus($A[0]['id'],10);
									}
								}
							} else {
								$_MAIN_MESSAGE.= tm_message(" -- ".___("ist bereits abgemeldet (unsubscribed)."));// und wurde geloescht
								//if (!tm_DEMO()) $ADDRESS->delAdr($A[0]['id']);
							}// if status !=11
						//} // else {}
					}
				} else { //isset A
					$_MAIN_MESSAGE.= tm_message_warning(sprintf(___("%s ist nicht bekannt."),$adr[$acc]));
				}//isset A
			}//for $acc

			if ($export==1) {
				fclose($fp);
				$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Datei gespeichert unter: %s"),$tm_datadir."/".$Export_Filename));
				$_MAIN_MESSAGE.="<a href=\"".$tm_URL_FE."/".$tm_datadir."/".$Export_Filename."\" target=\"_preview\">".$tm_datadir."/".$Export_Filename."</a>";
			}

		} else { //$ac>0
			$_MAIN_MESSAGE.= tm_message_notice(___("Es wurden keine Adressen zum Bearbeiten ausgewählt."));
		}//$ac>0
	}//if !empty val2
	//verbindung schliessen
	$Mailer->disconnect();
} else {
	//require_once (TM_INCLUDEPATH_GUI."/bounce_host_form.inc.php");
	//require_once (TM_INCLUDEPATH_GUI."/bounce_host_form_show.inc.php");
}
?>