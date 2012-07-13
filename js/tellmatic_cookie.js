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

//COOKIE FUNCTIONS
//original: http://www.quirksmode.org/js/cookies.html
//useful: http://www.yourhtmlsource.com/javascript/cookies.html
//example: http://www.javascripter.net/faq/settinga.htm

var Cookies = {
	init: function () {
		var allCookies = document.cookie.split('; ');
		for (var i=0;i<allCookies.length;i++) {
			var cookiePair = allCookies[i].split('=');
			this[cookiePair[0]] = cookiePair[1];
		}
	},
	create: function (name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
		this[name] = value;
	},
	erase: function (name) {
		this.create(name,'',-1);
		this[name] = undefined;
	}
};
Cookies.init();

function cookie_saveIt(name,value) {
	//var value = document.forms['cookieform'].cookie_value.value;
	if (!name)
		alert('No Name');
	else {
		if (!value)
			alert('No Value');
		else {
			Cookies.create(name,value,1);
			//cookie_check(name)
			alert('Cookie created');
		}
	}
}

function cookie_eraseIt(name) {
	Cookies.erase(name);
	alert('Cookie ' + name + ' erased');
}

function cookie_check(name) {
		var x = Cookies[name];
		if (x) alert('Cookie ' + name + '\nthat you set on a previous visit, is still active.\nIts value is ' + x);
}

function cookie_readIt(name) {
		var x = Cookies[name];
		if (x) alert('Cookie ' + name + '\nValue: ' + x);
}
