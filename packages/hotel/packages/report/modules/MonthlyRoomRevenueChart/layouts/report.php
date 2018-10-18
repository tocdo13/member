<table width="100%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />ADD: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong>[[.template_code.]]</strong></td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.montly_revenue_chart_year.]]</b></font>
		<br><br />
		<form name="WeeklyRevenueThuyForm" method="post">
		<table style=" padding-left:18px; padding-right:18px;" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td>[[.month_from.]]:&nbsp;&nbsp;</td>
            	<td>
                    <select  name="month_from" id="month_from" onchange="change_to();">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                   <script>
                  	    $('month_from').value='<?php if(Url::get('month_from')){echo Url::get('month_from');}else{ echo('1');}?>';
						function change_to(){
							var from = to_numeric($('month_from').value);
							var to = to_numeric($('month_to').value);
							var year_from = to_numeric($('year_from').value);
							var year_to = to_numeric($('year_to').value);
							if((year_from == year_to)&&(to < from)){
								$('month_to').value = from;
							}
						}
                  </script>
                </td>
                <td>[[.year.]]</td>
                <td>
                <select  name="year_from" id="year_from" onchange="change_year_to();">
					<?php
                        for($i=date('Y')+5;$i>=1990;$i--)
                        {
                            echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
                        }
                    ?>
                    </select>
                    <script>
                  	    $('year_from').value='<?php if(Url::get('year_from')){echo Url::get('year_from');}?>';
						function change_year_to(){
							var year_from = to_numeric($('year_from').value);
							var year_to = to_numeric($('year_to').value);
							var from = to_numeric($('month_from').value);
							var to = to_numeric($('month_to').value);
							if(year_to <= year_from){
								$('year_to').value = year_from;
								if(to < from){
									$('month_to').value = from;
								}
							}
						}
                  </script>
                </td>
                <td>[[.month_to.]]:&nbsp;&nbsp;</td>
                <td>
                	<select  name="month_to" id="month_to" onchange="change_from();">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                  <script>
                  	    $('month_to').value='<?php if(Url::get('month_to')){echo Url::get('month_to');}else{ echo date('m');}?>';
						function change_from(){
							var year_from = to_numeric($('year_from').value);
							var year_to = to_numeric($('year_to').value);
							var from = to_numeric($('month_from').value);
							var to = to_numeric($('month_to').value);
							if((to <from) && (year_from == year_to)){
								$('month_from').value = to;
							}
						}
                  </script>
                </td>
                <td>[[.year.]]</td>
                <td>
                	<select  name="year_to" id="year_to" onchange="change_year_from();">
					<?php
                        for($i=date('Y')+5;$i>=1990;$i--)
                        {
                            echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
                        }
                    ?>
                    </select>
                    <script>
                  	    $('year_to').value='<?php if(Url::get('year_to')){echo Url::get('year_to');}?>';
						function change_year_from(){
							var year_from = to_numeric($('year_from').value);
							var year_to = to_numeric($('year_to').value);
							var from = to_numeric($('month_from').value);
							var to = to_numeric($('month_to').value);
							if(year_to <= year_from){
								$('year_from').value =year_to;
								if(to < from){
									$('month_from').value = to;
								}
							}
						}
                  </script>
                </td>
                <td>
                <input type="submit" name="do_search" value="[[.report.]]">
                </td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr>
          </table>
	</form>
	</td></tr>
    
    </table>
</td>
</tr>
<tr>
<td><div id="container"></div></td>
</tr>
</table>
<script type="text/javascript">
	var items = [[|items|]];
	var curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
	var title = '';
	if(curency='VND'){
		title='[[.unit.]] 1000 VND';
	}
	var $money=0;
	var chart;
	jQuery(document).ready(function() {
		var ox = [];
		var j = 0;
		var oy = [];
		var max_amount = 0;
		for(i in items){
			ox[j] = + items[i]['month'];
			if(curency == 'VND'){
				oy[j] = to_numeric(items[i]['amount']/1000);
			}else{
				oy[j] = to_nummeric(items[i]['amount']);
			}
			if(max_amount < oy[j])
			{
				max_amount = oy[j]
			}
			j++;
		}
		max_amount +=2;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line'
			},
			title: {
				enabled: false,
				text: title
			},
			xAxis: {
				categories: ox
			},
			yAxis: {
				min:0,
				title: {
					text: '[[.month_revenue.]]'
				}
			}
			,
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true,
                        formatter: function() {
        					return number_format(this.y);
        				}
					},
				}
			},
			legend: {
				enabled: false
			},
			tooltip:{
				formatter: function() {
					s ='[[.month.]]:'+this.x+'<br/><b>[[.revenue.]]:'+ number_format(this.y)+'</b><br/>';
					return s;
				}
			},
			rect:{
				enabled:false
			},
			series: [{
				name: '[[.mont_revenue.]] ',
				data:oy
			}, { name:'', data:[]
			}]
		});
		
		
	});
</script>
 <script>
 jQuery('.highcharts-legend').css('display','none');
 </script>