/*********************************************************************** 
* File    : jsfx_ImageFader.js  © JavaScript-FX.com
* Created : 2001/08/31 
* Author  : Roy Whittle  (Roy@Whittle.com) www.Roy.Whittle.com 
* Purpose : To create a fading effect for images
* History 
* Date         Version  Description 
* 2001-08-09	1.0	First version
* 2001-08-31	1.1	Code split - others became 
*					jsfx_FadingRollovers,
*                             jsfx_ImageFader,
*					jsfx_ImageZoom.
* 2002-01-27	1.2	Completed development by converting to jsfx namespace
* 2002-02-21	1.3	Added jsfx.fadeUpImg jsfx.fadeDownImg
* 2002-01-29	1.4	Make "fade" a seperate object of "img"
* 2002-03-12	1.5	Added an auto fade up/down for images 
*					with class imageFader
* 2002-08-29	1.6	Thanks to piglet (http://homepage.ntlworld.com/thepiglet/)
*				I now have a partial fix for NS7 and Mozilla 1.1.
***********************************************************************/ 
if(!window.jsfx)
	jsfx=new Object();

jsfx.FadeImageRunning    = false;
jsfx.FadeImageMinOpacity = 40;
jsfx.FadeImageAutoUp	 = 10;
jsfx.FadeImageAutoDown   = 10;
jsfx.FadeImageSavedOver  = null;
jsfx.FadeImageSavedOut   = null;
document.write('<STYLE TYPE="text/css">.imageFader{ position:relative; filter:alpha(opacity='+jsfx.FadeImageMinOpacity+'); -moz-opacity:'+jsfx.FadeImageMinOpacity/101+'}</STYLE>');
/*******************************************************************
*
* Function    : actionOnMouseOver
*
* Description : Called automatically whenever an element in the
*			document is "mousedOver". It checks to see if the
*			element has the className == "imageFader" and if so
*			starts fading up the element.
*
*****************************************************************/
jsfx.fadeImage_actionOnMouseOver = function(e)
{
	srcElement=e ? e.target : event.srcElement;
	
	if(srcElement.className && srcElement.className=="imageFader")
		jsfx.fadeUp(srcElement);

	/*** If the document already had an onMouseOver handler, call it ***/
	if(jsfx.FadeImageSavedOver != null)
		jsfx.FadeImageSavedOver(e);
}

/*******************************************************************
*
* Function    : actionOnMouseOut
*
* Description : Called automatically whenever an element in the
*			document is "mousedOut". It checks to see if the
*			element has the className == "imageFader" and if so
*			starts fading down the element.
*
*****************************************************************/
jsfx.fadeImage_actionOnMouseOut = function(e)
{
	srcElement=e ? e.target : event.srcElement;

	if(srcElement.className && srcElement.className=="imageFader")
		jsfx.fadeDown(srcElement);
	
	/*** If the document already had an onMouseOut handler, call it ***/
	if(jsfx.FadeImageSavedOut != null)
		jsfx.FadeImageSavedOut(e);
}
/*******************************************************************
*
* Function    :	fadeImageAuto
*
* Parameters  :	minOpacity	- Minimum opacity to fade down to.
*			stepUp	- fade up speed 	- larger = faster.
*			stepDown 	- fade down speed	- larger = faster.
*
* Description :	Saves the documents original mousOver/Out handlers
*			and installs the actionMouseOver/Out handlers
*			of jsfx.fadeImage
*
*****************************************************************/
jsfx.fadeImageAuto = function(minOpacity, stepUp, stepDown)
{
	if(minOpacity)
		jsfx.FadeImageMinOpacity = minOpacity;
	if(stepUp)
		jsfx.FadeImageAutoUp	= stepUp;
	if(stepDown)
		jsfx.FadeImageAutoDown	= stepDown;

	/*** Save the original document mouseOver/Out events ***/
	jsfx.FadeImageSavedOver = document.onmouseover;
	jsfx.FadeImageSavedOut  = document.onmouseout;

	document.onmouseover	= jsfx.fadeImage_actionOnMouseOver ;
	document.onmouseout	= jsfx.fadeImage_actionOnMouseOut ;
	jsfx.setMinOpacity(jsfx.FadeImageMinOpacity);
}
/*******************************************************************
*
* Function    : setMinOpacity
*
* Description : sets the minimum opacity for all fading images from
*			the default of 40 to the passed value.
*
*****************************************************************/
jsfx.setMinOpacity = function(minOpacity)
{
	if(document.layers) return;

	for(i=0 ; i<document.images.length ; i++)
	{
		var img = document.images[i];
		if(img.className=="imageFader")
		{
			if(img.filters)
				img.filters.alpha.opacity = jsfx.FadeImageMinOpacity;
			else
				img.style.MozOpacity = jsfx.FadeImageMinOpacity/101;
		}
	}
}
/*******************************************************************
*
* Function    : fadeUpImg
*
* Description : Finds the image in the document and calls jsfx.fadeUp
*
*****************************************************************/
jsfx.fadeUpImg = function(imgName, step)
{
	if(document.layers || window.opera)
		return;

	img = document.images[imgName];
	if(img)
		jsfx.fadeUp(img, step);
}
/*******************************************************************
*
* Function    : fadeUp
*
* Description : This function is based on the turn_on() function
*		      of animate2.js (animated rollovers from www.roy.whittle.com).
*		      Each fading image object is given a state. 
*			OnMouseOver the state is switched depending on the current state.
*			Current state -> Switch to
*			===========================
*			null		->	OFF.
*			OFF		->	FADE_UP
*			FADE_DOWN	->	FADE_UP
*			FADE_UP_DOWN->	FADE_UP
*****************************************************************/
jsfx.fadeUp = function(img, step)
{

	if(img)
	{
		if(!step) step=jsfx.FadeImageAutoUp;

		if(img.fade == null)
		{
			img.fade = new Object();
			img.fade.state	 = "OFF";
			img.fade.upStep	 = step;
			img.fade.downStep  = step;
			img.fade.minOpacity  = jsfx.FadeImageMinOpacity;
			img.fade.index = img.fade.minOpacity;
			
		}
		if(img.fade.state == "OFF")
		{
			img.fade.upStep  = step;
			img.fade.state = "FADE_UP";
			jsfx.startImageFading();
		}
		else if( img.fade.state == "FADE_UP_DOWN"
			|| img.fade.state == "FADE_DOWN")
		{
			img.fade.upStep  = step;
			img.fade.state = "FADE_UP";
		}
	}
}
/*******************************************************************
*
* Function    : fadeDownImg
*
* Description : Finds the image in the document and calls jsfx.fadeDown
*
*****************************************************************/
jsfx.fadeDownImg = function(imgName, step)
{
	if(document.layers || window.opera)
		return;

	img = document.images[imgName];
	if(img)
		jsfx.fadeDown(img, step);
}
/*******************************************************************
*
* Function    : fadeDown
*
* Description : This function is based on the turn_off function
*		      of animate2.js (animated rollovers from www.roy.whittle.com).
*		      Each zoom object is given a state. 
*			OnMouseOut the state is switched depending on the current state.
*			Current state -> Switch to
*			===========================
*			ON		->	FADE_DOWN.
*			FADE_UP	->	FADE_UP_DOWN.
*****************************************************************/
jsfx.fadeDown = function(img, step)
{
	if(img)
	{
		if(!step) step=jsfx.FadeImageAutoDown;

		if(img.fade.state=="ON")
		{
			img.fade.downStep  = step;
			img.fade.state="FADE_DOWN";
			jsfx.startImageFading();
		}
		else if(img.fade.state == "FADE_UP")
		{
			img.fade.downStep  = step;
			img.fade.state="FADE_UP_DOWN";
		}
	}
}
/*******************************************************************
*
* Function    : startImageFading
*
* Description : This function is based on the start_animating() function
*	        	of animate2.js (animated rollovers from www.roy.whittle.com).
*			If the timer is not currently running, it is started.
*			Only 1 timer is used for all objects
*****************************************************************/
jsfx.startImageFading = function()
{
	if(!jsfx.FadeImageRunning)
		jsfx.FadeImageAnimation();
}

/*******************************************************************
*
* Function    : FadeImageAnimation
*
* Description : This function is based on the Animate function
*		    of animate2.js (animated rollovers from www.roy.whittle.com).
*		    Each object has a state. This function
*		    modifies each object and (possibly) changes its state.
*****************************************************************/
jsfx.FadeImageAnimation = function()
{
	jsfx.FadeImageRunning = false;
	for(i=0 ; i<document.images.length ; i++)
	{
		var img = document.images[i];
		if(img.fade)
		{
			if(img.fade.state == "FADE_UP")
			{
				img.fade.index+=img.fade.upStep;
				if(img.fade.index > 100)
					img.fade.index = 100;

				if(img.filters)
					img.filters.alpha.opacity = img.fade.index;
				else
					img.style.MozOpacity = img.fade.index/101;

				if(img.fade.index == 100)
					img.fade.state="ON";
				else
					jsfx.FadeImageRunning = true;
			}
			else if(img.fade.state == "FADE_UP_DOWN")
			{
				img.fade.index+=img.fade.upStep;
				if(img.fade.index > 100)
					img.fade.index = 100;

				if(img.filters)
					img.filters.alpha.opacity = img.fade.index;
				else
					img.style.MozOpacity = img.fade.index/101;
	
				if(img.fade.index == 100)
					img.fade.state="FADE_DOWN";
				jsfx.FadeImageRunning = true;
			}
			else if(img.fade.state == "FADE_DOWN")
			{
				img.fade.index-=img.fade.downStep;
				if(img.fade.index < img.fade.minOpacity)
					img.fade.index = img.fade.minOpacity;

				if(img.filters)
					img.filters.alpha.opacity = img.fade.index;
				else
					img.style.MozOpacity = img.fade.index/101;

				if(img.fade.index == img.fade.minOpacity)
					img.fade.state="OFF";
				else
					jsfx.FadeImageRunning = true;
			}
		}
	}
	/*** Check to see if we need to animate any more frames. ***/
	if(jsfx.FadeImageRunning)
		setTimeout("jsfx.FadeImageAnimation()", 40);
}
