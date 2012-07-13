<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/10 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/*******************************************************************************/
if (tm_DEBUG()) $MESSAGE.=tm_message_debug("updating adr record");
$code=$ADR[0]['code'];
//wenn adresse existiert, adressdaten updaten!
$ADDRESS->updateAdr(Array(
	"id"=>$ADR[0]['id'],
	"email"=>$email,
	"aktiv"=>$ADR[0]['aktiv'],
	"created"=>$created,
	"author"=>$author,
	"f0"=>$f0,
	"f1"=>$f1,
	"f2"=>$f2,
	"f3"=>$f3,
	"f4"=>$f4,
	"f5"=>$f5,
	"f6"=>$f6,
	"f7"=>$f7,
	"f8"=>$f8,
	"f9"=>$f9,
	"memo"=>"subscribe update, memo:\n ".$memo,
	),
	$new_adr_grp);
//wenn user abgemeldet und sich wieder anmelden will... dann status aendern, sonst bleibt alles wie es ist...:
//update status wenn unsubscribed (11)! -- status: 1 , neu
if ($ADR[0]['status'] ==11) {
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("email was unsubscribed, set status=1, aktiv=1");
	$ADDRESS->setStatus($ADR[0]['id'],1);
	$ADDRESS->setAktiv($ADR[0]['id'],1);
}
$newADRID=$ADR[0]['id'];
if (tm_DEBUG()) $MESSAGE.=tm_message_debug("update adrid is ".$newADRID." code: ".$code);

//und neue referenzen zu neuen gruppen hinzufuegen
//$ADDRESS->addRef($ADR[0]['id'],$new_adr_grp);
// ^^^ nur fuer den fall das daten nicht geupdated werden!!! sondern nur referenzen hinzugefuegt!
//optional nachzuruesten und in den settings einstellbar :)
?>