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

/////////////////////////////////////
//Statistik fuer Formulare
/////////////////////////////////////

if ($set=="frm" && check_dbid($frm_id)) {
	#$_MAIN_OUTPUT.="<a href=\"http://www.tellmatic.org/?c=donate\" target=\"_blank\">".___("Diese Funktion ist leider noch nicht implementiert. Bitte unterstützen Sie die Entwicklung von Tellmatic.")."</a>";
	$FRM=$FORMULAR->getForm($frm_id);
	////////////////////////////////////////////////////////////////////////////////////////
	//allg. Infos
	////////////////////////////////////////////////////////////////////////////////////////
	$_MAIN_OUTPUT.=sprintf(___("Statistik für Formular %s"),"<b>".display($FRM[0]['name'])."</b>");
	$_MAIN_OUTPUT.= "<br>".display($FRM[0]['descr']);

	$_MAIN_OUTPUT.= "<br><br>ID: ".$FRM[0]['id'];

	$created_date=$FRM[0]['created'];
	$updated_date=$FRM[0]['updated'];
	$author=$FRM[0]['author'];
	$editor=$FRM[0]['editor'];

	if ($FRM[0]['aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("tick.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(aktiv)");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("cancel.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("(inaktiv)");
	}

	if ($FRM[0]['subscribe_aktiv']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("user_green.png",___("Aktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Neuangemeldete Adressen sind aktiv.");
	} else {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("user_red.png",___("Inaktiv"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Neuangemeldete Adressen sind deaktiviert.");
	}

	if ($FRM[0]['double_optin']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("arrow_refresh_small.png",___("Double-Opt-In"))."&nbsp;".___("Double-Opt-In");
	}

	if ($FRM[0]['use_captcha']==1) {
		$_MAIN_OUTPUT.=  "<br>".tm_icon("sport_8ball.png",___("Captcha"))."&nbsp;";
		$_MAIN_OUTPUT.=  ___("Captcha").",&nbsp;";
		$_MAIN_OUTPUT.=  sprintf(___("%s Ziffern"),$FRM[0]['digits_captcha']);
		$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung")." :<em>".display($FRM[0]['captcha_errmsg'])."</em>";
	}	

	$_MAIN_OUTPUT.= "<br><br>".sprintf(___("erstellt am: %s von: %s"),$created_date,$author).
							"<br>".sprintf(___("bearbeitet am: %s von %s"),$updated_date,$editor).
							"<br><br>".sprintf(___("Anmeldungen: %s"),$FRM[0]['subscriptions'])." &nbsp;";

	$_MAIN_OUTPUT.= "<br><br>".___("Einstellungen").":";

	$_MAIN_OUTPUT.=	"<br>Submit= ".display($FRM[0]['submit_value']);
	$_MAIN_OUTPUT.=	"<br>Reset= ".display($FRM[0]['reset_value']);

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"email= ".display($FRM[0]['email'])." &nbsp; []<br>Typ: text ";
	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['email_errmsg'])."</em>";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";	
	$_MAIN_OUTPUT.=	"f0= ".display($FRM[0]['f0'])." &nbsp; [".display($FRM[0]['f0_value'])."]<br>Typ: ".$FRM[0]['f0_type']." ";
	if ($FRM[0]['f0_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f0_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f0_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f1= ".display($FRM[0]['f1'])." &nbsp; [".display($FRM[0]['f1_value'])."]<br>Typ: ".$FRM[0]['f1_type']." ";
	if ($FRM[0]['f1_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f1_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f1_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f2= ".display($FRM[0]['f2'])." &nbsp; [".display($FRM[0]['f2_value'])."]<br>Typ: ".$FRM[0]['f2_type']." ";
	if ($FRM[0]['f2_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f2_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f2_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f3= ".display($FRM[0]['f3'])." &nbsp; [".display($FRM[0]['f3_value'])."]<br>Typ: ".$FRM[0]['f3_type']." ";
	if ($FRM[0]['f3_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f3_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f3_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f4= ".display($FRM[0]['f4'])." &nbsp; [".display($FRM[0]['f4_value'])."]<br>Typ: ".$FRM[0]['f4_type']." ";
	if ($FRM[0]['f4_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f4_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f4_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f5= ".display($FRM[0]['f5'])." &nbsp; [".display($FRM[0]['f5_value'])."]<br>Typ: ".$FRM[0]['f5_type']." ";
	if ($FRM[0]['f5_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f5_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f5_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f6= ".display($FRM[0]['f6'])." &nbsp; [".display($FRM[0]['f6_value'])."]<br>Typ: ".$FRM[0]['f6_type']." ";
	if ($FRM[0]['f6_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f6_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f6_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f7= ".display($FRM[0]['f7'])." &nbsp; [".display($FRM[0]['f7_value'])."]<br>Typ: ".$FRM[0]['f7_type']." ";
	if ($FRM[0]['f7_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f7_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f7_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f8= ".display($FRM[0]['f8'])." &nbsp; [".display($FRM[0]['f8_value'])."]<br>Typ: ".$FRM[0]['f8_type']." ";
	if ($FRM[0]['f8_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f8_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f8_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.=	"<BR><BR>";
	$_MAIN_OUTPUT.=	"f9= ".display($FRM[0]['f9'])." &nbsp; [".display($FRM[0]['f9_value'])."]<br>Typ: ".$FRM[0]['f9_type']." ";
	if ($FRM[0]['f9_required']==1) 	$_MAIN_OUTPUT.= "(".___("Pflichtfeld").")";
	$_MAIN_OUTPUT.= "<br>".___("Fehlermeldung").": <em>".display($FRM[0]['f9_errmsg'])."</em>";
	$_MAIN_OUTPUT.= "<br>".___("Expression").": ".display($FRM[0]['f9_expr'])."";
	$_MAIN_OUTPUT.=	"";

	$_MAIN_OUTPUT.= "<br><br>".___("Anmeldungen über dieses Formular werden in die folgenden Gruppen eingeordnet:")."<br>";
	$_MAIN_OUTPUT.= "";

	$GRP=$ADDRESS->getGroup(0,0,$FRM[0]['id'],0);
	$acg=count($GRP);
	for ($accg=0;$accg<$acg;$accg++) {
		$_MAIN_OUTPUT .= "<br>".display($GRP[$accg]['name'])."";
	}
	$_MAIN_OUTPUT.= "";
	#$_MAIN_OUTPUT.= "<hr>";

}//frm
?>