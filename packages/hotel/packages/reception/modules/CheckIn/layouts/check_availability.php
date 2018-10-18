<form name="CheckAvailabilityForm" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bound">
    <tr height="40">
	    <td width="90%" class="form-title">[[.reservation.]]</td>
        <td width="1%"><input name="book" type="submit" value="[[.book_now.]]" class="button-medium-add"></td>
    </tr>
</table>
<div>
<fieldset>
<legend class="legend-title">[[.available_rooms.]]</legend>
[[.from.]] <input name="arrival_time" type="text" id="arrival_time" class="date-input">
[[.to.]] <input name="departure_time" type="text" id="departure_time" class="date-input">
<input name="search" type="submit" value="[[.search.]]">
</fieldset>
</div><br />
<div>
<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
  <tr class="table-header">
    <td>[[.room_level.]]</td>
    <td><!--LIST:days--><span class="check-availability-day header">[[|days.value|]]</span><!--/LIST:days--></td>
  </tr>
  <tr>
    <td width="150" nowrap="nowrap">
		<!--LIST:room_levels--><div class="check-availability-item">&nbsp;<!--IF:cond([[=room_levels.min_room_quantity=]]>0)--><input name="room_quantity_[[|room_levels.id|]]" type="text" id="room_quantity_[[|room_levels.id|]]" style="width:15px;" max="[[|room_levels.min_room_quantity|]]" class="room-quantity-by-date"><!--/IF:cond--><span onclick="selectAllLevel('[[|room_levels.id|]]','[[|room_levels.min_room_quantity|]]');">[[|room_levels.name|]]</span></div><!--/LIST:room_levels--></td>
	<td nowrap="nowrap">
		<!--LIST:room_levels-->
			<div class="check-availability-item"><!--LIST:room_levels.day_items--><span class="check-availability-day">[[|room_levels.day_items.room_quantity|]]</span><!--/LIST:room_levels.day_items--></div>
		<!--/LIST:room_levels-->
	</td>
  </tr>  
</table>
</div>
</form>
<script>
	jQuery(".date-input").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	function selectAllLevel(levelId,minRoomQuantity){
		jQuery(".room-quantity-by-date").each(function(){
			idString = this.id;
			var re =  new RegExp("room_quantity_"+levelId,"g");
			if(idString.match(re)){
				if(jQuery(this).val()==''){
					jQuery(this).val(minRoomQuantity);
				}else{
					jQuery(this).val('');
				}
			}
		});
	}
</script>