<?php 
class VipCard extends Module
{
	public static $item = array();
	function VipCard($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditVipCardForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and VipCard::$item = DB::select('VIP_CARD','ID = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditVipCardForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM VIP_CARD WHERE ID = '.Url::iget('id').'')){
						DB::delete('VIP_CARD','ID = '.Url::iget('id'));
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current();
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('VIP_CARD','ID = '.$arr[$i]);
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
					$this->add_form(new ListVipCardForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>