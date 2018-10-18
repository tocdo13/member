<?php
class DeleteWarehouseProductForm extends Form
{
	function DeleteWarehouseProductForm()
	{
		Form::Form("DeleteWarehouseProductForm");
		$this->add('id',new IDType(true,'object_not_exists',PRODUCT));
	}
	function on_submit()
	{
		if($this->check() and $product = DB::select(PRODUCT,'id=\''.$_REQUEST['id'].'\''))
		{
			/*----ngocnv add 08/09/2009----*/
			if(DB::select('store_product','product_id=\''.$_REQUEST['id'].'\''))
			{
				$this->error('id','product_in_use');
			}
			else
			{
				$this->delete($_REQUEST['id']);
				Url::redirect_current(array('type'));
			}
/*			//Duc sua 08/06/2009
			if(URL::get('confirm'))
			{
				$this->delete($_REQUEST['id']);
			}
			if(URL::get('edit'))
			{
				$this->update($_REQUEST['id']);
			}
*/			Url::redirect_current(array('type','category_id'));
		}
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				wh_product.id
				,wh_product.price 
				,wh_product.direct_export 
				,wh_product.type 
				,wh_product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	wh_product
				left outer join product_category on product_category.id=wh_product.category_id 
				left outer join unit on unit.id=wh_product.unit_id 
			where
				wh_product.id = \''.URL::get('id').'\'');
		if($row = DB::fetch())
		{
			$row['price'] = System::display_number($row['price']); 
			$defintition = array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL');
			if(isset($defintition[$row['type']]))
			{
				$row['type'] = $defintition[$row['type']];
			}
			else
			{
				$row['type'] = '';
			} 
		}
		$this->parse_layout('delete',$row);
	}
	function delete($id)
	{
		DB::store_temp('wh_product',$id,TEMP);
		DB::delete('wh_product', 'id=\''.$id.'\'');
	}
	function update($id)
	{
		DB::update_id('wh_product',array('status'=>'NO_USE'),$id);
	}
}
?>