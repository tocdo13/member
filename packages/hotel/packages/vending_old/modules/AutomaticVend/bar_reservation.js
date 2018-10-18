function FullScreen()
{
	jQuery("#order_full_screen").attr('class','full_screen');
	jQuery("#full_screen_button").attr('value','[[.exit_full_screen.]]');
}
function switchFullScreen()
{
	if(jQuery.cookie('order_fullScreen')==1)
    {
		jQuery("#order_full_screen").attr('class','');
		jQuery("#full_screen_button").attr('value','[[.full_screen.]]');
		jQuery.cookie('order_fullScreen',0);
	}
    else
    {
		FullScreen();
		jQuery.cookie('order_fullScreen',1);
	}
	jQuery('.jcarousel-clip-horizontal').width(jQuery("#order_full_screen").width()-80);
	jQuery('#info_summary').width(jQuery("#order_full_screen").width()-10);		
}
if(jQuery.cookie('order_fullScreen')==1)
{
	FullScreen();
}		
function ShowExpan()
{
	jQuery("#post_show").css('display','block');
	jQuery("#product_select_expansion").addClass('css_product_select_expansion');
	jQuery("#expan").css('display','none');
	jQuery("#back").css('display','block');
	jQuery("#mask").css('display','block');
	jQuery(".body").css('height','auto');
	var height_expansion = jQuery("#product_select_expansion").height();
	var width_window = jQuery('#table_bound').width();
	var left_css = (jQuery(window).width() - jQuery('#table_bound').width())/2;
	jQuery("#product_select_expansion").width(jQuery('#table_bound').width());
	jQuery("#product_select_expansion").css('left',left_css);
	jQuery(".body").width(335);
	jQuery("#post_show,#show_food_drink").width(605);
	jQuery(".body").css('float','left');
	jQuery('#info_summary').css('display','none');
	jQuery("#post_show,#show_food_drink").css('float','right','top','10px');
	jQuery("#show_food_drink").css('top','20px');
	jQuery("#search_product").css('display','none');
	jQuery("#show_food_drink").html(jQuery("#foot_drink_bound").html());
}

function UpdateNumTable()
{
	var count = 0;
	for(var i=101;i<=input_count;i++){
		if(jQuery('#table_id_'+i).val() != '' && jQuery('#table_id_'+i).val() != undefined){
			count++;	
		}
	}
	jQuery("#num_table").val(count);		
}

function GetNumber(name,key,pass)
{
	//var numberButtonValue = name;
	jQuery('.radio_number').each(function()
    {
		if(this.checked==true)
        {
			var str = jQuery('#input_'+this.id).val();
			if(name=='<-')
            {
				var str_new = str.substr(0,str.length - 1);
			}
            else
            {
				if(name=='.')
                {
					var str_new = str+'.';		
				}
                else
                {
					var str_new = str + name;	
				}
			}
			var selected = this.id;
			if(pass==2)
            {
                var promotion = to_numeric(jQuery('#input_radio_promotion').val())+to_numeric(jQuery('#promotion_'+key).val());
				var quantity = to_numeric(jQuery('#input_radio_quantity').val())+to_numeric(jQuery('#quantity_'+key).val());
			}
            else
            {
				var quantity = to_numeric(jQuery('#input_radio_quantity').val());
                var promotion = to_numeric(jQuery('#input_radio_promotion').val());
			}
            if(selected == 'radio_promotion')
            {
				promotion = 0;
			}

			//str_new = to_numeric(str_new);
			if(this.lang=='quantity')
            {
				if( to_numeric(str_new) + promotion >to_numeric(quantity) )
                {
					jQuery('#input_'+this.id).val(str);    
				}
                else
                {
					jQuery('#input_'+this.id).val(str_new);
				}
			}
            else if(this.lang=='percentage')
            {
				if(to_numeric(str_new)>100)
                {
					jQuery('#input_'+this.id).val(str);
				}
                else
                {
                    jQuery('#input_'+this.id).val(str_new);
                }
			}
            else
            {
				jQuery('#input_'+this.id).val(str_new);
			}
			jQuery('#input_'+this.id).FormatNumber();
		}
	});
	update_amount(true,key);
}
function update_amount(kt,key)
{ // Tong tien cua sp khi dung hop chon
	if(kt == true)
    {
		var price = to_numeric(jQuery('#input_radio_price').val());
		var quantity = to_numeric(jQuery('#input_radio_quantity').val());
        var promotion = 0
		var percentage = 0;
		var amount = 0;
        if(jQuery('#input_radio_promotion').val() != '')
        {
			promotion = to_numeric(jQuery('#input_radio_promotion').val());
		}
		if(jQuery('#input_radio_percentage').val() != '')
        {
			percentage = to_numeric(jQuery('#input_radio_percentage').val());
		}
        var quantity_discount = to_numeric(jQuery('#discount_quantity').val());
        var real_quantity = quantity - quantity_discount;
        var real_price = price * (1 - (promotion + percentage)/100)
		totalAmount = real_quantity * real_price;
        //alert(totalAmount);
		jQuery('#amount').val(number_format(roundNumber(totalAmount,2)));
	}
}
function update_item_amount(key)
{ 
    var price = jQuery('#price_'+key).val();
    price = to_numeric(price);
    if(price == 'undefined')
    {
        price = 0;
    }
    var quantity = to_numeric(jQuery('#quantity_'+key).val());
    if(quantity == 'undefined')
    {
        quantity = 0;
    }
    if (jQuery('#promotion_'+key).length > 0)
       var promotion = to_numeric(jQuery('#promotion_'+key).val());
    else 
        promotion = 0;
    // giam gia theo so luong
    if (jQuery('#quantity_discount_'+key).length > 0)
       var quantity_discount = to_numeric(jQuery('#quantity_discount_'+key).val());
    else 
        quantity_discount = 0;
    // agent discount
    if (jQuery('#percentage_'+key).length > 0)
        var percentage = parseInt(jQuery('#percentage_'+key).val().replace(/\,/g,''));
	else 
        percentage = 0;
    // discount category
    if (jQuery('#amount_'+key).length > 0)
        var discount = parseInt(jQuery('#discount_category_'+key).val().replace(/\,/g,''));
	else 
        discount = 0;
    
    // tinh toan
    var real_quantity = quantity - quantity_discount;
    var real_price = price * (1 - (percentage + promotion)/100);
    var totalAmount = real_quantity * real_price;
    //alert(total_discount);
	jQuery('#amount_'+key).val(number_format(totalAmount));
	return totalAmount;
}
function update_total_amount()
{
	var total_amount = 0;
	jQuery('.selected-foot-and-drink-table').each(function()
    {
		var key = jQuery(this).attr('lang');
		if(key != '')
        {
			total_amount += update_item_amount(key);
		}
	});
	return total_amount;
}
function GetTotalPayment()
{
	var order_discount = 0;
	var order_percent = 0; 
	var deposit = 0;
	var service_rate = 0;
	var tax_rate = 0;
	var totalAmount = 0;
	var addDiscount = 0;
	totalAmount = update_total_amount();
    //alert(totalAmount);
	if(jQuery("#discount").val() != '')
    {
	   var order_discount = to_numeric(jQuery("#discount").val());
	}
	if(jQuery("#discount_percent").val() != '')
    {
	   var order_percent = to_numeric(jQuery("#discount_percent").val());
	}
	if(jQuery("#service_charge").val() != '')
    {
	   var service_rate = to_numeric(jQuery("#service_charge").val());
	}
	if(jQuery("#tax_rate").val() != '')
    {
	   var tax_rate = to_numeric(jQuery("#tax_rate").val());
	}
	if(jQuery("#deposit").val() != '')
    {
	   var deposit = to_numeric(jQuery("#deposit").val());
	}
	totalAmount = totalAmount - totalAmount*order_percent*0.01 - order_discount; //GG hoa don
	if(full_rate)
    {
		var param = 1 + tax_rate*0.01 + service_rate*0.01 + tax_rate*service_rate*0.01*0.01;
		jQuery('#total_payment').val(number_format(totalAmount));
		jQuery('#total_amount').val(number_format(roundNumber(totalAmount/param,2)));	
	}
    else if(full_charge)
    {
		var param = 1 + service_rate*0.01;
		jQuery('#total_payment').val(number_format(totalAmount + totalAmount*tax_rate*0.01));
		jQuery('#total_amount').val(number_format(roundNumber(totalAmount/param,2)));			
	}
    else
    {
		jQuery('#total_amount').val(number_format(totalAmount));
		var total_service = totalAmount * service_rate *0.01;
		var total_tax = (totalAmount + total_service) * tax_rate * 0.01;
		jQuery('#total_payment').val(number_format(totalAmount + total_service + total_tax));	
	}
   jQuery("#remain").val(number_format(to_numeric(jQuery("#total_payment").val()) - deposit));
   
   jQuery("#amount_mini").html(jQuery("#remain").val());
}
function GetAmount()
{
	var quantity = 	to_numeric(jQuery('#quantity').val());
    var promotion_quantity = to_numeric(jQuery('#promotion_quantity').val());
	var percentage = to_numeric(jQuery('#percentage').val());
	var price = to_numeric(jQuery('#input_radio_price').val());
    if(quantity <= promotion_quantity)
    {
		var amount = 0;
	}
    else
    {
		var amount = roundNumber(((quantity- promotion_quantity) * price)*(100-percentage)/100,2);
	}
	jQuery('#amount').val(amount);
	return amount;
}
function GetTotalAmount()
{
	return to_numeric(jQuery("#total_amount").val());
}
function DeleteProduct(key)
{
	var items_id = jQuery("#items_id").val();
	var items = items_id.replace(key,'');
	jQuery("#items_id").val(items);
	if(jQuery("#items_id").val() == ',')
    {
		jQuery("#items_id").val('');	
	}	
	var new_item =items.replace(',,',',');
	jQuery("#items_id").val(new_item);
	jQuery("#table_"+key).remove();
	GetTotalPayment();
}

function CheckPrice(obj,ktt)
{
	var price = to_numeric(obj.value);
	if(!is_numeric(price))
    {
		alert('[[.this_is_not_a_number.]]');	
		jQuery('#input_radio_price').val('');
	}
    else
    {
		if(ktt == '')
        {
			jQuery('#input_radio_price').val(number_format(price));	
			var quantity = to_numeric(jQuery('#input_radio_quantity').val());
			jQuery('#input_radio_price').val(number_format(price));
			jQuery('#amount').val(number_format(price*quantity));		
		}
        else
        {
			jQuery('#price_product_'+ktt).val(number_format(price));		
		}
	}
}
function HideDialog(obj)
{
  jQuery("#"+obj).fadeOut(300);
  //jQuery("#"+obj).fadeOut(300);
} 

function getSummary()
{
	//jQuery('#div_bound_genneral_info').css('display','none');
	jQuery('#div_bound_summary').css('display','block');
	jQuery("#bound_summary").fadeIn(300);	
}
function getResevationRoom()
{
	jQuery('#div_bound_summary').css('display','none');
	//jQuery('#div_bound_genneral_info').css('display','block');
	//jQuery("#bound_genneral_info").fadeIn(400);	
}
function getNote(id)
{
	jQuery("#dialog_keyboard").fadeIn(300);
	jQuery('#text_keyboard').focus();	
	jQuery('#dialog_keyboard').css('display','block');
	jQuery('#text_keyboard').val(jQuery('#note_'+id).val());
	jQuery('#id_note').val(id);
	//jQuery('#note_product').val(jQuery('#note_'+id).html());	
}
//jQuery('.list-number').focus(function(){
function getSelectQuantity(name)
{
	var obj = name;
	var textkeyboard= '<div id="select_number" class="select_number" style="width: 260px;" onclick="event.stopPropagation();"><span id="title_quantity" style="font-size:14px;font-weight:bold;"></span>';
	textkeyboard += '<input name="number_selected" type="text" id="number_selected" class="input_number" style="width:222px; height:35px; margin-bottom:10px; text-align:right;font-size:18px;"><ul id="list_number" class="list_number">';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(1,\''+obj+'\');">1</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(2,\''+obj+'\');">2</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(3,\''+obj+'\');">3</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(4,\''+obj+'\');">4</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(5,\''+obj+'\');">5</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(6,\''+obj+'\');">6</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(7,\''+obj+'\');">7</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(8,\''+obj+'\');">8</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(9,\''+obj+'\');">9</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(0,\''+obj+'\');">0</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(12,\''+obj+'\');">.</li>';
	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(13,\''+obj+'\');"><-</li>';
	textkeyboard += '</ul>';
	textkeyboard += '<input name="clear_number" type="button" value="CLEAR" id="clear_number" onclick="jQuery(\'#number_selected\').val(\'\');" class="button_disable_small"><input name="cancel_number" type="button" value="CANCEL" id="cancel_number" onclick="HideDialog(\'select_number\');" class="button_disable_small">';
	textkeyboard += '<input name="ok_number" type="button" value="OK" id="ok_number" onclick="selectNumber(11,\''+obj+'\');" class="button_allow"></div>';
	jQuery('#dialog_number').html(textkeyboard);
	jQuery("#select_number").fadeIn(300);	
	jQuery('#number_selected').focus();
	jQuery('#number_selected').ForceNumericOnly();
	if(name=='delete')
    {
		jQuery('#title_quantity').html('Delete How Many?');	
	}
    else 
    if(name=='quantity')
    {
		jQuery('#title_quantity').html('Enter Quantity');	
	}
    else 
    if(name=='discount')
    {
		jQuery('#title_quantity').html('Enter Discount %');	
	}
    else 
    if(name=='percentage')
    {
		jQuery('#title_quantity').html('Enter Discount Percentage');	
	}

}

function selectNumber(value,obj)
{
	if(value==11)
    {
		HideDialog('select_number');
		var quantity_select = to_numeric(jQuery('#number_selected').val());
		jQuery('.ids_selected').each(function()
        {
			var id_selected=jQuery(this).attr('lang');
			if(jQuery(this).attr('checked')== 'checked')
            {
				if(obj == 'quantity')
                {
					jQuery('#quantity_'+id_selected).val(to_numeric(jQuery('#quantity_'+id_selected).val())+quantity_select);	
					update_item_amount(id_selected);
				}
                else if(obj == 'delete')
                {
					if(to_numeric(jQuery('#quantity_'+id_selected).val()) <= quantity_select)
                    {
						DeleteProduct(id_selected);
						id_selected = '';
					}
                    else
                    {
						jQuery('#quantity_'+id_selected).val(to_numeric(jQuery('#quantity_'+id_selected).val())-quantity_select);	
						update_item_amount(id_selected);
					}
				}
                else 
                if(obj == 'percentage' && quantity_select<=100)
                {
					jQuery('#percentage_'+id_selected).val(quantity_select);
					update_item_amount(id_selected);
				}
			}
		});
		if(obj == 'discount' && quantity_select<=100)
        {  
			jQuery('#discount_percent').val(quantity_select);
		}
		GetTotalPayment();	
		return;
	}
	var old_val = jQuery('#number_selected').val();	
	var old_val_1 = old_val;
	if(value==13)
    {
		old_val = old_val.substr(0,old_val.length - 1);
	}
    else
    {
		if(value==12)
        {
			var n = old_val.indexOf(".");
			if(n<0)
            {
				old_val = old_val + '.';
			}
		}
        else
        {
		    old_val = old_val + value;
		}
	}
	if((obj == 'discount' || obj == 'percentage') && to_numeric(old_val) <= 100)
    {
		jQuery('#number_selected').val(old_val);
	}
    else 
    if((obj == 'discount' || obj == 'percentage') && to_numeric(old_val) > 100)
    {
		jQuery('#number_selected').val(old_val_1);
	}
    else 
    if(obj != 'discount' && obj != 'percentage')
    {
		jQuery('#number_selected').val(old_val);
	}
	GetTotalPayment();
}
function paging(step_number)
{
	jQuery(function($)
    {
		jQuery('#bound_product_list').easyPaginate(
        {
			step:step_number,
			delay:10
		});
	});
}

function get_keyboard(obj,key)
{
	if(obj==false)
    {
		obj = '';
	}
	var textkeyboard = '<div id="dialog_keyboard" class="dialog_keyboard">';
	textkeyboard += '<input name="name_keyboard" type="hidden" id="name_keyboard" value="'+obj+'" >';
	textkeyboard += '<input name="text_keyboard" type="text" id="text_keyboard" style="width:600px;margin-bottom:4px;" ><input name="clear" type="button" value="CLEAR" class="button_disable_small" style="height:32px; width:85px;" onclick="jQuery(\'#text_keyboard\').val(\'\');jQuery(\'#input_product_name\').val(\'\');">';
	textkeyboard += '<ul id="keyboard_'+obj+'" class="keyboard">';
	textkeyboard += '<li class="symbol"><span class="off">`</span><span class="on">~</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">1</span><span class="on">!</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">2</span><span class="on">@</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">3</span><span class="on">#</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">4</span><span class="on">$</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">5</span><span class="on">%</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">6</span><span class="on">^</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">7</span><span class="on">&</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">8</span><span class="on">*</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">9</span><span class="on">(</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">0</span><span class="on">)</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">-</span><span class="on">_</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">=</span><span class="on">+</span></li>';
	textkeyboard += '<li class="delete lastitem">Backspace</li>';
	textkeyboard += '<li class="tab">tab</li>';
	textkeyboard += '<li class="letter">q</li>';
	textkeyboard += '<li class="letter">w</li>';
	textkeyboard += '<li class="letter">e</li>';
	textkeyboard += '<li class="letter">r</li>';
	textkeyboard += '<li class="letter">t</li>';
	textkeyboard += '<li class="letter">y</li>';
	textkeyboard += '<li class="letter">u</li>';
	textkeyboard += '<li class="letter">i</li>';
	textkeyboard += '<li class="letter">o</li>';
	textkeyboard += '<li class="letter">p</li>';
	textkeyboard += '<li class="symbol" ><span class="off">[</span><span class="on">{</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">]</span><span class="on">}</span></li>';
	textkeyboard += '<li class="symbol lastitem"><span class="off">\\</span><span class="on">|</span></li>';
	textkeyboard += '<li class="capslock">caps lock</li>';
	textkeyboard += '<li class="letter">a</li>';
	textkeyboard += '<li class="letter">s</li>';
	textkeyboard += '<li class="letter">d</li>';
	textkeyboard += '<li class="letter">f</li>';
	textkeyboard += '<li class="letter">g</li>';
	textkeyboard += '<li class="letter">h</li>';
	textkeyboard += '<li class="letter">j</li>';
	textkeyboard += '<li class="letter">k</li>';
	textkeyboard += '<li class="letter">l</li>';
	textkeyboard += '<li class="symbol" ><span class="off">;</span><span class="on">:</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">\'</span><span class="on">"</span></li>';
	textkeyboard += '<li class="return lastitem" onclick="HideDialog(\'dialog_keyboard\');if(jQuery(\'#name_keyboard\').val()==\'input_product_name\'){searchProduct(\'\',\'\');}else if(jQuery(\'#name_keyboard\').val()==\'note_product\'){submitNote(jQuery(\'#text_keyboard\').val(),jQuery(\'#id_note\').val());}">OK</li>';
	textkeyboard += '<li class="left-shift">shift</li>';
	textkeyboard += '<li class="letter">z</li>';
	textkeyboard += '<li class="letter">x</li>';
	textkeyboard += '<li class="letter">c</li>';
	textkeyboard += '<li class="letter">v</li>';
	textkeyboard += '<li class="letter">b</li>';
	textkeyboard += '<li class="letter">n</li>';
	textkeyboard += '<li class="letter">m</li>';
	textkeyboard += '<li class="symbol" ><span class="off">,</span><span class="on"><</span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">.</span><span class="on">></span></li>';
	textkeyboard += '<li class="symbol" ><span class="off">/</span><span class="on">?</span></li>';
	textkeyboard += '<li class="right-shift lastitem">shift</li>';
	textkeyboard += '<li class="cancel" onclick="HideDialog(\'dialog_keyboard\');">CANCEL</li>';
	textkeyboard += '<li class="space lastitem">Â </li>';
	textkeyboard += '<li class="accept" onclick="HideDialog(\'dialog_keyboard\');if(jQuery(\'#name_keyboard\').val()==\'input_product_name\'){searchProduct(\'\',\'\');}else if(jQuery(\'#name_keyboard\').val()==\'note_product\'){submitNote(jQuery(\'#text_keyboard\').val(),jQuery(\'#id_note\').val());}">ACCEPT</li>';
	textkeyboard += '</ul></div>';	
jQuery('#view_keyboard').html(textkeyboard);
jQuery("#view_keyboard").fadeIn(300);
if(key != '')
{
	jQuery('#text_keyboard').val(jQuery('#note_'+key).val());
}
else
{
	jQuery('#text_keyboard').val(jQuery('#input_product_name').val());	
}
jQuery('#text_keyboard').focus();
jQuery('#dialog_keyboard').keyboard_action();
}
function setAmountPayment(obj)
{
    var value = 0;
    id = obj.getAttribute('lang');
    code = obj.getAttribute('alt');
    name = obj.name;
    jQuery('.input-payment').each(function()
    {
    	if(this.lang != id)
        {
    		value = to_numeric(value) + to_numeric(this.value);
    	}
    });	
    jQuery("#input_pmt_"+code).val(number_format(to_numeric($('remain').value) - value));
    if(code=='ROOM')
    {
    	checkNumber(obj);		
    }
}
function checkNumber(obj)
{
if(!is_numeric(to_numeric(obj.value)))
{
	alert('[[.is_not_number.]]');
	obj.value = '';
}
else
{
	if(to_numeric(obj.value) ==0)
    {
		//jQuery('#guest_room_info').hide();
		jQuery('#reservation_traveller_id').value(0);	
		jQuery('#reservation_room_id').value(0);	
	}
	code = obj.getAttribute('alt');
	if(code=='ROOM' && (jQuery('#reservation_traveller_id').val()==0) && (jQuery('#reservation_room_id').val()==0))
    {
		alert('[[.insert_room_guest.]]');
		//jQuery('#guest_room_info').show();//css('display','block');	
	}
}
}
function checkSearchProduct(obj,keyCode)
{
    var val = obj.value;	
    if(jQuery('#dialog_keyboard').css('display') == 'block')
    {
    	var str = jQuery('#input_product_name').val();
    	jQuery('#text_keyboard').val(str);	
    }
}
function DeleteTable(key)
{
    jQuery('#table_list_'+key).remove();	
}

function SetCss()
{
    jQuery('.ids_selected').each(function()
    {
    	if(jQuery(this).attr('checked')=='checked')
        {
    		check_selected = 1;
    	}
    });
    if(check_selected==0)
    {
    	jQuery('.menu_extra_li').addClass('css_menu_estra_disable');
    	jQuery('.menu_extra_li').css({'background':'#d6d6d6','cursor':'default'});
    	jQuery('.menu_extra_li img').css('background','#d6d6d6');
    }
    else
    {
    	jQuery('.menu_extra_li').removeClass('css_menu_estra_disable');
    	jQuery('.menu_extra_li').css({'background':'#ffffc0','cursor':'pointer'});
    	jQuery('.menu_extra_li img').css('background','#ffffc0');
    }
    if(order_id=='')
    {
    	jQuery('.menu_extra_li_order').attr('onclick','');
    	jQuery('.menu_extra_li_order').css({'background':'#d6d6d6','cursor':'default'});
    	jQuery('.menu_extra_li_order img').css('background','#d6d6d6');
    }
    else
    {
    	jQuery('.menu_extra_li_order').css('background','#ffffc0');
    	jQuery('.menu_extra_li_order img').css('background','#ffffc0');
    }	
}
function CssSelected()
{
	var kt = 0;
	jQuery('.ids_selected').each(function()
    {
		var id=jQuery(this).attr('lang');
		if(jQuery(this).attr('checked')== 'checked')
        {
			jQuery('#item_detail_'+id).children().css('background','#f1bb89');
			jQuery('#item_detail_'+id+' :input').css('background','#f1bb89');
			kt = 1;
		}
        else
        {
			jQuery('#item_detail_'+id).children().css('background','#ffffff');	
			jQuery('#item_detail_'+id+' :input').css('background','#ffffff');		
		}
	});
	if(kt==0)
    {
		check_selected = 0;	
	}
	SetCss();	
}
function updateUnit()
{
	if(jQuery('#unit_abc'))
    {
		if(typeof(units[jQuery('#unit_abc').val()])=='undefined')
        {
		}
        else
        {
			jQuery('#unit_abc').val(units[jQuery('#unit_abc').val()]['id']);
			jQuery('#unit_id').val(units[jQuery('#unit_abc').val()]['name']);
		}
	}
}
function items_checked()
{
	var check = 0;
	var key = '';
	jQuery('.ids_selected').each(function()
    {
		var id_selected=jQuery(this).attr('lang');
		if(jQuery(this).attr('checked')== 'checked')
        {
			check = check + 1;	
			key = id_selected;
		}
	});	
	if(check==1 && key!='')
    {
		SelectedItems(key,1);
	}
    else
    {
		return false;	
	}
}