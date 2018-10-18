<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function change_house_status()
    {
        if($this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                //$this->response(200, "TRUE");
                $room_id = Url::get('room_id');
                $house_status = Url::get('house_status');
                $user_id = Url::get('user_id');
                $repair_to = Url::get('repair_to');
                $start_time = $time = Date_Time::to_time(date('d/m/Y', time()));
                $end_time = Date_Time::to_time(date('d/m/Y', time()));
                if(Url::get('repair_to') && Url::get('repair_to') != "")
                {
            		$end_time = Date_Time::to_time($repair_to);	
            	}
                $room_name = DB::fetch("select name from room where id = ".$room_id,'name');
                
                $update_ready = false;
                /** repair đè nhau */
                if($house_status && $house_status == 'REPAIR')
                {
                    $cond_repair = " room_id=".$room_id." AND house_status='REPAIR' and start_date <= '".Date_Time::to_orc_date(date('d/m/Y',$end_time))."' and end_date >= '".Date_Time::to_orc_date(date('d/m/Y',$start_time))."'";
                    while($time <= $end_time)
                    {
                        $cond_1 = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' AND room_id=\''.$room_id.'\'';
                        $check_date = DB::fetch('SELECT start_date,end_date FROM room_status WHERE '.$cond_1.'');
                        if(isset($check_date['start_date']) && isset($check_date['end_date']) && $check_date['start_date']!='' && $check_date['end_date']!='')
                        {
                            $cond_repair .= " AND ( start_date = '".$check_date['start_date']."' AND end_date='".$check_date['end_date']."')";
                        }
                        $cond_repair .= " AND ( in_date != '".Date_Time::to_orc_date(date('d/m/Y',$time))."')";
                        
                        $time += 3600*24;
                    }
                    $orcl = DB::fetch_all('SELECT * FROM room_status WHERE '.$cond_repair.'');
                    if(sizeof($orcl)>0)
                    {
                        foreach($orcl as $id => $content)
                        {
                            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($content['in_date'],'/')) < $start_time)
                            {
                                DB::update('room_status',array('end_date' => Date_Time::to_orc_date(date('d/m/Y',($start_time-24*3600)))), 'id='.$content['id']);
                            }
                            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($content['in_date'],'/'))>$end_time)
                            {
                                DB::update('room_status',array('start_date' => Date_Time::to_orc_date(date('d/m/Y',($end_time+24*3600)))), 'id='.$content['id']);
                            }
                        }
                    }
                }
                //End
                $time = $start_time;
                
                while($time<=$end_time)
                {
                    $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' AND room_id=\''.$room_id.'\' AND status != \'AVAILABLE\'';
                    $sql = 'SELECT * FROM room_status WHERE '.$cond.'';
                    if($old_house_status = DB::fetch($sql))
                    { 
                        DB::update('room_status',array('note'=>'','house_status'=>''),$cond);
                    }
                    $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' AND room_id=\''.$room_id.'\' AND status = \'AVAILABLE\'';
                    $sql = 'SELECT * FROM room_status WHERE '.$cond.'';
                    if($old_house_status = DB::fetch($sql))
                    { 
                        $user_repair = ($house_status=='REPAIR')?$user_id:'';
                        $data_arr = array(
                            'note' => Url::get('note'),
                            'house_status' => $house_status,
                            'user_repair' => $user_repair,
                            'start_date' => Date_Time::to_orc_date(date('d/m/Y',$start_time)),
                            'end_date' => Date_Time::to_orc_date(date('d/m/Y',$end_time))
                        );
                        
                        DB::update('room_status', $data_arr,$cond);
                        $title = 'Update Room house_status and note room_id : '.$room_id.',room_name : '.$room_name.', Old status: ' .$old_house_status['house_status'].', New status: '.(Url::get('house_status')?Url::get('house_status'):'READY');
                        $description = ''
                            .Portal::language('house_status_note').':'.Url::get('house_status').'<br>  '
                            .Portal::language('in_date').':'.date('d/m/Y',$time).'<br>  '
                            .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                            .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
                        
                        DB::insert('log', array('type'=>"Edit", 'module_id'=>is_object(Module::$current)?Module::block_id():0,
                        'title' => $title, 'description' => $description, 'time'=>time(),'user_id'=>$user_id));
                    }else
                    {
                        $user_repair = ($house_status=='REPAIR')?$user_id:'';
                        $data_arr = array(
                            'note' => Url::get('note'),
                            'room_id' => $room_id,
                            'in_date' => Date_Time::to_orc_date(date('d/m/Y',$time)),
                            'status' => 'AVAILABLE',
                            'house_status' => $house_status,
                            'user_repair' => $user_repair,
                            'start_date' => Date_Time::to_orc_date(date('d/m/Y',$start_time)),
                            'end_date' => Date_Time::to_orc_date(date('d/m/Y',$end_time))
                        );
                        DB::insert('room_status', $data_arr);
                        $title = 'Insert Room house_status and note room_id: '.$room_id.',room_name: '.$room_name.', Status: ' .(Url::get('house_status')?Url::get('house_status'):'READY').'';
                        $description = ''
                            .Portal::language('note').':'.Url::get('note').'<br> '
                            .Portal::language('house_status_note').':'.Url::get('house_status').'<br> '
                            .Portal::language('in_date').':'.date('d/m/Y',$time).'<br> ' 
                            .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                            .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
                        DB::insert('log', array('type'=>"Edit", 'module_id'=>is_object(Module::$current)?Module::block_id():0,
                        'title' => $title, 'description' => $description, 'time'=>time(),'user_id'=>$user_id));
                    }
                    
                    $time += 3600*24;
                }
                /** repair cắt chặng */
                if($house_status == '' || $house_status =='CLEAN' || $house_status =='DIRTY')
                {
                    $cond1 =  ' room_id = '.$room_id.' 
                                        and house_status = \'REPAIR\' 
                                        and start_date <= \''.Date_Time::to_orc_date(Url::sget('in_date')).'\'  
                                        and end_date >= \''.Date_Time::to_orc_date(Url::sget('in_date')).'\' 
                                    ';
                    if($repairs = DB::fetch_all('select * from room_status where '.$cond1 ))
                    {
                        
                        foreach($repairs as $key=>$value)
                        {
                            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],"/"))< Date_Time::to_time(Url::sget('in_date')))
                            {
                                
                                DB::update('room_status',array('end_date' => Date_Time::to_orc_date(date('d/m/Y',Date_Time::to_time(Url::sget('in_date'))-86400))), 'id='.$value['id']);
                            }
                            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],"/")) > $end_time)
                            {
                                DB::update('room_status',array('start_date'=>Date_Time::to_orc_date(date('d/m/Y', $end_time+86400))),'id='.$value['id']);
                            }
                        }
                    }
                }
                
                if(Url::get('type') == 'IOS')
                {
                    $this->response(200, json_encode(array('status' => 'TRUE')));
                }else
                {
                    $this->response(200, "TRUE");
                }
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
}   
$api = new api();
?>