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
//Beispielcode: letzte X Newsletter anzeigen

//This is just a very simple example!
$nl_offset=0;//offset, kann auch aus $_GET oder per getVar ermittelt werden....
$nl_rows=5;
$nl_columns=2;
$nl_limit=$nl_rows * $nl_columns;//anzahl anzuzeigender newsletter
$nl_sortIndex="updated";//letztes edit datum, alternativ: "created"
$nl_content=0;//do not return content
$nl_grpid=0;//nl group, 0=all groups
//umstaendehalber koennte man auch das sendedatum aus der q auslesen!
//wir gehen aber den einfachen weg

//neues address-objekt
$NL=new tm_NL();
$search_nl['is_template']=0;
$search_nl['aktiv']=1;
$search_nl['status']=Array(3,4,5);//started, sent, archiv, see Stats.inc.php

#$N=Array();//array
//function getNL($id=0,$offset=0,$limit=0,$group_id=0,$return_content=0,$sortIndex="",$sortType=0, $search=Array())
//get nl_limit newsletters with offset nl_offset and sort by nl_sortIndex, and return Content
//hole nl_limit newsletter, ab nl_offset , sortiere vorher nach nl_sortIndex und gib den Inhalt und Details zurueck

$N=$NL->getNL(0,$nl_offset, $nl_limit,0,1,"updated",1,$search_nl);
#, $nl_grpid, $nl_content, $nl_sortIndex);

echo "<table border=1 width=600>\n";
echo "<tbody>\n";
echo "<tr>\n";
//Array durchwandern und jede Adresse pruefen

$ni=0;
foreach ($N as $nl) {
#for ($ni=0;$ni<$nl_limit;$ni++) {
#	$nl=$N[$ni];
	#if ($nl['status']==3 || $nl['status']==4 || $nl['status']==5 ) {	
		$nl_image="nl_".date_convert_to_string($nl['created'])."_1.jpg";	
		$nl_imageurl=$tm_URL_FE."/".$tm_nlimgdir."/".$nl_image;
		echo "<td width=".round(600/$nl_columns)." height=".round(600/$nl_rows)." align=\"center\" valign=\"middle\">\n";
		echo "<font size=1>created ".display($nl['created'])
				." by ".display($nl['author'])
				."\n<br>\n"
				."last updated ".display($nl['updated'])
				." by ".display($nl['editor'])
				."\n<br>\n"
				."<a href=\"".$nl['link']."\" target=\"_blank\">"
				.display($nl['title'])."<br>".display($nl['title_sub'])
				."</a>"
				."\n<br>\n"
				.display($nl['summary'])
				."\n<br>\n"
				."<a href=\"".$nl['link']."\" target=\"_blank\">"
				."<img src=\"".$nl_imageurl."\" border=0 width=200 height=200>\n"
				."\n<br>\n"
				.display($nl_imageurl)
				."</a>"
				."</font>\n";
		echo "</td>\n";
		if ( ($ni+1) % $nl_columns == 0) {
			echo "</tr><tr>\n";		
		}
		$ni++;
	#}//status
}//for / foreach

echo "</tr>\n";
echo "</tbody>\n";
echo "</table>\n";
?>