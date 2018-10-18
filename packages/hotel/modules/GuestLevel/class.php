<?php 
class GuestLevel extends Module
{
	public static $item = array();
	function GuestLevel($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditGuestLevelForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and GuestLevel::$item = DB::select('guest_type','ID ='.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditGuestLevelForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
            
				if(User::can_delete(false,ANY_CATEGORY))
                {
					if(Url::get('id') and DB::exists('SELECT ID FROM guest_type WHERE ID = '.Url::iget('id').''))
                    {
						$this->delete(Url::iget('id'));
						Url::redirect_current();
					}
					if(Url::get('item_check_box'))
                    {
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++)
                        {
							$this->delete($arr[$i]);
						}
						Url::redirect_current();
					}
                    else
                    {
						Url::redirect_current();
					}
				}
                else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListGuestLevelForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
	function delete($id){
	   //system::debug($id);die();
		DB::delete('guest_type','ID = '.$id);
	}	
}
?>