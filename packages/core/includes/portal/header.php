<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK REV="made" href="mailto:email@tcv.vn">
<META HTTP-EQUIV="Content-Type" CONTENT="text/css; charset=UTF-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="cache-control" content="no-store">
<META NAME="keywords" CONTENT="<?php echo isset(Portal::$meta_keywords)?Portal::$meta_keywords:Portal::get_setting('website_keywords');?>">
<META NAME="description" CONTENT="<?php echo Portal::get_setting('website_description')?Portal::get_setting('website_description'):Portal::$meta_description;?>">
<META NAME="ROBOTS" CONTENT="ALL">
<META NAME="author" CONTENT="TCV.,JSC">
<!---<meta name="viewport" content="width=device-width, initial-scale=1.0">--->
<TITLE><?php echo HOTEL_NAME;?></TITLE>
<LINK rel="stylesheet" href="<?php echo Portal::template('core');?>/css/tcv.css?v=<?php echo VERSION;?>" type="text/css">
<LINK rel="stylesheet" href="<?php echo Portal::template('core');?>/css/global.css?v=<?php echo VERSION;?>" type="text/css">
<?php echo Portal::$extra_css;?>
<link rel="shortcut icon" href="favicon.ico" >
<script src="packages/core/includes/js/jquery/jquery-1.7.1.js?v=<?php echo VERSION;?>" type="text/javascript"></script>
<script src="packages/core/includes/js/jquery/ui/jquery.ui.core.js?v=<?php echo VERSION;?>" type="text/javascript"></script>
<script>
	languageId = <?php echo Portal::language();?>;
	BEGINNING_YEAR = <?php echo BEGINNING_YEAR;?>;
	var moduleAllowDoubleClick = '<?php echo MODULE_ALLOW_DOUBLE_CLICK;?>';
	query_string = "?<?php echo urlencode($_SERVER['QUERY_STRING']);?>";
	PORTAL_ID = "<?php echo substr(PORTAL_ID,1);?>";
	jQuery.noConflict();
    var LATE_CHECKIN_AUTO = '<?php echo LATE_CHECKIN_AUTO;?>';
    var AUTO_LI_START_TIME = '<?php echo AUTO_LI_START_TIME;?>';
    var AUTO_LI_END_TIME = '<?php echo AUTO_LI_END_TIME;?>';
    var TIME_IN_DEFAULT = '<?php echo CHECK_IN_TIME;?>';
	var TIME_OUT_DEFAULT = '<?php echo CHECK_OUT_TIME;?>';
	var CAN_ADMIN = <?php echo User::can_admin()?'true':'false'?>;
	var CAN_CHANGE_PRICE = <?php echo User::can_delete(Portal::get_module_id('CheckIn'),ANY_CATEGORY)?'true':'false'?>;
    //Kimtan them cac quyen de sua gia
    var CAN_ADMIN_PRICE = <?php echo User::can_admin(Portal::get_module_id('CheckIn'),ANY_CATEGORY)?'true':'false'?>;
    var CAN_EDIT_PRICE = <?php echo User::can_edit(Portal::get_module_id('CheckIn'),ANY_CATEGORY)?'true':'false'?>;
    var CAN_ADD_PRICE = <?php echo User::can_add(Portal::get_module_id('CheckIn'),ANY_CATEGORY)?'true':'false'?>;
	//end Kimtan them
    var CAN_CHECKIN = <?php echo User::can_add(Portal::get_module_id('CheckIn'),ANY_CATEGORY)?'true':'false'?>;
	var CAN_CHECKOUT = <?php echo User::can_edit(Portal::get_module_id('CheckIn'),ANY_CATEGORY)?'true':'false'?>;
	var CAN_EDIT_PRICE_DN = <?php echo User::can_admin(Portal::get_module_id('PrivilegeEditPrice'),ANY_CATEGORY)?'true':'false'?>;
</script>
<script src="packages/core/includes/js/common.js?v=<?php echo VERSION;?>" type="text/javascript"></script>
<?php if(User::is_admin()){?>
<script src="packages/core/includes/js/admin.js?v=<?php echo VERSION;?>" type="text/javascript"></script>
<?php }?>
<?php if(User::is_deploy()){?>
<script src="packages/core/includes/js/deploy.js?v=<?php echo VERSION;?>" type="text/javascript"></script>
<?php }?>
<?php echo Portal::$extra_js;?>
<?php echo Portal::$extra_header;?></HEAD>
<BODY>
<div id='loading-layer' style=" display:none">
	<div id='loading-layer-text'><img src="packages/core/skins/default/images/ajax-loader-big.gif" align="absmiddle" hspace="5" class="displayIn"><br /><?php echo Portal::language('Loading');?>......</div>
</div>
<div class="mask-window" style="background-color:#666;width:100%;height:100%;z-index:9999;position:absolute;display:none;"></div>