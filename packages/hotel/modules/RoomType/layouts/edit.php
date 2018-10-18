<script type="text/javascript" src="packages/core/includes/js/picker.js"></script>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="room_type-bound">
<form name="EditRoomTypeForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%" style="text-align: right; padding-right: 30px;"><input type="submit" value="[[.Save.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-lime" style="text-transform: uppercase; text-decoration: none;">[[.cancel.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.kind_of_room.]](*):</td>
					<td><input name="name" type="text" id="name"></td>
				</tr>
				<tr>
					<td class="label">[[.brief_name.]]:</td>
					<td><input name="brief_name" type="text" id="brief_name"></td>
				</tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>