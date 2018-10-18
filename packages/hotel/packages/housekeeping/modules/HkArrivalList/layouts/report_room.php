2<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}

</style>
<!---------HEADER----------->
<!--IF:first_page([[=real_page_no=]]==1)-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        Date: <?php echo date('d/m/Y H:i');?>
                        <br />
                        Printer: <?php $user_name =DB::fetch('select name_1 from party where user_id=\''.User::id().'\''); echo $user_name['name_1']  ;?>
                    
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.arrival_room_list.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.day.]]&nbsp;[[|day|]]
                            </span> 
                        </div>
                    </td>
                 </tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>
<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.date.]]</td>
                                	<td><input name="date" type="text" id="date"/></td>
                                    <td>[[.status.]]</td>
                                	<td><select name="status" id="status"></select></td>
                                    <td>[[.order_by_list.]]</td>
                                    <td><select name="order_by" id="order_by"></select></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
    }
);
</script>
<!--/IF:first_page-->
<!---------REPORT----------->	
<!--IF:check_room([[=real_room_count=]])-->
<table class="table_boder"  cellpadding="5" cellspacing="0" width="100%" border="" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="20px">[[.stt.]]</th>
        <th class="report_table_header" width="20px">[[.reservation_room_code.]]</th>
        <th class="report_table_header" width="20px">[[.room.]]</th>
        <th class="report_table_header" width="50px">[[.room_level.]]</th>
        <th class="report_table_header" width="120px">[[.tour.]], [[.company.]]</th>                        
        <th class="report_table_header" width="100px">[[.guest_name.]]</th>
        <th class="report_table_header" width="40px">[[.countries.]]</th>
        <th class="report_table_header" width="20px">[[.A/c.]]</th>
        <th class="report_table_header" width="50px">[[.arrival_date.]]</th>
        <th class="report_table_header" width="50px">[[.departure_date.]]</th>
        <th class="report_table_header" width="20px">[[.night.]]</th>
    </tr>
    <?php $i=1;?>
	<!--LIST:items-->
    <tr bgcolor="white">
       
        
       
        <td class="report_table_column" align="center"><div style="font-size:11px;">[[|items.stt_recode_1|]]</div></td>
        <td class="report_table_column" align="center"><div style="font-size:11px;">
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.reservation_room_code=]]));?>" target="_blank">[[|items.reservation_id|]]</a></div>
        </td>
        <td class="report_table_column" align="center"><div style="font-size:11px;">[[|items.room_name|]]</div></td>
        <td class="report_table_column" align="center"><div style="font-size:11px;">[[|items.brief_name|]]</div></td>
        
        <td class="report_table_column" style="white-space: normal; text-align: left; font-size:10px;">
            <div style="font-size:13px;"><b>[[|items.note|]]</b></div><i>[[|items.reservation_note|]]</i>
        </td>                  
		<td class="report_table_column" style="text-align: left;"><div style="font-size:11px;">[[|items.fullname|]]
        <!--<a href="<?php //echo Url::build('traveller',array('id'=>[[=items.traveller_id=]]));?>" target="_blank">[[|items.fullname|]]</a></div>-->
        </td>
        <td class="report_table_column" style="white-space: normal;" align="center"><div style="font-size:11px;">[[|items.country_code|]]</div></td>
        <td class="report_table_column" align="center"><div style="font-size:11px;">
            <!--Neu phong co khach thi cho ra so khach, neu khong thi cho ra so adult trong csdl-->
            <?php echo [[=items.adult=]]; ?>/<?php echo ([[=items.child=]]?[[=items.child=]]:0) ?></div>        </td>
	   <?php if([[=items.time_in=]]=='' &&  [[=items.time_in_room=]] != 0 ){?>
       <td class="report_table_column" rowspan="</?php echo $k; ?>" align="center"><div style="font-size:11px;">
			<?php echo date('d/m/Y H:i',[[=items.time_in_room=]]);?></div>        </td>
		<td class="report_table_column" rowspan="</?php echo $k; ?>" align="center"><div style="font-size:11px;">
			<?php echo date('d/m/Y H:i',[[=items.time_out_room=]]);?></div>        </td>
        <?php } ?>
        
         <?php if([[=items.time_in=]]!='' && [[=items.time_in=]]!= 0){?>                          
    		<td class="report_table_column" style="text-align: left;"><div style="font-size:11px;"><?php echo date('d/m/Y H:i',[[=items.time_in=]]);?> </div></td>
            <td class="report_table_column" style="white-space: normal;" align="center"><div style="font-size:11px;"><?php echo date('d/m/Y H:i',[[=items.time_out=]]);?></div></td>
        <?php }?>
        
		      <td class="report_table_column" align="center"><div style="font-size:11px;">[[|items.night|]]</div></td>
       
    </tr>
	<!--/LIST:items-->
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <!--IF:check(([[=room_count=]]!=[[=real_room_count=]]) && (([[=real_page_no=]]==[[=real_total_page=]])))-->
	<tr bgcolor="white">
		<td colspan="2" class="report_table_column"><strong><div style="font-size:10px;">[[.total.]]</div></strong></td>
		<td class="report_table_column" align="center"><strong><div style="font-size:10px;">[[|real_room_count|]]</div></strong></td>
        <td class="report_table_column">&nbsp;</td>
		<td colspan="3" class="report_table_column">&nbsp;</td>
        <td class="report_table_column" align="center"><strong><div style="font-size:10px;">[[|real_num_people|]]/[[|real_num_child|]]</div></strong></td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        <td class="report_table_column" align="center"><strong><div style="font-size:10px;">[[|real_night|]]</div></strong></td>
  	</tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
    		<td colspan="2" class="report_table_column"><strong><div style="font-size:10px;">[[.total.]]</div></strong></td>
    		<td class="report_table_column" align="center"><strong><div style="font-size:10px;">[[|real_room_count|]]</div></strong></td>
            <td class="report_table_column">&nbsp;</td>
   		  <td colspan="3" class="report_table_column">&nbsp;</td>
            <td class="report_table_column" align="center"><strong><div style="font-size:10px;">[[|real_num_people|]]/[[|real_num_child|]]</div></strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td class="report_table_column" align="center"><strong><div style="font-size:10px;">[[|real_night|]]</div></strong></td>
    	</tr>
    	<!--/IF:end_page-->
    <!--/IF:check-->
</table>
<!---------FOOTER----------->
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>
<!--IF:end_page(([[=page_no=]]==[[=total_page=]]) || ([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
        <td></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_room-->
