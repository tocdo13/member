<style>
    @media print
{
    #search_display{
        display: none;
    }
    .footer{
        display: none;
    }
    .see{
        display: none;
    }
}
.simple-layout-middle{
    width: 95%;
}
</style>

<form name="repost_revenue" method="post">
<table id="repost_revenue" width="100%" style="border-collapse: collapse;">
    <tr>
        <td>
            <table border="0" cellSpacing=0 cellpadding="5" width="100%" class="header" style="border-collapse: collapse;">
			<tr valign="middle">
			  <td align="left">
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?>
			  </td>
			  <td align="right">
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
			  </td>
			</tr>	
             <tr>
                <td style="text-align: center;text-transform: uppercase; " colspan="2">
                    <?php if(Url::get('type')=='date'){ ?><h2><?php echo Portal::language('repost_revenue_payment_date');?></h2><?php }else{ ?><h2><?php echo Portal::language('repost_revenue_payment_month');?></h2><?php } ?><?php echo Portal::language('date_from');?>: <?php echo $this->map['date_from'];?> <?php echo Portal::language('date_to');?>: <?php echo $this->map['date_to'];?>
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
            </tr>
		</table>
        </td>
    </tr>
    <tr id="search_display">
        <td align="middle" width="80%"><fieldset >
            <legend><?php echo Portal::language('search');?></legend>
        <table cellspacing="0" cellpadding="5" width="70%" class="search_date" border="0" style=" margin-left: 200px; border-collapse: collapse; ">
            
            <tr>
                <td>
                        <?php echo Portal::language('date_from');?>
                        <input  name="date_from" id="date_from" style="width: 80px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>">
                </td>
                <td>
                        <?php echo Portal::language('date_to');?>
                        <input  name="date_to" id="date_to" style="width: 80px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>">
                </td>
                <td>
                        <?php echo Portal::language('customer_name');?>
                        <input  name="customer_name" id="customer_name" style="width: 250px;" onkeypress="Autocomplete()" oninput="delete_customer();" autocomplete="off" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                        <input  name="customer_id" id="customer_id" class="hidden"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                </td>
                <td>
                        <?php echo Portal::language('type');?>
                        <select  name="type" title="" id="type"><?php
					if(isset($this->map['type_list']))
					{
						foreach($this->map['type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('type',isset($this->map['type'])?$this->map['type']:''))
                    echo "<script>$('type').value = \"".addslashes(URL::get('type',isset($this->map['type'])?$this->map['type']:''))."\";</script>";
                    ?>
	
                            
                        </select>
                </td>
                <td>
                    <input name="sub" type="submit" id="sub" value="<?php echo Portal::language('search');?>"/>
                </td>
            </tr>
           
            
        </table>
        </fieldset>
        </td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="5" width="100%" border="1" class="content" style="border-collapse: collapse;">
                <tr style="background: #eeeeee;">
                <?php if(Url::get('type')=='date'){ ?>
                    <td rowspan="2" style="text-align: center;" ><b><?php echo Portal::language('date');?></b></td>
                  <?php }else{?>  
                  <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('month');?></b></td>
                  <?php } ?>
                    <td colspan="3" style="text-align: center;"><b>FO</b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('hosekeeping');?></b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('restaurant');?></b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('shop');?></b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('spa');?></b></td>
                    <?php if($this->map['isset_karaoke']==1){?>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('karaoke');?></b></td>
                    <?php }?>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('drain');?></b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('tax');?></b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('total_amount');?></b></td>
                    <td rowspan="2" style="text-align: center;"><b><?php echo Portal::language('payment_amount');?></b></td>
                    <td rowspan="2" style="text-align: center;width:8% "><b><?php echo Portal::language('total_not_payment');?></b></td>
                   <td rowspan="2" style="text-align: center; width:5% " class="see"><b><?php echo Portal::language('see');?></b></td>
                </tr>
                 <tr style="background: #eeeeee;">
                    <td style="text-align: center;"><b><?php echo Portal::language('amount_room1');?><br /></b></td>
                    <td style="text-align: center; width :13% "><b><?php echo Portal::language('total_service_room');?><br/></b></td>
                    <td style="text-align: center;"><b><?php echo Portal::language('service_1');?></b></td>
                    
                </tr>
                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                <tr>
                    <td style="text-align: center;"><b><?php echo $this->map['items']['current']['id'];?></b></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['room'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['service_room'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['service_other'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['housekeeping'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['bar'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['vending'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['spa'])?></td>
                    <?php if($this->map['isset_karaoke']==1){?>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['karaoke'])?></td>
                    <?php }?>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total_before_tax'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['tax'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['total']))?></td>
                    <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['payment'])?></td>
                    <td style="text-align: right;"><?php echo System::display_number(round($this->map['items']['current']['not_payment']))?></td>
                    <td style="text-align: center;" class="see"><div onclick="url_revenue('<?php echo $this->map['items']['current']['start_date'];?>','<?php echo $this->map['items']['current']['end_date'];?>');"><img src="packages/core/skins/default/images/buttons/rate_list.gif" /></div></td>
                </tr>
                <?php }}unset($this->map['items']['current']);} ?>
                <tr style="background: #eeeeee;">
                    <td style="text-align: center;text-transform: uppercase;"><b><?php echo Portal::language('total_drain');?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_room'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_service_room'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_service_other'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_housekeeping'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_bar'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_vending'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_spa'])?></b></td>
                    <?php if($this->map['isset_karaoke']==1){?>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_karaoke'])?></b></td>
                    <?php }?>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_before_tax'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_tax'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number(round($this->map['total_total']))?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number($this->map['total_payment'])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number(round($this->map['total_not_payment']))?></b></td>
                    <td style="text-align: right;" class="see"><b></b></td>
                   
                   
                    
                </tr>
            </table>
        </td>
    
    </tr>
    <tr>
        <td>
            <table class="footer" collpadding="0" collspacing="5" width="100%" border="0"  style="border-collapse: collapse;" >
                <tr>
                    <td valign="top" style="padding-left: 2px;" > <b>Ý nghĩa :</b></td>
                </tr>
                <tr >
                    <td style="padding-left: 20px;">-Báo cáo tổng hợp doanh thu theo ngày thống kê doanh thu của tiền phòng và dịch vụ theo từng ngày phát sinh của những phòng sẽ thu tiền</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">-Phòng FOC và FOC All sẽ ko tính doanh thu (tiền = 0)</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">-Những phòng, dịch vụ Cancel sẽ không được tính doanh thu</td><br />
                </tr>
            </table>
        </td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
    
    function Autocomplete()
    {
        jQuery('#customer_name').autocomplete({
            url:'get_customer_search_fast.php?customer=1',
            onItemSelect:function(item)
            {
                document.getElementById('customer_id').value = item.data[0];
            }
            
        }); 
    }
    function delete_customer()
    {
        if(jQuery("#customer_name").val()=='')
        {
            jQuery("#customer_id").val('');
        }
    }
    function url_revenue(start_date,end_date)
    {
        var customer_id = jQuery("#customer_id").attr('value');
        var customer_name = jQuery("#customer_name").attr('value');
        window.open('?page=report_revenue_group_of_type_new&date_from='+start_date+'&date_to='+end_date+'&customer_id='+customer_id+'&customer_name='+customer_name)   
    }

</script>