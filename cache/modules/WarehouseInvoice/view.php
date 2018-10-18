<style>

@media print{
    .check_hidden{
        display: none;
    }
}
</style>
<div style="width:720px;padding:10px;text-align:center;font-size:14px;float:left;">	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
		</td>
		<td align="right">
			<?php echo Portal::language('no1');?>: <?php echo $this->map['bill_number'];?><br />
			<?php echo Portal::language('day');?>:&nbsp;<?php echo $this->map['day'];?>/<?php echo $this->map['month'];?>/<?php echo $this->map['year'];?><br />
            <?php 
                if($this->map['type']=='IMPORT' and $this->map['invoice_number']!='')
                echo 'HD: '.$this->map['invoice_number'];
            ?>
		</td>
	</tr>
</table>


</div>
<br clear="all"/>

<div style="text-align:left;">
<div style="width:720px;padding:2px 2px 2px 2px;text-align:center;font-size:14px;">
    <div style="padding:2px 2px 2px 2px;">
        <div style="text-indent:0px;vertical-align:top;font-size:16px;text-transform:uppercase;font-weight:bold;"><?php echo $this->map['title'];?></div>
        <div>
        	<table width="100%">
                <tr valign="top">
                    <td width="70%" style="font-size:12px;text-align:left">
                        <!--&#272;&#417;n v&#7883;:
                        <?php echo $this->map['supplier_name'];?><br/-->
                         <!--ELSE-->
                        <?php echo $this->map['warehouse_name'];?> <br/>
                        <!--/IF:cond-->
                        <!--Ng&#432;&#7901;i giao: <?php echo $this->map['deliver_name'];?> <br />
                        Ng&#432;&#7901;i nh&#7853;n: <?php echo $this->map['receiver_name'];?>->
                    </td>
                    <!--td width="30%" align="right" nowrap="nowrap" style="font-size:12px;">
                        Nh&acirc;n vi&ecirc;n: <?php echo $this->map['staff_name'];?><br />
                        <strong><?php echo $this->map['warehouse_name'];?></strong><br />
                    </td-->
                </tr>
                <?php if($this->map['supplier_name']!=''){?>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left"><?php echo Portal::language('supplier');?>: 
                    <em><?php echo $this->map['supplier_name'];?></em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
                <?php }?>
                <?php if($this->map['wh_receiver_name']!=''){?>
                <tr valign="top">
                    <td style="font-size:12px;text-align:left"><?php echo Portal::language('to_deception');?>: 
                    <em><?php echo $this->map['wh_receiver_name'];?></em>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
                <?php }?>
        		<tr valign="top">
                    <td style="font-size:12px;text-align:left"><?php echo Portal::language('description');?>: 
                    <em><?php echo $this->map['note'];?></em> &nbsp; <?php echo $this->map['get_back_supplier_name'];?>
                    </td>
                    <td align="right" nowrap="nowrap"  style="font-size:12px;">&nbsp;</td>
                </tr>
        	</table>
        </div>
        <div style="padding:2px 2px 2px 2px;text-align:left;">&nbsp;</div>
        <div style="text-align:left;">
            <table width="100%" border="1" cellspacing="0" cellpadding="2" style="border-collapse:collapse" bordercolor="#000000">
                <tr>
                    <th width="4%" scope="col"><?php echo Portal::language('no');?></th>
                    <th width="25%" align="center" scope="col"><?php echo Portal::language('product_name');?> <br /></th>
                    <th width="11%" align="center" scope="col"><?php echo Portal::language('code');?></th>
                    <th width="10%" scope="col" align="center"><?php echo Portal::language('unit');?></th>
                    <!--<th width="15%" align="center" scope="col">Kho</th>-->
                    <th width="10%" scope="col" align="center"><?php echo Portal::language('quantity');?>  </th>
                    <th width="100" scope="col" align="center"><?php echo Portal::language('price');?> </th>
                    <th width="160" scope="col" align="center"><?php echo Portal::language('amount');?> </th>
                </tr>
                <tr>
                    <td align="center">A</td>
                    <td align="center">B</td>
                    <td align="center">C</td>
                    <td align="center">D</td>
                    <!--<td align="center">E</td>-->
                    <td align="center">1</td>
                    <td width="150" align="center">2</td>
                    <td align="right" nowrap="nowrap">3=(1x2)</td>
                </tr>
                <?php if(isset($this->map['products']) and is_array($this->map['products'])){ foreach($this->map['products'] as $key1=>&$item1){if($key1!='current'){$this->map['products']['current'] = &$item1;?>
                <tr>
                    <td align="center"><?php echo $this->map['products']['current']['i'];?></td>
                    <td align="left" style="padding:0 0 0 10px;"><?php echo $this->map['products']['current']['name'];?></td>
                    <td align="center" nowrap="nowrap"><?php echo $this->map['products']['current']['product_id'];?></td>
                    <td align="center"><?php echo $this->map['products']['current']['unit_name'];?></td>
                    <!--<td align="left"><?php echo $this->map['products']['current']['warehouse'];?></td>-->
                    <td align="right" ><?php echo $this->map['products']['current']['number'];?></td>
                    <td width="150" align="right" class="amount"><?php echo System::display_number(round($this->map['products']['current']['price'],2));?></td>
                    <?php 
				if(($this->map['products']['current']['num']>0))
				{?>
                        <td width="150" align="right" class="amount"><?php echo System::display_number(round($this->map['products']['current']['payment_amount'],2));?></td>
                     <?php }else{ ?>
                        <td width="150" align="right"  class="amount"><?php echo System::display_number(round($this->map['products']['current']['money_add'],2));?></td>
                    
				<?php
				}
				?>
                </tr>
                <?php }}unset($this->map['products']['current']);} ?>
                <?php //for($i=0;$i<=20;$i++){?>
               <!-- <tr>
                    <td>&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                </tr>-->
                <?php 
                /*if($i==1)
                {
                echo '<div style="display:none;page-break-after:always;">';
                }
                } */?> 
                <tr>
            		<td>&nbsp;</td>
            		<td align="center" colspan="5">
                        <p style="text-align: right;margin-right:5px;"><?php echo Portal::language('total');?></p>
                        <p style="text-align: right;margin-right:5px;"><?php echo Portal::language('tax');?></p>
                        <p style="text-align: right; margin-right:5px;"><?php echo Portal::language('total_amount');?></p>
                    </td>
    				<td align="right">
                        <p class="amount"><?php echo System::display_number(round($this->map['total']));?></p>
                        <p class="amount"><?php echo System::display_number(round($this->map['tax']));?></p>
                        <p class="amount"><?php echo System::display_number(round($this->map['total_amount']));?></p>
                    </td>
                </tr>
            </table>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td colspan="5" align="right">
                    <?php echo Portal::language('amount_in_words');?>:&nbsp;<em><?php
                    require_once 'packages/core/includes/utils/currency.php';
                    echo currency_to_text(round($this->map['total_amount']));
                    ?></em><br />
                </td>
            </tr>
            
            <tr>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td colspan="3" align="right"><em><?php echo $this->map['day'];?>/<?php echo $this->map['month'];?>/<?php echo $this->map['year'];?>&nbsp;</em></td>
            </tr>
        	<tr>
                <!-- trung :an muc nay
                <td width="20%" align="center">
                    <?php if(Url::get('type')=='IMPORT'){?><?php echo Portal::language('shipper');?><?php }else{?><?php echo Portal::language('receiver');?><?php }?>
                </td>
                -->
                <td align="center" width="40%">
                    <?php echo Portal::language('accountant_shift');?>
                </td>
                <!-- trung :an muc nay
                <td align="center" width="20%">
                    <?php echo Portal::language('Chef');?>
                </td>
                -->
                <td width="15%">
                
                </td>
                <td width="45%" align="center">
                    <span style="width:25%;text-align:center;"><?php echo Portal::language('warehouseman');?></span>
                </td>
                <!--
                <td width="20%" align="center">
                    <span style="width:25%;text-align:center;"><?php echo Portal::language('accounting_department');?><br /></span>
                </td>
                -->                
        	</tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <!--
                <td>
                    <span style="width:100%;text-align:center; display: block;"><?php echo $this->map['user_name'];?><br /></span>
                </td>
                -->
            </tr>
        </table>
    </div>
</div>
</div>
<script>
    //Number.prototype.formatMoney = function(c, d, t){
//    var n = this, 
//        c = isNaN(c = Math.abs(c)) ? 2 : c, 
//        d = d == undefined ? "." : d, 
//        t = t == undefined ? "," : t, 
//        s = n < 0 ? "-" : "", 
//        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
//        j = (j = i.length) > 3 ? j % 3 : 0;
//       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
//     };
//        jQuery(".price").each(function(){
//            var str = jQuery(this).html();
//            jQuery(this).html(parseFloat(str).formatMoney(2,',','.'));
//        });
//        jQuery(".amount").each(function(){
//            var str = jQuery(this).html();
//            jQuery(this).html(parseFloat(str).formatMoney(2,',','.'));
//        });
</script>
