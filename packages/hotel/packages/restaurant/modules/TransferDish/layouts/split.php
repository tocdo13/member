<style>
.chk_label {
    display: inline;
}
 
 
.chk_order + .chk_label {
    background-color: #fafafa;
    border: 1px solid #cacece;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
    padding: 9px;
    border-radius: 3px;
    display: inline-block;
    position: relative;
}
 
.chk_order + .chk_label:active, .chk_order:checked + .chk_label:active {
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
}
 
.chk_order:checked + .chk_label {
    background-color: #e9ecee;
    border: 1px solid #adb8c0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);
    color: #99a1a7;
}
 
.chk_order:checked + .chk_label:after {
    content: '\2714';
    font-size: 21px;
    position: absolute;
    top: 0px;
    left: 3px;
    color: red;
}
 
 
.big-checkbox + .chk_label {
    padding: 18px;
}
 
.big-checkbox:checked + .chk_label:after {
    font-size: 28px;
    left: 6px;
}

</style>
<fieldset id="toolbar" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="100%" style="#width:99%;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title">Tranfer dish</td>
						</tr>
                         <tr>
                        	<td align="right" style="text-align:right;">
                                [[.bar_name.]]: <select name="bars" id="bars" onchange="updateBar();" style="height:30px;"></select> 
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
				<td width="50%" align="right" valign="top">Tranfer dish from table
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
								&nbsp;[[.to_table.]] : <select  name="to_table_[[|tables.id|]]" id="to_table" class="to_table" onchange="$('split_table_<?php echo $i;?>').checked=true;update_table_id(this);" style="height:35px;">
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
								<input name="quantity_before[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="hidden" id="quantity_before_[[|orders.id|]]"/>
                                <input name="printed_before[[[|orders.id|]]]" value="[[|orders.printed|]]" type="hidden" id="printed_before[[|orders.id|]]"/>
								<input name="quantity[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="text" id="quantity_[[|orders.id|]]" lang="[[|orders.id|]]" style="width:40px;height:30px;font-size:18px;text-align:center" onclick="getNumberKeyboard('quantity_[[|orders.id|]]');select();if($('order_[[|orders.id|]]').checked){$('order_[[|orders.id|]]').checked=false;}else{$('order_[[|orders.id|]]').checked=true;}" onchange="" maxlength="5" class="input_number" onkeyup="if(this.value>jQuery('#quantity_before_[[|orders.id|]]').val()){this.value='';}">
								<input name="order[[[|orders.id|]]]" value="[[|orders.id|]]" type="checkbox" id="order_[[|orders.id|]]" class="chk_order"/>
                                <input name="complete[[[|orders.id|]]]" value="[[|orders.complete|]]" type="hidden" id="complete_[[|orders.id|]]"/>
                                <label class="chk_label" for="order_[[|orders.id|]]"></label>
							</div>
						<!--/LIST:orders-->
					</div>
					<!--/IF:cond-->
				</td>
				<td align="left">&nbsp;</td>
			</tr>				
		</table>
        <div align="center" >
        <input name="save" type="submit" style="width: 70px; height: 50px;" value="Thực hiện" id="save" onclick="if(jQuery('#to_table').val() != 0){return true;}else{ alert('Bạn chưa chọn đến bàn!'); return false;}" />
        <input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.close()"/>
        </div>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
        <input name="new_table_id" type="hidden" id="new_table_id">
  </form>
</fieldset>
<div id="view_keyboard"></div>
<script>
	jQuery('#from_code').val('<?php if(Url::get('from_code')){echo Url::get('from_code');} else {echo '';}?>');
	function update_table_id(obj){
		//if(jQuery('#new_table_id').val() == ''){
			jQuery('#new_table_id').val(obj.value);
		//}else{
		//	jQuery('#new_table_id').val(jQuery('#new_table_id').val()+','+obj.value);
		//}
	}
	function updateBar(){
		jQuery('#acction').val(1);
		SplitForm.submit();
	}
    function getNumberKeyboard(name)
    {
        var obj = name;
    	var textkeyboard= '<div id="select_number_" class="select_number" onclick="event.stopPropagation();"><span id="title_quantity" style="font-size:14px;font-weight:bold;"></span>';
    	textkeyboard += '<input name="number_selected" type="text" id="number_selected" class="input_number" style="width:222px; height:35px; margin-bottom:10px; text-align:right;font-size:18px;"><ul id="list_number" class="list_number">';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(1,\''+obj+'\');">1</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(2,\''+obj+'\');">2</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(3,\''+obj+'\');">3</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(4,\''+obj+'\');">4</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(5,\''+obj+'\');">5</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(6,\''+obj+'\');">6</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(7,\''+obj+'\');">7</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(8,\''+obj+'\');">8</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(9,\''+obj+'\');">9</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(0,\''+obj+'\');">0</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(12,\''+obj+'\');">.</li>';
    	textkeyboard += '<li  class="menu_extra_li" onclick="selectNumber_Lang(13,\''+obj+'\');"><-</li>';
    	textkeyboard += '</ul>';
    	textkeyboard += '<input name="clear_number" type="button" value="CLEAR" id="clear_number" onclick="jQuery(\'#number_selected\').val(\'\');" class="button_disable_small"><input name="cancel_number" type="button" value="CANCEL" id="cancel_number" onclick="HideDialog(\'select_number_\');" class="button_disable_small">';
    	textkeyboard += '<input name="ok_number" type="button" value="OK" id="ok_number" onclick="setNumber('+obj+')" class="button_allow"></div>';
    	jQuery('#view_keyboard').html(textkeyboard); 
        jQuery("#select_number_").fadeIn(300);	
    	jQuery('#number_selected').focus();
    	jQuery('#number_selected').ForceNumericOnly();  
    }
    function setNumber(name)
    {
        //var my_selector = '#'+name;
        jQuery(name).val(jQuery("#number_selected").val());
        HideDialog('select_number_');
    }
    function selectNumber_Lang(value,obj)
    {
    	if(value==11)
        {
    		HideDialog('select_number');
    		var quantity_select = to_numeric(jQuery('#number_selected').val());
    		jQuery('.ids_selected').each(function()
            {
    			var id_selected=jQuery(this).attr('lang');
    			if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked')
                {
    				if(obj == 'quantity')
                    {
    					jQuery('#quantity_'+id_selected).val(to_numeric(jQuery('#quantity_'+id_selected).val())+quantity_select);	
    					update_item_amount(id_selected);
    				}
                    else 
                    if(obj == 'delete')
                    {
    					if(to_numeric(jQuery('#quantity_'+id_selected).val()) <= quantity_select)
                        {
    						DeleteProduct(id_selected);
    						id_selected = '';
    					}
                        else
                        {
    						jQuery('#quantity_'+id_selected).val(to_numeric(jQuery('#quantity_'+id_selected).val())-quantity_select);	
    						update_item_amount(id_selected);
    					}
    				}
                    else if(obj == 'percentage' && quantity_select<100)
                    {
    					jQuery('#percentage_'+id_selected).val(quantity_select);
    					update_item_amount(id_selected);
    				}
    			}
    		});
    		if(obj == 'discount' && quantity_select<=100)
            {  
    			jQuery('#discount_percent').val(quantity_select);
    		}
    		//GetTotalPayment();	
    		return;
    	}
    	var old_val = jQuery('#number_selected').val();	
    	var old_val_1 = old_val;
    	if(value==13)
        {
    		old_val = old_val.substr(0,old_val.length - 1);
    	}
        else
        {
    		if(value==12)
            {
    			var n = old_val.indexOf(".");
    			if(n<0)
                {
    				old_val = old_val + '.';
    			}
    		}
            else
            {
  				old_val = old_val + value;
                var max_value = to_numeric(jQuery('#'+obj).val());
                if(old_val > max_value)
                {
                    return false;
                }
    		}
    	}
    	if((obj == 'percentage') && to_numeric(old_val) <= 100)
        {
    		jQuery('#number_selected').val(old_val);
    	}
        else if((obj == 'percentage') && to_numeric(old_val) > 100)
        {
    		jQuery('#number_selected').val(old_val_1);
    	}
        else if(obj != 'percentage')
        {
    		jQuery('#number_selected').val(old_val);
    	}
    	//GetTotalPayment();
    }
    function selectNumber(value,obj)
    {
    	var old_val = jQuery('#number_selected').val();	
    	var old_val_1 = old_val;
        old_val = old_val + value;
        jQuery('#number_selected').val(old_val);
    }
    function HideDialog(obj)
    {
      jQuery("#"+obj).fadeOut(300);
      //jQuery("#"+obj).fadeOut(300);
    } 
</script>
