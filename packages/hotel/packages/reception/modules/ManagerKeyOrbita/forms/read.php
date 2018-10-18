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
        require_once 'packages/hotel/packages/reception/modules/ManagerKeyOrbita/db.php';
        
        //goi ham doc the tra ve 1 mang cac gia tri can thiet: cardNo,GuestSN,GuestIndex,Room,BeginTime,EndTime
        if(isset($_REQUEST['read']))
        {
            //gui chuoi doc the neu doc the thanh cong ghi thong tin the ra form
            //neu doc the khong thanh cong thi hien thi thong bao loi 
            $reception_id = explode("_",Url::get('reception_id'));
            $reception_id = $reception_id[0];
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$reception_id);
            $str = '2_1';//chuoi gui lenh doc the 
            $receive = process_client($str,$ip_port['ip'],$ip_port['port']);
            //System::debug($receive); exit();
            //tra ve CardNo neu thanh cong, tra ve 0 neu that bai 
            $str_receive = explode("_",$receive);
            if(empty($str_receive))
            {
                $result= 'Read Card unsuccessful!';
                $this->show_read($result);
            }
            else
            {
                $cardNo = $str_receive[0];
                $row = $this->get_object_fox($cardNo);
                
                if(empty($row))
                {
                    $this->show_read('Card has been checkout!');
                }
                else
                {
                    $this->display_card($row);
                }
            }
        }
        else
        {
            $this->show_read('');
        }
	}
    function display_card($row)
    {
        $this->map = array();
        
        $room_name  = $row['room_name'];
        $begin_date = date('d/m/Y',$row['begin_time']);
        $begin_time = date('H:i',$row['begin_time']);
        
        $expiry_date = date('d/m/Y',$row['end_time']);
        $expiry_time = date('H:i',$row['end_time']);

        $r_id = $row['reservation_id'];
        $arrival_date = date('d/m/Y',$row['time_in']);
        $departure_date = date('d/m/Y',$row['time_out']);
        $this->map['result'] = array('room_name'=>$room_name,'BeginTime'=>$begin_date,'Time_B'=>$begin_time,'EndTime'=>$expiry_date,'Time_E'=>$expiry_time);
        $this->map['result'] = $this->map['result'] + array('reservation_id'=>$r_id,'arrival_date'=>$arrival_date,'departure_date'=>$departure_date);
        
        $this->parse_layout('read',$this->map);
    }
    function get_object_fox($cardNo)
    {
        $sql = "SELECT manage_key_fox.id,
                    room.name as room_name,
                    manage_key_fox.begin_time,
                    manage_key_fox.end_time,
                    reservation_room.reservation_id,
                    reservation_room.time_in,
                    reservation_room.time_out
                FROM manage_key_fox
                INNER JOIN reservation_room ON reservation_room.id=manage_key_fox.reservation_room_id
                INNER JOIN room ON reservation_room.room_id=room.id
                WHERE manage_key_fox.checkout_user is null AND manage_key_fox.card_no='".trim($cardNo)."'
                ORDER BY id desc";

        $items  = DB::fetch_all($sql);
        
        $row = array();
        foreach($items as $r)
        {
            $row = $r;
            break;
        }
        return $row;
    }
    function show_read($result)
    {
        
        $this->map = array();
        //lay ra thong tin encoder s
        $db_items = DB::fetch_all("select 
                                        id || '_' || ip  as id, 
                                        reception as name
                                    from manage_ipsever
                                    order by reception desc");
		$reception_id_options = '';
        
		foreach($db_items as $item)
		{
		  if(isset($_REQUEST['reception_id']))
          {
            if($_REQUEST['reception_id']==$item['id'])
                $reception_id_options .= '<option value="'.$item['id'].'" selected="selected">'.$item['name'].'</option>';
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
    
}
?>