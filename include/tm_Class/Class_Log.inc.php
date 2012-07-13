<?php
/**
* Log
* @author      Volker Augustin
*/
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

/*

sql:
	
CREATE TABLE IF NOT EXISTS `changes_history` (
  `id` int(11) NOT NULL auto_increment,
  `siteid` varchar(255) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `author_id` int(11) NOT NULL default '0',
  `action` enum('new','edit','delete') NOT NULL default 'new' COMMENT 'ausgefuehrte aktion: new, edit, delete',
  `object` varchar(64) NOT NULL default '' COMMENT 'wo wurde geaendert',
  `property` varchar(64) NOT NULL default '' COMMENT 'was wurde geaendert, feldname',
  `x_value` longtext NOT NULL COMMENT 'alter wert',
  `edit_id` int(11) NOT NULL default '0' COMMENT 'id des geaenderten eintrags, bzw id des neuen eintrags oder geloeschte',
  `data` longtext,
  `memo` varchar(255) NOT NULL default '' COMMENT 'wenn loeschung, enthaelt dieses feld einen teil de alten daten!',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1600 ;

usage:

	
	in Class:
	var $LOG;
	
	in constructor:	
	$this->LOG=new tm_LOG();
	
	on add/create:
		after creation, at end of method!
		$item['id']=$this->DB->LastInsertID;
		$this->LOG->log(Array("data"=>$item_array,"object"=>"[identifier]","action"=>"new"));
				
	
	on update/edit:
		before edit, at beginning of method!
		$this->LOG->log(Array("data"=>$item_array,"object"=>"[identifier]","action"=>"edit"));
			
	on delete:
		$this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"[identifier]","action"=>"delete"));					

	div:
			$this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"object","action"=>"edit"));		
			$this->LOG->log(Array("data"=>Array("parent"=>$parent,"id"=>$child_id),"object"=>"object_parent_ref","action"=>"edit"));		
		
*/

/**
* Logging Class
* <code>
* </code>
* @author      Volker Augustin
*/

class tm_LOG {

	/**
	* LOG Object
	* @var object
	*/
	var $LOG=Array();
	/**
	* DB Object
	* @var object
	*/
	var $DB;
	/**
	* Helper DB Object
	* @var object
	*/	
	var $DB2;//2nd connection, e.g. needed to count adr from within getgroup()!
	/**
	* Constructor, creates new Instances for DB and LOG Objects 
	* @param
	*/	
 	function tm_LOG() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
 	}


	/**
	* save log
	* @param
	* @return boolean
	*/	
	function log($arr) {
		//indexes:
		//author_id = given author id, if not given, check data array for author id, if not given, check login, if not given, set to 0=system
		//action = text = new/edit/delete
		//object = contact/ticket/contact_group/object/contact_type etc
		//data = array with data, e.g. from $contact array addContact Method etc.
		$Return=false;
		//check values
		//set log date
		$this->LOG['date']=date("Y-m-d H:i:s");
		//chekc for author id
		$this->LOG['author_id']=0;//default is 0=system
		if ( isset($arr['author_id']) && check_dbid($arr['author_id']) ) {//if valid author_id in arr
			$this->LOG['author_id']=$arr['author_id'];
		} else { //else check for author_id in data array
			if ( isset($arr['data']['author_id']) && check_dbid($arr['data']['author_id']) ) {
				$this->LOG['author_id']=$arr['data']['author_id'];
			} else { // else, if not set at all get author id from logged in user
				global $LOGIN;
				if ( isset($LOGIN->USER['id'])  && check_dbid($LOGIN->USER['id']) ) {
					$this->LOG['author_id']=$LOGIN->USER['id'];
				}
			}		
		}
		//action
		//action should always be set, default is --
		$this->LOG['action']="--";		
		if ( isset($arr['action']) && !empty($arr['action']) ) {//wenn aktion definiert
			$this->LOG['action']=$arr['action'];
		}
		//object
		//object should always be set, default is --
		$this->LOG['object']="--";		
		if ( isset($arr['object']) && !empty($arr['object']) ) {//wenn aktion definiert
			$this->LOG['object']=$arr['object'];
		}
		//edit_id, die id des geaenderten datensatzes! oder neuen datensatzes, defakto muss log() erst am ende einer add methode aufgerufen werden wenn die id bekannt ist! 
		//edit_id should always be set, default is 0
		$this->LOG['edit_id']=0;		
		if ( isset($arr['data']['id']) && !empty($arr['data']['id']) ) {//wenn id
			$this->LOG['edit_id']=$arr['data']['id'];
		}
		
		$this->LOG['memo']="";
		if (isset($arr['memo'])) { 
			$this->LOG['memo']=$arr['memo'];
		}
		
		$this->LOG['s_data']=serialize($arr['data']);	

		//hmmm, falls loeschung, daten aus altem datensatz anhand id ermitteln.... hmmmm
		if ($this->LOG['action']=="delete" && check_dbid($this->LOG['edit_id'])) {
			switch ($this->LOG['object']) {
				//default:
				#no default
				case 'usr' :
							$LINK=new tm_CFG();
							$DATA=$LINK->getUser("",$this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA);
						break;
				case 'adr' :
							$LINK=new tm_ADR();
							$DATA=$LINK->getAdr($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'adr_grp' :
							$LINK=new tm_ADR();
							$DATA=$LINK->getGroup($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'nl' :
							$LINK=new tm_NL();
							$DATA=$LINK->getNL($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'nl_grp' :
							$LINK=new tm_NL();
							$DATA=$LINK->getGroup($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;

				case 'bl' :
							$LINK=new tm_BLACKLIST();
							$DATA=$LINK->getBL($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'frm' :
							$LINK=new tm_FRM();
							$DATA=$LINK->getForm($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'host' :
							$LINK=new tm_HOST();
							$DATA=$LINK->getHost($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'q' :
							$LINK=new tm_Q();
							$DATA=$LINK->getQ($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'lnk' :
							$LINK=new tm_LNK();
							$DATA=$LINK->get($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;
				case 'lnk_grp' :
							$LINK=new tm_LNK();
							$DATA=$LINK->getGroup($this->LOG['edit_id']);
							$this->LOG['s_data']=serialize($DATA[0]);
						break;


			}//switch
		}//if action=delete

		//serialisierte werte speichern, ein eintrag in die db pro aktion!
		$Query ="INSERT INTO ".TM_TABLE_LOG." (
						date,
						author_id,
						action,
						object,
						property,
						x_value,
						edit_id,
						data,
						memo,
						siteid
						)
						VALUES (
						'".dbesc($this->LOG["date"])."',
						".checkset_int($this->LOG["author_id"]).",
						'".dbesc($this->LOG["action"])."',
						'".dbesc($this->LOG["object"])."',
						'',
						'',
						".checkset_int($this->LOG["edit_id"]).",
						'".dbesc($this->LOG['s_data'])."',
						'".dbesc($this->LOG['memo'])."',
						'".TM_SITEID."')";
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=FALSE;
				return $Return;			
			}
		/*		
		//jeden wert einzeln speichern, ist aber unsinnn!!!!
		//iterate data array, fetch all indexes and values and save...... yes, it becomes a very very big table!!!!! anders gehts halt nicht!
		foreach ($arr['data'] as $data_key => $data_val) {
	    	if (tm_DEBUG()) $_MAIN_MESSAGE.= "$data_key => $data_val\n, ";
			$Query ="INSERT INTO ".TM_TABLE_LOG." (
						date,
						author_id,
						action,
						object,
						property,
						x_value,
						edit_id,
						siteid
						)
						VALUES (
						'".dbesc($this->LOG["date"])."',
						'".checkset_int($this->LOG["author_id"])."',
						'".dbesc($this->LOG["action"])."',
						'".dbesc($this->LOG["object"])."',
						'".dbesc($data_key)."',
						'".dbesc($data_val)."',
						'".checkset_int($this->LOG["edit_id"])."',
						'".TM_SITEID."')";
			if (TM_DEBUG_SQL) $_MAIN_MESSAGE.="\n".$Query."<br>";
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=FALSE;
				return $Return;			
			}
		}//foreach
		*/
		return $Return;
	}//log

	function createSearchQuery($search) {
		$Query=" 
				";
		//site_id ist obligatorisch
		$Query.=" WHERE ".TM_TABLE_LOG.".siteid='".TM_SITEID."'";
		if (isset($search['object']) && !empty($search['object'])) {
			$Query .= " AND (
						".TM_TABLE_LOG.".object LIKE '".str_replace("*","%",dbesc($search['object']))."'
					)";
		}
		if (isset($search['action']) && !empty($search['action'])) {
			$Query .= " AND (
						".TM_TABLE_LOG.".action='".dbesc($search['action'])."'
					)";
		}
		if (isset($search['author_id']) && check_dbid($search['author_id'])) {
			$Query .= " AND (
						".TM_TABLE_LOG.".author_id='".checkset_int($search['author_id'])."'
					)";
		}
		if (isset($search['edit_id']) && check_dbid($search['edit_id'])) {
			$Query .= " AND ".TM_TABLE_LOG.".edit_id=".checkset_int($search['edit_id']);
		}
		if (isset($search['id']) && check_dbid($search['id'])) {
			$Query .= " AND ".TM_TABLE_LOG.".id=".checkset_int($search['id']);
		}

		Return $Query;
	}

	function get($id=0,$search=Array()) {
		$this->LOG=Array();
		$search['id']=$id;
		if (!isset($search['offset'])) $search['offset']=0;
		if (!isset($search['limit'])) $search['limit']=1;
		if (!isset($search['sort_type'])) $search['sort_type']=0;
		if (!isset($search['sort_index'])) $search['sort_index']="id";
		$Query ="
			SELECT ".TM_TABLE_LOG.".id, "
							.TM_TABLE_LOG.".date, "
							.TM_TABLE_LOG.".author_id, "
							.TM_TABLE_LOG.".action, "
							.TM_TABLE_LOG.".object, "
							.TM_TABLE_LOG.".property, "
							.TM_TABLE_LOG.".x_value,"
							.TM_TABLE_LOG.".edit_id, "
							.TM_TABLE_LOG.".data, "
							.TM_TABLE_LOG.".memo, "
							.TM_TABLE_LOG.".siteid "
			."FROM ".TM_TABLE_LOG."
			";		
		$Query.=$this->createSearchQuery($search);		
		if (!empty($search['sort_index'])) {
			$Query .= " ORDER BY ".dbesc($search['sort_index']);
			if ($search['sort_type']==0) {
				$Query .= " ASC
				";
			}
			if ($search['sort_type']==1) {
				$Query .= " DESC
				";
			}
		}
		if ($search['limit']>0 and $search['offset']>=0) {
			$Query .= " LIMIT ".checkset_int($search['offset'])." ,".checkset_int($search['limit'])."
							";
		}
		
		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->LOG[$ac]['id']=$this->DB->Record['id'];
			$this->LOG[$ac]['date']=$this->DB->Record['date'];
			$this->LOG[$ac]['author_id']=$this->DB->Record['author_id'];
			$this->LOG[$ac]['edit_id']=$this->DB->Record['edit_id'];
			$this->LOG[$ac]['action']=$this->DB->Record['action'];
			$this->LOG[$ac]['object']=$this->DB->Record['object'];
			$this->LOG[$ac]['property']=$this->DB->Record['property'];
			$this->LOG[$ac]['x_value']=$this->DB->Record['x_value'];
			$this->LOG[$ac]['data']=$this->DB->Record['data'];
			$this->LOG[$ac]['memo']=$this->DB->Record['memo'];
			$this->LOG[$ac]['siteid']=$this->DB->Record['siteid'];
			$ac++;
		}
		if ($ac > 0) {		
			$this->LOG[0]['count']=$ac;
		}
		return $this->LOG;
	}

	//zaehlen mit filter
	function count($search=Array()) {
		$Return=0;
		$Query ="SELECT count(".TM_TABLE_LOG.".id) as c FROM ".TM_TABLE_LOG;		
		$Query.=$this->createSearchQuery($search);
		if ($this->DB2->Query($Query)) {
			if ($this->DB2->next_record()) {
				$Return=$this->DB2->Record['c'];
			}
		}
		return $Return;
	}

	//loeschen mit filter
	function del($search=Array()) {
		$Return=false;
		$Query ="DELETE FROM ".TM_TABLE_LOG;		
		$Query.=$this->createSearchQuery($search);
		if ($this->DB->Query($Query)) {
			$logmemo="";			
			//log mit filter
			if (count($search)==0) $logmemo="flush()";			
			//if (TM_LOG) 
			//always log log entry deletions if enabled or not!!!
			$this->log(Array("data"=>Array("search"=>$search),"object"=>"log","action"=>"delete","memo"=>$logmemo));
			$Return=true;
		}
		return $Return;
	}


	//alles loeschen
	function flush() {
		return $this->del();
	}

}//class
?>