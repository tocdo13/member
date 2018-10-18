<script type="text/javascript">
	var product_arr = <?php echo String::array2js([[=products=]]);?>;
</script>
<?php //System::debug(String::array2js([[=products=]]));?>

<span style="display:none">
	<span id="mi_product_group_sample">
		<div id="input_group_#xxxx#" style="text-align:left;display:block;">
			
            <input name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#" value=""/>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][product_id]" style="width:100px;text-transform:uppercase;" type="text" id="product_id_#xxxx#" onblur="myAutocomplete('#xxxx#');getProductFromCode('#xxxx#',this.value);" autocomplete="off" <?php if(Url::get('cmd')=='edit') echo 'readonly'; ?> class="input_code"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][name]" style="width:140px;background-color:#CCC;" type="text" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="-1"/>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][unit]" type="text"  readonly="readonly" id="unit_#xxxx#" style="width:74px; background:#CCCCCC;" tabindex="-1" />
                <input  name="mi_product_group[#xxxx#][unit_id]" type="hidden" id="unit_id_#xxxx#" class="unit_product_value" style="width:75px;" tabindex="-1" />
                <select  name="mi_product_group[#xxxx#][units_id]" id="units_id_#xxxx#" style="display:none;width:74px;" class="unit_product_value" onchange="$('unit_id_#xxxx#').value = this.value;">[[|units|]]</select>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][category]" type="text"  readonly="readonly" id="category_#xxxx#" style="width:144px; background:#CCCCCC;" class="category_product" tabindex="-1" />
                <input  name="mi_product_group[#xxxx#][category_id]" type="hidden" id="category_id_#xxxx#" class="category_product_value" style="width:75px;" tabindex="-1" />
                <select  name="mi_product_group[#xxxx#][categorys_id]" id="categorys_id_#xxxx#" style="display:none;width:144px;" class="category_product_value" onchange="$('category_id_#xxxx#').value = this.value;">[[|categorys|]]</select>
            </span>
            
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][type]" type="text"  readonly="readonly" id="type_#xxxx#" style="width:74px; background:#CCCCCC;" class="product_type" tabindex="-1" />
                <select  name="mi_product_group[#xxxx#][types_id]" id="types_id_#xxxx#"  style="display:none;width:74px;" class="product_type_value" onchange="$('type_#xxxx#').value = this.value;">[[|types|]]</select>
            </span>
            
            <!---<span class="multi-input">
                <select  name="mi_product_group[#xxxx#][product_pipeline]" id="product_pipeline_#xxxx#" style="width:104px;" onchange="$('product_pipeline_#xxxx#').value = this.value;">
                    <option value="ALBA" >ALBA</option>
                    <option value="THANH TAN" >THANH TAN</option>
                </select>
            </span>--->
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][price]" style="width:100px;text-align:right;font-weight:bold;" type="text" id="price_#xxxx#"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][use_time]" style="width:100px;text-align:right;font-weight:bold;" type="text" id="use_time_#xxxx#"/>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][start_date]" style="width:100px;color:#090;" type="text" class="mi_date" id="start_date_#xxxx#"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][end_date]" style="width:100px;color:#F00;" type="text"  class="mi_date" id="end_date_#xxxx#"/>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][order_number]" style="width:30px;" type="text"  id="order_number_#xxxx#" class="input_number"/>
            </span>
			
            <span class="multi-input" style="margin-top: 4px;">
                <img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','group_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span>
			
            <br clear="all" />
            
        </div>
	</span>
</span>


<div class="product-bill-bound">
    <form name="EditProductPriceForm" method="post" onsubmit="return checkSubmit();">
    	<input  name="action" id="action" type="hidden"/>
    	
        <!--input dung de luu id(product_price_list) khi an nut xoa tung` multi row-->
        <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    	
        <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    		<tr>
            	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
                <td width="30%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('type'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a>
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
            
            <?php //if(Url::get('department_code') or Url::get('cmd')=='add'){?>
    		<?php if(Url::get('cmd')=='add'){?>
            <fieldset>
    			<legend class="title">[[.department.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td>
                            <?php 
                                if(User::can_admin(false,ANY_CATEGORY))
                                {
                            ?>
                            [[.select_portal.]]
                            <select name="portal_id" id="portal_id" onchange="$('action').value='search_portal';EditProductPriceForm.submit();" style="height: 24px;"></select>
                            <?php
                                }
                            ?>
                            [[.select_department.]]
                            <select  name="department_code" id="department_code" onchange="select_department(value);" style="height: 24px;">[[|department_list|]]</select>
                            
                            
                            <?php if(Url::get('department_code')){?>
                            
                                <script>$('department_code').value = "<?php echo Url::get('department_code');?>";</script>
                            
                            <?php }?>
                            
                            <!--khong co san pham nao-->
                            <!--IF:cond(![[=products=]])-->
                                <span class="notice" style="font-style:italic;">([[.Have_no_product.]])</span> 
                                <a class="w3-btn w3-cyan w3-text-white" target="_blank" style="text-transform: uppercase; margin-top: 5px;" href="<?php echo Url::build('product',array('cmd'=>'add','warehouse_id'));?>">[ [[.Add_product.]] ]</a>
                            <!--/IF:cond-->
                        </td>
    				</tr>
    			</table>
            </fieldset>	
            <b style="color:#FF6633;">Thay đổi cho sản phẩm</b>
            <strong>[[.unit_id.]]</strong><select  style="width:83px; height: 24px;"  onchange="change_unit(this.value);"><option value=""></option>
            					[[|unit_id_options|]]
            </select>
            <strong>[[.category_id.]]</strong>: <select name="categories_id" id="categories_id"  style="width:150px; height: 24px;" onchange="change_type_cate(this.value);"></select>
            <strong>[[.type.]]: </strong><select  style="width:150px; height: 24px;" onchange="change_type(this.value);">
				<option value=""></option>
                <option value="GOODS">[[.goods.]]</option>
                <option value="PRODUCT">[[.product.]]</option>
                <option value="DRINK">[[.drink.]]</option>
                <option value="MATERIAL">[[.material.]]</option>
                <option value="EQUIPMENT">[[.equipment.]]</option>
                <option value="SERVICE">[[.service.]]</option>
                <option value="TOOL">[[.tool.]]</option>
			</select>
            <strong style="color: red;">Note:</strong>Sẽ Update toàn bộ bản ghi ở phía dưới
            <br /> 	
            
            <?php }?>
            
            <?php if(Url::get('cmd')=='edit' and User::can_admin(false,ANY_CATEGORY) ){?>
            <fieldset>
    			<legend class="title">[[.portal.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td>[[|portal_id|]]</td>
    				</tr>
    			</table>
            </fieldset>	
            <?php }?>
            
    		<fieldset>
    			<legend class="title">[[.products.]]</legend>
    				<span id="mi_product_group_all_elems" style="text-align:left;">
                        <span class="multi-input-header" style="width:100px;float:left;">[[.code.]]</span>
                        <span class="multi-input-header" style="width:140px;float:left;">[[.name.]]</span>
                        <span class="multi-input-header" style="width:74px;">[[.unit.]]</span>
                        <span class="multi-input-header" style="width:144px;">[[.category.]]</span>
                        <span class="multi-input-header" style="width:74px;">[[.type.]]</span>
                        <!---<span class="multi-input-header" style="width:100px;">[[.product_pipeline.]]</span>--->
                        <span class="multi-input-header" style="width:100px;float:left;text-align:right;">[[.price.]]</span>
                        <span class="multi-input-header" style="width:100px;float:left;text-align:right;">[[.use_time.]]</span>
                        <span class="multi-input-header" style="width:100px;float:left;">[[.start_date.]]</span>
                        <span class="multi-input-header" style="width:100px;float:left;">[[.end_date.]]</span>
                        <span class="multi-input-header" style="width:30px;float:left;">[[.order_number.]]</span>
                        <br clear="all" />
    				</span>
                    <?php if(Url::get('cmd')=='add'){?>
    				<input class="w3-btn w3-cyan w3-text-white" type="button" value="[[.add_product.]]" onclick="mi_add_new_row('mi_product_group');myAutocomplete(input_count);checkusetime(input_count);show_datepicker(input_count);jQuery('#product_id_'+input_count).ForceCodeOnly();jQuery('#order_number_'+input_count).ForceNumericOnly();" style="text-transform: uppercase; margin-top: 5px;"/>
                    <?php }?>
            </fieldset>	
            <br />
    	</div>
    </form>	
</div>


<script type="text/javascript">
    jQuery(document).ready(function(){
        var department_code = '<?php echo Url::get('department_code');?>';
        console.log(department_code);
        for(var i=101; i<=input_count; i++)
    	{
    		if( department_code != 'SPA')
            {
                jQuery("#use_time_"+i).attr('readonly',true);
                jQuery("#use_time_"+i).css('background','silver');
                jQuery("#use_time_"+i).val('');  
            }
            if(department_code == 'SPA'){
                jQuery("#use_time_"+i).attr('readonly',false);
                jQuery("#use_time_"+i).css('background','white');
            }
        }
    });
    function change_unit(valueunit)
    {
        jQuery('.unit_product_value').val(valueunit);
    }
    function change_type(valuetyepe)
    {
        var value_cate =valuetyepe;
        jQuery('.product_type').val(value_cate);
        jQuery('.product_type_value').val(value_cate);
    }
    function change_type_cate(valuecategory)
    {
        jQuery('.category_product_value').val(valuecategory);
    }
    function select_department(value){
        //console.log(value);
        $('action').value='search_department';
        for(var i=101; i<=input_count; i++)
        	{
        	   //console.log(input_count);
        		if(jQuery("#department_code").val() != 'SPA')
                {
                    jQuery("#use_time_"+i).attr('readonly',true);
                    jQuery("#use_time_"+i).css('background','silver');
                    jQuery("#use_time_"+i).val('');
                    
                }
                if(jQuery("#department_code").val() == 'SPA')
                {
                    jQuery("#use_time_"+i).attr('readonly',false);
                    jQuery("#use_time_"+i).css('background','white');
                }
         	}
        
    }
    function checkusetime(input_count){
        var department_code = jQuery("#department_code").val();
        if(department_code != 'SPA'){  
            for(var i=101; i<=input_count; i++)
           	{
        	    jQuery("#use_time_"+i).attr('readonly',true);
                jQuery("#use_time_"+i).css('background','silver');
                jQuery("#use_time_"+i).val('');
                        
           	}
        }
    }
	function getProductFromCode(id,value)
    {
		if($('product_id_'+id))
        {
			if(typeof(product_arr[value])=='object')
            {
			//	console.log(product_arr[value]);
                $('product_id_'+id).value = product_arr[value]['id'];
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
					//$('name_'+id).value = '';
                    //$('name_'+id).className = 'notice';
					//$('name_'+id).value = '[[.products_does_not_exist.]]';
                    //$('unit_'+id).className = 'notice';
					//$('unit_'+id).value = '[[.none.]]';
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
                matchContains: true,
                matchSubset: false,
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
    
    function checkSubmit(){
        var check = true;
        /** bo check danh muc
        if(jQuery('#categories_id').val()=='1' || jQuery('#categories_id').val()=='--')
        {
            check = false;
        }
        else
        {
            
            for(var i=101; i<=input_count; i++)
        	{
        		if(jQuery('#category_id_'+i).val()=='1')
                check = false;
         	}
        }
        
        if(check == false)
        {
            alert('[[.ban_phai_chon_danh_muc_hang_hoa.]]');
            return false;
        }**/
        var i = 0;
        var arr = [];
        jQuery("div[id^=input_group_]").each(function(){
            if(i==0){
                i++;
            }
            else{
                var product_id = jQuery(this).find("span input[id^=product_id_]").val();
                var start_date = jQuery(this).find("span input[id^=start_date_]").val();
                var end_date = jQuery(this).find("span input[id^=end_date_]").val();
                var date = start_date+"-"+end_date;
                if(!arr[product_id]){
                   arr[product_id] = {};
                   arr[product_id][i] =  {};   
                   arr[product_id][i] = date;
                }
                else{
                   arr[product_id][i] =  date;   
                }
                i++;             
            }
        });
        var boolen = true;
        for(var key in arr){
            var count = 0;
            for(var k in arr[key]){
                var date = arr[key][k].split('-');
                var start_time = date[0];
                var end_time = date[1];
                
                if(!start_time){
                    start_time = "???";
                    start_timestamp = 0;
                }
                else{
                    var start_timestamp = new Date(convertDate(start_time));
                    var start_timestamp = start_timestamp.getTime(); 
                }
                
                if(!end_time){
                    end_time = "???";
                    end_timestamp = 99999999999000;
                }
                else{
                    var end_timestamp = new Date(convertDate(end_time));
                    var end_timestamp = end_timestamp.getTime();
                }
                if(start_timestamp>=end_timestamp){
                    alert("Thoi gian bat phai nho hon thoi gian ket thuc ( Ma : " +key +" | "+start_time+" - "+end_time+")");
                    boolen = false;
                    return false;
                }
                else{
                    count++;
                    arr['from_'+count] = start_timestamp/1000;
                    arr['to_'+count] = end_timestamp/1000;
                    arr['from_str_'+count] = start_time;
                    arr['to_str_'+count] = end_time;
                }
                for(j=1; j < count; j++){  
                if((arr['to_'+count]>=arr['from_'+j] && arr['to_'+j]>=arr['from_'+count])){
                            alert("Xung dot khoang thoi gian cua ma "+key+" ("+arr['from_str_'+j]+" - "+arr['to_str_'+j]+") --> ("+arr['from_str_'+count]+" - "+arr['to_str_'+count]+")");
                            boolen = false;
                            return false;
                    }
                }
                
            }
        }
        if(!boolen){
            return false;
        }
    }
    function convertDate(value){
        var date_arr = value.split("/");
        return date_arr[1]+"/"+date_arr[0]+"/"+date_arr[2];
    }
</script>
