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


$_FOOTER= '
<script type="text/javascript">
	if (document.getElementById("main_info") && document.getElementById("main_info").style.MozOpacity) //if Firefox/ NS6+
	setInterval("fadein(\'main_info\')",5)

	if (document.getElementById("main_help") && document.getElementById("main_help").style.MozOpacity) //if Firefox/ NS6+
	setInterval("fadein(\'main_help\')",5)

	if (document.getElementById("main_head") && document.getElementById("main_head").style.MozOpacity) //if Firefox/ NS6+
	setInterval("fadein(\'main_head\')",25)
	switchSection("sprechblase");
	switchSection("div_loader");
	switchSection("div_debug");
	switchSection("main_info");

	//loaded();
';

if (!empty($_MAIN_MESSAGE)) {
	$_FOOTER.= "switchSection('main_info');";
}

$_FOOTER.='
</script>
<br>
<br>
</body>
</html>
';
?>