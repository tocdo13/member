<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="party_room_sample">
		<div id="input_group_#xxxx#">        
            <input   name="party_room[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/>            
			<span class="multi-input" style="width:30px;border:1px solid #CCC; height: 24px;"><input  type="checkbox" id="_checked_#xxxx#" tabindex="-1"/></span>            
			<span class="multi-input"><input  name="party_room[#xxxx#][no]" type="text" readonly="readonly" id="no_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/></span>            
			<span class="multi-input"><input  name="party_room[#xxxx#][code]" type="text" id="code_#xxxx#"  tabindex="-1" readonly=""  style="width:30px; height: 24px;background:#CCC"/></span>
			<span class="multi-input"><input  name="party_room[#xxxx#][name]" style="width:150px; height: 24px;" class="multi-edit-text-input" type="text" id="name_#xxxx#"/></span>            
            <span class="multi-input"><input  name="party_room[#xxxx#][group_name]" type="text" id="group_name_#xxxx#" style="width:100px; height: 24px;"/></span>
            <span class="multi-input"><input  name="party_room[#xxxx#][address]" type="text" id="address_#xxxx#" style="width:300px; height: 24px;"/></span>
            <span class="multi-input"><input  name="party_room[#xxxx#][price]" type="text" id="price_#xxxx#" style="width:100px; height: 24px;text-align:right"/></span>
            <span class="multi-input"><input  name="party_room[#xxxx#][price_half_day]" type="text" id="price_half_day_#xxxx#" style="width:100px; height: 24px;text-align:right"/></span>
            <span class="multi-input"><input  name="party_room[#xxxx#][num]" type="text" id="num_#xxxx#" style="width:30px; height: 24px;text-align:right"/></span>
			<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width:70px; height: 24px; text-align:center;"><a href="#" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'party_room','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></span>
			
				<?php
				}
				?>
			<br clear="all" /> 
		</div>
	</span>
</span>
<form name="Editparty_room" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr height="40">
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('manage_party_room');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input type="submit" value="<?php echo Portal::language('Save');?>" class="w3-btn w3-blue" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('party_room');" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a></td><?php }?>
	</tr>
</table>
<table cellspacing="0" style="margin-left: 15px;">
	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
        <?php if(Form::$current->is_error()){?>
        <div>
            <br/>
            <?php echo Form::$current->error_messages();?>
        </div>
        <?php }?>
		<table border="0">
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="party_room_all_elems">
					<span style="white-space:nowrap; width:auto; text-transform: uppercase;">
						<span class="multi-input-header" style="float:left;width:30px; height: 24px; padding-top: 5px; text-align:left;padding:0px;"><input type="checkbox" value="1" onclick="mi_select_all_row('party_room',this.checked);"></span>
						<span class="multi-input-header" style="float:left;width:30px; height: 24px; padding-top: 5px;"><?php echo Portal::language('order_number');?></span>
						<span class="multi-input-header" style="float:left;width:30px; height: 24px; padding-top: 5px;"><?php echo Portal::language('code');?></span>
						<span class="multi-input-header" style="float:left;width:150px; height: 24px; padding-top: 5px;"><?php echo Portal::language('banquet_room_name');?></span>
                        
   						<span class="multi-input-header" style="float:left;width:100px; height: 24px; padding-top: 5px;"><?php echo Portal::language('group');?></span>
                        <span class="multi-input-header" style="float:left;width:300px; height: 24px; padding-top: 5px;"><?php echo Portal::language('room_address');?></span>
						<span class="multi-input-header" style="float:left;width:100px; height: 24px; padding-top: 5px;"><?php echo Portal::language('price');?></span>
                        <span class="multi-input-header" style="float:left;width:100px; height: 24px; padding-top: 5px;"><?php echo Portal::language('price_half_day');?></span>
                        <span class="multi-input-header" style="float:left;width:30px; height: 24px; padding-top: 5px;"><?php echo Portal::language('num');?></span>                                                    
						<span class="multi-input-header" style="float:left;width:70px;text-align:center; height: 24px; padding-top: 5px;"><?php echo Portal::language('Delete');?>?</span>
						<br clear="all">
					</span>
				</span>
			</div>
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('party_room');$('name_'+input_count).focus();jQuery('#price_'+input_count).ForceNumericOnly();jQuery('#price_half_day_'+input_count).ForceNumericOnly();jQuery('#price_'+input_count).FormatNumber();jQuery('#price_half_day_'+input_count).FormatNumber();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;"><?php echo Portal::language('Add');?></a></div>
</td></tr></table></td></tr></table><input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
<?php 
  if(isset($_REQUEST['party_room']))
  {
	  echo 'var party_room = '.String::array2js($_REQUEST['party_room']).';';
  }
  else
  {
	  echo 'var party_room = [];';
  }
?>
mi_init_rows('party_room',party_room);
function Confirm(index){
	var party_room_name = $('name_'+ index).value;
	return confirm('<?php echo Portal::language('Are_you_sure_delete_party_room_name');?> '+ party_room_name+'?');
}
var DeleteMessage = '<?php echo Portal::language('Delete_party_room_which_is_used_cause_error_for_report_and_statistic');?>\n';
DeleteMessage += '<?php echo Portal::language('You_should_delete_when_you_sure_party_room_that_you_choose_never_in_used');?>';
function ConfirmDelete(){
	var checkok =false;
	for(var i=101;i<=input_count;i++){	
	  if($('_checked_'+i) && $('_checked_'+i).checked == true){
		  checkok =true;
		  break;
		}
	}
	if(checkok ==false){
		var messa='<?php echo Portal::language('no_items_selected');?>';
		return alert(messa);
	}else{
	  return confirm(DeleteMessage);}
}
var count=0;
jQuery('.button-medium-save').click(function(event){
    count++;
    if(count>1)
    {
        event.preventDefault(); 
    }
})
</script>