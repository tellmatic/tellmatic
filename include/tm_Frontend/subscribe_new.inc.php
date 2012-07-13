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
if (tm_DEBUG()) $MESSAGE.=tm_message_debug("create new adr record");
srand((double)microtime()*1000000);
$code=rand(111111,999999);
//wenn adresse noch nicht existiert , neu anlegen
$newADRID=$ADDRESS->addAdr(Array(
	"email"=>$email,
	"aktiv"=>$FRM[0]['subscribe_aktiv'],
	"created"=>$created,
	"status"=>$status,
	"code"=>$code,
	"author"=>$author,
	"source"=>"subscribe",
	"source_id"=>$FRM[0]['id'],
	"source_extern_id"=>0,
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
	"memo"=>"subscribe new, memo:\n ".$memo,
	),
	$new_adr_grp);
	
if (tm_DEBUG()) $MESSAGE.=tm_message_debug("new adrid is ".$newADRID." code: ".$code);
?>