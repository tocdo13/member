<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	require_once 'packages/core/includes/utils/vn_code.php';
	if(URL::get('q') and URL::get('q')!='?'){
		$travellers = DB::fetch_all('
			SELECT 
				traveller.id,
				traveller.first_name,
				traveller.last_name,
				traveller.gender,
				traveller.nationality_id,
				to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
				traveller.address,
				traveller.phone,
				traveller.note,
				traveller.traveller_level_id,
				traveller.passport,
				traveller.email,
                traveller.member_code,
				reservation_traveller.visa_number,
				reservation_traveller.expire_date_of_visa,
				country.name_'.Portal::language().' as nationality_name,
				rownum
			FROM
				traveller
                left outer join reservation_traveller on traveller.id = reservation_traveller.traveller_id
				left outer join country on country.id = traveller.nationality_id   
			WHERE 
				(member_code like \'%'.URL::sget('q').'%\' or passport like \'%'.URL::sget('q').'%\' or (LOWER(FN_CONVERT_TO_VN(traveller.first_name)) like \''.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'))
			ORDER BY traveller.id DESC
			'
		);//AND (rownum > 0 AND rownum <= 10)	
		if(Url::get('passport'))
        {
			foreach($travellers as $key=>$value)
            {
			 	echo $value['passport'].'|'.$value['first_name'].' - '.$value['last_name'].' - '.$value['id']."\n";
        	}
		}elseif(Url::get('member_code')){
		    foreach($travellers as $key=>$value)
            {
                
                $detail_link = '<a target="blank" href="?page=traveller&id='.$value['id'].'">'.'Xem thông tin khách/ View this guest\'s info'.'#'.$value['id'].'</a>';
			 	echo $value['member_code'].'-'.$value['first_name'].'-'.$value['last_name'].'|'.$value['id']."\n";
        	}  
		        
		}else{
			foreach($travellers as $key=>$value){
				echo $value['first_name'].' - '.$value['last_name'].' - '.$value['id'].'|'.$value['passport']."\n";
        	}	
		}
		DB::close();
	}
?>