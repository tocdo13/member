<script>full_screen();</script>
<table style="width: 98%; margin: 5px auto;">
    <tr>
        <td style="text-align: left;">
            <strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?><br />
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
        </td>
        <td style="text-align: right;">
            <strong><?php echo Portal::language('template_code');?></strong><br />
			<i><?php echo Portal::language('promulgation');?></i>
            <br />
            <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; text-transform: uppercase;">
        <h1>
            <?php if(Url::get('type')==1){ ?>
            <?php echo Portal::language('housekeeping_revenue_report_minibar');?><br />
            <?php }
            else { ?>
            <?php echo Portal::language('housekeeping_revenue_report_laundry');?><br />
            <?php } ?>
        </h1>
        <?php if(Url::get('search_time')){ ?>
            <?php echo Portal::language('from');?> <?php echo $this->map['start_shift_time'];?> <?php echo $this->map['date_from'];?> <?php echo Portal::language('to');?> <?php echo $this->map['end_shift_time'];?> <?php echo $this->map['date_to'];?><br />
         <?php } ?>   
            <?php echo Portal::language('view_in');?>: <?php if($this->map['group']==1){ ?><?php echo Portal::language('product');?> <?php }else{ ?><?php echo Portal::language('invoice');?> <?php } ?>
            <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if($this->map['from_bill']!=''){ ?> <?php echo Portal::language('from_bill');?> <?php echo $this->map['from_bill']; } ?> <?php if($this->map['to_bill']!=''){ ?> <?php echo Portal::language('to');?> <?php echo $this->map['to_bill']; } ?> 
        </div>
        </td>
    </tr>
</table>