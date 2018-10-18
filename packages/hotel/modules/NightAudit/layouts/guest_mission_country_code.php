<style type="text/css">
#tbl_print{ display: none;}
</style>
<form name="GuestMissionCountryCodeForm" method="post">
<div>
<div><h3>[[.in_house_guests_with_mission_country_code.]] [[.in.]] [[.date.]] <?php echo $_SESSION['night_audit_date'];?></h3></div>
<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
  <tr class="table-header">
    <th>[[.fist_name.]]</th>
    <th>[[.last_name.]]</th>
    <th>[[.room_name.]]</th>
    <th>[[.company.]]</th>
    <th>&nbsp;</th>
  </tr>
  <!--LIST:items-->
  <tr>
  	<td><a target="_blank" href="<?php echo Url::build('traveller',array('cmd'=>'edit','id'=>[[=items.traveller_id=]],'r_r_id'=>[[=items.id=]]));?>">[[|items.first_name|]]</a></td>
    <td>[[|items.last_name|]]</td>
    <td>[[|items.room_name|]]</td>
    <td>[[|items.company_name|]]</td>
    <td><a target="_blank" href="<?php echo Url::build('traveller',array('cmd'=>'edit','id'=>[[=items.traveller_id=]],'r_r_id'=>[[=items.id=]]));?>">[[.correct.]]</a></td>
  </tr>
  <!--/LIST:items-->
  <!--IF:cond(![[=items=]])-->
  	<tr>
    	<td colspan="6" class="notice" align="center"><p>&nbsp;</p>[[.no_in_house_guests_with_mission_country_code.]]<p>&nbsp;</p></td>
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
    <td align="center"><p>&nbsp;</p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> <input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'early_checkin','start_time_night_audit'=>Url::get('start_time_night_audit')));?>'"/><p>&nbsp;</p></td>
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