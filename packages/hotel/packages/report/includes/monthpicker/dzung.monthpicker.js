/*
 * jQuery Monthpicker 1.1.2
 *
 * @licensed MIT <see below>
 * @licensed GPL <see below>
 *
 * @author Dzung Tran Tien
 * Email : dzung.trantien@gmail.com
 *
 */ 

/**
 * MIT License
 * Copyright (c) 2012, Dzung Tran Tien
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights 
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is 
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * GPL LIcense
 * Copyright (c) 2012, Dzung Tran Tien
 * 
 * This program is free software: you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the 
 * Free Software Foundation, either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
 * for more details.
 * 
 * You should have received a copy of the GNU General Public License along 
 * with this program. If not, see <http://www.gnu.org/licenses/>.
 */
 
(function($){
	$.fn.extend({ 
		monthpicker: function(options) {
            var datetime = new Date();
            //['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
            //['01','02','03','04','05','06','07','08','09','10','11','12']
			var defaults = {
				begginYear : datetime.getFullYear(),
                months : ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                min : 1970,
                max : 2099,
                callBack : false
			};
            var $this;
			var options = $.extend(defaults, options);
            var data_list = {
                months : function(e){
                    var str = '';
                    for(i = 0 ; i < 12; i++){
                        var val = ((i+1) < 10) ? ('0'+(i+1)) : (i+1);
                        str += '<li val="'+val+'">'+ options.months[i] + '</li>';
                    }
                    return str;
                },
                years : function(e){
                    var str = '';
                    var i = 2;
                    var middleYear = parseInt(options.begginYear) + 1;
                    if(options.begginYear >= options.max){
                        middleYear =  parseInt(options.max);
                    }
                    if(options.begginYear <= options.min){
                        middleYear =  parseInt(options.min) + 2;
                    }
                    while(true){
                        if(i < 0){
                            break;
                        }
                        str += '<li val="'+(middleYear - i)+'">' + (middleYear - i) + '</li>';
                        i--;
                    }
                    return str;
                }
            };
            var yearsEvent = {
                select : function(e){
                    $('#dzung-select-year li').removeClass('selected_item');
                    $(this).addClass('selected_item');
                    $('#dzung-hidden-month').slideDown(200);
                },
            };
            var monthEvent = {
                select : function(event){
                    $('#dzung-select-month li').removeClass('selected_item');
                    $(this).addClass('selected_item');
                    m_y = $('ul#dzung-select-month li.selected_item').attr('val') 
                        + '/' + $('ul#dzung-select-year li.selected_item').attr('val');
                    $this.val(m_y);
                    if(options.callBack != false && typeof options.callBack == 'function'){
                        options.callBack($this);
                    }
                    eventHandle.hide();
                }  
            };
            var handleNextPrev = {
                next : function(e){
                    nextItem = parseInt($('#dzung-select-year li:last-child').html()) + 1;
                    if( nextItem <= options.max){
                        $('<li/>').appendTo($('#dzung-select-year')).html(nextItem).attr('val',nextItem).bind('click',yearsEvent.select);
                        if($('#dzung-select-year li:first-child').hasClass('selected_item')){
                            $('#dzung-hidden-month').slideUp(); 
                        }
                        $('#dzung-select-year li:first-child').remove();   
                    }
                },
                prev : function(e){
                    prevItem = parseInt($('#dzung-select-year li:first-child').html()) - 1;
                    if( prevItem >= options.min){
                        $('<li/>').insertBefore($('#dzung-select-year li:first-child')).attr('val',prevItem).html(prevItem).bind('click',yearsEvent.select);
                        if($('#dzung-select-year li:last-child').hasClass('selected_item')){
                            $('#dzung-hidden-month').slideUp(); 
                        }
                        $('#dzung-select-year li:last-child').remove();   
                    }
                }
            };
            var eventHandle = {
                show : function(obj){
                    eventHandle.hide();
                    $this = obj;
                    current = $this.val().split('/');
                    if(current != 'undefined' && !isNaN(current[1])){
                        options.begginYear = current[1];
                    }
                    $dialog = $('<div id="dzung-hidden-dialog"/>').appendTo('body');
                    $year_bound = $('<div/>').appendTo($dialog);
                    $year_title = $('<div style="width: 100%; font-size:14px;"/>').append('Select Year: ');
                    $year_title.appendTo($year_bound);
                    $close_dialog = $('<span id="dzung-close-dialog"/>').appendTo($year_title).append('x');
                    $year_list_bound = $('<div style="width: 100%; float: left;"/>').appendTo($year_bound);
                    $prev_year = $('<div id="dzung-prev-year"/>').appendTo($year_list_bound);
                    $year_container = $('<div id="dzung-years-container"/>').appendTo($year_list_bound);
                    $list_year = $('<ul id="dzung-select-year">').appendTo($year_container);
                    $next_year = $('<div id="dzung-next-year"/>').appendTo($year_list_bound);
                    $('<div/>').appendTo($dialog);
                    $hidden_month = $('<div id="dzung-hidden-month"/>').appendTo($dialog);
                    $('<div style="font-size:14px;"/>').appendTo($hidden_month).append('Select Month :');
                    $list_month = $('<ul id="dzung-select-month"/>').appendTo($hidden_month);
                    
                    $list_year.append(data_list.years());
                    $list_month.append(data_list.months());
                    
                    if(current != 'undefined' && !isNaN(current[0]) && !isNaN(current[1])){
                        $list_month.children('li[val="'+current[0]+'"]').addClass('selected_item');
                        $list_year.children('li[val="'+current[1]+'"]').addClass('selected_item');
                    }
                    
                    $next_year.bind('click', handleNextPrev.next);
                    $prev_year.bind('click', handleNextPrev.prev);
                    $hidden_month.hide();
                    
                    $close_dialog.click(function(){
                        eventHandle.hide();
                    });
                    $list_year.children('li').each(function(){
                        $(this).bind('click',yearsEvent.select);
                    });
                    $list_month.children('li').each(function(){
                        $(this).bind('click',monthEvent.select);
                    });
                    $dialog.css({
                        left : $this.offset().left,
                        top : ($this.offset().top + $this.height() + 5), 
                    });      
                    $dialog.slideDown();
                },
                hide : function(){
                    $('#dzung-hidden-dialog').remove();
                }
            };
            return this.each(function() {
				var opts = options;			
			    $(this).click(function(){
                    eventHandle.show($(this));
                });
    		});
    	}
	});
})(jQuery);