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
//Form
$Form=new tm_SimpleForm();
$FormularName="tm_i_lang";
$Form->new_Form($FormularName,$_SERVER["PHP_SELF"],"post","_self");
$Form->set_FormDesc($FormularName,"Tellmatic Installation - Select Language / Sprache wählen");
$Form->new_Input($FormularName,"set", "hidden", "language");
//////////////////
//add inputfields and buttons....
//////////////////
//lang
$Form->new_Input($FormularName,$InputName_Lang,"select", "");
$Form->set_InputDefault($FormularName,$InputName_Lang,$$InputName_Lang);
$Form->set_InputStyleClass($FormularName,$InputName_Lang,"mFormSelect","mFormSelectFocus");
$Form->set_InputDesc($FormularName,$InputName_Lang,"Sprache / Language");
$Form->set_InputReadonly($FormularName,$InputName_Lang,false);
$Form->set_InputOrder($FormularName,$InputName_Lang,2);
$Form->set_InputLabel($FormularName,$InputName_Lang,"Sprache / Language");
$Form->set_InputSize($FormularName,$InputName_Lang,0,1);
$Form->set_InputMultiple($FormularName,$InputName_Lang,false);
//add Data
$lc=count($LANGUAGES['lang']);
for ($lcc=0;$lcc<$lc;$lcc++) {
	$Form->add_InputOption($FormularName,$InputName_Lang,$LANGUAGES['lang'][$lcc],$LANGUAGES['text'][$lcc]);
}


//submit button
$Form->new_Input($FormularName,$InputName_Submit,"submit","Continue / Weiter");
$Form->set_InputStyleClass($FormularName,$InputName_Submit,"mFormSubmit","mFormSubmitFocus");
$Form->set_InputDesc($FormularName,$InputName_Submit,"");
$Form->set_InputReadonly($FormularName,$InputName_Submit,false);
$Form->set_InputOrder($FormularName,$InputName_Submit,998);
$Form->set_InputLabel($FormularName,$InputName_Submit,"");

#$FORM_LANG=$Form->get_Form($FormularName);

/*RENDER FORM*/
$Form->render_Form($FormularName);
/*DISPLAY*/
$FORM_LANG= "";
$FORM_LANG.= $Form->FORM[$FormularName]['head'];
$FORM_LANG.= $Form->INPUT[$FormularName]['set']['html'];
$FORM_LANG.= "<div id=\"lang\" style=\"display:block;\">";
$FORM_LANG.= "<table border=0>";
$FORM_LANG.= "<thead>";
$FORM_LANG.= "<tr>";
$FORM_LANG.= "<td valign=top align=\"left\" colspan=2>";
$FORM_LANG.= $Form->FORM[$FormularName]['desc'];
$FORM_LANG.= "</td>";
$FORM_LANG.= "</tr>";
$FORM_LANG.= "</thead>";
$FORM_LANG.= "<tbody>";
$FORM_LANG.= "<tr>";
$FORM_LANG.= "<td valign=top align=\"left\" width=200>";
$FORM_LANG.= $Form->INPUT[$FormularName][$InputName_Lang]['html'];
$FORM_LANG.= "</td>";
$FORM_LANG.= "<td valign=top align=\"right\">";
$FORM_LANG.= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$FORM_LANG.= "</td>";
$FORM_LANG.= "</tr>";
$FORM_LANG.= "</tbody>";
$FORM_LANG.= "</table>";
$FORM_LANG.= "</div>";
$FORM_LANG.= $Form->FORM[$FormularName]['foot'];
?>