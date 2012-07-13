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
$_MAIN_DESCR=___("Dateiverwaltung (Alternativen)");
$_MAIN_MESSAGE.="";

if (tm_DEMO()) $_MAIN_OUTPUT.=tm_message_warning(___("Demo! Dateimanager deaktiviert."));

		$_MAIN_OUTPUT .= "<table border=0 width=\"100%\" cellpadding=1 cellspacing=1>";
		$_MAIN_OUTPUT .= "<thead>";
		$_MAIN_OUTPUT .= "<tr><td align=\"left\" width=20>&nbsp;</td><td align=\"left\">&nbsp;</td><td align=\"left\" width=20>&nbsp;</td><td align=\"left\" width=20>&nbsp;</td></tr>";
		$_MAIN_OUTPUT .= "</thead>";
		$_MAIN_OUTPUT .= "<tbody>";


foreach ($filemanager as $filemanager_alternative => $filemanager_alt) {
		//fm exists?
		$filemanager[$filemanager_alternative]['installed']=(file_exists(TM_INCLUDEPATH_LIB_EXT."/".$filemanager[$filemanager_alternative]['dir']."/".$filemanager[$filemanager_alternative]['file'])) ? TRUE:FALSE;

		//fm not installed
		if (!$filemanager[$filemanager_alternative]['installed']) {
			$_MAIN_OUTPUT .= "<tr><td align=\"left\">";
			//icon not installed
			$_MAIN_OUTPUT.= tm_icon("cancel.png",sprintf(___("%s ist nicht installiert."),$filemanager[$filemanager_alternative]['name']));	
			$_MAIN_OUTPUT .= "</td><td align=\"left\" colspan=3>";
			$_MAIN_OUTPUT.="".sprintf(___("%s ist nicht installiert."),$filemanager[$filemanager_alternative]['name']);
			$_MAIN_OUTPUT.="<br>".sprintf(___("%s muss nach %s installiert werden."),$filemanager[$filemanager_alternative]['name'],"'<em>".TM_INCLUDEDIR_LIB_EXT."/".$filemanager[$filemanager_alternative]['dir']."</em>'");
			if (!tm_DEMO()) $_MAIN_OUTPUT.="<br>'<em>".TM_INCLUDEPATH_LIB_EXT."/".$filemanager[$filemanager_alternative]['dir']."</em>'";
			$_MAIN_OUTPUT.="<br>".$filemanager[$filemanager_alternative]['url']."";
			$_MAIN_OUTPUT .= "<br><br></td></tr>";
		}//not installed
		
		//installed
		if ($filemanager[$filemanager_alternative]['installed']) {
			$_MAIN_OUTPUT .= "<tr><td align=\"left\">";
			//icon ok
			$_MAIN_OUTPUT.= tm_icon("tick.png",sprintf(___("%s ist installiert."),$filemanager[$filemanager_alternative]['name']));	
			$_MAIN_OUTPUT .= "</td><td align=\"left\">";
			$_MAIN_OUTPUT.= display($filemanager[$filemanager_alternative]['name']);
			$_MAIN_OUTPUT .= "</td><td align=\"left\">";
			$_MAIN_OUTPUT .= "&nbsp;";//in iframe oeffnen, 2do
			$_MAIN_OUTPUT .= "</td><td align=\"left\">";
			//in neuem fenster oeffnen
			if (!tm_DEMO()) $_MAIN_OUTPUT .= "<a href=\"".$tm_URL_FE."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_LIB_EXT."/".$filemanager[$filemanager_alternative]['dir']."/".$filemanager[$filemanager_alternative]['file']."\" target=\"fileman_alt\">".tm_icon("arrow_right.png",sprintf(___("%s in neuem Fenster/TAB Öffnen"),$filemanager[$filemanager_alternative]['name']))."</a>";//in neuem fenster oeffnen
			$_MAIN_OUTPUT .= "<br><br></td></tr>";
		}//installed
}//foreach

$_MAIN_OUTPUT .= "</tbody>";
$_MAIN_OUTPUT .= "</table>";
$_MAIN_OUTPUT .= "<br><br>";



		/*
		$_MAIN_DESCR=___("Dateiverwaltung")." - ".$filemanager[$filemanager_alternative]['name'];
		$_MAIN_MESSAGE.="";

			if (!tm_DEMO()) 	$_MAIN_OUTPUT.='
				<iframe src="'.$tm_URL_FE.'/'.TM_INCLUDEDIR.'/'.$filemanager[$filemanager_alternative]['dir'].'/'.$filemanager[$filemanager_alternative]['file'].'" align="middle" name="_filemanager" scrolling="no" marginheight="0px" marginwidth="0px" height="600" width="728" style="border:1px dashed grey; -moz-border-radius:2em 2em 2em 2em; padding: 4px"></iframe>
				';
		*/


?>