<?php
class ChangeRoomForm extends Form
{
	function ChangeRoomForm()
	{
		Form::Form('ChangeRoomForm');
		$this->add('change_room_reason',new TextType(true,'you_have_to_input_change_room_reason',3,255));
	}
	function on_submit()
    {
		if($this->check())
        {
			if(!$this->check_conflig(Url::get('from_reservation_room_id'),Url::get('to_room_id'),$this))
            {
				$this->replace_all_room_id(Url::get('from_reservation_room_id'),Url::get('to_room_id'));
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
				echo '<script>window.setTimeout("location=\''.URL::build_current().'\'",2000);</script>';
				exit();
			}
		}
	}
	function check_conflig($reservation_room_id,$to_room_id,&$form)
    {
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
		$time_in = $record['time_in'];
		$time_out = $record['time_out'];
		$room_name = DB::fetch('SELECT name FROM room WHERE id = '.$to_room_id.'','name');
		$cond = 'room.portal_id = \''.PORTAL_ID.'\' AND R.status<>\'CANCEL\' AND R.status<>\'CHECKOUT\'
				AND R.room_id=\''.$to_room_id.'\'
				'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'');
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
				'.$cond.'
		';
		$room_id = $record['room_id'];
		if($reservation_room = DB::fetch($sql) and $record['status']<>'CANCEL' and $record['status']<>'CHECKOUT')
		{
			$form->error('to_room_id',Portal::language('room').' '.$room_name.' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'#'.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
			return;
		}else{
			return false;
		}
	}
	function replace_all_room_id($reservation_room_id,$to_room_id)
    {
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
		$from_room_name = DB::fetch('select name from room where id = '.$record['room_id'].'','name');
		$room_level_id = DB::fetch('select room_level_id from room where id = '.$to_room_id.'','room_level_id');
		$room_type_id = DB::fetch('select room_type_id from room where id = '.$to_room_id.'','room_type_id');
		$to_room_name = DB::fetch('select name from room where id = '.$to_room_id.'','name');
		$minibar_id = DB::fetch('select id from minibar where room_id = '.$to_room_id.'','id');
		DB::query('update reservation_room set note = \''.Url::get('change_room_reason').'\' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_id = '.$to_room_id.' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_level_id = '.$room_level_id.' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_type_id = '.$room_type_id.' where id = '.$reservation_room_id);
		DB::query('update housekeeping_invoice set MINIBAR_ID = \''.$minibar_id.'\' where type = \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$reservation_room_id);
		DB::query('update housekeeping_invoice set MINIBAR_ID = '.$to_room_id.' where type <> \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$reservation_room_id);
		$room_status = DB::fetch_all('select * from room_status where reservation_room_id = '.$reservation_room_id.'');
		foreach($room_status as $r => $room)
        {
			if($room['house_status'] =='REPAIR')
            {
				$room['reservation_room_id'] = 0;
				$room['reservation_id'] = 0;
				$room['change_price'] = 0;
				$room['status'] = 'AVAILABLE';
				unset($room['id']);
				DB::insert('room_status',$room);
			}
            else
            {
				$house_status = ($room['house_status']=='REPAIR')?'':$room['house_status'];
                $sql = 'update 
                            room_status 
                        set 
                            room_id = '.$to_room_id.',
                            house_status=\''.$house_status.'\' 
                        where 
                            --room_status.in_date >= \''.Date_Time::to_orc_date(date('d/m/Y')).'\' 
                            --AND room_status.in_date <=\''.$record['departure_time'].'\' 
                            reservation_room_id = '.$reservation_room_id;
				DB::query($sql);
			}
		}
		$description = '
			Change room from <strong>'.$from_room_name.'</strong> to room <strong>'.$to_room_name.'</strong><br>
			Arrival time: '.$record['arrival_time'].'<br>
			Departure time: '.$record['departure_time'].'<br>
			Reason: '.Url::sget('change_room_reason').'
		';
		$portal_name = DB::fetch('select name_1 from party where user_id = \''.PORTAL_ID.'\'','name_1');
		System::log('change_room','Change room of '.$portal_name.' from <strong>'.$from_room_name.'</strong> to room <strong>'.$to_room_name.'</strong>',$description,$reservation_room_id);
	}
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$reservation_rooms = Hotel::get_reservation_room();
		if(empty($reservation_rooms)){
			$cond = '';
		}else{
			$cond = 'AND (';
			$i = 0;
			foreach($reservation_rooms as $key=>$value){
				if($i>0){
					$cond .= ' AND ';
				}
				$cond .= 'room.id <> '.$value['room_id'].'';
				$i++;
			}
			$cond .= ')';
		}
        
        //Loai luon ca may phong book trong cac phong dich' (Luan ad sua 11/6)
        $booked_room = Hotel::get_booked_room();
        
		if(empty($booked_room)){
			$cond .= '';
		}else{
			$cond .= 'AND (';
			$i = 0;
			foreach($booked_room as $key=>$value){
				if($i>0){
					$cond .= ' AND ';
				}
				$cond .= 'room.id <> '.$value['room_id'].'';
				$i++;
			}
			$cond .= ')';
		}
        
		$this->map = array();
		$this->map['from_reservation_room_id_list'] = String::get_list($reservation_rooms);
		$available_rooms = Hotel::get_available_room($cond);
        
		$this->map['to_room_id_list'] = String::get_list($available_rooms);
		$this->parse_layout('change_room',$this->map);
	}
}
?>