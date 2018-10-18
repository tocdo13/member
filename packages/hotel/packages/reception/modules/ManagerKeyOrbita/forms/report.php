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
                DB::delete('manage_key_fox',$conditions);
            }
        }
       
	}
	function draw()
	{
	   //xoa di tat ca nhung the rac=> nhung the khong co lien ket toi reservation_room
        //$this->remove_key_exception();
        //xoa di tat ca nhung key co thoi gian tao the la 45 ngay va phong da duoc checkout
        //$this->remove_key_expiry_date();
        
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
        
        $commdoors = DB::fetch_all("select * from manage_commdoor");
        //lay ra tat ca reservation cua 1 phong nao do
        $sql ="SELECT reservation_room.id,reservation_room.room_id,room.name,
                reservation_room.reservation_id,reservation_room.time_in,reservation_room.time_out
                FROM reservation_room INNER JOIN room ON reservation_room.room_id=room.id  
                INNER JOIN manage_key_fox ON reservation_room.id=manage_key_fox.reservation_room_id
                WHERE $cond AND room.portal_id='".PORTAL_ID."'
                ORDER BY manage_key_fox.create_time desc";
        $reservation_rooms = DB::fetch_all($sql);
        //System::debug($reservation_rooms);
        foreach($reservation_rooms as $key=>$value)
        {
            $reservation_room_id = $reservation_rooms[$key]['id'];
            $reservation_rooms[$key]['time_in'] = date('d/m/Y H:i',$reservation_rooms[$key]['time_in']);
            $reservation_rooms[$key]['time_out'] = date('d/m/Y H:i',$reservation_rooms[$key]['time_out']);
            $items_key = $this->list_key_r_r($reservation_room_id,$start,$end,$commdoors);
            
            if(empty($items_key))
                unset($reservation_rooms[$key]);
            else
                $reservation_rooms[$key]['items_key'] = $items_key;
        }
        $this->map['items'] = $reservation_rooms;
        $this->list_room();
        //System::debug($reservation_rooms);
        //System::debug($this->map['room_id_list']);
        $this->parse_layout('report',$this->map);
	}
    
    
    function list_key_r_r($r_r_id,$start=0,$end=0,$commdoors)
    {
        $items_key = array();
        $cond ='';
        if($start!=0 && $end!=0)
        {
            $cond .=' AND manage_key_fox.create_time >='.$start.' AND manage_key_fox.create_time <='.($end + 86400);
        }
        $sql = 'SELECT manage_key_fox.id,
                    manage_key_fox.begin_time,
                    manage_key_fox.end_time,
                    manage_key_fox.create_time,
                    manage_key_fox.create_user,
                    manage_key_fox.checkout_user,
                    manage_key_fox.checkout_time,
                    manage_key_fox.commdoor
                FROM manage_key_fox where manage_key_fox.reservation_room_id='.$r_r_id.$cond .'
                ORDER BY manage_key_fox.create_time desc';
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
                
                $commdoor = "";
                foreach($commdoors as $k => $v)
                {
                    if(strpos("A".$value["commdoor"], $v['stt']))
                    {
                        if($commdoor != "")
                        {
                            $commdoor .= "<br/>+ ".$v['name'];
                        }
                        else
                        {
                            $commdoor .= "+ ".$v['name'];
                        }
                    }
                }
                
                $items_key[$key]['commdoor'] = $commdoor;
                
                if(isset($items_key[$key]['checkout_time']))
                    $items_key[$key]['checkout_time'] = date('d/m/Y H:i',$items_key[$key]['checkout_time']);          
            } 
        }
        return $items_key;
    }
    function list_room()
    {
        $rooms = DB::fetch_all("Select room.id,room.name FROM room 
                                INNER JOIN manage_doorid ON room.id=manage_doorid.room_id AND room.portal_id='".PORTAL_ID."' 
                                ORDER BY room.id");
        $this->map['room_id_list'] = array('ALL'=>Portal::language('All'))+String::get_list($rooms);
    }
    function remove_key_exception()
    {
        $sql ="SELECT manage_key_fox.id
                FROM manage_key_fox
                LEFT JOIN reservation_room ON manage_key_fox.reservation_room_id=reservation_room.id WHERE reservation_room.id is null";
        $ids = DB::fetch_all($sql);
        $conditions ='';
        foreach($ids as $key=>$value)
        {
            $conditions .='id='.$ids[$key]['id'].' OR ';
        }
        $conditions = substr($conditions,0,strlen($conditions)-3);
        if(trim($conditions)!='')
            DB::query("DELETE FROM manage_key_fox WHERE ".$conditions);
    }
    function remove_key_expiry_date()
    {
        //lay ra nhung key co create qua 45 ngay so voi ngay hien tai va trang thai phong = checkout 
        $now =  mktime(0,0,0,date('m'),date('d'),date('Y'));
        $past = $now - (30*24*60*60);
        $sql ="SELECT manage_key_fox.id
            FROM manage_key_fox 
            INNER JOIN reservation_room ON manage_key_fox.reservation_room_id=reservation_room.id AND (reservation_room.status='CHECKOUT' OR reservation_room.status='CANCEL')
            WHERE manage_key_fox.create_time<=$past";
        $ids = DB::fetch_all($sql);
        $conditions ='';
        foreach($ids as $key=>$value)
        {
            $conditions .='id='.$ids[$key]['id'].' OR ';
        }
        $conditions = substr($conditions,0,strlen($conditions)-3);
        if(trim($conditions)!='')
            DB::query("DELETE FROM manage_key_fox WHERE ".$conditions);   
    }
}
?>