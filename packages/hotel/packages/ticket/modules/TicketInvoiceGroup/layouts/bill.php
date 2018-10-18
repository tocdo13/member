<style>
	#bound{
		font-size:11px;
		font-family:Arial, Helvetica, sans-serif;
	}
    .des{display:none;}
</style>
<style type="text/css">
.price {font-size:12px; font-family: Arial, Helvetica, sans-serif}
</style>
<div id="bound" style="width:250px; float:left;">
	<div>
        <div style=" text-align:center; font-size:10px;">
          <p><span class="title_invoice" style="text-transform:uppercase; font-weight:bold;">
            </span><span class="title_invoice" style="font-size:20px;">[[.invoice.]]</span><br />
            <span class="title_invoice"><?php echo HOTEL_NAME;?></span> <br />
            <span class="title_invoice"><?php echo HOTEL_ADDRESS;?></span> <br />
            <span class="title_invoice">[[.tel.]]: <?php echo HOTEL_PHONE;?></span><br />
            
            <br />
          </p>
        </div>
    </div>
    <div><!--LIST:items2--> 
        <span>[[.invoice_number.]]:[[|items2.id|]]</span><br />
        <span>[[.guest.]]: [[|items2.customer_name|]]</span><br />
        <!--/LIST:items2-->
    	<span>[[.print_date.]]: <?php echo date(' H:i\' d/m/Y ');?></span><br />
        <span>[[.cashier.]]: <?php $user = Session::get('user_data'); echo $user['full_name'];?> </span><br />

    </div>
    <div style="margin-top:5px;">
    	<table width="100%" class="price">
        	<tr bgcolor="#CCCCCC">
            	<td  align="center" width="15">[[.num_of_tickets.]]</td>
                <td align="center">[[.name.]]</td>
                <td align="center">[[.price.]](*)</td>
                <td align="center">[[.amount.]]</td>
            </tr>
            <!--LIST:items-->
            <tr >
            	<td align="center">[[|items.quantity|]]</td>
                <td align="center">[[|items.ticket_name|]]</td>
                <td align="right">[[|items.price|]]</td>
                <td align="right">[[|items.total_price|]]</td>
            </tr>
            <tr>
                <td colspan="4" align="right">------------------------------------------------------</td>
            </tr>
            <?php if([[=items.total_discount_quantity=]]>0){?>
            <tr>
            	<td align="center"></td>
                <td align="left">[[.discount.]] SL: [[|items.discount_quantity|]]</td>
                <td align="right"></td>
                <td align="right">[[|items.total_discount_quantity|]]</td>
            </tr>
            <?php }?>
            <?php if([[=items.discount_cash=]]>0){?>
            <tr>
            	<td align="center"></td>
                <td align="left">[[.discount.]] [[.cash.]]</td>
                <td align="right"></td>
                <td align="right">[[|items.discount_cash|]]</td>
            </tr>
            <?php }?>
            <?php if([[=items.total_discount_rate=]]>0){?>
            <tr>
            	<td align="center"></td>
                <td align="left">[[.discount.]] [[|items.discount_rate|]]%</td>
                <td align="right"></td>
                <td align="right">[[|items.total_discount_rate|]]</td>
            </tr>
            <?php }?>
            <?php if(([[=items.discount_cash=]]+[[=items.total_discount_rate=]]+[[=items.total_discount_quantity=]])>0){ ?>
            <tr>
                <td colspan="4" align="right">--------------</td>
            </tr>
            <tr>
                <td align="right"></td>
            	<td align="left">[[.total.]]</td>
                <td align="left"></td>
                <td align="right">[[|items.total|]]</td>
            </tr>
            <tr>
                <td colspan="4" align="right">------------------------------------------------------</td>
            </tr>
            <?php } ?> 
            <!--/LIST:items-->
            <tr><td colspan="4" align="center">---------------------------------------------------------------</td></tr>
            <!--LIST:items2-->
            <?php if([[=items2.total_all_discount_rate=]]>0){?>
            <tr>
            	<td colspan="3" style="text-align: left;">[[.discount.]] [[.invoice.]]([[|items2.discount_rate|]]%)</td>
                <td align="right">[[|items2.total_all_discount_rate|]]</td>
            </tr>
            <?php }?>
            <?php if([[=items2.deposit=]]>0){?>
            <tr>
            	<td colspan="3" style="text-align: left;">[[.deposit.]]</td>
                <td align="right">[[|items2.deposit|]]</td>
            </tr>
            <?php }?>
            <tr >
                <th colspan="3" style="text-align: left;">[[.total.]]</th>
                <th>[[|items2.total_all|]]</th>
            </tr>
            <!--/LIST:items2--> 
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
                <td style="font-size: 15px;"><?php echo System::display_number($v['amount']); ?><?php if($v['currency_id']) echo $v['currency_id'] ?></td>
            </tr>
            <?php
            }
            ?>  
            
        </table>
        <?php } ?>
    </div>
</div>
<div style=" float: right; padding-right: 30px;">
<input style="width: 70px; height: 50px;"  type="button" name="print" id="print" value="Print" onclick="window.close();printWebPart('printer');"/>
<input style="width: 70px; height: 50px;"  type="reset" name="reset" id="exit" value="Thoát" onclick="window.close();"/>
</div>
<style type="text/css">
    @media print
    {
        #exit{display:none}
        #print{display:none}
    }
</style>