<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="60%"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.bar_reservation_list.]] [[|bar_name|]] <?php echo Url::get('status');?></td>
                    <!--Luu Nguyen GIap comment remove button datban  -->
					<?php //if(User::can_add(false,ANY_CATEGORY)){?><!--<td width="1%" align="right"><a href="<?php //echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.bar_reservation.]]</a></td>--><?php //}?>
                     <!--End Luu Nguyen Giap-->
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <td width="40%" style="text-align: right; padding-right: 30px;"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};BarReservationNewListForm.cmd.value='delete';BarReservationNewListForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a></td><?php }?>                    
                </tr>
                <tr>
                    <td>
                        <?php if(Form::$current->is_error()){?><div><?php echo Form::$current->error_messages();?></div><?php }?>
                    </td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
			<td>
				<fieldset>
                <legend class="" style="text-transform: uppercase;">[[.search_options.]]</legend>
                <form method="post" name="SearchBarReservationNewForm"> 
                <table>
                    <tr>
                      <!--<td align="right" nowrap="nowrap">[[.product_code.]]</td>
                      <td>:</td>
                      <td nowrap="nowrap"><input name="product_code" type="text" id="product_code" style="width:80px;" class="date-input" />                      </td>-->
                      <td align="right" nowrap>[[.invoice_number.]] <input name="invoice_number" type="text" id="invoice_number" class="date-input" style="height: 24px;" /></td>
                        <td align="right" nowrap>[[.arrival_time.]]</td>
                        
                        <td nowrap>
                                <input name="from_arrival_time" type="text" id="from_arrival_time" style="width:80px; height: 24px;" onchange="changevalue();" class="date-input">&nbsp;[[.to.]]
                                <input name="to_arrival_time" type="text" id="to_arrival_time" style="width:80px; height: 24px;" class="date-input" onchange="changefromday();" /></td>
                                
                        <td nowrap>&nbsp;</td>
                        <td><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value" style="height:24px;"></select></td>
                        
                        <td>&nbsp;</td>
                        <td align="right" style="text-align:right;">
                                [[.bar_name.]] <select name="bars" id="bars" onchange="updateBar();" style="height: 24px;"></select> 
                                <input name="acction" type="hidden" value="0" id="acction" style="height: 24px;" />
                                <script>
                                    var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                                    if(bar_id != ''){
                                    	$('bars').value = bar_id;	
                                    }
                                 </script>
                        </td>
                        <td nowrap><input class="w3-btn w3-gray" type="submit" value="[[.search.]]" style="height: 24px; padding-top: 5px;"/></td>
                    </tr>
                </table>
                </form>
                </fieldset>
				<form name="BarReservationNewListForm" method="post">
				<p>
				<table width="100%" cellpadding="2" style="display: none;">
                    <tr>
                        <td width="1%"><input type="button" value="[[.list_title_debt.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_debt'));?>'" /></td>
                        <td width="1%"><input type="button" value="[[.list_title_free.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_free'));?>'" /></td>
                        <td width="1%"><input type="button" value="[[.list_title_cancel.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_cancel'));?>'" /></td>
                        <td width="1%" align="right">&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td align="right"><strong>[[.currency.]]: <?php echo HOTEL_CURRENCY;?>&nbsp;&nbsp;</strong></td>
                    </tr>
                </table>
				</p>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th nowrap align="center">
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.time'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]/[[.time_in.]]</a></th>
						
						<th align="center" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.code'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]] </a>						</th>
                        <th align="center" nowrap="nowrap"> [[.mice.]]</th>
						<th align="center" nowrap="nowrap"> 
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.source_customer.]] / [[.guest_name.]]</a>							</th>
						<th align="center" nowrap="nowrap">[[.room_name.]]</th>
						<th align="center" nowrap="nowrap">[[.table_number.]]</th>
						<th align="center" nowrap="nowrap">[[.total.]]</th>
						<th align="center" nowrap="nowrap">[[.time_length.]]</th>
						<th align="center" nowrap>
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.status' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.status'));?>">
							<?php if(URL::get('order_by')=='bar_reservation.status') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.status.]]</a>						</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?><th width="1%" nowrap="nowrap">&nbsp;</th>
						<th style="text-align: center;">[[.user.]]</th>
						<th style="text-align: center;">[[.edit.]]</th><?php }?>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?><th width="1%">[[.delete.]]</th>
						<?php }?></tr>
					<!--LIST:items-->
                    <?php
						if([[=items.status=]]=='CHECKIN')
						{
							$bg_color = '#FFFF99';
						}
						else if([[=items.status=]]=='CHECKOUT')
						{
							$bg_color = '#E2F1DF';
						}
						else
						{
							$bg_color = '#FFFFFF';
						}						
						?>
                    
                    <?php //Xem hoa don echo URL::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print'),'method'=>'print_direct','id'=>[[=items.id=]]));?>
					<tr style="height: 24px;" bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build('touch_bar_restaurant').'&cmd=edit';?>&id=[[|items.id|]]&bar_id=[[|items.bar_id|]]';}else{just_click=false;}" style="cursor:hand;<?php if([[=items.status=]]=='CHECKIN'){?>font-weight:bold;<?php }?>">
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td nowrap align="left">[[|items.arrival_date|]]</td>
							<td align="left" nowrap="nowrap">[[|items.code|]]</td>
                            <td align="left" nowrap="nowrap"><?php if([[=items.mice_reservation_id=]]!=''){ ?>MICE+[[|items.mice_reservation_id|]]<?php } ?></td>
							<td align="left" nowrap="nowrap">[[|items.name|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.room_name|]]</td>
							<td align="center" nowrap="nowrap">[[|items.table_name|]]</td>
							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
							<td align="center" nowrap="nowrap"> [[|items.time_length|]] </td>
							<td align="left" nowrap>[[|items.status|]]</td>
							<td nowrap><?php if(User::can_delete(false,ANY_CATEGORY) and ([[=items.status=]]!='CHECKOUT' and [[=items.status=]]!='CANCEL')) {?><a href="<?php echo Url::build_current(array('act'=>'cancel','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]])); ?>" style="color:#FF0000;text-decoration:underline;">[[.cancel_this_order.]]</a><?php }else{ echo Portal::language('can_not_cancel');} ?></td>
							<td align="center" nowrap="nowrap">[[|items.user_id|]]</td>
							<?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td style="text-align: center;" nowrap><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]],'table_id'=>[[=items.table_id=]],'bar_area_id'=>[[=items.bar_area_id=]],'package_id'=>[[=items.package_id=]])); ?>"><i class="fa fa-edit" style="font-size: 16px;"></i></a></td> <?php } ?>
							<?php if(User::can_admin(false,ANY_CATEGORY)) {?><td style="text-align: center;" nowrap><a href="<?php echo Url::build_current(array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 16px;"></i></a></td>
							<?php } ?></tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
                <p>
                <table style="display: none;"><tr>
                    <td width="1%"><input type="button" value="[[.list_title_debt.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_debt'));?>'" /></td>
                    <td width="1%"><input type="button" value="[[.list_title_free.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_free'));?>'" /></td>
                    <td width="1%"><input type="button" value="[[.list_title_cancel.]]" class="button-medium" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'list_cancel'));?>'" /></td>
                </tr></table>
                </p>
			<input name="cmd" type="hidden" value="">
            </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
    function changevalue()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_arrival_time").val(jQuery("#from_arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_arrival_time").val(jQuery("#to_arrival_time").val());
        }
    }
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
</script>
<script>
    function check_validate_time(){
        from_arrival_time = jQuery('#from_arrival_time').val();
        to_arrival_time = jQuery('#to_arrival_time').val();
        if(from_arrival_time>to_arrival_time){
            alert("To Date must be greater From Date");
            jQuery('#to_arrival_time').css('border','1px solid red');
            jQuery('#to_arrival_time').val(from_arrival_time);
        }
    }
</script>