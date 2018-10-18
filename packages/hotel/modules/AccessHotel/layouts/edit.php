<span style="display:none">
    <span id="hotel_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1" /></span>
			<span class="multi-input"><input  name="hotel[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:35px;background:#EFEFEF;"/></span>
            <span class="multi-input">
					<input  name="hotel[#xxxx#][hotel_name]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="hotel_name_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="hotel[#xxxx#][hotel_link]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="hotel_link_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="hotel[#xxxx#][hotel_portal]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="hotel_portal_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="hotel[#xxxx#][note]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="note_#xxxx#"/>
			</span>
            <span class="multi-input">
					<input  name="hotel[#xxxx#][is_active]" type="checkbox" id="is_active_#xxxx#" class="checkbox" style="width: 80px;" onclick="fun_checked(#xxxx#);"/>
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'hotel','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div><br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="HotelForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="90%" class="form-title">[[.access_hotel.]]</td>
		<td width="5%"><input type="submit" value="[[.Save.]]" class="button-medium-save"></td>
		<td width="5%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('hotel');" class="button-medium-delete">[[.Delete.]]</a></td>
	</tr>
</table>
<div class="global-tab">
<div class="header">
</div>
<div class="body">
<table cellspacing="0" width="100%">
	<tr valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div>
				<span id="hotel_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px;"><input type="checkbox" value="1" onclick="mi_select_all_row('hotel',this.checked);"/>
						</span>
						<span class="multi-input-header" style="width:35px;">[[.ID.]]</span>
						<span class="multi-input-header" style="width:155px;">[[.hotel_name.]]</span>
                        <span class="multi-input-header" style="width:155px;">[[.hotel_link.]]</span>
                        <span class="multi-input-header" style="width:155px;">[[.hotel_portal.]]</span>
                        <span class="multi-input-header" style="width:155px;">[[.note.]]</span>
                        <span class="multi-input-header" style="width:80px;">[[.is_active.]]</span>
						<span class="multi-input-header" style="width:70px;">[[.Delete.]]</span>
					</span>
                    <br clear="all"/>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('hotel');$('name_'+input_count).focus();auto_check();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table>
</div></div>
</form>

<script>
<?php 	if(isset($_REQUEST['hotel'])){
			echo 'var star = '.String::array2js($_REQUEST['hotel']).';';
		}else{
			echo 'var star = [];';
		}
?>
mi_init_rows('hotel',star);
auto_check();
function Confirm(index)
{
    var hotel_name = $('hotel_name_'+index).value;
    return confirm('[[.Are_you_sure_delete_hotel.]]'+ hotel_name+'?');
}

function ConfirmDelete()
{
    return confirm('[[.Are_you_sure_delete_hotel_selected.]]');
}
function auto_check()
{
    var check = false;
    for(var i=101;i<=input_count;i++)
    {
    	if(document.getElementById("is_active_"+i).checked==true)
        {
            check = true;
        }
    }
    if(check==false)
    {
        for(var i=101;i<=input_count;i++)
        {
        	if(i==input_count)
            {
                document.getElementById("is_active_"+i).checked=true;
            }
        }
    }
}
function fun_checked(id)
{
    if(document.getElementById("is_active_"+id).checked==false)
    {
        document.getElementById("is_active_"+id).checked=true;
    }
    else
    {
        for(var i=101;i<=input_count;i++)
        {
        	if(i!=id)
            {
                document.getElementById("is_active_"+i).checked=false;
            }
        }
    }
}
</script>