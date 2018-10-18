<div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></div>
<?php $total_amount = 0;$ta=0; ?>
<!--IF:first_pages([[=page_no=]]==1)--><br /><br />
<label style="margin-left: 15px;">[[.total_to_reception.]]</label>:<span class="change_numTr"><?php echo System::display_number([[=amount_with_room=]]) ?>VND  </span>   
<!--/IF:first_pages-->
<table border=1 cellspacing=0 cellpadding=5 bordercolor=#cccccc style="width: 98%; text-align: center; margin: 5px auto;" id="export">
    <tr>
        <th>[[.name.]]</th>
        <th>[[.unit.]]</th>
        <th>[[.price.]]</th>
        <th>[[.quantity.]]</th>
        <th>[[.discount_product.]](%)</th>
        <th>[[.discount_invoice.]](%)</th>
        <th>[[.deposit.]]</th>
        <th>[[.total.]]</th>
    </tr>
    <!--IF:first_pages([[=page_no=]]!=1)-->
    <!---------LAST GROUP VALUE----------->	        
    <tr style="font-weight: bold;">
        <td colspan="3" align="right" nowrap class="report_table_column"><strong>[[.last_page_summary.]]</strong></td>
        <td style="text-align: center;"><?php echo [[=last_group_function_params=]]['quantity']; ?></td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;"  class="change_numTr"><?php echo System::display_number([[=last_group_function_params=]]['total']);?></td>
    </tr>
    <!--/IF:first_pages-->
    <?php  $b_r =''; $i = 0;?> 
    <!--LIST:items-->
    <?php 
    if($b_r != [[=items.name=]])
    {
        $i =0;
    }   
    if($b_r == [[=items.name=]])
    {
        $i++;
    }
    ?>
    <!--/LIST:items-->
    <?php $bar_order = '';?>	
    <!--LIST:items-->
        <?php if($bar_order != [[=items.name=]]){ $bar_order=[[=items.name=]];?> 
            <tr style="background: #f5f5f5; font-weight: bold; font-size: 16px;">
            <td colspan="7" style="text-align: left; margin-left: 20px; font-weight: bold;">
                <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.reservation_room_id=]]));?>">[[.room.]] [[|items.room_name|]] <?php if(!empty([[=items.booking_code=]])){ ?>, [[.Booking_code.]] : [[|items.booking_code|]] <?php } ?></a> <a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.order_id=]],'bar_id'=>[[=items.bar_id=]])); ?>"> - [[.bar_reservation.]]: [[|items.name|]]</a>
                <span class="change_numTr"><?php if([[=items.pay_with_room=]]==1 and [[=items.amount_pay_with_room=]]!=0) echo '('.Portal::language('pay_with_room').' '.System::display_number([[=items.amount_pay_with_room=]]).')' ?></span>
            </td>
            <!--LIST:total_items-->
            
             <?php if([[=items.name=]] == [[=total_items.id=]]){?> 
                <?php $key_id = [[=items.id=]]; ?>
                <td style="text-align: right; font-weight: bold; " class="change_numTr"><?php
				$ta= $ta+([[=total_items.total=]]-[[=total_items.deposit=]]);
				echo System::display_number([[=total_items.total=]]-[[=total_items.deposit=]]);
				
				?></td>   
             <?php } ?>
            <!--/LIST:total_items-->
        	</tr>
    	<?php }?>
            <?php if($key_id==[[=items.id=]]){ ?>
            <tr>
                <td style="text-align: left;">[[|items.product_name|]]</td>
                <td>[[|items.name_1|]]</td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number([[=items.price=]]); ?></td>
                <td>[[|items.quantity|]]</td>
                <td>[[|items.discount_rate|]](%)</td>
                <td>[[|items.order_discount_percent|]](%)</td>
                <td rowspan="<?php echo [[=total_items=]][[[=items.name=]]]['child']; ?>" style="text-align: right;" class="change_numTr"><?php echo System::display_number([[=items.deposit=]]); ?></td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number([[=items.product_total=]]); ?></td>
            </tr>
            <?php }else{ ?>
            <tr>
                <td style="text-align: left;">[[|items.product_name|]]</td>
                <td>[[|items.name_1|]]</td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number([[=items.price=]]); ?></td>
                <td>[[|items.quantity|]]</td>
                <td>[[|items.discount_rate|]](%)</td>
                <td>[[|items.order_discount_percent|]](%)</td>
                
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number([[=items.product_total=]]); ?></td>
            </tr>
            <?php } ?>
        
    <!--/LIST:items-->
    <tr style="font-weight: bold;">
        <td colspan="3" align="right" nowrap class="report_table_column"><strong><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
        <td style="text-align: center;"><?php echo [[=group_function_params=]]['quantity']; ?></td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($ta);
//System::display_number([[=group_function_params=]]['total']);
?></td>
    </tr>
</table>
<script>
jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>