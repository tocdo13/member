<fieldset id="toolbar" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="100%" style="#width:99%;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title">[[.transplant_order.]]</td>
						</tr>
					</table>
				</td>	
			</tr>	
			<tr>
            	<td align="right">[[.transplant_from_code.]]:</td>
				<td width="50%" valign="top"><select name="from_code" class="select-large" id="from_code"></select></td>
			</tr>
			<tr>
	            <td align="right">[[.to_code.]]</td>
				<td width="50%" valign="top"><select name="to_code" class="select-large" id="to_code"></select></td>
			</tr>
            <tr>
            	<td></td>
                <td><div ><input name="save" type="button" value="Thực hiện" onClick="checkSubmit();" /></div></td>
            </tr>
		</table>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
  </form>
</fieldset>
<script>
	function checkSubmit(){
		var k=0;
		if(jQuery('#from_code').val()==0){
			alert('Bạn chưa chọn bàn cần ghép');
			k = 1;	
			return false;
		}
		if(jQuery('#to_code').val()==0){
			alert('Bạn chưa chọn bàn ghép tới');
			k = 1;	
			return false;
		}
		if(k==0){
			jQuery('#action').val(1);
			SplitForm.submit();	
		}
	}
</script>