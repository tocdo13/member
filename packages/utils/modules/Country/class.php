<?php 
class Country extends Module
{
	function Country($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			switch(URL::get('cmd'))
			{
			case 'delete':
				if(is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0)
				{
					if(sizeof(URL::get('selected_ids'))>1)
					{
						require_once 'forms/list.php';
						$this->add_form(new ListCountryForm());
					}
					else
					{
						$ids = URL::get('selected_ids');
						$_REQUEST['id'] = $ids[0];
						require_once 'forms/detail.php';
						$this->add_form(new CountryForm());
					}
				}
				else
				if(Url::check('id') and DB::exists_id('country',$_REQUEST['id']))
				{
					require_once 'forms/detail.php';
					$this->add_form(new CountryForm());
				}
				else
				{
					Url::redirect_current();
				}
				break;
			case 'edit':
				if(Url::check('id') and DB::exists_id('country',$_REQUEST['id']) and User::can_edit(false,ANY_CATEGORY))
				{
					require_once 'forms/edit.php';
					$this->add_form(new EditCountryForm());
				}
				else
				{
					Url::redirect_current();
				}
				break;
			case 'add':
				require_once 'forms/edit.php';
				$this->add_form(new EditCountryForm());
				break;
			case 'view':
				if(Url::check('id') and DB::exists_id('country',$_REQUEST['id']) and User::can_delete(false,ANY_CATEGORY))
				{
					require_once 'forms/detail.php';
					$this->add_form(new CountryForm());
				}
				else
				{
					Url::redirect_current();
				}
				break;
            case 'update':
                DB::query('Update country set selected_report = 0');
    			foreach(Url::get('selected_report') as $id)
    			{
                    DB::query('Update country set selected_report = 1 where id = '.$id);
    			}
                Url::redirect_current();
/**
 * 				if(Url::check('id') and DB::exists_id('country',$_REQUEST['id']) and User::can_delete(false,ANY_CATEGORY))
 * 				{
 * 					require_once 'forms/detail.php';
 * 					$this->add_form(new CountryForm());
 * 				}
 * 				else
 * 				{
 * 					Url::redirect_current();
 * 				}
 */
				break;
			default: 
				require_once 'forms/list.php';
				$this->add_form(new ListCountryForm());
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