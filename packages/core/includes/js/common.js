jQuery(document).ready(function() {
	jQuery(".input_number").keydown(function(event) {
        //alert(event.keyCode);
		// Allow: backspace, delete, tab, escape, and enter
		if ( 
            event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
			 // Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			 // Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39) ||
            // Allow: . .(del)
            event.keyCode == 190 || event.keyCode == 110
            ) 
        {
				 // let it happen, don't do anything
				 return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault(); 
			}   
		}
	});
	jQuery(".input_code").keydown(function(event) {
		if (event.altKey == false && event.ctrlKey == false)
		{
			if(event.keyCode == 46 || event.keyCode == 35 || event.keyCode == 36 || event.keyCode == 8 || event.keyCode == 9 || (event.keyCode >= 48 && event.keyCode <= 57 && event.shiftKey== false) || (event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 97 && event.keyCode <= 122))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
   	});
    //20/10 : format input(money)
	jQuery(".format_number").keyup(function(event) {
        //left and right thi` khong xu ly
        if(event.keyCode == 37 || event.keyCode == 39)
            return;
        else
        {
            num = this.value.toString().replace(/\$|\,/g,'');
            
            var dot_position =this.value.indexOf('.');
            var last = '';
            if(dot_position!= -1)
            {
                last = this.value.substring(dot_position,this.value.length);
                num = this.value.substring(0,dot_position);
            }
            num = num.toString().replace(/\$|\,/g,'');
            var length = num.length;
            var result = '';
            if(length>3)
            {
                var start = 0;
                if(length%3 != 0)
                    start = length%3;
                if(start!=0)
                    result+=num.substring(0,start)+','; 
                for (var i = 1; i <= Math.floor(length/3); i++)
                {
                	result+= num.substring(start,start+3)+',';
                    start = start+3;
                }
                //cat bo dau phay sau cung
                result = result.substring(0,result.length-1)+last;
            }
            else
                result += num+last;
            jQuery(this).val(result); 
        }

	});
    
});
jQuery.fn.ForceNumericOnly =
function()
{
	return this.each(function()
	{
		jQuery(this).keydown(function(e)
		{
			var key = e.charCode || e.keyCode || 0;
			// allow enter backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			return (
				key == 8 || 
				key == 9 ||
                key == 110 ||
				key == 46 ||
				key == 190 ||
                key == 13 ||
				(key >= 37 && key <= 40) ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105))||(key == 65 && e.ctrlKey === true);
		});
	});
};
jQuery.fn.ForceCharOnly =
function()
{
	return this.each(function()
	{
		jQuery(this).keydown(function(e)
		{
			var key = e.charCode || e.keyCode || 0;
			// allow enter backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			return (
				key == 8 || 
				key == 9 ||
				key == 32 ||
				(key >= 37 && key <= 40) || (key >= 65 && key <= 90)  || (key == 65 && e.ctrlKey === true));
		});
	});
};	
jQuery.fn.ForceCodeOnly =
function()
{
	return this.each(function()
	{
		jQuery(this).keypress(function(event){
			if (event.altKey == false && event.ctrlKey == false)
			{
				if(event.keyCode == 46 || event.keyCode == 35 || event.keyCode == 36 || event.keyCode == 8 || event.keyCode == 9 || (event.keyCode >= 48 && event.keyCode <= 57 && event.shiftKey== false) || (event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 97 && event.keyCode <= 122))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		});
	});
};	

//Su dung khi dung multi item do mi duoc sinh ra sau, nen chua dc bind su kien
jQuery.fn.FormatNumber =
function()
{
    //20/10 : format input(money)
	jQuery(this).keyup(function(event) {
        //left and right thi` khong xu ly
        if(event.keyCode == 37 || event.keyCode == 39)
            return;
        else
        {
            num = this.value.toString().replace(/\$|\,/g,'');
            
            var dot_position =this.value.indexOf('.');
            var last = '';
            if(dot_position!= -1)
            {
                last = this.value.substring(dot_position,this.value.length);
                num = this.value.substring(0,dot_position);
            }
            num = num.toString().replace(/\$|\,/g,'');
            var length = num.length;
            var result = '';
            if(length>3)
            {
                var start = 0;
                if(length%3 != 0)
                    start = length%3;
                if(start!=0)
                    result+=num.substring(0,start)+','; 
                for (var i = 1; i <= Math.floor(length/3); i++)
                {
                	result+= num.substring(start,start+3)+',';
                    start = start+3;
                }
                //cat bo dau phay sau cung
                result = result.substring(0,result.length-1)+last;
            }
            else
                result += num+last;
            jQuery(this).val(result); 
        }

	});
};	

ns4 = document.layers;
op5 = (navigator.userAgent.indexOf("Opera 5")!=-1) 
	||(navigator.userAgent.indexOf("Opera/5")!=-1);
op6 = (navigator.userAgent.indexOf("Opera 6")!=-1) 
	||(navigator.userAgent.indexOf("Opera/6")!=-1);
agt=navigator.userAgent.toLowerCase();
mac = (agt.indexOf("mac")!=-1);
ie = (agt.indexOf("msie") != -1); 
mac_ie = mac && ie;
//Them tu he thong cu Duc
function addEvent( obj, type, fn ) { 
  if ( obj.attachEvent ) { 
    obj['e'+type+fn] = fn; 
    obj[type+fn] = function(){obj['e'+type+fn]( window.event );} 
    obj.attachEvent( 'on'+type, obj[type+fn] ); 
  } else 
    obj.addEventListener( type, fn, false ); 
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
function format_number(pnumber,decimals){
	if (isNaN(pnumber)) { return 0};
	if (pnumber=='') { return 0};
	
	var snum = new String(pnumber);	
	var sec = snum.split('.');
	var whole = parseFloat(sec[0]);
	var result = '';
	
	if(sec.length > 1){
		var dec = new String(sec[1]);		
		dec = String(parseFloat(sec[1])/Math.pow(10,(dec.length - decimals)));
		
		dec = String(whole + Math.round(parseFloat(dec))/Math.pow(10,decimals));
		
		var dot = dec.indexOf('.');
		if(dot == -1){
			dec += '.'; 
			dot = dec.indexOf('.');
		}
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	} else{
		var dot;
		var dec = new String(whole);
		dec += '.';
		dot = dec.indexOf('.');		
		while(dec.length <= dot + decimals) { dec += '0'; }
		result = dec;
	}	
	return result;
}
function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
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
//
function echo(st)
{
	document.write(st);
}
function $(id)
{
	if(typeof(id)=='object')
	{
		return id;
	}
	return document.getElementById(id);
}
function toggle(id, status)
{
	if($(id))
	{
		if(typeof(status)!='undefined')
		{
			$(id).style.display = status;
		}
		else if($(id).style.display == 'none')
		{
			$(id).style.display ='';
		}
		else
		{
			$(id).style.display = 'none';
		}
	}
}
function findPos(object) {
	var left = 0;
	var top = 0;
	while (object.offsetParent) {
	left += object.offsetLeft;
	top += object.offsetTop;
	object = object.offsetParent;
	}
	left += object.offsetLeft;
	top += object.offsetTop;
	return [left,top];
}
/*function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	}
	return [curleft,curtop];
}*/

function select_all_checkbox(form,name,status, select_color, unselect_color)
{
	for (var i = 0; i < form.elements.length; i++) {
		if (form.elements[i].name == 'selected_ids[]') {
			if(status==-1)
			{
				form.elements[i].checked = !form.elements[i].checked;
			}
			else
			{
				form.elements[i].checked = status;
			}
			if(select_color)
			{
				if($(name+'_tr_'+form.elements[i].value))
				{
					$(name+'_tr_'+form.elements[i].value).style.backgroundColor=
						form.elements[i].checked?select_color:unselect_color;
				}
			}
		}
	}
}
function select_checkbox(form, name, checkbox, select_color, unselect_color)
{
	tr_color = checkbox.checked?select_color:unselect_color;
	if(typeof(event)=='undefined' || !event.shiftKey)
	{
		$(name+'_all_checkbox').lastSelected = checkbox;
		if(select_color && $(name+'_tr_'+checkbox.value))
		{
			$(name+'_tr_'+checkbox.value).style.backgroundColor=
				checkbox.checked?select_color:unselect_color;
		}
		update_all_checkbox_status(form, name);
		return;
	}
	//select_all_checkbox(form, name, false, select_color, unselect_color);
	
	var active = typeof($(name+'_all_checkbox').lastSelected)=='undefined'?true:false;
	
	for (var i = 0; i < form.elements.length; i++) {
		if (!active && form.elements[i]==$(name+'_all_checkbox').lastSelected)
		{
			active = 1;
		}
		if (!active && form.elements[i]==checkbox)
		{
			active = 2;
		}
		if (active && form.elements[i].id == name+'_checkbox') {
			form.elements[i].checked = checkbox.checked;
			$(name+'_tr_'+form.elements[i].value).style.backgroundColor=
				checkbox.checked?select_color:unselect_color;
		}
		if(active && (form.elements[i]==checkbox && active==1) || (form.elements[i]==$(name+'_all_checkbox').lastSelected && active==2))
		{
			break;
		}
	}
	update_all_checkbox_status(form, name);
}
function update_all_checkbox_status(form, name)
{
	var status = true;
	for (var i = 0; i < form.elements.length; i++) {
		if (form.elements[i].name == 'selected_ids[]' && !form.elements[i].checked) {
			status = false;
			break;
		}
	}
	$(name+'_all_checkbox').checked = status;
}
/*---------------------Ham check_all,select_invert cua he thong cu ngocnv updated 11/05/09-----------------------------------*/
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

/*-----------------------------------------------------------------------------------*/
var ns = (navigator.appName.indexOf("Netscape") != -1);
var d = document;
var px = document.layers ? "" : "px";
function JSFX_FloatDiv(id, sx, sy)
{
	var el=d.getElementById?d.getElementById(id):d.all?d.all[id]:d.layers[id];
	window[id + "_obj"] = el;
	if(d.layers)el.style=el;
	el.cx = el.sx = sx;el.cy = el.sy = sy;
	el.sP=function(x,y){this.style.left=x+px;this.style.top=y+px;};
	el.flt=function()
	{
		var pX, pY;
		pX = (this.sx >= 0) ? 0 : ns ? innerWidth : 
		document.documentElement && document.documentElement.clientWidth ? 
		document.documentElement.clientWidth : document.body.clientWidth;
		pY = ns ? pageYOffset : document.documentElement && document.documentElement.scrollTop ? 
		document.documentElement.scrollTop : document.body.scrollTop;
		if(this.sy<0) 
		pY += ns ? innerHeight : document.documentElement && document.documentElement.clientHeight ? 
		document.documentElement.clientHeight : document.body.clientHeight;
		this.cx += (pX + this.sx - this.cx)/8;this.cy += (pY + this.sy - this.cy)/8;
		this.sP(this.cx, this.cy);
		setTimeout(this.id + "_obj.flt()", 40);
	}
	return el;
}
function number_format_usd(nStr)
{
	//nStr = format_number(nStr,2);
	nStr += '';
	
	x = nStr.split('.');
	x1 = x[0];
	//x2 = x.length > 1 ? '.' + x[1] : '';
	
	//duc them
	var decimals = 2; 
	if(x.length > 1){
		var x2 = new String(x[1]);		
		x2 = String(Math.round(parseFloat(x[1])/Math.pow(10,(x2.length - decimals))));
		while(x2.length < decimals) { x2 = '0'+x2; }
		x2 = '.'+x2;
	} else{
		var x2 = '';
		x2 += '.';
		while(x2.length <= decimals) { x2 += '0'; }
	}
	//end edit
	
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
function number_format(nStr)
{
	nStr = to_numeric(nStr);
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	//x2 = x.length > 1 ? '.' + x[1] : '';
	
	//duc them
	var decimals = 2; 
	if(x.length > 1){
		var x2 = new String(x[1]);		
		x2 = String(Math.round(parseFloat(x[1])/Math.pow(10,(x2.length - decimals))));
		while(x2.length < decimals) { x2 = '0'+x2; }
        //Dat START bo sung truong hop lam tron phan thap phan bi tang len 1 don vi phan nguyen
        /** 
        VD x = 10.9988
        khi do phan thap phan = .9988
        sau qua trinh xu ly tren x2 = 100
        dan den 0.9988 => 0.100
        trong truong hop nay can phai cong them cho x1 1 don vi
        **/
        //x2 = '.'+x2;
        if(x2.length > decimals)
        {
            x1 = to_numeric(x1) + 1;
            x1 += '';
            
            var x2 = '';
        }
        else
            x2 = '.'+x2;
        //Dat END bo sung
	} else{
		var x2 = '';
		//x2 += '.';
		//while(x2.length <= decimals) { x2 += '0'; }
	}
	//end edit
	
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/*-------------------- Edit by ngocnv add function to_numeric--------------*/
function to_numeric(st)
{
	st = st+'';
	if(st)
	{
		return (typeof(st)=='number' || (typeof(st.match)!='undefined' && !st.match(/[^0-9.,-]/)))?parseFloat(st.replace(/\,/g,'')):st;
	}
	else
	{
		if(st==''){
			return 0;
		}else return st;
	}
}
/*-------------------------------------------------------------------------*/
function is_numeric(sText)
{
	var ValidChars = "0123456789.";
	var isNumeric=true;
	var Char;
	
	
	for (i = 0; i < sText.length && isNumeric == true; i++) 
	  { 
	  Char = sText.charAt(i); 
	  if (ValidChars.indexOf(Char) == -1) 
		 {
		 isNumeric = false;
		 }
	  }
	return isNumeric;
}
function stringToNumber(st){
	if(st)
	{
		return (typeof(st)=='number' || (typeof(st.match)!='undefined' && !st.match(/[^0-9.,-]/)))?parseFloat(st.replace(/\,/g,'')):st;
	}
	else
	{
		return st;
	}
}
function start_clock()
{
	var thetime=new Date();
	var nhours=thetime.getHours();
	var nmins=thetime.getMinutes();
	var nsecn=thetime.getSeconds();
	var nday=thetime.getDay();
	var nmonth=thetime.getMonth();
	var ntoday=thetime.getDate();
	var nyear=thetime.getYear();
	var AorP=" ";
	if (nhours>=12)
		AorP="P.M.";
	else
		AorP="A.M.";
	if (nhours>=13)
		nhours-=12;
	if (nhours==0)
	   nhours=12;
	if (nsecn<10)
	 nsecn="0"+nsecn;
	if (nmins<10)
	 nmins="0"+nmins;
	$('clockspot').innerHTML=nhours+": "+nmins+": "+nsecn+" "+AorP;
	setTimeout('start_clock()',1000);
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
/*-------------------- edit by ngocnv 19/05/09 ------------------------*/
function full_screen()
{
	is_full_screen = true;
	if($('_footer_region'))
	{
		$('_header_region').style.display='none';
		$('_footer_region').style.display='none';
		$('_center_region').className='center_region_full_screen';
	}
}
/*-----------------------Print-----------------------------------------------*/
/* 7/7/2016 - Dat comment.*/
//function printWebPart(tagid,user){
//	if(tagid)
//	{
//        var d = new Date();
//        var month = d.getMonth()+1;
//        var html = "";
//		html = "<html><head>"+
//		"<link rel=\"stylesheet\" type=\"text/css\" href=\"packages/core/skins/default/css/global.css\" media=\"print\" ></link>"+
//		"</head><body >"+
//		jQuery("#"+tagid).html()+
//		"</body></html>";
//		width = jQuery(document).width();
//		height = jQuery(document).height();
//		html = html.replace('packages/core/includes/js/common.js','');
//		var printWP = window.open("about:blank","printWebPart","location=0,width="+width+",height="+height+",top=0,left=0");
//		printWP.document.open();
//		printWP.document.write(html);
//        printWP.document.body.style.zoom= "40%";
//		printWP.print();
//		printWP.document.close();
//		printWP.close();
//	}
//}

/** Thanh fix ham in khong bi in trang trang **/
function printWebPart(tagid,user,close_window_opener){
    if(!close_window_opener){
        close_window_opener = 'false';
    }
    if(tagid)
	{
            
            var clone = jQuery("#"+tagid).clone();
            jQuery(clone).find('script').remove();
            var printContents = jQuery(clone).html();
            printContents += "<script> window.print(); window.close(); </script>";
            /** Tham so close_window_opener su dung de dong cua so parent **/
                if(close_window_opener=='true')
                {
                    printContents += "<script> window.parent.close(); </script>";
                }
            /** Tham so close_window_opener su dung de dong cua so parent **/
            printContents = '<script src="packages/core/includes/js/jquery/jquery-1.7.1.js?v=3.15" type="text/javascript"></script>'+printContents;
            width = jQuery(document).width();
    		height = jQuery(document).height();
            var printWP = window.open("about:blank","printWebPart","location=0,width="+width+",height="+height+",top=0,left=0");
    		
            printWP.document.open();
    		printWP.document.write(printContents);        
	}
}
/** Thanh fix ham in khong bi in trang trang **/
function printWebPartZoom(tagid){
	if(tagid)
	{
		var html = "";
		html = "<html><head>"+
		"<link rel=\"stylesheet\" type=\"text/css\" href=\"packages/core/skins/default/css/global.css\" media=\"print\" ></link>"+
		"</head><body >"+
		jQuery("#"+tagid).html()+
		"</body></html>";
		width = jQuery(document).width();
		height = jQuery(document).height();
		html = html.replace('packages/core/includes/js/common.js','');
		var printWP = window.open("about:blank","printWebPart","location=0,width="+width+",height="+height+",top=0,left=0");
		printWP.document.open();
		printWP.document.write(html);
        //printWP.document.body.style.zoom= zoom;
		printWP.print();
		printWP.document.close();
		printWP.close();
	}
}
function advancePrint(url,massage){
	html = jQuery("#printer").html();
	jQuery("body").empty();
	jQuery("body").append('<div id="printer">'+html+'</div>');
	jQuery("#printer").printPage({
	  url: url,
	  attr: "href",
	  message:massage
	});
	window.close();
}
function convertDateToJSDate(date){
	if(date.match(/[0-9]+\/[0-9]+\/[0-9]+/)){
		arr = date.split('/');
		return arr[1]+'-'+arr[0]+'-'+arr[2];
	}else{
		return false;
	}
}
function convertJSDateToDate(JSDate){
	if(JSDate.match(/[0-9]+\-[0-9]+\-[0-9]+/)){
		arr = JSDate.split('/');
		return arr[1]+'/'+arr[0]+'/'+arr[2];
	}else{
		return false;
	}
}
function calculateDays(dateFrom,dateTo){
    dateFromArray = dateFrom.split('/');
    newDateFrom = dateFromArray[2]+"/"+dateFromArray[1]+"/"+dateFromArray[0];
    dateToArray = dateTo.split('/');
    newDateTo = dateToArray[2]+"/"+dateToArray[1]+"/"+dateToArray[0];
    var begDate = new Date(newDateFrom);
	var endDate = new Date(newDateTo);
	var difDate = endDate.getTime() - begDate.getTime();
	return difDate/(24*60*60*1000);
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
function openWindowUrl(url,data)
{
	var id = 'new_window';
	var title = '';
	var top = -300;
	var width = 600;
	var height = 400;
	if(data[0]){
		id = data[0];	
	}
	if(data[1]){
		title = data[1];	
	}
	if(data[2]){
		top = data[2];	
	}
	if(data[4]){
		width = data[4];	
	}
	if(data[5]){
		height = data[5];	
	}
	document_width = jQuery(document).width();
	var left = (document_width/2)-(data[4]/2);  
	if(jQuery('#'+id)){       
		jQuery.newWindow({
			id:id,
			title:title,
			content:"",
			posx:left,
			posy:top,
			width:width,
			height:height,
			draggable:true,
			onWindowClose:function(){
				if(confirm('BÃ�ï¿½Ã�Â¡Ã�ï¿½Ã�ÂºÃ�ï¿½Ã�Â¡n cÃ�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â³ muÃ�ï¿½Ã�Â¡Ã�ï¿½Ã�Â»Ã�Â¯Ã�Â¿Ã�Â½n tÃ�ï¿½Ã�Â¡Ã�ï¿½Ã�ÂºÃ�ï¿½Ã�Â£i lÃ�ï¿½Ã�Â¡Ã�ï¿½Ã�ÂºÃ�ï¿½Ã�Â¡i trang khÃ�ï¿½Ã¯Â¿Â½Ã�ï¿½Ã�Â´ng?'))
				{
				    var at_path = window.parent.location.toString();
                    if(at_path.indexOf('&adddd_guest=yes')>-1)
                    {
                        var ts_path = at_path.replace('&adddd_guest=yes','');
                        //alert(ts_path);
                        window.parent.location.replace(ts_path);
                    }
                    else
					   window.parent.location.reload();
				}
				jQuery('.mask-window').fadeOut();
			}
		});
	}
	jQuery.updateWindowContentWithUrl(id,url);
	dHeight = jQuery(document).height();
	jQuery('.mask-window').css('height',dHeight);
	jQuery('.mask-window').fadeIn();
}
