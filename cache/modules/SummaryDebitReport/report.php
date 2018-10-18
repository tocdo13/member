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
            <th colspan="3" style="text-align: center;"><h1 style="text-transform: uppercase;"><?php echo Portal::language('summary_debit_report');?></h1></th>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <label><?php echo Portal::language('customer');?></label>
                <input  name="customer_name" id="customer_name" onfocus="Autocomplete();" autocomplete="off" style="padding: 5px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                <input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onclick="$('customer_name').value='';$('customer_id').value='';" style="cursor:pointer;"/>
                <label><?php echo Portal::language('from_date');?></label>
                <input  name="from_date" id="from_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                <label><?php echo Portal::language('to_date');?></label>
                <input  name="to_date" id="to_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                <input value="<?php echo Portal::language('search');?>" type="submit" style="padding: 5px;" />
            </td>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table cellpadding="5" border="1" bordercolor="#CCCCCC" style="width: 100%; margin: 10px auto;">
    <tr style="text-align: center; background: #EEEEEE;">
        <th><?php echo Portal::language('stt');?></th>
        <th><?php echo Portal::language('name');?></th>
        <th><?php echo Portal::language('lastest_debit');?></th>
        <th><?php echo Portal::language('total_debit');?></th>
        <th><?php echo Portal::language('paied');?></th>
        <th><?php echo Portal::language('outstanding_debt_last');?></th>
        <th><?php echo Portal::language('detail');?></th>
    </tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr style="text-align: center;">
        <td><?php echo $this->map['items']['current']['stt'];?></td>
        <td><?php echo $this->map['items']['current']['name'];?></td>
        <td style="text-align: right;"><?php echo $this->map['items']['current']['debit_last_period_before'];?></td>
        <td style="text-align: right;"><?php echo $this->map['items']['current']['debit_in_period'];?></td>
        <td style="text-align: right;"><?php echo $this->map['items']['current']['review'];?></td>
        <td style="text-align: right;"><?php echo $this->map['items']['current']['debit'];?></td>
        <td><a href="?page=summary_debit_report&cmd=detail&customer_id=<?php echo $this->map['items']['current']['id'];?>&from_date=<?php echo $this->map['from_date'];?>&to_date=<?php echo $this->map['to_date'];?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr style="text-align: right; background: #EEEEEE;">
        <th colspan="2"><?php echo Portal::language('total');?></th>
        <th style="text-align: right;"><?php echo $this->map['debit_last_period_before'];?></th>
        <th style="text-align: right;"><?php echo $this->map['debit_in_period'];?></th>
        <th style="text-align: right;"><?php echo $this->map['review'];?></th>
        <th style="text-align: right;"><?php echo $this->map['debit'];?></th>
        <th></th>
    </tr>
</table>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast_son.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
            }
        }) ;
    }
</script>