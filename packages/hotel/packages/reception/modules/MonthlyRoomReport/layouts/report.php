<style>
	.buttonsplitmenu
    {
		z-index:20000;	
	}
    #no_items_no_select
    {
        width: 20px; height: auto; position: absolute; top: 0px; left: 0px;
    }
    #no_items
    {
        width: 20px; height: auto; position: absolute; top: 20px; left: 0px;
    }
    #deselect:hover #no_items_no_select
    {
        top: 20px;
    }
    #deselect:hover #no_items
    {
        top: 0px;
    }
</style>
<link rel="stylesheet" href="packages/hotel/packages/reception/skins/default/css/monthy_room_report.css" type="text/css" />
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
		<table width="100%" id="hidden">
            <tr><td>
    		<fieldset>
            <legend><b>[[.time_select.]]</b></legend>
    		<table border="0" style="margin:auto;">
            	<tr style="text-align:center;">
                	<td></td>
                	<td>[[.from_date.]]:&nbsp;&nbsp;
                	<input type="text" name="from_date" id="from_date" class="date-input" onclick="jQuery('#ui-datepicker-div').css('z-index',3000);" onchange="check_from_date();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>[[.to_date.]]:&nbsp;&nbsp;
                    <input type="text" name="to_date" id="to_date" class="date-input" onclick="jQuery('#ui-datepicker-div').css('z-index',3000);" onchange="check_to_date();"/></td>
                    <td>[[.order_by.]]:&nbsp;&nbsp;
                    <select name="room_order" id="room_order"></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><input type="button" name="do_search" value="  [[.view.]]  " onclick="submitForm();"/></td>
                    <!--<input id="at_ts" style="width: 100px;"/>-->
    			</tr>
    		  </table>
    		  </fieldset>
    		  </td>
            </tr>
            
        </table>
	</td></tr></table>
</td></tr></table>

<div id="revenue">
	<div id="slideMenu">
    	<div class="revenue_td_menu" style="height:50px;">[[.room.]]</div>
    <!--LIST:items-->
    	<div class="revenue_td_menu" id="room_name" style="height:30px;"><div align="right" style="color:[[|items.color|]]; font-size:18px;">[[|items.name|]]<br /></div>
        <div style="color:[[|items.color|]];"><label style="font-size:9px;">[[|items.room_level_name|]]</label></div></div>
    <!--/LIST:items-->
    </div>
	<div id="menu_bound" style="margin-left:85px;position:absolute;">
   	<?php $tt=0; $from_time = [[=from_time=]]; $to_time = [[=to_time=]];      
	for($i=$from_time; $i<$to_time ; $i+=24*3600)
    {
	    $t = getdate($i);
		if(date('d/m/Y',$i) == date('d/m/Y'))
        {
			echo '<div class="revenue_td" style="font-size:11px;background:#ff00ff; text-align: center; height:15px;">'.date('d/m',$i).'</div>';            
           
        }
        else
        {
            if(substr($t['weekday'],0,3) == 'Sat')
            {
                echo '<div class="revenue_td" style="font-size:11px;height:15px; color:#0000ff; text-align: center;">'.date('d/m',$i).'</div>'; 
            }
            else
            {
                if(substr($t['weekday'],0,3) == 'Sun')
                {
                    echo '<div class="revenue_td" style="font-size:11px;height:15px; color:red; text-align: center;">'.date('d/m',$i).'</div>'; 
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
			echo '<div class="revenue_td" style="font-size:11px;height:15px; background:#ff00ff;text-align:center">'.substr($t['weekday'],0,3).'</div>';  
         }
         else
         {
             if(substr($t['weekday'],0,3) == 'Sat')
             {
                echo '<div class="revenue_td" style="font-size:11px;height:15px; color:#0000ff; text-align:center">'.substr($t['weekday'],0,3).'</div>'; 
             }
             else
             {
                 if(substr($t['weekday'],0,3) == 'Sun')
                 {
                    echo '<div class="revenue_td" style="font-size:11px;height:15px; color:red;text-align:center">'.substr($t['weekday'],0,3).'</div>'; 
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
            echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px; background:#ff00ff;text-align:center">'.$value['id'].'/'.($value['total_avai_room']).'</div>';
        }
        else
        {
            if(substr($t['weekday'],0,3) == 'Sat')
            {
                echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px; color:#0000ff;text-align:center">'.$value['id'].'/'.($value['total_avai_room']).'</div>';
            }
            else
            {
                 if(substr($t['weekday'],0,3) == 'Sun')
                 {
                    echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px; color:red;text-align:center">'.$value['id'].'/'.($value['total_avai_room']).'</div>'; 
                 }
                 else
                 {
                    echo '<div id = "date_'.Date_Time::to_time(date('d/m/Y',$value['date'])).'" class="revenue_td" style="font-size:11px; height:15px;text-align:center">'.$value['id'].'/'.($value['total_avai_room']).'</div>';
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
            if( isset([[=items.days.close_room=]]) AND [[=items.days.close_room=]]==0)
            {
                echo '<div class="td_blank_past" style="background:#EEEEEE; overflow: hidden;" title="Room: '.[[=items.name=]].' Do not use"></div>';
            }
            else
            {
                if([[=items.days.day=]] >= Date_Time::to_time(date('d/m/Y')))
                {
                    echo '<div class="revenue_td_blank" style="cursor:pointer; overflow: hidden;" id="room_'.[[=items.id=]].'_'.[[=items.days.day=]].'" onclick="select_room(\''.[[=items.id=]].'\',\''.[[=items.days.day=]].'\'); " lang="'.[[=items.id=]].'_'.[[=items.days.day=]].'" title="Room: '.[[=items.name=]].' &#13 Price: '.System::Display_number([[=items.price=]]).' &#13 Date: '.date('d/m',[[=items.days.day=]]).'"></div>';
    			}
                else
                {
                    echo '<div class="td_blank_past" id="room_'.[[=items.id=]].'_'.[[=items.days.day=]].'" lang="'.[[=items.id=]].'_'.[[=items.days.day=]].'" style="background:#EDF5FF; overflow: hidden;" title="Room: '.[[=items.name=]].' &#13 Price: '.System::Display_number([[=items.price=]]).' &#13 Date: '.date('d/m',[[=items.days.day=]]).'"></div>';
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
    						echo '<div style=" overflow: hidden; '.([[=items.days.do_not_move=]]==1?'border: 1px solid #000000; width: 36px; height: 28px;':'').' " class="reservation_'.(([[=items.days.reservation_status=]]=='BOOKED' and ![[=items.days.confirm=]])?'TENTATIVE':[[=items.days.reservation_status=]]).'" 
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
                                        style=" border:hidden;margin-top:8px;font-size:10px; overflow: hidden; word-break: keep-all;" maxlength="200" readonly="readonly" id="customer_name_'.$key.'" >
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
                                echo '<div style=" overflow: hidden;"  class="reservation_'.[[=items.days.reservation_status=]].'" 
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
                                echo '<div style=" overflow: hidden;"  class="reservation_'.[[=items.days.reservation_status=]].'" 
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
            }
			//onclick="window.open(\'?page=reservation&cmd=edit&id='.[[=items.days.reservation_id=]].'&r_r_id='.[[=items.days.reservation_room_id=]].'\');"
        ?>
    	<!--/LIST:items.days-->
    </div><div class="clear"></div>
    <!--/LIST:items-->
   </div> 
   <div style="margin: auto; height: 32px; width: 100%;"></div>
   <div class="booking-toolbar">
   		[[.room_status.]]:
        BOOK NOT ASSIGN &nbsp;<input name="houseuse" type="text" style="background:#00b2f9;width:20px;" readonly="readonly" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BOOKED &nbsp;<input name="booked" type="text" style="background:#5290CA;width:20px;" readonly="readonly" />
   		<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TENTATIVE &nbsp;<input name="booked" type="text" style="background:#339900;width:20px;" readonly="readonly" />-->     
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OCCUPIED &nbsp;<input name="booked" type="text" style="background:#EFE87D;width:20px;" readonly="readonly" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHECKOUT &nbsp;<input name="booked" type="text" style="background:#BD4FBD;width:20px;" readonly="readonly" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REPAIR &nbsp;<input name="repair" type="text" style="background:#606060;width:20px;" readonly="readonly" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HOUSEUSE &nbsp;<input name="houseuse" type="text" style="background:#cc11cc;width:20px;" readonly="readonly" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DO-NOT-USE &nbsp;<input name="houseuse" type="text" style="background:#EEEEEE;width:20px;" readonly="readonly" />
        <div id="option_select" style="display: none; float: right;" >
            
            <div id="multi" style="width: 20px; height: 20px; cursor: pointer; overflow: hidden; position: relative; float: right; margin-right: 10px;" onclick="jQuery('#option_selected').val(1); fun_select_icon(1);">
                <img id="mutil_items_no_select" src="packages\core\skins\default\images\icon_table\mutil_items_no_select.png" style="width: 20px; height: auto; position: absolute; top: 0px; left: 0px;" title="select mutil items" />
                <img id="mutil_items" src="packages\core\skins\default\images\icon_table\mutil_items.png" style="width: 20px; height: auto; position: absolute; top: 20px; left: 0px;" title="select mutil items"  />
            </div>
            <div id="one" style="width: 20px; height: 20px; cursor: pointer; overflow: hidden; position: relative; float: right; margin-right: 10px;" onclick="jQuery('#option_selected').val(0); fun_select_icon(0);">
                <img id="one_items" src="packages\core\skins\default\images\icon_table\one_items.png" style="width: 20px; height: auto; position: absolute; top: 0px; left: 0px;" title="select one items"  />
                <img id="one_items_no_select" src="packages\core\skins\default\images\icon_table\one_items_no_select.png" style="width: 20px; height: auto; position: absolute; top: 20px; left: 0px;" title="select one items"  />
            </div>
            <span style="float: right; margin: 0px; margin-right: 10px; padding: 0px; width: 1px; height: 20px; border-right: 1px solid rgba(0,0,0,.3);"></span>
            <span style="float: right; margin: 0px; padding: 0px; width: 1px; height: 20px; border-left: 1px solid rgba(0,0,0,.3);"></span>
            <div id="deselect" style="width: 20px; height: 20px; cursor: pointer; overflow: hidden; position: relative; float: right; margin-right: 10px;" onclick="deSelectAll();">
                <img id="no_items_no_select" src="packages\core\skins\default\images\icon_table\no_items_no_select.png" title=" no select items" />
                <img id="no_items" src="packages\core\skins\default\images\icon_table\no_items.png" title=" no select items"  />
            </div>
            
            <input name="option_selected" type="text" id="option_selected" value="0" style="display: none;" />
        </div>
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
        <!--luu nguyen giap move status repair-->
        <tr class="li_menu" id="repair">
            <td width="20%" style="background:#E7F2F8;" class="td_img"><img src="resources/default/1277713392_setting.png" class="img_icon"></td>
            <td style="background:#FFFFFF;" class="td_title">Repair - Status </td>
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
        var items_org = <?php echo json_encode($this->map['items_org']); ?>;
        //console.log(rooms_select);
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
		jQuery("#from_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker();//{ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
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
    	    queue: false, duration: 0
        }
    );
	});
    
    
	jQuery(window).scroll(function()
    {
    	scroll_dat();
	});
    
    function scroll_dat()
    {
        jQuery('#menu_bound').css('position','absolute');	
        jQuery('#menu_bound').animate(
            { top: (to_numeric(jQuery(window).scrollTop())) + 'px' },
    		{ queue: false, duration: 0}//Luu Nguyen Giap edit value old = 50
        );  
        var sideMenu = document.getElementById("slideMenu");
    	if(to_numeric(jQuery(window).scrollTop())<sideMenu.offsetTop)
        {
    		jQuery('#menu_bound').css('margin-top',sideMenu.offsetTop-to_numeric(jQuery(window).scrollTop()));		
    	}
        else
        {
    		jQuery('#menu_bound').css('margin-top','0px');		
    	} 
        //Luu Nguyen Giap add 
        jQuery('.booking-toolbar').css('position','absolute');
        jQuery('.booking-toolbar').css('z-index','3000');
        jQuery('.booking-toolbar').animate(
            { bottom:  '32px' },
            { queue: false, duration: 0}//Luu Nguyen Giap edit value old = 50
        );  
        if(to_numeric(jQuery(window).scrollTop())>0)
        {
            jQuery('.booking-toolbar').css('margin-bottom','-'+(to_numeric(jQuery(window).scrollTop()))+ 'px');        
        }
        else
        {
            jQuery('.booking-toolbar').css('margin-bottom','0px');        
        }   
        //End Luu Nguyen Giap
    }
    
    
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
        scroll_dat();
	});
//start = start_time cua chang chuyen den
// original_start_time = start_time cua chang ban dau
function save_position(rr_id,room_id,start,nights,action,reservation_status,div_id,from_room_name,room_name,original_start_time)
{
	var text= '<div id="dialog" class="web-dialog"><div class="info"><span class="title_bound">[[.reason_change_room.]]</span><img width="15" height="15" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="returnOldPosition();HideDialog(\'dialog\');" style="float:right;"/></div>';
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
	text += '<tr><td><input name="use_old_price" type="checkbox" id="use_old_price" /> [[.use_old_price.]]</td><td> </td></tr>';
	text += '<tr><td colspan=2><input name="chang_status" type="button" value="[[.update_change.]]" id="change_room" onclick="save('+rr_id+','+room_id+','+start+','+nights+',\''+action+'\','+div_id+',\''+reservation_status+'\','+original_start_time+');"><input name="exit" type="button" value="[[.cancel.]]" id="exit" onclick="returnOldPosition();HideDialog(\'dialog\');jQuery(\'#mask\').hide();"></td></tr>';
	text += '</table></div>';
	jQuery('#reser_detail').html(text);
	jQuery("#dialog").css('z-index',2500);	
	jQuery("#dialog").fadeIn(300);	
}
function submitForm()
{
    var url = '?page=monthly_room_report&manager=0';
	url += '&from_date='+($('from_date').value);
	url += '&to_date='+($('to_date').value);
    var room_order = jQuery('#room_order').val();
    url += '&room_order='+room_order;
	window.open(url,'_self');
}
function fun_select_icon(key)
{
    
    if(key==0)
    {
        jQuery("#one_items").css('top','0px');
        jQuery("#one_items_no_select").css('top','20px');
        jQuery("#mutil_items").css('top','20px');
        jQuery("#mutil_items_no_select").css('top','0px');
    }
    else
    {
        jQuery("#one_items").css('top','20px');
        jQuery("#one_items_no_select").css('top','0px');
        jQuery("#mutil_items").css('top','0px');
        jQuery("#mutil_items_no_select").css('top','20px');
    }
}
</script>
