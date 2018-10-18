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
        <br />
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.piechart_karaoke-spa_revenue.]]</b></font>
		<br><br />
		<form name="WeeklyRevenueThuyForm" method="post">
		<table style=" padding-left:18px; padding-right:18px;" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td>[[.date_from.]]:&nbsp;&nbsp;</td>
            	<td>
                <input type="text" name="from_date" id="from_date" class="date-input" onchange="changevalue();"/>
                 <script>
			  $('from_date').value='<?php if(Url::get('from_date')){echo Url::get('from_date');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
			  function changevalue(){
				  var myfromdate = $('from_date').value.split("/");
				  var mytodate = $('to_date').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					  $('to_date').value =$('from_date').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
						  if(myfromdate[2] == mytodate[2]){
					         $('to_date').value =$('from_date').value;
						  }
					  }else{
						  if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
							  if(myfromdate[2] == mytodate[2]){
					  			$('to_date').value =$('from_date').value;
							  }
						  }
					  }
				  }
			  }
			  </script>
                </td>
                <td>[[.date_to.]]:&nbsp;&nbsp;</td>
                <td>
                <input type="text" name="to_date" id="to_date" class="date-input" onchange="changefromday();"/>
                <script>
			  $('to_date').value='<?php if(Url::get('to_date')){echo Url::get('to_date');}else{ echo (date('t').'/'.date('m').'/'.date('Y'));}?>';
			  function changefromday(){
				 var myfromdate = $('from_date').value.split("/");
				  var mytodate = $('to_date').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					 $('from_date').value= $('to_date').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
					   $('from_date').value = $('to_date').value;
					  }else{
                              if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
					  			$('from_date').value =$('to_date').value;
						  }
					  }
				  }
			  }
			  </script>
                 </td>
                <td>
                <input type="submit" name="do_search" value="  [[.report.]]  ">
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
    <td align ="center">
        <br />
        <table cellpadding="0" cellspacing="0" align="center" width="500px" border="1"  style="border-collapse: collapse;">
            <tr style="background-color: #EBE9ED;">
                <th>Stt</th>
                <th>Dịch vụ</th>
                <th>Doanh thu</th>
                <th>Tỷ lệ</th>
            </tr>
            <!--LIST:datas-->
            <tr>
                <td align="center">[[|datas.stt|]]</td>
                <td style="padding-left: 10px;">[[|datas.name|]]</td>
                <td align="right" style="padding-right: 10px;"><?php echo System::display_number([[=datas.amount_total=]]);?></td>
                <td align="center"><?php echo round([[=datas.percent=]],2);?>%</td>
            </tr>
            <!--/LIST:datas-->
            <tr style="background-color: #EBE9ED;">
                <th colspan="2">[[.total.]]</th>
                <th  align="right" style="padding-right: 10px;"><?php echo System::display_number([[=total=]]);?></th>
                <th>&nbsp;</th>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td>
        <br />
        <div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>
    </td>
</tr>
</table>
<script>
jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
</script>				
<script type="text/javascript">
	var chart;
	var items = [[|items|]];
	var data_items = [];
	j = 0;
	tong =0
	tien = 0;
	var title='';
	var curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
	for(i in items){
		data_items[j] = [];
		data_items[j][0] = items[i]['name'];
		if(curency=='VND'){
			title='[[.unit_1000_VND.]]';
			data_items[j][1] = to_numeric( items[i]['amount_total']/1000);
			tong +=data_items[j][1];
		}else{
			data_items[j][1] = to_numeric( items[i]['amount_total']);
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
				text: title
			},
			tooltip: {
				formatter: function() {
						tong = 	(this.y/this.percentage)*100;		
					return '<b>'+ this.point.name +'</b>:'+ roundNumber(this.percentage,2) +' % <br/>'+
							'[[.room_revenue.]]:<b>' + number_format(this.y)+'</b><br/>'
							+'[[.total.]]: <b>' +number_format(tong)+'</b>';
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
							return '<b>'+ this.point.name +'</b>('+ roundNumber(this.percentage,2) +' %)<br/><b>'+number_format(this.y)+ '</b>';
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