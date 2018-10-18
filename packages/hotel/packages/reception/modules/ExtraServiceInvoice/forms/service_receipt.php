<?php
class ViewExtraServiceInvoiceForm extends Form
{
	function ViewExtraServiceInvoiceForm()
	{
		Form::Form();
		System::set_page_title(HOTEL_NAME);
		$this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		$this->map = array();
		$this->map['in_date_options'] = '';
		$item = ExtraServiceInvoice::$item;
		if(isset($item['reservation_room_id']))
        {
			$reservation_room_id = $item['reservation_room_id'];
		}
        else
        {
			if(Url::get('reservation_room_id'))
            {
				$reservation_room_id = Url::iget('reservation_room_id');
			}
            else
            {
				$reservation_room_id = 0;
			}
		}
        $service = DB::fetch('select * from extra_service where code=\'VFD\'');
		$room = DB::fetch('
			select 
				reservation_room.id,
				reservation.id as reservation_id,
				DECODE(room.name,NULL,reservation_room.temp_room,room.name) name,
				to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_date,
				to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_date,
				reservation_room.time_in,
				reservation_room.time_out,
				reservation_room.status 
			from 
				reservation_room 
				inner join reservation on reservation.id = reservation_room.reservation_id
				left outer join room on room.id = reservation_room.room_id 
			where 
				reservation.portal_id = \''.PORTAL_ID.'\' AND reservation_room.id = '.$reservation_room_id.'');
		$arrival_time = Date_Time::to_time($room['arrival_date']);
		$departure_time = Date_Time::to_time($room['departure_date']);
		$date_option_temp_str = '';
		for($i=$arrival_time;$i<=$departure_time;$i=$i+(24*3600))
		{
			$date = Date_Time::to_orc_date(date('d/m/Y',$i));
			$current_date = Date_Time::to_orc_date(date('d/m/Y'));
			if(Date_Time::to_time(date('d/m/Y')) <= Date_Time::to_time(date('d/m/Y',$i))){
				$date_option_temp_str .= '<option value="'.date('d/m/Y',$i).'">'.date('d/m/Y',$i).'</option>';
			}
		}
		$this->map['arrival_time'] = $arrival_time;
		$this->map['departure_time'] = $departure_time;
        $lock = 0;
		if($item)
        {
			$this->map['room_name'] = 'RE'.$room['reservation_id'].' - '.$room['name'];
			$this->map['arrival_date'] = $room['arrival_date'];
			$this->map['departure_date'] = $room['departure_date'];
			$this->map['status'] = $room['status'];
			$_REQUEST['code'] = $item['code'];
            $_REQUEST['close'] = $item['close'];   
            if($item['late_checkin']==1 or $item['late_checkout']==1 or $item['early_checkin']==1 ){
                $lock = 1;
            }
			foreach($item as $key=>$value)
            {
				if(!isset($_REQUEST[$key]))
                {
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
			if(!isset($_REQUEST['mi_product_group']))
			{
				$sql = '
					SELECT
						extra_service_invoice_detail.*,
						(extra_service_invoice_detail.price*(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))) as payment_price,
						extra_service.unit,
                        extra_service_invoice.net_price,
                        extra_service_invoice.tax_rate,
                        extra_service_invoice.service_rate
					FROM
						extra_service_invoice_detail
                        inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
						INNER JOIN extra_service ON extra_service.id = extra_service_invoice_detail.service_id
					WHERE
						extra_service_invoice_detail.invoice_id=\''.$item['id'].'\'
				';
				$mi_product_group = DB::fetch_all($sql);
                //System::debug($mi_product_group);
                $i = 1;
                $total_amount = 0;
                $total_discount = 0;
                $total_fee = 0;
                $total_tax = 0;
				foreach($mi_product_group as $k=>$v)
                {
                    $t_dt = $mi_product_group[$k]['amount_discount'];
                    if($v['net_price']==0)
                    {
                        $total_amount += $mi_product_group[$k]['payment_price'];
                        $total_discount += $t_dt;
                        $total_fee += ($v['payment_price'] - $v['amount_discount'])*$v['service_rate']/100;
                        $total_tax += ($v['payment_price'] - $v['amount_discount'] + (($v['payment_price'] - $v['amount_discount'])*$v['service_rate']/100))*$v['tax_rate']/100;
                    }
                    else
                    {
                        $t_a = ($mi_product_group[$k]['payment_price']*100/(100+$v['tax_rate']))*100/(100+$v['service_rate']);
                        $total_amount += $t_a;
                        $t_f = ($t_a - $t_dt)*$v['service_rate']/100;
                        $total_fee += $t_f;
                        $total_tax += ($t_a - $t_dt + $t_f)*$v['tax_rate']/100;
                        
                    }
					$mi_product_group[$k]['price'] = System::display_number($v['price']);
					$mi_product_group[$k]['in_date'] = Date_Time::convert_orc_date_to_date($v['in_date'],'/');
					$mi_product_group[$k]['payment_price'] = System::display_number($v['payment_price']);
					$mi_product_group[$k]['quantity'] = System::display_number($v['quantity'] + $v['change_quantity']);
                    $mi_product_group[$k]['no'] = System::display_number($i);
                    $i += 1;
				}
				//$_REQUEST['mi_product_group'] = $mi_product_group;
                //$total_fee = ($total_amount - $total_discount)*EXTRA_SERVICE_SERVICE_CHARGE;
                //$total_tax = ($total_amount - $total_discount + $total_fee)*EXTRA_SERVICE_TAX_RATE;
                $grant_total = $total_amount - $total_discount + $total_fee + $total_tax;
                $total_fee = System::display_number(round($total_fee));
                $total_tax = System::display_number(round($total_tax));
                $grant_total = System::display_number(round($grant_total));
                $total_amount = System::display_number(round($total_amount));
                $total_discount = System::display_number(round($total_discount));
                $_REQUEST['total_fee'] = $total_fee;
                $_REQUEST['total_tax'] = $total_tax;
                $_REQUEST['grant_total'] = $grant_total;
                $_REQUEST['total_amount'] = $total_amount;
                $_REQUEST['total_discount'] = $total_discount;
                $this->map['items'] = $mi_product_group;
                //System::debug($mi_product_group);
			}
		}
        else
        {
			if(!Url::get('create_date'))
            {
				$_REQUEST['time'] = date('d/m/Y',time());
			}
			if(!Url::get('bill_number'))
            {
				$lastest_item = DB::fetch('SELECT id,bill_number,note,code FROM extra_service_invoice ORDER BY id DESC');
				$total = intval(str_replace('ES','',$lastest_item['bill_number']))+1;
				$total = (strlen($total)<2)?'0'.$total:$total;					
				$_REQUEST['bill_number'] = 'ES'.$total;
                $_REQUEST['note'] = $lastest_item['note'];
                $_REQUEST['code'] = $lastest_item['code'];
			}
		}
        $_REQUEST['lock'] = $lock;
		$this->map['in_date_options'] = $date_option_temp_str;
		$rooms = $this->get_reservations();
		$this->map['reservation_room_id_list'] = array(''=>Portal::language('select_room')) + String::get_list($rooms);
		$services = DB::select_all('extra_service',false,'name');
		foreach($services as $key=>$value)
        {
			$services[$key]['price'] = System::display_number($value['price']);
		}
		$this->map['service_options'] = '';
		foreach($services as $value){
			$this->map['service_options'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		$this->map['services'] = String::array2js($services);
		$this->map['payment_type_list'] = array(
			'SERVICE'=>Portal::language('service'),
			'ROOM'=>Portal::language('room')
		);
		$this->map['payment_method_id_list'] = String::get_list(DB::select_all('payment_type','structure_id <> \''.ID_ROOT.'\'','structure_id'));
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_extra_service_invoice'):Portal::language('edit_extra_service_invoice');
		$this->parse_layout('service_receipt_14052014',$this->map);
	}	
	function get_reservations()
    {
		if(Url::get('booking_code'))
        {
			$cond = 'AND UPPER(reservation.booking_code) LIKE \'%'.strtoupper(Url::sget('booking_code')).'%\'';
		}
        else{
			$cond = 'AND
					(
						(reservation_room.status = \'CHECKIN\' AND room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\')
						OR (reservation_room.status = \'BOOKED\' AND room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\')
					)';
		}
		$sql = '
			select
				reservation_room.id,reservation.id as reservation_id,
				CONCAT(CONCAT((CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END),CONCAT(\' - \',CONCAT(\''.Portal::language('duration').': \',CONCAT(to_char(reservation_room.arrival_time,\'DD/MM/YY\'),CONCAT(\' - \',CONCAT(to_char(reservation_room.departure_time,\'DD/MM/YY\'),CONCAT(\', \',reservation_room.status))))))),CONCAT(\' - \',CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)))) as name, 
				to_char(reservation_room.arrival_time,\'%DD/%MM/%YY\') as arrival_date,to_char(reservation_room.departure_time,\'%DD/%MM/%YY\') as departure_date
			from
				reservation_room
				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				left outer join reservation_traveller on reservation_traveller.traveller_id = reservation_room.traveller_id
				left outer join traveller on traveller.id = reservation_traveller.traveller_id
				left outer join room on room.id  =  reservation_room.room_id
				left outer join room_status on room_status.reservation_room_id  =  reservation_room.id
			where
				reservation.portal_id = \''.PORTAL_ID.'\'
				'.$cond.'
			order by
				reservation_room.arrival_time
		';
		$rrs = DB::fetch_all($sql);//OR(reservation_room.status = \'BOOKED\' and room_status.status = \'BOOKED\' and reservation_room.departure_time >= \''.Date_time::to_orc_date(date('d/m/Y')).'\')
		// (reservation_room.closed <> 1 OR reservation_room.closed IS NULL) AND
		foreach($rrs as $key=>$value)
		{
			$rrs[$key]['name'] = 'RE'.$value['reservation_id'].' - '.$value['name'];
		}
		return $rrs;
	}
}
?>