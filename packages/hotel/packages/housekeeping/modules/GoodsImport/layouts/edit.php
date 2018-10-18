<!-- saved from url=(0014)about:internet -->
<form method="post" name="EditGoodsImportForm">
<div style="background-color:#FFFFFF;width:1000px; margin:auto;">
	<div style="text-align:left;background-color:#ECE9D8;width:100%;text-indent:20px;line-height:25px;text-transform:uppercase;font-weight:bold;">
		s&#7889; l&#432;&#7907;ng m&#7863;t h&#224;ng c&#7847;n nh&#7853;p cho c&#225;c phòng
	</div>
	<div style="text-align:left;background-color:#ECE9D8;width:100%;text-indent:20px;font-weight:bold;color:#FF0000;vertical-align:middle;">
	[[|error_message|]]
	</div>
	<table border="2" cellspacing="0" cellpadding="5" align="left" bordercolor="#CEC79B" bgcolor="#FFFFFF" style="border-collapse:collapse;">
	  <tr bgcolor="#ECE9D8">
		<td><input name="room" type="checkbox" id="room" value="room" onclick="check_all();"></td>  
		<td></td>
		<!--LIST:room_products_sample-->
		<td align="center">	
			[[|room_products_sample.id|]]	</td>
		<!--/LIST:room_products_sample-->
	  </tr>
	  <!--LIST:rooms-->
	  <tr>
		  <td><input name="check_[[|rooms.id|]]" id="check_[[|rooms.id|]]" type="checkbox" value="[[|rooms.id|]]"></td>
		<td>P[[|rooms.name|]]</td>
		<!--LIST:rooms.products-->
		<td align="center">
			[[|rooms.products.import_quantity|]]
		</td>
		<!--/LIST:rooms.products-->
	  </tr>
	   <!--/LIST:rooms-->
	</table>
</div>
<div style="display:block;text-align:center;background-color:#ECE9D8;width:1000px; margin:auto; padding:5px 0px 5px;line-height:25px;text-transform:uppercase;font-weight:bold;">
	<input name="print" type="submit" value="   [[.print_voucher.]]   ">&nbsp;&nbsp;
	<input name="update" type="submit" value="[[.import_room.]]">&nbsp;&nbsp;
	<input type="button" value="[[.update.]]" onclick="window.location='<?php echo Url::build_current();?>'">
</div>
</form>
<script>
	function check_all()
	{
		 if($('room').checked==true)
		 {
		 	<!--LIST:rooms-->
				$('check_[[|rooms.id|]]').checked=true;
			<!--/LIST:rooms-->
		 }
		 else
		 {
		 	<!--LIST:rooms-->
				$('check_[[|rooms.id|]]').checked=false;
			<!--/LIST:rooms-->
		 }
	}
</script>