function getLayout(date){
    var html='<h5>Booking date <input type="text" name="date" id="date" value='+date+' style="width:100px" onchange="changeDate()" /></h5>'+
        '<table id="table-row" class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">'+
          '<tr>'+
            '<th style="border: none; border-bottom: 2px solid black;">Reservation #</th>'+
            '<th style="border: none; border-bottom: 2px solid black;">BookingID </th>'+
            '<th style="border: none;border-bottom: 2px solid black;">Guest Name</th>'+
            '<th style="border: none;border-bottom: 2px solid black;">Channel</th>'+
            '<th style="border: none;border-bottom: 2px solid black;">Check-in</th>'+
            '<th style="border: none;border-bottom: 2px solid black;">Check-out</th>'+
          '</tr>';
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'getLayout',date:date},
     success:function(result)
     {   var content=JSON.parse(result);
         for(var i in content){
            html+='<tr>'+
                '<td><a target="_blank" href="?page=reservation&cmd=edit&id='+content[i]['resservation_id']+'&portal=default">#'+content[i]['resservation_id']+'</a></td>'+
                '<td><a target="_blank" href="?page=reservation&cmd=edit&id='+content[i]['resservation_id']+'&portal=default">'+content[i]['booking_id']+'</a></td>'+
                '<td>'+content[i]['first_name']+' '+content[i]['last_name']+'</td>'+
                '<td>'+content[i]['customer_id']+'</td>'+
                '<td>'+content[i]['check_in']+'</td>'+
                '<td>'+content[i]['check_out']+'</td>'+
            '</tr>';
         }
         html+='</table><br/>';
         jQuery('#content').html(html);
         jQuery('#date').datepicker();
         setTimeout(function(){
         }, 1000);
     }
    }); 
}
function changeDate(){
    getLayout(jQuery('#date').val());
}
/** ==================================================================================================================================================================================== **/
function getRoomLayoutContent(){
    jQuery('#content').html('<div id="loader"><div class="loader"></div></div>');
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'getRoom'},
     success:function(result)
     {   var content=JSON.parse(result);
         var html='<h3><b>Room Mapping</b></h3>'
         +'<table id="table-room" class="w3-table w3-bordered" style="width:400px">'
         +'<tr>'
            +'<th nowap><h4>Newway Room Level</h4></th>'
            +'<th><h4>HLS Room</h4></th>'
          +'</tr>';
         for(var i in content['newwayRooms']){
            var selected='';
            html+='<tr><td>'+content['newwayRooms'][i]['brief_name']+'</td><td><select name='+content['newwayRooms'][i]['id']+'><option value="">Choose room</option>';
            for( var j in content['hlsRooms']){
                if(content['newwayRooms'][i]['hotellink_room_id']==content['hlsRooms'][j]['RoomId']){
                    selected='selected';
                }else{
                    selected='';
                }
                html+='<option value='+content['hlsRooms'][j]['RoomId']+' '+selected+'>'+content['hlsRooms'][j]['Name']+'</option>';
            }
            html+='</td></tr>';
         }
         html+='</table><br/><input type="button" class="w3-btn w3-blue" value="Save" onclick="synchronizeRoom()"><br/><br/>';
         jQuery('#content').html(html);
         setTimeout(function(){
         }, 1000);
     }
     });
}
/** =======================================================================================================Room============================================================================= **/
function synchronizeRoom(){
    var rooms = jQuery('select').serializeArray();
    var availability = jQuery('.availability').serializeArray();
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'updateRoom',data:rooms,availability:availability},
     success:function(result)
     {
         setTimeout(function(){
         }, 1000);
     }
     });
     getRoomLayoutContent();
}
/** =======================================================================================================Customer============================================================================= **/
function getCustomerLayoutContent(){
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'getCustomer'},
     success:function(result)
     {   var content=JSON.parse(result);
         var html='<h3><b>Customer Mapping</b></h3><input type="button" class="w3-button w3-white w3-border" value="Save" onclick="synchronizeCustomer()">'
         +'<table id="table-room" style="min-width:500px">'
         +'<tr>'
            +'<th><h4>HLS Channel</h4></th>'
            +'<th><h4>Newway Customer</h4></th>'
          +'</tr>';
         for(var i in content['hlsChannel']){
            var selected='';
            html+='<tr><td>'+content['hlsChannel'][i]['name']+'</td><td><select class="w3-select w3-light-grey" name='+content['hlsChannel'][i]['id']+'><option value="">Choose Customer</option>';
            for( var j in content['newwayCustomer']){
                if(content['hlsChannel'][i]['newway_customer_id']==content['newwayCustomer'][j]['id']){
                    selected='selected';
                }else{
                    selected='';
                }
                html+='<option value='+content['newwayCustomer'][j]['id']+' '+selected+'>'+content['newwayCustomer'][j]['name']+'</option>';
            }
            html+='</select></td></tr>';
         }
         html+='</table><br/></p>';
         jQuery('#content').html(html);
         setTimeout(function(){
         }, 1000);
     }
     });
}
function synchronizeCustomer(){
    var customers = jQuery('select').serializeArray();
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'updateCustomer',data:customers},
     success:function(result)
     {
         setTimeout(function(){
         }, 1000);
     }
     }).done(function() {
        getCustomerLayoutContent();
        alert('Done!');
    }).fail(function() {
        alert('Fail!');
    });
}
/** ===================================================================================================Availability================================================================================= **/
function getAvailability(monthView,yearView){
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'availability',month:monthView,year:yearView},
     success:function(result)
     {
        var content=JSON.parse(result);
        //console.log(content);
        var month=content['month'];
        var year=content['year'];
         var html='<table><tr><td><p>Month</p><select onchange="viewOtherMonth(\'availability\')" class="month_option" id="month_option_availability"  name="month_option_availability" style="width:100px"></select></td><td><p>Change Availability to</p><input id="change_availability_input" name="change_availability_input" oninput="changeValue()" class="w3-input w3-border" type="text"></td><td valign="bottom"><p></p><input id="change_availability" type="button" value="Save" onclick="updateAvailability('+month+','+year+')"></td></tr></table><p></p>'
         +'<table id="table-availability">';
         html+='<tr style="background-color:#D3D3D3"><th></th>';
         for(var i in content['date']){
            html+='<td data-check_select_all="0" id="date_'+i+'" class="'+content['date'][i]['class_th']+'" onclick="setColumn(\''+i+'\')">'+content['date'][i]['date']+'<br>'+content['date'][i]['day_of_week']+'</td>';
         }
         html+='</tr>';
         for(var j in content['newwayRooms']){
            html+=' <tr><td style="text-align: left !important" data-check_select_all="0" id="'+content['newwayRooms'][j]['brief_name']+'" onclick="setRow(\''+content['newwayRooms'][j]['brief_name']+'\')">'+content['newwayRooms'][j]['brief_name']+'</td>';
            for(var i in content['date']){
                var total='';
                var check_availability='';
                if(''+i+'_'+content['newwayRooms'][j]['hotellink_room_id']+'' in content['availability']){
                   total=content['availability'][''+i+'_'+content['newwayRooms'][j]['hotellink_room_id']+''];
                }
                if(total==''){
                    check_availability='empty-data';
                }
                html+='<td onmousedown="cancel(event,\''+content['newwayRooms'][j]['hotellink_room_id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onclick="selectThisBox(\''+content['newwayRooms'][j]['hotellink_room_id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onmouseover="selectBox(\''+content['newwayRooms'][j]['hotellink_room_id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" class="'+content['date'][i]['class']+' date_'+i+' '+content['newwayRooms'][j]['brief_name']+' '+check_availability+'" id="'+content['newwayRooms'][j]['hotellink_room_id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><input type="hidden" name="'+content['newwayRooms'][j]['hotellink_room_id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><span>'+total+'</span></td>';
            }
            html+='</tr>';
         }
         jQuery('#availability').html(html);
         jQuery('#month_option_availability').html(content['month_option']);
         jQuery('#table-availability').on('contextmenu', 'td', function(e){ return false; });
         setTimeout(function(){
         }, 1000);
     }
     });
     
}
function cancel(event,id){
    event.preventDefault();
    if (event.which == 3) {
        jQuery('#'+id).removeClass('box-select');
    }
}
function setRow(row){
    if(jQuery('#'+row).data('check_select_all')==0){
        jQuery('.'+row).addClass('box-select');
        jQuery('#'+row).data('check_select_all',1);
    }else{
        jQuery('.'+row).removeClass('box-select');
        jQuery('#'+row).data('check_select_all',0);
    }
}
function setColumn(row){
    if(jQuery('#date_'+row).data('check_select_all')==0){
        jQuery('.date_'+row).addClass('box-select');
        jQuery('#date_'+row).data('check_select_all',1);
    }else{
        jQuery('.date_'+row).removeClass('box-select');
        jQuery('#date_'+row).data('check_select_all',0);
    }
}
function updateAvailability(month,year){
    boxChange=jQuery('.box-select input').serializeArray();
    jQuery.ajax({
    url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'updateAvailability',data:boxChange,month:month,year:year},
     success:function(result)
     {
         getAvailability(month,year);
         setTimeout(function(){
         }, 1000);
     }
    });
}
function selectBox(obj){
    jQuery(document).mousedown(function() {
    isDown = true;      // When mouse goes down, set isDown to true
      })
      .mouseup(function() {
        isDown = false;    // When mouse goes up, set isDown to false
      });
    if(isDown){
        jQuery('#'+obj).addClass('box-select');
    }
}
function selectThisBox(obj){
    if(jQuery('#'+obj).hasClass('box-select')){
        jQuery('#'+obj).removeClass('box-select');
    }else{
        jQuery('#'+obj).addClass('box-select');
    }  
}
function changeValue(obj){
    jQuery('.box-select span').html(jQuery('#change_availability_input').val());
    jQuery('.box-select input:first-child').val(jQuery('#change_availability_input').val());
}
function viewOtherMonth(type){
    console.log(type);
    time=jQuery('#month_option_'+type).val();
    monthview=time.split("-");
    if(type=='rate'){
        getRates(monthview[0],monthview[1]);
    }else if(type=='extra-adult'){
        getExtraAdult(monthview[0],monthview[1]);
    }else if(type=='stop-sell'){
        getStopSellForm(monthview[0],monthview[1]);
    }
    else{
        getAvailability(monthview[0],monthview[1]);
    }
}
/** ===================================================================================================Rates================================================================================ **/
function getRates(monthView,yearView){
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'rates',month:monthView,year:yearView},
     success:function(result)
     {
        var content=JSON.parse(result);
        var month=content['month'];
        var year=content['year'];
         var html='<table><tr><td><p>Month</p><select onchange="viewOtherMonth(\'rate\')" class="month_option" id="month_option_rate"  name="month_option_rate" style="width:100px"></select></td><td><p>Change Rates to</p><input id="change_availability_input" name="change_availability_input" oninput="changeValue()" class="w3-input w3-border" type="text"></td><td valign="bottom"><p></p><input id="change_availability" type="button" value="Save" onclick="updateRates('+month+','+year+')"></td></tr></table><p></p>'
         +'<table id="table-rates">';
         for(var j in content['newwayRooms']){
            html+='<tr style="background-color:#D3D3D3;"><th>'+content['newwayRooms'][j]['brief_name']+'</th>';
            for(var i in content['date']){
                html+='<td data-check_select_all="0" id="date_'+i+'" class="'+content['date'][i]['class_th']+'" onclick="setColumn(\''+i+'\')">'+content['date'][i]['date']+'<br>'+content['date'][i]['day_of_week']+'</td>';
            }
            html+='</tr>';
            for(var k in content['newwayRooms'][j]['plans']){
                html+='<tr><td style="text-align: left !important" data-check_select_all="0" id="'+content['newwayRooms'][j]['plans'][k]['id']+'" onclick="setRow(\''+content['newwayRooms'][j]['plans'][k]['id']+'\')">'+content['newwayRooms'][j]['plans'][k]['name']+'</td>';
                for(var i in content['date']){
                    var total='';
                    var check_availability='';
                    var check_stop_sell='';
                    var stop_sell=0;
                    var availability='';
                    if(''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+'' in content['rates']){
                       total=content['rates'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                       availability= content['availability'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                       stop_sell= content['stop_sell'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                    }
                    if(availability==0){
                        check_availability='empty-data';
                    }
                    if(stop_sell==1){
                        check_stop_sell='room_close';
                    }
                    html+='<td title="'+availability+' Rooms available" onmousedown="cancel(event,\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onclick="selectThisBox(\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onmouseover="selectBox(\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" class="'+check_stop_sell+' '+content['date'][i]['class']+' date_'+i+' '+content['newwayRooms'][j]['plans'][k]['id']+' '+check_availability+'" id="'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><input type="hidden" name="'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><span>'+total+'</span></td>';;
                }
                html+='</tr>';
            }
         }
         html+='</table></br></br>';
         jQuery('#rates').html(html);
         jQuery('#month_option_rate').html(content['month_option']);
         jQuery('#table-rates').on('contextmenu', 'td', function(e){ return false; });
         setTimeout(function(){
         }, 1000);
     }
     });
}
function updateRates(month,year){
    boxChange=jQuery('.box-select input').serializeArray();
     console.log(boxChange);
    jQuery.ajax({
    url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'updateRates',data:boxChange,month:month,year:year},
     success:function(result)
     {
         getRates(month,year);
         setTimeout(function(){
         }, 1000);
     }
    });
}
/** =================================================================================================Stop sell=================================================================================== **/
function getStopSellForm(monthView,yearView){
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'rates',month:monthView,year:yearView},
     success:function(result)
     {
        var content=JSON.parse(result);
        var month=content['month'];
        var year=content['year'];
         var html='<table><tr><td><p>Month</p><select class="month_option" onchange="viewOtherMonth(\'stop-sell\')" id="month_option_stop-sell"  name="month_option_stop-sell" style="width:100px"></select></td><td style="padding-left:10px"><p>Change Stop Sale to</p><input class="w3-radio" type="radio" name="stop-sell" value="on" checked><label>On</label><input class="w3-radio" type="radio" name="stop-sell" value="off"><label>Off</label></td>'+
         '<td valign="bottom"><p></p><input id="change_availability" type="button" value="Save" onclick="updateStopSell('+month+','+year+')"></td></tr></table><p></p>'
         +'<table id="table-stop-sell">';
         for(var j in content['newwayRooms']){
            html+='<tr style="background-color:#D3D3D3;"><th>'+content['newwayRooms'][j]['brief_name']+'</th>';
            for(var i in content['date']){
                html+='<td data-check_select_all="0" id="date_'+i+'" class="'+content['date'][i]['class_th']+'" onclick="setColumn(\''+i+'\')">'+content['date'][i]['date']+'<br>'+content['date'][i]['day_of_week']+'</td>';
            }
            html+='</tr>';
            for(var k in content['newwayRooms'][j]['plans']){
                html+='<tr><td style="text-align: left !important" data-check_select_all="0" id="'+content['newwayRooms'][j]['plans'][k]['id']+'" onclick="setRow(\''+content['newwayRooms'][j]['plans'][k]['id']+'\')">'+content['newwayRooms'][j]['plans'][k]['name']+'</td>';
                for(var i in content['date']){
                    var total='';
                    var availability='';
                    var check_stop_sell='';
                    var check_availability='';
                    var stop_sell=0;
                    if(''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+'' in content['rates']){
                       total=content['rates'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                       availability= content['availability'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                       stop_sell= content['stop_sell'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                    }
                    if(availability==0){
                        check_availability='empty-data';
                    }
                    if(stop_sell==1){
                        check_stop_sell='room_close';
                    }
                    html+='<td title="'+availability+' Rooms available" onmousedown="cancel(event,\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onclick="selectThisBox(\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onmouseover="selectBox(\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" class="'+check_stop_sell+' '+content['date'][i]['class']+' date_'+i+' '+content['newwayRooms'][j]['plans'][k]['id']+' '+check_availability+'" id="'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><input type="hidden" name="'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><span></span></td>';;
                }
                html+='</tr>';
            }
         }
         html+='</table></br></br>';
         jQuery('#stop-sell').html(html);
         jQuery('#month_option_stop-sell').html(content['month_option']);
         jQuery('#table-stop-sell').on('contextmenu', 'td', function(e){ return false; });
         setTimeout(function(){
         }, 1000);
     }
     });
}
function updateStopSell(month,year){
    var cmd=jQuery("input[type='radio']:checked").val();
    boxChange=jQuery('.box-select input').serializeArray();
    jQuery.ajax({
    url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'updateStopSell',data:boxChange,month:month,year:year,cmd:cmd},
     success:function(result)
     {
         getStopSellForm(month,year);
         setTimeout(function(){
         }, 1000);
     }
    });
}
function updateExtraAdult(month,year){
    boxChange=jQuery('.box-select input:first-child').serializeArray();
    jQuery.ajax({
    url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'updateExtraAdult',data:boxChange,month:month,year:year},
     success:function(result)
     {
         getExtraAdult(month,year);
         setTimeout(function(){
         }, 1000);
     }
    });
}
function getExtraAdult(monthView,yearView){
    jQuery.ajax({
     url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
     type:"POST",
     data:{type:'rates',month:monthView,year:yearView,extra_adult:1},
     success:function(result)
     {
        var content=JSON.parse(result);
        var extraAdult =[];
        for( var i in content['extra_adult'] ) {
            extraAdult[i]=content['extra_adult'][i];
        }
        var month=content['month'];
        var year=content['year'];
         var html='<table><tr><td><p>Month</p><select onchange="viewOtherMonth(\'extra-adult\')" class="month_option" id="month_option_extra-adult"  name="month_option_rate" style="width:100px"></select></td>'+
         '<td><p>Adjust Rates</p><select class="month_option" onchange="changeExtraAdultRate()" id="adjust-rates"  name="adjust-rates" style="width:100px"><option value="1">Change to</option><option value="2">Increase by</option><option value="3">Decrease by</option></select></td>'+
         '<td><p>Change Rates to</p><input id="change_availability_input" style="width:100px" name="change_availability_input" oninput="changeExtraAdultRate()" class="w3-input w3-border" type="text"></td>'+
         '<td><p>Adjust Rates</p><select class="month_option" onchange="changeExtraAdultRate()" id="type-rates"  name="type-rates" style="width:100px"><option value="1">Amount</option><option style="display:none" value="2">Percent</option></select></td>'+
         '<td valign="bottom"><p></p><input id="change_availability" type="button" value="Save" onclick="updateExtraAdult('+month+','+year+')"></td></tr></table><p></p><table id="table-extra-adult">';
         for(var j in content['newwayRooms']){
            html+='<tr style="background-color:#D3D3D3;"><th>'+content['newwayRooms'][j]['brief_name']+'</th>';
            for(var i in content['date']){
                html+='<td data-check_select_all="0" id="date_'+i+'" class="'+content['date'][i]['class_th']+'" onclick="setColumn(\''+i+'\')">'+content['date'][i]['date']+'<br>'+content['date'][i]['day_of_week']+'</td>';
            }
            html+='</tr>';
            for(var k in content['newwayRooms'][j]['plans']){
                html+='<tr><td style="text-align: left !important" data-check_select_all="0" id="'+content['newwayRooms'][j]['plans'][k]['id']+'" onclick="setRow(\''+content['newwayRooms'][j]['plans'][k]['id']+'\')">'+content['newwayRooms'][j]['plans'][k]['name']+'</td>';
                for(var i in content['date']){
                    var extra_adult='';
                    var check_availability='';
                    var check_stop_sell='';
                    var stop_sell=0;
                    var availability='';
                    if(''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+'' in content['rates']){
                       extra_adult=content['extra_adult'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                       availability= content['availability'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                       stop_sell= content['stop_sell'][''+i+'_'+content['newwayRooms'][j]['plans'][k]['id']+''];
                    }
                    if(availability==0){
                        check_availability='empty-data';
                    }
                    if(stop_sell==1){
                        check_stop_sell='room_close';
                    }
                    html+='<td title="'+availability+' Rooms available" onmousedown="cancel(event,\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onclick="selectThisBox(\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" onmouseover="selectBox(\''+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'\')" class="'+check_stop_sell+' '+content['date'][i]['class']+' date_'+i+' '+content['newwayRooms'][j]['plans'][k]['id']+' '+check_availability+'" id="'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'">'+
                    '<input type="hidden" name="'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'"><span>'+extra_adult+'</span>'+
                    '<input type="hidden" name="old_'+content['newwayRooms'][j]['plans'][k]['id']+'_'+content['date'][i]['date']+'_'+month+'_'+year+'" value='+extra_adult+'>'+
                    '</td>';
                }
                html+='</tr>';
            }
         }
         html+='</table></br></br>';
         jQuery('#extra-adult').html(html);
         jQuery('#month_option_extra-adult').html(content['month_option']);
         jQuery('#table-extra-adult').on('contextmenu', 'td', function(e){ return false; });
         setTimeout(function(){
         }, 1000);
     }
     });
}
/** =================================================================================================RatePlans=================================================================================== **/
function getRatePlansLayoutContent(){
    jQuery('#content').html('<div id="loader"><div class="loader"></div></div>');
    var html='<h3><b><i class="fa fa-cog"></i> Rate Plans</b></h3><table id="table-rateplan" class="w3-table w3-bordered">';
    jQuery.ajax({
         url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
         type:"POST",
         data:{type:'getRatePlans'},
         success:function(result)
         {
            var content=JSON.parse(result);
            for(i in content['newwayRooms']){
                html+='<tr style="background-color:#8FBC8F"><td colspan="2"><b>'+content['newwayRooms'][i]['name']+'</b></td></tr>';
                for(j in content['ratePlans']){
                    if(content['ratePlans'][j]['hotellink_room_id']==content['newwayRooms'][i]['hotellink_room_id']){
                        html+='<tr><td></td><td>+ '+content['ratePlans'][j]['name']+'</td></tr>';
                    }
                }
            }
            html+='</table><br/><input type="button" class="w3-btn w3-blue" value="Update" onclick="updateRatePlans()"/></br></br></br></br></br>';
            jQuery('#content').html(html);
             setTimeout(function(){
             }, 1000);
         }
     });
}

function updateRatePlans(){
    rates=jQuery('.rates').serializeArray();
    jQuery('#content').html('');
    jQuery('#content').html('<div id="loader"><div class="loader"></div></div>');
    jQuery.ajax({
         url:"packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/forms/content_layout.php?",
         type:"POST",
         data:{type:'updateRatePlans',rates:rates},
         success:function(result)
         {
             setTimeout(function(){
             }, 1000);
             getRatePlansLayoutContent();
         }
     });
}

function getBookingEngineForm(){
    html='<div class="w3-bar w3-light-green" style="width:1200px !important;margin-left:18px">'+
              '<input id="bt-availability" type="button" class="w3-bar-item w3-button" onclick="openTab(\'availability\')" value="Availability"/>'+
              '<input id="bt-rates" type="button" class="w3-bar-item w3-button" onclick="openTab(\'rates\')" value="Rates"/>'+
              '<input id="bt-extra-adult" type="button" class="w3-bar-item w3-button" onclick="openTab(\'extra-adult\')" value="Extra Adult"/>'+
              '<input id="bt-extra-child" type="button" class="w3-bar-item w3-button" onclick="openTab(\'extra-child\')" value="Extra Child"/>'+
              '<input id="bt-min-nights" type="button" class="w3-bar-item w3-button" onclick="openTab(\'min-nights\')" value="Min Nights"/>'+
              '<input id="bt-max-nights" type="button" class="w3-bar-item w3-button" onclick="openTab(\'max-nights\')" value="Max Nights"/>'+
              '<input id="bt-close-arrival" type="button" class="w3-bar-item w3-button" onclick="openTab(\'close-arrival\')" value="Close to Arrival "/>'+
              '<input id="bt-close-departure" type="button" class="w3-bar-item w3-button" onclick="openTab(\'close-departure\')" value="Close to Departure"/>'+
              '<input id="bt-stop-sell" type="button" class="w3-bar-item w3-button" onclick="openTab(\'stop-sell\')" value="Stop Sell"/>'+
              '<input id="bt-release-period" type="button" class="w3-bar-item w3-button" onclick="openTab(\'release-period\')" value="Release Period"/>'+
            '</div>'+
            
            '<div id="availability" class="w3-container tab">'+

            '</div>'+
            
            '<div id="rates" class="w3-container tab" style="display:none">'+

            '</div>'+
            '<div id="extra-adult" class="w3-container tab">'+

            '</div>'+
            '<div id="stop-sell" class="w3-container tab" style="display:none">'+

            '</div>';
     jQuery('#content').html(html);
     openTab('availability');       
}
function openTab(id) {
    jQuery('.tab').html('');
    var i;
    var x = document.getElementsByClassName("tab");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    if(id=='availability'){
        getAvailability('','');
    }else if(id=='rates'){
        getRates('','');
    }
    else if(id=='stop-sell'){
        getStopSellForm('','');
    }else{
        getExtraAdult('','');
    }
    jQuery('input.w3-bar-item').css('background-color','#8BC34A');
    jQuery('#bt-'+id).css('background-color','#CCCCCC');
    document.getElementById(id).style.display = "block";  
}

function selectedBarItem(id){
    jQuery('a.w3-bar-item').css('color','black');
    jQuery('#'+id).css('color','red');
}
function changeExtraAdultRate(){
    if(jQuery('#adjust-rates').val()==1){
        jQuery('#type-rates option:last-child').css('display','none');
        jQuery('#type-rates option:first-child').attr('selected','selected');
    }else{
        jQuery('#type-rates option:last-child').css('display','block');
    }
    if(jQuery('#adjust-rates').val()==1){
        jQuery('.box-select span').html(jQuery('#change_availability_input').val());
        jQuery('.box-select input:first-child').val(jQuery('#change_availability_input').val());
    }else{
        if(jQuery('#adjust-rates').val()==2){
            jQuery('.box-select').each(function(){
                oldValue=parseFloat(jQuery(this).find('input:last-child').val());
                if(jQuery('#type-rates').val()==1){
                    var newValue=0;
                    if(jQuery('#change_availability_input').val()!=''){
                        newValue=parseFloat(jQuery('#change_availability_input').val());
                    }
                    jQuery(this).find('span').html((oldValue+newValue).toFixed(2));
                    jQuery('.box-select input:first-child').val((oldValue+newValue).toFixed(2));
                }else{
                    var newValue=0;
                    if(jQuery('#change_availability_input').val()!=''){
                        newValue=parseFloat(jQuery('#change_availability_input').val())*oldValue/100;
                    }
                    jQuery(this).find('span').html((oldValue+newValue).toFixed(2));
                    jQuery('.box-select input:first-child').val((oldValue+newValue).toFixed(2));
                }
            });
        }else{
            jQuery('.box-select').each(function(){
                oldValue=parseFloat(jQuery(this).find('input:last-child').val());
                if(jQuery('#type-rates').val()==1){
                    var newValue=0;
                    if(jQuery('#change_availability_input').val()!=''){
                        newValue=parseFloat(jQuery('#change_availability_input').val());
                    }
                    var total=oldValue-newValue;
                    if(total<0){total=0;}
                    jQuery(this).find('span').html(total.toFixed(2));
                    jQuery('.box-select input:first-child').val(total.toFixed(2));
                }else{
                    var newValue=0;
                    if(jQuery('#change_availability_input').val()!=''){
                        newValue=parseFloat(jQuery('#change_availability_input').val())*oldValue/100;
                    }
                    var total=oldValue-newValue;
                    if(total<0){total=0;}
                    jQuery(this).find('span').html(total.toFixed(2));
                    jQuery('.box-select input:first-child').val(total.toFixed(2));
                }
            });
        }
    }
    jQuery('.box-select input').each(function(){
        
    });
}