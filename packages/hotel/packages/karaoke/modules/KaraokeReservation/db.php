<?php
class KaraokeReservationNewDB{
	static function get_products($cond = '')
	{
		$row = DB::fetch_all('
			select 
				res_product.code as id, res_product.name_'.Portal::language().' as name,
				res_product.price,res_product.category_id,
				unit.name_'.Portal::language().' as unit_name,
				rownum
			from
				res_product
				left outer join unit on unit.id = res_product.unit_id
			where
				(res_product.type=\'PRODUCT\' or res_product.type=\'GOODS\')
				'.$cond.'
				AND (rownum>0 AND rownum<=2000)
			order by
				res_product.code
		');
		return $row;
		//and product.status = \'avaiable\'
	}
	static function get_karaoke_table($id)
	{
		return DB::fetch_all('
			select 
				karaoke_reservation_table.id,
				karaoke_reservation_table.order_person,
				karaoke_reservation_table.num_people as num_people,
				karaoke_table.name as name,
				karaoke_table.code as code,
				karaoke_table.id as table_id
			from 
				karaoke_reservation_table
				inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id 
			where 
				karaoke_reservation_table.karaoke_reservation_id=\''.$id.'\' and karaoke_table.portal_id=\''.PORTAL_ID.'\'
			order by
				karaoke_reservation_table.id
		');
	}
	static function get_reservation_product()
	{
		return DB::fetch_all('
			select 
				karaoke_reservation_product.id,
				karaoke_reservation_product.product_id,
				res_product.name_'.Portal::language().' as name,
				res_product.category_id,
				karaoke_reservation_product.quantity as quantity, 
				karaoke_reservation_product.quantity_discount as quantity_discount, 
				karaoke_reservation_product.price, 
				karaoke_reservation_product.discount_rate as discount_rate,
				unit.name_'.Portal::language().' as unit_name ,
				karaoke_reservation_product.printed
			from 
				karaoke_reservation_product 
				inner join res_product on res_product.code = karaoke_reservation_product.product_id
				LEFT OUTER join unit on unit.id = res_product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = res_product.category_id
			where 
				karaoke_reservation_product.karaoke_reservation_id=\''.Url::iget('id').'\' and res_product.portal_id=\''.PORTAL_ID.'\'
				
			order by
				karaoke_reservation_product.product_id
				'
		);	
	}
	static function get_reservation_category()
	{
		return DB::fetch_all('
			select 
				res_product.category_id as id,
				product_category.name
			from 
				karaoke_reservation_product
				inner join res_product on res_product.code = karaoke_reservation_product.product_id
				inner join product_category on product_category.id = res_product.category_id
				LEFT OUTER JOIN unit on unit.id = res_product.unit_id 
			where 
				karaoke_reservation_product.karaoke_reservation_id=\''.Url::iget('id').'\'
			order by
				karaoke_reservation_product.id desc
				'
		);	
	}	
}
?>