<?php 
class ArrivalReportForm extends Form{
    function ArrivalReportForm(){
        Form::Form('ArrivalReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function draw(){
        $this->map = array();
        if(!Url::get('from_date') && !Url::get('to_date')){
            $_REQUEST['from_date'] = $_REQUEST['to_date'] = date('d/m/Y');
        }
        $cond = ' reservation_room.arrival_time <=\''.Date_Time::to_orc_date(Url::get('to_date')).'\' AND reservation_room.arrival_time >= \''.Date_Time::to_orc_date(Url::get('from_date')).'\'';
        $this->map['report'] = ArrivalReportDB::get_report($cond);
        $this->map['service'] = ArrivalReportDB::get_service();
        $this->parse_layout('report' , $this->map);
    }
}
?>
