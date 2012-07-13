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

if (!file_exists(realpath(dirname(__FILE__))."/../include/tm_config.inc.php")) {
//"../include/tm_config.inc.php"
	echo "config file missing!";
	exit;
}
#require_once ("../include/tm_config.inc.php");
require_once(realpath(dirname(__FILE__))."/../include/tm_config.inc.php");

require_once (TM_INCLUDEPATH_LIB."/tm_lib_admin.inc.php");

$dl=getVar('dl');

//?dl=i|f,section,filename
//downloadfile (f), showimage (i) 
//section definiert bereich auf /files, also import_export etc, darf aber nicht als dir genommen werden wegen ../ etc
//also if section=bla then dir=blub!

if ($dl=='f' || $dl=='i') {
	require_once (TM_INCLUDEPATH_LIB."/Login.inc.php");
	
	if ($logged_in) {
		echo "yeah, logged in";
	} else {
		echo "not logged in, go away";	
	}
	
	if ($dl=="f") {
		exit;
	}
	if ($dl=="i") {
		exit;
	}
} else {
	require_once (TM_INCLUDEPATH_LIB."/Index.inc.php");
}
?>