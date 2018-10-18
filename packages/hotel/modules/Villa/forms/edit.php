<?php
class VillaForm extends Form
{
	function VillaForm()
	{
		Form::Form('VillaForm');
		$this->add('villa.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if($this->check())
		{			
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				require_once 'packages/hotel/includes/php/hotel.php';
				foreach($ids as $id)
				{
					$this->delete_villa($id);
				}
			}
			if(isset($_REQUEST['villa']))
			{
				$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
				foreach($_REQUEST['villa'] as $key=>$record)
				{
					if($portal_id){
						$record['portal_id'] = $portal_id;
					}
					if($record['id'] and DB::exists_id('villa',$record['id']))
					{
						$villa_id = $record['id'];
						unset($record['id']);
						DB::update('villa',$record,'id=\''.$villa_id.'\'');
					}
					else
					{
						unset($record['id']);
						$id = DB::insert('villa',$record);
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			Url::redirect_current(array('portal_id'));
		}
	}	
	function draw()
	{
		if(!isset($_REQUEST['portal_id'])){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['villa']))
		{
			$cond = ' 1>0 ';
			$sql = '
				select
					villa.*
				from
					villa
				WHERE
					1 = 1
					'.(Url::get('portal_id')?' AND villa.portal_id = \''.Url::get('portal_id').'\'':'').'
				order by
					villa.floor,villa.position
				';
			$villa  = DB::fetch_all($sql);
			$_REQUEST['villa'] = $villa;
		}
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_villa($villa_id){
		if($villa_id and DB::exists('select id from villa where id = '.$villa_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('villa','id=\''.$villa_id.'\'');
			//DB::delete('reservation','villa_id=\''.$villa_id.'\' and type=\'VILLA\'');
			//DB::delete('reservation_room',' villa_id=\''.$villa_id.'\'');
			//DB::delete('room_status','villa_id=\''.$villa_id.'\'');
		}
	}
}
?>