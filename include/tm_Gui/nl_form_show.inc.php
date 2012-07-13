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

/*RENDER FORM*/

$Form->render_Form($FormularName);

/*DISPLAY*/
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['act']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['set']['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName]['nl_id']['html'];

$_MAIN_OUTPUT.= "<table border=0>";

	$_MAIN_OUTPUT.= "<tr>";
	$_MAIN_OUTPUT.= "<td colspan=\"2\" style=\"border: 1px dashed #cccccc;\">";
if (!empty($nl_id)) {
	$_MAIN_OUTPUT.= "ID: <b>".$NL[0]['id']."</b>";
	$_MAIN_OUTPUT.= "<br>";
	$_MAIN_OUTPUT.= sprintf(___("Erstellt am: %s von %s"),"<b>".$NL[0]['created']."</b>","<b>".$NL[0]['author']."</b>");
	$_MAIN_OUTPUT.= "<br>";
	$_MAIN_OUTPUT.= sprintf(___("Bearbeitet am: %s von %s"),"<b>".$NL[0]['updated']."</b>","<b>".$NL[0]['editor']."</b>");
}
	$_MAIN_OUTPUT.= "<br><br>";
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "<td valign=\"top\"  rowspan=16 style=\"border: 1px dashed #cccccc;\">";
	$_MAIN_OUTPUT.= tm_icon("disk.png",___("Anhänge"))."&nbsp;".___("Anhänge");
	$_MAIN_OUTPUT.= "<br>";
	$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_AttachExisting]['html'];
	$_MAIN_OUTPUT.= "</td>";
	$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= tm_icon("textfield_rename.png",___("Vorlage"))."&nbsp;".___("Vorlage");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\" style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Template]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv")).tm_icon("cancel.png",___("Inaktiv"))."&nbsp;".___("Aktiv");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Aktiv]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" width=\"200\">";
$_MAIN_OUTPUT.= tm_icon("sum.png",___("Betreff"))."&nbsp;".___("Betreff");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Name]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" width=\"200\">";
$_MAIN_OUTPUT.= tm_icon("textfield.png",___("Titel"))."&nbsp;".___("Titel");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Title]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" width=\"200\">";
$_MAIN_OUTPUT.= tm_icon("textfield.png",___("Sub-Titel"))."&nbsp;".___("Sub-Titel");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_TitleSub]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" width=\"200\">";
$_MAIN_OUTPUT.= tm_icon("user_comment.png",___("Empfängername"))."&nbsp;".___("Empfängername");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_RCPTName]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("page_white_office.png",___("Format"))."&nbsp;".___("Format");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ContentType]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("lorry.png",___("Massenmailing")).tm_icon("user_suit.png",___("personalisiertes Newsletter"))."&nbsp;".___("Mailing-Typ");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Massmail]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("book.png",___("Gruppe"))."&nbsp;".___("Gruppe");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Group]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("page_white_link.png",___("Link"))."&nbsp;".___("Link");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Link]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= tm_icon("photo.png",___("Bild"))."&nbsp;".___("Bild");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\" style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Image1]['html'];

$_MAIN_OUTPUT.= "<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ImageResize]['html'];
$_MAIN_OUTPUT.= ___("Neue Größe").":&nbsp;";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ImageResizeSize]['html'];
$_MAIN_OUTPUT.= "px";
$_MAIN_OUTPUT.= "<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ImageWatermark]['html'];
$_MAIN_OUTPUT.= ___("Wasserzeichen").":&nbsp;<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ImageWatermarkImage]['html'];


$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("page_white_world.png",___("HTML"))."&nbsp;".___("HTML");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_File]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("picture_go.png",___("Tracking Bild"))."&nbsp;".___("Blind- bzw. Tracking Bild auswählen oder neues Bild hochladen");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_TrackImageExisting]['html']."&nbsp; oder<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_TrackImageNew]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("bullet_star.png",___("Tracking personalisiert"),"","","","user_suit.png")."&nbsp;".___("Tracking personalisiert");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_TrackPerso]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png")."&nbsp;".___("Inline Images");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_UseInlineImages]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("attach.png",___("Anhang"))."&nbsp;".___("Neuer Anhang");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Attach1]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3 style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= tm_icon("page_white_text.png",___("Text"))."&nbsp;".___("Text-Part")."&nbsp;&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "(<a href=\"javascript:switchSection('text_part');\" >".___("Ein-/Ausblenden")."</a>)";
$_MAIN_OUTPUT.= "<div id=\"text_part\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_DescrText]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3 style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= tm_icon("page_white_h.png",___("HTML-Header"))."&nbsp;".___("HTML-Header")."&nbsp;&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "(<a href=\"javascript:switchSection('html_part_header');\" >".___("Ein-/Ausblenden")."</a>)<br>";
$_MAIN_OUTPUT.= "<div id=\"html_part_header\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_BodyHead]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3 style=\"border: 1px dashed #cccccc;\">";
#$_MAIN_OUTPUT.= "<a href=\"#\" onclick=\"loadWysiwyg();\">";
#$_MAIN_OUTPUT.= ___("Lade Wysiwyg Editor");
#$_MAIN_OUTPUT.= "&nbsp;".tm_icon("wand.png",___("Lade Wysiwyg Editor"))."</a>";
$_MAIN_OUTPUT.= tm_icon("page_white_h.png",___("HTML-Body"))."&nbsp;".___("HTML-Body")."&nbsp;&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "(<a href=\"javascript:switchSection('html_part_body');\" >".___("Ein-/Ausblenden")."</a>)";
$_MAIN_OUTPUT.= "<div id=\"html_part_body\">";
$_MAIN_OUTPUT.= "<a href=\"javascript:toggleEditor('".$InputName_Descr."');\" >";
$_MAIN_OUTPUT.= tm_icon("wand.png",___("Editor Ein/Aus"))."&nbsp;".___("Wysiwyg Editor Ein/Aus");
$_MAIN_OUTPUT.= "</a><br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Descr]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3 style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= tm_icon("page_white_h.png",___("HTML-Footer"))."&nbsp;".___("HTML Footer")."&nbsp;&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "(<a href=\"javascript:switchSection('html_part_footer');\" >".___("Ein-/Ausblenden")."</a>)<br>";
$_MAIN_OUTPUT.= "<div id=\"html_part_footer\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_BodyFoot]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

//summary
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3 style=\"border: 1px dashed #cccccc;\">";
$_MAIN_OUTPUT.= tm_icon("page_white_zip.png",___("Zusammenfassung"))."&nbsp;".___("Zusammenfassung")."&nbsp;&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "(<a href=\"javascript:switchSection('nl_summary');\" >".___("Ein-/Ausblenden")."</a>)";
$_MAIN_OUTPUT.= "<div id=\"nl_summary\">";
$_MAIN_OUTPUT.= "<a href=\"javascript:toggleEditor('".$InputName_Summary."');\" >";
$_MAIN_OUTPUT.= tm_icon("wand.png",___("Editor Ein/Aus"))."&nbsp;".___("Wysiwyg Editor Ein/Aus");
$_MAIN_OUTPUT.= "</a><br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Summary]['html'];
$_MAIN_OUTPUT.= "</div>";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Reset]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];

$_MAIN_OUTPUT.= "<br><br>";


require_once(TM_INCLUDEPATH_GUI."/nl_form_legende.inc.php");

	$_MAIN_OUTPUT.= "
		<script language=\"javascript\" type=\"text/javascript\">
		switchSection('html_part_footer');
		switchSection('html_part_header');
		switchSection('html_part_body');
		switchSection('text_part');
		switchSection('nl_summary');
		//toggleSlide('toggle_nlbody_text','text_part',1);//trigger function erzeugen: triggerid,divid,toggle
		//toggleSlide('toggle_nlbody_html','html_part',1);//trigger function erzeugen: triggerid,divid,toggle
		</script>
	";

require_once (TM_INCLUDEPATH_LIB."/wysiwyg.inc.php");

?>