<link rel="stylesheet" href="skins/default/report.css">
<script>
jQuery(document).ready(function(){
	jQuery('#date').datepicker();
 });

</script>
<form name="HouseCountReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		ADD: <?php echo HOTEL_ADDRESS;?><BR>
		Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
		Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>		</td>
		<td align="right" nowrap width="35%" style="padding-right:20px;">
		<strong>[[.template_code.]]</strong></td>
	</tr>	
	<tr>
	  <td align="center" colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td align="center" colspan="2"><p><font class="report_title">[[.house_count_report.]]</font></p>
	  <p>[[.from_date.]]
        <input name="from_date" type="text" id="from_date" style="width:90px;">
        <script>
			  $('from_date').value='<?php if(Url::get('from_date')){echo Url::get('from_date');}else{ echo date('d/m/Y');}?>';
        </script>
        <input name="report" type="submit" id="report" value="GO" />
	  </p></td>
	</tr>
</table>

<br />
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
  <tr  valign="middle" bgcolor="#EFEFEF">
         <th rowspan="1" style="text-align:center;">[[.date.]]</th>
         <?php
		        $items = [[=items=]];
				$total_room = [[=total_room=]];
		   		$time  = Url::get('from_date')?Date_Time::to_time(Url::get('from_date')):Date_Time::to_time(date('d/m/Y'));
				$end_time = $time + 6*24*3600 ;
		    for($i = $time ; $i <= $end_time; $i += 24*3600 ){
		    ?>
        	<th  style="text-align:center;"><?php $date_time = date('d/m/Y',$i); echo $date_time;?></th>
           <?php } ?>
        	<th>[[.capacity.]]</th>
  </tr>
   <!--LIST:partys-->
   <?php $k=0;?>
  <tr valign="middle">
  		<td  style="text-align:left;"> [[|partys.name|]] </td>
        <?php 
		$total = 0;
         for($i = $time ; $i <= $end_time; $i += 24*3600 ){ 
		   		   $date_time = date('d/m/Y',$i); ?>
         	<td style="text-align:center;">
				<?php if(isset($items[[[=partys.user_id=]].'_'.$date_time] ) ){ echo $items[[[=partys.user_id=]].'_'.$date_time]['total'] ;
				$total +=$items[[[=partys.user_id=]].'_'.$date_time]['total'] ;
				}?>            
            </td>
          <?php } ?>
           <td>  
             <?php
			     if(isset($total_room[[[=partys.user_id=]]]) && $total_room[[[=partys.user_id=]]]['total'] > 0 ){
			 	  $percent = round($total/$total_room[[[=partys.user_id=]]]['total'],1); echo $percent;
				 }
			  ?>
           </td>
  </tr>
   <!--/LIST:partys-->     
</table>
<script>
	jQuery("#from_date").datepicker();
</script>