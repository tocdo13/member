<style>
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
</style>
<form name="ViewOrderPcImportWarehouseOrderForm" method="POST" enctype="multipart/form-data">
    <div style="width: 100%; height: auto; ">
        <table style="width: 100%;">
            <tr>
                <td>
                    <img src="packages/hotel/packages/purchasing/skins/default/images/purchases_proposed/iconarchive.png" style="width: 50px; height: auto; float: left;" />
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;"><?php echo Portal::language('edit_order');?></h3>
                </td>
                <td style="text-align: right;">
                    
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
        
        <?php if(!isset($this->map['no_data'])){ ?>
        <table style="width: 100%; margin: 10px auto;" cellspacing="2" cellpadding="2">
            <tr>
                <td style="width: 150px;">
                    <label ><?php echo Portal::language('create_time');?></label> : 
                    <input id="create_time" name="create_time" type="text" class="ipt_style" style="width: 80px;" value="<?php echo $this->map['create_time'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?>  />
                </td>
                <td style="width: 200px;">
                    <label ><?php echo Portal::language('order_code');?></label> : 
                    <input id="order_code" name="order_code" type="text" class="ipt_style" style="width: 120px; font-size: 10px; text-align: center; background: #DDDDDD;" readonly="" value="<?php echo $this->map['code'];?>" />
                </td>
                <td style="width: 200px;">
                    <label ><?php echo Portal::language('order_name');?></label> : 
                    <input id="order_name" name="order_name" type="text" class="ipt_style" style="width: 160px;" value="<?php echo $this->map['name'];?>" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?> />
                </td>
                <td style="width: 60px; text-align: right;">
                    <?php echo Portal::language('description');?>: 
                </td>
                <td style="width: 400px;">
                    <textarea id="description" name="description" <?php if($this->map['status']==4){ echo 'readonly=""'; } ?> style="width: 400px; height: 24px; border: 1px solid #DDDDDD;"><?php echo $this->map['description'];?></textarea>
                </td>
            </tr>
            <tr>
                
            </tr>
            <tr>
                <td colspan="5">
                    <table style="width: 100%; background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
                        <tr style="text-transform: uppercase;">
                            <td style=" text-align: left; width: 450px;">
                                <b><?php echo Portal::language('supplier_name');?>:</b> <label id="supplier_name"><?php echo $this->map['pc_supplier_name'];?></label><br />
                                <b><?php echo Portal::language('supplier_address');?>:</b> <label id="supplier_address"><?php echo $this->map['pc_supplier_address'];?></label>                               
                            </td>
                            <td style="width: 200px;">
                                <b><?php echo Portal::language('supplier_tax_code');?>:</b> <label id="supplier_tax_code"><?php echo $this->map['pc_supplier_tax_code'];?></label><br />
                                <b><?php echo Portal::language('supplier_phone');?>:</b> <label id="supplier_phone"><?php echo $this->map['pc_supplier_mobile'];?></label>
                            </td>
                            <td style=" width: 180px;"><b><?php echo Portal::language('total_order');?>:</b> <label id="total_order"><?php echo $this->map['total'];?></label><br /><b><?php echo Portal::language('product');?>:</b> <label id="quantity_product"><?php echo $this->map['quantity'];?></label></td>
                            <td style="width: 250px;">
                                    <label ><?php echo Portal::language('number_contract');?></label> : 
                                    <input id="number_contract" name="number_contract" type="text" class="ipt_style" style="width:150px" value="<?php echo $this->map['number_contract'];?>" readonly="" />
                            </td>
                        </tr>
                        <tr>
                            
                            <td colspan="4" style="text-align: right; padding-right: 50px;">
                                    <?php if($this->map['file_contract']==''){ ?>
                                    <label class="lbl_style"><?php echo Portal::language('file_contract');?></label> : 
                                    Choose a file to upload: <input type="file" name="file" id="file" />
                                    <span  style="color: red;">(<?php echo Portal::language('only_choose_doc_or_docx_or_pdf_file_type');?>)</span>
                                    <?php }else{ ?>
                                    <label class="lbl_style"><?php echo Portal::language('download_file_contract');?></label> 
                                    <a href="<?php echo $this->map['file_contract'];?>" style="padding: 5px; border: 1px solid #DDDDDD; background: #00B2F9; color: #FFFFFF; margin: 5px;"><?php echo Portal::language('download');?></a>
                                    <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <label class="lbl_style"><?php echo Portal::language('list_product_recommend');?></label><br />
                    <table id="list_product" style="width: 100%; margin: 0px auto;" cellspacing="5" cellpadding="10" border="1" bordercolor="#DDDDDD">
                        <tr style="background: #DDDDDD; text-align: center; text-transform: uppercase;">
                            <th><?php echo Portal::language('stt');?></th>
                            <th><?php echo Portal::language('department_recommend');?></th>
                            <th><?php echo Portal::language('product_code');?></th>
                            <th><?php echo Portal::language('product_name');?></th>
                            <th><?php echo Portal::language('unit_name');?></th>
                            <th style="display: none;"><?php echo Portal::language('quantity_remain_warehouse');?></th>
                            <th><?php echo Portal::language('quantity_recommend');?></th>
                            <th style="display: none;"><?php echo Portal::language('total_quantity_warehoouse');?></th>
                            <th><?php echo Portal::language('price');?></th>
                            <th><?php echo Portal::language('tax_percent');?></th>
                            <th><?php echo Portal::language('pc_tax_amount');?></th>
                            <th><?php echo Portal::language('total');?></th>
                            <th><?php echo Portal::language('delivery_date');?></th>
                            <th><?php echo Portal::language('note');?></th>
                            <th><?php echo Portal::language('name_warehouse');?></th>
                            <th><?php echo Portal::language('import_wh');?></th>
                            <th><?php echo Portal::language('handover');?></th>
                        </tr>
                        <?php if(isset($this->map['mi_list_product']) and is_array($this->map['mi_list_product'])){ foreach($this->map['mi_list_product'] as $key1=>&$item1){if($key1!='current'){$this->map['mi_list_product']['current'] = &$item1;?>
                                <tr>
                                    <td style="width: 20px;" rowspan="<?php echo $this->map['mi_list_product']['current']['count'];?>"><?php echo $this->map['mi_list_product']['current']['stt'];?></td>
                                    <td style="width:100px;" rowspan="<?php echo $this->map['mi_list_product']['current']['count'];?>"><?php echo $this->map['mi_list_product']['current']['name'];?></td>
                                    <?php $mi_list_product_child = '';  ?>
                                    <?php if(isset($this->map['mi_list_product']['current']['child']) and is_array($this->map['mi_list_product']['current']['child'])){ foreach($this->map['mi_list_product']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['mi_list_product']['current']['child']['current'] = &$item2;?>
                                        <?php $mi_list_product_child = $this->map['mi_list_product']['current']['child']['current']['id']; $select_name = 'warehouse_name_'. $this->map['mi_list_product']['current']['id']; ?>
                                        <td style="width: 100px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['product_id'];?> <?php echo 1 ?></td>
                                        <td style="width:200px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['product_name'];?></td>
                                        <td style="width: 70px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['unit_name'];?></td>
                                        <td style="display: none;"><?php echo $this->map['mi_list_product']['current']['child']['current']['wh_remain'];?></td>
                                        <td style="width: 70px;"><?php if($this->map['mi_list_product']['current']['child']['current']['quantity'] <1){ echo '0'.$this->map['mi_list_product']['current']['child']['current']['quantity'];}else{echo $this->map['mi_list_product']['current']['child']['current']['quantity'];}?></td>
                                        <td style="display: none;"><?php echo $this->map['mi_list_product']['current']['child']['current']['total_quantity_warehouse'];?></td>
                                        <td style="text-align: right;width: 70px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['price'];?></td>
                                        <td style="text-align: right;width: 50px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['tax_percent'];?></td>
                                        <td style="text-align: right;width: 70px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['tax_amount'];?></td>
                                        <td style="text-align: right;width: 100px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['total'];?></td>
                                        <td style="width: 70px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['delivery_date'];?></td>
                                        <td><?php echo $this->map['mi_list_product']['current']['child']['current']['note'];?></td>
                                        <!--trung :them truong select kho -->
                                        <td style="width: 120px;" rowspan="<?php echo $this->map['mi_list_product']['current']['count'];?>">
                                        <select  name="<?php echo $select_name; ?>" id="warehouse_name" style="width: 120px !important;"><?php
					if(isset($this->map['<?php echo $select_name; ?>_list']))
					{
						foreach($this->map['<?php echo $select_name; ?>_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('<?php echo $select_name; ?>',isset($this->map['<?php echo $select_name; ?>'])?$this->map['<?php echo $select_name; ?>']:''))
                    echo "<script>$('<?php echo $select_name; ?>').value = \"".addslashes(URL::get('<?php echo $select_name; ?>',isset($this->map['<?php echo $select_name; ?>'])?$this->map['<?php echo $select_name; ?>']:''))."\";</script>";
                    ?>
	
                                            <?php  foreach($this->map['warehouse_id'] as $key =>$value ){ ?>                                        
                                            <option <?php if($this->map['mi_list_product']['current']['child']['current']['warehouse_id']== $value['id']){ echo 'selected="selected"';} ?> value="<?php echo $value['id']; ?>" ><?php  echo $value['name'] ;?></option>
                                             <?php }  ?>                                            
                                        </select>
                                        </td>                                                                                
                                       <!--trung :them truong select kho -->                                                                                
                                        <?php break; ?>
                                    <?php }}unset($this->map['mi_list_product']['current']['child']['current']);} ?>
                                    <td style="width: 80px;" rowspan="<?php echo $this->map['mi_list_product']['current']['count'];?>"><a class="w3-btn w3-orange" target="_blank" onclick="import_warehouse(<?php echo $this->map['mi_list_product']['current']['id'];?>,'<?php echo $this->map['mi_list_product']['current']['ids'];?>');" id="href_warehouse" ><?php echo Portal::language('inport_wh');?></a></td>
                                    <td style="width: 80px;" rowspan="<?php echo $this->map['mi_list_product']['current']['count'];?>"><a class="w3-btn w3-blue" target="_blank" href="?page=pc_import_warehouse_order&cmd=add&action=handover&order_id=<?php echo Url::get('id'); ?>&portal_department_id=<?php echo $this->map['mi_list_product']['current']['id'];?>&ids=<?php echo $this->map['mi_list_product']['current']['ids'];?>"><?php echo Portal::language('hanover');?></a></td>
                                <?php if(isset($this->map['mi_list_product']['current']['child']) and is_array($this->map['mi_list_product']['current']['child'])){ foreach($this->map['mi_list_product']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['mi_list_product']['current']['child']['current'] = &$item3;?>
                                        <?php if($mi_list_product_child != $this->map['mi_list_product']['current']['child']['current']['id']){  ?>
                                        <td style="width: 100px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['product_id'];?></td>
                                        <td style="width: 200px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['product_name'];?></td>
                                        <td><?php echo $this->map['mi_list_product']['current']['child']['current']['unit_name'];?></td>
                                        <td style="display: none;"><?php echo $this->map['mi_list_product']['current']['child']['current']['wh_remain'];?></td>
                                        <td><?php if($this->map['mi_list_product']['current']['child']['current']['quantity'] <1){ echo '0'.$this->map['mi_list_product']['current']['child']['current']['quantity'];}else{echo $this->map['mi_list_product']['current']['child']['current']['quantity'];}?></td>
                                        <td style="display: none;"><?php echo $this->map['mi_list_product']['current']['child']['current']['total_quantity_warehouse'];?></td>
                                        <td style="text-align: right;"><?php echo $this->map['mi_list_product']['current']['child']['current']['price'];?></td>
                                        <td style="text-align: right;"><?php echo $this->map['mi_list_product']['current']['child']['current']['tax_percent'];?></td>
                                        <td style="text-align: right;"><?php echo $this->map['mi_list_product']['current']['child']['current']['tax_amount'];?></td>
                                        <td style="text-align: right;"><?php echo $this->map['mi_list_product']['current']['child']['current']['total'];?></td>
                                        <td style="width: 70px;"><?php echo $this->map['mi_list_product']['current']['child']['current']['delivery_date'];?></td>
                                        <td ><?php echo $this->map['mi_list_product']['current']['child']['current']['note'];?></td>
                                       
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php }}unset($this->map['mi_list_product']['current']['child']['current']);} ?>
                        <?php }}unset($this->map['mi_list_product']['current']);} ?>
                    </table>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
     
    function import_warehouse(portal_department_id,ids)
    {
        if(ids == 0)
        {
            alert('Sản phẩm đã được nhập kho, không thể nhập lại');
            return false;
        }
        var warehouse_id = jQuery('select[name=warehouse_name_'+portal_department_id+']').val();
        console.log(warehouse_id);
        url = '?page=pc_import_warehouse_order&cmd=add&action=import&order_id=<?php echo Url::get('id'); ?>&portal_department_id='+portal_department_id+'&ids='+ids+'&warehouse_ids='+warehouse_id;
        window.open(url);
    }
    
    jQuery("#create_time").datepicker();
    <?php if(isset($this->map['no_data'])){ ?>
    var input_count = to_numeric('<?php echo $i-1; ?>');
    <?php } ?>
    
    function get_total_amount()
    {
        var total_amount = 0;
        var quantity_prd = 0;
        for(var i=101; i<=input_count; i++)
        {
            total = to_numeric(jQuery("#quantity_"+i).val())*to_numeric(jQuery("#price_"+i).val());
            jQuery("#total_"+i).val(number_format(total));
            if(document.getElementById('check_'+i).checked==true)
            {
                total_amount += total;
                quantity_prd +=1;
            }
        }
        jQuery("#quantity_product").html(number_format(quantity_prd));
        jQuery("#total_order").html(number_format(total_amount));
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
    
    function fun_select_sup()
    {
        if(jQuery("#pc_supplier_id").val()=='')
        {
            jQuery("#supplier_name").html('');
        }
        else
        {
            jQuery("#supplier_name").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['name']);
            jQuery("#supplier_address").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['address']);
            jQuery("#supplier_phone").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['mobile']);
            jQuery("#supplier_tax_code").html(list_supplier_js[jQuery("#pc_supplier_id").val()]['tax_code']);
            for(var i=101; i<=input_count; i++)
            {
                if(list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+jQuery("#pc_supplier_id").val()] != undefined)
                    price = list_sup_price_js[jQuery("#product_id_"+i).val()+'_'+jQuery("#pc_supplier_id").val()]['price'];
                else
                    price = 0;
                jQuery("#price_"+i).val(number_format(to_numeric(price)));
                total = to_numeric(price) * to_numeric(jQuery("#quantity_"+i).val());
                jQuery("#total_"+i).val(number_format(total));
            }
            get_total_amount();
        }
    }
    
    function fun_checksubmit(key)
    {
        var check_select = false;
        for(var i=101; i<=input_count; i++)
        {
            if(document.getElementById('check_'+i).checked==true)
                check_select = true;
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
                EditPcOrderForm.submit();
            } 
        }
    } 
    
                   
</script>