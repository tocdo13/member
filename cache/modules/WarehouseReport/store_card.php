<link rel="stylesheet" href="skins/default/report.css"/>
<!--the kho-->
<div class="product-report-bound">
    <div style="width:720px;padding:10px;text-align:center;font-size:14px;">	
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
                <td align="left">
                	<strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                	Địa chỉ: <?php echo HOTEL_ADDRESS;?><br/>
                	Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
                	Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
                </td>
<!--				<td align="right">M&#7851;u s&#7889;:S12-SKT/DNN<br>
		Ban h&agrave;nh theo Q&#272; s&#7889; 1177/TC/Q&#272;/C&#272;KT<br>
		ng&agrave;y 23-12-1996 c&#7911;a B&#7897; T&agrave;i ch&iacute;nh<br>
		</td>
-->         </tr>
		</table>
	</div>
    <table width="100%" id="export">
        <tr>
            <td>
            
           
    <div style="width:720px;">
        <div style="text-align:center;">
            <div class="report_title"><?php echo Portal::language('stock_card');?></div>
        	<p><?php echo Portal::language('date_from');?> <?php echo $this->map['date_from'];?> <?php echo Portal::language('to');?> <?php echo $this->map['date_to'];?></p>
            <table border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td align="left"><?php echo Portal::language('warehouse');?>: </td>
                    <td align="left"><?php echo $this->map['warehouse'];?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('product_code');?>: </td>
                    <td align="left"><?php echo $this->map['code'];?></td>
                </tr>
                <tr>
                    <td align="left"><?php echo Portal::language('product_name');?>: </td>
                    <td align="left"><?php echo $this->map['name'];?></td>
                </tr>
            </table>
            <br />
        </div>
        <div style="text-align: center;"><p><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></p></div>
        
    	<div>
            <table cellpadding="2" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
    			<tr valign="middle" bgcolor="#EFEFEF">
    				<th colspan="3" class="report_table_header"><?php echo Portal::language('invoice');?></th>
    				<th rowspan="2" class="report_table_header"><?php echo Portal::language('note');?></th>
    				<th colspan="3" class="report_table_header"><?php echo Portal::language('number');?></th>
    			</tr>
    			<tr>
                    <th rowspan="1" class="report_table_header"><?php echo Portal::language('invoice_date');?></th>
        			<th rowspan="1" class="report_table_header"><?php echo Portal::language('import_code');?></th>
        			<th rowspan="1" class="report_table_header"><?php echo Portal::language('export_code');?></th>
        			<th rowspan="1" class="report_table_header"><?php echo Portal::language('import');?></th>
        			<th rowspan="1" class="report_table_header"><?php echo Portal::language('export');?></th>
        			<th rowspan="1" class="report_table_header"><?php echo Portal::language('store_remain');?></th>
    			</tr>
    			<tr>
                    <td align="center">1</td>
                    <td align="center">2</td>
                    <td align="center">3</td>
                    <td align="center">4</td>
                    <td align="center">5</td>
                    <td align="center">6</td>
                    <td align="center">7</td>
                </tr>
    			<tr bgcolor="white">
    				<td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
                    <td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
                    <td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
    				<td width="200" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><?php echo Portal::language('start_period_remain');?></td>
    				<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
    				<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
    				<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><strong><?php echo $this->map['start_remain'];?></strong></td>
    			</tr>
    			<tr>
    				<td colspan="7" class="report_sub_title" align="right"><b>&nbsp;</b></td>
    			</tr>
    			<?php if(isset($this->map['products']) and is_array($this->map['products'])){ foreach($this->map['products'] as $key1=>&$item1){if($key1!='current'){$this->map['products']['current'] = &$item1;?>
    			<tr bgcolor="white">
                    <td nowrap align="left" class="report_table_column" width="70"><?php echo $this->map['products']['current']['create_date'];?></td>
                    <td nowrap align="left" class="report_table_column" width="70"><?php echo $this->map['products']['current']['import_invoice_code'];?></td>
                    <td nowrap align="left" class="report_table_column" width="70"><?php echo $this->map['products']['current']['export_invoice_code'];?></td>
                    <td nowrap align="left" class="report_table_column" width="200"><?php echo $this->map['products']['current']['note'];?></td>
                    <td nowrap align="right" class="report_table_column" width="100">
                    <?php if($this->map['products']['current']['import_number']>1)
                        echo $this->map['products']['current']['import_number'];
                    else
                        echo '0'.$this->map['products']['current']['import_number'];
                    ?></td>
                    <td nowrap align="right" class="report_table_column" width="100"><?php echo $this->map['products']['current']['export_number'];?></td>
                    <td nowrap align="right" class="report_table_column" width="100"><?php echo $this->map['products']['current']['remain'];?></td>
    			</tr>
    			<?php }}unset($this->map['products']['current']);} ?>
    			<tr>
    				<td colspan="7" class="report_sub_title" align="right"><b>&nbsp;</b></td>
    			</tr>
    			<tr bgcolor="white">
                    <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
                    <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
                    <td align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
                    <td align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><?php echo Portal::language('total');?></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong><?php echo $this->map['import_total'];?></strong></td>
                    <td align="right" nowrap="nowrap" bgcolor="#F1F1F1" class="report_table_column"><strong><?php echo $this->map['export_total'];?></strong></td>
                    <td align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
                </tr>
    			<tr bgcolor="white">
    				<td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="70" align="left" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="200" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><?php echo Portal::language('end_period_remain');?></td>
    				<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td>
    				<td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column">&nbsp;</td><td width="100" align="right" nowrap bgcolor="#F1F1F1" class="report_table_column"><strong><?php echo round($this->map['end_remain'],2)?></strong></td>
    			</tr>
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
                <td align="center" width="45%"><em><?php echo Portal::language('date');?>: <?php echo date('d/m/Y') ?></em></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="center"><strong><?php echo Portal::language('creater');?> </strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center"><strong><?php echo Portal::language('treasurer');?></strong></td>
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
