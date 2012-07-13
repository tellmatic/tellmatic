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

class tm_HOST {
	/**
	* Host Object
	* @var array
	*/
	var $HOST=Array();
	/**
	* DB Object
	* @var object
	*/
	var $DB;
	/**
	* Helper DB Object
	* @var object
	*/
	var $DB2;
	/**
	* LOG Object
	* @var object
	*/	
	var $LOG;
  
	/**
	* Constructor, creates new Instances for DB and LOG Objects 
	* @param
	*/	
	function tm_HOST() {
		#$this->DB=new tm_DB();
		$this->DB=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
	}
	/**
	* get Item
	* @param
	* @return boolean
	*/	
	function getHost($id=0,$search=Array()) {
		$this->HOST=Array();
		#$DB=new tm_DB();
		$Query ="SELECT ".TM_TABLE_HOST.".id, "
										.TM_TABLE_HOST.".name, "
										.TM_TABLE_HOST.".host, "
										.TM_TABLE_HOST.".type, "
										.TM_TABLE_HOST.".port, "
										.TM_TABLE_HOST.".options, "
										.TM_TABLE_HOST.".smtp_auth, "
										.TM_TABLE_HOST.".smtp_domain, "
										.TM_TABLE_HOST.".smtp_ssl, "
										.TM_TABLE_HOST.".smtp_max_piped_rcpt, "
										.TM_TABLE_HOST.".aktiv, "
										.TM_TABLE_HOST.".standard, "
										.TM_TABLE_HOST.".user, "
										.TM_TABLE_HOST.".pass, "
										.TM_TABLE_HOST.".max_mails_atonce, "
										.TM_TABLE_HOST.".max_mails_bcc, "
										.TM_TABLE_HOST.".sender_name, "
										.TM_TABLE_HOST.".sender_email, "
										.TM_TABLE_HOST.".return_mail, "
										.TM_TABLE_HOST.".reply_to, "
										.TM_TABLE_HOST.".delay, "
										.TM_TABLE_HOST.".imap_folder_trash, "
										.TM_TABLE_HOST.".imap_folder_sent, "
										.TM_TABLE_HOST.".siteid";
		$Query .=" FROM ".TM_TABLE_HOST;
		$Query .=" WHERE ".TM_TABLE_HOST.".siteid='".TM_SITEID."'";
		if (check_dbid($id)) {
			$Query .= " AND ".TM_TABLE_HOST.".id=".checkset_int($id);
		}
		if (isset($search['type']) && !empty($search['type'])) {
			$Query .= " AND ".TM_TABLE_HOST.".type='".dbesc($search['type'])."'";
		}
		if (isset($search['aktiv']) && !empty($search['aktiv'])) {
			$Query .= " AND ".TM_TABLE_HOST.".aktiv=".checkset_int($search['aktiv']);
		}
		if (isset($search['standard']) && !empty($search['standard'])) {
			$Query .= " AND ".TM_TABLE_HOST.".standard=".checkset_int($search['standard']);
		}
		if (!empty($sortIndex)) {
			$Query .= " ORDER BY ".dbesc($sortIndex);
			if ($sortType==0) {
				$Query .= " ASC";
			}
			if ($sortType==1) {
				$Query .= " DESC";
			}
		}
		$this->DB->Query($Query);
		$hc=0;
		while ($this->DB->next_record()) {
			$this->HOST[$hc]['id']=$this->DB->Record['id'];
			$this->HOST[$hc]['name']=$this->DB->Record['name'];
			$this->HOST[$hc]['host']=$this->DB->Record['host'];
			$this->HOST[$hc]['type']=$this->DB->Record['type'];
			$this->HOST[$hc]['port']=$this->DB->Record['port'];
			$this->HOST[$hc]['options']=$this->DB->Record['options'];
			$this->HOST[$hc]['smtp_auth']=$this->DB->Record['smtp_auth'];
			$this->HOST[$hc]['smtp_domain']=$this->DB->Record['smtp_domain'];
			$this->HOST[$hc]['smtp_ssl']=$this->DB->Record['smtp_ssl'];
			$this->HOST[$hc]['smtp_max_piped_rcpt']=$this->DB->Record['smtp_max_piped_rcpt'];
			$this->HOST[$hc]['aktiv']=$this->DB->Record['aktiv'];
			$this->HOST[$hc]['standard']=$this->DB->Record['standard'];
			$this->HOST[$hc]['user']=$this->DB->Record['user'];
			$this->HOST[$hc]['pass']=$this->DB->Record['pass'];
			$this->HOST[$hc]['max_mails_atonce']=$this->DB->Record['max_mails_atonce'];
			$this->HOST[$hc]['max_mails_bcc']=$this->DB->Record['max_mails_bcc'];
			$this->HOST[$hc]['sender_name']=$this->DB->Record['sender_name'];
			$this->HOST[$hc]['sender_email']=$this->DB->Record['sender_email'];
			$this->HOST[$hc]['return_mail']=$this->DB->Record['return_mail'];
			$this->HOST[$hc]['reply_to']=$this->DB->Record['reply_to'];
			$this->HOST[$hc]['delay']=$this->DB->Record['delay'];
			$this->HOST[$hc]['imap_folder_trash']=$this->DB->Record['imap_folder_trash'];
			$this->HOST[$hc]['imap_folder_sent']=$this->DB->Record['imap_folder_sent'];
			$this->HOST[$hc]['siteid']=$this->DB->Record['siteid'];
			$hc++;
		}
		return $this->HOST;
	}//getHost

	function setAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_HOST." 
								SET aktiv=".checkset_int($aktiv)."
								WHERE id=".checkset_int($id)." 
								AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"host","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAktiv


	function addHost($host) {
		$Return[0]=false;
		$Return[1]=0;
		$Query ="INSERT INTO ".TM_TABLE_HOST." (
					name,host,
					type,port,
					options,
					smtp_auth,
					smtp_domain,
					smtp_ssl,
					smtp_max_piped_rcpt,
					user,pass,
					max_mails_atonce, max_mails_bcc,
					sender_name, sender_email, return_mail,reply_to,
					delay,
					imap_folder_sent,
					imap_folder_trash,
					aktiv,standard,
					siteid
					)
					VALUES (
					'".dbesc($host["name"])."', '".dbesc($host["host"])."',
					'".dbesc($host["type"])."', ".checkset_int($host["port"]).",
					'".dbesc($host["options"])."',
					'".dbesc($host["smtp_auth"])."',
					'".dbesc($host["smtp_domain"])."',
					".checkset_int($host["smtp_ssl"]).",
					".checkset_int($host["smtp_max_piped_rcpt"]).",
					'".dbesc($host["user"])."',	'".dbesc($host["pass"])."',
					".checkset_int($host["max_mails_atonce"]).",".checkset_int($host["max_mails_bcc"]).",
					'".dbesc($host["sender_name"])."',	'".dbesc($host["sender_email"])."',	'".dbesc($host["return_mail"])."',	'".dbesc($host["reply_to"])."',
					".checkset_int($host["delay"]).",
					'".dbesc($host["imap_folder_sent"])."',
					'".dbesc($host["imap_folder_trash"])."',					
					".checkset_int($host["aktiv"]).",0,
					'".TM_SITEID."')";
		if ($this->DB->Query($Query)) {
				if ($this->DB->LastInsertID != 0) {
					$Return[0]=true;
					$Return[1]=$this->DB->LastInsertID;
				}
				//log
				$host['id']=$this->DB->LastInsertID;
				if (TM_LOG) $this->LOG->log(Array("data"=>$host,"object"=>"host","action"=>"new"));
		}
		return $Return;
	}//addHost

	function updateHost($host) {
		$Return=false;
		if (isset($host['id']) && check_dbid($host['id'])) {
			$Query ="UPDATE ".TM_TABLE_HOST."
					SET
					name='".dbesc($host["name"])."', host='".dbesc($host["host"])."',
					type='".dbesc($host["type"])."', port=".checkset_int($host["port"]).",
					options='".dbesc($host["options"])."',
					smtp_auth='".dbesc($host["smtp_auth"])."',
					smtp_domain='".dbesc($host["smtp_domain"])."',
					smtp_ssl=".checkset_int($host["smtp_ssl"]).",
					smtp_max_piped_rcpt=".checkset_int($host["smtp_max_piped_rcpt"]).",
					user='".dbesc($host["user"])."', pass='".dbesc($host["pass"])."',
					max_mails_atonce=".checkset_int($host["max_mails_atonce"]).", max_mails_bcc=".checkset_int($host["max_mails_bcc"]).",
					sender_name='".dbesc($host["sender_name"])."', sender_email='".dbesc($host["sender_email"])."', return_mail='".dbesc($host["return_mail"])."', reply_to='".dbesc($host["reply_to"])."',
					delay=".checkset_int($host["delay"]).",
					imap_folder_sent='".dbesc($host["imap_folder_sent"])."',
					imap_folder_trash='".dbesc($host["imap_folder_trash"])."',
					aktiv=".checkset_int($host["aktiv"])."
					WHERE siteid='".TM_SITEID."' AND id=".checkset_int($host["id"]);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$host,"object"=>"host","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//updateHost

	function delHost($id) {
		$Return=false;
		if (check_dbid($id)) {
			//log before deletion
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"host","action"=>"delete"));
			$Query ="DELETE FROM ".TM_TABLE_HOST." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		//todo: defaulthost fuer offene versendeauftraege!!! host nur loeschen wenn nicht aktuell benutzt wird! oder komplett loeschen auch aus refs und ersetzen....
		return $Return;
	}//delHost

	function getStdSMTPHost() {
		$Return=$this->getHost(0,Array("standard"=>1, "aktiv"=>1, "type"=>"smtp"));
		return $Return;
	}

	function setHostStd($id=0) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_HOST."
						SET standard=0
						WHERE standard=1
						AND siteid='".TM_SITEID."'";
			//log				
			$HOSTSTD=$this->getStdSMTPHost();
			if ($this->DB->Query($Query)) {
				//log				
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>0,"id"=>$HOSTSTD[0]['id']),"object"=>"host","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			$Query ="UPDATE ".TM_TABLE_HOST."
						SET standard=1
						WHERE id=".checkset_int($id)."
						AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				//log				
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>1,"id"=>$id),"object"=>"host","action"=>"edit"));			
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//setHostStd
}  //class
?>