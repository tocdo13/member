<div style="width:100%;background:#0A1B2A;text-align:center;height:800px;padding-top:100px;">
<!---<div style="width:100%;background:url(packages/user/skins/images/login-bg.jpg);text-align:center;height:800px;padding-top:100px;">--->
<table width="500" cellpadding="5" cellspacing="0" align="center" style="margin-left:auto;margin-right:auto;">
  <tr>
    <td>
	<span><i class="fa fa-cloud w3-text-blue" style="font-size: 60px; margin-right: 10px;"></i></span><span><img src="<?php echo HOTEL_BANNER;?>" style="max-width: 200px;"/></span><br /><br />
	<table width="100%" border="0" cellspacing="0" cellpadding="10">
	  <tr>
		<td align="center"><b style="color:#FF0000;font-size:20px"><?php echo Portal::language('you_dont_access');?></b></td>
	  </tr>
	   <tr>
		<td align="center"><b class="w3-text-white" style="font-size:17px;"><?php echo Portal::language('please_contact_with_admin_to_access');?></b></td>
	  </tr>
		<tr>
		<td align="center" class="w3-text-white"><?php echo Portal::language('go_back');?> <a href="<?php echo Url::build('home');?>" ><?php echo Portal::language('home_page');?></a>&nbsp;| &nbsp;<a href="<?php echo Url::build('sign_out');?>" ><?php echo Portal::language('sign_in_with_other_account');?></a></td>
	  </tr>
	</table><br />
</td>
</tr>	
</div>