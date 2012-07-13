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
//inclue tm config //remove the #
#require_once ("./include/tm_config.inc.php");//change path to full path to tm_config if the script is not in tellmatic installation directory!
require_once(realpath(dirname(__FILE__))."/include/tm_config.inc.php");

//This is just a very simple example!

/***********************************************************/
//create a new newsletter:
/***********************************************************/
//all vars are required, even if they are used or not e.g. track_image, attach_ext
//subject
$subject='test_newsletter';//subject
//body
$body='test <b>test</b> <em>test</em>';
$body_text="test";
//creation date
$created=date("Y-m-d H:i:s");
//status
$status=1;//1:new
//active
$aktiv=1;//newsletter is active
//a link
$link="http://www.tellmatic.org";
//massmail or personalized email?
$massmail=0;//1:bcc, 0:personalized
//name of te author
$author="name";//name of author, e.g. login name from cms etc, you may add this user to tellmatic to make statistics (when it gets implemented)...
//newsletter group id
$nl_grp_id=1;//id of the group the newsletter should become a member of
//content type, send as html, text or both
$content_type="text/html";//text, html, text/html
//track image
$track_image="_global";//"_global", "_blank", "/image.png"
//recipients name
$rcpt_name="Recipient";
//attachements
$attach_existing=Array();

//new instance of newsletter class, //remove the #
#$NEWSLETTER=new tm_NL();
//add a newsletter, //remove the #
#$NEWSLETTER->addNL(
								Array(
									"subject"=>$subject,
									"body"=>$body,
									"body_text"=>$body_text,
									"aktiv"=>$aktiv,
									"status"=>$status,
									"massmail"=>$massmail,
									"link"=>$link,
									"created"=>$created,
									"author"=>$author,
									"grp_id"=>$nl_grp_id,
									"content_type"=>$content_type,
									"track_image"=>$track_image,
									"rcpt_name"=>$rcpt_name,
									"attachements"=>$attach_existing
									)
								);
//thats it

/***********************************************************/
//create a new address entry:
/***********************************************************/
$email="test123@virtualhost.de";
$f0="Frau";
$f1="Tanja";
$f2="Test";
$f3=$f4=$f5=$f6=$f7=$f8=$f9="f";

$adr_grp=Array("1","2");//array von address-gruppen ids denen die neue adresse angehoeren soll
$memo="text text text test text text text";
$aktiv=1;// 1|0
$created=date("Y-m-d H:i:s");
$author="admin";//$LOGIN->USER['name'];


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
			//gruppen denen die adr bereits  angehoert
			$old_adr_grp = $ADDRESS->getGroupID(0,$ADR[0]['id'],0);
			//neue gruppen nur die die neu sind, denen die adr noch nicht angehoert!
			//adr_grp=gruppen aus dem formular
			$new_adr_grp = array_diff($adr_grp,$old_adr_grp);
			$all_adr_grp = array_merge($old_adr_grp, $new_adr_grp);
			$adr_exists=true;
		}
		//////////////////////
		if ($adr_exists) {
			//wenn adresse existiert, adressdaten updaten!
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
			//hier newmemo benutzen da memo sonst doppelt!
			$ADDRESS->newMemo($ADR[0]['id'],$memo);
			//und neue referenzen zu neuen gruppen hinzufügen
			//$ADDRESS->addRef($ADR[0]['id'],$new_adr_grp);
			// ^^^ nur fuer den fall das daten nicht geupdated werden!!! sondern nur referenzen hinzugefuegt!
			//optional nachzuruesten und in den settings einstellbar :)
			echo "<br>".___("Diese E-Mail-Adresse existiert bereits. Die Daten wurden aktualisiert.");
			echo "<br>".___("Der Status der Adresse wurde nicht verändert!");
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
			echo "<br>".sprintf(___("Neue Adresse %s wurde angelegt."),"'<b>".display($email)."</b>'");
		}
?>