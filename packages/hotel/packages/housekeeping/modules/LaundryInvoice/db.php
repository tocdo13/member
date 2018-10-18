<?php
class LaundryInvoiceDB
{
    //bo? khong dung ham nay
	static function UpdateProductStatus($time)
	{
		if(!($category = DB::select('product_category','code=\'GL\'')))
		{
			$category = array();
		}
		$sql = '
				select 
					hk_product.code as id, name_'.Portal::language().' as name, price,product_category.code
				from
					hk_product
					inner join product_category on hk_product.category_id = product_category.id
				where
					type=\'SERVICE\' and '.IDStructure::direct_child_cond($category['structure_id']).'
					and status <> \'NO_USE\'
				order by
					product_category.structure_id
			';
		$date = Date_Time::convert_time_to_ora_date($time);
		if($products = DB::fetch_all($sql)){} else $products = array();
		foreach($products as $key=>$value)
		{
			$sql = 'select
						id
					from
						hk_product_status
					where
						time = \''.$date.'\' 
						and product_id = \''.$key.'\' 
						and from_table = \'hk_product\'';
			if(!DB::exists($sql)){
				$status['time'] = $date;
				$status['product_id'] = $key;
				$status['from_table'] = 'hk_product';
				$status['status'] = 'avaiable';
				DB::insert('hk_product_status',$status);
			}
		}
	}
}
?>