<?php
class GuestRegistrationCardForm extends Form
{
	function GuestRegistrationCardForm()
	{
		Form::Form('GuestRegistrationCardForm');
                $this->link_css(Portal::template('core') . '/css/jquery/datepicker.css');
                $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   if(Url::get('form')==8){
	        $this->map = array();
            $this->map['time_print'] = date('H:i d/m/Y');
            $user_data = Session::get('user_data');
            $this->map['user_print'] = $user_data['full_name'];
            
            $start_time_breakfast = $this->calc_time(BREAKFAST_FROM_TIME);
            $end_time_breakfast = $this->calc_time(BREAKFAST_TO_TIME);
            
            if(!isset($_GET['re_id'])){
            $sql= '
            select 
                reservation_room.id as id,
                room.name as room_name,
                reservation_room.time_in,
                reservation_room.time_out,
                customer.name as name
            from 
                reservation_room inner join room on reservation_room.room_id = room.id
                INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
                LEFT JOIN customer ON reservation.customer_id = customer.id
            WHERE
				reservation_room.id='.Url::iget('id').'';           
	        $row = DB::fetch($sql);
            
            $sql = "SELECT 
            reservation_traveller.id as id, 
            traveller.first_name || ' ' || traveller.last_name as last_name,
            traveller.gender , 
            reservation_room.time_in, 
            reservation_room.time_out, 
            reservation_room.id as reservation_room_id,
            room.name as room_name, 
            traveller.is_child  
            FROM reservation  
            INNER JOIN reservation_room ON reservation_room.reservation_id=reservation.id 
            INNER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id 
            INNER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
            INNER JOIN room ON reservation_room.room_id = room.id
            WHERE reservation_room.id=".Url::iget('id')." AND reservation_traveller.status!='CHECKOUT' AND reservation_traveller.status!='CHANGE' ORDER BY reservation_room.id";
            
            $list_customer = DB::fetch_all($sql);
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
                    if(isset($list_customer[$key])){
                                    if(!isset($min_time)){
                                        $min_time = $list_customer[$key]['time_in'];
                                        $max_time = $list_customer[$key]['time_out'];
                                        $this->map['time_in'] = $min_time;
                                        $this->map['time_out'] = $max_time;
                                    }
                                    else{
                                        if($list_customer[$key]['time_in']<=$min_time){
                                            $this->map['time_in'] = $list_customer[$key]['time_in'];
                                        }
                                        if($list_customer[$key]['time_out']>=$max_time){
                                            $this->map['time_out'] = $list_customer[$key]['time_out'];
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
                    room_level.num_people
                    FROM 
                    reservation_room 
                    INNER JOIN room ON reservation_room.room_id = room.id 
                    INNER JOIN room_level ON room.room_level_id = room_level.id
                    WHERE 
                    reservation_room.id=".Url::iget('id').' AND reservation_room.status!=\'CHECKOUT\' AND reservation_room.status!=\'CANCEL\' AND reservation_room.breakfast=1 ORDER BY reservation_room.id';
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
                                    //echo $next_day_from_time_in;
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    //echo $next_day_from_time_in;
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        //echo "kkkk";
                                        unset($result[$key]);
                                    }
                                    else{
                                        $result[$key]['time_in'] = $next_day_from_time_in+$start_time_breakfast;                                        
                                    }
                                }
                                else{
                                    //echo "2";
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
                                    $arr_temp[$i]['time_in'] = $result[$key]['time_in'];
                                    $arr_temp[$i]['time_out'] = $result[$key]['time_out'];
                                    $arr_temp[$i]['reservation_room_id'] = $key;
                                    $arr_temp[$i]['is_child'] = 0;
                                    $arr_temp[$i]['first_name'] = "";
                                    $arr_temp[$i]['last_name'] = "";
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
                     $this->map['list_customer'][$value['room_name']."_".$key] = $value;   
                     unset($this->map['list_customer'][$key]);
                 } 
                 ksort($this->map['list_customer']); 
                     $this->map['time_in'] = isset($min_time)?$min_time:0;
                    $this->map['time_out'] =  isset($max_time)?$max_time:0;
                $this->map['room_name'] = $row['room_name'];
                $this->map['customer_name']= $row['name'];
                
            } 
            else{
                
                $re_id = $_GET['re_id'];
                $sql= '
                select 
                    reservation_room.id as id,
                    room.name as room_name,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    customer.name as name
                from 
                    room inner join reservation_room on reservation_room.room_id = room.id
                    INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
                    LEFT JOIN customer ON reservation.customer_id = customer.id
                WHERE
    				reservation_room.reservation_id='.Url::iget('re_id').' AND reservation_room.status!=\'CHECKOUT\' AND reservation_room.status!=\'CANCEL\' AND reservation_room.breakfast=1';           
    	        $row = DB::fetch_all($sql);
                
                foreach($row as $key =>$value){
                     $this->map['customer_name'] = $value['name'];
                }
                $this->map['re_code'] = Url::iget('re_id'); 
                $this->map['is_group'] = "ok";
                $sql = "SELECT c.id as id, d.first_name || ' ' || d.last_name as last_name, d.gender, e.name as room_name, b.time_in, b.time_out, d.is_child, b.id as reservation_room_id  
                FROM reservation a 
                INNER JOIN reservation_room b ON b.reservation_id=a.id 
                INNER JOIN reservation_traveller c ON c.reservation_room_id = b.id 
                INNER JOIN traveller d ON d.id = c.traveller_id
                INNER JOIN room e ON e.id = b.room_id
                WHERE a.id=".Url::iget('re_id').' AND b.status!=\'CHECKOUT\' AND b.status!=\'CANCEL\' AND b.breakfast=1 AND c.status!=\'CHECKOUT\' AND c.status!=\'CHANGE\' ORDER BY b.id';
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
                                    //echo $next_day_from_time_in;
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    //echo $next_day_from_time_in;
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        //echo "kkkk";
                                        unset($list_customer[$key]);
                                    }
                                    else{ 
                                        
                                        $list_customer[$key]['time_in'] =  $next_day_from_time_in+$start_time_breakfast;                                       
                                    }
                                }
                                else{
                                    //echo "2";
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
                    room_level.num_people
                    FROM 
                    reservation_room 
                    INNER JOIN room ON reservation_room.room_id = room.id 
                    INNER JOIN room_level ON room.room_level_id = room_level.id
                    WHERE 
                    reservation_id=".Url::iget('re_id').' AND reservation_room.status!=\'CHECKOUT\' AND reservation_room.status!=\'CANCEL\' AND reservation_room.breakfast=1 ORDER BY reservation_room.id';
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
                                    //echo $next_day_from_time_in;
                                    $next_day_from_time_in = Date_Time::to_time($next_day_from_time_in);
                                    //echo $next_day_from_time_in;
                                    if($value['time_out']>=$next_day_from_time_in && $value['time_out']<($next_day_from_time_in+$start_time_breakfast)){
                                        //echo "kkkk";
                                        unset($result[$key]);
                                    }
                                    else{                                     
                                        $result[$key]['time_in'] = $next_day_from_time_in+$start_time_breakfast;                                        
                                    }
                                }
                                else{
                                    //echo "2";
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
                            //System::debug($result[$key]);
                            $adult = $value['adult'];
                            $people = $value['adult'] + $value['child'];
                            $num_people = $value['num_people'];
                            $room = $value['name'];
                            if($people>0){
                                for($j = 0; $j< $people; $j++){
                                    $arr_temp[$i]['id'] = $i;
                                    $arr_temp[$i]['room_name'] = $room;
                                    $arr_temp[$i]['time_in'] = $result[$key]['time_in'];
                                    $arr_temp[$i]['time_out'] = $result[$key]['time_out'];
                                    $arr_temp[$i]['first_name'] = "";
                                    $arr_temp[$i]['last_name'] = "";
                                    $arr_temp[$i]['reservation_room_id'] = $key;
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
                                    $arr_temp[$i]['time_in'] = $result[$key]['time_in'];
                                    $arr_temp[$i]['time_out'] = $result[$key]['time_out'];
                                    $arr_temp[$i]['first_name'] = "";
                                    $arr_temp[$i]['last_name'] = "";
                                    $arr_temp[$i]['is_child'] = 0;
                                    $arr_temp[$i]['reservation_room_id'] = $key;
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
                $this->map['list_customer'] = $list_customer + $arr_temp;
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
            
            
                 ksort($this->map['list_customer']); 
                $this->map['time_in'] = isset($min_time)?$min_time:0;
                $this->map['time_out'] =  isset($max_time)?$max_time:0;
                    
            }
           $this->map['start_time_breakfast'] = $start_time_breakfast;
           $this->map['end_time_breakfast'] = $end_time_breakfast;  
           
           $this->parse_layout('guest_voucher_breakfast',$this->map);
	   }
       else{
		$this->map = array();
		$sql = '
			SELECT
				reservation_room.*,
                traveller.id as id,
                room.name as room_name,reservation_room.price as room_rate,
				reservation_room.net_price,
                reservation_room.reservation_id as reservation_id,
                                to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time,
                                to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
				traveller.last_name as full_name,
				to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
                                country.name_1 as nationality,
				traveller.gender, traveller.address,
                                CONCAT(traveller.phone, CONCAT(\' \',traveller.email)) as contact_details,
				traveller.phone,traveller.passport,traveller.gender,
				customer.name as customer_name,
                                traveller.address as customer_address,
                                customer.mobile as customer_mobile,
                                customer.phone as customer_phone,
                                customer.fax as customer_fax,
                                customer.email as customer_email,
                                room_level.name as room_level,
				reservation_traveller.flight_code,
                                to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as date_entry,
                                to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa,
                                reservation_traveller.port_of_entry as port,
                                reservation_traveller.flight_code_departure,
                                reservation_traveller.flight_arrival_time,
                                reservation_traveller.flight_departure_time,
                                reservation_traveller.visa_number,
                                reservation_traveller.note,
                                reservation.id as reservation_id,
                                customer_group.name as reservation_type,
    				customer_group.show_price
			FROM
				reservation_room
				inner join reservation on reservation.id = reservation_room.reservation_id
                                left join room on reservation_room.room_id = room.id
                                left join room_type on room.room_type_id = room_type.id
                                left join room_level on room.room_level_id = room_level.id
                                left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                left outer join traveller on reservation_traveller.traveller_id = traveller.id
                                left outer join country on traveller.nationality_id = country.id
                                --left outer join tour on reservation.tour_id = tour.id
                                left outer join customer on reservation.customer_id = customer.id
                                left outer join customer_group on customer_group.id=customer.group_id
                                --left outer join room_status on room_status.reservation_room_id = reservation_room.id
                                
			WHERE
				reservation_room.id='.Url::iget('id');
                
		if(Url::get('id') and $row = DB::fetch_all($sql)){
		  //System::debug($row);
          $guest = array();
          $row1 = array();
          foreach($row as $key=>$value)
          {
            $guest[$key]['id'] = $value['id'];
            $guest[$key]['name'] = $value['full_name'];
            $row1 = $value;
           	$this->map['guest']['1'] = $value;
          }
          //System::debug($row1);
          //System::debug($guest); 
          if(Url::get('guest_name')){
            $id_traveller = Url::get('guest_name');
            //echo $id_traveller;
            $row1 = $row[$id_traveller];
            //System::debug($row1);
            $this->map['guest']['1'] = $row[$id_traveller];
          }
          $this->map['guest_name_list'] = array($value['id']=>$value['full_name'])+String::get_list($guest);
          //System::debug($this->map['guest_name_list']);
          //exit();
			//$guest = DB::fetch_all('
//				SELECT
//					traveller.id,
//					(traveller.first_name||\' \'||traveller.last_name) as full_name,traveller.gender
//				FROM
//					traveller
//					INNER JOIN reservation_traveller on reservation_traveller.traveller_id = traveller.id
//				WHERE
//                    rownum<=1 and
//					reservation_traveller.reservation_room_id = '.$row['id'].'
// 			');

            
			if($row1['show_price']){
			$row1['room_rate'] = System::display_number($row1['room_rate']);
			if(!$row1['net_price']){

				if($row1['service_rate']){
					$row1['room_rate'] .= '+';
				}
				if($row1['tax_rate']){
					$row1['room_rate'] .= '+';
				}
			}
			}
			else{
				$row1['room_rate'] = '';
			}
            
            
			$this->map += $row1;
            
            
			if(Url::get('form')==1){
				$layout = 'registration_form';
			}elseif(Url::get('form')==2){
				$layout = 'guest_confirmation_form';
			}elseif(Url::get('form')==3){
				$layout = 'guest_confirmation_form_vn';
			}else{
				$layout = 'guest_registration_card';
			}
            
			$this->parse_layout($layout,$this->map);
            }
         }   
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60 + 59;
    }
}
?>