<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/11 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

/**functions**/



function mime_extract_rfc2822_address($string)
{
		//http://www.php.net/manual/de/function.preg-match-all.php#62104
        //rfc2822 token setup
        $crlf           = "(?:\r\n)";
        $wsp            = "[\t ]";
        $text           = "[\\x01-\\x09\\x0B\\x0C\\x0E-\\x7F]";
        $quoted_pair    = "(?:\\\\$text)";
        $fws            = "(?:(?:$wsp*$crlf)?$wsp+)";
        $ctext          = "[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F" .
                          "!-'*-[\\]-\\x7F]";
        $comment        = "(\\((?:$fws?(?:$ctext|$quoted_pair|(?1)))*" .
                          "$fws?\\))";
        $cfws           = "(?:(?:$fws?$comment)*(?:(?:$fws?$comment)|$fws))";
        //$cfws           = $fws; //an alternative to comments
        $atext          = "[!#-'*+\\-\\/0-9=?A-Z\\^-~]";
        $atom           = "(?:$cfws?$atext+$cfws?)";
        $dot_atom_text  = "(?:$atext+(?:\\.$atext+)*)";
        $dot_atom       = "(?:$cfws?$dot_atom_text$cfws?)";
        $qtext          = "[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F!#-[\\]-\\x7F]";
        $qcontent       = "(?:$qtext|$quoted_pair)";
        $quoted_string  = "(?:$cfws?\"(?:$fws?$qcontent)*$fws?\"$cfws?)";
        $dtext          = "[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F!-Z\\^-\\x7F]";
        $dcontent       = "(?:$dtext|$quoted_pair)";
        $domain_literal = "(?:$cfws?\\[(?:$fws?$dcontent)*$fws?]$cfws?)";
        $domain         = "(?:$dot_atom|$domain_literal)";
        $local_part     = "(?:$dot_atom|$quoted_string)";
        $addr_spec      = "($local_part@$domain)";
        $display_name   = "(?:(?:$atom|$quoted_string)+)";
        $angle_addr     = "(?:$cfws?<$addr_spec>$cfws?)";
        $name_addr      = "(?:$display_name?$angle_addr)";
        $mailbox        = "(?:$name_addr|$addr_spec)";
        $mailbox_list   = "(?:(?:(?:(?<=:)|,)$mailbox)+)";
        $group          = "(?:$display_name:(?:$mailbox_list|$cfws)?;$cfws?)";
        $address        = "(?:$mailbox|$group)";
        $address_list   = "(?:(?:^|,)$address)+";

        //apply expression
        preg_match_all("/^$address_list$/", $string, $array, PREG_SET_ORDER);
        return $array;
};

function tm_DEMO() {
	//yeah, lots of debug info here
	
	//global demo mode enabled: demo mode always enabled, regardless of usersettings, or if logged in or not! always enabled 
	if (TM_DEMO) 	{
		if (tm_DEBUG()) $GLOBALS['_MAIN_MESSAGE'].=tm_message_debug("tm_DEMO(): ___DEMO GLOBALLY ENABLED, RETURN TRUE___");
		return TM_DEMO;//TRUE
	}
	//check if user is logged in
	$logged_in=FALSE;
	if (isset($GLOBALS['logged_in']) && $GLOBALS['logged_in']===TRUE) {
			$logged_in=$GLOBALS['logged_in'];
	}
	//debug output
	
	if (tm_DEBUG() && $logged_in) {
		$GLOBALS['_MAIN_MESSAGE'].= tm_message_debug("tm_DEMO(): ___LOGGED IN___");
	}
	if (tm_DEBUG() && !$logged_in) {
		$GLOBALS['_MAIN_MESSAGE'].= tm_message_debug("tm_DEMO(): ___NOT LOGGED IN___");
	}
	
	//logged_in is not set or false if used in frontend! (subscribe, send_it, unsubscribe, check_it, send_it etc)
	if (TM_DEMO_FE  && !$logged_in) {
		if (tm_DEBUG()) $GLOBALS['_MAIN_MESSAGE'].=tm_message_debug("tm_DEMO(): ___DEMO FE ENABLED AND NOT LOGGED IN, RETURN TRUE ___");
		return TM_DEMO_FE;//TRUE
	}
	if (TM_DEMO_FE  && $logged_in) {
		if (tm_DEBUG()) $GLOBALS['_MAIN_MESSAGE'].=tm_message_debug("tm_DEMO(): ___DEMO FE ENABLED AND LOGGED IN ___");
	}
	//check if demo mode is enabled for user
	//check if user is logged in and has demo enabled, otherwise return FALSE!
	$demo_user = (
								$logged_in
								) 
								? $GLOBALS['LOGIN']->USER['demo'] : 0 ;
	if ($demo_user == 1) {
		if (tm_DEBUG()) $GLOBALS['_MAIN_MESSAGE'].=tm_message_debug("tm_DEMO(): ___DEMO USER == 1, RETURN TRUE ___");
		return TRUE;
	}
	if (tm_DEBUG()) $GLOBALS['_MAIN_MESSAGE'].=tm_message_debug("tm_DEMO(): ___DEMO USER != 1, RETURN FALSE ___");
	return FALSE;
}

function tm_DEBUG() {
	//global debug mode enabled: debug mode always enabled, regardless of usersettings, or if logged in or not! always enabled 
	if (TM_DEBUG) 	{
		return TM_DEBUG;//TRUE
	}
	//check if user is logged in
	$logged_in=FALSE;
	if (isset($GLOBALS['logged_in']) && $GLOBALS['logged_in']===TRUE) {
			$logged_in=$GLOBALS['logged_in'];
	}
	//logged_in is not set or false if used in frontend! (subscribe, send_it, unsubscribe, check_it, send_it etc)
	if (TM_DEBUG_FE  && !$logged_in) {
		return TM_DEBUG_FE;//TRUE
	}
	//check if user is logged in and has debug enabled, otherwise return FALSE!
	$debug_user = (
								$logged_in
								) 
								? $GLOBALS['LOGIN']->USER['debug'] : 0 ;
	if ($debug_user == 1) {
		return TRUE;
	}
	return FALSE;
}

function tm_DEBUG_LANG() {
	//global debug mode enabled: debug mode always enabled, regardless of usersettings, or if logged in or not! always enabled 
	if (TM_DEBUG_LANG) 	{
		return TM_DEBUG_LANG;//TRUE
	}
	//check if user is logged in
	$logged_in=FALSE;
	if (isset($GLOBALS['logged_in']) && $GLOBALS['logged_in']===TRUE) {
			$logged_in=$GLOBALS['logged_in'];
	}
	//logged_in is not set or false if used in frontend! (subscribe, send_it, unsubscribe, check_it, send_it etc)
	if (TM_DEBUG_LANG_FE  && !$logged_in) {
		return TM_DEBUG_LANG_FE;//TRUE
	}
	//check if user is logged in and has demo enabled, otherwise return FALSE!
	$debug_lang_user = (
								$logged_in
								) 
								? $GLOBALS['LOGIN']->USER['debug_lang'] : 0 ;
	if ($debug_lang_user == 1) {
		return TRUE;
	}
	return FALSE;
}

function tm_DEBUG_LANG_LEVEL() {
	//check if user is logged in
	$logged_in=FALSE;
	if (isset($GLOBALS['logged_in']) && $GLOBALS['logged_in']===TRUE) {
			$logged_in=$GLOBALS['logged_in'];
	}

	//check if user is logged in and has level > 0, otherwise return global setting
	$debug_user_lang_level = (
													$logged_in
													) 
								? $GLOBALS['LOGIN']->USER['debug_lang_level'] : 0 ;
	//if level >0 then return usersetting, ...
	if ($debug_user_lang_level > 0) return $debug_user_lang_level;
	//otherwise return global setting
	return TM_DEBUG_LANG_LEVEL;
}

//get rss feed
function fetchRSS($url) {
	$doc = new DOMDocument();
 	$doc->load($url);
  	$arrFeeds = array();
  	foreach ($doc->getElementsByTagName('item') as $node) {
    	$itemRSS = array ( 
      	'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
      	'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
      	'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
      	'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
      	);
    	array_push($arrFeeds, $itemRSS);
  	}
  	return $arrFeeds;
 }
 
function tm_message_success($text="",$addnewline=FALSE,$htmlentities=FALSE) {
	return tm_message($text,"tm_message_success",$addnewline,$htmlentities=FALSE);
}
function tm_message_notice($text="",$addnewline=FALSE,$htmlentities=FALSE) {
	return tm_message($text,"tm_message_notice",$addnewline,$htmlentities=FALSE);
}
function tm_message_config($text="",$addnewline=FALSE,$htmlentities=FALSE) {
	return tm_message($text,"tm_message_config",$addnewline,$htmlentities=FALSE);
}
function tm_message_warning($text="",$addnewline=FALSE,$htmlentities=FALSE) {
	return tm_message($text,"tm_message_warning",$addnewline,$htmlentities=FALSE);
}
function tm_message_error($text="",$addnewline=FALSE,$htmlentities=FALSE) {
	return tm_message($text,"tm_message_error",$addnewline,$htmlentities=FALSE);
}
function tm_message_debug($text="",$addnewline=FALSE,$htmlentities=FALSE) {
	return tm_message($text,"tm_message_debug",$addnewline);
}

function tm_debugmessage($text="",$addnewline=FALSE) {
	//for backwards compat
	//tm_debugmessage replaced by tm_message_debug
	return tm_message_debug($text,$addnewline);
}

//show tm messages
function tm_message($text="",$message_type="default",$addnewline=FALSE,$htmlentities=FALSE) {
	$Return="";
	if (!empty($text)) {
		//use name of message style class for type instead of switch/case....
		if ($message_type=='default') {
			$message_style="tm_message";
		} else {
			$message_style=$message_type;
		}
		
		//...but use switch/case for prefixes
		switch($message_type) {
			default :	//"default"
				$message_prefix="";	
				break;
			case "tm_message_error":
			#case "error":
				$message_prefix=___("FEHLER").": ";
				break;
			case "tm_message_debug":
			#case "debug":
				$message_prefix=___("DEBUG").": ";
				break;
			//we need to add new cases if we want to define prefixes for other message-styles/types
		}
		
		$Return.="\n<div class=\"".$message_style."\">".$message_prefix;
		//http://talks.php.net/show/debugging/11
		if ($htmlentities) {
			$Return.=$text;
		} else {
			$Return.=display($text);
		}
		if ($addnewline) {
			$Return.="<br>";
		}
		$Return.="</div>\n";
	}
	return $Return;
}


//mtail, tail xx lines of a file
function mtail($file,$lines=1,$chunk=1024) {
	$Return="";	
	if (tm_DEBUG()) $Return.=tm_message_debug("mtail: file: $file , lines: $lines , chunk: $chunk");
	if ($fp = fopen($file, 'r')) {
		#if (tm_DEBUG()) $Return.=tm_debugmessage("open $file");
		$offset_seek=($chunk)* -($lines);
		#fseek($fp, $offset_seek, SEEK_END);
		$fs=filesize($file);
		if ($offset_seek > $fs) {
			$offset_seek=0;
		}
		$fpos=ftell($fp);
		// read some data
		#$data = fgets($fp, $chunk*($lines+1)-1);
		//string file_get_contents  ( string $filename  [, bool $use_include_path = false  [, resource $context  [, int $offset = -1  [, int $maxlen = -1  ]]]] )
		
		$offset=$chunk*$lines;
		$offset2=$fs-$offset;
		$data=file_get_contents($file,false,NULL,$offset2);
		#$tail_lines=explode("\n",$data);
		$tail_lines = preg_split('/[\r\n]+/', $data, -1, PREG_SPLIT_NO_EMPTY);		
		$lc=count($tail_lines);
		if (tm_DEBUG()) $Return.=tm_message_debug("offset (chunk * -(lines)): $offset fs: $fs fpos: $fpos fs-fpos: ".($fs-$fpos)." lc: $lc");
		#if (tm_DEBUG()) $Return.=tm_message_debug("data:");
		#if (tm_DEBUG()) $Return.=tm_message_debug($data);
		#if (tm_DEBUG()) $Return.=tm_message_debug("tail_lines:");
		#if (tm_DEBUG()) $Return.=tm_message_debug(print_r($tail_lines,TRUE));	
		
		if ($lc<=$lines) {
			//noch mehr daten einlesen
		}
		if ($lc<1) {
			#if (tm_DEBUG()) $Return.=tm_message_debug("lc<=1 $lc<=1");
			$Return.=tm_message_notice(sprintf(___("Datei %s hat keine Einträge!"),$file));
		}
		if ($lc>0) {
			#if (tm_DEBUG()) $Return.=tm_message_debug("lc>1");
			for ($lcc=$lc;$lcc>($lc-$lines);$lcc--) { 
				$Return.=tm_message_notice($tail_lines[$lcc-1]);
			}
		}
		
		fclose($fp);
	} else {
		$Return.=tm_message_notice(sprintf(___("Fehler: Datei %s kann nicht geöffnet werden!"),$file));
	}
	#$Return .="<hr>";
	return $Return;
}


//displays 2 for 2.00 or 2.01 for 2.01, preserves decimal values
//Jeroen de Bruijn [NL]
//http://php.net/manual/de/function.number-format.php
//we use pregsplit instead. split is outdated since php 503 :P
function DisplayDouble($value,$dec=2,$sep="",$thousands="") {
  list($whole, $decimals) = preg_split("/[.,]/", number_format($value,$dec+2));//weird: floatval will not do it
  if (intval($decimals) > 0) {
    	return number_format($value,$dec,$sep,$thousands);
  	} else {
    	return number_format($value,0,$sep,$thousands);
    }
}
  

//watermark an image
function watermark($image_source, $image_new, $image_watermark, $quality=95) {
	$Return[0]=true;
	$Return[1]="OK";
	if ($im_src = ImageCreateFromJPEG($image_source)) {
		if ($wm_src = ImageCreateFromPNG($image_watermark)) {

			ImageAlphaBlending($im_src, true);

			$im_width = ImageSX($im_src);
			$im_height = ImageSY($im_src);

			$wm_width = ImageSX($wm_src);
			$wm_height = ImageSY($wm_src);

			$wm_offset_x =$im_width - $wm_width;
			$wm_offset_y =$im_height - $wm_height;
			$wm_margin_x =  round($wm_offset_x / 2);
			$wm_margin_y =  round($wm_offset_y / 2);

			//copy watermark into source image! but save as new file
			ImageCopy($im_src, $wm_src, $wm_margin_x, $wm_margin_y, 0, 0, $wm_width, $wm_height);
			//save new file			
			if (ImageJPEG($im_src,$image_new, $quality)) {
			} else {
				$Return[0]=False;
				$Return[1]=sprintf(___("Fehler beim schreiben von %s."),$image_new);		
			}//imagejpeg
			ImageDestroy($wm_src);
		} else {
			$Return[0]=False;
			$Return[1]=sprintf(___("Fehler beim öffnen von %s."),$image_watermark);
		}//wm_src
		ImageDestroy($im_src);
	} else {
		$Return[0]=False;
		$Return[1]=sprintf(___("Fehler beim öffnen von %s."),$image_source);
	}//im_src
	return $Return;
}
//create thumbnail, proportional, max NNN pixels width or height, use largest side
function createThumb($image,$outputimage,$maxsize=180,$quality=95) {
	$Return=true;
   if ($im = ImageCreateFromJPEG($image))
   {
	   if (ImageSX($im)>ImageSY($im)) {
	   	$f = ImageSX($im)/$maxsize;
	   	$breite_neu = $maxsize;
	   	$hoehe_neu = ImageSY($im)/$f;
		} else {
	   	$f = ImageSY($im)/$maxsize;
	   	$breite_neu = ImageSX($im)/$f;
	   	$hoehe_neu = $maxsize;
		}
	  if ($im2 = ImageCreateTrueColor($breite_neu,$hoehe_neu)) {
	   ImageCopyResampled ($im2, $im, 0, 0, 0, 0, $breite_neu, $hoehe_neu, ImageSX($im), ImageSY($im));
	   if (ImageJPEG($im2,$outputimage,$quality)) {
	   } else {
	   	$Return=false;
	   }
	   ImageDestroy($im2);
	  } else {
		$Return=false;	  
	  }//im2
		ImageDestroy($im);
	} else {
		$Return=false;	  
	}//im
	return $Return;
}

//gettext wrapper function
function ___($s,$convert=1) {
	//if convert == 1 then use display function, else return raw output
	if (tm_DEBUG_LANG()) global $debug_translated,$debug_not_translated,$debug_same_translated;
	//return $s;//disabled
	//return _($s);//native gettext
	//return __($s);//php_gettext emulation
	$translated=translate($s);//'eigene' gettext emulation! adapted from old squirrelmail
	$text=$translated[0];
	$match=$translated[1];//=0 no match, =1 match, =2 guess
	if (tm_DEBUG_LANG() && $match==0) {
		if (tm_DEBUG_LANG_LEVEL() == 1 || tm_DEBUG_LANG_LEVEL()==3) $text.="(-)";
		$debug_not_translated[]=$s;
	}
	if (tm_DEBUG_LANG() && $match==1) {
		if (tm_DEBUG_LANG_LEVEL() == 1 || tm_DEBUG_LANG_LEVEL()==3) $text.="(+)";
		$debug_translated[]=$s." ==&gt; ".$translated[0];
	}
	if (tm_DEBUG_LANG() && $match==2) {
		if (tm_DEBUG_LANG_LEVEL == 1 || tm_DEBUG_LANG_LEVEL==3) $text.="(~)";
		$debug_same_translated[]=$s." ==&gt; ".$translated[0];
	}
	//fix bug 3114550
	//https://sourceforge.net/tracker/index.php?func=detail&aid=3114550&group_id=190396&atid=933192
	//do not convert if used in images etc.
	//thx to tms-schmidt
	if ($convert===1) {
		$return=display($text);
	} else {
		$return=$text;
	}
	return $return;
}

function dbesc($val) {
	//escape function for mysql/dbs
	return mysql_real_escape_string($val);
}
function display($val) {
	//function to display strings and values from db
	$Return=htmlentities($val,ENT_QUOTES,"UTF-8",false);
	return $Return;
}

function undoMagicQuotes($array, $topLevel=true) {
    $newArray = array();
    foreach($array as $key => $value) {
        if (!$topLevel) {
            $key = stripslashes($key);
        }
        if (is_array($value)) {
            $newArray[$key] = undoMagicQuotes($value, false);
        } else {
            $newArray[$key] = stripslashes($value);
        }
    }
    return $newArray;
}

function remove_old_admin_files() {
	$path=TM_ADMINPATH_TMP;
	$Return="";
	$F=Array();
	$dc=0;
	$handle=opendir($path);
	while (($filename = readdir($handle))!==false) {
		if (($filename != ".") && ($filename != "..")) {
			$file=$path."/".$filename;
			if (is_file($file) && $filename!=".htaccess" && $filename!="phpids.cache" && $filename!="phpids.log") {
				#$F[$dc]['file']=$file;
				#$F[$dc]['name']=$filename;
				#$F[$dc]['time']=filemtime($file);
				#$F[$dc]['now']=time();
				#$F[$dc]['diff']=$F[$dc]['now']-$F[$dc]['time'];
				#$F[$dc]['date']=date ("F d Y H:i:s.", $F[$dc]['time']);
				#echo $dc.": ".$F[$dc]['name']." ".$F[$dc]['date']." ".$F[$dc]['now']." - ".$F[$dc]['time']." = ".$F[$dc]['diff']."  ";
				#if ($F[$dc]['diff'] > 5) {
				$diff=time()-filemtime($file);
				if ( $diff > 6) { // wenn datei aelter als 6 sekunden, löschen!
					#echo "UNLINK ".$Return[$dc]['file'];
					#unlink ($Return[$dc]['file']);
					$Return.="".$filename." (".$diff."s) removed!\n";
					unlink ($file);
				} else {
					$Return.="".$filename." (".$diff."s)\n";
				}
				#echo "<br>";
				#$dc++;
			}
		}
	}
	@closedir($handle);
	return $Return;
}

function tm_icon($iconname,$title="",$alt="",$id="",$bgcolor="",$bgimage="") {
	global $tm_iconURL;
	if (empty($iconname)) $iconname="asterisk_orange.png";
	if (empty($title)) $title=___("Kein Titel");
	if (empty($alt)) $alt=$title;
	if (empty($id)) $id="icon_".rand(111111,999999);
	$Return="";
	$Return.=  "<img src=\"".$tm_iconURL."/".$iconname."\" title=\"".$title."\" border=\"0\" alt=\"Icon: ".$alt."\"";
	$Return.=" id=\"".$id."\"";
	if (!empty($bgcolor) || !empty($bgimage)) $Return.=" style=\"";
	if (!empty($bgcolor)) $Return.=" background-color:".$bgcolor.";";
	if (!empty($bgimage)) $Return.=" background-image: url(".$tm_iconURL."/".$bgimage.");";
	if (!empty($bgcolor) || !empty($bgimage)) $Return.="\"";
	//bubble+fader
	$Return.=" onmouseover=\"switchSection('sprechblase');doc_writer('sprechblase_text','".$title."');\" onmouseout=\"switchSection('sprechblase');\" ";
	$Return.=">";
	return $Return;
}

//fetch current messages and news from tellmatic homepage
function getMessages() {
	$msg="";  
  $file_source = "http://www.tellmatic.org/MESSAGES";
  if (($msg = file_get_contents($file_source)) === FALSE) {
  	return "<br><font color=red>".___("Kann Tellmatic News nicht öffnen.").": <a href=\"http://www.tellmatic.org/MESSAGES\" target=\"_blank\">http://www.tellmatic.org/MESSAGES</a></font>";
  }
  return $msg;
}

//fetch current version from tellmatic homepage
function getCurrentVersion() {
	$ver="";
	$file_source = "http://www.tellmatic.org/CURRENT_VERSION";
	if (($ver = file_get_contents($file_source)) === FALSE) {
		return "<br><font color=red>".___("Kann Tellmatic Versionsinfo nicht öffnen").": <a href=\"http://www.tellmatic.org/CURRENT_VERSION\" target=\"_blank\">http://www.tellmatic.org/CURRENT_VERSION</a></font>";
	}
  return $ver;
}

//fetch directory names in path, returns an array of paths, no subdirectories
function getCSSDirectories($path) {
	clearstatcache();
	$Return=Array();
	$dc=0;
	$handle=opendir($path);
	while (($file = readdir($handle))!==false) {
		if (($file != ".") && ($file != "..")) {
			#if (opendir($path."/".$file)) {
			if (is_dir($path."/".$file)) {
				$Return[$dc]=$file;
				$dc++;
				#closedir($path."/".$file);
			}
		}
	}
	@closedir($handle);
	return $Return;
}


//removes empty indexes from array, needed sometime...
//http://www.php.net/manual/en/function.array-splice.php
function array_remove_empty($inarray) {
      if (is_array($inarray)) {
          foreach($inarray as $k=>$v) {
              if (!(empty($v))) {
                  $out[$k]=$v;
              }
          }
          return $out;
      } else {
          return $inarray;
      }
}

//unset array index and rehash the array! php has no native function for this, argh.
//http://www.php.net/manual/en/function.array-splice.php
//wwird function only accepts integer indexes....hmmmm
function array_unset($array,$index) {
  // unset $array[$index], shifting others values
  $res=array();
  $i=0;
  foreach ($array as $item) {
    if ($i!=$index)
      $res[]=$item;
    $i++;
  }
  return $res;
}

//get domainname from email
function getDomainFromEmail($str) {
	$return="";
	$domain=explode("@",$str,2);//use explode instead of split, php5.3
	if (isset($domain[1])) {
		$return=$domain[1];	
	} else {
		//as is
		$return=$domain[0];
	}
	return $return;
}

//calculate x,y coordinates from londitude $lon and latitude $lat depending on image size $width and $height
function getlocationcoords($lat, $lon, $width, $height)
{
   $x = (($lon + 180) * ($width / 360));
   $y = ((($lat * -1) + 90) * ($height / 180));
   return array("x"=>round($x),"y"=>round($y));
}

//fetch ip address
function getIP() {
/* taken from: php-magazin 05-07 (de) S. 38, by C.Fraunholz */
/* modified by vizzy for tellmatic */
	if (!(isset($_SERVER['HTTP_VIA']) || isset($_SERVER['HTTP_CLIENT_IP']))) {
		$ip = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
	} else { //PROXY!
		$proxy_ip = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
		//fixed bug: https://sourceforge.net/tracker/?func=detail&aid=3114571&group_id=190396&atid=933192
		//bug id: 3114571
		//thx to tms-schmidt		
		#$token1 = (int) substr($_SERVER['HTTP_X_FORWARDED_FOR'],0,strpos($_SERVER['HTTP_X_FORWARDED_FOR'],"."));		
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$token1 = (int) substr($_SERVER['HTTP_X_FORWARDED_FOR'],0,strpos($_SERVER['HTTP_X_FORWARDED_FOR'],"."));
		}
		
		$tokenc1="0.0.0.0";		
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$tokenc1 = (int) substr($_SERVER['HTTP_CLIENT_IP'],0,strpos($_SERVER['HTTP_CLIENT_IP'],"."));
		} else {
			if (isset($_SERVER['HTTP_VIA'])) {
				$tokenc1 = (int) substr($_SERVER['HTTP_VIA'],0,strpos($_SERVER['HTTP_VIA'],"."));
			}
		}
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !($token1 == 10 || $token1 == 192 || $token1 == 127 || $token1 == 224) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { // Proxy is nicht lokal (Firewall oder Transparent-Proxy)
			$ip = long2ip(ip2long($_SERVER['HTTP_X_FORWARDED_FOR']));
		} elseif(isset($_SERVER['HTTP_CLIENT_IP']) && !($tokenc1 == 10 || $tokenc1 == 192 || $tokenc1 == 127 || $tokenc1 == 224)) {
			$ip = long2ip(ip2long($_SERVER['HTTP_CLIENT_IP']));
		} else {
			$ip = $proxy_ip;
		}
	}
	$komma = strpos($ip,",");
	if ($komma !== false) $ip = substr($ip,0,$komma);
	return $ip;
}

//read file and put each line in an array
function readFileInArray($file) {
	$rows=Array();
	if (file_exists($file)) {
		$uf=fopen($file,"r");
		if ($uf)  {
		   while(!feof($uf))
	 		{
				$rows[]=fgets($uf, 4096);
			}
			fclose($uf);
		}//if uf
	}//file_exists
	return $rows;
}

//internal tellmatic date 2 string conversion function
function date_convert_to_string($date) {
	//ersetzt leerzeichen etc, damit wir das datum in den dateinamen einbauen koennen...
	//date_convert_to_string("Y-m-d H:i:s")
	$date=str_replace(".","",$date);
	$date=str_replace("-","",$date);
	$date=str_replace(":","",$date);
	$date=str_replace("_","",$date);
	$date=str_replace(" ","",$date);
	return $date;
}

//make microtime string
function mk_microtime($timestamp,$Y=0,$M=5,$D=8,$H=11,$I=14,$S=17) {
	//2011-04-05 16:48:00
	//0123-56-89 12:45:78	
	//returns date-time string from timestamp
	$Year=substr($timestamp,$Y,4);
	$Month=substr($timestamp,$M,2);
	$Day=substr($timestamp,$D,2);
	$Hour=substr($timestamp,$H,2);
	$Minute=substr($timestamp,$I,2);
	$Second=substr($timestamp,$S,2);
   return mktime($Hour,$Minute,$Second,$Month,$Day,$Year);
   
}

//sort an array, e.g. for displaying sorted addresslists in tables etc.
function sort_array($ARR,$sf="",$sfs=0) {
//3 parameter, mdim-Array zb $ARR[$i]['name'], Sortierindex zB.'name', sfs=1 desc, =0 asc
	$fcc=count($ARR);
	if (empty($sf) || $fcc<1) {
		return $ARR;
	}
	foreach ($ARR as $field) {
		$sort[]=$field[$sf];
	}
	//sfs: sort file desc: 1, else asc: 0
	if ($sfs==1) {
		@array_multisort($sort, SORT_DESC, $ARR, SORT_DESC);
	} else {
		@array_multisort($sort, SORT_ASC, $ARR, SORT_ASC);
	}
	return $ARR;
}

//calculate bytes from 8M 8K or whatever ini_get returns......
function calc_bytes($pms) {
	if (preg_match('/^([\d\.]+)([gmk])?$/i', $pms, $m)) {
  		$value = $m[1];
  		if (isset($m[2])) {
    		switch(strtolower($m[2])) {
	      		case 'g': $value *= 1024;  # fallthrough
	      		case 'm': $value *= 1024;  # fallthrough
	      		case 'k': $value *= 1024; break;
	      		default: $value=0;
	      	}
	    }
		return $value;
	} else {
		return 0;
	}

}

//strip blanks and spaces from beginning and end of a string
function utrim($source){
 $temp = ltrim($source);
 $temp = rtrim($temp);
 $temp = trim($temp);

 return $temp;
}

//write content to file
function write_file($file_path,$file_name,$file_content="") {
//3 parameters: path, filename, content
	$fp = fopen($file_path."/".$file_name,"w");
	if (!$fp) {
			return false;
	} else {
		fputs($fp,$file_content,strlen($file_content));
	}
	fclose($fp);
	chmod ($file_path."/".$file_name, 0644);
	return true;
}

//update existing file
function update_file($file_path,$file_name,$file_content="") {
//3 parameters: path, filename, content
	$fp = fopen($file_path."/".$file_name,"a");
	if (!$fp) {
			return false;
	} else {
		fputs($fp,$file_content,strlen($file_content));
	}
	fclose($fp);
	chmod ($file_path."/".$file_name, 0664);
	return true;
}

function append_file($file_path,$file_name,$file_content="") {
	update_file($file_path,$file_name,$file_content);
}

//get variables
function getVar($Var,$slashes=0,$default="",$global=0) {
    //this function gets variables from post or get or session, post has higher priority!
    //order is post, get, session
    //3 parameters: variablename, add slashes? (1 if the function should add slashes to the returning value), default value (if $$Var is empty)
    if (isset($_POST[$Var]) && !is_array($_POST[$Var])) {
	    $Return = $_POST[$Var];
      	#$Return = (get_magic_quotes_gpc()) ? stripslashes($_POST[$Var]) : $_POST[$Var];
    } else {
        if (isset($_GET[$Var])) {
			$Return = $_GET[$Var];
	   		#$Return = (get_magic_quotes_gpc()) ? stripslashes($_GET[$Var]) : $_GET[$Var];
        } else {
    	    if (isset($_SESSION[$Var])) {
				$Return = $_SESSION[$Var];
		   		#$Return = (get_magic_quotes_gpc()) ? stripslashes($_SESSION[$Var]) : $_SESSION[$Var];
    	    } else {
				#$Return=$default;
				//if nothing set, try to get global value
				if ($global==1) {
	    	    	if (isset($GLOBALS[$Var])) {
						$Return = $GLOBALS[$Var];
    	    		} else {
						$Return=$default;
					}
				} else {
					$Return=$default;
				}
			}
		}
    }
    if ($slashes==1)   {
		$Return = addslashes($Return);
	}
	return $Return;
}//getVar()


//Liste von e-Mails checken
function checkEmailList($eMailList,$debug=0,$level=2) {
	//3 parameters: list of comma-separated emails, $debug?, e mail check level
	if (!empty($eMailList)) {
		$Return=true;
		//zerlege string fwd in bestandteile durch komma getrennt
		$eMailListArray=explode(",",$eMailList);
		$Count=count($eMailListArray);
		$Message="";
		$Message.="<br>".sprintf(___("Prüfe Adressen: (%s)"),$Count)."<br>";

		for ($Counter=0;$Counter<$Count;$Counter++) {
			//pruefe einzelne adresse
			$Message.=$Counter.".:".$eMailListArray[$Counter];
			//wird einmal falsches ergebnis ausgegeben, false setzen
			$check_mail=checkEmailAdr($eMailListArray[$Counter],$level);
			if (!$check_mail[0]) {
				$Return=false;
				$Message.="...........<b>".___("Fehler")." ".$check_mail[1]."</b>";
			} else {
				$Message.="...........".___("OK");
			}
			$Message.="<br>";
		}
		if ($debug==1) {
			echo tm_message_debug($Message);
		}
	} else {
		//wenn leer ok
		$Return=true;
	}//!empty
	return $Return;
}

//start zlib http compression if available.
function m_obstart() {
$encode = getenv("http_ACCEPT_ENCODING");
if(ereg("gzip",$encode)) {
		//ob_start("ob_gzhandler");
		ob_start("zlib.output_handler");
	} else {
		ob_start();
	}
}

//generate a random password
function makeRandomPassword($length) {
  $salt = "abchefghjkmnpqrstuvwxyz0123456789-/_";
  srand((double)microtime()*1000000);
  	$i = 0;
  	$pass="";
  	while ($i <= $length) {
    		$num = rand() % 33;
    		$tmp = substr($salt, $num, 1);
    		$pass = $pass . $tmp;
    		$i++;
  	}
  	return $pass;
}


//pruefen auf gueltiges flag 1 oder 0 fuer db
function check_flag($value) {
	$return=false;
	if (isset($value) && is_numeric($value)) {
		$value=intval($value);
		if (($value==0 || $value==1) ) {
			$return=true;
		}
	}
	return $return;
}

//pruefen auf gueltige dbid, >0, numerisch und nicht "" etc.
function check_dbid($value) {
	$return=false;
	if (isset($value) && is_numeric($value) && (int)$value==$value) {
		if (($value>=1) && $value!="") { // war <=0, changed 1085
			$return=true;
//			$return=$value;
		}
	}
	return $return;
}

function checkset_int($val,$default=0) {
	if (isset($val) && !empty($val) && is_numeric( $val ) && (int)$val == $val) {
		return $val;
	} else {
		return $default;
	}
}

//unify/grou/uniq an array of words so that each value will be existing only once
function unify_array($words) {
	$wcount=count($words);
	$wcc=0;
	$W=Array();;
	for ($wc=0;$wc<$wcount;$wc++) {
		$found=0;
		for ($wcc_=0;$wcc_<$wcc;$wcc_++) {
			if ($W[$wcc_]!=$words[$wc]) {
				if ($found!=1) {$found=0;}
			} else {
				$found=1;
			}
		}
		if ($found==0) {
			$W[$wcc]=$words[$wc];
			$wcc++;
		}
	}
	return $W;
}

//search if value exists in array
function is_in($search, $array) {
   $found = false;
   foreach ($array as $element) {
       if (strtolower($search) == strtolower($element)) {$found = true;}
   }
   return $found;
}

//check for a valid numeric id
function checkid($id) {
	if (!empty($id) && is_numeric($id)) { // &&$id>0
		return TRUE;
	} else {
		return FALSE;
	}

}


//function to fetch array variables, e.g. if register_globals is off
function pt_register()
{
    $num_args = func_num_args();
    $vars = array();
    if ($num_args >= 2) {
        $method = strtoupper(func_get_arg(0));
        if (($method != 'SESSION') && ($method != 'GET') && ($method != 'POST') && ($method != 'SERVER') && ($method != 'COOKIE') && ($method != 'ENV')) {
            die('The first argument of pt_register must be one of the following: GET, POST, SESSION, SERVER, COOKIE, or ENV');
        }
//        $varname = "HTTP_{$method}_VARS";
			//30012007:
			$varname = '_'.$method;
        global ${$varname};
        for ($i = 1; $i < $num_args; $i++) {
            $parameter = func_get_arg($i);
            if (isset(${$varname}[$parameter])) {
                global $$parameter;
                //${$varname}[$parameter] = (get_magic_quotes_gpc()) ? ${$varname}[$parameter] : addslashes(${$varname}[$parameter]);
				$$parameter = ${$varname}[$parameter];
            }
        }
    } else {
        die('You must specify at least two arguments');
    }
}
// register a GET var
//pt_register('GET', 'user_id', 'password');
// register a server var
//pt_register('SERVER', 'PHP_SELF');
// register some POST vars
//pt_register('POST', 'submit', 'field1', 'field2', 'field3');

//create hex color value from rgb color values
function hexColor($color) {
	//color is an array: color[0]=red, color[1]=green, color[2]=blue
  $HEX=dechex(($color[0]<<16)|($color[1]<<8)|$color[2]);
  if (strlen($HEX) < 6) {
  	$hlen=(6-strlen($HEX));
  	for ($h=0;$h<$hlen;$h++) {
  		$HEX .="0";
  	}
  }
  return $HEX;
}

//2003-08-13
//old experimental clear_text function to make clear text not containing various special chars
function clear_text($x) {
	$x=strip_htmltags($x);
	$x=strip_tags($x);
	$x=strip_specialchar($x);
	$x=trim($x);
	return $x;
}

//2003-07-14
//old experimental clear_text function to make clear text not containing various special chars
function strip_htmltags($html) {
   $pos1 = false;
   $pos2 = false;
   do {
       if ($pos1 !== false && $pos2 !== false) {
           $first = NULL;
           $second = NULL;
           if ($pos1 > 0)
                $first = substr($html, 0, $pos1);
           if ($pos2 < strlen($html) - 1)
               $second = substr($html, $pos2);
          $html = $first . $second;
       }
      preg_match("/<script[^>]*>/i", $html, $matches);
      $str1 =& $matches[0];
      preg_match("/<\/script>/i", $html, $matches);
      $str2 =& $matches[0];
      $pos1 = strpos($html, $str1);
     $pos2 = strpos($html, $str2);
      if ($pos2 !== false)
          $pos2 += strlen($str2);
   } while ($pos1 !== false && $pos2 !== false);
   return $html;
}

function strip_specialchar($x) {
//old experimental clear_text function to make clear text not containing various special chars
	$x=trim($x);
	//$x=str_replace ( ".", "", $x);
	$x=str_replace ( "\\", "", $x);
	//$x=str_replace ( "+", "", $x);
	$x=str_replace ( "*", "", $x);
//	$x=str_replace ( "?", "", $x);
//	$x=str_replace ( "[", "", $x);
	$x=str_replace ( "^", "", $x);
//	$x=str_replace ( "]", "", $x);
	$x=str_replace ( "$", "", $x);
//	$x=str_replace ( "(", "", $x);
//	$x=str_replace ( ")", "", $x);
//	$x=str_replace ( "{", "", $x);
//	$x=str_replace ( "}", "", $x);
//	$x=str_replace ( "=", "", $x);
	$x=str_replace ( "!", "", $x);
	//$x=str_replace ( "<", "", $x);
	//$x=str_replace ( ">", "", $x);
	$x=str_replace ( "|", "", $x);
	//$x=str_replace ( ":", "", $x);
//	$x=str_replace ( "&", "", $x);
	$x=str_replace ( "\"", "", $x);
	$x=str_replace ( "%", "", $x);
	//$x=str_replace ( "=", "", $x);
	//$x=str_replace ( "/", "", $x);
	$x=str_replace ( "'", "", $x);
	$x=str_replace ( "Ž", "", $x);
	$x=str_replace ( "`", "", $x);
	$x=str_replace ( "~", "", $x);
	return $x;
}


function SendMail($from_address,$from_name,$to_address,$to_name,$subject,$text,$html,$AttmFiles=Array(),$HOST=Array()){
	global $use_SMTPmail;//tm_lib!
	$return=false;
	//Name darf nicht = email sein und auch kein komma enthalten, plaintext!
	$to_name=str_replace($to_address,"",$to_name);
	$to_name=clear_text($to_name);
	$to_name=str_replace(",","",$to_name);
	if ($use_SMTPmail) {
	 	if (SendMail_smtp($from_address,$from_name,$to_address,$to_name,$subject,$text,$html,$AttmFiles,$HOST)) {
	 		$return=true;
	 	}
	 } else {
	 	if (SendMail_mail($from_address,$from_name,$to_address,$to_name,$subject,$text,$html,$AttmFiles)) {
	 		$return=true;
	 	}
 	}
 	return $return;
}

function SendMail_smtp($from_address,$from_name,$to_address,$to_name,$subject,$text,$html,$AttmFiles=Array(),$HOST=Array()) {
	global $encoding,$tm_URL_FE;//use default encoding
	$return=false;
	if (!isset($HOST[0])) {
		$HOSTS=new tm_HOST();
		$HOST=$HOSTS->getStdSMTPHost();
	}
	//using http://www.phpclasses.org/trackback/browse/package/9.html http://www.phpclasses.org/browse/package/14.html http://www.phpclasses.org/trackback/browse/package/14.html
	//Class: MIME E-mail message composing and sending
	//thx to Manuel Lemos <mlemos at acm dot org>
	//look at: http://freshmeat.net/projects/mimemessageclass/
	#require_once(TM_INCLUDEPATH_CLASS."/Class_SMTP.inc.php");
	$email_message=new smtp_message_class;
	$email_message->default_charset=$encoding;
	$email_message->authentication_mechanism=$HOST[0]['smtp_auth'];;
	/* This computer address */
	$email_message->localhost=$HOST[0]['smtp_domain'];
	$email_message->ssl=$HOST[0]['smtp_ssl'];
	/* SMTP server address, probably your ISP address */
	$email_message->smtp_host=$HOST[0]['host'];
	$email_message->smtp_port=$HOST[0]['port'];
	/* authentication user name */
	$email_message->smtp_user=$HOST[0]['user'];
	/* authentication realm or Windows domain when using NTLM authentication */
	$email_message->smtp_realm="";
	/* authentication workstation name when using NTLM authentication */
	$email_message->smtp_workstation="";
	/* authentication password */
	$email_message->smtp_password=$HOST[0]['pass'];
	/* if you need POP3 authetntication before SMTP delivery,
	 * specify the host name here. The smtp_user and smtp_password above
	 * should set to the POP3 user and password*/
	$email_message->smtp_pop3_auth_host="";
	/* Output dialog with SMTP server */
	$email_message->smtp_html_debug=0;
	/* if smtp_debug is 1,
	 * set this to 1 to make the debug output appear in HTML */
		if (TM_DEBUG_SMTP) {
			$email_message->smtp_debug=1;
		} else {
			$email_message->smtp_debug=0;
			$email_message->smtp_html_debug=0;
		}
	$email_message->maximum_piped_recipients=$HOST[0]['smtp_max_piped_rcpt'];//sends only XX rcpt to before waiting for ok from server!
	$email_message->mailer=TM_APPTEXT." using smtp";
	$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
	$email_message->SetEncodedEmailHeader("From",$HOST[0]['sender_email'],$HOST[0]['sender_name']);
	$email_message->SetEncodedEmailHeader("Reply-To",$HOST[0]['reply_to'],$HOST[0]['sender_name']);
	$email_message->SetHeader("Return-Path",$HOST[0]['return_mail']);
	$email_message->SetEncodedEmailHeader("Errors-To",$HOST[0]['return_mail'],$HOST[0]['sender_name']);
	$email_message->SetEncodedHeader("List-Unsubscribe",$tm_URL_FE."/unsubscribe.php");
	$email_message->SetEncodedHeader("Subject",$subject);
	$partids=array();//array of partnumbers, returned by reference from createpart etc
	//we want mixed multipart, with alternative text/html and attachements, inlineimages and all that
	//text part must be the first one!!!	//only add part
	$email_message->CreateQuotedPrintableTextPart($text,"",$partids[]);
	$email_message->CreateQuotedPrintableHtmlPart($html,"",$partids[]);
	//AddAlternativeMultiparts
	$email_message->AddAlternativeMultipart($partids);
	//attachements
	//filenames stored in Array $AttmFiles
	if($AttmFiles){
	 foreach($AttmFiles as $AttmFile){
		$attachment=array(
			"FileName"=>$AbsPath."/".$AttmFile,
			"Content-Type"=>"automatic/name",
			"Disposition"=>"attachment"
		);
		$email_message->AddFilePart($attachment);
		}//for each
	}//ifAttmFiles
	$error=$email_message->Send();
	for($recipient=0,Reset($email_message->invalid_recipients);$recipient<count($email_message->invalid_recipients);Next($email_message->invalid_recipients),$recipient++)
		echo "Invalid recipient: ",Key($email_message->invalid_recipients)," Error: ",$email_message->invalid_recipients[Key($email_message->invalid_recipients)],"\n";
	if(strcmp($error,"")) {
		$return[0]=false;
		$return[1]=$error;
	}
	if (empty($error)) {
		$return[0]=true;
		$return[1]="";
	}
	return $return;
}


function SendMail_mail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles){
	/*
	$From      ... sender mail address like "my@address.com"
	$FromName  ... sender name like "My Name"
	$To        ... recipient mail address like "your@address.com"
	$ToName    ... recipients name like "Your Name"
	$Subject   ... subject of the mail like "This is my first testmail"
	$Text      ... text version of the mail
	$Html      ... html version of the mail
	$AttmFiles ... array containing the filenames to attach like array("file1","file2")

	$TEXT="This is the first test\n in text format\n.";
	$HTML="<font color=red>This is the first test in html format.</font>";
	$ATTM=array("/home/myself/test/go.jpg",
						"/home/myself/test/SomeDoc.pdf");

	SendMail(
	"my@address.com","PHP Apache Webmailer", //sender
	"your@address.com","Recipients Name",    //recipient
	"Testmail",                               //subject
	$TEXT,$HTML,$ATTM);                      //body and attachment(s)
	*/

//Name darf nicht = email sein und auch kein komma enthalten, plaintext!
	$ToName=str_replace($To,"",$ToName);
	$ToName=clear_text($ToName);
	$ToName=str_replace(",","",$ToName);

	$OB="----=_OuterBoundary_000".md5(rand());
	$IB="----=_InnerBoundery_001".md5(rand());

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .="Return-Path: <".$From.">\r\n";
	$headers .= "From: ".$FromName." <".$From.">\r\n";
	$headers .="To: ".$ToName." <".$To.">\r\n";
	$headers .="Reply-To: ".$FromName." <".$From.">\r\n";
	$headers .= "X-Mailer: ".TM_APPTEXT."\r\n";
	$headers .="Content-Type: multipart/mixed;\n\t";
	$headers.="boundary=\"".$OB."\"\r\n";

	//Messages start with text/html alternatives in OB
	$Msg ="This is a multi-part message in MIME format.\n";
	$Msg.="\n--".$OB."\n";
	$Msg.="Content-Type: multipart/alternative;\n\tboundary=\"".$IB."\"\n\n";

	//plaintext section
	$Msg.="\n--".$IB."\n";
	$Msg.="Content-Type: text/plain;\n\tcharset=\"iso-8859-1\"\n";
	$Msg.="Content-Transfer-Encoding: quoted-printable\n\n";
	// plaintext goes here
	$Msg.=$Text."\n\n";
	// html section
	$Msg.="\n--".$IB."\n";
	$Msg.="Content-Type: text/html;\n\tcharset=\"iso-8859-1\"\n";
	$Msg.="Content-Transfer-Encoding: base64\n\n";
	// html goes here
	$Msg.=chunk_split(base64_encode($Html))."\n\n";

	// end of IB
	$Msg.="\n--".$IB."--\n";

	// attachments
	if($AttmFiles){
	 foreach($AttmFiles as $AttmFile){
		$patharray = explode ("/", $AttmFile);
		$FileName=$patharray[count($patharray)-1];
		$Msg.= "\n--".$OB."\n";
		$Msg.="Content-Type: application/octetstream;\n\tname=\"".$FileName."\"\n";
		$Msg.="Content-Transfer-Encoding: base64\n";
		$Msg.="Content-Disposition: attachment;\n\tfilename=\"".$FileName."\"\n\n";

	 //file goes here
		$fd=fopen ($AttmFile, "r");
		$FileContent=fread($fd,filesize($AttmFile));
		fclose ($fd);
		$FileContent=chunk_split(base64_encode($FileContent));
		$Msg.=$FileContent;
		$Msg.="\n\n";
	 }
	}

//message ends
	$Msg.="\n--".$OB."--\n";
	 ini_set('sendmail_from', $From);
	if (mail($To,$Subject,$Msg,$headers)) {
		//send syslog message
		syslog(LOG_INFO,"Mail sent from $From  to $ToName <$To> ");
		ini_restore('sendmail_from');
		return true;
	} else {
		ini_restore('sendmail_from');
		return false;
	}
}
//SendMail END

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

function configure_me() {
	return true;
}

function checkEmailAdr($email,$level=1,$from="") {
	$Return[0]=true;//ok, weil mit level=0 oder >3 pruefung ausgeschaltet werden kann.....
	$Return[1]="";
	//default level ist 2, mx dns und sytax!

	if ($level>=1) {
		//syntax
		//$regex="([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}";
		// ^^^^^^^^^ findet auch adresen innerhalb des textes!
		//$regex="^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$"; // hat probleme mit a.-b.cde@x-yz.td
		//2007-05-03		: http://www.markussipila.info/pub/emailvalidator.php?action=validate
		
		//for eregi:
		$regex_eregi="^[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+(\.[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+)*(\.{0,1})@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,})$";
		$regex_preg ="^[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+(\.[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+)*(\.{0,1})\@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,})$";
		//oder auch: "^[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.{0,1})@[_a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,3}|\.info|\.gouv|\.name|\.museum)\$"
		//von: http://www.zend.com/zend/spotlight/ev12apr.php?article=ev12apr&kind=sl&id=12978&open=0&anc=0&view=1#notes
		//if(ereg ("<($regex)>", $email, $email_V))
		//if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $email, $check)) {
		
		/*
		yes! i know this regex has bugs, and its not possible to write a regex that nearly matches all valid emails. and this regex may fail with unicode domains. 
		so, please, instead of complaining about that, tell me a better solution :) critics are welcome!
		
		*/
		/*	
				$email = "support@الصفحة_الرئيسية";
		if (!preg_match("@^[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+(\.[a-z0-9,!#\$%&'\*\+/=\?\^_`\{\|}~-]+)*(\.{0,1})\@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,})$@",$email)) echo "ohnoes! we failed to validate a <b>valid</b> email address...";
			^fails!
		*/
		
		#if (preg_match($regex_preg,$email)) {
		if (eregi("$regex_eregi", $email, $check)) {
			$Return[0]=true;
			$Return[1]=___("E-Mail Syntax OK");
		} else {
			$Return[0]=false;
			$Return[1]=___("Syntaxfehler");
			return $Return;//und weg
		}
	}

	if ($level>=2) {
		//split array
		list($userName, $mailDomain) = explode("@", $email,2);//use explode instead of split, php5.3
		/*
		//das ganze is nich ganz rfc konform.....
		//mx
		$mx=getmxrr($mailDomain,$mxhosts);//print_r($mxhosts);
		if($mx==1) {
		//if(getmxrr(substr(strstr($check[0], '@'), 1), $validate_email_temp)) {
			$Return[0]=true;
		} else {
			$Return[0]=false;
			$Return[1]=___("Kein MX Eintrag");
			return $Return;
		}
		//dns
		$dns=checkdnsrr($mailDomain, "MX");
		//checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY")
		if ($dns==1) {
			$Return[0]=true;
		} else {
			$Return[0]=false;
			$Return[1]=___("MX DNS Problem");
			return $Return;
		}
		*/
		//unter beruecksichtigung der rfc darf es bei fehlendem mx zu keinem fehler kommen....
		//check mx+dns
		//true if mx is found, if no mx is found check ANY dns entry
		$mx=getmxrr($mailDomain,$mxhosts);//print_r($mxhosts);
		if($mx==1) {
			$Return[0]=true;
		} else {//if mx
			#$Return[1]=___("Kein MX Eintrag");
			$dns=checkdnsrr($mailDomain, "ANY");
			if ($dns==1) {
				$Return[0]=true;
			} else {
				$Return[0]=false;
				$Return[1]=___("MX DNS Problem");
				return $Return;
			}//if dns
		}//if mx
	}

	//validate()
	//fragt mx ab, wenn domain aber noch kein dns eintrag hat, weil gerade erzeugt......schlaegt der check fehl....
	if ($level>=3) {
		$Return=validate_email($email,$from);
	}
	return $Return;
}

function validate_email($email,$from="") {
	$protocol="";
	$HOSTS=new tm_HOST();
	$HOST=$HOSTS->getStdSMTPHost();

	if (empty($from)) {
		$from=$HOST[0]['sender_email'];
	}
	
	$Return=Array(0=>true, 1=>"OK");
	list($userName, $mailDomain) = explode("@", $email,2);//use explode instead of split, php5.3
	$protocol.="from: ".$from." \n";
	$protocol.="to: ".$email." \n";
	$mx=getmxrr($mailDomain,$mxhosts);
	$hc=count($mxhosts);
	if ($hc>0) {
		//wir preuefen nur mal den ersten mx
		$host=$mxhosts[0];
		$protocol.=$host." \n";
		//added @ to suppress possible error messages, thx to tms-schmidt
		//fixed bug id: 3114571
		//https://sourceforge.net/tracker/?func=detail&aid=3114571&group_id=190396&atid=933192
		$Connect = @fsockopen ( $host, 25, $errno, $errstr, 0.5);//timeout 0,5 sec
		usleep(100000);
		if ($Connect) {
	        //aol hack
	        /*
	        //schwachsinnig! gibt schleife bis 220... ohoh
	        do {
					 $Out = fgets ( $Connect, 1024 );
				} while (ereg("^220",$Out));
	        */
	        if (ereg("^220", $Out = fgets($Connect, 1024))) {
	        //aol: if (ereg("^220", $Out)) {
 				usleep(100000);
	           fputs ($Connect, "HELO ".$HOST[0]['smtp_domain']."\r\n");
	           $Out = fgets ( $Connect, 1024 );
	           $protocol.=$Out." \n";
				usleep(100000);
	           fputs ($Connect, "MAIL FROM: $from\r\n");
	           $From = fgets ( $Connect, 1024 );
					$protocol.=$From." \n";
				usleep(100000);
	           fputs ($Connect, "RCPT TO: $email\r\n");
	           $To = fgets ($Connect, 1024);
	           $protocol.=$To." \n";
				usleep(100000);
	           fputs ($Connect, "QUIT\r\n");
	           fclose($Connect);
	           //http://www.faqs.org/rfcs/rfc821.html
	           if (!ereg ("^250", $From) || !ereg ( "^250", $To )) {
	           	if (ereg ("^4", $From) || ereg ( "^4", $To )) {
	           		$Return[1]=___("Server lehnt Mail ab, temporaerer Fehler, Greylisting etc.");#."\n".$protocol."\n";
	           	} else {
	               $Return[0]=false;
	               $Return[1]=___("Server meldet Fehler, Adresse abgelehnt");#."\n".$protocol."\n";
					}
	           }
	        } else {
	            $Return[0] = false;
	            $Return[1] = ___("Keine Antwort vom Server.");#."\n".$protocol."\n";
           }
	   }  else {
	       $Return[0]=false;
	       $Return[1]=___("Kann Verbindung zum Server nicht herstellen.")." (".$errno." ".$errstr.")";#."\n".$protocol."\n";
	   }
	} else {
		//kein mx host gefunden $hc=0
	        $Return[0]=false;
	        $Return[1]=___("Kein MX Eintrag (Validate)");#."\n".$protocol."\n";
	}
	$Return[1] .="\n".$protocol."\n";
	return $Return;
}

function checkinput($input) {
	if (eregi("^[0-9a-z]", $input, $check)) {
		return true;
	}  else {
		return false;
	}
}

function get_file_ext( $filename ) {
	ereg( ".*\.([a-zA-z0-9]{0,5})$", $filename, $regs );
	return( $regs[1] );
}

function unhtmlentities ($string)
{
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}

/* function gen_rec_files_array ($path,$selected="",$recpath="",$extension="",$addcompletepath=0)
// erzeugt array mit einer liste der datein im angegebenen verzeichnis mit unterverzeichnissen
// * path: ist der gewuenschte pfad ab dem  angezeigt werden soll
// * selected: enthaelt den ausgewaehlten wert
// * extension: filter , z.Bsp.. 'jpg' etc...
// * addcomletepath: wenn 1,  fuegt den absoluten Pfad hinzu
// * recpath: recursive hilfsvariable : intern fuer recursives durchsuchen
// Verwendung z.Bsp edit_ConfigLayout, edit_Gallery_Image_Item.inc etc. und ueberall dort wo Dateien innerhalb eines Pfades als Selectfeld auszuwaehlen sind
*/
function gen_rec_files_array ($path,$selected="",$recpath="",$extension="",$addcompletepath=0) {
	global $Domain,$StdDir,$nowDir,$includefile,$upl,$PHP_SELF,$dir2;
	global $FileARRAY;
	if (is_dir($path)) {
		chdir($path);
		$handle=opendir('.');
		$ac=0;//voa 17072005
		while (($file = readdir($handle))!==false) {
			$rec="";
			if (($file != ".") && ($file != "..")) {
				if (is_file($file) or is_dir($file)) {
					if (!empty($recpath)) {
						$rec=$recpath;
					}
					$rec.="/".$file;
					if (is_file($file)) {
						if (!empty($extension) && $extension!='') {
							if (preg_match ("/".$extension."/i", $rec)) {
								if ($addcompletepath==1) {
									//$FileARRAY[$ac]['filename']=$path."/".$recpath."/".$rec;
									$FileARRAY[]['filename']=$path."/".$recpath."/".$rec;
								} else {
									//$FileARRAY[$ac]['filename']=$rec;
									$FileARRAY[]['filename']=$rec;
								}
							}
						} else {
							if ($addcompletepath==1) {
									//$FileARRAY[$ac]['filename']=$path."/".$recpath."/".$rec;
									$FileARRAY[]['filename']=$path."/".$recpath."/".$rec;
							} else {
									//$FileARRAY[$ac]['filename']=$rec;
									$FileARRAY[]['filename']=$rec;
							}
						}
					}
					$ac++;
					gen_rec_files_array ($path."/".$file,$selected,$rec,$extension,$addcompletepath);
					chdir($path);
				}
			}
		}
		@closedir($handle);
	}
}

//alternate getmxrr function for windows
if (PHPWIN && !function_exists('getmxrr')) {
	//see: http://www.php.net/manual/de/function.getmxrr.php
	function getmxrr($hostname, &$mxhosts)
	{
	    $mxhosts = array();
	    exec('%SYSTEMDIRECTORY%\\nslookup.exe -q=mx '.escapeshellarg($hostname), $result_arr);
	    foreach($result_arr as $line)
	    {
	      if (preg_match("/.*mail exchanger = (.*)/", $line, $matches))
	          $mxhosts[] = $matches[1];
	    }
	    return( count($mxhosts) > 0 );
	}
}

/*****************************************************************************************/
/*****************************************************************************************/
/*****************************************************************************************/
function getDirectories($path) {
	clearstatcache();	
	$Return=Array();
	$dc=0;
	$handle=opendir($path);
	while (($file = readdir($handle))!==false) {
		#if (($file != ".")) {//&& ($file != "..")) 
			#if (opendir($path."/".$file)) {
			if (is_dir($path."/".$file)) {
				$Return[$dc]['name']=$file;
				$Return[$dc]['files']=count(scandir($path."/".$file))-2;// - "." + ".."
				$dc++;
				#closedir($path."/".$file);
			}
		#}
	}
	@closedir($handle);
	return $Return;
}//getDirectories

/*****************************************************************************************/
/*****************************************************************************************/
/*****************************************************************************************/

function getFiles($path) {
	clearstatcache();
	$Return=Array();
	$fc=0;
	if (is_dir($path)) {
		$handle=opendir($path);
		while (($file = readdir($handle))!==false) {
			if (($file != ".") && ($file != "..") && is_file($path."/".$file)) {
				$Return[$fc]['name']=$file;
				$Return[$fc]['size']=filesize($path."/".$file);
				$Return[$fc]['ext']="";
				$fc++;
			}//is file
		}//readdir
		@closedir($handle);
	}//is_dir path	
	return $Return;
}//getFiles

/*****************************************************************************************/
/*****************************************************************************************/
/*****************************************************************************************/
//formats readable ouput to display filesizes in Byte/KByte or MByte dpepending on the size
function formatFileSize($val) {
	$Return=0;
	if ($val<1024) {
		$Return=$val." Byte";
	}
	//1048576=1M
	if ($val>=1024 && $val<1048576)	{
		$Return=number_format(($val/1024),2,".","")." KB";
	}
	if ($val>=(1048576))	{
		$Return=number_format(($val/1048576),2,".","")." MB";
	}
	return $Return;
}

 function getMime($file,$host='',$port=80)  { 
   $host = $host ? $host : TM_DOMAIN; 
	$host=str_replace("http://","",$host);
	$host=str_replace("https://","",$host);
 	#return $host." | ".$file;

   $response = send_http_request("HEAD $file HTTP/1.0\r\nUser-Agent: 
 ".TM_APPTEXT."\r\n". 
                                 "Host: $host:$port\r\n\r\n"); 
   if($response['header']['HTTP_STATUS_CODE'] == 200) { 
   	return $response['header']['HTTP_CONTENT-TYPE']; 
   } else { 
   	return $response['header']['HTTP_STATUS_CODE']; 
   } 
 } 

 function send_http_request($request='',$host='',$port=80)  { 
   $host = $host ? $host : TM_DOMAIN;
	$host=str_replace("http://","",$host);
	$host=str_replace("https://","",$host);
    if(!$fp=fsockopen($host, $port)) 
     return 0; 
   if (!fputs($fp, $request, strlen($request))) 
     return 0; 
	$data="";
   while(!feof($fp)) $data .= fread($fp, 256); //win32 limit 2048 
     fclose($fp); 
  
   $http_response = get_http_response($data); 
   return $http_response; 
} 
  
 function get_http_response($data)  { 
   $pos = strpos($data,"\r\n\r\n"); 
   $data = array(substr($data,0,$pos),substr($data,$pos+4)); 
   $tmp = explode("\r\n", $data[0]); 
   $response['content'] = $data[1]; 
   ereg("^(.*) ([[:digit:]]*) (.*)",$tmp[0],$http); 
   $response['header']['HTTP_VERSION']       = $http[1]; 
   $response['header']['HTTP_STATUS_CODE']   = $http[2]; 
   $response['header']['HTTP_REASON_PHRASE'] = $http[3]; 
   for($i=1;$i<count($tmp);$i++)  { 
     list($env,$value) = explode(':',$tmp[$i]); 
     $response['header']["HTTP_".strtoupper($env)] = ltrim($value); 
   } 
   return $response; 
} 
 
//taken from php.net/explode
// tajhlande at gmail dot com
//19-Jun-2007 05:28
//While trying to use explode() to parse CSV formatted lines output by MS Excel, I found that if cells contained a comma, then explode() would not behave as desired.  So I wrote the following function, which obeys the double quote escaping format output by Excel.  Note that it is not sophisticated enough to handle delimiters or escapes that consist of more than one character.  I also have no idea how this code will perform when subjected to Unicode data.  Use at your own risk.
// splits a string into an array of tokens, delimited by delimiter char
// tokens in input string containing the delimiter character or the literal escape character are surrounded by a pair of escape characteres
// a literal escape character is produced by the escape character appearing twice in sequence
// default delimiter character and escape character are suitable for Excel-exported CSV formatted lines
function splitWithEscape ($str, $delimiterChar = ',', $escapeChar = '"') {
    $len = strlen($str);
    $tokens = array();
    $i = 0;
    $inEscapeSeq = false;
    $currToken = '';
    while ($i < $len) {
        $c = substr($str, $i, 1);
        if ($inEscapeSeq) {
            if ($c == $escapeChar) {
                // lookahead to see if next character is also an escape char
                if ($i == ($len - 1)) {
                    // c is last char, so must be end of escape sequence
                    $inEscapeSeq = false;
                } else if (substr($str, $i + 1, 1) == $escapeChar) {
                    // append literal escape char
                    $currToken .= $escapeChar;
                    $i++;
                } else {
                    // end of escape sequence
                    $inEscapeSeq = false;
                }
            } else {
                $currToken .= $c;
            }
        } else {
            if ($c == $delimiterChar) {
                // end of token, flush it
                array_push($tokens, $currToken);
                $currToken = '';
            } else if ($c == $escapeChar) {
                // begin escape sequence
                $inEscapeSeq = true;
            } else {
                $currToken .= $c;
            }
        }
        $i++;
    }
    // flush the last token
    array_push($tokens, $currToken);
    return $tokens;
}
?>