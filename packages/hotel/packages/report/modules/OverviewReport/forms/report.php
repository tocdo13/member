<?php
class OverviewReportForm extends Form{
	function OverviewReportForm(){
		Form::Form('OverviewReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
	}
	function draw(){
		 $this->map = array();
		 $from_date = '1'.'/'.date('m').'/'.date('Y');
		 $end_date = (Url::get('day')) ? Url::get('day'):date('d/m/Y');
		 //--------------tinh trong ngay -------------------------
		 $from_date = $end_date;
		 $this->map = $this->get_status_rooms($from_date,$end_date );
		 $this->map['traveller'] = $this->get_traveller($from_date,$end_date );
		 $total_room = 0;
		 foreach($this->map['traveller'] as $key =>$value){
			 if($value['arrival_time'] == $value['departure_time']){
				 $this->map['traveller'][$key]['change_price'] = $value['pice'];
			 }
			 $total_room += $this->map['traveller'][$key]['change_price'];
		 }
		 $this->map['total_room'] = $total_room;
		 $this->map['booking_revenue'] = $this->get_amount_booked($from_date,$end_date )?$this->get_amount_booked($from_date,$end_date ):0;
		 $this->map['no_show_revenue'] = $this->no_show_revenue($from_date,$end_date )?$this->no_show_revenue($from_date,$end_date ):0;
		 $this->map['minibar_revenue'] = $this->get_minibar_revenue($from_date,$end_date )?$this->get_minibar_revenue($from_date,$end_date ):0;
		 $this->map['laundry_revenue'] = $this->laudry_revenue($from_date,$end_date )?$this->laudry_revenue($from_date,$end_date ):0;
		 $this->map['compensation_revenue'] = $this->compensation_revenue($from_date,$end_date )?$this->compensation_revenue($from_date,$end_date ):0;
		 $this->map['restaurant_revenue'] = $this->restaurant_revenue($from_date,$end_date )?$this->restaurant_revenue($from_date,$end_date ):0;
		 $this->map['karaoke_revenue'] = $this->karaoke_revenue($from_date,$end_date )?$this->karaoke_revenue($from_date,$end_date ):0;
		 $this->map['massage_revenue'] = $this->massage_revenue($from_date,$end_date )?$this->massage_revenue($from_date,$end_date ):0;
		 $this->map['tennis_revenue'] = $this->tennis_revenue($from_date,$end_date )?$this->tennis_revenue($from_date,$end_date ):0;
		 $this->map['swimming_pool_revenue'] = $this->swimming_pool_revenue($from_date,$end_date )?$this->swimming_pool_revenue($from_date,$end_date ):0;
		 $this->map['extra_service_revenue'] = $this->extra_service_revenue($from_date,$end_date )?$this->extra_service_revenue($from_date,$end_date ):0;
		 $items = array();
		 if($this->map['total_room']!=0){
			 $items[0]['id']= 0;
			 $items[0]['name'] ='room revenue';
			 $items[0]['total']=$this->map['total_room'];
		 }
		 if($this->map['booking_revenue']!=0){
			 $items[1]['id']= 0;
			 $items[1]['name'] ='booking revenue';
			 $items[1]['total']= $this->map['booking_revenue'];
		 }
		 if($this->map['no_show_revenue']!=0){
			 $items[2]['id']= 0;
			 $items[2]['name'] ='no show revenue';
			 $items[2]['total']= $this->map['no_show_revenue'];
		 }
		 if($this->map['minibar_revenue']!=0){
			 $items[3]['id']= 0;
			 $items[3]['name'] ='minibar revenue';
			 $items[3]['total']= $this->map['minibar_revenue'];
		 }
		 if($this->map['laundry_revenue']!=0){
			 $items[4]['id']= 0;
			 $items[4]['name'] ='laundry revenue';
			 $items[4]['total']= $this->map['laundry_revenue'];
		 }
		 if($this->map['compensation_revenue']!=0){
			 $items[5]['id']= 0;
			 $items[5]['name'] ='compensation revenue';
			 $items[5]['total']= $this->map['compensation_revenue'];
		 }
		 if($this->map['restaurant_revenue']!=0){
			 $items[6]['id']= 0;
			 $items[6]['name'] ='restaurant revenue';
			 $items[6]['total']= $this->map['restaurant_revenue'];
		 }
		 if($this->map['karaoke_revenue']!=0){
			 $items[7]['id']= 0;
			 $items[7]['name'] ='karaoke revenue';
			 $items[7]['total']= $this->map['karaoke_revenue'];
		 }
		 if($this->map['massage_revenue']!=0){
			 $items[8]['id']= 0;
			 $items[8]['name'] ='massage revenue';
			 $items[8]['total']= $this->map['massage_revenue'];
		 }
		 if($this->map['tennis_revenue']!=0){
			 $items[9]['id']= 0;
			 $items[9]['name'] ='tennis revenue';
			 $items[9]['total']= $this->map['tennis_revenue'];
		 }
		 if($this->map['swimming_pool_revenue']!=0){
			 $items[10]['id']= 0;
			 $items[10]['name'] ='swimming pool revenue';
			 $items[10]['total']= $this->map['swimming_pool_revenue'];
		 }
		 if($this->map['extra_service_revenue']!=0){
			 $items[11]['id']= 0;
			 $items[11]['name'] ='extra service revenue';
			 $items[11]['total']= $this->map['extra_service_revenue'];
		 }
		 $total_occupie =0;
		 //$total_occupie = $this->occupied_revenue($from_date,$end_date )?$this->occupied_revenue($from_date,$end_date ):0;
		 $this->map['total'] = 	$this->map['total_room'] +
		 						$this->map['booking_revenue'] + 
								$this->map['no_show_revenue'] +
								$this->map['minibar_revenue'] +
								$this->map['laundry_revenue'] +
						 		$this->map['compensation_revenue'] +
								$this->map['restaurant_revenue'] +
								$this->map['karaoke_revenue'] + 
								$this->map['massage_revenue'] +
								$this->map['tennis_revenue'] +
								$this->map['swimming_pool_revenue']+
								$this->map['extra_service_revenue'];
	     	$this->map['booking_revenue'] =System::display_number($this->map['booking_revenue']);
			$this->map['no_show_revenue']= System::display_number($this->map['no_show_revenue']); 
			$this->map['minibar_revenue'] = System::display_number($this->map['minibar_revenue']);
			$this->map['laundry_revenue'] = System::display_number($this->map['laundry_revenue']);
			$this->map['compensation_revenue'] = System::display_number($this->map['compensation_revenue']);
			$this->map['restaurant_revenue'] = System::display_number($this->map['restaurant_revenue']);
			$this->map['karaoke_revenue'] =System::display_number( $this->map['karaoke_revenue']); 
			$this->map['massage_revenue'] = System::display_number($this->map['massage_revenue']);
			$this->map['tennis_revenue'] = System::display_number($this->map['tennis_revenue']);
			$this->map['swimming_pool_revenue'] = System::display_number($this->map['swimming_pool_revenue']);
			$this->map['extra_service_revenue'] = System::display_number($this->map['extra_service_revenue']);
			$this->map['total'] = System::display_number($this->map['total']);
		/*------------------------------------------- Bieu do cot ------------------------*/
		 $from_date = '1'.'/'.date('m').'/'.date('Y');
		 $this->map['traveller_column'] = $this->get_traveller($from_date,$end_date );
		 $total_room_column = 0;
		 foreach($this->map['traveller_column'] as $key =>$value){
			 if($value['arrival_time'] == $value['departure_time']){
				 $this->map['traveller_column'][$key]['change_price'] = $value['pice'];
			 }
			 $total_room_column += $this->map['traveller_column'][$key]['change_price'];
		 }
		 $this->map['total_room_column'] = $total_room_column;
		 $this->map['booking_revenue_column'] = $this->get_amount_booked($from_date,$end_date )?$this->get_amount_booked($from_date,$end_date ):0;
		 $this->map['no_show_revenue_column'] = $this->no_show_revenue($from_date,$end_date )?$this->no_show_revenue($from_date,$end_date ):0;
		 $this->map['minibar_revenue_column'] = $this->get_minibar_revenue($from_date,$end_date )?$this->get_minibar_revenue($from_date,$end_date ):0;
		 $this->map['laundry_revenue_column'] = $this->laudry_revenue($from_date,$end_date )?$this->laudry_revenue($from_date,$end_date ):0;
		 $this->map['compensation_revenue_column'] = $this->compensation_revenue($from_date,$end_date )?$this->compensation_revenue($from_date,$end_date ):0;
		 $this->map['restaurant_revenue_column'] = $this->restaurant_revenue($from_date,$end_date )?$this->restaurant_revenue($from_date,$end_date ):0;
		 $this->map['karaoke_revenue_column'] = $this->karaoke_revenue($from_date,$end_date )?$this->karaoke_revenue($from_date,$end_date ):0;
		 $this->map['massage_revenue_column'] = $this->massage_revenue($from_date,$end_date )?$this->massage_revenue($from_date,$end_date ):0;
		 $this->map['tennis_revenue_column'] = $this->tennis_revenue($from_date,$end_date )?$this->tennis_revenue($from_date,$end_date ):0;
		 $this->map['swimming_pool_revenue_column'] = $this->swimming_pool_revenue($from_date,$end_date )?$this->swimming_pool_revenue($from_date,$end_date ):0;
		 $this->map['extra_service_revenue_column'] = $this->extra_service_revenue($from_date,$end_date )?$this->extra_service_revenue($from_date,$end_date ):0;
		 $arr = array();
		 if($this->map['total_room_column']!=0){
			 $arr[0]['id']= 0;
			 $arr[0]['name'] ='room revenue';
			 $arr[0]['total']=$this->map['total_room_column'];
		 }
		 if($this->map['booking_revenue_column']!=0){
			 $arr[1]['id']= 0;
			 $arr[1]['name'] ='booking revenue';
			 $arr[1]['total']= $this->map['booking_revenue_column'];
		 }
		 if($this->map['no_show_revenue_column']!=0){
			 $arr[2]['id']= 0;
			 $arr[2]['name'] ='no show revenue';
			 $arr[2]['total']= $this->map['no_show_revenue_column'];
		 }
		 if($this->map['minibar_revenue_column']!=0){
			 $arr[3]['id']= 0;
			 $arr[3]['name'] ='minibar revenue';
			 $arr[3]['total']= $this->map['minibar_revenue_column'];
		 }
		 if($this->map['laundry_revenue_column']!=0){
			 $arr[4]['id']= 0;
			 $arr[4]['name'] ='laundry revenue';
			 $arr[4]['total']= $this->map['laundry_revenue_column'];
		 }
		 if($this->map['compensation_revenue_column']!=0){
			 $arr[5]['id']= 0;
			 $arr[5]['name'] ='compensation revenue';
			 $arr[5]['total']= $this->map['compensation_revenue_column'];
		 }
		 if($this->map['restaurant_revenue_column']!=0){
			 $arr[6]['id']= 0;
			 $arr[6]['name'] ='restaurant revenue';
			 $arr[6]['total']= $this->map['restaurant_revenue_column'];
		 }
		 if($this->map['karaoke_revenue_column']!=0){
			 $arr[7]['id']= 0;
			 $arr[7]['name'] ='karaoke revenue';
			 $arr[7]['total']= $this->map['karaoke_revenue_column'];
		 }
		 if($this->map['massage_revenue_column']!=0){
			 $arr[8]['id']= 0;
			 $arr[8]['name'] ='massage revenue';
			 $arr[8]['total']= $this->map['massage_revenue_column'];
		 }
		 if($this->map['tennis_revenue_column']!=0){
			 $arr[9]['id']= 0;
			 $arr[9]['name'] ='tennis revenue';
			 $arr[9]['total']= $this->map['tennis_revenue_column'];
		 }
		 if($this->map['swimming_pool_revenue_column']!=0){
			 $arr[10]['id']= 0;
			 $arr[10]['name'] ='swimming pool revenue';
			 $arr[10]['total']= $this->map['swimming_pool_revenue_column'];
		 }
		 if($this->map['extra_service_revenue_column']!=0){
			 $arr[11]['id']= 0;
			 $arr[11]['name'] ='extra service revenue';
			 $arr[11]['total']= $this->map['extra_service_revenue_column'];
		 }
		 $this->parse_layout('report',array('pieitems'=>String::array2js($items),'columnitems'=>String::array2js($arr))+$this->map);
	}
	function get_status_rooms($from_date,$end_date){
		$sql='
			SELECT
			     	rs.room_id , rs.in_date, rr.departure_time, rr.arrival_time,
					rs.status, rs.house_status, rs.id, rr.status as rr_status
			FROM 
					room_status rs
			LEFT 
				OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
			INNER JOIN 
					room ON room.id = rs.room_id
			WHERE 
					(rs.in_date >= \''.Date_Time::to_orc_date($from_date).'\'
					AND rs.in_date <= \''.Date_Time::to_orc_date($end_date).'\')
					AND room.portal_id = \''.PORTAL_ID.'\'';
		$rooms = DB::fetch_all($sql);
		$room_status = array();
		$room_status['today_ocuppied_rooms']=0;
		$room_status['today_check_ins']=0;
		$room_status['today_check_outs']=0;
		$room_status['checked_out_rooms_marked_dirty']=0;
		$room_status['today_no_shows']=0;
		$room_status['today_check_outs']=0;
		$room_status['repairing_rooms']=0;
		$room_status['occupied_rooms_marked_for_dirty']=0;
		foreach($rooms as $key => $value){
			if($value['status'] == 'OCCUPIED'){
				if($value['arrival_time'] != $value['departure_time'] and $value['departure_time'] == $value['in_date']){
						
					}else{
						$room_status['today_ocuppied_rooms']++;
					}
			}
			if($value['status'] == 'OCCUPIED' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') == Date_Time::to_orc_date($from_date)){
					$room_status['today_check_ins']++;
				}
			if($value['rr_status'] == 'CHECKOUT' and Date_Time::convert_orc_date_to_date($value['departure_time'],'/') == Date_Time::to_orc_date($from_date)){
					$room_status['today_check_outs']++;
					if($value['house_status']=='DIRTY'){
						$room_status['checked_out_rooms_marked_dirty']++;
					}
				}
			if($value['status'] == 'BOOKED' and Date_Time::convert_orc_date_to_date($value['arrival_time'],'/') < Date_Time::to_orc_date($from_date)){
					$room_status['today_no_shows']++;
				}
			if($value['rr_status'] == 'CHECKOUT' and Date_Time::convert_orc_date_to_date($value['departure_time'],'/') == Url::sget('date')){
					$room_status['today_check_outs']++;
					if($value['house_status']=='DIRTY'){
						$room_status['checked_out_rooms_marked_dirty']++;
					}
				}
			if($value['house_status'] == 'REPAIR'){
				$room_status['repairing_rooms'] ++;
			}
			if($value['status'] == 'OCCUPIED'){
					if($value['house_status']=='DIRTY'){
						$room_status['occupied_rooms_marked_for_dirty']++;
					}
				}
			}
			////////////////////////////////////////////CANCEL////////////////////////////////////////////////////
			$sql = '
				SELECT
					count (*) as total
				FROM
					reservation_room
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND	(reservation_room.status = \'CANCEL\')
					AND ( reservation_room.lastest_edited_time >='.Date_Time::to_time($from_date).' )
				    AND  ( reservation_room.lastest_edited_time <'.(Date_Time::to_time($end_date)+24*3600).')';
			$room_status['today_cancellations'] = DB::fetch($sql,'total');
			//////////////////////// BOOKED //////////////////
			$sql = '
				SELECT
					count (*) as total
				FROM
					reservation_room
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND	(reservation_room.status = \'BOOKED\' OR reservation_room.booked_user_id is not null)
					AND (	reservation_room.time >='.Date_Time::to_time($from_date).')
					AND ( reservation_room.time <'.(Date_Time::to_time($end_date )+24*3600).')
			';
			$room_status['today_bookeds'] = DB::fetch($sql,'total');

		 return $room_status;
	}
	function get_traveller($from_date,$end_date){
		$sql = '
					select 
						distinct
						reservation_room.id,
						CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as guest_name,
						room_level.name as room_level_name,
						room.name as room_name,
						to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time ,
						to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
						room_status.change_price,
						reservation_room.price,
						tour.name as tour_name,
						customer.name as company_name
					from 
						room_status
						inner join  reservation_room on room_status.reservation_room_id=reservation_room.id					
						inner join reservation on reservation.id = reservation_room.reservation_id					
						inner  join room on room.id = reservation_room.room_id
						inner join room_level on room_level.id = room.room_level_id 
						left outer join tour on tour.id = reservation.tour_id
						left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
						left outer join traveller on reservation_traveller.traveller_id = traveller.id
						left outer join customer on reservation.customer_id=customer.id
					where 
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND room_status.in_date >= \''.Date_Time::to_orc_date($from_date).'\'
						AND room_status.in_date <= \''.Date_Time::to_orc_date($end_date).'\'
						AND room_status.status = \'OCCUPIED\'
						AND (room_level.is_virtual is null or room_level.is_virtual = 0)
					order by
						room.name
			';
			$items = DB::fetch_all($sql);
			return $items; 	
	}
	function get_amount_booked($from_date,$end_date){
			$sql = '
				SELECT
					SUM(room_status.change_price) AS total
				FROM
					room_status
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date >= \''.Date_Time::to_orc_date($from_date).'\'
					AND room_status.in_date <= \''.Date_Time::to_orc_date($end_date).'\'
					AND reservation_room.status = \'BOOKED\'
					AND reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\'
					AND reservation_room.arrival_time <= \''.Date_Time::to_orc_date($end_date).'\'
			';
			return DB::fetch($sql,'total');
	}
	function no_show_revenue($from_date,$to_date){
			$sql = '
				SELECT
					SUM(room_status.change_price) AS total
				FROM
					room_status
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date($from_date).'\'
					AND reservation_room.status = \'BOOKED\'
					AND reservation_room.arrival_time < \''.Date_Time::to_orc_date($from_date).'\'
			';
			return DB::fetch($sql,'total');
	}
	function get_minibar_revenue($from_date,$end_date){
			$sql = '
			SELECT
				SUM(housekeeping_invoice.total) AS total
			FROM
				housekeeping_invoice
				INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
			WHERE
				reservation.portal_id = \''.PORTAL_ID.'\'
				AND housekeeping_invoice.type = \'MINIBAR\'
				AND housekeeping_invoice.time >= '.Date_Time::to_time($from_date).'
				AND housekeeping_invoice.time < '.(Date_Time::to_time($end_date)+24*3600).'
			';
			return DB::fetch($sql,'total');
	}
	function laudry_revenue($from_date,$end_date){
			$sql = '
				SELECT
					SUM(housekeeping_invoice.total) AS total
				FROM
					housekeeping_invoice
					INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND housekeeping_invoice.type = \'LAUNDRY\'
					AND housekeeping_invoice.time >= '.Date_Time::to_time($from_date).'
					AND housekeeping_invoice.time < '.(Date_Time::to_time($end_date )+24*3600).'
			';
			return DB::fetch($sql,'total');
	}
	function compensation_revenue($from_date,$end_date){
			$sql = '
				SELECT
					SUM(housekeeping_invoice.total) AS total
				FROM
					housekeeping_invoice
					INNER JOIN reservation_room ON reservation_room.id = housekeeping_invoice.reservation_room_id
					INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND housekeeping_invoice.type = \'EQUIP\'
					AND housekeeping_invoice.time >= '.Date_Time::to_time($from_date,$end_date ).'
					AND housekeeping_invoice.time < '.(Date_Time::to_time($from_date,$end_date )+24*3600).'
			';
			return DB::fetch($sql,'total');
	}
	function restaurant_revenue($from_date,$end_date){
			$sql = '
				SELECT 
					sum( bar_reservation.TOTAL) as total
				FROM 
					bar_reservation
				WHERE 
					 '.Date_Time::to_time($from_date).'<= TIME_OUT 
					 AND TIME_OUT <'.(Date_Time::to_time($end_date )+(24*3600)).'
					 AND bar_reservation.portal_id = \''.PORTAL_ID.'\'
				';
		return DB::fetch($sql,'total');
	}
	function karaoke_revenue($from_date,$end_date){
		if(HAVE_KARAOKE){
				$sql = '
					SELECT 
						sum(ka_reservation.total) as total
					  FROM 
						ka_reservation
						inner join ka_reservation_room on ka_reservation_room.ka_reservation_id = ka_reservation.id
						left outer join reservation_room on reservation_room.id=ka_reservation.reservation_room_id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND ka_reservation.time_out>='.Date_Time::to_time($from_date).'
						AND ka_reservation.time_out < '.(Date_Time::to_time($end_date)+(24*3600)).'
				';
				return DB::fetch($sql,'total');
			}
	}
	function massage_revenue($from_date,$end_date){
			if(HAVE_MASSAGE){
				$sql = '
					SELECT 
						 sum(massage_product_consumed.price*massage_product_consumed.quantity) as total
					  FROM 
						massage_product_consumed
						inner join massage_room on massage_product_consumed.room_id = massage_room.id
						left outer join reservation_room on massage_product_consumed.hotel_reservation_room_id = reservation_room.id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
						reservation.portal_id = \''.PORTAL_ID.'\'	
						AND massage_product_consumed.time_out>='.Date_Time::to_time($from_date).'
						AND massage_product_consumed.time_out < '.(Date_Time::to_time($end_date )+(24*3600)).'
				';
				return DB::fetch($sql,'total');
			}
	}
	function tennis_revenue($from_date,$end_date){	
			if(HAVE_TENNIS){
				$sql = '
					SELECT 
						 sum(tennis_reservation_court.total_amount) as total
					  FROM 
						tennis_reservation_court
						inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
						left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
						left outer join reservation_room on tennis_reservation_court.hotel_reservation_room_id = reservation_room.id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
					   reservation.portal_id = \''.PORTAL_ID.'\'	
					   AND tennis_reservation_court.time_out>='.Date_Time::to_time($from_date).'
					   AND tennis_reservation_court.time_out < '.(Date_Time::to_time($end_date )+(24*3600)).'
				';
				return DB::fetch($sql,'total');
			}
	}
	function swimming_pool_revenue($from_date,$end_date){
			if(HAVE_SWIMMING){
				$sql = '
					SELECT 
						 sum(swimming_pool_reservation_pool.total_amount) as total
					  FROM 
						swimming_pool_reservation_pool
						inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
						left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
						left outer join reservation_room on swimming_pool_reservation_pool.hotel_reservation_room_id = reservation_room.id
						left outer join reservation ON reservation.id = reservation_room.reservation_id
					  WHERE 
						reservation.portal_id = \''.PORTAL_ID.'\'
						AND swimming_pool_reservation_pool.time_out>='.Date_Time::to_time($from_date).'
						AND swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time($end_date )+(24*3600)).'
				';
				return DB::fetch($sql,'total');			
			}
	}
	function extra_service_revenue($from_date,$end_date){
		$sql = 	'	
				select 
						sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as total
					from 
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where 
						extra_service_invoice.portal_id = \''.PORTAL_ID.'\'
						AND extra_service_invoice_detail.in_date >= \''.Date_Time::to_orc_date($from_date).'\'
						AND extra_service_invoice_detail.in_date < \''.Date_Time::to_orc_date($end_date).'\'
						AND extra_service_invoice_detail.used = 1
			';
			return DB::fetch($sql,'total');
	}
	/*
	function occupied_revenue ($from_date,$end_date){
			$sql = '
				SELECT
					SUM(room_status.change_price) AS total
				FROM
					room_status
					INNER JOIN reservation ON reservation.id = room_status.reservation_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date($from_date).'\'
					AND room_status.in_date = \''.Date_Time::to_orc_date($end_date).'\'
					AND room_status.status = \'OCCUPIED\'
			';
			return DB::fetch($sql,'total');
	}
	*/
}
?>