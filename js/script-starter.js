/*
Any site-specific scripts you might have.
*/
	
jQuery(function ($) {

	/************************* 
	Variables
	**************************/
	var browserwidth;
	var desktopwidth = 1024; // resolución mínima desktop
	var mobilewidth = 767; // resolución máxima móviles 

	/************************* 
	Functiones
	**************************/
	
	function onLoadAndResize(){ 
		getbrowserwidth();	
		
		if(browserwidth >= mobilewidth) {
			scrollimage();
		}
		
		if(browserwidth <= mobilewidth) {
			menumobile();
		}
	}
	
	// Obtiene anchura del browser (no se usa el método nativo de jquery porque da valor inexacto en algunos browsers)
	function getbrowserwidth(){
		browserwidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth || 0;
		//console.log(browserwidth);
		return browserwidth;
	}
	
	// goToByScroll slide
	function goToByScroll(dataslide) {
	    htmlbody.animate({
	    scrollTop: $('.slide[data-slide="' + dataslide + '"]').offset().top}, 2000, 'easeInOutQuint');
	}
	
	function scrollimage(){
		$(window).scroll(function(e){
		  parallax();
		});
		
		function parallax(){
		  var scrolled = $(window).scrollTop();
		  $('#slide-1 .image-article').css('top',-(scrolled*0.15)+'px');
		  $('#slide-2 .image-article').css('top',-(scrolled*0.14)+'px');
		  $('#slide-3 .image-article').css('top',-(scrolled*0.12)+'px');
		  $('#slide-4 .image-article').css('top',-(scrolled*0.1)+'px');
		  $('#slide-5 .image-article').css('top',-(scrolled*0.08)+'px');
		}
	}
	
	/*
	* menu mobile
	*/
	function menumobile() {
		$('.open')
	      .bind('click focus', function(){
	        $('#access').slideToggle();
	        $('.open').toggleClass('active');
	        return;
	        console.log('error');
	    }); 
	   
	   $('.close')
	      .bind('click focus', function(){
	        $('#access').slideToggle();
	        $('.open').toggleClass('active');
	        return;
	    }); 
    }
    
    /************************* 
	Ejecución
	**************************/

	$(window).load(function() {
		onLoadAndResize();

		// waypoint
		(function(){

		    var links = $('.navigation').find('li');
		    var circleButton = $('.circle_button');
		    slide = $('.slide');
		    button = $('.button');
		    mywindow = $(window);
		    htmlbody = $('html,body');
		    
		    var content = $("#slide1 .inner");
		    var header = $("#header");
		    var homeHeader = $(".home #header");
		 
			// waypoint header hover slide
		    slide
		      .waypoint(function (direction) {
			        dataslide = $(this).attr('data-slide');
			        if (direction === 'down') {
			            $('.navigation li[data-slide="' + dataslide + '"]').addClass('active').prev().removeClass('active');
			        }	        
			      }, { offset: '25%' })
			   .waypoint(function(direction) {
			  		 dataslide = $(this).attr('data-slide');
			        if (direction === 'up') {
				        $('.navigation li[data-slide="' + dataslide + '"]').addClass('active').next().removeClass('active');
			        }
			     }, { offset: '0'}); 
		   
		    // waypoint active slide1
		    mywindow.scroll(function () {
		        if (mywindow.scrollTop() == 0) {
		            $('.navigation li[data-slide="1"]').addClass('active');
		            $('.navigation li[data-slide="2"]').removeClass('active');
		            homeHeader.removeClass('mini');
		        } else if(mywindow.scrollTop() >= 146) {
			        homeHeader.addClass('mini');
			        
		        }
		        
		    });

			// links li slide
		    links.click(function (e) {
		        e.preventDefault();
		        dataslide = $(this).attr('data-slide');
		        goToByScroll(dataslide);
		    });
		    
		    // circle button li slide
		    circleButton.click(function (e) {
		        e.preventDefault();
		        dataslide = $(this).attr('data-slide');
		        goToByScroll(dataslide);
		    });
		   

		})();

	});  
    
    $(window).resize(function(){
		onLoadAndResize();
	});

});

