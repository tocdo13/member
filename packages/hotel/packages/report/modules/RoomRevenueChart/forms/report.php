<?php
class RoomRevenueChartReportForm extends Form{
	function RoomRevenueChartReportForm(){
		Form::Form('RoomRevenueChartReportForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.js');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/excanvas.compiled.js');
		$this->link_js('packages/hotel/packages/report/includes/monthpicker/dzung.monthpicker.js');
        $this->link_css('packages/hotel/packages/report/includes/monthpicker/dzung.monthpicker.css');
	}
    
    function get_items($dates){
        $items = array();
        foreach ($dates as $date_common => $date_orc) {
			$amount = RoomRevenueChartReportForm::get_amount_date($date_orc);
            $items[] = $amount;
			
        }
        return $items;
    }
    
    function get_amount_date($date){
        $result = 0;
        $sql = '
            SELECT 
                NVL(reservation_room.tax_rate, 0) tax_rate
                ,NVL(reservation_room.service_rate, 0) service_rate
                ,reservation_room.room_id  
                ,reservation_room.price
                ,NVL(room_status.change_price, 0) change_price
                ,room_status.id
				,reservation_room.id as reservation_room_id
                ,reservation_room.arrival_time
                ,reservation_room.departure_time
				,TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival
				,TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as departure
                ,TO_DATE(room_status.in_date, \'DD-MON-YYYY\') in_date
				,reservation_room.net_price
            FROM
                room_status
                INNER JOIN reservation_room ON room_status.reservation_room_id = reservation_room.id
                INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
            WHERE
                room_status.in_date = \''.$date.'\' AND 
                room_status.status = \'OCCUPIED\' 
        ';
        $rooms = DB::fetch_all($sql);
        
		$room_services = RoomRevenueChartReportForm::get_service_room($date);
		$total_room_service = 0;
		foreach($room_services as $k =>$service){ 
			$total_room_service += $service['amount'];
		}
        foreach($rooms as $key => $room ) {   
            $result += RoomRevenueChartReportForm::get_room_amount($room);    
        }
        return ($result+$total_room_service);
    }
    
    function get_room_amount($room){
        $room_service_rate = 0;
        $room_rate = 0;
        $tax_rate = 0;
        $room_amount = 0;
        if($room['arrival_time'] == $room['departure_time']){
            if($room['change_price'] == 0){
                $room['change_price'] = $room['price'];
            }    
        }
		if($room['net_price']==1){
			return $room['change_price'];
		}else{
			$room_service_rate = ($room['change_price'] * $room['service_rate']) / 100;
       	 	$room_rate =  $room['change_price'] + $room_service_rate;
        	$tax_rate = ($room_rate * $room['tax_rate']) / 100; 
        	$room_amount = $room_rate + $tax_rate;
        	return $room_amount;	
		}
    }
    function get_service_room($date){
		$room_services = DB::fetch_all('
					SELECT 	(exs.reservation_room_id || \'-\' || TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\')) as id
							,sum(exs.total_amount) as amount
							,TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date
					FROM 
							extra_service_invoice exs
							inner join extra_service_invoice_detail ON extra_service_invoice_detail.invoice_id = exs.id
							inner join reservation_room ON reservation_room.id = exs.reservation_room_id
					WHERE 
							exs.payment_type=\'ROOM\'
							AND extra_service_invoice_detail.in_date = \''.$date.'\'
					GROUP BY 
							exs.reservation_room_id
							,extra_service_invoice_detail.in_date
		');
		return $room_services;	
	}
    //Format date : (d/m/Y) 
    function getDatesFromRange($start_date = '', $end_date = ''){
        $sStartDate = $start_date;  
        $day = 24 * 3600;
        $aDays[$sStartDate] = Date_Time::to_orc_date($sStartDate);  
        $sCurrentDate = $sStartDate;
        while(true) {
            $aDays[$sCurrentDate] = Date_Time::to_orc_date($sCurrentDate);
            $sCurrentDate = date('d/m/Y', (Date_Time::to_time($sCurrentDate) + $day));
            if(Date_Time::to_time($sCurrentDate) >  Date_Time::to_time($end_date)){
                break;
            }
        }  
         
        return $aDays;
    }
    
    function process_dates($dates = array()){
        $arr = array();
        foreach ($dates as $key => $value ) {
            $arr[] = substr($key, 0, 2);
        }
        return json_encode($arr);
    }
    
    function process_data_chart($data, $color_code = 0){
        $resource = '';
        foreach ($data as $key => $value ) {
            $row = '{y:'.($value / 1000).',color: colors['.$color_code.']},';
            $resource .= $row;
        }
        return '['.$resource.']';
    }
    
    function draw()
    {
        $m_y = array();
        $dates = array();
        $data = array();
        
        if(Url::get('month_report1')){
            $m_y = explode('/' , Url::get('month_report1'));
        }else{
            $m_y = explode('/' , date('m/Y', time()));
        }
        
        $_REQUEST['month_report1'] = implode('/',$m_y);
        
        //$from_date = Url::get('from_date') ? Url::get('from_date') : '1/'.date('m/Y',time());
        //$to_date = Url::get('to_date') ? Url::get('to_date') : '29/'.date('m/Y',time());
                        
        $end_of_month  = cal_days_in_month(CAL_GREGORIAN, $m_y[0], $m_y[1]);
        $from_date = Url::get('month_report1') ? '01/'.Url::get('month_report1') : '01/'.date('m/Y',time());
        $to_date = Url::get('month_report1') 
            ? $end_of_month.'/'.Url::get('month_report1') 
            : cal_days_in_month(CAL_GREGORIAN, date('m',time()), date('Y',time())).'/'.date('m/Y',time());
        
        $dates = RoomRevenueChartReportForm::getDatesFromRange($from_date, $to_date);
        $data = RoomRevenueChartReportForm::get_items($dates);
        
        $this->parse_layout('report',array(
            'data' => RoomRevenueChartReportForm::process_data_chart($data),
            'to_date' => $to_date,
            'from_date' => $from_date,
            'date_list' => RoomRevenueChartReportForm::process_dates($dates)
        ));
    }
}
?>