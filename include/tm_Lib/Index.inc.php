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

$T=new Timer();

$action=getVar("act");



$img_arrowup=tm_icon("bullet_arrow_up.png",___("Sortierung aufsteigend"));
$img_arrowdown=tm_icon("bullet_arrow_down.png",___("Sortierung absteigend"));


//set default style!
$Style=$C[0]['style'];


//remove old temporrary admin html  files!
//if file is older then 3 seconds, remove!
if (USE_TMP_HTML_FILES) {
	if (tm_DEBUG()) {
		$_MAIN_OUTPUT.="<!--\n".remove_old_admin_files()."-->\n";
	} else {
		remove_old_admin_files();
	}
}
$mSTDURL=new tm_URL();

//Handle Login / Logout
require_once (TM_INCLUDEPATH_LIB."/Login.inc.php");
//Sprache festlegen! wird oben bereits  aufgerufen!! wenn noch nicht eingeloggt.

if ($logged_in) {
	require (TM_INCLUDEPATH_LIB."/Lang.inc.php");
	if (!empty($LOGIN->USER['style'])) {
		$Style=$LOGIN->USER['style'];
	}
}//logged in

if (tm_DEMO()) $_MAIN_MESSAGE.="<br><font color=\"blue\"><strong>".___("DEMO Modus aktiviert")."</strong></font><br>".___("Einige Funktionen sind deaktiviert.")."<br>";
if (tm_DEBUG()) $_MAIN_MESSAGE.="<br><font size=2 color=\"red\"><h1>".___("DEBUG Modus")."</h1></font><br>";


//HTML-head
require_once (TM_INCLUDEPATH_LIB."/Header.inc.php"); // = $_HEAD_HTML
require_once (TM_INCLUDEPATH_LIB."/Head.inc.php");
//Status definitionen einbinden..... war vorher tm_lib, jetzt hier da abhaengig von der sprache :) sonst wirds nicht uebersetzt
require_once (TM_INCLUDEPATH_LIB."/Stats.inc.php");
if ($logged_in) {

	//default action, user default page after login, $action
	if (!isset($action) || empty($action)) {
		$action=$LOGIN->USER['startpage'];//set default action after login
		#$action="Welcome";//set default action after login
	}

	$mSTDURL->addParam("act",$action);

	require_once (TM_INCLUDEPATH_LIB."/Menu.inc.php");


}
require_once (TM_INCLUDEPATH_LIB."/Main.inc.php");
require_once (TM_INCLUDEPATH_LIB."/Foot.inc.php");
require_once (TM_INCLUDEPATH_LIB."/Footer.inc.php");// = $_FOOTER
//new Template
$_Tpl=new tm_Template();

if (tm_DEBUG_LANG() && (tm_DEBUG_LANG_LEVEL() == 2 || tm_DEBUG_LANG_LEVEL()==3) ) {
	$debug_not_translated=unify_array($debug_not_translated);
	$_MAIN.="<div><b><font color=\"red\">NOT TRANSLATED:</font></b><font size=-2>";
	foreach ($debug_not_translated as $word) {
		$_MAIN.="<br>".$word;
	}
	$_MAIN.="</font></div>";
	unset($debug_not_translated);

	$debug_same_translated=unify_array($debug_same_translated);
	$_MAIN.="<br><div><b><font color=\"orange\">TRANSLATED WITH SAME STRING:</font></b><font size=-2>";
	foreach ($debug_same_translated as $word) {
		$_MAIN.="<br>".$word;
	}
	$_MAIN.="</font></div>";
	unset($debug_same_translated);

	$debug_translated=unify_array($debug_translated);
	$_MAIN.="<br><div><b><font color=\"green\">TRANSLATED:</font></b><font size=-2>";
	foreach ($debug_translated as $word) {
		$_MAIN.="<br>".$word;
	}
	$_MAIN.="</font></div>";
	unset($debug_translated);
}

$_Tpl->setTemplatePath(TM_TPLPATH."/".$Style);
$_Tpl->setParseValue("_HEAD_HTML", $_HEAD_HTML);
$_Tpl->setParseValue("_HEAD", $_HEAD);
$_Tpl->setParseValue("_MAIN", $_MAIN);
$_Tpl->setParseValue("_FOOT", $_FOOT);
$_Tpl->setParseValue("_FOOTER", $_FOOTER);
$_Tpl->setParseValue("_MENU", $_MENU);

if ($logged_in) {
	$_Tpl->setParseValue("_MENU", $_MENU);
	$adm_file_name="tm_".session_id()."-".$LOGIN->USER['id']."-".time().".html";
}

if (!$logged_in) {
	$adm_file_name="login_".session_id()."-0-".time().".html";
}

//RENDER PAGE
$_PAGE=$_Tpl->renderTemplate("Index.html");

//write to file and redirect?
if (USE_TMP_HTML_FILES) {
	write_file(TM_ADMINPATH_TMP."/",$adm_file_name,$_PAGE);
	header('Location: '.$tm_URL."/tmp/".$adm_file_name);//hmmm, use dir constant instead...2do
} else {
	//or output page and exit :)
	echo $_PAGE;
}
exit;
?>