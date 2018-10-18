<?php
class RoomStatus extends Form{
	function RoomStatus(){
		Form::Form('RoomStatus');
        $this->link_js('packages/core/includes/js/multi_items.js');
	}
    
    function on_submit()
	{
        foreach($_REQUEST['room_repair'] as $k => $v)
        {
            $status = $v['status']!="READY"?$v['status']:"";
            $this->setStatus($v['room_id'],$v['start_date'],$v['end_date'],$v['note'],$status);
        }
        DB::query('delete from room_status where reservation_id = 0 and house_status is null and note is null');
        echo "<script>
                opener.location.reload(true);
                window.close();
            </script>";
	}
    
	function draw()
    {
		$this->map = array();
        
        $status = $_REQUEST['cmd']=="repair"?"REPAIR":"READY";
        
        $regist = explode("|",$_REQUEST['rooms']);
        $rooms_r = array();
        if(isset($regist))
        {
            foreach($regist as $k => $v)
            {
                $temp = explode(",",$v);
                $rooms_r[$k]['id'] = $k;
                $rooms_r[$k]['room_id'] = $temp[0];
                $rooms_r[$k]['room_name'] = DB::fetch("select name as name from room where id =".$temp[0],"name");
                $rooms_r[$k]['start_date'] = $temp[1];
                $rooms_r[$k]['end_date'] = $temp[2];
                $rooms_r[$k]['status'] = $status;
            }
        }
        
        if(!isset($_REQUEST['room_repair']))
		{
			$_REQUEST['room_repair'] = $rooms_r;
		}
        
		$this->parse_layout('status',$this->map);		
	}
    
    function setStatus($room_id, $start_date, $end_date, $note, $status)
	{
        $today = Date_Time::to_time(date('d/m/Y'));
        $start_time = Date_Time::to_time($start_date);
        if($start_time < $today)
            $start_time = $today;
        $end_time = Date_Time::to_time($end_date);
        $time = $start_time;
        if($start_time > $end_time)
            return;
            
        /** START - THANH add phan chan repair khi booked het hang phong **/
        $alert = "";
        if($status=="REPAIR"){        
            $sql = "SELECT room.id as id, room.room_level_id FROM room INNER JOIN room_level ON room.room_level_id = room_level.id WHERE room.id=".$room_id." AND room_level.portal_id='".PORTAL_ID."'";
            $room_level_id = DB::fetch($sql);                    
            require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
            $extra_cond =' rl.id = \''.$room_level_id['room_level_id'].'\'';
            $start_time_temp = Date_Time::to_time($start_date);
            
            $end_time_temp = Date_Time::to_time($end_date)+3600*24;
            $room_levels = check_availability('',$extra_cond,$start_time_temp,$end_time_temp);
            foreach($room_levels as $key=>$value){
                if($key==100000)
                    continue;
                else{
                    if($value['min_room_quantity']<=0){
                        if(empty($alert)){
                            $alert.="<script>alert(\"Thao tac that bai do : \\n";
                        }
                        $alert.="\\t + Hang phong ".$value['name']." da duoc booked het trong khoang thoi gian tu ".Date("H:i:s d/m/Y",$start_time_temp)." den ".Date("H:i:s d/m/Y",$end_time_temp)."\\n";
                    }
                }    
            }
           if(!empty($alert)){
                $alert.="\")</script>";
                return $alert;
            } 
        }                                   
        /** END - THANH add phan chan repair khi booked het hang phong **/
        
        
        $top_status;
        $top_date_start;
        $top_date_end;
        $mid_status=$status;
        $mid_date_start=$start_time;
        $mid_date_end=$end_time;
        $bot_status;
        $bot_date_start;
        $bot_date_end;     
        $checkTop = DB::fetch("select HOUSE_STATUS, date_to_unix(start_date) as date_start, date_to_unix(end_date) as date_end
                        from room_status
                        where in_date='".Date_Time::to_orc_date(date('d/m/Y',$start_time - 3600*24))."' 
                        AND room_id='".$room_id."'");
        $checkBot = DB::fetch("select HOUSE_STATUS, date_to_unix(start_date) as date_start, date_to_unix(end_date) as date_end
                        from room_status
                        where in_date='".Date_Time::to_orc_date(date('d/m/Y',$end_time + 3600*24))."' 
                        AND room_id='".$room_id."'");         
        if(isset($checkTop))
        {
            
            if(isset($checkTop['date_start']))
            {
                $top_status = $checkTop['house_status'];
                $top_date_start = $checkTop['date_start'];
                $top_date_end = $start_time - 3600*24;
            }
            else
                unset($top_status);
        }
        if(isset($checkBot))
        {
            if(isset($checkBot['date_end']))
            {
                $bot_status = $checkBot['house_status'];
                $bot_date_start = $end_time + 3600*24;
                $bot_date_end = $checkBot['date_end'];
            }
            else
                unset($bot_status);
        }
                                
        if(isset($top_status) and $top_status == $mid_status)
        {
            $top_date_end = $mid_date_end;
            $mid_date_start = $top_date_start;
            if(isset($bot_status) and $mid_status == $bot_status)
            {
                $top_date_end = $bot_date_end;
                $mid_date_end = $bot_date_end;
                $bot_date_start = $top_date_start;
            }
        }
        else
        {
            if(isset($bot_status) and $mid_status == $bot_status)
            {
                $mid_date_end = $bot_date_end;
                $bot_date_start = $mid_date_start;
            }
        }    
        if(isset($top_status) and $top_status)
        {
            $time = $top_date_start;
            while($time<$start_time)
            {
                $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
                    AND room_id=\''.$room_id.'\'';
                DB::update('room_status',
                    array(
                        'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$top_date_start)),
                        'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$top_date_end)),
                        'note'=>$note
                    ),
                    $cond
                );
                $time += 3600*24;
            }
        }
                                
        if(isset($bot_status) and $bot_status)
        {
            $time = $end_time + 3600*24;
            while($time<=$bot_date_end)
            {
                $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
                    AND room_id=\''.$room_id.'\'';
                DB::update('room_status',
                    array(
                        'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$bot_date_start)),
                        'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$bot_date_end)),
                        'note'=>$note
                    ),
                    $cond
                );
                $time += 3600*24;
            }
        }
                                
        $time = $start_time;
        
        while($time<=$end_time)
        {
            $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
				AND room_id=\''.$room_id.'\' AND status != \'AVAILABLE\'';
            $sql = 'SELECT * FROM room_status WHERE '.$cond.'';
            if($old_house_status = DB::fetch($sql))
			{ 
                DB::update('room_status',
					array(
					'note'=>'',
					'house_status'=>''
					),
					$cond
			    );
            }
            $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
				AND room_id=\''.$room_id.'\' AND status = \'AVAILABLE\'';
            /*
            $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
                AND room_id=\''.$room_id.'\'';
            */
            /** START th�m th�ng tin t�n ph�ng cho ph?n log d?i tr?ng th�i ph�ng**/
            $room_name = DB::fetch("select name from room where id = ".$room_id,'name');
            /** END th�m th�ng tin t�n ph�ng cho ph?n log d?i tr?ng th�i ph�ng**/
            $sql = 'SELECT * FROM room_status WHERE '.$cond.'';
            if(DB::exists($sql))
            {
                $old_house_status = DB::fetch($sql);
                DB::update('room_status',
                    array(
                        'house_status'=>$status,
                        'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$mid_date_start)),
                        'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$mid_date_end)),
                        'note'=>$note
                    ),
                    $cond
                );
                /** START th�m th�ng tin t�n ph�ng cho ph?n log d?i tr?ng th�i ph�ng**/
                $title = '(THSDP) Update Room house_status and note room_id : '.$room_id.',room_name : '.$room_name.', Old status: ' .$old_house_status['house_status'].', New status: '.($status!=''?$status:'READY');
				/** END th�m th�ng tin t�n ph�ng cho ph?n log d?i tr?ng th�i ph�ng**/
                $description = ''
                .Portal::language('house_status_note').':'.$status.'<br>  '
				.Portal::language('in_date').':'.date('d/m/Y',$time).'<br>  '
                .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
				System::log('edit',$title,$description,$room_id);
            }else
            {
                DB::insert('room_status',
                    array(
                        'room_id'=>$room_id,
                        'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),
                        'status'=>'AVAILABLE',
                        'house_status'=>$status,
                        'start_date'=>Date_Time::to_orc_date(date('d/m/Y',$mid_date_start)),
                        'end_date'=>Date_Time::to_orc_date(date('d/m/Y',$mid_date_end)),
                        'note'=>$note
                    )
                );
                /** START th�m th�ng tin t�n ph�ng cho ph?n log d?i tr?ng th�i ph�ng**/
                $title = '(THSDP)Insert Room house_status and note room_id: '.$room_id.',room_name: '.$room_name.', Status: ' .($status!=''?$status:'READY').'';
				/** END th�m th�ng tin t�n ph�ng cho ph?n log d?i tr?ng th�i ph�ng**/
                $description = ''
                .Portal::language('note').':'.$note.'<br> '
                .Portal::language('house_status_note').':'.$status.'<br> '
				.Portal::language('in_date').':'.date('d/m/Y',$time).'<br> ' 
                .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
				System::log('edit',$title,$description,$room_id);
            }
            $time += 3600*24;
        }
    }
}
?>
