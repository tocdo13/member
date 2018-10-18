<?php
class EditRoomLevelForm extends Form
{
	function EditRoomLevelForm()
	{
		Form::Form();
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'name',
				'brief_name',
				'price'=>Url::get('price')?Url::get('price'):'0',
				'num_people',
				'color',
				'is_virtual'=>Url::check('is_virtual')?1:0,
				'portal_id',
                                'position'=>Url::get('position')?Url::get('position'):''
			);
			if(Url::get('cmd')=='edit'){
				$log_action = 'edit';
				$id = Url::iget('id');
				DB::update('room_level',$array,'id='.Url::iget('id'));
			}else{
				$log_action = 'add';
				$id = DB::insert('room_level',$array);
			}
			$description = '';
			if(URl::get('group_deleted_ids')){
				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
				$description .= '<hr>';
				foreach($group_deleted_ids as $delete_id)
				{
					$description .= 'Delete policy id: '.$delete_id.'<br>';
					DB::delete_id('room_level_rate',$delete_id);
				}
			}	
			$room_level_id = $id; 
			if(isset($_REQUEST['mi_policy_group']))
			{	
				$description .= '<hr>';
				foreach($_REQUEST['mi_policy_group'] as $key=>$record)
				{
					$record['rate']=str_replace(',','',$record['rate']);
					$record['policy_from'] = Date_Time::to_orc_date($record['policy_from']);
					$record['policy_to'] = Date_Time::to_orc_date($record['policy_to']);
					$empty = true;
					foreach($record as $record_value)
					{
						if($record_value)
						{
							$empty = false;
						}
					}
					if(!$empty)
					{
						$record['room_level_id'] = $room_level_id;
						$record['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
						if($record['id'])
						{
							$id = $record['id'];
							unset($record['id']);
							$description .= 'Edit [Policy name: '.$record['name'].', Rate: '.$record['rate'].', From: '.$record['policy_from'].', To: '.$record['policy_to'].']<br>';
							DB::update('room_level_rate',$record,'id=\''.$id.'\'');
						}
						else
						{
							if(isset($record['id'])){
								unset($record['id']);
							}
							$description .= 'Add [Policy name: '.$record['name'].', Rate: '.$record['rate'].', From: '.$record['policy_from'].', To: '.$record['policy_to'].']<br>';
							DB::insert('room_level_rate',$record);
						}
					}
				}
			} 
			$log_title = 'Room level: #'.$id.'';
			System::log($log_action,$log_title,$description,$id);// Edited in 07/03/2011
			Url::redirect_current();
		}
	}
	function draw()
	{
		/*DB::query('
			alter table room_type
			add(
				PRICE	NUMBER(11,2),
				NUM_PEOPLE	NUMBER,
				COLOR	VARCHAR2(20),
				IS_VIRTUAL	NUMBER(1,0),
				UPPCHARGE_PERSON	NUMBER(11,2),
				EXTRA_BED_CHARGE	NUMBER(11,2),
				PORTAL_ID	VARCHAR2(20)	
			)
		');*/
		$this->map = array();
		$item = RoomLevel::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[($key)] = $value;
				}
			}
			if(!isset($_REQUEST['mi_policy_group']))
			{
				$sql = '
					SELECT
						room_level_rate.*,
						TO_CHAR(room_level_rate.policy_from,\'DD/MM/YYYY\') as policy_from,
						TO_CHAR(room_level_rate.policy_to,\'DD/MM/YYYY\') as policy_to
					FROM
						room_level_rate
					WHERE
						room_level_rate.room_level_id = \''.$item['id'].'\'
					ORDER BY
						room_level_rate.id
				';
				$mi_policy_group = DB::fetch_all($sql);
				foreach($mi_policy_group as $k=>$v){
					$mi_policy_group[$k]['rate'] = System::display_number_report($v['rate']);
				}
				$_REQUEST['mi_policy_group'] = $mi_policy_group;
			} 
		}
		$this->map['privilege_options'] = '<option value="ADD">'.Portal::language('for_add_privilege').'</option>';
		$this->map['privilege_options'] .= '<option value="ADMIN">'.Portal::language('for_admin_privilege').'</option>';
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_room_level'):Portal::language('edit_room_level');
		$this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());
		$this->parse_layout('edit',$this->map);
	}	
}
?>