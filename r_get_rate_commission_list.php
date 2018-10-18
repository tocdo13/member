<?php
    //echo '0000000000000000000';
    //echo Url::get('customer_id');
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    //echo Url::get('i');
	if(User::is_login())
    {
		$content = '';
		$check_rate_customer = false;
		if(Url::get('customer_id')){
			$items = DB::fetch_all('
				SELECT
					crc.*,
                    customer.name
				FROM
					customer_rate_commission crc inner join customer on crc.customer_id = customer.id
				WHERE
					crc.customer_id=\''.Url::get('customer_id').'\'
					AND crc.portal_id = \''.PORTAL_ID.'\'
					AND (crc.start_date <= \''.Date_Time::to_orc_date(Url::get('start_date')).'\' AND crc.end_date >= \''.Date_Time::to_orc_date(Url::get('end_date')).'\')
				ORDER BY
					crc.customer_id
			');
            //System::debug(Date_Time::to_time(Url::get('start_date')));
			if(empty($items)){
				$check_rate_customer = false;
				$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;color:#FF0000;">'.Portal::language('has_no_any_rate_policy_for_this_customer').'. '.Portal::language('please_select_default_rate').'</li>';
			}else{
				$check_rate_customer = true;
                //$rate=0;
                //$display_rate='';
				foreach($items as $key=>$value){
					$rate = $value['commission_rate'];
                    $time_from = Date_Time::convert_orc_date_to_date($value['start_date'],'/');
                    $time_to = Date_Time::convert_orc_date_to_date($value['end_date'],'/');
					$display_rate = 'hoa hồng ';
                    if(Url::get('index'))
                    {
						$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setCommission('.Url::get('index').',\''.$rate.'\');">'.$time_from.'-'.$time_to.'-'.$value['name'].' / '.$display_rate.' :'.$rate.'%</li>';
				    }
				}
			}
		}
		echo $content;
		DB::close();
	}
?>