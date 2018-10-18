<style type="text/css">
#tbl_print{ display: none;}
</style>
<form name="CheckRevenueForm" method="post">
<div>
<div>
  <h3>[[.list_of_counters_not_closed_on.]] [[.date.]] <?php echo $_SESSION['night_audit_date']?></h3>
</div>
<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
  <tr class="table-header">
    <th>[[.guest_name.]]</th>
    <th>[[.room_level.]]</th>
    <th>[[.room_name.]]</th>
    <th>[[.room_rate.]] (<?php echo HOTEL_CURRENCY;?>)</th>
    <th>[[.arrival_time.]] - [[.departure_time.]]</th>
    <th>[[.company.]]</th>
    <td><a href="<?php echo Url::build_current(array('cmd','close_all'=>'1'));?>">[[.close_all.]]</a></td>
  </tr>
  <!--LIST:items-->
  <tr>
  	<td>[[|items.guest_name|]]</td>
    <td>[[|items.room_level_name|]]</td>
    <td>[[|items.room_name|]]</td>
    <td align="right">[[|items.change_price|]]</td>
    <td>[[|items.arrival_time|]] - [[|items.departure_time|]]<!--IF:cond([[=items.verify_dayuse=]] and [[=items.brief_departure_time=]]==$_SESSION['night_audit_date'])--><strong>(V.D: <?php echo [[=items.verify_dayuse=]]/10;?>)</strong><!--/IF:cond--></td>
    <td>[[|items.company_name|]]</td>
    <td><a href="<?php echo Url::build_current(array('cmd','room_status_id'=>[[=items.room_status_id=]]));?>">[[.close.]]</a></td>
  </tr>
  <!--/LIST:items-->
  <tr>
    	<td colspan="4" align="right"><strong>[[.total.]]: [[|total|]]
    	</strong>
    	<td colspan="4" class="notice" align="center">&nbsp;</td>
    </tr>
  <!--IF:cond(![[=items=]])-->
  	<tr>
    	<td colspan="8" class="notice" align="center"><p>&nbsp;</p>
    	  [[.no_counters_not_closed.]]
   	    <p>&nbsp;</p></td>
    </tr>
  <!--/IF:cond-->
</table>
<table id="tbl_print" width="100%">
    <tr>
        <td align="center" colspan="2">[[.start_time_night_audit.]]:<?php echo Date('H:i d/m/Y',Url::get('start_time_night_audit'));?></td>
    </tr>
    <tr>
        <td align="left" width="50%">[[.print_user.]]</td>
        <td align="right" width="50%">[[.print_time.]]</td>
    </tr>
    <tr>
        <td align="left" width="50%"><?php echo Session::get('user_id');?></td>
        <td align="right" width="50%"><?php echo Date('H:i d/m/Y',time());?></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><p class="notice"><!--IF:cond([[=items=]])-->[[.need_to_close_all_revenue_in_date_to_continue.]]<!--/IF:cond--></p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> 
	<input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'bar_not_checkout','start_time_night_audit'=>Url::get('start_time_night_audit')));?>'" <?php echo [[=items=]]?'disabled="disabled"':'';?> />
	<!--Url::build('order_list',array('date'=>$_SESSION['night_audit_date-->
    <p>&nbsp;</p></td>
  </tr>
</table>
</div>
<input name="night_audit" type="hidden" id="night_audit" />
</form>
<style type="text/css">
@media print{
    
    input[type=button]{
        display:none;
    }
    #tbl_print{
        display: block;
    }
}
</style>