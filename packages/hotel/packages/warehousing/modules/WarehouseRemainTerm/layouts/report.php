<!--Bao cao ton kho-->
<style>
/*full m�n h�nh*/
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
        </div>
        <div>
        	<table cellpadding="2" cellspacing="0" bordercolor="#000000" border="1" width="100%">
                <tr valign="middle" align="center">
                    <th style="width:10px;" class="report_table_header">[[.stt.]]</th>
                	<th  class="report_table_header">[[.id.]]</th>
                    <th class="report_table_header">[[.product_name.]]</th>
                    <th  class="report_table_header">[[.unit.]]</th>
                    <th  class="report_table_header">[[.quantity.]]</th>
                    <th  class="report_table_header">[[.total_money.]]</th>
                </tr>
                <?php
                $check = true;
                foreach($this->map['product_remain'] as $key=>$value)
                {
                    echo '<tr>';
                    echo '<td>'.$value['stt'].'</td>';
                    echo '<td><strong>'.$value['id'].'</strong></td>';
                    echo '<td style="text-align:left;">'.$value['product_name'].'</td>';
                    echo '<td>'.$value['unit_name'].'</td>';
                    echo '<td align="right">'.$value['remain_number'].'</td>';
                    echo '<td align="right">'.System::display_number($value['total_product']).'</td>';
                    echo '</tr>';
                    if($value['remain_number']<0 || $value['total_product']<0)
                    {
                        $check = false;
                    }
                }
                ?>
        	</table>
        </div>
        <div>
            <form name="WarehouseRemainTermForm" method="post">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td align="center"><input name="warehouse_remain_term" type="submit" onclick=" return fun_check();" value="[[.warehouse_remain_term.]]" tabindex="-1"/></td>
                </tr>
            </table>
            </form>
        </div>
    </div>	
</div>
<script>
function fun_check()
{
    var check = '<?php echo $check;?>';
    if(check==true)
    {
        return true;
    }
    else
    {
        alert('[[.van_ton_tai_san_pham_co_so_tien_hoac_so_luong_ton_am.]]');
        return false;
    }
}
</script>