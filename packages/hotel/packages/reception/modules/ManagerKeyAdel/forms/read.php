<?php
class ReadKeyForm extends Form
{
    private $stx;
    private $etx;
    private $rs;
    private $add_client;
    private $add_source;
    private $cmd;
    
    
	function ReadKeyForm()
	{
		Form::Form('ReadKeyForm');
        $this->stx='';
        $this->etx ='';
        $this->rs ='|';
        $this->add_client='01';
        $this->add_source='03';
        $this->cmd= 'E';
	}	
    function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:80px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}
    
	function draw()
	{
        require_once 'packages/hotel/packages/reception/modules/ManagerKeyAdel/db.php';
        //goi ham doc the tra ve 1 mang cac gia tri can thiet: cardNo,GuestSN,GuestIndex,Room,BeginTime,EndTime
        if(isset($_REQUEST['read']))
        {
            $encoder = $_REQUEST['reception_id'];
            $str_encoder = explode("_",$encoder);
            $ip_port = DB::fetch("select * from manage_ipsever_adel where id=".$str_encoder[0]);
            $this->add_client =sprintf("%02d",$ip_port['add_client']);
            $this->add_source = sprintf("%02d",$ip_port['add_source']);
            $str =$this->stx.$this->add_client.$this->add_source.$this->cmd.$this->etx;
            $receive = process_client($str,$ip_port['ip'],$ip_port['port']);
            
            //$receive ='03010|R010203|NHUY TAP|D201410311355|O201411041200';
            if($receive=='')
            {
                $this->show_read('Read card unsuccess!<br/>IP sever or port invalid');
            } 
            else
            {
                //kiem tra chuoi ket qua
                $str = substr($receive,0,5);
                if($str!=$this->stx.$this->add_source.$this->add_client)
                {
                    $this->show_read('Read card unsuccess!<br/>Receive data error');
                }  
                else
                {
                    $cmd_result = substr($receive,5,1);
                    if($cmd_result!=0)
                    {
                        $this->display_error($cmd_result); 
                    } 
                    else
                    {
                        $this->display_card($receive); 
                    }  
                }
            } 
        }
        else
        {
            $this->show_read('');
        }
	}
    function show_read($result)
    {
        
        $this->map = array();
        //lay ra thong tin encoder s
        $db_items = DB::fetch_all("select 
                                        id || '_' || ip ||'_'|| add_client||'_'||add_source as id, 
                                        reception as name
                                    from manage_ipsever_adel
                                    order by reception desc");
		$reception_id_options = '';
		foreach($db_items as $item)
		{
			$reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        $this->map['reception_id'] = $reception_id_options;
        
        $this->map['result'] = $result;
        //end lay ra thong tin encoder
        $this->parse_layout('show_read',$this->map);
    }
    function display_error($cmd_result)
    {
        $display ='';
        switch($cmd_result)
        {
           case 1:
                $display ='Read card unsuccsess<br/>unconfirmed error';
                break;
           case 2:
                $display ='Read card unsuccsess<br/>wrong (invalid) target address';
                break;
           case 3:
                $display ='Read card unsuccsess<br/>invalid command code';
                break;
           case 4:
                $display ='Read card unsuccsess<br/>the room is occupied';
                break;
           case 5:
                $display ='Read card unsuccsess<br/>error of COM, or the encoder is busy';
                break;
           case 6:
                $display ='Read card unsuccsess<br/>invalid room number';
                break;
           case 7:
                $display ='Read card unsuccsess<br/>key code already exits';
                break;
           case 8:
                $display ='Read card unsuccsess<br/>the encoder waits overtime';
                break;
           case 10:
                $display ='Read card unsuccsess<br/>invalid time';
                break;
            default:
                $display='unsuccsess';
                break; 
        }
        $this->show_read($display);
    }
    function display_card($receive)
    {
        $s = explode('|',$receive);
        
        $s_begin = substr($s[2],1);
        $s_end = substr($s[3],1);
        $s_door_id = (int)substr($s[1],3);   
        $sql ="SELECT room.id,room.name
            FROM room,manage_doorid
            WHERE room.id=manage_doorid.room_id AND room.portal_id='".PORTAL_ID."'
            AND manage_doorid.door_id=".$s_door_id; 
        $s_room = DB::fetch($sql);
        $s_room_name = $s_room['name'];
        $beginTime = substr($s_begin,6,2).'/'.substr($s_begin,4,2).'/'.substr($s_begin,0,4);
        $i_begin = mktime(substr($s_begin,8,2),substr($s_begin,10,2),0,substr($s_begin,4,2),substr($s_begin,6,2),substr($s_begin,0,4));
        $i_end = mktime(substr($s_end,8,2),substr($s_end,10,2),0,substr($s_end,4,2),substr($s_end,6,2),substr($s_end,0,4));
        
        $endTime = substr($s_end,6,2).'/'.substr($s_end,4,2).'/'.substr($s_end,0,4);
        $time_b = substr($s_begin,8,2).':'.substr($s_begin,10,2);
        $time_e = substr($s_end,8,2).':'.substr($s_end,10,2);
        
        $this->map = array();
        $this->map['result'] = array('room_name'=>$s_room_name,'BeginTime'=>$beginTime,'Time_B'=>$time_b,'EndTime'=>$endTime,'Time_E'=>$time_e);
       
        $sql ="SELECT manage_key_adel.id,
            reservation_room.id as reservation_room_id,
            reservation_room.reservation_id,
            reservation_room.time_in,reservation_room.time_out
            FROM manage_key_adel 
            INNER JOIN reservation_room ON reservation_room.id=manage_key_adel.reservation_room_id 
            AND manage_key_adel.delete_user is null
            INNER JOIN room ON room.id=reservation_room.room_id AND 
            room.name='".$s_room_name."' AND room.portal_id='".PORTAL_ID."'";
        
        $arr_keys = DB::fetch($sql);
        //System::debug($arr_keys);
        
        if(isset($arr_keys['id']))
        {
            $r_id = $arr_keys['reservation_id'];
           // $room = $reservation_id[$reservation_room_id]['name'];
            $arrival_date = Date_Time::display_date($arr_keys['time_in']);
            $departure_date = Date_Time::display_date($arr_keys['time_out']);
            $this->map['result'] = $this->map['result'] + array('reservation_id'=>$r_id,'arrival_date'=>$arrival_date,'departure_date'=>$departure_date);
        }
        //System::debug($this->map);
        $this->parse_layout('read',$this->map);
    }
}
?>