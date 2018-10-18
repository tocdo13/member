<link rel="stylesheet" href="skins/default/report.css">
<script>
jQuery(document).ready(function(){
	jQuery('#date').datepicker();
 });

</script>
<?php
$date = explode('/',[[=day=]]);
?>
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
        <input name="date" type="text" id="date" style="width:90px;" value="[[|day|]]">
        <input name="report" type="submit" id="report" value="GO" />
	  </p></td>
	</tr>
</table>
<br />
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
  <tr  valign="middle" bgcolor="#EFEFEF">
    <th>[[.stt.]]</th>
    <th>[[.rooms.]]</th>
   <?php for($i=0;$i<7;$i++){?> <th><?php echo date('d/m/Y',mktime(0,0,0,$date[1],$date[0],$date[2])+$i*24*60*60);?></th><?php }?>
  </tr>
  <?php $j=1;
  $rooms = [[=rooms=]];
  ?>
  <?php foreach($rooms as $key=>$value)
  {?>
  <tr>
    <td><?php echo $j++;?></td>
    <td><?php echo $key;?></td>
   <?php for($i=0;$i<7;$i++){?> 
   		<td>
			<?php 
				$day = date('d/m/Y',mktime(0,0,0,$date[1],$date[0],$date[2])+$i*24*60*60);
				if(isset($value[$day]))
				{
					echo $value[$day]['acount'].'('.(System::display_number($value[$day]['acount']/[[=room_count=]])*100).'%)';
				}
				else
				{
					echo '0';
				}
			?>
		</td>
	<?php }?>  
  </tr>
 <?php }?> 
</table>
<br>
<!--IF:cond(1==2)-->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
  <tr  valign="middle" bgcolor="#EFEFEF">
    <th>[[.stt.]]</th>
    <th>[[.guest.]]</th>
   <?php for($i=0;$i<7;$i++){?> <th><?php echo date('d/m/Y',mktime(0,0,0,$date[1],$date[0],$date[2])+$i*24*60*60);?></th><?php }?>
  </tr>
  <?php $j=1;
  $guest = [[=guest=]];
  ?>
  <?php foreach($guest as $key=>$value)
  {?>
  <tr>
    <td><?php echo $j++;?></td>
    <td><?php if($key=='CHECKIN'){echo 'OCCUPIED';}else{echo $key;}?></td>
   <?php for($i=0;$i<7;$i++){?> 
   		<td>
			<?php 
				$day = date('d/m/Y',mktime(0,0,0,$date[1],$date[0],$date[2])+$i*24*60*60);
				if(isset($value[$day]))
				{
					echo $value[$day]['adult'];
				}
				else
				{
					echo '0';
				}
			?>
		</td>
	<?php }?>  
  </tr>
 <?php }?> 
</table>
<!--/IF:cond-->
<script>
	jQuery("#date").datepicker();
</script>