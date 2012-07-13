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

$InputName_Submit="submit";
$InputName_Reset="reset";

//Form
$Form=new tm_SimpleForm();
$FormularName="nl_new";
//make new Form
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormJS($FormularName," onSubmit=\"switchSection('div_loader');\" ");
//add a Description
$Form->set_FormDesc($FormularName,___("Neuen Newsletter erstellen"));
$Form->set_FormType($FormularName,"multipart/form-data");
$Form->new_Input($FormularName,"act", "hidden", $action);
$Form->new_Input($FormularName,"set", "hidden", "save");
$Form->new_Input($FormularName,"nl_id", "hidden", $nl_id);
//////////////////
//add inputfields and buttons....
//////////////////
//File 1, html
$Form->new_Input($FormularName,$InputName_File,"file", "");
$Form->set_InputJS($FormularName,$InputName_File," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_File,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_File,48,48);
$Form->set_InputDesc($FormularName,$InputName_File,___("HTML-Vorlage hochladen"));
$Form->set_InputReadonly($FormularName,$InputName_File,false);
$Form->set_InputOrder($FormularName,$InputName_File,9);
$Form->set_InputLabel($FormularName,$InputName_File,"");
//File 2, image
$Form->new_Input($FormularName,$InputName_Image1,"file", "");
$Form->set_InputJS($FormularName,$InputName_Image1," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Image1,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Image1,48,48);
$Form->set_InputDesc($FormularName,$InputName_Image1,___("Bild hochladen")." {IMAGE1}");
$Form->set_InputReadonly($FormularName,$InputName_Image1,false);
$Form->set_InputOrder($FormularName,$InputName_Image1,8);
$Form->set_InputLabel($FormularName,$InputName_Image1,"");
//File 3, Attachement
$Form->new_Input($FormularName,$InputName_Attach1,"file", "");
$Form->set_InputJS($FormularName,$InputName_Attach1," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Attach1,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Attach1,8,255);
$Form->set_InputDesc($FormularName,$InputName_Attach1,___("Anhang hochladen")." {ATTACH1}");
$Form->set_InputReadonly($FormularName,$InputName_Attach1,false);
$Form->set_InputOrder($FormularName,$InputName_Attach1,11);
$Form->set_InputLabel($FormularName,$InputName_Attach1,"");

//Watermark?
$Form->new_Input($FormularName,$InputName_ImageWatermark,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_ImageWatermark," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ImageWatermark,$$InputName_ImageWatermark);
$Form->set_InputStyleClass($FormularName,$InputName_ImageWatermark,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ImageWatermark,48,48);
$Form->set_InputDesc($FormularName,$InputName_ImageWatermark,___("Wasserzeichen hinzufügen"));
$Form->set_InputReadonly($FormularName,$InputName_ImageWatermark,false);
$Form->set_InputOrder($FormularName,$InputName_ImageWatermark,8);
$Form->set_InputLabel($FormularName,$InputName_ImageWatermark,"");

//Select Watermark Image
$Form->new_Input($FormularName,$InputName_ImageWatermarkImage,"select", "watermark.png");
$Form->set_InputJS($FormularName,$InputName_ImageWatermarkImage," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ImageWatermarkImage,basename($$InputName_ImageWatermarkImage));
$Form->set_InputStyleClass($FormularName,$InputName_ImageWatermarkImage,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ImageWatermarkImage,___("Wasserzeichen-Bild auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_ImageWatermarkImage,false);
$Form->set_InputOrder($FormularName,$InputName_ImageWatermarkImage,8);
$Form->set_InputLabel($FormularName,$InputName_ImageWatermarkImage,"");
$Form->set_InputSize($FormularName,$InputName_ImageWatermarkImage,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ImageWatermarkImage,false);
//add Data
$WatermarkImg_Files=getFiles(TM_IMGPATH) ;
$btsort=Array();
foreach ($WatermarkImg_Files as $field) {
	$btsort[]=$field['name'];
}
@array_multisort($btsort, SORT_ASC, $WatermarkImg_Files, SORT_ASC);
$ic= count($WatermarkImg_Files);
for ($icc=0; $icc < $ic; $icc++) {
		//only png files for watermark are accepted
		if (preg_match ("/.png$/i", $WatermarkImg_Files[$icc]['name']) || preg_match ("/.PNG$/i", $WatermarkImg_Files[$icc]['name'])) {
			$Form->add_InputOption($FormularName,$InputName_ImageWatermarkImage,$WatermarkImg_Files[$icc]['name'],display($WatermarkImg_Files[$icc]['name']));
		}
}

//Resize?
$Form->new_Input($FormularName,$InputName_ImageResize,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_ImageResize," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ImageResize,$$InputName_ImageResize);
$Form->set_InputStyleClass($FormularName,$InputName_ImageResize,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ImageResize,48,48);
$Form->set_InputDesc($FormularName,$InputName_ImageResize,___("Größe ändern"));
$Form->set_InputReadonly($FormularName,$InputName_ImageResize,false);
$Form->set_InputOrder($FormularName,$InputName_ImageResize,8);
$Form->set_InputLabel($FormularName,$InputName_ImageResize,"");

//Resize Size
$Form->new_Input($FormularName,$InputName_ImageResizeSize,"text", display($$InputName_ImageResizeSize));
$Form->set_InputJS($FormularName,$InputName_ImageResizeSize," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_ImageResizeSize,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_ImageResizeSize,5,3);
$Form->set_InputDesc($FormularName,$InputName_ImageResizeSize,___("Neue Größe"));
$Form->set_InputReadonly($FormularName,$InputName_ImageResizeSize,false);
$Form->set_InputOrder($FormularName,$InputName_ImageResizeSize,8);
$Form->set_InputLabel($FormularName,$InputName_ImageResizeSize,"");

//existing attachements
$Form->new_Input($FormularName,$InputName_AttachExisting,"select", "");
$Form->set_InputJS($FormularName,$InputName_AttachExisting," onChange=\"flash('submit','#ff0000');\" ");
if (!empty($$InputName_AttachExisting)) {
	#$Form->set_InputDefault($FormularName,$InputName_AttachExisting,basename($$InputName_AttachExisting));
}
$Form->set_InputStyleClass($FormularName,$InputName_AttachExisting,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_AttachExisting,___("Anhänge auswählen, Strg/Ctrl+Klick f. Mehrfachauswahl"));
$Form->set_InputReadonly($FormularName,$InputName_AttachExisting,false);
$Form->set_InputOrder($FormularName,$InputName_AttachExisting,22);
$Form->set_InputLabel($FormularName,$InputName_AttachExisting,"");
$Form->set_InputSize($FormularName,$InputName_AttachExisting,0,20);
$Form->set_InputMultiple($FormularName,$InputName_AttachExisting,true);
//add Data
$Attm_Dirs=getDirectories($tm_nlattachpath) ;
$btsort=Array();
foreach ($Attm_Dirs as $field) {
	$btsort[]=$field['name'];
}
@array_multisort($btsort, SORT_ASC, $Attm_Dirs, SORT_ASC);
$dc= count($Attm_Dirs);
for ($dcc=0; $dcc < $dc; $dcc++) {
	$a_path=$tm_nlattachpath;
	if ($Attm_Dirs[$dcc]['name']!="CVS") {
		if (!empty($Attm_Dirs[$dcc]['name'])) {
			$a_path.="/".$Attm_Dirs[$dcc]['name'];
		}
		$Attm_Files=getFiles($a_path) ;
		$btsort=Array();
		foreach ($Attm_Files as $field) {
			$btsort[]=$field['name'];
		}
		@array_multisort($btsort, SORT_ASC, $Attm_Files, SORT_ASC);
		$ic= count($Attm_Files);
		for ($icc=0; $icc < $ic; $icc++) {
			if ($Attm_Files[$icc]['name']!=".htaccess" && $Attm_Files[$icc]['name']!="index.php" && $Attm_Files[$icc]['name']!="index.html") {
				$a_file=$Attm_Files[$icc]['name'];
				if (!empty($Attm_Dirs[$dcc]['name']) && $Attm_Dirs[$dcc]['name']!=".") {
					$a_file=$Attm_Dirs[$dcc]['name']."/".$Attm_Files[$icc]['name'];
				}

				$Form->add_InputOption($FormularName,$InputName_AttachExisting,$a_file,display($Attm_Files[$icc]['name'])." (".formatFileSize($Attm_Files[$icc]['size']).")",display($Attm_Dirs[$dcc]['name']));
			}//if Attm name
		}//for  lcc
	}//if attmdir name
}//for dcc

//Subject
$Form->new_Input($FormularName,$InputName_Name,"text", display($$InputName_Name));
$Form->set_InputJS($FormularName,$InputName_Name," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Name,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Name,48,255);
$Form->set_InputDesc($FormularName,$InputName_Name,___("Erscheint als Betreff in der e-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_Name,false);
$Form->set_InputOrder($FormularName,$InputName_Name,1);
$Form->set_InputLabel($FormularName,$InputName_Name,"");

//Titel
$Form->new_Input($FormularName,$InputName_Title,"text", display($$InputName_Title));
$Form->set_InputJS($FormularName,$InputName_Title," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Title,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Title,48,255);
$Form->set_InputDesc($FormularName,$InputName_Title,___("Titel (z.Bsp. zur Anzeige auf der Webseite)"));
$Form->set_InputReadonly($FormularName,$InputName_Title,false);
$Form->set_InputOrder($FormularName,$InputName_Title,2);
$Form->set_InputLabel($FormularName,$InputName_Title,"");

//SubTitel
$Form->new_Input($FormularName,$InputName_TitleSub,"text", display($$InputName_TitleSub));
$Form->set_InputJS($FormularName,$InputName_TitleSub," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_TitleSub,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_TitleSub,48,255);
$Form->set_InputDesc($FormularName,$InputName_TitleSub,___("Sub-Titel (z.Bsp. zur Anzeige auf der Webseite)"));
$Form->set_InputReadonly($FormularName,$InputName_TitleSub,false);
$Form->set_InputOrder($FormularName,$InputName_TitleSub,3);
$Form->set_InputLabel($FormularName,$InputName_TitleSub,"");

//Link
$Form->new_Input($FormularName,$InputName_Link,"text", display($$InputName_Link));
$Form->set_InputJS($FormularName,$InputName_Link," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Link,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Link,48,1024);
$Form->set_InputDesc($FormularName,$InputName_Link,___("Link")." {LINK1}");
$Form->set_InputReadonly($FormularName,$InputName_Link,false);
$Form->set_InputOrder($FormularName,$InputName_Link,7);
$Form->set_InputLabel($FormularName,$InputName_Link,"");

//Aktiv
$Form->new_Input($FormularName,$InputName_Aktiv,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Aktiv," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Aktiv,$$InputName_Aktiv);
$Form->set_InputStyleClass($FormularName,$InputName_Aktiv,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Aktiv,48,48);
$Form->set_InputDesc($FormularName,$InputName_Aktiv,___("Aktiv"));
$Form->set_InputReadonly($FormularName,$InputName_Aktiv,false);
$Form->set_InputOrder($FormularName,$InputName_Aktiv,1);
$Form->set_InputLabel($FormularName,$InputName_Aktiv,"");

//Template?
$Form->new_Input($FormularName,$InputName_Template,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Template," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Template,$$InputName_Template);
$Form->set_InputStyleClass($FormularName,$InputName_Template,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_Template,48,48);
$Form->set_InputDesc($FormularName,$InputName_Template,___("Aktiv"));
$Form->set_InputReadonly($FormularName,$InputName_Template,false);
$Form->set_InputOrder($FormularName,$InputName_Template,1);
$Form->set_InputLabel($FormularName,$InputName_Template,"");


//Massenmail!?
//Content_Type
$Form->new_Input($FormularName,$InputName_Massmail,"select", "");
$Form->set_InputJS($FormularName,$InputName_Massmail," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Massmail,$$InputName_Massmail);
$Form->set_InputStyleClass($FormularName,$InputName_Massmail,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Massmail,___("Mailing-Typ"));
$Form->set_InputReadonly($FormularName,$InputName_Massmail,false);
$Form->set_InputOrder($FormularName,$InputName_Massmail,6);
$Form->set_InputLabel($FormularName,$InputName_Massmail,"");
$Form->set_InputSize($FormularName,$InputName_Massmail,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Massmail,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_Massmail,0,"Personalisiertes Newsletter (einzelne Mails)");
#$Form->add_InputOption($FormularName,$InputName_Massmail,1,"Massenmailing (per BCC, nicht personalisiert)");

//Content html
$Form->new_Input($FormularName,$InputName_Descr,"textarea", $$InputName_Descr);
$Form->set_InputJS($FormularName,$InputName_Descr," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Descr,"mFormTextarea_Content","mFormTextareaFocus_Content");
$Form->set_InputSize($FormularName,$InputName_Descr,180,50);
$Form->set_InputDesc($FormularName,$InputName_Descr,___("Newsletter-Text")." (html)");
$Form->set_InputReadonly($FormularName,$InputName_Descr,false);
$Form->set_InputOrder($FormularName,$InputName_Descr,21);
$Form->set_InputLabel($FormularName,$InputName_Descr,"");

//Content html Head
$Form->new_Input($FormularName,$InputName_BodyHead,"textarea", $$InputName_BodyHead);
$Form->set_InputJS($FormularName,$InputName_BodyHead," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_BodyHead,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_BodyHead,180,10);
$Form->set_InputDesc($FormularName,$InputName_BodyHead,___("HTML Header"));
$Form->set_InputReadonly($FormularName,$InputName_BodyHead,false);
$Form->set_InputOrder($FormularName,$InputName_BodyHead,21);
$Form->set_InputLabel($FormularName,$InputName_BodyHead,"");

//Content html Foot
$Form->new_Input($FormularName,$InputName_BodyFoot,"textarea", $$InputName_BodyFoot);
$Form->set_InputJS($FormularName,$InputName_BodyFoot," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_BodyFoot,"mFormTextarea","mFormTextareaFocus");
$Form->set_InputSize($FormularName,$InputName_BodyFoot,180,10);
$Form->set_InputDesc($FormularName,$InputName_BodyFoot,___("HTML Footer"));
$Form->set_InputReadonly($FormularName,$InputName_BodyFoot,false);
$Form->set_InputOrder($FormularName,$InputName_BodyFoot,21);
$Form->set_InputLabel($FormularName,$InputName_BodyFoot,"");


//Content text
$Form->new_Input($FormularName,$InputName_DescrText,"textarea", $$InputName_DescrText);
$Form->set_InputJS($FormularName,$InputName_DescrText," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_DescrText,"mFormTextarea_Content","mFormTextareaFocus_Content");
$Form->set_InputSize($FormularName,$InputName_DescrText,180,30);
$Form->set_InputDesc($FormularName,$InputName_DescrText,___("Newsletter-Text")." (text)");
$Form->set_InputReadonly($FormularName,$InputName_DescrText,false);
$Form->set_InputOrder($FormularName,$InputName_DescrText,20);
$Form->set_InputLabel($FormularName,$InputName_DescrText,"");

//Summary
$Form->new_Input($FormularName,$InputName_Summary,"textarea", $$InputName_Summary);
$Form->set_InputJS($FormularName,$InputName_Summary," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_Summary,"mFormTextarea_Content","mFormTextareaFocus_Content");
$Form->set_InputSize($FormularName,$InputName_Summary,180,8);
$Form->set_InputDesc($FormularName,$InputName_Summary,___("Zusammenfassung")." (html)");
$Form->set_InputReadonly($FormularName,$InputName_Summary,false);
$Form->set_InputOrder($FormularName,$InputName_Summary,22);
$Form->set_InputLabel($FormularName,$InputName_Summary,"");


//Gruppe
$Form->new_Input($FormularName,$InputName_Group,"select", "");
$Form->set_InputJS($FormularName,$InputName_Group," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Group,$nl_grp_id);
$Form->set_InputStyleClass($FormularName,$InputName_Group,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Group,___("Newsletter-Gruppe waehlen"));
$Form->set_InputReadonly($FormularName,$InputName_Group,false);
$Form->set_InputOrder($FormularName,$InputName_Group,6);
$Form->set_InputLabel($FormularName,$InputName_Group,"");
$Form->set_InputSize($FormularName,$InputName_Group,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Group,false);
//add Data
$NEWSLETTER=new tm_NL();
$GRP=$NEWSLETTER->getGroup();
$acg=count($GRP);
for ($accg=0; $accg<$acg; $accg++)
{
	$Form->add_InputOption($FormularName,$InputName_Group,$GRP[$accg]['id'],$GRP[$accg]['name']);
}

//Select existing Trackimage
$Form->new_Input($FormularName,$InputName_TrackImageExisting,"select", "");
$Form->set_InputJS($FormularName,$InputName_TrackImageExisting," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_TrackImageExisting,basename($$InputName_TrackImageExisting));
$Form->set_InputStyleClass($FormularName,$InputName_TrackImageExisting,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_TrackImageExisting,___("Blind-/Tracking-Bild auswählen"));
$Form->set_InputReadonly($FormularName,$InputName_TrackImageExisting,false);
$Form->set_InputOrder($FormularName,$InputName_TrackImageExisting,10);
$Form->set_InputLabel($FormularName,$InputName_TrackImageExisting,"");
$Form->set_InputSize($FormularName,$InputName_TrackImageExisting,0,1);
$Form->set_InputMultiple($FormularName,$InputName_TrackImageExisting,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_TrackImageExisting,"_global","-- GLOBAL --");
$Form->add_InputOption($FormularName,$InputName_TrackImageExisting,"_blank","-- BLANK --");
$TrackImg_Files=getFiles($tm_nlimgpath) ;
$btsort=Array();
foreach ($TrackImg_Files as $field) {
	$btsort[]=$field['name'];
}
@array_multisort($btsort, SORT_ASC, $TrackImg_Files, SORT_ASC);
$ic= count($TrackImg_Files);
for ($icc=0; $icc < $ic; $icc++) {
	if ($TrackImg_Files[$icc]['name']!=".htaccess" && $TrackImg_Files[$icc]['name']!="index.php" && $TrackImg_Files[$icc]['name']!="index.html") {
		$Form->add_InputOption($FormularName,$InputName_TrackImageExisting,$TrackImg_Files[$icc]['name'],display($TrackImg_Files[$icc]['name']));
	}
}

//upload new trackingimage
$Form->new_Input($FormularName,$InputName_TrackImageNew,"file", "");
$Form->set_InputJS($FormularName,$InputName_TrackImageNew," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_TrackImageNew,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_TrackImageNew,48,48);
$Form->set_InputDesc($FormularName,$InputName_TrackImageNew,___("neues Bild hochladen")." {IMAGE1}");
$Form->set_InputReadonly($FormularName,$InputName_TrackImageNew,false);
$Form->set_InputOrder($FormularName,$InputName_TrackImageNew,10);
$Form->set_InputLabel($FormularName,$InputName_TrackImageNew,"");


//Track personalized
$Form->new_Input($FormularName,$InputName_TrackPerso,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_TrackPerso," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_TrackPerso,$$InputName_TrackPerso);
$Form->set_InputStyleClass($FormularName,$InputName_TrackPerso,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_TrackPerso,48,48);
$Form->set_InputDesc($FormularName,$InputName_TrackPerso,___("Tracking personalisiert"));
$Form->set_InputReadonly($FormularName,$InputName_TrackPerso,false);
$Form->set_InputOrder($FormularName,$InputName_TrackPerso,10);
$Form->set_InputLabel($FormularName,$InputName_TrackPerso,"");

//use_inline_images
$Form->new_Input($FormularName,$InputName_UseInlineImages,"select", "");
$Form->set_InputJS($FormularName,$InputName_UseInlineImages," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_UseInlineImages,$$InputName_UseInlineImages);
$Form->set_InputStyleClass($FormularName,$InputName_UseInlineImages,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_UseInlineImages,___("Inline Images"));
$Form->set_InputReadonly($FormularName,$InputName_UseInlineImages,false);
$Form->set_InputOrder($FormularName,$InputName_UseInlineImages,11);
$Form->set_InputLabel($FormularName,$InputName_UseInlineImages,"");
$Form->set_InputSize($FormularName,$InputName_UseInlineImages,0,1);
$Form->set_InputMultiple($FormularName,$InputName_UseInlineImages,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"0",___("Aus"));
$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"1",___("nur lokale Bilder"));
#$Form->add_InputOption($FormularName,$InputName_UseInlineImages,"2",___("alle Bilder als Inline"));

//Content_Type
$Form->new_Input($FormularName,$InputName_ContentType,"select", "");
$Form->set_InputJS($FormularName,$InputName_ContentType," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_ContentType,$$InputName_ContentType);
$Form->set_InputStyleClass($FormularName,$InputName_ContentType,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_ContentType,___("Format"));
$Form->set_InputReadonly($FormularName,$InputName_ContentType,false);
$Form->set_InputOrder($FormularName,$InputName_ContentType,5);
$Form->set_InputLabel($FormularName,$InputName_ContentType,"");
$Form->set_InputSize($FormularName,$InputName_ContentType,0,1);
$Form->set_InputMultiple($FormularName,$InputName_ContentType,false);
//add Data
$Form->add_InputOption($FormularName,$InputName_ContentType,"text/html","TEXT/HTML");
$Form->add_InputOption($FormularName,$InputName_ContentType,"html","HTML");
$Form->add_InputOption($FormularName,$InputName_ContentType,"text","TEXT");

//rcpt_name etc
$Form->new_Input($FormularName,$InputName_RCPTName,"text", display($$InputName_RCPTName));
$Form->set_InputJS($FormularName,$InputName_RCPTName," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputStyleClass($FormularName,$InputName_RCPTName,"mFormText","mFormTextFocus");
$Form->set_InputSize($FormularName,$InputName_RCPTName,48,256);
$Form->set_InputDesc($FormularName,$InputName_RCPTName,___("Erscheint als Empfängername in der E-Mail"));
$Form->set_InputReadonly($FormularName,$InputName_RCPTName,false);
$Form->set_InputOrder($FormularName,$InputName_RCPTName,4);
$Form->set_InputLabel($FormularName,$InputName_RCPTName,"");

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Speichern"));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");
//$Form->set_InputJS($FormularName,$InputName_Submit," onClick=\"switchSection('div_loader');\" ");

//a reset button
$Form->new_Input($FormularName,$InputName_Reset,"reset",___("Reset"));
$Form->set_InputStyleClass($FormularName,$InputName_Reset,"mFormReset","mFormResetFocus");
$Form->set_InputDesc($FormularName,$InputName_Reset,___("Reset"));
$Form->set_InputReadonly($FormularName,$InputName_Reset,false);
$Form->set_InputOrder($FormularName,$InputName_Reset,999);
$Form->set_InputLabel($FormularName,$InputName_Reset,"");
?>