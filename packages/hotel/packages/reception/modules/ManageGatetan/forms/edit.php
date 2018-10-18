<?php
class ManageGateForm extends Form
{
	function ManageGateForm()
	{
		Form::Form('ManageGateForm');
		$this->add('gate.name',new TextType(true,'invalid_name',0,255));
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
					DB::delete('gate','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['gate']))
			{
				foreach($_REQUEST['gate'] as $key=>$record)
				{
					unset($record['id']);
					if($record['code'] and $row = DB::fetch('select id from gate where code=\''.$record['code'].'\''))
					{
						$code = $record['code'];
						unset($record['code']);
						$record['last_update'] = Date_Time::convert_time_to_ora_date();
						DB::update('gate',$record,'code=\''.$code.'\'');
					}
					else
					{
						$record['create_date'] = Date_Time::convert_time_to_ora_date();
						$record['last_update'] = Date_Time::convert_time_to_ora_date();
						$id = DB::insert('gate',$record);
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
		if(!isset($_REQUEST['gate']))
		{
			$cond = ' 1>0 ';
			$sql = '
				select
					gate.*
				from
					gate
				order by
					gate.name
				';
			$gate = DB::fetch_all($sql);
			//System::debug($room);
			$_REQUEST['gate'] = $gate;
		}		
		$this->parse_layout('edit');
	}
}
?>