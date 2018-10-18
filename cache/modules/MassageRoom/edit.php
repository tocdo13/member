<?php System::set_page_title(HOTEL_NAME);?>
<div class="room_level-bound">
<form name="EditMassageRoomForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%" align="right" style="padding-right: 30px;"><input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-gray" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('cancel');?></a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="label"><?php echo Portal::language('room_level_name');?>(*):</td>
                  <td><input  name="category" id="category" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('category'));?>"></td>
                </tr>
                <tr>
					<td class="label"><?php echo Portal::language('room_name');?>(*):</td>
					<td><input  name="name" id="name"  style="height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>"></td>
				</tr>
				<tr>
					<td class="label"><?php echo Portal::language('position');?>:</td>
					<td><input  name="POSITION" id="POSITION"  style="height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('POSITION'));?>"></td>
				</tr>
                <tr>
                    <td class="label"><?php echo Portal::language('area');?>:</td>
                    <td> <select  name="area_id" id="area_id" class="multi-edit-text-input"  style="height: 24px;"><?php
					if(isset($this->map['area_id_list']))
					{
						foreach($this->map['area_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('area_id',isset($this->map['area_id'])?$this->map['area_id']:''))
                    echo "<script>$('area_id').value = \"".addslashes(URL::get('area_id',isset($this->map['area_id'])?$this->map['area_id']:''))."\";</script>";
                    ?>
	<?php echo $this->map['area'];?></select></td>
                </tr>
			</table>
	  </fieldset>	
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>