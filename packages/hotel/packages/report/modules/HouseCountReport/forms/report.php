<?php
class HouseCountReportForm extends Form
{
	function HouseCountReportForm()
	{
		Form::Form('HouseCountReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
	}
		function get_res($from_date,$to_date){		   
		$sql_m = 'SELECT * FROM (
					SELECT 
							reservation_room.id,
							reservation_room.time_in,
							reservation_room.time_out,
							reservation_room.status ,
							reservation_room.early_checkin,
							reservation_room.early_arrival_time,
							reservation_room.arrival_time ,
							reservation.portal_id,
							party.name_'.Portal::language().' as party_name,
							reservation_room.departure_time,
							to_char(arrival_time,\'DD/MM/YYYY\') as brief_arrival_time ,
							to_char(departure_time,\'DD/MM/YYYY\') as brief_departure_time ,
							reservation_room.verify_dayuse
					FROM reservation_room 
							 inner join reservation on reservation.id = reservation_room.reservation_id 
							 inner join party on party.user_id = reservation.portal_id left outer 
							 join room on room.id = reservation_room.room_id 
							 left outer join room_level on room_level.id = reservation_room.room_level_id 
					WHERE reservation.portal_id <> \'#default\' AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'BOOKED\') 
							AND ((reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\' AND reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_date).'\')) 
							ORDER BY reservation_room.reservation_id DESC,reservation_room.time_in )
					 ORDER BY brief_arrival_time, brief_departure_time
					';				
		$r = DB::fetch_all($sql_m);
		$total_rooms = array();
		$time_from = Date_time::to_time($from_date);
		$time_end = Date_time::to_time($to_date);
		foreach($r as $k=>$v){
			 $time_arrive = Date_Time::to_time($v['brief_arrival_time'] );
			  $time_departure = Date_Time::to_time($v['brief_departure_time']);
			if($v['verify_dayuse']){
			      $v['verify_dayuse'] = $v['verify_dayuse']/10;
				if($v['early_checkin']){
					if($v['early_arrival_time']){
						$v['early_arrival_time'] = Date_Time::convert_orc_date_to_date($v['early_arrival_time'],'/');
						// echo $v['portal_id'].$v['status'].'in_date'.$v['early_arrival_time'].$v['early_arrival_time'].'<br/>';
						if(isset($total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']])){
								$total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']]['total'] += $v['verify_dayuse'];
						}else{
								$total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']]['id'] = $v['portal_id'].'_'.$v['early_arrival_time'];
								$total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']]['total'] = $v['verify_dayuse'];
								$total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']]['name'] = $v['party_name'];
								$total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']]['portal_id'] =$v['portal_id'];
								$total_rooms[$v['portal_id'].'_'.$v['early_arrival_time']]['in_date'] = $v['early_arrival_time'];
						}
					}else{
						//echo $v['portal_id'].$v['status'].'in_date'.$v['brief_arrival_time'].$v['early_arrival_time'].'<br/>';
						if(isset($total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']])){
										$total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']]['total'] += $v['verify_dayuse'];
									}else{
										$total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']]['id'] = $v['portal_id'].'_'.$v['brief_arrival_time'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']]['total'] = $v['verify_dayuse'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']]['name'] = $v['party_name'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']]['portal_id'] =$v['portal_id'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_arrival_time']]['in_date'] = $v['brief_arrival_time'];
						}
					}
				}
				else{
					if($v['verify_dayuse'] < 0 ){
						
						if($time_arrive < $time_departure){
								 	  $time_before =date('d/m/Y',$time_departure - 24* 3600);
									// echo $v['portal_id'].$v['status'].'in_date'.$time_before.$v['verify_dayuse'].'<br/>';
									if(isset($total_rooms[$v['portal_id'].'_'.$time_before])){
											$total_rooms[$v['portal_id'].'_'.$time_before]['total'] += $v['verify_dayuse'];
										}else{
											$total_rooms[$v['portal_id'].'_'.$time_before]['id'] = $v['portal_id'].'_'.$v['departure_time'];
											$total_rooms[$v['portal_id'].'_'.$time_before]['total'] = $v['verify_dayuse'];
											$total_rooms[$v['portal_id'].'_'.$time_before]['name'] = $v['party_name'];
											$total_rooms[$v['portal_id'].'_'.$time_before]['portal_id'] =$v['portal_id'];
											$total_rooms[$v['portal_id'].'_'.$time_before]['in_date'] = $time_before;
										}	 	
						}else{
							// echo $v['portal_id'].$v['status'].'in_date'.$v['brief_departure_time'].$v['verify_dayuse'].'<br/>';
								if(isset($total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']])){
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['total'] += $v['verify_dayuse'];
									}else{
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['id'] = $v['portal_id'].'_'.$v['brief_departure_time'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['total'] = $v['verify_dayuse'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['name'] = $v['party_name'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['portal_id'] =$v['portal_id'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['in_date'] = $v['brief_departure_time'];
									}
						}
								  	
					}else{ 
							// echo $v['portal_id'].'date'.$v['brief_departure_time'].'veri'.$v['verify_dayuse'].'<br/>';
								if(isset($total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']])){
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['total'] += $v['verify_dayuse'];
									}else{
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['id'] = $v['portal_id'].'_'.$v['brief_departure_time'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['total'] = $v['verify_dayuse'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['name'] = $v['party_name'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['portal_id'] =$v['portal_id'];
										$total_rooms[$v['portal_id'].'_'.$v['brief_departure_time']]['in_date'] = $v['brief_departure_time'];
									}
						}
					}
			} 

			  for($i = $time_from ; $i<=$time_end; $i+=24*3600){
				  $date_time = date('d/m/Y',$i);
						if($v['status'] == 'BOOKED'){
							if(($time_arrive <= $i) && ($time_departure > $i)){
							//echo $v['portal_id'].$v['status'].'in_time'.$date_time.'arr'.$v['brief_arrival_time'].'drr'.$v['brief_departure_time'].'<br/>';
									  if(isset($total_rooms[$v['portal_id'].'_'.$date_time])){
															$total_rooms[$v['portal_id'].'_'.$date_time]['total'] += 1;
														}else{
															$total_rooms[$v['portal_id'].'_'.$date_time]['id'] = $v['portal_id'].'_'.$date_time;
															$total_rooms[$v['portal_id'].'_'.$date_time]['total'] = 1;
															$total_rooms[$v['portal_id'].'_'.$date_time]['name'] = $v['party_name'];
															$total_rooms[$v['portal_id'].'_'.$date_time]['portal_id'] =$v['portal_id'];
															$total_rooms[$v['portal_id'].'_'.$date_time]['in_date'] = $date_time;
										}
							}
							if(($time_arrive ==  $i) && ($time_departure == $i)){
								//echo $v['portal_id'].$v['status'].'in_time'.$date_time.'arr'.$v['brief_arrival_time'].'drr'.$v['brief_departure_time'].'<br/>';
								if(isset($total_rooms[$v['portal_id'].'_'.$date_time])){
															$total_rooms[$v['portal_id'].'_'.$date_time]['total'] += 1;
														}else{
															$total_rooms[$v['portal_id'].'_'.$date_time]['id'] = $v['portal_id'].'_'.$date_time;
															$total_rooms[$v['portal_id'].'_'.$date_time]['total'] = 1;
															$total_rooms[$v['portal_id'].'_'.$date_time]['name'] = $v['party_name'];
															$total_rooms[$v['portal_id'].'_'.$date_time]['portal_id'] =$v['portal_id'];
															$total_rooms[$v['portal_id'].'_'.$date_time]['in_date'] = $date_time;
										}
								
							}

					  }
					  if(($v['status'] !='BOOKED')){
							  if( $time_arrive < $i && $time_departure > $i){	
							 
								  if(isset($total_rooms[$v['portal_id'].'_'.$date_time])){
												$total_rooms[$v['portal_id'].'_'.$date_time]['total'] += 1;
									}else{
												$total_rooms[$v['portal_id'].'_'.$date_time]['id'] = $v['portal_id'].'_'.$date_time;
												$total_rooms[$v['portal_id'].'_'.$date_time]['total'] = 1 ;
												$total_rooms[$v['portal_id'].'_'.$date_time]['name'] = $v['party_name'];
												$total_rooms[$v['portal_id'].'_'.$date_time]['portal_id'] = $v['portal_id'];
												$total_rooms[$v['portal_id'].'_'.$date_time]['in_date'] = $date_time;
									}
							  } 
							  if($time_arrive == $i ){
									//echo $v['portal_id'].$v['status'].'in_time'.$date_time.'arr'.$v['brief_arrival_time'].'drr'.$v['brief_departure_time'].'<br/>';
									if(isset($total_rooms[$v['portal_id'].'_'.$date_time])){
													$total_rooms[$v['portal_id'].'_'.$date_time]['total'] += 1 ;
										}else{
													$total_rooms[$v['portal_id'].'_'.$date_time]['id'] = $v['portal_id'].'_'.$date_time;
													$total_rooms[$v['portal_id'].'_'.$date_time]['total'] = 1 ;
													$total_rooms[$v['portal_id'].'_'.$date_time]['name'] = $v['party_name'];
													$total_rooms[$v['portal_id'].'_'.$date_time]['portal_id'] = $v['portal_id'];
													$total_rooms[$v['portal_id'].'_'.$date_time]['in_date'] = $date_time;
										}  
						  }
				  }
			  }
			  
		}
		return $total_rooms;
	}

function draw(){
		$this->map = array();
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$start_date = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
		$end_date =Date('d/m/Y',Date_time::to_time($start_date) + 6*24*3600);	
		// $items = $this->get_room_busy($start_date,$end_date);
		$items = $this->get_res($start_date,$end_date);
		$from_time = Date_Time::to_time($start_date);
		$to_time = Date_Time::to_time($end_date);
		$partys = DB::fetch_all('  SELECT  
										party.id , 
										party.name_1 as name,
										party.user_id from party 
									WHERE party.type=\'PORTAL\' AND user_id <>\'#default\' order by  party.user_id');	
		foreach($partys as $id=>$v){
			for($i=$from_time;$i<=$to_time; $i +=24*3600){
				$date =date('d/m/Y',$i);
					if(!isset($items[$v['user_id'].'_'.$date])){
						$items[$v['user_id'].'_'.$date]['total']['id'] = $v['user_id'].'_'.$date;
						$items[$v['user_id'].'_'.$date]['portal_id'] =  $v['user_id'];
						$items[$v['user_id'].'_'.$date]['in_date'] = $date;
						$items[$v['user_id'].'_'.$date]['total'] = 0;
						$items[$v['user_id'].'_'.$date]['name'] = $v['name'];
					}
			}		
		}
		$this->map['total_room'] =DB::fetch_all(' 
											select count(*)as total , room.portal_id as id
											from room  
											INNER JOIN room_level ON room_level.id = room.room_level_id
											where 
											(room_level.is_virtual = 0 OR room_level.is_virtual is null)
											group by room.portal_id
											');
		$this->map['items'] = $items;
		$this->map['partys'] = $partys;
		$this->parse_layout('report',$this->map);
		
	}

}
?>