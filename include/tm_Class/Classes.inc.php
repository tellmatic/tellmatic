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

require_once (TM_INCLUDEPATH_CLASS."/Class_DB.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Misc.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Log.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_URL.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Tpl.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_CFG.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_NL.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Q.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Adr.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Blacklist.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Form.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Link.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Host.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Mail.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_Bounce.inc.php");
require_once (TM_INCLUDEPATH_CLASS."/Class_mSimpleForm.inc.php");
require_once(TM_INCLUDEPATH_CLASS."/Class_SMTP.inc.php");//was in send_it_config, we do not load it here, we only need it in send_it! finally decided to load it here, then there is no need to include it in function sendmail_smtp etc.
?>