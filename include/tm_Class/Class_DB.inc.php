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

#require_once (TM_INCLUDEPATH."/db_mysql.inc.php");

if (!defined('TM_DEBUG_SQL')) { define('TM_DEBUG_SQL',FALSE); }

/*DB*/
/* moved to tm_lib
define ("TM_DB_NAME",$tm["DB"]["Name"]);
define ("TM_DB_PORT",$tm["DB"]["Port"]);
if ($tm["DB"]["Socket"]==1) {
	define ("TM_DB_HOST", $tm["DB"]["Host"]);
} else {
	define ("TM_DB_HOST", $tm["DB"]["Host"].":".TM_DB_PORT);
}
define ("TM_DB_USER",$tm["DB"]["User"]);
define ("TM_DB_PASS",$tm["DB"]["Pass"]);
unset($tm["DB"]);
*/

class tm_DB extends DB_Sql
{
	var $classname = "ConnectDB";
	var $Host     = TM_DB_HOST;
	var $Database = TM_DB_NAME;
	var $User     = TM_DB_USER;
	var $Password = TM_DB_PASS;
	var $Autofree = false;
	var $Debug = 0;
	var $PConnect = 0;//persistent

	function halt($msg)
	{
		global $SERVER_NAME;
		$Message = sprintf("<pre>SQL Error: %s (%s)\n<BR>%s\n</pre>", $this->Errno, $this->Error, $msg);
		echo("<br><font color=\"#ff0000\">Database Error in Database $this->Database:</font><font color=\"#3300ff\"><pre>".$Message."</pre></font>");
 	}
}

//however, the db class itself used here is very old, but works fine and very comfortable :)
//taken from phplib
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: db_mysql.inc,v 1.11 2002/08/07 19:33:57 layne_weathers Exp $
 *
 */

class DB_Sql {

  /* public: connection parameters */
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  /* public: configuration parameters */
  var $Auto_Free     = 0;     ## Set to 1 for automatic mysql_free_result()
  var $Debug         = 0;     ## Set to 1 for debugging messages.
  var $Halt_On_Error = "yes"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
  var $PConnect      = 0;     ## Set to 1 to use persistent database connections
  var $Seq_Table     = "db_sequence";

  /* public: result array and current row number */
  var $Record   = array();
  var $Row;

  /* public: current error number and error text */
  var $Errno    = 0;
  var $Error    = "";

  /* public: this is an api revision, not a CVS revision. */
  var $type     = "mysql";
  var $revision = "1.2";

	var $LastInsertID=0;//letzte id bei insert.

  /* private: link and query handles */
  var $Link_ID  = 0;
  var $Query_ID = 0;

  var $locked   = false;      ## set to true while we have a lock



  /* public: constructor */
  function DB_Sql($query = "") {
      $this->query($query);
  }

  /* public: some trivial reporting */
  function link_id() {
    return $this->Link_ID;
  }

  function query_id() {
    return $this->Query_ID;
  }

  /* public: connection management */
  function connect($Database = "", $Host = "", $User = "", $Password = "") {
    /* Handle defaults */
    if ("" == $Database)
      $Database = $this->Database;
    if ("" == $Host)
      $Host     = $this->Host;
    if ("" == $User)
      $User     = $this->User;
    if ("" == $Password)
      $Password = $this->Password;

    /* establish connection, select database */
    if ( 0 == $this->Link_ID ) {

      if(!$this->PConnect) {
        $this->Link_ID = mysql_connect($Host, $User, $Password);
      } else {
        $this->Link_ID = mysql_pconnect($Host, $User, $Password);
      }
      if (!$this->Link_ID) {
        $this->halt("connect($Host, $User, \$Password) failed.");
        return 0;
      }

      if (!mysql_select_db($Database,$this->Link_ID)) {
        $this->halt("cannot use database ".$Database);
        return 0;
      }
      mysql_query("SET NAMES 'utf8'");//utf8...
    }

    return $this->Link_ID;
  }

  /* public: discard the query result */
  function free() {
  	if ($this->Query_ID > 0) {
      mysql_free_result($this->Query_ID);
    }
    $this->Query_ID = 0;
  }

  /* public: perform a query */
  function query($Query_String) {
	if (TM_DEBUG_SQL) {
		global $_MAIN_MESSAGE;
		$_MAIN_MESSAGE.=tm_message_debug($Query_String);
	}
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;

    if (!$this->connect()) {
      return 0; /* we already complained in connect() about that. */
    };

    # New query, discard previous result.
    //doit manually!!! call free() only if needed after big selects
    #if ($this->Query_ID) {
    #  $this->free();
    #}

    if ($this->Debug)
      printf("<font size=-1>Debug: query = %s<br></font>\n", $Query_String);

	    $this->Query_ID = mysql_query($Query_String,$this->Link_ID);
	    $this->Row   = 0;
	    $this->Errno = mysql_errno();
	    $this->Error = mysql_error();
	 if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }
    # Will return nada if it fails. That's fine.
    $this->LastInsertID=0;
    $this->LastInsertID=mysql_insert_id($this->Link_ID);
    return $this->Query_ID;
  }

  /* public: walk result set */
  function next_record() {
    if (!$this->Query_ID) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }

    $this->Record = mysql_fetch_array($this->Query_ID);
    $this->Row   += 1;
    $this->Errno  = mysql_errno();
    $this->Error  = mysql_error();

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_Free) {
      $this->free();
    }
    return $stat;
  }

  /* public: position in result set */
  function seek($pos = 0) {
    $status = mysql_data_seek($this->Query_ID, $pos);
    if ($status)
      $this->Row = $pos;
    else {
      $this->halt("seek($pos) failed: result has ".$this->num_rows()." rows.");

      /* half assed attempt to save the day,
       * but do not consider this documented or even
       * desireable behaviour.
       */
      mysql_data_seek($this->Query_ID, $this->num_rows());
      $this->Row = $this->num_rows();
      return 0;
    }

    return 1;
  }

  /* public: table locking */
  function lock($table, $mode = "write") {
    $query = "lock tables ";
    if(is_array($table)) {
      while(list($key,$value) = each($table)) {
        // text keys are "read", "read local", "write", "low priority write"
        if(is_int($key)) $key = $mode;
        if(strpos($value, ",")) {
          $query .= str_replace(",", " $key, ", $value) . " $key, ";
        } else {
          $query .= "$value $key, ";
        }
      }
      $query = substr($query, 0, -2);
    } elseif(strpos($table, ",")) {
      $query .= str_replace(",", " $mode, ", $table) . " $mode";
    } else {
      $query .= "$table $mode";
    }
    if(!$this->query($query)) {
      $this->halt("lock() failed.");
      return false;
    }
    $this->locked = true;
    return true;
  }

  function unlock() {

    // set before unlock to avoid potential loop
    $this->locked = false;

    if(!$this->query("unlock tables")) {
      $this->halt("unlock() failed.");
      return false;
    }
    return true;
  }

  /* public: evaluate the result (size, width) */
  function affected_rows() {
    return mysql_affected_rows($this->Link_ID);
  }

  function num_rows() {
    return mysql_num_rows($this->Query_ID);
  }

  function num_fields() {
    return mysql_num_fields($this->Query_ID);
  }

  /* public: shorthand notation */
  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
    if (isset($this->Record[$Name])) {
      return $this->Record[$Name];
    }
  }

  function p($Name) {
    if (isset($this->Record[$Name])) {
      print $this->Record[$Name];
    }
  }

  /* public: sequence numbers */
  function nextid($seq_name) {
    /* if no current lock, lock sequence table */
    if(!$this->locked) {
      if($this->lock($this->Seq_Table)) {
        $locked = true;
      } else {
        $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");
        return 0;
      }
    }

    /* get sequence number and increment */
    $q = sprintf("select nextid from %s where seq_name = '%s'",
               $this->Seq_Table,
               $seq_name);
    if(!$this->query($q)) {
      $this->halt('query failed in nextid: '.$q);
      return 0;
    }

    /* No current value, make one */
    if(!$this->next_record()) {
      $currentid = 0;
      $q = sprintf("insert into %s values('%s', %s)",
                 $this->Seq_Table,
                 $seq_name,
                 $currentid);
      if(!$this->query($q)) {
        $this->halt('query failed in nextid: '.$q);
        return 0;
      }
    } else {
      $currentid = $this->f("nextid");
    }
    $nextid = $currentid + 1;
    $q = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
               $this->Seq_Table,
               $nextid,
               $seq_name);
    if(!$this->query($q)) {
      $this->halt('query failed in nextid: '.$q);
      return 0;
    }

    /* if nextid() locked the sequence table, unlock it */
    if($locked) {
      $this->unlock();
    }

    return $nextid;
  }

  /* public: return table metadata */
  function metadata($table = "", $full = false) {
    $count = 0;
    $id    = 0;
    $res   = array();

    /*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *   ["meta"][field name]  index of field named "field name"
     *   This last one could be used if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) { ...
     */

    // if no $table specified, assume that we are working with a query
    // result
    if ($table) {
      $this->connect();
      $id = mysql_list_fields($this->Database, $table);
      if (!$id) {
        $this->halt("Metadata query failed.");
        return false;
      }
    } else {
      $id = $this->Query_ID;
      if (!$id) {
        $this->halt("No query specified.");
        return false;
      }
    }

    $count = mysql_num_fields($id);

    // made this IF due to performance (one if is faster than $count if's)
    if (!$full) {
      for ($i=0; $i<$count; $i++) {
        $res[$i]["table"] = mysql_field_table ($id, $i);
        $res[$i]["name"]  = mysql_field_name  ($id, $i);
        $res[$i]["type"]  = mysql_field_type  ($id, $i);
        $res[$i]["len"]   = mysql_field_len   ($id, $i);
        $res[$i]["flags"] = mysql_field_flags ($id, $i);
      }
    } else { // full
      $res["num_fields"]= $count;

      for ($i=0; $i<$count; $i++) {
        $res[$i]["table"] = mysql_field_table ($id, $i);
        $res[$i]["name"]  = mysql_field_name  ($id, $i);
        $res[$i]["type"]  = mysql_field_type  ($id, $i);
        $res[$i]["len"]   = mysql_field_len   ($id, $i);
        $res[$i]["flags"] = mysql_field_flags ($id, $i);
        $res["meta"][$res[$i]["name"]] = $i;
      }
    }

    // free the result only if we were called on a table
    if ($table) {
      #mysql_free_result($id);
      $this->free();
    }
    return $res;
  }

  /* public: find available table names */
  function table_names() {
    $this->connect();
    $h = mysql_query("show tables", $this->Link_ID);
    $i = 0;
    while ($info = mysql_fetch_row($h)) {
      $return[$i]["table_name"]      = $info[0];
      $return[$i]["tablespace_name"] = $this->Database;
      $return[$i]["database"]        = $this->Database;
      $i++;
    }
    #mysql_free_result($h);
	#$this->free();//nein, nur link id, free() nutzt query_id!    
    return $return;
  }

  /* private: error handling */
  function halt($msg) {
    $this->Error = mysql_error($this->Link_ID);
    $this->Errno = mysql_errno($this->Link_ID);

    if ($this->locked) {
      $this->unlock();
    }

    if ($this->Halt_On_Error == "no")
      return;

    $this->haltmsg($msg);

    if ($this->Halt_On_Error != "report")
      die("Session halted.");
  }

  function haltmsg($msg) {
  	//trigger_error("\n\nMysql Database Error! \n\n<br><br>\n\n $msg \n<br>Error NO: ".$this->Errno."<br>\n".$this->Error."\n\n<br>", E_USER_ERROR);
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);

  }

}

?>