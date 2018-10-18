<?php 
class PlandOfMonth extends Module
{
	function PlandOfMonth($row)
	{
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
			if(URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				
				if(sizeof(URL::get('selected_ids'))>1)
				{
					foreach(URL::get('selected_ids') as $id)
        			{
        				DB::delete_id('pland_of_month',$id);
                        DB::delete('plan_of_month_detail','pland_of_month_id='.$id);
        			}
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					DB::delete_id('pland_of_month',$_REQUEST['id']);
                    DB::delete('plan_of_month_detail','pland_of_month_id='.$_REQUEST['id']);
				}
			}
            switch(URL::get('cmd'))
			{
    			case 'edit':
    				require_once 'forms/edit.php';
    				$this->add_form(new EditPlandOfMonthForm());break;
    			case 'add':
    				require_once 'forms/add.php';
    				$this->add_form(new AddPlandOfMonthForm());break;
                default:
    				require_once 'forms/list.php';
    					$this->add_form(new ListPlandOfMonthForm());break; 
			}
		}
        else
        {
			URL::access_denied();
		}
	}	
}
?>