current_room=0;
/*function update_room_info(id, name)
{
	$('room_info_'+name).innerText = rooms_info[id][name];
}*/
room_classes = {};
function select_room(id, form)
{
	var rooms = Array();
	if(form.room_ids.value != '')
	{
		rooms = form.room_ids.value.split(',');
	}
	var e = (window.event) ? window.event : evt;
	if(e.shiftKey && rooms.length>0)
	{
		var i = 0, j=0;
		for(i in rooms_info)
		{
			if(rooms_info[i].id==rooms[rooms.length-1])
			{
				break;
			}
		}
		for(j in rooms_info)
		{
			if(rooms_info[j].id==id)
			{
				break;
			}
		}
		var start = Math.min(i,j);
		var end = Math.max(i,j);
		for(i = start;i<=end;i++)
		{
			if(typeof(rooms_info[i])!='undefined')
			{
				var k = rooms_info[i].id;
				var new_selection = true;
				for(j in rooms)
				{
					if(rooms[j]==k)
					{
						new_selection = false;
					}
				}
				if(new_selection && typeof($('room_'+k))!='undefined' && $('room_'+k)!=null)
				{
					room_classes[k] = $('room_'+k).className;
					$('room_'+k).className += " selected";
					rooms.push(k);
				}
			}
		}
	}
	else
	{
		if(!e.ctrlKey)
		{
			for(var i in rooms)
			{
				if(document.getElementById('room_'+rooms[i]))
				{
					document.getElementById('room_'+rooms[i]).className = room_classes[rooms[i]];
				}
			}
			rooms = Array();
		}
		var changed = false;
		for(var i in rooms)
		{
			if(rooms[i]==id)
			{
				document.getElementById('room_'+id).className = room_classes[id];
				rooms.splice(i,1);
				changed = true;
				break;
			}
		}
		if(!changed)
		{
			room_classes[id] = document.getElementById('room_'+id).className;
			document.getElementById('room_'+id).className += " selected";
			rooms.push(id);
		}
	}
	form.room_ids.value = rooms.join(',');
}
function check_date(time_in,time_out)
{
	var time_out=document.getElementById(time_out);
	var time_in=document.getElementById(time_in);
	var TIMEIN=time_in.value;
	var TIMEOUT=time_out.value;	
	var time_in_array=TIMEIN.split("/");
	var time_out_array=TIMEOUT.split("/");
	var time_in_day=parseInt(time_in_array[0],10);
	var time_in_month=parseInt(time_in_array[1],10);
	var time_in_year=parseInt(time_in_array[2],10);
	var time_out_day=parseInt(time_out_array[0],10);
	var time_out_month=parseInt(time_out_array[1],10);
	var time_out_year=parseInt(time_out_array[2],10);
	if(time_out_year<time_in_year)
	{
		document.getElementById('date_error_notice').innerHTML='[[.error.]]:&nbsp;[[.time_out_less_than_time_in.]]';
		return false;
	}
	else
	if(time_out_year==time_in_year && time_out_month<time_in_month)
	{
		document.getElementById('date_error_notice').innerHTML='[[.error.]]:&nbsp;[[.time_out_less_than_time_in.]]';
		return false;		
	}
	else
	if((time_out_year==time_in_year && time_out_month==time_in_month) && time_out_day<time_in_day)
	{
		document.getElementById('date_error_notice').innerHTML='[[.error.]]:&nbsp;[[.time_out_less_than_time_in.]]';
		return false;		
	}
	else
	{
		return true;
	}
	return true;
}
//phan cac ham` cho filter
var st = false;
timer1	= null;
var counter=100;
function fnDoWork_start(obj)
{

	obj.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 40;

}
function fnDoWork_end(obj)
{

	obj.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 100;
}
var start=false;
function doTrans(Obj,startImageSrc,endImageSrc) {
	start=true;
	Obj.filters[0].apply();
	if(start==false){
		Obj.src = startImageSrc;
		var start=true;
		//imgObjText.innerHTML = content1
	} else {
		Obj.src = endImageSrc;
		//imgObjText.innerHTML = content2
	}
	Obj.filters[0].play(1);
}
function return_true_color(Obj,true_color)
{
    Obj.src = true_color;			
}
function doTransCss(Obj,endImageSrc) {
	lastStartImageSrc = Obj.style.backgroundImage;
	Obj.filters[0].apply();
	Obj.style.backgroundImage = 'url('+endImageSrc+')';
	Obj.filters[0].play(1);
}
function endTransCss(Obj)
{
    Obj.style.backgroundImage = lastStartImageSrc;		
}
//--------di chuyen con tro menu---------------
px =  "px";
timer1	= null;
var decrease	= 0.1; 
var offset = 0;
var selected_id = null;

function getPosition(id) 
{ 
	endPos = document.getElementById(id).offsetTop;
}
function actionMenu(obj,steps) 
{
	if (document.getElementById) 
	{
		el = document.getElementById(obj) ;
	}
	el.xpos = el.offsetTop;
	if (el.xpos < endPos) 
	{
		clearTimeout(timer1);
	}
	else if (el.xpos >= endPos) 
	{
		clearTimeout(timer1);
	}
		distance = endPos - el.xpos + offset;
		steps = distance*decrease; 
		el.xpos += steps;
		el.style.top = el.xpos+px ;
		timer1= setTimeout("actionMenu('" + obj + "')",25);
}