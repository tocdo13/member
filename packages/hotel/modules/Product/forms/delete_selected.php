<?php
class DeleteSelectedProductForm extends Form
{
	function DeleteSelectedProductForm()
	{
		Form::Form("DeleteSelectedProductForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				if($product=DB::select('product','id=\''.$id.'\''))
				{
					if(DB::select('store_product','product_id=\''.$id.'\''))
					{
						$this->error('confirm','product_in_use');
					}
					else
					{
						DeleteProductForm::delete($id);
					}
				}
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				product.id
				,product.type 
				,product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	product
				left outer join product_category on product_category.id=product.category_id 
				left outer join unit on unit.id=product.unit_id 
			where
				product.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')
		');
		$items = DB::fetch_all();
		$bar_reservation_product = DB::fetch_all('select product_id as id from bar_reservation_product');
        $housekeeping_invoice_detail = DB::fetch_all('select product_id as id from housekeeping_invoice_detail');
        $wh_invoice_detail = DB::fetch_all('select product_id as id from wh_invoice_detail');
        $wh_start_term_remain = DB::fetch_all('select product_id as id from wh_start_term_remain');
        $massage_product_consumed = DB::fetch_all('select product_id as id from massage_product_consumed');
        $product_material = DB::fetch_all('select product_id as id from product_material');
        
        foreach($items as $key=>$item)
		{
			$defintition = array('goods'=>'goods','product'=>'product','material'=>'material','equipment'=>'equipment','tool'=>'tool');
			if(isset($defintition[$items[$key]['type']]))
			{
				$items[$key]['type'] = $defintition[$items[$key]['type']];
			}
			else
			{
				$items[$key]['type'] = '';
			} 
            
            $items[$key]['check'] = 0;
            if(isset($bar_reservation_product[$item['id']]) || isset($housekeeping_invoice_detail[$item['id']]) || isset($wh_invoice_detail[$item['id']]) || isset($massage_product_consumed[$item['id']]) || isset($product_material[$item['id']]) || isset($wh_start_term_remain[$item['id']]) )
            {
                $items[$key]['check'] = 1;
            }
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>