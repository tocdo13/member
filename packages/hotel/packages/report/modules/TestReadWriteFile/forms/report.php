<?php
class SummaryReportForm extends Form
{
	function SummaryReportForm()
	{
		Form::Form('SummaryReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/skins/default/css/night_audit.css');
	}
    function on_submit()
    {
        $file = fopen('telephone\Pbxlog_Jun-2013.txt','r');
        $index = 0;
        $array_data = array();
        while(!feof($file))
        {
            $strtemp = fgets($file);
            $array_data[$index++] = sscanf($strtemp,"%s %s %s %s %s %s");
        }
        fclose($file);
        
        System::debug($array_data);
    }
	function draw()
	{
		$this->map = array();
		
		$this->parse_layout('report',$this->map);
	}
}
?>