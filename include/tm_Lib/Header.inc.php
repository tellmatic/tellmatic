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
$_HEAD_HTML='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset='.$encoding.'">
<meta name="application" content="'.TM_APPTEXT.'">
<meta name="author" content="Volker Augustin">
<meta name="Publisher" content="Tellmatic">
<meta name="Copyright" content="2007-2010 Volker Augustin - multi.art.studio Hanau">
<meta name="Description" content="tellmatic - the newslettermaschine - www.tellmatic.org">
<meta name="Page-topic" content="Newsletter">
<meta name="Audience" content="all">
<!--meta name="Content-language" content="DE"-->
<meta name="Robots" content="NOINDEX,NOFOLLOW">
<meta name="Keywords" content="news, newsletter, massmail, personalized mail, mailing, emailmarketing">
<meta name="OBGZip" content="true">
<link rel="shortcut icon" href="'.$tm_URL_FE.'/'.TM_IMGDIR.'/favicon.ico">

<title>'.TM_APPTEXT.'</title>

<style type="text/css">
	@import url('.$tm_URL_FE.'/css/calendar.css);
	@import url('.$tm_URL_FE.'/css/tellmatic.css);
	@import url('.$tm_URL_FE.'/css/'.$Style.'/tellmatic.css);
	@import url('.$tm_URL_FE.'/css/'.$Style.'/tellmatic_head.css);
	@import url('.$tm_URL_FE.'/css/'.$Style.'/tellmatic_menu.css);
	@import url('.$tm_URL_FE.'/css/'.$Style.'/tellmatic_main.css);
	@import url('.$tm_URL_FE.'/css/'.$Style.'/tellmatic_form.css);
</style>
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/tellmatic.js"></script>
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/tellmatic_cookie.js"></script>
	<!--script type="text/javascript" src="'.$tm_URL_FE.'/js/tellmatic_ajax.js"></script-->
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/jsFormValidation/jsFormValidation.js"></script>
	<!--calendar-->
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/cal/calendar.js"></script>
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/cal/lang/calendar-en.js"></script>
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/cal/calendar-setup.js"></script>
	<!--mootools-->
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/mootools/mootools.js"></script>
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/mootools/mootools-more.js"></script>
	<script type="text/javascript" src="'.$tm_URL_FE.'/js/tellmatic_mootools.js"></script>
';

if ($logged_in) {
	$_HEAD_HTML.='<script type="text/javascript">
		window.onload = countdown;
		var timeout = '.TM_SESSION_TIMEOUT.';
		function countdown() {
		  document.getElementById("timeoutdisplay").firstChild.nodeValue = timeout;
		  if ((timeout--) > 0) window.setTimeout("countdown()", 1000);
		}
	</script>';

}
$_HEAD_HTML.='
</head>
<body><!-- onload="init_ajax();" -->
	<div id="div_loader" class="loader">
		<div id="ld" align="left" style="display:block;padding:0;">
			<table id="lpc" bgcolor="#ffcc00" cellpadding=0 cellspacing=0>
			<tr><td>
			<div id="lt" style="background-color:#33ff33; color:#ffffff; padding:0;">
					<div id="ltt" style="background-color:#eeeeee; color:#ff0000; padding:0;">
						<b>loading&nbsp;.......</b>
					</div>
			</div>
			</td></tr>
			</table>
		</div>
	</div>
	<script type="text/javascript">
	//<!--
	load();
	//--></script>
';
?>