<form name="EditSupplierForm" method="post">
    <table cellpadding="2" cellspacing="0" width="100%" >
    <tr>
        <td width="80%" align="left">
            <span><b>[[.add_supplier.]]</b></span>
        </td>
        <td align="right">
            <input name="save" type="submit" value="[[.save.]]" class="button-medium-save" />
            <a href="<?php echo Url::build_current(array('group_id','act'));?>"  class="button-medium-back">[[.back.]]</a>
        </td>
        
    </tr>
    </table>
    <div class="content">
        	<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table cellpadding="2" cellspacing="0" width="100%">
            <tr>
                <td>
                    <fieldset>
                        <legend>[[.info.]]</legend>
                        <table border="0" width="70%">
                            <tr>
                                <td>[[.code.]](*)</td>
                                <td><input name="code" type="text" id="code" /></td>
                                <td>[[.name.]](*)</td>
                                <td><input name="name" type="text" id="name"/></td>
                            </tr>
                            <tr>
                                <td>[[.telephone.]]</td>
                                <td><input name="phone" type="text" id="phone" /></td>
                                <td>[[.fax.]]</td>
                                <td><input name="fax" type="text" id="fax"/></td>
                            </tr>
                            <tr>
                                <td>[[.hoteline.]]</td>
                                <td><input name="mobile" type="text" id="mobile" /></td>
                                <td>[[.email.]]</td>
                                <td><input name="email" type="text" id="email"/></td>
                            </tr>
                            <tr>
                                <td>[[.tax_code.]]</td>
                                <td><input name="tax_code" type="text" id="tax_code" /></td>
                                <td>[[.address.]]</td>
                                <td><input name="address" type="text" id="address"/></td>
                            </tr>
                            <tr>
                                <td>[[.account_number.]]</td>
                                <td><input name="account_number" type="text" id="account_number" /></td>
                                <td>[[.contact_person_name.]]</td>
                                <td><input name="contact_person_name" type="text" id="contact_person_name"/></td>
                            </tr>
                            <tr>
                                <td>[[.contact_person_phone.]]</td>
                                <td><input name="contact_person_phone" type="text" id="contact_person_phone" /></td>
                                <td>[[.contact_person_mobile.]]</td>
                                <td><input name="contact_person_mobile" type="text" id="contact_person_mobile"/></td>
                            </tr>
                        </table>
                    </fieldset>
                    
                </td>
            </tr>
        
        </table>
    </div>

</form>