<style>
@media print
{
    #export{display:none}
}
</style>
<!--xuat nhap ton-->
<link rel="stylesheet" href="skins/default/report.css"/>
<div class="report-bound">
<div style="text-align:left;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			[[.address.]]: <?php echo HOTEL_ADDRESS;?><br/>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<!--td align="right"><strong>[[.Warehouse.]]: [[|warehouse|]]</strong><br /></td-->
		<td align="right"><strong>[[|warehouse|]]</strong><br /></td>
	

	</tr>
</table>
<br />
	<div style="width:100%;" >
		<div style="padding:2px;">
		<div class="report_title" align="center">[[|title|]]</div>
		<div>
			<table width="100%">
				<tr valign="top">
					<td style="font-size:12px;text-align:center;"><br />
						[[.date_from.]] [[|date_from|]] [[.date_to.]] [[|date_to|]]
					</td>
				</tr>
			</table>
	    </div>
		<div style="padding:2px 2px 2px 2px;text-align:left;">
			&nbsp;
		</div>
	    <div style="text-align:left;">
            <button id="export">[[.export.]]</button>
			<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
            <tr>
                <th class="report_table_header" align="center" scope="col" width="100px" rowspan="2">[[.product_code.]]<br /></th>
                <th class="report_table_header" align="center" scope="col" width="200px" rowspan="2">[[.product_name.]]</th>
                <th class="report_table_header" align="center" scope="col" width="50px"  rowspan="2">[[.unit.]]</th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2">[[.beginning.]]</th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2">[[.import.]]</th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2">[[.export.]]</th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2">[[.last_balance.]]</th>
            </tr>
            <tr>
                <th class="report_table_header" align="center" scope="col" width="100px">[[.quantity.]]</th>
                <th class="report_table_header" align="center" scope="col" width="100px">[[.total_amount.]]</th><!--[[.total.]]</th-->
                <th class="report_table_header" align="center" scope="col" width="100px">[[.quantity.]]</th>
                <th class="report_table_header" align="center" scope="col" width="100px">[[.total_amount.]]</th><!--[[.total.]]</th-->
                <th class="report_table_header" align="center" scope="col" width="100px">[[.quantity.]]</th>
                <th class="report_table_header" align="center" scope="col" width="100px">[[.total_amount.]]</th><!--[[.total.]]</th-->
                <th class="report_table_header" align="center" scope="col" width="100px">[[.quantity.]]</th>
                <th class="report_table_header" align="center" scope="col" width="100px">[[.total_amount.]]</th><!--[[.total.]]</th-->
            </tr>
			<?php 
                $category = '';
            ?>		
		      <!--LIST:products-->
			<?php if($category != [[=products.category_id=]] ){$category=[[=products.category_id=]]; if([[=products.category_id=]] != "Dịch vụ"){ if([[=products.category_id=]] != "Service"){ ?>
			<tr>
				<td colspan="11" class="category-group">[[|products.category_id|]]</td>
			</tr>
			<?php }}}?>
            <?php if([[=products.category_id=]] != "Dịch vụ"){ if([[=products.category_id=]] != "Service"){ ?>
            <tr>
                <td align="left">[[|products.product_id|]]</td>
                <td align="left">[[|products.name|]]</td>
                <td align="center">[[|products.unit|]]</td>
                <td align="right"><?php echo number_format([[=products.start_term_quantity=]],3,".",",");?></td>
                <td align="right"><?php $grand_total_start_term_money =  (isset([[=products.total_start_term_money=]])?System::display_number_report(round([[=products.total_start_term_money=]],2)):0);
                                    echo $grand_total_start_term_money;
                                    ?></td>
                <td align="right"><?php echo number_format([[=products.import_number=]],3,".",",");?></td>
                <td align="right"><?php echo System::display_number_report(round([[=products.total_import_money=]],2));?></td>
                <td align="right"><?php echo number_format([[=products.export_number=]],3,".",",");?></td>
                <td align="right"><?php echo System::display_number_report(round([[=products.total_export_money=]],2));?></td>
                <td align="right"><?php echo number_format([[=products.remain_number=]],3,".",",");?></td>
                <td align="right">
                    <?php
                    $total_remain_money = (isset([[=products.total_start_term_money=]])?round([[=products.total_start_term_money=]],2):0) + (isset([[=products.total_import_money=]])?round([[=products.total_import_money=]],2):0) - (isset([[=products.total_export_money=]])?round([[=products.total_export_money=]],2):0); 
                    echo System::display_number_report($total_remain_money); 
                    ?>
                </td>
            </tr>
            <?php }} ?>
  			  <!--/LIST:products-->
              <tr>
                <th colspan="4" align="right" >[[.grand_total.]]</th>
                <th align="right" ><?php echo System::display_number_report([[=grand_total2=]]['grand_total_start_term_money'],2);?></th>
                <th align="right" colspan="2"><?php echo System::display_number_report([[=grand_total2=]]['grand_total_import_money']);?></th>
                <th align="right" colspan="2"><?php echo System::display_number_report([[=grand_total2=]]['grand_total_export_money']);?></th>
                <th align="right" colspan="2" >
                        <?php
                    //$total_remain_money2 =  round([[=grand_total2=]]['grand_total_start_term_money'],2) + round([[=grand_total2=]]['grand_total_import_money'],2)- round([[=grand_total2=]]['grand_total_export_money'],2);
                    $total_remain_money2 = [[=grand_total2=]]['grand_total_start_term_money']+[[=grand_total2=]]['grand_total_import_money']-[[=grand_total2=]]['grand_total_export_money'];
                    echo System::display_number_report($total_remain_money2); 
                    ?>
                </th>
              </tr> 
		  </table>
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td colspan="2" align="left">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" width="50%">&nbsp;</td>
                <td align="right"><em>[[.date.]]&nbsp;[[|day|]] [[.month.]]&nbsp;[[|month|]]&nbsp;[[.year.]]&nbsp;[[|year|]]&nbsp;</td>
            </tr>
            <tr>
            	
                <td align="center"><strong>[[.creater.]]</strong></td>
            	<td align="center"><strong>[[.accountant.]]</strong><p>&nbsp;</p><p>&nbsp;</p></td>
            </tr>
		</table>
	</div>
	</div>
</div>
</div>
<script>
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);
</script>
