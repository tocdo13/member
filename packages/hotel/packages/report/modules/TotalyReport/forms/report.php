<?php
//////////////////BỰC MÌNH THẬT. THAY ĐỔI XOÀNH XOẠCH>MAY MÀ MÌNH COMMENT LẠI KHÔNG GIỜ KHÔNG HIỂU TẠI SAO LẠI VIẾT THẾ NÀY >KAKA
class TotalyReportForm extends Form
{
	function TotalyReportForm()
	{
		Form::Form('TotalyReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
	function draw()
	{
        
	   $this->map = array();
       $from_date = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):Date_Time::to_orc_date(date('d/m/Y'));
       $to_date = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):Date_Time::to_orc_date(date('d/m/Y'));
       
       
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $this->map['to_date']= Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       
       $f_month = date('n',Date_time::to_time( $this->map['from_date']));
      
       $f_year = date('Y',Date_time::to_time( $this->map['from_date']));       
       $t_month = date('n',Date_time::to_time( $this->map['to_date']));

       $t_year = date('Y',Date_time::to_time( $this->map['to_date']));
       if($f_year == $t_year)
       {
            $distance = $t_month - $f_month + 1;
       }
       else
       {
            $distance = 12 - $f_month +(12*($t_year-$f_year-1))+ 1 + $t_month;
       }
       //chuyển ngày tháng năm thành năm tháng ngày

       $f1 = date('Y-m-d',Date_time::to_time( $this->map['from_date']));
       $t1 = date('Y-m-d',Date_time::to_time( $this->map['to_date']));
       
       $pre_from_date = Date_time::convert_time_to_ora_date(strtotime ( '-'.$distance.' month' , strtotime($f1)));
       $pre_to_date = Date_time::convert_time_to_ora_date(strtotime ( '-'.$distance.' month' , strtotime($t1)));
       
       $last_f_date = strtotime ( '-1 year' , strtotime($this->map['from_date']));
       $last_t_date = strtotime ( '-1 year' , strtotime($this->map['to_date'] ));
       
       $last_from_date = Date_time::convert_time_to_ora_date($last_f_date);
       $last_to_date = Date_time::convert_time_to_ora_date($last_t_date);
       
       $_REQUEST['from_date'] = $this->map['from_date'];
	   $_REQUEST['to_date'] = $this->map['to_date'];
       ////////////////////
       ////////
       ///////////////////
       $cond = ' 1=1';
       $cond .= ' AND reservation_traveller.arrival_date <= \''.$to_date.'\' AND reservation_traveller.departure_date>=\''.$from_date.'\'';
       
       $pre_cond = '1 = 1';
       $pre_cond .= ' AND reservation_traveller.arrival_date <= \''.$pre_to_date.'\' AND reservation_traveller.departure_date>=\''.$pre_from_date.'\'';
       
       $last_cond= ' 1=1';
       $last_cond.=' AND reservation_traveller.arrival_date <= \''.$last_to_date.'\' AND reservation_traveller.departure_date>=\''.$last_from_date.'\'';
       ////////////////////
       ////////
       /////////////////// 
       $s_date = Url::get('from_date')?Date_Time::to_time(Url::get('from_date')):Date_Time::to_time(date('d/m/Y'));
       $e_date = Url::get('to_date')?(Date_Time::to_time(Url::get('to_date'))+86400):(Date_Time::to_time(date('d/m/Y'))+86400);
       $songay = ($e_date - $s_date)/24/60/60;
       
       $f1 = date('Y-m-d',Date_time::to_time( $this->map['from_date']));
       $t1 = date('Y-m-d',Date_time::to_time( $this->map['to_date'])+86400);
       
       $pre_s_date = strtotime ( '-'.$distance.' month' , strtotime($f1));
       $pre_e_date = strtotime ( '-'.$distance.' month' , strtotime($t1));
         
       $last_s_date = strtotime ( '-1 year' , strtotime($s_date));
       $last_e_date = strtotime ( '-1 year' , strtotime($e_date));
       
       //////////////////////////////////
       //////
       ////////////////////////////////// 
       $cond1=' 1=1';
       $cond1 .='AND (reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') AND reservation_room.arrival_time<=\''.$to_date.'\' AND reservation_room.departure_time>=\''.$from_date.'\'';
       
       $pre_cond1 = '1=1';
       $pre_cond1.=' AND (reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') AND reservation_room.arrival_time<=\''.$pre_to_date.'\' AND reservation_room.departure_time>=\''.$pre_from_date.'\'';
       
       $last_cond1 = ' 1=1';
       $last_cond1 .= 'AND (reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') AND reservation_room.arrival_time<=\''.$last_to_date.'\' AND reservation_room.departure_time>=\''.$last_from_date.'\'';
       ////////////////////////////////
       ///
       ///////////////////////////////
       $cond2=' 1=1';
       $cond2.='AND ((reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') AND room_status.in_date<=\''.$to_date.'\' AND room_status.in_date>=\''.$from_date.'\')';
       
       $pre_cond2 = ' 1=1';
       $pre_cond2 .= 'AND ((reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') AND room_status.in_date<=\''.$pre_to_date.'\' AND room_status.in_date>=\''.$pre_from_date.'\') ';
       
       $last_cond2 = ' 1=1';
       $last_cond2 .= 'AND ((reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') AND room_status.in_date<=\''.$last_to_date.'\' AND room_status.in_date>=\''.$last_from_date.'\')';
       /////////////////////////////////
       //
       ////////////////////////////////
       $cond3=' 1=1';
       $cond3.='AND extra_service_invoice.time<=\''.$e_date.'\' AND extra_service_invoice.time>=\''.$s_date.'\'';
       
       $pre_cond3 = ' 1=1';
       $pre_cond3 .= 'AND extra_service_invoice.time<=\''.$pre_e_date.'\' AND extra_service_invoice.time>=\''.$pre_s_date.'\'';
       
       $last_cond3 = ' 1=1';
       $last_cond3 .= 'AND extra_service_invoice.time<=\''.$last_e_date.'\' AND extra_service_invoice.time>=\''.$last_s_date.'\'';
       /////////////////////////////////
       //
       ////////////////////////////////
       $cond4=' 1=1';
       $cond4.='AND housekeeping_invoice.time<=\''.$e_date.'\' AND housekeeping_invoice.time>=\''.$s_date.'\'';
       
       $pre_cond4 = ' 1=1';
       $pre_cond4 .= 'AND housekeeping_invoice.time<=\''.$pre_e_date.'\' AND housekeeping_invoice.time>=\''.$pre_s_date.'\'';
       
       $last_cond4 = ' 1=1';
       $last_cond4 .= 'AND housekeeping_invoice.time<=\''.$last_e_date.'\' AND housekeeping_invoice.time>=\''.$last_s_date.'\'';
       /////////////////////////////////
       //
       ////////////////////////////////
       $cond5=' 1=1';
       $cond5.='AND (bar_reservation.status = \'CHECKIN\' OR bar_reservation.status = \'CHECKOUT\')AND bar_reservation.arrival_time<=\''.$e_date.'\' AND bar_reservation.departure_time>=\''.$s_date.'\'';
       
       $pre_cond5 = ' 1=1';
       $pre_cond5 .= 'AND (bar_reservation.status = \'CHECKIN\' OR bar_reservation.status = \'CHECKOUT\')AND bar_reservation.arrival_time<=\''.$pre_e_date.'\' AND bar_reservation.departure_time>=\''.$pre_s_date.'\'';
       
       $last_cond5 = ' 1=1';
       $last_cond5 .= 'AND (bar_reservation.status = \'CHECKIN\' OR bar_reservation.status = \'CHECKOUT\')AND bar_reservation.arrival_time<=\''.$last_e_date.'\' AND bar_reservation.departure_time>=\''.$last_s_date.'\'';
       /////////////////////////////////
       //
       ////////////////////////////////
       $cond6 =' 1=1';
       $cond6.='AND (massage_product_consumed.status = \'CHECKIN\' OR massage_product_consumed.status = \'CHECKOUT\')AND massage_product_consumed.time_in<=\''.$e_date.'\' AND massage_product_consumed.time_out>=\''.$s_date.'\'';
       
       $pre_cond6 =' 1=1';
       $pre_cond6 .='AND (massage_product_consumed.status = \'CHECKIN\' OR massage_product_consumed.status = \'CHECKOUT\')AND massage_product_consumed.time_in<=\''.$pre_e_date.'\' AND massage_product_consumed.time_out>=\''.$pre_s_date.'\'';
       
       $last_cond6 =' 1=1';
       $last_cond6 .='AND (massage_product_consumed.status = \'CHECKIN\' OR massage_product_consumed.status = \'CHECKOUT\')AND massage_product_consumed.time_in<=\''.$last_e_date.'\' AND massage_product_consumed.time_out>=\''.$last_s_date.'\'';
       /////////////////////////////////
       //
       ////////////////////////////////
       $cond7 =' 1=1';
       $cond7.=' AND ticket_invoice.time <=\''.$e_date.'\' AND ticket_invoice.time >=\''.$s_date.'\' AND (ticket.code = \'ENF\' OR ticket.code = \'ENFK\' OR ticket.code = \'ENK\' OR ticket.code = \'EN\')';
       
       $pre_cond7 ='1=1';
       $pre_cond7.= ' AND ticket_invoice.time <=\''.$pre_e_date.'\' AND ticket_invoice.time >=\''.$pre_s_date.'\' AND (ticket.code = \'ENF\' OR ticket.code = \'ENFK\' OR ticket.code = \'ENK\' OR ticket.code = \'EN\')';
       
       $last_cond7 = '1=1';
       $last_cond7.= ' AND ticket_invoice.time <=\''.$last_e_date.'\' AND ticket_invoice.time >=\''.$last_s_date.'\' AND (ticket.code = \'ENF\' OR ticket.code = \'ENFK\' OR ticket.code = \'ENK\' OR ticket.code = \'EN\')';
       /////////////////////////////////////////////
       //
       ////////////////////////////////////////////
       $cond8 ='1=1';
       $cond8 .=' AND ticket_reservation.time <=\''.$e_date.'\' AND ticket_reservation.time >=\''.$s_date.'\'';
       
       $highwire_cond ='  UPPER(ticket_service.name_2) LIKE \'HIGHWIRE%\'';
       $zipline_cond ='  UPPER(ticket_service.name_2) LIKE \'ZIPLINE%\'';
       
       $pre_cond8 ='1=1';
       $pre_cond8 .=' AND ticket_reservation.time <=\''.$pre_e_date.'\' AND ticket_reservation.time >=\''.$pre_s_date.'\'';
       
       $last_cond8 ='1=1';
       $last_cond8 .=' AND ticket_reservation.time <=\''.$last_e_date.'\' AND ticket_reservation.time >=\''.$last_s_date.'\'';
       ///////////////////////////////////////////////
       //
       /////////////////////////////////////////////
       $cond9=' 1=1';
       $cond9.='AND (ve_reservation.status = \'CHECKIN\' OR ve_reservation.status = \'CHECKOUT\')AND ve_reservation.arrival_time<=\''.$e_date.'\' AND ve_reservation.arrival_time>=\''.$s_date.'\'';
       
       $pre_cond9 = ' 1=1';
       $pre_cond9 .= 'AND (ve_reservation.status = \'CHECKIN\' OR ve_reservation.status = \'CHECKOUT\')AND ve_reservation.arrival_time<=\''.$pre_e_date.'\' AND ve_reservation.arrival_time>=\''.$pre_s_date.'\'';
       
       $last_cond9 = ' 1=1';
       $last_cond9 .= 'AND (ve_reservation.status = \'CHECKIN\' OR ve_reservation.status = \'CHECKOUT\')AND ve_reservation.arrival_time<=\''.$last_e_date.'\' AND ve_reservation.arrival_time>=\''.$last_s_date.'\'';
       //////////////////////////////////////////////
       //condition plan 
       /////////////////////////////////////////////
       $f_m = date('m',Date_time::to_time( $this->map['from_date']));
       $t_m = date('m',Date_time::to_time( $this->map['to_date']));
       $cond_plan = ' 1=1';
       if($f_year == $t_year)
       {
            $cond_plan .= ' AND plan_in_month.year = \''.$f_year.'\' AND plan_in_month.year = \''.$t_year.'\' AND plan_in_month.month <= \''.$t_m.'\' AND plan_in_month.month >= \''.$f_m.'\'';
       }
       else
       {
            $cond_plan .= ' AND ((plan_in_month.year = \''.$f_year.'\' AND plan_in_month.month <= \'12\' AND plan_in_month.month >= \''.$f_m.'\') OR (plan_in_month.year = \''.$t_year.'\' AND plan_in_month.month >= \'01\' AND plan_in_month.month <= \''.$t_m.'\'))';
       }
       ////////////////////////////////////////////
       //
       //////////////////////////////////////////
       $this->map['total_traveller_welness'] = 0;
       $this->map['total_traveller_welness_euro'] = 0;
       $this->map['total_traveller_welness_asia'] = 0;
       $this->map['total_traveller_welness_other'] = 0;
       $this->map['total_traveller_welness_hue'] = 0; 
       $this->map['total_traveller_welness_hnhcm'] = 0;
       $this->map['total_traveller_welness_khac'] = 0;
       $this->map['total_traveller_fitness'] = 0;
       $this->map['total_traveller_fitness_euro'] = 0;
       $this->map['total_traveller_fitness_asia']  = 0;
       $this->map['total_traveller_fitness_other'] = 0;
       $this->map['total_traveller_fitness_hue'] = 0;
       $this->map['total_traveller_fitness_hnhcm'] = 0;
       $this->map['total_traveller_fitness_khac'] = 0;
       $this->map['total_traveller_queen1'] = 0;
       $this->map['total_traveller_queen1_euro'] = 0;
       $this->map['total_traveller_queen1_asia'] = 0;
       $this->map['total_traveller_queen1_other'] = 0;
       $this->map['total_traveller_queen1_hue'] = 0;
       $this->map['total_traveller_queen1_hnhcm'] = 0;
       $this->map['total_traveller_queen1_khac'] = 0;
       $this->map['total_traveller_queen2'] = 0;
       $this->map['total_traveller_queen2_euro'] = 0;
       $this->map['total_traveller_queen2_asia'] = 0;
       $this->map['total_traveller_queen2_other'] = 0;
       $this->map['total_traveller_queen2_hue'] = 0;
       $this->map['total_traveller_queen2_hnhcm'] = 0;
       $this->map['total_traveller_queen2_khac'] = 0;
       $this->map['total_room_welness'] = 0;
       $this->map['total_room_fitness'] = 0;
       $this->map['total_room_queen1'] = 0;
       $this->map['total_room_queen2'] = 0;
       $this->map['total_room_repair_welness'] = 0;
       $this->map['total_room_repair_fitness'] = 0;
       $this->map['total_room_repair_queen1'] = 0;
       $this->map['total_room_repair_queen2'] = 0;
       $this->map['total_room_traveller_welness'] = 0;
       $this->map['total_room_traveller_fitness'] = 0;
       $this->map['total_room_traveller_queen1'] = 0;
       $this->map['total_room_traveller_queen2'] = 0;
       $this->map['percent_welness'] = 0; 
       $this->map['percent_fitness'] = 0;
       $this->map['percent_queen1'] = 0;
       $this->map['percent_queen2'] = 0; 
       ///////////////////////////////////////////////
       // ĐẾM SỐ KHÁCH
       ///////////////////////////////////////////////
       
        $items = TotalyReportDB::count_traveller($cond);
        //default
        // welness
        $welness = 0;
        $euro_welness = 0;//âu
        $asia_welness = 0;//á
        $other_welness = 0;
        
        $hue_welness = 0;
        $hn_welness = 0;
        $khac_welness = 0; 
        
        //fitness
        $fitness = 0;
        $euro_fitness = 0;//âu
        $asia_fitness = 0;//á
        $other_fitness = 0;

        $hue_fitness = 0;
        $hn_fitness = 0;
        $khac_fitness = 0;
        
        // hue queen 1
        $queen1 =0;
        $euro_queen1 = 0;
        $asia_queen1 = 0;
        $other_queen1=0;
        
        $hue_queen1 = 0;
        $hn_queen1 = 0;
        $khac_queen1 = 0;
        
        //hue queen 2
        $queen2 =0;
        $euro_queen2 = 0;
        $asia_queen2 = 0;
        $other_queen2=0;
        
        $hue_queen2 = 0;
        $hn_queen2 = 0;
        $khac_queen2 = 0;
        
        foreach($items as $key => $value)
        {
            if($value['portal_id'] =='#default')
            {
                if($value['area_id'] ==2)// welness 
                {
                    $welness++;
                    $this->map['total_traveller_welness'] = $welness;
                    
                    if($value['continents_id'] ==1)// châu âu
                    {
                        $euro_welness++;
                        $this->map['total_traveller_welness_euro'] = $euro_welness;
                    }
                    if($value['continents_id'] ==2)// châu á
                    {
                        $asia_welness++;
                        $this->map['total_traveller_welness_asia'] = $asia_welness;
                    }
                    if($value['continents_id'] ==3)// khác
                    {
                        $other_welness++;
                        $this->map['total_traveller_welness_other'] = $other_welness;
                    }
                    if($value['province_id'] !='')
                    {
                        
                        if($value['province_code'] =='HUE')
                        {
                            $hue_welness++;
                            $this->map['total_traveller_welness_hue'] = $hue_welness;
                        }
                        if($value['province_code'] =='HN' || $value['province_code']=='HCM')
                        {
                            $hn_welness++;
                            $this->map['total_traveller_welness_hnhcm'] = $hn_welness;
                        }
                        if($value['province_code'] =='TTK')
                        {
                            $khac_welness++;
                            $this->map['total_traveller_welness_khac'] = $khac_welness;
                        }
                    }
                }
                if($value['area_id'] ==1)// fitness
                {
                    $fitness++;
                    $this->map['total_traveller_fitness'] = $fitness;
                    if($value['continents_id'] ==1)// châu âu
                    {
                        $euro_fitness++;
                        $this->map['total_traveller_fitness_euro'] = $euro_fitness;
                    }
                    if($value['continents_id'] ==2)// châu á
                    {
                        $asia_fitness++;
                        $this->map['total_traveller_fitness_asia'] = $asia_fitness;
                    }
                    if($value['continents_id'] ==3)// khác
                    {
                        $other_fitness++;
                        $this->map['total_traveller_fitness_other'] = $other_fitness;
                    }
                    if($value['province_id'] !='')
                    {
                        
                        if($value['province_code'] =='HUE')
                        {
                            $hue_fitness++;
                            $this->map['total_traveller_fitness_hue'] = $hue_fitness;
                        }
                        if($value['province_code'] =='HN' || $value['province_code']=='HCM')
                        {
                            $hn_fitness++;
                            $this->map['total_traveller_fitness_hnhcm'] = $hn_fitness;
                        }
                        if($value['province_code'] !='HUE' && $value['province_code'] !='HN' && $value['province_code'] !='HCM')
                        {
                            $khac_fitness++;
                            $this->map['total_traveller_fitness_hue'] = $khac_fitness;
                        }
                    }
                }
            }
            if($value['portal_id'] =='#huequeen1')
            {
                $queen1++;
                $this->map['total_traveller_queen1'] = $queen1;
                if($value['continents_id'] ==1)// châu âu
                {
                    $euro_queen1++;
                    $this->map['total_traveller_queen1_euro'] = $euro_queen1;
                }
                if($value['continents_id'] ==2)// châu á
                {
                    $asia_queen1++;
                    $this->map['total_traveller_queen1_asia'] = $asia_queen1;
                }
                if($value['continents_id'] ==3)// khác
                {
                    $other_queen1++;
                    $this->map['total_traveller_queen1_other'] = $other_queen1;
                }
                if($value['province_id'] !='')
                {
                    
                    if($value['province_code'] =='HUE')
                    {
                        $hue_queen1++;
                        $this->map['total_traveller_queen1_hue'] = $hue_queen1;
                    }
                    if($value['province_code'] =='HN' || $value['province_code']=='HCM')
                    {
                        $hn_queen1++;
                        $this->map['total_traveller_queen1_hnhcm'] = $hn_queen1;
                    }
                    if($value['province_code'] !='HUE' && $value['province_code'] !='HN' && $value['province_code'] !='HCM')
                    {
                        $khac_queen1++;
                        $this->map['total_traveller_queen1_khac'] = $khac_queen1;
                    }
                }
            }
            if($value['portal_id'] =='#huequeen2')
            {
                $queen2++;
                $this->map['total_traveller_queen2'] = $queen2;
                if($value['continents_id'] ==1)// châu âu
                {
                    $euro_queen2++;
                    $this->map['total_traveller_queen2_euro'] = $euro_queen2;
                }
                if($value['continents_id'] ==2)// châu á
                {
                    $asia_queen2++;
                    $this->map['total_traveller_queen2_asia'] = $asia_queen2;
                }
                if($value['continents_id'] ==3)// khác
                {
                    $other_queen2++;
                    $this->map['total_traveller_queen2_other'] = $other_queen2;
                }
                if($value['province_id'] !='')
                {
                    
                    if($value['province_code'] =='HUE')
                    {
                        $hue_queen2++;
                        $this->map['total_traveller_queen2_hue'] = $hue_queen2;
                    }
                    if($value['province_code'] =='HN' || $value['province_code']=='HCM')
                    {
                        $hn_queen2++;
                        $this->map['total_traveller_queen2_hnhcm'] = $hn_queen2;
                    }
                    if($value['province_code'] !='HUE' && $value['province_code'] !='HN' && $value['province_code'] !='HCM')
                    {
                        $khac_queen2++;
                        $this->map['$total_traveller_queen2_khac'] = $khac_queen2;
                    }
                }
            }
        }
        // tổng lượng khách
        $this->map['total_traveller'] = $this->map['total_traveller_welness'] + $this->map['total_traveller_fitness'] + $this->map['total_traveller_queen1'] + $this->map['total_traveller_queen2'];
        // tổng lượng khách châu âu
        $this->map['total_traveller_euro'] =$this->map['total_traveller_welness_euro'] + $this->map['total_traveller_fitness_euro'] + $this->map['total_traveller_queen1_euro'] + $this->map['total_traveller_queen2_euro'];
        // tổng lượng khách châu á
        $this->map['total_traveller_asia'] =$this->map['total_traveller_welness_asia'] + $this->map['total_traveller_fitness_asia'] + $this->map['total_traveller_queen1_asia'] + $this->map['total_traveller_queen2_asia'];
        // tổng lượng khách khác
        $this->map['total_traveller_other'] =$this->map['total_traveller_welness_other'] + $this->map['total_traveller_fitness_other'] + $this->map['total_traveller_queen1_other'] + $this->map['total_traveller_queen2_other'];
        // tổng lượng khách  huế
        $this->map['total_traveller_hue'] =$this->map['total_traveller_welness_hue'] + $this->map['total_traveller_fitness_hue'] + $this->map['total_traveller_queen1_hue'] + $this->map['total_traveller_queen2_hue'];
        // tổng lượng khách  hà nội và HCM
        $this->map['total_traveller_hnhcm'] =$this->map['total_traveller_welness_hnhcm'] + $this->map['total_traveller_fitness_hnhcm'] + $this->map['total_traveller_queen1_hnhcm'] + $this->map['total_traveller_queen2_hnhcm'];
        // tổng lượng khách  việt nam vùng khác
        $this->map['total_traveller_khac'] =$this->map['total_traveller_welness_khac'] + $this->map['total_traveller_fitness_khac'] + $this->map['total_traveller_queen1_khac'] + $this->map['total_traveller_queen2_khac'];
        
        ////////////////////////////////////////////
        //TỔNG SỐ PHÒNG
        ///////////////////////////////////////////
        // tổng số phòng của khách sạn
        $room =DB::fetch_all('select room.id as id, room.portal_id, room.area_id from room left join area_group on room.area_id = area_group.id');
        $t_r_w = 0;
        $t_r_f = 0;
        $t_r_q1 = 0;
        $t_r_q2 = 0;
        $i=0;
        foreach($room as $ke => $val)
        {
            $i++;
            if($val['portal_id']=='#default')
            {
                if($val['area_id']==2)
                {
                   $t_r_w++;
                   $this->map['total_room_welness'] = $t_r_w; 
                }
                if($val['area_id']==1)
                {
                    $t_r_f++;
                    $this->map['total_room_fitness'] = $t_r_f;
                }
            }
            if($val['portal_id']=='#huequeen1')
            {
                $t_r_q1++;
                $this->map['total_room_queen1'] = $t_r_q1;
            }
            if($val['portal_id']=='#huequeen2')
            {
                $t_r_q2++;
                $this->map['total_room_queen2'] = $t_r_q2;
            }
            
        }
        $this->map['total_room_welness'] = $this->map['total_room_welness']*$songay;
        $this->map['total_room_fitness'] = $this->map['total_room_fitness']*$songay;
        $this->map['total_room_queen1'] = $this->map['total_room_queen1']*$songay;
        $this->map['total_room_queen2'] = $this->map['total_room_queen2']*$songay;
        //tổng phong của 3 portal
        $this->map['total_room'] = $this->map['total_room_welness'] + $this->map['total_room_fitness'] + $this->map['total_room_queen1'] + $this->map['total_room_queen2'];
        //số phòng đang repair
        $room_repair =DB::fetch_all('select room_status.id as id, room.portal_id, room.area_id from room_status inner join room on room.id = room_status.room_id where room_status.house_status=\'REPAIR\' AND room_status.in_date>=\''.$from_date.'\' AND room_status.in_date<=\''.$to_date.'\'');
       
        $t_r_r_w = 0;
        $t_r_r_f = 0;
        $t_r_r_q1 = 0;
        $t_r_r_q2 = 0;
        $i=0;
        foreach($room_repair as $ke => $val)
        {
            $i++;
            if($val['portal_id']=='#default')
            {
                if($val['area_id']==2)
                {
                   $t_r_r_w++;
                   $this->map['total_room_repair_welness'] = $t_r_r_w; 
                }
                if($val['area_id']==1)
                {
                    $t_r_r_f++;
                    $this->map['total_room_repair_fitness'] = $t_r_r_f;
                }
            }
            if($val['portal_id']=='#huequeen1')
            {
                $t_r_r_q1++;
                $this->map['total_room_repair_queen1'] = $t_r_r_q1;
            }
            if($val['portal_id']=='#huequeen2')
            {
                $t_r_r_q2++;
                $this->map['total_room_repair_queen2'] = $t_r_r_q2;
            }
            
        }
        $this->map['total_room_repair_welness'] = $this->map['total_room_repair_welness'];
        $this->map['total_room_repair_fitness'] = $this->map['total_room_repair_fitness'];
        $this->map['total_room_repair_queen1'] = $this->map['total_room_repair_queen1'];
        $this->map['total_room_repair_queen2'] = $this->map['total_room_repair_queen2'];
        // tổng phòng repair của 3 portal
        $this->map['total_room_repair'] = $this->map['total_room_repair_welness'] + $this->map['total_room_repair_fitness'] +  $this->map['total_room_repair_queen1'] + $this->map['total_room_repair_queen2'];
        // phòng available
        $this->map['total_room_available_welness'] = $this->map['total_room_welness'] - $this->map['total_room_repair_welness'];
        $this->map['total_room_available_fitness'] = $this->map['total_room_fitness'] - $this->map['total_room_repair_fitness'];
        $this->map['total_room_available_queen1'] = $this->map['total_room_queen1'] - $this->map['total_room_repair_queen1'];
        $this->map['total_room_available_queen2'] = $this->map['total_room_queen2'] - $this->map['total_room_repair_queen2'];
        // tổng phòng available của 3 portal
        $this->map['total_room_available'] = $this->map['total_room_available_welness'] + $this->map['total_room_available_fitness'] + $this->map['total_room_available_queen1'] + $this->map['total_room_available_queen2'];
        
        //////////////////////////////////////////
        //SỐ PHÒNG CÓ KHÁCH
        /////////////////////////////////////////
        $room_traveller = TotalyReportDB::room_have_traveller($cond1,$to_date);
        $t_r_t_w = 0;  
        $t_r_t_f = 0; 
        $t_r_t_q1 = 0; 
        $t_r_t_q2 = 0;                           
        foreach($room_traveller as $k=>$v)
        {
            
            if($v['portal_id'] == '#default')
            {
                if($v['area_id']==2)
                {
                    $t_r_t_w++;
                    $this->map['total_room_traveller_welness'] = $t_r_t_w;
                }
                if($v['area_id']==1)
                {
                    $t_r_t_f++;
                    $this->map['total_room_traveller_fitness'] = $t_r_t_f;
                }
            }
            if($v['portal_id'] == '#huequeen1')
            {
                $t_r_t_q1++;
                $this->map['total_room_traveller_queen1'] = $t_r_t_q1;
            }
            if($v['portal_id'] == '#huequeen2')
            {
                $t_r_t_q2++;
                $this->map['total_room_traveller_queen2'] = $t_r_t_q2;
            }
        }
        //////////////////////////////////
        //TỈ LỆ
        /////////////////////////////////
        $this->map['percent_welness'] = ($this->map['total_room_traveller_welness']/$this->map['total_room_welness'])*100;
        $this->map['percent_fitness'] = ($this->map['total_room_traveller_fitness']/$this->map['total_room_fitness'])*100;
        $this->map['percent_queen1'] = ($this->map['total_room_traveller_queen1']/$this->map['total_room_queen1'])*100;
        $this->map['percent_queen2'] = ($this->map['total_room_traveller_queen2']/$this->map['total_room_queen2'])*100;
        
        /////GIÁ CHƯA VAT
        ///////////////////////////////
        // DOANH THU BÁN PHÒNG
        //////////////////////////////
        $this->map['total_room_price_welness']=0;
        $this->map['total_room_price_fitness']=0;
        $this->map['total_room_price_queen1']=0;
        $this->map['total_room_price_queen2']=0;
        $room_price = TotalyReportDB::room_price_not_VAT($cond2,$to_date,$from_date);
        foreach($room_price as $key1=>$value1)
        {
            $value1['change_price'] = $value1['change_price'] - $value1['reduce_amount'] - ($value1['change_price']*$value1['reduce_balance']/100);
            if($value1['portal_id'] == '#default')
            {
                if($value1['area_id'] == 2)
                {
                    
                    $this->map['total_room_price_welness'] += $value1['change_price'];
                }
                if($value1['area_id'] == 1)
                {
                    $this->map['total_room_price_fitness'] += $value1['change_price'];
                }
            }
            if($value1['portal_id'] == '#huequeen1')
            {
                $this->map['total_room_price_queen1'] += $value1['change_price'];
            }
            if($value1['portal_id'] == '#huequeen2')
            {
                $this->map['total_room_price_queen2'] += $value1['change_price'];
            }
        }
        // KÌ TRƯỚC
        $room_pre_price = TotalyReportDB::room_price_not_VAT($pre_cond2,$pre_to_date,$pre_from_date);
        $this->map['total_room_price_pre_welness']=0;
        $this->map['total_room_price_pre_fitness']=0;
        $this->map['total_room_price_pre_queen1']=0;
        $this->map['total_room_price_pre_queen2']=0;
        foreach($room_pre_price as $key1=>$value1)
        {
            $value1['change_price'] = $value1['change_price'] - $value1['reduce_amount'] - ($value1['change_price']*$value1['reduce_balance']/100);
            if($value1['portal_id'] == '#default')
            {
                if($value1['area_id'] == 2)
                {
                    
                    $this->map['total_room_price_pre_welness'] += $value1['change_price'];
                }
                if($value1['area_id'] == 1)
                {
                    $this->map['total_room_price_pre_fitness'] += $value1['change_price'];
                }
            }
            if($value1['portal_id'] == '#huequeen1')
            {
                $this->map['total_room_price_pre_queen1'] += $value1['change_price'];
            }
            if($value1['portal_id'] == '#huequeen2')
            {
                $this->map['total_room_price_pre_queen2'] += $value1['change_price'];
            }
            
        }
        //////////////////////////////
        //////EI và LO
        //////////////////////////////
        $extra_service_room = TotalyReportDB::ei_lo($cond3);
        $this->map['total_e_service_welness'] =0;
        $this->map['total_e_service_fitness'] =0;
        $this->map['total_e_service_queen1'] =0;
        $this->map['total_e_service_queen2'] =0;
        foreach($extra_service_room as $k=>$s_r)
        {
            if($s_r['portal_id'] == '#default')
            {
                if($s_r['area_id'] == 2)
                {
                    $this->map['total_e_service_welness'] += $s_r['total_before_tax'];
                }
                if($s_r['area_id'] == 1)
                {
                    $this->map['total_e_service_fitness'] += $s_r['total_before_tax'];
                }
            }
            if($s_r['portal_id'] == '#huequeen1')
            {
                $this->map['total_e_service_queen1'] += $s_r['total_before_tax'];
            }
            if($s_r['portal_id'] == '#huequeen2')
            {
                $this->map['total_e_service_queen2'] += $s_r['total_before_tax'];
            }
        }
        //KÌ TRƯỚC
        $extra_service_pre_room = TotalyReportDB::ei_lo($pre_cond3);
        $this->map['total_e_service_pre_welness'] =0;
        $this->map['total_e_service_pre_fitness'] =0;
        $this->map['total_e_service_pre_queen1'] =0;
        $this->map['total_e_service_pre_queen2'] =0;
        foreach($extra_service_pre_room as $k=>$s_r)
        {
            if($s_r['portal_id'] == '#default')
            {
                if($s_r['area_id'] == 2)
                {
                    $this->map['total_e_service_pre_welness'] += $s_r['total_before_tax'];
                }
                if($s_r['area_id'] == 1)
                {
                    $this->map['total_e_service_pre_fitness'] += $s_r['total_before_tax'];
                }
            }
            if($s_r['portal_id'] == '#huequeen1')
            {
                $this->map['total_e_service_pre_queen1'] += $s_r['total_before_tax'];
            }
            if($s_r['portal_id'] == '#huequeen2')
            {
                $this->map['total_e_service_pre_queen2'] += $s_r['total_before_tax'];
            }
        }
        //tổng tiền phòng
        $this->map['total_room_price_welness'] = $this->map['total_room_price_welness'] + $this->map['total_e_service_welness'];
        $this->map['total_room_price_fitness'] = $this->map['total_room_price_fitness'] + $this->map['total_e_service_fitness'];
        $this->map['total_room_price_queen1'] = $this->map['total_room_price_queen1'] + $this->map['total_e_service_queen1'];
        $this->map['total_room_price_queen2'] = $this->map['total_room_price_queen2'] + $this->map['total_e_service_queen2'];
        $this->map['total_room']= $this->map['total_room_price_welness'] + $this->map['total_room_price_fitness'] + $this->map['total_room_price_queen1'] + $this->map['total_room_price_queen2'];
        // tổng tiền phòng kì trước
        $this->map['total_room_price_pre_welness'] = $this->map['total_room_price_pre_welness'] + $this->map['total_e_service_pre_welness'];
        $this->map['total_room_price_pre_fitness'] = $this->map['total_room_price_pre_fitness'] + $this->map['total_e_service_pre_fitness'];
        $this->map['total_room_price_pre_queen1'] = $this->map['total_room_price_pre_queen1'] + $this->map['total_e_service_pre_queen1'];
        $this->map['total_room_price_pre_queen2'] = $this->map['total_room_price_pre_queen2'] + $this->map['total_e_service_pre_queen2'];
        $this->map['total_pre_room']= $this->map['total_room_price_pre_welness'] + $this->map['total_room_price_pre_fitness'] + $this->map['total_room_price_pre_queen1'] + $this->map['total_room_price_pre_queen2'];
        if($this->map['total_pre_room'] > 0)
            $this->map['pre_room_percent'] = ($this->map['total_room'])*100/$this->map['total_pre_room'];
        else 
            $this->map['pre_room_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_room'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH13');
        if($this->map['plan_total_room'] > 0)
            $this->map['plan_total_room_percent'] = $this->map['total_room']*100/$this->map['plan_total_room'];
        else
            $this->map['plan_total_room_percent'] = 100;
        ///////////////////////////////
        //DỊCH VỤ PHÒNG HAY CÁI NGƯỜI NÀO ĐÓ ĐÃ SỦA LẠI LÀ DOANH THU BUỒNG
        //////////////////////////////
        //////////////////////////////
        //minibar, laundry,equipment
        /////////////////////////////
        $housekeeping = TotalyReportDB::minibar_laundry_equipment_price($cond4);
        $this->map['total_housekeeping_welness']=0;
        $this->map['total_housekeeping_fitness']=0;
        $this->map['total_housekeeping_queen1']=0;
        $this->map['total_housekeeping_queen2']=0;
        foreach($housekeeping as $key3=>$value3)
        {
            if($value3['portal_id'] == '#default')
            {
                if($value3['area_id'] == 2)
                {
                    $this->map['total_housekeeping_welness'] += $value3['total_before_tax'];
                }
                if($value3['area_id'] == 1)
                {
                    $this->map['total_housekeeping_fitness'] += $value3['total_before_tax'];
                }
            }
            if($value3['portal_id'] == '#huequeen1')
            {
                $this->map['total_housekeeping_queen1'] += $value3['total_before_tax'];
            }
            if($value3['portal_id'] == '#huequeen2')
            {
                $this->map['total_housekeeping_queen2'] += $value3['total_before_tax'];
            }
        }
        //KÌ TRƯỚC
        $housekeeping_pre = TotalyReportDB::minibar_laundry_equipment_price($pre_cond4);
        $this->map['total_housekeeping_pre_welness']=0;
        $this->map['total_housekeeping_pre_fitness']=0;
        $this->map['total_housekeeping_pre_queen1']=0;
        $this->map['total_housekeeping_pre_queen2']=0;
        foreach($housekeeping_pre as $key3=>$value3)
        {
            if($value3['portal_id'] == '#default')
            {
                if($value3['area_id'] == 2)
                {
                    $this->map['total_housekeeping_pre_welness'] += $value3['total_before_tax'];
                }
                if($value3['area_id'] == 1)
                {
                    $this->map['total_housekeeping_pre_fitness'] += $value3['total_before_tax'];
                }
            }
            if($value3['portal_id'] == '#huequeen1')
            {
                $this->map['total_housekeeping_pre_queen1'] += $value3['total_before_tax'];
            }
            if($value3['portal_id'] == '#huequeen2')
            {
                $this->map['total_housekeeping_pre_queen2'] += $value3['total_before_tax'];
            }
        }
        $this->map['total_service_welness'] = $this->map['total_housekeeping_welness'];
        $this->map['total_service_fitness'] = $this->map['total_housekeeping_fitness'];
        $this->map['total_service_queen1'] = $this->map['total_housekeeping_queen1'];
        $this->map['total_service_queen2'] = $this->map['total_housekeeping_queen2'];
        //KÌ TRƯỚC
        $this->map['total_service_pre_welness'] = $this->map['total_housekeeping_pre_welness'];
        $this->map['total_service_pre_fitness'] =  $this->map['total_housekeeping_pre_fitness'];
        $this->map['total_service_pre_queen1'] = $this->map['total_housekeeping_pre_queen1'];
        $this->map['total_service_pre_queen2'] = $this->map['total_housekeeping_pre_queen2'];
        // tổng dịch vu
        $this->map['total_service'] = $this->map['total_service_welness'] + $this->map['total_service_fitness']  + $this->map['total_service_queen1'] + $this->map['total_service_queen2'];
        // kì trước
        $this->map['total_service_pre'] = $this->map['total_service_pre_welness'] + $this->map['total_service_pre_fitness']  + $this->map['total_service_pre_queen1'] + $this->map['total_service_pre_queen2'];
        if($this->map['total_service_pre'] > 0)
            $this->map['service_pre_percent'] = ($this->map['total_service'])*100/$this->map['total_service_pre'];
        else
            $this->map['service_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_service'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH14');
        if($this->map['plan_total_service'] > 0)
            $this->map['plan_total_service_percent'] = $this->map['total_service']*100/$this->map['plan_total_service'];
        else
            $this->map['plan_total_service_percent'] = 100;
        ///////////////////////////////////
        //PHÒNG VÀ DỊCH VỤ PHÒNG
        //////////////////////////////////
        $this->map['amount_room_welness'] = $this->map['total_room_price_welness'] + $this->map['total_service_welness'] ; 
        $this->map['amount_room_fitness'] = $this->map['total_room_price_fitness'] + $this->map['total_service_fitness'] ;
        $this->map['amount_room_queen1'] = $this->map['total_room_price_queen1'] + $this->map['total_service_queen1'];
        $this->map['amount_room_queen2'] = $this->map['total_room_price_queen2'] + $this->map['total_service_queen2']; 
        //kfi trước
        $this->map['amount_room_pre_welness'] = $this->map['total_room_price_pre_welness'] + $this->map['total_service_pre_welness'] ; 
        $this->map['amount_room_pre_fitness'] = $this->map['total_room_price_pre_fitness'] + $this->map['total_service_pre_fitness'] ;
        $this->map['amount_room_pre_queen1'] = $this->map['total_room_price_pre_queen1'] + $this->map['total_service_pre_queen1'];
        $this->map['amount_room_pre_queen2'] = $this->map['total_room_price_pre_queen2'] + $this->map['total_service_pre_queen2']; 
        ///////////////////////////////////
        //NHÀ HÀNG
        //////////////////////////////////
        $bar = TotalyReportDB::bar_price($cond5);
        $this->map['total_bar_welness'] = 0;
        $this->map['total_bar_fitness'] = 0;
        $this->map['total_bar_queen1'] = 0;
        $this->map['total_bar_queen2'] = 0;
        foreach($bar as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_bar_welness'] += $value4['total_before_tax'];
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_bar_fitness'] += $value4['total_before_tax'];
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_bar_queen1'] += $value4['total_before_tax'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_bar_queen2'] += $value4['total_before_tax'];
            }
           
        }
        // tổng nhà hàng
        $this->map['total_bar'] = $this->map['total_bar_welness'] + $this->map['total_bar_fitness'] + $this->map['total_bar_queen1'] + $this->map['total_bar_queen2']; 
        //KÌ TRƯỚC
        $bar_pre = TotalyReportDB::bar_price($pre_cond5);
        $this->map['total_bar_pre_welness'] = 0;
        $this->map['total_bar_pre_fitness'] = 0;
        $this->map['total_bar_pre_queen1'] = 0;
        $this->map['total_bar_pre_queen2'] = 0;
        foreach($bar_pre as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_bar_pre_welness'] += $value4['total_before_tax'];
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_bar_pre_fitness'] += $value4['total_before_tax'];
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_bar_pre_queen1'] += $value4['total_before_tax'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_bar_pre_queen2'] += $value4['total_before_tax'];
            }
           
        }
        $this->map['total_bar_pre'] = $this->map['total_bar_pre_welness'] + $this->map['total_bar_pre_fitness'] + $this->map['total_bar_pre_queen1'] + $this->map['total_bar_pre_queen2']; 
        if($this->map['total_bar_pre'] > 0)
            $this->map['bar_pre_percent'] = ($this->map['total_bar'])*100/$this->map['total_bar_pre'];
        else
            $this->map['bar_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_bar'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH15');
        if($this->map['plan_total_bar'] > 0)
            $this->map['plan_total_bar_percent'] = $this->map['total_bar']*100/$this->map['plan_total_bar'];
        else
            $this->map['plan_total_bar_percent'] = 100;
        ////////////////////////////////////
        //SPA
        ///////////////////////////////////
        $this->map['total_spa_welness'] =0;
        $this->map['total_spa_fitness'] =0;
        $this->map['total_spa_queen1'] =0;
        $this->map['total_spa_queen2'] =0;
        $spa = TotalyReportDB::spa_price($cond6);
        foreach($spa as $key5=>$value5)
        {
           if($value5['portal_id'] == '#default')
            {
                if($value5['area_id'] == 2)
                {
                     $this->map['total_spa_welness']  += $value5['total_before_tax'];
                }
                if($value5['area_id'] == 1)
                {
                    $this->map['total_spa_fitness'] += $value5['total_before_tax'];
                }
            }
            if($value5['portal_id'] == '#huequeen1')
            {
                $this->map['total_spa_queen1'] += $value5['total_before_tax'];
            }
            if($value5['portal_id'] == '#huequeen2')
            {
                $this->map['total_spa_queen2'] += $value5['total_before_tax'];
            } 
        }
        //tổng spa
        $this->map['total_spa'] = $this->map['total_spa_welness'] + $this->map['total_spa_fitness'] + $this->map['total_spa_queen1'] + $this->map['total_spa_queen2'];
        //KÌ TRƯỚC
        $this->map['total_spa_pre_welness'] =0;
        $this->map['total_spa_pre_fitness'] =0;
        $this->map['total_spa_pre_queen1'] =0;
        $this->map['total_spa_pre_queen2'] =0;
        $spa_pre = TotalyReportDB::spa_price($pre_cond6);
        foreach($spa_pre as $key5=>$value5)
        {
           if($value5['portal_id'] == '#default')
            {
                if($value5['area_id'] == 2)
                {
                     $this->map['total_spa_pre_welness']  += $value5['total_before_tax'];
                }
                if($value5['area_id'] == 1)
                {
                    $this->map['total_spa_pre_fitness'] += $value5['total_before_tax'];
                }
            }
            if($value5['portal_id'] == '#huequeen1')
            {
                $this->map['total_spa_pre_queen1'] += $value5['total_before_tax'];
            }
            if($value5['portal_id'] == '#huequeen2')
            {
                $this->map['total_spa_pre_queen2'] += $value5['total_before_tax'];
            } 
        }
        //tổng spa
        $this->map['total_spa_pre'] = $this->map['total_spa_pre_welness'] + $this->map['total_spa_pre_fitness'] + $this->map['total_spa_pre_queen1'] + $this->map['total_spa_pre_queen2'];
        if($this->map['total_spa_pre']>0)
            $this->map['spa_pre_percent'] = ($this->map['total_spa'])*100/$this->map['total_spa_pre'];
        else
            $this->map['spa_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_spa'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH20');
        if($this->map['plan_total_spa']>0)
            $this->map['plan_total_spa_percent'] = $this->map['total_spa']*100/$this->map['plan_total_spa'];
        else
            $this->map['plan_total_spa_percent'] = 100;
        ////////////////////////////////////////
        //BÁN HÀNG
        ////////////////////////////////////////
        $souvenir = TotalyReportDB::souvenir_price($cond9);
        $this->map['total_souvenir_welness'] = 0;
        $this->map['total_souvenir_fitness'] = 0;
        $this->map['total_souvenir_queen1'] = 0;
        $this->map['total_souvenir_queen2'] = 0;
        foreach($souvenir as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                //if($value4['area_id'] == 2)
                //{
                     //$this->map['total_souvenir_welness'] += $value4['total_before_tax'];
                //}
                //if($value4['area_id'] == 1)
                //{
                    $this->map['total_souvenir_fitness'] += $value4['total_before_tax'];
                //}
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_souvenir_queen1'] += $value4['total_before_tax'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_souvenir_queen2'] += $value4['total_before_tax'];
            }
           
        }
        // tổng bán hàng
        $this->map['total_souvenir'] = $this->map['total_souvenir_welness'] + $this->map['total_souvenir_fitness'] + $this->map['total_souvenir_queen1'] + $this->map['total_souvenir_queen2']; 
        //KÌ TRƯỚC
        $souvenir_pre = TotalyReportDB::souvenir_price($pre_cond9);
        $this->map['total_souvenir_pre_welness'] = 0;
        $this->map['total_souvenir_pre_fitness'] = 0;
        $this->map['total_souvenir_pre_queen1'] = 0;
        $this->map['total_souvenir_pre_queen2'] = 0;
        foreach($souvenir_pre as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                //if($value4['area_id'] == 2)
                //{
                //     $this->map['total_souvenir_pre_welness'] += $value4['total_before_tax'];
                //}
                //if($value4['area_id'] == 1)
                //{
                    $this->map['total_souvenir_pre_fitness'] += $value4['total_before_tax'];
                //}
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_souvenir_pre_queen1'] += $value4['total_before_tax'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_souvenir_pre_queen2'] += $value4['total_before_tax'];
            }
           
        }
        $this->map['total_souvenir_pre'] = $this->map['total_souvenir_pre_welness'] + $this->map['total_souvenir_pre_fitness'] + $this->map['total_souvenir_pre_queen1'] + $this->map['total_souvenir_pre_queen2']; 
        if($this->map['total_souvenir_pre']>0)
            $this->map['souvenir_pre_percent'] = ($this->map['total_souvenir'])*100/$this->map['total_souvenir_pre'];
        else
            $this->map['souvenir_pre_percent']= 100;
        // kế hoạch
        $this->map['plan_total_souvenir'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH19');
        if($this->map['plan_total_souvenir'] > 0)
            $this->map['plan_total_souvenir_percent'] = $this->map['total_souvenir']*100/$this->map['plan_total_souvenir'];
        else
            $this->map['plan_total_souvenir_percent'] = 100;
        //////////////////////////////////////
        //ENTRANCE TICKET
        /////////////////////////////////////
        $entrance = TotalyReportDB::entrance_ticket_price($cond7);
        $this->map['total_entrance_welness'] = 0;
        $this->map['total_entrance_fitness'] = 0;
        $this->map['total_entrance_queen1'] = 0;
        $this->map['total_entrance_queen2'] = 0;
        $this->map['quantity_welness'] = 0;
        $this->map['quantity_fitness'] = 0;
        $this->map['quantity_queen1'] = 0;
        $this->map['quantity_queen2'] = 0;
        foreach($entrance as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_entrance_welness'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100)))/1.1;
                     $this->map['quantity_welness'] += $value4['quantity'];
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_entrance_fitness'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100)))/1.1;
                    $this->map['quantity_fitness'] += $value4['quantity'];
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_entrance_queen1'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100)))/1.1;
                $this->map['quantity_queen1'] += $value4['quantity'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_entrance_queen2'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100)))/1.1;
                $this->map['quantity_queen2'] += $value4['quantity'];
            }
           
        }
        $this->map['total_entrance'] = $this->map['total_entrance_welness'] + $this->map['total_entrance_fitness'] + $this->map['total_entrance_queen1'] + $this->map['total_entrance_queen2'];
        //KÌ TRƯỚC
        $pre_entrance = TotalyReportDB::entrance_ticket_price($pre_cond7);
        $this->map['total_entrance_pre_welness'] = 0;
        $this->map['total_entrance_pre_fitness'] = 0;
        $this->map['total_entrance_pre_queen1'] = 0;
        $this->map['total_entrance_pre_queen2'] = 0;
        foreach($pre_entrance as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_entrance_pre_welness'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100))/1.1);
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_entrance_pre_fitness'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100))/1.1);
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_entrance_pre_queen1'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100))/1.1);
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_entrance_pre_queen2'] += (((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100))-(((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']) - ((($value4['price']*($value4['quantity'] - $value4['discount_quantity'])) - (($value4['quantity'] - $value4['discount_quantity'])* $value4['discount_cash']))*$value4['discount_rate']/100)))*$value4['discount_percent']/100))/1.1);
            }
           
        }
        $this->map['total_entrance_pre'] = $this->map['total_entrance_pre_welness'] + $this->map['total_entrance_pre_fitness'] + $this->map['total_entrance_pre_queen1'] + $this->map['total_entrance_pre_queen2'];
        if($this->map['total_entrance_pre']>0)
            $this->map['entrance_pre_percent'] = ($this->map['total_entrance'])*100/$this->map['total_entrance_pre'];
        else
            $this->map['entrance_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_entrance'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH12');
        if($this->map['plan_total_entrance']>0)
            $this->map['plan_total_entrance_percent'] = $this->map['total_entrance']*100/$this->map['plan_total_entrance'];
        else
            $this->map['plan_total_entrance_percent'] = 100;
        ///////////////////////////////////////
        //highwire
        //////////////////////////////////////
        require_once 'packages/hotel/packages/ticket/modules/TicketServiceTotalReport/db.php';
        $highwire = TicketServiceTotalReportDB::get_all_ticket_service_revenue($cond8,$highwire_cond);
        $this->map['total_highwire_welness'] = 0;
        $this->map['total_highwire_fitness'] = 0;
        $this->map['total_highwire_queen1'] = 0;
        $this->map['total_highwire_queen2'] = 0;
        foreach($highwire as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_highwire_welness'] += $value4['net_amount'];
                    
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_highwire_fitness'] += $value4['net_amount'];;
                    
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_highwire_queen1'] += $value4['net_amount'];;
                
                
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_highwire_queen2'] += $value4['net_amount'];;
                
            }
           
        }
        $this->map['total_highwire'] = $this->map['total_highwire_welness'] + $this->map['total_highwire_fitness'] + $this->map['total_highwire_queen1'] + $this->map['total_highwire_queen2'];
        //ki truoc
        $pre_highwire = TicketServiceTotalReportDB::get_all_ticket_service_revenue($pre_cond8,$highwire_cond);
        
        $this->map['total_highwire_pre_welness'] = 0;
        $this->map['total_highwire_pre_fitness'] = 0;
        $this->map['total_highwire_pre_queen1'] = 0;
        $this->map['total_highwire_pre_queen2'] = 0;
        foreach($pre_highwire as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_highwire_pre_welness'] += $value4['net_amount'];
                    
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_highwire_pre_fitness'] += $value4['net_amount'];;
                    
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_highwire_pre_queen1'] += $value4['net_amount'];;
                
                
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_highwire_pre_queen2'] += $value4['net_amount'];;
                
            }
           
        }
        $this->map['total_highwire_pre'] = $this->map['total_highwire_pre_welness'] + $this->map['total_highwire_pre_fitness'] + $this->map['total_highwire_pre_queen1'] + $this->map['total_highwire_pre_queen2'];
        if($this->map['total_highwire_pre']>0)
            $this->map['highwire_pre_percent'] = ($this->map['total_highwire'])*100/$this->map['total_highwire_pre'];
        else
            $this->map['highwire_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_highwire'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH16');
        if($this->map['plan_total_highwire'] > 0)
            $this->map['plan_total_highwire_percent'] = $this->map['total_highwire']*100/$this->map['plan_total_highwire'];
        else
            $this->map['plan_total_highwire_percent'] = 100;
        //////////////////////////////////////////////////
        //ZIPLINE
        //////////////////////////////////////////////////
        $zipline= TicketServiceTotalReportDB::get_all_ticket_service_revenue($cond8,$zipline_cond);
        $this->map['total_zipline_welness'] = 0;
        $this->map['total_zipline_fitness'] = 0;
        $this->map['total_zipline_queen1'] = 0;
        $this->map['total_zipline_queen2'] = 0;
        foreach($zipline as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_zipline_welness'] += $value4['net_amount'];
                    
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_zipline_fitness'] += $value4['net_amount'];;
                    
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_zipline_queen1'] += $value4['net_amount'];;
                
                
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_zipline_queen2'] += $value4['net_amount'];;
                
            }
           
        }
        $this->map['total_zipline'] = $this->map['total_zipline_welness'] + $this->map['total_zipline_fitness'] + $this->map['total_zipline_queen1'] + $this->map['total_zipline_queen2'];
        //ki truoc
        $pre_zipline = TicketServiceTotalReportDB::get_all_ticket_service_revenue($pre_cond8,$zipline_cond);
        
        $this->map['total_zipline_pre_welness'] = 0;
        $this->map['total_zipline_pre_fitness'] = 0;
        $this->map['total_zipline_pre_queen1'] = 0;
        $this->map['total_zipline_pre_queen2'] = 0;
        foreach($pre_zipline as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_zipline_pre_welness'] += $value4['net_amount'];
                    
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_zipline_pre_fitness'] += $value4['net_amount'];;
                    
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_zipline_pre_queen1'] += $value4['net_amount'];;
                
                
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_zipline_pre_queen2'] += $value4['net_amount'];;
                
            }
           
        }
        $this->map['total_zipline_pre'] = $this->map['total_zipline_pre_welness'] + $this->map['total_zipline_pre_fitness'] + $this->map['total_zipline_pre_queen1'] + $this->map['total_zipline_pre_queen2'];
        if($this->map['total_zipline_pre']>0)
            $this->map['zipline_pre_percent'] = ($this->map['total_zipline'])*100/$this->map['total_zipline_pre'];
        else
            $this->map['zipline_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_zipline'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH17');
        if($this->map['plan_total_zipline'] > 0)
            $this->map['plan_total_zipline_percent'] = $this->map['total_zipline']*100/$this->map['plan_total_zipline'];
        else
            $this->map['plan_total_zipline_percent'] = 100;
        //////////////////////////////////
        //LÀNG NGHỀ
        ///////////////////////////////////
        $village = TotalyReportDB::village_price($cond5);
        $this->map['total_village_welness'] = 0;
        $this->map['total_village_fitness'] = 0;
        $this->map['total_village_queen1'] = 0;
        $this->map['total_village_queen2'] = 0;
        foreach($village as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_village_welness'] += $value4['total_before_tax'];
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_village_fitness'] += $value4['total_before_tax'];
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_village_queen1'] += $value4['total_before_tax'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_village_queen2'] += $value4['total_before_tax'];
            }
           
        }
        // tổng làng nghề
        $this->map['total_village'] = $this->map['total_village_welness'] + $this->map['total_village_fitness'] + $this->map['total_village_queen1'] + $this->map['total_village_queen2']; 
        //KÌ TRƯỚC
        $village_pre = TotalyReportDB::village_price($pre_cond5);
        $this->map['total_village_pre_welness'] = 0;
        $this->map['total_village_pre_fitness'] = 0;
        $this->map['total_village_pre_queen1'] = 0;
        $this->map['total_village_pre_queen2'] = 0;
        foreach($village_pre as $key4=>$value4)
        {
            if($value4['portal_id'] == '#default')
            {
                if($value4['area_id'] == 2)
                {
                     $this->map['total_village_pre_welness'] += $value4['total_before_tax'];
                }
                if($value4['area_id'] == 1)
                {
                    $this->map['total_village_pre_fitness'] += $value4['total_before_tax'];
                }
            }
            if($value4['portal_id'] == '#huequeen1')
            {
                $this->map['total_village_pre_queen1'] += $value4['total_before_tax'];
            }
            if($value4['portal_id'] == '#huequeen2')
            {
                $this->map['total_village_pre_queen2'] += $value4['total_before_tax'];
            }
           
        }
        $this->map['total_village_pre'] = $this->map['total_village_pre_welness'] + $this->map['total_village_pre_fitness'] + $this->map['total_village_pre_queen1'] + $this->map['total_village_pre_queen2']; 
        if($this->map['total_village_pre'] > 0)
            $this->map['village_pre_percent'] = ($this->map['total_village'])*100/$this->map['total_village_pre'];
        else
            $this->map['village_pre_percent'] = 0;
        // kế hoạch
        $this->map['plan_total_village'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH18');
        if($this->map['plan_total_village'] > 0)
            $this->map['plan_total_village_percent'] = $this->map['total_village']*100/$this->map['plan_total_village'];
        else
            $this->map['plan_total_village_percent'] = 100;
        ////////////////////////
        /////////DỊCH VỤ KHÁC
        ///////////////////////
        /////////////////////////
        //extra_service
        //////////////////////////
        $extra_service = TotalyReportDB::extra_service_price($cond3);
        $this->map['total_extra_service_welness']=0;
        $this->map['total_extra_service_fitness']=0;
        $this->map['total_extra_service_queen1']=0;
        $this->map['total_extra_service_queen2']=0;
        foreach($extra_service as $key2=>$value2)
        {
            if($value2['portal_id'] == '#default')
            {
                if($value2['area_id'] == 2)
                {
                    $this->map['total_extra_service_welness'] += $value2['total_before_tax'];
                }
                if($value2['area_id'] == 1)
                {
                    $this->map['total_extra_service_fitness'] += $value2['total_before_tax'];
                }
            }
            if($value2['portal_id'] == '#huequeen1')
            {
                $this->map['total_extra_service_queen1'] += $value2['total_before_tax'];
            }
            if($value2['portal_id'] == '#huequeen2')
            {
                $this->map['total_extra_service_queen2'] += $value2['total_before_tax'];
            }
        }
        //KÌ TRƯỚC
        $extra_service_pre = TotalyReportDB::extra_service_price($pre_cond3);
        $this->map['total_extra_service_pre_welness']=0;
        $this->map['total_extra_service_pre_fitness']=0;
        $this->map['total_extra_service_pre_queen1']=0;
        $this->map['total_extra_service_pre_queen2']=0;
        foreach($extra_service_pre as $key2=>$value2)
        {
            if($value2['portal_id'] == '#default')
            {
                if($value2['area_id'] == 2)
                {
                    $this->map['total_extra_service_pre_welness'] += $value2['total_before_tax'];
                }
                if($value2['area_id'] == 1)
                {
                    $this->map['total_extra_service_pre_fitness'] += $value2['total_before_tax'];
                }
            }
            if($value2['portal_id'] == '#huequeen1')
            {
                $this->map['total_extra_service_pre_queen1'] += $value2['total_before_tax'];
            }
            if($value2['portal_id'] == '#huequeen2')
            {
                $this->map['total_extra_service_pre_queen2'] += $value2['total_before_tax'];
            }
        }
        $this->map['total_other_service_welness'] = $this->map['total_extra_service_welness'];
        $this->map['total_other_service_fitness'] = $this->map['total_extra_service_fitness'];
        $this->map['total_other_service_queen1'] = $this->map['total_extra_service_queen1'];
        $this->map['total_other_service_queen2'] = $this->map['total_extra_service_queen2'];
        //KÌ TRƯỚC
        $this->map['total_other_service_pre_welness'] = $this->map['total_extra_service_pre_welness'];
        $this->map['total_other_service_pre_fitness'] =  $this->map['total_extra_service_pre_fitness'];
        $this->map['total_other_service_pre_queen1'] = $this->map['total_extra_service_pre_queen1'];
        $this->map['total_other_service_pre_queen2'] = $this->map['total_extra_service_pre_queen2'];
        // tổng dịch vu
        $this->map['total_other_service'] = $this->map['total_other_service_welness'] + $this->map['total_other_service_fitness']  + $this->map['total_other_service_queen1'] + $this->map['total_other_service_queen2'];
        // kì trước
        $this->map['total_other_service_pre'] = $this->map['total_other_service_pre_welness'] + $this->map['total_other_service_pre_fitness']  + $this->map['total_other_service_pre_queen1'] + $this->map['total_other_service_pre_queen2'];
        if($this->map['total_other_service_pre']>0)
            $this->map['other_service_pre_percent'] = ($this->map['total_other_service'])*100/$this->map['total_other_service_pre'];       
        if($this->map['total_other_service_pre']==0)
            $this->map['other_service_pre_percent'] = 100;
        // kế hoạch
        $this->map['plan_total_other_service'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH22');
        if($this->map['plan_total_other_service']>0)
            $this->map['plan_total_other_service_percent'] = $this->map['total_other_service']*100/$this->map['plan_total_other_service'];
        else
            $this->map['plan_total_other_service_percent'] = 100;
        ////////////////////////////////////
        //TỔNG DOANH THU THỰC TẾ KHÔNG VAT
        ////////////////////////////////////
        $this->map['total_welness'] = $this->map['amount_room_welness'] + $this->map['total_bar_welness'] + $this->map['total_spa_welness'] + $this->map['total_entrance_welness'] + $this->map['total_highwire_welness'] + $this->map['total_zipline_welness'] + $this->map['total_village_welness'] + $this->map['total_souvenir_welness'] + $this->map['total_other_service_welness'];
        $this->map['total_fitness'] = $this->map['amount_room_fitness'] + $this->map['total_bar_fitness'] + $this->map['total_spa_fitness'] + $this->map['total_entrance_fitness']  + $this->map['total_highwire_fitness'] + $this->map['total_zipline_fitness'] + $this->map['total_village_fitness'] + $this->map['total_souvenir_fitness'] + $this->map['total_other_service_fitness'];
        $this->map['total_queen1'] = $this->map['amount_room_queen1'] + $this->map['total_bar_queen1'] + $this->map['total_spa_queen1'] + $this->map['total_entrance_queen1'] + $this->map['total_highwire_queen1'] + $this->map['total_zipline_queen1'] + $this->map['total_village_queen1'] + $this->map['total_souvenir_queen1'] + $this->map['total_other_service_queen1'];
        $this->map['total_queen2'] = $this->map['amount_room_queen2'] + $this->map['total_bar_queen2'] + $this->map['total_spa_queen2'] + $this->map['total_entrance_queen2'] + $this->map['total_highwire_queen2'] + $this->map['total_zipline_queen2'] + $this->map['total_village_queen2'] + $this->map['total_souvenir_queen2'] + $this->map['total_other_service_queen2'];
        //tổng doanh thu thực tê
        $this->map['total_actuallly'] = $this->map['total_welness'] + $this->map['total_fitness'] + $this->map['total_queen1'] + $this->map['total_queen2'];
        //KÌ TRƯỚC
        $this->map['total_pre_welness'] = $this->map['amount_room_pre_welness'] + $this->map['total_bar_pre_welness'] + $this->map['total_spa_pre_welness'] + $this->map['total_entrance_pre_welness'] + $this->map['total_highwire_pre_welness'] + $this->map['total_zipline_pre_welness'] + $this->map['total_village_pre_welness'] + $this->map['total_souvenir_pre_welness'] + $this->map['total_other_service_pre_welness'];
        $this->map['total_pre_fitness'] = $this->map['amount_room_pre_fitness'] + $this->map['total_bar_pre_fitness'] + $this->map['total_spa_pre_fitness'] + $this->map['total_entrance_pre_fitness'] + $this->map['total_highwire_pre_fitness'] + $this->map['total_zipline_pre_fitness'] + $this->map['total_village_pre_fitness'] + $this->map['total_souvenir_pre_fitness'] + $this->map['total_other_service_pre_fitness'];
        $this->map['total_pre_queen1'] = $this->map['amount_room_pre_queen1'] + $this->map['total_bar_pre_queen1'] + $this->map['total_spa_pre_queen1'] + $this->map['total_entrance_pre_queen1'] + $this->map['total_highwire_pre_queen1'] + $this->map['total_zipline_pre_queen1'] + $this->map['total_village_pre_queen1'] + $this->map['total_souvenir_pre_queen1'] + $this->map['total_other_service_pre_queen1'];
        $this->map['total_pre_queen2'] = $this->map['amount_room_pre_queen2'] + $this->map['total_bar_pre_queen2'] + $this->map['total_spa_pre_queen2'] + $this->map['total_entrance_pre_queen2'] + $this->map['total_highwire_pre_queen2'] + $this->map['total_zipline_pre_queen2'] + $this->map['total_village_pre_queen2'] + $this->map['total_souvenir_pre_queen2'] + $this->map['total_other_service_pre_queen2'];
        //tổng doanh thu thực tê ki truoc
        $this->map['total_pre_actuallly'] = $this->map['total_pre_welness'] + $this->map['total_pre_fitness'] + $this->map['total_pre_queen1'] + $this->map['total_pre_queen2'];
        $this->map['total_pre_actuallly_percent'] = ($this->map['total_actuallly'])*100/$this->map['total_pre_actuallly'];
        // kế hoạch
        $this->map['plan_total_actuallly'] = TotalyReportDB::plan($cond_plan, $plan_name ='KHTH23');
        if($this->map['plan_total_actuallly']>0)
            $this->map['plan_total_actuallly_percent'] = $this->map['total_actuallly']*100/$this->map['plan_total_actuallly'];
        else
            $this->map['plan_total_actuallly_percent'] = 100;
        //////////////////////////////////////
        //ĐƠN VỊ GIÁ TRUNG BÌNH
        /////////////////////////////////////
        //dịch vụ phòng
        if($this->map['total_room_traveller_welness']>0)
            $this->map['everage_room_welness'] = $this->map['total_room_price_welness'] / $this->map['total_room_traveller_welness'];
        else
            $this->map['everage_room_welness'] = 0;
        if($this->map['total_room_traveller_fitness']>0)
            $this->map['everage_room_fitness'] = $this->map['total_room_price_fitness'] / $this->map['total_room_traveller_fitness'];
        else
            $this->map['everage_room_fitness'] = 0;
        if($this->map['total_room_traveller_queen1']>0)
            $this->map['everage_room_queen1'] = $this->map['total_room_price_queen1'] / $this->map['total_room_traveller_queen1'];
        else
            $this->map['everage_room_queen1'] = 0;
        if($this->map['total_room_traveller_queen2']>0)
            $this->map['everage_room_queen2'] = $this->map['total_room_price_queen2'] / $this->map['total_room_traveller_queen2'];
        else
            $this->map['everage_room_queen2'] = 0;
        // vé vào cửa
        if($this->map['quantity_welness']>0)
            $this->map['everage_entrance_welness'] = $this->map['total_entrance_welness'] / $this->map['quantity_welness'];
        else
            $this->map['everage_entrance_welness'] = 0;
        if($this->map['quantity_fitness']>0)
            $this->map['everage_entrance_fitness'] = $this->map['total_entrance_fitness'] / $this->map['quantity_fitness'];
        else
            $this->map['everage_entrance_fitness'] = 0;
        if($this->map['quantity_queen1']>0)
            $this->map['everage_entrance_queen1'] = $this->map['total_entrance_queen1'] / $this->map['quantity_queen1'];
        else
            $this->map['everage_entrance_queen1'] = 0;
        if($this->map['quantity_queen2']>0)
            $this->map['everage_entrance_queen2'] = $this->map['total_entrance_queen2'] / $this->map['quantity_queen2'];
        else
            $this->map['everage_entrance_queen2'] = 0;
        ///////////////////////////////
        //////////////
        //////////////////////////////
        $data_hot[1] = $this->map['total_entrance_welness'] + $this->map['total_entrance_fitness'];
        $data_queen[1] = $this->map['total_entrance_queen1'];
        $data_hotel[1] = $this->map['total_entrance_queen2'];
        
        $data_hot[2] = $this->map['total_room_price_welness'] + $this->map['total_room_price_fitness'];
        $data_queen[2] = $this->map['total_room_price_queen1'];
        $data_hotel[2] = $this->map['total_room_price_queen2'];
        
        $data_hot[3] = $this->map['total_service_welness'] + $this->map['total_service_fitness'];
        $data_queen[3] = $this->map['total_service_queen1'];
        $data_hotel[3] = $this->map['total_service_queen2'];
        
        $data_hot[4] = $this->map['total_bar_welness'] + $this->map['total_bar_fitness'];
        $data_queen[4] = $this->map['total_bar_queen1'];
        $data_hotel[4] = $this->map['total_bar_queen2'];
        
        $data_hot[5] = $this->map['total_highwire_welness'] + $this->map['total_highwire_fitness'];
        $data_queen[5] = $this->map['total_highwire_queen1'];
        $data_hotel[5] = $this->map['total_highwire_queen2'];
        
        $data_hot[6] = $this->map['total_zipline_welness'] + $this->map['total_zipline_fitness'];
        $data_queen[6] = $this->map['total_zipline_queen1'];
        $data_hotel[6] = $this->map['total_zipline_queen2'];
        
        $data_hot[7] = $this->map['total_village_welness'] + $this->map['total_village_fitness'];
        $data_queen[7] = $this->map['total_village_queen1'];
        $data_hotel[7] = $this->map['total_village_queen2'];
        
        $data_hot[8] = $this->map['total_souvenir_welness'] + $this->map['total_souvenir_fitness'];
        $data_queen[8] = $this->map['total_souvenir_queen1'];
        $data_hotel[8] = $this->map['total_souvenir_queen2'];
        
        $data_hot[9] = $this->map['total_spa_welness'] + $this->map['total_spa_fitness'];
        $data_queen[9] = $this->map['total_spa_queen1'];
        $data_hotel[9] = $this->map['total_spa_queen2'];
        
        $data_total[1] = 0;
        $data_total[2] = 0;
        $data_total[3] = 0;
        $data_total[4] = 0;
        
        for($i = 1; $i <= 9; $i++)
        {
            $data_total[1] += $data_hot[$i];
        }
        for($i = 1; $i <= 9; $i++)
        {
            $data_total[2] += $data_queen[$i];
        }
        for($i = 1; $i <= 9; $i++)
        {
            $data_total[3] += $data_hotel[$i];
        }
        /*
        System::debug($this->map+array('data_hot'=>json_encode($data_hot),
                                                    'data_queen'=>json_encode($data_queen),
                                                    'data_hotel'=>json_encode($data_hotel),
                                                    'data_total'=>json_encode($data_total)));exit();
                                                    */
                                                    
        $this->parse_layout('report',$this->map+array('data_hot'=>json_encode($data_hot),
                                                    'data_queen'=>json_encode($data_queen),
                                                    'data_hotel'=>json_encode($data_hotel),
                                                    'data_total'=>json_encode($data_total)));
	}
   
}
?>