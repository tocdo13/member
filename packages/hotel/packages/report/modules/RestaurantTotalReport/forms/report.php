<?php 
class RestaurantTotalReportForm extends Form
{
	function RestaurantTotalReportForm()
	{
		Form::Form('RestaurantTotalReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function draw()
    {
        if(Url::get('do_search'))
        {
            //echo 'dddddddd';
            //exit();
            
        }
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
        $bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($cond);
        $resort_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($cond,'#default');
        $alba_queen_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($cond,'#huequeen1');
        $alba_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($cond,'#huequeen2');
        //lay so lieu ki truowc
        $decrese_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($decrese_cond);
        $decrese_resort_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($decrese_cond,'#default');
        $decrese_alba_queen_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($decrese_cond,'#huequeen1');
        $decrese_alba_bar_revenue = RestaurantTotalReportDB::get_restaurant_revenue($decrese_cond,'#huequeen2');
        //System::debug($bar_revenue);
        // lay du lieu ke hoach
        $items = RestaurantTotalReportDB::get_plan_revenue();
        if(User::is_admin())
        {
            //System::debug($items);
        }
        //khop noi cac so lieu
        foreach($bar_revenue as $key => $value)
        {
            $bar_revenue[$key]['plan_revenue'] = 0;
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
            // add doanh thu ke hoach vao 
            if(isset($items[$key.'_is_processed']) and $items[$key.'_is_processed']['portal_id'] = '#default')
            {
                $bar_revenue[$key]['is_processed']['plan_revenue'] = $items[$key.'_is_processed']['value'];
                $bar_revenue[$key]['plan_revenue'] += $items[$key.'_is_processed']['value'];
            }
            else
            {
                $bar_revenue[$key]['is_processed']['plan_revenue'] = 0;
            }
            if(isset($items[$key.'_product']) and $items[$key.'_product']['portal_id'] = '#default')
            {
                $bar_revenue[$key]['product']['plan_revenue'] = $items[$key.'_product']['value'];
                $bar_revenue[$key]['plan_revenue'] += $items[$key.'_product']['value'];
            }
            else
            {
                $bar_revenue[$key]['product']['plan_revenue'] = 0;
            }
            if(isset($items[$key.'_service']) and $items[$key.'_service']['portal_id'] = '#default')
            {
                $bar_revenue[$key]['service']['plan_revenue'] = $items[$key.'_service']['value'];
                $bar_revenue[$key]['plan_revenue'] += $items[$key.'_service']['value'];
            }
            else
            {
                $bar_revenue[$key]['service']['plan_revenue'] = 0;
            }
        }
        $resort_bar_revenue['plan_revenue'] = 0;
        $resort_bar_revenue['is_processed']['plan_revenue'] = 0;
        $resort_bar_revenue['product']['plan_revenue'] = 0;
        $resort_bar_revenue['service']['plan_revenue'] = 0;
        
        $alba_queen_bar_revenue['plan_revenue'] = 0;
        $alba_queen_bar_revenue['is_processed']['plan_revenue'] = 0;
        $alba_queen_bar_revenue['product']['plan_revenue'] = 0;
        $alba_queen_bar_revenue['service']['plan_revenue'] = 0;
        
        $alba_bar_revenue['plan_revenue'] = 0;
        $alba_bar_revenue['is_processed']['plan_revenue'] = 0;
        $alba_bar_revenue['product']['plan_revenue'] = 0;
        $alba_bar_revenue['service']['plan_revenue'] = 0;
         
        foreach($items as $key => $value)
        {
            if($value['portal_id'] == '#default')
            {
                $resort_bar_revenue['plan_revenue'] += $value['value'];
                $resort_bar_revenue[$value['bar_index']]['plan_revenue'] += $value['value']; 
            }
            if($value['portal_id'] == '#huequeen1')
            {
                $alba_queen_bar_revenue['plan_revenue'] += $value['value'];
                $alba_queen_bar_revenue[$value['bar_index']]['plan_revenue'] += $value['value']; 
            }
            if($value['portal_id'] == '#huequeen2')
            {
                $alba_bar_revenue['plan_revenue'] += $value['value'];
                $alba_bar_revenue[$value['bar_index']]['plan_revenue'] += $value['value']; 
            }
        }
        $resort_bar_revenue['decrese_real_price'] = $decrese_resort_bar_revenue['real_price'];
        $resort_bar_revenue['is_processed']['decrese_real_price'] = $decrese_resort_bar_revenue['is_processed']['real_price'];
        $resort_bar_revenue['product']['decrese_real_price'] = $decrese_resort_bar_revenue['product']['real_price'];
        $resort_bar_revenue['service']['decrese_real_price'] = $decrese_resort_bar_revenue['service']['real_price'];
        
        $alba_queen_bar_revenue['decrese_real_price'] = $decrese_alba_queen_bar_revenue['real_price'];
        $alba_queen_bar_revenue['is_processed']['decrese_real_price'] = $decrese_alba_queen_bar_revenue['is_processed']['real_price'];
        $alba_queen_bar_revenue['product']['decrese_real_price'] = $decrese_alba_queen_bar_revenue['product']['real_price'];
        $alba_queen_bar_revenue['service']['decrese_real_price'] = $decrese_alba_queen_bar_revenue['service']['real_price'];
        
        $alba_bar_revenue['decrese_real_price'] = $decrese_alba_bar_revenue['real_price'];
        $alba_bar_revenue['is_processed']['decrese_real_price'] = $decrese_alba_bar_revenue['is_processed']['real_price'];
        $alba_bar_revenue['product']['decrese_real_price'] = $decrese_alba_bar_revenue['product']['real_price'];
        $alba_bar_revenue['service']['decrese_real_price'] = $decrese_alba_bar_revenue['service']['real_price'];
        //tong hop so lieu
        $summanry['is_processed']['real_price'] = $resort_bar_revenue['is_processed']['real_price'] + $alba_queen_bar_revenue['is_processed']['real_price'] + $alba_bar_revenue['is_processed']['real_price'];
        $summanry['is_processed']['original_price'] = $resort_bar_revenue['is_processed']['original_price'] + $alba_queen_bar_revenue['is_processed']['original_price'] + $alba_bar_revenue['is_processed']['original_price'];
        $summanry['is_processed']['decrese_real_price'] = $resort_bar_revenue['is_processed']['decrese_real_price'] + $alba_queen_bar_revenue['is_processed']['decrese_real_price'] + $alba_bar_revenue['is_processed']['decrese_real_price'];
        $summanry['is_processed']['plan_revenue'] = $resort_bar_revenue['is_processed']['plan_revenue'] + $alba_queen_bar_revenue['is_processed']['plan_revenue'] + $alba_bar_revenue['is_processed']['plan_revenue'];
        
        $summanry['product']['real_price'] = $resort_bar_revenue['product']['real_price'] + $alba_queen_bar_revenue['product']['real_price'] + $alba_bar_revenue['product']['real_price'];
        $summanry['product']['original_price'] = $resort_bar_revenue['product']['original_price'] + $alba_queen_bar_revenue['product']['original_price'] + $alba_bar_revenue['product']['original_price'];
        $summanry['product']['decrese_real_price'] = $resort_bar_revenue['product']['decrese_real_price'] + $alba_queen_bar_revenue['product']['decrese_real_price'] + $alba_bar_revenue['product']['decrese_real_price'];
        $summanry['product']['plan_revenue'] = $resort_bar_revenue['product']['plan_revenue'] + $alba_queen_bar_revenue['product']['plan_revenue'] + $alba_bar_revenue['product']['plan_revenue'];
        
        $summanry['service']['real_price'] = $resort_bar_revenue['service']['real_price'] + $alba_queen_bar_revenue['service']['real_price'] + $alba_bar_revenue['service']['real_price'];
        $summanry['service']['original_price'] = $resort_bar_revenue['service']['original_price'] + $alba_queen_bar_revenue['service']['original_price'] + $alba_bar_revenue['service']['original_price'];
        $summanry['service']['decrese_real_price'] = $resort_bar_revenue['service']['decrese_real_price'] + $alba_queen_bar_revenue['service']['decrese_real_price'] + $alba_bar_revenue['service']['decrese_real_price'];
        $summanry['service']['plan_revenue'] = $resort_bar_revenue['service']['plan_revenue'] + $alba_queen_bar_revenue['service']['plan_revenue'] + $alba_bar_revenue['service']['plan_revenue'];
        
        $summanry['real_price'] = $summanry['is_processed']['real_price'] + $summanry['product']['real_price'] + $summanry['service']['real_price'];
        $summanry['original_price'] = $summanry['is_processed']['original_price'] + $summanry['product']['original_price'] + $summanry['service']['original_price'];
        $summanry['decrese_real_price'] = $summanry['is_processed']['decrese_real_price'] + $summanry['product']['decrese_real_price'] + $summanry['service']['decrese_real_price'];
        $summanry['plan_revenue'] = $summanry['is_processed']['plan_revenue'] + $summanry['product']['plan_revenue'] + $summanry['service']['plan_revenue'];
        //System::debug($resort_bar_revenue);
        $this->parse_layout('report',array(
                                            'bar_revenue'=>$bar_revenue,
                                            'resort'=>$resort_bar_revenue,
                                            'alba_queen'=>$alba_queen_bar_revenue,
                                            'summary'=>$summanry,
                                            'alba'=>$alba_bar_revenue
                                            ));
        
    }
}
?>