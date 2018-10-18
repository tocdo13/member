<style>
    a{
        text-decoration: none;
        color: #00b2f9;
    }
    a:hover{
        text-decoration: none;
        color: #00b2f9;
    }
    a:active{
        color: #00b2f9;
    }
</style>
<form name="HistoryMemberForm" method="post">
    <div id="search" style="width: 960px; margin: 0px auto;">
       <fieldset>
            <legend><?php echo Portal::language('search');?></legend>
            <table cellSpacing="0" cellpadding="5" style="width: 100%;">
                <tr>
                    <th style="width: 20%;"><?php echo Portal::language('invoice_code');?>:</th>
                    <th style="width: 20%;"><?php echo Portal::language('member_code');?>:</th>
                    <th style="width: 20%;"><?php echo Portal::language('member_name');?>:</th>
                    <th style="width: 20%;"><?php echo Portal::language('from_date');?>:</th>
                    <th style="width: 20%;"><?php echo Portal::language('to_date');?>:</th>
                </tr>
                <tr>
                    <td><input  name="invoice_code" id="invoice_code" style="width: 100%; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('invoice_code'));?>"></td>
                    <td><input  name="member_code" id="member_code" style="width: 100%; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('member_code'));?>"></td>
                    <td><input  name="member_name" id="member_name" style="width: 100%; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('member_name'));?>"></td>
                    <td><input  name="from_date" id="from_date" style="height: 25px; width: 120px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"> <input  name="from_time" id="from_time" style="height: 25px; width: 30px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>"></td>
                    <td><input  name="to_date" id="to_date" style=" height: 25px; width: 120px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"> <input  name="to_time" id="to_time" style="height: 25px; width: 30px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>"></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;"><input name="do_search" type="submit" value="  <?php echo Portal::language('search');?>  " /></td>
                </tr>
            </table>
       </fieldset> 
    </div>
    <div id="print" style="width: 100%;">
        <div style="width: 100%; text-align: center; text-transform: uppercase;"><h1><?php echo Portal::language('history_member');?></h1></div>
        <div style="width: 100%; margin: 10px auto; float: left;"><?php echo $this->map['paging'];?></div>
        <table cellSpacing="0" cellpadding="5" border="1" style="width: 98%; margin: 1%;">
            <tr style="background: #cccccc; text-transform: uppercase;">
                <th style="text-align: center; width: 20px; height: 35px;"><?php echo Portal::language('stt');?></th>
                <th style="text-align: center;"><?php echo Portal::language('payment_type_id');?></th>
                <th style="text-align: center;"><?php echo Portal::language('price');?></th>
                <th style="text-align: center;"><?php echo Portal::language('change_price');?></th>
                <th style="text-align: center;"><?php echo Portal::language('point');?></th>
                <th style="text-align: center;"><?php echo Portal::language('detail_point');?></th>
            </tr>
            <?php if(isset($this->map['list_items']) and is_array($this->map['list_items'])){ foreach($this->map['list_items'] as $key1=>&$item1){if($key1!='current'){$this->map['list_items']['current'] = &$item1;?>
                <tr>
                    <td colspan="6" style="padding-left: 20px; font-size: 13px; font-weight: bold; text-align: left; background: #eeeeee; height: 30px;"><?php echo Portal::language('member_code');?>: <?php echo $this->map['list_items']['current']['member_code'];?> - <?php echo Portal::language('traveller_name');?>: <?php echo $this->map['list_items']['current']['full_name'];?> - <?php echo Portal::language('time');?>: <?php echo $this->map['list_items']['current']['create_time'];?> - <?php echo Portal::language('user_id');?>: <?php echo $this->map['list_items']['current']['user_id'];?>
                    <br />
                    <?php echo Portal::language('type');?>: <?php echo $this->map['list_items']['current']['type'];?> - <?php if($this->map['list_items']['current']['type']=='RESERVATION'){ ?>  recode: <?php }else{ ?> <?php echo Portal::language('recode');?> <?php } ?> [ <a href="<?php echo $this->map['list_items']['current']['link_recode'];?>" target="_blank"><?php echo $this->map['list_items']['current']['bill_id'];?></a> ] - <?php if($this->map['list_items']['current']['type']=='RESERVATION'){ ?>  Folio/Group Folio: <?php }else{ ?> <?php echo Portal::language('view');?>: <?php } ?> [ <a href="<?php echo $this->map['list_items']['current']['link_invoice'];?>" target="_blank"><?php if($this->map['list_items']['current']['type']=='RESERVATION'){ ?>  <?php echo $this->map['list_items']['current']['folio_id'];?> <?php }else{ ?> <?php echo Portal::language('invoice');?> <?php } ?> </a> ]
                    </td>
                </tr>
                <?php $stt=0; ?>
                <?php if(isset($this->map['list_items']['current']['child']) and is_array($this->map['list_items']['current']['child'])){ foreach($this->map['list_items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['list_items']['current']['child']['current'] = &$item2;?>
                    <?php $stt++; ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $stt; ?></td>
                        <td><?php echo $this->map['list_items']['current']['child']['current']['payment_type_id'];?></td>
                        <td><?php echo $this->map['list_items']['current']['child']['current']['price'];?></td>
                        <td><?php echo $this->map['list_items']['current']['child']['current']['change_price'];?></td>
                        <td><?php echo $this->map['list_items']['current']['child']['current']['point'];?></td>
                        <td><?php echo $this->map['list_items']['current']['child']['current']['detail'];?></td>
                    </tr>
                <?php }}unset($this->map['list_items']['current']['child']['current']);} ?>
            <?php }}unset($this->map['list_items']['current']);} ?>
        </table>
        <div style="width: 100%; margin: 10px auto; float: left;"><?php echo $this->map['paging'];?></div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>