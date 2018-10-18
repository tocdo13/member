<?php
class ReportArrivalList extends Form
{
    function ReportArrivalList()
    {
        Form::Form('ReportArrivalList');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');      
    }
    
    function draw()
    {
        $cond ='';
        /** lay dieu kien ngay den cua phong **/
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        $cond .= ' and reservation_room.arrival_time = \''.Date_Time::to_orc_date($this->map['date']).'\' ';
        
        /** lay deu kien xem theo portal **/
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        $_REQUEST['portal_id'] = $this->map['portal_id'];
        $cond.=$this->map['portal_id']!='ALL'?' AND reservation.portal_id=\''.$this->map['portal_id'].'\'':'';
        
        /** lay dieu kien xem theo trang thai **/
        $this->map['status_list'] = array(''=>Portal::language('all'),'NOT_ASSGIN'=>'NOT_ASSGIN','BOOKED'=>'BOOKED','CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT');
        $this->map['status'] = Url::get('status')?Url::get('status'):'';
        if($this->map['status']!='')
        {
            if($this->map['status'] == 'NOT_ASSGIN')
                $cond.=' AND reservation_room.room_id is null AND reservation_room.status = \'BOOKED\' ';
            else
                $cond.=' AND reservation_room.status = \''.$this->map['status'].'\' ';
        }
        
        /**
         * Bao cao thong ke danh sach phong den trong ngay duoc xem.
         * trong do hien thi chi tiet danh sach khach trong phòng
         * gom nhom theo ma recode.
         * thu tu gom nhom
         *      Reservation.id
         *      reservation_room.id
         *      reservation_traveller.id
         * muc tieu lay du lieu
         *      $record = truy van: lay cac phong co ngay den la ngay hom nay. != CANCEL
         *      $traveller_all = lay tat ca khach trong phong co ngay den bang ngay hom nay
         *      foreach $traveller_all. kiem tra su ton tai cua $record gan traveller co recode
        **/
        $record = DB::fetch_all("
                                    SELECT
                                        reservation_room.id
                                        ,NVL(reservation_room.adult,0) as adult 
                                        ,NVL(reservation_room.child,0) + NVL(reservation_room.child_5,0) as child
                                        ,TO_CHAR(reservation_room.arrival_time,'DD/MM/YYYY') as arrival_time
                                        ,TO_CHAR(reservation_room.departure_time,'DD/MM/YYYY') as departure_time
                                        ,reservation_room.departure_time - reservation_room.arrival_time as night
                                        ,reservation_room.time_in
                                        ,reservation_room.time_out
                                        ,reservation_room.note                                        
                                        ,reservation.id as reservation_id
                                        ,customer.name as customer_name
                                        ,room.id as room_id
                                        ,room.name as room_name
                                        ,room_level.name as room_level_name
                                    FROM
                                        reservation_room
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                        inner join customer on customer.id=reservation.customer_id
                                        left join room on room.id=reservation_room.room_id
                                        left join room_level on reservation_room.room_level_id = room_level.id
                                    WHERE
                                        reservation_room.status!='CANCEL'
                                        ".$cond."
                                    ORDER BY
                                        reservation.id,reservation_room.id
                                ");
        $items = array();
        $stt = 1;
        $this->map['total_room']=0;
        $this->map['total_child']=0;
        $this->map['total_adult']=0;
        $this->map['total_night']=0;
        $cond_es_invoid = '';
        $in_date = Date_Time::convert_time_to_ora_date(Date_Time::to_time($this->map['date']));
        $cond_hs = '1=1';
        $house_status_arr = DB::fetch_all('SELECT room_id as id, house_status FROM room_status WHERE house_status is not null and in_date=\''.$in_date.'\'');
        foreach($record as $key=>$value)
        {
            if($cond_es_invoid=='')
            {
                $cond_es_invoid = $key;
            }
            else
            {
                $cond_es_invoid .= ','.$key;
            }
            $house_status = '';
            if(isset($house_status_arr[$value['room_id']]))
            {
                $house_status = $house_status_arr[$value['room_id']]['house_status'];
            }
            if(!isset($items[$value['reservation_id']]))
            {
                $items[$value['reservation_id']]['stt'] = $stt++;
                $items[$value['reservation_id']]['recode'] = $value['reservation_id'];
                $items[$value['reservation_id']]['customer_name'] = $value['customer_name'];
                $items[$value['reservation_id']]['count_child'] = 0;
                $items[$value['reservation_id']]['child'] = array();
            }
            $items[$value['reservation_id']]['count_child']++;
            $items[$value['reservation_id']]['child'][$key] = $value;
            $items[$value['reservation_id']]['child'][$key]['house_status'] = $house_status;
            $items[$value['reservation_id']]['child'][$key]['count_child'] = 0;
            $items[$value['reservation_id']]['child'][$key]['child_child'] = array();
            $items[$value['reservation_id']]['child'][$key]['count_traveler'] = 0;
            $this->map['total_room']++;
            $this->map['total_child']+=$value['child'];
            $this->map['total_night']+=$value['night'];
            $items[$value['reservation_id']]['child'][$key]['extrabed'] = 0;
            $items[$value['reservation_id']]['child'][$key]['baby_cot'] = 0;
        }
        $cond_es = '';
        if($cond_es_invoid!='')
        {
            $cond_es = 'and extra_service_invoice.reservation_room_id in ('.$cond_es_invoid.')';
        }
        $sql = '
            select 
                extra_service_invoice.reservation_room_id || \'_\' || extra_service.code as id,
                extra_service_invoice.reservation_room_id,
                extra_service.code,
                reservation_room.reservation_id,
                sum(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as quantity
            from extra_service_invoice_detail
                inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id
                inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
            where             
            (extra_service.code = \'EXTRA_BED\' or extra_service.code = \'BABY_COT\')
            '.$cond_es.'
            group by extra_service_invoice.reservation_room_id,
            extra_service.code,
            reservation_room.reservation_id
       ';
       $es_arr = DB::fetch_all($sql);
       foreach($es_arr as $id=>$value){
            if(isset($items[$value['reservation_id']]['child'][$value['reservation_room_id']]))
            {
                if($value['code'] == 'EXTRA_BED' and $value['quantity']>0)
                {
                    $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['extrabed']= 1;
                }
                if($value['code'] == 'BABY_COT' and $value['quantity']>0)
                {
                    $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['baby_cot']= 1;
                }
            }
        }
       $traveller_all = DB::fetch_all("
                                        SELECT
                                            reservation_traveller.id
                                            ,traveller.first_name || ' ' || traveller.last_name as traveller_name
                                            ,country.name_".Portal::language()." as country_name
                                            ,reservation_room.id as reservation_room_id
                                            ,reservation.id as reservation_id
                                        FROM
                                            reservation_traveller
                                            inner join traveller on traveller.id=reservation_traveller.traveller_id
                                            inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                            inner join reservation on reservation.id=reservation_room.reservation_id
                                            left join country on country.id=traveller.nationality_id
                                        WHERE
                                            reservation_room.status!='CANCEL'
                                            ".$cond."
                                        ORDER BY
                                            traveller.first_name,traveller.last_name,country.name_".Portal::language()."
                                        ");
        foreach($traveller_all as $key=>$value)
        {
            if(isset($items[$value['reservation_id']]['child'][$value['reservation_room_id']]))
            {
                $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['count_child']++;
                $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['child_child'][$key]['id'] = $key;
                $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['child_child'][$key]['traveller_name'] = $value['traveller_name'];
                $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['child_child'][$key]['country_name'] = $value['country_name'];
                
                if($items[$value['reservation_id']]['child'][$value['reservation_room_id']]['count_child']>1)
                {
                    $items[$value['reservation_id']]['count_child']++;
                }
                $items[$value['reservation_id']]['child'][$value['reservation_room_id']]['count_traveler']++;
            }
        }
        $this->map['items']=$items;
        //System::debug($items); 
        foreach($items as $k=>$v)
        {
            foreach($items[$k]['child'] as $k1=>$v1)
            {
                $this->map['total_adult'] += $v1['adult'];
            }
        }
        //System::debug($this->map);
		$this->parse_layout('report',$this->map);
    }
}

?>
