<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div class="body">
<form name="ListForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="65%" class="" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 30px;"></i> [[.minibar_limited.]]</td>
			<td align="right" nowrap="nowrap" style="padding-right: 50px;">
				<input type="button" class="w3-btn w3-cyan w3-text-white" onclick="location='<?php echo URL::build_current(array('cmd'=>'add'));?>'" value="[[.add_to_all.]]" style="text-transform: uppercase;"/>
				<input type="button" class="w3-btn w3-red" onclick="if(confirm('Are you sure to delete all product from all minibar? (This operation could not be undone!!!)'))location='<?php echo URL::build_current(array('cmd'=>'remove_all'));?>'" value="[[.remove_all.]]" style="text-transform: uppercase;"/>
                <input type="button" class="w3-btn w3-pink" onclick="if(confirm('Are you sure to delete all product from all minibar? (This operation could not be undone!!!)')){ ListForm.act.value='remove'; ListForm.submit(); }" value="[[.delete.]]" style="text-transform: uppercase;"/>
                <input name="act" type="hidden" id="act" />
			</td>
            <?php if(!MINIBAR_IMPORT_UNLIMIT){?>
			<td width="1%"><a href="<?php echo Url::build('minibar_import')?>" class="button-medium-add" >[[.minibar_import.]]</a></td>
            <?php }?>
        </tr>
    </table><br />
	<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
		<!--LIST:floors-->		
		<tr>
			<td width="60px" nowrap bgcolor="#82BAFF" style="color:#FFFFFF" align="center">
            <strong>[[|floors.name|]]</strong>
            <input id="[[|floors.class_room_floor|]]" type="checkbox" onclick="check_all(this.id);" />
            </td>
            <?php $i=1;?>
			<td>
			<!--LIST:floors.minibars-->
				<div class="room-bound">
				<a href="<?php echo URL::build_current(array('minibar_id'=>[[=floors.minibars.id=]]));?>" class="room <?php echo [[=floors.minibars.status=]]?'OCCUPIED':'AVAILABLE';?>" style="width:53px;height:53px;">
					<div><strong>[[|floors.minibars.room_name|]]</strong> <em style="font-size: 9px;">[[|floors.minibars.room_level_brief_name|]]</em></div>
                    <div><input name="selected_ids[]" type="checkbox" value="[[|floors.minibars.id|]]" class="[[|floors.class_room_floor|]]" /></div>
				</a>
				</div><?php if($i%12==0){echo '<div class="clear"></div>';}?>
            <?php $i++;?>
			<!--/LIST:floors.minibars-->
          	</td>
		</tr>		
		<!--/LIST:floors-->	
	</table>

</form>	

</div>
<script>
function check_all(id)
{
    var check = jQuery("#"+id).attr('checked');
    jQuery("."+id).each(function(){
        this.checked = check;
    });
}

</script>