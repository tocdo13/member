<script type="text/javascript" src="packages/core/includes/js/picker.js"></script>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="vip-card-type-bound">
<form name="EditVipCardTypeForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="55%" class="form-title">[[|title|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="body">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.name.]](*):</td>
					<td><input name="name" type="text" id="name"></td>
				</tr>
				<tr>
					<td class="label">[[.discount_percent.]]:</td>
					<td><input name="discount_percent" type="text" id="discount_percent"></td>
				</tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>