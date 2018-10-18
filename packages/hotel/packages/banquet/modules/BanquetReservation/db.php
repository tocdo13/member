<?php
class BanquetReservationDB{
    static function get_reservation_product($type)
	{
		return DB::fetch_all('
			select 
				party_reservation_detail.id,
				party_reservation_detail.product_id,
                CASE
                    WHEN
                            party_reservation_detail.product_name is not NULL    
                    THEN
                        	party_reservation_detail.product_name 
                    ELSE
                            product.name_'.Portal::language().'          
                END name,
				product.category_id,
				product.type,
				party_reservation_detail.quantity as quantity, 
				party_reservation_detail.price,
                CASE
                    WHEN
                            party_reservation_detail.product_unit is not NULL    
                    THEN
                        	party_reservation_detail.product_unit
                    ELSE
                           unit.name_'.Portal::language().'         
                END unit_name,
                unit.id as unit_id,
                0 as discount_rate
			from 
				party_reservation_detail 
				inner join product on product.id = party_reservation_detail.product_id
				inner join unit on unit.id = product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
			where
                party_reservation_detail.type ='.$type.' and 
				party_reservation_detail.party_reservation_id=\''.Url::get('id').'\'
			order by
				party_reservation_detail.id ASC
				'
		);	// 81: Mon nuong
	}
	static function get_reservation_category()
	{
		return DB::fetch_all('
			select 
				product.category_id as id,
				product_category.name
			from 
				bar_reservation_product
				inner join product on product.id = bar_reservation_product.product_id
				INNER JOIN wh_start_term_remain ON wh_start_term_remain.product_id = product.id
				INNER JOIN warehouse ON warehouse.id = wh_start_term_remain.warehouse_id
				inner join product_category on product_category.id = product.category_id
				inner join unit on unit.id = bar_reservation_product.unit_id 
			where 
				bar_reservation_product.bar_reservation_id=\''.Url::get('id').'\' and warehouse.code=\'REST\'
			order by
				bar_reservation_product.id ASC
				'
		);	
	}
	static function get_units(){
		$list_units = DB::fetch_all('select id,name_'.Portal::language().' as name from unit order by name');	 
		$units = '<option value="">--Unit--</option>';
		foreach($list_units as $id => $value){
			$units .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		return $units;
	}
	static function get_code_in_day($status,$arrival_time,$bar_id){
		$time =  Date_Time::to_time(date('d/m/Y',$arrival_time));
		$bar_code = DB::fetch('select code from bar where id = '.$bar_id.'','code');
		$sql = 'SELECT code from bar_reservation where id=(select max(id) from bar_reservation where arrival_time >='.$time.' and arrival_time <'.($time + (24*3600)).' ) ';
		$code = DB::fetch($sql,'code');
		if($code){	
			$location_max = strrpos($code,'-') +1;
			$code_max = substr($code,$location_max) + 1;
			if(strlen($code_max) == 1){
				$code_max = '0'.$code_max;	
			}	
		}else{
			$code_max = '01';	
		}
		$code_new = $bar_code.'-'.date('d',$arrival_time).date('m',$arrival_time).date('y',$arrival_time).'-'.$code_max;		
		echo $code_new; //exit();
		return $code_new;
	}
	function get_reservation_banquet_room($type)
	{
		return DB::fetch_all('
			select 
				party_reservation_room.id,
				party_reservation_room.id as banquet_reservation_room_id,				
				party_reservation_room.party_room_id as banquet_room_id,
				party_reservation_room.note, 
				party_reservation_room.price as total, 
				party_reservation_room.time_type,
                party_reservation_room.address,
				party_room.name,
				party_room.group_name
			from 
				party_reservation_room 
				INNER JOIN party_room on party_room.id = party_reservation_room.party_room_id
			where 
                party_reservation_room.type='.$type.'
				and party_reservation_room.party_reservation_id=\''.Url::iget('id').'\'
			order by
				party_reservation_room.id ASC
				'
		);	// 81: Mon nuong
	}
}
?>