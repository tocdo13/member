<link rel="stylesheet" href="../../HouseCountReport - Copy/layouts/skins/default/report.css">
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
	<td align="center" colspan="2"><p><font class="report-title"><h2>[[.occupancy_holding_report.]]</h2></font></p>
	  <p>[[.from_date.]]
        <input name="date" type="text" id="date" style="width:90px;" value="[[|day|]]">
        <input name="report" type="submit" id="report" value="GO" />
	  </p></td>
	</tr>
</table>
<br />
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
  <tr  valign="middle" bgcolor="#EFEFEF">
    <th rowspan="2" >[[.date.]]</th>
    <!--LIST:item_party-->
    	<th colspan="2" style="text-align:center;">[[|item_party.name_1|]]</th>
    <!--/LIST:item_party-->
         	<tr>	
            		<!--LIST:item_party-->
            			<th style="text-align:center; width:100px; background:#EFFFEF;">BOOKED</th>
        				<th style="text-align:center; width:100px; background:#EFFFEF;">OCCUPIED</th>
                    <!--/LIST:item_party-->
            </tr>
        </tr>  
            	<!--LIST:items-->
                <tr style="background:<?php $date_time1 = [[=items.id=]]; $week = date('w',$date_time1); echo ($week==0)?'silver;':(($week==6)?'#EFEFEF;':'#FFF');?>"><th  style="text-align:center;"><?php $date_time = [[=items.id=]]; $date_time = date('d/m/Y',$date_time); echo $date_time;?></th>
                	<!--LIST:item_party-->
         					<td style="text-align:center;"><?php if(isset($this->map['items']['current'][([[=item_party.id=]]).'BOOKED'])){ $total_booked = $this->map['items']['current'][([[=item_party.id=]]).'BOOKED']; echo  $total_booked?$total_booked:''; unset($total_booked); $book = $this->map['items']['current'][([[=item_party.id=]]).'_percent_book']; echo $book?'( '.$book.'%)':'';}?></td>
							<td style="text-align:center;"><?php if(isset($this->map['items']['current'][([[=item_party.id=]]).'OCCUPIED'])){$total_occ = $this->map['items']['current'][([[=item_party.id=]]).'OCCUPIED']; echo  $total_occ?$total_occ:''; unset($total_occ); $occ = $this->map['items']['current'][([[=item_party.id=]]).'_percent_occ']; echo $occ?'( '.$occ.'%)':'';}?></td>
                    <!--/LIST:item_party-->
  			<!--/LIST:items-->	
        </tr>
</table>
<br>

<script>
	jQuery("#date").datepicker();
</script>