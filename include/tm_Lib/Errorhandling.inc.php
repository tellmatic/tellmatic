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

//ERRORHANDLING
// we will do our own error handling

error_reporting(1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

function userErrorHandler ($errno, $errmsg, $filename, $linenum, $vars) {
	global $tm_logpath,$_ERROR;
	if (!isset($_ERROR)) {
		$_ERROR=Array();
		$_ERROR['text']="";
		$_ERROR['html']="";
	}
    $err_date = date("Y-m-d H:i:s (T)");
    /*

		value	 constant
		1	E_ERROR
		2	E_WARNING
		4	E_PARSE
		8	E_NOTICE
		16	E_CORE_ERROR
		32	E_CORE_WARNING
		64	E_COMPILE_ERROR
		128	E_COMPILE_WARNING
		256	E_USER_ERROR
		512	E_USER_WARNING
		1024	E_USER_NOTICE
		6143	E_ALL
		2048	E_STRICT
		4096	E_RECOVERABLE_ERROR

    */

    $errortype = array (
                1   =>  "Error",
                2   =>  "Warning",
                4   =>  "Parsing Error",
                8   =>  "Notice",
                16  =>  "Core Error",
                32  =>  "Core Warning",
                64  =>  "Compile Error",
                128 =>  "Compile Warning",
                256 =>  "User Error",
                512 =>  "User Warning",
                1024=>  "User Notice",
				2048=>	"Strict",
				4096=>	"Recoverable Error"
                );
    $user_errors = array( E_ALL, E_USER_NOTICE, E_ERROR,  E_PARSE, E_USER_ERROR, E_USER_WARNING, E_RECOVERABLE_ERROR,E_STRICT,E_WARNING, E_NOTICE );
    //,E_STRICT,E_WARNING, E_NOTICE

/*
    $err = "<errorentry>\n";
    $err .= "\t<datetime>".$dt."</datetime>\n";
    $err .= "\t<errornum>".$errno."</errornum>\n";
    $err .= "\t<errortype>".$errortype[$errno]."</errortype>\n";
    $err .= "\t<errormsg>".$errmsg."</errormsg>\n";
    $err .= "\t<scriptname>".$filename."</scriptname>\n";
    $err .= "\t<scriptlinenum>".$linenum."</scriptlinenum>\n";
    if (in_array($errno, $user_errors)) {
        $err .= "\t<vartrace>".serialize($vars,"Variables")."</vartrace>\n";
    }
    $err .= "</errorentry>\n\n";
*/
   $err="";
   $err_html="";
    if (in_array($errno, $user_errors)) {
	    #$err .= "";
	    $err .= "errdate:".$err_date." |;| ";
		$err .= "errno:".$errno." |;| ";
		$err .= "errtype:".$errortype[$errno]." |;| ";
		$err .= "errmsg:\"".$errmsg."\" |;| ";
		$err .= "errfile:".$filename." |;| ";
		$err .= "errline:".$linenum." |;| ";
		//vorsicht: macht viel mist :) alle template variablen werden ausgegeben :O   //$err .= "vars:".serialize($vars)."|;|";
		$err .="\n";
		$_ERROR['text'].=$err;

	  	$err_html .= "\n<br><font size=-1 color=red>";
	  	$err_html .= "\n".$err_date."";
		$err_html .= "\n PHP Error ".$errno."/".$errortype[$errno]."";
		$err_html .= "\n \"<em>".$errmsg."</em>\"";
		$err_html .= "\n in File: <em>".basename($filename)."</em>";
		#$err_html .= "\n in File: <em>".basename($filename,".inc.php")."</em>";
		$err_html .= "\n&nbsp;at Line: <em>".$linenum."</em>";
		//$err_html .= "vars:".serialize($vars)."";
		$err_html .="</font><br>\n";
		$_ERROR['html'].=$err_html;
     }
	//Error in Datei loggen
	error_log($_ERROR['text'], 3, TM_PHP_LOGFILE);
	#debug_print_backtrace();
	#chmod (TM_PHP_LOGFILE, 0664);
}
?>