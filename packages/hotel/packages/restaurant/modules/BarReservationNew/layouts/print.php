<style>
	<!--IF:cond([[=preview=]])-->
	@media print{body {display:none;}}
	<!--/IF:cond-->
	.restaurant-invoice-bound{
		float:left;
		width:450px;
		padding:5px;
	}
</style>
<div style="height:80px;float:left;width:400px;">&nbsp;</div><br clear="left">
<div class="restaurant-invoice-bound">
	<div class="item-body">
        <div class="item-body">
        	<span style="font-size:11px;font-weight:bold;">No: [[|order_id|]] | [[.print_date.]]: <?php echo date('d/m/Y H:i\'');?></span>
            <span style="font-size:11px;font-weight:bold;width:200px;float:right;text-align:right;">Thu ng&acirc;n/Cashier: <?php echo Session::get('user_id');?></span>
        </div>
        <div>
        	<span style="font-size:11px;font-weight:bold;">Li&ecirc;n h&#7879;/Contact: <?php echo HOTEL_PHONE;?></span>
        	<span style="font-size:11px;font-weight:bold;width:200px;float:right;text-align:right;">T&yacute; gi&aacute;/Exchange rate: [[|exchange|]]</span>
        </div>
    </div>
    <div class="item-body"><div class="seperator-line">&nbsp;</div></div>
    <div class="item-body">
        <div valign="top" align="left">B&agrave;n s&#7889;/Table No: [[|tables_name|]]</div>
	</div>
	<div class="item-body">
        <div valign="top" align="left">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="60%">Kh&#225;ch h&#224;ng/Agent name: [[|customer_name|]] <!--IF:check_room(isset([[=room_name=]]) and [[=room_name=]])-->([[|room_name|]])<!--/IF:check_room--></td>
			<td width="40%" align="right">Ng&#432;&#7901;i nh&#7853;n: [[|receiver_name|]] </td>
		</tr>
		</table>
		</div>
	</div>
    <div><div class="item-body"></div><div class="item-body"></div>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000">
          <tr>
            <th width="50%" align="left">Di&#7877;n gi&#7843;i<br />
            Description </th>
            <th width="10%" align="center">SL<br />
            Q</th>
            <th width="15%" align="center">&#272;&#417;n gi&#225;<br />Price</th>
            <th width="10%" align="center" nowrap="nowrap">Gi&#7843;m(%)<br />
            Disc(%)</th>
            <th width="20%" align="center" nowrap="nowrap">Th&agrave;nh ti&#7873;n<br />
            Amount </th>
          </tr>
          <?php $i=1;?>
          <!--LIST:product_items-->
          <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0)-->
          <tr valign="top">
            <td align="left"><?php echo $i;?>. [[|product_items.product__name|]]<div class="item-body"></div></td>
            <td align="center">[[|product_items.product__remain_quantity|]]</td>
            <td align="right">[[|product_items.product__price|]]</td>
            <td align="center">[[|product_items.product__discount|]]</td>
            <td align="right">[[|product_items.product__total|]]</td>
          </tr>
          <?php $i++;?>
           <!--/IF:check_remain_quantity-->
          <!--IF:check_discount_quantity(MAP['product_items']['current']['product__quantity_discount']!=0)-->
          <tr valign="top">
            <td align="left"><?php echo $i;?>. [[|product_items.product__name|]] <strong>([[.promotion.]])</strong><div class="item-body"></div></td>
            <td align="center">[[|product_items.product__quantity_discount|]]</td>
            <td align="right">[[|product_items.product__price|]]</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
          </tr>
          <?php $i++;?>                      
          <!--/IF:check_discount_quantity-->
          <!--/LIST:product_items-->
      </table>
      <div class="item-body total-group">
      <div class="item-body">&nbsp;</div>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td align="right">
              <table width="300" cellpadding="2" border="0">
              <tr>
                <td width="80%"><strong>[[.amount.]]/Amount:</strong><div class="sub-item-body"></div></td>
                <td width="20%" align="right">[[|amount|]]</td>
              </tr>
              <!--IF:check_discount(MAP['total_discount']!='0.00')-->
              <tr>
                <td><strong>[[.discounted.]]/Discounted:  </strong><div class="sub-item-body"></div></td>
                <td align="right">[[|total_discount|]]</td>
              </tr>
              <!--/IF:check_discount-->
              <!--IF:check_servicechrg(MAP['bar_fee']!='0.00')-->
              <tr>
                <td><strong> (5%) [[.service_chrg.]]/Service charge: </strong><div class="sub-item-body"></div></td>
                <td align="right">[[|bar_fee|]]</td>
              </tr>
              <!--/IF:check_servicechrg-->
              <!--IF:check_tax(MAP['tax']!='0.00')-->
              <tr>
                <td><strong>([[|tax_rate|]]%) [[.tax_rate.]]/Tax:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|tax|]]</td>
              </tr>
              <!--/IF:check_tax-->
              <tr>
                <td nowrap="nowrap" align="left" style="border:2px solid #000;border-right:0px;"><strong>[[.sum_total.]]/Grant Total:</strong><div class="sub-item-body"></div></td>
                <td align="right" style="border:2px solid #000;border-left:0px;"><span style="font-size:14px;font-weight:bold;">[[|sum_total|]]</span></td>
              </tr>
               <tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;">$ [[|sum_total_usd|]]</span></td>
              </tr>
                <!--IF:check_prepaid([[=prepaid=]]!='0.00')-->
              <tr>
                <td><strong>[[.prepaid.]]/Deposit:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|prepaid|]]</td>
              </tr>
              <tr>
                <td nowrap="nowrap" align="left" style="border:2px solid #000;border-right:0px;"><strong>[[.remain_paid.]]/ Remain:</strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:14px;font-weight:bold;">[[|remain_prepaid|]]</span></td>
              </tr>
               <tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;">$ [[|remain_prepaid_usd|]]</span></td>
              </tr>
              <!--/IF:check_prepaid-->
            </table>
            <div style="color:#F00;font-weight:bold;padding-top:10px;font-size:16px;">[[|preview|]]</div>
          </td>
        </tr>
    </table>
    </div>
  </div>
</div>
<script>
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
					jQuery(this).after('<div style="page-break-after:always;text-align:center;color:#666666;">-[[.page.]] '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}
			}
			i++;
		});
	}
	//printWebPart('printer');
	//window.close();
</script>