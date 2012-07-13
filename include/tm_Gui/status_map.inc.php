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

$_MAIN_DESCR=___("Karte");
$_MAIN_MESSAGE.="";

$FORMULAR=new tm_FRM();
$QUEUE=new tm_Q();

$InputName_CreateMap="create_map";
$$InputName_CreateMap=getVar($InputName_CreateMap);

$create=getVar("create");

////////////////////////////////////////////////////////////////////////////////////////
require_once (TM_INCLUDEPATH_GUI."/status_map_form.inc.php");
require_once (TM_INCLUDEPATH_GUI."/status_map_form_show.inc.php");
////////////////////////////////////////////////////////////////////////////////////////

if ($create==1) {

	require_once (TM_INCLUDEPATH_LIB_EXT."/geoip/geoip.inc");
	require_once (TM_INCLUDEPATH_LIB_EXT."/geoip/geoipcity.inc");
	require_once (TM_INCLUDEPATH_LIB_EXT."/geoip/geoipregionvars.php");
	$gi = geoip_open(TM_INCLUDEPATH_LIB_EXT."/geoip/GeoLiteCity.dat",GEOIP_STANDARD);

	//map oeffnen
	//usermap: neues bild erstellen, so gross wie map, png transparent!
	//frm_s auslesen,
	//	wenn ip !empty und !"0.0.0.0"
	// 	setze punkt in usermap
	//usermap speichern
	//neue earthmap, jpg
	//usermap ueber orig map legen und speichern
	//earthmap anzeigen
	$im_earthmap = ImageCreateFromJPEG(TM_IMGPATH."/earthmap_800.jpg");
	$im_size_x = imagesx($im_earthmap);
	$im_size_y = imagesy($im_earthmap);

	$im_flag = ImageCreateFromPNG(TM_ICONPATH."/house.png");
	$im_size_flag_x = imagesx($im_flag);
	$im_size_flag_y = imagesy($im_flag);

	#$im_map_user = ImageCreateTrueColor($im_size_x,$im_size_y); #ImageCreateTrueColor($im_size_x,$im_size_y);
	#$Black = ImageColorAllocate($im_map_user , 0,0,0);
	#$White = ImageColorAllocate($im_map_user , 255,255,255);
	#$Red = imagecolorallocate ($im_map_user, 255,0,0);
	#$Blue = imagecolorallocate ($im_map_user, 0,0,255);
	#$Yellow = imagecolorallocate ($im_map_user, 255,255,0);

	$Black = ImageColorAllocate($im_earthmap , 0,0,0);
	$White = ImageColorAllocate($im_earthmap , 255,255,255);
	$Red = imagecolorallocate ($im_earthmap, 255,0,0);
	$Blue = imagecolorallocate ($im_earthmap, 0,0,255);
	$Yellow = imagecolorallocate ($im_earthmap, 255,255,0);

	#ImageColorTransparent($im_map_user, $Black);
	#ImageFill($im_map_user, 0, 0, $Black);
	#Imageinterlace($im_map_user, 1);
	#ImageAlphaBlending($im_map_user, true);

	#$im_earthmap_user = ImageCreateTrueColor($im_size_x,$im_size_y);
	#ImageAlphaBlending($im_earthmap_user, true);

	//ip auslesen, koordinaten ermitteln und in x,y umsetzen, punkt markieren
	$geoip = geoip_record_by_addr($gi,getIP());
	#$pt = getlocationcoords($geoip->latitude, $geoip->longitude, $im_size_x, $im_size_y);
	//https://sourceforge.net/tracker/index.php?func=detail&aid=3114589&group_id=190396&atid=933192
	//bug fixed, id: 3114589
	//thx to tms-schmidt
	if (!is_null($geoip)) $pt = getlocationcoords($geoip->latitude, $geoip->longitude, $im_size_x, $im_size_y);
	
	/*****************************************************************/
	//Map Forms
	/*****************************************************************/
	if ($create_map=="subscriptions") {
		$map_earth_filename_sub="map_earth_user_subscriptions.jpg";
		$map_filename_sub="map_user_subscriptions.png";
		//punkte markieren
		#$FORMULAR->makeMap($im_map_user,$gi,0,$im_size_x,$im_size_y);
		$FORMULAR->makeMap($im_earthmap,$gi,0,$im_size_x,$im_size_y);
		$map_text=___("Geographische Verteilung der Anmeldungen")." ".TM_TODAY;
	}
	/*****************************************************************/
	//Map NL History
	/*****************************************************************/
	if ($create_map=="readers") {
		$map_earth_filename_sub="map_earth_user_history.jpg";
		$map_filename_sub="map_user_history.png";
		//punkte markieren
		#$QUEUE->makeRandomIP(10000);
		#$QUEUE->makeMap($im_map_user,$gi,0,$im_size_x,$im_size_y);
		$QUEUE->makeMap($im_earthmap,$gi,0,$im_size_x,$im_size_y);
		$map_text=___("Geographische Verteilung der Leser")." ".TM_TODAY;
	}
	/*****************************************************************/
	//Map NL History Radius Map
	/*****************************************************************/
	if ($create_map=="readers_rad") {
		$map_earth_filename_sub="map_earth_user_history_rad.jpg";
		$map_filename_sub="map_user_history_rad.png";
		//punkte markieren
		#$QUEUE->makeRandomIP(10000);
		#$QUEUE->makeMapWeight($im_map_user,$gi,0,$im_size_x,$im_size_y);
		$QUEUE->makeMapWeight($im_earthmap,$gi,0,$im_size_x,$im_size_y);
		$map_text=___("Geographische Verteilung der Leser")." ".TM_TODAY;
	}

	//flag setzen in usermap, aktueller Standort! Headquarter :)
	//https://sourceforge.net/tracker/index.php?func=detail&aid=3114589&group_id=190396&atid=933192
	//bug fixed, id: 3114589
	//thx to tms-schmidt
	if (!is_null($geoip)) {
		ImageCopy($im_earthmap, $im_flag,$pt["x"]-round($im_size_flag_x/2) ,$pt["y"]-$im_size_flag_x , 0, 0, $im_size_flag_x, $im_size_flag_y);
	}
	#ImageCopy($im_map_user, $im_flag,$pt["x"]-round($im_size_flag_x/2) ,$pt["y"]-$im_size_flag_x , 0, 0, $im_size_flag_x, $im_size_flag_y);
	//datum und beschreibung in usermap einfuegen
	#imagefilledrectangle($im_map_user, 0, $im_size_y-26, $im_size_x, $im_size_y-10, $White);
	#imagettftext ($im_map_user, 10, 0, 12, ($im_size_y-12), $Blue, TM_IMGPATH."/arial.ttf", $map_text );
	imagettftext ($im_earthmap, 10, 0, 12, ($im_size_y-12), $Blue, TM_IMGPATH."/arial.ttf", $map_text );
	//earthmap in earthmap_user kopieren
	#ImageCopy($im_earthmap_user, $im_earthmap,0, 0, 0, 0, $im_size_x, $im_size_y);
	//user_map in earthmap_user kopieren (markierungen)
	#ImageCopy($im_earthmap_user, $im_map_user,0, 0, 0, 0, $im_size_x, $im_size_y);
	#
	//usermap als png speichern
	#imagePNG($im_map_user,$tm_reportpath."/".$map_filename_sub);
	//user-earthmap als jpg speichern
	#ImageJPEG($im_earthmap_user,$tm_reportpath."/".$map_earth_filename_sub, 80);
	ImageJPEG($im_earthmap,$tm_reportpath."/".$map_earth_filename_sub, 80);

	#close resources
	ImageDestroy($im_flag);
	ImageDestroy($im_earthmap);
	#ImageDestroy($im_map_user);
	#ImageDestroy($im_earthmap_user);

}//create==1


/*****************************************************************/
$_MAIN_OUTPUT.=tm_message(___("Geographische Verteilung der Anmeldungen"));
if (file_exists($tm_reportpath."/map_earth_user_subscriptions.jpg")) {
	$_MAIN_OUTPUT.="<br><img src=\"".$tm_URL_FE."/".$tm_reportdir."/map_earth_user_subscriptions.jpg\" width=\"100%\" alt=\"".___("Karte")."\">";
} else {
	$_MAIN_OUTPUT.=tm_message_warning(___("Karte wurde noch nicht erstellt."));
}

$_MAIN_OUTPUT.="<br><br>";

$_MAIN_OUTPUT.=tm_message(___("Geographische Verteilung der Leser"));
if (file_exists($tm_reportpath."/map_earth_user_history.jpg")) {
	$_MAIN_OUTPUT.="<br><img src=\"".$tm_URL_FE."/".$tm_reportdir."/map_earth_user_history.jpg\" width=\"100%\" alt=\"".___("Karte")."\">";
} else {
	$_MAIN_OUTPUT.=tm_message_warning(___("Karte wurde noch nicht erstellt."));
}

$_MAIN_OUTPUT.="<br><br>";

$_MAIN_OUTPUT.=tm_message(___("Geographische Verteilung der Leser, Gewichtet"));
if (file_exists($tm_reportpath."/map_earth_user_history_rad.jpg")) {
	$_MAIN_OUTPUT.="<br><img src=\"".$tm_URL_FE."/".$tm_reportdir."/map_earth_user_history_rad.jpg\" width=\"100%\" alt=\"".___("Karte")."\">";
} else {
	$_MAIN_OUTPUT.=tm_message_warning(___("Karte wurde noch nicht erstellt."));
}
$_MAIN_OUTPUT.= "<br><br>";
?>