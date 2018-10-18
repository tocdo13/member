<fieldset id="toolbar" style="margin-top:2px">
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
				<td width="60%" align="right" valign="top">[[.split_from_code.]]
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
								&nbsp;[[|tables.name|]] : <select  name="to_table_[[|tables.id|]]" id="to_table" class="to_table" onchange="$('split_table_<?php echo $i;?>').checked=true;update_table_id(this);" style="height:35px;">
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
								<input name="quantity[[[|orders.id|]]]" value="[[|orders.quantity|]]" type="text" id="quantity_[[|orders.id|]]" lang="[[|orders.id|]]" style="width:40px;height:30px;font-size:18px;text-align:center"  onclick="select();if($('order_[[|orders.id|]]').checked){$('order_[[|orders.id|]]').checked=false;}else{ if(this.value!=''){$('order_[[|orders.id|]]').checked=true;}}" maxlength="5" class="input_number" onkeyup="if(this.value=='0' || this.value=='' || Number(this.value)>Number(jQuery('#quantity_before_[[|orders.id|]]').val()))
                                                                                                                                                                                                                                                                                                                                                                                                                                                                           {this.value='';$('order_[[|orders.id|]]').checked=false; jQuery('#order_[[|orders.id|]]').css('display','none');} else{jQuery('#order_[[|orders.id|]]').css('display','');}"/>
								<input name="order[[[|orders.id|]]]" value="[[|orders.id|]]" type="checkbox" id="order_[[|orders.id|]]" class="order" />
                                
                                <?php if([[=orders.quantity_discount=]]>0){ ?>
                                    <label>[[.quantity_discount_1.]]</label>
                                    <input name="quantity_discount[[[|orders.id|]]]" value="[[|orders.quantity_discount|]]" type="text" id="quantity_discount_[[|orders.id|]]" onchange="fun_quantity_discount([[|orders.id|]],this.value);" style="width: 40px; height: 30px;font-size:18px;text-align:center" />
                                    <input name="quantity_discount_before[[[|orders.id|]]]" value="[[|orders.quantity_discount|]]" type="text" id="quantity_discount_before_[[|orders.id|]]" style="width: 40px; height: 30px;font-size:18px;text-align:center; display: none;" />
                                <?php }else{ ?>
                                    <label>[[.quantity_discount_1.]]</label>
                                    <input name="quantity_discount[[[|orders.id|]]]" value="[[|orders.quantity_discount|]]" type="text" id="quantity_discount_[[|orders.id|]]" style="width: 40px; height: 30px;font-size:18px;text-align:center" readonly="" />
                                    <input name="quantity_discount_before[[[|orders.id|]]]" value="[[|orders.quantity_discount|]]" type="text" id="quantity_discount_before_[[|orders.id|]]" style="width: 40px; height: 30px;font-size:18px;text-align:center; display: none;" />
                                <?php } ?>
							</div>
						<!--/LIST:orders-->
					</div>
					<!--/IF:cond-->
				</td>
				<td align="left">&nbsp;</td>
			</tr>				
		</table>
        <div align="center" >
        <input type="button" style="width: 70px; height: 50px;" value="[[.split_table_ok.]]" onclick="if(jQuery('#to_table').val() != 0){}else{ alert('Bạn chưa chọn đến bàn!'); return false;} fun_check();"/>
        <input name="save" type="text" style="display: none;" id="save" />
        <!--<input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.history.back(-1);"/>-->
        <input style="width: 70px; height: 50px;" name="exit" type="reset" value="[[.split_table_cancel.]]" id="exit" onclick="window.close()"/>
        </div>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
        <input name="new_table_id" type="hidden" id="new_table_id">
  </form>
</fieldset>

<script>
    var orders = <?php echo String::array2js([[=orders=]]); ?>;
	jQuery('#from_code').val('<?php if(Url::get('from_code')){echo Url::get('from_code');} else {echo '';}?>');
	function update_table_id(obj){
			jQuery('#new_table_id').val(obj.value);
	}
	function updateBar(){
		jQuery('#acction').val(1);
		SplitForm.submit();
	}
    function fun_check()
    {
        var check=true;
        for(id in orders)
        {
            if(fun_check_quantity_discount(id)==false)
            {
                check=false;
            }
        }
        if(check==true)
        {
            jQuery('#save').val('thuc hien');
            SplitForm.submit();
        }
        else
        {
            alert('số lượng khuyến mãi lớn hơn số lượng món hiện có');
        }
    }
    function fun_check_quantity_discount(id)
    {
        if( (to_numeric(orders[id]['quantity'])!=to_numeric(jQuery("#quantity_"+id).val())) || (to_numeric(orders[id]['quantity_discount'])!=to_numeric(jQuery("#quantity_discount_"+id).val())) )
        {
            var quantity = to_numeric(jQuery("#quantity_"+id).val());
            var quantity_discount = to_numeric(jQuery("#quantity_discount_"+id).val());
            var quantity_old = (to_numeric(orders[id]['quantity'])-to_numeric(jQuery("#quantity_"+id).val()));
            var quantity_discount_old = (to_numeric(orders[id]['quantity_discount'])-to_numeric(jQuery("#quantity_discount_"+id).val()));
            console.log(quantity+"-"+quantity_discount);
            console.log(quantity_old+"-"+quantity_discount_old);
            if(quantity<quantity_discount || quantity_old<quantity_discount_old)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }
    function fun_quantity_discount(id,value)
    {
        if(to_numeric(value)>to_numeric(orders[id]['quantity_discount']))
        {
            alert('số lượng khuyến mãi lớn hơn số lượng km hiện có');
            jQuery("#quantity_discount_"+id).val(to_numeric(orders[id]['quantity_discount']));
        }
    }
</script>
