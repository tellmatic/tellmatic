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

$_MAIN_DESCR=___("Newsletter bearbeiten");
$_MAIN_MESSAGE.="";

$set=getVar("set");
$nl_id=getVar("nl_id");

//field names for query
$InputName_File="file";//datei
pt_register("POST","file");

$InputName_Image1="image1";//bild1
pt_register("POST","image1");

$InputName_Attach1="attach1";
pt_register("POST","attach1");

$InputName_AttachExisting="attach_existing";// auswahl anhang
$$InputName_AttachExisting=Array();
pt_register("POST",$InputName_AttachExisting);
$InputName_Name="subject";
$$InputName_Name=getVar($InputName_Name);

$InputName_Title="title";//titel f. webseite
$$InputName_Title=getVar($InputName_Title);

$InputName_TitleSub="title_sub";//subtitel2 f. webseite
$$InputName_TitleSub=getVar($InputName_TitleSub);

$InputName_Massmail="massmail";
$$InputName_Massmail=getVar($InputName_Massmail);

$InputName_Descr="body";
$$InputName_Descr=getVar($InputName_Descr,0);//varname,slashes? 0=no add slashes

$InputName_DescrText="body_text";//range from
$$InputName_DescrText=getVar($InputName_DescrText,0);//varname,slashes? 0=dont add slashes

$InputName_BodyHead="body_head";//body html version header
$$InputName_BodyHead=getVar($InputName_BodyHead,0);//varname,slashes? 0=dont add slashes

$InputName_BodyFoot="body_foot";//body html version footer
$$InputName_BodyFoot=getVar($InputName_BodyFoot,0);//varname,slashes? 0=dont add slashes

$InputName_Summary="summary";//summary html version f. webseite
$$InputName_Summary=getVar($InputName_Summary,0);//varname,slashes? 0=dont add slashes

$InputName_Aktiv="aktiv";
$$InputName_Aktiv=getVar($InputName_Aktiv);

$InputName_Template="is_template";//template?
$$InputName_Template=getVar($InputName_Template);

$InputName_Link="link";
$$InputName_Link=getVar($InputName_Link);

$InputName_ContentType="content_type";
$$InputName_ContentType=getVar($InputName_ContentType);

$InputName_Group="nl_grp_id";
$$InputName_Group=getVar($InputName_Group);

$InputName_TrackImageNew="track_image_new";//trackimage upload
pt_register("POST","track_image_new");

$InputName_TrackImageExisting="track_image_existing";//trackimage auswahl
$$InputName_TrackImageExisting=getVar($InputName_TrackImageExisting);

$InputName_TrackPerso="track_personalized";
$$InputName_TrackPerso=getVar($InputName_TrackPerso);

$InputName_UseInlineImages="use_inline_images";
$$InputName_UseInlineImages=getVar($InputName_UseInlineImages);

$InputName_RCPTName="rcpt_name";//name
$$InputName_RCPTName=getVar($InputName_RCPTName);

//watermark?
$InputName_ImageWatermark="image_watermark";//add watermark to image
$$InputName_ImageWatermark=getVar($InputName_ImageWatermark);

$InputName_ImageWatermarkImage="image_watermark_image";//what image to use as watermark
$$InputName_ImageWatermarkImage=getVar($InputName_ImageWatermarkImage);
if ($$InputName_ImageWatermarkImage=="") {
	$$InputName_ImageWatermarkImage="watermark.png";
}

//resize?
$InputName_ImageResize="image_resize";//resize image?
$$InputName_ImageResize=getVar($InputName_ImageResize);

$InputName_ImageResizeSize="image_resize_size";//what size?
$$InputName_ImageResizeSize=getVar($InputName_ImageResizeSize);

$NEWSLETTER=new tm_NL();
$NL=$NEWSLETTER->getNL($nl_id);

$created=$NL[0]['created'];
$updated=date("Y-m-d H:i:s");
$author=$LOGIN->USER['name'];

$check=true;
if ($set=="save") {
	//checkinput
	if (empty($subject)) {$check=false;$_MAIN_MESSAGE.=tm_message_error(___("Betreff sollte nicht leer sein."));}

		//upload ?!
		include_once (TM_INCLUDEPATH_GUI."/nl_upload.inc.php");

		$_MAIN_MESSAGE.= tm_message_success(___("Der Newsletter wurde gespeichert unter:")).
				"<ul>".
				tm_message(___("Vorlage:"))." <a href=\"".$tm_URL_FE."/".$tm_nldir."/".$NL_Filename_N."\" target=\"_preview\">".$tm_nldir."/".$NL_Filename_N."</a>".
				"<br>".
				tm_message(___("Text:"))." <a href=\"".$tm_URL_FE."/".$tm_nldir."/".$NL_Filename_T."\" target=\"_preview\">".$tm_nldir."/".$NL_Filename_T."</a>".
				"</ul>";
/*
				"<br>".
				tm_message(___("Online:"))." <a href=\"".$tm_URL_FE."/".$tm_nldir."/".$NL_Filename_P."\" target=\"_preview\">".$tm_nldir."/".$NL_Filename_P."</a>".
				"<br>".
				tm_message(___("Online:"))." <a href=\"".$tm_URL_FE."/view.php?nl_id=".$nl_id."\" target=\"_preview\">/view.php?nl_id=".$nl_id."</a>".
*/

	$track_image="";
	if ($uploaded_track_image_new) {
		$track_image="/".$uploaded_track_image_new_name;
	} else {
		$track_image=$track_image_existing;
	}

	if ($check) {
		$NEWSLETTER->updateNL(
							Array(
									"id"=>$nl_id,
									"subject"=>$subject,
									"title"=>$title,
									"title_sub"=>$title_sub,
									"body"=>$body,
									"body_text"=>$body_text,
									"body_head"=>$body_head,
									"body_foot"=>$body_foot,
									"summary"=>$summary,
									"aktiv"=>$aktiv,
									"is_template"=>$is_template,
									"massmail"=>$massmail,
									"link"=>$link,
									"created"=>$updated,
									"author"=>$author,
									"grp_id"=>$nl_grp_id,
									"content_type"=>$content_type,
									"track_image"=>$track_image,
									"track_personalized"=>$track_personalized,
									"use_inline_images"=>$use_inline_images,
									"rcpt_name"=>$rcpt_name,
									"attachements"=>$attach_existing,
									)
								);
		$_MAIN_MESSAGE.=tm_message_success(sprintf(___("Newsletter %s wurde aktualisiert."),"'".$subject."'"));
						
		$action="nl_list";
		require_once (TM_INCLUDEPATH_GUI."/nl_list.inc.php");
	} else {
		$body=stripslashes(strtr($body, $trans));
		$body_head=stripslashes(strtr($body_head, $trans));
		$body_foot=stripslashes(strtr($body_foot, $trans));
		require_once (TM_INCLUDEPATH_GUI."/nl_form.inc.php");
		require_once (TM_INCLUDEPATH_GUI."/nl_form_show.inc.php");
	}

} else {
	$NL=$NEWSLETTER->getNL($nl_id,0,0,0,1);
	$subject=$NL[0]['subject'];
	$title=$NL[0]['title'];
	$title_sub=$NL[0]['title_sub'];
	#$body=strtr($NL[0]['body'], $trans);
	$body=$NL[0]['body'];
	$body_text=$NL[0]['body_text'];
	$summary=$NL[0]['summary'];
	$aktiv=$NL[0]['aktiv'];
	$is_template=$NL[0]['is_template'];
	$massmail=$NL[0]['massmail'];
	$link=$NL[0]['link'];
	$nl_grp_id=$NL[0]['grp_id'];
	$content_type=$NL[0]['content_type'];
	$rcpt_name=$NL[0]['rcpt_name'];
	$track_personalized=$NL[0]['track_personalized'];
	$use_inline_images=$NL[0]['use_inline_images'];
	$$InputName_TrackImageExisting=$NL[0]['track_image'];
	//array umwandeln, der array aus get sieht anders aus als der fuer update und new!!!
	$atc=0;
	$attachements=$NL[0]['attachements'];
	foreach ($attachements as $attachfile) {
		$attach_existing[$atc]=$attachfile['file'];
		$atc++;
	}
	
	if (empty($body_head)) $body_head=TM_NL_HTML_START;
	if (empty($body_foot)) $body_foot=TM_NL_HTML_END;

	require_once (TM_INCLUDEPATH_GUI."/nl_form.inc.php");
	require_once (TM_INCLUDEPATH_GUI."/nl_form_show.inc.php");
}
?>