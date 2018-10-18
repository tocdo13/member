// JavaScript Document
var total = 0;
function update_table(obj)
{
	table_count = obj.id.replace('table_id_','');
	if(obj.value)
	{
		$('num_people_'+table_count).value = table_num_people[obj.value];
		$('code_'+table_count).value = table_code[obj.value];
	}
}
function get_product(code)
{
	if(typeof product_array[code]!= 'undefined')
	{
		return product_array[code];
	}
	return false;
}
function update_product(obj)
{
	var product = get_product(obj.value);
	index = obj.id.replace('product_id_','');
	var breakFlag = false;
	if(product)
	{
		jQuery('.bar-product-id').each(function(){
			if(product.id == jQuery(this).val() && jQuery(this).attr('id')!=obj.id){
				id = jQuery(this).attr('id');
				id = id.replace('product_id_','');
				var q = to_numeric($('quantity_'+id).value);
				//$('quantity_'+id).value = q + 1;
				alert('Da nhap '+obj.value+': '+q);
				//breakFlag = true;
			}
		});
		if(breakFlag==false){
			$('product_id_'+index).value = product.id;
			$('name_'+index).value = product.name;
			$('unit_'+index).value = product.unit;
			$('quantity_'+index).value = 1;
			$('price_'+index).value = number_format(roundNumber(product.price,2));
			if($('discount_'+index)){
				update_total_checkin(obj);
			}else{
				update_total(obj);	
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
		if($('discount_'+index)){
			update_total_checkin(obj);
		}else{
			update_total(obj);	
		}
	}
	if($('discount_'+index)){
		calculate_checkin();
	}else{
		calculate();
	}
}

function update_total(obj)
{
	index = obj.id.replace('product_id_','');
	var product = get_product(obj.value);
	if(product && is_numeric($('quantity_'+index).value))
	{
		$('total_'+index).value = number_format(roundNumber(to_numeric($('quantity_'+index).value)*stringToNumber(product.price),2));
	}
	else
	{
		$('total_'+index).value = 0;
	}
}

function calculate()
{
	function exchange(c)
	{
		c1=c.split(".");
		/*c2=c1[0].split(",");*/
		return parseInt(c1[0])*1000+parseInt(c1[1]);
	}
	
	
	var total = 0;
	for(var i=101; i<=input_count; i++)
	{
		if($('total_'+i))
		{
			total = roundNumber(total + to_numeric($('total_'+i).value),2);
		}
	}
	//-------service -----------	
	if($('service_rate')){
		service = $('service_rate').value;
	} else {
		service = $('bar_fee_rate').value;
	}
	service_total = total*service/100;
	//tax ----------
	tax = $('tax_rate').value;
	tax_total = (total+service_total)*tax/100;
	
	if($('tax_total')) $('tax_total').value = number_format(roundNumber(tax_total,2));
	if($('service_total')) $('service_total').value = number_format(roundNumber(service_total,2));
	
	$('summary').value = number_format(roundNumber(total,2));
	total = total+tax_total+service_total;
	//$('bar_fee').innerHTML = number_format(roundNumber(total*5/100,2));
	$('sum_total').value = number_format(roundNumber(total,2));

}
function clone_input()
{
	$('receiver_name').value=$('agent_name').value;
}
function get_room(room_id)
{
	if(typeof room_array[room_id]!= 'undefined')
	{
		return room_array[room_id];
	}
	return false;
}
function update_room(obj){
	var room = get_room(obj.value);
	if(room)
    {
		$('reservation_room_id').value = room.id;
		if($('room')) $('room').checked = true;
		for(i=0;i<$('reservation_room_id').options.length;i++)
        {
			if($('reservation_room_id').options[i].selected == true)
            {
				$('reservation_room_id').value = $('reservation_room_id').options[i].value;
			}
		}
	}
    else
    {
		$('reservation_room_id').value = 0;
		if($('room')) $('room').checked = false;
		jQuery('#pay_with_room').attr('checked','');
		//$('receiver_name').value = '';
	}
}
function update_traveller(obj){
	for(var j in room_array){
		if(room_array[j]['id']==obj.value){
			$('reservation_traveller_id').value = j;
			jQuery('#pay_with_room').show();
			break;
		}else{
			$('reservation_traveller_id').value = 0;
			jQuery('#pay_with_room').attr('checked','');
			jQuery('#pay_with_room').hide();
		}
	}
}
/*------------ CheckIn -------------------------------------*/
function update_total_checkin(obj)
{
	index = obj.id.replace('product_id_','');
	var product = get_product(obj.value);
	var total = 0;
	var discount = 0;
	if(product && is_numeric($('quantity_'+index).value) && is_numeric($('quantity_discount_'+index).value) && is_numeric($('discount_'+index).value))
	{
		total = (to_numeric($('quantity_'+index).value)-to_numeric($('quantity_discount_'+index).value))*to_numeric($('price_'+index).value);
		discount = total*to_numeric($('discount_'+index).value)/100;
		$('total_'+index).value = number_format(total-discount);
		
	}
}

function update_product_checkin(obj)
{
	index = obj.id.replace('product_id_','');	
	var product = get_product(obj.value);
	if(product)
	{
		$('product_id_'+index).value = product.id;
		$('name_'+index).value = product.name;
		$('unit_'+index).value = product.unit;
		$('quantity_'+index).value = 1;
		$('quantity_discount_'+index).value = 0;
		$('discount_'+index).value = 0;
		$('price_'+index).value = number_format(roundNumber(product.price,2));
		update_total_checkin(obj);
	}
}
function calculate_checkin()
{
	var discount=0;
	for(var i=101; i<=input_count; i++)
	{
		if($('quantity_'+i) && $('quantity_discount_'+i) && $('price_'+i))
		{
			var total = (to_numeric($('quantity_'+i).value)-to_numeric($('quantity_discount_'+i).value))*to_numeric($('price_'+i).value);
			var discnt = total*to_numeric($('discount_'+i).value)/100;
			discount = discount+discnt;
		}
	}
	$('discount').value = discount==0?'0,00':number_format(discount);
	var total=0;
	for(var i=101; i<=input_count; i++){
		if($('total_'+i)){
			total = total+to_numeric($('total_'+i).value);
		}
	}
	total_before_service = total;
	$('summary').innerHTML = number_format(total+discount);
	bar_fee = roundNumber(total*$('bar_fee_rate').value/100,2);
	$('bar_fee').value = number_format(bar_fee);
	total = parseFloat(total) + roundNumber(parseFloat(total)*$('bar_fee_rate').value/100,2);
	$('total_before_tax').value = number_format(roundNumber(total,2));
	tax_rate = parseFloat(($('tax_rate').value?$('tax_rate').value:0));
	if(tax_rate>0){
		tax = roundNumber((parseFloat(total_before_service)+parseFloat(bar_fee))*tax_rate/100,2);
	}else{
		tax = roundNumber(parseFloat(total_before_service)*tax_rate/100,2);	
	}
	$('tax').value= number_format(tax);
	total = parseFloat(total) + tax;// - discount
	$('sum_total').value = number_format(roundNumber(total,2));
	$('remain_paid').innerHTML = number_format(roundNumber(total - to_numeric($('prepaid').value),2));
}
function show_payment_type(value){
	if(value==2){
		$('payment_type').style.display='';
	} else {
		$('payment_type').style.display='none';
	}
}
function check_barfee(){
	if($('cb_barfee').checked){
		$('bar_fee_rate').value=5;
	} else {
		$('bar_fee_rate').value=0;	
	}
}
function calculate_rate(value){
	
}
function update_discount_rate(value){
	for(id in product_items){
		product = product_items[id];
		if(product && product.category_id == value){
			for(var i=101;i<=input_count;i++){
				if($('product_id_'+i) && $('product_id_'+i).value == product.product_id){
					if($('discount_value_'+value).value){
						$('discount_'+i).value = $('discount_value_'+value).value;
					} else {
						$('discount_'+i).value = 0;
					}
					update_total_checkin($('product_id_'+i));
					calculate_checkin();
				}
			}
			
		}
	}
}