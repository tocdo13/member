<?php
class RestaurantRevenueReportForm extends Form
{
	function RestaurantRevenueReportForm()
	{
		Form::Form('RestaurantRevenueReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');  
	}
	function draw()
	{
        $summary = array();
        
        //search
        $summary['from_day'] = Url::get('from_date',date('d/m/Y'));
        $summary['to_day'] = Url::get('to_date',date('d/m/Y'));
        $from_day = Date_Time::to_orc_date($summary['from_day']);
        $to_day = Date_Time::to_orc_date($summary['to_day']);
        
        require_once 'packages/hotel/packages/vending/modules/VendingProductRevenueReportWater/db.php';
        $summary['items'] = RestaurantRevenueReportFormDB::getdata($from_day,$to_day);
        //System::debug($summary['items']);
        $summary += RestaurantRevenueReportFormDB::get_summary($summary['items']);
        //System::debug($summary);
		$this->parse_layout('report',$summary);
	}
}
?>