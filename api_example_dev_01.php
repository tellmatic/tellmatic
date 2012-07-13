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
exit;//remove this line or add # in front of line
//inclue tm config
#require_once ("./include/tm_config.inc.php");//change path to full path to tm_config if the script is not in tellmatic installation directory!
require_once(realpath(dirname(__FILE__))."/include/tm_config.inc.php");

//Beispielcode: Adressen mit mehr als 1 Gruppe anzeigen

//This is just a very simple example!

//neues address-objekt
$ADR=new tm_ADR();

//
$A=Array();//array mit allen addressids
$B=Array();//zielarray mit addressids mit mehr als 1 gruppe
$bn=0;//zaehler f. adressen > 1 gruppe

//Array A fuellen mit AddressIDs aus der DB
//syntax:   function getAdrID($group_id=0,$search=Array())
$A=$ADR->getAdrID();

echo print_r($A,TRUE);
echo "<hr>";


//Array durchwandern und jede Adresse pruefen
foreach ($A as $adr_id) {
	$G=Array();//temporaeres Array
	//Gruppen fuer Adresse holen	
	//syntax: function getGroupID($id=0,$adr_id=0,$frm_id=0,$search=Array())
	$G=$ADR->getGroupID(0,$adr_id);
	//wenn mehr als 1 Gruppe!, dann speichere ID der Adresse in B und erhoehe den Counter bn	
	if (count($G)>1) {
		$B[$bn]=$adr_id;
		$bn++;
	}
}

echo print_r($B,TRUE);
echo "<hr>";


//ergebnis anzeigen, IDs von Adressen mit mehr als 1 Gruppe sind in $B abgelegt
foreach ($B as $adr_id) {
	//Addressdetails auslesen
	//syntax: getAdr($id=0,$offset=0,$limit=0,$group_id=0,$search=Array(),$sortIndex="",$sortType=0,$Details=1) 
	$A=$ADR->getAdr($adr_id);
	//Gruppen f.d. Addresse auslesen
	$G=$ADR->getGroup(0,$adr_id);
	
	echo print_r($A,TRUE);
	echo print_r($G,TRUE);
	echo "<hr>";
}

?>