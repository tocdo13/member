<?php 
class StartTermRemain extends Module
{
	public static $item = array();
	function StartTermRemain($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditStartTermRemainForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and StartTermRemain::$item = DB::select('wh_start_term_remain','id ='.Url::iget('id').' and portal_id=\''.PORTAL_ID.'\'')){
					require_once 'forms/edit.php';
					$this->add_form(new EditStartTermRemainForm());
				}else{
					Url::access_denied();
				}
				break;
                  case 'import':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/import.php';
					$this->add_form(new ImportStartTermRemainForm());
				}else{
					Url::access_denied();
				}
				break;  
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT id FROM wh_start_term_remain WHERE id = '.Url::iget('id').'')){
						DB::delete('wh_start_term_remain','id= '.Url::iget('id'));
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('wh_start_term_remain','id = '.$arr[$i]);
						}
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
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
					$this->add_form(new ListStartTermRemainForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>