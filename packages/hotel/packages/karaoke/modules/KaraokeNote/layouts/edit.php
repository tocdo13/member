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
                <tr style="vertical-align: top;">
                    <td class="label">[[.note.]](*):</td>
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
jQuery(document).ready(function(){
<?php if(Url::get('confirm')==1) {?>

jQuery('#confirm').attr('checked','checked');

<?php } ?>
    
})

function change()
{
    if(jQuery('#confirm').attr('checked')==false)
        jQuery('#confirm_user').val('');
}

function change_chkbox()
{
    if( jQuery('#confirm_user').val()!= '' )
        jQuery('#confirm').attr('checked','checked');
    else
        jQuery('#confirm').attr('checked',false);

}  
</script>