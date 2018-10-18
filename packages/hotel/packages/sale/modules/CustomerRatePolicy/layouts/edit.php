<?php System::set_page_title(HOTEL_NAME);?>
<span style="display:none">
	<span id="mi_policy_group_sample">
		<div id="input_group_#xxxx#" class="multi-item-wrapper">
			<input  name="mi_policy_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][name]" style="width:100px;" type="text" id="name_#xxxx#"></span>            
			<span class="multi-input"><input  name="mi_policy_group[#xxxx#][start_date]" style="width:100px;" type="text" id="start_date_#xxxx#"></span>
			<span class="multi-input"><input  name="mi_policy_group[#xxxx#][end_date]" style="width:100px;" type="text" id="end_date_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_1_adult]" style="width:70px;" type="text" id="rate_1_adult_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_2_adults]" style="width:70px;" type="text" id="rate_2_adults_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_3_adults]" style="width:70px;" type="text" id="rate_3_adults_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_extra_adults]" style="width:70px;" type="text" id="rate_extra_adults_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_1_child]" style="width:70px;" type="text" id="rate_1_child_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_2_children]" style="width:70px;" type="text" id="rate_2_children_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_3_children]" style="width:70px;" type="text" id="rate_3_children_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate_extra_children]" style="width:75px;" type="text" id="rate_extra_children_#xxxx#"></span>
            <span class="multi-input"><select  name="mi_policy_group[#xxxx#][room_level_id]" id="room_level_id_#xxxx#" style="width:95px;" >[[|room_level_options|]]</select></span>
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="if(!confirm('[[.are_you_sure.]]')){return false};mi_delete_row($('input_group_#xxxx#'),'mi_policy_group','#xxxx#','group_');if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/></span></span>
             <br clear="all">
		</div>
	</span> 
</span>
<div>
<form name="EditCustomerRatePolicyForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="80%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('group_id','customer_id'));?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        	<tr>
            	<td><h3>[[.customer.]]: [[|customer_name|]]</h3></td>
            </tr>
            <tr valign="top">
                <td>
                <fieldset>
					<legend class="title">[[.rate_detail.]]</legend>
                     <input  name="group_deleted_ids" type="hidden" id="group_deleted_ids" value="<?php echo URL::get('group_deleted_ids');?>"/>
						<span id="mi_policy_group_all_elems" style="text-align:left;">
							<span>
                                <span class="multi-input-header" style="width:100px;">[[.name.]]</span>
								<span class="multi-input-header" style="width:100px;">[[.start_date.]]</span>
								<span class="multi-input-header" style="width:100px;">[[.end_date.]]</span>
                                <span class="multi-input-header" style="width:70px;">[[.1_adult.]]</span>
								<span class="multi-input-header" style="width:70px;">[[.2_adults.]]</span>
                                <span class="multi-input-header" style="width:70px;">[[.3_adults.]]</span>
                                <span class="multi-input-header" style="width:70px;">[[.extra_adults.]]</span>
                                <span class="multi-input-header" style="width:70px;">[[.1_child.]]</span>
                                <span class="multi-input-header" style="width:70px;">[[.2_children.]]</span>
                                <span class="multi-input-header" style="width:70px;">[[.3_children.]]</span>
                                <span class="multi-input-header" style="width:75px;">[[.extra_children.]]</span>
                                <span class="multi-input-header" style="width:90px;">[[.room_level.]]</span>
							</span>
							<br clear="all">
						</span>
						<div><input type="button" value="[[.add_policy.]]" onclick="mi_add_new_row('mi_policy_group');jQuery('#start_date_'+input_count).datepicker({ minDate: new Date(2010, 1 - 1, 1) });jQuery('#end_date_'+input_count).datepicker({ minDate: new Date(2010, 1 - 1, 1) });" class="button-medium-add"></div>
				</fieldset>
                </td>
            </tr>
        </table>
	</div>
</form>	
</div>
<script>
mi_init_rows('mi_policy_group',<?php echo isset($_REQUEST['mi_policy_group'])?String::array2js($_REQUEST['mi_policy_group']):'{}';?>);
for(var index=101;index<=input_count;index++){
	if(jQuery("#start_date"+index)){
		jQuery("#start_date"+index).datepicker({ minDate: new Date(2010, 1 - 1, 1) });
	}
	if(jQuery("#end_date_"+index)){
		jQuery("#end_date_"+index).datepicker({ minDate: new Date(2010, 1 - 1, 1) });
	}
}
</script>