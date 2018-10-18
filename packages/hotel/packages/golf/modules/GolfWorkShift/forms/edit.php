<?php
class EditgolfWorkShiftForm extends Form
{
	function EditgolfWorkShiftForm()
	{
		Form::Form('EditgolfWorkShiftForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
        $this->link_js('packages/hotel/packages/golf/includes/jquery-ui.min.js');
        $this->link_js('packages/core/includes/js/jquery.ui.touch-punch.min.js');
        $this->link_js('packages/core/includes/js/jquery.touch.min.js');
        $this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
        $this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.droppable.js');
	}
	function on_submit()
    {
        
	}
	function draw()
	{
	   $this->map = array();
       
       $month = $this->map['month'] = isset($_REQUEST['month'])?$_REQUEST['month']:(date('m')+0);
       $year = $this->map['year'] = isset($_REQUEST['year'])?$_REQUEST['year']:date('Y');
       
       $this->map['month_list'] = array(
                                        '1'=>Portal::language('month').' 1', '2'=>Portal::language('month').' 2', '3'=>Portal::language('month').' 3', '4'=>Portal::language('month').' 4',
                                        '5'=>Portal::language('month').' 5', '6'=>Portal::language('month').' 6', '7'=>Portal::language('month').' 7', '8'=>Portal::language('month').' 8',
                                        '9'=>Portal::language('month').' 9', '10'=>Portal::language('month').' 10', '11'=>Portal::language('month').' 11', '12'=>Portal::language('month').' 12',
                                        );
       $this->map['year_list'] = array();
       for($i=$year-5;$i<=$year+5;$i++){
            $this->map['year_list'][$i] = Portal::language('year').' '.$i;
       }
       $start_date = '01/'.$month.'/'.$year;
       $end_date = cal_days_in_month(CAL_GREGORIAN,$month,$year).'/'.$month.'/'.$year;
       $timeline = array();
       $j = 1;
       $timeline[1]['week'] = array('MON'=>array(),'TUE'=>array(),'WED'=>array(),'THU'=>array(),'FRI'=>array(),'SAT'=>array(),'SUN'=>array());
       for($i=Date_Time::to_time($start_date);$i<=Date_Time::to_time($end_date);$i+=86400){
            $date = getdate($i);
            $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
            $date['id'] = $i;
            if($date['wday']==1){
                $j++;
                $timeline[$j]['week'] = array('MON'=>array(),'TUE'=>array(),'WED'=>array(),'THU'=>array(),'FRI'=>array(),'SAT'=>array(),'SUN'=>array());
            }
            $timeline[$j]['week'][$date['weekday']] = $date;
       }
       $this->map['count_week'] = $j;
       $this->map['timeline'] = $timeline;
       
       $this->parse_layout('edit',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
