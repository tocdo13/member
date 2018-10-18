<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<span style="display:none">
	<span id="mi_ticket_sample">
		<div id="input_group_#xxxx#">
            <input   name="mi_ticket[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
            <span class="multi-input" style="width:35px;"><input name="radio"  type="radio" id="_checked_#xxxx#" style="cursor: pointer;" tabindex="-1" onclick="show_ticket_type();$('quantity_'+#xxxx#).focus();"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][name]" style="width:185px; background-color: #CCC;" class="multi-edit-text-input" type="text" id="name_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][prefix]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="prefix_#xxxx#" readonly="readonly"/></span>
<span class="multi-input"><input  name="mi_ticket[#xxxx#][form]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="form_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][denoted]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="denoted_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][name_2]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="name_2_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][ticket_group_name]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="ticket_group_name_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][price]" style="width:50px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="text" id="price_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][price_before_discount]" style="width:50px;text-align: right; background-color: #CCC" type="hidden" id="price_before_discount_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][quantity]" onkeyup="calc_total(#xxxx#);" style="width:30px;text-align: right;" class="multi-edit-text-input input_number format_number" type="text" id="quantity_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][total]" style="width:80px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="text" id="total_#xxxx#" readonly="readonly"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][received]" onkeyup="calc_total(#xxxx#);" style="width:80px;text-align: right;" class="multi-edit-text-input input_number format_number" type="text" id="received_#xxxx#"/></span>
            <span class="multi-input"><input  name="mi_ticket[#xxxx#][return]" style="width:80px;text-align: right; background-color: #CCC" class="multi-edit-text-input input_number format_number" type="text" id="return_#xxxx#" readonly="readonly"/></span>
            <br clear="all" />
        </div>
	</span>
</span>
<form name="AddTicketInvoiceForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="75%" class="form-title">[[.ticket_invoice.]]</td>
		<td align="left"  width="100px">
			<input name="save" type="submit" class="button-medium-save" value="[[.Save.]]" />
        </td>
		<td align="left" width="100px"><input type="button" name="list" class="button-medium-back" onclick="window.location='<?php echo Url::build_current();?>'" value="[[.List.]]" /></td>
	</tr>
</table>

<fieldset>
    [[.select_area.]]: <select name="ticket_area_id" id="ticket_area_id" onchange="getUrl(this.value);"></select>
    <input name="change_area" type="hidden" id="change_area" value="" />
</fieldset>
<br />
<table cellspacing="0" style="float: left;">
	<tr><td style="padding-bottom:30px">
		<table>
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr bgcolor="#EEEEEE" valign="top">
			<td style="">
			<div style="background-color:#EFEFEF;">
				<span id="mi_ticket_all_elems">
					<span style="white-space:nowrap; width:auto;">
                        <span class="multi-input-header" style="float:left;width:35px;">[[.select.]]</span>
						<span class="multi-input-header" style="float:left;width:180px;">[[.ticket_name.]]</span>
                        <span class="multi-input-header" style="float:left;width:50px;">[[.price.]]</span>
                        <span class="multi-input-header" style="float:left;width:30px;">[[.SL.]]<span><em style="color: red;">(*)</em></span></span>
                        <span class="multi-input-header" style="float:left;width:80px;">[[.total.]]</span>
                        <span class="multi-input-header" style="float:left;width:80px;">[[.received.]]</span>
                        <span class="multi-input-header" style="float:left;width:80px;">[[.return.]]</span>
						<br clear="all"/>
					</span>
				</span>
			</div>
            <br />
            </td>   
        </tr>
        </table>
    </td></tr>
</table>

    <fieldset>
        <legend class="title">[[.list_ticket_invoice.]]</legend>
        <span>[[.user.]]: [[|user_id|]]</span><br />
        <span>[[.ticket_area.]]: [[|ticket_area_name|]]</span><br /><br />
        <div>
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr>
                <th style="width: 10px;" align="center">[[.order_number.]]</th>
                <th style="width: 150px;" align="left">[[.ticket_name.]]</th>
                <th style="width: 20px;" align="center">[[.SL.]]</th>
                <th style="width: 50px;" align="center">[[.total.]]</th>
                <th style="width: 80px;" align="center">[[.date.]]</th>
            </tr>
            <!--LIST:items-->
            <tr>  
                <td style="cursor:pointer; text-align: center;">[[|items.i|]]</td>
                <td style="cursor:pointer; text-align: left;">[[|items.ticket_name|]]</td>
                <td style="cursor:pointer; text-align: center;">[[|items.quantity|]]</td>
                <td style="cursor:pointer; text-align: right;">[[|items.total|]]</td>
                <td style="cursor:pointer; text-align: center;"><?php echo  [[=items.time=]]? date('H\h:i d/m/Y',[[=items.time=]]):'' ; ?></td>
        	</tr>
            <!--/LIST:items-->			
        </table>
        </div>
        <br />
        <div class="paging">[[|paging|]]</div>
    </fieldset>


</form>

<script>
<?php if(isset($_REQUEST['mi_ticket'])){echo 'var tickets = '.String::array2js($_REQUEST['mi_ticket']).';';}else{echo 'var tickets = [];';}?>
mi_init_rows('mi_ticket',tickets);

jQuery(document).ready(function(){
    show_default_ticket();
    show_ticket_type();
});

function calc_total(index)
{
    var quantity = jQuery('#quantity_'+index).val().replace(',','');
    var price = jQuery('#price_'+index).val().replace(',','');
    if(quantity == '')
        var total = 0;
    else
        var total = to_numeric(quantity) * to_numeric(price);
    jQuery('#total_'+index).val(number_format(total));
    
    var received = jQuery('#received_'+index).val().replace(',','');
    if(received == '')
        var back = '';
    else
    {
        if(to_numeric(received) <= to_numeric(total) )
            var back = 0;
        else
            var back = to_numeric(received) - to_numeric(total);
    }
    jQuery('#return_'+index).val(number_format(back));
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
            
        }
        else
        {
            jQuery("#price_"+i).css("display","none");
            jQuery("#quantity_"+i).css("display","none");
            jQuery("#total_"+i).css("display","none");
            jQuery("#received_"+i).css("display","none");
            jQuery("#return_"+i).css("display","none");
            
            jQuery("#quantity_"+i).val('');
            calc_total(i);
        }
	}
}

function show_default_ticket()
{
    var ticket_id = <?php echo $_REQUEST['ticket_id']?$_REQUEST['ticket_id']:0;?>;
    for(var i=101;i<=input_count;i++)
    {	
        if($('id_'+i) && $('id_'+i).value == ticket_id)
        {
            jQuery('#_checked_'+i).attr('checked','checked');
            jQuery('#quantity_'+i).focus();
            break;   
        }
	}
    
}

function getUrl(ticket_area_id){
	if(ticket_area_id!='')
	{
		var url = '?page=ticket_invoice';
		url += '&ticket_area_id='+to_numeric(ticket_area_id);
		url += '&cmd=add';
		window.location = url;
	}	
}
</script>