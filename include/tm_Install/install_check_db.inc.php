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

//2DO: use tm_message_success etc

$MESSAGE.=tm_message_notice(___("Verbindung zur Datenbank wird geprüft"));
if (tm_DEMO()) {
	$checkDB=true;
	$check=true;
	$MESSAGE.=tm_message_success(___("Die Verbindung zur Datenbank war erfolgreich"));
}

if ($check && !tm_DEMO()) {
	$db_connect_host=$db_host;
	if ($db_socket!=1) {
		$db_connect_host.=":".$db_port;
	}
    if(!@mysql_connect($db_connect_host, $db_user, $db_pass)) {
		$MESSAGE.=tm_message_error(___("Fehler! Es konnte keine Verbindung zur Datenbank aufgebaut werden"));
		$MESSAGE.=tm_message_error(mysql_error());
		$check=false;
		$checkDB=false;
	} else {
		$MESSAGE.=tm_message_success(___("Die Verbindung zur Datenbank war erfolgreich"));
		$checkDB=true;
		$check=true;
		//check for temporary table permissions		
		$tmp_code=rand(111,999);
		$tmp_tablename="tellmatic_temporary_table_test_".$tmp_code;
		$MESSAGE.=tm_message_notice(___("Prüfe auf Berechtigung zum erstellen temporärer Tabellen."));
		$MESSAGE.=tm_message_notice(sprintf(___("Temporäre Tabelle %s wird erstellt."),$tmp_tablename));
		$Query_tmptable="CREATE TEMPORARY TABLE ".$tmp_tablename." (".
						"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,".
						"name varchar(255)".
						")";
		$db_res=mysql_connect($db_connect_host, $db_user, $db_pass);
		$db_selected = mysql_select_db($db_name, $db_res);
		if ($db_selected) {	
		    $checkTmpTable = mysql_query($Query_tmptable,$db_res);
		    if (!$checkTmpTable) {//check if false, otherwise may contain values or true 
				$MESSAGE.=tm_message_error(___("Ein Fehler beim erstellen temporärer Tabellen ist aufgetreten. Bitte prüfen Sie die Berechtigungen des Datenbankbenutzers."));
				$MESSAGE.=tm_message_error(mysql_error());
				$checkTmpTable=false;
				$checkDB=false;
				$check=false;
		    } else {
				$MESSAGE.=tm_message_success(___("OK, temporäre Tabellen können erstellt werden."));
				$checkTmpTable=true;
		    }//checktmptable
		} else {
    		$check=false;
			$MESSAGE.=tm_message_error(___("SQL ERROR:").mysql_error());
		}
	}//mysqlconnect
}
?>