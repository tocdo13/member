current_row = 0,current_column=0;
var number_column = 0,number_row = 0;
function reservation_add_row(button)
{
	number_row++;
	var table = button.parentNode.parentNode.parentNode.parentNode;
	var sample_tr = button.parentNode.parentNode;
	if(sample_tr.parentNode.tagName == 'TBODY')
	{
		var index = 0;
		while(index < table.tBodies[0].childNodes.length)
		{
			if(table.tBodies[0].childNodes[index] == sample_tr)
			{
				break;
			}
			index ++;
		}
		var tr = table.tBodies[0].insertRow(index);
	}
	else
	{
		var tr = table.tBodies[0].insertRow();
	}
	tr.row_index = number_row;
	for(var i=0;i<sample_tr.childNodes.length;i++)
	{
		var td=tr.insertCell(tr.childNodes.length);
		if(i==sample_tr.childNodes.length-1)
		{
			td.innerHTML = '<span onclick="reservation_delete_row(this);"><img  title="[[.delete_row.]]" alt="[[.delete_row.]]" src="skins/default/images/icon/delete_row.gif"></span>\
				<span onclick="reservation_add_row(this);"><img title="[[.insert_row_above.]]" alt="[[.insert_row_above.]]" src="skins/default/images/icon/insert_row_above.gif"></span>';
		}
		else
		if(i==0)
		{
			td.innerHTML = '<input type="text" style="width:100%" name="reduce_date['+number_row+']" id="reduce_date_'+number_row+'">';
		}
		else
		{
			if(typeof(sample_tr.childNodes[i].column_index)!='undefined')
			{
				td.column_index = sample_tr.childNodes[i].column_index;
			}
			else
			{
				td.column_index = '';
			}
			td.innerHTML = reservation_cell_html(number_row,sample_tr.childNodes[i].column_index);
		}
	}
}
function reservation_add_column(button)
{
	number_column ++;
	var table = button.parentNode.parentNode.parentNode.parentNode;
	var index = 0;
	while(index < button.parentNode.parentNode.childNodes.length)
	{
		if(button.parentNode.parentNode.childNodes[index] == button.parentNode)
		{
			break;
		}
		index ++;
	}
	var tbody = table.firstChild;
	while(tbody)
	{
		var tr = tbody.firstChild;
		while(tr)
		{
			var td = tr.insertCell(index);
			td.column_index = number_column;
			if(tbody.tagName=='TFOOT')
			{
				td.innerHTML = '<span onclick="reservation_delete_column(this);"><img title="[[.delete_column.]]" alt="[[.delete_column.]]" src="skins/default/images/icon/delete_column.gif"/></span>\
				<span onclick="reservation_add_column(this);"><img title="[[.insert_column_to_the_left.]]" alt="[[.insert_column_to_the_left.]]" src="skins/default/images/icon/insert_column_to_the_left.gif" ></span>';
			}
			else
			if(tbody.tagName == 'THEAD')
			{
				td.innerHTML = '<input type="text" style="width:100%" name="reduce_room['+number_column+']" id="reduce_room_'+number_column+'">';
			}
			else
			{
				if(typeof(tr.row_index)!='undefined')
				{
					td.innerHTML = reservation_cell_html(tr.row_index,number_column);
				}
				else
				{
					td.innerHTML = '&nbsp;';
				}
			}
			tr = tr.nextSibling;
		}
		tbody = tbody.nextSibling;
	}
}
function reservation_delete_row(button)
{
	var tbody = button.parentNode.parentNode.parentNode;
	tbody.removeChild(button.parentNode.parentNode);
}
function reservation_delete_column(button)
{
	var td = button.parentNode;
	var tr = button.parentNode.parentNode;
	var index = 0;
	while(tr.childNodes[index] != td && index<tr.childNodes.length)
	{
		index++;
	}
	var table = button.parentNode.parentNode.parentNode.parentNode;
	var tbody = table.firstChild;
	while(tbody)
	{
		var tr = tbody.firstChild;
		while(tr)
		{
			tr.deleteCell(index);
			tr = tr.nextSibling;
		}
		tbody = tbody.nextSibling;
	}
}
function reservation_cell_html(i,j)
{
	return '<input type="text" style="width:100%" name="reduce_price['+i+']['+j+']" id="reduce_price_'+i+'_'+j+'" onfocus="current_row='+i+';current_column='+j+';" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;">'
}
function init_table(columns, rows, data)
{
	for(var i in rows)
	{
		reservation_add_row($('reservation_add_row'));
	}
	for(var j in columns)
	{
		reservation_add_column($('reservation_add_column'));
	}
	var t_row_index = 0;
	for(var i in rows)
	{
		t_row_index++;
		$('reduce_date_'+t_row_index).value = rows[i];
		var t_col_index = 0;
		for(var j in columns)
		{
			t_col_index ++;
			$('reduce_price_'+t_row_index+'_'+t_col_index).value = (typeof(data[i][j])!='undefined')?data[i][j]:0;
		}
	}
	var t_col_index = 0;
	for(var j in columns)
	{
		t_col_index ++;
		$('reduce_room_'+t_col_index).value = columns[j];
	}
}
