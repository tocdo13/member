<?php
class ReadKeyForm extends Form
{
	function ReadKeyForm()
	{
		Form::Form('ReadKeyForm');
	}	
    function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:80px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}
    
	function draw()
	{
        //goi ham doc the tra ve 1 mang cac gia tri can thiet: cardNo,GuestSN,GuestIndex,Room,BeginTime,EndTime
        if(isset($_REQUEST['read']))
        {
            $this->action_read();
        }
        else
        {
            $this->show_read('');
        }
	}
    function action_read()
    {
        require_once 'packages/hotel/packages/reception/modules/ManagerKeySalto/db.php';
        $encoder = $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        $ip_port = DB::fetch("select * from manage_ipsever where id=".$str_encoder[0]);
        
        $arr = array();
        $arr[0] = 'LT';
        $arr[1] = $str_encoder[0];
        $arr[2] = 'E';
        $in = generate_card($arr);
        $data ='';
        $num_result = proccess_cmd($in,$ip_port['ip'],$ip_port['port'],$data);
        if($num_result!=0)
        {
            //hien thi thong bao loi
            $str_result =display_error_socket($num_result);
            $this->show_read($str_result);
        }                           
        else
        {
            $str_result = proccess_data($data);
            if($str_result!='success')
            {
                //hien thi thong bao loi
                $this->show_read($str_result);
            }
            
            else
            {
                //hien thi thong tin card
                $this->display_card($data);
            }
        }                                                                                                                                                                                                                                                                                                                                       
    }
    function show_read($result)
    {
        //echo date('d/m/Y H:i:s',1421816400);
        $this->map = array();
        //lay ra thong tin encoder s
        $db_items = DB::fetch_all("select 
                                        id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever 
                                    order by reception desc");
		$reception_id_options = '';
        //echo $_REQUEST['reception_id'];
        //exit();
		foreach($db_items as $item)
		{
            if(isset($_REQUEST['reception_id']))
            {
                if($_REQUEST['reception_id']==$item['id'])
                    $reception_id_options .= '<option value="'.$item['id'].'" selected="true">'.$item['name'].'</option>';
                else
                    $reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
            }
            else
	           $reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $this->map['reception_id'] = $reception_id_options;
        $this->map['result'] = $result;
        //end lay ra thong tin encoder
        $this->parse_layout('show_read',$this->map);
    }
   
    function display_card($receive)
    {
        //$receive='³LT³4³101³102³³³CI³2³13³1455051114³14201114³PMS³';
        $arr_result = explode('³',$receive);
        $len = count($arr_result);
        unset($arr_result[0]);
        unset($arr_result[$len-1]);
        
        $door_id = $arr_result[3];
        $start_time  = $arr_result[10];
        $end_time = $arr_result[11];
        $can_open_rooms ='';
        if(trim($arr_result[4])!='')
            $can_open_rooms .=$arr_result[4].' ';
        if(trim($arr_result[5])!='')
            $can_open_rooms .=$arr_result[5].' ';
            
        if(trim($arr_result[6])!='')
            $can_open_rooms .=$arr_result[6].' ';
            
        $status = '';
        if(trim($arr_result[7])=='CI')
            $status='Check-In';
        else
            $status='Check-Out';
        
        
        $e_time ='';
        $s_end ='';
        
        if(strlen($end_time)==8)
        {
            $e_time = substr($end_time,0,2);
            $e_time .=':00';
            $s_end = substr($end_time,2,2).'/'.substr($end_time,4,2).'/20'.substr($end_time,6,2);
        }
        else
        {
            $e_time = substr($end_time,0,2);
            $e_time .=':'.substr($end_time,2,2);
            $s_end =substr($end_time,4,2).'/'.substr($end_time,6,2).'/20'.substr($end_time,8,2);
        }
        //lay ra reservation tuong ung theo doorid va thoi gian tao the 
        $room_id =DB::fetch("SELECT room_id FROM manage_doorid WHERE door_id=$door_id");
        $room_id = $room_id['room_id'];
        
        $date_end = explode("/",$s_end);
        $time_end = explode(":",$e_time);
        
        $end_time_s = mktime($time_end[0],$time_end[1],0,$date_end[1],$date_end[0],$date_end[2]);
        $past = $end_time_s - 600;
        $toward = $end_time_s + 600;
        
        $sql ="SELECT reservation_room.reservation_id,reservation_room.time_in,
                reservation_room.time_out
            FROM manage_key_salto 
            INNER JOIN reservation_room ON reservation_room.room_id=$room_id AND manage_key_salto.reservation_room_id=reservation_room.id
            AND manage_key_salto.end_time>=$past AND  manage_key_salto.end_time<=$toward";
        
        $reservation_room = DB::fetch($sql);
        //SYstem::debug($reservation_room);
        if(!empty($reservation_room))
        {
            $recode = $reservation_room['reservation_id'];
            $arrival = date('d/m/Y',$reservation_room['time_in']);
            $departure = date('d/m/Y',$reservation_room['time_out']);
        }
        else
        {
            $recode = "";
            $arrival = "";
            $departure = "";
        }
        
        $this->map = array();
        $this->map['result'] = array('door_id'=>$door_id,'recode'=>$recode,'arrival'=>$arrival,'departure'=>$departure,'status'=>$status,'can_open_rooms'=>$can_open_rooms,'e_time'=>$e_time,'s_end'=>$s_end);
        
        $this->parse_layout('read',$this->map);
    }
}
?>