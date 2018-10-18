<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
EDITED BY KHOAND
******************************/

	function number_array($from, $to, $name=false)
	{
		$array = array();
		for($i = $from; $i<=$to; $i++)
		{
			if($name)
			{
				$array[]=array($name=>(strlen($i)<2)?'0'.$i:$i);
			}
			else
			{
				$array[$i] = $i;
			}
		}
		return $array;
	}
	function get_time_parameter($param, $default, &$end)
	{
		$number = URL::get($param, $default);
		if(!$number) $number = $default;
		$end = $number;
		if(is_numeric($number))
		{
			return $number;
		}
		$numbers = explode('-',$number);
		if(is_numeric($numbers[0]))
		{
			if($numbers[1])
			{
				$end = $numbers[1];
			}
			else
			{
				$end = $numbers[0];
			}
			return $numbers[0];
		}
		return $default;
	}
	function get_time_parameters($day=false, $end_day=false)
	{
		$years = number_array(BEGINNING_YEAR-1, date('Y',time())+4, 'year');
		$start = get_time_parameter('year', date('Y'), $end);
		$current_year = 0;
		foreach($years as $key=>$year)
		{
			$years[$key]['selected'] = ($year['year']>=$start and $year['year']<=$end)?'_selected':'';
			if($years[$key]['selected']=='_selected'){
				$current_year = intval($year['year']);
			}
		}
		$months = number_array(0, 12, 'month');
		$start = get_time_parameter('month', date('m'), $end);
		$current_month = 0;
		foreach($months as $key=>$month)
		{
			$months[$key]['selected'] = ($month['month']>=$start and $month['month']<=$end)?'_selected':'';
			if($months[$key]['selected']=='_selected'){
				$current_month = intval($month['month']);
			}
		}
        //System::debug($current_month);
        //System::debug($current_year);
		$days = number_array(0, cal_days_in_month(CAL_GREGORIAN,$current_month,$current_year), 'day');
		if(!$day)
		{
			$start = get_time_parameter('day', date('d'), $end);
		}
		else
		{
			$start = $day;
			$end = $end_day;
		}
		foreach($days as $key=>$day)
		{
			$days[$key]['selected'] = ($day['day']>=$start and $day['day']<=$end)?'_selected':'';
			$current_time = Date_Time::to_time($day['day'].'/'.$current_month.'/'.$current_year);
			$days[$key]['date'] = substr(date('l',$current_time),0,2);
			$days[$key]['day_of_week'] = substr(date('w',$current_time),0,2);
		}
		return array('years'=>$years,'months'=>$months,'days'=>$days);
	}
	function get_time_range(&$obj)
	{
		$obj->from_year =1990;
		$obj->to_year =date('Y',time());
		$obj->from_month=1;
		$obj->to_month=1;
		$obj->from_day=1;
		$obj->to_day=cal_days_in_month(CAL_GREGORIAN,$obj->to_month,$obj->to_year);
		if(URL::get('from_year'))
		{
			$obj->from_year = Url::get('from_year');
		}
		if(URL::get('to_year'))
		{
			$obj->to_year = Url::get('to_year');
		}
		if(Url::get('from_month'))
		{	
			$obj->from_month = Url::get('from_month');
		}
		if(Url::get('to_month'))
		{	
			$obj->to_month = Url::get('to_month');
		}
		if(URL::get('from_day'))
		{
			$obj->from_day = URL::get('from_day');
		}
		if(URL::get('to_day'))
		{
			$obj->to_day = URL::get('to_day');
		}
		$_REQUEST['day'] = $obj->from_day.'-'.$obj->to_day;
		$_REQUEST['month'] = $obj->from_month.'-'.$obj->to_month;
		$_REQUEST['year'] = $obj->from_year.'-'.$obj->to_year;
	}
?>