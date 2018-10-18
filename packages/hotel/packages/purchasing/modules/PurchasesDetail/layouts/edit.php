<style>
    table tr td{
        padding: 5px;
    }
</style>
<span style="display:none">
	<span id="mi_product_sample">
		<div id="input_group_#xxxx#" style="text-align:center;">
			<input  name="mi_product[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <span class="multi-input">
                <input name="mi_product[#xxxx#][check_product_code]" type="checkbox" id="check_product_code_#xxxx#" value="check_product_code" style="display: none;" />
                <input  name="mi_product[#xxxx#][product_code]" style="width:60px; text-transform: uppercase;" type="text" id="product_code_#xxxx#" list="list_product_code_#xxxx#" onchange="select_product(#xxxx#);" autocomplete="OFF"/>
                <datalist id="list_product_code_#xxxx#">
                [[|all_product|]]
                </datalist>
            </span>
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][product_name]" style="width:150px;" type="text" id="product_name_#xxxx#" />
            </span>
            <span class="multi-input">
                <input name="mi_product[#xxxx#][unit_id]" id="unit_id_#xxxx#" style="width:60px; display: none;" type="text"/>
                <input name="mi_product[#xxxx#][unit_name]" id="unit_name_#xxxx#" style="width:60px;" list="list_unit_#xxxx#" onchange="select_unit(#xxxx#);" type="text"/>
                <datalist id="list_unit_#xxxx#">
                [[|all_unit|]]
                </datalist>
            </span>
            <span class="multi-input">
                <select  name="mi_product[#xxxx#][product_category_id]" id="product_category_id_#xxxx#" style="width:184px;">[[|categorys_id|]]</select>
            </span>
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][quantity]" style="width:60px;" type="text" id="quantity_#xxxx#" class="input_number"/>
            </span>	
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][note]" style="width:120px;" type="text" id="note_#xxxx#"/>
            </span>
            <span class="multi-input">
				<span style="width:16px; padding: 0px 12px; text-align: center;">
                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
				<img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\file_delete.png" id="delete_#xxxx#" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','group_');check_delete(#xxxx#);" style="cursor:pointer; margin: 0px auto; text-align: center; width: 16px; height: auto;"/>
                <?php }?>
                </span>
            </span>		
            <br/>
            <br style="clear: all;" />
        </div>
	</span>
</span>
<form name="AddPurchasesProposedForm" method="post">
    <fieldset style="width: 720px; margin: 20px auto; box-shadow: 0px 0px 5px #555555; border: 3px solid #2ab2be;">
        <legend style="width: 40px; background: #ffffff; height: 40px; overflow: hidden; text-align: center; border-radius: 50%; border: 3px solid #2ab2be; box-shadow: 0px 0px 5px #09435b;"><img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\iconarchive.png" style="width: 40px; height: auto;" /></legend>
        <table cellpadding="5" cellspacing="5" style="width: 98%; margin: 1% auto;">
            <tr>
                <td colspan="4" style="text-align: center;"><h3 style="font-size: 20px; text-transform: uppercase;">[[.adjusted_purchases_proposed.]]</h3></td>
            </tr>
            <tr>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.date.]]:</td>
                <td><input name="create_date" type="text" id="create_date" readonly="" style="border: 1px solid #09435b;" /></td>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.department.]]:</td>
                <td><input name="department" type="text" id="department" readonly="" style="border: 1px solid #09435b;" /></td>
            </tr>
            <tr>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.creater.]]:</td>
                <td><input name="creater" type="text" id="creater" readonly="" style="border: 1px solid #09435b;" /></td>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.confirm_user.]]:</td>
                <td><input name="confirm_user" type="text" id="confirm_user" readonly="" style="border: 1px solid #09435b;" /></td>
            </tr>
            <tr>
                <td colspan="4" style="font-size: 13px; font-weight: bold;">[[.content_pusrchases.]]:</td>
            </tr>
            <tr>
                <td colspan="4">
                    <textarea name="description" id="description" style="width: 700px; height: 50px; border: 1px solid #09435b;"></textarea>
                    <input name="list_delete" type="text" id="list_delete" style="display: none;" />
                </td>
            </tr>
        </table>
        <fieldset style="margin: 20px auto;">
            <span id="mi_product_all_elems" style="text-align:center;">
                <span>
    				<span class="multi-input-header" style="width:60px; background: #facc39;">[[.code.]]</span>
    				<span class="multi-input-header" style="width:150px; background: #facc39;">[[.name.]]</span>
                    <span class="multi-input-header" style="width:60px; background: #facc39;">[[.unit_at.]]</span>
                    <span class="multi-input-header" style="width:180px; background: #facc39;">[[.category.]]</span>
                    <span class="multi-input-header" style="width:60px; background: #facc39;">[[.quantity.]]</span>
                    <span class="multi-input-header" style="width:120px; background: #facc39;">[[.note.]]</span>
                    <span class="multi-input-header" style="width:40px; background: #facc39;">[[.delete.]]</span> 
    			</span>
           	<br style="clear: all;"/>
    		</span>
    		<input id="add_new" type="button" value="[[.add_product.]]" onclick="mi_add_new_row('mi_product');" style=" margin: 10px; padding: 10px; border: none; background: #19937c; color: #000000; font-weight: bold;" />
		</fieldset>	
        <table cellpadding="5" cellspacing="5" style="width: 98%; margin: 1% auto;">
            <tr>
                <td colspan="4" style="text-align: center;">
                    <?php if(User::can_edit(false,ANY_CATEGORY)){ ?>
                    <input type="submit" name="save" id="save" style=" margin: 10px; padding: 15px; border: none; background: #facc39; color: #000000; font-weight: bold;" value="[[.save.]]" onclick="return fun_text();" />
                    <?php } ?>
                    <input type="submit" name="back" id="back" style=" margin: 10px; padding: 15px; border: none; background: #f04d4e; color: #000000; font-weight: bold;" value="[[.back.]]" onclick="return fun_text();" />
                </td>
            </tr>
        </table>
    </fieldset>
</form>
<script>
    mi_init_rows('mi_product',<?php echo isset($_REQUEST['mi_product'])?String::array2js($_REQUEST['mi_product']):'{}';?>);
    for(var i=101;i<=input_count;i++)
    {
        document.getElementById("check_product_code_"+i).checked=true;
    }
    var mi_arr = <?php echo isset($_REQUEST['mi_product'])?String::array2js($_REQUEST['mi_product']):'{}';?>;
    console.log(mi_arr);
    for(var j in mi_arr)
    {
        for(var i=101;i<=input_count;i++)
        {
            if(to_numeric(jQuery("#id_"+i).val())==to_numeric(mi_arr[j]['id']))
            {
                if(mi_arr[j]['purchases_group_invoice_id']!='')
                {
                    jQuery("#delete_"+i).css('display','none');
                    jQuery("#product_code_"+i).attr('readonly','readonly');
                    jQuery("#product_name_"+i).attr('readonly','readonly');
                    jQuery("#unit_name_"+i).attr('readonly','readonly');
                    jQuery("#product_category_id_"+i).attr('readonly','readonly');
                    jQuery("#quantity_"+i).attr('readonly','readonly');
                }
            }
        }
    }
    var all_product_list = [[|all_product_list|]];
    var all_unit_list = [[|all_unit_list|]];
    function check_delete(id)
    {
        if(jQuery("#list_delete").val()=='')
        {
            jQuery("#list_delete").val(jQuery("#id_"+id).val());
        }
        else
        {
            jQuery("#list_delete").val("_"+jQuery("#id_"+id).val());
        }
    }
    function select_product(id)
    {
        var product = jQuery("#product_code_"+id).val().split("---");
        var check = false;
        for(var i in all_product_list)
        {
            jQuery("#product_code_"+id).val(product[0]);
            if(product[0]==all_product_list[i]['id'])
            {
                check=true;
                jQuery("#product_name_"+id).val(all_product_list[i]['name']);
                jQuery("#product_category_id_"+id).val(all_product_list[i]['product_category_id']);
                jQuery("#unit_id_"+id).val(all_product_list[i]['unit_id']);
                jQuery("#unit_name_"+id).val(all_product_list[i]['unit_name']);
                document.getElementById("check_product_code_"+id).checked=true;
            }
        }
        if(check==false)
        {
            jQuery("#product_name_"+id).val('');
            jQuery("#product_category_id_"+id).val('');
            jQuery("#unit_id_"+id).val('');
            jQuery("#unit_name_"+id).val('');
            document.getElementById("check_product_code_"+id).checked=false;
        }
        
    }
    function select_unit(id)
    {
        var unit = jQuery("#unit_name_"+id).val();
        var check = false;
        for(var j in all_unit_list)
        {
            jQuery("#unit_name_"+id).val(unit);
            if(unit==all_unit_list[j]['name'])
            {
                check=true;
                jQuery("#unit_id_"+id).val(all_unit_list[j]['id']);
            }
        }
        if(check==false)
        {
            jQuery("#unit_id_"+id).val('');
        }
        
    }
    function check_price(id)
    {
        var total = 0;
        for(var i=101;i<=input_count;i++)
        {
            sum = to_numeric(jQuery("#quantity_"+i).val())*to_numeric(jQuery("#price_"+i).val());
            jQuery("#total_"+i).val(sum);
            total += sum;
        }
        jQuery("#total_amount").val(total);
    }
    function fun_text()
    {
        if(jQuery("#description").val()=='')
        {
            alert('B?n chua nh?p n?i dung d? xu?t!');
            return false;
        }
        else
        {
            return true;
        }
    }
</script>