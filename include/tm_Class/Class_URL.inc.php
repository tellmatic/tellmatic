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

class tm_URL {
	var $Params;
	var $URLName;

	function addStdParam($Para,$Value) {
		if ($this->Params[$Para]=$Value) {
			return true;
		} else {
			return false;
		}
	}
	function addParam($Para,$Value,$must_not_be_empty=0) {
		if ($must_not_be_empty==1) {
			if (empty($Value)) {
				return false;
			}
		}
		if ($this->Params[$Para]=urlencode($Value)) {
			return true;
		} else {
			return false;
		}
	}
	function delParam($Para) {
		unset($this->Params[$Para]);
	}
	function getParamString($Para) {
		if (isset($Para) && !empty($Para) && $Para!='') {
			return "$Para=".$this->Params[$Para];
		}	else {
			return false;
		}
	}
	function getParamValue($Para) {
		if (isset($Para) && !empty($Para) && $Para!='') {
			return $this->Params[$Para];
		} else {
			return false;
		}
	}
	function getParamCount() {
		return count($this->Params);
	}
	function getAllParams() {
		$P="?1=1";
		$P_=array_keys($this->Params);

		//print_r($this->Params);

		for ($i=0;$i<$this->getParamCount($this->Params);$i++) {
			$P__=$P_[$i];

			if ($this->Params[$P__]!="" ) {//&& !empty($this->Params[$P__])
				$P .="&amp;".$P__."=".$this->Params[$P__];
			}
		}
		//$P = str_replace("//", "/", $P);
		return urldecode($P);
	}
}


?>