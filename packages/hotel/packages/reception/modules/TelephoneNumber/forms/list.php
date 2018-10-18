<?php
class ListTelephoneNumberForm extends Form
{
	function ListTelephoneNumberForm()
	{
		Form::Form('ListTelephoneNumberForm');
	}
	function draw()
	{
		$cond = '1 >0';
		$item_per_page = 50;
		$sql = '
			select count(*) as acount
			from 
				telephone_number
			where '.$cond.'
		';
		$count = DB::fetch($sql);
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql = '
		SELECT * FROM
		(
			select 
				telephone_number.id
				,telephone_number.phone_number 
				,room.name as room_id 
				,ROWNUM as rownumber
			from 
			 	telephone_number
				left outer join room on room.id=telephone_number.room_id 
			where '.$cond.'
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by name').'
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
			)
		);
	}
}
?>