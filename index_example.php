<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<meta name="application" content="Tellmatic - Die Newsletter Maschine (www.tellmatic.org)">
<meta name="author" content="Volker Augustin">
<meta name="Publisher" content="multi.art.studio Hanau - www.multiartstudio.com">
<meta name="Copyright" content="2007 Volker Augustin - multi.art.studio Hanau">
<meta name="Description" content="tellmatic - the newslettermaschine - www.tellmatic.org">
<meta name="Page-topic" content="Newsletter">
<meta name="Audience" content="all">
<meta name="Content-language" content="DE">
<meta name="Robots" content="NOINDEX,NOFOLLOW">
<meta name="Keywords" content="news, newsletter, massmail, personalized mail, mailing, emailmarketing">
<meta name="OBGZip" content="true">
<link rel="shortcut icon" href="img/favicon.ico">
<title>Tellmatic - Die Newsletter Maschine (www.tellmatic.org)</title>
<style type="text/css">
	@import url(css/default/tellmatic.css);
	@import url(css/default/tellmatic_head.css);
	@import url(css/default/tellmatic_menu.css);
	@import url(css/default/tellmatic_main.css);
	@import url(css/default/tellmatic_form.css);
    .s { width:1px; height:1px; color: black; background-color: red; font-size : 2pt; }
    .w { width:1px; height:1px; color: white; background-color: white; font-size : 2pt; }
</style>
	<script type="text/javascript" src="js/tellmatic.js"></script>
	<script type="text/javascript" src="js/jsFormValidation/jsFormValidation.js"></script>
</head>
<body>
	<div id="head" class="head"></div>
	<div id="logo" class="logo"></div>
	<div id="tellmatic" class="tellmatic"></div>
	<div id="main" class="main">
<?php
	//a href=subscribe.php?fid=1
	$called_via_url=false;//include! output of inclöude files is saved in $_OUTPUT
	$frm_id=1;
	$_CONTENT="";
	include("subscribe.php");
	echo $_CONTENT;
	$_CONTENT="";
	include("unsubscribe.php");
	echo $_CONTENT;
?>
	</div>
	<script type="text/javascript">
		<!--
		checkForm();
		-->
	</script>
</body>
</html>