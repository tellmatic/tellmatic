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
		if (!isset($search_log['object'])) {
			$search_log['object']="";
		}
		$LogbookURLPara->addParam("s_obj",$search_log['object']);
		$LogbookURLPara_=$LogbookURLPara->getAllParams();
		$_MAIN_OUTPUT.= "<div class=\"link_logbook_section\">";
		if (!isset($search_log['object'])) {		
			$_MAIN_OUTPUT.= "<br><b>...oops,no obj for log in $act</strong><br>";
			$_MAIN_MESSAGE.= "<br><b>...oops,no obj for log in $act</strong><br>";
		}
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$LogbookURLPara_."\" title=\"".___("Logbuch anzeigen")."\">".tm_icon("script.png",___("Logbuch anzeigen"))."&nbsp;".___("Logbuch anzeigen")."&nbsp;(".$logcount.")</a>";
		$_MAIN_OUTPUT.= "</div>";
	}
}

	//show last loglines
	if (tm_DEBUG() && $user_is_admin) {
		$_MAIN_MESSAGE.="<br><div class=\"log_summary\">";
		$search_log['limit']=10;
		$search_log['sort_type']=1;
		$_MAIN_MESSAGE.=tm_icon("script.png",___("Logbuch"),___("Logbuch"))."&nbsp;<b>".sprintf(___("Die letzen %s Logbucheinträge"),$search_log['limit']).":</b>";
		$LOGS=new tm_LOG();
		$LOG=$LOGS->get(0,$search_log);
		$_MAIN_MESSAGE.="<table style=\"width:100%; border:1px solid #cccccc\">";
		$_MAIN_MESSAGE.="<tbody>";
		foreach ($LOG as $logentry) {
			$_MAIN_MESSAGE.="<tr>";
			$_MAIN_MESSAGE.="<td>";
			$_MAIN_MESSAGE.=display($logentry['date']);
			$_MAIN_MESSAGE.="</td>";
			$_MAIN_MESSAGE.="<td>";
			$_MAIN_MESSAGE.=display($LOGIN->getUserName($logentry['author_id']));
			$_MAIN_MESSAGE.="</td>";
			$_MAIN_MESSAGE.="<td>";
			$_MAIN_MESSAGE.=display($logentry['object']);
			$_MAIN_MESSAGE.="</td>";
			$_MAIN_MESSAGE.="<td>";
			$_MAIN_MESSAGE.=display($logentry['action']);
			$_MAIN_MESSAGE.="</td>";
			$_MAIN_MESSAGE.="</tr>";
		}
		$_MAIN_MESSAGE.="</tbody>";
		$_MAIN_MESSAGE.="</table>";
		$_MAIN_MESSAGE.="</div>";
	}

?>