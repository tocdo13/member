<style>
    #timehidden
    {
        display:none;	
    }
	@media print
    {
		#hidden
        {
			display:none;
		}
		#timehidden
        {
			display:block;	
		}
	}
.table-bound tr th {
	text-align: center;
}
th {
	text-align: right;
}
tr th {
	text-align: right;
}
</style>
<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%"><strong>THANH TAN CORPORATION - THUA THIEN HUE</strong><br />12 NGUYEN VAN CU - TP HUE<BR></td>
				<td align="right" nowrap width="35%"><strong></strong></td>
			</tr>	
		</table>
<table  bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.room_revenue_report.]]</b></font><br />
        <br />
<label id="timehidden"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo 'After tax';}else if ($_POST['tax']=='before_tax'){echo 'Before Tax';}}else{echo 'After tax';} ?></label>
        <label id="timehidden">[[.from_date.]]: [[|from_date|]]  [[.to_date.]]: [[|to_date|]]</label>
		<form name="WeeklyRevenueForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td></td>
            	<td>[[.from_date.]]:&nbsp;&nbsp;
            	<input type="text" name="from_date" id="from_date" class="date-input" onChange="check_from_date();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td >[[.to_date.]]:&nbsp;&nbsp;
                <input type="text" name="to_date" id="to_date" class="date-input" onChange="check_to_date();"/></td>
                <td><input type="submit" name="do_search" value="  [[.report.]]  " onClick="check_radio()"/></td>
			</tr>
            <tr align="center">
                <td></td>
                <td>[[.after_tax.]]<input type="radio" id="1" value="after_tax" <?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo 'checked=""';}}else{echo 'checked=""';} ?> name ="tax"  /></td>
                <td>[[.before_tax.]]<input type="radio" id="2" value="before_tax" name="tax"<?php if(isset($_POST['do_search'])){if($_POST['tax']=='before_tax'){echo 'checked=""';}} ?>   /></td>
                <td></td>
            </tr>
		  </table>
		  </fieldset>
		  </td>
    </tr>
    </table>
			</form>
	</td></tr></table>
</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" >
    <tr valign="middle" bgcolor="silver" align="center" >
        <th rowspan="2" width="250px" >[[.building.]]</th>
        <th width="200px">[[.fact.]]</th>
        <th width="200px" colspan="2">[[.compared_to_previous_year.]]</th>
        <th colspan="2">[[.compared_to_same_period.]]</th>
        <th colspan="2">[[.compared_to_previous_period.]]</th>
    </tr>
    <tr align="center" bgcolor="silver">
        <th width="160px">[[.revenue.]]</th>
        <th width="160px">[[.previous_year.]]</th>
        <th width="40px">[[.%.]]</th>
        <th width="160px">[[.same_period.]]</th>
        <th width="40px">[[.%.]]</th> 
        <th width="160px">[[.current_budget.]]</th>
        <th width="40px">[[.%.]]</th>
    </tr>
    <!--LIST:total_revenue-->
    <?php
        $room_revenue = [[=total_revenue.room_revenue=]];
        //$room_revenue_last_year = [[=total_revenue.room_revenue_last_year=]];
        $minibar = [[=total_revenue.minibar=]];
        //$minibar_last_year = [[=total_revenue.minibar_last_year=]];
        $laundry = [[=total_revenue.laundry=]];
        //$laundry_last_year = [[=total_revenue.laundry_last_year=]];
        $extra_services = [[=total_revenue.extra_services=]];
        //$extra_services_last_year = [[=total_revenue.extra_services_last_year=]];
        $equipment = [[=total_revenue.equipment=]];
        //$equipment_last_year = [[=total_revenue.equipment_last_year=]];  
      ?>
    <tr bgcolor="#EFEFEF">
        <th>[[|total_revenue.name|]]</th>
        <td align="right"><strong><?php $total = System::display_number(round([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]]));if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.room_revenue=]]/1.155 +[[=total_revenue.EI_LO=]]/1.155+ [[=total_revenue.minibar=]]/1.155+[[=total_revenue.laundry=]]/1.155+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]]));}}else{echo $total; }  ?></strong></td>
        <td align="right"><strong>0</strong></td>
        <td align="right"><strong>0%</strong></td>
        <td align="right"><strong><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]] + [[=total_revenue.minibar_same_period=]]+[[=total_revenue.laundry_same_period=]]+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]]));}else if ($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.room_revenue_same_period=]]/1.155+[[=total_revenue.EI_LO_same_period=]]/1.155 + [[=total_revenue.minibar_same_period=]]/1.155+[[=total_revenue.laundry_same_period=]]/1.155+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]]));}}else{echo System::display_number(round([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]] + [[=total_revenue.minibar_same_period=]]+[[=total_revenue.laundry_same_period=]]+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]]));} ?></strong></td>
        <td align="right">
            <strong><?php 
                if(([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]] + [[=total_revenue.minibar_same_period=]]+[[=total_revenue.laundry_same_period=]]+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number(
                            (([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]])*100
                            )/([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]] + [[=total_revenue.minibar_same_period=]]+[[=total_revenue.laundry_same_period=]]+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number(
                            (([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]])*100
                            )/([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]] + [[=total_revenue.minibar_same_period=]]+[[=total_revenue.laundry_same_period=]]+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]])).'%';
                        }
                    }
                    else
                    {
                            echo System::display_number(
                            (([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]])*100
                            )/([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]] + [[=total_revenue.minibar_same_period=]]+[[=total_revenue.laundry_same_period=]]+[[=total_revenue.extra_services_same_period=]]+[[=total_revenue.equipment_same_period=]])).'%';
                        }
                }
                else
                {
                    echo '100%';
                }
             ?></strong>
        </td>
        <td align="right"><strong><?php $total2 = System::display_number([[=total_revenue.total_month_room=]] + [[=total_revenue.total_month_minibar=]]+[[=total_revenue.total_month_laundry=]]+[[=total_revenue.total_month_extra_services=]]); echo $total2; ?></strong></td>
        <td align="center">
            <strong><?php
                if(([[=total_revenue.total_month_room=]] + [[=total_revenue.total_month_minibar=]]+[[=total_revenue.total_month_laundry=]]+[[=total_revenue.total_month_extra_services=]])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_revenue.room_revenue=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.EI_LO=]]+[[=total_revenue.equipment=]])*100
                                        )/([[=total_revenue.total_month_room=]] + [[=total_revenue.total_month_minibar=]]+[[=total_revenue.total_month_laundry=]]+[[=total_revenue.total_month_extra_services=]])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_revenue.room_revenue=]]/1.155+[[=total_revenue.EI_LO=]]/1.155 + [[=total_revenue.minibar=]]/1.155+[[=total_revenue.laundry=]]/1.155+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]])*100
                                        )/([[=total_revenue.total_month_room=]] + [[=total_revenue.total_month_minibar=]]+[[=total_revenue.total_month_laundry=]]+[[=total_revenue.total_month_extra_services=]])).'%';
                        }
                    }
                    else
                    {
                        echo System::display_number( (
                                            ([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]] + [[=total_revenue.minibar=]]+[[=total_revenue.laundry=]]+[[=total_revenue.extra_services=]]+[[=total_revenue.equipment=]])*100
                                        )/([[=total_revenue.total_month_room=]] + [[=total_revenue.total_month_minibar=]]+[[=total_revenue.total_month_laundry=]]+[[=total_revenue.total_month_extra_services=]])).'%';
                    }
                }
                else
                {
                    echo '0%';
                }
            ?></strong>
        </td>
    </tr>
    <tr >
        <td> - [[.revenue_room.]]</td>
        <td id="revenue_room" align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.room_revenue=]]/1.155+[[=total_revenue.EI_LO=]]/1.155));}}else{echo System::display_number(round([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]])); }?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round(([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]])/1.155));}}else{echo System::display_number(round([[=total_revenue.room_revenue_same_period=]]));}?></td>
        <td align="right"><?php if(([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]])>0){echo System::display_number((([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]])*100)/([[=total_revenue.room_revenue_same_period=]]+[[=total_revenue.EI_LO_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_revenue.total_month_room=]]));?></td>
        <td align="right"><?php if([[=total_revenue.total_month_room=]]>0){echo System::display_number((([[=total_revenue.room_revenue=]]+[[=total_revenue.EI_LO=]])*100)/[[=total_revenue.total_month_room=]]).'%';}else{echo '100%';}?></td>
    </tr>
    <tr >
        <td> - [[.minibar.]]</td>
        <td align="right" id="minibar"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.minibar=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.minibar=]]/1.155));}}else{echo System::display_number(round([[=total_revenue.minibar=]]));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.minibar_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.minibar_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_revenue.minibar_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_revenue.minibar_same_period=]]>0){echo System::display_number((([[=total_revenue.minibar=]])*100)/([[=total_revenue.minibar_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_revenue.total_month_minibar=]]));?></td>
        <td align="right"><?php if([[=total_revenue.total_month_minibar=]]>0){echo System::display_number(([[=total_revenue.minibar=]]*100)/[[=total_revenue.total_month_minibar=]]).'%';}else{echo '100%';}?></td>   
    </tr >
    <tr >
        <td> - [[.Laundry.]]</td>
        <td align="right" id="laundry"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.laundry=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.laundry=]]/1.155));}}else{echo System::display_number(round([[=total_revenue.laundry=]]));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.laundry_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.laundry_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_revenue.laundry_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_revenue.laundry_same_period=]]>0){echo System::display_number((([[=total_revenue.laundry=]])*100)/([[=total_revenue.laundry_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_revenue.total_month_laundry=]]));?></td>
        <td align="right"><?php if([[=total_revenue.total_month_laundry=]]>0){echo System::display_number(([[=total_revenue.laundry=]]*100)/[[=total_revenue.total_month_laundry=]]).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr >
        <td> - [[.other_service.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_revenue.extra_services=]]));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.extra_services_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.extra_services_same_period=]]));}}else{echo System::display_number(round([[=total_revenue.extra_services_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_revenue.extra_services_same_period=]]>0){echo System::display_number((([[=total_revenue.extra_services=]])*100)/([[=total_revenue.extra_services_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_revenue.total_month_extra_services=]]));?></td>
        <td align="right"><?php if([[=total_revenue.total_month_extra_services=]]>0){echo System::display_number(([[=total_revenue.extra_services=]]*100)/[[=total_revenue.total_month_extra_services=]]).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr >
        <td> - [[.compensation.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_revenue.equipment=]]));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_revenue.equipment_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_revenue.equipment_same_period=]]));}}else{echo System::display_number(round([[=total_revenue.equipment_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_revenue.equipment_same_period=]]>0){echo System::display_number((([[=total_revenue.equipment=]])*100)/([[=total_revenue.equipment_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right">0</td>
        <td align="right">0%</td>   
    </tr>

    <!--/LIST:total_revenue-->
    
    <?php
        $room_revenue2 = [[=total_resort=]]['room_revenue'];
        //$room_revenue_last_year5 = [[=total_resort=]]['room_revenue_last_year'];
        $minibar2 = [[=total_resort=]]['minibar'];
        //$minibar_last_year5 = [[=total_resort=]]['minibar_last_year'];
        $laundry2 = [[=total_resort=]]['laundry'];
        //$laundry_last_year5 = [[=total_resort=]]['laundry_last_year'];
        $extra_services2 = [[=total_resort=]]['extra_services'];
        //$extra_services_last_year5 = [[=total_resort=]]['extra_services_last_year'];
        $equipment2 = [[=total_resort=]]['equipment'];
        //$equipment_last_year5 = [[=total_resort=]]['equipment_last_year'];  
      ?>
    <tr valign="middle" bgcolor="silver">
        <th>[[.total_resort.]]</th>
        <td align="right"><strong><?php $total1 = System::display_number(round([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment']));if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue']/1.155+[[=total_resort=]]['EI_LO']/1.155 + [[=total_resort=]]['minibar']/1.155+[[=total_resort=]]['laundry']/1.155+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment']));}}else{echo $total1; }  ?></strong></td>
        <td align="right"><strong>0</strong></td>
        <td align="right"><strong>0%</strong></td>
        <td align="right"><strong><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']));}else if ($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue_same_period']/1.155+[[=total_resort=]]['EI_LO_same_period']/1.155 + [[=total_resort=]]['minibar_same_period']/1.155+[[=total_resort=]]['laundry_same_period']/1.155+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']));}}else{echo System::display_number(round([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']));} ?></strong></td>
        <td align="right">
        	<strong><?php
                if(([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number(
                            (([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                            )/([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number(
                            (([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])*100
                            )/([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])).'%';
                        }
                    }
                    else
                    {
                            echo System::display_number(
                            (([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                            )/([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])).'%';
                        }
                }
                else
                {
                    echo '100%';
                }
            ?></strong>
        </td>
        <td align="right"><strong><?php $total2 = System::display_number([[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar']); echo $total2; ?></strong></td>
        <td align="right">
            <strong><?php
                if(([[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                                        )/([[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_resort=]]['room_revenue']/1.155+[[=total_resort=]]['EI_LO']/1.155 + [[=total_resort=]]['minibar']/1.155+[[=total_resort=]]['laundry']/1.155+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                                        )/([[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])).'%';
                        }
                    }
                    else
                    {
                        echo System::display_number( (
                                            ([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                                        )/([[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])).'%';
                    }
                }
                else
                {
                    echo '0%';
                }
            ?></strong>
        </td>
    </tr>
    <tr  >
        <td> - [[.revenue_room.]]</td>
        <td id="revenue_room" align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue']/1.155+[[=total_resort=]]['EI_LO']/1.155));}}else{echo System::display_number(round([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'])); }?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['room_revenue_same_period']/1.155+[[=total_resort=]]['EI_LO_same_period']/1.155));}}else{echo System::display_number(round([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period']));}?></td>
        <td align="right"><?php if([[=total_resort=]]['room_revenue_same_period']>0){echo System::display_number((([[=total_resort=]]['room_revenue'])*100)/([[=total_resort=]]['room_revenue_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_resort=]]['total_month_room']));?></td>
        <td align="right"><?php if([[=total_resort=]]['total_month_room']>0){echo System::display_number((([[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'])*100)/[[=total_resort=]]['total_month_room']).'%';}else{echo '100%';}?></td>
    </tr>
    <tr  >
        <td> - [[.minibar.]]</td>
        <td align="right" id="minibar"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['minibar']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['minibar']/1.155));}}else{echo System::display_number(round([[=total_resort=]]['minibar']));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['minibar_same_period']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['minibar_same_period']/1.155));}}else{echo System::display_number(round([[=total_resort=]]['minibar_same_period']));}?></td>
        <td align="right"><?php if([[=total_resort=]]['minibar_same_period']>0){echo System::display_number((([[=total_resort=]]['minibar'])*100)/([[=total_resort=]]['minibar_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_resort=]]['total_month_minibar']));?></td>
        <td align="right"><?php if([[=total_resort=]]['total_month_minibar']>0){echo System::display_number(([[=total_resort=]]['minibar']*100)/[[=total_resort=]]['total_month_minibar']).'%';}else{echo '100%';}?></td>   
    </tr >
    <tr  >
        <td> - [[.Laundry.]]</td>
        <td align="right" id="laundry"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['laundry']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['laundry']/1.155));}}else{echo System::display_number(round([[=total_resort=]]['laundry']));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_resort=]]['laundry_same_period']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_resort=]]['laundry_same_period']/1.155));}}else{echo System::display_number(round([[=total_resort=]]['laundry_same_period']));}?></td>
        <td align="right"><?php if([[=total_resort=]]['laundry_same_period']>0){echo System::display_number((([[=total_resort=]]['laundry'])*100)/([[=total_resort=]]['laundry_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_resort=]]['total_month_laundry']));?></td>
        <td align="right"><?php if([[=total_resort=]]['total_month_laundry']>0){echo System::display_number(([[=total_resort=]]['laundry']*100)/[[=total_resort=]]['total_month_laundry']).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr  >
        <td> - [[.other_service.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_resort=]]['extra_services']));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php echo System::display_number(round([[=total_resort=]]['extra_services_same_period']));?></td>
        <td align="right"><?php if([[=total_resort=]]['extra_services_same_period']>0){echo System::display_number((([[=total_resort=]]['extra_services'])*100)/([[=total_resort=]]['extra_services_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number([[=total_resort=]]['total_month_extra_services']);?></td>
        <td align="right"><?php if([[=total_resort=]]['total_month_extra_services']>0){echo System::display_number(([[=total_resort=]]['extra_services']*100)/[[=total_resort=]]['total_month_extra_services']).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr  >
        <td> - [[.compensation.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_resort=]]['equipment']));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php echo System::display_number(round([[=total_resort=]]['equipment_same_period'])) ?></td>
        <td align="right"><?php if([[=total_resort=]]['equipment_same_period']>0){echo System::display_number((([[=total_resort=]]['equipment'])*100)/([[=total_resort=]]['equipment_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right">0</td>
        <td align="right">0%</td>   
    </tr>
    <!--LIST:total_queen-->
    <?php
        $room_revenue3 = [[=total_queen.room_revenue=]];
        //$room_revenue_last_year3 = [[=total_queen.room_revenue_last_year=]];
        $minibar3 = [[=total_queen.minibar=]];
        //$minibar_last_year3 = [[=total_queen.minibar_last_year=]];
        $laundry3 = [[=total_queen.laundry=]];
        //$laundry_last_year3 = [[=total_queen.laundry_last_year=]];
        $extra_services3 = [[=total_queen.extra_services=]];
        //$extra_services_last_year3 = [[=total_queen.extra_services_last_year=]];
        $equipment3 = [[=total_queen.equipment=]];
        //$equipment_last_year3 = [[=total_queen.equipment_last_year=]];  
      ?>
    <tr valign="middle" bgcolor="silver">
        <th>[[.total_Allba_Queen.]]</th>
        <td align="right"><strong><?php $total2 = System::display_number(round([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]]));if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.room_revenue=]]/1.155 +[[=total_queen.EI_LO=]]/1.155+ [[=total_queen.minibar=]]/1.155+[[=total_queen.laundry=]]/1.155+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]]));}}else{echo $total2; }  ?></strong></td>
        <td align="right"><strong>0</strong></td>
        <td align="right"><strong>0%</strong></td>
        <td align="right"><strong><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]] + [[=total_queen.minibar_same_period=]]+[[=total_queen.laundry_same_period=]]+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]]));}else if ($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.room_revenue_same_period=]]/1.155+[[=total_queen.EI_LO_same_period=]]/1.155 + [[=total_queen.minibar_same_period=]]/1.155+[[=total_queen.laundry_same_period=]]/1.155+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]]));}}else{echo System::display_number(round([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]] + [[=total_queen.minibar_same_period=]]+[[=total_queen.laundry_same_period=]]+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]]));} ?></strong></td>
        <td align="right">
        	<strong><?php 
                if(([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]] + [[=total_queen.minibar_same_period=]]+[[=total_queen.laundry_same_period=]]+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number(
                            (([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]])*100
                            )/([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]] + [[=total_queen.minibar_same_period=]]+[[=total_queen.laundry_same_period=]]+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number(
                            (([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]])*100
                            )/([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]] + [[=total_queen.minibar_same_period=]]+[[=total_queen.laundry_same_period=]]+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]])).'%';
                        }
                    }
                    else
                    {
                            echo System::display_number(
                            (([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]])*100
                            )/([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]] + [[=total_queen.minibar_same_period=]]+[[=total_queen.laundry_same_period=]]+[[=total_queen.extra_services_same_period=]]+[[=total_queen.equipment_same_period=]])).'%';
                        }
                }
                else
                {
                    echo '100%';
                }
             ?></strong>
        </td>
        <td align="right"><strong><?php $total24 = System::display_number([[=total_queen.total_month_room=]] + [[=total_queen.total_month_minibar=]]+[[=total_queen.total_month_laundry=]]+[[=total_queen.total_month_extra_services=]]); echo $total24; ?></strong></td>
        <td align="right">
           <strong><?php
                if(([[=total_queen.total_month_room=]] + [[=total_queen.total_month_minibar=]]+[[=total_queen.total_month_laundry=]]+[[=total_queen.total_month_extra_services=]])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_queen.room_revenue=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.EI_LO=]]+[[=total_queen.equipment=]])*100
                                        )/([[=total_queen.total_month_room=]] + [[=total_queen.total_month_minibar=]]+[[=total_queen.total_month_laundry=]]+[[=total_queen.total_month_extra_services=]])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_queen.room_revenue=]]/1.155+[[=total_queen.EI_LO=]]/1.155 + [[=total_queen.minibar=]]/1.155+[[=total_queen.laundry=]]/1.155+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]])*100
                                        )/([[=total_queen.total_month_room=]] + [[=total_queen.total_month_minibar=]]+[[=total_queen.total_month_laundry=]]+[[=total_queen.total_month_extra_services=]])).'%';
                        }
                    }
                    else
                    {
                        echo System::display_number( (
                                            ([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]] + [[=total_queen.minibar=]]+[[=total_queen.laundry=]]+[[=total_queen.extra_services=]]+[[=total_queen.equipment=]])*100
                                        )/([[=total_queen.total_month_room=]] + [[=total_queen.total_month_minibar=]]+[[=total_queen.total_month_laundry=]]+[[=total_queen.total_month_extra_services=]])).'%';
                    }
                }
                else
                {
                    echo '0%';
                }
            ?></strong> 
        </td>
    </tr>
    <tr >
        <td> - [[.revenue_room.]]</td>
        <td id="revenue_room" align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.room_revenue=]]/1.155+[[=total_queen.EI_LO=]]/1.155));}}else{echo System::display_number(round([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]])); }?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.room_revenue_same_period=]]/1.155+[[=total_queen.EI_LO_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]]));}?></td>
        <td align="right"><?php if(([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]])>0){echo System::display_number((([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]])*100)/([[=total_queen.room_revenue_same_period=]]+[[=total_queen.EI_LO_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_queen.total_month_room=]]));?></td>
        <td align="right"><?php if([[=total_queen.total_month_room=]]>0){echo System::display_number((([[=total_queen.room_revenue=]]+[[=total_queen.EI_LO=]])*100)/[[=total_queen.total_month_room=]]).'%';}else{echo '100%';}?></td>
    </tr>
    <tr >
        <td> - [[.minibar.]]</td>
        <td align="right" id="minibar"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.minibar=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.minibar=]]/1.155));}}else{echo System::display_number(round([[=total_queen.minibar=]]));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.minibar_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.minibar_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_queen.minibar_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_queen.minibar_same_period=]]>0){echo System::display_number((([[=total_queen.minibar=]])*100)/([[=total_queen.minibar_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_queen.total_month_minibar=]]));?></td>
        <td align="right"><?php if([[=total_queen.total_month_minibar=]]>0){echo System::display_number(([[=total_queen.minibar=]]*100)/[[=total_queen.total_month_minibar=]]).'%';}else{echo '100%';}?></td>   
    </tr >
    <tr >
        <td> - [[.Laundry.]]</td>
        <td align="right" id="laundry"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.laundry=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.laundry=]]/1.155));}}else{echo System::display_number(round([[=total_queen.laundry=]]));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.laundry_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.laundry_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_queen.laundry_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_queen.laundry_same_period=]]>0){echo System::display_number((([[=total_queen.laundry=]])*100)/([[=total_queen.laundry_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number([[=total_queen.total_month_laundry=]]);?></td>
        <td align="right"><?php if([[=total_queen.total_month_laundry=]]>0){echo System::display_number(([[=total_queen.laundry=]]*100)/[[=total_queen.total_month_laundry=]]).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr >
        <td> - [[.other_service.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_queen.extra_services=]]));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.extra_services_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.extra_services_same_period=]]));}}else{echo System::display_number(round([[=total_queen.extra_services_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_queen.extra_services_same_period=]]>0){echo System::display_number((([[=total_queen.extra_services=]])*100)/([[=total_queen.extra_services_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number([[=total_queen.total_month_extra_services=]]);?></td>
        <td align="right"><?php if([[=total_queen.total_month_extra_services=]]>0){echo System::display_number(([[=total_queen.extra_services=]]*100)/[[=total_queen.total_month_extra_services=]]).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr >
        <td> - [[.compensation.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_queen.equipment=]]));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_queen.equipment_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_queen.equipment_same_period=]]));}}else{echo System::display_number(round([[=total_queen.equipment_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_queen.equipment_same_period=]]>0){echo System::display_number((([[=total_queen.equipment=]])*100)/([[=total_queen.equipment_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right">0</td>
        <td align="right">0%</td>   
    </tr>
    <!--/LIST:total_queen-->
    <!--LIST:total_allba-->
    <?php
        $room_revenue4 = [[=total_allba.room_revenue=]];
        //$room_revenue_last_year4 = [[=total_allba.room_revenue_last_year=]];
        $minibar4 = [[=total_allba.minibar=]];
        //$minibar_last_year4 = [[=total_allba.minibar_last_year=]];
        $laundry4 = [[=total_allba.laundry=]];
        //$laundry_last_year4 = [[=total_allba.laundry_last_year=]];
        $extra_services4 = [[=total_allba.extra_services=]];
        //$extra_services_last_year4 = [[=total_allba.extra_services_last_year=]];
        $equipment4 = [[=total_allba.equipment=]];
        //$equipment_last_year4 = [[=total_allba.equipment_last_year=]];  
      ?>
    <tr valign="middle" bgcolor="silver">
        <th>[[.total_Allba.]]</th>
        <td align="right"><strong><?php $total3 = System::display_number(round([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]]));if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.room_revenue=]]/1.155 +[[=total_allba.EI_LO=]]/1.155+ [[=total_allba.minibar=]]/1.155+[[=total_allba.laundry=]]/1.155+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]]));}}else{echo $total3; }  ?></strong></td>
        <td align="right"><strong>0</strong></td>
        <td align="right"><strong>0%</strong></td>
        <td align="right"><strong><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]] + [[=total_allba.minibar_same_period=]]+[[=total_allba.laundry_same_period=]]+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]]));}else if ($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.room_revenue_same_period=]]/1.155+[[=total_allba.EI_LO_same_period=]]/1.155 + [[=total_allba.minibar_same_period=]]/1.155+[[=total_allba.laundry_same_period=]]/1.155+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]]));}}else{echo System::display_number(round([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]] + [[=total_allba.minibar_same_period=]]+[[=total_allba.laundry_same_period=]]+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]]));} ?></strong></td>
        <td align="right">
        	<strong><?php 
                if(([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]] + [[=total_allba.minibar_same_period=]]+[[=total_allba.laundry_same_period=]]+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number(
                            (([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]])*100
                            )/([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]] + [[=total_allba.minibar_same_period=]]+[[=total_allba.laundry_same_period=]]+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number(
                            (([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]])*100
                            )/([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]] + [[=total_allba.minibar_same_period=]]+[[=total_allba.laundry_same_period=]]+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]])).'%';
                        }
                    }
                    else
                    {
                            echo System::display_number(
                            (([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]])*100
                            )/([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]] + [[=total_allba.minibar_same_period=]]+[[=total_allba.laundry_same_period=]]+[[=total_allba.extra_services_same_period=]]+[[=total_allba.equipment_same_period=]])).'%';
                        }
                }
                else
                {
                    echo '100%';
                }
             ?></strong>
        </td>
        <td align="right"><strong><?php $total23 = System::display_number([[=total_allba.total_month_room=]] + [[=total_allba.total_month_minibar=]]+[[=total_allba.total_month_laundry=]]+[[=total_allba.total_month_extra_services=]]); echo $total23; ?></strong></td>
        <td align="right">
            <strong><?php
                if(([[=total_allba.total_month_room=]] + [[=total_allba.total_month_minibar=]]+[[=total_allba.total_month_laundry=]]+[[=total_allba.total_month_extra_services=]])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_allba.room_revenue=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.EI_LO=]]+[[=total_allba.equipment=]])*100
                                        )/([[=total_allba.total_month_room=]] + [[=total_allba.total_month_minibar=]]+[[=total_allba.total_month_laundry=]]+[[=total_allba.total_month_extra_services=]])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number( (
                                            ([[=total_allba.room_revenue=]]/1.155+[[=total_allba.EI_LO=]]/1.155 + [[=total_allba.minibar=]]/1.155+[[=total_allba.laundry=]]/1.155+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]])*100
                                        )/([[=total_allba.total_month_room=]] + [[=total_allba.total_month_minibar=]]+[[=total_allba.total_month_laundry=]]+[[=total_allba.total_month_extra_services=]])).'%';
                        }
                    }
                    else
                    {
                        echo System::display_number( (
                                            ([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]] + [[=total_allba.minibar=]]+[[=total_allba.laundry=]]+[[=total_allba.extra_services=]]+[[=total_allba.equipment=]])*100
                                        )/([[=total_allba.total_month_room=]] + [[=total_allba.total_month_minibar=]]+[[=total_allba.total_month_laundry=]]+[[=total_allba.total_month_extra_services=]])).'%';
                    }
                }
                else
                {
                    echo '0%';
                }
            ?></strong>
        </td>
    </tr>
    <tr >
        <td> - [[.revenue_room.]]</td>
        <td id="revenue_room" align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.room_revenue=]]/1.155+[[=total_allba.EI_LO=]]/1.155));}}else{echo System::display_number(round([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]])); }?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.room_revenue_same_period=]]/1.155+[[=total_allba.EI_LO_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_allba.room_revenue_same_period=]]>0){echo System::display_number((([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]])*100)/([[=total_allba.room_revenue_same_period=]]+[[=total_allba.EI_LO_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_allba.total_month_room=]]));?></td>
        <td align="right"><?php if([[=total_allba.total_month_room=]]>0){echo System::display_number((([[=total_allba.room_revenue=]]+[[=total_allba.EI_LO=]])*100)/[[=total_allba.total_month_room=]]).'%';}else{echo '100%';}?></td>
    </tr>
    <tr >
        <td> - [[.minibar.]]</td>
        <td align="right" id="minibar"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.minibar=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.minibar=]]/1.155));}}else{echo System::display_number(round([[=total_allba.minibar=]]));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.minibar_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.minibar_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_allba.minibar_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_allba.minibar_same_period=]]>0){echo System::display_number((([[=total_allba.minibar=]])*100)/([[=total_allba.minibar_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_allba.total_month_minibar=]]));?></td>
        <td align="right"><?php if([[=total_allba.total_month_minibar=]]>0){echo System::display_number(([[=total_allba.minibar=]]*100)/[[=total_allba.total_month_minibar=]]).'%';}else{echo '100%';}?></td>   
    </tr >
    <tr >
        <td> - [[.Laundry.]]</td>
        <td align="right" id="laundry"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.laundry=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.laundry=]]/1.155));}}else{echo System::display_number(round([[=total_allba.laundry=]]));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.laundry_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.laundry_same_period=]]/1.155));}}else{echo System::display_number(round([[=total_allba.laundry_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_allba.laundry_same_period=]]>0){echo System::display_number((([[=total_allba.laundry=]])*100)/([[=total_allba.laundry_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=total_allba.total_month_laundry=]]));?></td>
        <td align="right"><?php if([[=total_allba.total_month_laundry=]]>0){echo System::display_number(([[=total_allba.laundry=]]*100)/[[=total_allba.total_month_laundry=]]).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr >
        <td> - [[.other_service.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_allba.extra_services=]]));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.extra_services_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.extra_services_same_period=]]));}}else{echo System::display_number(round([[=total_allba.extra_services_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_allba.extra_services_same_period=]]>0){echo System::display_number((([[=total_allba.extra_services=]])*100)/([[=total_allba.extra_services_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number([[=total_allba.total_month_extra_services=]]);?></td>
        <td align="right"><?php if([[=total_allba.total_month_extra_services=]]>0){echo System::display_number(([[=total_allba.extra_services=]]*100)/[[=total_allba.total_month_extra_services=]]).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr >
        <td> - [[.compensation.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=total_allba.equipment=]]));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=total_allba.equipment_same_period=]]));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=total_allba.equipment_same_period=]]));}}else{echo System::display_number(round([[=total_allba.equipment_same_period=]]));}?></td>
        <td align="right"><?php if([[=total_allba.equipment_same_period=]]>0){echo System::display_number((([[=total_allba.equipment=]])*100)/([[=total_allba.equipment_same_period=]])).'%';}else{echo '0%';} ?></td>
        <td align="right">0</td>
        <td align="right">0%</td>   
    </tr>
    <!--/LIST:total_allba-->
    <?php
        $room_revenue5 = [[=grand_total=]]['room_revenue'];
        //$room_revenue_last_year5 = [[=grand_total=]]['room_revenue_last_year'];
        $minibar5 = [[=grand_total=]]['minibar'];
        //$minibar_last_year5 = [[=grand_total=]]['minibar_last_year'];
        $laundry5 = [[=grand_total=]]['laundry'];
        //$laundry_last_year5 = [[=grand_total=]]['laundry_last_year'];
        $extra_services5 = [[=grand_total=]]['extra_services'];
        //$extra_services_last_year5 = [[=grand_total=]]['extra_services_last_year'];
        $equipment5 = [[=grand_total=]]['equipment'];
        //$equipment_last_year5 = [[=grand_total=]]['equipment_last_year'];  
      ?>
    <tr valign="middle" bgcolor="silver">
        <th>[[.total_Thanh_Tan.]]</th>
        <td align="right"><strong><?php $total5 = System::display_number(round([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment']));if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue']/1.155+[[=grand_total=]]['EI_LO']/1.155 + [[=grand_total=]]['minibar']/1.155+[[=grand_total=]]['laundry']/1.155+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']/1.155+[[=total_resort=]]['EI_LO']/1.155 + [[=total_resort=]]['minibar']/1.155+[[=total_resort=]]['laundry']/1.155+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment']));}}else{echo $total5; }  ?></strong></td>
        <td align="right"><strong>0</strong></td>
        <td align="right"><strong>0%</strong></td>
        <td align="right"><strong><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period'] + [[=grand_total=]]['minibar_same_period']+[[=grand_total=]]['laundry_same_period']+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']));}else if ($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue_same_period']/1.155+[[=grand_total=]]['EI_LO_same_period']/1.155 + [[=grand_total=]]['minibar_same_period']/1.155+[[=grand_total=]]['laundry_same_period']/1.155+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['room_revenue_same_period']/1.155+[[=total_resort=]]['EI_LO_same_period']/1.155 + [[=total_resort=]]['minibar_same_period']/1.155+[[=total_resort=]]['laundry_same_period']/1.155+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']));}}else{echo System::display_number(round([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period'] + [[=grand_total=]]['minibar_same_period']+[[=grand_total=]]['laundry_same_period']+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']));} ?></strong></td>
        <td align="right">
        	<strong><?php
                if(([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period'] + [[=grand_total=]]['minibar_same_period']+[[=grand_total=]]['laundry_same_period']+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number(
                            (([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])*100
                            )/([[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period']+[[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period'] + [[=grand_total=]]['minibar_same_period']+[[=grand_total=]]['laundry_same_period']+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period'])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number(
                            (([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period'] + [[=grand_total=]]['minibar_same_period']+[[=grand_total=]]['laundry_same_period']+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])*100
                            )/([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])).'%';
                        }
                    }
                    else
                    {
                            echo System::display_number(
                            (([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                            )/([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period'] + [[=grand_total=]]['minibar_same_period']+[[=grand_total=]]['laundry_same_period']+[[=grand_total=]]['extra_services_same_period']+[[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period'] + [[=total_resort=]]['minibar_same_period']+[[=total_resort=]]['laundry_same_period']+[[=total_resort=]]['extra_services_same_period']+[[=total_resort=]]['equipment_same_period'])).'%';
                        }
                }
                else
                {
                    echo '100%';
                }
            ?></strong>
        </td>
        <td align="right"><strong><?php $total25 = System::display_number([[=grand_total=]]['total_month_room'] + [[=grand_total=]]['total_month_laundry'] + [[=grand_total=]]['total_month_extra_services']+[[=grand_total=]]['total_month_minibar']+[[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar']); echo $total25; ?></strong></td>
        <td align="right">
            <strong><?php
                if(([[=grand_total=]]['total_month_room'] + [[=grand_total=]]['total_month_laundry'] + [[=grand_total=]]['total_month_extra_services']+[[=grand_total=]]['total_month_minibar'])>0)
                {
                    if(isset($_POST['do_search']))
                    {
                        if($_POST['tax']=='after_tax')
                        {
                            echo System::display_number( (
                                            ([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                                        )/([[=grand_total=]]['total_month_room'] + [[=grand_total=]]['total_month_laundry'] + [[=grand_total=]]['total_month_extra_services']+[[=grand_total=]]['total_month_minibar']+[[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])).'%';
                        }
                        else if($_POST['tax']=='before_tax')
                        {
                            echo System::display_number( (
                                            ([[=grand_total=]]['room_revenue']/1.155+[[=grand_total=]]['EI_LO']/1.155 + [[=grand_total=]]['minibar']/1.155+[[=grand_total=]]['laundry']/1.155+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']/1.155+[[=total_resort=]]['EI_LO']/1.155 + [[=total_resort=]]['minibar']/1.155+[[=total_resort=]]['laundry']/1.155+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                                        )/([[=grand_total=]]['total_month_room'] + [[=grand_total=]]['total_month_laundry'] + [[=grand_total=]]['total_month_extra_services']+[[=grand_total=]]['total_month_minibar']+[[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])).'%';
                        }
                    }
                    else
                    {
                        echo System::display_number( (
                                            ([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO'] + [[=grand_total=]]['minibar']+[[=grand_total=]]['laundry']+[[=grand_total=]]['extra_services']+[[=grand_total=]]['equipment']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'] + [[=total_resort=]]['minibar']+[[=total_resort=]]['laundry']+[[=total_resort=]]['extra_services']+[[=total_resort=]]['equipment'])*100
                                        )/([[=grand_total=]]['total_month_room'] + [[=grand_total=]]['total_month_laundry'] + [[=grand_total=]]['total_month_extra_services']+[[=grand_total=]]['total_month_minibar']+[[=total_resort=]]['total_month_room'] + [[=total_resort=]]['total_month_laundry'] + [[=total_resort=]]['total_month_extra_services']+[[=total_resort=]]['total_month_minibar'])).'%';
                    }
                }
                else
                {
                    echo '0%';
                }
            ?></strong>
        </td>
    </tr>
    <tr bgcolor="silver" >
        <td> - [[.revenue_room.]]</td>
        <td id="revenue_room" align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue']/1.155+[[=grand_total=]]['EI_LO']/1.155+[[=total_resort=]]['room_revenue']/1.155+[[=total_resort=]]['EI_LO']/1.155));}}else{echo System::display_number(round([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'])); }?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['room_revenue_same_period']/1.155+[[=grand_total=]]['EI_LO_same_period']/1.155+[[=total_resort=]]['room_revenue_same_period']/1.155+[[=total_resort=]]['EI_LO_same_period']/1.155));}}else{echo System::display_number(round([[=grand_total=]]['room_revenue_same_period']+[[=grand_total=]]['EI_LO_same_period']+[[=total_resort=]]['room_revenue_same_period']+[[=total_resort=]]['EI_LO_same_period']));}?></td>
        <td align="right"><?php if([[=grand_total=]]['room_revenue_same_period']>0){echo System::display_number((([[=grand_total=]]['room_revenue']+[[=total_resort=]]['room_revenue'])*100)/([[=grand_total=]]['room_revenue_same_period']+[[=total_resort=]]['room_revenue_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=grand_total=]]['total_month_room']+[[=total_resort=]]['total_month_room']));?></td>
        <td align="right"><?php if([[=grand_total=]]['total_month_room']>0){echo System::display_number((([[=grand_total=]]['room_revenue']+[[=grand_total=]]['EI_LO']+[[=total_resort=]]['room_revenue']+[[=total_resort=]]['EI_LO'])*100)/([[=grand_total=]]['total_month_room']+[[=total_resort=]]['total_month_room'])).'%';}else{echo '100%';}?></td>
    </tr>
    <tr bgcolor="silver" >
        <td> - [[.minibar.]]</td>
        <td align="right" id="minibar"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['minibar']+[[=total_resort=]]['minibar']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['minibar']/1.155+[[=total_resort=]]['minibar']/1.155));}}else{echo System::display_number(round([[=grand_total=]]['minibar']+[[=total_resort=]]['minibar']));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['minibar_same_period']+[[=total_resort=]]['minibar_same_period']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['minibar_same_period']/1.155+[[=total_resort=]]['minibar_same_period']/1.155));}}else{echo System::display_number(round([[=grand_total=]]['minibar_same_period']+[[=total_resort=]]['minibar_same_period']));}?></td>
        <td align="right"><?php if([[=grand_total=]]['minibar_same_period']>0){echo System::display_number((([[=grand_total=]]['minibar']+[[=total_resort=]]['minibar'])*100)/([[=grand_total=]]['minibar_same_period']+[[=total_resort=]]['minibar_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=grand_total=]]['total_month_minibar']+[[=total_resort=]]['total_month_minibar']));?></td>
        <td align="right"><?php if([[=grand_total=]]['total_month_minibar']>0){echo System::display_number((([[=grand_total=]]['minibar']+[[=total_resort=]]['minibar'])*100)/([[=grand_total=]]['total_month_minibar']+[[=total_resort=]]['total_month_minibar'])).'%';}else{echo '100%';}?></td>   
    </tr >
    <tr bgcolor="silver" >
        <td> - [[.Laundry.]]</td>
        <td align="right" id="laundry"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['laundry']+[[=total_resort=]]['laundry']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['laundry']/1.155+[[=total_resort=]]['laundry']/1.155));}}else{echo System::display_number(round([[=grand_total=]]['laundry']+[[=total_resort=]]['laundry']));}?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php if(isset($_POST['do_search'])){if($_POST['tax']=='after_tax'){echo System::display_number(round([[=grand_total=]]['laundry_same_period']+[[=total_resort=]]['laundry_same_period']));}else if($_POST['tax']=='before_tax'){echo System::display_number(round([[=grand_total=]]['laundry_same_period']/1.155+[[=total_resort=]]['laundry_same_period']/1.155));}}else{echo System::display_number(round([[=grand_total=]]['laundry_same_period']+[[=total_resort=]]['laundry_same_period']));}?></td>
        <td align="right"><?php if([[=grand_total=]]['laundry_same_period']>0){echo System::display_number((([[=grand_total=]]['laundry'])*100)/([[=grand_total=]]['laundry_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number(round([[=grand_total=]]['total_month_laundry']+[[=total_resort=]]['total_month_laundry']));?></td>
        <td align="right"><?php if([[=grand_total=]]['total_month_laundry']>0){echo System::display_number((([[=grand_total=]]['laundry']+[[=total_resort=]]['laundry'])*100)/([[=grand_total=]]['total_month_laundry']+[[=total_resort=]]['total_month_laundry'])).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr bgcolor="silver" >
        <td> - [[.other_service.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=grand_total=]]['extra_services']+[[=total_resort=]]['extra_services']));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php echo System::display_number(round([[=grand_total=]]['extra_services_same_period']+[[=total_resort=]]['extra_services_same_period']));?></td>
        <td align="right"><?php if([[=grand_total=]]['extra_services_same_period']>0){echo System::display_number((([[=grand_total=]]['extra_services']+[[=total_resort=]]['extra_services'])*100)/([[=grand_total=]]['extra_services_same_period']+[[=total_resort=]]['extra_services_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right"><?php echo System::display_number([[=grand_total=]]['total_month_extra_services']+[[=total_resort=]]['total_month_extra_services']);?></td>
        <td align="right"><?php if([[=grand_total=]]['total_month_extra_services']>0){echo System::display_number((([[=grand_total=]]['extra_services']+[[=total_resort=]]['extra_services'])*100)/([[=grand_total=]]['total_month_extra_services']+[[=total_resort=]]['total_month_extra_services'])).'%';}else{echo '100%';}?></td>   
    </tr>
    <tr bgcolor="silver" >
        <td> - [[.compensation.]]</td>
        <td align="right" id="other_service"><?php echo System::display_number(round([[=grand_total=]]['equipment']+[[=total_resort=]]['equipment']));?></td>
        <td align="right">0</td>
        <td align="right">0%</td>
        <td align="right"><?php echo System::display_number(round([[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['equipment_same_period'])) ?></td>
        <td align="right"><?php if([[=grand_total=]]['equipment_same_period']>0){echo System::display_number((([[=grand_total=]]['equipment']+[[=total_resort=]]['equipment'])*100)/([[=grand_total=]]['equipment_same_period']+[[=total_resort=]]['equipment_same_period'])).'%';}else{echo '0%';} ?></td>
        <td align="right">0</td>
        <td align="right">0%</td>   
    </tr>
     
</table>
<script>
        $('from_date').value = '[[|from_date|]]';
		$('to_date').value = '[[|to_date|]]';
		function check_from_date(){
			//alert($('from_date').value);
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((from_date[1] > to_date[1]) || (from_date[2] > to_date[2])){
				$('to_date').value = $('from_date').value;
			}else{
				if((from_date[0] > to_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2]) ){
					$('to_date').value = $('from_date').value;	
				}	
			}
		}
		function check_to_date(){
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((to_date[1] < from_date[1]) || ( to_date[2] < from_date[2])){
				$('from_date').value = $('to_date').value;
			}else{
				if((to_date[0] < from_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2])){
					$('from_date').value = $('to_date').value;
				}
			}
		}
		jQuery("#from_date").datepicker({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
        
        
</script>