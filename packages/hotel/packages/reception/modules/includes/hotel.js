room_classes = {};
function select_room(id, form)
{
	var rooms;
	if(form.room_ids.value != '')
	{
		rooms = form.room_ids.value.split(',');
	}
	else
	{
		rooms = Array();
	}
	var changed = false;
	for(var i in rooms)
	{
		if(rooms[i]==id)
		{
			document.getElementById('room_'+id).className = room_classes[id];
			//document.getElementById('room_'+id).className = "room";
			rooms.splice(i,1);
			changed = true;
			break;
		}
	}
	if(!changed)
	{
		room_classes[id] = document.getElementById('room_'+id).className;
		document.getElementById('room_'+id).className = "room_select";
		rooms.push(id);
	}
	form.room_ids.value = rooms.join(',');
}
function check_date(time_in,time_out,error_message)
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
		document.getElementById('date_error_notice').innerHTML=error_message;
		return false;
	}
	else
	if(time_out_year==time_in_year && time_out_month<time_in_month)
	{
		document.getElementById('date_error_notice').innerHTML=error_message;
		return false;
	}
	else
	if((time_out_year==time_in_year && time_out_month==time_in_month) && time_out_day<time_in_day)
	{
		document.getElementById('date_error_notice').innerHTML=error_message;
		return false;
	}
	else
	{
		return true;
	}
}
//phan cac ham` cho filter
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
}// JavaScript Document