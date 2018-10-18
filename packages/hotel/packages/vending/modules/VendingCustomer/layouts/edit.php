<?php System::set_page_title(HOTEL_NAME);?>
<span style="display:none">
	<span id="mi_contact_group_sample">
		<div id="input_group_#xxxx#" style="text-align:left;">
			<input  name="mi_contact_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input"><input  name="mi_contact_group[#xxxx#][contact_name]" style="width:150px;" type="text" id="contact_name_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="mi_contact_group[#xxxx#][contact_phone]" style="width:120px;" type="text" id="contact_phone_#xxxx#"></span>
            <span class="multi-input"><input  name="mi_contact_group[#xxxx#][contact_mobile]" style="width:120px;" type="text" id="contact_mobile_#xxxx#"></span>
			<span class="multi-input"><input  name="mi_contact_group[#xxxx#][contact_email]" style="width:150px;" type="text" id="contact_email_#xxxx#"></span>
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_contact_group','#xxxx#','group_');" style="cursor:pointer;"/></span></span>
             <br clear="all">
		</div>
	</span> 
</span>
<div class="customer_type-bound">
<form name="EditCustomerForm" method="post">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="80%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('group_id','act'));?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
            	<td>
                	<fieldset>
                    <legend class="title">[[.general_info.]]</legend>
                	<table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="label">[[.customer_group.]]:</td>
                            <td><select name="group_id" id="group_id"></select></td>
                        </tr>
                        <tr>
                            <td class="label">[[.code.]](*):</td>
                            <td><input name="code" type="text" id="code" AUTOCOMPLETE=OFF></td>
                        </tr>
                        <tr>
                          <td class="label">[[.name.]](*):</td>
                          <td><input name="name" type="text" id="name" AUTOCOMPLETE=OFF></td>
                      </tr>
                        <tr>
                          <td class="label">[[.phone.]]:</td>
                          <td><input name="PHONE" type="text" id="PHONE" /></td>
                      </tr>
                        <tr>
                          <td class="label">[[.fax.]]:</td>
                          <td><input name="fax" type="text" id="fax" /></td>
                      </tr>
                        <tr>
                          <td class="label">[[.mobile.]]:</td>
                          <td><input name="MOBILE" type="text" id="MOBILE" /></td>
                      </tr>
                        <tr>
                          <td class="label">[[.email.]]:</td>
                          <td><input name="EMAIL" type="text" id="EMAIL" /></td>
                      </tr>
                        <tr>
                          <td class="label">[[.tax_code.]]:</td>
                          <td><input name="tax_code" type="text" id="tax_code" /></td>
                        </tr>
                        <tr>
                          <td class="label">[[.address.]]:</td>
                          <td><textarea name="ADDRESS" id="ADDRESS"></textarea></td>
                      </tr>
                        <tr>
                          <td colspan="2" class="label">&nbsp;</td>
                      </tr>
						<tr>
                       	<td colspan="2">
						<fieldset>
							<legend class="title">[[.contact_person_info.]]</legend>                            
                            <span id="mi_contact_group_all_elems" style="text-align:left;">
							<span>
                                <span class="multi-input-header" style="width:150px;">[[.contact_name.]]</span>
								<span class="multi-input-header" style="width:120px;">[[.contact_phone.]]</span>
								<span class="multi-input-header" style="width:120px;">[[.contact_mobile.]]</span>
                                <span class="multi-input-header" style="width:150px;">[[.contact_email.]]</span>
							</span>                            
                            </span>
							<div><input type="button" value="[[.add_contact.]]" onclick="mi_add_new_row('mi_contact_group');" class="button-medium-add"></div>
                            </fieldset>
                            </td>
                        </tr>
                    </table>
                    </fieldset>
                </td>
            </tr>
        </table>
	</div>
</form>	
</div>
<script>
mi_init_rows('mi_contact_group',<?php echo isset($_REQUEST['mi_contact_group'])?String::array2js($_REQUEST['mi_contact_group']):'{}';?>);
jQuery("#code").autocomplete({
		url: 'r_get_customer.php?code=1'
});
jQuery("#NAME").autocomplete({
		url: 'r_get_customer.php?name=1'
});
</script>