<style>
.simple-layout-middle{width:100%;}
</style>
<!-----------------RECORD-------------->
<table cellSpacing=0 width="98%" style=" margin: 10px auto;" id="export">
	<tr>
    	 <td align="left" width="100%"><strong><?php echo HOTEL_NAME;?></strong><br />
	<?php echo HOTEL_ADDRESS;?></strong></td>
    </tr>
    <tr valign="top">
		<td width="100%" align="center" valign="middle">
          <font class="report_title">[[.<font class="report-title"></font>.]][[.reservation_summary_by_seller.]]<br /><br />
		  <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
			[[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
  		<br>
	      </span>
      </td>
	</tr>
    <tr>
        <td>
            <div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]"  /></div>
            <table id="myTable" class="tablesorter" cellpadding="5" cellspacing="0" border="1" bordercolor="#555555" style="width: 98%; margin: 0px auto;" id="export">
                <thead>
                <tr style="text-align: center; font-weight: bold; height: 30px; background: #EFEFEF;">
                    <td>[[.stt.]]</td>
                    <td>[[.recode.]]</td>
                    <td>[[.source.]]</td>
                    <td>[[.booking_code.]]</td>
                    <td>[[.room.]]</td>
                    <td>[[.Guest.]]</td>
                    <td>[[.arr_date.]]</td>
                    <td>[[.dep_date.]]</td>
                    <td style="width: 20px;">[[.prs.]]</td>
                    <td>[[.room_level.]]</td>
                    <td style="width: 20px;">[[.nts.]]</td>
                    <td>[[.price.]]</td>
                    <td>[[.total.]]</td>
                    <td>[[.status.]]</td>
                    <td>[[.user.]]</td>
                    <td>[[.booked_date.]]</td>
                </tr>
                </thead>
                <tbody>
                <?php $stt=0; $total_night=0; ?>
                <!--LIST:items-->
                    <tr>
                        <td><?php echo ++$stt; ?></td>
                        <td rowspan="[[|items.count_child|]]"><a href="?page=reservation&cmd=edit&id=[[|items.id|]]">[[|items.id|]]</a></td>
                        <td rowspan="[[|items.count_child|]]">[[|items.customer_name|]]</td>
                        <td rowspan="[[|items.count_child|]]">[[|items.booking_code|]]</td>
                        <!--LIST:items.child-->
                            <?php $child_id=[[=items.child.id=]]; ?>
                            <td>[[|items.child.room_name|]]</td>
                            <td>[[|items.child.traveller_name|]]</td>
                            <td>[[|items.child.arrival_time|]]</td>
                            <td>[[|items.child.departure_time|]]</td>
                            <td>[[|items.child.adult|]]</td>
                            <td>[[|items.child.room_level|]]</td>
                            <td>[[|items.child.night|]]</td><?php $total_night+=[[=items.child.night=]]; ?>
                            <td style="text-align: right;">[[|items.child.price|]]</td>
                            <td style="text-align: right;">[[|items.child.total|]]</td>
                            <td>[[|items.child.status|]]</td>
                            <td>[[|items.child.user_name|]]</td>
                            <?php break; ?>
                        <!--/LIST:items.child-->
                        <td rowspan="[[|items.count_child|]]">[[|items.create_date|]]</td>
                    </tr>
                    <?php if([[=items.count_child=]]>1){ ?>
                        <!--LIST:items.child-->
                            <?php if($child_id!=[[=items.child.id=]]){ ?>
                            <tr>
                            <td><?php echo ++$stt; ?></td>
                            <td>[[|items.child.room_name|]]</td>
                            <td>[[|items.child.traveller_name|]]</td>
                            <td>[[|items.child.arrival_time|]]</td>
                            <td>[[|items.child.departure_time|]]</td>
                            <td>[[|items.child.adult|]]</td>
                            <td>[[|items.child.room_level|]]</td>
                            <td>[[|items.child.night|]]</td><?php $total_night+=[[=items.child.night=]]; ?>
                            <td style="text-align: right;">[[|items.child.price|]]</td>
                            <td style="text-align: right;">[[|items.child.total|]]</td>
                            <td>[[|items.child.status|]]</td>
                            <td>[[|items.child.user_name|]]</td>
                            </tr>
                            <?php } ?>
                        <!--/LIST:items.child-->
                    <?php } ?>
                <!--/LIST:items-->
                </tbody>
                <tr style="text-align: center; font-weight: bold; height: 30px; background: #eeeeee;">
                    <td>[[.total.]]</td>
                    <td><?php echo [[=summary=]]['total_recode']; ?></td>
                    <td colspan="6"></td>
                    <td><?php echo [[=summary=]]['total_adult']; ?></td>
                    <td></td>
                    <td><?php echo $total_night; ?></td>
                    <td></td>
                    <td style="text-align: right;"><?php echo number_format([[=summary=]]['total_amount']); ?></td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </td>
    </tr>	
</table>
<script>
jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>
