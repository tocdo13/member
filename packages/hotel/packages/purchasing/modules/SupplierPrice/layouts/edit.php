<?php System::set_page_title(HOTEL_NAME);?>


<div class="customer_type-bound">
<form name="EditSupplierPriceForm" method="post">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
        	<td width="80%" class="form-title">&nbsp;&nbsp;[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<input name="save" type="submit" value="[[.Save.]]" class="button-medium-save" onclick="return check_input();"/>
    			<a href="<?php echo Url::build_current(array('group_id','act'));?>"  class="button-medium-back">[[.back.]]</a>
            </td>
        </tr>
    </table>
    
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
            	<td>
                	<fieldset>
                    <legend class="title">[[.info.]]</legend>
                	<table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="label">[[.code_product.]]<span style="color: red;">(*)</span>:</td>
                            <td><input name="code_product" type="text" id="code_product" onfocus="ProductAutocomplete();"  autocomplete="off" value="<?php echo [[=code_product=]]?>"/></td>
                            <td class="label">[[.name_product.]]:</td>
                            <td ><input name="product_name" type="text" id="product_name" style="width: 250px;" onfocus="ProductAutocomplete2();" value="<?php echo [[=product_name=]]?>"/></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="label">[[.supplier.]]<span style="color: red;">(*)</span>:</td>
                            <td colspan="4">
                            <select name="supplier_name" id="supplier_name" style="width: 150px;">
                            [[|option_suppliers|]]
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">[[.from_date.]]:</td>
                            <td><input name="starting_date" type="text" id="starting_date" value="<?php echo [[=starting_date=]]?>"/></td>
                            <td class="label" align="right">[[.to_date.]]:</td>
                            <td colspan="2"><input name="ending_date" type="text" id="ending_date" value="<?php echo [[=ending_date=]]?>" /></td>
                        </tr>
                        <tr>
                            <td class="label">[[.price_product.]]<span style="color: red;"> (*)</span>:</td>
                            <td colspan="4"><input name="price_product" type="text" id="price_product" style="text-align: right;" value="<?php echo [[=price_product=]]; ?>" onkeyup="this.value = number_format(to_numeric(this.value));" />
                            </td>
                        </tr>
                    </table>
                    </fieldset>
                </td>
            </tr>
        </table>
	</div>
</form>	
</div>

<script type="text/javascript">

function ProductAutocomplete()
{
    jQuery("#code_product").autocomplete({
         url:'get_product_purcharsing.php?product_purchasing=1',
         onItemSelect: function(item) 
         {
            //console.log(item.data);
            document.getElementById("product_name").value= item.data;
            //$('version_id').value = versions[jQuery("#version").val()]['version_id'];
         }
    }); 
}
function ProductAutocomplete2(id)
{
    jQuery("#product_name_"+id).autocomplete({
         url:'get_product_purcharsing.php?product_purchasing=1&name_product=1',
         onItemSelect: function(item) 
         {
            //console.log(item);
            document.getElementById("product_id_"+id).value= item.data;
            //$('version_id').value = versions[jQuery("#version").val()]['version_id'];
         }
    }); 
}
jQuery("#starting_date").datepicker();
jQuery("#ending_date").datepicker();

function check_input()
{
    var code_product  = document.getElementById("code_product");
    var supplier = document.getElementById("supplier_name");
    var price_product = document.getElementById("price_product");
    
    if(code_product.value=="")
    {
        alert("Bạn phải nhập mã sản phẩm");
        code_product.focus();
        code_product.style.backgroundColor = "yellow";
        return false;
    }
    if(supplier.value==0)
    {
        alert("Bạn phải chọn nhà cung cấp");
        supplier.focus();
        supplier.style.backgroundColor = "yellow";
        return false;
    }
    if(price_product.value=="" || price_product.value==0)
    {
        alert("Bạn phải nhập giá cho sản phẩm");
        price_product.focus();
        price_product.style.backgroundColor = "yellow";
        return false;
    }
    return true;
}

</script>