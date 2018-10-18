<?php System::set_page_title(HOTEL_NAME.' - '.[[=title=]]);?>
<div class="room_level-bound">
<form name="EditNoteForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="label">[[.create_date.]]</td>
                    <td>
                        <input name="create_date" type="text" id="create_date"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">[[.customer.]]</td>
                    <td>
                        <select name="customer_id" id="customer_id"></select>
                    </td>
                </tr>
                <tr>
                    <td class="label">[[.payment.]] <strong><em><span style="color: red;">(*)</span></em></strong>:</td>
                    <td>
                        <input name="total" type="text" id="total" class="input_number format_number" style="width: 113px;"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">[[.currency.]]</td>
                    <td>
                        <select name="currency_id" id="currency_id"></select>
                    </td>
                </tr>
                <tr style="vertical-align: top;">
                    <td class="label">[[.note.]]:</td>
                    <td>
                        <textarea name="note" id="note" style="width: 300px; height: 100px;" ></textarea>
                    </td>
                </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script>
	jQuery('#create_date').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
</script>