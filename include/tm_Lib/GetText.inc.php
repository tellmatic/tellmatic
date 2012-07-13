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

/** adapted from original: **/

/**
 * SquirrelMail internal gettext functions
 *
 * Copyright (c) 1999-2005 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Alternate to the system's built-in gettext.
 * relies on .po files (can't read .mo easily).
 * Uses the session for caching (speed increase)
 * Possible use in other PHP scripts?  The only SM-specific thing is
 *   $sm_language, I think
 *
 * @link http://www.php.net/gettext Original php gettext manual
 * @version $Id: gettext.php,v 1.19.2.3 2004/12/27 15:03:43 kink Exp $
 * @package squirrelmail
 * @subpackage i18n
 */

function load_translateStrings($locale) {
	global $translateStrings;
	$filename=TM_PATH."/locale/tellmatic-".$locale.".po";
    $file = @fopen($filename, 'r');
    if ($file == false) {
		echo "Fatal Error. Cannot load required language file.";
    	echo "File not found: ".$filename;
    	exit;
    }
    $key = '';
    $SkipRead = false;
    while (! feof($file)) {
        if (! $SkipRead) {
            $line = trim(fgets($file, 4096));
        } else {
            $SkipRead = false;
        }
 //       if (ereg('^msgid "(.*)"$', $line, $match)) {
    	if (preg_match('/^msgid\s+"(.+?)"$/', $line, $match)) {
            if ($match[1] == '') {
                /*
                 * Potential multi-line
                 * msgid ""
                 * "string string "
                 * "string string"
                 */
                $key = '';
                $line = trim(fgets($file, 4096));
                while (ereg('^[ ]*"(.*)"[ ]*$', $line, $match)) {
                    $key .= $match[1];
                    $line = trim(fgets($file, 4096));
                }
                $SkipRead = true;
            } else {
                /* msgid "string string" */
                $key = $match[1];
            }
//        } elseif (ereg('^msgstr "(.*)"$', $line, $match)) {
        } elseif (preg_match('/^msgstr\s+"(.+?)"$/', $line, $match)) {
    		if ($match[1] == '') {
                /*
                 * Potential multi-line
                 * msgstr ""
                 * "string string "
                 * "string string"
                 */
                $translateStrings[$key] = '';
                $line = trim(fgets($file, 4096));
                while (ereg('^[ ]*"(.*)"[ ]*$', $line, $match)) {
                    $translateStrings[$key] .= $match[1];
                    $line = trim(fgets($file, 4096));
                }
                $SkipRead = true;
            } else {
                /* msgstr "string string" */
                $translateStrings[$key] = $match[1];
            }
            #$translateStrings[$key] =stripslashes($translateStrings[$key]);
            /* If there is no translation, just use the untranslated string */
            if ($translateStrings[$key] == '') {
                $translateStrings[$key] = $key;
                if (tm_DEBUG()) $translateStrings[$key].="()";
            }
            $key = '';
        }
    }
    fclose($file);
}

function translate($str) {
	global $translateStrings;
	$translated=Array();
    /* Try finding the exact string */
    if (isset($translateStrings[$str])) {
		$translated[0]=$translateStrings[$str];
		if ($translateStrings[$str]==$str) {
			$translated[1]=2;//2 same source=translated
    	} else {
			$translated[1]=1;//1=match
		}
    } else {
    	//guess?
       	#$translated[0]=guess($str);
		#$translated[1]=3;//3=guess
       	//return input 1:1
		$translated[0]=$str;
		$translated[1]=0;//0=no match

    }
    return $translated;
}

function guess($str) {
    /* Look for a string that is very close to the one we want
       Very computationally expensive */
    global $translateStrings;
    $oldPercent = 0;
    $oldStr = '';
    $newPercent = 0;
    foreach ($translateStrings as $k => $v) {
        similar_text($str, $k, $newPercent);
        if ($newPercent > $oldPercent) {
            $oldStr = $v;
            $oldPercent = $newPercent;
        }
    }
    /* Require 80% match or better
       Adjust to suit your needs */
    if ($oldPercent > 80) {
        /* Remember this so we don't need to search again */
        $translateStrings[$str] = $oldStr;
        return $oldStr;
    }

    /* Remember this so we don't need to search again */
    $translateStrings[$str] = $str;
    return $str;
}

?>