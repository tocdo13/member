<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function get_floor()
    {
        if($this->method == 'GET' || $this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $floors=DB::fetch_all('
                                SELECT 
                                    room.floor as id,
                                    room.floor,
                                    room.coordinates 
                                FROM 
                                    room
                                    INNER JOIN room_level ON room.room_level_id=room_level.id
                                WHERE 
                                    room_level.is_virtual=0 
                                GROUP BY 
                                    room.id,
                                    room.floor,
                                    room.coordinates 
                                ORDER BY 
                                    room.id asc'
                );
                //System::debug($floors);
                $items = array();
                $arr = array(
                            '1113021993' => array(
                                            'id' => 1113021993,
                                            'floor' => Portal::language('all'),
                                            'coordinates' => "1113021993"
                            )
                );
                
                $log_out = array(
                            '111302' => array(
                                            'id' => 111302,
                                            'floor' => Portal::language('log_out'),
                                            'coordinates' => "111302"
                            )
                );
                $arr += $floors;
                $arr += $log_out;
                //System::debug($arr);
                foreach($arr as $key => $value)
                {
                    array_push($items, $value);
                }
                if(Url::get('type') == 'IOS')
                {
                    $this->response(200, json_encode($arr)); // AUTH
                }else{
                    $this->response(200, json_encode($items)); // AUTH
                }   
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }
    }
    
    function house_status()
    {
        if($this->method == 'GET' || $this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $floor_id = Url::get('floor_id');
                //System::debug($floor_id);
                $rooms = $this->get_rooms('#'.Url::get('portal_id'),$floor_id);
                $room_statuses = $this->get_room_statuses(date('d/m/Y', time()), '#'.Url::get('portal_id'),$floor_id);
                
                $room_id = 0;
                $status_arr = array();
                
                $laundry_net_price = NET_PRICE_LAUNDRY;
                $laundry_tax_rate = LAUNDRY_TAX_RATE;
                $laundry_service_rate = LAUNDRY_SERVICE_CHARGE; 
                
                $minibar_net_price = NET_PRICE_MINIBAR;
                $minibar_tax_rate = MINIBAR_TAX_RATE;
                $minibar_service_rate = MINIBAR_SERVICE_CHARGE;
                
                $equipment_tax_rate = 0;
                foreach($room_statuses as $key=>$room_status)
        		{
        			if(isset($rooms[$room_status['room_id']]))
        			{	
        				if((isset($room_status['hk_note']) and $room_status['hk_note'] and ($room_status['status']!='CHECKOUT' or ($room_status['status']=='CHECKOUT' and $room_status['house_status']=='REPAIR')))){
        					$rooms[$room_status['room_id']]['hk_note'] = ''.$room_status['hk_note']."";
        				}
        				$status_arr[$room_status['room_id']]['hk_note'] = $rooms[$room_status['room_id']]['hk_note'];
        				if(isset($room_overdues[$room_status['room_id']]) and $room_status['in_date'] == Date_Time::to_orc_date(date('d/m/Y', time()))){
        					//unset($room_statuses[$room_status['id']]);
        				}
        				{
        					$status_arr[$room_status['room_id']] = $room_status;
        					if($room_status['room_id'] !=$room_id)
        					{
        						$room_id = $room_status['room_id'];
        						unset($room_status['room_id']);	
        						if(isset($room_status['start_date']) && $room_status['start_date']!='')
                                {
        							$rooms[$room_id]['time_in'] = ($room_status['house_status']=='REPAIR')?date('d/m', Date_Time::to_time($room_status['start_date'])):date('d/m', Date_Time::to_time($room_status['time_in']));
        							$rooms[$room_id]['time_out'] = ($room_status['house_status']=='REPAIR')?date('d/m', (Date_Time::to_time($room_status['end_date'])+86399)):date('d/m', Date_Time::to_time($room_status['time_out']));
        						}
        						switch($room_status['status'])
                                {
        							case 'BOOKED':
        								$rooms[$room_id]['status'] = 'BOOKED';
        								break;
        							case 'OVERDUE_BOOKED':
                                        $rooms[$room_id]['status'] = 'OVERDUE_BOOKED';
                                        break;
                                    case 'DAYUSED':
                                        $rooms[$room_id]['status'] = 'DAYUSED';
        								break;		
                                    case 'OCCUPIED':
        								$rooms[$room_id]['status'] = 'OCCUPIED';
        								if(Date_Time::to_time($room_status['arrival_time']) == Date_Time::to_time(date('d/m/Y', time())))
                                        {
        									$rooms[$room_id]['status'] = 'DAYUSED';
                                            if($room_status['is_virtual'] == 0)
                                            {
                                                if($room_status['change_room_from_rr']!='')
                                                {
                                                    $rooms[$room_id]['status'] = 'CHANGE_ROOM';
                                                }
                                            }
        								}
                                        if(Date_Time::to_time($room_status['departure_time']) == Date_Time::to_time(date('d/m/Y', time())))
                                        {
        									$rooms[$room_id]['status'] = 'EXPECTED_CHECKOUT';
        								}
        								break;
        							case 'OVERDUE':
        								$rooms[$room_id]['status'] = 'OVERDUE';
        								break;
        							default:
        								$rooms[$room_id]['status'] = $room_status['status'];
        								break;
        						}
        						$room_status['status'] = $rooms[$room_id]['status'];
                                $rooms[$room_id]['laundry_net_price'] = "$laundry_net_price";
                                $rooms[$room_id]['laundry_tax_rate'] = "$laundry_tax_rate";
                                $rooms[$room_id]['laundry_service_rate'] = "$laundry_service_rate";
                                $rooms[$room_id]['minibar_net_price'] = "$minibar_net_price";
                                $rooms[$room_id]['minibar_tax_rate'] = "$minibar_tax_rate";
                                $rooms[$room_id]['minibar_service_rate'] = "$minibar_service_rate";
                                $rooms[$room_id]['equipment_tax_rate'] = "$equipment_tax_rate";
        						$rooms[$room_id]['house_status'] = $room_status['house_status'];
        					}
        					$room_status['time_in'] = $room_status['time_in']?' ('.date('H:i\'',$room_status['time_in']).')':'';
        					$room_status['time_out'] = $room_status['time_out']?' ('.date('H:i\'',$room_status['time_out']).')':'';
        					if(isset($room_status['start_date']) && $room_status['start_date']!='')
                            {
        						$rooms[$room_id]['time_in'] = ($room_status['house_status']=='REPAIR')?date('d/m', Date_Time::to_time($room_status['start_date'])):date('d/m', Date_Time::to_time($room_status['time_in']));
       							$rooms[$room_id]['time_out'] = ($room_status['house_status']=='REPAIR')?date('d/m', (Date_Time::to_time($room_status['end_date'])+86399)):date('d/m', Date_Time::to_time($room_status['time_out']));
        					}
                            if(isset($room_status['reservation_status']) && ($room_status['reservation_status'] == 'BOOKED' || $room_status['reservation_status'] == 'CHECKIN'))
                            {
                                $rooms[$room_id]['check_status'] = 1;
                            }
                            if(isset($room_status['reservation_status']) && ($room_status['reservation_status'] != 'CHECKOUT'))
                            {
                                $rooms[$room_id]['reservations'][$room_status['id']] = $room_status;
                            }
        				}
        			}
        		}
                //System::debug($rooms); 
                $floors = array();
        		$last = false;
        		$i=0;
                //System::debug($rooms);
                foreach($rooms as $key => $value)
                {
                    if(Url::get('type') == 1)
                    {
                        if($value['check_status'] == 0)
                        {
                            unset($rooms[$key]);
                        }
                    }else if(Url::get('type') == 0)
                    {
                        if($value['check_status'] == 1)
                        {
                            unset($rooms[$key]);
                        }
                    }
                }
                
        		foreach($rooms as $key => $room)
        		{
                    if(isset($room['reservations']))
                    {
        				$rooms[$key]['old_reservations'] = $room['reservations'];
        				$room['old_reservations'] = $room['reservations'];
        				$arr = $room['reservations'];
        				$rooms[$key]['reservations'] = array_reverse($room['reservations']);
        				$room['old_reservations'] = array_reverse($arr);
        			}else
                    {  
        				$room['reservations'] = array();
        				$room['old_reservations'] = array();
        			}
        			$i++;
                    if(!isset($floors[$room['floor']]))
                    {
                        $floors[$room['floor']]= array( 'name'=>$room['floor'],
  						                                'rooms'=>array()
      					                              );
                    }
        			$floors[$room['floor']]['rooms'][$i] = $room;
        		}
                //System::debug($floors);
                $items = array();
                foreach($floors as $key => $value)
                {
                    foreach($value['rooms'] as $k => $v)
                    {
                        $v['house_status'] = $v['house_status']?$v['house_status']:"";
                        $v['hk_note'] = $v['hk_note']?$v['hk_note']:"";
                        $v['note'] = $v['note']?$v['hk_note']:"";
                        array_push($items,$v);                        
                    }
                }  
                 
                $this->response(200, json_encode($items));
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
        
        /*if($this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $floor_id = Url::get('floor_id');
                //System::debug($floor_id);
                $rooms = $this->get_rooms('#'.Url::get('portal_id'),$floor_id);
                $room_statuses = $this->get_room_statuses(date('d/m/Y', time()), '#'.Url::get('portal_id'),$floor_id);
                
                $room_id = 0;
                $status_arr = array();
                
                $laundry_net_price = NET_PRICE_LAUNDRY;
                $laundry_tax_rate = LAUNDRY_TAX_RATE;
                $laundry_service_rate = LAUNDRY_SERVICE_CHARGE; 
                
                $minibar_net_price = NET_PRICE_MINIBAR;
                $minibar_tax_rate = MINIBAR_TAX_RATE;
                $minibar_service_rate = MINIBAR_SERVICE_CHARGE;
                
                $equipment_tax_rate = 0;
                foreach($room_statuses as $key=>$room_status)
        		{
        			if(isset($rooms[$room_status['room_id']]))
        			{	
        				if((isset($room_status['hk_note']) and $room_status['hk_note'] and ($room_status['status']!='CHECKOUT' or ($room_status['status']=='CHECKOUT' and $room_status['house_status']=='REPAIR')))){
        					$rooms[$room_status['room_id']]['hk_note'] = ''.$room_status['hk_note']."";
        				}
        				$status_arr[$room_status['room_id']]['hk_note'] = $rooms[$room_status['room_id']]['hk_note'];
        				if(isset($room_overdues[$room_status['room_id']]) and $room_status['in_date'] == Date_Time::to_orc_date(date('d/m/Y', time()))){
        					//unset($room_statuses[$room_status['id']]);
        				}
        				{
        					$status_arr[$room_status['room_id']] = $room_status;
        					if($room_status['room_id'] !=$room_id)
        					{
        						$room_id = $room_status['room_id'];
        						unset($room_status['room_id']);	
        						if(isset($room_status['start_date']) && $room_status['start_date']!='')
                                {
        							$rooms[$room_id]['time_in'] = ($room_status['house_status']=='REPAIR')?date('d/m', Date_Time::to_time($room_status['start_date'])):date('d/m', Date_Time::to_time($room_status['time_in']));
        							$rooms[$room_id]['time_out'] = ($room_status['house_status']=='REPAIR')?date('d/m', (Date_Time::to_time($room_status['end_date'])+86399)):date('d/m', Date_Time::to_time($room_status['time_out']));
        						}
        						switch($room_status['status'])
                                {
        							case 'BOOKED':
        								$rooms[$room_id]['status'] = 'BOOKED';
        								break;
        							case 'OVERDUE_BOOKED':
                                        $rooms[$room_id]['status'] = 'OVERDUE_BOOKED';
                                        break;
                                    case 'DAYUSED':
                                        $rooms[$room_id]['status'] = 'DAYUSED';
        								break;		
                                    case 'OCCUPIED':
        								$rooms[$room_id]['status'] = 'OCCUPIED';
        								if(Date_Time::to_time($room_status['arrival_time']) == Date_Time::to_time(date('d/m/Y', time())))
                                        {
        									$rooms[$room_id]['status'] = 'DAYUSED';
                                            if($room_status['is_virtual'] == 0)
                                            {
                                                if($room_status['change_room_from_rr']!='')
                                                {
                                                    $rooms[$room_id]['status'] = 'CHANGE_ROOM';
                                                }
                                            }
        								}
                                        if(Date_Time::to_time($room_status['departure_time']) == Date_Time::to_time(date('d/m/Y', time())))
                                        {
        									$rooms[$room_id]['status'] = 'EXPECTED_CHECKOUT';
        								}
        								break;
        							case 'OVERDUE':
        								$rooms[$room_id]['status'] = 'OVERDUE';
        								break;
        							default:
        								$rooms[$room_id]['status'] = $room_status['status'];
        								break;
        						}
        						$room_status['status'] = $rooms[$room_id]['status'];
                                $rooms[$room_id]['laundry_net_price'] = "$laundry_net_price";
                                $rooms[$room_id]['laundry_tax_rate'] = "$laundry_tax_rate";
                                $rooms[$room_id]['laundry_service_rate'] = "$laundry_service_rate";
                                $rooms[$room_id]['minibar_net_price'] = "$minibar_net_price";
                                $rooms[$room_id]['minibar_tax_rate'] = "$minibar_tax_rate";
                                $rooms[$room_id]['minibar_service_rate'] = "$minibar_service_rate";
                                $rooms[$room_id]['equipment_tax_rate'] = "$equipment_tax_rate";
        						$rooms[$room_id]['house_status'] = $room_status['house_status'];
        					}
        					$room_status['time_in'] = $room_status['time_in']?' ('.date('H:i\'',$room_status['time_in']).')':'';
        					$room_status['time_out'] = $room_status['time_out']?' ('.date('H:i\'',$room_status['time_out']).')':'';
        					if(isset($room_status['start_date']) && $room_status['start_date']!='')
                            {
        						$rooms[$room_id]['time_in'] = ($room_status['house_status']=='REPAIR')?date('d/m', Date_Time::to_time($room_status['start_date'])):date('d/m', Date_Time::to_time($room_status['time_in']));
       							$rooms[$room_id]['time_out'] = ($room_status['house_status']=='REPAIR')?date('d/m', (Date_Time::to_time($room_status['end_date'])+86399)):date('d/m', Date_Time::to_time($room_status['time_out']));
        					}
                            if(isset($room_status['reservation_status']) && ($room_status['reservation_status'] == 'BOOKED' || $room_status['reservation_status'] == 'CHECKIN'))
                            {
                                $rooms[$room_id]['check_status'] = 1;
                            }
                            if(isset($room_status['reservation_status']) && ($room_status['reservation_status'] != 'CHECKOUT'))
                            {
                                $rooms[$room_id]['reservations'][$room_status['id']] = $room_status;
                            }
        				}
        			}
        		}
                //System::debug($rooms); 
                $floors = array();
        		$last = false;
        		$i=0;
                //System::debug($rooms);
                foreach($rooms as $key => $value)
                {
                    if(Url::get('type') == 1)
                    {
                        if($value['check_status'] == 0)
                        {
                            unset($rooms[$key]);
                        }
                    }else if(Url::get('type') == 0)
                    {
                        if($value['check_status'] == 1)
                        {
                            unset($rooms[$key]);
                        }
                    }
                }
                
        		foreach($rooms as $key => $room)
        		{
                    if(isset($room['reservations']))
                    {
        				$rooms[$key]['old_reservations'] = $room['reservations'];
        				$room['old_reservations'] = $room['reservations'];
        				$arr = $room['reservations'];
        				$rooms[$key]['reservations'] = array_reverse($room['reservations']);
        				$room['old_reservations'] = array_reverse($arr);
        			}else
                    {  
        				$room['reservations'] = array();
        				$room['old_reservations'] = array();
        			}
        			$i++;
                    if(!isset($floors[$room['floor']]))
                    {
                        $floors[$room['floor']]= array( 'name'=>$room['floor'],
  						                                'rooms'=>array()
      					                              );
                    }
        			$floors[$room['floor']]['rooms'][$i] = $room;
        		}
                //System::debug($floors);
                $items = array();
                foreach($floors as $key => $value)
                {
                    foreach($value['rooms'] as $k => $v)
                    {
                        $v['house_status'] = $v['house_status']?$v['house_status']:"";
                        $v['hk_note'] = $v['hk_note']?$v['hk_note']:"";
                        $v['note'] = $v['note']?$v['hk_note']:"";
                        $items[$k] = $v;                        
                    }
                }  
                 
                $this->response(200, json_encode($items));
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }*/
    }
    
    function get_rooms($portal_id,$floor_id)
	{
        $cond_f = '';
        if($floor_id != '1113021993')
        {
            $cond_f .= ' AND room.coordinates=\''.$floor_id.'\' ';
        }
		$sql = '
			SELECT 
				DISTINCT 
                room.id,
				room.name,
				room.floor || \' - R.\' ||room.name as name,
				room.floor,
				room_level.price,
				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
				0 AS overdue_reservation_id,
				\'\' as house_status,
				\'AVAILABLE\' as status,
                0 as background_color,
				\'\' AS note,
				\'\' AS hk_note,
				minibar.id as minibar_id,
                \'0\' as laundry_net_price,
                \'0\' as laundry_tax_rate,
                \'0\' as laundry_service_rate,
                \'0\' as minibar_net_price,
                \'0\' as minibar_tax_rate,
                \'0\' as minibar_service_rate,
                \'0\' as equipment_tax_rate,
				room.position,
				room.room_level_id,
				room.room_type_id,
				room_level.is_virtual,
				room_level.brief_name as room_level_name,
                room_type.id as room_type,
                0 as check_status
			FROM
				room
				INNER JOIN room_level ON room_level.id = room.room_level_id
				INNER JOIN room_type ON room_type.id = room_type_id 
				LEFT JOIN minibar ON room.id = minibar.room_id 
			WHERE
				room.portal_id = \''.$portal_id.'\'
                '.$cond_f.'
			ORDER BY
				room.floor, 
                room.name, 
				room.position
                
		';
		$rooms = DB::fetch_all($sql);
		foreach($rooms as $key=>$room)
		{
			$rooms[$key]['price'] = System::display_number($room['price']);
		}
		return $rooms;
	}
    
    function get_room_statuses($in_date, $portal_id, $floor_id)
	{
	    $current_time = $to_date = Date_Time::to_time($in_date);
		$this->year = date('Y',$current_time);
		$this->month = date('m',$current_time); 
		$this->day = date('d',$current_time);
		$this->end_year = date('Y',$to_date);
		$this->end_month = date('m',$to_date);
		$this->end_day = date('d',($to_date));
        
        $cond_f = '';
        if($floor_id != '1113021993')
        {
            $cond_f .= ' AND room.coordinates=\''.$floor_id.'\' ';
        }
        
		$sql = '
            SELECT 
                DISTINCT 
    			room_status.id,
    			DECODE(reservation.customer_id,0,\'\',concat(customer.name,concat(\' \',customer.address))) as company_name,
    			room_status.room_id,
    			room_status.reservation_id,
    			CASE 
    				WHEN 
    					reservation_room.status = \'CHECKOUT\'
    				THEN 
    					CASE
    						WHEN
    							reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
    						THEN
    							\'CHECKOUT\'
    						ELSE
    							room_status.status
    					END
    				ELSE
    					CASE
    						WHEN
    							reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' 
                                AND reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
    						THEN
    							\'DAYUSED\'
    						ELSE
    							CASE
    								WHEN
    									reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' AND reservation_room.time_out < '.time().'
                                        AND reservation_room.arrival_time != \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
    								THEN
    									\'OVERDUE\'
    								ELSE
                                        CASE
                                            WHEN 
                                                reservation_room.status = \'CHECKIN\' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\' AND reservation_room.time_out > '.time().'
                                                AND reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                                            THEN
                                                \'EXPECTED_CHECKOUT\'
                                            ELSE
            									CASE
                    								WHEN
                    									reservation_room.status = \'BOOKED\'
                                                        AND 1 = '.DISPLAY_BOOK_OVERDUE.' 
                                                        AND (reservation_room.time_in + '.(TIME_BOOK_OVERDUE * 60).') <= '.time().'
                                                    THEN
                    									\'OVERDUE_BOOKED\'
                    								ELSE
                    									room_status.status
                                                 END
                                            END
    							END
    					END
    			END status,
    			house_status,
    			reservation.id as reservation_code,
                reservation_room.id as reservation_room_id,
    			reservation_room.status as reservation_status,
    			reservation_room.time_in,
                reservation_room.time_out,
    			reservation_room.departure_time,
    			reservation_room.arrival_time,
                room_status.in_date,
    			DECODE(reservation_room.status,\'CHECKIN\',1,(DECODE(reservation_room.status,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)))) AS status_order,
                room_level.is_virtual,
                room_type.id as room_type,
                reservation_room.change_room_from_rr,
                reservation_room.change_room_to_rr,
                reservation_room.old_arrival_time
    		FROM
    			room_status
    			left outer join reservation_room on reservation_room.id = room_status.reservation_room_id
                left outer join room_level on room_level.id = reservation_room.room_level_id
    			left outer join reservation on reservation.id = room_status.reservation_id
    			left outer join traveller on traveller.id = reservation_room.traveller_id
    			left outer join customer on reservation.customer_id = customer.id
    			left outer join tour on tour.id = reservation.tour_id
                left outer join room on room.room_level_id = room_level.id
                left outer join room_type on room.room_type_id = room_type.id
    		WHERE 
    			reservation.portal_id = \''.PORTAL_ID.'\'
    			and room_status.status <> \'CANCEL\' and room_status.status <> \'NOSHOW\' AND reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'NOSHOW\' AND room_status.status <> \'AVAILABLE\'
    			AND room_status.in_date = \''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
                '.$cond_f.'
            ORDER BY 
                status_order DESC,ABS(reservation_room.time_in - '.time().') DESC
		';
		$room_statuses = DB::fetch_all($sql);

		$sql = '
			SELECT
				room_status.*,\'\' as full_name,
                room_status.note as hk_note
				,TO_CHAR(room_status.start_date,\'dd/mm/yyyy\') as start_date
				,TO_CHAR(room_status.end_date,\'dd/mm/yyyy\') as end_date
			FROM
				room_status
				INNER JOIN room ON room.id = room_status.room_id
				LEFT OUTER JOIN reservation ON reservation.id = room_status.reservation_id
				LEFT OUTER JOIN reservation_room ON room_status.reservation_room_id = reservation_room.id
			WHERE
				room.portal_id = \''.PORTAL_ID.'\'
                AND (
                        room_status.status = \'AVAILABLE\' 
                        OR reservation_room.status = \'CHECKOUT\' 
                        OR reservation_room.status = \'CANCEL\' 
                        OR reservation_room.status = \'NOSHOW\'
                ) 
                AND (room_status.note is not null OR room_status.house_status is not null)
				AND room_status.in_date>=\''.Date_Time::to_orc_date(($this->day.'/'.$this->month.'/'.$this->year)).'\'
				AND room_status.in_date<=\''.Date_Time::to_orc_date(($this->end_day.'/'.$this->end_month.'/'.$this->end_year)).'\'
                '.$cond_f.'
			ORDER BY 
				room_status.id DESC
		';
		$available_rooms = DB::fetch_all($sql);
		$house_status_arr = array();
		foreach($available_rooms as $k=>$v)
        {
            $available_rooms[$k]['time_in'] = '';
			$available_rooms[$k]['time_out'] = '';
            $available_rooms[$k]['arrival_time'] = '';
			$available_rooms[$k]['departure_time'] = '';
			if($v['house_status'])
            {
                if(isset($house_status_arr[$v['room_id']]))
                {
                    if($house_status_arr[$v['room_id']]=='DIRTY' && $v['house_status']=='CLEAN')
                    {
                        $house_status_arr[$v['room_id']] = 'DIRTY';
                    }
                    else
                    {
                        $house_status_arr[$v['room_id']] = $v['house_status'];
                    }
                }else
                {
                    $house_status_arr[$v['room_id']] = $v['house_status'];
                } 
			}
		}
        
		foreach($room_statuses as $id=>$room_status)
		{
			$k_ = '';
			foreach($available_rooms as $k=>$v)
            {
				if($v['room_id'] == $room_status['room_id'] and $v['in_date'] == $room_status['in_date'])
                {
                    $room_statuses[$id]['hk_note'] = $v['note']?$v['note']:'';
					$room_statuses[$id]['house_status'] = $v['house_status']?$v['house_status']:'';
                    if($v['house_status']=='REPAIR')
                    {
    					$room_statuses[$id]['time_in'] = Date_Time::to_time($v['start_date']);
                        $room_statuses[$id]['time_out'] = Date_Time::to_time($v['end_date']);
                        $room_statuses[$id]['start_date'] = $v['start_date'];
                        $room_statuses[$id]['end_date'] = $v['end_date'];
                    }
                    unset($available_rooms[$k]);
				}
			}
			if(isset($house_status_arr[$room_status['room_id']]))
            {
				$room_statuses[$id]['house_status'] = $house_status_arr[$room_status['room_id']];
			}
			if(isset($available_rooms[$k_]))
            {
				unset($available_rooms[$k_]);
			}
            $room_statuses[$id]['arrival_time'] = $room_status['arrival_time']?Date_Time::convert_orc_date_to_date($room_status['arrival_time'],'/'):'';
			$room_statuses[$id]['departure_time'] = $room_status['departure_time']?Date_Time::convert_orc_date_to_date($room_status['departure_time'],'/'):'';		
		}
		$room_statuses += $available_rooms;
        
		return $room_statuses;
	}
}   
$api = new api();
?>