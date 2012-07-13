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

Class tm_Mail {
	var $Error;
	var $mbox;
	var $count_msg;
	var $Mails=Array();	//Klassenweites Array fuer Mails!
	var $Host;
	var $Type;
	var $Port;
	var $Options;
	var $Srv;
	var $User;
	var $Pass;
	

	function getError() {
		$this->Error=imap_last_error();
	}
	function connect($Host, $User, $Pass,$type="pop3",$port="110",$options="")  {//"novalidate-cert"
		//port, type: imap/pop3! 
		$this->Srv="$Host:$port/$type";
		if (!empty($options))	{
			$this->Srv .="/$options"; 
		}
		$this->Host=$Host;
		$this->Type=$type;
		$this->Port=$port;
		$this->Options=$options;
		$this->User=$User;
		$this->Pass=$Pass;
		//
		$this->mbox = imap_open ("{".$this->Srv."}INBOX", "$User", "$Pass"); 
		$this->count_msg=imap_num_msg($this->mbox);
		$this->getError();
	}
	function disconnect() {
		imap_close ($this->mbox);
	}
	function reconnect() {
		$this->disconnect();
		$this->connect($this->Host,$this->User,$this->Pass,$this->Type,$this->Port,$this->Options);
	}
	
	function expunge() {
		imap_expunge ($this->mbox); 
		$this->count_msg=imap_num_msg($this->mbox);
	}

	function delete($no) {
		imap_delete($this->mbox,$no);
		//imap_setflag_full($this->mbox, imap_uid($this->mbox, 0), "\\Deleted \\Seen", ST_UID);
		$this->count_msg=imap_num_msg($this->mbox);
	}
	
	
function getImapFolderAttributesFlagArray($p_attributes){
	//from http://www.php.net/manual/de/function.imap-getmailboxes.php
	//razonklnbd at hotmail dot com  06-Jan-2008 08:49  
    $attrs[LATT_HASNOCHILDREN]='false';
    $attrs[LATT_HASCHILDREN]='false';
    $attrs[LATT_REFERRAL]='false';
    $attrs[LATT_UNMARKED]='false';
    $attrs[LATT_MARKED]='false';
    $attrs[LATT_NOSELECT]='false';
    $attrs[LATT_NOINFERIORS]='false';
    $attrsX=$attrs;
    foreach($attrs as $attrkey=>$attrval){
        if ($p_attributes & $attrkey){
            $attrsX[$attrkey]='true';
            $p_attributes -=$attrkey;
        }
    }
    return $attrsX;
} 	
	
	function getFolders() {
		//copied some code from http://www.php.net/manual/de/function.imap-getmailboxes.php
		//razonklnbd at hotmail dot com  06-Jan-2008 08:49  
		$Return=Array();
		$Folders=imap_getmailboxes($this->mbox, "{".$this->Host."}", '*');
		#$Return=imap_list($this->mbox, "{".$this->Host."}", '*');
		 if(is_array($Folders)){
        	foreach($Folders as $fkey=>$folder){
            	$mapname = str_replace("{".$this->Host."}", "", imap_utf7_decode($folder->name));
            	if($mapname[0] != ".") {
                	//$attrs[LATT_]=': NO';
                	$list_folders[$fkey]['name'] = $folder->name;
                	$list_folders[$fkey]['mapname'] = $mapname;
                	$list_folders[$fkey]['delimiter'] = $folder->delimiter;
                	$list_folders[$fkey]['attributes'] = $folder->attributes;
                	$list_folders[$fkey]['attr_values'] = $this->getImapFolderAttributesFlagArray($folder->attributes);
            	}
        	}
    	}
    	$Return=$list_folders;
		$this->getError();
		return $Return;
	}	

	function ImapStatus($folder="INBOX") {
		$Return=Array();
		$status = imap_status($this->mbox, "{".$this->Host."}".$folder, SA_ALL);
		if ($status) {
	  		$Return['messages'] = $status->messages;
	  		$Return['recent'] = $status->recent;
	  		$Return['unseen'] = $status->unseen;
	  		$Return['uidnext'] = $status->uidnext;
	  		$Return['uidvalidity'] = $status->uidvalidity;
		}
		$this->getError();
		return $Return;
	}//function status

	function getQuota() {
		$Return = imap_get_quotaroot($this->mbox, "INBOX");
		$this->getError();
		return $Return;
	}	
	
	function saveMessage($folder,$message) {
		imap_append($this->mbox, "{".$this->Host."}".$folder,$message);
	}
	
	//nachrichten holen und in array zurueckliefern , so wie wirs gerne haetten...
	function getMail($id=0,$offset=0,$limit=0,$search=Array()) {
		$Mail=Array();
		$Return=Array();
		if (!$limit) {
			$limit=$this->count_msg;
		}
		if ($limit+$offset > $this->count_msg) {
			$limit = ($this->count_msg-$offset);
		}
		if ($id) {
			$offset=$id-1;//da wir als id mindestens 1 uebergeben muessen wir hier eins abziehen da ein array bei 0 beginnen soll....
			$limit=1;
		}
		
		$cc=0;
		for ($i=$offset; $i < ($offset+$limit); $i++) {
		  	$header = imap_headerinfo($this->mbox, $i+1, 80, 80);//$i+1 !! messageno beginnt bei 1 und array bei 0!!!
			//alternative statt headerinfo etcie x header aus nach dnen wir ja auch unbedingt suchen wollen:
			//enthaelt den kompletten header
			$Mail[$cc]['header'] = imap_fetchheader($this->mbox, $i+1);
			//=explode("\n", imap_fetchheader($mbox, $i));
			
			$to = (isset($header->to) && isset($header->to[0]->mailbox) && isset($header->to[0]->host)) ? $header->to[0]->mailbox."@".$header->to[0]->host : "k.a.";//: '".display($header->to[0]->mailbox."@".$header->to[0]->host)."'";
			#$from=$header->from[0]->mailbox."@".$header->from[0]->host;
			//fixed bug where from addrress is not a valid email address, e.g. From: MAILER-DAEMON (Mail Delivery System)
			//https://sourceforge.net/tracker/?func=detail&aid=3121248&group_id=190396&atid=933192
			//bug id 3121248
			//thx to tms-schmidt
			$from = (isset($header->from) && isset($header->from[0]->mailbox) && isset($header->from[0]->host)) ? $header->from[0]->mailbox."@".$header->from[0]->host : "k.a.";//: '".display($header->from[0]->mailbox."@".$header->from[0]->host)."'";

			//wenn kein filter oder filter matched, TO
			if (!isset($search['to']) || $search['to']==$to) {
				$Mail[$cc]['to_box'] = (isset($header->to[0]->mailbox)) ? $header->to[0]->mailbox : "k.a.";
				$Mail[$cc]['to_host'] = (isset($header->to[0]->host)) ? $header->to[0]->host : "k.a.";
				#$Mail[$cc]['from_box'] =  $header->from[0]->mailbox;
				#$Mail[$cc]['from_host'] =  $header->from[0]->host;
				//fixed bug where from addrress is not a valid email address, e.g. From: MAILER-DAEMON (Mail Delivery System)
				//https://sourceforge.net/tracker/?func=detail&aid=3121248&group_id=190396&atid=933192
				//bug id 3121248
				//thx to tms-schmidt
				$Mail[$cc]['from_box'] = (isset($header->from[0]->mailbox)) ? $header->from[0]->mailbox : "k.a.";
				$Mail[$cc]['from_host'] = (isset($header->from[0]->host)) ? $header->from[0]->host : "k.a.";
				$Mail[$cc]['to'] = $to;
				$Mail[$cc]['from'] = $from;
				$Mail[$cc]['subject'] = (isset($header->fetchsubject)) ? $header->fetchsubject : "no Subject";
				$Mail[$cc]['date'] = (isset($header->Date)) ? $header->Date : "no Date";
				#$Mail[$cc]['id'] = $header->message_id;
				$Mail[$cc]['no'] = utrim($header->Msgno);
				$Mail[$cc]['size'] = $header->Size;
				$Mail[$cc]['unseen'] = $header->Unseen;
				$Mail[$cc]['recent'] = $header->Recent;
				$Mail[$cc]['flagged'] = $header->Flagged;

				//textparts aus der mail holen und anhaengen			
				$Mail[$cc]['body'] = $this->getTextParts($i+1) ;			
				$cc++;
			} else {
				unset($Mail[$cc]);
				//$limit++; //limit um 1 erhoehen wenn filter gesetzt und nichts gefunden! damit wir immer $limit ergebnisse bekommen , maximal!
				// kann aber zu einer komischen schleife fuehren, liefert mehr ergebnisse als limit ist... :)
				//pruefen ob max nazahl mails ueberschritten, wenn ja, schleife abbrechen!
				#if (($offset+$limit+1)>$this->count_msg) {
				#	$i=$this->count_msg;
				#}
				#$limit++; // limit um 1 erhoehen!
			}
			//imap_setflag_full($this->mbox, $i, "\\SEEN"); 
			//nur imap!!! imap_setflag_full($this->mbox, imap_uid($this->mbox, $i), "\\Seen", ST_UID);
			//print_r(imap_headerinfo($this->mbox, $i, 80, 80));
		}
		//array neu ordnen
		$Mail=array_values($Mail);
		$this->Mails=$Mail;// an klassenweites array uebergeben! damit kann man immernoch sachen machen
		Return $Mail;
	}


	//ermittelt textparts einer email, keine arrays, dient zur analyse von bodys auf emailadressen fuer bounces etc.
	function getTextParts($no) {
		$obj = imap_fetchstructure($this->mbox, $no); 
		$P = $this->mime_scan($obj);  
		$pc = count($P); 
		$body = "";
		for ($pcc = 1; $pcc <= $pc; $pcc++)		{ 
			//keine arrays... :)
			//is ne abkuerzung, man koennte auch wenn ein array zurueckgegeben wird dort nochmal durchwandern..... das wird zuviel, in der regel verbirgt sich dort mime encoded zeugs, anhaenge, html etc
			//und leerzeichen entfernen! vorne/hinten...
			$Part = $this->fetchpart($this->mbox, $no , $pcc);
			if (!is_array($Part)) {
				$body .= $Part."\n";
			}
			$Part=$this->fetchpart($this->mbox, $no , $pcc."1");
			if (!is_array($Part)) {
				$body .= $Part."\n";
			}
		} //pcc
		return $body;
	}


    // get the body of a part of a message according to the 
    // string in $part 
    function fetchpart($mbox, $msgNo, $part) { 
        $parts = $this->fetchparts($mbox, $msgNo); 
         
        $partNos = explode(".", $part); 
         
        $currentPart = $parts; 
        while(list ($key, $val) = each($partNos)) { 
	        	if (isset($currentPart[$val])) {
   	         $currentPart = $currentPart[$val]; 
   	      }
        } 
         
        if ($currentPart != "") return $currentPart; 
          else return false; 
    } 

    // get an array with the bodies all parts of an email 
    // the structure of the array corresponds to the  
    // structure that is available with imap_fetchstructure 
    function fetchparts($mbox, $msgNo) { 
        $parts = array(); 
        $header = imap_fetchheader($mbox,$msgNo); 
        $body = imap_body($mbox,$msgNo, FT_INTERNAL); 
         
        $i = 1; 
         
        if ($newParts = $this->mimesplit($header, $body)) { 
            while (list ($key, $val) = each($newParts)) { 
                $parts[$i] = $this->mimesub($val); 
                $i++;                 
            } 
        } else { 
            $parts[$i] = $body; 
        } 
        return $parts; 
   }      


	function mime_scan_multipart(&$parts, $part_number,$Parts) 
	{ 
        $n = 1; 
        foreach ($parts as $obj) { 
                if ($obj->type == 1) { 
                	$Parts[$n]= "$n "; 
                			$this->mime_scan_multipart($obj->parts, "$part_number.$n",$Parts); 
                } else { 
                      $Parts[$n]= "$n "; 
                } 
                $n++; 
        } 
        return $Parts; 
	} 
 
	function mime_scan(&$obj) 
	{ 
        if ($obj->type == 1) 
              return  $this->mime_scan_multipart($obj->parts, "",""); 
        else 
                return 1; 
	} 

 
    // splits a message given in the body if it is 
    // a mulitpart mime message and returns the parts, 
    // if no parts are found, returns false 
    function mimesplit($header, $body) { 
        $parts = array(); 
         
        $PN_EREG_BOUNDARY = "Content-Type:(.*)boundary=\"([^\"]+)\""; 
 
        if (eregi ($PN_EREG_BOUNDARY, $header, $regs)) { 
            $boundary = $regs[2]; 
 
            $delimiterReg = "([^\r\n]*)$boundary([^\r\n]*)"; 
            if (eregi ($delimiterReg, $body, $results)) { 
                $delimiter = $results[0]; 
                $parts = explode($delimiter, $body); 
                $parts = array_slice ($parts, 1, -1); 
            } 
             
            return $parts; 
        } else { 
            return false; 
        }     
         
         
    } 
 
    // returns an array with all parts that are 
    // subparts of the given part 
    // if no subparts are found, return the body of  
    // the current part 
    function mimesub($part) { 
        $i = 1; 
        $headDelimiter = "\r\n\r\n"; 
        $delLength = strlen($headDelimiter); 
     
        // get head & body of the current part 
        $endOfHead = strpos( $part, $headDelimiter); 
        $head = substr($part, 0, $endOfHead); 
        $body = substr($part, $endOfHead + $delLength, strlen($part)); 
         
        // check whether it is a message according to rfc822 
        if (stristr($head, "Content-Type: message/rfc822")) { 
            $part = substr($part, $endOfHead + $delLength, strlen($part)); 
            $returnParts[1] = $this->mimesub($part); 
            return $returnParts; 
        // if no message, get subparts and call function recursively 
        } elseif ($subParts = $this->mimesplit($head, $body)) { 
            // got more subparts 
            while (list ($key, $val) = each($subParts)) { 
                $returnParts[$i] = $this->mimesub($val); 
                $i++; 
            }             
            return $returnParts; 
        } else { 
            return $body; 
        } 
    } 
 
  
  
 }    //Class tm_Mail
?>