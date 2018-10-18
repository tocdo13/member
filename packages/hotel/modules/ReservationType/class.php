<?php 
class ReservationType extends Module
{
	public static $item = array();
	function ReservationType($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditReservationTypeForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and ReservationType::$item = DB::select('reservation_type','ID ='.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditReservationTypeForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM reservation_type WHERE ID = '.Url::iget('id').'')){
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
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListReservationTypeForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
	function delete($id){
		if($items=DB::fetch_all('SELECT reservation_room.ID,reservation_room.reservation_type_ID,room.name FROM reservation_room left outer join room on room.id = reservation_room.room_id WHERE reservation_type_ID='.$id.'')){
			echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
			';
			echo '<div class="warning-box">';
			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
			echo '<div class="content"><h3>'.Portal::language('rooms_with_this_type').'</h3>';
			echo '<ul>';
			foreach($items as $key=>$value){
				echo '<li><a target="_blank" href="'.Url::build('room',array('id'=>$value['id'],'cmd'=>'edit')).'">'.Portal::language('view').' '.$value['name'].'</a> | <a target="_blank" class="delete-link" href="'.Url::build('room',array('id'=>$value['id'],'cmd'=>'delete')).'">'.Portal::language('delete').'</a></li>';
			}
			echo '</ul>';
			echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_all().'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
			DB::update('reservation_room',array('reservation_type_ID'=>0),'reservation_type_ID='.$id);
		}
		DB::delete('reservation_type','ID = '.$id);
	}	
}
?>