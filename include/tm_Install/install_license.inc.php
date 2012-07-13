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

//verfuegbare sprache siehe install_conf.inc.php
//1090: 	$LANGUAGES=Array(	'lang' => Array('de','en','es','fr','it','nl','pt')

$download_note['en']='<b>Thank you for using Tellmatic!</b><br>A small Warning: Unforntunately there are several downloadsites in the internet offering Tellmatic 
download from a different host other than Tellmatic.org or Sourceforge, or maybe offer download as a ZIP File.
<br>You may trust them, but do it on your own risk!
<br>You better download Tellmatic from http://www.tellmatic.org/download (there is a Link to download for free from Sourceforge.net)
<br>Please do NOT PAY for downloads except you make a donation to Tellmatic.org or so and get it as an email attachment.
<br>Especially DO NOT TRUST and use downloads from sites saying they offer a "Tellmatic special exnterprise full featured Version" or something weird like this, there is only one Version available! And it\'s for free!';

$download_note['de']=$download_note['en'];
$download_note['es']=$download_note['en'];
$download_note['fr']=$download_note['en'];
$download_note['it']=$download_note['en'];
$download_note['nl']=$download_note['en'];
$download_note['pt']=$download_note['en'];

$license['de']='
GPL
http://www.gnu.org/licenses/gpl.html
http://www.gnu.de/documents/gpl.de.html
http://www.gnu.de/documents/gpl-2.0.de.html

AGPL	
http://www.gnu.org/licenses/agpl.html
http://de.wikipedia.org/wiki/GNU_Affero_General_Public_License
';

$license['en']='
GPL
http://www.gnu.org/licenses/gpl.html

AGPL	
http://www.gnu.org/licenses/agpl.html
';
$license['es']=$license['en'];
$license['fr']=$license['en'];
$license['it']=$license['en'];
$license['nl']=$license['en'];
$license['pt']=$license['en'];

//Form
$Form=new tm_SimpleForm();
$FormularName="tm_i_license";
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormDesc($FormularName,___("Tellmatic Installation - Lizenz"));
$Form->new_Input($FormularName,"set", "hidden", "license");
$Form->new_Input($FormularName,"lang", "hidden", $lang);
//////////////////
//add inputfields and buttons....
//////////////////
//license
$Form->new_Input($FormularName,$InputName_License,"textarea", $license[$lang]);
$Form->set_InputStyleClass($FormularName,$InputName_License,"mFormTextArea","mFormTextAreaFocus");
$Form->set_InputSize($FormularName,$InputName_License,80,10);
$Form->set_InputDesc($FormularName,$InputName_License,"(A)GPL - (Affero) GNU Public License");
$Form->set_InputReadonly($FormularName,$InputName_License,false);
$Form->set_InputOrder($FormularName,$InputName_License,1);
$Form->set_InputLabel($FormularName,$InputName_License,"(A)GPL - (Affero) GNU Public License<br>");

//accept
$Form->new_Input($FormularName,$InputName_Accept,"checkbox", 1);
$Form->set_InputJS($FormularName,$InputName_Accept," onChange=\"flash('submit','#ff0000');\" ");
$Form->set_InputDefault($FormularName,$InputName_Accept,$$InputName_Accept);
$Form->set_InputStyleClass($FormularName,$InputName_Accept,"mFormCheckbox","mFormCheckboxFocus");
$Form->set_InputSize($FormularName,$InputName_Accept,1,1);
$Form->set_InputDesc($FormularName,$InputName_Accept,___("Akzeptieren"));
$Form->set_InputReadonly($FormularName,$InputName_Accept,false);
$Form->set_InputOrder($FormularName,$InputName_Accept,3);
$Form->set_InputLabel($FormularName,$InputName_Accept,___("Akzeptieren"));

//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit",___("Weiter"));
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");


/*RENDER FORM*/
$Form->render_Form($FormularName);
/*DISPLAY*/
$FORM_LICENSE= "";
$FORM_LICENSE.= $Form->FORM[$FormularName]['head'];
$FORM_LICENSE.= $Form->INPUT[$FormularName]['set']['html'];
$FORM_LICENSE.= $Form->INPUT[$FormularName]['lang']['html'];
$FORM_LICENSE.= "<div id=\"lang\" style=\"display:block;\">";
$FORM_LICENSE.= "<table border=0>";
$FORM_LICENSE.= "<thead>";
$FORM_LICENSE.= "<tr>";
$FORM_LICENSE.= "<td valign=top align=\"left\" colspan=2>";
$FORM_LICENSE.= $Form->FORM[$FormularName]['desc'];
$FORM_LICENSE.= "</td>";
$FORM_LICENSE.= "</tr>";
$FORM_LICENSE.= "</thead>";
$FORM_LICENSE.= "<tbody>";

$FORM_LICENSE.= "<tr>";
$FORM_LICENSE.= "<td valign=top align=\"left\" colspan=2>";
$FORM_LICENSE.= $download_note[$lang];
$FORM_LICENSE.= "<br><br>";
$FORM_LICENSE.= "</td>";
$FORM_LICENSE.= "</tr>";


$FORM_LICENSE.= "<tr>";
$FORM_LICENSE.= "<td valign=top align=\"left\" colspan=2>";
$FORM_LICENSE.= $Form->INPUT[$FormularName][$InputName_License]['html'];
$FORM_LICENSE.= "</td>";
$FORM_LICENSE.= "</tr>";

$FORM_LICENSE.= "<tr>";
$FORM_LICENSE.= "<td valign=top align=\"left\">";
$FORM_LICENSE.= $Form->INPUT[$FormularName][$InputName_Accept]['html'];
$FORM_LICENSE.= "</td>";
$FORM_LICENSE.= "<td valign=top align=\"right\">";
$FORM_LICENSE.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$FORM_LICENSE.= "</td>";
$FORM_LICENSE.= "</tr>";

$FORM_LICENSE.= "</tbody>";
$FORM_LICENSE.= "</table>";
$FORM_LICENSE.= "</div>";
$FORM_LICENSE.= $Form->FORM[$FormularName]['foot'];
?>