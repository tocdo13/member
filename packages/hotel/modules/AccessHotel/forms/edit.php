<?php
class AccessHotelForm extends Form
{
	function AccessHotelForm()
	{
		Form::Form('AccessHotelForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if(URL::get('deleted_ids'))
		{
			$ids = explode(',',URL::get('deleted_ids'));
			require_once 'packages/hotel/includes/php/hotel.php';
			foreach($ids as $id)
			{
				$this->delete_hotel($id);
			}
		}
        if(isset($_REQUEST['hotel']))
		{
			foreach($_REQUEST['hotel'] as $key=>$record)
			{
			    if(isset($record['is_active']) AND $record['is_active']=='on')
                {
                    $record['is_active'] = 1;
                }
                else
                {
                    $record['is_active'] = 0;
                }
				if($record['id'] and DB::exists_id('hotel',$record['id']))
				{
					$hotel_id  = $record['id'];
					unset($record['id']);
					DB::update('hotel',$record,'id=\''.$hotel_id.'\'');
				}
				else
				{
					unset($record['id']);
					$id = DB::insert('hotel',$record);
				}
			}
		}
        if (isset($ids) and sizeof($ids))
		{
			$_REQUEST['selected_ids'].=','.join(',',$ids);
		}
		Url::redirect_current(array('portal_id'));
	}	
	function draw()
	{
		if(!isset($_REQUEST['hotel']))
		{
			$sql = 'select * from hotel order by id';
			$hotel = DB::fetch_all($sql);
			$_REQUEST['hotel'] = $hotel;
		}
		$this->parse_layout('edit');
	}
	function delete_hotel($hotel_id){
		if($hotel_id and DB::exists('select id from hotel where id = '.$hotel_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('hotel','id=\''.$hotel_id.'\'');	
		}
	}
}
?>