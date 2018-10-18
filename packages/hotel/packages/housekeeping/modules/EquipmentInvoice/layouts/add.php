<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<form name="AddEquipmentInvoiceForm" method="post" >
    <table cellpadding="5" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    <tr>
        <td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.compensation_invoice.]]</td>
        <td align="right"  width="40%" style="padding-right: 30px;"><input type="button" id="btnsave" class="w3-btn w3-orange w3-text-white" value="[[.Save_and_close.]]" onclick="checksave();" style="text-transform: uppercase; margin-right: 5px;"/>
        <?php if(!Url::get('fast')){ ?>
        <input type="button" name="list" class="w3-btn w3-lime" onclick="window.location='<?php echo Url::build_current();?>'" value="[[.List.]]" style="text-transform: uppercase;"/></td>
        <?php } ?>
    </tr>
    </table>
    
    <table width="70%" border="0" cellpadding="10" cellspacing="0" bordercolor="black">
    <tr>
        <td>
            <table width="100%" border="0">
                <tr>
                    <td width="20%"><div align="center"><img src="<?php echo HOTEL_LOGO; ?>" width="150" height="80" /></div></td>
                    <td width="80%" align="left">
                    <div style="text-transform:uppercase"><?php echo HOTEL_NAME;?></div>
                    <div>Add : <?php echo HOTEL_ADDRESS; ?></div>
                    <div>Tel : <?php echo HOTEL_PHONE;?>* Fax : <?php echo HOTEL_FAX;?></div>
                    <div>E-mail : <?php echo HOTEL_EMAIL;?></div>
                </td>
                </tr>
            </table>
            <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
            <table width="100%" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse" cellspacing="0" cellpadding="5px">
                <tr style="width: 100%;">
                    <td colspan="6">
                        <table style="width: 100%;">
                            <tr style="width: 100%;">
                                <th scope="col" style="width:25%;">                        
                                [[.room_no.]]:
                                <select name="room_id" id="room_id" onchange="this.form.form_block_id.value=0;this.form.submit();" style="margin-right: 20px; width: 100px; height: 24px;"></select>                            
                                                        
                                </th>
                                <th style="width:20%;">[[.code_hand.]]: <input name="code" type="text" id="code" style="width:100px; height: 24px;" /></th>
                                <th style="width: 35%;">
                                    <span style="width:100%;"> [[.note.]]:</span><br/> <textarea name="note" id="note" rows="2" cols="20" style="width: 95%;"></textarea>
                                </th>
                                <th scope="col">[[.time.]]: [[|time|]]</th>
                            </tr>
                        </table>
                    </td>                    
                </tr>
                <tr class="w3-light-gray" style="width: 100%; height: 24px;">
                    <th width="10px" align="center">[[.stt.]]</th>
                    <th width="70px" align="center">[[.equipment_id.]]</th>
                    <th width="220px" align="center">[[.equipment_name.]]</th>
                    <th width="80px" align="center">[[.price.]]</th>
                   <!-- <th width="150px" align="center">[[.remain_quantity.]]</th>-->
                    <th width="75px" align="center">[[.quantity.]]</th>
                    <th width="75px" align="center">[[.amount.]]</th>
                </tr>
              <!--IF:equipment(isset([[=equipment=]]) and is_array([[=equipment=]]))-->
              <?php $i=1; ?>
              <!--LIST:equipment-->
                <tr>
                    <td align="center">
                        <?php echo $i++; ?>
                        <input type="hidden" name="equipment['[[|equipment.id|]]'][product_id]" id="equipment_[[|equipment.code|]]" value="[[|equipment.code|]]" />
                    </td>
                    <td align="center">[[|equipment.code|]]</td>
                    <td align="left">[[|equipment.name|]]</td>
                    <td align="center"><input type="text" style="text-align:right; width: 60px; border:none " name="equipment['[[|equipment.id|]]'][price]" id="price_[[|equipment.id|]]" value="<?php echo System::display_number([[=equipment.price=]]); ?>" onkeyup="update('[[|equipment.id|]]');" class="input_number" /></td>
                    <!--<td align="center"><input type="text" style="text-align:center; width: 60px; border:none "  name="equipment['[[|equipment.id|]]'][in_room_quantity]" id="in_room_quantity_[[|equipment.id|]]" value="[[|equipment.quantity|]]" readonly="readonly"/></td>-->
                    <td align="center"><input type="text" style="text-align:right; width: 60px; "  name="equipment['[[|equipment.id|]]'][quantity]" id="quantity_[[|equipment.id|]]" onkeyup="update('[[|equipment.id|]]');" class="input_number" /></td>
                    <td align="center"><input type="text" style="text-align:right; width: 60px; border:none " name="equipment['[[|equipment.id|]]'][amount]" id="amount_[[|equipment.id|]]" readonly="readonly" value="0" /></td>
                </tr>
              <!--/LIST:equipment-->

              <!--/IF:equipment-->
            </table>

            <table width="350px" border="0" bordercolor="#CCCCCC" style="border-collapse:collapse; float: right;" cellspacing="0" cellpadding="5px">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr align="right">
                    <td align="right"><strong>[[.total_before_tax.]]:</strong></td>
                    <td><input type="text" name="total_before_tax" id="total_before_tax" readonly="" style="text-align:right; width:150px;border:none; border-bottom:1px dashed #999999;" /></td>
                </tr>
                <tr align="right">
                    <td align="right"><strong>[[.tax_rate.]]:</strong>( <input  name="tax_rate" type="text" id="tax_rate" style="width:20px; border:none; background-color: #FFF;" onchange="total_calculate();" />%)</td>
                    <td><input type="text" name="tax" id="tax" readonly="" style="text-align:right; width:150px;border:none; border-bottom:1px dashed #999999;" /></td>
                </tr>
                <tr align="right">
                    <td align="right"><strong>[[.total.]]:</strong></td>
                    <td><input type="text" name="total" id="total" readonly="" style="text-align:right; width:150px;border:none; border-bottom:1px dashed #999999;" /></td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
</form>
<script type="text/javascript">
	function update(id)
    {
        //KID ĐÃ CMT ĐOẠN DƯỚI ĐÂY BỎ KIỂM TRA SỐ LƯỢNG
        //if(to_numeric(getElemValue('quantity_'+id)) != '')
//        {
//                if(to_numeric(getElemValue('quantity_'+id)) > to_numeric(getElemValue('in_room_quantity_'+id)))
//                {
//                    alert('[[.quantity_must_less_than_in_room_quantity.]]');
//                    $('quantity_'+id).value = ''; 
//                }
//            
//        }
		var amount = to_numeric(getElemValue('price_'+id))*to_numeric(getElemValue('quantity_'+id));
		$('amount_'+id).value=number_format(roundNumber(amount,2));
		total_calculate();
	}
    function checksave(){
        jQuery('#btnsave').css('display','none');
        AddEquipmentInvoiceForm.submit();
    }
	function total_calculate(){
		var total = 0;
		jQuery('input[id^=amount]').each(function(){
			total += to_numeric(this.value);
		});		
		$('total_before_tax').value = number_format(roundNumber(total,2));
		$('tax').value = number_format(roundNumber(total*getElemValue('tax_rate')/100,2));
		$('total').value = number_format(roundNumber(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('tax')),2));
	
	}
</script>