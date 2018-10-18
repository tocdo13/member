<!---------REPORT----------->	
<div align="right"><em>[[.price_unit.]]: <?php echo HOTEL_CURRENCY;?></em></div>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="1%" align="center" nowrap="nowrap" class="report_table_header">[[.no.]]</th>
		<th class="report_table_header" align="left" nowrap="nowrap" >[[.Trip_code.]]</th>
		<th width="5%" align="center" nowrap="nowrap" class="report_table_header">[[.group_size.]]</th>
		<th width="20%" nowrap="nowrap" class="report_table_header">[[.room_stay.]]</th>
		<th align="center" nowrap="nowrap" class="report_table_header">[[.no_of_room.]]</th>
		<th align="center" nowrap="nowrap" class="report_table_header">[[.no_for_nite.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.check_in_date.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.check_out_date.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.total.]]</th>
        <th class="report_table_header">[[.note.]]</th>
    </tr>
	<!--LIST:items-->
	<tr bgcolor="white">
		<td width="1%" align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
		<td nowrap align="left" class="report_table_column" width="150">
		[[|items.booking_code|]]</td>
		<td align="center" nowrap="nowrap" class="report_table_column">[[|items.num_people|]]</td>
		<td align="right" class="report_table_column">[[|items.rooms_stay|]]</td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column">[[|items.num_room|]]</td>
		<td width="5%" align="center" nowrap="nowrap" class="report_table_column">[[|items.night|]]</td>
		<td width="10%" nowrap align="right" class="report_table_column">[[|items.arrival_time|]]</td>
		<td width="10%" nowrap align="right" class="report_table_column">[[|items.departure_time|]]</td>
		<td align="right" nowrap class="report_table_column">[[|items.total|]]</td>
        <td class="report_table_column">&nbsp;</td>
	</tr>
    <input name="old_payment_[[|items.id|]]" type="hidden" id="old_payment_[[|items.id|]]" value="[[|items.paied|]]" />
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
	<!--/IF:first_page-->	
	<tr bgcolor="white">
	  <td align="center" valign="middle" class="report_table_column">&nbsp;</td>
	  <td colspan="7" align="right" nowrap class="report_table_column">[[.total_minibar.]]</td>
	  <td align="right" nowrap class="report_table_column" id="td_total_amount" style="font-weight:bold;">[[|total_minibar|]]</td>
	  <td class="report_table_column">&nbsp;</td>
  </tr>
	<tr bgcolor="white">
	  <td align="center" valign="middle" class="report_table_column">&nbsp;</td>
	  <td colspan="7" align="right" nowrap class="report_table_column">[[.total_laundry.]]</td>
	  <td align="right" nowrap class="report_table_column" id="td_total_amount" style="font-weight:bold;">[[|total_laundry|]]</td>
	  <td class="report_table_column">&nbsp;</td>
  </tr>
	<tr bgcolor="white">
	  <td align="center" valign="middle" class="report_table_column">&nbsp;</td>
	  <td colspan="7" align="right" nowrap class="report_table_column">[[.total_bar.]]</td>
	  <td align="right" nowrap class="report_table_column" id="td_total_amount" style="font-weight:bold;">[[|total_bar|]]</td>
	  <td class="report_table_column">&nbsp;</td>
  </tr>
	<tr bgcolor="white">
  <td align="center" valign="middle" class="report_table_column">&nbsp;</td>
	  <td colspan="7" align="right" nowrap class="report_table_column"><strong>[[.Total.]]</strong></td>
	  <td align="right" nowrap class="report_table_column" id="td_total_amount" style="font-weight:bold;">[[|total_amount|]]</td>
      <td class="report_table_column">&nbsp;</td>
</tr>
</table>
