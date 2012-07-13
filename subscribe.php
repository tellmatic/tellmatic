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

//config einbinden
#require_once ("./include/tm_config.inc.php");
require_once(realpath(dirname(__FILE__))."/include/tm_config.inc.php");

//if subscribe.php is included in your script, please set frm_id to Form ID and $called_via_url=false; $_CONTENT holds the html output
require_once(TM_INCLUDEPATH_FE."/subscribe.inc.php");
?>