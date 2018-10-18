<!------------------------------ HEADER ---------------------------------->
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title email_report">[[.email_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.email_report {font-size: 19px !important;}
@media print {
   #email_report {
    display: none;
   } 
}
</style>

<!------------------------------ SEARCH ---------------------------------->
<form method="POST" name="email_report" id="email_report">
    <div id="search_email" style="width: 1000px; margin: 0 auto;">
        <label>[[.from_date.]]</label><input name="date_from" type="text" id="date_from" onchange="changevalue();" />
        <label>[[.to_date.]]</label><input name="date_to" type="text" id="date_to" onchange="changefromday();" />
        <label>[[.type_mail.]]</label><select name="type_mail" id="type_mail"></select>
        <label>[[.email_status.]]</label><select name="email_status" id="email_status"></select>
        <label>[[.portal.]]</label><select name="portal_id" id="portal_id"></select>
        <input name="search" type="submit" id="search" value="search" />
    </div>
</form>
<!------------------------------ REPORT ---------------------------------->
<table style="width: 1000px; margin: 0 auto;"  cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound">
     <tr bgcolor="#EFEFEF">
        <td>[[.STT.]]</td>
        <td>[[.code_reservation.]]</td>
        <td>[[.expiration_date.]]</td>
        <td>[[.customer.]]</td>
        <td>[[.number_phone.]]</td>
        <td>[[.email.]]</td>
        <td>[[.email_to_address.]]</td>
        <td>[[.date_send_mail.]]</td>
        <td>[[.note.]]</td>
        <td>[[.status.]]</td>
     </tr>
     <?php $k=1 ?>
     <!--LIST:booking-->
     <tr bgcolor="#EFEFEF">
        <td><?php echo $k++ ?></td>
        <td><a target="_blank" href="?page=reservation&cmd=booking_confirm&id=[[|booking.id|]]&portal=default">[[|booking.id|]]</a></td>
        <td><?php echo Date_Time::convert_orc_date_to_date([[=booking.dealine_deposit=]]) ?></td>
        <td><a target="_blank" href="?page=customer&cmd=edit&id=[[|booking.customer_id|]]">[[|booking.ctm_name|]]</a></td>
        <td>[[|booking.ctm_phone|]]</td>
        <td>[[|booking.email|]]</td>
        <td>[[|booking.email_to_address|]]</td>
        <td><?php echo Date_Time::convert_orc_date_to_date([[=booking.date_send_mail=]]) ?></td>
        <td>[[|booking.note|]]</td>
        <?php if([[=booking.check_send_mail=]]==0){ ?>
            <td class="status_pending"><?php  echo Portal::language('pending') ?></td>
            <?php } if([[=booking.check_send_mail=]]==1){ ?>
            <td class="status_sent"><?php  echo Portal::language('sent') ?></td>
            <?php } if([[=booking.check_send_mail=]]==2){ ?>
            <td class="status_error" align="center">
                <span class="error">error</span>
            </td>
        <?php } ?>
     </tr>
     <!--/LIST:booking-->
</table>

<script type="text/javascript">
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
</script>



