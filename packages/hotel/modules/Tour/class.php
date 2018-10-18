<?php 
class Tour extends Module
{
	public static $item = array();
	function Tour($row)
	{
//DB::query('update reservation set tour_id = 0');
		/*DB::query('
			ALTER TABLE TOUR
			ADD(
				IS_VN	NUMBER(1,0),
				ENTRY_DATE	DATE,
				PORT_OF_ENTRY	VARCHAR2(128),
				BACK_DATE	DATE,
				ENTRY_TARGET	VARCHAR2(128),
				GO_TO_OFFICE	VARCHAR2(128),
				COME_FROM	NUMBER(11,0)
			)
		');*/
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditTourForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and Tour::$item = DB::select('tour','id = '.Url::iget('id'))){
					require_once 'forms/edit.php';
					$this->add_form(new EditTourForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'view':
				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and Tour::$item = DB::select('tour','id = '.Url::iget('id'))){
					require_once 'forms/view.php';
					$this->add_form(new ViewTourForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'copy':
				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and Tour::$item = DB::select('tour','id = '.Url::iget('id'))){
					require_once 'forms/copy.php';
					$this->add_form(new CopyTravellersForm());
				}else{
					Url::access_denied();
				}
				break;		
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and $row = DB::fetch('SELECT * FROM tour WHERE id = '.Url::iget('id').' AND portal_id = \''.PORTAL_ID.'\'')){
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
					$this->add_form(new ListTourForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
	function delete($id){
		if(!User::can_admin(false,ANY_CATEGORY)){
			if($reservations=DB::fetch_all('SELECT ID,TOUR_ID FROM reservation WHERE TOUR_ID='.$id.'')){
				echo '<LINK rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">
					<LINK rel="stylesheet" href="packages/hotel/skins/default/css/style.css" type="text/css">
				';
				echo '<div class="warning-box">';
				echo '<div class="title">'.Portal::language('delete_confirm').'</div>';
				echo '<div class="content"><h3>'.Portal::language('reservations_use_this_tour').'</h3>';
				echo '<ul>';
				foreach($reservations as $key=>$value){
					echo '<li><a target="_blank" href="'.Url::build('reservation',array('id'=>$value['id'],'cmd'=>'edit')).'">'.Portal::language('view').'</a></li>';
				}
				echo '</ul>';
				echo '<div class="notice">'.Portal::language('are_you_sure').'? <a href="'.Url::build_current(array('cmd','id','item_check_box')).'&action=continue"><strong>'.Portal::language('sure').'</strong></a> | <a href="'.Url::build_current().'">'.Portal::language('back').'</a></div>';
				echo '</div>';
				echo '</div>';
				if(Url::get('action')!='continue'){
					exit();
				}
				DB::update('reservation',array('tour_id'=>0),'tour_id='.$id);
			}
		}
		System::log('delete','Delete tour '.DB::fetch('SELECT id,name FROM tour WHERE id = '.$id,'name'),'',$id);// Edited in 28/01/2010
		DB::delete('tour','id = '.$id.'  AND portal_id = \''.PORTAL_ID.'\'');
	}	
}
?>