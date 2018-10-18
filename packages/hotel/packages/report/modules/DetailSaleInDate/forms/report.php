<?php
class DetailSaleInDateForm extends Form
{
	function DetailSaleInDateForm()
	{
		Form::Form('DetailSaleInDateForm');
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
        $date_from = Date_time::to_orc_date($this->map['date_from']);
        $date_end = Date_time::to_orc_date($this->map['date_to']);
		if(Url::get('do_search'))
		{
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
            
            if($portal_id != 'ALL')
            {
                $cond.=' reservation.portal_id = \''.$portal_id.'\' '; 
            }
		  
			$cond .= ' AND ROOM_STATUS.IN_DATE <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND ROOM_STATUS.IN_DATE >= \''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\' 
                        AND ROOM_STATUS.STATUS = \'OCCUPIED\' 
					'.((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'').'
			';
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$sql='
				    SELECT 
                      room_status.in_date ||\'_\'|| room_type.name ||\'_\'|| customer.sale_code AS id,
                      room_status.in_date as in_date,
                      customer.sale_code as sale,
                      room_type.name as room_type,
                      count(reservation_room.room_id) as room_count,
                      sum(reservation_room.adult) as sum_adult,
                      sum(nvl(reservation_room.child,0)) as sum_child,
                      sum(case
                          when room_status.in_date = reservation_room.arrival_time
                          then 
                              (case
                               when (reservation_room.foc is not null OR reservation_room.foc_all=1)
                               then 0
                               else
                                  (case
                                   when RESERVATION_ROOM.net_price = 0
                                   then ((CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                   else
                                    ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)-NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                   end)
                                end
                               )
                           else
                              (case
                               when (reservation_room.foc is not null OR reservation_room.foc_all=1)
                               then 0
                               else   
                                  (case
                                   when RESERVATION_ROOM.net_price = 0
                                   then (CHANGE_PRICE*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0)*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                   else
                                    ((((CHANGE_PRICE/(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0))*(1-NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0))*(1+NVL(RESERVATION_ROOM.SERVICE_RATE,0)/100.0))*(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
                                   end)
                                end)
                            end) as price
                FROM
                      room_status
                      inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                      inner join room on reservation_room.room_id = room.id
                      inner join room_type on room.room_type_id = room_type.id
                      inner join room_level on room.room_level_id = room_level.id
                      inner join reservation on reservation_room.reservation_id = reservation.id
                      inner join customer on customer.ID = reservation.customer_ID
                WHERE
						'.$cond.'
                        and customer.group_id != \'ROOT\' and room_status.change_price!=0 AND ((room_level.is_virtual is null) OR (room_level.is_virtual = 0))
                GROUP BY
                      room_type.name, room_status.in_date, customer.sale_code
                ORDER BY
                      room_status.in_date DESC
			';
			$report->items = DB::fetch_all($sql);
            //System::debug($sql);
            //KimTan lấy thêm ei_lo_li và các dịch vụ co type bằng service
            $sql_service='
                select esid.id AS id,
                    esid.in_date,
                    esid.in_date ||\'_\'|| room_type.name ||\'_\'|| customer.sale_code as room_indate,
                    room_type.name as room_type,
                    customer.sale_code as sale,
                    esid.quantity,
                    esi.tax_rate,
                    esi.service_rate,
                    esi.net_price,
                    esid.price as change_price,
                    esid.percentage_discount,
                    esid.amount_discount,
                    esi.payment_type,
                    es.code,
                    rr.adult,
                    nvl(rr.child,0) as child,
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
                    left join room_type on room.room_type_id = room_type.id
                    WHERE
                    (es.code = \'LATE_CHECKIN\' 
                    OR es.code = \'EARLY_CHECKIN\' 
                    OR es.code=\'LATE_CHECKOUT\'
                    OR esi.payment_type = \'ROOM\')
                    AND esid.in_date >= \''.$date_from.'\' 
                    AND esid.in_date <= \''.$date_end.'\'
            ';
            $service_room = DB::fetch_all($sql_service);
            foreach($service_room as $key => $value)
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
                if($value['foc']!='' OR $value['foc_all']==1)
                {
                    $amount = 0;
                }
                $service_room[$key]['amount'] = System::calculate_number($amount);
            }
            foreach($service_room as $key1 => $value1)
            {
                if(isset($report->items[$value1['room_indate']]))
                {
                    $report->items[$value1['room_indate']]['price'] +=  $value1['amount'];
                    if($value1['code'] != '')
                    {
                        $report->items[$value1['room_indate']]['room_count'] += $value1['quantity'];
                        $report->items[$value1['room_indate']]['sum_adult'] += $value1['adult'];
                        $report->items[$value1['room_indate']]['sum_child'] += $value1['child'];
                    }
                }
                else
                {
                    $report->items[$value1['room_indate']]['id'] = $value1['room_indate'];
                    $report->items[$value1['room_indate']]['in_date'] = $value1['in_date'];
                    $report->items[$value1['room_indate']]['price'] =  $value1['amount'];
                    $report->items[$value1['room_indate']]['sale'] = $value1['sale'];
                    $report->items[$value1['room_indate']]['room_type'] = $value1['room_type'];
                    if($value1['code'] != '')
                    {
                        $report->items[$value1['room_indate']]['room_count'] = $value1['quantity'];
                        $report->items[$value1['room_indate']]['sum_adult'] = $value1['adult'];
                        $report->items[$value1['room_indate']]['sum_child'] = $value1['child'];
                    }
                }
            }
            ksort($report->items);
            //System::debug($report->items);
            $_REQUEST['sammary'] = array('total_room'=>'0','total_adult'=>'0','total_child'=>'0','total_price'=>'0');
            $_REQUEST['in_date'][0] = array('in_date'=>'','num'=>'0');
            $i = 0;
            $stt = 1;
            foreach($report->items as $key=>$value){
                $report->items[$key]['stt'] = $stt++;
                $report->items[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
                if($_REQUEST['in_date'][$i]['in_date']!=$report->items[$key]['in_date']){
                    $i+=1;
                    $_REQUEST['in_date'][$i]['in_date']=$report->items[$key]['in_date'];
                    $_REQUEST['in_date'][$i]['num']=1;
                }else{
                    $_REQUEST['in_date'][$i]['num']+=1;
                }
                $_REQUEST['sammary']['total_room'] += $value['room_count'];
                $_REQUEST['sammary']['total_adult'] += $value['sum_adult'];
                $_REQUEST['sammary']['total_child'] += $value['sum_child'];
                $_REQUEST['sammary']['total_price'] += $value['price'];
            }
            unset($_REQUEST['in_date'][0]);
            $this->parse_layout('report',array('items'=>$report->items));
		}
		else
		{
            //list TA
			$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
			//lấy list mã ssale
            //$sale = DB::fetch_all("select account_privilege_group.account_id as id, account_privilege_group.account_id as name from account_privilege_group where account_privilege_group.group_privilege_id='10'");
            //$this->map['sale_code_list'] = array('all'=>'--select-sale--') + String::get_list($sale);
            
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}			
	}
	
}
?>
