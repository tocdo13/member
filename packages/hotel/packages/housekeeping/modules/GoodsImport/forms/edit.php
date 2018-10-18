<?php

class EditGoodsImportForm extends Form
{
	function EditGoodsImportForm()
	{
		Form::Form('EditGoodsImportForm');
	}
	function on_submit()
	{		
		if(Url::check('update'))
		{			
			require 'cache/temp.php'; // thay cho phan ben tren
//----------------------So luong can nhap cho moi phong -------------------------
			foreach($rooms_all as $key=>$room)
			{
				if(Url::get('check_'.$key))
				{
					$products = $room['products'];
					foreach($products as $key=>$value){
						if($value['import_quantity']>0)
						{
							$value['quantity'] = $value['import_quantity'];
							unset($value['import_quantity']);
							$value['room_id'] = $room['id'];
							$value['time'] = time();
							if(isset($room['reservation_room_id']))
							{
								$value['reservation_room_id'] = $room['reservation_room_id'];
							}
							unset($value['id']);
							DB::insert('room_product_detail',$value);
						}
					}
				}
			}
			Url::redirect_current();
		}
		else
		{
			Url::redirect_current(array('action'=>'print'));
		}
	}	
	function draw()
	{
		$error_message='';
		if(Url::get('error')==1)
		{
			$error_message='Trong kho housekeeping &#273;&#227; h&#7871;t!';
		}
//-------------------Danh sach hang hoa can nhap------------------------------------------------------------
		$sql='SELECT DISTINCT
					hk_product.id,hk_product.name_'.Portal::language().' AS name, \'\' as import_quantity
				FROM 
					room_product INNER JOIN hk_product ON room_product.product_id=hk_product.code
				ORDER BY
					hk_product.id
			';
		DB::query($sql);
		$room_products_sample=DB::fetch_all();
//---------------------------danh sach phong can nhap va so luong nhap----------------------------------------------------
		$rooms_all = array();
		require_once 'cache/config/default.php';
		//lay tat ca cac phong chua su dung lan nao
		$roomNotUsed = $this->RoomsNotUsed();		
		$rooms_all = $this->getNumsNeedImport($roomNotUsed,1);		
		//Lay cac phong khach vua check out va chua nhap hang			
		$rooms_check_out = $this->RoomJustCheckOut(); //phong check out
		$rooms_check_outs = $this->getNumsNeedImport($rooms_check_out,2); //so luong can nhap
		foreach($rooms_check_outs as $key=>$value)
		{
			$rooms_all[$key] = $value;
		}
		//Phong dang co khach check in va chua nhap hang		
		$rooms_check_in = $this->RoomIsCheckIn();		
		$rooms_check_ins = $this->getNumsNeedImport($rooms_check_in,3);
		
		foreach($rooms_check_ins as $key=>$value)
		{
			$rooms_all[$key] = $value;
		}
//-------------------------------------------------------------------------------
		$layout='edit';
		if(Url::get('action')=='print')
		{
			$layout='print';
		}
		$this->save_file($rooms_all);
//-------------------------------------------------------------------------------
		$this->parse_layout($layout,
			array(
			'room_products_sample'=>$room_products_sample,
			'rooms'=>$rooms_all,
			'error_message'=>$error_message
			)
		);	
	}
	function RoomsNotUsed()
	{
		require_once 'cache/config/default.php';
		if(GET_NUMS_PEOPLE_OF_ROOM == 2)
		{
			$nums_people = 'room_level.NUM_PEOPLE';			
		}else
		{
			$nums_people = '2 as NUM_PEOPLE';
		}
		$allRooms = DB::fetch_all('
				select
					room.*, '.$nums_people.'
				from
					room
					inner join room_level on room.room_level_ID = room_level.id
				order by room.name
			');
		$RoomsUsed	= DB::fetch_all('
				select
					room.*
				from
					room
					inner join room_product_detail on room_product_detail.room_id = room.id
			');
		if($RoomsUsed)
		{
			foreach($allRooms as $key=>$value)
			{
				if(isset($RoomsUsed[$key])) unset($allRooms[$key]);
			}
		}
		return $allRooms;
	}
	function RoomJustCheckOut()
	{
		//phong check out va thoi gian cuoi cung check out
		require_once 'cache/config/default.php';
		if(GET_NUMS_PEOPLE_OF_ROOM == 2)
		{
			$nums_people = 'room_level.NUM_PEOPLE';			
		}else
		{
			$nums_people = '2 as NUM_PEOPLE';
		}
		$sql = '
			select
				room.*, '.$nums_people.',RESERVATION_ROOM.id as RESERVATION_ROOM_ID,RESERVATION_ROOM.time_out
				,from_unixtime(RESERVATION_ROOM.time_out) as in_date
			from
				room
				inner join room_level on room.room_level_ID = room_level.id
				inner join RESERVATION_ROOM on RESERVATION_ROOM.room_id = room.id
			where						
				RESERVATION_ROOM.time_out<='.time().'
				and RESERVATION_ROOM.time_out in (
					select 
						max(RESERVATION_ROOM.time_out) as time_out
					from
						RESERVATION_ROOM
						inner join room on room.id = RESERVATION_ROOM.room_id
					where
						RESERVATION_ROOM.status = \'CHECKOUT\' AND RESERVATION_ROOM.time_out>='.strtotime(date('m/d/Y',time())).' 
					group by
						RESERVATION_ROOM.room_id , room.name)
			order by 
				room.name,RESERVATION_ROOM.time_out desc
		';
		return DB::fetch_all($sql);
	}
	function RoomIsCheckIn()
	{
		$sql = '
			select
				room.*,RESERVATION_ROOM.num_people,RESERVATION_ROOM.id as RESERVATION_ROOM_ID,RESERVATION_ROOM.time_out,
				to_char(FROM_UNIXTIME(room_product_detail.time)) as in_date
			from
				room
				inner join RESERVATION_ROOM on RESERVATION_ROOM.room_id = room.id
				inner join room_product_detail on room_product_detail.room_id = room.id
			where
				RESERVATION_ROOM.status = \'CHECKIN\' AND RESERVATION_ROOM.time_out >= '.time().'
				and to_char(FROM_UNIXTIME(room_product_detail.time))!=\''.Date_time::to_orc_date(date('d/m/y')).'\'
			order by 
				room.name
		';
		return DB::fetch_all($sql);
	}
	function getNumsNeedImport($rooms,$select)
	{
		//----------------------So luong can nhap cho moi phong -------------------------
		// FUNCTION : Loai bot cac phong khong thoa man dieu kien tu danh sach cho truoc
		foreach($rooms as $key=>$room)
		{
			switch($select)
			{
				//phong chua su dung
				case 1:	$sql = '
						select
							room_product_detail.id
						from
							room_product_detail
						where 
							room_product_detail.room_id = '.$room['id'].'
					'; break;
				// phong check out
				case 2:	$sql = '
						select
							room_product_detail.id
						from
							room_product_detail
						where 
							room_product_detail.room_id = '.$room['id'].'
							and room_product_detail.time > '.$room['time_out'].'
							and to_char(FROM_UNIXTIME(room_product_detail.time)) = \''.Date_time::to_orc_date(date('d/m/y')).'\' 
					'; break;
				//phong check in
				case 3:	$sql = '
						select
							room_product_detail.id
						from
							room_product_detail
						where 
							room_product_detail.room_id = '.$room['id'].'
							and to_char(FROM_UNIXTIME(room_product_detail.time)) = \''.Date_time::to_orc_date(date('d/m/y')).'\' 
					';
					 	break;
			}
			if(DB::exists($sql))
			{
				//phong da nhap
				unset($rooms[$key]);
			}else
			{
				if(!isset($room['num_people'])) $room['num_people'] = 1;
				$sql = 'select 
							room_product.id,
							room_product.product_id,
							case when auto=1 then NORM_QUANTITY*'.$room['num_people'].' else NORM_QUANTITY end import_quantity
						from
							room_product
							inner join hk_product on hk_product.code = room_product.product_id
						where
							room_id = '.$room['id'].'
							and hk_product.status != \'NO_USE\'
				';
				$rooms[$key]['products'] = DB::fetch_all($sql);
			}
		}
		return $rooms;
	}
	function save_file($rooms_all)
	{
		$content = '<?php $rooms_all = '.var_export($rooms_all,true).'; ?>';
		$handler = fopen('cache/temp.php','w+');
		fwrite($handler,$content);
		fclose($handler);
	}
}
?>