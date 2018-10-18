<?php 
				if((isset($this->map['product_id'])))
				{?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        jQuery('#date').datepicker();    
    });
    
	function check(obj)
	{
		if(obj.value > <?php echo $this->map['quantity'];?>)
		{
			alert('<?php echo Portal::language('damaged_equipment_not_greater_than_quantity_avaiable');?>');
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
					<td class="" width="100%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-bullhorn w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('damaged_title');?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	<td bgcolor="#EEEEEE">
	<table width="100%">
	<?php if(Form::$current->is_error()) { echo Form::$current->error_messages();}?>
	<form name="HousekeepingEquipmentDamagedForm" method="post" >
	<input  name="sselect" value="0" / type ="hidden" value="<?php echo String::html_normalize(URL::get('sselect'));?>">
	<tr>
    <td>
		<table>
		<tr bgcolor="#EEEEEE">
			<td nowrap><strong><?php echo Portal::language('date');?></strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
			<td >
				<input name="date" type="text" id="date" style="width:70px" value="<?php echo $this->map['current_date'];?>"/>
			</td>
		</tr>
		<tr bgcolor="#EEEEEE">
			<td nowrap><strong><?php echo Portal::language('room_id');?></strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
			<td><?php echo $this->map['room_name'];?></td>
		</tr>
		</table>
        <table>
            <tr>
                <td><span class="multi-input-header"><span style="width:80px;"><strong><?php echo Portal::language('code');?></strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:500px;"><strong><?php echo Portal::language('name');?></strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:80px;"><strong><?php echo Portal::language('unit_name');?></strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:80px;"><strong><?php echo Portal::language('remain_quantity');?></strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:122px;"><strong><?php echo Portal::language('quantity_damaged');?></strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:150px;"><strong><?php echo Portal::language('damaged_type');?></strong></span></span></td>
            </tr>
            <tr>
                <td><span><span style="width:80px;"><?php echo $this->map['product_id'];?></span></span></td>
                <td><span><span style="width:500px;"><?php echo $this->map['name'];?></span></span></td>
                <td><span><span style="width:80px;"><?php echo $this->map['unit_name'];?></span></span></td>
                <td><span><span style="width:80px;"><?php echo $this->map['quantity'];?></span></span></td>
                <td><span><span style="width:119px;"><input  name="quantity" id="quantity" size="15" onkeyup="check(this);" class="input_number" style="height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('quantity'));?>"></span></span></td>
                <td>
            		<span><span style="width:150px;">
						<select  name="type" id="type" style="height: 24px; width: 150px;">
							<option value="DAMAGED" selected="selected"><?php echo Portal::language('damaged');?></option>
							<option value="LOST"><?php echo Portal::language('lost');?></option>
						</select>	
					</span></span>
                </td>
            </tr>
            <tr>
                <td colspan="6"><span><strong><?php echo Portal::language('reason');?></strong></span></td>
            </tr>
            <tr>
                <td colspan="6">
				    <span><textarea  name="note" id="note" style="width:100%" rows="4"><?php echo String::html_normalize(URL::get('note',''));?></textarea></span>
                </td>
            </tr>
        </table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td>
			<table>
			<tr><td>
                <input class="w3-btn w3-blue" type="submit" value="<?php echo Portal::language('Save');?>" style="text-transform: uppercase; maring-right: 5px;" />
                <input class="w3-btn w3-green" type="button" onclick="window.location='<?php echo Url::build_current(); ?>'" value="<?php echo Portal::language('list');?>" style="text-transform: uppercase;"/>
			</td></tr>
			</table>
		</td>
	</tr>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</table>
	</td></tr>
</table>
 <?php }else{ ?>
<div><?php echo Portal::language('id_dont_exists');?></div>

				<?php
				}
				?>