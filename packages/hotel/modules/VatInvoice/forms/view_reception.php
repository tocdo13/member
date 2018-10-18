<?php
class VatInvoiceViewForm extends Form
{
	function VatInvoiceViewForm()
	{
		Form::Form('VatInvoiceViewForm');	
	}
	function draw()
	{
        $this->map = array();
        $this->map['is_modify'] = Url::get('is_modify');
        $department = Url::get('department');
        $type = Url::get('type');
        $cond = $this->generate_cond('reservation_room',$type,$department);
        //r_id : ma res
        //r_r_id ma res_room
        /**
         * Tien phong
         */
        $sql = 'select
                    room_status.id,
                    room_status.change_price as price_before_tax,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                    COALESCE(reservation_room.reduce_amount,0) as reduce_amount,
                    to_char(room_status.in_date, \'DD/MM/YYYY\') as in_date,
                    room.name as room_name
                from 
                    reservation_room
                    inner join room_status on reservation_room.id = room_status.reservation_room_id
                    inner join room on reservation_room.room_id = room.id
                Where 
                    '.$cond.'
                    and room_status.change_price != 0
                Order by 
                    room.name    
                ';
        //echo $sql;
        $items = DB::fetch_all($sql);
        $this->map['total_price_before_tax'] = 0;
        $this->map['total_service_fee'] = 0;
        $this->map['total_tax_fee'] = 0;
        //fill ten phong (trong hd group de phan biet cac phong)
        $room_name = false; 
        foreach($items as $key=>$value)
        {
            $items[$key]['description'] = ''.$value['in_date'].''.(  ( $room_name!=$value['room_name'] ) ? ' - '.$value['room_name']:''  ).' '.Portal::language('room_charge');
            $room_name = $value['room_name'];
            if($items[$key]['net_price']==1)
            {
                $items[$key]['price_before_tax'] = $items[$key]['price_before_tax'] / (1+$items[$key]['tax_rate']/100);
                $items[$key]['price_before_tax'] = round( $items[$key]['price_before_tax'] / (1+$items[$key]['service_rate']/100) );
            }
            $items[$key]['service_fee'] = round( $items[$key]['price_before_tax'] * $items[$key]['service_rate']/100 );
            $items[$key]['tax_fee'] = round( ($items[$key]['price_before_tax']+$items[$key]['service_fee']) * $items[$key]['tax_rate']/100 );
            $this->map['total_price_before_tax'] += $items[$key]['price_before_tax'];
            $this->map['total_service_fee'] += $items[$key]['service_fee'];
            $this->map['total_tax_fee'] += $items[$key]['tax_fee'];  
        }
        /**
         * Hoa don phong
         */
        $sql = 'select
                    housekeeping_invoice.id || \'-hk\' as id,
                    housekeeping_invoice.total_before_tax as price_before_tax,
                    housekeeping_invoice.tax_rate,
                    housekeeping_invoice.fee_rate as service_rate,
                    0 as net_price,
                    0 as reduce_balance,
                    0 as reduce_amount,
                    DECODE(
                    housekeeping_invoice.type,          \'EQUIP\',\''.Portal::language('compensation_invoice').'\',
                                                        \'LAUNDRY\',\''.Portal::language('laundry_invoice').'\',
                                                        \'MINIBAR\',\''.Portal::language('minibar_invoice').'\',
                                                        \'\'
                    )  as description,
                    to_char(FROM_UNIXTIME(housekeeping_invoice.time), \'DD/MM/YYYY\') as in_date,
                    room.name as room_name
                from 
                    reservation_room
                    inner join housekeeping_invoice on reservation_room.id = housekeeping_invoice.reservation_room_id
                    inner join room on reservation_room.room_id = room.id
                Where 
                    '.$cond.'
                Order by
                    room.name     
                ';
        $hk_items = DB::fetch_all($sql);
        $items = $this->generate_final_items($items,$hk_items); 
        /**
         * Hoa don dich vu mo rong
         */
        /*
        $sql = 'select
                    extra_service_invoice.id || \'-extra\' as id,
                    DECODE(
                    extra_service_invoice.total_before_tax, \'\',extra_service_invoice.total_amount,
                                                            extra_service_invoice.total_before_tax
                    )  as price_before_tax,
                    DECODE(
                    extra_service_invoice.total_before_tax, \'\',0,
                                                            extra_service_invoice.tax_rate
                    )  as tax_rate,
                    DECODE(
                    extra_service_invoice.total_before_tax, \'\',0,
                                                            extra_service_invoice.service_rate
                    )  as service_rate,
                    0 as net_price,
                    0 as reduce_balance,
                    0 as reduce_amount,
                    \''.Portal::language('extra_service_invoice').'\' as description,
                    to_char(FROM_UNIXTIME(extra_service_invoice.time), \'DD/MM/YYYY\') as in_date,
                    room.name as room_name
                from 
                    reservation_room
                    inner join extra_service_invoice on reservation_room.id = extra_service_invoice.reservation_room_id
                    inner join room on reservation_room.room_id = room.id
                Where 
                    '.$cond.'
                Order by
                    room.name    
                ';
        */
        
        $sql = 'select
                    extra_service_invoice_detail.id || \'-extra\' as id,
                    extra_service_invoice_detail.price * extra_service_invoice_detail.quantity as price_before_tax,
                    extra_service_invoice.tax_rate,
                    extra_service_invoice.service_rate,
                    0 as net_price,
                    0 as reduce_balance,
                    0 as reduce_amount,
                    extra_service_invoice_detail.name as description,
                    to_char(FROM_UNIXTIME(extra_service_invoice.time), \'DD/MM/YYYY\') as in_date,
                    room.name as room_name
                from 
                    reservation_room
                    inner join extra_service_invoice on reservation_room.id = extra_service_invoice.reservation_room_id
                    inner join extra_service_invoice_detail on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                    inner join room on reservation_room.room_id = room.id
                Where 
                    '.$cond.'
                Order by
                    room.name    
                ';
        //System::debug($sql) ;
        $ex_items = DB::fetch_all($sql);
        //System::debug($ex_items);
        $items = $this->generate_final_items($items,$ex_items);
        /**
         * Hoa don nha hang neu dc chuyen ve phong
         */
        $sql = 'select
                    bar_reservation.id || \'-bar\' as id,
                    bar_reservation.total_before_tax as price_before_tax,
                    bar_reservation.tax_rate,
                    bar_reservation.bar_fee_rate as service_rate,
                    0 as net_price,
                    0 as reduce_balance,
                    0 as reduce_amount,
                    \''.Portal::language('bar_invoice').'\' as description,
                    to_char(FROM_UNIXTIME(bar_reservation.time), \'DD/MM/YYYY\') as in_date,
                    room.name as room_name
                from 
                    reservation_room
                    inner join bar_reservation on reservation_room.id = bar_reservation.reservation_room_id
                    inner join room on reservation_room.room_id = room.id
                Where 
                    '.$cond.'
                Order by
                    room.name     
                ';
        //echo $sql;
        $bar_items = DB::fetch_all($sql);
        $items = $this->generate_final_items($items,$bar_items);
        $this->map['total'] = $this->map['total_price_before_tax']+ $this->map['total_service_fee']+ $this->map['total_tax_fee'];
        foreach($this->map as $key=>$value)
        {
            $this->map[$key] = System::display_number($this->map[$key]);
        }
        require_once 'packages/core/includes/utils/currency.php';
        $this->map['total_in_words'] = currency_to_text(System::calculate_number($this->map['total']));
		$this->print_all_pages($items);
	}
    function print_all_pages($items)
	{
        $line_per_page = Url::get('line_per_page');
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($items as $key=>$item)
		{
			if($count>=$line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        
		if(sizeof($pages)>0)
		{
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('view_reception',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
    function print_page($items, $page_no, $total_page)
	{
        $i = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['stt'] = $i++;
            $items[$key]['price_before_tax'] = System::display_number($items[$key]['price_before_tax']);
            $items[$key]['service_fee'] = System::display_number($items[$key]['service_fee']);
            $items[$key]['tax_fee'] = System::display_number($items[$key]['tax_fee']);
            //$items[$key]['change_price'] = System::display_number($items[$key]['change_price']);
            
        }
		$this->parse_layout('view_reception',array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
    //tao array de in 
    function generate_final_items($final_items, $items)
    {
        $room_name = false; 
        
        foreach($items as $key=>$value)
        {
            $items[$key]['description'] = ''.$value['in_date'].''.(  ( $room_name!=$value['room_name'] ) ? ' - '.$value['room_name']:''  ).' '.$items[$key]['description'];
            $room_name = $value['room_name'];
            
            $items[$key]['service_fee'] = round( $items[$key]['price_before_tax'] * $items[$key]['service_rate']/100 );
            $items[$key]['tax_fee'] = round( ($items[$key]['price_before_tax']+$items[$key]['service_fee']) * $items[$key]['tax_rate']/100 );
            
            $this->map['total_price_before_tax'] += $items[$key]['price_before_tax'];
            $this->map['total_service_fee'] += $items[$key]['service_fee'];
            $this->map['total_tax_fee'] += $items[$key]['tax_fee'];
            
            //array_push($items,$hk_items[$key]);
            $final_items[$key] =  $items[$key];
        }
        $this->map['total_price_before_tax'] = round($this->map['total_price_before_tax']);
        return $final_items; 
    }
    
    function generate_cond($table,$type,$department)
    {
        $cond = ' 1=1 ';
        if($type == 'SINGLE')//HD tung phong
        {
            $cond .= ' and '.$table.'.reservation_id = '.Url::get('reservation_id');
            if($table == 'vat_invoce')
                $cond.= ' and '.$table.'.reservation_room_id = '.Url::get('reservation_room_id');
            else
                $cond.= ' and '.$table.'.id = '.Url::get('reservation_room_id');
        }
        else//Hd ca nhom
            $cond .= ' and '.$table.'.reservation_id = '.Url::get('reservation_id');
        if($table == 'vat_invoice')
            $cond .= ' and vat_invoice.type = \''.$type.'\' and vat_invoice.department = \''.$department.'\' '; 
        return $cond;
    }
}
?>