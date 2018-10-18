<style>
#timehidden{
		display:none;	
	}
	@media print{
		#hidden{
			display:none;
		}
		#timehidden{
			display:block;	
		}
	}
</style>


<table width="100%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br />ADD: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong>[[.template_code.]]</strong></td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.chart_room.]]</b></font>

		<br><br />
        <label id="timehidden">Từ ngày: [[|from_date|]]  Đến ngày: [[|to_date|]]</label>
		<form name="WeeklyRevenueThuyForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td>[[.from_date.]]:&nbsp;&nbsp;</td>
            	<td><input type="text" name="from_date" id="from_date" class="date-input" onchange="check_from_date();"/></td>
                <td>[[.to_date.]]:&nbsp;&nbsp;</td>
                <td><input type="text" name="to_date" id="to_date" class="date-input" onchange="check_to_date();"/></td>
                <td><input type="submit" name="do_search" value="  [[.report.]]  "></td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr>
          </table>
			</form>
	</td></tr></table>
</td>

<table width="80%" style="border:1px solid #DFDFDF; line-height:50px; margin:auto; margin-top:20px;" id="revenue">
<tr>
	<td><div id="container"></div></td>
</tr>
</table>
<script>
		$('from_date').value = '[[|from_date|]]';
		$('to_date').value = '[[|to_date|]]';
		function check_from_date(){
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((to_nummer(from_date[1]) > to_nummer(to_date[1])) || (to_nummer(from_date[2]) > to_nummer(to_date[2]))){
				$('to_date').value = $('from_date').value;
			}else{
				if((to_nummer(from_date[0]) > to_nummer(to_date[0])) && (to_nummer(from_date[1]) == to_nummer(to_date[1])) && (to_nummer(from_date[2]) == to_nummer(to_date[2])) ){
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
		jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
		jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
</script>
<script>
	var items = [[|items|]];
	var room_amount;
	var date_arr = [];
	var arr_room = new Array();
	//var date_arr =  new Array();
	//var arr_room = new Array();
	var arr_room_no = new Array();
	var series_booked_data =  {};
	series_booked_data['name'] = 'Booked';
	series_booked_data['data'] = [];
	var hihi = [];
	var series_occupied_data =  {};	
	series_occupied_data['name'] = 'Occupied';
	series_occupied_data['data'] = [];
	var j = 0;
	var series_arr = [];
	for(i in items){
		var in_date = items[i]['in_date'];
		room_amount = items[i]['room_amount'];
		date_arr[j] = ""+items[i]['in_date']+"";
		if(items[i]['room_amount'] != 0){
			arr_room[j] = ""+items[i]['room_amount']+"<br>"+items[i]['in_date'];
		}else arr_room[j] = '0'+"<br>"+items[i]['in_date'];
		arr_room_no[j] = items[i]['total_room'];
		series_booked_data['data'][j] = to_numeric(items[i]['total_BOOKED']);
		series_occupied_data['data'][j] = to_numeric(items[i]['total_OCCUPIED']);
		j++;
	}
	series_arr = [series_booked_data, series_occupied_data];
	
	
	Highcharts.setOptions({
    chart: {
        style: {
            width: '980px',
        }
    }
});
	
	var chart;
	jQuery(document).ready(function() {
		chart = new Highcharts.Chart({
			chart: {
			 renderTo: 'container',
			 defaultSeriesType: 'column'
			},
			title: {
			 text: ''
			},
			xAxis: {	  
			 categories: arr_room,
				labels: {
           	 style: {
				margin: '-12px',
				padding: '0 0 0 25px',
				fontSize: '9px'			
            }
       		 }
			},
			yAxis: {
			 min: 0,
			 max: [[|total|]],
			 title: {
				text: 'Total room',
				style: {
            		margin: '0 -50px 0 0'
       			 }
			 },
			 stackLabels: {
				enabled: true,
				style: {
				   fontWeight: 'bold',
				   color: (Highcharts.theme && Highcharts.theme.textColor) || 'silver'
				}
			 }
			},
			legend: {
			 align: 'left',
			 x: -100,
			 verticalAlign: 'bottom',
			 y: 10,
			 //floating: false,
			 backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
			 borderColor: '#CCC',
			 borderWidth: 1,
			// shadow: false,
			 margin: '-100px'
			},
			tooltip: {
			 formatter: function() {
							return '<br/>'+ this.x;
						}
			},
			plotOptions: {
			 column: {
				stacking: 'normal',
				dataLabels: {
				   enabled: false,
				   color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
				}
			 }
			},
			series: series_arr
		});
	});
</script>
