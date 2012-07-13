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

$set=getVar("set");
$val=getVar("val");
$doit=getVar("doit");//wird per js an url angefuegt!!! confirm()

//fastmode //using method with insert select left join etc to insert addresses, testing!
//so viele sendeeintraege in nl_h werden maximal gemacht! (wenn exectime==0 dann unlimited, eigenes limit ca 3600 --> 3600/30*20000=2,4mio)
$default_h_limit=round($max_execution_time/30*20000);//max 20k per 30 sec
//wenn versandauftrag angelegt wird...:
//user offset und limit
//offset
$InputName_Offset="usr_offset";
$$InputName_Offset=getVar($InputName_Offset,0,0);//default 0
//limit
$InputName_Limit="usr_limit";
$$InputName_Limit=getVar($InputName_Limit,0,$default_h_limit);//default ^^

$HOSTS=new tm_HOST();
?>