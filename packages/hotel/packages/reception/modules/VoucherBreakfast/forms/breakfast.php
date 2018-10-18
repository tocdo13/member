<?php
class VoucherBreakfastForm extends Form{
	function VoucherBreakfastForm(){
		Form::Form('VoucherBreakfastForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
    {
           $start_time_breakfast = $this->calc_time(BREAKFAST_FROM_TIME);
           $end_time_breakfast = $this->calc_time(BREAKFAST_TO_TIME); 
        
           $this->map=array();
           if(!isset($_POST['search']) && !isset($_POST['re_id'])){
                $_POST['search'] = "";
                $_POST['status'] = "all";
                $_POST['in_date'] = date("d/m/Y");
                $_POST['re_id'] = "";
                
           }
           if(isset($_POST['search']) && isset($_POST['re_id'])){
            $cond = " 1=1 ";
            $cond.= !empty($_POST['re_id'])? " AND reservation.id = ".$_POST['re_id']: "";
            $this->map['re_id'] = $_POST['re_id'];
            if(isset($_POST['status'])){
                $status = $_POST['status'];
                $cond_two = $cond;
                switch($status){
                    case "new_traveller":
                                          $cond.= " AND reservation_traveller.arrival_time >= ".Date_Time::to_time($_POST['in_date'])." AND reservation_traveller.arrival_time<=".(Date_Time::to_time($_POST['in_date'])+3600*24);  
                                          $cond_two.= " AND reservation_room.time_in >= ".Date_Time::to_time($_POST['in_date'])." AND reservation_room.time_in<=".(Date_Time::to_time($_POST['in_date'])+3600*24);
                                          $this->map['status'] = "new_traveller";
                                          break;
                    case "old_traveller":
                                          $cond.= " AND reservation_traveller.arrival_time < ".Date_Time::to_time($_POST['in_date'])." AND reservation_traveller.departure_time>".(Date_Time::to_time($_POST['in_date'])+3600*24)." AND reservation_room.status='CHECKIN'";
                                          $cond_two.= " AND reservation_room.time_in < ".Date_Time::to_time($_POST['in_date'])." AND reservation_room.time_out>".(Date_Time::to_time($_POST['in_date']) + 3600*24)." AND reservation_room.status='CHECKIN'";  
                                          $this->map['status'] = "old_traveller";
                                          break;
                    default:
                                         $cond.= " AND reservation_traveller.arrival_time <= ".(Date_Time::to_time($_POST['in_date']) + 3600*24)." AND reservation_traveller.departure_time>=".(Date_Time::to_time($_POST['in_date']));
                                         $cond_two.= " AND reservation_room.time_in <= ".(Date_Time::to_time($_POST['in_date']) + 3600*24)." AND reservation_room.time_out>=".(Date_Time::to_time($_POST['in_date']));
                                         $this->map['status'] = "all";
                                         break;                                                                    
                }
            }
            
                $re_id = $_POST['re_id'];
                $this->map['re_code'] = $_POST['re_id']; 
                $this->map['is_group'] = "ok";
                $sql = "SELECT 
                traveller.id as id,
                traveller.first_name || ' ' || traveller.last_name as last_name,
                traveller.gender, 
                room.name as room_name, 
                reservation_room.time_in, 
                reservation_room.time_out, 
                reservation_room.id as reservation_room_id,
                traveller.is_child,
                customer.name as customer_name
                FROM reservation  
                INNER JOIN reservation_room  ON reservation_room.reservation_id=reservation.id 
                INNER JOIN reservation_traveller  ON reservation_traveller.reservation_room_id = reservation_room.id                               
                INNER JOIN traveller  ON traveller.id = reservation_traveller.traveller_id
                INNER JOIN room  ON room.id = reservation_room.room_id
                INNER JOIN customer ON reservation.customer_id = customer.id
                WHERE ".$cond.' AND reservation_room.status!=\'CHECKOUT\' AND reservation_room.status!=\'CANCEL\' AND reservation_room.breakfast=1 AND reservation_traveller.status!=\'CHECKOUT\' AND reservation_traveller.status!=\'CHANGE\' ORDER BY reservation_room.id';
                
                $list_customer = DB::fetch_all($sql); 
                
                    $arr_temp = array();
                    $i = 1;
                        foreach($list_customer as $key=>$value){       
                        if(date("D",$value['time_in'])!="Sun"){                          
                            $time_in = date("H:i:s",$value['time_in']);
                            $second_time_in = explode(":",$time_in);
                            $second_time_in = $second_time_in[0]*3600 + $second_time_in[1]*60+ $second_time_in[2];
                            
                            $time_out = date("H:i:s",$value['time_out']);
                            $second_time_out = explode(":",$time_out);
                            $second_time_out = $second_time_out[0]*3600 + $second_time_out[1]*60+ $second_time_out[2];
                            
                            if($second_time_in>($end_time_breakfast)){
                                if(date($value['time_in'])!=date($value['time_out'])){
                                    
                                    $date = date('d/m/Y',$value['time_in']);
                                    $date_arr = explode("/",$date);
                                    $date_arr = $date_arr[1]."/".$date_arr[0]."/".$date_arr[2];
                                    $next_day_from_time_in = date('d/m/Y',strtotime("+1 day",strtotime($date_arr)));
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        unset($list_customer[$key]);
                                    }
                                    else{ 
                                        
                                        $list_customer[$key]['time_in'] =  $next_day_from_time_in+$start_time_breakfast;                                       
                                    }
                                }
                                else{
                                    unset($list_customer[$key]);  
                                }
                            }
                            else{
                                
                                if(date($value['time_in'])==date($value['time_out'])){
                                    if($second_time_out<($start_time_breakfast)){
                                        unset($list_customer[$key]);
                                    }
                                }
                            }
                        }
                        else{
                            
                            $time_in = date("H:i:s",$value['time_in']);
                            $second_time_in = explode(":",$time_in);
                            $second_time_in = $second_time_in[0]*3600 + $second_time_in[1]*60+ $second_time_in[2];
                            
                            $time_out = date("H:i:s",$value['time_out']);
                            $second_time_out = explode(":",$time_out);
                            $second_time_out = $second_time_out[0]*3600 + $second_time_out[1]*60+ $second_time_out[2];
                            
                            if($second_time_in>($end_time_breakfast)){
                                if(date($value['time_in'])!=date($value['time_out'])){
                                    $date = date('d/m/Y',$value['time_in']);
                                    $date_arr = explode("/",$date);
                                    $date_arr = $date_arr[1]."/".$date_arr[0]."/".$date_arr[2];
                                    $next_day_from_time_in = date('d/m/Y',strtotime("+1 day",strtotime($date_arr)));
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        unset($list_customer[$key]);
                                    }
                                    else{
                                         $list_customer[$key]['time_in'] =  $next_day_from_time_in+$start_time_breakfast;  
                                    }
                                }
                                else{
                                    unset($list_customer[$key]);  
                                }
                            }
                            else{
                                if(date($value['time_in'])==date($value['time_out'])){
                                    if($second_time_out<($start_time_breakfast)){
                                        unset($list_customer[$key]);
                                    }
                                }
                            }
                        }                      
                    } 
                    
                    $sql = "SELECT 
                    reservation_room.id as id,
                    reservation_room.adult,
                    reservation_room.child,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    room.name as name,
                    room_level.num_people,
                    customer.name as customer_name
                    FROM 
                    reservation_room 
                    INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
                    INNER JOIN room ON reservation_room.room_id = room.id 
                    INNER JOIN room_level ON room.room_level_id = room_level.id
                    INNER JOIN customer ON reservation.customer_id = customer.id
                    WHERE 
                    ".$cond_two.' AND reservation_room.status!=\'CHECKOUT\' AND reservation_room.status!=\'CANCEL\' AND reservation_room.breakfast=1 ORDER BY reservation_room.id';
                    $result = DB::fetch_all($sql);
                    $arr_temp = array();
                    $i = 1;
                    foreach($result as $key=>$value){                        
                        if(date("D",$value['time_in'])!="Sun"){
                            $time_in = date("H:i:s",$value['time_in']);
                            $second_time_in = explode(":",$time_in);
                            $second_time_in = $second_time_in[0]*3600 + $second_time_in[1]*60+ $second_time_in[2];
                            
                            $time_out = date("H:i:s",$value['time_out']);
                            $second_time_out = explode(":",$time_out);
                            $second_time_out = $second_time_out[0]*3600 + $second_time_out[1]*60+ $second_time_out[2];
                           
                            if($second_time_in>($end_time_breakfast)){
                                
                                if(date($value['time_in'])!=date($value['time_out'])){
                                    
                                    $date = date('d/m/Y',$value['time_in']);
                                    $date_arr = explode("/",$date);
                                    $date_arr = $date_arr[1]."/".$date_arr[0]."/".$date_arr[2];
                                    $next_day_from_time_in = date('d/m/Y',strtotime("+1 day",strtotime($date_arr)));
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        unset($result[$key]);
                                    }
                                    else{                                     
                                        $result[$key]['time_in'] = $next_day_from_time_in+$start_time_breakfast;                                        
                                    }
                                }
                                else{
                                    unset($result[$key]);  
                                }
                            }
                            else{                               
                                if(date($value['time_in'])==date($value['time_out'])){
                                    if($second_time_out<($start_time_breakfast)){
                                        unset($result[$key]);
                                    }
                                }
                            }
                        }
                        else{                          
                            $time_in = date("H:i:s",$value['time_in']);
                            $second_time_in = explode(":",$time_in);
                            $second_time_in = $second_time_in[0]*3600 + $second_time_in[1]*60+ $second_time_in[2];
                            
                            $time_out = date("H:i:s",$value['time_out']);
                            $second_time_out = explode(":",$time_out);
                            $second_time_out = $second_time_out[0]*3600 + $second_time_out[1]*60+ $second_time_out[2];
                            
                            if($second_time_in>($end_time_breakfast)){
                                if(date($value['time_in'])!=date($value['time_out'])){
                                    $date = date('d/m/Y',$value['time_in']);
                                    $date_arr = explode("/",$date);
                                    $date_arr = $date_arr[1]."/".$date_arr[0]."/".$date_arr[2];
                                    $next_day_from_time_in = date('d/m/Y',strtotime("+1 day",strtotime($date_arr)));
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        unset($result[$key]);
                                    }
                                    else{
                                       $result[$key]['time_in'] = $next_day_from_time_in+$start_time_breakfast;  
                                    }
                                }
                                else{                                   
                                    unset($result[$key]);  
                                }
                            }
                            else{
                                if(date($value['time_in'])==date($value['time_out'])){
                                    if($second_time_out<($start_time_breakfast)){
                                        unset($result[$key]);
                                    }
                                }
                            }
                        }
                        if(isset($result[$key])){         
                            $adult = $value['adult'];
                            $people = $value['adult'] + $value['child'];
                            $num_people = $value['num_people'];
                            $room = $value['name'];
                            if($people>0){
                                for($j = 0; $j< $people; $j++){
                                    $arr_temp[$i]['id'] = $i;
                                    $arr_temp[$i]['room_name'] = $room;
                                    $arr_temp[$i]['customer_name'] = $result[$key]['customer_name'];
                                    $arr_temp[$i]['time_in'] = $result[$key]['time_in'];
                                    $arr_temp[$i]['time_out'] = $result[$key]['time_out'];
                                    $arr_temp[$i]['reservation_room_id'] = $key;
                                    $arr_temp[$i]['first_name'] = "";
                                    $arr_temp[$i]['last_name'] = "";
                                    if($adult==0)
                                    {
                                        $arr_temp[$i]['is_child'] = 1;
                                    }
                                    else
                                    {
                                        $arr_temp[$i]['is_child'] = 0;
                                        $adult--;
                                    }
                                    $i++;
                                }
                            }
                            else{
                              for($j = 0; $j< $num_people; $j++){
                                    $arr_temp[$i]['id'] = $i;
                                    $arr_temp[$i]['room_name'] = $room;
                                    $arr_temp[$i]['customer_name'] = $result[$key]['customer_name'];
                                    $arr_temp[$i]['time_in'] = $result[$key]['time_in'];
                                    $arr_temp[$i]['time_out'] = $result[$key]['time_out'];
                                    $arr_temp[$i]['reservation_room_id'] = $key;
                                    $arr_temp[$i]['first_name'] = "";
                                    $arr_temp[$i]['last_name'] = "";
                                    $arr_temp[$i]['is_child'] = 0;
                                    $i++;
                                }  
                            }                               
                        }                                             
                    }
                    
                    foreach($list_customer as $k=>$v){
                       foreach($arr_temp as $key=>$value){
                        if($v['room_name'] == $value['room_name']){
                            unset($arr_temp[$key]);
                            break;
                        }
                    }
                }            
                $this->map['list_customer'] = $list_customer+$arr_temp;
                
                foreach($this->map['list_customer'] as $key=>$value){
                        if(!isset($min_time)){
                            $min_time = $this->map['list_customer'][$key]['time_in'];
                            $max_time = $this->map['list_customer'][$key]['time_out'];
                            
                        }
                        else{
                            if($this->map['list_customer'][$key]['time_in']<$min_time){
                                $min_time = $this->map['list_customer'][$key]['time_in'];
                            }
                            if($this->map['list_customer'][$key]['time_out']>$max_time){
                                $max_time = $this->map['list_customer'][$key]['time_out'];
                            }
                        }
                      $value['time_out_temp'] =   date('d/m/Y',$this->map['list_customer'][$key]['time_out']);   
                      $value['time_in_temp'] =   date('d/m/Y',$this->map['list_customer'][$key]['time_in']);  
                     $this->map['list_customer'][$value['room_name']."_".$key] = $value;   
                     unset($this->map['list_customer'][$key]);
                 } 
                 //ksort($this->map['list_customer']);  
                 //System::debug($this->map['list_customer']); exit();
               // $in_date = Date_Time::to_orc_date(date("d/m/Y"));
//            
//            
//            $sql = "SELECT * FROM voucher_breakfast WHERE in_date='".$in_date."' ORDER BY voucher_breakfast.reservation_room_id,id";
//            $result = DB::fetch_all($sql);
//            $array_count_old_data = array();
//            
//            foreach($result as $key=>$value)
//            {
//                if(!isset($array_count_old_data[$value['reservation_room_id']]))
//                {
//                    $array_count_old_data[$value['reservation_room_id']] = 1;
//                }
//                else
//                {
//                    $array_count_old_data[$value['reservation_room_id']]++;
//                }
//            }
//            
//            //System::debug($this->map['list_customer']); exit();
//            $sql = "SELECT voucher_breakfast.reservation_room_id as id FROM voucher_breakfast WHERE in_date='".$in_date."' ORDER BY voucher_breakfast.reservation_room_id";
//            $old_data = DB::fetch_all($sql);
//            
//            $str_date = date("dmY");
//            
//            
//            
//            $sql = "SELECT MAX(voucher_id) as id FROM voucher_breakfast WHERE in_date='".$in_date."'";
//            $max_id = DB::fetch($sql);
//            
//            $start_id = $max_id['id']==null ? 1 : $max_id['id']+1; 
//            
//            $id_temp = "";
//            foreach($this->map['list_customer'] as $k=>$v)
//            {
//                if(empty($old_data))
//                {
//                    $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$str_date.str_pad($start_id,5,"0",STR_PAD_LEFT),"in_date"=>$in_date,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['last_name']));
//                    $this->map['list_customer'][$k]['barcode'] = $str_date.str_pad($start_id,5,"0",STR_PAD_LEFT);
//                    $this->map['list_customer'][$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
//                    $start_id++;
//                }
//                else
//                {
//                    if(!isset($old_data[$v['reservation_room_id']]))
//                    {
//                        $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$str_date.str_pad($start_id,5,"0",STR_PAD_LEFT),"in_date"=>$in_date,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['last_name']));
//                        $this->map['list_customer'][$k]['barcode'] = $str_date.str_pad($start_id,5,"0",STR_PAD_LEFT);
//                        $this->map['list_customer'][$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
//                        $start_id++;
//                    }
//                    else
//                    { 
//                       if($array_count_old_data[$v['reservation_room_id']]==0)
//                       {
//                            $id = DB::insert("voucher_breakfast", array("voucher_id"=>$start_id,"barcode"=>$str_date.str_pad($start_id,5,"0",STR_PAD_LEFT),"in_date"=>$in_date,"reservation_room_id"=>$v['reservation_room_id'],"is_child"=>$v['is_child'],"guest_name"=>$v['last_name']));
//                            $this->map['list_customer'][$k]['barcode'] = $str_date.str_pad($start_id,5,"0",STR_PAD_LEFT);
//                            $this->map['list_customer'][$k]['no'] = str_pad($start_id,5,"0",STR_PAD_LEFT);
//                            $start_id++;
//                       }
//                       else
//                       {
//                            foreach($result as $key=>$value)
//                            {
//                              if($value['reservation_room_id'] == $v['reservation_room_id'])
//                              {
//                                if(strpos((string) $id_temp, (string) $key)===false)
//                                {
//                                    $this->map['list_customer'][$k]['barcode'] = $value['barcode'];
//                                    $this->map['list_customer'][$k]['no'] = str_pad($value['voucher_id'],5,"0",STR_PAD_LEFT);
//                                    $id_temp.=($key.",");
//                                    $array_count_old_data[$value['reservation_room_id']]--;
//                                    break;
//                                }
//                              }
//                           } 
//                       } 
//                    }
//                }
//                
//            }                
            $this->map['time_in'] = date("d/m/Y");
            $this->map['in_date'] = isset($_REQUEST['in_date']) ? $_REQUEST['in_date'] : date("d/m/Y");
            $this->map['time_out'] =  isset($max_time)?$max_time:0;
        }
        else{
            
            $this->map['in_date'] = date("d/m/Y");
            $this->map['status']= "all"; 
        }
        
        $this->map['start_time_breakfast'] = $start_time_breakfast;
        $this->map['end_time_breakfast'] = $end_time_breakfast; 
        
        $this->map['time_in'] = Date_Time::to_time(date("d/m/Y"));       
        $this->parse_layout('breakfast',$this->map);
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
