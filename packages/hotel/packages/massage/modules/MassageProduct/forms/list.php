<?php
class ListMassageProductForm extends Form
{
	function ListMassageProductForm()
	{
		Form::Form('ListMassageProductForm');
	}
	function on_submit()
	{
		Url::redirect();
	}
	function draw()
	{
		$languages = DB::select_all('language');
		$action='';
		if(Url::check('action'))
		{
			$action=Url::get('action');
		}
		if(is_array(URL::get('selected_ids')))
		{
			$_REQUEST['selected_ids'] = implode(',',URL::get('selected_ids'));
		}
		$cond = URL::get('selected_ids')?(strpos(URL::get('selected_ids'),',')?' massage_product.id in (\''.str_replace(',','\',\'',URL::get('selected_ids')).'")':' massage_product.id=\''.URL::get('selected_ids').'\''):
				' 1>0 and portal_id=\''.PORTAL_ID.'\''
				.((URL::get('category_id')and URL::get('category_id')!=1)?'
					and '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'
				':'')  
				.(URL::get('type')?' and lower(massage_product.type)=\''.strtolower(addslashes(URL::get('type'))).'\'':'')  
				.(URL::get('code')?' and lower(massage_product.code) LIKE \'%'.strtolower(addslashes(URL::get('code'))).'%\'':'')
				.(URL::get('name')?' and lower(massage_product.name_'.Portal::language().') LIKE \'%'.strtolower(addslashes(URL::get('name'))).'%\'':'');
		$item_per_page = 100;
		DB::query('
			select 
				count(*) as acount
			from 
				massage_product
				left outer join product_category on product_category.id = massage_product.category_id
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,5,false,'page_no',array('type'));
		$select = '';
		DB::query('
		select * from(
			select 
				massage_product.id
				,massage_product.code
				,massage_product.price				
				,massage_product.status
				,massage_product.type
				'.$select.'
				,massage_product.name_'.Portal::language().' as name 
				,PRODUCT_CATEGORY.name as category_id 
				,unit.name_'.Portal::language().' as unit_id
				,ROWNUM as rownumber
			from 
			 	massage_product
				left outer join PRODUCT_CATEGORY on massage_product.category_id = PRODUCT_CATEGORY.id
				left outer join unit on massage_product.unit_id = unit.id
			where '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by name').'
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			$items[$key]['price'] = System::display_number_report($value['price']);
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'action'=>$action,
				'total'=>$count['acount']
			)
		);
	}
}
?>