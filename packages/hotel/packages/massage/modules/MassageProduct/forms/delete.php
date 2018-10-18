<?php
class DeleteMassageProductForm extends Form
{
	function DeleteMassageProductForm()
	{
		Form::Form("DeleteMassageProductForm");
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
				'.PRODUCT.'.id
				,'.PRODUCT.'.price 
				,'.PRODUCT.'.direct_export 
				,'.PRODUCT.'.type 
				,'.PRODUCT.'.name_'.Portal::language().' as name 
				,'.PRODUCT_CATEGORY.'.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	'.PRODUCT.'
				left outer join '.PRODUCT_CATEGORY.' on '.PRODUCT_CATEGORY.'.id='.PRODUCT.'.category_id 
				left outer join unit on unit.id='.PRODUCT.'.unit_id 
			where
				'.PRODUCT.'.id = \''.URL::get('id').'\'');
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
		DB::store_temp(PRODUCT,$id,TEMP);
		DB::delete(PRODUCT, 'id=\''.$id.'\'');
	}
	function update($id)
	{
		DB::update_id(PRODUCT,array('status'=>'NO_USE'),$id);
	}
}
?>