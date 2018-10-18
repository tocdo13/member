<div style="width:100%;background: #0A1B2A;text-align:center;height:800px;padding-top:100px;">
<!---<div style="width:100%;background:url(<?php echo BACKGROUND_URL;?>) center top;text-align:center;height:800px;padding-top:100px;">--->
<table width="300" cellpadding="5" cellspacing="0" align="center" style="background:#0A1B2A;margin-left:auto;margin-right:auto;">
<!---<table width="550" cellpadding="5" cellspacing="0" align="center" style="background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x;background-color:#FFFFFF;border:3px solid #3089E5;margin-left:auto;margin-right:auto;">--->
  <tr>
    <td>
	<img src="<?php echo HOTEL_BANNER;?>"/>
	<!--IF:login(!User::is_login())-->
	<table cellpadding="5" width="100%" align="center" class="" bgcolor="#0A1B2A">
	<form name="form1" method="post">
	  <tr>
		  <td class="simple_text" align="left" nowrap="nowrap"><?php echo Form::$current->error_messages();?></td>
	  </tr>
	  <!---<tr>
		  <td class="simple_text" width="20%" align="center" nowrap="nowrap">[[.user_name.]]</td>
	  </tr>--->
	  <tr>
		  <td style="width: 10%;"><i class="fa fa-user w3-text-white" style="font-size: 30px;"></i></td>
          <td align="center" style="width: 85%;"><input class="w3-input w3-round-xxlarge" name="user_id" type="text" id="user_id" style="width:100%;" /></td>
	  </tr>
	  <!---<tr>
	      <td class="simple_text" width="20%" align="center">[[.password.]]</td>
	  </tr>--->
	  <tr>
	      <td style="width: 10%;"><i class="fa fa-unlock-alt w3-text-white" style="font-size: 30px;"></i></td>
          <td align="center" style="width: 85%;"><input class="w3-input w3-round-xxlarge" name="password" type="password" id="password" style="width:100%;"/></td>
	  </tr>
	<tr>
	  <td colspan="2" align="center" class="simple_text">
	    <input class="w3-btn w3-round-xxlarge w3-orange w3-text-white" name="sign_in" type="submit" id="sign_in" value=" [[.login.]] " style="width:auto;height:auto;font-size:14px; text-transform: uppercase;"/>
		<p>&nbsp;</p>
      </td>
	 </tr>
	  </form>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="2" style="">
	  <tr>
	  	<td align="center" class="w3-text-gray">Newway PMS - Version: <?php echo VERSION;?>
		- Developed by <a target="_blank" href="http://quanlyresort.com">TCV</a>.,JSC</td>
	  </tr>
	</table>
</td>
</tr>
</table>
</div>
<script type="text/javascript">
window.onload = function init(){$('user_id').focus();}
</script>
