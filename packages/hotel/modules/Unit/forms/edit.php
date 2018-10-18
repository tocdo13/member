<?php
class EditUnitForm extends Form
{
	function EditUnitForm()
	{
		Form::Form('EditUnitForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('unit.name_'.$language['id'],new TextType(true,'invalid_name_'.$language['id'],0,2000)); 
		}
	}
	function on_submit()
	{
		if($this->check())
		{
			if(isset($_REQUEST['mi_unit']))
			{
				foreach($_REQUEST['mi_unit'] as $key=>$record)
				{
					if($record['id']=='(auto)')
					{
						$record['id']=false;
					}
					if($record['id'] and DB::exists_id('unit',$record['id']))
					{
						DB::update('unit',$record,'id='.$record['id']);
					}
					else
					{
						unset($record['id']);
						DB::insert('unit',$record);
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('unit',$id);
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{	            
	   $array = array();
	   $sql = DB::fetch_all('
			select 
				product.id
                ,unit.id as unit_id
				,unit.name_'.Portal::language().' as unit_name
			from 
			 	product 
                left outer join unit on product.unit_id = unit.id
                left outer join product_category on product.category_id = product_category.id
            order by unit.id
		');
		$items = array();
        foreach($sql as $k=>$v)
        {
            if(isset($v['unit_id']))
            {
                $items[$v['unit_id']]['unit_id'] = $v['unit_id'] ;
                $items[$v['unit_id']]['unit_name'] = $v['unit_name'] ;
            }
                
        }
		$languages = DB::select_all('language');
		$paging = '';
		if(!isset($_REQUEST['mi_unit']))
		{
			$cond = '1=1 '
		;
		$item_per_page = 200;
		DB::query('
			select 
				count(*) as acount
			from 
				unit
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
		SELECT 
			* 
		FROM(
			select 
				unit.*,ROWNUM as rownumber
			from 
				unit
			where 
				'.$cond.'
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by name_1 asc').'
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
        ORDER BY id
		');
		$mi_unit = DB::fetch_all();
        foreach($mi_unit as $key=>$value)
        {
            $mi_unit[$key]['can_delete'] = 0 ;
             if(isset($items[$key]))
            {
                $mi_unit[$key]['can_delete'] = 1;
                $array[$key]['id'] = $value['id'];
                $array[$key]['name'] = $value['name_1'];
            }
        }
		$_REQUEST['mi_unit'] = $mi_unit;
		}
        $_REQUEST['check_arr'] = $array ;
		$this->parse_layout('edit',
			array(
			'languages'=>$languages,
			'paging'=>$paging,
			)
		);
	}
}
?>