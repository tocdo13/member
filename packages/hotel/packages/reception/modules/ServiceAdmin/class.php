<?php 
class ServiceAdmin extends Module
{
	public static $item = array();
	function ServiceAdmin($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditServiceAdminForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and ServiceAdmin::$item = DB::select('service','ID = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditServiceAdminForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM service WHERE ID = '.Url::iget('id').'')){
						$this->delete(Url::iget('id'));
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							$this->delete($arr[$i]);
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
					$this->add_form(new ListServiceAdminForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
	function delete($id){
		if($items=DB::fetch_all('SELECT ID,SERVICE_ID FROM RESERVATION_ROOM_SERVICE WHERE SERVICE_ID = '.$id.'')){
			echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
				<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
			';
			echo '<div class="warning-box">';
			echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
			echo '<div class="content"><h3>'.Portal::language('reservation_with_this_service').'</h3>';
			echo '<ul>';
			foreach($items as $key=>$value){
				echo '<li><a target="_blank" href="'.Url::build('reservation',array('id'=>$value['id'],'cmd'=>'invoice')).'&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1">'.Portal::language('view').' #'.$value['id'].'</a> | <a target="_blank" class="delete-link" href="'.Url::build('service',array('id'=>$value['id'],'cmd'=>'delete')).'">'.Portal::language('delete').'</a></li>';
			}
			echo '</ul>';
			echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_all().'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
			DB::delete('RESERVATION_ROOM_SERVICE','service_id='.$id);
		}
		DB::delete('service','ID = '.$id);
	}		
}
?>