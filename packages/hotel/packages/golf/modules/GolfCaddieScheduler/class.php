<?php 
class GolfCaddieScheduler extends Module
{
	function GolfCaddieScheduler($row)
	{
		Module::Module($row);
        if(Url::get('status')=='DELETE'){
            DB::delete('golf_caddie_scheduler','id='.Url::get('schduler_id'));
            echo '';
            exit();
        }
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{
			case 'edit':
                require_once 'forms/add.php';
				$this->add_form(new AddGolfCaddieSchedulerForm());break;
			case 'add':
				require_once 'forms/add.php';
				$this->add_form(new AddGolfCaddieSchedulerForm());break;
			default: 
				require_once 'forms/list.php';
				$this->add_form(new ListGolfCaddieSchedulerForm());
				break;
			}
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>