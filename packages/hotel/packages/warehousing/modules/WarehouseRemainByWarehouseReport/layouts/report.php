<!--Bao cao ton kho-->
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
    
    <div style="width:;padding:10px;">
        <div style="text-align:center;">
        	<div class="report_title">[[.warehouse_remain_order_by_warehouse_report.]]</div>
        	<br/><strong>[[.date.]] [[|date|]]</strong><br />
        	
           <?php
            foreach ($this->map['group_wh'] as $key=>$value)
            {
                echo $value['name'];
            }
           ?>
            
        </div>
        
        <div>
        	<table cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" width="100%">
                <tr valign="middle" align="center">
                    <th style="width:10px;" class="report_table_header">[[.stt.]]</th>
                	<th  class="report_table_header">[[.id.]]</th>
                    <th class="report_table_header">[[.product_name.]]</th>
                    <th  class="report_table_header">[[.unit.]]</th>
                    <!--LIST:group_wh-->
                    <th class="report_table_header">[[|group_wh.name|]]</th>
                    <!--/LIST:group_wh-->
                    <th  class="report_table_header">[[.total_money.]]</th>
                </tr>
                
                <?php
                //System::debug($this->map['product_remain']);
                $total_money3 = 0;
                foreach($this->map['product_remain'] as $key=>$value)
                {
                    echo '<tr>';
                    echo '<td>'.$value['stt'].'</td>';
                    echo '<td><strong>'.$value['id'].'</strong></td>';
                    echo '<td style="text-align:left;">'.$value['product_name'].'</td>';
                    echo '<td>'.$value['unit_name'].'</td>';
                    
                    $total = 0;
                    foreach($this->map['group_wh'] as $k=>$v)
                    {
                        echo '<td align="right" >'.($value['remain_number_'.$k]?$value['remain_number_'.$k]:'').'</td>';
                        $total+=$value['remain_number_'.$k];
                    }
                    //echo '<td>'.$total.'</td>';
                   echo '<td align="right">'.System::display_number_report($value['total_product']).'</td>';
                    echo '</tr>';
                    $total_money3 += $value['total_product'];
                }
                
                
                ?>
                <tr>
                    <th colspan="5" align="right" >[[.total.]]</th>
                    <th align="right" ><?php echo System::display_number_report($total_money3);?></th>
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
                    <td align="right"><em>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?> </em></td>
                </tr>
                <tr>
                    <td align="center"><strong>Ng&#432;&#7901;i l&#7853;p bi&#7875;u </strong></td>
                    <td align="center"><strong>Th&#7911; kho </strong> </td>
                </tr>
            </table>
        </div>
    </div>	
</div>