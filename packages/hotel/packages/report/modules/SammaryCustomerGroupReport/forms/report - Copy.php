<?php
class SammaryCustomerGroupReportForm extends Form
{
	function SammaryCustomerGroupReportForm()
	{
		Form::Form('SammaryCustomerGroupReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
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
		if(Url::get('do_search'))
		{
		    require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;  
            $cond = '1=1';
            $cond_service ='';
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
            }
            else
            {
                $_REQUEST['portal_id'] = '';                       
            }
            if($portal_id != 'all')
            {
                $cond.=' and reservation.portal_id = \''.$portal_id.'\' ';
                $cond_service.=' and reservation.portal_id = \''.$portal_id.'\' ';
            }
            
            
            $datediff = abs((Date_Time::to_time(Url::get('date_to'))) - (Date_Time::to_time(Url::get('date_from'))))/86400 +1;
            
            $cond .= ' and reservation_room.status != \'BOOKED\' AND reservation_room.status != \'CANCEL\'';
            $cond .= ((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'');
            
            $cond .= ' and reservation_room.departure_time>=\''.Date_Time::to_orc_date(Url::get('date_from')).'\' and reservation_room.arrival_time<=\''.Date_Time::to_orc_date(Url::get('date_to')).'\' ';
            
			$sql = '
                SELECT 
                        reservation_room.id as id,
                        SUM(room_status.change_price) as total,
                        reservation_room.arrival_time,
                        reservation_room.departure_time,
                        reservation_room.net_price,
                        reservation_room.tax_rate,
                        reservation_room.service_rate,
                        reservation_room.foc,
                        NVL(reservation_room.reduce_balance,0) as reduce_balance,
                        NVL(reservation_room.reduce_amount,0) as reduce_amount,
                        reservation_room.adult,
                        reservation_room.child,
                        NVL(reservation_room.change_room_from_rr,0) as change_room_from_rr,
                        NVL(reservation_room.change_room_to_rr,0) as change_room_to_rr,
                        customer.name as customer_name,
                        customer_group.name as group_name,
                        customer.id as customer_id
                FROM
                      room_status
                      inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                      inner join room on reservation_room.room_id = room.id
                      inner join room_level on room_level.id = room.room_level_id
                      inner join room_type on room.room_type_id = room_type.id
                      inner join reservation on reservation_room.reservation_id = reservation.id
                      inner join customer on customer.ID = reservation.customer_ID
                      inner join customer_group on customer_group.id = customer.group_id
                WHERE
                    '.$cond.'
                      
                      and customer.group_id != \'ROOT\'
                      and reservation_room.foc_all=0
                      and (room_level.is_virtual is null or room_level.is_virtual = 0)
                GROUP BY
                    reservation_room.id,
                    customer.name,
                    customer.id,
                    customer_group.name,
                    reservation_room.adult,
                    reservation_room.child,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    reservation_room.foc,
                    reservation_room.reduce_balance,
                    reservation_room.reduce_amount,
                    reservation_room.change_room_from_rr,
                    reservation_room.change_room_to_rr
                ORDER BY
                      customer_group.name DESC
			 ';
			$detail_items = DB::fetch_all($sql);
            foreach($detail_items as $k => $v)
            {
                $detail_items[$k]['total']=0;
                $detail_items[$k]['sum_room']=0;
                $sql="
                        SELECT sum(
                                case
                                when room_status.in_date = reservation_room.arrival_time
                                then 
                                    (case
                                     when RESERVATION_ROOM.net_price = 0
                                     then ((CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                     else
                                      ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                    end) 
                                else
                                    (case
                                     when RESERVATION_ROOM.net_price = 0
                                     then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                     else
                                      ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                    end)
                                end) as total,
                                count(room_status.in_date) as sum_room
                                
                        FROM room_status
                        INNER JOIN reservation_room on reservation_room.id = ROOM_STATUS.reservation_room_id
                        WHERE                      
                            reservation_room.id=".$k.' 
                            and ROOM_STATUS.in_date>=\''.(Date_Time::to_orc_date(Url::get('date_from'))).'\' 
                            and ROOM_STATUS.in_date<=\''.(Date_Time::to_orc_date(Url::get('date_to'))).'\'
                            and ROOM_STATUS.change_price !=0
                        ';
                   $detail_items[$k]['total'] += DB::fetch($sql,'total');
                   $detail_items[$k]['sum_room'] += DB::fetch($sql,'sum_room');  
                      
            }
            
            $report->items = array();
            
       // system::debug($report->items);
        $cond_service.=' AND to_char(extra_service_invoice_detail.in_date,\'dd/mm/yyyy\')  >=\''.($this->map['date_from']).'\'
			    AND to_char(extra_service_invoice_detail.in_date,\'dd/mm/yyyy\') <=\''.(($this->map['date_to'])).'\'
            ';
        $sql = '
            SELECT
                extra_service_invoice.id,
                customer.name as company_name,
                customer.id,
                sum(extra_service_invoice_detail.quantity) as quantity,
                sum(extra_service_invoice.total_amount) as price_service
            FROM
                extra_service_invoice_detail
                inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                inner join reservation on reservation_room.reservation_id = reservation.id
				INNER JOIN  room_level on room_level.id = reservation_room.room_level_id
                inner join customer on customer.ID = reservation.customer_ID
                inner join customer_group on customer_group.id = customer.group_id
            WHERE
                (extra_service.code = \'LATE_CHECKOUT\'
                OR extra_service.code = \'LATE_CHECKIN\'
                OR extra_service.code = \'EARLY_CHECKIN\'
                OR extra_service_invoice.payment_type = \'ROOM\')
                AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                AND reservation_room.foc_all = 0
                '.$cond_service.' 
            GROUP BY 
                extra_service_invoice.id,
                customer.name,
                customer.id    
                '
                
        ;
       
        $record = DB::fetch_all($sql);
        //system::Debug($record);
        $count_room =DB::fetch('
                                    select count(room.id) as id
                                    from room
                                        inner join room_level ON room.room_level_id = room_level.id
                                    WHERE room_level.is_virtual = 0
                                          AND room.portal_id = \''.$portal_id.'\'
                                ');
             
            $_REQUEST['sammary'] = array(
                                        'total_room'=>'0',
                                        'total_adult'=>'0',
                                        'total_child'=>'0',
                                        'total_price'=>'0'
                                        );
            $_REQUEST['group_total'][0] = array('group_name'=>'','group_total_room'=>'0','group_total_adult'=>'0','group_total_child'=>'0','group_total_price'=>'0');
            $i = 0;
            
            //system::debug($report->items);
            foreach($report->items as $key=>$value)
            {                
                $_REQUEST['sammary']['total_adult'] += $value['sum_adult'];
                $_REQUEST['sammary']['total_child'] += $value['sum_child'];
                $_REQUEST['sammary']['total_price'] += $value['price'];
                $report->items[$key]['price'] = $value['price'];                
                $_REQUEST['sammary']['total_room'] += $report->items[$key]['room_count'];
                
                foreach($record as $key2=>$value2)
                {
                    if(isset($report->items[$key2]['customer_id']))
                    {
                        $_REQUEST['sammary']['total_price'] += $value2['price_service'];
                        $report->items[$key2]['price'] += $value2['price_service'];                   
                        $report->items[$key2]['room_count'] += $value2['quantity'];
                    }
                    
                }
                if($_REQUEST['group_total'][$i]['group_name']!=$value['group_name'])
                {
                    $i+=1;
                    $_REQUEST['group_total'][$i]['group_name'] = $value['group_name'];
                    $_REQUEST['group_total'][$i]['group_total_room'] = $report->items[$key]['room_count'];
                    $_REQUEST['group_total'][$i]['group_total_adult'] = $value['sum_adult'];
                    $_REQUEST['group_total'][$i]['group_total_child'] = $value['sum_child'];
                    $_REQUEST['group_total'][$i]['group_total_price'] = $report->items[$key]['price'];                    
                }
                else
                {
                    $_REQUEST['group_total'][$i]['group_name'] = $value['group_name'];                    
                    $_REQUEST['group_total'][$i]['group_total_room'] += $report->items[$key]['room_count'];
                    $_REQUEST['group_total'][$i]['group_total_adult'] += $value['sum_adult'];
                    $_REQUEST['group_total'][$i]['group_total_child'] += $value['sum_child'];
                    $_REQUEST['group_total'][$i]['group_total_price'] += $report->items[$key]['price'];   
                }   
            }
            
            unset($_REQUEST['group_total'][0]);
            foreach($_REQUEST['group_total'] as $k => $v)
            {
                if($_REQUEST['group_total'][$k]['group_total_room']==0 and $_REQUEST['group_total'][$k]['group_total_price']==0)
                //unset($_REQUEST['group_total'][$k]);
                {
                    
                }
            }
            
            $this->parse_layout('report',array('items'=>$report->items,'date_diff'=>$datediff,'count_room'=>$count_room['id']));
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