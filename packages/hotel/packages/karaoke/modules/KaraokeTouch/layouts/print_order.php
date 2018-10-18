<style>
	 @media print {
  }
	.karaoke-invoice-bound{
		width:450px;
		padding:5px;
	}
	
</style>
<div id="transform" style="width:450px; padding:5px;text-align:left">
<div class="karaoke-invoice-bound">
	<div class="item-body">
	<table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:10px;">
	<tr>
	
	<td width="1%"><img src="<?php echo HOTEL_LOGO;?>" width="100px;"></td>
	<td style="font-size:18px;font-weight:bold;" align="center">BILL/PHIẾU THANH TOÁN<br/><br/>[[|karaoke_name|]]</td>
</tr>
</table>
        <div class="item-body" align="left">
        	<span style="font-size:11px;font-weight:bold;"><span style="font-size:16px;">No: [[|order_id|]]</span></span>
            <span style="font-size:11px;font-weight:bold;width:200px;float:right;text-align:right;">Thu ng&acirc;n/Cashier: <?php echo Session::get('user_id');?><br>[[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span>
        </div>
        <div align="left" style="border-bottom:1px solid #000000;">
        	<span style="font-size:11px;font-weight:bold;">Li&ecirc;n h&#7879;/Contact: <?php echo HOTEL_PHONE;?></span>
        </div>
    </div>
    <div class="item-body">
        <div valign="top" align="left">Phòng/Room No: [[|tables_name|]]</div>
        <div>Số lượng khách/Num People: [[|num_people|]]</div>
	</div>
	<div class="item-body">
        <div valign="top" align="left" style="border-bottom:1px solid #000000;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="60%">Kh&#225;ch h&#224;ng/Customer name: [[|receiver_name|]] [[|customer_name|]] <!--IF:check_room(isset([[=room_name=]]) and [[=room_name=]])-->([[|room_name|]])<!--/IF:check_room--></td>
			<td width="40%" align="right"><!---Ng&#432;&#7901;i nh&#7853;n: [[|receiver_name|]] --->[[.date_created.]] [[|time|]]</td>
		</tr>
		</table>
		</div>
	</div>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000">
          <tr>
            <th width="50%" align="left"  style="border-bottom:1px solid #000000;">Di&#7877;n gi&#7843;i<br />
            Description </th>
             <th width="10%" align="center"  style="border-bottom:1px solid #000000;">ĐVT<br />
            Unit</th>
             <!--<th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL YC<br />
            Quantity</th>-->
            <!--<th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL Trả lại<br />
            remain</th>-->
            <th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL<br />
            used</th>
            <th width="15%" align="right"  style="border-bottom:1px solid #000000;">&#272;&#417;n gi&#225;<br />Price</th>
            <th width="10%" align="right" nowrap="nowrap"  style="border-bottom:1px solid #000000;">Gi&#7843;m(%)<br />
            Disc(%)</th>
            <th width="20%" align="right" nowrap="nowrap" style="border-bottom:1px solid #000000;">Th&agrave;nh ti&#7873;n<br />
            Amount </th>
          </tr>
          <?php $i=1;?>
          <!--LIST:product_items-->
          <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0 && MAP['product_items']['current']['cancel_all']==0)-->
          <tr valign="top">
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i;?>. [[|product_items.product__name|]]<div class="item-body"></div></td>
            <td align="center" nowrap="nowrap"  style="border-bottom:1px solid #000000;">[[|product_items.product__unit|]]</td>
            <!--<td align="center"><?php echo ([[=product_items.product__remain_quantity=]] + [[=product_items.product__remain=]]);?></td>-->
            <!--<td align="center">[[|product_items.product__remain|]]</td>-->
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__remain_quantity|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">[[|product_items.product__price|]]</td>
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__discount|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">[[|product_items.product__total|]]</td>
          </tr>
          <?php $i++;?>
          <!--/IF:check_remain_quantity-->
          <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
          <tr valign="top">
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i;?>. [[|product_items.product__name|]] <strong>([[.promotion.]])</strong><div class="item-body"></div></td>
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__unit|]]</td>
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__quantity_discount|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">[[|product_items.product__price|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
          </tr>
          <?php $i++;?> 
          <!--/IF:check_discount_quantity-->
          <!--IF:check_cancel_quantity(MAP['product_items']['current']['cancel_all']==1)-->
           <tr valign="top">
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i;?>. [[|product_items.product__name|]] <strong>([[.cancel_all.]])</strong><div class="item-body"></div></td>
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__unit|]]</td>
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__quantity|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">[[|product_items.product__price|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
          </tr>                 
          <?php $i++;?> 
          <!--/IF:check_cancel_quantity-->
          <!--/LIST:product_items-->
          <?php $total_sing = 0; ?>
          <?php if(sizeof([[=sing_room=]])>0){ ?>
            
            <!--LIST:sing_room-->
                <tr valign="top">
                <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i;?>. [[|sing_room.name|]] <strong>([[.sing_price.]])</strong><div class="item-body"></div></td>
                <td align="center" style="border-bottom:1px solid #000000;">[[.hours.]]</td>
                <td align="center" style="border-bottom:1px solid #000000;">[[|sing_room.time|]]</td>
                <td align="right" style="border-bottom:1px solid #000000;">[[|sing_room.price|]]</td>
                <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
                <td align="right" style="border-bottom:1px solid #000000;">[[|sing_room.total|]]</td>
                <?php $total_sing += System::calculate_number([[=sing_room.total=]]); ?>
            </tr>    
            <?php $i++; ?> 
            <!--/LIST:sing_room-->
            <?php } ?>
      </table>
      <div class="item-body total-group">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td align="right">
              <table width="350" cellpadding="0" border="0">
              <tr>
                <td width="80%"><strong>[[.amount.]]/Amount:</strong><div class="sub-item-body"></div></td>
                <td width="20%" align="right">[[|amount|]]</td>
              </tr>
              <!--IF:check_order_discount(MAP['order_discount']!='0.00')-->
              <tr>
                <td><strong>[[.product_discounted.]]/Product Discounted:  </strong><div class="sub-item-body"></div></td>
                <td align="right">[[|order_discount|]]</td>
              </tr>
              <!--/IF:check_order_discount-->
               <!--IF:check_discount_percent(MAP['discount_percent']!='0.00' or MAP['total_discount']!='0.00')-->
              <tr>
                <td><strong>([[|discount_percent|]]%) [[.order_discount.]]/Order Discount:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|total_discount|]]</td>
              </tr>
              <!--/IF:check_discount_percent-->
              <!--IF:check_servicechrg(MAP['service_amount']!='0.00')-->
              <tr>
                <td><strong> ([[|karaoke_fee_rate|]]%)[[.service_chrg.]]/Service charge: </strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo System::display_number([[=service_amount=]]);?></td>
              </tr>
              <!--/IF:check_servicechrg-->
              <!--IF:check_tax(MAP['tax_amount']!='0.00')-->
              <tr>
                <td><strong>([[|tax_rate|]]%) [[.tax_rate.]]/Tax:</strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo System::display_number([[=tax_amount=]]);?></td>
              </tr>
              <!--/IF:check_tax-->

               <!--IF:check_deposit(MAP['deposit']!='0.00')-->
              <tr>
                <td><strong>Đặt cọc/ Deposit:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|deposit|]]</td>
              </tr>
              <!--/IF:check_deposit-->
              <tr>
                <td nowrap="nowrap" align="left" style="border:1px solid #000;border-right:0px;"><strong>[[.sum_total.]]/Grant Total:</strong><div class="sub-item-body"></div></td>
                <td align="right" style="border:1px solid #000;border-left:0px;"><span style="font-size:18px;font-weight:bold;"><?php echo System::display_number(System::calculate_number([[=sum_total=]])+$total_sing); ?></span></td>
              </tr>
              <!-- <tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;">[[|sum_total_usd|]] &nbsp;USD</span></td>
              </tr>-->
                <!--IF:check_prepaid([[=prepaid=]] AND [[=prepaid=]]!='0.00')-->
              <tr>
                <td><strong>[[.prepaid.]]/Deposit:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|prepaid|]]</td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="left" style="border:2px solid #000;border-right:0px;"><strong>[[.remain_paid.]]/ Remain:</strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:14px;font-weight:bold;">[[|remain_prepaid|]]</span></td>
              </tr>
               <!--<tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;">USD [[|remain_prepaid_usd|]]</span></td>
              </tr>-->
              <!--/IF:check_prepaid-->
            </table>
            <?php if(sizeof([[=payment_list=]])>0){ ?>
        <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" style="font-weight: bold; margin-top: 3px;">
            <tr>
                <th colspan="2" style="font-size: 15px;">[[.payment.]]</th>
            </tr>
           
            <?php foreach($this->map['payment_list'] as $k => $v)
            {
            ?>
            <tr>
                <td style="font-size: 15px;"><?php echo $v['payment_type_name'] ?></td>
                <td style="font-size: 15px;"><?php echo (System::display_number(round($v['amount']))); ?><?php if($v['currency_id']) echo $v['currency_id'] ?></td>
            </tr>
            <?php
            }
            ?>  
            
        </table>
        <?php } ?>
            <div style="color:#F00;font-weight:bold;padding-top:10px;font-size:16px;"></div>
			<table cellpadding="5" cellspacing="0" width="100%" style="height:50px;border-collapse:collapse;" border="1" bordercolor="silver">
            	<tr>
                	<td width="33%" align="center">[[.Cashier.]] <br> <span style="font-style:italic">Cashier</span></td>
                	<td width="33%" align="center">[[.room_no.]] <br> <span style="font-style:italic">Room No</span></td>
                	<td align="center">[[.guest_signature.]] <br> <span style="font-style:italic">Customer's signature</span></td>
                </tr>
                <tr>
                	<td><br><br><br><br></td>
                	<td valign="middle" align="center"><!--IF:check_room(isset([[=room_name=]]) and [[=room_name=]])-->([[|room_name|]])<!--/IF:check_room--></td>
                	<td></td>
                </tr>
            </table>
          </td>
        </tr>
    </table>
    </div>
  </div>
</div>
</div>
<script>
	var printt = '<?php echo (Url::get(md5('preview'))?Url::get(md5('preview')):'');?>';
	if(printt ==''){
		printWebPart('printer');	
	}
	<!--IF:cond([[=preview=]])-->
	function handleKeyPress(evt) {  
		var nbr;  
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==17 || nbr==80){
			//if(!confirm('[[.Are_you_sure_to_add_reservation.]]?')){
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
	<!--/IF:cond-->
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 18;
	var i = 1;
	var j = 0;
	var page = 1;
	/*if((itemBodySize + subItemBodySize) < maxLine){
		jQuery(".item-body").each(function(){
			if(j == itemBodySize -2 ){
				for(var c = 0;c <= (maxLine - itemBodySize);c++){
					jQuery(this).after('<div class="item-body"></div>');
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
							jQuery(this).before('<div class="item-body"></div>');
						}		
					}
					mode = maxLine - subItemBodySize;
				}
				/*if(i%(mode) == 0){
					jQuery(this).after('<div style="page-break-after:always;text-align:center;color:#666666;">-[[.page.]] '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}*/
			}
			i++;
		});
	}
	//printWebPart('printer');
	//window.close();
</script>