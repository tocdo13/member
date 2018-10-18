<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
	//if(Url::get('edit_directly')==1){
		if(URL::get('passport') and URL::get('passport')!='?')
        {
			$sql = '
				SELECT
					traveller.id,
					traveller.first_name,
					traveller.last_name,
					traveller.gender,
					to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
					traveller.birth_date_correct,
					traveller.passport,
                    traveller.supply_passport_day,
                    traveller.place_issuance_passport,
                    traveller.member_code,
					traveller.nationality_id,   
					traveller.is_vn,
					traveller.note,
					traveller.phone,
					traveller.address,
					traveller.traveller_level_id,
					reservation_traveller.visa_number as visa,
					to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa,
					to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as entry_date,
					reservation_traveller.port_of_entry,
					to_char(reservation_traveller.back_date,\'DD/MM/YYYY\') as back_date,
					reservation_traveller.entry_target,
					reservation_traveller.go_to_office,
					reservation_traveller.come_from
				FROM
					traveller
					LEFT OUTER JOIN reservation_traveller ON reservation_traveller.traveller_id = traveller.id
					left outer join country on country.id =traveller.nationality_id
				WHERE
					traveller.passport=\''.URL::sget('passport').'\'
				ORDER BY
					reservation_traveller.id DESC
					
			';
			
			if($traveller = DB::fetch($sql)){
				if($traveller['nationality_id']){
					$country = DB::fetch('select id,code_1,name_'.Portal::language().' as name from country where id = '.$traveller['nationality_id'].'');
					$traveller['nationality_id'] = $country['code_1'];
					$traveller['nationality_name'] = $country['name'];
				}else{
					$traveller['nationality_id'] ='';
					$traveller['nationality_name'] ='';
				}
				echo 'var traveller={"":""';
				foreach($traveller as $field=>$value)
				{
					echo ',"'.$field.'":"'.$value.'"';
				}
				echo '};';
			}
		}
        else if(URL::get('member_code') and URL::get('member_code')!='?')
        {
            $sql = '
				SELECT
					traveller.id,
					traveller.first_name,
					traveller.last_name,
					traveller.gender,
					to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
					traveller.birth_date_correct,
					traveller.passport,
                    traveller.member_code,
					traveller.nationality_id,   
					traveller.is_vn,
					traveller.note,
					traveller.phone,
					traveller.address,
					traveller.traveller_level_id,
					reservation_traveller.visa_number as visa,
					to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa,
					to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as entry_date,
					reservation_traveller.port_of_entry,
					to_char(reservation_traveller.back_date,\'DD/MM/YYYY\') as back_date,
					reservation_traveller.entry_target,
					reservation_traveller.go_to_office,
					reservation_traveller.come_from
				FROM
					traveller
					LEFT OUTER JOIN reservation_traveller ON reservation_traveller.traveller_id = traveller.id
					left outer join country on country.id =traveller.nationality_id
				WHERE
					traveller.passport=\''.URL::sget('passport').'\'
				ORDER BY
					reservation_traveller.id DESC
					
			';
			
			if($traveller = DB::fetch($sql)){
				if($traveller['nationality_id']){
					$country = DB::fetch('select id,code_1,name_'.Portal::language().' as name from country where id = '.$traveller['nationality_id'].'');
					$traveller['nationality_id'] = $country['code_1'];
					$traveller['nationality_name'] = $country['name'];
				}else{
					$traveller['nationality_id'] ='';
					$traveller['nationality_name'] ='';
				}
				echo 'var traveller={"":""';
				foreach($traveller as $field=>$value)
				{
					echo ',"'.$field.'":"'.$value.'"';
				}
				echo '};';
			}
        }
        else if(	
			URL::get('traveller_id') 
			and URL::get('traveller_id')!='' 
			and $traveller = DB::fetch('
				SELECT 
					id,first_name,last_name,gender,nationality_id,
					to_char(birth_date,\'DD/MM/YYYY\') as birth_date,
					address,phone,note,passport,member_code,traveller_level_id,visa,expire_date_of_visa
				FROM
					traveller 
				WHERE 
					id='.URL::get('traveller_id').''
					
			)
		)
        {
			if($traveller['nationality_id']){
				$country = DB::fetch('select id,code_1,name_'.Portal::language().' as name from country where id = '.$traveller['nationality_id'].'');
				$traveller['nationality_id'] = $country['code_1'];
				$traveller['nationality_name'] = $country['name'];
			}else{
				$traveller['nationality_id'] ='';
				$traveller['nationality_name'] ='';
			}	
			echo 'var traveller={"":""';
			foreach($traveller as $field=>$value)
			{
				echo ',"'.$field.'":"'.$value.'"';
			}
			echo '};';
		}
	//}
	DB::close();
?>