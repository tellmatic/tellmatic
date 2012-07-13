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

//using http://www.phpclasses.org/trackback/browse/package/9.html http://www.phpclasses.org/browse/package/14.html http://www.phpclasses.org/trackback/browse/package/14.html
//Class: MIME E-mail message composing and sending
//thx to Manuel Lemos <mlemos at acm dot org>
//look at: http://freshmeat.net/projects/mimemessageclass/
//BSD License http://www.opensource.org/licenses/bsd-license.html

//sasl - auth meachanism
/*
sasl/basic_sasl_client.php
sasl/cram_md5_sasl_client.php
sasl/digest_sasl_client.php
sasl/login_sasl_client.php
sasl/ntlm_sasl_client.php
sasl/plain_sasl_client.php
sasl/sasl.php
*/
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/basic_sasl_client.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/cram_md5_sasl_client.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/digest_sasl_client.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/login_sasl_client.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/ntlm_sasl_client.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/plain_sasl_client.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/sasl/sasl.php");

//mime message class
/*
mimemessage/email_message.php
mimemessage/smtp_message.php
*/
require_once(TM_INCLUDEPATH_LIB_EXT."/mimemessage/email_message.php");
require_once(TM_INCLUDEPATH_LIB_EXT."/mimemessage/smtp_message.php");

//smtp class
/*
smtp/smtp.php
*/
require_once(TM_INCLUDEPATH_LIB_EXT."/smtp/smtp.php");
?>