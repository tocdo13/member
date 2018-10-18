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
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;">[[.edit_order.]]</h3>
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
                    <?php if(isset([[=no_data=]])){ ?>
                        <div style="margin: 10px auto; width: 200px; height: 70px; line-height: 70px; text-align: center; border: 2px solid #00B2F9;">
                            <b>[[.no_request.]]</b>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        
        <?php if(!isset([[=no_data=]])){ ?>
        <table style="width: 100%; margin: 10px auto;" cellspacing="2" cellpadding="2">
            <tr>
                <td style="width: 150px;">
                    <label >[[.create_time.]]</label> : 
                    <input id="create_time" name="create_time" type="text" class="ipt_style" style="width: 80px;" value="[[|create_time|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?>  />
                </td>
                <td style="width: 200px;">
                    <label >[[.order_code.]]</label> : 
                    <input id="order_code" name="order_code" type="text" class="ipt_style" style="width: 120px; font-size: 10px; text-align: center; background: #DDDDDD;" readonly="" value="[[|code|]]" />
                </td>
                <td style="width: 200px;">
                    <label >[[.order_name.]]</label> : 
                    <input id="order_name" name="order_name" type="text" class="ipt_style" style="width: 160px;" value="[[|name|]]" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> />
                </td>
                <td style="width: 60px; text-align: right;">
                    [[.description.]]: 
                </td>
                <td style="width: 400px;">
                    <textarea id="description" name="description" <?php if([[=status=]]==4){ echo 'readonly=""'; } ?> style="width: 400px; height: 24px; border: 1px solid #DDDDDD;">[[|description|]]</textarea>
                </td>
            </tr>
            <tr>
                
            </tr>
            <tr>
                <td colspan="5">
                    <table style="width: 100%; background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
                        <tr style="text-transform: uppercase;">
                            <td style=" text-align: left; width: 450px;">
                                <b>[[.supplier_name.]]:</b> <label id="supplier_name">[[|pc_supplier_name|]]</label><br />
                                <b>[[.supplier_address.]]:</b> <label id="supplier_address">[[|pc_supplier_address|]]</label>                               
                            </td>
                            <td style="width: 200px;">
                                <b>[[.supplier_tax_code.]]:</b> <label id="supplier_tax_code">[[|pc_supplier_tax_code|]]</label><br />
                                <b>[[.supplier_phone.]]:</b> <label id="supplier_phone">[[|pc_supplier_mobile|]]</label>
                            </td>
                            <td style=" width: 180px;"><b>[[.total_order.]]:</b> <label id="total_order">[[|total|]]</label><br /><b>[[.product.]]:</b> <label id="quantity_product">[[|quantity|]]</label></td>
                            <td style="width: 250px;">
                                    <label >[[.number_contract.]]</label> : 
                                    <input id="number_contract" name="number_contract" type="text" class="ipt_style" style="width:150px" value="[[|number_contract|]]" readonly="" />
                            </td>
                        </tr>
                        <tr>
                            
                            <td colspan="4" style="text-align: right; padding-right: 50px;">
                                    <?php if([[=file_contract=]]==''){ ?>
                                    <label class="lbl_style">[[.file_contract.]]</label> : 
                                    Choose a file to upload: <input type="file" name="file" id="file" />
                                    <span  style="color: red;">([[.only_choose_doc_or_docx_or_pdf_file_type.]])</span>
                                    <?php }else{ ?>
                                    <label class="lbl_style">[[.download_file_contract.]]</label> 
                                    <a href="[[|file_contract|]]" style="padding: 5px; border: 1px solid #DDDDDD; background: #00B2F9; color: #FFFFFF; margin: 5px;">[[.download.]]</a>
                                    <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <label class="lbl_style">[[.list_product_recommend.]]</label><br />
                    <table id="list_product" style="width: 100%; margin: 0px auto;" cellspacing="5" cellpadding="10" border="1" bordercolor="#DDDDDD">
                        <tr style="background: #DDDDDD; text-align: center; text-transform: uppercase;">
                            <th>[[.stt.]]</th>
                            <th>[[.department_recommend.]]</th>
                            <th>[[.product_code.]]</th>
                            <th>[[.product_name.]]</th>
                            <th>[[.unit_name.]]</th>
                            <th style="display: none;">[[.quantity_remain_warehouse.]]</th>
                            <th>[[.quantity_recommend.]]</th>
                            <th style="display: none;">[[.total_quantity_warehoouse.]]</th>
                            <th>[[.price.]]</th>
                            <th>[[.tax_percent.]]</th>
                            <th>[[.pc_tax_amount.]]</th>
                            <th>[[.total.]]</th>
                            <th>[[.delivery_date.]]</th>
                            <th>[[.note.]]</th>
                            <th>[[.name_warehouse.]]</th>
                            <th>[[.import_wh.]]</th>
                            <th>[[.handover.]]</th>
                        </tr>
                        <!--LIST:mi_list_product-->
                                <tr>
                                    <td style="width: 20px;" rowspan="[[|mi_list_product.count|]]">[[|mi_list_product.stt|]]</td>
                                    <td style="width:100px;" rowspan="[[|mi_list_product.count|]]">[[|mi_list_product.name|]]</td>
                                    <?php $mi_list_product_child = '';  ?>
                                    <!--LIST:mi_list_product.child-->
                                        <?php $mi_list_product_child = [[=mi_list_product.child.id=]]; $select_name = 'warehouse_name_'. [[=mi_list_product.id=]]; ?>
                                        <td style="width: 100px;">[[|mi_list_product.child.product_id|]] <?php echo 1 ?></td>
                                        <td style="width:200px;">[[|mi_list_product.child.product_name|]]</td>
                                        <td style="width: 70px;">[[|mi_list_product.child.unit_name|]]</td>
                                        <td style="display: none;">[[|mi_list_product.child.wh_remain|]]</td>
                                        <td style="width: 70px;"><?php if([[=mi_list_product.child.quantity=]] <1){ echo '0'.[[=mi_list_product.child.quantity=]];}else{echo [[=mi_list_product.child.quantity=]];}?></td>
                                        <td style="display: none;">[[|mi_list_product.child.total_quantity_warehouse|]]</td>
                                        <td style="text-align: right;width: 70px;">[[|mi_list_product.child.price|]]</td>
                                        <td style="text-align: right;width: 50px;">[[|mi_list_product.child.tax_percent|]]</td>
                                        <td style="text-align: right;width: 70px;">[[|mi_list_product.child.tax_amount|]]</td>
                                        <td style="text-align: right;width: 100px;">[[|mi_list_product.child.total|]]</td>
                                        <td style="width: 70px;">[[|mi_list_product.child.delivery_date|]]</td>
                                        <td>[[|mi_list_product.child.note|]]</td>
                                        <!--trung :them truong select kho -->
                                        <td style="width: 120px;" rowspan="[[|mi_list_product.count|]]">
                                        <select name="<?php echo $select_name; ?>" id="warehouse_name" style="width: 120px !important;">
                                            <?php  foreach([[=warehouse_id=]] as $key =>$value ){ ?>                                        
                                            <option <?php if([[=mi_list_product.child.warehouse_id=]]== $value['id']){ echo 'selected="selected"';} ?> value="<?php echo $value['id']; ?>" ><?php  echo $value['name'] ;?></option>
                                             <?php }  ?>                                            
                                        </select>
                                        </td>                                                                                
                                       <!--trung :them truong select kho -->                                                                                
                                        <?php break; ?>
                                    <!--/LIST:mi_list_product.child-->
                                    <td style="width: 80px;" rowspan="[[|mi_list_product.count|]]"><a class="w3-btn w3-orange" target="_blank" onclick="import_warehouse([[|mi_list_product.id|]],'[[|mi_list_product.ids|]]');" id="href_warehouse" >[[.inport_wh.]]</a></td>
                                    <td style="width: 80px;" rowspan="[[|mi_list_product.count|]]"><a class="w3-btn w3-blue" target="_blank" href="?page=pc_import_warehouse_order&cmd=add&action=handover&order_id=<?php echo Url::get('id'); ?>&portal_department_id=[[|mi_list_product.id|]]&ids=[[|mi_list_product.ids|]]">[[.hanover.]]</a></td>
                                <!--LIST:mi_list_product.child-->
                                        <?php if($mi_list_product_child != [[=mi_list_product.child.id=]]){  ?>
                                        <td style="width: 100px;">[[|mi_list_product.child.product_id|]]</td>
                                        <td style="width: 200px;">[[|mi_list_product.child.product_name|]]</td>
                                        <td>[[|mi_list_product.child.unit_name|]]</td>
                                        <td style="display: none;">[[|mi_list_product.child.wh_remain|]]</td>
                                        <td><?php if([[=mi_list_product.child.quantity=]] <1){ echo '0'.[[=mi_list_product.child.quantity=]];}else{echo [[=mi_list_product.child.quantity=]];}?></td>
                                        <td style="display: none;">[[|mi_list_product.child.total_quantity_warehouse|]]</td>
                                        <td style="text-align: right;">[[|mi_list_product.child.price|]]</td>
                                        <td style="text-align: right;">[[|mi_list_product.child.tax_percent|]]</td>
                                        <td style="text-align: right;">[[|mi_list_product.child.tax_amount|]]</td>
                                        <td style="text-align: right;">[[|mi_list_product.child.total|]]</td>
                                        <td style="width: 70px;">[[|mi_list_product.child.delivery_date|]]</td>
                                        <td >[[|mi_list_product.child.note|]]</td>
                                       
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <!--/LIST:mi_list_product.child-->
                        <!--/LIST:mi_list_product-->
                    </table>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>
</form>
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
    <?php if(isset([[=no_data=]])){ ?>
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
            alert('[[.are_you_dont_select_supplier.]]');
            return false;
        }
        else
        {
            if(check_select==false)
            {
                alert('[[.are_you_dont_select_product.]]');
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