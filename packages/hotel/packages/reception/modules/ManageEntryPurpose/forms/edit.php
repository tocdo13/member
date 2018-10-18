<?php
class ManageEntryPurposeForm extends Form
{
	function ManageEntryPurposeForm()
	{
		Form::Form('ManageEntryPurposeForm');
		$this->add('entry_purposes.code',new TextType(true,'invalid_code',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if($this->check())
		{
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				require_once 'packages/hotel/includes/php/hotel.php';
				foreach($ids as $id)
				{
					DB::delete('entry_purposes','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['entry_purposes']))
			{
				foreach($_REQUEST['entry_purposes'] as $key=>$record)
				{
					if(isset($record['code']) and $record['code']!='' and $row = DB::fetch('select id from entry_purposes where code=\''.$record['code'].'\''))
					{
						$code = $record['code'];
						unset($record['code']);
						$record['last_update'] = Date_Time::convert_time_to_ora_date();
						DB::update('entry_purposes',$record,'code=\''.$code.'\'');
					}
					else
					{
						$record['create_date'] = Date_Time::convert_time_to_ora_date();
						$record['last_update'] = Date_Time::convert_time_to_ora_date();
						$id = DB::insert('entry_purposes',$record);
					}
				}
				if(isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{
		if(!isset($_REQUEST['entry_purposes']))
		{
			$cond = ' 1>0 ';
			$sql = '
				select
					entry_purposes.*
				from
					entry_purposes
				order by
					entry_purposes.name
				';
			$entry_purposes = DB::fetch_all($sql);
			//System::debug($entry_purposes);
			$_REQUEST['entry_purposes'] = $entry_purposes;
		}		
		$this->parse_layout('edit');
	}
}
?>