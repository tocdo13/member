<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<?php $checked_tax = [[=tax_check=]]; ?>
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
    	<br/>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.room_revenue_comparing_bar_chart.]]</b></font>
		<br><br />
		<form name="WeeklyRevenueThuyForm" method="post">
		<table style=" padding-left:18px; padding-right:18px;" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td align="center">[[.date_from.]]: <input type="text" name="from_date" id="from_date" class="date-input" onchange="changevalue();"/>
                <script>
                    $('from_date').value='<?php if(Url::get('from_date')){echo Url::get('from_date');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
                    
                </script>
                 [[.date_to.]]: <input type="text" name="to_date" id="to_date" class="date-input" onchange="changefromday();"/>
                <script>
                    $('to_date').value='<?php if(Url::get('to_date')){echo Url::get('to_date');}else{ echo (date('t').'/'.date('m').'/'.date('Y'));}?>';
                    
                </script>
                 [[.hotel.]] <select name="portal_id" id="portal_id"></select>
                 <input type="submit" name="do_search" value="  [[.report.]]  " />
                </td>
			</tr>
            <tr style="display: none;">
                <td align="center"> Chưa Thuế <input type="radio" name="check_tax" value="no_tax" <?php if($checked_tax=="no_tax"){echo 'checked="yes"';} ?>  /> <span> </span> Có Thuế <input type="radio" name="check_tax" value="yes_tax" <?php if($checked_tax=="yes_tax"){echo 'checked="yes"';} ?> /></td>
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
<td><div id="container_month_detail"></div></td>
</tr>
<tr>
<td><div id="container_months"></div></td>
</tr>
</table>
<script>
jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }

jQuery(document).ready(function() {
    //bieu do chi tiet thang
    var items = [[|items_month_detail|]];
    console.log(items);
	var type = [[|type|]];
	var ox = [];
	var data = [];
	h=0;
	var percent=0;
	var curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
	var title='';
	for(k in type){
		data[h] ={};
		data[h]['name']=type[k]['name'];
		data[h]['data']=[];
		j=0;
		for(i in items){
			var st = type[k]['id']+'amount';
			if(curency=='VND'){
				title='ĐƠN VỊ TÍNH 1000';
				data[h]['data'][j] = to_numeric(Math.round(items[i][st]/1000));
			}else{
				data[h]['data'][j] = to_numeric(Math.round(items[i][st]));
			}
			j++;
		}
		h++;
	}
	var l=0;
	var itemmax =0;
	for(i in items){
	   ox[l] = items[i]['date'];
	   var tg = 0;
	   for(k in type){
	       var st = type[k]['id'] +'amount';
	       var amount = 0;
	       if(curency=='VND'){
	           amount = to_numeric(items[i][st])/1000;
	       }else{
	           amount = to_numeric(items[i][st]);
	       }
	       tg += amount;
           tg = Math.round(tg);
	   }
	   if(itemmax < tg){
	       itemmax = tg
	   }
	   l++;
	}	
	itemmax =itemmax +10;
	var chart;
	var total = 0;
	var y = 0;
    
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container_month_detail',
            defaultSeriesType: 'column',
            chartWidth: 10
        },
        title: {
            text: title
        },
        xAxis: {
            categories: ox,
            title: {
                text: '[[.day.]]'
            }
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            max:itemmax,
            title: {
                text: '[[.room_revenue.]]'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    
                },
                formatter: function() {
                	return number_format(this.total);
                }
            }
        },
        tooltip: {
            formatter: function() {
                total = to_numeric(this.point.stackTotal);
				y =to_numeric(this.y);
				percent =Math.round((y/total)*100);
				return '[[.date.]]:<b>'+ this.x +'</b>'+ '<br/>' 
								+'[[.total.]]: <b>'+ number_format(Math.round(this.point.stackTotal)*1000)+'</b>'+ curency +'<br/>'
						        + this.series.name+': ('+percent+' %)'
								+'<br/>[[.room_revenue.]]: <b>'+ number_format(Math.round(this.y)*1000) +'</b>'+ curency ;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series:data
    });
    
    //bieu do các tháng
    var temp = [[|items_months|]];
    items = [];
    for(i in temp)
    {
        items[parseInt(i)] = temp[i];
    }
	type = [[|type|]];
	ox = [];
	data = [];
	h=0;
	percent=0;
	curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
	title='';
	for(k in type){
		data[h] ={};
		data[h]['name']=type[k]['name'];
		data[h]['data']=[];
		j=0;
		for(i in items){
			var st = type[k]['id']+'amount';
			if(curency=='VND'){
				title='ĐƠN VỊ TÍNH 1000';
				data[h]['data'][j] = to_numeric(Math.round(items[i][st]/1000));
			}else{
				data[h]['data'][j] = to_numeric(Math.round(items[i][st]));
			}
			j++;
		}
		h++;
	}
	l=0;
	itemmax =0;
    
	for(i in items){
	   
	   ox[l] = items[i]['date'];
	   var tg = 0;
	   for(k in type){
	       var st = type[k]['id'] +'amount';
	       var amount = 0;
	       if(curency=='VND'){
	           amount = to_numeric(items[i][st])/1000;
	       }else{
	           amount = to_numeric(items[i][st]);
	       }
	       tg += amount;
	   }
	   if(itemmax < tg){
	       itemmax = tg
	   }
	   l++;
	}	
	itemmax =itemmax +10;
	chart;
	total = 0;
	y = 0;
    
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container_months',
            defaultSeriesType: 'column'
        },
        title: {
            text: title
        },
        xAxis: {
            categories: ox,
            title: {
                text: '[[.month.]]'
            }
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            max:itemmax,
            title: {
                text: '[[.room_revenue.]]'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                },
                formatter: function() {
                	return number_format(this.total);
                }
            }
        },
        tooltip: {
            formatter: function() {
                total = to_numeric(this.point.stackTotal);
				y =to_numeric(this.y);
				percent =Math.round((y/total)*100);
				return '[[.month.]]:<b>'+ this.x +'</b>'+ '<br/>' 
								+'[[.total.]]: <b>'+ number_format(Math.round(this.point.stackTotal)*1000)+'</b>'+ curency +'<br/>'
						        + this.series.name+': ('+percent+' %)'
								+'<br/>[[.room_revenue.]]: <b>'+ number_format(Math.round(this.y)*1000) +'</b>'+ curency ;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series:data
    });
});
    
</script>