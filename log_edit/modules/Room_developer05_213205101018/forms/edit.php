<?php
class RoomForm extends Form
{
	function RoomForm()
	{
		Form::Form('RoomForm');
		$this->add('room.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if($this->check())
		{	
		    /** check trung ten **/
            if(isset($_REQUEST['room']))
            {
                foreach($_REQUEST['room'] as $k_room=>$v_room)
                {
                    foreach($_REQUEST['room'] as $k_room_1=>$v_room_1)
                    {
                        if($k_room_1!=$k_room AND $v_room['close_room']==1 AND $v_room_1['close_room']==1 AND $v_room['name']==$v_room_1['name'])
                        {
                            $this->error('coflig_room_name','Ten phong '.$v_room['name'].' trung nhau');
                        }
                    }
                }
            }
            if($this->is_error())
            {
                return;
            }
            /** end  **/
            if(URL::get('deleted_ids') AND User::is_admin())
			{
				//$ids = explode(',',URL::get('deleted_ids'));
				//require_once 'packages/hotel/includes/php/hotel.php';
				//foreach($ids as $id)
				//{
                    //$this->delete_room($id);
				//}
			}
			if(isset($_REQUEST['room']))
			{
			     $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			     //System::debug($_REQUEST['room']); die;
			    /**
                 * Manh: them de luu lai lich su thay doi hang phong
                **/ 
                if($history_id=DB::fetch("SELECT id FROM room_history WHERE in_date='".Date_Time::to_orc_date(date('d/m/Y'))."' AND portal_id='".$portal_id."'","id"))
                {
                    // Truong hop sua lai trong ngay
                    DB::update('room_history',array('last_editer'=>User::id(),'last_edit_time'=>time()),'id='.$history_id);
                    $detail_ids = '';
                    if(isset($_REQUEST['room']))
                    {
                        foreach($_REQUEST['room'] as $key=>$value)
                        {
                            $value_arr = explode("-",$value['id']);
                            $value['id'] = $value_arr[1];
                            if($value['id']!='')
                            {
                                unset($value['stt']);
                                unset($value['minibar_id']);
                                if($de_id = DB::fetch("SELECT id FROM room_history_detail WHERE room_history_id=".$history_id." AND room_id=".$value['id'],"id"))
                                {
                                    $record_detail = $value;
                                    unset($record_detail['id']);
                                    DB::update('room_history_detail',$record_detail,'id='.$de_id);
                                    $detail_ids .= ' AND id!='.$de_id;
                                }
                                else
                                {
                                    $record_detail = $value;
                                    unset($record_detail['id']);
                                    $record_detail['room_history_id'] = $history_id;
                                    $record_detail['room_id'] = $value['id'];
                                    $record_detail['portal_id'] = $portal_id;
                                    $de_id = DB::insert('room_history_detail',$record_detail);
                                    $detail_ids .= ' AND id!='.$de_id;
                                }
                            }
                        }
                    }
                    DB::delete('room_history_detail','room_history_id='.$history_id.$detail_ids);
                }
                else
                {
                    // Truong hop them moi lich su
                    /** dong lai lich su cu neu co **/
                    $history_old_in_date = DB::fetch('SELECT max(in_date) as in_date FROM room_history WHERE in_date<\''.Date_Time::to_orc_date(date('d/m/Y')).'\' AND portal_id=\''.$portal_id.'\'','in_date');
                    DB::update('room_history',array('end_date'=>Date_Time::to_orc_date(date('d/m/Y')),'end_time'=>time()),'in_date=\''.$history_old_in_date.'\'');
                    
                    $history_id = DB::insert('room_history',array(
                                                                'in_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                                                                'creater'=>User::id(),
                                                                'create_time'=>time(),
                                                                'last_editer'=>User::id(),
                                                                'last_edit_time'=>time(),
                                                                'start_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                                                                'start_time'=>time(),
                                                                'portal_id'=>$portal_id
                                                                ));
                    
                    if(isset($_REQUEST['room']))
                    {
                        foreach($_REQUEST['room'] as $key=>$value)
                        {
                            $value_arr = explode("-",$value['id']);
                            $value['id'] = $value_arr[1];
                            if($value['id']!='')
                            {
                                unset($value['stt']);
                                unset($value['minibar_id']);
                                
                                $record_detail = $value;
                                unset($record_detail['id']);
                                $record_detail['room_history_id'] = $history_id;
                                $record_detail['room_id'] = $value['id'];
                                $record_detail['portal_id'] = $portal_id;
                                DB::insert('room_history_detail',$record_detail);
                            }
                        }
                    }
                }
                /**
                 *  END manh
                **/
				foreach($_REQUEST['room'] as $key=>$record)
				{
                    $record_arr = explode("-",$record['id']);
                    $record_arr = $record_arr[1];
                    $record['id'] = $record_arr;
                    unset($record['stt']);
					if($portal_id)
                    {
						$record['portal_id'] = $portal_id;
					}
                    
					if($record['id'] and DB::exists_id('room',$record['id']))
					{
                        if(isset($record['minibar_id']) AND $record['close_room']==1)
                        {
							if($m = DB::fetch('select id from minibar where room_id=\''.$record['id'].'\''))
							{
								DB::update('minibar',array('status'=>'AVAILABLE','id'=>'M-'.$portal_id.'-'.$record['name'],'name'=>'Minibar'.$record['name']),'room_id=\''.$record['id'].'\'');
							}
                            else
                            {
								if(!DB::fetch('select id from minibar where id=\'M-'.$portal_id.'-'.$record['name'].'\''))
                                {
									DB::insert('minibar',array(	'id'=>'M-'.$portal_id.'-'.$record['name'],
																'name'=>'Minibar'.$record['name'],
																'room_id'=>$record['id'],
																'STATUS'=>'AVAILABLE',
																'OLD_ID'=>'0'
															));
								}
                                else
                                {
									DB::update('minibar',array('status'=>'AVAILABLE','room_id'=>$record['id']),'id=\''.'M-'.$portal_id.'-'.$record['name'].'\'');	
								}
							}
							unset($record['minibar_id']);
						}
                        else
                        {
                            unset($record['minibar_id']);
							DB::update('minibar',array('status'=>'NO_USE'),'room_id=\''.$record['id'].'\'');
						}
						$room_id = $record['id'];
						unset($record['id']);
                       
						DB::update('room',$record,'id=\''.$room_id.'\'');
					}
					else
					{
                        if(isset($record['minibar_id']) AND $record['close_room']==1)
						{
							$minibar = true;
						}
                        else
                        {
							$minibar = false;
						}
						unset($record['minibar_id']);
						unset($record['id']);
						$id = DB::insert('room',$record);
                        
                        /** Manh them de ghi lai lich su phong - hang phong **/
                        $record_detail = $record;
                        $record_detail['room_history_id'] = $history_id;
                        $record_detail['room_id'] = $id;
                        $record_detail['portal_id'] = $portal_id;
                        DB::insert('room_history_detail',$record_detail);
                        /** end manh **/
                        
                        /** manh them de khai bao trang thiet bi phong **/
                        if($id and $room_old_id = DB::fetch('select id from room where name=\''.$record['name'].'\' and close_room=0 and portal_id=\''.$portal_id.'\'','id'))
                        {
                            $room_amenities_old = DB::fetch_all('select * from room_amenities where room_id='.$room_old_id);
                            foreach($room_amenities_old as $k_amens=>$v_amens)
                            {
                                unset($v_amens['id']);
                                $v_amens['room_id']=$id;
                                DB::insert('room_amenities',$v_amens);
                            }
                            
                            $housekeeping_equipment_old = DB::fetch_all('select * from housekeeping_equipment where room_id='.$room_old_id);
                            foreach($housekeeping_equipment_old as $k_equip=>$v_equip)
                            {
                                unset($v_equip['id']);
                                $v_equip['room_id']=$id;
                                DB::insert('housekeeping_equipment',$v_equip);
                            }
                        }
                        /** end manh **/
                        
						if($id and $minibar)
                        {
							if($m = DB::fetch('select id from minibar where room_id=\''.$id.'\''))
							{
								DB::update('minibar',array('status'=>'AVAILABLE'),'room_id=\''.$id.'\'');
							}
                            else
                            {
								if(!DB::fetch('select id from minibar where id=\''.'M-'.$portal_id.'-'.$record['name'].'\''))
                                {
									DB::insert('minibar',array(	'id'=>'M-'.$portal_id.'-'.$record['name'],
																'name'=>'Minibar'.$record['name'],
																'room_id'=>$id,
																'STATUS'=>'AVAILABLE',
																'OLD_ID'=>'0'
															));
								}
                                else
                                {
									DB::update('minibar',array('status'=>'AVAILABLE','room_id'=>$id),'id=\''.'M-'.$portal_id.'-'.$record['name'].'\'');	
								}
							}
						}
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
                
                DB::fetch(" update reservation_room
                            set room_level_id = (select room_level_id from room where room.id = room_id)
                            where status in ('CHECKIN','BOOKED') and room_id is not null");
                DB::fetch(" update room_status
                            set room_level_id = (select room_level_id from room where room.id = room_id)
                            where reservation_id in (select reservation_id
                              from reservation_room
                              where reservation_room.status in ('CHECKIN','BOOKED') and room_id is not null)");
			}
			Url::redirect_current(array('portal_id'));
		}
	}	
	function draw()
	{   
		if(!isset($_REQUEST['portal_id']))
        {
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['room']))
		{
			$cond = ' 1>0 ';
			$sql = '
				select
					room.*, 
                    nvl(room_level.position,1000) || \'\' || room.floor || \'\' || room.position || \'-\' || room.id as id,
                    DECODE(minibar.id,\'\',0,1) as minibar_id
				from
					room
                    left join room_level on room_level.id = room.room_level_id
					left outer join
					( 
						select 
							minibar.id, minibar.room_id
						from
							minibar
						where
							minibar.status != \'NO_USE\'
					) minibar on room.id = minibar.room_id
				WHERE
					1 = 1
					'.(Url::get('portal_id')?' AND room.portal_id = \''.Url::get('portal_id').'\'':'').'
				order by
					nvl(room_level.position,1000),room.floor,room.position
				';
			$room = DB::fetch_all($sql);
            $i=1;
            foreach($room as $k=>$v)
            {
                $room[$k]['stt']=$i++;
            }
			$_REQUEST['room'] = $room;
		}
		$db_items = DB::select_all('room_level','portal_id = \''.Url::get('portal_id').'\'','name');
		$room_level_options = '';
		foreach($db_items as $item)
		{
			$room_level_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}		
		$this->map['room_level_options'] = $room_level_options;
		$db_items = DB::select_all('room_type',false,'name');
		$room_type_options = '';
		foreach($db_items as $item)
		{
			$room_type_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}		
		$this->map['room_type_options'] = $room_type_options;
        
        //list area
        $db_items = DB::select_all('area_group','portal_id = \''.PORTAL_ID.'\'','name_'.Portal::language());
		$area_options = '';
		foreach($db_items as $item)
		{
			$area_options .= '<option value="'.$item['id'].'">'.$item['name_'.Portal::language()].'</option>';
		}		
		$this->map['area_options'] = $area_options;
        
        //status room
        $this->map['status_option'] = '<option value="1">SHOW</option><option value="0">HIDE</option>';
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_room($room_id)
    {
		if($room_id and DB::exists('select id from room where id = '.$room_id.'') and User::can_delete(false,ANY_CATEGORY))
        {
			DB::delete('room','id=\''.$room_id.'\'');
			DB::delete('reservation_room','room_id=\''.$room_id.'\'');
			DB::delete('room_status','room_id=\''.$room_id.'\'');
			if($minibar = DB::select('minibar','room_id = '.$room_id.''))
            {
				$id = $minibar['id'];
				DB::delete('minibar','id=\''.$id.'\'');
				DB::delete('minibar_product','minibar_id=\''.$id.'\'');
				if($items = DB::select_all('housekeeping_invoice','minibar_id=\''.$id.'\''))
                {
					foreach($items as $value)
                    {
						DB::delete('housekeeping_invoice_detail','invoice_id='.$value['id'].'');
						DB::delete('housekeeping_invoice','id='.$value['id'].'');
					}
				}
			}
		}
	}
}
?>
