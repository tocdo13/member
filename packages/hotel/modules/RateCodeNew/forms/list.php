<?php
class RateCodeNewForm extends Form
{
	function RateCodeNewForm()
    {
		Form::Form('RateCodeNewForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_js('skins/default/bootstrap/js/bootstrap.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
	function draw()
    {
        $cond = "1=1";
        if(isset($_REQUEST)){
            if(!empty($_POST['name'])){
                $cond.= " AND rate_code.name='".$_POST['name']."'";
            }
            if(!empty($_POST['code'])){
                $cond.= " AND rate_code.code='".$_POST['code']."'";
            }
            if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
                $start_date = $_POST['start_date'];
                $start_date = date("d/m/Y", strtotime($_POST['start_date']));
                $start_date = Date_Time::to_time($start_date);
                $end_date = $_POST['end_date'];
                $end_date = date("d/m/Y", strtotime($_POST['end_date']));
                $end_date = Date_Time::to_time($end_date);
                $cond.=" AND ( DATE_TO_UNIX(rate_code_time.start_date)<=$end_date AND DATE_TO_UNIX(rate_code_time.end_date)>=$start_date )";
                //$cond.=" AND ((()<=$start_date AND (DATE_TO_UNIX(a.end_date)-12*3600)>=$end_date)) OR ((DATE_TO_UNIX(a.start_date)-12*3600)>=$start_date AND (DATE_TO_UNIX(a.end_date)-12*3600)<=$end_date) ) )";
            }
            else if(!empty($_POST['start_date'])){
                $start_date = $_POST['start_date'];
                $start_date = date("d/m/Y", strtotime($_POST['start_date']));
                $start_date = Date_Time::to_time($start_date);
                $cond.= " AND (DATE_TO_UNIX(rate_code_time.start_date)<=$start_date AND DATE_TO_UNIX(rate_code_time.end_date)>=$start_date)";
            }
            //else{
//                $start_date = 0;
//                $cond.= " AND DATE_TO_UNIX(a.start_date)<=$start_date";
//            }
            else if(!empty($_POST['end_date'])){
                $end_date = $_POST['end_date'];
                $end_date = date("d/m/Y", strtotime($_POST['end_date']));
                $end_date = Date_Time::to_time($end_date);
                $cond.= " AND (DATE_TO_UNIX(rate_code_time.start_date)<=$end_date AND DATE_TO_UNIX(rate_code_time.end_date)>=$end_date)";
            }
            if(!empty($_POST['customer_group'])){
                $cond.=" AND customer.name LIKE('%".$_POST['customer_group']."%')";
            }
            //else{
//                $end_date = 0;
//                $cond.= " AND DATE_TO_UNIX(a.end_date)>=$end_date";
//            }
            
        }
        $sql = "SELECT 
                    rate_code_time.id as id, 
                    rate_code_time.*, 
                    rate_code.name, 
                    rate_code.code,DATE_TO_UNIX(rate_code_time.start_date) as s_date 
                    FROM rate_code_time 
                    INNER JOIN rate_code ON rate_code_time.rate_code_id=rate_code.id  
                    INNER JOIN rate_customer_group ON rate_code.id = rate_customer_group.rate_code_id 
                    INNER JOIN customer ON rate_customer_group.customer_id = customer.id
                    WHERE $cond ORDER BY rate_code_time.rate_code_id,rate_code_time.id";
        $rate_code_time = DB::fetch_all($sql); 
       
        $this->getGroupItem($rate_code_time,'rate_code_id');
        
        foreach($rate_code_time as $key=>$value){
            if(isset($value['count'])){
              $sql = "SELECT 
                        rate_customer_group.id,
                        customer.name,
                        customer_group.name as customer_group_name,
                        customer_group.id as customer_group_id 
                        FROM rate_customer_group  
                        INNER JOIN customer ON rate_customer_group.customer_id=customer.id
                        INNER JOIN customer_group ON customer_group.id = customer.group_id
                        WHERE rate_code_id = ".$value['rate_code_id']."
                        ORDER BY customer_group.name, customer.name
                        "
                        ;
              $rate_code_time[$key]['rate_customer_group_list'] = DB::fetch_all($sql);  
            } 
            $rate_code_time[$key]['brief'] = $this->getBrief($value['frequency'],$value['weekly']);
            $rate_code_time[$key]['priority_str'] = $this->getPriority($value['priority']);          
        }
        //System::debug($rate_code_time); 
        $sql = "SELECT * FROM rate_code";
        $this->map['rate_code_list'] = DB::fetch_all($sql);
        
        $sql = "SELECT * FROM customer";
        $this->map['customer_group'] = DB::fetch_all($sql);
        
        $this->map['rate_code_time']=$rate_code_time;
        $this->parse_layout('list',$this->map);
    }
    public function getMinute($hour){
        $time = explode(':', $hour);
        return ($time[0]*60 + $time[1]);
    }
    public function getBrief($week, $day){
        $str = "";
        if($week == "w"){
            $str = Portal::language("Every_week_on")." ";
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
                $str = Portal::language("Every_week_on_weekends");
            }
            else if($countOutWeek==5 && $countInWeek==0){
                $str = Portal::language("Every_week_on_weekdays");
            }

        }
        else{
            $str=Portal::language("every_day");
        }
        return $str;
    }
    public function getGroupItem(&$arr,$field){
            reset($arr);
            $j = 1;
              $key = array_keys($arr);
              for($i=0 ; $i< count($arr); $i++){
                  $current = current($arr);
                  $next = next($arr);
                  //echo $current[$field]."/".$next[$field];
                  if($current[$field] == $next[$field]){
                      $j++;
                  }
                  else{
                      $arr[$key[$i-$j+1]]['count'] = $j;
                      $j = 1;
                  }
              }
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