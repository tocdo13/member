<style>
#to_code option{padding:10px 0px;}
</style>
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
                        <tr>
                        	<td align="right" style="text-align:right;">
                                [[.bar_name.]]: <select name="bars" id="bars" onchange="updateBar();" style="height:30px;font-size: 25px;"></select> 
                                <input name="acction" type="hidden" value="0" id="acction" />
                                
                                <script>
                                    var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                                    if(bar_id != ''){
                                    	$('bars').value = bar_id;	
                                    }
                                 </script>
                        	</td>
                        </tr>
					</table>
				</td>	
			</tr>	
			<tr>
            	<td align="right">Tranfer dish from table:</td>
				<td width="50%" valign="top"><select name="from_code"  class="select-large" id="from_code" onchange="jQuery('#to_code option[value='+this.value+']').attr('disabled','disabled');" style="height:35px;></select></td>
			</tr>
			<tr>
	            <td align="right">[[.to_code.]]</td>
				<td width="50%" valign="top"><select name="to_code" class="select-large" id="to_code" style="height:35px;"></select></td>
			</tr>
            <tr>
            	<td></td>
                <td><div >
                    <input style="width: 70px; height: 50px;" name="save" type="submit" value="Thực hiện" onclick="if(jQuery('#from_code').val() != 0 && jQuery('#to_code').val() != 0){return true;}else{ alert('Bàn ghép không được để trống!'); return false;}" />
                     <input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.close()"/>
                </div></td>
            </tr>
		</table>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
  </form>
</fieldset>
<script>
	function updateBar(){
		jQuery('#acction').val(1);
		SplitForm.submit();
	}
</script>