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

#exit;

//include config file: edit line if you run this script via cronjob, add full path to tellmatic config file
#require_once ("./tm_config.inc.php");
#require_once (dirname(__FILE__)."/tm_config.inc.php");
require_once (realpath(dirname(__FILE__))."/tm_config.inc.php");

require_once(TM_INCLUDEPATH_BE."/send_it.inc.php");
?>