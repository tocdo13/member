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
    <div style="width: 98%; margin: 10px auto;" >
        <table style="width: 100%;">
            <tr>
                <td>
                    <img class="data_img" src="packages/hotel/packages/purchasing/skins/default/images/purchases_proposed/iconarchive.png" style="width: 50px; height: auto; float: left;" />
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;"><?php if(Url::get('action')=='handover'){ ?>[[.list_order_handover.]]<?php }else{ ?>[[.list_order_import_wh.]]<?php } ?> </h3>
                </td>
                <td style="text-align: right;">
                    <input name="print_list" id="print_list" type="button" onclick="fun_print_list();" value="[[.print_list.]]" style="padding: 10px;" />
                    <button id="export" style="padding: 10px;">[[.export_excel.]]</button>
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
                            <b>[[.no_order_in_house.]]</b>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <form name="ListPcOrderForm" method="POST">
        
        <fieldset id="search" style="background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
        <legend style="line-height: 25px; padding: 5px;">[[.search.]]</legend>
        <table cellspacing="5" cellpadding="5">
            <tr>
                <td><label class="lbl_style">[[.from_date.]]:</label><br /><input name="from_date" type="text" id="from_date" class="ipt_style" style="width: 100px;" /></td>
                <td><label class="lbl_style">[[.to_date.]]:</label><br /><input name="to_date" type="text" id="to_date" class="ipt_style" style="width: 100px;" /></td>
                <td><label class="lbl_style">[[.bill_number.]]:</label><br /><input name="bill_number" type="text" id="bill_number" class="ipt_style" style="width: 100px;" /></td>
                <td><label class="lbl_style">[[.invoice_number.]]:</label><br /><input name="invoice_number" type="text" id="invoice_number" class="ipt_style" style="width: 100px;" /></td>
                <!--<td><label class="lbl_style">[[.supplier.]]:</label><br /><select name="supplier_id" id="supplier_id" class="ipt_style" style="width: 200px;"></select></td>-->
                <td><label class="lbl_style">[[.supplier.]]:</label><br />
                <input name="supplier_name" type="text" id="supplier_name" onfocus="Autocomplete()" />
                <input name="supplier_id" type="text" id="supplier_id" style="display: none;"  />
                
                </td>
                <td><label class="lbl_style">[[.warehouse.]]:</label><br /><select name="warehouse_id" id="warehouse_id" class="ipt_style" style="width: 200px;"></select></td>
                <td><input name="search" id="search" type="submit" value="[[.search.]]" style="padding: 5px;" /></td>
            </tr>
        </table>
        </fieldset>
        <?php if(!isset([[=no_data=]])){ ?>
        <table id="report" style="width: 100%; border-collapse: collapse; margin: 10px auto;" cellspacing="2" cellpadding="2" border="1" bordercolor="#DDDDDD">
            <tr style="height: 30px; background: #DDDDDD; text-align: center;">
                <th>[[.stt.]]</th>
                <th>[[.create_date.]]</th>
                <th>[[.bill_number.]]</th>
                <?php if(Url::get('action')=='import'){ ?>
                <th>[[.warehouse.]]</th>
                <?php } ?>
                <th>[[.order_name.]]</th>
                <th>[[.receiver_name.]]</th>
                <th>[[.deliver_name.]]</th>
                <th>[[.description.]]</th>
                <th>[[.supplier_name.]]</th>
                <th>[[.invoice_number.]]</th>
                <th>[[.total_amount.]]</th>
                <th>[[.view_invoice.]]</th>
                <th>[[.edit.]]</th>
            </tr>
            <?php $stt=1; ?>
            <!--LIST:items-->
            <tr>
                <td><?php echo $stt++; ?></td>
                <td>[[|items.create_date|]]</td>
                <td>[[|items.bill_number|]]</td>
                <?php if(Url::get('action')=='import'){ ?>
                <td>[[|items.warehouse_name|]]</td>
                <?php } ?>
                <td>[[|items.pc_order_name|]]</td>
                <td>[[|items.receiver_name|]]</td>
                <td>[[|items.deliver_name|]]</td>
                <td>[[|items.note|]]</td>
                <td>[[|items.supplier_name|]]</td>
                <td>[[|items.invoice_number|]]</td>
                <td style="text-align:right;"><?php echo System::display_number([[=items.total_amount=]]); ?></td>
                <?php if(Url::get('action')=='import'){ ?>
                <td style="text-align: center; width: 80px;"><a target="_blank" href="?page=warehouse_invoice&cmd=view&id=[[|items.id|]]&type=[[|items.type|]]"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px; padding-top: 2px;"></i></a></td>
                <?php }else{ ?>
                <td style="text-align: center; width: 80px;"><a target="_blank" href="?page=pc_import_warehouse_order&cmd=view_handover&id=[[|items.id|]]"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px; padding-top: 2px;"></i></a></td>
                <?php } ?>
                <td style="text-align: center; width: 40px;"><a href="?page=pc_import_warehouse_order&cmd=edit&id=[[|items.id|]]&action=<?php echo Url::get('action'); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px; padding-top: 2px;"></i></a></td>
            </tr>
            <!--/LIST:items-->
        </table>
        <?php } ?>
        </form>
    </div>

<script>
    var arr_supplier= <?php echo String::array2js([[=suppliers=]]); ?>;//trung add arr nay de lay customer
    //console.log(arr_supplier);
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery(".data_img").remove();
                jQuery("#report").battatech_excelexport({
                    containerid: "report"
                   , datatype: 'table'
                });
                ListPcOrderForm.submit();
            });
            
        }
    );
    
    function fun_print_list()
    {
        jQuery("#print_list").css('display','none');
        jQuery("#export").css('display','none');
        jQuery("#search").css('display','none');
        jQuery(".data_img").remove();
        var user ='<?php echo User::id(); ?>';printWebPart('printer',user);
        ListPcOrderForm.submit();
    }
    function Autocomplete()
    {
        jQuery("#supplier_name").autocomplete({
            url:'get_customer1.php?supplier=1',
            onItemSelect:function(item){
                jQuery("#supplier_id").val(arr_supplier[item.data]['supplier_id']);
            }
            
        });
    }
</script>