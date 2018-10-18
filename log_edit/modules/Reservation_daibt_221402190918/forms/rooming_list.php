<?php
/** tieu chi:
    - c?c phong co ngay den  = nhau se gom cung 1 nh?m(b?ng)
    - t? ng?y:ng?y d?n
    - d?n ng?y:ng?y l?n nh?t trong c?c ph?ng c? ng?y d?n c?a 1 nh?m(b?ng)
    - create by kieubg
 **/
class RoomingListForm extends Form
{
	function RoomingListForm()
	{
		Form::Form('RoomingListForm');
		$this->link_css(Portal::template('hotel').'/css/report.css');
	  	$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/rooming_list.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		$this->map = array();
		$sql='select
				reservation.*,
				tour.name AS group_name,
				customer.name AS customer_name,customer.address,customer.phone,customer.email,
				to_char(tour.arrival_time,\'DD/MM/YYYY\') as arrival_time,
				to_char(tour.departure_time,\'DD/MM/YYYY\') as departure_time
			from
				reservation
				left outer join tour on tour.id=reservation.tour_id
				left outer join customer on reservation.customer_id = customer.id
			where
				reservation.id='.Url::iget('id').'';
        $this->map['total_room']=0;        
		if($row = DB::fetch($sql))
        {
			$this->map += $row;
			$this->map['hotel_name'] = HOTEL_NAME;
			$sql = '';
            /** m?ng c?c ng?y d?n trong recode(ph?n nh?m) **/
			$arrival_dates = array();
            /** l?y ra c?c kh?ch thu?c ph?ng c? ng?y d?n trong nh?m $key **/
            $sql='	SELECT
					CONCAT(rr.id,rtr.id) as id,
                    LOWER(t.first_name) as first_name,
                    TO_CHAR(t.birth_date, \'dd/mm/YYYY\') as date_of_birth,
                    LOWER(t.last_name) as last_name,
                    t.gender,
                    DECODE(t.gender,0,\''.Portal::language('female').'\',\''.Portal::language('male').'\') AS gender_at,
					r.name as room_name,
                    r.id as room_id,
					rl.brief_name as room_level,
                    room_type.brief_name as room_type,
					rr.room_level_id,
                    country.name_1,
                    rr.arrival_time,
                    rr.departure_time,
					rtr.special_request as note,
					rr.adult,
					NVL(rr.child,0)+NVL(rr.child_5,0) as child,
                    rr.id as reservation_room_id
				FROM
                    reservation_room rr left join 
					reservation_traveller rtr on rr.id = rtr.reservation_room_id
					left JOIN traveller t ON t.id = rtr.traveller_id
                    left join country on t.nationality_id = country.id  
					LEFT OUTER JOIN room r ON r.id = rr.room_id
                    LEFT JOIN room_type ON room_type.id = r.room_type_id
					LEFT OUTER JOIN room_level rl ON rr.room_level_id = rl.id
				WHERE
					rr.reservation_id = '.$row['id'].' and rr.status != \'CANCEL\'
				ORDER BY
				rr.arrival_time,rr.room_level_id, rr.room_id';
            $travellers=DB::fetch_all($sql);
            $check_room='';
            $check_room_level='';
            $first_traveller_of_room_level=array();
            $first_traveller_of_room=array();
            $this->map['arrival_date']='';
            $this->map['departure_date']='';
            foreach($travellers as $key=>$value){    
                if(!$this->map['arrival_date']){
                    $this->map['arrival_date']=Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
                }
                if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/'))>Date_Time::to_time($this->map['departure_date'])){
                    $this->map['departure_date']=Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
                }
                if(isset($arrival_dates[$value['arrival_time']][$value['room_level_id']]['first_traveller'])){
                    $value['first_traveller_of_room_level']=0;
                }else{
                    $value['first_traveller_of_room_level']=1;
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_adult']=0;
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_child']=0;
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_room']=0;
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_room_level_traveller']=0;
                    $first_traveller_of_room_level[$value['arrival_time']][$value['room_level_id']]=$value['id'];
                    $arrival_dates[$value['arrival_time']][$value['room_level_id']]['first_traveller']=1;
                }
                 if(isset($arrival_dates[$value['arrival_time']][$value['room_level_id']][$value['reservation_room_id']]['first_traveller'])){
                    $value['first_traveller_of_room']=0;
                }else{
                    $value['count_traveller']=1;
                    $value['first_traveller_of_room']=1;
                    $first_traveller_of_room[$value['arrival_time']][$value['room_level_id']][$value['reservation_room_id']]=$value['id'];
                    $arrival_dates[$value['arrival_time']][$value['room_level_id']][$value['reservation_room_id']]['first_traveller']=1;
                }
                if(!isset($arrival_dates[$value['arrival_time']]['arrival_time'])){
                    $arrival_dates[$value['arrival_time']]['arrival_time']=$value['arrival_time'];
                }
                if(!isset($arrival_dates[$value['arrival_time']]['departure_time'])){
                    $arrival_dates[$value['arrival_time']]['departure_time']=$value['departure_time'];
                }else{
                    if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/'))>Date_Time::to_time(Date_Time::convert_orc_date_to_date($arrival_dates[$value['arrival_time']]['departure_time'],'/'))){
                        $arrival_dates[$value['arrival_time']]['departure_time']=$value['departure_time'];
                    }
                }
                $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['travellers'][$key]=$value;
                //System::debug($arrival_dates);exit();
                //echo $value['reservation_room_id'].'_'.$check_room.'++';
                if($value['reservation_room_id']!=$check_room){
                    $this->map['total_room']++;
                    if(isset($arrival_dates[$value['arrival_time']]['count_adult'])){
                        $arrival_dates[$value['arrival_time']]['count_adult']+=$value['adult'];
                        $arrival_dates[$value['arrival_time']]['count_child']+=$value['child'];
                        $arrival_dates[$value['arrival_time']]['count_room']++;
                    }else{
                        $arrival_dates[$value['arrival_time']]['count_adult']=$value['adult'];
                        $arrival_dates[$value['arrival_time']]['count_child']=$value['child'];
                        $arrival_dates[$value['arrival_time']]['count_room']=1;
                    } 
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_adult']+=$value['adult'];
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_child']+=$value['child'];
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_room']++;
                }else{
                    $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['travellers'][$first_traveller_of_room[$value['arrival_time']][$value['room_level_id']][$value['reservation_room_id']]]['count_traveller']++;
                }
                $arrival_dates[$value['arrival_time']]['room_level'][$value['room_level_id']]['count_room_level_traveller']++;
                $check_room=$value['reservation_room_id'];
                $check_room=$value['reservation_room_id'];
            }
            //System::debug($first_traveller_of_room_level);
            $this->map['items']=$arrival_dates;
			$this->parse_layout('rooming_list_new',$this->map);
		}
	}
}
?>