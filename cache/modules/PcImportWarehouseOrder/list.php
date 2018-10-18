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
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;"><?php if(Url::get('action')=='handover'){ ?><?php echo Portal::language('list_order_handover');?><?php }else{ ?><?php echo Portal::language('list_order_import_wh');?><?php } ?> </h3>
                </td>
                <td style="text-align: right;">
                    <input name="print_list" id="print_list" type="button" onclick="fun_print_list();" value="<?php echo Portal::language('print_list');?>" style="padding: 10px;" />
                    <button id="export" style="padding: 10px;"><?php echo Portal::language('export_excel');?></button>
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
                            <b><?php echo Portal::language('no_order_in_house');?></b>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <form name="ListPcOrderForm" method="POST">
        
        <fieldset id="search" style="background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
        <legend style="line-height: 25px; padding: 5px;"><?php echo Portal::language('search');?></legend>
        <table cellspacing="5" cellpadding="5">
            <tr>
                <td><label class="lbl_style"><?php echo Portal::language('from_date');?>:</label><br /><input  name="from_date" id="from_date" class="ipt_style" style="width: 100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                <td><label class="lbl_style"><?php echo Portal::language('to_date');?>:</label><br /><input  name="to_date" id="to_date" class="ipt_style" style="width: 100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                <td><label class="lbl_style"><?php echo Portal::language('bill_number');?>:</label><br /><input  name="bill_number" id="bill_number" class="ipt_style" style="width: 100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('bill_number'));?>"></td>
                <td><label class="lbl_style"><?php echo Portal::language('invoice_number');?>:</label><br /><input  name="invoice_number" id="invoice_number" class="ipt_style" style="width: 100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('invoice_number'));?>"></td>
                <!--<td><label class="lbl_style"><?php echo Portal::language('supplier');?>:</label><br /><select  name="supplier_id" id="supplier_id" class="ipt_style" style="width: 200px;"><?php
					if(isset($this->map['supplier_id_list']))
					{
						foreach($this->map['supplier_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))
                    echo "<script>$('supplier_id').value = \"".addslashes(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))."\";</script>";
                    ?>
	</select></td>-->
                <td><label class="lbl_style"><?php echo Portal::language('supplier');?>:</label><br />
                <input  name="supplier_name" id="supplier_name" onfocus="Autocomplete()" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_name'));?>">
                <input  name="supplier_id" id="supplier_id" style="display: none;"  / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_id'));?>">
                
                </td>
                <td><label class="lbl_style"><?php echo Portal::language('warehouse');?>:</label><br /><select  name="warehouse_id" id="warehouse_id" class="ipt_style" style="width: 200px;"><?php
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
                <td><input name="search" id="search" type="submit" value="<?php echo Portal::language('search');?>" style="padding: 5px;" /></td>
            </tr>
        </table>
        </fieldset>
        <?php if(!isset($this->map['no_data'])){ ?>
        <table id="report" style="width: 100%; border-collapse: collapse; margin: 10px auto;" cellspacing="2" cellpadding="2" border="1" bordercolor="#DDDDDD">
            <tr style="height: 30px; background: #DDDDDD; text-align: center;">
                <th><?php echo Portal::language('stt');?></th>
                <th><?php echo Portal::language('create_date');?></th>
                <th><?php echo Portal::language('bill_number');?></th>
                <?php if(Url::get('action')=='import'){ ?>
                <th><?php echo Portal::language('warehouse');?></th>
                <?php } ?>
                <th><?php echo Portal::language('order_name');?></th>
                <th><?php echo Portal::language('receiver_name');?></th>
                <th><?php echo Portal::language('deliver_name');?></th>
                <th><?php echo Portal::language('description');?></th>
                <th><?php echo Portal::language('supplier_name');?></th>
                <th><?php echo Portal::language('invoice_number');?></th>
                <th><?php echo Portal::language('total_amount');?></th>
                <th><?php echo Portal::language('view_invoice');?></th>
                <th><?php echo Portal::language('edit');?></th>
            </tr>
            <?php $stt=1; ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr>
                <td><?php echo $stt++; ?></td>
                <td><?php echo $this->map['items']['current']['create_date'];?></td>
                <td><?php echo $this->map['items']['current']['bill_number'];?></td>
                <?php if(Url::get('action')=='import'){ ?>
                <td><?php echo $this->map['items']['current']['warehouse_name'];?></td>
                <?php } ?>
                <td><?php echo $this->map['items']['current']['pc_order_name'];?></td>
                <td><?php echo $this->map['items']['current']['receiver_name'];?></td>
                <td><?php echo $this->map['items']['current']['deliver_name'];?></td>
                <td><?php echo $this->map['items']['current']['note'];?></td>
                <td><?php echo $this->map['items']['current']['supplier_name'];?></td>
                <td><?php echo $this->map['items']['current']['invoice_number'];?></td>
                <td style="text-align:right;"><?php echo System::display_number($this->map['items']['current']['total_amount']); ?></td>
                <?php if(Url::get('action')=='import'){ ?>
                <td style="text-align: center; width: 80px;"><a target="_blank" href="?page=warehouse_invoice&cmd=view&id=<?php echo $this->map['items']['current']['id'];?>&type=<?php echo $this->map['items']['current']['type'];?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px; padding-top: 2px;"></i></a></td>
                <?php }else{ ?>
                <td style="text-align: center; width: 80px;"><a target="_blank" href="?page=pc_import_warehouse_order&cmd=view_handover&id=<?php echo $this->map['items']['current']['id'];?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px; padding-top: 2px;"></i></a></td>
                <?php } ?>
                <td style="text-align: center; width: 40px;"><a href="?page=pc_import_warehouse_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>&action=<?php echo Url::get('action'); ?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px; padding-top: 2px;"></i></a></td>
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>
        </table>
        <?php } ?>
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    </div>

<script>
    var arr_supplier= <?php echo String::array2js($this->map['suppliers']); ?>;//trung add arr nay de lay customer
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