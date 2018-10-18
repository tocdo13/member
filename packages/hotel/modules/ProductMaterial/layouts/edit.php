<script type="text/javascript">
	var materials = <?php echo String::array2js([[=materials=]]);?>;
</script>
<span style="display:none">
	<span id="mi_material_product_sample">
		<div id="input_group_#xxxx#" style="text-align:left;display:block;">
            <span class="multi-input">
                <input name="mi_material_product[#xxxx#][id]" type="hidden" readonly="readonly" id="id_#xxxx#" style="text-align:right;width:50px;background-color:#CCC;"/>
            </span>
            <span class="multi-input">
                <input name="mi_material_product[#xxxx#][material_id]" type="text" id="material_id_#xxxx#" style="width:150px;" onblur="getProductFromCode('#xxxx#',this.value);" tabindex="2"/>
            </span>
            <span class="multi-input">
                <input name="mi_material_product[#xxxx#][material_name]" type="text" id="material_name_#xxxx#" style="width:200px;background-color:#CCC;"  tabindex="2" readonly="readonly"/>
            </span>
            <span class="multi-input">
                <input name="mi_material_product[#xxxx#][quantity]" style="width:100px;" type="text" id="quantity_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44 && event.keyCode!=45)event.returnValue=false;" tabindex="2"/>
            </span>
            <span class="multi-input">
                <input name="mi_material_product[#xxxx#][unit]" style="width:100px;background-color:#CCC;" type="text" id="unit_#xxxx#" readonly="readonly"/>
            </span>            
            <span class="multi-input">
            	<img src="packages/core/skins/default/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_minibar_product','#xxxx#','');event.returnValue=false;" style="cursor:pointer;"/>
            </span>
            <br clear="all"/>
		</div>
	</span>
</span>

<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div class="product-bill-bound">
    <form name="EditProductMaterialForm" method="post" >
        <div class="content">
    		<?php if(Form::$current->is_error()){?>
            <div>
            <br/>
            <?php echo Form::$current->error_messages();?>
            </div>
            <?php }?>
            
        	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        		<tr>
                	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.product_limit.]]</td>
                    <td width="30%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                    	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
        				<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('type'));?>"  class="w3-btn w3-green w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a><?php }?>
                    </td>
                </tr>
            </table>
            
            <fieldset style="width: 475px;" >
                <legend class="title">[[.product.]]</legend>
                <?php if(Url::get('cmd')=='edit'){ ?>
                <input type="button" onclick="if(confirm('Are you sure to delete?')) location='<?php echo Url::build_current(array('cmd'=>'remove_all','product_id','product_name','portal_id'));?>'" value="[[.delete_material.]]"/>
                <?php }?>
                <div style="width:475px ;height: 100px; overflow-x: hidden; overflow-y: auto;">
                    <table id="div_input_id" style="width: 150px; float: left;"></table>
                    <table id="div_input_name" style="width: 300px; float: left;"></table>
                </div>                
            </fieldset>
                        
            <br clear="all" /> 
            <div>
                <input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo Url::get('deleted_ids');?>"/> 
                  
                <span id="mi_material_product_all_elems">
                    <span class="multi-input-header" style="width:150px;">[[.material_id.]]</span>
                    <span class="multi-input-header" style="width:200px;">[[.material_name.]]</span>
                    <span class="multi-input-header" style="width:100px;">[[.norm_quantity.]]</span>
                    <span class="multi-input-header" style="width:100px;">[[.unit.]]</span>
                    <span class="multi-input-header" style="width:20px;"><img src="packages/core/skins/default/images/spacer.gif"/></span>
                    <br clear="all"/>
                </span>
                <input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="[[.add_item.]]" onclick="mi_add_new_row('mi_material_product');$('material_id_'+input_count).focus();myAutocomplete(input_count);"/>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
product_id_list = [[|product_id_list_js|]];
product_name_list = [[|product_name_list_js|]];
for(var j in product_id_list)
{
	jQuery('#div_input_id').append('<tr><td><input name="product_id_list['+j+']" type="text" id="product_id" readonly="readonly" value="'+product_id_list[j]+'" /></td><tr>');
}
for(var j in product_name_list)
{
	jQuery('#div_input_name').append('<tr><td><input name="product_name_list['+j+']" type="text" id="product_id" readonly="readonly" value="'+product_name_list[j]+'" style="width:300px" /></td><tr>');
}
function myAutocomplete(id)
{
	jQuery("#material_id_"+id).autocomplete({
		url: 'get_product.php?material=1',
	 	onItemSelect: function(item) {
            getProductFromCode(id,jQuery("#material_id_"+id).val());
		}            
	});
}


function getProductFromCode(id,value)
{
	if($('material_id_'+id))
    {
		if(typeof(materials[value])=='object')
        {
			$('material_name_'+id).value = materials[value]['name'];
			$('unit_'+id).value = materials[value]['unit'];
        }
        else
        {
            $('material_name_'+id).value =null;
			$('unit_'+id).value = null;
		}
	check_duplicate_material(id,jQuery("#material_id_"+id).val());
    } 
}

//1 product khong the co 2 material giong nhau
function check_duplicate_material(id,value)
{
    var duplicate = 0;
    for(var i = 101; i<= input_count; i++)
    {
        if(jQuery('#material_id_'+i).val())
        {
            if(value==jQuery('#material_id_'+i).val())
                duplicate++;
            if(duplicate>=2)
            {
                alert('[[.product_exist.]]')
                $('material_id_'+id).value =null;
                $('material_name_'+id).value =null;
                $('unit_'+id).value = null;
                return false;
            }    
        }
    }
}

mi_init_rows('mi_material_product',<?php echo isset($_REQUEST['mi_material_product'])?String::array2js($_REQUEST['mi_material_product']):'{}';?>);

</script>