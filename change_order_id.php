<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	require_once 'packages/core/includes/utils/vn_code.php';
    date_default_timezone_set('Asia/Saigon');
	
    if(User::id()=='developer14')
    {
        $pc_recommend_detail = DB::fetch_all('SELECT * FROM pc_recommend_detail WHERE pc_recommend_detail.order_id is not null');
        foreach($pc_recommend_detail as $key => $value)
        {
            $k = explode(',',$value['order_id']);
            foreach($k as $v)
            {
                $order_arr = array(
                    'pc_recommend_detail_id' => $value['id'],
                    'order_id' => $v    
                );
                DB::insert('pc_recommend_detail_order', $order_arr);
            }
        }
        echo 'Change data success!';
    }else
    {
        echo 'Errors!';        
    }
	DB::close();
?>