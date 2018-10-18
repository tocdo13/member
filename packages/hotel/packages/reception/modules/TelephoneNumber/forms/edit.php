<?php
class EditTelephoneNumberForm extends Form
{
	function EditTelephoneNumberForm()
	{
		Form::Form('EditTelephoneNumberForm');
		$this->add('telephone_number.phone_number',new TextType(false,'invalid_number',0,255)); 	
		$this->add('telephone_number.room_id',new IDType(true,'invalid_room_id','room'));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(URL::get('selected_ids'))
			{
				if(strpos(URL::get('selected_ids'),','))
				{
					$old_items = DB::select_all('telephone_number','id in ('.URL::get('selected_ids').')');
				}
				else
				{
					$old_item = DB::select('telephone_number',URL::get('selected_ids'));
					$old_items = array($old_item['id']=>$old_item);
				}
			}
			else
			{
				$old_items = array();
			}
			if(isset($_REQUEST['mi_telephone_number']))
			{
				foreach($_REQUEST['mi_telephone_number'] as $key=>$record)
				{
					if($record['id'] and isset($old_items[$record['id']]))
					{
						DB::update('telephone_number',$record,'id='.$record['id']);
						if(isset($old_items[$record['id']]))
						{
							$old_items[$record['id']]['not_delete'] = true;
						}
					}
					else
					{
						unset($record['id']);
						$ids[] = DB::insert('telephone_number',$record); //huan them $ids = 
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			foreach($old_items as $item)
			{
				if(!isset($item['not_delete']))
				{
					DB::delete_id('telephone_number',$item['id']);
				}
			}
				Url::redirect_current(array('selected_ids')+array( 
	));
		}
	}	
	function draw()
	{	
		if(URL::get('selected_ids'))
		{
			if(is_string(URL::get('selected_ids')))
			{
				$selected_ids = explode(',',URL::get('selected_ids'));
			}
			else
			{
				$selected_ids = URL::get('selected_ids');
			}
			if(sizeof($selected_ids)>0)
			{
				if(!isset($_REQUEST['mi_telephone_number']))
				{
					DB::query('
						select 
							*
						from 
							telephone_number
						where
							'.((sizeof($selected_ids)>1)?'id in ('.join($selected_ids,',').')':'id='.reset($selected_ids)).'
					');
					$mi_telephone_number = DB::fetch_all();
					$_REQUEST['mi_telephone_number'] = $mi_telephone_number;
					$_REQUEST['selected_ids'] = join($selected_ids,',');
				}
			}
		}
		$db_items = DB::select_all('room',false,'id');
		$room_id_options = '';
		foreach($db_items as $item)
		{
			$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		} 
		$this->parse_layout('edit',
			array(
			'room_id_options' => $room_id_options, 
			)
		);
	}
}
?>