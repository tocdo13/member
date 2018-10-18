<style type="text/css">
.simple-layout-middle{width:100%;}
</style>
<script>
	ReservationRoom_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.reservation_id|]]'
<!--/LIST:items-->
	}
</script>
<table cellspacing="0" width="99%">
	<tr valign="top">
		<td nowrap>&nbsp;</td>
		<td width="100%">			
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%"><br />                    
		            <form method="post" name="SearchReservationRoomForm">
                    <table style="width: 100%; text-align: center;">
                            <tr>
                                <td style="padding-bottom: 5px;"><span class="w3-text-indigo" style="text-transform: uppercase; font-size: 14px;">[[.filter_by.]] [[.hotel.]]</span> <select name="portal_id" id="portal_id" style="height: 24px; margin-right: 30px;"></select>
                                [[.from_date.]]:<input name="from_date" type="text" id="from_date" onchange="changevalue();" style="width: 70px; height: 24px; margin-right: 20px;" />
                                [[.to_date.]]:<input name="to_date" type="text" id="to_date" onchange="changefromday();" style="width: 70px; height: 24px;" /></td>
                                
                            </tr>
                        </table>
                    <fieldset style="width: 100%;">
                        <div style="width: 13%; float: left; border-right: 1px solid lightgray;">
                      
                        <script>
                            jQuery("#from_date").datepicker();
                            jQuery("#to_date").datepicker();
                        </script>
                        </div>
                    <div style="width: 100%; float: left; padding-left: 20px;">
                    
					<input type="hidden" name="page_no" value="1" />
                    <!--<input name="status" type="text" id="status"> -->
					<table style="width: 100%;">
                    <form name="ExportForm" method="post">
                        <div>
    						<tr>
        						  <td style="width: 100px;">[[.room.]]</td>        						  
        						  <td style="width: 100px;"><input name="room_id" type="text" id="room_id" style="width:100px; height: 24px;" /></td>
        						  <td style="width: 100px;">[[.customer.]]</td>        						  
        						  <td style="width: 100px;"><input name="customer_name" type="text" id="customer_name" style="width: 100px;height: 24px;" onkeyup="Autocomplete();"/></td><input name="customer_id" type="text" id="customer_id" class="hidden" />
        						  <td style="width: 100px;">[[.guest_name.]]</td>
        						  <td style="width: 100px;"><input name="traveller_name" type="text" id="traveller_name" style="width:100px; height: 24px;" /></td>
        						  <td style="width: 80px;">[[.vnd_rate.]]</td>
        						  <td style="width: 100px;"><select name="price_operator" id="price_operator" style="width:30px;font-weight:bold; height: 24px;"></select><input name="price" type="text" id="price" style="width:70px;height: 24px;" onKeyUp="change_price();" /></td>
                                  <td style="width: 50px;">[[.room_level.]]</td>
                                  <td style="width: 100px;"><select name="room_level" id="room_level" style="width: 100px;height: 24px;"></select></td>
                                  <td style="width: 10px;"></td>
        						  <td style="width: 100px;"><input type="submit" name="search" value="[[.search.]]" class="w3-btn w3-blue" style="width: 100%;" /></td>
                                  <!--Oanh them export file excel -->
                                  <td style="width: 100px;"><input class="w3-btn w3-green" name="export_file_excel" type="button" onclick="exportExcel()" value="[[.export_file_excel.]]" style="width: 100%;" /></td>
                                  <!-- end Oanh-->
                            </tr>
    						<tr>
        						  <td style="width: 100px;">[[.booking_code.]]</td>        						  
        						  <td style="width: 100px;"><input name="booking_code" type="text" id="booking_code" style="width:100px;height: 24px;" /></td>
        						  <td style="width: 100px;">[[.rcode.]]</td>        						  
        						  <td style="width: 100px;"><input name="code" type="text" id="code" style="width:100px;height: 24px;" /></td>
        						  <td style="width: 100px;">[[.nationality.]]</td>        						  
        						  <td style="width: 100px;">
                                    <select name="nationality_id" id="nationality_id" style="width:100px; height: 24px;">
        						    </select>
                                    </td>
        						  <td style="width: 80px;">[[.note.]]</td>
        						  <td colspan="3" style="" ><input name="note" type="text" id="note" style="width:100%; height: 24px;" /></td>
                                  <td style="width: 10px;"></td>
        						  <td colspan="2" style="width: 200px;" ><input type="submit" name="view_printable_list" value="[[.view_printable_list.]]" class="w3-btn w3-gray" style="width: 100%;" /></td>
                                  
                            </tr>
                         </div>
                         </form>
                    </table>
                    </div>
					<input type="submit" style="width:0px;background-color:inherit;border:0 solid white;display:none">
                    </fieldset>
					</form>
                    <form name="ReservationRoomListForm" method="post">
                    <input name="cmd" type="hidden" id="cmd" value="" />
					<div>
    					<table width="100%">
    						<tr>
                            	<td width="100%">
    							<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                                    <tr>
                                        <td class="form-title">[[|title|]] ([[.total.]]:
                                        <?php if(url::get('status') =='CHECKOUT'){ echo [[=total_checkout=]];}else{ echo [[=total=]]; }?>)</td>
                                    </tr>
                                </table>
                                </td>
    					   </tr>
                        </table>
					</div>
					<table id="myTable" class="tablesorter" width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="table-header" style="text-transform: uppercase;">
							<th nowrap align="center" style="width: 150px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='traveller_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.guest_name.]]								</a>							</th>
							<th align="center" style="width: 150px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room_number.]]</a></th>
                            <th nowrap align="center" style="width: 80px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.price' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.price'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.price') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.price.]] <span style="font-size:10px;">(<?php echo HOTEL_CURRENCY;?>)</span></a>
							</th><th nowrap align="center" style="width: 80px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.adult' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.adult'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.adult') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.num_people.]]								</a>
							</th><th nowrap align="center" style="width: 100px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.arrival_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.arrival_time'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.arrival_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.arrival_time.]]								</a>
							</th><th nowrap align="center" style="width: 100px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.departure_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.departure_time'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='reservation_room.departure_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.departure_time.]]								</a>
							</th><th nowrap align="center" style="width: 100px;">[[.status.]]</th>
							<th align="center" style="width: 100px;"> [[.create_user.]] </th>
							<th align="center" style="width: 100px;"> [[.create_time.]] </th>
							<th align="center" style="width: 100px;">[[.tk_book.]]</th>
							<th align="center" style="width: 100px;"> [[.lastest_edited_user.]] </th>
							<th align="center" style="width: 100px;">[[.lastest_edited_time.]]</th>
                            <?php //if(User::can_admin(false,ANY_CATEGORY)){ ?>
                            	<!--<th align="center" style="width: 100px;">[[.tk_checkin.]]</th>
                            <?php //}else{?>
							<th align="center" style="width: 100px;">[[.checked_in_user.]]</th> -->
                            <?php //}?>
							<?php if(User::can_add(false,ANY_CATEGORY))
							{
							?><th>[[.edit.]]</th>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th>[[.delete.]]</th>
							<?php
							}
							?></tr>
						<?php $temp = '';?>
                        <!--LIST:items-->
                        <?php if($temp!=[[=items.reservation_id=]]){$temp = [[=items.reservation_id=]];?>
                        <tr>
	                        <td colspan="13" class="category-group">
							[[[.rcode.]]:  [[|items.reservation_id|]]] <?php if([[=items.mice_reservation_id=]]!=''){ echo '| <span style="color: red;">[MICE+'.[[=items.mice_reservation_id=]].']</span>'; } ?> | <span style="color:#0066FF;">[[[.booking_code.]]: [[|items.booking_code|]]]</span> | [[.tour.]]: [[|items.tour_name|]] | [[.customer.]]: [[|items.customer_name|]]
							<!--IF:cond([[=items.group_note=]])-->
								<div class="note" style="text-transform:none;"><span class="note">*[[.note.]]: [[|items.group_note|]]</span></div>
								<!--/IF:cond-->
							</td>
                            <td class="reservation-list-item" style="background-color: #FF9; border-bottom: 1px solid #FC0" nowrap width="15">
							<?php
							if(
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and [[=items.status=]] == 'BOOKED')
							)
							{
							?>&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>[[=items.reservation_id=]])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
							<?php
							}
							?></td>
                        </tr>
                        <?php }?>
						<tr valign="top" id="ReservationRoom_tr_[[|items.reservation_id|]]" <?php echo 'class="'.(([[=items.i=]]%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150" align="left">
                            	[[|items.i|]] / [[.invoice_number.]]: [[|items.id|]]
								<!--LIST:items.travellers-->
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>[[=items.travellers.id=]]));?>">[[|items.travellers.full_name|]]</a></div>
								<!--/LIST:items.travellers--></td>
							<td class="reservation-list-item"align="left">
								<div style="float:left;width:150px;font-size:11px;"><strong>[[|items.room_id|]]</strong> - [[.room_level.]] <strong>[[|items.room_level|]]</strong><br />
							  ([[|items.portal_name|]])</div>
							  <!--IF:cond([[=items.note=]])-->
								<div class="note"><span class="note">*[[.note.]]: [[|items.note|]]</span></div>
								<!--/IF:cond-->
							  </td>
							<td class="reservation-list-item" nowrap align="right">
								[[|items.price|]]</td>
                            <td class="reservation-list-item" nowrap>
								[[|items.adult|]]<i class="fa fa-male" style="font-size: 16px;"></i><!--IF:cond([[=items.child=]])-->+ [[|items.child|]]<i class="fa fa-child" style="font-size: 16px;"></i><!--/IF:cond-->
						  </td>
							<td class="reservation-list-item" nowrap align="center" title="[[|items.arrival_time|]] [[|items.time_in|]]">
								[[|items.time_in|]]</td>
							<td  class="reservation-list-item"nowrap align="center" title="[[|items.departure_time|]] [[|items.time_out|]]">
							  [[|items.time_out|]] <!--IF:vd_cond([[=items.verify_dayuse=]])--><span style="font-weight:bold;">[[|items.verify_dayuse_label|]]</span><!--/IF:vd_cond--></td>
							<td class="reservation-list-item" nowrap align="center">
								[[|items.status|]]							</td>
							<td class="reservation-list-item">[[|items.user_id|]]</td>
							<td class="reservation-list-item"><?php echo date('d/m/Y H:i\'',[[=items.time=]]);?></td>
							<td align="left" nowrap="nowrap" class="reservation-list-item"> [[|items.booked_user_id|]] </td>
							<td class="reservation-list-item">[[|items.lastest_edited_user_id|]]</td>
							<td class="reservation-list-item"><?php echo [[=items.lastest_edited_time=]]?date('d/m/Y H:i\'',[[=items.lastest_edited_time=]]):'';?></td>
                            <?php //if(User::can_admin(false,ANY_CATEGORY))
							{
							?><!--<td  class="reservation-list-item" valign="middle" style="text-align: center;">
                            <a href="#" onclick="openWindowUrl('http://<?php //echo $_SERVER['HTTP_HOST'];?>/<?php// echo Url::$root;?>form.php?block_id=428&cmd=deposit&id=[[|items.id|]]&type=RESERVATION&customer_id=[[|items.customer_id|]]&portal_id=<?php echo PORTAL_ID;?>',Array([[|items.id|]],'[[.deposit.]]','80','210','1000','500'));" title="[[.Deposit.]]" ><i class="fa fa-money w3-text-green" style="font-size: 30px;"></i> </a></td>-->
							<?php }?>
							<td class="reservation-list-item" nowrap width="20" style="text-align: center;">
							<?php
							if( User::can_edit(false,ANY_CATEGORY)or (USER::can_add(false,ANY_CATEGORY) and ([[=items.status=]] == 'BOOKED' ))or (User::can_edit(false,ANY_CATEGORY) and ([[=items.status=]] == 'BOOKED' or [[=items.status=]] == 'IN'))){?>
								&nbsp;<a href="<?php echo Url::build_current(array('layout'=>'list','customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>[[=items.reservation_id=]])); ?>&r_r_id=[[|items.id|]]" title="[[.Edit.]]"><i class="fa fa-edit w3-text-orange" style="font-size: 18px; margin-top: 5px;"></i></a>
							<?php
							}
							?></td>
							<td class="reservation-list-item" nowrap width="15">
							<?php
							if(
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and [[=items.status=]] == 'BOOKED')
							)
							{
							?>&nbsp;<!--<a href="<?php //echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>[[=items.reservation_id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" border="0"></a>-->
							<?php
							}
							?></td>
						</tr>
						<?php if([[=reservation_arr=]][[[=items.reservation_id=]]] == [[=items.cc=]]){?>
						<tr bgcolor="#EFEFEF">
						  <td align="right" class="reservation-list-item"><strong>[[.total_room.]]</strong></td>
						  <td colspan="14" align="left" class="reservation-list-item"><strong><?php echo [[=reservation_arr=]][[[=items.reservation_id=]]];?></strong></td>
					    </tr>
					  	<?php }?>
						<!--/LIST:items-->
				  </table>
                  </form>
				</td>
			</tr>
			</table>
			[[|paging|]]
    </td>
  </tr>
</table>
<script type="text/javascript">
    function change_price()
    {
        jQuery('#price').ForceNumericOnly().FormatNumber();
    }
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
//luu nguyen giap add closed button
function close_window_fun(){
    location.reload();
    jQuery(".window-container").fadeOut();
   // console.log('aaaaa');
}

//oanh add

function exportExcel(){
    jQuery("#myTable").battatech_excelexport({
        containerid: "myTable"
        ,datatype: 'table'
    });
} 
//end oanh
function Autocomplete()
{
    jQuery("#customer_name").autocomplete({
        url: 'get_customer_search_fast.php?customer=1',
        onItemSelect: function(item){
            document.getElementById('customer_id').value = item.data[0];
        }
    })
}
</script>