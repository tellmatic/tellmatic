<?php
/**
* Links
* @author      Volker Augustin
*/
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006-10 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

/**
* Link Class
* <code>
* </code>
* @author      Volker Augustin
*/
class tm_LNK {
	/**
	* Items Object
	* @var object
	*/
	var $ITEM=Array();
	/**
	* Item Group Object
	* @var object
	*/
	var $GRP=Array();
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
	* LOG Object
	* @var object
	*/	
	var $LOG;
  
  	#var $x_default=Array();
  	
	/**
	* parseLog Array
	* @var array
	*/	
	var $parseLog=Array();
  
  	
	/**
	*@var
	*/
	var $TM_TABLE_ITEM;  	
	/**
	*@var
	*/
	var $TM_TABLE_ITEM_GRP;  	
	/**
	*@var
	*/
	var $TM_TABLE_ITEM_GRP_REF;  	
	/**
	*@var
	*/
	var $TM_TABLE_ITEM_CLICK;  	
  	
	/**
	* Constructor, creates new Instances for DB and LOG Objects 
	* @param
	*/	
	function tm_LNK() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
		
		$this->TM_TABLE_ITEM=TM_TABLE_LNK;
		$this->TM_TABLE_ITEM_GRP=TM_TABLE_LNK_GRP;
		$this->TM_TABLE_ITEM_GRP_REF=TM_TABLE_LNK_GRP_REF;
		$this->TM_TABLE_ITEM_CLICK=TM_TABLE_LNK_CLICK;
		#$this->search_default=Array('a'=>1,'b'=>2);
	}
	
	/*
	function doit() {
		$this->testme();
		$y['c']=3;
		$this->testme($y);
		#exit;	
	}
	*/
	/*
	function reviewSearchArray($x) {
		//schau nach was in $x nicht gesetzt ist und nimm die keys und werte aus $x_default
		foreach ($this->x_default as $prop => $val) {
			if (!isset($x[$prop])) $x[$prop]=$this->x_default[$prop];
		}
		return $x;
	}
	*/
	/*
	function testme($x=array()) {
		$x=$this->reviewSearchArray($x);
		print_r($x);
	}
	*/
	
	/**
	* get Item
	* @param
	* @return boolean
	*/	
	function get($id=0,$search=Array()) {
		$this->ITEM=Array();
		$Query ="SELECT ".$this->TM_TABLE_ITEM.".id, "
										.$this->TM_TABLE_ITEM.".aktiv, "
										.$this->TM_TABLE_ITEM.".created, "
										.$this->TM_TABLE_ITEM.".updated, "
										.$this->TM_TABLE_ITEM.".author, "
										.$this->TM_TABLE_ITEM.".editor, "
										.$this->TM_TABLE_ITEM.".short, "
										.$this->TM_TABLE_ITEM.".name, "
										.$this->TM_TABLE_ITEM.".url, "
										.$this->TM_TABLE_ITEM.".descr, "
										.$this->TM_TABLE_ITEM.".clicks, "
										.$this->TM_TABLE_ITEM.".siteid "
										."";

		$Query .=" FROM ".$this->TM_TABLE_ITEM;
		$group_id=0;
		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		}
		if (check_dbid($group_id)) {
			$Query .=" LEFT JOIN ".$this->TM_TABLE_ITEM_GRP_REF." ON ".$this->TM_TABLE_ITEM.".id = ".$this->TM_TABLE_ITEM_GRP_REF.".item_id";
		}

		$Query .=" WHERE ".$this->TM_TABLE_ITEM.".siteid='".TM_SITEID."'";

		if (check_dbid($group_id)) {
			$Query .=" AND ".$this->TM_TABLE_ITEM_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".$this->TM_TABLE_ITEM_GRP_REF.".grp_id=".checkset_int($group_id);
		}
		if (isset($search['url']) && !empty($search['url'])) {
				$Query .= " AND lcase(".$this->TM_TABLE_ITEM.".url) like lcase('".dbesc($search['url'])."')";
		}
		if (isset($search['name']) && !empty($search['name'])) {
				$Query .= " AND lcase(".$this->TM_TABLE_ITEM.".name) like lcase('".dbesc($search['name'])."')";
		}
		if (isset($search['short']) && !empty($search['short'])) {
				$Query .= " AND ".$this->TM_TABLE_ITEM.".short like '".dbesc($search['short'])."'";
		}
		if (isset($search['author']) && !empty($search['author'])) {
			$Query .= " AND ".$this->TM_TABLE_ITEM.".author like '".dbesc($search['author'])."'";
		}
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".$this->TM_TABLE_ITEM.".aktiv = ".checkset_int($search['aktiv'])."";
		}
		if (check_dbid($id)) {
			$Query .= " AND ".$this->TM_TABLE_ITEM.".id=".checkset_int($id);
		}
		if (isset($search['sortIndex']) && !empty($search['sortIndex'])) {
			$Query .= " ORDER BY ".dbesc($search['sortIndex']);
			if ($search['sortType']==0) {
				$Query .= " ASC";
			}
			if ($search['sortType']==1) {
				$Query .= " DESC";
			}
		}

		if (isset($search['limit']) && $search['limit'] >0 && isset($search['offset']) && $search['offset']>=0) {
			$Query .= " LIMIT ".checkset_int($search['offset'])." ,".checkset_int($search['limit']);
		}
		$this->DB->Query($Query);
		$c=0;
		while ($this->DB->next_record()) {
			$this->ITEM[$c]['id']=$this->DB->Record['id'];
			$this->ITEM[$c]['aktiv']=$this->DB->Record['aktiv'];
			$this->ITEM[$c]['created']=$this->DB->Record['created'];
			$this->ITEM[$c]['updated']=$this->DB->Record['updated'];
			$this->ITEM[$c]['author']=$this->DB->Record['author'];
			$this->ITEM[$c]['editor']=$this->DB->Record['editor'];
			$this->ITEM[$c]['short']=$this->DB->Record['short'];
			$this->ITEM[$c]['name']=$this->DB->Record['name'];
			$this->ITEM[$c]['url']=$this->DB->Record['url'];
			$this->ITEM[$c]['descr']=$this->DB->Record['descr'];
			$this->ITEM[$c]['clicks']=$this->DB->Record['clicks'];
			$this->ITEM[$c]['siteid']=$this->DB->Record['siteid'];
			$c++;
		}
		$this->DB->free();
		return $this->ITEM;
	}//get

	function getID($group_id=0,$search=Array()) {
	//liefert NUR die IDs in einer Gruppe zurueck als Array!!! Beoetigt fuer die Formulare
		$ID=Array();
		$DB=new tm_DB();
		$Query ="
						SELECT ".$this->TM_TABLE_ITEM.".id
						FROM ".$this->TM_TABLE_ITEM."
					";
		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		}
		if (check_dbid($group_id)) {
			$Query .="LEFT JOIN ".$this->TM_TABLE_ITEM_GRP_REF." ON ".$this->TM_TABLE_ITEM.".id = ".$this->TM_TABLE_ITEM_GRP_REF.".item_id";
		}
		$Query .=" WHERE ".$this->TM_TABLE_ITEM.".siteid='".TM_SITEID."'";
		if (check_dbid($group_id)) {
			$Query .=" AND ".$this->TM_TABLE_ITEM_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".$this->TM_TABLE_ITEM_GRP_REF.".grp_id=".checkset_int($group_id);
		}
		if (isset($search['url']) && !empty($search['url'])) {
			$Query .= " AND lcase(".$this->TM_TABLE_ITEM.".url) like lcase('".dbesc($search['url'])."')";
		}
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".$this->TM_TABLE_ITEM.".aktiv = ".checkset_int($search['aktiv'])."";
		}
		$DB->Query($Query);
		$c=0;
		while ($DB->next_record()) {
			$ITEMID[$c]=$DB->Record['id'];
			$c++;
		}
		$this->DB->free();
		return $ITEMID;
	}//getID

	function count($group_id=0,$search=Array()) {
		$Query ="
						SELECT count(".$this->TM_TABLE_ITEM.".id) as c
						FROM ".$this->TM_TABLE_ITEM."
					";
		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		}
		if (check_dbid($group_id)) {
			$Query .="LEFT JOIN ".$this->TM_TABLE_ITEM_GRP_REF." ON ".$this->TM_TABLE_ITEM.".id = ".$this->TM_TABLE_ITEM_GRP_REF.".item_id";
		}
		$Query .=" WHERE ".$this->TM_TABLE_ITEM.".siteid='".TM_SITEID."'
					";
		if (check_dbid($group_id)) {
			$Query .=" AND ".$this->TM_TABLE_ITEM_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".$this->TM_TABLE_ITEM_GRP_REF.".grp_id = ".checkset_int($group_id);
		}
		if (isset($search['url']) && !empty($search['url'])) {
			$Query .= " AND lcase(".$this->TM_TABLE_ITEM.".url) like lcase('".dbesc($search['url'])."')";
		}
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".$this->TM_TABLE_ITEM.".aktiv = ".checkset_int($search['aktiv'])."";
		}
		$this->DB2->Query($Query);
		if ($this->DB2->next_record()) {
			$count=$this->DB2->Record['c'];
		}
		return $count;
	}//count

	function getGroup($id=0,$search=Array()) {
		$this->GRP=Array();
		$Query ="
			SELECT ".$this->TM_TABLE_ITEM_GRP.".id, "
							.$this->TM_TABLE_ITEM_GRP.".short, "
							.$this->TM_TABLE_ITEM_GRP.".name, "
							.$this->TM_TABLE_ITEM_GRP.".descr, "
							.$this->TM_TABLE_ITEM_GRP.".aktiv, "
							.$this->TM_TABLE_ITEM_GRP.".standard,
							".$this->TM_TABLE_ITEM_GRP.".author,
							".$this->TM_TABLE_ITEM_GRP.".editor,
							".$this->TM_TABLE_ITEM_GRP.".created,
							".$this->TM_TABLE_ITEM_GRP.".updated,
							".$this->TM_TABLE_ITEM_GRP.".siteid
			FROM ".$this->TM_TABLE_ITEM_GRP."
			WHERE ".$this->TM_TABLE_ITEM_GRP.".siteid='".TM_SITEID."'
			";
		if (isset($search['id']) && !empty($search['id'])) {
			$id=checkset_int($search['id']);
		}
		if (check_dbid($id)) {
			$Query .= " AND ".$this->TM_TABLE_ITEM_GRP.".id=".checkset_int($id);
		}

		if (isset($search['item_id'])  && check_dbid($search['item_id'])) {
			$Query ="";
			$Query .= "
				SELECT DISTINCT ".$this->TM_TABLE_ITEM_GRP.".id, "
												.$this->TM_TABLE_ITEM_GRP.".short, "
												.$this->TM_TABLE_ITEM_GRP.".name, "
												.$this->TM_TABLE_ITEM_GRP.".descr, "
												.$this->TM_TABLE_ITEM_GRP.".aktiv, "
												.$this->TM_TABLE_ITEM_GRP.".standard,
												".$this->TM_TABLE_ITEM_GRP.".author,
												".$this->TM_TABLE_ITEM_GRP.".editor,
												".$this->TM_TABLE_ITEM_GRP.".created,
												".$this->TM_TABLE_ITEM_GRP.".updated,
												".$this->TM_TABLE_ITEM_GRP.".siteid
				FROM ".$this->TM_TABLE_ITEM_GRP.", ".$this->TM_TABLE_ITEM_GRP_REF."
				WHERE ".$this->TM_TABLE_ITEM_GRP.".id=".$this->TM_TABLE_ITEM_GRP_REF.".grp_id
				AND ".$this->TM_TABLE_ITEM_GRP.".siteid='".TM_SITEID."'
				AND ".$this->TM_TABLE_ITEM_GRP_REF.".siteid='".TM_SITEID."'
				AND ".$this->TM_TABLE_ITEM_GRP_REF.".item_id=".checkset_int($search['item_id']);
		}
		
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".$this->TM_TABLE_ITEM_GRP.".aktiv=".checkset_int($search['aktiv']);
		}
		if (isset($search['short']) && !empty($search['short'])) {
				$Query .= " AND ".$this->TM_TABLE_ITEM_GRP.".short like '".dbesc($search['short'])."'";
		}

		$Query .= "	ORDER BY ".$this->TM_TABLE_ITEM_GRP.".name";
		$this->DB->Query($Query);
		$c=0;
		while ($this->DB->next_record()) {
			$this->GRP[$c]['id']=$this->DB->Record['id'];
			$this->GRP[$c]['siteid']=$this->DB->Record['siteid'];
			$this->GRP[$c]['short']=$this->DB->Record['short'];
			$this->GRP[$c]['name']=$this->DB->Record['name'];
			$this->GRP[$c]['descr']=$this->DB->Record['descr'];
			$this->GRP[$c]['aktiv']=$this->DB->Record['aktiv'];
			$this->GRP[$c]['standard']=$this->DB->Record['standard'];
			$this->GRP[$c]['author']=$this->DB->Record['author'];
			$this->GRP[$c]['editor']=$this->DB->Record['editor'];
			$this->GRP[$c]['created']=$this->DB->Record['created'];
			$this->GRP[$c]['updated']=$this->DB->Record['updated'];
			if (isset($search['count']) && $search['count']==1) {
				$this->GRP[$c]['item_count']=$this->count(checkset_int($this->GRP[$c]['id']));
			}
			$c++;
		}
		$this->DB->free();
		return $this->GRP;
	}//getGroup

	function getGroupID($id=0,$search=Array()) {
	//quick hack, should use create query method 
	//liefert NUR die IDs zurueck als Array!!! Beoetigt fuer die Formulare
		$GRPID=Array();
		$GRP=$this->getGroup($id,$search);
		$acg=count($GRP);
		for ($accg=0;$accg<$acg;$accg++) {
			$GRPID[$accg]=$GRP[$accg]['id'];
		}
		return $GRPID;
	}//getGroupID

	function getGroupStd() {
	//quick hack, should use create query method
		$GRPSTD=Array();
		$GRP=$this->getGroup();
		//$this->getGroup(0,0,0,0,Array());
		foreach ($GRP as $GROUP) {
			if ($GROUP['standard']==1) {
				$GRPSTD=$GROUP;
			}
		}
		unset($GRP);
		return $GRPSTD;
	}//getGroupStd


	function setAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".$this->TM_TABLE_ITEM." SET aktiv=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"lnk","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAktiv

	function setGrpAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".$this->TM_TABLE_ITEM_GRP." SET aktiv=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"lnk_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setGrpAktiv

	function setGrpStd($id=0) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".$this->TM_TABLE_ITEM_GRP." SET standard=0 WHERE standard=1 AND siteid='".TM_SITEID."'";
			//log
			$GRPSTD=$this->getGroupStd();
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>0,"id"=>$GRPSTD['id']),"object"=>"lnk_grp","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			$Query ="UPDATE ".$this->TM_TABLE_ITEM_GRP." SET standard=1 WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>1,"id"=>$id),"object"=>"lnk_grp","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//setGrpStd

	function addGrp($group) {
		$Return=false;
		$Query ="INSERT INTO ".$this->TM_TABLE_ITEM_GRP." (
					short,name,descr,aktiv,
					standard,
					created,author,
					updated,editor,
					siteid
					)
					VALUES (
					'".dbesc($group["short"])."',
					'".dbesc($group["name"])."',
					'".dbesc($group["descr"])."',
					".checkset_int($group["aktiv"]).", 0,
					'".dbesc($group["created"])."', '".dbesc($group["author"])."',
					'".dbesc($group["created"])."', '".dbesc($group["author"])."',
					'".TM_SITEID."')";
		if ($this->DB->Query($Query)) {
			//log
			$group['id']=$this->DB->LastInsertID;
			if (TM_LOG) $this->LOG->log(Array("data"=>$group,"object"=>"lnk_grp","action"=>"new"));
			$Return=$group['id'];
		}
		return $Return;
		//return ID!!!
	}//addGrp

	function updateGrp($group) {
		$Return=false;
		if (isset($group['id']) && check_dbid($group['id'])) {
			$Query ="UPDATE ".$this->TM_TABLE_ITEM_GRP."
					SET
					short='".dbesc($group["short"])."',
					name='".dbesc($group["name"])."',
					descr='".dbesc($group["descr"])."',
					aktiv=".checkset_int($group["aktiv"]).",
					updated='".dbesc($group["created"])."',
					editor='".dbesc($group["author"])."'
					WHERE siteid='".TM_SITEID."' AND id=".checkset_int($group["id"]);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$group,"object"=>"lnk_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//updateGrp


	function delGrp($id,$reorg=1,$deleteItems=0) {
		$Return=false;
		if (check_dbid($id)) {
			//OK, log here, befoe deletion, but maybe add some more logging on details, new references etc
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"lnk_grp","action"=>"delete","memo"=>"reorg:".$reorg." delitems:".$deleteItems));					
			if (TM_LOG && $deleteItems==1) $this->LOG->log(Array("data"=>Array("id"=>0),"object"=>"lnk","action"=>"delete","memo"=>"deleted group ".$id." and all entrys"));

			//wenn reorg==1, dfault, dann adressen der standardgruppe neu zuordnen
			//andernfalls, auch adressen loeschen?
			//standard gruppe suchen
			if ($reorg==1) {
				//ggf get Std Group() nutzen
				$Query ="SELECT id FROM ".$this->TM_TABLE_ITEM_GRP
								." WHERE siteid='".TM_SITEID
								."' AND standard=1";
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				//wenn standardgruppe gefunden, weitermachen, ansonsten nichts tun!!! loeschen geht nur wenn eine std gruppe definiert wurde welcher die NL aus zu loeschender Gruppe zugeordnet werden koennen
				if ($this->DB->next_record()) {
					$stdGrpID=$this->DB->Record['id'];
					//adresse der stdgruppe neu zuordnen
					//achtung! durch das normale update aller referenzen gibt es redundante eintraege!!!
					//alle adr suchen die zur loeschenden gruppe gehoeren
					//einzelne adressen in array einlesen! achtung, array wird zu gross! deshalb kommt hier ne eigene query hin!
					//bzw. mit limit und offset arbeiten und in schleife einbinden!
					//function getAdr($id=0,$offset=0,$limit=0,$group_id=0,$search=Array(),$sortIndex="",$sortType=0,$Details=1) {
					//neu:
					$total=$this->count($id);
					global $adr_row_limit;
					$limit=$adr_row_limit;
					for ($offset=0; $offset <= $total; $offset+=$limit) {
						$item=$this->get(0,Array("offset"=>$offset, "limit"=>$limit,"group"=>$id));
						//referenzen zur Standardgruppe dieser adressen suchen und aufloesen:
						$c=count($item);
						//schleife drumherum mit adr_row_limit
						for ($cc=0;$cc<$c;$cc++) {
							$Query ="SELECT ".$this->TM_TABLE_ITEM_GRP_REF.".id FROM ".$this->TM_TABLE_ITEM_GRP_REF.
											" WHERE siteid='".TM_SITEID.
											"' AND item_id=".checkset_int($item[$cc]['id']).
											" AND grp_id=".checkset_int($stdGrpID);
							if ($this->DB->Query($Query)) {
								$Return=true;
							} else {
								$Return=false;
								return $Return;
							}
							if ($this->DB->next_record()) {
								//  ist item mit standardgruppe verknuepft, dann alte referenz loeschen
								$Query ="DELETE FROM ".$this->TM_TABLE_ITEM_GRP_REF."  WHERE siteid='".TM_SITEID."' AND item_id=".checkset_int($item[$cc]['id'])." AND grp_id=".checkset_int($id);
								if ($this->DB->Query($Query)) {
									$Return=true;
								} else {
									$Return=false;
									return $Return;
								}
							} else {	//oder
								//  ist item NICHT mit standardgruppe verknuepft, dann alte referenz mit stdgruppe updaten
								//update der verknuepfung zur alten Gruppe mit der neuen Gruppe...
								$Query ="UPDATE ".$this->TM_TABLE_ITEM_GRP_REF." SET grp_id=".checkset_int($stdGrpID)." WHERE siteid='".TM_SITEID."' AND grp_id=".checkset_int($id);
								if ($this->DB->Query($Query)) {
									$Return=true;
								} else {
									$Return=false;
									return $Return;
								}
							}//if
						}//for acc
					}//offset
				}//next record
			}//reorg
			
			if ($deleteItems==1) {
				/**/
				//items loeschen
				/**/
				$Query ="DELETE ".$this->TM_TABLE_ITEM." FROM ".$this->TM_TABLE_ITEM."  ";//WHERE siteid='".TM_SITEID."' ";
				$Query .="LEFT JOIN ".$this->TM_TABLE_ITEM_GRP_REF." ON ".$this->TM_TABLE_ITEM_GRP_REF.".item_id = ".$this->TM_TABLE_ITEM.".id";
				$Query .=" WHERE ".$this->TM_TABLE_ITEM.".siteid='".TM_SITEID."'";
				$Query .=" AND ".$this->TM_TABLE_ITEM_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".$this->TM_TABLE_ITEM_GRP_REF.".grp_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				/**/
				//referenzen loeschen
				/**/
				$Query ="DELETE FROM ".$this->TM_TABLE_ITEM_GRP_REF."  WHERE siteid='".TM_SITEID."' AND grp_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				/**/
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			}
			//gruppe loeschen
			$Query ="DELETE FROM ".$this->TM_TABLE_ITEM_GRP."  WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//delGrp

	function del($id) {
		$Return=false;
		if (check_dbid($id)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"lnk","action"=>"delete"));
			//referenzen loeschen
			$Query ="DELETE FROM ".$this->TM_TABLE_ITEM_GRP_REF." WHERE siteid='".TM_SITEID."' AND item_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			//item loeschen
			$Query ="DELETE FROM ".$this->TM_TABLE_ITEM." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//del

	function add($adr,$grp) {
		$Return=false;
		//neuen Link speichern
		$Query ="INSERT INTO ".
						$this->TM_TABLE_ITEM.
						" (
							aktiv,
							author,
							created,
							editor,
							updated,
							short,
							name,
							url,
							descr,
							clicks,
							siteid
						)
					VALUES 
						(
						".checkset_int($adr["aktiv"]).",
						'".dbesc($adr["author"])."',
						'".dbesc($adr["created"])."',
						'".dbesc($adr["author"])."',
						'".dbesc($adr["created"])."',
						'".dbesc($adr["short"])."',
						'".dbesc($adr["name"])."',
						'".dbesc($adr["url"])."',
						'".dbesc($adr["descr"])."',
						0,
						'".TM_SITEID."'
						)";
		if ($this->DB->Query($Query)) {
			$Return=true;
		} else {
			$Return=false;
			return $Return;
		}
		//Abfragen! und ID suchen, die brauchen wir fuer die Verknuepfung zu den Adressgruppen
		//search for new id:
		//neu, use lastinsertid!!!:
		if ($this->DB->LastInsertID != 0) {
		//neu
			$new_id=$this->DB->LastInsertID;

			//log here
			$lnk['id']=$new_id;
			$lnk['grp']=$grp;
			if (TM_LOG) $this->LOG->log(Array("data"=>$lnk,"object"=>"lnk","action"=>"new"));
			
			//gruppen eintragen
			//use internal method addref instead:
			$this->addRef($new_id,$grp);
		}//get id, next record, bzw if $this->DB->LastInsertID!=0
		$Return=$new_id;
		return $Return;
	}//addAdr

	//addRef: Gruppenreferenzen anlegen, keine pruefung auf doppelte refs!!! nur geeignet zum neuanlegen der rerefenzen bei ersteintrag! setGroup() fuer updates etc! setGroup unterstuetzt auch merge etc.
	//wird auch von setgroup aufgerufen ! ;-)
	//adr_id= address id, grp: array with group_ids
	function addRef($id,$grp) {
		$Return=false;
		if (check_dbid($id)) {
			//gruppen eintragen
			$acg=count($grp);
			if ($acg>0) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id,"grp"=>$grp),"object"=>"lnk","action"=>"edit"));
				for ($accg=0;$accg<$acg;$accg++) {
					if (isset($grp[$accg])) {
						$Query="INSERT INTO ".$this->TM_TABLE_ITEM_GRP_REF." (item_id,grp_id,siteid) VALUES (".checkset_int($id).",".checkset_int($grp[$accg]).",'".TM_SITEID."')";
						if ($this->DB->Query($Query)) {
							$Return=true;
						} else {
							$Return=false;
							return $Return;
						}//if query
					}//if isset grp
				}//for
			}//if acg>0
		}//if check_dbid
		return $Return;
	}

	function update($item,$grp) {
		$Return=false;
		if (isset($item['id']) && check_dbid($item['id'])) {
			$adr['grp']=$grp;			
			$Query ="UPDATE ".$this->TM_TABLE_ITEM." SET
							short='".dbesc($item["short"])."',
							name='".dbesc($item["name"])."',
							url='".dbesc($item["url"])."',
							descr='".dbesc($item["descr"])."',
							aktiv=".checkset_int($item["aktiv"]).",
							editor='".dbesc($item["author"])."',
							updated='".dbesc($item["created"])."'
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($item["id"]);
			if ($this->DB->Query($Query)) {
				$item['grp']=$grp;
				if (TM_LOG) $this->LOG->log(Array("data"=>$item,"object"=>"lnk","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//update group references
			//use setGroup instead!:
			$this->setGroup($item['id'],$grp);
		}//if adr_id
		return $Return;
	}//update

//setGroup: set Item references, delete old refs, create new
//takes at least 2 params, item_id and $new_group (array with group_ids!)
//if 3. parameter  $old_grp is set and 4th param $merge is 1, then new and old groups will be merged and unified
function setGroup($item_id,$new_grp,$old_grp=Array(),$merge=0) {
		$Return=false;
		if (isset($item_id) && check_dbid($item_id)) {
			//alle refs loeschen
			$Query ="DELETE FROM ".$this->TM_TABLE_ITEM_GRP_REF." WHERE siteid='".TM_SITEID."' AND item_id=".checkset_int($item_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			if ($merge==0) {
				//set only new groups!
				//do nothing special, default
				$groups=$new_grp;
			}
			if ($merge==1) {
				//merge old and new groups!
				$groups=$this->mergeGroups($old_grp,$new_grp);
			}
			if ($merge==2) {
				//diff old and new groups! set only groups from old groups not in new groups!
				$groups=array_diff($old_grp,$new_grp);
				//re-index!!! important!
				$groups=array_values($groups);
			}
			//neue refs anlegen
			$this->addRef($item_id,$groups);
		}//if adr_id
		return $Return;
	}//setGroup
	
	//merge groups, merge 2 arrays with ids and unify
	function mergeGroups($grp1,$grp2) {
		$grp=Array();
		$grp_diff = array_diff($grp1,$grp2);//nur diff
		$grp = array_merge($grp2, $grp_diff);//alte+neue gruppen zusammenfuegen
		return $grp;
	}

//add individual click to link click table, one and only first click per ip:adr:nl:q:h
	function addClick($data) {
		$Return=false;
		$lnk_id=0;
		$nl_id=0;
		$q_id=0;
		$adr_id=0;
		$h_id=0;
		
		if (isset($data['lnk_id']) && check_dbid($data['lnk_id'])) {
			$lnk_id=$data["lnk_id"];
		}
		if (isset($data['nl_id']) && check_dbid($data['nl_id'])) {
			$nl_id=$data["nl_id"];
		}
		if (isset($data['q_id']) && check_dbid($data['q_id'])) {
			$q_id=$data["q_id"];
		}
		if (isset($data['adr_id']) && check_dbid($data['adr_id'])) {
			$adr_id=$data["adr_id"];
		}
		if (isset($data['h_id']) && check_dbid($data['h_id'])) {
			$h_id=$data["h_id"];
		}
		
		//only count one unique dataset, check if exists first!
		$Query ="INSERT INTO ".$this->TM_TABLE_ITEM_CLICK." (
						created,
						siteid,
						lnk_id,
						nl_id,
						q_id,
						adr_id,
						h_id,
						ip,
						clicks
					)
					VALUES (
					'".date("Y-m-d H:i:s")."',
					'".TM_SITEID."',
					".checkset_int($lnk_id).",
					".checkset_int($nl_id).",
					".checkset_int($q_id).",
					".checkset_int($adr_id).",
					".checkset_int($h_id).",
					'".getIP()."',
					1
					)
					ON DUPLICATE KEY UPDATE clicks=clicks+1
					";
		if ($this->DB->Query($Query)) {
			$Return=true;			
			//do not log
		}
		return $Return;
	}
//count a click and increase counter.. double clicks do not matter, all clicks counted! :P
	function countClick($id) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".$this->TM_TABLE_ITEM." SET clicks=clicks+1 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}


//reset clicks for specified link
	function resetClicks($id) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".$this->TM_TABLE_ITEM." SET clicks=0 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//resetClicks
	
//reset clicks for specified link
	function flushClicks($id) {
		$Return=false;
		if (check_dbid($id)) {
			//first reset clicks
			if ($this->resetClicks($id)) {
				//now delete entries for link in link_click table			
				$Query ="DELETE 
								FROM ".$this->TM_TABLE_ITEM_CLICK."  
								WHERE siteid='".TM_SITEID."' 
								AND lnk_id=".checkset_int($id);					 
				if ($this->DB->Query($Query)) {
					$Return=$this->DB->affected_rows();
				}
			}
		}
		return $Return;
	}//flushClicks

//parse links!

function createLink($lnk,$filter="",$data=Array()) {
		if (tm_DEBUG()) $this->parseLog[]= "createLink: start timer";
		if (tm_DEBUG())  $T=new Timer();//zeitmessung
		if (tm_DEBUG()) $this->parseLog[]= "createLink: start:".$T->MidResult()."";

	//oooops, a global!
	global $tm_URL_FE;
	//always return link by id if $lnk is numeric, otherwise, if text return by shortname
	$Return="";
	
	//now add optional parameters for html tags: separated by comma, split lnk and redefine, remove leading spaces
	//syntax is {LNK_*:[id|short][,[tagparameters]]
	//example: {LNKID_AHREF:3,style="border: 1px dashed #ff0000;" someothervalidparameterforahrefhtmltags="something"}
	//not for textlinks
	$lnk_arr= preg_split("/,/",$lnk);//preg_split("/[,]+/", $lnk);//splitWithEscape ($lnk, ',', '"');
	$lnk_id=str_replace(",","",$lnk_arr[0]);//we have to remove comma
	$lnk_params= (count($lnk_arr)>1) ? $lnk_arr[1] : "";
	
	if (!is_numeric($lnk_id)) {
		//fetch id from active link
		#$Return.="<br>fetch link by short: $lnk_id<br>";
		$LNK=$this->get(0, Array("short"=>$lnk_id) );//only active links
		if (count($LNK)>0) {
			//now call myself with id
			$Return="".$this->createLink($LNK[0]['id'].",".$lnk_params,$filter,$data);
		} else {
			return $Return;//quit here if no link found
		}//count
	}//!numeric

	if (check_dbid($lnk_id) && is_numeric($lnk_id)) {
		$LNK=$this->get($lnk_id);
		if (tm_DEBUG()) $this->parseLog[]= "createLink: get link from db:".$T->MidResult()."";
		if (isset($LNK[0]) && $LNK[0]['aktiv']==1) {
				//create tracking parametes from $data for url
				$trackingparams="?l_id=".$LNK[0]['id'];
				if (isset($data['nl_id'])) {
					$trackingparams.='&amp;nl_id='.$data['nl_id'];
				}
				if (isset($data['q_id'])) {
					$trackingparams.='&amp;q_id='.$data['q_id'];
				}
				if (isset($data['a_id'])) {
					$trackingparams.='&amp;a_id='.$data['a_id'];
				}
				if (isset($data['h_id'])) {
					$trackingparams.='&amp;h_id='.$data['h_id'];
				}
	
				//full link with name etc
				if ($filter=="") {
					if ($LNK[0]['url'][0]=="#") {
						$Return="<a href=\"".$LNK[0]['url']."\" title=\"".display($LNK[0]['descr'])."\" ".$lnk_params.">".$LNK[0]['name']."</a>";
					} else {
						$Return="<a href=\"".$tm_URL_FE."/click.php".$trackingparams."\" title=\"".display($LNK[0]['descr'])."\" target=\"_blank\" ".$lnk_params.">".$LNK[0]['name']."</a>";
					}
				}//""
				//only a href
				if ($filter=="ahref") {
					if ($LNK[0]['url'][0]=="#") {
						$Return="<a href=\"".$LNK[0]['url']." title=\"".display($LNK[0]['descr'])."\" ".$lnk_params.">";
					} else {
						$Return="<a href=\"".$tm_URL_FE."/click.php".$trackingparams."\" title=\"".display($LNK[0]['descr'])."\" target=\"_blank\" ".$lnk_params.">";
					}
				}//ahref
				//only url
				if ($filter=="url") {
					$Return=$tm_URL_FE."/click.php".$trackingparams;
					#$LNK[0]['url'];
				}//url
				//only rawurl
				if ($filter=="rawurl") {
					$Return=$LNK[0]['url'];
				}//url
				if ($filter=="text") {
					if ($LNK[0]['url'][0]=="#") {
						$Return=$LNK[0]['name'].": ".$LNK[0]['descr'];
					} else {
						$Return=$LNK[0]['name'].": ".$tm_URL_FE."/click.php".$trackingparams;					
					}
					#$LNK[0]['url'];
				}//text
		}//isset LNK && aktiv
	}//numeric
	if (tm_DEBUG()) $this->parseLog[]= "createLink: ".$T->MidResult()."";
	return $Return;	
}


	function createLinkGroup($lnkgrp,$filter="",$data=Array()) {

		if (tm_DEBUG()) 	$this->parseLog[]= "createLinkGroup: start timer";
		if (tm_DEBUG())  $T=new Timer();//zeitmessung

		$Return="";
		
		$lnkgrp_arr= preg_split("/,/",$lnkgrp);//preg_split("/[,]+/", $lnk);//splitWithEscape ($lnk, ',', '"');
		$lnkgrp_id=str_replace(",","",$lnkgrp_arr[0]);//we have to remove comma
		$lnkgrp_params= (count($lnkgrp_arr)>1) ? $lnkgrp_arr[1] : "";
	
		if (!is_numeric($lnkgrp_id)) {
			$LNKGRP=$this->getGroup(0, Array("short"=>$lnkgrp_id) );
			if (count($LNKGRP)>0) {
				$Return=$this->createLinkGroup($LNKGRP[0]['id'].",".$lnkgrp_params,$filter,$data);
			}//count
		}//!numeric
		
		if (check_dbid($lnkgrp_id) && is_numeric($lnkgrp_id)) {
			$LNKGRP=$this->getGroup($lnkgrp_id);
			if (isset($LNKGRP[0]) && $LNKGRP[0]['aktiv']==1) {
				if ($filter=="text") {				
					$Return="* ".$LNKGRP[0]['name'];
					$Return.="\n".$LNKGRP[0]['descr'];
				} else {
					$Return="<strong>".display($LNKGRP[0]['name'])."</strong>";
					$Return.="<br><font size=-1>".display($LNKGRP[0]['descr'])."</font>";
				}
				//get links by groupid
				$LNK=$this->get(0,Array("group"=>$lnkgrp_id));
				$c=count($LNK);
				if ($c>0) {
					for ($cc=0;$cc<$c;$cc++) {
						if ($filter=="text") {
							$Return.="\n -- ".$this->createLink($LNK[$cc]['id'],$filter,$data);
						} else {
							$Return.="<br>".$this->createLink($LNK[$cc]['id'].",".$lnkgrp_params,$filter,$data);						
						}
					}//for
				}//count
			}//count
		}//numeric
		if (tm_DEBUG()) $this->parseLog[]= "createLinkGroup:".$T->MidResult()."";		
		return $Return;
	}
	
	/**
	* parseLinks
	* @param text
	* @return text
	*/	
	function parseLinks($txt,$filter="",$data=array()) {

		if (tm_DEBUG()) 	$this->parseLog[]= "parseLinks: start timer";
		if (tm_DEBUG())  $T=new Timer();//zeitmessung

		//for tracking: $data=array('nl_id','q_id','a_id' etc) for stats and linktracking!		
		
		//if filter=="text" then return groups as url only
		//for links you have to use url yourselv in the textpart of nl
		//text filter only returns urls of links only for groups ;)

		//create dynamic code to pass $data to the callbackfunction		
		$dataparams='$data=Array();';
		if (isset($data['nl_id'])) {
			$dataparams.='$data["nl_id"]='.$data['nl_id'].';';
		}
		if (isset($data['a_id'])) {
			$dataparams.='$data["a_id"]='.$data['a_id'].';';
		}
		if (isset($data['q_id'])) {
			$dataparams.='$data["q_id"]='.$data['q_id'].';';
		}
		if (isset($data['h_id'])) {
			$dataparams.='$data["h_id"]='.$data['h_id'].';';
		}
		
		//parse links
		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: start:".$T->MidResult()."";
		
		//parse single link by shortname {LNK:[link_short_name]}
		$txt=preg_replace_callback("/(\{LNK:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"",$data), $arr[2]);'
			)		
			, $txt);

		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNK:".$T->MidResult()."";
			
		//parse single link by shortname and return only a href {LNK_AHREF:[link_short_name]}
		$txt=preg_replace_callback("/(\{LNK_AHREF:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"ahref",$data), $arr[2]);'
			)		
			, $txt);
		
		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNK_AHREF:".$T->MidResult()."";

		//parse single link by shortname and return only url {LNK_URL:[link_short_name]}
		$txt=preg_replace_callback("/(\{LNK_URL:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"url",$data), $arr[2]);'
			)		
			, $txt);

		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNK_URL:".$T->MidResult()."";

		//parse single link by shortname and return only raw url {LNK_URLRAW:[link_short_name]}
		$txt=preg_replace_callback("/(\{LNK_URLRAW:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"rawurl",$data), $arr[2]);'
			)		
			, $txt);

		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNK_URLRAW:".$T->MidResult()."";

		//parse link by given id {LNKID:[link_id]}
		$txt=preg_replace_callback("/(\{LNKID:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"",$data), $arr[2]);'
			)		
			, $txt);

		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNKID:".$T->MidResult()."";

		//parse link by given id and return only ahref {LNKID_AHREF:[link_id]}
		$txt=preg_replace_callback("/(\{LNKID_AHREF:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"ahref",$data), $arr[2]);'
			)		
			, $txt);
		
		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNKID_AHREF:".$T->MidResult()."";
		
		//parse link by given id and return only url {LNKID_URL:[link_id]}
		$txt=preg_replace_callback("/(\{LNKID_URL:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"url",$data), $arr[2]);'
			)		
			, $txt);

		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNKID_URL:".$T->MidResult()."";			
			
		//parse link by given id and return only raw url {LNKID_URLRAW:[link_id]}
		$txt=preg_replace_callback("/(\{LNKID_URLRAW:)(.*?)(\})/",
			create_function (
				'$arr',
				$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLink($arr[2],"rawurl",$data), $arr[2]);'
			)		
			, $txt);
		
		if (tm_DEBUG()) $this->parseLog[]= "parseLinks: LNKID_URLRAW:".$T->MidResult()."";		
		
		//parse link groups
		
		if ($filter=="") {
			//parse all links group by group shortname {LNKGRP:[link_group_short_name]}
			$txt=preg_replace_callback("/(\{LNKGRP:)(.*?)(\})/",
				create_function (
					'$arr',
					$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLinkGroup($arr[2],"",$data), $arr[2]);'
				)
				, $txt);
			//parse all links by group id {LNKGRPID:[link_group_id]}
			$txt=preg_replace_callback("/(\{LNKGRPID:)(.*?)(\})/",
				create_function (
					'$arr',
					$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLinkGroup($arr[2],"",$data), $arr[2]);'
				)
				, $txt);
		}//""

		if ($filter=="text") {
			//if text use url filter, only return urls of links
		
			//parse all links group by group shortname {LNKGRP:[link_group_short_name]}
			$txt=preg_replace_callback("/(\{LNKGRP:)(.*?)(\})/",
				create_function (
					'$arr',
					$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLinkGroup($arr[2],"text",$data), $arr[2]);'
				)
				, $txt);
			//parse all links by group id {LNKGRPID:[link_group_id]}
			$txt=preg_replace_callback("/(\{LNKGRPID:)(.*?)(\})/",
				create_function (
					'$arr',
					$dataparams.'$LNK=new tm_LNK(); return str_replace($arr[2], $LNK->createLinkGroup($arr[2],"text",$data), $arr[2]);'
				)
				, $txt);
		}//""
		
		return $txt;
	}//parseLinks

	/* not used, 
		just for completition, we use inline anonymous functions in preg_replace_callback
		i also use more general methods to fetch links...
		
	function parse_links_($txt) {
		$txt=preg_replace_callback("/(\{LNK:)(.*?)(\})/","tm_LNK::parse_link_by_name", $txt);
		$txt=preg_replace_callback("/(\{LNKID:)(.*?)(\})/","tm_LNK::parse_link_by_id", $txt);
		$txt=preg_replace_callback("/(\{LNKGRP:)(.*?)(\})/","tm_LNK::parse_link_group_by_name", $txt);
		$txt=preg_replace_callback("/(\{LNKGRPID:)(.*?)(\})/","tm_LNK::parse_link_group_by_id", $txt);
	}
	function parse_link_by_name ($arr) {
		$out=array();
		$out=str_replace($arr[2], createLink_by_name($arr[2]), $arr[2]);
		return $out;
	}
	function parse_link_by_id ($arr) {
		$out=array();
		$out=str_replace($arr[2], createLink_by_id($arr[2]), $arr[2]);
		return $out;
	}
	function parse_link_group_by_name ($arr) {
		$out=array();
		$out=str_replace($arr[2], createLink_by_groupname($arr[2]), $arr[2]);
		return $out;
	}
	function parse_link_group_by_id ($arr) {
		$out=array();
		$out=str_replace($arr[2], createLink_by_groupid($arr[2]), $arr[2]);
		return $out;
	}

	function createLink_by_name($short,$filter="") {
		$Return="";
		$LNK=$this->get(0, Array("short"=>$short) );
		if (count($LNK)>0) {
			$Return=$this->createLink_by_id($LNK[0]['id'],$filter);
		}
		return $Return;
	}
	
	function createLink_by_id($id,$filter="") {
		$Return="";
		$LNK=$this->get($id);
		if (count($LNK)>0) {
			//full link with name etc
			if ($filter=="") {
				$Return="<a href=\"".$LNK[0]['url']."\" title=\"".display($LNK[0]['descr'])."\" target=\"_blank\">".$LNK[0]['name']."</a>";
			}
			//only a href
			if ($filter=="ahref") {
				$Return="<a href=\"".$LNK[0]['url']."\" title=\"".display($LNK[0]['descr'])."\" target=\"_blank\">";
			}
			//only url
			if ($filter=="url") {
				$Return=$LNK[0]['url'];
			}
		}
		return $Return;
	}
	function createLink_by_groupname($short) {
		$Return="";
		$LNKGRP=$this->getGroup(0, Array("short"=>$short) );
		if (count($LNKGRP)>0) {
			$Return=$this->createLink_by_groupid($LNKGRP[0]['id']);
		}
		return $Return;
	}
	
	function createLink_by_groupid($id) {
		$Return="";
		$LNKGRP=$this->getGroup($id);
		if (count($LNKGRP)>0) {
			$Return="<strong>".display($LNKGRP[0]['name'])."</strong>";
			$Return.="<br><font size=-1>".display($LNKGRP[0]['descr'])."</font>";
			//get links by groupid
			$LNK=$this->get(0,Array("group"=>$id));
			$c=count($LNK);
			if ($c>0) {
				for ($cc=0;$cc<$c;$cc++) {
				//$Return.="<br>".$this->createLink_by_id($LNK[$cc]['id']);
				$Return.="<br>".$this->createLink($LNK[$cc]['id']);
				}
			}
		}
		return $Return;
	}
	*/

}//class
?>