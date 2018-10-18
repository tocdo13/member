<?php
class ListTravellerForm extends Form
{
	function ListTravellerForm()
	{
		Form::Form('ListTravellerForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit(){
		if(Url::get('update_to_pa18')){
			$this->update_to_pa18();
			Url::redirect_current();
		}
	}
	function draw()
	{
		//$this->fix_country();
		if(!Url::get('date')){
			$_REQUEST['date'] = date('d/m/Y');
		}
		$rooms = ListTravellerDB::get_reservation();
		$this->map['reservation_room_id_list'] = array(''=>Portal::language('all'))+String::get_list($rooms);
		$cond = ' reservation.portal_id = \''.PORTAL_ID.'\' '
				.(URL::get('nationality_id')?' and traveller.nationality_id = '.URL::iget('nationality_id').'':'') 
				.((URL::get('nationality')=='vn')?' and traveller.nationality_id = 99':'')
				.((URL::get('nationality')=='foreign')?' and traveller.nationality_id <> 99':'')
				.(URL::get('reservation_room_id')?' and reservation_traveller.reservation_room_id = '.URL::iget('reservation_room_id').'':'') 
				.(URL::get('first_name')?' and (UPPER(traveller.first_name) LIKE \'%'.strtoupper(URL::get('first_name')).'%\' or UPPER(traveller.last_name) LIKE \'%'.strtoupper(URL::get('first_name')).'%\')':'')    
				.(URL::get('passport')?' and UPPER(traveller.passport) LIKE \'%'.strtoupper(URL::get('passport')).'%\'':'') 
				.(URL::get('date')?' and reservation_room.time_out > '.(Date_Time::to_time(URL::get('date'))+24*3600).'':'')
		;
		if(!Url::check('all_traveller')){
			if(!empty($rooms)){
				$rooms = String::get_list($rooms,'id');
				$str = join(',',$rooms);
				$cond .= ' and reservation_traveller.reservation_room_id in ('.$str.')';
			}else{
				$cond .= ' and 1=2';// truong hop nguoc lai tra ve dieu kien false de khong co ban ghi nao hien thi
			}
		}
		$item_per_page = 100;
		DB::query('
			select 
				count(*) as acount
			from 
				traveller
				inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
				inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
				inner join reservation on reservation.id = reservation_room.reservation_id
				inner join room on reservation_room.room_id=room.id
				left outer join country on country.id=traveller.nationality_id 
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql = '
			select * from
				(select 
					traveller.id
					,traveller.first_name ,traveller.last_name ,traveller.gender 
					,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
					,traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax 
					,traveller.note 
					,traveller.is_vn
					,reservation_room.time_in
					,reservation_room.time_out
					,country.name_'.Portal::language().' as nationality
					,room.name as room_name
					,row_number() over ('.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by room.name ASC').') as rownumber
				from 
					traveller
					inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
					inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room on reservation_room.room_id=room.id
					left outer join country on country.id=traveller.nationality_id 
				where 
					'.$cond.'
				)
			where
				rownumber > '.(page_no()-1)*$item_per_page.' and rownumber<='.(page_no()*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$defintition = array('1'=>'male','0'=>'female');
			if(isset($defintition[$items[$key]['gender']]))
			{
				$items[$key]['gender'] = Portal::language($defintition[$items[$key]['gender']]);
			}
			else
			{
				$items[$key]['gender'] = '';
			}
			$items[$key]['gender']=$item['gender']?Portal::language('male'):Portal::language('female');         
		}
		 DB::query('
		 		select
					id, country.name_'.Portal::language().' as name
				from 
					country
				where 
					1=1
				order by 
					name_2'
		);
		$nationality_id_list =String::get_list(DB::fetch_all()); 
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['time_in']=$value['time_in']?date('d/m/Y',$value['time_in']):'';
			$items[$key]['time_out']=$value['time_out']?date('d/m/Y',$value['time_out']):'';
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
		$this->map['nationality_list'] = array(
			''=>Portal::language('all'),
			'foreign'=>Portal::language('foreign'),
			'vn'=>Portal::language('vietnam')
		);
		$dbf_file = '';
		$dir_string = 'cache\portal\\'.str_replace('#','',PORTAL_ID).'\\';
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
		$this->map += array(
				'items'=>$items,
				'paging'=>$paging,
				'nationality_id_list' => array(''=>Portal::language('all'))+$nationality_id_list,
				'nationality_id' => URL::get('nationality_id')
		);
		$this->map['portals'] = array(''=>Portal::language('select')) + String::get_list(Portal::get_all_portal());
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
	function update_to_pa18($excel=false){
		require_once 'cache/portal/'.str_replace('#','',PORTAL_ID).'/PA18/PA18.php';
		require_once 'packages/core/includes/utils/vn_code.php';
		$dir_string = 'resources\PA18\\'.str_replace('#','',PORTAL_ID).'\\';
		$dir = opendir($dir_string);
		while($file = readdir($dir)){
			if(file_exists($dir_string.$file) and is_file($dir_string.$file)){
				$arr = explode('.',$file);
				if(isset($arr[1]) and strtoupper($arr[1]) == 'DBF'){
					unlink($dir_string.$file);
				}
			}
		}
		closedir($dir);
		$index = 1;
		$file = $dir_string.'INT'.date('md').$index.'.DBF';
		while(file_exists($file))
		{
			$index++;
			$file = $dir_string.'INT'.date('md').$index.'.DBF';
		}
		
		$def = array(
		  array("S_KEY",		"C",  15),
		  array("HTVIET",		"C",  100),
		  array("HTEN",			"C",  100),		  
		  array("XD_TVIET",		"C",  1),
		  array("GTINH",		"C",  1),
		  array("NGTHNSINH",	"D",  8),
		  array("XD_NTNSINH",	"C",  1),
		  array("MAQT",			"C",  3),
		  array("TENQT",		"C",  100),
		  array("MAQTGC",		"C",  3),
		  array("TENQTGC",		"C",  100),
		  array("VIETNAM",		"N",  1,0),
		  array("SO_XNC",		"C",  10),
		  array("SO_HC",		"C",  15),
		  array("LOAI_HC",		"C",  15),
		  array("NGAY_HC",		"D",  8),
		  array("HAN_HC",		"D",  8),
		  array("NOICAP_HC",	"C",  15),
		  array("SO_TT",		"C",  15),
		  array("SERI_TT",		"C",  15),
		  array("LOAI_TT",		"C",  15),
		  array("GIATRI_TT",	"C",  1),
		  array("NGAY_TT",		"D",  8),
		  array("HAN_TT",		"D",  8),
		  array("NOICAP_TT",	"C",  15),
		  array("NGHENGHIEP",	"C",  100),
		  array("MAMD",			"N",  2,0),
		  array("NGAY_NHAP",	"D",  8),
		  array("CUA_N",		"C",  3),
		  array("TENCK",		"C",  100),
		  array("HAN_XUAT",		"D",  8),
		  array("SO_TTRU",		"C",  15),
		  array("TTRU_TU",		"D",  8),
		  array("TTRU_DEN",		"D",  8),
		  array("CHECK_OUT",	"D",  8),
		  array("MADV",			"N",  3,0),
		  array("TENDV",		"C",  100),
		  array("MAKS",			"C",  5),
		  array("TENKS",		"C",  100),
		  array("SO_PHONG",		"C",  10),
		  array("DIA_CHI",		"C",  100),
		  array("MATT",			"C",  3),
		  array("MAQH",			"C",  5),
		  array("NGUOINHAP",	"C",  100),
		  array("NGAY_NHAPM",	"D",  8),
		  array("NOTES",		"C",  254),
		  array("STATUS",		"C",  10),
		  array("REG_DATE",		"D",  8),
		  array("LASTUPDATE",	"D",  8),
		  array("CLOSE_DATE",	"D",  100),
		  array("HSLT_UID",		"C",  15),
		);
		if (!dbase_create($file, $def)) {
		  echo "Error, can't create the database\n";
		  exit();
		}
		echo $file;
		$db = dbase_open($file, 2);
		if($db){
			 $record_numbers = dbase_numrecords($db);
			  for ($i = 1; $i <= $record_numbers; $i++) {
				  dbase_delete_record($db,$i);
			  }
			   // expunge the database
			  dbase_pack($db);
			$items = DB::fetch_all('
			select
				traveller.*,to_char(traveller.birth_date,\'MM/DD/YYYY\') as birth_date,
				to_char(traveller.create_date,\'MM/DD/YYYY\') as create_date,
				to_char(traveller.last_update,\'MM/DD/YYYY\') as last_update,
				room.name as room_name,reservation_room.time_in,reservation_room.time_out,
				reservation_traveller.entry_target,to_char(reservation_traveller.entry_date,\'MM/DD/YYYY\') as entry_date,reservation_traveller.port_of_entry,
				to_char(reservation_traveller.back_date,\'MM/DD/YYYY\') as back_date,reservation_traveller.go_to_office,
				reservation_traveller.provisional_residence,
				reservation_traveller.hotel_name,reservation_traveller.distrisct,reservation_traveller.come_from,
				reservation_traveller.input_staff,to_char(reservation_traveller.input_date,\'YYYYMMDD\') as input_date,
				reservation_traveller.ENTRY_FORM_NUMBER,
				reservation_traveller.OCCUPATION,
				reservation_traveller.visa_number,
				to_char(reservation_traveller.expire_date_of_visa,\'MM/DD/YYYY\') as expire_date_of_visa,
				country.code_1 as nationality,
				country.name_1 as nationality_name,
				reservation_room.time_in,
				reservation_room.time_out
			from
				traveller
				inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id
				inner join reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
				inner join reservation on reservation.id = reservation_room.reservation_id
				inner join room_status on room_status.reservation_room_id  =  reservation_room.id
				inner join room on reservation_room.room_id = room.id
				inner join country on country.id = traveller.nationality_id
			where
				reservation.portal_id = \''.PORTAL_ID.'\'
				and reservation_room.status = \'CHECKIN\'
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
				and reservation_room.time_out > '.(Date_Time::to_time(date('d/m/Y'))+24*3600).'
			order by
				room.name
			');
			foreach($items as $key=>$value)
			{
				$value['arrival_date'] = date('d/m/Y',$value['time_in']);
				$value['departure_date'] = date('d/m/Y',$value['time_out']);
				$value['passport'] = ($value['passport']=='?')?'':$value['passport'];
				$value['come_from'] = ($value['come_from']?DB::fetch('select id,code_2 from country where id = '.$value['come_from'].'','code_2'):'');
				//$value['note'] = '...';
				$value['transfer_date'] = date('Ymd',time());
				$gate_name = '';
				if($value['port_of_entry'])
				{
					$gate_name = DB::fetch('select id,name from gate where code=\''.$value['port_of_entry'].'\'','name');
				}
				$array = array(
					'',
					convert_utf8_to_tcvn3($value['first_name'].' '.$value['last_name']),
					strtoupper(convert_utf8_to_latin($value['first_name'].' '.$value['last_name'])),
					'0',//$value['xd_tviet'],
					$value['gender'],
					$value['birth_date']?Date_Time::to_dbase_date($value['birth_date']):'',
					$value['birth_date_correct'],
					$value['nationality'],
					convert_utf8_to_tcvn3($value['nationality_name']),
					'',
					'',
					$value['is_vn'],
					$value['entry_form_number'],
					$value['passport'],
					'',//$value['passport_type'],//
					'',//$value['passport_date'],//
					'',//$value['passport_expired'],//
					'',//$value['passport_place'],//
					$value['visa_number'],
					'',//$value['visa_serie'],//
					'',//$value['visa_type'],//
					'',//$value['visa_value'],//
					'',//$value['visa_date'],//
					Date_Time::to_dbase_date($value['expire_date_of_visa']),//
					'',//$value['visa_place'],//
					$value['occupation'],
					$value['entry_target'],//$value['mamd'],//
					$value['entry_date'],
					$value['port_of_entry'],//$value['port_of_entry_code']?$value['port_of_entry_code']:'XXX',
					$gate_name,
					$value['back_date'],
					'',//$value['so_ttru'],
					Date_Time::to_dbase_date($value['arrival_date']),
					Date_Time::to_dbase_date($value['departure_date']),
					Date_Time::to_dbase_date(date('d/m/Y')),
					'1',//$value['ma_dv'],
					'',//$value['ten_dv'],
					PA18_HOTEL_CODE,
					PA18_HOTEL_NAME,
					$value['room_name'],
					PA18_HOTEL_ADDRESS,
					PA18_DISTRICT_CODE,
					PA18_PROVINCE_CODE,//$value['ma_qh'],
					PA18_HOTEL_USER,
					date('Ymd'),//$value['ngaynhap_pm'],
					$value['note'],//$value['note'],
					'1',//$value['status'],
					Date_Time::to_dbase_date($value['create_date']),//$value['reg_date'],
					Date_Time::to_dbase_date($value['last_update']),//$value['lastupdate'],
					'',//$value['closedate'],
					PA18_HOTEL_CODE.$key
				);
				dbase_add_record($db,$array);
			}
			dbase_close($db);
		}
		/*if($excel == true){
			$fileName = '32LOSU_NN_201008292037.XLS';
			$content = 'SO_PHONG'."\t".'HTEN'."\t".'GTINH'."\t".'NGTHNSINH'."\t".'VIETNAM'."\t".'QTICH_HNAY'."\t".'SO_HC_TT'."\t";
			$content .= 'MDICH'."\t".'NGAY_NHAP'."\t".'CKHAU_NHAP'."\t".'HAN_XUAT'."\t".'NGAY_DEN'."\t".'NGAY_DI'."\t".'DEN_CQTC'."\t";
			$content .= 'DIA_CHI_TT'."\t".'TEN_KSNK'."\t".'QUAN_HUYEN'."\t".'TU_DAU_DEN'."\t".'CAN_BO_NM'."\t".'NGAY_NMAY'."\t".'NGAYTRUYEN'."\t";		
			$content .= "\n";	
			foreach($items as $key=>$value)
			{
				$content .= $value['room_name']."\t".$value['first_name'].' '.$value['last_name']."\t".$value['gender']."\t".$value['birth_date']."\t".$value['is_vn']."\t".$value['nationality']."\t".$value['passport']."\t";
				$content .=  $value['entry_target']."\t".$value['entry_date']."\t".$value['port_of_entry']."\t".$value['back_date']."\t".$value['arrival_date']."\t".$value['departure_date']."\t".$value['go_to_office']."\t";
				$content .= $value['provisional_residence']."\t".$value['hotel_name']."\t".$value['distrisct']."\t".$value['come_from']."\t".$value['input_staff']."\t".$value['input_date']."\t".date('m/d/Y')."\t";		
				$content .= "\n";		
			}
			//echo $content;
			WriteFileExcel::WriteFile($fileName,$content);
		}*/
	}
}
?>