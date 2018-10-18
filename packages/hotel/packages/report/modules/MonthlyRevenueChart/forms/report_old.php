<?php
class MonthlyRevenueChartDzungForm extends Form{
	function MonthlyRevenueChartDzungForm(){
		Form::Form('MonthlyRevenueChartDzungForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.js');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/excanvas.compiled.js');
	}
    /**
     * FUNCTION calc revenue
     * 
        CREATE OR REPALCE
        FUNCTION month_revenue(
          tax_rate IN NUMBER,
          service_rate IN NUMBER,
          price IN NUMBER,
          change_price IN NUMBER,
          arrival_time IN DATE,
          departure_time IN DATE,
          deposit IN NUMBER
        ) return NUMBER
        IS
          total_room_revenue NUMBER;
          room_price NUMBER;
        BEGIN
          IF arrival_time = departure_time AND change_price = 0
            THEN
              room_price := price;
            ELSE
              room_price := change_price;
          END IF;
          total_room_revenue := room_price + ((room_price * service_rate) / 100) + ((tax_rate * room_price) / 100) - deposit;
          RETURN total_room_revenue;
        END;
    */
    
    function get_housekepping($year){
        $total_room_revenue = 0;
        $cond = 'rr.status =\'CHECKOUT\' AND EXTRACT(YEAR FROM rr.departure_time) = \''.$year.'\' ';
        $sql = '
            SELECT 
              SUM(MONTH_REVENUE(
                NVL(rr.tax_rate, 0),
                NVL(rr.service_rate, 0),
                rr.price,
                NVL(rs.change_price, 0),
                rr.arrival_time,
                rr.departure_time,
                NVL(rr.deposit, 0)
              )) total
              ,EXTRACT(MONTH FROM rr.departure_time) id
            FROM room_status rs 
              INNER JOIN reservation_room rr ON rs.reservation_room_id = rr.id 
              INNER JOIN reservation r ON r.id = rr.reservation_id 
            WHERE
                '.$cond.'  
            GROUP BY EXTRACT(MONTH FROM rr.departure_time)
        ';
		echo $sql;
        $monthty_revenue = DB::fetch_all($sql);
        $sql_deposit = '
            SELECT 
                SUM(deposit) as total
                ,EXTRACT(MONTH FROM deposit_date) id
            FROM 
                reservation_room
            WHERE
                EXTRACT(YEAR FROM deposit_date) = \''.$year.'\'
            GROUP BY EXTRACT(MONTH FROM deposit_date)
        ';
        $deposit = DB::fetch_all($sql_deposit);
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($monthty_revenue[$i]) && $monthty_revenue[$i]['total'])? $monthty_revenue[$i]['total'] : 0) 
                        + ((isset($deposit[$i]) && $deposit[$i]['total']) ? $deposit[$i]['total'] : 0);
        }
        return $months;
    }
    
    function get_other_service($year){
        
        $cond = '(rs.status =\'CHECKOUT\' OR rs.status =\'CHECKIN\') 
            AND EXTRACT(YEAR FROM rr.departure_time) = \''.$year.'\' ';
		$sql='  SELECT 
						SUM(rrs.amount) total 
                        ,EXTRACT(MONTH FROM rr.departure_time) id
				FROM 
						reservation_room_service rrs 
						INNER JOIN reservation_room rr ON rrs.reservation_room_id = rr.id 
						INNER JOIN room_status rs ON rr.id = rs.reservation_room_id
						INNER JOIN service ON service.id = rrs.service_id				
				WHERE 	
				    '.$cond.'
				GROUP BY EXTRACT(MONTH FROM rr.departure_time) ';
		$services = DB::fetch_all($sql);
        
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($services[$i]) && $services[$i]['total'])? $services[$i]['total'] : 0);
        }
		return $months;
    }
    
    function get_bar($year){
        $sql = '    SELECT 
						 SUM(br.total) total
                         ,EXTRACT(MONTH FROM FROM_UNIXTIME(br.departure_time)) id
					FROM
						bar_reservation br
					WHERE  
						br.status=\'CHECKOUT\' 
                        AND EXTRACT(YEAR FROM FROM_UNIXTIME(br.departure_time)) = \''.$year.'\'
                    GROUP BY EXTRACT(MONTH FROM FROM_UNIXTIME(br.departure_time)) 
            ';
        $bar = DB::fetch_all($sql);
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($bar[$i]) && $bar[$i]['total'])? $bar[$i]['total'] : 0);
        }
		return $months;
    }
    
    function get_spa($year){
        $sql ='
					SELECT 
                        SUM(massage_reservation_room.total_amount) total
                        ,EXTRACT(MONTH FROM FROM_UNIXTIME(time)) id
					FROM 
						massage_reservation_room
					WHERE
                        status=\'CHECKOUT\' 
                        AND EXTRACT(YEAR FROM FROM_UNIXTIME(time)) =\''.$year.'\'
                    GROUP BY EXTRACT(MONTH FROM FROM_UNIXTIME(time))
                    ';    
		$spa = DB::fetch_all($sql);
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($spa[$i]) && $spa[$i]['total'])? $spa[$i]['total'] : 0);
        }
        return $months;
    }
    function get_amount_minibar($year){
        $extra_cond = '';
		$extra_cond = 'rr.status =\'CHECKOUT\' AND EXTRACT(YEAR FROM rr.departure_time) =\''.$year.'\'';
		$sql = '
					SELECT
						SUM(hk.total) total
                        ,EXTRACT(MONTH FROM rr.departure_time) id
					FROM
                        housekeeping_invoice hk
                        INNER JOIN reservation_room rr ON rr.id = hk.reservation_id
					WHERE hk.type = \'MINIBAR\' AND '.$extra_cond.'
                    GROUP BY EXTRACT(MONTH FROM rr.departure_time)
        '; 
        $minibar = DB::fetch_all($sql);
		$months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($minibar[$i]) && $minibar[$i]['total'])? $minibar[$i]['total'] : 0);
        }
        return $months;
    }
    function get_amount_laundry($year){
        $extra_cond = '';
		$extra_cond = ' rr.status =\'CHECKOUT\' AND EXTRACT(YEAR FROM rr.departure_time) =\''.$year.'\'';
		$sql = '
					SELECT
						SUM(hk.total) total
                        ,EXTRACT(MONTH FROM rr.departure_time) id
					FROM
                        housekeeping_invoice hk
                        INNER JOIN reservation_room rr ON rr.id = hk.reservation_id
					WHERE hk.type = \'LAUNDRY\' AND '.$extra_cond.'
                    GROUP BY EXTRACT(MONTH FROM rr.departure_time)
        '; 
		$laundry = DB::fetch_all($sql);
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($laundry[$i]) && $laundry[$i]['total'])? $laundry[$i]['total'] : 0);
        }
        return $months;
    }
    function get_amount_phone($year){
        $extra_cond = '';
		$extra_cond = 'EXTRACT(YEAR FROM FROM_UNIXTIME(trd.hdate)) = \''.$year.'\'';
		$sql = '
					SELECT
						SUM(trd.price) total
                        ,EXTRACT(MONTH FROM FROM_UNIXTIME(trd.hdate)) id
					FROM
                        telephone_report_daily trd
                        INNER JOIN telephone_number tn ON trd.phone_number_id = tn.id
                        INNER JOIN reservation_room rr ON rr.room_id = tn.room_id
					WHERE '.$extra_cond.'
                    GROUP BY EXTRACT(MONTH FROM FROM_UNIXTIME(trd.hdate))
        '; 
		$phone = DB::fetch_all($sql);
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($phone[$i]) && $phone[$i]['total'])? $phone[$i]['total'] : 0);
        }
        return $months;
    }
    function get_amount_extra_service($year){
        $extra_cond = '';
			$extra_cond = 'rr.status =\'CHECKOUT\' AND EXTRACT(YEAR FROM rr.departure_time) = \''.$year.'\' ';
		$sql = '
					SELECT
						SUM(esid.quantity * esid.price) total
                        ,EXTRACT(MONTH FROM rr.departure_time) id
					FROM
                        extra_service_invoice_detail esid
                        INNER JOIN extra_service_invoice esi ON esi.id = esid.invoice_id
                        INNER JOIN reservation_room rr ON esi.reservation_room_id = rr.id
					WHERE '.$extra_cond.' AND esid.used = 1
                    GROUP BY EXTRACT(MONTH FROM rr.departure_time) 
                    '; 
		$extra_service = DB::fetch_all($sql);
        $months = false;
        for($i = 1; $i <= 12; $i++){
            $months[$i] = ((isset($extra_service[$i]) && $extra_service[$i]['total'])? $extra_service[$i]['total'] : 0);
        }
        return $months;
    }
    
    function process_data($data){
        $str = '[';
        foreach ($data as $key => $item ) {
            $str .= ceil($item/1000).',';
        }
        $str .= ']';
        return $str;
    }
    
    function draw(){
        $data = false;

        $months = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $year = Url::get('selected_year', date('Y',time()));
        $room_revenue = MonthlyRevenueChartDzungForm::get_housekepping($year);
        $extra_service = MonthlyRevenueChartDzungForm::get_amount_extra_service($year);
        $bar = MonthlyRevenueChartDzungForm::get_bar($year);
        $phone = MonthlyRevenueChartDzungForm::get_amount_phone($year);
        $minibar = MonthlyRevenueChartDzungForm::get_amount_minibar($year);
        $laundry = MonthlyRevenueChartDzungForm::get_amount_laundry($year);
        $spa = MonthlyRevenueChartDzungForm::get_spa($year);
        $other_service = MonthlyRevenueChartDzungForm::get_other_service($year);
        
        $data = '['
                .'{name:\''.Portal::language('telephone').'\',data:'.MonthlyRevenueChartDzungForm::process_data($phone).'},'
                .'{name:\''.Portal::language('laundry').'\',data:'.MonthlyRevenueChartDzungForm::process_data($laundry).'},'
                .'{name:\''.Portal::language('other_service').'\',data:'.MonthlyRevenueChartDzungForm::process_data($other_service).'},'
                .'{name:\''.Portal::language('extra_service').'\',data:'.MonthlyRevenueChartDzungForm::process_data($extra_service).'},'
                .'{name:\''.Portal::language('minibar').'\',data:'.MonthlyRevenueChartDzungForm::process_data($minibar).'},'
                .'{name:\''.Portal::language('bar').'\',data:'.MonthlyRevenueChartDzungForm::process_data($bar).'},'
                .'{name:\''.Portal::language('spa').'\',data:'.MonthlyRevenueChartDzungForm::process_data($spa).'},'
                .'{name:\''.Portal::language('room_revenue').'\',data:'.MonthlyRevenueChartDzungForm::process_data($room_revenue).'}'
                .']';
            
        $this->parse_layout('report', array(
            'list_month' => json_encode($months),
            'data' => $data,
            'year' => $year
        ));
    }
}
?>