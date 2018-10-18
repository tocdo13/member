<?php 
class RoomType extends Module
{
	public static $item = array();
	function RoomType($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
                
				if(User::can_add(false,ANY_CATEGORY)){//
					require_once 'forms/edit.php';
					$this->add_form(new EditRoomTypeForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and RoomType::$item = DB::select('room_type','ID ='.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditRoomTypeForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM room_type WHERE ID = '.Url::iget('id').'')){
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
					$this->add_form(new ListRoomTypeForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
	function delete($id){
		if($items=DB::fetch_all('SELECT ID,room_type_ID,NAME FROM ROOM WHERE room_type_ID='.$id.'')){
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
			DB::update('ROOM',array('room_type_ID'=>0),'room_type_ID='.$id);
		}
		DB::delete('room_type','ID = '.$id);
	}	
}
?>