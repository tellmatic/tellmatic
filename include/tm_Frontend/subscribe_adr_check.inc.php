<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/10 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/*******************************************************************************/

	#new, alle neu:pubgrp+defgrp			adr_grp
	#update:
	#nur neue: alle neuen + alte				adr_grp + adr_grp_pub + adr->grp
	#ueberschreiben: defgrp + pubgrp		adr_grp + adr_grp_pub	
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("precheck ok, check===true");

	#proof?!
	if ($C[0]['proof']==1) {
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("proofing global enabled");
		if ($FRM[0]['proof']==1) {
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("proofing for this form enabled");
			$ADDRESS->proof();
		}	else {
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("proofing for this form disabled");		
		}
	}	
	
	//gruppen f. formular
	//erst nur die defaultgruppen!
	$default_adr_grp=$ADDRESS->getGroupID(0,0,$frm_id,Array("public_frm_ref"=>0,"aktiv"=>1));
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("default group for this form: ".implode(",",$default_adr_grp));
	#$new_adr_grp=$adr_grp;
	//default gruppenzuordnung im code: gruppen neu referenzieren, default seinstellung in tellmatc ist aber aktualisierung und nur neue gruppen bei update
	//deswegen bei exsitenten adressen check if overwrite_pubgroup !=1, uffz 
	//erstmal gruppen komplett neu anlegen
	//neu= public + default
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("user selected public group for this form: ".implode(",",$adr_grp_pub));
	$tmp_adr_grp=array_merge($adr_grp_pub,$default_adr_grp);
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("default group + selected public group: ".implode(",",$tmp_adr_grp));
	$new_adr_grp=array_unique($tmp_adr_grp);
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("default group + public group, unique: ".implode(",",$new_adr_grp));
	
	//dublettencheck
	//check if adr already exists!
	if (tm_DEBUG()) $MESSAGE.=tm_message_debug("checking email");
	$search['email']=$email;
	//auf existenz pruefen und wenn email noch nicht existiert dann eintragen.
	$ADR=$ADDRESS->getAdr(0,0,0,0,$search);
	$ac=count($ADR);
	if ($ac>0) {
		//oh! adresse ist bereits vorhanden!
		//wir diffen die gruppen und fuegen nur die referenzen hinzu die noch nicht existieren!
		$adr_exists=true;
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("email already exists");
		//gruppen denen die adr bereits  angehoert
		if ($FRM[0]['overwrite_pubgroup']!=1) {
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("updating groups, merge with old");
			$old_adr_grp = $ADDRESS->getGroupID(0,$ADR[0]['id'],0);
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("old groups: ".implode(",",$old_adr_grp));
			$tmp_adr_grp = array_merge($old_adr_grp, $new_adr_grp);
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("merge old and new: ".implode(",",$tmp_adr_grp));
			$new_adr_grp = array_unique($tmp_adr_grp);
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("merge old and new, unique: ".implode(",",$new_adr_grp));
		} else {//overwrite pubgroups
			//ueberschreiben mit neuen gruppen refs:
			if (tm_DEBUG()) $MESSAGE.=tm_message_debug("overwrite groups, save only default and new selected.");
		}//overwrite pubgroups
	} else {//adr exists
		if (tm_DEBUG()) $MESSAGE.=tm_message_debug("email not yet exists, save new entry");
	}
	//re-index, important!
	$new_adr_grp=array_values($new_adr_grp);		
?>