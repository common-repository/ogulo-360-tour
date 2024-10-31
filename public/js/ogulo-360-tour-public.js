jQuery(function($) {
 
    window.Ogulo360 = {};
 
    Ogulo360.init = function() {
        Ogulo360.cacheSelectors();
        Ogulo360.onLoadEventHandler();
    }
 
    Ogulo360.cacheSelectors = function() {
        // Initialize variables and use in whole script
        Ogulo360.mainbody = $('body');
        Ogulo360.main = $('.ogulo_tour_handler');
        Ogulo360.body = $('.ogulo_tour_handler_inner');
        Ogulo360.active = 'active';
        Ogulo360.hidden = 'hidden';
       
    }
 	
 	Ogulo360.onLoadEventHandler = function() {
 		
 		loadDefaultTrigger();
 	}

    var loadDefaultTrigger = function( e ){
  
    	var is_page = Ogulo360.body.length;
    	
    	if(is_page > 0){
 			loadFrontTours();
    	}
    	
    };

    var loadFrontTours = function( e ){
    	var tour_slugs=[];
    	Ogulo360.body.each(function(index, value){
    		var eachTour = $(this);
    		var slug = eachTour.attr('id').replace('tour-id-','');
    		var width = eachTour.attr('data-tour-width');
    		var height = eachTour.attr('data-tour-height');
    		if(tour_slugs.indexOf(slug+'|'+width+'|'+height) === -1){
		        tour_slugs.push(slug+'|'+width+'|'+height);
		    }
    		
    	});

    	if(tour_slugs.length > 0){
    		for (var i = 0; i < tour_slugs.length; i++) {
    			
    			
		    	$.ajax({
					url: oguloJSObject.ajaxurl,
					type: 'post',
					data: {
						'action' : 'load_front_tours',
						'nonce'	 : oguloJSObject.nonce,
						'tour'	 : tour_slugs[i]
					},
					success: function( res ) {
						
			    		var obj = JSON.parse(res);
			    		if(obj.success){
			    			//console.log('.tour-id-'+obj.success.tour_id+'//'+obj.success.tour_id+'//'+Ogulo360.mainbody.find('.tour-id-'+obj.success.tour_id).length);
			    			var tour_container = Ogulo360.mainbody.find('.tour-id-'+obj.tour.tour_id);
			    			tour_container.parent().removeClass('before');
			    			tour_container.html(obj.success.embed);
			    		}
			    		else{
			    			Ogulo360.mainbody.find('.tour-id-'+obj.tour.tour_id).find('p').text(obj.error);
			    		}
					}
				});
		    	
    		}
    		
    	}
    	
    };

    

    Ogulo360.init();
 
});