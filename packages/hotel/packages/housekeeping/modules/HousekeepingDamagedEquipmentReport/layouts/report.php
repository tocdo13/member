<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" id="export">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  nowrap="nowrap" class="report_table_header" align="left">[[.stt.]]</th>
		<th align="center"  nowrap="nowrap" class="report_table_header">[[.date.]]</th>
		<th class="report_table_header">[[.product_name.]]</th>
		<th class="report_table_header">[[.room_id.]]</th>
		<th class="report_table_header">[[.damaged_type.]]</th>
		<th class="report_table_header">[[.unit_name.]]</th>
		<th class="report_table_header">[[.note.]]</th>
        <th class="report_table_header">[[.quantity.]]</th>
	</tr>
	
	<!--IF:first_page([[=page_no=]]!=1)-->
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="7" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
			<td align="center" class="report_table_column"><?php echo ([[=last_group_function_params=]]['total_quantity']);?></td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">
			[[|items.stt|]]		</td>
		<td align="center" valign="top" nowrap="nowrap" class="report_table_column">[[|items.date|]]</td>
		<td nowrap align="center" class="report_table_column" width="150">
				[[|items.product_name|]]	  </td>
		<td nowrap align="center" class="report_table_column" width="150">
				[[|items.room_name|]]			</td>
		<td nowrap align="center" class="report_table_column" width="100">[[|items.damaged_type|]] </td>
		<td align="center" nowrap class="report_table_column"> [[|items.unit_name|]] </td>
        <td align="center" nowrap class="report_table_column"> [[|items.note|]] </td>
		<td align="center" nowrap class="report_table_column"> [[|items.quantity|]] </td>
	</tr>
	<!--/LIST:items-->
	<!---------TOTAL GROUP FUNCTION----------->				
		<tr class="report_table_column">
			<th colspan="7" align="right" style="font-size:12px;"><?php if([[=real_page_no=]]==[[=real_total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
			<td align="center" nowrap="nowrap" style="font-size:12px;font-weight:bold"><?php echo [[=group_function_params=]]['total_quantity'] ?></td>
		</tr>

</table>
<script>
jQuery('#export_repost').click(function(){
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>