﻿<?php 
	class MonthlyRoomReportDB
    {
		static function get_items($date_from,$date_to,$check,$cond_order)
        {
            $cond = '';
			$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
			$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);			
			$sql = '
			select
				room_status.id,
				room_status.room_id,
				room_status.status,
				room_status.note,
				room_status.house_status,
				reservation.note as reservation_note,
				reservation_room.note as reservation_room_note,
				reservation_room.adult,
				room_status.in_date,
				reservation_room.service_rate,
                reservation_room.arrival_time as r_r_arrival_time_lt,
                reservation_room.departure_time as r_r_departure_time_lt,
                to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as r_r_arrival_time,
                to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as r_r_departure_time,
				reservation_room.confirm,
				reservation_room.tax_rate,
				reservation_room.reduce_balance,
                reservation_room.do_not_move,
                reservation_room.user_do_not_move,
                reservation_room.note_do_not_move,
				to_char(room_status.in_date,\'DD\') as day,
				room_status.reservation_id,
				room_status.reservation_room_id,
				room_status.change_price as price,
                reservation_traveller.special_request,
				CASE
					WHEN reservation_room.status is null or room_status.house_status = \'REPAIR\'
					THEN room_status.house_status
					ELSE reservation_room.status
				END as reservation_status ,
                CASE
					WHEN reservation_room.arrival_time is null or room_status.house_status = \'REPAIR\'
					THEN room_status.start_date
					ELSE reservation_room.arrival_time
				END as arrival_time,
				CASE
					WHEN reservation_room.departure_time is null or room_status.house_status = \'REPAIR\'
					THEN room_status.end_date
					ELSE reservation_room.departure_time
				END as departure_time,
				CASE
					WHEN reservation_room.arrival_time is null or room_status.house_status = \'REPAIR\'
					THEN DATE_TO_UNIX(room_status.start_date)
					ELSE DATE_TO_UNIX(reservation_room.arrival_time)
				END as start_time,
				CASE
					WHEN reservation_room.departure_time is null or room_status.house_status = \'REPAIR\'
					THEN DATE_TO_UNIX(room_status.end_date)
					ELSE DATE_TO_UNIX(reservation_room.departure_time)
				END as end_time,
				(tour.name || \' \' || customer.name || DECODE(traveller.gender,1,\' Mr.\',DECODE(traveller.gender,0,\' Ms.\',\'\')) || traveller.first_name || \' \' || traveller.last_name) as customer,
				traveller.gender,
				to_char(FROM_UNIXTIME(reservation_room.time_in),\'HH\') as time_in_hour,
				to_char(FROM_UNIXTIME(reservation_room.time_out),\'HH\') as time_out_hour,
				CASE
					WHEN (reservation_room.arrival_time is null AND reservation_room.departure_time is null) or room_status.house_status = \'REPAIR\'
					THEN (room_status.end_date - room_status.start_date)+1
					ELSE ( CASE WHEN reservation_room.departure_time>\''.$date_to.'\' THEN (TO_DATE(\''.$date_to.'\') - reservation_room.arrival_time)+1 ELSE reservation_room.departure_time-reservation_room.arrival_time END)
				END as nights,
				tour.name as tour_name,
				reservation.color,
				room.id as room_id
			from
				room_status
				left outer join reservation_room on room_status.reservation_room_id = reservation_room.id 
				left outer join reservation on room_status.reservation_id = reservation.id
				left outer join customer on reservation.customer_id = customer.id
				left outer join tour on reservation.tour_id = tour.id
				left outer join room on room.id = room_status.room_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
				left outer join traveller on traveller.id = reservation_traveller.traveller_id
			where
				(
                    (room_status.house_status=\'REPAIR\') 
                    OR (room_status.house_status=\'HOUSEUSE\') 
                    OR (
                            room_status.reservation_room_id !=0 
                            AND ((reservation_room.status<>\'CANCEL\' and reservation_room.departure_time  != reservation_room.arrival_time) or ( reservation_room.status<>\'CHECKOUT\' and reservation_room.status<>\'CANCEL\' and reservation_room.departure_time  = reservation_room.arrival_time))
                            AND reservation.portal_id = \''.PORTAL_ID.'\'
                        )
                )  
                AND room_status.in_date>=\''.$date_from.'\' 
                and room_status.in_date<=\''.$date_to.'\'
                '.$cond.'
			order by
			     reservation_room.arrival_time, reservation_room.departure_time DESC	--KID : doi thanh cai truoc de sap xep room_status.reservation_room_id ASC
		';
		$room_statuses = DB::fetch_all($sql);
        //System::debug($room_statuses);
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		//$num_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$rooms = DB::fetch_all('select 
                                    room.id, 
                                    0 as lately_checkout,
                                    \'black\' as price_color,
                                    1 as can_book,
                                    room_type_id,
                                    room_level_id, 
                                    room_type.price as room_price, 
                                    room.name, 
                                    0 as reservation_id, \'\' as price,0 as total,
                                    \'\' as note, 
                                    room_type.name as type,
                                    case 
                                        when REGEXP_REPLACE(room.name,\'([0-9])\',\'\') is null
                                        then to_number(room.name)
                                    end as number_room_name,
                                    room_level.brief_name as room_level_name
                                from room 
                                    inner join room_type on room_type_id=room_type.id 
                                    inner join room_level on room_level_id=room_level.id 
                                WHERE room_level.portal_id = \''.PORTAL_ID.'\' '.$cond.' 
                                --order by room.room_level_id desc,room.name
                                '.$cond_order.'
                                ');
                           
		$days = array();
		$room_types = DB::fetch_all('select 
                                        id,price,name,color, 0 as remain 
                                    from room_level 
                                    WHERE room_level.portal_id = \''.PORTAL_ID.'\' order by name');
		$i = 0;
		foreach($rooms as $id=>$room)
		{
			for($j = $time_from; $j<$time_to ; $j+=24*3600)
            {
				if(!isset($rooms[$id]['days']))
                {
					$rooms[$id]['days'][$j] = array();
				}
				$rooms[$id]['days'][$j]['day'] = $j;
                $rooms[$id]['days'][$j]['real_day'] = date('d/m/Y',$j);
			}
			$rooms[$id]['stt'] = $i++;
			$room_types[$room['room_level_id']]['remain'] ++;
		}
		$room_repair = array();
        $temp_array = array();
        $room_tmp = array();
        /** start: KID xu ly sap xep phong chua gan phong **/
        foreach($room_statuses as $key => $status)
        {
            if($status['room_id'] == '' and $status['reservation_room_id'])
           
            {
                $items = MonthlyRoomReportDB::get_random_room_id($status['reservation_room_id']);
                $room_tmp[$key]=$items;
                
            }
        }
        $room_available = MonthlyRoomReportDB::sort_room($room_tmp);
        /** end:KID xu ly sap xep phong chua gan phong **/
        
		foreach($room_statuses as $key => $status)
        {
            
            /** START DAT sua loi THSDP, cac ban ghi cua phong REPAIR, DIRTY room_status cung duoc gan phong tu dong dan den loi **/
            if($status['room_id'] == '' and $status['reservation_room_id'])
            /** END DAT sua loi THSDP, cac ban ghi cua phong REPAIR, DIRTY room_status cung duoc gan phong tu dong dan den loi **/
            {
                //$items = MonthlyRoomReportDB::get_random_room_id($status['reservation_room_id']);//tự động gán cho các phòng trống
                //if($status['reservation_id'] == 179)
                //System::debug($items);
                if(!empty($room_available[$key]))
                {
                    foreach($room_available[$key] as $k => $value)
                    {
                        $lt = true;
                        if(!empty($temp_array))
                        {
                            foreach($temp_array as $l => $t)
                            {
                                /*KID: sau or la de check truong hop cos 2 phong book chua gan.
                                1 phong co ngay tu a->b, con 1 phong tu a->c.
                                neu chi co cai dang truoc thi truong hop nay ca 2 phong deu duoc chon phong
                                nen khong phong nao the hien duoc tren so do phong.
                                */
                                if(
                                    (
                                        $status['reservation_room_id'] != $l and $t['room_id'] == $value['id'] 
                                        and Date_Time::to_time(Date_Time::convert_orc_date_to_date($t['time_in'],'/')) < Date_Time::to_time(Date_Time::convert_orc_date_to_date($status['r_r_departure_time_lt'],'/')) 
                                        and Date_Time::to_time(Date_Time::convert_orc_date_to_date($t['time_out'],'/')) > Date_Time::to_time(Date_Time::convert_orc_date_to_date($status['r_r_arrival_time_lt'],'/'))
                                    ) 
                                    or 
                                    (
                                        $status['reservation_room_id'] != $l and $t['room_id'] == $value['id'] 
                                        and Date_Time::to_time(Date_Time::convert_orc_date_to_date($t['time_in'],'/')) <= Date_Time::to_time(Date_Time::convert_orc_date_to_date($status['r_r_departure_time_lt'],'/')) 
                                        and Date_Time::to_time(Date_Time::convert_orc_date_to_date($t['time_out'],'/')) >= Date_Time::to_time(Date_Time::convert_orc_date_to_date($status['r_r_arrival_time_lt'],'/'))
                                        and Date_Time::to_time(Date_Time::convert_orc_date_to_date($t['time_in'],'/')) == Date_Time::to_time(Date_Time::convert_orc_date_to_date($status['r_r_arrival_time_lt'],'/'))
                                    )
                                )
                                {
                                    $lt = false;
                                    //break;
                                }
                            }
                        }
                        if($lt)
                        {
                            if(!isset($temp_array[$status['reservation_room_id']]))
                            {
                                $temp_array[$status['reservation_room_id']]['room_id'] = $k;
                                $temp_array[$status['reservation_room_id']]['time_in'] = $status['r_r_arrival_time_lt'];
                                $temp_array[$status['reservation_room_id']]['time_out'] = $status['r_r_departure_time_lt'];
                                $status['room_id'] = $k;
                                $room_statuses[$key]['room_id'] = $k;
                                $status['reservation_status'] = 'LT';
                            }
                            else
                            {
                                $status['room_id'] = $temp_array[$status['reservation_room_id']]['room_id'];
                                $room_statuses[$key]['room_id'] = $temp_array[$status['reservation_room_id']]['room_id'];
                                $status['reservation_status'] = 'LT';
                            }  
                            break;  
                        }  
                    }
                }
            }
			if(isset($rooms[$status['room_id']]))
            {
				for($i = $time_from; $i<$time_to; $i+=24*3600)
                {                  
                    //LuanAd sua ngay 25/05/2013, them 1 truong hop rieng cho phong REPAIR
					if(
                        (date('d/m/Y',$i) == Date_Time::convert_orc_date_to_date($status['in_date'],'/')) 
                        && 
                        (
                            ($status['departure_time'] != $status['in_date'] || $status['departure_time'] == $status['arrival_time'])
                            ||
                            (
                                ($status['departure_time'] <= $status['in_date'] || $status['departure_time'] == $status['arrival_time'])
                                &&
                                ($status['reservation_status'] == 'REPAIR' OR $status['reservation_status'] == 'HOUSEUSE')
                            )
                        )
                    )
                    {
                        if(
                            ($status['reservation_status']== 'REPAIR' OR $status['reservation_status']== 'HOUSEUSE')
                            and
                            !DB::fetch('select * 
                                        from room_status 
                                        where room_id = '.$status['room_id'].' 
                                            and in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\'')
                        )
                        {
                            continue;
                        }
                        $status['room_name'] = $rooms[$status['room_id']]['name'];
                        /** start: KID them !isset($rooms[$status['room_id']]['days'][$i]['room_id']
                        de xet truong hop trong cung 1 ngay co nhieu checkin 
                        trong 1 phong thi hien thi trang thai cua lan checkin cuoi**/
                        if(!isset($rooms[$status['room_id']]['days'][$i]['room_id']))
                        {
    						$rooms[$status['room_id']]['days'][$i] = $status;
    						if(($time_from > $rooms[$status['room_id']]['days'][$i]['start_time']) && ($time_to < $rooms[$status['room_id']]['days'][$i]['end_time']))
                            {
    							$rooms[$status['room_id']]['days'][$i]['nights'] = floor(($time_to - $time_from)/84600);	
    						}
                            else if($time_to < $rooms[$status['room_id']]['days'][$i]['end_time'])
                            {
    							$rooms[$status['room_id']]['days'][$i]['nights'] = 	floor(($time_to - $rooms[$status['room_id']]['days'][$i]['start_time'])/84600);
    						}
                            else if($time_from > $rooms[$status['room_id']]['days'][$i]['start_time'])
                            {
    							$rooms[$status['room_id']]['days'][$i]['nights'] = 	floor(($rooms[$status['room_id']]['days'][$i]['end_time'] - $time_from)/84600);	
    						}
    						if($rooms[$status['room_id']]['days'][$i]['nights'] <= 1)
                            {
    						 	$rooms[$status['room_id']]['days'][$i]['cus'] = substr($rooms[$status['room_id']]['days'][$i]['customer'],0,6);
    						}
                            else
                            {
    							$rooms[$status['room_id']]['days'][$i]['cus'] = $rooms[$status['room_id']]['days'][$i]['customer'];
    						}
    						if($rooms[$status['room_id']]['days'][$i]['cus'] == '')
                            {
    							$rooms[$status['room_id']]['days'][$i]['cus'] = $status['customer'];//($status['gender']==1)?'Mr':'Ms' ;
    							$rooms[$status['room_id']]['days'][$i]['customer'] = $status['customer'];	
    						}
    						if($rooms[$status['room_id']]['days'][$i]['nights'] ==0)
                            {
    						}
    						$rooms[$status['room_id']]['days'][$i]['day'] = $i;
    						$rooms[$status['room_id']]['days'][$i]['note'] = $status['customer'].' &#13 Price: '.System::display_number($status['price']);
    						if($status['note'] !='')
                            {
    							$rooms[$status['room_id']]['days'][$i]['note'] .= ' &#13 HK note: '.$status['note'] .' '.$status['reservation_room_note'];	
    						}
    						if($status['reservation_room_note'] !='')
                            {
    							$rooms[$status['room_id']]['days'][$i]['note'].= ' &#13 Room note: '.$status['reservation_room_note'];		
    						}
    						if($status['reservation_note'] !='')
                            {
    							$rooms[$status['room_id']]['days'][$i]['note'].= ' &#13 Group note: '.$status['reservation_note'];		
    						}
                            //Them note khach
                            if($status['reservation_room_id'])
                            {
                                $traveller_note = DB::fetch_all('select 
                                                                    reservation_traveller.*, 
                                                                    traveller.first_name || \' \' || traveller.last_name as full_name 
                                                                from reservation_traveller 
                                                                    inner join traveller on traveller.id = reservation_traveller.traveller_id  
                                                                where reservation_room_id = '.$status['reservation_room_id']);
                                //System::debug($traveller_note);
                                foreach($traveller_note as $traveller=>$note)
                                {
                                    if(trim($note['special_request']))
                                        $rooms[$status['room_id']]['days'][$i]['note'] .= "\n".$note['full_name']." : ".$note['special_request'];
                                }
                            }
                            //Them ngay den ngay di
                            if($status['r_r_arrival_time'])
                                $rooms[$status['room_id']]['days'][$i]['note'] .= "\n".$status['r_r_arrival_time']." - ".$status['r_r_departure_time'];
					   }
                       /** end: KID them !isset($rooms[$status['room_id']]['days'][$i]['room_id']
                        de xet truong hop trong cung 1 ngay co nhieu checkin 
                        trong 1 phong thi hien thi trang thai cua lan checkin cuoi**/
                    }
                    else if(!isset($rooms[$status['room_id']]['days'][$i]))
                    {
						$rooms[$status['room_id']]['days'][$i] = array();
						$rooms[$status['room_id']]['days'][$i]['day'] = $i;
					}
				}
			}
	}
    foreach($rooms as $k => $room)
    {
		if(isset($room_types[$room['room_level_id']]) && $room_types[$room['room_level_id']]['id']== $room['room_level_id'])
        {
			$rooms[$k]['color'] = 	$room_types[$room['room_level_id']]['color'];
			$rooms[$k]['price'] = 	$room_types[$room['room_level_id']]['price'];
		}
			foreach($room['days'] as $d=> $day)
            {
				if(isset($day['nights']))
                {
					if($day['nights'] == 0 && isset($room['days'][$day['day']+86400]) && isset($room['days'][$day['day']+86400]['nights']))
                    {
						if($room['days'][$day['day']+86400]['arrival_time'] == $day['in_date'])
                        {
						}
                        else
                        {
							$rooms[$k]['days'][$d]['nights'] = 1;	
						}
					}
                    else if($day['nights'] == 0)
                    {
						$rooms[$k]['days'][$d]['nights'] = 1;		
					}
				}
			}
		}
		//System::Debug($rooms);
        //exit();
		if($check == false)
        {
			$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
			if(!is_dir($dir_string))
            {
				mkdir($dir_string);	
			}
			$str = " var items_js=";
			$str.= String::array2js($rooms);
			$str.= '';
			$f = fopen($dir_string.'/list_items_room.js','w+');
			fwrite($f,$str);
			fclose($f);
			
		}
        else
        {
			return $rooms;	
		}
	}
	static function get_rooms($check)
    {
        $cond = '';
        if(User::is_admin())
        {
            //$cond .= ' and room.id = 19';
        }
		$rooms = DB::fetch_all('select 
                                    room.id, 
                                    0 as lately_checkout,
                                    \'black\' as price_color,
                                    1 as can_book,
                                    room_type_id,
                                    room_level_id, 
                                    room_type.price as room_price, 
                                    room.name, 
                                    0 as reservation_id, 
                                    \'\' as price,
                                    0 as total,
                                    \'\' as note, 
                                    room_type.name as type,
                                    room_level.brief_name as room_level_name 
                                from room 
                                    inner join room_type on room_type_id=room_type.id 
                                    inner join room_level on room_level_id=room_level.id 
                                WHERE room_level.portal_id = \''.PORTAL_ID.'\' '.$cond.' 
                                order by room.room_level_id,room.name');
		if($check==false)
        {
			$dir_string = 'cache/data/'.str_replace('#','',PORTAL_ID).'';
			if(!is_dir($dir_string))
            {
				mkdir($dir_string);	
			}
			$str = " var rooms_array=";
			$str.= String::array2js($rooms);
			$str.= '';
			$f = fopen($dir_string.'/list_room_array.js','w+');
			fwrite($f,$str);
			fclose($f);
		}
        else
        {
			return $rooms;
		}
	}
    static function get_total_rooms($date_from,$date_to)
    {
    	$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
        //System::debug($date_from);
        //System::debug(Date_Time::convert_orc_date_to_date($date_from,'/'));
        $total_room = array();
        $j=0;
        $sql = 'select count(room.id) as id from room inner join room_level on room.room_level_id = room_level.id where room.portal_id = \''.PORTAL_ID.'\' and room_level.is_virtual = 0';
        $num_of_room = DB::fetch($sql,'id');
        for($i = $time_from; $i<$time_to; $i+=24*3600)
        {         
            $t = Date_Time::convert_time_to_ora_date($i);
            $sql = 'select 
                        count(room_status.id) as id
                    from room_status 
                        inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                        inner join reservation on reservation.id = reservation_room.reservation_id
                        left join room_level on reservation_room.room_level_id = room_level.id 
                    where  room_status.in_date=\''.$t.'\'
                        and(room_status.status = \'OCCUPIED\' 
                        or room_status.status = \'BOOKED\') 
                        and (in_date!=departure_time or (arrival_time=departure_time and change_room_to_rr is null))
                         and nvl(room_level.is_virtual,0) = 0';
            $total_room[$j] = DB::fetch($sql);
            //System::debug($sql);
            $sql = 'select 
                        count(room_status.id) as id
                    from 
                        room_status 
                    where  
                        room_status.in_date=\''.$t.'\'
                        and (room_status.house_status = \'REPAIR\' or room_status.house_status = \'HOUSEUSE\')';
            $total_room[$j]['repair_houseuse'] = DB::fetch($sql,'id');
            $total_room[$j]['num_of_room'] = $num_of_room;
            $total_room[$j]['date'] = $i;
            $j++;
        }
        return $total_room;    
    }
    // ham tra ve list cac room_id co the nhet vao 1 reservation_room_id
    // ham tra ve tat ca cac room_id con trong trong 1 ngay cua mot hang phong
    static function get_random_room_id($id)
    {
        $rr_info = DB::fetch('select id,room_level_id, time_in, time_out from reservation_room where id = '.$id);
        $room_level_id = $rr_info['room_level_id'];
        // get tat ca cac room_id thuoc hang phong
        $sql = 'select 
                    room.id
                from 
                    room
                    inner join room_level on room.room_level_id = room_level.id
                where 
                    room.room_level_id = '.$room_level_id.'
                ';
        $room_of_level = DB::fetch_all($sql);
        $in_date = '';
        for($i = $rr_info['time_in']; $i < $rr_info['time_out']; $i += 24*60*60)
        {
            $date = '\''.Date_Time::convert_time_to_ora_date($i).'\',';
            $in_date .= $date;
        }
        $in_date .= '\''.Date_Time::convert_time_to_ora_date($rr_info['time_in']).'\'';
        //if($id == 306)
        //echo $in_date.'<br>';
        // get tat ca cac room_id thuoc hang phong co trong bang room_status vao in_date
        $sql = 'select
                    room_status.id,
                    room_status.room_id
                from
                    room_status
                    inner join room on room.id = room_status.room_id
                    left join reservation_room on reservation_room.id = room_status.reservation_room_id
                where
                    room.room_level_id = '.$room_level_id.'
                    and room_status.in_date in ('.$in_date.')
                    and ((room_status.reservation_id != 0 and (room_status.in_date < reservation_room.departure_time) and room_status.change_price is not null) or (room_status.reservation_id = 0 and room_status.house_status != \'DIRTY\'))
                    and room_status.status != \'CANCEL\'
                ';
        $used_rooms = DB::fetch_all($sql);
        foreach($used_rooms as $key => $value)
        {
            if(isset($room_of_level[$value['room_id']]))
            {
                unset($room_of_level[$value['room_id']]);
            }
        }
        return $room_of_level;  
    }
    /** start:KID ham phong chua gan co so phong phu hop nho nhat ( giơ chua dung nhung co luc se dung) **/
    function find_min(&$room_tmp)
    {
        $i = 0;
        foreach($room_tmp as $key=>$value)
        {
            if($i==0)
            {
                $min = count($value);
            }
            $i++;
            if($min>count($value))
            {
                $min=count($value);
            }
        }
        return $min;
    }
    /** end:start:KID ham phong chua gan co so phong phu hop nho nhat ( giơ chua dung nhung co luc se dung)**/
    
    /** start:KID ham sap xep phong chua gan co so phong phu hop tu be den lon ( giơ chua dung nhung co luc se dung) **/
    function sort_room_sign($room_tmp)
    {
        while(!empty($room_tmp))
        {
            $min = MonthlyRoomReportDB::find_min($room_tmp);
            foreach($room_tmp as $k=>$v)
            {
                if($min == count($v))
                {
                    $temp[$k]=$v;
                    unset($room_tmp[$k]);
                }
            }
            
        }
        return $temp;
    }
    /** end:KID ham sap xep phong chua gan co so phong phu hop tu be den lon ( giơ chua dung nhung co luc se dung) **/
    
    /** start:KID ham tim phong trong co so lan xuat hien it nhat trong mang **/	
    function find_min_room_avalable($room_tmp)
    {
        $room_num = array();
        foreach($room_tmp as $key=>$value)
        {
            if($value)
            {
                foreach($value as $k => $v)
                {
                    if(isset($room_num[$k]))
                        $room_num[$k]['num']++;
                    else
                    {
                        $room_num[$k] = array('id'=>$k, 'num'=>1);
                    }
                }
            }
            
        }
        $i=0;
        if(!empty($room_num))
        {
            foreach($room_num as $ke=>$ve)
            {
                if($i==0)
                {
                    $min_room = $ve['num'];
                }
                $i++;
                if($min_room>=$ve['num'])
                {
                    $min_room=$ve['num'];
                    $room_id_min = $ke;
                }
            }
            return $room_id_min;
        }
    }
    
    /** end:KID ham tim phong trong co so lan xuat hien it nhat trong mang **/
    
    /** start:KID ham tim sap xep phong trong tu be den lon phu hop voi phong book **/
    function sort_room(&$room_tmp)
    {
        $rooms_avlb = array();
        while(!empty($room_tmp))
        {
            $room_min = MonthlyRoomReportDB::find_min_room_avalable($room_tmp);
            foreach($room_tmp as $key=>$value)
            {   
                foreach($value as $k=>$v)
                { 
                    
                    if($k==$room_min)
                    {
                        $rooms_avlb[$key][$k]['id']=$v['id'];
                        unset($room_tmp[$key][$k]);
                    }  
                }
                if(empty($value))
                {
                    unset($room_tmp[$key]);
                }
                
            }    
        }
        return  $rooms_avlb;       
    }
    /** end:KID ham tim sap xep phong trong tu be den lon phu hop voi phong book **/	
}
?>
