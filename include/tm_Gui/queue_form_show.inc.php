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
$_MAIN_OUTPUT.= "<table border=0>";
$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\">".tm_icon("newspaper.png",___("Newsletter"))."&nbsp;".___("Newsletter")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_NL]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"middle\">";
$_MAIN_OUTPUT.= "".___("versenden an:");
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "<td valign=\"top\">".tm_icon("group.png",___("Gruppen"))."&nbsp;".___("Gruppen")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Group]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>".tm_icon("calendar.png",___("Versanddatum"))."&nbsp;".___("Versand starten am:")."<br>";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SendAt]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SendAtTimeH]['html'];
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SendAtTimeM]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("ruby.png",___("Blacklist prüfen"))."&nbsp;".___("Blacklist prüfen");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Blacklist]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("medal_gold_1.png",___("Proofing"))."&nbsp;".___("Proofing");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Proof]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("user.png",___("Touch-Opt-In"))."&nbsp;".___("Touch-Opt-In");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Touch]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("bullet_star.png",___("Inline Images"),"","","","picture.png")."&nbsp;".___("Inline Images");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_UseInlineImages]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("cog.png",___("Versand automatisch starten / Empfängerliste automatisch aktualisieren"))."&nbsp;".___("Versand automatisch starten / Empfängerliste automatisch aktualisieren");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Autogen]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("hourglass.png",___("Versandliste sofort erstellen"))."&nbsp;".___("Versandliste sofort erstellen");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Send]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";

$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("email_add.png",___("Speichere Kopie ausgehender Mails auf IMAP Server"))."&nbsp;".___("Speichere Kopie ausgehender Mails auf IMAP Server");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_SaveImap]['html']."&nbsp;&nbsp;";
$_MAIN_OUTPUT.= tm_icon("server_database.png",___("IMAP"))."&nbsp;".___("IMAP-Server").":";
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_HostIDImap]['html'];
$_MAIN_OUTPUT.= "</td>";
$_MAIN_OUTPUT.= "</tr>";


$_MAIN_OUTPUT.= "<tr>";
$_MAIN_OUTPUT.= "<td valign=\"top\" colspan=3>";
$_MAIN_OUTPUT.= "<br>".tm_icon("server.png",___("Mail-Server"))."&nbsp;".___("Mail-Server");
$_MAIN_OUTPUT.= $Form->INPUT[$FormularName][$InputName_Host]['html']."&nbsp;&nbsp;";
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

$_MAIN_OUTPUT.= '
<script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "send_at_date",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "send_at_date",       // ID of the button
      date			: "",
      firstDay		: 0
    }
  );
</script>
';

?>