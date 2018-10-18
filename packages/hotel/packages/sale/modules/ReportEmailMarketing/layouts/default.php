<!--IF:first_page([[=page_no=]]==[[=start_page=]] or [[=page_no=]] == 0 )-->
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
                            <font class="report_title email_report">[[.email_report_maketing.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>


<form method="POST" name="email_report" id="email_report">
<!------------------------------ SEARCH ---------------------------------->
    <div id="search_email" style="width: 100%; margin: 0 auto; font-size: 11px;">
        <label>[[.line_per_page.]]</label><input name="line_per_page" type="text" id="line_per_page" value="100" size="4" maxlength="20" style="text-align:right"/>
        <label>[[.no_of_page.]]</label><input name="total_page" type="text" id="total_page" value="500" size="4" maxlength="2" style="text-align:right"/>
        <!-- <label>[[.from_page.]]</label><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/>-->
        <label>[[.from_date.]]</label><input name="date_from" type="text" id="date_from" onchange="changevalue();" />
        <label>[[.to_date.]]</label><input name="date_to" type="text" id="date_to" onchange="changefromday();" />
        <label>[[.type_email.]]</label><select name="group_event" id="group_event"></select>
        <label>[[.email_status.]]</label><select name="email_status" id="email_status"></select>
        <input name="search" type="submit" id="search" value="search" />
    </div>
<!--/IF:first_page-->    
</form>

<!------------------------------ REPORT ------------------------------------>
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<table style="width: 100%; margin: 0 auto;"  cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound">
    <tr bgcolor="#EFEFEF">
        <td>[[.STT.]]</td>
        <td>[[.customer.]]</td>
        <td>[[.email.]]</td>
        <td>[[.phone.]]</td>
        <td>[[.date_send.]]</td>
        <td >[[.status.]]</td>
    </tr>
    <?php 
    $k=0;
    ?>
    <!--LIST:items-->    
<!--IF:first_page([[=page_no=]]!=1)-->    
    <tr>
        <td><?php echo ++$k; ?></td>
        <td>
        <?php if(!empty([[=items.tra_id=]])){ ?>
         <a target="_blank" href="?page=traveller&cmd=edit&id=[[|items.tra_id|]]">
         <?php }else{ ?>
          <a target="_blank" href="?page=customer&cmd=edit&id=[[|items.cus_id|]]">
          <?php } ?>
         [[|items.full_name|]]</a>
        </td>
        <td>[[|items.email|]]</td>
        <td>[[|items.phone|]]</td>
        <td><?php echo Date_time::convert_orc_date_to_date([[=items.date_send=]])?></td>
        <td>
        <?php if([[=items.status=]]==0) echo 'Pending';
              elseif([[=items.status=]]==2) echo 'error';
              else echo 'sent' ?>
        </td>
    </tr>
  
    <!--/LIST:items-->
</table>
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>
<!--IF:end_page(([[=page_no=]]==[[=total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.creator.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<!--/IF:check_data-->
<script>
jQuery("#date_from").datepicker();
jQuery("#date_to").datepicker();
</script>
