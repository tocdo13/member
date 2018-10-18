<?php
class DeleteRestaurantProductForm extends Form
{
	function DeleteRestaurantProductForm()
	{
		Form::Form("DeleteRestaurantProductForm");
		$this->add('id',new IDType(true,'object_not_exists','res_product'));
	}
	function on_submit()
	{
		if($this->check() and $product = DB::select('res_product','id=\''.$_REQUEST['id'].'\''))
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
				res_product.id
				,res_product.price 
				,res_product.type 
				,res_product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	res_product
				left outer join product_category on product_category.id = res_product.category_id 
				left outer join unit on unit.id=res_product.unit_id 
			where
				res_product.id = \''.URL::get('id').'\'');
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
		//DB::store_temp('res_product',$id,TEMP);
		DB::delete('res_product', 'id=\''.$id.'\'');
	}
	function update($id)
	{
		DB::update_id('res_product',array('status'=>'NO_USE'),$id);
	}
}
?>