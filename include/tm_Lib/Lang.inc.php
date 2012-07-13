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

	#$locale = (isset($LOGIN) && isset($LOGIN->USER['lang']))? $LOGIN->USER['lang'] : DEFAULT_LOCALE;
	$locale = (isset($logged_in) && $logged_in && isset($LOGIN) && isset($LOGIN->USER['lang']))? $LOGIN->USER['lang'] : DEFAULT_LOCALE;
	
	//eigene gettext emulation
	$translateStrings = Array();
	load_translateStrings($locale);
?>