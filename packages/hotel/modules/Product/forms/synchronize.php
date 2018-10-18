<?php
class SynProductForm extends Form
{
	function SynProductForm()
	{
		Form::Form('SynProductForm');
		$this->add('product.id',new TextType(false,'invalid_code',0,20)); 
		$this->add('product.type',new SelectType(false,'invalid_type',array('GOODS'=>'GOODS','DRINK'=>'DRINK','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL'))); 
		$this->add('product.category_id',new IDType(true,'invalid_category_id','product_category')); 
		$this->add('product.unit_id',new IDType(true,'invalid_unit_id','unit')); 

		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('product.name_'.$language['id'],new TextType(true,'invalid_name',0,2000)); 
		}
	}
	function on_submit()
	{
		if($this->check() and !is_array(URL::get('selected_ids')))
		{
			Url::redirect_current(array('type','just_edited_ids'=>URL::get('selected_ids')));
		}
	}	
	function draw()
	{
		$cond = '1>0 '
				.((URL::get('category_id')and URL::get('category_id')!=1)?'	and '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'
				':'');
		$languages = DB::select_all('language');
		$sql = '
			select 
				product.id
				,product.status
				,product.type
				,concat(concat(product.id,\' - \'),product.name_'.Portal::language().') as name
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id
			from 
				product
				left outer join product_category on product.category_id = product_category.id
				left outer join unit on product.unit_id = unit.id
			where
				'.$cond.'
			order by
				product.name_'.Portal::language().' asc				
		';
		$items = DB::fetch_all($sql);
		DB::query('
			select
				id, product_category.name as name, structure_id
			from
				product_category
			order by
				structure_id
		');
		$category_id_list = String::get_list(DB::fetch_all());
		$function_list = array();
		$function_list = array(
			'hk_product'=>HAVE_MINIBAR,
			'res_product'=>HAVE_RESTAURANT,
			'ka_product'=>HAVE_KARAOKE,
			'massage_product'=>HAVE_MASSAGE,
			'tennis_product'=>HAVE_TENNIS,
			'swimming_pool_product'=>HAVE_SWIMMING,
			'wh_product'=>HAVE_WAREHOUSE
		);
		$table = 'hk_product';
		if(Url::get('function') and isset($function_list[Url::get('function')]))
		{
			$table = Url::get('function');
		}	
		foreach($function_list as $key=>$value)
		{
			if(!$value)
			{
				unset($function_list[$key]);
			}
			else
			{
				$function_list[$key] = Portal::language($key);
			}
		}
		$syn_sql = '
			select 
				'.$table.'.code as id
				,'.$table.'.status
				,'.$table.'.type
				,concat(concat('.$table.'.code,\' - \'),'.$table.'.name_'.Portal::language().') as name
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id
			from 
				'.$table.'
				left outer join product_category on '.$table.'.category_id = product_category.id
				left outer join unit on '.$table.'.unit_id = unit.id
			where
				'.$table.'.portal_id=\''.PORTAL_ID.'\'
			order by
				'.$table.'.name_'.Portal::language().' asc
		';		
		$product_syn = DB::fetch_all($syn_sql);
		foreach($product_syn as $key=>$value)
		{
			if(isset($items[$key]))
			{
				unset($items[$key]);
			}
		}
		$this->parse_layout('synchronize',
			array(
			'product_id[]_list'=>String::get_list($items),
			'languages'=>$languages,
			'category_id_list'=>$category_id_list,
			'function_list'=>$function_list,
			'product_syn[]_list'=>String::get_list($product_syn)
			)
		);
	}
}
?>