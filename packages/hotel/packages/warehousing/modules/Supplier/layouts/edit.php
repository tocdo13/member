<?php System::set_page_title(HOTEL_NAME);?>


<div class="customer_type-bound">
<form name="EditSupplierForm" method="post">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange" style="text-transform: uppercase; margin-right: 5px;"/>
    			<a href="<?php echo Url::build_current(array('group_id','act'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a>
            </td>
        </tr>
    </table>
    
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
            	<td>
                	<fieldset>
                    <legend class="title">[[.info.]]</legend>
                	<table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="label">[[.code.]](*):</td>
                            <td><input name="code" type="text" id="code"/></td>
                            <td class="label">[[.name.]](*):</td>
                            <td colspan="3" style="width: 500px;"><input name="name" type="text" id="name" style="width: 100%;"/></td>
                        </tr>
                        <tr>
                            <td class="label">[[.tax_code.]]:</td>
                            <td><input name="tax_code" type="text" id="tax_code" /></td>
                            <td class="label">[[.address.]]:</td>
                            <td colspan="3" style="width: 500px;"><textarea name="address" id="address" style="width: 100%;"></textarea></td>
                        </tr>
                        <tr>
                            <td class="label">[[.telephone_number.]]:</td>
                            <td><input name="phone" type="text" id="phone"/></td>
                            <td class="label">[[.fax.]]:</td>
                            <td><input name="fax" type="text" id="fax" /></td>
                            <td class="label">[[.email.]]:</td>
                            <td style="width: 200px;"><input name="email" type="text" id="email" style="width: 100%;" /></td>
                        </tr>                        
                        <!---<tr>
                            <td class="label">[[.mobile.]]:</td>
                            <td><input name="mobile" type="text" id="mobile" /></td>
                            
                        </tr>--->                      
                        <tr>
                            <td class="label">[[.contact_person_name.]]:</td>
                            <td><input name="contact_person_name" type="text" id="contact_person_name" /></td>
                            <td class="label">[[.contact_person_mobile.]]:</td>
                            <td><input name="contact_person_mobile" type="text" id="contact_person_mobile" /></td>
                            <td class="label">[[.contact_person_email.]]:</td>
                            <td style="width: 200px;"><input name="contact_person_email" type="text" id="contact_person_email" style="width: 100%;"/></td>
                            <!---<td class="label">[[.contact_person_phone.]]:</td>
                            <td><input name="contact_person_phone" type="text" id="contact_person_phone" /></td>--->
                        </tr>
                    </table>
                    </fieldset>
                </td>
            </tr>
        </table>
	</div>
</form>	
</div>