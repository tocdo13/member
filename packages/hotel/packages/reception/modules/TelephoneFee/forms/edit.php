<?php
class EditTelephoneFeeForm extends Form
{
	function EditTelephoneFeeForm()
	{
		Form::Form('EditTelephoneFeeForm');
		$this->add('telephone_fee.name',new TextType(true,'invalid_name',0,255)); 
		$this->add('telephone_fee.prefix',new TextType(false,'invalid_prefix',0,255)); 
		$this->add('telephone_fee.fee',new FloatType(true,'invalid_fee','0','100000000000'));
		$this->add('telephone_fee.start_fee',new FloatType(true,'invalid_start_fee','0','100000000000'));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(URL::get('selected_ids'))
			{
				if(strpos(URL::get('selected_ids'),','))
				{
					$old_items = DB::select_all('telephone_fee','id in ('.URL::get('selected_ids').')');
				}
				else
				{
					$old_item = DB::select('telephone_fee',URL::get('selected_ids'));
					$old_items = array($old_item['id']=>$old_item);
				}
			}
			else
			{
				$old_items = array();
			}
			if(isset($_REQUEST['mi_telephone_fee']))
			{
				foreach($_REQUEST['mi_telephone_fee'] as $key=>$record)
				{
					$record['start_fee'] = $record['start_fee']?str_replace(',','',$record['start_fee']):0;
					$record['fee'] = $record['fee']?str_replace(',','',$record['fee']):0;					
					if($record['id'] and isset($old_items[$record['id']]))
					{
						DB::update('telephone_fee',$record,'id='.$record['id']);
						if(isset($old_items[$record['id']]))
						{
							$old_items[$record['id']]['not_delete'] = true;
						}
					}
					else
					{
						unset($record['id']);
						$ids[] = DB::insert('telephone_fee',$record); //huan them $ids = 
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
					DB::delete_id('telephone_fee',$item['id']);
				}
			}
			Url::redirect_current();
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
				if(!isset($_REQUEST['mi_telephone_fee']))
				{
					DB::query('
						select 
							*
						from 
							telephone_fee
						where
							'.((sizeof($selected_ids)>1)?'id in ('.join($selected_ids,',').')':'id='.reset($selected_ids)).'
					');
					$mi_telephone_fee = DB::fetch_all();
					foreach($mi_telephone_fee as $key=>$value)
					{
						$mi_telephone_fee[$key]['fee'] = System::display_number($value['fee']); 
						$mi_telephone_fee[$key]['start_fee'] = System::display_number($value['start_fee']); 
					}
					$_REQUEST['mi_telephone_fee'] = $mi_telephone_fee;
					$_REQUEST['selected_ids'] = join($selected_ids,',');
				}
			}
		}
		$this->parse_layout('edit',
			array(
			)
		);
	}
}
?>