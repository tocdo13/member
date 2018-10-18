<?php
class Table
{
	function get_busy_tables($start_time,$end_time,$bar_reservation_id=false)
	{
	   $cond = '';
	    if($bar_reservation_id)
            $cond = 'AND bar_reservation.id!='.$bar_reservation_id;
		DB::query('
			select 
				bar_table.id, 
				bar_table.name,
				bar_table.num_people,
				table_id,
				bar_table.num_people,
				bar_reservation_id,
				bar_reservation.code,
				bar_reservation.status,
				bar_reservation.note,
				agent_name,
				agent_address, 
				agent_phone, 
				arrival_time,
				departure_time
			from
				bar_table
				inner join bar_reservation_table on table_id = bar_table.id
				inner join bar_reservation
					on bar_reservation_id = bar_reservation.id 
						and departure_time>='.$start_time.' 
						and arrival_time<'.$end_time.' 
						and bar_reservation.status<>\'CANCEL\' 
						'.$cond.'
			where
				1=1
		');
		$items = DB::fetch_all();
        //System::debug($items);
		$tables = array();
		foreach($items as $item)
		{
			if(!isset($tables[$item['table_id']]))
			{
				$item['id'] = $item['table_id'];
				$tables[$item['table_id']] = $item;
			}
			else
			if($item['bar_reservation_id']!=$tables[$item['table_id']]['bar_reservation_id'])
			{
				$tables[$item['table_id']]['bar_reservation_id'] = 'MIXED';
				$tables[$item['table_id']]['status'] = 'MIXED';
			}
		}
		return $tables;
	}
	
	function get_available_table($from_time, $to_time, $tables_exclusion=false)
	{
		if($tables_exclusion)
		{
			$cond=' and bar_table.id NOT IN ('.$tables_exclusion.')';
		}
		else
		{
			$cond='';
		}
		
		DB::query('
			select 
				distinct bar_table.id
			from
				bar_table
				inner join bar_reservation_table on table_id = bar_table.id
				inner join bar_reservation
					on bar_reservation_id = bar_reservation.id and bar_reservation.status<>\'CHECKOUT\' 
			where
				1=1 and bar_table.portal_id=\''.PORTAL_ID.'\'
				and ((bar_reservation.time_out=0 and bar_reservation.departure_time>'.$from_time.') or (bar_reservation.time_out!=0 and bar_reservation.time_out>'.$from_time.'))
				and ((bar_reservation.time_in=0 and bar_reservation.arrival_time<'.$to_time.') or (bar_reservation.time_in<'.$to_time.' and bar_reservation.time_in!=0))
				'.$cond
		);
		$busy_tables = DB::fetch_all();
		DB::query('
			select 
				bar_table.*
			from
				bar_table
			where
				1=1 and bar_table.portal_id=\''.PORTAL_ID.'\'
			order by bar_table.name 
		');
		$tables = DB::fetch_all();
		foreach($busy_tables as $busy_table)
		{
			unset($tables[$busy_table['id']]);
		}
		return $tables;
	}
	function get_privilege_bar()
    { // khoand sua ngay 04/10/2011
		$bars = DB::fetch_all('SELECT id,privilege FROM bar where portal_id=\''.PORTAL_ID.'\'');
		if(!User::is_admin())
        {
			$cond = ' and (';
			//$cond = '';
			$i = 1;
			foreach($bars as $key => $value)
            {	
				if(User::can_edit(Portal::get_module_id($value['privilege']),ANY_CATEGORY))
                {
					if($i == 1)
                    {
						$cond .= ' bar.id = '.$value['id'].'';
					}
                    else
                    {
						$cond .= ' or bar.id = '.$value['id'].'';
					}
					$i++;
				}
			}
			$cond .= ')';
			if($i>1)
            {
				return $cond;
			}
            else
            {
				return false;
			}
			return $cond;
		}
        else
        {
			return false;	
		}
	}
	function check_table_conflict($start_time,$end_time,$tables,$bar_reservation_id=false)
	{
		$arr = array();
        if(!$bar_reservation_id)
		  $busy_tables = Table::get_busy_tables($start_time,$end_time);
        else
            $busy_tables = Table::get_busy_tables($start_time,$end_time,$bar_reservation_id);
		if($tables)
		{
			foreach($tables as $key=>$value)
			{
				$arr[$key] = false;
				if(isset($busy_tables[$key]) and $busy_tables[$key])
				{
					$arr[$key] = $busy_tables[$key]['bar_reservation_id'];
				}
			}
		}
		return $arr;
	}
	function get_code_bar_reservation($bar_reservation_id){
		/*$code = '';
		$leng = strlen($bar_reservation_id);
		for($j=0;$j<6-$leng;$j++){
			$code .= '0';	
		}
		$code = date('Y').'-'.$code.$bar_reservation_id;
		return $code;*/
        $sql = 'SELECT max(TO_NUMBER(REPLACE(bar_reservation.code,\'-\',\'\'))) as location FROM bar_reservation WHERE bar_reservation.portal_id=\''.PORTAL_ID.'\'';
		$location = DB::fetch($sql);
        $location['location'] = (int)(substr($location['location'],4)) + 1;
        $location['location'] = str_pad($location['location'], 7, "0", STR_PAD_LEFT);
        $code = date('Y').'-'.$code.($location['location']);
        return $code;
	}
	function updateTotalBar($id){
		$products = DB::fetch_all('SELECT bar_reservation_product.*
										,bar_reservation.full_charge
										,bar_reservation.full_rate
										,bar_reservation.discount
										,bar_reservation.discount_percent 
										,bar_reservation.tax_rate
										,bar_reservation.bar_fee_rate as service_rate
									FROM bar_reservation_product 
										INNER JOIN bar_reservation ON bar_reservation_product.bar_reservation_id = bar_reservation.id
									WHERE bar_reservation_id='.$id.'');	
		$total_amount = 0;
		$tax_rate = 0;
		$service_rate = 0;
		$discount = 0;
		$discount_percent = 0;
		foreach($products as $k =>$value){
			if($value['full_rate']==1){
				$param = (1+($value['tax_rate']*0.01) + ($value['service_rate']*0.01) + (($value['tax_rate']*0.01)*($value['service_rate']*0.01)));
				$value['price'] = round($value['price']/$param,2);
			}else if($value['full_charge']==1){	
				$param = (1+($value['service_rate']*0.01));
				$value['price'] = round($value['price']/$param,2);		
			}
			$net_amount = ($value['price'] * ($value['quantity'] - $value['quantity_discount']));
			$total_amount += ($net_amount - $net_amount*$value['discount_category']/100 - (($net_amount - $net_amount*$value['discount_category']/100)*$value['discount_rate']/100));
			$tax_rate = $value['tax_rate'];
			$service_rate = $value['service_rate'];
			$discount = $value['discount'];
			$discount_percent = $value['discount_percent'];
		}
		$total = $total_amount - $total_amount*$discount_percent/100 - $discount;
		$total_before_tax = $total;
		$total = $total + $total*$service_rate*0.01 + ($total + $total*$service_rate*0.01)*$tax_rate*0.01; 
		DB::update('bar_reservation',array('total_before_tax'=>round($total_before_tax,0),'total'=>round($total,0)),' id='.$id);
	}
}
?>
