<script type="text/javascript" src="packages/core/includes/js/picker.js"></script>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="reservation_type-bound">
<form name="EditGuestLevelForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.name.]](*):</td>
					<td><input name="name" type="text" id="name"/></td>
				</tr>
                <tr>
					<td class="label">[[.group.]](*):</td>
					<td><select name="group_name" id="group_name"></select></td>
				</tr>
                <tr>
					<td class="label">[[.online.]](*):</td>
					<td><select name="is_online" id="is_online"></select></td>
				</tr>
				<tr>
					<td class="label">[[.position.]]:</td>
					<td><input name="position" type="text" id="position"/></td>
				</tr>                
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>