<?php 
class RoomMap120118 extends Module
{
	function RoomMap120118($row)
	{
        /** start: KID them ham kiem tra repair phong co booking tuong lai **/
        if(Url::get('room_id'))
        {
            if(Url::get('house_status')=='REPAIR')
            {
                if(Url::get('repair_to'))
                {
                    if(DB::exists('select id from room_status where status=\'BOOKED\' and room_id='.Url::get('room_id').' and in_date <=\''.Date_Time::to_orc_date(Url::get('repair_to')).'\' and in_date >=\''.Date_Time::to_orc_date(Url::get('in_date')).'\''))
                    {
                        echo 'false';
                    }
                    else
                    {
                        echo 'true';
                    }
                    exit();
                }
            }
        }
        /** end: KID them ham kiem tra repair phong co booking tuong lai **/
		Module::Module($row);
		if(User::is_login()){
			require_once 'packages/core/includes/utils/time_select.php';
			$year = get_time_parameter('year', date('Y'), $end_year);
			$end_year=$end_year?$end_year:$year;
			$month = get_time_parameter('month', date('m'), $end_month);
			$day = get_time_parameter('day', date('d'), $end_day);
			$time = strtotime($month.'/'.$day.'/'.$year);
			$end_time = strtotime($end_month.'/'.$end_day.'/'.$end_year);
			if(URL::get('cmd')=='set_extra_bed' and URL::get('room_ids'))
			{
				$room_ids = explode(',',URL::get('room_ids'));
				
				foreach($room_ids as $room_id)
				{
					$t = $time;
					while($t<=$end_time)
					{
						if($room_status = DB::select('room_status','room_id="'.$room_id.'" and date="'.date('d-m-Y',$t).'"'))
						{
							DB::update('room_status',array('extra_bed'=>URL::get('extra_bed')),'room_id="'.$room_id.'" and in_date="'.date('d-m-Y',$t).'"');
						}
						$t += 24*3600;
					}
				}
				URL::redirect_current(array('day','month','year'));
			}
			else
			if(URL::get('cmd')=='set_out_of_service' and URL::get('room_ids'))
			{
				$room_ids = explode(',',URL::get('room_ids'));
				
				foreach($room_ids as $room_id)
				{
					$t = $time;
					while($t<=$end_time)
					{
						if($room_status = DB::select('room_status','room_id="'.$room_id.'" and in_date="'.date('d-m-Y',$t).'"'))
						{
							DB::update('room_status',array('out_of_service'=>URL::get('out_of_service')),'room_id="'.$room_id.'" and in_date="'.date('d-m-Y',$t).'"');
						}
						else
						{
							DB::insert('room_status',array(
								'room_id'=>$room_id,
								'in_date'=>date('d-m-Y',$t),
								'status'=>'AVAILABLE',
								'out_of_service'=>URL::get('out_of_service')
							));
						}
						$t += 24*3600;
					}
				}
				URL::redirect_current(array('day','month','year'));
			}
			else if(URL::get('act')== 'check_availability'){
				require_once 'forms/room_availability.php';
				$this->add_form(new RoomAvailabilityForm());
			}
			else
			{
				require_once 'forms/room_map.php';
				$this->add_form(new RoomMap120118Form());
			}
		}else{
			Url::redirect('sign_in');
		}
	}
}
?>