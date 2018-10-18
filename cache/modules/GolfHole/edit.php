<style>
/* Safari*/
    @media screen and (-webkit-min-device-pixel-ratio:0)  {
        .demo{ display: block; }
    }
</style>
<span style="display:none;">
	<span id="mi_hole_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input" style="text-align: center; width: 16px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_hole[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#" class="w3-input w3-border" tabindex="-1" style="width:35px;background:#EFEFEF;"/></span>
			<span class="multi-input">
					<input  name="mi_hole[#xxxx#][code]" style="width:100px;font-weight:bold;color:#06F;" class="w3-input w3-border" type="text" id="code_#xxxx#"/>
			</span>
			
			<span class="multi-input">
				<input  name="mi_hole[#xxxx#][name]" style="width:150px;" class="w3-input w3-border" type="text" id="name_#xxxx#"/>
			</span>
            <span class="multi-input">
				<input  name="mi_hole[#xxxx#][num]" style="width:100px; text-align: right;" class="input_number w3-input w3-border" type="text" onchange="check_num('#xxxx#');" id="num_#xxxx#"/>
			</span>
			
			<?php if(User::can_delete(false,ANY_CATEGORY)){?>
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onclick="if(Confirm('#xxxx#')){mi_delete_row($('input_group_#xxxx#'),'mi_hole','#xxxx#','');}" style="cursor:pointer;"/>
			</span>
			<?php }?>
		</div><br clear="all" />
	</span>
</span>
<form name="HoleForm" method="post" >
<table cellpadding="10" cellspacing="10">
	<tr height="40">
		<td class="form-title"><?php echo Portal::language('manage_hole');?></td>
		<?php if(User::can_edit(false,ANY_CATEGORY) || User::can_delete(false,ANY_CATEGORY)|| User::can_add(false,ANY_CATEGORY)){?>
            <td><input name="save" type="submit" id="save" value="<?php echo Portal::language('save');?>" class="w3-button w3-amber"/></td>
        <?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <!--<td width="1%"><a href="javascript:void(0);" onclick="DeleteSelected();" class="button-medium-delete"><?php echo Portal::language('Delete');?></a></td>-->
            <td style="text-align: right;"><input type="button" value="<?php echo Portal::language('delete');?>" onclick=" DeleteSelected();"  class="w3-button w3-pink" /></td>
        <?php }?>
	</tr>
    <tr>
        <td colspan="3">
            <p style="color: red;">(*): Mã, Tên, Số lượng lỗ không được để trống và trùng nhau</p>
        </td>
    </tr>
</table>
<div class="global-tab">
<div class="header">

</div>
<div class="body">
<table cellspacing="0" width="100%">
	<tr valign="top"><td></td></tr>
	<tr>
        <td style="padding-bottom:30px">
            <input name="module_id" type="hidden" id="module_id" value="<?php echo $this->map['module_id'];?>" />
            <input name="structure_id" type="hidden" id="structure_id" value="<?php echo $this->map['structure_id'];?>" />
    		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>"/>
    		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
            <table border="0">
            <?php //if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
                <tr valign="top">
                    <td style="">
            			<div>
            				<span id="mi_hole_all_elems">
            					<span>
            						<span class="multi-input-header" style="width:16px; height: 40px; line-height: 40px; text-align: center;"><input type="checkbox" value="1" onclick="mi_select_all_row('mi_hole',this.checked);"/>
            						</span>
            						<span class="multi-input-header" style="width:35px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('ID');?></span>
            						<span class="multi-input-header" style="width:100px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('code');?></span>
            						<span class="multi-input-header" style="width:150px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('name');?></span>
            						<span class="multi-input-header" style="width:100px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('num_hole');?></span>            						
            						<span class="multi-input-header" style="width:70px; height: 40px; line-height: 40px; text-align: center;"><?php echo Portal::language('Delete');?></span>
            					</span>
                                <br clear="all"/>
            				</span>
            			</div>
                        <br clear="all"/>
                        <?php
                        if(User::can_add(false,ANY_CATEGORY))
                        { 
                        ?>
            			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_hole');document.getElementById('num_'+ input_count).value=0;" class="w3-button w3-blue"><?php echo Portal::language('Add');?></a></div>
                        <?php 
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div></div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
/* 
<?php 	
        if(isset($_REQUEST['hole']))
        {
			echo 'var holes = '.String::array2js($_REQUEST['hole']).';';
            ?>
            mi_init_rows('hole',holes);
            <?php 
		}
?>
*/
<?php     
    if(isset($_REQUEST['mi_hole'])){
		echo 'var holes = '.String::array2js($_REQUEST['mi_hole']).';';
	}else{
		echo 'var holes = [];';
	}
?>
mi_init_rows('mi_hole',holes);
function DeleteSelected()
{       
    var inputs = jQuery('input:checkbox:checked');
    if(inputs.length > 0){                    
        mi_delete_selected_row('mi_hole');                                           
    }else{
        alert('<?php echo Portal::language('no_items_to_delete');?>');
    }    
}
function Confirm(index)
{
	return confirm('<?php echo Portal::language('Are_you_sure_delete');?>');
}

function check_num($index,$flag=false)
{
    if(to_numeric(to_numeric(jQuery("#num_"+ $index).val())%9)==0)
    {
        jQuery("#num_"+ $index).val(to_numeric(jQuery("#num_"+ $index).val()));
        return true;
    }
    else
    {
        if(!$flag){
            alert('<?php echo Portal::language('num_multiple_9');?>');
            jQuery("#num_"+ $index).val('');
        }
        return false;
    }
}
</script>