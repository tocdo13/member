<style>
    *{
        line-height: 20px;
    }
    #report_content table tr{
        background: #fff;
    }
    #report_content table tr:hover{
        background: #eee;
    }
</style>
<form name="SammaryCustomerGroupReportForm" method="post" >
<table id="export">
    <tr>
        <td>
        <div id="header_report" style="margin: 5px auto; width: 90%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 55px; text-align: center;"><div style="border-radius: 50%; width: 50px; height: 50px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 50px; height: auto;" /></div></td>
            <td style="width: 200px; text-align: left;">
                <p style="text-transform: uppercase;"><span style="font-size: 13px; font-weight: bold;"><?php echo HOTEL_NAME; ?></span><br />
                    <i><?php echo Portal::language('room_sale');?></i>
                </p>
            </td>
            <td style="text-align: center;">
                <h1 style="text-transform: uppercase; font-size: 19px;"><?php echo Portal::language('summary_customer_group_report');?></h1>
                <i><?php echo Portal::language('date_from');?> <?php echo $_REQUEST['date_from']; ?> <?php echo Portal::language('date_to');?> <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 255px; text-align: right;">
                <p><?php echo Portal::language('saler');?>: <i><?php echo Portal::language('all');?></i><br />
                    <?php echo Portal::language('customer_group');?>: <i><?php echo $_REQUEST['group_id']; ?></i>
                </p>
            </td>
        </tr>
    </table>
</div>
<!-- end header --!>

<div style="text-align: center;"> <input name="export_repost" type="submit" id="export_repost"  value="<?php echo Portal::language('export');?>"  /></div>
<div id="report_content" style="width: 90%; margin: 10px auto;" >
    <table border=1 cellSpacing=0 style="width: 100%;"  >
        <tr style="background: #000000;">
            <th rowspan="2" style="color: #fff; text-align: center;"><?php echo Portal::language('stt');?></th>
            <th rowspan="2" style="color: #fff; text-align: center;"><?php echo Portal::language('company_name');?></th>
            <th colspan="3" style="color: #fff; text-align: center;"><?php echo Portal::language('quantity');?></th>
            <th rowspan="2" style="color: #fff; text-align: center;"><?php echo Portal::language('revenue');?></th>
        </tr>
        <tr style="background: #000000;">
            <th style="color: #fff; text-align: center;"><?php echo Portal::language('number_night');?></th>
            <th style="color: #fff; text-align: center;"><?php echo Portal::language('number_adult');?></th>
            <th style="color: #fff; text-align: center;"><?php echo Portal::language('number_child');?></th>
        </tr>
        <?php $stt = 0; $total_room = 0; $total_child=0; $total_adult=0; $total_amount = 0; ?> 
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr style="background: silver; font-weight: bold;">
                <td colspan="6"><?php echo $this->map['items']['current']['name'];?></td>
            </tr>
            <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
                <tr>
                    <td><?php echo ++$stt; ?></td>
                    <td><?php echo $this->map['items']['current']['child']['current']['name'];?></td>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['child']['current']['total_room'];?></td>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['child']['current']['total_adult'];?></td>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['child']['current']['total_child'];?></td>
                    <td style="text-align: right; font-weight: bold;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['child']['current']['total_amount']); ?></td>
                </tr>
            <?php }}unset($this->map['items']['current']['child']['current']);} ?>
            <tr style="background: #dddddd; font-weight: bold;">
                <td colspan="2" style="text-align: right;"><?php echo Portal::language('total_amount');?> <?php echo $this->map['items']['current']['name'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['total_room'];?><?php $total_room += $this->map['items']['current']['total_room']; ?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['total_adult'];?><?php $total_adult += $this->map['items']['current']['total_adult']; ?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['total_child'];?><?php $total_child += $this->map['items']['current']['total_child']; ?></td>
                <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['total_amount']); $total_amount += $this->map['items']['current']['total_amount']; ?></td>
            </tr>
        <?php }}unset($this->map['items']['current']);} ?>
        <tr style="background: silver; height: 30px; font-weight: bold;">
            <td colspan="2" style="text-align: center;"><?php echo Portal::language('total');?></td>
            <td style="text-align: center;"><?php echo $total_room; ?></td>
            <td style="text-align: center;"><?php echo $total_adult; ?></td>
            <td style="text-align: center;"><?php echo $total_child; ?></td>
            <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($total_amount); ?></td>
        </tr>
    </table>
</div><!-- end report --!>


<div style="margin-left: 50px;">
    <?php echo Portal::language('description');?>: <br />
    -(1) <?php echo Portal::language('description_summary_customer_report_1');?><br />
    -(2) <?php echo Portal::language('description_summary_customer_report_2');?><br />
   -(3) <?php echo Portal::language('description_summary_customer_report_3');?><br />
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</td>
    </tr>
</table>

<script>
    jQuery("#export_repost").click(function(){
        
        jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())); 
        });
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table' 
        });
    })

</script>
