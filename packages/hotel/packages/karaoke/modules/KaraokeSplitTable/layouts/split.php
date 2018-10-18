<fieldset id="toolkaraoke" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="100%" style="#width:99%;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title">[[.split_order.]]</td>
						</tr>
                         <tr>
                        	<td align="right" style="text-align:right;">
                                [[.karaoke_name.]]: <select name="karaokes" id="karaokes" onchange="updateKaraoke();" style="height:30px;"></select> 
                                <input name="acction" type="hidden" value="0" id="acction" />
                               
                                <script>
                                    var karaoke_id = '<?php if(Url::get('karaoke_id')){ echo Url::get('karaoke_id');} else { echo '';}?>';
                                    if(karaoke_id != ''){
                                    	$('karaokes').value = karaoke_id;	
                                    }
                                 </script>
                        	</td>
                        </tr>
					</table>
				</td>	
			</tr>	
			<tr>
				<td width="50%" align="right" valign="top">[[.split_from_code.]]
				  <select name="from_code" class="select-large" id="from_code" onchange="SplitForm.submit();"  style="height:35px;"></select>
			    </td>
			</tr>
            <tr>
            	<td align="right">
                	<div style="width:200px;">
                	<fieldset>
                    <legend>[[.Table.]]</legend>
					<!--IF:cond(isset([[=tables=]]) and count([[=tables=]])>0)-->
					<div> <?php $i=1;?>
						<!--LIST:tables-->
							<div align="right" style="margin:3px;">
								&nbsp;[[|tables.name|]] : <select  name="to_table_[[|tables.id|]]" class="to_table" onchange="$('split_table_<?php echo $i;?>').checked=true;update_table_id(this);" style="height:35px;">
                                [[|tables_option|]]
                                </select><input name="split_table[[[|tables.id|]]]" type="checkbox" id="split_table_<?php echo $i;?>" value="[[|tables.id|]]" />
							</div>
							<?php $i++;?>
						<!--/LIST:tables-->
					</div>
					<!--/IF:cond-->
                    </fieldset>
                    </div>
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
								<input name="quantity[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="text" id="quantity_[[|orders.id|]]" lang="[[|orders.id|]]" style="width:40px;height:30px;font-size:18px;text-align:center" onclick="select();if($('order_[[|orders.id|]]').checked){$('order_[[|orders.id|]]').checked=false;}else{$('order_[[|orders.id|]]').checked=true;}" onchange="" maxlength="5" class="input_number" onkeyup="if(this.value>jQuery('#quantity_before_[[|orders.id|]]').val()){this.value='';}">
								<input name="order[[[|orders.id|]]]" value="[[|orders.id|]]" type="checkbox" id="order_[[|orders.id|]]" class="order">
							</div>
						<!--/LIST:orders-->
					</div>
					<!--/IF:cond-->
				</td>
				<td align="left">&nbsp;</td>
			</tr>				
		</table>
        <div align="center" >
        <input name="save" type="submit" style="width: 70px; height: 50px;" value="Thực hiện" id="save"/>
        <input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.close()"/>
        </div>
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
	function updateKaraoke(){
		jQuery('#acction').val(1);
		SplitForm.submit();
	}
</script>
