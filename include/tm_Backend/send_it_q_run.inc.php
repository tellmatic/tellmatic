<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        */
/* tellmatic, the newslettermachine                                             */
/* tellmatic, die Newslettermaschine                                            */
/* 2006/12 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

			//schleife... max mails bcc
			
			//1090 schelfie wird eigentlich nicht mehr gebraucht, daher max_mails_bcc nicht mehr vom host sondern immer 1!
			//schleife bearbeitet ur noch personalisierte mails und immer nur einen eintrag
			$bc=$hcc+$max_mails_bcc;
			$BCC="";
			$BCC_Arr=Array();
			#for ($bcc=$hcc;$bcc<$bc;$bcc++) {
			//bcc is same as hcc now
			$bcc=$hcc;
			send_log("bcc=hcc=".$bcc);

				$a_error=false;
				$h_error=false;
				$skipped=false;

				if (isset($H[$bcc]['id'])) {
						//set adr_id for logging
						$log_adr_id=$H[$bcc]['adr_id'];
						send_log($bcc.".) ");
						// ok, wir muessen nun um zu vermeiden,
						// das bei gleichzeitigen aufrufen doppelte mails verschickt werden,
						// den status erneut abfragen auf 5=running, und wenn nichts gefunden wurde
						// einen status setzen fuer die history 5=running !!!

						//aktuellen eintrag abrufen und auf status 5 pruefen! ebenfalls ob nicht schon status fertig etc.
						//$HRun=$QUEUE->getH($H[$bcc]['id'],0,0,0,0,0,0,5);
						//wir drehen es um, wir pruefen nur ob dieser eintrag in der sendeliste auch noch auf status 1, warten auf versand=neu, steht....
						//function getH($id=0,$offset=0,$limit=0,$q_id=0,$nl_id=0,$grp_id=0,$adr_id=0,$status=0) {
						$HWait=$QUEUE->getH($H[$bcc]['id'],0,0,0,0,0,0,1);
						//und wenn nun hc_run==1 ist, dann senden
						//eigentlich muesste die variable nun HWait und hc_wait heissen!

						//$hc_run=count($HRun);//wieviel running sendeeintraege
						$hc_wait=count($HWait);//wieviel running sendeeintraege
						//wenn der aktuelle eintrag kein status 5 hat, versenden! evtl auch pruefen auf bereits versendet!
						// ^^^korrektur: wenn status == 1 ist, dann versenden, ansonsten ist der eintrag schon irgendwie bearbeitet worden!!!!

						//weil wenn konkurrierende jobs, dann kann der eine 5 setzen und dann ist ok, wenn wir aber gerade dann dort ankommen ... und der eintrag ist noch in im H Array... dann wird email evtl 2x versendet!
						//eigentlich nur kritisch bei konkurrierenden eintraegen
						//umgedreht:
						//if ($hc_run==0) { //ok
						//jetzt abfragen ob was gefunden fuer status wait==1
						send_log("HID: ".$H[$bcc]['id']);
						send_log("adr_id: ".$H[$bcc]['adr_id']);
						send_log("h_status: ".$H[$bcc]['status']."==1");
						if ($hc_wait>0) { //ok

							$send_it=true;//wird gebraucht um versenden abzufangen wenn aktuell bearbeiteter eintrag

							send_log("OK: HID: ".$H[$bcc]['id']);

							//vor der pruefung auf valide email status schon auf 5 setzen, 
							//ist ggf doppelt gemobbelt, kann aber ggf. unter bestimmten Umstaenden dazu fuehren
							// das eine validierung auf gueltigen mx etwas laenger dauert, 
							//der job mit einem anderen konkurriert und waehrend die 
							//pruefung und mx abfrage laeuft der konkurrierende job sich die adresse krallt, man weiss nix genaues nich ;)
							//h eintrag wird zum bearbeiten gesperrt, status 5!
							send_log("setHStatus 5");
							$QUEUE->setHStatus($H[$bcc]['id'],5);

							send_log("adr_id: ".$H[$bcc]['adr_id']);
							//adresse holen
							send_log("getAdr()");
							$ADR=$ADDRESS->getAdr($H[$bcc]['adr_id']);
							//nur senden wenn korrekte emailadr! und fehlerrate < max errors
							//erstmal ok, kein fehler
							//fehler beim senden? adresse ok?
							$a_error=false;
							$h_error=false;
							$a_status=$ADR[0]['status'];
////todo optimierung: check errors vor blacklist und vor mx, spart ggf zeit und abfragen von adressen die eh zu viele fehler haben oder geblacklisted wurden!

							//status & touch, chck if status=0 and adr status 12							
							send_log("checking adr status for q touch value");	
							if ($Q[$qcc]['touch']==0 && $ADR[0]['status']==12) {
									send_log("touch=0 for this queue, but email ".$ADR[0]['email']." hast status 12, skipped");
									$skipped=true;
									send_log("set h_status to 6 (canceled)");
									send_log("set skipped=true");
									$h_error=true;
									$h_status=6;//cancel, abbruch (status 6), oder 4 fehler?
									//lieber 6, abbruch, da fehlerhafte q's ggf nochmal bearbeitet werden!
							}
							//or touch=2 and status !=12, if touch =1 dont care!
							if ($Q[$qcc]['touch']==2 && $ADR[0]['status']!=12) {
									send_log("touch=2 for this queue, but email ".$ADR[0]['email']." hast NOT status 12, skipped");
									$skipped=true;
									send_log("set h_status to 6 (canceled)");
									send_log("set skipped=true");
									$h_error=true;
									$h_status=6;//cancel, abbruch (status 6), oder 4 fehler?
									//lieber 6, abbruch, da fehlerhafte q's ggf nochmal bearbeitet werden!
							}

							//BLACKLIST prüfen
							if ($Q[$qcc]['check_blacklist']) {
								send_log("checking blacklist");	
								if ($BLACKLIST->isBlacklisted($ADR[0]['email'])) {
									//wenn adr auf blacklist steht, fehler setzen und abbrechen
									send_log("email ".$ADR[0]['email']." matches the active blacklist");
									//statt a_error setzen wir h_error!
									#$a_error=true;
									//aber skipped nutzen
									$skipped=true;
									send_log("set h_status to 6 (canceled)");
									send_log("set skipped=true");
									$h_error=true;
									$h_status=6;//cancel, abbruch (status 6), oder 4 fehler?
									//lieber 6, abbruch, da fehlerhafte q's ggf nochmal bearbeitet werden!
								} else {
									send_log("OK, does not match the active blacklist");
								}
							}

							//email pruefen
							$check_mail=checkEmailAdr($ADR[0]['email'],$EMailcheck_Sendit);
							//if !a_error auch abfragen wegen blacklist pruefung oben!
							//if (!$a_error && $check_mail[0] && $ADR[0]['errors']<=$C[0]['max_mails_retry']) {
							//statt a_error nehmen wir jetzt h_error! das hat den grund das adressen in der blacklist als fehlerhaft markiert wurden mit a_error
							//das waere aber unlogisch! stattdessen h_error und h_status=6
							if (!$skipped && !$h_error && $check_mail[0] && $ADR[0]['errors']<=$C[0]['max_mails_retry']) {
								send_log("checkemail: OK");
								//wenn adresse auch wirklich aktiv etc.
								if ($ADR[0]['aktiv']==1) {
									send_log("Aktiv: OK");
									//status adresse pruefen , kann sich seit eintrag in die liste geaendert haben!
									if ($ADR[0]['status']==1 || $ADR[0]['status']==2 || $ADR[0]['status']==3 || $ADR[0]['status']==4  || $ADR[0]['status']==10 || $ADR[0]['status']==12) {
										send_log("Adr-Status: OK (1|2|3|4|10|12)");
										$h_status=5;
									} else {//adr status
										//////wenn adresse nicht richtigen status, status geaendert wurde nachdem h sendeliste erzeugt....
										/////$a_error=true;
										/////$a_status=8;//fehler, status changed!
										////nein ! ^^ fehler! wir belassen den alten status!!!!
										send_log("Adr-Status: NOT OK !=(1|2|3|4|10|12)");
										$h_status=4;//fehler , aber hier machen wir nen fehler!
										$h_error=true;
									}//adr status

								} else {//adr aktiv
										//addresse nicht aktiv
										//$a_status=8;//fehler, status changed! // adresse wurde zwischenzeitlich deaktiviert, wir belassen alten status!!!
										send_log("Adr not active");
										$h_status=4;//fehler
										$h_error=true;
										//$a_error=true;wir belassen alten status!!!
								}//adr aktiv

							} // if !skipped ...etc

							if (!$check_mail[0] || $ADR[0]['errors']>$C[0]['max_mails_retry']) {
								$a_status=9;//fehlerhafte adr o. ruecklaeufer
								$a_error=true;
								$h_status=4;//fehler
								$h_error=true;
								//adr add memo:
								$log_msg="ERROR: invalid email (".$ADR[0]['email'].") [".$check_mail[1]."] or reached max errors [".$ADR[0]['errors']." of max. ".$C[0]['max_mails_retry']."]";
								$ADDRESS->addMemo($H[$bcc]['adr_id'],"send_it: ".$log_msg);
								send_log($log_msg);
							}//wenn errors > max errors oder !check_mail

							if ($a_error) {
								$log_msg="ERROR: set adr to status=".$a_status;
								$ADDRESS->addMemo($H[$bcc]['adr_id'],"send_it: ".$log_msg);
								send_log($log_msg);
								
								//$ADDRESS->setStatus($H[$bcc]['adr_id'],$a_status);
								$ADDRESS->setStatus($ADR[0]['id'],$a_status);
								$ADDRESS->setAError($ADR[0]['id'],($ADR[0]['errors']+1));//fehler um 1 erhoehen
								//ADR Status
								$log_msg="adr errors (new): ".($ADR[0]['errors']+1);
								$ADDRESS->addMemo($H[$bcc]['adr_id'],"send_it: ".$log_msg);
								send_log($log_msg);
							}

							send_log("set h_status=$h_status");
							$QUEUE->setHStatus($H[$bcc]['id'],$h_status);
							$created=date("Y-m-d H:i:s");
							$QUEUE->setHSentDate($H[$bcc]['id'],$created);


							//wenn kein fehler aufgetreten... dann mail vorbereiten

							if (!$a_error && !$h_error)	{
								#if (!$massmail) {

									//add some additional personalized headers
									//2do: make this headers an option, add user defined headers
									$email_obj->SetEncodedHeader("X-TM_ACODE",$ADR[0]['code']);  
									$email_obj->SetEncodedHeader("X-TM_AID",$ADR[0]['id']);

									$TM_MESSAGE_ID=$H[$bcc]['nl_id'].".".$H[$bcc]['q_id'].".".$H[$bcc]['id']."-".md5($ADR[0]['email'])."-".$ADR[0]['id'].".".$ADR[0]['code'];
									#.".".microtime();
									$email_obj->SetEncodedHeader("X-TM_MESSAGE_ID",$TM_MESSAGE_ID);


								/*************************************************/
								/* inline images (1090rc2)*/
								/*************************************************/
								
								/*
								inline images, now we have the parsed html message body, lets catch the html dom source, get image sources 
								and replace all local hosted images excluding blindimage php script src. fetch image src from html, make src list, 
								clean list, remove doubles, remove external images, exclude blindimage script, create arry from list 
								and save image paths. later attach images in array to mail and replace html src with new multipart ids as for inline images! :)
								we use img arc as key for arrays, so now check for doubles necessary
								replace IMAGE1 in parseNL via new parameter:
								parseNL neuen funktionsparameter: $values=array();
								values['IMAGE1']="something";
								*/
								
								//get setting from q
								//q use setting from nl but can be overwritten for single q's
								$use_inline_images=$Q[$qcc]['use_inline_images'];//0=off, 1=only local images, 2=include external http images, url_fopen required? // -1= no changes for q, nl setting is used, otherwise overwrite setting from nl!

								//inline image message parts will be cached, so we need to generate and add inline images only on the first run (hcc==0)
								//BUT html changes and we have to parse html dom for every mail! and replace img src with inline cid!!!
								//we will reuse the nlbody_html_images array to store cid for img src!
								//use_inline_images = 0: no inline images, use html src as is, no img src replacement
								//use_inline_images = 1 (>0): create inline images only for local hosted images
								//use_inline_images = 2 (>1): also load external images via http
								//if local image, we support another method to include the images. if local, we load the local file from local path instead of url
								if ($use_inline_images>0) {
									send_log ("use inline images");
									if ($use_inline_images==1) {
										send_log ("support only local img src");
									}
									if ($use_inline_images>1) {
										send_log ("support external img src");
									}
									if ($hcc==0) {
										send_log("first run, hcc==0, init array to store img src and file/cid");
										$nlbody_html_images=Array();//array where all images in html src get saved
										//nlbody_html_images[src][src|file|cid] uses src as index, src contains http src, file is local path, cid is inline content id
										//we add parts before parseNL, but only on the first run
										//we only do that on the first run (hcc==0) and cache filparts!
										//Array is reused on every run, so initialzed only on hcc==0
										//we will reuse the nlbody_html_images array to store cid for img src!
										$nlbody_img_c=0;//counter
										$nlbody_img_ext_c=0;//counter, external images
										$nlbody_img_loc_c=0;//counter, local images
										$nlbody_img_tot_c=0;//counter, total, include excluded external if local img src only
									}//hcc==0
									
									if ($hcc==0) {//first run
										//check for IMAGE1
										//IMAGE1 is special case! its replaced in parseNL with img src= etc
										//so we do not have the img src yet im dom!
										//we check for an existing image1 file for current nl and add message part
										//later on we pass the cid as new value for image1 to parseNL
										send_log ("check for IMAGE1");
										//$nl_new_values['IMAGE1']=""; dont do that! do not init as empty string, parseNL checks if key exists and accepts empty values!!!! anyway. may not a problem. but its not necessary to set IMAGE1 to "";
										//similar static code to get image1 name is in tm_NL->parseNL									
										$NL_Imagename1="nl_".date_convert_to_string($NL[0]['created'])."_1.jpg";
										send_log ($tm_nlimgpath."/".$NL_Imagename1);
										if (file_exists($tm_nlimgpath."/".$NL_Imagename1)) {
											$IMAGE1_URL=$tm_URL_FE."/".$tm_nlimgdir."/".$NL_Imagename1;
											//memo: add array entry, but not necessary, 
											//but therefore we have to check for image1url when adding other inline images and prevent adding image1 messagepart again! 
											//the easier solution would be to not set array entry for image1, but so we have all image src in one array! ;-)
											$nlbody_html_images[$IMAGE1_URL]['src']=$IMAGE1_URL;
											$nlbody_html_images[$IMAGE1_URL]['file']=$tm_nlimgpath."/".$NL_Imagename1;
											send_log("exists :) now creating message part for inline cid.");
											$tmp_inlineimage=array(
												"FileName"=>$nlbody_html_images[$IMAGE1_URL]["file"],
												"Content-Type"=>"automatic/name",
												"Disposition"=>"inline",
												"Cache"=>1
											);
											
											$email_obj->CreateFilePart($tmp_inlineimage,$tmp_image_part);
											$nlbody_html_images[$IMAGE1_URL]["cid"]=$email_obj->GetPartContentID($tmp_image_part);
											$nlbody_html_images[$IMAGE1_URL]["part"]=$tmp_image_part;
											send_log("IMAGE1 inline cid is:".$nlbody_html_images[$IMAGE1_URL]["cid"]);
											$IMAGE1="<img src=\"cid:".$nlbody_html_images[$IMAGE1_URL]["cid"]."\" border=0 alt=\"Image1\" id=\"tm_Image1\">";
											$nl_new_values['IMAGE1']=$IMAGE1;										
											send_log("new parseNL value of IMAGE1 is: ".display($nl_new_values['IMAGE1']));
										}//files exists, IMAGE1
									}//hcc==0
									//ok, done, image1 cid saved in nl_new_values
									//now get dom source and replace all other image sources: optionally local only or also load external images, requires url_fopen enabled  etc
									//we do this for every mail, because html changes and we need to replace img src again.... however
									$nlbody_html_dom = str_get_html($NL[0]['body']);
									//get image sources from dom and replace
									// Find all images
									//Create images array
									//this is also only necessary on the first run, no need to search for img src on every mail, only personalized parameters may change but no images
									if ($hcc==0) {
										foreach($nlbody_html_dom->find('img') as $nlbody_html_img) {
											$nlbody_img_tot_c++;
											send_log ("found img src: ".$nlbody_img_tot_c." ".$nlbody_html_img->src);
											//strip and replace http://local.domain.tld ... only images hosted locally within the tellmatic newsletter images dir are used! ! 
											//if image src contains http://your-domain.tld/tellmatic-install.dir/files/newsletter/images ($tm_nlimgdir)
											//this is because we only can access local images from a path without downloading them!
											//we search for $tm_URL_FE and $tm_nlimgdir: $tm_URL_FE."/".$tm_nlimgdir									
	
											//if use_inline_images<2 we only add local images, if >1 we include external sources										
											
											//check for local domainname and dir											
											$tmp_pos = strpos($nlbody_html_img->src, $tm_URL_FE."/".$tm_nlimgdir);
											#send_log("pos check:".$tmp_pos);
											if($tmp_pos === false && $use_inline_images<2) {
												//external img src found and no external supported
	 											send_log("external images disabled, skipping");
	 											$nlbody_img_ext_c++;
											}//pos===true && use_inline_images<2
	
											if($tmp_pos === false && $use_inline_images>1) {//external image and external supported / ==2
												$nlbody_img_ext_c++;
												$nlbody_img_c++;
												send_log("found external image:".$nlbody_img_ext_c);
												//add array element
												$nlbody_html_images[$nlbody_html_img->src]["src"]=$nlbody_html_img->src;
												//external image: we use img src as value for [file]!!!!!
												$nlbody_html_images[$nlbody_html_img->src]["file"]=$nlbody_html_img->src;
												send_log("...src=".$nlbody_html_img->src);
												send_log("...file=".$nlbody_html_img->src);
											}//pos===true && use_inline_images<2
											
											if($tmp_pos === true || $tmp_pos ===0)  {//local image
												//local newsletter image found
												$nlbody_img_loc_c++;
												$nlbody_img_c++;
												send_log("found local image:".$nlbody_img_loc_c);
												//add array element
												$nlbody_html_images[$nlbody_html_img->src]["src"]=$nlbody_html_img->src;
												//local file?
												//entweder:
												//tm_URL_FE durch TM_PATH ersetzen
												//oder
												//$tm_URL_FE."/".$tm_nlimgdir durch $tm_nlimgpath 
												//ersetzen
												$tmp_img_file=str_replace($tm_URL_FE,TM_PATH,$nlbody_html_img->src);
												$nlbody_html_images[$nlbody_html_img->src]["file"]=$tmp_img_file;
												send_log("...src=".$nlbody_html_img->src);
												send_log("...file=".$tmp_img_file);
											}//pos===true
										}//foreach
										
										//2do: add css images!
										
										send_log("found ".$nlbody_img_tot_c." images total");
										send_log("found ".$nlbody_img_loc_c." local images");
										send_log("found ".$nlbody_img_ext_c." external images");
										send_log("found ".$nlbody_img_c." inline images to add");
									}//hcc==0

									//now we replace all img src via html dom and modify NL[0][body] on the fly!!!!
									//this is because NL[0] is passed directly to parseNL and parseNL will now use bodypart and textpart from nl instead from file (1090rc2) 
									foreach ($nlbody_html_images as $nlbody_html_image) {
										if ($hcc==0) {
	 										send_log("create inline images file parts, hcc==0, first run");
	 										//exclude image1 src!!! already added!!!
	 										//the easier solution would be to not set array entry for image1, but so we have all image src in one array!
	 										if ($nlbody_html_image['src']!=$IMAGE1_URL) {
												$tmp_inlineimage=array(
													"FileName"=>$nlbody_html_image["file"],
													"Content-Type"=>"automatic/name",
													"Disposition"=>"inline",
													"Cache"=>1
												);
												send_log("add inline image, src: ".$nlbody_html_image["src"]."=> file: ".$nlbody_html_image["file"]);
												$email_obj->CreateFilePart($tmp_inlineimage,$tmp_image_part);
												$nlbody_html_images[$nlbody_html_image["src"]]["part"]=$tmp_image_part;
												$nlbody_html_images[$nlbody_html_image["src"]]["cid"]=$email_obj->GetPartContentID($tmp_image_part);
												send_log("cid=".$nlbody_html_images[$nlbody_html_image["src"]]["cid"]);
											}//if !$IMAGE1_URL
										}//hcc==0
											//now replace img src in html dom, every mail!
											send_log("replace img src ".$nlbody_html_image["src"]." => cid:".$nlbody_html_images[$nlbody_html_image["src"]]["cid"]);
											$NL[0]['body']=str_replace($nlbody_html_image["src"],"cid:".$nlbody_html_images[$nlbody_html_image["src"]]["cid"],$NL[0]['body']);
											//or better use the php html dom class find and replace methods
											//this prevents cleartext urls in html, e.g. if you type the url of an image as text and not as html tag!
											//this should not happen very often, but anyway
											#$nlbody_html_dom->find('img[src='.$nlbody_html_image["src"].']')->src = "cid:".$nlbody_html_images[$nlbody_html_image["src"]]["cid"];
									}//foreach

									//now save new dom html and replace body in NL[0] current newsletter!
									#$NL[0]['body']=$nlbody_html_dom->save();
									//done
								}//use inline images >0

								/*************************************************/

									//new, 1088, use parseNL
									//new, 1090rc2 parseNL will use body from NL[0] === $data['nl']
									//new, 1090rc2, parseNL will take values for nl variables like IMAGE1
									//new, 1090rc2, parseNL will set NEWSLETTER->parseLog as log array. output with foreach NEWSLETTER->parseLog... and sendlog(...)
									send_log("parse Newsletter");
									$NEWSLETTER->parseLog=Array();//init parseLog Array
									//parse html part
									$NLBODY="empty htmlpart";
									if ($NL[0]['content_type']=="html" || $NL[0]['content_type']=="text/html") {
										send_log("parse Newsletter html version");
										$NLBODY=$NEWSLETTER->parseNL(Array('nl'=>$NL[0],'adr'=>$ADR[0],'h'=>Array('id'=>$H[$bcc]['id']),'q'=>Array('id'=>$H[$bcc]['q_id'])),"html",$nl_new_values);//new para $values to set values for nl vars, e.g. image1 inline image cid
									}
									//parse text part
									$NLBODY_TEXT="empty textpart";
									if ($NL[0]['content_type']=="text" || $NL[0]['content_type']=="text/html") {
										send_log("parse Newsletter text version");
											$NLBODY_TEXT=$NEWSLETTER->parseNL(Array('nl'=>$NL[0],'adr'=>$ADR[0],'h'=>Array('id'=>$H[$bcc]['id']),'q'=>Array('id'=>$H[$bcc]['q_id'])),"text",$nl_new_values);
									}

									//parseNL set NEWSLETTER->parseLog, output here:
									send_log("start parseLog:");
									foreach ($NEWSLETTER->parseLog as $parseLogLine) {
										send_log($parseLogLine);
									}									
									send_log("end parseLog --");

								#}//!massmail

													
								
								send_log("create Mail, set To/From and prepare Header");
								//Name darf nicht = email sein und auch kein komma enthalten, plaintext!
								//to etc fuer personalisiertes nl:
								#if (!$massmail) {
									#$SUBJ = $NEWSLETTER->parseSubject( Array('text'=>$NL[0]['subject'], 'date'=>date(TM_NL_DATEFORMAT), 'adr'=>$ADR[0]) );//hmmm, we should use date from send_at in q!
									//now get date in parse method itself! do not give a 'data' entry
									$SUBJ = $NEWSLETTER->parseSubject( Array('text'=>$NL[0]['subject'], 'adr'=>$ADR[0],'q'=>Array('id'=>$H[$bcc]['q_id'])) );//add q values, only id now, but could be complete q array..... 'q'=>$Q[$qcc]
									send_log("Subject: ".$NL[0]['subject']);
									send_log("Subject (parsed): ".$SUBJ);
									$email_obj->SetEncodedHeader("Subject",$SUBJ);

									send_log("personal Mailing, add TO:");
									$To=$ADR[0]['email'];
									if (!empty($NL[0]['rcpt_name'])) {
										$RCPT_Name_TMP=$NL[0]['rcpt_name'];
									} else {
										if (!empty($C[0]['rcpt_name'])) {
											$RCPT_Name_TMP=$C[0]['rcpt_name'];
										} else {
											$RCPT_Name_TMP="Tellmatic Newsletter";
										}
									}
									#$RCPT_Name = $NEWSLETTER->parseRcptName( Array('text'=>$RCPT_Name_TMP, 'date'=>date(TM_NL_DATEFORMAT), 'adr'=>$ADR[0]) );//hmmm, we should use date from send_at in q!									$RCPT_Name = str_replace($RCPT_Name_search, $RCPT_Name_replace, $RCPT_Name_TMP);
									//now get date in parse method itself! do not give a 'data' entry
									$RCPT_Name = $NEWSLETTER->parseRcptName( Array('text'=>$RCPT_Name_TMP, 'adr'=>$ADR[0],'q'=>Array('id'=>$H[$bcc]['q_id'])) );
									send_log("rcpt_name: ".$NL[0]['rcpt_name']);
									send_log("rcpt_name_tmp: ".$RCPT_Name_TMP);
									send_log("rcpt_name_parsed: ".$RCPT_Name);
									//rcpt name should NOT be empty AND NOT email!
									$ToName=$RCPT_Name;//hmpf....
									$email_obj->SetEncodedEmailHeader("To",$To,$ToName);//bei massenmailing tun wir das schon oben
								#}//!massmail
								
								send_log($ADR[0]['email']);
								//send_log(  "\n      count NL");
								$ADDRESS->addNL($ADR[0]['id']);//newsletter counter hochzaehlen!
								send_log("no.=".$bcc.", email=".$ADR[0]['email'].", adr_id=".$ADR[0]['id'].", status_A=".$ADR[0]['status']."/".$a_status.", status_H=".$H[$bcc]['status']."/".$h_status.",err=".$ADR[0]['errors']);
							} else {// !$a_error && !$h_error
								send_log("*** Address: Error");
							}
						} else {//hc_run==0 bzw $hc_wait>0
							//nix machen
							//send_log(  "\n *** Eintrag wird gerade versendet und wird uebersprungen");
							send_log("*** Entry was already processed");
							#if (!$massmail) {
								$send_it=false;
								$skipped=true;
							#}//!massmail
						}
					} else {//if isset h[bcc][id]
						send_log("*** h[][id] not set");
					}
					$ADDRESS->markRecheck($ADR[0]['id'],0);				
#				}//for bcc
	/*
	BCC
	*/
			$send_ok=false;

			if (!$a_error && !$h_error && $send_it)	{
				send_log("add Mail Body Parts");

				$use_textpart=false;
				$use_htmlpart=false;
				
				/*
				 *  It is strongly recommended that when you send HTML messages,
				 *  also provide an alternative text version of HTML page,
				 *  even if it is just to say that the message is in HTML,
				 *  because more and more people tend to delete HTML only
				 *  messages assuming that HTML messages are spam.
				 *
				 *  Multiple alternative parts are gathered in multipart/alternative parts.
				 *  It is important that the fanciest part, in this case the HTML part,
				 *  is specified as the last part because that is the way that HTML capable
				 *  mail programs will show that part and not the text version part.
				 */				
				
				//add mailparts in first run, otherwise: if massmail: use cached body, add nothing, or if personalized: replace part
				//if massmail and first run, or on each run if personalizef mailing: create parts				
				#if ( !$massmail || ($massmail && $hcc==0) ) {
					//create text/html part:
					send_log("Newsletter is from type: '".$NL[0]['content_type']."'");
					//we want mixed multipart, with alternative text/html and attachements, inlineimages and all that
					//text part must be the first one!!!
					if ($NL[0]['content_type']=="text" || $NL[0]['content_type']=="text/html") {
						$use_textpart=true;
						send_log("create TEXT Part");
						//only create part
						$email_obj->CreateQuotedPrintableTextPart($email_obj->WrapText($NLBODY_TEXT),"",$mimepart_text);
					}
					if ($NL[0]['content_type']=="html" || $NL[0]['content_type']=="text/html") {
						$use_htmlpart=true;
						send_log("create HTML Part");
						//only create part
						$email_obj->CreateQuotedPrintableHtmlPart($NLBODY,"",$mimepart_html);
					}

					$partids=array();//array of partnumbers, returned by reference from createpart etc
					if ($use_textpart) {
						array_push($partids,$mimepart_text);
						#if (tm_DEBUG()) send_log(print_r($mimepart_text));
					}
					if ($use_htmlpart) {
						array_push($partids,$mimepart_html);
						#if (tm_DEBUG()) send_log(print_r($mimepart_html));
					}
				#}//!massmail || ($masmail && hcc==0)
					
				//on first entry add body and inline image parts
				if ($hcc==0) {
					//AddAlternativeMultiparts
					//save initial part ids					
					if ($use_textpart) {
						$mimepart_text_init=$mimepart_text;
					}	
					if ($use_htmlpart) {
						$mimepart_html_init=$mimepart_html;
					}
					send_log("add Parts");
					#$email_obj->AddAlternativeMultipart($partids);
					$email_obj->CreateAlternativeMultipart($partids,$body_part);
					
					//All related parts are gathered in a single multipart/related part.
					$related_parts=array(
						$body_part,
					);
					//add inline image parts
					foreach ($nlbody_html_images as $nlbody_html_image) {
						array_push($related_parts, $nlbody_html_image['part']);

					}
					
					$email_obj->AddRelatedMultipart($related_parts);
				}//hcc==0
				
				//if not massmail: only replace part
				//if (!$massmail && $hcc > 0) {
				if (!$massmail && $hcc > 0) {
					send_log("replace Parts for personalized mailing");
					if ($use_htmlpart) {
						$email_obj->ReplacePart($mimepart_html_init,$mimepart_html);
					}
					if ($use_textpart) {
						$email_obj->ReplacePart($mimepart_text_init,$mimepart_text);
					}					
				}//!massmail
				//only add attachements on first run, hcc=0
				if ($hcc==0) {
					//erst jetzt darf der part f.d. attachement hinzugefuegt werden!
					$attachements=$NL[0]['attachements'];
					$atc=count($attachements);
					if ($atc>0) {
						send_log("adding ".$atc." Attachements:");
						foreach ($attachements as $attachfile) {
							if (file_exists($tm_nlattachpath."/".$attachfile['file'])) {
								send_log("add Attachement ".$attachfile['file']);
								$ATTM=array(
									"FileName"=>$tm_nlattachpath."/".$attachfile['file'],
									"Content-Type"=>"automatic/name",
									"Disposition"=>"attachment",
									"Cache"=>1
								);
								$email_obj->AddFilePart($ATTM);
							} else {//file_exists
								send_log("Attachement ".$attachfile['file']." does not exist.");
							}//file_exists
						}//foreach
					}//if count/atc
				}//hcc==0
				
				send_log("added ".$atc." Attachements:");

				$email_obj->GetMessageSize($message_size);
				send_log("Message Size: ".$message_size."B");
				
								
				/************************************************/				
				//place copy of final outgoing mail in imap sent folder
				/************************************************/
				//will come in next releases fully supported in the gui
				if ($Q[$qcc]["save_imap"]==1 && $imap_connected==true) {
					$email_obj->GetMessage($message_plain);
					//imap host init is done in send_it					
					send_log("Save Copy in Sent Folder ".$HOST_IMAP[0]['imap_folder_sent']);
					$Mailer->saveMessage($HOST_IMAP[0]['imap_folder_sent'],$message_plain);
					if (!empty($Mailer->Error)) {
						send_log("Mailer Error ".$Mailer->Error);
					}//!emoty Mailer->Error
				}//if save_imap && imap_connected

				/************************************************/				
				//finally send out mail
				/************************************************/
		
				//Send!
				send_log("SEND Mail");
				$smtp_error="";
				
				if (!tm_DEMO()) $smtp_error=$email_obj->Send();

				if (empty($error)) {
					$send_ok=true;
					send_log("OK");
				} else {
					$send_ok=false;
					send_log("ERROR: ".$smtp_error);
				}
			}// !$a_error && !$h_error
			
			//wenn senden ok....fein!
			if ($send_ok) {
				$a_status=2;//ok
				$h_status=2;//gesendet
			} else {
				//wenn nihct uebersprungen, status markieren
				if (!$skipped) {
					$a_status=10;//sende fehler, wait retry
					$h_status=4;//fehler
					$a_error=true;
				}
				if ($skipped) {
					//wenn uebersprungen, alten status behalten
					$h_status=$H[$bcc]['status'];
					//undefined index? hmmm, check!
				}
			}//send ok

			//alle aktuell bearbeiteten adressen markieren, nl coutner und status, schleife wie bcc!
			//for () {
			//	$ADDRESS->addNL($ADR[0]['id']);//newsletter counter hochzaehlen!
			//}
			
			if (!$skipped) {
				send_log("set h status=$h_status for entry $hcc to $bc -1");
				$created=date("Y-m-d H:i:s");
				for ($bcc=$hcc;$bcc<$bc;$bcc++) {
					if (isset($H[$bcc]['id'])) {
						//H Status setzen
						$QUEUE->setHStatus($H[$bcc]['id'],$h_status);
						$QUEUE->setHSentDate($H[$bcc]['id'],$created);
					}
				}
			}

			if ($skipped) {
				send_log("skipped.");
			}

			//wenn kein address-fehler aufgetreten ist und KEIN Massenmailing!
			#if (!$massmail && !$a_error && !$skipped) {
			if (!$a_error && !$skipped) {
					//ADR Fehler zuruecksetzen....
					$ADDRESS->setAError($ADR[0]['id'],0);//fehler auf 0
					//ADR Status
					if ($ADR[0]['status']!=4) {
						send_log("set Adr status: $a_status");
						$ADDRESS->setStatus($ADR[0]['id'],$a_status);
					}
					send_log("set Adr err changed from ".$ADR[0]['errors']." to: 0");
			}//no error, not skipped
			$time=$T->MidResult();
			send_log("time: ".$time);
			$log_adr_id=0;

			///hmmm, some of this stuff must go to the bcc loop maybe.
			//no nomore bcc loop needed! only 1 entry is processed always since 1090
?>