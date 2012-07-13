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

//DEBUG DATA!!!
$_FOOT="";
$_FOOT.= "<div id=\"div_debug\" class=\"debug\">";
$_FOOT.= "<a href=\"javascript:switchSection('div_debug');\"><img src=\"".$tm_iconURL."/information.png\" border=\"0\"  alt=\"".___("Debug-Informationen")."\"></a>";
$_FOOT.= "<br>";
if (function_exists('memory_get_usage')) {
	$_FOOT.= "<br>".sprintf(___("Benutzer Speicher: %s MB"),number_format((memory_get_usage()/1024/1024), 2, ',', ''));
}
$_FOOT.= "<br>".sprintf(___("Bearbeitungszeit: %s Sekunden"),number_format($T->Result(), 2, ',', ''));
$_FOOT.= "<br>".sprintf(___("Max. Upload: %s"),ini_get("upload_max_filesize"));
$_FOOT.= "<br>".sprintf(___("Speicherlimit: %s"),ini_get("memory_limit"))."";
$_FOOT.= "<br>".sprintf(___("Max. POST Data: %s"),ini_get("post_max_size"));
$_FOOT.= "<br>".sprintf(___("Max. Ausführungszeit: %s Sekunden"),ini_get("max_execution_time"));
$real_path=realpath("./index.php");
$path_info=pathinfo($real_path);
$doc_root=$_SERVER["DOCUMENT_ROOT"];
$host=$_SERVER["HTTP_HOST"];
$self=$_SERVER["PHP_SELF"];
$pathinfo=pathinfo($self);
$_FOOT.= "<br>Domain=: ".$host;
$_FOOT.= "<br>Docroot=: ".$doc_root;
$_FOOT.= "<br>Dir=: ".$pathinfo['dirname'];
$_FOOT.= "<br><br><a href=\"".$tm_URL_FE."/".TM_INCLUDEDIR."/phpinfo.php\" target=\"_blank\">".___("PHP Info")."</a><br><br>";
$_FOOT.= "<br><br><a href=\"javascript:switchSection('div_debug');\">(X) ".___("Fenster schliessen")."</a><br><br>";
$_FOOT.= "<br><br><center>&copy;-left 2006-2010 <a href=\"http://www.tellmatic.org\" target=\"blank\">".TM_APPTEXT."</a></center><br><br>";
$_FOOT.= "</div>";

//new Template
$_Tpl_Foot=new tm_Template();
$_Tpl_Foot->setTemplatePath(TM_TPLPATH."/".$Style);
$_Tpl_Foot->setParseValue("_FOOT", $_FOOT);

$_FOOT=$_Tpl_Foot->renderTemplate("Foot.html");
?>