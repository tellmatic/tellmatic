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


class tm_Q {
	/**
	* Queue
	* @var array
	*/
	var $Q=Array();
	/**
	* Adress Groups
	* @var array
	*/
	var $GRP=Array();
	/**
	* Addresses
	* @var array
	*/
	var $ADR=Array();
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
	function tm_Q() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
	}

	function getQ($id=0,$offset=0,$limit=0,$nl_id=0,$grp_id=0,$status=0,$search=Array()) {
		$this->Q=Array();
		$Query ="SELECT ".TM_TABLE_NL_Q.".id, "
											.TM_TABLE_NL_Q.".nl_id, "
											.TM_TABLE_NL_Q.".grp_id, "
											.TM_TABLE_NL_Q.".host_id, "
											.TM_TABLE_NL_Q.".created, "
											.TM_TABLE_NL_Q.".author, "
											.TM_TABLE_NL_Q.".status, "
											.TM_TABLE_NL_Q.".send_at, "
											.TM_TABLE_NL_Q.".check_blacklist, "
											.TM_TABLE_NL_Q.".autogen, "
											.TM_TABLE_NL_Q.".touch, "
											.TM_TABLE_NL_Q.".proof, "
											.TM_TABLE_NL_Q.".use_inline_images, "
											.TM_TABLE_NL_Q.".sent, "
											.TM_TABLE_NL_Q.".save_imap, "
											.TM_TABLE_NL_Q.".host_id_imap, "
											.TM_TABLE_NL_Q.".siteid
						FROM ".TM_TABLE_NL_Q."
					";
		$Query .=" WHERE ".TM_TABLE_NL_Q.".siteid='".TM_SITEID."'";
		if (check_dbid($nl_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".nl_id=".checkset_int($nl_id)." ";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".grp_id=".checkset_int($grp_id)." ";
		}
		/*
		if (check_dbid($host_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".host_id=".checkset_int($host_id)." ";
		}
		*/
		if ($status>0) {
			$Query .=" AND ".TM_TABLE_NL_Q.".status=".checkset_int($status)." ";
		}
		if (check_dbid($id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".id=".checkset_int($id);
		}
		//nur vor datum
		if (isset($search['send_before'])) {
			$Query .=" AND ".TM_TABLE_NL_Q.".send_at <  '".dbesc($search['send_before'])."'";
		}
		//nur nach datum
		if (isset($search['send_after'])) {
			$Query .=" AND ".TM_TABLE_NL_Q.".send_at >  '".dbesc($search['send_after'])."'";
		}
		//autogen
		if (isset($search['autogen'])) {
			$Query .=" AND ".TM_TABLE_NL_Q.".autogen =".checkset_int($search['autogen']);
		}
		$Query .=" ORDER BY ".TM_TABLE_NL_Q.".created desc	";
		if ($limit >0 and $offset>=0) {
			$Query .= " LIMIT ".checkset_int($offset)." ,".checkset_int($limit);
		}

		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->Q[$ac]['id']=$this->DB->Record['id'];
			$this->Q[$ac]['created']=$this->DB->Record['created'];
			$this->Q[$ac]['author']=$this->DB->Record['author'];
			$this->Q[$ac]['status']=$this->DB->Record['status'];
			$this->Q[$ac]['send_at']=$this->DB->Record['send_at'];
			$this->Q[$ac]['check_blacklist']=$this->DB->Record['check_blacklist'];
			$this->Q[$ac]['autogen']=$this->DB->Record['autogen'];
			$this->Q[$ac]['touch']=$this->DB->Record['touch'];
			$this->Q[$ac]['proof']=$this->DB->Record['proof'];
			$this->Q[$ac]['use_inline_images']=$this->DB->Record['use_inline_images'];
			$this->Q[$ac]['sent']=$this->DB->Record['sent'];
			$this->Q[$ac]['save_imap']=$this->DB->Record['save_imap'];
			$this->Q[$ac]['host_id_imap']=$this->DB->Record['host_id_imap'];
			$this->Q[$ac]['nl_id']=$this->DB->Record['nl_id'];
			$this->Q[$ac]['grp_id']=$this->DB->Record['grp_id'];
			$this->Q[$ac]['host_id']=$this->DB->Record['host_id'];
			$this->Q[$ac]['siteid']=$this->DB->Record['siteid'];
			$ac++;
		}
		return $this->Q;
	}//getQ

	function countQ($nl_id=0,$grp_id=0,$status=0) {
		$this->Q=Array();
		$Query ="SELECT count(id) as c
						FROM ".TM_TABLE_NL_Q;
		$Query .=" WHERE siteid='".TM_SITEID."'";
		if (check_dbid($nl_id)) {
			$Query .=" AND nl_id=".checkset_int($nl_id)." ";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND grp_id=".checkset_int($grp_id)." ";
		}
		if ($status>0) {
			$Query .=" AND status=".checkset_int($status)."";
		}
		$this->DB2->Query($Query);
		$count=0;
		if ($this->DB2->next_record()) {
			$count=$this->DB2->Record['c'];
		}
		return $count;
	}//countQ


	function getQtoPrepare($search=Array()) {
		//q status=1, qid liefern zwischen jetzt und etwas spaeter :)
		//function getQ($id=0,$offset=0,$limit=0,$nl_id=0,$grp_id=0,$status=0,$search=Array())
		//search['send_before' | 'send_after']
		$search['send_before']=date("Y-m-d H:i:s",strtotime("+6 hours"));//zwischen jetzt vor x stunden
		#$search['send_after']=date("Y-m-d H:i:s");//und jetzt...
		$search['autogen']=1;//auto refresh/regenerate
		$status=1;
		$limit=1;//standardmaessig nur 1 eintrag zurueckgeben...
		if (isset($search['limit'])) $limit=$search['limit'];
		$Return=$this->getQ($id=0,$offset=0,$limit,$nl_id=0,$grp_id=0,$status,$search);
		return $Return;
		/*
		date("Y-m-d H:i:s",strtotime("-6 hour")); // Same applies for months e.g. "-1 months" - 1 days etc
		The unit of time displacement may be selected by the string ‘year’ or ‘month’ for moving by whole years or months. 
		These are fuzzy units, as years and months are not all of equal duration. 
		More precise units are ‘fortnight’ which is worth 14 days, ‘week’ worth 7 days, ‘day’ worth 24 hours, ‘hour’ worth 60 minutes, ‘minute’ or ‘min’ worth 60 seconds, and ‘second’ or ‘sec’ worth one second. 
		An ‘s’ suffix on these units is accepted and ignored.
		*/

	}

	function setAutogen($id=0,$autogen=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_Q." SET autogen=".checkset_int($autogen)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("autogen"=>$autogen,"id"=>$id),"object"=>"q","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAutogen

	function getQtoSend($id=0,$offset=0,$limit=0,$nl_id=0,$grp_id=0,$search=Array()) {
	//almost same as getQ, but multiple status etc
		$this->Q=Array();
		if (!isset($search['send_at'])) {
			$send_at=date("Y-m-d H:i:s");
		}
		$Query ="SELECT ".TM_TABLE_NL_Q.".id, "
											.TM_TABLE_NL_Q.".nl_id, "
											.TM_TABLE_NL_Q.".grp_id, "
											.TM_TABLE_NL_Q.".host_id, "
											.TM_TABLE_NL_Q.".created, "
											.TM_TABLE_NL_Q.".author, "
											.TM_TABLE_NL_Q.".status, "
											.TM_TABLE_NL_Q.".check_blacklist, "
											.TM_TABLE_NL_Q.".autogen, "
											.TM_TABLE_NL_Q.".proof, "
											.TM_TABLE_NL_Q.".touch, "
											.TM_TABLE_NL_Q.".use_inline_images, "
											.TM_TABLE_NL_Q.".send_at, "
											.TM_TABLE_NL_Q.".sent, "
											.TM_TABLE_NL_Q.".save_imap, "
											.TM_TABLE_NL_Q.".host_id_imap, "
											.TM_TABLE_NL_Q.".siteid
						FROM ".TM_TABLE_NL_Q."
					";
		$Query .=" WHERE ".TM_TABLE_NL_Q.".siteid='".TM_SITEID."'";

		$Query .=" AND (
							".TM_TABLE_NL_Q.".status=2 OR ".TM_TABLE_NL_Q.".status=3 OR ".TM_TABLE_NL_Q.".status=6
							)
							";
		//terminierter versand!!!!
		$Query .=" AND ".TM_TABLE_NL_Q.".send_at <  '".dbesc($send_at)."'";
		if (check_dbid($id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".id=".checkset_int($id)." ";
		}
		if (check_dbid($nl_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".nl_id=".checkset_int($nl_id)." ";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".grp_id=".checkset_int($grp_id)." ";
		}
		$Query .=" ORDER BY ".TM_TABLE_NL_Q.".send_at asc,".TM_TABLE_NL_Q.".status asc	";
		if ($limit >0 and $offset>=0) {
			$Query .= " LIMIT ".checkset_int($offset)." ,".checkset_int($limit);
		}
		$this->DB->Query($Query);
		$qc=0;
		while ($this->DB->next_record()) {
			$this->Q[$qc]['id']=$this->DB->Record['id'];
			$this->Q[$qc]['created']=$this->DB->Record['created'];
			$this->Q[$qc]['author']=$this->DB->Record['author'];
			$this->Q[$qc]['status']=$this->DB->Record['status'];
			$this->Q[$qc]['send_at']=$this->DB->Record['send_at'];
			$this->Q[$qc]['check_blacklist']=$this->DB->Record['check_blacklist'];
			$this->Q[$qc]['autogen']=$this->DB->Record['autogen'];
			$this->Q[$qc]['proof']=$this->DB->Record['proof'];
			$this->Q[$qc]['touch']=$this->DB->Record['touch'];
			$this->Q[$qc]['use_inline_images']=$this->DB->Record['use_inline_images'];
			$this->Q[$qc]['sent']=$this->DB->Record['sent'];
			$this->Q[$qc]['save_imap']=$this->DB->Record['save_imap'];
			$this->Q[$qc]['host_id_imap']=$this->DB->Record['host_id_imap'];
			$this->Q[$qc]['nl_id']=$this->DB->Record['nl_id'];
			$this->Q[$qc]['grp_id']=$this->DB->Record['grp_id'];
			$this->Q[$qc]['host_id']=$this->DB->Record['host_id'];
			$this->Q[$qc]['siteid']=$this->DB->Record['siteid'];
			$qc++;
		}
		return $this->Q;
	}//getQtoSend

	function getQID($nl_id=0, $grp_id=0, $status=0) {
		$this->Q=Array();
		$Query ="SELECT ".TM_TABLE_NL_Q.".id
						FROM ".TM_TABLE_NL_Q;
		$Query .=" WHERE ".TM_TABLE_NL_Q.".siteid='".TM_SITEID."'";
		if (check_dbid($nl_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".nl_id=".checkset_int($nl_id)." ";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND ".TM_TABLE_NL_Q.".grp_id=".checkset_int($grp_id)." ";
		}
		if ($status>0) {
			$Query .=" AND ".TM_TABLE_NL_Q.".status=".checkset_int($status)." ";
		}

		$Query .=" ORDER BY ".TM_TABLE_NL_Q.".created desc ";

		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->Q[$ac]=$this->DB->Record['id'];
			$ac++;
		}
		return $this->Q;
	}//getQID

	function getH($id=0,$offset=0,$limit=0,$q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0) {
		$this->H=Array();
		$Query ="SELECT ".TM_TABLE_NL_H.".id, "
											.TM_TABLE_NL_H.".q_id, "
											.TM_TABLE_NL_H.".nl_id, "
											.TM_TABLE_NL_H.".grp_id, "
											.TM_TABLE_NL_H.".adr_id, "
											.TM_TABLE_NL_H.".host_id, "
											.TM_TABLE_NL_H.".created, "
											.TM_TABLE_NL_H.".status, "
											.TM_TABLE_NL_H.".sent, "
											.TM_TABLE_NL_H.".ip, "
											.TM_TABLE_NL_H.".siteid
						FROM ".TM_TABLE_NL_H."	";
		$Query .=" WHERE ".TM_TABLE_NL_H.".siteid='".TM_SITEID."'";
		if (check_dbid($q_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".q_id=".checkset_int($q_id)." ";
		}
		if (check_dbid($nl_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".nl_id=".checkset_int($nl_id)." ";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".grp_id=".checkset_int($grp_id)." ";
		}
		if (check_dbid($adr_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".adr_id=".checkset_int($adr_id)." ";
		}
		if ($status>0) {
			$Query .=" AND ".TM_TABLE_NL_H.".status=".checkset_int($status)." ";
		}
		if (check_dbid($id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".id=".checkset_int($id)." ";
		}
		if ($limit >0 and $offset>=0) {
			$Query .= " LIMIT ".checkset_int($offset)." ,".checkset_int($limit);
		}
		$Query .=" ORDER BY ".TM_TABLE_NL_H.".created desc	";

		$this->DB->Query($Query);
		$hc=0;
		while ($this->DB->next_record()) {
			$this->H[$hc]['id']=$this->DB->Record['id'];
			$this->H[$hc]['created']=$this->DB->Record['created'];
			$this->H[$hc]['status']=$this->DB->Record['status'];
			$this->H[$hc]['q_id']=$this->DB->Record['q_id'];
			$this->H[$hc]['nl_id']=$this->DB->Record['nl_id'];
			$this->H[$hc]['grp_id']=$this->DB->Record['grp_id'];
			$this->H[$hc]['adr_id']=$this->DB->Record['adr_id'];
			$this->H[$hc]['host_id']=$this->DB->Record['host_id'];
			$this->H[$hc]['sent']=$this->DB->Record['sent'];
			$this->H[$hc]['ip']=$this->DB->Record['ip'];
			$this->H[$hc]['siteid']=$this->DB->Record['siteid'];
			$hc++;
		}
		return $this->H;
	}//getH



	function getHtoSend($id=0,$offset=0,$limit=0,$q_id=0,$nl_id=0,$grp_id=0,$adr_id=0) {
		//liefert nur zu sendende eintarege zurueck!!! status==1 !!!
		$this->H=Array();
		$Query ="SELECT ".TM_TABLE_NL_H.".id, "
											.TM_TABLE_NL_H.".q_id, "
											.TM_TABLE_NL_H.".nl_id, "
											.TM_TABLE_NL_H.".grp_id, "
											.TM_TABLE_NL_H.".adr_id, "
											.TM_TABLE_NL_H.".host_id, "
											.TM_TABLE_NL_H.".created, "
											.TM_TABLE_NL_H.".status, "
											.TM_TABLE_NL_H.".sent, "
											.TM_TABLE_NL_H.".siteid
						FROM ".TM_TABLE_NL_H."
					";
		$Query .=" WHERE ".TM_TABLE_NL_H.".siteid='".TM_SITEID."'";
		//!!!!
		$Query .=" AND ".TM_TABLE_NL_H.".status=1 ";
		if (check_dbid($q_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".q_id=".checkset_int($q_id)." ";
		}
		if (check_dbid($nl_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".nl_id=".checkset_int($nl_id)."";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".grp_id=".checkset_int($grp_id)." ";
		}
		if (check_dbid($adr_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".adr_id=".checkset_int($adr_id)." ";
		}
		if (check_dbid($id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".id=".checkset_int($id)." ";
		}
		$Query .=" ORDER BY ".TM_TABLE_NL_H.".created asc, status asc";
		if ($limit >0 and $offset>=0) {
			$Query .= " LIMIT ".checkset_int($offset)." ,".checkset_int($limit);
		}
		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->H[$ac]['id']=$this->DB->Record['id'];
			$this->H[$ac]['created']=$this->DB->Record['created'];
			$this->H[$ac]['status']=$this->DB->Record['status'];
			$this->H[$ac]['q_id']=$this->DB->Record['q_id'];
			$this->H[$ac]['nl_id']=$this->DB->Record['nl_id'];
			$this->H[$ac]['grp_id']=$this->DB->Record['grp_id'];
			$this->H[$ac]['adr_id']=$this->DB->Record['adr_id'];
			$this->H[$ac]['host_id']=$this->DB->Record['host_id'];
			$this->H[$ac]['sent']=$this->DB->Record['sent'];
			$this->H[$ac]['siteid']=$this->DB->Record['siteid'];
			$ac++;
		}
		return $this->H;
	}//getHtoSend

	function countH($q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0) {
		$count=0;
		$Query ="
						SELECT count(".TM_TABLE_NL_H.".id) as c
						FROM ".TM_TABLE_NL_H."
					";
		$Query .=" WHERE ".TM_TABLE_NL_H.".siteid='".TM_SITEID."'
					";
		if (check_dbid($q_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".q_id=".checkset_int($q_id)." ";
		}
		if (check_dbid($nl_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".nl_id=".checkset_int($nl_id)." ";
		}
		if (check_dbid($grp_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".grp_id=".checkset_int($grp_id)." ";
		}
		if (check_dbid($adr_id)) {
			$Query .=" AND ".TM_TABLE_NL_H.".adr_id=".checkset_int($adr_id)." ";
		}
		if ($status>0) {
			$Query .=" AND ".TM_TABLE_NL_H.".status=".checkset_int($status)." ";
		}
		$Query .=" ORDER BY ".TM_TABLE_NL_H.".created desc ";

		$this->DB2->Query($Query);
		if ($this->DB2->next_record()) {
			$count=$this->DB2->Record['c'];
		}
		return $count;
	}//getH


	function delQ($id,$delH=0) {
		$Return=false;
		if (check_dbid($id)) {
			//log before deletion
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"q","action"=>"delete","memo"=>"del H:".$delH));
			//q loeschen
			$Query ="DELETE FROM ".TM_TABLE_NL_Q
							." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			//historie loeschen?
			if ($delH==1) {
				//versandliste, history h loeschen
				$this->clearH(Array("q_id"=>$id));
			}//delH
			if ($delH!=1) {
				//wenn historie nicht geloescht wird.....:
				//markieren wir einen abbruch bei laufenden nicht abgeschlossenen eintraegen set status=6(canceled) where status=1(neu) oder 5(running)!
				$Query ="UPDATE ".TM_TABLE_NL_H."
								SET status=6
								WHERE siteid='".TM_SITEID."'
								AND q_id=".checkset_int($id)."
								AND ( status=1 or status=5 )
						";
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			}//delH
		}
		return $Return;
	}//delQ

	function addQ($q,$grp) {
		$qc=0;
		//neue Q speichern fuer jede gewaehlte Gruppe
		$gc=count($grp);
		if (check_dbid($q['nl_id'])) {
			for ($gcc=0;$gcc<$gc;$gcc++) {
				$Query ="INSERT INTO ".TM_TABLE_NL_Q." (nl_id,grp_id,host_id,status,send_at,check_blacklist,touch,proof,use_inline_images,sent,save_imap,host_id_imap,author,created,siteid)
									VALUES ("
										.checkset_int($q["nl_id"]).","
										.checkset_int($grp[$gcc]).","
										.checkset_int($q["host_id"]).","
										.checkset_int($q["status"]).","
										."'".dbesc($q["send_at"])."',"
										.checkset_int($q["check_blacklist"]).","
										.checkset_int($q["touch"]).","
										.checkset_int($q["proof"]).","
										.checkset_int($q["use_inline_images"]).","
										."'',"
										.checkset_int($q["save_imap"]).","
										.checkset_int($q["host_id_imap"]).","
										."'".dbesc($q["author"])."',"
										."'".dbesc($q["created"])."',"
										."'".TM_SITEID."'
										)";
				if ($this->DB->Query($Query)) {
					$Return[$qc]['result']=true;
					$Return[$qc]['id']=$this->DB->LastInsertID;
					//log
					$q['id']=$Return[$qc]['id'];
					if (TM_LOG) $this->LOG->log(Array("data"=>$q,"object"=>"q","action"=>"new"));					
					
					
					if (isset($q['autogen'])) {
						$this->setAutogen($Return[$qc]['id'], checkset_int($q['autogen']));
					}//if autogen
				} else {
					$Return[$qc]['result']=false;
					$Return[$qc]['id']=0;
					return $Return;
				}//if query
				$qc++;
			}//for gcc
		}//check dbid nl_id
		return $Return;
	}//addQ

	function addH($h) {
		$Return=false;
		//do not log!
		if (check_dbid($h['q_id']) && check_dbid($h['nl_id']) && check_dbid($h['grp_id']) &&	check_dbid($h['adr_id'])	) {
			//neue History, Versandliste
			$Query ="INSERT INTO ".TM_TABLE_NL_H." (q_id,nl_id,grp_id,adr_id,host_id,status,created,sent,siteid)
						VALUES ( ".checkset_int($h["q_id"]).",
									".checkset_int($h["nl_id"]).",
									".checkset_int($h["grp_id"]).",
									".checkset_int($h["adr_id"]).",
									".checkset_int($h["host_id"]).",
									".checkset_int($h["status"]).",
									'".dbesc($h["created"])."',
									'',
									'".TM_SITEID."'
										)";
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//addH

	function setHStatus($id,$status) {
	//do not log!
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_H." SET status=".checkset_int($status)."
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}

	function setHSentDate($id,$created) {
	//do not log!
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_H." SET sent='".dbesc($created)."'
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}

	function setHIP($id,$ip) {
	//do not log!
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_H." SET ip='".dbesc($ip)."'
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}

	function setStatus($id,$status) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_Q." SET status=".checkset_int($status)."
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("status"=>$status,"id"=>$id),"object"=>"q","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}

	function setSentDate($id,$date) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_Q." SET sent='".dbesc($date)."'
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("sent"=>$date,"id"=>$id),"object"=>"q","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}

	function clearH($search=Array()) {
		$Return=false;
		//versandliste, history h loeschen
		if ( 
				( isset($search['adr_id']) && check_dbid($search['adr_id']) ) ||
				( isset($search['nl_id']) && check_dbid($search['nl_id']) ) ||
				( isset($search['q_id']) && check_dbid($search['q_id']) ) 
			) {
				$Query ="DELETE FROM ".TM_TABLE_NL_H." WHERE siteid='".TM_SITEID."'";
				if (isset($search['adr_id'])) {
					$Query .=" AND adr_id=".checkset_int($search['adr_id']);
				}
				if (isset($search['nl_id'])) {
					$Query .=" AND nl_id=".checkset_int($search['nl_id']);
				}
				if (isset($search['q_id'])) {
					$Query .=" AND q_id=".checkset_int($search['q_id']);
				}
				if (isset($search['not_running']) && $search['not_running']==1) {
					$Query .=" AND status!=1 AND status!=5 ";//nicht status 1 neu und 5 versand
				}
				if ($this->DB->Query($Query)) {
					if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>0,"search"=>$search),"object"=>"q","action"=>"delete","memo"=>"clearH()"));
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
		}//isset und check_dbid
		return $Return;
	}//clearH

	function makeMap(&$img,&$gi,$q_id,$width,$height) {
		$Query ="SELECT ip FROM ".TM_TABLE_NL_H."
					WHERE siteid='".TM_SITEID."'";
		$Query .=" AND ip!='0.0.0.0' AND ip!=''";
		if (check_dbid($q_id)) {
			$Query .=" AND q_id=".checkset_int($q_id);
		}
		if ($this->DB->Query($Query)) {
			$Color1 = imagecolorallocate ($img, 255,255,0);
			$Color2 = imagecolorallocate ($img, 255,0,0);
			while ($this->DB->next_Record()) {
				$geoip = geoip_record_by_addr($gi,$this->DB->Record['ip']);
				if (isset($geoip->latitude, $geoip->longitude)) {
					$pt = getlocationcoords($geoip->latitude, $geoip->longitude, $width, $height);
					imagefilledrectangle($img,$pt["x"]-1,$pt["y"]-1,$pt["x"]+1,$pt["y"]+1,$Color1);
					imagerectangle($img,$pt["x"]-2,$pt["y"]-2,$pt["x"]+2,$pt["y"]+2,$Color2);
				}
				#imagesetpixel($img,$pt["x"],$pt["y"],$Color1);
			}//while
		}//if query
	}//makeMap

	function makeMapWeight(&$img,&$gi,$q_id,$width,$height) {
		$Query ="SELECT ip FROM ".TM_TABLE_NL_H."
					WHERE siteid='".TM_SITEID."'";
		$Query .=" AND ip!='0.0.0.0' AND ip!=''";
		if (check_dbid($q_id)) {
			$Query .=" AND q_id=".checkset_int($q_id);
		}
		#$Query .=" Limit 10000";
		if ($this->DB->Query($Query)) {
			$max=0;
			$sum=0;
			$K[0][0]=0;
			while ($this->DB->next_Record()) {
				$geoip = geoip_record_by_addr($gi,$this->DB->Record['ip']);
				if (isset($geoip->latitude, $geoip->longitude)) {
					$pt = getlocationcoords($geoip->latitude, $geoip->longitude, $width, $height);
					if (isset($K[$pt['x']][$pt['y']])) {
						$K[$pt['x']][$pt['y']]++;
						if ($K[$pt['x']][$pt['y']] > $max) $max=$K[$pt['x']][$pt['y']];
					} else {
						$K[$pt['x']][$pt['y']]=1;
					}
					$sum ++;
				}
			}//while
			$one_pc=$sum/100;
			$Color1 = imagecolorallocatealpha ($img, 255,0,0,0);
			foreach ($K as $x => $ya) {
				foreach ($ya as $y => $c) {
					$d=round($c/$one_pc);
					$r=$d*5;
					imagearc ( $img, $x, $y, $r, $r, 0, 360, $Color1 );
				}
			}
		}//if query
	}//makeMap

	function makeRandomIP($limit) {
		$DB2=new tm_DB();
		$Query ="SELECT id FROM ".TM_TABLE_NL_H."
					WHERE siteid='".TM_SITEID."'";
		$Query .=" AND ip='0.0.0.0' LIMIT ".checkset_int($limit);
		if ($this->DB->Query($Query)) {
			while ($this->DB->next_Record()) {
				srand((double)microtime()*30000);
				$newip=rand(1,254).".".rand(1,254).".".rand(1,240).".".rand(1,254);
				$Query2="UPDATE ".TM_TABLE_NL_H."  set ip='".$newip."' where id='".$this->DB->Record['id']."'";
				$DB2->Query($Query2);
			}//while
		}//if query
	}//makeRandomIP

	function addHQ($h) {
		//neue History, Versandliste
		global $C;//config! fuer $C[0]['max_mails_retry']
		if (tm_DEBUG()) global $_MAIN_MESSAGE;
		$Return=Array();
		//Return: 0=result:true|false, 1=num_rows, 2=affected_rows, or index? 'result', 'num_rows', 'affected_rows'
		if (check_dbid($h['q_id']) && check_dbid($h['nl_id']) && check_dbid($h['grp_id']) && check_dbid($h['host_id'])) {
			if (tm_DEBUG()) $_MAIN_MESSAGE.="<br>q_id: ".$h['q_id'].", nl_id: ".$h['nl_id'].", grp_id: ".$h['grp_id'].", host_id: ".$h['host_id'];
			
			//get Q
			$Q=$this->getQ($h['q_id']);			
			//add if isset Q[0]... maybe and check if valid q id.... but....
			
			//temporary tablename nl_h._.microseconds.q_id to make it unique
			$tmp_code=rand(111,999); 
			$tmp_tablename = TM_TABLE_NL_H."_tmp_".TM_SITEID."_".$h['q_id']."_".$h['nl_id']."_".$h['grp_id']."_".$tmp_code;
			$Query_TempTable ="
			#temporary tablename: $tmp_tablename
			/*
			select into new table: $tmp_tablename
			all entries from nl_h table (recipients list for selected adrgroup and newsletter) that have status=1 and 
			addresses (adr_id) found in adr and are member of the selected group
			(group in nl_h doesnt matter, only group from adr_ref table!!! this one is important to be aware of double entries)
			don't care of adr.status, aktiv etc. we want all entries with status new (nl_h.status=1)
			for current siteid!
			2009.06.09 changed from status=1 to !=4,  this finally is the list of adresses belonging to more than one group who should receive the newsletter.
			this list of emails should not get inserted in the newly created recipients list for a queue to one of such groups for the same newsletter, because they already exist there!.
			changed from nl_h.status=1 to nl_h status=1 or 2 or 3 or 5, means not status 4 and not status =6 (canceled).
			not status 4 means that failed addresses gets inserted again!!!!!! maybe make that an option, just testing now. maybe status doesnt matter? a subscriber should never get the same newsletter twice ....
			would be a problem maybe because you always have to create a new newsletter for a group. or only could insert new collected addresses to send an 'old' newsletter you have already send to that group before.
			to resend to failed addresses may be a good idea also, as it does with status !=4 becaue failed addresses gets marked failed more quickly ;) and that gives us a better quality maybe of our address pool.
			*/
			CREATE TEMPORARY TABLE
				".$tmp_tablename." 
				(
					nl_h_adr_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					/* was soll eigentlich das autoincrement und der primary key hier, das koennte mysql errors geben..... wobei die nicht auftreten durften, aber autoinc is quatsch! fixen! */
					/*for debugging*/
					adr_status INT NOT NULL,
					nl_h_status INT NOT NULL,
					nl_h_nl_id INT NOT NULL,
					tmp_siteid varchar(64),
					/* 1089, added more indexes, gives us ~+10% more speed during the join select in the next select query */	
					KEY `adr_status` (`adr_status`),
					KEY `nl_h_status` (`nl_h_status`),
					KEY `nl_h_nl_id` (`nl_h_nl_id`),
					KEY `tmp_siteid` (`tmp_siteid`)					
				)
			SELECT 
				/*select adr_id from recipients list nl_h*/
				".TM_TABLE_NL_H.".adr_id AS nl_h_adr_id,
				/* for debugging */
				".TM_TABLE_ADR.".status AS adr_status,
				".TM_TABLE_NL_H.".nl_id AS nl_h_status,
				".TM_TABLE_NL_H.".nl_id AS nl_h_nl_id,
				/*with siteid*/
				'".TM_SITEID."' as tmp_siteid
			FROM ".TM_TABLE_NL_H."
				/* join with addresstable */
				INNER JOIN ".TM_TABLE_ADR." 
					ON (
							".TM_TABLE_NL_H.".adr_id = ".TM_TABLE_ADR.".id
						)
			WHERE 
				/* with siteid */
				".TM_TABLE_ADR.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_NL_H.".siteid='".TM_SITEID."'
				/* for selected newsletter */
				AND ".TM_TABLE_NL_H.".nl_id=".checkset_int($h['nl_id'])."
				/* with status=1=new */
				/* 2DO: MAYBE MAKE THAT AN OPTION*/
				AND (
						".TM_TABLE_NL_H.".status !=4 /* was status=1 */
						/*new:*/
						AND ".TM_TABLE_NL_H.".status !=6
						)
				/* group by adr id and siteid */
				GROUP BY ".TM_TABLE_ADR.".id, ".TM_TABLE_ADR.".siteid
			";//Query_TempTable

			//hier in query touch fuer q und adresse pruefen! wenn touch, dan auch status touch zulassen ansonsten diese adressen aussparen, bzw umgekehrt, nur touch adressen waehlen, oder alle wie gehabt in <1090rc2!
			//touch ist adr.status=12!
		
			$Query_SelectInto="
			/* insert new adr_id for selected newsletter in newsletter history */
			/* adresses can refer to multiple groups adr_grp.id via adr_grp_ref.adr_id and adr_grp_ref.grp_id (inner join) */
			INSERT INTO 
				".TM_TABLE_NL_H." 
				(
					q_id, #qeue id
					#".checkset_int($h["q_id"])." AS q_id,
					host_id, #host id
					#".checkset_int($h["host_id"])." AS host_id,
					nl_id, #newsletter id
					#".checkset_int($h["nl_id"])." AS nl_id,
					grp_id, #adress group id
					adr_id, #adress id
					status, #history status (1=new)
					created, #creation date
					sent, #send date
					siteid #the siteid
				)
			SELECT 
				/* we also could use prefined values from php array h, but we use matches */
				/* q_id, host_id from q, nl_id from nl.id, adr_id from adr.id, and grp_id from grp.id */
				/* status, created is taken from array, sent is empty (not sent yet) and siteid as defined in Tellmatic */
				".TM_TABLE_NL_Q.".id AS q_id,
				".TM_TABLE_NL_Q.".host_id AS host_id,
				".TM_TABLE_NL.".id AS nl_id,
				".TM_TABLE_ADR_GRP.".id AS grp_id,
				".TM_TABLE_ADR.".id AS adr_id,
				".checkset_int($h["status"])." AS status,
				'".dbesc($h["created"])."' AS created,
				'' as sent,
				'".TM_SITEID."' as siteid
			FROM (".TM_TABLE_ADR.") 
				/* join adresstable adr.id with groups references table adr_grp_ref.adr_id */
				INNER JOIN ".TM_TABLE_ADR_GRP_REF." 
					ON (".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id) 
				/* join groupstable adr_grp.id with groups references table adr_grp_ref.grp_id  */
				INNER JOIN ".TM_TABLE_ADR_GRP." 
					ON (".TM_TABLE_ADR_GRP_REF.".grp_id = ".TM_TABLE_ADR_GRP.".id)
				/* join newsletterqueue nl_q.grp_id with groupstable adr_grp.id */
				INNER JOIN ".TM_TABLE_NL_Q." 
					ON (".TM_TABLE_ADR_GRP.".id = ".TM_TABLE_NL_Q.".grp_id)
				/* join newsletter nl.id with queuetable nl_q.nl_id */
				INNER JOIN ".TM_TABLE_NL." 
					ON (".TM_TABLE_NL_Q.".nl_id = ".TM_TABLE_NL.".id)
				/* left join now adresstable adr.id with temporary historytable/recipientstable $tmp_tablename.adr_id */
				LEFT JOIN ".$tmp_tablename." ON (".TM_TABLE_ADR.".id = ".$tmp_tablename.".nl_h_adr_id) 
			WHERE 
				/* Address is active */
				".TM_TABLE_ADR.".aktiv=1
				/* for siteid */
				AND ".TM_TABLE_ADR.".siteid='".TM_SITEID."'
				AND (
							/* less errors */
							".TM_TABLE_ADR.".errors<=".checkset_int($C[0]['max_mails_retry'])."
							/* or NULL */
							OR ".TM_TABLE_ADR.".errors IS NULL
						)
				AND (
				";
				if ($Q[0]['touch']==0) {
					$Query_SelectInto.="
							/* filter adr with status 1,2,3,4,10 (all valid, but NO touch adr (12))*/
							 ".TM_TABLE_ADR.".status=1 
							OR ".TM_TABLE_ADR.".status=2 
							OR ".TM_TABLE_ADR.".status=3 
							OR ".TM_TABLE_ADR.".status=4 
							OR ".TM_TABLE_ADR.".status=10 
							";
				}
				if ($Q[0]['touch']==1) {
					$Query_SelectInto.="
							/* filter adr with status 1,2,3,4,10 or 12 (all valid, old behaviour)*/
							 ".TM_TABLE_ADR.".status=1 
							OR ".TM_TABLE_ADR.".status=2 
							OR ".TM_TABLE_ADR.".status=3 
							OR ".TM_TABLE_ADR.".status=4 
							OR ".TM_TABLE_ADR.".status=10 
							OR ".TM_TABLE_ADR.".status=12
							";
				}
				if ($Q[0]['touch']==2) { 
					$Query_SelectInto.="
							/* filter adr with status 12 (touch only)*/
							 ".TM_TABLE_ADR.".status=12 
							"; 
 				}
				$Query_SelectInto.="
						)
				/* for selected group */
				AND ".TM_TABLE_ADR_GRP.".id=".checkset_int($h['grp_id'])."
				AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($h['grp_id'])."
				AND ".TM_TABLE_NL_Q.".grp_id=".checkset_int($h['grp_id'])."
				AND (
						/* q status must be 1 new or 2 started */
						/* or 5, stopped/paused! */
						".TM_TABLE_NL_Q.".status=1
						OR ".TM_TABLE_NL_Q.".status=2
						OR ".TM_TABLE_NL_Q.".status=5
						)
				/* group must be active */
				AND ".TM_TABLE_ADR_GRP.".aktiv=1
				/* for selected newsletter */
				AND ".TM_TABLE_NL.".id=".checkset_int($h['nl_id'])."
				AND ".TM_TABLE_NL_Q.".nl_id=".checkset_int($h['nl_id'])."
				/* for siteid */
				AND ".TM_TABLE_ADR_GRP.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_NL.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_NL_Q.".siteid='".TM_SITEID."'
				AND
				/* only addresses not included in temporary table */
				".$tmp_tablename.".nl_h_adr_id IS NULL
				";
			if (tm_DEBUG()) $_MAIN_MESSAGE.="<br><strong>Create temporary Table ".$tmp_tablename."</strong><br><pre><font size=1>".$Query_TempTable."</font></pre>";
			if ($this->DB->Query($Query_TempTable)) {
				$Return[0]=TRUE;
				$Return[1]=0;#$Return[1]=$this->DB->num_rows();
				$Return[2]=$this->DB->affected_rows();
				if (tm_DEBUG()) $_MAIN_MESSAGE.="<br><strong>Select into nl_h</strong><br><pre><font size=1>".$Query_SelectInto."</font></pre>";
				if ($this->DB->Query($Query_SelectInto)) {
					$Return[0]=TRUE;
					$Return[1]=0; #$Return[1]=$this->DB->num_rows();//fehler, maysql warning...
					$Return[2]=$this->DB->affected_rows();
				} else {
					$Return[0]=FALSE;
					return $Return;
				}
			} else {
				$Return[0]=FALSE;
				return $Return;
			}
		}
		return $Return;
	}//addHQ
	
	function restart_failed($q_id=0) {
		$Return=false;
		if (check_dbid($q_id)) {
			$Query ="UPDATE ".TM_TABLE_NL_H." ". 
						"SET status=1 ".
						"WHERE ".
						"q_id=".checkset_int($q_id)." ".
						"AND (status=4 OR status=6) ".
						"AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("restart_failed"=>1,"id"=>$q_id),"object"=>"q","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
		//anzahl affected rows etc zurueckgeben, siehe addHQ
	}//restart_failed

}  //class
?>