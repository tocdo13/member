<style>
    .title{
        font-weight: 600;
        font-size: 12px;
        color:black;
    }
    /* unvisited link */
    a:link {
        color: blue;
    }
    
    /* visited link */
    a:visited {
        color: black;
    }
    
    /* mouse over link */
    a:hover {
        color: black;
    }
    
    /* selected link */
    a:active {
        color: black;
    }
    
</style>
<div class="row">
    <div class="container">
        <div class="col-md-10 col-md-offset-1" style="margin-top: 30px;">
            <button class="button pull-right" onclick="print();" style="margin-bottom: 20px;">In</button>
            <label class="pull-right" style="margin-right: 20px;"><input id="choice_print"  name="choice_print" type="radio" value="2" onclick="change_display(this.value);" /> In rút gọn</label>
            <label class="pull-right" style="margin-right: 20px;"><input id="choice_print" checked="" name="choice_print" type="radio" value="1" onclick="change_display(this.value);" /> In đầy đủ</label>
            <div style="clear: both;"></div>
            <!-- Phần in đầy đủ  -->
            <div id="print_full">
            <table class="table col-md-12" width="100%" border="0" cellspacing="0" style="margin-bottom: 0px;">
                <tr>
                    <td class="col-md-4" style="width: 50%;">
                        <img src="<?php echo HOTEL_LOGO; ?>" width="250px;" />
                    </td>
                    <td>
                        <h3 style="text-align: center; margin-top:25px;">PRO-FORMA INVOICE</h3>
                    </td>
                </tr>
                <tr>
                    <td><br /></td>
                    <td><br /></td>
                </tr>
                <tr>
                    <td class="col-md-6">
                        <p><span class="title">Tên khách/Guest Name:</span>
                        <select id="customer_name" name="customer_name">
                        <!--LIST:traveller-->
                            <?php
                                if(!empty([[=traveller.first_name=]]) || !empty([[=traveller.last_name=]])){
                            ?>
                            <option value="[[|traveller.id|]]">[[|traveller.first_name|]] [[|traveller.last_name|]] </option>   
                            <?php        
                                }
                            ?>
                        <!--/LIST:traveller-->
                        </select>
                        <span id="print_cus_name" style="display: none;"></span>
                        </p>
                        <!--LIST:customer_info-->
                            <p><span class="title">Công ty/ Company:</span>
                               <span style="word-wrap: break-word;">[[|customer_info.name|]]</span>
                            </p>
                            <p><span class="title">Địa chỉ/Add:</span>
                               <span style="word-wrap: break-word;">[[|customer_info.address|]]</span> 
                            </p>
                            <p><span class="title">MST/ VAT code:</span>
                               [[|customer_info.tax_code|]] 
                            </p>
                            <p><span class="title">Hình thức thanh toán/Payment term:</span>
                                [[|customer_info.payment_type1|]]
                            </p>
                        <!--/LIST:customer_info--> 
                    </td>
                    <td align="left">
                        <p class="col-md-12" style="padding-left: 20%;"><span class="title">Ngày/Date :</span> <?php echo date('d/m/Y'); ?></p>
                        <p class="col-md-12" style="padding-left: 20%;"><span class="title">Số phòng/Villa No:</span>
                        <?php
                            $str_room = "";
                        ?>
                            <!--LIST:traveller-->
                                <?php $str_room.=[[=traveller.name=]].", "; ?>
                            <!--/LIST:traveller-->
                            <?php 
                                $str_room = substr($str_room,0,strlen($str_room)-2); 
                                if(strlen($str_room)==3){
                                echo $str_room;
                                }
                            ?>
                        </p>
                        <p class="col-md-12" style="padding-left: 20%;"><span class="title">Mã khách/Code:</span><input type="text" style="visibility: hidden; width: 50px;" /></p>
                        <p class="col-md-12" style="padding-left: 20%;"><span class="title">Ngày đến/Check in:</span> <?php echo date("H:i d/m/Y",[[=time_in_longest=]]); ?></p>
                        <p class="col-md-12" style="padding-left: 20%;"><span class="title">Ngày Đi/ Check out:</span> <?php echo date("H:i d/m/Y",[[=time_out_longest=]]); ?></p>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered col-md-12" width="100%" border="1" cellspacing="0" style="margin-top: 10px;" cellpadding="5" >
                <thead>
                    <tr>
                       <!-- <th rowspan="2" width="10%">Ngày</th> -->
                        <th rowspan="2" width="8%">Hóa đơn</th>
                        <th rowspan="2">Diễn giải</th>
                        <th rowspan="2" width="8%">Số lượng</th>
                        <th colspan="2">Thành tiền</th>
                        <th rowspan="2" width="20%">Ghi chú</th>
                    </tr>
                    <tr>
                        <th>VND</th>
                        <th>USD</th>
                    </tr>
                </thead>
                <tbody>  
                    <!--LIST:folio-->
                    <?php if(([[=folio.type=]]=='ROOM' && isset([[=folio.count=]])) || ([[=folio.type=]]!='ROOM' && [[=folio.type=]]!='DEPOSIT' && [[=folio.type=]]!='DEPOSIT_GROUP' && [[=folio.type=]]!='ADVANCE_PAYMENT' && [[=folio.type=]]!='ADVANCE_PAYMENT_GROUP' && [[=folio.type=]]!='DISCOUNT')){
                    ?>
                    <tr>
                      <!--  <td><?php //echo date('d/m/Y',[[=folio.create_time=]]); ?></td> -->
                        <td>
                            <?php
                                switch([[=folio.type=]]){
                                    case 'ROOM':
                                                    echo "";
                                                    break;
                                    case 'LAUNDRY':
                                                    echo "<a style='color:black;' href='?page=laundry_invoice&cmd=edit&id=".[[=folio.id=]]."'>#LD_".[[=folio.position=]]."</a>";
                                                    break;
                                    case 'MINIBAR':
                                                    echo "<a style='color:black;' href='?page=minibar_invoice&cmd=edit&id=".[[=folio.id=]]."'>#MN_".[[=folio.position=]]."</a>";
                                                    break;
                                    case 'EQUIPMENT':
                                                    echo "<a style='color:black;' href='?page=equipment_invoice&cmd=edit&id=".[[=folio.id=]]."'>#".[[=folio.id=]]."</a>";
                                                    break;
                                    case 'EXTRA_SERVICE':
                                                    echo "<a style='color:black;' href='?page=extra_service_invoice&cmd=view_receipt&id=".[[=folio.ex_id=]]."'>#".[[=folio.ex_bill=]]."</a>"; 
                                                    break;   
                                    case 'BAR':
                                                    echo "<a style='color:black;' href='".Url::build('touch_bar_restaurant',array('cmd'=>'detail','act'=>'print_b_e_order','id'=>[[=folio.id=]]))."'>#".[[=folio.code=]]."</a>";  
                                                    break;                                                                                
                                    case 'MASSAGE':
                                                    echo "<a style='color:black;' target='_blank' href='?page=massage_daily_summary&cmd=invoice&id=".[[=folio.id=]]."'>#".[[=folio.id=]]."</a>";
                                                    break;   
                                    case 'VE':
                                                    echo "<a style='color:black;' href=".Url::build('automatic_vend',array('cmd'=>'edit','id'=>[[=folio.id=]],'department_id'=>[[=folio.department_id=]],'department_code'=>[[=folio.department_code=]])).">#".[[=folio.code=]]."</a>";
                                                    break;                    
                                }
                            ?>
                        </td>
                        <td>
                            
                                <div>
                                    <?php if(!empty([[=folio.product=]])){ ?>
                                    <p>- [[|folio.description|]]  </p>
                                    <!--LIST:folio.product-->
                                          <p style="padding-left: 20px; font-style: italic;">+[[|folio.product.name|]] <span class="product_[[|folio.product.id|]]">( <?php echo number_format([[=folio.product.price=]],'0','.',',') ?>đ )</span> <button type="button" onclick="removePrice(this,'print_full');" id="product_[[|folio.product.id|]]"><span class="glyphicon glyphicon-remove" style="color: red;"></span></button> </p> 
                                    <!--/LIST:folio.product--> 
                                    <?php }
                                    else if(isset([[=folio.count=]]) && [[=folio.type=]]!='EXTRA_SERVICE'){
                                    ?>
                                       <p>-[[|folio.description|]]<button type="button" onclick="removePrice(this,'print_full');" id="room_[[|folio.id|]]"><span class="glyphicon glyphicon-remove" style="color: red;"></span></button> </p>
                                    <?php    
                                    }
                                    else if([[=folio.type=]]=='EXTRA_SERVICE'){
                                     ?>
                                      <p>-[[|folio.description|]]<button type="button" onclick="removePrice(this,'print_full');" id="service_[[|folio.id|]]"><span class="glyphicon glyphicon-remove" style="color: red;"></span></button> </p>
                                     <?php   
                                    }
                                                                        
                                     ?>
                                </div>
                               
                        </td>
                        <td>
                                <div>
                                    
                                    <?php if(!empty([[=folio.product=]])){ ?>
                                    <p>&nbsp;</p>
                                    <!--LIST:folio.product-->
                                          <p style="height: 19px; line-height:19px; padding-top:2px;">[[|folio.product.quantity|]]</p> 
                                    <!--/LIST:folio.product--> 
                                    <?php }
                                    else if(isset([[=folio.count=]]) && [[=folio.type=]]!='EXTRA_SERVICE'){
                                    ?>
                                       <p  style="height: 19px; line-height:19px; padding-top:2px;">[[|folio.count|]]</p>
                                    <?php    
                                    }
                                    else if([[=folio.type=]]=='EXTRA_SERVICE'){
                                     ?>
                                      <p  style="height: 19px; line-height:19px; padding-top:2px;">[[|folio.extra_service_quantity|]]</p>
                                     <?php   
                                    }
                                     ?>
                                </div>
                        </td>
                        <td>
                                <div>
                                    
                                    <?php if(!empty([[=folio.product=]])){ ?>
                                    <p>&nbsp;</p>
                                    <!--LIST:folio.product-->
                                          <p style="height: 19px; line-height:19px; padding-top:2px;" total_id="product_[[|folio.product.id|]]" class="product_[[|folio.product.id|]]"><?php echo number_format([[=folio.product.total_product=]],'0','.',',') ?></p> 
                                    <!--/LIST:folio.product--> 
                                    <?php }
                                    else if(isset([[=folio.count=]]) && [[=folio.type=]]!='EXTRA_SERVICE'){
                                    ?>
                                        <p style="height: 19px; line-height:19px; padding-top:2px;" total_id="room_[[|folio.id|]]" class="room_[[|folio.id|]]"><?php echo number_format(([[=folio.total=]]),'0','.',',') ?></p> 
                                    <?php    
                                    }
                                    else if([[=folio.type=]]=='EXTRA_SERVICE'){
                                    ?>
                                         <p style="height: 19px; line-height:19px; padding-top:2px;" total_id="service_[[|folio.id|]]" class="service_[[|folio.id|]]"><?php echo number_format([[=folio.total_amount=]],'0','.',',') ?></p> 
                                    <?php    
                                    }
                                     ?>
                                </div>
                        </td>
                        <td>
                                <div>
                                    
                                    <?php if(!empty([[=folio.product=]])){ ?>
                                    <p>&nbsp;</p>
                                    <!--LIST:folio.product-->
                                          <p style="height: 19px; line-height:19px; padding-top:2px;" total_usd_id="product_[[|folio.product.id|]]" class="product_[[|folio.product.id|]]"><?php echo number_format([[=folio.product.total_product=]]/[[=exchange_rate=]],'2','.',','); ?></p> 
                                    <!--/LIST:folio.product--> 
                                    <?php }
                                    else if(isset([[=folio.count=]]) && [[=folio.type=]]!='EXTRA_SERVICE'){
                                    ?>
                                        <p style="height: 19px; line-height:19px; padding-top:2px;" total_usd_id="room_[[|folio.id|]]" class="room_[[|folio.id|]]"><?php echo number_format([[=folio.total=]]/[[=exchange_rate=]],'2','.',',') ?></p> 
                                    <?php    
                                    }
                                    else if([[=folio.type=]]=='EXTRA_SERVICE'){
                                    ?>    
                                       <p style="height: 19px; line-height:19px; padding-top:2px;" total_usd_id="service_[[|folio.id|]]" class="service_[[|folio.id|]]"><?php echo number_format([[=folio.total_amount=]]/[[=exchange_rate=]],'2','.',',') ?></p>  
                                    <?php
                                    }
                                     ?>
                                </div>
                        </td>
                        <td>
                            <textarea id="notice_full" style="width: 100%;"></textarea>
                            <p id="notice_full_hidden" style="display: none;"></p>
                        </td>
                    </tr>
                    <?php } ?>
                    <!--/LIST:folio--> 
                   <tr>
                        <td colspan="3" align="right">
                            <p style="width: 100%; border-bottom: black thin solid;"><span class="title" style="font-style: italic;">Tỷ giá/Exchance Rate: <?php echo number_format([[=exchange_rate=]],'0','.',','); ?></span></p> 
                            <p><span class="title">Tổng cộng/Subtotal:</span></p>
                            <p><span class="title">Thanh toán trước/Advance payment:</span></p>
                            <p><span class="title">Đặt trước/Deposit:</span></p>
                            <p><span class="title">Còn phải thanh toán/ Pending:</span></p>
                        </td>
                        <td>
                            <p>&nbsp;</p>
                            <p id="total_amount" class="title"><?php echo number_format([[=total_amount=]],'0','.',','); ?></p>
                            <p class="title"><?php echo number_format([[=total_advance_payment=]]+[[=total_group_advance_payment=]],'0','.',','); ?></p>
                            <p class="title"><?php echo number_format([[=total_deposit=]]+[[=total_group_deposit=]],'0','.',','); ?></p>
                            <p id="total_amount_payment" class="title"><?php echo number_format([[=total_amount=]]-[[=total_deposit=]]-[[=total_group_deposit=]],'0','.',','); ?></p>
                        </td>
                        <td>
                            <p>&nbsp;</p>
                            <p id="total_amount_usd" class="title"><?php echo number_format([[=total_amount=]]/[[=exchange_rate=]],'2','.',','); ?></p>
                            <p class="title"><?php echo number_format(([[=total_advance_payment=]]+[[=total_group_advance_payment=]])/[[=exchange_rate=]],'2','.',','); ?></p>
                            <p class="title"><?php echo number_format(([[=total_deposit=]]+[[=total_group_deposit=]])/[[=exchange_rate=]],'2','.',','); ?></p>
                            <p id="total_amount_payment_usd" class="title"><?php echo number_format(([[=total_amount=]]-[[=total_deposit=]]-[[=total_group_deposit=]]-[[=total_advance_payment=]]-[[=total_group_advance_payment=]])/[[=exchange_rate=]],'2','.',','); ?></p>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                           
                           <p><span class="title">Số Tiền Bằng Chữ/Amount in word:</span><span class="total_payment" style="margin-left: 20px; font-size:13px; font-weight:bold; font-style: italic;"></span></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<tr>
            		<td align="center"><br /><br />
            		Cashier's Signature<br>
                    	<i>Thu ngân</i>
             	  </td>
            		<td width="50%" align="center" valign="top">
            			<br /><br />
                        Guest's Signature<br/>
                       <i> Chữ ký khách </i>
            		</td>
            	</tr>
            	<tr>
            		<td colspan="2" align="center"><br /><br /></td>
            	</tr>
            </table>
            <!--ELSE-->
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	<tr>
                	<td height="100">&nbsp;</td>
            	</tr>
            </table>
            </div>
        <!-- Kết thúc phần in đầy đủ  -->
        
        <!-- Phần in rút gọn -->
            <div id="print_simple" style="display: none;">
            <table class="table col-md-12" width="100%" border="0" cellspacing="0" style="margin-bottom: 0px;">
                <tr>
                    <td class="col-md-4">
                        <img src="<?php echo HOTEL_LOGO; ?>" width="250px;" />
                    </td>
                    <td>
                        <h3 style="text-align: center; margin-top:25px;">PRO-FORMA INVOICE</h3>
                    </td>
                </tr>
                <tr>
                    <td><br /></td>
                    <td><br /></td>
                </tr>
                <tr>
                    <td>
                        <p><span class="title">Tên khách/Guest Name:</span>
                        <select id="customer_name_simple" name="customer_name">
                        <!--LIST:traveller-->
                            <?php
                                if(!empty([[=traveller.first_name=]]) || !empty([[=traveller.last_name=]])){
                            ?>
                            <option value="[[|traveller.id|]]">[[|traveller.first_name|]] [[|traveller.last_name|]] </option>   
                            <?php        
                                }
                            ?>
                        <!--/LIST:traveller-->
                        </select>
                        <span id="print_cus_name_simple" style="display: none;"></span>
                        </p>
                        <!--LIST:customer_info-->
                            <p><span class="title">Công ty/ Company:</span>
                               <span style="word-wrap: break-word;">[[|customer_info.name|]]</span>
                            </p>
                            <p><span class="title">Địa chỉ/Add:</span>
                               <span style="word-wrap: break-word;">[[|customer_info.address|]]</span> 
                            </p>
                            <p><span class="title">MST/ VAT code:</span>
                               [[|customer_info.tax_code|]] 
                            </p>
                            <p><span class="title">Hình thức thanh toán/Payment term:</span>
                                [[|customer_info.payment_type1|]]
                            </p>
                        <!--/LIST:customer_info--> 
                    </td>
                    <td align="left">
                        <p class="col-md-5 col-md-offset-7"><span class="title">Ngày/Date :</span> <?php echo date('d/m/Y'); ?></p>
                        <p class="col-md-5 col-md-offset-7"><span class="title">Số phòng/Villa No:</span>
                        <?php
                            $str_room = "";
                        ?>
                            <!--LIST:traveller-->
                                <?php $str_room.=[[=traveller.name=]].", "; ?>
                            <!--/LIST:traveller-->
                            <?php 
                                $str_room = substr($str_room,0,strlen($str_room)-2); 
                                if(strlen($str_room)==3){
                                echo $str_room;
                                }
                            ?>
                        </p>
                        <p class="col-md-5 col-md-offset-7"><span class="title">Mã khách/Code:</span><input type="text" style="visibility: hidden; width: 50px;" /></p>
                        <p class="col-md-5 col-md-offset-7"><span class="title">Ngày đến/Check in:</span> <?php echo date("H:i d/m/Y",[[=time_in_longest=]]); ?></p>
                        <p class="col-md-5 col-md-offset-7"><span class="title">Ngày Đi/ Check out:</span> <?php echo date("H:i d/m/Y",[[=time_out_longest=]]); ?></p>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered col-md-12" width="100%" border="1" cellspacing="0"  style="margin-top: 10px;" cellpadding="5">
                <thead>
                    <tr>
                      <!--  <th rowspan="2" width="10%">Ngày</th> -->
                        <th rowspan="2" width="8%">Hóa đơn</th>
                        <th rowspan="2">Diễn giải</th>
                        <th colspan="2">Thành tiền</th>
                        <th rowspan="2" width="20%">Ghi chú</th>
                    </tr>
                    <tr>
                        <th>VND</th>
                        <th>USD</th>
                    </tr>
                </thead>
                <tbody>
                    <!--LIST:folio-->
                    <?php if((([[=folio.type=]]=='ROOM') || ([[=folio.type=]]!='ROOM' && [[=folio.type=]]!='DEPOSIT' && [[=folio.type=]]!='DEPOSIT_GROUP' && [[=folio.type=]]!='ADVANCE_PAYMENT' && [[=folio.type=]]!='ADVANCE_PAYMENT_GROUP' && [[=folio.type=]]!='DISCOUNT' )) && isset([[=folio.total_amount_print=]])){ ?>
                    <tr>
                       <!-- <td><?php //echo date('d/m/Y',[[=folio.create_time=]]); ?></td> -->
                        <td>
                        </td>
                        <td>
                            
                                
                                    <?php if(isset([[=folio.total_amount_print=]])){
                                    ?>
                                    <p>-
                                    <?php
                                      switch([[=folio.type=]]){
                                          case "EQUIPMENT":
                                                            echo 'Khoản đền bù';
                                                            break;
                                          case "EXTRA_SERVICE":
                                                            echo "Dịch vụ mở rộng";
                                                            break;
                                          case "LAUNDRY":
                                                            echo "Dịch vụ giặt là";
                                                            break;
                                          case "MINIBAR":
                                                            echo "Dịch vụ minibar";
                                                            break;
                                          case "ROOM"    :
                                                            echo "Dịch vụ lưu trú"; 
                                                            break;                                                                                            
                                          case "BAR"     :
                                                           echo "Dịch vụ ẩm thực";
                                                           break;                                                           
                                          case "MASSAGE"     :
                                                           echo "Dịch vụ massage";
                                                           break;  
                                          case "VE"      :
                                                            echo "Dịch vụ bán hàng";
                                                            break;                                                                              
                                      }
                                    ?>
                                    <button type="button" onclick="removePrice(this,'print_simple');" id="items_[[|folio.id|]]"><span class="glyphicon glyphicon-remove" style="color: red; font-size:10px;"></span></button></p>
                                    <?php    
                                    }
                                     ?>                
                        </td>
                        <td>
                                <?php
                                    if(isset([[=folio.total_amount_print=]])){
                                ?>
                                <p style="height: 18px; line-height:18px; padding-top:2px;" total_id="items_[[|folio.id|]]" class="items_[[|folio.id|]]"><?php echo number_format([[=folio.total_amount_print=]],0,'.',','); ?></p>
                                <?php
                                    }
                                ?>
                        </td>
                        <td>
                                <?php
                                    if(isset([[=folio.total_amount_print=]])){
                                ?>
                                <p style="height: 18px; line-height:18px; padding-top:2px;" total_usd_id="items_[[|folio.id|]]" class="items_[[|folio.id|]]"><?php   echo number_format([[=folio.total_amount_print=]]/[[=exchange_rate=]],2,'.',',');  ?></p>
                                <?php
                                    }
                                ?>
                            
                        </td>
                        <td>
                            <textarea id="notice_simple" style="width: 100%;"></textarea>
                            <p id="notice_simple_hidden" style="display: none;"></p>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <!--/LIST:folio-->  
                    <tr>
                        <td colspan="2" align="right">
                             <p style="width: 100%; border-bottom: black thin solid;"><span class="title" style="font-style: italic;">Tỷ giá/Exchance Rate: <?php echo number_format([[=exchange_rate=]],'0','.',','); ?></span></p> 
                            <p><span class="title">Tổng cộng/Subtotal:</span></p>
                            <p><span class="title">Thanh toán trước/Advance payment:</span></p>
                            <p><span class="title">Đặt trước/Deposit:</span></p>
                            <p><span class="title">Còn phải thanh toán/ Pending:</span></p>
                        </td>
                        <td>
                            <p>&nbsp;</p>
                            <p id="total_amount" class="title"><?php echo number_format([[=total_amount=]],'0','.',','); ?></p>
                            <p class="title"><?php echo number_format([[=total_advance_payment=]]+[[=total_group_advance_payment=]],'0','.',','); ?></p>
                            <p class="title"><?php echo number_format([[=total_deposit=]]+[[=total_group_deposit=]],'0','.',','); ?></p>
                            <p id="total_amount_payment" class="title"><?php echo number_format([[=total_amount=]]-[[=total_deposit=]]-[[=total_group_deposit=]],'0','.',','); ?></p>
                        </td>
                        <td>
                            <p>&nbsp;</p>
                            <p id="total_amount_usd" class="title"><?php echo number_format([[=total_amount=]]/[[=exchange_rate=]],'2','.',','); ?></p>
                            <p class="title"><?php echo number_format(([[=total_advance_payment=]]+[[=total_group_advance_payment=]])/[[=exchange_rate=]],'2','.',','); ?></p>
                            <p class="title"><?php echo number_format(([[=total_deposit=]]+[[=total_group_deposit=]])/[[=exchange_rate=]],'2','.',','); ?></p>
                            <p id="total_amount_payment_usd" class="title"><?php echo number_format(([[=total_amount=]]-[[=total_deposit=]]-[[=total_group_deposit=]]-[[=total_advance_payment=]]-[[=total_group_advance_payment=]])/[[=exchange_rate=]],'2','.',','); ?></p>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                           <p><span class="title">Số Tiền Bằng Chữ/Amount in word:</span><span class="total_payment" style="margin-left: 20px; font-size:13px; font-weight:bold; font-style: italic;"></span></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<tr>
            		<td align="center"><br /><br />
            		Cashier's Signature<br>
                    	<i>Thu ngân</i>
             	  </td>
            		<td width="50%" align="center" valign="top">
            			<br /><br />
                        Guest's Signature<br/>
                       <i> Chữ ký khách </i>
            		</td>
            	</tr>
            	<tr>
            		<td colspan="2" align="center"><br /><br /></td>
            	</tr>
            </table>
            <!--ELSE-->
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	<tr>
                	<td height="100">&nbsp;</td>
            	</tr>
            </table>
        </div>
        <!--Kết thúc in rút gọn-->
        </div>
    </div>
</div>   
<script>

Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
    
var ChuSo=new Array(" không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín ");
var Tien=new Array( "", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ");
function DocSo3ChuSo(baso)
{
    var tram;
    var chuc;
    var donvi;
    var KetQua="";
    tram=parseInt(baso/100);
    chuc=parseInt((baso%100)/10);
    donvi=baso%10;
    if(tram==0 && chuc==0 && donvi==0) return "";
    if(tram!=0)
    {
        KetQua += ChuSo[tram] + " trăm ";
        if ((chuc == 0) && (donvi != 0)) KetQua += " linh ";
    }
    if ((chuc != 0) && (chuc != 1))
    {
            KetQua += ChuSo[chuc] + " mươi";
            if ((chuc == 0) && (donvi != 0)) KetQua = KetQua + " linh ";
    }
    if (chuc == 1) KetQua += " mười ";
    switch (donvi)
    {
        case 1:
            if ((chuc != 0) && (chuc != 1))
            {
                KetQua += " mốt ";
            }
            else
            {
                KetQua += ChuSo[donvi];
            }
            break;
        case 5:
            if (chuc == 0)
            {
                KetQua += ChuSo[donvi];
            }
            else
            {
                KetQua += " lăm ";
            }
            break;
        default:
            if (donvi != 0)
            {
                KetQua += ChuSo[donvi];
            }
            break;
        }
    return KetQua;
}
function DocTienBangChu(SoTien)
    {
    var lan=0;
    var i=0;
    var so=0;
    var KetQua="";
    var tmp="";
    var ViTri = new Array();
    if(SoTien<0) return "Số tiền âm !";
    if(SoTien==0) return "Không đồng !";
    if(SoTien>0)
    {
        so=SoTien;
    }
    else
    {
        so = -SoTien;
    }
    if (SoTien > 8999999999999999)
    {
        return "Số quá lớn!";
    }
    ViTri[5] = Math.floor(so / 1000000000000000);
    if(isNaN(ViTri[5]))
        ViTri[5] = "0";
    so = so - parseFloat(ViTri[5].toString()) * 1000000000000000;
    ViTri[4] = Math.floor(so / 1000000000000);
     if(isNaN(ViTri[4]))
        ViTri[4] = "0";
    so = so - parseFloat(ViTri[4].toString()) * 1000000000000;
    ViTri[3] = Math.floor(so / 1000000000);
     if(isNaN(ViTri[3]))
        ViTri[3] = "0";
    so = so - parseFloat(ViTri[3].toString()) * 1000000000;
    ViTri[2] = parseInt(so / 1000000);
     if(isNaN(ViTri[2]))
        ViTri[2] = "0";
    ViTri[1] = parseInt((so % 1000000) / 1000);
     if(isNaN(ViTri[1]))
        ViTri[1] = "0";
    ViTri[0] = parseInt(so % 1000);
    if(isNaN(ViTri[0]))
        ViTri[0] = "0";
    if (ViTri[5] > 0)
    {
        lan = 5;
    }
    else if (ViTri[4] > 0)
    {
        lan = 4;
    }
    else if (ViTri[3] > 0)
    {
        lan = 3;
    }
    else if (ViTri[2] > 0)
    {
        lan = 2;
    }
    else if (ViTri[1] > 0)
    {
        lan = 1;
    }
    else
    {
        lan = 0;
    }
    for (i = lan; i >= 0; i--)
    {
       tmp = DocSo3ChuSo(ViTri[i]);
       KetQua += tmp;
       if (ViTri[i] > 0) KetQua += Tien[i];
       if ((i > 0) && (tmp.length > 0)) KetQua += ' ';
    }
   if (KetQua.substring(KetQua.length - 1) == ' ')
   {
        KetQua = KetQua.substring(0, KetQua.length - 1);
   }
   KetQua = KetQua.substring(1,2).toUpperCase()+ KetQua.substring(2)+' đồng';
   return KetQua;
}
   var total_payment = <?php echo round(([[=total_amount=]]-[[=total_deposit=]]-[[=total_group_deposit=]]-[[=total_advance_payment=]]-[[=total_group_advance_payment=]]),0); ?> 
    if(total_payment>0){
        jQuery(".total_payment").html(DocTienBangChu(total_payment));
    }
    
    
    function print(){
      value = jQuery("textarea#notice").val();
      jQuery("#notice_print").html(value);
      //alert(value);
      var choose = jQuery("#choice_print:checked").val();
      if(choose==1){
        
        var cus_id = jQuery("select#customer_name").val();
        var cus_name = jQuery("select#customer_name option[value="+cus_id+"]").html();
        jQuery("select#customer_name").css('display','none');
        jQuery("span#print_cus_name").html(cus_name);
        jQuery("span#print_cus_name").css('display','');
        
        
         jQuery("textarea#notice_full").each(function(){
               var content = jQuery(this).val();
               jQuery(this).css('display','none');
               jQuery(this).parent().find('p').html(content);
               jQuery(this).parent().find('p').css('display','');
         });
        
        jQuery("#print_full button[id]").each(function(){
            jQuery(this).css('display','none');
        });
        jQuery("#print_full td").css('font-size','11px');
        jQuery("#print_full p").css('font-size','11px');
        jQuery("#print_full span").css('font-size','11px');
        jQuery("#print_full p").css({'height':'15px','line-height':'15px'});
        printWebPart('print_full','<?php echo User::id(); ?>');
      }
      else{
        
        var cus_id = jQuery("select#customer_name_simple").val();
        var cus_name = jQuery("select#customer_name_simple option[value="+cus_id+"]").html();
        jQuery("select#customer_name_simple").css('display','none');
        jQuery("span#print_cus_name_simple").html(cus_name);
        jQuery("span#print_cus_name_simple").css('display','');
        
        
        jQuery("textarea#notice_simple").each(function(){
               var content = jQuery(this).val();
               jQuery(this).css('display','none');
               jQuery(this).parent().find('p').html(content);
               jQuery(this).parent().find('p').css('display','');
         });
        
        jQuery("#print_simple button[id]").each(function(){
            jQuery(this).css('display','none');
        }); 
             
        printWebPart('print_simple','<?php echo User::id(); ?>');
      }
      
    }
    
    function change_display(value){
        if(value==1){
            var status = jQuery("#print_full:visible").length;
            if(!status){
             
            jQuery("#print_full button[id]").each(function(){
            jQuery(this).css('display','');
            });    
            jQuery("#notice_full").val(jQuery("#notice_simple").val());    
            jQuery("#print_full").show("fast");
             jQuery("#print_simple").hide("fast");  
             
             
             var cus_id = jQuery("select#customer_name_simple").val(); 
                        jQuery("select#customer_name option").each(function(){
                            if(jQuery(this).attr('value')==cus_id){
                                jQuery(this).attr('checked');
                            }
                        });
            }
        }
        else{
            var status = jQuery("#print_simple:visible").length;
            if(!status){
             jQuery("#print_simple button[id]").each(function(){
            jQuery(this).css('display','');
            });    
                
             jQuery("#print_simple").show("fast");
             jQuery("#print_full").hide("fast");  
             jQuery("#notice_simple").val(jQuery("#notice_full").val());  
             var cus_id = jQuery("select#customer_name").val(); 
                        jQuery("select#customer_name_simple option").each(function(){
                            if(jQuery(this).attr('value')==cus_id){
                                jQuery(this).attr('checked');
                            }
                        }); 
              
            } 
        }
    }
    
    function removePrice(obj, target){
        var id = jQuery(obj).attr('id');
        
        var current = jQuery("#"+target+" p#total_amount").html();
            current = to_numeric(current);
          
            
        var current_usd = jQuery("#"+target+" p#total_amount_usd").html();
            current_usd = to_numeric(current_usd);  
        
        var current_payment = jQuery("#"+target+" p#total_amount_payment").html();
            current_payment = to_numeric(current_payment);  
        
        var current_payment_usd = jQuery("#"+target+" p#total_amount_payment_usd").html();
            current_payment_usd = to_numeric(current_payment_usd); 
              
            //alert(current);
            value = jQuery("#"+target+" p[total_id="+id+"]").html();
            value = to_numeric(value);
                        
            value_usd = jQuery("#"+target+" p[total_usd_id="+id+"]").html();
            value_usd = to_numeric(value_usd);
            
        
        jQuery("#"+target+" ."+id).each(function(){
            if(jQuery(this).css('visibility')!='hidden'){
                //console.log(this);
                jQuery("#"+target+" p#total_amount").html((current-value).formatMoney(0,'.',','));
                jQuery("#"+target+" p#total_amount_payment").html((current_payment-value).formatMoney(0,'.',','));
                
                
                jQuery("#"+target+" p#total_amount_usd").html((current_usd-value_usd).formatMoney(2,'.',','));
                jQuery("#"+target+" p#total_amount_payment_usd").html((current_payment_usd-value_usd).formatMoney(2,'.',','));
                
                var total_payment = current_payment-value;
                    if(total_payment>0){
                        jQuery("#"+target+" .total_payment").html(DocTienBangChu(total_payment));
                    }
                    else{
                        jQuery("#"+target+" .total_payment").html("");
                    }
                
                jQuery(this).css('visibility','hidden');
            }
            else{
                //console.log(this);
                jQuery("#"+target+" p#total_amount").html((current+value).formatMoney(0,'.',','));
                jQuery("#"+target+" p#total_amount_payment").html((current_payment+value).formatMoney(0,'.',','));
                
                
                jQuery("#"+target+" p#total_amount_usd").html((current_usd+value_usd).formatMoney(2,'.',','));
                jQuery("#"+target+" p#total_amount_payment_usd").html((current_payment_usd+value_usd).formatMoney(2,'.',','));
                
                var total_payment = current_payment+value;
                    if(total_payment>0){
                        jQuery("#"+target+" .total_payment").html(DocTienBangChu(total_payment));
                    }
                    else{
                        jQuery("#"+target+" .total_payment").html("");
                    }
                jQuery(this).css('visibility','visible');
            }
        });
    }
</script> 