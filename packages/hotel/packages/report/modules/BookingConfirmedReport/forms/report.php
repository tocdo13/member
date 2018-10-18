<?php
class BookingConfirmedReportForm extends Form
{
    function BookingConfirmedReportForm()
    {
        Form::Form('BookingConfirmedReportForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
    }
    function draw()
    {
        
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
        $cond = '1=1';
        $ctm_id = '';
        $r_id = isset($_REQUEST['reservation_id'])?$_REQUEST['reservation_id']:'';
        if($r_id!='')
        {
            $cond .= ' and reservation.id = '.$r_id;
        }
        /** Nguon khach **/
        $this->map['customer'] = Url::get('customer_ids','');
        if($this->map['customer']!='')
        {
            $cond .= ' and reservation.customer_id in ('.$this->map['customer'].')';
        }                               
        $l_customer = DB::fetch_all($sql='
                       select customer.id,customer.name from customer order by customer.name
        ');       
        //System::debug($this->map['customer']);                         
        $this->map['customer_js'] = String::array2js(explode(',',Url::get('customer_id_')));            
        $customer = '<div id="checkboxes_customer">';
        foreach($l_customer as $key=>$value)
        {                
            $customer .= '<label for="customer_'.$value['id'].'">';    
            $customer .= '<input name="customer_'.$value['id'].'" type="checkbox" id="customer_'.$value['id'].'" flag="'.$value['id'].'" class="customer" onclick="get_ids(\'customer\');"/>'.$value['name'].'</label>';                                    
        }   
        $customer .= '</div>';            
        $this->map['list_customer'] = $customer; 
        /** Nguon khach **/   
        $this->map['from_date'] = isset($_REQUEST['from_date'])?$_REQUEST['from_date'] = $_REQUEST['from_date']:$_REQUEST['from_date'] = date('01/m/Y', Date_Time::to_time(date('d/m/Y', time())));
        $this->map['to_date'] = isset($_REQUEST['to_date'])?$_REQUEST['to_date'] = $_REQUEST['to_date']:$_REQUEST['to_date'] = date('t/m/Y');
        $from_date = Date_Time::to_time($_REQUEST['from_date']);
        $to_date = Date_Time::to_time($_REQUEST['to_date']);
        
        
        $cond .= ' and reservation.cut_of_date >= \''.Date_time::to_orc_date($this->map['from_date']).'\'';
        $cond .= ' and reservation.cut_of_date <= \''.Date_time::to_orc_date($this->map['to_date']).'\'';
        
        $sql = '
            select 
                room_status.id as id
                ,room_status.in_date
                ,room_status.change_price as price
                ,reservation_room.id as reservation_room_id
                ,reservation_room.arrival_time
                ,reservation_room.departure_time
                ,reservation_room.time_out
                ,reservation_room.time_in
                ,reservation_room.net_price
                ,reservation_room.tax_rate
                ,reservation_room.service_rate
                ,reservation_room.reduce_balance
                ,reservation_room.reduce_amount
                ,reservation.id as reservation_id
                ,to_char(reservation.cut_of_date,\'DD/MM/YYYY\') as cut_of_date
                ,customer.name as customer_name
            from 
                room_status
                inner join reservation_room on room_status.reservation_room_id = reservation_room.id
                inner join reservation on reservation_room.reservation_id = reservation.id
                left join customer on reservation.customer_id = customer.id
                left join room_level on reservation_room.room_level_id=room_level.id
            where 
                '.$cond.'
                AND reservation_room.status!=\'CANCEL\'
                AND reservation_room.status!=\'NOSHOW\'
                AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
            order by 
                reservation.cut_of_date
                ,reservation_room.id
        ';
        $reservation_arr = DB::fetch_all($sql);
        $record_arr = array();
        /** lay thong tin ve phong **/
        foreach($reservation_arr as $key=>$value)
        {
            if($value['net_price']==1 AND $value['reduce_balance']==0 AND $value['reduce_amount']==0)
            {
                /** gia da co thue phi va khong co giam gia **/
                $price = $value['price'];
            }
            else
            {
                if($value['net_price']==1)
                {
                    $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                }
                $value['price'] = $value['price'] - ($value['price']*$value['reduce_balance']/100);
                if($value['in_date']==$value['arrival_time'])
                {
                    $value['price'] = $value['price'] - $value['reduce_amount'];
                }
                $price = $value['price']*((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
            }
            if(!isset($record_arr[$value['reservation_id']]))
            {
                $record_arr[$value['reservation_id']]['id'] = $value['reservation_id'];
                $record_arr[$value['reservation_id']]['cut_of_date'] = $value['cut_of_date'];
                $record_arr[$value['reservation_id']]['customer_name'] = $value['customer_name'];
                $record_arr[$value['reservation_id']]['time_in'] = $value['time_in'];
                $record_arr[$value['reservation_id']]['time_out'] = $value['time_out'];
                $record_arr[$value['reservation_id']]['total_amount'] = $price;
                $record_arr[$value['reservation_id']]['rr_array'][$value['reservation_room_id']] = $value['reservation_room_id'];
                $record_arr[$value['reservation_id']]['total_deposit'] = 0;
            }
            else
            {
                $record_arr[$value['reservation_id']]['total_amount'] += $price;
                if($value['time_in']<$record_arr[$value['reservation_id']]['time_in'])
                {
                    $record_arr[$value['reservation_id']]['time_in'] = $value['time_in'];
                }
                if($value['time_out']>$record_arr[$value['reservation_id']]['time_out'])
                {
                    $record_arr[$value['reservation_id']]['time_out'] = $value['time_out'];
                }
                if(!isset($record_arr[$value['reservation_id']]['rr_array'][$value['reservation_room_id']]))
                {
                    $record_arr[$value['reservation_id']]['rr_array'][$value['reservation_room_id']] = $value['reservation_room_id'];
                }
            }
        }
        /** lay doanh thu li **/
        $sql = "
                SELECT
                    concat(concat(extra_service.name,'_'),esid.id) as id
                    ,CASE
            			WHEN 
            				extra_service_invoice.net_price =0 or extra_service_invoice.net_price = NULL
            			THEN
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01) + ((((esid.quantity+nvl(esid.change_quantity,0))*esid.price) + (((esid.quantity+nvl(esid.change_quantity,0))*esid.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)
            			ELSE
            				((esid.quantity+nvl(esid.change_quantity,0))*esid.price)
                    END as total_amount
                    ,reservation.id as reservation_id
                FROM
                    extra_service_invoice_detail esid
                    inner join extra_service_invoice on extra_service_invoice.id=esid.invoice_id
                    inner join extra_service on extra_service.id = esid.service_id
                    left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                WHERE
                    ".$cond."
                    AND extra_service.code = 'LATE_CHECKIN'
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    extra_service.name, esid.id
                ";
        $extra_service = DB::fetch_all($sql);
        foreach($extra_service as $key=>$value)
        {
            if(isset($record_arr[$value['reservation_id']]))
            {
                $record_arr[$value['reservation_id']]['total_amount'] += round($value['total_amount']);
            }
        }
        $cond_user = '';
        if(Url::get('user_id')!='')
        {
            $cond_user .= ' and payment.user_id=\''.Url::get('user_id').'\'';
        }
        $sql_group = '
            select 
                payment.id
                ,payment.amount
                ,to_char(from_unixtime(payment.time),\'DD/MM/YYYY\') as date_deposit
                ,payment.payment_type_id
                ,payment.currency_id
                ,payment.reservation_id
                ,payment.exchange_rate
                ,payment.description
                ,payment.user_id
            from payment
                inner join reservation on payment.reservation_id = reservation.id
            where 
                '.$cond.$cond_user.'
                and (payment.type_dps = \'GROUP\')
                and payment.type = \'RESERVATION\'
            order by payment.reservation_id
        ';
        $deposit_group = DB::fetch_all($sql_group);
        $sql_room = '
            select 
                payment.id
                ,payment.amount
                ,to_char(from_unixtime(payment.time),\'DD/MM/YYYY\') as date_deposit
                ,payment.payment_type_id
                ,payment.currency_id
                ,reservation.id as reservation_id
                ,payment.exchange_rate
                ,payment.description
                ,payment.user_id
            from payment
                inner join reservation_room on payment.reservation_room_id = reservation_room.id
                inner join reservation on reservation_room.reservation_id = reservation.id
            where 
                '.$cond.$cond_user.'
                and (payment.type_dps = \'ROOM\')
                and payment.type = \'RESERVATION\'
                and reservation_room.status != \'CANCEL\'
            order by payment.reservation_id
        ';
        $deposit_room = DB::fetch_all($sql_room);
        $deposit_arr = $deposit_group+$deposit_room;
        //System::debug($deposit_arr);
        $deposit = array();
        foreach($deposit_arr as $k=>$v)
        {
            if(isset($record_arr[$v['reservation_id']]))
            {
                $record_arr[$v['reservation_id']]['total_deposit'] += ($v['currency_id']=='VND')?$v['amount']:($v['amount']*$v['exchange_rate']);
                $record_arr[$v['reservation_id']]['child_deposit'][$k] = $v;
            }
        }
        if($cond_user!='')
        {
            foreach($record_arr as $k=>$v)
            {
                if(!isset($record_arr[$k]['child_deposit']))
                {
                    unset($record_arr[$k]);
                }
            }
        }
        //System::debug($record_arr);
        $this->parse_layout('report',
        	array(
        		'items'=>$record_arr
        	)+$this->map
        ); 
        /** lay thong tin ve dat coc **/
        
        
    }    
}
?>