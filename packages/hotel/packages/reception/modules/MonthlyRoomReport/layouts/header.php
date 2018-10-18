<style>
	.buttonsplitmenu
    {
		z-index:20000;	
	}
</style>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<form name="WeeklyViewFolioForm" method="post">
<div id="mask" class="mask">[[.Please wait.]]...</div>
<table width="80%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%" style="display:none;">
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
		<fieldset>
        <legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td></td>
            	<td>[[.from_date.]]:&nbsp;&nbsp;
            	<input type="text" name="from_date" id="from_date" class="date-input" onclick="jQuery('#ui-datepicker-div').css('z-index',3000);" onchange="check_from_date();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>[[.to_date.]]:&nbsp;&nbsp;
                <input type="text" name="to_date" id="to_date" class="date-input" onclick="jQuery('#ui-datepicker-div').css('z-index',3000);" onchange="check_to_date();"/></td>
                <td><input type="button" name="do_search" value="  [[.view.]]  " onclick="submitForm();"/></td>
                <!--<input id="at_ts" style="width: 100px;"/>-->
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
    	<div class="revenue_td_menu" id="room_name" style="height:30px;background:[[|items.color|]];">[[|items.name|]]<br />
        <label style="font-size:9px;">[[|items.room_level_name|]]</label></div>
    <!--/LIST:items-->
    </div>
	<div id="menu_bound" style="margin-left:85px;position:absolute;">
   	<?php $tt=0; $from_time = [[=from_time=]]; $to_time = [[=to_time=]];      
	for($i=$from_time; $i<$to_time ; $i+=24*3600)
    {
	    $t = getdate($i);
		if(date('d/m/Y',$i) == date('d/m/Y'))
        {
			echo '<div class="revenue_td" style="font-size:11px;background:#66CCCC; text-align: center; height:15px;">'.date('d/m',$i).'</div>';            
           
        }
        else
        {
            if(substr($t['weekday'],0,3) == 'Sat')
            {
                echo '<div class="revenue_td" style="font-size:11px;height:15px; background:rgba(235, 255, 0, 0.63); text-align: center;">'.date('d/m',$i).'</div>'; 
            }
            else
            {
                if(substr($t['weekday'],0,3) == 'Sun')
                {
                    echo '<div class="revenue_td" style="font-size:11px;height:15px; background:red; text-align: center;">'.date('d/m',$i).'</div>'; 
                }
                else
                {
                    echo '<div class="revenue_td" style="font-size:11px;height:15px;text-align: center;">'.date('d/m',$i).'</div>';   
                }   
            } 
		}
	}
    for($i=$from_time;$i<$to_time ; $i+=24*3600)
    {
         $t = getdate($i);
         if(date('d/m/Y',$i) == date('d/m/Y'))
         {
			echo '<div class="revenue_td" style="font-size:11px;height:15px; background:#66CCCC;text-align:center">'.substr($t['weekday'],0,3).'</div>';  
         }
         else
         {
             if(substr($t['weekday'],0,3) == 'Sat')
             {
                echo '<div class="revenue_td" style="font-size:11px;height:15px; background:rgba(235, 255, 0, 0.63);text-align:center">'.substr($t['weekday'],0,3).'</div>'; 
             }
             else
             {
                 if(substr($t['weekday'],0,3) == 'Sun')
                 {
                    echo '<div class="revenue_td" style="font-size:11px;height:15px; background:red;text-align:center">'.substr($t['weekday'],0,3).'</div>'; 
                 }
                 else
                 {
                    echo '<div class="revenue_td" style="font-size:11px;height:15px;text-align:center">'.substr($t['weekday'],0,3).'</div>';  
                 }
             }  
         }
	}
	?>
    <?php 
      foreach([[=total_rooms=]] as $key=>$value)
      {
        $t = getdate($value['date']);
        if(date('dmY',$value['date']) == date('dmY'))
        {
            echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px; background:#66CCCC;text-align:center">'.$value['id'].'/'.($value['num_of_room'] - $value['id'] - $value['repair_houseuse']).'</div>';
        }
        else
        {
            if(substr($t['weekday'],0,3) == 'Sat')
            {
                echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px; background:rgba(235, 255, 0, 0.63);text-align:center">'.$value['id'].'/'.($value['num_of_room'] - $value['id'] - $value['repair_houseuse']).'</div>';
            }
            else
            {
                 if(substr($t['weekday'],0,3) == 'Sun')
                 {
                    echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px; background:red;text-align:center">'.$value['id'].'/'.($value['num_of_room'] - $value['id'] - $value['repair_houseuse']).'</div>'; 
                 }
                 else
                 {
                    echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px;text-align:center">'.$value['id'].'/'.($value['num_of_room'] - $value['id'] - $value['repair_houseuse']).'</div>';
                 }
            }   
        }
      }			
	?>
    </div><div class="clear"></div>
   <div style="margin-top:1px;margin-left:85px;padding-top:51px;">
	<!--LIST:items-->
    <?php $rr_id = 't'; $starttime = 't'; $reservation_status ='t'; ?>
	<div class="revenue_tr" id="revenue_tr_[[|items.name|]]">
    	<!--LIST:items.days-->
        <?php 
			if([[=items.days.day=]] >= Date_Time::to_time(date('d/m/Y')))
            {
                echo '<div class="revenue_td_blank" style="cursor:pointer;" id="room_'.[[=items.id=]].'_'.[[=items.days.day=]].'" onclick="select_room(\''.[[=items.id=]].'\',\''.[[=items.days.day=]].'\'); " lang="'.[[=items.id=]].'_'.[[=items.days.day=]].'" title="Room: '.[[=items.name=]].' &#13 Price: '.System::Display_number([[=items.price=]]).' &#13 Date: '.date('d/m',[[=items.days.day=]]).'"></div>';
			}
            else
            {
                echo '<div class="td_blank_past" id="room_'.[[=items.id=]].'_'.[[=items.days.day=]].'" lang="'.[[=items.id=]].'_'.[[=items.days.day=]].'" style="background:#EDF5FF;" title="Room: '.[[=items.name=]].' &#13 Price: '.System::Display_number([[=items.price=]]).' &#13 Date: '.date('d/m',[[=items.days.day=]]).'"></div>';
			}
            
			if(isset([[=items.days.id=]])) // or ([[=items.days.house_status=]]=='REPAIR')
            {
    			$key = [[=items.days.room_id=]].'_'.[[=items.days.start_time=]].'_'.[[=items.days.end_time=]].'_'.[[=items.days.reservation_room_id=]];
				
                //if($rr_id != [[=items.days.reservation_room_id=]] or (([[=items.days.house_status=]]=='REPAIR' OR [[=items.days.house_status=]]=='HOUSEUSE') and ($rr_id != '' or $starttime != [[=items.days.start_time=]])))
                if($reservation_status != [[=items.days.reservation_status=]] or ($starttime != [[=items.days.start_time=]]))
                {
                    $reservation_status=[[=items.days.reservation_status=]];
                    $starttime = [[=items.days.start_time=]];
					//if([[=items.days.house_status=]]!='REPAIR' && [[=items.days.house_status=]]!='HOUSEUSE')
                    if([[=items.days.reservation_status=]]!='REPAIR' && [[=items.days.reservation_status=]]!='HOUSEUSE')
                    {
						echo '<div class="reservation_'.(([[=items.days.reservation_status=]]=='BOOKED' and ![[=items.days.confirm=]])?'TENTATIVE':[[=items.days.reservation_status=]]).'" 
                                id="reservation_'.$key.'"  title="'.[[=items.days.note=]].'"
                                draggable="true"
                                onMouseOver="
                    				var r_r_id = \''.[[=items.days.reservation_room_id=]].'\'; 
                                    jQuery(this).bind(\'contextmenu\' , function(e)
                                    {
                                        mouseX = e.pageX; 
                                        mouseY = e.pageY;
                        				reser_act={};
                                        '.([[=items.days.reservation_room_id=]]?
                                            'if(r_r_id !=\'\')
                                            {
                                                reser_act['.[[=items.days.reservation_room_id=]].'] = \''.[[=items.days.reservation_status=]].'\';
                                            }':'').
                                        'check_invisible();
                        				jQuery(\'#myMenu\').css({\'left\' : mouseX , \'top\' : mouseY}).show();
                        				return false; 
                    			    });"  
                                lang="'.[[=items.days.start_time=]].'_'.[[=items.days.nights=]].'">';
						echo '<label name="customer_name"  
                                    class="reservation_'.(([[=items.days.reservation_status=]]=='BOOKED' and ![[=items.days.confirm=]])?'TENTATIVE':[[=items.days.reservation_status=]]).'"\
                                    style=" border:hidden;margin-top:8px;font-size:10px;" maxlength="200" readonly="readonly" id="customer_name_'.$key.'" >
                              '.[[=items.days.cus=]].'</label>';
						echo '<input type = "hidden" id="reservation_'.$key.'_in"  value="'.[[=items.days.reservation_status=]].'"/>';
                        echo '<input type = "hidden" id="reservation_'.$key.'_room_name"  value="'.[[=items.days.room_name=]].'"/>';
                        echo '</div>';
					}
                    else
                    {
                        if([[=items.days.reservation_status=]]=='REPAIR')
                        {
                            $starttime = [[=items.days.start_time=]];
                            echo '<div class="reservation_'.[[=items.days.reservation_status=]].'" 
                                    id="reservation_'.$key.'"  title="'.[[=items.days.note=]].'" 
                                    lang="'.[[=items.days.start_time=]].'_'.[[=items.days.nights=]].'"
                                    onMouseOver="
                        				jQuery(this).bind({
                                            contextmenu : function(e)
                                            {
                            				    mouseX = e.pageX; mouseY = e.pageY;
                                                show_menu_repair();
                            				    jQuery(\'#myMenu\').css({\'left\' : mouseX , \'top\' : mouseY}).show();
                            				    return false; 
                                            }
                                        });" >';	
                            echo '<input name="customer_name"  type="text" value="REPAIR" 
                                    class="reservation_'.(([[=items.days.reservation_status=]]=='BOOKED' and ![[=items.days.confirm=]])?'TENTATIVE':[[=items.days.reservation_status=]]).'" 
                                    style=" border:hidden;margin-top:8px;font-size:10px;" maxlength="200" readonly="readonly" id="customer_name_'.$key.'" >';
                            echo '</div>';
                            echo "<script>
                                    jQuery('#reservation_".$key."').click(function(e)
                                    {
                                        mouseX = e.pageX; 
                                        mouseY = e.pageY;
                                        under_repair('".[[=items.id=]]."',mouseX,mouseY);
                                        return false;    
                                    });
                                </script>"; 
    					}
                        if([[=items.days.reservation_status=]]=='HOUSEUSE')
                        {
                            //echo 'luan';
                            echo '<div class="reservation_'.[[=items.days.reservation_status=]].'" 
                                id="reservation_'.$key.'"  title="'.[[=items.days.note=]].'" 
                                lang="'.[[=items.days.start_time=]].'_'.[[=items.days.nights=]].'">';	
                            echo '<input name="customer_name"  type="text" value="HOUSEUSE" 
                                class="reservation_'.(([[=items.days.reservation_status=]]=='BOOKED' and ![[=items.days.confirm=]])?'TENTATIVE':[[=items.days.reservation_status=]]).'" 
                                style=" border:hidden;margin-top:8px;font-size:10px;" maxlength="200" readonly="readonly" id="customer_name_'.$key.'" >';
                            echo '</div>';
    					}
                    }
                    
						//onClick="reservation_form(\''.[[=items.days.reservation_id=]].'\',\''.[[=items.days.reservation_room_id=]].'\');"
					//echo [[=items.days.cus=]]';
				}
                else
                {
				}
				//$rr_id=[[=items.days.reservation_room_id=]];
        	}
            else
            {
                $reservation_status='t';
                $starttime='t';
            }
			//onclick="window.open(\'?page=reservation&cmd=edit&id='.[[=items.days.reservation_id=]].'&r_r_id='.[[=items.days.reservation_room_id=]].'\');"
        ?>
    	<!--/LIST:items.days-->
    </div><div class="clear"></div>
    <!--/LIST:items-->
   </div> 
   <div class="booking-toolbar">
   		[[.room_status.]]: BOOKED &nbsp;<input name="booked" type="text" style="background:#75ACFF;width:100px;" readonly="readonly" />
   		TENTATIVE &nbsp;<input name="booked" type="text" style="background:#339900;width:100px;" readonly="readonly" />        
        OCCUPIED &nbsp;<input name="booked" type="text" style="background:#FF954F;width:100px;" readonly="readonly" />
        CHECKOUT &nbsp;<input name="booked" type="text" style="background:#FF9999;width:100px;" readonly="readonly" />
        REPAIR &nbsp;<input name="repair" type="text" style="background:#606060;width:100px;" readonly="readonly" />
        HOUSEUSE &nbsp;<input name="houseuse" type="text" style="background:#cc11cc;width:100px;" readonly="readonly" />
   </div>
     <div class="booking-toolbar" style="display:none;">
	<!--IF:reservation(User::can_edit(false,ANY_CATEGORY))-->
	&nbsp;&nbsp;&nbsp;&nbsp;<strong>Price: </strong>&nbsp;
	<!--LIST:room_types-->
	[[|room_types.name|]] <input type="text" id="room_price_[[|room_types.id|]]" value="[[|room_types.price|]]" style="width:65px;color:#0033FF">
	<!--/LIST:room_types-->
	<strong>Default:</strong>&nbsp;Time in: <input type="text" id="time_in" value="<?php echo CHECK_IN_TIME;?>" style="width:40px;color:#FF3300">&nbsp;Time out: <input type="text" id="time_out" value="<?php echo CHECK_OUT_TIME;?>" style="width:40px;color:#FF3300">&nbsp;
	<!--/IF:reservation-->
    </div>
	<table cellpadding="3" id="myMenu" class="contextMenu">
        <tr class="li_menu" id="assign">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1277714566_room_map.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Assign - Room </td>
        </tr>
        <tr class="li_menu" id="un_assign">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1277714566_room_map.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Unassign - Room </td>
        </tr>
        <tr class="li_menu" id="un_repair">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1277714566_room_map.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Unrepair - Status </td>
        </tr>
        <tr class="li_menu" id="repair">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1277713392_setting.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Repair - Status </td>
        </tr>
    	<tr class="li_menu" id="add">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303201760_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Add - Reservation </td>
        </tr>
    	<tr class="li_menu" id="edit">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/accounting_report.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Edit - Reservation</td>
        </tr>
        <tr class="li_menu" id="cancel" style="display:none;">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303201782_cancel.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Cancel</td>
        </tr>
         <tr class="li_menu" id="check_in">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303201760_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Checkin</td>
        </tr>
         <tr class="li_menu" id="change_room" style="display:none;">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1307957422_change_room.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Change room</td>
        </tr>
         <tr class="li_menu" id="view">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/accounting_report.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">View order</td>
        </tr>
    	<tr class="li_menu" id="extra_service">
        	<td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1303201760_checkin.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Add - Extra service</td>
        </tr>
    </table>
   
</div>
 <div id="reser_detail"></div>
 </form>
 <?php 
    $a = json_encode($this->map['items']); 
    //echo ($a);
?>
<?php 
    echo '<script>';
    echo 'var block_id = '.Module::block_id().';';
    echo 'var default_checkin_time = \''.CHECK_IN_TIME.'\';';
    echo 'default_checkout_time = \''.CHECK_OUT_TIME.'\';';
    echo '</script>'; 
?>
<script>
		setBeginPosition();
        // cac bien de file monthly_room_report.js co the su dung
        var rooms_js = <?php echo json_encode($this->map['items']); ?>;
        //console.log(rooms_js);
        //console.log(items_js);
        var orgRoom_id=-1;
        var orgDate=-1;
        // variable for select by ctrl button
        var select_day = 0;
        var time_today = <?php echo Date_Time::to_time(date('d/m/Y')); ?>;
		var time_from = [[|time_from|]];
		var time_to = [[|time_to|]];
		var width_window = to_numeric(jQuery(window).width());
		var num_days = [[|num_days|]];	
		room_types_js = [[|room_types_js|]]; 
        var to_day = [[|to_day|]]; var width = 0;
		//alert(to_day);
		reser_act = {};
		flag = {};
		reservation = {};
		rooms = {};
		$('from_date').value = '[[|from_date|]]';
		$('to_date').value = '[[|to_date|]]';
		jQuery("#from_date").datepicker({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
	jQuery(window).scroll(function() 
    {
        jQuery('#slideMenu').animate(
        { 
            left: to_numeric(jQuery(window).scrollLeft())+4 + 'px' 
        },
    	{ 
    	    queue: false, duration: 50
        }
    );
	});
	jQuery(window).scroll(function()
    {
    	jQuery('#menu_bound').css('position','absolute');	
        jQuery('#menu_bound').animate(
            { top: (to_numeric(jQuery(window).scrollTop())) + 'px' },
    		{ queue: false, duration: 50}
        );
    	if(to_numeric(jQuery(window).scrollTop())<242)
        {
    		jQuery('#menu_bound').css('margin-top',242-to_numeric(jQuery(window).scrollTop()));		
    	}
        else
        {
    		jQuery('#menu_bound').css('margin-top','0px');		
    	}
	});
	jQuery('.revenue_td_blank').each(function()
    {
			var index = this.lang;
			room_id = index.substr(0,index.lastIndexOf("_"));
			day = index.substr(index.lastIndexOf("_")+1,index.length -1);
			flag[room_id+'_'+day] = 0;	
			reservation[room_id+'_1'] = {};
			reservation[room_id+'_2'] = {};
	});
	jQuery(document).ready( function() 
    {
        init_monthly_room_report();
	});
//start = start_time cua chang chuyen den
// original_start_time = start_time cua chang ban dau
function save_position(rr_id,room_id,start,nights,action,reservation_status,div_id,from_room_name,room_name,original_start_time)
{
	var text= '<div id="dialog" class="web-dialog"><div class="info"><span class="title_bound">[[.reason_change_room.]]'+start+'</span><img width="15" height="15" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="returnOldPosition();HideDialog(\'dialog\');" style="float:right;"/></div>';
	text += '<table id="detail"><tr><td>[[.order_id.]] : </td><td>'+rr_id+'</td></tr>';
	text += '<tr><td>[[.change_from_room.]]</td><td>'+from_room_name+'</td></tr>';
    text += '<tr><td>[[.change_to_room.]]</td><td>'+room_name+'</td></tr>';
	text += '<tr><td>[[.arrival_time.]] : </td><td>'+get_date(start)+'</td></tr>';
	text += '<tr><td>[[.departure_time.]]</td><td>'+get_date(to_numeric(start)+(to_numeric(nights)*86400))+'</td></tr>';
    if(reservation_status == 'LT')
    {
        text += '<tr><td>[[.assign.]]</td><td><input name = "for_assign" type = "checkbox" id = "for_assign"/></td></tr>';
        text += '<tr><td>[[.for_space_only.]]</td><td><input name = "for_space_only" type = "checkbox" id = "for_space_only"/></td></tr>';
    }
	text += '<tr><td>[[.reason.]]</td><td> </td></tr>';
	text += '<tr><td colspan="2"><textarea id="reason_change" rows="5" cols="32" ></textarea></td></tr>';
	text += '<tr><td colspan=2><input name="chang_status" type="button" value="[[.update_change.]]" id="change_room" onclick="save('+rr_id+','+room_id+','+start+','+nights+',\''+action+'\','+div_id+',\''+reservation_status+'\','+original_start_time+');"><input name="exit" type="button" value="[[.cancel.]]" id="exit" onclick="returnOldPosition();HideDialog(\'dialog\');jQuery(\'#mask\').hide();"></td></tr>';
	text += '</table></div>';
	jQuery('#reser_detail').html(text);
	jQuery("#dialog").css('z-index',2500);	
	jQuery("#dialog").fadeIn(300);	
}
</script>