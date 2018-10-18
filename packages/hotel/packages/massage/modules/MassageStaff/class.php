<?php 
class MassageStaff extends Module
{
	public static $item = array();
	function MassageStaff($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/edit.php';
					$this->add_form(new EditMassageStaffForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and MassageStaff::$item = DB::fetch('select massage_staff.*,to_char(massage_staff.birth_date,\'DD/MM/YYYY\') as birth_date from massage_staff where massage_staff.id = '.Url::iget('id').' and portal_id=\''.PORTAL_ID.'\'')){
					require_once 'forms/edit.php';
					$this->add_form(new EditMassageStaffForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
				    $log_action = 'delete';
                    //System::debug(Url::iget('id'));exit();
                    if($sql = DB::fetch_all('SELECT * 
                                             FROM 
                                                  massage_product_consumed
                                                  left join massage_staff_room on massage_staff_room.reservation_room_id = massage_product_consumed.reservation_room_id
                                             WHERE
                                                  massage_staff_room.staff_id in ('.Url::iget('id').')')){
                    
                    //System::debug($sql);exit();                                
                    echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
        				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
        			';
                    echo '<div class="warning-box">';
        			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
        			echo '<div class="content"><h3>'.Portal::language('Nhân viên đã nằm trong một Booking').'</h3>';
        			echo '<ul>';
                    echo '<div class="notice">'.Portal::language('Bạn không thể xóa nhân viên này !!!').'? | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
        			exit();                                         
                    }
					if(Url::get('id') and DB::exists('SELECT id FROM massage_staff WHERE id = '.Url::iget('id').''))
                    {
                        $massage_staff = DB::fetch('SELECT * FROM massage_staff WHERE id = '.Url::iget('id').'');
                        $gender = ($massage_staff['gender']== '1')?'Nữ':'Nam';
                        $log_title = 'Delete Massage Staff: #'.Url::get('id').'';
                        $description.= '<strong>Massage Staff:</strong><br>';
                        $description.= '[Staff id: '.Url::get('id').', Staff name: '.$massage_staff['full_name'].', Gender: '.$gender.', Birth: '.$massage_staff['birth_date'].', Address: '.$massage_staff['address'].']<br>'; 
						DB::delete('massage_staff','ID = '.Url::iget('id'));
						System::log($log_action,$log_title,$description,Url::get('id'));
                        Url::redirect_current();
					}
					if(Url::get('item_check_box'))
                    {
						$arr = Url::get('item_check_box');
                        $parameter='';
                        $log_title = 'Delete Massage Staff:';	
						for($i=0;$i<sizeof($arr);$i++)
                        {
                            $massage_staff = DB::fetch('SELECT * FROM massage_staff WHERE id = '.$arr[$i].'');
                            $gender = ($massage_staff['gender']== '1')?'Nữ':'Nam';
                            $description.= '<strong>Massage Staff:</strong><br>';
                            $description.= '[Staff id: '.Url::get('id').', Staff name: '.$massage_staff['full_name'].', Gender: '.$gender.', Birth: '.$massage_staff['birth_date'].', Address: '.$massage_staff['address'].']<br>'; 
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
							DB::delete('massage_staff','id = '.$arr[$i]);
						}
                        System::log($log_action,$log_title,$description,$parameter);
						Url::redirect_current();
					}
                    else
                    {
						Url::redirect_current();
					}
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListMassageStaffForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>