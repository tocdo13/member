<span style="display:none">
	<span id="room_repair_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-edit-input" style="display:none;" >
					<input  name="room_repair[#xxxx#][room_id]" style="width:150px; background: #FFC; " class="multi-edit-text-input" type="text" id="room_id_#xxxx#">
			</span>
            <span class="multi-edit-input">
					<input  name="room_repair[#xxxx#][room_name]" style="width:50px; text-align: center; background: #FFC; " class="multi-edit-text-input" type="text" id="room_name_#xxxx#" readonly >
			</span>
            <span class="multi-edit-input">
					<input  name="room_repair[#xxxx#][start_date]" style="width:100px; text-align: center; background: #FFC; " class="multi-edit-text-input" type="text" id="start_date_#xxxx#" readonly >
			</span>
            <span class="multi-edit-input">
					<input  name="room_repair[#xxxx#][end_date]" style="width:100px; text-align: center; background: #FFC; " class="multi-edit-text-input" type="text" id="end_date_#xxxx#" readonly >
			</span>
            <span class="multi-edit-input">
					<input  name="room_repair[#xxxx#][note]" style="width:270px; text-align: center;" class="multi-edit-text-input" type="text" id="note_#xxxx#" >
			</span>
            <span class="multi-edit-input">
					<input  name="room_repair[#xxxx#][status]" style="width:70px; text-align: center; background: #FFC; " class="multi-edit-text-input" type="text" id="status_#xxxx#" readonly >
			</span>
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi_edit_input"><span style="width:20px;">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'room_repair','#xxxx#','');event.returnValue=false; }" style="cursor:hand;"/>
			</span></span>
			<!--/IF:delete-->
			<br clear="all">
		</div>
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>

<form name="EditMinibarForm" method="post" >
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr>
    		<td width="55%" class="form-title">Set room status</td>
    		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.Save.]]" class="button-medium-save" ></td><?php }?>
    	</tr>
    </table>
    <table cellspacing="0">
    	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
    	<tr>
            <td style="padding-bottom:30px">
        		<table border="0">
            		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
            		<tr bgcolor="#EEEEEE" valign="top">
            			<td style="">
                			<div style="background-color:#EFEFEF;">
                				<span id="room_repair_all_elems">
                					<span style="white-space:nowrap; width:auto;">
                                        <span class="multi-edit-input_header">
                                            <span class="table-title" style="float:left;width:56px;text-align:center;padding:0px;">
                                                Room
                                            </span>
                                        </span>
                						<span class="multi-edit-input_header">
                                            <span class="table-title" style="float:left;width:106px;text-align:center;">
                                                [[.start_date.]]
                                            </span>
                                        </span>
                						<span class="multi-edit-input_header">
                                            <span class="table-title" style="float:left;width:106px;text-align:center;">
                                                [[.end_date.]]
                                            </span>
                                        </span>
                                        <span class="multi-edit-input_header">
                                            <span class="table-title" style="float:left;width:276px;text-align:center;">
                                                [[.note.]]
                                            </span>
                                        </span>
                                        <span class="multi-edit-input_header">
                                            <span class="table-title" style="float:left;width:76px;text-align:center;">
                                                [[.status.]]
                                            </span>
                                        </span>
                						<br clear="all">
                					</span>
                				</span>
                			</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>

<script>
<?php 	if(isset($_REQUEST['room_repair'])){
			echo 'var room_repair = '.String::array2js($_REQUEST['room_repair']).';';
		}else{
			echo 'var room_repair = [];';
		}
?>
console.log(room_repair);
mi_init_rows('room_repair',room_repair);
function Confirm(index){
	//var bar_table_name = $('name_'+index).value;
	return confirm('Are you sure?');
}
</script>