<style>
.simple-layout-middle{width:100%;}
input[type=text]{
    width: 120px;
}
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div class="body">
	<form name="ForgotObjectListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.list_forgot_object.]]</td>
        	<?php if(User::can_add(false,ANY_CATEGORY)) { ?>
        	<td style="width: 40%; text-align: right; padding-right: 30px;"><a href="<?php echo Url::build_current(array('cmd'=>'add')+array('forgot_object_room_id','forgot_object_name', 'forgot_object_object_type', 'forgot_object_last_name', 'forgot_object_first_name', 'forgot_object_country', 'forgot_object_check_in_date_start','forgot_object_check_in_date_end', 'forgot_object_check_out_date_start','forgot_object_check_out_date_end', 'forgot_object_status', ));?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a>
        	<?php } ?>
        	<?php if(User::can_delete(false,ANY_CATEGORY)) { ?>
        	<input type="submit" name="delete_selected" value="[[.Delete.]]" class="w3-btn w3-red" style="text-transform: uppercase;" /></td>
        	<?php } ?>
        </tr>
	</table>
    	
	<table cellspacing="0" width="100%">
	<tr>
		<td>&nbsp;</td>
		<td width="100%">
			<fieldset style="margin-bottom:10px;">
			<legend class="" style="text-transform: uppercase;">[[.search.]]</legend>
			<table width="100%">
            
            <tr>
                <!--Start Luu Nguyen Giap add portal -->
                <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                <td >[[.hotel.]]</td>
                <td style="margin: 0;" ><select name="portal_id" id="portal_id" style="height: 24px;"></select></td>
                <?php //}?>
                <!--End Luu Nguyen Giap add portal -->
            	<td >[[.object_name.]]: </td>
                <td ><input name="name" type="text" id="name" size="30" style="height: 24px;"/></td>
                <td >[[.check_in_date.]]: </td>
                <td > <input name="time_start" type="text" id="time_start" size="12" style="width: 80px; height: 24px;" /></td>
                <td >[[.to_date.]]</td>
                <td ><input name="time_end" type="text" id="time_end" size="12" style="width: 80px; height: 24px;"/></td>
                <td>[[.employee_id.]]: </td>
                <td><input name="employee_id" type="text" id="employee_id" size="30" style="height: 24px;"/></td>
                <td>[[.object_type.]]:</td>
                <td><select name="object_type"id="object_type" style="height: 24px;"></select></td>
                <td>[[.status.]] : </td>
                <td colspan="4"><select  name="status" id="status" style="height: 24px;">
                                                    <option value="">[[.all.]]</option>
                                                    <option value="0">[[.notpay.]]</option>
                                                    <option value="1">[[.pay.]]</option>
                                                    <option value="2">[[.handled.]]</option>
                                                    </select>
                                                    <script>jQuery("#status").val(<?php echo Url::get('status'); ?>);</script>
                </td>     
                <td><input type="submit" value="[[.search.]]" style="height: 24px;"/></td>
            </tr>
			</table>
			</fieldset>
            
			<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#C6E2FF" border="1" style="border-collapse:collapse">
				<tr class="w3-light-gray" style="line-height:20px; text-transform: uppercase; text-align: center;">
					<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"/></th>
					<th align="center" width="100px">
						<a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.name'));?>">
						<?php if(URL::get('order_by')=='forgot_object.name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.name.]]								</a>							
                    </th>
                    <th align="center" width="80px">
						<a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.object_type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.object_type'));?>">
						<?php if(URL::get('order_by')=='forgot_object.object_type') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.object_type.]]								</a>
					</th>
                    <th align="center" width="50px">
						<a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.object_code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.object_code'));?>">
						<?php if(URL::get('order_by')=='forgot_object.object_code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.object_code.]]								</a>
					</th>
					<th align="center" width="30px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.object_type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.object_type'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.quantity') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.SL.]] </a>
                    </th>
					<th align="center" width="50px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.object_type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.object_type'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.unit') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.unit.]] </a>
                    </th>
					<th align="center" width="50px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.object_type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.object_type'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.room_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room.]] </a>
                    </th>
					<th align="center" width="50px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.position' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.position'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.position') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.position.]] </a>
                    </th>
					<th align="center" width="50px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.reason' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.reason'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.reason') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.reason.]] </a>
                    </th>
					<th align="center" width="80px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.guest_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.guest_name'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.guest_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.guest_name.]] </a>
                    </th>
                    <th align="center" width="80px"><a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.company_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.company_name'));?>">
					  <?php if(URL::get('order_by')=='forgot_object.company_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.company_name.]] </a>
                    </th>
                    
					<th align="center" width="60px">
						<a href="<?php echo URL::build_current(((URL::get('order_by')=='forgot_object.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'forgot_object.status'));?>">
						<?php if(URL::get('order_by')=='forgot_object.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]								</a>
					</th>
					<th align="center" width="100px">[[.forgot_time.]]</th>
					<?php if(User::can_edit(false,ANY_CATEGORY)) { ?>
                    <th width="1%">[[.edit.]]</th>
					<?php }
					if(User::can_delete(false,ANY_CATEGORY)) { ?>
                    <th width="1%">[[.delete.]]</th>
					<?php } ?>
                </tr>
				<!--LIST:items-->
				<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;">
					<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"/></td>
					<td align="left">[[|items.name|]]</td>
                    <td align="left">[[|items.object_type|]]</td>
                    <td align="left">[[|items.object_code|]]</td>
					<td align="left">[[|items.quantity|]]</td>
					<td align="left">[[|items.unit|]]</td>
                    <td align="left">[[|items.room_name|]]</td>
                    <td align="left">[[|items.position|]]</td>
                    <td align="left">[[|items.reason|]]</td>
                    <td align="left">[[|items.guest_name|]]</td>
                    <td align="left">[[|items.company_name|]]</td>
					<!--<td align="left">[[|items.first_name|]] [[|items.last_name|]]</td>
					<td align="left">[[|items.country|]]</td>-->
					<td align="left">
					<!--IF:check(MAP['items']['current']['status']==0)-->
						[[.notpay.]]
					<!--ELSE-->
                        <!--IF:check_1(MAP['items']['current']['status']==1)-->
						<img src="../../../../../skins/default/images/room/paid.gif" alt="Đã trả" />
                        <!--ELSE-->
                        [[.handled.]]
                        <!--/IF:check_1-->
					<!--/IF:check-->
					</td>
					<td align="left">[[|items.time|]]</td>
					<?php if(User::can_edit(false,ANY_CATEGORY)) { ?>
                    <td>
						&nbsp;<a href="<?php echo Url::build_current(array('forgot_object_room_id', 'forgot_object_name', 'forgot_object_object_type', 'forgot_object_last_name', 'forgot_object_first_name', 'forgot_object_country', 'forgot_object_check_in_date_start','forgot_object_check_in_date_end', 'forgot_object_check_out_date_start','forgot_object_check_out_date_end', 'forgot_object_status', )+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
                    </td>
					<?php }
					if(User::can_delete(false,ANY_CATEGORY)) { ?>
                    <td>
						&nbsp;<a href="<?php echo Url::build_current(array('forgot_object_room_id',  'forgot_object_name', 'forgot_object_object_type', 'forgot_object_last_name', 'forgot_object_first_name', 'forgot_object_country', 'forgot_object_check_in_date_start','forgot_object_check_in_date_end', 'forgot_object_check_out_date_start','forgot_object_check_out_date_end', 'forgot_object_status', )+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a></td>
					<?php } ?>
                </tr>
				<!--/LIST:items-->
			</table>
			[[|paging|]]
			<p>
			<table><tr>
				<?php if(User::can_add(false,ANY_CATEGORY)) { ?>
                <td><?php /*Draw::button(Portal::language('add_new'),Url::build_current(array('cmd'=>'add')+array('forgot_object_room_id', 'forgot_object_name', 'forgot_object_object_type', 'forgot_object_last_name', 'forgot_object_first_name', 'forgot_object_country', 'forgot_object_check_in_date_start','forgot_object_check_in_date_end', 'forgot_object_check_out_date_start','forgot_object_check_out_date_end', 'forgot_object_status', )));*/?></td>
				<?php }
				if(User::can_delete(false,ANY_CATEGORY)) { ?>
                <td><?php //Draw::button(Portal::language('delete_selected'),'delete_selected',false,true,'ForgotObjectListForm');?></td>
				<?php } ?>
            </tr></table>
			</p>
			</form>
		</td>
	</tr>
	</table>
</div>
<script type="text/javascript">
	jQuery('#time_start').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#time_end').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
</script>