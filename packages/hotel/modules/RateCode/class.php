<?php 
class RateCode extends Module
{
	
	function RateCode($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
            case 'edit':
                if(User::can_view(false,ANY_CATEGORY))
                {
    				require_once 'forms/edit.php';
    				$this->add_form(new EditRateCodeForm());
                }
                else
    				Url::access_denied();
				break;
			case 'delete':
                if(User::can_view(false,ANY_CATEGORY))
                {
    				$this->delete_rate_code(Url::get('id'));
                    Url::redirect_current();
                }
                else
    				Url::access_denied();
				break;
            case 'delete_group':
                $ids = implode($_REQUEST['item_check_box'],',');
                $this->delete_rate_code($ids);
                Url::redirect_current();
                break;
			default:
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListRateCodeForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
		}
	}
    
    function delete_rate_code($id)
    {
        DB::delete('rate_code','id in ('.$id.')');
        DB::delete('rate_customer_group','rate_code_id in ('.$id.')');
        DB::delete('rate_room_level','rate_code_id in ('.$id.')');
    }	
		
}
?>