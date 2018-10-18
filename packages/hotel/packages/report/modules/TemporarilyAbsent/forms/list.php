<?php
class TemporarilyAbsentForm extends Form
{
	function TemporarilyAbsentForm()
	{
		Form::Form('TemporarilyAbsentForm');
		$this->link_css('skins/default/css/cms.css');		
	}		
	function draw()
	{
        $cond = 	  ' (
                            (reservation_room.status=\'CHECKIN\' and reservation_traveller.status=\'CHECKIN\') 
                                or
                            (reservation_room.status=\'CHECKOUT\' and reservation_room.arrival_time = reservation_room.departure_time and reservation_room.CHANGE_ROOM_TO_RR is null)
                        ) 
                        and (room_level.is_virtual=0 or room_level.is_virtual is NULL )  
                        AND (
                                (reservation_traveller.old_arrival_time is null and from_unixtime(reservation_traveller.arrival_time) <= \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' and from_unixtime(reservation_traveller.departure_time) > \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\')
                                or 
                                (reservation_traveller.old_arrival_time is not null and from_unixtime(reservation_traveller.old_arrival_time) <= \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' and from_unixtime(reservation_traveller.departure_time) > \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\')
                        )
                        and reservation.portal_id=\''.PORTAL_ID.'\'';
                        
		$extra_cond = ' (
                            (reservation_room.status=\'CHECKIN\' and reservation_traveller.status=\'CHECKIN\') 
                                or
                            (reservation_room.status=\'CHECKOUT\' and reservation_room.arrival_time = reservation_room.departure_time and reservation_room.CHANGE_ROOM_TO_RR is null)
                        ) 
                        and (room_level.is_virtual=0 or room_level.is_virtual is NULL ) 
                        AND (
                                (reservation_traveller.old_arrival_time is null and from_unixtime(reservation_traveller.arrival_time) = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' )
                                or 
                                (reservation_traveller.old_arrival_time is not null and from_unixtime(reservation_traveller.old_arrival_time) = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' )
                        ) 
                        --and from_unixtime(reservation_traveller.departure_time) > \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' 
                        and reservation.portal_id=\''.PORTAL_ID.'\'';
		$layout = 'list';
		if(Url::get('type')==1) 
		{
			$cond .=' and country.code_2<>\'VNM\'';
			$extra_cond .=' and country.code_2<>\'VNM\'';
		}
		else
		{
			$layout = 'list_vn';
			$cond .=' and country.code_2=\'VNM\'';
			$extra_cond .=' and country.code_2=\'VNM\'';
		}
        $year = substr(date('y'),-1);
        $dir_string = 'resources\PA18\\'.str_replace('#','',PORTAL_ID).'\\'.PA18_HOTEL_CODE.''.date('md').$year.'.txt';
        
        if(file_exists($dir_string))
        {
            $content = file_get_contents($dir_string);
            $cond .= ' and ('.$content.')';
        }
        
        //$cond .= ' and RESERVATION_TRAVELLER.pa18_lt = 1';
        //$extra_cond .= ' and RESERVATION_TRAVELLER.pa18_lt = 1';
        
		$item_per_page = 999999999;
		require_once 'packages/core/includes/utils/paging.php';
		$sql = '
		SELECT
				count(distinct traveller.id) AS acount
			FROM
				RESERVATION_TRAVELLER
				INNER JOIN TRAVELLER on TRAVELLER.id = RESERVATION_TRAVELLER.TRAVELLER_ID
				INNER JOIN RESERVATION_ROOM on RESERVATION_ROOM.ID = RESERVATION_TRAVELLER.RESERVATION_ROOM_ID
                inner join room on reservation_room.room_id=room.id
                inner join room_level on room.room_level_id = room_level.id
				INNER JOIN reservation on reservation.id = reservation_room.reservation_id
				INNER JOIN country on country.id = TRAVELLER.NATIONALITY_ID
			WHERE
				'.$cond.'
		';
		$total =  DB::fetch($sql,'acount');
		//khach luu
		$sql = '
		SELECT
				count(distinct traveller.id) AS acount
			FROM
				RESERVATION_TRAVELLER
				INNER JOIN TRAVELLER on TRAVELLER.id = RESERVATION_TRAVELLER.TRAVELLER_ID
				INNER JOIN RESERVATION_ROOM on RESERVATION_ROOM.ID = RESERVATION_TRAVELLER.RESERVATION_ROOM_ID
                inner join room on reservation_room.room_id=room.id
                inner join room_level on room.room_level_id = room_level.id
				INNER JOIN reservation on reservation.id = reservation_room.reservation_id
				INNER JOIN country on country.id = TRAVELLER.NATIONALITY_ID
			WHERE
				'.$extra_cond.' 
		';	
		$total_guest_new_male = DB::fetch($sql.' and TRAVELLER.gender=1','acount');
        $total_guest_new_female = DB::fetch($sql.' and TRAVELLER.gender=0','acount');
        $paging =  paging($total,$item_per_page,10,false,'page_no',array('type'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					TRAVELLER.id,
					Traveller.first_name, traveller.last_name,
					traveller.gender, 
					traveller.address,
					reservation_traveller.visa_number,
                    DECODE(
                    reservation_traveller.visa_number,   \'TRANSIT\',\'TRANSIT\',
                                                        traveller.passport
                    )  as passport,
					to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa,
					reservation_traveller.go_to_office,
					reservation_traveller.occupation,
					reservation_traveller.entry_target,
					country.code_'.Portal::language().' as country,
					to_char(RESERVATION_ROOM.ARRIVAL_TIME,\'DD/MM/YYYY\') as ARRIVAL_TIME,
					to_char(RESERVATION_ROOM.DEPARTURE_TIME,\'DD/MM/YYYY\') as DEPARTURE_TIME,
					to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as date_entry,
                    (
                    CASE 
                    WHEN reservation_traveller.time_in_pa18 is null or reservation_traveller.time_in_pa18 = 0
                    THEN reservation_traveller.arrival_time
                    ELSE reservation_traveller.time_in_pa18
                    END
                    ) as rt_arrival_time,
                    (
                    CASE 
                    WHEN reservation_traveller.time_out_pa18 is null or reservation_traveller.time_out_pa18 = 0
                    THEN reservation_traveller.DEPARTURE_TIME
                    ELSE reservation_traveller.time_out_pa18
                    END
                    ) as rt_DEPARTURE_TIME,
					reservation_traveller.port_of_entry as port,
                    reservation_traveller.id as rt_id,
					room.name as room_name, to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
					ROWNUM as rownumber
				FROM
					RESERVATION_TRAVELLER
					INNER JOIN TRAVELLER on TRAVELLER.id = RESERVATION_TRAVELLER.TRAVELLER_ID
					INNER JOIN RESERVATION_ROOM on RESERVATION_ROOM.ID = RESERVATION_TRAVELLER.RESERVATION_ROOM_ID
                    inner join room on reservation_room.room_id=room.id
                    inner join room_level on room.room_level_id = room_level.id
    				INNER JOIN reservation on reservation.id = reservation_room.reservation_id
					INNER JOIN country on country.id = TRAVELLER.NATIONALITY_ID
                WHERE '.$cond.'
				ORDER BY
					TRAVELLER.last_name DESC
			)
			WHERE
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';		
		$items = DB::fetch_all($sql);	
        if (User::is_admin())
        {
            //System::debug($sql);
            //System::debug($items);
        }
		$total_guest_male = 0;
		$total_guest_female = 0;	
		foreach($items as $key=>$value)
		{
			if($value['gender'])
			{
				$total_guest_male++;
			}
			else
			{
				$total_guest_female++;
			}
		}
		$total = $total_guest_female + $total_guest_male;
		$this->parse_layout($layout,array(
			'paging'=>$paging,
			'items'=>$items	,
			'total_guest_new'=>$total_guest_new_male+$total_guest_new_female,
			'total_guest_male'=>$total_guest_new_male,
			'total_guest_female'=>$total_guest_new_female,
			'total_guest_old'=>$total,
			'total_guest_old_male'=>$total_guest_male,
			'total_guest_old_female'=>$total_guest_female
		));
	}
}
?>
