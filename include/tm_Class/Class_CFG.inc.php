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


class tm_CFG {
	var $C=Array();
	var $USER=Array();

	/**
	* LOG Object
	* @var object
	*/	var $LOG;
  
	/**
	* Constructor, creates new Instances for DB and LOG Objects 
	* @param
	*/	
	function tm_CFG() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
	}
	
//LOGIN / LOGOUT
	function Logout() {
		//logout....
		global $user_name,$user_pw,$Style;
		$user_name="";
		$user_pw="";
		$Style="default";
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
		   setcookie(session_name(), '', time()-42000, '/');
		}
		// Finally, destroy the session.
		session_unset();
		session_destroy();
	}

	function Login($name,$passwd,$checkadmin=0) {
		$Return=false;
		if ($this->checkUserLogin($name,$passwd,$checkadmin)) {
			//ok, logged in , userdaten holen
			$this->getUser($name);
			//wenn ok
			//cookie setzen
			$Return=true;
		}
		return $Return;
	}


	function LoginHTTP($checkadmin=0,$msg="new Login") {
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			$this->authenticate($msg);
		}
		$LoginName=$_SERVER['PHP_AUTH_USER'];
		$PassWD=$_SERVER['PHP_AUTH_PW'];
		if ($this->checkUserLogin($LoginName,$PassWD,$checkadmin)) {
			$this->User=$LoginName;
			return true;
		}	else {
	 		$this->authenticate("another Login");
	 	}
	}

	function checkUserLogin($name,$passwd,$checkadmin=0) {
		$Query="
				SELECT name,aktiv,admin FROM ".TM_TABLE_USER."
				WHERE name='".dbesc($name)."'
				AND passwd='".dbesc($passwd)."'
				AND aktiv='1'
				AND siteid='".TM_SITEID."'
				ORDER by name
				";
		$this->DB->Query($Query);
		if ($this->DB->next_record())	{
			$isAdmin=$this->DB->Record['admin'];
			if ($checkadmin==1) {
				if ($isAdmin==1) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}	else {
			return false;
	 	}
	}//checkUserLogin

	function authenticate($msg)
	{
		Header("WWW-Authenticate: Basic realm=$msg");
		Header("HTTP/1.0 401 Unauthorized");
		echo "<html><head></head><body>$msg<br>Sie sind nicht angemeldet! You are not logged in!<br><b>Zugriff verweigert! Access denied!</b><br>";
		echo "</body></html>";
		exit;
	}

//USER
	function getUser($user,$id=0) {
		$this->USER=Array();
		$USER_=$this->getUsers($user,$id);
		$this->USER=$USER_[0];
		return $this->USER;
	}//getUserSettings

	function getUserName($id=0) {
		$USER_=$this->getUsers("",$id);
		if ( isset($USER_[0]) && !empty($USER_[0]['name']) ) {
			return $USER_[0]['name'];
		} else {
			return "-- ".___("Unbekannt")." --";
		}
	}
	function getUsers($user="",$id=0) {
		$USER=Array();//this->
		$Query ="	SELECT id,
							name,
							passwd,
							crypt,
							last_login,
							email,
							admin,
							manager,
							style,
							lang,
							expert,
							demo,
							startpage,
							debug,
							debug_lang,
							debug_lang_level,
							aktiv,
							siteid
						FROM ".TM_TABLE_USER."
						WHERE siteid='".TM_SITEID."'";
		if (!empty($user)) {
			$Query .=" AND name='".dbesc($user)."'";
		}
		if (check_dbid($id)) {
			$Query .=" AND id=".checkset_int($id);
		}
		if (!empty($user) || check_dbid($id)) {
			$Query .=" LIMIT 1";
		}

		$this->DB->Query($Query);
		$uc=0;
		while ($this->DB->next_record()) {
			$USER[$uc]['id']=$this->DB->Record['id'];//this->
			$USER[$uc]['name']=$this->DB->Record['name'];
			$USER[$uc]['passwd']=$this->DB->Record['passwd'];
			$USER[$uc]['crypt']=$this->DB->Record['crypt'];
			$USER[$uc]['email']=$this->DB->Record['email'];
			$USER[$uc]['last_login']=$this->DB->Record['last_login'];
			$USER[$uc]['admin']=$this->DB->Record['admin'];
			$USER[$uc]['manager']=$this->DB->Record['manager'];
			$USER[$uc]['style']=$this->DB->Record['style'];
			$USER[$uc]['lang']=$this->DB->Record['lang'];
			$USER[$uc]['expert']=$this->DB->Record['expert'];
			$USER[$uc]['demo']=$this->DB->Record['demo'];
			$USER[$uc]['startpage']=$this->DB->Record['startpage'];
			$USER[$uc]['debug']=$this->DB->Record['debug'];
			$USER[$uc]['debug_lang']=$this->DB->Record['debug_lang'];
			$USER[$uc]['debug_lang_level']=$this->DB->Record['debug_lang_level'];
			$USER[$uc]['aktiv']=$this->DB->Record['aktiv'];
			$USER[$uc]['siteid']=$this->DB->Record['siteid'];
			$uc++;
		}
		return $USER;//this->
	}//getUsers

	function addUSER($user) {
		$Return=false;
		$Query ="INSERT INTO "
					.TM_TABLE_USER
					." (
						name,passwd,crypt,email,
						last_login,
						aktiv,admin,manager,
						style,lang,
						expert,demo,
						startpage,
						debug,debug_lang,debug_lang_level,
						siteid
					) VALUES (	"
					."'".dbesc($user['name'])."',"
					."'".dbesc($user['passwd'])."',"
					."'".dbesc($user['crypt'])."',"
					."'".dbesc($user['email'])."',"
					."0,"
					.checkset_int($user['aktiv']).","
					.checkset_int($user['admin']).","
					.checkset_int($user['manager']).","
					."'".dbesc($user['style'])."',"
					."'".dbesc($user['lang'])."',"
					.checkset_int($user['expert']).","
					.checkset_int($user['demo']).","
					."'".dbesc($user['startpage'])."',"
					.checkset_int($user['debug']).","
					.checkset_int($user['debug_lang']).","
					.checkset_int($user['debug_lang_level']).","
					."'".dbesc($user['siteid'])."'"
					.")	";
		if ($this->DB->Query($Query)) {
			//log
			$user['id']=$this->DB->LastInsertID;
			if (TM_LOG) $this->LOG->log(Array("data"=>$user,"object"=>"usr","action"=>"new"));
			$Return=true;
		}
		return $Return;
	}//addUSER

	function updateUser($user) {
		$Return=false;
		if (check_dbid($user['id'])) {
			$Query ="UPDATE ".TM_TABLE_USER."
					SET
					name='".dbesc($user["name"])."',
					email='".dbesc($user["email"])."',
					admin='".checkset_int($user["admin"])."',
					manager='".checkset_int($user["manager"])."',
					style='".dbesc($user["style"])."',
					lang='".dbesc($user["lang"])."',
					expert='".checkset_int($user["expert"])."',
					demo='".checkset_int($user["demo"])."',
					startpage='".dbesc($user["startpage"])."',
					debug='".checkset_int($user["debug"])."',
					debug_lang='".checkset_int($user["debug_lang"])."',
					debug_lang_level='".checkset_int($user["debug_lang_level"])."',
					aktiv='".checkset_int($user["aktiv"])."'
					WHERE id=".checkset_int($user['id'])." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$user,"object"=>"usr","action"=>"edit"));
				$Return=true;
			}
			return $Return;
		}
	}
	
	function delUser($id=0) {
		$Return=false;
		if (check_dbid($id)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"usr","action"=>"delete"));
			$Query ="DELETE FROM ".TM_TABLE_USER." WHERE id=".checkset_int($id)." AND admin!='1' AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//delUser
	
	function setPasswd($user,$passwd,$crypt) {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET passwd='".dbesc($passwd)."', crypt='".dbesc($crypt)."' WHERE siteid='".dbesc(TM_SITEID)."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("passwd"=>"[new passwd]","id"=>$U['id']),"object"=>"usr","action"=>"edit"));			$Return=true;
		}
		return $Return;
	}//setPasswd

	//update last_login
	function setTime($user) {
		$Return=false;
		$time=time();
		$Query ="UPDATE ".TM_TABLE_USER." SET last_login='".$time."' WHERE siteid='".dbesc(TM_SITEID)."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			$Return=$time;
		}
		return $Return;
	}//setPasswd

	function setStyle($user,$style="default") {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET style='".dbesc($style)."' WHERE siteid='".TM_SITEID."'	AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("style"=>$style,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setStyle

	function setEMail($user,$email) {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `email`='".dbesc($email)."' WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("email"=>$email,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}

	function setLang($user,$lang="de") {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `lang`='".dbesc($lang)."' WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("lang"=>$lang,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setLang

	function setStartpage($user,$startpage="Welcome") {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `startpage`='".dbesc($startpage)."' WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("startpage"=>$startpage,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setLang


	function setExpert($user,$expert=0) {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `expert`=".checkset_int($expert)." WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("expert"=>$expert,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setExpert

	function setDemo($user,$demo=1) {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `demo`=".checkset_int($demo)." WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("demo"=>$demo,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setDemo

	function setDebug($user,$debug=0) {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `debug`=".checkset_int($debug)." WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("debug"=>$debug,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setDemo

	function setDebugLang($user,$debug_lang=0,$debug_lang_level=0) {
		$U=$this->getUser($user);
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_USER." SET `debug_lang`=".checkset_int($debug_lang).",`debug_lang_level`=".checkset_int($debug_lang_level)." WHERE siteid='".TM_SITEID."' AND name='".dbesc($user)."'";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("debug_lang"=>$debug_lang,"debug_lang_level"=>$debug_lang_level,"id"=>$U['id']),"object"=>"usr","action"=>"edit"));
			$Return=true;
		}
		return $Return;
	}//setDemo


	function setUSERAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_USER." SET aktiv=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND admin!='1' AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"usr","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAktiv

	function setAdmin($id,$admin=0) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_USER." SET `admin`=".checkset_int($admin)." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("admin"=>$admin,"id"=>$id),"object"=>"usr","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAdmin

	function setManager($id,$manager=0) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_USER." SET `manager`=".checkset_int($manager)." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("manager"=>$manager,"id"=>$id),"object"=>"usr","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setManager


 //CONFIG
	function getSites() {
		$Sites=Array();
		$Query ="	SELECT id,
							name, siteid
						FROM ".TM_TABLE_CONFIG."
					";
		$this->DB->Query($Query);
		$cc=0;
		while ($this->DB->next_record()) {
			$Sites[$cc]['id']=$this->DB->Record['id'];
			$Sites[$cc]['name']=$this->DB->Record['name'];
			$Sites[$cc]['siteid']=$this->DB->Record['siteid'];
			$cc++;
		}
		return $Sites;
	}//getSites

	function getCFG($siteid) {
		$this->C=Array();
		$Query ="
						SELECT id,
							siteid,
							name,
							lang,
							style,
							notify_mail,
							notify_subscribe,
							notify_unsubscribe,
							emailcheck_intern,
							emailcheck_subscribe,
							emailcheck_sendit,
							emailcheck_checkit,
							max_mails_retry,
							check_version,
							track_image,
							rcpt_name,
							unsubscribe_use_captcha,
							unsubscribe_digits_captcha,
							unsubscribe_sendmail,
							unsubscribe_action,
							unsubscribe_host,
							checkit_limit,
							checkit_from_email,
							checkit_adr_reset_error,
							checkit_adr_reset_status,
							bounceit_limit,
							bounceit_host,
							bounceit_action,
							bounceit_search,
							bounceit_filter_to,
							bounceit_filter_to_email,
							proof,
							proof_url,
							proof_trigger,
							proof_pc
						FROM ".TM_TABLE_CONFIG."
						WHERE siteid='".TM_SITEID."'
						LIMIT 1
					";
		$this->DB->Query($Query);
		$cc=0;
		if ($this->DB->next_record()) {
			$this->C[$cc]['id']=$this->DB->Record['id'];
			$this->C[$cc]['siteid']=$this->DB->Record['siteid'];
			$this->C[$cc]['name']=$this->DB->Record['name'];
			$this->C[$cc]['lang']=$this->DB->Record['lang'];
			$this->C[$cc]['style']=$this->DB->Record['style'];
			$this->C[$cc]['siteid']=TM_SITEID;
			$this->C[$cc]['notify_mail']=$this->DB->Record['notify_mail'];
			$this->C[$cc]['notify_subscribe']=$this->DB->Record['notify_subscribe'];
			$this->C[$cc]['notify_unsubscribe']=$this->DB->Record['notify_unsubscribe'];
			$this->C[$cc]['emailcheck_intern']=$this->DB->Record['emailcheck_intern'];
			$this->C[$cc]['emailcheck_subscribe']=$this->DB->Record['emailcheck_subscribe'];
			$this->C[$cc]['emailcheck_sendit']=$this->DB->Record['emailcheck_sendit'];
			$this->C[$cc]['emailcheck_checkit']=$this->DB->Record['emailcheck_checkit'];
			$this->C[$cc]['max_mails_retry']=$this->DB->Record['max_mails_retry'];
			$this->C[$cc]['check_version']=$this->DB->Record['check_version'];
			$this->C[$cc]['track_image']=$this->DB->Record['track_image'];
			$this->C[$cc]['rcpt_name']=$this->DB->Record['rcpt_name'];
			$this->C[$cc]['unsubscribe_use_captcha']=$this->DB->Record['unsubscribe_use_captcha'];
			$this->C[$cc]['unsubscribe_digits_captcha']=$this->DB->Record['unsubscribe_digits_captcha'];
			$this->C[$cc]['unsubscribe_sendmail']=$this->DB->Record['unsubscribe_sendmail'];
			$this->C[$cc]['unsubscribe_action']=$this->DB->Record['unsubscribe_action'];
			$this->C[$cc]['unsubscribe_host']=$this->DB->Record['unsubscribe_host'];
			$this->C[$cc]['checkit_limit']=$this->DB->Record['checkit_limit'];
			$this->C[$cc]['checkit_from_email']=$this->DB->Record['checkit_from_email'];
			$this->C[$cc]['checkit_adr_reset_error']=$this->DB->Record['checkit_adr_reset_error'];
			$this->C[$cc]['checkit_adr_reset_status']=$this->DB->Record['checkit_adr_reset_status'];
			$this->C[$cc]['bounceit_limit']=$this->DB->Record['bounceit_limit'];
			$this->C[$cc]['bounceit_host']=$this->DB->Record['bounceit_host'];
			$this->C[$cc]['bounceit_action']=$this->DB->Record['bounceit_action'];
			$this->C[$cc]['bounceit_search']=$this->DB->Record['bounceit_search'];
			$this->C[$cc]['bounceit_filter_to']=$this->DB->Record['bounceit_filter_to'];
			$this->C[$cc]['bounceit_filter_to_email']=$this->DB->Record['bounceit_filter_to_email'];
			$this->C[$cc]['proof']=$this->DB->Record['proof'];
			$this->C[$cc]['proof_url']=$this->DB->Record['proof_url'];
			$this->C[$cc]['proof_trigger']=$this->DB->Record['proof_trigger'];
			$this->C[$cc]['proof_pc']=$this->DB->Record['proof_pc'];
		}
		return $this->C;
	}//getCFG

	function addCFG($cfg) {
		$Return=false;

		$Query ="INSERT INTO
						".TM_TABLE_CONFIG."
					(
					name,
					lang,
					style,
					notify_mail,
					notify_subscribe,
					notify_unsubscribe,
					emailcheck_intern,
					emailcheck_subscribe,
					emailcheck_sendit,
					emailcheck_checkit,
					check_version,
					max_mails_retry,
					track_image,
					rcpt_name,
					unsubscribe_use_captcha,
					unsubscribe_digits_captcha,
					unsubscribe_sendmail,
					unsubscribe_action,
					unsubscribe_host,
					checkit_limit,
					checkit_from_email,
					checkit_adr_reset_error,
					checkit_adr_reset_status,
					bounceit_limit,
					bounceit_host,
					bounceit_action,
					bounceit_search,
					bounceit_filter_to,
					bounceit_filter_to_email,
					proof,
					proof_url,
					proof_trigger,
					proof_pc,
					siteid
					)
					VALUES
					(
					'".dbesc($cfg["name"])."',
					'".dbesc($cfg["lang"])."',
					'".dbesc($cfg["style"])."',
					'".dbesc($cfg["notify_mail"])."',
					".checkset_int($cfg["notify_subscribe"]).",
					".checkset_int($cfg["notify_unsubscribe"]).",
					".checkset_int($cfg["emailcheck_intern"]).",
					".checkset_int($cfg["emailcheck_subscribe"]).",
					".checkset_int($cfg["emailcheck_sendit"]).",
					".checkset_int($cfg["emailcheck_checkit"]).",
					".checkset_int($cfg["check_version"]).",
					".checkset_int($cfg["max_mails_retry"]).",
					'".dbesc($cfg["track_image"])."',
					'".dbesc($cfg["rcpt_name"])."',
					".checkset_int($cfg["unsubscribe_use_captcha"]).",
					".checkset_int($cfg["unsubscribe_digits_captcha"]).",
					".checkset_int($cfg["unsubscribe_sendmail"]).",
					'".dbesc($cfg["unsubscribe_action"])."',
					".checkset_int($cfg["unsubscribe_host"]).",
					".checkset_int($cfg["checkit_limit"]).",
					'".dbesc($cfg["checkit_from_email"])."',
					".checkset_int($cfg["checkit_adr_reset_error"]).",
					".checkset_int($cfg["checkit_adr_reset_status"]).",
					".checkset_int($cfg["bounceit_limit"]).",
					".checkset_int($cfg["bounceit_host"]).",
					'".dbesc($cfg["bounceit_action"])."',
					'".dbesc($cfg["bounceit_search"])."',
					".checkset_int($cfg["bounceit_filter_to"]).",
					'".dbesc($cfg["bounceit_filter_to_email"])."',
					".checkset_int($cfg["proof"]).",
					'".dbesc($cfg["proof_url"])."',
					".checkset_int($cfg["proof_trigger"]).",
					".checkset_int($cfg["proof_pc"]).",
					'".dbesc($cfg["siteid"])."'
					)
					";
		if ($this->DB->Query($Query)) {
			//log
			$cfg['id']=$this->DB->LastInsertID;
			if (TM_LOG) $this->LOG->log(Array("data"=>$cfg,"object"=>"cfg","action"=>"new"));
			$Return=true;
		} else {
			$Return=false;
			return $Return;
		}
		return $Return;
	}//addCFG

	function updateCFG($cfg) {
		$Return=false;
		$Query ="UPDATE ".TM_TABLE_CONFIG."
					SET
					name='".dbesc($cfg["name"])."',
					lang='".dbesc($cfg["lang"])."',
					style='".dbesc($cfg["style"])."',
					notify_mail='".dbesc($cfg["notify_mail"])."',
					notify_subscribe=".checkset_int($cfg["notify_subscribe"]).",
					notify_unsubscribe=".checkset_int($cfg["notify_unsubscribe"]).",
					emailcheck_intern=".checkset_int($cfg["emailcheck_intern"]).",
					emailcheck_subscribe=".checkset_int($cfg["emailcheck_subscribe"]).",
					emailcheck_sendit=".checkset_int($cfg["emailcheck_sendit"]).",
					emailcheck_checkit=".checkset_int($cfg["emailcheck_checkit"]).",
					check_version=".checkset_int($cfg["check_version"]).",
					max_mails_retry=".checkset_int($cfg["max_mails_retry"]).",
					track_image='".dbesc($cfg["track_image"])."',
					rcpt_name='".dbesc($cfg["rcpt_name"])."',
					unsubscribe_use_captcha=".checkset_int($cfg["unsubscribe_use_captcha"]).",
					unsubscribe_digits_captcha=".checkset_int($cfg["unsubscribe_digits_captcha"]).",
					unsubscribe_sendmail=".checkset_int($cfg["unsubscribe_sendmail"]).",
					unsubscribe_action='".dbesc($cfg["unsubscribe_action"])."',
					unsubscribe_host='".dbesc($cfg["unsubscribe_host"])."',
					checkit_limit=".checkset_int($cfg["checkit_limit"]).",
					checkit_from_email='".dbesc($cfg["checkit_from_email"])."',
					checkit_adr_reset_error=".checkset_int($cfg["checkit_adr_reset_error"]).",
					checkit_adr_reset_status=".checkset_int($cfg["checkit_adr_reset_status"]).",
					bounceit_limit=".checkset_int($cfg["bounceit_limit"]).",
					bounceit_host=".checkset_int($cfg["bounceit_host"]).",
					bounceit_action='".dbesc($cfg["bounceit_action"])."',
					bounceit_search='".dbesc($cfg["bounceit_search"])."',
					bounceit_filter_to=".checkset_int($cfg["bounceit_filter_to"]).",
					bounceit_filter_to_email='".dbesc($cfg["bounceit_filter_to_email"])."',
					proof=".checkset_int($cfg["proof"]).",
					proof_url='".dbesc($cfg["proof_url"])."',
					proof_trigger=".checkset_int($cfg["proof_trigger"]).",
					proof_pc=".checkset_int($cfg["proof_pc"])."
					WHERE siteid='".dbesc($cfg["siteid"])."'
					";
		if ($this->DB->Query($Query)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>$cfg,"object"=>"cfg","action"=>"edit"));			
			$Return=true;
		}
		return $Return;
	}//updateCFG

}//Class CFG
?>