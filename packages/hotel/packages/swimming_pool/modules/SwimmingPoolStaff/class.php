<?php 
class SwimmingPoolStaff extends Module
{
	public static $item = array();
	function SwimmingPoolStaff($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/edit.php';
					$this->add_form(new EditSwimmingPoolStaffForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and SwimmingPoolStaff::$item = DB::fetch('select swimming_pool_staff.*,to_char(swimming_pool_staff.birth_date,\'DD/MM/YYYY\') as birth_date from swimming_pool_staff where swimming_pool_staff.id = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditSwimmingPoolStaffForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT id FROM swimming_pool_staff WHERE id = '.Url::iget('id').'')){
						DB::delete('swimming_pool_staff','ID = '.Url::iget('id'));
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current();
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('swimming_pool_staff','id = '.$arr[$i]);
						}
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current();
					}else{
						Url::redirect_current();
					}
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListSwimmingPoolStaffForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>