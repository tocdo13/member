function FullScreen(){
	jQuery("#order_full_screen").attr('class','full_screen');
	jQuery("#full_screen_button").attr('value','[[.exit_full_screen.]]');
}
function switchFullScreen(){
	if(jQuery.cookie('order_fullScreen')==1){
		jQuery("#order_full_screen").attr('class','');
		jQuery("#full_screen_button").attr('value','[[.full_screen.]]');
		jQuery.cookie('order_fullScreen',0);
	}else{
		FullScreen();
		jQuery.cookie('order_fullScreen',1);
	}
	jQuery('.jcarousel-clip-horizontal').width(jQuery("#order_full_screen").width()-80);
	jQuery('#info_summary').width(jQuery("#order_full_screen").width()-10);		
}
if(jQuery.cookie('order_fullScreen')==1){
	FullScreen();
}		
function ShowExpan(){
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

function UpdateNumTable(){
	var count = 0;
	for(var i=101;i<=input_count;i++){
		if(jQuery('#table_id_'+i).val() != '' && jQuery('#table_id_'+i).val() != undefined){
			count++;	
		}
	}
	jQuery("#num_table").val(count);		
}

function GetNumber(name,key,pass){
	//var numberButtonValue = name;
	jQuery('.radio_number').each(function(){
		if(this.checked==true){
			var str = jQuery('#input_'+this.id).val();
			if(name=='<-'){
				var str_new = str.substr(0,str.length - 1);
			}else{
				if(name=='.'){
					var str_new = str+'.';		
				}else{
					var str_new = str + name;	
				}
			}
			var selected = this.id;
			if(pass==2){
				var quantity = to_numeric(jQuery('#input_radio_quantity').val())+to_numeric(jQuery('#quantity_'+key).val());
				var promotion = to_numeric(jQuery('#input_radio_promotion').val())+to_numeric(jQuery('#promotion_'+key).val());
				var product_return = to_numeric(jQuery('#input_radio_return').val())+to_numeric(jQuery('#remain_'+key).val());
				var product_cancel = to_numeric(jQuery('#input_radio_cancel').val())+to_numeric(jQuery('#cancel_'+key).val());
			}else{
				var quantity = to_numeric(jQuery('#input_radio_quantity').val());
				var promotion = to_numeric(jQuery('#input_radio_promotion').val());
				var product_return = to_numeric(jQuery('#input_radio_return').val());
				var product_cancel = to_numeric(jQuery('#input_radio_cancel').val());
			}
			if(selected == 'radio_promotion'){
				promotion = 0;
			}else if(selected == 'radio_return'){
				product_return = 0;
			}else if(selected == 'radio_cancel'){
				product_cancel = 0;
			}
			//str_new = to_numeric(str_new);
			if(this.lang=='quantity'){
				if((to_numeric(str_new) + product_return + promotion + product_cancel)>to_numeric(quantity)){
					jQuery('#input_'+this.id).val(str);    
				}else{
					jQuery('#input_'+this.id).val(str_new);
				}
			}else if(this.lang=='percentage'){
				if(to_numeric(str_new)>100){
					jQuery('#input_'+this.id).val(str);
				}else{jQuery('#input_'+this.id).val(str_new);}
			}else{
				jQuery('#input_'+this.id).val(str_new);
			}
			jQuery('#input_'+this.id).FormatNumber();
		}
	});
	update_amount(true,key);
}
function update_amount(kt,key){ // Tong tien cua sp khi dung hop chon
	if(kt == true){
        var price = to_numeric(jQuery('#input_radio_price').val());
		var quantity = to_numeric(jQuery('#input_radio_quantity').val());
		var promotion = 0; var product_cancel = 0;
		var percentage = 0; var product_return = 0;
		var amount = 0;
		if(jQuery('#input_radio_promotion').val() != ''){
			promotion = to_numeric(jQuery('#input_radio_promotion').val());
		}
		if(jQuery('#input_radio_percentage').val() != ''){
			percentage = to_numeric(jQuery('#input_radio_percentage').val());
		}
        if(jQuery('#discount_category').val() != ''){
			promotion = to_numeric(jQuery('#discount_category').val());
		}
		if(jQuery('#input_radio_return').val() != ''){
			product_return = to_numeric(jQuery('#input_radio_return').val());
		}
		if(jQuery('#input_radio_cancel').val() != ''){
			product_cancel = to_numeric(jQuery('#input_radio_cancel').val());
		}
		amount = (price * (quantity - (promotion+product_cancel+product_return))); 
		//amount = amount * ((100-to_numeric(jQuery('#discount_category').val()))/100);
        amount = amount-to_numeric(jQuery('#discount_category').val());
		totalAmount = amount * ((100-percentage)/100);
		jQuery('#amount').val(number_format(roundNumber(totalAmount,2)));
	}
}
function update_item_amount(key){
totalAmount = 0;	
if(key)
	{
	 // tinh tong tien cua 1 san pham.
	var price = to_numeric(jQuery('#price_'+key).val());
	var quantity = to_numeric(jQuery('#quantity_'+key).val());
	var promotion = to_numeric(jQuery('#promotion_'+key).val());
	var percentage = to_numeric(jQuery('#percentage_'+key).val());
	var quantity_return = to_numeric(jQuery('#remain_'+key).val());
	var quantity_cancel = to_numeric(jQuery('#quantity_cancel_'+key).val());
	var oldAmount = to_numeric(jQuery('#amount_'+key).val());
	var oldTotalAmount = to_numeric(jQuery('#total_amount').val());
	var total_discount = 0;
	if(promotion==''){
		promotion =0;	
	}
	var amount = (price * (quantity - (promotion)));
	total_discount = amount * (to_numeric(jQuery('#discount_category_'+key).val())/100);		
	total_discount += (amount - total_discount)*percentage/100;
	totalAmount = amount - total_discount;
	jQuery('#amount_'+key).val(number_format(totalAmount));
	}
	return totalAmount;
}
function update_total_amount(){
	var total_amount = 0;
	jQuery('.selected-foot-and-drink-table').each(function(){
		var key = jQuery(this).attr('lang');
		if(key != ''){
			total_amount += update_item_amount(key);
		}
	});
    
	return total_amount;
}


function GetTotalPayment(){
		var order_discount = 0;
		var order_percent = 0; 
		var deposit = 0;
		var service_rate = 0;
		var tax_rate = 0;
		var totalAmount = 0;
		var addDiscount = 0;
		totalAmount = update_total_amount();
        
		if(jQuery("#discount").val() != ''){
		   var order_discount = to_numeric(jQuery("#discount").val());
		}
		if(jQuery("#discount_percent").val() != ''){
		   var order_percent = to_numeric(jQuery("#discount_percent").val());
		}
        
		if(jQuery("#service_charge").val() != ''){
		   var service_rate = to_numeric(jQuery("#service_charge").val());
		}
		if(jQuery("#tax_rate").val() != ''){
		   var tax_rate = to_numeric(jQuery("#tax_rate").val());
		}
		if(jQuery("#deposit").val() != ''){
		   var deposit = to_numeric(jQuery("#deposit").val());
		} 
        if(discount_after_tax)//neu giam gia sau thue phi
        {
            if(full_rate){
                totalAmount = totalAmount;
            }else if(full_charge){
                totalAmount = totalAmount + totalAmount*tax_rate*0.01
            }
            else{
                var total_service = totalAmount * service_rate *0.01;
			    var total_tax = (totalAmount + total_service) * tax_rate * 0.01;
                totalAmount = totalAmount + total_service + total_tax;
            }
            totalAmount = totalAmount - totalAmount*order_percent*0.01 - order_discount; //GG hoa don
            jQuery('#total_payment').val(number_format(totalAmount));
            jQuery('#total_amount').val(number_format(Math.floor((totalAmount*100/(100 + tax_rate))*100/(100 + service_rate),2)));
        }
        else//neu giam gia truoc thue phi
        {
            if(full_rate){
                totalAmount = (totalAmount*100/(100 + tax_rate))*100/(100 + service_rate);
            }else if(full_charge){
                totalAmount = totalAmount*100/(100 + service_rate)
            }
            else{
                totalAmount = totalAmount;
            }
            totalAmount = totalAmount - totalAmount*order_percent*0.01 - order_discount; //GG hoa don
            jQuery('#total_amount').val(number_format(Math.floor(totalAmount,0)));
            var total_service = totalAmount * service_rate *0.01;
			var total_tax = (totalAmount + total_service) * tax_rate * 0.01;
            jQuery('#total_payment').val(number_format(totalAmount + total_service + total_tax));
        }
        //jQuery('#total_payment').val(number_format(Math.floor(totalAmount + totalAmount*tax_rate*0.01,0)));
		//jQuery('#total_amount').val(number_format(Math.ceil(totalAmount/param,0)));
       if(to_numeric(jQuery("#total_payment").val())<=amount_package)
       {
            jQuery("#total_payment").val(0);
       }
       else
       {
            jQuery("#total_payment").val(number_format(to_numeric(jQuery("#total_payment").val())-amount_package));
       } 
	   jQuery("#remain").val(number_format(to_numeric(jQuery("#total_payment").val()) - deposit));
	   jQuery("#amount_mini").html(jQuery("#remain").val());
       /** manh sua phan them so tien khach dua **/
       var total_payment_traveller = to_numeric(jQuery("#total_payment_traveller").val());
       if(total_payment_traveller>=1){
            jQuery("#total_remain_traveller").html(number_format(total_payment_traveller - to_numeric(jQuery("#amount_mini").html())));
       }
       /** end manh **/
	}
function GetAmount(){
	var quantity = 	to_numeric(jQuery('#quantity').val());
	var promotion_quantity = to_numeric(jQuery('#promotion_quantity').val());
	var percentage = to_numeric(jQuery('#percentage').val());
	var price = to_numeric(jQuery('#input_radio_price').val());
	if(quantity <= promotion_quantity){
		var amount = 0;
	}else{
		var amount = roundNumber(((quantity - promotion_quantity) * price) - ((quantity - promotion_quantity) * price)*percentage/100,2);
	}
	jQuery('#amount').val(amount);
	return amount;
}
function GetTotalAmount(){
	return to_numeric(jQuery("#total_amount").val());
}
function DeleteProduct(key){
	var items_id = jQuery("#items_id").val();
	var items = items_id.replace(key,'');
	jQuery("#items_id").val(items);
	if(jQuery("#items_id").val() == ','){
		jQuery("#items_id").val('');	
	}	
	var new_item =items.replace(',,',',');
	jQuery("#items_id").val(new_item);
	jQuery("#table_"+key).remove();
	GetTotalPayment();
}
function CancelProduct(key){
	var conf = confirm('Do you want cancel all product?');
	if(conf){
		quantity = to_numeric(jQuery('#quantity_'+key).val());
		remain = to_numeric(jQuery('#remain_'+key).val());
		promotion = to_numeric(jQuery('#promotion_'+key).val());
		jQuery('#quantity_cancel_'+key).val(quantity - remain - promotion);
		var oldTotalAmount = GetTotalAmount();
		var oldAmount = to_numeric(jQuery('#amount_'+key).val());
		GetTotalPayment();
		//jQuery("#total_amount").val(number_format(roundNumber(to_numeric(oldTotalAmount) - oldAmount,2)));
		GetTotalPayment();
		jQuery("#table_"+key+" tr td").css('color','#D6D6D6');
		jQuery("#table_"+key+" tr td input").css('color','#D6D6D6');
		jQuery("#table_"+arr['id']+" tr td").css('cursor','default');
		jQuery("#table_"+arr['id']+" tr td input").css('cursor','default');
	}
}
function CheckPrice(obj,ktt){
	var price = to_numeric(obj.value);
	if(!is_numeric(price)){
		alert('[[.this_is_not_a_number.]]');	
		jQuery('#input_radio_price').val('');
	}else{
		if(ktt == ''){
			jQuery('#input_radio_price').val(number_format(price));	
			var quantity = to_numeric(jQuery('#input_radio_quantity').val());
			jQuery('#input_radio_price').val(number_format(price));
			jQuery('#amount').val(number_format(price*quantity));		
		}else{
			jQuery('#price_product_'+ktt).val(number_format(price));		
		}
	}
}
function HideDialog(obj){
  jQuery("#"+obj).fadeOut(300);
  //jQuery("#"+obj).fadeOut(300);
} 

function getSummary(){
	//jQuery('#div_bound_genneral_info').css('display','none');
	jQuery('#div_bound_summary').css('display','block');
	jQuery("#bound_summary").fadeIn(300);	
	GetTotalPayment();
}
function getResevationRoom(){
	jQuery('#div_bound_summary').css('display','none');
	//jQuery('#div_bound_genneral_info').css('display','block');
	//jQuery("#bound_genneral_info").fadeIn(400);	
}
function getNote(id){
	jQuery("#dialog_keyboard").fadeIn(300);
	jQuery('#text_keyboard').focus();	
	jQuery('#dialog_keyboard').css('display','block');
	jQuery('#text_keyboard').val(jQuery('#note_'+id).val());
	jQuery('#id_note').val(id);
	//jQuery('#note_product').val(jQuery('#note_'+id).html());	
}

function items_checked()
{
    var check = 0;
    var key = '';
    jQuery('.ids_selected').each(function()
    {
        var id_selected=jQuery(this).attr('lang');
        
        if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked')
        {
            check = check + 1;    
            key = id_selected;
        }
    });    
    
    if(check==1 && key!='')
    {
        //console.log(key);
        SelectedItems(key,1);
    }
    else
    {
        //console.log('hhhh');
        return false;    
    } 
}
//jQuery('.list-number').focus(function(){
function getSelectQuantity(name){
	var can_action =0;
    var quantity = 0;
    var printed = 0;
    var max_select = 1000;
    var can_action = 1;
	jQuery('.ids_selected').each(function(){
		var id_selected=jQuery(this).attr('lang');
		if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){
		    if(name=='delete'){  
                quantity = to_numeric(jQuery("#quantity_"+id_selected).val());
                 
                printed = to_numeric(jQuery("#printed_"+id_selected).val());
                
                //console.log(quantity+","+printed);
                if(quantity!=printed){
                    if(max_select>=(quantity - printed)){
                        max_select = quantity - printed;
                    }
                }
                else
                {
                    alert("San pham duoc in order, khong chinh sua so luong!");
                    if(max_select>=(quantity - printed)){
                        max_select = quantity - printed;
                    }
                    can_action = 0;
                    return false;
                    
                }
                
            }
		}
	});
    
	var status = jQuery('#status').val();
	if((can_action == 1 || name=='discount') && ((status=='CHECKOUT' && can_admin_bar) || status !='CHECKOUT'))
    {
		var obj = name;
		var textkeyboard= '<div id="select_number" class="select_number" style="width:260px;" onclick="event.stopPropagation();"><span id="title_quantity" style="font-size:14px;font-weight:bold;"></span>';
		textkeyboard += '<input name="number_selected" type="text" id="number_selected" class="input_number" style="width:222px; height:35px; margin-bottom:10px; text-align:right;font-size:18px;"><ul id="list_number" class="list_number">';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(1,\''+obj+'\','+max_select+');">1</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(2,\''+obj+'\','+max_select+');">2</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(3,\''+obj+'\','+max_select+');">3</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(4,\''+obj+'\','+max_select+');">4</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(5,\''+obj+'\','+max_select+');">5</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(6,\''+obj+'\','+max_select+');">6</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(7,\''+obj+'\','+max_select+');">7</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(8,\''+obj+'\','+max_select+');">8</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(9,\''+obj+'\','+max_select+');">9</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(0,\''+obj+'\','+max_select+');">0</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(12,\''+obj+'\','+max_select+');">.</li>';
		textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber(13,\''+obj+'\','+max_select+');"><-</li>';
		textkeyboard += '</ul>';
        /** Daund thêm lý do giảm giá toàn hóa đơn */
        if(name=='discount'){
        textkeyboard += '<span>Reason discount</span><textarea name="reason_discount_popup" id="reason_discount_popup" style="width: 224px; height: 50px; overflow: hidden;"></textarea>';    
        }
        /** Daund thêm lý do giảm giá toàn hóa đơn */
		textkeyboard += '<input name="clear_number" type="button" value="CLEAR" id="clear_number" onclick="jQuery(\'#number_selected\').val(\'\');jQuery(\'#reason_discount_popup\').val(\'\');" class="button_disable_small"><input name="cancel_number" type="button" value="CANCEL" id="cancel_number" onclick="HideDialog(\'select_number\');" class="button_disable_small">';
		textkeyboard += '<input name="ok_number" type="button" value="OK" id="ok_number" onclick="selectNumber(11,\''+obj+'\','+max_select+');" class="button_allow"></div>';
		jQuery('#dialog_number').html(textkeyboard);
		var check = 1;
		if(name=='delete'){ 
			jQuery('.ids_selected').each(function(){
				var id_selected=jQuery(this).attr('lang');
				if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked' ){
					if(jQuery('#quantity_'+id_selected).val()!=1){
						check = 0;	
					}
				}
			});	
		}
		if(name=='delete' && check==1){
			jQuery('#number_selected').val(1);
			selectNumber(11,obj,max_select);	
		}else{
			jQuery("#select_number").fadeIn(300);	
			jQuery('#number_selected').focus();
			//jQuery('#number_selected').ForceNumericOnly();
		}
		if(name=='delete' && check==0){
			jQuery('#title_quantity').html('Delete How Many?');	
		}else if(name=='quantity'){
			jQuery('#title_quantity').html('Enter Quantity');	
		}else if(name=='discount'){
			jQuery('#title_quantity').html('Enter Discount %');	
		}else if(name=='percentage'){
			jQuery('#title_quantity').html('Enter Discount Percentage');	
		}
	}
    else if(can_action==0)
    {
        jQuery('#dialog_number').html('');
    }
}
function getSelectOutSide(name){
	var status = jQuery('#status').val();
	if((status=='CHECKOUT' && can_admin) || status !='CHECKOUT'){
		var obj = name;
		var textkeyboard= '<div id="select_outside" class="select_outside" style="width:260px;" onclick="event.stopPropagation();"><span id="title_quantity" style="font-size:14px;font-weight:bold;">SELECT EXTRA MENU</span>';
		textkeyboard += '<br><br>NAME: <input name="name_outside" type="text" id="name_outside" style="width:200px; height:25px; margin-bottom:10px; text-align:right;"><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" onclick="jQuery(\'#name_outside\').val(\'\');" style="margin-left:5px;"/>';
		textkeyboard += 'PRICE: <input name="price_outside" type="text" id="price_outside" onkeyup="this.value=number_format(this.value);" style="width:200px; height:25px; margin-bottom:10px; text-align:right;"><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" onclick="jQuery(\'#price_outside\').val(\'\');" style="margin-left:5px;"/>';
		textkeyboard += 'QUANTITY: <input name="quantity_outside" type="text" id="quantity_outside" value="1" style="width:200px; height:25px; margin-bottom:10px; text-align:right;"><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" onclick="jQuery(\'#quantity_outside\').val(\'\');" style="margin-left:5px;"/>';
		textkeyboard += '<br>';
		textkeyboard += '<input name="d_outside" type="button" value="DRINK" id="d_outside" onclick="if(jQuery(\'#name_outside\').val()!=\'\' && to_numeric(jQuery(\'#price_outside\').val())>=0 && to_numeric(jQuery(\'#quantity_outside\').val())>0){SelectedItems(\'DOUTSIDE\',0);}HideDialog(\'select_outside\');" class="button_allow_small"><input name="f_outside" type="button" value="FOOD" id="f_outside" onclick="if(jQuery(\'#name_outside\').val()!=\'\' && to_numeric(jQuery(\'#price_outside\').val())>=0 && to_numeric(jQuery(\'#quantity_outside\').val())>0){SelectedItems(\'FOUTSIDE\',0);}HideDialog(\'select_outside\');" class="button_allow_small">';
		textkeyboard += '<br><input name="s_outside" type="button" value="SERVICE" id="s_outside" onclick="if(jQuery(\'#name_outside\').val()!=\'\' && to_numeric(jQuery(\'#price_outside\').val())>=0 && to_numeric(jQuery(\'#quantity_outside\').val())>0){SelectedItems(\'SOUTSIDE\',0);}HideDialog(\'select_outside\');" class="button_allow_small"><input name="cancel_outside" type="button" value="CANCEL" onclick="HideDialog(\'select_outside\');" class="button_disable_small" ></div>';
		jQuery('#dialog_number').html(textkeyboard);
		jQuery("#select_outside").fadeIn(300);	
		jQuery('#name_outside').focus();
		jQuery('#price_outside').ForceNumericOnly();
		jQuery('#quantity_outside').ForceNumericOnly();
	}
}
function selectNumber(value,obj,max_select){
	if(value==11){
		HideDialog('select_number');
		var quantity_select = to_numeric(jQuery('#number_selected').val());
		jQuery('.ids_selected').each(function(){
			var id_selected=jQuery(this).attr('lang');
			if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){
				if(obj == 'quantity'){
					jQuery('#quantity_'+id_selected).val(to_numeric(jQuery('#quantity_'+id_selected).val())+quantity_select);	
					jQuery('#quantity_'+id_selected).attr("hasEditedQuantity","");
					jQuery("input[id^=SET_"+id_selected+"]").each(function(){
					   var real_value = to_numeric(jQuery(this).val());
                       var original_value = to_numeric(jQuery(this).attr('original_value'));
                       jQuery(this).val(parseInt(real_value+original_value*quantity_select));
					});
                    				update_item_amount(id_selected);
				}else if(obj == 'delete'){
				    if(quantity_select>=max_select){
				        quantity_select=max_select;
				    }
					if(to_numeric(jQuery('#quantity_'+id_selected).val()) <= quantity_select){
						DeleteProduct(id_selected);
						id_selected = '';
					}else{
					    jQuery("input[id^=SET_"+id_selected+"]").each(function(){
    					   var real_value = jQuery(this).val();
                           var original_value = jQuery(this).attr('original_value');
                           jQuery(this).val(parseInt(real_value-original_value*quantity_select));
    					});   
						jQuery('#quantity_'+id_selected).val(to_numeric(jQuery('#quantity_'+id_selected).val())-quantity_select);	
						update_item_amount(id_selected);
					}
				}else if(obj == 'percentage' && quantity_select<=100){
					jQuery('#percentage_'+id_selected).val(quantity_select);
					update_item_amount(id_selected);
				}
			}
		});
		if(obj == 'discount' && quantity_select<=100){
			jQuery('#discount_percent').val(quantity_select);
            var reason_discount = to_numeric(jQuery('#reason_discount_popup').val());
            jQuery('#reason_discount').val(jQuery('#reason_discount_popup').val().replace(/\r?\n/g, '<br />'));
		}
		GetTotalPayment();	
		return;
	}
	var old_val = jQuery('#number_selected').val();	
	var old_val_1 = old_val;
	if(value==13){
		old_val = old_val.substr(0,old_val.length - 1);
	}else{
		if(value==12){
			var n = old_val.indexOf(".");
			if(n<0){
				old_val = old_val + '.';
			}
		}else{
				old_val = old_val + value;
		}
	}
	if((obj == 'discount' || obj == 'percentage') && to_numeric(old_val) <= 100){
		jQuery('#number_selected').val(old_val);
	}else if((obj == 'discount' || obj == 'percentage') && to_numeric(old_val) >= 100){
		jQuery('#number_selected').val(old_val_1);
	}else if(obj != 'discount' && obj != 'percentage'){
		jQuery('#number_selected').val(old_val);
	}
	GetTotalPayment();
}
function paging(step_number){
	jQuery(function($){
		jQuery('#bound_product_list').easyPaginate({
			step:step_number,
			delay:10
		});
	});
}

function get_keyboard(obj,key){
		if(obj==false){
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
		textkeyboard += '<li class="space lastitem">SPACE</li>';
		textkeyboard += '<li class="accept" onclick="HideDialog(\'dialog_keyboard\');if(jQuery(\'#name_keyboard\').val()==\'input_product_name\'){searchProduct(\'\',\'\');}else if(jQuery(\'#name_keyboard\').val()==\'note_product\'){submitNote(jQuery(\'#text_keyboard\').val(),jQuery(\'#id_note\').val());}">ACCEPT</li>';
		textkeyboard += '</ul></div>';	
	jQuery('#view_keyboard').html(textkeyboard);
	jQuery("#view_keyboard").fadeIn(300);
	if(key != ''){
		jQuery('#text_keyboard').val(jQuery('#note_'+key).val());
	}else{
		jQuery('#text_keyboard').val(jQuery('#input_product_name').val());	
	}
	jQuery('#text_keyboard').focus();
	jQuery('#dialog_keyboard').keyboard_action();
}
function setAmountPayment(obj){
	var value = 0;
	id = obj.getAttribute('lang');
	code = obj.getAttribute('alt');
	name = obj.name;
	jQuery('.input-payment').each(function(){
		if(this.lang != id){
			value = to_numeric(value) + to_numeric(this.value);
		}
	});	
	jQuery("#input_pmt_"+code).val(number_format(to_numeric($('remain').value) - value));
	if(code=='ROOM'){
		checkNumber(obj);		
	}
}
function checkNumber(obj){
	if(!is_numeric(to_numeric(obj.value))){
		alert('[[.is_not_number.]]');
		obj.value = '';
	}else{
		if(to_numeric(obj.value) ==0){
			//jQuery('#guest_room_info').hide();
			jQuery('#reservation_traveller_id').value(0);	
			jQuery('#reservation_room_id').value(0);	
		}
		code = obj.getAttribute('alt');
		if(code=='ROOM' && (jQuery('#reservation_traveller_id').val()==0) && (jQuery('#reservation_room_id').val()==0)){
			alert('[[.insert_room_guest.]]');
			//jQuery('#guest_room_info').show();//css('display','block');	
		}
	}
}
function checkSearchProduct(obj,keyCode){
	var val = obj.value;	
	if(jQuery('#dialog_keyboard').css('display') == 'block'){
		var str = jQuery('#input_product_name').val();
		jQuery('#text_keyboard').val(str);	
	}
	}
	function DeleteTable(key){
	jQuery('#table_list_'+key).remove();	
	}
function SetCss(){
	var count = 0;
	jQuery('.ids_selected').each(function(){
		if(jQuery(this).attr('checked')==true || jQuery(this).attr('checked')=='checked'){
			count = count+1;
			check_selected = 1;
		}
        jQuery('.menu_extra_li').addClass('css_menu_estra_disable');
		jQuery('.menu_extra_li').css({'background':'#d6d6d6','cursor':'default'});
		jQuery('.menu_extra_li img').css('background','#d6d6d6');
	});
    
	if(check_selected==0){
		jQuery('.menu_extra_li').addClass('css_menu_estra_disable');
		jQuery('.menu_extra_li').css({'background':'#d6d6d6','cursor':'default'});
		jQuery('.menu_extra_li img').css('background','#d6d6d6');
	}else{
		jQuery('.menu_extra_li').removeClass('css_menu_estra_disable');
		jQuery('.menu_extra_li').css({'background':'#ffffc0','cursor':'pointer'});
		jQuery('.menu_extra_li img').css('background','#ffffc0');
		if(count >1 || count == 0){
			jQuery('#li_edit').addClass('css_menu_estra_disable');
			jQuery('#li_edit').css({'background':'#d6d6d6','cursor':'default'});
			jQuery('#li_edit img').css('background','#d6d6d6');	
		}
	}
    
    if(check_print_invoice==1)
    {
        jQuery('.menu_extra_li').addClass('css_menu_estra_disable');
		jQuery('.menu_extra_li').css({'background':'#d6d6d6','cursor':'default'});
		jQuery('.menu_extra_li img').css('background','#d6d6d6');
        jQuery('.menu_extra_li').attr('onclick','');
        jQuery('#li_discount').attr('onclick','').css({'background':'#d6d6d6','cursor':'default'});
        jQuery('#discount').attr('readonly','readonly');
        jQuery('#discount_percent').attr('readonly','readonly');
    }
    
	if(order_id==''){
		jQuery('.menu_extra_li_order').attr('onclick','');
		jQuery('.menu_extra_li_order').css({'background':'#d6d6d6','cursor':'default'});
		jQuery('.menu_extra_li_order img').css('background','#d6d6d6');
	}else{
		jQuery('.menu_extra_li_order').css('background','#ffffc0');
		jQuery('.menu_extra_li_order img').css('background','#ffffc0');
	}	
}
function blockcss()
{
        check_selected =0;
        count = 0;
        jQuery('.menu_extra_li_extra').addClass('css_menu_estra_disable');
		jQuery('.menu_extra_li_extra').css({'background':'#d6d6d6','cursor':'default'});
		jQuery('.menu_extra_li_extra img').css('background','#d6d6d6');
        
        jQuery('.menu_extra_li').addClass('css_menu_estra_disable');
		jQuery('.menu_extra_li').css({'background':'#d6d6d6','cursor':'default'});
		jQuery('.menu_extra_li img').css('background','#d6d6d6');
        
        jQuery('#item_detail_'+id).children().css('background','#ffffff');	
		jQuery('#item_detail_'+id+' :input').css('background','#ffffff');	
        
}
function CssSelected(){
		var kt = 0;
		jQuery('.ids_selected').each(function(){
			var id=jQuery(this).attr('lang');
			if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){
				jQuery('#item_detail_'+id).children().css('background','#f1bb89');
				jQuery('#item_detail_'+id+' :input').css('background','#f1bb89');
				kt = 1;
			}else{
				jQuery('#item_detail_'+id).children().css('background','#ffffff');	
				jQuery('#item_detail_'+id+' :input').css('background','#ffffff');		
			}
		});
		if(kt==0){
			check_selected = 0;	
		}
           SetCss(); 
       	
	}
function block_selected(){
		var kt = 0;
		jQuery('.checkblock').each(function(){
			var id=jQuery(this).attr('lang');
			if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){
				jQuery('#item_detail_'+id).children().css('background','#ffffff');	
				jQuery('#item_detail_'+id+' :input').css('background','#ffffff');
				kt = 0;
			}else{
				jQuery('#item_detail_'+id).children().css('background','#ffffff');	
				jQuery('#item_detail_'+id+' :input').css('background','#ffffff');		
			}
		});
		if(kt==0){
			check_selected = 0;	
		}
		blockcss();	
	}
function updateUnit(){
		if(jQuery('#unit_abc')){
			if(typeof(units[jQuery('#unit_abc').val()])=='undefined'){
			}else{
				jQuery('#unit_abc').val(units[jQuery('#unit_abc').val()]['id']);
				jQuery('#unit_id').val(units[jQuery('#unit_abc').val()]['name']);
			}
		}
	}
