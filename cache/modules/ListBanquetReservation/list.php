<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('banquet_reservation_list'));?>
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('banquet_reservation_list');?>
                    </td>   
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td style="text-align: right; padding-right: 30px;"><input class="w3-btn w3-pink" type="button" onclick="fun_print_group();" value="<?php echo Portal::language('print_group');?>" /></td><?php }?>           
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
                <legend class="title"><?php echo Portal::language('search_options');?></legend>
                <form method="post" name="ListBanquetReservationForm"> 
                <table>
                    <tr>
                      <td align="right" nowrap="nowrap"><?php echo Portal::language('guest_name');?></td>
                      <td nowrap="nowrap"><input  name="full_name" id="full_name" style="width:150px; height: 24px;" class="date-input" / type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                        <td align="right" nowrap><?php echo Portal::language('from_date');?></td>
                        <td nowrap>
                            <input  name="from_arrival_time" id="from_arrival_time" onchange="changevalue();" style="width:80px;height: 24px;" class="date-input"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_arrival_time'));?>">&nbsp;<?php echo Portal::language('to_date');?>
                            <input  name="to_arrival_time" id="to_arrival_time" onchange="changefromday();" style="width:80px;height: 24px;" class="date-input"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_arrival_time'));?>">
                        </td>
                        <td nowrap><input type="submit" value="<?php echo Portal::language('search');?>" style="height: 24px;" /></td>
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
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                </fieldset>
				<form name="ListBanquetReservationForm" method="post">
				
                <div style="border:2px solid #FFFFFF;">
    				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
    					<tr class="w3-light-gray" style="text-align: center; height: 26px;">
                            <th><input type="checkbox" name="checkall" id="checkall" /></th>
                            <th><?php echo Portal::language('stt');?></th>
                            <th><?php echo Portal::language('time');?>/<?php echo Portal::language('time_in');?></th>
                            <th><?php echo Portal::language('full_name');?></th>
    						<th><?php echo Portal::language('party_type');?></th>
    						<th><?php echo Portal::language('party_num_people');?></th>
    						<th><?php echo Portal::language('total');?></th>
                            <th><?php echo Portal::language('total_remain_vat');?></th>
                            <th><?php echo Portal::language('user');?></th>
                            <th><?php echo Portal::language('note');?></th>
                            <th><?php echo Portal::language('print_vat');?></th>
                        </tr>
                        <?php $stt=1; ?>
    					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    					<tr style="text-align: center;">
                            <td><input type="checkbox" id="items_<?php echo $this->map['items']['current']['id'];?>" class="checkitems" /></td>
                            <td><?php echo $stt++; ?></td>
    						<td><?php echo date('H:i d/m/Y',$this->map['items']['current']['checkin_time']); ?></td>
                            <td><?php echo $this->map['items']['current']['full_name'];?></td>
							<td><?php echo $this->map['items']['current']['party_name'];?></td>
                            <td>
                                <?php 
                                    if($this->map['items']['current']['party_id'] == 3)
                                        echo $this->map['items']['current']['meeting_num_people'].' ';
                                    else
                                        echo $this->map['items']['current']['num_people'].' ';
                                ?><?php echo Portal::language('person');?>
                            </td>
                            <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total']); ?></td>
                            <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total_remain_vat']); ?></td>
							<td><?php echo $this->map['items']['current']['user_id'];?></td>
                            <td><?php echo $this->map['items']['current']['note'];?></td>
                            <td><input type="button" class="w3-btn w3-pink" onclick="fun_print_group('<?php echo $this->map['items']['current']['id'];?>')" value="<?php echo Portal::language('print_vat');?>"/></td>
                        </tr>
   					    <?php }}unset($this->map['items']['current']);} ?>
    				</table>
                </div>
                <?php echo $this->map['paging'];?>
			     <input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    
<script>
	jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    var items = <?php echo $this->map['items_js'];?>;
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