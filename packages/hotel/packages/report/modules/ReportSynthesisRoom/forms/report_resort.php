<?php
class ReportSynthesisRoomForm extends Form
{
	function ReportSynthesisRoomForm()
	{
		Form::Form('ReportSynthesisRoomForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
    {
        
        $dautuan = $this->get_beginning_date_of_week();
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_week();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):$this->get_end_date_of_week();
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
		//$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/'));
		//$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
		//-------------------------------------------------------------------------------------------------------------
        $datetime_from = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $day = date('d',Date_Time::to_time($datetime_from));
        $m = date('m',Date_Time::to_time($datetime_from));
        $y = date('Y',Date_Time::to_time($datetime_from));
        $datetime_to = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $day2 = date('d',Date_Time::to_time($datetime_to));
        $m2 = date('m',Date_Time::to_time($datetime_to));
        $y2 = date('Y',Date_Time::to_time($datetime_to));
        $finish1 = $day.'/'.$m.'/'.($y-1);
        $finish2 = $day2.'/'.$m2.'/'.($y-1);
        $day_cond3_1 = $day.'/'.($m-($m2-$m+1)).'/'.($y2);
        $day_cond3_2 = $day2.'/'.($m-1).'/'.($y2);
        echo $day_cond3_1.'-'.$day_cond3_2;
        //echo $finish1;
        $date_from2 = Date_Time::to_orc_date($finish1);
        $date_to2 = Date_Time::to_orc_date($finish2);
        
        $date_from3 = Date_Time::to_orc_date($day_cond3_1);
        $date_to3 = Date_Time::to_orc_date($day_cond3_2);
        //---------------------------------------------------------------------------------------------------------------
        $month = date('m');
        $cond = $this->cond = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from.'\' and reservation_room.departure_time<=\''.$date_to.'\'';
		$cond .= (URL::get('portal')?' and room.portal_id = \'#'.URL::get('portal').'\' ':'') ;
        //----------------------------------------------------------------------------------------------------------------
        $cond2 = $this->cond2 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from2.'\' and reservation_room.departure_time<=\''.$date_to2.'\'';
		$cond2 .= (URL::get('portal')?' and room.portal_id = \'#'.URL::get('portal').'\' ':'') ;
        //--------------------------------------------------------------------------------------------------------------------
        $cond3 = $this->cond3 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from3.'\' and reservation_room.departure_time<=\''.$date_to3.'\'';
		$cond3 .= (URL::get('portal')?' and room.portal_id = \'#'.URL::get('portal').'\' ':'') ;
        //-----------------------------------------------------------------------------------------------------------------------
        $sql_portal = ('
                    select
                                reservation_room.id
                                ,room.ROOM_TYPE_ID
                                ,reservation_room.status 
                                ,reservation_room.time_in
                                ,room_type.name
                                ,room.portal_id
                            from
                                reservation_room
                                inner join room on room.id = reservation_room.room_id
                                inner join room_type on room_type.id = room.room_type_id
                            where 
                                reservation_room.time_in != 0
                                and room_id != 0
                                and'.$cond.'
                                and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                            order by portal_id
        '); 
        
        $test5=DB::fetch_all($sql_portal);
        //System::debug($sql_portal);
        //System::debug($test5);
        $total_resort = array();
        foreach($test2 as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            //System::debug($test);
            $total_resort[$value['portal_id']]['equipment_last_year'] = 0;
            $total_resort[$value['portal_id']]['room_revenue_last_year'] = 0;
            $total_resort[$value['portal_id']]['name_last_year'] = 0;
            $total_resort[$value['portal_id']]['minibar_last_year'] = 0;
            $total_resort[$value['portal_id']]['laundry_last_year'] = 0;
            $total_resort[$value['portal_id']]['extra_services_last_year'] = 0;
            
            $total_resort[$value['portal_id']]['equipment_same_period'] = 0;
            $total_resort[$value['portal_id']]['room_revenue_same_period'] = 0;
            $total_resort[$value['portal_id']]['name_same_period'] = 0;
            $total_resort[$value['portal_id']]['minibar_same_period'] = 0;
            $total_resort[$value['portal_id']]['laundry_same_period'] = 0;
            $total_resort[$value['portal_id']]['extra_services_same_period'] = 0;
            $total_resort[$value['portal_id']]['EI_LO_same_period'] = 0;
            
            $total_resort[$value['portal_id']]['total_month_room'] = 0;
            $total_resort[$value['portal_id']]['total_month_minibar'] = 0;
            $total_resort[$value['portal_id']]['total_month_laundry'] = 0;
            $total_resort[$value['portal_id']]['total_month_extra_services'] = 0;
            $total_resort[$value['portal_id']]['EI_LO_last_year'] = 0;
            if(isset($total_resort[$value['portal_id']]['room_revenue']))
            {
                $total_resort[$value['portal_id']]['room_revenue'] += $test['room'];
                $total_resort[$value['portal_id']]['extra_services'] += $test['extra_services'];
                $total_resort[$value['portal_id']]['minibar'] += $test['minibar'];
                $total_resort[$value['portal_id']]['laundry'] += $test['laundry'];
                $total_resort[$value['portal_id']]['name'] = $value['name'];
                $total_resort[$value['portal_id']]['equipment'] += $test['equipment'];
                $total_resort[$value['portal_id']]['EI_LO'] += $test['extra_services_EI_LO'];        
            }
            else 
            {
                $total_resort[$value['portal_id']]['room_revenue'] = $test['room'];
                $total_resort[$value['portal_id']]['name'] = $value['name'];
                $total_resort[$value['portal_id']]['laundry'] = $test['laundry'];
                $total_resort[$value['portal_id']]['extra_services'] = $test['extra_services'];
                $total_resort[$value['portal_id']]['minibar'] = $test['minibar'];
                $total_resort[$value['portal_id']]['equipment'] = $test['equipment'];
                $total_resort[$value['portal_id']]['EI_LO'] = $test['extra_services_EI_LO']; 
            } 
        }
        
        //-----------------------------------------------cung ki nam ngoai----------------------------------------------
        $sql2_resort = '
                    select
                        reservation_room.id
                        ,room.ROOM_TYPE_ID
                        ,reservation_room.time_in
                        ,room_type.name
                        ,room.portal_id
                    from
                        reservation_room
                        inner join room on room.id = reservation_room.room_id
                        inner join room_type on room_type.id = room.room_type_id
                    where 
                        reservation_room.time_in != 0
                        and room_id != 0
                        and'.$cond2.'
                        and reservation_room.status = \'CHECKIN\' 
                        and reservation_room.status = \'CHECKOUT\'
                    order by portal_id
        ';
        $test3_resort=DB::fetch_all($sql2_resort);
        //System::debug($sql2);
        foreach($test3_resort as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            if(isset($total_resort[$value['portal_id']]['room_revenue_last_year']))
            {
                $total_resort[$value['portal_id']]['room_revenue_last_year'] += $test['room'];
                $total_resort[$value['portal_id']]['extra_services_last_year'] += $test['extra_services'];
                $total_resort[$value['portal_id']]['minibar_last_year'] += $test['minibar'];
                $total_resort[$value['portal_id']]['laundry_last_year'] += $test['laundry'];
                $total_resort[$value['portal_id']]['equipment_last_year'] += $test['equipment'];
                $total_resort[$value['portal_id']]['name_last_year'] = $value['name'];
                $total_resort[$value['portal_id']]['EI_LO_last_year'] += $test['extra_services_EI_LO'];        
            }
            else 
            {
                $total_resort[$value['portal_id']]['room_revenue_last_year'] = $test['room'];
                $total_resort[$value['portal_id']]['equipment_last_year'] = $test['equipment'];
                $total_resort[$value['portal_id']]['name_last_year'] = $value['name'];
                $total_resort[$value['portal_id']]['laundry_last_year'] = $test['laundry'];
                $total_resort[$value['portal_id']]['extra_services_last_year'] = $test['extra_services'];
                $total_resort[$value['portal_id']]['minibar_last_year'] = $test['minibar'];
                $total_resort[$value['portal_id']]['EI_LO_last_year'] = $test['extra_services_EI_LO'];
            }
        }
        //System::debug($total_revenue);
        //-------------------------------------/cung ki nam ngoai--------------------------------------------------------
        //-------------------------------------cung ki--------------------------------------------------------
        $sql3_resort = '
                    select
                        reservation_room.id
                        ,room.ROOM_TYPE_ID
                        ,reservation_room.time_in
                        ,room_type.name
                        ,room.portal_id
                    from
                        reservation_room
                        inner join room on room.id = reservation_room.room_id
                        inner join room_type on room_type.id = room.room_type_id
                    where 
                        reservation_room.time_in != 0
                        and room_id != 0
                        and'.$cond3.'
                        and reservation_room.status = \'CHECKIN\' 
                        and reservation_room.status = \'CHECKOUT\'
                    order by portal_id
        ';
        $test4_resort=DB::fetch_all($sql3_resort);
        //System::debug($sql3);
        foreach($test4_resort as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            if(isset($total_resort[$value['portal_id']]['room_revenue_same_period']))
            {
                $total_resort[$value['portal_id']]['room_revenue_same_period'] += $test['room'];
                $total_resort[$value['portal_id']]['extra_services_same_period'] += $test['extra_services'];
                $total_resort[$value['portal_id']]['minibar_same_period'] += $test['minibar'];
                $total_resort[$value['portal_id']]['laundry_same_period'] += $test['laundry'];
                $total_resort[$value['portal_id']]['equipment_same_period'] += $test['equipment'];
                $total_resort[$value['portal_id']]['name_same_period'] = $value['name'];
                $total_resort[$value['portal_id']]['EI_LO_same_period'] += $test['extra_services_EI_LO'];        
            }
            else 
            {
                $total_resort[$value['portal_id']]['room_revenue_same_period'] = $test['room'];
                $total_resort[$value['portal_id']]['equipment_same_period'] = $test['equipment'];
                $total_resort[$value['portal_id']]['name_last_yearsame_period'] = $value['name'];
                $total_resort[$value['portal_id']]['laundry_same_period'] = $test['laundry'];
                $total_resort[$value['portal_id']]['extra_services_same_period'] = $test['extra_services'];
                $total_resort[$value['portal_id']]['minibar_same_period'] = $test['minibar'];
                $total_resort[$value['portal_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
            }
        }
        //-------------------------------------/cung ki --------------------------------------------------------
        //--------------------------------------Ke Hoach-----------------------------------------------------------------
        /////////////////////////////-----------Phong--------------/////////////////////
        $sql_room_resort = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'ROOM\'
                order by
                    plan_in_month.room_type_id
        ';
        $items_room_resort = DB::fetch_all($sql_room_resort);
        //System::debug($items);
        foreach($items_room_resort as $key=>$value)
        {
            if(isset($total_resort[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_resort[$value['room_type_id']]['total_month_room']))
                {
                    $total_resort[$value['room_type_id']]['total_month_room'] += $value['value'];
                }
                else
                {
                    $total_resort[$value['room_type_id']]['total_month_room'] = $value['value'];
                }  
            } 
        }
        //$a = ReportSynthesisRoomDB::get_reservation_room_revenue(2);
        //System::debug($total_resort);
        ////////////////////////////////-----------------/Phong-----------/////////////////////////////////////
        /////////////////////////////-----------Minibar--------------/////////////////////
        $sql_minibar_resort = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'MINIBAR\'
                order by
                    plan_in_month.room_type_id
        ';
        $items_minibar_resort = DB::fetch_all($sql_minibar);
        //System::debug($items);
        foreach($items_minibar_resort as $key=>$value)
        {
            if(isset($total_resort[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_resort[$value['room_type_id']]['total_month_minibar']))
                {
                    $total_resort[$value['room_type_id']]['total_month_minibar'] += $value['value'];
                }
                else
                {
                    $total_resort[$value['room_type_id']]['total_month_minibar'] = $value['value'];
                }
            }
        }
        //$a = ReportSynthesisRoomDB::get_reservation_room_revenue(2);
        //System::debug($total_resort);
        ////////////////////////////////-----------------/Minibar-----------/////////////////////////////////////
        /////////////////////////////-----------Laundry--------------/////////////////////
        $sql_laundry = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'LAUNDRY\'
                order by
                    plan_in_month.room_type_id
        ';
        $items_laundry_resort = DB::fetch_all($sql_laundry);
        //System::debug($items);
        foreach($items_laundry_resort as $key=>$value)
        {
            if(isset($total_resort[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_resort[$value['room_type_id']]['total_month_laundry']))
                {
                    $total_resort[$value['room_type_id']]['total_month_laundry'] += $value['value'];
                }
                else
                {
                    $total_resort[$value['room_type_id']]['total_month_laundry'] = $value['value'];
                }
            }
        }
        //$a = ReportSynthesisRoomDB::get_reservation_room_revenue(3);
        //System::debug($a);
        ////////////////////////////////-----------------/Laundry-----------/////////////////////////////////////
                /////////////////////////////-----------/extra_services--------------/////////////////////
        $sql_extra_services_resort = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'EXTRA_SERVICES\'
                order by
                    plan_in_month.room_type_id
        ';
        $items_extra_services_resort = DB::fetch_all($sql_extra_services);
        //System::debug($items);
        foreach($items_extra_services_resort as $key=>$value)
        {
            if(isset($total_resort[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_resort[$value['room_type_id']]['total_month_extra_services']))
                {
                    $total_resort[$value['room_type_id']]['total_month_extra_services'] += $value['value'];
                }
                else
                {
                    $total_resort[$value['room_type_id']]['total_month_extra_services'] = $value['value'];
                }
            }
        }
        //$a = ReportSynthesisRoomDB::get_reservation_room_revenue(10);
        System::debug($total_revenue);
        //System::debug($a);
        ////////////////////////////////-----------------/extra_services-----------/////////////////////////////////////
        
        //--------------------------------------/Ke hoach----------------------------------------------------------------
         
         
        //$total_resort = array();
//        $total_resort['resort_room_revenue']=0;
//        $total_resort['resort_laundry']=0;
//        $total_resort['resort_extra_services']=0;
//        $total_resort['resort_minibar']=0;
//        $total_resort['resort_equipment']=0;
//        $total_resort['resort_EI_LO']=0;
//        foreach($total_revenue as $key=>$value)
//        {
//            if(isset($total_resort['resort_room_revenue']))
//            {
//                $total_resort['resort_room_revenue'] += $value['room_revenue'];
//                $total_resort['resort_laundry'] += $value['laundry'];
//                $total_resort['resort_extra_services'] += $value['extra_services'];
//                $total_resort['resort_minibar'] += $value['minibar'];
//                $total_resort['resort_equipment'] += $value['equipment'];
//                $total_resort['resort_EI_LO'] += $value['EI_LO'];
//            }
//            else
//            {
//                $total_resort['resort_room_revenue'] = $value['room_revenue'];
//                $total_resort['resort_laundry'] = $value['laundry'];
//                $total_resort['resort_extra_services'] = $value['extra_services'];
//                $total_resort['resort_minibar'] = $value['minibar'];
//                $total_resort['resort_equipment'] = $value['equipment'];
//                $total_resort['resort_EI_LO'] = $value['EI_LO'];
//            }  
//        }
        //System::debug($total_revenue);
        
                         
        $this->parse_layout('report',$this->map + array
                                (
                                    'total_resort'=>$total_resort , 
                                )
                            );
        
    }
    function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * (5 + $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * ($day_of_week -2));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
 }
 ?>