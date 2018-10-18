<?php
class EditMinibarImportForm extends Form
{
	function EditMinibarImportForm()
	{
		Form::Form('EditMinibarImportForm');
	}
	function on_submit()
	{
		if(Url::check('update'))
		{
			$sql='SELECT DISTINCT
						hk_product.id,hk_product.name_'.Portal::language().' AS name, \'\' as import_quantity
					FROM 
						minibar_product INNER JOIN hk_product ON minibar_product.product_id=hk_product.code
					ORDER BY
						hk_product.id
				';
			$minibar_products_sample=DB::fetch_all($sql);
		//-------------------------------------------------------------------------------
			$sql='SELECT 
						minibar.*
					FROM 
						minibar
					ORDER BY
						minibar.name
				';	
			DB::query($sql);
			$minibars=DB::fetch_all();
			foreach($minibars as $key=>$minibar)
			{
				$minibars[$key]['products'] = $minibar_products_sample;
			}
		//-------------------------------------------------------------------------------
			$sql='SELECT 
						minibar_product.id, minibar_id, product_id, norm_quantity-quantity as import_quantity
					FROM 
						minibar_product
						inner join hk_product on hk_product.code=product_id
				';	
			DB::query($sql);
			$products=DB::fetch_all();
		//-------------------------------------------------------------------------------
			foreach($products as $product)
			{
				$minibars[$product['minibar_id']]['products'][$product['product_id']] = $product;
				if(Url::check('check_'.$product['minibar_id']))
				{
					if(DB::exists('select id,minibar_id,product_id from minibar_product where minibar_id=\''.$product['minibar_id'].'\' and product_id=\''.$product['product_id'].'\''))
					{
						DB::query('update minibar_product set quantity=quantity+'.$product['import_quantity'].' where minibar_id=\''.$product['minibar_id'].'\' and product_id=\''.$product['product_id'].'\'');
/*						//can sua
						if(DB::exists('select store_id,product_id from store_product where store_id=7 and product_id=\''.$product['product_id'].'\' and quantity>='.$product['import_quantity'].''))
						{
							DB::query('update minibar_product set quantity=quantity+'.$product['import_quantity'].' where minibar_id=\''.$product['minibar_id'].'\' and product_id=\''.$product['product_id'].'\'');
						}
						else
						{
							Url::redirect_current(array('error'=>'1'));
						}
*/					}
				}
			}
			Url::redirect_current();
		}
		else
		{
			Url::redirect_current(array('action'=>'print'));
		}
	}	
	function draw()
	{		
//-------------------------------------------------------------------------------
		$error_message='';
		if(Url::get('error')==1)
		{
			$error_message='Trong kho housekeeping &#273;&#227; h&#7871;t!';
		}
		$sql='SELECT DISTINCT
					hk_product.code,hk_product.name_'.Portal::language().' AS name, \'\' as import_quantity
				FROM 
					minibar_product INNER JOIN hk_product ON minibar_product.product_id=hk_product.code
				ORDER BY
					hk_product.id
			';
		DB::query($sql);
		$minibar_products_sample=DB::fetch_all();		
	//-------------------------------------------------------------------------------
		$sql='SELECT 
					minibar.*
				FROM 
					minibar
				ORDER BY
					minibar.name
			';	
		DB::query($sql);
		$minibars=DB::fetch_all();
		foreach($minibars as $key=>$minibar)
		{
			$minibars[$key]['products'] = $minibar_products_sample;
		}		
//-------------------------------------------------------------------------------
		$sql='
				SELECT 
					minibar_product.id, minibar_id, product_id, norm_quantity-quantity as import_quantity
				FROM 
					minibar_product
					inner join hk_product on hk_product.code=product_id
			';
		DB::query($sql);
		$products=DB::fetch_all();
//-------------------------------------------------------------------------------
		foreach($products as $product)
		{
			$minibars[$product['minibar_id']]['products'][$product['product_id']] = $product;
		}
//-------------------------------------------------------------------------------
		$layout='edit';
		if(Url::get('action')=='print')
		{
			$layout='print';
		}
//-------------------------------------------------------------------------------
		$this->parse_layout($layout,
			array(
			'minibar_products_sample'=>$minibar_products_sample,
			'minibars'=>$minibars,
			'error_message'=>$error_message
			)
		);	
	}
}
?>