<?php 
class Log extends Module
{
	function Log($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(Url::get('delete_selected') and URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedLogForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteLogForm());
				}
			}
			else
			if(Url::check('delete_all'))
			{
				DB::query('TRUNCATE TABLE log');
				Url::redirect_current();
			}
			else
			if(Url::check('delete_with_parameter'))
			{
				require_once 'packages/hotel/includes/php/time_select.php';
				$year = get_time_parameter('year', date('Y'), $end_year);
				$month = get_time_parameter('month', date('m'), $end_month);
				$day = get_time_parameter('day', date('d'), $end_day);
				$cond = '
						1 >0'
					.(URL::get('user_id')?' and user_id=\''.URL::get('user_id').'\'':'')  
					.(URL::get('module_id')?' and module_id=\''.URL::get('module_id').'\'':'')  
					.' and FROM_UNIXTIME(log.time)>=\''.$year.'-'.$month.'-'.$day.'\'
					and FROM_UNIXTIME(log.time)<=\''.$end_year.'-'.$end_month.'-'.$end_day.'\' '
					.(URL::get('type')?' and log.type = \''.URL::get('type').'\'':'') 
				;
				if($cond!='1 >0')
				{
					DB::delete('log',$cond);
				}
				Url::redirect_current();
			}
			else
			if(
				(((URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'edit')) and User::can_edit(false,ANY_CATEGORY))
					and Url::check('id') and DB::exists_id('log',$_REQUEST['id'])))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteLogForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditLogForm());break;
					
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddLogForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('log',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new LogForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListLogForm());
					}
					break;
				}
			}
			else
			{
				Url::redirect_current();
			}
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>