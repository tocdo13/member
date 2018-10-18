<?php System::set_page_title(HOTEL_NAME);?>
<div class="tour-bound">
<form name="CopyTravellersForm" method="post" onsubmit="return checkInput();">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="90%" class="form-title">[[|title|]]</td>
			<td width="1%"><input name="copy" type="submit" id="copy" value="[[.copy.]]" class="button-medium-save" /></td>
			<td width="1%"><input type="button" value="[[.back.]]" class="button-medium-back" onclick="window.location = '<?php echo Url::build_current();?>'" /></td>
        </tr>
    </table>
	<div class="content">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr valign="top">
			<td width="300">
				<h3>[[.group_info.]]</h3>
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
					  <td class="label">[[.tour_leader.]]:</td>
					  <td>[[|tour_leader|]]</td>
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
				</table>
			</td>
			<td>
				<h3>[[.copy_travellers.]]</h3>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td>
					  <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
                          <tr class="table-header">
                            <th width="1%"><input type="checkbox" id="all_item_check_box" /></th>
                            <th width="1%" title="[[.check_all.]]">[[.no.]]</th>
                            <th align="left" nowrap="nowrap" >[[.full_name.]]</th>
                            <th align="left" nowrap="nowrap" >[[.is_vietnamese.]]</th>
                            <th align="left" nowrap="nowrap" >[[.nationality.]]</th>
                            <th align="left" nowrap="nowrap" >[[.passport.]]</th>
                            <th align="left" nowrap="nowrap" >[[.room.]]</th>
                            <th align="left" nowrap="nowrap" >[[.date_in.]]</th>
                            <th align="left" nowrap="nowrap" >[[.date_out.]]</th>
                          </tr>
                          <?php $i=1;?>
						  <!--LIST:items-->
                          <tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}?> id="Traveller_tr_[[|items.id|]]">
                            <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]" /></td>
                            <td align="right" nowrap="nowrap">[[|items.i|]]</td>
                            <td align="left" nowrap="nowrap"> [[|items.first_name|]] [[|items.last_name|]] [[[.G.]]:[[|items.gender|]] / [[.DOB.]]: [[|items.birth_date|]]]</td>
                            <td align="center" nowrap="nowrap"><span style="cursor:text">[[|items.is_vn|]]</span></td>
                            <td align="left"><span style="cursor:text">[[|items.nationality_code|]]</span></td>
                            <td align="left" nowrap="nowrap" style="cursor:text"> [[|items.passport|]]</td>
                            <td align="left" nowrap="nowrap" style="cursor:text"> [[|items.room_name|]]</td>
                            <td align="left" nowrap="nowrap"> [[|items.time_in|]]</td>
                            <td nowrap="nowrap" align="left"> [[|items.time_out|]]</td>
                            <!--/LIST:items-->
                        </table></td>
				</tr>
				</table>
				<h3>[[.to_tour.]]</h3>
				<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td>[[.tour.]]: <input name="tour_name" type="text" id="tour_name" style="width:215px;" readonly="" class="readonly">
						  <input name="tour_id" type="text" id="tour_id" class="hidden">
						  <a href="#" onclick="window.open('?page=tour&amp;action=select_tour_to_copy&amp;source_tour_id=<?php echo Url::iget('id');?>','tour')"><img src="skins/default/images/cmd_Tim.gif" /></a> <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('tour_name').value='';$('tour_id').value=0;" style="cursor:hand;"></td>
				  <td  colspan="3">[[.company.]]: <input name="customer_name" type="text" id="customer_name" style="width:215px;"  readonly="" class="readonly">
						  <input name="customer_id" type="text" id="customer_id" class="hidden"></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
</form>	
</div>
<script>
jQuery("#all_item_check_box").click(function (){
	var check  = this.checked;
	jQuery(".item-check-box").each(function(){
		this.checked = check;
	});
});
function checkInput(){
	var passedTraveller = false;
	jQuery(".item-check-box").each(function (){
		if(jQuery(this).attr('checked') == true){
			passedTraveller = true;		
		}
	});
	if(passedTravller == false){
		alert('[[.you_have_to_select_at_least_one_traveller.]]');
	}else{
		return false;
	}
	if($('tour_id').value == '' || $('tour_id').value == 0){
		alert('[[.you_have_to_select_tour_to_copy.]]');
		return false;
	}else{
		if(passedTravller == true){
			return true;
		}
	}
}
</script>