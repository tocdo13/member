<?php 
class MonthlyRoomReport extends Module
{
	function MonthlyRoomReport($row)
	{
		Module::Module($row);
		require_once 'db.php';
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):Date_Time::to_orc_date(date('d/m/Y',Date_Time::to_time(date('d/m/Y'))-86400*3));
		$times = Date_Time::to_time(date('d/m/Y'))+(28*86400);
		//Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_month();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):Date_Time::to_orc_date(date('d/m/Y',$times));
		MonthlyRoomReportDB::get_items($date_from,$date_to,false);
		MonthlyRoomReportDB::get_rooms(false);
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY))
			{
			    if(Url::get('cmd')=='repair' or Url::get('cmd')=='un_repair') 
                {
                    require_once 'forms/status.php';
					$this->add_form(new RoomStatus());
                    return;
                }
                
				if(Url::get('rr_id') && Url::get('cmd')=='change_room')
                {
					$end_time = Url::get('start_time') + (Url::get('nights')* 86400);
					$kt = $this->check_conflig(Url::get('rr_id'),Url::get('room_id'),Url::get('start_time'),$end_time,Url::get('act'));
					if($kt)
                    {
						$this->replace_all_room_id(Url::get('rr_id'),Url::get('room_id'),Url::get('start_time'),$end_time,Url::get('act'),Url::get('for_assign'));
                        exit();
					}
                    else
                    {
						echo 'unsucess'; exit();	
					}	
				}
                //doan xu ly chung cho checkin, checkout, assign, un_assign ...
                if(Url::get('rr_id') && Url::get('cmd')=='assign_room')
                {
                    if(Url::get('rr_id') != '' and Url::get('room_id') != '')
                    {
                        $act = Url::get('act');
                        if($act == 'assign')
                        {
                            DB::update('reservation_room',array('room_id' => Url::get('room_id')),'id = '.Url::get('rr_id'));
                            DB::update('room_status',array('room_id' => Url::get('room_id')),'reservation_room_id = '.Url::get('rr_id'));   
                        }
                        else
                        if($act == 'un_assign')
                        {
                            DB::update('reservation_room',array('room_id' => ''),'id = '.Url::get('rr_id'));
                            DB::update('room_status',array('room_id' => ''),'reservation_room_id = '.Url::get('rr_id'));
                        }
                        else
                        if($act == 'check_in')
                        {
                            $data = array();
                            $data['time_in'] = time();
                            $data['status'] = 'CHECKIN';
                            DB::update('reservation_room', $data, 'id = '.Url::get('rr_id'));
                            DB::update('room_status',array('status'=>'OCCUPIED'), 'reservation_room_id = '.Url::get('rr_id'));
                        }
                        echo 'sucess';
                        exit();
                    }
                    else
                    {
                        echo 'Oop! Error here!';
                        exit();   
                    }	
				}
				if(Url::get('id') && Url::get('status'))
                {
					$status = Url::get('status');
					DB::update('reservation_room',array('status'=>Url::get('status')),'id='.Url::get('id'));	
					if(Url::get('status') == 'CHECKIN' || Url::get('status')=='CHECKOUT')
                    {
						$status = 'OCCUPIED';
					}
					DB::update('room_status',array('status'=>$status),'reservation_room_id='.Url::get('id'));	
					Url::redirect_current();
				}
                else
                {
					require_once 'forms/report.php';
					$this->add_form(new MonthlyRoomReportForm());
				}
			}
			else
			{
				URL::access_denied();
			}
		}
	}
	function check_conflig($reservation_room_id,$to_room_id,$start, $end,$act)
    {
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
		$time_in = $record['time_in']; 
		$time_out = $record['time_out']; 
		if($act==true)
        {
			$time_in = $start; 
			$time_out = $end;
		}
        $sql = 'select
                    id
                from
                    reservation_room
                where
                    room_id = '.$to_room_id.'
                    and time_in <= '.$end.'
                    and time_out >= '.$start;
		$room_name = DB::fetch('SELECT name FROM room WHERE id = '.$to_room_id.'','name');
		//$cond = 'R.status<>\'CANCEL\' AND R.status<>\'CHECKOUT\'
//				AND R.room_id=\''.$to_room_id.'\' 
//				'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'');
//		
//		$cond .= ' AND (
//				(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_out.')
//			OR	(R.time_in >= '.$time_in.' AND R.time_out >= '.$time_out.' AND R.time_in <= '.$time_out.')
//			OR	(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_in.' AND R.time_out <= '.$time_out.')
//			OR	(R.time_in >= '.$time_in.' AND R.time_out <= '.$time_out.')
//			OR	(R.time_out = '.$time_in.')
//		)';
//		$sql = '
//			SELECT 
//				R.id,
//                R.reservation_id
//			FROM 
//				reservation_room R
//				INNER JOIN room ON room.id = R.room_id
//			WHERE 
//				'.$cond.'
//		';
        $in_date = '';
        for($i = $start; $i < $end; $i += 24*60*60)
        {
            $date = '\''.Date_Time::convert_time_to_ora_date($i).'\',';
            $in_date .= $date;
        }
        $in_date .= '\''.Date_Time::convert_time_to_ora_date($start).'\'';
        $sql = 'select
                    *
                from
                    room_status
                where
                    in_date in ('.$in_date.')
                    and room_id = '.$to_room_id.'
                    and status != \'CANCEL\'
                    and reservation_room_id != '.$reservation_room_id.'
                    and ((room_status.reservation_id != 0 and room_status.change_price != 0 and room_status.change_price is not null) or (room_status.reservation_id = 0 and house_status != \'DIRTY\'))
                ';
		//System::debug($sql);
        $reservation_room = DB::fetch_all($sql);
        //System::debug($reservation_room);
		$room_id = $record['room_id'];
		if(!empty($reservation_room) and $record['status']<>'CANCEL' and $record['status']<>'CHECKOUT')
		{
		    //$form = new Form();
			//$form->error('to_room_id',Portal::language('room').' '.$room_name.' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'#'.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
			return false;
		}
        else
        {
			return true;
		}
	}
	function replace_all_room_id($reservation_room_id,$to_room_id,$start,$end,$act,$for_assign)
    {
		//echo $reservation_room_id.'=='.$to_room_id; exit();
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
        if($record['room_id'] != '')
        {
            //echo 'adadadasssssddddddd';
            $from_room_name = DB::fetch('select name from room where id = '.$record['room_id'].'','name');
            DB::query('update reservation_room set room_id = '.$to_room_id.' where id = '.$reservation_room_id);
        }
        else
        {
            $from_room_name = 'booked without assign';
            if(Url::get('for_assign') == 1)
            {
                //echo 'he he he';
                DB::query('update reservation_room set room_id = '.$to_room_id.' where id = '.$reservation_room_id);
            }
        }
		$room_type_id = DB::fetch('select room_type_id from room where id = '.$to_room_id.'','room_type_id');
		$room_level_id = DB::fetch('select room_level_id from room where id = '.$to_room_id.'','room_level_id');
		$to_room_name = DB::fetch('select name from room where id = '.$to_room_id.'','name');
		$minibar_id = DB::fetch('select id from minibar where room_id = '.$to_room_id.'','id');
		DB::query('update reservation_room set note_change_room = \''.Url::get('note').'\' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_level_id = '.$room_level_id.' where id = '.$reservation_room_id);
		DB::query('update reservation_room set room_type_id = '.$room_type_id.' where id = '.$reservation_room_id);
		if($act==true)
        {
			DB::query('update reservation_room set time_in = '.$start.',time_out = '.$end.', arrival_time=\''.Date_Time::to_orc_date(date('d/m/y',$start)).'\',departure_time=\''.Date_Time::to_orc_date(date('d/m/y',$end)).'\'  where id = '.$reservation_room_id);
			$room_status = DB::fetch_all('select * from room_status where reservation_room_id = '.$reservation_room_id.'');
			$string = '';
			$j=0;
			foreach($room_status as $k=> $status)
            {
				if($j==0)
                {
					$string .= $k;		
				}
                else
                {
					$string .= ','.$k;	
				}
				$j++;
			}
			$t=0;
			for($i=$start;$i<=$end;$i+=86400)
            {
				$split = explode(",",$string);
				DB::query('update room_status set in_date=\''.Date_Time::to_orc_date(date('d/m/y',$i)).'\' where reservation_room_id='.$reservation_room_id.' and id='.$split[$t]);
				$t++;
			}
			DB::delete('room_status','reservation_room_id = '.$reservation_room_id.' and (in_date<\''.Date_Time::to_orc_date(date('d/m/y',$start)).'\' or in_date>\''.Date_Time::to_orc_date(date('d/m/y',$end)).'\' )');
		}
        else
        {
			$time_in = $record['arrival_time'];
			$time_out = $record['departure_time'];
		}
		DB::query('update housekeeping_invoice set MINIBAR_ID = \''.$minibar_id.'\' where type = \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$reservation_room_id);
		DB::query('update housekeeping_invoice set MINIBAR_ID = '.$to_room_id.' where type <> \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$reservation_room_id);
		if(Url::get('for_assign') == 1 or $record['room_id'] != '')
        {
            //echo 'adadadad';
            DB::query('update room_status set room_id = '.$to_room_id.' where reservation_room_id = '.$reservation_room_id);
        }
		$description = '';
//			Change room from <strong>'.$from_room_name.'</strong> to room <strong>'.$to_room_name.'</strong><br>
//			Arrival time: '.$time_in.'<br>
//			Departure time: '.$time_out.'<br>
//			Reason: '.Url::sget('change_room_reason').'
//		';
		System::log('change_room','Change room from <strong>'.$from_room_name.'</strong> to room <strong>'.$to_room_name.'</strong>',$description,$reservation_room_id);
	}
	function get_beginning_date_of_month()
    {
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_month = date('m',$time_today);
		return (Date_Time::to_orc_date('01/'.$day_of_month.'/'.date('Y',time())));
	}
	function get_end_date_of_month()
    {
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_month = date('m',$time_today);
		$num_day = cal_days_in_month(CAL_GREGORIAN,date('m',time()),date('Y',time()));
		return (Date_Time::to_orc_date($num_day.'/'.$day_of_month.'/'.date('Y',time())));
	}	
}
?>