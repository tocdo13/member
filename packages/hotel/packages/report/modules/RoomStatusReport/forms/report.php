<?php
class RoomStatusReportForm extends Form{
	function RoomStatusReportForm(){
		Form::Form('RoomStatusReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
		Url::redirect_current(array('portal_id','in_date'));
	}
	function draw(){
		require_once 'packages/core/includes/utils/lib/report.php';
		$in_date = Url::get('in_date')?Date_Time::to_orc_date(Url::get('in_date')):Date_Time::to_orc_date(date('d/m/Y',time()));
        $checkout=$booked=$dayuse=$dirty=$vacan_room=$occupied=$repair=$departure=$book_not_room= $showroom_rooms=$clean_rooms=$extrabed_rooms=  array();
        $view_all = true;
        //phân làm 2 mảng , 1 mảng chứa những phòng sạch , và mảng còn lại
        //trang thái 1 lấy ra tất cả các phòng sach
        $pa='#default';
        $sql='
            select room.id , room.name from room
            where 
                room.id  NOT IN (
                    select 
                        room_id as id
                    from 
                        room_status 
                    where 
                        in_date=\''.$in_date.'\' 
                        and room_id is not NULL
                        and( status =\'AVAILABLE\'
                        or status=\'OCCUPIED\'
                        or status=\'BOOKED\'
                )
            UNION ALL
            select id from room where name like \'%'.'PA'.'%\'
            )'.(Url::get('portal_id')?' and room.portal_id=\''.Url::get('portal_id').'\'':'').'
            order by room.id
             ';
            $vacan_room = DB::fetch_all($sql); 
        //tất cả các trạng thái còn lại gộm lại chung là một mảng   
         $sql2 = '
                select 
                    room_status.id,room.id as room_id, room.name,room_status.status,
                    room_status.house_status,room_status.in_date 
                    ,rr.status as rr_status, rr.arrival_time,rr.departure_time
                    ,room_status.reservation_room_id
                from 
                    room 
                    inner join room_status on room_status.room_id = room.id
                    left join reservation_room rr on rr.id = room_status.reservation_room_id
                where room.id IN
                (   
                    select room_id as id 
                    from room_status 
                    where 
                        in_date=\''.$in_date.'\'
                        and room_id is not NULL
                        and( status =\'AVAILABLE\' or status=\'OCCUPIED\' or status=\'BOOKED\' ) 
                )
                '.(Url::get('portal_id')?' and room.portal_id=\''.Url::get('portal_id').'\'':'').'
                and  name not like \'%'.'PA'.'%\' and in_date=\''.$in_date.'\'
                union all                   
                select 
                    room_status.id,room.id as room_id, room.name,room_status.status,
                    room_status.house_status,room_status.in_date 
                    ,rr.status as rr_status, rr.arrival_time,rr.departure_time
                    ,room_status.reservation_room_id
                from 
                    room_status 
                    left join room on room_status.room_id = room.id
                    inner join reservation_room rr on rr.id = room_status.reservation_room_id
                where 
                    room_status.room_id is NULL and room_status.in_date=\''.$in_date.'\' 
                order by id
             ';        
        $busy_room = DB::fetch_all($sql2);
        $sql_extra_bed = '
            select 
                reservation_room.id as id
            from 
                extra_service_invoice_detail
                inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
            where 
                extra_service_invoice_detail.in_date=\''.$in_date.'\'
                and extra_service.code = \'EXTRA_BED\'
                and reservation_room.status != \'CANCEL\'
        ';
        $extrabed_arr = DB::fetch_all($sql_extra_bed);
        $book_not_room=0;  
        foreach($busy_room as $key=>$val){
            if($val['status']=='BOOKED'){
                $booked[$val['room_id']] = array('name'=>$val['name']);
                unset($busy_room[$key]);
            }
            if($val['status']=='OCCUPIED'){
                $occupied[$val['room_id']] = array('name'=>$val['name']);
            }
            if($val['arrival_time'] == $val['departure_time'] and $val['arrival_time'] !=''){
                $dayuse[$val['room_id']] = array('name'=>$val['name']);
            }
            if($val['house_status']=='DIRTY'){
                $dirty[$val['room_id']] = array('name'=>$val['name']);
            }
            if($val['house_status']=='REPAIR'){
                $repair[$val['room_id']] = array('name'=>$val['name']);
            }
            //giap.ln add truong hop showroom and clean room
            if($val['house_status']=='SHOWROOM') 
            {
                $showroom_rooms[$val['room_id']] = array('name'=>$val['name']);
            }
            if($val['house_status']=='CLEAN')
            {
                $clean_rooms[$val['room_id']] = array('name'=>$val['name']);
            }
            //end giap.ln 
            if(strtotime($val['departure_time'])==strtotime($in_date) and $val['rr_status']!='BOOKED' and $val['rr_status']!='CANCEL'){
                 $departure[$val['room_id']] =array('name'=>$val['name']);
            }
            if($val['rr_status']=='CHECKOUT'){
                 $checkout[$val['room_id']] = array('name'=>$val['name']);
                unset($busy_room[$key]);
            }
            if($val['room_id']==''){
                $book_not_room ++;
                unset($busy_room[$key]);
            }
            if(isset($extrabed_arr[$val['reservation_room_id']])){
                $extrabed_rooms[$val['reservation_room_id']] = array('name'=>$val['name']);
            }
        }
            
          
          $this->map['in_date'] = Date_Time::convert_orc_date_to_date($in_date,'/');
          $this->map['vacan_dirty_rooms'] = $dirty;
          $this->map['vacant_rooms'] = $vacan_room;
          $this->map['occupied_rooms']=$occupied;
          $this->map['suspence_rooms'] =$repair;
          $this->map['room_day_used'] =$dayuse;
          $this->map['departure_rooms'] = $departure;
          $this->map['checkout_rooms'] = $checkout;
          $this->map['booked_rooms'] = ksort($booked);
          $this->map['view_all'] = $view_all;
          $this->map['booked_without_room']=$book_not_room;
          $this->map['show_rooms'] = $showroom_rooms;
          $this->map['clean_rooms'] = $clean_rooms;
          /** oanh add **/
          $this->map['extrabed_rooms'] = $extrabed_rooms;
          
          if(count(Portal::get_portal_list()) == 3)
    		  $this->map['portal_id_list']  = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
          else 
              $this->map['portal_id_list']  = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
          $this->parse_layout('report',$this->map);		
	}
}
?>
