<?php
class EditUnitForm extends Form
{
	function EditUnitForm()
	{
		Form::Form('EditUnitForm');


		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('unit.name_'.$language['id'],new TextType(true,'invalid_name',0,2000)); 
		}
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
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
			Url::redirect_current(array());
		}
	}	
	function draw()
	{	
		$languages = DB::select_all('language');
		$paging = '';
		if(!isset($_REQUEST['mi_unit']))
		{
			$cond = '
		1>0 '
		;
		$item_per_page = 20;
		DB::query('
			select count(*) as acount
			from 
				unit
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
		SELECT * FROM(
			select 
				unit.*,ROWNUM as rownumber
			from 
				unit
			where '.$cond.'
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by name_1 asc').'
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$mi_unit = DB::fetch_all();
		$_REQUEST['mi_unit'] = $mi_unit;
		}
		$this->parse_layout('edit',
			array(
			'languages'=>$languages,
			'paging'=>$paging,
			)
		);
	}
}
?>