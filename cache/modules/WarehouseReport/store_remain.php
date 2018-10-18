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
			<?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?><br/>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<!--td align="right"><strong><?php echo Portal::language('Warehouse');?>: <?php echo $this->map['warehouse'];?></strong><br /></td-->
		<td align="right"><strong><?php echo $this->map['warehouse'];?></strong><br /></td>
	

	</tr>
</table>
<br />
	<div style="width:100%;" >
		<div style="padding:2px;">
		<div class="report_title" align="center"><?php echo $this->map['title'];?></div>
		<div>
			<table width="100%">
				<tr valign="top">
					<td style="font-size:12px;text-align:center;"><br />
						<?php echo Portal::language('date_from');?> <?php echo $this->map['date_from'];?> <?php echo Portal::language('date_to');?> <?php echo $this->map['date_to'];?>
					</td>
				</tr>
			</table>
	    </div>
		<div style="padding:2px 2px 2px 2px;text-align:left;">
			&nbsp;
		</div>
	    <div style="text-align:left;">
            <button id="export"><?php echo Portal::language('export');?></button>
			<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
            <tr>
                <th class="report_table_header" align="center" scope="col" width="100px" rowspan="2"><?php echo Portal::language('product_code');?><br /></th>
                <th class="report_table_header" align="center" scope="col" width="200px" rowspan="2"><?php echo Portal::language('product_name');?></th>
                <th class="report_table_header" align="center" scope="col" width="50px"  rowspan="2"><?php echo Portal::language('unit');?></th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2"><?php echo Portal::language('beginning');?></th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2"><?php echo Portal::language('import');?></th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2"><?php echo Portal::language('export');?></th>
                <th class="report_table_header" align="center" scope="col" width="200px" colspan="2"><?php echo Portal::language('last_balance');?></th>
            </tr>
            <tr>
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('quantity');?></th>
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('total_amount');?></th><!--<?php echo Portal::language('total');?></th-->
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('quantity');?></th>
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('total_amount');?></th><!--<?php echo Portal::language('total');?></th-->
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('quantity');?></th>
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('total_amount');?></th><!--<?php echo Portal::language('total');?></th-->
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('quantity');?></th>
                <th class="report_table_header" align="center" scope="col" width="100px"><?php echo Portal::language('total_amount');?></th><!--<?php echo Portal::language('total');?></th-->
            </tr>
			<?php 
                $category = '';
                $total_start_term_money = 0;
                $total_import_money = 0;
                $total_export_money = 0;
            ?>		
		      <?php if(isset($this->map['products']) and is_array($this->map['products'])){ foreach($this->map['products'] as $key1=>&$item1){if($key1!='current'){$this->map['products']['current'] = &$item1;?>
			<?php if($category != $this->map['products']['current']['category_id'] ){$category=$this->map['products']['current']['category_id']; if($this->map['products']['current']['category_id'] != "Dịch vụ"){ if($this->map['products']['current']['category_id'] != "Service"){ ?>
			<tr>
				<td colspan="11" class="category-group"><?php echo $this->map['products']['current']['category_id'];?></td>
			</tr>
			<?php }}}?>
            <?php if($this->map['products']['current']['category_id'] != "Dịch vụ"){ if($this->map['products']['current']['category_id'] != "Service"){ ?>
            <tr>
                <td align="left"><?php echo $this->map['products']['current']['product_id'];?></td>
                <td align="left"><?php echo $this->map['products']['current']['name'];?></td>
                <td align="center"><?php echo $this->map['products']['current']['unit'];?></td>
                <td align="right"><?php echo round($this->map['products']['current']['start_term_quantity'],2);?></td>
                <td align="right">
                    <div class="price">
                    <?php $grand_total_start_term_money =  (isset($this->map['products']['current']['total_start_term_money'])?round($this->map['products']['current']['total_start_term_money'],2):0);
                                    $total_start_term_money +=$grand_total_start_term_money;
                                    echo System::display_number($grand_total_start_term_money);
                                    ?>
                     </div>
                </td>
                    
                <td align="right"><?php echo $this->map['products']['current']['import_number'];?></td>
                <td align="right">
                    <div class="price">
                    <?php 
                                       $grand_total_import_money = round($this->map['products']['current']['total_import_money'],2);  
                                       $total_import_money += $grand_total_import_money;  
                                       echo  System::display_number($grand_total_import_money);?>
                    </div>
                </td>
                <td align="right"><?php echo $this->map['products']['current']['export_number'];?></td>
                <td align="right">
                    <div class="price">
                    <?php 
                                        $grand_total_export_money = round($this->map['products']['current']['total_export_money'],2);
                                        $total_export_money += $grand_total_export_money;   
                                        echo System::display_number($grand_total_export_money);?>
                       </div>                  
                </td>
                <td align="right"><?php echo round($this->map['products']['current']['remain_number'],2);?></td>
                <td  align="right">
                    <div class="price">
                    <?php
                    $total_remain_money = (isset($this->map['products']['current']['total_start_term_money'])?round($this->map['products']['current']['total_start_term_money'],2):0) + (isset($this->map['products']['current']['total_import_money'])?round($this->map['products']['current']['total_import_money'],2):0) - (isset($this->map['products']['current']['total_export_money'])?round($this->map['products']['current']['total_export_money'],2):0); 
                    echo (round($this->map['products']['current']['remain_number'],2)>0)?System::display_number($total_remain_money):0; 
                    ?>
                    </div>
                </td>
            </tr>
            <?php }} ?>
  			  <?php }}unset($this->map['products']['current']);} ?>
              <tr>
                <th colspan="4" align="right" ><?php echo Portal::language('grand_total');?></th>
                <th align="right" ><div class="price"><?php echo System::display_number($total_start_term_money,2);?></div></th>
                <th align="right" colspan="2"><div class="price"><?php echo System::display_number($total_import_money);?></div></th>
                <th align="right" colspan="2"><div class="price"><?php echo System::display_number($total_export_money);?></div></th>
                <th align="right" colspan="2" ><div class="price">
                        <?php
                    //$total_remain_money2 =  round($this->map['grand_total2']['grand_total_start_term_money'],2) + round($this->map['grand_total2']['grand_total_import_money'],2)- round($this->map['grand_total2']['grand_total_export_money'],2);
                    $total_remain_money2 = $total_start_term_money+$total_import_money-$total_export_money;
                    echo System::display_number($total_remain_money2); 
                    ?></div>
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
                <td align="right"><em><?php echo Portal::language('date');?>&nbsp;<?php echo $this->map['day'];?> <?php echo Portal::language('month');?>&nbsp;<?php echo $this->map['month'];?>&nbsp;<?php echo Portal::language('year');?>&nbsp;<?php echo $this->map['year'];?>&nbsp;</td>
            </tr>
            <tr>
            	
                <td align="center"><strong><?php echo Portal::language('creater');?></strong></td>
            	<td align="center"><strong><?php echo Portal::language('accountant');?></strong><p>&nbsp;</p><p>&nbsp;</p></td>
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
            jQuery(".price").each(function(){
                console.log(jQuery(this).html().trim());
                jQuery(this).html(to_numeric(jQuery(this).html().trim()));
            });
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
              , datatype: 'table'
            });
        });
    }
);
</script>
