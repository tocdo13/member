<script>full_screen();</script>
<style>
.tdtitle
{
	font-size: 12px;
	font-weight:bold;
	text-transform:uppercase;
}
</style>
<div style="width:980px; margin:auto;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<form name="AddLaundryInvoiceForm" method="post">
<tr><td style="padding-left:10px;">
		<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
		<table cellpadding="0" width="100%" border="0">
		<tr>
			<td width="10%" align="center" valign="top"><img src="<?php echo HOTEL_LOGO; ?>" align="middle" width="100"></td>
			<td width="80%" align="center">
				<div style="height: 50px;">
					<font style="font-size: 12px; font-weight:bold; text-transform:uppercase"><?php echo HOTEL_NAME; ?></font>
				</div>
				<div>
					<font style="font-size: 16px; font-weight:bold; text-transform: uppercase;"><?php echo Portal::language('laundry_invoice');?></font>
				</div>
			</td>
			<td width="10%" align="center">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="padding-top:7px;">
				<table width="90%" cellpadding="0" cellspacing="0" border="0" style="text-align:left">
				<tr height="30px">
					<td nowrap="nowrap"><?php echo Portal::language('guest_name');?> :</td>
					<td width="60%"><?php echo $this->map['customer_name'];?></td>
					<td width="35%"><?php echo Portal::language('room');?> : <span id="room_name" style="width: 20%"><?php echo $this->map['room_name'];?></span></td>
				</tr>
				<tr>
					<td nowrap="nowrap"><?php echo Portal::language('date');?> :</td>
					<td><?php echo $this->map['date'];?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Portal::language('time');?> : <?php echo $this->map['hour'];?>
					</td>
					<td>
						<?php echo Portal::language('voucher_no');?> : <?php echo $this->map['invoice_id'];?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" class="data_title"><hr color="#000000" size="1"></td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="69%" valign="top" align="center">
				<table border="1" width="100%" bordercolor="#000000" cellpadding="5">
					<tr height="18px">
						<td rowspan="2" width="25%">
							<span class="tdtitle"><?php echo Portal::language('article');?></span>
						</td>
						<?php if(isset($this->map['categories']) and is_array($this->map['categories'])){ foreach($this->map['categories'] as $key1=>&$item1){if($key1!='current'){$this->map['categories']['current'] = &$item1;?>
						<td colspan="3" align="center"><span class="tdtitle"><?php echo $this->map['categories']['current']['name'];?></span></td>
						<?php }}unset($this->map['categories']['current']);} ?>
						<td rowspan="2"><b><?php echo Portal::language('total');?></b></td>
					</tr>
					<tr height="18px">
						<?php if(isset($this->map['categories']) and is_array($this->map['categories'])){ foreach($this->map['categories'] as $key2=>&$item2){if($key2!='current'){$this->map['categories']['current'] = &$item2;?>
						<td align="right"><b><?php echo Portal::language('price');?></b></td>
						<td align="center"><b><?php echo Portal::language('quantity');?></b></td>
                        <td align="center"><b><?php echo Portal::language('promotion');?></b></td>
						<?php }}unset($this->map['categories']['current']);} ?>
					</tr>
					<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current'] = &$item3;?>
					<tr height="22px">
						<td align="left"><span style="margin-left:5px; font-weight:bold"><?php echo $this->map['items']['current']['product_name'];?></span></td>
						<?php $i=1; ?>
						<?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['child']['current'] = &$item4;?>
						<td align="right" width="80px">
							<?php 
				if((isset($this->map['items']['current']['child']['current']['price']) and isset($this->map['items']['current']['child']['current']['product'])))
				{?>
							<span id="price_<?php echo $this->map['items']['current']['product_key'];?>_<?php echo $i; ?>"><?php echo $this->map['items']['current']['child']['current']['price'];?></span>
							 <?php }else{ ?>
							<span id="price_<?php echo $this->map['items']['current']['product_key'];?>_<?php echo $i; ?>"></span>
							
				<?php
				}
				?>
						</td>
						<td align="center" width="80px">
							<?php 
				if((isset($this->map['items']['current']['child']['current']['product'])))
				{?>
							<?php echo isset($this->map['items']['current']['child']['current']['quantity'])?$this->map['items']['current']['child']['current']['quantity']:'&nbsp;';?>
							
				<?php
				}
				?>
						</td>
                        <td align="center" width="80px">
							<?php 
				if((isset($this->map['items']['current']['child']['current']['product'])))
				{?>
							<?php echo isset($this->map['items']['current']['child']['current']['promotion'])?$this->map['items']['current']['child']['current']['promotion']:'&nbsp;';?>
							
				<?php
				}
				?>
						</td>
						<?php $i++; ?>
						<?php }}unset($this->map['items']['current']['child']['current']);} ?>
						<td><span id="total_<?php echo $this->map['items']['current']['product_key'];?>"><?php echo System::display_number_report($this->map['items']['current']['total']);?></span></td>
					</tr>
					<?php }}unset($this->map['items']['current']);} ?>
				</table>
			</td>
			<td width="1%"></td>
			<td width="30%" valign="top">
				<table width="100%" cellpadding="0" border="0">
					<tr>
						<td width="50%"><input name="instruction[]" value="REGULAR_SERVICE" type="checkbox" <?php echo $this->map['REGULAR_SERVICE']!=0?' checked':'';?>> <span style="text-transform:uppercase"><?php echo Portal::language('regular_service');?></span></td>
						<td width="50%">
							<input name="is_express_rate" value="EXPRESS" type="checkbox" <?php echo $this->map['express_rate']!=0?' checked':'';?>> <span style="text-transform:uppercase"><?php echo Portal::language('express');?></span>
						</td>
					</tr>
					<tr>
						<td width="50%"><input name="instruction[]" value="SHIRTS_ON_HANGER" type="checkbox" <?php echo $this->map['SHIRTS_ON_HANGER']!=0?' checked':'';?>/><span style="text-transform:uppercase"><?php echo Portal::language('shirts_on_hanger');?></span></td>
						<td width="50%"><input name="instruction[]" value="SHIRTS_FOLDED" type="checkbox" <?php echo $this->map['SHIRTS_FOLDED']!=0?' checked':'';?>/> <span style="text-transform:uppercase"><?php echo Portal::language('shirts_folded');?></span></td>
					</tr>
					<tr>
						<td width="50%"><input name="instruction[]" value="NO_STARCH" type="checkbox" <?php echo $this->map['NO_STARCH']!=0?' checked':'';?>/> <span style="text-transform:uppercase"><?php echo Portal::language('no_starch');?></span></td>
						<td width="50%"><input name="instruction[]" value="LIGHT_STARCH" type="checkbox" <?php echo $this->map['LIGHT_STARCH']!=0?' checked':'';?>/> <span style="text-transform:uppercase"><?php echo Portal::language('light_starch');?></span></td>
					</tr>
					<tr>
						<td colspan="2"><?php echo Portal::language('note');?></td>
					</tr>
					<tr>
						<td colspan="2" style="padding-right:15px;"><textarea  name="note" style="width:100%" rows="8"><?php echo String::html_normalize(URL::get('note',''.$this->map['note']));?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3"><hr color="#000000" size="1"></td>
		</tr>
		<tr>
			<td colspan="3" valign="top">
				<table cellpadding="0" width="100%" border="1" bordercolor="#000000" style="border:1px solid #000000; border-collapse:collapse; margin-bottom:7px;">
					<tr height="24px" valign="middle">
						<td width="24%" style="padding-left:4px;"><?php echo Portal::language('subtotal');?></td>
						<td width="12%" align="right" style="padding-right:7px;">
							<span id="subtotal"><?php echo $this->map['subtotal'];?></span>						</td>
						<td rowspan="6" width="63%">
							<div style="line-height: 18px; margin-left:10px;">
							* Call before 10.00 AM for same day return.<br />
							* Collection after 10.00 PM, next day delivery at 5.00 PM.<br />
							* For 4 hours Express Service, 50% surcharge will be added.<br />
							* <?php echo HOTEL_NAME; ?> is environment friendly and your laundry will only be wrapped into plastic bags, upon request.
							<br /><b>Notice:</b><br />
							* Please fill in the list in full and sign. Incase of discrepancy on count the Hotel count must be accepted as correct.<br />
							* Any claim must be reported with this list within 24 hours.<br />
							* The Hotel's liabilities for either loss or damage will not exceed the amount of 10 times the Laundry Charges.<br />
							* The Hotel can not be responsible for shrinkage or fastness of color, nor for valuables left in or gaments.<br />
							* All prices are subject to Goverment Tax and 5% Service Charge and may change without prior notice.<br />
							</div>						</td>
					</tr>
					<tr height="24px" valign="middle">
					  <td style="padding-left:4px;"> <?php echo $this->map['discount'];?>% <?php echo Portal::language('discount');?> </td>
					  <td align="right" style="padding-right:7px;"><span id="service_charge">
                        <?php 
				if(($this->map['total_discount']!='0.00'))
				{?>
                        <?php echo $this->map['total_discount'];?>
                        
				<?php
				}
				?>

                      </span> </td>
				  </tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;"><?php echo $this->map['express_rate'];?>% <?php echo Portal::language('express_service_surcharge');?></td>
						<td align="right" style="padding-right:7px;">
							<span id="express">
							<?php 
				if(($this->map['express']!='0.00'))
				{?>
							<?php echo $this->map['express'];?>
							
				<?php
				}
				?>
							</span>						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
							<?php echo $this->map['fee_rate'];?>% <?php echo Portal::language('service_charge');?>						</td>
						<td align="right" style="padding-right:7px;">
							<span id="service_charge">
							<?php 
				if(($this->map['service_charge']!='0.00'))
				{?>
							<?php echo $this->map['service_charge'];?>
							
				<?php
				}
				?>
							</span>						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;">
							<?php echo $this->map['tax_rate'];?>% <?php echo Portal::language('goverment_tax');?>						</td>
						<td align="right" style="padding-right:7px;">
							<span id="tax">
							<?php 
				if(($this->map['tax']!='0.00'))
				{?>
							<?php echo $this->map['tax'];?>
							
				<?php
				}
				?>
							</span>						</td>
					</tr>
					<tr height="24px" valign="middle">
						<td style="padding-left:4px;"><?php echo Portal::language('grant_total');?> (<?php echo HOTEL_CURRENCY; ?>)</td>
						<td align="right" style="padding-right:7px;">
							<?php echo $this->map['grant_total'];?>						</td>
					</tr>
				</table>
			</td>
		</tr>
        <tr>
			<td colspan="3">
				<div id="main_div_class" style="padding-bottom:40px;">
					<div id="sub_div_class" style="width:15%; padding-right: 3px; float:left">
							<?php Draw::button(Portal::language('delete_confirm'),false,false,true,'AddLaundryInvoiceForm');?>
					</div>
					<div id="sub_div_class" style="width:15%; padding-right: 3px; float:left">
							<?php Draw::button(Portal::language('list_laundry_invoice'),URL::build_current());?>
					</div>
					<input  name="id" id="id" / type ="hidden" value="<?php echo String::html_normalize(URL::get('id'));?>">
					<input type="hidden" name="confirm_delete" value="confirm_delete" />
					<input type="hidden" name="cmd" value="delete" />
					<div style="clear:both"></div>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</table>
</div>