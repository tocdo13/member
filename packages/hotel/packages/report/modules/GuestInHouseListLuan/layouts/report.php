<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 100px; height: auto;" /><br /><br /><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong> 
        <br />
        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo User::id();?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" >[[.traveller_room.]]<br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"> <?php echo date('H:i',time());?> [[.day.]]&nbsp;[[|date|]] </span> </div></td>
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
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print"/>
</div>
<!---------SEARCH----------->
<div style="text-align: left;"><input name="hidden_price" type="checkbox" id="hidden_price" /> [[.hidden_price.]] </div>
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
                                    <td align="left"><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                    <td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <!-- Oanh add tìm kiếm theo nhóm nguồn khách -->
                                    <td>[[.customer.]]: </td>
                                    <td><select name="customer_id" id="customer_id"></select></td>
                                    <!-- End Oanh -->
                                    
                                </tr>
                                <tr>
                                    <td align="left">[[.date.]]</td>
                                	<td><input name="date" type="text" id="date"/></td>
                                    <td>[[.time.]]</td>
                                    <td><input name="time" type="text" id="time" style="width: 50px;"/></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="left"><button id="export">[[.export.]]</button></td>
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
<table  id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
    <tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="25px">[[.stt.]]</th>
		<th class="report_table_header" width="35px">[[.reservation_room_code.]]</th>
        <th class="report_table_header" width="35px">[[.booking_code.]]</th>
        <th class="report_table_header" width="150px">[[.tour.]],[[.company.]]</th>
        <th class="report_table_header" width="150px">[[.customer_of_group.]]</th>
        <th class="report_table_header" width="100px">[[.note_group.]]</th>
        
        <th class="report_table_header" width="30px">[[.room.]]</th>
         <?php if(Url::get('dept') != 'HK') { ?>
        <th class="report_table_header col_price" width="50px">[[.price.]]</th>
        <?php } ?>
         <th class="report_table_header" width="30px">[[.night.]]</th>
        <th class="report_table_header" width="100px">[[.room_note.]]</th>
		<th class="report_table_header" width="150px">[[.guest_name.]]</th>
    
        <th class="report_table_header" width="50px">[[.date_of_birth.]]</th>
        <th class="report_table_header" width="30px">[[.gender.]]</th>
		<th class="report_table_header" width="40px">[[.nationality.]]</th>
		<th class="report_table_header" width="50px">[[.arrival_date.]]</th>
		<th class="report_table_header" width="50px">[[.departure_date.]]</th>
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
            ".$v1['reservation_id']."</div></td>
        <td class='report_table_column ss' rowspan=".$v1['count']." align='center'> ".$v1['booking_code']."</td>";
        if($company==1){
                        echo '<td class="report_table_column" rowspan='.$v1['count'].' align="center">
                        <div style="font-size:11px;">'.$v1['note'].'</div></td>';
                    }
        echo "<td class='report_table_column ss' rowspan=".$v1['count']." align='center'> ".$v1['customer_group_name']."</td>";
        echo "<td class='report_table_column ss' rowspan=".$v1['count']." align='left'> ".$v1['reservation_note']."</td>  
            ";
        foreach($v1['child'] as $k2=>$v2){
            $i=1;$j=1;$a =1;$b=1;$q=1; 
            if(count([[=items=]][$k1]['child'][$k2]['childrend'])>0){
                for($d=1;$d<=$v2['count'];$d++){
                    reset([[=items=]]);
                    if($q==1){
                         echo '
                         
                          
                        
                         <td class="report_table_column" rowspan='.$v2['count'].' align="center">
                        <div style="font-size:11px;">'.$v2['room_name'].'</div></td>
                        
                         <td class="report_table_column col_price" rowspan='.$v2['count'].' align="center">
                        <div style="font-size:11px;" >'.System::display_number($v2['price']).'</div></td>
                        
                        <td class="report_table_column" rowspan='.$v2['count'].' align="center">
                        <div style="font-size:11px;">'.$v2['night'].'</div></td>
                        <td class="report_table_column" rowspan='.$v2['count'].' align="left">
                        <div style="font-size:11px;">'.$v2['reservation_room_note'].'</div></td>
                         '; 
                    }   
                    
                    if($d>1)
                        echo '<tr>';
                    foreach($v2['childrend'] as $k3=>$v3){
                        echo '<td class="report_table_column" align="left">
                        <div style="font-size:11px;">
                        <a href="'.Url::build('traveller',array('id'=>$v3['traveller_id'])).'" target="_blank">
                        '.$v3['fullname'].'</a></div></td>
                      
                    <td class="report_table_column" align="center">
                        <div style="font-size:11px;">'.$v3['birth_date'].'</div></td>
                    <td class="report_table_column" align="center">
                        <div style="font-size:11px;">'.$v3['gender'].'</div></td>
                    <td class="report_table_column" align="center">
                        <div style="font-size:11px;">'.$v3['country_code_1'].'</div></td>
                    <td class="report_table_column" align="center">
                        <div style="font-size:11px;">'.date('d/m/Y H:i',$v3['time_in']).'</div></td>
                    <td class="report_table_column" align="center">
                        <div style="font-size:11px;">'.date('d/m/Y H:i',$v3['time_out']).'</div></td>
                    </tr>
                        ';
                    unset($v2['childrend'][$k3]);     
                        break;
                    }
                     $q++;
                     $company++;
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
		<td colspan="2" class="report_table_column"><strong>[[.total.]]</strong></td>
		<td class="report_table_column"><strong></strong></td>
        <td align="center" class="report_table_column"><strong>[[|real_total_guest|]]</strong></td>
        
        <td colspan="2" class="report_table_column"></td>
        <td align="center" class="report_table_column">[[|real_room_count|]]</td>
        <td align="right" class="report_table_column col_price"><strong><?php echo System::display_number([[=total_price=]]); ?></strong></td>
        <td colspan="1" class="report_table_column">&nbsp;</td>
        <?php if(Url::get('dept') != 'HK') { ?>
        <td>&nbsp;</td>
        <?php } ?>
        <td align="center" class="report_table_column"><strong>[[|real_night|]]</strong></td>
	</tr>
    <!--ELSE-->
    	<!--IF:end_page([[=page_no=]]==[[=total_page=]])-->
    	<tr bgcolor="white">
    		<td colspan="6" class="report_table_column"><strong>[[.total.]]</strong></td>
            <td align="center" class="report_table_column"><strong>[[|real_room_count|]]</strong></td>
            <td align="right" class="report_table_column col_price"><strong><?php echo System::display_number([[=total_price=]]); ?></strong></td>
    		
            <td align="center" class="report_table_column"><strong>[[|real_night|]]</strong></td>
            <td colspan="" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong>[[|real_total_guest|]]</strong></td>
            <td colspan="" class="report_table_column"></td>
            <td></td>
            <td colspan="1" class="report_table_column">&nbsp;</td>
            <?php if(Url::get('dept') != 'HK') { ?>
            <td>&nbsp;</td>
            <?php } ?>
            <td colspan="" class="report_table_column">&nbsp;</td>

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
	<td  align="center"> <?php echo date('H:i',time());?> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
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
        jQuery('#to_day').datepicker();
        jQuery('#time').mask("99:99");
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);
jQuery('#hidden_price').click(function(){
    if (jQuery(this).is(":checked")){
        jQuery('.col_price').css('display','none');
    }else{
        jQuery('.col_price').css('display','');
    }
});
</script>