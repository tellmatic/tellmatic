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

$_MAIN_DESCR=___("Systemeinstellungen ändern");

if (!$user_is_admin) {
	$_MAIN_MESSAGE.=tm_message_warning(___("Sie haben keine Berechtigung dies zu tun"));
	$LOGIN->Logout();
}

//show log summary
//search for logs, only section
$search_log['object']="cfg";
require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");

if ($user_is_admin) {

	$_MAIN_MESSAGE.="";
	$_MAIN_OUTPUT.= "<br><strong>".___("Globale Einstellungen")."</strong><br>";

	$set=getVar("set");

	//field names for query
	$InputName_Name="name";//name
	$$InputName_Name=getVar($InputName_Name);

	$InputName_Lang="lang";
	$$InputName_Lang=getVar($InputName_Lang);

	$InputName_Style="style";
	$$InputName_Style=getVar($InputName_Style);

	$InputName_NotifySubscribe="notify_subscribe";
	$$InputName_NotifySubscribe=getVar($InputName_NotifySubscribe);

	$InputName_NotifyUnsubscribe="notify_unsubscribe";
	$$InputName_NotifyUnsubscribe=getVar($InputName_NotifyUnsubscribe);

	$InputName_NotifyMail="notify_mail";
	$$InputName_NotifyMail=getVar($InputName_NotifyMail);

	$InputName_MaxRetry="max_retry";
	$$InputName_MaxRetry=getVar($InputName_MaxRetry);

	$InputName_ECheckIntern="emailcheck_intern";
	$$InputName_ECheckIntern=getVar($InputName_ECheckIntern);

	$InputName_ECheckSubscribe="emailcheck_subscribe";
	$$InputName_ECheckSubscribe=getVar($InputName_ECheckSubscribe);

	$InputName_ECheckSendit="emailcheck_sendit";
	$$InputName_ECheckSendit=getVar($InputName_ECheckSendit);

	$InputName_ECheckCheckit="emailcheck_checkit";
	$$InputName_ECheckCheckit=getVar($InputName_ECheckCheckit);

	$InputName_CheckVersion="check_version";
	$$InputName_CheckVersion=getVar($InputName_CheckVersion);

	$InputName_TrackImageExisting="track_image_existing";//trackimage auswahl
	$$InputName_TrackImageExisting=getVar($InputName_TrackImageExisting);

	$InputName_TrackImageNew="track_image_new";//trackimage upload
	pt_register("POST","track_image_new");

	$InputName_RCPTName="rcpt_name";//name
	$$InputName_RCPTName=getVar($InputName_RCPTName);

	$InputName_UnsubUseCaptcha="unsubscribe_use_captcha";
	$$InputName_UnsubUseCaptcha=getVar($InputName_UnsubUseCaptcha);
	
	$InputName_UnsubDigitsCaptcha="unsubscribe_digits_captcha";
	$$InputName_UnsubDigitsCaptcha=getVar($InputName_UnsubDigitsCaptcha);
	
	$InputName_UnsubSendMail="unsubscribe_sendmail";
	$$InputName_UnsubSendMail=getVar($InputName_UnsubSendMail);
	
	$InputName_UnsubAction="unsubscribe_action";
	$$InputName_UnsubAction=getVar($InputName_UnsubAction);

	$InputName_UnsubHost="unsubscribe_host";
	$$InputName_UnsubHost=getVar($InputName_UnsubHost);
	
	$InputName_CheckitLimit="checkit_limit";
	$$InputName_CheckitLimit=getVar($InputName_CheckitLimit);

	$InputName_CheckitFromEmail="checkit_from_email";
	$$InputName_CheckitFromEmail=getVar($InputName_CheckitFromEmail);
	
	$InputName_CheckitAdrResetError="checkit_adr_reset_error";
	$$InputName_CheckitAdrResetError=getVar($InputName_CheckitAdrResetError);
	
	$InputName_CheckitAdrResetStatus="checkit_adr_reset_status";
	$$InputName_CheckitAdrResetStatus=getVar($InputName_CheckitAdrResetStatus);

	$InputName_BounceitLimit="bounceit_limit";
	$$InputName_BounceitLimit=getVar($InputName_BounceitLimit);
	
	$InputName_BounceitHost="bounceit_host";
	$$InputName_BounceitHost=getVar($InputName_BounceitHost);
	
	$InputName_BounceitAction="bounceit_action";
	$$InputName_BounceitAction=getVar($InputName_BounceitAction);

	$InputName_BounceitSearch="bounceit_search";
	$$InputName_BounceitSearch=getVar($InputName_BounceitSearch);
	
	$InputName_BounceitFilterTo="bounceit_filter_to";
	$$InputName_BounceitFilterTo=getVar($InputName_BounceitFilterTo);
	
	$InputName_BounceitFilterToEmail="bounceit_filter_to_email";
	$$InputName_BounceitFilterToEmail=getVar($InputName_BounceitFilterToEmail);

	$InputName_Proof="proof";
	$$InputName_Proof=getVar($InputName_Proof);

	$InputName_ProofURL="proof_url";
	$$InputName_ProofURL=getVar($InputName_ProofURL);

	$InputName_ProofTrigger="proof_trigger";
	$$InputName_ProofTrigger=getVar($InputName_ProofTrigger);

	$InputName_ProofPc="proof_pc";
	$$InputName_ProofPc=getVar($InputName_ProofPc);


	$CONFIG=new tm_CFG();
	$HOSTS=new tm_HOST();
	
	//read css directories and check for stylesheets and template directories
	$CSSDirs=Array();
	$CSSDirsTmp=getCSSDirectories(TM_PATH."/css");
	$css_c=count($CSSDirsTmp);
	$css_i=0;
	for ($css_cc=0; $css_cc < $css_c; $css_cc++) {
		$css_file=TM_PATH."/css/".$CSSDirsTmp[$css_cc]."/tellmatic.css";
		$tpl_dir=TM_TPLPATH."/".$CSSDirsTmp[$css_cc];
		if (file_exists($css_file)) {
			if (is_dir($tpl_dir)) {
				$CSSDirs[$css_i]["dir"]=$CSSDirsTmp[$css_cc];
				$CSSDirs[$css_i]["name"]=$CSSDirsTmp[$css_cc];
				$css_i++;
			}
		}
	}
	unset($CSSDirsTmp);
	
	
	
	$check=true;
	if ($set=="save") {

		//image
		//imageupload
		// Wurde wirklich eine Datei hochgeladen?
		$uploaded_track_image_new=false;
		if(is_uploaded_file($_FILES["track_image_new"]["tmp_name"])) {
			// Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
			if(preg_match("/\." . $allowed_trackimage_filetypes . "$/i", $_FILES["track_image_new"]["name"])) {
				// Datei auch nicht zu groß
				if($_FILES["track_image_new"]["size"] <= $max_upload_size) {
					// Alles OK -> Datei kopieren
					$uploaded_track_image_new_name=$_FILES["track_image_new"]["name"];
					if($check) {
						//http://www.php.net/manual/en/features.file-upload.php, use basename to preserve filename for multiple uploaded files.... if needed ;)
						if (move_uploaded_file($_FILES["track_image_new"]["tmp_name"], $tm_nlimgpath."/".$uploaded_track_image_new_name)) {
							$_MAIN_MESSAGE.= tm_message_success(___("Track-BILD-Datei erfolgreich hochgeladen."));
							$_MAIN_MESSAGE.= "<ul>".$_FILES["track_image_new"]["name"];
							$_MAIN_MESSAGE.= " / ".$_FILES["track_image_new"]["size"]." Byte";
							$_MAIN_MESSAGE.= ", ".$_FILES["track_image_new"]["type"];
							$_MAIN_MESSAGE.= tm_message(___("Datei gespeichert unter:"));
							$_MAIN_MESSAGE.=" <a href=\"".$tm_URL_FE."/".$tm_nlimgdir."/".$uploaded_track_image_new_name."\"  target=\"_preview\">".$tm_nlimgdir."/".$uploaded_track_image_new_name."</a>";
							$_MAIN_MESSAGE.= "</ul>";
							$uploaded_track_image_new=true;
						} else {
							$_MAIN_MESSAGE.= tm_message_error(___("Fehler beim kopieren."));
							$_MAIN_MESSAGE.= tm_message_error(___("Tracker-BILD-Datei konnte nicht hochgeladen werden."));
							$check=false;
						}//copy
					} else {
						$_MAIN_MESSAGE.= tm_message_error(___("Tracker-BILD-Datei konnte nicht hochgeladen werden."));
					}//check
				} else {
					$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Die Tracker-BILD-Datei darf nur eine Größe von %s Byte besitzen."),$max_upload_size));
					$check=false;
				}//max size
			} else {
				$_MAIN_MESSAGE.= tm_message_error(___("Die Tracker-BILD-Datei besitzt eine ungültige Endung."));
				$check=false;
			}//extension
		} else {
		}//no file
		//ende upload image
		//checkinput
		if (($notify_subscribe==1 || $notify_unsubscribe==1) && empty($notify_mail)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse für Benachrichtigungen darf nicht leer sein").".");}
		$check_mail=checkEmailAdr($notify_mail,$EMailcheck_Intern);
		if (!empty($notify_mail) && !$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die E-Mail-Adresse für Benachrichtigungen bei An-/Abmeldungen ist nicht gültig.")." ".$check_mail[1]);}

		if (!empty($$InputName_CheckitFromEmail)) {
			$check_mail=checkEmailAdr($$InputName_CheckitFromEmail,$EMailcheck_Intern);
			if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die Filter E-Mail-Adresse für die automatische Prüfung ist nicht gültig.")." ".$check_mail[1]);}
		}

		if ($$InputName_BounceitFilterTo==1) {
			$check_mail=checkEmailAdr($$InputName_BounceitFilterToEmail,$EMailcheck_Intern);
			if (!$check_mail[0]) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Die Filter E-Mail-Adresse für das  automatische Bouncemanagement ist nicht gültig.")." ".$check_mail[1]);}
		}

		if (!tm_DEMO() && $check) {
			$track_image="";
			if ($uploaded_track_image_new) {
				$track_image="/".$uploaded_track_image_new_name;
			} else {
				$track_image=$track_image_existing;
			}
			$CONFIG->updateCFG(Array(
					"siteid"=>TM_SITEID,
					"name"=>$name,
					"lang"=>$lang,
					"style"=>$style,
					"notify_mail"=>$notify_mail,
					"notify_subscribe"=>$notify_subscribe,
					"notify_unsubscribe"=>$notify_unsubscribe,
					"max_mails_retry"=>$max_retry,
					"emailcheck_intern"=>$emailcheck_intern,
					"emailcheck_subscribe"=>$emailcheck_subscribe,
					"emailcheck_sendit"=>$emailcheck_sendit,
					"emailcheck_checkit"=>$emailcheck_checkit,
					"check_version"=>$check_version,
					"track_image"=>$track_image,
					"rcpt_name"=>$rcpt_name,
					"unsubscribe_use_captcha"=>$unsubscribe_use_captcha,
					"unsubscribe_digits_captcha"=>$unsubscribe_digits_captcha,
					"unsubscribe_sendmail"=>$unsubscribe_sendmail,
					"unsubscribe_action"=>$unsubscribe_action,
					"unsubscribe_host"=>$unsubscribe_host,
					"checkit_limit"=>$checkit_limit,
					"checkit_from_email"=>$checkit_from_email,
					"checkit_adr_reset_error"=>$checkit_adr_reset_error,
					"checkit_adr_reset_status"=>$checkit_adr_reset_status,
					"bounceit_limit"=>$bounceit_limit,
					"bounceit_host"=>$bounceit_host,
					"bounceit_action"=>$bounceit_action,
					"bounceit_search"=>$bounceit_search,
					"bounceit_filter_to"=>$bounceit_filter_to,
					"bounceit_filter_to_email"=>$bounceit_filter_to_email,
					"proof"=>$proof,
					"proof_url"=>$proof_url,
					"proof_trigger"=>$proof_trigger,
					"proof_pc"=>$proof_pc,
					));
			$_MAIN_MESSAGE.=tm_message_success(___("Die Einstellungen wurden gespeichert und sind ab sofort gültig")."!");
			$action="adm_set";
		}
	} else {
		$C=$CONFIG->getCFG(TM_SITEID);
		$$InputName_Name=$C[0]['name'];//
		$$InputName_Lang=$C[0]['lang'];//
		$$InputName_Style=$C[0]['style'];//
		$$InputName_NotifySubscribe=$C[0]['notify_subscribe'];//
		$$InputName_NotifyUnsubscribe=$C[0]['notify_unsubscribe'];//
		$$InputName_NotifyMail=$C[0]['notify_mail'];//
		$$InputName_MaxRetry=$C[0]['max_mails_retry'];//
		$$InputName_ECheckIntern=$C[0]['emailcheck_intern'];//
		$$InputName_ECheckSubscribe=$C[0]['emailcheck_subscribe'];//
		$$InputName_ECheckSendit=$C[0]['emailcheck_sendit'];//
		$$InputName_ECheckCheckit=$C[0]['emailcheck_checkit'];//
		$$InputName_CheckVersion=$C[0]['check_version'];//
		$$InputName_TrackImageExisting=$C[0]['track_image'];
		$$InputName_RCPTName=$C[0]['rcpt_name'];
		$$InputName_UnsubUseCaptcha=$C[0]['unsubscribe_use_captcha'];
		$$InputName_UnsubDigitsCaptcha=$C[0]['unsubscribe_digits_captcha'];
		$$InputName_UnsubSendMail=$C[0]['unsubscribe_sendmail'];
		$$InputName_UnsubAction=$C[0]['unsubscribe_action'];
		$$InputName_UnsubHost=$C[0]['unsubscribe_host'];
		$$InputName_CheckitLimit=$C[0]['checkit_limit'];
		$$InputName_CheckitFromEmail=$C[0]['checkit_from_email'];
		$$InputName_CheckitAdrResetError=$C[0]['checkit_adr_reset_error'];
		$$InputName_CheckitAdrResetStatus=$C[0]['checkit_adr_reset_status'];
		$$InputName_BounceitLimit=$C[0]['bounceit_limit'];
		$$InputName_BounceitHost=$C[0]['bounceit_host'];
		$$InputName_BounceitAction=$C[0]['bounceit_action'];
		$$InputName_BounceitSearch=$C[0]['bounceit_search'];
		$$InputName_BounceitFilterTo=$C[0]['bounceit_filter_to'];
		$$InputName_BounceitFilterToEmail=$C[0]['bounceit_filter_to_email'];
		$$InputName_Proof=$C[0]['proof'];
		$$InputName_ProofURL=$C[0]['proof_url'];
		$$InputName_ProofTrigger=$C[0]['proof_trigger'];
		$$InputName_ProofPc=$C[0]['proof_pc'];
	}
	require_once (TM_INCLUDEPATH_GUI."/adm_set_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/adm_set_form_show.inc.php");
}//user_is_admin
?>