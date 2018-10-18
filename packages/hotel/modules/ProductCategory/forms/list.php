<?php
class ListProductCategoryForm extends Form
{
	function ListProductCategoryForm()
	{
		Form::Form('ListProductCategoryForm');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			$this->deleted_selected_ids();	
		}
        $language ="";
           if(Portal::language()==2){
    	       $language = "_en";
    	   }  
		//if(Url::get('act')=='edit'){
			$portal_id=PORTAL_ID;	
			$this->get_select_condition($portal_id);
			$categories = DB::fetch_all('
			select 
				'.PRODUCT_CATEGORY.'.id 
				,'.PRODUCT_CATEGORY.'.structure_id
				,'.PRODUCT_CATEGORY.'.name'.$language.' as name
				,'.PRODUCT_CATEGORY.'.code
				--,'.PRODUCT_CATEGORY.'.position				
			from 
			 	'.PRODUCT_CATEGORY.'
			where
				 '.$this->cond.'			
			order by 
				'.PRODUCT_CATEGORY.'.structure_id ');	
			foreach($categories as $k =>$cate){
				if(Url::get('position_'.$cate['id'])){
					DB::update('product_category',array('position'=>Url::get('position_'.$cate['id'])),' id='.$cate['id']);	
				}
			}
		//}
	}
	function draw()
	{
		//echo System::Debug(Module::$current->redirect_parameters); exit();
		$portal_id=PORTAL_ID;	
		$this->get_just_edited_id();
		$this->get_items($portal_id);
		$items=ProductCategoryDB::check_categories($this->items);
        
		$this->parse_layout('list',$this->just_edited_id+
			array(
				'items'=>$items,
			)
		);
	}	
	function get_just_edited_id()
	{
		$this->just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$this->just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$this->just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
	}
	function deleted_selected_ids()
	{
		foreach(URL::get('selected_ids') as $id)
		{
		}
		require_once 'detail.php';
		foreach(URL::get('selected_ids') as $id)
		{
			if($id and $category=DB::fetch('select id,structure_id from '.PRODUCT_CATEGORY.' where id='.intval($id)) and User::can_edit(false,ANY_CATEGORY))
			{
				DB::delete_id(PRODUCT_CATEGORY,$id);
			}	
			if($this->is_error())
			{
				return;
			}
		}
		Url::redirect_current(Module::$current->redirect_parameters);
	}
	function get_items($portal_id)
	{
	    $language ="";
           if(Portal::language()==2){
    	       $language = "_en";
    	   }  
		$this->get_select_condition($portal_id);
		$this->items = DB::fetch_all('
			select 
				'.PRODUCT_CATEGORY.'.id 
				,'.PRODUCT_CATEGORY.'.structure_id
				,'.PRODUCT_CATEGORY.'.name'.$language.' as name
				,'.PRODUCT_CATEGORY.'.code
				--,'.PRODUCT_CATEGORY.'.position				
			from 
			 	'.PRODUCT_CATEGORY.'
			where
				 '.$this->cond.'			
			order by 
				'.PRODUCT_CATEGORY.'.structure_id
		',false);
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);		
	}
	function get_select_condition($portal_id)
	{
		$this->cond = '1>0 ' 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and '.PRODUCT_CATEGORY.'.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
	}
	
}
?>