<style>
    #show_print {
        display: none;
    }
    @media print
    {
      #show_print {
            display: inline;
        }
      #hide_print {
            display: none;
        }
    }
</style>
<form name="SummaryDebitReportForm" method="POST">
    <table cellpadding="5" style="width: 100%;">
        <tr>
            <td style="width: 100px;">
                <img src="<?php echo HOTEL_LOGO;?>" style="width: 100px;"/>
            </td>
            <td>
                <b><?php echo HOTEL_NAME;?></b><br />
				ADD: <?php echo HOTEL_ADDRESS;?><br />
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
            </td>
            <td style="text-align: right;">
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?><br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;"><h1 style="text-transform: uppercase;"><?php echo Portal::language('summary_deductible_report');?></h1></td>
        </tr>
        <tr id="hide_print">
            <td colspan="3" style="text-align: center;">
                <label><?php echo Portal::language('customer');?></label>
                <select  name="customer_id" id="customer_id" style="padding: 5px; max-width: 250px;"><?php
					if(isset($this->map['customer_id_list']))
					{
						foreach($this->map['customer_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))
                    echo "<script>$('customer_id').value = \"".addslashes(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))."\";</script>";
                    ?>
	</select>
                <label><?php echo Portal::language('from_date');?></label>
                <input  name="from_date" id="from_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                <label><?php echo Portal::language('to_date');?></label>
                <input  name="to_date" id="to_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                <label><?php echo Portal::language('account');?></label>
                <select  name="user_id" id="user_id" style="padding: 5px; max-width: 150px;"><?php
					if(isset($this->map['user_id_list']))
					{
						foreach($this->map['user_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))
                    echo "<script>$('user_id').value = \"".addslashes(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))."\";</script>";
                    ?>
	</select>
                <label><?php echo Portal::language('payment_type');?></label>
                <select  name="payment_type_id" id="payment_type_id" style="padding: 5px; max-width: 150px;"><?php
					if(isset($this->map['payment_type_id_list']))
					{
						foreach($this->map['payment_type_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_type_id',isset($this->map['payment_type_id'])?$this->map['payment_type_id']:''))
                    echo "<script>$('payment_type_id').value = \"".addslashes(URL::get('payment_type_id',isset($this->map['payment_type_id'])?$this->map['payment_type_id']:''))."\";</script>";
                    ?>
	</select>
                <input value="<?php echo Portal::language('search');?>" type="submit" style="padding: 5px;" />
            </td>
        </tr>
    </table>
    <table id="show_print" cellpadding="5" style="width: 100%;">
        <tr>
            <td style="text-align: center;">
                <?php echo Portal::language('from_date');?>: <?php echo $this->map['from_date'];?> - <?php echo Portal::language('to_date');?>: <?php echo $this->map['to_date'];?>
            </td>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table cellpadding="5" border="1" bordercolor="#CCCCCC" style="width: 100%; margin: 10px auto;">
    <tr style="text-align: center; background: #EEEEEE;">
        <th><?php echo Portal::language('stt');?></th>
        <th><?php echo Portal::language('date');?></th>
        <th><?php echo Portal::language('customer');?></th>
        <th><?php echo Portal::language('price');?></th>
        <th><?php echo Portal::language('description');?></th>
        <th><?php echo Portal::language('recode');?></th>
        <th><?php echo Portal::language('booking_code');?></th>
        <th><?php echo Portal::language('folio');?></th>
        <th><?php echo Portal::language('bar_reservation_code');?></th>
        <th><?php echo Portal::language('ve_reservation_code');?></th>
        <th><?php echo Portal::language('mice');?></th>
        <th><?php echo Portal::language('mice_invoice_code');?></th>
        <th><?php echo Portal::language('payment_type');?></th>
        <th><?php echo Portal::language('account');?></th>
    </tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr style="text-align: center;">
        <td><?php echo $this->map['items']['current']['stt'];?></td>
        <td><?php echo $this->map['items']['current']['in_date'];?></td>
        <td><?php echo $this->map['items']['current']['customer_name'];?></td>
        <td style="text-align: right;"><?php echo $this->map['items']['current']['price'];?></td>
        <td><?php echo $this->map['items']['current']['description'];?></td>
        <td><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id'];?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
        <td><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id'];?>"><?php echo $this->map['items']['current']['booking_code'];?></a></td>
        <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_folio'];?>"><?php echo $this->map['items']['current']['folio_id'];?></a></td>
        <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_bar'];?>"><?php echo $this->map['items']['current']['bar_reservation_code'];?></a></td>
        <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_vend'];?>"><?php echo $this->map['items']['current']['ve_reservation_code'];?></a></td>
        <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['items']['current']['mice_reservation_id'];?>"><?php echo $this->map['items']['current']['mice_reservation_id'];?></a></td>
        <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_mice'];?>"><?php echo $this->map['items']['current']['mice_invoice_code'];?></a></td>
        <td><?php echo $this->map['items']['current']['payment_type_name'];?></td>
        <td><?php echo $this->map['items']['current']['user_name'];?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr style="text-align: right; background: #EEEEEE;">
        <th colspan="3"><?php echo Portal::language('total');?></th>
        <th style="text-align: right;"><?php echo System::display_number($this->map['total']); ?></th>
        <th colspan="10"></th>
    </tr>
</table>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
            }
        }) ;
    }
</script>