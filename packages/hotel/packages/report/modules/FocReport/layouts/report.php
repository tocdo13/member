<?php //System::debug([[=items=]]); ?>
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong>
        <br />
        
        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo User::id();?>
       
         </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.foc_report.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php echo [[=from_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=from_date=]] ?> - <?php echo [[=to_time=]];?>&nbsp;[[.day.]]&nbsp;<?php echo [[=to_date=]] ?></span> </div></td>
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
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
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
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue()"/></td>
                                    
                                    <td>[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday()"/></td>
                                    <td  align="left" nowrap="nowrap">[[.user_status.]]</td>
                       			    <td>
                                      <!-- 7211 -->  
                                      <select style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                                        <option value="1">Active</option>
                                        <option value="0">All</option>
                                      </select>
                                      <!-- 7211 end--> 
                                    </td>
                    			    <td>[[.by_user.]]</td>
                    			    <td><select name="user_id" id="user_id"></select></td>
                                    <td>[[.from_time.]]</td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 50px;"/></td>
                                    <td>[[.to_time.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>
                                    <td><input type="submit" name="do_search" value="[[.report.]]" onclick="return check_search();"/></td>
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
	    <th rowspan="2" class="report-table-header">[[.no.]]</th>
        <th rowspan="2" class="report-table-header">[[.recode.]]</th>
        <th rowspan="2" class="report-table-header">[[.room.]]</th>
        <th rowspan="2" class="report-table-header">[[.folio_id.]]</th>
        <th rowspan="2" class="report-table-header">[[.guest_name.]]</th>
        <th rowspan="2" class="report-table-header" >[[.arrival_date.]]</th>
        <th rowspan="2" class="report-table-header" >[[.departure_date.]]</th>
        <th rowspan="2" class="report-table-header" >[[.night.]]</th>  
        <th rowspan="2" class="report-table-header">[[.room_rate.]](VND)</th>
        <th rowspan="2" class="report-table-header">[[.room_rate.]]<br />(USD)</th>
        <th rowspan="2" class="report-table-header">[[.room_price_total.]]</th>
        <th rowspan="2" class="report-table-header">[[.extra_bed.]]</th>
        <th  class="report-table-header">[[.rest.]]</th>
        <th rowspan="2" class="report-table-header">[[.minibar.]]</th>
        <th rowspan="2" class="report-table-header">[[.laundry.]]</th>
        <th rowspan="2" class="report-table-header">[[.telephone.]]</th>
        <th rowspan="2" class="report-table-header">[[.compensation.]]</th>
        <th rowspan="2" class="report-table-header">[[.spa.]]</th>
        <th rowspan="2" class="report-table-header">[[.tour1.]]</th>
        <th rowspan="2" class="report-table-header">[[.other.]]</th>
        <th rowspan="2" class="report-table-header" style="background-color:#FFFF99;">[[.total.]]</th>
        
           
	</tr>
    <tr>
        <!--<th width="60px" class="report-table-header">[[.break_fast.]]</th>-->
        <th width="60px" class="report-table-header">[[.F&B.]]</th>
    </tr>
    <!---<tr>
        <th width="60px" class="report-table-header">[[.VND.]]</th>
        <th width="60px" class="report-table-header">[[.VND.]]</th>
        <th width="60px" class="report-table-header">[[.VND.]]</th>
        <th width="40px"  class="report-table-header">[[.USD.]]</th>
    </tr>--->
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
    <!--LIST:items-->
	<tr bgcolor="white">
        <?php
            $k = $this->map['count_room'][[[=items.code=]]]['num'];
            if($is_rowspan == false)
            {
        ?>
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.stt|]]</td>
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <a target="_blank" href="<?php echo "?page=reservation&layout=list&cmd=edit&id=".[[=items.reservation_id=]]; ?>">[[|items.reservation_id|]]</a>
            </td>
        <?php
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
            <td class="report_table_column" style="text-align: center;">[[|items.room_name|]]</td>
        <?php
           }
        ?>
         <?php 
            if($is_rowspan == false)
            {
        ?>
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <a target="_blank" href="<?php echo ([[=items.customer_id=]] !=''?Url::build('view_traveller_folio',array('folio_id'=>[[=items.code=]],'id'=>[[=items.reservation_id=]],'cmd'=>'group_invoice','customer_id'=>[[=items.customer_id=]])):Url::build('view_traveller_folio',array('traveller_id'=>[[=items.traveller_id=]],'folio_id'=>[[=items.code=]])));?>">
            <?php if(isset([[=items.folio_code=]])){?>
                <?php echo 'No.F'.str_pad([[=items.folio_code=]],6,"0",STR_PAD_LEFT) ;?>
            <?php } else {?>
                <?php echo 'Ref'.str_pad([[=items.code=]],6,"0",STR_PAD_LEFT) ;?>
            <?php }?>
            </a>
            <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.guest_name|]]</td>
           
        <?php
            }
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
             <td align="center" class="report_table_column"><?php echo ([[=items.time_in=]]==0?'':date('d/m/Y',[[=items.time_in=]]));?></td>
            <td align="center" class="report_table_column" ><?php echo ([[=items.time_out=]]==0?'':date('d/m/Y',[[=items.time_out=]]));?></td>
            <td align="center" class="report_table_column" ><?php echo System::Display_number(round(([[=items.time_out=]]-[[=items.time_in=]])/(24*3600)));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number([[=items.price=]]);?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number([[=items.price=]]/RES_EXCHANGE_RATE);?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.room=]]==0?'':System::Display_number(round([[=items.room=]]))); ?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.extra_bed=]]==0?'':System::Display_number(round([[=items.extra_bed=]])));?></td>
            <!--<td class="report_table_column" style="text-align: right;"></?php echo ([[=items.break_fast=]]==0?'':System::Display_number(round([[=items.break_fast=]])));?></td>-->
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.restaurant=]]==0?'':System::Display_number(round([[=items.restaurant=]])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.minibar=]]==0?'':System::Display_number(round([[=items.minibar=]]))); ?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.laundry=]]==0?'':System::Display_number(round([[=items.laundry=]])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.telephone=]]==0?'':System::Display_number(round([[=items.telephone=]])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.equip=]]==0?'':System::Display_number(round([[=items.equip=]])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.spa=]]==0?'':System::Display_number(round([[=items.spa=]])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.tour=]]==0?'':System::Display_number(round([[=items.tour=]])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ([[=items.extra_service=]]==0?'':System::Display_number(round([[=items.extra_service=]])));?></td>
            <td style="background-color:#FFFF99; text-align: right;" class="report_table_column">
            <?php echo System::Display_number(round([[=items.extra_service=]]
                +[[=items.minibar=]]
                +[[=items.restaurant=]]
                +[[=items.laundry=]]
                +[[=items.telephone=]]

                +[[=items.equip=]]
                +[[=items.spa=]]
                +[[=items.room=]]
                +[[=items.extra_bed=]]
				+[[=items.break_fast=]]
                +[[=items.tour=]]));?></td>
            
        <?php
           $i++ ;}
        ?>
        
        <?php
            
                if($is_rowspan == false)
            {
                $is_rowspan = true;
            } 
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
		<td class="report_table_column"><strong>[[.total.]]</strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=real_room_count=]]); ?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_room_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_extra_bed_total=]]); ?></strong></td>
        <!--<td align="right" class="report_table_column"><strong></?php echo System::display_number([[=total_break_fast_total=]]); ?></strong></td>-->
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_restaurant_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_minibar_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_laundry_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_telephone_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_equip_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_spa_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_tour_total=]]); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=total_extra_service_total=]]); ?></strong></td>
        <td style="background-color:#FFFF99;" align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_room_total']
                                                                                                                                + $this->map['total_extra_bed_total']
                                                                                                                                + $this->map['total_restaurant_total']
                                                                                                                                + $this->map['total_minibar_total']
                                                                                                                                + $this->map['total_laundry_total']
                                                                                                                                + $this->map['total_telephone_total']
                                                                                                                                + $this->map['total_equip_total']
                                                                                                                                + $this->map['total_spa_total']
                                                                                                                                + $this->map['total_tour_total']
																																+ $this->map['total_break_fast_total']
                                                                                                                                + $this->map['total_extra_service_total']); ?></strong></td>
        <td style="background-color:#FFCCFF;"><strong><?php echo System::display_number(round([[=total_reduce_amount_total=]])); ?></strong></td>
        
        
    </tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
		<td class="report_table_column"><strong>[[.total.]]</strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=real_room_count=]]); ?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_room_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_extra_bed_total=]])); ?></strong></td>
        <!--<td align="right" class="report_table_column"><strong></?php echo System::display_number(round([[=total_break_fast_total=]])); ?></strong></td>-->
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_restaurant_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_minibar_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_laundry_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_telephone_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_equip_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_spa_total=]])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_tour_total=]])); ?>&nbsp;</strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_extra_service_total=]])); ?></strong></td>
        <td style="background-color:#FFFF99;" align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=total_extra_service_total=]])
                                                                                                                                        +round([[=total_room_total=]])
                                                                                                                                        +round([[=total_minibar_total=]])
                                                                                                                                        +round([[=total_restaurant_total=]])
                                                                                                                                        +round([[=total_laundry_total=]])
                                                                                                                                        +round([[=total_telephone_total=]])
                                                                                                                                        +round([[=total_spa_total=]])
							 																											+round([[=total_break_fast_total=]])
                                                                                                                                        +round([[=total_equip_total=]])
                                                                                                                                        +round([[=total_extra_bed_total=]])
                                                                                                                                        +round([[=total_tour_total=]])); ?></strong></td>                                                                                                                               
        
        
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

	<td align="left" colspan="2"></td>
	<td  align="center"> <?php //echo date('H\h : i\p',time());?> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
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
input[id="from_date"]{width:70px;}
input[id="start_page"]{width:30px;}
input[id="no_of_page"]{width:30px;}
input[id="line_per_page"]{width:30px;}
input[id="to_date"]{width:70px;}
input[type="submit"]{width:100px;}
input[id="from_time"]{width:40px;}
input[id="to_time"]{width:40px;}
selcet[id="user_id"]{width:70px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>

<script type="text/javascript">
// 7211
    var users = <?php echo String::array2js([[=users=]]);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#user_id').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        jQuery('#to_day').datepicker();
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
    }
);
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
        
        if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
        {
            alert('[[.start_time_longer_than_end_time_try_again.]]');
            return false;
        }
        else
        {
            if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
            {
                alert('[[.the_max_time_is_2359_try_again.]]');
                return false;
            }
            else
            {                
                return true;             
            }
        }   
    }
    //end:KID them ham check dieu kien search
</script>
