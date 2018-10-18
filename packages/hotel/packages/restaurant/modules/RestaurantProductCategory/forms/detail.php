<?php
class RestaurantProductCategoryForm extends Form
{
	function RestaurantProductCategoryForm()
	{
		Form::Form("RestaurantProductCategoryForm");
		$this->add('id',new IDType(true,'object_not_exists','res_product_category'));
		$this->link_css(Portal::template('core').'/css/category.css');
	}
	function on_submit()
	{
		if(Url::get('id') and $category=DB::fetch('select id,structure_id from res_product_category where id='.intval(Url::get('id'))) and User::can_edit(false,$category['structure_id']))
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
		$this->item_data = DB::select('res_product_category',$id);
		if($items=DB::fetch_all('SELECT id,name_'.Portal::language().' as name FROM res_product WHERE category_id='.$id.'')){
			echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
			';
			echo '<div class="warning-box">';
			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
			echo '<div class="content">'.Portal::language('products_in').' <strong>'.$this->item_data['name'].'</strong>';
			echo '<ul>';
			foreach($items as $key=>$value){
				echo '<li><a target="_blank" href="'.Url::build('restaurant_product',array('selected_ids'=>$value['id'],'cmd'=>'edit_selected')).'">'.Portal::language('view').' '.$value['name'].'</a></li>';
			}
			echo '</ul>';
			echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_all().'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
			DB::update('restaurant_product',array('category_id'=>0),'category_id='.$id);
		}
		DB::delete_id('res_product_category', $id);
	}
	function load_data()
	{
		DB::query('
			select 
				res_product_category.id
				,res_product_category.structure_id
				,res_product_category.name
			from 
			 	res_product_category
			where
				res_product_category.id = '.URL::iget('id').'');
		$this->item_data = DB::fetch();
	}
	function load_multiple_items()
	{
	}
}
?>