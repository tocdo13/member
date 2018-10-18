<?php
class ExportDataFromSoftwareForm extends Form
{
    function ExportDataFromSoftwareForm()
    {
        Form::Form('ExportDataFromSoftwareForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
        $this->map['export_date'] = isset($_REQUEST['export_date'])?$_REQUEST['export_date'] = $_REQUEST['export_date']:$_REQUEST['export_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time())));
        $this->map = array();  
        $this->parse_layout('report', $this->map);   
    }
}
?>