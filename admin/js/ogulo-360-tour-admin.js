jQuery(function($) {
 
    window.Ogulo360 = {};
 
    Ogulo360.init = function() {
        Ogulo360.cacheSelectors();
        Ogulo360.onLoadEventHandler();
        Ogulo360.eventHandler();

    }
 
    Ogulo360.cacheSelectors = function() {
        // Initialize variables and use in whole script
        Ogulo360.body = $('#ogulo_settings');
        Ogulo360.active = 'active';
        Ogulo360.hidden = 'hidden';
        Ogulo360.licence_field = $('.licence_field').find('input');
        Ogulo360.licence_notice_img = $('.licence_field').find('img');
        Ogulo360.licence_field = $('.licence_field').find('input');
        Ogulo360.notice = Ogulo360.body.find('.notice');
        Ogulo360.tours_nav = Ogulo360.body.find('#my_tours');
        Ogulo360.tours_panel = Ogulo360.body.find('.my_tours');
        Ogulo360.activate_btn = Ogulo360.body.find('.activate_ogulo');
        Ogulo360.wait_btn = Ogulo360.body.find('.activate_ogulo').next();
        Ogulo360.oogulo_verified = Ogulo360.body.find('.oogulo_verified');
 
    }
	
	
 	
 	Ogulo360.onLoadEventHandler = function() {
 		
 		loadDefaultTrigger();
 	}
    
    Ogulo360.eventHandler = function() {
 		Ogulo360.body.on( 'click', '.clear', hideNotice );
        Ogulo360.body.on( 'click', '.activate_ogulo', validateActivation );
		
    }

    var loadDefaultTrigger = function( e ){
    	 
    	
    	var key = Ogulo360.licence_field.val();
    	var default_tab = 1;
    	if(key.length > 0){
    		Ogulo360.tours_nav.removeClass('hidden');
 			Ogulo360.tours_panel.removeClass('hidden');
 			default_tab = 0;
 			getAllTours();

    	}
    	
    	Ogulo360.body.tabs({ active : default_tab });
    };

    var getAllTours = function( e ){
    	var params = {
    		'action' : 'get_all_tours',
    		'nonce'	 : oguloJSObject.nonce

    	};
    	$.post(oguloJSObject.ajaxurl, params, function(res){
    		Ogulo360.tours_panel.html(res);
    		Ogulo360.tours_panel.find('table').DataTable({
    			///"order": [[ 2, "asc" ]]
    		});
    	});
    };

    var hideNotice = function( e ) {
    	Ogulo360.notice.fadeOut('slow');
    };
    
    var validateActivation = function( e ) {
	    e.preventDefault();

	    var key = Ogulo360.licence_field.val();
	    if(key.length <= 0){
	    	Ogulo360.licence_field.focus().css('border','1px solid #07ADAD');
	    	Ogulo360.licence_notice_img.removeClass('hidden');
 			Ogulo360.notice.removeClass('hidden');
	    	 return false;
	    }

	   	oguloActivation();
		};
    
 	var oguloActivation = function(){
 		Ogulo360.licence_field.css('border','');
 		Ogulo360.activate_btn.addClass('hidden');
 		Ogulo360.wait_btn.removeClass('hidden');


 		var key = Ogulo360.licence_field.val();
 		var params = {
 			'action' : 'ogulo_activation',
 			'key'	 : key,
 			'nonce'	 : oguloJSObject.nonce
 		};
 		$.post(oguloJSObject.ajaxurl, params, function(res){
 			var obj = JSON.parse(res);
 				Ogulo360.activate_btn.removeClass('hidden');
 				Ogulo360.wait_btn.addClass('hidden');
 			if(obj.success){
 				Ogulo360.licence_notice_img.addClass('hidden');
 				Ogulo360.oogulo_verified.removeClass('hidden');
 				Ogulo360.notice.addClass('hidden');
 				getAllTours();
 				setTimeout(function(){ 
 					Ogulo360.tours_nav.removeClass('hidden');
 					Ogulo360.tours_panel.removeClass('hidden');
 					Ogulo360.tours_nav.find('a').click();
 					location.reload();
 				 }, 1000);
				 
 			}else{
 				Ogulo360.licence_notice_img.removeClass('hidden');
 				Ogulo360.oogulo_verified.addClass('hidden');
 				Ogulo360.notice.removeClass('hidden');
 			}
 		});
		

 	}

    Ogulo360.init();
 
});