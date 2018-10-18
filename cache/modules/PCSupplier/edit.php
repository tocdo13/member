<?php System::set_page_title(HOTEL_NAME);?>


<div class="customer_type-bound">
<form name="EditSupplierForm" method="post">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
	
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
    			<a href="<?php echo Url::build_current(array('group_id','act'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('back');?></a>
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
                    <legend class="title"><?php echo Portal::language('info');?></legend>
                	<table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="label" ><?php echo Portal::language('code');?>(*):</td>
                            <td ><input  name="code" id="code" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>"></td>
                            <td class="label"><?php echo Portal::language('name');?>(*):</td>
                            <td colspan="3" style="width: 500px;"><!--<input  name="name" id="name" style="width: 200px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">-->
                                <textarea  name="name" id="name" style="width: 100%; height: 24px;" ><?php echo String::html_normalize(URL::get('name',''));?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo Portal::language('tax_code');?>:</td>
                            <td><input  name="tax_code" id="tax_code" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('tax_code'));?>"></td>
                            <td class="label"><?php echo Portal::language('address');?>:</td>
                            <td colspan="3" style="width: 500px;"><textarea  name="address" id="address" style="width: 100%; height: 24px;" ><?php echo String::html_normalize(URL::get('address',''));?></textarea></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo Portal::language('telephone_number');?>:</td>
                            <td><input  name="phone" id="phone" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>"></td>
                            <td class="label"><?php echo Portal::language('fax');?>:</td>
                            <td><input  name="fax" id="fax" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('fax'));?>"></td>
                            <td class="label"><?php echo Portal::language('email');?>:</td>
                            <td><input  name="email" id="email" style="width: 250px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
                        </tr>                        
                        <!---<tr>
                            <td class="label"><?php echo Portal::language('mobile');?>:</td>
                            <td><input  name="mobile" id="mobile" style="width: 200px;" / type ="text" value="<?php echo String::html_normalize(URL::get('mobile'));?>"></td>
                            
                        </tr>--->                                               
                        <tr>
                            
                            <td class="label"><?php echo Portal::language('contact_person_name');?>:</td>
                            <td><input  name="contact_person_name" id="contact_person_name" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('contact_person_name'));?>"></td>
                            <td class="label"><?php echo Portal::language('contact_person_mobile');?>:</td>
                            <td><input  name="contact_person_mobile" id="contact_person_mobile" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('contact_person_mobile'));?>"></td>
                            <td class="label"><?php echo Portal::language('contact_person_email');?>:</td>
                            <td><input  name="contact_person_email" id="contact_person_email" style="width: 250px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('contact_person_email'));?>"></td>
                        </tr>
                        <tr>                            
                            <!---<td class="label"><?php echo Portal::language('contact_person_phone');?>:</td>
                            <td><input  name="contact_person_phone" id="contact_person_phone" style="width: 200px;" / type ="text" value="<?php echo String::html_normalize(URL::get('contact_person_phone'));?>"></td>--->                            
                        </tr>                        
                        <tr>
                            <td class="label"><?php echo Portal::language('account_number');?>:</td>
                            <td><input  name="account_number" id="account_number" style="width: 200px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('account_number'));?>"></td>                            
                        </tr>                                               
                    </table>
                    </fieldset>
                </td>
            </tr>
        </table>
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>