<?php
class ReportRevenueGroupOfTypeForm extends Form
{
	function ReportRevenueGroupOfTypeForm()
	{
		Form::Form('ReportRevenueGroupOfTypeForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');     
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
	   $this->map = array();
       /** chọn portal xem - SELECTBOX_STRING **/
       $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
       $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());
       
       /** chọn các bộ phận trong portal dó - SELECTBOX_STRING **/
       $active_department = DB::fetch_all('Select 
                                                department.code as id,
                                                department.name_'.Portal::language().' as name 
                                            from 
                                                department 
                                                inner join portal_department on department.code = portal_department.department_code 
                                            where
                                                portal_department.portal_id = \''.$this->map['portal_id'].'\'
                                                and department.parent_id = 0 AND department.code != \'WH\'
                                        ');
       
       $this->map['list_list'] = array(''=>Portal::language('all'),'EXTRA_SERVICE'=>portal::language('extra_service_list'))+String::get_list($active_department);
       $this->map['list'] = Url::get('list')?Url::get('list'):'';
       $this->map['status_list'] = array(''=>Portal::language('all'),'BOOKED'=>'BOOKED','CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT');
       $this->map['status'] = Url::get('status')?Url::get('status'):'';
       /** chọn người tạo - SELECTBOX_STRING **/
       $users = DB::fetch_all('select account.id,party.full_name as name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');
       $this->map['create_user_list'] = array(''=>Portal::language('all'))+String::get_list($users);
       $this->map['create_user'] = Url::get('create_user')?Url::get('create_user'):'';
       
       /** chọn thời gian - DATE **/
       $this->map['date_from'] = Url::get('date_from')?Url::get('date_from'):date('d/m/Y');
       $this->map['date_to'] = Url::get('date_to')?Url::get('date_to'):date('d/m/Y');
       
       /** chọn giờ **/
       $this->map['time_in'] = Url::get('time_in')?Url::get('time_in'):'00:00';
       $this->map['time_out'] = Url::get('time_out')?Url::get('time_out'):'23:59';
       
       /** chọn mã mặc định - NUMBER **/
       $this->map['re_code'] = Url::get('re_code')?Url::get('re_code'):'';
       
       /** chọn số phiếu - STRING **/
       $this->map['number_of_vote'] = Url::get('number_of_vote')?Url::get('number_of_vote'):'';
       
       /** chọn số phòng - STRING **/
       $this->map['room_number'] = Url::get('room_number')?Url::get('room_number'):'';
       $this->map['customer_id'] = Url::get('customer_id')?Url::get('customer_id'):'';
       $this->map['status'] = Url::get('status')?Url::get('status'):'';
       /** khởi tạo option search **/
       /** lễ tân **/
       $cond_resservation = '( room_status.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND room_status.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** dịch vụ **/
       $cond_extra_service = '( extra_service_invoice_detail.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND extra_service_invoice_detail.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** buồng **/
       $cond_housekeeping_invoice = '( housekeeping_invoice.time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).' AND housekeeping_invoice.time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' )';
       /** bar **/
       $cond_bar = '( bar_reservation.time_in <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time($this->map['time_out'])).' AND bar_reservation.time_out >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time($this->map['time_in'])).' ) AND ( bar_reservation.status=\'CHECKIN\' OR bar_reservation.status=\'CHECKOUT\' OR bar_reservation.status=\'BOOKED\' )';
       /** Spa **/
       $cond_spa = '(massage_reservation_room.time<='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' AND massage_reservation_room.time>='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).')';
       /** đặt tiệc **/
       $cond_party = '(party_reservation.checkin_time<='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' AND party_reservation.checkout_time>='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).')';
       /** bán hàng **/
       $cond_vend = '( ve_reservation.time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).' AND ve_reservation.time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' ) AND ( ve_reservation.status=\'CHECKIN\' OR ve_reservation.status=\'CHECKOUT\' OR ve_reservation.status=\'BOOKED\' )';
       /** bán vé **/
       $cond_ticket = '( ticket_invoice.date_used >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND ticket_invoice.date_used <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** karaoke **/
       $cond_karaoke = '( karaoke_reservation.time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).' AND karaoke_reservation.time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' ) AND ( karaoke_reservation.status=\'CHECKIN\' OR karaoke_reservation.status=\'CHECKOUT\' OR karaoke_reservation.status=\'BOOKED\' )';
       
       
       /** tìm kiếm theo portal **/
       if($this->map['portal_id']!='')
       {
             $cond_resservation .= 'AND (reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_extra_service .= 'AND (extra_service_invoice.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_housekeeping_invoice .= 'AND (housekeeping_invoice.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_bar .= 'AND (bar_reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_spa .= 'AND (massage_reservation_room.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_party .= 'AND (party_reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_vend .= 'AND (ve_reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_ticket .= 'AND (ticket_invoice.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_karaoke .= 'AND (karaoke_reservation.portal_id=\''.$this->map['portal_id'].'\')';
       }
       /** tìm kiếm theo mã mặc định **/
       if($this->map['re_code']!='')
       {
             $cond = 'AND (reservation.id='.$this->map['re_code'].')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.=$cond;
             $cond_karaoke.=$cond;
             $cond_spa .= $cond;
             $cond_party .= 'AND (party_reservation.id=0)';
             $cond_vend .= 'AND (ve_reservation.id=0)';
             $cond_ticket .= 'AND (ticket_invoice.id=0)';
       }
       /** tìm kiếm theo số phiếu **/
       if($this->map['number_of_vote']!='')
       {
             if(is_numeric($this->map['number_of_vote']))
             {
                $cond_resservation.='AND (reservation.id='.$this->map['number_of_vote'].')';
                $cond_housekeeping_invoice.='AND (housekeeping_invoice.id='.$this->map['number_of_vote'].'AND housekeeping_invoice.type=\'EQUIP\')';
                $cond_extra_service.='AND (extra_service_invoice.bill_number=\''.$this->map['number_of_vote'].'\')';
                $cond_bar.='AND (bar_reservation.code=\'\')';
                $cond_karaoke.='AND (karaoke_reservation.code=\'\')';
                $cond_spa .= 'AND (massage_reservation_room.id=\''.$this->map['number_of_vote'].'\')';
                $cond_party .= 'AND (party_reservation.id=\''.$this->map['number_of_vote'].'\')';
                $cond_vend .= 'AND (ve_reservation.id=0)';
                $cond_ticket .= 'AND (ticket_invoice.id=0)';
             }
             else
             {
                $cond_resservation.='AND (reservation.id=0)';
                $cond_extra_service.='AND (extra_service_invoice.bill_number=\''.strtoupper($this->map['number_of_vote']).'\')';
                $cond_bar.='AND (bar_reservation.code=\''.$this->map['number_of_vote'].'\')';
                $cond_karaoke.='AND (karaoke_reservation.code=\''.$this->map['number_of_vote'].'\')';
                $cond_vend.='AND (ve_reservation.code=\''.$this->map['number_of_vote'].'\')';
                $cond_ticket.='AND (upper(ticket.name)=\''.strtoupper($this->map['number_of_vote']).'\')';
                if(strpos($this->map['number_of_vote'],'_'))
                {
                    $arr_vote = explode("_",$this->map['number_of_vote']);
                    if(strtoupper($arr_vote[0])=='MN')
                    {
                        $cond_housekeeping_invoice.='AND (housekeeping_invoice.position='.$arr_vote[1].'AND housekeeping_invoice.type=\'MINIBAR\')';
                    }
                    elseif(strtoupper($arr_vote[0])=='LD')
                    {
                        $cond_housekeeping_invoice.='AND (housekeeping_invoice.position='.$arr_vote[1].'AND housekeeping_invoice.type=\'LAUNDRY\')';
                    }
                    else
                    {
                        $cond_housekeeping_invoice.= 'AND (housekeeping_invoice.type=\'OTHER\')';
                    }
                }
                else
                {
                    $cond_housekeeping_invoice.= 'AND (housekeeping_invoice.type=\'OTHER\')';
                }
                $cond_spa .= 'AND (massage_reservation_room.id=0)';
                $cond_party .= 'AND (party_reservation.id=0)';
             }
       }
       /** tìm kiếm theo người tạo **/
       if($this->map['create_user']!='')
       {
             $cond = 'AND (party.user_id=\''.$this->map['create_user'].'\')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.=$cond;
             $cond_karaoke.=$cond;
             $cond_spa.=$cond;
             $cond_party.=$cond;
             $cond_vend.=$cond;
             $cond_ticket.=$cond;
       }
       /** tìm kiếm theo số phòng **/
       if($this->map['room_number']!='')
       {
             $cond = 'AND (room.name=\''.$this->map['room_number'].'\')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.=$cond;
             $cond_karaoke.=$cond;
             $cond_spa.=$cond;
             $cond_party .= 'AND (party_reservation.id=0)';
             $cond_vend .= 'AND (ve_reservation.id=0)';
             $cond_ticket.= 'AND (ticket_invoice.id=0)';
       }
       if($this->map['customer_id']!='')
       {
             $cond = 'AND (reservation.customer_id=\''.$this->map['customer_id'].'\')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.='AND (reservation.customer_id=\''.$this->map['customer_id'].'\' or bar_reservation.customer_id=\''.$this->map['customer_id'].'\')';
             $cond_karaoke.='AND (reservation.customer_id=\''.$this->map['customer_id'].'\' or karaoke_reservation.customer_id=\''.$this->map['customer_id'].'\')';
             //$cond_spa.=$cond;
             //$cond_party .= 'AND (party_reservation.id=0)';
             $cond_vend .= 'AND (reservation.customer_id=\''.$this->map['customer_id'].'\' or ve_reservation.customer_id=\''.$this->map['customer_id'].'\')';
             //$cond_ticket.= 'AND (ticket_invoice.id=0)';
       }
       if($this->map['status']!='')
       {
             $cond = 'AND (reservation_room.status=\''.$this->map['status'].'\')';
             $cond_resservation.=$cond; 
       }
       /** lấy dữ liệu bộ phận lễ tân **/
       $reservation = array();
       if(($this->map['list']=='' OR $this->map['list']=='REC') AND $this->id_like_array('REC',$active_department))
       {
             $reservation = $this->get_reservation_revenue($cond_resservation);
             //System::debug($reservation);
       }
       
       /** lấy dữ liệu dịch vụ khác **/
       $extra_service = array();
       $list_extra_service = array();
       if(($this->map['list']=='' OR $this->map['list']=='REC' OR $this->map['list']=='EXTRA_SERVICE'))
       {
            if($this->map['list']=='REC')
                $cond_extra_service .= "AND (extra_service_invoice.payment_type='ROOM')";
            elseif($this->map['list']=='EXTRA_SERVICE')
                $cond_extra_service .= ' AND extra_service.code != \'EARLY_CHECKIN\' AND extra_service.code != \'LATE_CHECKOUT\' AND extra_service.code != \'LATE_CHECKIN\'';
            $extra_service = $this->get_extra_service_revenue($cond_extra_service);
       }
       $key_word = '';
       foreach($extra_service as $key=>$value)
       {
            $key_arr = explode('_',$key);
            if($key_arr[0]!=$key_word)
            {
                $key_word = $key_arr[0];
                $list_extra_service[$key_word]['id'] = $key_word;
                $list_extra_service[$key_word]['total'] = $value['price'];
            }
            else
            {
                $list_extra_service[$key_word]['total'] += $value['price'];
            }
       }
       
       /** lấy dữ liệu minibar **/
       $minibar = array();
       if(($this->map['list']=='' OR $this->map['list']=='HK') AND $this->id_like_array('HK',$active_department))
        $minibar = $this->get_housekeeping($cond_housekeeping_invoice,'MINIBAR');
       
       /** lấy dữ liệu giặt là **/
       $laundry = array();
       if(($this->map['list']=='' OR $this->map['list']=='HK') AND $this->id_like_array('HK',$active_department))
        $laundry = $this->get_housekeeping($cond_housekeeping_invoice,'LAUNDRY');
       
       /** lấy dữ liệu HĐ đền bù **/
       $equipment = array();
       if(($this->map['list']=='' OR $this->map['list']=='HK') AND $this->id_like_array('HK',$active_department))
        $equipment = $this->get_housekeeping($cond_housekeeping_invoice,'EQUIP');
       
       /** lấy dữ liệu nhà hàng **/
       $bar = array();
       if(($this->map['list']=='' OR $this->map['list']=='RES') AND $this->id_like_array('RES',$active_department))
       $bar = $this->get_bar($cond_bar);
       
       /** lấy dữ liệu Spa **/
       $spa = array();
       if(($this->map['list']=='' OR $this->map['list']=='SPA') AND $this->id_like_array('SPA',$active_department))
       $spa = $this->get_spa($cond_spa);
       
       /** lấy dữ liệu đặt tiệc **/
       $party=array();
       if(($this->map['list']=='' OR $this->map['list']=='BANQUET') AND $this->id_like_array('BANQUET',$active_department))
       $party = $this->get_party($cond_party);
       
       /** lấy dữ liệu bán hàng **/
       $vend=array();
       if(($this->map['list']=='' OR $this->map['list']=='VENDING') AND $this->id_like_array('VENDING',$active_department))
       $vend = $this->get_vend($cond_vend);
       
       /** lấy dữ liệu bán vé **/
       $ticket = array();
       if(($this->map['list']=='' OR $this->map['list']=='TICKET') AND $this->id_like_array('TICKET',$active_department))
       $ticket = $this->get_ticket($cond_ticket);
       
       /** lấy dữ liệu karaoke **/
       $karaoke = array();
       if(($this->map['list']=='' OR $this->map['list']=='KARAOKE') AND $this->id_like_array('KARAOKE',$active_department))
       $karaoke = $this->get_karaoke($cond_karaoke);
       
       
       /** truyền layout **/
       $_REQUEST += $this->map;
       $this->parse_layout('report',array(
                                        'reservation'=>$reservation,
                                        'extra_service'=>$extra_service,
                                        'list_extra_service'=>$list_extra_service,
                                        'minibar'=>$minibar,
                                        'laundry'=>$laundry,
                                        'equip'=>$equipment,
                                        'bar'=>$bar,
                                        'spa'=>$spa,
                                        'party'=>$party,
                                        'vend'=>$vend,
                                        'ticket'=>$ticket,
                                        'karaoke'=>$karaoke
                                        )+$this->map);
       //**************************************************************************************************\\
	}
    
    /** hàm lấy doanh thu phòng **/
    function get_reservation_revenue($cond)
    {
        $sql = "
                SELECT 
                    concat('RESERVATION_',room_status.id) as id
                    ,room_status.in_date
                    ,room_status.change_price as price
                    ,room.name as room
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                    ,party.name_".Portal::language()." as user_name
                    ,reservation_room.note
                    ,reservation_room.net_price
                    ,reservation_room.tax_rate
                    ,reservation_room.service_rate
                    ,reservation_room.reduce_balance
                    ,reservation_room.reduce_amount
                    ,reservation_room.arrival_time
                    ,reservation_room.departure_time
                    ,reservation_room.status as reservation_room_status
                    ,NVL(reservation_room.change_room_from_rr,0) as change_from
                    ,NVL(reservation_room.change_room_to_rr,0) as change_to
                    ,reservation_room.foc
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,customer.name as customer_name
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                    left join party on reservation_room.user_id=party.user_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    left join room on reservation_room.room_id=room.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    inner join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond." AND reservation_room.status!='CANCEL'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    room.name ,room_status.in_date,reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            
            /** tinh gia **/
            if(($value['arrival_time']==$value['departure_time'] AND $value['change_to']!=0) OR ($value['arrival_time']!=$value['departure_time'] AND $value['in_date']==$value['departure_time']))
            {
                /** loai bo th doi phong dayuse **/
                unset($report[$key]);
            }
            else
            {
                if($value['net_price']==1 AND $value['reduce_balance']==0 AND $value['reduce_amount']==0)
                {
                    /** gia da co thue phi va khong co giam gia **/
                    $report[$key]['price'] = $value['price'];
                }
                else
                {
                    if($value['net_price']==1)
                    {
                        $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                    }
                    $value['price'] = $value['price'] - ($value['price']*$value['reduce_balance']/100);
                    if($value['in_date']==$value['arrival_time'])
                    {
                        $value['price'] = $value['price'] - $value['reduce_amount'];
                    }
                    $value['price'] = $value['price']*((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                    $report[$key]['price'] = $value['price'];
                }
                if($value['foc']!='' OR $value['foc_all']!=0)
                {
                    $report[$key]['price'] = 0;
                }
                if($value['reservation_room_status']=='CANCEL')
                {
                    $report[$key]['price'] = 0;
                    $report[$key]['note'] = $value['note']."<i> ( Phòng đổi trạng thái từ BOOK->CANCEL )</i>";
                }
            }
            if(isset($report[$key]))
            {
                $report[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date']);
                $report[$key]['link'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
                $report[$key]['number_of_vote'] = $value['reservation_id'];
            }
        }
        return $report;
    }
    /** hàm lấy doanh thu dịch vụ **/
    function get_extra_service_revenue($cond)
    {
        $sql = "
                SELECT
                    concat(concat(extra_service.name,'_'),extra_service_invoice_detail.id) as id
                    ,CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + ((((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price) + (((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price)
                   END as price
                    ,extra_service_invoice_detail.quantity
                    ,extra_service_invoice_detail.in_date
                    ,extra_service_invoice_detail.percentage_discount
                    ,extra_service_invoice_detail.amount_discount
                    ,extra_service_invoice_detail.note
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.status as reservation_room_status
                    ,reservation.id as reservation_id
                    ,extra_service_invoice.id as extra_service_invoice_id
                    ,extra_service_invoice.bill_number
                    ,extra_service_invoice.net_price
                    ,extra_service_invoice.tax_rate
                    ,extra_service_invoice.service_rate
                    ,customer.name as customer_name
                FROM
                    extra_service_invoice_detail
                    inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    left join party on party.user_id=extra_service_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond."
                    and reservation_room.status !='CANCEL'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    extra_service.name, extra_service_invoice_detail.id
                ";
                
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            /*if($value['foc']!='' OR $value['foc_all']!=0)
                {
                    $report[$key]['price'] = 0;
                }*/
            if($value['reservation_room_status']=='CANCEL')
            {
                $report[$key]['price'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( Phòng đổi trạng thái từ BOOK->CANCEL )</i>";
            }
            if($value['foc_all']!=0)
                {
                    $report[$key]['price'] = 0;
                }
            $report[$key]['link'] = "?page=extra_service_invoice&cmd=view_receipt&id=".$value['extra_service_invoice_id'];
            $report[$key]['number_of_vote'] = $value['bill_number'];
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            $report[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date']);
            
            unset($report[$key]['extra_service_invoice_id']);
            unset($report[$key]['foc_all']);
            unset($report[$key]['quantity']);
            unset($report[$key]['percentage_discount']);
            unset($report[$key]['amount_discount']);
            unset($report[$key]['net_price']);
            unset($report[$key]['tax_rate']);
            unset($report[$key]['service_rate']);
        }
        return $report;
    }
    /** hàm lấy doanh thu buồng **/
    function get_housekeeping($cond,$type)
    {
        $sql = "
                SELECT
                    concat('".$type."_',housekeeping_invoice.id) as id
                    ,housekeeping_invoice.id as housekeeping_invoice_id
                    ,housekeeping_invoice.position
                    ,housekeeping_invoice.time as in_date
                    ,housekeeping_invoice.total as price
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.status as reservation_room_status
                    ,reservation.id as reservation_id
                    ,housekeeping_invoice.note
                    ,customer.name as customer_name
                FROM
                    housekeeping_invoice
                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room on room.id=reservation_room.room_id
                    inner join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=housekeeping_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond."
                    AND housekeeping_invoice.type = '".$type."'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    housekeeping_invoice.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
            }
            if($value['reservation_room_status']=='CANCEL')
            {
                $report[$key]['price'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( Phòng đổi trạng thái từ BOOK->CANCEL )</i>";
            }
            $report[$key]['number_of_vote'] = $value['position'];
            if($type=='MINIBAR')
                $report[$key]['link'] = "?page=minibar_invoice&id=".$value['housekeeping_invoice_id'];
            elseif($type=='LAUNDRY')
                $report[$key]['link'] = "?page=laundry_invoice&id=".$value['housekeeping_invoice_id'];
            else
            {
                $report[$key]['link'] = "?page=equipment_invoice&id=".$value['housekeeping_invoice_id'];
                $report[$key]['number_of_vote'] = $value['housekeeping_invoice_id'];
            }
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            unset($report[$key]['foc_all']);
            unset($report[$key]['housekeeping_invoice_id']);
        }
        return $report;
    }
    /** hàm lấy doanh thu nhà hàng **/
    function get_bar($cond)
    {
        $sql = "
                SELECT
                    concat('BAR_',bar_reservation.id) as id
                    ,bar_reservation.code
                    ,bar_reservation.time as in_date
                    ,bar_reservation.total as price
                    ,bar_reservation.id as bar_reservation_id
                    ,bar_reservation.bar_id as bar_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,bar_reservation.note
                    ,cr.name as customer_name_r
                    ,cb.name as customer_name_b
                FROM
                    bar_reservation
                    left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=bar_reservation.user_id
                    left join customer cr on reservation.customer_id = cr.id
                    left join customer cb on bar_reservation.customer_id = cb.id
                WHERE
                    ".$cond."
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    bar_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
            }
            
            $report[$key]['link']="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=".$value['bar_reservation_id']."&bar_id=".$value['bar_id']."";
            
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            unset($report[$key]['bar_reservation_id']);
            unset($report[$key]['bar_id']);
            unset($report[$key]['foc_all']);
        }
        return $report;
    }
    
    /** Hàm lấy doanh thu spa **/
    function get_spa($cond)
    {
        $sql = "
                SELECT
                    concat('SPA_',massage_reservation_room.id) as id
                    ,massage_reservation_room.id as massage_reservation_room_id
                    ,massage_reservation_room.time as in_date
                    ,massage_reservation_room.total_amount as price
                    ,massage_reservation_room.note as note
                    ,reservation_room.id as reservation_room_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation.id as reservation_id
                    ,party.name_".Portal::language()." as user_name
                    ,room.name as room
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=massage_reservation_room.user_id
                WHERE
                    ".$cond."
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
            }
            
            $report[$key]['link']="?page=massage_daily_summary&cmd=invoice&id=".$value['massage_reservation_room_id']."";
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            unset($report[$key]['foc_all']);
        }
        return $report;      
    }
    /** hàm lấy doanh thu đặt tiệc **/
    function get_party($cond)
    {
        $sql = "
                SELECT
                    concat('PARTY_',party_reservation.id) as id
                    ,party_reservation.id as party_reservation_id
                    ,party_reservation.party_type
                    ,party_reservation.time as in_date
                    ,party_reservation.total as price
                    ,party_type.note || ' ' || party_reservation.note as note
                    ,party.name_".Portal::language()." as user_name
                FROM
                    party_reservation
                    inner join party_type on party_type.id=party_reservation.party_type
                    inner join party on party.user_id=party_reservation.user_id
                WHERE
                    ".$cond."
                    AND party_reservation.status!='CANCEL'
                ORDER BY
                    party_reservation.time, party_reservation.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            $report[$key]['link']="?page=banquet_reservation&cmd=".$value['party_type']."&action=edit&id=".$value['party_reservation_id'];
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "#";
        }
        return $report;
    }
    
    /** hàm lấy doanh thu bán hàng **/
    function get_vend($cond)
    {
        $sql = "
                SELECT
                    concat('VEND_',ve_reservation.id) as id
                    ,ve_reservation.code
                    ,ve_reservation.time as in_date
                    ,ve_reservation.total as price
                    ,ve_reservation.id as ve_reservation_id
                    ,ve_reservation.department_id
                    ,ve_reservation.department_code
                    ,party.name_".Portal::language()." as user_name
                    ,ve_reservation.note
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.reservation_id as reservation_id
                    ,customer.name as customer_name
                FROM
                    ve_reservation
                    inner join party on party.user_id=ve_reservation.user_id
                    LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                    LEFT JOIN reservation ON reservation_room.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond."
                ORDER BY
                    ve_reservation.time, ve_reservation.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            $report[$key]['link']="?page=automatic_vend&cmd=edit&id=".$value['ve_reservation_id']."&department_id=".$value['department_id']."&department_code=".$value['department_code'];
            
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
        }
        return $report;
    }
    
    /** hàm lấy doanh thu bán vé **/
    function get_ticket($cond)
    {
        $sql = "
                SELECT
                    concat('TICKET_',ticket_invoice.id) as id
                    ,ticket_invoice.total as price
                    ,ticket_invoice.date_used as in_date
                    ,ticket.name as number_of_vote
                    ,party.name_".Portal::language()." as user_name
                    ,'Ticket group ' || ticket_group.name as note
                FROM
                    ticket_invoice
                    inner join ticket on ticket.id=ticket_invoice.ticket_id
                    inner join ticket_group on ticket_group.id=ticket_invoice.ticket_area_id
                    inner join party on party.user_id=ticket_invoice.user_id
                WHERE
                    ".$cond."
                ORDER BY
                    ticket_invoice.date_used, ticket_invoice.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            $report[$key]['link']="#";
            $report[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date']);
            $report[$key]['link_recode'] = "#";
        }
        return $report;
    }
    /** hàm lấy doanh thu karaoke **/
    function get_karaoke($cond)
    {
        $sql = "
                SELECT
                    concat('KARAOKE_',karaoke_reservation.id) as id
                    ,karaoke_reservation.code
                    ,karaoke_reservation.time as in_date
                    ,karaoke_reservation.total as price
                    ,karaoke_reservation.id as karaoke_reservation_id
                    ,karaoke_reservation.karaoke_id as karaoke_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,karaoke_reservation.note
                FROM
                    karaoke_reservation
                    left join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=karaoke_reservation.user_id
                WHERE
                    ".$cond."
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    karaoke_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        foreach($report as $key=>$value)
        {
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
            }
            
            $report[$key]['link']="?page=karaoke_touch&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=f7531e2d0ea27233ce00b5f01c5bf335&5ebeb6065f64f2346dbb00ab789cf001=1&id=".$value['karaoke_reservation_id']."&karaoke_id=".$value['karaoke_id']."";
            
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
        }
        return $report;
    }
    
    /** hàm định dạng giờ sang unit **/
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
    /** hàm kiểm tra sự tồn tại của một Id trong mảng **/
    
    function id_like_array($id,$array)
    {
        if(sizeof($array)>0)
        {
            $check  = false;
            foreach($array as $key=>$value)
            {
                if($id==$key)
                {
                    $check = true;
                }
            }
            return $check;
        }
        else
        {
            return false;
        }
    }
    
}
?>
