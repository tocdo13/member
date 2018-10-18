<script>full_screen();</script>
<style>
.tdtitle
{
	font-size: 12px;
	font-weight:bold;
	text-transform:uppercase;
}
@media print{
	   #export_excel{
	       display: none;
	   }
  }
</style>
<div style="width:980px; margin:auto;">
<input name="export_excel" id="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 100px; height: 23px"/>
<table id="Export" width="100%" cellpadding="0" cellspacing="0" border="0">
<form name="AddLaundryInvoiceForm" method="post">
<tr><td style="padding-left:10px;">
		<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
		<table cellpadding="0" width="100%" border="0">
		<tr>
			<td width="10%" id="imgs" align="center" valign="top"><img src="<?php echo HOTEL_LOGO; ?>" align="middle" width="100"></td>
			
<td width="80%" align="center">
				<div style="height: 50px;">
					<font style="font-size: 12px; font-weight:bold; text-transform:uppercase"><?php echo HOTEL_NAME; ?></font>
				</div>
				<div>
					<font style="font-size: 16px; font-weight:bold; text-transform: uppercase;">[[.laundry_invoice.]]</font>
				</div>
			</td>
			<td width="10%" align="center">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="padding-top:7px;">
				<table width="90%" cellpadding="0" cellspacing="0" border="0" style="text-align:left">
				<tr height="30px">
					<td nowrap="nowrap">[[.guest_name.]] : </td>
					<td width="60%">[[|customer_name|]]</td>
					<td width="35%">[[.room.]] : <span id="room_name" style="width: 20%">[[|room_name|]]</span></td>
				</tr>
				<tr>
					<td nowrap="nowrap">[[.code.]] : LD_[[|position|]]</td>
					<td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [[.date.]] :&nbsp; &nbsp; [[|date|]]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[[.time.]] : [[|hour|]]
					</td>
					<td>
						<strong>[[.code_hand.]] : [[|code|]]</strong>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" class="data_title"><hr color="#000000" size="1"></td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="69%" valign="top" align="center">
				<table id="tb_nomeric" border="1" width="100%" bordercolor="#000000" cellpadding="5">
					<tr height="18px">
						<td rowspan="2" width="25%">
							<span class="tdtitle"><b>[[.article.]]</b></span>
						</td>
						<!--LIST:categories-->
						<td colspan="3" align="center"><span class="tdtitle"><b>[[|categories.name|]]</b></span></td>
						<!--/LIST:categories-->
						<td rowspan="2"><b>[[.total.]]</b></td>
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
						<td align="left"><span style="margin-left:5px; font-weight:bold">[[|items.product_name|]]</span></td>
						<?php $i=1; ?>
						<!--LIST:items.child-->
						<td align="right" width="80px">
							<!--IF::check_price1(isset([[=items.child.price=]]) and isset([[=items.child.product=]]))-->
							<span class="to_numeric" id="price_[[|items.product_key|]]_<?php echo $i; ?>">[[|items.child.price|]]</span>
							<!--ELSE-->
							<span class="to_numeric" id="price_[[|items.product_key|]]_<?php echo $i; ?>"></span>
							<!--/IF::check_price1-->
						</td>
						<td align="center" width="80px">
							<!--IF:check_product_1(isset([[=items.child.product=]]))-->
							<?php echo isset([[=items.child.quantity=]])?[[=items.child.quantity=]]:'&nbsp;';?>
							<!--/IF:check_product_1-->
						</td>
                        <td align="center" width="80px">
							<!--IF:check_product_1(isset([[=items.child.product=]]))-->
							<?php echo isset([[=items.child.promotion=]])?[[=items.child.promotion=]]:'&nbsp;';?>
							<!--/IF:check_product_1-->
						</td>
						<?php $i++; ?>
						<!--/LIST:items.child-->
						<td><span class="to_numeric" id="total_[[|items.product_key|]]"><?php if([[=items.total=]]!=0){ echo System::display_number([[=items.total=]]); } ?></span></td>
					</tr>
					<!--/LIST:items-->
				</table>
			</td>
			<td width="1%"></td>
			<td width="30%" valign="top">
				<table width="100%" cellpadding="0" border="0">
					<tr>
						<!--<td width="50%"><input name="instruction[]" value="REGULAR_SERVICE" type="checkbox" <?php echo [[=REGULAR_SERVICE=]]!=0?' checked':'';?>> <span style="text-transform:uppercase">[[.regular_service.]]</span></td>-->
						<td colspan="2" width="10%">
							<input name="is_express_rate" id="is_express_rate" value="EXPRESS" type="checkbox" onclick="return false;" <?php echo Url::get('is_express_rate')==1?' checked':''; //echo [[=express_rate=]]!=0?' checked':'';?>/><span style="text-transform:uppercase">[[.express.]]</span>
						</td>
                        
					</tr>
					<tr>
						<!--<td width="50%"><input name="instruction[]" value="SHIRTS_ON_HANGER" type="checkbox" <?php echo [[=SHIRTS_ON_HANGER=]]!=0?' checked':'';?>><span style="text-transform:uppercase">[[.shirts_on_hanger.]]</span></td>
						<td width="50%"><input name="instruction[]" value="SHIRTS_FOLDED" type="checkbox" <?php echo [[=SHIRTS_FOLDED=]]!=0?' checked':'';?>> <span style="text-transform:uppercase">[[.shirts_folded.]]</span></td>-->
					</tr>
					<tr>
						<!--<td width="50%"><input name="instruction[]" value="NO_STARCH" type="checkbox" <?php echo [[=NO_STARCH=]]!=0?' checked':'';?>> <span style="text-transform:uppercase">[[.no_starch.]]</span></td>
						<td width="50%"><input name="instruction[]" value="LIGHT_STARCH" type="checkbox" <?php echo [[=LIGHT_STARCH=]]!=0?' checked':'';?>> <span style="text-transform:uppercase">[[.light_starch.]]</span></td>-->
					</tr>
					<tr>
						<td colspan="2">[[.note.]]</td>
					</tr>
                    <tr>
						<td colspan="2" style="color: red; font-style: italic;">(*)[[.quantity_include_promotion.]]</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="4" style="padding-right:15px;"><textarea name="note" style="width:100%" rows="8">[[|note|]]</textarea><span id="note"></span></td>
					</tr>
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
							<span class="to_numeric" id="subtotal">[[|subtotal|]]</span>						
                        </td>
						<td rowspan="6" width="63%">
							<div style="line-height: 18px; margin-left:10px;">
							* Call before 10.00 AM for same day return.<br />
							* Collection after 10.00 PM, next day delivery at 5.00 PM.<br />
							* For 4 hours Express Service, 50% surcharge will be added.<br />
							* <?php echo HOTEL_NAME; ?> is environment friendly and your laundry will only be wrapped into plastic bags, upon request.
							<br /><b>Notice:</b><br />
							* Please fill in the list in full and sign. Incase of discrepancy on count the Hotel count must be accepted as correct.<br />
							* Any claim must be reported with this list within 24 hours.<br />
							* The Hotel's liabilities for either loss or damage will not exceed the amount of 10 times the Laundry Charges.<br />
							* The Hotel can not be responsible for shrinkage or fastness of color, nor for valuables left in or gaments.<br />
							* All prices are subject to Goverment Tax and 5% Service Charge and may change without prior notice.<br />
							</div>						</td>
					</tr>
					<tr height="24px" valign="middle">
                        <td style="padding-left:4px;"> [[|discount|]]% [[.discount.]] </td>
                        <td align="right" style="padding-right:7px;">
                            <span class="to_numeric" id="service_charge">
                            <!--IF:total_discount([[=total_discount=]]!='0.00')-->
                            [[|total_discount|]]
                            <!--/IF:total_discount-->
                            </span> 
                        </td>
                    </tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">[[|express_rate|]]% [[.express_service_surcharge.]]</td>
						<td align="right" style="padding-right:7px;">
							<span class="to_numeric" id="express">
							<!--IF:check_express([[=express=]]!='0.00')-->
							[[|express|]]
							<!--/IF:check_express-->
							</span>
                        </td>
					</tr>
					<tr height="24px" valign="middle">
						<td  style="padding-left:4px;">
							[[|fee_rate|]]% [[.service_charge.]]						
                        </td>
						<td align="right" style="padding-right:7px;">
							<span class="to_numeric" id="service_charge">
							<!--IF:check_service_charge([[=service_charge=]]!='0.00')-->
							[[|service_charge|]]
							<!--/IF:check_service_charge-->
							</span>
                        </td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">[[|tax_rate|]]% [[.goverment_tax.]]</td>
						<td  align="right" style="padding-right:7px;">
							<span class="to_numeric" id="tax">
							<!--IF:check_tax([[=tax=]]!='0.00')-->
							[[|tax|]]
							<!--/IF:check_tax-->
							</span>
                        </td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">[[.grant_total.]] (<?php echo HOTEL_CURRENCY; ?>)</td>
						<td align="right" style="padding-right:7px;"><span class="to_numeric">[[|grant_total|]]</span></td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</form>
</table>
</div>
<script>
	//recalculate_housekeeping_invoice_detail();
    jQuery("#export_excel").click(function () {
    jQuery("textarea").each(function(){
        jQuery("#note").text(this.value);
        this.remove();
    });
    jQuery('.to_numeric').each(function(){
        var content = jQuery(this).html();
        if(content.trim()!="")
        {
            jQuery(this).html(to_numeric(jQuery(this).html().trim())); 
        }
    });
    //return;
    jQuery('#is_express_rate').remove();
    jQuery('#imgs').remove();
    jQuery("#Export").clone().find("img").remove();
    jQuery("#Export").battatech_excelexport({
        containerid: "Export"
       , datatype: 'table'
       , fileName: '[[.laundry_invoice.]]'
    });       
    
    
});
</script>