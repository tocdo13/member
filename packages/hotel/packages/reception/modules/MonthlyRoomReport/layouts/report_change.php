<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<style>
#timehidden{
		display:none;	
	}
	@media print{
		#hidden{
			display:none;
		}
		#timehidden{
			display:block;	
		}
	}
	.revenue_td{
		border-right:1px solid #FFFFFF;
		float:left;
		height:30px;
		text-align:left;
		font-size:9px;
		line-height:30px;
	}
	.reservation{
		border-right:1px solid #BEBEBE;
		float:left;
		height:30px;
		text-align:left;
		font-size:9px;
		line-height:30px;
	}
	.revenue_tr{
		border-bottom:1px solid #BEBEBE;	
		height:30px;
	}
	.revenue_td_menu{
		border-bottom:1px solid #FFFFFF;
		height:50px;
		text-align:left;
	}
	.revenue_td_blank{
		border-right:1px solid #BEBEBE;
		float:left;
		height:30px;
		text-align:left;	
	}
	.td_blank_past{
		border-right:1px solid #BEBEBE;
		float:left;
		height:30px;
		text-align:left;	
	}
	#slideMenu{
		position:absolute;
		width:40px !important;
		background:#E5E5E6;
		margin-left:4px;
		z-index:2005;
	}
	.simple-layout-middle{
		width:100%;	
	}
	.reservation_CHECKIN{
		background:#F7A204;	
	}
	.reservation_CHECKOUT{
		background:#FF9999;	
	}
	.reservation_BOOKED{
		background:#75ACFF;	
	}
	#menu_bound{
		background:url(packages/hotel/skins/default/images/iosstyle/item_list_bg.png) repeat-x;
		height:50px;
		border:#B7CDFC;
		z-index:2000;
	}
	.booking-toolbar{
		background:#66CCCC;padding:5px;color:#FFFFFF;width:100%;
		margin-left:-40px;
	}
	#myMenu{
		height:80px;
		width:140px;	
		border:1px solid #686868;
	}
	#myMenu tr{
		border-bottom:1px solid #BEBEBE;
	}
	#myMenu tr:hover{
		cursor:pointer;	
	}
	.img_icon{
		width:18px;
		height:20px;	
	}
	.web-dialog{
		display: none;
		float:left;
		position: fixed;
		width: 380px;
		top: 40%;
		left: 50%;
		margin-left: -190px;
		margin-top: -100px;	
		background-color: #FFFFFF;
		z-index: 102;
		border: 5px solid #AAAAAA;
		border-right: 5px solid #CCCCCC;
		border-left: 5px solid #CCCCCC;
}
#mask{
	display:none;
	width:100%;
	height:1000px;
	position:absolute;
	top:0px;
	left:0px;
	float:left;
	background:#999999;
	opacity:0.7;
}
#revenue{
	display:none;	
}
#detail{
	height:250px;
	width:300px;
	margin:auto;
}
#detail tr{
	height:30px;	
}
.info{
	background:url(packages/hotel/skins/default/images/iosstyle/grey_bg.png) repeat-x 0% 100%;
	height:40px;
	line-height:40px;
	color:#666666;
	font-weight:bold;
	border-bottom:1px solid #CCCCCC;
}
</style>
<form name="WeeklyViewFolioForm" method="post">
<div id="mask" class="mask">[[.Please wait.]]...</div>
<table width="80%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br />ADD: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong>[[.template_code.]]</strong></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%"></td>
				<td align="right" nowrap width="35%"></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%">[[.print_by.]] : <?php echo Session::get('user_id');?></td>
				<td align="right" nowrap width="35%"></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%">[[.print_date.]] : <?php echo date('h:i d/M/Y',time());?></td>
				<td align="right" nowrap width="35%"></td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.monthly_room_report.]]</b></font>

		<br><br />
        <label id="timehidden">Từ ngày: [[|from_date|]]  Đến ngày: [[|to_date|]]</label>
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td></td>
            	<td>[[.from_date.]]:&nbsp;&nbsp;
            	<input type="text" name="from_date" id="from_date" class="date-input" onclick="jQuery('#ui-datepicker-div').css('z-index',3000);" onchange="check_from_date();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>[[.to_date.]]:&nbsp;&nbsp;
                <input type="text" name="to_date" id="to_date" class="date-input" onclick="jQuery('#ui-datepicker-div').css('z-index',3000);" onchange="check_to_date();"/></td>
                <td><input type="submit" name="do_search" value="  [[.view.]]  "></td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
      		

	</td></tr></table>
</td></tr></table>
<div id="revenue">
	<div id="slideMenu">
    	<div class="revenue_td_menu" style="height:50px;">[[.room.]]</div>
    <!--LIST:items-->
    	<div class="revenue_td_menu" id="room_name" style="height:30px;">[[|items.name|]]</div>
    <!--/LIST:items-->
    </div>
	<div id="menu_bound" style="margin-left:41px;position:absolute;">
   	<?php $tt=0; $from_time = [[=from_time=]]; $to_time = [[=to_time=]]; 
			for($i=$from_time;$i<$to_time ; $i+=24*3600){
				if(date('d/m/Y',$i) == date('d/m/Y')){
					echo '<div class="revenue_td" style="font-size:11px;background:#66CCCC; height:50px;">'.date('d/m',$i).'</div>'; 
				}else{
					echo '<div class="revenue_td" style="font-size:11px;height:50px;">'.date('d/m',$i).'</div>'; 	
				}
			}
			?>
    </div><div class="clear"></div>
   <div style="margin-top:1px;margin-left:41px;padding-top:51px;">
	<!--LIST:items-->
    <?php $rr_id = '';?>
	<div class="revenue_tr" id="revenue_tr_[[|items.name|]]">
    	<!--LIST:items.days-->
        <?php 
			if([[=items.days.day=]] >= Date_Time::to_time(date('d/m/Y'))){
					echo '<div class="revenue_td_blank" style="cursor:pointer;" id="room_'.[[=items.id=]].'_'.[[=items.days.day=]].'" onclick="select_room(\''.[[=items.id=]].'\',\''.[[=items.days.day=]].'\'); " lang="'.[[=items.id=]].'_'.[[=items.days.day=]].'" title="'.date('d/m',[[=items.days.day=]]).'"></div>';
				}else{
					echo '<div class="td_blank_past" id="room_'.[[=items.id=]].'_'.[[=items.days.day=]].'" lang="'.[[=items.id=]].'_'.[[=items.days.day=]].'" style="background:#EDF5FF;" title="'.date('d/m',[[=items.days.day=]]).'"></div>';
			}
			if(isset([[=items.days.customer=]])){// or ([[=items.days.house_status=]]=='REPAIR')
				if($rr_id != [[=items.days.reservation_room_id=]] or ([[=items.days.house_status=]]=='REPAIR' and $rr_id != '')){
					if($rr_id != [[=items.days.reservation_room_id=]] && [[=items.days.house_status=]]!='REPAIR'){
						echo '<div class="reservation_'.[[=items.days.reservation_status=]].'" id="reservation_'.[[=items.days.reservation_room_id=]].'"  title="'.[[=items.days.customer=]].'" onMouseOver="
				var r_r_id = \''.[[=items.days.reservation_room_id=]].'\'; jQuery(this).bind(\'contextmenu\' , function(e){mouseX = e.pageX; mouseY = e.pageY;
				reser_act={};'.([[=items.days.reservation_room_id=]]?' if(r_r_id !=\'\'){reser_act['.[[=items.days.reservation_room_id=]].'] = \''.[[=items.days.reservation_status=]].'\';}':'').'check_invisible();
				jQuery(\'#myMenu\').css({\'left\' : mouseX , \'top\' : mouseY}).show();
				return false; 
			});"  lang="'.[[=items.days.start_time=]].'_'.[[=items.days.nights=]].'">';
						echo '<input name="customer_name" type="text" value="'.[[=items.days.cus=]].'" class="reservation_'.[[=items.days.reservation_status=]].'" style=" border:hidden;margin-top:8px;font-size:10px;" maxlength="200" readonly="readonly" id="customer_name_'.[[=items.days.reservation_room_id=]].'" >';
						echo '</div>';
					}elseif([[=items.days.house_status=]]=='REPAIR' and $rr_id != ''){
							echo '<div class="reservation_'.[[=items.days.reservation_status=]].'" id="reservation_'.[[=items.days.reservation_room_id=]].'"  title="'.[[=items.days.customer=]].'" lang="'.[[=items.days.start_time=]].'_'.[[=items.days.nights=]].'">';	
							echo '<input name="customer_name" type="text" value="REPAIR" class="reservation_'.[[=items.days.reservation_status=]].'" style=" border:hidden;margin-top:8px;font-size:10px;" maxlength="200" readonly="readonly" id="customer_name_'.[[=items.days.reservation_room_id=]].'" >';
					echo '</div>';
						}
						//onClick="reservation_form(\''.[[=items.days.reservation_id=]].'\',\''.[[=items.days.reservation_room_id=]].'\');"
					//echo [[=items.days.cus=]]';
				}else{
				}
				$rr_id=[[=items.days.reservation_room_id=]];
        	}
        ?>
    	<!--/LIST:items.days-->
    </div><div class="clear"></div>
    <!--/LIST:items-->
   </div> 
     <div class="booking-toolbar">
	<!--IF:reservation(User::can_edit(false,ANY_CATEGORY))-->
	&nbsp;&nbsp;&nbsp;&nbsp;<strong>Price: </strong>&nbsp;
	<!--LIST:room_types-->
	[[|room_types.name|]] <input type="text" id="room_price_[[|room_types.id|]]" value="[[|room_types.price|]]" style="width:65px;color:#0033FF">
	<!--/LIST:room_types-->
	<strong>Default:</strong>&nbsp;Time in: <input type="text" id="time_in" value="<?php echo CHECK_IN_TIME;?>" style="width:40px;color:#FF3300">&nbsp;Time out: <input type="text" id="time_out" value="<?php echo CHECK_OUT_TIME;?>" style="width:40px;color:#FF3300">&nbsp;
	<!--/IF:reservation-->
	<table cellpadding="3" id="myMenu" class="contextMenu">
    	<tr class="li_menu" id="add">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303290976_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Add - Reservation </td>
        </tr>
    	<tr class="li_menu" id="edit">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/accounting_report.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Edit - Reservation</td>
        </tr>
        <tr class="li_menu" id="cancel">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303290976_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Cancel</td>
        </tr>
         <tr class="li_menu" id="checkin">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303290976_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Checkin</td>
        </tr>
         <tr class="li_menu" id="change_room">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303290976_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Change room</td>
        </tr>
         <tr class="li_menu" id="view">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303290976_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">View order</td>
        </tr>
    </table>
   
 </div></div>
 <div id="reser_detail"></div>
 </form>
<script>
		var time_from = [[|time_from|]];
		var time_to = [[|time_to|]];
		var width_window = to_numeric(jQuery(window).width());
		var num_days = [[|num_days|]];	
		room_types_js = [[|room_types_js|]]; var to_day = [[|to_day|]]; var width = 0;
		//alert(to_day);
		reser_act = {};
		flag = {};
		reservation = {};
		rooms = {};
		$('from_date').value = '[[|from_date|]]';
		$('to_date').value = '[[|to_date|]]';
		function check_from_date(){
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((from_date[1] > to_date[1]) || (from_date[2] > to_date[2])){
				$('to_date').value = $('from_date').value;
			}else{
				if((from_date[0] > to_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2]) ){
					$('to_date').value = $('from_date').value;	
				}	
			}
		}
		function check_to_date(){
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((to_date[1] < from_date[1]) || ( to_date[2] < from_date[2])){
				$('from_date').value = $('to_date').value;
			}else{
				if((to_date[0] < from_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2])){
					$('from_date').value = $('to_date').value;
				}
			}
		}
		jQuery("#from_date").datepicker({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
	
	jQuery(window).scroll(function() {
		
    jQuery('#slideMenu').animate(
        { left: to_numeric(jQuery(window).scrollLeft())+4 + 'px' },
		{ queue: false, duration: 50}
    );
	});
	jQuery(window).scroll(function(){
	jQuery('#menu_bound').css('position','absolute');	
    jQuery('#menu_bound').animate(
        { top: (to_numeric(jQuery(window).scrollTop())) + 'px' },
		{ queue: false, duration: 50}
    );
	if(to_numeric(jQuery(window).scrollTop())<327){
		jQuery('#menu_bound').css('margin-top',327-to_numeric(jQuery(window).scrollTop()));		
	}else{
		jQuery('#menu_bound').css('margin-top','0px');		
	}
	});
	jQuery('.revenue_td_blank').each(function(){
			var index = this.lang;
			room_id = index.substr(0,index.lastIndexOf("_"));
			day = index.substr(index.lastIndexOf("_")+1,index.length -1);
			flag[room_id+'_'+day] = 0;	
			reservation[room_id+'_1'] = {};
			reservation[room_id+'_2'] = {};
	});
	jQuery(document).ready( function() {
		width = (width_window-45-num_days)/num_days;
		width = Math.floor(width);
		jQuery('#slideMenu').css('margin-left','0px');
		jQuery('.revenue_tr').css('width',(width+1)*num_days);
		jQuery('#menu_bound').css('width',(width+1)*num_days);
		jQuery('.revenue_td').css('width',width);
		jQuery('.revenue_td_blank,.td_blank_past').css({'width':width});
		var change = false;	
		for(var key in items_js){
			for(var k in items_js[key]['days']){
				if(items_js[key]['days'][k]['reservation_room_id'] != undefined){
					if(items_js[key]['days'][k]['nights'] == 1){
						nights = to_numeric(items_js[key]['days'][k]['nights']);
						jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css('width',nights*width);
						if(items_js[key]['days'][k]['start_time']<=time_from){
							jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css('left',45);
						}else{
							jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css('left',(width+1)*Math.floor((to_numeric(items_js[key]['days'][k]['start_time']) - time_from)/86400)+45);
						}
						jQuery('#customer_name_'+items_js[key]['days'][k]['reservation_room_id']).css('width',(width-5)*nights);
					}else if(items_js[key]['days'][k]['nights'] > 1){
						nights = to_numeric(items_js[key]['days'][k]['nights']);
						if(items_js[key]['days'][k]['start_time']<=time_from){
							jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css('left',45);
						}else{
							jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css('left',(width+1)*Math.floor((to_numeric(items_js[key]['days'][k]['start_time']) - time_from)/86400)+45);
						}
						jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css('width',(width+1)*nights-1);
						jQuery('#customer_name_'+items_js[key]['days'][k]['reservation_room_id']).css('width',(width-5)*nights);
					}
					jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).css({'float':'left','height':'30px','border-right':'1px solid #BEBEBE','position':'absolute','z-index':1000});
				}
			}
		}
		jQuery('#revenue').css('display','block');
		function booked_rooms_selected(act){
			 var rooms_arr = '';
			 var rooms_prices = '';                   
			 var y=0;
			 if(act == 'add'){
				for(var j in rooms_array){
					if(rooms[rooms_array[j]['id']] != undefined){
						if(y==0){
							rooms_arr = rooms_array[j]['id'];
						}else{
							rooms_arr += '|'+rooms_array[j]['id'];
						} 
						var start_date = 0;
						var end_date = 0;
						var count= 0;
						for(var t=time_from;t<=time_to;t+=86400){
							if(flag[rooms_array[j]['id']+'_'+t] == 1){
								if((start_date==0  && end_date==0) || (count==0 && start_date!=0  && end_date!=0)){
									if(count==0 && start_date!=0  && end_date!=0){
										rooms_arr += '|'+rooms_array[j]['id'];
									}
									start_date = t;
									end_date = t+86400;	
									count=1;
								}else if(end_date!=0 && t==end_date){
									end_date = t+86400;	
								}else if(end_date!=0 && end_date < t && t!=end_date){
									end_date = 0; start_date = 0; ;	
								}
							}else{
								if(end_date == t){
									rooms_arr += ','+get_date(start_date)+','+get_date(end_date); 
									count = 0;
								}	
							}
						}
						y=y+1;	
					}
				}
				var y=1;
				for(var s in room_types_js){
					rooms_prices += '&room_prices['+room_types_js[s]['id']+']='+room_types_js[s]['price'];	y=y+1;	
				}	
				window.open('?page=reservation&cmd=add&time_in=13:00&time_out=12:00&rooms='+rooms_arr+rooms_prices);
			 }else if(act == 'cancel' || act == 'checkin' || act=='view' || act=='edit'|| act=='change_room'){
				reservation_arr = new Array();
				for(var key in items_js){
					for(var k in items_js[key]['days']){
						 if(reser_act[items_js[key]['days'][k]['reservation_room_id']] != undefined){
							 reservation_arr = items_js[key]['days'][k];
						 }
					}
				}
				if(act == 'checkin' && reservation_arr['start_time']>to_day){
					alert('[[.unable_to_checkin_in_future.]]');
					return false;	
				}
				if((act == 'cancel' || (act == 'checkin' && reservation_arr['start_time']==to_day)) && reservation_arr['reservation_status'] == 'BOOKED'){
					 var text= '<div id="dialog" class="web-dialog"><div class="info"><span class="title_bound">[[.reservation_detail.]]</span><img width="15" height="15" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog(\'dialog\');" style="float:right;"/></div>';
					text += '<table id="detail"><tr><td>[[.order_id.]] : </td><td>'+reservation_arr['reservation_room_id']+'</td></tr>';
					text += '<tr><td>[[.customer_name.]]</td><td>'+reservation_arr['customer']+'</td></tr>';
					text += '<tr><td>[[.arrival_time.]] : </td><td>'+reservation_arr['arrival_time']+'</td></tr>';
					text += '<tr><td>[[.departure_time.]]</td><td>'+reservation_arr['departure_time']+'</td></tr>';
					text += '<tr><td>[[.change_status_to.]]</td><td>'+act.toUpperCase()+'</td></tr>';
					text += '<tr><td colspan=2><input name="chang_status" type="button" value="[[.update_status.]]" id="chang_status" onclick="change_status(\''+act.toUpperCase()+'\','+reservation_arr['reservation_room_id']+');"><input name="exit" type="button" value="[[.cancel.]]" id="exit" onclick="HideDialog(\'dialog\');jQuery(\'#mask\').hide();"></td></tr>';
					text += '</table></div>';
					jQuery('#reser_detail').html(text);
					jQuery("#dialog").css('z-index',2500);	
					jQuery("#dialog").fadeIn(300);
				 }else if(act=='view'){
						window.open('?page=reservation&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&extra_service_invoice=1&included_deposit=1&included_related_total=1&cmd=invoice&id='+reservation_arr['reservation_room_id']);
						return false;
				 }else if(act=='edit'){
					 window.open('?page=reservation&cmd=edit&id='+reservation_arr['reservation_id']+'&r_r_id='+reservation_arr['reservation_room_id']);
					 return false;
				 }else if(act=='change_room'){
				 	window.open('?page=change_room&id='+reservation_arr['reservation_room_id']);
					return false;
			 	}
			 }
       }
		jQuery('.revenue_td_blank').bind('contextmenu' , function(e){
			 mouseX = e.pageX; 
  			 mouseY = e.pageY;
				jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer'});	
				jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer'});
				jQuery('#edit td').css({'background':'#D6D6D6','cursor':'text'});
				jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text'});
				jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text'}); 
				jQuery('#view td').css({'background':'#D6D6D6','cursor':'text'}); 
				jQuery('#change_room td').css({'background':'#D6D6D6','cursor':'text'}); 
            jQuery('#myMenu').css({'left' : mouseX , 'top' : mouseY}).show();
            return false; 
        });
		 jQuery('#myMenu tr.li_menu').click(function(){
			var obj = this.id;
            jQuery('#myMenu').hide();
            booked_rooms_selected(obj);
       });
	   jQuery('.revenue_tr').mousedown(function(){
		   jQuery('#myMenu').hide();
		});
		var width_obj = 0; var left_obj=0; var right_limit=0; var left_limit=0;
		for(var key in items_js){
			for(var k in items_js[key]['days']){
				if(items_js[key]['days'][k]['reservation_room_id'] != undefined){
					top_limit = to_numeric(jQuery('#menu_bound').position().top)+to_numeric(jQuery('#menu_bound').height());
					bottom_limit=to_numeric(jQuery('.booking-toolbar').position().top);
					if(to_numeric(items_js[key]['days'][k]['start_time'])<to_day && to_numeric(items_js[key]['days'][k]['end_time'])>to_day){
							jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).hover(function(){
							width_obj = to_numeric(jQuery(this).width());
							left_obj =  to_numeric(jQuery(this).position().left);
							right_limit =left_obj;//+ width_obj;
							bottom_limit = bottom_limit+to_numeric(jQuery(this).height());
							jQuery(this).draggable({
								stop: function(){
									var action = false;
									var id = (this.id).substr((this.id).lastIndexOf("_")+1,(this.id).length-1);
									var start_time = (this.lang).substr(0,(this.lang).lastIndexOf("_"));
									var nights = (this.lang).substr((this.lang).lastIndexOf("_")+1,(this.lang).length);
									var left = this.offsetLeft;
									var top = this.offsetTop;
									jQuery('.td_blank_past').each(function(){
										obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);
										if(left==obj_left && top==obj_top){
											obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
											room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
											if(change == false){
												jQuery('#mask').css('z-index',2400);
												jQuery('#mask').show();
												save_position(id,room_id,day,nights,action);
											}else{
												return false;	
											}
										}
									});	
								},
								containment: [left_obj,top_limit,right_limit,bottom_limit]
								,revert: function(){
								var id = jQuery(this).attr('id');
								var left = to_numeric(this.offset().left);
								var top = to_numeric(this.offset().top);
								var count = Math.floor(left/width);
								var width = to_numeric(jQuery(this).width()); var kt=0;
								top_limit = to_numeric(jQuery('#menu_bound').position().top)+to_numeric(jQuery('#menu_bound').height());
								lef = 0; tp = 0;
								jQuery('.td_blank_past').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);
									if((obj_left+width) > left && left>=obj_left && lef<obj_left){
										lef = obj_left;	
										obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
									}
									if((obj_top+30)>top && obj_top<=top){
										tp = obj_top;	
										obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
									}
								});
								jQuery('.reservation_BOOKED').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
									var obj_width = to_numeric(jQuery(this).width());
									if(lef==obj_left && tp==obj_top){
										kt = 1;	
										}else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width))){
										kt = 1;		
									}
								});
								jQuery('.reservation_CHECKIN').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
									var obj_width = to_numeric(jQuery(this).width());
									if(lef==obj_left && tp==obj_top){
										kt = 1;	
									}else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width))){
										kt = 1;		
									}
								});
								jQuery('.reservation_CHECKOUT').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
									var obj_width = to_numeric(jQuery(this).width());
									if(lef==obj_left && tp==obj_top){
										kt = 1;	
										}else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width))){
										kt = 1;		
									}
								});
								if(left_limit<=lef && tp>=top_limit && kt==0){
									jQuery(this).css('left',lef);
									jQuery(this).css('top',tp);
									change = false;
									return false;
								}else{ 
									change = true;
									return true;	
								}
								
							}
							});							
						});	
					}else if(to_numeric(items_js[key]['days'][k]['start_time'])>=to_day){
						//alert('#reservation_'+items_js[key]['days'][k]['reservation_room_id']);
						jQuery('#reservation_'+items_js[key]['days'][k]['reservation_room_id']).draggable({
							start: function() {
								 jQuery(this).css('z-index',1500);
							},
							stop: function() {
								var action = true;
								var id = (this.id).substr((this.id).lastIndexOf("_")+1,(this.id).length-1);
								var start_time = (this.lang).substr(0,(this.lang).lastIndexOf("_"));
								var nights = (this.lang).substr((this.lang).lastIndexOf("_")+1,(this.lang).length);
								var left = this.offsetLeft;
								var top = this.offsetTop; 
								jQuery('.revenue_td_blank').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);
									if(left==obj_left && top==obj_top){
										obj_id = this.lang; 
										day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
										if(change == false){
											jQuery('#mask').css('z-index',2400);
											jQuery('#mask').show();
											save_position(id,room_id,day,nights,action);
										}else{
											return false;	
										}
									}
								});
							},
							revert: function(){
								var id = jQuery(this).attr('id');
								var left = to_numeric(this.offset().left);
								var top = to_numeric(this.offset().top);
								var count = Math.floor(left/width);
								var width = to_numeric(jQuery(this).width()); var kt=0;
								top_limit = to_numeric(jQuery('#menu_bound').position().top)+to_numeric(jQuery('#menu_bound').height());
								lef = 0; tp = 0;
								jQuery('.revenue_td_blank').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);
									obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
									room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
									if(day == to_day){
										left_limit = obj_left;		
									}
									if((obj_left+width) > left && left>=obj_left && lef<obj_left){
										lef = obj_left;	
									}
									if((obj_top+30)>top && obj_top<=top){
										tp = obj_top;	
									}
									
								});
								jQuery('.reservation_BOOKED').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
									var obj_width = to_numeric(jQuery(this).width());
									if(lef==obj_left && tp==obj_top){
										kt = 1;	
										}else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width))){
										kt = 1;		
									}
								});
								jQuery('.reservation_CHECKIN').each(function(){
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
									var obj_width = to_numeric(jQuery(this).width());
									if(lef==obj_left && tp==obj_top){
										kt = 1;	
										}else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width))){
										kt = 1;		
									}
								});
								if(left_limit<=lef && tp>=top_limit && kt==0){
									jQuery(this).css('left',lef);
									jQuery(this).css('top',tp);
									change = false;
									return false;
								}else{ 
									change = true;
									return true;	
								}
								
							},
							containment: [to_numeric(jQuery('#menu_bound').position().left),top_limit,to_numeric(jQuery('#menu_bound').width()),bottom_limit]
						});	
					}
				}
			}
		}		
});
	function change_status(act,rr_id){
			jQuery.ajax({
					url:"form.php?block_id=<?php echo Module::block_id();?>",
					type:"POST",
					data:{status:act,id:rr_id},
					success:function(html){
						HideDialog('dialog');
						window.open(location.reload(true));
						//alert('Update status to '+act+' with order id = '+rr_id);
					}
			});
		}
	function select_room(room_id,day){
		if(event.ctrlKey){
				if(reservation[room_id+'_2'] < day){
					for(var t=reservation[room_id+'_2'];t<=day;t+=86400){
						for(var key in items_js){
							if(items_js[key]['id']==room_id){
								if(items_js[key]['days'][t]['day']==t && items_js[key]['days'][t]['nights']==undefined){
									bgr_room(room_id,t,true);
									reservation[room_id+'_2'] = to_numeric(t) + 86400;	
								}else{
									
								}
							}
						}
					}
				}
				if(reservation[room_id+'_1'] > day){
					for(var i=reservation[room_id+'_1'];i>=day;i-=86400){	
						for(var key in items_js){
							if(items_js[key]['id']==room_id){
								if(items_js[key]['days'][i]['day']==i && items_js[key]['days'][i]['nights']==undefined){
									bgr_room(room_id,i,true);
									reservation[room_id+'_1'] = to_numeric(i);
								}else{
									
								}
							}
						}
					}
				}
		}else{
			bgr_room(room_id,day,false);
			if(flag[room_id+'_'+day] == 1){
				reservation[room_id+'_1'] = day;	
				reservation[room_id+'_2'] = to_numeric(day)+86400;	
			}
		}
	}
	function reservation_form(r_id, rr_id){
		location.href = '?page=reservation&cmd=edit&id='+r_id+'r_r_id='+rr_id;	
	}
	function HideDialog(obj){
	  jQuery("#"+obj).fadeOut(300);
	  //location.reload(true);
	} 
	function check_invisible(){
		for(var j in reser_act){
				if(reser_act[j]=='BOOKED'){
					jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer'});	
					jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer'});				
					jQuery('#add td').css({'background':'#D6D6D6','cursor':'text'});
					
				}else if(reser_act[j]=='CHECKIN'){
					jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer'});	
					jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer'});
					jQuery('#add td').css({'background':'#D6D6D6','cursor':'text'});
					jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text'});
					jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text'});
					
				}else if(reser_act[j]=='CHECKOUT'){
					jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer'});	
					jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer'});
					jQuery('#add td').css({'background':'#D6D6D6','cursor':'text'});
					jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text'});
					jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text'});
				}
			}
	}
	function bgr_room(room_id,day,kt){
		if(flag[room_id+'_'+day] == 1 && kt==false){
			jQuery('#room_'+room_id+'_'+day).css('background','#FFFFFF');	
			flag[room_id+'_'+day] =0;
			if(rooms[room_id] != undefined){
				delete rooms[room_id];
			}
		}else if(flag[room_id+'_'+day] ==0){
			jQuery('#room_'+room_id+'_'+day).css('background','#A6BFE5');	
			flag[room_id+'_'+day] =1;
			rooms[room_id] = room_id;  
		}	
	}
	 function get_date(time){
			var a = new Date(time*1000);
			var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
			var year = a.getFullYear();
			var month = months[a.getMonth()];
			var date = a.getDate();
			var hour = a.getHours();
			var min = a.getMinutes();
			var sec = a.getSeconds();
			var time = date+'/'+month+'/'+year;
			return time;
		}
	function save_position(rr_id,room_id,start,nights,action){
		 var text= '<div id="dialog" class="web-dialog"><div class="info"><span class="title_bound">[[.reason_change_room.]]</span><img width="15" height="15" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog(\'dialog\');" style="float:right;"/></div>';
		text += '<table id="detail"><tr><td>[[.order_id.]] : </td><td>'+rr_id+'</td></tr>';
		text += '<tr><td>[[.change_to_room.]]</td><td>'+room_id+'</td></tr>';
		text += '<tr><td>[[.arrival_time.]] : </td><td>'+get_date(start)+'</td></tr>';
		text += '<tr><td>[[.departure_time.]]</td><td>'+get_date(to_numeric(start)+(to_numeric(nights)*86400))+'</td></tr>';
		text += '<tr><td>[[.reason.]]</td><td> </td></tr>';
		 text += '<tr><td colspan="2"><textarea id="reason_change" rows="5" cols="32" ></textarea></td></tr>';
		text += '<tr><td colspan=2><input name="chang_status" type="button" value="[[.update_change.]]" id="change_room" onclick="save('+rr_id+','+room_id+','+start+','+nights+',\''+action+'\');HideDialog(\'dialog\');"><input name="exit" type="button" value="[[.cancel.]]" id="exit" onclick="HideDialog(\'dialog\');jQuery(\'#mask\').hide();"></td></tr>';
		text += '</table></div>';
		jQuery('#reser_detail').html(text);
		jQuery("#dialog").css('z-index',2500);	
		jQuery("#dialog").fadeIn(300);	
	}
	function save(rr_id,room_id, start, nights,action){
		var reason_change = jQuery('#reason_change').val();
		if(reason_change != ''){
			jQuery.ajax({
						url:"form.php?block_id=<?php echo Module::block_id();?>",
						type:"POST",
						data:{rr_id:rr_id,room_id:room_id,start_time:start,nights:nights,cmd:'change_room',act:action,note:reason_change},
						success:function(html){
							jQuery('#mask').hide();
							//alert(html);
							window.open(location.reload(true));
						}
			});
		}else{
			alert('Reason_change_empty !\nChange_unsuccessful.');	
			HideDialog('dialog');
			return false;
		}
	}
     </script>