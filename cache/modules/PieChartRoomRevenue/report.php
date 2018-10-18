<table width="100%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />ADD: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong><?php echo Portal::language('template_code');?></strong></td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<font class="report_title" style="font-size:20px; text-align:center;"><b><?php echo Portal::language('piechart_room_revenue');?></b></font>
		<br><br />
		<form name="WeeklyRevenueThuyForm" method="post">
		<table style=" padding-left:18px; padding-right:18px;" id="hidden"><tr><td>
		<fieldset><legend><b><?php echo Portal::language('time_select');?></b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td><?php echo Portal::language('date_from');?>:&nbsp;&nbsp;</td>
            	<td>
                <input type="text" name="from_date" id="from_date" class="date-input" onchange="changevalue();"/>
                 <script>
			  $('from_date').value='<?php if(Url::get('from_date')){echo Url::get('from_date');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
			  
			  </script>
                </td>
                <td><?php echo Portal::language('date_to');?>:&nbsp;&nbsp;</td>
                <td>
                <input type="text" name="to_date" id="to_date" class="date-input" onchange="changefromday();"/>
                <script>
			  $('to_date').value='<?php if(Url::get('to_date')){echo Url::get('to_date');}else{ echo (date('t').'/'.date('m').'/'.date('Y'));}?>';
			  
			  </script>
                 </td>
                 <td>
                    <?php echo Portal::language('portal');?>
                    <select  name="portal_id" id="portal_id" ><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	
                    </select>
                 </td>
                <td>
                <input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  ">
                </td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr>
          </table>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</td></tr>
    
    </table>
</td>
</tr>
<tr>
<td><div id="container" style="width: 800px; height: 400px; margin: 50px auto"></div></td>
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
</script>				
<script type="text/javascript">
	var chart;
	var items = <?php echo $this->map['items'];?>;
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
			title='<?php echo Portal::language('unit_1000_VND');?>';
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
					return '<b>'+ this.point.name +'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'+
							'<?php echo Portal::language('room_revenue');?>:<b>' + number_format(Math.round(this.y)*1000)+'</b><br/>'
							+'<?php echo Portal::language('total');?>: <b>' +number_format(Math.round(tong)*1000)+'</b>';
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
							return '<b>'+ this.point.name +'</b>('+ roundNumber(this.percentage,1) +' %)<b>'+number_format(this.y)+ '</b>';
						}
					},
					showInLegend: true
				}
			},
			series: [{
				type: 'pie',
				name: '<?php echo Portal::language('piechart_room_revenue');?>',
				data:data_items
				
			}]
		});
	});				
		</script>