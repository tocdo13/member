<?php 
class MassageGuest extends Module
{
	public static $item = array();
	function MassageGuest($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/edit.php';
					$this->add_form(new EditMassageGuestForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and MassageGuest::$item = DB::select('massage_guest','id = '.Url::iget('id').' and portal_id=\''.PORTAL_ID.'\'')){
					require_once 'forms/edit.php';
					$this->add_form(new EditMassageGuestForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
				    $log_action = 'delete';
					if(Url::get('id') and DB::exists('SELECT id FROM massage_guest WHERE id = '.Url::iget('id').'')){
						$massage_guest = DB::fetch('SELECT * FROM massage_guest WHERE id = '.Url::iget('id').'');
                        $category = ($massage_guest['category']== 'COMMON')?'Thông thường':'VIP';
                        $log_title = 'Delete Massage guest: #'.Url::get('id').'';
                        $description.= '<strong>Massage Guest:</strong><br>';
                        $description.= '[Guest group: '.$category.', Guest id: '.Url::get('id').', Guest code: '.$massage_guest['code'].', Guest name: '.$massage_guest['full_name'].', Email: '.$massage_guest['email'].', Address: '.$massage_guest['address'].']<br>'; 
                        DB::delete('massage_guest','ID = '.Url::iget('id'));
						System::log($log_action,$log_title,$description,Url::get('id'));
                        Url::redirect_current();
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');
                        $parameter='';
                        $log_title = 'Delete Massage Guest:';	
						for($i=0;$i<sizeof($arr);$i++){
						    $massage_guest = DB::fetch('SELECT * FROM massage_guest WHERE id = '.$arr[$i].'');
                            $category = ($massage_guest['category']== 'COMMON')?'Thông thường':'VIP';
                            $description.= '<strong>Massage Guest:</strong><br>';
                            $description.= '[Guest group: '.$category.', Guest id: '.$arr[$i].', Guest code: '.$massage_guest['code'].', Guest name: '.$massage_guest['full_name'].', Email: '.$massage_guest['email'].', Address: '.$massage_guest['address'].']<br>'; 
                            if($i< (sizeof($arr)-1))
                            {
                                $log_title .= ' #'.$arr[$i].',';
                                $parameter.=$arr[$i].', ';
                            }
                            if($i== ((sizeof($arr)-1)))
                            {
                                $log_title .= ' #'.$arr[$i].'';
                                $parameter.=$arr[$i].'';
                            }
                            if($i< (sizeof($arr) - 1))
                            {
                                $description.= '<hr>';
                            }  
							DB::delete('massage_guest','id = '.$arr[$i]);
						}
                        System::log($log_action,$log_title,$description,$parameter);
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
					$this->add_form(new ListMassageGuestForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>