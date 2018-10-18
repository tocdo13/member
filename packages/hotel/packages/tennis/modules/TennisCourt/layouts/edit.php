<?php System::set_page_title(HOTEL_NAME);?>
<div class="court_type-bound">
<form name="EditTennisCourtForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="label">[[.category.]](*):</td>
                  <td><input name="category" type="text" id="category" /></td>
                </tr>
                <tr>
					<td class="label">[[.name.]](*):</td>
					<td><input name="name" type="text" id="name"></td>
				</tr>
				<tr>
					<td class="label">[[.position.]]:</td>
					<td><input name="POSITION" type="text" id="POSITION"></td>
				</tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>