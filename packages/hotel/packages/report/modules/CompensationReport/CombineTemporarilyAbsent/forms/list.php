<?php
class CombineTemporarilyAbsentForm extends Form
{
	function CombineTemporarilyAbsentForm()
	{
		Form::Form('CombineTemporarilyAbsentForm');
		$this->link_css('skins/default/css/cms.css');		
	}		
	function draw()
	{	
		$item_per_page = 999999999;
		$cond = 	  ' 1>0 AND (RESERVATION_ROOM.status=\'CHECKIN\' OR RESERVATION_ROOM.status=\'CHECKOUT\') and ((RESERVATION_ROOM.TIME_IN>'.(Date_Time::to_time(date('d/m/Y'))-4*3600).' and RESERVATION_ROOM.TIME_IN<='.(Date_Time::to_time(date('d/m/Y'))+20*3600).'))';
		$extra_cond = ' 1>0 AND (RESERVATION_ROOM.status=\'CHECKIN\' OR RESERVATION_ROOM.status=\'CHECKOUT\') and (RESERVATION_ROOM.TIME_IN>'.(Date_Time::to_time(date('d/m/Y',time()-24*3600))+20*3600).' and RESERVATION_ROOM.TIME_IN<'.(Date_Time::to_time(date('d/m/Y'))+20*3600).')';
		require_once 'packages/core/includes/utils/paging.php';
		$sql = '
		SELECT
				count(*) AS acount
			FROM
				RESERVATION_TRAVELLER
				INNER JOIN TRAVELLER on TRAVELLER.id = RESERVATION_TRAVELLER.TRAVELLER_ID
				INNER JOIN RESERVATION_ROOM on RESERVATION_ROOM.ID = RESERVATION_TRAVELLER.RESERVATION_ROOM_ID
				INNER JOIN reservation on reservation.id = reservation_room.reservation_id
				INNER JOIN country on country.id = TRAVELLER.NATIONALITY_ID
			WHERE
				'.$cond.' and reservation.portal_id=\''.PORTAL_ID.'\'
		';	
		$total =  DB::fetch($sql,'acount');
		$paging =  paging($total,$item_per_page,10,false,'page_no',array('type'));
		$sql2 = 'SELECT  DISTINCT
					country.name_'.Portal::language().' as country_name
					,traveller.nationality_id as id
				FROM 
					traveller
					INNER JOIN reservation_traveller ON reservation_traveller.traveller_id = traveller.id
					INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
					INNER JOIN reservation on reservation.id = reservation_room.reservation_id
					INNER JOIN country ON country.id = traveller.nationality_id
				WHERE 	
					'.$cond.' and reservation.portal_id=\''.PORTAL_ID.'\'
				GROUP BY 
					traveller.nationality_id,country.name_'.Portal::language().'
				ORDER BY 
				    country.name_'.Portal::language().'';
		$national = DB::fetch_all($sql2);
		foreach($national as $id => $nation){
			$national[$id]['male'] = 0;
			$national[$id]['female'] = 0;
			$national[$id]['total_guest'] = 0;	
		}
		$sql3 = '
			select * from 
			( SELECT 
					traveller.id as id
					,reservation_room.id as reservation_room_id
					,reservation_room.room_id as room_id
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,concat(traveller.first_name,traveller.last_name) as full_name
					,traveller.gender
					,TO_CHAR(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
					,country.name_'.Portal::language().' as country_name
					,reservation_traveller.note
					,traveller.first_name
					,traveller.last_name
					,traveller.is_vn
					,DECODE(traveller.transit,1,\'TRANSIT\',traveller.passport) as passport
					,traveller.nationality_id
					,room.name as room_name
					,ROWNUM as rownumber
                    ,traveller.address
				FROM 
					reservation_traveller
					INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
					INNER JOIN reservation on reservation.id = reservation_room.reservation_id
					INNER JOIN room ON room.id = reservation_room.room_id
					INNER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
					INNER JOIN country ON country.id = traveller.nationality_id
				WHERE 	
					'.$cond.'  and reservation.portal_id=\''.PORTAL_ID.'\'
					)
			WHERE
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
				';
		$items = DB::fetch_all($sql3);			
		$total_vietnamese = 0;
		$total_national = 0;
		$vietkieu = 0;	
		$total_guest = 0;
		$total_male =0;
		$total_female = 0;
		foreach($items as $key=>$value)
		{
			if($value['gender'] == 1)
			{
				$items[$key]['gender'] = Portal::language('male');
			}
			else
			{
				$items[$key]['gender'] = Portal::language('female');
			}
            /*
			if(($value['nationality_id'] == 460) && ($value['is_vn'] == '')){
				$total_vietnamese++;	
			}
            */
            if(($value['nationality_id'] == 460)){
				$total_vietnamese++;	
			}
			if(($value['nationality_id'] != 460) && ($value['is_vn'] == '')){
				$total_national++;	
			}
			else 
                if($value['is_vn'] != ''){
    				$vietkieu++;	
    			}
			if($value['nationality_id'] == $national[$value['nationality_id']]['id']){
				if($value['gender'] == 1){
					$national[$value['nationality_id']]['male']++; 
				}else{
					$national[$value['nationality_id']]['female']++; 	
				}
				$national[$value['nationality_id']]['total_guest'] ++;
			}
			$total_guest++;
		}	
		//System::debug($national);
		$this->parse_layout('list_vn',array(
			'paging'=>$paging,
			'items'=>$items	,
			'total_vietnamese'=>$total_vietnamese,
			'total_national'=>$total_national,
			'vietkieu'=>$vietkieu,
			'total_guest'=>$total_guest,
			'national'=>$national
		));
	}
}
?>