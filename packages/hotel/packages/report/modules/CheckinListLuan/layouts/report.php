<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>

<!---------HEADER----------->
<!--IF:first_page([[=real_page_no=]]==1)-->
<div class="report-bound"> 
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="35%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
    				<td> 
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" >[[.checkin_room_list.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.day.]]&nbsp;[[|date|]]
                            </span> 
                        </div>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
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
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
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
                                    <td>[[.date.]]</td>
                                    <td><input name="date" type="text" id="date" /></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td><input type="submit" name="do_search" value="[[.report.]]"/></td>
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
jQuery(document).ready(function(){
        jQuery('#date').datepicker();
    
});
</script>
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_room([[=real_room_count=]])-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="20px">[[.stt.]]</th>
		<th class="report_table_header" width="30px">[[.re_code.]]</th>
        <th class="report_table_header" width="30px">[[.room.]]</th>
        <th class="report_table_header" width="30px">[[.room_level.]]</th>
        <th class="report_table_header" width="50px">[[.price.]]</th>
        <th class="report_table_header" width="150px">[[.guest_name.]]</th>
        <th class="report_table_header" width="150px">[[.tour.]],[[.company.]]</th>
		<th class="report_table_header" width="30px">[[.nationality.]]</th>
        <th class="report_table_header" width="30px">[[.A/c.]]</th>
		<th class="report_table_header" width="50px">[[.time_in.]]</th>
		<th class="report_table_header" width="50px">[[.time_out.]]</th>
        <th class="report_table_header" width="30px">[[.night.]]</th>
        <th class="report_table_header" width="30px">[[.flight_code.]]</th>
        <th class="report_table_header" width="50px">[[.flight_arrival_time.]]</th>
        </tr>
    
       <?php 
    $stt=0;
    if(isset([[=items=]])){
        if(count([[=items=]])>0){
            foreach([[=items=]] as $k1=>$v1){
        $stt++;$company=1;
        echo '<tr>
        <td class="report_table_column ss" rowspan='.$v1['count'].' align="center">
            <div style="font-size:11px;">'.$stt.'</div></td>';
        echo"
        <td class='report_table_column ss' rowspan=".$v1['count']." align='center'>
            <div style='font-size:11px;'>
            <a href='".Url::build('reservation',array('cmd'=>'edit','id'=>$v1['reservation_id'],'r_r_id'=>$v1['reservation_room_code']))."' target='_blank'>
            ".$v1['reservation_id']."
            </div></td>";
        foreach($v1['child'] as $k2=>$v2){
            $i=1;$j=1;$a =1;$b=1;$q=1; 
            if(count([[=items=]][$k1]['child'][$k2]['childrend'])>0){
                for($d=1;$d<=$v2['count'];$d++){
                    reset([[=items=]]);
                if($q==1){
                     echo '<td class="report_table_column" rowspan='.$v2['count'].' align="center">
                    <div style="font-size:11px;">'.$v2['room_name'].'</div></td>
                    
                    <td class="report_table_column" rowspan='.$v2['count'].' align="center">
                    <div style="font-size:11px;">'.$v2['brief_name'].'</div></td>
                    
                    <td class="report_table_column" rowspan='.$v2['count'].' align="center">
                    <div style="font-size:11px;">'.System::display_number($v2['price']).'</div></td>
                    
                   
                     '; 
                }
                if($d>1)
                    echo '<tr>';
                foreach($v2['childrend'] as $k3=>$v3){
                    echo '<td class="report_table_column" align="center">
                    <div style="font-size:11px;">
                    <a href="'.Url::build('traveller',array('id'=>$v3['traveller_id'])).'" target="_blank">
                    '.$v3['fullname'].'</a></div></td>';
                    break;
                }
         
         
            if($company==1){
                echo '
                <td class="report_table_column" rowspan='.$v1['count'].' align="center">
                <div style="font-size:11px;">'.$v1['note'].'</div></td>';
            }
             
            foreach($v2['childrend'] as $k3=>$v3){
                echo '<td class="report_table_column" align="center">
                <div style="font-size:11px;">'.$v3['country_code_1'].'</div></td>';
                break;
              }  
            if($q==1){  
                echo '<td class="report_table_column" rowspan='.$v2['count'].' align="center">
                <div style="font-size:11px;">'.$v2['adult'].'/'.$v2['child'].'</div></td>';
            }
                foreach($v2['childrend'] as $k3=>$v3){
                    echo '<td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.date('d/m/Y H:i',$v3['time_in']).'</div></td>
                    <td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.date('d/m/Y H:i',$v3['time_out']).'</div></td>
                    ';
                   break;
                }
            
            if($q==1){  
                echo '<td class="report_table_column" rowspan='.$v2['count'].' align="center">
                <div style="font-size:11px;">'.$v2['night'].'</div></td>';
            }
            $g='';
            /** Minh format time */
                foreach($v2['childrend'] as $k3=>$v3){
                    $v3['flight_arrival_time'] = $v3['flight_arrival_time']!=0?date('H:i',$v3['flight_arrival_time']):'';                    
                    echo '<td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.$v3['flight_code'].'</div></td>
                    <td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.$v3['flight_arrival_time'].'</div></td>
                    
                    </tr>
                    ';
                    $g=$k3;
                unset($v2['childrend'][$k3]); 
                break;
                }
                
            $company++;    
            $q++;
            } 
            }
               
        }   
    }
        }  
    }
  ?>  
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <!--IF:check(([[=room_count=]]!=[[=real_room_count=]]) && (([[=real_page_no=]]==[[=real_total_page=]])))-->
	<tr bgcolor="white">
		<td align="center" colspan="2" class="report_table_column"><strong>[[.total.]]</strong></td>
		<td align="" class="report_table_column"><strong>[[|real_room_count|]]</strong></td>
        <td class="report_table_column">&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=real_total_money=]]) ?></strong></td>
		<td colspan="3" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong>[[|real_num_people|]]/[[|real_num_child|]]</strong></td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        
        <td align="center" class="report_table_column"><strong>[[|real_night|]]</strong></td>
        <td colspan="10" class="report_table_column">&nbsp;</td>
	</tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
    		<td align="center" colspan="2" class="report_table_column"><strong>[[.total.]]</strong></td>
    		<td align="center" class="report_table_column"><strong>[[|real_room_count|]]</strong></td>
            <td class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=real_total_money=]]) ?></strong></td>
    		<td colspan="3" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong>[[|real_num_people|]]/[[|real_num_child|]]</strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong>[[|real_night|]]</strong></td>
            <td colspan="10" class="report_table_column">&nbsp;</td>
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
	<td > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_room-->
