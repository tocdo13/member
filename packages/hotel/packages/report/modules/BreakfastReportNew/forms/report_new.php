<?php
class BreakfastReportForm extends Form
{
	function BreakfastReportForm()
	{
		Form::Form('BreakfastReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function draw()
	{
	    $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):date('d/m/Y');
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):date('d/m/Y');
        $this->map['from_time'] = Url::get('from_time')?Url::get('from_time'):'00:00';
        $this->map['to_time'] = Url::get('to_time')?Url::get('to_time'):date('H').':'.date('i');
        $_REQUEST['date_from'] = $this->map['date_from']; $from_date = Date_Time::to_time($this->map['date_from']);   

        $_REQUEST['date_to'] = $this->map['date_to']; $to_date = Date_Time::to_time($this->map['date_to']);
        
        $_REQUEST['from_time'] = $this->map['from_time'];
        
        $_REQUEST['to_time'] = $this->map['to_time'];
        
        $from_time_view = Date_Time::to_time($this->map['date_from']) + $this->calc_time($this->map['from_time']);                                
        
        $to_time_view = Date_Time::to_time($this->map['date_to']) + $this->calc_time($this->map['to_time']);
        
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            if($portal_id!="ALL")
            {
                $cond ="  reservation.portal_id ='".$portal_id."' ";
            }
            else
            {
                $cond=" 1=1 ";
            }
            
            if(Url::get('today'))
            {
                $this->map['today'] = 1;
                $_REQUEST['today'] = 1;
                $from_time_view = Date_Time::to_time(date('d/m/Y')) + $this->calc_time('00:00');
                $to_time_view = Date_Time::to_time(date('d/m/Y')) + $this->calc_time(date('H').':'.date('i'));
            }else{
                $this->map['today'] = 0;
                $_REQUEST['today'] = 0;
            }
            
            $cond .= " AND reservation_room.time_in<=$to_time_view AND reservation_room.time_out>=$from_time_view ";
			$cond.=" AND (reservation_room.status='CHECKIN' OR reservation_room.status='CHECKOUT') ";
		    
            $report = DB::fetch_all("
                                    SELECT
                                        reservation_room.id
                                        ,NVL(reservation_room.adult,0) as adult 
                                        ,NVL(reservation_room.child,0) as child
                                        ,reservation_room.time_in
                                        ,reservation_room.time_out
                                        ,DATE_TO_UNIX(reservation_room.arrival_time) as arrival_time
                                        ,DATE_TO_UNIX(reservation_room.departure_time) as departure_time
                                        ,CONCAT(
                                            CONCAT(customer.name,' '),
                                            CONCAT(
                                                reservation.note,
                                                CONCAT(' ',reservation_room.note)
                                            )
                                        ) as note
                                        ,room.name as room_name
                                        ,NVL(reservation_room.change_room_from_rr,0) as change_from
                                        ,NVL(reservation_room.change_room_to_rr,0) as change_to
                                        ,reservation_room.old_arrival_time
                                    FROM
                                        reservation_room
                    					inner join reservation on reservation.id=reservation_room.reservation_id
                    					left outer join customer on customer.id=reservation.customer_id
                    					left outer join room on room.id=reservation_room.room_id
                                    WHERE
                                        ".$cond."
                                        -- AND reservation_room.BREAKFAST = 1 
                                    ORDER BY 
                                        reservation_room.time_in DESC, reservation_room.time_out DESC, room.name DESC
                                    ");
            //System::debug($report);
            $stt = 1;
			foreach($report as $key=>$value)
            {
			     $report[$key]['date_in'] = date('H:i d/m/Y',$value['time_in']);
                 $report[$key]['date_out'] = date('H:i d/m/Y',$value['time_out']);
                 
                 
                 /** DEM NGAY **/
                 if($value['arrival_time']<=$from_date)
                 {
                    if($value['departure_time']>=$to_date)
                    {
                        $num_date = (($to_date-$from_date)/(24*3600)) + 1;
                        //$total_date = $this->count($num_date,date('H:i',$value['time_in']),date('H:i',$value['time_out']),$change_from,$change_to);
                    }
                    else
                    {
                        $num_date = (($value['departure_time']-$from_date)/(24*3600)) + 1;
                        //$total_date = $this->count($num_date,date('H:i',$value['time_in']),date('H:i',$value['time_out']),$change_from,$change_to);
                    }
                 }
                 else
                 {
                    if($value['departure_time']>=$to_date)
                    {
                        $num_date = (($to_date-$value['arrival_time'])/(24*3600)) + 1;
                        //$total_date = $this->count($num_date,date('H:i',$value['time_in']),date('H:i',$value['time_out']),$change_from,$change_to);
                    }
                    else
                    {
                        $num_date = (($value['departure_time']-$value['arrival_time'])/(24*3600)) + 1;
                        //$total_date = $this->count($num_date,date('H:i',$value['time_in']),date('H:i',$value['time_out']),$change_from,$change_to);
                    }
                 }
                 if($value['change_to']==0)
                 {
                    $change_to = false;
                 }
                 else
                 {
                    $change_to = true;
                    $num_date -= 1;
                 }
                 $report[$key]['num'] = $num_date;
                 /** ************************ **/
                 if($num_date<=0)
                 {
                    unset($report[$key]);
                 }
                 else
                 {
                    $child = DB::fetch_all("SELECT traveller.id,concat(concat(traveller.first_name,' '),traveller.last_name) as full_name FROM traveller inner join reservation_traveller on reservation_traveller.traveller_id=traveller.id inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id WHERE reservation_room.id=".$value['id']." ORDER BY reservation_traveller.arrival_time DESC, reservation_traveller.departure_time DESC");
                    $row = sizeof($child);
                    $report[$key]['row'] = $row;
                    $report[$key]['child_arr'] = $child;
                    $report[$key]['stt'] = $stt++;
                 }
			}
            //System::debug($report);
            $this->parse_layout('report_new',array('items'=>$report)+$this->map);
		}
		else
		{
            $this->map= array();
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
			$this->parse_layout('search',$this->map);	
		}
	}
	function print_all_pages(&$report)
	{
	   
	}
	function print_page($items, &$report, $page_no,$total_page,$summary)
	{
	   
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function count($num_date,$time_in,$time_out,$change_from,$change_to)
    {
        if($change_from==true OR $change_to==true)
        {
            if($change_to==true AND $change_from==false)
            {
                if($this->calc_time($time_in)>=$this->calc_time('11:00'))
                {
                    $num_date = $num_date - 1;
                }
                $num_date = $num_date - 1;
            }
            elseif($change_to==false AND $change_from==true)
            {
                if($this->calc_time($time_out)<=$this->calc_time('11:00'))
                {
                    $num_date = $num_date - 1;
                }
            }else{
                $num_date = $num_date - 1;
            }
        }
        else
        {
            if($this->calc_time($time_in)>=$this->calc_time('11:00'))
            {
                $num_date = $num_date - 1;
            }
            if($this->calc_time($time_out)<=$this->calc_time('11:00'))
            {
                $num_date = $num_date - 1;
            }
                
        }
        return $num_date;
    }
}
?>