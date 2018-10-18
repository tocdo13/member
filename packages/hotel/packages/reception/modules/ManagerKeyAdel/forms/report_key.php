<?php
class ReportKeyForm extends Form
{
	function ReportKeyForm()
	{
		Form::Form('ReportKeyForm');
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
	}
	function draw()
	{
        //xoa di tat ca nhung the rac=> nhung the khong co lien ket toi reservation_room
        $this->remove_key_exception();
        //xoa di tat ca nhung key co thoi gian tao the la 45 ngay va phong da duoc checkout
        $this->remove_key_expiry_date();
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
            $now =  mktime(0,0,0,date('m'),date('d'),date('Y'));
            $start = $now -(10*24*60*60);
            $end =$now +(10*24*60*60);
            $_REQUEST['start_date'] = date('d/m/Y',$start);
            $_REQUEST['expiry_date'] = date('d/m/Y',$end);
        }
        
        $cond =$room_id!='ALL'?'reservation_room.room_id='.$room_id:'1=1';
        $room_id =$room_id!='ALL'?$room_id:0;
        
        //lay ra tat ca reservation cua 1 phong nao do
        $sql ='SELECT reservation_room.id,reservation_room.room_id,room.name,
                reservation_room.reservation_id,reservation_room.time_in,reservation_room.time_out
                FROM reservation_room INNER JOIN room ON  reservation_room.room_id=room.id 
                INNER JOIN manage_key_adel ON reservation_room.id=manage_key_adel.reservation_room_id
                WHERE '.$cond.'
                ORDER BY manage_key_adel.create_time desc';
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
        $this->map['items'] = $reservation_rooms;
        $this->list_room();
        $this->parse_layout('report_key',$this->map);
	}
 
    function list_key_r_r($r_r_id,$start=0,$end=0)
    {
        $items_key = array();
        $cond ='';
        if($start!=0 && $end!=0)
        {
            $cond .=' AND manage_key_adel.create_time >='.$start.' AND manage_key_adel.create_time <='.($end + 86400);
        }
        $sql = 'SELECT manage_key_adel.id,manage_key_adel.begin_time,
                    manage_key_adel.end_time,manage_key_adel.create_time,
                    manage_key_adel.create_user,manage_key_adel.delete_user,
                    manage_key_adel.delete_time,manage_key_adel.delete_note
                FROM manage_key_adel where manage_key_adel.reservation_room_id='.$r_r_id.$cond .'
                ORDER BY manage_key_adel.create_time desc';
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
        $sql ="SELECT manage_key_adel.id
                FROM manage_key_adel
                LEFT JOIN reservation_room ON manage_key_adel.reservation_room_id=reservation_room.id WHERE reservation_room.id is null";
        $ids = DB::fetch_all($sql);
        $conditions ='';
        foreach($ids as $key=>$value)
        {
            $conditions .='id='.$ids[$key]['id'].' OR ';
        }
        $conditions = substr($conditions,0,strlen($conditions)-3);
        if(trim($conditions)!='')
            DB::query("DELETE FROM manage_key_adel WHERE ".$conditions);
    }
    function remove_key_expiry_date()
    {
        //lay ra nhung key co create qua 45 ngay so voi ngay hien tai va trang thai phong = checkout 
        $now =  mktime(0,0,0,date('m'),date('d'),date('Y'));
        $past = $now - (90*24*60*60);
        $sql ="SELECT manage_key_adel.id
            FROM manage_key_adel 
            INNER JOIN reservation_room ON manage_key_adel.reservation_room_id=reservation_room.id AND (reservation_room.status='CHECKOUT' OR reservation_room.status='CANCEL')
            WHERE manage_key_adel.create_time<=$past";
        $ids = DB::fetch_all($sql);
        $conditions ='';
        foreach($ids as $key=>$value)
        {
            $conditions .='id='.$ids[$key]['id'].' OR ';
        }
        $conditions = substr($conditions,0,strlen($conditions)-3);
        if(trim($conditions)!='')
            DB::query("DELETE FROM manage_key_adel WHERE ".$conditions);   
    }
}
?>