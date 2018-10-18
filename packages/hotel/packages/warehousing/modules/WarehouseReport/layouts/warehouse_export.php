<!--Bao cao chuyen kho, xuat kho cho bo phan-->
<link rel="stylesheet" href="skins/default/report.css"/>
<div class="product-report-bound">
	<div style="width:;padding:10px;text-align:center;font-size:14px;">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left">
					<strong><?php echo HOTEL_NAME;?></strong><br />
					Địa chỉ: <?php echo HOTEL_ADDRESS;?><BR>
					Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
					Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
				<!--td align="right"><strong>[[.warehouse.]]: [[|warehouse|]]</strong><br><br></td-->
				<td align="right"><strong>[[|warehouse|]]</strong><br>
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>
		</table>
	</div>
    <div style="width:;padding:10px;">
    <form name="WarehouseReportOptionsForm" method="post">
    <table width="100%" id="export">
    <tr>
        <td>
        
        <div style="text-align:center;">
        	<div class="report_title">[[.warehouse_export_report.]]</div>
        	<!--[[.warehouse_export_report.]]111</div-->
        	<br/>
        	[[.date_from.]] [[|date_from|]] [[.to.]] [[|date_to|]]<br/><br />
        </div>
        <div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></div>
        <div>
        	<table cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" width="100%" >
        		<tr valign="middle">
        			<th colspan="4" class="report_table_header" align="center">[[.product.]]</th>
        			<th colspan="4" class="report_table_header" align="center">[[.detail.]]</th>
                    <th rowspan="2" class="report_table_header" align="center">[[.note.]]</th>
        		</tr>
        		<tr>
                    <th width="20" rowspan="1" class="report_table_header" align="center">[[.no.]]</th>
                    <th width="100" rowspan="1" class="report_table_header" align="center">[[.code.]]</th>
                    <th width="200" rowspan="1" class="report_table_header" align="center">[[.name.]]</th>
                    <th width="100" rowspan="1" class="report_table_header" align="center">[[.unit.]]</th>
                    <th width="100" rowspan="1" class="report_table_header" align="center">[[.quantity.]]</th>
                    <th width="100" rowspan="1" class="report_table_header" align="center">[[.to_warehouse.]]</th>
                    <th width="150" rowspan="1" class="report_table_header" align="center">[[.price.]]</th>
                    <th width="150" rowspan="1" class="report_table_header" align="center">[[.amount.]]</th>
        		</tr>
        		<!--LIST:products-->
        		<tr bgcolor="white">
                    <td align="left" nowrap="nowrap" class="report_table_column"> [[|products.i|]] </td>
                    <td align="left" nowrap class="report_table_column">[[|products.product_id|]]</td>
                    <td align="left" nowrap="nowrap" class="report_table_column"> [[|products.name|]] </td>
                    <td align="center" nowrap class="report_table_column">[[|products.unit_name|]]</td>
                    <td align="right" nowrap class="report_table_column change_numTr">[[|products.quantity|]] </td>
                    <td align="center" nowrap class="report_table_column">[[|products.warehouse_name|]]</td>
                    <td align="right" nowrap="nowrap" class="report_table_column change_numTr">[[|products.price|]]</td>
                    <td align="right" nowrap class="report_table_column change_numTr">[[|products.amount|]]</td>
                    <td align="left" nowrap class="report_table_column">[[|products.note|]]</td>
        		</tr>
        		<!--/LIST:products-->
        		<tr bgcolor="white">
                    <td colspan="7" align="right" nowrap="nowrap" class="report_table_column"><strong>[[.total.]]</strong></td>
                    <td align="right" nowrap="nowrap" class="report_table_column"><strong class="change_numTr">[[|total_amount|]]</strong></td>
                    <td align="right" nowrap="nowrap" class="report_table_column">&nbsp;</td>
        	  </tr>
        	</table>
        </div>
        <div>
            <table  style="width: 1100px;" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td colspan="8" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center" width="45%">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right" width="45%"><em>[[.date.]]&nbsp;[[|day|]] [[.month.]]&nbsp;[[|month|]]&nbsp;[[.year.]]&nbsp;[[|year|]]&nbsp;</em></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center" ><strong>[[.creater.]]</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center"><strong>[[.warehouseman.]]</strong> </td>
                </tr>
            </table>
        </div>
        </td>
    </tr>
        </table>
        </form>
    </div>	
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