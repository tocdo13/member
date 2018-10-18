<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
 });
</script>
<div style="width:100%;overflow:auto">
<table cellSpacing=0 width="100%">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		ADD: <?php echo HOTEL_ADDRESS;?><br />
          </td>
		<td align="right" nowrap width="15%" style="padding-right:20px;">
		<strong>Biểu 4b</strong>
		</td>
	</tr>	
	<tr>
	<td align="center" colspan="2" style="font-size:18px;"><strong>[[.Average_room_rate_forecast.]]</strong></td>
    </tr>
    	<td>&nbsp;</td>
    <tr>
    </tr>
  </table>
</form>
<table cellSpacing=0 width="100%" border="1">
	<tr>
    	<td align="center" rowspan="2">LOẠI PHÒNG</td>
        <td align="center" rowspan="2">SL PHÒNG</td>
        <td align="center" colspan="3">GIÁ PHÒNG BÌNH QUÂN</td>
        <td align="center" rowspan="2">ĐẶC ĐIỂM TỪNG LOẠI PHÒNG</td>
    </tr>
    <tr>
    	<td align="center">2011</td>
        <td align="center">2012</td>
        <td align="center">2013</td>
    </tr>
    <tr>
    	<td align="left"><strong>KHU A</strong></td>
        <td align="center"><strong>41</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Suite</td>
        <td align="center">3</td>
        <td align="right">2.000.000</td>
        <td align="right">2.250.000</td>
        <td align="right">2.600.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">First E.D</td>
        <td align="center">13</td>
        <td align="right">1.600.000</td>
        <td align="right">1.600.000</td>
        <td align="right">1.800.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Executive Deluxe</td>
        <td align="center">15</td>
        <td align="right">1.400.000</td>
        <td align="right">1.400.000</td>
        <td align="right">1.500.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Deluxe</td>
        <td align="center">10</td>
        <td align="right">1.100.000</td>
        <td align="right">1.100.000</td>
        <td align="right">1.200.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td align="left"><strong>KHU B</strong></td>
        <td align="center"><strong>111</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Suite</td>
        <td align="center">5</td>
        <td align="right">1.800.000</td>
        <td align="right">1.800.000</td>
        <td align="right">2.050.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Executive Deluxe</td>
        <td align="center">5</td>
        <td align="right">1.400.000</td>
        <td align="right">1.400.000</td>
        <td align="right">1.500.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Deluxe</td>
        <td align="center">20</td>
        <td align="right">1.100.000</td>
        <td align="right">1.100.000</td>
        <td align="right">1.200.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
    	<td align="left">Superior</td>
        <td align="center">81</td>
        <td align="right">970.000</td>
        <td align="right">965.000</td>
        <td align="right">960.000</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
    	<td align="left" colspan="2"><strong>GIÁ PHÒNG BÌNH QUÂN CHUNG</strong></td>
        <td align="right">1.153.750</td>
        <td align="right">1.156.000</td>
        <td align="right">1.206.000</td>
        <td>&nbsp;</td>
    </tr>
</table>
<table width="100%" align="left">
	<tr>
    	<td align="left">GHI CHÚ:</td>
    </tr>
    <tr>
    	<td align="left">- Giá phòng bình quân thực tế tính cho từng loại phòng<br>
        - Giá phòng là giá NET và chưa tính bất kỳ khoản thu kèm</td>
    </tr>
</table>
</div>