<?php
class BarReservationDB{
	static function get_products($cond = '')
	{
		$row = DB::fetch_all('
			select 
				res_product.code as id, res_product.name_'.Portal::language().' as name,
				res_product.price,res_product.bar_id,
				unit.name_'.Portal::language().' as unit_name 
			from
				res_product
				left outer join unit on unit.id = res_product.unit_id
			where
				(res_product.type=\'PRODUCT\' or res_product.type=\'GOODS\') and res_product.bar_id='.Url::get('bar_id').'
				'.$cond.'
			order by
				res_product.id
		');
		return $row;
		//and product.status = \'avaiable\'
	}
	static function get_bar_table($id)
	{
		return DB::fetch_all('
			select 
				bar_table.name as name, bar_table.code as code, bar_table.num_people as num_people, 
				bar_reservation_table.table_id as id 
			from 
				bar_reservation_table inner join bar_table on bar_table.id = bar_reservation_table.table_id 
			where 
				bar_reservation_table.bar_reservation_id=\''.$id.'\'
		');
	}
	static function get_reservation_product()
	{
		return DB::fetch_all('
			select 
				res_product.name_'.Portal::language().' as name, 
				bar_reservation_product.product_id as id, bar_reservation_product.quantity as quantity, 
				bar_reservation_product.quantity_discount as quantity_discount, bar_reservation_product.price, 
				bar_reservation_product.discount_rate as discount_rate,
				unit.name_'.Portal::language().' as unit_name 
			from 
				bar_reservation_product 
                inner join res_product on res_product.code = bar_reservation_product.product_id and res_product.bar_id='.Url::get('bar_id').'
				inner join unit on unit.id = res_product.unit_id 
			where 
				bar_reservation_product.bar_reservation_id=\''.Url::get('id').'\''
		);	
	}
}
?>