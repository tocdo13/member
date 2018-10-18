2222<!--nhap kho tu nha cc-->
<link rel="stylesheet" href="skins/default/report.css"/>
<div class="product-report-bound">
	<div style="width:720px;padding:10px;text-align:center;font-size:14px;">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left">
					<strong><?php echo HOTEL_NAME;?></strong><br />
        			Địa chỉ: <?php echo HOTEL_ADDRESS;?><br/>
        			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
        			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
				<td align="right"><strong>[[.supplier.]]: [[|supplier|]]</strong><br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>
		</table>
	</div>
    <div style="width:720px;">
    <table width="100%" id="export">
    <tr>
        <td>
    	<div style="text-align:center;">
    		<div class="report_title">[[.imported_exported_report_from_supplier.]] </div><br/>
    		[[.date_from.]] [[|date_from|]] [[.to.]] [[|date_to|]]<br/><br />
    	</div>
        <div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></div>
    	<div>
    		<table cellpadding="2" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
    			<tr valign="middle">
    				<th colspan="7">[[.product.]]</th>
    				<th colspan="3">[[.detail.]]</th>
    			</tr>
    			<tr>
                    <th width="20" rowspan="1">[[.no.]]</th>
                    <th width="100" rowspan="1">[[.bill_number.]]</th>
                    <th width="100" rowspan="1">[[.invoice_number.]]</th>
                    <th width="100" rowspan="1">[[.supplier.]]</th>
                    <th width="100" rowspan="1">[[.code.]]</th>
                    <th width="200" rowspan="1">[[.name.]]</th>
                    <th width="100" rowspan="1">[[.unit.]]</th>
                    <th width="100" rowspan="1">[[.quantity.]]</th>
                    <th width="150" rowspan="1">[[.price.]]</th>
                    <th width="150" rowspan="1">[[.amount.]]</th>
    			</tr>
    			<?php $create_date = '';?>
    			<!--LIST:products-->
    			<?php if($create_date != [[=products.create_date=]]){$create_date = [[=products.create_date=]];?>
    			<tr bgcolor="#EFEFEF">
    				<td colspan="7" class="category-group">[[|products.create_date|]]</td>
                    <td class="category-group" align="right"><strong><?php echo System::display_number([[=quantity_by_date=]][[[=products.create_date=]]]);?></strong></td>
                    <td class="category-group"></td>
    				<td class="category-group" align="right"><strong><?php echo System::display_number([[=arr_by_date=]][[[=products.create_date=]]]);?></strong></td>
    			</tr>
    			<?php }?>
    			<tr bgcolor="white">
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|products.i|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|products.bill_number|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|products.invoice_number|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|products.supplier|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|products.product_id|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column"> [[|products.name|]]</td>
                    <td nowrap align="center" class="report_table_column">[[|products.unit_name|]]</td>
                    <td align="right" nowrap="nowrap" class="report_table_column">[[|products.quantity|]]</td>
                    <td align="right" nowrap="nowrap" class="report_table_column change_num">[[|products.price|]]</td>
                    <td align="right" nowrap="nowrap" class="report_table_column change_num">[[|products.amount|]]</td>
    			</tr>
    			<!--/LIST:products-->
                <tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.total.]] </strong></td>
                    <!--<td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_quantity|]]</strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"></td>-->
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_amount|]]</strong></td>
                </tr>
                <!--IF:total_before_tax([[=commission=]] or [[=shipping_fee=]])-->
                <tr bgcolor="white">
                    <td colspan="9 align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.total_before_vat.]] </strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_before_tax|]]</strong></td>
                </tr>
                <!--/IF:total_before_tax-->
                <!--IF:commission([[=commission=]])-->
                <tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.total_commission.]] [[|commission|]]% </strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_commission|]]</strong></td>
                </tr>
                <tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.total_after_commission.]] </strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_after_commission|]]</strong></td>
                </tr>
                <!--/IF:commission-->
                <!--IF:shipping_fee([[=shipping_fee=]])-->
                <tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.shipping_fee.]] </strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|shipping_fee|]]</strong></td>
                </tr>
                <!--/IF:shipping_fee-->
                <!--IF:grand_total([[=commission=]] or [[=shipping_fee=]])--><tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.total_before_tax_commission.]] </strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_before_tax_commission|]]</strong></td>
                </tr>
                <tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.grand_total.]] </strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" id="grand_total" class="report_table_column"><strong>[[|grand_total|]]</strong></td>
                </tr>
                <!--/IF:grand_total-->
    		</table>
    	</div>
                </td>
            </tr>
        </table>
        <div>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" width="50%">&nbsp;</td>
                    <td align="right"><em>[[|day|]]/[[|month|]]/[[|year|]]&nbsp;</em></td>
                </tr>
                <tr>
                    <td align="center"><strong>[[.creater.]] </strong></td>
                    <td align="center"><strong>[[.warehouseman.]] </strong> </td>
                </tr>
            </table>
        </div>
    </div>	
</div>
<script>
 jQuery('#export_repost').click(function(){
         jQuery('.change_num').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
        })
        jQuery('.report_title').css('font-size', '24px');
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })

</script>