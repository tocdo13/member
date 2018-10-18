<?php
ob_start();
class ListTravellerForm extends Form
{
	function ListTravellerForm()
	{
		Form::Form('ListTravellerForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');

    }
	function on_submit()
    {
       
	}
	function draw()
	{   if(User::id()=='developer14')
        {
            System::debug($_REQUEST);
        }
        if(!Url::get('arrival_time'))
        {
            $_REQUEST['arrival_time'] = date('d/m/Y');
		}
        $this->map['reservation_id_list'] = array(''=>Portal::language('all')) + String::get_list(ListTravellerDB::get_reservation());
        $rooms = ListTravellerDB::get_reservation_room();
        $this->map['reservation_room_id_list'] = array(''=>Portal::language('all')) + String::get_list($rooms);
        $cond = ' (room_level.is_virtual=0 or room_level.is_virtual is NULL ) 
                 and reservation.portal_id = \''.PORTAL_ID.'\''
				.(URL::get('reservation_id')?' AND reservation_room.reservation_id = '.URL::iget('reservation_id').'':'')
				.(URL::get('reservation_room_id')?' AND reservation_traveller.reservation_room_id = '.URL::iget('reservation_room_id').'':'') 
				.(URL::get('full_name')?' AND (concat(concat(traveller.first_name,\' \'),traveller.last_name) LIKE \'%'.mb_strtoupper((URL::get('full_name')),'UTF-8').'%\')':'')    
				.(URL::get('passport')?' AND UPPER(traveller.passport) LIKE \'%'.strtoupper(URL::get('passport')).'%\'':'') 
				.' AND ((reservation_traveller.old_arrival_time is null and reservation_traveller.arrival_date =\''.Date_Time::to_orc_date(Url::get('arrival_time')).'\')
                or 
                (reservation_traveller.old_arrival_time is not null and reservation_traveller.old_arrival_time>='.Date_Time::to_time(Url::get('arrival_time')).' and reservation_traveller.old_arrival_time<='.(Date_Time::to_time(Url::get('arrival_time'))+86399).'))'
		;
        if(Url::get('nationality'))
        {
            if(Url::get('nationality') != 'ALL')
            {
                $country = '439';
                if(Url::get('nationality') == $country)
                {
                    $cond .= ' AND traveller.nationality_id = '.$country.'';
                }else
                {
                    $cond .= ' AND traveller.nationality_id <> '.$country.'';
                }
            }
        }
        $item_per_page = 100;
		DB::query('
    			SELECT 
    				count(distinct traveller.id) as acount
    			FROM 
    				traveller
    				INNER JOIN reservation_traveller ON traveller.id=reservation_traveller.traveller_id 
    				INNER JOIN reservation_room ON reservation_room.id=reservation_traveller.reservation_room_id
    				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
    				INNER JOIN room ON reservation_room.room_id=room.id
                    INNER JOIN room_level ON room.room_level_id = room_level.id
    				LEFT OUTER JOIN country ON country.id=traveller.nationality_id 
                WHERE 
                    '.$cond.'
		');
        $count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('arrival_time','nationality'));
        $sql = '
			SELECT * FROM
				(SELECT 
					traveller.id
					,traveller.first_name 
                    ,traveller.last_name
                    ,traveller.gender
					,country.name_1 as name_nationality_vn
                    ,country.name_2 as name_nationality_en
                    ,country.code_'.Portal::language().' as code_nationality
					,traveller.is_vn
					,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
                    ,traveller.birth_date_correct
                    ,certification_type.name as certification_name
					,traveller.passport 
                    ,traveller.visa as visa_name
                    ,reservation_traveller.visa_number
                    ,religion.name as religion_name
					,room.name as room_name
                    ,reservation_traveller.arrival_time as time_in
                    ,reservation_traveller.departure_time as time_out
                    ,reservation_traveller.arrival_time as time_in1
                    ,reservation_traveller.departure_time as time_out1
                    ,entry_purposes.name as entry_target
                    ,reservation_traveller.pa18
                    ,reservation_traveller.pa18 as check_pa18
                    ,ethnic.name as ethnic_name
                    ,province.name as province_name
                    ,to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa
                    ,to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as entry_date
					,to_char(reservation_traveller.back_date, \'DD/MM/YYYY\') as back_date
                    ,gate.name as port_of_entry
                    ,row_number() over ('.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by room.name ASC').') as rownumber
                    ,reservation_traveller.id as r_traveller_id
                    ,reservation_traveller.OCCUPATION
					,reservation_traveller.go_to_office
					,reservation_traveller.come_from
				FROM 
					traveller
					inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id 
					inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room on reservation_room.room_id=room.id
                    inner join room_level on room.room_level_id = room_level.id
					left join country on country.id=traveller.nationality_id
                    left join certification_type on  certification_type.id = traveller.certification_id
                    left join ethnic on ethnic.id=traveller.ethnic_id
                    left join gate on gate.code=reservation_traveller.port_of_entry 
                    left join entry_purposes on entry_purposes.code=reservation_traveller.entry_target
                    left join province on province.id=traveller.province_id
                    left join religion on religion.id = traveller.religion_id
				WHERE 
                    '.$cond.'
                    and reservation_room.status = \'CHECKIN\'
				)
			WHERE
				rownumber > '.(page_no()-1)*$item_per_page.' and rownumber<='.(page_no()*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $path='E:\\wamp\\www\\resources\\PA18\\default\\Hotel'.date('m').''.date('d').''.substr(date('Y'),3,1).'.txt';
        $conten='';
        if(@fopen($path,'r'))
        {
            $conten=file_get_contents($path);
        }else
        {
            $conten='1=1';  
        }
        $traveller_save=DB::fetch_all('SELECT traveller.id FROM traveller WHERE '.$conten.''); 
        //System::debug($traveller_save);
        $i=1;
        $j=1;
        foreach($items as $key => $value)
        {
            $items[$key]['reason']='Khác';
            if($items[$key]['code_nationality'] == 'VNM')
            {
                if($items[$key]['certification_name']=='')
                {
                    $items[$key]['certification_name']='CMND';
                }
                if($items[$key]['province_name'] == '')
                {
                    $items[$key]['province_name'] ='Hà Nội';                    
                } 
                $defintition = array('1'=>'male','0'=>'female');
                if(isset($defintition[$items[$key]['gender']]))
                {
                    $items[$key]['gender'] = Portal::language($defintition[$items[$key]['gender']]);
                }else
                {
                    $items[$key]['gender'] = '';
                }
                $items[$key]['gender']=$value['gender']?Portal::language('male'):Portal::language('female');                   
            }else
            {
                if($items[$key]['certification_name']=='')
                {
                    $items[$key]['certification_name']='Hộ Chiếu';                    
                }
                if($items[$key]['province_name'] == '')
                {
                    $items[$key]['province_name'] ='Nước Ngoài';                    
                } 
                if($items[$key]['entry_date']=='')
                {
                    $items[$key]['entry_date']=date('d/m/Y') ;
                }
                if($items[$key]['back_date']=='')
                {
                    $today=date('d-m-Y');
                    $date=strtotime(date("Y-m-d", strtotime($today)) . " +2 month");
                    $date= strftime("%d/%m/%Y", $date);
                    $items[$key]['back_date']=$date; 
                }
                $defintition = array('1'=>'m','0'=>'f');
                if(isset($defintition[$items[$key]['gender']]))
                {
                    $items[$key]['gender'] = Portal::language($defintition[$items[$key]['gender']]);
                }else
                {
                    $items[$key]['gender'] = '';
                }
                $items[$key]['gender']=$value['gender']?Portal::language('m'):Portal::language('f');
                if($items[$key]['birth_date_correct'] == '')
                {
                    $items[$key]['birth_date_correct'] = 'D';                    
                }
            }
            if($items[$key]['is_vn'] == 2)
            {
                $items[$key]['overseas_vietnamese']= 'Không';
            }else
            {
                $items[$key]['overseas_vietnamese']= 'Có';
            }
            if($items[$key]['religion_name'] == '')
            {
                $items[$key]['religion_name'] = 'Không';
            }
            if($items[$key]['ethnic_name']=='')
            {
                $items[$key]['ethnic_name']='Khác' ;
            }
            if($items[$key]['entry_target']=='')
            {
                $items[$key]['entry_target']='Công tác';
            }
            if($items[$key]['check_pa18'] ==0)
            {
                $items[$key]['check_pa18'] = 'Chưa chuyển';
            }else
            {
                $items[$key]['check_pa18'] = 'Chuyển';
            }
            if($items[$key]['port_of_entry'] =='')
            {
                $items[$key]['port_of_entry'] ='Sân bay Quốc tế Nội Bài';
            }
            if(!$value['entry_date'] and !$value['port_of_entry'] and !$value['back_date'] and !$value['entry_target'] and !$value['go_to_office'] and !$value['come_from'])
            {
                $items[$key]['inputed_pa18_info'] = false;
            }else
            {
                $items[$key]['inputed_pa18_info'] = true;
            }
            $items[$key]['time_in']=$value['time_in']?date('d/m/Y',$value['time_in']):'';
            $items[$key]['time_out']=$value['time_out']?date('d/m/Y',$value['time_out']):'';
            $items[$key]['time_out_expected']=$value['time_out']?date('d/m/Y',$value['time_out']+86400):'';
            if($value['code_nationality'] == 'VNM')
            {
                $items[$key]['i']=$i++;                
            }else
            {
                $items[$key]['i']=$j++;                
            }
        }
        $nationality = DB::fetch_all('
		 		SELECT
					id, 
                    country.name_'.Portal::language().' as name,
                    country.code_'.Portal::language().' as code
				FROM 
					country
				WHERE 
					1=1
				ORDER BY 
					name_2'
		);
        $dbf_file = '';
		$dir_string = 'cache\portal\\'.str_replace('#','',PORTAL_ID).'\\';
		$dir = opendir($dir_string);
		while($file = readdir($dir))
        {
			if(file_exists($dir_string.$file) and is_file($dir_string.$file))
            {
				$arr = explode('.',$file);
				if(isset($arr[1]) and strtoupper($arr[1]) == 'DBF')
                {
					$dbf_file = $dir_string.$file;
				}
			}
		}
        $this->map['dbf_file'] = $dbf_file;
        $this->map['nationality_list'] = array(
                        'ALL'=> Portal::language('all'),
                        '439'=> Portal::language('Việt Nam'),
                        '111'=> Portal::language('foreign')
        );
        $this->map += array(
    				'items'=>$items,
    				'paging'=>$paging
		);
        $file_name = explode('/',$_SERVER['REQUEST_URI']);
        $file_name = $file_name[1];
        $this->map['file_name'] = $file_name;
        $this->parse_layout('list', $this->map);
        if(Url::get('export_file_excel'))
        {
            if(Url::get('arrival_time'))
            {
				$this->export_file_excel(Url::get('arrival_time'),$file_name);
			}
        }
        if(Url::get('export_xml_nn'))
        {
            if(Url::get('arrival_time'))
            {
                $this->export_file_xml($items,Url::get('arrival_time'),$file_name);
            }
		    
        }
        //System::debug($items);
    }
    function export_file_excel($from_date,$filename)
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
            for($j=0;$j<count($traveller_ids);$j++)
            {
            	$cond .=($j==0)?'':' or ';
            	$cond .= ' reservation_traveller.id = '.$traveller_ids[$j].'';	
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
                        (reservation_traveller.old_arrival_time is null and from_unixtime(reservation_traveller.arrival_time) = \''.(Date_Time::to_orc_date($from_date)).'\' )
                        or 
                        (reservation_traveller.old_arrival_time is not null and from_unixtime(reservation_traveller.old_arrival_time) = \''.(Date_Time::to_orc_date($from_date)).'\' )
                ) 
                and reservation.portal_id=\''.PORTAL_ID.'\''
            	.((URL::get('nationality')=='439')?' AND traveller.nationality_id = 439':'')
            	.((URL::get('nationality')=='111')?' AND traveller.nationality_id <> 439':'').'
            ';
        }
        $export = DB::fetch_all('
            select distinct
					 traveller.id
					,traveller.first_name || \' \' || traveller.last_name as full_name
                    ,traveller.gender
					,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
					,traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax 
					,traveller.note 
					,traveller.is_vn
                    ,traveller.birth_date_correct
                    ,case
                    when reservation_traveller.time_in_pa18 is null
                    then reservation_traveller.arrival_time
                    else reservation_traveller.time_in_pa18
                    end as time_in
                    ,case
                    when reservation_traveller.time_out_pa18 is null
                    then reservation_traveller.departure_time
                    else reservation_traveller.time_out_pa18
                    end as time_out
                    ,reservation_traveller.arrival_time as time_in1
                    ,reservation_traveller.departure_time as time_out1
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
					inner join country on country.id=traveller.nationality_id 
				where '.$cond.' 
        ');
        $i++;
		foreach($export as $key=>$value)
		{
		    $value['gender']=$value['gender']?'M':'F';
            $hour_in=$value['time_in']?date('H:i',$value['time_in1']):'';
            $hour_out=$value['time_out']?date('H:i',$value['time_out1']):'';
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
        $fileName = $filename."-pa18-export-".(str_replace('/','-',$from_date)).".xls";
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
    function export_file_xml($items,$from_date,$filename)
    {
        $i =1;
        $pa18 = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $pa18 .= "<KHAI_BAO_TAM_TRU>\n";
        foreach($items as $key => $value)
        {
            if($items[$key]['code_nationality'] != 'VNM')
            {
                if(!$value['pa18'])
                {
                    //System::debug($value);
                    $pa18 .= "<THONG_TIN_KHACH>\n";
                        $pa18 .= "<so_thu_tu>". $i . "</so_thu_tu>\n";
                        $pa18 .= "<ho_ten>". $value['first_name'] . ' ' . $value['last_name'] . "</ho_ten>\n";
                        $pa18 .= "<ngay_sinh>". $value['birth_date'] . "</ngay_sinh>\n";
                        $pa18 .= "<ngay_sinh_dung_den>". $value['birth_date_correct'] . "</ngay_sinh_dung_den>\n";
                        $pa18 .= "<gioi_tinh>". $value['gender'] . "</gioi_tinh>\n";
                        $pa18 .= "<ma_quoc_tich>". $value['code_nationality'] . "</ma_quoc_tich>\n";
                        $pa18 .= "<so_ho_chieu>". $value['passport'] . "</so_ho_chieu>\n";
                        $pa18 .= "<so_phong>". $value['room_name'] . "</so_phong>\n";
                        $pa18 .= "<ngay_den>". $value['time_in'] . "</ngay_den>\n";
                        $pa18 .= "<ngay_di_du_kien>". $value['time_out'] . "</ngay_di_du_kien>\n";
                        $pa18 .= "<ngay_tra_phong>". $value['time_out'] . "</ngay_tra_phong>\n";
                    $pa18 .= "</THONG_TIN_KHACH>\n";
                    $i++; 
                    //DB::update('reservation_traveller',array('pa18'=>1),'id='.$value['r_traveller_id'].'');                          
                }                
            }            
        }
        $pa18 .= "</KHAI_BAO_TAM_TRU>";
        $file_name = $filename.'-pa18-'.date('d-m-Y', Date_Time::to_time($from_date)) . '.xml';
        file_put_contents($file_name, $pa18);
        echo "<script>";
        echo 'alert(\'Đã xuất file thành công!\');';
        echo "</script>";
    }
}
?>