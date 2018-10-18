<style>
/*full màn hình*/
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
            <!--IF:cond(Url::get('type')=='IMPORT')-->
        	<div class="report_title">[[.warehouse_import_by_receiver_report.]]</div>
            <!--ELSE-->
            <div class="report_title">[[.warehouse_export_by_receiver_report.]]</div>
            <!--/IF:cond-->
        	<br/>
            <strong>[[.from_date.]] [[|from_date|]] [[.to.]] [[|to_date|]]</strong>
            <br/><br />
            <strong>[[.from_warehouse.]]: [[|warehouse_name|]]</strong><br />
                    [[.receiver_name_1.]]:  [[|receiver_name|]]
        </div>
        <div style="text-align: center;"><p><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></p></div>
        <br />
        <div>
        	<table cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" width="100%">
                <tr valign="middle" align="center">
                    <th style="width:10px;" class="report_table_header">[[.stt.]]</th>
                	<th style="width:70px;" class="report_table_header">[[.id.]]</th>
                    <th style="width:300px;" class="report_table_header">[[.product_name.]]</th>
                    <th style="width:100px;" class="report_table_header">[[.quantity.]]</th>
                    <th style="width:100px;" class="report_table_header">[[.price.]]</th>
                    <th style="width:100px;" class="report_table_header">[[.amount.]]</th>
                </tr>
                <?php $stt = 1;?>

                <!--LIST:items-->
                <tr>
                <?php if(isset([[=items.rowspan=]])) {?>
                <td rowspan="[[|items.rowspan|]]" ><?php echo $stt++; ?></td>
                <td rowspan="[[|items.rowspan|]]" ><strong>[[|items.product_id|]]</strong></td>
                <td rowspan="[[|items.rowspan|]]" align="left" style="padding-left: 10px;">[[|items.product_name|]]</td>
                <td rowspan="[[|items.rowspan|]]" align="right" >[[|items.total|]]</td>
                <td rowspan="[[|items.rowspan|]]" align="right" style="padding-left: 10px;"><span class="change_numTr"><?php echo System::display_number([[=items.price=]]); ?></span></td>
                <td rowspan="[[|items.rowspan|]]" align="right" ><span class="change_numTr"><?php echo System::display_number([[=items.total_1=]]);?></span></td>
                <?php } ?>
                 </tr>
                <!--/LIST:items-->

                
        	</table>
        </div>
        <div>
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td colspan="6" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center" width="45%">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right" width="45%"><em>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?> </em></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td align="center"><strong>[[.creater.]] </strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="center"><strong>[[.warehouseman.]]  </strong> </td>
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