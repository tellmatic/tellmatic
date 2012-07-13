<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        	*/
/* tellmatic, the newslettermachine                                             	*/
/* tellmatic, die Newslettermaschine                                            */
/* 2006/11 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

/***********************************************************/
//load header
/***********************************************************/
require_once(TM_INCLUDEPATH_INSTALL."/install_head.inc.php");

/***********************************************************/
//Message
/***********************************************************/
//headline
$MESSAGE="<h1>Tellmatic Installation</h1>";
//if demo is true, throw a message
if (tm_DEMO()) $MESSAGE.="<h2><font color=\"red\">Demo</font></h2>";
//add some links on top
$MESSAGE.="<a href=\"http://www.tellmatic.org\" target=\"_blank\">HOME</a>&nbsp;-&nbsp;".
					"<a href=\"http://www.tellmatic.org/about\" target=\"_blank\">ABOUT/ÜBER</a>&nbsp;-&nbsp;".
					"<a href=\"README\" target=\"_blank\">README</a>&nbsp;-&nbsp;".
					"INSTALL.<a href=\"INSTALL.DE\" target=\"_blank\">DE</a>/<a href=\"INSTALL.EN\" target=\"_blank\">EN</a>&nbsp;&nbsp;".
					"<a href=\"UPDATE\" target=\"_blank\">UPDATE</a>&nbsp;&nbsp;".
					"<a href=\"CHANGES\" target=\"_blank\">CHANGES</a>&nbsp;&nbsp;".
					"<a href=\"http://www.tellmatic.org/?c=faq\" target=\"_blank\">FAQ</a>".
					"<br><br>";
//check if language is selected
if (!isset($lang) || empty($lang)) {
	//if not, include form
	require_once(TM_INCLUDEPATH_INSTALL."/install_language.inc.php");
	$MESSAGE.=$FORM_LANG;
}

//if language is set
if (isset($lang) && !empty($lang)) {
	//load gettext array for translation
	$translateStrings = Array();
	load_translateStrings($lang);
	/***********************************************************/
	//precheck
	/***********************************************************/
	//check for memory, permissions etc
	require_once(TM_INCLUDEPATH_INSTALL."/install_check_pre.inc.php");
	//prepare registration message
	if (!tm_DEMO()) $MESSAGE_REG=TM_APPTEXT."\n".
							"Date: ".$created."\n".
							"PHPVersion: ".phpversion()."\n".
							"PHP Sapi: ".$php_sapi."\n".
							"OS: ".$php_os.": ".PHP_OS."\n".
							"DocRoot: ".TM_DOCROOT."\n".
							"Domain: ".TM_DOMAIN."\n".
							"Dir: ".TM_DIR."\n".
							"Path: ".TM_PATH."\n".
							"mem: ".$mem."\n".
							"exec_time: ".$exec_time."\n".
							"register_globals: ".ini_get("register_globals")."\n".
							"safe_mode: ".ini_get("safe_mode")."\n".
							"magic_quotes: ".ini_get("magic_quotes_gpc")."\n".
							"lang: ".$lang."\n";
	if (tm_DEMO()) $MESSAGE_REG=TM_APPTEXT."\n".
							"Date: ".$created."\n".
							"PHPVersion: ---demo---\n".
							"PHP Sapi: ---demo---\n".
							"OS: ---demo---\n".
							"DocRoot: ---demo---\n".
							"Domain: ".TM_DOMAIN."\n".
							"Dir: ".TM_DIR."\n".
							"Path: ---demo---\n".
							"mem: ---demo---\n".
							"exec_time: ---demo---\n".
							"register_globals: ---demo---\n".
							"safe_mode: ---demo---\n".
							"magic_quotes: ---demo---\n".
							"lang: ".$lang."\n";
	//if pre check is ok
	if ($check) {
		//check if license was accepted
		if ($accept!=1) {
			//if not, include the license form
			require_once(TM_INCLUDEPATH_INSTALL."/install_license.inc.php");
			//add rendered form to output
			$MESSAGE.=$FORM_LICENSE;
		}
		//if license is accepted
		if ($accept==1) {
			//check if form was submitted
			if ($set=="save" && $check) {
				/***********************************************************/
				//check form input
				/***********************************************************/
				if ($check) require_once(TM_INCLUDEPATH_INSTALL."/install_check_input.inc.php");
				/***********************************************************/
				//check db connection
				/***********************************************************/
				if ($check) require_once(TM_INCLUDEPATH_INSTALL."/install_check_db.inc.php");
				//do install routines
				if ($check && $checkDB) require_once(TM_INCLUDEPATH_INSTALL."/install_conf_sql.inc.php");
				if ($check && $checkDB) require_once(TM_INCLUDEPATH_INSTALL."/install_install.inc.php");
				if ($check && $checkDB) require_once(TM_INCLUDEPATH_INSTALL."/install_examples_conf.inc.php");
				if ($check && $checkDB) require_once(TM_INCLUDEPATH_INSTALL."/install_examples.inc.php");
				if ($check && $checkDB) require_once(TM_INCLUDEPATH_INSTALL."/install_finish.inc.php");
				/***********************************************************/
				//formular anzeigen, show form
				/***********************************************************/
				//Daten ermitteln
				if (!$check) {
					//if input check and db check is not ok and form was sent
					require_once(TM_INCLUDEPATH_INSTALL."/install_form.inc.php");
					require_once(TM_INCLUDEPATH_INSTALL."/install_form_show.inc.php");
					//add rendered form to outpur
					$MESSAGE.=$FORM;
				}
			} else {
				//do nothing
			}//set = save && check
			/***********************************************************/
			//formular anzeigen
			/***********************************************************/
			if ($set!="save" && $check) {
				//show installation form
				require_once(TM_INCLUDEPATH_INSTALL."/install_form.inc.php");
				require_once(TM_INCLUDEPATH_INSTALL."/install_form_show.inc.php");
				//add rendered form to output
				$MESSAGE.=$FORM;
			}//!save && check
		}//accept
	}//check
	//prepare info message
	if (!tm_DEMO()) $MESSAGE_INFO="<hr>".
							"<b>".___("Informationen")."</b>".
							"<br>".___("PHP Version:")." <em>".phpversion()."</em>".
							"<br>PHP Sapi: <em>".$php_sapi."</em>".
							"<br>OS: <em>".$php_os.": ".PHP_OS."</em>".
							"<br>DocRoot: <em>".TM_DOCROOT."</em>".
							"<br>".___("Domain").": <em>".TM_DOMAIN."</em>".
							"<br>".___("Verzeichnis").": <em>".TM_DIR."</em>".
							"<br>".___("Installationspfad").": <em>".TM_PATH."</em>".
							"<br>".___("zugewiesener Speicher für PHP").": <em>".$mem." Byte</em>".
							"<br>".___("zugewiesene Ausführungszeit für PHP").": <em>".$exec_time." Sekunden</em>";
	if (tm_DEMO()) $MESSAGE_INFO="<hr>".
							"<b>".___("Informationen")."</b>".
							"<br>".___("PHP Version:")." <em>---demo---</em>".
							"<br>PHP Sapi: <em>---demo---</em>".
							"<br>OS: <em>---demo---</em>".
							"<br>DocRoot: <em>---demo---</em>".
							"<br>".___("Domain").": <em>".TM_DOMAIN."</em>".
							"<br>".___("Verzeichnis").": <em>".TM_DIR."</em>".
							"<br>".___("Installationspfad").": <em>---demo---</em>".
							"<br>".___("zugewiesener Speicher für PHP").": <em>---demo--- Byte</em>".
							"<br>".___("zugewiesene Ausführungszeit für PHP").": <em>---demo--- Sekunden</em>";

	//add info message to output
	$MESSAGE.="<p>".$MESSAGE_INFO."</p>";
}//lang

//if debug is set to true
if (tm_DEBUG_LANG() && (tm_DEBUG_LANG_LEVEL() == 2 || tm_DEBUG_LANG_LEVEL==3) ) {
	$MESSAGE.="<br><br>";
	//show translated not strings
	$debug_not_translated=unify_array($debug_not_translated);
	$MESSAGE.="<div><b><font color=\"red\">NOT TRANSLATED:</font></b><font size=-2>";
	foreach ($debug_not_translated as $word) {
		$MESSAGE.="<br>".$word;
	}
	$MESSAGE.="</font></div>";
	unset($debug_not_translated);
	//show strings translated with source string
	$debug_same_translated=unify_array($debug_same_translated);
	$MESSAGE.="<br><div><b><font color=\"orange\">TRANSLATED WITH SAME STRING:</font></b><font size=-2>";
	foreach ($debug_same_translated as $word) {
		$MESSAGE.="<br>".$word;
	}
	$MESSAGE.="</font></div>";
	unset($debug_same_translated);
	//show translated strings
	$debug_translated=unify_array($debug_translated);
	$MESSAGE.="<br><div><b><font color=\"green\">TRANSLATED:</font></b><font size=-2>";
	foreach ($debug_translated as $word) {
		$MESSAGE.="<br>".$word;
	}
	$MESSAGE.="</font></div>";
	unset($debug_translated);
}
//create output
//a header and logo
echo '<div id="head" class="head">&nbsp;</div><div id="logo" class="logo">&nbsp;</div><div id="tellmatic" class="tellmatic">&nbsp;</div>';
//the main section
echo '<div id="main" class="main">'.$MESSAGE;
//a copyright message
echo '<br><br>&copy;-left 2007-2012 ff. <a href="http://www.tellmatic.org" target="blank">Tellmatic - die Newsletter Maschine - www.tellmatic.org</a>';
echo '</div>';
//add footer
require_once(TM_INCLUDEPATH_INSTALL."/install_foot.inc.php")
?>