<?php 
class RestaurantWarehouseInvoiceDB
{
	static function get_store_remain()
	{
		$cond = '1=1';
		$sql = '
			SELECT
				hk_wh_invoice_detail.id,hk_wh_invoice_detail.product_id,
				hk_wh_invoice_detail.num,
				hk_wh_invoice.type,wh_product.start_term_quantity
			FROM
				hk_wh_invoice_detail
				INNER JOIN wh_product ON wh_product.id = hk_wh_invoice_detail.product_id
				INNER JOIN unit ON unit.id = wh_product.unit_id
				INNER JOIN hk_wh_invoice ON hk_wh_invoice.id= hk_wh_invoice_detail.invoice_id
				LEFT OUTER JOIN warehouse ON warehouse.id = hk_wh_invoice.warehouse_id
			WHERE
				'.$cond.'
			ORDER BY
				hk_wh_invoice_detail.product_id	
		';
		$items = DB::fetch_all($sql);
		$sql = '
			SELECT
				wh_product.id,wh_product.id as product_id,
				wh_product.start_term_quantity,wh_product.start_term_quantity as remain_number,0 as import_number,0 as export_number
			FROM
				wh_product
				INNER JOIN unit ON unit.id = wh_product.unit_id	
			WHERE
				wh_product.start_term_quantity>0
			ORDER BY
				wh_product.category_id,wh_product.id
		';
		$products = DB::fetch_all($sql);
		$i = 0;
		foreach($products as $key=>$value){
			$products[$key]['start_term_quantity'] = 0;
			$products[$key]['remain_number'] = 0;
		}
		$new_products = $products;//array();
		foreach($items as $key=>$value){
			$product_id = $value['product_id'];
			if(isset($new_products[$product_id]['id'])){
				if($value['type']=='IMPORT'){
					$new_products[$product_id]['import_number'] += $value['num'];
				}else{
					$new_products[$product_id]['export_number'] += $value['num'];
				}
				$new_products[$product_id]['remain_number'] = $new_products[$product_id]['import_number'] - $new_products[$product_id]['export_number'];
				$new_products[$product_id]['remain_number'] += $new_products[$product_id]['start_term_quantity'];
			}else{
				$new_products[$product_id]['id'] = $product_id;
				$new_products[$product_id]['product_id'] = $product_id;
				if($value['type']=='IMPORT'){
					$new_products[$product_id]['import_number'] = $value['num'];
				}else{
					$new_products[$product_id]['export_number'] = $value['num'];
				}
				$new_products[$product_id]['remain_number'] = $new_products[$product_id]['import_number'] - $new_products[$product_id]['export_number'];
			}
		}
		return $new_products;		
	}
}
?>