<script>
	function change_product(cmd)
	{
		jQuery('#action').val(cmd);
		document.SplitForm.submit();
	}
</script>
<fieldset id="toolbar" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="100%" style="#width:99%;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title">[[.split_table.]]</td>
						</tr>
					</table>
				</td>	
			</tr>	
			<tr>
				<td width="50%" align="right" valign="top">[[.from_table.]]
				  <select name="from_table" class="select-large" id="from_table" onchange="change_product('change_table');"></select>
			    </td>
			  <td width="50%" align="left">[[.to_talbe_split.]]
			    <select name="to_table" class="select-large" id="to_table"></select><input name="save" value="[[.save.]]" type="submit">
		      </td>
			</tr>	
			<tr>
				<td align="right">
					<!--IF:cond(isset([[=orders=]]) and count([[=orders=]])>0)-->
					<div>
						<!--LIST:orders-->
							<div align="right" style="margin:3px;">
								<label for="order_[[|orders.id|]]">[[|orders.name|]] [<strong>[[|orders.quantity|]]</strong>]</label> [[.conversition.]] 
								<input name="quantity_before[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="hidden" id="quantity_before_[[|orders.id|]]">
								<input name="quantity[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="text" id="quantity_[[|orders.id|]]" style="width:30px;text-align:center" maxlength="5">
								<input name="order[[[|orders.id|]]]" value="[[|orders.id|]]" type="checkbox" id="order_[[|orders.id|]]">
							</div>
						<!--/LIST:orders-->
					</div>
					<!--/IF:cond-->
				</td>
				<td align="left">&nbsp;</td>
			</tr>				
		</table>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
  </form>
</fieldset>
