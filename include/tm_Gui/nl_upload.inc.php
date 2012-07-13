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

	$LINK=new tm_LNK();
	//dateiupload
	$file_content="";
	$body_tmp="";

	$uploaded_html=false;
	$uploaded_image1=false;
	$uploaded_attach1=false;

	//hochgeladene html datei
	$NL_Filename="nl_".date_convert_to_string($created).".html";
	//komplettes newsletter raw
	$NL_Filename_N="nl_".date_convert_to_string($created)."_n.html";
	//komplettes newsletter text
	$NL_Filename_T="nl_".date_convert_to_string($created)."_t.txt";
	//geparster Newsletter
	$NL_Filename_P="nl_".date_convert_to_string($created)."_p.html";
	//Bild 1
	$NL_Imagename1="nl_".date_convert_to_string($created)."_1.jpg";
	
	#image watermark and resize	
	//temporary image	
	$NL_Imagename1_tmp="nl_".date_convert_to_string($created)."_1_tmp.jpg";
	//source image, original, delete if resize and/or watermark!
	$NL_Imagename1_source="nl_".date_convert_to_string($created)."_1_src.jpg";
	//resized image
	$NL_Imagename1_resized="nl_".date_convert_to_string($created)."_1_resized.jpg";
	//watermark image
	$NL_Imagename1_watermarked="nl_".date_convert_to_string($created)."_1_watermarked.jpg";


	$watermark_image=TM_IMGPATH."/".$$InputName_ImageWatermarkImage;

	//2do: strip code, wird nicht mehr benoetigt! oder? was wird damit eigentlich noch gemacht, gar nix oder? evtl nach html body vor footer einfuegen oder so, oder optional nach header oder footer oder variabel!!!
	//html datei
	// Wurde wirklich eine Datei hochgeladen?
if($check) {
	if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
		// Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
		if(preg_match("/\." . $allowed_html_filetypes . "$/i", $_FILES["file"]["name"])) {
			// Datei auch nicht zu groß
			if($_FILES["file"]["size"] <= $max_upload_size) {
				// Alles OK -> Datei kopieren
					//http://www.php.net/manual/en/features.file-upload.php, use basename to preserve filename for multiple uploaded files.... if needed ;)
					if (move_uploaded_file($_FILES["file"]["tmp_name"], $tm_nlpath."/".$NL_Filename)) {
						$_MAIN_MESSAGE.= tm_message_success(___("HTML-Datei erfolgreich hochgeladen."));
						$_MAIN_MESSAGE.= tm_message_success(___("HTML-Code wurde angefügt."));
						$_MAIN_MESSAGE.= "<ul>".$_FILES["file"]["name"];
						$_MAIN_MESSAGE.= " / ".$_FILES["file"]["size"]." Byte";
						$_MAIN_MESSAGE.= ", ".$_FILES["file"]["type"];
						$_MAIN_MESSAGE.= tm_message(___("Datei gespeichert unter:"))." <a href=\"".$tm_URL_FE."/".$tm_nldir."/".$NL_Filename."\" target=\"_preview\">".$tm_nldir."/".$NL_Filename."</a>";
						$_MAIN_MESSAGE.= "</ul>";
						$uploaded_html=true;
					} else {
						$_MAIN_MESSAGE.= tm_message_error(___("Fehler beim kopieren."));
						$_MAIN_MESSAGE.= tm_message_error(___("HTML-Datei konnte nicht hochgeladen werden."));
						$check=false;
					}//copy
			} else {
				$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Die HTML-Datei darf nur eine Grösse von %s Byte haben."),$max_byte_size));
				$check=false;
			}//max size
		} else {
			$_MAIN_MESSAGE.= tm_message_error(___("Die HTML-Datei besitzt eine ungültige Endung."));
			$check=false;
		}//extension
	} else {
		$_MAIN_MESSAGE.= tm_message_notice(___("Kein HTML-Datei zum hochladen angegeben."));
	}//no file
}//check
	//ende upload html

	//image
	if($check) {
		//imageupload
		// Wurde wirklich eine Datei hochgeladen?
		if(is_uploaded_file($_FILES["image1"]["tmp_name"])) {
			// Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
			if(preg_match("/\." . $allowed_image_filetypes . "$/i", $_FILES["image1"]["name"])) {
				// Datei auch nicht zu groß
				if($_FILES["image1"]["size"] <= $max_upload_size) {
					// Alles OK -> Datei kopieren
					//http://www.php.net/manual/en/features.file-upload.php, use basename to preserve filename for multiple uploaded files.... if needed ;)
					//save uploaded source image, do watermark and resize
					if (move_uploaded_file($_FILES["image1"]["tmp_name"], $tm_nlimgpath."/".$NL_Imagename1_source)) {
						//copy source image to tmp image
						copy ($tm_nlimgpath."/".$NL_Imagename1_source,$tm_nlimgpath."/".$NL_Imagename1_tmp);
						//create thumb ?!
						if ($$InputName_ImageResize==1 && $$InputName_ImageResizeSize > 0) {
							//save resized image
							$rs=createThumb($tm_nlimgpath."/".$NL_Imagename1_tmp,$tm_nlimgpath."/".$NL_Imagename1_resized,$$InputName_ImageResizeSize,95);
							if ($rs) {
								//move resized image to tmp image
								rename($tm_nlimgpath."/".$NL_Imagename1_resized,$tm_nlimgpath."/".$NL_Imagename1_tmp);
								$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Bildgröße geändert in max. %s px."),$$InputName_ImageResizeSize));
							} else {
								$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Fehler beim Ändern der Bildgröße in max. %s px."),$$InputName_ImageResizeSize));
							}
						}
						#add watermark to image?						
						if ($$InputName_ImageWatermark==1) {
							if (file_exists($watermark_image)) {
								$wm=watermark($tm_nlimgpath."/".$NL_Imagename1_tmp, $tm_nlimgpath."/".$NL_Imagename1_watermarked, $watermark_image, 95);
								if ($wm[0]) {
									//move resized image to tmp image
									rename($tm_nlimgpath."/".$NL_Imagename1_watermarked,$tm_nlimgpath."/".$NL_Imagename1_tmp);
									$_MAIN_MESSAGE.= tm_message_success(sprintf(___("Wasserzeichen zum Bild hinzugefügt (%s)."),$$InputName_ImageWatermarkImage));
								} else {
									$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Fehler beim Hinzufügen des Wasserzeichens (%s)."),$$InputName_ImageWatermarkImage));
									$_MAIN_MESSAGE.= tm_message_error($wm[1]);//fehlermeldung aus createThumb
								}
							} else {
								$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Wasserzeichen existiert nicht (%s)."),$$InputName_ImageResizeSize));							
							}
						}
						//copy tmp image to nl image
						copy ($tm_nlimgpath."/".$NL_Imagename1_tmp,$tm_nlimgpath."/".$NL_Imagename1);
						//unlink() source and temp image;
						unlink ($tm_nlimgpath."/".$NL_Imagename1_tmp);
						unlink ($tm_nlimgpath."/".$NL_Imagename1_source);
						$_MAIN_MESSAGE.= tm_message_success(___("BILD-Datei erfolgreich hochgeladen."));
						$_MAIN_MESSAGE.= "<ul>".$_FILES["image1"]["name"];
						$_MAIN_MESSAGE.= " / ".$_FILES["image1"]["size"]." Byte";
						$_MAIN_MESSAGE.= ", ".$_FILES["image1"]["type"];
						$_MAIN_MESSAGE.= tm_message(___("Datei gespeichert unter:"))." <a href=\"".$tm_URL_FE."/".$tm_nlimgdir."/".$NL_Imagename1."\"  target=\"_preview\">".$tm_nlimgdir."/".$NL_Imagename1."</a>";
						$_MAIN_MESSAGE.= "</ul>";
						$uploaded_image1=true;
					} else {
						$_MAIN_MESSAGE.= tm_message_error(___("Fehler beim kopieren."));
						$_MAIN_MESSAGE.= tm_message_error(___("BILD-Datei konnte nicht hochgeladen werden."));
						$check=false;
					}//copy
				} else {
					$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Die BILD-Datei darf nur eine Grösse von %s Byte besitzen."),$max_byte_size));
					$check=false;
				}//max size
			} else {
				$_MAIN_MESSAGE.= tm_message_error(___("Die BILD-Datei besitzt eine ungültige Endung."));
				$check=false;
			}//extension
		} else {
			$_MAIN_MESSAGE.= tm_message_notice(___("Kein Bild zum hochladen angegeben."));
		}//no file
	}//check
	//ende upload image

	//attachement
	//attaxchement upload
	// Wurde wirklich eine Datei hochgeladen?
if($check) {
	if(is_uploaded_file($_FILES["attach1"]["tmp_name"])) {
		// Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
		//if(preg_match("/\." . $allowed_attach_filetypes . "$/i", $_FILES["attach1"]["name"])) {
			// Datei auch nicht zu groß
			if($_FILES["attach1"]["size"] <= $max_upload_size) {
				// Alles OK -> Datei kopieren
				$attachinfo=pathinfo($_FILES["attach1"]["name"]);
					$uploaded_attachement_new_name=$_FILES["attach1"]["name"];
					//http://www.php.net/manual/en/features.file-upload.php, use basename to preserve filename for multiple uploaded files.... if needed ;)
					if (move_uploaded_file($_FILES["attach1"]["tmp_name"], $tm_nlattachpath."/".$uploaded_attachement_new_name)) {
						$_MAIN_MESSAGE.= tm_message_success(___("Anhang erfolgreich hochgeladen."));
						$_MAIN_MESSAGE.= "<ul>".$_FILES["attach1"]["name"];
						$_MAIN_MESSAGE.= " / ".$_FILES["attach1"]["size"]." Byte";
						$_MAIN_MESSAGE.= ", ".$_FILES["attach1"]["type"];
						$_MAIN_MESSAGE.= tm_message(___("Datei gespeichert unter:"))." <a href=\"".$tm_URL_FE."/".$tm_nlattachdir."/".$uploaded_attachement_new_name."\"  target=\"_preview\">".$tm_nlattachdir."/".$uploaded_attachement_new_name."</a>";
						$_MAIN_MESSAGE.= "</ul>";
						$uploaded_attach1=true;
						$atc=count($attach_existing);
						$attach_existing[$atc]=$uploaded_attachement_new_name;
					} else {
						$_MAIN_MESSAGE.= tm_message_error(___("Fehler beim kopieren."));
						$_MAIN_MESSAGE.= tm_message_error(___("Anhang konnte nicht hochgeladen werden."));
						$check=false;
					}//copy
			} else {
				$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Der Anhang Datei darf nur eine Grösse von %s Byte haben."),$max_byte_size));
				$check=false;
			}//max size
		/*
		} else {
			$_MAIN_MESSAGE.= "<br>".___("Der Anhang besitzt eine ungültige Endung.");
			$check=false;
		}//extension
		*/
	} else {
		$_MAIN_MESSAGE.= tm_message_notice(___("Kein Anhang zum hochladen angegeben."));
	}//no file
}//check

//ende upload attachement

	//image
	//imageupload
	// Wurde wirklich eine Datei hochgeladen?
if($check) {
	$uploaded_track_image_new=false;
	if(is_uploaded_file($_FILES["track_image_new"]["tmp_name"])) {
		// Gültige Endung? ($ = Am Ende des Dateinamens) (/i = Groß- Kleinschreibung nicht berücksichtigen)
		if(preg_match("/\." . $allowed_trackimage_filetypes . "$/i", $_FILES["track_image_new"]["name"])) {
			// Datei auch nicht zu groß
			if($_FILES["track_image_new"]["size"] <= $max_upload_size) {
				// Alles OK -> Datei kopieren
				$uploaded_track_image_new_name=$_FILES["track_image_new"]["name"];
					//http://www.php.net/manual/en/features.file-upload.php, use basename to preserve filename for multiple uploaded files.... if needed ;)
					if (move_uploaded_file($_FILES["track_image_new"]["tmp_name"], $tm_nlimgpath."/".$uploaded_track_image_new_name)) {
						$_MAIN_MESSAGE.= tm_message_success(___("Track-BILD-Datei erfolgreich hochgeladen."));
						$_MAIN_MESSAGE.= "<ul>".$_FILES["track_image_new"]["name"];
						$_MAIN_MESSAGE.= " / ".$_FILES["track_image_new"]["size"]." Byte";
						$_MAIN_MESSAGE.= ", ".$_FILES["track_image_new"]["type"];
						$_MAIN_MESSAGE.= tm_message(___("Datei gespeichert unter:"))." <a href=\"".$tm_URL_FE."/".$tm_nlimgdir."/".$uploaded_track_image_new_name."\"  target=\"_preview\">".$tm_nlimgdir."/".$uploaded_track_image_new_name."</a>";
						$_MAIN_MESSAGE.= "</ul>";
						$uploaded_track_image_new=true;
					} else {
						$_MAIN_MESSAGE.= tm_message_error(___("Fehler beim kopieren."));
						$_MAIN_MESSAGE.= tm_message_error(___("Tracker-BILD-Datei konnte nicht hochgeladen werden."));
						$check=false;
					}//copy
			} else {
				$_MAIN_MESSAGE.= tm_message_error(sprintf(___("Die Tracker-BILD-Datei darf nur eine Grösse von %s Byte besitzen."),$max_byte_size));
				$check=false;
			}//max size
		} else {
			$_MAIN_MESSAGE.= tm_message_error(___("Die Tracker-BILD-Datei besitzt eine ungültige Endung."));
			$check=false;
		}//extension
	} else {
		$_MAIN_MESSAGE.= tm_message_notice(___("Kein Tracker-BILD zum hochladen angegeben."));
	}//no file
}//check

//ende upload image



	// kompletter content= $body + html content = $body_tmp
	if (file_exists($tm_nlpath."/".$NL_Filename)) {
		$file_content=file_get_contents($tm_nlpath."/".$NL_Filename);
	}
	
	$body_tmp=$body.$file_content;
	// wenn html datei hochgeladen, datei auslesen und an eingegebenen Content anhaengen! $body_tmp !
	// 	vorher/nachher option?
	//wir speichern nur ungeparsten content in der DB!!!! ---> $body
	//und in einer Datei! als Template fuer den geparsten Newsletter
	$body_tmp=stripslashes($body_tmp);
	write_file($tm_nlpath,$NL_Filename_N,$body_tmp);
	//template fuer textpart speichern
	$body_text_tmp=stripslashes($body_text);
	write_file($tm_nlpath,$NL_Filename_T,$body_text_tmp);
	//wird nun zur onlineversion geparsed!
	//geparster content, wird als nl file gespeichert nl+created bei new und update!
	//new: use parse function !
	//first fetch newsletter and pass [0] (which is array containing nl data) to parseNL function
	$NL_parse=$NEWSLETTER->getNL($nl_id,0,0,0,1);//mit content!
	//parseNL nutzt nun nicht mehr die files sonern die uebergebenen daten via data['nl'] 
	$body_p=$NEWSLETTER->parseNL(Array("nl"=>$NL_parse[0]),"html");//no adr record, this is anonymous online version

	//geparste nl datei speichern!
	write_file($tm_nlpath,$NL_Filename_P,$body_p);
?>