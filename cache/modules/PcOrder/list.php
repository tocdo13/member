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
                    <h3 style="float: left; text-transform: uppercase; font-size: 21px;"><?php echo Portal::language('list_order');?>
                    <?php if(Url::get('status')==1){ echo ' Chờ kế toán duyệt'; }elseif(Url::get('status')==2){ echo ' Chờ giám đốc duyệt'; }elseif(Url::get('status')==3){ echo ' Đã duyệt'; }elseif(Url::get('status')==4){ echo portal::language('confirm_payment'); } ?></h3>
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
                            <?php if(Url::get('status')==1){ echo portal::language('no_waiting_confirm'); }elseif(Url::get('status')==2){ echo portal::language('no_waiting_manager_confirm'); }elseif(Url::get('status')==3){ echo portal::language('no_manager_confirm'); }elseif(Url::get('status')==4){ echo portal::language('no_confirm_payment'); } ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <form name="ListPcOrderForm" method="POST">
        
        <fieldset id="search" style="background: url('packages/hotel/packages/purchasing/skins/default/images/partent.png') top left repeat;">
        <legend style="line-height: 25px; padding: 5px;"><?php echo Portal::language('search');?></legend>
        <table cellspacing="5" cellpadding="2">
            <tr>
                <td><label ><?php echo Portal::language('from_date');?>:</label><input  name="from_date" id="from_date" class="ipt_style" style="width: 100px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                <td><label ><?php echo Portal::language('to_date');?>:</label><input  name="to_date" id="to_date" class="ipt_style" style="width: 100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                <td><label ><?php echo Portal::language('order_code');?>:</label><input  name="order_code" id="order_code" class="ipt_style" style="width: 100px;text-transform:uppercase" / type ="text" value="<?php echo String::html_normalize(URL::get('order_code'));?>"></td>
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
                <td><label ><?php echo Portal::language('supplier');?>:</label>
                <input  name="supplier_name" id="supplier_name" class="ipt_style" style="width: 200px;" onfocus="Autocomplete()"  / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_name'));?>">
                <input  name="supplier_id" id="supplier_id" class="ipt_style" style="width: 200px ; display: none;"   / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_id'));?>">
                </td>
                <td><input name="search"  id="search" type="submit" value="<?php echo Portal::language('search');?>" style="padding: 3px;" /></td>
            </tr>
        </table>
        </fieldset>
        <?php //if(!isset($this->map['no_data'])){ ?>
        <table id="report" style="width: 100%; border-collapse: collapse; margin: 10px auto;" cellspacing="5" cellpadding="5" border="1" bordercolor="#DDDDDD">
            <tr class="w3-light-gray" style="height: 30px; text-align: center; text-transform: uppercase;">
                <th><?php echo Portal::language('stt');?></th>
                <th><?php echo Portal::language('order_code');?></th>
                <th><?php echo Portal::language('order_name');?></th>
                <th><?php echo Portal::language('create_time');?></th>
                <th><?php echo Portal::language('description');?></th>
                <th><?php echo Portal::language('status');?></th>
                <th><?php echo Portal::language('supplier_name');?></th>
                <th><?php echo Portal::language('creater');?></th>
                <th style="display: <?php if(Url::get('status') == 1){ echo 'none';}if(Url::get('status') == 2){ echo '';}if(Url::get('status') == 3){ echo '';}if(Url::get('status') == 4){ echo 'none';} ?>;"><?php echo Portal::language('who_confirm');?></th>
                <th><?php echo Portal::language('total_amount');?> &nbsp;<span id="sum_total"></span></th>
                <?php 
                if(Url::get('status')==4){
                    ?>
                    <th><?php echo Portal::language('payment_type');?></th>
                    <th><?php echo Portal::language('import_status');?></th>
                    <?php 
                } 
                ?>
                <th><?php echo Portal::language('edit');?></th>
                <?php if(Url::get('status')>=3){ ?>
                <th><?php echo Portal::language('print_pc_order');?></th>
                <?php } ?>
            </tr>
            <?php $stt=1; $sum_total = 0; ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr style="height: 30px;">
                <td style="text-align: center; cursor: pointer; width: 30px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?>  ><?php echo $stt++ ?></td>
                <td style="text-align: center; cursor: pointer; width: 100px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['code'];?></td>
                <td style="text-align: left; cursor: pointer; width: 150px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['name'];?></td>
                <td style="text-align: center; cursor: pointer; width: 120px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['create_time'];?></td>
                <td style="text-align: left; cursor: pointer; width: 250px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['description'];?></td>
                <td style="text-align: center; cursor: pointer; width: 150px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['status'];?></td>
                <td style="text-align: left; cursor: pointer;width: 250px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['pc_supplier_name'];?></td>
                <td style="text-align: center; cursor: pointer; width: 150px;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo $this->map['items']['current']['creater'];?></td>
                <td style="text-align: center; width: 150px; display: <?php if(Url::get('status') == 1){ echo 'none';}if(Url::get('status') == 2){ echo '';}if(Url::get('status') == 3){ echo '';}if(Url::get('status') == 4){ echo 'none';} ?>;"><?php if(Url::get('status') == 2){ echo $this->map['items']['current']['person_confirm'];}if(Url::get('status') == 3){ echo $this->map['items']['current']['person_confirm_1'];} ?></td>
                <td class="total_final" style="text-align: right; width: 100px; cursor: pointer;" <?php if(Url::get('status')!=4){ ?> onclick="window.location='?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>'" <?php }else{ ?> onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'" <?php } ?> ><?php echo System::display_number($this->map['items']['current']['total']); $sum_total +=$this->map['items']['current']['total']; ?></td>
                <?php
                if(Url::get('status')==4) 
                {
                    ?>
                    <td style="width: 100px;"><?php echo $this->map['items']['current']['payment_type_name']; ?></td>
                    <td style="text-align: center; width: 100px; cursor: pointer;"   onclick="window.location='?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>'">
                    <?php
                    if($this->map['items']['current']['import_status']=='')
                        echo 'Chờ nhập kho';
                    else
                        echo 'Nhập 1 phần';
                     
                     ?></td>
                    <?php 
                }
                ?>

                <?php if(Url::get('status')!=4){ ?>
                <td style="text-align: center; width: 40px;"><a href="?page=pc_order&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><img class="data_img" src="packages/hotel/packages/purchasing/skins/default/images/edit.png" style="width: 15px; height: auto;" /></a></td>
                <?php }else{ ?> 
                <td style="text-align: center;width: 40px;"><a href="?page=pc_import_warehouse_order&cmd=view_order&id=<?php echo $this->map['items']['current']['id'];?>"><img class="data_img" src="packages/hotel/packages/purchasing/skins/default/images/edit.png" style="width: 15px; height: auto;" /></a></td>
                <?php } ?>
                <?php if(Url::get('status')>=3){ ?>
                <td style="text-align: center;"><a target="_blank" href="?page=pc_order&cmd=print_order&id=<?php echo $this->map['items']['current']['id'];?>"><img class="data_img" src="packages/hotel/packages/purchasing/skins/default/images/printer.png" style="width: 15px; height: auto;" /></a></td>
                <?php } ?>
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>
            <tr>
                <td style="text-align: right; font-weight: bold;" colspan="<?php if(Url::get('status') ==1 or Url::get('status') ==4){echo 8;}else{echo 9;}?>"><?php echo Portal::language('total_amount');?> </td>
                <td style="text-align: right; font-weight: bold;" ><?php echo System::display_number($sum_total); ?></td>
                <td style="text-align: right;" ></td>
                <td style="text-align: right; display: <?php if(Url::get('status')==3 or Url::get('status')==4){echo '';}else{echo 'none';} ?>;" ></td>
                <td style="text-align: right; display: <?php if(Url::get('status')==4){echo '';}else{echo 'none';} ?>;" ></td>
                <td style="text-align: right; display: <?php if(Url::get('status')==4){echo '';}else{echo 'none';} ?>;" ></td>
            </tr>
        </table>
        <?php //} ?>
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    </div>
    <div class="paging"><?php echo $this->map['paging'];?></div> 
<script>
var arr_supplier = <?php echo String::array2js($this->map['suppliers_arr']); ?> ;
//console.log(arr_supplier);
jQuery(document).ready(function(){
        var sum_total = '<?php echo System::display_number($sum_total); ?>';
        jQuery('#sum_total').html(sum_total);
})
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    
    jQuery(document).ready(
        function()
        {
            jQuery("#export").click(function () {
                jQuery(".data_img").remove();
                jQuery("#report .total_final").each(function(){
                    jQuery(this).html(to_numeric(jQuery(this).html()));
                });
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
        jQuery('#supplier_name').autocomplete({
            url:'get_customer1.php?supplier=1',
            onItemSelect:function(item){
                console.log(arr_supplier[item.data]);
                jQuery('#supplier_id').val(arr_supplier[item.data]['supplier_id']);
                
            }
        });
    }
</script>