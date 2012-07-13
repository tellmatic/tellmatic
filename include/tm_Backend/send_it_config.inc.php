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


ob_start();
/********************************************************************************/
#require_once(TM_INCLUDEPATH_CLASS."/Class_SMTP.inc.php");//moved to Classes.inc.php...
#require_once (TM_INCLUDEPATH_LIB_EXT."/phphtmlparser/html2text.inc");

/*
//$called_via_url moved to tm_lib
*/

//a http refresh may work
//2do: make reload intervall an option in config
$reload_intervall=300;

$QUEUE=new tm_Q();
$NEWSLETTER=new tm_NL();
$ADDRESS=new tm_ADR();
$HOSTS=new tm_HOST();
$BLACKLIST=new tm_BLACKLIST();
$T=new Timer();//zeitmessung

$skip_send=FALSE;//if true, skip sending routine// is true after new q has been prepared
$log_proc_id=rand(111111,999999);//give each run a unique id
$log_q_id="0";
$log_nl_id="0";
$log_grp_id="0";
$log_adr_id="0";
$log_msg="";

//set static nl vars! new :) 1090rc2
$nl_new_values=Array();

$_MAIN_MESSAGE="";

/***********************************************************/
//default logfile name, var will be overwritten with proper value if a q was found! 
/***********************************************************/
$logfilename_send_it_q="q.log.html";
send_log("default logfilename is ".$logfilename_send_it_q);

/***********************************************************/
//check if loaded via browser/url, output html header if true
/***********************************************************/
if ($called_via_url) {
	send_log("called_via_url = TRUE");
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$encoding."\">\n";
	echo "<html>\n<body bgcolor=\"#aabbcc\">\n";
	echo "<font size=2 color=\"#000000\"><pre>\n";
} else {
	send_log("called_via_url = FALSE");
}


send_log("set massmail false, 1090, no more massmails!!");
$massmail=false;
send_log("always personalized newsletter!");
$max_mails_bcc=1;
send_log("max_mails_bcc =1 = ".$max_mails_bcc);
$limitQ=1;//nur einen q eintrag bearbeiten
send_log("limitQ = ".$limitQ);


function send_log($text) {
	#update log on the fly
	global $tm_logpath;//make a constant!
	$profile_time=$GLOBALS['T']->MidResult();
	$timestamp=round(microtime(TRUE));//microtime(TRUE);
	$datetime=date("Y-m-d H:i:s");
	
	$log="";	
	$log.="[".$timestamp."][".$GLOBALS['log_proc_id']."],".$datetime.",q:".$GLOBALS['log_q_id'].",n:".$GLOBALS['log_nl_id'].",g:".$GLOBALS['log_grp_id'].",a:".$GLOBALS['log_adr_id']; 
	$log.=",time: ".number_format  ( $profile_time  , 4 , "."  , "");	
	$log.=",t: ".$text;
	$log.="\n";
	update_file($tm_logpath,$GLOBALS['logfilename_send_it_q'],$log);
	#output to browser or cron:
	
	#if ($GLOBALS['called_via_url']) echo $GLOBALS['logfilename_send_it_q']." : ";	
	echo $log;
	#if ($GLOBALS['called_via_url']) echo "<br>";

    // check that buffer is actually set before flushing
	if (ob_get_length()) {
		//http://www.php.net/manual/de/function.ob-end-flush.php
		flush();
		ob_end_flush();
		#ob_end_clean();		
		#$out=ob_get_contents() ;
		#ob_flush();
	}
	ob_start();
}
?>