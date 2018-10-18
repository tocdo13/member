<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditPlanForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="100%" class="form-title" style="text-indent: 50px;"><?php echo Portal::language('manage_plan_in_month');?></td>
        <td width="1%" style="height: 30px;width: 100px;"><a href="<?php echo Url::build_current();?>" class="w3-button w3-gray w3-hover-gray" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;height: 30px;width: 100px;"><?php echo Portal::language('back');?></a></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="<?php echo Portal::language('Save');?>" name="save" class="w3-button w3-orange w3-hover-orange"/></td><?php }?>
        <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="<?php echo Portal::language('save_stay');?>" name="save_stay" class="w3-button w3-blue w3-hover-blue"/></td><?php }?>
		
    </tr>
</table>

<div class="search-box">	
    <fieldset>
    	<legend class="title"><?php echo Portal::language('select');?></legend>
        
        <table border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('year');?></td>
					<td>:</td>
					<td>
						<select  name="year" id="year" style="width:80px;" onchange="EditPlanForm.submit()">
                        <?php
                            for($i=date('Y')+1;$i>=BEGINNING_YEAR;$i--)
                            {
                            echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
                            }
                        ?>
						</select>
					</td>
				</tr>
		</table>
    </fieldset>
</div>
<br />
<br />
<table cellspacing="0">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr>
        <td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
		<table border="0" width="1630px">
    		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
        	<tr bgcolor="#EEEEEE" valign="top" style="overflow: auto;">
    			<td style="overflow: auto;">
        			<div style="background-color:#EFEFEF;">
        				<span id="mi_plan_all_elems">
        					<span style="white-space:nowrap; width:100%; height: 30px;">
        						<span class="multi-input-header" style="text-align: center;;width:40px;height: 30px;"><?php echo Portal::language('month');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('units_built');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:60px;height: 30px;"><?php echo Portal::language('room_repair');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('rooms_available');?><br /><?php echo Portal::language('for_sale');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('room_soild');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('rooms');?><br /><?php echo Portal::language('complimentary');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('total_rooms_1');?><br /><?php echo Portal::language('occupied_1');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('house_use_rooms');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:80px;height: 30px;"><?php echo Portal::language('no_of_guests');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:120px;height: 30px;"><?php echo Portal::language('room_revenue');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:120px;height: 30px;"><?php echo Portal::language('revenue');?><br /><?php echo Portal::language('eat_drink');?>&nbsp;<?php echo Portal::language('bar');?>&nbsp;<?php echo Portal::language('party');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:105px;height: 30px;"><?php echo Portal::language('telephone_revenue');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:100px;height: 30px;"><?php echo Portal::language('laundry_revenue');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:100px;height: 30px;"><?php echo Portal::language('minibar_revenue');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:100px;height: 30px;"><?php echo Portal::language('revenue');?><br /><?php echo Portal::language('transport_service');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:100px;height: 30px;"><?php echo Portal::language('revenue');?><br /><?php echo Portal::language('spa_service');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:100px;height: 30px;"><?php echo Portal::language('revenue');?><br /><?php echo Portal::language('service_others');?></span>
                                <span class="multi-input-header" style="text-align: center;;width:100px;height: 30px;"><?php echo Portal::language('revenue');?><br /><?php echo Portal::language('vending');?></span>
        						<br clear="all"/>
        					</span>
        				</span>
        			</div>
                    <?php for($i=1;$i<=12;$i++){?>
                    <div style="background-color:#EFEFEF;">
        				<span id="mi_plan_all_elems">
        					<span style="white-space:nowrap; width:100%;">
        						<span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][month]" style="width:40px;text-align: center;" readonly="readonly" type="text" id="month_<?php echo $i;?>" value="<?php echo $i;?>"/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][units_built]" style="width:80px;text-align: right;" type="text" id="units_built_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][room_repair]" style="width:60px;text-align: right;" type="text" id="room_repair_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][rooms_available_for_sale]" style="width:80px;text-align: right;" type="text" id="rooms_available_for_sale_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][rooms_sold]" style="width:80px;text-align: right;" type="text" id="rooms_sold_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][complimentary_rooms]" style="width:80px;text-align: right;" type="text" id="complimentary_rooms_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][total_rooms_occupied]" style="width:80px;text-align: right;" type="text" id="total_rooms_occupied_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][house_use_rooms]" style="width:80px;text-align: right;" type="text" id="house_use_rooms_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][no_of_guests]" style="width:80px;text-align: right;" type="text" id="no_of_guests<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][room_revenue]" style="width:120px;text-align: right;" type="text" id="room_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][bar_revenue]" style="width:120px;text-align: right;" type="text" id="bar_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][telephone_revenue]" style="width:105px;text-align: right;" type="text" id="telephone_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][laundry_revenue]" style="width:100px;text-align: right;" type="text" id="laundry_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][minibar_revenue]" style="width:100px;text-align: right;" type="text" id="minibar_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][transport_revenue]" style="width:100px;text-align: right;" type="text" id="transport_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][spa_revenue]" style="width:100px;text-align: right;" type="text" id="spa_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][others_revenue]" style="width:100px;text-align: right;" type="text" id="others_revenue_<?php echo $i;?>" value=""/>
                                </span>
                                <span class="multi-input">
                                    <input name="mi_plan[<?php echo $i;?>][vending_revenue]" style="width:100px;text-align: right;" type="text" id="vending_revenue_<?php echo $i;?>" value=""/>
                                </span>
        					</span>
        				</span>
        			</div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>


</script>