<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.message_revenue_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
   		<?php if(User::can_admin()){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id"></select></div>
        <?php }?>		
        <table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table width="400" border="0" cellspacing="0" cellpadding="5">
		  <tr>
		    <td width="30%">[[.in_day.]]: </td>
		    <td><input name="in_date" type="checkbox" id="in_date" onclick="updateDatetime(this);"></td>
		    </tr>
		  <tr>
		    <td>[[.date_from.]]: </td>
			<td><input name="date_from" type="text" id="date_from" size="8"></td><!--  [[.hour.]]: <input name="time_from" type="text" id="time_from" size="5"> -->
		  </tr>
		  <tr>
		    <td> [[.date_to.]]:</td>
		    <td><input name="date_to" type="text" id="date_to" size="8"></td> <!--  [[.hour.]]: <input name="time_to" type="text" id="time_to" size="5"> -->
		    </tr>
		  <tr>
		    <td>[[.room_id.]]: </td>
			<td><select name="room_id" id="room_id"></select></td>
		  </tr>		  
		</table>
		
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="15" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="200" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			</table>
			
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  "/>
				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
			</p>
			</form>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td></tr></table>
	</div>
	</div>
</td>
</tr></table>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#time_from").mask("99:99");
	jQuery("#time_to").mask("99:99");	
	jQuery(document).ready(function(){
		jQuery('#date_from').datepicker();
		jQuery('#date_to').datepicker();
	 });
	function updateDatetime(obj){
		if(obj.checked == true){
			$('date_from').value = '<?php echo date('d/m/Y');?>';
			$('date_to').value = '<?php echo date('d/m/Y');?>';
		}else{
			$('date_from').value = '';
			$('date_to').value = '';
		}
	}
</script>