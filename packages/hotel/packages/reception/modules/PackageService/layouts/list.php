<style>
.center{text-align:center;}
</style>
<div class="room-type-supplier-bound">
<form name="ListExtraServiceInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="45%" style="text-align: right;"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"  class="w3-btn w3-cyan w3-text-white">[[.Add.]]</a><?php }?>
            <?php if (User::can_delete(false, ANY_CATEGORY)) { ?>
            <a href="#" onclick="select_delete_all();" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.delete.]]</a></td>
            <?php } ?>
        </tr>
    </table>
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			Code/Name: <input name="keyword" type="text" id="keyword" style="height: 24px;" />
            <input name="search" type="submit" value="OK" style="height: 24px;" />
            <div style="display: inline-block;margin-left: 10%;font-weight: normal;font-style:italic;font-size:14px;color:red">[[.note_package.]]</div>
		</fieldset><br />
            
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase;">
			  <th width="5%" class="center">
              <input type="checkbox" id="all_item_check_box"/></th>
			  <th width="12%" class="center">[[.code_service.]]</th>
              <th width="25%" class="center">[[.name_service.]]</th>
              <th width="12%" class="center">[[.price.]]</th>
			  <th width="12%" class="center">[[.unit.]]</th>
			  <th width="12%" class="center">[[.department.]] </th>
			  <th width="12%" class="center">[[.use.]]</th>
              <th width="5" class="center">[[.edit.]]</th>
              <th width="5%" class="center">[[.delete.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td class="center">
              <?php 
                    if([[=items.can_delete=]]==0)
                    {
                        ?>
                        <input name="item_check_box" type="checkbox" class="item-check-box" value="[[|items.id|]]" />
                        <?php 
                    }
                 ?>
                
              </td>
			  <td class="center">[[|items.code|]]</td>
				<td class="center">[[|items.name|]]</td>
                <td class="right"><?php echo System::display_number([[=items.price=]]); ?></td>
				<td class="center">[[|items.unit|]]</td>
				<td class="center">[[|items.department_name|]]</td>
                <td class="center">
                <?php 
                if([[=items.used=]]==1){echo 'Yes';}else{echo 'NO';}
                ?>
                </td>
                
                <td class="center"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>" target="_blank"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
                 <td class="center">
                 <?php 
                    if([[=items.can_delete=]]==0)
                    {
                        ?>
                        <a href="#" class="bh" onclick="get_id_delete([[|items.id|]]);">
                            <img src="packages/core/skins/default/images/buttons/delete.gif" style="width: 17px; height: auto; cursor: pointer;"/>
                        </a>
                        <?php 
                    }
                 ?>
                    
                 </td>
                 
			</tr>
            <!--/LIST:items-->		
		</table>
<br />
		
	</div>
	<input name="cmd" type="hidden" value="">
</form>	
</div>


<script type="text/javascript">
    jQuery("#delete_button").click(function () {
    ListRoomTypeForm.cmd.value = 'delete';
    ListRoomTypeForm.submit();
});
jQuery(".delete-one-item").click(function () {
    if (!confirm('[[.are_you_sure.]]')) {
        return false;
    }
});
jQuery("#all_item_check_box").click(function () {
    var check = this.checked;
    jQuery(".item-check-box").each(function () {
        this.checked = check;
    });
});

function select_delete_all() {

    var delete_ids = [];
    jQuery.each(jQuery("input[name='item_check_box']:checked"), function () {
        delete_ids.push(jQuery(this).val());
    });
    delete_ids.join(",");
    if (delete_ids == '') {
        alert('Bạn chưa chọn package');
        return false;
    } else {
        var answer = confirm("Bạn có chắc chắn không?");
        if (answer)
            window.location.href = '?page=package_service&cmd=delete&delete_ids=' + delete_ids;
        else
            return false;    
    }
}
function get_id_delete(id) {
    var answer = confirm("Bạn có chắc chắn không?");
    if (answer)
        window.location.href = '?page=package_service&cmd=delete&delete_ids='+id;
    else
        return false;
}

</script>