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
	//STATUSCODES
	//statuscodes der adressen und bilder dazu
	//achtung!!! wenn neuer status dann auch beim versenden beachten......... und evtll. anfuegen an if abfrage, send.inc
	
	$STATUS=Array(
						"adr"=>Array(
								"status"=>Array(
												1=>___("Neu",0),
												2=>___("OK",0),
												3=>___("Bestätigt",0),
												4=>___("Angezeigt",0),
												5=>___("Warten",0),
												6=>"6 --",
												7=>"7 --",
												8=>___("Fehler C",0),
												9=>___("Fehler A",0),
												10=>___("Fehler S",0),
												11=>___("Abgemeldet",0),
												12=>___("Touch",0),
								),
								"descr"=>Array(
												1=>___("Neuanmeldung",0),
												2=>___("manuell Eingetragen, Syntax Geprüft, Senden OK",0),
												3=>___("Bestätigt, Double-Opt-In",0),
												4=>___("Rechecked, NL/Blindimage angezeigt",0),
												5=>___("Warten auf Bestätigung (Double-Opt-IN)",0),
												6=>___("undefiniert",0),
												7=>___("undefiniert",0),
												8=>___("Fehler beim versenden, Status/Aktiv wurde geändert vor dem versenden!",0),
												9=>___("Fehlerhafte Adresse o. Rückläufer",0),
												10=>___("Fehler beim versenden, neuer Versuch",0),
												11=>___("Abgemeldet",0),
												12=>___("1st-Touch-Opt-In",0),
								),
								"statimg"=>Array(
												1=>"new.png",
												2=>"bullet_black.png",
												3=>"bullet_green.png",
												4=>"bullet_feed.png",
												5=>"bullet_purple.png",
												6=>"bullet_yellow.png",
												7=>"bullet_black.png",
												8=>"page_error.png",
												9=>"bullet_error.png",
												10=>"transmit_error.png",
												11=>"user_red.png",
												12=>"user_add.png",
								),
								"color"=>Array(
												1=>"#009933",
												2=>"#00cc66",
												3=>"#00ff00",
												4=>"#00ffff",
												5=>"#ffff00",
												6=>"#000000",
												7=>"#000000",
												8=>"#ff6600",
												9=>"#ff0000",
												10=>"#ffcc00",
												11=>"#333333",//#ff9933
												12=>"#996600",
								),
								"textcolor"=>Array(
												1=>"#000000",
												2=>"#000000",
												3=>"#000000",
												4=>"#000000",
												5=>"#000000",
												6=>"#ffffff",
												7=>"#ffffff",
												8=>"#000000",
												9=>"#000000",
												10=>"#000000",
												11=>"#ffffff",
												12=>"#ffffff",
								),
						),

						"nl"=>Array(
								"status"=>Array(
												"1"=>___("Neu",0),
												"2"=>___("Queued",0),
												"3"=>___("Versand",0),
												"4"=>___("Gesendet",0),
												"5"=>___("Archiv",0),
												"6"=>___("Warten",0),
								),
								"descr"=>Array(
												"1"=>___("Keine Versandaufträge oder Historie f. dieses Newsletter",0),
												"2"=>___("In der Warteschlange",0),
												"3"=>___("Versand gestartet",0),
												"4"=>___("Versendet",0),
												"5"=>___("Archiviert - Qs wurden gelöscht",0),
												"6"=>___("Versand vorbereitet, Warten auf Versand (terminiert)",0),
								),
								"statimg"=>Array(
												"1"=>"new.png",
												"2"=>"clock_orange.png",
												"3"=>"clock_play.png",
												"4"=>"feed_green.png",
												"5"=>"feed_disk_blue.png",
												"6"=>"clock.png"
								),
								"color"=>Array(
												"1"=>"#009933",
												"2"=>"#ffcc00",
												"3"=>"#00ff00",
												"4"=>"#00ffcc",
												"5"=>"#333399",
												"6"=>"#ffff00"
								),
						),

						"q"=>Array(
								"status"=>Array(
												"1"=>___("Neu",0),
												"2"=>___("Gestartet",0),
												"3"=>___("Running",0),
												"4"=>___("Fertig",0),
												"5"=>___("Angehalten",0),
								),
								"descr"=>Array(
												"1"=>___("Neu",0),
												"2"=>___("Wird versendet",0),
												"3"=>___("In Arbeit",0),
												"4"=>___("Versendet",0),
												"5"=>___("Angehalten",0),
								),
								"statimg"=>Array(
												"1"=>"new.png",
												"2"=>"clock_orange.png",
												"3"=>"clock_play.png",
												"4"=>"feed_green.png",
												"5"=>"stop.png"
								),
						),

						"h"=>Array(
								"status"=>Array(
												"1"=>___("Neu",0),
												"2"=>___("Fertig",0),
												"3"=>___("View",0),
												"4"=>___("Fehler",0),
												"5"=>___("Versand",0),
												"6"=>___("Abbruch",0),
												"7"=>___("Abgemeldet",0),
								),
								"descr"=>Array(
												"1"=>___("Warten auf Versand",0),
												"2"=>___("Versendet, OK",0),
												"3"=>___("Versendet, angezeigt",0),
												"4"=>___("Versendet, Fehler",0),
												"5"=>___("Wird in diesem Moment versendet!!!",0),
												"6"=>___("Abgebrochen, Q gelöscht",0),
												"7"=>___("Abgemeldet",0),
								),
								"statimg"=>Array(
												"1"=>"new.png",
												"2"=>"bullet_green.png",
												"3"=>"bullet_feed.png",
												"4"=>"bullet_error.png",
												"5"=>"bullet_feed.png",
												"6"=>"bullet_red.png",
												"7"=>"user_red.png"
								),
						),
				);
?>