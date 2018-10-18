<style>
	 @media print {
	   #printer_logo{
	       display: none;
	   }
  }	
</style>
<div id="printer_logo">
<a onclick="var content = jQuery('#printer').html();printWebPart_Tan(content);printed_tmp_bill();" title="Print" style="float: right;"><img src="packages/core/skins/default/images/printer.png" height="40"></a>
<a href="<?php echo '?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.Url::get('id').'&bar_id='.Url::get('bar_id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&print_automatic_bill=1&portal='.Url::get('portal'); ?>" class="button-medium" style="float: right;">[[.print_automatic_bill.]]</a>
<input name="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 100px; height: 23px"/>
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
        <?php if([[=check_name_invoice=]]==1){ ?>
        [[.bill_folio.]]
        <?php }else{ ?>
        [[.bill_folio.]]
        <?php } ?>
    </td>
    </tr>
    <tr>
    <td></td>
    <td align="center">[[.date.]] :<?php echo date('d/m/Y H:i\'');?></td>
    </tr>
    <tr>
    <td></td>
    <td align="center">[[.print_time.]]: [[|table_checkout|]]</td>
    </tr>
    <tr>
    <td></td>
    <td align="center">[[.print_user.]]: <?php echo User::id(); ?></td>
    </tr>
</table>
       <!-- <div class="item-body" align="left">
        	<span style="font-size:11px;font-weight:bold;"><span style="font-size:16px;">No: [[|order_id|]]</span> </span>
            <span style="font-size:11px;font-weight:bold;width:200px;float:right;text-align:right;">Thu ng&acirc;n/Cashier: <?php //echo Session::get('user_id');?></span>
        </div>-->
        
        <div align="left" style="border-bottom:1px solid #000000;">
        	<span style="font-size:14px;font-weight:bold;">[[.res.]]: [[|bar_name|]] </span>
        </div>
    </div>
    
    <!--<div class="item-body">
        <div valign="top" align="left">B&agrave;n s&#7889;/Table No: [[|tables_name|]]</div>
	</div>-->
	<div class="item-body">
        <div valign="top" align="left" style="border-bottom:1px solid #000000;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<?php  
                if(!empty([[=customer_name=]])){
            ?>
                 <td colspan="2" align="left">[[.guest_name.]]: <?php echo [[=customer_name=]]['fullname']  ?>/    [[.room.]]: [[|room_name|]]</td>
                 <td colspan="2"></td>
                 <td align="right">[[.table_no.]]: [[|tables_name|]]</td>       
                    
            <?php }else{ ?>
                 <td colspan="2" align="left">[[.guest_name.]]: [[|receiver_name|]]</td>
                 <td colspan="2"></td>
                 <td align="right">[[.table_no.]]: [[|tables_name|]]</td>
                    
                <?php } ?>    
        </tr>
        
        <tr>
            <td>[[.company.]]: <?php if(!empty([[=customer_name=]])) echo [[=customer_name=]]['cname']; else echo [[=agent_name=]] ?> </td>
            <td align="center">[[.code.]]: [[|code|]]</td>
            <td colspan="2"></td>
            <td align="right">[[.guest_no.]]: [[|num_people|]] </td>
        </tr>
		</table>
		</div>
	</div>
    <div><div class="item-body"></div><div class="item-body"></div>
        <table id="tbl_content" width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000">
          <tr>
            <th width="40%" align="left"  style="border-bottom:1px solid #000000;">[[.description.]] </th>
             <!--<th width="10%" align="center"  style="border-bottom:1px solid #000000;">ĐVT<br />
            Unit</th>
             <th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL YC<br />
            Quantity</th>-->
            <!--<th width="10%" align="center"  style="border-bottom:1px solid #000000;">SL Trả lại<br />
            remain</th>-->
            <th width="10%" align="center"  style="border-bottom:1px solid #000000;">[[.quantity.]]</th>
            <th width="15%" align="center"  style="border-bottom:1px solid #000000;">[[.price.]]</th>
            <th width="15%" align="right" nowrap="nowrap"  style="border-bottom:1px solid #000000;">[[.discount.]]</th>
            <th width="20%" align="right" nowrap="nowrap" style="border-bottom:1px solid #000000;">[[.amount.]]</th>
          </tr>
          <?php $i=1;?>
          <!--LIST:product_items-->
          <!--IF:check_remain_quantity(MAP['product_items']['current']['product__remain_quantity']!=0 && MAP['product_items']['current']['cancel_all']==0)-->
          <tr valign="top">
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i . '. ' . ucwords([[=product_items.product__name=]]);//if(isset([[=product_items.chair_number=]])!='') echo '<br/>'.' Số ghế :'.[[=product_items.chair_number=]] ?>   <div class="item-body"></div></td><!--trung add cot chair_number-->           
           <!-- <td align="center" nowrap="nowrap"  style="border-bottom:1px solid #000000;">[[|product_items.product__unit|]]</td>-->
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
            <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i . '. ' . ucwords([[=product_items.product__name=]]); ?><strong>([[.promotion.]])</strong><?php //if(isset([[=product_items.chair_number=]])!='') echo '<br/>'.' Số ghế :'.[[=product_items.chair_number=]] ?><div class="item-body"></div></td><!--trung add cot chair_number truong hop khuyen mai-->
            <!--<td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__unit|]]</td>-->
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__quantity_discount|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">[[|product_items.product__price|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
            <td align="right" style="border-bottom:1px solid #000000;">0</td>
          </tr>
          <?php $i++;?> 
          <!--/IF:check_discount_quantity-->
          <!--IF:check_cancel_quantity(MAP['product_items']['current']['cancel_all']==1)-->
           <tr valign="top">
            <td align="left"><?php echo $i . '. ' . ucwords([[=product_items.product__name=]]);?><strong>([[.cancel_all.]])</strong><div class="item-body"></div></td>
            <!--<td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__unit|]]</td>-->
            <td align="center" style="border-bottom:1px solid #000000;">[[|product_items.product__quantity|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">[[|product_items.product__price|]]</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
            <td align="right" style="border-bottom:1px solid #000000;">&nbsp;</td>
          </tr>                 
          <?php $i++;?> 
          <!--/IF:check_cancel_quantity-->
          <!--/LIST:product_items-->
          <!--LIST:set_product_items-->
              <tr>
                <td align="left" style="border-bottom:1px solid #000000;"><?php echo $i;?>. [[|set_product_items.name|]]<div class="item-body"></div></td>
                <td align="center" style="border-bottom:1px solid #000000;"><?php echo [[=set_product_items.product__remain_quantity=]]; ?></td>
                <td align="right" style="border-bottom:1px solid #000000;"><?php echo [[=set_product_items.product__price=]]; ?></td>
                <td align="right" style="border-bottom:1px solid #000000;"><?php echo [[=set_product_items.product__discount=]]; ?></td>
                <td align="right" style="border-bottom:1px solid #000000;"><?php echo [[=set_product_items.product__total=]]; ?></td>
              </tr>
                  <?php
                    if(is_array([[=set_product_items.items=]])){
                        foreach([[=set_product_items.items=]] as $key=>$value){
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
                        <td align="left" style="border-bottom:1px solid #000000;padding-left: 20px;font-style: italic;"> - <?php echo $value['product__name']; ?> <strong>([[.promotion.]])</strong><div class="item-body"></div></td>
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
                        <td align="left" style="padding-left: 20px;font-style: italic;"> - <?php echo $value['product__name']; ?><strong>([[.cancel_all.]])</strong><div class="item-body"></div></td>
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
          <!--/LIST:set_product_items-->
      </table>
      <br />
        <table id="tbl_content" width="100%" cellpadding="0" border="0">
              <tr>
                <td colspan="3"></td>
                <td width="80%"><strong>[[.subtotal.]]:</strong><div class="sub-item-body"></div></td>
                <td width="20%" align="right">[[|amount|]]</td>
              </tr>
              <!--IF:check_discount(MAP['total_discount']!='0.00')-->
              <tr>
                <td colspan="3"></td>
                <td><strong>[[.product_discounted.]]:  </strong><div class="sub-item-body"></div></td>
                <td align="right">[[|total_discount|]]</td>
              </tr>
              <!--/IF:check_discount-->
              <!--IF:check_discount_percent(MAP['discount_percent']!='0.00')-->
              <tr>
                <td colspan="3"></td>    
                <td><strong>([[|discount_percent|]]%) [[.order_discount.]]: </strong><div class="sub-item-body"></div></td>
                <td align="right"><?php echo System::display_number(round([[=order_discount=]]));?></td>
              </tr>
              <!--/IF:check_discount_percent-->
              <!--IF:check_discount_after_tax(MAP['discount_after_tax']==0)-->
                   <!--IF:check_discount_percent(MAP['discount_percent_dn']!='0.00')-->
                  <tr>
                    <td colspan="3"></td>    
                    <td><strong>([[|discount_percent|]]%) [[.order_discount.]]: </strong><div class="sub-item-body"></div></td>
                    <td align="right"><?php echo [[=discount_percent_dn=]];?></td>
                  </tr>
                  <!--/IF:check_discount_percent-->  
              <!--/IF:check_discount_after_tax-->  
              <!--IF:check_servicechrg(MAP['bar_fee']!='0.00')-->
              <tr>
                <td colspan="3"></td>    
                <td><strong> ([[|bar_fee_rate|]]%)[[.service_chrg.]]: </strong><div class="sub-item-body"></div></td>
                <td align="right">[[|bar_fee|]]</td>
              </tr>
              <!--/IF:check_servicechrg-->
              <!--IF:check_tax(MAP['tax']!='0.00')-->
              <tr>
                <td colspan="3"></td>
                <td><strong>([[|tax_rate|]]%) [[.tax_rate.]]: </strong><div class="sub-item-body"></div></td>
                <td align="right">[[|tax|]]</td>
              </tr>
              <!--/IF:check_tax-->
              <!--IF:check_discount_after_tax(MAP['discount_after_tax']!=0)-->
                   <!--IF:check_discount_percent(MAP['discount_percent_dn']!='0.00')-->
                  <tr>
                    <td colspan="3"></td>    
                    <td><strong>([[|discount_percent|]]%) [[.order_discount.]]: </strong><div class="sub-item-body"></div></td>
                    <td align="right"><?php echo [[=discount_percent_dn=]];?></td>
                  </tr>
                  <!--/IF:check_discount_percent-->  
              <!--/IF:check_discount_after_tax-->            
               <!--IF:check_deposit(MAP['deposit']!='0.00')-->
              <tr>
                <td colspan="3"></td>
                <td><strong>[[.deposit.]]:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|deposit|]]</td>
              </tr>
              <!--/IF:check_deposit-->
              <?php if([[=total_payment_traveller=]]>=1){ ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style=""><strong>[[.total_payment_traveller.]]:</strong><div class="sub-item-body"></div></td>
                <td align="right" style=""><span style="font-weight:bold;"><?php echo System::display_number([[=total_payment_traveller=]]); ?></span></td>
              </tr>
              <?php } ?>
              <?php if(isset([[=package_amount=]])){ ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style=""><strong>[[.package_amount.]]: </strong><div class="sub-item-body"></div></td>
                <td align="right" style=""><span style="font-weight:bold;"><?php echo System::display_number([[=package_amount=]]); ?></span></td>
              </tr>
              <?php } ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left"><strong>[[.sum_total.]]:</strong><div class="sub-item-body"></div></td>
                <td align="right" style="font-size:18px;font-weight:bold;">[[|sum_total|]]</td>
              </tr>
              <?php if([[=total_payment_traveller=]]>=1){ ?>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style=""><strong>[[.total_remain_traveller.]]: </strong><div class="sub-item-body"></div></td>
                <td align="right" style=""><span style="font-weight:bold;"><?php echo System::display_number([[=total_payment_traveller=]]-System::calculate_number([[=sum_total=]])); ?></span></td>
              </tr>
              <?php } ?>
              <!-- <tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;">[[|sum_total_usd|]] &nbsp;USD</span></td>
              </tr>-->
                <!--IF:check_prepaid([[=prepaid=]]!='0.00')-->
              <tr>
                <td colspan="3"></td>
                <td><strong>[[.prepaid.]]:</strong><div class="sub-item-body"></div></td>
                <td align="right">[[|prepaid|]]</td>
              </tr>
              <tr>
                <td colspan="3"></td>
                <td nowrap="nowrap" align="left" style="border:2px solid #000;border-right:0px;"><strong>[[.remain_paid.]]:</strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:14px;font-weight:bold;">[[|remain_prepaid|]]</span></td>
              </tr>
               <!--<tr>
                <td nowrap="nowrap" align="left"><strong></strong><div class="sub-item-body"></div></td>
                <td align="right"><span style="font-size:12px;font-weight:bold;">USD [[|remain_prepaid_usd|]]</span></td>
              </tr>-->
              <!--/IF:check_prepaid-->
        </table>
      
<?php if(sizeof([[=payment_list=]])>0){ ?>
        <table id="tbl_content" width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000" style="font-weight: bold; margin-top: 3px;">
            <tr>
                <th colspan="5" style="font-size: 15px;">[[.payment.]]</th>
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
                        <div style="color:#F00;font-weight:bold;padding-top:10px;font-size:16px;text-align: right;">[[|preview|]]</div>
                    </td>
                </tr>
            </table>
            <table cellpadding="5" cellspacing="0" width="100%" style="height:50px;border-collapse:collapse;" border="1" bordercolor="silver">
            	<tr>
                    <td  width="33%" align="center">[[.cashier.]]</td>
                	<td colspan="2" width="33%" align="center">[[.room_number.]]</td>
                	<td colspan="2" align="center">[[.guest_name.]]</td>
                </tr>
                <tr>
                	
                    <td ><br><br><br><br></td>
                	<td colspan="2" valign="middle" align="center"><!--IF:check_room(isset([[=room_name=]]) and [[=room_name=]])-->([[|room_name|]])<!--/IF:check_room--></td>
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
	<!--IF:cond([[=preview=]])-->
//	function handleKeyPress(evt) {  
//		var nbr;  
//		var nbr = (window.event)?event.keyCode:evt.which;
//		if(nbr==17 || nbr==80){
//			//if(!confirm('[[.Are_you_sure_to_add_reservation.]]?')){
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
	<!--/IF:cond-->
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
           , fileName: '[[.bill_folio.]]'
        }); 
    });
</script>
