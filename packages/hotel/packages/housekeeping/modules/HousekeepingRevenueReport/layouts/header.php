<script>full_screen();</script>
<table style="width: 98%; margin: 5px auto;">
    <tr>
        <td style="text-align: left;">
            <strong><?php echo HOTEL_NAME;?></strong><br />
			[[.address.]]: <?php echo HOTEL_ADDRESS;?><br />
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
        </td>
        <td style="text-align: right;">
            <strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
            <br />
            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; text-transform: uppercase;">
        <h1>
            <?php if(Url::get('type')==1){ ?>
            [[.housekeeping_revenue_report_minibar.]]<br />
            <?php }
            else { ?>
            [[.housekeeping_revenue_report_laundry.]]<br />
            <?php } ?>
        </h1>
        <?php if(Url::get('search_time')){ ?>
            [[.from.]] [[|start_shift_time|]] [[|date_from|]] [[.to.]] [[|end_shift_time|]] [[|date_to|]]<br />
         <?php } ?>   
            [[.view_in.]]: <?php if([[=group=]]==1){ ?>[[.product.]] <?php }else{ ?>[[.invoice.]] <?php } ?>
            <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if([[=from_bill=]]!=''){ ?> [[.from_bill.]] <?php echo [[=from_bill=]]; } ?> <?php if([[=to_bill=]]!=''){ ?> [[.to.]] <?php echo [[=to_bill=]]; } ?> 
        </div>
        </td>
    </tr>
</table>