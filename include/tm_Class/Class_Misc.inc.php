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

class Digit {

  var $bits = array(1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384);
  var $matrix  = array();
  var $bitmasks = array(31599, 18740, 29607, 31143, 18921, 31183, 31695, 18855, 31727, 31215);

  function digit( $dig ) {
    $this->matrix[] = array(0, 0, 0); // 2^0, 2^1, 2^2 ... usw.
    $this->matrix[] = array(0, 0, 0);
    $this->matrix[] = array(0, 0, 0);
    $this->matrix[] = array(0, 0, 0);
    $this->matrix[] = array(0, 0, 0); // ..., ..., 2^14

    ((int)$dig >= 0 && (int)$dig <= 9) && $this->setMatrix( $this->bitmasks[(int)$dig] );
  }

  function setMatrix( $bitmask ) {
    $bitsset = array();

    for ($i=0; $i<count($this->bits); ++$i)
      (($bitmask & $this->bits[$i]) != 0) && $bitsset[] = $this->bits[$i];

    foreach($this->matrix AS $row=>$col)
      foreach($col AS $cellnr => $bit)
        in_array( pow(2,($row*3+$cellnr)), $bitsset) && $this->matrix[$row][$cellnr] = 1;
  }
}

class Number {

  var $num = 0;
  var $digits = array();

  function number( $num ) {
    $this->num = (int)$num;

    $r = "{$this->num}";
    for( $i=0; $i<strlen($r); $i++ )
      $this->digits[] = new Digit((int)$r[$i]);
  }

  function getNum() { return $this->num; }

  function printNumber() {
  	$n="";
    for($row=0; $row<count($this->digits[0]->matrix); $row++) {
      foreach( $this->digits AS $digit ) {
        foreach($digit->matrix[$row] AS $cell)
          if($cell === 1) $n.="<span class=\"s\">&nbsp;&nbsp;</span>"; else $n.="<span class=\"w\">&nbsp;&nbsp;</span>";
	        $n.="<span class=\"w\">&nbsp;</span>";
      }
      $n.="<br>";
    }
    return $n;
  }
}

class Timer
		{
		var $Start;
		var $MiddleTime;

		function getMicroSecs($Var)
			{
			$Tmp = array();
			$Tmp = explode(" ", $Var);
			return $Tmp[1] + $Tmp[0];
			}

		function Timer()
			{
			$this->Start = $this->getMicroSecs(microtime());
			$this->MiddleTime = $this->Start;
			}

		function Result()
			{
			return $this->getMicroSecs(microtime()) - $this->Start;
			}

		function MidResult()
			{
			$Tmp = $this->getMicroSecs(microtime());
			$Result = $Tmp - $this->MiddleTime;
			$this->MiddleTime = $Tmp;
			return $Result;
			}
		}

?>