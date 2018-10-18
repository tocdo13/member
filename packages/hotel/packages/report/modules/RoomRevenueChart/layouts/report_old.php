<table class="table_chart" style="width: 100%; margin-top : 40px;">
<tr>
    <td style="padding-left : 20px; font-weight: bold;">Newway Single version</td>
    <td style="text-align: right; padding-right : 40px;">[[.template_code.]]</td>
</tr>
<tr>
    <td style="padding-left : 20px;"><?php echo HOTEL_ADDRESS; ?></td>
</tr>
<tr>
    <td colspan="2" style="text-align: center; font-weight: bold; font-size: 1.3em;">
            [[.room_revenue_chart.]]
    </td>
</tr>
<tr>
    <td colspan="2">
    <form action="" method="post">
        <fieldset style="width: 55%; margin : auto;">
            <legend>[[.time_select.]]</legend>
            <table  style="width: 100%;" >
                <tr>
                    <td>[[.from_date.]]</td>
                    <td><input type="text" class="date-input" id="from_date" name="from_date" value="<?php if(Url::get('from_date')) echo Url::get('from_date'); else{ echo date('01/m/Y',time());} ?>" /></td>
                    <td>[[.to_date.]]</td>
                    <td><input type="text" class="date-input" id="to_date" name="to_date" value="<?php if(Url::get('to_date')) echo Url::get('to_date'); else echo date('d/m/Y' , time()); ?>" /></td>
                    <td><input type="submit" value="[[.report.]]" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
    </td>
</tr>
</table>
<script>
jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
jQuery(document).ready(function(){
    function checkdate(){
        from_date = jQuery('#from_date').val();
        from_date = from_date.split('/');
        from_date = new Date(from_date[2] , from_date[1] , from_date[0] ).getTime();
        to_date = jQuery('#to_date').val();
        to_date = to_date.split('/');
        to_date = new Date(to_date[2] , to_date[1] , to_date[0] ).getTime();
        if(from_date >= to_date){
            return false;
        }else{
            return true;
        }
    }
    function write_date(){
        if(jQuery('#to_date').val() == '' || !checkdate() ){
            from_date = jQuery('#from_date').val();
            from_date = from_date.split('/');
            if(from_date[0] == new Date(from_date[2] , from_date[1] , 0).getDate()){
                from_date[0] = 1;
                if(from_date[1] == 12 ){
                    from_date[1] = '01' ;
                    from_date[2]++;
                }else{
                    from_date[1]++;
                }
            }else{
                from_date[0]++;
            }
            if(from_date[0] < 10){
                from_date[0] = '0' + from_date[0];
            }
            to_date = from_date[0] + '/' + from_date[1] + '/' + from_date[2];
            jQuery('#to_date').val(to_date); 
        }
    }
    jQuery('#from_date').change(function(){
       write_date(); 
    });
    jQuery('#to_date').change(function(){
        if(!checkdate()){
            alert('[[.from_date_not_greater_than_to_date.]]');
            write_date();
        }
    });
    jQuery('#bottom_submit').click(function(){
       if(jQuery('#from_date').val() != '' && jQuery('#to_date').val() != '' && checkdate() ){
            this.form.submit();
                return;
           }else{
                return false;
           }
    });
});
</script>
<script type="text/javascript">
    function write_chart( items , id , date ){
            var chart;
            var category = new Array();
            var data = new Array();
            k = 0;
            for(var x in items){
                category[k] = items[x]['id'] ;
                data[k] = to_numeric(items[x]['revenue']);
                k++;
            }
            var unit = '';
            <!--IF:cond(HOTEL_CURRENCY == 'VND')-->
                unit = '[[.unit_1,000_VND.]]';
            <!--ELSE-->
                <!--IF:cond1(HOTEL_CURRENCY == 'USD')-->
                unit = '[[.unit_1_USD.]]';
                <!--/IF:cond1-->
            <!--/IF:cond-->
            var text
			jQuery(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: id ,
						defaultSeriesType: 'column',
						margin: [ 50, 50, 100, 80]
					},
					title: {
						text: '[[.month.]] ' + date.substr(4) + '   [[.year.]] ' + date.substr(0,4) + ''
					},
					xAxis: {
						categories: category,
						labels: {
							align: 'center',
							style: {
								 font: 'normal 13px Verdana, sans-serif'
							}
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: unit
						}
					},
					legend: {
						enabled: false
					},
					tooltip: {
						formatter: function() {
							return '<b> [[.day.]] : '+ this.x +'</b><br/>'+
								 '[[.Revenue.]] : '+ Highcharts.numberFormat(this.y, 2) + '  ( ' +
								 unit + ' )';
						}
					},
				        series: [{
						name: 'Population',
						data: data,
						dataLabels: {
							enabled: true,
							rotation: -90,
							color: '#FFFFFF',
							align: 'center',
							x: -3,
							y: 10,
							formatter: function() {
								return Highcharts.numberFormat(this.y , 2);
							},
							style: {
								font: 'normal 13px Verdana, sans-serif'
							}
						}			
					}]
				});
				
				
			});
    }
    jQuery(document).ready(function(){
        var items = [[|items|]];
        for(x in items){
            for(y in items[x]){
                id = 'container' + y;
                string = '<tr><td colspan="2"><div id="' + id + '"></div></td></tr>';
                jQuery('.table_chart').append(string);
                write_chart(items[x][y] , id , y );
            }
        }
    });
		</script>