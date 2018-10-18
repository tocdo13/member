<?php
class ShopInvoiceDB{
	static function get_products()
	{
		return DB::fetch_all('
			select 
				shop_product.id, shop_product.name_'.Portal::language().' as name,
				shop_product.price,
				unit.name_'.Portal::language().' as unit_name 
			from
				shop_product
				left outer join unit on unit.id = shop_product.unit_id
			where
				shop_product.type=\'PRODUCT\' or shop_product.type=\'GOODS\'
			order by
				shop_product.id
		');
	}
}
?>