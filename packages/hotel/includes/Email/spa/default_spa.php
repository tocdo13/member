<?php
	function MassageDefaulEmail($item_invoice)
    {
        ob_start();
?>
       <table width="900px;" style=" border: 1px solid #00b2f9; padding: 5px;" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td width="100%">
                    <table cellSpacing=0 cellPadding=2 border=0 width="100%">
                        <tr>
                            <td align="center"> 
                                <table cellpadding="0" width="100%" border="0">
                                    <tr>
                                        <td width="30%" align="center" valign="middle" class="logo"><img src="<?php echo $_SERVER['DOCUMENT_ROOT'].Url::$root.HOTEL_LOGO;?>" alt="logo" width="300px;" /></td>
                                        <td width="40%" align="center" class="title" valign="top" style="font-size:30px;font-weight:bold;text-transform:uppercase;">
                                            Hóa Đơn SPA
                                        </td>
                                        <td width="30%" align="left">
                    						<span style="font-size:15px;font-weight:bold;">No :</span> <span style="font-weight:bold"><?php echo $item_invoice['id'] ?></span><br />
                    						<span style="font-size:15px;">Ngày: <?php echo date('d/m/Y') ?></span><br />
                    						<span style="font-size:15px;">Loại tiền: VND</span>
                                        </td>
                                    </tr>
                                    <tr>
                    					<td colspan="2" align="left">
                    						Tên khách:  <?php echo  $item_invoice['guest_name']?> 
                                        </td>    
                    				</tr>
                                    <tr>
                                        <td colspan="2" align="left">
                    						Số phòng:<?php echo  $item_invoice['hotel_room'] ?> 
                    					</td>
                                    </tr>
                                </table>
                                <br />
                                <div style="width:100%;">
                                <?php
                                    if(isset($item_invoice['products']))
                                    {
                                ?>
                                        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">
                                             <tr bgcolor="#EFEFEF">
                        					    <th width="10%" nowrap="nowrap">R</th>
                        					    <th align="left" width="30%">Tên</th>
                        					    <th width="15%" align="right">Giá</th>
                        					    <th align="center" width="10%" nowrap="nowrap">Số Lượng</th>
                        						<th width="20%" align="right">Tổng</th>
                        				      </tr>
                                        
                                    <?php          
                                        foreach($item_invoice['products'] as $k => $v)
                                        {
                                    ?>
                                            <tr valign="top">
                        					    <td align="center" nowrap="nowrap"><?php echo $v['room_name'] ?></td>
                        					    <td align="left">
                        							<?php echo $v['name'] ?>
                        						</td>
                        					    <td align="right"><?php echo $v['price'] ?></td>
                        						<td align="center"><?php echo $v['quantity'] ?></td>
                        						<td align="right"><?php echo $v['amount'] ?></td>
                        					</tr>
                                    <?php           
                                        }            
                                    ?>
                                            <tr>
                                                <td colspan="3" align="right"><strong>Tổng</strong>:</td>
                        					    <td align="center"><strong><?php echo  $item_invoice['total_quantity']?></strong></td>
                        						<td align="right"><strong><?php echo  $item_invoice['total_amount_']?></strong></td>
                                            </tr>
                                        </table>    
                                <?php
                                    }
                                ?>
                                     
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2" bordercolor="#000000" style="padding-right: 2px; ;">
                                        <tr>
                                            <th></th>
                                            <th width="100%" align="right">
                                                  <?php
                                                        if($item_invoice['discount']>0)
                                                        {
                                                    ?>
                                                        Giảm giá: <?php echo '('.$item_invoice['discount'].'%):'.$item_invoice['discount_amount']?>
                                                 <?php  } ?>
                                            </th>
                                            
                                        </tr>
                                        
                                        
                                    </table>
                                    
                                    <table width="50%" border="0" cellspacing="0" cellpadding="2" bordercolor="#000000" style="float: right; padding-right:2px;">
                                        <tr>
                                            <th align="right">Tip:</th>
                                            <th align="right"><?php echo $item_invoice['total_tip']; ?></th>
                                        </tr>
                                        
                                        <tr>
                                            <th align="right">Phí :</th>
                                            <th align="right"><?php echo System::display_number($item_invoice['service_rate_amount'])?></th>
                                        </tr>
                                        <tr>
                                            <th align="right">Thuế :</th>
                                            <th align="right"><?php echo System::display_number($item_invoice['tax_amount'])?></th>
                                        </tr>
                                        <tr>
                                            <th align="right">Tổng số tiền: </th>
                                            <th align="right"><?php echo System::display_number($item_invoice['total_amount'])?></th>
                                        </tr>
                                    </table>
                                    <br clear="all"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
       </table> 
<?php        
        $output = ob_get_contents();
        ob_end_clean(); 
        return $output;
    }
?>