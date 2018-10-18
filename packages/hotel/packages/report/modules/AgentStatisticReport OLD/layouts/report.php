<link rel="stylesheet" href="skins/default/report.css">
<style>
#date_moth{
	display:none;
}
#report_td{
	border:1px solid #ccc;
	height:27px;
}
@media print{
#date_moth{
	display:block;
	padding-top:10px;
	font-size:14px;
}
.no_print{
	display:none;
}
</style>
<form name="SearchForm" method="post">
<table style="width:100%;">
<tr valign="top">
<td align="left" width="100%">
	<table border="0" cellSpacing=0 cellpadding="5" width="100%">
			<tr valign="middle">
			<td width="100"><img src="<?php echo HOTEL_LOGO;?>" width="100" /></td>
			  <td align="left"><br />
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
				ADD: <?php echo HOTEL_ADDRESS;?><BR>
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
			  </td>
			  <td>
			  </td>
			</tr>	
		</table>
</td>
</tr>
<tr>
    <td style="text-align:center; padding-top:26px;">
    <font class="report_title">[[.agent_or_company_statistic_report.]]</font><br />
    <label id="date_moth">[[.from_date.]] : [[|from_date|]] - [[.to_date.]] : [[|to_date|]]</label> 
    </td>
</tr>
<tr class="no_print">
  <td colspan="3" style="padding-left:195px; padding-right:200px;">
  <br/>
<fieldset>
  <legend>[[.time_select.]]</legend>
    <table style="margin-left:60px;">
    <tr> <td nowrap="nowrap">[[.by_day.]] &nbsp;&nbsp;</td>
    <td><input type="text" name="from_day" id="from_day" class="date-input" onchange="changevalue();"/>
    <script>
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
			  function changevalue(){
				  var myfromdate = $('from_day').value.split("/");
				  var mytodate = $('to_day').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					  $('to_day').value =$('from_day').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
					  $('to_day').value =$('from_day').value;
					  }else{
						  if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
					  			$('to_day').value =$('from_day').value;
						  }
					  }
				  }
			  }
	</script>
    </td>
    <td> &nbsp;&nbsp;[[.to_day.]] &nbsp;&nbsp;</td><td><input type="text" name="to_day" id="to_day" class="date-input" onchange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{ echo date('d/m/Y');}?>';
			  function changefromday(){
				 var myfromdate = $('from_day').value.split("/");
				  var mytodate = $('to_day').value.split("/");
				  if(myfromdate[2] > mytodate[2]){
					 $('from_day').value= $('to_day').value;
				  }else{
					  if(myfromdate[1] > mytodate[1]){
					   $('from_day').value = $('to_day').value;
					  }else{
                              if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
					  			$('from_day').value =$('to_day').value;
						  }
					  }
				  }
			  }
	</script>
    </td>
	<td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"></td>
     </tr></table>
     </fieldset>
     </td>
     </tr>
     <tr>
        <td style="padding-left:10px;">
         		 <div style="width:100%; padding-bottom:10px;" id="rp">        
            <table style=" width:100%; border:1px solid #ccc; margin-top:26px; padding-top:10px; padding-left:40px;" id="report" >
               <tr valign="middle" bgcolor="#EFEFEF" style="padding-left:10px; height:30px; border:1px solid #ccc">
               		<th style="border:1px solid #ccc; text-align:left; width:120px; padding-left:9px;">[[.company_name.]]</th>
                    <th style="text-align:center;width:13%;">[[.booking_code.]]</th>
                    <th style="border:1px solid #ccc; text-align:center;width:10%;">[[.room_type.]]</th>
                     <th style="text-align:center;width:8%;">[[.room_name.]]</th>
                    <th style="border:1px solid #ccc; text-align:center;width:10%;">[[.time_in.]]</th>
                    <th style="border:1px solid #ccc; text-align:center;width:10%;">[[.time_out.]]</th>
                    <th style="border:1px solid #ccc; text-align:center;width:4%;">[[.no_of_night.]]</th>
                   <th style="border:1px solid #ccc; text-align:center; width:13%">[[.note.]]</th>
               </tr>
               <!--LIST:items-->
               <tr style="height:30px; border:1px solid #ccc">
                   <td style="border:1px solid #ccc; text-align:left; padding-left:9px;">- [[|items.companyname|]]</td>
                   <td style="border:1px solid #ccc; text-align:center">[[|items.booking_code|]]</td>
                   <td style="border:1px solid #ccc;text-align:center ">[[|items.room_type|]]</td>
                   <td style="border:1px solid #ccc; text-align:center">[[|items.room_name|]]</td>
                   <td style="border:1px solid #ccc; text-align:center"><?php echo Date_Time::convert_orc_date_to_date ([[=items.arrival_time=]],'/');?></td>
                   <td style="border:1px solid #ccc; text-align:center"><?php echo Date_Time::convert_orc_date_to_date ([[=items.departure_time=]],'/');?></td>
                   <td style="border:1px solid #ccc;text-align:center">[[|items.duration|]]</td>
                   <td style="border:1px solid #ccc; text-align:center">[[|items.room_note|]]</td>
               </tr>
               <!--/LIST:items-->
            </table>
        </div>
        
        </td>
     </tr>
     
    </table>
    
  </td>
</tr> 
</table>
</form>
<script>
jQuery("#from_day").datepicker({ minDate: new Date(BEGINNING_YEAR,1, 1) });
jQuery("#to_day").datepicker({ minDate: new Date(BEGINNING_YEAR,1, 1) });
</script>
