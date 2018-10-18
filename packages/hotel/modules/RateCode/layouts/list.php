<?php System::set_page_title(HOTEL_NAME);?>
<div class="room-type-supplier-bound">
<form name="ListRateCodeForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.list_rate_code.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" style="text-align: right; padding-right: 30px;"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: margin-right:5px;">[[.Add.]]</a><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(confirm('[[.are_you_sure.]]')){ ListRateCodeForm.cmd.value='delete_group';ListRateCodeForm.submit();}"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: margin-right:5px;">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table><br />
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="1%">[[.stt.]]</th>
              <th width="10%" align="center">[[.code.]]</th>
			  <th width="15%" align="center">[[.rate_code_name.]]</th>
			  <th width="10%" align="center">[[.start_date.]]</th>
              <th width="10%" align="center">[[.end_date.]]</th>
              <th width="20%" align="center">[[.customer_groups.]]</th>
              <th width="10%" align="center">[[.frequence.]]</th>
			  <th width="10%" align="center">[[.date_level.]]</th>
			  <th width="5%" align="center">[[.edit.]]</th>
		      <th width="5%" align="center">[[.delete.]]</th>
		  </tr>
          <!--LIST:items-->
            <?php
                if([[=items.index=]]%2==0)
                    echo '<tr style="background-color: #E8F3FF ;">';
                else
                    echo "<tr>"; 
            ?>
            
                <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/></td>
                <td style="text-align: center;">[[|items.index|]]</td>
                <td>[[|items.code|]]</td>
                <td>[[|items.name|]]</td>
                <td style="text-align: center;">[[|items.start_date|]]</td>
                <td style="text-align: center;">[[|items.end_date|]]</td>
                <td>[[|items.customer_groups|]]</td>
                <td>
                <?php
                    if([[=items.frequence=]]=='DAILY')
                        echo 'Hàng ngày';
                    elseif([[=items.frequence=]]=='WEEKLY')
                    {
                        echo 'Hàng tuần';
                        echo '<br/>';
                        echo [[=items.weekly=]];
                    }
                    elseif([[=items.frequence=]]=='MONTHLY')
                        echo 'Hàng tháng';
                    else
                        echo 'Hàng năm'; 
                ?>
                </td>
                <td>
                <?php
                    if([[=items.date_level=]]=='NORMAL')
                        echo 'Bình thường';
                    elseif([[=items.date_level=]]=='CELEBRATE')
                        echo 'Ngày lễ';
                    else
                        echo 'Đặc biệt'; 
                ?>
                </td>
                
                <td style="text-align: center;"><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif"/></a></td>
                <td style="text-align: center;"><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"/></a></td>
            </tr>
          <!--/LIST:items-->			
		</table>
		
	</div>
	<input name="cmd" type="hidden" id="cmd" value=""/>
</form>	
</div>
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListRoomLevelForm.cmd.value = 'delete';
		ListRoomLevelForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('[[.are_you_sure.]]')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
</script>