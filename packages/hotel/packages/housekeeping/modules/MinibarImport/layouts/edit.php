<!-- saved from url=(0014)about:internet -->
<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr>
    		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.number_of_items_needed_for_minibar.]]</td>
    		<td align="right"><input name="update" type="submit" value="[[.import_minibar.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
            <input name="print" type="submit" value="[[.print_import_voucher.]]" class="w3-btn w3-gray" style="text-transform: uppercase;"/></td>        
    	</tr>
    </table>
    <div style="text-align:left;background-color:#ECE9D8;width:100%;text-indent:20px;font-weight:bold;color:#FF0000;vertical-align:middle;">
        [[|error_message|]]
    </div>
    
    <table border="1" cellspacing="0" cellpadding="5" align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr class="w3-light-gray" style="text-transform: uppercase;">
            <td rowspan="3"><input name="minibar" type="checkbox" id="minibar" value="minibar" onclick="check_all();"/></td>  
            <td rowspan="3">[[.Minibar_name.]]</td>
            <td align="center" colspan="<?php echo sizeof([[=minibar_products_sample=]])*2;?>">[[.Minibar_product_list.]]</td>
        </tr>
        <tr>
            <!--LIST:minibar_products_sample-->
            <td align="center" colspan="2"><strong>[[|minibar_products_sample.name|]]</strong></td>
            <!--/LIST:minibar_products_sample-->  
        </tr>
        <tr>
            <!--LIST:minibar_products_sample-->
            <td align="center">[[.norm_quantity.]]</td>
            <td align="center">[[.used.]]</td>    
            <!--/LIST:minibar_products_sample-->      
        </tr>  
        <!--LIST:minibars-->
        <tr>
            <td><input name="check_[[|minibars.id|]]" id="check_[[|minibars.id|]]" type="checkbox" value="[[|minibars.id|]]"/></td>
            <td>Room [[|minibars.name|]]</td>
            <!--LIST:minibars.products-->
            <td align="center" style="color:blue;">[[|minibars.products.import_quantity|]]</td>
            <td align="center" style="color:red;"><!--IF:cond_minibar(isset([[=minibars.products.quantity=]]))-->[[|minibars.products.quantity|]]<!--/IF:cond_minibar--></td>    
            <!--/LIST:minibars.products-->
        </tr>
        <!--/LIST:minibars-->
    </table>
</div>
</form>
<script>
	function check_all()
	{
		 if($('minibar').checked==true)
		 {
		 	<!--LIST:minibars-->
				$('check_[[|minibars.id|]]').checked=true;
			<!--/LIST:minibars-->
		 }
		 else
		 {
		 	<!--LIST:minibars-->
				$('check_[[|minibars.id|]]').checked=false;
			<!--/LIST:minibars-->
		 }
	}
</script>