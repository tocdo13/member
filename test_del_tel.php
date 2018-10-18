<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    if(User::is_admin() and Session::get('user_id') == 'developer14')
    {
        set_time_limit(-1);
        DB::delete('TELEPHONE_REPORT_DAILY','portal_id=\'#default\' and hdate >=1498842000');
        echo '<h3>Newway: Deleted all successfull...!</h3>';
    }else
    {
        echo '<h3>'.Portal::language('you_do_not_have_permission_to_do_this_action_please_contact_tcv').'<h3>';
    }
?>