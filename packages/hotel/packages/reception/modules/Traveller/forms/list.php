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
        if(Url::get('update_to_pa18'))
        {
			if(Url::get('arrival_time'))
            {
				$this->update_to_pa18(Url::get('arrival_time'));
			}
            else
            {
				echo '<div style="font-size:14px;font-weight:bold;">Chua chon Quoc tich hoac ngay check in... </div>';
				echo '
					<script>
						window.setTimeout("location=\''.URL::build_current(array('full_name','nationality','reservation_room_id','arrival_time','','')).'\'",1000);
					</script>';
				exit();
			}
			//Url::redirect_current();
		}
	}
	function draw()
	{
		//$this->fix_country();
		if(!Url::get('arrival_time')){
			$_REQUEST['arrival_time'] = date('d/m/Y');
		}
		$this->map['reservation_id_list'] = array(''=>Portal::language('all'))+String::get_list(ListTravellerDB::get_reservation());
		$rooms = ListTravellerDB::get_reservation_room();
		$this->map['reservation_room_id_list'] = array(''=>Portal::language('all'))+String::get_list($rooms);
		$cond = ' (room_level.is_virtual=0 or room_level.is_virtual is NULL ) 
                 and reservation.portal_id = \''.PORTAL_ID.'\' 
                 and ((reservation_room.status=\'CHECKIN\' and reservation_traveller.status=\'CHECKIN\') 
                    or
                    (reservation_room.status=\'CHECKOUT\' and reservation_room.arrival_time = reservation_room.departure_time and reservation_room.CHANGE_ROOM_TO_RR is null)
                 )'
				.(URL::get('reservation_id')?' AND reservation_room.reservation_id = '.URL::iget('reservation_id').'':'') 
				.(URL::get('nationality_id')?' AND traveller.nationality_id = '.URL::iget('nationality_id').'':'') 
				.((URL::get('nationality')=='vn')?' AND traveller.nationality_id = 99':'')
				.((URL::get('nationality')=='nn')?' AND traveller.nationality_id <> 99':'')
				.(URL::get('reservation_room_id')?' AND reservation_traveller.reservation_room_id = '.URL::iget('reservation_room_id').'':'') 
				.(URL::get('full_name')?' AND (UPPER(traveller.first_name) LIKE \'%'.strtoupper(URL::get('full_name')).'%\' or UPPER(traveller.last_name) LIKE \'%'.strtoupper(URL::get('full_name')).'%\')':'')    
				.(URL::get('passport')?' AND UPPER(traveller.passport) LIKE \'%'.strtoupper(URL::get('passport')).'%\'':'') 
				.' AND ((reservation_traveller.old_arrival_time is null and from_unixtime(reservation_traveller.arrival_time) = \''.(Date_Time::to_orc_date(URL::sget('arrival_time',date('d/m/Y')))).'\' )
                                                or 
                                                (reservation_traveller.old_arrival_time is not null and from_unixtime(reservation_traveller.old_arrival_time) = \''.(Date_Time::to_orc_date(URL::sget('arrival_time',date('d/m/Y')))).'\' )) '
		;
		$item_per_page = 100;
		DB::query('
			select 
				count(distinct traveller.id) as acount
			from 
				traveller
				inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
				inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
				inner join reservation on reservation.id = reservation_room.reservation_id
				inner join room on reservation_room.room_id=room.id
                inner join room_level on room.room_level_id = room_level.id
				left outer join country on country.id=traveller.nationality_id 
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('arrival_time','nationality'));
		$sql = '
			select * from
				(select 
					traveller.id
					,traveller.first_name ,traveller.last_name ,traveller.gender
					,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
					,traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax 
					,traveller.note 
					,traveller.is_vn
                    ,traveller.BIRTH_DATE_CORRECT
					,reservation_room.time_in
					,reservation_room.time_out
					,country.name_'.Portal::language().' as nationality
                    ,country.name_1 as nationality_name
                    ,country.code_1 as code_nationality
					,room.name as room_name
					,DECODE(reservation_room.status,\'CHECKIN\',\'IN\',DECODE(reservation_room.status,\'CHECKOUT\',\'OUT\',DECODE(reservation_room.status,\'BOOKED\',\'B\',\'CANCEL\'))) AS status
					,row_number() over ('.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by room.name ASC').') as rownumber
					,reservation_traveller.entry_date
					,reservation_traveller.port_of_entry
					,reservation_traveller.back_date
                    ,reservation_traveller.OCCUPATION
					,reservation_traveller.entry_target
					,reservation_traveller.go_to_office
					,reservation_traveller.come_from
				from 
					traveller
					inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
					inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room on reservation_room.room_id=room.id
                    inner join room_level on room.room_level_id = room_level.id
					left outer join country on country.id=traveller.nationality_id 
				where '.$cond.'
				)
			where
				rownumber > '.(page_no()-1)*$item_per_page.' and rownumber<='.(page_no()*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
            //System::debug($items);
        foreach($items as $key=>$item){
			if(!$item['entry_date'] and !$item['port_of_entry'] and !$item['back_date'] and !$item['entry_target'] and !$item['go_to_office'] and !$item['come_from']){
				$items[$key]['inputed_pa18_info'] = false;
			}else{
				$items[$key]['inputed_pa18_info'] = true;
			}
			$defintition = array('1'=>'male','0'=>'female');
			if(isset($defintition[$items[$key]['gender']])){
				$items[$key]['gender'] = Portal::language($defintition[$items[$key]['gender']]);
			}else{
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
			$items[$key]['hour_in']=$value['time_in']?date('H:i',$value['time_in']):'';
            $items[$key]['time_out']=$value['time_out']?date('d/m/Y',$value['time_out']):'';
            $items[$key]['hour_out']=$value['time_out']?date('H:i',$value['time_out']):'';
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
			'nn'=>Portal::language('foreign'),
			'vn'=>Portal::language('vietnam'),
			'tq'=>Portal::language('china')
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
        
        if(Url::get('export_file_excel'))
        {
            if(Url::get('arrival_time'))
            {
				$this->export_file_excel(Url::get('arrival_time'));
			}
        }
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
	function update_to_pa18($date,$excel=false)
    {
		//require_once 'cache/portal/'.str_replace('#','',PORTAL_ID).'/PA18/PA18.php';
		require_once 'packages/core/includes/utils/vn_code.php';
		$dir_string = 'resources\PA18\\'.str_replace('#','',PORTAL_ID).'\\';
		$dir = opendir($dir_string);
		while($file = readdir($dir)){
			if(file_exists($dir_string.$file) and is_file($dir_string.$file))
            {
				$arr = explode('.',$file);
				if(isset($arr[1]) and strtoupper($arr[1]) == 'DBF')
                {
					//unlink($dir_string.$file);
				}
			}
		}
		closedir($dir);
		$index = 1;
		$year = substr(date('y'),-1);
        
		$file = $dir_string.PA18_HOTEL_CODE.''.date('md').$year.'.DBF';
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
		  array("VIETNAM",		"N",  1,0),
		  array("MAQTGC",		"C",  15),
		  array("TENQTGC",		"C",  100),		  
		  array("SO_HC",		"C",  15),
		  array("SO_TT",		"C",  15),
		  array("NGHENGHIEP",	"C",  100),
		  array("MAMD",			"N",  2,0),
		  array("TENMD",		"C",  100),
		  array("HAN_XUAT",		"D",  8),
		  array("TTRU_TU",		"D",  8),
		  array("TTRU_DEN",		"D",  8),
		  array("CHECKOUT",		"D",  8),
		  array("MADV",			"N",  3,0),
		  array("TENDV",		"C",  100),
		  array("MAKS",			"C",  3),
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
		  array("CLOSE_DATE",	"D",  8),
		  array("HSLT_UID",		"C",  15),
  		  array("NGAY_NHAP",	"D",  8),
		  array("CUA_N",		"C",  3),
		  array("TENCK",		"C",  100)
		);		
		if (!dbase_create($file, $def)) {
		  echo "Error, can't create the database\n";
		  exit();
		}
		$db = dbase_open($file, 2);
		if($db)
        {
			 $record_numbers = dbase_numrecords($db);
			  for ($i = 1; $i <= $record_numbers; $i++) 
              {
				  dbase_delete_record($db,$i);
			  }
			   // expunge the database
			   dbase_pack($db);
			   $cond = ' 1=1 ';
			  if(Url::get('id_check_box'))
              {
				$cond .= ' and (';
				$traveller_ids = explode(",",Url::get('id_check_box'));
				for($j=0;$j<count($traveller_ids);$j++){
					$cond .=($j==0)?'':' or ';
					$cond .= ' traveller.id = '.$traveller_ids[$j].'';	
				}
				$cond .=')';
			  }
              else
              {
				$cond .= ' and (
                        (reservation_room.status=\'CHECKIN\' and reservation_traveller.status=\'CHECKIN\') 
                            or
                        (reservation_room.status=\'CHECKOUT\' and reservation_room.arrival_time = reservation_room.departure_time and reservation_room.note_change_room is null)
                    ) 
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )  
                    AND (
                            (reservation_traveller.old_arrival_time is null and from_unixtime(reservation_traveller.arrival_time) = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' )
                            or 
                            (reservation_traveller.old_arrival_time is not null and from_unixtime(reservation_traveller.old_arrival_time) = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' )
                    ) 
                    and reservation.portal_id=\''.PORTAL_ID.'\''
					.((URL::get('nationality')=='vn')?' AND traveller.nationality_id = 99':'')
					.((URL::get('nationality')=='nn')?' AND traveller.nationality_id <> 99':'').'
				';
			}
            $sql = '
			SELECT
				traveller.*,
				to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
				to_char(traveller.create_date,\'DD/MM/YYYY\') as create_date,
				to_char(traveller.last_update,\'DD/MM/YYYY\') as last_update,
				room.name as room_name,
				reservation_room.time_in,
				reservation_room.time_out,
				reservation_traveller.entry_target,
				to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as entry_date,
				reservation_traveller.port_of_entry,
				to_char(reservation_traveller.back_date,\'DD/MM/YYYY\') as back_date,
				reservation_traveller.go_to_office,
				reservation_traveller.provisional_residence,
                reservation_traveller.hotel_name,
				reservation_traveller.distrisct,
				reservation_traveller.come_from,
				reservation_traveller.input_staff,
				to_char(reservation_traveller.input_date,\'YYYYMMDD\') as input_date,
				reservation_traveller.ENTRY_FORM_NUMBER,
				reservation_traveller.OCCUPATION,
				reservation_traveller.visa_number,
				to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa,
				reservation_traveller.note,
				country.code_1 as nationality,
				country.name_1 as nationality_name,
				reservation_room.time_in,
				reservation_room.time_out,
				reservation_room.checked_in_user_id,
				reservation_traveller.time_out as traveller_time_out,
				entry_purposes.name as entry_target_name,
				customer.name as customer_name
			FROM
				traveller
				inner join reservation_traveller on reservation_traveller.traveller_id = traveller.id
				inner join reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
				inner join reservation on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
				inner join room_status on room_status.reservation_room_id  =  reservation_room.id
				inner join room on reservation_room.room_id = room.id
                inner join room_level on room.room_level_id = room_level.id
				inner join country on country.id = traveller.nationality_id
				LEFT OUTER JOIN entry_purposes on reservation_traveller.entry_target = entry_purposes.code
			WHERE '.$cond.'
			ORDER BY
				room.name
			';
            
			$items = DB::fetch_all($sql);
            //System::debug($items); exit();
            $str_trave_id = '';
			foreach($items as $key=>$value)
			{
                $str_trave_id .= " or traveller.id = ".$value['id'];
				$value['arrival_date'] = date('d/m/Y',$value['time_in']);
				$value['departure_date'] =  $value['traveller_time_out']?date('d/m/Y',$value['traveller_time_out']):date('d/m/Y',(Date_Time::to_time(date('d/m/Y',$value['time_in']))+24*3600));
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
					'',// S_KEY
					strtoupper(convert_utf8_to_latin($value['first_name'].' '.$value['last_name'])), // HTVIET
					strtoupper(convert_utf8_to_latin($value['first_name'].' '.$value['last_name'])), //HTEN
					'0',//$value['xd_tviet'],
					$value['gender'], //GTINH
					$value['birth_date']?Date_Time::to_dbase_date($value['birth_date']):'', //NGTHNSINH
					$value['birth_date_correct'], //XD_NTNSINH
					$value['nationality'], //MAQT
					convert_utf8_to_latin($value['nationality_name']), //TENQT
					$value['is_vn'], //VIETNAM
					$value['nationality'], //MAQTGC
					convert_utf8_to_latin($value['nationality_name']), //TENQTGC
					$value['passport'], //SO_HC
					$value['visa_number'], //SO_TT
					$value['occupation'], //NGHENGHIEP
					$value['entry_target'],// MAMD
					$value['entry_target_name'], //TENMD
					$value['back_date'], //HAN_XUAT
					Date_Time::to_dbase_date($value['arrival_date']),
					Date_Time::to_dbase_date($value['departure_date']),
					$value['traveller_time_out']?Date_Time::to_dbase_date(date('d/m/Y',$value['traveller_time_out'])):'',
					'',//MADV,
					$value['customer_name'],//TENDV,
					PA18_HOTEL_CODE,
					PA18_HOTEL_NAME,
					$value['room_name'],
					PA18_HOTEL_ADDRESS,
					PA18_DISTRICT_CODE,
					PA18_PROVINCE_CODE,//$value['ma_qh'],
					strtoupper($value['checked_in_user_id']),
					Date_Time::to_dbase_date(date('d/m/Y')),//$value['ngaynhap_pm'],
					$value['note'],//$value['note'],
					'1',//$value['status'],
					Date_Time::to_dbase_date($value['create_date']),//$value['reg_date'],
					Date_Time::to_dbase_date($value['last_update']),//$value['lastupdate'],
					'',//$value['closedate']
					'',// 
					'',//NGAYNHAP
					$value['port_of_entry'],//$value['port_of_entry_code']?$value['port_of_entry_code']:'XXX',					
					convert_utf8_to_latin($gate_name),					
				);
				dbase_add_record($db,$array);
			}
            if($str_trave_id)
            {
                $str_trave_id = substr($str_trave_id,3,strlen($str_trave_id)-3);
                $filetxt = $dir_string.PA18_HOTEL_CODE.''.date('md').$year.'.txt';
                file_put_contents($filetxt, $str_trave_id);
            }
			dbase_close($db);
			header("Location: http://".$_SERVER['HTTP_HOST']."/".Url::$root."/".$file);
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
    function export_file_excel($from_date)
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Họ tên');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Giới tính');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày sinh');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'XĐNS');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Địa chỉ');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Nghề nghiệp');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Số phòng');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Ngày đến');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Ngày đi');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Giờ dến');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Giờ đi');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'VN');
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'CMND/HC');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Loại HC');
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Mã QT');
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'Số VISA/TT');
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Loại giấy tờ');
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Mã MĐ');
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Ngày nhập cảnh');
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Mã CK');
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, 'Hạn TT/Thẻ');
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, 'Nơi cấp TT');
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, 'Cơ quan tiếp đón');
        $cond = ' 1=1 ';
			  if(Url::get('id_check_box'))
              {
				$cond .= ' and (';
				$traveller_ids = explode(",",Url::get('id_check_box'));
				for($j=0;$j<count($traveller_ids);$j++){
					$cond .=($j==0)?'':' or ';
					$cond .= ' traveller.id = '.$traveller_ids[$j].'';	
				}
				$cond .=')';
			  }
              else
              {
				$cond .= ' and (
                        (reservation_room.status=\'CHECKIN\' and reservation_traveller.status=\'CHECKIN\') 
                            or
                        (reservation_room.status=\'CHECKOUT\' and reservation_room.arrival_time = reservation_room.departure_time and reservation_room.note_change_room is null)
                    ) 
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )  
                    AND (
                            (reservation_traveller.old_arrival_time is null and from_unixtime(reservation_traveller.arrival_time) = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' )
                            or 
                            (reservation_traveller.old_arrival_time is not null and from_unixtime(reservation_traveller.old_arrival_time) = \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\' )
                    ) 
                    and reservation.portal_id=\''.PORTAL_ID.'\''
					.((URL::get('nationality')=='vn')?' AND traveller.nationality_id = 99':'')
					.((URL::get('nationality')=='nn')?' AND traveller.nationality_id <> 99':'').'
				';
			}
        $export = DB::fetch_all('
            select 
					 traveller.id
					,traveller.first_name || \' \' || traveller.last_name as full_name
                    ,traveller.gender
					,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
					,traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax 
					,traveller.note 
					,traveller.is_vn
                    ,traveller.birth_date_correct
					,reservation_room.time_in
					,reservation_room.time_out
					,country.name_'.Portal::language().' as nationality
					,room.name as room_name
					,DECODE(reservation_room.status,\'CHECKIN\',\'IN\',DECODE(reservation_room.status,\'CHECKOUT\',\'OUT\',DECODE(reservation_room.status,\'BOOKED\',\'B\',\'CANCEL\'))) AS status
					,row_number() over ('.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by room.name ASC').') as rownumber
					,to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as entry_date
					,to_char(reservation_traveller.back_date,\'DD/MM/YYYY\') as back_date
                    ,to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa
                    ,reservation_traveller.port_of_entry
                    ,reservation_traveller.occupation
					,reservation_traveller.entry_target
					,reservation_traveller.go_to_office
					,reservation_traveller.come_from
                    ,reservation_traveller.visa_number
                    ,country.code_1 as nationality
				    ,case
                    when country.code_1 = \'VNM\'
                    then 2
                    else 0
                    end as code_nationality
                    ,country.name_1 as nationality_name
				from 
					traveller
					inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
					inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room on reservation_room.room_id=room.id
                    inner join room_level on room.room_level_id = room_level.id
					left outer join country on country.id=traveller.nationality_id 
				where '.$cond.' 
        ');
        $i++;
		foreach($export as $key=>$value)
		{
		    $value['gender']=$value['gender']?'M':'F';
            $hour_in=$value['time_in']?date('H:i',$value['time_in']):'';
            $hour_out=$value['time_out']?date('H:i',$value['time_out']):'';
            $value['time_in']=$value['time_in']?date('d/m/Y',$value['time_in']):'';
            $value['time_out']=$value['time_out']?date('d/m/Y',$value['time_out']):'';
		    //gán các truong
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['full_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['gender']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['birth_date']);
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['birth_date_correct']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['address']);
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['occupation']);
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['room_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['time_in']);
    		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['time_out']);
    		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $hour_in);
    		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $hour_out);
    		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['is_vn']);
    		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['passport']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '');
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $value['nationality']);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $value['visa_number']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '');
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $value['entry_target']);
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value['entry_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $value['port_of_entry']);
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $value['expire_date_of_visa']);
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, '');
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $value['go_to_office']);
            $i++;
        }
        //System::debug($export);exit();
        $fileName = "PA18_export_".(str_replace('/','-',$from_date)).".xls";
        //System::debug($objPHPExcel); exit();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName);
		if(file_exists($fileName))
        {
			echo '<script>';
			echo 'window.location.href = \''.$fileName.'\';';
			echo ' </script>';
		}else{
			echo '<script>';
			echo 'alert(" Export dữ liệu không thành công !");';
			echo '</script>';
		}
        //System::debug($export);exit();
    }
}
?>