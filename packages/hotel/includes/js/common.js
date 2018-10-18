function echo(st)
{
	document.write(st);
}
function $(id)
{
	return document.getElementById(id);
}
function category_display(button_obj,obj,name)
{
	if(obj.style.display == 'none')
	{
		//delete_cookie(name,path,domain)
		obj.style.display = '';
		button_obj.innerHTML = '<img src="skins/default/images/tree_last.gif">';
	}
	else
	{
		obj.style.display = 'none';
		button_obj.innerHTML = '<img src="skins/default/images/tree_last_collapse.gif">';
	}
}
function display(obj)
{
	if(obj.style.display == 'none')
	{
		obj.style.display = '';
	}
	else
	{
		obj.style.display = 'none';
	}
}
function display_layer_at_mouse_position(layer_name, offsetX, offsetY)
{
	var object = document.getElementById(layer_name);
	if(event.x<document.body.clientWidth-object.style.width.replace('px',''))
	{
		object.style.left = event.x+document.body.scrollLeft+offsetX;
	}
	else
	{
		object.style.left = event.x+document.body.scrollLeft-object.clientWidth+offsetX;
	}
	
	if(event.y<document.body.clientHeight-object.style.height.replace('px',''))
	{
		object.style.top = event.y+document.body.scrollTop+offsetY;
	}
	else
	{
		object.style.top = event.y+document.body.scrollTop-object.clientHeight+offsetY;
	}
	object.style.display = '';
}
function switch_display_layer_at_mouse_position(layer_name, offsetX, offsetY)
{
	var object = document.getElementById(layer_name);
	if(object.style.display == 'none')
	{
		display_layer_at_mouse_position(layer_name, offsetX, offsetY);
	}
	else
	{
		object.style.display = 'none';
	}
}
function display_all_element(prefix, postfix_list, value)
{
	for(var i in postfix_list)
	{
		if(document.getElementById(prefix+postfix_list[i]))
		{
			document.getElementById(prefix+postfix_list[i]).style.display = value;
		}
	}
}

function URL()
{
	this.requests = Array();
	this.request_string = '';
	var hrefs = location.href.split('?');
	
	if(hrefs.length>1)
	{
		this.request_string = hrefs[1];
		var requests = hrefs[1].split('&');
		for(var i in requests)
		{
			var request = requests[i].split('=');
			if(request.length>1)
			{
				this.requests[request[0]] = request[1];
			}
		}
	}
}
URL.prototype.get = function(name,def)
{
	if(this.requests[name]!=null)
	{
		return this.requests[name];
	}
	else
	{
		if(def!=null)
		{
			return def;
		}
		else
		{
			return '';
		}
	}
}
url = new URL;
//=========================================================
//phan cac ham` cho filter
var st = false;
timer1	= null;
var counter=100;
function fnDoWork_start(obj)
{

	//obj.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 40;

}
function fnDoWork_end(obj)
{

	//obj.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 100;
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
function is_numeric(st)
{
	return st && (typeof(st)=='number' || (typeof(st.match)!='undefined' && !st.match(/[^0-9.,]/)));
}
function getElemValue(name)
{
	if(typeof($(name))!='undefined')
	{
		if(typeof($(name).value)!='undefined')
		{
			return $(name).value;
		}
		if(typeof($(name).innerHTML)!='undefined')
		{
			return $(name).innerHTML;
		}
	}
}
function to_numeric(st)
{
	if(st)
	{
		return (typeof(st)=='number' || (typeof(st.match)!='undefined' && !st.match(/[^0-9.,-]/)))?parseFloat(st.replace(/\,/g,'')):st;
	}
	else
	{
		return st;
	}
}
function to_vnnumeric(st)
{
	if(st)
	{
		st=st.toString();
		return (!st.match(/[^0-9.-]/))?st.replace(',','.'):st;
	}
	else
	{
		return st;
	}
}
function number_format(a) {
	var b,c,d,e,f,g,h,i,j;
	if(parseFloat(a)!=0)
	{
		a=a.toString();
		if(!a.match(/[^0-9.]/))
		{
			b=2;
			c='.';
			d=',';
			 a = Math.round(a * Math.pow(10, b)) / Math.pow(10, b);
			 
			 e = a.toString();
			 f = e.split('.');
			 if (!f[0]) {
			  f[0] = '0';
			 }
			 if (!f[1]) {
			  f[1] = '';
			 }
			 if (f[1].length < b) {
			  g = f[1];
			  for (i=f[1].length + 1; i <= b; i++) {
			   g += '0';
			  }
			  f[1] = g;
			 }
			 if(d != '' && f[0].length > 3) {
			  h = f[0];
			  f[0] = '';
			  for(j = 3; j < h.length; j+=3) {
			   i = h.slice(h.length - j, h.length - j + 3);
			   f[0] = d + i +  f[0] + '';
			  }
			  j = h.substr(0, (h.length % 3 == 0) ? 3 : (h.length % 3));
			  f[0] = j + f[0];
			 }
			 c = (b <= 0) ? '' : c;
			 return f[0] + c + f[1];
		}
		else
		{
				return a;
		}
	}
	else
	{
		return '0.00';
	}
	return st;
}
var oldInput=0;
function clickage(module_name,ids,arr_name,color_change)
{
	eval('array='+arr_name);
	if(!event.shiftKey)
	{
		oldInput=ids;
		$(module_name+'_check_'+ids).checked=$(module_name+'_check_'+ids).checked?1:0;
		$(module_name+'_tr_'+array[ids]).style.backgroundColor=$(module_name+'_check_'+ids).checked?color_change:'white';
		return false;
	}
	$(module_name+'_check_'+ids).checked=1;
	var low=Math.min(ids,oldInput);
	var high=Math.max(ids,oldInput)
	var uncheck=1;
	for(var i=low;i<=high;i++)
	{
		uncheck &= $(module_name+'_check_'+i).checked;
		$(module_name+'_check_'+i).checked=1;
	}
	if(uncheck)
	{
		for(i=low;i<=high;i++)
		{
			$(module_name+'_check_'+i).checked=0;
		}
	}
	change_bg(module_name,array,color_change);
	return true;
}
// Function change_bg: thay doi mau` cua? mot mang
// module_name: ten cua? module dang thao tac
// color_change: mau` cua? rows sau khi select
// arr_name: mang? cac' input
function change_bg(module_name,array,color_change)
{
	for (i=1;i<=array['length'];i++)
	{
		if ($(module_name+'_check_'+i).checked)
		{
			$(module_name+'_tr_'+array[i]).style.backgroundColor=color_change;
			final=i;
		}
		else
		{
			$(module_name+'_tr_'+array[i]).style.backgroundColor='white';
		}
	}
	return $(module_name+'_tr_'+array[final]).style.backgroundColor;
}
function check_all(module_name,array,color_change,value)
{
	//eval('array='+array);
	var inputs = document.getElementsByTagName('INPUT');
	for (var i=0;i<inputs.length;i++)
	{
		if(typeof(inputs[i])!='undefined' && inputs[i].type=='checkbox' && inputs[i].id.indexOf(module_name+'_check_')==0)
		{
			var index=inputs[i].id.substr((module_name+'_check_').length);
			if(value)
			{
				
				$(module_name+'_check_'+index).checked=1;
				if($(module_name+'_tr_'+array[index]))$(module_name+'_tr_'+array[index]).style.backgroundColor=color_change;
			}
			else
			{
				$(module_name+'_check_'+index).checked=0;	
				if($(module_name+'_tr_'+array[index]))$(module_name+'_tr_'+array[index]).style.backgroundColor='white';
			}
		}
	}
	$(module_name+'_check_0').checked=$(module_name+'_check_1').checked;	
}
function select_invert(module_name,array,color_change)
{
	eval('array='+array);
	for (i=1;i<=array['length'];i++)
	{
		$(module_name+'_check_'+i).checked=$(module_name+'_check_'+i).checked?0:1;	
		$(module_name+'_tr_'+array[i]).style.backgroundColor=$(module_name+'_check_'+i).checked?color_change:'white';
	}
}
function change_tab(obj)
{
	if(obj.className=="tab")
	{
		obj.className="tab_hover";
	}
	else
	{
		obj.className="tab";
	}
}
function select_date_time_range(url, start, end)
{
	if(start>end)
	{
		var temp = start;
		start = end;
		end = temp;
	}
	location = url+start+'-'+end;
}
function getElementHeight(Elem) {
	if (ns4) {
		var elem = getObjNN4(document, Elem);
		return elem.clip.height;
	} else {
		if(document.getElementById) {
			var elem = document.getElementById(Elem);
		} else if (document.all){
			var elem = document.all[Elem];
		}
		if (op5) { 
			xPos = elem.style.pixelHeight;
		} else {
			xPos = elem.offsetHeight;
		}
		return xPos;
	} 
}

function getElementWidth(Elem) {
	if (ns4) {
		var elem = getObjNN4(document, Elem);
		return elem.clip.width;
	} else {
		if(document.getElementById) {
			var elem = document.getElementById(Elem);
		} else if (document.all){
			var elem = document.all[Elem];
		}
		if (op5) {
			xPos = elem.style.pixelWidth;
		} else {
			xPos = elem.offsetWidth;
		}
		return xPos;
	}
}

ns4 = document.layers;
op5 = (navigator.userAgent.indexOf("Opera 5")!=-1) 
	||(navigator.userAgent.indexOf("Opera/5")!=-1);
op6 = (navigator.userAgent.indexOf("Opera 6")!=-1) 
	||(navigator.userAgent.indexOf("Opera/6")!=-1);
agt=navigator.userAgent.toLowerCase();
mac = (agt.indexOf("mac")!=-1);
ie = (agt.indexOf("msie") != -1); 
mac_ie = mac && ie;
function addEvent( obj, type, fn ) { 
  if ( obj.attachEvent ) { 
    obj['e'+type+fn] = fn; 
    obj[type+fn] = function(){obj['e'+type+fn]( window.event );} 
    obj.attachEvent( 'on'+type, obj[type+fn] ); 
  } else 
    obj.addEventListener( type, fn, false ); 
} 
function open_window(url)
{
	window.open(url,'tiachopviet','fullscreen');
}
function findPos(obj) {
	var currentleft = currenttop = 0;
	if (obj.offsetParent) {
		currentleft = obj.offsetLeft
		currenttop = obj.offsetTop
		while (obj = obj.offsetParent) {
			currentleft += obj.offsetLeft
			currenttop += obj.offsetTop
		}
	}
	return [currenttop,currentleft];
}