<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { 
    	background: #0A1B2A;
    }
@media only screen and (max-width: 500px) {
    body { 
    	background: #0A1B2A;
    }
}
</style>
<div class="w3-container" style="text-align:center;padding-top:100px;">
<!---<div style="width:100%;background:url(<?php echo BACKGROUND_URL;?>) center top;text-align:center;height:800px;padding-top:100px;">--->
<table width="300" cellpadding="5" cellspacing="0" align="center" style="margin-left:auto;margin-right:auto;">
<!---<table width="550" cellpadding="5" cellspacing="0" align="center" style="background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x;background-color:#FFFFFF;border:3px solid #3089E5;margin-left:auto;margin-right:auto;">--->
  <tr>
    <td>
	<span><i class="fa fa-cloud w3-text-blue" style="font-size: 60px; margin-right: 10px;"></i></span><span><img src="<?php echo HOTEL_BANNER;?>" style="max-width: 200px;"/></span>
	<!--IF:login(!User::is_login())-->
	<table cellpadding="5" width="100%" style="height: 100%;" align="center" class="" bgcolor="#0A1B2A">
	<form name="form1" method="post">
	  <tr>
		  <td colspan="2" class="simple_text" align="center" nowrap="nowrap"><?php echo Form::$current->error_messages() ;?></td>
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
  <table width="100%" border="0" cellspacing="0" cellpadding="2" style="height: 100%">
	  <tr>
	  	<td align="center" class="w3-text-gray">Newway PMS - Version: <?php echo VERSION;?>
		- Developed by <a target="_blank" href="http://quanlyresort.com" style="text-decoration: none;"><span class="w3-text-white">TCV</span></a>.,JSC - <span class="w3-text-orange">CSKH: 1900636116</span></td>
	  </tr>
      <tr>
	  	<td align="center" class="w3-text-white">[[.using_for.]]: <?php echo HOTEL_NAME?></td>
	  </tr>
	</table>
</td>
</tr>
</table>
<div>
    <p class="w3-text-gray">Để sử dụng phần mềm được tốt nhất, bạn nên tải các trình duyệt dưới đây</p>
            <p><a class="w3-hide-small" href="https://www.google.com/intl/vi/chrome/browser/desktop/index.html" target="_blank" rel="nofollow" title="Click để tải trình duyệt Chrome"><img src="skins/default/images/login/Chrome.png"> </a><a class="w3-hide-large" href="https://www.google.com/intl/vi/chrome/browser/mobile/index.html" target="_blank" rel="nofollow" title="Click để tải trình duyệt Chrome"><img src="skins/default/images/login/Chrome.png"> </a><a href="http://coccoc.vn" target="_blank" rel="nofollow" title="Click để tải trình duyệt Cốc cốc"><img src="skins/default/images/login/CocCoc.png"></a></p>
</div>
</div>
<script type="text/javascript">
window.onload = function init(){$('user_id').focus();}
</script>
