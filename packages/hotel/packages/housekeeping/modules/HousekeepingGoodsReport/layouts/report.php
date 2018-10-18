<!---------REPORT----------->
<table cellpadding="0" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" width="100%">
<tr>
	<td valign="top">
		<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#C3C3C3">
			<tr valign="middle" bgcolor="#EFEFEF">
				<th nowrap="nowrap" class="report_table_header" align="center" width="5%">[[.stt.]]</th>
				<!--IF:product(!URL::get('product_id'))-->
				<th class="report_table_header">[[.product_code.]]</th>
				<th class="report_table_header">[[.product_name.]]</th>
					<!--<?php $cols=6;?>-->
				<!--ELSE-->
				<th class="report_table_header">[[.room.]]</th>
				<th class="report_table_header">[[.product_name.]]</th>
					<!--<?php $cols=4;?>-->
				<!--/IF:product-->
				<th class="report_table_header">[[.unit.]]</th>
				<th class="report_table_header">[[.quantity.]]</th>
			</tr>
			<!--LIST:items-->
			<tr bgcolor="white">
				<td nowrap="nowrap" valign="top" align="center" class="report_table_column">
					[[|items.stt|]]
				</td>
				<!--IF:product(!URL::get('product_id'))-->
				<td nowrap align="center" class="report_table_column" width="60px">
					[[|items.product_code|]]
				</td>
				<td nowrap align="left" class="report_table_column" width="200px">
					[[|items.product_name|]]
				</td>
				<!--ELSE-->
				<td nowrap align="center" class="report_table_column" width="50px">
					[[|items.room_name|]]
				</td>
				<td nowrap align="left" class="report_table_column" width="200px">
					[[|items.product_name|]]
				</td>
				<!--/IF:product-->
				<td nowrap align="center" class="report_table_column" width="50px">
					[[|items.unit|]]
				</td>
				<td nowrap align="right" class="report_table_column" width="60px">
					[[|items.quantity|]]
				</td>
			</tr>
			<!--/LIST:items-->
		</table>
	</td>
</tr>
</table>