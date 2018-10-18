<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif;; font-size:11px;">
    			<tr>
                    <td align="left" width="35%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right">
                    [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                    <br />
                    [[.user_print.]]:<?php echo User::id();?>
                    </td>
                    
    			</tr>
            
                <tr>
                    
                    <td colspan="2" >
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" style="font-size:16px;">[[.foreign_guest_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.day.]]&nbsp;[[|date|]]
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
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.date.]]</td>
                                	<td><input name="date" type="text" id="date"/></td>
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
.total{background-color:#FFC}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
    }
);
//style="background-color:#FFC"
</script>

<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" rowspan="2" width="20px">[[.stt.]]</th>
		<th class="report_table_header" rowspan="2" width="250px">[[.nationality.]]</th>
        <th class="report_table_header" rowspan="2" width="100px">[[.total_occupied.]]</th>
        <th class="report_table_header" colspan="2">[[.in_this.]]</th>
        <th class="report_table_header" colspan="2">[[.analysis.]] [[.colum.]]</th>
    </tr>
    <tr>
        <th class="report_table_header" width="100px">[[.old_day.]]</th>
        <th class="report_table_header" width="100px">[[.today_arrival.]]</th>
        <th class="report_table_header" width="100px">[[.foreigner.]]</th>
        <th class="report_table_header" width="100px">[[.vietnamese_residing_abroad.]]</th>
    </tr>
    
    
    <?php
        foreach($this->map['nationality'] as $key=>$value)
        {
            echo '<tr>';
            echo '<td>'.$value['stt'].'</td>';
            echo '<td>'.$value['nationality'].'</td>';
            echo '<td align="center">'.($value['TOTAL']?$value['TOTAL']:'').'</td>';
            echo '<td align="center">'.(($value['TOTAL']-$value['TOTAL_today'])?($value['TOTAL']-$value['TOTAL_today']):'').'</td>';
            echo '<td align="center">'.($value['TOTAL_today']?$value['TOTAL_today']:'').'</td>';
            echo '<td align="center">'.($value['FOREIGN']?$value['FOREIGN']:'').'</td>';
            echo '<td align="center">'.($value['FOREIGN_isVN']?$value['FOREIGN_isVN']:'').'</td>';
            echo '</tr>';
        }
        
        echo '<tr class="total" style="font-weight:bold;">';
        echo '<td  align="right" colspan="2">'.Portal::language('total').'</td>';
        echo '<td align="center">'.([[=total=]]['TOTAL']?[[=total=]]['TOTAL']:'').'</td>';
        echo '<td align="center">'.(([[=total=]]['TOTAL']-[[=total=]]['TOTAL_today'])?([[=total=]]['TOTAL']-[[=total=]]['TOTAL_today']):'').'</td>';
        echo '<td align="center">'.([[=total=]]['TOTAL_today']?[[=total=]]['TOTAL_today']:'').'</td>';
        echo '<td align="center">'.([[=total=]]['FOREIGN']?[[=total=]]['FOREIGN']:'').'</td>';
        echo '<td align="center">'.([[=total=]]['FOREIGN_isVN']?[[=total=]]['FOREIGN_isVN']:'').'</td>';
        echo '</tr>';
        
    ?>
</table>


<!---------FOOTER----------->

<table width="100%" cellpadding="10">
<tr>
	<td width="100%" align="right"> [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="100%" align="right" >[[.creator.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>