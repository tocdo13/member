<?php
class RevenueSituationReportForm extends Form
{
	function RevenueSituationReportForm()
	{
		Form::Form('RevenueSituationReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   
        $this->map = array();
        
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        $month = date('m',Date_Time::to_time($this->map['date']));
        $year = date('Y',Date_Time::to_time($this->map['date']));
        $this->map['year'] = $year;
        $this->map['last_year'] = $year-1;
        $date = Date_Time::to_orc_date($this->map['date']);
        $begin_month = Date_Time::to_orc_date('1/'.$month.'/'.$year);
        $begin_month_last_year = Date_Time::to_orc_date('1/'.$month.'/'.($year-1));
        $date_last_year = Date_Time::to_orc_date(date('d',Date_Time::to_time($this->map['date'])).'/'.$month.'/'.($year-1));
        
        //So phong ks
        $max_room = DB::fetch('Select count(room.id) as num from room_level inner join room on room_level.id = room.room_level_id Where room.portal_id = \''.PORTAL_ID.'\' and room_level.is_virtual = 0  ','num');
        
        //echo $max_room;
        
        $cond =' 1=1 ';
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' ';
            $last_year_cond.= ' AND reservation.portal_id = \''.$portal_id.'\' ';
            $in_date_cond.= ' AND reservation.portal_id = \''.$portal_id.'\' ';
        } 
        
		$cond .= ' AND (reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.$date.'\' AND reservation_room.departure_time >=\''.$begin_month.'\'  ) ';
        
        $last_year_cond .= ' AND (reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.$date_last_year.'\' AND reservation_room.departure_time >=\''.$begin_month_last_year.'\'  ) ';
        
        $in_date_cond .= ' AND (reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.$date.'\' AND reservation_room.departure_time >=\''.$date.'\'  ) ';
        //System::debug($cond);
        //System::debug($last_year_cond);
        
        //Tong so phong kinh doanh 1 ngay
        $max_room = DB::fetch('Select count(room.id) as num from room_level inner join room on room_level.id = room.room_level_id Where room.portal_id = \''.PORTAL_ID.'\' and room_level.is_virtual = 0 ','num');
        //Tong so phong kinh doanh trong 1 thang
        $total_room = $max_room * Date_Time::day_of_month($month,$year);
        //Tong so phong kinh doanh trong 1 thang nam truoc
        $total_room_last_year = $max_room * Date_Time::day_of_month($month,($year-1));
        
        //echo $max_room;

        //echo $total_room;
        
        //System::debug($data);
        
        $guest_situation = array(
                                    'GUEST_IN_DATE'=>0,
                                    'GUEST_IN_MONTH'=>0,
                                    'GUEST_LAST_YEAR'=>0,
                                );
        /**
         * Dem so luot khach, trong ngay, trong thang , cung ki nam ngoai
         */
        
		$items = $this->get_data_guest($cond);
        //System::debug(strtotime($date));
        //Duyet qua tung khach
        foreach($items as $k=>$v)
        {
            if($v['fullname']!= ' ')
            {
                $guest_situation['GUEST_IN_MONTH']++;
                //Neu ngay out cua khach nam trong ngay
                if($v['time_out']>= Date_Time::to_time($this->map['date']) && $v['time_out']<= (Date_Time::to_time($this->map['date'])+86400) )
                {
                    $guest_situation['GUEST_IN_DATE']++;
                }
            }

                
        }
        //echo Date_Time::to_time($this->map['date']);
        $last_year_items = $this->get_data_guest($last_year_cond);
        
        
        foreach($last_year_items as $k=>$v)
        {
            if($v['fullname']!= ' ')
            {
                $guest_situation['GUEST_LAST_YEAR']++;
            }      
        }
        
        //System::debug($guest_situation);
        
        $room_situation = array(
                            'ROOM_IN_DATE'=>0,//so phong ban
                            'ROOM_IN_MONTH'=>0,
                            'ROOM_LAST_YEAR'=>0,
                            
                            'ROOM_REVENUE_IN_DATE'=>0,//dooanh thu phong
                            'ROOM_REVENUE_IN_MONTH'=>0,
                            'ROOM_REVENUE_LAST_YEAR'=>0,
                            
                            'PHONE_REVENUE_IN_DATE'=>0,//dooanh thu dien thoai
                            'PHONE_REVENUE_IN_MONTH'=>0,
                            'PHONE_REVENUE_LAST_YEAR'=>0,
                            
                            'AVERAGE_ROOM_PRICE_IN_DATE'=>0,//gia phong binh quan
                            'AVERAGE_ROOM_PRICE_IN_MONTH'=>0,
                            'AVERAGE_ROOM_PRICE_LAST_YEAR'=>0,
                            
                            'OCCUPANCY_IN_DATE'=>0,//cong suat phong]
                            'OCCUPANCY_IN_MONTH'=>0,
                            'OCCUPANCY_LAST_YEAR'=>0,
                            
                            'REVPAR_IN_DATE'=>0,//cong suat phong]
                            'REVPAR_IN_MONTH'=>0,
                            'REVPAR_LAST_YEAR'=>0,
                            
                            'TRANSPORT_REVENUE_IN_DATE'=>0,//phi van chuyen
                            'TRANSPORT_REVENUE_IN_MONTH'=>0,
                            'TRANSPORT_REVENUE_LAST_YEAR'=>0,
                            
                            'MINIBAR_REVENUE_IN_DATE'=>0,//minibar
                            'MINIBAR_REVENUE_IN_MONTH'=>0,
                            'MINIBAR_REVENUE_LAST_YEAR'=>0,
                            
                            'LAUNDRY_REVENUE_IN_DATE'=>0,//giat la
                            'LAUNDRY_REVENUE_IN_MONTH'=>0,
                            'LAUNDRY_REVENUE_LAST_YEAR'=>0,
                            
                            'OTHER_REVENUE_IN_DATE'=>0,//khac
                            'OTHER_REVENUE_IN_MONTH'=>0,
                            'OTHER_REVENUE_LAST_YEAR'=>0,
                        );
        
        /**
         * Tinh doanh thu phong trong ngay, trong thang , cung ki nam ngoai
         */
        
        $items = $this->get_room_data($cond);
        //System::debug($items);
        
        foreach($items as $key=>$value)
        {
            //tru di 1 ngay de ra so night, neu 2 truong nay bang nhau thi van tinh la 1 ngay
            if($value['arrival_time']!=$value['departure_time'])
                $items[$key]['night']=$items[$key]['night']-1;
            
            $items[$key]['total_after'] = $this->calc_money($value['net_price'],$value['total'],$value['reduce_balance'],$value['reduce_amount'],$value['service_rate'],$value['tax_rate']);
            
            $room_situation['ROOM_IN_MONTH'] += $items[$key]['night'];
            $room_situation['ROOM_REVENUE_IN_MONTH'] += $items[$key]['total_after'];
            
            //$telephone_revenue
            $telephone_revenue = DB::fetch('
                                            Select 
                                                SUM(telephone_report_daily.price) as total
                                            FROM
                                                telephone_report_daily
                                            WHERE
                                                phone_number_id = \''.$value['phone_number'].'\'
                                                and hdate >= '.$value['time_in'].'
                                                and hdate <= '.$value['time_out'].'
                                            ','total');
            $room_situation['PHONE_REVENUE_IN_MONTH'] += $telephone_revenue; 
                                                                       
            if($value['time_out']>= Date_Time::to_time($this->map['date']) && $value['time_out']<= (Date_Time::to_time($this->map['date'])+86400) )
            {
                $room_situation['ROOM_IN_DATE'] += $items[$key]['night'];
                $room_situation['ROOM_REVENUE_IN_DATE'] += $items[$key]['total_after'];
                $room_situation['PHONE_REVENUE_IN_DATE'] += $telephone_revenue;  
            }
        }
        
        
        
        $last_year_items = $this->get_room_data($last_year_cond);
        
        foreach($last_year_items as $key=>$value)
        {
            //tru di 1 ngay de ra so night, neu 2 truong nay bang nhau thi van tinh la 1 ngay
            if($value['arrival_time']!=$value['departure_time'])
                $items[$key]['night']=$items[$key]['night']-1;
            
            $items[$key]['total_after'] = $this->calc_money($value['net_price'],$value['total'],$value['reduce_balance'],$value['reduce_amount'],$value['service_rate'],$value['tax_rate']);
            
            $room_situation['ROOM_LAST_YEAR'] += $items[$key]['night'];
            $room_situation['ROOM_REVENUE_LAST_YEAR'] += $items[$key]['total_after'];
            
            //$telephone_revenue
            $telephone_revenue = DB::fetch('
                                            Select 
                                                SUM(telephone_report_daily.price) as total
                                            FROM
                                                telephone_report_daily
                                            WHERE
                                                phone_number_id = \''.$value['phone_number'].'\'
                                                and hdate >= '.$value['time_in'].'
                                                and hdate <= '.$value['time_out'].'
                                            ','total');
            $room_situation['PHONE_REVENUE_LAST_YEAR'] += $telephone_revenue; 
        }
        
        
        $room_situation['AVERAGE_ROOM_PRICE_IN_DATE'] = $room_situation['ROOM_REVENUE_IN_DATE']/($room_situation['ROOM_IN_DATE']?$room_situation['ROOM_IN_DATE']:1);
        $room_situation['AVERAGE_ROOM_PRICE_IN_MONTH'] = $room_situation['ROOM_REVENUE_IN_MONTH']/($room_situation['ROOM_IN_MONTH']?$room_situation['ROOM_IN_MONTH']:1);
        $room_situation['AVERAGE_ROOM_PRICE_LAST_YEAR'] = $room_situation['ROOM_REVENUE_LAST_YEAR']/($room_situation['ROOM_LAST_YEAR']?$room_situation['ROOM_LAST_YEAR']:1);
        
        $room_situation['OCCUPANCY_IN_DATE'] = $room_situation['ROOM_IN_DATE']*100/$max_room;
        $room_situation['OCCUPANCY_IN_MONTH'] = $room_situation['ROOM_IN_MONTH']*100/$total_room;
        $room_situation['OCCUPANCY_LAST_YEAR'] = $room_situation['ROOM_LAST_YEAR']*100/$total_room_last_year;
        
        $room_situation['REVPAR_IN_DATE'] = $room_situation['OCCUPANCY_IN_DATE']*$room_situation['AVERAGE_ROOM_PRICE_IN_DATE']/100;
        $room_situation['REVPAR_IN_MONTH'] = $room_situation['OCCUPANCY_IN_MONTH']*$room_situation['AVERAGE_ROOM_PRICE_IN_MONTH']/100;
        $room_situation['REVPAR_LAST_YEAR'] = $room_situation['OCCUPANCY_LAST_YEAR']*$room_situation['AVERAGE_ROOM_PRICE_LAST_YEAR']/100;
        
        
        
        /**
         * Tinh phi van chuyen
         */
         
        $room_situation['TRANSPORT_REVENUE_IN_DATE'] = ($this->count_pickup($in_date_cond)+$this->count_see_off($in_date_cond))* PICKUP_PRICE;
        $room_situation['TRANSPORT_REVENUE_IN_MONTH'] = ($this->count_pickup($cond)+$this->count_see_off($cond))* PICKUP_PRICE;
        $room_situation['TRANSPORT_REVENUE_LAST_YEAR'] = ($this->count_pickup($last_year_cond)+$this->count_see_off($last_year_cond))* PICKUP_PRICE;
        
        /**
         * Tinh minibar
         */
        $hk_cond = ' AND housekeeping_invoice.type = \'MINIBAR\' ';
        
        $room_situation['MINIBAR_REVENUE_IN_DATE'] = $this->get_hk_revenue($in_date_cond,$hk_cond);
        $room_situation['MINIBAR_REVENUE_IN_MONTH'] = $this->get_hk_revenue($cond,$hk_cond);
        $room_situation['MINIBAR_REVENUE_LAST_YEAR'] = $this->get_hk_revenue($last_year_cond,$hk_cond);
        
        
        /**
         * Tinh LAUNDRY
         */
        $hk_cond = ' AND housekeeping_invoice.type = \'LAUNDRY\' ';
        
        $room_situation['LAUNDRY_REVENUE_IN_DATE'] = $this->get_hk_revenue($in_date_cond,$hk_cond);
        $room_situation['LAUNDRY_REVENUE_IN_MONTH'] = $this->get_hk_revenue($cond,$hk_cond);
        $room_situation['LAUNDRY_REVENUE_LAST_YEAR'] = $this->get_hk_revenue($last_year_cond,$hk_cond);
        
        /**
         * Tinh other
         */
        $hk_cond = ' AND housekeeping_invoice.type != \'LAUNDRY\' AND housekeeping_invoice.type != \'MINIBAR\' ';
        
        $room_situation['OTHER_REVENUE_IN_DATE'] = $this->get_hk_revenue($in_date_cond,$hk_cond);
        $room_situation['OTHER_REVENUE_IN_MONTH'] = $this->get_hk_revenue($cond,$hk_cond);
        $room_situation['OTHER_REVENUE_LAST_YEAR'] = $this->get_hk_revenue($last_year_cond,$hk_cond);
        
        $room_situation['OTHER_REVENUE_IN_DATE'] += $this->get_extra_service_revenue($in_date_cond);
        $room_situation['OTHER_REVENUE_IN_MONTH'] += $this->get_extra_service_revenue($cond);
        $room_situation['OTHER_REVENUE_LAST_YEAR'] += $this->get_extra_service_revenue($last_year_cond);
        //echo $in_date_cond;
        
        //System::debug($room_situation);
        //tong doah thu phong, nha hang, tat ca
        $summary = array(
                        'TOTAL_BAR_REVENUE_IN_DATE'=>0,
                        'TOTAL_BAR_REVENUE_IN_MONTH'=>0,
                        'TOTAL_BAR_REVENUE_LAST_YEAR'=>0,
                        'TOTAL_SPA_REVENUE_IN_DATE'=>0,
                        'TOTAL_SPA_REVENUE_IN_MONTH'=>0,
                        'TOTAL_SPA_REVENUE_LAST_YEAR'=>0,
                        'TOTAL_TICKET_REVENUE_IN_DATE'=>0,
                        'TOTAL_TICKET_REVENUE_IN_MONTH'=>0,
                        'TOTAL_TICKET_REVENUE_LAST_YEAR'=>0,
                        'TOTAL_VEND_REVENUE_IN_DATE'=>0,
                        'TOTAL_VEND_REVENUE_IN_MONTH'=>0,
                        'TOTAL_VEND_REVENUE_LAST_YEAR'=>0,
                        'TOTAL_VEND_REVENUE_IN_DATE_DRINK'=>0,
                        'TOTAL_VEND_REVENUE_IN_MONTH_DRINK'=>0,
                        'TOTAL_VEND_REVENUE_LAST_YEAR_DRINK'=>0,
                        'TOTAL_ROOM_REVENUE_IN_DATE'=>0,
                        'TOTAL_ROOM_REVENUE_IN_MONTH'=>0,
                        'TOTAL_ROOM_REVENUE_LAST_YEAR'=>0,
                        'TOTAL_REVENUE_IN_DATE'=>0,
                        'TOTAL_REVENUE_IN_MONTH'=>0,
                        'TOTAL_REVENUE_LAST_YEAR'=>0,
                        );
        //tinh doanh thu ban ve
        $ticket_cond = ' ticket_invoice.portal_id  = \''.PORTAL_ID.'\' 
                     AND (ticket_invoice.time <'.(strtotime($date) + 24*3600 - 1).' 
                     AND ticket_invoice.time >='.(strtotime($begin_month)).' )';
        $last_year_ticket_cond = ' ticket_invoice.portal_id  = \''.PORTAL_ID.'\' 
                     AND (ticket_invoice.time <'.(strtotime($date_last_year) + 24*3600 - 1).' 
                     AND ticket_invoice.time >='.(strtotime($begin_month_last_year)).' )';
        $ticket_items = $this->get_ticket_data($ticket_cond);
        $last_year_ticket_items = $this->get_ticket_data($last_year_ticket_cond);
        if(!empty($ticket_items))
        foreach($ticket_items as $key => $value)
        {
            $summary['TOTAL_TICKET_REVENUE_IN_MONTH'] += $value['total'];
            if(date('d/m/y',$value['time']) == date('d/m/y',strtotime($date)))
            {
                $summary['TOTAL_TICKET_REVENUE_IN_DATE'] += $value['total'];
            }
        }
        if(!empty($last_year_ticket_items))
        foreach($last_year_ticket_items as $key => $value)
        {
            $summary['TOTAL_TICKET_REVENUE_LAST_YEAR'] += $value['total'];
        }
        //tinh doanh thu vending
        $vending_cond = ' ve_reservation.portal_id  = \''.PORTAL_ID.'\' 
                     AND (ve_reservation.arrival_time <'.(strtotime($date) + 24*3600 - 1).' 
                     AND ve_reservation.arrival_time >='.(strtotime($begin_month)).' )';
        $last_year_vending_cond = ' ve_reservation.portal_id = \''.PORTAL_ID.'\' 
                                AND (ve_reservation.arrival_time <'.(strtotime($date_last_year) + 24*3600 - 1).'
                                AND ve_reservation.arrival_time >='.(strtotime($begin_month_last_year)).' )';
        $vending_items = $this->get_vending_reservation_data($vending_cond);
        $last_year_vending_items = $this->get_vending_reservation_data($last_year_vending_cond);
        
        //System::debug($vending_items);
        //System::debug($last_year_vending_items);
        if(!empty($vending_items))
        foreach($vending_items as $key => $value)
        {
            if($value['type'] == 'DRINK')
            {
                $total_before_dis = ($value['quantity'] - $value['quantity_discount'])*$value['price'];
                $cat_discount = $total_before_dis*$value['discount_category']/100;
                $product_discount = ($total_before_dis - $cat_discount)*$value['product_discount']/100 + $cat_discount;
                $total_discount = ($total_before_dis - $product_discount)*$value['discount']/100 + $product_discount;
                $total_fee = ($total_before_dis - $total_discount)*$value['bar_fee_rate']/100;
                $total_tax = ($total_before_dis - $total_discount + $total_fee)*$value['tax_rate']/100;
                $summary['TOTAL_VEND_REVENUE_IN_MONTH_DRINK'] += $total_before_dis + $total_fee + $total_tax - $total_discount;
            }
            else
            {
                $total_before_dis = ($value['quantity'] - $value['quantity_discount'])*$value['price'];
                $cat_discount = $total_before_dis*$value['discount_category']/100;
                $product_discount = ($total_before_dis - $cat_discount)*$value['product_discount']/100 + $cat_discount;
                $total_discount = ($total_before_dis - $product_discount)*$value['discount']/100 + $product_discount;
                $total_fee = ($total_before_dis - $total_discount)*$value['bar_fee_rate']/100;
                $total_tax = ($total_before_dis - $total_discount + $total_fee)*$value['tax_rate']/100;
                $summary['TOTAL_VEND_REVENUE_IN_MONTH'] += $total_before_dis + $total_fee + $total_tax - $total_discount;
            }
            if(date('d/m/y',$value['arrival_time'])== date('d/m/y',strtotime($date)))  
            {
                if($value['type'] == 'DRINK')
                {
                    $total_before_dis = ($value['quantity'] - $value['quantity_discount'])*$value['price'];
                    $cat_discount = $total_before_dis*$value['discount_category']/100;
                    $product_discount = ($total_before_dis - $cat_discount)*$value['product_discount']/100 + $cat_discount;
                    $total_discount = ($total_before_dis - $product_discount)*$value['discount']/100 + $product_discount;
                    $total_fee = ($total_before_dis - $total_discount)*$value['bar_fee_rate']/100;
                    $total_tax = ($total_before_dis - $total_discount + $total_fee)*$value['tax_rate']/100;
                    $summary['TOTAL_VEND_REVENUE_IN_DATE_DRINK'] += $total_before_dis + $total_fee + $total_tax - $total_discount;
                }
                else
                {
                    $total_before_dis = ($value['quantity'] - $value['quantity_discount'])*$value['price'];
                    $cat_discount = $total_before_dis*$value['discount_category']/100;
                    $product_discount = ($total_before_dis - $cat_discount)*$value['product_discount']/100 + $cat_discount;
                    $total_discount = ($total_before_dis - $product_discount)*$value['discount']/100 + $product_discount;
                    $total_fee = ($total_before_dis - $total_discount)*$value['bar_fee_rate']/100;
                    $total_tax = ($total_before_dis - $total_discount + $total_fee)*$value['tax_rate']/100;
                    $summary['TOTAL_VEND_REVENUE_IN_DATE'] += $total_before_dis + $total_fee + $total_tax - $total_discount;
                }
            }
        }
        if(!empty($last_year_vending_items))
        foreach($last_year_vending_items as $key => $value)
        {
            if($value['type'] == 'DRINK')
            {
                $total_before_dis = ($value['quantity'] - $value['quantity_discount'])*$value['price'];
                $cat_discount = $total_before_dis*$value['discount_category']/100;
                $product_discount = ($total_before_dis - $cat_discount)*$value['product_discount']/100 + $cat_discount;
                $total_discount = ($total_before_dis - $product_discount)*$value['discount']/100 + $product_discount;
                $total_fee = ($total_before_dis - $total_discount)*$value['bar_fee_rate']/100;
                $total_tax = ($total_before_dis - $total_discount + $total_fee)*$value['tax_rate']/100;
                $summary['TOTAL_VEND_REVENUE_LAST_YEAR_DRINK'] += $total_before_dis + $total_fee + $total_tax - $total_discount;
            }
            else
            {
                $total_before_dis = ($value['quantity'] - $value['quantity_discount'])*$value['price'];
                $cat_discount = $total_before_dis*$value['discount_category']/100;
                $product_discount = ($total_before_dis - $cat_discount)*$value['product_discount']/100 + $cat_discount;
                $total_discount = ($total_before_dis - $product_discount)*$value['discount']/100 + $product_discount;
                $total_fee = ($total_before_dis - $total_discount)*$value['bar_fee_rate']/100;
                $total_tax = ($total_before_dis - $total_discount + $total_fee)*$value['tax_rate']/100;
                $summary['TOTAL_VEND_REVENUE_LAST_YEAR'] += $total_before_dis + $total_fee + $total_tax - $total_discount;
            }
        }
        //Tinh tong doanh thu spa
        $spa_cond = ' MASSAGE_RESERVATION_ROOM.portal_id  = \''.PORTAL_ID.'\' 
                     AND (MASSAGE_PRODUCT_CONSUMED.status = \'CHECKOUT\'
                     AND MASSAGE_PRODUCT_CONSUMED.time_in <'.(strtotime($date) + 24*3600 - 1).' 
                     AND MASSAGE_PRODUCT_CONSUMED.time_out >='.(strtotime($begin_month)).' )';
        $last_year_spa_cond = ' MASSAGE_RESERVATION_ROOM.portal_id = \''.PORTAL_ID.'\' 
                                AND ( MASSAGE_PRODUCT_CONSUMED.status = \'CHECKOUT\' 
                                AND MASSAGE_PRODUCT_CONSUMED.time_in <\''.(strtotime($date_last_year) + 24*3600 - 1).'\' 
                                AND MASSAGE_PRODUCT_CONSUMED.time_out >=\''.(strtotime($begin_month_last_year)).'\' )';
        
        $spa_items = $this->get_spa_reservation_data($spa_cond);
        $last_year_spa_items = $this->get_spa_reservation_data($last_year_spa_cond);
        
        if(!empty($spa_items))
        foreach($spa_items as $key => $value)
        {
            $summary['TOTAL_SPA_REVENUE_IN_MONTH'] += round($value['total_amount'] - $value['discount'] + $value['tip_amount'] - $value['total_amount']*$value['tax']/100);
            if(date('d/m/y',$value['time_out']) == date('d/m/y',strtotime($date)))
            {
                
                $summary['TOTAL_SPA_REVENUE_IN_DATE'] += round($value['total_amount'] - $value['discount'] + $value['tip_amount'] - $value['total_amount']*$value['tax']/100);
            }
        }
        if(!empty($last_year_spa_items))
        foreach($last_year_spa_items as $key => $value)
        {
            $summary['TOTAL_ROOM_REVENUE_LAST_YEAR'] += round($value['total_amount'] - $value['discount'] + $value['tip_amount'] - $value['total_amount']*$value['tax']/100);
        }
      //  if(User::is_admin())
//        {
//            
//            System::debug($spa_items);
//            echo '------------------';
//            System::debug($last_year_spa_items);   
//        }
        /**
         * Tinh doanh thu nha hang
         */
         
        $bar = DB::fetch_all('Select bar.id, bar.code, bar.name from bar where portal_id = \''.PORTAL_ID.'\' ');
        foreach($bar as $key => $value)
        {
            //Tong doanh thu cua tung nha hang
            $bar[$key]['TOTAL_IN_DATE'] = 0;
            $bar[$key]['TOTAL_IN_MONTH'] = 0;
            $bar[$key]['TOTAL_LAST_YEAR'] = 0;
            
            $bar[$key]['DRINK'] = array('BAR_REVENUE_IN_DATE'=>0,
                                        'BAR_REVENUE_IN_MONTH'=>0,
                                        'BAR_REVENUE_LAST_YEAR'=>0,);
            $bar[$key]['FOOD'] = array('BAR_REVENUE_IN_DATE'=>0,//dooanh thu phong
                                        'BAR_REVENUE_IN_MONTH'=>0,
                                        'BAR_REVENUE_LAST_YEAR'=>0,);
        }
        //System::debug($bar);
        $bar_cond = ' bar_reservation.portal_id = \''.PORTAL_ID.'\' AND ( bar_reservation.status = \'CHECKOUT\' AND FROM_UNIXTIME(bar_reservation.departure_time) <=\''.$date.'\' AND FROM_UNIXTIME(bar_reservation.departure_time) >=\''.$begin_month.'\'  ) ';
        $last_year_bar_cond = ' bar_reservation.portal_id = \''.PORTAL_ID.'\' AND ( bar_reservation.status = \'CHECKOUT\' AND FROM_UNIXTIME(bar_reservation.departure_time) <=\''.$date_last_year.'\' AND FROM_UNIXTIME(bar_reservation.departure_time) >=\''.$begin_month_last_year.'\'  ) ';

        $items_bar = $this->get_bar_reservation_data($bar_cond);
        
        
        foreach($items_bar as $k=>$v)
        {
            $v['total_FOOD'] = $v['total_FOOD'] * (1-$v['discount_percent']/100);
            $v['total_FOOD'] = $v['total_FOOD'] - $v['discount'];
            $v['total_FOOD'] = $v['total_FOOD'] * (1+$v['bar_fee_rate']/100);
            $v['total_FOOD'] = $v['total_FOOD'] * (1+$v['tax_rate']/100);
            
            $v['total_DRINK'] = $v['total_DRINK'] * (1-$v['discount_percent']/100);
            $v['total_DRINK'] = $v['total_DRINK'] - $v['discount'];
            $v['total_DRINK'] = $v['total_DRINK'] * (1+$v['bar_fee_rate']/100);
            $v['total_DRINK'] = $v['total_DRINK'] * (1+$v['tax_rate']/100);
            
            foreach($bar as $key => $value)
            {
                if($v['bar_id']==$key)
                {
                    $bar[$key]['TOTAL_IN_MONTH'] += ($v['total_FOOD']+$v['total_DRINK']);
                    
                    $bar[$key]['FOOD']['BAR_REVENUE_IN_MONTH'] += $v['total_FOOD'];
                    $bar[$key]['DRINK']['BAR_REVENUE_IN_MONTH'] += $v['total_DRINK'];
                    
                    $summary['TOTAL_BAR_REVENUE_IN_MONTH'] += $bar[$key]['TOTAL_IN_MONTH']; 
                    if(strtotime($v['departure_time']) == strtotime($date) )
                    {
                        $bar[$key]['TOTAL_IN_DATE'] += ($v['total_FOOD']+$v['total_DRINK']);
                        
                        $bar[$key]['FOOD']['BAR_REVENUE_IN_DATE'] += $v['total_FOOD'];
                        $bar[$key]['DRINK']['BAR_REVENUE_IN_DATE'] += $v['total_DRINK'];
                        
                        $summary['TOTAL_BAR_REVENUE_IN_DATE'] += $bar[$key]['TOTAL_IN_DATE']; 
                    }
                    break;
                }
                
            }
        }
        
        $items_bar_last_year = $this->get_bar_reservation_data($last_year_bar_cond);
        
        foreach($items_bar_last_year as $k=>$v)
        {
            $v['total_FOOD'] = $v['total_FOOD'] * (1-$v['discount_percent']/100);
            $v['total_FOOD'] = $v['total_FOOD'] - $v['discount'];
            $v['total_FOOD'] = $v['total_FOOD'] * (1+$v['bar_fee_rate']/100);
            $v['total_FOOD'] = $v['total_FOOD'] * (1+$v['tax_rate']/100);
            
            $v['total_DRINK'] = $v['total_DRINK'] * (1-$v['discount_percent']/100);
            $v['total_DRINK'] = $v['total_DRINK'] - $v['discount'];
            $v['total_DRINK'] = $v['total_DRINK'] * (1+$v['bar_fee_rate']/100);
            $v['total_DRINK'] = $v['total_DRINK'] * (1+$v['tax_rate']/100);
            
            foreach($bar as $key => $value)
            {
                if($v['bar_id']==$key)
                {
                    $bar[$key]['TOTAL_LAST_YEAR'] += ($v['total_FOOD']+$v['total_DRINK']);
                    
                    $bar[$key]['FOOD']['BAR_REVENUE_LAST_YEAR'] += $v['total_FOOD'];
                    $bar[$key]['DRINK']['BAR_REVENUE_LAST_YEAR'] += $v['total_DRINK'];
                    
                    $summary['TOTAL_BAR_REVENUE_LAST_YEAR'] += $bar[$key]['TOTAL_LAST_YEAR'];
                }
                
            }
        }
        
        //$summary = array();
        $summary['TOTAL_ROOM_REVENUE_IN_DATE'] += $room_situation['ROOM_REVENUE_IN_DATE']+$room_situation['PHONE_REVENUE_IN_DATE']+$room_situation['MINIBAR_REVENUE_IN_DATE']+$room_situation['LAUNDRY_REVENUE_IN_DATE']+$room_situation['TRANSPORT_REVENUE_IN_DATE']+$room_situation['OTHER_REVENUE_IN_DATE'];
        $summary['TOTAL_ROOM_REVENUE_IN_MONTH'] += $room_situation['ROOM_REVENUE_IN_MONTH']+$room_situation['PHONE_REVENUE_IN_MONTH']+$room_situation['MINIBAR_REVENUE_IN_MONTH']+$room_situation['LAUNDRY_REVENUE_IN_MONTH']+$room_situation['TRANSPORT_REVENUE_IN_MONTH']+$room_situation['OTHER_REVENUE_IN_MONTH'];
        $summary['TOTAL_ROOM_REVENUE_LAST_YEAR'] += $room_situation['ROOM_REVENUE_LAST_YEAR']+$room_situation['PHONE_REVENUE_LAST_YEAR']+$room_situation['MINIBAR_REVENUE_LAST_YEAR']+$room_situation['LAUNDRY_REVENUE_LAST_YEAR']+$room_situation['TRANSPORT_REVENUE_LAST_YEAR']+$room_situation['OTHER_REVENUE_LAST_YEAR'];
        
        
        $summary['TOTAL_REVENUE_IN_DATE'] += $summary['TOTAL_ROOM_REVENUE_IN_DATE']+$summary['TOTAL_BAR_REVENUE_IN_DATE'];
        $summary['TOTAL_REVENUE_IN_MONTH'] += $summary['TOTAL_ROOM_REVENUE_IN_MONTH']+$summary['TOTAL_BAR_REVENUE_IN_MONTH'];
        $summary['TOTAL_REVENUE_LAST_YEAR'] += $summary['TOTAL_ROOM_REVENUE_LAST_YEAR']+$summary['TOTAL_BAR_REVENUE_LAST_YEAR'];
        //System::debug($summary);
        //System::debug($bar);
        
        $this->parse_layout('report',$this->map+array(
                                                       'bar'=>$bar,
                                                       'room_situation'=>$room_situation,
                                                       'guest_situation'=>$guest_situation,
                                                       'summary'=>$summary
                                                    )
                            );
        
	}
    
    
    function get_data_guest($cond)
    {
        /**
         * Dem so luot khach
         */
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id desc) as id,
                    reservation.id as reservation_id,
                    reservation_room.id as reservation_room_id,
                    reservation_traveller.id as reservation_traveller_id,
                    traveller.id as traveller_id,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    reservation_room.departure_time,
                    reservation_room.time_out
                from 
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                where 
                    '.$cond.' 
                order by 
                    reservation_room.id DESC';
        //echo $sql;
		$items = DB::fetch_all($sql);
        //System::debug($items);
        return $items;
    }
    
    
    //join ca phone number de tinh tien dien thoai
    function get_room_data($cond)
    {
		$sql='
				SELECT
                    reservation_room.id,
                    reservation_room.departure_time,
                    reservation_room.arrival_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    SUM(room_status.change_price) + ( COALESCE(reservation_room.verify_dayuse,0)/10 * reservation_room.price   ) as total,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                    COALESCE(reservation_room.reduce_amount,0) as reduce_amount,
                    COUNT(room_status.id) + COALESCE(reservation_room.verify_dayuse,0)/10 as night,
                    telephone_number.phone_number
				FROM
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left  join room_status on room_status.reservation_room_id = reservation_room.id
                    inner join room on room.id = reservation_room.room_id
                    left  join telephone_number on telephone_number.room_id = room.id
				WHERE
					'.$cond.'
				GROUP BY
                    reservation_room.id,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    reservation_room.reduce_balance,
                    reservation_room.reduce_amount,
                    reservation_room.verify_dayuse,
                    reservation_room.price,
                    telephone_number.phone_number
				ORDER BY
					reservation_room.id DESC
					
		';
        //System::debug($sql);
        $items = DB::fetch_all($sql);
        return $items;
    }
    
            //tinh tien sau thue, charge
    function calc_money($net_price,$total,$reduce_balance,$reduce_amount,$service_rate,$tax_rate)
    {
        $total_after = 0;
        //gia phong chua bao gom tax and charge
        if($net_price==0)
        {
            $total_after = $total * (1-$reduce_balance/100);
            $total_after = $total_after - $reduce_amount;
            $total_after = $total_after * (1+$service_rate/100);
            $total_after = $total_after * (1+$tax_rate/100);
        }
        else//da bao gom tax va charge => chia nguoc lai, tru tiep giam gia, roi nhan lai tax va rate
        {
            $total_after = $total / (1+$tax_rate/100);
            $total_after = $total_after / (1+$service_rate/100);
            $total_after = $total_after * (1-$reduce_balance/100);
            $total_after = $total_after - $reduce_amount;
            $total_after = $total_after * (1+$service_rate/100);
            $total_after = $total_after * (1+$tax_rate/100);
        }
        return $total_after;
    }
    
    
    function count_pickup($cond)
    {
        $num_pickup = DB::fetch(
                        'select
                                COUNT(reservation_traveller.id) as num_pickup
                            from 
                                reservation_room 
                                inner join reservation on reservation.id = reservation_room.reservation_id
                                inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                            where
                                '.$cond.' 
                                and reservation_traveller.status != \'CHANGE\'
                                and reservation_traveller.pickup = 1
                                and reservation_traveller.pickup_foc = 1
                        ','num_pickup');
        return $num_pickup;
        
    }
    
    function count_see_off($cond)
    {
        $num_see_off = DB::fetch(
                                'select
                                        COUNT(reservation_traveller.id) as num_see_off
                                    from 
                                        reservation_room 
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                        inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                    where
                                        '.$cond.' 
                                        and reservation_traveller.status != \'CHANGE\'
                                        and reservation_traveller.see_off = 1
                                        and reservation_traveller.see_off_foc = 1
                                ','num_see_off');
        return $num_see_off;
        
    }
    
    function get_hk_revenue($cond, $hk_cond)
    {
        $housekeeping_revenue = DB::fetch('
                                        Select 
                                            SUM(housekeeping_invoice.total) as total
                                        FROM
                                            housekeeping_invoice
                                            inner join reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
                                            inner join reservation on reservation.id = reservation_room.reservation_id
                                        WHERE
                                            '.$cond.$hk_cond.'
                                        ','total');
        return $housekeeping_revenue;
    }
    
    function get_extra_service_revenue($cond)
    {
        $extra_service_revenue = DB::fetch('
                                            Select 
                                                SUM(extra_service_invoice.total_amount) as total
                                            FROM
                                                extra_service_invoice
                                                inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                                inner join reservation on reservation.id = reservation_room.reservation_id
                                            WHERE
                                                '.$cond.'
                                            ','total'); 
    }
    
    //Du lieu nha hang, da tinh toan thue, charge
    function get_bar_reservation_data($cond)
    {
        $sql = '
                Select
                    bar_reservation_product.id,
                    bar_reservation_product.product_id,
                    bar_reservation_product.price,
                    bar_reservation_product.discount_rate,
                    (bar_reservation_product.quantity - bar_reservation_product.quantity_discount) as quantity,
                    bar_reservation_product.discount_category,
                    bar_reservation.id as bar_reservation_id,
                    bar_reservation.bar_id,
                    bar_reservation.discount,
                    bar_reservation.tax_rate,
                    bar_reservation.bar_fee_rate,
                    bar_reservation.discount_percent,
                    bar_reservation.full_rate,
                    bar_reservation.full_charge,
                    product.type,
                    FROM_UNIXTIME(bar_reservation.departure_time) as departure_time
                    
                From
                    bar_reservation
                    inner join bar_reservation_product on bar_reservation.id = bar_reservation_product.bar_reservation_id
                    inner join product on bar_reservation_product.product_id = product.id
                Where
                    '.$cond.'
                    AND ( product.type = \'PRODUCT\' OR product.type = \'GOODS\' OR product.type = \'DRINK\' )
                Order by 
                    bar_reservation.id
                ';
        //System::debug($sql);exit();
        $items = DB::fetch_all($sql);
        //System::debug($items);
        
        $bar_reservation = array();
        $bar_res_id = false;
        //tinh toan tren tung san pham
        foreach($items as $k => $v)
        {
            if($v['full_rate']==1)
            {
                $items[$k]['price'] = $items[$k]['price'] / (1+$v['tax_rate']/100);
                $items[$k]['price'] = $items[$k]['price'] / (1+$v['bar_fee_rate']/100);
                $items[$k]['price'] = $items[$k]['price'] * (1-$v['discount_category']/100);
                $items[$k]['price'] = $items[$k]['price'] * (1-$v['discount_rate']/100);
            }
            else
                if($v['full_charge']==1)
                {
                    $items[$k]['price'] = $items[$k]['price'] / (1+$v['bar_fee_rate']/100);
                    $items[$k]['price'] = $items[$k]['price'] * (1-$v['discount_category']/100);
                    $items[$k]['price'] = $items[$k]['price'] * (1-$v['discount_rate']/100);
                }
                
            $items[$k]['total_FOOD'] = 0;
            $items[$k]['total_DRINK'] = 0;
            if($v['type']=='DRINK')
            {
                $items[$k]['total_DRINK'] = $items[$k]['price'] * $v['quantity'];    
            }
            else
            {
                $items[$k]['total_FOOD'] = $items[$k]['price'] * $v['quantity']; 
            }
                
            if($v['bar_reservation_id'] != $bar_res_id)
            {
                $bar_reservation[$v['bar_reservation_id']] = array(
                                                                    'id'=>$v['bar_reservation_id'],
                                                                    'bar_id'=>$v['bar_id'],
                                                                    'total_FOOD'=>$items[$k]['total_FOOD'],
                                                                    'total_DRINK'=>$items[$k]['total_DRINK'],
                                                                    'discount_percent'=>$v['discount_percent'],
                                                                    'discount'=>$v['discount'],
                                                                    'tax_rate'=>$v['tax_rate'],
                                                                    'bar_fee_rate'=>$v['bar_fee_rate'],
                                                                    'departure_time'=>$v['departure_time'],
                                                                                                                                        
                                                                );
                $bar_res_id = $v['bar_reservation_id'];
            }
            else
            {
                $bar_reservation[$v['bar_reservation_id']]['total_FOOD'] += $items[$k]['total_FOOD'];
                $bar_reservation[$v['bar_reservation_id']]['total_DRINK'] += $items[$k]['total_DRINK'];
            }  
        }
        //System::debug($items);
        return $bar_reservation;
        
    }
    //Du lieu spa, da tinh toan thue, charge
    function get_spa_reservation_data($cond)
    {
        $sql = '
                select 
                    massage_reservation_room.id,
                    massage_reservation_room.tax,
                    massage_reservation_room.tip_amount,
                    massage_reservation_room.total_amount,
                    massage_reservation_room.discount,
                    MASSAGE_PRODUCT_CONSUMED.time_out
                from
                    MASSAGE_PRODUCT_CONSUMED 
                    inner join massage_reservation_room
                    on MASSAGE_PRODUCT_CONSUMED.reservation_room_id = massage_reservation_room.id
                where
                    '.$cond;
        //System::debug($sql);                 
        $items = DB::fetch_all($sql);
        return $items;
    }
    //du lieu vending
    function get_vending_reservation_data($cond)
    {
        $sql = '
                select 
                    ve_reservation_product.id,
                    ve_reservation_product.price,
                    ve_reservation_product.quantity,
                    ve_reservation_product.QUANTITY_DISCOUNT,
                    ve_reservation_product.DISCOUNT_RATE,
                    ve_reservation_product.discount as product_discount,
                    ve_reservation_product.DISCOUNT_CATEGORY,
                    product.type,
                    ve_reservation.id as ve_reservation_id,
                    ve_reservation.tax_rate,
                    ve_reservation.discount,
                    ve_reservation.bar_fee_rate,
                    ve_reservation.arrival_time
                from
                    ve_reservation_product
                    inner join ve_reservation on ve_reservation_product.bar_reservation_id = ve_reservation.id
                    inner join product on ve_reservation_product.product_id = product.id
                where'.$cond;
        //System::debug($sql);
        $items = DB::fetch_all($sql);
        return $items;
    }
    function get_ticket_data($cond)
    {
        $sql = '
                select * from ticket_invoice
                where' . $cond;
        $items = DB::fetch_all($sql);
        return $items;
    }
}
?>