<script>
object_type_list = <?php echo String::array2js($this->map['object_type_list']);?>;
</script>
<span style="display:none">
	<span id="mi_forgot_object_sample">
		<div id="input_group_#xxxx#">
			<input  name="mi_forgot_object[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
			<span class="multi-input"><input  name="mi_forgot_object[#xxxx#][name]" style="width:150px;height: 24px;" type="text" id="name_#xxxx#" ></span>
			<span class="multi-input"><input  name="mi_forgot_object[#xxxx#][object_type]" style="width:150px;height: 24px;" type="text" id="object_type_#xxxx#"  onkeyup="update_suggest_box(this,object_type_list);" onkeydown="select_suggest(event,object_type_list)" onblur="$('suggest_box').style.display='none';"></span>
			<span class="multi-input"><input  name="mi_forgot_object[#xxxx#][quantity]" style="width:70px;height: 24px;" type="text" id="quantity_#xxxx#" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46)event.returnValue=false;"></span>
			<span class="multi-input"><input  name="mi_forgot_object[#xxxx#][unit]" style="width:70px;height: 24px;" type="text" id="unit_#xxxx#" ></span>
            <span class="multi-input"><input  name="mi_forgot_object[#xxxx#][object_code]" style="width:70px;height: 24px;" type="text" id="object_code_#xxxx#" ></span>
            <span class="multi-input"><input  name="mi_forgot_object[#xxxx#][reason]" style="width:200px;height: 24px;" type="text" id="reason_#xxxx#" ></span>
            <span class="multi-input"><input  name="mi_forgot_object[#xxxx#][position]" style="width:70px;height: 24px;" type="text" id="position_#xxxx#" ></span>
            <span class="multi-input"><input  name="mi_forgot_object[#xxxx#][guest_name]" style="width:100px;height: 24px;" type="text" id="guest_name_#xxxx#" ></span>
            <span class="multi-input"><input  name="mi_forgot_object[#xxxx#][company_name]" style="width:100px;height: 24px;" type="text" id="company_name_#xxxx#" ></span>
			<span class="multi-input">
				<a href="#" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_forgot_object','#xxxx#','');event.returnValue=false;" style="cursor:hand;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px; padding-top: 2px;"></i></a>
			</span><br clear="all" />
		</div>
	</span> 
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<form name="AddRoomForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC">
	<tr height="40">
		<td width="60%" class="" style="font-size: 18px; text-transform: uppercase; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 24px;"></i> <?php echo Portal::language('add_new_forgot_object');?></td>
		<td style="width: 40%; text-align: right; padding-right: 30px;"><input type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange" style="text-transform: uppercase; margin-right: 5px;"/>
		<input type="button" value="<?php echo Portal::language('List');?>" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current();?>'" style="text-transform: uppercase;"/></td>
	</tr>
</table>
<table cellspacing="0" width="100%">
<tr>
	<td>
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<input type="hidden" name="add_items" value="" id="add_items">	
	<?php 
		if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<fieldset>
		<legend class="" style="text-transform: uppercase;"><?php echo Portal::language('search_options');?></legend>
		<table>
		<tr>
		  <td nowrap="nowrap"><strong><?php echo Portal::language('room_id');?></strong></td>
		  <td >:</td>
		  <td><select  name="room_id" id="room_id"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	
		    </select>
          </td>
		  <td nowrap><strong><?php echo Portal::language('forgot_time');?></strong></td>
		  <td >:</td>
		  <td>
			<select  name="hour">
			<script>
				for (var i=0;i<=23;i++){
					document.write('<option value="'+i+'">'+i+'</option>');
				}
			</script>	
			</select>
			<script>
			$('hour').value='<?php echo $this->map['hour'];?>';
			</script>
			&nbsp;<strong><?php echo Portal::language('hour');?></strong>
			<select  name="minute">
			<script>
				for (i=0;i<=59;i++){
					document.write('<option value="'+i+'">'+i+'</option>');
				}
			</script>	
			</select>
			<script>
			$('minute').value='<?php echo $this->map['minute'];?>';
			</script>
			&nbsp;<strong><?php echo Portal::language('minute');?></strong> &nbsp;&nbsp;
			<strong><?php echo Portal::language('date');?></strong>
			<input name="time" type="text" id="time" size="12" value="<?php echo $this->map['time'];?>">
			</td>
		  <td><strong><?php echo Portal::language('employee_id');?></strong></td>
		  <td >:</td>
		  <td><input  name="employee_name" id="employee_name" style="width:200px" / type ="text" value="<?php echo String::html_normalize(URL::get('employee_name'));?>"></td>
		</tr>
		</table>
		</fieldset><br />
		<table width="100%">
		<tr>
			<td colspan="9">
				<fieldset>
					<span id="mi_forgot_object_all_elems" style="text-transform: uppercase;">
							<span class="multi-input-header" style="width:150px;"><?php echo Portal::language('name');?></span>
							<span class="multi-input-header" style="width:150px;"><?php echo Portal::language('object_type');?></span>
							<span class="multi-input-header" style="width:70px;"><?php echo Portal::language('quantity');?></span>
							<span class="multi-input-header" style="width:70px;"><?php echo Portal::language('unit');?></span>
                            <span class="multi-input-header" style="width:70px;"><?php echo Portal::language('object_code');?></span>
                            <span class="multi-input-header" style="width:200px;"><?php echo Portal::language('reason');?></span>
                            <span class="multi-input-header" style="width:70px;"><?php echo Portal::language('position');?></span>
                            <span class="multi-input-header" style="width:100px;"><?php echo Portal::language('guest_name');?></span>
                            <span class="multi-input-header" style="width:100px;"><?php echo Portal::language('company_name');?></span>
							<span class="multi-input-header" style="width:20px;"><img src="skins/default/images/spacer.gif"/></span><br clear="all">
					</span>
					<input class="w3-btn w3-gray" type="button" value="<?php echo Portal::language('add_item');?>" onclick="mi_add_new_row('mi_forgot_object');" style="margin-top: 5px;"/>
				</fieldset></td>
		</tr>
		</table>
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td>
	</tr>
</table>
<script>
mi_init_rows('mi_forgot_object',<?php if(isset($_REQUEST['mi_forgot_object'])){echo String::array2js($_REQUEST['mi_forgot_object']);}else{echo '[]';}?>); 
</script>
<script>
	function echo_time(name,number)
	{
		document.write('<select  name="'+name+'">');
		for( i=0; i<=number ; i++ )
		{
			document.write('<option value="'+i+'">'+i+'</option>');
		}
		document.write('</select>');
	}
	jQuery('#time').datepicker();
    jQuery('.button-medium-save').click(function(){
        jQuery('.button-medium-save').css('display', 'none');    
    })
</script>
<div id="suggest_box" style="position:absolute; border:1px solid black;background-color:white;display:none;"></div>