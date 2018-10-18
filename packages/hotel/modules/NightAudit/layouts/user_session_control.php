<style type="text/css">
#tbl_print{ display: none;}
</style>
<form name="GuestMissionCountryCodeForm" method="post">
<div>
<div><h3>[[.user_session_list.]]</h3></div>
<?php $id=''; ?>
<!--LIST:items-->
<?php if(Session::get('user_id')!=[[=items.id=]]){    
    $id.= [[=items.id=]].','; }
?>
<!--/LIST:items-->
 <?php $id = substr($id,0,-1);?>
<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
  <tr class="table-header">
    <th>[[.ip_address.]]</th>
    <th>[[.User.]]</th>
    <th>[[.login.]]</th>
    <th>[[.department.]]</th>
    <th>[[.state.]]</th>
    <th><a href="<?php echo Url::build_current(array('cmd','id'=>$id));?>">[[.logout_all.]]</a></th>
  </tr>
  <!--LIST:items-->
  <tr>
  	<td>[[|items.ip|]]</td>
    <td>[[|items.id|]]</td>
    <td>[[|items.login|]]</td>
    <td>[[|items.department_name|]]</td>
    <td>&nbsp;</td>
    <td>
    	<?php if(Session::get('user_id')!=[[=items.id=]]){?>
    	<a href="<?php echo Url::build_current(array('cmd','id'=>[[=items.id=]]));?>">[[.logout.]]</a>   
        <?php }?>
     </td>
  </tr>
  <!--/LIST:items-->
  <!--IF:cond(![[=items=]])-->
  	<tr>
    	<td colspan="6" class="notice" align="center"><p>&nbsp;</p>[[.no_user_session.]]<p>&nbsp;</p></td>
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
    <td align="center"><p>&nbsp;</p><input type="button" value="[[.close_night_audit.]]" onclick="window.location='<?php echo Url::build('room_map');?>'" /> <input type="button" value="[[.continue_night_audit.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'without_guest_name','start_time_night_audit'=>Url::get('start_time_night_audit')));?>'"/><p>&nbsp;</p></td>
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