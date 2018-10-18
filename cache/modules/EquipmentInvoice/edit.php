<style>
    .span_total_quantity{ display: none;}
    .td_total_quantity{ display: none;}
    #span_tax_rate{display: none;}
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<form id="AddEquipmentInvoiceForm" name="AddEquipmentInvoiceForm" method="post" >
  <table cellpadding="5" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    <tr style="border-bottom: 1px solid lightgray;">
      <td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 20px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('compensation_invoice');?></td>
      <td align="right"  width="40%" style="padding-right: 30px;">
      <input type="submit" class="w3-btn w3-orange w3-text-white" value="<?php echo Portal::language('Save_and_close');?>" onclick="return check_save();" style="text-transform: uppercase; margin-right: 5px;" />
      <input type="button" name="list" class="w3-btn w3-lime" onclick="window.location='<?php echo Url::build_current();?>'" value="<?php echo Portal::language('List');?>" style="text-transform: uppercase;"/>
      </td>
    </tr>
  </table>
  <table width="70%" border="0" cellpadding="10" cellspacing="0">
    <tr>
      <td>
        <table width="100%" border="0">
          <tr>
            <td width="20%"><div align="center"><img src="<?php echo HOTEL_LOGO; ?>" width="150" height="80" /></div></td>
            <td width="80%" align="left" style="padding-left:50px;"><div style="text-transform:uppercase"><?php echo HOTEL_NAME;?></div>
              <div>Add : <?php echo HOTEL_ADDRESS; ?></div>
              <div>Tel : <?php echo HOTEL_PHONE;?>* Fax : <?php echo HOTEL_FAX;?></div>
              <div>E-mail : <?php echo HOTEL_EMAIL;?></div></td>
          </tr>
        </table>
        <?php if(Form::$current->is_error()) { echo Form::$current->error_messages(); }?>
        <table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="5px">
          <tr>
            <th scope="col" style="width: 50%;">
                <div align="left">
                <?php echo Portal::language('room_no');?>: <span style="font-size: 24px; color: red; margin-right: 30px;"><?php echo $this->map['room_name'];?></span>
                <input  name="room_id" id="room_id" / type ="hidden" value="<?php echo String::html_normalize(URL::get('room_id'));?>">
                
                <?php echo Portal::language('code_hand');?>: <input  name="code" id="code" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                </div>
            </th>            
            <th scope="col" style="width: 150px;">
            <?php echo Portal::language('time');?>: <?php echo $this->map['date'];?><?php 
				if(($this->map['lastest_edited_time']))
				{?> | <?php echo Portal::language('lastest_edited_time');?> :<?php echo $this->map['lastest_edited_time'];?>
				<?php
				}
				?>
            </th>
            <th style="width: 30%;" >
                	<span align="top" style="padding-top: 10px;"><?php echo Portal::language('note');?>:</span><br/><textarea  name="note" id="note" rows="" cols="20" style="width: 98%;"><?php echo String::html_normalize(URL::get('note',''.$this->map['note']));?></textarea>
                </th>
          </tr>
          <tr style="width: 100%;">
            <td colspan="3">
                <table style="width: 100%;" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="5px">
                    <tr class="w3-light-gray" style="width: 100%;">
                      	<th><?php echo Portal::language('stt');?></th>
                        <th nowrap="nowrap"><?php echo Portal::language('equipment_id');?></th>
                        <th nowrap="nowrap" style="width: 40%;"><?php echo Portal::language('equipment_name');?></th>
                        <th align="center"><?php echo Portal::language('price');?></th>
                        <th class="td_quantity" align="center" nowrap="nowrap"><?php echo Portal::language('quantity');?></th>
                        <th class="td_quantity" align="center" nowrap="nowrap"><?php echo Portal::language('edit_quantity');?></th>
                        <td class="td_total_quantity" align="center" width="50px"><?php echo Portal::language('total_quantity');?></td>
                        <th align="center"><?php echo Portal::language('amount');?></th>
                    </tr>
            		  
                      <!--IF:equipment(isset($this->map['equipment']) and is_array($this->map['equipment']))-->
                      <?php $i=1; ?>
                      <?php if(isset($this->map['equipment']) and is_array($this->map['equipment'])){ foreach($this->map['equipment'] as $key1=>&$item1){if($key1!='current'){$this->map['equipment']['current'] = &$item1;?>
                    <tr>
                        <td><?php echo $i++; ?><input type="hidden" name="equipment['<?php echo $this->map['equipment']['current']['id'];?>'][product_id]" id="equipment_<?php echo $this->map['equipment']['current']['id'];?>" value="<?php echo $this->map['equipment']['current']['id'];?>" /></td>
                        <td><?php echo $this->map['equipment']['current']['id'];?></td>
                        <td><?php echo $this->map['equipment']['current']['name'];?></td>
                        <td><input type="text" style="text-align:right; width:100px;" name="equipment['<?php echo $this->map['equipment']['current']['id'];?>'][price]" id="price_<?php echo $this->map['equipment']['current']['id'];?>" value="<?php echo System::display_number($this->map['equipment']['current']['price']); ?>" onkeyup="update('<?php echo $this->map['equipment']['current']['id'];?>');" class="input_number" /></td>
                        <td class="td_quantity"><input type="text" style="text-align:right; width:80px;"  name="equipment['<?php echo $this->map['equipment']['current']['id'];?>'][quantity]" id="quantity_<?php echo $this->map['equipment']['current']['id'];?>" value="<?php echo $this->map['equipment']['current']['quantity'];?>" onkeyup="update('<?php echo $this->map['equipment']['current']['id'];?>');" class="input_number" /></td>
                        <td class="td_quantity"><input type="text" style="text-align:right; width:80px;"  name="equipment['<?php echo $this->map['equipment']['current']['id'];?>'][change_quantity]" id="change_quantity_<?php echo $this->map['equipment']['current']['id'];?>" value="<?php echo $this->map['equipment']['current']['change_quantity'];?>" onkeypress="return isNumberKey(event)" onkeyup="if(parseInt(jQuery(this).val()) + parseInt(jQuery('#quantity_<?php echo $this->map['equipment']['current']['id'];?>').val()) <0){alert('tổng số lượng không được nhỏ hơn không');jQuery(this).val(0);}update('<?php echo $this->map['equipment']['current']['id'];?>');" /></td>
                        <td class="td_total_quantity" align="center"><span id="span_total_quantity_<?php echo $this->map['equipment']['current']['id'];?>" class="span_total_quantity"><?php echo($this->map['equipment']['current']['quantity'] + $this->map['equipment']['current']['change_quantity'])?> </span></td>
                        <td><input type="text" style="text-align:right; width:150px;" name="equipment['<?php echo $this->map['equipment']['current']['id'];?>'][amount]" id="amount_<?php echo $this->map['equipment']['current']['id'];?>" readonly="readonly" value="<?php echo System::display_number($this->map['equipment']['current']['amount']); ?>" /></td>
                    </tr>
                      <?php }}unset($this->map['equipment']['current']);} ?>          
                </table>
                <input type="hidden" value="" name="change_quan" id="change_quan" style="width:40px;text-align:center"/>  
            </td>
          </tr>   
        <tr bordercolor="#FFFFFF" style="border-top:1px solid #999999;"><td colspan="3">&nbsp;</td></tr>
        <tr bordercolor="#FFFFFF">
            <td  colspan="2" align="right" style="padding-right:10px;"><strong><?php echo Portal::language('total_before_tax');?>:</strong></td>
            <td align="right"><span id="span_total_before_tax"></span><input type="hidden" name="total_before_tax" id="total_before_tax" readonly="" style="text-align:right; width:150px;border:none; border-bottom:1px dashed #999999;" /></td>
        </tr>
        <tr bordercolor="#FFFFFF">
            <td  colspan="2" align="right" style="padding-right:10px;"><strong><?php echo Portal::language('tax_rate');?>:</strong>( <span id="span_tax_rate"><?php echo $this->map['tax_rate'];?></span><input  name="tax_rate" type="text" id="tax_rate" style="width:20px; border:none" value="<?php echo $this->map['tax_rate'];?>" onchange="total_calculate();" />%)</td>
            <td align="right"><span id="tax"></span></td>
        </tr>
        <tr bordercolor="#FFFFFF">
            <td colspan="2" align="right" style="padding-right:10px;"><strong><?php echo Portal::language('total');?>:</strong></td>
            <td align="right"><span id="span_total"></span><input type="hidden" name="total" id="total" readonly="" style="text-align:right; width:150px;border:none; border-bottom:1px dashed #999999;" /></td>
        </tr>
  </table>
  </td>
  </tr>
  </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
	function update(id){
        if($('change_quan').value =='')
        {
            $('change_quan').value = 0;
        }
        if(parseInt(jQuery('#change_quantity_'+id).val()) + parseInt(jQuery('#quantity_'+id).val()) <0){
            alert('tổng số lượng không được nhỏ hơn không');
            jQuery('#quantity_'+id).val(0);
            jQuery('#change_quantity_'+id).val(0);
            }
        $('change_quan').value += Math.abs(to_numeric(getElemValue('change_quantity_'+id)));
          
        $('span_total_quantity_'+id).innerText=to_numeric(getElemValue('quantity_'+id)) + to_numeric(getElemValue('change_quantity_'+id));
		var amount = to_numeric(getElemValue('price_'+id))*(to_numeric(getElemValue('quantity_'+id)) + to_numeric(getElemValue('change_quantity_'+id))  );
		$('amount_'+id).value=number_format(roundNumber(amount,2));
		total_calculate();
	}
	function total_calculate(){
	    var chang_quan = 0;
        var equipment = <?php echo String::array2js($this->map['equipment'])?>;
        for (var item in equipment) 
        {
            chang_quan += Math.abs(to_numeric(getElemValue('change_quantity_'+equipment[item]['id'])));
        }
        
        $('change_quan').value = chang_quan;
           
		var total = 0;
		jQuery('input[id^=amount]').each(function(){
			total += to_numeric(this.value);
		});		
		$('span_total_before_tax').innerText = number_format(roundNumber(total,2));
        $('total_before_tax').value = number_format(roundNumber(total,2));
		$('tax').innerText = number_format(roundNumber(total*getElemValue('tax_rate')/100,2));	
		$('total').value = number_format(roundNumber(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('tax')),2));
        $('span_total').innerText = number_format(roundNumber(to_numeric(getElemValue('total_before_tax'))+to_numeric(getElemValue('tax')),2));
	
	}
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && ((charCode != 45 && charCode < 48) || charCode > 57))
            return false;
        return true;
    } 
	total_calculate();
    var last_time = <?php echo $this->map['last_time'];?>;
    function check_save()
    {
        <?php echo 'var id_check = '.Url::get("id").';';?> 
        <?php echo 'var block_id = '.Module::block_id().';';?>
        jQuery.ajax({
    				url:"form.php?block_id="+block_id,
    				type:"POST",
                    dataType: "json",
    				data:{check_last_time:1,id:id_check,last_time:last_time},
    				success:function(html)
                    {
                        if(html['status']=='error')
                        {
                            alert('RealTime:\n Lưu ý, Hóa đơn đền bù này đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                            return false;
                        }
                        else
                        {
                            if(jQuery('#change_quan').val() !=''){
                                    if(jQuery('#change_quan').val()>0 && jQuery('#note').val()==''){
                                        //alert('bạn chưa nhập ghi chú');
                                        return false;
                                    }else{
                                        return true;
                                    }
                            }else{
                                return true;
                            }
                        }
    				}
    	});
        if(jQuery('#change_quan').val() !=''){
            if(jQuery('#change_quan').val()>0 && jQuery('#note').val()==''){
                alert('bạn chưa nhập ghi chú');
                return false;
            }
        }
        AddEquipmentInvoiceForm.submit();
    }
</script>
<style>
@media print
{
    .td_quantity{display:none}
    .button-medium{display:none}
    .button-medium-save{display:none}
    #tax_rate{display: none;}
    .td_total_quantity{display: inline;}
    .span_total_quantity{display: inline}
    #span_tax_rate{display: inline;}
}
</style>