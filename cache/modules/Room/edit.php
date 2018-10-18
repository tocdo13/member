<span style="display:none">
	<span id="room_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"></span>
			<span class="multi-input"><input  name="room[#xxxx#][id]" type="text" readonly="" id="id_#xxxx#"  tabindex="-1"  style="width:35px;background:#EFEFEF;display:none;"/></span>
            <span class="multi-input"><input  name="room[#xxxx#][stt]" type="text" readonly="readonly" class="readonly" id="stt_#xxxx#"  tabindex="-1"  style="width:35px;background:#EFEFEF;"/></span>
			<span class="multi-input">
					<input  name="room[#xxxx#][name]" style="width:155px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="name_#xxxx#">
			</span>
			<span class="multi-input">
				<select  name="room[#xxxx#][room_level_id]" style="width:155px;" class="multi-edit-text-input"  id="room_level_id_#xxxx#" onchange="CheckChangeRoomLevel('#xxxx#');"><option value=""></option>
					<?php echo $this->map['room_level_options'];?>
				</select>
			</span>
			<span class="multi-input">
				<select  name="room[#xxxx#][room_type_id]" style="width:105px;" class="multi-edit-text-input"  id="room_type_id_#xxxx#"><option value=""></option>
					<?php echo $this->map['room_type_options'];?>
				</select>
			</span>
            <!--<span class="multi-input">
                <select  name="room[#xxxx#][area_id]" style="width:150px;" class="multi-edit-text-input"  id="area_id_#xxxx#">
					<?php echo $this->map['area_options'];?>
				</select>
            </span>-->
			<span class="multi-input">
				<input  name="room[#xxxx#][floor]" style="width:150px; height: 21px;" type="text" class="multi-edit-text-input" id="floor_#xxxx#"/>
			</span>
            <!--<span class="multi-input">
				<input  name="room[#xxxx#][house_name]" style="width:150px;" class="multi-edit-text-input" type="text" id="house_name_#xxxx#" />
			</span>-->
			<span class="multi-input">
				<input  name="room[#xxxx#][position]" style="width:70px;" class="multi-edit-text-input" type="text" id="position_#xxxx#" onchange="check_validate_position();" >
			</span>
            <span class="multi-input">
                <input  name="room[#xxxx#][coordinates]" style="width:70px;" class="multi-edit-text-input" type="text" id="coordinates_#xxxx#">
            </span>
            <span class="multi-input" style="display: none;">
                <select  name="room[#xxxx#][close_room]" style="width:100px;" class="multi-edit-text-input"  id="close_room_#xxxx#" onchange="CheckCloseRoom('#xxxx#');">
					<?php echo $this->map['status_option'];?>
				</select>
            </span>
			<span class="multi-input"><input style="width:70px;" name="room[#xxxx#][minibar_id]" type="checkbox" id="minibar_id_#xxxx#" checked /></span>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'room','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/>
			</span>
			
				<?php
				}
				?>
		</div><br clear="all" />
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditMinibarForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="90%" class="form-title"><?php echo Portal::language('manage_room');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="<?php echo Portal::language('Save');?>" class="button-medium-save" onclick="return check_validate_position();"/></td><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('room');" class="button-medium-delete"><?php echo Portal::language('Delete');?></a></td><?php }?>
	</tr>
</table>
<div class="global-tab">
<div class="header">
<?php if(isset($this->map['portals']) and is_array($this->map['portals'])){ foreach($this->map['portals'] as $key1=>&$item1){if($key1!='current'){$this->map['portals']['current'] = &$item1;?>
	<a <?php echo Url::get('portal_id')==$this->map['portals']['current']['id']?'class="selected"':''?> href="<?php echo Url::build_current(array('portal_id'=>$this->map['portals']['current']['id']));?>"><?php echo $this->map['portals']['current']['name'];?></a>
<?php }}unset($this->map['portals']['current']);} ?>
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
				<span id="room_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px;"><input type="checkbox" value="1" onclick="mi_select_all_row('room',this.checked);">
						</span>
						<!--<span class="multi-input-header" style="width:35px;"><?php echo Portal::language('ID');?></span>-->
                        <span class="multi-input-header" style="width:30px; text-align: center;"><?php echo Portal::language('stt');?></span>
						<span class="multi-input-header" style="width:155px; text-align: center;"><?php echo Portal::language('Room_name');?></span>
						<span class="multi-input-header" style="width:160px; text-align: center;"><?php echo Portal::language('room_level');?></span>
						<span class="multi-input-header" style="width:100px; text-align: center;"><?php echo Portal::language('bed_type');?></span>
                        <!--<span class="multi-input-header" style="width:150px; text-align: center;"><?php echo Portal::language('area');?></span>-->
						<span class="multi-input-header" style="width:150px; text-align: center;"><?php echo Portal::language('Floor');?></span>
                        <!--<span class="multi-input-header" style="width:150px; text-align: center;"><?php echo Portal::language('house');?></span>-->
						<span class="multi-input-header" style="width:70px; text-align: center;"><?php echo Portal::language('Position');?></span>
                        <span class="multi-input-header" style="width:70px;"><?php echo Portal::language('coordinates');?></span>
                        <!--<span class="multi-input-header" style="width:100px; text-align: center;"><?php echo Portal::language('status');?></span>-->
						<span class="multi-input-header" style="width:70px; text-align: center;"><?php echo Portal::language('Have_Minibar');?></span>
						<span class="multi-input-header" style="width:70px; text-align: center;"><?php echo Portal::language('Delete');?></span>
					</span>
                    <br clear="all">
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('room');$('name_'+input_count).focus();" class="button-medium-add"><?php echo Portal::language('Add');?></a></div>
</td></tr></table></td></tr></table>
</div></div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="loading_room" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none;">
    
</div>
<script>
<?php 	if(isset($_REQUEST['room'])){
			echo 'var minibars = '.String::array2js($_REQUEST['room']).';';
		}else{
			echo 'var minibars = [];';
		}
?>
function warning(obj){
	if(jQuery('#id_'+jQuery(obj).attr('index')).val() && window.minibars[jQuery('#id_'+jQuery(obj).attr('index')).val()]){
		var obj = window.minibars[jQuery('#id_'+jQuery(obj).attr('index')).val()];
		if(obj['no_delete']=='true'){
			alert('<?php echo Portal::language('you_can_delete_this_minibar_because_it_cause_damaged_for_system');?>');
			return false;
		}
	}
	return true;
}
//console.log(minibars);
mi_init_rows('room',minibars);
function Confirm(index){
	var room_name = $('name_'+index).value;
	return confirm('<?php echo Portal::language('Are_you_sure_delete_room');?> '+room_name+'?');
}
var DeleteMessage = '<?php echo Portal::language('Delete_room_which_is_used_cause_error_for_report_and_statistic');?>';
DeleteMessage += '<?php echo Portal::language('You_should_delete_when_you_sure_room_that_you_choose_never_in_used');?>';
function ConfirmDelete(){
	return confirm(DeleteMessage);
}
function check_validate_position()
{
    return true;
    var $check = 0;
    var $check_position = new Array();
    for(var i=101;i<=input_count;i++)
    {
        if(jQuery("#position_"+i).val()!= undefined && jQuery("#position_"+i).val()!='' && to_numeric(jQuery("#position_"+i).val())>0)
        {
            if(!$check_position[jQuery("#floor_"+i).val()])
            {
                $check_position[jQuery("#floor_"+i).val()] = new Array();
                $check_position[jQuery("#floor_"+i).val()]['child'] = new Array();
                $check_position[jQuery("#floor_"+i).val()]['child'][jQuery("#position_"+i).val()] = new Array();
                $check_position[jQuery("#floor_"+i).val()]['child'][jQuery("#position_"+i).val()]['id'] = jQuery("#position_"+i).val()
            }
            else
            {
                if($check_position[jQuery("#floor_"+i).val()]['child'][jQuery("#position_"+i).val()])
                {
                    jQuery("#position_"+i).css('background','#fcffa2');
                    $check = 1;
                }
                else
                {
                    $check_position[jQuery("#floor_"+i).val()]['child'][jQuery("#position_"+i).val()] = jQuery("#position_"+i).val();
                }
            }
        }
    }
    if($check==1)
    {
        //alert('<?php echo Portal::language('conflict_position');?>');
        //return false;
        
    }
    else
    {
        return true;
    }
    
}

function CheckChangeRoomLevel($index)
{
    if(jQuery("#id_"+$index).val()!='')
    {
        jQuery("#loading_room").css('display','');
        $room_arr = jQuery("#id_"+$index).val().split('-');
        $room_id = $room_arr[1];
        jQuery.ajax({
             url:"get_change_room_level.php?",
             type:"POST",
             data:{data:'check_booking',room_id:$room_id},
             success:function(html)
                                {
                                    
                                    var obj = jQuery.parseJSON(html);
                                    if(obj['status']=='F')
                                    {
                                        $list_recode = '----------------------------\n';
                                        for(var recode in obj['reservation'])
                                        {
                                            $list_recode += '   + Recode: #'+obj['reservation'][recode]['id']+'\n';
                                        }
                                        alert('khong duoc thay doi loai phong! phong nay da co booking dat\n'+$list_recode);
                                        jQuery("#room_level_id_"+$index).val(obj['room_level_id']);
                                    }
                                    jQuery("#loading_room").css('display','none');
                                }
          });
    }
}

function CheckCloseRoom($index)
{
    if(to_numeric(jQuery("#close_room_"+$index).val()) != 1 && jQuery("#id_"+$index).val()!='')
    {
        jQuery("#loading_room").css('display','');
        $room_arr = jQuery("#id_"+$index).val().split('-');
        $room_id = $room_arr[1];
        jQuery.ajax({
             url:"get_change_room_level.php?",
             type:"POST",
             data:{data:'check_close_room',room_id:$room_id,in_date:'<?php echo date('d/m/Y'); ?>'},
             success:function(html)
                                {
                                    var obj = jQuery.parseJSON(html);
                                    if(obj['status']=='F')
                                    {
                                        $list_recode = '----------------------------\n';
                                        for(var recode in obj['reservation'])
                                        {
                                            $list_recode += '   + Recode: #'+obj['reservation'][recode]['id']+'\n';
                                        }
                                        alert('Khong duoc an phong, phong nay dang co booking chua su dung xong\n'+$list_recode);
                                        jQuery("#close_room_"+$index).val(1);
                                    }
                                    jQuery("#loading_room").css('display','none');
                                }
          });
    }
}
</script>
