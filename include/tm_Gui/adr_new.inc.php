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

$_MAIN_DESCR=___("Neue Adresse eintragen");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$adr_id=0;
$created=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$offset=getVar("offset");
$limit=getVar("limit");
$s_email=getVar("s_email");
$s_status=getVar("s_status");
$s_author=getVar("s_author");
$s_aktiv=getVar("s_aktiv");
$adr_grp_id=getVar("adr_grp_id");
$st=getVar("st");
$si=getVar("si");
$si0=getVar("si0");
$si1=getVar("si1");
$si2=getVar("si2");

//field names for query
$InputName_Group="adr_grp";//range from
pt_register("POST","adr_grp");
if (!isset($adr_grp[0])) {
	$adr_grp[0]=getVar("adr_grp_id");
}

$InputName_Name="email";
$$InputName_Name=getVar($InputName_Name);

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv,0,1);

$InputName_Status="status";
$$InputName_Status=getVar($InputName_Status);

##werte aus sessions uebernehmen... oder uebergebene werte
//nur wenn nicht gespeichert wird, also nur bei neuaufruf des formulares
if ($set!="save") { 
 if (!empty($s_status)) {
 	$status=$s_status;
 } 
 if (!empty($s_email)) {
 	$email=$s_email;
 } 
}


$InputName_F0="f0";
$$InputName_F0=getVar($InputName_F0);
$InputName_F1="f1";
$$InputName_F1=getVar($InputName_F1);
$InputName_F2="f2";
$$InputName_F2=getVar($InputName_F2);
$InputName_F3="f3";
$$InputName_F3=getVar($InputName_F3);
$InputName_F4="f4";
$$InputName_F4=getVar($InputName_F4);
$InputName_F5="f5";
$$InputName_F5=getVar($InputName_F5);
$InputName_F6="f6";
$$InputName_F6=getVar($InputName_F6);
$InputName_F7="f7";
$$InputName_F7=getVar($InputName_F7);
$InputName_F8="f8";
$$InputName_F8=getVar($InputName_F8);
$InputName_F9="f9";
$$InputName_F9=getVar($InputName_F9);

$InputName_Memo="memo";
$$InputName_Memo=getVar($InputName_Memo);

$check=true;
if ($set=="save") {
	//checkinput
	if (empty($email)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse darf nicht leer sein."));}
	//email auf gueltigkeit pruefen
	$check_mail=checkEmailAdr($email,$EMailcheck_Intern);
	if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse ist nicht gültig.")." ".$check_mail[1]);}
	if ($check) {
		$ADDRESS=new tm_ADR();
		///////////////////////////
		//dublettencheck
		$search['email']=$email;
		//auf existenz pruefen und wenn email noch nicht existiert dann eintragen.
		$ADR=$ADDRESS->getAdr(0,0,0,0,$search);
		$ac=count($ADR);
		//oh! adresse ist bereits vorhanden!
		//wir diffen die gruppen und fuegen nur die referenzen hinzu die noch nicht existieren!
		$new_adr_grp=$adr_grp;
		$adr_exists=false;
		if ($ac>0) {
	//				$check=false;
			//gruppen denen die adr bereits  angehoert
			$old_adr_grp = $ADDRESS->getGroupID(0,$ADR[0]['id'],0);
			//neue gruppen nur die die neu sind, denen die adr noch nicht angehoert!
			//adr_grp=gruppen aus dem formular
			$new_adr_grp = array_diff($adr_grp,$old_adr_grp);
			$all_adr_grp = array_merge($old_adr_grp, $new_adr_grp);
	/*
			print_r($adr_grp);echo "<hr>";
			print_r($old_adr_grp);echo "<hr>";
			print_r($new_adr_grp);echo "<hr>";
			print_r($all_adr_grp);echo "<hr>";
	*/
			$adr_exists=true;
		}
		//////////////////////
		if ($adr_exists) {
			//wenn adresse existiert, adressdaten updaten!
			//
			$code=$ADR[0]['code'];
			$ADDRESS->updateAdr(Array(
				"id"=>$ADR[0]['id'],
				"email"=>$email,
				"aktiv"=>$aktiv,
				"created"=>$created,
				"author"=>$author,
				"f0"=>$f0,
				"f1"=>$f1,
				"f2"=>$f2,
				"f3"=>$f3,
				"f4"=>$f4,
				"f5"=>$f5,
				"f6"=>$f6,
				"f7"=>$f7,
				"f8"=>$f8,
				"f9"=>$f9
				),
				$all_adr_grp);
			//kein memo uebergeben f. update
			//"memo"=>$memo,
			//hier newmemo benutzen da memo sonst doppelt!
			$ADDRESS->newMemo($ADR[0]['id'],$memo);
			//nein, bei update keine statusaenderung da sonst alter status ueberschrieben wuerde..... das ist schlöcht bei unsubscribed adr :) status soll so bleiben.
			#$ADDRESS->setStatus($ADR[0]['id'],$status);
			//
			//und neue referenzen zu neuen gruppen hinzufügen
			//$ADDRESS->addRef($ADR[0]['id'],$new_adr_grp);
			// ^^^ nur fuer den fall das daten nicht geupdated werden!!! sondern nur referenzen hinzugefuegt!
			//optional nachzuruesten und in den settings einstellbar :)
			$_MAIN_MESSAGE.=tm_message_success(___("Diese E-Mail-Adresse existiert bereits. Die Daten wurden aktualisiert."));
			$_MAIN_MESSAGE.=tm_message_notice(___("Der Status der Adresse wurde nicht verändert!"));
		} else {
			//wenn adresse noch nicht existiert , neu anlegen
			srand((double)microtime()*1000000);
			$code=rand(111111,999999);
			$ADDRESS->addAdr(Array(
					"email"=>$email,
					"aktiv"=>$aktiv,
					"created"=>$created,
					"author"=>$author,
					"status"=>$status,
					"code"=>$code,
					"memo"=>$memo,
					"source"=>"user",
					"source_id"=>$LOGIN->USER['id'],
					"source_extern_id"=>0,
					"f0"=>$f0,
					"f1"=>$f1,
					"f2"=>$f2,
					"f3"=>$f3,
					"f4"=>$f4,
					"f5"=>$f5,
					"f6"=>$f6,
					"f7"=>$f7,
					"f8"=>$f8,
					"f9"=>$f9
					),
					$new_adr_grp);
			$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Neue Adresse %s wurde angelegt."),"'".$email."'"));
		}

		$action="adr_list";
		include_once (TM_INCLUDEPATH_GUI."/adr_list.inc.php");
	} else {
		require_once (TM_INCLUDEPATH_GUI."/adr_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/adr_form_show.inc.php");
	}
} else {
	require_once (TM_INCLUDEPATH_GUI."/adr_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/adr_form_show.inc.php");
}
?>