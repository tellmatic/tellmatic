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

//copy selected option from selectbox to textarea/textfield etc. 
function copyselectedoption(ID,optionslist,pretag,posttag) {
//example:
//add: onChange="javascript:copyselectedoption('idOfTextArea_etc',this,'---pre---','---post---');" to a selectbox (this!).
//e.g. used in host_form.inc.php for imap/pop3 options			
   	var SelectedOption = optionslist.selectedIndex;
   	if (SelectedOption != 0) {
       	document.getElementById(ID).value += pretag + optionslist.options[SelectedOption].value + posttag;
       	optionslist.selectedIndex = 0;
   	}	
}

//change css visibility of an element
function makeVis(divID) {
	document.getElementById(divID).style.visibility = "visible";
}

function makeVisBlock(divID) {
	document.getElementById(divID).style.visibility = "visible";
	document.getElementById(divID).style.display = "block";
}
function makeInVisBlock(divID) {
	document.getElementById(divID).style.visibility = "invisible";
	document.getElementById(divID).style.display = "none";
}

//check form settings and enable disable several options
function checkFormSettings(form_id,items_match,items_nomatch,check_id,check_value) {
	//voa 11/008
	//check for value and enable/disable items with item_ids
	//function checkFormSettings(form_id,item_ids,check_id,check_value)
	//form_id: id of form to check
	//items: array of items and bool to disable item[N][0]=item_id | [1]=disabled:true|false]
	//check_id: id of item to check for check_value
	//check_value: value of item with check_id 

	form=document.getElementById(form_id);
	field=document.getElementById(check_id);
	if (field.value==check_value)  {
		for (var f = 0; f < items_match.length; ++f) {
			var changefield=document.getElementById(items_match[f][0]);
		    changefield.disabled = items_match[f][1];
		}
	} else {
		for (var f = 0; f < items_nomatch.length; ++f) {
			var changefield=document.getElementById(items_nomatch[f][0]);
		    changefield.disabled = items_nomatch[f][1];
		}
	}
}

function checkHostType() {
	form_id='edit_host';
	check_id='type';
	check_value='smtp';
	items_match=[	['smtp_auth',false], 
								['smtp_domain',false], 
								['smtp_auth',false], 
								['smtp_ssl',false], 
								['sender_name',false], 
								['sender_email',false], 
								['return_mail',false],
								['reply_to',false],
								['max_mails_atonce',false],
								['max_mails_bcc',false],
								['smtp_max_piped_rcpt',false],
								['delay',false],
								['options',true],
							];
	items_nomatch=[	['smtp_auth',true], 
								['smtp_domain',true], 
								['smtp_auth',true], 
								['smtp_ssl',true], 
								['sender_name',true], 
								['sender_email',true], 
								['return_mail',true], 
								['reply_to',true],
								['max_mails_atonce',true],
								['max_mails_bcc',true],
								['smtp_max_piped_rcpt',true],
								['delay',true],
								['options',false],
							];
	checkFormSettings(form_id,items_match,items_nomatch,check_id,check_value);
}


function checkAdrListForm() {
	form=document.getElementById('adr_list');
	field=document.getElementById('set');

	if (field.value == 'set_status_multi')  {
		var changefield=document.getElementById('status_multi');
	    changefield.disabled = false;
		var changefield=document.getElementById('adr_grp_id_multi');
	    changefield.disabled = true;
	} else {
		var changefield=document.getElementById('status_multi');
	    changefield.disabled = true;
	}
	
	if (field.value == 'move_grp_multi' || field.value == 'copy_grp_multi' || field.value == 'delete_grp_multi')  {
		var changefield=document.getElementById('adr_grp_id_multi');
	    changefield.disabled = false;
		var changefield=document.getElementById('status_multi');
	    changefield.disabled = true;
	} else {
		var changefield=document.getElementById('adr_grp_id_multi');
	    changefield.disabled = true;
	}
}

function checkADRCleanForm() {
	form=document.getElementById('adr_clean');
	field=document.getElementById('set');
	field2=document.getElementById('remove_duplicates');
	
	var changefield=document.getElementById('adr_grp_id_multi');
    changefield.disabled = true;
	var changefield=document.getElementById('status_multi');
    changefield.disabled = true;

	var changefield=document.getElementById('remove_duplicates_method');
    changefield.disabled = true;
	var changefield=document.getElementById('remove_duplicates_limit');
    changefield.disabled = true;
	var changefield=document.getElementById('remove_duplicates_details');
    changefield.disabled = true;
	var changefield=document.getElementById('remove_duplicates_export');
    changefield.disabled = true;

	//disable all if checking duplicates 
	if (field2.checked) {
		//enable method and limit
		var changefield=document.getElementById('remove_duplicates_method');
    	changefield.disabled = false;
		var changefield=document.getElementById('remove_duplicates_limit');
    	changefield.disabled = false;
		var changefield=document.getElementById('remove_duplicates_details');
    	changefield.disabled = false;
		var changefield=document.getElementById('remove_duplicates_export');
    	changefield.disabled = false;
		//disable all the rest
		var changefield=document.getElementById('set');
    	changefield.disabled = true;
		var changefield=document.getElementById('blacklist');
    	changefield.disabled = true;
		var changefield=document.getElementById('email');
    	changefield.disabled = true;
		var changefield=document.getElementById('status');
    	changefield.disabled = true;
		var changefield=document.getElementById('status_multi');
    	changefield.disabled = true;
		var changefield=document.getElementById('adr_grp_id');
    	changefield.disabled = true;
		var changefield=document.getElementById('adr_grp_id_multi');
    	changefield.disabled = true;
	} else {
	
		var changefield=document.getElementById('set');
    	changefield.disabled = false;
		var changefield=document.getElementById('blacklist');
    	changefield.disabled = false;
		var changefield=document.getElementById('email');
    	changefield.disabled = false;
		var changefield=document.getElementById('status');
    	changefield.disabled = false;
		var changefield=document.getElementById('status_multi');
    	changefield.disabled = false;
		var changefield=document.getElementById('adr_grp_id');
    	changefield.disabled = false;
		var changefield=document.getElementById('adr_grp_id_multi');
    	changefield.disabled = false;	
	
		if (field.value == 'set_status')  {
			var changefield=document.getElementById('adr_grp_id_multi');
		    changefield.disabled = true;
			var changefield=document.getElementById('status_multi');
		    changefield.disabled = false;
		}
		if (field.value == 'move_grp' || field.value == 'copy_grp' || field.value == 'delete_grp')  {
			var changefield=document.getElementById('adr_grp_id_multi');
		    changefield.disabled = false;
			var changefield=document.getElementById('status_multi');
		    changefield.disabled = true;
		}
	}
}
function checkImport() {
	form=document.getElementById('adr_import');
	field=document.getElementById('delete');
	field2=document.getElementById('blacklist');
	if (field.checked || field2.checked)  {
	//disable
		var changefield=document.getElementById('merge_groups');
	    changefield.disabled = true;
		var changefield=document.getElementById('adr_grp');
	    changefield.disabled = true;
		var changefield=document.getElementById('status_new');
	    changefield.disabled = true;
		var changefield=document.getElementById('status_exists');
	    changefield.disabled = true;
		var changefield=document.getElementById('aktiv_new');
	    changefield.disabled = true;
		var changefield=document.getElementById('aktiv_existing');
	    changefield.disabled = true;
		var changefield=document.getElementById('check_double');
	    changefield.disabled = true;
		var changefield=document.getElementById('mark_recheck');
	    changefield.disabled = true;
	} else {
	//enable
		var changefield=document.getElementById('merge_groups');
	    changefield.disabled = false;
		var changefield=document.getElementById('adr_grp');
	    changefield.disabled = false;
		var changefield=document.getElementById('status_new');
	    changefield.disabled = false;
		var changefield=document.getElementById('status_exists');
	    changefield.disabled = false;
		var changefield=document.getElementById('aktiv_new');
	    changefield.disabled = false;
		var changefield=document.getElementById('aktiv_existing');
	    changefield.disabled = false;
		var changefield=document.getElementById('check_double');
	    changefield.disabled = false;
		var changefield=document.getElementById('mark_recheck');
	    changefield.disabled = false;
	}
}

// bei import und delete diverse felder deaktivieren:
function checkImport_X() {
	//leider geht dat nich da value=1 nicht funzt, field.checked waere das richtige......
	form_id='adr_import';
	items_match=[	['merge_groups',true], 
								['adr_grp',true], 
								['status_new',true], 
								['status_exists',true], 
								['aktiv_new',true], 
								['aktiv_existing',true], 
								['check_double',true]
							];
	items_nomatch=[	['merge_groups',false], 
								['adr_grp',false], 
								['status_new',false], 
								['status_exists',false], 
								['aktiv_new',false], 
								['aktiv_existing',false], 
								['check_double',false]
							];

	check_id='delete';
	check_value='1';
	checkFormSettings(form_id,items_match,items_nomatch,check_id,check_value);

	check_id='blacklist';
	check_value='1';
	checkFormSettings(form_id,items_match,items_nomatch,check_id,check_value);
}

//flash element
function flash(id, class_new, f) {
	var element = document.getElementById(id);

	/*
	var class_cur = element.className;
	if (f==1) {
		element.className = 'mFormSubmitFocus';
		setTimeout("flash('" + id + "', '" + class_cur + "',0)", 1500);

	} else {
		element.className = 'mFormSubmit';
		setTimeout("flash('" + id + "', '" + class_cur + "',1)", 2500);
	}
	*/

	element.className = 'mFormSubmitFocus';
}
      


//flash element
function flash_old(id, color) {
	var element = document.getElementById(id);
	var color_cur = element.style.backgroundColor;
	element.style.backgroundColor = color;
	setTimeout("flash('" + id + "', '" + color_cur + "')", 3500);
}
      
//checkbox auswahl umkehren fuer alle checkboxen im formular !!!
//auwahl umkehren alle selectboxen in formular mit der id....
function checkAllForm(id) {
  for (var i = 0; i < document.getElementById(id).elements.length; i++) {
     if(document.getElementById(id).elements[i].type == 'checkbox'){
      document.getElementById(id).elements[i].checked = !(document.getElementById(id).elements[i].checked);
    }
  }
}

//auswahl umkehren bei checkboxen mit der id...., bei mehreren checkboxen mit gleichem namen.....  wir dnur der erste bearbeitet!!
function checkByID(id) {
  if(document.getElementById(id).type == 'checkbox'){
    document.getElementById(id).checked = !(document.getElementById(id).checked);
  }
}

//setzt den focus auf das erste test oder passwortfeld!
//aus squirrelmail
function checkForm() {
   var f = document.forms.length;
   var i = 0;
   var pos = -1;
   while( pos == -1 && i < f ) {
       var e = document.forms[i].elements.length;
       var j = 0;
       while( pos == -1 && j < e ) {
           if ( document.forms[i].elements[j].type == 'text'            || document.forms[i].elements[j].type == 'password' ) {
               pos = j;
           }
           j++;
       }
   i++;
   }
   if( pos >= 0 ) {
       document.forms[i-1].elements[pos].focus();
   }
}

//setzt style hintergrundfarbe ueber id
function setBGColor(my_id,color)
{
	var element=document.getElementById(my_id);
	element.style.background=color;
}

//schreibt den inhalt von arg2 in einen bestimmten bereich mit der id arg1 , zbsp.ein div, der alte inhalt wird aber geloescht!!!
//irgendwo im netz gefunden
function doc_writer(arg1,arg2)
  {
    var o = false;
    if (document.getElementById)
      { o = document.getElementById(arg1); }
    else if (document.all)
      { o = document.all[arg1]; }
    if (o)
      { o.innerHTML = arg2; }
    else if (document.layers)
      {
        with (document.layers[arg1].document)
          { 
            open();
            write(arg2);
            close();
          }
      }
  }     


function confirmLink(theLink, theMessage)
{
    confirmMsg='Sind Sie sich sicher?';
    if (confirmMsg == '' || typeof(window.opera) != 'undefined') {
        return true;
    }
    var is_confirmed = confirm(confirmMsg + ' \n' + theMessage);
    if (is_confirmed) {
        theLink.href += '&doit=1';
    }
    return is_confirmed;
} // end of the 'confirmLink()' function






var opacity=0 //opacity of image
var increase=1 //increase opacity indicator
var decrease=0 //decrease opacity indicator

	
function switchSection(input)
{
	var section=document.getElementById(input);
	if(section.style.display=="none")
	{
		section.style.display="block"
	}
	else
	{
		section.style.display="none"
	}
}


function fadein(objid){
	if (opacity<1)
		opacity+=0.05
	else{
		increase=0
		decrease=1
	}
	document.getElementById(objid).style.MozOpacity=opacity
}

function iflip(iname,img) {
 for(imageno=0;document.images[imageno];imageno++) {
	 if (document.images[imageno].name==iname) {
	 	window.document.images[imageno].src = img;
	 }
 }
}

/* setVisibility(objId, sVisibility)
* Parameters: 
* objId - the id of an element (case sensitive)
* sVisibility - "visible" | "inherit" | "none" (case insensitive)
*
* USAGE: 
* Show div1:
* onclick='setVisibility("div1","visible")' 
*
* Inherit the visibility of div2's parentNode:
* onblur='setVisibility("div2", "inherit")' 
*
* Hide span3:
* onblur='setVisibility("span3", "hidden")'
*/         

   function setVisibility(objId, sVisibility) {
        var obj = document.getElementById(objId);
         obj.style.visibility = sVisibility;
   }





var WMTT=null;
document.onmousemove=updateToolTip;

function updateToolTip(e) 
{
	var x=(document.all)?window.event.x+document.body.scrollLeft:e.pageX;
	var y=(document.all)?window.event.y+document.body.scrollTop:e.pageY;
	
	if (WMTT!=null) 
	{		
		var versetzungY=-150;
		var versetzungX=-180;
		
		var WMTTID = WMTT.id;
		var ersterTeilDerID = WMTTID.substr(0,6);
		

			WMTT.style.left=(x+versetzungX)+"px";
			WMTT.style.top=(y+versetzungY+8)+"px";
	}
}
function showToolTip(id,inputposition) 
{
	updateToolTip;
	var position=inputposition;
	WMTT=document.getElementById(id);
	WMTT.style.display="block"
}

function hideToolTip() 
{
	WMTT.style.display="none";
}





var ini = new Date().getTime();
var pcld = 10;
var pc = 4;
var pct = 2;
var pctt = 1;


function load() {
pcld +=9;
pc += 4;
pct += 2;
pctt += 1;

//if (pc < 10) { 
//	document.getElementById('ld').style.width = pcld + "%";
	document.getElementById('ld').style.width = "100%";
//} else {
//	pcld=10;
//}
if (pc < 100) { 
	document.getElementById('lpc').style.width = pc + "%";
} else {
	pc=4;
}
if (pct < 100) { 
	document.getElementById('lt').style.width =  pct + "%";
} else {
	pct=2;
}
if (pctt < 100) { 
	document.getElementById('ltt').style.width =  pctt + "%";
} else {
	pctt=1;
}
var time = setTimeout("load()",25);
//if (pc > 100) { clearTimeout(time); loaded() }

}

function loaded() {
fim = new Date().getTime();
dif = fim - ini;
document.getElementById('ld').style.display = 'none';

}


/**
 * Sets/unsets the pointer and marker in browse mode
 *
 * @param   object   the table row
 * @param   string   the action calling this script (over, out or click)
 * @param   string   the default background color
 * @param   string   the color to use for mouseover
 * @param   string   the color to use for marking a row
 *
 * @return  boolean  whether pointer is set or not
 */
function setPointer(theRow, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    } // end 3

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == null || currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()) {
        if (theAction == 'out') {
            newColor = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor = (thePointerColor != '')
                     ? thePointerColor
                     : theDefaultColor;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } // end 5

    return true;
} // end of the 'setPointer()' function



/**
The JavaScript functions below will gradually enlarge or shrink an image
on the current page. I use this for mouseover effects, but there might be
some other interesting applications of it as well.

You can use these scripts in any way you'd like, just don't pretend like
you wrote them yourself.

version 1.0
March 17, 2005
Julian Robichaux, http://www.nsftools.com
*/

/**** adjust these two parameters to control how fast or slow
 **** the images grow/shrink ****/
// how many milliseconds we should wait between resizing events
var resizeDelay = 50;
// how many pixels we should grow or shrink by each time we resize
var resizeIncrement = 3;

// this will hold information about the images we're dealing with
var imgCache = new Object();


/**
The getCacheTag function just creates a (hopefully) unique identifier for
each <img> that we resize.
*/
function getCacheTag (imgElement) {
	return imgElement.src + "~" + imgElement.offsetLeft + "~" + imgElement.offsetTop;
}


/**
We're using this as a class to hold information about the <img> elements
that we're manipulating.
*/
function cachedImg (imgElement, increment) {
	this.img = imgElement;
	this.cacheTag = getCacheTag(imgElement);
	this.originalSrc = imgElement.src;
	
	var h = imgElement.height;
	var w = imgElement.width;
	this.originalHeight = h;
	this.originalWidth = w;
	
	increment = (!increment) ? resizeIncrement : increment;
	this.heightIncrement = Math.ceil(Math.min(1, (h / w)) * increment);
	this.widthIncrement = Math.ceil(Math.min(1, (w / h)) * increment);
}


/**
This is the function that should be called in the onMouseOver and onMouseOut
events of an <img> element. For example:

<img src='onesmaller.gif' onMouseOver='resizeImg(this, 150, "onebigger.gif")' onMouseOut='resizeImg(this)'>

The only required parameter is the first one (imgElement), which is a
reference to the <img> element itself. If you're calling from onMousexxx, 
you can just use "this" as the value.

The second parameter specifies how much larger or smaller we should resize
the image to, as a percentage of the original size. In the example above,
we want to resize it to be 150% larger. If this parameter is omitted, we'll
assume you want to resize the image to its original size (100%).

The third parameter can specify another image that should be used as the
image is being resized (it's common for "rollover images" to be similar but
slightly different or more colorful than the base images). If this parameter
is omitted, we'll just resize the existing image.
*/
function resizeImg (imgElement, percentChange, newImageURL) {
	// convert the percentage (like 150) to an percentage value we can use
	// for calculations (like 1.5)
	var pct = (percentChange) ? percentChange / 100 : 1;
	
	// if we've already resized this image, it will have a "cacheTag" attribute
	// that should uniquely identify it. If the attribute is missing, create a
	// cacheTag and add the attribute
	var cacheTag = imgElement.getAttribute("cacheTag");
	if (!cacheTag) {
		cacheTag = getCacheTag(imgElement);
		imgElement.setAttribute("cacheTag", cacheTag);
	}
	
	// look for this image in our image cache. If it's not there, create it.
	// If it is there, update the percentage value.
	var cacheVal = imgCache[cacheTag];
	if (!cacheVal) {
		imgCache[cacheTag] = new Array(new cachedImg(imgElement), pct);
	} else {
		cacheVal[1] = pct;
	}
	
	// if we're supposed to be using a rollover image, use it
	if (newImageURL)
		imgElement.src = newImageURL;
	
	// start the resizing loop. It will continue to call itself over and over
	// until the image has been resized to the proper value.
	resizeImgLoop(cacheTag);
	return true;
}


/**
This is the function that actually does all the resizing. It calls itself
repeatedly with setTimeout until the image is the right size.
*/
function resizeImgLoop (cacheTag) {
	// get information about the image element from the image cache
	var cacheVal = imgCache[cacheTag];
	if (!cacheVal)
		return false;
	
	var cachedImageObj = cacheVal[0];
	var imgElement = cachedImageObj.img;
	var pct = cacheVal[1];
	var plusMinus = (pct > 1) ? 1 : -1;
	var hinc = plusMinus * cachedImageObj.heightIncrement;
	var vinc = plusMinus * cachedImageObj.widthIncrement;
	var startHeight = cachedImageObj.originalHeight;
	var startWidth = cachedImageObj.originalWidth;
	
	var currentHeight = imgElement.height;
	var currentWidth = imgElement.width;
	var endHeight = Math.round(startHeight * pct);
	var endWidth = Math.round(startWidth * pct);
	
	// if the image is already the right size, we can exit
	if ( (currentHeight == endHeight) || (currentWidth == endWidth) )
		return true;
	
	// increase or decrease the height and width, making sure we don't get
	// larger or smaller than the final size we're supposed to be
	var newHeight = currentHeight + hinc;
	var newWidth = currentWidth + vinc;
	if (pct > 1) {
		if ((newHeight >= endHeight) || (newWidth >= endWidth)) {
			newHeight = endHeight;
			newWidth = endWidth;
		}
	} else {
		if ((newHeight <= endHeight) || (newWidth <= endWidth)) {
			newHeight = endHeight;
			newWidth = endWidth;
		}
	}
	
	// set the image element to the new height and width
	imgElement.height = newHeight;
	imgElement.width = newWidth;
	
	// if we've returned to the original image size, we can restore the
	// original image as well (because we may have been using a rollover
	// image in the original call to resizeImg)
	if ((newHeight == cachedImageObj.originalHeight) || (newWidth == cachedImageObj.originalwidth)) {
		imgElement.src = cachedImageObj.originalSrc;
	}
	
	// shrink or grow again in a few milliseconds
	setTimeout("resizeImgLoop('" + cacheTag + "')", resizeDelay);
}