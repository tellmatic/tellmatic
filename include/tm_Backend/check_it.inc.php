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

$ADDRESS=new tm_ADR();
$search['recheck']=1;
$ADR=$ADDRESS->getAdr(0,0,$C[0]['checkit_limit'],0,$search,"",0,0);
$ac=count($ADR);
if ($ac>0) {
	echo sprintf(___("Limit: %s Found: %s"),$C[0]['checkit_limit'],$ac)."\n";
	for ($acc=0;$acc<$ac;$acc++) {
		$protocol="";
		$protocol.=$ADR[$acc]['email']."\n";
		$ADR_C=$ADDRESS->getAdr($ADR[$acc]['id']);
		//nochmal kurz pruefen ob recheck noch 1 ist, sonst pruefen wir ggf. doppelt	
		if ($ADR_C[0]['recheck']==1) {
			//recheck auf 0 setzen
			$ADDRESS->markRecheck($ADR[$acc]['id'],0);
			//pruefen		
			$check_mail=checkEmailAdr($ADR[$acc]['email'],$C[0]['emailcheck_checkit'],$C[0]['checkit_from_email']);
			if (!$check_mail[0]) {
				$protocol.=___("Error, e-mail marked as failed.")."\n";
				$protocol.=$check_mail[1]."\n";
				$ADDRESS->setStatus($ADR[$acc]['id'],9);#see Stats.inc.php
				$ADDRESS->addMemo($ADR[$acc]['id'],$protocol);
			} else {
				#opt.: als OK markieren wenn status 9 war, error a, oder 10, error s
				if ($ADR[$acc]['id']==9 || $ADR[$acc]['id']==10) {
					if ($C[0]['checkit_adr_reset_status']==1) {
						$ADDRESS->setStatus($ADR[$acc]['id'],2);#2:ok, checked, #see Stats.inc.php
						$protocol.=___("OK, e-mail marked as ok.")."\n";
					}
				} else {
					$protocol.=___("OK, but e-mail status not changed.")."\n";			
				}
	
				if ($C[0]['checkit_adr_reset_error']==1) {
					$ADDRESS->setAError($ADR[$acc]['id'],0);
					$protocol.=___("OK, reset errors to 0.")."\n";
				}
			}
		} else {
			$protocol.=___("Skipped, already processed")."\n";
		}
		$protocol.="----------------\n";
		echo $protocol;
		flush();
		ob_flush();
	}
	echo ___("Finish");
}
?>