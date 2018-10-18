
// JavaScript Document
var total = 0;
function get_product(code)
{
	for(i in product_array)
	{
		if(typeof product_array[i]['product_id']!= 'undefined' && product_array[i]['product_id']==code)
		{
			return product_array[i];
		}
	}
	return false;
}

function get_discount_before_tax(obj){
	discount = obj.value;
	if(discount > 100){
		alert('Không nhập quá 100%');	
	}else if(!is_numeric(to_numeric(discount))){
		alert('KhÃ�Â´ng Ã�ï¿½Ã�Âºng Ã�ï¿½Ã¡Â»ï¿½nh dÃ¡ÂºÂ¡ng');	
	}
	else{
		jQuery('.product_discount').each(function(){
			id = jQuery(this).attr('id');
			id = id.replace('discount_','');
			product = get_product($('product_id_'+id).value);
			if(discount !='0')
			{
				$('discount_'+id).value = discount;
				$('discount_'+id).readonly = 'readonly'
				update_total_checkin($('product_id_'+id),$('price_id_'+id).value);
			}
			else
			{
				$('discount_'+id).value = 0;
				update_total_checkin($('product_id_'+id),$('price_id_'+id).value);
			}
			
			//alert($('total_'+index).value);
		});
	}
}
function update_product(value,index)
{
	var product = get_product(value);
	jQuery('#name_'+index).addClass('readonly_input');
	//jQuery('#price_'+index).addClass('readonly_input');
	jQuery('#name_'+index).attr('readonly',true);
	//jQuery('#price_'+index).attr('readonly',true);
    jQuery('#price_'+index).ForceNumericOnly().FormatNumber();	
    jQuery('#quantity_'+index).ForceNumericOnly().FormatNumber();
	jQuery('#units_id_'+index).css('display','none');
	jQuery('#unit_'+index).css('display','block');	
	var breakFlag = false;
	if(product)
	{
		jQuery('.bar-product-id').each(function(){
			if(product.code == jQuery(this).val() && jQuery(this).attr('id')!=obj.id){
				id = jQuery(this).attr('id');
				id = id.replace('product_id_','');
				var q = to_numeric($('quantity_'+id).value);
				//$('quantity_'+id).value = q + 1;
				//alert('Da nhap '+obj.value+': '+q);
				//breakFlag = true;
			}
		});
		if(breakFlag==false){
			$('product_id_'+index).value = product.product_id;
			$('name_'+index).value = product.name;
			$('unit_'+index).value = product.unit;
			$('quantity_'+index).value = 0;
			$('price_'+index).value = number_format(roundNumber(product.price,2));
			if($('discount_'+index)){
				update_total_checkin(value,index);
			}else{
				update_total(value,product,index);	
			}
		}else{
			$('product_id_'+index).value ='';
			$('name_'+index).value = '';
			$('unit_'+index).value = '';
			$('quantity_'+index).value = 0;
			$('price_'+index).value = 0;
		}
	}
	else
	{
		$('name_'+index).value = '';
		$('unit_'+index).value = '';
		$('quantity_'+index).value = 0;
		$('price_'+index).value = 0;
	}
	if($('discount_'+index))
		calculate_checkin();
    else
		calculate();
}

function update_total(value,index)
{
    //var product = get_product(value);
    //alert(roundNumber(to_numeric($('quantity_'+index).value)));
	if($('quantity_'+index) && $('quantity_'+index).value != '' && $('price_'+index) && $('price_'+index).value != '')
		$('total_'+index).value = number_format(parseInt($('quantity_'+index).value.replace(/\,/g,''))*parseInt($('price_'+index).value.replace(/\,/g,'')));
	else
        jQuery("#total_"+index).val(0);
}

function calculate(needed)
{
    /*
	function exchange(c)
	{
		c1=c.split(".");
		//c2=c1[0].split(",");
		return parseInt(c1[0])*1000+parseInt(c1[1]);
	}
    */
	var total = 0;
    //Tong tien phong va menu
    var total_mi_price = 0;
	var discount_percent = 0;
	/*
    for(var i=101; i<=input_count; i++)
	{
		if($('banquet_room_id_'+i))
		{
			update_banquet_room($('banquet_room_id_'+i).value,i);
		}
		if($('total_'+i ) )
		{
            if( $('party_category').value=='ROOM_PRICE' ) //Bo qua tien menu
            {
                if(jQuery("#total_"+i).attr('group') =='ROOM' ) 
                    total_mi_price += roundNumber(total + to_numeric($('total_'+i).value),2);
            }
            else
            {
                if(jQuery("#total_"+i).attr('group') =='MENU' ) //Bo qua tien phong
                    total_mi_price += roundNumber(total + to_numeric($('total_'+i).value),2);   
            }
            //total = roundNumber(total + to_numeric($('total_'+i).value),2);
		}
	}
    */
//	total_full_price = 0;
//	if($('party_category').value=='FULL_PRICE')
//	{
//		total_full_price = to_numeric($('num_people').value)*to_numeric($('price_per_people').value.replace(/\,/g,''));
//	}
//    else
//    {       
    	for(var i=101; i<=input_count; i++)
    	{
    		if($('banquet_room_id_'+i) && needed === undefined)
    		{
    			//update_banquet_room($('banquet_room_id_'+i).value,i);
    		}
    		if($('total_'+i))
    		{
                total_mi_price += roundNumber(total + to_numeric($('total_'+i).value),2);  
    		}
    	}   
    //}
    //alert(total_mi_price);
	//total += total_full_price;
    total += total_mi_price;
    var coffee = jQuery('#coffee_total_money').length;
    if (jQuery('#coffee_total_money').length !=0 || jQuery('#water_total_money').length !=0)
    {
        if(jQuery('#coffee_total_money').val() != '')
            total += parseInt(jQuery('#coffee_total_money').val().replace(/\,/g,''));
        if(jQuery('#water_total_money').val() != '')
            total += parseInt(jQuery('#water_total_money').val().replace(/\,/g,''));   
    }
    //alert(total);
	$('summary').value = number_format(roundNumber(total,2));
	//-------service -----------	
	if($('service_rate')){
		service = $('service_rate').value;
	} else {
		service = $('bar_fee_rate').value;
	}
	service_total = total*service/100;
	//tax ----------
	tax = $('vat').value;
	tax_total = (total+service_total)*tax/100;
	
	if($('tax_total')) $('tax_total').value = number_format(roundNumber(tax_total,2));
	if($('service_total')) $('service_total').value = number_format(roundNumber(service_total,2));
	
	total = total+tax_total+service_total;
    if (jQuery('#deposit_1').val() != '')
        total -= parseInt(jQuery('#deposit_1').val().replace(/\,/g,''));
    if (jQuery('#deposit_2').val() != '')
        total -= parseInt(jQuery('#deposit_2').val().replace(/\,/g,''));
    if (jQuery('#deposit_3').val() != '')
        total -= parseInt(jQuery('#deposit_3').val().replace(/\,/g,''));
    if (jQuery('#deposit_4').val() != '')
        total -= parseInt(jQuery('#deposit_4').val().replace(/\,/g,''));
	//$('bar_fee').innerHTML = number_format(roundNumber(total*5/100,2));
	$('sum_total').value = number_format(roundNumber(total,2)); 
}
function check_barfee()
{
	if($('cb_barfee').checked)
		$('bar_fee_rate').value=5;
	else
		$('bar_fee_rate').value=0;	
}
function calculate_rate(value)
{
	
}
function update_banquet_room(banquet_room_id,index)
{
    jQuery('#total_'+index).ForceNumericOnly().FormatNumber();
	if(banquet_room_arr[banquet_room_id]!='undefined')
	{
		banquet_room = banquet_room_arr[banquet_room_id];
		if($('group_name_'+index))
		{
            if($('banquet_room_id_'+index).value!='')
            {
                //alert(banquet_room.group_name);
    			$('group_name_'+index).value =  banquet_room.group_name;
                $('address_'+index).value =  banquet_room.address;		
    			if($('time_type').value=='DAY')
    				$('total_'+index).value = number_format(banquet_room.price);
    			else
    				$('total_'+index).value = number_format(banquet_room.price_half_day);
            }
		}
	}
}

function update_meeting_room(meeting_room_id,index)
{
	if(banquet_room_arr[meeting_room_id]!='undefined')
	{
	    jQuery('#total_'+index).ForceNumericOnly().FormatNumber();
		banquet_room = banquet_room_arr[meeting_room_id];
		if($('group_name_'+index))
		{
            if($('meeting_room_id_'+index).value!='')
            {
                //alert(banquet_room.group_name);
    			$('group_name_'+index).value =  banquet_room.group_name;
                $('address_'+index).value =  banquet_room.address;			
    			if($('time_type').value=='DAY')
    				$('total_'+index).value = number_format(banquet_room.price);
    			else
    				$('total_'+index).value = number_format(banquet_room.price_half_day);
            }
		}
	}
}
function change_banquet_category(value)
{
	if(value=='FULL_PRICE')
	{
        //alert(1);
		jQuery('.room_price').css('display','none');
		jQuery('.room_price input').val(0);
		jQuery('.full_price').css('display','');
        //jQuery('.banquet_room').css('display','none');
        //jQuery('.banquet_menu').css('display','block');
	}
	else
	{
		jQuery('.room_price').css('display','');
		jQuery('.full_price').css('display','none');
		jQuery('.full_price input').val(0);
        //jQuery('.banquet_room').css('display','block');
        //jQuery('.banquet_menu').css('display','none');
	}
	calculate();
}