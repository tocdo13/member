<?php
class SammaryCustomerGroupReportForm extends Form
{
	function SammaryCustomerGroupReportForm()
	{
		Form::Form('SammaryCustomerGroupReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{      
        $this->map = array();
        $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):('01/'.date('m/Y'));
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
        $_REQUEST['date_from'] = $this->map['date_from'];
        $_REQUEST['date_to'] = $this->map['date_to'];
        $this->line_per_page = Url::get('line_per_page')?Url::get('line_per_page'):32;
        $this->no_of_page = Url::get('no_of_page')?Url::get('no_of_page'):50;
        $this->start_page = Url::get('start_page')?Url::get('start_page'):1;
        //System::debug($this->map);
		if(Url::get('do_search'))
		{
		    require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;  
            $cond = '';
            if(Url::get('portal_id'))
            {
                $portal_id = Url::get('portal_id');
                $_REQUEST['portal_id'] = $portal_id;
            }
            else
            {
                $portal_id = PORTAL_ID;
                $_REQUEST['portal_id'] = PORTAL_ID;                       
            }
           
            if(Url::get('group_id'))
            {
                $_REQUEST['group_id'] = Url::get('group_id');
                $cond .= " AND customer.group_id ='".Url::get('group_id')."'";
            }
            else
            {
                $_REQUEST['portal_id'] = '';                       
            }
            if($portal_id != 'all')
            {
                $cond.=' and r.portal_id = \''.$portal_id.'\' ';
            }
            $date_from = Date_Time::to_orc_date($this->map['date_from']);
            $date_end = Date_Time::to_orc_date($this->map['date_to']);
            $sql = '
				SELECT 
					rs.change_price,
					rs.reservation_room_id ,
					rr.tax_rate, 
					rr.service_rate,
                    rr.net_price,
                    NVL(rr.adult,0) as adult,
                    NVL(rr.child,0) as child,
					rs.in_date,
                    date_to_unix(rs.in_date) as time_in_date,      
					rs.id,
					rr.arrival_time,
					rr.departure_time,
					rr.price,
                    rr.time_in, 
                    customer.id as customer_id,
                    customer.name as customer_name,
					customer_group.id as reservation_type_id,
					customer_group.name as reservation_type_name,
                    rr.reduce_balance,
                    rr.reduce_amount,
                    rr.foc,
                    rr.foc_all,
                    nvl(rr.change_room_from_rr,0) as change_room_from_rr,
                    nvl(rr.change_room_to_rr,0) as change_room_to_rr,
                    from_unixtime(rr.old_arrival_time) as old_arival_date
				FROM 
					room_status rs 
    				INNER JOIN  reservation_room rr ON rr.id = rs.reservation_room_id 
    				INNER JOIN  reservation r on rr.reservation_id = r.id
                    INNER JOIN  customer on r.customer_id=customer.id
                    INNER JOIN  customer_group on customer.group_id=customer_group.id
    				left JOIN  room ON room.id = rr.room_id
    				left JOIN  room_level on room_level.id = rr.room_level_id
				WHERE 
					(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
					AND rs.in_date >= \''.$date_from.'\' 
					AND rs.in_date <= \''.$date_end.'\'
				'.$cond.' ORDER BY customer_group.id,customer_id,rs.in_date';
            $room_totals = DB::fetch_all($sql);
            $orcl = '
                SELECT
                    esid.id,
                    esid.in_date,
                    esid.quantity,
                    esi.tax_rate,
                    esi.service_rate,
                    esi.net_price,
                    customer.id as customer_id,
                    customer.name as customer_name,
                    customer_group.id as reservation_type_id,
                    customer_group.name as reservation_type_name,
                    esid.price as change_price,
                    esid.percentage_discount,
                    esid.amount_discount,
                    esi.payment_type,
                    es.code,
                    rr.foc_all,
                    rr.foc
                FROM
                    extra_service_invoice_detail esid
                    INNER JOIN  extra_service_invoice esi ON esid.invoice_id=esi.id
                    INNER JOIN  reservation_room rr ON rr.id = esi.reservation_room_id
                    INNER JOIN  reservation r on rr.reservation_id = r.id
                    INNER JOIN  customer on r.customer_id=customer.id
                    INNER JOIN  customer_group on customer.group_id=customer_group.id
                    INNER JOIN  extra_service es ON es.id = esid.service_id
                    left JOIN  room ON room.id = rr.room_id
                    left JOIN  room_level on room_level.id = rr.room_level_id
                WHERE
                    (es.code = \'LATE_CHECKIN\' 
                    OR es.code = \'EARLY_CHECKIN\' 
                    OR es.code=\'LATE_CHECKOUT\'
                    OR esi.payment_type = \'ROOM\')
                    AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                    AND esid.in_date >= \''.$date_from.'\' 
                    AND esid.in_date <= \''.$date_end.'\' '.$cond.'
                ORDER BY customer_group.id,customer_id,esid.in_date
                ';
            $extra_ser_room = DB::fetch_all($orcl);
            //System::debug($extra_ser_room);
            $result = array();
            foreach($room_totals as $key => $value)
            {
                //check net price
                if($value['net_price'])
                    $value['change_price'] = $value['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                //GIAM GIA %
                $value['change_price'] *= (1-$value['reduce_balance']/100);
                //GIAM GIA SOTIEN
                if($value['in_date'] == $value['arrival_time'])
                    $value['change_price'] -= $value['reduce_amount'];
                //check option thue 
                $amount = $value['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
                $amount = number_format($amount);
                $amount = System::calculate_number($amount);
                if($value['foc']!='' OR $value['foc_all']==1)
                {
                    $amount=0;
                }
                $night = 1;
                $adult = $value['adult'];
                $child = $value['child'];
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_from_rr']==0 AND $value['change_room_to_rr']==0 AND $value['time_in'] < ($value['time_in_date']+(6*3600)) )
                {
                    $night = 0;
                    $adult = 0;
                    $child = 0;
                }
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_from_rr']!=0 AND $value['change_room_to_rr']==0 AND $value['old_arival_date'] < ($value['time_in_date']+(6*3600)) )
                {
                    $night = 0;
                    $adult = 0;
                    $child = 0;
                }
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_to_rr']==0 AND $value['change_room_from_rr']!=0 AND $value['old_arival_date']!=$value['departure_time'])
                {
                    $night = 0;
                    $adult = 0;
                    $child = 0;
                }
                /** doi phong trong ngay **/
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_to_rr']!=0)
                {
                    $night = 0;
                    $amount = 0;
                    $adult = 0;
                    $child = 0;
                }
                /** ngay cuoi cung trong chang **/
                if( $value['arrival_time']!=$value['departure_time'] AND $value['in_date'] == $value['departure_time'])
                {
                    $night = 0;
                    $amount = 0;
                    $adult = 0;
                    $child = 0;
                }
                if(!isset($result[$value['reservation_type_id']]))
                {
                    $result[$value['reservation_type_id']]['id'] = $value['reservation_type_id'];
                    $result[$value['reservation_type_id']]['name'] = $value['reservation_type_name'];
                    $result[$value['reservation_type_id']]['total_room'] = $night;
                    $result[$value['reservation_type_id']]['total_adult'] = $adult;
                    $result[$value['reservation_type_id']]['total_child'] = $child;
                    $result[$value['reservation_type_id']]['total_amount'] = $amount;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['id'] = $value['customer_id'];
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['name'] = $value['customer_name'];
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_room'] = $night;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_adult'] = $adult;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_child'] = $child;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_amount'] = $amount;
                }
                else
                {
                    $result[$value['reservation_type_id']]['total_room'] += $night;
                    $result[$value['reservation_type_id']]['total_adult'] += $adult;
                    $result[$value['reservation_type_id']]['total_child'] += $child;
                    $result[$value['reservation_type_id']]['total_amount'] += $amount;
                    if(!isset($result[$value['reservation_type_id']]['child'][$value['customer_id']]))
                    {
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['id'] = $value['customer_id'];
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['name'] = $value['customer_name'];
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_room'] = $night;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_adult'] = $adult;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_child'] = $child;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_amount'] = $amount;
                    }
                    else
                    {
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_room'] += $night;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_adult'] += $adult;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_child'] += $child;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_amount'] += $amount;
                    }
                }
                
            }
            
            //CONG THEM TIEN DICH VU MO RONG
            foreach($extra_ser_room as $key => $value)
            {
                //check net price
                if($value['net_price'])
                    $value['change_price'] = $value['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                // giam gia %
                $value['change_price'] = $value['change_price'] - ($value['change_price']*$value['percentage_discount']/100);
                // giam gia so tien
                $value['change_price'] = $value['change_price'] - $value['amount_discount'];
                //check option thue 
                $amount = $value['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
                $amount = $amount*$value['quantity'];
                $amount = number_format($amount);
                $amount = System::calculate_number($amount);
                if($value['foc_all']==1)
                {
                    $amount = 0;
                }
                if(($value['code']=='LATE_CHECKIN' OR $value['code']=='EARLY_CHECKIN' OR $value['code']=='LATE_CHECKOUT'))
                {
                    $night = $value['quantity'];
                }
                else
                {
                    $night = 0;
                }
                $adult = 0;
                $child = 0;
                if(!isset($result[$value['reservation_type_id']]))
                {
                    $result[$value['reservation_type_id']]['id'] = $value['reservation_type_id'];
                    $result[$value['reservation_type_id']]['name'] = $value['reservation_type_name'];
                    $result[$value['reservation_type_id']]['total_room'] = $night;
                    $result[$value['reservation_type_id']]['total_adult'] = $adult;
                    $result[$value['reservation_type_id']]['total_child'] = $child;
                    $result[$value['reservation_type_id']]['total_amount'] = $amount;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['id'] = $value['customer_id'];
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['name'] = $value['customer_name'];
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_room'] = $night;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_adult'] = $adult;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_child'] = $child;
                    $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_amount'] = $amount;
                }
                else
                {
                    $result[$value['reservation_type_id']]['total_room'] += $night;
                    $result[$value['reservation_type_id']]['total_adult'] += $adult;
                    $result[$value['reservation_type_id']]['total_child'] += $child;
                    $result[$value['reservation_type_id']]['total_amount'] += $amount;
                    if(!isset($result[$value['reservation_type_id']]['child'][$value['customer_id']]))
                    {
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['id'] = $value['customer_id'];
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['name'] = $value['customer_name'];
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_room'] = $night;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_adult'] = $adult;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_child'] = $child;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_amount'] = $amount;
                    }
                    else
                    {
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_room'] += $night;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_adult'] += $adult;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_child'] += $child;
                        $result[$value['reservation_type_id']]['child'][$value['customer_id']]['total_amount'] += $amount;
                    }
                }
            }
      
            $this->parse_layout('report',array('items'=>$result));
		}
		else
		{
			$this->map['group_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::fetch_all('SELECT ID,NAME FROM CUSTOMER_GROUP WHERE '.IDStructure::child_cond(ID_ROOT,1).''));
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}	
	}
	
}
?>