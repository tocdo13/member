<style>
    .simple-layout-middle{
		width:100%;	
	}
      
    label.lbl_style {
        line-height: 20px;
        padding: 5px;
        background: #beecff;
        color: #171717;
        margin: 0px 0px 0px 5px;
        font-weight: bold;
    }
    .ipt_style {
        height: 25px;
        border: 1px solid #DDDDDD;
        padding: 0px 5px;
    }
    .multiselect {
  width: 200px;
  float: left;
}

.selectBox {
  position: relative;
}

.selectBox select {
  width: 100%;
  font-weight: bold;
}

.overSelect {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

#checkboxes {
  display: none;
  border: 1px #dadada solid;
}

#checkboxes label {
  display: block;
}

#checkboxes label:hover {
  background-color: #1e90ff;
}
.scroll_ctm{
    height: 200px;       
    overflow-y: auto;    
    overflow-x: hidden;
}
#myDIV {
    overflow: auto;
}
.scoll_over{
    height: 20px;
    width: 60px;
    padding: 10px;
    overflow: auto;
}
</style>
<form name="AddPcOrderForm" method="POST">
    <div style="width: 100%; height: auto; background: #EAE9E9;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <img src="packages/hotel/packages/purchasing/skins/default/images/purchases_proposed/iconarchive.png" style="width: 50px; height: auto; float: left;" />
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;"><?php echo Portal::language('create_order');?></h3>
                </td>
                <td style="text-align: right;">
                    <input value="" name="act" id="act" type="text" style="display: none;" />
                    <?php if(User::can_add(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY)){ ?>
                    <input class="w3-btn w3-cyan w3-text-white" name="save" id="save" type="button" onclick="fun_checksubmit('creater');" value="<?php echo Portal::language('create_order');?>" style="text-transform: uppercase;" />
                    <?php } ?>
                </td>
            </tr>
            <tr style="border-bottom: 1px dashed #EEEEEE;">
                <td colspan="2">
                    <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <?php if(isset($this->map['no_data'])){ ?>
                        <div style="margin: 10px auto; width: 200px; height: 70px; line-height: 70px; text-align: center; border: 2px solid #00B2F9;">
                            <b><?php echo Portal::language('no_request');?></b>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        
        <?php $i=101; ?>
        <?php if(!isset($this->map['no_data'])){ ?>
        <table style="width: 100%; margin: 1px auto;" cellspacing="2" cellpadding="2"  >
            <tr>
                <td style="width: 70px;">
                    <label ><?php echo Portal::language('create_time');?></label> :
                </td>
                <td style="width: 130px;"> 
                    <input id="create_time" name="create_time" type="text" class="ipt_style" style="width: 100%; height: 24px;" value="<?php echo date('d/m/Y'); ?>" />
                </td>
                <td style="width: 80px;">
                    <label ><?php echo Portal::language('order_code');?></label> : 
                </td>
                <td style="width: 130px;"> 
                    <input id="order_code" name="order_code" type="text" class="ipt_style" style="width: 100%;height: 24px; background: #DDDDDD;" readonly="" />
                </td>
                <td style="width: 100px;">
                    <label ><?php echo Portal::language('order_name');?></label> : 
                </td>
                <td style="width: 150px;"> 
                    <input id="order_name" name="order_name" type="text" class="ipt_style" style="width: 100%;height: 24px;" />
                </td>
                <td style="vertical-align: top; width: 100px;">                
                    <label ><?php echo Portal::language('supplier');?></label>
                </td>
                <td style="width: 300px;">
                    <input  name="pc_supplier_name" id="pc_supplier_name" onfocus="Autocomplete();" autocomplete="off" style="width:100%;height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('pc_supplier_name'));?>">
                    <input  name="pc_supplier_id" id="pc_supplier_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('pc_supplier_id'));?>">
                </td>
            </tr>
            <tr style="padding-bottom: 5px;">
                <td style="width: 70px;">
                    <label ><?php echo Portal::language('receiver');?></label> :
                </td>
                <td style="width: 130px;"> 
                    <input  name="receiver" id="receiver" class="ipt_style" style="width: 100%;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('receiver'));?>">
                </td>
                <td style="width: 80px;">
                    <label ><?php echo Portal::language('place_of_receipt');?></label> : 
                </td>
                <td style="width: 130px;">
                    <input  name="place_of_receipt" id="place_of_receipt" class="ipt_style" style="width: 100%;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('place_of_receipt'));?>">
                </td>
                <td style="width: 100px;">
                    <label ><?php echo Portal::language('tel_of_receipt');?></label> : 
                </td>
                <td style="width: 150px;">
                    <input  name="tel_of_receipt" id="tel_of_receipt" class="ipt_style" style="width: 100%;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('tel_of_receipt'));?>">
                </td>
                <td style=" width: 100px;" >
                    <label ><?php echo Portal::language('description');?></label>
                </td>
                <td style="width: 300px;">
                    <textarea id="description" name="description" style=" width: 100%;height: 24px;"></textarea>
                </td>
            </tr>                       
            <tr>
                <td colspan="8" style="border-top: 1px solid lightgray; ">
                    <table style="width: 70%; background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
                        <tr style="text-transform: uppercase;">
                            <td style="text-align: left; width: 400;">
                                <b><?php echo Portal::language('supplier_name');?>:</b> <label id="supplier_name"></label>                                                          
                            </td >
                            <td style="text-align: left; width: 200;">
                                <b><?php echo Portal::language('supplier_tax_code');?>:</b> <label id="supplier_tax_code"></label>
                            </td>                                
                            <td style="text-align: left; width: 200;">
                                <b><?php echo Portal::language('total_order');?>:</b> <label id="total_order"></label>
                            </td>                            
                        </tr>
                        <tr style="text-transform: uppercase;">
                            <td style="text-align: left; width: 400;">                                
                                <b><?php echo Portal::language('supplier_address');?>:</b> <label id="supplier_address"></label>                               
                            </td >
                            <td style="text-align: left; width: 200;">
                                <b><?php echo Portal::language('supplier_phone');?>:</b> <label id="supplier_phone"></label>
                            </td>                                   
                            <td style="text-align: left; width: 200;">
                                <b><?php echo Portal::language('product');?>:</b> <label id="quantity_product"></label></td>
                            </td>                                                                
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="8" style="">
                    <fieldset style="width: 100%">
                    <legend class="title"><?php echo Portal::language('choose_group_by');?></legend>
                    <label style="float: left;height: 18px; margin-top: 2px; "><?php echo Portal::language('group_category');?> &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <div class="multiselect">
                        <div class="selectBox" >
                          <select  name="category_id_1" id="" style="height: 24px;"><?php
					if(isset($this->map['category_id_1_list']))
					{
						foreach($this->map['category_id_1_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('category_id_1',isset($this->map['category_id_1'])?$this->map['category_id_1']:''))
                    echo "<script>$('category_id_1').value = \"".addslashes(URL::get('category_id_1',isset($this->map['category_id_1'])?$this->map['category_id_1']:''))."\";</script>";
                    ?>
	</select>
                          <div class="overSelect"></div>
                        </div>
                        <div id="checkboxes">
                          <?php if(isset($this->map['category_name']) and is_array($this->map['category_name'])){ foreach($this->map['category_name'] as $key1=>&$item1){if($key1!='current'){$this->map['category_name']['current'] = &$item1;?>
                          <label >
                            <input name= "<?php echo $this->map['category_name']['current']['name'];?>" type="checkbox" id="checkbox_P_C_<?php echo $this->map['category_name']['current']['id'];?>" lang="<?php echo $this->map['category_name']['current']['id'];?>" class="itemcheckbox" onclick="FunCheckCategory(this);" style="height: 24px;" />
                            <?php echo $this->map['category_name']['current']['indent'];?>
				            <?php echo $this->map['category_name']['current']['indent_image'];?>
                            <?php echo $this->map['category_name']['current']['name'];?>
                          </label>
                            <?php }}unset($this->map['category_name']['current']);} ?>
                        </div>
                    </div>
                    
                   <label>&nbsp;&nbsp; <?php echo Portal::language('department_recommend');?> &nbsp;&nbsp;<select  name="department1" id="department1"   onchange="search_department(this)" style="height: 24px;"><?php
					if(isset($this->map['department1_list']))
					{
						foreach($this->map['department1_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('department1',isset($this->map['department1'])?$this->map['department1']:''))
                    echo "<script>$('department1').value = \"".addslashes(URL::get('department1',isset($this->map['department1'])?$this->map['department1']:''))."\";</script>";
                    ?>
	</select></label>
                    
                    <input style="display: none;" type="checkbox" name="chbmerge" id="chbmerge" onclick="get_product_department(this);" />
                    <label style="display: none;" for="chbmerge"><?php echo Portal::language('merge_quantity_pc');?></label>
                    <br/>                                   
                    <div style="font-weight: bold; display: none;"><?php echo Portal::language('merge_quantity_pc_note');?>
                    </div>
                    </fieldset>
                    
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    
                    
                </td>
            </tr>
        </table>
        
        <?php } ?>
       
    </div>
      <label class="w3-blue" style="text-transform: uppercase; padding: 3px;"><?php echo Portal::language('list_product_recommend');?></label><br />  
     <table id="list_product" cellspacing="5" cellpadding="5" border="1" bordercolor="#DDDDDD">
                        <tr id="head_product" style="background: #DDDDDD; text-align: center; text-transform: uppercase;">
                            <th><input id="check_all" name="check_all" type="checkbox" onclick="fun_check_all();" /></th>
                            <th style="white-space: nowrap;"><?php echo Portal::language('product_code');?></th>
                            <th style="white-space: nowrap;"><?php echo Portal::language('product_name');?></th>
                            <th><?php echo Portal::language('DVT');?></th>
                            <th><?php echo Portal::language('department_recommend');?></th>
                            <th style="display: none;"><?php echo Portal::language('quantity_debit_warehouse');?></th>
                            <th><?php echo Portal::language('quantity_recommend');?></th>
                            <th style="display: none;"><?php echo Portal::language('quantity_remain_warehoouse');?></th>
                            <th><?php echo Portal::language('price');?></th>
                            <th><?php echo Portal::language('tax_percent');?></th>
                            <th><?php echo Portal::language('pc_tax_amount');?></th>
                            <th><?php echo Portal::language('price_after_tax');?></th>
                            <th><?php echo Portal::language('total');?></th>
                            <th><?php echo Portal::language('delivery_date');?></th>
                            <th><?php echo Portal::language('depscription');?></th>
                            <th><?php echo Portal::language('note');?></th>
                        </tr>
                        <?php if(isset($this->map['mi_list_product']) and is_array($this->map['mi_list_product'])){ foreach($this->map['mi_list_product'] as $key2=>&$item2){if($key2!='current'){$this->map['mi_list_product']['current'] = &$item2;?>
                        <tr id="mi_list_product_<?php echo $i; ?>"  class="items_category_id_<?php echo $this->map['mi_list_product']['current']['category_id'];?> item_department_<?php echo $this->map['mi_list_product']['current']['portal_department_id1'];?> tr-content" style="text-align: center; font-weight: normal; display: ;">
                            <th style="font-weight: normal;">
                                <input class="check_box_child check_box_child_<?php echo $this->map['mi_list_product']['current']['category_id'];?>" id="check_<?php echo $i; ?>" name="check_<?php echo $i; ?>" type="checkbox" onclick="get_total_amount();" />
                                <input id="pc_recommend_detail_id_list_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][pc_recommend_detail_id_list]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['pc_recommend_detail_id_list'];?>" />
                            </th>
                            <th style="font-weight: normal; white-space: nowrap;text-align: left;"><input id="product_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][product_id]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['product_id'];?>" /><?php echo $this->map['mi_list_product']['current']['product_id'];?> <br /><?php 
				if((User::can_view(false,ANY_CATEGORY)))
				{?><span style="margin: 0 auto; cursor: pointer;"  onclick="purchase_history(<?php echo $i; ?>)"><img  src="packages/core/skins/default/images/buttons/view.png"/></span>
				<?php
				}
				?></th>

                            <th style="font-weight: normal; white-space: nowrap; text-align: left;"><input id="product_name_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][product_name]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['product_name'];?>" /><div id="myDIV"><?php echo $this->map['mi_list_product']['current']['product_name'];?></div></th>

                            <th style="font-weight: normal;"><input id="unit_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][unit_id]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['unit_id'];?>" /><?php echo $this->map['mi_list_product']['current']['unit_name'];?></th>

                            <th style="font-weight: normal;"><input id="portal_department_id_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][portal_department_id]" style="display: none;" value="<?php echo $this->map['mi_list_product']['current']['portal_department_id'];?>" /><?php echo $this->map['mi_list_product']['current']['department_name'];?></th>

                            <th style="font-weight: normal;"><input id="quantity_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][quantity]" class="ipt_style" style="width: 40px; text-align: center;" value="<?php echo $this->map['mi_list_product']['current']['quantity'];?>" onchange="jQuery('#quantity_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#quantity_<?php echo $i; ?>').val()))); jQuery('#wh_total_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#quantity_<?php echo $i; ?>').val())));CountPrice(<?php echo $i; ?>);" /></th>
                              
                            <th style="font-weight: normal;"><input id="price_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][price]" class="ipt_style" style="width: 80px; text-align: right;" value="0" onchange="jQuery('#price_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#price_<?php echo $i; ?>').val())));get_total_amount();CountPrice(<?php echo $i; ?>);" /></th>

                            <th style="font-weight: normal;"><input id="tax_percent_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][tax_percent]" class="ipt_style" style="width: 40px; text-align: right;" value="<?php echo $this->map['mi_list_product']['current']['tax_percent'];?>" onchange="get_total_amount();CountPrice(<?php echo $i; ?>);" /></th>
                            
                            <th style="font-weight: normal;"><input id="tax_amount_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][tax_amount]" class="readonly" style="width: 80px; text-align: right;" readonly="readonly" value="0" onchange="jQuery('#price_<?php echo $i; ?>').val(number_format(to_numeric(jQuery('#price_<?php echo $i; ?>').val())));" /></th>
                            <th style="font-weight: normal;"><input id="price_after_tax_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][price_after_tax]" class="readonly" oninput="jQuery('#price_after_tax_<?php echo $i; ?>').ForceNumericOnly().FormatNumber(); CountChangePrice(<?php echo $i; ?>);" style="width: 80px; text-align: right;" /></th>


                            <th style="font-weight: normal;"><input id="total_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][total]" class="readonly" style="width: 80px; text-align: right;" value="0" readonly="readonly" /></th>

                            <th style="font-weight: normal;"><input id="delivery_date_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][delivery_date]" class="ipt_style" style="width: 60px; text-align: center;display: none;" value="<?php echo $this->map['mi_list_product']['current']['delivery_date']; ?>" readonly="" /><?php echo date('d/m/Y',$this->map['mi_list_product']['current']['delivery_date']); ?></th>
                            <th><textarea id="description_product" name="mi_list_product[<?php echo $i; ?>][description_product1]" style="width: 120px;  border: 1px solid #DDDDDD;"></textarea></th>
                            <th style="font-weight: normal;width:200px "><input id="note_<?php echo $i; ?>" type="text" name="mi_list_product[<?php echo $i; ?>][note]" class="ipt_style "  style="width: auto; text-align: center;display: none" value="<?php echo $this->map['mi_list_product']['current']['note']; ?>" readonly="" /><?php echo $this->map['mi_list_product']['current']['note']; ?></th>
                           
                        </tr>
                        <?php $i++; ?>
                        <?php }}unset($this->map['mi_list_product']['current']);} ?>
                    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
<?php echo 'var block_id = '.Module::block_id().';';?>
jQuery("#create_time").datepicker();
    <?php if(!isset($this->map['no_data'])){ ?>
    var input_count = to_numeric('<?php echo $i-1; ?>');
    <?php } ?>

    jQuery('body').click(function(event) {

    if (!jQuery(event.target).closest('.multiselect').length) {

        jQuery('#checkboxes').hide();

    }else{jQuery('#checkboxes').show();};

});
    function Autocomplete()
    {
        jQuery("#pc_supplier_name").autocomplete({
             url: 'get_supplier.php',
             onItemSelect: function(item){
                
                var $arr = item.data[0].split('-');
                jQuery("#pc_supplier_id").val($arr[0]);
                jQuery.ajax({
            			url:"form.php?block_id="+block_id,
            			type:"POST",
            			data:{action:'GetSupplier',pc_supplier_id:$arr[0]},
                        success:function(res)
                        {
                            var otbjs = jQuery.parseJSON(res);
                            jQuery("#supplier_name").html(otbjs.name);
                            jQuery("#supplier_address").html(otbjs.address);
                            jQuery("#supplier_phone").html(otbjs.mobile);
                            jQuery("#supplier_tax_code").html(otbjs.tax_code);
                        }
                }); 
                fun_select_sup(false,$arr[0]); 
            }
        }) ;
    }
    jQuery(".itemcheckbox").attr('checked',true);
    function FunCheckCategory(obj) {
        var $id = obj.lang;
        if($id == 1){
            if(document.getElementById(obj.id).checked==true){
                jQuery(".itemcheckbox").attr('checked',true);
                jQuery("#department1").val('0');
            }else{
                jQuery(".itemcheckbox").attr('checked',false);
            }
        }
        FucGetItem();
    }
    function FucGetItem() {
        jQuery(".itemcheckbox").each(function(){
            $id = this.id;
            //console.log(jQuery(".items_category_id_"+this.lang));
            if(document.getElementById($id).checked==true){
                    jQuery(".items_category_id_"+this.lang).css('display','');
                }else{
                    //console.log($id);
                    jQuery(".items_category_id_"+this.lang).css('display','none');
                    jQuery(".check_box_child_"+this.lang).attr('checked',false);
                }
        });
    }

    function search_department(obj)
    {
        //console.log(obj.value);
        jQuery(".tr-content").css('display','none');
        if(obj.value==0){
            jQuery('input:checkbox:checked').each(function(){
            var group_id=jQuery(this).attr('lang');
            jQuery('.items_category_id_'+group_id).css('display','table-row');
           });
        }else{
            jQuery('input:checkbox:checked').each(function(){
            var group_id=jQuery(this).attr('lang');
            jQuery('.items_category_id_'+group_id).filter('.item_department_'+obj.value).css('display','table-row');
           });
        }
       
    }
    function get_total_amount()
    {
        var total_amount = 0;
        var quantity_prd = 0;
        var arr_product = new Array();

        var tax_percent =0;
        var tax_amount =0;
        var price =0;
        var quantity = 0;
        for(var i=101; i<=input_count; i++)
        {
            tax_percent = to_numeric(jQuery("#tax_percent_"+i).val());
            price = to_numeric(jQuery("#price_"+i).val());
            quantity = to_numeric(jQuery("#quantity_"+i).val());
            tax_amount = price*tax_percent*0.01*quantity;
            price_after_tax = to_numeric(jQuery("#price_after_tax_"+i).val());
            total = to_numeric(jQuery("#total_"+i).val());//tong tien sau thue 
            //total += tax_amount;
            //jQuery("#total_"+i).val(number_format(Math.round(total)));
            //jQuery("#tax_amount_"+i).val(number_format(Math.round(tax_amount)));
            //jQuery("#price_after_tax_"+i).val(number_format(Math.round(price_after_tax)));
            if(document.getElementById('check_'+i).checked==true)
            {
                total_amount += total;
                var product_id = jQuery("#product_id_" + i).val();
                
                if(arr_product.indexOf(product_id)<0)
                {
                    arr_product.push(product_id);
                }
                
                //quantity_prd +=1;
            }
        }
        quantity_prd = arr_product.length;
        jQuery("#quantity_product").html(number_format(Math.round(quantity_prd)));
        jQuery("#total_order").html(number_format(Math.round(total_amount)));
    }
    function CountPrice(xxxx)
    {
        var quantity = to_numeric(jQuery("#quantity_"+xxxx).val());
        var price = to_numeric(jQuery('#price_'+xxxx).val());
        var tax = to_numeric(jQuery('#tax_percent_'+xxxx).val());
        if(price == '')
        {
            price = 0;
            jQuery('#price_'+xxxx).val(0);        
        }
        if(tax == '')
        {
            tax = 0;
            jQuery('#tax_percent_'+xxxx).val(0)
        }
        price_after_tax = to_numeric(price)*(1+tax/100);
        jQuery('#tax_amount_'+xxxx).val(number_format(to_numeric(Math.round(to_numeric(price)*(tax/100)*quantity))));  
        jQuery('#price_after_tax_'+xxxx).val(number_format(Math.round(price_after_tax)));
        jQuery('#total_'+xxxx).val(number_format(to_numeric(Math.round(price_after_tax*quantity))));
        get_total_amount();  
    }
    
    function CountChangePrice(xxxx)
    {
        var quantity = to_numeric(jQuery("#quantity_"+xxxx).val());
        var price_after_tax = to_numeric(jQuery('#price_after_tax_'+xxxx).val());
        var tax = to_numeric(jQuery('#tax_percent_'+xxxx).val());           
        price_before_tax = to_numeric(price_after_tax)/(1+tax/100);
        jQuery('#tax_amount_'+xxxx).val(number_format(Math.round(to_numeric(price_before_tax)*(tax/100)*quantity)));        
        jQuery('#price_'+xxxx).val(number_format(price_before_tax));
        jQuery('#total_'+xxxx).val(number_format(to_numeric(price_after_tax)*quantity)); 
        get_total_amount(); 
    }
    
    function fun_check_all(id)
    {
        if(document.getElementById("check_all").checked==true)
        {
            jQuery(".check_box_child").attr('checked','checked');
        }
        else
        {
            jQuery(".check_box_child").removeAttr('checked');
        }
        get_total_amount();
    }
    
    
    function fun_select_sup(flag=false,pc_supplier_id)
    {
        if(jQuery("#pc_supplier_id").val()=='')
        {
            jQuery("#supplier_name").html('');
        }
        else
        {
            /*daund cmt viết lại ajax bên dưới if(jQuery("#pc_supplier_id").val()=='')
            {
                jQuery("#supplier_name").html('');
            }
            else
            {
                jQuery("#supplier_name").html(list_supplier_js[pc_supplier_id]['name']);
                jQuery("#supplier_address").html(list_supplier_js[pc_supplier_id]['address']);
                jQuery("#supplier_phone").html(list_supplier_js[pc_supplier_id]['mobile']);
                jQuery("#supplier_tax_code").html(list_supplier_js[pc_supplier_id]['tax_code']);
                var tax_percent =0;
                var tax_amount =0;
                var price =0;
                var tax =0;
                var quantity = 0;
                for(var i=101; i<=input_count; i++)
                {
                    if(list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id] != undefined)
                    {
                        price = list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id]['price'];
                        tax = list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id]['tax'];//trung: lay gia tri tax cua ncc do
                        }
                    else
                    {
                        price = 0;
                        tax=0;
                    }
                    quantity = jQuery("#quantity_"+i).val();
                    jQuery("#price_"+i).val(to_numeric(price));
                    jQuery("#tax_percent_"+i).val(to_numeric(tax));//trung: lay gia tri tax cua ncc do
                    tax_percent = jQuery("#tax_percent_"+i).val();
                    console.log(quantity);
                    // ko biet co can lay cai tax_precent trong product ko nhi??
                    if(flag==true && tax_percent==0)
                    {
    
                        if(list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id] != undefined)
                            {
                                
                                
                                tax_percent = list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id]['tax_percent'];
                                //console.log(tax_percent);
                                if(tax_percent=='')
                                    tax_percent =0;
                                 jQuery("#tax_percent_"+i).val(tax_percent);
                            }
                    }
                    tax_amount = tax_percent*price*0.01;
                    tax_amount = tax_amount*quantity;
                    
                    jQuery("#tax_amount_"+i).val(number_format(Math.round(tax_amount)));
                    total = price * quantity;
                    total +=tax_amount;
                    jQuery("#total_"+i).val(number_format(Math.round(total)));
                    CountPrice(i);
                }
                get_total_amount();
            }*/
            jQuery.ajax({
            		url:"form.php?block_id="+block_id,
            		type:"POST",
            		data:{action:'GetPriceSupplier',supplier_id:pc_supplier_id},
                    success:function(html)
                    {
                        var html = jQuery.parseJSON(html);
                        for(var i=101; i<=input_count; i++)
                        {
                            if(html[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id] != undefined)
                            {
                                price = html[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id]['price'];
                                tax = html[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id]['tax'];
                            }
                            else
                            {
                                price = 0;
                                tax=0;
                            }
                            quantity = to_numeric(jQuery("#quantity_"+i).val());
                            jQuery("#price_"+i).val(to_numeric(price));
                            jQuery("#tax_percent_"+i).val(to_numeric(tax));
                            tax_percent = jQuery("#tax_percent_"+i).val();
                            if(flag==true && tax_percent==0)
                            {
            
                                if(html[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id] != undefined)
                                {
                                    
                                    
                                    tax_percent = html[jQuery("#product_id_"+i).val()+'_'+pc_supplier_id]['tax_percent'];
                                    //console.log(tax_percent);
                                    if(tax_percent=='')
                                        tax_percent =0;
                                     jQuery("#tax_percent_"+i).val(tax_percent);
                                }
                            }
                            tax_amount = tax_percent*price*0.01;
                            tax_amount = tax_amount*quantity;
                            
                            jQuery("#tax_amount_"+i).val(number_format(Math.round(tax_amount)));
                            total = price * quantity;
                            total +=tax_amount;
                            jQuery("#total_"+i).val(number_format(Math.round(total)));
                            CountPrice(i);
                        }
                        get_total_amount();
                    }
            });                        
        }
    }
    
    function fun_checksubmit(key)
    {
        var check_select = false;
        for(var i=101; i<=input_count; i++)
        {
            if(document.getElementById('check_'+i).checked==true)
                check_select = true;
            if(to_numeric(jQuery("#quantity_"+i).val()) == 0 && jQuery('#check_'+i).attr('checked')=='checked')
            {
                alert("Số lượng không thể = 0");
                jQuery('#quantity_'+i).focus();
                jQuery('#quantity_'+i).css('background-color','yellow');
                return false;                        
            }
        }
        if(jQuery("#pc_supplier_id").val()=='')
        {
            alert('<?php echo Portal::language('are_you_dont_select_supplier');?>');
            return false;
        }
        else
        {
            if(check_select==false)
            {
                alert('<?php echo Portal::language('are_you_dont_select_product');?>');
                return false;
            }
            else
            {
                for(var i=101; i<=input_count; i++)
                {
                    if(document.getElementById('check_'+i).checked==false)
                        jQuery("#mi_list_product_"+i).remove();
                }
                jQuery("#act").val(key);
                AddPcOrderForm.submit();
            } 
        }
    }
   
    
    
    function get_product_department(obj)
    {
        //console.log(input_count);
        var n = input_count -100;
        for(var i = n;i>0;i--)
        {
            document.getElementById('list_product').deleteRow(i);
        }
        input_count =100;
        if(obj.checked)//lay ra du lieu the hien nhung san pham da duoc gom nhom
        {
            send('GROUP_BY');
        }
        else//lay ra du lieu don: khong gom nhom 
        {

            send('');
        }
        
    }
    function send(flag)
    {
        department_code = jQuery('#department1').val();
        $category_id = '';
        jQuery('input:checkbox:checked').each(function(){
            var group_id=jQuery(this).attr('lang');
            if(group_id != 1 && group_id != undefined)
            {
                if($category_id == '')
                {
                    $category_id = group_id;
                }else
                {
                    $category_id += ','+group_id;
                }
            }
        });
        //console.log($category_id);
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var obj = JSON.parse(text_reponse);
                
                var k = 101;
                for(var i in obj)
                {
                    create_row_product(obj[i],k);
                    k++;
                }
                input_count = k-1;
                fun_select_sup(true);
            }
        }
        xmlhttp.open("GET","get_product_purcharsing.php?recommendation_product=1&department_code="+department_code+"&category_id="+$category_id+"&flag="+ flag,true);
        xmlhttp.send();
        
    }
    
    function create_row_product(obj,k)
    {
        console.log(obj);
        var table = document.getElementById('list_product');
        var rowCount = table.rows.length;
        /** Daund: Dựng lại table */
        //tao tr 
        var row = table.insertRow(rowCount);
       
        row.style.textAlign = 'center';
        row.style.fontWeight ='normal';
        row.id = "mi_list_product_" + k;
        row.setAttribute("class", "items_category_id_"+obj['category_id']+ " item_department_"+obj['portal_department_id']+" tr-content");
        //console.log(row);
        //tao td thu 1 la checkbox 

        var index =0;
        var style = {fontWeight:"normal"};
        
        var nodes = [{tag:"input",type:"checkbox",id:"check_" +k,name:"check_" + k,class:"check_box_child",onclick:"get_total_amount();"},{tag:"input",type:"text",id:"pc_recommend_detail_id_list_" + k,name:"mi_list_product["+k+"][pc_recommend_detail_id_list]",value:obj['pc_recommend_detail_id_list'],style:{display:"none"}}];
        setCellTable(row,index,style,nodes,false);
        //tao td thu 2
        
        index++;
        
        nodes = [{tag:"input",type:"text",id:"product_id_" +k,name:"mi_list_product["+k+"][product_id]",value:obj['product_id'],style:{display:"none"}}];
        setCellTable(row,index,style,nodes,obj['product_id']);
        
        
        //tao td thu 3 
        index ++;
        
        nodes = [{tag:"input",type:"text",id:"product_name_" +k,name:"mi_list_product["+k+"][product_name]",value:obj['product_name'],style:{display:"none"}}];
        setCellTable(row,index,style,nodes,obj['product_name']);
        

        //tao td thu 4 
        index ++;
        nodes = [{tag:"input",type:"text",id:"unit_id_" +k,name:"mi_list_product["+k+"][unit_id]",value:obj['unit_id'],style:{display:"none"}}];
        setCellTable(row,index,style,nodes,obj['unit_name']);

        //tao td thu 5 
        index++;
        nodes = [{tag:"input",type:"text",id:"portal_department_id_" +k,name:"mi_list_product["+k+"][portal_department_id]",value:obj['portal_department_id'],style:{display:"none"}}];
        setCellTable(row,index,style,nodes,obj['department_name']);

        //tao td thu 6 
        index++;
        nodes = [{tag:"input",type:"text",id:"quantity_" +k,name:"mi_list_product["+k+"][quantity]",value:obj['quantity'],style:{textAlign:"center",width:"40px"},class:"ipt_style",onkeyup:"jQuery('#quantity_"+k+"').val(number_format(to_numeric(jQuery('#quantity_"+k+"').val()))); jQuery('#wh_total_"+k+"').val(number_format(to_numeric(jQuery('#quantity_"+k+"').val())));get_total_amount();CountPrice("+k+");"}];
        setCellTable(row,index,style,nodes,false);
        
        // display none
        /*
        var style_none = {fontWeight:"normal",display:"none"};
        index++;
        nodes = [{tag:"input",type:"text",id:"quantity_old_" +k,value:obj['quantity']}];
        setCellTable(row,index,style_none,nodes,false);
        */
        
        //tao td thu 7
        index++;
        nodes = [{tag:"input",type:"text",id:"price_" +k,name:"mi_list_product["+k+"][price]",value:0,style:{textAlign:"center",width:"60px"},class:"ipt_style",onkeyup:"jQuery('#price_"+k+"').val(number_format(to_numeric(jQuery('#price_"+k+"').val())));get_total_amount();CountPrice("+k+");"}];
        setCellTable(row,index,style,nodes,false);

         //tao td thu 8
        index++;
        nodes = [{tag:"input",type:"text",id:"tax_percent_" +k,name:"mi_list_product["+k+"][tax_percent]",value:0,style:{textAlign:"center",width:"40px"},class:"ipt_style",onkeyup:"get_total_amount();CountPrice("+k+");"}];
        setCellTable(row,index,style,nodes,false);

         //tao td thu 9
        index++;
        nodes = [{tag:"input",type:"text",id:"tax_amount_" +k,name:"mi_list_product["+k+"][tax_amount]",value:0,style:{textAlign:"right",width:"60px"},class:"readonly",onkeyup:"jQuery('#tax_amount_"+k+"').val(number_format(to_numeric(jQuery('#tax_amount_"+k+"').val())));get_total_amount();"}];
        setCellTable(row,index,style,nodes,false);

        //tao td thu 10 
        index++;
        nodes = [{tag:"input",type:"text",id:"price_after_tax_" +k,name:"mi_list_product["+k+"][price_after_tax]",value:0,style:{textAlign:"right",width:"80px"},class:"readonly",onkeyup:"jQuery('#price_after_tax_"+k+"').val(number_format(to_numeric(jQuery('#price_after_tax_"+k+"').val())));CountChangePrice("+k+");"}];
        setCellTable(row,index,style,nodes,false);

        //tao td thu 11 
        index++;
        nodes = [{tag:"input",type:"text",id:"total_" +k,name:"mi_list_product["+k+"][total]",value:0,style:{textAlign:"right",width:"80px"},class:"readonly"}];
        setCellTable(row,index,style,nodes,false);

        //tao td thu 12

        index++;
        nodes = [{tag:"input",type:"text",id:"delivery_date_" + k,name:"mi_list_product["+k+"][delivery_date]",value:obj['delivery_date'],style:{textAlign:"center",width:"60px",display:"none"},class:"ipt_style"}];
        setCellTable(row,index,style,nodes,obj['delivery_date_conver']);
        
        //tao td thu 13

        index++;
        nodes = [{tag:"textarea",id:"description_product_" + k,name:"mi_list_product["+k+"][description_product1]",style:{textAlign:"left",width:"120px"}}];
        setCellTable(row,index,style,nodes,false);

        //tao td thu 14 
        index++;
        nodes = [{tag:"input",type:"text",id:"note_" +k,name:"mi_list_product["+k+"][note]",value:obj['note']==null?'':obj['note'],style:{textAlign:"left",width:"200px",display:"none"},class:"ipt_style"}];
        setCellTable(row,index,style,nodes,obj['note']);
        SetLinkPurchaseHistory(k);
    }
    function setCellTable(row,index,style,nodes,innerHTML)
    {
        var td = row.insertCell(index);
        //var style =  {abc:"aaaaaaa", def: "bbbbbbbb"};
        if(typeof(style['fontWeight']) != "undefined")
            td.style.fontWeight = style['fontWeight'];
        if(typeof(style['display']) != "undefined")
            td.style.display = style['display'];
        //var nodes = [{"tag":"input","type":"checkbox","id":"check_id","name":"check_name","class":"check_Class","style":{abc:"def"}}];
        //thuc hien duyet qua cac node
        //console.log(nodes);
        for(var i in nodes)
        {
            //voi moi node thuc hien set du lieu 
            var element = document.createElement(nodes[i]['tag']);
            if(nodes[i]['tag'] != 'textarea')
                element.setAttribute('type',nodes[i]['type']);
            element.setAttribute('id',nodes[i]['id']);
            element.setAttribute('name',nodes[i]['name']);
            if(index ==1)
                td.id="content_td_"+nodes[i]['id'];
            if(typeof(nodes[i]['value']) != "undefined")
            {
                element.setAttribute('value',nodes[i]['value']);  
            }
            if(typeof(nodes[i]['class']) != "undefined")
            {
                element.setAttribute('class',nodes[i]['class']);  
            }
            if(typeof(nodes[i]['onclick']) != "undefined")
            {
                element.setAttribute('onclick',nodes[i]['onclick']);  
            }
            //onkeyup
            if(typeof(nodes[i]['onkeyup']) != "undefined")
            {
                element.setAttribute('onkeyup',nodes[i]['onkeyup']);  
            }
            if(typeof(nodes[i]['style']) != "undefined")
            {
                if(typeof(nodes[i]['style']['display']) != "undefined")
                    element.style.display = nodes[i]['style']['display'];
                //textAlign, width
                if(typeof(nodes[i]['style']['textAlign']) != "undefined")
                    element.style.textAlign = nodes[i]['style']['textAlign'];
                if(typeof(nodes[i]['style']['width']) != "undefined")
                    element.style.width = nodes[i]['style']['width'];
            }
            if(innerHTML!=false)
                td.innerHTML = innerHTML;
            td.appendChild(element);
        }
    }
    
    function SetLinkPurchaseHistory(k)
    {
        $id = jQuery('#mi_list_product_'+k +" td")[1].id;
        jQuery('#'+$id).append('</br /><span style="margin: 0 auto; cursor: pointer;" onclick="purchase_history('+k+')"><img src="packages/core/skins/default/images/buttons/view.png"></span>');
    }
    
    function purchase_history(xxxx)
    {
        product_id = jQuery('#product_id_'+xxxx).val();
        url = '?page=purchase_history&product_id='+product_id+'';  
        window.open(url); 
    }
</script>