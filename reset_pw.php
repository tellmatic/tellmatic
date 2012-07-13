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
//your username
$username="myusername";
//new password
$new_password="top scret";
/********************************************************************************/
define ('TM_SITEID','tellmatic');
$hash=md5(TM_SITEID.$username.$new_password);
$sql="UPDATE user SET passwd='".$hash."' WHERE siteid='".TM_SITEID."' AND name='".$username."' AND aktiv=1 AND admin=1";
echo "new hash:<br><br>";
echo "<pre>".$hash."</pre>";
echo "<br>open up phpmyadmin or similar tool, list table [prefix_]user and copy paste the new hash value and paste into [prefix_]user table for user <b>'".$username."'</b>";
echo "<br>or use this query (maybe with the mysql commandline tool, change tablename and add prefix if needed!):<br><pre>".$sql."</pre>";
echo "<br>finally login to Tellmatic as user '".$username."' and change password again in your usersettings (this is necessary to recreate the .htpasswd file!)";
?>