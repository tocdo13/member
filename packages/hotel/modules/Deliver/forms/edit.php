<?php
class EditDeliverForm extends Form
{
    
	function EditDeliverForm()
	{
		Form::Form('EditDeliverForm');
        
		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('WH_RECEIVER.name_'.$language['id'],new TextType(true,'invalid_name_'.$language['id'],0,2000)); 
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
					if($record['id'] and DB::exists_id('WH_RECEIVER',$record['id']))
					{
					   $record['portal_id'] = PORTAL_ID;
						DB::update('WH_RECEIVER',$record,'id='.$record['id']);
					}
					else
					{
						unset($record['id']);
                        $record['portal_id'] = PORTAL_ID;
						DB::insert('WH_RECEIVER',$record);
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
					DB::delete_id('WH_RECEIVER',$id);
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{	
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
				WH_RECEIVER
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		//System::debug($count);
        require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
		SELECT 
			* 
		FROM(
			select 
				WH_RECEIVER.*,ROWNUM as rownumber
			from 
				WH_RECEIVER
			where 
				'.$cond.'
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by name asc').'
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$mi_unit = DB::fetch_all();
        //System::debug($mi_deliver);
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