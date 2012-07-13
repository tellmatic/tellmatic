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
/**/
/**/
/**/
/**/
/**/
/**/
/**/
/**/
	define("TM_DISCLAIMER",'Disclaimer! The Coder(s) of Tellmatic is/are not responsible for any content in this Newsletter! Tellmatic is only the software used to create and send this mail to your email address! Tellmatic is free and Open Source Software! We are not responsible for misuse of Tellmatic sending Spam! Unfortunately sometimes Spammers use Tellmatic. I/we do not support Spam in any way.');
	
//Header and Footer that gets added to each Newsletter:
//{TITLE} and {TITLE_SUB} are replaces by actual NL Title und Sub-Title!
//in the default shown here i mixed up quotes and double quotes ;-) almost to add ne lines and format the source, and to not have to escape doublequotes for doctype etc.
			$TM_NL_HTML_Start='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'.
												"\n<html>\n<head>\n".
												'<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">'.
												"\n".
												'<meta name="application" content="{TM_APPTEXT}">'.
												"\n".												
												'<meta name="tellmatic-version" content="{TM_VERSION}">'.
												"\n".
												'<title>{SUBJECT} - {TITLE}-{TITLE_SUB}</title>'.
												"\n".
												'</head>'.
												"\n".
												'<body>';
			$TM_NL_HTML_End="\n".
												'<br><div style="font-size:7pt; color:#dddddd;">Newsletter created with {TM_APPTEXT}'.
												'<br>'.
												TM_DISCLAIMER.
												'</div>'.
												"\n".
												'</body>'.
												"\n".
												'</html>';
/**/
/**/
/**/
/**/
/**/
/**/
/**/
/**/
/******************************DO NOT CHANGE - or only if you exactly know what you are doing ;-) :**********************************/
/**/
/**/
/**/
/**/
/**/
/**/
/**/
/**/
if (!isset($tm_config)) {
    $tm_config=false;
}

if (!$tm_config) {

	/***********************************************************/
	//Dateformat for Newsletters to parse {DATE}
	/***********************************************************/
	define ("TM_NL_DATEFORMAT","%d.%m.%Y");
	//changed to use strftime: http://php.net/manual/de/function.strftime.php
	//http://pubs.opengroup.org/onlinepubs/007908799/xsh/strftime.html
	//pre 1090rc2 was date()
	
	/***********************************************************/
	//demomode und debugmode
	/***********************************************************/
	define ("TM_DEMO", FALSE);//use demo mode, enable globally regardless of usersettings
	define ("TM_DEMO_FE", FALSE);//use demo mode in frontend
	/***********************************************************/
	//use PHPIDS
	/***********************************************************/
	define ("TM_PHPIDS", FALSE);//use php ids, sorry, please do not enable, makes more trouble than it helps
	/***********************************************************/
	//debugmode, can be false and set by admin for specific user, if true here, its globally enabled
	/***********************************************************/
	define ("TM_DEBUG", FALSE);//printout useful debug infos, enable globally regardless of usersettings
	define ("TM_DEBUG_FE", FALSE);//printout useful debug infos in frontend/backendfiles , used if global debug is disabled and not logged in

	define ("TM_DEBUG_LANG", FALSE);//printout useful translation strings infos
	define ("TM_DEBUG_LANG_FE", FALSE);//printout useful translation strings infos in frontend
	define ("TM_DEBUG_LANG_LEVEL", 0);// 1: mark only words, 2: only list 3: 1+2
	
	//this debug settings can not be set by user, set globally here
	define ("TM_DEBUG_SQL", FALSE);//Warning! this will print out all sql queries!!!
	define ("TM_DEBUG_SMTP", FALSE);//Warning! this will print out all smtp messages and communication with server

	/***********************************************************/
	//logging
	/***********************************************************/
	define ("TM_LOG", TRUE);//use logbook

	/***********************************************************/
	//use temporary html files?
	/***********************************************************/
	define ("USE_TMP_HTML_FILES", TRUE);//save output to temporary html files in admin/tmp
	//this is useful if you want to prevent reloading a page with new values! 

	/***********************************************************/
	//defne globals for Newsletter html head and foot from variables defined by user at top of file!
	/***********************************************************/
	define ("TM_NL_HTML_START", $TM_NL_HTML_Start);
	define ("TM_NL_HTML_END", $TM_NL_HTML_End);
	
	/***********************************************************/
	//siteid
	//siteid wird aber zukuenftig variabel sein, bzw default-siteid vergeben werden.
	/***********************************************************/
	//siteid //achtung, siteid aendern heisst komplett neuer datenstamm... es muessen auch user angelegt werden.... oehm... nicht aendern!!!
	define ("TM_SITEID","tellmatic");

	/***********************************************************/
	//old httpd auth login +php-cgi
	//not needed for tellmatic >1.0.8, changed to use sessions!
	/***********************************************************/
	//old login via http-auth via php had problems on misconfigured php-cgi installations
	//uncomment the next line if your php is running as cgi and http authentication does not work:
	#list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
	/*
	and  create a textfile named '.htaccess' in your tellmatic install-directory:
	<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]
	</IfModule>
	*/
	// !! mod_rewrite must be enabled !!
	//not tested! source/from: http://www.besthostratings.com/articles/http-auth-php-cgi.html

/***********************************************************/
//STOP
// ab hier nichts aendern!!! // DO NOT CHANGE
/***********************************************************/
	/*	
	$called_via_url=FALSE;
	if (isset($_SERVER['REMOTE_ADDR'])) {
		$called_via_url=TRUE;
	}
	*/
	//hmm, use short form
	$called_via_url = (isset($_SERVER['REMOTE_ADDR'])) ? TRUE : FALSE;

	$_MAIN_MESSAGE="";
	$_MAIN_HELP="";
	$_MAIN_OUTPUT="";
	$_MENU="";

/***********************************************************/
//check if php runs on windows!
/***********************************************************/
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {  $php_windows=true; } else {  $php_windows=false; }
	define("PHPWIN",$php_windows);

/***********************************************************/
//
/***********************************************************/
	$trans = get_html_translation_table(HTML_ENTITIES);

/***********************************************************/
//define db crecedentials
/***********************************************************/
//same in install_conf_sql!
define ("TM_DB_NAME",$tm["DB"]["Name"]);
define ("TM_DB_PORT",$tm["DB"]["Port"]);
if ($tm["DB"]["Socket"]==1) {
	define ("TM_DB_HOST", $tm["DB"]["Host"]);
} else {
	define ("TM_DB_HOST", $tm["DB"]["Host"].":".TM_DB_PORT);
}
define ("TM_DB_USER",$tm["DB"]["User"]);
define ("TM_DB_PASS",$tm["DB"]["Pass"]);
unset($tm["DB"]);


/***********************************************************/
//verzeichnisse
/***********************************************************/
	define("TM_ADMINDIR","admin");
	define("TM_ADMINDIR_TMP","admin/tmp");
	//includefiles
	define("TM_INCLUDEDIR","include");
	define("TM_INCLUDEDIR_LIB","tm_Lib");
	define("TM_INCLUDEDIR_LIB_EXT","tm_Lib_Ext");
	define("TM_INCLUDEDIR_CLASS","tm_Class");
	define("TM_INCLUDEDIR_BE","tm_Backend");//backend include dir: send_it, check_it, bounce_it etc
	define("TM_INCLUDEDIR_FE","tm_Frontend");//frontend include dir: subscribe, view, unsubscribe, click etc
	define("TM_INCLUDEDIR_GUI","tm_Gui");//GUI include dir
	define("TM_INCLUDEDIR_INSTALL","tm_Install");
	//verzeichnis templates
	define("TM_TPLDIR","tpl");
	//verzeichnis fuer bilder:
	define("TM_IMGDIR","img");
	//verzeichnis fuer icons
	define("TM_ICONDIR","img/icons");
	//verzeichnis fuer docs
	define("TM_DOCDIR","doc");
	//verzeichnis fuer files
	define("TM_FILEDIR","files");
/***********************************************************/
//pfade
/***********************************************************/
	//tmpath
	define("TM_PATH",TM_DOCROOT."/".TM_DIR);
	define("TM_ADMINPATH",TM_PATH."/".TM_ADMINDIR);
	define("TM_ADMINPATH_TMP",TM_PATH."/".TM_ADMINDIR_TMP);//temporary html files for admin gui
	//includes
	define("TM_INCLUDEPATH",TM_PATH."/".TM_INCLUDEDIR);
	define("TM_INCLUDEPATH_LIB",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_LIB);
	define("TM_INCLUDEPATH_LIB_EXT",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_LIB_EXT);//external libs, smtp, mime, phpdomparser, etc
	define("TM_INCLUDEPATH_CLASS",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_CLASS);//Tellmatic ClassFiles
	define("TM_INCLUDEPATH_BE",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_BE);//backend include dir: send_it, check_it, bounce_it etc
	define("TM_INCLUDEPATH_FE",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_FE);//frontend include dir: subscribe, view, unsubscribe, click etc
	define("TM_INCLUDEPATH_GUI",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_GUI);//frontend include dir: subscribe, view, unsubscribe, click etc
	define("TM_INCLUDEPATH_INSTALL",TM_PATH."/".TM_INCLUDEDIR."/".TM_INCLUDEDIR_INSTALL);//install files
	//pfad zur docu
	define("TM_DOCPATH",TM_PATH."/".TM_DOCDIR);
	//pfad zu bildern, interne
	define("TM_IMGPATH",TM_PATH."/".TM_IMGDIR);
	//icons intern
	define("TM_ICONPATH",TM_PATH."/".TM_ICONDIR);
	//templates intern
	define("TM_TPLPATH",TM_PATH."/".TM_TPLDIR);
	//files
	define("TM_FILEPATH",TM_PATH."/".TM_FILEDIR);


/***********************************************************/
//hacks for php5
/***********************************************************/
	if (version_compare(phpversion(), "5.0", ">=")) {   $usePhp5=TRUE; } else {  $usePhp5=FALSE; }
	define("PHP5",$usePhp5);

	//object copy wrapper function, needed to switch from php4 to 5
	function tmObjCopy($O) {
		if (PHP5) {
			#require (TM_INCLUDEPATH_LIB."/PHP5_clone.inc.php");//$obj=clone $O;
			$obj=clone $O;
			return $obj;
		} else {
			return $O;
		}
	}

/***********************************************************/
//sitespezifische dirs....! fuer multicustomer
/***********************************************************/
	//verzeichnis fuer gespeicherte newsletter:
	$tm_nldir=TM_FILEDIR."/newsletter";
	//verzeichnis fuer gespeicherte newsletter-bilder unterhalb von $tm_nldir:
	$tm_nlimgdir=TM_FILEDIR."/newsletter/images";
	//verzeichnis fuer attachements
	$tm_nlattachdir=TM_FILEDIR."/attachements";
	//verzeichnis fuer formular-templates
	$tm_formdir=TM_FILEDIR."/forms";
	//verzeichnis fuer import/export dateien
	$tm_datadir=TM_FILEDIR."/import_export";
	//verzeichnis fuer send logs
	$tm_logdir=TM_FILEDIR."/log";
	//sessions, tmp
	$tm_tmpdir=TM_FILEDIR."/tmp";
	//reports, statistic images
	$tm_reportdir=TM_FILEDIR."/reports";

/***********************************************************/
//pfade
/***********************************************************/
	//newsletter html files
	$tm_nlpath=TM_PATH."/".$tm_nldir;
	//pfad verzeichnis fuer gespeicherte newsletter-bilder unterhalb von $tm_nldir:
	$tm_nlimgpath=TM_PATH."/".$tm_nlimgdir;
	//attachements
	$tm_nlattachpath=TM_PATH."/".$tm_nlattachdir;
	//pfad verzeichnis fuer formular-templates
	$tm_formpath=TM_PATH."/".$tm_formdir;
	//pfad verzeichnis fuer import/export dateien
	$tm_datapath=TM_PATH."/".$tm_datadir;
	//pfad f. logfiles
	$tm_logpath=TM_PATH."/".$tm_logdir;
	//pfad zu reports
	$tm_reportpath=TM_PATH."/".$tm_reportdir;
	//temp, sessions etc
	$tm_tmppath=TM_PATH."/".$tm_tmpdir;

	define ("TM_PHP_LOGFILE", $tm_logpath."/tellmatic_php_error.log");


/***********************************************************/
//URLs
/***********************************************************/
	$tm_URL="/".TM_DIR."/".TM_ADMINDIR."";//backend, admin //TM_DOMAIN.
	$tm_URL_FE=TM_DOMAIN."/".TM_DIR;//frontend!
	$tm_imgURL=$tm_URL_FE."/".TM_IMGDIR;
	$tm_iconURL=$tm_URL_FE."/".TM_ICONDIR;
	/***********************************************************/
	//check if we should use 256 colors png icons instead, for internet exploder.....
	/***********************************************************/
	if (file_exists(TM_INCLUDEPATH."/256")) {
		define ("USE_256COL_ICONS",TRUE);
		$tm_iconURL.="/256";
	}else {
		define ("USE_256COL_ICONS",FALSE);
	}
/***********************************************************/
//Today
//todays date//used e.g. for saving status and statistic images and reports etc
/***********************************************************/
	define ("TM_TODAY",date("Y-m-d"));//todays date//used e.g. for saving status and statistic images and reports etc

/***********************************************************/
//tabellen
/***********************************************************/
	//DBS Tabellen, Namen und Prefix!
	if (!empty($tm_tablePrefix)) {
		$tm_tablePrefix.="_";
	}
	define ("TM_TABLE_CONFIG", $tm_tablePrefix."config");
	define ("TM_TABLE_USER", $tm_tablePrefix."user");
	define ("TM_TABLE_LOG", $tm_tablePrefix."log");
	define ("TM_TABLE_NL", $tm_tablePrefix."nl");
	define ("TM_TABLE_NL_GRP", $tm_tablePrefix."nl_grp");
	define ("TM_TABLE_ADR", $tm_tablePrefix."adr");
	define ("TM_TABLE_ADR_DETAILS", $tm_tablePrefix."adr_details");
	define ("TM_TABLE_ADR_GRP", $tm_tablePrefix."adr_grp");
	define ("TM_TABLE_ADR_GRP_REF", $tm_tablePrefix."adr_grp_ref");
	define ("TM_TABLE_NL_Q", $tm_tablePrefix."nl_q");
	define ("TM_TABLE_NL_H", $tm_tablePrefix."nl_h");
	define ("TM_TABLE_NL_ATTM", $tm_tablePrefix."nl_attm");
	define ("TM_TABLE_FRM", $tm_tablePrefix."frm");
	define ("TM_TABLE_FRM_GRP_REF", $tm_tablePrefix."frm_grp_ref");
	define ("TM_TABLE_FRM_S", $tm_tablePrefix."frm_s");
	define ("TM_TABLE_HOST", $tm_tablePrefix."hosts");
	define ("TM_TABLE_BLACKLIST", $tm_tablePrefix."blacklist");
	define ("TM_TABLE_LNK", $tm_tablePrefix."lnk");
	define ("TM_TABLE_LNK_GRP", $tm_tablePrefix."lnk_grp");
	define ("TM_TABLE_LNK_GRP_REF", $tm_tablePrefix."lnk_grp_ref");
	define ("TM_TABLE_LNK_CLICK", $tm_tablePrefix."lnk_click");

/***********************************************************/
//includes: funktionen, klassen
/***********************************************************/
/***********************************************************/
//funktionen
/***********************************************************/
	require_once (TM_INCLUDEPATH_LIB."/Functions.inc.php");

/******************************************/
//php ids
/******************************************/
	#phpids sucks a lot, we need explicitely define the include dir, base path has no effect! ;P bad style, doesnt really work as explained, lacks documentation, example is just a fake. needs too much tweaking.
	if (TM_PHPIDS) require_once (TM_INCLUDEPATH_LIB."/PHPIDS.inc.php");
	
/***********************************************************/
//handle magic quotes
/***********************************************************/
	//http://talks.php.net/show/php-best-practices/26
	if (get_magic_quotes_gpc()) {
		//http://www.php.net/manual/en/security.magicquotes.disabling.php#id2553906
	    $_GET = undoMagicQuotes($_GET);
	    $_POST = undoMagicQuotes($_POST);
	    $_COOKIE = undoMagicQuotes($_COOKIE);
	    $_REQUEST = undoMagicQuotes($_REQUEST);
	}

	//http://www.php.net/manual/en/function.htmlentities.php#77556
	foreach($_POST as $key => $val) {
	  // scrubbing the field NAME...
	  if(preg_match('/%/', urlencode($key))) die('XSS');//'FATAL::XSS hack attempt detected. Your IP has been logged.'
	}

/***********************************************************/
//Errorhandler:
/***********************************************************/
	require_once (TM_INCLUDEPATH_LIB."/Errorhandling.inc.php");
	//eigene errorhandler funktion
	set_error_handler("userErrorHandler");

/***********************************************************/
//htmlparser, convert html to text
/***********************************************************/
	require_once (TM_INCLUDEPATH_LIB_EXT."/phphtmlparser/html2text.inc");

/***********************************************************/
//simple html dom, http://simplehtmldom.sourceforge.net/
/***********************************************************/
	require_once (TM_INCLUDEPATH_LIB_EXT."/simplehtmldom/simple_html_dom.php");

/***********************************************************/
//array mit verfuegbaren sprachen
/***********************************************************/
	$LANGUAGES=Array(	'lang' => Array('de','en','es','fr','it','nl','pt'),
										'text' => Array('Deutsch','English','Espana','France','Italiano','Dutch','Portuguese (BR)'),
									);
	$supported_locales = $LANGUAGES['lang'];//array('en', 'de');
	//^^^ lang array auch in install_conf einfuegen, hardcoded!

/***********************************************************/
//eigene gettext emulation:
/***********************************************************/
	require_once(TM_INCLUDEPATH_LIB."/GetText.inc.php");

/***********************************************************/
//klassen
/***********************************************************/
	require_once (TM_INCLUDEPATH_CLASS."/Classes.inc.php");
	#require_once (TM_INCLUDEPATH."/phphtmlparser/html2text.inc");
	//wird bisher nur beim versenden in send_it.php benoetigt, und deshalb auch nur dort eingebunden!

/***********************************************************/
//config aus db holen
/***********************************************************/
	$CONFIG=new tm_CFG();
	$C=$CONFIG->getCFG(TM_SITEID);
	//eMail prueflevel!
	$EMailcheck_Intern=$C[0]['emailcheck_intern'];
	$EMailcheck_Subscribe=$C[0]['emailcheck_subscribe'];
	$EMailcheck_Sendit=$C[0]['emailcheck_sendit'];
	$EMailcheck_Checkit=$C[0]['emailcheck_checkit'];

/***********************************************************/
//set locales
/***********************************************************/
	define("DEFAULT_LOCALE", $C[0]['lang']);

/***********************************************************/
//SMTP/POP3 Config
//default, f. interne mails
/***********************************************************/
	$use_SMTPmail=true;//fuer interne smtp mail function! sendMail()
	//Benutze POP before SMTP zur Authentifizierung? NEIN!
	$SMTPPopB4SMTP=false;

/***********************************************************/
//mindestlänge für passwoerter
/***********************************************************/
	$minlength_pw=6;

/***********************************************************/
//eMail prueflevel!
/***********************************************************/
	$EMAILCHECK['intern'][0]="keine Prüfung";
	$EMAILCHECK['intern'][1]="Syntax";
	$EMAILCHECK['intern'][2]="Syntax + MX/DNS";
	$EMAILCHECK['intern'][3]="Syntax, MX/DNS + Validate";

	$EMAILCHECK['subscribe'][1]="Syntax";
	$EMAILCHECK['subscribe'][2]="Syntax + MX/DNS";
	$EMAILCHECK['subscribe'][3]="Syntax, MX/DNS + Validate";

	$EMAILCHECK['sendit'][1]="Syntax";
	$EMAILCHECK['sendit'][2]="Syntax + MX/DNS";
	$EMAILCHECK['sendit'][3]="Syntax, MX/DNS + Validate";

	$EMAILCHECK['checkit'][1]="Syntax";
	$EMAILCHECK['checkit'][2]="Syntax + MX/DNS";
	$EMAILCHECK['checkit'][3]="Syntax, MX/DNS + Validate";

/***********************************************************/
//Tellmatic Name/Version
/***********************************************************/
	require_once (TM_INCLUDEPATH_LIB."/tm_version.inc.php");

/***********************************************************/

	//Sprache festlegen! wird unten erneut aufgerufen!! wenn eingeloggt.
	require (TM_INCLUDEPATH_LIB."/Lang.inc.php");
	
/***********************************************************/
//Messages, f. subscribe/unsubscribe
/***********************************************************/

#require_once(TM_INCLUDEPATH."/Messages.inc.php");
//no more needed, language for unsubscrbe/login is now from default language >= tm 1090rc2
// set default lang in admin -> settings

/***********************************************************/
	if (tm_DEBUG()) $debug_translated=Array();
	if (tm_DEBUG()) $debug_not_translated=Array();
	if (tm_DEBUG()) $debug_same_translated=Array();

/***********************************************************/
//encoding
/***********************************************************/
	$encoding = "UTF-8";

/***********************************************************/
//configured
/***********************************************************/
	$tm_config=true;
}
?>