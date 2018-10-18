<style>
    select 
    {
        width: 150px;
        height: 20px;
    }
</style>
<form method="POST" name="DebtDeductionForm">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">Giảm trừ công nợ</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="button" value="[[.save_close.]]" onclick="check_validate();" class="button-medium-save"  /><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="top">
            <td>
                <fieldset>
                    <legend>[[.general_info.]]</legend>
                        <table border="0" cellspacing="0" cellpadding="2">
                            <tr>
                                <td>[[.date.]]</td>
                                <td><input name="date_in"  type="text" id="date_in" readonly="readonly" <?php if(Url::get('cmd')=='edit') { ?>value="<?php echo Date_Time::convert_orc_date_to_date([[=date_in=]],'/') ?>" <?php }else { ?> value="<?php echo date('d/m/Y') ?>" <?php } ?> style="background: antiquewhite;" /></td>
                                
                            </tr>
                            
                            <tr>
                                <td>[[.customer.]]</td>
                                <td>
                                    <input  name="customer_name" type="text" id="customer_name" <?php if(Url::get('cmd')=='edit') { ?>value="[[|customer_name|]]" <?php } ?>  onfocus="customerAutocomplete();" /> 
                                    <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> 
                                        <img src="skins/default/images/cmd_Tim.gif" />
                                    </a>
                                    <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:pointer;"/>  
                                </td>
                                <input  name="customer_id" type="hidden" id="customer_id" <?php if(Url::get('cmd')=='edit'){ ?> value="[[|customer_id|]]" <?php } ?> />
                                
                      
                                
                            </tr>
                            
                            <tr>
                                <td>[[.amount.]]</td>
                                <td><input  name="price" type="text" id="price" <?php if(Url::get('cmd')=='edit') { ?>value="<?php echo System::display_number([[=price=]]) ?>" <?php } ?> oninput="change_price()"   onKeyUp="changePriceFunc();" /></td>
                                
                            </tr>
                            <tr>
                                <td>[[.money_type.]]</td>
                                <td>
                                    <select style="" name="currency_id" id="currency_id">
                                        <option value="VND">VND</option>
                                        <option <?php if(Url::get('cmd')=='edit' and [[=currency_id=]]=='USD'){?> selected="selected" <?php } ?> value="USD">USD</option>
                                    </select>
                                </td>
                                
                            </tr>
                            <tr>
                                <td>[[.description.]]</td>
                                <td colspan="4">
                                    <textarea    name="description" id="description" style="width: 500px; height: 100px;"><?php if(Url::get('cmd')=='edit') { echo [[=description=]]; } ?></textarea>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>[[.recode.]]</td>
                                <td>
                                    <input  name="recode" type="text" id="recode" style="width: 50px;" <?php if(Url::get('cmd')=='edit') {?> value="[[|recode|]]" <?php } ?> />
                                    <label style="margin-left: 20px ;">[[.number_folio.]]:</label>
                                    <input  name="number_folio" type="text" id="number_folio" <?php if(Url::get('cmd')=='edit') {?> value="[[|folio_number|]]" <?php } ?>  style="width: 50px;" />
                                </td>
                                
                                
                            </tr>
                        </table>    
                 </fieldset>
            </td>
        </tr>     
    </table>
</form>
<script>
    jQuery("#date_in").datepicker();
    function change_price(){
        jQuery('#price').ForceNumericOnly().FormatNumber();
    }
    

    function customerAutocomplete()
    {
    	jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item) {
             document.getElementById('customer_id').value = item.data[0];
            }
        }) 
    }
    function changePriceFunc()
    {   
        for(var i=101;i<=input_count;i++){
            count_price(i,true);
        }
    }
    
    function check_validate()
    {
        if(jQuery("#date_in").val()=='')
        {
            alert('Ngày tháng không được trống');
            return false;
        }
        if(jQuery("#customer_name").val()=='')
        {
            alert('Bạn chưa nhập khách hàng');
            return false;
        }
        if(jQuery("#price").val()=='')
        {
            alert('Bạn chưa nhập số tiền giảm công nợ');
            return false;
        }
        DebtDeductionForm.submit();
    }

</script>
