<!---------REPORT----------->	

<p style="margin-left: 15px;">[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></p>
<table cellpadding="5" cellspacing="0" width="98%" border="1" bordercolor="#CCCCCC" style="text-align: center; margin: 5px auto;">
    <tr style="font-weight: bold; background: #eeeeee;">
        <th>[[.stt.]]</th>
        <th>[[.recode.]]</th>
        <th>[[.date.]]</th>
        <th>[[.room.]]</th>
        <th>[[.guest_name.]]</th>
        <th>[[.invoice_code.]]</th>
        <th>[[.total_before_tax.]]</th>
        <th>[[.service_charge.]]</th>
        <th>[[.tax.]]</th>
        <th>[[.total.]]</th>
        <th>[[.user_id.]]</th>
        <th>[[.code_hand.]]</th>
    </tr>
    <?php $i=0 ?>
    <!--LIST:items-->
    <tr>
        <td><?php echo ++$i ?></td>
        <td><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.res_id|]]">[[|items.res_id|]]</a></td>
        <td>[[|items.date|]]</td>
        <td>[[|items.room_name|]]</td>
        <td style="text-align: left;">[[|items.traveller_name|]]</td>
        <td><a target="_blank" href="?page=<?php if(Url::get('type')==1) echo 'minibar_invoice'; else echo 'laundry_invoice'; ?>&cmd=detail&portal=default&id=[[|items.id|]]">[[|items.position|]]</a></td>
        <td style="text-align: right;"><?php echo System::display_number(round([[=items.total_before_tax=]])); ?></td>
        <td style="text-align: right;"><?php echo System::display_number(round([[=items.fee_rate=]])); ?></td>
        <td style="text-align: right;"><?php echo System::display_number(round([[=items.tax_rate=]])); ?></td>
        <td style="text-align: right;"><?php echo System::display_number(round([[=items.total=]])); ?></td>
        <td>[[|items.user_id|]]</td>
        <td>[[|items.housekeeping_invoice_code|]]</td>
    </tr>
    <!--/LIST:items-->
    <tr style="text-align: right; font-weight: bold;background: #eeeeee;">
        <td colspan="6">[[.total.]]:</td>
        <td><?php echo System::display_number(round([[=summary_total_before_tax=]])); ?></td>
        <td><?php echo System::display_number(round([[=summary_fee_rate=]])); ?></td>
        <td><?php echo System::display_number(round([[=summary_tax_rate=]])); ?></td>
        <td><?php echo System::display_number(round([[=summary_total=]])); ?></td>
        <td colspan="2"></td>
    </tr>
</table>
			