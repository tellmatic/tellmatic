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

if (tm_DEMO()) exit;

$HOSTS=new tm_HOST();
$Mailer=new tm_Mail();
$Bounce=new tm_Bounce();
$ADDRESS=new tm_ADR();
#POP3/IMAP HostID frm global config
$HOST=$HOSTS->getHost($C[0]['bounceit_host']);

if (!isset($HOST[0])) exit;

$Mail=Array();
$BMail=Array();
$Bounces=Array();
$bcmatch=0;
$offset=0;

$search_mail=Array();
if ($C[0]['bounceit_filter_to']==1) {
	$search_mail['to']=$C[0]['bounceit_filter_to_email'];
	echo  sprintf(___("Es werden nur E-Mails an die Adresse %s durchsucht."),$C[0]['bounceit_filter_to_email'])."\n";
}

#serververbidung aufbauen
echo  sprintf(___("Verbindung zum Server %s wird aufgebaut..."),$HOST[0]['name']." (".$HOST[0]['host'].":".$HOST[0]['port']."/".$HOST[0]['type'].")")."\n";
$Mailer->Connect($HOST[0]['host'], $HOST[0]['user'], $HOST[0]['pass'],$HOST[0]['type'],$HOST[0]['port'],$HOST[0]['options']);
if (!empty($Mailer->Error)) {
	echo  sprintf(___("Servermeldung: %s"),"".$Mailer->Error."")."\n";
	exit;
}

//Mails auslesen
$Mail=$Mailer->getMail(0,$offset,$C[0]['bounceit_limit'],$search_mail);
$mc=count($Mail);
echo  sprintf(___("%s Mails gefunden"),$mc)."\n";

if ($mc < 1) exit;

if ($C[0]['bounceit_search']=="header" || $C[0]['bounceit_search']=="headerbody") {
	$checkHeader=1;
	echo  ___("E-Mail-Header wird nach potentiellen Adressen durchsucht")."\n";
}
if ($C[0]['bounceit_search']=="body" || $C[0]['bounceit_search']=="headerbody") {
	$checkBody=1;
	echo  ___("E-Mail-Body wird nach potentiellen Adressen durchsucht")."\n";
}

#adressen auslesen
for ($mcc=0;$mcc<$mc;$mcc++) {
	$BMail=$Bounce->filterBounces($Mailer->getMail($Mail[$mcc]['no']),$checkHeader,$checkBody);//$Messages , checkHeader=1, checkBody, returnOnlyBounces..., filter to:
	if (!empty($BMail[0]['bounce'])) {
		$Bounces = array_merge($Bounces,$BMail[0]['bounce']);
		$bcmatch++;
	}
	#mail loeschen und postfach aufraeumen	
	$Mailer->delete($Mail[$mcc]['no']);
}
#mail loeschen und postfach aufraeumen
$Mailer->expunge();

$bctotal=count($Bounces);
$Bounces=unify_array($Bounces);
$bc=count($Bounces);
echo sprintf(___("Es wurden %s Mails durchsucht."),$mc)."\n".
		sprintf(___("%s Mails ergaben einen Treffer."),$bcmatch)."\n".
		sprintf(___("Es wurden aus %s Adressen %s (eindeutige) potentiell fehlerhafte Adressen erkannt."),$bctotal,$bc)."\n";

if ($bc <1) exit;

#adressen bearbeiten
$created=date("Y-m-d H:i:s");
srand((double)microtime()*1000000);
$rcode=rand(11,99);
$Export_Filename="bounceit_".date_convert_to_string($created)."-".$rcode.".csv";
$BounceDel_Filename="bounceit-delete_".date_convert_to_string($created)."-".$rcode.".csv";
$fp = fopen($tm_datapath."/".$Export_Filename,"a");
$fpdel = fopen($tm_datapath."/".$BounceDel_Filename,"a");
$delimiter=",";
$CSV=$ADDRESS->genCSVHeader($delimiter);
if ($fp) {
	if (!tm_DEMO()) fputs($fp,$CSV,strlen($CSV));
}
if ($fpdel) {
	if (!tm_DEMO()) fputs($fpdel,$CSV,strlen($CSV));
}

echo  ___("Export").": ".$Export_Filename."\n";

$search['email_exact_match']=true;

for ($bcc=0;$bcc<$bc;$bcc++) {
	$search['email']=$Bounces[$bcc];
	//output to cvs quick hack
	//$CSV="\"".$Bounces[$bcc]."\"\n";
	//fputs($fp,$CSV,strlen($CSV));
	$A=$ADDRESS->getAdr(0,0,0,0,$search,"",0,0);
	//CSV Zeile erstellen:
	$CSV=$ADDRESS->genCSVline($A[0],$delimiter);
	//und in file schreiben:
	if (!tm_DEMO() && $fp) fputs($fp,$CSV,strlen($CSV));

	if (isset($A[0]['id'])) {
		$protocol="bounce_it.php: ";
		if ($C[0]['bounceit_action'] == "delete") {
			if (!tm_DEMO() && $fpdel) fputs($fpdel,$CSV,strlen($CSV));
			if (!tm_DEMO()) $ADDRESS->delAdr($A[0]['id']);
			$protocol.= sprintf(___("Die Adresse %s wurde gelöscht."),$A[0]['email'])."\n";
		}

		if ($C[0]['bounceit_action'] == "error") {
			$ADDRESS->setStatus($A[0]['id'],9);
			$protocol.= sprintf(___("Die Adresse %s wurde als Fehlerhaft markiert."),$A[0]['email'])."\n";
		}

		if ($C[0]['bounceit_action'] == "aktiv") {
			$ADDRESS->setAktiv($A[0]['id'],0);
			$protocol.= sprintf(___("Die Adresse %s wurde deaktiviert."),$A[0]['email'])."\n";
		}

		if ($C[0]['bounceit_action'] == "unsubscribe") {
			$ADDRESS->unsubscribe($A[0]['id'],"Bounce");
			$ADDRESS->setAktiv($A[0]['id'],0);
			$protocol.= sprintf(___("Die Adresse %s wurde abgemeldet und deaktivert."),$A[0]['email'])."\n";
		}
		if ($C[0]['bounceit_action'] == "auto") {
			$protocol.= $A[0]['email'].": ";
			//wenn erros noch unter dem limit...
			//if ($A[0]['errors'] <= $max_mails_retry) {
			//$C[0]['max_mails_retry']
				//fehler zaehlen
				$ADDRESS->setAError($A[0]['id'],($A[0]['errors']+1));
				$protocol.= " -- ".sprintf(___("Fehler: %s von max. %s"),($A[0]['errors']+1),$C[0]['max_mails_retry'])."\n";

				//wenn adresse noch nicht abgemeldet!!!!!
				if ($A[0]['status']!=11) {
					//wenn erros das limit ueberschritten hat:
					if (($A[0]['errors']+1) > $C[0]['max_mails_retry'])  {
						//unsubscribe und deaktivieren
						$ADDRESS->setStatus($A[0]['id'],9);
						$ADDRESS->setAktiv($A[0]['id'],0);
						$protocol.= " -- ".sprintf(___("Als fehlerhaft markiert und deaktivert (Sendefehler >%s)"),$C[0]['max_mails_retry'])."\n";
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
					$protocol.= " -- ".___("ist bereits abgemeldet (unsubscribed).")."\n";
				}// if status !=11
			//} // else {}
			if ($C[0]['bounceit_action'] != "delete") {
				$ADDRESS->addMemo($A[0]['id'],$protocol);
			}
			echo $protocol;
		}
	} else { //isset A
		echo sprintf(___("%s ist nicht bekannt."),$Bounces[$bcc])."\n";
	}//isset A
	flush();
	ob_flush();
}//for $bcc
echo "Logfiles: ".$BounceDel_Filename." / ".$Export_Filename;
fclose($fp);
fclose($fpdel);
?>