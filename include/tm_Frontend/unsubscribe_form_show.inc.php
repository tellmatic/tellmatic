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

/*RENDER FORM*/

$Form->render_Form($FormularName);

/*DISPLAY*/

$FHEAD= $Form->FORM[$FormularName]['head'];
$FHEAD.= $Form->INPUT[$FormularName]['a_id']['html'];
$FHEAD.= $Form->INPUT[$FormularName]['h_id']['html'];
$FHEAD.= $Form->INPUT[$FormularName]['nl_id']['html'];
$FHEAD.= $Form->INPUT[$FormularName]['code']['html'];
$FHEAD.= $Form->INPUT[$FormularName]['cpt']['html'];//captcha
$FHEAD.= $Form->INPUT[$FormularName]['set']['html'];
$FEMAIL= $Form->INPUT[$FormularName][$InputName_Name]['html'];
$FCAPTCHA= $Form->INPUT[$FormularName][$InputName_Captcha]['html'];
$FSUBMIT= $Form->INPUT[$FormularName][$InputName_Submit]['html'];
$FFOOT=$Form->FORM[$FormularName]['foot'];

?>