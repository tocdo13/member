<span style="display:none">
	<span id="mi_unit_sample">
		<span id="input_group_#xxxx#" style="white-space:nowrap;">
			<span class="multi_edit_input">
				<span><input  type="checkbox" id="_checked_#xxxx#"></span>
			</span>
			<span class="multi_edit_input">
				<span style="width:40px;"><input  name="mi_unit[#xxxx#][id]" type="text" id="id_#xxxx#" class="multi_edit_text_input" style="width:40px;text-align:right;" value="(auto)"></span>
			</span>
			<!--LIST:languages-->
			<span class="multi_edit_input">
				<input  name="mi_unit[#xxxx#][name_[[|languages.id|]]]" style="width:150px;" class="multi_edit_text_input" type="text" id="name_[[|languages.id|]]_#xxxx#"  tabindex="2">
			</span>
			<!--/LIST:languages-->
			<span class="multi_edit_input">
				<input  name="mi_unit[#xxxx#][value]" style="width:100px;" class="multi_edit_text_input" type="text" id="value_#xxxx#"  tabindex="2">
			</span>
			<span class="multi_edit_input">
				<input  name="mi_unit[#xxxx#][base_unit_id]" style="width:100px;" class="multi_edit_text_input" type="text" id="base_unit_id_#xxxx#"  tabindex="3">
			</span>
			<span class="multi_edit_input"><span style="width:20;">
				<img id="id_image_#xxxx#" src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_unit','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br/>
		</span>
	</span>
</span>
<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('product_unit'):Portal::language('product_unit');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_unit'));?>
<form name="EditUnitForm" method="post" >
<div align="center">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="margin-top:3px;text-align:left;">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="70%"><?php echo $title;?></td>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%"><a href="javascript:void(0)" onclick="EditUnitForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none;">[[.save.]]</a></td><?php }?>
                </tr>
            </table>
		</td>
	</tr>
	<tr>
	<td>
	<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
	<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>" />
	<table width="100%">
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EFEFEF" valign="top">
	<td bgcolor="#EFEFEF"><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?><tr bgcolor="#EFEFEF" valign="top">
		<td>
		<div style="background-color:#EFEFEF;">
		      <span id="mi_unit_all_elems">
					<span style="white-space:nowrap;">
						<span class="multi_edit_input_header"><span><input type="checkbox" value="1" onclick="mi_select_all_row('mi_unit',this.checked);"/></span></span>
						<span class="multi_edit_input_header"><span style="width:45px;">[[.id.]]</span></span>
						<!--LIST:languages-->
						<span class="multi_edit_input_header"><span style="width:155px;"><a href="<?php echo URL::build_current(array('order_by'=>'name_'.[[=languages.id=]]));?>">[[.name.]]([[|languages.code|]])</a></span></span>
                        <!--/LIST:languages-->
						<span class="multi_edit_input_header"><span style="width:105px;">[[.value.]]</span></span>
						<span class="multi_edit_input_header"><span style="width:105px;">[[.base_unit_id.]]</span></span>
						<br/>
					</span>
				</span>
		</div>
			<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_unit');"/>
		[[|paging|]]
		</td>
	</tr>
	<tr bgcolor="#EFEFEF">
		<td bgcolor="#EFEFEF">
			<p>
			<table>
			<tr><td>
				<input class="w3-btn w3-gray" type="button" value="  [[.discard.]]  " onclick="location='<?php echo URL::build_current(array());?>';"/>
				<input class="w3-btn w3-gray" type="button" value="  [[.delete.]]  " onclick="mi_delete_selected_row('mi_unit');" />
			</td></tr>
			</table>
			</p>
		</td>
	</tr>
	</table>
    <input name="confirm_edit" type="hidden" value="1" />
	</td>
</tr>
</table>
</div>
</form>
<script>
var name_array = new Array(
<?php if(isset($_REQUEST['check_arr'])){
        $a = $_REQUEST['check_arr'] ;
        foreach($a as $k=>$v)
        {
            echo($v['id']);
            echo(',');    
        }
}?> 
);

mi_init_rows('mi_unit',
	<?php if(isset($_REQUEST['mi_unit']))
	{
		echo String::array2js($_REQUEST['mi_unit']);
	}
	else
	{
		echo '[]';
	}
	?>);

for(var x = 101 ; x <= input_count;x++)
{
    for(var a = 0 ; a <name_array.length;a++)
    {
        if(jQuery('#id_'+x).val()==name_array[a])
        {
            console.log(jQuery('#id_image_'+x).hide());
        }
    }
}
</script>
