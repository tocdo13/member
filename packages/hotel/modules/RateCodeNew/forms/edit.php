<?php
class RateCodeNewEditForm extends Form
{
	function RateCodeNewEditForm()
    {
		Form::Form('RateCodeNewEditForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_js('skins/default/bootstrap/js/bootstrap.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
    function on_submit(){

        $scheduleCode = $_POST['code'];
        $scheduleName = $_POST['name'];
        if(isset($_REQUEST['r_id'])){
            
            
            $r_id = $_GET['r_id'];
        
            $sql = "SELECT * FROM rate_code_time WHERE rate_code_id IN($r_id)";
            $rate_code_time_list = DB::fetch_all($sql);
            
            foreach($rate_code_time_list as $key => $value){
                DB::delete("rate_room_level"," rate_code_time_id=$key");
            }
            
            DB::delete("rate_customer_group"," rate_code_id IN ($r_id)");
            DB::delete("rate_code_time", " rate_code_id IN($r_id)");
            DB::update("rate_code",array("name"=>$scheduleName,"code"=>$scheduleCode)," id=$r_id");
            $id = $r_id;
        }
        else{
            DB::insert('rate_code',array('code'=>$scheduleCode, 'name'=>$scheduleName));
        
            $sql = "SELECT id FROM rate_code where code='$scheduleCode'";
            $tempID = DB::fetch($sql);
            $id = $tempID['id'];
        }
        
        $countRecord = $_POST['countRecord'];
        
        foreach($_POST['group_list'] as $key=>$value)
        {
            DB::insert('rate_customer_group',array('customer_id'=>$value,'rate_code_id'=>$id));
        }
        
        
        //$sql = "SELECT * FROM customer_group";
        //$group_list = DB::fetch_all($sql);
        
        $sql = "SELECT * FROM room_level";
        $room_level_list = DB::fetch_all($sql);
                
        
        for($i = 0; $i< $countRecord; $i++){
            if(isset($_POST['start_date_'.$i])){
            
            $start_date = Date_Time::to_orc_date($_POST['start_date_'.$i]);
            
            $end_date = Date_Time::to_orc_date($_POST['end_date_'.$i]);
            $frequency = $_POST['frequency_'.$i];
            $weekly = $_POST['weekly_'.$i];
            $priority = $_POST['priority_'.$i];
            }
            else{
                continue;
            }
            
            DB::insert('rate_code_time',array('rate_code_id'=>$id,'start_date'=>$start_date, 'end_date'=>$end_date,'frequency'=>$frequency,'weekly'=>$weekly,'priority'=>$priority));
            if(empty($weekly)){
                $sql = "SELECT id FROM rate_code_time where rate_code_id=$id AND start_date='$start_date' AND end_date='$end_date' AND priority=$priority AND frequency='$frequency'"; 
            }
            else{
            $sql = "SELECT id FROM rate_code_time where rate_code_id=$id AND start_date='$start_date' AND end_date='$end_date' AND priority=$priority AND frequency='$frequency' AND weekly='$weekly'";
            }
            $temp = DB::fetch($sql);
            $time_id = $temp['id'];
            //System::debug($sql) ; System::debug($temp); exit();
            
            foreach($room_level_list as $key => $value){
                if(isset($_POST['price_'.$i.'_'.$key])){
                    $value = System::calculate_number($_POST['price_'.$i.'_'.$key]);
                    DB::insert('rate_room_level',array('room_level_id'=>$key,'rate_code_time_id'=>$time_id,'price'=>$value));
                }
            }
        }
        Url::redirect('rate_code_new');
    }
	function draw()
    {
        $sql = "SELECT * FROM customer_group where structure_id != '1000000000000000000'";
        $group_list = DB::fetch_all($sql);
        foreach($group_list as $k=>$v)
        {
            $sql = "SELECT customer.id,
                           customer.code,
                           customer.name 
                            FROM customer WHERE group_id = '".$v['id']."'";
            $result = DB::fetch_all($sql);
            $group_list[$k]['items'] = $result;
        }
        //System::debug($group_list);
        $this->map['group_list'] = $group_list;
        
        $sql = "SELECT * FROM room_level WHERE portal_id='".PORTAL_ID."' ORDER BY name";
        $room_level_list = DB::fetch_all($sql);
        $this->map['room_level_list'] = $room_level_list;
        
        $room_level_list_js = String::array2js($room_level_list);
        $this->map['room_level_list_js'] = $room_level_list_js;
            
            
            $sql = "SELECT * FROM rate_code";
            $rate_code_list_contain = DB::fetch_all($sql);
            foreach($rate_code_list_contain as $key=>$value){
                $sql = "SELECT * 
                            FROM rate_customer_group a 
                            INNER JOIN customer b ON a.customer_id=b.id  
                            WHERE rate_code_id=".$value['id'];
                $rate_code_list_contain[$key]['customer_group'] = DB::fetch_all($sql);
                $sql = "SELECT id, TO_CHAR(start_date,'DD/MM/YYYY') as start_date, TO_CHAR(end_date,'DD/MM/YYYY') as end_date, frequency, weekly, priority FROM rate_code_time WHERE rate_code_id=".$value['id'];
                $rate_code_list_contain[$key]['rate_code_time'] = DB::fetch_all($sql);
            }
            
            $this->map['rate_code_list_contain_js'] = String::array2js($rate_code_list_contain);
            
            //System::debug($rate_code_list_contain); exit();
         $cond = "";
         $condTwo = ""; 
         $this->map['rate_code_name'] = "";
         $this->map['rate_code_code'] = "";
          if(isset($_GET['r_id'])){
            $r_id = $_GET['r_id'];
            $condTwo.=" rate_code_id=".$r_id; 
            $cond.=" id = ".$r_id;
            
            $sql = "SELECT * FROM rate_code WHERE id!=".$r_id;
            $rate_code_list_contain = DB::fetch_all($sql);
            foreach($rate_code_list_contain as $key=>$value){
                $sql = "SELECT * FROM 
                                rate_customer_group a 
                                INNER JOIN customer b ON a.customer_id=b.id  
                                WHERE rate_code_id=".$value['id'];
                $rate_code_list_contain[$key]['customer_group'] = DB::fetch_all($sql);
                $sql = "SELECT id, TO_CHAR(start_date,'DD/MM/YYYY') as start_date, TO_CHAR(end_date,'DD/MM/YYYY') as end_date, frequency, weekly, priority FROM rate_code_time WHERE rate_code_id=".$value['id'];
                $rate_code_list_contain[$key]['rate_code_time'] = DB::fetch_all($sql);
            }

            $this->map['rate_code_list_contain_js'] = String::array2js($rate_code_list_contain);
            
            $sql = "SELECT * FROM rate_code WHERE $cond";
            $rate_code_list = DB::fetch($sql);
            ///System::debug($rate_code_list);
            $this->map['rate_code_name'] = $rate_code_list['name'];
            $this->map['rate_code_code'] = $rate_code_list['code'];
            
            
            $sql = "SELECT id, TO_CHAR(start_date,'YYYY-MM-DD') as start_date, TO_CHAR(end_date,'YYYY-MM-DD') as end_date, frequency, weekly, priority FROM rate_code_time WHERE $condTwo ORDER BY id";
            $schedule_list = DB::fetch_all($sql);
            
            
            $sql = "SELECT * FROM rate_customer_group WHERE $condTwo";
            $customer_group_list = DB::fetch_all($sql);
            
            $this->map['customer_group_list_js'] = String::array2js($customer_group_list);
            
            foreach($schedule_list as $key=>$value){
                $schedule_list[$key]['brief'] = $this->getBrief($value['frequency'],$value['weekly']);
                $schedule_list[$key]['strPriority'] = $this->getPriority($value['priority']);
                $sql = "SELECT room_level_id as id, price FROM rate_room_level WHERE rate_code_time_id =".$value['id'];
                $schedule = DB::fetch_all($sql);
                foreach($schedule as $k => $item){
                    $schedule_list[$key]['schedule'][$k]['price'] = number_format($item['price'],"0",'.',',');
                    $schedule_list[$key]['schedule'][$k]['id'] = $item['id'];
                }
            }
            //System::debug($schedule_list); exit();
            $this->map['schedule_list'] = $schedule_list;
            $this->map['schedule_list_js'] = String::array2js($schedule_list); 
        }
        
        //System::debug($rate_code_list_contain); exit();
        $this->parse_layout('edit',$this->map);
    }
    public function getBrief($week, $day){
        $str = "";
        if($week == "w"){
            $str = "Hàng tuần vào";
            $arrW = explode(",",$day);
            $countInWeek = 0;
            $countOutWeek = 0;
            $date = "";
            //System::debug($arrW);
            foreach($arrW as $key => $value){
                switch($value){
                    case "1":
                            $date.= Portal::language("sun").", ";
                            break;
                    case "2":
                            $date.= Portal::language("mon").", ";
                            break;
                    case "3":
                            $date.= Portal::language("tue").", ";
                            break;
                    case "4":
                            $date.= Portal::language("wed").", ";
                            break;
                    case "5":
                            $date.= Portal::language("thu").", ";
                            break;
                    case "6":
                            $date.= Portal::language("fri").", ";
                            break;
                    case "7":
                            $date.= Portal::language("sat").", ";
                            break;                                                
                }
                if($value==6 || $value==7){
                    $countInWeek++;
                }
                else{
                    $countOutWeek++;
                }   
            }
            $str.=$date;
            $str = substr($str,0,strlen($str)-2);
            if(count($arrW)==7){
                $str = Portal::language("every_day");
            }
            else if($countInWeek==2){
                $str = Portal::language("The_weekly_on_weekends");
            }
            else if($countOutWeek==5 && $countInWeek==0){
                $str = Portal::language("The_weekly_on_weekdays");
            }

        }
        else{
            $str=Portal::language("every_day");
        }
        return $str;
    }
    function getPriority($value){
        $str = "";
        switch($value){
            case "1":
                       $str = Portal::language("every_day");
                       break;
            case "2":
                        $str = Portal::language("holyday_day");
                        break;
            case "3":
                        $str =Portal::language("special_day");
                        break;                            
        }
        return $str;
    }
}    
?>    