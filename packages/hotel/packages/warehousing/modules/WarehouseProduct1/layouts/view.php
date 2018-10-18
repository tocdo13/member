<?php System::set_page_title(HOTEL_NAME);?>
<div class="wh_product-bound">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
        </tr>
    </table>
	<div class="content">
			<table border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
				<tr>
					<td class="label">[[.customer.]]:</td>
					<td>[[|customer_name|]]</td>
				</tr>
                <tr bgcolor="#EFEFEF">
					<td class="label"><strong>[[.name.]]:</strong></td>
					<td><strong>[[|name|]]</strong></td>
				</tr>
				<tr>
                  <td class="label">[[.wh_product_leader.]]:</td>
				  <td>[[|wh_product_leader|]]</td>
			  </tr>
				<tr>
                  <td class="label">[[.expected_room_quantity.]]:</td>
				  <td>[[|room_quantity|]]</td>
			  </tr>
				<tr>
					<td class="label">[[.expected_num_people.]]:</td>
					<td>[[|num_people|]]</td>
				</tr>
				<tr>
                  <td class="label">[[.note.]]:</td>
				  <td>[[|note|]]</td>
			  </tr>
				<tr>
                  <td class="label">[[.arrival_time.]]:</td>
				  <td>[[|arrival_time|]]</td>
			  </tr>
				<tr>
                  <td class="label">[[.departure_time.]]:</td>
				  <td>[[|departure_time|]]</td>
			  </tr>
				<tr>
                  <td class="label"><strong>[[.expected_total_amount.]]:</strong></td>
				  <td><strong>[[|total_amount|]] <?php echo HOTEL_CURRENCY;?></strong></td>
			  </tr>
				<tr>
                  <td class="label"><strong>[[.extra_amount.]]:</strong></td>
				  <td><strong>[[|extra_amount|]] <?php echo HOTEL_CURRENCY;?></strong></td>
			  </tr>
			</table>
	</div>
</div>