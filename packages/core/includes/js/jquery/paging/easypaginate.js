/*
 * 	Easy Paginate 1.0 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/
 *
 *	Copyright (c) 2011 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */

(function($) {
		  
	$.fn.easyPaginate = function(options){

		var defaults = {				
			step: 25,
			delay: 1,
			numeric: true,
			nextprev: true,
			auto:false,
			pause:4000,
			clickstop:true,
			controls: 'pagination',
			current: 'current'
		}; 
		
		var options = $.extend(defaults, options); 
		var step = options.step;
		var lower, upper;
		var children = jQuery('#bound_product_list').children();
		var count = children.length;
		var obj, next, prev;		
		var page = 1;
		var timeout;
		var clicked = false;
		jQuery('#bound_product_list').each(function(){
			obj = this;
			if(count>step){
				var pages = Math.floor(count/step);
				if((count/step) > pages) pages++;
				var ol = $('<ol id="'+ options.controls +'"></ol>').insertAfter(obj);
				if(options.nextprev){
					next = $('<li class="next"><img src="packages/hotel/skins/default/images/iosstyle/next_blue.png" class="icon" > </li>')
						.hide()
						.appendTo(ol)
						.click(function(){
							clicked = true;			
							page++;
							show();
						});
				};				
				if(options.nextprev){
					prev = $('<li class="prev"><img src="packages/hotel/skins/default/images/iosstyle/pre_blue.png" class="icon"></li>')
						.hide()
						.appendTo(ol)
						.click(function(){
							clicked = true;
							page--;
							show();
						});
				};
				/*if(options.numeric){
					for(var i=1;i<=pages;i++){
					$('<li data-index="'+ i +'">'+ i +'</li>')
						.appendTo(ol)
						.click(function(){	
							clicked = true;
							page = $(this).attr('data-index');
							show();
						});					
					};				
				};*/
				
			
				show();
			};
		});	
		function show(){
			clearTimeout(timeout);
			lower = ((page-1) * step);
			upper = lower+step;
			$(children).each(function(i){
				var child = $(this);
				child.hide();
				if(i>=lower && i<upper){ setTimeout(function(){ child.show() }, ( i-( Math.floor(i/step) * step) )*options.delay ); }
				if(options.nextprev){
					if(upper >= count) { next.hide(); } else { next.show(); };
					if(lower >= 1) { prev.show(); } else { prev.hide(); };
				};
			});	
			$('li','#'+ options.controls).removeClass(options.current);
			$('li[data-index="'+page+'"]','#'+ options.controls).addClass(options.current);
			
			if(options.auto){
				if(options.clickstop && clicked){}else{ timeout = setTimeout(auto,options.pause); };
			};
		};
		
		function auto(){
			if(upper <= count){ page++; show(); }			
		};
	};	

})(jQuery);