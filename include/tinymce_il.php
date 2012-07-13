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

include ("./tm_config.inc.php");
function tinymce_createimagelist($FileA,$UrlPrefix) {
	$Return="";
	//sort array by name:
		$btsort=Array();
		foreach ($FileA as $field) {
			$btsort[]=$field['filename'];
		}
		@array_multisort($btsort, SORT_ASC, $FileA, SORT_ASC);
	$ic= count($FileA);
	for ($icc=0; $icc < $ic; $icc++) {
		$Return.= "[\"".$FileA[$icc]['filename']."\",\"".$UrlPrefix."/".$FileA[$icc]['filename']."\"]";
		if ($icc < ($ic-1)) {
		 $Return.= ",\n";
		}
	}
	Return $Return;
}

$tinymce_il="";
#unset($FileARRAY);
#gen_rec_files_array($tm_nlimgpath);
#$tinymce_il.=tinymce_createimagelist($FileARRAY,$tm_URL_FE."/".$tm_nlimgdir);


//new:
$Attm_Dirs=getDirectories($tm_nlimgpath) ;
foreach ($Attm_Dirs as $field) {
	$btsort[]=$field['name'];
}
@array_multisort($btsort, SORT_ASC, $Attm_Dirs, SORT_ASC);
$dc= count($Attm_Dirs);
for ($dcc=0; $dcc < $dc; $dcc++) {
	$a_path=$tm_nlimgpath;
	if ($Attm_Dirs[$dcc]['name']!="CVS" && $Attm_Dirs[$dcc]['name']!="..") {
		if (!empty($Attm_Dirs[$dcc]['name'])) {
			$a_path.="/".$Attm_Dirs[$dcc]['name'];
		}
		$Attm_Files=getFiles($a_path) ;
		foreach ($Attm_Files as $field) {
			$btsort[]=$field['name'];
		}
		@array_multisort($btsort, SORT_ASC, $Attm_Files, SORT_ASC);
		$ic= count($Attm_Files);
		for ($icc=0; $icc < $ic; $icc++) {
			if ($Attm_Files[$icc]['name']!=".htaccess" && $Attm_Files[$icc]['name']!="index.php" && $Attm_Files[$icc]['name']!="index.html") {
				$a_file=$Attm_Files[$icc]['name'];
				if (!empty($Attm_Dirs[$dcc]['name']) && $Attm_Dirs[$dcc]['name']!=".") {
					$a_file=$Attm_Dirs[$dcc]['name']."/".$Attm_Files[$icc]['name'];
				}
				$tinymce_il.= "[\"".$a_file."\",\"".$tm_URL_FE."/".$tm_nlimgdir."/".$a_file."\"]";
				$tinymce_il.= ",\n";
			}//if Attm name
		}//for  icc
	}//if attmdir name
}//for dcc
$tinymce_il.= "[\"\",\"\"]";

echo "var tinyMCEImageList = new Array(\n";
echo $tinymce_il."\n";
echo ");\n";
?>