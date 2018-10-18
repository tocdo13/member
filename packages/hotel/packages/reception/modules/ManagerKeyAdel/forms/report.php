<?php
class ReportForm extends Form
{
	function ReportForm()
	{
		Form::Form('ReportForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->map = array();
	}	
    function on_submit()
	{
	   
        if(isset($_REQUEST['get_delete']) && $_REQUEST['get_delete']!='')
        {
            if(!empty($_REQUEST['check_list']))
            {
                $conditions ='';
                for($i=0;$i<count($_REQUEST['check_list']);$i++)
                {
                    if($i==(count($_REQUEST['check_list']) -1))
                    {
                        $conditions .=' id='.$_REQUEST['check_list'][$i];
                    }
                    else
                    {
                        $conditions .=' id='.$_REQUEST['check_list'][$i].' OR';
                    }
                }
                DB::delete('manage_key_adel',$conditions);
            }
        }
        else
        {
            $room_id = $_REQUEST['room_id'];
            $start_date = explode("/",$_REQUEST['start_date']);
            $expiry_date = explode("/",$_REQUEST['expiry_date']);
            $start = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);//H,i,s,m,d,Y
            $end = mktime(0,0,0,$expiry_date[1],$expiry_date[0],$expiry_date[2]);
            Url::redirect_current(array('cmd'=>'report','room_id'=>$room_id,'start'=>$start,'expiry'=>$end));    
        }
	}
	function draw()
	{
        if(isset($_REQUEST['r_r_id']))//lay ra list key cua 1 reservation_room
        {
            //lay ra reservation_room=> truong hop click 1 reservation_room
            $this->map['reservation_room'] = $this->get_reservation_room($_REQUEST['r_r_id']);
           // System::debug($this->map['reservation_room']);
            //lay ra danh sach cac the cho 1 reservation_room
            
            $this->map['items_key'] = $this->list_key_r_r($_REQUEST['r_r_id'],0,0);
        }
        else//truong hop co nhieu reservation
        {
            $_REQUEST['room_id'] = Url::get('room_id');
            $_REQUEST['start_date'] = date('d/m/Y',Url::get('start'));
            $_REQUEST['expiry_date'] = date('d/m/Y',Url::get('expiry'));
            
            $items = $this->list_reservation_room_keys(Url::get('room_id'),Url::get('start'),Url::get('expiry'));
            $this->map['items'] = $items;
        }
        //lay ra danh sach tat ca cac phong        
        $this->list_room();
        $this->parse_layout('report',$this->map);
	}
    
    function get_reservation_room($r_r_id)
    {
       // $r_r_id = $_REQUEST['r_r_id'];
        //lay ra reservation_room
        $sql ='SELECT reservation_room.id,room.name,room.id as room_id,
                reservation_room.time_in,reservation_room.time_out,
                reservation_room.reservation_id
                FROM reservation_room INNER JOIN room ON reservation_room.id='.$r_r_id.' AND reservation_room.room_id=room.id ';
        $reservation_room = DB::fetch($sql);
        $_REQUEST['room_id'] = $reservation_room['room_id'];
        $reservation_room['time_in'] = date('d/m/Y H:i',$reservation_room['time_in']);
        $reservation_room['time_out'] = date('d/m/Y H:i',$reservation_room['time_out']);
        return $reservation_room;
    }
    function list_key_r_r($r_r_id,$start=0,$end=0)
    {
        $items_key = array();
        $cond ='';
        if($start!=0 && $end!=0)
        {
            $cond .=' AND manage_key_adel.begin_time >='.$start.' AND manage_key_adel.end_time <='.($end + 86400);
        }
        $sql = 'SELECT manage_key_adel.id,manage_key_adel.begin_time,
                    manage_key_adel.end_time,manage_key_adel.create_time,
                    manage_key_adel.create_user,manage_key_adel.delete_user,
                    manage_key_adel.delete_time,manage_key_adel.delete_note
                FROM manage_key_adel where manage_key_adel.reservation_room_id='.$r_r_id.$cond .'
                ORDER BY manage_key_adel.id';
        $items_key = DB::fetch_all($sql);
        if(!empty($items_key))
        {
            $i=1;
            $flag = false;
            foreach($items_key as $key=>$value)
            {
                if($flag==false)
                {
                    $flag = true;
                    $_REQUEST['start_date'] = date('d/m/Y',$items_key[$key]['begin_time']);
                    $_REQUEST['expiry_date'] = date('d/m/Y',$items_key[$key]['end_time']) ;
                }
                $items_key[$key]['index'] = $i++;
                $items_key[$key]['begin_time'] = date('d/m/Y H:i',$items_key[$key]['begin_time']); 
                $items_key[$key]['end_time'] = date('d/m/Y H:i',$items_key[$key]['end_time']) ;
                $items_key[$key]['create_time'] = date('d/m/Y H:i',$items_key[$key]['create_time']);
                if(isset($items_key[$key]['delete_time']))
                    $items_key[$key]['delete_time'] = date('d/m/Y H:i',$items_key[$key]['delete_time']);          
            } 
        }
        return $items_key;
    }
    function list_room()
    {
        $rooms = DB::fetch_all('Select id,name FROM room ORDER BY id');
        $this->map['room_id_list'] = array('ALL'=>Portal::language('All'))+String::get_list($rooms);
    }
    function list_reservation_room_keys($room_id,$start,$end)
    {
        $cond = $room_id!='ALL'?'reservation_room.room_id='.$room_id:'1=1';
        //lay ra tat ca reservation cua 1 phong nao do
        $sql ='SELECT reservation_room.id,reservation_room.room_id,room.name,
                reservation_room.reservation_id,reservation_room.time_in,reservation_room.time_out
                FROM reservation_room INNER JOIN room ON '.$cond.' AND reservation_room.room_id=room.id ';
        
        $reservation_rooms = DB::fetch_all($sql);
        
        foreach($reservation_rooms as $key=>$value)
        {
            $reservation_room_id = $reservation_rooms[$key]['id'];
            $reservation_rooms[$key]['time_in'] = date('d/m/Y H:i',$reservation_rooms[$key]['time_in']);
            $reservation_rooms[$key]['time_out'] = date('d/m/Y H:i',$reservation_rooms[$key]['time_out']);
            $items_key = $this->list_key_r_r($reservation_room_id,$start,$end);
            
            if(empty($items_key))
                unset($reservation_rooms[$key]);
            else
                $reservation_rooms[$key]['items_key'] = $items_key;
        }
       // System::debug($reservation_rooms);
        return $reservation_rooms;
    }
}
?>