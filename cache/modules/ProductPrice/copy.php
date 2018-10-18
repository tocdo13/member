<script type="text/javascript">
	var product_arr = <?php echo String::array2js($this->map['products']);?>;
</script>
<?php //System::debug(String::array2js($this->map['products']));?>

<span style="display:none">
	<span id="mi_product_group_sample">
		<div id="input_group_#xxxx#" style="text-align:left;display:block;">
			
            <input name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#" value=""/>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][product_id]" style="width:100px;" type="text" id="product_id_#xxxx#" onblur="myAutocomplete('#xxxx#');getProductFromCode('#xxxx#',this.value);" autocomplete="off" <?php if(Url::get('cmd')=='edit') echo 'readonly'; ?>/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][name]" style="width:200px;background-color:#CCC;" type="text" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="-1"/>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][unit]" type="text"  readonly="readonly" id="unit_#xxxx#" style="width:70px; background:#CCCCCC;" tabindex="-1" />
                <input  name="mi_product_group[#xxxx#][unit_id]" type="hidden" id="unit_id_#xxxx#" style="width:75px;" tabindex="-1" />
                <select   name="mi_product_group[#xxxx#][units_id]" id="units_id_#xxxx#" style="display:none;width:74px;" onchange="$('unit_id_#xxxx#').value = this.value;"><?php echo $this->map['units'];?></select>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][category]" type="text"  readonly="readonly" id="category_#xxxx#" style="width:140px; background:#CCCCCC;" tabindex="-1" />
                <input  name="mi_product_group[#xxxx#][category_id]" type="hidden" id="category_id_#xxxx#" style="width:75px;" tabindex="-1" />
                <select   name="mi_product_group[#xxxx#][categorys_id]" id="categorys_id_#xxxx#" style="display:none;width:144px;" onchange="$('category_id_#xxxx#').value = this.value;"><?php echo $this->map['categorys'];?></select>
            </span>
            
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][type]" type="text"  readonly="readonly" id="type_#xxxx#" style="width:70px; background:#CCCCCC;" tabindex="-1" />
                <select   name="mi_product_group[#xxxx#][types_id]" id="types_id_#xxxx#" style="display:none;width:74px;" onchange="$('type_#xxxx#').value = this.value;"><?php echo $this->map['types'];?></select>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][price]" style="width:100px;text-align:right;font-weight:bold;" type="text" id="price_#xxxx#"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][start_date]" style="width:100px;color:#090;" type="text" class="mi_date" id="start_date_#xxxx#"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][end_date]" style="width:100px;color:#F00;" type="text"  class="mi_date" id="end_date_#xxxx#"/>
            </span>
			
            <span class="multi-input" style="margin-top: 4px;">
                <img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','group_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span>
			
            <br clear="all" />
            
        </div>
	</span>
</span>


<div class="product-bill-bound">
    <form name="CopyProductPriceForm" method="post">
    	<input  name="action" id="action" type="hidden"/>
    	
        <!--input dung de luu id(product_price_list) khi an nut xoa tung` multi row-->
        <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    	
        <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    		<tr>
            	<td width="80%" class="form-title"><?php echo $this->map['title'];?></td>
                <td width="20%" align="right" nowrap="nowrap">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="<?php echo Portal::language('Save');?>" class="button-medium-save"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('type'));?>"  class="button-medium-back"><?php echo Portal::language('back');?></a>
                    <?php }?>
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
    			<legend class="title"><?php echo Portal::language('from');?></legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label"><?php echo Portal::language('portal');?>:</td>
    					<td><?php echo $this->map['portal_id'];?></td>
    				</tr>
                    <tr>
    					<td class="label"><?php echo Portal::language('department');?>:</td>
    					<td><?php echo $this->map['department_name'];?></td>
    				</tr>
    			</table>
            </fieldset>	
            
            <fieldset>
    			<legend class="title"><?php echo Portal::language('to');?></legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td>
                            <?php 
                                if(User::can_admin(false,ANY_CATEGORY))
                                {
                            ?>
                            <?php echo Portal::language('select_portal');?>
                            <select  name="to_portal_id" id="to_portal_id" onchange="$('action').value='search_portal';CopyProductPriceForm.submit();"><?php
					if(isset($this->map['to_portal_id_list']))
					{
						foreach($this->map['to_portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('to_portal_id',isset($this->map['to_portal_id'])?$this->map['to_portal_id']:''))
                    echo "<script>$('to_portal_id').value = \"".addslashes(URL::get('to_portal_id',isset($this->map['to_portal_id'])?$this->map['to_portal_id']:''))."\";</script>";
                    ?>
	</select>
                            
                            <?php
                                }
                            ?>
                            <?php echo Portal::language('select_department');?>
                            <select  name="to_department_code" id="to_department_code" onchange="$('action').value='search_department';"><?php echo $this->map['department_list'];?></select>
                            
                            
                            <?php if(Url::get('department_code')){?>
                            
                                <script>$('to_department_code').value = "<?php echo Url::get('department_code');?>";</script>
                            
                            <?php }?>
                            
                            <!--khong co san pham nao-->
                            <?php 
				if((!$this->map['products']))
				{?>
                                <span class="notice" style="font-style:italic;">(<?php echo Portal::language('Have_no_product');?>)</span> 
                                <a target="_blank" style="color:#00F;font-weight:bold;" href="<?php echo Url::build('product',array('cmd'=>'add','warehouse_id'));?>">[ <?php echo Portal::language('Add_product');?> ]</a>
                            
				<?php
				}
				?>
                        </td>
    				</tr>
    			</table>
            </fieldset>	
            
            
    		<fieldset>
    			<legend class="title"><?php echo Portal::language('products');?></legend>
    				<span id="mi_product_group_all_elems" style="text-align:left;">
                        <span class="multi-input-header" style="width:100px;float:left;"><?php echo Portal::language('code');?></span>
                        <span class="multi-input-header" style="width:200px;float:left;"><?php echo Portal::language('name');?></span>
                        <span class="multi-input-header" style="width:70px;"><?php echo Portal::language('unit');?></span>
                        <span class="multi-input-header" style="width:140px;"><?php echo Portal::language('category');?></span>
                        <span class="multi-input-header" style="width:70px;"><?php echo Portal::language('type');?></span>
                        <span class="multi-input-header" style="width:100px;float:left;text-align:right;"><?php echo Portal::language('price');?></span>
                        <span class="multi-input-header" style="width:100px;float:left;"><?php echo Portal::language('start_date');?></span>
                        <span class="multi-input-header" style="width:100px;float:left;"><?php echo Portal::language('end_date');?></span>
                        <br clear="all" />
    				</span>
    				<input type="button" value="<?php echo Portal::language('add_product');?>" onclick="mi_add_new_row('mi_product_group');myAutocomplete(input_count);show_datepicker(input_count)" style="width:auto;"/>
            </fieldset>	
            <br />
    	</div>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>


<script type="text/javascript">
	function getProductFromCode(id,value)
    {
		if($('product_id_'+id))
        {
			if(typeof(product_arr[value])=='object')
            {
				$('name_'+id).value = product_arr[value]['name'];
				$('name_'+id).className = '';
				$('unit_'+id).value = product_arr[value]['unit'];
                $('unit_'+id).className = '';
				$('unit_id_'+id).value = product_arr[value]['unit_id'];
                $('type_'+id).value = product_arr[value]['type'];
                $('category_id_'+id).value = product_arr[value]['category_id'];
                $('category_'+id).value = product_arr[value]['category'];
                jQuery('#units_id_'+id).css('display','none');
                jQuery('#unit_'+id).css('display','block');	
                jQuery('#types_id_'+id).css('display','none');
                jQuery('#type_'+id).css('display','block');
                jQuery('#categorys_id_'+id).css('display','none');
                jQuery('#category_'+id).css('display','block');		
                jQuery('#name_'+id).attr('readonly',true);
                jQuery('#name_'+id).css('background-color','#CCC');
                jQuery('#name_'+id).addClass('readonly');
			}
            else
            {
				if(value)
                {
                    jQuery('#name_'+id).attr('readonly',false);
        			jQuery('#name_'+id).removeClass('readonly');
        			jQuery('#name_'+id).css('background-color','white');
                    jQuery('#units_id_'+id).css('display','block');
                    jQuery('#unit_'+id).css('display','none');	
                    jQuery('#types_id_'+id).css('display','block');
                    jQuery('#type_'+id).css('display','none');
                    jQuery('#categorys_id_'+id).css('display','block');
                    jQuery('#category_'+id).css('display','none');	
					$('name_'+id).value = '';
				}
                else
                {
                    jQuery('#name_'+id).attr('readonly',true);
                    jQuery('#name_'+id).css('background-color','#CCC');
                    jQuery('#name_'+id).addClass('readonly');
                    jQuery('#units_id_'+id).css('display','none');
                    jQuery('#unit_'+id).css('display','block');
                    jQuery('#types_id_'+id).css('display','none');
                    jQuery('#type_'+id).css('display','block');	
                    jQuery('#categorys_id_'+id).css('display','none');
                    jQuery('#category_'+id).css('display','block');		
					$('name_'+id).value = '';
                    $('unit_'+id).value = '';
                    $('type_'+id).value = '';
                    $('category_'+id).value = '';
				}
			}
		}
	}
    
	function myAutocomplete(id)
	{
		jQuery("#product_id_"+id).autocomplete({
                url: 'get_product.php?product=1',
        onItemSelect: function(item) {
			getProductFromCode(id,jQuery("#product_id_"+id).val());
		}
        });
	}
	
    mi_init_rows('mi_product_group',<?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>);
	
    <?php if(isset($_REQUEST['mi_product_group'])){?>
	for(var i=101; i<=input_count; i++)
	{
		show_datepicker(i);
	}
	<?php }?>
    
	function show_datepicker(id)
	{
		jQuery('#start_date_'+id).datepicker();
		jQuery('#end_date_'+id).datepicker();		
	}
</script>
