<style type="text/css">
	 @media print{
	   #printer_logo{
	       display: none;
	   }
  }
  *{font-family: sans-serif, Arial, Tahoma;}	
</style>
<!---------------------------------- LAYOUT KHO GIAY K8 ---------------------------------------------->
<!--Start - them button in.khi in goi ajax cap nhat truong intamtinh-->
<div id="printer_logo">
<a onclick="var content = jQuery('#printer').html();printWebPart_Tan(content);printed_tmp_bill();" title="Print" style="float: right;"><img src="packages/core/skins/default/images/printer.png" height="40"></a>
<a href="<?php echo '?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.Url::get('id').'&bar_id='.Url::get('bar_id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&print_automatic_bill=1&portal='.Url::get('portal'); ?>" class="button-medium" style="float: right;"><?php echo Portal::language('print_automatic_bill');?></a>
<input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 100px; height: 23px"/>
</div>
<!--End - them button in.khi in goi ajax cap nhat truong intamtinh-->
<table id="Export" width="100%" style="position: relative; float:left;"><tr><td>
<div id="printer" style="width:300px; padding:0px; text-align:left;">
    <div class="restaurant-invoice-bound" style="width: 100%;">
        <table style="border-bottom: 1px solid #dddddd;" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td id="srcs" style="text-align: center;"><div style="width: 100%; height: 120px; overflow: hidden; text-align: center;"><img src="<?php echo HOTEL_LOGO;?>" style="width: 200px; height: auto;" /></div></td>
            </tr>
            <tr>
                <td style="font-size: 18px; font-weight: bold; text-align: center; line-height: 25px;">INVOICE / HÓA ĐƠN</td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold; text-align: center;"><?php echo Portal::language('date');?> :<?php echo date('d/m/Y H:i\'');?></td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold; text-align: center;"><?php echo Portal::language('checkout_time');?>: <?php echo $this->map['table_checkout'];?></td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold; text-align: center;"><?php echo Portal::language('print_user');?>: <?php echo User::id(); ?></td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold; text-align: center;">Table No :<span style="font-size:18px;"><strong><?php echo $this->map['tables_name'];?></strong></span></td>
                
            </tr>
            
            <tr>
                <td style="font-size: 17px; font-weight: bold; text-align: center;"><?php echo Portal::language('Room');?> :<span style="font-size:18px;"><strong><?php echo $this->map['room_package_name'];?></strong></span></td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold; text-align: center;"><?php echo Portal::language('Package');?> :<span style="font-size:18px;"><strong><?php echo $this->map['package_name'];?></strong></span></td>
            </tr>
        </table>
        <table style="border-bottom: 1px solid #000000;" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <?php  
                if(!empty($this->map['customer_name'])){
                ?>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('guest_name');?> :<?php echo $this->map['customer_name']['fullname']  ?>/    <?php echo Portal::language('room');?>: <?php echo $this->map['room_name'];?></td>
                <?php }else{ ?>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('guest_name');?> :<?php echo $this->map['receiver_name'];?></td>
                <?php } ?>  
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('company');?>:<?php if(!empty($this->map['customer_name'])) echo $this->map['customer_name']['cname']; else echo $this->map['agent_name'] ?> </td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('code');?>: <?php echo $this->map['order_id'];?></td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('guest_no');?> :<?php echo $this->map['num_people'];?> </td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('cashier');?> :<?php echo $this->map['checkout_user']; ?> </td>
            </tr>
            <tr>
                <td style="font-size: 17px; font-weight: bold;"><?php echo Portal::language('print_usser');?> :<?php echo $this->map['print_user']; ?> </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000" style="font-weight: bold;">
            <tr>
          <td colspan="4">
          <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px solid #000000; font-weight: bold;">
          <tr valign="top">
            <th colspan="4" width="100%" align="left" style="border-bottom:1px solid #B8B9BF;font-size: 17px;">Diễn giải/Detail </th> 
          </tr>
          <tr>
                <th width="30%" align="center" style="border-bottom:none !important; font-size: 17px;">S.L used</th>
                <th width="30%" align="left"  style="font-size: 17px;">Đ.G Price</th>
                <th  align="left"  style="font-size: 17px;">Giảm(%) Disc(%)</th>
                <th align="right" style="font-size: 17px;">Tổng Amount</th>
            </tr>
          </table>
          </td>
          </tr>
            <?php $i=1;?>
          <?php if(isset($this->map['product_items']) and is_array($this->map['product_items'])){ foreach($this->map['product_items'] as $key1=>&$item1){if($key1!='current'){$this->map['product_items']['current'] = &$item1;?>
          <?php 
				if(($this->map['product_items']['current']['product__remain_quantity']!=0 && $this->map['product_items']['current']['cancel_all']==0))
				{?>
          <tr>
          <td colspan="4">
          <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px dashed #000000;font-weight: bold;">
          <tr valign="top">
           
            <td width="100%" align="left" colspan="4"style="border-bottom:1px solid #B8B9BF; font-size: 17px;text-transform: capitalize;"><?php //echo $i;?>. <?php echo $this->map['product_items']['current']['product__name'];?><?php //if(isset($this->map['product_items']['current']['chair_number'])!='') echo '<br/>'.' Số ghế :'.$this->map['product_items']['current']['chair_number'] ?></td> 
          </tr>
          <tr>
            <td width="30%" align="center" style=" font-size: 17px;"><?php echo $this->map['product_items']['current']['product__remain_quantity'];?></td>
            <td width="30%" align="left" style=" font-size: 17px;"><?php echo $this->map['product_items']['current']['product__price'];?></td>
            <td width="20%" align="right;" style=" font-size: 17px;"><?php echo $this->map['product_items']['current']['product__discount'];?></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['product_items']['current']['product__total'];?></td>
          </tr>
          </table>
          </td>
          </tr>
          <?php $i++;?>
          
				<?php
				}
				?>
          <?php 
				if(($this->map['product_items']['current']['product__quantity_discount']!=0))
				{?>
          <tr>
          <td colspan="4">
          <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px dashed #000000; font-weight: bold;">
          <tr valign="top">
          
            <td width="100%" align="left" colspan="4" style="border-bottom:1px solid #B8B9BF; font-size: 17px; text-transform: capitalize;"><?php //echo $i;?>. <?php echo $this->map['product_items']['current']['product__name'];?> <strong>(<?php echo Portal::language('promotion');?>)</strong><?php //if(isset($this->map['product_items']['current']['chair_number'])!='') echo '<br/>'.' Số ghế :'.$this->map['product_items']['current']['chair_number'] ?></td>
          </tr>
          <tr>
            <td width="30%" align="center" style="font-size: 17px;"><?php echo $this->map['product_items']['current']['product__quantity_discount'];?></td>
            <td width="30%" align="left" style="font-size: 17px;"><?php echo $this->map['product_items']['current']['product__price'];?></td>
            <td width="20%" align="right" style="font-size: 17px;">&nbsp;</td>
            <td align="right" style="font-size: 17px;">0</td>
          </tr>
          </table>
          </td>
          </tr>
          
          <?php $i++;?> 
          
				<?php
				}
				?>
          <?php 
				if(($this->map['product_items']['current']['cancel_all']==1))
				{?>
           <!--<tr>
          <td colspan="4">
          <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px dashed #000000;font-weight: bold;">
          <tr valign="top">
            <td align="left" colspan="4" style="border-bottom:1px solid #B8B9BF; font-size: 15px;text-transform: capitalize;"><?php echo $i;?>. <?php echo $this->map['product_items']['current']['product__name'];?> <strong>(<?php echo Portal::language('cancel_all');?>)</strong></td>
          </tr> 
          <tr>
            <td width="30%" align="center" style="font-size: 15px;"><?php echo $this->map['product_items']['current']['product__quantity'];?></td>
            <td width="30%" align="left" style="font-size: 15px;"><?php echo $this->map['product_items']['current']['product__price'];?></td>
            <td width="20%" align="right" style="font-size: 15px;">&nbsp;</td>
            <td align="left" style="font-size: 15px;">&nbsp;</td>
          </tr> 
          </table>
          </td>
          </tr>  -->        
          <?php //$i++;?> 
          
				<?php
				}
				?>
          <?php }}unset($this->map['product_items']['current']);} ?>
          
          <?php if(isset($this->map['set_product_items']) and is_array($this->map['set_product_items'])){ foreach($this->map['set_product_items'] as $key2=>&$item2){if($key2!='current'){$this->map['set_product_items']['current'] = &$item2;?>
              <tr>
              <td colspan="4">
              <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px dashed #000000;font-weight: bold;">
              <tr valign="top">
                <td width="100%" align="left" colspan="4"style="border-bottom:1px solid #B8B9BF; font-size: 17px;text-transform: capitalize;"><?php //echo $i;?>. <?php echo $this->map['set_product_items']['current']['name'];?></td> 
              </tr>
              <tr>
                <td width="30%" align="center" style=" font-size: 14px;"><?php echo $this->map['set_product_items']['current']['product__remain_quantity'];?></td>
                <td width="30%" align="left" style=" font-size: 14px;"><?php echo $this->map['set_product_items']['current']['product__price'];?></td>
                <td width="20%" align="right;" style=" font-size: 14px;"><?php echo $this->map['set_product_items']['current']['product__discount'];?></td>
                <td align="right" style="font-size: 14px;"><?php echo $this->map['set_product_items']['current']['product__total'];?></td>
              </tr>
              <?php
                    if(is_array($this->map['set_product_items']['current']['items'])){
                        foreach($this->map['set_product_items']['current']['items'] as $key=>$value){
                        if($value['product__remain_quantity']!=0 && $value['cancel_all']==0){
                      ?>
              <tr valign="top">
                <td width="100%" align="left" colspan="4"style="border-bottom:1px solid #B8B9BF; font-size: 14px;text-transform: capitalize; padding-left: 20px; font-style: italic;"> + <?php echo $value['product__name']; ?></td> 
              </tr>        
              <tr>
                <td width="30%" align="center" style=" font-size: 14px;"><?php echo $value['product__remain_quantity']; ?></td>
                <td width="30%" align="left" style=" font-size: 14px;"><?php echo $value['product__price']; ?></td>
                <td width="20%" align="right;" style=" font-size: 14px;"><?php echo $value['product__discount']; ?></td>
                <td align="right" style="font-size: 14px;"><?php echo $value['product__total']; ?></td>
              </tr>
              <?php
                        }
                    }
                  }  
              ?>
              </table>
              </td>
              </tr>
              <?php
                if($value['product__quantity_discount']!=0){
              ?>
              <tr valign="top">
                <td width="100%" align="left" colspan="4"style="border-bottom:1px solid #B8B9BF; font-size: 14px;text-transform: capitalize; padding-left: 20px; font-style: italic;"> + <?php echo $value['product__name']; ?></td> 
              </tr>
              <tr>
              <td colspan="4">
              <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px dashed #000000; font-weight: bold;">
              <tr valign="top">
                <td width="100%" align="left" colspan="4" style="border-bottom:1px solid #B8B9BF; font-size: 14px; text-transform: capitalize;"><?php echo $value['product__name']; ?> <strong>(<?php echo Portal::language('promotion');?>)</strong></td>
              </tr>
              <tr>
                <td width="30%" align="center" style="font-size: 14px;"><?php echo $value['product__quantity_discount']; ?></td>
                <td width="30%" align="left" style="font-size: 14px;"><?php echo $value['product__price']; ?></td>
                <td width="20%" align="right" style="font-size: 14px;">&nbsp;</td>
                <td align="right" style="font-size: 14px;">0</td>
              </tr>
              </table>
              </td>
              </tr>
              <?php
                }
              ?>
              <?php $i++;?> 
          <?php }}unset($this->map['set_product_items']['current']);} ?>
          
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('amount');?>/Amount:</strong></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['amount'];?></td>
          </tr>
          
          <?php 
				if(($this->map['total_discount']!='0.00'))
				{?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('product_discounted');?>/Product Discounted:  </strong></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['total_discount'];?></td>
          </tr>
          
				<?php
				}
				?>
          <?php 
				if(($this->map['discount_after_tax']==0))
				{?>
              <?php 
				if(($this->map['discount_percent_dn']!='0.00'))
				{?>
              <tr>
                <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('discount_amount');?>/Order Discount:  </strong></td>
                <td align="right" style="font-size: 17px;"><?php echo $this->map['discount_percent_dn'];?></td>
              </tr>
              
				<?php
				}
				?>
          
				<?php
				}
				?>
           <?php 
				if(($this->map['order_discount']!='0.00'))
				{?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong>(<?php echo $this->map['discount_percent'];?>%) <?php echo Portal::language('order_discount');?>/Order Discount:</strong></td>
            <td align="right" style="font-size: 17px;"><?php echo System::display_number($this->map['order_discount']);?></td>
          </tr>
          
				<?php
				}
				?>
          <?php 
				if(($this->map['bar_fee']!='0.00'))
				{?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong> (<?php echo $this->map['bar_fee_rate'];?>%)<?php echo Portal::language('service_chrg');?>/Service charge: </strong></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['bar_fee'];?></td>
          </tr>
          
				<?php
				}
				?>
          <?php 
				if(($this->map['tax']!='0.00'))
				{?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong>(<?php echo $this->map['tax_rate'];?>%) <?php echo Portal::language('tax_rate');?>/Tax:</strong></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['tax'];?></td>
          </tr>
          
				<?php
				}
				?>
          <?php 
				if(($this->map['discount_after_tax']!=0))
				{?>
              <?php 
				if(($this->map['discount_percent_dn']!='0.00'))
				{?>
              <tr>
                <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('discount_amount');?>/Order Discount:  </strong></td>
                <td align="right" style="font-size: 17px;"><?php echo $this->map['discount_percent_dn'];?></td>
              </tr>
              
				<?php
				}
				?>
          
				<?php
				}
				?>
           <?php 
				if(($this->map['deposit']!='0.00'))
				{?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong>Đặt cọc/ Deposit:</strong></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['deposit'];?></td>
          </tr>
          
				<?php
				}
				?>
          <?php if($this->map['total_payment_traveller']>=1){ ?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('total_payment_traveller');?>:</strong></td>
            <td align="right" style=""><span style="font-size:17px;font-weight:bold;"><?php echo System::display_number($this->map['total_payment_traveller']); ?></span></td>
          </tr>
          <?php } ?>          
          <tr>
            <td colspan="3" style="border:1px solid #000;border-right:0px;font-size: 17px;"><strong><?php echo Portal::language('sum_total');?>/Grant Total:</strong></td>
            <td align="right" style="border:1px solid #000;border-left:0px;font-size: 17px;"><span style="font-size:17px;font-weight:bold;"><?php echo $this->map['sum_total'];?></span></td>
          </tr>          
          <?php if($this->map['total_payment_traveller']>=1){ ?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('total_remain_traveller');?>:</strong></td>
            <td align="right" style=""><span style="font-size:17px;font-weight:bold;"><?php echo System::display_number($this->map['total_payment_traveller']-System::calculate_number($this->map['sum_total'])); ?></span></td>
          </tr>
          <?php } ?>
           <!--
           <tr>
            <td colspan="3" style="border:1px solid #000;border-right:0px;font-size: 17px;"><strong><?php echo Portal::language('sum_total');?>/Grant Total USD:</strong><div class="sub-item-body"></div></td>
            <td align="right" style="border:1px solid #000;border-left:0px;font-size: 17px;"><span style="font-size:17px;font-weight:bold;"><?php echo $this->map['sum_total_usd'];?></span></td>
          </tr>
          -->
            <?php 
				if(($this->map['prepaid']!='0.00'))
				{?>
          <tr>
            <td colspan="3" style="font-size: 17px;"><strong><?php echo Portal::language('prepaid');?>/Deposit:</strong></td>
            <td align="right" style="font-size: 17px;"><?php echo $this->map['prepaid'];?></td>
          </tr>
          <tr>
            <td colspan="3" style="border:2px solid #000;border-right:0px;font-size: 17px;"><strong><?php echo Portal::language('remain_paid');?>/ Remain:</strong></td>
            <td align="right"><span style="font-size:17px;font-weight:bold;"><?php echo $this->map['remain_prepaid'];?></span></td>
          </tr>
           <!--<tr>
            <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
            <td align="right"><span style="font-size:12px;font-weight:bold;">USD <?php echo $this->map['remain_prepaid_usd'];?></span></td>
          </tr>-->
          
				<?php
				}
				?>
        </table>
        <?php if(sizeof($this->map['payment_list'])>0){ ?>
        <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" style="font-weight: bold; margin-top: 3px;">
            <tr>
                <th colspan="2" style="font-size: 15px;"><?php echo Portal::language('payment');?></th>
            </tr>
           
            <?php foreach($this->map['payment_list'] as $k => $v)
            {
            ?>
            <tr>
                <td style="font-size: 15px;"><?php echo $v['payment_type_name'] ?></td>
                <td style="font-size: 15px;text-align: right;"><?php echo System::display_number($v['amount']); ?> <?php echo $v['currency_id'] ?></td>
            </tr>
            <?php
            }
            ?>  
            
        </table>
        <?php } ?>
        <div style="color:#F00;font-weight:bold;padding-top:10px;font-size:17px; float: right;"><?php echo $this->map['preview'];?></div>
			<table cellpadding="5" cellspacing="0" width="100%" style="height:50px;border-collapse:collapse;" border="0" bordercolor="silver">
            	<tr>
                	<td width="33%" align="center" style="font-size: 15px;">Thu Ngân <br> <span style="font-style:italic">Cashier</span></td>
                	<td width="33%" align="center" style="font-size: 15px;">Số Phòng<br> <span style="font-style:italic">Room No</span></td>
                	<td align="center" style="font-size: 15px;">Chữ ký khách hàng<br> <span style="font-style:italic">Customer's signature</span></td>
                </tr>
                <tr>
                	<td width="33%" align="center"><br><br><br><br></td>
                	<td valign="middle" align="center"><?php 
				if((isset($this->map['room_name']) and $this->map['room_name']))
				{?>(<?php echo $this->map['room_name'];?>)
				<?php
				}
				?></td>
                	<td></td>
                </tr>
            </table>
    </div>
</div>
</td></tr></table>
<script>
    /** START - an button in cua menu**/
    var txt=document.getElementById("chang_language").innerHTML;
    txt = txt.replace('<a onclick="printWebPart(\'printer\');" title="Print"><img src="packages/core/skins/default/images/printer.png" height="40"></a> |', "");
    txt = txt.replace('<a onclick="printWebPart(\'printer\');" title="In"><img src="packages/core/skins/default/images/printer.png" height="40"></a> |', "");
    document.getElementById("chang_language").innerHTML = txt;
    /** END - an button in cua menu**/
    /** ham cap nhat trang thai da in cua hoa don tam tinh**/
    var bar_reservation_id = <?php echo ($_REQUEST['id']);  ?>;
    function printed_tmp_bill(){
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","packages/hotel/packages/restaurant/modules/TouchBarRestaurant/ajax.php?cmd=printed_tmp_bill&bar_reservation_id="+bar_reservation_id,true);        
        opener.location.reload();
        xmlhttp.send();
    }
    
	var printt = '<?php echo (Url::get(md5('preview'))?Url::get(md5('preview')):'');?>';
    if(printt != ''){
		jQuery("#note").css('display','none');	
	}

    function printWebPart_Tan(content){
    	if(content)
    	{
    		var html = "";
    		html = "<html><head>"+
    		"<link rel=\"stylesheet\" type=\"text/css\" href=\"packages/core/skins/default/css/global.css\" media=\"print\" ></link>"+
            "<style type=\"text/css\">"+
            "*{ font-family: sans-serif, Arial, Tahoma; }"+
            "</style>"+
    		"</head><body >"+
    		content+
    		"</body></html>";
    		width = jQuery(document).width();
    		height = jQuery(document).height();
    		html = html.replace('packages/core/includes/js/common.js','');
    		var printWP = window.open("about:blank","printWebPart_Tan");
    		printWP.document.open();
    		printWP.document.write(html);
            //printWP.document.body.style.zoom= "40%";
    		printWP.print();
    		printWP.document.close();
    		printWP.close();
    	}
    }
	var printt = '<?php echo (Url::get(md5('preview'))?Url::get(md5('preview')):'');?>';
    if(printt ==''){
        //console.log('aaa');
		//printWebPart('printer');
        //console.log('aaa');
        var content = jQuery("#printer").html();	
	    window.close();
        printWebPart_Tan(content);
    }
	<?php 
				if(($this->map['preview']))
				{?>
	function handleKeyPress(evt) {  
		var nbr;  
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==17 || nbr==80){
			//if(!confirm('<?php echo Portal::language('Are_you_sure_to_add_reservation');?>?')){
			return false;
			//}
		}
		return true;
	}
	document.onkeydown= handleKeyPress;
	jQuery('body').disableTextSelect();
	jQuery(document).ready(function(){ 
		   jQuery(document).bind("contextmenu",function(e){
				  return false;
		   }); 
	});
	
				<?php
				}
				?>
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 34;
	var i = 1;
	var j = 0;
	var page = 1;
	/*if((itemBodySize + subItemBodySize) < maxLine){
		jQuery(".item-body").each(function(){
			if(j == itemBodySize -2 ){
				for(var c = 0;c <= (maxLine - itemBodySize);c++){
					jQuery(this).after('<div class="item-body"><div class="date">&nbsp;</div><div class="description">&nbsp;</div><div class="amount">&nbsp;</div></div>');
				}
			}
			j++;
		});
	}else */
	{
		jQuery(".item-body").each(function(){
			if(i<(itemBodySize + subItemBodySize)){
				var mode = maxLine;
				if(jQuery(this).attr('class') == 'item-body total-group'){
					if(i + subItemBodySize < maxLine){
						for(var c = 0;c <= (maxLine - (i + subItemBodySize));c++){
							jQuery(this).before('<div class="item-body"><div class="date">&nbsp;</div><div class="description">&nbsp;</div><div class="amount">&nbsp;</div></div>');
						}		
					}
					mode = maxLine - subItemBodySize;
				}
				if(i%(mode) == 0){
					jQuery(this).after('<div style="page-break-after:always;text-align:center;color:#666666;">-<?php echo Portal::language('page');?> '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}
			}
			i++;
		});
	}
	//printWebPart('printer');
	//window.close();
    jQuery("#export_excel").click(function () {
    jQuery('#title_page').css('display', 'block');
    jQuery('#imgs').remove();
    jQuery("#Export").clone().find("img").remove();
    jQuery("#Export").battatech_excelexport({
        containerid: "Export"
       , datatype: 'table'
       , fileName: '<?php echo Portal::language('bill_folio');?>'
    });       
    
    
});
</script>
