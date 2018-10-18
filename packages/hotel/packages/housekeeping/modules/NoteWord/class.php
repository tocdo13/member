<?php 
class NoteWord extends Module
{
	function NoteWord($row)
	{  
	   Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{    
			switch(URL::get('cmd'))
			{
				case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM note WHERE ID = '.Url::iget('id').'')){
					  // echo Url::iget('id');die;
                     $this->delete(Url::iget('id')); 
						Url::redirect_current();
					}
					if(Url::get('item_check_box')){
					 
						$arr = Url::get('item_check_box');
                        
						for($i=0;$i<sizeof($arr);$i++){
							$this->delete($arr[$i]);
						}
						Url::redirect_current();
					}else{
						Url::redirect_current();
					}
				}else{
					Url::access_denied();
				}
				break;
				/*case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditHousekeepingEquipmentForm());break;*/
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddHousekeepingEquipmentForm());break;
                case 'edit':
					require_once 'forms/add.php';
					$this->add_form(new AddHousekeepingEquipmentForm());break;    
				case 'damaged':
					require_once 'forms/damaged.php';
					$this->add_form(new HousekeepingEquipmentDamagedForm());break;
				case 'list_damaged':
					require_once 'forms/list_damaged.php';
					$this->add_form(new ListHousekeepingEquipmentDamagedForm());break;
				default: 
					if(URL::check('id') and DB::exists_id('housekeeping_equipment',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new HousekeepingEquipmentForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListHousekeepingEquipmentForm());
					}
					break;
			}
		}
		else
		{
			Url::redirect_current();
		}
	}
    
    	function delete($id){
		DB::delete('note','ID = '.$id);
	}
	
}
?>