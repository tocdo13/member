<?php
class SummaryCustomerReportForm extends Form
{
	function SummaryCustomerReportForm()
	{
		Form::Form('SummaryCustomerReportForm');
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
        $_REQUEST['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):999;
        if($_REQUEST['line_per_page']<1) $_REQUEST['line_per_page'] = 99;
        $_REQUEST['total_page'] = Url::get('total_page')?Url::get('total_page'):50;
        if($_REQUEST['total_page']<1) $_REQUEST['total_page'] = 50;
        $_REQUEST['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
        if($_REQUEST['start_page']<1) $_REQUEST['start_page'] = 1;
		if(Url::get('do_search'))
		{
		    require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report; 
            $report->items = array();
            $cond = '';
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
            
            if($portal_id != 'ALL')
            {
                $cond.=' reservation.portal_id = \''.$portal_id.'\' '; 
                $cond_service.=' and reservation.portal_id = \''.$portal_id.'\' ';
            }
			$cond .= ' AND
                    (
                        ( reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND reservation_room.departure_time >=\''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\'  ) 
                        OR 
                        ( reservation_room.status = \'CHECKIN\' AND reservation_room.arrival_time <=\''.date('d-M-Y',Date_Time::to_time($this->map['date_to'])).'\' AND reservation_room.arrival_time >=\''.date('d-M-Y',Date_Time::to_time($this->map['date_from'])).'\'   ) 
                    )
					'.((URL::get('customer_id'))?' AND reservation.customer_id = '.Url::get('customer_id').'':'').'
			';
            $cond_service.=' AND extra_service_invoice_detail.time >=\''.Date_Time::to_time($this->map['date_from']).'\'
			    AND extra_service_invoice_detail.time <=\''.(Date_Time::to_time($this->map['date_to'])+86400).'\'
            ';
            // TRUY VAN DU LIEU;
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
                        reservation_room.foc_all,
                        --reservation_room.early_checkin,
                        --reservation_room.late_checkout,
                        NVL(reservation_room.reduce_balance,0) as reduce_balance,
                        NVL(reservation_room.reduce_amount,0) as reduce_amount,
                        reservation_room.adult,
                        reservation_room.child,
                        NVL(reservation_room.change_room_from_rr,0) as change_room_from_rr,
                        NVL(reservation_room.change_room_to_rr,0) as change_room_to_rr,
                        customer.name as customer_name,
                        customer.id as customer_id
                FROM
                      room_status
                      inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                      inner join room on reservation_room.room_id = room.id
                      inner join room_level on room_level.id = room.room_level_id
                      inner join room_type on room.room_type_id = room_type.id
                      inner join reservation on reservation_room.reservation_id = reservation.id
                      inner join customer on customer.ID = reservation.customer_ID
                WHERE
                    '.$cond.'
                      and customer.group_id != \'ROOT\'
                      --and reservation_room.foc_all=0
                      and (room_level.is_virtual is null or room_level.is_virtual = 0)
                      and reservation_room.room_type_id !=3
                GROUP BY
                    reservation_room.id,
                    customer.name,
                    customer.id,
                    reservation_room.adult,
                    reservation_room.child,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    reservation_room.foc,
                    reservation_room.foc_all,
                    --reservation_room.early_checkin,
                    --reservation_room.late_checkout,
                    reservation_room.reduce_balance,
                    reservation_room.reduce_amount,
                    reservation_room.change_room_from_rr,
                    reservation_room.change_room_to_rr
                ORDER BY
                      customer.NAME DESC
			 ';
             //System::debug($sql);
			$detail_items = DB::fetch_all($sql);
            //System::debug($detail_items);
            foreach($detail_items as $id=>$content)
            {
                if(($content['arrival_time']==$content['departure_time']) AND ($content['change_room_to_rr']!=0))
                {
                    // TH: doi phong trong ngay, khong tinh doanh thu tien phong cua trương hop nay
                }
                else
                {
                   // if($content['foc']!='')
//                    {
//                        // TH: mien phi tien phong
//                        //$detail_items[$id]['total']=0;
//                    }
//                    else
                    //{
                        $total = 0;
                        if($content['net_price']==0)
                        {
                            // TH: gia chua co thue phi
                            $total = $content['total'] - $content['reduce_balance']; // tru giam gia theo so tien
                            $total = $total - ($total*$content['reduce_balance']/100); // tru giam gia theo %
                            $total = $total * (1+$content['service_rate']/100); // tinh phi dv
                            $total = $total * (1+$content['tax_rate']/100); // tinh thue
                        }
                        else
                        {
                            $total = $content['total'] / (1+$content['tax_rate']/100);// tinh gia chua thue
                            $total = $total / (1+$content['service_rate']/100); // tinh gia chua thue
                            $total = $total - $content['reduce_balance']; // tru giam gia theo so tien
                            $total = $total - ($total*$content['reduce_balance']/100); // tru giam gia theo %
                            $total = $total * (1+$content['service_rate']/100); // tinh phi dv
                            $total = $total * (1+$content['tax_rate']/100); // tinh thue
                        }
                        /* end trung :them truong hop giam gia tien phong va giam gia toan bo cung lay vao bc */
                        if($content['foc_all']==1 or $content['foc']!='')
                        {
                            $total=0;
                        }
                        if(!isset($report->items[$content['customer_id']]))
                        {
                            $report->items[$content['customer_id']]['id'] = $content['customer_id'];
                            $report->items[$content['customer_id']]['company_name'] = $content['customer_name'];
                            $report->items[$content['customer_id']]['room_count'] = 1;
                            $report->items[$content['customer_id']]['sum_adult'] = $content['adult'];
                            $report->items[$content['customer_id']]['sum_child'] = $content['child'];
                            $report->items[$content['customer_id']]['price'] = $total;
                        }
                        else
                        {
                            $report->items[$content['customer_id']]['room_count'] += 1;
                            $report->items[$content['customer_id']]['sum_adult'] += $content['adult'];
                            $report->items[$content['customer_id']]['sum_child'] += $content['child'];
                            $report->items[$content['customer_id']]['price'] += $total;
                        }
                   // }
                }
            }
            //System::debug($report->items);
            $sql = '
                SELECT
                    extra_service_invoice.id,
                    customer.id as company_id,
                    customer.name as company_name,
                    extra_service_invoice_detail.quantity,
                    extra_service_invoice.total_amount as price_service
                FROM
                    extra_service_invoice_detail
                    inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                    inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                    inner join reservation_room on extra_service_invoice.reservation_room_id = reservation_room.id
                    inner join room on reservation_room.room_id = room.id
                    inner join reservation on reservation_room.reservation_id = reservation.id
    				inner join  room_level on room_level.id = room.room_level_id
                    inner join customer on customer.ID = reservation.customer_ID
                    inner join customer_group on customer_group.id = customer.group_id
                WHERE
                    (extra_service.code = \'LATE_CHECKOUT\'
                    OR extra_service.code = \'LATE_CHECKIN\'
                    OR extra_service.code = \'EARLY_CHECKIN\'
                    OR extra_service_invoice.payment_type = \'ROOM\')
                    AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                    AND reservation_room.foc_all = 0
                    '.$cond_service
            ;
            $record = DB::fetch_all($sql);
            foreach($record as $id_1=>$content_1)
            {
                if(isset($report->items[$content_1['company_id']]))
                {
                    $report->items[$content_1['company_id']]['price'] += $content_1['price_service'];
                }
            }
            //System::debug($record);
            $_REQUEST['sammary'] = array(
                                        'total_room'=>'0',
                                        'total_adult'=>'0',
                                        'total_child'=>'0',
                                        'total_price'=>'0'
                                        );
            foreach($report->items as $key=>$value){
                $_REQUEST['sammary']['total_room'] += $value['room_count'];
                $_REQUEST['sammary']['total_adult'] += $value['sum_adult'];
                $_REQUEST['sammary']['total_child'] += $value['sum_child'];
                $_REQUEST['sammary']['total_price'] += $value['price'];
            }
            //System::debug($report->items);
                $this->phan_trang($report);
            //phan_toan_bo_trang($report->items);
		}
		else
		{
		    $this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:999;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;  
			$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}
       // System::debug($report->items)	;
	}
    // PHAN TRANG:
    function phan_trang(&$report){
	   $n = sizeof($report->items);// �?m t?ng s? b?n ghi.
       if($n<=0){// n?u kh�ng c� b?n ghi n�o. th? tr? v? layout search v�o cho $this->map['no_record'] = 1 �? x�u c?nh b�o.
			$this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:999;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;  
			$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);
            exit();
       }
       $pages = array();// khai b�o m?ng page �? ch?a t?ng trang.
       $count = 0;// $count �? �?m b?n ghi duy?t qua t?ng trang
       $i=1;// g�n gi� tr? ban �?u cho trang l� 1.
       if($n<=$_REQUEST['line_per_page']){//n?u s? b?n ghi nh? h�n s? d?ng tr�n 1 trang - ngh?a l� ch? c� 1 trang duy nh?t
            $this->parse_layout('header',array());// l?y ti�u �?
            $this->parse_layout('report',array('items'=>$report->items,'num_page'=>'1','total_page'=>'1'));// in lu�n d? li?u ra
       }else{// n?u s? b?n ghi l?n h�n s? d?ng tr�n 1 trang-> c� nhi?u h�n 1 trang-> t?o c�c trang
            foreach($report->items as $key=>$value){// duy?t qua t?t c? b?n ghi c� trong $report->items
                $count += 1;// t�ng $count l�n 1 ��n v? sau m?i v?ng l�p
                if($count > $_REQUEST['line_per_page']){//n?u $count b?ng v?i s? d?ng tr�n 1 trang ngh?a l� �? duy?t ��?c 1 trang
                    $count = 1;// tr? gi� tr? $count v? 1 �? v?ng l?p sau duy?t trang k? ti?p
                    $i +=1;// t�ng s? trang l�n 1 ��n v?   
                }
                $pages[$i][$key]=$value;// l?y t?ng b?n ghi g�n v�o s? trang t��ng ?ng.
            }
            $total_page = sizeof($pages);// t?ng s? trang.
            $this->parse_layout('header',array());// l?y ti�u �?
            foreach($pages as $num_page=>$page){// l?p to�n b? trang
                if(($num_page>=$_REQUEST['start_page']) AND ($num_page<=$_REQUEST['total_page']))// x�t �i?u ki?n �? b?t �?u in t? trang bao nhi�u v� in bao nhi�u trang.
                $this->in_trang($num_page,$page,$total_page);// m?i v?ng l?p l?i g?i h�m print_page �? parse_layout t?ng trang 1 sang report-layout
            }
       }
	}// end print_all_page
    function in_trang($num_page,$page,$total_page){
        //System::debug($page);
        $this->parse_layout('report',array(
                                    'items'=>$page,
                                    'num_page'=>$num_page,
                                    'total_page'=>$total_page
                                    ));
    }
	
}
?>