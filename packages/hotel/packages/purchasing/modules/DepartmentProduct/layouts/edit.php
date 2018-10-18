<script type="text/javascript">

var product_department = <?php echo String::array2js([[=product_department=]]);?>;
</script>

<span style="display:none">
    <span id="products_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input"><input  type="checkbox" style="width: 16px;" id="_checked_#xxxx#" tabindex="-1"/></span>
			<span class="multi-input">
            <input  name="products[#xxxx#][index]" type="text" id="index_#xxxx#" style="width:30px;background:#EFEFEF; text-align: center;"/>
            <input  name="products[#xxxx#][id]" type="hidden" id="id_#xxxx#"  tabindex="-1" style="width:30px;background:#EFEFEF;"/>
            </span>
			<span class="multi-input">
					<input  name="products[#xxxx#][code]" style="width:80px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="code_#xxxx#" onfocus="ProductAutocomplete(#xxxx#);"  autocomplete="off"/>
			</span>
            <span class="multi-input">
					<input  name="products[#xxxx#][name]" style="width:250px;font-weight:bold;color:#06F;" class="multi-edit-text-input" type="text" id="name_#xxxx#" onfocus="ProductAutocomplete2(#xxxx#);"/>
			</span>
            
			<span class="multi-input">
					<input  name="products[#xxxx#][unit]" style="width:100px;font-weight:bold;color:#06F;"  type="text" id="unit_#xxxx#" readonly="readonly"/>
			</span>
            
            <span class="multi-input">
					<input  name="products[#xxxx#][supplier]" style="width:250px;font-weight:bold;color:#06F;"  type="text" id="supplier_#xxxx#" readonly="readonly"/>
			</span>
            <span class="multi-input">
					<input  name="products[#xxxx#][department]" style="width:150px;font-weight:bold;color:#06F;"  type="text" id="department_#xxxx#" readonly="readonly"/>
			</span>
            <span class="multi-input">
					<input  name="products[#xxxx#][price]" style="width:100px;font-weight:bold;color:#06F;text-align: right;"  type="text" class="multi-edit-text-input" id="price_#xxxx#" onkeyup="this.value = number_format(to_numeric(this.value));"/>
			</span>
            
			<!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:70px;text-align:center">
				<img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.png" onclick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'products','#xxxx#',''); }" style="cursor:pointer;"/>
			</span>
			<!--/IF:delete-->
		</div><br clear="all" />
	</span>	
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="DepartmentProductForm" method="post" >
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr height="40">
		<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.manage_department_product.]]</td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" style="text-align: right;"><input type="button" value="[[.import_from_excel.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
        <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="submit" value="[[.Save.]]" onclick="return check_value();" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0);" onclick="if(ConfirmDelete()) mi_delete_selected_row('products'); DepartmentProductForm.submit();" class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a></td><?php }?>
	</tr>
</table>
<fieldset>
<legend>Danh sách sản phẩm</legend>
<div style="width: 100%;margin-left: 10px; margin-top: 5px; margin-bottom: 5px;">Bộ phận
<select name="department_id" id="department_id" style="width:150px" onchange="search_product();">
[[|department_list|]]
</select>
</div>
<table cellspacing="0" width="100%">
	<tr valign="top"><td></td></tr>
	<tr><td style="padding-bottom:30px">
		<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>"/>
		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
		<table border="0">
		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
		<tr valign="top">
			<td style="">
			<div>
				<span id="products_all_elems">
					<span>
						<span class="multi-input-header" style="width:16px;"><input type="checkbox" value="1" onclick="mi_select_all_row('project',this.checked);">
						</span>
						<span class="multi-input-header" style="width:35px;">[[.stt.]]</span>
                        <span class="multi-input-header" style="width:80px;">[[.code_product.]]</span>
						<span class="multi-input-header" style="width:250px;">[[.product_name_pc.]]</span>
                        
                        <span class="multi-input-header" style="width:100px;">[[.unit.]]</span>
                        <span class="multi-input-header" style="width:250px;">[[.supplier.]]</span>
                        <span class="multi-input-header" style="width:150px;">[[.department.]]</span>
                        <span class="multi-input-header" style="width:100px;">[[.price.]]</span>
                        
						<span class="multi-input-header" style="width:70px;">[[.Delete.]]</span>
					</span>
                    <br clear="all"/>
				</span>
			</div>
            <input name="search" type="hidden" id="search" value="" /> 
			<div><a href="javascript:void(0);" onclick="mi_add_new_row('products');change_readonly();" class="button-medium-add">[[.Add.]]</a></div>
</td></tr></table></td></tr></table>
</fieldset>
</form>

<script type="text/javascript">
<?php 	
if(isset($_REQUEST['products']))
{
    echo 'var products = '.String::array2js($_REQUEST['products']).';';
}
else
{
    echo 'var products = "";';
}
?>
mi_init_rows('products',products);

for(var i=101;i<=input_count;i++)
{
    document.getElementById("unit_" + i).style.backgroundColor="#EFEFEF";
    document.getElementById("supplier_" + i).style.backgroundColor="#EFEFEF";
    document.getElementById("name_" + i).style.backgroundColor="#EFEFEF";
    document.getElementById("department_" + i).style.backgroundColor="#EFEFEF";
}

function Confirm(index){
	var project_name = $('name_'+index).value; 
	return confirm('Bạn muốn xóa sản phẩm: '+project_name+'?');  
}

function ConfirmDelete(){
	return confirm('Bạn muốn xóa những sản phẩm đã chọn?');
}

function ProductAutocomplete(index)
{
    jQuery("#code_" + index).autocomplete({
         url:'get_product_purcharsing.php?department_product=1',
         onItemSelect: function(item) 
         {
            var id = item.data[1];
            document.getElementById("name_" + index).value= product_department[id]['product_name'];
            document.getElementById("unit_" + index).value = product_department[id]['unit'];
            document.getElementById("supplier_" + index).value = product_department[id]['supplier_name'];
            document.getElementById("price_" + index).value = number_format(to_numeric(product_department[id]['price']));
         }
    }); 
}
function ProductAutocomplete2(index)
{
    jQuery("#name_" + index).autocomplete({
         url:'get_product_purcharsing.php?department_product=1&name_product=1',
         onItemSelect: function(item) 
         {
            console.log(item);
            var id = item.data[1];
            document.getElementById("code_" + index).value= product_department[id]['product_id'];
            document.getElementById("unit_" + index).value = product_department[id]['unit'];
            document.getElementById("supplier_" + index).value = product_department[id]['supplier_name'];
            document.getElementById("price_" + index).value = number_format(to_numeric(product_department[id]['price']));
         }
    }); 
}
function change_readonly()
{
    document.getElementById("unit_" + input_count).style.backgroundColor="#EFEFEF";
    document.getElementById("supplier_" + input_count).style.backgroundColor="#EFEFEF";
    document.getElementById("name_" + input_count).style.backgroundColor="#EFEFEF";
    document.getElementById("index_" + input_count).value = input_count - 100;
    document.getElementById("department_" + input_count).style.backgroundColor="#EFEFEF";
}

function search_product()
{
    DepartmentProductForm.search.value = "search";
    DepartmentProductForm.submit();
}

function check_value()
{
    DepartmentProductForm.search.value="";
    return true;
}

function choose_checked(obj,index)
{
    var select_str =document.getElementById("selected_ids").value;
    if(obj.checked)
    {
        if(select_str!="")
             select_str += "," + document.getElementById("id_" + index).value;
        else
            select_str = document.getElementById("id_" + index).value;
            
        console.log(document.getElementById("id_" + index).value);
        document.getElementById("selected_ids").value = select_str;
    }
    else
    {
        //xoa nhung thu can thiet ben trong chuoi nay 
        select_str = select_str.replace(document.getElementById("id_" + index).value + ",","");
        document.getElementById("selected_ids").value = select_str;
    }
}

</script>
