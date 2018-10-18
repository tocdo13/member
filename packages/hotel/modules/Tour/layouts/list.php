<style>
.cancelled{
	color:#999999 !important;
	font-size:14px;
	font-weight:bold;	
	text-decoration:line-through;
}
#input_cancel{
	color:#F00;	
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="module-wrapper">
<form name="ListTourForm" method="post">
	<div class="module-header">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" class="table-bound">
		<tr height="40">
        	<td width="90%" class="form-title">[[|title|]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right" nowrap="nowrap"><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add">[[.Add.]]</a></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%" align="right" nowrap="nowrap"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListTourForm.cmd.value='delete';ListTourForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table>
    </div>      
	<div class="module-body">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			<table border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td>[[.tour_name.]]: <input name="tour_name" type="text" id="tour_name" style="width:80px;" /></td>
				<td>[[.company.]]: <input name="customer_name" type="text" id="customer_name" style="width:80px;" /></td>
				<td>[[.booking_code.]]: <input name="booking_code" type="text" id="booking_code" style="width:80px;" /></td>
				<td>[[.arrival_time.]]: <input name="arrival_time" type="text" id="arrival_time" class="date-input" onchange="changevalue();" AUTOCOMPLETE=OFF /></td>
				<td>[[.departure_time.]]: <input name="departure_time" type="text" id="departure_time" class="date-input" onchange="changefromday();" AUTOCOMPLETE=OFF /></td>
				<td align="right"><input name="search" type="submit" value="OK" /></td>
			  </tr>
			</table>
		</fieldset><br />
		<table border="1" cellspacing="0" cellpadding="2" bordercolor="#C6E2FF">
			<tr class="table-header">
			  <th width="1%"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="1%">[[.order_number.]]</th>
              <th width="150" align="left"><a href="<?php echo Url::build_current(array('tour_name','customer_name','booking_code','arrival_time','departure_time','order_by'=>'tour.name','order_by_dir'=>((Url::get('order_by_dir')=='ASC')?'DESC':'ASC')));?>" title="[[.click_here_to_order.]]">[[.name.]]</a></th>
			  <th width="60" align="center">[[.room_quantity.]]</th>
			  <th width="60" align="center">[[.num_people.]]([[.adult.]])</th>
               <th width="60" align="center">[[.child.]]</th>
			  <th width="70" nowrap="nowrap" align="left"><a href="<?php echo Url::build_current(array('tour_name','customer_name','booking_code','arrival_time','departure_time','order_by'=>'tour.arrival_time','order_by_dir'=>((Url::get('order_by_dir')=='ASC')?'DESC':'ASC')));?>" title="[[.click_here_to_order.]]">[[.arrival_time.]]</a></th>
			  <th width="70" nowrap="nowrap" align="left"><a href="<?php echo Url::build_current(array('tour_name','customer_name','booking_code','arrival_time','departure_time','order_by'=>'tour.departure_time','order_by_dir'=>((Url::get('order_by_dir')=='ASC')?'DESC':'ASC')));?>" title="[[.click_here_to_order.]]">[[.departure_time.]]</a></th>
			  <th width="120" align="left">[[.tour_leader.]]</th>
			  <th width="100" align="left">[[.note.]]</th>
			  <th width="100" align="center">[[.create_user.]]/[[.modified_user.]]</th>
			  <th width="15">&nbsp;</th>
			  <th width="15">&nbsp;</th>
			  <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		      <th width="1%">&nbsp;</th>
		  </tr>
		  <!--LIST:items-->
			<tr <?php if([[=items.i=]]%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}?> >
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
			  <td>[[|items.i|]]</td>
				<td>
                	<div style="float:left;width:200px;">
						<?php if([[=items.selected=]]){?><a class="select-item" href="#" onclick="pick_value([[|items.id|]]);window.close();">[[.select.]]</a> <?php }?><img src="packages/core/skins/default/images/tour.png" height="15" align="texttop"> <?php echo [[=items.reservation_id=]]?'<a target="_blank" id="name_'.[[=items.id=]].'" class="'.(([[=items.cancelled=]] == 1)?"title":"cancelled").'" href="'.Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]])).'">'.[[=items.name=]].'</a>':'<span id="name_'.[[=items.id=]].'" class="'.(([[=items.cancelled=]] == 1)?"title":"cancelled").'">'.[[=items.name=]].'</span>';?><br /><!--IF:cancelled_cond([[=items.cancelled=]] == 0)--><span name="input_cancel" id="input_cancel">([[.cancelled_all_rooms.]])</span><br /><!--/IF:cancelled_cond-->
						[[.company_name.]]: <span id="customer_name_[[|items.id|]]">[[|items.company_name|]]</span><input type="hidden" id="customer_id_[[|items.id|]]" value="[[|items.company_id|]]"><br />
						[[.booking_code.]]: [[|items.booking_code|]]
					</div></td>
			    <td align="center" id="room_quantity_[[|items.id|]]"><!--IF:cond1([[=items.room_quantity=]] != 0)-->[[|items.room_quantity|]]<!--/IF:cond1--></td>
		      <td align="center">[[|items.adult|]]</td>
              <td align="center">[[|items.child|]] </td>
			    <td id="arrival_time_[[|items.id|]]">[[|items.arrival_time|]]</td>
                <td id="departure_time_[[|items.id|]]">[[|items.departure_time|]]</td>
                <td>[[|items.tour_leader|]]</td>
               <td>[[|items.note|]]</td>
			   <td align="center">[[|items.user_id|]] / [[|items.last_modified_user_id|]]</td>
			   <td><!--IF:cond([[=items.reservation_id=]] and [[=items.have_traveller=]])--><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'rooming_list','id'=>[[=items.reservation_id=]]));?>" title="[[.view_rooming_list.]]"><img src="packages/core/skins/default/images/buttons/note.gif"></a><!--/IF:cond--></td>
			   	<td><!--IF:cond([[=items.reservation_id=]])--><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>" title="[[.view_detail.]]"><img src="packages/core/skins/default/images/buttons/form.png" width="16" height="16" /></a><!--/IF:cond--></td>
				<td><!--IF:cond([[=items.reservation_id=]])--><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'copy','id'=>[[=items.id=]]));?>" title="[[.copy_travellers_from_this_group.]]"><img src="packages/core/skins/default/images/buttons/copy.png" /></a><!--/IF:cond--></td>
		     	<td><a href="<?php echo Url::build_current(array('action','cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
			    <td><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a><?php }?></td>
			</tr>
		  <!--/LIST:items-->			
	  </table>
<br />
		<div class="paging">[[|paging|]]</div>
	</div>
	<input  name="cmd" type="hidden" value="">
	<input  name="tour_list" type="hidden" id="tour_list">
</form>	
</div>
<script type="text/javascript">
	jQuery("#arrival_time").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery("#departure_time").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery("#delete_button").click(function (){
		ListTourForm.cmd.value = 'delete';
		ListTourForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('[[.are_you_sure.]]')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.AddReservationForm){
				checkAvailability = false;
				var form = window.opener.document.AddReservationForm;
			} else if(window.opener.document.CheckAvailabilityForm){
				checkAvailability = true;
				var form = window.opener.document.CheckAvailabilityForm;
			} else if(window.opener.document.CopyTravellersForm){
				checkAvailability = false;
				var form = window.opener.document.CopyTravellersForm;
			}
			form.tour_name.value = $('name_'+id).innerHTML;
			form.tour_id.value = id;		
			form.customer_name.value = $('customer_name_'+id).innerHTML;
			form.customer_id.value = $('customer_id_'+id).value;
			if(window.opener.jQuery('#customer_name').val()){
				var inputCount = window.opener.input_count;
				for(var i=101;i<=inputCount;i++){
					if(window.opener.jQuery('#price_'+i) && window.opener.jQuery('#old_status_'+i) && window.opener.jQuery('#old_status_'+i).val()==''){
						//window.opener.jQuery('#price_'+i).val(0);
					}
				}
				window.opener.jQuery('.price').attr('readonly',true);
				window.opener.jQuery('.price').addClass('readonly');			
			}
			if(window.opener.jQuery('#arrival_time')){
				window.opener.jQuery('#arrival_time').val($('arrival_time_'+id).innerHTML);	
			}
			if(window.opener.jQuery('#departure_time')){
				window.opener.jQuery('#departure_time').val($('departure_time_'+id).innerHTML);	
			}
			if(window.opener.jQuery('.room-quantity-by-date')){
				window.opener.jQuery('.room-quantity-by-date').each(function (){
					if(jQuery(this).attr('lang') == 'Premium'){
						jQuery(this).val($('room_quantity_'+id).innerHTML);
					}
				});
			}
		}
	}
    function changevalue(){
        var myfromdate = $('arrival_time').value.split("/");
        var mytodate = $('departure_time').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('departure_time').value =$('arrival_time').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('departure_time').value =$('arrival_time').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('departure_time').value =$('arrival_time').value;
                }
            }
        }
    }
    function changefromday(){
        var myfromdate = $('arrival_time').value.split("/");
        var mytodate = $('departure_time').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('arrival_time').value= $('departure_time').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('arrival_time').value = $('departure_time').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('arrival_time').value =$('departure_time').value;
                }
            }
        }
    }
</script>