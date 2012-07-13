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

Class tm_Bounce {
	var $Bounces=Array();
	var $count=0;
	var $regex_headerfield="^([^:]*): (.*)";
	//the easy way, everything that might look like an emailaddress
	var $regex_email='/[-.\w]+@[-.\w]+\.\w{2,}/';//for preg_match_all: /[-.\w]+@[-.\w]+\.\w{1,6}/
	//array with regex for headers to search for addresses
	var $searchXHeaders=Array("^X-Failed:","^From:","^Return-Path:","^Reply-To:","^Followup-To:","^CC:","^To:","^envelope-to:","^X-");
	//"^X-Failed:","^From:","^Return-Path:","^Reply-To:","^Followup-To:","^CC:","^To:","^envelope-to:","^X-"
	//chars to remove from header key value
	var $removable_chars=Array("<",">","|",":",",",";");//"<",">","|",":",",",";"
	
	function filterBounces($Mail,$checkHeader=1,$checkBody=1,$returnOnlyBounces=0) {
		/*
		Uebernimmt ein Array fuer eine einzelne Mail (aus class mMail ... getMail()] und prueft wahlweise Header und /oder Body auf Bounces...und gibt dieses Array 'gefiltert' zurueck
		*/
		$Bounce=Array();
		$Bounce_Head=Array();
		$Bounce_Body=Array();
		$mc=count($Mail);
		for ($mcc=0;$mcc<$mc;$mcc++) {
			if (isset($Mail[$mcc])) {
				$is_bouncemail=false;
				$Message=$Mail[$mcc];//Array
				//header checken?
				if ($checkHeader==1) {
					$Bounce_Head=$this->checkHeader($Message['header']);
					//adressen mit falscher syntax rauswerfen
					$Bounce_Head=$this->removeInvalidEmails($Bounce_Head);
				}
				//body checken?
				if ($checkBody==1) {
					$Bounce_Body=$this->checkBody($Message['body']);
					//adressen mit falscher syntax rauswerfen
					$Bounce_Body=$this->removeInvalidEmails($Bounce_Body);
				}
				//wenn was gefunden wurde... ist der array nicht leer, also ist es eine potentielle boncemail
				$Mail[$mcc]['is_bouncemail']=0;
				if (count($Bounce_Head) || count($Bounce_Body)) {
					$is_bouncemail=true;
					$Mail[$mcc]['is_bouncemail']=1;
				}
				//wenn nur bounces zurueckgeliefert werden sollen, und es ist keines, dann eintrag loeschen
				if ($returnOnlyBounces==1 && !$is_bouncemail ) {
					unset($Mail[$mcc]);
				}
				if ($returnOnlyBounces==0 || ($returnOnlyBounces==1 && $is_bouncemail) ) {
					//array erzeigen aus den gefundenen adressen in head und body
					$Bounce=array_merge($Bounce_Head,$Bounce_Body);
					//hier unifying, da wir pro mail jede adresse nur einmal auswerten muessen.
					$Bounce=unify_array($Bounce);
					//Array in der Message Speichern
					$Mail[$mcc]['bounce']=$Bounce;
				}
			}
		}
		//arraay neu ordnen
		$Mail=array_values($Mail);
		return $Mail;
	}//function filter Bounces

	function check_for_emails($line) {
		$adr=Array();
		$return=Array();
		if (preg_match_all($this->regex_email , $line, $adr)) {
			$lac=count($adr[0]);
			for ($lacc=0;$lacc<$lac;$lacc++) {
				$email=$adr[0][$lacc];
				$ac=count($return);
				$return[$ac]=$email;
			}//for
		}//if pregmatch
		return $return;
	}//check_for_emails

	function removeInvalidEmails($adr) {
		//removes syntactically invalid emails from 1-dim adr array
		$ac=count($adr);
		for ($acc=0;$acc<$ac;$acc++) {
			if (isset($adr[$acc])) {
				$check_mail=checkEmailAdr($adr[$acc],1);//1=nur syntax
				if (!$check_mail[0]) unset($adr[$acc]);
			}
		}
		$adr=array_values($adr);
		return $adr;
	}//removeInvalidEmails

	function checkHeader($message_header) {
		$adr=array();
		$header_lines = explode("\n", $message_header);
		$hlc=count($header_lines);//anzahl zeilen, lines
		#if (tm_DEBUG()) echo "found ".$hlc." lines in header<br>\n";
		//now browse header array:
		$lc=count($header_lines);
		for ($lcc=0;$lcc<$lc;$lcc++) {
			$hline_arr=Array();
			$hline=$header_lines[$lcc];
			#if (tm_DEBUG()) echo "<br>\nline $lcc: <font size=1>".$hline."</font>";
			//sieht die zeile wie eine headerzeile aus? 
			//trennen von name und wert (alles nach ': ')
		   if (eregi($this->regex_headerfield, $hline, $hline_arr)) {
		   	#if (tm_DEBUG()) echo "<br>\nis Header, continue";
		      #if (tm_DEBUG()) echo "<br>\nsplit header and value: ";
			   $hline_key=$hline_arr[1];
			   $hline_value=$hline_arr[2];
		      if (tm_DEBUG()) echo "<br>\nkey: ".$hline_key."<br>\nvalue: ".$hline_value;
				//remove removable chars
				$hline_value=str_replace($this->removable_chars," ",$hline_value);
		  		//search for headers
		  		foreach ($this->searchXHeaders as $XHeader) {
		  			//if header matches
		         if (eregi("$XHeader", $hline)) {
			         #if (tm_DEBUG()) echo "<br>\nmatches: '$XHeader'";
						//now detect email in current line.......
						$xadr=$this->check_for_emails($hline_value);
						//merge array with $adr
						$adr=array_merge($adr,$xadr);
							//mehrere adressen sind moeglicherweise zeilenweise angefuegt....
							//nun die naechste zeile pruefen....! wenn diese existiert kein headerfeld enthaelt, fuegen wir sie an da es moeglicherweise eine weitere x-failed adrese ist......!
							#if (isset($header_lines[$lcc+1]) && !eregi("$regex_headerfield", $header_lines[$lcc+1])) {//doppeltgemoppelt, aber noetig fuer die meldung, kann eigentlich weg, dann wird es aber immer durchlaufen....
								#if (tm_DEBUG()) echo "<br>\n....continues on next headerline ".($lcc+1);
								//nun die naechsten zeilen pruefen....! wenn diese existiert kein headerfeld enthaelt, fuegen wir sie an da es moeglicherweise eine weitere header adrese ist......! sobald headerfield entdeckt wurde, abbruch, lcc2=lc
								for ($lcc2=($lcc+1);$lcc2<$lc;$lcc2++) {
									if (isset($header_lines[$lcc2]) && !eregi($this->regex_headerfield, $header_lines[$lcc2])) {
										#if (tm_DEBUG()) echo "<br>checking next line $lcc2";
										$hline2=$header_lines[$lcc2];
										#if (tm_DEBUG()) echo "<br>$hline2";
										$xadr=$this->check_for_emails($hline2);
										#if (tm_DEBUG()) print_r($xadr);
										$adr=array_merge($adr,$xadr);
						         } else {//if isset 
						         	#if (tm_DEBUG()) echo "<br>skip, next line ".($lcc2)." is a new headerline!";
						         	$lcc2=$lc;//absprung
						         }//if isset 
								}//for lcc2
							#}//if isset headerline+1
		           }//if eregi XHeader....
				}//foreach searchXHeader as XHeader
			} else {//if line looks like a header
				#if (tm_DEBUG()) echo "<br>\ndoes not look like a headerline...";
			}//if line looks like a header
		   #if (tm_DEBUG()) echo "<br>\n----------------------------------------------------------------<br>\n";
		}//foreach header_lines as line
		#if (tm_DEBUG()) echo "<br>gesamt ".count($adr)." adressen erkannt<br>";
		$adr=array_unique($adr);
		return $adr;
	}//checkHeader

	function checkBody($message_body) {
		$adr=array();
		$body_lines = explode("\n", $message_body);
		$hlc=count($body_lines);//anzahl zeilen, lines
		#if (tm_DEBUG()) echo "found ".$hlc." lines in body<br>\n";
		//now browse body line array:
		$lc=count($body_lines);
		for ($lcc=0;$lcc<$lc;$lcc++) {
			$bline_arr=Array();
			$bline=$body_lines[$lcc];
			#if (tm_DEBUG()) echo "<br>\nline $lcc: <font size=1>".$bline."</font>";
				//remove removable chars
				$bline=str_replace($this->removable_chars," ",$bline);
				$xadr=$this->check_for_emails($bline);
				//merge array with $adr
				$adr=array_merge($adr,$xadr);
		}//foreach body_lines as line
		#if (tm_DEBUG()) echo "<br>gesamt ".count($adr)." adressen erkannt<br>";
		$adr=array_unique($adr);
		return $adr;
	}//checkBody

	function checkHeader_OLD($Message) {
		$Bounce=Array();
		$header = explode("\n", $Message['header']);
		$bounce_count=0;
		$regex_headerfield="^([^:]*): (.*)";
		$regex_nextline="^([^ ]*)(.*)";
		//wir suchen als erstes nach einem X-Header X-Failed-recipients: name@domain.tld
   		$searchXHeaders[0]="^X-failed";
   		$searchXHeaders[1]="^From";
   		$searchXHeaders[2]="^Return-Path";
   		$searchXHeaders[3]="^Reply-To";
   		$searchXHeaders[4]="^Followup-To";
   		$searchXHeaders[5]="^CC";
   		#$searchXHeaders[6]="^Envelope-to";
	//from:
		$Bounce[$bounce_count]=$Message['from'];
		$bounce_count++;
	//to:
		#$Bounce[$bounce_count]=$Message['to'];
		#$bounce_count++;

	   // browse header array
	   if (is_array($header) && count($header)) {
	       $lc=count($header);//anzahl zeilen, lines
		    for ($lcc=0;$lcc<$lc;$lcc++) {
	       		$line=$header[$lcc];
	       		foreach ($searchXHeaders as $XHeader) {
					//mehrere adressen sind moeglicherweise zeilenweise angefuegt....
			         if (eregi("$XHeader", $line)) {
				  #if (eregi("^X-failed", $line)) {
		               //trennen von name und wert (alles nach ': ')
		               eregi("$regex_headerfield", $line, $arg);
		               //adresse speichern
						$arg[2]=utrim(str_replace(",","",$arg[2]));
						$check_mail=checkEmailAdr($arg[2],1);//nur syntax
						if ($check_mail[0]) {
		               		$Bounce[$bounce_count] =utrim($arg[2]);
		               		$bounce_count++;
						}
						//nun die naechste zeile pruefen....! wenn diese existiert kein headerfeld enthaelt, fuegen wir sie an da es moeglicherweise eine weitere x-failed adrese ist......!
						for ($lcc2=($lcc+1);$lcc2<$lc;$lcc2++) {
							if (isset($header[$lcc2]) && !eregi("$regex_headerfield", $header[$lcc2])) {
			               		eregi("$regex_nextline", $header[$lcc2], $arg);
								if (!empty($arg[2])) {
									$arg[2]=utrim(str_replace(",","",$arg[2]));
									$check_mail=checkEmailAdr($arg[2],1);//nur syntax
									if ($check_mail[0]) {
					               		$Bounce[$bounce_count] = $arg[2];
					               		$bounce_count++;
									}
				            	}
				         	}
						}
						#$lcc=$lc;//damit wird die schleife beednet!
						$lcc++;
		            unset ($arg);
		           }//if eregi x-faild....
				}//foreach
	       }//for lcc (1)
	   }//is array && count
	   return $Bounce;
	}//function checkHeader()

	function checkBody_OLD($Message) {
		$Bounce=Array();
		$body=explode("\n", $Message['body']);
		$bounce_count=0;
		foreach ($body as $line) {
			//wir suchen nun nach diversen mustern....
			//als erste trennen wir nach : doppelpunkt :
			//und nach ; semikolon ;
			//trim
			#$line=utrim(clear_text($line));
			#$line=strip_htmltags($line);
			#$line=strip_tags($line);

			#echo $line."<br>\n";

			//< und > wegmachen....
			$line=str_replace("<"," ",$line);
    		$line=str_replace(">"," ",$line);

    		//doppelpunkt auch filtern, und komma
    		$line=str_replace(":"," ",$line);
    		$line=str_replace(","," ",$line);
			$line=utrim($line);
			#echo $line."<br>\n";
			//for each regex_body as $regex... etc
			//wenn email syntax korrekt, also wenn es wirklich einen email ist in dieser zeile....
			#checkEmailAdradr($email,1)//1==nur syntax
			//oder
			//int eregi  ( string $pattern  , string $string  [, array &$regs  ] )
			//if matches are found for parenthesized substrings of pattern and the function is called with the third argument regs , the matches will be stored in the elements of the array regs .
			//$regs[1] will contain the substring which starts at the first left parenthesis; $regs[2] will contain the substring starting at the second, and so on. $regs[0] will contain a copy of the complete string matched.
			#$regex="([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}";
			$regex="([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}";
			// ^^^^^^^^^ findet auch adresen innerhalb des textes!
			eregi("$regex",$line,$adr_arr);
			$ac=count($adr_arr);
			for ($acc=1;$acc<$ac;$acc++) {
				echo $acc.":".$adr_arr[$acc]."\n";
			}
			$check_mail=checkEmailAdr($line,1);//nur syntax
			if ($check_mail[0]) {
    	       $Bounce[$bounce_count] = $line;
    	       $bounce_count++;
				#echo "<hr><br><br>(1) detected bounceaddress: ".$line."<br><hr>\n";
			} else {
				//spezialfall 1
				//Original-Recipient: rfc822;tm_xxxx@domain.tld
				//Final-Recipient: rfc822;tm_xxxx@domain.tld
				//wir trennen nach semikolon..... und nehmen den zweiten eintrag!
//alt:
				$line_x = explode(";", $line);
				if (isset($line_x[1])) {
					$line_x[1]=utrim($line_x[1]);
					$check_mail=checkEmailAdr($line_x[1],1);//nur syntax
					if ($check_mail[0]) {
  		 	        	$Bounce[$bounce_count] = $line_x[1];
  		   		      	$bounce_count++;
						#echo "<hr><br><br>(2) detected bounceaddress: ".$line."<br><hr>\n";
					}//checkEmailAdradr line_x
				}//isset line_x[1]

				//neu:
				//trennen nach semikolon, dann nach doppelpunkt.... jeweils erste 3 eintraege druchsuchen und auf email pruefen
				//quatsch, siehe neue funktion!
			} //chkemail line
		}//foreach
		return $Bounce;
	}//function check Body







} //Class tm_Bounce
?>