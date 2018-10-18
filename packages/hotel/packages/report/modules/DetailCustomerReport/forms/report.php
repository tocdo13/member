<?php
class DetailCustomerReportForm extends Form
{
	function DetailCustomerReportForm()
	{
		Form::Form('DetailCustomerReportForm');
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
        $date_from = Date_Time::to_orc_date($this->map['date_from']);
        $date_end = Date_Time::to_orc_date($this->map['date_to']);
        
		if(Url::get('do_search'))
		{
            $cond = '1=1';
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
                $cond.=' and r.portal_id = \''.$portal_id.'\' '; 
            }
		  
			$cond .= ' AND rs.IN_DATE <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND rs.IN_DATE >= \''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\' 
                        AND (rs.status = \'OCCUPIED\' OR rs.status =\'BOOKED\')
					'.((URL::get('customer_id'))?' AND r.customer_id = '.Url::get('customer_id').'':'').'
			';
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$sql = '
				SELECT 
					rs.change_price,
					rs.reservation_room_id ,
                    customer.name ||\'_\'|| room_type.name ||\'_\'|| rs.in_date ||\'_\'|| customer.id as customer_date,
					rr.tax_rate, 
					rr.service_rate,
                    rr.net_price,
                    NVL(rr.adult,0) as adult,
                    NVL(rr.child,0) as child,
                    NVL(rr.child_5,0) as child_5,
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
                    customer.sale_code as sale,
                    room_type.name as room_type,
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
                    left join room_type on room.room_type_id = room_type.id
    				left JOIN  room_level on room_level.id = rr.room_level_id
				WHERE 
                '.$cond.'
                and (room_level.is_virtual is null or room_level.is_virtual = 0)
				 ORDER BY customer_group.id,customer_id,rs.in_date';
			$report->items = DB::fetch_all($sql);                
            $result = array();
            
            foreach($report->items as $key=>$value)
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
                $child_under_five = $value['child_5'];
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_from_rr']==0 AND $value['change_room_to_rr']==0 AND $value['time_in'] < ($value['time_in_date']+(6*3600)) )
                {
                    $night = 0;
                    $adult = 0;
                    $child = 0;
                    $child_under_five = 0;
                }
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_from_rr']!=0 AND $value['change_room_to_rr']==0  AND $value['old_arival_date'] < ($value['time_in_date']+(6*3600)) )
                {
                    $night = 0;
                    $adult = 0;
                    $child = 0;
                    $child_under_five = 0;
                }
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_to_rr']==0 AND $value['change_room_from_rr']!=0 AND $value['old_arival_date']!=$value['departure_time'])
                {
                    $night = 0;
                    $adult = 0;
                    $child = 0;
                    $child_under_five = 0;
                }
                /** doi phong trong ngay **/
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_to_rr']!=0)
                {
                    $night = 0;
                    $amount = 0;
                    $adult = 0;
                    $child = 0;
                    $child_under_five = 0;
                }
                /** ngay cuoi cung trong chang **/
                if( $value['arrival_time']!=$value['departure_time'] AND $value['in_date'] == $value['departure_time'])
                {
                    $night = 0;
                    $amount = 0;
                    $adult = 0;
                    $child = 0;
                    $child_under_five = 0;
                }
                if(isset($result[$value['customer_date']]))
                {
                    $result[$value['customer_date']]['id'] = $value['customer_date'];
                    $result[$value['customer_date']]['customer_id'] = $value['customer_id'];
                    $result[$value['customer_date']]['company_name'] = $value['customer_name'];
                    $result[$value['customer_date']]['in_date'] = $value['in_date'];
                    $result[$value['customer_date']]['sale'] = $value['sale'];
                    $result[$value['customer_date']]['room_type'] = $value['room_type'];
                    
                    $result[$value['customer_date']]['price'] += $amount;
                    $result[$value['customer_date']]['room_count'] += $night;
                    $result[$value['customer_date']]['sum_adult'] += $adult;
                    $result[$value['customer_date']]['sum_child'] += $child;
                    $result[$value['customer_date']]['sum_child_under_five'] += $child_under_five;   
                }
                else
                {
                    $result[$value['customer_date']]['id'] = $value['customer_date'];
                    $result[$value['customer_date']]['customer_id'] = $value['customer_id'];
                    $result[$value['customer_date']]['company_name'] = $value['customer_name'];
                    $result[$value['customer_date']]['in_date'] = $value['in_date'];
                    $result[$value['customer_date']]['sale'] = $value['sale'];
                    $result[$value['customer_date']]['room_type'] = $value['room_type'];
                    
                    $result[$value['customer_date']]['num'] = 1;
                    $result[$value['customer_date']]['price'] =  $amount;
                    $result[$value['customer_date']]['room_count'] = $night;
                    $result[$value['customer_date']]['sum_adult'] = $adult;
                    $result[$value['customer_date']]['sum_child'] = $child;
                    $result[$value['customer_date']]['sum_child_under_five'] = $child_under_five;   
                    
                }
                
            }
            
            //System::debug($result);exit();
            //lấy ra lo_li_ei hoac cac dich vu tra ve phong
            //Daund them dieu kien tim kiem theo nguon 
            $cond_service = ((URL::get('customer_id'))?' AND r.customer_id = '.Url::get('customer_id').'':'');
            $sql_service='
                select ROW_NUMBER() OVER(ORDER BY customer.NAME DESC) AS id,
                    esid.in_date,
                    customer.name ||\'_\'|| room_type.name ||\'_\'|| esid.in_date ||\'_\'|| customer.id as customer_date,
                    esid.quantity,
                    esi.tax_rate,
                    esi.service_rate,
                    esi.net_price,
                    customer.id as customer_id,
                    customer.name as customer_name,
                    customer.sale_code as sale,
                    customer_group.id as reservation_type_id,
                    customer_group.name as reservation_type_name,
                    esid.price as change_price,
                    esid.percentage_discount,
                    esid.amount_discount,
                    esi.payment_type,
                    es.code,
                    room_type.name as room_type,
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
                    left  JOIN  room ON room.id = rr.room_id
                    left  join room_type on room.room_type_id = room_type.id
                    WHERE
                    (es.code = \'LATE_CHECKIN\' 
                    OR es.code = \'EARLY_CHECKIN\' 
                    OR es.code=\'LATE_CHECKOUT\'
                    OR esi.payment_type = \'ROOM\')
                    AND esid.in_date >= \''.$date_from.'\' 
                    AND esid.in_date <= \''.$date_end.'\'
                    '.$cond_service.'
            ';
            $service_room = DB::fetch_all($sql_service);
            $orcl = '
                SELECT 
                      ROW_NUMBER() OVER(ORDER BY customer.NAME DESC) AS id,  
                      customer.NAME as company_name,
                      rs.in_date as in_date,
                      count(rs.in_date) as num
                      
                FROM
                      room_status rs
                      inner join reservation_room on reservation_room.id = rs.reservation_room_id
                      inner join room on reservation_room.room_id = room.id
                      inner join room_type on room.room_type_id = room_type.id
                      inner join reservation r on reservation_room.reservation_id = r.id
                      inner join customer on customer.ID = r.customer_ID
                WHERE
                						'.$cond.'
                                        and customer.group_id != \'ROOT\' and rs.change_price!=0
                GROUP BY
                      room_type.name, rs.in_date, customer.NAME, customer.sale_code
                ORDER BY
                      customer.NAME DESC, rs.in_date DESC
            ';
            $record = DB::fetch_all($orcl);
            
            //System::debug($record);
            $n=0;
            $this->map['customer'][0] = array('company_name'=>'','in_date'=>'','number_date'=>'0');
            $_REQUEST['total'] = array('total_room'=>'0','total_adult'=>'0','total_child'=>'0','total_child_under_five'=>'0','total_price'=>'0');
            foreach($result as $key=>$value){
                $result[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date'],"/");
                $_REQUEST['total']['total_room'] += $value['room_count'];
                $_REQUEST['total']['total_adult'] += $value['sum_adult'];
                $_REQUEST['total']['total_child'] += $value['sum_child'];
                $_REQUEST['total']['total_child_under_five'] += $value['sum_child_under_five'];
                $_REQUEST['total']['total_price'] += $value['price'];
               
            }  
            //System::debug($service_room);          
            foreach($service_room as $key1 => $value1)
            {
                //check net price
                if($value1['net_price'])
                    $value1['change_price'] = $value1['change_price']/(1+$value1['service_rate']/100)/(1+$value1['tax_rate']/100);
                // giam gia %
                $value1['change_price'] = $value1['change_price'] - ($value1['change_price']*$value1['percentage_discount']/100);
                // giam gia so tien
                $value1['change_price'] = $value1['change_price'] - $value1['amount_discount'];
                //check option thue 
                $amount = $value1['change_price']*(1+$value1['service_rate']/100)*(1+$value1['tax_rate']/100);
                $amount = $amount*$value1['quantity'];
                $amount = number_format($amount);
                $amount = System::calculate_number($amount);
                if($value1['foc_all']==1)
                {
                    $amount = 0;
                }
                if(($value1['code']=='LATE_CHECKIN' OR $value1['code']=='EARLY_CHECKIN' OR $value1['code']=='LATE_CHECKOUT'))
                {
                    $night = $value1['quantity'];
                }
                else
                {
                    $night = 0;
                }
                $adult = 0;
                $child = 0;
                $child_under_five = 0;                
                if(isset($result[$value1['customer_date']]))
                {
                    $result[$value1['customer_date']]['price'] +=  $amount;
                    $result[$value1['customer_date']]['room_count'] += $night;
                    $result[$value1['customer_date']]['sum_adult'] += $adult;
                    $result[$value1['customer_date']]['sum_child'] += $child;
                    $result[$value1['customer_date']]['sum_child_under_five'] += $child_under_five;
                    
                    $_REQUEST['total']['total_price'] += $amount;
                    $_REQUEST['total']['total_room'] += $night; 
                    $_REQUEST['total']['total_adult'] += $adult;
                    $_REQUEST['total']['total_child'] += $child;
                    $_REQUEST['total']['total_child_under_five'] += $child_under_five;
                }
                else
                {
                    $result[$value1['customer_date']]['id'] = $value1['customer_date'];
                    $result[$value1['customer_date']]['customer_id'] = $value1['customer_id'];
                    $result[$value1['customer_date']]['company_name'] = $value1['customer_name'];
                    $result[$value1['customer_date']]['in_date'] = Date_Time::convert_orc_date_to_date($value1['in_date'],"/");
                    $result[$value1['customer_date']]['sale'] = $value1['sale'];
                    $result[$value1['customer_date']]['room_type'] = $value1['room_type'];
                    $result[$value1['customer_date']]['num'] = 1;
                    $result[$value1['customer_date']]['price'] =  $amount;
                    $result[$value1['customer_date']]['room_count'] = $night;
                    $result[$value1['customer_date']]['sum_adult'] = $adult;
                    $result[$value1['customer_date']]['sum_child'] = $child;
                    $result[$value1['customer_date']]['sum_child_under_five'] = $child_under_five;
                     
                    $_REQUEST['total']['total_price'] += $amount;
                    $_REQUEST['total']['total_room'] += $night; 
                    $_REQUEST['total']['total_adult'] += $adult;
                    $_REQUEST['total']['total_child'] += $child;
                    $_REQUEST['total']['total_child_under_five'] += $child_under_five;
                }      
            }
            
            $i=0;
            //System::debug($result);
            ksort($result);
            
            //System::debug($result);
            $this->map['count_customer'][0] = array('company'=>'','num'=>'0');
            $stt=1;
            
            foreach($result as $key=>$value){
                if($value['room_count']==0 and $value['sum_adult']==0 and $value['sum_child']==0)
                {
                    unset($result[$key]);
                }
                else
                {
                    $result[$key]['num']=1;
                    $result[$key]['stt'] = $stt++;
                    if($this->map['count_customer'][$i]['company'] == $value['company_name']){
                        $this->map['count_customer'][$i]['num'] += 1; 
                    }else{
                        $i+=1;
                        $this->map['count_customer'][$i]['company'] = $value['company_name'];
                        $this->map['count_customer'][$i]['num'] = 1;
                    }
                    foreach($record as $id=>$content){
                        if(($value['in_date']==$content['in_date']) AND ($value['company_name']==$content['company_name'])){
                            $result[$key]['num'] += 1 ;
                        }
                    }
               } 
               /** Minh fix lay thua du lieu **/ 
               if($value['customer_id'] != Url::get('customer_id') && Url::get('customer_id')!='')
               {
                    //daund ẩn unset($result[$key]);
               }             
            }            
            //System::debug($result);
            
            unset($this->map['count_customer'][0]);
            //System::debug($this->map['count_customer']);
            $this->parse_layout('report',array('items'=>$result,'count_customer'=>$this->map['count_customer']));
            
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
