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
//configure
/***********************************************************/
//guess path to config and include
#define("TM_DOCROOT",dirname(__FILE__));
#define("TM_DOCROOT",realpath(dirname(__FILE__)));
#^^ bringt nix da wir unbedingt den dirnamen brauchen, falls nicht im docroot vom httpd installiert
require_once(realpath(dirname(__FILE__))."/include/tm_Install/install_conf.inc.php");
#require_once(TM_DOCROOT."/include/install/install_conf.inc.php");
require_once(TM_INCLUDEPATH_INSTALL."/install.inc.php");
?>