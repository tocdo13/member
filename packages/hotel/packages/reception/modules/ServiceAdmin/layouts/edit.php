<?php System::set_page_title(HOTEL_NAME);?>
<div class="ServiceAdmin_type-bound">
<form name="EditServiceAdminForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br>
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td class="label">[[.name.]](*):</td>
                  <td><input name="name" type="text" id="name"></td>
                </tr>
                <tr>
                  <td class="label">[[.pay_with.]]:</td>
                  <td><!--IF:cond(User::can_admin(false,ANY_CATEGORY) or Url::get('cmd')=='add')--><select name="type" id="type"></select><!--ELSE--><input name="type" type="text" id="type" readonly="" class="readonly"><!--/IF:cond--></td>
                </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>