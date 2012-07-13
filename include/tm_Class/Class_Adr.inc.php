<?php
/**
* ADR
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
* Address Class
* <code>
* </code>
* @author      Volker Augustin
*/
class tm_ADR {
	/**
	* ADR Object
	* @var object
	*/
	var $ADR=Array();
	/**
	* ADR Grp Object
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
	*/	var $LOG;
  
	/**
	* Constructor, creates new Instances for DB and LOG Objects 
	* @param
	*/	
	function tm_ADR() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
		
		#define($this->TABLE_ITEM,TM_TABLE_ADR);
	}
	/**
	* get Item
	* @param
	* @return boolean
	*/	
	function getAdr($id=0,$offset=0,$limit=0,$group_id=0,$search=Array(),$sortIndex="",$sortType=0,$Details=1) {
		$this->ADR=Array();
		#$DB=new tm_DB();
		$Query ="SELECT ".TM_TABLE_ADR.".id, "
										."lcase(".TM_TABLE_ADR.".email) as email, "
										.TM_TABLE_ADR.".aktiv, "
										.TM_TABLE_ADR.".created, "
										.TM_TABLE_ADR.".updated, "
										.TM_TABLE_ADR.".author, "
										.TM_TABLE_ADR.".editor, "
										.TM_TABLE_ADR.".status, "
										.TM_TABLE_ADR.".errors, "
										.TM_TABLE_ADR.".code, "
										.TM_TABLE_ADR.".clicks, "
										.TM_TABLE_ADR.".views, "
										.TM_TABLE_ADR.".newsletter, "
										.TM_TABLE_ADR.".recheck, "
										.TM_TABLE_ADR.".proof, "
										.TM_TABLE_ADR.".source, "
										.TM_TABLE_ADR.".source_id, "
										.TM_TABLE_ADR.".source_extern_id, "
										.TM_TABLE_ADR.".siteid ";
			$Query .=", ".TM_TABLE_ADR_DETAILS.".id as d_id,"
								.TM_TABLE_ADR_DETAILS.".memo";

		if ($Details==1) {
			$Query .=", ".TM_TABLE_ADR_DETAILS.".id as d_id,"
								.TM_TABLE_ADR_DETAILS.".memo, "
								.TM_TABLE_ADR_DETAILS.".f0, "
								.TM_TABLE_ADR_DETAILS.".f1, "
								.TM_TABLE_ADR_DETAILS.".f2, "
								.TM_TABLE_ADR_DETAILS.".f3, "
								.TM_TABLE_ADR_DETAILS.".f4, "
								.TM_TABLE_ADR_DETAILS.".f5, "
								.TM_TABLE_ADR_DETAILS.".f6, "
								.TM_TABLE_ADR_DETAILS.".f7, "
								.TM_TABLE_ADR_DETAILS.".f8, "
								.TM_TABLE_ADR_DETAILS.".f9";
		}

		$Query .=" FROM ".TM_TABLE_ADR;
			$Query .=" LEFT JOIN ".TM_TABLE_ADR_DETAILS." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_DETAILS.".adr_id";

		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		}
		if (check_dbid($group_id)) {
			$Query .=" LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id";
		}

		$Query .=" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'";

		if (check_dbid($group_id)) {
			$Query .=" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($group_id);
		}

		if (isset($search['email']) && !empty($search['email'])) {
			if (isset($search['email_exact_match']) && $search['email_exact_match']===true) {
				$Query .= " AND lcase(".TM_TABLE_ADR.".email) = lcase('".dbesc($search['email'])."')";
			} else {
				$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
			}
		}
		if (isset($search['author']) && !empty($search['author'])) {
			$Query .= " AND ".TM_TABLE_ADR.".author like '".dbesc($search['author'])."'";
		}
		if (isset($search['recheck']) && !empty($search['recheck'])) {
			$Query .= " AND ".TM_TABLE_ADR.".recheck = ".checkset_int($search['recheck'])."";
		}
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".TM_TABLE_ADR.".aktiv = ".checkset_int($search['aktiv'])."";
		}
		//check for status, OR
		if (isset($search['status']) && $search['status']>0) {
			//if is no array, let first array entry be the string, so we always have an array
			if (!is_array($search['status'])) {
				$search_status=$search['status'];				
				$search['status']=Array();
				$search['status'][0]=$search_status;
			}
			//create query
			$ssc=count($search['status']);
			if ($search['status'][0]>0) {
				$Query .= " AND (";
				for ($sscc=0;$sscc<$ssc;$sscc++) {
					$Query .= TM_TABLE_ADR.".status=".checkset_int($search['status'][$sscc]);
					if (($sscc+1)<$ssc) $Query.=" OR";
				}
				$Query .= " )";
			}
		}		

		if (isset($search['code']) && !empty($search['code'])) {
			$Query .= " AND ".TM_TABLE_ADR.".code = '".dbesc($search['code'])."'";
		}

		if ($Details==1 && isset($search['f0_9']) && !empty($search['f0_9'])) {
			$Query .= " AND (
							".TM_TABLE_ADR_DETAILS.".f0 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f1 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f2 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f3 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f4 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f5 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f6 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f7 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f8 like '".dbesc($search['f0_9'])."'
							OR ".TM_TABLE_ADR_DETAILS.".f9 like '".dbesc($search['f0_9'])."'
							)";
		}//if

		if (check_dbid($id)) {
			$Query .= " AND ".TM_TABLE_ADR.".id=".checkset_int($id);
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

		if ($limit >0 and $offset>=0) {
			$Query .= " LIMIT ".checkset_int($offset)." ,".checkset_int($limit);
		}
		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->ADR[$ac]['id']=$this->DB->Record['id'];
			$this->ADR[$ac]['email']=$this->DB->Record['email'];
			$this->ADR[$ac]['aktiv']=$this->DB->Record['aktiv'];
			$this->ADR[$ac]['created']=$this->DB->Record['created'];
			$this->ADR[$ac]['updated']=$this->DB->Record['updated'];
			$this->ADR[$ac]['author']=$this->DB->Record['author'];
			$this->ADR[$ac]['editor']=$this->DB->Record['editor'];
			$this->ADR[$ac]['status']=$this->DB->Record['status'];
			$this->ADR[$ac]['clicks']=$this->DB->Record['clicks'];
			$this->ADR[$ac]['views']=$this->DB->Record['views'];
			$this->ADR[$ac]['newsletter']=$this->DB->Record['newsletter'];
			$this->ADR[$ac]['errors']=$this->DB->Record['errors'];
			$this->ADR[$ac]['code']=$this->DB->Record['code'];
			$this->ADR[$ac]['memo']=$this->DB->Record['memo'];
			$this->ADR[$ac]['recheck']=$this->DB->Record['recheck'];
			$this->ADR[$ac]['proof']=$this->DB->Record['proof'];
			$this->ADR[$ac]['source']=$this->DB->Record['source'];
			$this->ADR[$ac]['source_id']=$this->DB->Record['source_id'];
			$this->ADR[$ac]['source_extern_id']=$this->DB->Record['source_extern_id'];
			$this->ADR[$ac]['d_id']=$this->DB->Record['d_id'];
			$this->ADR[$ac]['siteid']=$this->DB->Record['siteid'];
			if ($Details==1) {
				#$this->ADR[$ac]['memo']=$this->DB->Record['memo'];
				#$this->ADR[$ac]['d_id']=$this->DB->Record['d_id'];
				$this->ADR[$ac]['f0']=$this->DB->Record['f0'];
				$this->ADR[$ac]['f1']=$this->DB->Record['f1'];
				$this->ADR[$ac]['f2']=$this->DB->Record['f2'];
				$this->ADR[$ac]['f3']=$this->DB->Record['f3'];
				$this->ADR[$ac]['f4']=$this->DB->Record['f4'];
				$this->ADR[$ac]['f5']=$this->DB->Record['f5'];
				$this->ADR[$ac]['f6']=$this->DB->Record['f6'];
				$this->ADR[$ac]['f7']=$this->DB->Record['f7'];
				$this->ADR[$ac]['f8']=$this->DB->Record['f8'];
				$this->ADR[$ac]['f9']=$this->DB->Record['f9'];
			}
			$ac++;
		}
		$this->DB->free();
		return $this->ADR;
	}//getAdr

	function getAdrID($group_id=0,$search=Array()) {
	//liefert NUR die IDs zurueck als Array!!! Beoetigt fuer die Formulare
	//keine suche ueber f0_9 !!!!! da wir speicher sparen wollen... nur ids!
	//bzw neue query testen!
		$ADRID=Array();
		$DB=new tm_DB();
		$Query ="
						SELECT ".TM_TABLE_ADR.".id
						FROM ".TM_TABLE_ADR."
					";
		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		}
		if (check_dbid($group_id)) {
			$Query .="LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id";
		}
		$Query .=" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'";
		if (check_dbid($group_id)) {
			$Query .=" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($group_id);
		}
		if (isset($search['email']) && !empty($search['email'])) {
			$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
		}
		if (isset($search['recheck']) && !empty($search['recheck'])) {
			$Query .= " AND ".TM_TABLE_ADR.".recheck = ".checkset_int($search['recheck'])."";
		}
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".TM_TABLE_ADR.".aktiv = ".checkset_int($search['aktiv'])."";
		}
		//check for status, OR
		if (isset($search['status']) && $search['status']>0) {
			//if is no array, let first array entry be the string, so we always have an array
			if (!is_array($search['status'])) {
				$search_status=$search['status'];				
				$search['status']=Array();
				$search['status'][0]=$search_status;
			}
			//create query
			$ssc=count($search['status']);
			if ($search['status'][0]>0) {
				$Query .= " AND (";
				for ($sscc=0;$sscc<$ssc;$sscc++) {
					$Query .= TM_TABLE_ADR.".status=".checkset_int($search['status'][$sscc]);
					if (($sscc+1)<$ssc) $Query.=" OR";
				}
				$Query .= " )";
			}
		}		
		$DB->Query($Query);
		$ac=0;
		while ($DB->next_record()) {
			$ADRID[$ac]=$DB->Record['id'];
			$ac++;
		}
		$this->DB->free();
		return $ADRID;
	}//getAdrID

	function countADR($group_id=0,$search=Array()) {
		$Query ="
						SELECT count(".TM_TABLE_ADR.".id) as c
						FROM ".TM_TABLE_ADR."
					";
		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		}
		if (check_dbid($group_id)) {
			$Query .="LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id";
		}
		$Query .=" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'
					";
		if (check_dbid($group_id)) {
			$Query .=" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".TM_TABLE_ADR_GRP_REF.".grp_id = ".checkset_int($group_id);
		}
		if (isset($search['email']) && !empty($search['email'])) {
			$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
		}
		if (isset($search['recheck']) && !empty($search['recheck'])) {
			$Query .= " AND ".TM_TABLE_ADR.".recheck = ".checkset_int($search['recheck'])."";
		}
		//check for status, OR
		if (isset($search['status']) && $search['status']>0) {
			//if is no array, let first array entry be the string, so we always have an array
			if (!is_array($search['status'])) {
				$search_status=$search['status'];				
				$search['status']=Array();
				$search['status'][0]=$search_status;
			}
			//create query
			$ssc=count($search['status']);
			$Query .= " AND (";
			for ($sscc=0;$sscc<$ssc;$sscc++) {
				$Query .= TM_TABLE_ADR.".status=".checkset_int($search['status'][$sscc]);
				if (($sscc+1)<$ssc) $Query.=" OR";
			}
			$Query .= " )";
		}		
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".TM_TABLE_ADR.".aktiv = ".checkset_int($search['aktiv'])."";
		}
		$this->DB2->Query($Query);
		if ($this->DB2->next_record()) {
			$count=$this->DB2->Record['c'];
		}
		return $count;
	}//countADR

	function countValidADR($group_id=0) {
		global $C;
		$count=0;
		if (check_dbid($group_id)) {
			$Query ="
						SELECT count(".TM_TABLE_ADR.".id) as c
						FROM ".TM_TABLE_ADR."
							INNER JOIN ".TM_TABLE_ADR_GRP_REF." 
								ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id 
							INNER JOIN ".TM_TABLE_ADR_GRP." 
								ON ".TM_TABLE_ADR_GRP_REF.".grp_id = ".TM_TABLE_ADR_GRP.".id
						WHERE 
						".TM_TABLE_ADR.".aktiv=1
						AND ".TM_TABLE_ADR.".siteid='".TM_SITEID."'
						AND (
								".TM_TABLE_ADR.".errors<=".(checkset_int($C[0]['max_mails_retry']))."
								OR ".TM_TABLE_ADR.".errors IS NULL
								)
						AND (
								 ".TM_TABLE_ADR.".status=1 
								OR ".TM_TABLE_ADR.".status=2 
								OR ".TM_TABLE_ADR.".status=3 
								OR ".TM_TABLE_ADR.".status=4 
								OR ".TM_TABLE_ADR.".status=10 
								OR ".TM_TABLE_ADR.".status=12 
								)
						AND ".TM_TABLE_ADR.".siteid='".TM_SITEID."'
						AND ".TM_TABLE_ADR_GRP.".siteid='".TM_SITEID."'
						AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						AND ".TM_TABLE_ADR_GRP.".id='".checkset_int($group_id)."'
						";
			$this->DB2->Query($Query);
			if ($this->DB2->next_record()) {
				$count=$this->DB2->Record['c'];
			}
		}
		return $count;
	}//countValidADR


	function getGroup($id=0,$adr_id=0,$frm_id=0,$count=0,$search=Array()) {
		$this->GRP=Array();
		$Query = "SELECT ";
		if (check_dbid($adr_id) || check_dbid($frm_id)) {
			$Query .= "DISTINCT ";
		}
		
		$Query .= TM_TABLE_ADR_GRP.".id, "
							.TM_TABLE_ADR_GRP.".name, "
							.TM_TABLE_ADR_GRP.".public, "
							.TM_TABLE_ADR_GRP.".public_name, "
							.TM_TABLE_ADR_GRP.".descr, "
							.TM_TABLE_ADR_GRP.".aktiv, "
							.TM_TABLE_ADR_GRP.".standard, "
							.TM_TABLE_ADR_GRP.".prod, "
							.TM_TABLE_ADR_GRP.".author, "
							.TM_TABLE_ADR_GRP.".editor, "
							.TM_TABLE_ADR_GRP.".created, "
							.TM_TABLE_ADR_GRP.".updated, "
							."
							";
		
		if (check_dbid($frm_id)) {
			$Query .= "
				".TM_TABLE_FRM_GRP_REF.".public as public_frm_ref,
				";
		}
		$Query .= "
				".TM_TABLE_ADR_GRP.".siteid 
				";
			
		$Query .= "
			FROM ".TM_TABLE_ADR_GRP." ";
		if (check_dbid($adr_id)) {
			$Query .= ", ".TM_TABLE_ADR_GRP_REF." ";
		}
		if (check_dbid($frm_id)) {
			$Query .= ", ".TM_TABLE_FRM_GRP_REF." ";
		}
		
		$Query .= "
			WHERE ".TM_TABLE_ADR_GRP.".siteid='".TM_SITEID."'
			";

		//nur public groups? 0/1
		if ( isset($search['public']) ) {
			$Query .= "
				AND ".TM_TABLE_ADR_GRP.".public=".checkset_int($search['public'])."
				";
		}
		
		if (isset($search['aktiv']) && (checkset_int($search['aktiv'])===1 || checkset_int($search['aktiv'])===0)) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".TM_TABLE_ADR_GRP.".aktiv=".checkset_int($search['aktiv']);
		}

//aah! string compare wnn per url uebergeben! bzw int wenn ueber interne abfrage! weird! also fuer flags und zahlen und ids alles auf checkset_int umruesten

		if (isset($search['prod']) && (checkset_int($search['prod'])===1 || checkset_int($search['prod'])===0)) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".TM_TABLE_ADR_GRP.".prod=".checkset_int($search['prod']);
		}
		
		if (check_dbid($id)) {
			$Query .= " AND ".TM_TABLE_ADR_GRP.".id=".checkset_int($id);
		}

		if (check_dbid($adr_id)) {
			$Query .= "
				AND ".TM_TABLE_ADR_GRP.".id=".TM_TABLE_ADR_GRP_REF.".grp_id
				AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_ADR_GRP_REF.".adr_id=".checkset_int($adr_id);
		}
		
		if (check_dbid($frm_id)) {
			$Query .= "
				AND ".TM_TABLE_ADR_GRP.".id=".TM_TABLE_FRM_GRP_REF.".grp_id
				AND ".TM_TABLE_FRM_GRP_REF.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_FRM_GRP_REF.".frm_id=".checkset_int($frm_id);
		}
		//nur pub groups references used in forms 0|1
		if (check_dbid($frm_id) && isset($search['public_frm_ref']) ) {
			$Query .= "
				AND ".TM_TABLE_FRM_GRP_REF.".public=".checkset_int($search['public_frm_ref'])."
			";
		}

		$Query .= "	ORDER BY ".TM_TABLE_ADR_GRP.".name";
		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->GRP[$ac]['id']=$this->DB->Record['id'];
			$this->GRP[$ac]['siteid']=$this->DB->Record['siteid'];
			$this->GRP[$ac]['name']=$this->DB->Record['name'];
			$this->GRP[$ac]['public']=$this->DB->Record['public'];
			$this->GRP[$ac]['public_name']=$this->DB->Record['public_name'];
			$this->GRP[$ac]['descr']=$this->DB->Record['descr'];
			$this->GRP[$ac]['aktiv']=$this->DB->Record['aktiv'];
			$this->GRP[$ac]['standard']=$this->DB->Record['standard'];
			$this->GRP[$ac]['prod']=$this->DB->Record['prod'];
			$this->GRP[$ac]['author']=$this->DB->Record['author'];
			$this->GRP[$ac]['editor']=$this->DB->Record['editor'];
			$this->GRP[$ac]['created']=$this->DB->Record['created'];
			$this->GRP[$ac]['updated']=$this->DB->Record['updated'];
			if ( check_dbid($frm_id) ) {
				$this->GRP[$ac]['public_frm_ref']=$this->DB->Record['public_frm_ref'];
			}
			if ($count==1) {
				$this->GRP[$ac]['adr_count']=$this->countADR(checkset_int($this->GRP[$ac]['id']));
			}
			$ac++;
		}
		$this->DB->free();
		return $this->GRP;
	}//getGroup

	function getGroupID($id=0,$adr_id=0,$frm_id=0,$search=Array()) {
	//quick hack, should use create query method 
	//liefert NUR die IDs zurueck als Array!!! Beoetigt fuer die Formulare
		$GRPID=Array();
		$GRP=$this->getGroup($id,$adr_id,$frm_id,0,$search);
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
			$Query ="UPDATE ".TM_TABLE_ADR." SET aktiv=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"adr","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setAktiv

	function setGrpAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_ADR_GRP." SET aktiv=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"adr_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setGrpAktiv

	function setGrpProd($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_ADR_GRP." SET prod=".checkset_int($aktiv)." WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("prod"=>$aktiv,"id"=>$id),"object"=>"adr_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setGrpAktiv


	function setGrpStd($id=0) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_ADR_GRP." SET standard=0 WHERE standard=1 AND siteid='".TM_SITEID."'";
			//log
			$GRPSTD=$this->getGroupStd();
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>0,"id"=>$GRPSTD['id']),"object"=>"adr_grp","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			$Query ="UPDATE ".TM_TABLE_ADR_GRP." SET standard=1 WHERE id=".checkset_int($id)." AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>1,"id"=>$id),"object"=>"adr_grp","action"=>"edit"));
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
		$Query ="INSERT INTO ".TM_TABLE_ADR_GRP." (
					name,
					public,
					public_name,
					descr,
					aktiv, 
					standard,
					prod,
					created,author,
					updated,editor,
					siteid
					)
					VALUES (
					'".dbesc($group["name"])."',
					".checkset_int($group["public"]).",
					'".dbesc($group["public_name"])."',
					'".dbesc($group["descr"])."',
					".checkset_int($group["aktiv"]).", 
					0,
					".checkset_int($group["prod"]).", 
					'".dbesc($group["created"])."', '".dbesc($group["author"])."',
					'".dbesc($group["created"])."', '".dbesc($group["author"])."',
					'".TM_SITEID."')";
		if ($this->DB->Query($Query)) {
			//log
			$group['id']=$this->DB->LastInsertID;
			if (TM_LOG) $this->LOG->log(Array("data"=>$group,"object"=>"adr_grp","action"=>"new"));
			$Return=true;
		}
		#return $Return;
		return $group['id'];
		//should return ID!!!
	}//addGrp

	function updateGrp($group) {
		$Return=false;
		if (isset($group['id']) && check_dbid($group['id'])) {
			$Query ="UPDATE ".TM_TABLE_ADR_GRP."
					SET
					name='".dbesc($group["name"])."',
					public=".checkset_int($group["public"]).",
					public_name='".dbesc($group["public_name"])."',
					descr='".dbesc($group["descr"])."',
					aktiv=".checkset_int($group["aktiv"]).",
					prod=".checkset_int($group["prod"]).",
					updated='".dbesc($group["created"])."',
					editor='".dbesc($group["author"])."'
					WHERE siteid='".TM_SITEID."' AND id=".checkset_int($group["id"]);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$group,"object"=>"adr_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//updateGrp

	function cleanAdr($search) {
				/**/
				//delete flag setzen 'clean'
				/**/
				$Query ="UPDATE ".TM_TABLE_ADR." LEFT JOIN ".TM_TABLE_ADR_GRP_REF.
								" ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id".
								" SET ".TM_TABLE_ADR.".clean=1".
								" WHERE ".
								TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($search['group']).
								" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR.".siteid='".TM_SITEID."'";
				if (isset($search['email']) && !empty($search['email'])) {
					$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
				}
				if (isset($search['status']) && $search['status']>0) {
					$Query .=" 
								AND ".TM_TABLE_ADR.".status=".checkset_int($search['status']);
				}
				if ($this->DB->Query($Query)) {
					//Log only here					
					//NO ITEM LOGGING
					//BUT LOG ACTION
					if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>0,"search"=>$search),"object"=>"adr","action"=>"delete","memo"=>"cleanAdr()"));

					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				/**/
				//adr details loeschen
				/**/
				$Query ="DELETE ".TM_TABLE_ADR_DETAILS." FROM ".TM_TABLE_ADR_DETAILS."  ".
								" LEFT JOIN ".TM_TABLE_ADR." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_DETAILS.".adr_id".
								" LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id".
								" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR.".clean=1".
								" AND ".TM_TABLE_ADR_DETAILS.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($search['group']);
				if (isset($search['email']) && !empty($search['email'])) {
					$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
				}
				if (isset($search['status']) && $search['status']>0) {
					$Query .="
								AND ".TM_TABLE_ADR.".status=".checkset_int($search['status']);
				}
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				
				/**/
				//referenzen zu gruppen loeschen
				/**/
				$Query ="DELETE ".TM_TABLE_ADR_GRP_REF." FROM ".TM_TABLE_ADR_GRP_REF."  ".
								" LEFT JOIN ".TM_TABLE_ADR." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id".
								" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR.".clean=1".
								" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($search['group']);
				if (isset($search['email']) && !empty($search['email'])) {
					$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
				}
				if (isset($search['status']) && $search['status']>0) {
					$Query .="
								AND ".TM_TABLE_ADR.".status=".checkset_int($search['status']);
				}
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}

				/**/
				//subscriptions loeschen
				/**/
				/**/
				//adressen loeschen
				/**/
				$Query ="DELETE FROM ".TM_TABLE_ADR."  ".
								" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'".
								" AND ".TM_TABLE_ADR.".clean=1";
				if (isset($search['email']) && !empty($search['email'])) {
					$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
				}
				if (isset($search['status']) && $search['status']>0) {
					$Query .="
								AND ".TM_TABLE_ADR.".status=".checkset_int($search['status']);
				}
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
	}
	
	function delGrp($id,$reorg=1,$deleteAdr=0) {
		$Return=false;
		if (check_dbid($id)) {
			//OK, log here, befoe deletion, but maybe add some more logging on details, new references etc
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"adr_grp","action"=>"delete","memo"=>"reorg:".$reorg." deladr:".$deleteAdr));					
			if (TM_LOG && $deleteAdr==1) $this->LOG->log(Array("data"=>Array("id"=>0),"object"=>"adr","action"=>"delete","memo"=>"deleted adr group ".$id." and all adr"));

			//wenn reorg==1, dfault, dann adressen der standardgruppe neu zuordnen
			//andernfalls, auch adressen loeschen?
			//standard gruppe suchen
			if ($reorg==1) {
				//ggf get Std Group() nutzen
				$Query ="SELECT id FROM ".TM_TABLE_ADR_GRP
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
					$total=$this->countADR($id);
					global $adr_row_limit;
					$limit=$adr_row_limit;
					for ($offset=0; $offset <= $total; $offset+=$limit) {
						$adr=$this->getAdr(0,$offset,$limit,$id,Array(),"","",0);
						//referenzen zur Standardgruppe dieser adressen suchen und aufloesen:
						$ac=count($adr);
						//schleife drumherum mit adr_row_limit
						for ($acc=0;$acc<$ac;$acc++) {
							$Query ="SELECT ".TM_TABLE_ADR_GRP_REF.".id FROM ".TM_TABLE_ADR_GRP_REF.
											" WHERE siteid='".TM_SITEID.
											"' AND adr_id=".checkset_int($adr[$acc]['id']).
											" AND grp_id=".checkset_int($stdGrpID);
							if ($this->DB->Query($Query)) {
								$Return=true;
							} else {
								$Return=false;
								return $Return;
							}
							if ($this->DB->next_record()) {
								//  ist adr mit standardgruppe verknuepft, dann alte referenz loeschen
								$Query ="DELETE FROM ".TM_TABLE_ADR_GRP_REF."  WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($adr[$acc]['id'])." AND grp_id=".checkset_int($id);
								if ($this->DB->Query($Query)) {
									$Return=true;
								} else {
									$Return=false;
									return $Return;
								}
							} else {	//oder
								//  ist adr NICHT mit standardgruppe verknuepft, dann alte referenz mit stdgruppe updaten
								//update der verknuepfung zur alten Gruppe mit der neuen Gruppe...
								$Query ="UPDATE ".TM_TABLE_ADR_GRP_REF." SET grp_id=".checkset_int($stdGrpID)." WHERE siteid='".TM_SITEID."' AND grp_id=".checkset_int($id);
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
			
			if ($deleteAdr==1) {
				//einzelne adressen in array einlesen! achtung, array wird zu gross! deshalb kommt hier ne eigene query hin!
				//adressdetails loeschen:
				/*zu dumm, mysql kann kein delete + join...pfff, also 2 queries, zuerst die adresseendetails, dann die adr, ... und zum schluss die detailsdaten wie q und history etc?? siehe unten, anmerkungen
				*/
				$Query ="DELETE ".TM_TABLE_ADR_DETAILS." FROM ".TM_TABLE_ADR_DETAILS."  ";//WHERE siteid='".TM_SITEID."' ";
				$Query .=" LEFT JOIN ".TM_TABLE_ADR." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_DETAILS.".adr_id";
				$Query .=" LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id";
				$Query .=" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'";
				$Query .=" AND ".TM_TABLE_ADR_DETAILS.".siteid='".TM_SITEID."'
							AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				/**/
				//adressen loeschen
				/**/
				$Query ="DELETE ".TM_TABLE_ADR." FROM ".TM_TABLE_ADR."  ";//WHERE siteid='".TM_SITEID."' ";
				$Query .="LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR_GRP_REF.".adr_id = ".TM_TABLE_ADR.".id";
				$Query .=" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'";
				$Query .=" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".TM_TABLE_ADR_GRP_REF.".grp_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				/**/
				//referenzen loeschen
				/**/
				$Query ="DELETE FROM ".TM_TABLE_ADR_GRP_REF."  WHERE siteid='".TM_SITEID."' AND grp_id=".checkset_int($id);
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
			//aber die q muss geloescht werden!!!
			//q loeschen
			$Query ="DELETE FROM ".TM_TABLE_NL_Q." WHERE siteid='".TM_SITEID."' AND grp_id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//verknuepfung zu formularen loeschen
			$Query ="DELETE FROM ".TM_TABLE_FRM_GRP_REF."  WHERE siteid='".TM_SITEID."' AND grp_id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//gruppe loeschen
			$Query ="DELETE FROM ".TM_TABLE_ADR_GRP."  WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//delGrp

	function delAdr($id) {
		$Return=false;
		if (check_dbid($id)) {
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"adr","action"=>"delete"));
			//versandliste, history h loeschen
			//ok jetzt historie loeschen! aber nicht wenn adr_grp oder q!
			$Query ="DELETE FROM ".TM_TABLE_NL_H." WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			//referenzen loeschen
			$Query ="DELETE FROM ".TM_TABLE_ADR_GRP_REF." WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			//details loeschen
			$Query ="DELETE FROM ".TM_TABLE_ADR_DETAILS." WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			//subscriptions loeschen
			$Query ="DELETE FROM ".TM_TABLE_FRM_S." WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//adresse loeschen
			$Query ="DELETE FROM ".TM_TABLE_ADR." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//delAdr

	function addAdr($adr,$grp) {
		$Return=false;
		//neue Adresse speichern
		$Query ="INSERT INTO ".
						TM_TABLE_ADR.
						" (
							email,
							aktiv,
							status,
							code,
							author,
							created,
							editor,
							updated,
							clicks,
							views,
							newsletter,
							source,
							source_id,
							source_extern_id,
							siteid
						)
					VALUES 
						(
						lcase('".dbesc($adr["email"])."'),
						".checkset_int($adr["aktiv"]).",
						".checkset_int($adr["status"]).",
						'".dbesc($adr["code"])."',
						'".dbesc($adr["author"])."',
						'".dbesc($adr["created"])."',
						'".dbesc($adr["author"])."',
						'".dbesc($adr["created"])."',
						0, 0, 0,
						'".dbesc($adr["source"])."',
						".checkset_int($adr["source_id"]).",
						".checkset_int($adr["source_extern_id"]).",
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
			$new_adr_id=$this->DB->LastInsertID;
			//detaildaten eintragen
			$Query="INSERT INTO ".TM_TABLE_ADR_DETAILS." (adr_id,siteid,f0,f1,f2,f3,f4,f5,f6,f7,f8,f9)
							VALUES ('".
										checkset_int($new_adr_id)."',
										'".TM_SITEID."',
										'".dbesc($adr['f0'])."',
										'".dbesc($adr['f1'])."',
										'".dbesc($adr['f2'])."',
										'".dbesc($adr['f3'])."',
										'".dbesc($adr['f4'])."',
										'".dbesc($adr['f5'])."',
										'".dbesc($adr['f6'])."',
										'".dbesc($adr['f7'])."',
										'".dbesc($adr['f8'])."',
										'".dbesc($adr['f9'])."'
										)";
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {//if query
				$Return=false;
				return $Return;
			}//if query

			//log here
			$adr['id']=$new_adr_id;
			$adr['grp']=$grp;
			if (TM_LOG) $this->LOG->log(Array("data"=>$adr,"object"=>"adr","action"=>"new"));
			
			
			//memo eintragen
			//wenn memo expliziot gesetzt wurde, hier anfuegen, ansonsten muss das script selbst addMemo() oder newMemo() aufrufen!
			if (isset($adr['memo']) && !empty($adr['memo'])) $this->newMemo($new_adr_id,$adr['memo']); // neue memo!
			//gruppen eintragen
			//use internal method addref instead:
			$this->addRef($new_adr_id,$grp);
		}//get id, next record, bzw if $this->DB->LastInsertID!=0
		$Return=$new_adr_id;
		return $Return;
	}//addAdr

	//addRef: Gruppenreferenzen anlegen, keine pruefung auf doppelte refs!!! nur geeignet zum neuanlegen der rerefenzen bei ersteintrag! setGroup() fuer updates etc! setGroup unterstuetzt auch merge etc.
	//wird auch von setgroup aufgerufen ! ;-)
	//adr_id= address id, grp: array with group_ids
	function addRef($adr_id,$grp) {
		$Return=false;
		if (check_dbid($adr_id)) {
			//gruppen eintragen
			$acg=count($grp);
			if ($acg>0) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$adr_id,"grp"=>$grp),"object"=>"adr","action"=>"edit"));
				for ($accg=0;$accg<$acg;$accg++) {
					if (isset($grp[$accg])) {
						$Query="INSERT INTO ".TM_TABLE_ADR_GRP_REF." (adr_id,grp_id,siteid) VALUES (".checkset_int($adr_id).",".checkset_int($grp[$accg]).",'".TM_SITEID."')";
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

	function setStatus($id,$status) {
		//ups, author und editdatum vergessen...hmmm
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_ADR." SET status=".checkset_int($status)."
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("status"=>$status,"id"=>$id),"object"=>"adr","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}

	//fuer unsubscribe form
	function unsubscribe($id,$author) {
		$Return=false;
		if (check_dbid($id)) {
			$updated=date("Y-m-d H:i:s");
			$Query ="UPDATE ".TM_TABLE_ADR." SET updated='".$updated."', status=11, editor='".dbesc($author)."' WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			//log
			
			#oder, 2 queries, einmal edit und author, danach setstatus
			#sollte vereinheitlicht werden!!!
			
			#query fuer datum und author			
			#$Query ="UPDATE ".TM_TABLE_ADR." SET updated='".date("Y-m-d H:i:s")."' editor='".dbesc($author)."' WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			#und nun setstatus ;)
			#$this->setStatus($id,11);
		
						 
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("status"=>11,"id"=>$id,"editor"=>$author, "updated"=>$updated),"object"=>"adr","action"=>"edit","memo"=>"adr_unsubscribe()"));
				$Return=true;
			}
		}
		return $Return;
	}

	function updateAdr($adr,$grp) {
		$Return=false;
		if (isset($adr['id']) && check_dbid($adr['id'])) {
			$adr['grp']=$grp;			
			$Query ="UPDATE ".TM_TABLE_ADR." SET
							email=lcase('".dbesc($adr["email"])."'),
							aktiv=".checkset_int($adr["aktiv"]).",
							editor='".dbesc($adr["author"])."',
							updated='".dbesc($adr["created"])."'
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr["id"]);
			if ($this->DB->Query($Query)) {
				$adr['grp']=$grp;
				if (TM_LOG) $this->LOG->log(Array("data"=>$adr,"object"=>"adr","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//details
			$Query ="UPDATE ".TM_TABLE_ADR_DETAILS." SET
						f0='".dbesc($adr["f0"])."',
						f1='".dbesc($adr["f1"])."',
						f2='".dbesc($adr["f2"])."',
						f3='".dbesc($adr["f3"])."',
						f4='".dbesc($adr["f4"])."',
						f5='".dbesc($adr["f5"])."',
						f6='".dbesc($adr["f6"])."',
						f7='".dbesc($adr["f7"])."',
						f8='".dbesc($adr["f8"])."',
						f9='".dbesc($adr["f9"])."'
						 WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($adr["id"]);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//update memo!
			//wenn memo expliziot gesetzt wurde, hier anfuegen, ansonsten muss das script selbst addMemo() oder newMemo() aufrufen!
			if (isset($adr['memo']) && !empty($adr['memo'])) $this->addMemo($adr['id'],$adr['memo']);//memo anfuegen
			//update group references
			//use setGroup instead!:
			$this->setGroup($adr['id'],$grp);
		}//if adr_id
		return $Return;
	}//updateAdr
//setGroup: set Address references, delete old refs, create new
//takes at least 2 params, adr_id and $new_group (array with group_ids!)
//if 3. parameter  $old_grp is set and 4th param $merge is 1, then new and old groups will be merged and unified
function setGroup($adr_id,$new_grp,$old_grp=Array(),$merge=0) {
		$Return=false;
		if (isset($adr_id) && check_dbid($adr_id)) {
			//alle refs loeschen
			$Query ="DELETE FROM ".TM_TABLE_ADR_GRP_REF." WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($adr_id);
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
			$this->addRef($adr_id,$groups);
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

	function setAError($id,$errors) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_ADR." SET errors=".checkset_int($errors)."
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}

	function addClick($adr_id) {
		$Return=false;
		if (check_dbid($adr_id)) {
			/*
			$ADR=$this->getADR($adr_id);
			$clicks=$ADR[0]['clicks'];
			$clicks++;
			$Query ="UPDATE ".TM_TABLE_ADR." SET clicks=".checkset_int($clicks)." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr_id);
			*/
			$Query ="UPDATE ".TM_TABLE_ADR." SET clicks=clicks+1 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//addClick

	function addView($adr_id) {
		$Return=false;
		if (check_dbid($adr_id)) {
			/*
			$ADR=$this->getADR($adr_id);
			$views=$ADR[0]['views'];
			$views++;
			$Query ="UPDATE ".TM_TABLE_ADR." SET views=".checkset_int($views)." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr_id);
			*/
			$Query ="UPDATE ".TM_TABLE_ADR." SET views=views+1 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//addView

	function addNL($adr_id) {
		$Return=false;
		if (check_dbid($adr_id)) {
		/*
			$ADR=$this->getADR($adr_id);
			$nl=$ADR[0]['newsletter'];
			$nl++;
			$Query ="UPDATE ".TM_TABLE_ADR." SET newsletter=".checkset_int($nl)." WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr_id);
		*/
			$Query ="UPDATE ".TM_TABLE_ADR." SET newsletter=newsletter+1 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($adr_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//addNL

	function newMemo($adr_id,$memo) {
		$Return=false;
		if (check_dbid($adr_id)) {
			$memo=date("Y-m-d H:i:s").": ".$memo;
			$Query ="UPDATE ".TM_TABLE_ADR_DETAILS." SET memo='".dbesc($memo)."' WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($adr_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//newMemo
	function addMemo($adr_id,$memo) {
		$Return=false;
		if (check_dbid($adr_id)) {
			$ADR=$this->getADR($adr_id);
			//memo = datum + neue memo + alte memo, neu steht somit jetzt immer oben
			$memo=date("Y-m-d H:i:s").": ".$memo."\n\n".$ADR[0]['memo'];
			$Query ="UPDATE ".TM_TABLE_ADR_DETAILS." SET memo='".dbesc($memo)."' WHERE siteid='".TM_SITEID."' AND adr_id=".checkset_int($adr_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
		}
		return $Return;
	}//addMemo
	
	function markRecheck($id, $recheck=0,$search=Array()) {
		$Query ="
						UPDATE ".TM_TABLE_ADR."
					";
		if (isset($search['group']) && !empty($search['group'])) {
			$group_id=checkset_int($search['group']);
		} else {
			$group_id=0;		
		}
		if (check_dbid($group_id)) {
			$Query .="LEFT JOIN ".TM_TABLE_ADR_GRP_REF." ON ".TM_TABLE_ADR.".id = ".TM_TABLE_ADR_GRP_REF.".adr_id";
		}
		$Query .=" SET  ".TM_TABLE_ADR.".recheck=".checkset_int($recheck); 
		$Query .=" WHERE ".TM_TABLE_ADR.".siteid='".TM_SITEID."'
					";
		if (check_dbid($group_id)) {
			$Query .=" AND ".TM_TABLE_ADR_GRP_REF.".siteid='".TM_SITEID."'
						  AND ".TM_TABLE_ADR_GRP_REF.".grp_id = ".checkset_int($group_id);
		}
		if (isset($search['email']) && !empty($search['email'])) {
			$Query .= " AND lcase(".TM_TABLE_ADR.".email) like lcase('".dbesc($search['email'])."')";
		}
		//check for status, OR
		if (isset($search['status']) && $search['status']>0) {
			//if is no array, let first array entry be the string, so we always have an array
			if (!is_array($search['status'])) {
				$search_status=$search['status'];				
				$search['status']=Array();
				$search['status'][0]=$search_status;
			}
			//create query
			$ssc=count($search['status']);
			$Query .= " AND (";
			for ($sscc=0;$sscc<$ssc;$sscc++) {
				$Query .= TM_TABLE_ADR.".status=".checkset_int($search['status'][$sscc]);
				if (($sscc+1)<$ssc) $Query.=" OR";
			}
			$Query .= " )";
		}
		if (isset($search['aktiv']) && ($search['aktiv']==="1" || $search['aktiv']==="0")) {//!!! we have to compare strings, weird php! argh.
			$Query .= " AND ".TM_TABLE_ADR.".aktiv = ".checkset_int($search['aktiv']);
		}
		if (check_dbid($id)) {
			$Query .= " AND ".TM_TABLE_ADR.".id=".checkset_int($id);
		}
		$this->DB2->Query($Query);
	}

	function remove_duplicates($search) {
		$this->fetch_duplicates($search);
		
		//now deleting duplicates one by one, this must be imporved
		//lateron we will only set a delete flag and then use a clean up function like in clean_adr
		#$c=0;
		foreach ($this->DUPLICATES['dups'] as $DUP) {
			foreach ($DUP['del'] as $del_id) {
				if (!tm_DEMO()) $this->delADR($del_id);
			}
		}
		return $this->DUPLICATES;
	}
	function fetch_duplicates($search) {
		$this->DUPLICATES=Array();
		$this->DUPLICATES['dups']=Array();
		$ac=0;
		//check if keep method is set, this tells the method what address should be kept, first, last or random, others get deleted!!!
		if (isset($search['method'])) {
			//create the query, this will return only addresses with at least 1 duplicate (having qty>0)
			//we have to use innerjoin because we have an additional unique index 'id'
			//query willnot return qty, so he have to count them ourself			
			//this does the trick:
			
			#SELECT t1.id, t1.colb FROM t1 INNER JOIN (SELECT count(t1.colb) AS qty, t1.colb FROM t1 GROUP t1.colb HAVING qty>1) AS dups ON t1.colb=dups.colb ORDER BY t1.colb, t1.id
			$Query = "SELECT 
						".TM_TABLE_ADR.".id, 
						".TM_TABLE_ADR.".email 
						FROM ".TM_TABLE_ADR."
						INNER JOIN ( 
							SELECT count(".TM_TABLE_ADR.".email) AS qty,
								".TM_TABLE_ADR.".email 
								FROM ".TM_TABLE_ADR." 
								GROUP BY ".TM_TABLE_ADR.".email
								HAVING qty >1
								";
			if (isset($search['limit']) && $search['limit']>0) {
				$Query.=" LIMIT ".checkset_int($search['limit'])."
						";
			}
			$Query.=") AS dups
							ON 
							".TM_TABLE_ADR.".email = dups.email 
						ORDER BY ".TM_TABLE_ADR.".email ASC,".TM_TABLE_ADR.".id ASC
					";			
			//SORT BY IST RELEVANT, DA WIR KEEP LATEST ODER KEEP FIRST ENTRY NUTZEN.
			//send query to db and fetch duplicates
			$this->DB->Query($Query);
			$dupcount=0;
			while ($this->DB->next_record()) {
				//index for dupicates array is the email address! hmmmm
				$email=$this->DB->Record['email'];
				//check if index exists, otherwise create it and save data, count duplicates
				if (isset($this->DUPLICATES['dups'][$email])) {
					$qty=count($this->DUPLICATES['dups'][$email]['id']);
					$this->DUPLICATES['dups'][$email]['id'][$qty]=$this->DB->Record['id'];
					//count
					$this->DUPLICATES['dups'][$email]['qty']++; #++ oder $qty+1;
				} else {
					$this->DUPLICATES['dups'][$email]['email']=$this->DB->Record['email'];
					$this->DUPLICATES['dups'][$email]['qty']=1;
					$this->DUPLICATES['dups'][$email]['id'][0]=$this->DB->Record['id'];
				}
				$dupcount++;
				#= $dupcount + $this->DUPLICATES[$email]['qty'];
			}//while
			$this->DB->free();
			//array is created like so:
			//['dups']: 'email' - unique email adr, 'qty' count/quantity, 'id' =Array with ids
			//count: unique dups
			//cound_dup: all dups
			
			//now walk through the array and check which entry should be kept using array index
			foreach ($this->DUPLICATES['dups'] as $DUP) {	
				if ($search['method']=='random') {
					$keep=rand(0,($DUP['qty']-1));
				}		
				if ($search['method']=='first') {
					$keep=0;
				}		
				if ($search['method']=='last') {
					$keep=$DUP['qty']-1;
				}
				$this->DUPLICATES['dups'][$DUP['email']]['keep']=$keep;
				$this->DUPLICATES['dups'][$DUP['email']]['del']=Array();
				//save ids to delete in an array 'del'
				for ($dc=0;$dc<$DUP['qty'];$dc++) {
					if ($dc != $keep) {
						$del=count($this->DUPLICATES['dups'][$DUP['email']]['del']);
						$this->DUPLICATES['dups'][$DUP['email']]['del'][$del]=$DUP['id'][$dc];
					}//if				
				}//for
				
				###improvements:
				#check syntax etc and check what data should be preserved...! automagic, if keeping email is invalid, use another one etc pp
			}//foreach
		}//isst method


		//count
		$this->DUPLICATES['count']=count($this->DUPLICATES['dups']);//must be first defined, we add more indexes later!!!
		$this->DUPLICATES['count_dup']=$dupcount;


		//return array to display
		return $this->DUPLICATES;
	}//fetch_duplicates
	
	function count_duplicates() {
		$count=0;
		$Query = "SELECT ".TM_TABLE_ADR.".email, count(".TM_TABLE_ADR.".email) AS qty
					FROM ".TM_TABLE_ADR." 
							GROUP BY ".TM_TABLE_ADR.".email
							HAVING qty >1
							";
		$this->DB->Query($Query);
		while ($this->DB->next_record()) {
			//index for dupicates array is the email address! hmmmm
			$count +=$this->DB->Record['qty'];
		}
		return $count;
	}//count_duplicates
	
	function genCSVHeader($delimiter=",") {
	 	$CSV="\"email\"$delimiter\"f0\"$delimiter\"f1\"$delimiter\"f2\"$delimiter\"f3\"$delimiter\"f4\"$delimiter\"f5\"$delimiter\"f6\"$delimiter\"f7\"$delimiter\"f8\"$delimiter\"f9\"$delimiter\"id\"$delimiter\"created\"$delimiter\"author\"$delimiter\"updated\"$delimiter\"editor\"$delimiter\"aktiv\"$delimiter\"status\"$delimiter\"code\"$delimiter\"errors\"$delimiter\"clicks\"$delimiter\"views\"$delimiter\"newsletter\"$delimiter\"source\"$delimiter\"source_id\"$delimiter\"source_extern_id\"$delimiter\"memo\"\n";
		return $CSV;	
	}
	
	function genCSVline($ADR,$delimiter=",") {
		$CSV="";		
		$memo=str_replace("\n"," --|-- ",$ADR['memo']);
		$memo=str_replace("\r"," --|-- ",$memo);
		$CSV.="\"".$ADR['email']."\"$delimiter";
		$CSV.="\"".$ADR['f0']."\"$delimiter";
		$CSV.="\"".$ADR['f1']."\"$delimiter";
		$CSV.="\"".$ADR['f2']."\"$delimiter";
		$CSV.="\"".$ADR['f3']."\"$delimiter";
		$CSV.="\"".$ADR['f4']."\"$delimiter";
		$CSV.="\"".$ADR['f5']."\"$delimiter";
		$CSV.="\"".$ADR['f6']."\"$delimiter";
		$CSV.="\"".$ADR['f7']."\"$delimiter";
		$CSV.="\"".$ADR['f8']."\"$delimiter";
		$CSV.="\"".$ADR['f9']."\"$delimiter";
		$CSV.="\"".$ADR['id']."\"$delimiter";
		$CSV.="\"".$ADR['created']."\"$delimiter";
		$CSV.="\"".$ADR['author']."\"$delimiter";
		$CSV.="\"".$ADR['updated']."\"$delimiter";
		$CSV.="\"".$ADR['editor']."\"$delimiter";
		$CSV.="\"".$ADR['aktiv']."\"$delimiter";
		$CSV.="\"".$ADR['status']."\"$delimiter";
		$CSV.="\"".$ADR['code']."\"$delimiter";
		$CSV.="\"".$ADR['errors']."\"$delimiter";
		$CSV.="\"".$ADR['clicks']."\"$delimiter";
		$CSV.="\"".$ADR['views']."\"$delimiter";
		$CSV.="\"".$ADR['newsletter']."\"$delimiter";
		$CSV.="\"".$ADR['source']."\"$delimiter";
		$CSV.="\"".$ADR['source_id']."\"$delimiter";
		$CSV.="\"".$ADR['source_extern_id']."\"$delimiter";
		$CSV.="\"".$memo."\"$delimiter";
		$CSV.="\n";
		return $CSV;
	}
	
	function proof() {
	}
	
	function proof_adr(&$adr) {
		/*
		if (count($adr) >= TM_PROOF_TRIGGER) {
			$proof_count=round((count($adr)/100)*TM_PROOF_PC);
			#$proof_step=round(count($adr)/(100/TM_PROOF_PC));
			$proof_step=round(count($adr)/$proof_count);
			$proof="";
			for ($proof_index=0;$proof_index<$proof_count;$proof_index=$proof_index+$proof_step) {
					//$proof_record=rand(0,($lines-1));... every x record
				if (isset($adr[$proof_index])) {
					$proof.=md5($adr[$proof_index]['email']).";";
				}
			}
		}
		*/
	}//proof
}//class
?>