<!--nhap kho tu nha cc-->
<link rel="stylesheet" href="skins/default/report.css"/>

<div class="product-report-bound">
<table width="100%" id="export">
        <tr>
            <td>
	<div style="width:720px;padding:10px;text-align:center;font-size:14px;">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left" width="45%">
					<strong><?php echo HOTEL_NAME;?></strong><br />
        			Địa chỉ: <?php echo HOTEL_ADDRESS;?><br/>
        			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
        			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
				<td align="right" width="45%"><strong>[[.supplier.]]: [[|supplier|]]</strong><br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>
		</table>
	</div>
    
    <div style="width:720px;">
    	<div style="text-align:center;">
    		<div class="report_title">[[.exported_remain_report_from_supplier.]] </div><br/>
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
    			<!--IF:back_products([[=back_products=]])-->
    			<tr>
    				<td colspan="10"><strong>H&agrave;ng h&oacute;a xu&#7845;t tr&#7843; l&#7841;i </strong></td>
    			</tr>
    			<?php $create_date = '';?>
    			<!--LIST:back_products-->
    			<?php if($create_date != [[=back_products.create_date=]]){$create_date = [[=back_products.create_date=]];?>
    			<tr bgcolor="#EFEFEF">
    				<td colspan="9" class="category-group">[[|back_products.create_date|]]</td>
    				<td class="category-group" align="right"><strong class="change_numTr"><?php echo System::display_number([[=back_arr_by_date=]][[[=back_products.create_date=]]]);?></strong></td>
    			</tr>
    			<?php }?>
    			<tr bgcolor="white">
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|back_products.i|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|back_products.bill_number|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|back_products.invoice_number|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column">[[|back_products.supplier|]]</td>
                    <td align="left" nowrap class="report_table_column">[[|back_products.product_id|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column"> [[|back_products.name|]]</td>
                    <td nowrap align="center" class="report_table_column">[[|back_products.unit_name|]]</td>
                    <td align="right" nowrap class="report_table_column">[[|back_products.quantity|]]</td>
                    <td align="right" nowrap="nowrap" class="report_table_column" ><span class="change_numTr">[[|back_products.price|]]</span></td>
                    <td align="right" nowrap class="report_table_column" ><span class="change_numTr">[[|back_products.amount|]]</span></td>
    			</tr>
    			<!--/LIST:back_products-->
    			<!--/IF:back_products-->
                <tr bgcolor="white">
                    <td colspan="9" align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[.total.]] </strong></td>
                    <!--<td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong>[[|total_quantity|]]</strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"></td>-->
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong class="change_numTr">[[|total_amount|]]</strong></td>
                </tr>
    		</table>
    	</div>
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
    </td>
        </tr>
    </table>
</div>
<script>
 jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        //jQuery('.class_none').remove();
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })
</script>