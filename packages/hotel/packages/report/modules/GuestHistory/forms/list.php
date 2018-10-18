<?php
class ListGuestHistoryForm extends Form
{
	function ListGuestHistoryForm()
	{
		Form::Form('ListGuestHistoryForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/vn_code.php';
		$rooms = ListGuestHistoryDB::get_reservation();
        //kimTan xu ly chon phong theo portal
        //echo Url::get('portal_id');
        $name_portal = " ";
        $room_portal ='1=1';
        if(Url::get('portal_id')=='ALL' or Url::get('portal_id')=='')
        {
            $name_portal='||\'-\'||name_portal';
        }
        if(Url::get('portal_id')!='' and Url::get('portal_id')!='ALL')
        {
            $room_portal="room.portal_id ='".Url::get('portal_id')."'";
        }
        
        $sql='select
                                room.id as id,
                                room.name '.$name_portal.' as name
                                from room
                                left join (select
                                                party.user_id as user_id,
                                                party.name_1 as name_portal
                                                from
                                                party
                                                where type = \'PORTAL\') on room.portal_id = user_id
                                where '.$room_portal.'
                                order by room.portal_id,room.id
                                ';
        $room = DB::fetch_all($sql); 
        //end kimTan xu ly chon phong theo portal                       
        //System::debug($sql);
		$this->map['room_id_list'] = array(''=>Portal::language('all'))+String::get_list($room);
        //System::debug($this->map['room_id_list']);
        //exit();
        //Start Luu Nguyen Giap add portal
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id =PORTAL_ID;
        }
        if($portal_id!="ALL")
        {
            $cond ="  reservation.portal_id ='".$portal_id."' ";
        }
        else
        {
            $cond=" 1=1 ";
        } 
        //echo Url::get('room_id');
        //End Luu Nguyen Giap add portal
		$cond .= ((URL::get('nationality_id')?' and country.code_1 = \''.Url::get('nationality_id').'\'':''))
				.(URL::get('room_id')?' and room.id =  \''.URL::iget('room_id').'\'':'') //KimTan:them tim kiem theo phong
				.(URL::get('traveller_name')?' AND CONCAT(LOWER(FN_CONVERT_TO_VN(traveller.first_name)),CONCAT(\' \',LOWER(FN_CONVERT_TO_VN(traveller.last_name)))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('traveller_name'),'utf-8')).'%\'':'')				
				.(URL::get('customer_name')?' AND LOWER(FN_CONVERT_TO_VN(customer.name)) LIKE \'%'.mb_strtolower(URL::sget('customer_name'),'utf-8').'%\'':'')
				.(URL::get('passport')?' and UPPER(traveller.passport) LIKE \'%'.strtoupper(URL::get('passport')).'%\'':'') 
				.(URL::get('arrival_date')?' and reservation_room.arrival_time >= \''.Date_Time::to_orc_date(URL::sget('arrival_date')).'\'':'')
				.(URL::get('departure_date')?' and reservation_room.departure_time <= \''.Date_Time::to_orc_date(URL::sget('departure_date')).'\'':'')
				.(URL::get('gender')?' and traveller.gender = '.((URL::sget('gender')=='2')?'0':'1').'':'')
                .((URL::get('code')?' and reservation.id = \''.Url::get('code').'\'':''))
                .((URL::get('booking_code')?' and  LOWER(FN_CONVERT_TO_VN(reservation.booking_code)) LIKE \'%'.mb_strtolower(URL::sget('booking_code'),'utf-8').'%\'':''))
                .(URL::get('note')?' AND LOWER(FN_CONVERT_TO_VN(reservation.note)) LIKE \'%'.mb_strtolower(URL::sget('note'),'utf-8').'%\'':'')
                .((URL::get('status')?' and reservation_room.status = \''.Url::get('status').'\'':''))
                
		;
        //echo $cond;
        if(URL::get('birthday'))
        {
            $time = Date_time::to_time(URL::get('birthday'));
            $day = Date('d',$time);
            $month = Date('m',$time);
            $cond .= ' AND EXTRACT(DAY FROM TO_DATE(traveller.birth_date, \'DD-MON-YY\'))='.$day.' 
                       AND EXTRACT(MONTH FROM TO_DATE(traveller.birth_date, \'DD-MON-YY\'))='.$month.'';
        }
        if(Url::get('count_traveller')){
           $cond .= 'and reservation_room.traveller_id in ( 
                                select traveller_id from (
                                select traveller_id,count(id)as tong
                                from reservation_room 
                                group by traveller_id
                                )tmp
                                where tmp.tong>='.Url::get('count_traveller').' and tmp.traveller_id !=0
                            )';
        }
        //echo $cond;
		$item_per_page = 1000;
		DB::query('
			select 
				count(*) as acount
			from 
				traveller
				left outer join country on country.id=traveller.nationality_id 
				left outer join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
				left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
				INNER JOIN reservation on reservation.id = reservation_room.reservation_id
				left outer join room on reservation_room.room_id=room.id
				left outer join customer ON customer.id = reservation.customer_id
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql='
			select * from
				(select 
					traveller.id
					,DECODE(traveller.gender,0,\'Mrs/Miss\',\'Mr\') as gender
					,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name
					,traveller.first_name,traveller.last_name
					,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
					,traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax 
					,traveller.note
					,reservation_traveller.special_request
					,traveller.is_vn
                    ,traveller.email as traveller_email
                    ,traveller.phone as traveller_phone
                     ,reservation_traveller.arrival_time
                     ,reservation_traveller.departure_time
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.price
					,country.name_'.Portal::language().' as nationality
					,room.name as rooms_name
					,DECODE(reservation_room.status,\'CHECKIN\',\'IN\',DECODE(reservation_room.status,\'BOOKED\',\'BOOKED\',DECODE(reservation_room.status,\'CANCEL\',\'CANCEL\',\'OUT\'))) as room_status
					,customer.name as customer_name
					,reservation_room.status
					,row_number() over(order by traveller.first_name) as rownumber
				from 
					traveller
					left outer join country on country.id=traveller.nationality_id 
					left outer join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
					left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					INNER JOIN reservation on reservation.id = reservation_room.reservation_id
					LEFT OUTER JOIN customer on customer.id = reservation.customer_id
					INNER JOIN room on reservation_room.room_id=room.id
				where 
					'.$cond.'
				order by
					reservation_room.time_in DESC
				)
			where
				rownumber > '.(page_no()-1)*$item_per_page.' and rownumber<='.(page_no()*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
        //System::debug($sql);
		 DB::query('

		 		select
					code_1 as id, CONCAT(country.code_1,CONCAT(\' - \',country.name_'.Portal::language().')) as name
				from 
					country
				where 
					1=1
				order by 
					code_1'
		);
		$nationality_id_list =String::get_list(DB::fetch_all()); 
		$i=1;
        foreach($items as $key => $value)
        {
            $reservation_count = DB::fetch_all('
                select
                reservation_traveller.id,
                reservation_traveller.special_request as note ,
                reservation_traveller.traveller_id, 
                reservation_traveller.arrival_time,
                reservation_traveller.departure_time,
                reservation_traveller.to_judge,
                reservation.customer_id,
                reservation.user_id,
                customer.name as customer_name,
                reservation_room.reservation_id,
                room.name,
                DECODE(reservation_room.status,\'CHECKIN\',\'IN\',DECODE(reservation_room.status,\'BOOKED\',\'BOOKED\',DECODE(reservation_room.status,\'CANCEL\',\'CANCEL\',\'OUT\'))) as status,
                reservation_room.price
                from
                reservation_traveller inner join reservation_room 
                on reservation_traveller.reservation_room_id = reservation_room.id
				inner join traveller on traveller.id=reservation_traveller.traveller_id 
                left outer join country on country.id=traveller.nationality_id 
                inner join room on reservation_room.room_id = room.id
                inner join reservation on reservation.id = reservation_room.reservation_id
                LEFT OUTER JOIN customer on customer.id = reservation.customer_id
                where  '.$cond.' and reservation_traveller.traveller_id = '.$value['id']
                .'order by
                reservation_traveller.departure_time DESC');//reservation_room.status<>\'CANCEL\' AND
            $j =1;
            foreach($reservation_count as $k => $v)
            { 
               $items[$key]['vn_time'][$k]['arrival_time'] = $v['arrival_time'];
               $items[$key]['vn_time'][$k]['departure_time']= $v['departure_time'];
               $items[$key]['vn_time'][$k]['room_name'] = $v['name'];
               $items[$key]['vn_time'][$k]['status'] = $v['status'];
               $items[$key]['vn_time'][$k]['price'] = $v['price'];
               $items[$key]['vn_time'][$k]['customer_name'] = $v['customer_name'];
               $items[$key]['vn_time'][$k]['reservation_id'] = $v['reservation_id'];
               $items[$key]['vn_time'][$k]['note'] = $v['note'];
               $items[$key]['vn_time'][$k]['to_judge'] = $v['to_judge'];
               $items[$key]['vn_time'][$k]['user_id'] = $v['user_id'];
               $j += 1;
            }
            $items[$key]['j'] = $j;
        }
		foreach ($items as $key=>$value)
		{
			$items[$key]['arrival_time']=$value['arrival_time']?date('d/m/Y',$value['arrival_time']):'';
            $items[$key]['departure_time']=$value['departure_time']?date('d/m/Y',$value['departure_time']):'';
            $items[$key]['time_in']=$value['time_in']?date('d/m/Y',$value['time_in']):'';
			$items[$key]['time_out']=$value['time_out']?date('d/m/Y',$value['time_out']):'';
			$items[$key]['i']=$i++;
            if(isset($value['vn_time']))
            foreach($value['vn_time'] as $k => $v)
            {
                $items[$key]['vn_time'][$k]['departure_time'] = $v['departure_time']?date('d/m/Y',$v['departure_time']):'';
                $items[$key]['vn_time'][$k]['arrival_time'] = $v['arrival_time']?date('d/m/Y',$v['arrival_time']):'';
            }
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
		$this->map['nationality_id_list'] = array(''=>Portal::language('all'))+ $nationality_id_list;
		$dbf_file = '';
		$dir_string = 'resources\PA18\\';
		$dir = opendir($dir_string);
		while($file = readdir($dir)){
			if(file_exists($dir_string.$file) and is_file($dir_string.$file)){
				$arr = explode('.',$file);
				if(isset($arr[1]) and strtoupper($arr[1]) == 'DBF'){
					$dbf_file = $dir_string.$file;
				}
			}
		}
		$this->map['dbf_file'] = $dbf_file;
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
		$this->map['gender_list'] = array(
			''=>Portal::language('gender'),
			'1'=>Portal::language('Male'),
			'2'=>Portal::language('Female'),
		);
//System::debug($items);
		$this->map += array(
				'items'=>$items,
				'paging'=>$paging,
                'room1_id_list' => array(''=>'')+String::get_list(DB::fetch_all('select * from room where room.portal_id=\''.PORTAL_ID.'\' order by name')),
		);
        $status = array(''=>'ALL','CHECKIN'=>'CHECKIN', 'BOOKED'=>'BOOKED', 'CHECKOUT'=>'CHECKOUT', 'CANCEL'=>'CANCEL');
        $this->map += array('status_list'=>$status);
		$this->parse_layout('list',$just_edited_id+$this->map);
	}
	function fix_country(){
		$sql = '
			SELECT 
				traveller.id,traveller.nationality_id,zone.name_1
			FROM
				traveller
				inner join zone on zone.id = traveller.nationality_id
			WHERE
				traveller.nationality_id is not null
			group by
				traveller.id,traveller.nationality_id,zone.name_1
			order by 
				zone.name_1
		';
		$items = DB::fetch_all($sql);
		foreach($items as $value){
			$item['name_2'] = '...';
			if(!$item = DB::fetch('select id,name_2 from country where upper(name_2) like \'%'.strtoupper($value['name_1']).'%\'')){
				//echo $value['nationality_id'].'-'.$value['name_1'].'-'.$item['name_2'].'<br>';
				DB::update('traveller',array('nationality_id'=>157),'nationality_id=700');
				DB::update('traveller',array('nationality_id'=>158),'nationality_id=701');
				DB::update('traveller',array('nationality_id'=>155),'nationality_id=698');
				DB::update('traveller',array('nationality_id'=>344),'nationality_id=744');
				DB::update('traveller',array('nationality_id'=>99),'nationality_id=1');
				//DB::update('traveller',array('nationality_id'=>$item['id']),'id='.$value['id']);
			}
		}
	}
}
?>
