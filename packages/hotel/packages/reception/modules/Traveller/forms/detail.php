<?php
class TravellerForm extends Form
{
	function TravellerForm()
	{
		Form::Form('TravellerForm');
		$this->add('id',new IDType(true,'object_not_exists','traveller'));
		$this->add('traveller_comment.time',new DateType(true,'invalid_time')); 
		$this->add('traveller_comment.content',new TextType(true,'invalid_content',0,255000)); 
		$this->add('traveller_comment.hour',new TextType(false,'invalid_hour',0,255)); 
		$this->add('traveller_comment.user_id',new IDType(true,'invalid_user_id','user')); 
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{
			if(isset($_REQUEST['mi_traveller_comment']))
			{
				foreach($_REQUEST['mi_traveller_comment'] as $key=>$record)
				{
					if($record['hour'])
					{
						$hours = explode(':',$record['hour']);
						$hour = $hours[0]*3600+(isset($hours[1])?$hours[1]*60:0)+(isset($hours[2])?$hours[2]:0);
					}
					else
					{
						$hour = 0;
					}
					$record['time']=Date_Time::to_time($record['time'])+$hour;
					unset($record['hour']);
					$record['traveller_id']=URL::get('id');
					if($record['id'] and DB::exists_id('traveller_comment',$record['id']))
					{
						echo $record['id'];
						DB::update('traveller_comment',$record,'id='.$record['id']);
					}
					else
					{
						unset($record['id']);
						DB::insert('traveller_comment',$record);
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					if($id)
					{
						DB::delete_id('traveller_comment',$id);
					}
				}
			}
			Url::redirect_current(array('id'));
		}
	}
	function draw()
	{
		DB::query('
			select 
				traveller.id
				,traveller.first_name ,traveller.last_name ,
				DECODE(traveller.gender,1,\''.Portal::language('male').'\',\''.Portal::language('female').'\') as gender ,
				to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
				traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax ,traveller.note 
				,tour.name as tour_id 

				,country.name_'.Portal::language().' as nationality_id 
			from 
			 	traveller
				left outer join tour on tour.id=traveller.tour_id 
				left outer join country on country.id=traveller.nationality_id 
			where
				traveller.id = '.URL::get('id'));
		$row = DB::fetch();
		
		$paging = '';
		if(!isset($_REQUEST['mi_traveller_comment']))
		{
			$cond = '
		traveller_id = '.URL::get('id').' 
		';
		$item_per_page = 50;
		DB::query('
			select 
				count(*) as acount
			from 
				traveller_comment
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
					traveller_comment.id,
					traveller_comment.content,
					traveller_comment.user_id,
					traveller_comment.time,
					ROWNUM as rownumber
				from 
					traveller_comment
				where 
					'.$cond.'
				order by 
				'.(URL::get('order_by')?URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'time desc').'
			) 
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.$item_per_page.'
		';
		DB::query($sql);
			$mi_traveller_comment = DB::fetch_all();
			foreach($mi_traveller_comment as $key=>$value){
				$mi_traveller_comment[$key]['time'] = date('d/m/Y',$value['time']);
				$mi_traveller_comment[$key]['hour'] = date('H:i:s',$value['time']);
			}
			$_REQUEST['mi_traveller_comment'] = $mi_traveller_comment;
		}
		$row['user_id'] = Session::get('user_id');
        $sql = '
			SELECT
				reservation_traveller.id||folio.id as id,
                reservation_traveller.id as re_traveller_id,
                reservation_traveller.traveller_id,
                reservation_traveller.reservation_room_id,
                reservation_traveller.arrival_time,
                reservation_traveller.departure_time,
                room.name as room_name,
                reservation_traveller.note,
                reservation.id || room.name as room_count,
                reservation_room.reservation_id,
                reservation_room.customer_name as customer_name,
                folio.id as folio,
                folio.total as total_folio
			FROM
				reservation_traveller
				INNER JOIN reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
                INNER JOIN reservation ON reservation_room.reservation_id = reservation.id 
                INNER JOIN room ON reservation_room.room_id = room.id
                left join folio on folio.reservation_id = reservation_traveller.reservation_id
			WHERE
				reservation_traveller.traveller_id = '.Url::iget('id').
            ' ORDER BY
                reservation_room.reservation_id,reservation_room.id
                ';
		$row['related_reservation'] = DB::fetch_all($sql);
        //System::debug($row['related_reservation']);
        
        $count = array();//dem so recode giong nhau va so reservation giong nhau cua tung recode
        foreach($row['related_reservation'] as $key => $value) //duyet
        {
            if(!isset($count[$value['reservation_id']])) 
            {
                $count[$value['reservation_id']] = array('count'=>0);// neu chua co thi khoi tao reservation_id
                $count[$value['reservation_id']][$value['reservation_room_id']] = 0;
            }
            else
            {
                if(!isset($count[$value['reservation_id']][$value['reservation_room_id']]))//neu chua co thi khoi tao reservation_room_id
                {
                    $count[$value['reservation_id']][$value['reservation_room_id']] = 0;
                }
            }
            $count[$value['reservation_id']]['count']++;//co roi thi dem tang len
            $count[$value['reservation_id']][$value['reservation_room_id']]++;
        }
        //System::debug($count);
        //exit();
		$this->parse_layout('detail',$row+
			array(
                'items'=>$row['related_reservation'],
                'count' => $count,
                'paging'=>$paging
			)
		);
	}
}
?>