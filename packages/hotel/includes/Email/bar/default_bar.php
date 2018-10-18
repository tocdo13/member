<?php
    function BarDefaulEmail($item_invoice)
    {
        ob_start();
?>
        <div id="transform_a4" style="width:800px; padding:5px;text-align:left; margin: 0 auto; border: 1px solid #00b2f9; padding: 5px;">
            <div class="restaurant-invoice-bound" style="width: 100%;">
            	<div class="item-body">
                	<table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:10px;">
                    	<tr>
                        	<td rowspan="3" ><img src="<?php echo $_SERVER['DOCUMENT_ROOT'].Url::$root.HOTEL_LOGO;?>" width="250px;" /></td>
                        	<td style="font-size:18px;font-weight:bold;" align="center">INVOICE / HÓA ĐƠN</td>
                        </tr>
                        <tr>
                            <td align="center">Ngày :<?php echo date('d/m/Y H:i\'');?></td>
                        </tr>
                        <tr>
                            <td align="center">Giờ in:<?php echo $item_invoice['table_checkout'] ?></td>
                        </tr>
                   
                    </table>
                    <br />
                    <div align="left">
                    	<span style="font-size:14px;font-weight:bold;">Đơn vị bán hàng :<?php echo $item_invoice['bar_name'] ?></span>
                    </div>
                </div>
                <br />
                <div class="item-body">
                    <div valign="top" align="left">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <?php  
                                    if(!empty($item_invoice['customer_name']))
                                    {
                                ?>
                                     <td colspan="2" align="left">Tên khách :<?php echo $item_invoice['customer_name']['fullname']; ?>/   phòng: <?php echo $item_invoice['room_name']; ?></td>
                                     <td align="right">Table No:<?php echo $item_invoice['tables_name']; ?></td>   
                                <?php }
                                    else
                                    { ?>
                                     <td colspan="2" align="left">Tên khách: <?php echo $item_invoice['receiver_name'];?></td>
                                     
                                     <td align="right">Table No:<?php echo $item_invoice['tables_name']; ?></td>
                                        
                                <?php } ?> 
                            </tr>
                            
                            <tr>
                                <td style="border-bottom:1px solid #000000;">công ty:<?php if(!empty($item_invoice['customer_name'])) echo $item_invoice['customer_name']['cname']; else echo $item_invoice['agent_name']  ?> </td>
                                <td align="center" style="border-bottom:1px solid #000000;">Mã:<?php  echo $item_invoice['order_id'] ?></td>
                                <td align="right" style="border-bottom:1px solid #000000;">Số khách :<?php  echo $item_invoice['num_people'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br />
                <div>
                    <div class="item-body"></div>
                    <div class="item-body"></div>
                    <table width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000">
                        <tr>
                            <th width="50%" align="left"  style="border-bottom:1px solid #000000;">Diễn giải<br /> Description </th>
                            <th width="10%" align="center"  style="border-bottom:1px solid #000000;">Số lượng<br />used</th>
                            <th width="15%" align="right"  style="border-bottom:1px solid #000000;">Đơn giá<br />Price</th>
                            <th width="10%" align="right" nowrap="nowrap"  style="border-bottom:1px solid #000000;">Giảm(%)<br />Disc(%)</th>
                            <th width="20%" align="right" nowrap="nowrap" style="border-bottom:1px solid #000000;">Thành tiền<br />Amount</th>
                        </tr>
                        <?php
                        $i=1; 
                        foreach($item_invoice['product_items'] as $k => $v) 
                        {
                            if($item_invoice['product_items'][$k]['product__remain_quantity']!=0 && $item_invoice['product_items'][$k]['cancel_all']==0)
                            {
                         ?>       
                                <tr valign="top">
                                    <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i.'.'.$v['product__name'];?><div class="item-body"></div></td>
                                    <td align="center" style="border-bottom:1px solid #000000;"><?php echo $v['product__remain_quantity'] ?></td>
                                    <td align="right" style="border-bottom:1px solid #000000;"><?php echo $v['product__price'] ?></td>
                                    <td align="center" style="border-bottom:1px solid #000000;"><?php echo $v['product__discount'] ?></td>
                                    <td align="right" style="border-bottom:1px solid #000000;"><?php echo $v['product__total'] ?></td>
                                </tr>
                         <?php
                             $i++;      
                            }
                            if($item_invoice['product_items'][$k]['product__quantity_discount']!=0 )
                            {
                          ?>
                                <tr  valign="top">
                                    <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i.'.'.$v['product__name'] ?><strong>([[.promotion.]])</strong><div class="item-body"></div></td>
                                    <td align="center" style="border-bottom:1px solid #000000;"><?php $v['product__quantity_discount'];?></td>
                                    <td align="right" style="border-bottom:1px solid #000000;"><?php $v['product__price'];?></td>
                                    <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                                    <td align="right" style="border-bottom:1px solid #000000;">0</td>
                                </tr>
                          <?php
                            $i++;
                            }
                            if($item_invoice['product_items'][$k]['cancel_all']==1)       
                            {
                            ?>
                            
                                <tr valign="top">
                                    <td align="left"><?php echo $i.'. '.$v['product__name'];?><strong>(Cancel all)</strong><div class="item-body"></div></td>
                                    <td align="center" style="border-bottom:1px solid #000000;"><?php echo $v['product__quantity'] ?></td>
                                    <td align="right" style="border-bottom:1px solid #000000;"><?php echo $v['product__price'] ?></td>
                                    <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                                    <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                                </tr>
                            <?php
                            $i++;    
                            }
                        }
                        ?>
                    </table>
                    <br />
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td align="right">
                                <table width="700">
                                    <tr>
                                       <td width="70%"><strong>Tổng/Amount:</strong><div class="sub-item-body"></div></td>
                                       <td width="30%" align="right"><?php echo $item_invoice['amount']; ?></td>
                                    </tr>
                                    <?php  
                                        if($item_invoice['total_discount']!='0.00')
                                        {
                                    ?>
                                            <tr>
                                                <td><strong>Sản phẩm giảm giá/Product Discounted:  </strong><div class="sub-item-body"></div></td>
                                                <td align="right"><?php echo $item_invoice['total_discount']; ?></td>   
                                            </tr>    
                                    <?php
                                        }
                                        if($item_invoice['discount_percent']!='0.00')
                                        {
                                    ?>        
                                            <tr>
                                                <td><strong>(<?php echo $item_invoice['discount_percent']; ?>%) Giảm giá trên hóa đơn/Order Discount:</strong><div class="sub-item-body"></div></td>
                                                <td align="right"><?php echo System::display_number($item_invoice['order_discount']);?></td>
                                            </tr>   
                                    <?php        
                                        }
                                        if($item_invoice['bar_fee']!='0.00')
                                        {
                                     ?>       
                                            <tr>
                                                <td><strong> (<?php echo $item_invoice['bar_fee_rate']; ?>%)Phí dịch vụ/Service charge: </strong><div class="sub-item-body"></div></td>
                                                <td align="right"><?php echo $item_invoice['bar_fee']; ?></td>
                                            </tr>   
                                     <?php       
                                        }
                                        if($item_invoice['tax']!='0.00')
                                        {
                                        ?>    
                                            <tr>
                                                <td><strong>(<?php echo $item_invoice['tax_rate']; ?>%) Thuế/Tax:</strong><div class="sub-item-body"></div></td>
                                                <td align="right"><?php echo $item_invoice['tax']; ?></td>
                                            </tr>
                                      <?php      
                                        }
                                        if($item_invoice['deposit']!='0.00')
                                        {
                                        ?> 
                                             <tr>
                                                <td><strong>Đặt cọc/ Deposit:</strong><div class="sub-item-body"></div></td>
                                                <td align="right"><?php echo $item_invoice['deposit']; ?></td>
                                             </tr>   
                                      <?php      
                                        }
                                        
                                      ?>
                                            <tr>
                                                <td nowrap="nowrap" align="left" style="border:1px solid #000;border-right:0px;"><strong>Tổng tiền thanh toán/Grant Total:</strong><div class="sub-item-body"></div></td>
                                                <td align="right" style="border:1px solid #000;border-left:0px;"><span style="font-size:18px;font-weight:bold; color: red;"><?php echo $item_invoice['sum_total'] ?></span></td>
                                            </tr>          
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
            			
<?php   
        $output = ob_get_contents();
        ob_end_clean(); 
        //print_r($output);die();
        return $output;
    }
?>