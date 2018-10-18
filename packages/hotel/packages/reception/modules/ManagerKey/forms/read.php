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
        require_once 'packages/hotel/packages/reception/modules/ManagerKey/db.php';
        //goi ham doc the tra ve 1 mang cac gia tri can thiet: cardNo,GuestSN,GuestIndex,Room,BeginTime,EndTime
        if(isset($_REQUEST['read']))
        {
            $encoder = $_REQUEST['reception_id'];
            $str_encoder = explode("_",$encoder);
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$str_encoder[0]);
            $str ='2-1';
           // $result = test_connect('<STX>0103E<ETX>',$ip_port['ip'],$ip_port['port']);
            $result = ReadKey($str,$ip_port['ip'],$ip_port['port']);
            if($result==-1)
            {
                $this->load_data($result);
            } 
            else
            {
                
                $s = explode("-",$result);
                $beginTime = substr($s[2],4,2).'/'.substr($s[2],2,2).'/20'.substr($s[2],0,2);
                $endTime = substr($s[3],4,2).'/'.substr($s[3],2,2).'/20'.substr($s[3],0,2);
                $time_b = substr($s[2],6,2).':'.substr($s[2],8,2);
                $time_e = substr($s[3],6,2).':'.substr($s[3],8,2);
                
                $this->map = array();
                if($s[0]==1 && $s[2]=='1009300900')
                {
                    $this->load_data(1);
                }
                else
                {
                    $this->map['result'] = array('CardNo'=>$s[0],'BeginTime'=>$beginTime,'Time_B'=>$time_b,'EndTime'=>$endTime,'Time_E'=>$time_e,'GuestIdx'=>$s[1]);
                
                    $reservation_room_id = DB::fetch_all("select id,reservation_room_id from manage_key where id=".$s[0]);
                    if(isset($reservation_room_id[$s[0]]['reservation_room_id']))
                    {
                        $reservation_room_id = $reservation_room_id[$s[0]]['reservation_room_id'];
                        $sql ="SELECT reservation_room.id,reservation_room.reservation_id,room.name,
                            reservation_room.time_in,reservation_room.time_out
                            FROM reservation_room INNER JOIN room ON reservation_room.room_id=room.id and reservation_room.id=".$reservation_room_id;
                        
                        
                        $reservation_id = DB::fetch_all($sql);
                        if(isset($reservation_id[$reservation_room_id]['reservation_id']))
                        {
                            $r_id = $reservation_id[$reservation_room_id]['reservation_id'];
                            $room = $reservation_id[$reservation_room_id]['name'];
                            $arrival_date = Date_Time::display_date($reservation_id[$reservation_room_id]['time_in']);
                            $departure_date = Date_Time::display_date($reservation_id[$reservation_room_id]['time_out']);
                            $this->map['result'] = $this->map['result'] + array('reservation_id'=>$r_id,'room'=>$room,'arrival_date'=>$arrival_date,'departure_date'=>$departure_date);
                        }
                    }   
                    $this->parse_layout('read',$this->map);
                }
            } 
        }
        else
        {
            $this->load_data();
        }
        
	}
    
    function load_data($result=0)
    {
        $this->map = array();
        //lay ra thong tin encoder 
         $db_items = DB::fetch_all("select 
                                        id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever 
                                    order by reception desc");
		$reception_id_options = '';
		foreach($db_items as $item)
		{
			$reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $this->map['reception_id'] = $reception_id_options;
        //end lay ra thong tin encoder
        $this->map['result']= $result;
        $this->parse_layout('show_read', $this->map);
    }
}
?>