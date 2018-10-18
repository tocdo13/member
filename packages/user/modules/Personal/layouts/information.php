<script type="text/javascript">
jQuery(function(){
	jQuery('#birth_date').datepicker();
});
</script>
<div class="personal-bound">
	<div class="personal-content" style="width:980px;margin-left:auto;margin-right:auto;">
        <div class="setting-tab">
            <div class="bound selected"><div>
              <div><a href="<?php echo Url::build_current()?>">[[.Account_information.]]</a></div>
            </div></div>
            <div class="bound normal"><div>
              <div><a href="<?php echo Url::build_current(array('cmd'=>'change_pass'))?>">[[.Change_password.]]</a></div>
            </div></div>
        </div>
        <div class="setting-bound">
        <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>	
        <form name="EditUser" method="post" id="EditUser" enctype="multipart/form-data">
            <table cellpadding="4" cellspacing="0" width="100%" align="center">
                    <tr>
                        <td width="32%" align="right">[[.Account_id.]]</td>
                      <td width="68%"><input name="account_id" type="text" id="account_id" class="input-huge" style="width:200px;background-color:#FFFFCC;" readonly="readonly"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.full_name.]]</td>
                      <td width="68%"><input name="full_name" type="text" id="full_name" class="input-huge" style="width:300px;"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.address.]]</td>
                        <td width="68%"><input name="address" type="text" id="address" class="input-huge" style="width:300px;"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.zone.]]</td>
                        <td width="68%"><select name="zone_id" class="select-large" id="zone_id"></select></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.gender.]]</td>
                        <td width="68%"><select name="gender" class="select" id="gender"></select></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.birth_date.]]</td>
                        <td width="68%"><input name="birth_date" type="text" id="birth_date" class="input"  style="width:200px;"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.phone_number.]]</td>
                        <td width="68%"><input name="phone" type="text" id="phone" class="input-huge" style="width:200px;"></td>
                    </tr>
                    <tr>
                        <td width="32%" align="right">[[.email.]]</td>
                        <td width="68%"><input name="email" type="text" id="email" class="input-huge" style="width:200px;"></td>
                    </tr>
            </table>
            <div class="personal-button" align="center"><input name="submit" type="submit" value="[[.Save.]]" />&nbsp;&nbsp;&nbsp;<input name="reset" type="reset" value="[[.Reset.]]" /></div>
          </form>	
        </div>
	</div>
</div>
