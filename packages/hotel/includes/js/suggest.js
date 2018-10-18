start_suggest = false;
last_suggest = false;
next_suggest = false;
max_suggest = 10;

function update_suggest_box(input,object_type_list)
{
	var items = object_type_list;
	
	var count = 0;
	var st = '<table cellspacing="0">';
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
		if(count>0 || value=='' || (i && i.toLowerCase().indexOf(value.toLowerCase())==0))
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
			st += '<tr'+((count==0)?' style="background-color:#FFFFCC"':'')+'><td>'+items[i]+'</td></tr>';
			count++;
		}
		if(count==0)
		{
			last_suggest = i;
		}
	}
	st+='</table>';
	$('suggest_box').innerHTML = st;
	
	if($('suggest_box').style.display == 'none')
	{
		var pos = findPos(input);
		if($('_center_region'))
		{
			var bound_pos = findPos($('_center_region'));
		}
		else
		{
			var bound_pos = [0,0];
		}
		$('suggest_box').style.left = pos[1]-bound_pos[1]+100+'px';
		$('suggest_box').style.top = pos[0]-bound_pos[0]+getElementHeight(input.id)-20+'px';
		$('suggest_box').style.display='';
	}
	if(items[value])
	{
		input.title = items[value];
	}
}

function select_suggest(evt,object_type_list){
	if(evt && !evt.ctrlKey && (evt.keyCode==38 || evt.keyCode==40))
	{
		var target=(evt.target) ? evt.target : evt.srcElement;
		if(evt.keyCode==40 && next_suggest)
		{
			target.value=next_suggest;
			target.fireEvent('onchange');
			update_suggest_box(target,object_type_list);
		}
		else
		if(evt.keyCode==38 && last_suggest)
		{
			target.value=last_suggest;
			target.fireEvent('onchange');
			update_suggest_box(target,object_type_list);
		}
	}
}