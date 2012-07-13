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
//Beispielcode: letzte X Newsletter anzeigen, newsletter aber aus q holen! nur mit status versendet!
//"Newsletter-Archiv"

//This is just a very simple example!
$nl_offset=0;//offset, kann auch aus $_GET oder per getVar ermittelt werden....
$nl_limit=10;//anzahl anzuzeigender newsletter
$nl_content=0;//do not return content
$nl_grpid=0;//nl group, 0=all groups

$QUEUE=new tm_Q();
$NL=new tm_NL();
$search_nl['is_template']=0;
$search_nl['aktiv']=1;
$search_nl['status']=Array(3,4,5);//started, sent, archiv, see Stats.inc.php
$Q=$QUEUE->getQ(0,0,$nl_limit,0,$nl_grpid,4);

foreach ($Q as $q) {
	$N=$NL->getNL($q['nl_id'],0,1,0,1,"",1,$search_nl);
	$nl=$N[0];
		$nl_image="nl_".date_convert_to_string($nl['created'])."_1.jpg";	
		$nl_imageurl=$tm_URL_FE."/".$tm_nlimgdir."/".$nl_image;
		echo "created ".display($nl['created'])
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
		echo "<hr>\n";
}//for / foreach

?>