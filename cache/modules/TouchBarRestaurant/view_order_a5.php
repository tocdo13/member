<style>
	 @media print {
	   #printer_logo{
	       display: none;
	   }
  }	
</style>
<div id="printer_logo">
<a onclick="var content = jQuery('#printer').html();printWebPart_Tan(content);printed_tmp_bill();" title="Print" style="float: right;"><img src="packages/core/skins/default/images/printer.png" height="40"></a>
<a href="<?php echo '?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.Url::get('id').'&bar_id='.Url::get('bar_id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&print_automatic_bill=1&portal='.Url::get('portal'); ?>" class="button-medium" style="float: right;"><?php echo Portal::language('print_automatic_bill');?></a>
<input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 100px; height: 23px"/>
</div>
<!---------------------------------- LAYOUT KHO GIAY A5 ---------------------------------------------->
<table id="Export" width="width:420px" style="position: relative; float:left;"><tr><td>
<div id="printer" style="width:420px; padding:5px;text-align:left; display: ;">
<div class="restaurant-invoice-bound" style="width: 100%;">
	<div class="item-body">
	<table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:10px;">
	<tr>
	<td width="1%" rowspan="4" id="srcs" ><img src="<?php echo HOTEL_LOGO;?>" width="100px;" /></td>
    <td></td>
	<td style="font-size:18px;font-weight:bold;" align="center">
        <?php if($this->map['check_name_invoice']==1){ ?>
        <?php echo Portal::language('INVOICE / HÓA ĐƠN');?>
        <?php }else{ ?>
        <?php echo Portal::language('INVOICE / HÓA ĐƠN');?>
        <?php } ?>
    </td>
    </tr>
    <tr>
    <td></td>
    <td align="center"><?php echo Portal::language('date');?> :<?php echo date('d/m/Y H:i\'');?></td>
    </tr>
    <tr>
    <td></td>
    <td align="center"><?php echo Portal::language('print_time');?>: <?php echo $this->map['table_checkout'];?></td>
    </tr>
    <tr>
    <td></td>
    <td align="center"><?php echo Portal::language('print_user');?>: <?php echo User::id(); ?></td>
    </tr>
</table>
    <div>
       <!-- <div class="item-body" align="left">
        	<span style="font-size:11px;font-weight:bold;"><span style="font-size:16px;">No: <?php echo $this->map['order_id'];?></span> </span>
            <span style="font-size:11px;font-weight:bold;width:200px;float:right;text-align:right;">Thu ng&acirc;n/Cashier: <?php //echo Session::get('user_id');?></span>
        </div>-->
        
        <div align="left" style="border-bottom:1px solid #000000;">
        	<span style="font-size:14px;font-weight:bold;"><?php echo Portal::language('res');?>: <?php echo $this->map['bar_name'];?> </span>
            <div style="font-size:14px;font-weight:bold;"><?php echo Portal::language('room');?> : <?php echo $this->map['room_package_name'];?></div>
            <div style="font-size:14px;font-weight:bold;"><?php echo Portal::language('package');?> : <?php echo $this->map['package_name'];?></div>
        </div>
    </div>
    
    <!--<div class="item-body">
        <div valign="top" align="left">B&agrave;n s&#7889;/Table No: <?php echo $this->map['tables_name'];?></div>
	</div>-->
	<div class="item-body">
        <div valign="top" align="left" style="border-bottom:1px solid #000000;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<?php  
                if(!empty($this->map['customer_name'])){
            ?>
                 <td colspan="2" align="left"><?php echo Portal::language('guest_name');?>: <?php echo $this->map['customer_name']['fullname']  ?>/    <?php echo Portal::language('room');?>: <?php echo $this->map['room_name'];?></td>
                 <td colspan="2"></td>
                 <td align="right"><?php echo Portal::language('table_no');?>: <?php echo $this->map['tables_name'];?></td>       
                    
            <?php }else{ ?>
                 <td colspan="2" align="left"><?php echo Portal::language('guest_name');?>: <?php echo $this->map['receiver_name'];?></td>
                 <td colspan="2"></td>
                 <td align="right"><?php echo Portal::language('table_no');?>: <?php echo $this->map['tables_name'];?></td>
                    
                <?php } ?>    
        </tr>
        
        <tr>
            <td><?php echo Portal::language('company');?>: <?php if(!empty($this->map['customer_name'])) echo $this->map['customer_name']['cname']; else echo $this->map['agent_name'] ?> </td>
            <td align="center"><?php echo Portal::language('code');?>: <?php echo $this->map['code'];?></td>
            <td colspan="2"></td>
            <td align="right"><?php echo Portal::language('guest_no');?>: <?php echo $this->map['num_people'];?> </td>
        </tr>
		</table>
		</div>
	</div>
    <div><div class="item-body"></div><div class="item-body"></div>
        <table id="tbl_content" width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000">
          <tr>
            <th width="40%" align="left"  style="border-bottom:1px solid #000000;"><?php echo Portal::language('description');?> </th>
             <!--<th width="10%" align="center"  style="border-bottom:1px solid #000000;">ĐVT<br />
            Unit</th>
             <th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL YC<br />
            Quantity</th>-->
            <!--<th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL Trả lại<br />
            remain</th>-->
            <th width="10%" align="center"  style="border-bottom:1px solid #000000;"><?php echo Portal::language('quantity');?></th>
            <th width="15%" align="center"  style="border-bottom:1px solid #000000;"><?php echo Portal::language('price');?></th>
            <th width="15%" align="right" nowrap="nowrap"  style="border-bottom:1px solid #000000;"><?php echo Portal::language('discount');?></th>
            <th width="20%" align="right" nowrap="nowrap" style="border-bottom:1px solid #000000;"><?php echo Portal::language('amount');?></th>
          </tr>
          <?php $i=1;?>
          <?php if(isset($this->map['product_items']) and is_array($this->map['product_items'])){ foreach($this->map['product_items'] as $key1=>&$item1){if($key1!='current'){$this->map['product_items']['current'] = &$item1;?>
          <?php 
				if(($this->map['product_items']['current']['product__remain_quantity']!=0 && $this->map['product_items']['current']['cancel_all']==0))
				{?>
          <tr valign="top">
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i . '. ' . ucwords($this->map['product_items']['current']['product__name']);//if(isset($this->map['product_items']['current']['chair_number'])!='') echo '<br/>'.' Số ghế :'.$this->map['product_items']['current']['chair_number'] ?>   <div class="item-body"></div></td><!--trung add cot chair_number-->           
           <!-- <td align="center" nowrap="nowrap"  style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__unit'];?></td>-->
            <!--<td align="center"><?php echo ($this->map['product_items']['current']['product__remain_quantity'] + $this->map['product_items']['current']['product__remain']);?></td>-->
            <!--<td align="center"><?php echo $this->map['product_items']['current']['product__remain'];?></td>-->
            <td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__remain_quantity'];?></td>
            <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__price'];?></td>
            <td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__discount'];?></td>
            <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__total'];?></td>
            
          </tr>
          <?php $i++;?>
          
				<?php
				}
				?>
          <?php 
				if(($this->map['product_items']['current']['product__quantity_discount']!=0))
				{?>
          <tr valign="top">
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i . '. ' . ucwords($this->map['product_items']['current']['product__name']); ?><strong>(<?php echo Portal::language('promotion');?>)</strong><?php //if(isset($this->map['product_items']['current']['chair_number'])!='') echo '<br/>'.' Số ghế :'.$this->map['product_items']['current']['chair_number'] ?><div class="item-body"></div></td><!--trung add cot chair_number truong hop khuyen mai-->
            <!--<td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__unit'];?></td>-->
            <td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__quantity_discount'];?></td>
            <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__price'];?></td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
            <td align="right" style="border-bottom:1px solid #000000;">0</td>
          </tr>
          <?php $i++;?> 
          
				<?php
				}
				?>
          <?php 
				if(($this->map['product_items']['current']['cancel_all']==1))
				{?>
           <tr valign="top">
            <td align="left"><?php echo $i . '. ' . ucwords($this->map['product_items']['current']['product__name']);?><strong>(<?php echo Portal::language('cancel_all');?>)</strong><div class="item-body"></div></td>
            <!--<td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__unit'];?></td>-->
            <td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__quantity'];?></td>
            <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['product_items']['current']['product__price'];?></td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
          </tr>                 
          <?php $i++;?> 
          
				<?php
				}
				?>
          <?php }}unset($this->map['product_items']['current']);} ?>
          <?php if(isset($this->map['set_product_items']) and is_array($this->map['set_product_items'])){ foreach($this->map['set_product_items'] as $key2=>&$item2){if($key2!='current'){$this->map['set_product_items']['current'] = &$item2;?>
              <tr>
                <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i;?>. <?php echo $this->map['set_product_items']['current']['name'];?><div class="item-body"></div></td>
                <td align="center" style="border-bottom:1px solid #000000;"><?php echo $this->map['set_product_items']['current']['product__remain_quantity']; ?></td>
                <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['set_product_items']['current']['product__price']; ?></td>
                <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['set_product_items']['current']['product__discount']; ?></td>
                <td align="right" style="border-bottom:1px solid #000000;"><?php echo $this->map['set_product_items']['current']['product__total']; ?></td>
              </tr>
                  <?php
                    if(is_array($this->map['set_product_items']['current']['items'])){
                        foreach($this->map['set_product_items']['current']['items'] as $key=>$value){
                        if($value['product__remain_quantity']!=0 && $value['cancel_all']==0){
                      ?>  
                      <tr valign="top">
                        <td align="left" style="border-bottom:1px solid #000000;padding-left: 20px; font-style: italic;"> - <?php echo $value['product__name']; ?><div class="item-body"></div></td>
                        <td align="center" style="border-bottom:1px solid #000000;"><?php echo $value['product__remain_quantity']; ?></td>
                        <td align="right" style="border-bottom:1px solid #000000;"><?php echo $value['product__price']; ?></td>
                        <td align="center" style="border-bottom:1px solid #000000;"><?php echo $value['product__discount']; ?></td>
                        <td align="right" style="border-bottom:1px solid #000000;"><?php echo $value['product__total']; ?></td>
                      </tr>
                      <?php
                        }
                      ?>
                      <?php
                        if($value['product__quantity_discount']!=0){
                      ?>
                      <tr valign="top">
                        <td align="left" style="border-bottom:1px solid #000000;padding-left: 20px;font-style: italic;"> - <?php echo $value['product__name']; ?> <strong>(<?php echo Portal::language('promotion');?>)</strong><div class="item-body"></div></td>
                        <td align="center" style="border-bottom:1px solid #000000;"><?php echo $value['product__quantity_discount']; ?></td>
                        <td align="right" style="border-bottom:1px solid #000000;"><?php echo $value['product__price']; ?></td>
                        <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                        <td align="right" style="border-bottom:1px solid #000000;">0</td>
                      </tr>
                      <?php
                        }
                      ?>
                      <?php
                        if($value['cancel_all']==1){
                      ?>
                       <tr valign="top">
                        <td align="left" style="padding-left: 20px;font-style: italic;"> - <?php echo $value['product__name']; ?><strong>(<?php echo Portal::language('cancel_all');?>)</strong><div class="item-body"></div></td>
                        <td align="center" style="border-bottom:1px solid #000000;"><?php echo $value['product__quantity']; ?></td>
                        <td align="right" style="border-bottom:1px solid #000000;"><?php echo $value['product__price']; ?></td>
                        <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                        <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                      </tr>                 
                  <?php
                        }
                      }  
                      $i++;
                    }
                  ?>  
          <?php }}unset($this->map['set_product_items']['current']);} ?>
      </table>
      <br />
        <table id="tbl_content" width="100%" cellpadding="0" border="0">
              <tr>
                <td colspan="3"></td>
                <td width="80%"><strong><?php echo Portal::language('subtotal');?>:</strong><div class="sub-item-body"></div></td>
                <td width="20%" align="right"><?php echo $this->map['amount'];?></td>
              </tr>
              <?php 
				if(($this->map['total_discount']!='0.00'))
				{?>
              <tr>
                <td colspan="3"></td>
                <td><strong><?php echo Portal::language('product_discounted');?>:  </strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo $this->map['total_discount'];?></td>
              </tr>
              
				<?php
				}
				?>
              <?php 
				if(($this->map['discount_percent']!='0.00'))
				{?>
              <tr>
                <td colspan="3"></td>    
                <td><strong>(<?php echo $this->map['discount_percent'];?>%) <?php echo Portal::language('order_discount');?>: </strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo System::display_number(round($this->map['order_discount']));?></td>
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
                    <td colspan="3"></td>    
                    <td><strong>(<?php echo $this->map['discount_percent'];?>%) <?php echo Portal::language('order_discount');?>: </strong><div class="sub-item-body"></div></td>
                    <td align="right"><?php echo $this->map['discount_percent_dn'];?></td>
                  </tr>
                  
				<?php
				}
				?>  
              
				<?php
				}
				?>  
              <?php 
				if(($this->map['bar_fee']!='0.00'))
				{?>
              <tr>
                <td colspan="3"></td>    
                <td><strong> (<?php echo $this->map['bar_fee_rate'];?>%)<?php echo Portal::language('service_chrg');?>: </strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo $this->map['bar_fee'];?></td>
              </tr>
              
				<?php
				}
				?>
              <?php 
				if(($this->map['tax']!='0.00'))
				{?>
              <tr>
                <td colspan="3"></td>
                <td><strong>(<?php echo $this->map['tax_rate'];?>%) <?php echo Portal::language('tax_rate');?>: </strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo $this->map['tax'];?></td>
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
                    <td colspan="3"></td>    
                    <td><strong>(<?php echo $this->map['discount_percent'];?>%) <?php echo Portal::language('order_discount');?>: </strong><div class="sub-item-body"></div></td>
                    <td align="right"><?php echo $this->map['discount_percent_dn'];?></td>
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
                <td colspan="3"></td>
                <td><strong><?php echo Portal::language('deposit');?>:</strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo $this->map['deposit'];?></td>
              </tr>
              
				<?php
				}
				?>
              <?php if($this->map['total_payment_traveller']>=1){ ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style=""><strong><?php echo Portal::language('total_payment_traveller');?>:</strong><div class="sub-item-body"></div></td>
                <td align="right" style=""><span style="font-weight:bold;"><?php echo System::display_number($this->map['total_payment_traveller']); ?></span></td>
              </tr>
              <?php } ?>
              <?php if(isset($this->map['package_amount'])){ ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style=""><strong><?php echo Portal::language('package_amount');?>: </strong><div class="sub-item-body"></div></td>
                <td align="right" style=""><span style="font-weight:bold;"><?php echo System::display_number($this->map['package_amount']); ?></span></td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left"><strong><?php echo Portal::language('sum_total');?>:</strong><div class="sub-item-body"></div></td>
                <td align="right" style="font-size:18px;font-weight:bold;"><?php echo $this->map['sum_total'];?></td>
              </tr>
              <?php if($this->map['total_payment_traveller']>=1){ ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style=""><strong><?php echo Portal::language('total_remain_traveller');?>: </strong><div class="sub-item-body"></div></td>
                <td align="right" style=""><span style="font-weight:bold;"><?php echo System::display_number($this->map['total_payment_traveller']-System::calculate_number($this->map['sum_total'])); ?></span></td>
              </tr>
              <?php } ?>
              <!-- <tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;"><?php echo $this->map['sum_total_usd'];?> &nbsp;USD</span></td>
              </tr>-->
                <?php 
				if(($this->map['prepaid']!='0.00'))
				{?>
              <tr>
                <td colspan="3"></td>
                <td><strong><?php echo Portal::language('prepaid');?>:</strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo $this->map['prepaid'];?></td>
              </tr>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style="border:2px solid #000;border-right:0px;"><strong><?php echo Portal::language('remain_paid');?>:</strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:14px;font-weight:bold;"><?php echo $this->map['remain_prepaid'];?></span></td>
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
        <table id="tbl_content" width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" style="font-weight: bold; margin-top: 3px;">
            <tr>
                <th colspan="5" style="font-size: 15px;"><?php echo Portal::language('payment');?></th>
            </tr>
           
            <?php foreach($this->map['payment_list'] as $k => $v)
            {
            ?>
            <tr>
                
                <td colspan="3" style="font-size: 15px;"><?php echo $v['payment_type_name'] ?></td>
                <td colspan="2" style="font-size: 15px;text-align: right;"><span class="amt"><?php echo System::display_number($v['amount']); ?></span> <?php echo $v['currency_id'] ?></td>
            </tr>
            <?php
            }
            ?>  
            
        </table>
        <?php } ?>
            <table>
                <tr>
                    <td colspan="5" style="text-align: right;">
                        <div style="color:#F00;font-weight:bold;padding-top:10px;font-size:16px;text-align: right;"><?php echo $this->map['preview'];?></div>
                    </td>
                </tr>
            </table>
            <table cellpadding="5" cellspacing="0" width="100%" style="height:50px;border-collapse:collapse;" border="1" bordercolor="silver">
            	<tr>
                    <td  width="33%" align="center"><?php echo Portal::language('cashier');?></td>
                	<td colspan="2" width="33%" align="center"><?php echo Portal::language('room_number');?></td>
                	<td colspan="2" align="center"><?php echo Portal::language('guest_name');?></td>
                </tr>
                <tr>
                	
                    <td ><br><br><br><br></td>
                	<td colspan="2" valign="middle" align="center"><?php 
				if((isset($this->map['room_name']) and $this->map['room_name']))
				{?>(<?php echo $this->map['room_name'];?>)
				<?php
				}
				?></td>
                	<td colspan="2"></td>
                </tr>
            </table>
                    
  </div>
</div>
</div>
</td></tr></table>
<script>
	//var printt = '<?php //echo (Url::get(md5('preview'))?Url::get(md5('preview')):'');?>';
//	if(printt ==''){
//		printWebPart('printer');	
//	}
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
function printWebPart_Tan(content){
    	if(content)
    	{
    		var html = "";
    		html = "<html><head>"+
    		"<link rel=\"stylesheet\" type=\"text/css\" href=\"packages/core/skins/default/css/global.css\" media=\"print\" ></link>"+
    		"</head><body >"+
    		content+
    		"</body></html>";
    		width = jQuery(document).width();
    		height = jQuery(document).height();
    		html = html.replace('packages/core/includes/js/common.js','');
    		var printWP = window.open("about:blank","printWebPart","location=0,width="+width+",height="+height+",top=0,left=0");
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
//	function handleKeyPress(evt) {  
//		var nbr;  
//		var nbr = (window.event)?event.keyCode:evt.which;
//		if(nbr==17 || nbr==80){
//			//if(!confirm('<?php echo Portal::language('Are_you_sure_to_add_reservation');?>?')){
//			return false;
//			//}
//		}
//		return true;
//	}
//	document.onkeydown= handleKeyPress;
//	jQuery('body').disableTextSelect();
//	jQuery(document).ready(function(){ 
//		   jQuery(document).bind("contextmenu",function(e){
//				  return false;
//		   }); 
//	});
	
				<?php
				}
				?>
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 50;
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
        jQuery('table#tbl_content td').each(function(){
            var content = jQuery(this).html();
            if(content.trim()!="")
            {
                jQuery(this).html(to_numeric(jQuery(this).html())); 
            }           
        });
        jQuery('table#tbl_content span.amt').each(function(){
            var content = jQuery(this).html();
            if(content.trim()!="")
            {
                jQuery(this).html(to_numeric(jQuery(this).html())); 
            }           
        });
        jQuery('#imgs').remove();
        jQuery("#Export").clone().find("img").remove();
        jQuery("#Export").battatech_excelexport({
            containerid: "Export"
           , datatype: 'table'
           , fileName: '<?php echo Portal::language('bill_folio');?>'
        }); 
    });
</script>
