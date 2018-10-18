<?php System::set_page_title(HOTEL_NAME);?>
<div class="ExtraServiceAdmin_type-bound">
<form name="EditExtraServiceAdminForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" align="right" style="padding-right: 30px;"><input name="save" type="submit" id="save" value="[[.Save_and_close.]]" onclick="if(checkcode()==true && to_number(jQuery('#price').val())>=0) { return true;}else {alert('[[.price_invalid.]]');return false;}" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br/>
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td class="label">[[.code.]](*):</td>
                  <td><input name="code" type="text" id="code" /> <span class="note">[[.code_should_not_change_because_it_can_be_used_in_other.]]</span></td>
                </tr>
                <tr>
                  <td class="label">[[.name.]](*):</td>
                  <td><input name="name" type="text" id="name"/></td>
                </tr>
                <tr>
                  <td class="label">[[.price.]]</td>
                  <td><input name="price" type="text" id="price"  oninput="change_priceFc();" /></td>
              </tr>
                <tr>
                  <td class="label">[[.unit.]]</td>
                  <td><input name="unit" type="text" id="unit" /></td>
                </tr>
                <tr>
                  <td class="label">[[.status.]]</td>
                  <td>
                      <select name="status" id="status"></select>
                  </td>
                </tr>
                <tr>
                <?php if((Url::get('cmd')=='edit' && [[=can_edit=]]==0) or (Url::get('cmd')=='add')){?>
                  <td class="label">[[.type.]]</td>
                  <td>
                      <select name="type" id="type"></select>
                  </td>
                  <?php }else{?>
                  <td class="label" style="display: none;">[[.type.]]</td>
                  <td style="display: none;">
                      <select name="type" id="type"></select>
                  </td>
                  <?php }?>
                </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<?php if(Url::get('cmd')=='edit' and (Url::get('code')!='EXTRA_BED' or Url::get('code')!='BABY_CODE')){?>
<script type="text/javascript">
jQuery('#code').attr('readonly','readonly').css('background-color','#F1F1F1');
</script>
<?php }?>
<script>
    function change_priceFc()
    {
        jQuery('#price').ForceNumericOnly().FormatNumber();
    }
    function checkcode(){
       if(jQuery('#code').val().trim()==''){
          alert('[[.check_code.]]'); return false;
       }else{
          return true;
       }
    }
</script>
