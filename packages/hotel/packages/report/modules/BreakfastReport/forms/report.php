<?php
/** BÁO CÁO KHÁCH ĂN SÁNG
 * lấy khoảng thời gian được ăn sáng setting theo hệ thống
 * Dayuse: tinh 1 lượt ăn sáng nếu giờ đến nằm trong khoảng thời gian ăn sáng - Đổi phòng dayuse - lấy ra chặng sau!
 * phòng ở dài ngày.
 **/
class BreakfastReportForm extends Form
{
	function BreakfastReportForm()
	{
		Form::Form('BreakfastReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function draw()
	{
	   $this->map['portal_id_list'] = array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):'';
       
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       
       $this->map['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):32;
       $this->map['no_of_page'] = Url::get('no_of_page')?Url::get('no_of_page'):50;
       $this->map['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
       
       $from_time = Date_Time::to_orc_date($this->map['from_date']);
       $to_time = Date_Time::to_orc_date($this->map['to_date']);
       
              
       $cond = ' (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'BOOKED\') ';
       $cond .= Url::get('portal_id')?' AND (reservation.portal_id=\''.Url::get('portal_id').'\')':'';
       $cond .= "AND ( room_status.in_date<='".$to_time."' AND room_status.in_date>='".$from_time."' )";
        $cond .= Url::get('customer_name')?"AND customer.name = '".Url::get('customer_name')."' " :"" ; 
       $sql = "
                SELECT
                    room_status.id,
                    room_status.in_date,
                    reservation_room.id as reservation_room_id,
                    reservation_room.reservation_id,
                    NVL(reservation_room.adult,0) as adult,
                    NVL(reservation_room.child,0) as child,
                    NVL(reservation_room.child_5,0) as child_5,-- them truong tr.e<5 tuoi
                    reservation_room.time_in,
                    reservation_room.arrival_time,
                    reservation_room.time_out,
                    reservation_room.departure_time,
                    reservation_room.note,
                    reservation_room.change_room_from_rr,
                    reservation_room.change_room_to_rr,
                    reservation_room.breakfast,
                    room.name,
                    -- trung add customer_name
                    customer.name as customer_name
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    -- trung add left outer customer
                    left outer join customer on reservation.customer_id = customer.id
                    left join room on reservation_room.room_id=room.id
                    left join room_level on room.room_level_id=room_level.id
                WHERE
                    ".$cond." AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
               order by room.id asc
                ";
       $recode = DB::fetch_all($sql);
       $items = array();
       $stt=1;
       $this->map['total_room'] = 0;
       $this->map['total_adult'] = 0;
       $this->map['total_child'] = 0;
       $this->map['total_child_5'] = 0;
       $nationality  =array();
       foreach($recode as $key=>$value)
       {
            $time_in = date('H:i',$value['time_in']);
            $time_out = date('H:i',$value['time_out']);
            if($value['arrival_time']==$value['departure_time'])
            {
                /** truong hop dayuser **/
                if($value['change_room_to_rr']!='')
                {
                    unset($recode[$key]);
                }
            }
            else
            {
                if($value['in_date']==$value['arrival_time'])
                {
                    if($this->calc_time(BREAKFAST_FROM_TIME)>=$this->calc_time($time_out) 
                        OR 
                        $this->calc_time(BREAKFAST_TO_TIME)<=$this->calc_time($time_in) 
                        )
                    {
                        unset($recode[$key]);
                    }
                }
                //kimtan thêm
                if($value['in_date']==$value['departure_time'])
                {
                    if($this->calc_time(BREAKFAST_FROM_TIME)<=$this->calc_time($time_out) and $this->calc_time(BREAKFAST_TO_TIME)>=$this->calc_time($time_out))
                    {
                        if($value['change_room_to_rr']!='')
                        unset($recode[$key]);
                    }
                }
            }
            if(isset($recode[$key]))
            {
                if(!isset($items[$value['reservation_room_id']]))
                {
                    //echo $value['adult'].'<br>';
                    $items[$value['reservation_room_id']]['id'] = $value['reservation_room_id'];
                    $items[$value['reservation_room_id']]['reservation_id'] = $value['reservation_id'];
                    $items[$value['reservation_room_id']]['stt'] = $stt++;
                    $items[$value['reservation_room_id']]['room_name'] = $value['name'];
                    $items[$value['reservation_room_id']]['adult'] = $value['adult'];
                    $items[$value['reservation_room_id']]['child'] = $value['child'];
                    $items[$value['reservation_room_id']]['child_5'] = $value['child_5'];// lay ra so tr.e<5t
                    $items[$value['reservation_room_id']]['breakfast'] = $value['breakfast'];
                    $items[$value['reservation_room_id']]['time_in'] = date('H:i d/m/Y',$value['time_in']);
                    $items[$value['reservation_room_id']]['time_out'] = date('H:i d/m/Y',$value['time_out']);
                    $items[$value['reservation_room_id']]['note'] = $value['note'];
                    $items[$value['reservation_room_id']]['customer_name_1'] = $value['customer_name'];
                    $traveller = DB::fetch_all('
                                                    SELECT 
                                                        traveller.id 
                                                       	,country.name_'.Portal::language().' as nationality
                                                        ,country.code_'.Portal::language().' as code_name
                                                        ,concat(concat(traveller.first_name,\' \'),traveller.last_name) as traveller_name 
                                                    from 
                                                        traveller 
                                                        inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id
                                                        left outer join country on country.id=traveller.nationality_id
                                                    Where
                                                        reservation_traveller.reservation_room_id = '.$value['reservation_room_id'].'
                                                ');
                                                //System::debug($traveller);
                    if(!empty($traveller)){
                        foreach($traveller as $k=>$val){
                            if(!isset($nationality[$val['code_name']])){
                                $nationality[$val['code_name']] =1;
                            }else{
                                $nationality[$val['code_name']]++;
                            }
                        }
                    }else{
                        $traveller[1]['id']='';
                        $traveller[1]['code_name']='';
                        $traveller[1]['traveller_name']='';
                    }                            
                    $items[$value['reservation_room_id']]['child_traveller'] = $traveller;
                    $items[$value['reservation_room_id']]['count_child'] = sizeof($traveller)==0?1:sizeof($traveller);
                    $this->map['total_room'] += 1;
                    
                    $this->map['total_adult'] += $value['adult'];
                    $this->map['total_child'] += $value['child'];
                    $this->map['total_child_5'] += $value['child_5'];//tong so cot tr.3<5 tuoi
                
                }
                
                
            }
       }
       $this->map['nationality'] = $nationality;
       $this->parse_layout('report',array('items'=>$items)+$this->map);
      //System::debug($items); 
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}
?>
