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

//aufruf: news_blank.png.php?h_id=&nl_id=&a_id=
/*
aufruf ohne parameter --> blindimage XxY Pixel oder auch global...? :)

aufruf mit parameter:
settings per newsletter -->
	blankimage ? --> blank png erzeigen
	eigenes bild --> extension auslesen, jpg oder png erzeugen
	global? --> globale settings auslesen
		blank? --> blank png erzeugen
		eigenes bild --> extension auslesen, jpg oder png erzeugen

	bild ausgeben.
*/

$h_id=getVar("h_id");
$nl_id=getVar("nl_id");
$a_id=getVar("a_id");

$TrackImageType="png";
$create_track_image=false;

if (checkid($nl_id)) {
	$NEWSLETTER=new tm_NL();
	//nl holen
	$NL=$NEWSLETTER->getNL($nl_id);
	//wenn newsletter gefunden, ok
	if (count($NL)>0) {
		$create_track_image=true;
	}
	//nl view counter ++
	$NEWSLETTER->addView($nl_id);
	//history id? dann in der historie auf view setzen!
	if (checkid($h_id)) {
		$QUEUE=new tm_Q();
		//nur der erste aufruf wird mit der ip versehen! ansonsten wuerde diese jedesmal ueberschrieben wenn der leser oder ein anderer das nl anschaut.
		$H=$QUEUE->getH($h_id);
		if (isset($H[0])) {//https://sourceforge.net/tracker/?func=detail&aid=3114571&group_id=190396&atid=933192
			if (empty($H[0]['ip']) || $H[0]['ip']=='0.0.0.0') {
				$QUEUE->setHIP($H[0]['id'], getIP());	//save ip
			}
			if ($H[0]['status']!=7) { //7:unsubscribed
				$QUEUE->setHStatus($h_id,3);	//view
			}
		}//isset H[0]
	}//checkid h_id
	//adressid? wenn ja status aendern und view zaehlen
	if (checkid($a_id)) {
		$ADDRESS=new tm_ADR();
		$ADR=$ADDRESS->getAdr($a_id);
		if (isset($ADR[0])) {//https://sourceforge.net/tracker/?func=detail&aid=3114571&group_id=190396&atid=933192
			//only set view status if not waiting status or unsubscribed // !5 && !11
			if ($ADR[0]['status']!=5 && $ADR[0]['status']!=11) {
				$ADDRESS->setStatus($a_id,4);	//view
			}
			//adr view counter ++
			$ADDRESS->addView($a_id);	//view
			//save memo
			$created=date("Y-m-d H:i:s");
			$memo="viewed (".$NL[0]['subject'].")";
			$ADDRESS->addMemo($a_id,$memo);
		}//isset ADR[0]
	}//checkid($a_id)
}

//wenn kein trackimage erzeugt werden soll, also kein newsletter gefunden wurde, blank erzeugen
if (!$create_track_image) {
	$Image=makeBlankImage(4,7);
}

//andernfalls, falls track image erzeugt werden soll, abhaengig vom newsletter...
if ($create_track_image) {
	//track image auslesen
	$imagefilename=$NL[0]['track_image'];
	//wenn "_global":
	if ($imagefilename=="_global") {
		//bildname uebergeben, evtl ist es "_blank"
		//einstellungen aus der config uebernehmen:
		$imagefilename=$C[0]['track_image'];
	}
	//"_blank"?
	if ($imagefilename=="_blank") {
		$Image=makeBlankImage(4,7);
	}

	//wenn kein blank oder global
	if ($imagefilename!="_blank" && $imagefilename!="_global" ) {
		$TrackImageType=strtolower(get_file_ext( $imagefilename ));
		if ($TrackImageType=="jpeg") $TrackImageType="jpg";
		$imagefile=$tm_nlimgpath."/".$imagefilename;
		//wenn die datei existiert:
		if (file_exists($imagefile)) {
			$Image=makeTrackImage($imagefile,$TrackImageType);
		} else {
			//wenn die datei nicht existiert, blank!
			$Image=makeBlankImage(4,7);
		}
	}
}



#Header("content-type: image/".$TrackImageType);
//    Image output
ob_start("trackimage_output_handler");

if ($TrackImageType=="png") {
	ImagePNG($Image);
}
if ($TrackImageType=="jpg") {
	ImageJPEG($Image);
}

ob_end_flush();

ImageDestroy($Image);

/////////////////////////////////
//    Output handler
function trackimage_output_handler($img) {
	global $TrackImageType;
    header('Content-type: image/'.$TrackImageType);
    header('Content-Length: ' . strlen($img));
    return $img;
}

//read and return existing image
function makeTrackImage($imagefile,$type="png") {
	if ($type=="png") {
		$ImageIn  = imagecreatefrompng($imagefile);
	}
	if ($type=="jpg") {
		$ImageIn  = imagecreatefromjpeg($imagefile);
	}
	$width=ImageSX($ImageIn);
	$height=ImageSY($ImageIn);

	//now create truecolor image and transfer
	$ImageOut = imagecreatetruecolor($width, $height);

	if ($type=="png") {

		//if not truecolor, convert png to truecolor png
		if (!imageistruecolor($ImageIn)) {
	        imagealphablending($ImageOut, false);
			imagecopy($ImageOut, $ImageIn, 0, 0, 0, 0, $width, $height);
    	    imagesavealpha($ImageOut, true);
			$bgColor = imagecolorallocate($ImageIn, 255,255,255);
			ImageColorTransparent($ImageOut, $bgColor);
			imagefill($ImageOut , 0,0 , $bgColor);

			/*
			$original_transparency=-1;

			$Image_transparency = imagecolortransparent($ImageIn);
			$original_transparency=$Image_transparency;
			if ($Image_transparency >= 0) {
			    //get the actual transparent color
			    $rgb = imagecolorsforindex($ImageIn, $Image_transparency);
			    $Image_transparency = ($rgb['red'] << 16) | ($rgb['green'] << 8) | $rgb['blue'];
			    //change the transparent color to black, since transparent goes to black anyways (no way to remove transparency in GIF)
			    imagecolortransparent($ImageIn, imagecolorallocate($ImageIn, 0, 0, 0));
			}
			*/
			#imagealphablending($ImageIn, true);
			#imagealphablending($ImageOut, true);
			#imagesavealpha($ImageIn, true);
			#imagesavealpha($ImageOut, true);
			#1:
			#$bgColor = imagecolorallocate($ImageIn, 255,255,255);
			#ImageColorTransparent($ImageOut, $bgColor);
			#imagefill($ImageOut , 0,0 , $bgColor);
			#2:
		    #$bgColor = imagecolorat($ImageIn,1,1);
			#imagecolortransparent($ImageOut, $bgColor);
		    #3:
		    #$trans_color = imagecolorallocatealpha($ImageIn, 0, 0, 0, 127);
			#ImageColorTransparent($ImageOut, $trans_color);

			#imagecopy($ImageOut, $ImageIn, 0, 0, 0, 0, $width, $height);
		    #imagefill($ImageOut, 0, 0, $trans_color);

			//remake transparency (if there was transparency)
			/*
			if ($original_transparency >= 0) {
				imagealphablending($ImageOut, false);
				imagesavealpha($ImageOut, true);
				for ($x = 0; $x < $w; $x++) {
					for ($y = 0; $y < $h; $y++) {
						if (imagecolorat($ImageOut, $x, $y) == $Image_transparency) {
			        		imagesetpixel($ImageOut, $x, $y, 127 << 24);
						}//if
					}//for
				}//for
				//And now $img is a true color image resource
			}// if orig trs
			*/


		}//not truecolor

		//true color png
		if (imageistruecolor($ImageIn)) {
			imageAlphaBlending($ImageIn, true);
			imageSaveAlpha($ImageIn, true);
			$ImageOut=$ImageIn;
		}//truecolor
	}//type=png

	if ($type=="jpg") {
		$ImageOut=$ImageIn;
	}//jpg
    #imagedestroy($imageIn);
	return $ImageOut;
}//function

function makeBlankImage($width=4,$height=7) {
	//bild generieren
	$ImageOut = ImageCreate($width,$height);
	$White = ImageColorAllocate($ImageOut, 255,255,255);
	$FC=$White;
	$BG=$White;
	$TC=$BG;
	ImageColorTransparent($ImageOut, $TC);
	ImageFill($ImageOut, 0, 0, $BG);
	Imageinterlace($ImageOut, 1);
	return $ImageOut;
}
?>