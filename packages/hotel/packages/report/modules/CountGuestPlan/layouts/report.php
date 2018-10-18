<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>Biểu 3b</strong>
                    </td>
                </tr>
                <tr>    
    				<td align="center" colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >KẾ HOẠCH LƯỢT KHÁCH NĂM <?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'];?><br /></font>
                        </div>
                    </td>
                    
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>
<style type="text/css">
.table-bound{margin: 0 auto !important;}
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>




<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        
		<th class="report_table_header" width="200px" rowspan="2">[[.category.]]</th>
		<th class="report_table_header" width="150px" colspan="3"><?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'] - 2;?></th>
        <th class="report_table_header" width="150px" colspan="3"><?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'] - 1;?></th>
        <th class="report_table_header" width="150px" colspan="3"><?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'];?></th>
        <th class="report_table_header" width="100px" colspan="2">[[.compare.]]</th>
    </tr>
    
    <tr>
		<th class="report_table_header" width="50px">[[.no_of_guest.]]</th>
        <th class="report_table_header" width="50px">[[.guest_day.]]</th>
        <th class="report_table_header" width="50px">[[.room_day.]]</th>
        <th class="report_table_header" width="50px">[[.no_of_guest.]]</th>
        <th class="report_table_header" width="50px">[[.guest_day.]]</th>
        <th class="report_table_header" width="50px">[[.room_day.]]</th>
		<th class="report_table_header" width="50px">[[.no_of_guest.]]</th>
        <th class="report_table_header" width="50px">[[.guest_day.]]</th>
        <th class="report_table_header" width="50px">[[.room_day.]]</th>
        <th class="report_table_header" width="50px">TH<?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'] - 1;?>/TH<?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'] - 2;?></th>
        <th class="report_table_header" width="50px">KH<?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'];?>/TH<?php $current_year['year'] = Url::get_value('year'); echo $current_year['year'] - 1;?></th>
     </tr>
     <?php
        //System::debug([[=items=]]);
        foreach([[=items=]] as $key=>$value)
        {
            $current_year['year'] = Url::get_value('year');
            if (isset($value['name']))
            $percent_prev = 0;
            if($value['name'] != 'guest_specific')
            {
                if($value[$current_year['year'] - 2]['guest_day'] == 0)
                $percent_prev = $value[$current_year['year'] - 1]['guest_day'] .'%';
                else
                    $percent_prev = round($value[$current_year['year'] - 1]['guest_day']/$value[$current_year['year'] - 2]['guest_day']*100, 2).'%';   
            }
            else
                $percent_prev = '';
            //echo $value[$current_year['year'] - 1]['no_of_guest'];    
            if($value['name'] == 'guest_specific')
            {
                $current_guest='';
                $current_guest_day='';
                $current_roomday = '';
            }
                
            if($value['name'] != 'guest_specific')
            {
                $current_guest = round($value[$current_year['year'] - 1]['no_of_guest']*1.15);
                $current_roomday = round($value[$current_year['year'] - 1]['room_day']*1.15);
                $current_guest_day = round($value[$current_year['year'] - 1]['guest_day']*1.15);
                //echo $current_guest;
            }  
            if($value['name'] != 'guest_specific')
            {
                if($value[$current_year['year'] - 1]['guest_day'] == 0)
                    $percent_current = $current_guest_day .'%';
                else
                    $percent_current = round(($current_guest_day/$value[$current_year['year'] - 1]['guest_day'] - 1)*100).'%';   
            }
            else
                $percent_current = '';
                
            echo '<tr>
                   	<td class="report_table_column" align="left">'.Portal::language($value['name']).'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['no_of_guest'].'</td>
            		<td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['guest_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['room_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['no_of_guest'].'</td>
            		<td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['guest_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['room_day'].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="report_table_column" align="right">'.$percent_prev.'</td>
                    <td></td>
                </tr>';
        }
     ?>
     <tr>
       	<td class="report_table_column" align="left"><strong>B. PHÂN THEO QUỐC TỊCH</strong></td>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
		<td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
        <td class="report_table_column" align="right"></td>
    </tr>
    <?php
        //System::debug([[=nationality_items=]]);
        foreach([[=nationality_items=]] as $key=>$value)
        {
            $current_year['year'] = Url::get_value('year');
            if($value[$current_year['year'] - 2]['guest_day'] == 0)
                $percent_prev = $value[$current_year['year'] - 1]['guest_day'] .'%';
            else
                $percent_prev = ($value[$current_year['year'] - 1]['guest_day']/$value[$current_year['year'] - 2]['guest_day']*100).'%';
            
            if($value[$current_year['year'] - 1]['guest_day'] == 0)
                    $percent_current = ($value[$current_year['year'] - 1]['guest_day']*1.15) .'%';
            else
                $percent_current = (($value[$current_year['year'] - 1]['guest_day']*0.15)/$value[$current_year['year'] - 1]['guest_day']*100).'%';
            if (isset($value['name']))
            echo '<tr>
                   	<td class="report_table_column" align="left">'.Portal::language($value['name']).'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['no_of_guest'].'</td>
            		<td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['guest_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['room_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['no_of_guest'].'</td>
            		<td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['guest_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['room_day'].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="report_table_column" align="right">'.round($percent_prev,2).'%</td>
                    <td></td>
                </tr>';
        }
     ?>
     <?php
        foreach([[=domestic_items=]] as $key=>$value)
        {
            $current_year['year'] = Url::get_value('year');
            if($value[$current_year['year'] - 2]['guest_day'] == 0)
                $percent_prev = $value[$current_year['year'] - 1]['guest_day'] .'%';
            else
                $percent_prev = ($value[$current_year['year'] - 1]['guest_day']/$value[$current_year['year'] - 2]['guest_day']*100).'%';
            
            if($value[$current_year['year'] - 1]['guest_day'] == 0)
                    $percent_current = ($value[$current_year['year'] - 1]['guest_day']*0.15) .'%';
            else
                $percent_current = (($value[$current_year['year'] - 1]['guest_day']*0.15)/$value[$current_year['year'] - 1]['guest_day']*100).'%';
            if (isset($value['name']))
            echo '<tr>
                   	<td class="report_table_column" align="left">'.Portal::language($value['name']).'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['no_of_guest'].'</td>
            		<td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['guest_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 2]['room_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['no_of_guest'].'</td>
            		<td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['guest_day'].'</td>
                    <td class="report_table_column" align="right">'.$value[$current_year['year'] - 1]['room_day'].'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="report_table_column" align="right">'.round($percent_prev,2).'%</td>
                    <td></td>
                </tr>';
        }
     ?>
    <?php 
        $current_year['year'] = Url::get_value('year');
        if([[=room_capacity=]][$current_year['year'] - 2] == 0)
        {
            $percent_prev = [[=room_capacity=]][$current_year['year'] - 1]/152/365*100;
        }
        else
            $percent_prev = [[=room_capacity=]][$current_year['year'] - 1]/[[=room_capacity=]][$current_year['year'] - 2]*100;
        if([[=room_capacity=]][$current_year['year'] - 1] == 0)
        {
            $percent_current = [[=room_capacity=]][$current_year['year'] - 1]*1.15/152/365*100;
        }
        else
            $percent_current = [[=room_capacity=]][$current_year['year'] - 1]/[[=room_capacity=]][$current_year['year'] - 1]*0.15*100;
        echo '<tr>
               	<td class="report_table_column" align="left"><strong>III. CÔNG SUẤT PHÒNG</strong></td>
                <td class="report_table_column" align="center" colspan="3"><strong>'.round([[=room_capacity=]][$current_year['year'] - 2]/152/365*100,2).'%</strong></td>
        		<td class="report_table_column" align="center" colspan="3"><strong>'.round([[=room_capacity=]][$current_year['year'] - 1]/152/365*100,2).'%</strong></td>
                <td colspan="3"></td>
                <td class="report_table_column" align="right"><strong>'.round($percent_prev,2).'%</strong></td>
        		<td></td>
            </tr>';
    ?>
</table>



<br/>


<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td></td>
	<td>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<div style="page-break-before:always;page-break-after:always;"></div>