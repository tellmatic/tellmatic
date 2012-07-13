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
/* Besuchen Sie die Homepage für Updates und weitere Infos                     */
/********************************************************************************/
/*
$_MAIN_MESSAGE.=tm_message("default");
$_MAIN_MESSAGE.=tm_message("success",'success');
$_MAIN_MESSAGE.=tm_message("notice",'notice');
$_MAIN_MESSAGE.=tm_message("warning",'warning');
$_MAIN_MESSAGE.=tm_message("error",'error');
$_MAIN_MESSAGE.=tm_message("debug",'debug');
*/
if (file_exists(TM_PATH."/install.php")) {
	$_MAIN_MESSAGE.=tm_message_warning(___("ACHTUNG! /install.php existiert! Die Datei install.php bitte nach erfolgreicher Installation verschieben oder löschen!"));
}

if(!function_exists('file_get_contents')) {
	$MAIN_MESSAGE.=tm_message_warning(___("FEHLER! file_get_contents() ist deaktiviert"));
}

if (is_writeable(TM_INCLUDEPATH)) {
	#$_MAIN_MESSAGE.="<br><font size=2 color=red><b>".sprintf(___("ACHTUNG! %s ist nicht Schreibgeschützt"),TM_INCLUDEPATH)."</b></font>";
	#if (@chmod (TM_INCLUDEPATH, 0755)) {
	#	$_MAIN_MESSAGE.="<br><font size=2 color=green>".sprintf(___("Rechte für %s wurden geändert!"),TM_INCLUDEPATH)."</font>";
	#} else {
	#	$_MAIN_MESSAGE.="<br><font size=2 color=blue>".sprintf(___("Rechte für %s konnten nicht geändert werden! Bitte ändern Sie diese manuell."),TM_INCLUDEPATH)."</font>";
	#}
}

if (is_writeable(TM_INCLUDEPATH."/tm_config.inc.php")) {
	$_MAIN_MESSAGE.=tm_message_warning(___("tm_config.inc ist nicht schreibgeschützt!"));
	if (@chmod (TM_INCLUDEPATH."/tm_config.inc.php", 0444)) {
		$_MAIN_MESSAGE.=tm_message_notice(___("Rechte für tm_config.inc wurden geändert!"));
	} else {
		$_MAIN_MESSAGE.=tm_message_warning(___("Rechte für tm_config.inc konnten nicht geändert werden! Bitte ändern Sie diese manuell."));
	}
}

if (!is_writeable(TM_ADMINPATH_TMP)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),TM_ADMINPATH_TMP));
}
if (!is_writeable($tm_datapath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_datapath));
}
if (!is_writeable($tm_nlpath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_nlpath));
}
if (!is_writeable($tm_nlattachpath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_nlattachpath));
}
if (!is_writeable($tm_nlimgpath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_nlimgpath));
}
if (!is_writeable($tm_logpath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_logpath));
}
if (!is_writeable($tm_formpath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_formpath));
}
if (!is_writeable($tm_tmppath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_tmppath));
}
if (!is_writeable($tm_reportpath)) {
	$_MAIN_MESSAGE.=tm_message_error(sprintf(___("Keine Schreibrechte für %s"),$tm_reportpath));
}

if (!file_exists(TM_ADMINPATH_TMP."/.htaccess")) {
	#$_MAIN_MESSAGE.=tm_message_error(sprintf(___("ACHTUNG! %s ist nicht Passwortgeschützt"),TM_ADMINPATH_TMP));
}
if (!file_exists(TM_INCLUDEPATH."/.htaccess")) {
	$_MAIN_MESSAGE.=tm_message_warning(sprintf(___("ACHTUNG! %s ist nicht Passwortgeschützt"),TM_INCLUDEPATH));
}
if (!file_exists($tm_datapath."/.htaccess")) {
	$_MAIN_MESSAGE.=tm_message_warning(sprintf(___("ACHTUNG! %s ist nicht Passwortgeschützt"),$tm_datapath));
}
if (!file_exists($tm_logpath."/.htaccess")) {
	$_MAIN_MESSAGE.=tm_message_warning(sprintf(___("ACHTUNG! %s ist nicht Passwortgeschützt"),$tm_logpath));
}
if (!file_exists($tm_tmppath."/.htaccess")) {
	$_MAIN_MESSAGE.=tm_message_warning(sprintf(___("ACHTUNG! %s ist nicht Passwortgeschützt"),$tm_tmppath));
}
if (!file_exists($tm_reportpath."/.htaccess")) {
	$_MAIN_MESSAGE.=tm_message_warning(sprintf(___("ACHTUNG! %s ist nicht Passwortgeschützt"),$tm_reportpath));
}

if ($logged_in) {

	//log
	$LOGS=new tm_LOG();
	if (TM_LOG) $LOGS->log(Array("data"=>Array("id"=>$LOGIN->USER['id'],"act"=>$action),"object"=>"usr","action"=>"usage"));

	$_MAIN_DESCR=___("Bitte aus dem Menü wählen.");

	switch ($action) {
		//default:
		default : require_once (TM_INCLUDEPATH_GUI."/Welcome.inc.php"); break;
		case 'Welcome' : require_once (TM_INCLUDEPATH_GUI."/Welcome.inc.php"); break;
		//admin
		#case 'adr_testadressen' : if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/adr_testadressen.inc.php"); break;
		case 'adm_set' : if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/adm_set.inc.php"); break;
		case 'adm_user_list' :  if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/adm_user_list.inc.php"); break;
		case 'adm_user_edit' :  if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/adm_user_edit.inc.php"); break;
		case 'adm_user_new' :  if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/adm_user_new.inc.php"); break;
		case 'host_list' : if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/host_list.inc.php"); break;
		case 'host_new' : if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/host_new.inc.php"); break;
		case 'host_edit' : if ($user_is_admin) require_once (TM_INCLUDEPATH_GUI."/host_edit.inc.php"); break;
		//auch als manager
		case 'log_list' : if ($user_is_admin || $user_is_manager) require_once (TM_INCLUDEPATH_GUI."/log_list.inc.php"); break;
		//user
		case 'user' : require_once (TM_INCLUDEPATH_GUI."/user.inc.php"); break;
		//verwaltung
		case 'bl_list' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/bl_list.inc.php"); break;
		case 'bl_new' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/bl_new.inc.php"); break;
		case 'bl_edit' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/bl_edit.inc.php"); break;
		case 'bounce' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/bounce.inc.php"); break;
		case 'adr_clean' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/adr_clean.inc.php"); break;
		case 'bounce_it' : if ($user_is_manager) require_once (TM_INCLUDEPATH_BE."/bounce_it_memo.inc.php"); break;
		case 'check_it' : if ($user_is_manager) require_once (TM_INCLUDEPATH_BE."/check_it_memo.inc.php"); break;
		case 'send_it' : if ($user_is_manager) require_once (TM_INCLUDEPATH_BE."/send_it_memo.inc.php"); break;
		//newsletter
		case 'nl_grp_list' : require_once (TM_INCLUDEPATH_GUI."/nl_grp_list.inc.php"); break;
		case 'nl_grp_new' : require_once (TM_INCLUDEPATH_GUI."/nl_grp_new.inc.php"); break;
		case 'nl_grp_edit' : require_once (TM_INCLUDEPATH_GUI."/nl_grp_edit.inc.php"); break;
		case 'nl_list' : require_once (TM_INCLUDEPATH_GUI."/nl_list.inc.php"); break;
		case 'nl_list_tpl' : $s_nl_istemplate=1;require_once (TM_INCLUDEPATH_GUI."/nl_list.inc.php"); break;
		case 'nl_new' : require_once (TM_INCLUDEPATH_GUI."/nl_new.inc.php"); break;
		case 'nl_edit' : require_once (TM_INCLUDEPATH_GUI."/nl_edit.inc.php"); break;
		case 'queue_new' : require_once (TM_INCLUDEPATH_GUI."/queue_new.inc.php"); break;
		case 'queue_list' : require_once (TM_INCLUDEPATH_GUI."/queue_list.inc.php"); break;
		case 'queue_send' : require_once (TM_INCLUDEPATH_GUI."/queue_send.inc.php"); break;
		//adressen
		case 'adr_grp_list' : require_once (TM_INCLUDEPATH_GUI."/adr_grp_list.inc.php"); break;
		case 'adr_grp_new' : require_once (TM_INCLUDEPATH_GUI."/adr_grp_new.inc.php"); break;
		case 'adr_grp_edit' : require_once (TM_INCLUDEPATH_GUI."/adr_grp_edit.inc.php"); break;
		case 'adr_list' : require_once (TM_INCLUDEPATH_GUI."/adr_list.inc.php"); break;
		case 'adr_list_search' : $no_list=1;require_once (TM_INCLUDEPATH_GUI."/adr_list.inc.php"); break;
		case 'adr_new' : require_once (TM_INCLUDEPATH_GUI."/adr_new.inc.php"); break;
		case 'adr_edit' : require_once (TM_INCLUDEPATH_GUI."/adr_edit.inc.php"); break;
		case 'adr_import' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/adr_import.inc.php"); break;
		case 'adr_export' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/adr_export.inc.php"); break;
		//links
		case 'link_grp_list' : require_once (TM_INCLUDEPATH_GUI."/link_grp_list.inc.php"); break;
		case 'link_grp_new' : require_once (TM_INCLUDEPATH_GUI."/link_grp_new.inc.php"); break;
		case 'link_grp_edit' : require_once (TM_INCLUDEPATH_GUI."/link_grp_edit.inc.php"); break;
		case 'link_list' : require_once (TM_INCLUDEPATH_GUI."/link_list.inc.php"); break;
		case 'link_new' : require_once (TM_INCLUDEPATH_GUI."/link_new.inc.php"); break;
		case 'link_edit' : require_once (TM_INCLUDEPATH_GUI."/link_edit.inc.php"); break;
		//formulare
		case 'form_list' : require_once (TM_INCLUDEPATH_GUI."/form_list.inc.php"); break;
		case 'form_new' : require_once (TM_INCLUDEPATH_GUI."/form_new.inc.php"); break;
		case 'form_edit' : require_once (TM_INCLUDEPATH_GUI."/form_edit.inc.php"); break;
		//filemanager
		case 'filemanager_alternatives' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/filemanager_alternatives.inc.php"); break;
		#case 'filemanager' : if ($user_is_manager) require_once (TM_INCLUDEPATH_GUI."/filemanager.inc.php"); break;
		case 'filemanager_file_edit' : require_once (TM_INCLUDEPATH_GUI."/filemanager_file_edit.inc.php"); break;		
		//status + statistik
		case 'status' : require_once (TM_INCLUDEPATH_GUI."/status.inc.php"); break;
		case 'status_top_x' : require_once (TM_INCLUDEPATH_GUI."/status_top_x.inc.php"); break;
		case 'status_map' : require_once (TM_INCLUDEPATH_GUI."/status_map.inc.php"); break;
		case 'statistic' : require_once (TM_INCLUDEPATH_GUI."/statistic.inc.php"); break;
	}//switch act

	$_MAIN_DESCR.=" (".TM_SITEID.")";
	//icon hilfe
	#$_MAIN_DESCR="<img src=\"".$tm_iconURL."/help.png\" border=\"0\" onclick=\"javascript:switchSection('main_help');\" alt=\"".___("Hilfe")."\">&nbsp;&nbsp;".$_MAIN_DESCR;
	#$_MAIN_DESCR="<!--mootools fx slide in/out--><a href=\"#\" id=\"toggle_help\"><img src=\"".$tm_iconURL."/help.png\" border=\"0\" alt=\"".___("Hilfe")."\"></a>&nbsp;&nbsp;".$_MAIN_DESCR;
	#$_MAIN_DESCR="<img src=\"".$tm_iconURL."/help.png\" border=\"0\" id=\"toggle_help\" alt=\"".___("Hilfe")."\">&nbsp;&nbsp;".$_MAIN_DESCR;
	$_MAIN_DESCR=tm_icon("help.png",___("Hilfe"),___("Hilfe"),"toggle_help")."&nbsp;".$_MAIN_DESCR;
	//HELP	
	require_once (TM_INCLUDEPATH_GUI."/help_section.inc.php");
	//expertmode, hilfe ausblenden
	if ($user_is_expert) $_MAIN_OUTPUT.= "
	<script type=\"text/javascript\">
		//switchSection('main_help');
		toggleSlide('toggle_help','main_help',1);
	</script>";
	if (!$user_is_expert) $_MAIN_OUTPUT.= "
	<script type=\"text/javascript\">
		toggleSlide('toggle_help','main_help',0);
	</script>";

	//PHP Fehler
	if (!empty($_ERROR['html'])) {
		$_MAIN_MESSAGE.="<hr noshade><br>".$_ERROR['html']."<br><hr noshade><br>";
	}

	if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("available_mem=$available_mem Byte");
	if (tm_DEBUG()) $_MAIN_MESSAGE.=tm_message_debug("adr_row_limit=$adr_row_limit");

	$pwchanged=getVar("pwchanged");
	$usr_message=getVar("usr_message",1,"");
	$_MAIN_MESSAGE.=$usr_message;
	if ($pwchanged==1) {
		#$cryptpw=getVar("cryptpw");
		$check=getVar("check");
		if ($check==1) {
			$_MAIN_MESSAGE.=tm_message_notice(___("Passwort wurde gespeichert und ist ab sofort gültig."));
		}//check
	}//pwchanged
    //wichtig, triggerfunction f. menu: id ist "me_0_1"
    //only if logged in, else it throws a $trigger has no property js error because there is no menu!
    $_MAIN_OUTPUT.= "<script language=\"javascript\" type=\"text/javascript\">".
										"toggleSlide('me_0_2','main_info',0);".
										"toggleSlide('me_0_3','main_help',0);".
										"</script>";


	//show log summary
	//search for logs, only section
	$search_log=Array("object"=>'');//empty=all
	require(TM_INCLUDEPATH_GUI."/log_summary_section.inc.php");


}//logged in



if (!empty($_MAIN_MESSAGE)) {
	$_MAIN_MESSAGE="<font size=-1>".
			"<!--a href=\"javascript:switchSection('main_info')\"-->".
			"<a href=\"#\" id=\"toggle_main_info\">".
			tm_icon("exclamation.png",___("Hinweise"),___("Hinweise")).
			"&nbsp;".___("Informationen ausblenden")."</a>".
			"</font><br><br>".
			"<script language=\"javascript\" type=\"text/javascript\">".
			"</script>".$_MAIN_MESSAGE;
	$_MAIN_MESSAGE.="<br><br>";
}

$_MAIN_OUTPUT.= '
<script type="text/javascript">
	<!--
	toggleSlide("toggle_main_info","main_info",0);
	checkForm();
	-->
</script>
';

//show last xx lines of tellmatic php logfile: ... if there is something to show...
if ($user_is_admin && TM_PHP_LOG_TAIL && file_exists(TM_PHP_LOGFILE) && filesize(TM_PHP_LOGFILE)>0) {
	$_MAIN_MESSAGE.="<br><div>";
	$_MAIN_MESSAGE.=sprintf(___("Die letzten %s Einträge in der Tellmatic PHP Fehler-Log-Datei %s:"),TM_PHP_LOG_TAIL_LINES,TM_PHP_LOGFILE)."<br>";
	$_MAIN_MESSAGE.="<font size=1>".mtail(TM_PHP_LOGFILE,TM_PHP_LOG_TAIL_LINES)."</font>";
	$_MAIN_MESSAGE.="</div>";	
}

//new Template
$_Tpl_Main=new tm_Template();
$_Tpl_Main->setTemplatePath(TM_TPLPATH."/".$Style);
$_Tpl_Main->setParseValue("_MAIN_DESCR", $_MAIN_DESCR);
$_Tpl_Main->setParseValue("_MAIN_MESSAGE", $_MAIN_MESSAGE);
$_Tpl_Main->setParseValue("_MAIN_HELP", $_MAIN_HELP);
$_Tpl_Main->setParseValue("_MAIN_OUTPUT", $_MAIN_OUTPUT);
$_MAIN=$_Tpl_Main->renderTemplate("Main.html")."\n";
$_MAIN.= "<br><br><center>&copy;-left 2006-2012 <a href=\"http://www.tellmatic.org\" target=\"blank\">".TM_APPTEXT."</a></center><br><br>";
?>