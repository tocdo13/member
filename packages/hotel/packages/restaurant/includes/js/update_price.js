// JavaScript Document
function update_table(obj)
{
	if(obj.value)
	{
		obj.num_people_input.value = table_num_people[obj.value];
		obj.code_input.value = table_code[obj.value];
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
		obj.price_input.value = number_format(roundNumber(product.price,2));
		update_total(obj);
	}
	else
	{
		obj.name_input.value = '';
		obj.unit_input.value = '';
		obj.quantity_input.value = 0;
		obj.price_input.value = 0;
		update_total(obj)
	}
}

function update_total(obj)
{
	var product = get_product(obj.value);
	if(product && is_numeric(obj.quantity_input.value))
	{
		obj.total_input.value = number_format(roundNumber(to_numeric(obj.quantity_input.value)*stringToNumber(product.price),2));
	}
	else
	{
		obj.total_input.value = 0;
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
	
	total_input = document.getElementsByName('product__total[]');
	
	var total = 0;
	for(var i=1; i<total_input.length; i++)
	{
		total = roundNumber(total + to_numeric(total_input[i].value),2);
	}
	$('summary').innerHTML = number_format(roundNumber(total,2));
	//$('bar_fee').innerHTML = number_format(roundNumber(total*5/100,2));
	total = total;
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
function update_room(obj)
{
	var room = get_room(obj.value);
	if(room)
	{
		$('reservation_id').value = room.id;
		$('agent_name').value = room['name'];
	}
	else
	{
		$('reservation_id').value = '';
		$('agent_name').value = '';		
	}
}
