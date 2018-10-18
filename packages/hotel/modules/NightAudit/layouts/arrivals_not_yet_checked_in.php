<style type="text/css">
#tbl_print{ display: none;}
</style>
<form name="ArrivalsNotYetCheckedInForm" method="post">
<div>
<div>
  <h3>[[.arrivals_not_yet_checked_in.]] [[.in.]] [[.date.]] <?php echo $_SESSION['night_audit_date'];?></h3>
</div>
<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
  <tr class="table-header">
    <th>#</th>
    <th>[[.guest_name.]]</th>
    <th>[[.room_level.]]</th>
    <th>[[.room_name.]]</th>
    <th>[[.arrival_time.]] - [[.departure_time.]]</th>
   
    <th>[[.source.]]</th>
    <th>[[.status.]]</th>
    <th>[[.confirm.]]</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  <?php $i = 1;?>
  <!--LIST:items-->
  <tr>
    <td><?php echo $i++;?></td>
  	<td>[[|items.guest_name|]]</td>
    <td>[[|items.room_level_name|]]</td>
    <td>[[|items.room_name|]]</td>
    <td>[[|items.arrival_time|]] - [[|items.departure_time|]]</td>
    <td>[[|items.customer_name|]]</td>
    <td>[[|items.status|]]</td>
    <!--IF:confirm([[=items.confirm=]] == 1)-->
    <td><input  type="checkbox" checked="checked"/></td>
    <!--ELSE-->
    <td><input  type="checkbox"/></td>
    <!--/IF:confirm-->
    
    <td><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.id=]]));?>">[[.correct.]]</a></td>
    <!--IF:confirm([[=items.confirm=]] == 0)-->
    <td><a target="_blank" href="<?php echo Url::build('night_audit',array('cmd'=>'cancel','id'=>[[=items.reservation_id=]],'r_r_id'=>[[=items.id=]]));?>">[[.cancel.]]</a></td>
    <!--ELSE-->
    <td>&nbsp;</td>
    <!--/IF:confirm-->
  </tr>
  <!--/LIST:items-->
  <!--IF:cond(![[=items=]])-->
  	<tr>
    	<td colspan="11" class="notice" align="center"><p>&nbsp;</p>
    	  [[.no_arrivals_not_yet_checked_in.]]
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
    <td align="center"><p>&nbsp;</p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> <input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'departures_not_checked_out','start_time_night_audit'=>Url::get('start_time_night_audit')));?>'"/><p>&nbsp;</p></td>
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