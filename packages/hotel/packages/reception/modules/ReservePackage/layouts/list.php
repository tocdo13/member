<style>
.center{text-align:center;}
</style>
<div class="room-type-supplier-bound">
<form name="ListExtraServiceInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="100%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
			
        </tr>
    </table>
    
    <!--<fieldset>
			<legend class="title">[[.search.]]</legend>
			Bộ phận: <select name="department_id" id="department_id" style="width: 100px;"></select>
            <input name="search" type="submit" id="search" value="[[.search.]]" />
		</fieldset><br />-->
            
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
            <th width="5%" class="center">[[.stt.]]</th>
			  <th width="10%" class="center">[[.arrive_date.]]</th>
			  <th width="5%" class="center">Recode</th>
              <th width="5%" class="center">[[.room_name.]]</th>
              <th width="8%" class="center">[[.status.]]</th>
              <th width="15%" class="center">[[.service.]]</th>
              <th width="5%" class="center">[[.quantity.]]</th>
              <th width="5%" class="center">[[.unit.]]</th>
              <th width="10%" class="center">[[.used.]]</th>
			  <th width="8%" class="center">[[.price.]]</th>
			  <th width="8%" class="center">[[.money.]]</th>
			  <th width="10%" class="center">[[.note.]]</th>
              <th width="15%" class="center">[[.bar_reservation.]]</th>
		  </tr>
		  <!--LIST:items-->
          <?php
            if([[=items.status_reservation=]]=='CHECKOUT')
            {
                $style ='style="background-color: yellow;"';
            } 
            else
            {
                $style='';
            }
          ?>
			<tr style="height: 24px;" <?php echo $style;?>>
                <td class="center">[[|items.index|]]</td>
                <td class="center">[[|items.in_date|]]</td>
                <td class="center"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>" target="_blank">[[|items.reservation_id|]]</a></td>
                <td class="center">[[|items.room_name|]]</td>
                <td class="center">[[|items.status_reservation|]]</td>
                <td class="center">[[|items.name|]]</td>
                <td class="center">[[|items.quantity|]]</td>
		<td class="center">[[|items.unit|]]</td>
                <td class="center">[[|items.quantity_used|]]
                <?php
                    switch(Url::get('cmd'))
                    {
                        case 'res':
                        {
                            if(count([[=items.bars=]])>0)
                            {
                                $count = count([[=items.bars=]])-1;
                                $i= 0;
                                echo '(';
                                ?>
                                <!--LIST:items.bars-->
                                <?php
                                    
                                    if($i==$count)
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>[[=items.bars.id=]],'package_id'=>[[=items.bars.package_id=]],'rr_id'=>[[=items.bars.reservation_room_id=]],'table_id'=>[[=items.bars.table_id=]],'bar_area_id'=>[[=items.bars.bar_area_id=]],'bar_id'=>[[=items.bars.bar_id=]]));?>" target="_blank">[[|items.bars.id|]]</a>)
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>[[=items.bars.id=]],'package_id'=>[[=items.bars.package_id=]],'rr_id'=>[[=items.bars.reservation_room_id=]],'table_id'=>[[=items.bars.table_id=]],'bar_area_id'=>[[=items.bars.bar_area_id=]],'bar_id'=>[[=items.bars.bar_id=]]));?>" target="_blank">[[|items.bars.id|]]</a>,
                                        <?php 
                                    }
                                    $i++;
                                ?>
                                
                                <!--/LIST:items.bars-->
                                <?php 
                            } 
                            break;
                        }
                        case 'spa':
                        {
                            if(count([[=items.massages=]])>0)
                            {
                                $count = count([[=items.massages=]])-1;
                                $i= 0;
                                echo '(';
                                ?>
                                <!--LIST:items.massages-->
                                <?php
                                    
                                    if($i==$count)
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','id'=>[[=items.massages.id=]],'package_id'=>[[=items.massages.package_id=]],'rr_id'=>[[=items.massages.hotel_reservation_room_id=]]));?>" target="_blank">[[|items.massages.id|]]</a>)
                                        <?php 
                                    } 
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','id'=>[[=items.massages.id=]],'package_id'=>[[=items.massages.package_id=]],'rr_id'=>[[=items.massages.hotel_reservation_room_id=]]));?>" target="_blank">[[|items.massages.id|]]</a>,
                                        <?php 
                                    }
                                    $i++;
                                ?>
                                
                                <!--/LIST:items.massages-->
                                <?php 
                            }
                            break;
                        }
                        default:
                        {
                            break;
                        }
                    }
                    
                ?>
                
                </td>
                <td align="right">[[|items.price|]]</td>
                <td align="right">[[|items.total_amount|]]</td>
                <td align="left">[[|items.note|]]</td>
                <?php
                switch(Url::get('cmd'))
                {
                    case 'res':
                    {
                        if([[=items.status_reservation=]]=='CHECKIN' ||[[=items.status_reservation=]]=='BOOKED')
                        {
                            if([[=items.quantity_used=]]<[[=items.quantity=]] || [[=items.quantity_used=]]=='')
                            {
                                ?>
                                <td class="center"><a href="<?php echo Url::build('table_map',array('package_id'=>[[=items.package_sale_detail_id=]],'reservation_room_id'=>[[=items.reservation_room_id=]])); ?>" target="_blank"  style="text-align: center;">Đặt bàn</a></td>
                                <?php 
                            }
                            else
                            {
                                ?>
                                <td class="center"></td>
                                <?php 
                            }
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                    case 'kar':
                    {
                        if([[=items.status_reservation=]]=='CHECKIN' ||[[=items.status_reservation=]]=='BOOKED')
                        {
                        ?>
                        <td class="center"><a href="<?php echo Url::build('karaoke_table_map',array('package_id'=>[[=items.package_sale_detail_id=]],'reservation_room_id'=>[[=items.reservation_room_id=]])); ?>" target="_blank"  style="text-align: center;">Đặt phòng</a></td>
                        <?php 
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                    case 'spa':
                    {
                        if([[=items.status_reservation=]]=='CHECKIN' ||[[=items.status_reservation=]]=='BOOKED')
                        {
                            if([[=items.quantity_used=]]<[[=items.quantity=]] || [[=items.quantity_used=]]=='')
                            {
                                ?>
                                <td class="center"><a href="<?php echo Url::build('massage_daily_summary',array('package_id'=>[[=items.package_sale_detail_id=]],'reservation_room_id'=>[[=items.reservation_room_id=]])); ?>" target="_blank"  style="text-align: center;">Đặt phòng spa</a></td>
                                <?php 
                            }
                            else
                            {
                                ?>
                                <td class="center"></td>
                                <?php 
                            }
                        
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                    case 'vend':
                    {
                        if([[=items.status_reservation=]]=='CHECKIN')
                        {
                        ?>
                        <td class="center"><a href="<?php echo Url::build('vending_reservation',array('package_id'=>[[=items.id=]])); ?>" target="_blank"  style="text-align: center;">Bán hàng</a></td>
                        <?php 
                        }
                        else
                        {
                            ?>
                            <td class="center"></td>
                            <?php 
                        }
                        break;
                    }
                } 
                ?>
                
                
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
        alert('Bạn chưa chọn khách hàng');
        return false;
    } else {
        var answer = confirm("Bạn có chắc chắn không?");
        if (answer)
            window.location.href = '?page=package_service_two&cmd=delete&delete_ids=' + delete_ids;
        else
            return false;    
    }
}
function get_id_delete(id) {
    var answer = confirm("Bạn có chắc chắn không?");
    if (answer)
        window.location.href = '?page=package_service_two&cmd=delete&delete_ids='+id;
    else
        return false;
}

</script>
