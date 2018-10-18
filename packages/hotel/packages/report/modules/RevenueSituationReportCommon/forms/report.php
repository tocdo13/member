<?php
class RevenueSituationReportCommonForm extends Form
{
	function RevenueSituationReportCommonForm()
	{
		Form::Form('RevenueSituationReportCommonForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
	function draw()
	{
	   
        $this->map = array();
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        $this->map['date'] = Url::get('date')?Url::get('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        $month = date('m',Date_Time::to_time($this->map['date']));
        $year = date('Y',Date_Time::to_time($this->map['date']));
        $this->map['year'] = $year;
        $this->map['last_year'] = $year-1;
        $date = Date_Time::to_orc_date($this->map['date']);
        $begin_month = Date_Time::to_orc_date('1/'.$month.'/'.$year);
        $begin_month_last_year = Date_Time::to_orc_date('1/'.$month.'/'.($year-1));
        $date_last_year = Date_Time::to_orc_date(date('d',Date_Time::to_time($this->map['date'])).'/'.$month.'/'.($year-1));
        
        //check condition portal selected
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $_REQUEST['portal_id'] = PORTAL_ID;
            $portal_id = PORTAL_ID;
        }
        
        /*-----room------*/
        $query_total_revenue = "
        select sum (
            case
             when RESERVATION_ROOM.net_price = 0
             then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0)
             else
              CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0)
            end) as total
        from ROOM_STATUS
        inner join RESERVATION on RESERVATION.ID = ROOM_STATUS.RESERVATION_ID
        inner join RESERVATION_ROOM on RESERVATION_ROOM.ID = ROOM_STATUS.RESERVATION_ROOM_ID
        WHERE 
        ROOM_STATUS.STATUS = 'OCCUPIED'
        AND (ROOM_STATUS.HOUSE_STATUS != 'HOUSEUSE' OR ROOM_STATUS.HOUSE_STATUS is null)
        AND (RESERVATION_ROOM.FOC is null OR RESERVATION_ROOM.FOC_ALL = 0)";
        //check condition date selected
        $cond = ' and 1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        if(Url::get('date'))
        {
            $in_date_cond .= " AND room_status.IN_DATE = '".$date."' AND (IN_DATE < DEPARTURE_TIME OR  DEPARTURE_TIME=ARRIVAL_TIME)";
            $in_month_cond .= " AND room_status.IN_DATE <= '".$date."' AND room_status.IN_DATE >= '".$begin_month."'";
            $last_year_cond .= " AND room_status.IN_DATE <= '".$date_last_year."' AND room_status.IN_DATE >= '".$begin_month_last_year."'";
        }
        //check portal date selected
        if($portal_id != 'ALL')
        {
            $cond .= " AND reservation.PORTAL_ID = '".$portal_id."'";
        }
        
        //echo $query_total_revenue.$cond.$in_date_cond;
        //exit();
        $this->map['TOTAL_ROOM_IN_DATE'] = DB::fetch($query_total_revenue.$cond.$in_date_cond,'total');
        $this->map['TOTAL_ROOM_IN_MONTH'] = DB::fetch($query_total_revenue.$cond.$in_month_cond,'total');
        $this->map['TOTAL_ROOM_LAST_YEAR'] = DB::fetch($query_total_revenue.$cond.$last_year_cond,'total');
        
 
        $data_static[1]['total_date'] = $this->map['TOTAL_ROOM_IN_DATE'];
        $data_static[1]['total_month'] = $this->map['TOTAL_ROOM_IN_MONTH'];
        
        /*-----housekeeping------*/
        $query_total_housekeepng = "
        select sum(TOTAL) as total
        from HOUSEKEEPING_INVOICE
        where ";
        $cond = '1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        
        if($portal_id != 'ALL')
        {
            $cond .= " AND PORTAL_ID = '".$portal_id."'";
        }
        if(Url::get('date'))
        {
            $in_date_cond .= "and FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) = '".$date."'";
            $in_month_cond .= "and FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) <= '".$date."' AND FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) >= '".$begin_month."'";
            $last_year_cond .= "and FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) <= '".$date_last_year."' AND FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME) >= '".$begin_month_last_year."'";
        }
        
        $this->map['TOTAL_HOUSEKEEPING_IN_DATE'] = DB::fetch($query_total_housekeepng.$in_date_cond." and ".$cond,'total');
        $this->map['TOTAL_HOUSEKEEPING_IN_MONTH'] = DB::fetch($query_total_housekeepng.$in_month_cond." and ".$cond,'total');
        $this->map['TOTAL_HOUSEKEEPING_LAST_YEAR'] = DB::fetch($query_total_housekeepng.$last_year_cond." and ".$cond,'total');
        
        $data_static[2]['total_date'] = $this->map['TOTAL_HOUSEKEEPING_IN_DATE'];
        $data_static[2]['total_month'] = $this->map['TOTAL_HOUSEKEEPING_IN_MONTH'];
        
        /*-----extra service------*/
        $query_total_extraservice = "
        select sum(TOTAL_AMOUNT) as total
        from EXTRA_SERVICE_INVOICE
        where ";
        
        $cond = '1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        
        if($portal_id != 'ALL')
        {
            $cond .= " AND PORTAL_ID = '".$portal_id."'";
        }
        if(Url::get('date'))
        {
            $in_date_cond .= "and FROM_UNIXTIME(EXTRA_SERVICE_INVOICE.TIME) = '".$date."'";
            $in_month_cond .= "and FROM_UNIXTIME(EXTRA_SERVICE_INVOICE.TIME) <= '".$date."' AND FROM_UNIXTIME(EXTRA_SERVICE_INVOICE.TIME) >= '".$begin_month."'";
            $last_year_cond .= "and FROM_UNIXTIME(EXTRA_SERVICE_INVOICE.TIME) <= '".$date_last_year."' AND FROM_UNIXTIME(EXTRA_SERVICE_INVOICE.TIME) >= '".$begin_month_last_year."'";
        }
        
        $this->map['TOTAL_EXTRASERVICE_IN_DATE'] = DB::fetch($query_total_extraservice.$in_date_cond." and ".$cond,'total');
        $this->map['TOTAL_EXTRASERVICE_IN_MONTH'] = DB::fetch($query_total_extraservice.$in_month_cond." and ".$cond,'total');
        $this->map['TOTAL_EXTRASERVICE_LAST_YEAR'] = DB::fetch($query_total_extraservice.$last_year_cond." and ".$cond,'total');
        
        $data_static[3]['total_date'] = $this->map['TOTAL_EXTRASERVICE_IN_DATE'];
        $data_static[3]['total_month'] = $this->map['TOTAL_EXTRASERVICE_IN_MONTH'];
        
        /*-----spa------*/
        $query_total_spa = "
        select SUM((PRICE*QUANTITY*((100-NVL(DISCOUNT,0))/100.0)*(1+NVL(TAX,0)/100.0))) as total
        from MASSAGE_PRODUCT_CONSUMED
        inner join MASSAGE_RESERVATION_ROOM on MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID = MASSAGE_RESERVATION_ROOM.ID
        where MASSAGE_PRODUCT_CONSUMED.STATUS= 'CHECKOUT' and ";
        
        $cond = '1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        
        if($portal_id != 'ALL')
        {
            $cond .= " AND PORTAL_ID = '".$portal_id."'";
        }
        if(Url::get('date'))
        {
            $in_date_cond .= "and FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) = '".$date."'";
            $in_month_cond .= "and FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) <= '".$date."' AND FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) >= '".$begin_month."'";
            $last_year_cond .= "and FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) <= '".$date_last_year."' AND FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.TIME_OUT) >= '".$begin_month_last_year."'";
        }
        
        $this->map['TOTAL_SPA_IN_DATE'] = DB::fetch($query_total_spa.$in_date_cond." and ".$cond,'total');
        $this->map['TOTAL_SPA_IN_MONTH'] = DB::fetch($query_total_spa.$in_month_cond." and ".$cond,'total');
        $this->map['TOTAL_SPA_LAST_YEAR'] = DB::fetch($query_total_spa.$last_year_cond." and ".$cond,'total');
        
        $data_static[4]['total_date'] = $this->map['TOTAL_SPA_IN_DATE'];
        $data_static[4]['total_month'] = $this->map['TOTAL_SPA_IN_MONTH'];
        
        /*-----sales------*/
        $query_total_sale = "
        select sum(TOTAL) as total
        from VE_RESERVATION
        where ";
        
        $cond = '1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        
        if($portal_id != 'ALL')
        {
            $cond .= " AND PORTAL_ID = '".$portal_id."'";
        }
        if(Url::get('date'))
        {
            $in_date_cond .= "and FROM_UNIXTIME(VE_RESERVATION.TIME) = '".$date."'";
            $in_month_cond .= "and FROM_UNIXTIME(VE_RESERVATION.TIME) <= '".$date."' AND FROM_UNIXTIME(VE_RESERVATION.TIME) >= '".$begin_month."'";
            $last_year_cond .= "and FROM_UNIXTIME(VE_RESERVATION.TIME) <= '".$date_last_year."' AND FROM_UNIXTIME(VE_RESERVATION.TIME) >= '".$begin_month_last_year."'";
        }
        
        $this->map['TOTAL_SALE_IN_DATE'] = DB::fetch($query_total_sale.$in_date_cond." and ".$cond,'total');
        $this->map['TOTAL_SALE_IN_MONTH'] = DB::fetch($query_total_sale.$in_month_cond." and ".$cond,'total');
        $this->map['TOTAL_SALE_LAST_YEAR'] = DB::fetch($query_total_sale.$last_year_cond." and ".$cond,'total');
             
        $data_static[5]['name'] = '[[.revenue.]] [[.sales.]]';
        $data_static[5]['total_date'] = $this->map['TOTAL_SALE_IN_DATE'];
        $data_static[5]['total_month'] = $this->map['TOTAL_SALE_IN_MONTH'];
        
        /*-----TICKET------*/
        $query_total_ticket = "
        select sum(TOTAL) as total
        from TICKET_INVOICE
        where ";
        
        $cond = '1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        
        if($portal_id != 'ALL')
        {
            $cond .= " AND PORTAL_ID = '".$portal_id."'";
        }
        if(Url::get('date'))
        {
            $in_date_cond .= "and FROM_UNIXTIME(TICKET_INVOICE.TIME) = '".$date."'";
            $in_month_cond .= "and FROM_UNIXTIME(TICKET_INVOICE.TIME) <= '".$date."' AND FROM_UNIXTIME(TICKET_INVOICE.TIME) >= '".$begin_month."'";
            $last_year_cond .= "and FROM_UNIXTIME(TICKET_INVOICE.TIME) <= '".$date_last_year."' AND FROM_UNIXTIME(TICKET_INVOICE.TIME) > '".$begin_month_last_year."'";
        }
        
        $this->map['TOTAL_TICKET_IN_DATE'] = DB::fetch($query_total_ticket.$in_date_cond." and ".$cond,'total');
        $this->map['TOTAL_TICKET_IN_MONTH'] = DB::fetch($query_total_ticket.$in_month_cond." and ".$cond,'total');
        $this->map['TOTAL_TICKET_LAST_YEAR'] = DB::fetch($query_total_ticket.$last_year_cond." and ".$cond,'total');
        
        $data_static[6]['name'] = '[[.revenue.]] [[.ticket.]]';
        $data_static[6]['total_date'] = $this->map['TOTAL_TICKET_IN_DATE'];
        $data_static[6]['total_month'] = $this->map['TOTAL_TICKET_IN_MONTH'];
        
        /*-----BAR------*/
        $query_total_bar = "
        select sum(TOTAL) as total
        from BAR_RESERVATION
        where STATUS='CHECKOUT' AND ";
        
        $cond = '1=1 ';
        $in_month_cond = $cond;
        $last_year_cond = $cond;
        $in_date_cond = $cond;
        
        if($portal_id != 'ALL')
        {
            $cond .= " AND PORTAL_ID = '".$portal_id."'";
        }
        if(Url::get('date'))
        {
            $in_date_cond .= "and FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) = '".$date."'";
            $in_month_cond .= "and FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) <= '".$date."' AND FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) >= '".$begin_month."'";
            $last_year_cond .= "and FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) <= '".$date_last_year."' AND FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME) > '".$begin_month_last_year."'";
        }
        
        $this->map['TOTAL_BAR_IN_DATE'] = DB::fetch($query_total_bar.$in_date_cond." and ".$cond,'total');
        $this->map['TOTAL_BAR_IN_MONTH'] = DB::fetch($query_total_bar.$in_month_cond." and ".$cond,'total');
        $this->map['TOTAL_BAR_LAST_YEAR'] = DB::fetch($query_total_bar.$last_year_cond." and ".$cond,'total');
        
        $data_static[7]['name'] = '[[.revenue.]] [[.bar.]]';
        $data_static[7]['total_date'] = $this->map['TOTAL_BAR_IN_DATE'];
        $data_static[7]['total_month'] = $this->map['TOTAL_BAR_IN_MONTH'];
        
        /*-----TOTAL------*/
        $this->map['TOTAL_IN_DATE'] = $this->map['TOTAL_BAR_IN_DATE'] 
                                    + $this->map['TOTAL_TICKET_IN_DATE'] 
                                    + $this->map['TOTAL_SALE_IN_DATE']
                                    + $this->map['TOTAL_SPA_IN_DATE']
                                    +$this->map['TOTAL_EXTRASERVICE_IN_DATE']
                                    +$this->map['TOTAL_HOUSEKEEPING_IN_DATE']
                                    +$this->map['TOTAL_ROOM_IN_DATE'];
        $this->map['TOTAL_IN_MONTH'] = $this->map['TOTAL_BAR_IN_MONTH'] 
                                    + $this->map['TOTAL_TICKET_IN_MONTH'] 
                                    + $this->map['TOTAL_SALE_IN_MONTH']
                                    + $this->map['TOTAL_SPA_IN_MONTH']
                                    +$this->map['TOTAL_EXTRASERVICE_IN_MONTH']
                                    +$this->map['TOTAL_HOUSEKEEPING_IN_MONTH']
                                    +$this->map['TOTAL_ROOM_IN_MONTH'];
        $this->map['TOTAL_LAST_YEAR'] = $this->map['TOTAL_BAR_LAST_YEAR'] 
                                    + $this->map['TOTAL_TICKET_LAST_YEAR'] 
                                    + $this->map['TOTAL_SALE_LAST_YEAR']
                                    + $this->map['TOTAL_SPA_LAST_YEAR']
                                    +$this->map['TOTAL_EXTRASERVICE_LAST_YEAR']
                                    +$this->map['TOTAL_HOUSEKEEPING_LAST_YEAR']
                                    +$this->map['TOTAL_ROOM_LAST_YEAR'];
                                    
        $this->map['items'] = String::array2js($data_static);
        
        $this->parse_layout('report',$this->map);
	}
}
?>