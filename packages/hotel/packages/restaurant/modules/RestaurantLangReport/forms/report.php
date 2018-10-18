<?php 
class RestaurantLangReportForm extends Form
{
	function RestaurantLangReportForm()
	{
		Form::Form('RestaurantLangReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function draw()
    {
        if(Url::sget('from_date') and Url::sget('to_date'))
        {
            $from_month = date('m',Date_Time::to_time(Url::sget('from_date')));
            $to_month = date('m',Date_Time::to_time(Url::sget('to_date')));
            $decrese_month = $to_month - $from_month + 1;
        }
        else
        {
            $decrese_month = 1;
        }
        $decrese_cond = '1=1 ';
        $cond = '1=1 ';
        if(Url::sget('from_date'))
        {
            $day = date('d',Date_Time::to_time(Url::sget('from_date')));
            $month = date('m',Date_Time::to_time(Url::sget('from_date')));
            $year = date('Y',Date_Time::to_time(Url::sget('from_date')));
            $decrese_date = $day.'/'.($month - $decrese_month).'/'.$year;
            $decrese_cond .= 'AND br.departure_time >= '.Date_Time::to_time($decrese_date);
            $cond .= 'AND br.departure_time >= '.Date_Time::to_time(Url::get('from_date'));
            $_REQUEST['from_date'] = Url::get('from_date');
        }
        else
        {
            $day = date('d',time()-6*60*60*24);
            $month = date('m',time()-6*60*60*24);
            $year = date('Y',time()-6*60*60*24);
            $decrese_date = $day.'/'.($month - $decrese_month).'/'.$year;
            $decrese_cond .= 'AND br.departure_time >= '.Date_Time::to_time($decrese_date);
            $cond .= 'AND br.departure_time >= '.(Date_Time::to_time(date('d/m/Y',time()-6*60*60*24)));
            $_REQUEST['from_date'] = date('d/m/Y',time()-6*60*60*24);
        }
        if(Url::sget('to_date'))
        {
            $day = date('d',Date_Time::to_time(Url::sget('to_date')));
            $month = date('m',Date_Time::to_time(Url::sget('to_date')));
            $year = date('Y',Date_Time::to_time(Url::sget('to_date')));
            $decrese_date = $day.'/'.($month - $decrese_month).'/'.$year;
            $decrese_cond .= 'AND br.departure_time <= '.Date_Time::to_time($decrese_date);
            $cond .= ' AND br.departure_time <= '.(Date_Time::to_time(Url::get('to_date')) + 60*60*24);
            $_REQUEST['to_date'] = Url::get('to_date');
        }
        else
        {
            $day = date('d',time());
            $month = date('m',time());
            $year = date('Y',time());
            $decrese_date = $day.'/'.($month - $decrese_month).'/'.$year;
            $decrese_cond .= 'AND br.departure_time <= '.Date_Time::to_time($decrese_date);
            $cond .= 'AND br.departure_time <= '.(Date_Time::to_time(date('d/m/Y')) + 60*60*24);
            $_REQUEST['to_date'] = date('d/m/Y');
        }
        if(Url::get('hotel_id') and (Url::get('hotel_id') == 'all'))
        {
            $portal_id = '#default';
        }
        else
        if(Url::get('hotel_id') and (Url::get('hotel_id') != 'all'))
        {
            $portal_id = Url::get('hotel_id');
        }
        else
        {
            $portal_id = '#default';
        }
        $portal_list = Portal::get_portal_list();
        $bar_revenue = RestaurantLangReportDB::get_restaurant_revenue($cond,$for_portal = false, $portal_id);
        //lay so lieu ki truowc
        $decrese_bar_revenue = RestaurantLangReportDB::get_restaurant_revenue($decrese_cond,$for_portal = false, $portal_id);
        $all_portal_revenue = array();
        $decrese_all_portal_revenue = array();
        foreach($portal_list as $key => $value)
        {
            $all_portal_revenue[$key] = RestaurantLangReportDB::get_restaurant_revenue($cond,$key);
            $decrese_all_portal_revenue[$key] = RestaurantLangReportDB::get_restaurant_revenue($cond,$key);
        }
        //System::debug($bar_revenue);
        //khop noi cac so lieu
        foreach($bar_revenue as $key => $value)
        {
            if(isset($decrese_bar_revenue[$key]))
            {
                $bar_revenue[$key]['decrese_real_price'] = $decrese_bar_revenue[$key]['real_price'];
                $bar_revenue[$key]['decrese_original_price'] = $decrese_bar_revenue[$key]['original_price'];
                $bar_revenue[$key]['is_processed']['decrese_real_price'] = $decrese_bar_revenue[$key]['is_processed']['real_price'];
                $bar_revenue[$key]['product']['decrese_real_price'] = $decrese_bar_revenue[$key]['product']['real_price'];
                $bar_revenue[$key]['service']['decrese_real_price'] = $decrese_bar_revenue[$key]['service']['real_price'];
            }
            else
            {
                $bar_revenue[$key]['decrese_real_price'] = 0;
                $bar_revenue[$key]['decrese_original_price'] = 0;
                $bar_revenue[$key]['is_processed']['decrese_real_price'] = 0;
                $bar_revenue[$key]['product']['decrese_real_price'] = 0;
                $bar_revenue[$key]['service']['decrese_real_price'] = 0;
            }
        }
        $summanry['real_price'] = 0;
        $summanry['original_price'] = 0;
        $summanry['decrese_real_price'] = 0; 
        
        $summanry['is_processed']['real_price'] = 0;
        $summanry['is_processed']['original_price'] = 0;
        $summanry['is_processed']['decrese_real_price'] = 0;
        
        $summanry['product']['real_price'] = 0;
        $summanry['product']['original_price'] = 0;
        $summanry['product']['decrese_real_price'] = 0;
        
        $summanry['service']['real_price'] = 0;
        $summanry['service']['original_price'] = 0;
        $summanry['service']['decrese_real_price'] = 0;
        foreach($all_portal_revenue as $key => $value)
        {
            $all_portal_revenue[$key]['decrese_real_price'] = $decrese_all_portal_revenue[$key]['real_price'];
            $all_portal_revenue[$key]['is_processed']['decrese_real_price'] = $decrese_all_portal_revenue[$key]['is_processed']['real_price'];
            $all_portal_revenue[$key]['product']['decrese_real_price'] = $decrese_all_portal_revenue[$key]['product']['real_price'];
            $all_portal_revenue[$key]['service']['decrese_real_price'] = $decrese_all_portal_revenue[$key]['service']['real_price'];
            
            //tong hop so lieu
            $summanry['is_processed']['real_price'] += $value['is_processed']['real_price'];
            $summanry['is_processed']['original_price'] += $value['is_processed']['original_price'];
            $summanry['is_processed']['decrese_real_price'] += $decrese_all_portal_revenue[$key]['is_processed']['real_price'];
            
            $summanry['product']['real_price'] += $value['product']['real_price'];
            $summanry['product']['original_price'] += $value['product']['original_price'];
            $summanry['product']['decrese_real_price'] += $decrese_all_portal_revenue[$key]['product']['real_price'];
            
            $summanry['service']['real_price'] += $value['service']['real_price'];
            $summanry['service']['original_price'] += $value['service']['original_price'];
            $summanry['service']['decrese_real_price'] += $decrese_all_portal_revenue[$key]['service']['real_price'];
            
            $summanry['real_price'] += $value['is_processed']['real_price'] + $value['product']['real_price'] + $value['service']['real_price'];
            $summanry['original_price'] += $value['is_processed']['original_price'] + $value['product']['original_price'] + $value['service']['original_price'];
            $summanry['decrese_real_price'] += $decrese_all_portal_revenue[$key]['is_processed']['real_price'] + $decrese_all_portal_revenue[$key]['product']['real_price'] + $decrese_all_portal_revenue[$key]['service']['real_price']; 
        }
        //System::debug($resort_bar_revenue);
        // parse array
        // chi ap dung tren thanh tan, khi sang cac potal khac can thay doi lai, vi ngai dung lai lay out
        // i = 1,2
        $i = 1;
        foreach($portal_list as $key => $value)
        {
            if($key == $portal_id)
            {
                $parse_array['resort'] = $all_portal_revenue[$portal_id];
            }
            else
            {
                $parse_array[$i] = $all_portal_revenue[$key];
                if($i == 1)
                {
                    $parse_array['alba_queen'] = $parse_array[1];
                }
                if($i == 2)
                {
                    $parse_array['alba'] = $parse_array[2];
                }
                $i += 1;
            }
        }
        $this->parse_layout('report',array(
                                            'bar_revenue'=>$bar_revenue,
                                            'summary'=>$summanry,
                                            'portal_name'=>$portal_id,
                                            'hotel_id_list'=>array('all'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()),
                                            ) + $parse_array);
        
    }
}
?>