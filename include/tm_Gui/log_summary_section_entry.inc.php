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

//link zumlogbuch f. sektion
//$search_log['object']="adr"; <<--- should be set in section include file!
if ($user_is_admin) {
	$LOGSC=new tm_LOG();
	$logcount=$LOGSC->count($search_log);		
	if ($logcount>0) {
		$LogbookURLPara=tmObjCopy($mSTDURL);
		$LogbookURLPara->addParam("act","log_list");
		$LogbookURLPara->addParam("set","search");
		$LogbookURLPara->addParam("s_obj",$search_log['object']);
		$LogbookURLPara->addParam("s_edit_id",$search_log['edit_id']);
		$LogbookURLPara_=$LogbookURLPara->getAllParams();
		$_MAIN_OUTPUT.= "&nbsp;<a href=\"".$tm_URL."/".$LogbookURLPara_."\" title=\"".___("Logbuch anzeigen")."\">".tm_icon("script.png",___("Logbuch anzeigen")." (".$logcount.")")."</a>";
	}
}
?>