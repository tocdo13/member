<?php
class SammarySaleInDateForm extends Form
{
	function SammarySaleInDateForm()
	{
		Form::Form('SammarySaleInDateForm');
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
        $_REQUEST['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):999;
        if($_REQUEST['line_per_page']<1) $_REQUEST['line_per_page'] = 99;
        $_REQUEST['total_page'] = Url::get('total_page')?Url::get('total_page'):50;
        if($_REQUEST['total_page']<1) $_REQUEST['total_page'] = 50;
        $_REQUEST['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
        if($_REQUEST['start_page']<1) $_REQUEST['start_page'] = 1;
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
		  
			$cond .= '  
                        AND DATE_TO_UNIX(ROOM_STATUS.IN_DATE) <=\''.Date_Time::to_time($this->map['date_to']).'\' 
                        AND DATE_TO_UNIX(ROOM_STATUS.IN_DATE) >= \''.Date_Time::to_time($this->map['date_from']).'\' 
                        AND DATE_TO_UNIX(reservation_room.departure_time)>=\''.Date_Time::to_time($this->map['date_from']).'\' 
                        and DATE_TO_UNIX(reservation_room.arrival_time)<=\''.Date_Time::to_time($this->map['date_to']).'\'
                        AND ROOM_STATUS.STATUS = \'OCCUPIED\' 
			';
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$sql='
				    SELECT 
                      room_status.in_date AS id,
                      room_status.in_date as in_date,
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
                        and room_status.change_price!=0 
                        AND ((room_level.is_virtual is null) OR (room_level.is_virtual = 0))
                GROUP BY
                      room_status.in_date
                ORDER BY
                      room_status.in_date DESC
						
			';
            //System::debug($sql);
			$report->items = DB::fetch_all($sql);
            //KimTan lấy thêm ei_lo_li và các dịch vụ co type bằng service
            $sql_service='
                select esid.id AS id,
                    esid.in_date,
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
                if(isset($report->items[$value1['in_date']]))
                {
                    $report->items[$value1['in_date']]['price'] +=  $value1['amount'];
                    if($value1['code'] != '')
                    {
                        $report->items[$value1['in_date']]['room_count'] += $value1['quantity'];
                        $report->items[$value1['in_date']]['sum_adult'] += $value1['adult'];
                        $report->items[$value1['in_date']]['sum_child'] += $value1['child'];
                    }
                }
                else
                {
                    $report->items[$value1['in_date']]['id'] = $value1['in_date'];
                    $report->items[$value1['in_date']]['in_date'] = $value1['in_date'];
                    $report->items[$value1['in_date']]['price'] =  $value1['amount'];
                    if($value1['code'] != '')
                    {
                        $report->items[$value1['in_date']]['room_count'] = $value1['quantity'];
                        $report->items[$value1['in_date']]['sum_adult'] = $value1['adult'];
                        $report->items[$value1['in_date']]['sum_child'] = $value1['child'];
                    }
                }
            }
            $_REQUEST['sammary'] = array('total_room'=>'0','total_adult'=>'0','total_child'=>'0','total_price'=>'0');
            foreach($report->items as $key=>$value){
                $report->items[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date'],"/");
                $_REQUEST['sammary']['total_room'] += $value['room_count'];
                $_REQUEST['sammary']['total_adult'] += $value['sum_adult'];
                $_REQUEST['sammary']['total_child'] += $value['sum_child'];
                $_REQUEST['sammary']['total_price'] += $value['price'];
            }
            ksort($report->items);
            $this->phan_trang($report);
		}
		else
		{
            //list TA
			$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
			//lấy list mã ssale
            //$sale = DB::fetch_all("select account_privilege_group.account_id as id, account_privilege_group.account_id as name from account_privilege_group where account_privilege_group.group_privilege_id='10'");
            //$this->map['sale_code_list'] = array('all'=>'--select-sale--') + String::get_list($sale);
            $this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:999;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
            $this->parse_layout('search',$this->map);	
		}			
	}
	function phan_trang(&$report){
	   $n = sizeof($report->items);// đếm tổng số bản ghi.
       if($n<=0){// nếu không có bản ghi nào. thì trả về layout search vào cho $this->map['no_record'] = 1 để xâu cảnh báo.
            //list TA
			$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
			//lấy list mã ssale
            //$sale = DB::fetch_all("select account_privilege_group.account_id as id, account_privilege_group.account_id as name from account_privilege_group where account_privilege_group.group_privilege_id='10'");
            //$this->map['sale_code_list'] = array('all'=>'--select-sale--') + String::get_list($sale);
            $this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:999;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
            $this->parse_layout('search',$this->map);
            exit();
       }
       $pages = array();// khai báo mảng page để chứa từng trang.
       $count = 0;// $count để đếm bản ghi duyệt qua từng trang
       $i=1;// gán giá trị ban đầu cho trang là 1.
       if($n<=$_REQUEST['line_per_page']){//nếu số bản ghi nhỏ hơn số dòng trên 1 trang - nghĩa là chỉ có 1 trang duy nhất
            $this->parse_layout('header',array());// lấy tiêu đề
            $this->parse_layout('report',array('items'=>$report->items,'num_page'=>'1','total_page'=>'1'));// in luôn dữ liệu ra
       }else{// nếu số bản ghi lớn hơn số dòng trên 1 trang-> có nhiều hơn 1 trang-> tạo các trang
            foreach($report->items as $key=>$value){// duyệt qua tất cả bản ghi có trong $report->items
                $count += 1;// tăng $count lên 1 đơn vị sau mỗi vòng lăp
                if($count > $_REQUEST['line_per_page']){//nếu $count bằng với số dòng trên 1 trang nghĩa là đã duyệt được 1 trang
                    $count = 1;// trả giá trị $count về 1 để vòng lặp sau duyệt trang kế tiếp
                    $i +=1;// tăng số trang lên 1 đơn vị   
                }
                $pages[$i][$key]=$value;// lấy từng bản ghi gán vào số trang tương ứng.
            }
            $total_page = sizeof($pages);// tổng số trang.
            $this->parse_layout('header',array());// lấy tiêu đề
            foreach($pages as $num_page=>$page){// lặp toàn bộ trang
                if(($num_page>=$_REQUEST['start_page']) AND ($num_page<=$_REQUEST['total_page']))// xét điều kiện để bắt đầu in từ trang bao nhiêu và in bao nhiêu trang.
                $this->in_trang($num_page,$page,$total_page);// mỗi vòng lặp lại gọi hàm print_page để parse_layout từng trang 1 sang report-layout
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
