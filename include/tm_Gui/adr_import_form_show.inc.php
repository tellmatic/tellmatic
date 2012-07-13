<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/11 by Volker Augustin, multi.art.studio Hanau                            */
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
$_MAIN_OUTPUT.= "<table>";
$_MAIN_OUTPUT.= "<tr>";

$_MAIN_OUTPUT.= "<td valign=top>".tm_icon("page_white_gear.png",___("Upload"))."&nbsp;".___("CSV-Upload").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_File]['html'];
$_MAIN_OUTPUT.= "<br>".tm_icon("disk.png",___("Datei"))."&nbsp;".___("CSV-Datei auswählen").":".$Form->INPUT[$FormularName][$InputName_FileExisting]['html'];
$_MAIN_OUTPUT.= "</td>";

$_MAIN_OUTPUT.= "<td valign=top rowspan=\"13\" style=\"border-left:1px solid grey\">".tm_icon("group.png",___("Gruppen"))."&nbsp;".___("Gruppen")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Group]['html'];


$_MAIN_OUTPUT.= "<br>".___("neue Gruppe?")."&nbsp;".$Form->INPUT[$FormularName][$InputName_GroupNew]['html'];
$_MAIN_OUTPUT.= "<br>".$Form->INPUT[$FormularName][$InputName_GroupNewName]['html'];

$_MAIN_OUTPUT.= "</td>";


$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:1px dashed grey\" valign=\"top\">".tm_icon("keyboard.png",___("Bulk-Import"))."&nbsp;".___("Eine E-Mail-Adresse/Domain pro Zeile").":<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Bulk]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("lightbulb.png",___("Status"))."&nbsp;".___("Status f. neue Adressen:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Status]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv"));
$_MAIN_OUTPUT.= tm_icon("cancel.png",___("Inktiv"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Neue Adressen (De-)Aktivieren:")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_AktivNew]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:1px dashed grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("lightbulb.png",___("Status"))."&nbsp;".___("Status bei Update:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_StatusEx]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=top>";
$_MAIN_OUTPUT.= tm_icon("tick.png",___("Aktiv"));
$_MAIN_OUTPUT.= tm_icon("cancel.png",___("Inktiv"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Existierende Adressen bei Update (De-)Aktivieren:")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_AktivEx]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:1px dashed grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("group_add.png",___("Gruppen zusammenführen"))."&nbsp;".___("Gruppen zusammenführen").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_GroupsMerge]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("cross.png",___("Löschen"))."&nbsp;<font color=\"#ff0000\">".___("Importierte Adressen löschen")."</font>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Delete]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("ruby.png",___("Blacklist"))."&nbsp;".___("In Blacklist eintragen")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Blacklist]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("ruby_link.png",___("Blacklist Domain"))."&nbsp;".___("Blacklist Domain");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_BlacklistDomains]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("key.png",___("Dublettencheck"))."&nbsp;<font color=\"#ff0000\">".___("Auf Dubletten prüfen")."</font>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_DoubleCheck]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("key.png",___("Kein Update"))."&nbsp;<font color=\"#ff0000\">".___("Kein Update")."</font>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SkipEx]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("spellcheck.png",___("zur Prüfung vormerken"))."&nbsp;".___("Zur automatiscen Prüfung vormerken")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_MarkRecheck]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("gold_medal_1.png",___("Proofing"))."&nbsp;".___("Proofing")."";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Proof]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("pilcrow.png",___("Trennzeichen"));
$_MAIN_OUTPUT.= ___("Trennzeichen").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Delimiter]['html'];
$_MAIN_OUTPUT.= "&nbsp;";
$_MAIN_OUTPUT.= tm_icon("spellcheck.png",___("E-Mail-Prüfung"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Prüfung der E-Mail").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_ECheckImport]['html'];

$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td style=\"border-top:2px solid grey\" valign=\"top\">";
$_MAIN_OUTPUT.= tm_icon("control_fastforward.png",___("Offset"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Offset").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Offset]['html'];
$_MAIN_OUTPUT.= "&nbsp;";
$_MAIN_OUTPUT.= tm_icon("control_end.png",___("Limit"))."&nbsp;";
$_MAIN_OUTPUT.= ___("Limit").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Limit]['html'];

$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" align=\"right\">";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";
$_MAIN_OUTPUT.= "</table>";
$_MAIN_OUTPUT.= $Form->FORM[$FormularName]['foot'];
?>