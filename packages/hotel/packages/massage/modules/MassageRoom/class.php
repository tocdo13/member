<?php 
class MassageRoom extends Module
{
	public static $item = array();
	function MassageRoom($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditMassageRoomForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and MassageRoom::$item = DB::select('massage_room','id = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditMassageRoomForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
				    $log_action = 'delete';
					if(Url::get('id') and DB::exists('SELECT ID FROM massage_room WHERE ID = '.Url::iget('id').'')){
						$massage_room = DB::fetch('SELECT * FROM massage_room WHERE id = '.Url::iget('id').'');
                        if($massage_room['area_id']== 1)
                        {
                            $area = 'FU';
                        }
                        if($massage_room['area_id']== 2)
                        {
                            $area = 'FA';
                        }
                        if($massage_room['area_id']== 3)
                        {
                            $area = 'EL';
                        }
                        $log_title = 'Delete Massage Rooom: #'.Url::get('id').'';
                        $description.= '<strong>Massage Room:</strong><br>';
                        $description.= '[Mass Room Level: '.$massage_room['category'].', Mass Room No: '.$massage_room['name'].',  Mass Room Position: '.$massage_room['POSITION'].', Area: '.$area.']<br>'; 
                        $this->delete(Url::iget('id'));
                        System::log($log_action,$log_title,$description,Url::iget('id'));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');
                        $parameter='';
                        $log_title = 'Delete Massage Room:';	
						for($i=0;$i<sizeof($arr);$i++){
						    $massage_room = DB::fetch('SELECT * FROM massage_room WHERE id = '.$arr[$i].'');
                            if($massage_room['area_id']== 1)
                            {
                                $area = 'FU';
                            }
                            if($massage_room['area_id']== 2)
                            {
                                $area = 'FA';
                            }
                            if($massage_room['area_id']== 3)
                            {
                                $area = 'EL';
                            }
                            $description.= '<strong>Massage Room:</strong><br>';
                            $description.= '[Mass Room Level: '.$massage_room['category'].', Mass Room No: '.$massage_room['name'].',  Mass Room Position: '.$massage_room['POSITION'].', Area: '.$area.']<br>'; 
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
							$this->delete($arr[$i]);
						}
                        System::log($log_action,$log_title,$description,$parameter);
					}
					Url::redirect_current();	
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListMassageRoomForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
	function delete($id){
		if($items=DB::fetch_all('SELECT * FROM massage_product_consumed WHERE room_id='.$id.'')){
			echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
			';
			echo '<div class="warning-box">';
			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
			echo '<div class="content"><h3>'.Portal::language('reservations_use_this_room').'</h3>';
			echo '<ul>';
			foreach($items as $key=>$value){
				echo '<li><a target="_blank" href="'.Url::build('massage_daily_summary',array('id'=>$value['reservation_room_id'],'cmd'=>'edit','room_id'=>$value['room_id'])).'">'.Portal::language('view').' '.$value['reservation_room_id'].'</a></li>';
			}
			echo '</ul>';
			echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_all().'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
		}
		DB::delete('massage_room','id = '.$id);
	}	
}
?>