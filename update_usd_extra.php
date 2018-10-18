<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    if(User::is_admin() and Session::get('user_id') == 'developer14')
    {
        set_time_limit(-1);
        $extra_service_arr = DB::fetch_all('SELECT extra_service_invoice_detail.id,extra_service_invoice_detail.price FROM extra_service_invoice_detail');
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
	    $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        foreach($extra_service_arr as $key => $value)
        {
            $usd_arr = array('usd_price'=> $value['price']/$exchange_rate);
            DB::update('extra_service_invoice_detail',$usd_arr,'id='.$value['id']);
        }
        echo '<h3>'.Portal::language('success').'<h3>';
    }else
    {
        echo '<h3>'.Portal::language('you_do_not_have_permission_to_do_this_action_please_contact_tcv').'<h3>';
    }
?>