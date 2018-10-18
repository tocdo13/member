<?php
class CheckOutForm extends Form
{
    private $stx;
    private $etx;
    private $rs;
    private $add_client;
    private $add_source;
    private $cmd;
    
    
	function CheckOutForm()
	{
		Form::Form('CheckOutForm');
        $this->stx='';
        $this->etx ='';
        $this->rs ='|';
        $this->add_client='01';
        $this->add_source ='03';
        $this->cmd= 'B';
        
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:60px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
	}
	
	function draw()
	{
        require_once 'packages/hotel/packages/reception/modules/ManagerKeyAdel/db.php';
        if(isset($_REQUEST['delete']))
        {
            $this->list_checkout_detail();
        }
        else
        {
            /*if(isset($_REQUEST['checkout_guest']))
            {
                $this->load_list_guest();
            }
            else*/
            $this->loadData();
        }
	}
    function list_checkout_detail()
    {
        $this->map = array();
        //hien thi danh sach voi cac keys va phong duoc checkout 

        $encoder =  $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);

        $room_id = explode("_",$_REQUEST['room_id']);
        $row = array();
        $row['room_id'] = $room_id[0];
        $row['room_name'] = $room_id[1];
        $row['reception_id'] = $str_encoder[0];
        $row['number_keys'] = $_REQUEST['number_keys'];
        $this->map['row'] = $row;
        
    
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        //System::debug($doorid);
        $this->parse_layout('checkout_detail_room',$this->map);
    }
    
    function action()
    {
        $encoder =  $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        $ip_port = DB::fetch("select * from manage_ipsever_adel where id=".$str_encoder[0]);
        
      
        $this->add_client = sprintf("%02d",$ip_port['add_client']);
        $this->add_source = sprintf("%02d",$ip_port['add_source']);
        $str = $this->stx.$this->add_client.$this->add_source.$this->cmd.$this->rs;
        $str .='R'.$this->add_client;
        $s_room = $_REQUEST['room_id'];
        $s_room_name = explode("_",$s_room); 
        $room_id = $s_room_name[0];
        $doorid = DB::fetch('SELECT door_id FROM manage_doorid WHERE room_id='.$room_id);
        if(isset($doorid['doorid']))
        {
            $str .=sprintf("%04d",$doorid['door_id']).$this->etx;
        }
        else
        {
            $room_name = sprintf("%04d",$s_room_name[1]);
            $str .=$room_name.$this->etx;  
        }
         
        $str_success =$this->add_source.$this->add_client.'0';//chuoi thanh cong
        
        $receive =process_client($str,$ip_port['ip'],$ip_port['port']);
        //$receive = $str_success;
        $receive = $str_success;
        $post = strpos($receive,$str_success);
        if($post>=0)
        {
            $result ='Check out success!';
             //update manage_key_adel
            $this->update_manage_key($room_id);
        }
        else
        {
            $result ='Check out không thành công!';
        }
        
        $this->loadData($result);
    }
    function update_manage_key($room_id)
    {
        //lay ra nhung dong manage_key can update
        $sql ="SELECT mg.*
            FROM reservation_room rr 
            INNER JOIN manage_key_adel mg ON mg.delete_user is null AND mg.reservation_room_id=rr.id 
            AND rr.room_id=".$room_id;
        $arr_manage_key = DB::fetch_all($sql);
    
        //duyet mang va update tung dong theo id,delete_user,delete_time
        foreach($arr_manage_key as $row)
        {
            $row['delete_user'] = User::id();
            $row['delete_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
            $id = $row['id'];
            unset($row['id']);
            DB::update('manage_key_adel',$row,'id='.$id);
        }
    }
    function loadData($result='')
    {
        $this->map = array();
        $this->map['result'] = $result;
        //lay ra thong tin encoder 
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
        //end lay ra thong tin encoder
        
        //lay ra thong tin room 
        $db_items = DB::fetch_all("select 
                                        room.id ||'_'|| room.name as id,
                                        room.name
                                    from room, manage_doorid
                                    where room.id=manage_doorid.room_id AND room.portal_id='".PORTAL_ID."'
                                    order by room.name asc");
		$room_options = '';
        
		foreach($db_items as $item)
		{
			$room_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        
        $this->map['room_id'] = $room_options;
        $this->parse_layout('checkout',$this->map);
    }
    function load_list_guest()
    {
        //lay ra id cua phong 
        $room = explode("_",$_REQUEST['room_id']);
        $room_id = $room[0];
        //lay ra tat ca cac khach cua phong do status =checkin
        $sql ="SELECT rr.room_id,room.name as room_name,
                rr.id as reservation_room_id,
                rr.reservation_id, rr.time_in,rr.time_out,
                t.id as traveller_id,t.first_name || ' ' || t.last_name as full_name,
                NVL(rl.id,1) as id
                FROM reservation_room rr
                INNER JOIN room ON room.id=rr.room_id AND room.id=".$room_id." AND rr.status='CHECKIN'  
                LEFT JOIN reservation_traveller rl ON rr.id=rl.reservation_room_id
                LEFT OUTER JOIN traveller t ON rl.traveller_id=t.id";
        $arr_reservation_traveller = DB::fetch_all($sql);
        //vong lap tao bien index
        $i = 1;
        foreach($arr_reservation_traveller as &$row)
        {
            $row['index'] = $i++;
            $row['date_start'] = date('d/m/Y',$row['time_in']);
            $row['time_start'] = date('H:i',$row['time_in']);
            $row['date_expiry'] = date('d/m/Y',$row['time_out']);
            $row['time_expiry'] = date('H:i',$row['time_out']);
        }
        $_REQUEST['mi_reservation_traveller']=$arr_reservation_traveller;
        //System::debug($arr_reservation_traveller);
        //hien thi reception
        //lay ra thong tin encoder 
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
        $this->map['stx'] = $this->stx;
        $this->map['etx'] = $this->etx;
        $this->map['rs'] = $this->rs;
        $this->map['add_client'] = $this->add_client;
        $this->map['add_source'] = $this->add_source;
        $this->map['command'] = $this->cmd;
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        
        $this->parse_layout('checkout_guest',$this->map);
        
    }
}
?>