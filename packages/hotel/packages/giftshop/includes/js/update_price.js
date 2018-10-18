// JavaScript Document
function update_table(obj)
{
	if(obj.value)
	{
		obj.num_people_input.value=table_num_people[obj.value];
		obj.code_input.value=table_code[obj.value];
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
	if(product)
	{
		obj.id_input.value = product.id;
		obj.name_input.value = product.name;
		obj.unit_input.value = product.unit;
		obj.quantity_input.value = 1;
		obj.quantity_discount_input.value = 0;
		obj.price_input.value = number_format(product.price);
		obj.discount_input.value = "0";
		update_total(obj);
	}
}

function update_total(obj)
{
	var product = get_product(obj.value);
	var total = 0;
	var discount =0;
	if(product && is_numeric(obj.quantity_input.value) && is_numeric(obj.quantity_discount_input.value) && is_numeric(obj.discount_input.value))
	{
		total = (to_numeric(obj.quantity_input.value)-to_numeric(obj.quantity_discount_input.value))*to_numeric(obj.price_input.value);
		discount = total*to_numeric(obj.discount_input.value)/100;
		obj.total_input.value = number_format(total-discount);		
	}
}
function exchange(c)
{
	c1=c.split(".");
	return parseInt(c1[0])*1000+parseInt(c1[1]);
}
function calculate()
{
	function exchange(c)
	{
		c1=c.split(".");
		/*c2=c1[0].split(",");*/
		return parseInt(c1[0])*1000+parseInt(c1[1]);
	}
	
	total_input = document.getElementsByName('product__total[]');
	
	var total=0;
	for(var i=1; i<total_input.length; i++)
	{
		total = roundNumber(total + to_numeric(total_input[i].value),2);
	}
	$('sumary').value = number_format(roundNumber(total,2));
	if($('tax_rate').value)
	{
		tax_rate = parseFloat($('tax_rate').value);
	}
	else
	{
		tax_rate = 0;	
	}
	$('tax').value = number_format(roundNumber(total*tax_rate/100,2));
	total = total + roundNumber(total*tax_rate/100,2);
	$('sum_total').value = number_format(roundNumber(total,2));
}

function get_room(room_id)
{
	if(typeof room_array[room_id]!= 'undefined')
	{
		return room_array[room_id];
	}
	return false;
}
function update_room(obj)
{
	var room = get_room(obj.value);
	if(room)
	{
		$('reservation_id').value = room.id;
	}
	else
	{
		$('reservation_id').value = '';
	}
}
function check_barfee()
{
	if($('cb_barfee').checked)
	{
		$('bar_fee_rate').value=5;
	} 
	else
	{
		$('bar_fee_rate').value=0;	
	}
}
