<?php System::set_page_title(HOTEL_NAME.' - '.$this->map['title']);?>
<div class="room_level-bound">
<form name="EditNoteForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="65%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="35%" align="right"><input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;;"/><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green w3-text-white" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('back');?></a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
                <tr style="vertical-align: top;">
                    <td class="label"><?php echo Portal::language('note');?>(*):</td>
                    <td>
                        <textarea  name="note" id="note" style="width: 300px; height: 100px;" ><?php echo String::html_normalize(URL::get('note',''));?></textarea>
                    </td>
                </tr>
                <!--
                <tr>
                    <td class="label"><?php echo Portal::language('confirm_date');?>(*):</td>
                    <td>
                        <input  name="confirm_date" id="confirm_date" / type ="text" value="<?php echo String::html_normalize(URL::get('confirm_date'));?>">
                    </td>
                </tr>
                -->
                <tr>
                    <td class="label"><?php echo Portal::language('confirm');?>:</td>
                    <td>
                        <input  name="confirm" id="confirm" value="1" onclick="change();" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('confirm'));?>">
                    </td>
                </tr>
                <tr>
                    <td class="label"></td>
                    <td>
                        <!--<select  name="confirm_user" id="confirm_user" onchange="change_chkbox()"><?php
					if(isset($this->map['confirm_user_list']))
					{
						foreach($this->map['confirm_user_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('confirm_user',isset($this->map['confirm_user'])?$this->map['confirm_user']:''))
                    echo "<script>$('confirm_user').value = \"".addslashes(URL::get('confirm_user',isset($this->map['confirm_user'])?$this->map['confirm_user']:''))."\";</script>";
                    ?>
	</select>-->
                    </td>
                </tr>
			</table>
	  </fieldset>	
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
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