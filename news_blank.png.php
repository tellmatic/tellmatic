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

//aufruf: news_blank.png.php?h_id=&nl_id=&a_id=
/*
aufruf ohne parameter --> blindimage XxY Pixel oder auch global...? :)
aufruf mit parameter:
settings per newsletter -->
	blankimage ? --> blank png erzeigen
	eigenes bild --> extension auslesen, jpg oder png erzeugen
	global? --> globale settings auslesen
		blank? --> blank png erzeugen
		eigenes bild --> extension auslesen, jpg oder png erzeugen
	bild ausgeben.
*/
#require_once ("./include/tm_config.inc.php");
require_once(realpath(dirname(__FILE__))."/include/tm_config.inc.php");

require_once(TM_INCLUDEPATH_FE."/news_blank.png.inc.php");

?>