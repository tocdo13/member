<style>
	#bound{
		font-size:11px;
		font-family:Arial, Helvetica, sans-serif;
	}
    @media print{
        #printer_logo{
            display: none;
        }
    }
</style>
<style type="text/css">
.price {font-size:12px; font-family: Arial, Helvetica, sans-serif}
</style>
<div id="printer_logo" style="display: none;">
<a onclick="var content = jQuery('#printer').html();printWebPart_Dau(content);printed_tmp_bill();" title="Print" style="float: right;"><img src="packages/core/skins/default/images/printer.png" height="40"></a>
</div>
<div id="bound" style="width:280px; float:left; border: 1px solid black;" >
	<div>
        <div style=" text-align:center; font-size:10px;">
          <p><span class="title_invoice" style="text-transform:uppercase; font-weight:bold;">
          <?php if(Url::get(md5('preview')))
            {
        		echo Portal::language('invoice');
    		}
            else
            {
    			echo Portal::language('invoice');	
    		}
        ?>
            </span><br />
            <span class="title_invoice"><?php echo HOTEL_ADDRESS;?></span> <br />
            <span class="title_invoice"><?php echo Portal::language('tel');?>: <?php echo HOTEL_PHONE;?></span><br />
            <span class="title_invoice"><?php echo Portal::language('website');?>: <?php echo HOTEL_WEBSITE;?></span><br />
            <span class="title_invoice"><?php echo Portal::language('Email');?>: <?php echo HOTEL_EMAIL;?></span><br />
            <br />
          </p>
        </div>
    </div>
    <div>
    	<span><?php echo Portal::language('invoice');?>: #<?php echo $this->map['code'];?></span><br />
    	<span><?php echo Portal::language('print_date');?>: <?php echo date('d/m/Y H:i\'');?></span><br />
        <span><?php echo Portal::language('cashier');?>: <?php echo $this->map['user_id'];?></span><br />
        <span><?php echo Portal::language('person_order');?>: <?php echo $this->map['person_order'];?></span><br />
        <span><?php echo Portal::language('number_guest');?>: <?php echo $this->map['number_guest'];?></span><br />
        <span><?php echo Portal::language('guest_name');?>: <?php echo $this->map['guest_name'];?></span><br />
        <span><?php echo Portal::language('device_code');?>: <?php echo $this->map['device_code'];?></span><br />
        <span><?php echo Portal::language('guest_phone_number');?>: <?php echo $this->map['guest_phone_number'];?></span><br />
    </div>
    <div style="margin-top:5px;">
    	<table width="100%" class="price" style="font-size:11px;">
        	<tr bgcolor="#CCCCCC">
                <th align="left"><?php echo Portal::language('name');?></th>
                <th  align="center" width="15"><?php echo Portal::language('quantity');?></th>
                <th align="right"><?php echo Portal::language('price');?><?php if($this->map['full_rate']==1){echo '<span title="Rates include taxes and charges">(*)</span>';}else if($this->map['full_charge']==1){echo '<span title="Rates include charges">(*)</span>';}?></th>
                <!--<th style="width: 30px;" align="right">&nbsp;<?php echo Portal::language('discount_l');?>&nbsp;<br />(%)</th>
                <th style="width: 30px;" align="right">&nbsp;<?php echo Portal::language('promotion_l');?>&nbsp;<br />(%)</th>
                <th align="center">&nbsp;FOC&nbsp;</th>-->
                <th align="right"><?php echo Portal::language('amount');?></th>
            </tr>
            <?php if(isset($this->map['product_items']) and is_array($this->map['product_items'])){ foreach($this->map['product_items'] as $key1=>&$item1){if($key1!='current'){$this->map['product_items']['current'] = &$item1;?>
            <?php 
				if(($this->map['product_items']['current']['product__remain_quantity']!=0 && $this->map['product_items']['current']['cancel_all']==0))
				{?>
            <tr>
            	
                <td align="left"><?php echo $this->map['product_items']['current']['product__name'];?></td>
                <td align="center"><?php echo $this->map['product_items']['current']['product__remain_quantity'];?></td>
                <td align="right"><?php echo $this->map['product_items']['current']['product__price'];?></td>
                <!--<td align="right"><?php echo $this->map['product_items']['current']['discount_rate'];?></td>
                <td align="right"><?php echo $this->map['product_items']['current']['promotion'];?></td>
                <td align="center">N</td>-->
                <td align="right"><?php echo ($this->map['product_items']['current']['product__total']);?></td>
            </tr>
            
				<?php
				}
				?>
            <?php 
				if(($this->map['product_items']['current']['product__quantity_discount']!=0))
				{?>
            <tr>
            	
                <td align="left"><?php echo $this->map['product_items']['current']['product__name'];?></td>
                <td align="center"><?php echo $this->map['product_items']['current']['product__quantity_discount'];?></td>
                <td align="right"><?php echo $this->map['product_items']['current']['product__price'];?></td>
                <!--<td align="right">0</td>
                <td align="right">0</td>
                <td align="center">Y</td>-->
                <td align="right">0.00</td>
            </tr>
            
				<?php
				}
				?>
            <?php }}unset($this->map['product_items']['current']);} ?>
            <tr><td colspan="4" align="center"><hr /></td></tr>
            <!--IF:cond_total($this->map['tax_amount']>0 || $this->map['service_amount']>0 || $this->map['total_discount']>0 || $this->map['deposit'] > 0)-->
            <tr>
            	<td colspan="3" style="text-transform:uppercase;"><?php echo Portal::language('total_amount');?></td>
                <td align="right"><?php echo $this->map['total_amount'];?></td>
            </tr>
            <?php 
				if(($this->map['total_discount']!='0.00'))
				{?>
            <tr>
            	<td colspan="3" style="text-transform:uppercase;"><?php echo Portal::language('total_discount');?> (%)</td>
                <td align="right"><?php echo System::display_number(System::calculate_number($this->map['total_discount']));?></td>
            </tr>
            
				<?php
				}
				?>
            <?php 
				if(($this->map['bar_fee_rate']>0 && $this->map['full_charge'] == 0 && $this->map['full_rate'] == 0))
				{?>
            <tr>
            	<td colspan="3" style="text-transform:uppercase;"><?php echo $this->map['bar_fee_rate'];?>% &nbsp;<?php echo Portal::language('service');?></td>
                <td align="right"><?php echo System::display_number($this->map['service_amount']);?></td>
            </tr>
            
				<?php
				}
				?>
            <?php 
				if(($this->map['tax_rate']>0 && $this->map['full_rate'] == 0))
				{?>
            <tr>
            	<td colspan="3" style="text-transform:uppercase;"><?php echo $this->map['tax_rate'];?>% &nbsp;<?php echo Portal::language('tax');?></td>
                <td align="right"><?php echo System::display_number($this->map['tax_amount']);?></td>
            </tr>
             
				<?php
				}
				?>
             <?php 
				if(($this->map['deposit']!='0.00'))
				{?>
              <tr>
                <td colspan="3" style="text-transform:uppercase;"><?php echo Portal::language('deposit');?>:</td>
                <td align="right"><?php echo $this->map['deposit'];?></td>
              </tr>
              
				<?php
				}
				?>
             <tr style="font-weight:bold;">
            	<td colspan="3" style="text-transform:uppercase;"><?php echo Portal::language('total_payment');?></td>
                <td align="right"><?php echo $this->map['sum_total'];?></td>
            </tr>
            <tr>
            	<td colspan="4" style="text-align:right; font-size:11px;"><i>
						<?php
                        if($this->map['tax_rate']>0 && $this->map['full_rate'] == 1){
                            echo '(*) '.Portal::language('rates_include_taxes').' (10%)';	
                        }else if($this->map['bar_fee_rate']>0 && $this->map['full_charge']== 1){
                            echo '(*) '.Portal::language('rates_include_charges');	          
                        } ?></i>                </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; font-size:11px;">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right; font-size:11px;"><div align="center"><em>&nbsp;</em></div></td>
            </tr>
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
                <td style="font-size: 15px;"><?php echo System::display_number($v['amount']); ?><?php if($v['currency_id']) echo $v['currency_id'] ?></td>
            </tr>
            <?php
            }
            ?>  
            
        </table>
        <?php } ?>
    </div>
</div>
<script>
function printWebPart_Dau(content)
{
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
		var printWP = window.open("about:blank","printWebPart_Dau");
		printWP.document.open();
		printWP.document.write(html);
        //printWP.document.body.style.zoom= "40%";
		printWP.print();
		printWP.document.close();
		printWP.close();
	}
}
var printt = '<?php echo (Url::get('act')?Url::get('act'):'');?>';
if(printt == 1){
    var content = jQuery("#printer").html();	
    window.close();
    printWebPart_Dau(content);
}
</script>