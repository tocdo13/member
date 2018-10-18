<script>
	TelephoneFee_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?>
<table cellspacing="0">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td width="90%" class="form-title">
						[[.list_title_telephone_fee.]]
					</td>
					<td class="form-title-button">
						<a href="javascript:void(0)" onClick="EditTelephoneFeeForm.submit();"><img src="<?php echo Portal::template('core');?>/images/buttons/save_button.gif" style="text-align:center"/><br />[[.save.]]</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr valign="top">
		<td>
		<table cellspacing="0" width="100%">
		<tr valign=top>
		<td width="50%">
		<div id="_list_region" style="overflow:auto;height:500px;width:500px;">
				<form name="TelephoneFeeListForm" method="post">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr valign="middle" class="table-header"  style="line-height:20px">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="TelephoneFee_check_0" onClick="check_all('TelephoneFee','TelephoneFee_array_items','#FFFFEC',this.checked);"></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_fee.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_fee.name'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_fee.name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.country_name.]]
								</a>
							</th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_fee.prefix' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_fee.prefix'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_fee.prefix') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.prefix.]]
								</a>
							</th><th nowrap align="right" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_fee.start_fee' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_fee.start_fee'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_fee.start_fee') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.start_fee.]]
								</a>
							</th>
							<th nowrap align="right" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_fee.fee' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_fee.fee'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_fee.fee') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.block_fee.]]
								</a>
							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><th>&nbsp;</th>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th>&nbsp;</th>
							<?php
							}
							?></tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" style="cursor:hand;" id="TelephoneFee_tr_[[|items.id|]]">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onClick="tr_color = clickage('TelephoneFee','[[|items.i|]]','TelephoneFee_array_items','#FFFFEC');" id="TelephoneFee_check_[[|items.i|]]"></td>
							<td nowrap align="left" onClick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">
									[[|items.name|]]
								</td><td nowrap align="left" onClick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">
									[[|items.prefix|]]
								</td><td nowrap align="right" onClick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">
									[[|items.start_fee|]]
								</td><td nowrap align="right" onClick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">
									[[|items.fee|]]
								</td>
							<?php 
							if(User::can_edit(false,ANY_CATEGORY))
							{
							?><td nowrap width="15px">
								&nbsp;<a href="<?php echo Url::build_current(array()+array('edit_selected'=>true,'selected_ids'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><td nowrap width="15px">
								&nbsp;<a href="<?php echo Url::build_current(array()+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a> 
							</td>
							<?php
							}
							?></tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			[[|paging|]]
			<table width="100%"><tr><td>
				<?php
				if(User::can_delete(false,ANY_CATEGORY))
				{?>
					<input type="submit" title="&quot;" name="delete_selected" onClick="this.disable=true;" style="width:80px"  value="[[.delete.]]">
				<?php }
				?>
			</form>
			</tr></table>
		</div>
		</td>
