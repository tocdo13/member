     	<table style="width:100%">
        	<tr>
            	<td valign="top" width="100%" align="center">
                <form name="WeeklyRevenueThuyForm" method="post">
                <table border="0" style="margin:auto;">
                    <tr style="text-align:center;">
                    	<td>
                        <font class="report_title" style="font-size:20px; text-align:center;"><b>[[.daily_overview_report.]]</b></font>
                        </td>
                        <td style="padding-left:10px;">[[.date_from.]]:</td>
                        <td>
                        <input type="text" name="day" id="day" class="date-input"/>
                         <script>
                        $('day').value='<?php if(Url::get('day')){echo Url::get('day');}else{ echo (date('d/m/Y'));}?>';
                        </script>
                        </td>
                        <td>
                        <input type="submit" name="do_search" value="  [[.report.]]  ">
                        </td>
                    </tr>
                  </table>
                </form>
                </td>
                <td>
                </td>
            </tr>
        </table>
    </td>
<table style="width:100%">
	<tr>
    	<td style="width:50%">
			<fieldset>
                 <legend class="title">[[.chart_revenue_pie_in_day.]]</legend>
                 <div id="container" style="height:300px; margin:0px; padding-top:0px;"></div>
			</fieldset>        
        </td>
        <td style="width:50%">
        	<fieldset>
                 <legend class="title">[[.chart_revenue_column_in_month.]]</legend>
                 <div id="charbar" style="height:300px; margin:0px; padding-top:0px;"></div>
			</fieldset>
        </td>
    </tr>
    <tr>
    	<td>
        	       <fieldset>
                        <legend class="title">[[.room_details.]]</legend>
                       <table width="100%" border="0">
                          <tr class="row-odd">
                                <td width="70%">[[.today_ocuppied_rooms.]]</td>
                                <td>[[|today_ocuppied_rooms|]]</td>
                          </tr>
                          <tr class="row-even">
                                <td>[[.today_check_ins.]]</td><td>[[|today_check_ins|]]</td>
                          </tr>
                          <tr class="row-odd">
                                <td>[[.today_check_outs.]]</td><td>[[|today_check_outs|]]</td>
                          </tr>
                          <tr class="row-even">
                                <td>[[.today_bookeds.]]</td><td>[[|today_bookeds|]]</td>
                          </tr>
                          <tr class="row-odd">
                                <td>[[.today_no_shows.]]</td><td>[[|today_no_shows|]]</td>
                          </tr>
                          <tr class="row-even">
                                <td>[[.today_cancellations.]]</td><td>[[|today_cancellations|]]</td>
                          </tr>
                        </table>
                        </fieldset>                        
        </td>
        <td valign="top">
        			<fieldset>
                             <legend class="title">[[.housekeeping_details.]]</legend>
                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr class="row-odd">
                                    <td width="70%">[[.checked_out_rooms_marked_dirty.]]</td>
                                    <td>[[|checked_out_rooms_marked_dirty|]]</td>
                                </tr>
                                <tr class="row-even">
                                    <td>[[.repairing_rooms.]]</td>
                                    <td>[[|repairing_rooms|]]</td>
                                </tr>
                                <tr class="row-odd">
                                    <td>[[.occupied_rooms_marked_for_dirty.]]</td>
                                    <td>[[|occupied_rooms_marked_for_dirty|]]</td>
                                </tr>
                            </table>
                    </fieldset>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
                <fieldset>
                    <legend class="title">[[.revenue_list.]]</legend>
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr class="row-even" <?php if(User::can_admin(false, ANY_CATEGORY)){?> title="Double click to show or hide Revenue detail" ondblclick="if(jQuery('#occupied_revenue_table').css('display')=='none'){jQuery('#occupied_revenue_table').show();}else{jQuery('#occupied_revenue_table').hide();} return false;" <?php }?>>
                                <td>[[.occupied_revenue.]]</td>
                                <td align="right"><strong>[[|total|]]</strong></td>
                            </tr>
                                <!--IF:cond_can_admin(User::can_admin(false, ANY_CATEGORY))-->
                            <tr class="row-odd">
                                <td colspan="2">
                                    <table id="occupied_revenue_table" width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" bgcolor="#FFFFCC">
                                    <tr class="table-header">
                                        <th>[[.guest_name.]]</th>
                                        <th>[[.room_level.]]</th>
                                        <th>[[.room_name.]]</th>
                                        <th>[[.arrival_time.]]</th>
                                        <th>[[.departure_time.]]</th>
                                        <th>[[.group.]]/[[.tour.]]</th>
                                        <th>[[.company.]]</th>
                                        <th width="5%">[[.rate.]] (usd)</th>
                                    </tr>
                                   <!--LIST:traveller-->
                                    <tr>
                                        <td>[[|traveller.guest_name|]]</td>
                                        <td>[[|traveller.room_level_name|]]</td>
                                        <td>[[|traveller.room_name|]]</td>
                                        <td>[[|traveller.arrival_time|]]</td>
                                        <td>[[|traveller.departure_time|]]</td>
                                        <td>[[|traveller.tour_name|]]</td>
                                        <td>[[|traveller.company_name|]]</td>
                                        <td align="right">[[|traveller.change_price|]]</td>
                                    </tr>
                                 <!--/LIST:traveller-->
                                </table>
                            <!--IF:cond(![[=traveller=]])--><div class="notice">[[.no_room.]]</div><!--/IF:cond-->
                            </td>
                        </tr>
                        <!--/IF:cond_can_admin-->
                        <tr class="row-odd">
                            <td>[[.booking_revenue.]]</td>
                            <td  align="right">[[|booking_revenue|]]</td>
                        </tr>
                        <tr class="row-even">
                            <td>[[.no_show_revenue.]]</td>
                            <td align="right">[[|no_show_revenue|]]</td>
                        </tr>
                        <tr class="row-odd">
                            <td>[[.minibar_revenue.]]</td>
                            <td align="right">[[|minibar_revenue|]]</td>
                        </tr>
                        <tr class="row-even">
                            <td>[[.laundry_revenue.]]</td>
                            <td align="right">[[|laundry_revenue|]]</td>
                        </tr>
                        <tr class="row-odd">
                            <td>[[.compensation_revenue.]]</td>
                            <td align="right">[[|compensation_revenue|]]</td>                                  </tr>
                        <tr class="row-even">
                            <td>[[.restaurant_revenue.]]</td>
                            <td align="right">[[|restaurant_revenue|]]</td>
                        </tr>
                        <!--IF:cond(HAVE_KARAOKE)-->
                        <tr class="row-odd">
                            <td>[[.karaoke_revenue.]]</td>
                            <td align="right">[[|karaoke_revenue|]</td>
                        </tr>
                        <!--/IF:cond-->
                        <!--IF:cond(HAVE_MASSAGE)-->
                        <tr class="row-even">
                            <td>[[.massage_revenue.]]</td>
                            <td align="right">[[|massage_revenue|]]</td>
                        </tr>
                        <!--/IF:cond-->
                        <!--IF:cond(HAVE_TENNIS)-->
                        <tr class="row-odd">
                            <td>[[.tennis_revenue.]]</td>
                            <td align="right">[[|tennis_revenue|]]</td>                                 
                        </tr>
                        <!--/IF:cond-->
                        <!--IF:cond(HAVE_SWIMMING)-->
                        <tr class="row-even">
                            <td>[[.swimming_pool_revenue.]]</td>
                            <td align="right">[[|swimming_pool_revenue|]]</td>
                        </tr>
                        <!--/IF:cond-->
                        <tr class="row-odd">
                            <td>[[.extra_service_revenue.]]</td>
                            <td align="right">[[|extra_service_revenue|]]</td>
                        </tr>
                        <tr class="row-even">
                            <td><strong>[[.total_revenue.]]</strong></td>
                            <td align="right"><strong>[[|total|]]</strong></td>
                        </tr>
                        </table>
                </fieldset>
        </td>
    </tr>
</table>
<script>
jQuery("#day").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
</script>				
<script type="text/javascript">
	var chart;
	var pieitems = [[|pieitems|]];
	var data_items = [];
	j = 0;
	tong =0;
	var enddate = '<?php if(Url::get('day')){echo Url::get('day');}else{ echo (date('d/m/Y'));} ?>';
	var title ='[[.date.]] ' + enddate;
	var curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
	for(i in pieitems){
		data_items[j] = [];
		data_items[j][0] = pieitems[i]['name'];
		if(curency == 'VND'){
			title = 'ĐƠN VỊ 1000';
			data_items[j][1] = to_numeric( pieitems[i]['total']/1000);
			tong += data_items[j][1];
		}else{
			data_items[j][1] = to_numeric( pieitems[i]['total']);
		}
		j++;
	}
	jQuery(document).ready(function() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: title ,
				style: {
						color: '#3E576F',
						fontSize: '12px',
						marginTop: '0px',
						padding:'0px',
					}
			},
			tooltip: {
				formatter: function() {
						tong = 	(this.y/this.percentage)*100;		
					return ''+ this.point.name +':<b>'+ roundNumber(this.percentage,1) +'</b> % <br/>'+
							'[[.room_revenue.]]:<b>' + number_format(this.y)+'</b>' + curency + '<br/>'
							+'[[.total.]]: <b>' + number_format(tong) +'</b>' + curency ;
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
							return '<b>'+ this.point.name +'</b>('+ roundNumber(this.percentage,1) +'%)<b>'+ this.y+ '</b> '+curency+'';
						}
					},
					showInLegend: true
				}
			},
			series: [{
				type: 'pie',
				name: '[[.piechart_room_revenue.]]',
				data:data_items
				
			}]
		});
	});				
</script>
	<script type="text/javascript">
			var chart1;
			var columnitems = [[|columnitems|]];
			var fromdate = '1/1/2011';
			jQuery(document).ready(function() {
				var colors = Highcharts.getOptions().colors, 
				data = [];
				j = 0;
				categories = [];
				var maxdata = 0;
				var total = 0;
				var pencent = 0;
				for(i in columnitems){
					categories[j] = columnitems[i]['name'];
					data[j] = {}
					if(to_numeric(columnitems[i]['total']) > maxdata){
						maxdata = to_numeric(columnitems[i]['total']);
					}
					data[j]['y'] = to_numeric(columnitems[i]['total']);
					total += to_numeric(columnitems[i]['total']);
					data[j]['color'] = colors[j];
					j++;
				}		
				maxdata = maxdata + 5;
					name = '[[.renevue.]]' + ' [[.from.]] ' + fromdate + '- [[.to.]] ' + enddate,				
				chart1 = new Highcharts.Chart({
					chart: {
						renderTo: 'charbar', 
						type: 'column'
					},
					title: {
						text: '' 
					},
					xAxis: {
						categories: categories			
					},
					yAxis: {
						min:0,
						max:maxdata,
						title: {
							text: ''
						}
					},
					tooltip: {
						formatter: function() {
							var point = this.point,
							   pencent = Math.round((this.y/total)*100);
								s = this.x +': <b>'+ this.y +'</b>'+ curency + '('+pencent +'%)<br/>'+
								'[[.total.]]: <b>' + total +'</b>' +curency ;
							return s;
						}
					},
					series: [{
						 name: name,
						data: data, 
						color: 'white'
					}]
				});
			});
</script>
		