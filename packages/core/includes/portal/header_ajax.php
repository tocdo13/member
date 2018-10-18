<LINK rel="stylesheet" href="<?php echo Portal::template('core');?>/css/tcv.css" type="text/css">
<LINK rel="stylesheet" href="<?php echo Portal::template('core');?>/css/global.css" type="text/css">
<?php echo Portal::$extra_css;?>
<script src="packages/core/includes/js/jquery/jquery.min.1.4.2.js" type="text/javascript"></script>
<script src="packages/core/includes/js/jquery/ui/jquery.ui.core.js" type="text/javascript"></script>
<?php echo Portal::$extra_js;?>
<script>
	languageId = <?php echo Portal::language();?>;
	BEGINNING_YEAR = <?php echo BEGINNING_YEAR;?>;
	var moduleAllowDoubleClick = '<?php echo MODULE_ALLOW_DOUBLE_CLICK;?>';
	query_string = "?<?php echo urlencode($_SERVER['QUERY_STRING']);?>";
	PORTAL_ID = "<?php echo substr(PORTAL_ID,1);?>";
	jQuery.noConflict();
</script>
<script src="packages/core/includes/js/common.js" type="text/javascript"></script>

<?php if(User::is_admin()){?><script src="packages/core/includes/js/admin.js" type="text/javascript"></script>
<?php }?>
<?php if(User::is_deploy()){?><script src="packages/core/includes/js/deploy.js" type="text/javascript"></script>
<?php }?>
<?php echo Portal::$extra_header;?>