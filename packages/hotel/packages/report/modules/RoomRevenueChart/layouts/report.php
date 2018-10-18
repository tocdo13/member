<style>
#report-bound{min-height:600px;width:98%;margin:auto;padding:5px;}
#report-header{width:100%;float:left;}
#report-header-left{width:48%;float:left;}
#report-header-right{width:48%;float:right;text-align:right;margin-top:7px;margin-right:5px;}
#report-header-form{width:100%;float:left;}
#report-chart{width:100%;margin-top:20px;float:left;}
#report-title{width:50%;margin:auto;text-align:center;font-size:1.5em;text-transform:uppercase;}
#report-date{width:280px;/* 390px */margin:auto;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;-o-border-radius:3px;}
</style>
<div id="report-bound">
    <form name="RoomRevenueChartDzung" method="post">
        <div id="report-header">
            <div id="report-header-left">
                <strong><?php echo HOTEL_NAME;?></strong><br />
                <span>[[.address.]] : <?php echo HOTEL_ADDRESS;?>.</span>
            </div>
            <div id="report-header-right">
                <strong>[[.department.]] : [[.reception.]]</strong>
                <br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
            </div>
            
            <div id="report-header-form">
                <div id="report-title">[[.monthly_revenue_chart.]]</div>
                    <fieldset id="report-date">
                        <legend>[[.select_month.]]</legend>
                        <table>
                            <tbody>
                                <tr>
                                <!--
                                    <td>[[.from_date.]] : </td>
                                    <td><input name="from_date" id="from_date" value="[[|from_date|]]" style="width: 100px;" /></td>
                                    <td>[[.to_date.]] : </td>
                                    <td><input name="to_date" id="to_date" value="[[|to_date|]]" style="width: 100px;" /></td>
                                -->
                                    <td>[[.month.]] :</td>
                                    <td><input name="month_report1" class="monthpicker" value="<?php echo Url::get('month_report1');?>" /></td>
                                    <td><input type="submit" value="[[.view_report.]]" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
            </div>
        </div>
        <div id="report-chart">
            <div id="container" style="width: 950px; height: 450px; margin: 0 auto"></div>
        </div>
    </form>
</div>
<script>
    var chart;
    
    jQuery(document).ready(function(){
        
        //jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
        //jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
        jQuery(".monthpicker").monthpicker();
        
        var colors = Highcharts.getOptions().colors;
        var categories = [[|date_list|]];    
        var data = [[|data|]];
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						type: 'column'
					},
					title: {
						text: '[[.unit.]] : 1000 VND' 
					},
					subtitle: {
						text: ''
					},
					xAxis: {
						categories: categories,
						labels: {
							//rotation: -45,
							align: 'center',
                            style: {
								 font: '11px Verdana, sans-serif'
							}
						}					
					},
					yAxis: {
						title: {
							text: '[[.daily_revenue.]]'
						}
					},
					tooltip: {
						formatter: function() {
							//var point = this.point,
							s = '[[.date.]] : <strong>' + this.x +'</strong><br>';
							s += '[[.revenue.]] : '+ number_format(this.y) +'';
							return s;
						}
					},
					series: [{
						name : '[[.room_revenue.]]', 
						data: data, 
						color: colors[0]
					}]
            });
    });
</script>