<style>
	#genneral tr td{
        font-size:18px;
        line-height: 25px;
	}
    .genneral tr td{
        font-size:18px;
        line-height: 30px;	
    }
</style>
<div id="bound" style="width: 960px; margin:auto; margin-top:10px; padding-top:10px;">
    <table style="width: width: 100%; margin: 0px auto;">
        <tr style="width: 40%;"> 
            <td rowspan="3" style="text-align: left;"><img src="<?php echo HOTEL_LOGO; ?>" style="width: auto; height: 70px;" /></td>
        </tr>
        <tr>
            <td style="width: 50%;text-align: center;">
                <span style="font-size: 15pt; color: #ba1c00;" ><b><?php echo HOTEL_NAME; ?></b></span>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;font-size: 9pt; text-align: center;">
                <span style="color: #85200c;"><strong>Add: <?php echo HOTEL_ADDRESS; ?></strong></span><br /><span style="color: #85200c;"><strong>Tel: <?php echo HOTEL_PHONE; ?> - Fax: <?php echo HOTEL_FAX; ?></strong></span><br /><span style="color: #85200c;"><strong>Web: <?php echo HOTEL_WEBSITE; ?> - Email: <?php echo HOTEL_EMAIL; ?></strong></span>
            </td>
        </tr>
    </table>
    <br />
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="text-align: center;font-size: 19px;"><b>[[.DEPOSIT_INVOICE.]]</b></td>
        </tr>
    </table>
    <!--IF:cond_customer([[=customers=]] && !empty([[=customers=]]))-->
    <table cellpadding="0" width="100%" style="margin:auto; margin-top:15px;" cellspacing="0" id="genneral">
    <!--LIST:customers-->
    	<tr>    
        	<td width="121">[[.source.]]:</td>
            <td align="left">[[|customers.full_name|]]</td>
            <td width="121">[[.reservation_room_and_reservation.]]:</td>
            <td align="left">[[|booking_code|]]</td>
        </tr>
    <!--/LIST:customers-->
        <tr>   
            <td width="111">[[.print_by.]]:</td>
            <td align="left"><?php echo [[=person_print=]];?></td>
            <td width="111">[[.print_time.]]:</td>
            <td align="left"><?php echo date('h\h:i d/m/Y',time());?></td>
        </tr>
    </table>
    <br />
    <?php $total = 0; ?>
    <table style="width: 100%; margin: 0 auto; border-collapse: collapse;" border="1" class="genneral">
        <tr>
            <td width="350" align="center"><strong>[[.description.]]</strong></td>
            <td width="200" align="center"><strong>[[.time_dn.]]</strong></td>
            <td width="125" align="center"><strong>[[.pmtt.]]</strong></td>
            <td width="150" align="center"><strong>[[.amount.]]</strong></td>
            <td width="111" align="center"><strong>[[.receiver.]]</strong></td>
        </tr>
        <!--LIST:items-->
        <tr>
        	<td>[[|items.note|]]</td> 
        	<td><?php echo date('h\h:i d/m/Y',[[=items.time=]]);?></td>
            <td align="center"><?php echo Portal::language(strtolower([[=items.payment_type_id=]]));?></td> 
            <td align="right"><?php echo System::display_number([[=items.amount=]]).' (VND)'; $total +=[[=items.amount=]];?></td>
            <td width="50" align="center">[[|items.full_name|]]</td>  
        </tr>
        <!--/LIST:items-->
        <tr>
            <td colspan="3" style="text-align: right;"><strong>[[.total.]]</strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number($total) . ' (VND)'; ?></strong></td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <br />
    <table style="width: 100%; margin: 0 auto;" id="genneral">
        <tr>
            <td><strong><u>[[.note_deposit.]]:</u></strong></td>
        </tr>
        <br />
        <tr>
            <td><i class="fa fa-fw fa-dot-circle-o"></i> [[.note_deposit_1.]].</td>
        </tr>
        <tr>
            <td><i class="fa fa-fw fa-dot-circle-o"></i> [[.note_deposit_2.]].</td>
        </tr>
    </table>
    <br /><br />
    <table style="width: 100%; margin: 0 auto;" id="genneral">
        <tr>
            <td style="width: 50%; text-align: center;"><strong>[[.guest_deposit.]]</strong></td>
            <td style="width: 50%; text-align: center;"><strong>[[.receiver_deposit.]]</strong></td>
        </tr>
    </table>
</div>