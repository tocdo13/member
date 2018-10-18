<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    if(User::is_admin() and Session::get('user_id') == 'developer14')
    {
        set_time_limit(-1);
        $payment_room = DB::fetch_all('SELECT * FROM payment WHERE type =\'RESERVATION\' and (payment.time>=1509469200 AND payment.time<=1512061140)');
        $total_room = 0;
        foreach($payment_room as $key => $value)
        {
            $total_room += $value['amount'];            
        }
        $revenue_room = DB::fetch_all('')
        $total_revenue_room = 0;
        echo $total_room .'/'. $total_revenue_room; 
    }else
    {
        echo '<h3>'.Portal::language('you_do_not_have_permission_to_do_this_action_please_contact_tcv').'<h3>';
    }
?>