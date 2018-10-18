<?php
class EditMinibarForm extends Form
{
	function EditMinibarForm()
	{
		Form::Form('EditMinibarForm');
		//$this->add('minibar.id',new TextType(true,'invalid_code',0,255));
		$this->add('minibar.name',new TextType(true,'invalid_name',0,255));
		$this->add('minibar.room_id',new IDType(true,'invalid_room_id','room'));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		if($this->check())
		{
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					Hotel::delete_minibar($id);
				}
			}
			if(isset($_REQUEST['mi_minibar']))
			{
				foreach($_REQUEST['mi_minibar'] as $key=>$record)
				{
					if($record['id'] and DB::exists_id('minibar',$record['id']))
					{
						DB::update('minibar',$record,'id=\''.$record['id'].'\'');
					}
					else
					{
						$sql = 'SELECT 
									room.id,room.name,count(minibar.id) as minibars 
								FROM 
									room 
									left outer JOIN minibar on minibar.room_id = room.id 
								WHERE 
									room.id = \''.$record['room_id'].'\' and room.portal_id=\''.PORTAL_ID.'\'
								GROUP BY 
									room.id,room.name
							';
						$minibar_room = DB::fetch($sql);
						if($minibar_room['minibars'])
						{
							$record['id'] = 'M'.$minibar_room['name'].'_'.$minibar_room['minibars'];
						}else{
							$record['id'] = 'M'.$minibar_room['name'];
						}
						DB::insert('minibar',$record);
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
	{	
		$paging = '';
		if(!isset($_REQUEST['mi_minibar']))
		{
			$cond = ' 1>0 and room.close_room=1';
			$item_per_page = 1000;
		
			DB::query('
        				select 
                            count(*) as acount
        				from 
        					minibar
        					inner join room on room.id=minibar.room_id 
        				where 
                            '.$cond.'
        			');
			$count = DB::fetch();
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging($count['acount'],$item_per_page);		
			$sql = '
				select * from
				(
					select 
                        minibar.*,
                        ROWNUM as rownumber from
					(
						select 
							minibar.*, 
                            room.name as room_name
						from 
							minibar
							inner join room on room.id=minibar.room_id 
						where 
                            '.$cond.' 
                            and room.portal_id=\''.PORTAL_ID.'\'
						order by 
                            minibar.id
					) minibar
				)
				where
					rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page);
			$mi_minibar = DB::fetch_all($sql);
            foreach($mi_minibar as $key=>$value)
			{
				$sql = 'select
							id
						from
							housekeeping_invoice
						where
							type = \'MINIBAR\'
							and minibar_id = \''.$key.'\'
					';
                //Kiểm tra xem minibar đang đc sử dụng không
				if(DB::fetch($sql))
					$mi_minibar[$key]['no_delete'] = 'true';
                else
					$mi_minibar[$key]['no_delete'] = 'false';
				//them
				$mi_minibar[$key]['change_status'] = ($value['status']=='NO_USE')?'packages/cms/skins/default/images/admin/404/icon.png':'packages/core/skins/default/images/buttons/update_button.png';
				//end
			}			
			$_REQUEST['mi_minibar'] = $mi_minibar;
			//System::debug($mi_minibar);
		}
		
		$sql = 'select
					room.id, 
                    room.name , 
                    minibar.id as minibar 
				from
					room
					left outer join minibar on room.id = minibar.room_id
				where
					room.portal_id=\''.PORTAL_ID.'\'
                    AND room.close_room=1
				order by 
                    room.name';
		$db_items = DB::fetch_all($sql);
		$room_id_options = '';
        //Phòng nào ko có minibar sẽ đc bôi xanh thẻ option
		foreach($db_items as $item)
		{
			$style = '';
			if($item['minibar']=='')
			{	
				$style = 'style="background:#009999"';
			}
			$room_id_options .= '<option '.$style.' value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		$this->parse_layout('edit',
			array(
			'paging'=>$paging,
			'room_id_options' => $room_id_options, 
			)
		);
	}
}
?>