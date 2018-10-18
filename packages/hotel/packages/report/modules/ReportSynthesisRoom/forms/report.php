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
        $day_cond3_1 = $day.'/'.($m-($m2-$m+1)).'/'.($y);
        $day_cond3_2 = $day2.'/'.($m-1).'/'.($y2);
        //echo $day_cond3_1.'-'.$day_cond3_2;
        //echo $finish1;
        $date_from2 = Date_Time::to_orc_date($finish1);
        $date_to2 = Date_Time::to_orc_date($finish2);
        
        $date_from3 = Date_Time::to_orc_date($day_cond3_1);
        $date_to3 = Date_Time::to_orc_date($day_cond3_2);
        //---------------------------------------------------------------------------------------------------------------
        $month = date('m');
        $cond = $this->cond = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from.'\' and reservation_room.departure_time<=\''.$date_to.'\'';
		$cond .= ' and room.portal_id = \'#default\'' ;
        //----------------------------------------------------------------------------------------------------------------
        $cond2 = $this->cond2 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from2.'\' and reservation_room.departure_time<=\''.$date_to2.'\'';
		$cond2 .= ' and room.portal_id = \'#default\'' ;
        //--------------------------------------------------------------------------------------------------------------------
        $cond3 = $this->cond3 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from3.'\' and reservation_room.departure_time<=\''.$date_to3.'\'';
		$cond3 .= ' and room.portal_id = \'#default\'' ;
        //-----------------------------------------------------------------------------------------------------------------------

        $cond_queen = $this->cond = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from.'\' and reservation_room.departure_time<=\''.$date_to.'\'';
		$cond_queen .= ' and room.portal_id = \'#huequeen1\'' ;
        //----------------------------------------------------------------------------------------------------------------
        $cond2_queen = $this->cond2 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from2.'\' and reservation_room.departure_time<=\''.$date_to2.'\'';
		$cond2_queen .= ' and room.portal_id = \'#huequeen1\' ';
        //--------------------------------------------------------------------------------------------------------------------
        $cond3_queen = $this->cond3 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from3.'\' and reservation_room.departure_time<=\''.$date_to3.'\'';
		$cond3_queen .=' and room.portal_id = \'#huequeen1\' ' ;
        //-----------------------------------------------------------------------------------------------------------------------
        $cond_allba = $this->cond = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from.'\' and reservation_room.departure_time<=\''.$date_to.'\'';
		$cond_allba .= ' and room.portal_id = \'#huequeen2\' ';
        //----------------------------------------------------------------------------------------------------------------
        $cond2_allba = $this->cond2 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from2.'\' and reservation_room.departure_time<=\''.$date_to2.'\'';
		$cond2_allba .= ' and room.portal_id = \'#huequeen2\'' ;
        //--------------------------------------------------------------------------------------------------------------------
        $cond3_allba = $this->cond3 = ' 1 >0 '
				.' and reservation_room.departure_time>=\''.$date_from3.'\' and reservation_room.departure_time<=\''.$date_to3.'\'';
		$cond3_allba .= ' and room.portal_id = \'#huequeen2\'' ;
        //-----------------------------------------------------------------------------------------------------------------------
        $sql = ('
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
                            order by ROOM_TYPE_ID
        '); 
        
        $test2=DB::fetch_all($sql);
        $total_revenue = array();
        set_time_limit(0);
        foreach($test2 as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            //$total_revenue[$value['room_type_id']]['equipment_last_year'] = 0;
//            $total_revenue[$value['room_type_id']]['room_revenue_last_year'] = 0;
//            $total_revenue[$value['room_type_id']]['name_last_year'] = 0;
//            $total_revenue[$value['room_type_id']]['minibar_last_year'] = 0;
//            $total_revenue[$value['room_type_id']]['laundry_last_year'] = 0;
//            $total_revenue[$value['room_type_id']]['extra_services_last_year'] = 0;
            
            $total_revenue[$value['room_type_id']]['equipment_same_period'] = 0;
            $total_revenue[$value['room_type_id']]['room_revenue_same_period'] = 0;
            $total_revenue[$value['room_type_id']]['name_same_period'] = 0;
            $total_revenue[$value['room_type_id']]['minibar_same_period'] = 0;
            $total_revenue[$value['room_type_id']]['laundry_same_period'] = 0;
            $total_revenue[$value['room_type_id']]['extra_services_same_period'] = 0;
            $total_revenue[$value['room_type_id']]['EI_LO_same_period'] = 0;
            
            $total_revenue[$value['room_type_id']]['total_month_room'] = 0;
            $total_revenue[$value['room_type_id']]['total_month_minibar'] = 0;
            $total_revenue[$value['room_type_id']]['total_month_laundry'] = 0;
            $total_revenue[$value['room_type_id']]['total_month_extra_services'] = 0;
            $total_revenue[$value['room_type_id']]['EI_LO_last_year'] = 0;
            if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
            {
                $total_revenue[$value['room_type_id']]['room_revenue'] += $test['room'];
                $total_revenue[$value['room_type_id']]['extra_services'] += $test['extra_services'];
                $total_revenue[$value['room_type_id']]['minibar'] += $test['minibar'];
                $total_revenue[$value['room_type_id']]['laundry'] += $test['laundry'];
                $total_revenue[$value['room_type_id']]['name'] = $value['name'];
                $total_revenue[$value['room_type_id']]['equipment'] += $test['equipment'];
                $total_revenue[$value['room_type_id']]['EI_LO'] += $test['extra_services_EI_LO'];        
            }
            else 
            {
                $total_revenue[$value['room_type_id']]['room_revenue'] = $test['room'];
                $total_revenue[$value['room_type_id']]['name'] = $value['name'];
                $total_revenue[$value['room_type_id']]['laundry'] = $test['laundry'];
                $total_revenue[$value['room_type_id']]['extra_services'] = $test['extra_services'];
                $total_revenue[$value['room_type_id']]['minibar'] = $test['minibar'];
                $total_revenue[$value['room_type_id']]['equipment'] = $test['equipment'];
                $total_revenue[$value['room_type_id']]['EI_LO'] = $test['extra_services_EI_LO']; 
            } 
        }
        //System::debug($total_revenue);
        //exit();
        //-----------------------------------------------cung ki nam ngoai----------------------------------------------
       // $sql2 = '
//                    select
//                        reservation_room.id
//                        ,room.ROOM_TYPE_ID
//                        ,reservation_room.time_in
//                        ,room_type.name
//                    from
//                        reservation_room
//                        inner join room on room.id = reservation_room.room_id
//                        inner join room_type on room_type.id = room.room_type_id
//                    where 
//                        reservation_room.time_in != 0
//                        and room_id != 0
//                        and'.$cond2.'
//                        and reservation_room.status = \'CHECKIN\' 
//                        and reservation_room.status = \'CHECKOUT\'
//                    order by ROOM_TYPE_ID
//        ';
//        $test3=DB::fetch_all($sql2);
//        foreach($test3 as $key=>$value)
//        {
//            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
//            if(isset($total_revenue[$value['room_type_id']]))
//            {
//                if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
//                {
//                    $total_revenue[$value['room_type_id']]['room_revenue_last_year'] += $test['room'];
//                    $total_revenue[$value['room_type_id']]['extra_services_last_year'] += $test['extra_services'];
//                    $total_revenue[$value['room_type_id']]['minibar_last_year'] += $test['minibar'];
//                    $total_revenue[$value['room_type_id']]['laundry_last_year'] += $test['laundry'];
//                    $total_revenue[$value['room_type_id']]['equipment_last_year'] += $test['equipment'];
//                    $total_revenue[$value['room_type_id']]['name_last_year'] = $value['name'];
//                    $total_revenue[$value['room_type_id']]['EI_LO_last_year'] += $test['extra_services_EI_LO'];        
//                }
//                else 
//                {
//                    $total_revenue[$value['room_type_id']]['room_revenue_last_year'] = $test['room'];
//                    $total_revenue[$value['room_type_id']]['equipment_last_year'] = $test['equipment'];
//                    $total_revenue[$value['room_type_id']]['name_last_year'] = $value['name'];
//                    $total_revenue[$value['room_type_id']]['laundry_last_year'] = $test['laundry'];
//                    $total_revenue[$value['room_type_id']]['extra_services_last_year'] = $test['extra_services'];
//                    $total_revenue[$value['room_type_id']]['minibar_last_year'] = $test['minibar'];
//                    $total_revenue[$value['room_type_id']]['EI_LO_last_year'] = $test['extra_services_EI_LO'];
//                }
//            }
//        }
        //-------------------------------------/cung ki nam ngoai--------------------------------------------------------
        //-------------------------------------cung ki--------------------------------------------------------
        $sql3 = '
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
                                and'.$cond3.'
                                and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                            order by ROOM_TYPE_ID
        ';
        $test4=DB::fetch_all($sql3);
        //System::debug($test4);
        //exit();
        set_time_limit(0);
        foreach($test4 as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            if(isset($total_revenue[$value['room_type_id']]))
            {
                if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
                {
                    $total_revenue[$value['room_type_id']]['room_revenue_same_period'] += $test['room'];
                    $total_revenue[$value['room_type_id']]['extra_services_same_period'] += $test['extra_services'];
                    $total_revenue[$value['room_type_id']]['minibar_same_period'] += $test['minibar'];
                    $total_revenue[$value['room_type_id']]['laundry_same_period'] += $test['laundry'];
                    $total_revenue[$value['room_type_id']]['equipment_same_period'] += $test['equipment'];
                    $total_revenue[$value['room_type_id']]['EI_LO_same_period'] += $test['extra_services_EI_LO'];        
                }
                else 
                {
                    $total_revenue[$value['room_type_id']]['room_revenue_same_period'] = $test['room'];
                    $total_revenue[$value['room_type_id']]['equipment_same_period'] = $test['equipment'];
                    $total_revenue[$value['room_type_id']]['laundry_same_period'] = $test['laundry'];
                    $total_revenue[$value['room_type_id']]['extra_services_same_period'] = $test['extra_services'];
                    $total_revenue[$value['room_type_id']]['minibar_same_period'] = $test['minibar'];
                    $total_revenue[$value['room_type_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
                }
            }
            else
            {
                
                $total_revenue[$value['room_type_id']]['room_revenue_same_period'] = $test['room'];
                $total_revenue[$value['room_type_id']]['equipment_same_period'] = $test['equipment'];
                $total_revenue[$value['room_type_id']]['laundry_same_period'] = $test['laundry'];
                $total_revenue[$value['room_type_id']]['extra_services_same_period'] = $test['extra_services'];
                $total_revenue[$value['room_type_id']]['minibar_same_period'] = $test['minibar'];
                $total_revenue[$value['room_type_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
                
               // $total_revenue[$value['room_type_id']]['equipment_last_year'] = 0;
//                $total_revenue[$value['room_type_id']]['room_revenue_last_year'] = 0;
//                $total_revenue[$value['room_type_id']]['name_last_year'] = 0;
//                $total_revenue[$value['room_type_id']]['minibar_last_year'] = 0;
//                $total_revenue[$value['room_type_id']]['laundry_last_year'] = 0;
//                $total_revenue[$value['room_type_id']]['extra_services_last_year'] = 0;
                
                $total_revenue[$value['room_type_id']]['total_month_room'] = 0;
                $total_revenue[$value['room_type_id']]['total_month_minibar'] = 0;
                $total_revenue[$value['room_type_id']]['total_month_laundry'] = 0;
                $total_revenue[$value['room_type_id']]['total_month_extra_services'] = 0;
               // $total_revenue[$value['room_type_id']]['EI_LO_last_year'] = 0;
                
                $total_revenue[$value['room_type_id']]['equipment'] = 0;
                $total_revenue[$value['room_type_id']]['room_revenue'] = 0;
                $total_revenue[$value['room_type_id']]['name'] = $value['name'];
                $total_revenue[$value['room_type_id']]['minibar'] = 0;
                $total_revenue[$value['room_type_id']]['laundry'] = 0;
                $total_revenue[$value['room_type_id']]['extra_services'] = 0;
                $total_revenue[$value['room_type_id']]['EI_LO'] = 0;
                
            }
        }
        
        //-------------------------------------/cung ki --------------------------------------------------------
        //--------------------------------------Ke Hoach-----------------------------------------------------------------
        /////////////////////////////-----------Phong--------------/////////////////////
        $sql_room = '
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
        $items_room = DB::fetch_all($sql_room);
        foreach($items_room as $key=>$value)
        {
            if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_revenue[$value['room_type_id']]['total_month_room']))
                {
                    $total_revenue[$value['room_type_id']]['total_month_room'] += $value['value'];
                }
                else
                {
                    $total_revenue[$value['room_type_id']]['total_month_room'] = $value['value'];
                    
                }  
            } 
        }
        ////////////////////////////////-----------------/Phong-----------/////////////////////////////////////
        /////////////////////////////-----------Minibar--------------/////////////////////
        $sql_minibar = '
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
        $items_minibar = DB::fetch_all($sql_minibar);
        foreach($items_minibar as $key=>$value)
        {
            if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_revenue[$value['room_type_id']]['total_month_minibar']))
                {
                    $total_revenue[$value['room_type_id']]['total_month_minibar'] += $value['value'];
                }
                else
                {
                    $total_revenue[$value['room_type_id']]['total_month_minibar'] = $value['value'];
                }
            }
        }
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
        $items_laundry = DB::fetch_all($sql_laundry);
        foreach($items_laundry as $key=>$value)
        {
            if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_revenue[$value['room_type_id']]['total_month_laundry']))
                {
                    $total_revenue[$value['room_type_id']]['total_month_laundry'] += $value['value'];
                }
                else
                {
                    $total_revenue[$value['room_type_id']]['total_month_laundry'] = $value['value'];
                }
            }
        }
        ////////////////////////////////-----------------/Laundry-----------/////////////////////////////////////
                /////////////////////////////-----------/extra_services--------------/////////////////////
        $sql_extra_services = '
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
        $items_extra_services = DB::fetch_all($sql_extra_services);
        foreach($items_extra_services as $key=>$value)
        {
            if(isset($total_revenue[$value['room_type_id']]['room_revenue']))
            {
                if(isset($total_revenue[$value['room_type_id']]['total_month_extra_services']))
                {
                    $total_revenue[$value['room_type_id']]['total_month_extra_services'] += $value['value'];
                }
                else
                {
                    $total_revenue[$value['room_type_id']]['total_month_extra_services'] = $value['value'];
                }
            }
            
        }
        ////////////////////////////////-----------------/extra_services-----------/////////////////////////////////////
        
        //--------------------------------------/Ke hoach----------------------------------------------------------------
//--------------------------------------------------------RESORT___________________________________________________________________         
        $total_resort=array();
        foreach($total_revenue as $key=>$value)
        {
            //$total_resort['room_revenue']=0;
//            $total_resort['laundry'] = 0;
//            $total_resort['extra_services'] = 0;
//            $total_resort['minibar'] = 0;
//            $total_resort['equipment'] = 0;
//            $total_resort['EI_LO'] = 0;
//            $total_resort['equipment_same_period'] = 0;
//            $total_resort['room_revenue_same_period'] = 0;
//            $total_resort['minibar_same_period'] = 0;
//            $total_resort['laundry_same_period'] = 0;
//            $total_resort['extra_services_same_period'] = 0;
//            $total_resort['EI_LO_same_period'] = 0;
//            $total_resort['total_month_room'] = 0;
//            $total_resort['total_month_minibar'] =0;
//            $total_resort['total_month_laundry'] =0;
//            $total_resort['total_month_extra_services'] = 0;
            if(isset($total_resort['room_revenue']))
            {
                $total_resort['room_revenue'] += $value['room_revenue'];
                $total_resort['laundry'] += $value['laundry'];
                $total_resort['extra_services'] += $value['extra_services'];
                $total_resort['minibar'] += $value['minibar'];
                $total_resort['equipment'] += $value['equipment'];
                $total_resort['EI_LO'] += $value['EI_LO'];
                $total_resort['equipment_same_period'] += $value['equipment_same_period'];
                $total_resort['room_revenue_same_period'] += $value['room_revenue_same_period'];
                $total_resort['minibar_same_period'] += $value['minibar_same_period'];
                $total_resort['laundry_same_period'] += $value['laundry_same_period'];
                $total_resort['extra_services_same_period'] += $value['extra_services_same_period'];
                $total_resort['EI_LO_same_period'] += $value['EI_LO_same_period'];
                $total_resort['total_month_room'] += $value['total_month_room'];
                $total_resort['total_month_minibar'] += $value['total_month_minibar'];
                $total_resort['total_month_laundry'] += $value['total_month_laundry'];
                $total_resort['total_month_extra_services'] += $value['total_month_extra_services'];
            }
            else
            {
                $total_resort['room_revenue'] = $value['room_revenue'];
                $total_resort['laundry'] = $value['laundry'];
                $total_resort['extra_services'] = $value['extra_services'];
                $total_resort['minibar'] = $value['minibar'];
                $total_resort['equipment'] = $value['equipment'];
                $total_resort['EI_LO'] = $value['EI_LO'];
                $total_resort['equipment_same_period'] = $value['equipment_same_period'];
                $total_resort['room_revenue_same_period'] = $value['room_revenue_same_period'];
                $total_resort['minibar_same_period'] = $value['minibar_same_period'];
                $total_resort['laundry_same_period'] = $value['laundry_same_period'];
                $total_resort['extra_services_same_period'] = $value['extra_services_same_period'];
                $total_resort['EI_LO_same_period'] = $value['EI_LO_same_period'];
                $total_resort['total_month_room'] = $value['total_month_room'];
                $total_resort['total_month_minibar'] = $value['total_month_minibar'];
                $total_resort['total_month_laundry'] = $value['total_month_laundry'];
                $total_resort['total_month_extra_services'] = $value['total_month_extra_services'];
            }
        }
        
        //System::debug($total_revenue);
        //System::debug($total_resort);
        //exit(); 
        
//--------------------------------------------------------/RESORT___________________________________________________________________
//--------------------------------------------------------Allba_Queen_Hotel___________________________________________________________________
        $sql_portal2 = ('
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
                                and'.$cond_queen.'
                                and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                            order by portal_id
        '); 
        
        $test52=DB::fetch_all($sql_portal2);
        $total_queen = array();
        set_time_limit(0);
        foreach($test52 as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
//
//            $total_queen[$value['portal_id']]['equipment_last_year'] = 0;
//            $total_queen[$value['portal_id']]['room_revenue_last_year'] = 0;
//            $total_queen[$value['portal_id']]['name_last_year'] = 0;
//            $total_queen[$value['portal_id']]['minibar_last_year'] = 0;
//            $total_queen[$value['portal_id']]['laundry_last_year'] = 0;
//            $total_queen[$value['portal_id']]['extra_services_last_year'] = 0;
            
            $total_queen[$value['portal_id']]['equipment_same_period'] = 0;
            $total_queen[$value['portal_id']]['room_revenue_same_period'] = 0;
            $total_queen[$value['portal_id']]['name_same_period'] = 0;
            $total_queen[$value['portal_id']]['minibar_same_period'] = 0;
            $total_queen[$value['portal_id']]['laundry_same_period'] = 0;
            $total_queen[$value['portal_id']]['extra_services_same_period'] = 0;
            $total_queen[$value['portal_id']]['EI_LO_same_period'] = 0;
            
            $total_queen[$value['portal_id']]['total_month_room'] = 0;
            $total_queen[$value['portal_id']]['total_month_minibar'] = 0;
            $total_queen[$value['portal_id']]['total_month_laundry'] = 0;
            $total_queen[$value['portal_id']]['total_month_extra_services'] = 0;
            $total_queen[$value['portal_id']]['EI_LO_last_year'] = 0;
            
            $total_queen[$value['portal_id']]['room_revenue'] = 0;
            $total_queen[$value['portal_id']]['name'] = 0;
            $total_queen[$value['portal_id']]['laundry'] = 0;
            $total_queen[$value['portal_id']]['extra_services'] = 0;
            $total_queen[$value['portal_id']]['minibar'] = 0;
            $total_queen[$value['portal_id']]['equipment'] = 0;
            $total_queen[$value['portal_id']]['EI_LO'] = 0;
            if(isset($total_queen[$value['portal_id']]['room_revenue']))
            {
                $total_queen[$value['portal_id']]['room_revenue'] += $test['room'];
                $total_queen[$value['portal_id']]['extra_services'] += $test['extra_services'];
                $total_queen[$value['portal_id']]['minibar'] += $test['minibar'];
                $total_queen[$value['portal_id']]['laundry'] += $test['laundry'];
                $total_queen[$value['portal_id']]['name'] = $value['name'];
                $total_queen[$value['portal_id']]['equipment'] += $test['equipment'];
                $total_queen[$value['portal_id']]['EI_LO'] += $test['extra_services_EI_LO'];        
            }
            else 
            {
                $total_queen[$value['portal_id']]['room_revenue'] = $test['room'];
                $total_queen[$value['portal_id']]['name'] = $value['name'];
                $total_queen[$value['portal_id']]['laundry'] = $test['laundry'];
                $total_queen[$value['portal_id']]['extra_services'] = $test['extra_services'];
                $total_queen[$value['portal_id']]['minibar'] = $test['minibar'];
                $total_queen[$value['portal_id']]['equipment'] = $test['equipment'];
                $total_queen[$value['portal_id']]['EI_LO'] = $test['extra_services_EI_LO']; 
            } 
        }
        
        //-----------------------------------------------cung ki nam ngoai----------------------------------------------
       // $sql2_queen = '
//                    select
//                        reservation_room.id
//                        ,room.ROOM_TYPE_ID
//                        ,reservation_room.time_in
//                        ,room_type.name
//                        ,room.portal_id
//                    from
//                        reservation_room
//                        inner join room on room.id = reservation_room.room_id
//                        inner join room_type on room_type.id = room.room_type_id
//                    where 
//                        reservation_room.time_in != 0
//                        and room_id != 0
//                        and'.$cond2_queen.'
//                        and reservation_room.status = \'CHECKIN\' 
//                        and reservation_room.status = \'CHECKOUT\'
//                    order by portal_id
//        ';
//        $test3_queen=DB::fetch_all($sql2_queen);
//        foreach($test3_queen as $key=>$value)
//        {
//            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
//            if(isset($total_queen[$value['portal_id']]))
//            {
//                if(isset($total_queen[$value['portal_id']]['room_revenue_last_year']))
//                {
//                    $total_queen[$value['portal_id']]['room_revenue_last_year'] += $test['room'];
//                    $total_queen[$value['portal_id']]['extra_services_last_year'] += $test['extra_services'];
//                    $total_queen[$value['portal_id']]['minibar_last_year'] += $test['minibar'];
//                    $total_queen[$value['portal_id']]['laundry_last_year'] += $test['laundry'];
//                    $total_queen[$value['portal_id']]['equipment_last_year'] += $test['equipment'];
//                    $total_queen[$value['portal_id']]['name_last_year'] = $value['name'];
//                    $total_queen[$value['portal_id']]['EI_LO_last_year'] += $test['extra_services_EI_LO'];        
//                }
//                else 
//                {
//                    $total_queen[$value['portal_id']]['room_revenue_last_year'] = $test['room'];
//                    $total_queen[$value['portal_id']]['equipment_last_year'] = $test['equipment'];
//                    $total_queen[$value['portal_id']]['name_last_year'] = $value['name'];
//                    $total_queen[$value['portal_id']]['laundry_last_year'] = $test['laundry'];
//                    $total_queen[$value['portal_id']]['extra_services_last_year'] = $test['extra_services'];
//                    $total_queen[$value['portal_id']]['minibar_last_year'] = $test['minibar'];
//                    $total_queen[$value['portal_id']]['EI_LO_last_year'] = $test['extra_services_EI_LO'];
//                }
//            }
//        }
        //-------------------------------------/cung ki nam ngoai--------------------------------------------------------
        //-------------------------------------cung ki--------------------------------------------------------
        $sql3_queen = '
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
                                and'.$cond3_queen.'
                                and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                            order by portal_id
        ';
        $test4_queen=DB::fetch_all($sql3_queen);
        set_time_limit(0);
        foreach($test4_queen as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            if(isset($total_queen[$value['portal_id']]['room_revenue']))
            {
                if(isset($total_queen[$value['portal_id']]['room_revenue']))
                {
                    $total_queen[$value['portal_id']]['room_revenue_same_period'] += $test['room'];
                    $total_queen[$value['portal_id']]['extra_services_same_period'] += $test['extra_services'];
                    $total_queen[$value['portal_id']]['minibar_same_period'] += $test['minibar'];
                    $total_queen[$value['portal_id']]['laundry_same_period'] += $test['laundry'];
                    $total_queen[$value['portal_id']]['equipment_same_period'] += $test['equipment'];
                    $total_queen[$value['portal_id']]['name_same_period'] = $value['name'];
                    $total_queen[$value['portal_id']]['EI_LO_same_period'] += $test['extra_services_EI_LO'];        
                }
                else 
                {
                    $total_queen[$value['portal_id']]['room_revenue_same_period'] = $test['room'];
                    $total_queen[$value['portal_id']]['equipment_same_period'] = $test['equipment'];
                    $total_queen[$value['portal_id']]['name_last_yearsame_period'] = $value['name'];
                    $total_queen[$value['portal_id']]['laundry_same_period'] = $test['laundry'];
                    $total_queen[$value['portal_id']]['extra_services_same_period'] = $test['extra_services'];
                    $total_queen[$value['portal_id']]['minibar_same_period'] = $test['minibar'];
                    $total_queen[$value['portal_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
                }
            }
            else
            {
                    $total_queen[$value['portal_id']]['room_revenue_same_period'] = $test['room'];
                    $total_queen[$value['portal_id']]['equipment_same_period'] = $test['equipment'];
                    $total_queen[$value['portal_id']]['name_last_yearsame_period'] = $value['name'];
                    $total_queen[$value['portal_id']]['laundry_same_period'] = $test['laundry'];
                    $total_queen[$value['portal_id']]['extra_services_same_period'] = $test['extra_services'];
                    $total_queen[$value['portal_id']]['minibar_same_period'] = $test['minibar'];
                    $total_queen[$value['portal_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
                    
                   // $total_queen[$value['portal_id']]['equipment_last_year'] = 0;
//                    $total_queen[$value['portal_id']]['room_revenue_last_year'] = 0;
//                    $total_queen[$value['portal_id']]['name_last_year'] = 0;
//                    $total_queen[$value['portal_id']]['minibar_last_year'] = 0;
//                    $total_queen[$value['portal_id']]['laundry_last_year'] = 0;
//                    $total_queen[$value['portal_id']]['extra_services_last_year'] = 0;
                    
                    $total_queen[$value['portal_id']]['total_month_room'] = 0;
                    $total_queen[$value['portal_id']]['total_month_minibar'] = 0;
                    $total_queen[$value['portal_id']]['total_month_laundry'] = 0;
                    $total_queen[$value['portal_id']]['total_month_extra_services'] = 0;
                    $total_queen[$value['portal_id']]['EI_LO_last_year'] = 0;
                    
                    $total_queen[$value['portal_id']]['room_revenue'] = 0;
                    $total_queen[$value['portal_id']]['name'] = $value['name'];
                    $total_queen[$value['portal_id']]['laundry'] = 0;
                    $total_queen[$value['portal_id']]['extra_services'] = 0;
                    $total_queen[$value['portal_id']]['minibar'] = 0;
                    $total_queen[$value['portal_id']]['equipment'] = 0;
                    $total_queen[$value['portal_id']]['EI_LO'] = 0;
            }
        }
        //-------------------------------------/cung ki --------------------------------------------------------
        //--------------------------------------Ke Hoach-----------------------------------------------------------------
        /////////////////////////////-----------Phong--------------/////////////////////
        $sql_room_queen = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'ROOM\'
                    and plan_in_month.portal_id = \'#huequeen1\'
                order by
                    plan_in_month.portal_id
        ';
        $items_room_queen = DB::fetch_all($sql_room_queen);
        set_time_limit(0);
        foreach($items_room_queen as $key=>$value)
        {
            if(isset($total_queen[$value['portal_id']]))
            {
                if(isset($total_queen[$value['portal_id']]['total_month_room']))
                {
                    $total_queen[$value['portal_id']]['total_month_room'] += $value['value'];
                }
                else
                {
                    $total_queen[$value['portal_id']]['total_month_room'] = $value['value'];
                }  
            }
            else
            {
                $total_queen[$value['portal_id']]['total_month_room'] = 0;
            } 
        }
        ////////////////////////////////-----------------/Phong-----------/////////////////////////////////////
        /////////////////////////////-----------Minibar--------------/////////////////////
        $sql_minibar_queen = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'MINIBAR\'
                    and plan_in_month.portal_id = \'#huequeen1\'
                order by
                    plan_in_month.portal_id
        ';
        $items_minibar_queen = DB::fetch_all($sql_minibar_queen);
        foreach($items_minibar_queen as $key=>$value)
        {
            if(isset($total_queen[$value['portal_id']]))
            {
                if(isset($total_queen[$value['portal_id']]['total_month_minibar']))
                {
                    $total_queen[$value['portal_id']]['total_month_minibar'] += $value['value'];
                }
                else
                {
                    $total_queen[$value['portal_id']]['total_month_minibar'] = $value['value'];
                }
            }
            else
            {
                $total_queen[$value['portal_id']]['total_month_minibar'] = 0;
            }
        }
        ////////////////////////////////-----------------/Minibar-----------/////////////////////////////////////
        /////////////////////////////-----------Laundry--------------/////////////////////
        $sql_laundry_queen = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'LAUNDRY\'
                    and plan_in_month.portal_id = \'#huequeen1\'
                order by
                    plan_in_month.portal_id
        ';
        $items_laundry_queen = DB::fetch_all($sql_laundry_queen);
        foreach($items_laundry_queen as $key=>$value)
        {
            if(isset($total_queen[$value['portal_id']]))
            {
                if(isset($total_queen[$value['portal_id']]['total_month_laundry']))
                {
                    $total_queen[$value['portal_id']]['total_month_laundry'] += $value['value'];
                }
                else
                {
                    $total_queen[$value['portal_id']]['total_month_laundry'] = $value['value'];
                }
            }
            else
                {
                    $total_queen[$value['portal_id']]['total_month_laundry'] = 0;
                }
        }
        ////////////////////////////////-----------------/Laundry-----------/////////////////////////////////////
                /////////////////////////////-----------/extra_services--------------/////////////////////
        $sql_extra_services_queen = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'EXTRA_SERVICES\'
                    and plan_in_month.portal_id = \'#huequeen1\'
                order by
                    plan_in_month.portal_id
        ';
        $items_extra_services_queen = DB::fetch_all($sql_extra_services_queen);
        foreach($items_extra_services_queen as $key=>$value)
        {
            if(isset($total_queen[$value['portal_id']]))
            {
                if(isset($total_queen[$value['portal_id']]['total_month_extra_services']))
                {
                    $total_queen[$value['portal_id']]['total_month_extra_services'] += $value['value'];
                }
                else
                {
                    $total_queen[$value['portal_id']]['total_month_extra_services'] = $value['value'];
                }
            }
            else
                {
                    $total_queen[$value['portal_id']]['total_month_extra_services'] = 0;
                }
        }
        ///System::debug($total_queen);
//-------------------------------------------------------/Allba_Queen_Hotel___________________________________________________________________  
//--------------------------------------------------------Allba_Hotel___________________________________________________________________
        $sql_portal3 = ('
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
                                and'.$cond_allba.'
                                and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                            order by portal_id
        '); 
        
        $test53=DB::fetch_all($sql_portal3);
        //System::debug($sql_portal3);
        $total_allba = array();
        set_time_limit(0);
        foreach($test53 as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
//            $total_allba[$value['portal_id']]['equipment_last_year'] = 0;
//            $total_allba[$value['portal_id']]['room_revenue_last_year'] = 0;
//            $total_allba[$value['portal_id']]['name_last_year'] = 0;
//            $total_allba[$value['portal_id']]['minibar_last_year'] = 0;
//            $total_allba[$value['portal_id']]['laundry_last_year'] = 0;
//            $total_allba[$value['portal_id']]['extra_services_last_year'] = 0;
            
            $total_allba[$value['portal_id']]['equipment_same_period'] = 0;
            $total_allba[$value['portal_id']]['room_revenue_same_period'] = 0;
            $total_allba[$value['portal_id']]['name_same_period'] = 0;
            $total_allba[$value['portal_id']]['minibar_same_period'] = 0;
            $total_allba[$value['portal_id']]['laundry_same_period'] = 0;
            $total_allba[$value['portal_id']]['extra_services_same_period'] = 0;
            $total_allba[$value['portal_id']]['EI_LO_same_period'] = 0;
            
            $total_allba[$value['portal_id']]['total_month_room'] = 0;
            $total_allba[$value['portal_id']]['total_month_minibar'] = 0;
            $total_allba[$value['portal_id']]['total_month_laundry'] = 0;
            $total_allba[$value['portal_id']]['total_month_extra_services'] = 0;
            $total_allba[$value['portal_id']]['EI_LO_last_year'] = 0;
            if(isset($total_allba[$value['portal_id']]['room_revenue']))
            {
                $total_allba[$value['portal_id']]['room_revenue'] += $test['room'];
                $total_allba[$value['portal_id']]['extra_services'] += $test['extra_services'];
                $total_allba[$value['portal_id']]['minibar'] += $test['minibar'];
                $total_allba[$value['portal_id']]['laundry'] += $test['laundry'];
                $total_allba[$value['portal_id']]['name'] = $value['name'];
                $total_allba[$value['portal_id']]['equipment'] += $test['equipment'];
                $total_allba[$value['portal_id']]['EI_LO'] += $test['extra_services_EI_LO'];        
            }
            else 
            {
                $total_allba[$value['portal_id']]['room_revenue'] = $test['room'];
                $total_allba[$value['portal_id']]['name'] = $value['name'];
                $total_allba[$value['portal_id']]['laundry'] = $test['laundry'];
                $total_allba[$value['portal_id']]['extra_services'] = $test['extra_services'];
                $total_allba[$value['portal_id']]['minibar'] = $test['minibar'];
                $total_allba[$value['portal_id']]['equipment'] = $test['equipment'];
                $total_allba[$value['portal_id']]['EI_LO'] = $test['extra_services_EI_LO']; 
            } 
        }
        
        //-----------------------------------------------cung ki nam ngoai----------------------------------------------
        //$sql2_allba = '
//                    select
//                        reservation_room.id
//                        ,room.ROOM_TYPE_ID
//                        ,reservation_room.time_in
//                        ,room_type.name
//                        ,room.portal_id
//                    from
//                        reservation_room
//                        inner join room on room.id = reservation_room.room_id
//                        inner join room_type on room_type.id = room.room_type_id
//                    where 
//                        reservation_room.time_in != 0
//                        and room_id != 0
//                        and'.$cond2_allba.'
//                        and reservation_room.status = \'CHECKIN\' 
//                        and reservation_room.status = \'CHECKOUT\'
//                    order by portal_id
//        ';
//        $test3_allba=DB::fetch_all($sql2_allba);
//        foreach($test3_allba as $key=>$value)
//        {
//            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
//            if(isset($total_allba[$value['portal_id']]['room_revenue_last_year']))
//            {
//                $total_allba[$value['portal_id']]['room_revenue_last_year'] += $test['room'];
//                $total_allba[$value['portal_id']]['extra_services_last_year'] += $test['extra_services'];
//                $total_allba[$value['portal_id']]['minibar_last_year'] += $test['minibar'];
//                $total_allba[$value['portal_id']]['laundry_last_year'] += $test['laundry'];
//                $total_allba[$value['portal_id']]['equipment_last_year'] += $test['equipment'];
//                $total_allba[$value['portal_id']]['name_last_year'] = $value['name'];
//                $total_allba[$value['portal_id']]['EI_LO_last_year'] += $test['extra_services_EI_LO'];        
//            }
//            else 
//            {
//                $total_allba[$value['portal_id']]['room_revenue_last_year'] = $test['room'];
//                $total_allba[$value['portal_id']]['equipment_last_year'] = $test['equipment'];
//                $total_allba[$value['portal_id']]['name_last_year'] = $value['name'];
//                $total_allba[$value['portal_id']]['laundry_last_year'] = $test['laundry'];
//                $total_allba[$value['portal_id']]['extra_services_last_year'] = $test['extra_services'];
//                $total_allba[$value['portal_id']]['minibar_last_year'] = $test['minibar'];
//                $total_allba[$value['portal_id']]['EI_LO_last_year'] = $test['extra_services_EI_LO'];
//            }
//        }
        //-------------------------------------/cung ki nam ngoai--------------------------------------------------------
        //-------------------------------------cung ki--------------------------------------------------------
        $sql3_allba = '
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
                                and'.$cond3_allba.'
                                and (reservation_room.status = \'CHECKIN\' or reservation_room.status = \'CHECKOUT\')
                            order by portal_id
        ';
        $test4_allba=DB::fetch_all($sql3_allba);
        //System::debug($sql3_allba);
        set_time_limit(0);
        foreach($test4_allba as $key=>$value)
        {
            $test = ReportSynthesisRoomDB::get_reservation_room_revenue($key);
            if(isset($total_allba[$value['portal_id']]))
            {
                if(isset($total_allba[$value['portal_id']]['room_revenue']))
                {
                    $total_allba[$value['portal_id']]['room_revenue_same_period'] += $test['room'];
                    $total_allba[$value['portal_id']]['extra_services_same_period'] += $test['extra_services'];
                    $total_allba[$value['portal_id']]['minibar_same_period'] += $test['minibar'];
                    $total_allba[$value['portal_id']]['laundry_same_period'] += $test['laundry'];
                    $total_allba[$value['portal_id']]['equipment_same_period'] += $test['equipment'];
                    $total_allba[$value['portal_id']]['name_same_period'] = $value['name'];
                    $total_allba[$value['portal_id']]['EI_LO_same_period'] += $test['extra_services_EI_LO'];        
                }
                else 
                {
                    $total_allba[$value['portal_id']]['room_revenue_same_period'] = $test['room'];
                    $total_allba[$value['portal_id']]['equipment_same_period'] = $test['equipment'];
                    $total_allba[$value['portal_id']]['name_last_yearsame_period'] = $value['name'];
                    $total_allba[$value['portal_id']]['laundry_same_period'] = $test['laundry'];
                    $total_allba[$value['portal_id']]['extra_services_same_period'] = $test['extra_services'];
                    $total_allba[$value['portal_id']]['minibar_same_period'] = $test['minibar'];
                    $total_allba[$value['portal_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
                }
            }
            else
            {
                    $total_allba[$value['portal_id']]['room_revenue_same_period'] = $test['room'];
                    $total_allba[$value['portal_id']]['equipment_same_period'] = $test['equipment'];
                    $total_allba[$value['portal_id']]['name_last_yearsame_period'] = $value['name'];
                    $total_allba[$value['portal_id']]['laundry_same_period'] = $test['laundry'];
                    $total_allba[$value['portal_id']]['extra_services_same_period'] = $test['extra_services'];
                    $total_allba[$value['portal_id']]['minibar_same_period'] = $test['minibar'];
                    $total_allba[$value['portal_id']]['EI_LO_same_period'] = $test['extra_services_EI_LO'];
                    $total_allba[$value['portal_id']]['equipment_last_year'] = 0;
//            
//                    $total_allba[$value['portal_id']]['room_revenue_last_year'] = 0;
//                    $total_allba[$value['portal_id']]['name_last_year'] = 0;
//                    $total_allba[$value['portal_id']]['minibar_last_year'] = 0;
//                    $total_allba[$value['portal_id']]['laundry_last_year'] = 0;
//                    $total_allba[$value['portal_id']]['extra_services_last_year'] = 0;
                    
                    $total_allba[$value['portal_id']]['total_month_room'] = 0;
                    $total_allba[$value['portal_id']]['total_month_minibar'] = 0;
                    $total_allba[$value['portal_id']]['total_month_laundry'] = 0;
                    $total_allba[$value['portal_id']]['total_month_extra_services'] = 0;
                    $total_allba[$value['portal_id']]['EI_LO_last_year'] = 0;
                    
                    $total_allba[$value['portal_id']]['room_revenue'] = 0;
                    $total_allba[$value['portal_id']]['name'] = $value['name'];
                    $total_allba[$value['portal_id']]['minibar'] = 0;
                    $total_allba[$value['portal_id']]['laundry'] = 0;
                    $total_allba[$value['portal_id']]['extra_services'] = 0;
                    $total_allba[$value['portal_id']]['EI_LO'] = 0;
                    $total_allba[$value['portal_id']]['equipment'] = 0;
            }
        }
        //-------------------------------------/cung ki --------------------------------------------------------
        //--------------------------------------Ke Hoach-----------------------------------------------------------------
        /////////////////////////////-----------Phong--------------/////////////////////
        $sql_room_allba = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'ROOM\'
                order by
                    plan_in_month.portal_id
        ';
        $items_room_allba = DB::fetch_all($sql_room_allba);
        foreach($items_room_allba as $key=>$value)
        {
            if(isset($total_allba[$value['portal_id']]))
            {
                if(isset($total_allba[$value['portal_id']]['total_month_room']))
                {
                    $total_allba[$value['portal_id']]['total_month_room'] += $value['value'];
                }
                else
                {
                    $total_allba[$value['portal_id']]['total_month_room'] = $value['value'];
                }  
            } 
        }
        ////////////////////////////////-----------------/Phong-----------/////////////////////////////////////
        /////////////////////////////-----------Minibar--------------/////////////////////
        $sql_minibar_allba = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'MINIBAR\'
                order by
                    plan_in_month.portal_id
        ';
        $items_minibar_allba = DB::fetch_all($sql_minibar_allba);
        foreach($items_minibar_allba as $key=>$value)
        {
            if(isset($total_allba[$value['portal_id']]))
            {
                if(isset($total_allba[$value['portal_id']]['total_month_minibar']))
                {
                    $total_allba[$value['portal_id']]['total_month_minibar'] += $value['value'];
                }
                else
                {
                    $total_allba[$value['portal_id']]['total_month_minibar'] = $value['value'];
                }
            }
        }
        ////////////////////////////////-----------------/Minibar-----------/////////////////////////////////////
        /////////////////////////////-----------Laundry--------------/////////////////////
        $sql_laundry_allba = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'LAUNDRY\'
                order by
                    plan_in_month.portal_id
        ';
        $items_laundry_allba = DB::fetch_all($sql_laundry_allba);
        foreach($items_laundry_allba as $key=>$value)
        {
            if(isset($total_allba[$value['portal_id']]))
            {
                if(isset($total_allba[$value['portal_id']]['total_month_laundry']))
                {
                    $total_allba[$value['portal_id']]['total_month_laundry'] += $value['value'];
                }
                else
                {
                    $total_allba[$value['portal_id']]['total_month_laundry'] = $value['value'];
                }
            }
        }
        ////////////////////////////////-----------------/Laundry-----------/////////////////////////////////////
                /////////////////////////////-----------/extra_services--------------/////////////////////
        $sql_extra_services_allba = '
                select
                     plan_in_month.id
                    ,plan_in_month.name
                    ,plan_in_month.value
                    ,plan_in_month.month
                    ,plan_in_month.room_type_id
                    ,plan_in_month.portal_id
                from plan_in_month
                    inner join room_type on plan_in_month.room_type_id = room_type.id
                where
                    plan_in_month.month>='.$m.' and plan_in_month.month<='.$m2.'
                    and  plan_in_month.year = '.$y.'
                    and plan_in_month.name = \'EXTRA_SERVICES\'
                order by
                    plan_in_month.portal_id
        ';
        $items_extra_services_allba = DB::fetch_all($sql_extra_services_allba);
        foreach($items_extra_services_allba as $key=>$value)
        {
            if(isset($total_allba[$value['portal_id']]))
            {
                if(isset($total_allba[$value['portal_id']]['total_month_extra_services']))
                {
                    $total_allba[$value['portal_id']]['total_month_extra_services'] += $value['value'];
                }
                else
                {
                    $total_allba[$value['portal_id']]['total_month_extra_services'] = $value['value'];
                }
            }
        }
        //System::debug($total_allba);
        $total_all = array_merge($total_resort,$total_queen,$total_allba);
        $total_all = array();
        //exit();
        $grand_total =array();
        $grand_total['room_revenue']=0;
        $grand_total['laundry']=0;
        $grand_total['extra_services']=0;
        $grand_total['minibar']=0;
        $grand_total['equipment']=0;
        $grand_total['EI_LO']=0;
        //$grand_total['room_revenue_last_year']=0;
//        $grand_total['laundry_last_year']=0;
//        $grand_total['extra_services_last_year']=0;
//        $grand_total['minibar_last_year']=0;
//        $grand_total['equipment_last_year']=0;
//        $grand_total['EI_LO_last_year']=0;
        $grand_total['room_revenue_same_period']=0;
        $grand_total['laundry_same_period']=0;
        $grand_total['extra_services_same_period']=0;
        $grand_total['minibar_same_period']=0;
        $grand_total['equipment_same_period']=0;
        $grand_total['EI_LO_same_period']=0;
        $grand_total['total_month_room']=0;
        $grand_total['total_month_laundry']=0;
        $grand_total['total_month_extra_services']=0;
        $grand_total['total_month_minibar']=0;
        foreach($total_all as $key=>$value)
        {
            if(isset($grand_total['room_revenue']) && isset($grand_total['room_revenue_same_period']))
            {
            $grand_total['room_revenue'] += $value['room_revenue'];
            $grand_total['laundry'] += $value['laundry'];
            $grand_total['extra_services'] += $value['extra_services'];
            $grand_total['minibar'] += $value['minibar'];
            $grand_total['equipment'] += $value['equipment'];
            $grand_total['EI_LO'] += $value['EI_LO'];
  //          $grand_total['room_revenue_last_year'] += $value['room_revenue_last_year'];
//            $grand_total['laundry_last_year'] += $value['laundry_last_year'];
//            $grand_total['extra_services_last_year'] += $value['extra_services_last_year'];
//            $grand_total['minibar_last_year'] += $value['minibar_last_year'];
//            $grand_total['equipment_last_year'] += $value['equipment_last_year'];
//            $grand_total['EI_LO_last_year'] += $value['EI_LO_last_year'];
            $grand_total['room_revenue_same_period'] += $value['room_revenue_same_period'];
            $grand_total['laundry_same_period'] += $value['laundry_same_period'];
            $grand_total['extra_services_same_period'] += $value['extra_services_same_period'];
            $grand_total['minibar_same_period'] += $value['minibar_same_period'];
            $grand_total['equipment_same_period'] += $value['equipment_same_period'];
            $grand_total['EI_LO_same_period'] += $value['EI_LO_same_period'];
            $grand_total['total_month_room']+=$value['total_month_room'];
            $grand_total['total_month_laundry']+=$value['total_month_laundry'];
            $grand_total['total_month_extra_services']+=$value['total_month_extra_services'];
            $grand_total['total_month_minibar']+=$value['total_month_minibar'];
            }
            else
            {
            $grand_total['room_revenue'] = $value['room_revenue'];
            $grand_total['laundry'] = $value['laundry'];
            $grand_total['extra_services'] = $value['extra_services'];
            $grand_total['minibar'] = $value['minibar'];
            $grand_total['equipment'] = $value['equipment'];
            $grand_total['EI_LO'] = $value['EI_LO'];
            //$grand_total['room_revenue_last_year'] = $value['room_revenue_last_year'];
//            $grand_total['laundry_last_year'] = $value['laundry_last_year'];
//            $grand_total['extra_services_last_year'] = $value['extra_services_last_year'];
//            $grand_total['minibar_last_year'] = $value['minibar_last_year'];
//            $grand_total['equipment_last_year'] = $value['equipment_last_year'];
//            $grand_total['EI_LO_last_year'] = $value['EI_LO_last_year'];
            $grand_total['room_revenue_same_period'] = $value['room_revenue_same_period'];
            $grand_total['laundry_same_period'] = $value['laundry_same_period'];
            $grand_total['extra_services_same_period'] = $value['extra_services_same_period'];
            $grand_total['minibar_same_period'] = $value['minibar_same_period'];
            $grand_total['equipment_same_period'] = $value['equipment_same_period'];
            $grand_total['EI_LO_same_period'] = $value['EI_LO_same_period'];
            $grand_total['total_month_room']=$value['total_month_room'];
            $grand_total['total_month_laundry']=$value['total_month_laundry'];
            $grand_total['total_month_extra_services']=$value['total_month_extra_services'];
            $grand_total['total_month_minibar']=$value['total_month_minibar'];
            }
        }
        //System::debug($grand_total);
//-------------------------------------------------------/Allba_Hotel___________________________________________________________________                                  
        $this->parse_layout('report',$this->map + array
                                (
                                    'total_revenue'=>$total_revenue ,
                                    'total_resort'=>$total_resort ,
                                    'total_queen'=>$total_queen,
                                    'total_allba'=>$total_allba,
                                    'grand_total'=>$grand_total,    
                                )
                            );
        
    }
    function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date($time_today);
		$day_begin_of_week = $time_today;
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date($time_today);
		$end_of_week = $time_today;
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
 }
 ?>