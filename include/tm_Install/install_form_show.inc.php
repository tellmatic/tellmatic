<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        	*/
/* tellmatic, the newslettermachine                                             	*/
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
$FORM= "";
$FORM.= $Form->FORM[$FormularName]['head'];
//hidden fieldsnicht vergessen!
$FORM.= $Form->INPUT[$FormularName]['set']['html'];
$FORM.= $Form->INPUT[$FormularName]['accept']['html'];

$FORM.= "<div id=\"head_nav\" style=\"display:block;\">";
$FORM.= "<table border=0>";
$FORM.= "<thead>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"left\" colspan=2>";
$FORM.= $Form->FORM[$FormularName]['desc'];
$FORM.="<br><a href=\"#\" OnClick=\"makeVisBlock('user');makeInVisBlock('user_nav');makeVisBlock('dbs');makeInVisBlock('dbs_nav');makeVisBlock('smtp');makeInVisBlock('smtp_nav');makeVisBlock('install');makeInVisBlock('install_nav');\">".___("Alle Eingabefelder auf einmal anzeigen")."</a>";
$FORM.="<br><a href=\"#\" OnClick=\"makeVisBlock('user');makeVisBlock('user_nav');makeInVisBlock('dbs');makeVisBlock('dbs_nav');makeInVisBlock('smtp');makeVisBlock('smtp_nav');makeInVisBlock('install');makeVisBlock('install_nav');\">".___("Normale Ansicht")."</a>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</thead>";
$FORM.= "</table>";

$FORM.= "<div id=\"user\" style=\"display:block;\">";
$FORM.= "<table border=0>";
$FORM.= "<thead>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"left\" colspan=2>";
$FORM.= "<strong>".___("Benutzer")."</strong>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</thead>";
$FORM.= "<tbody>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Bitte geben Sie hier nun Ihren Benutzernamen für Tellmatic an.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_Name]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Geben Sie das gewünschte Passwort ein.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_Pass]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Bitte wiederholen Sie das Passwort.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_Pass2]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Wählen Sie Ihre Sprache für die Tellmatic Weboberfläche")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_Lang]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Geben Sie bitte eine gültige E-Mail-Adresse an. Diese Adresse können Sie später jederzeit ändern. Die angegebene Adresse dient als Standardadresse für Mailings etc.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_EMail]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"right\">";
$FORM.= "<div id=\"user_nav\" style=\"display:block;\">";
$FORM.="<a href=\"#\" OnClick=\"switchSection('user');switchSection('dbs');\">".___("Weiter (Datenbank)")."</a>";
$FORM.= "</div>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</tbody>";
$FORM.= "</table>";
$FORM.= "</div>";

$FORM.= "<div id=\"dbs\" style=\"display:none;\">";
$FORM.= "<table border=0>";
$FORM.= "<thead>";
$FORM.= "<strong>".___("Datenbank")."</strong>";
$FORM.= "</thead>";
$FORM.= "<tbody>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.="<br>". ___("Tragen Sie hier den Host-Namen oder die IP-Adresse Ihres Datenbankservers ein.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBHost]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Geben Sie die Portnummer an unter der der Datenbankserver Verbindungen entgegennimmt. (Standard ist 3306 für MySQL)")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBPort]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Wählen Sie Sockets wenn der Datenbankserver keine Verbindung über TCP/IP zulässt.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBSocket]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Wählen Sie den Typ der Datenbank (Standard is MyISAM für MySQL)")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBType]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Tragen Sie den Namen der Datenbank ein, sowie den namen des Datenbankbenutzers und das zugehörige Passwort.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBName]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBUser]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBPass]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Optional: Geben Sie einen TabellenPrefix ein. Dieser wird vor die Tabellennamen von tellmatic gesetzt. Wird ein Prefix angegeben, so fuegt Tellmatic automatisch ein _ an den Prefix an. Ein Prefix wird empfohlen wenn nur eine Datenbank zur Verfuegung steht und diese sich mehrere Programme teilen.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_DBTablePrefix]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"right\">";
$FORM.= "<div id=\"dbs_nav\" style=\"display:block;\">";
$FORM.="<a href=\"#\" OnClick=\"switchSection('dbs');switchSection('user');\">".___("Zurück (Benutzerdaten)")."</a>";
$FORM.="&nbsp;&nbsp;<a href=\"#\" OnClick=\"switchSection('dbs');switchSection('smtp');\">".___("Weiter (SMTP)")."</a>";
$FORM.= "</div>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</tbody>";
$FORM.= "</table>";
$FORM.= "</div>";

$FORM.= "<div id=\"smtp\" style=\"display:none;\">";
$FORM.= "<table border=0>";
$FORM.= "<thead>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"left\" colspan=2>";
$FORM.= "<strong>".___("Mail Server")."</strong>";
$FORM.= "<br>".___("Sie können die Daten für den SMTP/POP3/IMAP Host auch später ändern und weitere Server hinzufügen etc.")."<br>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</thead>";
$FORM.= "<tbody>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Geben Sie den Host-Namen Ihres E-Mail-Servers an. Z.Bsp.: mail.oneofmydomains.tld")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_SMTPHost]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Tragen Sie die Portnummer Ihres E-Mail-Servers ein unter der Verbindungen entgegen genommen werden. (Standard für SMTP ist 25, manchmal auch 587)")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_SMTPPort]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Benutzername für den SMTP-Server.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_SMTPUser]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Das Passwort für den SMTP-Benutzer.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_SMTPPass]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Bitte geben Sie hier den Domainnamen an mit der sich Tellmatic am SMTP-Server anmeldet. Im Regelfall ist das der Domainname der Webseite, ohne www.!")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_SMTPDomain]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Wählen Sie hier die Authentifizierungsmethode die Tellmatic verwenden soll um sich am SMTP Server anzumelden.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_SMTPAuth]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";

$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"right\">";
$FORM.= "<div id=\"smtp_nav\" style=\"display:block;\">";
$FORM.="<a href=\"#\" OnClick=\"switchSection('smtp');switchSection('dbs');\">".___("Zurück (Datenbank)")."</a>";
$FORM.="&nbsp;&nbsp;<a href=\"#\" OnClick=\"switchSection('smtp');switchSection('install');\">".___("Weiter (Installieren)")."</a>";
$FORM.= "</div>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</tbody>";
$FORM.= "</table>";
$FORM.= "</div>";

$FORM.= "<div id=\"install\" style=\"display:none;\">";
$FORM.= "<table border=0>";
$FORM.= "<thead>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"left\" colspan=2>";
$FORM.= "<strong>".___("Installation fertigstellen")."</strong>";
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</thead>";
$FORM.= "<tbody>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= $Form->INPUT[$FormularName][$InputName_RegMsg]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top>";
$FORM.= "<br>".___("Soll eine Installations-Mail an die Entwickler von Tellmatic gesendet werden? Die E-Mail enthält die oben angegebenen Informationen. Es werden keine sensitiven Daten wie Passwörter oder Benutzernamen verschickt. Die Daten werden nur zu statistischen Zwecken verwendet.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_Reg]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "<tr>";
$FORM.= "<td valign=top align=\"right\">";
$FORM.= "<div id=\"install_nav\" style=\"display:block;\">";
$FORM.="<a href=\"#\" OnClick=\"switchSection('install');switchSection('smtp');\">".___("Zurück (SMTP)")."</a>";
$FORM.= "</div>";
$FORM.= "<br>".___("Wenn Sie alle Angaben gemacht haben bestätigen Sie bitte hier. Tellmatic wird im nächsten Schritt installiert. Sollten Fehler auftreten wird dieses Formular wieder angezeigt und eine Warnmeldung bezüglich der aufgetretenen Fehler ausgegeben.")."<br>";
$FORM.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$FORM.= "</td>";
$FORM.= "</tr>";
$FORM.= "</tbody>";
$FORM.= "</table>";
$FORM.= "</div>";

$FORM.= $Form->FORM[$FormularName]['foot'];
?>