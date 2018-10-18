<?php
class LostCardForm extends Form
{
	function LostCardForm()
	{
		Form::Form('LostCardForm');

	}
	function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:60px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}	
	function draw()
	{
	   
        if(isset($_REQUEST['lost']))
        {
            $this->action();
        }
        else
        {
             $this->loadData();
        }
	}
    function action()
    {
        require_once 'packages/hotel/packages/reception/modules/ManagerKeySalto/db.php';
        $encoder =  $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        $ip_port = DB::fetch("select * from manage_ipsever where id=".$str_encoder[0]);
        
        $s_room = $_REQUEST['room_id'];
        $s_room = explode('_',$s_room);
        
        $door_id = DB::fetch('SELECT door_id FROM manage_doorid WHERE room_id='.$s_room[0]);
        if(isset($door_id['door_id']))
            $door_id = $door_id['door_id'];
        else
            $door_id=100;
        $arr = array();
        $arr[0] = 'CP';
        $arr[1] = $str_encoder[0];
        $arr[2] = $door_id;
        
        $in = generate_card($arr);
        $data ='';
    
        $num_result = proccess_cmd($in,$ip_port['ip'],$ip_port['port'],$data);
        if($num_result!=0)
        {
            $show = display_error_socket($num_result);
            $this->loadData($show,$s_room[1]);
        }
        else
        {
            $str_rerult = proccess_data($data);
            if($str_rerult=='success')
                $str_rerult ='Cancel card success';
            $this->loadData($str_rerult,$s_room[1]);
        }
    }
    function loadData($result='',$room='')
    {
        $this->map = array();
        $this->map['result'] = $result;
        //lay ra thong tin encoder 
         $db_items = DB::fetch_all("select 
                                        id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever 
                                    order by reception desc");
		$reception_id_options = '';
		foreach($db_items as $item)
		{
			$reception_id_options .= '<option value="'.$item['id'].'" >'.$item['name'].'</option>';
		}
        
        $this->map['reception_id'] = $reception_id_options;
        //end lay ra thong tin encoder
        
        //lay ra thong tin room 
        $db_items = DB::fetch_all("select 
                                        id ||'_'|| name as id,name
                                    from room 
                                    where portal_id='".PORTAL_ID."'
                                    order by name asc");
		$room_options = '';
        
		foreach($db_items as $item)
		{
            if($room!='' && $item['name']==$room)
                $room_options .= '<option value="'.$item['id'].'" selected="selected" >'.$item['name'].'</option>';
            else
                $room_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $this->map['room_options'] = $room_options;
       // $_REQUEST['room_id'] = $room;
        $this->parse_layout('lost_card',$this->map);
    }
}
?>