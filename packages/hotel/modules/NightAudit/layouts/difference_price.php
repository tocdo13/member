<form name="DeparturesNotCheckedOutForm" method="post">
<div>
<div>
  <h3>[[.difference_price.]] [[.date.]] <?php echo $_SESSION['night_audit_date'];?></h3>
</div>
<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
  <tr class="table-header">
    <th>[[.guest_name.]]</th>
    <th>[[.room_level.]]</th>
    <th>[[.room_name.]]</th>
    <th>[[.arrival_time.]] - [[.departure_time.]]</th>
    <th>[[.group.]]/[[.tour.]]</th>
    <th>[[.company.]]</th>
    <th>&nbsp;</th>
  </tr>
  <!--LIST:items-->
  <tr>
  	<td>[[|items.guest_name|]]</td>
    <td>[[|items.room_level_name|]]</td>
    <td>[[|items.room_name|]]</td>
    <td>[[|items.arrival_time|]] - [[|items.departure_time|]]</td>
    <td>[[|items.tour_name|]]</td>
    <td>[[|items.company_name|]]</td>
    <td><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.id=]]));?>">[[.correct.]]</a></td>
  </tr>
  <!--/LIST:items-->
  <!--IF:cond(![[=items=]])-->
  	<tr>
    	<td colspan="7" class="notice" align="center"><p>&nbsp;</p>
    	  [[.no_difference_price.]]
   	    <p>&nbsp;</p></td>
    </tr>
  <!--/IF:cond-->
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><p>&nbsp;</p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> <input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'summary_report'));?>'"/><p>&nbsp;</p></td>
  </tr>
</table>
</div>
<input name="night_audit" type="hidden" id="night_audit" />
</form>