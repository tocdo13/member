<?php
function ContentGroupEmail($invoice_group)
{
    ob_start();
?>
    <div id="invoice" style="width: 100%; font-size:14px; margin: 0 auto; border: 1px solid #00b2f9; padding: 5px;">
        <div class="item-body" style="float:left; width:100%;"><br /></div>
        <table cellpadding="0" width="100%" border="0">
        	<tr>
        		<td width="20%" align="center" valign="top"><img src="<?php if(defined('ROOT_PATH_EMAIL')) echo ROOT_PATH_EMAIL.HOTEL_LOGO; else echo $_SERVER['DOCUMENT_ROOT'].Url::$root.HOTEL_LOGO;?>" width="100%" /></td>
        		<td width="60%" align="center">
        			<div class="invoice-title" style="font-size:18px;font-weight:bold;color:#333333;margin:0px;padding:0px;">GROUP'S FOLIO</div>
        			<div class="invoice-sub-title" style="font-size:18px;font-weight:bold;color:#333333;margin:0px;padding:0px;">PHIẾU THANH TOÁN NHÓM</div>
        			<div class="invoice-contact-info" style="color:#666666;font-size:12px;"><?php echo HOTEL_ADDRESS;?></div>
        			<div class="invoice-contact-info" style="color:#666666;font-size:12px;">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
        			<div class="invoice-contact-info" style="color:#666666;font-size:12px;">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
        		</td>
        		<td width="20%" align="left">
        			<div>
        			   <p><font style="font-size: 14px; color:red;">Folio:</font> <font style="font-size:15px;font-weight:200;color:red;"><?php echo $invoice_group['bill_number'] ?></font><br /></p>
                       <p><font style="font-size: 14px;color:red; ">Re_code:</font> <font style="font-size:15px;font-weight:200;color:red;"><?php echo $invoice_group['reservation_id'] ?></font><br /></p>
                    </div>
        		</td>
        	</tr>
    	</table>
        <div class="item-body" style="float:left; width:100%;"><div class="seperator-line" style="border-bottom:1px solid #000000;float:left;width:100%;margin-bottom:10px;">&nbsp;</div></div>
        <table cellpadding="0" width="100%">
        	<tr valign="top">
        		<td align="left" width="50%">
        			<div class="item-body" style="float:left; width:100%;"><div>Customer / Khách hàng:<?php echo $invoice_group['guest'] ?></div></div>
        			<div class="item-body" style="float:left; width:100%;"><div>Traveller / Tên khách::<?php echo $invoice_group['traveller_name'] ?></div></div>
        			<div class="item-body" style="float:left; width:100%;"><div>Address / Địa chỉ::<?php echo $invoice_group['address'] ?></div></div>
                    <div class="item-body" style="float:left; width:100%;"><div>Arrival time ::<?php echo $invoice_group['arrival_time'] ?></div><div>Departure time ::<?php echo $invoice_group['departure_time'] ?></div></div>
                </td>
        		<td align="right" width="50%">
        			<div>Currency / Ti&#7873;n t&#7879;: <?php echo HOTEL_CURRENCY;?></div>
        			<div>Exchange Rate / Tỉ Giá: <?php echo System::display_number($invoice_group['exchange_rate']); ?> <br /></div>
                    <div>Print by:<?php echo $invoice_group['account_name'] ?><?php //echo Session::get('user_id');?></div>
                    <div>Print time : <?php echo date('H:i d/m/y');?></div>
                </td>
            </tr>
    	</table>
        
        <div class="item-body" style="float:left; width:100%;"><div class="seperator-line" style="border-bottom:1px solid #000000;float:left;width:100%;margin-bottom:10px;">&nbsp;</div></div>
        <?php $total=0;?>
        <div class="item-body" style="float:left; width:100%;">
            <table width="100%" >
                <tr>
                    <td align="left" valign="top">No</td>
                    <td align="left" valign="top">Date</td>
                    <td align="left" valign="top">Description</td>
                    <td align="right" valign="top">Amount</td>
                    <td align="right" valign="top">Ser.charge</td>
                    <td align="right" valign="top">VAT Tax</td>
                    <td align="right" valign="top">Total</td>
                </tr>
                <?php $i=0; ?>
                <?php 
                    foreach($invoice_group['items'] as $k => $v)
                    {
                        if($v['type']!= 'DEPOSIT_GROUP' && $v['type']!= 'DEPOSIT' && $v['type']!= 'DISCOUNT')
                        {
                            $stt = ++$i;
                            ?>
                            <tr>
                                <td align="left" valign="top"><?php echo $stt;?></td>
                                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date($v['date_use']);?></td>
                                <td align="left" valign="top"><?php echo $v['description'] ?>
                                <?php echo $v['hk_code']!= ''? '('.$v['hk_code'].')' : ''?>
                                <?php echo $v['ex_note']!= ''? '('.$v['ex_note'].')':'';  ?>
                                <?php if($v['foc_all'] == 1) echo '(FOC_ALL)';?>
                                </td>
                                <td align="right" valign="top"><?php echo System::display_number($v['amount']);?></td>
                                <td align="right" valign="top"><?php echo System::display_number($v['service_amount']);?></td>
                                <td align="right" valign="top"><?php echo System::display_number($v['tax_amount']);?></td>
                                <td align="right" valign="top"><?php echo System::display_number(round($v['total_amount']));?></td>
                            </tr>
                            <?php
                        }
                    }
                    foreach($invoice_group['items'] as $k => $v)
                    {
                        if($v['type']== 'DISCOUNT')
                        {
                            $stt = ++$i; $ount_dis = 0;
                            if($ount_dis == 0)
                            {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <hr width="100%" />
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td align="left" valign="top"><?php echo $stt;?></td>
                                <td align="left" valign="top"><?php echo Date_Time::convert_orc_date_to_date($v['date_use']);?></td>
                                <td align="left" valign="top"><?php echo $v['description'] ?> 
                                <?php echo $v['hk_code'] != ''? '('.$v['hk_code'].')' : ''?>
                                <?php echo $v['ex_note'] != ''? '('.$v['ex_note'].')':'';  ?>
                                <?php 
                                    if($v['foc_all'] == 1)
                                    {
                                        echo '(FOC_ALL)';
                                    }
                                 ?>
                                </td>
                                <td align="right" valign="top"><?php echo System::display_number($v['amount']);?></td>
                                <td align="right" valign="top">&nbsp;</td>
                                <td align="right" valign="top">&nbsp;</td>
                                <td align="right" valign="top">&nbsp;</td>
                            </tr>
                            <?php
                        }
                    }
                 ?>
            </table>
        </div>
      <div style="float:right;">
            <div>
            	<b style="color:red"><em>Tổng : <span style="float:right;"><?php echo System::display_number(round($invoice_group['total_amount']));?></span></em></b>
            </div>
            <?php
            if($invoice_group['total_foc']>0)
            {
              ?>  
              <div><b><em>Tổng miễn phí :<span style="float:right;"><?php echo System::display_number(round($invoice_group['total_foc']));?></span></em></b></div>
              <?php  
            }    
            ?>
            <?php
            if($invoice_group['total_group_deposit']>0)
            {
                ?>
                <div><b><em>Đặt cọc nhóm :<span style="float:right;"><?php echo System::display_number($invoice_group['total_group_deposit']);?></span></em></b></div>
                <?php
            }
            ?>
            <hr />
            <div style="float:right;">
            	<b style="color:red"><em>Tổng thanh toán :<span style="float:right;"><?php echo System::display_number(round($invoice_group['total_payment']));?></span></em></b>
            </div>
        </div>
        <div class="item-body" style="float:left; width:100%;"><div class="seperator-line" style="border-bottom:1px solid #000000;float:left;width:100%;margin-bottom:10px;">&nbsp;</div></div>
        <div style="float:right; width: 100%;"> 
            <table width="100%" style="float:right;">
              	<tr>
              		<td align="center">Loại thanh toán</td>
                    <td align="center">Số tài khoản/Số thẻ</td>
                    <td align="center">Diển giải</td>
                    <td align="center">Loại tiền</td>
                    <td align="right">Tổng</td>
                    <td align="right">Phí ngân hàng nếu có</td>
                    <td align="right">Tổng phí ngân hàng</td>
              	</tr>
                
                <?php
                foreach($invoice_group['payments'] as $k => $v)
                {
                    ?>
                    <tr>
                  		<td align="center"><?php echo Portal::language(strtolower($v['payment_type_id'])); if($v['credit_card_id']!=''){echo '('.$v['credit_card_name'].')';}?></td>
                        <td align="center"><?php echo $v['bank_acc'] ?></td>
                        <td align="center"><?php echo $v['description'] ?></td>
                        <td align="center"><?php echo $v['currency_id'] ?></td>
                        <td align="right"><?php echo System::display_number($v['total']);?>
                        <td align="right"><?php echo System::display_number($v['bank_fee']);?>
                        <td align="right"><?php echo System::display_number($v['total'] + $v['bank_fee']);?>
                  	</tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          	<tr>
            	<td height="100">&nbsp;</td>
        	</tr>
        </table>   
    </div>
<?php    
    $output = ob_get_contents();
    ob_end_clean(); 
    //print_r($output);die();
    return $output;
}
?>