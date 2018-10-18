<?php
class AsignReservationForm extends Form
{
	function AsignReservationForm()
	{
		Form::Form("AsignReservationForm");
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->add('reservation_room.id',new TextType(false,'invalid_reservation_id',0,255));
		$this->add('reservation_room.room_name',new TextType(false,'miss_room',0,255));
		$this->add('reservation_room.room_id',new TextType(false,'invalid_room_id',0,255,'status','CHECKIN'));
		$this->add('reservation_room.room_level_id',new TextType(false,'invalid_room_level_id',0,255));
		$this->add('reservation_room.room_level_name',new TextType(false,'invalid_room_level_id',0,255));
		$this->add('reservation_room.price',new FloatType(true,'invalid_price','0','100000000000'));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function on_submit()
    {
        //System::debug($this);exit();
        if($this->check())
        {
			if(Url::get('reservation_rooms'))
            {
                $reservation_rooms = Url::get('reservation_rooms');
                //System::debug($reservation_rooms);
                foreach($reservation_rooms as $key=>$record)
                {
                    $cond = 'room.portal_id = \''.PORTAL_ID.'\' AND R.status<>\'CANCEL\' AND R.status<>\'NOSHOW\'
						AND R.room_id=\''.$record['room_id'].'\' 
						'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'');
                    $time_in = Date_Time::to_time($record['arrival_time']); 
				    $time_out=Date_Time::to_time($record['departure_time']);
                    $arr = explode(':',$record['time_in']);
					$time_in= $time_in + intval($arr[0])*3600+intval($arr[1])*60;
					$arr = explode(':',$record['time_out']);
					$time_out= $time_out + intval($arr[0])*3600+intval($arr[1])*60;
                    $cond .= ' AND (
						(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_out.')
    					OR	(R.time_in >= '.$time_in.' AND R.time_out >= '.$time_out.' AND R.time_in <= '.$time_out.')
    					OR	(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_in.' AND R.time_out <= '.$time_out.')
    					OR	(R.time_in >= '.$time_in.' AND R.time_out <= '.$time_out.')
    					OR	(R.time_out = '.$time_in.')  
				    )';
                    $sql = '
					SELECT 
						R.id,R.reservation_id
					FROM 
						reservation_room R
						INNER JOIN room ON room.id = R.room_id
					WHERE 
						
				    ';
                    if($reservation_room = DB::fetch($sql.' '.$cond))
    				{
    					$this->error('room_id_'.$key,Portal::language('room').' '.$record['room_name'].' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
    				}
                }
                if($this->is_error())
    			{
    				return;
    			}
                // log
                $type_log = 'Asign Room';
                $recode = '';
                $id = Url::get('id');
                if(isset($_REQUEST['id'])){
                    $recode_arr = explode(',',Url::get('id'));
                    for($i=0;$i<sizeof($recode_arr);$i++){
                        $recode .= $recode==''?'#'.$recode_arr[$i]:', #'.$recode_arr[$i];
                    }
                }
                $title_log = 'Asign Room in Recode '.$recode;
                $description_log = '<h3>Asign recode infomation</h3><br/><hr/>';
                // end log
                // asign
				foreach($reservation_rooms as $key=>$record)
                {
					$record['price'] = System::calculate_number($record['price']);
					if($record['id']!=0 || $record['id']!='')
                    {
						DB::update('reservation_room',array('room_id'=>$record['room_id'],'room_level_id'=>$record['room_level_id'],'price'=>$record['price']),' id= '.$record['id'].' AND reservation_id='.$record['reservation_id'].'');
						//$date = DB::fetch('select TO_CHAR(\'DD/MM/YYYY\',arrival_time) as arrival_time,TO_CHAR(\'DD/MM/YYYY\',departure_time) as departure_time from reservation_room where id='.$recode['id'].' and reservation_id='.$recode['reservation_id']);
                        //$from = Date_Time::to_time($date['arrival_time']);
						//$to   = Date_Time::to_time($date['departure_time']);
						
                        //$d = $from;
                        $date['arrival_time'] = $record['arrival_time'];
                        $date['departure_time'] = $record['departure_time'];
                        $room_status = DB::fetch_all('select 
                                                            room_status.id,
                                                            reservation_room.departure_time,
                                                            reservation_room.arrival_time
                                                        from 
                                                            room_status 
                                                            inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                                                        where 
                                                            reservation_room.arrival_time<=room_status.in_date 
                                                            and ( (room_status.in_date<reservation_room.departure_time and reservation_room.arrival_time!=reservation_room.departure_time) 
                                                                    or (room_status.in_date<=reservation_room.departure_time and reservation_room.arrival_time=reservation_room.departure_time) )
                                                            and room_status.reservation_room_id='.$record['id'].'
                                                        ');
                        foreach($room_status as $k=>$v){
                            $date['arrival_time'] = $v['arrival_time'];
                            $date['departure_time'] = $v['departure_time'];
                            DB::update_id('room_status',
									array(
									'room_id'=>$record['room_id'],
									'change_price'=>$record['price']
									),$v['id']
								);
                        }
                        /*
						while($d>=$from and $d<=$to)
                        {
							//System::debug($_REQUEST['mi_reservation_room']);
							$sql = 'select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.$record['id'].'';
							if($room_status = DB::fetch($sql))
							{
								DB::update_id('room_status',
									array(
									'room_id'=>$record['room_id'],
									//'change_price'=>$record['price'],
									'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
									),$room_status['id']
								);
							}
							else
							{
								DB::insert('room_status',
									array(
										'room_id'=>$record['room_id'],
										'status'=>$status,
										'reservation_id'=>Url::get('id'),
										//'change_price'=>$record['price'],
										'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
										'reservation_room_id'=>$record['id']
									)
								);
							}
							$d=$d+(3600*24);
						}
                        */
                        $description_log .= '<p> - Action with room'.$record['room_name'].' price '.$record['price'].' Arrival time '.$date['arrival_time'].' Departure time: '.$date['departure_time'].'</p>';
                        DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$record['reservation_id']);
					}
				}
                // end asign
                $log_id = System::log($type_log,$title_log,$description_log,$id);
                if(isset($_REQUEST['id'])){
                    $recode_arr = explode(',',Url::get('id'));
                    for($i=0;$i<sizeof($recode_arr);$i++)
                    {
                        System::history_log('RECODE',$recode_arr[$i],$log_id);
                    }
                }
                
			}
            if(Url::get('fast')){
                echo Portal::language('asign_room').' '.Portal::language('successful'); die(); }
            else
			     Url::redirect_current(array('cmd'=>'edit','id'=>Url::get('id')));
		}
	}
	function draw()
	{
		$row = array();
		$this->map = array();
		$cond = '1>0 ';
		$customer_name = '';
		$tour_name = '';
		$tour_id = '';
		$customer_id = '';
		if(Url::get('id'))
        {
			if(strpos(Url::get('id'),','))
            {
				$ids = Url::get('id');
				$id = explode(',',$ids);
				$cond2 = '';
				for($i=0;$i<count($id);$i++)
                {
					if($id[$i] && $id[$i]!='')
                    {
						$cond2 .= ($i==0)?(' reservation.id ='.$id[$i]):(' OR reservation.id ='.$id[$i]);
					}
				}
				$cond .= ' AND ('.$cond2.')';
			}
            else
            {
				$cond .= ' AND reservation.id = '.Url::get('id').'';
			}
				// Laays ra cac thong tin cuar resrevation
				$reservations = DB::fetch_all('SELECT
							reservation.id
							,reservation.customer_id
							,reservation.note
							,customer.name as customer_name
							,tour.name as tour_name
							,tour.id as tour_id
							,reservation.booking_code
						FROM reservation
							LEFT OUTER JOIN customer ON reservation.customer_id = customer.id
							LEFT OUTER JOIN tour ON tour.id = reservation.tour_id
						WHERE '.$cond.' and reservation.portal_id = \''.PORTAL_ID.'\'');
				foreach($reservations as $key=>$r)
                {
					$customer_name = $r['customer_name'];
					$tour_name = $r['tour_name'];
					$tour_id = $r['tour_id'];
					$customer_id = $r['customer_id'];
					$bk_code = $r['booking_code'];
					$reservations[$key]['reservation_rooms'] = array();
				}
				//System::Debug($reservations);
				$reservation_rooms = DB::fetch_all('select
										reservation_room.id
										,reservation_room.reservation_id
										,reservation_room.status
										,reservation_room.arrival_time
										,reservation_room.departure_time
										,reservation_room.room_id
										,reservation_room.room_level_id
										,reservation_room.time_in
										,reservation_room.time_out
										,reservation_room.temp_room
										,reservation_room.note
										,reservation_room.price
										,reservation_room.adult
										,reservation_room.child
										,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
										,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
										,room_level.name as room_level_name
										,reservation_room.reservation_id
								FROM reservation_room
									INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
									INNER JOIN room_level ON reservation_room.room_level_id = room_level.id
									LEFT OUTER JOIN room ON room.id = reservation_room.room_id
								 WHERE '.$cond.' AND reservation.portal_id = \''.PORTAL_ID.'\' and reservation_room.status != \'CANCEL\' AND reservation_room.room_id is null
								 ORDER BY reservation.id');
				// Lay ra danh sach phofng.
                //System::debug($reservation_rooms);
				$cond_level = '';
				$min_time = ''; $max_time = '';
				foreach($reservation_rooms as $k => $reser)
                {
					$cond_level .= ($cond_level=='')?' room_level.id='.$reser['room_level_id'].'':' OR room_level.id='.$reser['room_level_id'].'';
					if($min_time=='' || $reser['time_in']<$min_time){
						$min_time = $reser['time_in'];
					}
					if($max_time=='' || $reser['time_out']>$max_time){
						$max_time = $reser['time_out'];
					}
				}
				if($cond_level==''){
					$cond_level = '1>0';
				}
				$cond_date = '';
                if($min_time != ''){
                    $min_time = Date_Time::to_time(date("d/m/Y",$min_time));
				    for($i=$min_time; $i<=$max_time;$i=$i+86400){
					$cond_date .= ($cond_date=='')?' rs.in_date=\''.Date_Time::to_orc_date(date('d/m/y',$i)).'\'':' OR rs.in_date=\''.Date_Time::to_orc_date(date('d/m/y',$i)).'\'';
				}
				}
				if($cond_date==''){
					$cond_date = '1>0';
				}
				// Lấy ra danh sách phòng trống
				$room_levels = DB::fetch_all('
					SELECT
						room.id as id
						,room.name as room_name
						,room_level.name as room_level_name
						,room_level.price
						,0 AS min_room_quantity
						,room_level.portal_id
						,room_level.id as room_level_id
						,0 as checked
						,room.floor
						,room_level.brief_name
					FROM
						room
						INNER JOIN room_level on room_level.id = room.room_level_id
					WHERE
						room_level.portal_id = \''.PORTAL_ID.'\' AND (room_level.is_virtual IS NULL OR room_level.is_virtual = 0) 
                    ORDER BY room.floor,room.room_level_id
					');
					// AND ('.$cond_level.')
				$sql_status = '
					SELECT
						r.portal_id,rs.id,rr.status,rs.house_status,rr.time_in,rr.time_out,rr.arrival_time,rr.departure_time,TO_CHAR(rs.in_date,\'DD/MM/YYYY\') AS in_date,rr.room_level_id,rr.room_id,room_level.name as room_level_name,room.name as room_name,room_level.brief_name
					FROM
						room_status rs
						INNER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
						INNER JOIN reservation r ON rr.reservation_id = r.id
						INNER JOIN room_level  ON rr.room_level_id = room_level.id
						INNER JOIN room ON room.id = rr.room_id
					WHERE
						r.portal_id = \''.PORTAL_ID.'\' AND ('.$cond_date.') AND rs.status <> \'CANCEL\'
					ORDER BY
						rr.room_level_id
					';
				$room_status = DB::fetch_all($sql_status);
                $sql_status2 = '
					SELECT
						r.portal_id,rs.id,rs.house_status,rs.room_id
					FROM
						room_status rs
						INNER JOIN room r ON r.id = rs.room_id
					WHERE
						r.portal_id = \''.PORTAL_ID.'\' AND ('.$cond_date.') AND rs.status <> \'CANCEL\'
                        AND rs.status <> \'OCCUPIED\' AND rs.house_status = \'REPAIR\'
					';
				$room_status2 = DB::fetch_all($sql_status2);
                //
				//AND  ('.$cond_level.')
				$floor = array();
                //System::debug($room_status);
				foreach($room_levels as $k=>$room)
                {
					$t=0;
					foreach($room_status as $key=>$status)
                    {
						if( $room['id']==$status['room_id'] && date('d/m/Y',$status['time_out']) != $status['in_date']){
							$t = 1;
                            //System::debug($room_status2);                            
						}
					}
                    foreach($room_status2 as $ke=>$status2)
                    {
						if( $room['id']==$status2['room_id']){
							$t = 1;
						}
					}
					if($t==0){
						$rooms[$k] = $room;
						if(!isset($floor[$room['floor']])){
							$floor[$room['floor']]['name'] = $room['floor'];
							$floor[$room['floor']]['rooms'] = array();
						}
                        //System::debug($rooms[$k]);
					}
				}
                
				// gasn phong vafo mangr reservation
                //System::debug($rooms);
				foreach($reservation_rooms as $a => $r_r){
					$n=0;
					foreach($rooms as $r => $room){
						$reservation_rooms[$a]['time_in'] = date('H:i',$r_r['time_in']);
						$reservation_rooms[$a]['time_out'] = date('H:i',$r_r['time_out']);
						$reservation_rooms[$a]['arrival_time'] = Date_Time::convert_orc_date_to_date($r_r['arrival_time'],'/');
						$reservation_rooms[$a]['departure_time'] = Date_Time::convert_orc_date_to_date($r_r['departure_time'],'/');
						$reservation_rooms[$a]['room_name_1'] = $r_r['temp_room'];
						$reservation_rooms[$a]['reservation_id'] = $r_r['reservation_id'];
						if($r_r['room_level_id'] == $room['room_level_id'] && $room['checked']==0){
							$reservation_rooms[$a]['room_id'] = $room['id'];
							$reservation_rooms[$a]['room_name'] = $room['room_name'];
							$reservation_rooms[$a]['room_level_id'] = $room['room_level_id'];
							$reservation_rooms[$a]['room_level_name'] = $room['room_level_name'];
							$reservation_rooms[$a]['price'] = System::display_number($r_r['price']);
							$rooms[$r]['checked'] = 1;
							$n = 1;
							break;
						}
					}
                    //System::debug($n);
					if($n==0){
						$reservation_rooms[$a]['note'] = Portal::language('this_room_type_has_expired');
						$reservation_rooms[$a]['price'] = System::display_number($r_r['price']);
						$reservation_rooms[$a]['room_level_id'] = $r_r['room_level_id'];
						$reservation_rooms[$a]['room_level_name'] = $r_r['room_level_name'];
					}
				}
		}
        $room_id = array();
		foreach($reservation_rooms as $k => $rr)
        {
			if(isset($reservations[$rr['reservation_id']])){
				$reservations[$rr['reservation_id']]['reservation_rooms'][$k] = $rr;
			}
            $room_id[$rr['room_id']] = $rr['room_id'];
		}
        
		foreach($rooms as $y =>$roo)
        {
			if(isset($floor[$roo['floor']]))
            {
				$floor[$roo['floor']]['rooms'][$y] = $roo;
			}
            
            
		}
        foreach($floor as $k => $v)
        {
            foreach($v['rooms'] as $key => $value)
            {
                if(isset($room_id[$key]))
                {
                    unset($floor[$k]['rooms'][$key]);
                }
            }
        }
        //system::debug($reservations);
		$this->map['rooms'] = $rooms;
		$this->map['floors'] = $floor;
		$this->map['tour_name'] = $tour_name;
		$this->map['tour_id'] = $tour_id;
		$this->map['customer_name'] = $customer_name;
		$this->map['customer_id'] = $customer_id;
		$this->map['re_code'] = Url::get('id');
		$this->map['booking_code'] = $bk_code;
		//System::Debug($reservations);
		$this->map['items'] = $reservations;
		$this->map['items_js'] = String::array2js($reservations);
		$this->parse_layout('asign_room',$this->map);
	}
}
?>