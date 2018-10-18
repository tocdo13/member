<style>
    .simple-layout-middle{
    		width:100%;	
    	}
    #rangetext:hover{
        opacity: 0.8;
    }
    tr.title_list, tr.title_list input{
            background: #ddd;    
    }
    
    table tr th,table tr td,table tr input{
        font-size:10px;
    }
    table tr th{
        text-align:center;
    }
    
    input.number_room{
        width:20px;
    }
    input.price{
        width:50px;
    }
</style>
<form name="waitingbook" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr  height="40">
        	<td width="80%" class="form-title">[[.waiting_book.]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="button-medium-add button_style" >[[.Add.]]</a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};waitingbook.cmd.value='delete';waitingbook.submit();"  class="button-medium-delete">[[.Delete.]]</a><?php }?>
            </td>
        </tr>
    </table>
    <div class="search" style="display: inline;">
        [[.from_date.]] <input name="date_from" type="text" id="date_from" onchange="changevalue();" />
        [[.to_date.]] <input name="date_to" type="text"  id="date_to" onchange="changefromday();" />
        <input name="search" type="submit" value="[[.search.]]" />
        <input name="list_waiting" type="submit" value="[[.list_waiting.]]" />
        <input name="list_confirm" type="submit" value="[[.list_expiration_date.]]" />
        <input name="list_booked" type="submit" value="[[.list_booked.]]" />
        [[.change_size_font.]]<input type="range" min="1" max="20" step="1" id="rangetext" onchange="updateTextInput(this.value);" style="opacity: 0.4;" />
    </div>
    <br />
    <br />
    
  <?php
    $n = sizeof([[=items=]]); $i=0;
  ?>
    <table cellpadding="0" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" id="table-waitingbook">
        <tr class="title_list">
            <th width="1%" rowspan="3"><input type="checkbox" id="all_item_check_box" onclick="check_all();" /></th>
            <th width="1%" style="text-align:center;" rowspan="3">STT</th>
            <th width="5%" rowspan="3">[[.person_contact.]]</th>
            <th width="12%" rowspan="3">[[.customer.]]</th>
            <th width="5%" rowspan="3">[[.departure_date.]] - [[.arrival_date.]]</th>
            <th width="5%" rowspan="3">[[.date_confirm.]]</th>
            <th width="10%" style="max-width: 15px;" rowspan="3" >[[.note.]]</th>
            <th width="50%" colspan="<?php echo $n*2; ?>">[[.room_empty.]]</th>
            <th width="3%" rowspan="2">[[.total_room.]]</th>
            <th width="3%" rowspan="3">[[.creat_booking.]]</th>
            <th width="3%" rowspan="3">[[.booking_confirm.]]</th>
            <th width="1%" rowspan="3">[[.edit.]]</th>
            <th width="1%" rowspan="3">[[.delete.]]</th>
        </tr>
        <tr class="title_list">
           <!--LIST:items-->
                <th colspan="2">[[|items.name|]]</th>
           <!--/LIST:items-->
        </tr>
            
        <tr class="title_list">
        
             <!--LIST:items-->
             <?php $i += 1; ?>
              <th><input  id="<?php echo 'room_count_'.$i; ?>" type="text" value="[[|items.min_room_quantity|]]" readonly="" style="width: 20px; border: none; text-align: center;"  /></th>
              <th>[[.price.]]</th>
              <!--/LIST:items-->
              <th><input name="total_room" type="text" id="total_room" readonly="" style="width: 20px; border: none;" /></th>
        
        </tr>
        <?php $x = 0;?>
       <!--LIST:customers-->
       <?php //$x +=1;?>  
        <tr>
               <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|customers.id|]]" /></td>
               <td style="text-align: center;"><?php echo $x +=1;?></td>
               <td>[[|customers.contact_name|]]</td>
               <td>[[|customers.customer_name|]]</td>
               <td>[[|customers.arrival_date|]]<br />[[|customers.departure_date|]]</td>
                <!--<td><?php echo Date_time::convert_orc_date_to_date([[=customers.arrival_date=]],'/'); ?><br /><?php echo Date_time::convert_orc_date_to_date([[=customers.departure_date=]],'/'); ?></td>-->
               <td id="confirm_[[|customers.id|]]"><?php echo Date_time::convert_orc_date_to_date([[=customers.confirm_date=]],'/'); ?></td>
               <td><?php echo substr([[=customers.note=]],0,30).'...' ?></td>
            <!--LIST:items-->
               <td style="text-align: center;"><input style="text-align: center;" id='number_room_[[|customers.id|]]_[[|items.id|]]' class='number_room' type='text' name='number_room_[[|customers.id|]]_[[|items.id|]]' onchange="sum_num_room([[|customers.id|]],[[|items.id|]]);"/></td>
               <th style="text-align: center;"><input class='price' type='text' name='price' id='price_[[|customers.id|]]_[[|items.id|]]' value="<?php echo system::Display_number([[=items.price=]]); ?>" /></th>
            <!--/LIST:items-->
                <td style="text-align: center;" class='count_room'><input name='count_room_[[|customers.id|]]' type='text' id='count_room_[[|customers.id|]]' readonly='' style='width: 20px; border: none;' /></td>
                <!-- <td><input type="button" id="reservation_[[|customers.id|]]"  onclick="reservation_book([[|customers.id|]]);" value="Booking" /></td>-->
                <td id="status_[[|customers.id|]]" style="text-align: center;">[[|customers.status|]]</td>
                <td><a target="_blank" href="?page=waiting_book&cmd=booking_confirm&id=[[|customers.id|]]"><input type="button" name="booking_confirm" style="width: 40px;" id="booking_confirm" value="[[.BK.]]" /></a></td>
                <td><a id="edit_[[|customers.id|]]" href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=customers.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
                <td style="text-align: center;"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=customers.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" /></a><?php }?></td>
        </tr>
         <!--/LIST:customers-->
    </table>
    <br />
    <br />
    <br />
    <br />
    <input type="hidden" name="cmd" />
</form>
<?php 
    $_REQUEST['size_customer'] = sizeof([[=customers=]]); //echo $size_customer;
    $_REQUEST['sizeof_items'] = $n;   
 ?>


<script>
    function updateTextInput(val)
    {
          jQuery('#table-waitingbook th,#table-waitingbook td').css('font-size',val+'px');
          //alert(val);
    };
    jQuery('td:contains(Booked)').css('color','red');   
    var customer_js = <?php echo String::array2js([[=customers=]]); ?>;
    for (var customer_id in customer_js){
        if(jQuery('#status_'+customer_id).html()=='Booked'){
            jQuery('#edit_'+customer_id).css('display','none');
        }
        var confirm_date = jQuery('#confirm_'+customer_id).html();
        confirm_date1 =parseDate(confirm_date); 
        var today = new Date();
        //alert(confirm_date1);
        if(confirm_date1 < today && jQuery('#status_'+customer_id).html()!='Booked'){
           jQuery('#confirm_'+customer_id).css('color','red'); 
        }
        
    }
    function parseDate(str) {
        var mdy = str.split('/');
        return new Date(mdy[2], mdy[1]-1, mdy[0]);
    }
    
    function reservation_book(customerid)
    {
        if(jQuery('#count_room_'+customerid).val()=='')
        {
          alert('Please choose number room and type room');
          return false;  
        }
        var room_levels='';
        var items_js = <?php echo String::array2js([[=items=]]); ?>;
        for (var items in items_js)
        {       
                room_levels += '|';
                //room_price += items_js[items]['id']+','+
                //số lượng phòng
                var vnumber_room = jQuery('#number_room_'+customerid+'_'+items_js[items]['id']).val();
                // giá mỗi phòng
                var price_room   = jQuery('#price_'+customerid+'_'+items_js[items]['id']).val();
                    price_room   = price_room.replace(/,/g,"");
                    room_levels  += items_js[items]['id']+','+vnumber_room+','+price_room+',,,';    
           
        }
        room_levels = room_levels.slice(1);
        console.log(customer_js);
        var customer_id    = customerid;
        var customer_name  = customer_js[customerid].customer_name ;
        var departure_time = customer_js[customerid].departure_date; 
        var arrival_time   = customer_js[customerid].arrival_date;
        var note           = customer_js[customerid].note;
        var cid    = customer_js[customerid].id ;
        
        var href  ="?page=reservation&cmd=add&tour_id=0&tour_name=";
            href +="&customer_id="+customer_id;
            href +="&customer_name="+customer_name;
            href +="&phone_booker="+customer_js[customerid].telephone;
            href +="&booking_code="+customer_js[customerid].code_booking;
            href +="&payment_type1="+customer_js[customerid].payment_method;
            href +="&booker="+customer_js[customerid].contact_name;
            href +="&room_levels="+room_levels;
            href +="&arrival_time="+arrival_time;
            href +="&time_in=13:00";
            href +="&departure_time="+departure_time;
            href +="&time_out=12:00";
            href +="&reservation_type_id=";
            href +="&status=BOOKED";
            href +="&confirm=0";
            href +="&booking_code=";
            href +="&portal=default";
            href +="&waitingbookid="+cid;
            
        window.open(href)
    } 
    
    
    function sum_num_room(customerid,itemsid){
        var items_js = <?php echo String::array2js([[=items=]]); ?>;
        var total_room =0;
        for(var room_lv_id in items_js)
        {
            itemsid = items_js[room_lv_id]['id'];   
            xvalue = jQuery('#number_room_'+customerid+'_'+itemsid).val();
            xvalue = Number(xvalue);
            total_room = xvalue + total_room;
            jQuery('#count_room_'+customerid).val(total_room);
        }
    }
     

        var total = '0';
        total = Number(total);
        <?php for($a=1;$a<=$n;$a++){ $_REQUEST['num_i'] = $a; ?>
      var i_n = <?php echo $_REQUEST['num_i']; ?>;
      i_n = Number(i_n);
      var test = jQuery('#room_count_'+i_n).val();
      
      total = total + Number(test);
      //console.log(total);
        <?php } ?>
        jQuery('#total_room').val(total);

	jQuery("#delete_button").click(function (){
		waitingbook.cmd.value = 'delete';
		waitingbook.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('[[.are_you_sure.]]')){
			return false;
		}
	});
    jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
        console.log(check);
		jQuery(".item-check-box").each(function(){
		  
			this.checked = check;
		});
	});
      
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
</script>  
