<style>

input:hover{
    border: 1px dashed #333;
}
select:hover{
    border: 1px dashed #333;    
}
table{
    border-collapse: collapse;  
}

table tr td{
    line-height: 20px;
}

@media print{
    #SearchForm{
        display: none;
    }
    #printer_logo{
        display: none;
    }
}
</style>

<table width="100%" id="printer_logo">
    <tr>
        <td width="85%">&nbsp;</td>
        <td align="right" style="vertical-align: bottom;" >
            <a onclick="print_customer_care();" title="In">
                <img src="packages/core/skins/default/images/printer.png" height="40" />
            </a>
        </td>
    </tr>
</table>
<table width="100%" style="margin: 0 auto;">
    <tr style="font-size: 12px;">
        <td style="text-align: left; width: 20%;"><img src="<?php echo HOTEL_LOGO; ?>" alt="Logo" style="width: 220px; height: auto;" /></td>
        <td style="text-align: center; width: 60%; font-size: 12px; font-weight: bold;"><?php echo HOTEL_NAME .'<br />Địa chỉ: '. HOTEL_ADDRESS .'<br />Email: '. HOTEL_EMAIL .'&nbsp; Website: '. HOTEL_WEBSITE; ?></td>
        <td style="text-align: right; width: 20%;"><?php echo Portal::language('print_times');?>: <span id="print_time"><?php echo date('H:i d/m/Y', time()); ?></span><br /><?php echo Portal::language('print_person');?>: <?php echo $this->map['print_person'];?></td>
    </tr>
    <tr style="font-size: 12px;">
        <td colspan="3"><h2 style="text-transform: uppercase; font-size: 20px; text-align: center;"><?php echo Portal::language('history_customer_care');?></h2></td>
    </tr>
</table>
<form id="SearchForm" method="post">
    <fieldset style="border: 1px solid #333;">
    <legend><?php echo Portal::language('search');?></legend>
        <table width="100%" style="margin: 0 auto;">
            <tr style="font-size: 12px;">
                <td><?php echo Portal::language('from_date');?>:</td>
                <td><input  name="from_date" id="from_date" style="width: 70px; height: 17px"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                <td><?php echo Portal::language('to_date');?>:</td>
                <td><input  name="to_date" id="to_date" style="width: 70px; height: 17px"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                <td><?php echo Portal::language('name_sale');?>:</td>
                <td><select  name="name_sale" id="name_sale" style="height: 23px;"><?php
					if(isset($this->map['name_sale_list']))
					{
						foreach($this->map['name_sale_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('name_sale',isset($this->map['name_sale'])?$this->map['name_sale']:''))
                    echo "<script>$('name_sale').value = \"".addslashes(URL::get('name_sale',isset($this->map['name_sale'])?$this->map['name_sale']:''))."\";</script>";
                    ?>
	</select></td>
                <td><?php echo Portal::language('company');?>:</td>
                <td><input  name="customer_name" id="customer_name" style="width: 100px; height: 17px" onfocus="Autocomplete();" autocomplete="off"  / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"><input  name="customer_id" id="customer_id" style="width: 100px; height: 17px"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id'));?>"></td>
                <td><input name="seach" type="submit" id="search" value="<?php echo Portal::language('search');?>" onclick="ViewCustomerCareForm.submit();" style="width: 87px; height: 23px;"/></td>
            </tr>
        </table>
    </fieldset>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<br />

<table width="100%" border="1" style="margin: 0 auto; border-collapse: collapse">
    <tr style="font-size: 12px; font-weight: bold; text-align: center;">
        <td><?php echo Portal::language('stt');?></td>
        <td width="120px"><?php echo Portal::language('name_sale');?></td>
        <td><?php echo Portal::language('date_contact');?></td>
        <td><?php echo Portal::language('company_name');?></td>
        <td><?php echo Portal::language('attorney_customer');?></td>
        <td width="500px"><?php echo Portal::language('content');?></td>
        <td><?php echo Portal::language('type_contact');?></td>
        <td><?php echo Portal::language('crate_time');?></td>
    </tr>
    <?php $i = 1;?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr style="font-size: 12px;">
        <td align="center"><?php echo $i++; ?></td>
        <td width="120px" style="padding-left: 5px;"><?php echo $this->map['items']['current']['sale_code'];?></td>
        <td style="padding-left: 5px;"><?php echo date('d/m/Y H:i', $this->map['items']['current']['time_contact']); ?></td>
        <td style="padding-left: 5px;"><?php echo $this->map['items']['current']['name'];?></td>
        <td style="padding-left: 5px;"><?php echo $this->map['items']['current']['attorney_customer'];?> &nbsp;- <?php echo $this->map['items']['current']['attorney_hotel'];?></td>
        <td width="500px" style="padding-left: 5px;"><?php echo $this->map['items']['current']['content_contact'];?></td>
        <td style="padding-left: 5px;"><?php echo $this->map['items']['current']['expedeency'];?></td>
        <td style="padding-left: 5px;"><?php echo $this->map['items']['current']['create_time']?date('d/m/Y H:i', $this->map['items']['current']['create_time']):''; ?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
</table>
<script>
jQuery("#chang_language").css('display','none');
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();
function Autocomplete()
{
    document.getElementById('customer_id').value = '';
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item){
            document.getElementById('customer_id').value = item.data[0]; 
        }
    }) ;
}

function check_select()
{
    var val = document.getElementById("customer").value;
    var opts = document.getElementById('data_customer').childNodes;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            jQuery('#customer_id').val(opts[i].id);
            break;
        }
    }
}

function print_customer_care()
{
    var print_time = '<?php echo date('d/m/Y H:i', time()); ?>';
    var user ='<?php echo User::id(); ?>';
    jQuery('#print_time').html(print_time);
    printWebPart('printer',user);
}
</script>