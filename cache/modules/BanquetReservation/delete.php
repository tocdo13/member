<!--?php echo"tet5";?-->
<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?>
<table cellpadding="15" cellspacing="0" width="980">
	<tr valign="top">
		<td align="left" colspan="3" class="form-title"><?php echo Portal::language('delete_bar_reservation');?></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="5" width="980">
	<tr bgcolor="#EEEEEE" valign="top">
	<td align="right">&nbsp;</td>
	<td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td width="100%">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EEEEEE" valign="top">
	<td align="right" nowrap="nowrap">B&#225;o l&#7895;i</td>
	<td bgcolor="#EEEEEE"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	<td bgcolor="#EEEEEE"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><form name="DeleteBanquetOrderForm" method="post">
	<input type="hidden" name="id" value="<?php echo $this->map['id'];?>"><input type="hidden" name="cmd" value="delete">
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong><?php echo Portal::language('code');?></strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%">
			<div class="detail_box"><?php echo $this->map['id'];?></div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong><?php echo Portal::language('status');?></strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%">
			<div class="detail_box"><?php echo $this->map['status'];?></div></td>
	</tr> <tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right"><strong><?php echo Portal::language('note');?></strong></td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">:</span></td>
		<td width="100%">
			<div class="detail_box"><?php echo $this->map['note'];?></div></td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	  <td nowrap align="right">&nbsp;</td>
	  <td bgcolor="#EEEEEE">&nbsp;</td>
	  <td bgcolor="#EEEEEE">
	  	<fieldset>
			<legend><?php echo Portal::language('canceler_info');?></legend>
			<textarea  name="cancel_note" style="width:80%" rows="7"><?php echo String::html_normalize(URL::get('cancel_note',''));?></textarea>
		</fieldset>	  </td>
	  </tr> 
	<tr bgcolor="#EEEEEE" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10" bgcolor="#ECE9D8"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="100%">
			<b><?php echo Portal::language('confirm_question');?>?</b>		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td bgcolor="#EEEEEE" colspan="3" align="center">
			<p>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteBanquetOrderForm');?></td><td>
				<?php Draw::button(Portal::language('list'),URL::build_current(array('bar_reservation_receptionist_id',   
)));?>
	<input type="hidden" name="confirm" value="1" id="confirm" />
			</td></tr>
			</table>
			</p>		</td>
	</tr>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</table>