<?php
class EditRoomGoodsForm extends Form
{
	function EditRoomGoodsForm()
	{
		Form::Form('EditRoomGoodsForm');
		$this->add('room_good.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000')); 
		if(URL::get('cmd')!='add')
		{
			$this->add('room_good.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000')); 
		}
		//$this->add('room_good.price',new FloatType(true,'invalid_price','0','100000000000'));
		$this->add('room_good.product_id',new TextType(false,'invalid_product_id',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/suggest.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/hotel/packages/housekeeping/skins/default/css/autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css(Portal::template('core').'/css/autocomplete.css');
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{			
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('room_good',$id);
				}
			}
			$ids = array();
			if(isset($_REQUEST['mi_room_good']))
			{
				$pos = 1;
				foreach($_REQUEST['mi_room_good'] as $key=>$record)
				{
					if($record['id']=='(auto)')
					{
						$record['id']='';
					}
					if(!$record['position']){
						$record['position'] = $pos++;
					}
					if($record['id'] and DB::exists_id('room_good',$record['id']))
					{
						DB::update('room_good',$record,'id='.$record['id']);
						$ids[] = $record['id'];
					}
					else
					{
						unset($record['id']);
						$record['in_date'] = Date_Time::to_orc_date(date('d/m/Y'));
						if(URL::get('room_id'))
						{
							$record['room_id']=URL::get('room_id');
							if(DB::exists('select id from hk_product where id = \''.$record['product_id'].'\''))
							{
								DB::delete('room_good','room_id = \''.$record['room_id'].'\' and product_id = \''.$record['product_id'].'\'');
								$ids[] = DB::insert('room_good',$record);
							}
						}
						else
						{
							$rooms = DB::select_all('room');
							foreach($rooms as $room)
							{
								$record['room_id'] = $room['id'];
								if(DB::exists('select id from hk_product where id = \''.$record['product_id'].'\''))
								{
									DB::delete('room_good','room_id = \''.$record['room_id'].'\' and product_id = \''.$record['product_id'].'\'');
									DB::insert('room_good',$record);
								}
							}
						}
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$ids[] = $_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			Url::redirect_current(array('room_id','selected_ids'=>join(',',$ids)));
		}
	}	
	function draw()
	{
		if((URL::get('room_id') and $room=DB::select_id('room',URL::get('room_id'))) or URL::get('cmd')=='add')
		{
			$paging = '';
			if(!isset($_REQUEST['mi_room_good']))
			{
				if(URL::get('cmd')!='add')
				{
					$cond = ' 1>0 '.(URL::get('room_id')?' and room_id=\''.URL::get('room_id').'\'':'');
					$item_per_page = 15;
					DB::query('
						select count(*) as acount
						from 
							room_good
							left outer join room on room.id=room_good.room_id
						where '.$cond.'
					');
					$count = DB::fetch();
					require_once 'packages/core/includes/utils/paging.php';
					$paging = paging($count['acount'],$item_per_page);
					
					$sql = '
						SELECT * from
						(
							SELECT temp.*,ROWNUM as rownumber FROM
							(
								SELECT 
									room_good.*									
								FROM 
									room_good
									left outer join room on room.id=room_good.room_id
								WHERE 
									'.$cond.'
								'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
							) temp
						)
						WHERE rownumber > '.((page_no()-1)*$item_per_page).' and rownumber < '.(page_no()*$item_per_page).'
					';
					$mi_room_good = DB::fetch_all($sql);
					foreach($mi_room_good as $key=>$value)
					{
						$mi_room_good[$key]['norm_quantity'] = System::display_number($value['norm_quantity']);
						$mi_room_good[$key]['price'] = System::display_number($value['price']);   
					}
					$_REQUEST['mi_room_good'] = $mi_room_good;
				}
				else
				{
					$_REQUEST['mi_room_good'] = array();
				}
			}
			//$db_items = DB::select_all('minibar',false,'id');
			$db_items = DB::fetch_all('
				select
					room.*
				from
					room
				order by
					floor,room.name
			');
			$room_id_options = '';
			foreach($db_items as $item)
			{
				$room_id_options .= '<option value="'.$item['id'].'">P'.$item['name'].'</option>';
			}
			RoomGoods::get_js_variables_data();			
			$this->parse_layout('edit',
				array(
				'paging'=>$paging,
				'name'=>isset($room)?$room['name']:'',
				'room_id_options' => $room_id_options
				)
			);
		}
		else
		{
			$rooms = DB::fetch_all('
				select
					room.*,
					room.name as room_name
				from
					room
				order by
					floor,room.name
			');			
			$floors = array();
			$last_floor = false;			
			foreach($rooms as $key=>$room)
			{
				if(!$last_floor or $last_floor!=$room['floor'])
				{
					$floors[$room['floor']]=
						array(
							'name'=>$room['floor'],
							'rooms'=>array()
						);
					$last_floor = $room['floor'];
					$i = 1;
				}
				$floors[$last_floor]['rooms'][$i] = $room;
				$i++;
			}
			$this->parse_layout('rooms',
				array(
				'floors'=>$floors
				)
			);
		}
	}
}
?>