<script>
	TelephoneNumber_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table width="100%" cellpadding="0" cellspacing="0" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr height="40">
					<td width="90%" class="form-title">[[.list_title_telephone_number.]]</td>
					<td width="1%"><a href="javascript:void(0)" onclick="EditTelephoneNumberForm.submit();" class="button-medium-save">[[.save.]]</a></td>
					<td width="1%"><a href="javascript:void(0)" onclick="window.history.go(-1)" class="button-medium-back">[[.back.]]</a></td>
				</tr>
			</table>
		</td>
	</tr><form name="TelephoneNumberListForm" method="post">
	<tr valign="top">
		<td width="100%">
		<table cellspacing="0" width="100%">
		<tr valign=top>
		<td width="50%">
		<div id="_list_region" style="overflow:auto;height:400px;width:500px">
			<table cellspacing="0" width="100%">
			<tr>
				<td><div style="width:10px;">&nbsp;</div></td>
				<td width="100%" bgcolor="#EFEEEE">
					<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" border="1">
						<tr valign="middle">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="TelephoneNumber_check_0" onclick="check_all('TelephoneNumber','TelephoneNumber_array_items','#FFFFEC',this.checked);"></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_number.number' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_number.number'));?>" style="color:#000000;font-weight:700" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_number.number') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.phone_number.]]
								</a>
							</th><th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" style="color:#000000;font-weight:700" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room_id.]]
								</a>
							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?><th bgcolor="#EFEEEE">&nbsp;</th>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th bgcolor="#EFEEEE">&nbsp;</th>
							<?php
							}
							?></tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" <?php Draw::hover('#EFEEEE');?>style="cursor:pointer;" id="TelephoneNumber_tr_[[|items.id|]]">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('TelephoneNumber','[[|items.i|]]','TelephoneNumber_array_items','#FFFFEC');" id="TelephoneNumber_check_[[|items.i|]]"></td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">[[|items.phone_number|]]</td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">[[|items.room_id|]]</td>
							<?php 
							if(User::can_edit(false,ANY_CATEGORY))
							{
							?><td nowrap bgcolor="#EFEEEE" width="15px"><a href="<?php echo Url::build_current(array()+array('edit_selected'=>true,'selected_ids'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><td nowrap bgcolor="#EFEEEE" width="15px"><a href="<?php echo Url::build_current(array()+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a> 
							</td>
							<?php
							}
							?></tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
			<table width="100%"><tr>
				<?php				
				if(User::can_delete(false,ANY_CATEGORY))
				{
					echo '<td align="left"  width="100px">';
					?>
					<input type="submit" title="&quot;" name="delete_selected" onClick="this.disable=true;" style="width:80px"  value="[[.delete.]]">
					<?php 
					echo '</td>';
				}
				?></td>
			</form>
			</tr></table>
		</div>
		<div>[[|paging|]]</div>
		</td>
