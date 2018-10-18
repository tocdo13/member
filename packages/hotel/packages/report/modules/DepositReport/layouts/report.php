<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" >
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th class="report_table_header" width="30px" rowspan="3">[[.stt.]]</th>
	  <th class="report_table_header" width="50px" rowspan="3">[[.re_code.]]</th>
	  <th class="report_table_header" width="200px" rowspan="3">[[.customer.]]</th>
	  <th class="report_table_header" width="80px" rowspan="3">[[.type.]]</th>
	  <th class="report_table_header" width="80px" rowspan="3">[[.room.]]</th>
	  <th class="report_table_header" width="80px" rowspan="3">[[.date.]]</th>
	  <th class="report_table_header" width="240px" colspan="6">[[.deposit.]]</th>
      <th class="report_table_header" width="100px" rowspan="3">[[.used.]]</th>
      <th class="report_table_header" width="100px" rowspan="3">[[.note.]]</th>
      <th class="report_table_header" width="100px" rowspan="3">[[.user.]]</th>
  </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
      <th colspan="2" class="report_table_header" width="80px">[[.cash.]]</th>
      <th colspan="2"  class="report_table_header" width="80px">[[.credit_card.]]</th>
      <th colspan="2"  class="report_table_header" width="80px">[[.bank_transfer.]]</th>
    </tr>
  <tr valign="middle" bgcolor="#EFEFEF">
        <th  class="report_table_header" width="80px">[[.vnd.]]</th>
        <th  class="report_table_header" width="80px">[[.usd.]]</th>
        <th  class="report_table_header" width="80px">[[.vnd.]]</th>
        <th  class="report_table_header" width="80px">[[.usd.]]</th>
        <th  class="report_table_header" width="80px">[[.vnd.]]</th>
        <th  class="report_table_header" width="80px">[[.usd.]]</th>
  </tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="6" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="right" class="report_table_column change_num"><?php echo System::display_number([[=last_group_function_params=]]['CASH']);?></td>
            <td align="right" class="report_table_column change_num"><?php echo System::display_number([[=last_group_function_params=]]['CREDIT_CARD']);?></td>
            <td align="right" class="report_table_column change_num"><?php echo System::display_number([[=last_group_function_params=]]['BANK']);?></td>
			<td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
		</tr>
	<!--/IF:first_page-->
<?php $stt = 1;?>
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td  valign="top" align="center" class="report_table_column"><?php echo $stt++; ?></td>
        <td  align="center" class="report_table_column" >
            <!--IF:cond( [[=items.type_dps=]]=='ROOM' )-->
                <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.reservation_room_id=]]));?>" target="_blank">[[|items.reservation_id|]]</a>
            <!--ELSE-->
                <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],));?>" target="_blank">[[|items.reservation_id|]]</a>
            <!--/IF:cond-->
        </td>
        <td  align="center" class="report_table_column" >[[|items.customer_name|]]</td>
        <td  align="center" class="report_table_column" >[[|items.type_dps|]]</td>
        <td  align="center" class="report_table_column" >[[|items.room_name|]]</td>
        <td  align="center" class="report_table_column" >[[|items.deposit_date|]]</td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.deposit_cash_vnd=]])){ echo System::display_number([[=items.deposit_cash_vnd=]]);}?></td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.deposit_cash_usd=]])){ echo System::display_number([[=items.deposit_cash_usd=]]);}?></td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.deposit_credit_card_vnd=]])){ echo System::display_number([[=items.deposit_credit_card_vnd=]]);}?></td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.deposit_credit_card_usd=]])){ echo System::display_number([[=items.deposit_credit_card_usd=]]);}?></td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.deposit_bank_vnd=]])){ echo System::display_number([[=items.deposit_bank_vnd=]]);}?></td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.deposit_bank_usd=]])){ echo System::display_number([[=items.deposit_bank_usd=]]);}?></td>
        <td  align="right"  class="report_table_column change_num" ><?php if(isset([[=items.total_used=]])){ echo System::display_number([[=items.total_used=]]);}?></td>
        <td  align="left">[[|items.note|]]</td>
        <td  align="center">[[|items.deposit_user_id|]]</td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="6" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
			<td align="right" class="report_table_column "><strong class="change_num"><?php if(isset([[=group_function_params=]]['CASH_VND'])){echo System::display_number([[=group_function_params=]]['CASH_VND']);}?></strong></td>
            <td align="right" class="report_table_column "><strong class="change_num"><?php if(isset([[=group_function_params=]]['CASH_USD'])){echo System::display_number([[=group_function_params=]]['CASH_USD']);}?></strong></td>
            <td align="right" class="report_table_column "><strong class="change_num"><?php if(isset([[=group_function_params=]]['CREDIT_CARD_VND'])){echo System::display_number([[=group_function_params=]]['CREDIT_CARD_VND']);}?></strong></td>
            <td align="right" class="report_table_column "><strong class="change_num"><?php if(isset([[=group_function_params=]]['CREDIT_CARD_USD'])){echo System::display_number([[=group_function_params=]]['CREDIT_CARD_USD']);}?></strong></td>
            <td align="right" class="report_table_column "><strong class="change_num"><?php if(isset([[=group_function_params=]]['BANK_VND'])){ echo System::display_number([[=group_function_params=]]['BANK_VND']);}?></strong></td>
            <td align="right" class="report_table_column "><strong class="change_num"><?php if(isset([[=group_function_params=]]['BANK_USD'])){ echo System::display_number([[=group_function_params=]]['BANK_USD']);}?></strong></td>
			<td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>

</table>
</div>
</div>
<script>
jQuery("#export_excel").click(function () {
        jQuery('.change_num').each(function(){
            jQuery(this).html(to_numeric(jQuery(this).html()));
        });
        jQuery('#export_excel').remove();
        jQuery('#export').battatech_excelexport({
        containerid: "export"
       , datatype: 'table'
    });
    
})
</script>