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
		  //$this->check_conflig(Url::get('from_reservation_room_id'),Url::get('to_room_id'),$this);
    		//Co ban ghi
            if($record = DB::select_id('reservation_room',Url::get('from_reservation_room_id')))
            {
                //System::debug($record);
                //exit();
                $s_time = Date_Time::to_time(date('d/m/Y'));
				$e_time = (Date_Time::to_time(date('d/m/Y')) + 86400*6);
                $time = $s_time;
                $room_id = DB::select('reservation_room','id = '.Url::get('from_reservation_room_id'));
                //System::debug($room_id['room_id']);
                /*
                while($time<=$e_time)
                {
                    DB::insert('room_status',
    									array(
    									'room_id'=>$room_id['room_id'],
    									'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),
    									'status'=>'AVAILABLE',
    									'house_status'=>'DIRTY',
    									'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$s_time)),
    									'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$e_time))
    									));
                    $time += 3600*24;
                }
                */
                DB::insert('room_status',
    									array(
    									'room_id'=>$room_id['room_id'],
    									'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$s_time)),
    									'status'=>'AVAILABLE',
    									'house_status'=>'DIRTY',
    									'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$s_time)),
    									'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$e_time))
    									));
                
                $arrival = Date_Time::convert_orc_date_to_date($record['arrival_time'],'/');
                //Ngày chuyển = ngày đến thì làm như bth
                
                if( Date_Time::to_time($arrival) == Date_Time::to_time(date('d/m/Y')) )
                {
            		if(!$this->check_conflig(Url::get('from_reservation_room_id'),Url::get('to_room_id'),$this)){
            			$this->replace_all_room_id(Url::get('from_reservation_room_id'),Url::get('to_room_id'));
            			echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
            			echo '<script>window.setTimeout("location=\''.URL::build_current().'\'",2000);</script>';
            			exit();
            		}
                    
                }
                else //Ngày chuyển khác ngày đến thì tách thành 1 đặt phòng mới cùng recode
                {
                    $this->go_action(Url::get('from_reservation_room_id'),Url::get('to_room_id'),$this);
                }  
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
		}
        else
        {
			return false;
		}
	}
	function replace_all_room_id($reservation_room_id,$to_room_id){
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
		$from_room_name = DB::fetch('select name from room where id = '.$record['room_id'].'','name');
		$room_level_id = DB::fetch('select room_level_id from room where id = '.$to_room_id.'','room_level_id');
		$room_type_id = DB::fetch('select room_type_id from room where id = '.$to_room_id.'','room_type_id');
		$to_room_name = DB::fetch('select name from room where id = '.$to_room_id.'','name');
		$minibar_id = DB::fetch('select id from minibar where room_id = '.$to_room_id.'','id');
		//DB::query('update reservation_room set note_change_room = \''.Url::get('change_room_reason').'\' where id = '.$reservation_room_id);
		
        DB::query('update reservation_room set room_id = '.$to_room_id.' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_level_id = '.$room_level_id.' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_type_id = '.$room_type_id.' where id = '.$reservation_room_id);
		DB::query('update housekeeping_invoice set MINIBAR_ID = \''.$minibar_id.'\' where type = \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$reservation_room_id);
		DB::query('update housekeeping_invoice set MINIBAR_ID = '.$to_room_id.' where type <> \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$reservation_room_id);
		$room_status = DB::fetch_all('select * from room_status where reservation_room_id = '.$reservation_room_id.'');
		foreach($room_status as $r => $room){
			if($room['house_status'] =='REPAIR'){
				$room['reservation_room_id'] = 0;
				$room['reservation_id'] = 0;
				$room['change_price'] = 0;
				$room['status'] = 'AVAILABLE';
				unset($room['id']);
				DB::insert('room_status',$room);
			}else{
				$house_status = ($room['house_status']=='REPAIR')?'':$room['house_status'];
				DB::query('update room_status set room_id = '.$to_room_id.',house_status=\''.$house_status.'\' where room_status.in_date >= \''.Date_Time::to_orc_date(date('d/m/Y')).'\' AND room_status.in_date <=\''.$record['departure_time'].'\' AND reservation_room_id = '.$reservation_room_id);
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
    
    
    //Chuyển phòng tách thành 1 phòng mới Luanad 2506
    
    function go_action($reservation_room_id,$to_room_id,&$form)
    {
        //Lấy ra r_room cũ
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
        
        //Tên phòng cũ và mới
        $from_room_name = DB::fetch('SELECT name FROM room WHERE id = '.$record['room_id'].'','name');
        $to_room_name = DB::fetch('SELECT name FROM room WHERE id = '.$to_room_id.'','name');
        
        //Không quan tâm thời gian đến của phòng, 
        // vì kể từ ngày hiện tại đến ngày out sẽ chuyển sang 1 phòng mới (là phòng dc chọn) nhung cùng recode
        //Ðây là khoảnng tg cần check conflict
        $to_day = date('d/m/Y');
        $end_day = date('d/m/Y',$record['time_out']);
        $cond_check = '
                            AND room_status.in_date != reservation_room.departure_time    
                            AND reservation_room.status != \'CANCEL\' 
                            AND reservation_room.status != \'CHECKOUT\' 
                            AND room_status.in_date >= \''.Date_Time::to_orc_date($to_day).'\'
                            AND room_status.in_date < \''.Date_Time::to_orc_date($end_day).'\'
                            AND reservation_room.id != '.$record['id'].' 
                            AND room_status.room_id = '.$to_room_id
                        ;
        $sql = '
                    Select 
                         room_status.id,
                         reservation_room.id as reservation_room_id,
                         reservation_room.reservation_id as reservation_id,
                         to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time,
                         to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
                         reservation_room.status,
                         room.name as room_name
                    from
                         room_status 
                         inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                         inner join room on room_status.room_id = room.id
                    where
                        1=1   '.$cond_check.'
                         
                ';
        $room_status = DB::fetch($sql);
        //System::debug($sql);
        //System::debug($room_status);
        //exit();
        //nếu tồn tại bản ghi trong khoảng tg check này thì sẽ bị trùng phòng nào đó
        if($room_status = DB::fetch($sql))
        {
            $txt_error = Portal::language('conflict_of_time_with').' '.Portal::language('room').' '.$room_status['room_name'].' ('.$room_status['status'].', Arrival: '.$room_status['arrival_time'].' - Departure: '.$room_status['departure_time'].') '.Portal::language('in').' Recode '. '<a target="blank" href="?page=reservation&cmd=edit&id='.$room_status['reservation_id'].'&r_r_id = '.$room_status['reservation_room_id'].'">#'.$room_status['reservation_id'].'</a>';
            $this->error('to_room_id',$txt_error,false);
            return false;
		}
        else
        {
            //Đóng  r_room cũ
            $this->close_old_reservation_room($record,$from_room_name,$to_room_name,$to_room_id);
        }
	}
    
    function close_old_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id)
    {
        //Lấy ra các khách còn IN trong đặt phòng cũ, để chuyển sang phòng mới
        $old_reservation_traveller = DB::select_all('reservation_traveller',' status != \'CHANGE\' and status != \'CHECKOUT\' and reservation_room_id = '.$reservation_room['id']);
        
        //Thông tin update cho r_room cũ
        $reservation_room_update = array(
                            'time_out'=>time(),
                            'departure_time'=>Date_Time::to_orc_date(date('d/m/Y')),
                            'note'=>$reservation_room['note'].' '.Portal::language('close_and_change_to_room').' '.$to_room_name.' '.Portal::language('from').' '.date('d/m/Y'),
                            'status'=>'CHECKOUT',
                            'lastest_edited_user_id'=>Session::get('user_id'),
                            'lastest_edited_time'=>time(),
                            'checked_out_user_id'=>Session::get('user_id'),
                            );    
        DB::update_id('reservation_room',$reservation_room_update,$reservation_room['id']);
        
        //Thông tin update cho khách trong r_room cũ
        $reservation_traveller_update = array(
                    'departure_time'=>time(),
                    'departure_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                    'status'=>'CHECKOUT',
                    );
        DB::update('reservation_traveller',$reservation_traveller_update,' reservation_room_id = '.$reservation_room['id']);
        
        //Mở 1 r_room mới cùng recode
        $this->create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$old_reservation_traveller);
        
    }
    
    function create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$old_reservation_traveller)
    {
        //Lấy mảng chứa room_level và price của phòng mới
		$room_level = DB::fetch('select room.*, room_level.price  from room inner join room_level on room_level.id = room.room_level_id  where room.id = '.$to_room_id);
        //Room type của loại phòng mới
		$room_type_id = DB::fetch('select room_type_id from room where id = '.$to_room_id.'','room_type_id');
        
        //Mã r_room cũ
        $old_reservation_room  = $reservation_room['id'];
        //Mã room_id cũ
        $from_room_id  = $reservation_room['room_id'];
        
        //Từ r_room cũ, loại bỏ và thêm các trường để tạo 1 r_room mới
        unset($reservation_room['id']);
        $reservation_room['room_id'] = $to_room_id;
        $reservation_room['room_type_id'] = $room_type_id;
        $reservation_room['price'] = $room_level['price'];
        $reservation_room['time_in'] = time();
        $reservation_room['arrival_time'] = Date_Time::to_orc_date(date('d/m/Y'));
        $reservation_room['note'] = Portal::language('change_from_room').' '.$from_room_name.' '.Portal::language('from').' '.date('d/m/Y');
        $reservation_room['total_amount'] = 0;
        $reservation_room['former_reservation_room_id'] = $old_reservation_room;
        $reservation_room['user_id'] = Session::get('user_id');
        $reservation_room['time'] = time();
        unset($reservation_room['lastest_edited_user_id']);
        unset($reservation_room['lastest_edited_time']);
        unset($reservation_room['bill_number']);
        $reservation_room['checked_in_user_id'] = Session::get('user_id');
        $reservation_room['room_level_id'] = $room_level['room_level_id'];
        $reservation_room['note_change_room'] = Url::get('change_room_reason');
        
        //Mã r_room mới
        $new_reservation_room = DB::insert('reservation_room',$reservation_room);
        
        //Nếu có khách đang check in ở r_room cũ thì sẽ đưa sang phòng mới
        if( $old_reservation_traveller )
        {
            //Xóa bỏ các trường thùa đi
            foreach($old_reservation_traveller as $key=>$value)
            {
                unset($old_reservation_traveller[$key]['id']);
                $old_reservation_traveller[$key]['reservation_room_id'] = $new_reservation_room;
                $old_reservation_traveller[$key]['arrival_time'] = time();
                $old_reservation_traveller[$key]['arrival_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                DB::insert('reservation_traveller',$old_reservation_traveller[$key]);
            }
        }
        
        /*
        Xử lý bảng room_status : cách xử lý
        Nếu 1 phòng ở tử 1 ->10, chuyển phòng từ ngày 5, thì từ ngày 5 trở đi sẽ là room_status của phòng mới
        Tuy nhiên cần thêm 1 bản ghi ngày 5 nữa vs giá = 0 cho phòng cũ
        */
        //Bản ghi giá  = 0 cho phòng cũ
        $room_status_cut_position = DB::fetch('select * from room_status where room_id = '.$from_room_id.' and reservation_room_id = '.$old_reservation_room.' and in_date = \''.Date_Time::to_orc_date( date('d/m/Y') ).'\' ');
        
        //Up date lại cho phòng mới
        $room_status_update  = array(
                    'room_id'=>$to_room_id,
                    'change_price'=>$room_level['price'],
                    'reservation_room_id'=>$new_reservation_room,
                                );    
        $cond = '
                room_id = '.$from_room_id.'
                and reservation_room_id = '.$old_reservation_room.'
                and in_date >= \''.Date_Time::to_orc_date( date('d/m/Y') ).'\'
        ';
        //and change_price != 0
        DB::update('room_status',$room_status_update,$cond);
        
        //Thêm bản ghi giá = 0  cho phòng cũ
        unset($room_status_cut_position['id']);
        $room_status_cut_position['change_price'] = 0;
        
        DB::insert('room_status',$room_status_cut_position);
        
        //Ghi log quá trình
        $description = '
			Checkout room <strong>'.$from_room_name.'</strong>  and change to room <strong>'.$to_room_name.'</strong><br>
			Reason: '.Url::sget('change_room_reason').'<br />
            Recode: <a target="_blank" href = '.Url::build('reservation',array('cmd'=>'edit','id'=>$reservation_room['reservation_id'])).'>#'.$reservation_room['reservation_id'].'</a>
		';
		System::log('change_room','Checkout room <strong>'.$from_room_name.'</strong>  and change to room <strong>'.$to_room_name.'</strong>',$description,$reservation_room['reservation_id']);
        
        /*
        Bước cuối cùng để hoàn thiện các trường dữ liệu đó là mở 1 tab mới của recode đó ra, nhấn save lại
        để cập nhật các trường còn trống (bill number, total amount...)
        */
        //echo "<script>window.open('".Url::build("reservation",array("cmd"=>"edit","id"=>$reservation_room['reservation_id']))."');</script>";
        Url::redirect( "reservation",array("cmd"=>"edit","id"=>$reservation_room['reservation_id']) );
    }
    
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$reservation_rooms = $this->get_reservation_room();
        //System::debug($reservation_rooms);
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
		$available_rooms = $this->get_available_room($cond);
        
		$this->map['to_room_id_list'] = String::get_list($available_rooms);
		$this->parse_layout('change_room',$this->map);
	}
    
    function get_reservation_room()
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,reservation_room.room_id
				,room.name || \'(\'|| room_level.brief_name ||\')\' || \' - \' || \'Arrival : \' || to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') || \'  Departure : \'  || to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
                inner join room_level on room.room_level_id=room_level.id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
			where
				room.portal_id=\''.PORTAL_ID.'\' and reservation_room.status=\'CHECKIN\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                and reservation_room.departure_time >= \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		';
		return DB::fetch_all($sql);
	}
    
    function get_available_room($cond=false){
		$rooms = DB::fetch_all('
			SELECT
				room.*,
                room.name || \' (\'|| room_level.brief_name ||\')\'  as name
			FROM
				room
				inner join room_level on room_level.id = room.room_level_id
			WHERE
				room.portal_id = \''.PORTAL_ID.'\'
				AND (room_level.is_virtual is null OR room_level.is_virtual = 0)
				'.$cond.'
			ORDER BY
				room.name
		');
		return $rooms;
	}
}
?>