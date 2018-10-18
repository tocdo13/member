<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
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
                            <font class="report_title specific" >[[.revenue_situation_report_common.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.date.]]&nbsp;[[|date|]]
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
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td>[[.date.]]</td>
                                    <td>
                                        <input name="date" type="text" id="date" style="width:100px;" class="by-year"/>
                                    </td>
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
.table-bound{margin: 0 auto !important;}
.desc{text-align: left !important;}
.main_title{text-align:left}
.sub_title_1{text-align:left; text-indent: 20px;}
.sub_title_2{text-align:left; text-indent: 40px;}
.sub_title_3{text-align:left; text-indent: 60px;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date').datepicker();
});
</script>



<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="150px" >[[.quota.]]</th>
		<th class="report_table_header" width="100px" >[[.in_date.]]</th>
        <th class="report_table_header" width="100px" >[[.total_in_month.]]</th>
        <th class="report_table_header" width="100px" >[[.same_period_last_year.]]</th>
        <th class="report_table_header" width="100px" >[[.compare.]](%) [[|year|]]/[[|last_year|]]</th>
    </tr>
    
    <tr >
		<td class="report_table_header sub_title_1" >1. [[.room_revenue.]]</td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_ROOM_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_ROOM_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_ROOM_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_ROOM_IN_MONTH=]]*100/([[=TOTAL_ROOM_LAST_YEAR=]]?[[=TOTAL_ROOM_LAST_YEAR=]]:1)); ?></td>
    </tr>
    
    <tr >
		<th class="report_table_header sub_title_1" >2. [[.housekeeping.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_HOUSEKEEPING_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_HOUSEKEEPING_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_HOUSEKEEPING_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_HOUSEKEEPING_IN_MONTH=]]*100/([[=TOTAL_HOUSEKEEPING_LAST_YEAR=]]?[[=TOTAL_HOUSEKEEPING_LAST_YEAR=]]:1)); ?></td>
    </tr>
    
    
    <tr >
		<th class="report_table_header sub_title_1" >3. [[.extra_sevice.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_EXTRASERVICE_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_EXTRASERVICE_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_EXTRASERVICE_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_EXTRASERVICE_IN_MONTH=]]*100/([[=TOTAL_EXTRASERVICE_LAST_YEAR=]]?[[=TOTAL_EXTRASERVICE_LAST_YEAR=]]:1)); ?></td>
    </tr>
    <tr >
		<th class="report_table_header sub_title_1" >4. [[.spa.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SPA_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SPA_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SPA_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SPA_IN_MONTH=]]*100/([[=TOTAL_SPA_LAST_YEAR=]]?[[=TOTAL_SPA_LAST_YEAR=]]:1)); ?></td>
    </tr>
    <tr >
		<th class="report_table_header sub_title_1" >5. [[.sales.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SALE_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SALE_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SALE_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_SALE_IN_MONTH=]]*100/([[=TOTAL_SALE_LAST_YEAR=]]?[[=TOTAL_SALE_LAST_YEAR=]]:1)); ?></td>
    </tr>
    <tr >
		<th class="report_table_header sub_title_1" >6. [[.ticket.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_TICKET_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_TICKET_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_TICKET_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_TICKET_IN_MONTH=]]*100/([[=TOTAL_TICKET_LAST_YEAR=]]?[[=TOTAL_TICKET_LAST_YEAR=]]:1)); ?></td>
    </tr>
    <tr>
		<th class="report_table_header sub_title_1" >7. [[.bar.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_BAR_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_BAR_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_BAR_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_BAR_IN_MONTH=]]*100/([[=TOTAL_BAR_LAST_YEAR=]]?[[=TOTAL_BAR_LAST_YEAR=]]:1)); ?></td>
    </tr>
    <tr style="font-weight: bold;">
		<th class="report_table_header sub_title_1" >[[.total.]] [[.revenue.]]</th>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_IN_DATE=]]); ?></td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_IN_MONTH=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_LAST_YEAR=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=TOTAL_IN_MONTH=]]*100/([[=TOTAL_LAST_YEAR=]]?[[=TOTAL_LAST_YEAR=]]:1)); ?></td>
    </tr>
</table>
<p><strong>Ghi chú: Doanh thu đã bao gồm 5% phí dịch vụ và 10% thuế GTGT</strong></p>
<table width="100%" >
    <tr>
        <td width="50%"><div id="in_date" style="height: 400; margin: 0 auto; " ></div></td>
        <td><div id="in_month" style="height: 400; margin: 0 auto; " ></div></td>
    </tr>
    
    <tr >
        <td colspan="2"  align="center" style="font-weight: bold;" > [[.price_unit.]]:&nbsp; <?php  echo(HOTEL_CURRENCY=='VND'?('1000'.HOTEL_CURRENCY):HOTEL_CURRENCY); ?></td>
    </tr>
</table>
<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td></td>
	<td>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >[[.general_accountant.]]</td>
	<td width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script type="text/javascript" >
    full_screen();
    var chart_date;
    var chart_month;
    var indate = '[[.in_date.]]';
    var inmonth = '[[.in_mon.]]';
    var data_date = [];
    var data_month = [];
    var array_names = ['[[.room_revenue.]]'
                        ,'[[.housekeeping.]]'
                        ,'[[.extra_sevice.]]'
                        ,'[[.spa.]]'
                        ,'[[.sales.]]'
                        ,'[[.ticket.]]'
                        ,'[[.bar.]]'];
    var items = [[|items|]];
    j = 0;
    tong = 0;
    
    var curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
    for(i in items)
    {
        data_date[j] = [];
        data_month[j] = [];
        data_date[j][0] = array_names[j];
        data_month[j][0] = array_names[j];
        if(curency == 'VND')
        {
            data_date[j][1] = to_numeric(items[i]['total_date']/1000);
            data_month[j][1] = to_numeric(items[i]['total_month']/1000);
        }
        else
        {
            data_date[j][1] = to_numeric(items[i]['total_date']);
            data_month[j][1] = to_numeric(items[i]['total_month']);
        }
        j++;
    }
    jQuery(document).ready(function()
    {
        chart_date = new Highcharts.Chart(
        {
            chart:{
                margin: 80,
                renderTo:'in_date',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            colors: [
               '#890d43', 
               '#ff0000', 
               '#0099ff', 
               '#99ff00', 
               '#003322', 
               '#001d59',
               '#fc5403'
            ],
            title:{
                margin: 30,
                text: '[[.room_revenue_comparing_bar_chart.]] '+indate.toLowerCase()
            },
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                                return this.point.name + '(' + roundNumber(this.percentage,1) + ' %) '+ number_format(this.y);
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '10px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
                    
				}
            },
            legend: {
                enabled: false
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_room_revenue.]]',
				data:data_date
			}]
        });
        
        chart_month = new Highcharts.Chart(
        {
            chart:{
                margin: 80,
                renderTo:'in_month',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            colors: [
               '#890d43', 
               '#ff0000', 
               '#0099ff', 
               '#99ff00', 
               '#003322', 
               '#001d59',
               '#fc5403'
            ],
            title:{
                margin: 30,
                text: '[[.room_revenue_comparing_bar_chart.]] '+inmonth.toLowerCase()
            },
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                                return this.point.name +'('+ roundNumber(this.percentage,1) +' %) '+number_format(this.y);
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '10px'
                        }
					},
					showInLegend: true
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
                    
				}
            },
            legend: {
               enabled: false
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_room_revenue.]]',
				data:data_month
			}]
        });
    });
    
</script>