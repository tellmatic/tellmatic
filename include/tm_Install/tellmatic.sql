-- phpMyAdmin SQL Dump
-- version 2.11.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 08. Juli 2012 um 18:37
-- Server Version: 5.0.51
-- PHP-Version: 5.2.6-1+lenny3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: 'm200001_tm-dev'
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'adr'
--

CREATE TABLE adr (
  id int(11) NOT NULL auto_increment,
  email varchar(255) collate utf8_bin NOT NULL default '',
  clean tinyint(1) NOT NULL default '0',
  aktiv tinyint(1) NOT NULL default '1',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  created datetime default NULL,
  updated datetime default NULL,
  author varchar(64) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin default NULL,
  `status` tinyint(1) NOT NULL default '0',
  `errors` smallint(1) default NULL,
  `code` varchar(32) collate utf8_bin default '0',
  clicks smallint(1) default '0',
  views smallint(1) default '0',
  newsletter smallint(1) default '0',
  recheck tinyint(1) default NULL COMMENT 'marked for email validation',
  proof smallint(6) NOT NULL,
  `source` enum('user','import','subscribe','sync') collate utf8_bin NOT NULL,
  source_id int(11) NOT NULL default '0',
  source_extern_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY email (email),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY `status` (`status`),
  KEY `code` (`code`),
  KEY adr_siteid_status (siteid,`status`),
  KEY adr_siteid_email (siteid,email),
  KEY adr_siteid_id (id,siteid),
  KEY proof (proof),
  KEY source_id (source_id),
  KEY `source` (`source`),
  KEY source_extern_id (source_extern_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'adr_details'
--

CREATE TABLE adr_details (
  id int(11) NOT NULL auto_increment,
  adr_id int(11) NOT NULL default '0',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'adr_grp'
--

CREATE TABLE adr_grp (
  id int(11) NOT NULL auto_increment,
  `name` varchar(128) collate utf8_bin NOT NULL default '',
  public tinyint(1) NOT NULL default '0',
  public_name varchar(255) collate utf8_bin default NULL,
  descr mediumtext collate utf8_bin,
  aktiv tinyint(1) NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  standard tinyint(1) NOT NULL default '0',
  prod tinyint(1) NOT NULL default '0',
  color varchar(10) collate utf8_bin default '#ffffff',
  created datetime NOT NULL default '0000-00-00 00:00:00',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  author varchar(64) collate utf8_bin NOT NULL default '',
  editor varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY `name` (`name`),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY standard (standard),
  KEY public (public),
  KEY prod (prod)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'adr_grp_ref'
--

CREATE TABLE adr_grp_ref (
  id int(11) NOT NULL auto_increment,
  adr_id int(11) NOT NULL default '0',
  grp_id int(11) NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY adr_id (adr_id),
  KEY grp_id (grp_id),
  KEY siteid (siteid),
  KEY grp_site_id (grp_id,siteid),
  KEY aref_adrid_siteid (adr_id,siteid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'blacklist'
--

CREATE TABLE blacklist (
  id int(11) NOT NULL auto_increment,
  `type` enum('email','domain','expr') collate utf8_bin NOT NULL default 'email',
  expr varchar(255) collate utf8_bin NOT NULL default '',
  aktiv tinyint(1) NOT NULL default '1',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY `type` (`type`),
  KEY bl_ate (`type`,expr,aktiv,siteid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'config'
--

CREATE TABLE config (
  id int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_bin NOT NULL default '',
  lang varchar(5) collate utf8_bin NOT NULL default 'de',
  style varchar(64) collate utf8_bin NOT NULL default 'default',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  notify_mail varchar(128) collate utf8_bin default NULL,
  notify_subscribe tinyint(1) NOT NULL default '1',
  notify_unsubscribe tinyint(1) NOT NULL default '1',
  emailcheck_intern tinyint(1) NOT NULL default '2',
  emailcheck_subscribe tinyint(1) NOT NULL default '2',
  emailcheck_sendit tinyint(1) NOT NULL default '1' COMMENT 'emailpruefung beim senden',
  emailcheck_checkit tinyint(1) NOT NULL default '3' COMMENT 'emailpruefung bei aufruf von check_it.php',
  max_mails_retry tinyint(1) NOT NULL default '5',
  check_version tinyint(1) NOT NULL default '1',
  track_image varchar(255) collate utf8_bin NOT NULL default '',
  rcpt_name varchar(255) collate utf8_bin NOT NULL default 'Newsletter',
  unsubscribe_use_captcha tinyint(1) NOT NULL default '0',
  unsubscribe_digits_captcha tinyint(1) NOT NULL default '4',
  unsubscribe_sendmail smallint(6) NOT NULL default '1',
  unsubscribe_action enum('unsubscribe','delete','blacklist','blacklist_delete') collate utf8_bin NOT NULL default 'unsubscribe',
  unsubscribe_host int(11) NOT NULL default '0',
  checkit_from_email varchar(255) collate utf8_bin NOT NULL default '',
  checkit_adr_reset_error tinyint(1) NOT NULL default '1',
  checkit_adr_reset_status tinyint(1) NOT NULL default '1',
  bounceit_host int(11) NOT NULL default '0',
  bounceit_search enum('header','body','headerbody') collate utf8_bin NOT NULL default 'headerbody',
  bounceit_action enum('auto','error','unsubscribe','aktiv','delete') collate utf8_bin NOT NULL default 'auto',
  bounceit_filter_to tinyint(1) NOT NULL default '0',
  bounceit_filter_to_email varchar(255) collate utf8_bin NOT NULL default '',
  checkit_limit smallint(6) NOT NULL default '25',
  bounceit_limit smallint(6) NOT NULL default '10',
  proof tinyint(1) NOT NULL default '1' COMMENT 'enable proof',
  proof_url varchar(255) collate utf8_bin NOT NULL default 'http://proof.tellmatic.org',
  proof_trigger int(11) NOT NULL default '10',
  proof_pc int(11) NOT NULL default '10',
  PRIMARY KEY  (id),
  KEY siteid (siteid),
  KEY proof (proof),
  KEY unsubscribe_host (unsubscribe_host)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'frm'
--

CREATE TABLE frm (
  id int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_bin NOT NULL default '',
  action_url varchar(255) collate utf8_bin NOT NULL default '',
  descr tinytext collate utf8_bin NOT NULL,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  standard tinyint(1) NOT NULL,
  aktiv tinyint(1) NOT NULL default '1',
  created datetime default NULL,
  updated datetime default NULL,
  author varchar(64) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin default NULL,
  double_optin tinyint(1) NOT NULL default '0',
  subscriptions int(11) NOT NULL default '0',
  use_captcha tinyint(1) default '1',
  digits_captcha tinyint(1) NOT NULL default '4',
  submit_value varchar(255) collate utf8_bin NOT NULL default '',
  reset_value varchar(255) collate utf8_bin NOT NULL default '',
  subscribe_aktiv tinyint(1) NOT NULL default '1',
  check_blacklist tinyint(1) NOT NULL default '1',
  proof tinyint(1) NOT NULL default '1' COMMENT 'do proofing?',
  force_pubgroup smallint(1) NOT NULL default '0',
  overwrite_pubgroup smallint(1) NOT NULL default '0',
  multiple_pubgroup tinyint(1) NOT NULL default '0' COMMENT 'allow multiple public groups',
  nl_id_doptin int(11) NOT NULL default '0',
  nl_id_greeting int(11) NOT NULL default '0',
  nl_id_update int(11) NOT NULL default '0',
  message_doptin text collate utf8_bin NOT NULL,
  message_greeting text collate utf8_bin NOT NULL,
  message_update text collate utf8_bin NOT NULL,
  host_id int(11) NOT NULL default '0' COMMENT 'SMTP Host ID',
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
  f0_required tinyint(1) default '0',
  f1_required tinyint(1) default '0',
  f2_required tinyint(1) default '0',
  f3_required tinyint(1) default '0',
  f4_required tinyint(1) default '0',
  f5_required tinyint(1) default '0',
  f6_required tinyint(1) default '0',
  f7_required tinyint(1) default '0',
  f8_required tinyint(1) default '0',
  f9_required tinyint(1) default '0',
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
  email_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  captcha_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  blacklist_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  pubgroup_errmsg varchar(255) collate utf8_bin NOT NULL default '""',
  f0_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f1_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f2_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f3_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f4_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f5_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f6_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f7_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f8_errmsg varchar(255) collate utf8_bin NOT NULL default '',
  f9_errmsg varchar(255) collate utf8_bin NOT NULL default '',
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
  KEY `name` (`name`),
  KEY siteid (siteid),
  KEY aktiv (aktiv),
  KEY nl_id_doptin (nl_id_doptin),
  KEY nl_id_greeting (nl_id_greeting),
  KEY standard (standard)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'frm_grp_ref'
--

CREATE TABLE frm_grp_ref (
  id int(11) NOT NULL auto_increment,
  frm_id int(11) NOT NULL default '0',
  grp_id int(11) NOT NULL default '0',
  public tinyint(4) NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY frm_id (frm_id),
  KEY grp_id (grp_id),
  KEY siteid (siteid),
  KEY grp_site_id (grp_id,siteid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'frm_s'
--

CREATE TABLE frm_s (
  id int(11) NOT NULL auto_increment,
  created datetime default NULL,
  frm_id int(11) NOT NULL default '0',
  adr_id int(11) NOT NULL default '0',
  ip varchar(16) collate utf8_bin NOT NULL default '0.0.0.0',
  siteid varchar(128) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY frm_id (frm_id,adr_id,siteid),
  KEY frms_siteid_ip (siteid,ip),
  KEY frms_siteid_ip_frmid (siteid,ip,frm_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'hosts'
--

CREATE TABLE `hosts` (
  id int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_bin NOT NULL default '',
  aktiv tinyint(1) NOT NULL default '1',
  standard tinyint(1) NOT NULL default '0',
  host varchar(255) collate utf8_bin NOT NULL default '',
  port smallint(6) NOT NULL default '0',
  `type` enum('smtp','pop3','imap') collate utf8_bin NOT NULL default 'smtp',
  options varchar(255) collate utf8_bin NOT NULL default '',
  smtp_auth varchar(32) collate utf8_bin NOT NULL default 'LOGIN',
  smtp_domain varchar(255) collate utf8_bin default NULL,
  smtp_ssl tinyint(1) NOT NULL default '0',
  smtp_max_piped_rcpt tinyint(8) NOT NULL default '1',
  `user` varchar(64) collate utf8_bin default NULL,
  pass varchar(64) collate utf8_bin default NULL,
  max_mails_atonce smallint(6) NOT NULL default '25',
  max_mails_bcc smallint(6) NOT NULL default '50',
  sender_name varchar(255) collate utf8_bin NOT NULL default '',
  sender_email varchar(255) collate utf8_bin NOT NULL default '',
  return_mail varchar(255) collate utf8_bin NOT NULL default '',
  reply_to varchar(255) collate utf8_bin NOT NULL default '',
  delay int(11) NOT NULL default '100000',
  imap_folder_trash varchar(255) collate utf8_bin default NULL,
  imap_folder_sent varchar(255) collate utf8_bin default NULL,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY hosts_aktiv_siteid (aktiv,siteid),
  KEY smtp_auth (smtp_auth),
  KEY standard (standard)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'lnk'
--

CREATE TABLE lnk (
  id bigint(20) NOT NULL auto_increment,
  siteid varchar(64) collate utf8_bin NOT NULL,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  author varchar(64) collate utf8_bin NOT NULL,
  editor varchar(64) collate utf8_bin NOT NULL,
  aktiv tinyint(1) NOT NULL,
  short varchar(48) collate utf8_bin NOT NULL,
  `name` varchar(255) collate utf8_bin NOT NULL,
  url tinytext collate utf8_bin NOT NULL,
  descr tinytext collate utf8_bin NOT NULL,
  clicks bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY siteid (siteid),
  KEY short (short),
  KEY idx_siteid_id (siteid,id),
  KEY idx_siteid_short (siteid,short),
  FULLTEXT KEY short_2 (short)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'lnk_click'
--

CREATE TABLE lnk_click (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'lnk_grp'
--

CREATE TABLE lnk_grp (
  id int(11) NOT NULL auto_increment COMMENT 'unique internal id',
  siteid varchar(64) collate utf8_bin NOT NULL COMMENT 'site id',
  created datetime NOT NULL COMMENT 'creation date',
  updated datetime NOT NULL COMMENT 'last update date',
  author varchar(64) collate utf8_bin NOT NULL COMMENT 'author name/id',
  editor varchar(64) collate utf8_bin NOT NULL COMMENT 'editor name/id',
  aktiv tinyint(1) NOT NULL,
  standard tinyint(1) NOT NULL default '0',
  short varchar(16) collate utf8_bin NOT NULL,
  `name` varchar(255) collate utf8_bin NOT NULL,
  descr tinytext collate utf8_bin NOT NULL,
  PRIMARY KEY  (id),
  KEY siteid (siteid),
  KEY short (short),
  KEY idx_siteid_id (siteid,id),
  KEY idx_siteid_short (siteid,short),
  FULLTEXT KEY short_2 (short)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'lnk_grp_ref'
--

CREATE TABLE lnk_grp_ref (
  id int(11) NOT NULL auto_increment,
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  item_id int(11) NOT NULL default '0',
  grp_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY siteid (siteid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'log'
--

CREATE TABLE log (
  id int(11) NOT NULL auto_increment,
  siteid varchar(255) character set utf8 NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  author_id int(11) NOT NULL default '0',
  `action` enum('new','edit','delete','memo','usage') character set utf8 NOT NULL default 'memo' COMMENT 'ausgefuehrte aktion: new, edit, delete',
  object varchar(64) character set utf8 NOT NULL default '' COMMENT 'wo wurde geaendert',
  property varchar(64) character set utf8 NOT NULL default '' COMMENT 'was wurde geaendert, feldname',
  x_value longtext character set utf8 NOT NULL COMMENT 'alter wert',
  edit_id int(11) NOT NULL default '0' COMMENT 'id des geaenderten eintrags, bzw id des neuen eintrags oder geloeschte',
  `data` longtext character set utf8,
  memo varchar(255) character set utf8 NOT NULL default '' COMMENT 'wenn loeschung, enthaelt dieses feld einen teil de alten daten!',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nl'
--

CREATE TABLE nl (
  id int(11) NOT NULL auto_increment,
  `subject` varchar(255) collate utf8_bin NOT NULL default '',
  title varchar(255) collate utf8_bin default NULL COMMENT 'title, text 1 f. webseite',
  title_sub varchar(255) collate utf8_bin default NULL COMMENT 'subtitle, text 2 f webseite',
  aktiv tinyint(1) NOT NULL default '0',
  body_head text collate utf8_bin NOT NULL COMMENT 'html header',
  body longtext collate utf8_bin,
  body_foot text collate utf8_bin NOT NULL COMMENT 'html footer',
  body_text longtext collate utf8_bin,
  summary longtext collate utf8_bin NOT NULL COMMENT 'zusammenfassung f. webseite',
  link tinytext collate utf8_bin,
  created datetime default NULL,
  updated datetime default NULL,
  `status` tinyint(1) default '0',
  massmail tinyint(1) NOT NULL default '0',
  rcpt_name varchar(255) collate utf8_bin NOT NULL default 'Newsletter',
  clicks smallint(1) default '0',
  views smallint(1) default '0',
  author varchar(128) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin default NULL,
  grp_id int(11) NOT NULL default '0',
  content_type varchar(12) collate utf8_bin NOT NULL default 'html',
  track_image varchar(255) collate utf8_bin NOT NULL default '_global',
  track_personalized tinyint(1) NOT NULL default '1',
  use_inline_images tinyint(1) NOT NULL default '0' COMMENT '0: off, 1:local 2: local+extern',
  is_template tinyint(1) NOT NULL,
  subscribe_frm_id int(11) NOT NULL default '1',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY aktiv (aktiv),
  KEY nl_subject (`subject`),
  KEY grp_id (grp_id),
  KEY siteid (siteid),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nl_attm'
--

CREATE TABLE nl_attm (
  id int(11) NOT NULL auto_increment,
  nl_id int(11) NOT NULL default '0',
  `file` varchar(255) collate utf8_bin NOT NULL default '',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY nl_id (nl_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nl_grp'
--

CREATE TABLE nl_grp (
  id int(11) NOT NULL auto_increment,
  `name` varchar(128) collate utf8_bin NOT NULL default '',
  descr mediumtext collate utf8_bin NOT NULL,
  aktiv tinyint(1) NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  standard tinyint(1) NOT NULL default '0',
  color varchar(10) collate utf8_bin default '#ffffff',
  created datetime NOT NULL default '0000-00-00 00:00:00',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  author varchar(64) collate utf8_bin NOT NULL default '',
  editor varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY `name` (`name`),
  KEY aktiv (aktiv),
  KEY siteid (siteid),
  KEY standard (standard)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nl_h'
--

CREATE TABLE nl_h (
  id int(11) NOT NULL auto_increment,
  q_id int(11) NOT NULL default '0',
  nl_id int(11) NOT NULL default '0',
  grp_id int(11) NOT NULL default '0',
  adr_id int(11) NOT NULL default '0',
  host_id int(11) NOT NULL default '0',
  `status` tinyint(1) default NULL,
  created datetime default NULL,
  `errors` smallint(1) default NULL,
  proof smallint(6) NOT NULL default '0' COMMENT 'optional proof return value',
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
  KEY nlh_siteid_status (siteid,`status`),
  KEY h_nlid_adrid_stat (`status`,nl_id,adr_id),
  KEY nlh_siteid_ip (siteid,ip),
  KEY nlh_siteid_qid_ip (siteid,ip,q_id),
  KEY nlh_siteid_ip_grpid (siteid,ip,grp_id),
  KEY nlh_siteid_ip_qid_nlid (siteid,ip,q_id,nl_id),
  KEY host_id (host_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'nl_q'
--

CREATE TABLE nl_q (
  id int(11) NOT NULL auto_increment,
  nl_id int(11) NOT NULL default '0',
  grp_id int(11) NOT NULL default '0',
  host_id int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  created datetime default NULL,
  updated datetime NOT NULL,
  send_at datetime default NULL,
  check_blacklist tinyint(4) NOT NULL default '1',
  proof tinyint(1) NOT NULL default '1' COMMENT 'do proofing?',
  autogen tinyint(1) NOT NULL default '0',
  touch tinyint(4) NOT NULL,
  use_inline_images tinyint(1) NOT NULL default '0' COMMENT '0: off, 1:local 2: local+extern',
  sent datetime default NULL,
  author varchar(64) collate utf8_bin default NULL,
  editor varchar(64) collate utf8_bin NOT NULL,
  convert_email tinyint(1) NOT NULL default '0',
  combine_email tinyint(1) NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY nl_id (nl_id,grp_id,`status`),
  KEY siteid (siteid),
  KEY send_at (send_at),
  KEY host_id (host_id),
  KEY autostart (autogen),
  KEY touch (touch)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle 'user'
--

CREATE TABLE `user` (
  id int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_bin NOT NULL default '',
  passwd varchar(64) collate utf8_bin NOT NULL default '',
  crypt varchar(128) collate utf8_bin NOT NULL default '',
  email varchar(255) collate utf8_bin NOT NULL default '',
  last_login int(11) NOT NULL default '0',
  aktiv tinyint(1) NOT NULL default '1',
  admin tinyint(1) NOT NULL default '0',
  manager tinyint(1) NOT NULL default '0',
  style varchar(64) collate utf8_bin NOT NULL default 'default',
  lang varchar(8) collate utf8_bin NOT NULL default 'de',
  expert tinyint(1) default '0',
  demo tinyint(1) NOT NULL default '0',
  startpage varchar(64) collate utf8_bin NOT NULL default 'Welcome' COMMENT 'page/action after login',
  debug tinyint(1) NOT NULL default '0',
  debug_lang tinyint(1) NOT NULL default '0',
  debug_lang_level tinyint(1) NOT NULL default '0',
  siteid varchar(64) collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (id),
  KEY `name` (`name`,passwd,aktiv,siteid),
  KEY lang (lang)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

