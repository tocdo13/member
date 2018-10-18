<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
    //echo Url::get('i').'aaaaaaaaaaaaa';
    //exit();
	if(User::is_login() and Url::get('room_level_id') and Url::get('adult')){
		$content = '';
		$check_rate_customer = false;
		if(Url::get('customer_id')){
			$items = DB::fetch_all('
				SELECT
					crp.*
				FROM
					customer_rate_policy crp
				WHERE
					crp.customer_id=\''.Url::iget('customer_id').'\'
					AND crp.room_level_id = '.Url::iget('room_level_id').' AND crp.portal_id = \''.PORTAL_ID.'\'
					AND (crp.start_date <= \''.Date_Time::to_orc_date(Url::get('start_date')).'\' AND crp.end_date >= \''.Date_Time::to_orc_date(Url::get('end_date')).'\')
				ORDER BY
					crp.name
			');
			if(empty($items)){
				$check_rate_customer = false;
				$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;color:#FF0000;">'.Portal::language('has_no_any_rate_policy_for_this_customer').'. '.Portal::language('please_select_default_rate').'</li>';
			}else{
				$check_rate_customer = true;
				foreach($items as $key=>$value){
					$rate = 0;
					$display_rate = '...';
					if(Url::iget('adult')==1 and $value['rate_1_adult']){
						$rate = $value['rate_1_adult'];
						$display_rate = Portal::language('1_adult');
					}elseif(Url::iget('adult')==2 and $value['rate_2_adults']){
						$rate = $value['rate_2_adults'];
						$display_rate = Portal::language('2_adults');
					}
					elseif(Url::iget('adult')==3 and $value['rate_3_adults']){
						$rate = $value['rate_3_adults'];
						$display_rate = Portal::language('3_adults');
					}elseif(Url::iget('adult')>=4 and $value['rate_extra_adults']){
					    //start: KID them de tinh lai chinh sach gia
					    $num_adult =  Url::iget('adult') - 3;  
						$rate = $value['rate_3_adults'] + $num_adult*$value['rate_extra_adults'];
                        //end
						$display_rate = Portal::language('extra_adults');
					}
					if(Url::iget('child')==1 and $value['rate_1_child']){
						$rate += $value['rate_1_child'];
						$display_rate .= ' '.Portal::language('and').' '.Portal::language('1_child');
					}elseif(Url::iget('child')==2 and $value['rate_2_children']){
						$rate += $value['rate_2_children'];
						$display_rate .= ' '.Portal::language('and').' '.Portal::language('2_children');
					}
					elseif(Url::iget('child')==3 and $value['rate_3_children']){
						$rate += $value['rate_3_children'];
						$display_rate .= ' '.Portal::language('and').' '.Portal::language('3_children');
					}elseif(Url::iget('child')>=4 and $value['rate_extra_children']){
					    //start: KID them de tinh lai chinh sach gia
					    $num_child =  Url::iget('child') - 3;  
						$rate += ($value['rate_3_children'] + $num_child*$value['rate_extra_children']);
                        //end   
						$display_rate .= ' '.Portal::language('and').' '.Portal::language('extra_children');
					}
					if(Url::iget('index')){
						$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setRate('.Url::iget('index').',\''.$rate.'\');jQuery(\'#room_quantity_'.Url::iget('index').'\').focus();">'.$value['name'].' / '.$display_rate.' - '.$rate.' '.HOTEL_CURRENCY.'</li>';
					}else{
						$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setRate('.Url::iget('room_level_id').',\''.$rate.'\');jQuery(\'#room_quantity_'.Url::iget('room_level_id').'\').focus();">'.$value['name'].' / '.$display_rate.' - '.$rate.' '.HOTEL_CURRENCY.'</li>';
					}
				}
			}
		}
		if($check_rate_customer==false){
			if($items = DB::fetch_all('
				SELECT 
					room_level_rate.id,room_level_rate.rate,room_level_rate.name,room_level_rate.privilege
				FROM 
					room_level_rate
				WHERE 
					room_level_rate.room_level_id = '.Url::iget('room_level_id').' AND portal_id = \''.PORTAL_ID.'\'
				ORDER BY 
					room_level_rate.name
			')){//1401: id cua module Reservation
				foreach($items as $key=>$value){
					if($value['privilege']=='ADD'){
						if(!User::can_add(1401,ANY_CATEGORY)){
							unset($items[$key]);
						}
					}elseif($value['privilege']=='ADMIN'){
						if(!User::can_admin(1401,ANY_CATEGORY)){
							unset($items[$key]);
						}
					}
				}
				foreach($items as $key=>$value){
					if(Url::get('index')){
						$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setRate('.Url::iget('index').',\''.$value['rate'].'\');jQuery(\'#room_quantity_'.Url::iget('index').'\').focus();">'.$value['name'].' / '.$value['rate'].' '.HOTEL_CURRENCY.'</li>';
					}else{
						$content .= '<li style="list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setRate('.Url::iget('room_level_id').',\''.$value['rate'].'\');jQuery(\'#room_quantity_'.Url::iget('room_level_id').'\').focus();">'.$value['name'].' / '.$value['rate'].' '.HOTEL_CURRENCY.'</li>';
					}
				}
			}else{
				$content .= '<li style="list-style:none;">--------------------------------------------------</li>';
				$item = DB::fetch('
					SELECT 
						room_level.id,room_level.price,room_level.name
					FROM 
						room_level
					WHERE 
						room_level.id = '.Url::iget('room_level_id').' AND portal_id = \''.PORTAL_ID.'\'
				');
			if(Url::get('index')){	
				$content .= '<li style="margin-left:5px;list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setRate('.Url::iget('index').',\''.$item['price'].'\');jQuery(\'#room_quantity_'.Url::iget('index').'\').focus();">'.$item['name'].' / '.System::display_number($item['price']).' '.HOTEL_CURRENCY.'</li>';
			}else{
				$content .= '<li style="margin-left:5px;list-style:inside;padding:2px;cursor:pointer;border-bottom:1px solid #FFFFFF;" onclick="setRate('.Url::iget('room_level_id').',\''.$item['price'].'\');jQuery(\'#room_quantity_'.Url::iget('index').'\').focus();">'.$item['name'].' / '.System::display_number($item['price']).' '.HOTEL_CURRENCY.'</li>';
			}
			}
		}
		echo $content;
		DB::close();
	}
?>