<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<form method="post" name="ListBarReservationNewForm"> 
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; padding-left: 15px; font-size: 18px;" width="100%"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('bar_invoice_list');?></td>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td><input class="w3-btn w3-pink" type="button" onclick="fun_print_group();" value="<?php echo Portal::language('print_group');?>" /></td><?php }?>
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
                <table style="width: 100%;">
                    <tr style="float: left;">
                        <td align="right"><?php echo Portal::language('invoice_number');?> <input  name="invoice_number" id="invoice_number" class="date-input" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('invoice_number'));?>"></td>
                        <td align="right"><?php echo Portal::language('arrival_date');?></td>
                        <td>
                            <input  name="from_arrival_time" id="from_arrival_time" onchange="changevalue();" style="width:80px;height: 24px;" class="date-input"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_arrival_time'));?>">
                            &nbsp;&nbsp;&nbsp;<?php echo Portal::language('departure_date');?>
                            <input  name="to_arrival_time" id="to_arrival_time" onchange="changefromday();" style="width:80px;height: 24px;" class="date-input"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_arrival_time'));?>">
                        </td>                       
                        <td>
                            <?php echo Portal::language('bar_name');?>: <select  name="bars" id="bars" onchange="updateBar();" style="height: 24px;"><?php
					if(isset($this->map['bars_list']))
					{
						foreach($this->map['bars_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))
                    echo "<script>$('bars').value = \"".addslashes(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))."\";</script>";
                    ?>
	</select> 
                            <input  name="acction" value="0" id="acction" / type ="hidden" value="<?php echo String::html_normalize(URL::get('acction'));?>">                            
                        </td>
                        <td>
                            <input type="button" value="<?php echo Portal::language('search');?>" onclick="fun_check();" style="height: 24px;" />
                        </td>
                    </tr>
                </table>
                </fieldset>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="w3-light-gray" style="height: 26px;">
                        <th><input name="check_all" id="check_all" type="checkbox" />ALL</th>
						<th align="left" width="150px"><?php echo Portal::language('time');?>/<?php echo Portal::language('time_in');?></th>
                        <th align="left" width="150px"><?php echo Portal::language('time');?>/<?php echo Portal::language('time_out');?></th>
                        <th align="left" width="100px"><?php echo Portal::language('code');?></th>
						<th align="left" width="100px"><?php echo Portal::language('invoice_number');?></th>
						<th align="center"><?php echo Portal::language('agent_name');?></th>
                        <th align="center"><?php echo Portal::language('table_name');?></th>
						<th align="center"><?php echo Portal::language('bar_name');?></th>
						<th align="center" width="100px"><?php echo Portal::language('total');?></th>
                        <th align="center" width="100px"><?php echo Portal::language('total_remain_vat');?></th>
						<?php if(User::can_admin(false,ANY_CATEGORY)) {?>
						<th><?php echo Portal::language('user');?></th>
						<th align="center"><?php echo Portal::language('print');?></th>
                        <?php }?>
                    </tr>
					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                    <tr>
                        <th style="font-weight: normal !important;"><input id="items_<?php echo $this->map['items']['current']['id'];?>" class="check_bar" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" /></th>
                        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['time_in'];?></th>
                        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['time_out'];?></th>
                        <th style="font-weight: normal !important;"><a target="_blank" href="<?php echo $this->map['items']['current']['href'];?>"><?php echo $this->map['items']['current']['id'];?></a></th>
                        <th ><a target="_blank" href="<?php echo $this->map['items']['current']['href'];?>"><?php echo $this->map['items']['current']['code'];?></a></th>
                        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['receiver_name'];?></th>
                        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['table_name'];?></th>
                        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['bar_name'];?></th>
                        <th style="text-align: right;font-weight: normal !important;"><?php echo $this->map['items']['current']['total'];?></th>
                        <th style="text-align: right;font-weight: normal !important;"><?php echo $this->map['items']['current']['total_remain_vat'];?></th>
                        <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
						<th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['user_id'];?></th>
						<th align="center"><input class="w3-btn w3-pink" type="button" onclick="fun_print_group('<?php echo $this->map['items']['current']['id'];?>')" value="<?php echo Portal::language('print_vat');?>"/></th>
                        <?php }?>
                    </tr>
					<?php }}unset($this->map['items']['current']);} ?>
				</table>
                </div>
                <input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
            </td>
            </tr>
            </table>
        </td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			    
<script>
	jQuery('#from_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    var items = <?php echo $this->map['items_js'];?>;
    jQuery("#check_all").click(function(){
		var check = this.checked;
		jQuery(".check_bar").each(function(){
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
                alert("bạn chưa chọn hóa đơn!");
            else
                window.location.href='?page=vat_bill&cmd=add&type=BAR&invoice_id='+list+'';
        }
        else {
            window.location.href='?page=vat_bill&cmd=add&type=BAR&invoice_id='+$id+'';
        }
    }
    function fun_check(){
        var toantu = jQuery("#list_toantu").val();
        var total = jQuery("#total").val();
        if(toantu==''){
            if(total!=''){
                alert("bạn chưa nhập tìm kiếm theo số tiền!");
                jQuery("#list_toantu").css('background','red');
            }else{
                ListBarReservationNewForm.submit();
            }
        }else{
            if(total==''){
                alert("bạn chưa nhập tìm kiếm theo số tiền!");
                jQuery("#total").css('background','red');
            }else{
                ListBarReservationNewForm.submit();
            }
        }
        //ListBarReservationNewForm.submit();
    }
	function updateBar(){
		jQuery('#acction').val(1);
		ListBarReservationNewForm.submit();
	}

    function changevalue()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_arrival_time").val(jQuery("#from_arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_arrival_time").val(jQuery("#to_arrival_time").val());
        }
    }
</script>