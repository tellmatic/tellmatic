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

if ($check && $checkDB) {

/***********************************************************/
//database
/***********************************************************/
//wir setzen db variablen und testen connection zur db!
$tm["DB"]["Name"]=$db_name;
$tm["DB"]["Host"]=$db_host;
$tm["DB"]["Port"]=$db_port;
$tm["DB"]["Socket"]=$db_socket;//1|0
$tm["DB"]["User"]=$db_user;
$tm["DB"]["Pass"]=$db_pass;

/***********************************************************/
//DB Values, define constants
/***********************************************************/
define ("TM_DB_NAME",$tm["DB"]["Name"]);
define ("TM_DB_PORT",$tm["DB"]["Port"]);
if ($tm["DB"]["Socket"]==1) {
	define ("TM_DB_HOST", $tm["DB"]["Host"]);
} else {
	define ("TM_DB_HOST", $tm["DB"]["Host"].":".TM_DB_PORT);
}
define ("TM_DB_USER",$tm["DB"]["User"]);
define ("TM_DB_PASS",$tm["DB"]["Pass"]);
unset($tm["DB"]);

/***********************************************************/
//sqls
/***********************************************************/
//$sql=stripslashes(file_get_contents(TM_INCLUDEPATH_INSTALL."/tellmatic.sql"));
$sql[0]['name']=___("Tabelle")." '".$tm_tablePrefix."adr' ".___("Adressen");
$sql[0]['sql']="
CREATE TABLE ".$tm_tablePrefix."adr (
  id int NOT NULL auto_increment,
  email varchar(255) collate utf8_bin NOT NULL default '',
  clean tinyint NOT NULL default '0',
  aktiv tinyint NOT NULL default '1',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  created datetime default NULL,
  updated datetime default NULL,
  author varchar(64) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin default NULL,
  status tinyint NOT NULL default '0',
  errors tinyint default NULL,
  code varchar(32) collate utf8_bin default '0',
  clicks smallint default '0',
  views smallint default '0',
  newsletter smallint default '0',
  recheck tinyint default '0',
  proof smallint NOT NULL default '0',
  source enum('user', 'import', 'subscribe', 'sync' , 'extern') collate utf8_bin NOT NULL,
  source_id int NOT NULL default '0',
  source_extern_id int NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY email (email),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY `status` (`status`),
  KEY code (code),
  KEY adr_siteid_status (siteid,`status`),
  KEY adr_siteid_email (siteid,email),
  KEY adr_siteid_id (id,siteid),
  KEY source (source),
  KEY source_id (source_id),
  KEY source_extern_id (source_extern_id),
  KEY proof (proof)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[1]['name']=___("Tabelle")." '".$tm_tablePrefix."adr_details' ".___("Adressen - Details");
$sql[1]['sql']="
CREATE TABLE ".$tm_tablePrefix."adr_details (
  id int NOT NULL auto_increment,
  adr_id int NOT NULL default '0',
  memo text collate utf8_bin,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  f0 varchar(128) collate utf8_bin default NULL,
  f1 varchar(128) collate utf8_bin default NULL,
  f2 varchar(128) collate utf8_bin default NULL,
  f3 varchar(128) collate utf8_bin default NULL,
  f4 varchar(128) collate utf8_bin default NULL,
  f5 varchar(128) collate utf8_bin default NULL,
  f6 varchar(128) collate utf8_bin default NULL,
  f7 varchar(128) collate utf8_bin default NULL,
  f8 varchar(128) collate utf8_bin default NULL,
  f9 varchar(128) collate utf8_bin default NULL,
  PRIMARY KEY  (id),
  KEY adr_id (adr_id),
  KEY siteid (siteid),
  KEY adrd_siteid_adrid (adr_id,siteid)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[2]['name']=___("Tabelle")." '".$tm_tablePrefix."adr_grp' ".___("Adressen - Gruppen");
$sql[2]['sql']="
CREATE TABLE ".$tm_tablePrefix."adr_grp (
  id int NOT NULL auto_increment,
  name varchar(255) collate utf8_bin NOT NULL default '',
  public tinyint NOT NULL default '0',
  public_name varchar(255) collate utf8_bin NOT NULL default '',
  descr mediumtext collate utf8_bin,
  aktiv tinyint NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  standard tinyint NOT NULL default '0',
  prod tinyint NOT NULL default '0',
  color varchar(10) collate utf8_bin default '#ffffff',
  created datetime NOT NULL default '0000-00-00 00:00:00',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  author varchar(64) collate utf8_bin NOT NULL default '',
  editor varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY name (name),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY standard (standard),
  KEY prod (prod)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

";

$sql[3]['name']=___("Tabelle")." '".$tm_tablePrefix."adr_grp_ref' ".___("Adressen - Referenzen");
$sql[3]['sql']="
CREATE TABLE ".$tm_tablePrefix."adr_grp_ref (
  id int NOT NULL auto_increment,
  adr_id int NOT NULL default '0',
  grp_id int NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY adr_id (adr_id),
  KEY grp_id (grp_id),
  KEY siteid (siteid),
  KEY grp_site_id (grp_id,siteid),
  KEY aref_adrid_siteid (adr_id,siteid)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[4]['name']=___("Tabelle")." '".$tm_tablePrefix."config' ".___("Einstellungen");
$sql[4]['sql']="
CREATE TABLE ".$tm_tablePrefix."config (
  id int NOT NULL auto_increment,
  name varchar(255) collate utf8_bin NOT NULL default '',
  lang varchar(5) collate utf8_bin NOT NULL default 'de',
  style varchar(64) collate utf8_bin NOT NULL default 'default',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  notify_mail varchar(128) collate utf8_bin default NULL,
  notify_subscribe tinyint NOT NULL default '1',
  notify_unsubscribe tinyint NOT NULL default '1',
  emailcheck_intern tinyint NOT NULL default '2',
  emailcheck_subscribe tinyint NOT NULL default '2',
  emailcheck_sendit tinyint NOT NULL default '1',
  emailcheck_checkit tinyint NOT NULL default '3',
  max_mails_retry tinyint NOT NULL default '5',
  check_version tinyint NOT NULL default '1',
  track_image varchar(255) collate utf8_bin NOT NULL default '',
  rcpt_name varchar( 255 ) NOT NULL default 'Newsletter',
  unsubscribe_use_captcha tinyint NOT NULL default '1',
  unsubscribe_digits_captcha tinyint NOT NULL default '4',
  unsubscribe_sendmail tinyint NOT NULL default '1',
  unsubscribe_action enum('unsubscribe', 'delete', 'blacklist', 'blacklist_delete') collate utf8_bin NOT NULL,
  unsubscribe_host int NOT NULL default '0',
  checkit_limit smallint NOT NULL default '25',
  checkit_from_email varchar( 255 ) NOT NULL default '',
  checkit_adr_reset_error tinyint NOT NULL default '1',
  checkit_adr_reset_status tinyint NOT NULL default '1',
  bounceit_limit smallint NOT NULL default '10',  
  bounceit_host int NOT NULL default '0',
  bounceit_action enum('auto','error','unsubscribe','aktiv','delete') collate utf8_bin NOT NULL default 'auto',
  bounceit_search enum('header','body','headerbody') collate utf8_bin NOT NULL default 'headerbody',
  bounceit_filter_to tinyint NOT NULL default '0',
  bounceit_filter_to_email varchar( 255 ) NOT NULL default '',
  proof tinyint NOT NULL default '1',  
  proof_url varchar( 255 ) NOT NULL default 'http://proof.tellmatic.org',
  proof_trigger int NOT NULL default '10',
  proof_pc int NOT NULL default '10',
  PRIMARY KEY  (id),
  KEY siteid (siteid)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[5]['name']=___("Tabelle")." '".$tm_tablePrefix."frm' ".___("Formulare");
$sql[5]['sql']="
CREATE TABLE ".$tm_tablePrefix."frm (
  id int NOT NULL auto_increment,
  name varchar(64) collate utf8_bin NOT NULL default '',
  action_url varchar(255) collate utf8_bin NOT NULL default '',
  descr tinytext collate utf8_bin NOT NULL default '',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  standard tinyint(1) NOT NULL default '0',
  aktiv tinyint NOT NULL default '1',
  created datetime default NULL,
  updated datetime default NULL,
  author varchar(64) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin default NULL,
  double_optin tinyint NOT NULL default '0',
  subscriptions int NOT NULL default '0',
  use_captcha tinyint(1) default '1',
  digits_captcha tinyint NOT NULL default '4',
  submit_value varchar(255) collate utf8_bin NOT NULL default '',
  reset_value varchar(255) collate utf8_bin NOT NULL default '',
  subscribe_aktiv tinyint(1) NOT NULL default '1',
  check_blacklist tinyint(1) NOT NULL default '1',
  proof tinyint(1) NOT NULL default '1',
  force_pubgroup tinyint(1) NOT NULL default '0',
  overwrite_pubgroup tinyint(1) NOT NULL default '0',
  multiple_pubgroup tinyint(1) NOT NULL default '0',
  nl_id_doptin INT NOT NULL DEFAULT '0' ,
  nl_id_greeting INT NOT NULL DEFAULT '0' ,
  nl_id_update INT NOT NULL DEFAULT '0' ,
  host_id INT NOT NULL DEFAULT '0',
  message_doptin TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  message_greeting TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  message_update TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, 
  email varchar(255) collate utf8_bin NOT NULL default '',
  f0 varchar(128) collate utf8_bin default NULL,
  f1 varchar(128) collate utf8_bin default NULL,
  f2 varchar(128) collate utf8_bin default NULL,
  f3 varchar(128) collate utf8_bin default NULL,
  f4 varchar(128) collate utf8_bin default NULL,
  f5 varchar(128) collate utf8_bin default NULL,
  f6 varchar(128) collate utf8_bin default NULL,
  f7 varchar(128) collate utf8_bin default NULL,
  f8 varchar(128) collate utf8_bin default NULL,
  f9 varchar(128) collate utf8_bin default NULL,
  f0_type varchar(24) collate utf8_bin default 'text',
  f1_type varchar(24) collate utf8_bin default 'text',
  f2_type varchar(24) collate utf8_bin default 'text',
  f3_type varchar(24) collate utf8_bin default 'text',
  f4_type varchar(24) collate utf8_bin default 'text',
  f5_type varchar(24) collate utf8_bin default 'text',
  f6_type varchar(24) collate utf8_bin default 'text',
  f7_type varchar(24) collate utf8_bin default 'text',
  f8_type varchar(24) collate utf8_bin default 'text',
  f9_type varchar(24) collate utf8_bin default 'text',
  f0_required tinyint default '0',
  f1_required tinyint default '0',
  f2_required tinyint default '0',
  f3_required tinyint default '0',
  f4_required tinyint default '0',
  f5_required tinyint default '0',
  f6_required tinyint default '0',
  f7_required tinyint default '0',
  f8_required tinyint default '0',
  f9_required tinyint default '0',
  f0_value text collate utf8_bin,
  f1_value text collate utf8_bin,
  f2_value text collate utf8_bin,
  f3_value text collate utf8_bin,
  f4_value text collate utf8_bin,
  f5_value text collate utf8_bin,
  f6_value text collate utf8_bin,
  f7_value text collate utf8_bin,
  f8_value text collate utf8_bin,
  f9_value text collate utf8_bin,
  email_errmsg varchar(255) collate utf8_bin default '',
  captcha_errmsg varchar(255) collate utf8_bin default '',
  blacklist_errmsg varchar(255) collate utf8_bin default '',
  pubgroup_errmsg varchar(255) collate utf8_bin default '',
  f0_errmsg varchar(255) collate utf8_bin default '',
  f1_errmsg varchar(255) collate utf8_bin default '',
  f2_errmsg varchar(255) collate utf8_bin default '',
  f3_errmsg varchar(255) collate utf8_bin default '',
  f4_errmsg varchar(255) collate utf8_bin default '',
  f5_errmsg varchar(255) collate utf8_bin default '',
  f6_errmsg varchar(255) collate utf8_bin default '',
  f7_errmsg varchar(255) collate utf8_bin default '',
  f8_errmsg varchar(255) collate utf8_bin default '',
  f9_errmsg varchar(255) collate utf8_bin default '',
  f0_expr varchar(255) collate utf8_bin default NULL,
  f1_expr varchar(255) collate utf8_bin default NULL,
  f2_expr varchar(255) collate utf8_bin default NULL,
  f3_expr varchar(255) collate utf8_bin default NULL,
  f4_expr varchar(255) collate utf8_bin default NULL,
  f5_expr varchar(255) collate utf8_bin default NULL,
  f6_expr varchar(255) collate utf8_bin default NULL,
  f7_expr varchar(255) collate utf8_bin default NULL,
  f8_expr varchar(255) collate utf8_bin default NULL,
  f9_expr varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (id),
  KEY name (name),
  KEY siteid (siteid),
  KEY aktiv (aktiv),
  KEY standard (standard)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[6]['name']=___("Tabelle")." '".$tm_tablePrefix."frm_grp_ref' ".___("Formulare - Referenzen");
$sql[6]['sql']="
CREATE TABLE ".$tm_tablePrefix."frm_grp_ref (
  id int NOT NULL auto_increment,
  frm_id int NOT NULL default '0',
  grp_id int NOT NULL default '0',
  public tinyint NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY frm_id (frm_id),
  KEY grp_id (grp_id),
  KEY siteid (siteid),
  KEY grp_site_id (grp_id,siteid)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

";

$sql[7]['name']=___("Tabelle")." '".$tm_tablePrefix."nl' ".___("Newsletter");
$sql[7]['sql']="
CREATE TABLE ".$tm_tablePrefix."nl (
  id int NOT NULL auto_increment,
  subject varchar(255) collate utf8_bin NOT NULL default '',
  title varchar(255) collate utf8_bin NOT NULL default '',
  title_sub varchar(255) collate utf8_bin NOT NULL default '',
  aktiv tinyint NOT NULL default '0',
  body_head text collate utf8_bin,
  body longtext collate utf8_bin,
  body_foot text collate utf8_bin,
  body_text longtext collate utf8_bin,
  summary longtext collate utf8_bin,
  link tinytext collate utf8_bin,
  created datetime default NULL,
  updated datetime default NULL,
  status tinyint default '0',
  massmail tinyint NOT NULL default '0',
  rcpt_name varchar( 255 ) NOT NULL default 'Newsletter',
  clicks int default '0',
  views int default '0',
  author varchar(128) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin default NULL,
  grp_id int NOT NULL default '0',
  content_type varchar(12) collate utf8_bin NOT NULL default 'html',
  track_image varchar(255) collate utf8_bin NOT NULL default '_global',
  track_personalized tinyint NOT NULL default 1,
  use_inline_images tinyint NOT NULL default 0,
  is_template tinyint NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY aktiv (aktiv),
  KEY nl_subject (`subject`),
  KEY grp_id (grp_id),
  KEY siteid (siteid),
  KEY `status` (`status`)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[8]['name']=___("Tabelle")." '".$tm_tablePrefix."nl_grp' ".___("Newsletter - Gruppen");
$sql[8]['sql']="
CREATE TABLE ".$tm_tablePrefix."nl_grp (
  id int NOT NULL auto_increment,
  name varchar(128) collate utf8_bin NOT NULL default '',
  descr mediumtext collate utf8_bin NOT NULL,
  aktiv tinyint NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  standard tinyint NOT NULL default '0',
  color varchar(10) collate utf8_bin default '#ffffff',
  created datetime NOT NULL default '0000-00-00 00:00:00',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  author varchar(64) collate utf8_bin NOT NULL default '',
  editor varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY name (name),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY standard (standard)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[9]['name']=___("Tabelle")." '".$tm_tablePrefix."nl_h' History";
$sql[9]['sql']="
CREATE TABLE ".$tm_tablePrefix."nl_h (
  id int NOT NULL auto_increment,
  q_id int NOT NULL default '0',
  nl_id int NOT NULL default '0',
  grp_id int NOT NULL default '0',
  adr_id int NOT NULL default '0',
  host_id int NOT NULL default '0',
  status tinyint default NULL,
  created datetime default NULL,
  errors tinyint default NULL,
  proof smallint default '0',
  sent datetime default NULL,
  ip varchar(16) collate utf8_bin NOT NULL default '0.0.0.0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY siteid (siteid),
  KEY `status` (`status`),
  KEY adr_id (adr_id),
  KEY grp_id (grp_id),
  KEY nl_id (nl_id),
  KEY q_id (q_id),
  KEY host_id (host_id),
  KEY nlh_siteid_status (siteid,`status`),
  KEY h_nlid_adrid_stat (`status`,nl_id,adr_id),
  KEY nlh_siteid_ip (siteid,ip),
  KEY nlh_siteid_qid_ip (siteid,ip,q_id),
  KEY nlh_siteid_ip_grpid (siteid,ip,grp_id),
  KEY nlh_siteid_ip_qid_nlid (siteid,ip,q_id,nl_id)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[10]['name']=___("Tabelle")." '".$tm_tablePrefix."nl_q' - Queue ";
$sql[10]['sql']="
CREATE TABLE ".$tm_tablePrefix."nl_q (
  id int NOT NULL auto_increment,
  nl_id int NOT NULL default '0',
  grp_id int NOT NULL default '0',
  host_id int NOT NULL default '0',
  status tinyint NOT NULL default '0',
  created datetime default NULL,
  send_at datetime default NULL,
  check_blacklist tinyint NOT NULL default '1',
  proof tinyint NOT NULL default '1',
  autogen tinyint NOT NULL default '0',
  touch tinyint NOT NULL default 0,
  use_inline_images tinyint NOT NULL default 0,
  sent datetime default NULL,
  save_imap tinyint NOT NULL default 0,
  host_id_imap int NOT NULL default 0,
  author varchar(64) collate utf8_bin default NULL,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY nl_id (nl_id,grp_id,`status`),
  KEY siteid (siteid),
  KEY host_id (host_id),
  KEY send_at (send_at),
  KEY touch (touch),
  KEY proof (proof)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[11]['name']=___("Tabelle")." '".$tm_tablePrefix."user' - ".___("Benutzer");
$sql[11]['sql']="
CREATE TABLE ".$tm_tablePrefix."user (
  id int NOT NULL auto_increment,
  name varchar(64) collate utf8_bin NOT NULL default '',
  passwd varchar(128) collate utf8_bin NOT NULL default '',
  crypt varchar(128) collate utf8_bin NOT NULL default '',
  email varchar(255) collate utf8_bin NOT NULL default '',
  last_login int NOT NULL default '0',
  aktiv tinyint NOT NULL default '1',
  admin tinyint NOT NULL default '0',
  manager tinyint NOT NULL default '0',
  style varchar(64) collate utf8_bin NOT NULL default 'default',
  lang varchar(8) collate utf8_bin NOT NULL default 'de',
  expert tinyint default '0',
  demo tinyint default '0',
  startpage varchar(64) collate utf8_bin NOT NULL default 'Welcome',
  debug tinyint default '0',
  debug_lang tinyint default '0',
  debug_lang_level tinyint default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY name (name,passwd,aktiv,siteid),
  KEY lang (lang)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[12]['name']=___("Tabelle")." '".$tm_tablePrefix."frm_s' - ".___("Formulare - Anmeldungen");
$sql[12]['sql']="
CREATE TABLE ".$tm_tablePrefix."frm_s (
  id int NOT NULL auto_increment,
  created datetime default NULL,
  frm_id int NOT NULL default '0',
  adr_id int NOT NULL default '0',
  ip varchar(16) collate utf8_bin NOT NULL default '0.0.0.0',
  siteid varchar(128) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY frm_id (frm_id,adr_id,siteid),
  KEY frms_siteid_ip (siteid,ip),
  KEY frms_siteid_ip_frmid (siteid,ip,frm_id)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[13]['name']=___("Tabelle")." '".$tm_tablePrefix."hosts' - ".___("Mail-Server");
$sql[13]['sql']="
CREATE TABLE ".$tm_tablePrefix."hosts (
  id int NOT NULL auto_increment,
  name varchar(255) collate utf8_bin NOT NULL default '',
  aktiv tinyint(1) NOT NULL default '1',
  standard tinyint(1) NOT NULL default '0',  
  host varchar(255) collate utf8_bin NOT NULL default '',
  port smallint(6) NOT NULL default '0',
  type enum('smtp','pop3','imap') collate utf8_bin NOT NULL default 'smtp',
  options varchar(255) collate utf8_bin NOT NULL default '',
  smtp_auth varchar(32) collate utf8_bin NOT NULL default '',
  smtp_domain varchar(255) collate utf8_bin NOT NULL default '',
  smtp_ssl tinyint(1) NOT NULL default '0',
  smtp_max_piped_rcpt smallint NOT NULL default '1',
  user varchar(64) collate utf8_bin default NULL,
  pass varchar(64) collate utf8_bin default NULL,
  max_mails_atonce smallint NOT NULL default '25',
  max_mails_bcc smallint NOT NULL default '50',
  sender_name varchar(255) collate utf8_bin NOT NULL default '',
  sender_email varchar(255) collate utf8_bin NOT NULL default '',
  return_mail varchar(128) collate utf8_bin NOT NULL default '',
  reply_to varchar(128) collate utf8_bin NOT NULL default '',
  delay INT NOT NULL default '100000',
  imap_folder_trash varchar(255) collate utf8_bin NOT NULL default '',
  imap_folder_sent varchar(255) collate utf8_bin NOT NULL default '',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY hosts_aktiv_siteid (aktiv,siteid)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[14]['name']=___("Tabelle")." '".$tm_tablePrefix."blacklist' - ".___("Blacklist");
$sql[14]['sql']="
CREATE TABLE ".$tm_tablePrefix."blacklist (
  id int(11) NOT NULL auto_increment,
  type enum('email','domain','expr') collate utf8_bin NOT NULL default 'email',
  expr varchar(255) collate utf8_bin NOT NULL default '',
  aktiv tinyint(1) NOT NULL default '1',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY type (type)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[15]['name']=___("Tabelle")." '".$tm_tablePrefix."nl_attm' - ".___("Newsletter - Attachements");
$sql[15]['sql']="
CREATE TABLE ".$tm_tablePrefix."nl_attm (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
nl_id INT NOT NULL ,
file VARCHAR( 255 ) NOT NULL ,
siteid VARCHAR( 64 ) NOT NULL ,
INDEX ( nl_id )
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[16]['name']=___("Tabelle")." '".$tm_tablePrefix."log' - ".___("Logbuch");
$sql[16]['sql']="
CREATE TABLE ".$tm_tablePrefix."log (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
date datetime NOT NULL default '0000-00-00 00:00:00',
author_id int(11) NOT NULL default '0',
action enum('new','edit','delete','memo','usage') NOT NULL default 'memo',
object varchar(64) NOT NULL default '',
property varchar(64) NOT NULL default '',
x_value longtext NOT NULL,
edit_id int(11) NOT NULL default '0',
data longtext,
memo varchar(255) NOT NULL default '',
siteid VARCHAR( 64 ) NOT NULL ,
INDEX ( edit_id )
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[17]['name']=___("Tabelle")." '".$tm_tablePrefix."lnk' - ".___("Links");
$sql[17]['sql']="
CREATE TABLE ".$tm_tablePrefix."lnk (
  id bigint(20) NOT NULL auto_increment,
  siteid varchar(64) collate utf8_bin NOT NULL,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  author varchar(64) collate utf8_bin NOT NULL,
  editor varchar(64) collate utf8_bin NOT NULL,
  aktiv tinyint(1) NOT NULL,
  short varchar(48) collate utf8_bin NOT NULL,
  name varchar(255) collate utf8_bin NOT NULL,
  url tinytext collate utf8_bin NOT NULL,
  descr tinytext collate utf8_bin NOT NULL,
  clicks bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY siteid (siteid),
  KEY short (short),
  KEY idx_siteid_id (siteid,id),
  KEY idx_siteid_short (siteid,short),
  FULLTEXT KEY short_2 (short)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[18]['name']=___("Tabelle")." '".$tm_tablePrefix."lnk_grp' - ".___("Link Gruppen");
$sql[18]['sql']="
CREATE TABLE ".$tm_tablePrefix."lnk_grp (
  id int(11) NOT NULL auto_increment COMMENT 'unique internal id',
  siteid varchar(64) collate utf8_bin NOT NULL COMMENT 'site id',
  created datetime NOT NULL COMMENT 'creation date',
  updated datetime NOT NULL COMMENT 'last update date',
  author varchar(64) collate utf8_bin NOT NULL COMMENT 'author name/id',
  editor varchar(64) collate utf8_bin NOT NULL COMMENT 'editor name/id',
  aktiv tinyint(1) NOT NULL,
  standard tinyint(1) NOT NULL default '0',
  short varchar(48) collate utf8_bin NOT NULL,
  name varchar(255) collate utf8_bin NOT NULL,
  descr tinytext collate utf8_bin NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=".$db_type." DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[19]['name']=___("Tabelle")." '".$tm_tablePrefix."lnk_grp_ref' - ".___("Link Gruppen Referenzen");
$sql[19]['sql']="
CREATE TABLE ".$tm_tablePrefix."lnk_grp_ref (
  id int(11) NOT NULL auto_increment,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  item_id int(11) NOT NULL default '0',
  grp_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY siteid (siteid)
) ENGINE=".$db_type."  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql[20]['name']=___("Tabelle")." '".$tm_tablePrefix."lnk_click' - ".___("Klicks");
$sql[20]['sql']="
CREATE TABLE ".$tm_tablePrefix."lnk_click (
  id bigint(11) NOT NULL auto_increment,
  created datetime NOT NULL,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  lnk_id int(11) NOT NULL default '0',
  nl_id int(11) NOT NULL default '0',
  q_id int(11) NOT NULL default '0',
  adr_id bigint(11) NOT NULL default '0',
  h_id bigint(11) NOT NULL default '0',
  ip varchar(16) collate utf8_bin NOT NULL default '0.0.0.0',
  clicks int(11) NOT NULL default '1',
  PRIMARY KEY  (id),
  UNIQUE KEY unique_clicks (siteid,lnk_id,nl_id,q_id,adr_id,h_id,ip),
  KEY siteid (siteid)
) ENGINE=".$db_type." DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

/***********************************************************/
//create tables etc
/***********************************************************/
	$checkDB=false;
	$DB=new tm_DB();
	$sc=count($sql);
	for ($scc=0;$scc<$sc;$scc++) {
		if (!tm_DEMO() && $DB->Query($sql[$scc]['sql'])) {
			$MESSAGE.=tm_message_success(sprintf(___("Datenbank: %s wurde erstellt"),$sql[$scc]['name']));
			$checkDB=true;
			if (tm_DEBUG()) $MESSAGE.="<pre>".$sql[$scc]['sql']."</pre>";
		} else {//!demo && query
			if (tm_DEMO()) {
				$checkDB=true;
				$MESSAGE.=tm_message_success(sprintf(___("Datenbank: %s wurde erstellt"),$sql[$scc]['name']));
				if (tm_DEBUG()) $MESSAGE.="<pre>".$sql[$scc]['sql']."</pre>";
			} else {//demo
				$MESSAGE.=tm_message_error(___("Ein Fehler beim Erstellen der Datenbank ist aufgetreten"));
				$MESSAGE.="<br>".$sql[$scc]['name']." :-(<br><pre>".$sql[$scc]['sql']."</pre>";
				#if (tm_DEBUG()) $MESSAGE.="<pre>".$sql[$scc]['sql']."</pre>";
				$checkDB=false;
				$check=false;
			}//demo
		}//!demo && query
	}//for


}//check && checkDB
?>