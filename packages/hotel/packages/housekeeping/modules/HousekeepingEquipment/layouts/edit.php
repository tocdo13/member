<style>
	.input_style
	{
		width:100%;
		border:0;
		background-color:#EFEFEE;
		font-weight:bold;	
		border-bottom:1 dashed; 	
	}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.edit_title.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#add">
						<img src="skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	<td bgcolor="#EEEEEE">
	<table width="100%">
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
<form name="EditHousekeepingEquipmentForm" method="post" >
	<input name="sselect" type="hidden" value="0" />
	<tr><td>
		<table>
		<tr bgcolor="#EEEEEE">
			<td nowrap><strong>[[.room_id.]]</strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
			<td >
				<select name="room_id" id="room_id" style="width:100px;"></select>
			</td>
		</tr>
		</table>
				<div>
					<span>
						<span class="multi-input-header"><span style="width:100;">[[.code.]]</span></span>
						<span class="multi-input-header"><span style="width:200;">[[.name.]]</span></span>
						<span class="multi-input-header"><span style="width:100;">[[.unit_name.]]</span></span>
						<span class="multi-input-header"><span style="width:100;">[[.quantity.]]</span></span>
						<br/>
					</span>
				</div>
				<div>
					<span>
						<span class="multi-input-header"><span style="width:100;">[[|product_id|]]</span></span>
						<span class="multi-input-header"><span style="width:200;">[[|name|]]</span></span>
						<span class="multi-input-header"><span style="width:100;">[[|unit_name|]]</span></span>
						<span class="multi-input-header"><span style="width:100;"><input name="quantity" type="text" id="quantity" value="[[|quantity|]]" size="10"></span></span>
						<br/>
					</span>
				</div>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td>
			<table>
			<tr><td>
				<?php Draw::button(Portal::language('save'),false,false,true,'EditHousekeepingEquipmentForm');?></td><td>
				<p><?php Draw::button(Portal::language('list'),URL::build_current(array('housekeeping_equipment_old_store_id')));?></p>
			</td></tr>
			</table>
		</td>
	</tr>
	</form>
	</table>
	</td></tr>
</table>