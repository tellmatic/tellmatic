<?php
/********************************************************************************/
/* this file is part of: / diese Datei ist ein Teil von:                        	*/
/* tellmatic, the newslettermachine                                             	*/
/* tellmatic, die Newslettermaschine                                            */
/* 2006/2010 by Volker Augustin, multi.art.studio Hanau                            */
/* Contact/Kontakt: info@tellmatic.org                                      */
/* Homepage: www.tellmatic.org                                                   */
/* leave this header in file!                                                   */
/* diesen Header nicht loeschen!                                                */
/* check Homepage for Updates and more Infos                                    */
/* Besuchen Sie die Homepage fuer Updates und weitere Infos                     */
/********************************************************************************/

if ($check) {

	$MESSAGE.=tm_message_notice(___("Beispieldaten werden erzeugt..."));
	
	if (!tm_DEMO()) {
/***********************************************************/
//create example data
/***********************************************************/
//nl gruppe
		$NEWSLETTER=new tm_NL();

		//create a new group
		$example_nl_group_1_id=$NEWSLETTER->addGrp(Array("name"=>"Newsletter Group 1", "descr"=>"zum testen / for testings", "aktiv"=>1, "author"=>"install", "created"=>$created, "editor"=>"install", "updated"=>$created));
		//make this group the default group
		$NEWSLETTER->setGRPStd($example_nl_group_1_id,1);
		
		//create a 2nd example group
		$example_nl_group_2_id=$NEWSLETTER->addGrp(Array("name"=>"Newsletter Group 2", "descr"=>"zum testen / for testings", "aktiv"=>0, "author"=>"install", "created"=>$created, "editor"=>"install", "updated"=>$created));

		//create a 3rd group used for templates (subscribe form)
		$example_nl_group_3_id=$NEWSLETTER->addGrp(Array("name"=>"Subscribe", "descr"=>"templates for subscribe mails send by subscribe-forms", "aktiv"=>1, "author"=>"install", "created"=>$created, "editor"=>"install", "updated"=>$created));

		//add a first testnewsletter in first group
		$example_nl_1_id=$NEWSLETTER->addNL(
								Array(
									"subject"=>"{DATE} Newsletter 1",
									"body"=>$example_nl_body_html,
									"body_text"=>$example_nl_body_text,
									"body_head"=>"",
									"body_foot"=>"",
									"aktiv"=>1,
									"status"=>1,
									"massmail"=>0,
									"link"=>"http://www.tellmatic.org",
									"created"=>date("Y-m-d H:i:s"),
									"author"=>"install",
									"grp_id"=>$example_nl_group_1_id,
									"rcpt_name"=>"Newsletter",
									"track_image"=>"_blank",
									"content_type"=>"text/html",
									"attachements"=>Array(),
									"is_template"=>0,
									"title"=>'Titel',
									"title_sub"=>'Titel 2',
									"summary"=>'Zusammenfassender Text zBsp. zur Anzeige auf der Webseite etc.',
									"track_personalized"=>1,
									"use_inline_images"=>0
									)
								);

		//add a second testnewsletter in 2nd group
		$example_nl_2_id=$NEWSLETTER->addNL(
								Array(
									"subject"=>"{DATE} Newsletter 2",
									"body"=>$example_nl_body_html,
									"body_text"=>$example_nl_body_text,
									"body_head"=>"",
									"body_foot"=>"",
									"aktiv"=>1,
									"status"=>1,
									"massmail"=>0,
									"link"=>"http://www.tellmatic.org",
									"created"=>date("Y-m-d H:i:s"),
									"author"=>"install",
									"grp_id"=>$example_nl_group_2_id,
									"rcpt_name"=>"Newsletter",
									"track_image"=>"_blank",
									"content_type"=>"text/html",
									"attachements"=>Array(),
									"is_template"=>0,
									"title"=>'Titel',
									"title_sub"=>'Titel 2',
									"summary"=>'Zusammenfassender Text zBsp. zur Anzeige auf der Webseite etc.',
									"track_personalized"=>1,
									"use_inline_images"=>0
									)
								);

		//add newsletter for doubleoptin message, use 3rd example group
		$example_nl_doptin_id=$NEWSLETTER->addNL(
								Array(
									"subject"=>"Newsletteranmeldung / Subscribe {DATE}",
									"body"=>$example_nl_doptin_body_html,
									"body_text"=>$example_nl_doptin_body_text,
									"body_head"=>"",
									"body_foot"=>"",
									"aktiv"=>1,
									"status"=>1,
									"massmail"=>0,
									"link"=>"http://www.tellmatic.org",
									"created"=>date("Y-m-d H:i:s"),
									"author"=>"install",
									"grp_id"=>$example_nl_group_3_id,
									"rcpt_name"=>"Newsletter",
									"track_image"=>"_blank",
									"content_type"=>"text/html",
									"attachements"=>Array(),
									"is_template"=>1,
									"title"=>'',
									"title_sub"=>'',
									"summary"=>'',
									"track_personalized"=>1,
									"use_inline_images"=>0
									)
								);
		
		//add newsletter for welcome/subscribe message, use 3rd example group
		$example_nl_welcome_id=$NEWSLETTER->addNL(
								Array(
									"subject"=>"Willkommen / Welcome {DATE}",
									"body"=>$example_nl_welcome_body_html,
									"body_text"=>$example_nl_welcome_body_text,
									"body_head"=>"",
									"body_foot"=>"",
									"aktiv"=>1,
									"status"=>1,
									"massmail"=>0,
									"link"=>"http://www.tellmatic.org",
									"created"=>date("Y-m-d H:i:s"),
									"author"=>"install",
									"grp_id"=>$example_nl_group_3_id,
									"rcpt_name"=>"Newsletter",
									"track_image"=>"_blank",
									"content_type"=>"text/html",
									"attachements"=>Array(),
									"is_template"=>1,
									"title"=>'',
									"title_sub"=>'',
									"summary"=>'',
									"track_personalized"=>1,
									"use_inline_images"=>0
									)
								);

		//add newsletter for update mail, use 3rd example group
		$example_nl_update_id=$NEWSLETTER->addNL(
								Array(
									"subject"=>"Aktualisierung / Update {DATE}",
									"body"=>$example_nl_update_body_html,
									"body_text"=>$example_nl_update_body_text,
									"body_head"=>"",
									"body_foot"=>"",
									"aktiv"=>1,
									"status"=>1,
									"massmail"=>0,
									"link"=>"http://www.tellmatic.org",
									"created"=>date("Y-m-d H:i:s"),
									"author"=>"install",
									"grp_id"=>$example_nl_group_3_id,
									"rcpt_name"=>"Newsletter",
									"track_image"=>"_blank",
									"content_type"=>"text/html",
									"attachements"=>Array(),
									"is_template"=>1,
									"title"=>'',
									"title_sub"=>'',
									"summary"=>'',
									"track_personalized"=>1,
									"use_inline_images"=>0
									)
								);

//add link groups
$LINKS=new tm_LNK();
		$lnk_group_id_1=$LINKS->addGrp(Array(
				"short"=>"tellmatic",
				"name"=>"Tellmatic",
				"descr"=>"Tellmatic Links",
				"aktiv"=>1,
				"created"=>date("Y-m-d H:i:s"),
				"author"=>"install"
				));
		$lnk_group_id_2=$LINKS->addGrp(Array(
				"short"=>"index",
				"name"=>"Index",
				"descr"=>"Newsletter Index",
				"aktiv"=>1,
				"created"=>date("Y-m-d H:i:s"),
				"author"=>"install"
				));
//add links		
		$LINKS->add(Array(
					"short"=>"tm.home",
					"name"=>"Tellmatic Homepage",
					"url"=>"http://www.tellmatic.org",
					"descr"=>"Tellmatic Homepage",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install"
					),
					Array(0 => $lnk_group_id_1));
		$LINKS->add(Array(
					"short"=>"tm.doc",
					"name"=>"Tellmatic Documentation",
					"url"=>"http://doc.tellmatic.org",
					"descr"=>"Tellmatic Online Documentation",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install"
					),
					Array(0 => $lnk_group_id_1));
		$LINKS->add(Array(
					"short"=>"tm.donate",
					"name"=>"Donate to Tellmatic",
					"url"=>"http://www.tellmatic.org/donate",
					"descr"=>"Tellmatic Donation",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install"
					),
					Array(0 => $lnk_group_id_1));
		$LINKS->add(Array(
					"short"=>"tm.contact",
					"name"=>"Contact / Kontakt",
					"url"=>"http://www.tellmatic.org/contact&sendForm=1&name={F1} {F2}&email={EMAIL}&code={CODE}&adrid={ADRID}&subject=Test",
					"descr"=>"Tellmatic Contact",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install"
					),
					Array(0 => $lnk_group_id_1));



		$LINKS->add(Array(
					"short"=>"idx.top",
					"name"=>"Top",
					"url"=>"#top",
					"descr"=>"Jump to Top",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install"
					),
					Array(0 => $lnk_group_id_2));
		$LINKS->add(Array(
					"short"=>"idx.bottom",
					"name"=>"Bottom",
					"url"=>"#bottom",
					"descr"=>"Jump to Bottom",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install"
					),
					Array(0 => $lnk_group_id_2));


//adr gruppe
		$ADDRESS=new tm_ADR();

		$ADDRESS->addGrp(Array("name"=>"ADR Group 1", "descr"=>"zum testen / for testings", "aktiv"=>1, "prod"=>1, "author"=>"install", "created"=>$created, "editor"=>"install", "updated"=>$created, "public"=>1, "public_name"=>"Test 1"));
		$ADDRESS->setGRPStd(1,1);
		$ADDRESS->addGrp(Array("name"=>"ADR Group 2", "descr"=>"zum testen / for testings", "aktiv"=>0, "prod"=>0, "author"=>"install", "created"=>$created, "editor"=>"install", "updated"=>$created, "public"=>0, "public_name"=>"Test 2"));
//adr : ok, bounce
			$code=rand(111111,999999);
			$new_adr_grp[0]=1;
			$ADDRESS->addAdr(Array(
					"email"=>"test@tellmatic.org",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install",
					"status"=>3,
					"code"=>$code,
					"proof"=>0,
					"source"=>"user",
					"source_id"=>1,
					"source_extern_id"=>0,					
					"memo"=>$created,
					"f0"=>"Herr",
					"f1"=>"Telly",
					"f2"=>"Tellmatic",
					"f3"=>"",
					"f4"=>"",
					"f5"=>"",
					"f6"=>"",
					"f7"=>"",
					"f8"=>"",
					"f9"=>""
					),
					$new_adr_grp);

			$code=rand(111111,999999);
			$new_adr_grp[0]=1;
			$ADDRESS->addAdr(Array(
					"email"=>"bounce@tellmatic.org",
					"aktiv"=>1,
					"created"=>date("Y-m-d H:i:s"),
					"author"=>"install",
					"status"=>1,
					"code"=>$code,
					"proof"=>0,
					"source"=>"user",
					"source_id"=>1,
					"source_extern_id"=>0,					
					"memo"=>$created,
					"f0"=>"Herr",
					"f1"=>"Tello",
					"f2"=>"Bounce",
					"f3"=>"",
					"f4"=>"",
					"f5"=>"",
					"f6"=>"",
					"f7"=>"",
					"f8"=>"",
					"f9"=>""
					),
					$new_adr_grp);


//form
		$FORMULAR=new tm_FRM();
		$new_adr_grp[0]=1;
		$FORMULAR->addForm(Array(
				"name"=>"Form 1",
				"action_url"=>"",
				"descr"=>"zum testen / for testing",
				"aktiv"=>1,
				"standard"=>1,
				"created"=>date("Y-m-d H:i:s"),
				"author"=>"install",
				"double_optin"=>1,
				"use_captcha"=>1,
				"digits_captcha"=>4,
				"check_blacklist"=>1,
				"proof"=>1,
				"force_pubgroup"=>0,
				"overwrite_pubgroup"=>0,
				"multiple_pubgroup"=>1,
				"subscribe_aktiv"=>1,
				"nl_id_doptin"=>$example_nl_doptin_id,
				"nl_id_greeting"=>$example_nl_welcome_id,
				"nl_id_update"=>$example_nl_update_id,
				"message_doptin"=>"Double OptIn Message",
				"message_greeting"=>"Greeting Message",
				"message_update"=>"Update Message",
				"host_id"=>1,
				"submit_value"=>"Subscribe / Anmelden",
				"reset_value"=>"Reset / Eingaben zurücksetzen",
				"email"=>"E-Mail-Adresse",
				"f0"=>"Anrede",
				"f1"=>"Name",
				"f2"=>"Name2",
				"f3"=>"",
				"f4"=>"",
				"f5"=>"",
				"f6"=>"",
				"f7"=>"",
				"f8"=>"",
				"f9"=>"",
				"f0_type"=>"select",
				"f1_type"=>"text",
				"f2_type"=>"text",
				"f3_type"=>"text",
				"f4_type"=>"text",
				"f5_type"=>"text",
				"f6_type"=>"text",
				"f7_type"=>"text",
				"f8_type"=>"text",
				"f9_type"=>"text",
				"f0_required"=>0,
				"f1_required"=>1,
				"f2_required"=>1,
				"f3_required"=>0,
				"f4_required"=>0,
				"f5_required"=>0,
				"f6_required"=>0,
				"f7_required"=>0,
				"f8_required"=>0,
				"f9_required"=>0,
				"f0_value"=>"--;Frau;Herr;Firma;Verein",
				"f1_value"=>"",
				"f2_value"=>"",
				"f3_value"=>"",
				"f4_value"=>"",
				"f5_value"=>"",
				"f6_value"=>"",
				"f7_value"=>"",
				"f8_value"=>"",
				"f9_value"=>"",
				"f0_expr"=>"",
				"f1_expr"=>"^[A-Za-z_ ][A-Za-z0-9_ ]*$",
				"f2_expr"=>"^[A-Za-z_ ][A-Za-z0-9_ ]*$",
				"f3_expr"=>"",
				"f4_expr"=>"",
				"f5_expr"=>"",
				"f6_expr"=>"",
				"f7_expr"=>"",
				"f8_expr"=>"",
				"f9_expr"=>"",
				"email_errmsg"=>"Ungültige E-Mail-Adresse",
				"captcha_errmsg"=>"Spamschutz! Bitte geben Sie untenstehenden Zahlencode ein.",
				"blacklist_errmsg"=>"Blacklisted",
				"pubgroup_errmsg"=>"Bitte Gruppe wählen",
				"f0_errmsg"=>"",
				"f1_errmsg"=>"Bitte füllen Sie das Feld Name aus",
				"f2_errmsg"=>"Bitte füllen Sie das Feld Name2 aus",
				"f3_errmsg"=>"",
				"f4_errmsg"=>"",
				"f5_errmsg"=>"",
				"f6_errmsg"=>"",
				"f7_errmsg"=>"",
				"f8_errmsg"=>"",
				"f9_errmsg"=>""
				),
				$new_adr_grp);
			$FORMULAR->setStd(1);
			
			
	}//demo
}//if check
?>