<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('banquet_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.banquet_reservation_list.]]
                    </td>   
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td style="text-align: right; padding-right: 30px;"><input class="w3-btn w3-pink" type="button" onclick="fun_print_group();" value="[[.print_group.]]" /></td><?php }?>           
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
			<td>
				<fieldset>
                <legend class="title">[[.search_options.]]</legend>
                <form method="post" name="ListBanquetReservationForm"> 
                <table>
                    <tr>
                      <td align="right" nowrap="nowrap">[[.guest_name.]]</td>
                      <td nowrap="nowrap"><input name="full_name" type="text" id="full_name" style="width:150px; height: 24px;" class="date-input" /></td>
                        <td align="right" nowrap>[[.from_date.]]</td>
                        <td nowrap>
                            <input name="from_arrival_time" type="text" id="from_arrival_time" onchange="changevalue();" style="width:80px;height: 24px;" class="date-input"/>&nbsp;[[.to_date.]]
                            <input name="to_arrival_time" type="text" id="to_arrival_time" onchange="changefromday();" style="width:80px;height: 24px;" class="date-input"/>
                        </td>
                        <td nowrap><input type="submit" value="[[.search.]]" style="height: 24px;" /></td>
                    </tr>
                    <script>
                        function changevalue(){
                            var myfromdate = $('from_arrival_time').value.split("/");
                            var mytodate = $('to_arrival_time').value.split("/");
                            if(myfromdate[2] > mytodate[2]){
                                $('to_arrival_time').value =$('from_arrival_time').value;
                            }else{
                                if(myfromdate[1] > mytodate[1]){
                                    $('to_arrival_time').value =$('from_arrival_time').value;
                                }else{
                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                        $('to_arrival_time').value =$('from_arrival_time').value;
                                    }
                                }
                            }
                        }
                        function changefromday(){
                            var myfromdate = $('from_arrival_time').value.split("/");
                            var mytodate = $('to_arrival_time').value.split("/");
                            if(myfromdate[2] > mytodate[2]){
                                $('from_arrival_time').value= $('to_arrival_time').value;
                            }else{
                                if(myfromdate[1] > mytodate[1]){
                                    $('from_arrival_time').value = $('to_arrival_time').value;
                                }else{
                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                        $('from_arrival_time').value =$('to_arrival_time').value;
                                    }
                                }
                            }
                        }
                    </script>
                </table>
                </form>
                </fieldset>
				<form name="ListBanquetReservationForm" method="post">
				
                <div style="border:2px solid #FFFFFF;">
    				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
    					<tr class="w3-light-gray" style="text-align: center; height: 26px;">
                            <th><input type="checkbox" name="checkall" id="checkall" /></th>
                            <th>[[.stt.]]</th>
                            <th>[[.time.]]/[[.time_in.]]</th>
                            <th>[[.full_name.]]</th>
    						<th>[[.party_type.]]</th>
    						<th>[[.party_num_people.]]</th>
    						<th>[[.total.]]</th>
                            <th>[[.total_remain_vat.]]</th>
                            <th>[[.user.]]</th>
                            <th>[[.note.]]</th>
                            <th>[[.print_vat.]]</th>
                        </tr>
                        <?php $stt=1; ?>
    					<!--LIST:items-->
    					<tr style="text-align: center;">
                            <td><input type="checkbox" id="items_[[|items.id|]]" class="checkitems" /></td>
                            <td><?php echo $stt++; ?></td>
    						<td><?php echo date('H:i d/m/Y',[[=items.checkin_time=]]); ?></td>
                            <td>[[|items.full_name|]]</td>
							<td>[[|items.party_name|]]</td>
                            <td>
                                <?php 
                                    if([[=items.party_id=]] == 3)
                                        echo [[=items.meeting_num_people=]].' ';
                                    else
                                        echo [[=items.num_people=]].' ';
                                ?>[[.person.]]
                            </td>
                            <td style="text-align: right;"><?php echo System::display_number([[=items.total=]]); ?></td>
                            <td style="text-align: right;"><?php echo System::display_number([[=items.total_remain_vat=]]); ?></td>
							<td>[[|items.user_id|]]</td>
                            <td>[[|items.note|]]</td>
                            <td><input type="button" class="w3-btn w3-pink" onclick="fun_print_group('[[|items.id|]]')" value="[[.print_vat.]]"/></td>
                        </tr>
   					    <!--/LIST:items-->
    				</table>
                </div>
                [[|paging|]]
			     <input name="cmd" type="hidden" value=""/>
            </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    var items = [[|items_js|]];
    jQuery("#checkall").click(function(){
		var check = this.checked;
		jQuery(".checkitems").each(function(){
			this.checked = check;
		});
	});
    function fun_print_group($id=false)
    {
        if(!$id) {
            var list = '';
            for(var x in items) {
                if(document.getElementById("items_"+x).checked==true) {
                    if(list=='')
                        list = items[x]['id'];
                    else
                        list += ','+items[x]['id'];
                }
            }
            if(list=='')
                alert("b?n chua ch?n hóa don!");
            else
                window.location.href='?page=vat_bill&cmd=add&type=BANQUET&invoice_id='+list+'';
        }
        else {
            window.location.href='?page=vat_bill&cmd=add&type=BANQUET&invoice_id='+$id+'';
        }
    }
</script>