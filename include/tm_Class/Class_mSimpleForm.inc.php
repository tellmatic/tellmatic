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

class tm_SimpleForm {  
	var $INPUT=Array();
	var $OPTION=Array();
	var $DEFAULT_INPUT=Array();
	var $OUTPUT=Array();
	var $FORM=Array();

//internal functions for forms
	function check_FormExists($formname)
	{
		$Return=false;
		if (!empty($formname) && isset($this->FORM[$formname]['name']) && $this->FORM[$formname]['name']==$formname)
		{		
			$Return=true;
		}
		return $Return;
	}

	//new FORM
	function add_Form($formname)
	{
		return $this->add_Formular($formname);
	}

	function add_Formular($formname)
	{
		$Return=false;
		if (empty($formname))
		{
			//$formname=$FormPrefix."_noName_StdForm";
			$formname="noName_StdForm";
		}
		/*
		 else {
			$formname=$FormPrefix.$formname;
		}
		*/
		if(!isset($this->FORM[$formname]['name']))
		{
			$this->FORM[$formname]['name']=$formname;
			$Return=true;
		}
		return $Return;
	}
	
	//set ACTION
	function set_FormAction($formname,$action)
	{
		global $MasterURL;
		global $PHP_SELF;
		$Return=false;
		if($this->check_FormExists($formname))
		{
			if (!isset($PHP_SELF) || empty($PHP_SELF)) 
			{
				$PHP_SELF=$MasterURL."/index.php";
			}
			if (empty($action))
			{
				$action=$PHP_SELF;
			}
			$this->FORM[$formname]['action']=$action;
			$Return=true;
		}
		return $Return;
	}
	
	//set METHOD
	function set_FormMethod($formname,$method)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			if (empty($method) || $method!="get")
			{
				$method="post";
			}
			$this->FORM[$formname]['method']=$method;
			$Return=true;
		}
		return $Return;
	}
	
	//set TYPE
	function set_FormType($formname,$type)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			if (!empty($type))
			{
				$this->FORM[$formname]['type']=$type;
				$Return=true;
			}
		}
		return $Return;
	}
	
	//set TARGET
	function set_FormTarget($formname,$target)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			if (empty($target))
			{
				$target="_self";
			}
			$this->FORM[$formname]['target']=$target;
			$Return=true;
		}
		return $Return;
	}

//set Description
	function set_FormDesc($formname,$desc)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['desc']=$desc;
			$Return=true;
		}
		return $Return;
	}
//set JSAction
	function set_FormJS($formname,$js)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['js']=$js;
			$Return=true;
		}
		return $Return;
	}
//set Style
	function set_FormStyle($formname,$style="")
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['style']=$style;
			$Return=true;
		}
		return $Return;
	}
//set Class
	function set_FormStyleClass($formname,$class)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['class']=$class;
			$Return=true;
		}
		return $Return;
	}
//set Default Input Style 
	function set_FormInputStyle($formname,$style="",$activestyle="")
	{
		$Return=false;
		if (empty($activestyle))
		{
			$activestyle=$style;
		}
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['style_input']=$style;
			$this->FORM[$formname]['style_active_input']=$activestyle;
			$Return=true;
		}
		return $Return;
	}
//set Default Input Style Class
	function set_FormInputStyleClass($formname,$class,$activeclass="")
	{
		$Return=false;
		if (empty($activeclass))
		{
			$activeclass=$class;
		}
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['class_input']=$class;
			$this->FORM[$formname]['class_active_input']=$activeclass;
			$Return=true;
		}
		return $Return;
	}



	//internal functions for inputs
	//new INPUT
	function add_Input($formname,$name)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['name']=$name;
			$this->INPUT[$formname][$name]['html']="";
			$Return=true;
		}
		return $Return;
	}
	//set INPUT TYPE
	function set_InputType($formname,$name,$type)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['type']=$type;
			$Return=true;
		}
		return $Return;
	}
	//set INPUT VALUE
	function set_InputValue($formname,$name,$value)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['value']=$value;
			$Return=true;
		}
		return $Return;
	}
	
	//set INPUT DEFAULT radio&select
	//user functions for select and radio
	function set_InputDefault($formname,$name,$default)
	{
		$Return=false;
		if($this->check_FormExists($formname) && ($this->INPUT[$formname][$name]['type']=="checkbox" || $this->INPUT[$formname][$name]['type']=="select" || $this->INPUT[$formname][$name]['type']=="radio"))
		{
			$this->INPUT[$formname][$name]['default']=$default;
			$Return=true;
		}
		return $Return;
	}
	//add INPUT OPTION radio&select
	function add_InputOption($formname,$name,$value,$desc="",$group="",$style="")
	{
		$Return=false;
		if($this->check_FormExists($formname) && ($this->INPUT[$formname][$name]['type']=="select" || $this->INPUT[$formname][$name]['type']=="radio"))
		{
			if (empty($desc))
			{
				$desc=$value;
			}
			
			//if isset, voa 31082006
			if (isset($this->OPTION[$formname][$name])) {
				$count=count($this->OPTION[$formname][$name]);
			} else {
				$count=0;
			}
			$this->OPTION[$formname][$name][$count]['value']=$value;
			$this->OPTION[$formname][$name][$count]['desc']=$desc;
			$this->OPTION[$formname][$name][$count]['group']=$group;
			$this->OPTION[$formname][$name][$count]['style']=$style;
			$Return=true;
		}
		return $Return;
	}
	
	
	function set_InputSize($formname,$name,$sizeX=1,$sizeY=1)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['sizeX']=$sizeX;
			$this->INPUT[$formname][$name]['sizeY']=$sizeY;
			$Return=true;
		}
		return $Return;
	}

	function set_InputOrder($formname,$name,$order=1)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['order']=$order;
			$Return=true;
		}
		return $Return;
	}

	function set_InputTag($formname,$name,$pre="",$post="")
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['tag_pre']=$pre;
			$this->INPUT[$formname][$name]['tag_post']=$post;
			$Return=true;
		}
		return $Return;
	}

	
	function set_InputDesc($formname,$name,$desc="")
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['desc']=$desc;
			$Return=true;
		}
		return $Return;
	}

	function set_InputLabel($formname,$name,$label="")
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['label']=$label;
			$Return=true;
		}
		return $Return;
	}
	
	function set_InputSource($formname,$name,$source="")
	{
		$Return=false;
		if($this->check_FormExists($formname) && $this->INPUT[$formname][$name]['type']=="submit")
		{
			$this->INPUT[$formname][$name]['source']=$source;
			$Return=true;
		}
		return $Return;
	}
	
	function set_InputMultiple($formname,$name,$multiple=false)
	{
		$Return=false;
		if($this->check_FormExists($formname) && ($this->INPUT[$formname][$name]['type']=="select" || $this->INPUT[$formname][$name]['type']=="radio" || $this->INPUT[$formname][$name]['type']=="checkbox"))
		{
			$this->INPUT[$formname][$name]['multiple']=$multiple;
			$Return=true;
		}
		return $Return;
	}
	
	function set_InputReadonly($formname,$name,$readonly=false)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['readonly']=$readonly;
			$Return=true;
		}
		return $Return;
	}
	function set_InputStyle($formname,$name,$style="",$activestyle="")
	{
		$Return=false;
		if (empty($activestyle))
		{
			$activestyle=$style;
		}
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['style']=$style;
			$this->INPUT[$formname][$name]['style_active']=$activestyle;
			$Return=true;
		}
		return $Return;
	}
	function set_InputStyleClass($formname,$name,$class,$activeclass="")
	{
		$Return=false;
		if (empty($activeclass))
		{
			$activeclass=$class;
		}

		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['class']=$class;
			$this->INPUT[$formname][$name]['class_active']=$activeclass;
			$Return=true;
		}
		return $Return;
	}

	//set INPUT Javascriptaction
	function set_InputJS($formname,$name,$js)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['js']=$js;
			$Return=true;
		}
		return $Return;
	}

	//set INPUT ID
	function set_InputID($formname,$name,$id)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->INPUT[$formname][$name]['id']=$id;
			$Return=true;
		}
		return $Return;
	}
	function sort_Input($formname)
	{
		//echo "before sorting:<p>";print_r($this->INPUT[$formname]);echo "</p><hr>";
		foreach ($this->INPUT[$formname] as $Fields)
		{
			$sort[]=$Fields['order'];
			if (is_array($sort)) {
			    @array_multisort($sort, SORT_ASC, $this->INPUT[$formname]);
			}
		}
		//echo "after sorting:<p>";print_r($this->INPUT[$formname]);echo "</p><hr>";
	}

//user functions
	
	function new_Form($formname,$action,$method,$target) 
	{
		return $this->new_Formular($formname,$action,$method,$target);
	}
	
	function new_Formular($formname,$action,$method,$target) 
	{
	//create a new Formular 
	//name: [STRING],action:[URI],method:[STRING:get|post],target:[STRING]
		$Return=false;
		if(!$this->check_FormExists($formname))
		{
		$this->add_Formular($formname);
		$this->set_FormAction($formname,$action);
		$this->set_FormMethod($formname,$method);
		$this->set_FormTarget($formname,$target);
		$this->set_FormDesc($formname,"");
		$this->set_FormStyle($formname,"");
		$this->set_FormStyleClass($formname,"");
		$this->set_FormInputStyle($formname,"","");
		$this->set_FormInputStyleClass($formname,"","");
		$this->set_FormJS($formname,"");
		$Return=true;
		}
		return $Return;
	}
	
	function new_Input($formname,$name="NoName", $type="text", $value="") 
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->add_Input($formname,$name);
			$this->set_InputType($formname,$name,$type);
			$this->set_InputValue($formname,$name,$value);
			$this->set_InputDefault($formname,$name,"");
			$this->set_InputSize($formname,$name,10,3);
			$order=count($this->INPUT[$formname]);
			$this->set_InputOrder($formname,$name,$order);
			$this->set_InputDesc($formname,$name,"");
			$this->set_InputLabel($formname,$name,"");
			$this->set_InputSource($formname,$name,"");
			$this->set_InputMultiple($formname,$name,false);
			$this->set_InputReadonly($formname,$name,false);
			$this->set_InputStyle($formname,$name,"","");
			$this->set_InputStyleClass($formname,$name,"","");
			$this->set_InputTag($formname,$name,"","");
			$this->set_InputJS($formname,$name,"");
			
			$Return=true;
		}
		return $Return;
	}

	function render_Input($formname,$name)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			//all inputs:
				if (!isset($this->INPUT[$formname][$name]['id'])) {
					$this->INPUT[$formname][$name]['id'] = $this->INPUT[$formname][$name]['name'];
				}

				//ausser hidden!!!
				if ($this->INPUT[$formname][$name]['type']!="hidden") {
					$this->INPUT[$formname][$name]['html'] = $this->INPUT[$formname][$name]['tag_pre'];
					$this->INPUT[$formname][$name]['html'] .= "<label for=\"".$this->INPUT[$formname][$name]['name']."\" class=\"mFormFieldLabel\"><span class=\"mFormFieldLabel\">\n";
					$this->INPUT[$formname][$name]['html'] .=$this->INPUT[$formname][$name]['label']."</span>\n";
					//<span class=\"mFormFieldLabelDesc\">".$this->INPUT[$formname][$name]['desc']."</span>";
					$this->INPUT[$formname][$name]['html'] .= "</label>\n";
				}
				$InputStyleClass="";
				$InputOnAction=$this->INPUT[$formname][$name]['js'];
				//1st use the assigned class
				if (!empty($this->INPUT[$formname][$name]['class']))
				{
					$InputStyleClass .= " class=\"".$this->INPUT[$formname][$name]['class']."\"";
					$InputOnAction .= " onFocus=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').className = '".$this->INPUT[$formname][$name]['class_active']."';\"";
					$InputOnAction .= " onBlur=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').className = '".$this->INPUT[$formname][$name]['class']."';\"";
				} else {
					//2nd: try to get assigned style if no class is set or empty
					if (!empty($this->INPUT[$formname][$name]['style']))
					{
						$InputStyleClass .= " style=\"".$this->INPUT[$formname][$name]['style']."\"";
						$InputOnAction .= " onFocus=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').style = '".$this->INPUT[$formname][$name]['style_active']."';\"";
						$InputOnAction .= " onBlur=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').style = '".$this->INPUT[$formname][$name]['style']."';\"";
					} else {
						//if no sepcific class or style for input has assigned or is empty, use the default class defined for the inputs
						if (!empty($this->FORM[$formname]['class_input']))
						{
							$InputStyleClass .= " class=\"".$this->FORM[$formname]['class_input']."\"";
							$InputOnAction .= " onFocus=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').className = '".$this->FORM[$formname]['class_active_input']."';\"";
							$InputOnAction .= " onBlur=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').className = '".$this->FORM[$formname]['class_input']."';\"";
						} else {
							//if theres no default class at all and no styles and classes , use the default input style assigned to form if not empty
							if (!empty($this->FORM[$formname]['style_input']))
							{
								$InputStyleClass .= " style=\"".$this->FORM[$formname]['style_input']."\"";
								$InputOnAction .= " onFocus=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').style = '".$this->FORM[$formname]['style_active_input']."';\"";
								$InputOnAction .= " onBlur=\"document.getElementById('".$this->INPUT[$formname][$name]['name']."').style = '".$this->FORM[$formname]['style_input']."';\"";
							}						
						}
					}
				}


			//hidden
			if (($this->INPUT[$formname][$name]['type']=="hidden"))
			{
				$this->INPUT[$formname][$name]['html'] .= "<input";
				$this->INPUT[$formname][$name]['html'] .= " type=\"".$this->INPUT[$formname][$name]['type']."\"";
				$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				$this->INPUT[$formname][$name]['html'] .= " value=\"".$this->INPUT[$formname][$name]['value']."\"";
				$this->INPUT[$formname][$name]['html'] .= ">\n";
			}

			//file
			if (($this->INPUT[$formname][$name]['type']=="file"))
			{
				$this->INPUT[$formname][$name]['html'] .= "<input";
				$this->INPUT[$formname][$name]['html'] .= " type=\"".$this->INPUT[$formname][$name]['type']."\"";
				$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				#$this->INPUT[$formname][$name]['html'] .= " value=\"".$this->INPUT[$formname][$name]['value']."\"";
				#$this->INPUT[$formname][$name]['html'] .= " size=\"".$this->INPUT[$formname][$name]['sizeX']."\"";
				#$this->INPUT[$formname][$name]['html'] .= " maxlength=\"".$this->INPUT[$formname][$name]['sizeY']."\"";
				$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
				$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
				/*
				if ($this->INPUT[$formname][$name]['readonly'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " readonly disabled";
				}
				*/
				$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
				$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;
				$this->INPUT[$formname][$name]['html'] .= ">\n\n";
			}
			
			//text || password
			if (($this->INPUT[$formname][$name]['type']=="text") || ($this->INPUT[$formname][$name]['type']=="password"))
			{
				$this->INPUT[$formname][$name]['html'] .= "<input";
				$this->INPUT[$formname][$name]['html'] .= " type=\"".$this->INPUT[$formname][$name]['type']."\"";
				$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				$this->INPUT[$formname][$name]['html'] .= " value=\"".$this->INPUT[$formname][$name]['value']."\"";
				$this->INPUT[$formname][$name]['html'] .= " size=\"".$this->INPUT[$formname][$name]['sizeX']."\"";
				$this->INPUT[$formname][$name]['html'] .= " maxlength=\"".$this->INPUT[$formname][$name]['sizeY']."\"";
				$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
				$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
				if ($this->INPUT[$formname][$name]['readonly'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " readonly disabled";
				}
				$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
				$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;
				$this->INPUT[$formname][$name]['html'] .= ">\n\n";
			}

			//textarea
			if ($this->INPUT[$formname][$name]['type']=="textarea")
			{
				$this->INPUT[$formname][$name]['html'] .= "<".$this->INPUT[$formname][$name]['type'];
				$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				$this->INPUT[$formname][$name]['html'] .= " cols=\"".$this->INPUT[$formname][$name]['sizeX']."\"";
				$this->INPUT[$formname][$name]['html'] .= " rows=\"".$this->INPUT[$formname][$name]['sizeY']."\"";
				$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
				$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
				if ($this->INPUT[$formname][$name]['readonly'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " readonly disabled";
				}
				$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
				$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;
				$this->INPUT[$formname][$name]['html'] .= ">";
				$this->INPUT[$formname][$name]['html'] .= $this->INPUT[$formname][$name]['value'];
				$this->INPUT[$formname][$name]['html'] .= "</".$this->INPUT[$formname][$name]['type'].">\n";
			}

			//checkbox
			if (($this->INPUT[$formname][$name]['type']=="checkbox"))
			{
				$this->INPUT[$formname][$name]['html'] .= "<input";
				$this->INPUT[$formname][$name]['html'] .= " type=\"".$this->INPUT[$formname][$name]['type']."\"";

				if ($this->INPUT[$formname][$name]['multiple'])//if multiple... make an aray, add [] to the name
				{
					$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."[]\"";
				} else {
					$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				}
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				$this->INPUT[$formname][$name]['html'] .= " value=\"".$this->INPUT[$formname][$name]['value']."\"";
				$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
				$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
				if ($this->INPUT[$formname][$name]['value']==$this->INPUT[$formname][$name]['default'])
				{
					$this->INPUT[$formname][$name]['html'] .= " checked=\"checked\"";
				}
				if ($this->INPUT[$formname][$name]['readonly'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " readonly disabled";
				}
				$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
				$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;
				$this->INPUT[$formname][$name]['html'] .= ">\n";
				#$this->INPUT[$formname][$name]['html'] .= ">\n</div>\n";
			}


			//submit || reset
			if (($this->INPUT[$formname][$name]['type']=="submit") || ($this->INPUT[$formname][$name]['type']=="reset"))
			{
				$this->INPUT[$formname][$name]['html'] .= "<input";
				if (!empty($this->INPUT[$formname][$name]['source']) && ($this->INPUT[$formname][$name]['type']=="submit"))
				{
					$this->INPUT[$formname][$name]['html'] .= " type=\"image\"";
				 	$this->INPUT[$formname][$name]['html'] .= " src=\"".$this->INPUT[$formname][$name]['source']."\"";
				} else {
					$this->INPUT[$formname][$name]['html'] .= " type=\"".$this->INPUT[$formname][$name]['type']."\"";
				}
				$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
				$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
				if ($this->INPUT[$formname][$name]['readonly'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " readonly disabled";
				}

				$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
				$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;
				$this->INPUT[$formname][$name]['html'] .= " value=\"".$this->INPUT[$formname][$name]['value']."\">";
			}
			
			//select
			if (($this->INPUT[$formname][$name]['type']=="select"))
			{
				$this->INPUT[$formname][$name]['html'] .= "<";
				$this->INPUT[$formname][$name]['html'] .= $this->INPUT[$formname][$name]['type'];
				if ($this->INPUT[$formname][$name]['multiple'])//if multiple... make an aray, add [] to the name
				{
					$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."[]\"";
				} else {
					$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
				}
				$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
				$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
				$this->INPUT[$formname][$name]['html'] .= " size=\"".$this->INPUT[$formname][$name]['sizeY']."\"";
				$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
				if ($this->INPUT[$formname][$name]['readonly'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " disabled";
				}
				if ($this->INPUT[$formname][$name]['multiple'])
				{
				 	$this->INPUT[$formname][$name]['html'] .= " multiple";
				}
				$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
				$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;
				$this->INPUT[$formname][$name]['html'] .= ">\n";
				if (!empty($this->INPUT[$formname][$name]['value']))
				{
					$this->INPUT[$formname][$name]['html'] .= "<option value=\"".$this->INPUT[$formname][$name]['value']."\">".$this->INPUT[$formname][$name]['value']."</option>\n";
				}

				if (isset($this->OPTION[$formname][$name]))
				{
					$count=count($this->OPTION[$formname][$name]);

					$group_open=false;

					for ($counter=0;$counter<$count;$counter++)
					{


						if (!empty($this->OPTION[$formname][$name][$counter]['group']) && !$group_open)
						{
							if ($counter == 0 || ($counter >= 1 && ($this->OPTION[$formname][$name][($counter-1)]['group'] != $this->OPTION[$formname][$name][($counter)]['group'])))
							{
								$this->INPUT[$formname][$name]['html'] .= "<optgroup label=\"".$this->OPTION[$formname][$name][$counter]['group']."\">\n";
								$group_open=true;
							}
						}
					
						$this->INPUT[$formname][$name]['html'] .= "<option value=\"".$this->OPTION[$formname][$name][$counter]['value']."\"";

				//is array, multiple select!
				if ($this->INPUT[$formname][$name]['multiple'])//if multiple... make an aray, add [] to the name
				{
					//echo 	"pt_register(".$this->FORM[$formname]['method'].",".$this->INPUT[$formname][$name]['name'].")";
					//hack! need ptregister, sets global var....umpf
					pt_register($this->FORM[$formname]['method'],$this->INPUT[$formname][$name]['name']);
					$tmp=$this->INPUT[$formname][$name]['name'];
					global $$tmp;
					$tmp_a=$$tmp;
					//echo $tmp_a;
					if (is_array($tmp_a)) {
						//print_r($tmp_a);
						$tmp_c=count($tmp_a);
						for ($tmp_cc=0;$tmp_cc < $tmp_c;$tmp_cc++) {
							if ($this->OPTION[$formname][$name][$counter]['value']==$tmp_a[$tmp_cc]) {
								$this->INPUT[$formname][$name]['html'] .=	" selected=\"selected\"";
							}
						}
					}
				} else {
					//einfacher wert						
						if ($this->INPUT[$formname][$name]['default']==$this->OPTION[$formname][$name][$counter]['value'])
						{
							$this->INPUT[$formname][$name]['html'] .=	" selected=\"selected\" ";
						}
				}
				
				
				
				if (!empty($this->OPTION[$formname][$name][$counter]['style'])) {
					$this->INPUT[$formname][$name]['html'] .=" style=\"".$this->OPTION[$formname][$name][$counter]['style']."\"";
				}
				
				
				$this->INPUT[$formname][$name]['html'] .=	">".$this->OPTION[$formname][$name][$counter]['desc']."</option>\n";

#debug $this->INPUT[$formname][$name]['html'] .= "match=\"val:".$this->OPTION[$formname][$name][$counter]['value']." def.:".$this->INPUT[$formname][$name]['default']."\"";

				if (!empty($this->OPTION[$formname][$name][$counter]['group']) && $group_open)
					{
						if (!isset($this->OPTION[$formname][$name][($counter+1)]) || ($this->OPTION[$formname][$name][($counter+1)]['group'] != $this->OPTION[$formname][$name][$counter]['group']))
						{
						//$counter == 0 || ($counter >= 1 &&  )
							$this->INPUT[$formname][$name]['html'] .= "</optgroup>\n";
							$group_open=false;
						}
					}
				}
			}
				$this->INPUT[$formname][$name]['html'] .= "</select>\n";
			}
			//radio
			if (($this->INPUT[$formname][$name]['type']=="radio"))
			{
				if (isset($this->OPTION[$formname][$name]))
				{
					$count=count($this->OPTION[$formname][$name]);
					for ($counter=0;$counter<$count;$counter++)
					{
						$this->INPUT[$formname][$name]['html'] .= $this->OPTION[$formname][$name][$counter]['desc'].":";
						$this->INPUT[$formname][$name]['html'] .= "<input ";
						$this->INPUT[$formname][$name]['html'] .= " type=\"".$this->INPUT[$formname][$name]['type']."\"";
						$this->INPUT[$formname][$name]['html'] .= " name=\"".$this->INPUT[$formname][$name]['name']."\"";
						$this->INPUT[$formname][$name]['html'] .= " id=\"".$this->INPUT[$formname][$name]['id']."\"";
						$this->INPUT[$formname][$name]['html'] .= " tabindex=\"".$this->INPUT[$formname][$name]['order']."\"";
						$this->INPUT[$formname][$name]['html'] .= " title=\"".clear_text($this->INPUT[$formname][$name]['desc'])."\"";
						if ($this->INPUT[$formname][$name]['readonly'])
						{
						 	$this->INPUT[$formname][$name]['html'] .= " readonly disabled";
						}
						$this->INPUT[$formname][$name]['html'] .= " value=\"".$this->OPTION[$formname][$name][$counter]['value']."\"";
						if ($this->INPUT[$formname][$name]['default']==$this->OPTION[$formname][$name][$counter]['value'])
						{
							$this->INPUT[$formname][$name]['html'] .=	" checked=\"checked\"";
						}
						$this->INPUT[$formname][$name]['html'] .= " ".$InputStyleClass;
						$this->INPUT[$formname][$name]['html'] .= " ".$InputOnAction;

						$this->INPUT[$formname][$name]['html'] .=	">\n";
					}
				}
			}
			$this->INPUT[$formname][$name]['html'] .= $this->INPUT[$formname][$name]['tag_post'];
			$Return=true;
		}
		return $Return;
	}//render_Input()


	function render_FormHead($formname)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$FormStyleClass="";
			if (!empty($this->FORM[$formname]['class']))
			{
				$FormStyleClass .= " class=\"".$this->FORM[$formname]['class']."\"";			
			} else {
				if (!empty($this->FORM[$formname]['style']))
				{
					$FormStyleClass .= " style=\"".$this->FORM[$formname]['style']."\"";			
				}
			}
			$this->FORM[$formname]['head'] ="<form";
			$this->FORM[$formname]['head'] .=" name=\"".$this->FORM[$formname]['name']."\"";
			$this->FORM[$formname]['head'] .=" id=\"".$this->FORM[$formname]['name']."\"";
			$this->FORM[$formname]['head'] .=" method=\"".$this->FORM[$formname]['method']."\"";
			$this->FORM[$formname]['head'] .=" action=\"".$this->FORM[$formname]['action']."\"";
			$this->FORM[$formname]['head'] .=" target=\"".$this->FORM[$formname]['target']."\"";
			if (isset($this->FORM[$formname]['type']) && !empty($this->FORM[$formname]['type'])) {
				$this->FORM[$formname]['head'] .=" enctype=\"".$this->FORM[$formname]['type']."\"";
			}
			$this->FORM[$formname]['head'] .= $FormStyleClass;
			$this->FORM[$formname]['head'] .= $this->FORM[$formname]['js'];
			$this->FORM[$formname]['head'] .=">\n";
			$Return=true;
		}
		return $Return;
	}//

	function render_FormFoot($formname)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			if (!isset($this->FORM[$formname]['foot'])) {
				$this->FORM[$formname]['foot']="";
			}
			$this->FORM[$formname]['foot'] .="</form>\n";
			$Return=true;
		}
		return $Return;
	}//


	function render_FormBody($formname)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->FORM[$formname]['body'] = "<table class=\"mFormTable\" border=\"0\"  rules=\"groups\" frames=\"border\" cellpadding=\"1\" cellspacing=\"1\" summary=\"".$this->FORM[$formname]['desc']."\">";
			//style=\"background-color:#eeeeee;\"
			//width=\"100%\" 
			$this->FORM[$formname]['body'] .= "<thead class=\"mFormTableHead\"><tr><td>".$this->FORM[$formname]['desc']."</td></tr></thead>";
			$this->FORM[$formname]['body'] .= "<tbody class=\"mFormTableBody\">";
			foreach($this->INPUT[$formname] as $Field)
			{
				if ($this->render_Input($formname,$Field['name']))
				{
					$this->FORM[$formname]['body'] .= "<tr><td>";
					$this->FORM[$formname]['body'] .= $this->INPUT[$formname][$Field['name']]['html']."\n";
					$this->FORM[$formname]['body'] .= "</td></tr>";
				}
			}
			$this->FORM[$formname]['body'] .= "</tbody>";
			$this->FORM[$formname]['body'] .=  "</table>";
			$Return=true;
		}
		return $Return;
	}//


	function render_Form($formname)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->render_FormHead($formname);
			$this->FORM[$formname]['html'] =	$this->FORM[$formname]['head'];
			$this->render_FormBody($formname);
			$this->FORM[$formname]['html'] .= $this->FORM[$formname]['body'];
			$this->render_FormFoot($formname);
			$this->FORM[$formname]['html'] .= $this->FORM[$formname]['foot'];
		}
		return $Return;
	}//

	function get_Form($formname)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			$this->render_Form($formname);
			return $this->FORM[$formname]['html'];
		} else {
			return $Return;
		}
	}//

	function print_Form($formname)
	{
		$Return=false;
		if($this->check_FormExists($formname))
		{
			echo $this->get_Form($formname);
		} else {
			return $Return;
		}
	}//


	function Debug()
	{
		$Debug="";
		foreach($this->FORM as $Form)
		{
			$Debug .= "<ul><b>Formular:</b>";
			$Debug .=	"<li>Name: ".$Form['name']."</li>";
			$Debug .= "<li>Method: ".$Form['method']."</li>";
			$Debug .= "<li>Action: ".$Form['action']."</li>";
			$Debug .= "<li>Target: ".$Form['target']."</li>";
			$Debug .= "<li>Style: ".$Form['style']."</li>";
			$Debug .= "<li>Default Input Style: ".$Form['style_input']."</li>";
			$Debug .= "<li>Default Active Input Style: ".$Form['style_active_input']."</li>";
			$Debug .= "<li>Class: ".$Form['class']."</li>";
			$Debug .= "<li>Default Input Class: ".$Form['class_input']."</li>";
			$Debug .= "<li>Default Active Input Class: ".$Form['class_active_input']."</li>";
			$Debug .= "<li>Description: ".$Form['desc']."</li>";
			$Debug .= "<ul><b>Fields:</b><br>";
			foreach($this->INPUT[$Form['name']] as $Field)
			{					
					$Debug.= "<ul>Name: ".$Field['name']."";
					$Debug.= "<li>ID: ".$Field['id']."</li>";
					$Debug.= "<li>Type: ".$Field['type']."</li>";
					$Debug.= "<li>Size: ".$Field['sizeX']." x ".$Field['sizeY']."</li>";
					$Debug.= "<li>Value: ".$Field['value']."</li>";
					$Debug.= "<li>Style:<br>normal: ".$Field['style']."<br>active: ".$Field['style_active']."</li>";
					$Debug.= "<li>Class:<br>normal: ".$Field['class']."<br>active: ".$Field['class_active']."</li>";
					$Debug.= "<li>Label: ".$Field['label']."</li>";
					$Debug.= "<li>Order/Tabindex: ".$Field['order']."</li>";
					$Debug.= "<li>Description: ".$Field['desc']."</li>";
					$Debug.= "</ul>";
			}
			$Debug.= "</ul>";
			
			$Debug.=  "</ul>";
		}
		return $Debug;
	}//Debug();

}  

?>