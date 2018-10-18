<!--IF:cond(isset([[=product_id=]]))-->
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        jQuery('#date').datepicker();    
    });
    
	function check(obj)
	{
		if(obj.value > [[|quantity|]])
		{
			alert('[[.damaged_equipment_not_greater_than_quantity_avaiable.]]');
			obj.value = '';
		}
	}
</script>
<style>
	.input_style
	{
		width:100%;
		border:0;
		background-color:#EFEFEE;
		font-weight:bold;	
		border-bottom:1px dashed; 	
	}
</style>
<?php System::set_page_title(HOTEL_NAME);?><table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td class="" width="100%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-bullhorn w3-text-orange" style="font-size: 26px;"></i> [[.damaged_title.]]</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	<td bgcolor="#EEEEEE">
	<table width="100%">
	<?php if(Form::$current->is_error()) { echo Form::$current->error_messages();}?>
	<form name="HousekeepingEquipmentDamagedForm" method="post" >
	<input name="sselect" type="hidden" value="0" />
	<tr>
    <td>
		<table>
		<tr bgcolor="#EEEEEE">
			<td nowrap><strong>[[.date.]]</strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
			<td >
				<input name="date" type="text" id="date" style="width:70px" value="[[|current_date|]]"/>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td nowrap><strong>[[.room_id.]]</strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
			<td>[[|room_name|]]</td>
		</tr>
		</table>
        <table>
            <tr>
                <td><span class="multi-input-header"><span style="width:80px;"><strong>[[.code.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:500px;"><strong>[[.name.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:80px;"><strong>[[.unit_name.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:80px;"><strong>[[.remain_quantity.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:122px;"><strong>[[.quantity_damaged.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:150px;"><strong>[[.damaged_type.]]</strong></span></span></td>
            </tr>
            <tr>
                <td><span><span style="width:80px;">[[|product_id|]]</span></span></td>
                <td><span><span style="width:500px;">[[|name|]]</span></span></td>
                <td><span><span style="width:80px;">[[|unit_name|]]</span></span></td>
                <td><span><span style="width:80px;">[[|quantity|]]</span></span></td>
                <td><span><span style="width:119px;"><input name="quantity" type="text" id="quantity" size="15" onkeyup="check(this);" class="input_number" style="height: 24px;"/></span></span></td>
                <td>
            		<span><span style="width:150px;">
						<select  name="type" id="type" style="height: 24px; width: 150px;">
							<option value="DAMAGED" selected="selected">[[.damaged.]]</option>
							<option value="LOST">[[.lost.]]</option>
						</select>	
					</span></span>
                </td>
            </tr>
            <tr>
                <td colspan="6"><span><strong>[[.reason.]]</strong></span></td>
            </tr>
            <tr>
                <td colspan="6">
				    <span><textarea name="note" id="note" style="width:100%" rows="4"></textarea></span>
                </td>
            </tr>
        </table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td>
			<table>
			<tr><td>
                <input class="w3-btn w3-blue" type="submit" value="[[.Save.]]" style="text-transform: uppercase; maring-right: 5px;" />
                <input class="w3-btn w3-green" type="button" onclick="window.location='<?php echo Url::build_current(); ?>'" value="[[.list.]]" style="text-transform: uppercase;"/>
			</td></tr>
			</table>
		</td>
	</tr>
	</form>
	</table>
	</td></tr>
</table>
<!--ELSE-->
<div>[[.id_dont_exists.]]</div>
<!--/IF:cond-->