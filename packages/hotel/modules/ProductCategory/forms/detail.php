<?php
class ProductCategoryForm extends Form
{
	function ProductCategoryForm()
	{
		Form::Form("ProductCategoryForm");
		$this->add('id',new IDType(true,'object_not_exists','category'));
		$this->link_css(Portal::template('core').'/css/category.css');
	}
	function on_submit()
	{
		if(Url::get('id') and $category=DB::fetch('select id,structure_id from '.PRODUCT_CATEGORY.' where id='.intval(Url::get('id'))) and User::can_edit(false,ANY_CATEGORY))
		{
			$this->delete($this,$_REQUEST['id']);
			Url::redirect_current(Module::$current->redirect_parameters);
		}
		else
		{
			Url::redirect_current(Module::$current->redirect_parameters);
		}
	}
	function draw()
	{
		$this->load_data();
		$this->parse_layout('detail',$this->item_data);
	}
	function delete(&$form,$id)
	{
		$this->item_data = DB::select(PRODUCT_CATEGORY,$id);
		DB::delete_id(PRODUCT_CATEGORY, $id);
	}
	function load_data()
	{
        echo Url::get('id');
        exit();
		DB::query('
			select 
				'.PRODUCT_CATEGORY.'.id
				,'.PRODUCT_CATEGORY.'.structure_id
				,'.PRODUCT_CATEGORY.'.name
			from 
			 	'.PRODUCT_CATEGORY.'
				

				left outer join `type` on `type`.id='.PRODUCT_CATEGORY.'.type 
			where
				'.PRODUCT_CATEGORY.'.id = "'.URL::sget('id').'"');
		$this->item_data = DB::fetch();
        System::debug($this->item_data);exit();
	}
	function load_multiple_items()
	{
	}
}
?>