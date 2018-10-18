<script type="text/javascript" src="packages/core/includes/js/picker.js"></script>
<?php System::set_page_title(HOTEL_NAME);?>
<span style="display:none">
	<span id="mi_policy_group_sample">
		<div id="input_group_#xxxx#" style="text-align:left;">
			<input  name="mi_policy_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input"><input  name="mi_policy_group[#xxxx#][name]" style="width:100px;" type="text" id="name_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="mi_policy_group[#xxxx#][policy_from]" type="text" id="policy_from_#xxxx#" style="width:70px;"></span>
            <span class="multi-input"><input  name="mi_policy_group[#xxxx#][policy_to]" type="text" id="policy_to_#xxxx#" style="width:70px;"></span>
			<span class="multi-input"><input  name="mi_policy_group[#xxxx#][rate]" style="width:70px;" type="text" id="rate_#xxxx#"></span>
            <span class="multi-input"><select   name="mi_policy_group[#xxxx#][privilege]" id="privilege_#xxxx#" style="width:150px;" ><?php echo $this->map['privilege_options'];?></select></span>
			<span class="multi-input">
				<span style="width:20px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_policy_group','#xxxx#','group_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/></span></span>
             <br clear="all">
		</div>
	</span> 
</span>
<div>   
<form name="EditRoomLevelForm" method="post">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="90%" class="form-title"><?php echo $this->map['title'];?></td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="<?php echo Portal::language('Save');?>" class="button-medium-save"></td><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="<?php echo Url::build_current();?>"  class="button-medium-back"><?php echo Portal::language('back');?></a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table width="100%" cellpadding="5" cellspacing="0">
          <tr valign="top">
           <td>
            <fieldset>
             <legend class="title"><?php echo Portal::language('general_info');?></legend>
                <table border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td class="label"><?php echo Portal::language('name');?>(*):</td>
                        <td><input  name="name" id="name" type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>"></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo Portal::language('brief_name');?>:</td>
                        <td><input  name="brief_name" id="brief_name" type ="text" value="<?php echo String::html_normalize(URL::get('brief_name'));?>"></td>
                    </tr>
                    <tr>
                      <td class="label"><?php echo Portal::language('num_people');?>:</td>
                      <td><input  name="num_people" id="num_people" type ="text" value="<?php echo String::html_normalize(URL::get('num_people'));?>"></td>
                  </tr>
                  <tr>
                      <td class="label"><?php echo Portal::language('price');?>:</td>
                      <td><input  name="price" id="price" / type ="text" value="<?php echo String::html_normalize(URL::get('price'));?>"></td>
                  </tr>
                  <tr>
                    <td class="label"><?php echo Portal::language('display_color');?></td>
                    <td><input  name="color" id="color" / type ="text" value="<?php echo String::html_normalize(URL::get('color'));?>"><span onclick="TCP.popup($('color'));" class="color-select-button" title="<?php echo Portal::language('select_color');?>"><img src="packages/core/skins/default/images/color_picker.gif" /></span></td>
                  </tr>
                  <tr>
                    <td class="label"><?php echo Portal::language('is_virtual');?></td>
                    <td><input  name="is_virtual" type="checkbox" id="is_virtual" value="1" <?php echo (URL::get('is_virtual')?'checked="checked"':'');?>></td>
                  </tr>
                  <tr>
                    <td class="label"><?php echo Portal::language('portal_name');?></td>
                    <td><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                  </tr>
                  <tr>
                    <td class="label"><?php echo Portal::language('stt');?></td>
                    <td><input  name="position" id="position" / type ="text" value="<?php echo String::html_normalize(URL::get('position'));?>"></td>
                  </tr>
                </table>
             </fieldset>	
            </td>
            <td width="65%">
                    <fieldset>
                        <legend class="title"><?php echo Portal::language('rate_policy');?></legend>
                            <span id="mi_policy_group_all_elems" style="text-align:left;">
                                <span>
                                    <span class="multi-input-header" style="width:100px;float:left;"><?php echo Portal::language('policy_name');?></span>
                                    <span class="multi-input-header" style="width:70px;float:left;"><?php echo Portal::language('policy_from');?></span>
                                    <span class="multi-input-header" style="width:70px;float:left;"><?php echo Portal::language('policy_to');?></span>
                                    <span class="multi-input-header" style="width:70px;float:left;"><?php echo Portal::language('rate');?></span>
                                    <span class="multi-input-header" style="width:150px;float:left;"><?php echo Portal::language('privilege');?></span>
                                </span>
                                <br clear="all">
                            </span>
                            <input type="button" value="<?php echo Portal::language('add_policy');?>" onclick="mi_add_new_row('mi_policy_group');jQuery('#policy_from_'+input_count).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });jQuery('#policy_to_'+input_count).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });" class="button-medium-add">
                    </fieldset>
                    </td>
                </tr>
            </table>
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
mi_init_rows('mi_policy_group',<?php echo isset($_REQUEST['mi_policy_group'])?String::array2js($_REQUEST['mi_policy_group']):'{}';?>);
for(var i=101; i<=input_count;i++){
	if($('policy_from_'+i)){
		jQuery('#policy_from_'+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	}
	if($('policy_to_'+i)){
		jQuery('#policy_to_'+i).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
	}
}
</script>