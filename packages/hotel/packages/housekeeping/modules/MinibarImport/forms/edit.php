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
            $sql='SELECT 
                    DISTINCT
					product.id as id,
                    product.name_'.Portal::language().' AS name, 
                    \'\' as import_quantity
				FROM 
					minibar_product 
                    inner join product ON minibar_product.product_id=product.id
                    inner join product_price_list on product.id = product_price_list.product_id
				WHERE
					product_price_list.portal_id=\''.PORTAL_ID.'\'
				ORDER BY
					product.id
			';
			$minibar_products_sample=DB::fetch_all($sql);
            //System::debug($minibar_products_sample);
            
		//-------------------------------------------------------------------------------
            $sql='SELECT 
					minibar.*, 
                    room.name as room_name
				FROM 
					minibar
					inner join room on minibar.room_id = room.id
				WHERE
					room.portal_id=\''.PORTAL_ID.'\'
				ORDER BY
					minibar.name
			';

			$minibars=DB::fetch_all($sql);			
			foreach($minibars as $key=>$minibar)
			{
				$minibars[$key]['products'] = $minibar_products_sample;
			}
            //System::debug($minibars);
            

		//-------------------------------------------------------------------------------
        //norm_quantity-quantity as import_quantity
			$sql='
				SELECT 
					minibar_product.id, 
                    minibar_id, 
                    product_id, 
                    (quantity * -1) as import_quantity
				FROM 
					minibar_product
					inner join product on product.id=product_id
					inner join minibar on minibar_product.minibar_id = minibar.id
					inner join room on minibar.room_id = room.id
			';
			$products=DB::fetch_all($sql);
            //System::debug($minibars);
            //echo "<hr/>";
            //System::debug($products);
		//-------------------------------------------------------------------------------
			foreach($products as $product)
			{
				$minibars[$product['minibar_id']]['products'][$product['product_id']] = $product;
				if(Url::check('check_'.$product['minibar_id']))
				{
				    /**
				     * Nhập hàng tức là cho số lượng sử dụng về = 0 ???
				     */
					if(DB::exists('select id,minibar_id,product_id from minibar_product where minibar_id=\''.$product['minibar_id'].'\' and product_id=\''.$product['product_id'].'\''))
					{
                        //echo 'update minibar_product set quantity=quantity+'.$product['import_quantity'].' where minibar_id=\''.$product['minibar_id'].'\' and product_id=\''.$product['product_id'].'\'';
						DB::query('update minibar_product set quantity=quantity+'.$product['import_quantity'].' where minibar_id=\''.$product['minibar_id'].'\' and product_id=\''.$product['product_id'].'\'');
					}
				}
			}
            //System::debug($minibars);
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
        //Lấy các sp đã nhập định mức, chuyển \'\' as import_quantity thành norm_quantity 
		$sql='SELECT 
                    DISTINCT
					product.id as id,
                    product.name_'.Portal::language().' AS name, 
                    \'\' as import_quantity
				FROM 
					minibar_product 
                    inner join product ON minibar_product.product_id=product.id
                    inner join product_price_list on product.id = product_price_list.product_id
				WHERE
					product_price_list.portal_id=\''.PORTAL_ID.'\'
				ORDER BY
					product.id
			';
		DB::query($sql);
		$minibar_products_sample=DB::fetch_all();
        //System::debug($minibar_products_sample);
        //System::debug($sql);
        //Lấy các minibar và gán sp vào đó
		$sql='SELECT 
					minibar.*, 
                    room.name as room_name
				FROM 
					minibar
					inner join room on minibar.room_id = room.id
				WHERE
					room.portal_id=\''.PORTAL_ID.'\'
                    and room.close_room=1
				ORDER BY
					minibar.name
			';	
		DB::query($sql);
		$minibars=DB::fetch_all();
		foreach($minibars as $key=>$minibar)
		{
			$minibars[$key]['products'] = $minibar_products_sample;
		}
        //System::debug($minibars);
        		
//-------------------------------------------------------------------------------
        //norm_quantity-quantity as import_quantity,
		$sql='
				SELECT 
					minibar_product.id, 
                    minibar_id, 
                    product_id, 
                    norm_quantity as import_quantity,
                    (quantity * -1) as quantity
				FROM 
					minibar_product
					inner join product on product.id=minibar_product.product_id
					inner join minibar on minibar_product.minibar_id = minibar.id
					inner join room on minibar.room_id = room.id
				WHERE
					minibar_product.portal_id=\''.PORTAL_ID.'\'
                    and room.close_room=1
			';
		$products=DB::fetch_all($sql);
        //System::debug($products);
        //System::debug($minibars);
//-------------------------------------------------------------------------------
		foreach($products as $product)
		{
			$minibars[$product['minibar_id']]['products'][$product['product_id']] = $product;
		}
        //System::debug($minibars);
//-------------------------------------------------------------------------------
		$layout='edit';
		if(Url::get('action')=='print')
		{
			$layout='print';
		}
//-------------------------------------------------------------------------------
        //System::debug($error_message);
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