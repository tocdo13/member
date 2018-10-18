<style>
.tdtitle
{
	font-size: 12px;
	font-weight:bold;
	text-transform:uppercase;
}
.laundry_input
{
	width:25px; text-align:center;
	font-size:13px;
	margin-bottom:0px;
}
#top{
    width:100%;
    overflow:hidden;
    clear:both;
}
#top-left{
    width: 150px;
    float:left;
}
#top-right{
    width: 300px;
    float:left;
    margin-top:20px;
}
</style>

<?php [[=current_time=]]; ?>
<form name="AddLaundryInvoiceForm" method="post">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
		<?php if(Form::$current->is_error()){ echo Form::$current->error_messages();}?>
		
		<tr>
			<td  align="left">
                <div id="top">
                    <div id="top-left">
                        <img src="<?php echo HOTEL_LOGO; ?>" width="150px" align="middle"/>
                    </div>
                    <div id="top-right">
                        <font style="font-size: 12px; font-weight:bold; text-transform:uppercase"><?php echo HOTEL_NAME; ?></font>
                        <div>Địa chỉ : <?php echo HOTEL_ADDRESS; ?></div>
                        <div>Tel : <?php echo HOTEL_PHONE;?>* Fax : <?php echo HOTEL_FAX;?></div>
                        <div>E-mail : <?php echo HOTEL_EMAIL;?></div>
                    </div>
                </div>  
            </td>
			
			<td align="right" nowrap="nowrap">
                <input name="save" type="button" id="save" value="[[.save.]]" class="w3-btn w3-blue w3-text-white" onclick="check_err();" />
                <?php if(!Url::get('fast')){ ?>
                <input type="button" value="[[.back.]]" onclick="window.location = '<?php echo Url::build_current();?>';" class="w3-btn w3-green w3-text-white"/>
                <?php } ?>
            </td>
		</tr>
        
        
        <tr align="center">
            <td>
				<div style="height: 50px; padding-top:20px;padding-left:230px;">	
					<font style="font-size: 24px; font-weight:bold; text-transform:uppercase;margin-top:20px;">[[.laundry_invoice.]]</font>
				</div>
			</td>
        </tr>
       
        <table>
    		<tr>
    			<td colspan="3" align="left" style="padding-top:7px;">
    				<table cellpadding="10" cellspacing="0" border="0">
    					<tr height="30px">
    						<td nowrap="nowrap">[[.room_name.]] :</td>
    						<td>
    							 <select name="reservation_room_id" id="reservation_room_id" onchange="this.form.form_block_id.value=0;this.form.submit();" style="width:100px"></select> <input name="group_payment" type="checkbox" id="group_payment" value="1" style="display:none;" />
    						</td>
    					</tr>
    					<tr>
    						<td nowrap="nowrap">[[.time.]] :</td>
    						<td>[[|current_time|]]</td>
    						<td>[[.code_hand.]]: <input name="code" type="text" id="code" style="width:70px;"  autocomplete="off" /></td>
    					</tr>
    				</table>
    			</td>
    		</tr>
            
    		<tr>
    			<td colspan="3" class="data_title"><hr color="#000000" size="1"/></td>
    		</tr>
		</table>

        <table width="100%" cellpadding="0" border="0" style="margin-bottom: 10px;">
					<tr>
						<!--<td width="50%"><input name="instruction[]" value="REGULAR_SERVICE" type="checkbox"/> <span style="text-transform:uppercase">[[.regular_service.]]</span></td>-->
						<td width="200px">
                            <div style="margin-left: 10px;">
                            <input name="is_express_rate" id="is_express_rate" value="EXPRESS" type="checkbox"  autocomplete="off" onclick="check_express_rate(); calculate();"/> <span style="text-transform:uppercase">[[.express.]]</span>
                            <br />
                            [[.note.]]
                            <br />
                            <span style="color: red; font-style: italic;">(*)[[.quantity_include_promotion.]]</span>
                            </div>
														
						</td>
                        <td>
                        <textarea name="note" style="width:400px" rows="3" ></textarea>
                        </td>
					</tr>
					<!--<tr>
						<td width="50%"><input name="instruction[]" value="SHIRTS_ON_HANGER" type="checkbox"/><span style="text-transform:uppercase">[[.shirts_on_hanger.]]</span></td>
						<td width="50%"><input name="instruction[]" value="SHIRTS_FOLDED" type="checkbox"/><span style="text-transform:uppercase">[[.shirts_folded.]]</span></td>
					</tr>
					<tr>
						<td width="50%"><input name="instruction[]" value="NO_STARCH" type="checkbox"/><span style="text-transform:uppercase">[[.no_starch.]]</span></td>
						<td width="50%"><input name="instruction[]" value="LIGHT_STARCH" type="checkbox"/><span style="text-transform:uppercase">[[.light_starch.]]</span></td>
					</tr>
					<tr>
						<td colspan="2">[[.note.]]</td>
					</tr>
                    <tr>
						<td colspan="2" style="color: red; font-style: italic;">(*)[[.quantity_include_promotion.]]</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-right:15px;"><textarea name="note" style="width:100%" rows="8"></textarea></td>
					</tr>-->
        </table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
            <td width="90%" valign="top" align="center">
				<table border="1" width="100%" bordercolor="#000000" cellpadding="5">
				    <tr height="18px">
						<td rowspan="2" width="25%">
							<span class="tdtitle">[[.article.]]</span>
						</td>
						<!--LIST:categories-->
						<td colspan="3" align="center"><span class="tdtitle"><?php if(Portal::language()==1){?>[[|categories.name|]]<?php }else{?>[[|categories.name_en|]]<?php }?></span></td>
						<!--/LIST:categories-->
						<td rowspan="2" width="100" align="right"><span class="tdtitle">[[.total.]]</span></td>
					</tr>
					<tr height="18px">						
						<!--LIST:categories-->
						<td align="center"><b>[[.price.]]</b></td>
						<td align="center"><b>[[.quantity.]]</b></td>
                        <td align="center"><b>[[.promotion.]]</b></td>
						<!--/LIST:categories-->
					</tr>
                    
					<!--LIST:items-->
					<tr height="22px">
						<td align="left">
							<span style="margin-left:5px; font-weight:bold">[[|items.product_name|]]</span>
						</td>
						<?php $i=1; ?>
						<!--LIST:items.child-->
						<td align="right" width="80px">
							<!--IF::check_price1(isset([[=items.child.price=]]) and isset([[=items.child.product=]]))-->
                            <?php if (substr([[=items.product_key=]],0,13) != 'LAUNDRY_OTHER')
                            {
                            ?>
							<span id="price_[[|items.product_key|]]_<?php echo $i; ?>">[[|items.child.price|]]</span>
							<input name="services[[[|items.child.product|]]][price]" type="hidden" value="[[|items.child.price|]]" autocomplete="off"/>
	                       <?php } ?>
                           <?php if (substr([[=items.product_key=]],0,13) == 'LAUNDRY_OTHER')
                           {
                           ?>
                           <span id="price_[[|items.product_key|]]_<?php echo $i; ?>" style="display: none;;">[[|items.child.price|]]</span>
							<input class="input_number laundry_input" style="width: 70px;" name="services[[[|items.child.product|]]][price]" value="[[|items.child.price|]]" autocomplete="off" onkeyup="jQuery('#price_[[|items.product_key|]]_<?php echo $i; ?>').val(this.value);calculate_total('[[|items.product_key|]]');"/>
                           <?php }?>
                            <!--/IF::check_price1-->
                            
						</td>
						<td align="center" width="80px">
							<!--IF:check_product_1(isset([[=items.child.product=]]))-->
                            <?php if (substr([[=items.product_key=]],0,13) == 'LAUNDRY_OTHER')
                            { ?>
							<input class="input_number laundry_input" maxlength="5" name="services[[[|items.child.product|]]][quantity]" type="text" id="quantity_[[|items.product_key|]]_<?php echo $i; ?>" value="<?php echo Url::get("services[[[=items.child.product=]]][quantity]")?Url::get("services[[[=items.child.product=]]][quantity]"):0;?>"  onkeyup="calculate_total('[[|items.product_key|]]');" <?php if(([[=items.child.price=]]=='0') &&(substr([[=items.product_key=]],0,13) != 'LAUNDRY_OTHER')){?>readonly="readonly" style="background-color:#F1F1F1;"<?php }?> />
							<?php }else{?>
                            <input class="input_number laundry_input" maxlength="5" name="services[[[|items.child.product|]]][quantity]" type="text" id="quantity_[[|items.product_key|]]_<?php echo $i; ?>" value="<?php echo Url::get("services[[[=items.child.product=]]][quantity]");?>" autocomplete="off" onkeyup="calculate_total('[[|items.product_key|]]');" <?php if(([[=items.child.price=]]=='0') &&(substr([[=items.product_key=]],0,13) != 'LAUNDRY_OTHER')){?>readonly="readonly" style="background-color:#F1F1F1;"<?php }?> />
                            <?php }?>
                            <!--/IF:check_product_1-->
						</td>
                        <td align="center" width="80px">
							<!--IF:check_product_1(isset([[=items.child.product=]]))-->
							<input class="input_number laundry_input" maxlength="5" name="services[[[|items.child.product|]]][promotion]" type="text" id="promotion_[[|items.product_key|]]_<?php echo $i; ?>" value="<?php echo Url::get("services[[[=items.child.product=]]][promotion]");?>" autocomplete="off" onkeyup="calculate_total('[[|items.product_key|]]');" <?php if(([[=items.child.price=]]=='0')&&(substr([[=items.product_key=]],0,13) != 'LAUNDRY_OTHER')){?>readonly="readonly" style="background-color:#F1F1F1;"<?php }?> />
							<!--/IF:check_product_1-->
						</td>
						<?php $i++; ?>
						<!--/LIST:items.child-->
						<td align="right"><span id="total_[[|items.product_key|]]"></span></td>
					</tr>
					<!--/LIST:items-->
				</table>
            </td>
			
		</tr>
        
		<tr>
			<td colspan="3"><hr color="#000000" size="1"/></td>
		</tr>
        
		<tr>
			<td colspan="3" valign="top">
                <table cellpadding="0" width="100%" border="1" bordercolor="#000000" style="border:1px solid #000000; border-collapse:collapse; margin-bottom:7px;">
					<tr height="24px" valign="middle">
						<td width="24%" style="padding-left:4px;">[[.subtotal.]]</td>
						<td width="12%" align="right" style="padding-right:7px;">
							<span id="subtotal"></span>
							<input name="subtotal" type="hidden" id="id_subtotal"/>
						</td>
						<td rowspan="6" width="63%">
							<div style="line-height: 18px; margin-left:10px;">
							* Call before 10.00 AM for same day return.<br />
							* Collection after 10.00 PM, next day delivery at 5.00 PM.<br />
							* For 4 hours Express Service, surcharge will be added.<br />
							* <?php echo HOTEL_NAME; ?> is environment friendly and your laundry will only be wrapped into plastic bags, upon request.
							<br /><b>Notice:</b><br />
							* Please fill in the list in full and sign. Incase of discrepancy on count the Hotel count must be accepted as correct.<br />
							* Any claim must be reported with this list within 24 hours.<br />
							* The Hotel's liabilities for either loss or damage will not exceed the amount of 10 times the Laundry Charges.<br />
							* The Hotel can not be responsible for shrinkage or fastness of color, nor for valuables left in or gaments.<br />
							* All prices are subject to Goverment Tax and 5% Service Charge and may change without prior notice.<br />
							</div>
						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
							<input  name="discount" id="discount" type="text" onkeyup="calculate();" autocomplete="off" style="width:25px; text-align:right" value="[[|discount|]]" class="input_number laundry_input"/>
							% [[.discount.]]
						</td>
						<td align="right" style="padding-right:7px;">
							<span id="total_discount"></span>
							<input name="total_discount" type="hidden" id="id_total_discount" />
						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
							<input name="express_rate" id="express_rate" type="text" onkeypress=" return check_has_express_rate();" autocomplete="off" onkeyup="change_express_rate(this);" style="width:25px; text-align:right" value="0" class="input_number laundry_input"/>
							% [[.express_service_surcharge.]]
						</td>
						<td align="right" style="padding-right:7px;">
							<span id="express"></span>
							<input name="express" type="hidden" id="id_express"/>
						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
							<!--<input name="is_service_rate" id="is_service_rate" type="checkbox" onclick="check_service_rate(); calculate();" <?php //echo [[=service_rate=]]!=0?' checked':'';?>>-->
							<input name="service_rate" style="width:25px; text-align:right" type="text" id="service_rate" autocomplete="off" onkeyup=" calculate();" value="[[|service_rate|]]" class="input_number laundry_input"/>
							% [[.service_charge.]]
						</td>
						<td align="right" style="padding-right:7px;">
							<span id="service_charge"></span>
							<input name="service_charge" type="hidden" id="id_service_charge"/>
						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
							<!--<input name="is_tax_rate" id="is_tax_rate" type="checkbox" onclick="check_tax_rate(); calculate();" <?php //echo [[=tax_rate=]]!=0?' checked':'';?>>-->
							<input name="tax_rate" type="text" id="tax_rate" style="width:25px; text-align:right" onkeyup=" calculate();" autocomplete="off" value="[[|tax_rate|]]" class="input_number laundry_input"/>
							% [[.goverment_tax.]]
						</td>
						<td align="right" style="padding-right:7px;">
							<span id="tax"></span>
							<input name="tax" type="hidden" id="id_tax"/>
						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
                            [[.grant_total.]] (<?php echo HOTEL_CURRENCY; ?>)
                        </td>
						<td align="right" style="padding-right:7px;">
							<span id="grant_total"></span>
							<input name="grant_total" type="hidden" id="id_grant_total"/>
						</td>
                    </tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<p></p>
</form>
<script>
var room_array=
                {
                	<?php $first = true; ?>
                	<!--LIST:reservations-->
                	<?php if($first){ $first = false;} else { echo ',';} ?>
                		'[[|reservations.id|]]':{
                			'id':'[[|reservations.id|]]',
                			'name':'<?php echo addslashes([[=reservations.name=]])?>',
                			'agent_name':'<?php echo addslashes([[=reservations.agent_name=]]); ?>'
                		}
                	<!--/LIST:reservations-->
                }
var items_array=
                {
                <?php $first = true; ?>
                <!--LIST:items-->
                	<?php if($first){ $first = false;} else { echo ',';} ?>
                	'[[|items.product_key|]]':{
                		'id':'[[|items.product_key|]]',
                		'total':0
                	}
                <!--/LIST:items-->
                }
                
function calculate_total(index)
{
	var total=0;	
	var $item_nums = <?php echo sizeof([[=categories=]]);?>;
    for(var $i=1; $i<=$item_nums; $i++)
    {
        //So luong km nho hon so luong thuc
        if(to_numeric(getElemValue('promotion_'+index+'_'+$i)) != '')
        {
                console.log('1111111111111'); return false;
                if(to_numeric(getElemValue('promotion_'+index+'_'+$i)) > to_numeric(getElemValue('quantity_'+index+'_'+$i)))
                {
                    alert('[[.promotion_must_less_than_quantity.]]');
                    $('promotion_'+index+'_'+$i).value = ''; 
                }
            
        }
		if($('quantity_'+index+'_'+$i) && is_numeric(getElemValue('quantity_'+index+'_'+$i)))
		{
			if(to_numeric(getElemValue('promotion_'+index+'_'+$i)) == '')
            {
				var promotion = 0;
			}
            else
            {
				var promotion = to_numeric(getElemValue('promotion_'+index+'_'+$i));
			}
			var quantity = to_numeric(getElemValue('quantity_'+index+'_'+$i)) - promotion;
			total += quantity*to_numeric(getElemValue('price_'+index+'_'+$i));
		}
	}
	items_array[index]['total'] = roundNumber(total,2);
	$('total_'+index).innerHTML = number_format(total);
	//tinh tong
	calculate();
}

function calculate()
{
    //Tinh tong hoa don
    <?php if(NET_PRICE_LAUNDRY == 1) {?>
       var subtotal = 0;
       for(var i in items_array)
   	   {
    		if(is_numeric(items_array[i]['total']))
    		{
    			subtotal += parseFloat(items_array[i]['total']);
                
    		}
       }
       var tax = subtotal - (subtotal *100/ (to_numeric($('tax_rate').value) +100));
       var service_charge = roundNumber(((subtotal - tax) - ((subtotal - tax)*100/(to_numeric($('service_rate').value)+100)) ),2);
       
       // tổng chưa thuế phí
       subtotal = subtotal - tax - service_charge;
   	   $('subtotal').innerHTML = number_format(roundNumber(subtotal,2));
       $('id_subtotal').value = number_format(roundNumber(subtotal,2));
       
       //% giam gia
       var discount = roundNumber((subtotal*to_numeric($('discount').value)/100),2);
   	   $('total_discount').innerHTML = number_format(discount);
   	   $('id_total_discount').value = number_format(discount);
       subtotal -= discount;
       
       //Phi giat nhanh
       var express = roundNumber((subtotal*to_numeric($('express_rate').value)/100),2);
   	   $('express').innerHTML = number_format(express);
   	   $('id_express').value = number_format(express);
       subtotal += express;
       
       //Phi dich vu
   	   var service_charge = subtotal*to_numeric($('service_rate').value)/100;
   	   $('service_charge').innerHTML = number_format(service_charge);
   	   $('id_service_charge').value = number_format(service_charge);
       
       //Vat
       var tax = (subtotal + service_charge)*to_numeric($('tax_rate').value)/100;
   	   $('tax').innerHTML = number_format(roundNumber(tax,2));
   	   $('id_tax').value = number_format(tax);
       
       var grant_total = subtotal+ service_charge + tax;
       // tổng thuế phí
       $('grant_total').innerHTML = number_format(Math.round(grant_total)); //daund lam tron
       $('id_grant_total').value = number_format(Math.round(grant_total,2)); //daund lam tron
    <?php 
    }
    else
    {
    ?>
      var subtotal=0;
    	for(var i in items_array)
    	{
    		if(is_numeric(items_array[i]['total']))
    		{
    			subtotal += parseFloat(items_array[i]['total']);
    		}
    	}
    	$('subtotal').innerHTML = number_format(subtotal);
    	$('id_subtotal').value = number_format(roundNumber(subtotal,2));
    	
        //% giam gia
    	var discount = roundNumber(subtotal*to_numeric($('discount').value)/100,2);
    	$('total_discount').innerHTML = number_format(discount);
    	$('id_total_discount').value = number_format(discount);
    	subtotal -= discount;
    	
        //Phi giat nhanh
    	var express = roundNumber(subtotal*to_numeric($('express_rate').value)/100,2);
    	$('express').innerHTML = number_format(express);
    	$('id_express').value = number_format(express);
    	
        //Phi dich vu
    	var service_charge = roundNumber((subtotal+express)*to_numeric($('service_rate').value)/100,2);
    	$('service_charge').innerHTML = number_format(service_charge);
    	$('id_service_charge').value = number_format(service_charge);
    	
        //Vat
    	var tax = ( to_numeric(service_charge) + subtotal + to_numeric($('express').innerHTML ) ) * to_numeric($('tax_rate').value)/100;
    	$('tax').innerHTML = number_format(roundNumber(tax,2));
    	$('id_tax').value = number_format(tax);
    	
    	var grant_total = subtotal+to_numeric($('express').innerHTML)+to_numeric($('service_charge').innerHTML)+to_numeric($('tax').innerHTML);
    	$('grant_total').innerHTML = number_format(Math.round(grant_total,2)); //daund lam tron
    	$('id_grant_total').value = number_format(Math.round(grant_total,2));  //daund lam tron
    <?php } ?>
	
}
function check_service_rate()
{
	if($('is_service_rate').checked)
	{
		$('service_rate').value=5;
	} 
	else
	{
		$('service_rate').value=0;	
	}
}
function check_tax_rate()
{
	if($('is_tax_rate').checked)
	{
		$('tax_rate').value=10;
	} 
	else
	{
		$('tax_rate').value=0;	
	}
}
var express_rate = [[|express_rate|]];
function check_express_rate()
{
	if($('is_express_rate').checked)
	{
        $('express_rate').innerHTML=express_rate;
		$('express_rate').value=express_rate;
	} 
	else
	{
        $('express_rate').innerHTML=0;
		$('express_rate').value=0;	
	}
}

//neu su dung giat nhanh thi moi chon dc phi giat nhanh
function check_has_express_rate()
{
    
	if(!$('is_express_rate').checked)
	{
        $('express_rate').innerHTML=0;
        $('express_rate').value=0;	
        return false;
	}
    return true; 
    
}

function change_express_rate(obj)
{
	express_rate = obj.value;
	if($('is_express_rate').checked)
	{
		calculate();
	}
}
function check_err()
{
    if(jQuery('#reservation_room_id').val()== null)
    {
        alert('ban phai nhap ten phong');
        return false;
    }
    else{
        jQuery('#save').hide();
        AddLaundryInvoiceForm.submit();
    }
}
</script>
