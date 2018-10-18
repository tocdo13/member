<?php
class GeneralRevenueByDayReportForm extends Form
{
	function GeneralRevenueByDayReportForm()
	{
		Form::Form('GeneralRevenueByDayReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');     
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
	   //echo Url::get('type'); 
	   $this->map = array();
       /** chọn portal xem - SELECTBOX_STRING **/
       $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
       $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());
       /** chọn thời gian - DATE **/
       $this->map['date_from'] = Url::get('date_from')?Url::get('date_from'):date('d/m/Y');
       $this->map['date_to'] = Url::get('date_to')?Url::get('date_to'):date('d/m/Y');
       $this->map['customer_id'] = Url::get('customer_id')?Url::get('customer_id'):'';
       $this->map['type_list'] = array('date'=>Portal::language('date'),'month'=>Portal::language('month'));
       $type = 'date';
       if(Url::get('type'))
       {
            $type = Url::get('type');
       }
       //System::debug($this->map['customer_id']);
       /** khởi tạo option search **/
       /** lễ tân **/
       $cond_resservation = '( room_status.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND room_status.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** dịch vụ **/
       $cond_extra_service = '( extra_service_invoice_detail.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND extra_service_invoice_detail.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** buồng **/
       $cond_housekeeping_invoice = '( housekeeping_invoice.time >='.(Date_Time::to_time($this->map['date_from'])).' AND housekeeping_invoice.time <='.(Date_Time::to_time($this->map['date_to'])+(86399)).' )';
       /** bar **/
       $cond_bar = '( bar_reservation.time_out <='.(Date_Time::to_time($this->map['date_to'])+(86399)).' AND bar_reservation.time_out >='.(Date_Time::to_time($this->map['date_from'])).' ) AND ( bar_reservation.status=\'CHECKIN\' OR bar_reservation.status=\'CHECKOUT\' OR bar_reservation.status=\'BOOKED\' )';
       /** Spa **/
       $cond_spa = '(massage_reservation_room.time<='.(Date_Time::to_time($this->map['date_to'])+(86399)).' AND massage_reservation_room.time>='.(Date_Time::to_time($this->map['date_from'])).')';
       /** đặt tiệc **/
       $cond_party = '(party_reservation.checkin_time<='.(Date_Time::to_time($this->map['date_to'])+(86399)).' AND party_reservation.checkin_time>='.(Date_Time::to_time($this->map['date_from'])).')';
       /** bán hàng **/
       $cond_vend = '( ve_reservation.time >='.(Date_Time::to_time($this->map['date_from'])).' AND ve_reservation.time <='.(Date_Time::to_time($this->map['date_to'])+(86399)).' ) AND ( ve_reservation.status=\'CHECKIN\' OR ve_reservation.status=\'CHECKOUT\' OR ve_reservation.status=\'BOOKED\' )';
       /** bán vé **/
       $cond_ticket = '( ticket_invoice.date_used >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND ticket_invoice.date_used <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** karaoke **/
       $cond_karaoke = '( karaoke_reservation.time >='.(Date_Time::to_time($this->map['date_from'])).' AND karaoke_reservation.time <='.(Date_Time::to_time($this->map['date_to'])+(86399)).' ) AND ( karaoke_reservation.status=\'CHECKIN\' OR karaoke_reservation.status=\'CHECKOUT\' OR karaoke_reservation.status=\'BOOKED\' )';
       $cond_telephone = '( telephone_report_daily.hdate >='.(Date_Time::to_time($this->map['date_from'])).' AND telephone_report_daily.hdate <='.(Date_Time::to_time($this->map['date_to'])+(86399)).' )';
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
             $cond_telephone .= 'AND (reservation.portal_id=\''.$this->map['portal_id'].'\')';
       }
       if($this->map['customer_id']!='')
       {
             $cond = 'AND (reservation.customer_id=\''.$this->map['customer_id'].'\')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.='AND (reservation.customer_id=\''.$this->map['customer_id'].'\' or bar_reservation.customer_id=\''.$this->map['customer_id'].'\')';
             $cond_karaoke.='AND (reservation.customer_id=\''.$this->map['customer_id'].'\' or karaoke_reservation.customer_id=\''.$this->map['customer_id'].'\')';
             $cond_vend .= 'AND (reservation.customer_id=\''.$this->map['customer_id'].'\' or ve_reservation.customer_id=\''.$this->map['customer_id'].'\')';
             $cond_telephone .= 'AND (reservation.customer_id=\''.$this->map['customer_id'].'\')';
       }
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
       $this->map['isset_karaoke'] = 0;
       //System::debug($active_department);                               
       /** lấy dữ liệu bộ phận lễ tân **/
       $reservation = array();
       $li_arr = array();
       $extra_service_room = array();
       $extra_service = array();
       if($this->id_like_array('REC',$active_department))
       {
           /** lay doanh thu phong  **/
           $reservation = $this->get_reservation_revenue($cond_resservation,$type);
           /** lay doanh thu li  **/
           $cond_li = $cond_extra_service.' AND extra_service.code = \'LATE_CHECKIN\'';
           $li_arr = $this->get_extra_service_revenue($cond_li,$type);
           /** lay doanh thu phu troi tien phong  **/
           $cond_es_room = $cond_extra_service.' AND extra_service.code != \'LATE_CHECKIN\' AND extra_service.type = \'ROOM\'';
           $extra_service_room = $this->get_extra_service_revenue($cond_es_room,$type);
           /** lay doanh thu dich vu khac  **/
           $cond_es_service = $cond_extra_service.' AND extra_service.type = \'SERVICE\'';
           $extra_service = $this->get_extra_service_revenue($cond_es_service,$type);
       }
       /** lấy dữ liệu buong **/
       $housekeeping = array();
       if($this->id_like_array('HK',$active_department))
       {
            $housekeeping = $this->get_housekeeping($cond_housekeeping_invoice,$type);
       }
       /** lấy dữ liệu nhà hàng **/
       $bar = array();
       if($this->id_like_array('RES',$active_department))
       {
            $bar = $this->get_bar($cond_bar,$type);
       }
       /** lấy dữ liệu Spa **/
       $spa = array();
       if($this->id_like_array('SPA',$active_department))
       {
            $spa = $this->get_spa($cond_spa,$type); 
       }
       /** lấy dữ liệu đặt tiệc **/
       $party=array();
       if($this->id_like_array('BANQUET',$active_department))
       {
            $party = $this->get_party($cond_party,$type);
       }
       /** lấy dữ liệu bán hàng **/
       $vend=array();
       if($this->id_like_array('VENDING',$active_department))
       {
            $vend = $this->get_vend($cond_vend,$type); 
       }
       /** lấy dữ liệu bán vé **/
       $ticket = array();
       if($this->id_like_array('TICKET',$active_department))
       {
            $ticket = $this->get_ticket($cond_ticket,$type);
       }
       /** lấy dữ liệu karaoke **/
       $karaoke = array();
       if($this->id_like_array('KARAOKE',$active_department))
       {
            $karaoke = $this->get_karaoke($cond_karaoke,$type);
            $this->map['isset_karaoke'] = 1;
       }
       $telephone = array();
       $telephone = $this->get_telephone($cond_telephone,$type);
       $this->map['total_room']=0;
       $this->map['total_service_room']=0;
       $this->map['total_service_other']=0;
       $this->map['total_housekeeping']=0;
       $this->map['total_bar']=0;
       $this->map['total_spa']=0;
       $this->map['total_vending']=0;
       $this->map['total_ticket']=0;
       $this->map['total_karaoke']=0;
       $this->map['total_tax']=0;
       $this->map['total_before_tax']=0;
       $this->map['total_payment']=0;
       $this->map['total_not_payment']=0;
       $this->map['total_total']=0;
       if($type=='date' || $type=='')
       {
            $date_arr = array();
            $start_time = Date_Time::to_time($this->map['date_from']);
            $end_time = Date_Time::to_time($this->map['date_to']);
            for($i=$start_time;$i<=$end_time;$i+=86400)
            {
                $date = date('d/m/Y',$i);
                $date_arr[$date]['id'] = $date;
                $date_arr[$date]['room'] = 0;
                $date_arr[$date]['service_room'] = 0;
                $date_arr[$date]['service_other'] = 0;
                $date_arr[$date]['housekeeping'] = 0;
                $date_arr[$date]['bar'] = 0;
                $date_arr[$date]['spa'] = 0;
                $date_arr[$date]['banquet'] = 0;
                $date_arr[$date]['vending'] = 0;
                $date_arr[$date]['ticket'] = 0;
                $date_arr[$date]['karaoke'] = 0;
                $date_arr[$date]['payment'] = 0;
                $date_arr[$date]['not_payment'] = 0;
                $date_arr[$date]['total'] = 0;
                $date_arr[$date]['total_before_tax'] = 0;
                $date_arr[$date]['tax'] = 0;
                $date_arr[$date]['start_date'] = $date;
                $date_arr[$date]['end_date'] = $date;
                if(isset($reservation[$date]))
                {
                    $date_arr[$date]['room'] = $reservation[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $reservation[$date]['total_payment'];
                    $this->map['total_room'] += $reservation[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $reservation[$date]['total'];
                }  
                if(isset($li_arr[$date]))
                {
                    $date_arr[$date]['room'] += $li_arr[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $li_arr[$date]['total_payment'];
                    $this->map['total_room'] += $li_arr[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $li_arr[$date]['total'];
                }
                if(isset($extra_service_room[$date]))
                {
                    $date_arr[$date]['service_room'] = $extra_service_room[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $extra_service_room[$date]['total_payment'];
                    $this->map['total_service_room'] += $extra_service_room[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $extra_service_room[$date]['total'];
                }  
                if(isset($extra_service[$date]))
                {
                    $date_arr[$date]['service_other'] = $extra_service[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $extra_service[$date]['total_payment'];
                    $this->map['total_service_other'] += $extra_service[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $extra_service[$date]['total'];
                }
                if(isset($telephone[$date]))
                {
                    $date_arr[$date]['service_other'] += $telephone[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $telephone[$date]['total_payment'];
                    $this->map['total_service_other'] += $telephone[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $telephone[$date]['total'];
                }   
                if(isset($housekeeping[$date]))
                {
                    $date_arr[$date]['housekeeping'] = $housekeeping[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $housekeeping[$date]['total_payment'];
                    $this->map['total_housekeeping'] += $housekeeping[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $housekeeping[$date]['total'];
                }
                if(isset($bar[$date]))
                {
                    $date_arr[$date]['bar'] = $bar[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $bar[$date]['total_payment'];
                    $this->map['total_bar'] += $bar[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $bar[$date]['total'];
                }
                if(isset($party[$date]))
                {
                    $date_arr[$date]['bar'] += $party[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $party[$date]['total_payment'];
                    $this->map['total_bar'] += $party[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $party[$date]['total'];
                }
                if(isset($vend[$date]))
                {
                    $date_arr[$date]['vending'] = $vend[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $vend[$date]['total_payment'];
                    $this->map['total_vending'] += $vend[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $vend[$date]['total'];
                }
                if(User::id()=='developer06')
                {
                    //System::debug($date_arr);
                }
                if(isset($spa[$date]))
                {
                    $date_arr[$date]['spa'] = $spa[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $spa[$date]['total_payment'];
                    $this->map['total_spa'] += $spa[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $spa[$date]['total'];
                }
                if(isset($ticket[$date]))
                {
                    $date_arr[$date]['ticket'] = $ticket[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $ticket[$date]['total_payment'];
                    $this->map['total_ticket'] += $ticket[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $ticket[$date]['total'];
                }  
                if(isset($karaoke[$date]))
                {
                    $date_arr[$date]['karaoke'] = $karaoke[$date]['total_before_tax'];
                    $date_arr[$date]['payment'] += $karaoke[$date]['total_payment'];
                    $this->map['total_karaoke'] += $karaoke[$date]['total_before_tax'];
                    $date_arr[$date]['total'] += $karaoke[$date]['total'];
                }
                $date_arr[$date]['total_before_tax'] = $date_arr[$date]['room']+
                                            $date_arr[$date]['service_room']+
                                            $date_arr[$date]['service_other']+
                                            $date_arr[$date]['housekeeping']+
                                            $date_arr[$date]['bar']+
                                            $date_arr[$date]['spa']+
                                            $date_arr[$date]['vending']+
                                            $date_arr[$date]['ticket']+
                                            $date_arr[$date]['karaoke']
                                            ;
                $date_arr[$date]['tax'] = $date_arr[$date]['total'] - $date_arr[$date]['total_before_tax'];
                $date_arr[$date]['not_payment'] = $date_arr[$date]['total']-$date_arr[$date]['payment']; 
                $this->map['total_payment'] += $date_arr[$date]['payment'];
                $this->map['total_not_payment'] += $date_arr[$date]['not_payment'];
                $this->map['total_total'] += $date_arr[$date]['total']; 
                $this->map['total_before_tax'] += $date_arr[$date]['total_before_tax'];
                $this->map['total_tax'] += $date_arr[$date]['tax'];
                
                $date_arr[$date]['room'] = round($date_arr[$date]['room']);
                $date_arr[$date]['service_room'] = round($date_arr[$date]['service_room']);
                $date_arr[$date]['service_other'] = round($date_arr[$date]['service_other']);
                $date_arr[$date]['housekeeping'] = round($date_arr[$date]['housekeeping']);
                $date_arr[$date]['bar'] = round($date_arr[$date]['bar']);
                $date_arr[$date]['spa'] = round($date_arr[$date]['spa']);
                $date_arr[$date]['vending'] = round($date_arr[$date]['vending']);
                $date_arr[$date]['ticket'] = round($date_arr[$date]['ticket']);
                $date_arr[$date]['karaoke'] = round($date_arr[$date]['karaoke']);
                $date_arr[$date]['total_before_tax'] = round($date_arr[$date]['total_before_tax']);
                $date_arr[$date]['tax'] = round($date_arr[$date]['tax']);
                $date_arr[$date]['not_payment'] = round($date_arr[$date]['not_payment']);
            }
            $this->map['total_room'] = round($this->map['total_room']);
            $this->map['total_service_room'] = round($this->map['total_service_room']);
            $this->map['total_service_other'] = round($this->map['total_service_other']);
            $this->map['total_housekeeping'] = round($this->map['total_housekeeping']);
            $this->map['total_bar'] = round($this->map['total_bar']);
            $this->map['total_spa'] = round($this->map['total_spa']);
            $this->map['total_vending'] = round($this->map['total_vending']);
            $this->map['total_ticket'] = round($this->map['total_ticket']);
            $this->map['total_karaoke'] = round($this->map['total_karaoke']);
            $this->map['total_payment'] = round($this->map['total_payment']);
            $this->map['total_not_payment'] = round($this->map['total_not_payment']);
            $this->map['total_total'] = round($this->map['total_total']);
            $this->map['total_before_tax'] = round($this->map['total_before_tax']);
            $this->map['total_tax'] = round($this->map['total_tax']);
            
            $items = $date_arr;
       }
       else
       {
            $s_date = date('1/m/Y',Date_Time::to_time($this->map['date_from']));
            $e_date = date('1/m/Y',Date_Time::to_time($this->map['date_to']));
            $start_time = Date_Time::to_time($s_date);
            $end_time = Date_Time::to_time($e_date);
            $month_arr = array();
            for($i=$start_time;$i<=$end_time;$i+=cal_days_in_month(CAL_GREGORIAN, date('m',$i), date('Y',$i))*86400)
            {
                $month = date('m/Y',$i);
                $month_arr[$month]['id'] = $month;
                $month_arr[$month]['room'] = 0;
                $month_arr[$month]['service_room'] = 0;
                $month_arr[$month]['service_other'] = 0;
                $month_arr[$month]['housekeeping'] = 0;
                $month_arr[$month]['bar'] = 0;
                $month_arr[$month]['spa'] = 0;
                $month_arr[$month]['vending'] = 0;
                $month_arr[$month]['ticket'] = 0;
                $month_arr[$month]['karaoke'] = 0;
                $month_arr[$month]['payment'] = 0;
                $month_arr[$month]['not_payment'] = 0;
                $month_arr[$month]['total'] = 0;
                $month_arr[$month]['total_before_tax'] = 0;
                $month_arr[$month]['tax'] = 0;
                $month_arr[$month]['start_date'] = ($i==$start_time)?$this->map['date_from']:date('1/m/Y',$i);
                $month_arr[$month]['end_date'] = ($i==$end_time)?$this->map['date_to']:cal_days_in_month(CAL_GREGORIAN, date('m',$i), date('Y',$i)).date('/m/Y',$i);
                if(isset($reservation[$month]))
                {
                    $month_arr[$month]['room'] =  $reservation[$month]['total_before_tax'];                    
                    $month_arr[$month]['payment'] += $reservation[$month]['total_payment'];
                    $this->map['total_room'] += $reservation[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $reservation[$month]['total'];
                }  
                if(isset($li_arr[$month]))
                {
                    $month_arr[$month]['room'] += $li_arr[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $li_arr[$month]['total_payment'];
                    $this->map['total_room'] += $li_arr[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $li_arr[$month]['total'];
                }
                if(isset($extra_service_room[$month]))
                {
                    $month_arr[$month]['service_room'] = $extra_service_room[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $extra_service_room[$month]['total_payment'];
                    $this->map['total_service_room'] += $extra_service_room[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $extra_service_room[$month]['total'];
                }  
                if(isset($extra_service[$month]))
                {
                    $month_arr[$month]['service_other'] = $extra_service[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $extra_service[$month]['total_payment'];
                    $this->map['total_service_other'] += $extra_service[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $extra_service[$month]['total'];
                }
                if(isset($telephone[$month]))
                {
                    $month_arr[$month]['service_other'] += $telephone[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $telephone[$month]['total_payment'];
                    $this->map['total_service_other'] += $telephone[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $telephone[$month]['total'];
                }  
                if(isset($housekeeping[$month]))
                {
                    $month_arr[$month]['housekeeping'] = $housekeeping[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $housekeeping[$month]['total_payment'];
                    $this->map['total_housekeeping'] += $housekeeping[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $housekeeping[$month]['total'];
                }
                if(isset($bar[$month]))
                {
                    $month_arr[$month]['bar'] = $bar[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $bar[$month]['total_payment'];
                    $this->map['total_bar'] += $bar[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $bar[$month]['total'];
                } 
                if(isset($spa[$month]))
                {
                    $month_arr[$month]['spa'] = $spa[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $spa[$month]['total_payment'];
                    $this->map['total_spa'] += $spa[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $spa[$month]['total'];
                } 
                if(isset($party[$month]))
                {
                    $month_arr[$month]['bar'] += $party[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $party[$month]['total_payment'];
                    $this->map['total_bar'] += $party[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $party[$month]['total'];
                }
                if(isset($vend[$month]))
                {
                    $month_arr[$month]['vending'] = $vend[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $vend[$month]['total_payment'];
                    $this->map['total_vending'] += $vend[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $vend[$month]['total'];
                }
                if(isset($ticket[$month]))
                {
                    $month_arr[$month]['ticket'] = $ticket[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $ticket[$month]['total_payment'];
                    $this->map['total_ticket'] += $ticket[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $ticket[$month]['total'];
                }  
                if(isset($karaoke[$month]))
                {
                    $month_arr[$month]['karaoke'] = $karaoke[$month]['total_before_tax'];
                    $month_arr[$month]['payment'] += $karaoke[$month]['total_payment'];
                    $this->map['total_karaoke'] += $karaoke[$month]['total_before_tax'];
                    $month_arr[$month]['total'] += $karaoke[$month]['total'];
                }
                $month_arr[$month]['total_before_tax'] = $month_arr[$month]['room']+
                                            $month_arr[$month]['service_room']+
                                            $month_arr[$month]['service_other']+
                                            $month_arr[$month]['housekeeping']+
                                            $month_arr[$month]['bar']+
                                            $month_arr[$month]['spa']+
                                            $month_arr[$month]['vending']+
                                            $month_arr[$month]['ticket']+
                                            $month_arr[$month]['karaoke']
                                            ; 
                $month_arr[$month]['tax'] = $month_arr[$month]['total'] - $month_arr[$month]['total_before_tax'];
                $month_arr[$month]['not_payment'] = $month_arr[$month]['total']-$month_arr[$month]['payment']; 
                $this->map['total_payment'] += $month_arr[$month]['payment'];
                $this->map['total_not_payment'] += $month_arr[$month]['not_payment'];
                $this->map['total_total'] += $month_arr[$month]['total'];
                $this->map['total_before_tax'] += $month_arr[$month]['total_before_tax'];
                $this->map['total_tax'] += $month_arr[$month]['tax'];
                
                $month_arr[$month]['room'] = round($month_arr[$month]['room']);
                $month_arr[$month]['service_room'] = round($month_arr[$month]['service_room']);
                $month_arr[$month]['service_other'] = round($month_arr[$month]['service_other']);
                $month_arr[$month]['housekeeping'] = round($month_arr[$month]['housekeeping']);
                $month_arr[$month]['bar'] = round($month_arr[$month]['bar']);
                $month_arr[$month]['spa'] = round($month_arr[$month]['spa']);
                $month_arr[$month]['vending'] = round($month_arr[$month]['vending']);
                $month_arr[$month]['ticket'] = round($month_arr[$month]['ticket']);
                $month_arr[$month]['karaoke'] = round($month_arr[$month]['karaoke']);
                $month_arr[$month]['total_before_tax'] = round($month_arr[$month]['total_before_tax']);
                $month_arr[$month]['tax'] = round($month_arr[$month]['tax']);
                $month_arr[$month]['not_payment'] = round($month_arr[$month]['not_payment']);
            }
            $this->map['total_room'] = round($this->map['total_room']);
            $this->map['total_service_room'] = round($this->map['total_service_room']);
            $this->map['total_service_other'] = round($this->map['total_service_other']);
            $this->map['total_housekeeping'] = round($this->map['total_housekeeping']);
            $this->map['total_bar'] = round($this->map['total_bar']);
            $this->map['total_spa'] = round($this->map['total_spa']);
            $this->map['total_vending'] = round($this->map['total_vending']);
            $this->map['total_ticket'] = round($this->map['total_ticket']);
            $this->map['total_karaoke'] = round($this->map['total_karaoke']);
            $this->map['total_payment'] = round($this->map['total_payment']);
            $this->map['total_not_payment'] = round($this->map['total_not_payment']);
            $this->map['total_total'] = round($this->map['total_total']);
            $this->map['total_before_tax'] = round($this->map['total_before_tax']);
            $this->map['total_tax'] = round($this->map['total_tax']);
            $items = $month_arr;
       }
       //System::debug($this->map);
       //System::debug($items);
       //exit();
       /** truyền layout **/
       $_REQUEST += $this->map;
       $this->parse_layout('report',array('items'=>$items)+$this->map);
       //**************************************************************************************************\\
	}
    
    /** hàm lấy doanh thu phòng **/
    function get_reservation_revenue($cond,$type)
    {
        $sql = "
                SELECT 
                    room_status.id as id
                    ,room_status.in_date
                    ,room_status.change_price as price
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
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
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room_level on reservation_room.room_level_id=room_level.id
                    inner join customer on reservation.customer_id = customer.id
                    
                WHERE
                    ".$cond." AND reservation_room.status!='CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    room_status.in_date,reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT 
                    'RESERVATION_' || room_status.id || '_' || folio.id  || '_' || mice_invoice.id as id
                    ,room_status.in_date
                    ,reservation_room.arrival_time
                    ,reservation_room.departure_time
                    ,reservation_room.status as reservation_room_status
                    ,NVL(reservation_room.change_room_from_rr,0) as change_from
                    ,NVL(reservation_room.change_room_to_rr,0) as change_to
                    ,reservation_room.foc
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,payment.time as payment_time
                    ,nvl(traveller_folio.total_amount,0) as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room_level on reservation_room.room_level_id=room_level.id
                    inner join customer on reservation.customer_id = customer.id
                    left join traveller_folio on traveller_folio.type='ROOM' AND traveller_folio.invoice_id=room_status.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                    left join mice_invoice_detail on mice_invoice_detail.type='ROOM' AND mice_invoice_detail.invoice_id=room_status.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                    
                WHERE
                    ".$cond." AND reservation_room.status!='CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    room_status.in_date,reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        if(User::id()=='developer06')
        {
            //System::debug($payment_arr);
        }
        $rec_arr=array();
        foreach($report as $key=>$value)
        {
            $price = 0;
            $price_before_tax = 0;
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
                    $price = $value['price'];
                    $price_before_tax = $value['price']/(1+$value['tax_rate']/100);
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
                    $price_before_tax = $value['price']*(1+$value['service_rate']/100);
                    $value['price'] = $value['price']*((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                    $price = $value['price'];
                }
                if($value['foc']!='' OR $value['foc_all']!=0)
                {
                    $price = 0;
                    $price_before_tax = 0;
                }
                if($value['reservation_room_status']=='CANCEL')
                {
                    $price = 0;
                    $price_before_tax = 0;
                }
                $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
                $month = date('m/Y',Date_Time::to_time($date));
                if($type=='date')
                {
                    if(isset($rec_arr[$date]))
                    {
                        $rec_arr[$date]['total'] += $price;
                        $rec_arr[$date]['total_before_tax'] += $price_before_tax;
                    }
                    else
                    {
                        $rec_arr[$date]['id'] = $date;
                        $rec_arr[$date]['total'] = $price;
                        $rec_arr[$date]['total_before_tax'] = $price_before_tax;
                        $rec_arr[$date]['total_payment'] = 0;
                    }
                }
                else
                {
                    if(isset($rec_arr[$month]))
                    {
                        $rec_arr[$month]['total'] += $price;
                        $rec_arr[$month]['total_before_tax'] += $price_before_tax;
                    }
                    else
                    {
                        $rec_arr[$month]['id'] = $month;
                        $rec_arr[$month]['total'] = $price;
                        $rec_arr[$month]['total_before_tax'] = $price_before_tax;
                        $rec_arr[$month]['total_payment'] = 0;
                    }
                }
            }
        }
        //System::debug($payment_arr);
        foreach($payment_arr as $key=>$value)
        {
            if(($value['arrival_time']==$value['departure_time'] AND $value['change_to']!=0) OR ($value['arrival_time']!=$value['departure_time'] AND $value['in_date']==$value['departure_time']))
            {
                
            }
            else
            {
                $total_payment = 0;
                if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                {
                    $total_payment = $value['total_payment'];
                }
                if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                {
                    $total_payment = round($value['total_payment_mice']);
                }
                if($value['foc']!='' OR $value['foc_all']!=0)
                {
                    $total_payment = 0;
                }
                //echo $total_payment;
                $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
                $month = date('m/Y',Date_Time::to_time($date));
                if($type=='date')
                {
                    if(isset($rec_arr[$date]))
                        $rec_arr[$date]['total_payment'] += $total_payment;
                    else
                        $rec_arr[$date]['total_payment'] = $total_payment;
                    
                }
                else
                {
                    if(isset($rec_arr[$month]))
                        $rec_arr[$month]['total_payment'] += $total_payment;
                    else
                        $rec_arr[$month]['total_payment'] = $total_payment;
                }
            }
        }
        //System::debug($rec_arr);
        return $rec_arr;
    }
    /** hàm lấy doanh thu dịch vụ **/
    function get_extra_service_revenue($cond,$type)
    {
        $sql = "
                SELECT
                    extra_service_invoice_detail.id as id
                    ,extra_service_invoice_detail.price
                    ,(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0)) as quantity
                    ,extra_service_invoice_detail.in_date
                    ,extra_service_invoice_detail.percentage_discount
                    ,extra_service_invoice_detail.amount_discount
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,extra_service_invoice.net_price
                    ,extra_service_invoice.tax_rate
                    ,extra_service_invoice.service_rate
                    ,reservation_room.status as reservation_room_status
                    ,customer.name as customer_name
                FROM
                    extra_service_invoice_detail
                    inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond."
                    AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    extra_service.name, extra_service_invoice_detail.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(extra_service_invoice_detail.id,folio.id),mice_invoice.id) as id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,extra_service_invoice_detail.in_date
                    ,payment.time as payment_time
                    ,nvl(traveller_folio.total_amount,0) as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    extra_service_invoice_detail
                    inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join customer on reservation.customer_id = customer.id
                    left join traveller_folio on traveller_folio.type='EXTRA_SERVICE' AND traveller_folio.invoice_id=extra_service_invoice_detail.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                    left join mice_invoice_detail on mice_invoice_detail.type='EXTRA_SERVICE' AND mice_invoice_detail.invoice_id=extra_service_invoice_detail.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    extra_service.name, extra_service_invoice_detail.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $es_arr = array();
        foreach($report as $key=>$value)
        {
            if($value['net_price']==1)
            {
                $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
            }
            $value['price'] -= $value['amount_discount'];
            $value['price']  = $value['price'] - ($value['price']*$value['percentage_discount']/100);
            $price_before_tax = $value['price']*$value['quantity']*(1+$value['service_rate']/100);
            $value['price'] = $value['price']*((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
            $price = $value['price']*$value['quantity'];
            if($value['foc_all']!=0)
            {
                $price = 0;
                $price_before_tax = 0;
            }
            if($value['reservation_room_status']=='CANCEL')
            {
                $price = 0;
                $price_before_tax = 0;
            }
            $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            $month = date('m/Y',Date_Time::to_time($date));
            if($type=='date')
            {
                if(isset($es_arr[$date]))
                {
                    $es_arr[$date]['total'] += $price;
                    $es_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $es_arr[$date]['id'] = $date;
                    $es_arr[$date]['total'] = $price;
                    $es_arr[$date]['total_before_tax'] = $price_before_tax;
                    $es_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($es_arr[$month]))
                {
                    $es_arr[$month]['total'] += $price;
                    $es_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $es_arr[$month]['id'] = $month;
                    $es_arr[$month]['total'] = $price;
                    $es_arr[$month]['total_before_tax'] = $price_before_tax;
                    $es_arr[$month]['total_payment'] = 0;
                    
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            if($value['foc_all']!=0)
            {
                $total_payment = 0;
            }
            $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            $month = date('m/Y',Date_Time::to_time($date));
            if($type=='date')
            {
                if(isset($es_arr[$date]))
                $es_arr[$date]['total_payment'] += $total_payment;
                else
                $es_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($es_arr[$month]))
                $es_arr[$month]['total_payment'] += $total_payment;
                else
                $es_arr[$month]['total_payment'] = $total_payment;
            }
        }
        //System::debug($es_arr);
        return $es_arr;
    }
    /** hàm lấy doanh thu buồng **/
    function get_housekeeping($cond,$type)
    {
        $sql = "
                SELECT
                    housekeeping_invoice.id as id
                    ,housekeeping_invoice.time as in_date
                    ,housekeeping_invoice.total as price
                    ,housekeeping_invoice.tax_rate
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.status as reservation_room_status
                FROM
                    housekeeping_invoice
                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room_level on reservation_room.room_level_id=room_level.id
                    inner join party on party.user_id=housekeeping_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond."
                    AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    housekeeping_invoice.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(housekeeping_invoice.id,folio.id),mice_invoice.id) as id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,housekeeping_invoice.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else housekeeping_invoice.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    housekeeping_invoice
                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room_level on reservation_room.room_level_id=room_level.id
                    inner join party on party.user_id=housekeeping_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                    left join traveller_folio on (traveller_folio.type= 'EQUIPMENT' or traveller_folio.type= 'MINIBAR' or traveller_folio.type= 'LAUNDRY') AND traveller_folio.invoice_id=housekeeping_invoice.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                    left join mice_invoice_detail on (mice_invoice_detail.type= 'EQUIPMENT' or mice_invoice_detail.type= 'MINIBAR' or mice_invoice_detail.type= 'LAUNDRY') AND mice_invoice_detail.invoice_id=housekeeping_invoice.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    housekeeping_invoice.time, reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        //System::debug($sql_payment);
        $housekeeping_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $value['price']/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $price = 0;
                $price_before_tax = 0;
            }
            if($value['reservation_room_status']=='CANCEL')
            {
                $price = 0;
                $price_before_tax = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($housekeeping_arr[$date]))
                {
                    $housekeeping_arr[$date]['total'] += $price;
                    $housekeeping_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $housekeeping_arr[$date]['id'] = $date;
                    $housekeeping_arr[$date]['total'] = $price;
                    $housekeeping_arr[$date]['total_before_tax'] = $price_before_tax;
                    $housekeeping_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($housekeeping_arr[$month]))
                {
                    $housekeeping_arr[$month]['total'] += $price;
                    $housekeeping_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $housekeeping_arr[$month]['id'] = $month;
                    $housekeeping_arr[$month]['total'] = $price;
                    $housekeeping_arr[$month]['total_before_tax'] = $price_before_tax;
                    $housekeeping_arr[$month]['total_payment'] = 0;
                }
            }
            
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            if($value['foc_all']!=0)
            {
                $total_payment = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($housekeeping_arr[$date]))
                $housekeeping_arr[$date]['total_payment'] += $total_payment;
                else
                $housekeeping_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($housekeeping_arr[$month]))
                $housekeeping_arr[$month]['total_payment'] += $total_payment;
                else
                $housekeeping_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $housekeeping_arr;
    }
    /** hàm lấy doanh thu nhà hàng **/
    function get_bar($cond,$type)
    {
        $sql = "
                SELECT
                    bar_reservation.id as id
                    ,bar_reservation.DEPARTURE_TIME as in_date
                    ,bar_reservation.total as price
                    ,bar_reservation.tax_rate
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,cr.name as customer_name_r
                    ,cb.name as customer_name_b
                FROM
                    bar_reservation
                    left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join customer cr on reservation.customer_id = cr.id
                    left join customer cb on bar_reservation.customer_id = cb.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    bar_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        //System::debug($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('BAR_',bar_reservation.id),folio.id),mice_invoice.id) as id
                    ,bar_reservation.DEPARTURE_TIME as in_date
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else bar_reservation.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    bar_reservation
                    left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join customer cr on reservation.customer_id = cr.id
                    left join customer cb on bar_reservation.customer_id = cb.id
                    left join traveller_folio on traveller_folio.type='BAR' AND traveller_folio.invoice_id=bar_reservation.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='BAR' AND payment.bill_id=bar_reservation.id AND payment.type_dps is null) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='BAR' AND mice_invoice_detail.invoice_id=bar_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    bar_reservation.time, reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $bar_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $value['price']/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $price = 0;
                $price_before_tax = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($bar_arr[$date]))
                {
                    $bar_arr[$date]['total'] += $price;
                    $bar_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $bar_arr[$date]['id'] = $date;
                    $bar_arr[$date]['total'] = $price;
                    $bar_arr[$date]['total_before_tax'] = $price_before_tax;
                    $bar_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($bar_arr[$month]))
                {
                    $bar_arr[$month]['total'] += $price;
                    $bar_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $bar_arr[$month]['id'] = $month;
                    $bar_arr[$month]['total'] = $price;
                    $bar_arr[$month]['total_before_tax'] = $price_before_tax;
                    $bar_arr[$month]['total_payment'] = 0;
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            if($value['foc_all']!=0)
            {
                $total_payment = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($bar_arr[$date]))
                    $bar_arr[$date]['total_payment'] += $total_payment;
                else
                    $bar_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($bar_arr[$month]))
                    $bar_arr[$month]['total_payment'] += $total_payment;
                else
                    $bar_arr[$month]['total_payment'] = $total_payment;
            }
        }
        //System::debug($bar_arr);
        return $bar_arr;
    }
    
    /** Hàm lấy doanh thu spa **/
    function get_spa($cond,$type)
    {
        $sql = "
                SELECT
                    concat('SPA_',massage_reservation_room.id) as id
                    ,massage_reservation_room.time as in_date
                    ,massage_reservation_room.tax as tax_rate
                    ,massage_reservation_room.total_amount as price
                    ,NVL(reservation_room.foc_all,0) as foc_all
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    --left join room on reservation_room on room.id = reservation_room.room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('SPA_',massage_reservation_room.id),folio.id),mice_invoice.id) as id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,massage_reservation_room.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else massage_reservation_room.total_amount
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    --left join room on reservation_room on room.id = reservation_room.room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join traveller_folio on traveller_folio.type='MASSAGE' AND traveller_folio.invoice_id=massage_reservation_room.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='SPA' AND payment.bill_id=massage_reservation_room.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='MASSAGE' AND mice_invoice_detail.invoice_id=massage_reservation_room.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $spa_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $price/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $price = 0;
                $price_before_tax = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($spa_arr[$date]))
                {
                    $spa_arr[$date]['total'] += $price;
                    $spa_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $spa_arr[$date]['id'] = $date;
                    $spa_arr[$date]['total'] = $price;
                    $spa_arr[$date]['total_before_tax'] = $price_before_tax;
                    $spa_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($spa_arr[$month]))
                {
                    $spa_arr[$month]['total'] += $price;
                    $spa_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $spa_arr[$month]['id'] = $month;
                    $spa_arr[$month]['total'] = $price;
                    $spa_arr[$month]['total_before_tax'] = $price_before_tax;
                    $spa_arr[$month]['total_payment'] = 0;
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            if($value['foc_all']!=0)
            {
                $total_payment = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($spa_arr[$date]))
                    $spa_arr[$date]['total_payment'] += $total_payment;
                else
                    $spa_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($spa_arr[$month]))
                    $spa_arr[$month]['total_payment'] += $total_payment;
                else
                    $spa_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $spa_arr;      
    }
    /** hàm lấy doanh thu đặt tiệc **/
    function get_party($cond,$type)
    {
        $sql = "
                SELECT
                    concat('PARTY_',party_reservation.id) as id
                    ,party_reservation.checkin_time as in_date
                    ,party_reservation.total as price
                    ,party_reservation.vat as tax_rate
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
        $sql_payment = "
                SELECT
                    concat(concat('PARTY_',party_reservation.id),mice_invoice.id) as id
                    ,party_reservation.checkin_time as in_date
                    ,payment.time as payment_time
                    ,party_reservation.total as total_payment
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    party_reservation
                    inner join party_type on party_type.id=party_reservation.party_type
                    inner join party on party.user_id=party_reservation.user_id
                    left join payment on payment.type='BANQUET' AND payment.bill_id=party_reservation.id
                    left join mice_invoice_detail on mice_invoice_detail.type='BANQUET' AND mice_invoice_detail.invoice_id=party_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND party_reservation.status!='CANCEL'
                ORDER BY
                    party_reservation.time, party_reservation.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $party_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $price/(1+$value['tax_rate']/100);
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($party_arr[$date]))
                {
                    $party_arr[$date]['total'] += $price;
                    $party_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $party_arr[$date]['id'] = $date;
                    $party_arr[$date]['total'] = $price;
                    $party_arr[$date]['total_before_tax'] = $price_before_tax;
                    $party_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($party_arr[$month]))
                {
                    $party_arr[$month]['total'] += $price;
                    $party_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $party_arr[$month]['id'] = $month;
                    $party_arr[$month]['total'] = $price;
                    $party_arr[$month]['total_before_tax'] = $price_before_tax;
                    $party_arr[$month]['total_payment'] = 0;
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='')
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($party_arr[$date]))
                    $party_arr[$date]['total_payment'] += $total_payment;
                else
                    $party_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($party_arr[$month]))
                    $party_arr[$month]['total_payment'] += $total_payment;
                else
                    $party_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $party_arr;
    }
    
    /** hàm lấy doanh thu bán hàng **/
    function get_vend($cond,$type)
    {
        $sql = "
                SELECT
                    concat('VEND_',ve_reservation.id) as id
                    ,ve_reservation.code
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,ve_reservation.time as in_date
                    ,ve_reservation.total as price
                    ,ve_reservation.tax_rate
                FROM
                    ve_reservation
                    inner join party on party.user_id=ve_reservation.user_id
                    LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                    LEFT JOIN reservation ON reservation_room.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                ORDER BY
                    ve_reservation.time, ve_reservation.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('VEND_',ve_reservation.id),folio.id),mice_invoice.id) as id
                    ,ve_reservation.code
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,ve_reservation.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else ve_reservation.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    ve_reservation
                    inner join party on party.user_id=ve_reservation.user_id
                    LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                    LEFT JOIN reservation ON reservation_room.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                    left join traveller_folio on traveller_folio.type='VE' AND traveller_folio.invoice_id=ve_reservation.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='VEND' AND payment.bill_id=ve_reservation.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='VEND' AND mice_invoice_detail.invoice_id=ve_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                ORDER BY
                    ve_reservation.time, ve_reservation.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $ve_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $price/(1+$value['tax_rate']/100);
            if($value['foc_all'])
            {
                $price = 0;
                $price_before_tax = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            
            if($type=='date')
            {
                if(isset($ve_arr[$date]))
                {
                    $ve_arr[$date]['total'] += $price;
                    $ve_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $ve_arr[$date]['id'] = $date;
                    $ve_arr[$date]['total'] = $price;
                    $ve_arr[$date]['total_before_tax'] = $price_before_tax;
                    $ve_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($ve_arr[$month]))
                {
                    $ve_arr[$month]['total'] += $price;
                    $ve_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $ve_arr[$month]['id'] = $month;
                    $ve_arr[$month]['total'] = $price;
                    $ve_arr[$month]['total_before_tax'] = $price_before_tax;
                    $ve_arr[$month]['total_payment'] = 0;
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            if($value['foc_all'])
            {
                $total_payment = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($ve_arr[$date]))
                    $ve_arr[$date]['total_payment'] += $total_payment;
                else
                    $ve_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($ve_arr[$month]))
                    $ve_arr[$month]['total_payment'] += $total_payment;
                else
                    $ve_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $ve_arr;
    }
    
    /** hàm lấy doanh thu bán vé **/
    function get_ticket($cond,$type)
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
        $sql_payment = "
                SELECT
                    concat(concat(concat('TICKET_',ticket_invoice.id),folio.id),mice_invoice.id) as id
                    ,ticket_invoice.total as price
                    ,ticket_invoice.date_used as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else ticket_invoice.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    ticket_invoice
                    inner join ticket on ticket.id=ticket_invoice.ticket_id
                    inner join ticket_group on ticket_group.id=ticket_invoice.ticket_area_id
                    inner join party on party.user_id=ticket_invoice.user_id
                    left join traveller_folio on traveller_folio.type='TICKET' AND traveller_folio.invoice_id=ticket_invoice.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='TICKET' AND payment.bill_id=ticket_invoice.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='TICKET' AND mice_invoice_detail.invoice_id=ticket_invoice.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                ORDER BY
                    ticket_invoice.date_used, ticket_invoice.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $ticket_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            $month = date('m/Y',Date_Time::to_time($date));
            if($type=='date')
            {
                if(isset($ticket_arr[$date]))
                {
                    $ticket_arr[$date]['total'] += $price;
                }
                else
                {
                    $ticket_arr[$date]['id'] = $date;
                    $ticket_arr[$date]['total'] = $price;
                    $ticket_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($ticket_arr[$month]))
                {
                    $ticket_arr[$month]['total'] += $price;
                }
                else
                {
                    $ticket_arr[$month]['id'] = $month;
                    $ticket_arr[$month]['total'] = $price;
                    $ticket_arr[$month]['total_payment'] = 0;
                }
            }
            
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            $date = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
            $month = date('m/Y',Date_Time::to_time($date));
            if($type=='date')
            {
                if(isset($ticket_arr[$date]))
                    $ticket_arr[$date]['total_payment'] += $total_payment;
                else
                    $ticket_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($ticket_arr[$month]))
                    $ticket_arr[$month]['total_payment'] += $total_payment;
                else
                    $ticket_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $ticket_arr;
    }
    /** hàm lấy doanh thu karaoke **/
    function get_karaoke($cond,$type)
    {
        $sql = "
                SELECT
                    concat('KARAOKE_',karaoke_reservation.id) as id
                    ,karaoke_reservation.code
                    ,karaoke_reservation.time as in_date
                    ,karaoke_reservation.total as price
                    ,karaoke_reservation.tax_rate
                    ,karaoke_reservation.id as karaoke_reservation_id
                    ,karaoke_reservation.karaoke_id as karaoke_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                FROM
                    karaoke_reservation
                    left join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    karaoke_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('KARAOKE_',karaoke_reservation.id),folio.id),mice_invoice.id) as id
                    ,karaoke_reservation.code
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,karaoke_reservation.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else karaoke_reservation.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    karaoke_reservation
                    left join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join traveller_folio on traveller_folio.invoice_id=karaoke_reservation.id AND traveller_folio.type='KARAOKE'
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='KARAOKE' AND payment.bill_id=karaoke_reservation.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='KARAOKE' AND mice_invoice_detail.invoice_id=karaoke_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    karaoke_reservation.time, reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $karaoke_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $price/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $price = 0;
                $price_before_tax = 0;
            }
            $total_payment = 0;
            if($value['payment_time']!='')
            {
                $total_payment = $value['total_payment'];
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($karaoke_arr[$date]))
                {
                    $karaoke_arr[$date]['total_before_tax'] += $price_before_tax;
                    $karaoke_arr[$date]['total'] += $price;
                }
                else
                {
                    $karaoke_arr[$date]['id'] = $date;
                    $karaoke_arr[$date]['total'] = $price;
                    $karaoke_arr[$date]['total_before_tax'] = $price_before_tax;
                    $karaoke_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($karaoke_arr[$month]))
                {
                    $karaoke_arr[$month]['total'] += $price;
                    $karaoke_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $karaoke_arr[$month]['id'] = $month;
                    $karaoke_arr[$month]['total'] = $price;
                    $karaoke_arr[$month]['total_before_tax'] += $price_before_tax;
                    $karaoke_arr[$month]['total_payment'] = 0;
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
            {
                $total_payment = round($value['total_payment_mice']);
            }
            if($value['foc_all']!=0)
            {
                $total_payment = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($karaoke_arr[$date]))
                    $karaoke_arr[$date]['total_payment'] += $total_payment;
                else
                    $karaoke_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($karaoke_arr[$month]))
                    $karaoke_arr[$month]['total_payment'] += $total_payment;
                else
                    $karaoke_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $karaoke_arr;
    }
    /** hàm lấy doanh thu karaoke **/
    function get_telephone($cond,$type)
    {
        $sql = "
                SELECT
                    telephone_report_daily.id as id
                    ,telephone_report_daily.hdate as in_date
                    ,telephone_report_daily.price_vnd as price
                    ,telephone_report_daily.total_before_tax as price_before_tax
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                FROM
                    telephone_report_daily
                    left join reservation_room on reservation_room.id = telephone_report_daily.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                WHERE
                    ".$cond."
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(telephone_report_daily.id,folio.id) as id
                    ,telephone_report_daily.hdate as in_date
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else telephone_report_daily.price_vnd
                    end as total_payment
                    ,folio.total as total_folio
                FROM
                    telephone_report_daily
                    left join reservation_room on reservation_room.id = telephone_report_daily.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join traveller_folio on traveller_folio.invoice_id=telephone_report_daily.id AND traveller_folio.type='TELEPHONE'
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='TELEPHONE' AND payment.bill_id=telephone_report_daily.id) OR (payment.folio_id=folio.id)
                WHERE
                    ".$cond."
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        $telephone_arr = array();
        foreach($report as $key=>$value)
        {
            $price = $value['price'];
            $price_before_tax = $value['price_before_tax'];
            if($value['foc_all']!=0)
            {
                $price = 0;
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($telephone_arr[$date]))
                {
                    $telephone_arr[$date]['total'] += $price;
                    $telephone_arr[$date]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $telephone_arr[$date]['id'] = $date;
                    $telephone_arr[$date]['total'] = $price;
                    $telephone_arr[$date]['total_before_tax'] = $price_before_tax;
                    $telephone_arr[$date]['total_payment'] = 0;
                }
            }
            else
            {
                if(isset($telephone_arr[$month]))
                {
                    $telephone_arr[$month]['total'] += $price;
                    $telephone_arr[$month]['total_before_tax'] += $price_before_tax;
                }
                else
                {
                    $telephone_arr[$month]['id'] = $month;
                    $telephone_arr[$month]['total'] = $price;
                    $telephone_arr[$month]['total_before_tax'] = $price_before_tax;
                    $telephone_arr[$month]['total_payment'] = 0;
                }
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            $total_payment = 0;
            if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
            {
                $total_payment = $value['total_payment'];
            }
            $date = date('d/m/Y',$value['in_date']);
            $month = date('m/Y',$value['in_date']);
            if($type=='date')
            {
                if(isset($telephone_arr[$date]))
                    $telephone_arr[$date]['total_payment'] += $total_payment;
                else
                    $telephone_arr[$date]['total_payment'] = $total_payment;
            }
            else
            {
                if(isset($telephone_arr[$month]))
                    $telephone_arr[$month]['total_payment'] += $total_payment;
                else
                    $telephone_arr[$month]['total_payment'] = $total_payment;
            }
        }
        return $telephone_arr;
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
