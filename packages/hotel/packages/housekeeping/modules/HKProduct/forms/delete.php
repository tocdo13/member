<?php
class DeleteHKProductForm extends Form
{
	function DeleteHKProductForm()
	{
		Form::Form("DeleteHKProductForm");
		$this->add('id',new IDType(true,'object_not_exists','hk_product'));
	}
	function on_submit()
	{
		if($this->check() and $product = DB::select('hk_product','id=\''.$_REQUEST['id'].'\''))
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
				hk_product.id
				,hk_product.price 
				,hk_product.type 
				,hk_product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	hk_product
				left outer join product_category on product_category.id=hk_product.category_id 
				left outer join unit on unit.id=hk_product.unit_id 
			where
				hk_product.id = \''.URL::get('id').'\'');
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
		DB::store_temp('hk_product',$id,'hk_temp');
		DB::delete('hk_product', 'id=\''.$id.'\'');
	}
	function update($id)
	{
		DB::update_id('hk_product',array('status'=>'NO_USE'),$id);
	}
}
?>