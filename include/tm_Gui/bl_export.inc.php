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
//wird aus bl_list aufgerufen // set==export_blacklist
if ($user_is_manager && $set=="export_blacklist") {
	//ausgabedatei:
	//standard name aus datum fuer export generieren
	$created=date("Y-m-d H:i:s");
	//default:
	$Export_Filename="blacklist_".$type."_".date_convert_to_string($created)."";
	//extension .csv
	$Export_Filename=$Export_Filename.".txt";
	$BLACKLIST=new tm_BLACKLIST();
	$exported=0;
	//Exportdatei oeffnen
	$fp = fopen($tm_datapath."/".$Export_Filename,"w");
	if (!$fp) {
			 $_MAIN_MESSAGE.= tm_message_error(sprintf(___("Export-Datei kann nicht geöffnet werden %s"),"'".$Export_Filename."'"));
	} else {
			//anzahl exportierter eintraege:
			$exported=0;
			$BL=$BLACKLIST->getBL(0,Array("type"=>$type));
			$bc=count($BL);
			for ($bcc=0;$bcc<$bc;$bcc++) {
				$ENTRY="\"".$BL[$bcc]['expr']."\",\"".$BL[$bcc]['type']."\",".$BL[$bcc]['aktiv']."\n";
				//und in file schreiben:
				fputs($fp,$ENTRY,strlen($ENTRY));
				$exported++;
			}
			//close file
			fclose($fp);
			//chmod
			chmod ($tm_datapath."/".$Export_Filename, 0664);
	}//if $fp
	$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Es wurden insgesamt %s Einträge aus der Blacklist exportiert."),$exported));
	$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Datei gespeichert unter: %s"),$tm_datadir."/".$Export_Filename);
	$_MAIN_MESSAGE.= "<a href=\"".$tm_URL_FE."/".$tm_datadir."/".$Export_Filename."\" target=\"_preview\">".$tm_datadir."/".$Export_Filename."</a>";
}//export_blacklist==1
?>