<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong> </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.traveller_report.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"> [[.day.]]&nbsp;[[|day|]] </span> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page([[=real_page_no=]]==1)-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
</div>
<!---------SEARCH----------->

<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search"> 
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto">
                                <tr>        
                                    <td>[[.line_per_page.]]</td>
                                    <td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                    <td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td><input type="submit" name="do_search" value="[[.report.]]" /></td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>


<!--/IF:first_page-->










<!---------REPORT----------->	

<!--IF:check_room([[=real_room_count=]])-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="25px">[[.stt.]]</th>
		<th class="report_table_header" width="35px">[[.reservation_room_code.]]</th>
        <th class="report_table_header" width="150px">[[.tour.]],[[.company.]]</th>
		<th class="report_table_header" width="150px">[[.customer_name.]]</th>
        <th class="report_table_header" width="30px">[[.gender.]]</th>
		<th class="report_table_header" width="40px">[[.nationality.]]</th>
        <th class="report_table_header" width="30px">[[.room.]]</th>
		<th class="report_table_header" width="70px">[[.arrival_date.]]</th>
		<th class="report_table_header" width="70px">[[.departure_date.]]</th>
        <th class="report_table_header" width="30px">[[.night.]]</th>
	</tr>
	
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
    
    <!--LIST:items-->
	<tr bgcolor="white">
        <?php
            $k = $this->map['count_traveller'][[[=items.reservation_room_code=]]]['num'];
            
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.stt|]]</td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.reservation_id=]]));?>" target="_blank">[[|items.reservation_id|]]</a>
        </td>
        <td class="report_table_column" style="white-space: normal; text-align: left;" rowspan="<?php echo $k; ?>">[[|items.note|]]</td>
        <?php
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?> 
        <td class="report_table_column" style="text-align: left;">
        <a href="<?php echo Url::build('traveller',array('id'=>[[=items.traveller_id=]]));?>" target="_blank">[[|items.fullname|]]</a>
        </td>
        <td align="center" class="report_table_column">[[|items.gender|]]</td>
		<td align="center" class="report_table_column">[[|items.country_code_1|]]</td>
        <?php
            $i++;
            }
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.room_name|]]</td>
		<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
			<?php echo date('d/m/Y H:i',[[=items.time_in=]]);?>
        </td>
		<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
			<?php echo date('d/m/Y H:i',[[=items.time_out=]]);?>
        </td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.night|]]</td>
        <?php
            }
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
        <?php
            $is_rowspan = true;
            } 
        ?>

        <?php
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>
        
	</tr>
	<!--/LIST:items-->
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <!--IF:check(([[=room_count=]]!=[[=real_room_count=]]) && (([[=real_page_no=]]==[[=real_total_page=]])))-->
	<tr bgcolor="white">
		<td colspan="2" class="report_table_column"><strong>[[.total.]]</strong></td>
		<td class="report_table_column"><strong></strong></td>
        <td align="center" class="report_table_column"><strong>[[|real_total_guest|]]</strong></td>
        
        <td colspan="2" class="report_table_column"></td>
        <td align="center" class="report_table_column">[[|real_room_count|]]</td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong>[[|real_night|]]</strong></td>
	</tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
    		<td colspan="2" class="report_table_column"><strong>[[.total.]]</strong></td>
    		<td class="report_table_column"><strong></strong></td>
            <td align="center" class="report_table_column"><strong>[[|real_total_guest|]]</strong></td>
            <td colspan="2" class="report_table_column"></td>
           
            <td align="center" class="report_table_column"><strong>[[|real_room_count|]]</strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong>[[|real_night|]]</strong></td>

    	</tr>
    	<!--/IF:end_page-->
    <!--/IF:check-->
    
    

</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">[[.page.]] [[|page_no|]]/[[|total_page|]]</div></center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]) || ([[=real_page_no=]]==[[=real_total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td >[[.quantity_of_guest_has_breakfast.]] ........... </td>
	<td  align="center"> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td align="center" width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_room-->


<style type="text/css">
th,td{white-space:nowrap;}
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
        jQuery('#from_day').datepicker();
        jQuery('#to_day').datepicker();
    }
);
</script>