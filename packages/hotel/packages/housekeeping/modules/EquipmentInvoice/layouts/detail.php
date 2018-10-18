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
<input name="export_excel" id="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 100px; height: 23px"/>
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
                <div align="left">[[.code.]]: EQ_[[|position|]]</div>
				<div align="left">[[.room_no.]]: [[|room_name|]]</div>
				<div>[[.customer.]]: [[|customer_name|]]</div>			</th>
          </tr>
		  <tr>
		  	<td colspan="6" align="center">
				<h3 style="margin-bottom:4px;">[[.compensation_invoice.]]</h3>
				<div style="margin-bottom:10px;">[[.date.]] : [[|date|]]</div>
                <div style="margin-bottom:10px; float:right;">[[.note.]] : [[|note|]]</div>			</td>
		  </tr>
          <tr>
          	<th>[[.stt.]]</th>
            <th>[[.equipment_id.]]</th>
            <th width="200px">[[.equipment_name.]]</th>
            <th align="center">[[.price.]]</th>
            <th>[[.quantity.]]</th>
            <th>[[.amount.]]</th>
          </tr>
		  
		  <?php $i=1; ?>
		  <!--LIST:items-->
		  <tr>
		  	<td align="center"><?php echo $i++; ?></td>
			<td>[[|items.code|]]</td>
			<td>[[|items.name|]]</td>
			<td class="to_numeric" align="right">[[|items.price|]]</td>
			<td align="center">[[|items.quantity|]]</td>
			<td class="to_numeric" align="right">[[|items.amount|]]</td>
			</tr>
		  <!--/LIST:items-->
		<tr>
            <td colspan="5" align="right" style="padding-right:10px; border:none;"><strong>[[.total.]]:</strong></td><td class="to_numeric" align="right">[[|total_before_tax|]]</td>
		</tr>
		<tr style="display:none;"><td colspan="5" align="right" style="padding-right:10px;"><strong>[[.tax_rate.]]:</strong>( [[|tax_rate|]]%)</td><td align="right">[[|tax|]]</td>
		</tr>
		<tr style="display:none;"><td colspan="5" align="right" style="padding-right:10px;"><strong>[[.total.]]:</strong></td><td class="to_numeric" align="right">[[|total|]]</td>
		</tr>
		</table>
		<table width="100%" style="margin-top:20px;">
		  <tr>
		  	<td width="30%" align="left" colspan="3" style="border-top:1px solid black; padding-bottom:100px;">
				[[.guest_signature.]]
			</td>
			<td>&nbsp;</td>
			<td width="30%" align="right" colspan="2" valign="top" style="border-top:1px solid black;">
				[[.inventory_taken.]]
			</td>
		  </tr>
        </table>		
	</td>
    </tr>
  </table>
</form>
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
       , fileName: '[[.compensation_invoice.]]'
    });       
    
    
});
</script>