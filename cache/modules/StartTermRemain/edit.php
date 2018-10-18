<script type="text/javascript">
    var product_arr = <?php echo String::array2js($this->map['products']);?>;
</script>

<span style="display:none">
    <span id="mi_product_group_sample">
        <div id="input_group_#xxxx#" style="text-align:left;display:block;">
            <input  name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][product_id]" style="width:100px; height: 24px; text-transform: uppercase;" type="text" id="product_id_#xxxx#" tabindex="1_#xxxx#" onblur="myAutocomplete('#xxxx#');getProductFromCode('#xxxx#',this.value);" autocomplete="OFF"/>
            </span>
            <span class="multi-input">                
                <input  name="mi_product_group[#xxxx#][name]" style="width:200px; height: 24px;background-color:#CCC;" type="text" onclick="UpdateProduct('#xxxx#');" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="2_#xxxx#" />
            </span>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][quantity]" style="width:100px;height: 24px;text-align: right;" type="text" id="quantity_#xxxx#"  class="input_number format_number"/>
            </span>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][total_start_term_price]" style="width:150px;height: 24px;text-align: right;" type="text" id="total_start_term_price_#xxxx#" class="input_number format_number"/>
            </span>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][unit]" style="width:52px;height: 24px;background:#CCCCCC;" type="text" id="unit_#xxxx#" onclick="UpdateProduct('#xxxx#');" readonly="readonly" tabindex="-1"/>
                <select   name="mi_product_group[#xxxx#][units_id]" id="units_id_#xxxx#" style="display:none;width:60px;" onchange="$('unit_id_#xxxx#').value = this.value;"  tabindex="3_#xxxx#"><?php echo $this->map['units_id'];?></select>
            </span>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][type]" style="width:82px;height: 24px;background-color:#CCC;" type="text" id="type_#xxxx#" onclick="UpdateProduct('#xxxx#');"/>
                <select   name="mi_product_group[#xxxx#][types_id]" id="types_id_#xxxx#" style="display:none;width:83px;" onchange="$('type_#xxxx#').value = this.value;"  tabindex="5_#xxxx#"><?php echo $this->map['types_id'];?></select>
            </span>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][category]" type="text"  readonly="readonly" id="category_#xxxx#" style="width:144px;height: 24px; background:#CCCCCC;"  onclick="UpdateProduct('#xxxx#');"/>
                <input  name="mi_product_group[#xxxx#][category_id]" type="hidden" id="category_id_#xxxx#"  />
                <select   name="mi_product_group[#xxxx#][categorys_id]" id="categorys_id_#xxxx#" style="display:none;width:144px;" onchange="$('category_id_#xxxx#').value = this.value;"  tabindex="4_#xxxx#"><?php echo $this->map['categorys_id'];?></select>
            </span>
            <span class="multi-input">
                <img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','group_');if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span>
        <br clear="all"/>
        </div>
    </span>
</span>

<div class="product-bill-bound">
<form name="EditStartTermRemainForm" method="post">
    <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    <table cellpadding="15" cellspacing="0" width="70%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="40%" align="right" nowrap="nowrap">
            <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="import_from_excel" type="button" value="<?php echo Portal::language('import_from_excel');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" class="w3-btn w3-lime" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('type'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('back');?></a><?php }?>
            </td>
        </tr>
    </table>
    <div class="content">
        
        <?php if(Form::$current->is_error()){?>
        <div>
            <br/>
            <?php echo Form::$current->error_messages();?>
        </div>
        <?php }?>
        
        <fieldset>
            <legend class="title"><?php echo Portal::language('warehouse');?></legend>
            <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="label">&nbsp;</td>
                    <td><select  name="warehouse_id" id="warehouse_id" style="height: 24px;"><?php
					if(isset($this->map['warehouse_id_list']))
					{
						foreach($this->map['warehouse_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))
                    echo "<script>$('warehouse_id').value = \"".addslashes(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))."\";</script>";
                    ?>
	</select></td>
                    <!--/IF:cond-->
                </tr>
            </table>
        </fieldset>    
        
        <fieldset>
            <legend class="title"><?php echo Portal::language('products');?></legend>
            <span id="mi_product_group_all_elems" style="text-align:center; text-transform: uppercase;">
                <span class="multi-input-header" style="width:100px; height: 24px; padding-top: 3px;"><?php echo Portal::language('code');?></span>
                <span class="multi-input-header" style="width:200px; height: 24px; padding-top: 3px;"><?php echo Portal::language('name');?></span>
                <span class="multi-input-header" style="width:100px; height: 24px; padding-top: 3px;"><?php echo Portal::language('number');?></span>
                <span class="multi-input-header" style="width:150px; height: 24px; padding-top: 3px;"><?php echo Portal::language('total_start_term_price');?></span>
                <span class="multi-input-header" style="width:52px; height: 24px; padding-top: 3px;"><?php echo Portal::language('unit');?></span>
                <span class="multi-input-header" style="width:82px; height: 24px; padding-top: 3px;"><?php echo Portal::language('type');?></span>
                <span class="multi-input-header" style="width:144px; height: 24px; padding-top: 3px;"><?php echo Portal::language('category');?></span>
                <br clear="all"/>
            </span>
            <input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="<?php echo Portal::language('add_product');?>" onclick="mi_add_new_row('mi_product_group');myAutocomplete(input_count);jQuery('#total_start_term_price_'+input_count).FormatNumber();jQuery('#total_start_term_price_'+input_count).ForceNumericOnly();jQuery('#quantity_'+input_count).FormatNumber();jQuery('#quantity_'+input_count).ForceNumericOnly();" style="width:auto;"/>
        </fieldset>    
        
        <br />
        
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			    
</div>
<script type="text/javascript">  
    function getProductFromCode(id,product_id)
    {
        //neu co trong product
        if(product_id && typeof(product_arr[product_id])=='object')
        {
            jQuery('#units_id_'+id).css('display','none');    
            jQuery('#unit_'+id).css('display','block');
            jQuery('#categorys_id_'+id).css('display','none');    
            jQuery('#category_'+id).css('display','block');        
            jQuery('#name_'+id).attr('readonly',true);
            jQuery('#name_'+id).addClass('readonly');
            jQuery('#types_id_'+id).css('display','none');
            jQuery('#type_'+id).css('display','block');
            jQuery('#type_'+id).attr('readonly',true);
            jQuery('#type_'+id).css('background-color','#CCC');
            jQuery('#name_'+id).css('background-color','#CCC');
            $('name_'+id).value = product_arr[product_id]['name'];
            $('unit_'+id).value = product_arr[product_id]['unit'];
            $('units_id_'+id).value = product_arr[product_id]['units_id'];
            $('category_id_'+id).value = product_arr[product_id]['category_id'];
            $('category_'+id).value = product_arr[product_id]['category'];
            $('type_'+id).value = product_arr[product_id]['type'];
            
            $('name_'+id).className = '';
            check_duplicate_product(id,jQuery('#product_id_'+id).val());
            
        }
        else//neu khong co
        {
            UpdateProduct(id);
        }
    }
    
    //1 phieu khong the co 2 sp giong nhau
    function check_duplicate_product(id,value)
    {
        var duplicate = 0;
        for(var i = 101; i<= input_count; i++)
        {
            if(jQuery('#product_id_'+i).val())
            {
                if(value==jQuery('#product_id_'+i).val())
                    duplicate++;
                if(duplicate>=2)
                {
                    alert('<?php echo Portal::language('product_exist');?>')
                    mi_delete_row($('input_group_'+id),'mi_product_group',id,'group_');
                    return false;
                }    
            }
        }
    }
    
    
    function UpdateProduct(id)
    {
        //$('name_'+id).value = '';
        $('unit_'+id).value = '';
        //khi submit form ma bi loi thi se lay cac gia tri cu~
        $('unit_'+id).value = jQuery('#unit_'+id).val()?jQuery('#unit_'+id).val():'';
        $('category_'+id).value = '';
        $('category_id_'+id).value = jQuery('#category_id_'+id).val()?jQuery('#category_id_'+id).val():'';
        $('type_'+id).value = jQuery('#type_'+id).val()?jQuery('#type_'+id).val():'';
        if($('product_id_'+id).value != '')
        {
            jQuery('#name_'+id).attr('readonly',false);
            jQuery('#name_'+id).removeClass('readonly');
            jQuery('#name_'+id).css('background-color','#FFF');
            jQuery('#name_'+id).focus();
            jQuery('#units_id_'+id).css('display','block');    
            jQuery('#unit_'+id).css('display','none');
            jQuery('#categorys_id_'+id).css('display','block');    
            jQuery('#category_'+id).css('display','none');        
            jQuery('#types_id_'+id).css('display','block');
            jQuery('#type_'+id).css('display','none');
        }
        else
        {
            jQuery('#name_'+id).attr('readonly',true);
            jQuery('#name_'+id).addClass('readonly');
            jQuery('#name_'+id).css('background-color','#CCC');
            jQuery('#name_'+id).focus();
            jQuery('#units_id_'+id).css('display','none');    
            jQuery('#unit_'+id).css('display','block');
            jQuery('#categorys_id_'+id).css('display','none');    
            jQuery('#category_'+id).css('display','block');        
            jQuery('#types_id_'+id).css('display','none');
            jQuery('#type_'+id).css('display','block');  
        }
    
    }
    
    function myAutocomplete(id)
    {
        jQuery("#product_id_"+id).autocomplete({
            url: 'get_product.php?wh_start_term_remain=1',
            onItemSelect: function(item) 
            {
                getProductFromCode(id,jQuery("#product_id_"+id).val());
            }
            
            
        })
    }
    mi_init_rows('mi_product_group',<?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>);
</script>
