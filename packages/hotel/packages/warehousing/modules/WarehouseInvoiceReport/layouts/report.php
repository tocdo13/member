<!--Bao cao nhap xuat theo phieu-->
<style>
/*full m?n h?nh*/
.simple-layout-middle{width:100%;}
</style>
<link rel="stylesheet" href="skins/default/report.css"/>
<link rel="stylesheet" href="packages/hotel/packages/warehousing/skins/default/css/style.css"/>
<div class="product-report-bound">
	<div style="width:;padding:10px;text-align:center;font-size:14px;">	
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left">
					<strong><?php echo HOTEL_NAME;?></strong><br />
					Địa chỉ: <?php echo HOTEL_ADDRESS;?><br/>
					Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
					Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
                <td align="right">
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>
		</table>
	</div>
    <table width="100%" id="export">
        <tr>
            <td>
    <div style="width:;padding:10px;">
        <div style="text-align:center;">
        	<div class="report_title">[[.warehouse_invoice_report.]]</div>
        	<br/>
        	[[.date_from.]] [[|date_from|]] [[.to.]] [[|date_to|]]<br/><br />
        </div>
        <div style="text-align: center;"><p><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></p></div>
        <div>
        	<table cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" width="100%">
                <tr valign="middle" align="center">
                    <th style="width:10px;" class="report_table_header" rowspan="2">[[.stt.]]</th>
                	<th style="width:50px;" class="report_table_header" rowspan="2">[[.bill_number.]]</th>
                    <th style="width:70px;" class="report_table_header" rowspan="2">[[.date.]]</th><!--[[.create_date.]]</th-->
                    <th style="width:100px;" class="report_table_header" rowspan="2">[[.total_amount.]]</th>
                    <th style="width:160px;" class="report_table_header" rowspan="2">[[.description.]]</th><!--[[.note.]]</th-->
                    <th style="width:160px;" class="report_table_header" rowspan="2">[[.supplier_name.]]</th>
                    <th style="width:80px;" class="report_table_header" rowspan="2">[[.warehouse_name.]]</th>
                    <th style="width:80px;" class="report_table_header" rowspan="2">[[.to_warehouse.]]</th>
                    <th class="report_table_header" colspan="6">[[.detail.]]</th>
                </tr>
                
                <tr valign="middle" align="center">
                    <th style="width:10px;" class="report_table_header">[[.stt.]]</th>
                	<th style="width:50px;" class="report_table_header">[[.product_code.]]</th>
                    <th style="width:120px;" class="report_table_header">[[.name.]]</th>
                    <th style="width:50px;" class="report_table_header">[[.quantity.]]</th>
                    <th style="width:70px;" class="report_table_header">[[.price.]]</th>
                    <th style="width:70px;" class="report_table_header">[[.total_amount.]]</th>
                </tr>
                
        		<!--LIST:items-->
        		<tr bgcolor="white">
                    <td rowspan="[[|items.row_span|]]" align="center" class="report_table_column">[[|items.stt|]]</td>
                    <td rowspan="[[|items.row_span|]]" align="left" class="report_table_column">
                    <a href="<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>[[=items.id=]],'type'=>[[=items.type=]])); ?>" target="_blank" style="color: #039;">[[|items.bill_number|]]</a>
                    
                    </td>
                    <td rowspan="[[|items.row_span|]]" align="center" class="report_table_column">[[|items.create_date|]]</td>
                    <td rowspan="[[|items.row_span|]]" align="right" class="report_table_column"><span class="change_numTr"><?php echo System::display_number([[=items.total_amount=]]); ?></span></td>
                    <td rowspan="[[|items.row_span|]]" align="left" class="report_table_column">[[|items.note|]]</td>
                    <td rowspan="[[|items.row_span|]]" align="left" class="report_table_column">[[|items.supplier_name|]]</td>
                    <td rowspan="[[|items.row_span|]]" align="left" class="report_table_column">[[|items.warehouse_name|]]</td>
                    <td rowspan="[[|items.row_span|]]" align="left" class="report_table_column"><?php echo [[=items.to_warehouse_name=]]?[[=items.to_warehouse_name=]]:''; ?></td>
        		</tr>
                
                <!--LIST:items.detail-->
                <tr valign="middle" align="center">
                    <td align="center" class="report_table_column">[[|items.detail.stt|]]</td>
                    <td align="center" class="report_table_column">[[|items.detail.product_id|]]</td>
                    <td align="left" class="report_table_column">[[|items.detail.product_name|]]</td>
                    <td align="right" class="report_table_column">[[|items.detail.num|]]</td>
                    <td align="right" class="report_table_column"><span class="change_numTr">[[|items.detail.price|]]</span></td>
                    <td align="right" class="report_table_column"><span class="change_numTr"><?php echo System::display_number([[=items.detail.total_amount=]]?[[=items.detail.total_amount=]]:''); ?></span></td>
                </tr>
                <!--/LIST:items.detail-->
                
        		<!--/LIST:items-->

                <tr>
                    <th colspan="3" align="right" > [[.grand_total.]] </th>
                    <th colspan="1" align="right" ><span class="change_numTr"><?php echo System::display_number([[=grand_total=]]['grand_total_amount']); ?></span></th>
                    <th colspan="10" align="right" ><span class="change_numTr"><?php echo System::display_number([[=grand_total=]]['grand_total_amount']); ?></span></th>
                </tr>
        	</table>
        </div>
        <div>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td rowspan="12" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center" width="45%">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>                    
                    <td align="right" width="45%"><em>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?> </em></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center"><strong>Ng&#432;&#7901;i l&#7853;p bi&#7875;u </strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center"><strong>Th&#7911; kho </strong> </td>
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
           jQuery(this).html(to_numeric(jQuery(this).html()));
        });
        //jQuery('.class_none').remove();
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })
</script>