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

if (!isset($tm_config_admin)) {
    $tm_config_admin=false;
}

if (!$tm_config_admin) {

/***********************************************************/
//alternative filemanagers
/***********************************************************/

	$filemanager['extplorer']['name']="eXtplorer";
	$filemanager['extplorer']['url']="http://extplorer.sourceforge.net/";
	$filemanager['extplorer']['dir']="filemanager_extplorer";
	$filemanager['extplorer']['file']="index.php";
	
	$filemanager['myftphp']['name']="MyFT.PHP";
	$filemanager['myftphp']['url']="https://sourceforge.net/projects/myftphp/";
	$filemanager['myftphp']['dir']="filemanager_myftphp";
	$filemanager['myftphp']['file']="myft.php";
	
	$filemanager['pxp']['name']="PxP";
	$filemanager['pxp']['url']="http://www.webxplorer.org/phpXplorer/www/";
	$filemanager['pxp']['dir']="filemanager_pxp";
	$filemanager['pxp']['file']="system/index.php";
	
	$filemanager['gt']['name']="GT Filemanager";
	$filemanager['gt']['url']="http://www.gerd-tentler.de/tools/filemanager/";
	$filemanager['gt']['dir']="filemanager_gt";
	$filemanager['gt']['file']="filemanager.php";

/***********************************************************/
//tail xx lines of logfile
/***********************************************************/
	define("TM_PHP_LOG_TAIL",FALSE);//show last TM_PHP_LOG_TAIL_LINES lines of TM_PHP_LOGFILE	
	define("TM_PHP_LOG_TAIL_LINES",3);

/***********************************************************/
//uploads
/***********************************************************/
	$allowed_html_filetypes = "(htm|html|txt)";
	$allowed_image_filetypes = "(jpg|jpeg)";
	$allowed_trackimage_filetypes = "(jpg|jpeg|png)";
	$allowed_csv_filetypes = "(csv|txt)";

	$max_upload_size=2048000;//2M
	if (tm_DEMO()) $max_upload_size=2048;//64byte

/***********************************************************/
//limits
/***********************************************************/
	$adr_row_limit=500;
	$available_mem=calc_bytes(ini_get("memory_limit"));

	if ($available_mem==0) {
		//we have unlimted memory, but set a limit here
		$adr_row_limit=5000;//5k is enough :) like having ~48mb of ram
	}

	if ($available_mem >= (8*1024*1024)) { //8M
		$adr_row_limit=750;
	}
	if ($available_mem >= (16*1024*1024)) {
		$adr_row_limit=1500;
	}
	if ($available_mem >= (24*1024*1024)) {
		$adr_row_limit=2500;
	}
	if ($available_mem >= (32*1024*1024)) {
		$adr_row_limit=4000;
	}
	if ($available_mem >= (48*1024*1024)) {
		$adr_row_limit=5000;
	}
	if ($available_mem >= (64*1024*1024)) {
		$adr_row_limit=7500;
	}
	if ($available_mem >= (128*1024*1024)) {
		$adr_row_limit=15000;
	}
	
	$max_execution_time=ini_get("max_execution_time");
	if ($max_execution_time==0) $max_execution_time=3600;

/***********************************************************/
//kleinkram
/***********************************************************/
	$row_bgcolor="#ffffff";
	$row_bgcolor2="#dddddd";
	$row_bgcolor_inactive="#ff9999";
	#$row_bgcolor_hilite="#bbd6ff";
	$row_bgcolor_hilite="#ffcc00";
	#$row_bgcolor_click="#ffcc00";
	$row_bgcolor_fail="#ffbbbb";
/***********************************************************/
//proof
/***********************************************************/

	define ("TM_PROOF",TRUE);
	define ("TM_PROOF_URL","http://proof.tellmatic.org");#get from db! this is default
	define ("TM_PROOF_TRIGGER",10);#min adr, trigger proofing, default, get from db, if adr amount to check exceeds this value. proofing gets active if enabled, 1 to proof even 1 adr
	define ("TM_PROOF_PC",10);#how many percents, default, get from db, how many percent of all adr should be proofed? set to 100 to enable for all
	
/***********************************************************/
//paths for fm sections
/***********************************************************/
$file_path['path']['nl']=TM_PATH."/".$tm_nldir;
$file_path['name']['nl']=___("Newsletter-Templates");
$file_path['path']['nl']=TM_PATH."/".$tm_nlimgdir;
$file_path['name']['nl']=___("Newsletter-Bilder");
$file_path['path']['attm']=TM_PATH."/".$tm_nlattachdir;
$file_path['name']['attm']=___("Newsletter-Anhänge");
$file_path['path']['frm']=TM_PATH."/".$tm_formdir;
$file_path['name']['frm']=___("Formular-Templates");

/***********************************************************/
$debug_lang_levels_arr[0]="--";
$debug_lang_levels_arr[1]=___("Wörter");
$debug_lang_levels_arr[2]=___("Liste");
$debug_lang_levels_arr[3]=___("Wörter und Liste")." 1+2";

/***********************************************************/
//php settings etc
/***********************************************************/
	#@ini_set("max_execution_time","0");
	@ini_set("ignore_user_abort","1");

/***********************************************************/
//sessions
/***********************************************************/
	//sessions
	define ("TM_SESSION_TIMEOUT", 360*60);//360 * 60 sek = 6h
	@ini_set("session.cookie_lifetime",TM_SESSION_TIMEOUT*10);
	@ini_set("session.use_cookies","1");
	@ini_set("session.use_only_cookies","1");

/***********************************************************/
//cache off
/***********************************************************/
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Datum in der Vergangenheit

/***********************************************************/
//send http header encoding
/***********************************************************/
	header("Content-type: text/html; charset=$encoding");
	
/***********************************************************/
//start gzip compression output if available
/***********************************************************/
	m_obstart();

/***********************************************************/
//configured
/***********************************************************/
	$tm_config_admin=true;
}
?>