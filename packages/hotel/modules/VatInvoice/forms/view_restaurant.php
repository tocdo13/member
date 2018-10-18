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
        $cond = $this->generate_cond('bar_reservation',$department);
        
        $this->map['total_price_before_tax'] = 0;
        $this->map['total_service_fee'] = 0;
        $this->map['total_tax_fee'] = 0;
        
        /**
         * Hoa don nha hang
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
                    to_char(FROM_UNIXTIME(bar_reservation.time), \'DD/MM/YYYY\') as in_date
                from 
                    bar_reservation
                Where 
                    '.$cond.'  
                ';
        //echo $sql;
        $items = array();
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
			$this->parse_layout('view_restaurant',
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
        //System::debug($items);
		$this->parse_layout('view_restaurant',array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
    //tao array de in 
    function generate_final_items($final_items, $items)
    {
        
        foreach($items as $key=>$value)
        {
            //$items[$key]['description'] = $items[$key]['description'].' ('.$value['in_date'].')'.(  ( $room_name!=$value['room_name'] ) ? ' -P '.$value['room_name']:''  ).'';
            
            $items[$key]['service_fee'] = round( $items[$key]['price_before_tax'] * $items[$key]['service_rate']/100 );
            $items[$key]['tax_fee'] = round( ($items[$key]['price_before_tax']+$items[$key]['service_fee']) * $items[$key]['tax_rate']/100 );
            
            $this->map['total_price_before_tax'] += $items[$key]['price_before_tax'];
            $this->map['total_service_fee'] += $items[$key]['service_fee'];
            $this->map['total_tax_fee'] += $items[$key]['tax_fee'];
            
            //array_push($items,$hk_items[$key]);
            $final_items[$key] =  $items[$key];
        }
        return $final_items; 
    }
    
    function generate_cond($table,$department)
    {
        $cond = ' 1=1 ';
        if($table == 'vat_invoice')
            $cond.= ' and '.$table.'.bar_reservation_id = '.Url::get('bar_reservation_id');
        else
            $cond.= ' and '.$table.'.id = '.Url::get('bar_reservation_id');
        if($table == 'vat_invoice')
            $cond .= ' and vat_invoice.department = \''.$department.'\' ';  
        return $cond;
    }
}
?>