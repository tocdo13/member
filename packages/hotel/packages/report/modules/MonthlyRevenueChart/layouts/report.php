<style>
#report-bound{min-height:600px;width:98%;margin:auto;padding:5px;}
#report-header{width:100%;float:left;}
#report-header-left{width:48%;float:left;}
#report-header-right{width:48%;float:right;text-align:right;margin-top:7px;margin-right:5px;}
#report-header-form{width:100%;float:left;}
#report-chart{width:100%;margin-top:20px;float:left;}
#report-title{width:50%;margin:auto;text-align:center;font-size:1.5em;text-transform:uppercase;}
#report-date{width:280px;/* 390px */margin:auto;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;-o-border-radius:3px;}
#hidden-dialog{width:183px;/* 183px*/min-height: 68px;background:#FFF;border:solid 1px silver;float:left;padding:5px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;-o-border-radius:3px;background:#FFF;position:absolute;display:none;}
#hidden-dialog ul{display:inline;list-style:none;float:left;}
#hidden-dialog div{width:100%;float:left;}
#hidden-dialog li:hover{text-decoration:underline;}
#hidden-dialog li{display:inline;list-style:none;border:solid 1px silver;margin:2px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;-o-border-radius:3px;float:left;cursor:pointer;}
ul#select-year li{font-size:1.5em;width:55px;line-height:45px;text-align:center;}
.selected_item{color:red;background:#F8F8F8;font-weight:bold;}
#close-dialog{padding:0;float:right;color:red;width:15px !important;line-height:15px !important;text-align:center;font-weight:bold;cursor:pointer;}
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
            </div>
            <div id="report-header-form">
                <div id="report-title">[[.monthly_revenue_chart.]]</div>
                    <fieldset id="report-date">
                        <legend>[[.select_year.]]</legend>
                        <table style="margin: auto;" id="table_year">
                            <tbody>
                                <tr>
                                    <td>
                                        [[.year.]] : <input name="selected_year" id="yearpicker" value="[[|year|]]" class="" /> &nbsp; <input type="submit" value="[[.view_report.]]"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
            </div>
        </div>
        <div id="report-chart">
            <div id="container" style="width: 950px; height: 650px; margin: 0 auto"></div>
        </div>
    </form>
</div>
<div id="hidden-dialog">
    <div>
        <div>[[.select_year.]] : <span id="close-dialog">x</span></div>
        <ul id="select-year">
        </ul>
    </div>
</div>
<script>
    var chart;
    function close_dialog(){
        jQuery('#hidden-dialog').slideUp();
        jQuery('#hidden-month').hide('slow');
        jQuery('#select-year li').removeAttr('class');
        jQuery('#select-month li').removeAttr('class');
    }
    
    function element_focus(ele){
        jQuery('#hidden-dialog').css({
            left : jQuery(ele).offset().left,
            top : (jQuery(ele).offset().top + jQuery(ele).height() + 5), 
        });
        jQuery('#hidden-dialog').attr('element' , jQuery(ele).attr('id'));
        jQuery('#hidden-dialog').slideDown();
    }
    jQuery(document).ready(function() {
			 
        jQuery('#yearpicker').click(function(){
            $this = jQuery(this);
            element_focus($this);
            jQuery('#select-year li').each(function(){
                jQuery(this).click(function(){
                    $this.val(jQuery(this).html());
                    close_dialog();
                });
            });
        });
        
        jQuery('#close-dialog').click(function(){
            close_dialog();
        });
        
        jQuery('#next_year').click(function(){
            jQuery('#select-year').animate({left : jQuery(this).css('left')- 60}, 500);
        });

        jQuery('#select-year').html(function(){
            var list_year = '';
            var begin_year = <?php echo date('Y', time());?>;
            var i = 2;
            while(true){
                if(i < 0){
                    break;
                }
                list_year += '<li>' + (begin_year - i) + '</li>';
                i--;
            }
            return list_year;
        });
		
		var data = [];
		var room = [[|room|]];
		var bar = [[|bar|]];
		var hk = [[|hk|]];
		var  extra =[[|extra_service|]];
		var spa =[[|spa|]];
		var k;
		var ar = [];
		var r = {};
			data[0]={};
			data[0]['name']='[[.room_revenue.]]';
			data[0]['data'] =[];
			data[1]={};
			data[1]['name']='[[.bar_revenue.]]';
			data[1]['data'] =[];
			data[2]={};
			data[2]['name']='[[.housekeeping_revenue.]]';
			data[2]['data'] =[];
			data[3]={};
			data[3]['name']='[[.extra_service.]]';
			data[3]['data'] =[];
			data[4]={};
			data[4]['name']='[[.spa.]]';
			data[4]['data'] =[];
			j=0;
			for(h=1; h<=12; h++){
				k=h;
				if(h<10){
					k = '0'+h;
				}
				data[0]['data'][j]=to_numeric(room[k]['amount']);
				data[1]['data'][j]=to_numeric(bar[k]['amount']);
				data[2]['data'][j]=to_numeric(hk[k]['amount']);
				data[3]['data'][j]=to_numeric(extra[k]['amount']);
				data[4]['data'][j]=to_numeric(spa[k]['amount']);
				j++;
			}
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'column'
					},
					title: {
						text: '[[.year.]]:[[|year|]]'
					},
					xAxis: {
						categories: [[|list_month|]]
					},
					yAxis: {
						min: 0,
						title: {
							text: ''
						},
						stackLabels: {
							enabled: true,
                            style: {
								fontWeight: 'bold',
								color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
							}
						}
					},
					legend: {
						align: 'right',
						x: -190,
						verticalAlign: 'top',
						y: 20,
						floating: true,
						backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
						borderColor: '#CCC',
						borderWidth: 1,
						shadow: false
					},
					tooltip: {
						formatter: function() {
							return '[[.month.]] : <b>'+ this.x +'</b><br/>'+
								 this.series.name +' : '+ number_format(this.y) +'<br/>'+
								 'Total : '+ number_format(this.point.stackTotal);
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
				    series: data
				});
			});
</script>