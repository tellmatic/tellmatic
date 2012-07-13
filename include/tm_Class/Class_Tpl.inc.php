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

class tm_Template{
  var $file;
  var $path;
  var $replace_str = array();
  var $value = array();

  # Pfad zu den Templates übergeben
  function setTemplatePath($tpl_path=''){
      if(!empty($tpl_path)){
          if(substr($tpl_path, -1) == '/'){ # Auf Slash bei der Pfadeingabe prüfen
              $this->path = $tpl_path;
          }else{
              $this->path = $tpl_path.'/';
          }
      }
  }

  # welche Variablen ersetzen
  function setParseValue($r_str, $v_str){
      $this->replace_str[$r_str] = $r_str;
      $this->value[$r_str] = $v_str;
  }

  # Template anzeigen
  function renderTemplate($templateFile){
      $this->file = $this->path.$templateFile;
      $output = '';
      if(file_exists($this->file)){
	      $handle = fopen ($this->file, 'r');
	   	   while (!feof($handle)){
	  	        $buffer = fgets($handle, 4096);
	  	        $output .= $buffer;
	  	    }
	  	    $output=$this->renderContent($output);
	  	    fclose ($handle);
      } else {
		$output.="Error: Template ".$this->file." not exists!";	      
	}
    return $output;
  }

  # Template anzeigen
  function renderTemplateOld($templateFile){
      $this->file = $this->path.$templateFile;
      $output = '';
      if(!is_file($this->file)){
          $output.="Error: Template ".$this->file." not exists!";
      } else {
	      $handle = fopen ($this->file, 'r');
	   	   while (!feof($handle)){
	  	        $buffer = fgets($handle, 4096);
	  	        $output .= $buffer;
	  	    }
	  	    $output=$this->renderContent($output);
	  	    fclose ($handle);
  	   }
    return $output;
  }
  
  # Text rendern
  function renderContent($content){
  	    foreach($this->replace_str as $val){
  	        $content = str_replace('{'.$this->replace_str[$val].'}', $this->value[$val], $content);
  	    }
	return $content;
  }
}

?>