<?php
class Table
{
	function get_busy_tables($start_time,$end_time)
	{
		DB::query('
			select 
				karaoke_table.id, 
				karaoke_table.name,
				karaoke_table.num_people,
				table_id,
				karaoke_table.num_people,
				karaoke_reservation_id,
				karaoke_reservation.code,
				karaoke_reservation.status,
				karaoke_reservation.note,
				agent_name,
				agent_address, 
				agent_phone, 
				arrival_time,
				departure_time
			from
				karaoke_table
				inner join karaoke_reservation_table on table_id = karaoke_table.id
				inner join karaoke_reservation
					on karaoke_reservation_id = karaoke_reservation.id 
						and departure_time>='.$start_time.' 
						and arrival_time<'.$end_time.' 
						and karaoke_reservation.status<>\'CANCEL\' 
						
			where
				1=1
		');
		$items = DB::fetch_all();

		$tables = array();
		foreach($items as $item)
		{
			if(!isset($tables[$item['table_id']]))
			{
				$item['id'] = $item['table_id'];
				$tables[$item['table_id']] = $item;
			}
			else
			if($item['karaoke_reservation_id']!=$tables[$item['table_id']]['karaoke_reservation_id'])
			{
				$tables[$item['table_id']]['karaoke_reservation_id'] = 'MIXED';
				$tables[$item['table_id']]['status'] = 'MIXED';
			}
		}
		return $tables;
	}
	
	function get_available_table($from_time, $to_time, $tables_exclusion=false)
	{
		if($tables_exclusion)
		{
			$cond=' and karaoke_table.id NOT IN ('.$tables_exclusion.')';
		}
		else
		{
			$cond='';
		}
		
		DB::query('
			select 
				distinct karaoke_table.id
			from
				karaoke_table
				inner join karaoke_reservation_table on table_id = karaoke_table.id
				inner join karaoke_reservation
					on karaoke_reservation_id = karaoke_reservation.id and karaoke_reservation.status<>\'CHECKOUT\' 
			where
				1=1 and karaoke_table.portal_id=\''.PORTAL_ID.'\'
				and ((karaoke_reservation.time_out=0 and karaoke_reservation.departure_time>'.$from_time.') or (karaoke_reservation.time_out!=0 and karaoke_reservation.time_out>'.$from_time.'))
				and ((karaoke_reservation.time_in=0 and karaoke_reservation.arrival_time<'.$to_time.') or (karaoke_reservation.time_in<'.$to_time.' and karaoke_reservation.time_in!=0))
				'.$cond
		);
		$busy_tables = DB::fetch_all();
		DB::query('
			select 
				karaoke_table.*
			from
				karaoke_table
			where
				1=1 and karaoke_table.portal_id=\''.PORTAL_ID.'\'
			order by karaoke_table.name 
		');
		$tables = DB::fetch_all();
		foreach($busy_tables as $busy_table)
		{
			unset($tables[$busy_table['id']]);
		}
		return $tables;
	}
	function get_privilege_karaoke()
    { // khoand sua ngay 04/10/2011
		$karaokes = DB::fetch_all('SELECT id,privilege FROM karaoke where portal_id=\''.PORTAL_ID.'\'');
		if(!User::is_admin())
        {
			$cond = ' and (';
			//$cond = '';
			$i = 1;
			foreach($karaokes as $key => $value)
            {	
				if(User::can_edit(Portal::get_module_id($value['privilege']),ANY_CATEGORY))
                {
					if($i == 1)
                    {
						$cond .= ' karaoke.id = '.$value['id'].'';
					}
                    else
                    {
						$cond .= ' or karaoke.id = '.$value['id'].'';
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
	function check_table_conflict($start_time,$end_time,$tables)
	{
		$arr = array();
		$busy_tables = Table::get_busy_tables($start_time,$end_time);
		if($tables)
		{
			foreach($tables as $key=>$value)
			{
				$arr[$key] = false;
				if(isset($busy_tables[$key]) and $busy_tables[$key])
				{
					$arr[$key] = $busy_tables[$key]['karaoke_reservation_id'];
				}
			}
		}
		return $arr;
	}
	function get_code_karaoke_reservation($karaoke_reservation_id){
		$code = '';
		$leng = strlen($karaoke_reservation_id);
		for($j=0;$j<6-$leng;$j++){
			$code .= '0';	
		}
		$code = date('Y').'-'.$code.$karaoke_reservation_id;
		return $code;
	}
	function updateTotalKaraoke($id){
		$products = DB::fetch_all('SELECT karaoke_reservation_product.*
										,karaoke_reservation.full_charge
										,karaoke_reservation.full_rate
										,karaoke_reservation.discount
										,karaoke_reservation.discount_percent 
										,karaoke_reservation.tax_rate
										,karaoke_reservation.karaoke_fee_rate as service_rate
									FROM karaoke_reservation_product 
										INNER JOIN karaoke_reservation ON karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
									WHERE karaoke_reservation_id='.$id.'');	
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
		DB::update('karaoke_reservation',array('total_before_tax'=>round($total_before_tax,0),'total'=>round($total,0)),' id='.$id);
	}
}
?>