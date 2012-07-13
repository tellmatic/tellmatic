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

class tm_NL {
	/**
	* Newsletter
	* @var array
	*/
	var $NL=Array();
	/**
	* Newsletter Group
	* @var array
	*/
	var $GRP=Array();
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
	* parseLog Array
	* @var array
	*/	
	var $parseLog=Array();
  
	/**
	* Constructor, creates new Instances for DB and LOG Objects 
	* @param
	*/	
	function tm_NL() {
		$this->DB=new tm_DB();
		$this->DB2=new tm_DB();
		if (TM_LOG) $this->LOG=new tm_LOG();
  }

	function getNL($id=0,$offset=0,$limit=0,$group_id=0,$return_content=0,$sortIndex="",$sortType=0,$search=Array()) {
		$this->NL=Array();
		$Query ="
						SELECT
						id, subject, title, title_sub, body,body_text, body_head, body_foot, summary, 
						created, author, updated, editor,
						link, status, massmail, clicks, views, track_image, track_personalized, use_inline_images,
						grp_id, aktiv,
						content_type, rcpt_name,
						is_template, siteid
						FROM ".TM_TABLE_NL."
						WHERE ".TM_TABLE_NL.".siteid='".TM_SITEID."'
					";
					//link, status, massmail, clicks, views, attm, track_image,
		if (check_dbid($group_id)) {
			$Query .=" AND grp_id=".checkset_int($group_id)."
						";
		}
		if (check_dbid($id)) {
			$Query .= " AND id=".checkset_int($id)." ";
		}

		if (isset($search['aktiv'])) {
			$Query .= " AND aktiv=".checkset_int($search['aktiv'])." ";
		}
		//check for status, OR
		if (isset($search['status'])) {
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
				if ($search['status'][$sscc]>0) {
					$Query .= " status=".checkset_int($search['status'][$sscc]);
					if (($sscc+1)<$ssc) $Query.=" OR";
				}
			}
			$Query .= " )";
		}		
		
		if (isset($search['is_template'])) {
			$Query .= " AND is_template=".checkset_int($search['is_template'])." ";
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
			$id=$this->DB->Record['id'];
			#$this->NL[$ac]['id']=$this->DB->Record['id'];
			$this->NL[$ac]['id']=$id;
			$this->NL[$ac]['aktiv']=$this->DB->Record['aktiv'];
			$this->NL[$ac]['created']=$this->DB->Record['created'];
			$this->NL[$ac]['updated']=$this->DB->Record['updated'];
			$this->NL[$ac]['author']=$this->DB->Record['author'];
			$this->NL[$ac]['editor']=$this->DB->Record['editor'];
			$this->NL[$ac]['subject']=$this->DB->Record['subject'];
			$this->NL[$ac]['title']=$this->DB->Record['title'];
			$this->NL[$ac]['title_sub']=$this->DB->Record['title_sub'];
			if ($return_content==1) {
				$this->NL[$ac]['body']=$this->DB->Record['body'];
				$this->NL[$ac]['body_text']=$this->DB->Record['body_text'];
				$this->NL[$ac]['body_head']=$this->DB->Record['body_head'];
				$this->NL[$ac]['body_foot']=$this->DB->Record['body_foot'];
				$this->NL[$ac]['summary']=$this->DB->Record['summary'];
			}

			$this->NL[$ac]['is_template']=$this->DB->Record['is_template'];
			$this->NL[$ac]['status']=$this->DB->Record['status'];
			$this->NL[$ac]['massmail']=$this->DB->Record['massmail'];
			$this->NL[$ac]['grp_id']=$this->DB->Record['grp_id'];
			$this->NL[$ac]['link']=$this->DB->Record['link'];
			$this->NL[$ac]['clicks']=$this->DB->Record['clicks'];
			$this->NL[$ac]['views']=$this->DB->Record['views'];
			$this->NL[$ac]['content_type']=$this->DB->Record['content_type'];
			$this->NL[$ac]['track_image']=$this->DB->Record['track_image'];
			$this->NL[$ac]['track_personalized']=$this->DB->Record['track_personalized'];
			$this->NL[$ac]['use_inline_images']=$this->DB->Record['use_inline_images'];
			$this->NL[$ac]['rcpt_name']=$this->DB->Record['rcpt_name'];
			$this->NL[$ac]['attachements']=$this->getAttm($id);
			$this->NL[$ac]['siteid']=$this->DB->Record['siteid'];
			$ac++;
		}
		return $this->NL;
	}//getNL

	function addNL($nl) {
		$Return=false;
		$Query ="INSERT INTO ".TM_TABLE_NL."
						(
						subject,
						title, title_sub,
						body,body_text,body_head, body_foot,
						summary,
						aktiv,
						created,author,updated,editor,
						link,grp_id,status,track_image,track_personalized,use_inline_images,
						massmail,clicks,views,
						content_type, rcpt_name,
						is_template,
						siteid
						)
						VALUES
						(
						'".dbesc($nl["subject"])."',
						'".dbesc($nl["title"])."', '".dbesc($nl["title_sub"])."',
						'".dbesc($nl["body"])."', '".dbesc($nl["body_text"])."',  '".dbesc($nl["body_head"])."', '".dbesc($nl["body_foot"])."',
						'".dbesc($nl["summary"])."',
						".checkset_int($nl["aktiv"]).",
						'".dbesc($nl["created"])."', '".dbesc($nl["author"])."', '".dbesc($nl["created"])."', '".dbesc($nl["author"])."',
						'".dbesc($nl["link"])."',".checkset_int($nl["grp_id"]).",".checkset_int($nl["status"]).", '".dbesc($nl["track_image"])."', ".checkset_int($nl["track_personalized"]).", ".checkset_int($nl["use_inline_images"]).",
						".checkset_int($nl["massmail"]).", 0, 0,
						'".dbesc($nl["content_type"])."', '".dbesc($nl["rcpt_name"])."',
						".checkset_int($nl["is_template"]).",
						'".TM_SITEID."'
						)";
		$new_nl_id=0;
		if ($this->DB->Query($Query)) {
			//neue id
			$new_nl_id=$this->DB->LastInsertID;
			//add attachements
			$this->addAttm($new_nl_id,$nl['attachements']);
			//log
			$nl['id']=$new_nl_id;
			if (TM_LOG) $this->LOG->log(Array("data"=>$nl,"object"=>"nl","action"=>"new"));
			#$Return=true;
			$Return=$nl['id'];
		}
		return $Return;
	}//addNL

	function addAttm($nl_id,$attm) {
		$Return=false;
		if (check_dbid($nl_id)) {
			//alte eintraege erstmal loeschen
				$Query ="DELETE FROM ".TM_TABLE_NL_ATTM."
							WHERE
							nl_id=".checkset_int($nl_id)."
							and siteid='".TM_SITEID."'
							";
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}//if query
			//und dann neue hinzufuegen	, falls welche vorhanden
			if (count($attm>0)) {
				foreach ($attm as $attachement => $filename) {
					$Query ="INSERT INTO ".TM_TABLE_NL_ATTM."
								(nl_id,	file,siteid)
								VALUES
								(
								".checkset_int($nl_id).",
								'".dbesc($filename)."',
								'".TM_SITEID."'
								)";
					if ($this->DB->Query($Query)) {
						$Return=true;
					} else {
						$Return=false;
						return $Return;
					}//if query
				}//count
			}//foreach
		}//if dbid
		return $Return;
	}//addAttm

	function getAttm($nl_id) {
		$attm=Array();
		if (check_dbid($nl_id)) {
			$Query ="SELECT 
						id,nl_id,file,siteid 
						FROM ".TM_TABLE_NL_ATTM."
						WHERE
						nl_id=".checkset_int($nl_id)."
						AND ".TM_TABLE_NL_ATTM.".siteid='".TM_SITEID."'
						";
			$this->DB2->Query($Query);
			$atc=0;
			while ($this->DB2->next_record()) {
				$attm[$atc]['id']=$this->DB2->Record['id'];
				$attm[$atc]['nl_id']=$this->DB2->Record['nl_id'];
				$attm[$atc]['file']=$this->DB2->Record['file'];
				$attm[$atc]['siteid']=$this->DB2->Record['siteid'];
				$atc++;
			}
		}//if dbid
		#print_r($attm);
		return $attm;
	}//getAttm

	function updateNL($nl) {
		$Return=false;
		if (check_dbid($nl['id'])) {
			$Query ="UPDATE ".TM_TABLE_NL."
						SET 
						subject='".dbesc($nl["subject"])."',
						title='".dbesc($nl["title"])."',
						title_sub='".dbesc($nl["title_sub"])."',
						updated='".dbesc($nl["created"])."',
						editor='".dbesc($nl["author"])."',
						body='".dbesc($nl["body"])."',
						body_text='".dbesc($nl["body_text"])."',
						body_head='".dbesc($nl["body_head"])."',
						body_foot='".dbesc($nl["body_foot"])."',
						summary='".dbesc($nl["summary"])."',
						aktiv=".checkset_int($nl["aktiv"]).",
						track_image='".dbesc($nl["track_image"])."',
						track_personalized=".checkset_int($nl["track_personalized"]).",
						use_inline_images=".checkset_int($nl["use_inline_images"]).",
						massmail=".checkset_int($nl["massmail"]).",
						link='".dbesc($nl["link"])."',
						content_type='".dbesc($nl["content_type"])."',
						grp_id=".checkset_int($nl["grp_id"]).",
						rcpt_name='".dbesc($nl["rcpt_name"])."',
						is_template=".checkset_int($nl["is_template"])."
						WHERE siteid='".TM_SITEID."'
						AND id=".checkset_int($nl["id"]);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$nl,"object"=>"nl","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}//if query
		}
		//add attachements
		$this->addAttm($nl['id'],$nl['attachements']);
		return $Return;
	}//updateNL

	function delNL($id) {
		$Return=false;
		if (check_dbid($id)) {
			//log before deletion, logging class will fetch old data!			
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"nl","action"=>"delete"));
			//versandliste, history h loeschen
			//ok historie loeschen!
			$Query ="DELETE FROM ".TM_TABLE_NL_H."
						WHERE siteid='".TM_SITEID."'
						AND nl_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			//q loeschen
			$Query ="DELETE FROM ".TM_TABLE_NL_Q."
						WHERE siteid='".TM_SITEID."'
						AND nl_id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//attachements loeschen
			$Query ="DELETE FROM ".TM_TABLE_NL_ATTM."
						WHERE siteid='".TM_SITEID."'
						AND nl_id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			//nl loecshen
			$Query ="DELETE FROM ".TM_TABLE_NL."
						WHERE siteid='".TM_SITEID."'
						AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}//delNL

	function setAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL."
						SET aktiv=".checkset_int($aktiv)."
						WHERE id=".checkset_int($id)."
						AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"nl","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}//if query
		}
		return $Return;
	}//setAktiv

	function setAsTemplate($id=0,$is_template=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL."
						SET is_template=".checkset_int($is_template)."
						WHERE id=".checkset_int($id)."
						AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("template"=>$is_template,"id"=>$id),"object"=>"nl","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}//if query
		}
		return $Return;
	}//setAktiv


	function setStatus($id,$status) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL." SET status=".checkset_int($status)."
						 WHERE siteid='".TM_SITEID."' AND id=".checkset_int($id);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("status"=>$status,"id"=>$id),"object"=>"nl","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
		}
		return $Return;
	}

	function addClick($nl_id) {
		$Return=false;
		if (check_dbid($nl_id)) {
			$Query ="UPDATE ".TM_TABLE_NL."
						SET clicks=clicks+1
						WHERE siteid='".TM_SITEID."'
						AND id=".checkset_int($nl_id);
			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}//if query
		}
		return $Return;
	}//addClick

	function addView($nl_id) {
		$Return=false;
		if (check_dbid($nl_id)) {
			$Query ="UPDATE ".TM_TABLE_NL."
						SET views=views+1
						WHERE siteid='".TM_SITEID."'
						AND id=".checkset_int($nl_id);

			if ($this->DB->Query($Query)) {
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}//if query
		}
		return $Return;
	}//addView


	function countNL($group_id=0,$search=Array()) {
		$count=0;
		$this->NL=Array();
		//use this->DB2 !!!!!
		$Query ="
						SELECT count(id) as c
						FROM ".TM_TABLE_NL."
						WHERE siteid='".TM_SITEID."'
					";
		if (check_dbid($group_id)) {
			$Query .=" AND grp_id=".checkset_int($group_id)." ";
		}
		if (isset($search['aktiv'])) {
			$Query .= " AND aktiv=".checkset_int($search['aktiv'])." ";
		}
		//check for status, OR
		if (isset($search['status'])) {
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
				$Query .= " status=".checkset_int($search['status'][$sscc]);
				if (($sscc+1)<$ssc) $Query.=" OR";
			}
			$Query .= " )";
		}		
		if (isset($search['is_template'])) {
			$Query .= " AND is_template=".checkset_int($search['is_template'])." ";
		}
		$this->DB2->Query($Query);
		if ($this->DB2->next_record()) {
			$count=$this->DB2->Record['c'];
		}//if next record
		return $count;
	}//counNL

	function getNLID($group_id=0) {
		$this->NL=Array();
		$Query ="	SELECT id
							FROM ".TM_TABLE_NL."
							WHERE siteid='".TM_SITEID."'
						";
			if (check_dbid($group_id)) {
				$Query .=" AND grp_id=".checkset_int($group_id)."
							";
			}
			$this->DB->Query($Query);
			$ac=0;
			while ($this->DB->next_record()) {
				$this->NL[$ac]['id']=$this->DB->Record['id'];
				$ac++;
			}
		return $this->NL;
	}//getNLID

	function copyNL($id,$copyfiles=1) {
		global $tm_nlpath, $tm_nlimgpath, $tm_nlattachpath, $LOGIN;//, $TM_SITEID
		$Return=false;
		if (check_dbid($id)) {
			$created=date("Y-m-d H:i:s");
			$author=$LOGIN->USER['name'];
			$status=1;
			$NL=$this->getNL($id,0,0,0,1);
			//make a copy
			$NL_copy=$NL[0];
			//change some values
			$NL_copy['subject']="COPY OF ".$NL[0]["subject"];
			$NL_copy['created']=$created;
			$NL_copy['author']=$author;
			$NL_copy['status']=$status;
			$NL_copy['views']=0;
			$NL_copy['clicks']=0;
			//explizit kein template!			
			$NL_copy['is_template']=0;
			//copy attachement references
			//array umwandeln, der array aus get sieht anders aus als der fuer update und new!!!
			$atc=0;
			$attachements_new=Array();
			$attachements=$NL[0]['attachements'];
			foreach ($attachements as $attachfile) {
				$attachements_new[$atc]=$attachfile['file'];
				$atc++;
			}
			$NL_copy['attachements']=$attachements_new;

			//add new NL
			$Return=$this->addNL($NL_copy);
			//thats it
				if ($Return && $copyfiles==1) {
					//trackimage braucht nicht kopiert zu werden da eigen-name
					//bild
					$NL_Imagefile1=$tm_nlimgpath."/nl_".date_convert_to_string($NL[0]['created'])."_1.jpg";
					$NL_Imagefile1_New=$tm_nlimgpath."/nl_".date_convert_to_string($created)."_1.jpg";
					if (file_exists($NL_Imagefile1)) {
						copy($NL_Imagefile1,$NL_Imagefile1_New);
					}
					//html datei
					$NL_File=$tm_nlpath."/nl_".date_convert_to_string($NL[0]['created']).".html";
					$NL_File_New=$tm_nlpath."/nl_".date_convert_to_string($created).".html";
					if (file_exists($NL_File)) {
						copy($NL_File,$NL_File_New);
					}
					//template, text version
					$NL_File_N=$tm_nlpath."/nl_".date_convert_to_string($NL[0]['created'])."_t.txt";
					$NL_File_N_New=$tm_nlpath."/nl_".date_convert_to_string($created)."_t.txt";
					if (file_exists($NL_File_N)) {
						copy($NL_File_N,$NL_File_N_New);
					}
					//template, html version
					$NL_File_N=$tm_nlpath."/nl_".date_convert_to_string($NL[0]['created'])."_n.html";
					$NL_File_N_New=$tm_nlpath."/nl_".date_convert_to_string($created)."_n.html";
					if (file_exists($NL_File_N)) {
						copy($NL_File_N,$NL_File_N_New);
					}
					//geparste version
					$NL_File_P=$tm_nlpath."/nl_".date_convert_to_string($NL[0]['created'])."_p.html";
					$NL_File_P_New=$tm_nlpath."/nl_".date_convert_to_string($created)."_p.html";
					if (file_exists($NL_File_P)) {
						copy($NL_File_P,$NL_File_P_New);
					}
				}//if return && copyfiles
		}
		return $Return;
	}//copyNL

//GROUP FUNCTIONS

	function getGroup($id=0,$nl_id=0,$count=0) {
		$this->GRP=Array();
		$Query ="
			SELECT ".TM_TABLE_NL_GRP.".id, ".TM_TABLE_NL_GRP.".name, ".TM_TABLE_NL_GRP.".descr, ".TM_TABLE_NL_GRP.".aktiv, ".TM_TABLE_NL_GRP.".standard,
			".TM_TABLE_NL_GRP.".author,
			".TM_TABLE_NL_GRP.".editor,
			".TM_TABLE_NL_GRP.".created,
			".TM_TABLE_NL_GRP.".updated,
			".TM_TABLE_NL_GRP.".siteid
			FROM ".TM_TABLE_NL_GRP."
			WHERE ".TM_TABLE_NL_GRP.".siteid='".TM_SITEID."'
			";
		if (check_dbid($id)) {
			$Query .= " AND ".TM_TABLE_NL_GRP.".id=".checkset_int($id);
		}
		if (check_dbid($nl_id)) {
			$Query ="";
			$Query .= "
				SELECT DISTINCT ".TM_TABLE_NL_GRP.".id, ".TM_TABLE_NL_GRP.".name, ".TM_TABLE_NL_GRP.".descr, ".TM_TABLE_NL_GRP.".aktiv, ".TM_TABLE_NL_GRP.".standard,
				".TM_TABLE_NL_GRP.".author,
				".TM_TABLE_NL_GRP.".editor,
				".TM_TABLE_NL_GRP.".created,
				".TM_TABLE_NL_GRP.".updated
				FROM ".TM_TABLE_NL_GRP.", ".TM_TABLE_NL."
				WHERE ".TM_TABLE_NL_GRP.".id=".TM_TABLE_NL.".grp_id
				AND ".TM_TABLE_NL_GRP.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_NL.".siteid='".TM_SITEID."'
				AND ".TM_TABLE_NL.".id=".checkset_int($nl_id)."
			";
		}
		$Query .= "	ORDER BY ".TM_TABLE_NL_GRP.".name";
		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->GRP[$ac]['id']=$this->DB->Record['id'];
			$this->GRP[$ac]['name']=$this->DB->Record['name'];
			$this->GRP[$ac]['descr']=$this->DB->Record['descr'];
			$this->GRP[$ac]['aktiv']=$this->DB->Record['aktiv'];
			$this->GRP[$ac]['standard']=$this->DB->Record['standard'];
			$this->GRP[$ac]['author']=$this->DB->Record['author'];
			$this->GRP[$ac]['editor']=$this->DB->Record['editor'];
			$this->GRP[$ac]['created']=$this->DB->Record['created'];
			$this->GRP[$ac]['updated']=$this->DB->Record['updated'];
			if ($count==1) {
				$this->GRP[$ac]['nl_count']=$this->countNL($this->GRP[$ac]['id']);
			}
			$this->GRP[$ac]['siteid']=$this->DB->Record['siteid'];
			$ac++;
		}
		return $this->GRP;
	}//getGroup

	function getGroupID() {
		$this->NL=Array();
		$Query ="
						SELECT id
						FROM ".TM_TABLE_NL_GRP."
						WHERE siteid='".TM_SITEID."'
					";
		$this->DB->Query($Query);
		$ac=0;
		while ($this->DB->next_record()) {
			$this->NL[$ac]['id']=$DB->Record['id'];
			$ac++;
		}
		return $this->NL;
	}//getGroupID



	function setGrpAktiv($id=0,$aktiv=1) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_GRP."
						SET aktiv=".checkset_int($aktiv)."
						WHERE id=".checkset_int($id)."
						AND siteid='".TM_SITEID."'";
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("aktiv"=>$aktiv,"id"=>$id),"object"=>"nl_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//setGrpAktiv

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
	
	function setGrpStd($id=0) {
		$Return=false;
		if (check_dbid($id)) {
			$Query ="UPDATE ".TM_TABLE_NL_GRP."
						SET standard=0
						WHERE standard=1
						AND siteid='".TM_SITEID."'";
			//log: fetch old stdgroup before query!
			$GRPSTD=$this->getGroupStd();
			//do query, set standard to 0
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>0,"id"=>$GRPSTD['id']),"object"=>"nl_grp","action"=>"edit"));
				$Return=true;
			} else {
				$Return=false;
				return $Return;
			}
			$Query ="UPDATE ".TM_TABLE_NL_GRP."
						SET standard=1
						WHERE id=".checkset_int($id)."
						AND siteid='".TM_SITEID."'";
			//do query, set dstandard to 1 for selected
			if ($this->DB->Query($Query)) {
				//log
				if (TM_LOG) $this->LOG->log(Array("data"=>Array("standard"=>1,"id"=>$id),"object"=>"nl_grp","action"=>"edit"));
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
		$Query ="INSERT INTO ".TM_TABLE_NL_GRP."
					(
					name,descr,
					aktiv,standard,
					created,author,
					updated,editor,
					siteid
					)
					VALUES
					(
					'".dbesc($group["name"])."', '".dbesc($group["descr"])."',
					".checkset_int($group["aktiv"]).", 0,
					'".dbesc($group["created"])."', '".dbesc($group["author"])."',
					'".dbesc($group["created"])."', '".dbesc($group["author"])."',
					 '".TM_SITEID."'
					)";
		if ($this->DB->Query($Query)) {
			//log
			$group['id']=$this->DB->LastInsertID;
			if (TM_LOG) $this->LOG->log(Array("data"=>$group,"object"=>"nl_grp","action"=>"new"));
			#$Return=true;
			$Return=$group['id'];
		}
		return $Return;
	}//addGrp

	function updateGrp($group) {
		$Return=false;
		if (check_dbid($group['id'])) {
			$Query ="UPDATE ".TM_TABLE_NL_GRP."
						SET name='".dbesc($group["name"])."',
						descr='".dbesc($group["descr"])."',
						aktiv=".checkset_int($group["aktiv"]).",
						updated='".dbesc($group["created"])."',
						editor='".dbesc($group["author"])."'
						WHERE siteid='".TM_SITEID."'
						AND id=".checkset_int($group["id"]);
			if ($this->DB->Query($Query)) {
				if (TM_LOG) $this->LOG->log(Array("data"=>$group,"object"=>"nl_grp","action"=>"edit"));
				$Return=true;
			}
		}
		return $Return;
	}//updateGrp

	function delGrp($id) {
		$Return=false;
		if (check_dbid($id)) {
			//log before deletion! log will fetch old data
			if (TM_LOG) $this->LOG->log(Array("data"=>Array("id"=>$id),"object"=>"nl_grp","action"=>"delete"));
			//standard gruppe suchen
			$Query ="SELECT id
					FROM ".TM_TABLE_NL_GRP."
					WHERE siteid='".TM_SITEID."'
					AND standard=1";
			if ($this->DB->Query($Query)) {
				$Return=true;
			}
			//wenn standardgruppe gefunden, weitermachen, ansonsten nichts tun!!!
			//loeschen geht nur wenn eine std gruppe definiert
			// wurde welche die NL aus zu loeschender Gruppe zugeordnet werden koennen
			if ($this->DB->next_record()) {
				$stdGrpID=$this->DB->Record['id'];
				//newsletter der stdgruppe neu zuordnen
				$Query ="UPDATE ".TM_TABLE_NL." SET
							grp_id=".checkset_int($stdGrpID)."
							WHERE siteid='".TM_SITEID."'
							AND grp_id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
				//gruppe loeschen
				$Query ="DELETE FROM ".TM_TABLE_NL_GRP."
							WHERE siteid='".TM_SITEID."'
							AND id=".checkset_int($id);
				if ($this->DB->Query($Query)) {
					$Return=true;
				} else {
					$Return=false;
					return $Return;
				}
			} else {
				$Return=false;
			}
		}
		return $Return;
	}//delGrp

	function convertNL2Text($html,$type) {
		global $encoding;
		$text=$html;
		if ($type=="html" || $type=="text/html") {
			#$text=str_replace("<br>","\n", $text);
			#$text=strip_htmltags($text);
			#$text=strip_tags($text);
			$htmlToText=new Html2Text($html, 80);//class has apache license, may be in conflict with gpl???
		    #$text=htmlspecialchars_decode($text);//php5 only
		    #$text=html_entity_decode($text);
		    $text=$htmlToText->convert();
	   		#$text=strip_tags($text);
		    #$text=html_entity_decode($text,ENT_NOQUOTES,$encoding);
			$text = preg_replace('~<[^>]+>~', '', $text); // remove any HTML tags that are still left
			#$text=str_replace("&quot;","'",$text);
		}
		return $text;
	}//convertNL2Text


/********************************************************************************************/
/********************************************************************************************/
/********************************************************************************************/

	function parseSubject($data) {

		#['date'], ['text'], ['adr']=Array('email','code', 'f0'-'f9')
		if (!isset($data['adr'])) {
			$data['adr']=Array('email'=>"",'code'=>"",'f0'=>"",'f1'=>"",'f2'=>"",'f3'=>"",'f4'=>"",'f5'=>"",'f6'=>"",'f7'=>"",'f8'=>"",'f9'=>"");		
		}
		if (!isset($data['date'])) {
			#$data['date']=date(TM_NL_DATEFORMAT);
			//datum aus q send_at nehmen!
			//parse date
			//if valid q id, then use send_at date! and convert to format for nl
			//if not valid q_id given, use now
			if (isset($data['q']['id']) && check_dbid($data['q']['id'])) {	
				$QUEUE=new tm_Q();
				$Q=$QUEUE->getQ($data['q']['id']);
			}
			if (isset($Q[0])) {
				if (tm_DEBUG()) $this->parseLog[]= "Q[0] set, use q send_at date";
				$data['date']=strftime(TM_NL_DATEFORMAT,mk_microtime($Q[0]['send_at']));//"Q_send_at id ".$data['q']['id'].": ".
			} else {
				#$data['date']=date(TM_NL_DATEFORMAT);
				if (tm_DEBUG()) $this->parseLog[]= "Q[0] is NOT set, use servertime";
				$data['date']=strftime(TM_NL_DATEFORMAT,mk_microtime(date("Y-m-d H:i:s")));//"Q_send_at id ".$data['q']['id'].": ".
			}
			if (tm_DEBUG()) $this->parseLog[]= "date: ".$data['date'];
		}
		if (!isset($data['text'])) {
			$data['text']="";
		}
		$search = array("{F0}","{F1}","{F2}","{F3}","{F4}","{F5}","{F6}","{F7}","{F8}","{F9}","{EMAIL}","{DATE}","{CODE}","{TM_APPNAME}", "{TM_VERSION}","{TM_APPDESC}","{TM_APPURL}","{TM_APPTEXT}","{TM_DISCLAIMER}");
		$replace = array($data['adr']['f0'], $data['adr']['f1'], $data['adr']['f2'], $data['adr']['f3'], $data['adr']['f4'], $data['adr']['f5'], $data['adr']['f6'], $data['adr']['f7'], $data['adr']['f8'], $data['adr']['f9'],$data['adr']['email'],$data['date'],$data['adr']['code'],TM_APPNAME, TM_VERSION,TM_APPDESC,TM_APPURL,TM_APPTEXT,TM_DISCLAIMER);
		$subject= str_replace($search, $replace, $data['text']);
		return $subject;
	}
	
	function parseRcptName($data=Array()) {
		return $this->parseSubject($data);
	}

	function parseHeader($data=Array()) {
		return $this->parseSubject($data);
	}

	//now we have a parsing function that does all the weird stuff...., added in 1088
	function parseNL($data,$type,$values=Array()) {
		//$data=Array( nl => $NL(Array) , adr => $ADR(Array) )
		//e.g. pass NL[0] as $data['nl']
		//e.g. pass ADR[0] as $data['adr']
		
		//values => Array(), set values for e.g. IMAGE1 and other vars , if coded, so far only image1 is used for inline images, testcode
		//2do: add more vars to set values on!
		//these values dont get parsed and just replaced by the given value, whch indeed could also be dynamically ... but set from external		
		//$values can be empty.
		//we explicitely check if a key exists! maybe loop through keys.... hmmm, lets see
		
		//ouch, another global
		global $tm_URL_FE;//should become a constant		
		global $tm_nldir,$tm_nlattachdir,$tm_nlimgdir,$tm_nlimgpath,$tm_nlpath;//should become a constant too

		if (tm_DEBUG()) $this->parseLog[]= "parseNL: start timer";
		if (tm_DEBUG())  $T=new Timer();//zeitmessung
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: start:".$T->MidResult()."";
		
		$this->parseLog=Array();
		$AGroups=Array();//groups the adr belongs to
		$Return="";
		$nl_id=0;
		$a_id=0;
		$q_id=0;
		$h_id=0;
		$frm_id=0;

		$email="";
		$code="";
		$memo="";
		$f0=$f1=$f2=$f3=$f4=$f5=$f6=$f7=$f8=$f9="";

		#$personalized=false;

		if (isset($data['nl']) && isset($data['nl']['id']) && check_dbid($data['nl']['id']) ) {
			$nl_id=$data['nl']['id'];
			//we can assume that all in ['nl']is set
		}
		//at first we need a nl_id, if not set, exit and return empty string!
		if (!check_dbid($nl_id)) {
			$Return="!nl_id";
			return $Return;
		}
		//next we need to know the type, parse html or testpart? if not set, exit and return empty string!
		if ($type != "text" && $type != "html") {
			$Return="!type";
			return $Return;		
		}

		$data['text']=$data['nl']['subject'];
		
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: prepare:".$T->MidResult()."";
		$NLSUBJECT=$this->parseSubject($data);
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: parse subject:".$T->MidResult()."";
		
		//if isset $data['adr'] we assume that the newsletter is personalized and need personalized parsing with all parameters and variables, unles personalized tracking is disabled, then do not track h_id and adr_id 
		if (isset($data['adr']) && isset($data['adr']['id']) && check_dbid($data['adr']['id']) ) {
			$ADDRESS=new tm_ADR();
			#$personalized=true;
			$a_id=$data['adr']['id'];
			$email=$data['adr']['email'];
			$code=$data['adr']['code'];
			$memo=$data['adr']['memo'];
			$f0=$data['adr']['f0'];
			$f1=$data['adr']['f1'];
			$f2=$data['adr']['f2'];
			$f3=$data['adr']['f3'];
			$f4=$data['adr']['f4'];
			$f5=$data['adr']['f5'];
			$f6=$data['adr']['f6'];
			$f7=$data['adr']['f7'];
			$f8=$data['adr']['f8'];
			$f9=$data['adr']['f9'];

			$AGroups=$ADDRESS->getGroup(0,$a_id,$frm_id,0,Array("aktiv"=>1,"public"=>1));//fetch only public groups! dont show internal groups, "public_frm_ref"=>1, to show only pub groups with ref to form!
			if (tm_DEBUG()) $this->parseLog[]= "parseNL: get groups:".$T->MidResult()."";		

		}
		
		
		
		if (isset($data['h']) && isset($data['h']['id']) && check_dbid($data['h']['id']) ) {
			$h_id=$data['h']['id'];
		}

		//parse date
		//if valid q id, then use send_at date! and convert to format for nl
		//if not valid q_id given, use now

		if (isset($data['q']) && isset($data['q']['id']) && check_dbid($data['q']['id']) ) {	
			$QUEUE=new tm_Q();
			$Q=$QUEUE->getQ($data['q']['id']);
			$q_id=$data['q']['id'];//$Q[0]['id'];
			if (tm_DEBUG()) $this->parseLog[]= "parseNL: get q:".$T->MidResult()."";
		}
		if (isset($Q[0])) {
			$data['date']=strftime(TM_NL_DATEFORMAT,mk_microtime($Q[0]['send_at']));//"Q_send_at id ".$data['q']['id'].": ".
		} else {
			$data['date']=date(TM_NL_DATEFORMAT);
		}
		$DATE=$data['date'];
			
		//filenames
		//html datei//template for html parts
		$NL_Filename_N="nl_".date_convert_to_string($data['nl']['created'])."_n.html";
		//text datei//template for textparts
		$NL_Filename_T="nl_".date_convert_to_string($data['nl']['created'])."_t.txt";
		//image1
		//attention: static code, 
		//same static code for image1 is used in send_it_q_run to make inline image part (search for image src)!
		//so if we use inline images and create parts before parsing nl body,  image1 is passed to parseNL as static value.
		// $data['nl']['values']=array("IMAGE1","something") replaces tm var {IMAGE1} with given value, and is not parsed dynamically
		//maybe ->get_var_value($data,"IMAGE1"); fuer tm_NL Klasse
		$NL_Imagename1="nl_".date_convert_to_string($data['nl']['created'])."_1.jpg";

		//view nl online
		//use view.php (1088)
		if ($data['nl']['massmail']!=1) {
			if ($type=='text') {
				$NLONLINE_URL=$tm_URL_FE."/view.php?1=1&nl_id=".$nl_id."&q_id=".$q_id."&a_id=".$a_id."&h_id=".$h_id;
			}
			if ($type=='html') {
				$NLONLINE_URL=$tm_URL_FE."/view.php?1=1&amp;nl_id=".$nl_id."&amp;q_id=".$q_id."&amp;a_id=".$a_id."&amp;h_id=".$h_id;
			}
		} else {
			if ($type=='text') {
				$NLONLINE_URL=$tm_URL_FE."/view.php?1=1&nl_id=".$nl_id."&q_id=".$q_id;
			}
			if ($type=='html') {	
				$NLONLINE_URL=$tm_URL_FE."/view.php?1=1&amp;nl_id=".$nl_id."&amp;q_id=".$q_id;
			}
		}
		$NLONLINE="<a href=\"".$NLONLINE_URL."\" target=\"_blank\">";

		//template values
		$IMAGE1="";
		$IMAGE1_URL="";
		$LINK1="";
		$LINK1_URL="";
		$ATTACHEMENTS="";
		$ATTACHEMENTS_TEXT="";
		$GROUP="";
		foreach ($AGroups as $AGroup) {
			$GROUP .= display($AGroup['name'])."<br>";
		}

		//IMAGE1
		if (file_exists($tm_nlimgpath."/".$NL_Imagename1)) {
			#send_log("NL Image:".$tm_URL_FE."/".$tm_nlimgdir."/".$NL_Imagename1);
			$this->parseLog[]="NL Image:".$tm_URL_FE."/".$tm_nlimgdir."/".$NL_Imagename1;
			$IMAGE1_URL=$tm_URL_FE."/".$tm_nlimgdir."/".$NL_Imagename1;
			$IMAGE1="<img src=\"".$IMAGE1_URL."\" border=0 alt=\"Image1\" id=\"tm_Image1\">";
		}

		//Attachements!
		$attachements=$data['nl']['attachements'];
		$atc=count($attachements);
		if ($atc>0) {
			foreach ($attachements as $attachfile) {
				$ATTACHEMENTS.= "<a href=\"".$tm_URL_FE."/".$tm_nlattachdir."/".$attachfile['file']."\" target=\"_blank\" title=\"".$attachfile['file']."\">";
				$ATTACHEMENTS.=$attachfile['file'];
				$ATTACHEMENTS.= "</a><br>";
				$ATTACHEMENTS_TEXT.=$attachfile['file'].": ".$tm_URL_FE."/".$tm_nlattachdir."/".$attachfile['file'];
				$ATTACHEMENTS_TEXT.= "";
			}//foreach
		}//if count/atc

		//Blindimage
		if ($data['nl']['track_personalized']==1) {
			$BLINDIMAGE_URL=$tm_URL_FE."/news_blank.png.php?nl_id=".$nl_id."&amp;q_id=".$q_id."&amp;a_id=".$a_id."&amp;h_id=".$h_id;
		} else {
			//tracking nicht personalisiert, wie massmail!
			//koennte auch ggf oben global gesetzt werden, hier doppelt!
			$BLINDIMAGE_URL=$tm_URL_FE."/news_blank.png.php?nl_id=".$nl_id."&amp;q_id=".$q_id;
		}
		$BLINDIMAGE="<img src=\"".$BLINDIMAGE_URL."\" border=0 alt=\"\">";//no alt!
		#send_log("NL track personalized: ".$data['nl']['track_personalized']);
		$this->parseLog[]="NL track personalized: ".$data['nl']['track_personalized'];
		#send_log("Blindimage: ".$BLINDIMAGE_URL);
		$this->parseLog[]="Blindimage: ".$BLINDIMAGE_URL;
		
		//link to unsubscribe
		$UNSUBSCRIBE_URL=$tm_URL_FE."/unsubscribe.php?nl_id=".$nl_id."&amp;q_id=".$q_id."&amp;a_id=".$a_id."&amp;h_id=".$h_id."&amp;code=".$code;
		$UNSUBSCRIBE="<a href=\"".$UNSUBSCRIBE_URL."\" target=\"_blank\">";
		
		//subscribe link for touch optin or subscribe
		$SUBSCRIBE_URL=$tm_URL_FE."/subscribe.php?doptin=1&amp;email=".$email."&amp;code=".$code;//."&amp;touch=1"
		//optional fid form id parameter! for optin mails etc
		//check if we have a valid form id, used in subscribe url e.g. for doptin mails!
		if (isset($data['frm']) && isset($data['frm']['id']) && check_dbid($data['frm']['id']) ) {
			//add frm_id of form, needed to send subscribe mail and get greeting nl id!
			$SUBSCRIBE_URL.="&amp;fid=".$data['frm']['id'];
		}
		$SUBSCRIBE="<a href=\"".$SUBSCRIBE_URL."\" target=\"_blank\">";

		#send_log("Unsubscribe: ".$UNSUBSCRIBE_URL);
		$this->parseLog[]="Unsubscribe: ".$UNSUBSCRIBE_URL;
		#send_log("Subscribe (touch/double optin): ".$SUBSCRIBE_URL);
		$this->parseLog[]="Subscribe (touch/double optin): ".$SUBSCRIBE_URL;
		
		if (!empty($data['nl']['link'])) {
			if ($data['nl']['track_personalized']==1) {
				$LINK1_URL=$tm_URL_FE."/click.php?nl_id=".$nl_id."&amp;q_id=".$q_id."&amp;a_id=".$a_id."&amp;h_id=".$h_id;
			} else {
				$LINK1_URL=$tm_URL_FE."/click.php?nl_id=".$nl_id."&amp;q_id=".$q_id;
			}
		}
		$LINK1="<a href=\"".$LINK1_URL."\" target=\"_blank\">";
		#send_log("Link1: ".$LINK1_URL);
		$this->parseLog[]="Link1: ".$LINK1_URL;

		//set template vars		
		#send_log("parse Template - Massmailing");
		$this->parseLog[]="parse Template";
		
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: new tpl:".$T->MidResult()."";

		$_Tpl_NL=new tm_Template();
		#$_Tpl_NL->setTemplatePath($tm_nlpath);//no need to set path, we now use body from data['nl']!
		//set parse values that get parsed in html and text version!	
		
		//for setting values from external call, we check for existing key in $values
		if (array_key_exists("IMAGE1",$values)) {
			$this->parseLog[]="new value for IMAGE1 found, replacing with new value: ".display($values['IMAGE1']); 
			$_Tpl_NL->setParseValue("IMAGE1", $values['IMAGE1']);//e.g. used in send_it routine to set inline image src
		} else {
			$this->parseLog[]="use parsed value for IMAGE1";
			$_Tpl_NL->setParseValue("IMAGE1", $IMAGE1);
		}
		$_Tpl_NL->setParseValue("LINK1", $LINK1);
		$_Tpl_NL->setParseValue("ATTACH1", "");
		$_Tpl_NL->setParseValue("CLOSELINK", "</a>");
		$_Tpl_NL->setParseValue("BLINDIMAGE", $BLINDIMAGE);
		$_Tpl_NL->setParseValue("UNSUBSCRIBE", $UNSUBSCRIBE);
		$_Tpl_NL->setParseValue("SUBSCRIBE", $SUBSCRIBE);
		$_Tpl_NL->setParseValue("NLONLINE", $NLONLINE);
		$_Tpl_NL->setParseValue("IMAGE1_URL", $IMAGE1_URL);
		$_Tpl_NL->setParseValue("LINK1_URL", $LINK1_URL);
		$_Tpl_NL->setParseValue("ATTACH1_URL", "");
		$_Tpl_NL->setParseValue("NLONLINE_URL", $NLONLINE_URL);
		$_Tpl_NL->setParseValue("BLINDIMAGE_URL", $BLINDIMAGE_URL);
		$_Tpl_NL->setParseValue("UNSUBSCRIBE_URL", $UNSUBSCRIBE_URL);
		$_Tpl_NL->setParseValue("SUBSCRIBE_URL", $SUBSCRIBE_URL);
		$_Tpl_NL->setParseValue("DATE", $DATE);
		$_Tpl_NL->setParseValue("EMAIL",$email);
		$_Tpl_NL->setParseValue("CODE",$code);
		$_Tpl_NL->setParseValue("F0",$f0);
		$_Tpl_NL->setParseValue("F1",$f1);
		$_Tpl_NL->setParseValue("F2",$f2);
		$_Tpl_NL->setParseValue("F3",$f3);
		$_Tpl_NL->setParseValue("F4",$f4);
		$_Tpl_NL->setParseValue("F5",$f5);
		$_Tpl_NL->setParseValue("F6",$f6);
		$_Tpl_NL->setParseValue("F7",$f7);
		$_Tpl_NL->setParseValue("F8",$f8);
		$_Tpl_NL->setParseValue("F9",$f9);
		$_Tpl_NL->setParseValue("MEMO",$memo);
		$_Tpl_NL->setParseValue("TITLE",$data['nl']['title']);
		$_Tpl_NL->setParseValue("TITLE_SUB",$data['nl']['title_sub']);
		$_Tpl_NL->setParseValue("SUMMARY",$data['nl']['summary']);			
		$_Tpl_NL->setParseValue("GROUP",$GROUP);
		$_Tpl_NL->setParseValue("SUBJECT",$NLSUBJECT);
		$_Tpl_NL->setParseValue("TM_VERSION",TM_VERSION);
		$_Tpl_NL->setParseValue("TM_APPNAME",TM_APPNAME);
		$_Tpl_NL->setParseValue("TM_APPDESC",TM_APPDESC);
		$_Tpl_NL->setParseValue("TM_APPURL",TM_APPURL);
		$_Tpl_NL->setParseValue("TM_APPTEXT",TM_APPTEXT);
		$_Tpl_NL->setParseValue("TM_DISCLAIMER",TM_DISCLAIMER);

		if (tm_DEBUG()) $this->parseLog[]= "type: ".$type."";
		//add htmlpart! 
		if ($type=="html") {
		#if ($data['nl']['content_type']=="html" || $data['nl']['content_type']=="text/html") {
			#send_log("render HTML Template: ".$NL_Filename_N);
			if (tm_DEBUG()) $this->parseLog[]= "render HTML Part";#.$NL_Filename_N."";
			$this->parseLog[]="render HTML Part";#.$NL_Filename_N;
			//attachements html code
			$_Tpl_NL->setParseValue("ATTACHEMENTS", $ATTACHEMENTS);
			//Template rendern und body zusammenbauen
			//create header:
			//1st parse header:
			if (!empty($data['nl']['body_head'])) {
				if (tm_DEBUG()) $this->parseLog[]= "body_head not empty";
				$HTML_Head= $this->parseHeader(array("text"=>$data['nl']['body_head']));
			} else {
				if (tm_DEBUG()) $this->parseLog[]= "body_head empty, add default";
				$HTML_Head= $this->parseHeader(array("text"=>TM_NL_HTML_START));
			}			
			if (!empty($data['nl']['body_foot'])) {
				if (tm_DEBUG()) $this->parseLog[]= "body_foot not empty";
				$HTML_Foot= $this->parseHeader(array("text"=>$data['nl']['body_foot']));
			} else {
				if (tm_DEBUG()) $this->parseLog[]= "body_foot empty, add default";
				$HTML_Foot= $this->parseHeader(array("text"=>TM_NL_HTML_END));
			}			
			//replacement array
			$HTML_search = array("{TITLE}","{TITLE_SUB}","{SUBJECT}");
			$HTML_replace = array(display($data['nl']['title']),display($data['nl']['title_sub']),$NLSUBJECT);
			//replace nl vars, title subttle, subject
			$HTML_Head= str_replace($HTML_search, $HTML_replace, $HTML_Head);
			$HTML_Foot= str_replace($HTML_search, $HTML_replace, $HTML_Foot);	
			//tpl file no more used! use renderContent of TPL Class instead, data['nl']		
			#$Return=$HTML_Head.$_Tpl_NL->renderTemplate($NL_Filename_N).$HTML_Foot;
			$Return=$HTML_Head.$_Tpl_NL->renderContent($data['nl']['body']).$HTML_Foot;
		}
		//add textpart! 
		//use body_text, if body_text is empty or "" or so, convert body to text, this is a fallback, the converting is broken due to wysiwyg and reconverting of e.g. german umlauts to html entitites :O
		if ($type=="text") {
		#if ($data['nl']['content_type']=="text" || $data['nl']['content_type']=="text/html") {
			#if (!empty($NL[0]['body_text']) && $NL[0]['body_text']!="") {
				//attachements text code
				$_Tpl_NL->setParseValue("ATTACHEMENTS", $ATTACHEMENTS_TEXT);
				#$NLBODY_TEXT=$NL[0]['body_text'];
				#send_log("render Text Template: ".$NL_Filename_T);
				if (tm_DEBUG()) $this->parseLog[]= "render Text Part";#.$NL_Filename_T."";
				$this->parseLog[]="render Text Part";#.$NL_Filename_T;
				//tpl file no more used! use renderContent of TPL Class instead, data['nl']		
				#$Return=$_Tpl_NL->renderTemplate($NL_Filename_T);//text!
				$Return=$_Tpl_NL->renderContent($data['nl']['body_text']);
			#} else {
			#	$NLBODY_TEXT=$NEWSLETTER->convertNL2Text($NLBODY,$NL[0]['content_type']);
			#}
		}//if text text/html

		//finally parse links and modify $Return
		$LINK=new tm_LNK();
		$LINK->parseLog=Array();//init log array		
		//filter for linkparsing, if text then text, else "" for html version of parsed links
		$filter="";
		if ($type=="text") {
			$filter=$type;
		}
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: start parse links:".$T->MidResult()."";		
		
		if ($data['nl']['track_personalized']==1) {			
			$Return=$LINK->parseLinks($Return,$filter,Array("nl_id"=>$nl_id,"q_id"=>$q_id,"a_id"=>$a_id,"h_id"=>$h_id));
		} else {
			$Return=$LINK->parseLinks($Return,$filter,Array("nl_id"=>$nl_id,"q_id"=>$q_id));
		}
		//merge logs!
		$this->parseLog=array_merge($this->parseLog,$LINK->parseLog);		
		//return string, later on we will return array [0] is text and [1] is log, containing all logmessages as array
		#$Return[0]=$parsedNL Array (subject,body);
		#$Return[1]=$Log;
		
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: parse links:".$T->MidResult()."";		
		
		if (tm_DEBUG()) $this->parseLog[]= "parseNL: done:".$T->MidResult()."";

		return $Return;
	}

} //class
?>