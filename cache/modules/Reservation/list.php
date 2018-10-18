<style type="text/css">
.simple-layout-middle{width:100%;}
</style>
<script>
	ReservationRoom_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['reservation_id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
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
                                <td style="padding-bottom: 5px;"><span class="w3-text-indigo" style="text-transform: uppercase; font-size: 14px;"><?php echo Portal::language('filter_by');?> <?php echo Portal::language('hotel');?></span> <select  name="portal_id" id="portal_id" style="height: 24px; margin-right: 30px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select>
                                <?php echo Portal::language('from_date');?>:<input  name="from_date" id="from_date" onchange="changevalue();" style="width: 70px; height: 24px; margin-right: 20px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                                <?php echo Portal::language('to_date');?>:<input  name="to_date" id="to_date" onchange="changefromday();" style="width: 70px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                
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
                    <!--<input  name="status" id="status" type ="text" value="<?php echo String::html_normalize(URL::get('status'));?>"> -->
					<table style="width: 100%;">
                    <form name="ExportForm" method="post">
                        <div>
    						<tr>
        						  <td style="width: 100px;"><?php echo Portal::language('room');?></td>        						  
        						  <td style="width: 100px;"><input  name="room_id" id="room_id" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('room_id'));?>"></td>
        						  <td style="width: 100px;"><?php echo Portal::language('customer');?></td>        						  
        						  <td style="width: 100px;"><input  name="customer_name" id="customer_name" style="width: 100px;height: 24px;" onkeyup="Autocomplete();"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"></td><input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
        						  <td style="width: 100px;"><?php echo Portal::language('guest_name');?></td>
        						  <td style="width: 100px;"><input  name="traveller_name" id="traveller_name" style="width:100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('traveller_name'));?>"></td>
        						  <td style="width: 80px;"><?php echo Portal::language('vnd_rate');?></td>
        						  <td style="width: 100px;"><select  name="price_operator" id="price_operator" style="width:30px;font-weight:bold; height: 24px;"><?php
					if(isset($this->map['price_operator_list']))
					{
						foreach($this->map['price_operator_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('price_operator',isset($this->map['price_operator'])?$this->map['price_operator']:''))
                    echo "<script>$('price_operator').value = \"".addslashes(URL::get('price_operator',isset($this->map['price_operator'])?$this->map['price_operator']:''))."\";</script>";
                    ?>
	</select><input  name="price" id="price" style="width:70px;height: 24px;" onKeyUp="change_price();" / type ="text" value="<?php echo String::html_normalize(URL::get('price'));?>"></td>
                                  <td style="width: 50px;"><?php echo Portal::language('room_level');?></td>
                                  <td style="width: 100px;"><select  name="room_level" id="room_level" style="width: 100px;height: 24px;"><?php
					if(isset($this->map['room_level_list']))
					{
						foreach($this->map['room_level_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_level',isset($this->map['room_level'])?$this->map['room_level']:''))
                    echo "<script>$('room_level').value = \"".addslashes(URL::get('room_level',isset($this->map['room_level'])?$this->map['room_level']:''))."\";</script>";
                    ?>
	</select></td>
                                  <td style="width: 10px;"></td>
        						  <td style="width: 100px;"><input type="submit" name="search" value="<?php echo Portal::language('search');?>" class="w3-btn w3-blue" style="width: 100%;" /></td>
                                  <!--Oanh them export file excel -->
                                  <td style="width: 100px;"><input class="w3-btn w3-green" name="export_file_excel" type="button" onclick="exportExcel()" value="<?php echo Portal::language('export_file_excel');?>" style="width: 100%;" /></td>
                                  <!-- end Oanh-->
                            </tr>
    						<tr>
        						  <td style="width: 100px;"><?php echo Portal::language('booking_code');?></td>        						  
        						  <td style="width: 100px;"><input  name="booking_code" id="booking_code" style="width:100px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
        						  <td style="width: 100px;"><?php echo Portal::language('rcode');?></td>        						  
        						  <td style="width: 100px;"><input  name="code" id="code" style="width:100px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>"></td>
        						  <td style="width: 100px;"><?php echo Portal::language('nationality');?></td>        						  
        						  <td style="width: 100px;">
                                    <select  name="nationality_id" id="nationality_id" style="width:100px; height: 24px;"><?php
					if(isset($this->map['nationality_id_list']))
					{
						foreach($this->map['nationality_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))
                    echo "<script>$('nationality_id').value = \"".addslashes(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))."\";</script>";
                    ?>
	
        						    </select>
                                    </td>
        						  <td style="width: 80px;"><?php echo Portal::language('note');?></td>
        						  <td colspan="3" style="" ><input  name="note" id="note" style="width:100%; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('note'));?>"></td>
                                  <td style="width: 10px;"></td>
        						  <td colspan="2" style="width: 200px;" ><input type="submit" name="view_printable_list" value="<?php echo Portal::language('view_printable_list');?>" class="w3-btn w3-gray" style="width: 100%;" /></td>
                                  
                            </tr>
                         </div>
                         <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    </table>
                    </div>
					<input type="submit" style="width:0px;background-color:inherit;border:0 solid white;display:none">
                    </fieldset>
					<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    <form name="ReservationRoomListForm" method="post">
                    <input  name="cmd" id="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
					<div>
    					<table width="100%">
    						<tr>
                            	<td width="100%">
    							<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                                    <tr>
                                        <td class="form-title"><?php echo $this->map['title'];?> (<?php echo Portal::language('total');?>:
                                        <?php if(url::get('status') =='CHECKOUT'){ echo $this->map['total_checkout'];}else{ echo $this->map['total']; }?>)</td>
                                    </tr>
                                </table>
                                </td>
    					   </tr>
                        </table>
					</div>
					<table id="myTable" class="tablesorter" width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="table-header" style="text-transform: uppercase;">
							<th nowrap align="center" style="width: 150px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='traveller_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('guest_name');?>								</a>							</th>
							<th align="center" style="width: 150px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='room_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('room_number');?></a></th>
                            <th nowrap align="center" style="width: 80px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.price' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.price'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.price') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('price');?> <span style="font-size:10px;">(<?php echo HOTEL_CURRENCY;?>)</span></a>
							</th><th nowrap align="center" style="width: 80px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.adult' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.adult'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.adult') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('num_people');?>								</a>
							</th><th nowrap align="center" style="width: 100px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.arrival_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.arrival_time'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.arrival_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('arrival_time');?>								</a>
							</th><th nowrap align="center" style="width: 100px;">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='reservation_room.departure_time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'reservation_room.departure_time'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='reservation_room.departure_time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('departure_time');?>								</a>
							</th><th nowrap align="center" style="width: 100px;"><?php echo Portal::language('status');?></th>
							<th align="center" style="width: 100px;"> <?php echo Portal::language('create_user');?> </th>
							<th align="center" style="width: 100px;"> <?php echo Portal::language('create_time');?> </th>
							<th align="center" style="width: 100px;"><?php echo Portal::language('tk_book');?></th>
							<th align="center" style="width: 100px;"> <?php echo Portal::language('lastest_edited_user');?> </th>
							<th align="center" style="width: 100px;"><?php echo Portal::language('lastest_edited_time');?></th>
                            <?php //if(User::can_admin(false,ANY_CATEGORY)){ ?>
                            	<!--<th align="center" style="width: 100px;"><?php echo Portal::language('tk_checkin');?></th>
                            <?php //}else{?>
							<th align="center" style="width: 100px;"><?php echo Portal::language('checked_in_user');?></th> -->
                            <?php //}?>
							<?php if(User::can_add(false,ANY_CATEGORY))
							{
							?><th><?php echo Portal::language('edit');?></th>
							<?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th><?php echo Portal::language('delete');?></th>
							<?php
							}
							?></tr>
						<?php $temp = '';?>
                        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
                        <?php if($temp!=$this->map['items']['current']['reservation_id']){$temp = $this->map['items']['current']['reservation_id'];?>
                        <tr>
	                        <td colspan="13" class="category-group">
							[<?php echo Portal::language('rcode');?>:  <?php echo $this->map['items']['current']['reservation_id'];?>] <?php if($this->map['items']['current']['mice_reservation_id']!=''){ echo '| <span style="color: red;">[MICE+'.$this->map['items']['current']['mice_reservation_id'].']</span>'; } ?> | <span style="color:#0066FF;">[<?php echo Portal::language('booking_code');?>: <?php echo $this->map['items']['current']['booking_code'];?>]</span> | <?php echo Portal::language('tour');?>: <?php echo $this->map['items']['current']['tour_name'];?> | <?php echo Portal::language('customer');?>: <?php echo $this->map['items']['current']['customer_name'];?>
							<?php 
				if(($this->map['items']['current']['group_note']))
				{?>
								<div class="note" style="text-transform:none;"><span class="note">*<?php echo Portal::language('note');?>: <?php echo $this->map['items']['current']['group_note'];?></span></div>
								
				<?php
				}
				?>
							</td>
                            <td class="reservation-list-item" style="background-color: #FF9; border-bottom: 1px solid #FC0" nowrap width="15">
							<?php
							if(
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and $this->map['items']['current']['status'] == 'BOOKED')
							)
							{
							?>&nbsp;<a href="<?php echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>$this->map['items']['current']['reservation_id'])); ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
							<?php
							}
							?></td>
                        </tr>
                        <?php }?>
						<tr valign="top" id="ReservationRoom_tr_<?php echo $this->map['items']['current']['reservation_id'];?>" <?php echo 'class="'.(($this->map['items']['current']['i']%2==0)?'row-even':'row-odd').'"';?>>
							<td class="reservation-list-item" width="150" align="left">
                            	<?php echo $this->map['items']['current']['i'];?> / <?php echo Portal::language('invoice_number');?>: <?php echo $this->map['items']['current']['id'];?>
								<?php if(isset($this->map['items']['current']['travellers']) and is_array($this->map['items']['current']['travellers'])){ foreach($this->map['items']['current']['travellers'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['travellers']['current'] = &$item3;?>
									<div><a target="_blank" href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['travellers']['current']['id']));?>"><?php echo $this->map['items']['current']['travellers']['current']['full_name'];?></a></div>
								<?php }}unset($this->map['items']['current']['travellers']['current']);} ?></td>
							<td class="reservation-list-item"align="left">
								<div style="float:left;width:150px;font-size:11px;"><strong><?php echo $this->map['items']['current']['room_id'];?></strong> - <?php echo Portal::language('room_level');?> <strong><?php echo $this->map['items']['current']['room_level'];?></strong><br />
							  (<?php echo $this->map['items']['current']['portal_name'];?>)</div>
							  <?php 
				if(($this->map['items']['current']['note']))
				{?>
								<div class="note"><span class="note">*<?php echo Portal::language('note');?>: <?php echo $this->map['items']['current']['note'];?></span></div>
								
				<?php
				}
				?>
							  </td>
							<td class="reservation-list-item" nowrap align="right">
								<?php echo $this->map['items']['current']['price'];?></td>
                            <td class="reservation-list-item" nowrap>
								<?php echo $this->map['items']['current']['adult'];?><i class="fa fa-male" style="font-size: 16px;"></i><?php 
				if(($this->map['items']['current']['child']))
				{?>+ <?php echo $this->map['items']['current']['child'];?><i class="fa fa-child" style="font-size: 16px;"></i>
				<?php
				}
				?>
						  </td>
							<td class="reservation-list-item" nowrap align="center" title="<?php echo $this->map['items']['current']['arrival_time'];?> <?php echo $this->map['items']['current']['time_in'];?>">
								<?php echo $this->map['items']['current']['time_in'];?></td>
							<td  class="reservation-list-item"nowrap align="center" title="<?php echo $this->map['items']['current']['departure_time'];?> <?php echo $this->map['items']['current']['time_out'];?>">
							  <?php echo $this->map['items']['current']['time_out'];?> <?php 
				if(($this->map['items']['current']['verify_dayuse']))
				{?><span style="font-weight:bold;"><?php echo $this->map['items']['current']['verify_dayuse_label'];?></span>
				<?php
				}
				?></td>
							<td class="reservation-list-item" nowrap align="center">
								<?php echo $this->map['items']['current']['status'];?>							</td>
							<td class="reservation-list-item"><?php echo $this->map['items']['current']['user_id'];?></td>
							<td class="reservation-list-item"><?php echo date('d/m/Y H:i\'',$this->map['items']['current']['time']);?></td>
							<td align="left" nowrap="nowrap" class="reservation-list-item"> <?php echo $this->map['items']['current']['booked_user_id'];?> </td>
							<td class="reservation-list-item"><?php echo $this->map['items']['current']['lastest_edited_user_id'];?></td>
							<td class="reservation-list-item"><?php echo $this->map['items']['current']['lastest_edited_time']?date('d/m/Y H:i\'',$this->map['items']['current']['lastest_edited_time']):'';?></td>
                            <?php //if(User::can_admin(false,ANY_CATEGORY))
							{
							?><!--<td  class="reservation-list-item" valign="middle" style="text-align: center;">
                            <a href="#" onclick="openWindowUrl('http://<?php //echo $_SERVER['HTTP_HOST'];?>/<?php// echo Url::$root;?>form.php?block_id=428&cmd=deposit&id=<?php echo $this->map['items']['current']['id'];?>&type=RESERVATION&customer_id=<?php echo $this->map['items']['current']['customer_id'];?>&portal_id=<?php echo PORTAL_ID;?>',Array(<?php echo $this->map['items']['current']['id'];?>,'<?php echo Portal::language('deposit');?>','80','210','1000','500'));" title="<?php echo Portal::language('Deposit');?>" ><i class="fa fa-money w3-text-green" style="font-size: 30px;"></i> </a></td>-->
							<?php }?>
							<td class="reservation-list-item" nowrap width="20" style="text-align: center;">
							<?php
							if( User::can_edit(false,ANY_CATEGORY)or (USER::can_add(false,ANY_CATEGORY) and ($this->map['items']['current']['status'] == 'BOOKED' ))or (User::can_edit(false,ANY_CATEGORY) and ($this->map['items']['current']['status'] == 'BOOKED' or $this->map['items']['current']['status'] == 'IN'))){?>
								&nbsp;<a href="<?php echo Url::build_current(array('layout'=>'list','customer_name', 'year','month','day', 'status', 'room_id','cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'])); ?>&r_r_id=<?php echo $this->map['items']['current']['id'];?>" title="<?php echo Portal::language('Edit');?>"><i class="fa fa-edit w3-text-orange" style="font-size: 18px; margin-top: 5px;"></i></a>
							<?php
							}
							?></td>
							<td class="reservation-list-item" nowrap width="15">
							<?php
							if(
								User::can_admin(false,ANY_CATEGORY)
								or
								(User::can_delete(false,ANY_CATEGORY) and $this->map['items']['current']['status'] == 'BOOKED')
							)
							{
							?>&nbsp;<!--<a href="<?php //echo Url::build_current(array('customer_name', 'year','month','day', 'room_id', 'status', 'cmd'=>'delete','id'=>$this->map['items']['current']['reservation_id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>" border="0"></a>-->
							<?php
							}
							?></td>
						</tr>
						<?php if($this->map['reservation_arr'][$this->map['items']['current']['reservation_id']] == $this->map['items']['current']['cc']){?>
						<tr bgcolor="#EFEFEF">
						  <td align="right" class="reservation-list-item"><strong><?php echo Portal::language('total_room');?></strong></td>
						  <td colspan="14" align="left" class="reservation-list-item"><strong><?php echo $this->map['reservation_arr'][$this->map['items']['current']['reservation_id']];?></strong></td>
					    </tr>
					  	<?php }?>
						<?php }}unset($this->map['items']['current']);} ?>
				  </table>
                  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
				</td>
			</tr>
			</table>
			<?php echo $this->map['paging'];?>
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