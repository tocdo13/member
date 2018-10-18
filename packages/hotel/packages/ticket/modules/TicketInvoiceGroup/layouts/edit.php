<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<script type="text/javascript">
	ticket_arr = <?php echo String::array2js([[=ticket=]])?>;
    var room_array={
		'':''
	<!--LIST:reservation_traveller_list-->
	<!--IF:cond_extra(isset([[=reservation_traveller_list.reservation_room_id=]]))-->
		,'[[|reservation_traveller_list.id|]]':{
			'id':'[[|reservation_traveller_list.reservation_room_id|]]',
			'name':'<?php echo addslashes([[=reservation_traveller_list.name=]])?>'
		}
	<!--/IF:cond_extra-->
	<!--/LIST:reservation_traveller_list-->
	}
</script>
<span style="display:none">
	<span id="mi_ticket_sample">
		<div id="input_group_#xxxx#">
            <input   name="mi_ticket[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
            <!--<input   name="mi_ticket[#xxxx#][ticket_invoice_id]" type="hidden" readonly="" id="ticket_invoice_id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>-->
            <span class="multi-input">
                <select  name="mi_ticket[#xxxx#][ticket_id]" id="ticket_id_#xxxx#" style="width: 185px;" onchange="update_ticket(this.value,#xxxx#);">[[|ticket_options|]]</select>
            </span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][ticket_id_printed]" style="width:181px;text-align: left; background-color: #CCC; display: none;" class="multi-edit-text-input input_number format_number" type="text" id="ticket_id_printed_#xxxx#" readonly="readonly"/></span>
            <!--<span class="multi-input"><input  name="mi_ticket[#xxxx#][ticket_name]" style="width:180px; background-color: #CCC;" class="multi-edit-text-input" type="text" id="ticket_name_#xxxx#" readonly="readonly"/></span>-->
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][price]" style="width:50px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="text" id="price_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][price_before_discount]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="price_before_discount_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][quantity]" onkeyup="calc_total(#xxxx#);" onblur="calc_total(#xxxx#);" style="width:60px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="text" id="quantity_#xxxx#" /></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][discount_quantity]" onkeyup="calc_total(#xxxx#);" onblur="calc_total(#xxxx#);" onblur="calc_total(#xxxx#);" style="width:60px;text-align: right;" class="multi-edit-text-input input_number format_number" type="text" id="discount_quantity_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][discount_rate]" onkeyup="calc_total(#xxxx#);" onblur="calc_total(#xxxx#);" style="width:60px;text-align: right;" class="multi-edit-text-input input_number format_number" type="text"  id="discount_rate_#xxxx#"/></span>
            <!--<span class="multi-input"><input  name="mi_ticket[#xxxx#][discount_cash]" onkeyup="calc_total(#xxxx#);" onblur="calc_total(#xxxx#);" style="width:80px;text-align: right; display: none;" class="multi-edit-text-input input_number format_number" type="text"  id="discount_cash_#xxxx#"/></span>-->
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][total]" style="width:80px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="text" id="total_#xxxx#" readonly="readonly"/></span>
            <!--Hidden-->
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][last_code]" style="width:80px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="hidden" id="last_code_#xxxx#" readonly="readonly"/></span>
            <!--/Hidden-->
            <span class="multi-input">
                <a target="_blank" onclick="openPrint(#xxxx#);" id="printer_#xxxx#">
                    <img src="packages/core/skins/default/images/buttons/printer.png" alt="[[.printer.]]" height="15"/>
                </a>
            </span>
            <span class="multi-input">
                <a target="_blank" onclick="openCancel(#xxxx#);" id="cancel_#xxxx#" style="display: none;">
                    <img src="packages/core/skins/default/images/cancel.png" alt="[[.cancel.]]" height="15"/>
                </a>
            </span>
            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px; text-align:center;"><img id="delete_#xxxx#" tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_ticket','#xxxx#','');event.returnValue=false; updateTotal_Lang();}" style="cursor:pointer;"/></span>   
			<!--/IF:delete-->
            <br clear="all" />
        </div>
	</span>
</span>
<form name="AddTicketInvoiceForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="75%" class="form-title">[[.ticket_invoice.]]</td>
		<td align="left"  width="100px">
			<input name="save" type="submit" value="[[.Save.]]" class="button-medium-save" onclick="jQuery('.button-medium-save').css('display','none');"" />
        </td>
		<td align="left" width="100px">
        <input type="button" name="list" class="button-medium-back" onclick="window.location='<?php echo Url::build_current();?>'" value="[[.List.]]" />
        </td>
	</tr>
</table>

<input  name="deleted_ids" id="deleted_ids" type="hidden"/>
<br />
<!--LIST:items2-->

<fieldset>
    <legend class="title">[[.info.]]</legend>
    <div>
    <table>
        <tr>
            <th>[[.guest.]]</th>
            <td >
                <input name="customer_name" type="text" id="customer_name" style="" value="[[|items2.customer_name|]]" />
                <input name="customer_id" type="hidden" id="customer_id" class="" value="[[|items2.customer_name|]]"/>
               <!--IF:pointer(User::can_edit(false,ANY_CATEGORY))-->
               <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> 
               <img src="skins/default/images/cmd_Tim.gif" />
               </a> 
               <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:hand;">
               <!--/IF:pointer-->
            </td>  
            <td>
                <th>[[.note.]] : </th>
                <td><textarea name="note" id="note"></textarea></td>
            </td>    
        </tr>
        <tr>
            <th>[[.Address.]] : </th>
            <td><textarea name="customer_address" id="customer_address"></textarea></td>
            <td></td>
        </tr>
        <tr>
            <th align="left">[[.select_guest_room.]] : </th>
            <td>
                <select name="reservation_traveller_id" id="reservation_traveller_id" onChange="update_room(this);if(this.value!=0){jQuery('#pay_with_room').attr('checked',true);}"  style="width:170px;"></select>
            </td>
            <td style="width: 100px;"></td>
            <th>[[.room_name.]] : </th>
            <td> <select name="reservation_room_id" id="reservation_room_id"  onChange="update_traveller(this);if(this.value!=0){jQuery('#pay_with_room').attr('checked',true);}" style="width:100px;"></select></td>
            <td><input name="pay_with_room" type="checkbox" id="pay_with_room" value="1" style="width:20px; height:20px;" title="[[.pay_with_room.]]"/></td>
      	</tr>
        <tr>
            <td colspan="2">
            <!-- M?nh th�m � nh?p m� th�nh vi�n d? th?c hi?n t�ch di?m -->
            <?php if(SETTING_POINT==1){ ?>
            <span style="float: left; line-height: 25px; margin-right:35px;"> [[.member_code.]]: </span>
            <input type="text" name="member_code"value="<?php echo isset($_REQUEST['member_code'])?$_REQUEST['member_code']:''; ?>" id="member_code" autocomplete="off" onchange="fun_load_member_code();" style="width: 100px; height: 20px; text-align: center; border: 1px solid #555555; float: left;" />
            <input type="text" name="member_level_id" value="<?php echo isset($_REQUEST['member_level_id'])?$_REQUEST['member_level_id']:''; ?>" id="member_level_id" style="display: none;" />
            <input type="text" name="create_member_date" value="<?php echo isset($_REQUEST['create_member_date'])?$_REQUEST['create_member_date']:''; ?>" id="create_member_date" style="display: none;" />
            <input type="button" name="view_info_member" id="view_info_member" value="info" style="padding: 3px; margin: 0px 2px ; float: left; margin-left:20px;" onclick="fun_view_info_member();" />
            <div id="div_info" style="width: 100%; height: 100%; display: none; background-color: rgba(0, 0, 0, 0.9); position: fixed; top: 0px; left: 0px;">
                <div style="width: 600px; height: 400px; margin: 50px auto; background: #ffffff; position: relative;">
                    <div style="width: 20px; height: 20px; border: 2px solid #000000; border-radius: 50%; line-height: 20px; text-align: center; font-size: 17px; position: absolute; top: -10px; right: -10px; background: #fff; cursor: pointer;" onclick="fun_close_info();">X</div>
                    <div id="info_member_discount" style="width: 600px; height: 390px; position: absolute; top: 10px; left: 0px;"></div>
                </div>
            </div>
            <?php } ?>
            <!-- end M?nh -->
            </td>
            </tr>
            <tr>
                <th>[[.discount_rate.]]</th>
                <td><input type="text" id="discount_rate" name="discount_rate"  value="[[|items2.discount_rate|]]" style="width: 30px; text-align: right;" class="multi-edit-text-input input_number format_number" onkeyup="updateTotal_Lang();" onblur="updateTotal_Lang();" /></td>
            </tr>
            <tr>
                <th>[[.deposit.]]</th>
                <td><input type="text" id="deposit" name="deposit"  value="[[|items2.deposit|]]" style="width: 70px; text-align: right;" class="multi-edit-text-input input_number format_number" readonly="readonly" /></td>
            </tr>
            <tr>
                <th>[[.total_money.]]</th>
                <td><input type="text" id="total_all" name="total_all"  value="[[|items2.total_all|]]" readonly="readonly" style="text-align: right;" /></td>
            </tr>
            <tr>  
                
            </tr>
        </table>
    </div>
</fieldset>
<!--/LIST:items2-->
<br />
<table cellspacing="0">
	<tr><td style="padding-bottom:30px">
		<table>
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="mi_ticket_all_elems">
					<span style="white-space:nowrap; width:30px;">
						<span class="multi-input-header" style="float:left;width:180px;">[[.ticket_name.]]</span>
                        <span class="multi-input-header" style="float:left;width:50px;">[[.price.]]</span>
                        <span class="multi-input-header" style="float:left;width:60px;">[[.SL.]]<span><em style="color: red;">(*)</em></span></span>
                        <span class="multi-input-header" style="float:left;width:60px;">[[.discount_quantity.]]</span>
                        <span class="multi-input-header" style="float:left;width:60px;">[[.discount_rate.]]</span>
                        <!--<span class="multi-input-header" style="float:left;width:80px;">[[.cash_discount.]]</span>-->
                        <span class="multi-input-header" style="float:left;width:80px;">[[.total.]]</span>
                        <!--<span class="multi-input-header" style="float:left;width:80px;">[[.printed.]]</span>-->
                        <span class="multi-input-header" style="float:left;width:60px;">[[.print.]]/[[.cancel.]]</span>
						<br clear="all"/>
					</span>
				</span>
                <input type="button" value="[[.add_ticket.]]" onclick="mi_add_new_row('mi_ticket');"/>
			</div>
            <br />
            </td>   
        </tr>
        </table>
    </td></tr>
</table>    
</form>
<?php 
    //echo String::array2js($_REQUEST['mi_ticket']);
?>
<script>
<?php 
    if(isset($_REQUEST['mi_ticket']))
    {
        echo 'var tickets = '.String::array2js($_REQUEST['mi_ticket']).';';
    }
    else
    {
        echo 'var tickets = [];';
    }
?>
mi_init_rows('mi_ticket',tickets);
input_count_lang = 100;
function Confirm(index)
{
	var mi_ticket_name = jQuery('#ticket_id_'+ index).val();
	return confirm('[[.Do_you_want_to_delete.]] '+ mi_ticket_name+' ticket ?');
}
for( var i in tickets)
{
    input_count_lang ++;
    if(tickets[i]['last_code'] != '')
    {
        jQuery('#quantity_'+input_count_lang).attr('readonly','readonly'); 
        jQuery('#printer_'+input_count_lang).css('display','none');
        jQuery('#delete_'+input_count_lang).css('display','none');
        jQuery('#cancel_'+input_count_lang).css('display','');
        jQuery('#ticket_id_printed_'+input_count_lang).css('display','');  
        jQuery('#ticket_id_'+input_count_lang).css('display','none');
    }
    else
    {
        jQuery('#ticket_id_printed_'+input_count_lang).css('display','none');  
    }
}
//alert(tickets);

jQuery(document).ready(function()
{
    //show_default_ticket();
    //show_ticket_type();
    for(var i=101; i<=input_count; i++)
	{
	   var test_discount_ticket = jQuery('#discount_cash_'+i).val();
       jQuery('#discount_cash_'+i).val(number_format(test_discount_ticket));
	}
});
function openPrint(index)
{
    setTimeout('location.reload()',1000);
    window.open('?page=ticket_invoice_group&cmd=print&invoice_id='
                +jQuery('#id_'+index).val()+'&ticket_id='
                +jQuery('#ticket_id_'+index).val()+'&ticket_price='
                +jQuery('#price_'+index).val()+'&quantity='
                +jQuery('#quantity_'+index).val());
    
}
function openCancel(index)
{
    openWindowUrl('form.php?block_id=<?php echo module::block_id();?>&cmd=cancel&invoice_id='+jQuery('#id_'+index).val(),Array('cancel','cancel_for',80,210,950,500));
}
function calc_total(index)
{
    var quantity = to_numeric(jQuery('#quantity_'+index).val());
    var deposit = to_numeric(jQuery('#deposit').val());
    var quantity_discount = to_numeric(jQuery('#discount_quantity_'+index).val());
    var discount_rate = to_numeric(jQuery('#discount_rate_'+index).val());
    //Start: KID xu ly de an giam gia theo tien mat
    //var discount_cash = to_numeric(jQuery('#discount_cash_'+index).val());
    //End
    var price = to_numeric(jQuery('#price_'+index).val());
    
    //tinh toan
    var real_quantity = quantity - quantity_discount;
    //Start: KID xu ly de an giam gia theo tien mat
    //var real_price = (price - discount_cash)*(1 - discount_rate/100);
    var real_price = (price)*(1 - discount_rate/100);
    //End
    if(quantity == '')
        var total = 0;
    else
        var total = real_quantity*real_price;
    var old_total = to_numeric(jQuery('#total_'+index).val());
    jQuery('#total_'+index).val(number_format(total));
    var old_total_all = to_numeric(jQuery('#total_all').val());
    jQuery('#total_all').val(number_format(old_total_all - old_total + total)); 
    updateTotal_Lang();
}
//update total
function updateTotal_Lang()
{
    var total_all = 0;
	for(var i=101; i<=input_count; i++)
	{
		if($('total_'+i))
		{
			total_all = total_all + to_numeric(jQuery('#total_'+i).val());
		}
	}
    //var deposit = to_numeric(jQuery('#deposit').val());
    var old_total_all = total_all;
    var discount_rate_all = to_numeric(jQuery('#discount_rate').val());
    var total_discount_all = old_total_all*discount_rate_all/100;
    var new_total_all = old_total_all - total_discount_all;
    jQuery('#total_all').val(number_format(new_total_all));
}
function show_ticket_type()
{
    for(var i=101;i<=input_count;i++)
    {	
        if($('_checked_'+i) && $('_checked_'+i).checked == true)
        {
            jQuery("#price_"+i).css("display","block");
            jQuery("#quantity_"+i).css("display","block");
            jQuery("#total_"+i).css("display","block");
            jQuery("#received_"+i).css("display","block");
            jQuery("#return_"+i).css("display","block");
            jQuery("#total_all"+i).css("display","block");
            
        }
        else
        {
            jQuery("#price_"+i).css("display","none");
            jQuery("#quantity_"+i).css("display","none");
            jQuery("#total_"+i).css("display","none");
            jQuery("#received_"+i).css("display","none");
            jQuery("#return_"+i).css("display","none");
            jQuery("#total_all"+i).css("display","block");
            jQuery("#quantity_"+i).val('');
            calc_total(i);
        }
	}
}
//update room
function get_room(room_id)
{
	if(typeof room_array[room_id]!= 'undefined')
	{
		return room_array[room_id];
	}
	return false;
}
function update_room(obj)
{
	var room = get_room(obj.value);
	if(room)
    {
		$('reservation_room_id').value = room.id;
		if($('room')) $('room').checked = true;
		for(i=0;i<$('reservation_room_id').options.length;i++)
        {
			if($('reservation_room_id').options[i].selected == true)
            {
				$('reservation_room_id').value = $('reservation_room_id').options[i].value;
			}
		}
	}
    else
    {
		$('reservation_room_id').value = 0;
		if($('room')) $('room').checked = false;
		jQuery('#pay_with_room').attr('checked','');
		//$('receiver_name').value = '';
	}
}
function update_traveller(obj){
	for(var j in room_array){
		if(room_array[j]['id']==obj.value){
			$('reservation_traveller_id').value = j;
			jQuery('#pay_with_room').show();
			break;
		}else{
			$('reservation_traveller_id').value = 0;
			jQuery('#pay_with_room').attr('checked','');
			jQuery('#pay_with_room').hide();
		}
	}
}
//update ticket
function update_ticket(ticket_id,index)
{
	if(ticket_arr[ticket_id]!='undefined')
	{
		ticket = ticket_arr[ticket_id];
        if($('ticket_id_'+index).value!='')
        {
            //alert(banquet_room.group_name);
			$('price_'+index).value =  ticket.price;
        }
	}
}
function getUrl(ticket_reservation_id){
	if(ticket_reservation_id!='')
	{
		var url = '?page=ticket_invoice_group';
		url += '&ticket_reservation_id='+to_numeric(ticket_reservation_id);
		url += '&cmd=list';
		window.location = url;
	}	
}
function fun_load_member_code(){
    var member_code = jQuery("#member_code").val();
    if(member_code!=''){
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                for(obj in otbjs){
                    if(otbjs[obj]['no_member']==0){
                        alert("m� th�nh vi�n kh�ng t?n t?i, vui l�ng nh?p l?i m�!");
                        return;
                    }else{
                    jQuery("#member_level_id").val(otbjs[obj]['MEMBER_LEVEL_ID']);
                    jQuery("#create_member_date").val(otbjs[obj]['create_member_date']);
                    }
                }
            }
        }
        xmlhttp.open("GET","get_member.php?data=get_member_discount&member="+member_code,true);
        xmlhttp.send();
    }
}
function fun_view_info_member(){
    var member_level_id = jQuery("#member_level_id").val();
    var create_member_date = jQuery("#create_member_date").val();
    if(member_level_id == ''){
        alert("kh�ng c� chuong tr�nh gi?m gi� gi?m gi�! ki?m tra l?i m� th�nh vi�n!");
    }else{
    if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                //console.log(otbjs);
                var info = '';
                if(otbjs['info_member']['no_discount']==0){
                    alert("Kh�ng c� chuong tr�nh gi?m gi� cho h?ng th�nh vi�n n�y!");
                    return;
                }else{
                    info += '<table style="width: 100%; border: 1px solid #999999" >';
                            info += '<tr>';
                                info += '<th>[[.code_discount.]]</th>'
                                info += '<th>[[.title_discount.]]</th>'
                                info += '<th>[[.description_discount.]]</th>'
                                info += '<th>[[.start_date_discount.]]</th>'
                                info += '<th>[[.end_date_discount.]]</th>'
                            info +='</tr>';
                        for(var otbj in otbjs){
                            if(otbj!='info_member'){
                                info += '<tr>';
                                    info += '<th>'+otbjs[otbj]['MEMBER_DISCOUNT_CODE']+'</th>'
                                    info += '<th>'+otbjs[otbj]['TITLE']+'</th>'
                                    info += '<th>'+otbjs[otbj]['DESCRIPTION']+'</th>'
                                    info += '<th>'+otbjs[otbj]['START_DATE']+'</th>'
                                    info += '<th>'+otbjs[otbj]['END_DATE']+'</th>'
                                info +='</tr>';
                                
                            }
                        }
                        info += '</table>';
                        document.getElementById("info_member_discount").innerHTML = info;
                        jQuery("#div_info").css('display','');
                }
            }
        }
        xmlhttp.open("GET","get_member.php?data=get_member_info&member_level_id="+member_level_id+"&date="+create_member_date,true);
        xmlhttp.send();
        }
}
function fun_close_info(){
    jQuery("#div_info").css('display','none');
}
</script>
