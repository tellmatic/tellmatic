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

//slider...
//http://snipplr.com/view/4037/mootools-slide-toggle/
//http://www.congoblue.com.au/examples/MooTools_MultipleSliders/multipleSliders.html
function toggleSlide(trigger,element,toggle){
	  //Select the element you wish to slide
	  var mySlide = new Fx.Slide(element, {
		    duration: 250,
			mode:'vertical'});//brackets in next line crashes IE!!! :P		
	  //Create a slide in/out
	  $(trigger).addEvent('click', function(e){
		  	//add the click function (i.e on an anchor)
		  	e = new Event(e);//create a new event here
		  	mySlide.toggle();//do this to the selected element
		  	e.stop();//stop the event
	  });
	  if (toggle==1) {
		  	//mySlide.toggle();
		  	//use hide
		  	mySlide.hide();
	  }
}//toggleSlide


