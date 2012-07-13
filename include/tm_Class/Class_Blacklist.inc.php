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

class tm_BLACKLIST {
	/**
	* Blacklist Object
	* @var array
	*/
	var $BLACKLIST=Array();
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
	function tm_BLACKLIST() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
	}
	/**
	* get Item
	* @param
	* @return boolean
	*/	
	function getBL($id=0,$search=Array(),$offset=0,$limit=0) {
		$this->BLACKLIST=Array();
		$Query ="SELECT ".TM_TABLE_BLACKLIST.".id, "
										.TM_TABLE_BLACKLIST.".type, "
										.TM_TABLE_BLACKLIST.".expr, "
										.TM_TABLE_BLACKLIST.".aktiv, "
										.TM_TABLE_BLACKLIST.".siteid";

		$Query .=" FROM ".TM_TABLE_BLACKLIST;
		$Query .=" WHERE ".TM_TABLE_BLACKLIST.".siteid='".TM_SITEID."'";

		if (check_dbid($id)) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".id=".checkset_int($id);
		}
		if (isset($search['type']) && !empty($search['type'])) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".type='".dbesc($search['type'])."'";
		}
		if (isset($search['expr']) && !empty($search['expr'])) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".expr='".dbesc($search['expr'])."'";
		}
		if (isset($search['aktiv'])) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".type=".checkset_int($search['aktiv']);
		}
		if ($limit >0 and $offset>=0) {
			$Query .= " LIMIT ".checkset_int($offset)." ,".checkset_int($limit);
		}

		$this->DB->Query($Query);
		$hc=0;
		while ($this->DB->next_record()) {
			$this->BLACKLIST[$hc]['id']=$this->DB->Record['id'];
			$this->BLACKLIST[$hc]['type']=$this->DB->Record['type'];
			$this->BLACKLIST[$hc]['expr']=$this->DB->Record['expr'];
			$this->BLACKLIST[$hc]['aktiv']=$this->DB->Record['aktiv'];
			$this->BLACKLIST[$hc]['siteid']=$this->DB->Record['siteid'];
			$hc++;
		}
		return $this->BLACKLIST;
	}//getBL

	function setAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_BLACKLIST." SET aktiv=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"bl","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAktiv

	function addBL($blacklist) {
		$Return=false;
		$Query ="INSERT INTO ".TM_TABLE_BLACKLIST." (
					type,expr,
					aktiv,
					siteid
					)
					VALUES (
					'".dbesc($blacklist["type"])."', '".dbesc($blacklist["expr"])."',
					".checkset_int($blacklist["aktiv"]).",
					'".TM_SITEID."')";
		if ($this->DB->Query($Query)) {
			//log
			$blacklist['id']=$this->DB->LastInsertID;
			if (TM_LOG) $this->LOG->log(Array("data"=>$blacklist,"object"=>"bl","action"=>"new"));
			$Return=true;
		}
		return $Return;
	}//addBL

	function updateBL($blacklist) {
		$Return=false;
		if (isset($blacklist['id']) && check_dbid($blacklist['id'])) {
			$Query ="UPDATE ".TM_TABLE_BLACKLIST."
					SET
					type='".dbesc($blacklist["type"])."', expr='".dbesc($blacklist["expr"])."',
					aktiv=".checkset_int($blacklist["aktiv"])."
					WHERE siteid='".TM_SITEID."' AND id=".checkset_int($blacklist["id"]);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$blacklist,"object"=>"bl","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//updateBlacklist

	function delBL($id) {
		$Return=false;
		if (check_dbid($id)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"bl","action"=>"delete"));
			$Query ="DELETE FROM ".TM_TABLE_BLACKLIST." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
		}
		return $Return;
	}//delBL
	
	function countBL($search=Array()) {
	//zbsp. fuer pager benoetigt
		$Query ="
						SELECT count(".TM_TABLE_BLACKLIST.".id) as c
						FROM ".TM_TABLE_BLACKLIST."
					";
		$Query .=" WHERE ".TM_TABLE_BLACKLIST.".siteid='".TM_SITEID."'";
		
		if (isset($search['type']) && !empty($search['type'])) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".type = '".dbesc($search['type'])."'";
		}
		if (isset($search['expr']) && !empty($search['expr'])) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".expr='".dbesc($search['expr'])."'";
		}
		if (isset($search['aktiv']) && !empty($search['aktiv'])) {
			$Query .= " AND ".TM_TABLE_BLACKLIST.".aktiv = ".checkset_int($search['aktiv']);
		}
		$this->DB2->Query($Query);
		if ($this->DB2->next_record()) {
			$count=$this->DB2->Record['c'];
		}
		return $count;
	}//countBL

	function isBlacklisted($str,$type="all",$only_active=1) {
	//ganz einfacher check, schaut nach ob irgendein eintrag matched und gibt false oder true zurueck
		if (!empty($str)) {
			$return=false;//match: true, default/dont match: false
			$Query ="SELECT 
										".TM_TABLE_BLACKLIST.".id 
										FROM ".TM_TABLE_BLACKLIST."
										WHERE ".TM_TABLE_BLACKLIST.".siteid='".TM_SITEID."'";
			if (empty($type) || $type=="all") {
				$Query.="
								AND (
								".TM_TABLE_BLACKLIST.".expr='".dbesc($str)."'
								OR ".TM_TABLE_BLACKLIST.".expr='".dbesc(getDomainFromEMail($str))."'
								OR '".dbesc($str)."' REGEXP ".TM_TABLE_BLACKLIST.".expr
								)
							";
			}
			if ($type=="email") {
				$Query.="
								AND ".TM_TABLE_BLACKLIST.".expr='".dbesc($str)."'
							";
			}//domain
			if ($type=="domain") {
				$Query.="
								AND ".TM_TABLE_BLACKLIST.".expr='".dbesc(getDomainFromEMail($str))."'
							";
			}//domain
			if ($type=="expr") {
				//we do not use php ereg/i, too much overhead, we let mysql do the stuff... lets see how it works:
				//mysql: SELECT * FROM foo WHERE bar REGEXP "baz"
				$Query.="
								AND '".dbesc($str)."' REGEXP ".TM_TABLE_BLACKLIST.".expr
							";
			}//expr
			if ($only_active==1) {
				$Query.=" AND ".TM_TABLE_BLACKLIST.".aktiv=1";
			}
			$this->DB->Query($Query);
			if ($this->DB->next_record()) {
				$return=true;	
			}
			return $return;
		}//!empty str
	}//isBlacklisted
	
	function checkBL($str,$type="all",$only_active=1) {
	//erweiterung, neue fkt: check nach typ und gibt alle matches zurueck! in einem array ....
				//while match, set return[0]=true
				//add matches and type to array:
				//$return[1][$match_c]['type']=
				//$return[1][$match_c]['expr']=
				//$match_c++;
		if (!empty($str)) {
			$match_c=0;
			$return[0]=false;//match: true, default/dont match: false
			$return[1]=Array();//match: true, default/dont match: false
			$Query ="SELECT 
										".TM_TABLE_BLACKLIST.".id ,
										".TM_TABLE_BLACKLIST.".aktiv ,
										".TM_TABLE_BLACKLIST.".expr ,
										".TM_TABLE_BLACKLIST.".type 
										FROM ".TM_TABLE_BLACKLIST."
										WHERE ".TM_TABLE_BLACKLIST.".siteid='".TM_SITEID."'";

			if (empty($type) || $type=="all") {
				$Query.="
								AND (
								".TM_TABLE_BLACKLIST.".expr='".dbesc($str)."'
								OR ".TM_TABLE_BLACKLIST.".expr='".dbesc(getDomainFromEMail($str))."'
								OR '".dbesc($str)."' REGEXP ".TM_TABLE_BLACKLIST.".expr
								)
							";
			}
			if ($type=="email") {
				$Query.="
								AND ".TM_TABLE_BLACKLIST.".expr='".dbesc($str)."'
							";
			}//domain
			//check for domain
			if ($type=="domain") {
				$Query.="
								AND ".TM_TABLE_BLACKLIST.".expr='".dbesc(getDomainFromEMail($str))."'
							";
			}//domain
			//check for regexpression
			if ($type=="expr") {
				//we do not use php ereg/i, too much overhead, we let mysql do the stuff... lets see how it works:
				//mysql: SELECT * FROM foo WHERE bar REGEXP "baz"
				$Query.="
								AND '".dbesc($str)."' REGEXP ".TM_TABLE_BLACKLIST.".expr
							";
			}//expr
			if ($only_active==1) {
				$Query.=" AND ".TM_TABLE_BLACKLIST.".aktiv=1";
			}
			$this->DB->Query($Query);
			while ($this->DB->next_record()) {
				$return[0]=true;
				$return[1][$match_c]['expr']=$this->DB->Record['expr'];
				$return[1][$match_c]['aktiv']=$this->DB->Record['aktiv'];
				$return[1][$match_c]['type']=$this->DB->Record['type'];
				$match_c++;
			}
			return $return;
		}//!empty str
	}//checkBL


}  //class
?>