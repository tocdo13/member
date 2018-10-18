current_edit_row = false;
define_field_actions = {};
function init_fields(index)
{
	for(i in table_fields)
	{
		if(i && $(i+'_'+index))
		{
			var temp = $(i+'_'+index).innerHTML;
			if(typeof(define_field_actions[i])!='undefined' && define_field_actions[i])
			{
				var action = ' onfocus="$(\'suggest_box\').style.display=\'none\';this.last_value=this.value;this.parentNode.className=\'selected_input\';start_suggest = false;last_suggest = false;next_suggest = false;" onblur="this.parentNode.className=\'normal-input\';"'+define_field_actions[i].replace(/#index#/g,index);
			}
			else
			{
				var action = ' onfocus="$(\'suggest_box\').style.display=\'none\';this.last_value=this.value;this.parentNode.className=\'selected_input\';start_suggest = false;last_suggest = false;next_suggest = false;" onblur="this.parentNode.className=\'normal-input\';'+((table_fields[i]=='suggest')?'$(\'suggest_box\').style.display=\'none\';':'')+'"';
			}
			$(i+'_'+index).className = 'normal-input';
			switch(table_fields[i])
			{
			case 'select':
			if(define_select_fields[i])
			{
				$(i+'_'+index).innerHTML='<select name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input" '+action+'>'+define_select_fields[i]+'</select>';
			}
			else
			{
				$(i+'_'+index).innerHTML='<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input" '+action+'>';
			}
			break;
			case 'suggest':
			if(define_suggest_fields[i])
			{
				$(i+'_'+index).innerHTML='<input name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input" '+action+' onkeyup="update_suggest_box(this,\''+i+'\');">';
				$('records_'+index+'_'+i).define_index = i;
				if(window.addEventListener){
					$(i+'_'+index).addEventListener('onkeydown',select_suggest,false);
				}else{
					$(i+'_'+index).attachEvent('onkeydown',select_suggest);
				}
			}
			else
			{
				$(i+'_'+index).innerHTML='<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input" '+action+'>';
			}
			break;
			case 'currency':
				$(i+'_'+index).innerHTML='<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=36 && event.keyCode!=13 && event.keyCode!=45)event.returnValue=false;" '+action+'>';
				break;
			case 'int':
				$(i+'_'+index).innerHTML='<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=13)event.returnValue=false;" '+action+'>';
				break;
			case 'float':
				$(i+'_'+index).innerHTML='<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=13)event.returnValue=false;" '+action+'>';
				break;
			case 'checkbox':
				$(i+'_'+index).innerHTML='<input type="checkbox" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" value="1" '+action+'>';
				break;
			default:
				$(i+'_'+index).innerHTML='<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input" '+action+'>';
				break;
			}
			if(table_fields[i]=='date')
			{
				if(window.addEventListener){	
					$('records_'+index+'_'+i).addEventListener('onkeydown',onkeydown_change_date,false);
				}else{
					$('records_'+index+'_'+i).attachEvent('onkeydown',onkeydown_change_date);
				}
			}
			if(table_fields[i]=='code'||table_fields[i]=='int'||table_fields[i]=='float')
			{
				$('records_'+index+'_'+i).attachEvent('onkeydown',onkeydown_change_number);
			}
			if(table_fields[i]!='checkbox')
			{
				$('records_'+index+'_'+i).value = temp;
			}
			else
			{
				if(temp>0)
				{
					$('records_'+index+'_'+i).checked = true;
				}
			}
		}
	}
}
function delete_row(index)
{
	$('main_table').deleteRow($('new_row_'+index).rowIndex);
	current_edit_row = false;
}
function add_row(init_values,dont_move_focus)
{
	if(current_edit_row)
	{
		disable_select_input(current_edit_row);
	}
	
	var tr = $('main_table').insertRow(-1);
	rows['new_row_'+new_index] = 1;
	tr.id = 'new_row_'+new_index;
	tr.onclick=function(){edit_row(this,this.id);}
	current_edit_row = 'new_row_'+new_index;
	var td = tr.insertCell(-1);
	td.innerHTML = '<a  onclick="delete_row('+new_index+');"><img tabIndex="-1" src="packages/core/skins/default/images/buttons/delete.gif"></a>';
	var field_index = 0;
	for(var i in table_fields)
	{
		if(i)
		{
			
			var td = tr.insertCell(-1);
			if(typeof(define_field_actions[i])!='undefined' && define_field_actions[i])
			{
				var action = ' onfocus="$(\'suggest_box\').style.display=\'none\';this.last_value=this.value;this.parentNode.className=\'selected_input\';start_suggest = false;last_suggest = false;next_suggest = false;" onblur="this.parentNode.className=\'normal-input\';"'+define_field_actions[i].replace(/#index#/g,'new_row_'+new_index);
			}
			else
			{
				var action = ' onfocus="$(\'suggest_box\').style.display=\'none\';this.last_value=this.value;this.parentNode.className=\'selected_input\';start_suggest = false;last_suggest = false;next_suggest = false;" onblur="this.parentNode.className=\'normal-input\';'+((table_fields[i]=='suggest')?'$(\'suggest_box\').style.display=\'none\';':'')+'"';
			}
			switch(table_fields[i])
			{
			case 'select':
				if(define_select_fields[i])
				{
					td.innerHTML = '<div class="normal-input"><select  name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input"'+action+'>'+define_select_fields[i]+'</select></div>';
				}
				else
				{
					td.innerHTML = '<div class="normal-input"><input type="text" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input"'+action+'></div>';
				}
			break;
			case 'suggest':
			if(define_suggest_fields[i])
			{
				td.innerHTML = '<div class="normal-input"><input name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input" '+action+' onkeyup="update_suggest_box(this,\''+i+'\');"></div>';
				$('records_new_row_'+new_index+'_'+i).define_index = i;
				if(window.addEventListener){
					$('records_new_row_'+new_index+'_'+i).addEventListener('onkeydown',select_suggest,false);
				}else{
					$('records_new_row_'+new_index+'_'+i).attachEvent('onkeydown',select_suggest);
				}
			}
			else
			{
				$(i+'_'+new_index).innerHTML='<div class="normal-input"><input type="text" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input" '+action+'></div>';
			}
			break;
			case 'currency':
				td.innerHTML = '<div class="normal-input"><input type="text" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=36 && event.keyCode!=13 && event.keyCode!=45)event.returnValue=false;"'+action+'></div>';
				break;
			case 'int':
				td.innerHTML = '<div class="normal-input"><input type="text" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=13)event.returnValue=false;"'+action+'></div>';
				break;
			case 'float':
				td.innerHTML = '<div class="normal-input"><input type="text" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=13)event.returnValue=false;"'+action+'></div>';
				break;
			case 'checkbox':
				td.innerHTML = '<div class="normal-input"><input type="checkbox" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input" value="1"'+action+'></div>';
				break;
			default:
				td.innerHTML = '<div class="normal-input"><input type="text" name="records[new_row_'+new_index+']['+i+']" id="records_new_row_'+new_index+'_'+i+'" class="normal-input"'+action+'></div>';
				break;
			}
			if(table_fields[i]=='date')
			{
				if(window.addEventListener){	
					$('records_new_row_'+new_index+'_'+i).addEventListener('onkeydown',onkeydown_change_date,false);
				}else{
					$('records_new_row_'+new_index+'_'+i).attachEvent('onkeydown',onkeydown_change_date);
				}
			}
			if(table_fields[i]=='code'||table_fields[i]=='int'||table_fields[i]=='float')
			{
				$('records_new_row_'+new_index+'_'+i).attachEvent('onkeydown',onkeydown_change_number);
			}
			$('records_new_row_'+new_index+'_'+i).value = init_values?init_values[field_index]:'';
			field_index++;
		}
	}
	if(!dont_move_focus)
	{
		for(var i in table_fields)
		{
			if(i)
			{
				$('records_new_row_'+new_index+'_'+i).focus();
				break;
			}
		}
	}
	//tr.style.backgroundColor = '#EEEEEE';
	new_index++;
}
rows = {};
function edit_row(tr,index,evt)
{
	if(tr['close_ledger'] && tr['close_ledger']==1)
	{
		return;
	}
	if(current_edit_row!=index)
	{
		if(current_edit_row)
		{
			disable_select_input(current_edit_row);
		}
		if(!rows[index])
		{	
			rows[index] = 1;
			//if(!evt)if(event)evt=event;
			init_fields(index);
/*			if(evt && evt!=-1)
			{
				window.setTimeout('var obj=document.elementFromPoint('+evt.clientX+','+evt.clientY+');if(!obj.disabled){obj.focus();obj.fireEvent(\'onclick\');}',10);
			}
*/		}
		else
		{
			enable_select_input(index);
		}
		//tr_color = '#EEEEEE';
		current_edit_row = index;
	}
	
}
function init_search_row()
{
	var tr = $('main_table').insertRow(1);
	var td = tr.insertCell(-1);
	td.innerHTML = '<input type="image" src="packages/core/skins/default/images/buttons/search.gif"/>';
	for(var i in table_fields)
	{
		if(i)
		{
			var td = tr.insertCell(-1);
			switch(table_fields[i])
			{
			case 'select':
				if(define_select_fields[i])
				{
					td.innerHTML = '<select  name="search_by_'+i+'" id="search_by_'+i+'" class="normal-input" onchange="this.form.submit();"><option value=""></option>'+define_select_fields[i]+'</select>';
				}
				else
				{
					td.innerHTML = '<input  type="text" name="search_by_'+i+'" id="search_by_'+i+'" class="normal-input">';
				}
				break;
			case 'checkbox':
				td.innerHTML = '<input  type="checkbox" name="search_by_'+i+'" id="search_by_'+i+'" class="normal-input" value="1">';
				break;
			default:
				td.innerHTML = '<input  type="text" name="search_by_'+i+'" id="search_by_'+i+'" class="normal-input">';
				break;
			}
		}
	}
	tr.style.backgroundColor = '#FFFFCC';
	new_index++;
}
function change_to_select(input,i,index)
{
	if(typeof(define_field_actions[i])!='undefined' && define_field_actions[i])
	{
		var action = define_field_actions[i].replace(/#index#/g,index);
	}
	else
	{
		var action = '';
	}
	div = input.parentNode;
	var value = $('records_'+index+'_'+i).value;
	div.innerHTML = '<select name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input"'+action+'>'+define_select_fields[i]+'</select>'
	$('records_'+index+'_'+i).value = value;
}
function change_to_input(input,i,index)
{
	if(typeof(define_field_actions[i])!='undefined' && define_field_actions[i])
	{
		var action = define_field_actions[i].replace(/#index#/g,index);
	}
	else
	{
		var action = '';
	}
	div = input.parentNode;
	var value = $('records_'+index+'_'+i).value;
	div.innerHTML = '<input type="text" name="records['+index+']['+i+']" id="records_'+index+'_'+i+'" class="normal-input"'+action+'>';
	$('records_'+index+'_'+i).value = value;
}
function enable_select_input(index)
{
	for(i in table_fields)
	{
		if(table_fields[i]=='select')
		{
			change_to_select($('records_'+index+'_'+i),i,index);
		}
	}
}
function disable_select_input(index)
{
	for(i in table_fields)
	{
		if(table_fields[i]=='select')
		{
			change_to_input($('records_'+index+'_'+i),i,index);
		}
	}
}
function get_price(value)
{
	return parseFloat(value.replace('$',''));
}
function get_currency(value)
{
	return value.search('$')?'$':'';
}
start_suggest = false;
last_suggest = false;
next_suggest = false;
max_suggest = 50;
function update_suggest_box(input,index)
{
	var items = define_suggest_fields[index];
	
	var count = 0;
	var st = '<div style="height:100px;width:100px;overflow-y:scroll;"><table cellspacing="0">';
	var value = input.value;
	var save_last_suggest = last_suggest;
	var save_next_suggest = next_suggest;
	last_suggest = false;
	next_suggest = false;
	for(var i in items)
	{
		if(count>max_suggest)
		{
			break;
		}
		
		
		if(count>0 || value=='' || (i && i.toLowerCase().indexOf(value.toLowerCase())!=-1))
		{
			if(count==0)
			{
				if(start_suggest==i && $('suggest_box').style.display!='none')
				{
					last_suggest = save_last_suggest;
					next_suggest = save_next_suggest;
					return;
				}
				else
				{
					start_suggest = i;
				}
			}
			if(count == 1)
			{
				next_suggest = i;
			}
			st += '<tr'+((count==0)?' style="background-color:#FFFFCC"':'')+' onmouseover="this.style.backgroundColor=\'#FFFFCC\'" onmouseout="this.style.backgroundColor=\''+((count==0)?'#FFFFCC':'white')+'\';"><td nowrap><a  onclick="$(\''+input.id+'\').value=\''+i.toString().replace('\'','').replace('"','')+'\';update_suggest_box($(\''+input.id+'\'),\''+i.toString().replace('\'','').replace('"','')+'\');if(document.all)event.returnValue = false;else return false;">'+items[i]+'</a></td></tr>';
			count++;
		}
		if(count==0)
		{
			last_suggest = i;
		}
	}
	st+='</table></div>';
	$('suggest_box').innerHTML = st;
	
	if($('suggest_box').style.display == 'none')
	{
		var pos = findPos(input);
		var bound_pos = findPos($('center_region'));
		$('suggest_box').style.left = pos[1]-bound_pos[1];
		$('suggest_box').style.top = pos[0]-bound_pos[0]+getElementHeight(input.id);
		$('suggest_box').style.display='';
	}
	
	//if(items[value])
	{
		//input.title = items[value];
	}
}
function get_column_value(td)
{
	if(td.firstChild.firstChild&&td.firstChild.firstChild.tagName)
	{
		return td.firstChild.firstChild.disabled?'':td.firstChild.firstChild.value;
	}
	else
	{
		return td.firstChild.innerHTML;
	}
}
function copy_row(dest,src)
{
	if(src)
	{
		var td = src.firstChild;
		var dest_td = dest.firstChild;
		while(td && dest_td)
		{
			if(typeof(dest_td.firstChild.firstChild.value) != 'undefined')
			{
				dest_td.firstChild.firstChild.value = get_column_value(td);
			}
			td=td.nextSibling;
			dest_td=dest_td.nextSibling;
		}
	}
}
function copy_row_position(dest,src)
{
	while(src.previousSibling)
	{
		dest = dest.nextSibling;
		src = src.previousSibling;
	}
	/*if(dest.firstChild.firstChild&&dest.firstChild.firstChild.tagName)
	{
		dest.firstChild.firstChild.focus();
	}
	else*/
	{
		var indexes=dest.firstChild.id.split('_');
		edit_row(dest.parentNode,indexes[indexes.length-1],-1);
		dest.firstChild.firstChild.focus();
	}
}
function select_suggest(evt){
	if(evt && !evt.ctrlKey && (evt.keyCode==38 || evt.keyCode==40))
	{
		var target=(evt.target) ? evt.target : evt.srcElement;
		if(evt.keyCode==40 && next_suggest)
		{
			target.value=next_suggest;
			target.fireEvent('onchange');
			update_suggest_box(target,target.define_index);
		}
		else
		if(evt.keyCode==38 && last_suggest)
		{
			target.value=last_suggest;
			target.fireEvent('onchange');
			update_suggest_box(target,target.define_index);
		}
	}
}
function onkeydown_change_date(evt)
{
	if(evt && !evt.ctrlKey && (evt.keyCode==38 || evt.keyCode==40))
	{
		var target=(evt.target) ? evt.target : evt.srcElement;
		var dates = target.value.split('/');
		if(dates.length==3)
		{
			var date = new Date(dates[2],dates[1],dates[0]);
			//date.setTime(date.getTime()+3600*24);
			date.setDate(date.getDate()+((evt.keyCode==38)?-1:1));
			target.value = date.getDate()+'/'+date.getMonth()+'/'+date.getFullYear();
		}
	}
}
function onkeydown_change_number(evt)
{
	if(evt && !evt.ctrlKey && (evt.keyCode==38 || evt.keyCode==40))
	{
		var target=(evt.target) ? evt.target : evt.srcElement;
		var value = target.value;
		if(value)
		{
			var pos = value.search(/\d/);
			if(pos!=-1)
			{
				var pos1 = value.search(/[1-9]/);
				if(pos1==-1)pos1=pos;
				var number = parseInt(value.substr(pos1));
				
				number = number+((evt.keyCode==38)?-1:1);
				if(number>=0)
				{
					while((number+' ').length<value.length-pos+1)
					{
						number = '0'+number;
					}
					target.value = value.substr(0,pos)+number;
				}
			}
		}
	}
}

function default_onkeydown(evt)
{
	if(!evt)evt=event;
	if(evt.ctrlKey)
	{
		switch(evt.keyCode)
		{
		case 32:
			var target=(evt.target) ? evt.target : evt.srcElement;
			if(target && target.id && target.id.search('records')==0)
			{
				copy_row(target.parentNode.parentNode.parentNode,target.parentNode.parentNode.parentNode.previousSibling);
				return true;
			}
			break;
		case 45:
			add_row();
			return true;
		case 38:
			var target=(evt.target) ? evt.target : evt.srcElement;
			if(target && target.id && target.id.search('records')==0 && target.parentNode.parentNode.parentNode.previousSibling && target.parentNode.parentNode.parentNode.previousSibling.previousSibling)
			{
				copy_row_position(target.parentNode.parentNode.parentNode.previousSibling.firstChild,target.parentNode.parentNode);
				return true;
			}
			break;
		case 40:
			var target=(evt.target) ? evt.target : evt.srcElement;
			if(target && target.id && target.id.search('records')==0 && target.parentNode.parentNode.parentNode.nextSibling)
			{
				copy_row_position(target.parentNode.parentNode.parentNode.nextSibling.firstChild,target.parentNode.parentNode);
				return true;
			}
			break;
		case 35:
			var target=(evt.target) ? evt.target : evt.srcElement;
			
			if(target && target.id && target.id.search('records')==0)
			{
				var tr = $('main_table').firstChild.nextSibling.lastChild;
				if(tr)
				{
					copy_row_position(tr.firstChild,target.parentNode.parentNode);
					return true;
				}
			}
			
			break;
		case 36:
			var target=(evt.target) ? evt.target : evt.srcElement;
			
			if(target && target.id && target.id.search('records')==0)
			{
				var tr = $('main_table').firstChild.nextSibling.firstChild.nextSibling;
				if(tr)
				{
					copy_row_position(tr.firstChild,target.parentNode.parentNode);
				}
				return true;
			}
			
			break;
		case 37:
			var target=(evt.target) ? evt.target : evt.srcElement;
			if(target && target.id && target.id.search('records')==0)
			{
				var td = target.parentNode.parentNode.previousSibling;
				if(td && td.firstChild && td.firstChild.firstChild)
				{
					td.firstChild.firstChild.focus();
				}
				return true;
			}
			
			break;
		case 39:
			var target=(evt.target) ? evt.target : evt.srcElement;
			if(target && target.id && target.id.search('records')==0)
			{
				var td = target.parentNode.parentNode.nextSibling;
				if(td && td.firstChild && td.firstChild.firstChild)
				{
					td.firstChild.firstChild.focus();
				}
				return true;
			}
			
			break;
		case 34:
			var target=(evt.target) ? evt.target : evt.srcElement;
			
			if(target && target.id && target.id.search('records')==0)
			{
				var tr = target.parentNode.parentNode.parentNode;
				var i=0;
				while(tr.nextSibling && i<10)
				{
					tr = tr.nextSibling;
					i++;
				}
				copy_row_position(tr.firstChild,target.parentNode.parentNode);
				return true;
			}
			
			break;
		case 33:
			var target=(evt.target) ? evt.target : evt.srcElement;
			
			if(target && target.id && target.id.search('records')==0)
			{
				var tr = target.parentNode.parentNode.parentNode;
				var search_tr = tr.parentNode.firstChild.nextSibling;
				var i=0;
				while(tr.previousSibling && tr!=search_tr && i<10)
				{
					tr = tr.previousSibling;
					i++;
				}
				copy_row_position(tr.firstChild,target.parentNode.parentNode);
				return true;
			}
			
			break;
		
		default:;
		}
	}
	else
	{
		switch(evt.keyCode)
		{
		case 8:
			var target=(evt.target) ? evt.target : evt.srcElement;
			if(!target||(target.tagName!='INPUT' && target.tagName!='TEXTAREA'))
			{
				return true;
			}
			break;
		case 27:
			return true;
			/*var target=(evt.target) ? evt.target : evt.srcElement;
			if(target && target.last_value)
			{
				target.value = target.last_value;
				return true;
			}*/
			break;
		}
	}
}
function field_check_error(name,value,type)
{
	switch(type)
	{
	case 'int':
		if(value.match(/[^0-9,]/))
		{
			return false;
		}
		break;
		
		
	case 'float':
		if(value.match(/[^0-9,.]/))
		{
			return false;
		}
		break;
	case 'currency':
		if(value.match(/[^0-9,.$]/))
		{
			return false;
		}
		break;
	case 'suggest':
		if(!define_suggest_fields[name][value])
		{
			return false;
		}
		break;
	case 'date':
		if(!value.match(/^\d{2,2}\/\d{2,2}\/\d{4,4}$/))
		{
			return false;
		}
		break;
	case 'code':
		if(value.match(/[^a-zA-Z0-9_]/))
		{
			return false;
		}
		break;
	}
	return true;
}
new_index = 1;