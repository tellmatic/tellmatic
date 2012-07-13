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
$_MAIN_OUTPUT.="\n\n<!-- pager.inc.php -->\n\n";

// entrys = anzahl aktuell angezeigter eintraege, muss in jeder liste an der stelle wo noetig vor pager.inc eingefuegt und definiert werden! 
// dient der berechnung zur letzten seite... ;-) und anzeige anzahl eintraege!

//entrys_total = gesamtanzahl eintraege, ggf anhand suchparas
//offset: offset
//limit: limit anzahl eintraege pro angezeigte seite

$_MAIN_OUTPUT.= "<br><center>";

//an den anfang
$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$firstURLPara_."\" title=\"Anfang\"><img src=\"".$tm_iconURL."/resultset_first.png\" border=\"0\"></a>";

//1. seite zurueck
if ($offset>0) {
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$prevURLPara_."\" title=\"".___("Zurück")."\">".tm_icon("resultset_previous.png",___("Zurück"))."</a>";
}

$InputName_Page="page";
#$$InputName_Page=getVar($InputName_Type);


//pages
$page_total=abs(round($entrys_total / $limit));
$page_current=abs(round($offset / $limit));

$_MAIN_OUTPUT.= sprintf(___("Seite %s von %s"),$page_current+1,$page_total);
$_MAIN_OUTPUT.= ",&nbsp;".sprintf(___("Eintrag %s bis %s von %s"),($offset+1),($offset+$entrys),$entrys_total);

$page_start=$page_current-5;
if ($page_start<0) {
	$page_start=0;
}
$page_end=$page_current+5;
if ($page_end>$page_total) {
	$page_end=$page_total;
}
//1 seite vor
if ($limit < ($entrys+1) && ($offset+$entrys)!=$entrys_total) {
	$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$nextURLPara_."\" title=\"".___("Vor")."\">".tm_icon("resultset_next.png",___("Vor"))."</a>";
}
//ans ende
$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$lastURLPara_."\" title=\"".___("Ende")."\">".tm_icon("resultset_last.png",___("Ende"))."</a>";

$_MAIN_OUTPUT.= "<br>";
$_MAIN_OUTPUT.= "".___("Gehe zu Seite:")."&nbsp;";

for ($page=$page_start;$page < $page_end; $page++) {
	$pagesURLPara->addParam("offset",($limit*$page));
	$pagesURLPara_=$pagesURLPara->getAllParams();
	$page_title=sprintf(___("Seite %s von %s, Eintrag %s bis %s"),$page+1, $page_total, ($page*$limit)+1, ($page*$limit)+$limit);
	$page_linktext=$page+1;
	if ($page==$page_current) {
		$page_linktext="<strong>".$page_linktext."</strong>";
		$_MAIN_OUTPUT.= $page_linktext."&nbsp;";
	} else {
		$_MAIN_OUTPUT.= "<a href=\"".$tm_URL."/".$pagesURLPara_."\" title=\"".$page_title."\">".$page_linktext."</a>&nbsp;";
	}
}

#require_once(TM_INCLUDEPATH_GUI."/pager_form.inc.php");
#require_once(TM_INCLUDEPATH_GUI."/pager_form_show.inc.php");

$_MAIN_OUTPUT.= "</center><br>";
?>