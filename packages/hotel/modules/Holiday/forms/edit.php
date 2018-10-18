<?php
class HolidayForm extends Form
{
	function HolidayForm()
	{
		Form::Form('HolidayForm');
		$this->add('holiday.name',new TextType(true,'invalid_name',0,255));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if($this->check())
		{			
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete('holiday','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['holiday']))
			{				
				foreach($_REQUEST['holiday'] as $key=>$record)
				{
					$record['in_date'] = Date_Time::to_orc_date($record['in_date']);
					if($record['id'] and DB::exists_id('holiday',$record['id']))
					{
						$holiday_id = $record['id'];
						unset($record['id']);
						DB::update('holiday',$record,'id=\''.$holiday_id.'\'');
					}
					else
					{
						unset($record['id']);
						$id = DB::insert('holiday',$record);
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{
		if(!isset($_REQUEST['holiday']))
		{
			$cond = ' 1=1 ';
			$sql = '
				SELECT
					holiday.*
				FROM
					holiday
				ORDER BY
					holiday.in_date
				';
			$holiday = DB::fetch_all($sql);
			foreach($holiday as $key=>$value){
				$holiday[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			}
			$_REQUEST['holiday'] = $holiday;
		}
		$this->parse_layout('edit');
	}
}
?>