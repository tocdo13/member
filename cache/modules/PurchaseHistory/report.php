<style>
.simple-layout-middle{
    width: 100%;
}
#SearchForm{
    width: 850px;
}
input:hover{
    border: 1px dashed #333;
}
select:hover{
    border: 1px dashed #333;    
}
table{
    border-collapse: collapse;  
}
#content tr td tr th{
    line-height: 20px;
    text-align: center;
    font-size: 12px;
}
#content tr td tr td{
    line-height: 25px;
    font-size: 12px;
}
.customer_datalist{
    height:50px !important;
    max-height:80px !important;
    overflow: scroll;
}
@media print{
    #PurchaseHistoryForm{
        display: none;
    }
}
</style>
<table cellspacing="0" width="90%" style="margin: 0 auto;">
    <tr style="font-size:12px; font-weight:normal">
        <td align="left" width="80%">
            <strong><?php echo HOTEL_NAME;?></strong>
            <br />
            <strong><?php echo HOTEL_ADDRESS;?></strong>
        </td>
        <td align="right" style="padding-right:10px;" >
            <strong><?php echo Portal::language('template_code');?></strong>
            <br />
            <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
        </td>
    </tr>
</table>
<table width="90%" style="margin: 0 auto;">
    <tr>
        <td>
            <h2 style="text-align: center; text-transform: uppercase;"><?php echo Portal::language('purchase_history');?></h2>
            <form id="PurchaseHistoryForm" method="post">
                <fieldset style="border: 1px solid #069696;">
                    <legend style="font-weight: bold; color: #000;"><?php echo Portal::language('search');?></legend>
                    <table style="margin: 0 auto;">
                        <tr>
                            <td><?php echo Portal::language('from_date');?></td>
                            <td><input  name="from_date" id="from_date" style="width: 70px; height: 17px" readonly=""/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                            <td><?php echo Portal::language('to_date');?></td>
                            <td><input  name="to_date" id="to_date" style="width: 70px; height: 17px" readonly=""/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                            <td><?php echo Portal::language('product');?></td>
                            <!--<td><select  name="product_id" id="product_id" style="width: 100px; height: 23px"><?php
					if(isset($this->map['product_id_list']))
					{
						foreach($this->map['product_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('product_id',isset($this->map['product_id'])?$this->map['product_id']:''))
                    echo "<script>$('product_id').value = \"".addslashes(URL::get('product_id',isset($this->map['product_id'])?$this->map['product_id']:''))."\";</script>";
                    ?>
	</select></td>-->
                            <td>
                            <input  name="product_name" id="product_name" onfocus="Autocomplete_pr()" oninput="check_pr()" / type ="text" value="<?php echo String::html_normalize(URL::get('product_name'));?>">
                            <input  name="product_id" id="product_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>">
                            </td>
                            <td><?php echo Portal::language('supplier_name');?></td>
                            <!--<td><select  name="supplier_id" id="supplier_id" style="width: 100px; height: 23px"><?php
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
                            <td>
                            <input  name="supplier_name" id="supplier_name" onfocus="Autocomplete_sp()" oninput="check_sp()" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_name'));?>">
                            <input  name="supplier_id" id="supplier_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_id'));?>">
                            </td>
                            <td><input name="seach" type="submit" id="search" value="<?php echo Portal::language('view_report');?>" style="width: 87px; height: 23px;"/></td>
                            <td><input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 70px; height: 23px"/></td>
                        </tr>
                    </table>
                </fieldset>
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        </td>
    </tr>
</table>
<br />
<?php  if(empty($this->map['items'])){ ?>
    <table style="margin: 0 auto;">
        <tr>
            <td style="text-align: center;"><strong><?php echo Portal::language('no_record');?></strong></td>
        </tr>
    </table>
<?php }else{ ?>
<table id="PurchaseHistory" width="100%" border="1" style="border-collapse: collapse;">
    <tr style="line-height: 30px; background-color: #cccccc; text-align: center;">
        <th width="5px"><?php echo Portal::language('stt');?></th>
        <th width="30px"><?php echo Portal::language('date');?></th>
        <th width="50px"><?php echo Portal::language('product_id');?></th>
        <th width="50px"><?php echo Portal::language('product_name');?></th>
        <th width="50px"><?php echo Portal::language('purchase_code');?></th>
        <th width="50px"><?php echo Portal::language('supplier_code_new');?></th>
        <th width="100px"><?php echo Portal::language('supplier_name');?></th>
        <th width="5px"><?php echo Portal::language('quantity');?></th>
        <th width="30px"><?php echo Portal::language('price');?></th>
        <th width="5px"><?php echo Portal::language('tax');?> (%)</th>
        <th width="40px"><?php echo Portal::language('total_amount');?></th>
        <th width="50px"><?php echo Portal::language('user');?></th>
    </tr>
    <?php $i =1;?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr style="line-height: 20px;"> 
        <td width="5px" align="center"><?php echo $i++; ?></td>
        <td width="30px"><?php echo date('d/m/Y', $this->map['items']['current']['time']); ?></td>
        <td width="50px"><?php echo $this->map['items']['current']['product_id'];?></td>
        <td width="50px"><?php echo $this->map['items']['current']['product_name'];?></td>
        <td width="50px" onclick="OpenLink(<?php echo $this->map['items']['current']['pc_order_id'];?>);" style="color: #069696; cursor: pointer;" title="Mã đơn hàng <?php echo $this->map['items']['current']['code'];?>"><?php echo $this->map['items']['current']['code'];?></td>
        <td width="50px"><?php echo $this->map['items']['current']['supplier_code'];?></td>
        <td width="100px"><?php echo $this->map['items']['current']['supplier_name'];?></td>
        <td width="5px" align="center"><?php echo $this->map['items']['current']['quantity'];?></td>
        <td width="30px" align="right" class="change_num"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
        <td width="5px" align="center"><?php echo $this->map['items']['current']['tax_percent'];?></td>
        <td width="40px" align="right" class="change_num"><?php echo System::display_number(round($this->map['items']['current']['total_amount'])); ?></td>
        <td width="50px"><?php echo $this->map['items']['current']['full_name'];?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
</table>
<br />
<div class="paging"><?php echo $this->map['paging'];?></div>
<?php }?>

<script>

var supplier_arr = <?php echo String::array2js($this->map['suppliers']); ?>;
//console.log(supplier_arr);
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();

function OpenLink(id)
{
    url = '?page=pc_import_warehouse_order&cmd=view_order&id='+id;
    window.open(url);
}

jQuery("#export_excel").click(function () {
    jQuery('#title_page').css('display', 'block');
    jQuery('.change_num').each(function(){
        jQuery(this).html(to_numeric(jQuery(this).html()));
    })
    jQuery("#PurchaseHistory").battatech_excelexport({
        containerid: "PurchaseHistory"
       , datatype: 'table'
    });
});
function check_sp(){
    if(jQuery('#supplier_name').val()=='')
    {
        jQuery('#supplier_id').val('');
    }
}
function Autocomplete_sp()
{
    jQuery('#supplier_name').autocomplete({
        url:'get_customer1.php?supplier=1',
        onItemSelect:function(item){
        jQuery("#supplier_id").val(supplier_arr[item.data]['supplier_id']);
        
       }
    });
    
}

function check_pr()
{
    if(jQuery('#product_name').val() == '')
    {
        jQuery('#product_id').val('');
    }
}

function Autocomplete_pr()
{
    jQuery('#product_name').autocomplete({
       url:'get_product_all.php?product_all=1',
       onItemSelect:function(item){
        //console.log(item.data);
        jQuery('#product_id').val(item.data);
       } 
        
    });
}
</script>