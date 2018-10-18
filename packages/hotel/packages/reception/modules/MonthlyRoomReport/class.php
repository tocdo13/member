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
		MonthlyRoomReportDB::get_items($date_from,$date_to,false,$cond_order='');
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
                    require_once 'packages/hotel/packages/reception/modules/Reservation/forms/change_room.php';
                    $change_room = new ChangeRoomForm();
                    echo $change_room->change_room(Url::get('rr_id'),Url::get('room_id'),Url::get('use_old_price',0),Url::sget('change_room_reason'));
                    exit();
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
