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

			//Formular generieren und anzeigen lassen
			//captcha code
			//captcha digits werden einzeln erzeugt ....
			$captcha_code="";
			for ($digits=0;$digits<$FRM[0]['digits_captcha'];$digits++) {
				if ($digits>0) {
					$captcha_code .= rand(0,9);
				} else {
					$captcha_code .= rand(1,9);//wenn digits=0 == erste stelle, dann keine fuehrende 0!!! bei 1 beginnen.
				}
			}
			//der md5 wird im formular uebergeben und dann mit dem md5 der eingabe verglichen
			$captcha_md5=md5($captcha_code);
			//erzeugt neuen css captcha
			$captcha_text = new Number( $captcha_code );
			//rendert den css captcha
			$FCAPTCHAIMG=$captcha_text->printNumber();
			//$FCAPTCHAIMG ist jetzt der html code fuer den css captcha...

			//FGROUPDESCR wird in subscribe_form definiert
			$FGROUPDESCR="";
			//formular einbinden
			require_once (TM_INCLUDEPATH_FE."/subscribe_form.inc.php");
			//template vars definieren
			$_Tpl_FRM->setParseValue("FMESSAGE", $MESSAGE);
			$_Tpl_FRM->setParseValue("FNAME", display($FRM[0]['name']));
			$_Tpl_FRM->setParseValue("FDESCR", display($FRM[0]['descr']));
			$_Tpl_FRM->setParseValue("FHEAD", $FHEAD);
			$_Tpl_FRM->setParseValue("FFOOT", $FFOOT);
			$_Tpl_FRM->setParseValue("FRESET", $FRESET);
			$_Tpl_FRM->setParseValue("FSUBMIT", $FSUBMIT);
			$_Tpl_FRM->setParseValue("FEMAIL", $FEMAIL);
			$_Tpl_FRM->setParseValue("FEMAILNAME", display($FRM[0]['email']));
			$_Tpl_FRM->setParseValue("FCAPTCHA", $FCAPTCHA);
			$_Tpl_FRM->setParseValue("FCAPTCHAIMG", $FCAPTCHAIMG);
			$_Tpl_FRM->setParseValue("F0", $F0);
			$_Tpl_FRM->setParseValue("F1", $F1);
			$_Tpl_FRM->setParseValue("F2", $F2);
			$_Tpl_FRM->setParseValue("F3", $F3);
			$_Tpl_FRM->setParseValue("F4", $F4);
			$_Tpl_FRM->setParseValue("F5", $F5);
			$_Tpl_FRM->setParseValue("F6", $F6);
			$_Tpl_FRM->setParseValue("F7", $F7);
			$_Tpl_FRM->setParseValue("F8", $F8);
			$_Tpl_FRM->setParseValue("F9", $F9);
			$_Tpl_FRM->setParseValue("F0NAME", display($FRM[0]['f0']));
			$_Tpl_FRM->setParseValue("F1NAME", display($FRM[0]['f1']));
			$_Tpl_FRM->setParseValue("F2NAME", display($FRM[0]['f2']));
			$_Tpl_FRM->setParseValue("F3NAME", display($FRM[0]['f3']));
			$_Tpl_FRM->setParseValue("F4NAME", display($FRM[0]['f4']));
			$_Tpl_FRM->setParseValue("F5NAME", display($FRM[0]['f5']));
			$_Tpl_FRM->setParseValue("F6NAME", display($FRM[0]['f6']));
			$_Tpl_FRM->setParseValue("F7NAME", display($FRM[0]['f7']));
			$_Tpl_FRM->setParseValue("F8NAME", display($FRM[0]['f8']));
			$_Tpl_FRM->setParseValue("F9NAME", display($FRM[0]['f9']));

			$_Tpl_FRM->setParseValue("FGROUP", $FGROUP);
			$_Tpl_FRM->setParseValue("FGROUPDESCR", $FGROUPDESCR);
			
			$_Tpl_FRM->setParseValue("MEMO", $FMEMO);
			
			$_Tpl_FRM->setParseValue("TM_APPNAME", TM_APPNAME);
			$_Tpl_FRM->setParseValue("TM_APPDESC", TM_APPDESC);
			$_Tpl_FRM->setParseValue("TM_APPURL", TM_APPURL);
			$_Tpl_FRM->setParseValue("TM_APPTEXT", TM_APPTEXT);
			$_Tpl_FRM->setParseValue("TM_VERSION", TM_VERSION);
			$_Tpl_FRM->setParseValue("TM_DISCLAIMER", TM_DISCLAIMER);
			
			//Formular Template parsen und ausgeben, in $OUTPUT speichern...
			$OUTPUT.=$_Tpl_FRM->renderTemplate($Form_Filename);


?>