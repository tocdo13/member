var can_admin = false;
var current_traveller_index = false;
var currentTime = new Date()
var _month = currentTime.getMonth() + 1
var _day = currentTime.getDate()
var _year = currentTime.getFullYear()
var currentDate = (((_day<10)?'0'+_day:_day)+"/"+((_month<10)?'0'+_month:_month)+"/"+_year);
function get_traveller(index)
{
	if($('member_code_'+index).value!='' || $('passport_'+index).value!='' || ($('first_name_'+index).value!='' && $('last_name_'+index).value!=''))
	{
		current_traveller_index = index;
		ajax.get_text('r_get_traveller.php?first_name='+$('first_name_'+index).value+'&last_name='+$('last_name_'+index).value+'&passport='+$('passport_'+index).value+'&member_code='+$('member_code_'+index).value+'&traveller_id='+$('traveller_id__'+index).value, set_traveller);
	}
}
function set_traveller(text){
	var viewDetailLink = 'Xem thong tin khach/ View this guest\'s info';
	if(text!='')
	{
		eval(text);
		$('traveller_id__'+current_traveller_index).value = traveller.id;
		$('first_name_'+current_traveller_index).value = traveller.first_name;
		$('last_name_'+current_traveller_index).value = traveller.last_name;
		$('gender_'+current_traveller_index).value = traveller.gender;
		$('birth_date_'+current_traveller_index).value = traveller.birth_date;
		$('nationality_id_'+current_traveller_index).value = traveller.nationality_id;
		$('nationality_name_'+current_traveller_index).value = traveller.nationality_name;
		$('phone_'+current_traveller_index).value = traveller.phone;
		$('note_'+current_traveller_index).value = traveller.note;
		$('traveller_level_id_'+current_traveller_index).value = traveller.traveller_level_id;
		$('expire_date_of_visa_'+current_traveller_index).value = traveller.expire_date_of_visa;
		$('visa_'+current_traveller_index).value = traveller.visa;
        $('member_code_'+current_traveller_index).value = traveller.member_code;
		$('address_'+current_traveller_index).value = traveller.address;
		$('detail_link_'+current_traveller_index).innerHTML = '<a target="blank" href="?page=traveller&id='+traveller.id+'">'+viewDetailLink+'#'+traveller.id+'</a>';
	}
	else
	{
		$('detail_link_'+current_traveller_index).innerHTML = '';
	}
}
function updateNationality(index){
	if($('nationality_id_'+index) && $('nationality_id_'+index).value){
		if(typeof(nationalities[jQuery('#nationality_id_'+index).val()])=='undefined'){
		}else{
			jQuery('#nationality_name_'+index).val(nationalities[jQuery('#nationality_id_'+index).val()]['name']);
		}
	}
}
function updateStatusList(index){
    if($('old_status_'+index) && $('id_'+index).value){
		if($('status_'+index).value == 'CANCEL'){
			$('room_extra_info_'+index).className = 'room-extra-info cancel'
			removeOptionSelected($('status_'+index),'CHECKOUT');
			removeOptionSelected($('status_'+index),'CHECKIN');
		}else{
			$('room_extra_info_'+index).className = 'room-extra-info'
		}
		if($('status_'+index).value == 'BOOKED'){
			removeOptionSelected($('status_'+index),'CHECKOUT');
			if($('old_status_'+index).value && issetOptionSelected($('status_'+index),'CHECKIN')==false){
				appendOptionLast($('status_'+index),'Check in','CHECKIN');
			}
			if($('old_status_'+index).value && issetOptionSelected($('status_'+index),'CANCEL')==false){
				appendOptionLast($('status_'+index),'Cancel','CANCEL');
			}
		}
        //KimTan them
        if($('status_'+index).value =='NOSHOW'){
            //removeOptionSelected($('status_'+index),'CHECKIN');
            $('room_extra_info_'+index).className = 'room-extra-info cancel'
		    removeOptionSelected($('status_'+index),'CHECKOUT');
            removeOptionSelected($('status_'+index),'CHECKIN');
			removeOptionSelected($('status_'+index),'CANCEL');
			//removeOptionSelected($('status_'+index),'CANCEL');
		}
        if($('status_'+index).value !='NOSHOW' && $('status_'+index).value !='BOOKED'){
            removeOptionSelected($('status_'+index),'NOSHOW');
		}
        //end KimTan them
		if($('old_status_'+index).value =='CHECKIN'){
            //luu nguyen giap comment change status check in=>book
			//removeOptionSelected($('status_'+index),'BOOKED');
            //end
			removeOptionSelected($('status_'+index),'CANCEL');
			if(issetOptionSelected($('status_'+index),'CHECKOUT')==false){
				appendOptionLast($('status_'+index),'Check out','CHECKOUT');
			}
		}
		if($('old_status_'+index).value =='CHECKOUT'){
			removeOptionSelected($('status_'+index),'BOOKED');
			removeOptionSelected($('status_'+index),'CANCEL');
		}
	}else{
		removeOptionSelected($('status_'+index),'CANCEL');
        removeOptionSelected($('status_'+index),'NOSHOW');
		removeOptionSelected($('status_'+index),'CHECKOUT');
	}
	if($('id_'+index) && $('id_'+index).value){
		if(can_admin==false){
			//if($('delete_reservation_room_'+index))$('delete_reservation_room_'+index).style.display = 'none';
			//if($('delete_traveller_'+index))$('delete_traveller_'+index).style.display = 'none';
			//if($('price_'+index))$('price_'+index).readOnly = true;
		}
		//if($('select_room_'+index))$('select_room_'+index).style.display = 'none';
	}
}
function removeOptionSelected(object,value)
{
	if(object){
		var i;
		for (i = object.length - 1; i>=0; i--){
			if (object.options[i].value == value){
			  object.remove(i);
			}
		}
	}
}
function issetOptionSelected(object,value)
{
	if(object){
		var i;
		for (i = object.length - 1; i>=0; i--){
			if (object.options[i].value == value){
			  return true;
			}
		}
	}
	return false;
}
function appendOptionLast(object,text,value)
{
	var elOptNew = document.createElement('option');
	elOptNew.text = text;
	elOptNew.value = value;
	try {
		object.add(elOptNew, null); // standards compliant; doesn't work in IE
	}
	catch(ex) {
		object.add(elOptNew); // IE only
	}
}
function count_price(index,cPrS)//cPrS: change price schedule
{
	if(!cPrS){
		updateStatusList(index);
    }
	var readOnly_ = '';
	var change = false;
	var total=0;
	var total_date=0;
	var timeIN=0;
	var time_ins=new Array();
	var timeOUT=0;
	var time_outs=new Array();
	/*if($('old_status_'+index).value=='' || $('old_status_'+index).value=='BOOKED'){
		var d = new Date();
		alert(d.getHours()+':'+d.getMinutes());
		$('arrival_time_'+index).value=d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
		$('time_in_'+index).value=d.getHours()+':'+d.getMinutes();
	}*/
	//if(isset($('id_'+index).value))
    var time_in_default= TIME_IN_DEFAULT.split(":");
    //console.log(time_in_default);
	var time_out_default=TIME_OUT_DEFAULT.split(":");
    //console.log(time_out_default);
    var timeIN_DEFAULT = to_numeric(time_in_default[0]*60) + to_numeric(time_in_default[1]);
	var timeOUT_DEFAULT = to_numeric(time_out_default[0]*60) + to_numeric(time_out_default[1]);
    var timeIN_distance = 6*60;
	var timeOUT_distance = 23*60+59;
    /** manh them xet time late_checkin **/
    var timeIN_LateCheckIn = 6*60*60;
    
    var auto_li_start_time= AUTO_LI_START_TIME.split(":");
	var auto_li_end_time=AUTO_LI_END_TIME.split(":");
    var li_start_time = to_numeric(auto_li_start_time[0]*60) + to_numeric(auto_li_start_time[1]);
	var li_end_time = to_numeric(auto_li_end_time[0]*60) + to_numeric(auto_li_end_time[1]);
    /** end manh **/
	var time_in = $('time_in_'+index).value;
	if(time_in!='')
	{
		time_ins =time_in.split(":");
		timeIN=to_numeric(time_ins[0])*60+to_numeric(time_ins[1]);
        /** manh them xet time late_checkin **/
        timeIN_Room = to_numeric(time_ins[0])*60*60+to_numeric(time_ins[1])*60;
        /** end manh **/
	}
	var time_out = getElemValue('time_out_'+index);
	if(time_out!='')
	{
		time_outs =time_out.split(":");
		timeOUT=to_numeric(time_outs[0])*60+to_numeric(time_outs[1]);
	}
	var arrival_time = $('arrival_time_'+index).value;
	var departure_time = $('departure_time_'+index).value;
	if(arrival_time!='' && departure_time!='')
	{
		total_date=count_date(arrival_time,departure_time);
		if(total_date<0)
		{
			alert('Ngay` di phai lon hon ngay den...!');
            $('departure_time_'+index).value='';
			$('departure_time_'+index).focus();
			return;
		}
	}
    var check_date_price = 0;
	var check_date_price_out = 0;
    if(total_date==0 && timeIN>0 && timeIN<timeIN_distance ){ //&& timeOUT<=timeOUT_DEFAULT
        check_date_price = 1;
    }
	if(total_date==0 && timeOUT>0 && timeOUT<timeOUT_distance && timeIN<=timeIN_DEFAULT){
        check_date_price_out = 1;
    }
	if(total_date==0)
	{
		total_date = 1;
	}
	var checked = 0;
	var room_id = jQuery('#room_id_'+index).val();
	room_name = '';
	if(room_id > 0){
		room_name = jQuery('#room_name_'+index).val();
	}
    /** manh xet truong hop late_checkin de add gia **/
    
    
    if(time_in!='' && to_numeric(timeIN_Room)<=to_numeric(timeIN_LateCheckIn) && typeof(mi_reservation_room_arr)!='undefined' && typeof(mi_reservation_room_arr[$('id_'+index).value])!='undefined')
    {
        var now_date = new Date($('arrival_time_'+index).value.split('/')[2],$('arrival_time_'+index).value.split('/')[1],$('arrival_time_'+index).value.split('/')[0]);
        var before_date = fomat_day_month(now_date.getDate() - 1)+"/"+fomat_day_month(now_date.getMonth())+"/"+now_date.getFullYear(); 
        
        if(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][before_date]=='undefined')
        {
            //console.log(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][before_date]);
        }
        else
        {
            //jQuery("#auto_late_checkin_price_"+index).val(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][before_date]);
        }
    }
    /** end manh **/
    /** manh them luoc do gia extra_bed **/
    if(document.getElementById("extra_bed_"+index).checked==true && jQuery("#extra_bed_from_date_"+index).val()!='' && jQuery("#extra_bed_to_date_"+index).val()!='')
    {
        extra_bed_price = 0;
        if(to_numeric(jQuery("#extra_bed_rate_"+index).val())!=0)
            extra_bed_price = to_numeric(jQuery("#extra_bed_rate_"+index).val());
        else{
            jQuery("#extra_bed_rate_"+index).val(number_format(0));
        }
        var days_extra_bed = calculateDays($('extra_bed_from_date_'+index).value,$('extra_bed_to_date_'+index).value);
        var d_extra_bed = new Date(convertDateToJSDate($('extra_bed_from_date_'+index).value));
        $('price_schedule_extra_bed_'+index).innerHTML = '';
        for(var i_extra=1;i_extra<=days_extra_bed;i_extra++)
        {
            date_extra = d_extra_bed.getDate();
            month_extra = d_extra_bed.getMonth()+1;
            year_extra = (d_extra_bed.getYear()).toString();
            var year_extra_ = year_extra.replace(/^10/i,'200');
            year_extra_ = year_extra_.replace(/^11/i,'201');
            sch_date_extra = (((date_extra<10)?'0'+date_extra:date_extra)+"/"+((month_extra<10)?'0'+month_extra:month_extra)+"/"+year_extra_);
            if(!cPrS)
            {
                if(mi_reservation_room_arr[$('id_'+index).value]['change_price_extra_bed_arr'] != undefined)
                {
                    if(mi_reservation_room_arr[$('id_'+index).value]['change_price_extra_bed_arr'][sch_date_extra]!= undefined)
                    {
                        document.getElementById('extra_bed_rate_'+index).readOnly = true;
                        extra_bed_price = to_numeric(mi_reservation_room_arr[$('id_'+index).value]['change_price_extra_bed_arr'][sch_date_extra]);
                    }//daund sua loi luoc dau gia extrabed
                    else
                    {
                        document.getElementById('extra_bed_rate_'+index).readOnly = false;
                        extra_bed_price = to_numeric(jQuery('#extra_bed_rate_'+index).val());
                    }
                    //daund sua loi luoc dau gia extrabed
                }
            }
            $('price_schedule_extra_bed_'+index).innerHTML += '<span class="change-price-span" style="background: #d2fce3;">'+sch_date_extra+' <br /> <input name="mi_reservation_room['+index+'][change_price_extra_bed_arr]['+sch_date_extra+']" type="text" id="change_price_extra_bed_'+sch_date_extra+'_'+index+'" value="'+number_format(extra_bed_price)+'" style="width: 50px;" class="change-price-input" /></span>';
            d_extra_bed.setDate(d_extra_bed.getDate()+1);
        }
        jQuery("#price_schedule_bound_extra_bed_"+index).css('display','');
    }
    else
    {
        $('price_schedule_extra_bed_'+index).innerHTML = '';
        jQuery("#price_schedule_bound_extra_bed_"+index).css('display','none');
    }
    /** end manh **/
	if($('price_'+index) && $('price_'+index).value)
	{
		//----------------------------------------------------------------------------------------
		if(($('old_status_'+index).value=='' || $('old_status_'+index).value=='BOOKED') && $('status_'+index).value=='CHECKIN'){
			checked = 1;
		}
		var totaldate=count_date(arrival_time,departure_time);
		var total_amount = to_numeric($('price_'+index).value)*to_numeric(total_date);
		if($('price_schedule_'+index) && $('arrival_time_'+index)!=null && $('arrival_time_'+index).value && $('status_'+index)!=null && ($('status_'+index).value != 'CANCEL') && ($('status_'+index).value != 'NOSHOW'))
        {//&& ($('status_'+index).value != 'BOOKED'
			total_amount = 0;
			days = calculateDays($('arrival_time_'+index).value,$('departure_time_'+index).value);
			if(days<=1){
				days = 1;
			}
			$('price_schedule_'+index).innerHTML = '';
            var dateArray = $('arrival_time_'+index).value.split('/');
            var newDate = dateArray[2]+"/"+dateArray[1]+"/"+dateArray[0];
			var d = new Date(newDate);
			for(var n=1;n<=days;n++){
				if(n>1){
					d.setDate(d.getDate()+1);
				}
				date = d.getDate();
				month = d.getMonth()+1;
				y = (d.getYear()).toString();
				var date_room = (((date<10)?'0'+date:date)+"/"+((month<10)?'0'+month:month)+"/"+d.getYear().toString().substr(1,(d.getYear().toString()).length));
				var year_ = y.replace(/^10/i,'200');
				year_ = year_.replace(/^11/i,'201');
				r_d = (((date<10)?'0'+date:date)+"/"+((month<10)?'0'+month:month)+"/"+year_);
                r_d_2 = (((date<10)?'0'+date:date)+"_"+((month<10)?'0'+month:month)+"_"+year_);
				var $price = to_numeric($('price_'+index).value);
				if(holidays[r_d]){
					$price = parseFloat($price) + parseFloat(holidays[r_d]['charge']);
				}
				if(d.getDay()==5){
					$price = parseFloat($price) + parseFloat(saturday_charge);
				}
				if(d.getDay()==6){
					$price = parseFloat($price) + parseFloat(sunday_charge);
				}
                /** Start: Daund them readonly cho lược đấu giá quá khứ */
                var today_dn = new Date(); 
                var today_nd = today_dn.getDate()+"/"+(today_dn.getMonth()+1)+"/"+today_dn.getFullYear();
                //console.log(today_nd);
                var in_date_old = r_d
                in_date_old_arr = in_date_old.split('/');
                in_date_old = new Date(in_date_old_arr[1]+'/'+in_date_old_arr[0]+'/'+in_date_old_arr[2]);  
                
                var in_date_old = Date.parse(in_date_old);
                
                today_arr = today_nd.split('/');
                today_parse = new Date(today_arr[1]+'/'+today_arr[0]+'/'+today_arr[2]);  
                
                var today_parse = Date.parse(today_parse);
                /** END: Daund them readonly cho lược đấu giá quá khứ */
                if(cPrS)
                {
                    
                    //giap.ln add truong hop luoc do gia readonly khi su dung package tien phong
                    if(typeof(mi_reservation_room_arr)!='undefined' && typeof(mi_reservation_room_arr[$('id_'+index).value])!='undefined')
                    {
                        if(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d+'_is_package_room']==1)
                        {                            
                            $('price_schedule_'+index).innerHTML += '\
                            <span class="change-price-span">'+date_room+'-'+room_name+'<br>\
                            <input  name="mi_reservation_room['+index+'][change_price_arr]['+r_d+']" type="text" id="change_price_'+r_d+'_'+index+'" value="'+number_format(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d])+'" style="width:50px;" onchange="check_number();" class="readonly" readonly="readonly"  >\
                            </span>';                            
                        }
                        else
                        {    
                            if(to_numeric(in_date_old) < to_numeric(today_parse))
                            {
                                if(CAN_EDIT_PRICE_DN==false)
                                {
                                    $price = mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d];
                                }
                            }
                            $('price_schedule_'+index).innerHTML += '\
                            <span class="change-price-span">'+date_room+'-'+room_name+'<br>\
                            <input  name="mi_reservation_room['+index+'][change_price_arr]['+r_d+']" type="text" id="change_price_'+r_d+'_'+index+'" value="'+number_format($price)+'" style="width:50px;" onchange="check_number();" class="change-price-input change_price_'+r_d_2+'" '+readOnly+'  >\
                            </span>';
                        }
                    } 
                    else
                    {
                        $('price_schedule_'+index).innerHTML += '\
                        <span class="change-price-span">'+date_room+'-'+room_name+'<br>\
                        <input  name="mi_reservation_room['+index+'][change_price_arr]['+r_d+']" type="text" id="change_price_'+r_d+'_'+index+'" value="'+number_format($price)+'" style="width:50px;" onchange="check_number();" class="change-price-input change_price_'+r_d_2+' input_number" oninput="this.value = number_format(to_numeric(this.value));"  >\
                        </span>';
                    }
                	//end giap.ln
					jQuery('change_price_'+r_d+'_'+index).ForceNumericOnly();
                    /** Start: Daund them readonly cho lược đấu giá quá khứ */
                    if(to_numeric(in_date_old) < to_numeric(today_parse))
                    {
                        if(CAN_EDIT_PRICE_DN==false && ($('status_'+index).value =='CHECKIN'))
                        {
                            $('change_price_'+r_d+'_'+index).readOnly = true;
                            $('change_price_'+r_d+'_'+index).className = 'readonly';
                        }
                    }
                    /** END: Daund them readonly cho lược đấu giá quá khứ */ 
                }
                else
                {
                    
                    if(typeof(mi_reservation_room_arr)!='undefined' && typeof(mi_reservation_room_arr[$('id_'+index).value])!='undefined' && typeof(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'])!='undefined' && window.mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d]){// && !checked(KID CMT BOOK -> CHECKIN KHONG DOI LUOC DO GIA)
						
                        if(mi_reservation_room_arr[$('id_'+index).value]['change_price_closed_time_arr'] && mi_reservation_room_arr[$('id_'+index).value]['change_price_closed_time_arr'][r_d]){
							readOnly_ = 'readonly="readonly"';
						}else{
							readOnly_ = readOnly
						}
                        //giap.ln add readonly luoc do gia co su dung package tien phong
                        if(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d+'_is_package_room']==1)
                        {
                            readOnly_ = 'readonly="readonly"';
                        } 
                        //end 
                        
                        if(to_numeric(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d])>0){
                            /** manh **/
                            if(to_numeric($('price_'+index).value) == to_numeric(mi_reservation_room_arr[$('id_'+index).value]['price']))
                            {
                                $price = mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d];
                                
                            }   
                            /** end manh **/
                        }else{
                            $price = 0;
                        }
						if(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d+'_room_name']){
							room_name = mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d+'_room_name'];
                        }
                    }
                    if(check_date_price==1 && timeOUT<=timeOUT_DEFAULT){ // && n==1 && arrival_time == departure_time
                        }
					if(check_date_price_out == 1 && n==days && timeOUT>=timeOUT_DEFAULT){
						//$price = number_format(to_numeric($price)/2);
					}
                    if(totaldate==0 && timeIN>li_start_time && timeIN<li_end_time && LATE_CHECKIN_AUTO==1 && $('old_status_'+index).value=='BOOKED' && $('status_'+index).value=='CHECKIN'){
                        //$price=0;
                    }
                    if(mi_reservation_room_arr[$('id_'+index).value] && mi_reservation_room_arr[$('id_'+index).value]['arrival_time']==mi_reservation_room_arr[$('id_'+index).value]['departure_time'] && arrival_time!=departure_time)
                    {
                        $price = to_numeric(jQuery("#price_"+index).val());
                        
                    }

                    //console.log(mi_reservation_room_arr);
                    //console.log($('id_'+index).value);
                    //console.log(mi_reservation_room_arr[$('id_'+index).value]['change_price_arr'][r_d]);
                    $('price_schedule_'+index).innerHTML += '<span class="change-price-span">'+date_room+'-'+room_name+'<br><input  name="mi_reservation_room['+index+'][change_price_arr]['+r_d+']" type="text" id="change_price_'+r_d+'_'+index+'" value="'+number_format($price)+'"  onkeyup="FormatCurrency(this)" style="width:50px;" onchange="check_number();"  class="change-price-input change_price_'+r_d_2+' '+(readOnly_?' readonly':'')+'" '+readOnly_+'/>';
                    /** Start: Daund them readonly cho lược đấu giá quá khứ */
                    if(to_numeric(in_date_old) < to_numeric(today_parse))
                    {
                        if(CAN_EDIT_PRICE_DN==false && ($('status_'+index).value =='CHECKIN'))
                        {
                            $('change_price_'+r_d+'_'+index).readOnly = true;
                            $('change_price_'+r_d+'_'+index).className = 'readonly';
                        }
                    }
                    /** END: Daund them readonly cho lược đấu giá quá khứ */ 
                }
				total_date=count_date(r_d,currentDate);
				if(CAN_ADMIN_PRICE==false){
				    if(CAN_EDIT_PRICE==false)
                    {
                        if(CAN_ADD_PRICE==false)
                        {
                            $('change_price_'+r_d+'_'+index).readOnly = true;
                            $('change_price_'+r_d+'_'+index).className = 'readonly';
                        }
                        else
                        {
                            if($('status_'+index).value!='BOOKED')
                            {
                                $('change_price_'+r_d+'_'+index).readOnly = true;
                                $('change_price_'+r_d+'_'+index).className = 'readonly';
                            }
                        }
                    }
                    else
                    {
                        if($('status_'+index).value=='CHECKOUT')
                        {
                            $('change_price_'+r_d+'_'+index).readOnly = true;
                            $('change_price_'+r_d+'_'+index).className = 'readonly';
                        }
                    }
				}
				total_amount = to_numeric(total_amount) + to_numeric($('change_price_'+r_d+'_'+index).value);
			}
			$('price_schedule_bound_'+index).style.display = '';
			//alert(jQuery('#change-price-span'+index).size());
            if(!cPrS){
				$('invoice_'+index).style.display = '';
            }
		}else{
			$('price_schedule_bound_'+index).style.display = 'none';
             if(!cPrS){
				$('invoice_'+index).style.display = 'none';
            }
		}
		//----------------------------------------------------------------------------------------
		var std =arrival_time.split("/");
		var ed =departure_time.split("/");
		var std_second=Date.parse(std[1]+"/"+std[0]+"/"+std[2]);
		var ed_second=Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2]);
		if($('reduce_balance_'+index))
		{
			total_amount=to_numeric(total_amount*(1-to_numeric($('reduce_balance_'+index).value)/100));
		}
		if($('reduce_amount_'+index))
		{
			total_amount=to_numeric(total_amount) - to_numeric($('reduce_amount_'+index).value);
		}
		var total_amount_ = total_amount;
		var service_rate = 0;
		if($('service_rate_'+index))
		{
			service_rate=to_numeric(total_amount_*(to_numeric($('service_rate_'+index).value)/100));
			total_amount = to_numeric(total_amount) + to_numeric(service_rate);
		}
		if($('tax_rate_'+index))
		{
			var tax=to_numeric((total_amount_+service_rate)*(to_numeric($('tax_rate_'+index).value)/100));
			total_amount=to_numeric(total_amount)+to_numeric(tax);
		}
		if($('total_amount_'+index)){
			$('total_amount_'+index).value=number_format(roundNumber(total_amount,2));
		}
		if($('deposit_'+index))
			$('deposit_'+index).value=number_format(roundNumber(to_numeric($('deposit_'+index).value),2));
		//updateTotalPayment();
	}
}



function FormatCurrency(ctrl) {
    //Check if arrow keys are pressed - we want to allow navigation around textbox using arrow keys
    if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40)
    {
        return;
    }

    var val = ctrl.value;

    val = val.replace(/,/g, "")
    ctrl.value = "";
    val += '';
    x = val.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';

    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }

    ctrl.value = x1 + x2;
}

function updateTotalPayment(){
	$('total_payment').innerHTML = 0;
	for(var i=101;i<=input_count;i++){
		if($('total_amount_'+i)){
			$('total_payment').innerHTML = to_numeric($('total_payment').innerHTML) + to_numeric($('total_amount_'+i).value);
		//	$('total_payment').innerHTML = number_format(roundNumber($('total_payment').innerHTML,2));
		}
	}
}
function display_room_table(obj,current_id)
{
	//if($('selected_room').style.display=="none")
	//{
		$('selected_room').style.display="";
		$('selected_room').style.top=obj.offsetTop-10 + 'px';
		$('selected_room').style.left=obj.offsetLeft + 60 + 'px';
		$('selected_room').className=current_id;
		jQuery('#selected_room').css({'z-index':100});
		jQuery('#selected_room').css('padding-bottom','30px');
	//}
	asign_room();
}
function asign_room()
{
	$('rooms').innerHTML='\
			<div style="height:20px;background-color:#FF9900;border:1px solid #CCCCCC;text-align:center;margin:2px;color:#FFFFFF;font-weight:bold;text-transform:uppercase;padding:2px 0px 2px 0px;">SELECT ROOM</div>';
	if(all_forms['mi_reservation_room']){
		var columns=all_forms['mi_reservation_room'];
		for(var i in columns)
		{
			if($('status_'+columns[i]) && ($('status_'+columns[i]).value=='BOOKED' || $('status_'+columns[i]).value=='CHECKIN' || $('status_'+columns[i]).value=='CHECKOUT')){
				$('rooms').innerHTML+='<div id="'+($('room_id_'+columns[i]).value?$('room_id_'+columns[i]).value:$('room_name_'+columns[i]).value)+'-'+$('departure_time_'+columns[i]).value+'" onclick="$(\'room_level_\'+$(\'selected_room\').className).value=\''+$('room_level_'+columns[i]).value+'\';$(\'mi_traveller_room_name_\'+$(\'selected_room\').className).value=\''+$('room_name_'+columns[i]).value+'\';$(\'traveller_room_id_\'+$(\'selected_room\').className).value=this.id;$(\'selected_room\').style.display=\'none\';" style="display:block;border-bottom:1px solid #CCCCCC;text-align:center;background-color:#D2E9FF;cursor:pointer;width:99%;"><strong>'+$('room_level_'+columns[i]).value+'+'-'+</strong><strong>'+$('room_name_'+columns[i]).value+'</strong> <span style="font-size:10px;">('+$('arrival_time_'+columns[i]).value+'-'+$('departure_time_'+columns[i]).value+')</span></div>';
			}
		}
	}else if(mi_reservation_room_arr){
		var columns=mi_reservation_room_arr;
		for(var i in columns)
		{
            /** START - DAT_1662 sua truong hop them khach **/
			//console.log(columns);
            if(columns[i]['status']!='NOSHOW'){
                $('rooms').innerHTML+='<div id="'+(((columns[i]['room_id']!='')?columns[i]['room_id']:columns[i]['room_name'])+'-'+columns[i]['departure_time'])+'" onclick="$(\'room_level_\'+$(\'selected_room\').className).value=\''+columns[i]['room_level']+'\';$(\'mi_traveller_room_name_\'+$(\'selected_room\').className).value=\''+columns[i]['room_name']+'\';$(\'reservation_room_id_\'+$(\'selected_room\').className).value=\''+i+'\';$(\'traveller_room_id_\'+$(\'selected_room\').className).value=this.id;$(\'selected_room\').style.display=\'none\';" style="display:block;border-bottom:1px solid #CCCCCC;text-align:center;background-color:#D2E9FF;cursor:pointer;width:99%;"><strong>'+columns[i]['room_level']+'-'+'</strong><strong>'+columns[i]['room_name']+'</strong> <span style="font-size:10px;">('+columns[i]['arrival_time']+'-'+columns[i]['departure_time']+')</span></div>';
            }
            /** END - DAT_1662 sua truong hop them khach **/
		}
	}
	//var top = $('selected_room').offsetTop;
	//$('selected_room').style.bottom=(top+$('selected_room').offsetHeight+20)+ 'px';
}
function updateRoomForTraveller(index){
	for(var i=101;i<=input_count;i++){
		if($('traveller_room_id_'+i)){
			if($('room_id_old_'+index) && $('room_id_old_'+index).value){
				if($('traveller_room_id_'+i).value == $('room_id_old_'+index).value+'-'+$('departure_time_old_'+index).value){
					if($('room_id_'+index).value){
						$('traveller_room_id_'+i).value =  $('room_id_'+index).value+'-'+$('departure_time_'+index).value
						jQuery('#mi_traveller_room_name_'+i).attr('title',$('departure_time_'+index).value);
					}else{
						$('traveller_room_id_'+i).value = $('room_name_'+index).value+'-'+$('departure_time_'+index).value;
						jQuery('#mi_traveller_room_name_'+i).attr('title','...');
					}
					$('mi_traveller_room_name_'+i).value =  $('room_name_'+index).value;
				}
			}else{
				if($('mi_traveller_room_name_'+i).value == $('room_name_old_'+index).value){
					if($('room_id_'+index).value){
						$('traveller_room_id_'+i).value =  $('room_id_'+index).value+'-'+$('departure_time_'+index).value
						jQuery('#mi_traveller_room_name_'+i).attr('title',$('departure_time_'+index).value);
					}else{
						$('traveller_room_id_'+i).value = $('room_name_'+index).value+'-'+$('departure_time_'+index).value;
						jQuery('#mi_traveller_room_name_'+i).attr('title','...');
					}
					$('mi_traveller_room_name_'+i).value =  $('room_name_'+index).value;
				}
			}
		}
	}
	$('room_id_old_'+index).value = $('room_id_'+index).value;
	$('room_name_old_'+index).value = $('room_name_'+index).value;
	$('departure_time_old_'+index).value = $('departure_time_'+index).value;
}
function count_date(start_day, end_day)
{
	var std =start_day.split("/");
	var std_day=std[0];
	var std_month=std[1];
	var std_year=std[2];
//----------------------------
	var ed =end_day.split("/");
	var ed_day=ed[0];
	var ed_month=ed[1];
	var ed_year=ed[2];
//----------------------------
	var startDAY=std_month+"/"+std_day+"/"+std_year;
	var endDAY=ed_month+"/"+ed_day+"/"+ed_year;
	var std_second=Date.parse(startDAY);
	var ed_second=Date.parse(endDAY);
	return (ed_second-std_second)/86400000;
}
function count_all_price()
{
	var columns=all_forms['mi_reservation_room'];
	for(var i in columns)
	{
		count_price(columns[i],false);
	}
}
function contruct_elements()
{
	var columns=all_forms['mi_reservation_room'];
	for(var i in columns)
	{
		var timeIN=0;
		var time_ins=new Array();
		var timeOUT=0;
		var time_outs=new Array();
		var arrival_time = getElemValue('arrival_time_'+columns[i]);
		var departure_time =getElemValue('departure_time_'+columns[i]);
		var rate=1;
		var time_in = getElemValue('time_in_'+columns[i]);
		if(time_in!='')
		{
			time_ins =time_in.split(":");
			timeIN=to_numeric(time_ins[0])*60+to_numeric(time_ins[1]);
		}
		var time_out = getElemValue('time_out_'+columns[i]);
		if(time_out!='')
		{
			time_outs =time_out.split(":");
			timeOUT=to_numeric(time_outs[0])*60+to_numeric(time_outs[1]);
		}
		if($('status_'+columns[i]) && $('status_'+columns[i]).value=='BOOKED')
		{
			$('invoice_'+columns[i]).style.display='none';
		}
	}
}
function getRateList(id,roomLevelId,index,customerId,adult,child,startDate,endDate){
	if(adult<=0){
		alert('Miss adult quantity');
		jQuery('#adult_'+index).focus();
		return false;
	}
	if(roomLevelId){
		obj = $(id);
		if($('rate_list').style.display=="none")
        {
			$('rate_list').style.display="";
			$('rate_list').style.top=obj.offsetTop-20+'px';
			$('rate_list').style.left=obj.offsetLeft+20+'px';
			jQuery('#rate_list_result').html('Loading...');
			ajax.get_text('r_get_rate_list.php?room_level_id='+roomLevelId+'&index='+index+'&customer_id='+customerId+'&adult='+adult+'&child='+child+'&start_date='+startDate+'&end_date='+endDate, setRateList);
		}
	}else{
		//alert('You did not select room');
	}
}
function setRateList(text)
{
	jQuery('#rate_list_result').html(text);
}
function setRate(index,rate){
	$('price_'+index).value = number_format(rate);
	jQuery('#rate_list').hide();
	count_price(index,true);
}

/* commission */
function getCommissionList(id,index,customerId,startDate,endDate)
{
	if(customerId)
    {
		obj = $(id);
		if($('commission_list').style.display=="none")
        {
			$('commission_list').style.display="";
			$('commission_list').style.top=obj.offsetTop-20+'px';
			$('commission_list').style.left=obj.offsetLeft+20+'px';
			jQuery('#commission_list_result').html('Loading...');
			ajax.get_text('r_get_rate_commission_list.php?index='+index+'&customer_id='+customerId+'&start_date='+startDate+'&end_date='+endDate,setCommissionList);
		}
	}
    else
    {
		//alert('You did not select room');
	}
}
function setCommissionList(text){
	jQuery('#commission_list_result').html(text);
}
function setCommission(index,rate){
    jQuery('#commission_rate_'+index).val(number_format(rate));
	jQuery('#commission_list').hide();
}



function changeAllStatus(status,inputCount){
    
	for(var i=101;i<=inputCount;i++){
		if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
        {
            if($('status_'+i) && $('status_'+i).value != 'CHECKOUT' && $('status_'+i).value != 'CANCEL')
            {
    			if(status == 'CHECKIN' && $('status_'+i).value == 'BOOKED' && $('room_id_'+i).value)
                {
    				$('time_in_'+i).value = currentHour;
    				$('arrival_time_'+i).value = currentDate;
    				$('status_'+i).value = status;
    				count_price(i,true);
    			}
                else if(status == 'CHECKOUT' && $('status_'+i).value == 'CHECKIN')
                {
    				$('time_out_'+i).value = currentHour;
    				$('departure_time_'+i).value = currentDate;
    				$('status_'+i).value = status;
    				//if($('customer_id').value){updateAllDefCode('DEBIT')};
    			}
                if(status == 'CANCEL' && $('status_'+i).value == 'BOOKED')
                {
    				$('status_'+i).value = status;
    			}
    		}
            if(status == 'BOOKED' && $('status_'+i).value == 'CANCEL')
            {
                $('status_'+i).value = status;
                //giap.luunguyen add change all status
                count_price(i,false);
            }
            if(status == 'CANCEL')
            {
                count_price(i,false);
            }
            else
            {
                jQuery("#cancel_note_"+i).parent().css("display","none");
            }
            //END giap.luunguyen
        }
        
	}
}
function changeAllReservationType(value){
	for(var i=101;i<=input_count;i++){
	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
       {
            if($('room_level_id_'+i) && $('status_'+i) && ($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CHECKIN') || $('status_'+i).value == 'CHECKOUT'){
    			var pass = true;
    			if($('old_status_'+i) && $('old_status_'+i).value=='CHECKOUT'){
    				pass = false;
    			}
    			if(pass){
    				$('reservation_type_id_'+i).value = value;
    			}
    		}
       }
		
	}
}
function fun_change_all_ei(value)
{
    
    for(var i=101;i<=input_count;i++){
	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
       {
        //console.log(value);
            jQuery("#early_checkin_"+i).val(value);
       }
		
	}
}
function fun_change_all_lo(value)
{
    for(var i=101;i<=input_count;i++){
	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
       {
            jQuery("#late_checkout_"+i).val(value);
       }
		
	}
}
function fun_change_all_net()
{
    if(document.getElementById("change_all_net_price").checked==true)
    {
        for(var i=101;i<=input_count;i++){
    	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
           {
                //kimtan: them phan quyen cho o gia net giong het quyen sua gia
                var check_q = false;
                if(CAN_ADMIN_PRICE==true) // quyen admin cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
            	{	
            	    
                }else{
                    if(CAN_EDIT_PRICE==true){ // quyen edit cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
                        if(jQuery('#status_'+i).val()!='CHECKOUT')
                        {
                        }
                        else{
                            check_q = true;
                        }
                    }
                    else
                    {
                        if(CAN_ADD_PRICE==true){ // quyen add cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
                            if(jQuery('#status_'+i).val()=='BOOKED')
                            {
                            }
                            else{
                                check_q = true;
                            }
                        }
                        else
                        {
                            check_q = true;
                        }
                    }
                }
                if(check_q == false)
                //end kimtan: them phan quyen
                document.getElementById("net_price_"+i).checked=true;
           }
    	}
    }
    else
    {
        for(var i=101;i<=input_count;i++){
    	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
           {
                //kimtan: them phan quyen cho o gia net giong het quyen sua gia
                var check_q = false;
                if(CAN_ADMIN_PRICE==true) // quyen admin cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
            	{	
            	    
                }else{
                    if(CAN_EDIT_PRICE==true){ // quyen edit cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
                        if(jQuery('#status_'+i).val()!='CHECKOUT')
                        {
                        }
                        else{
                            check_q = true;
                        }
                    }
                    else
                    {
                        if(CAN_ADD_PRICE==true){ // quyen add cua nut Booked_Checkin_CheckOut_editPrice (554) dc quy dinh trong packages\core\includes\portal\header.php
                            if(jQuery('#status_'+i).val()=='BOOKED')
                            {
                            }
                            else{
                                check_q = true;
                            }
                        }
                        else
                        {
                            check_q = true;
                        }
                    }
                }
                if(check_q == false)
                document.getElementById("net_price_"+i).checked=false;
           }
    	}
    }
}
function changeAllCommissionRate(value){
 
	for(var i=101;i<=input_count;i++)
    {	
        if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
           {
                jQuery('#commission_rate_'+i).val(value);
           }
			
	}

}

function changeAllPaymentMethod(value){
	for(var i=101;i<=input_count;i++){
		if($('room_level_id_'+i) && $('status_'+i) && ($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CHECKIN') || $('status_'+i).value == 'CHECKOUT'){
			var pass = true;
			if($('old_status_'+i) && $('old_status_'+i).value=='CHECKOUT'){
				pass = false;
			}
			if(pass){
				$('def_code_'+i).value = value;
			}
		}
	}
}
/*function changeAllTime(value,type){
	for(var i=101;i<=input_count;i++){
	   if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
       {
            if($('room_level_id_'+i) && $('status_'+i) && ($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CHECKIN') || $('status_'+i).value == 'CHECKOUT'){
    			var pass = true;
    			if($('old_status_'+i) && $('old_status_'+i).value=='CHECKOUT'){
    				pass = false;
    			}
    			if(pass){
    				if(type == 'AT'){
    					$('arrival_time_'+i).value = value;
    				}else{
    					$('departure_time_'+i).value = value;
    					//updateRoomForTraveller(i);
    				}
    				total_date=count_date($('arrival_time_'+i).value,$('departure_time_'+i).value);
    				if(total_date<0)
    				{
    					//alert('Ngay` di phai lon hon ngay den...!');
    					//$('departure_time_'+i).value='';
    					//$('departure_time_'+i).focus();
    					//return;
    				}
    				if(type=='DT'){
    					for(var j=101;j<=input_count;j++){
    						if(jQuery('#traveller_room_id_'+j).val()!= 'undefined' && jQuery('#reservation_room_id_'+j).val()==jQuery('#id_'+i).val() && jQuery('#room_level_id_'+i).val() != 'undefined'){
    							if(jQuery('#status_'+j).val()=='CHECKIN'){
    								jQuery('#traveller_departure_date_'+j).val(jQuery('#departure_time_'+i).val());
    								jQuery('#departure_hour_'+j).val(jQuery('#time_out_'+i).val());
    							}
    						}
    					}
    				}
    			}
    			count_price(i,false);
    		}
       }
		
	}
}*/
/** oanh them **/
/*function changeTime(value,type){
	if(value!='')
    {
        for(var i=101;i<=input_count;i++){
	    if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
        {
            if($('room_level_id_'+i) && $('status_'+i) && ($('status_'+i).value == 'BOOKED' || $('status_'+i).value == 'CHECKIN') || $('status_'+i).value == 'CHECKOUT'){
        	var pass = true;
        	if($('old_status_'+i) && $('old_status_'+i).value=='CHECKOUT'){
        		pass = false;
        	}
            //console.log($('old_status_'+i).value);
            //console.log($('status_'+i).value);
            if(($('old_status_'+i).value == 'BOOKED' && $('status_'+i).value == 'CHECKIN') || ($('old_status_'+i).value == 'CHECKIN' && $('status_'+i).value == 'CHECKOUT'))
            {
                pass = false;
            }
        	if(pass){
        		if(type == 'TI'){
        			$('time_in_'+i).value = value;
        		}else{
        			$('time_out_'+i).value = value;
        		}
        	}
        	
            }
        }
	   }
    }
}*/
/** end oanh them **/
/** daund them */
function changeTime(value,type)
{
    var check_TI = true;
    var check_TO = true;
    if(value != '')
    {
    	for (var index = 101; index <= input_count; index++)
        {
            if(jQuery("#check_box_"+index +":checked").val() == 'on' || chec_box_tick(input_count)==true)
            {
                if($('old_status_'+index) && $('old_status_'+index).value == 'CHECKOUT' || $('old_status_'+index).value == 'CHECKIN' || $('old_status_'+index).value == 'CANCEL' || $('old_status_'+index).value == 'NOSHOW')
                {
                    check_TI = false;
                }else
                {
                    check_TI = true;
                }
                if($('old_status_'+index).value == 'CHECKOUT' || $('old_status_'+index).value == 'CANCEL' || $('old_status_'+index).value == 'NOSHOW')
                {
                    check_TO = false;                        
                }else
                {
                    check_TO = true;
                }
                if(check_TI == true && type == 'TI')
                {
                    var change_TI = value.split(':');
                    var time_out = jQuery('#time_out_'+index).val().split(':');
                    var arrival_date = jQuery('#arrival_time_'+index).val();
                    var departure_date  = jQuery('#departure_time_'+index).val();
                    if((change_TI > time_out) && (arrival_date == departure_date))
                    {
                        alert('Thời gian đến phải nhỏ hơn thời gian đi!' + ' #ROOM ' + jQuery('#room_name_'+index).val());
                        return;                        
                    }else
                    {
                        $('time_in_'+index).value = value;
                    }                 
                }
                if(check_TO == true && type == 'TO')
                {
                    var change_TO = value.split(':');
                    var time_in = jQuery('#time_in_'+index).val().split(':');
                    var arrival_date = jQuery('#arrival_time_'+index).val();
                    var departure_date  = jQuery('#departure_time_'+index).val();
                    if((change_TO < time_in) && (arrival_date == departure_date))
                    {
                        alert('Thời gian đi phải lớn hơn thời gian đến!' + ' #ROOM ' + jQuery('#room_name_'+index).val());
                        return;                        
                    }else
                    {
                        $('time_out_'+index).value = value;
                    }                       
                }           
            }
        }        
    }
}
function changeAllTime(value,type)
{
    var check_TI = true;
    var check_TO = true;
    if(value != '')
    {
    	for (var index = 101; index <= input_count; index++)
        {
            if(jQuery("#check_box_"+index +":checked").val() == 'on' || chec_box_tick(input_count)==true)
            {
                if($('old_status_'+index) && $('old_status_'+index).value == 'CHECKOUT' || $('old_status_'+index).value == 'CHECKIN' || $('old_status_'+index).value == 'CANCEL' || $('old_status_'+index).value == 'NOSHOW')
                {
                    check_TI = false;
                }else
                {
                    check_TI = true;
                }
                if($('old_status_'+index).value == 'CHECKOUT' || $('old_status_'+index).value == 'CANCEL' || $('old_status_'+index).value == 'NOSHOW')
                {
                    check_TO = false;                        
                }else
                {
                    check_TO = true;
                }
                if(check_TI == true && type == 'AT')
                {
                    var change_date = value.split('/');
                    var newChange_date = change_date[1] + "/" + change_date[0] + "/" + change_date[2];
                    var change_date_str = new Date(newChange_date).getTime();
                    var departure_date  = jQuery('#departure_time_'+index).val();
                    departure_date = departure_date.split('/');
                    var newDeparture_date = departure_date[1] + "/" + departure_date[0] + "/" + departure_date[2];
                    var departure_date_str = new Date(newDeparture_date).getTime();
                    if(change_date_str > departure_date_str)
                    {
                        alert('Ngày đến phải nhỏ hơn ngày đi!' + ' #ROOM ' + jQuery('#room_name_'+index).val());
                        jQuery('#all_arrival_time').val('');
                        return;
                    }else
                    {
                        $('arrival_time_'+index).value = value; 
                        count_price(index, false);   
                    }                
                }
                if(check_TO == true && type == 'DT')
                {
                    var change_date = value.split('/');
                    var newChange_date = change_date[1] + "/" + change_date[0] + "/" + change_date[2];
                    var change_date_str = new Date(newChange_date).getTime();
                    var arrival_date = jQuery('#arrival_time_'+index).val();
                    arrival_date = arrival_date.split('/');
                    var newArrival_date = arrival_date[1] + "/" + arrival_date[0] + "/" + arrival_date[2];
                    var arrival_date_str = new Date(newArrival_date).getTime();
                    if(change_date_str < arrival_date_str)
                    {
                        alert('Ngày đi phải lớn hơn ngày đến!' + ' #ROOM ' + jQuery('#room_name_'+index).val());
                        jQuery('#all_departure_time').val('');
                        return;
                    }else
                    {
                        $('departure_time_'+index).value = value;
                        count_price(index, false);
                    }                    
                }           
            }
        }        
    }        
}

function func_chang_foc_group()
{
    if(document.getElementById("foc_group").checked==true)
    {
        for (var index = 101; index <= input_count; index++)
        {
            if(jQuery("#check_box_"+index +":checked").val() == 'on' || chec_box_tick(input_count)==true)
            {
                jQuery('#foc_'+index).val('FOC');
            }
        }        
    }else
    {
        for (var index = 101; index <= input_count; index++)
        {
            if(jQuery("#check_box_"+index +":checked").val() == 'on' || chec_box_tick(input_count)==true)
            {
                jQuery('#foc_'+index).val('');
            }
        }         
    }    
}

function func_chang_foc_all_group()
{
    if(document.getElementById("foc_all_group").checked==true)
    {
        for (var index = 101; index <= input_count; index++)
        {
            if(jQuery("#check_box_"+index +":checked").val() == 'on' || chec_box_tick(input_count)==true)
            {
                document.getElementById("foc_all_"+index).checked=true;
            }
        }           
    }else
    {
        for (var index = 101; index <= input_count; index++)
        {
            if(jQuery("#check_box_"+index +":checked").val() == 'on' || chec_box_tick(input_count)==true)
            {
                document.getElementById("foc_all_"+index).checked=false;
            }
        }  
    }    
}
/* end daund them */
function updateTimeTraveller(i){
	for(var j=101;j<=input_count;j++){
		if(jQuery('#traveller_room_id_'+j).val()!= 'undefined' && jQuery('#reservation_room_id_'+j).val()==jQuery('#id_'+i).val() && jQuery('#room_level_id_'+i).val() != 'undefined'){
			if(jQuery('#status_'+j).val()=='CHECKIN'){
				jQuery('#traveller_departure_date_'+j).val(jQuery('#departure_time_'+i).val());
				jQuery('#departure_hour_'+j).val(jQuery('#time_out_'+i).val());
			}
		}
	}
}
function changeAllDiscount(val){
	if(is_numeric(val) && val<=100){
		for(var i=101;i<=input_count;i++)
        {
			if(document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true)
            {
                if($('foc_'+i).checked==false && $('foc_'+i).value =='' && $('status_'+i).value != 'CHECKOUT'){
    				$('reduce_balance_'+i).value = val;
    			}
            }
		}
	}
    else
    {
		alert('[[.this_number_is_not_greater_than_100.]]');
		$('discount_for_group').value=val.substr(0,val.length-1);
		return false;
	}
}

function copyRoom(rooms,quantity,index_input_count){
	if(quantity>0){
		var roomArr = rooms.split(',');
		var max = 0
                    for(i=101;i<=index_input_count;i++)
                    {
                        var room_name = jQuery('#room_name_'+i).val();
                        if(room_name)
                        {
                            room_name = room_name.split('#');
                            if(room_name[1]!='undefined')
                            {
                                if(to_numeric(room_name[1])>to_numeric(max))
                                {
                                    max = room_name[1];
                                }
                            }
                        }
                    }
        max=to_numeric(max)+1;
        for(i in roomArr){
			if(!isNaN(roomArr[i])){
				index = roomArr[i];
				if($('index_'+index))
				{
				    var roomCount = to_numeric($('count_number_of_room').innerHTML);
					for(i=1;i<=quantity;i++){
						mi_init_row('mi_reservation_room',mi_reservation_room_arr[$('id_'+index).value]);
						$('index_'+input_count).innerHTML = input_count;
						$('id_'+input_count).value = '';
						var room_name = $('room_name_'+index).value;
						if(room_name.match(/(\#[0-9a-zA-Z]+)/)){
							$('room_name_'+input_count).value = '#'+max;
							$('room_name_old_'+input_count).value = '#'+max;
						}else{
							$('room_name_'+input_count).value = room_name;
						}
                        $('room_name_'+input_count).value = '#'+max;
						$('room_name_old_'+input_count).value = '#'+max;
                        $('room_id_'+input_count).value = '';
                        $('status_'+input_count).value = 'BOOKED'; 
                        removeOptionSelected($('status_'+input_count),'CHECKIN'); 
                        roomCount=roomCount+1;
                        max=to_numeric(max)+1;
					}
                    $('count_number_of_room').innerHTML = roomCount;
				}
			}
		}
		updateAll();
	}else{
		alert('Miss room quantity');
		jQuery('#room_quantity').focus();
	}
}
function copyTraveller(travellers,quantity){
	if(quantity>0){
		var travellerArr = travellers.split(',');
		for(i in travellerArr){
			if(!isNaN(travellerArr[i])){
				index = travellerArr[i];
				if($('passport_'+index)){
					for(i=1;i<=quantity;i++){
						mi_init_row('mi_traveller',mi_traveller_arr[$('id_'+index).value]);
						$('index_'+input_count).innerHTML = input_count;
						$('id_'+input_count).value = '';
					}
				}
			}
		}
	}else{
		alert('Miss traveller quantity');
		jQuery('#traveller_quantity').focus();
	}
}
function depositForGroup(value){
	for(var i=101;i<=input_count;i++){
		if($('deposit_'+i)){
			$('deposit_'+i).value = roundNumber(to_numeric(value)/to_numeric($('count_number_of_room').innerHTML),2);
			if(!$('deposit_date_'+i).value){
				$('deposit_date_'+i).value = currentDate;
			}
		}
	}
}
function getRoomIndexById(value){// Made on: 21/10/2011
	var temp = value.split('-');
	var roomId = temp[0];
	var departureDate = temp[1];
	var returnValue = false;
	for(var i=101;i<=input_count;i++){
		if($('room_id_'+i) && $('room_id_'+i).value){
			if($('room_id_'+i).value == roomId && $('departure_time_'+i).value == departureDate){
				returnValue = i;
			}
		}
	}
	return returnValue;
}
function checkInput(){
	for(var i=101;i<=input_count;i++){
		if($('status_'+i) && ($('status_'+i).value == 'CHECKOUT')){
			/*if(!$('def_code_'+i).value){
				jQuery('#mask').hide();
				alert('Ban chua chon phuong thuc thanh toan');
				$('def_code_'+i).focus();
				return false;
			}*/
		}
		if($('status_'+i) && ($('status_'+i).value == 'CHECKOUT' || $('status_'+i).value == 'CHECKIN')){
			if(!$('reservation_type_id_'+i).value){
				jQuery('#mask').hide();
				alert('Ban chua chon loai Res');
				$('reservation_type_id_'+i).focus();
				return false;
			}
		}
	}
}
// New update 26/12/2012
function init_traveller_action(traveller_input_count)
{
	myAutocomplete(traveller_input_count);
	get_traveler_sugges(traveller_input_count);
	//jQuery('#birth_date_'+(traveller_input_count)).mask('99/99/9999');
	if(jQuery('#expire_date_of_visa_'+traveller_input_count)){
		jQuery("#expire_date_of_visa_"+traveller_input_count).datepicker();
	}
	if(jQuery('#arrival_hour_'+traveller_input_count)){
		jQuery("#arrival_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#departure_hour_'+traveller_input_count)){
		jQuery("#departure_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#traveller_arrival_date_'+traveller_input_count)){
		jQuery("#traveller_arrival_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#traveller_departure_date_'+traveller_input_count)){
		jQuery("#traveller_departure_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#flight_arrival_hour_'+traveller_input_count)){
		jQuery("#flight_arrival_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#flight_arrival_date_'+traveller_input_count)){
		jQuery("#flight_arrival_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#flight_departure_hour_'+traveller_input_count)){
		jQuery("#flight_departure_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#flight_departure_date_'+traveller_input_count)){
		jQuery("#flight_departure_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#checkout_guest_'+traveller_input_count) && !jQuery('#reservation_traveller_id_'+traveller_input_count).val())
	{
		jQuery('#checkout_guest_'+traveller_input_count).css('display','none');
		jQuery('#change_guest_'+traveller_input_count).css('display','none');
	}
}
function get_traveler_sugges(traveller_input_count){
	jQuery("#passport_"+traveller_input_count).autocomplete({
		url:'get_traveller.php',
		minChars: 0,
		width: 280,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return ' <span> ' + row.name + '</span>';
		},
		formatMatch: function(row, i, max) {
			return row.name;
		},
		formatResult: function(row) {
			return row.name;
		},
		onItemSelect: function(item) {
			get_traveller(traveller_input_count);
		}
	});
}
function change_all_traveller_level()
{
	for(var i=101;i<=input_count;i++){
		if(jQuery("#traveller_id_"+i)){
			jQuery('#traveller_level_id_'+i).val(jQuery('#change_all_traveller_id').val());
		}
	}
}
function check_early_checkin(){
	for(var i=101;i<=input_count;i++){
		if(jQuery("#early_checkin_"+i) && jQuery('#early_checkin_'+i).attr('checked')==true){
			jQuery('#early_checkin_'+i).attr('checked',false);
		}else{
			jQuery('#early_checkin_'+i).attr('checked',true);
		}
	}
}
function checkSubmit()
{
	for(var j=101;j<=input_count;j++)
	{
		if($('traveller_room_id_'+j))
		{
			alert($('traveller_room_id_'+j).value);
		}
	}
}
function myAutocomplete(index)
{
	if($("nationality_id_"+index)!=null){
	jQuery("#nationality_id_"+index).autocomplete({
		url:'r_get_countries.php',
        	onItemSelect: function(item) {
			updateNationality(index);
		},
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {
			return row.id;
		}
	});
	}
}
function buildRateList(index){
	if($('status_'+i) && $('customer_name').value){
		//jQuery('#price_'+i).attr('readonly',true);
		//$('price_'+i).className = 'readonly';
	}
	if(jQuery('#rate_list_'+index) && jQuery('#room_level_id_'+index)){
		jQuery('#rate_list_'+index).click(function(){
			var i = this.id.substr(10);
			var customerId = jQuery('#customer_id').val();
			var roomLevelId = jQuery("#room_level_id_"+i).val();
			var adult = jQuery("#adult_"+i).val();
			var child = jQuery("#child_"+i).val();
			var startDate = jQuery("#arrival_time_"+i).val();
			var endDate = jQuery("#departure_time_"+i).val();
			getRateList(jQuery(this).attr('id'),roomLevelId,i,customerId,adult,child,startDate,endDate);
		});
	}
}
function buildCommissionList(index)
{
	if(jQuery('#commission_list_'+index)){
		jQuery('#commission_list_'+index).click(function(){
			var i = this.id.substr(10);
			var customerId = jQuery('#customer_id').val();
			var startDate = jQuery("#arrival_time_"+i).val();
            //alert(startDate);
			var endDate = jQuery("#departure_time_"+i).val();
			getCommissionList(jQuery(this).attr('id'),i,customerId,startDate,endDate);
		});
	}
}
function checkRoomOut(room_id){
	$return = false;
	if(room_id){
		temp_arr = room_id.split('-');
		for(var i=101;i<=input_count;i++){
			if($('id_'+i) && $('id_'+i).value && $('room_id_'+i) && $('room_id_'+i).value == temp_arr[0] && $('departure_time_'+i).value == temp_arr[1]){
				if($('status_'+i) && $('status_'+i).value == 'CHECKOUT'){
					$return = true;
				}
			}
		}
	}
	return $return;
}
function expandShorten(id)
{
	if($('expand_'+id)){
		if($('expand_'+id).innHTML=='')
		{
			$('expand_'+id).innHTML='+';
			jQuery('#mi_reservation_room_sample_'+id).hide();
			$('expand_img_'+id).src='packages/core/skins/default/images/buttons/node_close.gif';
		}
		else
		{
			$('expand_'+id).innHTML='';
			jQuery('#mi_reservation_room_sample_'+id).slideDown(1000);
			$('expand_img_'+id).src='packages/core/skins/default/images/buttons/node_open.gif';
		}
	}
}
function expandShortenTraveller(id)
{
	if($('expand_guest_'+id)){
		if($('expand_guest_'+id).innHTML=='')
		{
			$('expand_guest_'+id).innHTML='+';
			jQuery('#extra_guest_'+id).hide();
			$('expand_guest_img_'+id).src='packages/core/skins/default/images/buttons/node_close.gif';
		}
		else
		{
			$('expand_guest_'+id).innHTML='';
			jQuery('#extra_guest_'+id).slideDown(100);
			$('expand_guest_img_'+id).src='packages/core/skins/default/images/buttons/node_open.gif';
		}
	}
}
function expandAll(){
	if($('expand_all_span').innHTML=='')
	{
		$('expand_all_span').innHTML='+';
		$('expand_all').src='packages/core/skins/default/images/buttons/node_close.gif';
		for(var i=101;i<=input_count;i++){
			if($('expand_img_'+i)){
				expandShorten(i);
			}
		}
	}
	else
	{
		$('expand_all_span').innHTML='';
		$('expand_all').src='packages/core/skins/default/images/buttons/node_open.gif';
		for(var i=101;i<=input_count;i++){
			if($('expand_img_'+i)){
				expandShorten(i);
			}
		}
	}
}
function Check_Availblity(index,input_count)
{
    if( (document.getElementById('do_not_move_'+index).checked || jQuery("#do_not_move_"+index).attr('checked')=='checked') && jQuery("#room_id_"+index).val()!='' )
    {
        if($('user_do_not_move_'+index).value!='')
            alert('Phong dang giu boi TK: '+$('user_do_not_move_'+index).value+' \n Ly do: \n '+$('note_do_not_move_'+index).value+'\n khong duoc doi phong');
        else
            alert('Ban dang giu phong \n Ly do: \n '+$('note_do_not_move_'+index).value+' \n khong duoc doi phong');
    }
    else
    {
    var room_level_name = jQuery('#room_level_name_'+index).val();
    var price = jQuery('#price_'+index).val();
    var usd_price = jQuery('#usd_price_'+index).val();
	var string = '?page=room_map&cmd=select&act=check_availability&object_id='+index+'&input_count='+input_count+'&price='+price+'&room_level_name='+room_level_name+'&usd_price='+usd_price;
	if($('time_in_'+index) && ($('time_in_'+index).value!='')){
		string += '&time_in='+$('time_in_'+index).value;
	}
	if($('arrival_time_'+index) && ($('arrival_time_'+index).value!='')){
		string += '&from_date='+$('arrival_time_'+index).value;
	}
	if($('time_out_'+index) && ($('time_out_'+index).value!='')){
		string += '&time_out='+$('time_out_'+index).value;
	}
	if($('departure_time_'+index) && ($('departure_time_'+index).value!='')){
		string += '&to_date='+$('departure_time_'+index).value;
	}
	if($('id_'+index) && ($('id_'+index).value!='')){
		string += '&r_r_id='+$('id_'+index).value;
	}
    string += '&type_check=single';
    /**
    Kimtan them cai nay de gan #max+1 cho phong chua gan phong tang dan len ko phu thuoc vao input_count.
    cai nay truyen sang module RoomMap trong layout room_availabiliti.php->ham:selectRoomLevel()
    **/
    var max = 0
    for(i=101;i<=input_count;i++)
    {
        var room_name = jQuery('#room_name_'+i).val();
        if(room_name)
        {
            room_name = room_name.split('#');
            if(room_name[1]!='undefined')
            {
                if(to_numeric(room_name[1])>to_numeric(max))
                {
                    max = room_name[1];
                }
            }
        }
    }
    string += '&max='+max;
    //end Kimtan them
	window.open(string,'select_room');
    }
	return false;
}
function fun_Check_Availblity(input_count,cmd)
{
    var check=false;
    var string = '?page=room_map&cmd=select&act=check_availability&type_check=group&input_count='+input_count;
    var object_id='';
    var r_r_id='';
    var time_min = 0;
    var time_max = 0;
    var date_min = '';
    var date_max = '';
    var count = 0;
    for(var i=101;i<=input_count;i++)
    {
	   if( (document.getElementById("check_box_"+i).checked==true || chec_box_tick(input_count)==true) && (jQuery("#status_"+i).val()=='BOOKED' || cmd=='add') && ( !document.getElementById("do_not_move_"+i).checked || jQuery("#do_not_move_"+i).attr('checked')=='' || jQuery("#room_id_"+i).val()=='' ) )
       {
            check=true;
            if(time_min==0 || time_max==0 || date_min=='' || date_max=='')
            {
                time_min = jQuery('#time_in_'+i).val();
                time_max = jQuery('#time_out_'+i).val();
                date_min = jQuery('#arrival_time_'+i).val();
                date_max = jQuery('#departure_time_'+i).val();
            }
            else
            {
                if( convert_time(time_min)>convert_time(jQuery('#time_in_'+i).val()) )
                    time_min = jQuery('#time_in_'+i).val();
                if( convert_time(time_max)<convert_time(jQuery('#time_out_'+i).val()) )
                    time_max = jQuery('#time_out_'+i).val();
                totaldate=count_date(date_min,jQuery('#arrival_time_'+i).val());
                if(totaldate>0)
                {
                    date_min = jQuery('#arrival_time_'+i).val();
                }
                totaldate=count_date(date_max,jQuery('#departure_time_'+i).val());
                if(totaldate<0)
                {
                    date_max = jQuery('#departure_time_'+i).val();
                }
                
            }
            
            if(object_id=='')
                object_id = i;
            else
                object_id += "_"+i;
            if(jQuery('#id_'+i).val()!='')
            {
                if(r_r_id=='')
                    r_r_id =  jQuery('#id_'+i).val();
                else
                    r_r_id += "_"+jQuery('#id_'+i).val();
            }
            count ++;
       }
	}
    string += '&time_in='+time_min;
    string += '&time_out='+time_max;
    string += '&from_date='+date_min;
    string += '&to_date='+date_max;
    string += '&object_id='+object_id;
    string += '&r_r_id='+r_r_id;
    string += '&count='+count;
    string += '&get_cmd='+cmd;
    
    if(check==true)
    {
        window.open(string,'select_room');
        return false;
    }
    else
    {
        alert('[[.select_room_level.]]! ');
        return false;
    }
}
function convert_time(time)
{
    var x = time.split(":");
    return (to_numeric(x[0])*3600+to_numeric(x[1])*60);
}
function chec_box_tick(input_count)
{
    var check=true;
    var tick=0;
    var none_tick=0;
    for(var i=101;i<=input_count;i++)
    {
        if(document.getElementById("check_box_"+i).checked==true)
            tick++;
        else
            none_tick++;
    }
    if(tick!=0 && none_tick!=0)
    {
        check=false;
    }
    return check;
}
function fomat_day_month(number)
{
    if(to_numeric(number)<10)
    {
        number = '0'+to_numeric(number);
    }
    return number;
}
