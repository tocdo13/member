<fieldset id="toolbar" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="80%" style="margin:auto;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title">[[.split_order.]]</td>
						</tr>
					</table>
				</td>	
			</tr>	
			<tr>
				<td width="50%" align="right" valign="top">[[.split_from_code.]]
				  <select name="from_code" class="select-large" id="from_code" onchange="SplitForm.submit();"></select>
			    </td>
                <td width="50%" align="left">
                	<div style="width:200px;">
					<!--IF:cond(isset([[=tables=]]) and count([[=tables=]])>0)-->
					<div>
						<!--LIST:tables-->
							<div align="right" style="margin:3px;">
								&nbsp;[[|tables.name|]] : <select  name="to_table_[[|tables.id|]]" class="to_table" onchange="update_table_id(this);">
                                [[|tables_option|]]
                                </select><input name="split_table[[[|tables.id|]]]" type="checkbox" id="split_table" value="[[|tables.id|]]" />
							</div>
						<!--/LIST:tables-->
					</div>
					<!--/IF:cond-->
                    </div>
                </td>
			</tr>
			<tr>
				<td align="right">  
					<!--IF:cond(isset([[=orders=]]) and count([[=orders=]])>0)-->
					<div>
						<!--LIST:orders-->
							<div align="right" style="margin:3px;" onclick="ChangeProduct(\'[[|orders.id|]]\');">
								<label for="order_[[|orders.id|]]">[[|orders.name|]] [<strong>[[|orders.quantity|]]</strong>]</label> [[.conversition.]] 
								<input name="quantity_before[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="hidden" id="quantity_before_[[|orders.id|]]">
								<input name="quantity[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="text" id="quantity_[[|orders.id|]]" style="width:30px;text-align:center" maxlength="5">
								<input name="order[[[|orders.id|]]]" lang="[[|orders.id|]]" type="input" id="order_[[|orders.id|]]" class="order" style="width: 35px;height: 32px;background: transparent url(/../../packages/hotel/skins/default/images/iosstyle/next-horizontal.png) no-repeat 0 0; border:none;">
							</div>
						<!--/LIST:orders-->
					</div>
					<!--/IF:cond-->  
				</td>
                
				<td align="left" id="change_to"></td>
			</tr>				
		</table>
        <div align="center" ><input name="save" type="submit" value="Thực hiện" id="save"/></div>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
        <input name="new_table_id" type="hidden" id="new_table_id">
  </form>
</fieldset>
<script>
	jQuery('#from_code').val('<?php if(Url::get('from_code')){echo Url::get('from_code');} else {echo '';}?>');
	function update_table_id(obj){
		if(jQuery('#new_table_id').val() == ''){
			jQuery('#new_table_id').val(obj.value);
		}else{
			jQuery('#new_table_id').val(jQuery('#new_table_id').val()+','+obj.value);
		}
	}
	product = [[|orders_js|]];
	function ChangeProduct(id){
		jQuery('#change_to').append('<div id="change_to_'+id"></div>');	
	}
</script>