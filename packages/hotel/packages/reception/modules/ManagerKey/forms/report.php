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
                DB::delete('manage_key',$conditions);
            }
        }
	}
	function draw()
	{
	   //xoa di tat ca nhung the rac=> nhung the khong co lien ket toi reservation_room
        $this->remove_key_exception();
        //xoa di tat ca nhung key co thoi gian tao the la 45 ngay va phong da duoc checkout
        $this->remove_key_expiry_date();
        
        $this->map = array();
        //$items = array();
        if(Url::get('room_id'))
            $room_id =Url::get('room_id');
        else
            $room_id ='ALL';

        if(Url::get('start_date') && Url::get('expiry_date'))
        {
            $s_start = explode("/",Url::get('start_date'));
            $start = mktime(0,0,0,$s_start[1],$s_start[0],$s_start[2]);
            
            $s_end = explode("/",Url::get('expiry_date'));
            $end = mktime(0,0,0,$s_end[1],$s_end[0],$s_end[2]);
        }
        else
        {
            $start = 0;
            $end =0;
            
            $now =  mktime(0,0,0,date('m'),date('d'),date('Y'));
            $past = $now -(10*24*60*60);
            $toward = $now +(10*24*60*60);
            $_REQUEST['start_date'] = date('d/m/Y',$past);
            $_REQUEST['expiry_date'] = date('d/m/Y',$toward);
        }
        
        $cond = $room_id!='ALL'?'reservation_room.room_id='.$room_id:'1=1';
        $room_id =$room_id!='ALL'?$room_id:0;
        
        
        $sql ="
                SELECT reservation_room.id,
                    reservation_room.room_id,room.name,
                    reservation_room.reservation_id,
                    reservation_room.time_in,
                    reservation_room.time_out
                FROM reservation_room INNER JOIN room ON reservation_room.room_id=room.id 
                INNER JOIN manage_key ON reservation_room.id=manage_key.reservation_room_id
                WHERE $cond
                ORDER BY manage_key.create_time desc
                ";
        $reservation_rooms = DB::fetch_all($sql);
        
        //System::debug($reservation_rooms);
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
        $this->map['items'] = $reservation_rooms;
        
        //lay ra danh sach tat ca cac phong        
        $this->list_room();
        
        $this->parse_layout('report',$this->map);
	}
    function list_key_r_r($r_r_id,$start=0,$end=0)
    {
        $items_key = array();
        $cond ='';
        if($start!=0 && $end!=0)
        {
            $cond .=' AND manage_key.begin_time >='.$start.' AND manage_key.end_time <='.($end + 86400);
        }
        else
        {
            $now =  mktime(0,0,0,date('m'),date('d'),date('Y'));
            $past = $now -(10*24*60*60);
            $toward = $now +(10*24*60*60);
            $cond .=" AND manage_key.begin_time >=$past AND manage_key.end_time <=$toward";
        }
        
        $sql = 'SELECT manage_key.id,manage_key.begin_time,manage_key.end_time,manage_key.create_time,
                manage_key.create_user,manage_key.delete_user,manage_key.delete_time,manage_key.delete_note
                FROM manage_key where reservation_room_id='.$r_r_id.$cond;
        $items_key = DB::fetch_all($sql);
        if(!empty($items_key))
        {
            $i=1;
            foreach($items_key as $key=>$value)
            {
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
    function remove_key_exception()
    {
        $sql ="SELECT manage_key.id
                FROM manage_key
                LEFT JOIN reservation_room ON manage_key.reservation_room_id=reservation_room.id WHERE reservation_room.id is null";
        $ids = DB::fetch_all($sql);
        $conditions ='';
        foreach($ids as $key=>$value)
        {
            $conditions .='id='.$ids[$key]['id'].' OR ';
        }
        $conditions = substr($conditions,0,strlen($conditions)-3);
        if(trim($conditions)!='')
            DB::query("DELETE FROM manage_key WHERE ".$conditions);
    }
    function remove_key_expiry_date()
    {
        //lay ra nhung key co create qua 45 ngay so voi ngay hien tai va trang thai phong = checkout 
        $now =  mktime(0,0,0,date('m'),date('d'),date('Y'));
        $past = $now - (46*24*60*60);
        $sql ="SELECT manage_key.id
            FROM manage_key 
            INNER JOIN reservation_room ON manage_key.reservation_room_id=reservation_room.id AND (reservation_room.status='CHECKOUT' OR reservation_room.status='CANCEL')
            WHERE manage_key.create_time<=$past";
        $ids = DB::fetch_all($sql);
        $conditions ='';
        foreach($ids as $key=>$value)
        {
            $conditions .='id='.$ids[$key]['id'].' OR ';
        }
        $conditions = substr($conditions,0,strlen($conditions)-3);
        if(trim($conditions)!='')
            DB::query("DELETE FROM manage_key WHERE ".$conditions);   
    }
    
}
?>