<?php 
class SingleReservation extends Module
{
	public static $item = array();
	function SingleReservation($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditSingleReservationForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and SingleReservation::$item = DB::select('tour','id = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditSingleReservationForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'view':
				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and SingleReservation::$item = DB::select('tour','id = '.Url::iget('id'))){
					require_once 'forms/view.php';
					$this->add_form(new ViewSingleReservationForm());
				}else{
					Url::access_denied();
				}
				break;	
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and $row = DB::fetch('SELECT * FROM tour WHERE id = '.Url::iget('id').'')){
						$this->delete($row['id']);
						Url::redirect_current();
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							$this->delete($arr[$i]);
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
					$this->add_form(new ListSingleReservationForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
}
?>