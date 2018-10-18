<?php System::set_page_title(HOTEL_NAME);?>
<div class="warehouse-bound">
<form name="EditTourForm" method="post">
	<table cellpadding="15" cellspacing="0" width="60%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="60%" class="" style="text-transform: uppercase; text-decoration: none; padding-left: 15px; font-size: 18px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('back');?></a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
			<table border="0" cellspacing="0" cellpadding="2">

                <tr>
					<td class="label"><?php echo Portal::language('parent');?>:</td>
					<td><select  name="parent_id" id="parent_id" style="height: 24px;"><?php
					if(isset($this->map['parent_id_list']))
					{
						foreach($this->map['parent_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))
                    echo "<script>$('parent_id').value = \"".addslashes(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))."\";</script>";
                    ?>
	</select></td>
                    <td class="label"><?php echo Portal::language('code');?> <span style="color: red;">(*)</span>:</td>
					<td>
                    <?php if(Url::get('cmd')=='edit'){?>
                    <input  name="code" type="text" id="code" size="40" value="<?php echo $this->map['code'];?>" readonly="readonly" style="width: 150px;height: 24px;" />
                    <?php }else{?>
                    <input  name="code" id="code" size="40" style="width: 150px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                    <?php }?>
                    </td>
                    <td class="label"><?php echo Portal::language('name');?> <span style="color: red;">(*)</span>:</td>
					<td><input  name="name" id="name" size="40" style="width: 200px;height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>"></td>
				</tr>
                <tr>
					<td class="label"><?php echo Portal::language('privilege');?>:</td>
					<td><input  name="module_name" id="module_name" disabled="disabled" style="height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('module_name'));?>"></td>
				</tr>

			</table>
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<?php if(Url::get('cmd')=='edit'){?>
<style type="text/css">
#code{
		
}
</style>
<?php }?>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>