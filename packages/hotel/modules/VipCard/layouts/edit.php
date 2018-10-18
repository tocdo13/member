<?php System::set_page_title(HOTEL_NAME);?>
<div class="VipCard_type-bound">
<form name="EditVipCardForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="55%" class="form-title">[[|title|]]</td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br>
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.VipCard_type.]](*):</td>
					<td><select name="card_type_id" id="card_type_id" onchange="EditVipCardForm.submit();"></select></td>
				</tr>
                <tr>
                  <td class="label">[[.code.]](*):</td>
                  <td><input name="code" type="text" id="code"></td>
                </tr>
                <tr>
					<td class="label">[[.card_holder.]](*):</td>
					<td><input name="card_holder" type="text" id="card_holder"></td>
				</tr>
				<tr>
                  <td class="label">[[.discount_percent.]]:</td>
				  <td><input name="discount_percent" type="text" id="discount_percent"> %</td>
			  </tr>
				<tr>
					<td class="label">[[.discount_amount.]]:</td>
					<td><input name="discount_amount" type="text" id="discount_amount"> <?php echo HOTEL_CURRENCY;?></td>
				</tr>
				<tr>
                  <td class="label">[[.join_date.]]:</td>
				  <td><input name="join_date" type="text" id="join_date"></td>
			  </tr>
				<tr>
                  <td class="label">[[.note.]]:</td>
				  <td><input name="note" type="text" id="note"></td>
			  </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script>
	jQuery("#join_date").datepicker();
</script>