<style>
@media print{
	   #export_excel{
	       display: none;
	   }
  }
</style>
<div ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_title'));?>
<div id="print">
<input name="export_excel" id="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 100px; height: 23px"/>
<form name="AddEquipmentInvoiceForm" method="post" >
  <table id="Export" width="650px" border="1" cellpadding="10" cellspacing="0" style="font-family:'Times New Roman', Times, serif; margin:0 auto;font-size:12px;border-collapse:collapse" bordercolor="black">
    <tr>
      <td width="100%">
	  	<table width="100%" border="0" >
          <tr>
            <td width="19%" id="imgs"><div align="center"><img src="<?php echo HOTEL_LOGO; ?>" width="100" height="83" /></div></td>
<td></td>
            <td></td>
            <td></td>
            <td width="56%" align="left"><div style="text-transform:uppercase"><?php echo HOTEL_NAME;?></div>
              <div>Add : <?php echo HOTEL_ADDRESS; ?></div>
              <div>Tel : <?php echo HOTEL_PHONE;?>* Fax : <?php echo HOTEL_FAX;?></div>
              <div>E-mail : <?php echo HOTEL_EMAIL;?></div></td>
          </tr>
        </table>
        <table width="100%" border="1" bordercolor="#000000" style="border-collapse:collapse" cellspacing="0" cellpadding="5px">
          <tr>
            <th colspan="6" scope="col" align="left">
                <div align="left"><?php echo Portal::language('code');?>: EQ_<?php echo $this->map['position'];?></div>
				<div align="left"><?php echo Portal::language('room_no');?>: <?php echo $this->map['room_name'];?></div>
				<div><?php echo Portal::language('customer');?>: <?php echo $this->map['customer_name'];?></div>			</th>
          </tr>
		  <tr>
		  	<td colspan="6" align="center">
				<h3 style="margin-bottom:4px;"><?php echo Portal::language('compensation_invoice');?></h3>
				<div style="margin-bottom:10px;"><?php echo Portal::language('date');?> : <?php echo $this->map['date'];?></div>
                <div style="margin-bottom:10px; float:right;"><?php echo Portal::language('note');?> : <?php echo $this->map['note'];?></div>			</td>
		  </tr>
          <tr>
          	<th><?php echo Portal::language('stt');?></th>
            <th><?php echo Portal::language('equipment_id');?></th>
            <th width="200px"><?php echo Portal::language('equipment_name');?></th>
            <th align="center"><?php echo Portal::language('price');?></th>
            <th><?php echo Portal::language('quantity');?></th>
            <th><?php echo Portal::language('amount');?></th>
          </tr>
		  
		  <?php $i=1; ?>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		  <tr>
		  	<td align="center"><?php echo $i++; ?></td>
			<td><?php echo $this->map['items']['current']['code'];?></td>
			<td><?php echo $this->map['items']['current']['name'];?></td>
			<td class="to_numeric" align="right"><?php echo $this->map['items']['current']['price'];?></td>
			<td align="center"><?php echo $this->map['items']['current']['quantity'];?></td>
			<td class="to_numeric" align="right"><?php echo $this->map['items']['current']['amount'];?></td>
			</tr>
		  <?php }}unset($this->map['items']['current']);} ?>
		<tr>
            <td colspan="5" align="right" style="padding-right:10px; border:none;"><strong><?php echo Portal::language('total');?>:</strong></td><td class="to_numeric" align="right"><?php echo $this->map['total_before_tax'];?></td>
		</tr>
		<tr style="display:none;"><td colspan="5" align="right" style="padding-right:10px;"><strong><?php echo Portal::language('tax_rate');?>:</strong>( <?php echo $this->map['tax_rate'];?>%)</td><td align="right"><?php echo $this->map['tax'];?></td>
		</tr>
		<tr style="display:none;"><td colspan="5" align="right" style="padding-right:10px;"><strong><?php echo Portal::language('total');?>:</strong></td><td class="to_numeric" align="right"><?php echo $this->map['total'];?></td>
		</tr>
		</table>
		<table width="100%" style="margin-top:20px;">
		  <tr>
		  	<td width="30%" align="left" colspan="3" style="border-top:1px solid black; padding-bottom:100px;">
				<?php echo Portal::language('guest_signature');?>
			</td>
			<td>&nbsp;</td>
			<td width="30%" align="right" colspan="2" valign="top" style="border-top:1px solid black;">
				<?php echo Portal::language('inventory_taken');?>
			</td>
		  </tr>
        </table>		
	</td>
    </tr>
  </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
	jQuery("#export_excel").click(function () {
    jQuery('.to_numeric').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html())); 
    });
    jQuery('#imgs').remove();
    jQuery("#Export").clone().find("img").remove();
    jQuery("#Export").battatech_excelexport({
        containerid: "Export"
       , datatype: 'table'
       , fileName: '<?php echo Portal::language('compensation_invoice');?>'
    });       
    
    
});
</script>