<?php
class UpdateTravellerForm extends Form{
	function UpdateTravellerForm()
    {
		Form::Form('UpdateTravellerForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");		
		//$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//$this->link_js("packages/core/includes/js/jquery.windows-engine.js");
		//$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		//$this->link_js('r_get_reservation_traveller.php?id='.Url::get('r_id'),false);
		//$this->link_js('r_get_reservation.php?id='.Url::get('r_id'),false);
		
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');	
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');		
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');     
		
		$this->add('traveller.first_name',new TextType(true,'invalid_first_name',0,255)); 
		$this->add('traveller.last_name',new TextType(true,'invalid_last_name',0,255)); 
		$this->add('traveller.birth_date',new DateType(false,'invalid_birth_date')); 
		$this->add('traveller.passport',new TextType(false,'miss_passport',0,255)); 
		//$this->add('traveller.nationality_id',new TextType(true,'miss_nationality',0,255)); 
		$this->add('traveller.visa',new TextType(false,'invalid_visa',0,255));
		$this->add('traveller.note',new TextType(false,'invalid_note',0,255)); 
		//$this->add('traveller.traveller_level_id',new TextType(true,'invalid_traveller_level',0,255)); 
		//$this->add('traveller.phone',new TextType(false,'invalid_phone',0,255)); 
		//$this->add('traveller.fax',new TextType(false,'invalid_fax',0,255)); 
		//$this->add('traveller.address',new TextType(false,'invalid_address',0,2000)); 
		$this->add('traveller.email',new EmailType(false,'invalid_email')); 
		$this->add('traveller.traveller_room_id',new TextType(false,'invalid_room_id',0,255));
        //oanhbtk add
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function on_submit()
    {
		if(Url::get('save') || Url::get('update'))
        {
			if($this->check())
            {
				require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
                require_once 'packages/hotel/includes/member.php';
                require_once 'packages/hotel/includes/Email/class_mail/class.verifyEmail.php';
				//$old_reservation_room = DB::select_all('reservation_room',' 1>0 '.(URL::get('rr_id')?' and id=\''.URL::get('rr_id').'\'':''));
				//$id = $old_reservation_room[URL::get('rr_id')]['reservation_id'];
				$id;$rr_id = null;
                
                //System::debug($_REQUEST);exit();
                if(Url::get('r_id'))
                {
					$id =  Url::get('r_id');	
				}
                if(Url::get('rr_id'))
                {
					$rr_id =  Url::get('rr_id');	
				}
                
                /** Oanh add thong bao loi  **/
                
                if(isset($_REQUEST['mi_traveller']))
                {
                    if($_REQUEST['mi_traveller'])
                    {
                        $stt_id = 0;
                        $check = false;
                        foreach($_REQUEST['mi_traveller'] as $mi_id=>$mi_value){ 
                            $stt_id ++;
                            $check_traveller[$stt_id]['id'] = $mi_id;
                            $check_traveller[$stt_id]['status'] = $mi_value['status'];
                            $check_traveller[$stt_id]['passport'] = $mi_value['passport'];
                            $check_traveller[$stt_id]['mi_traveller_room_name'] = $mi_value['mi_traveller_room_name'];
                        }
                        /** check trùng khi nhap passport **/
                        if(($mi_value['passport'] != '') AND ($mi_value['passport'] != '?') ) //
                        {
                            $passport_error = '';
                            for($k=1;$k<$stt_id;$k++)
                            {
                                for($l=$k+1;$l<=$stt_id;$l++)
                                {
                                    require_once 'packages/core/includes/utils/vn_code.php';
                                    if($check_traveller[$k]['passport'] != $check_traveller[$l]['passport']){
                                        //$check = false;
                                    }
                                    elseif($check_traveller[$k]['passport']!='?' and $check_traveller[$k]['status']!='CHECKOUT' and strtoupper(convert_utf8_to_latin($check_traveller[$k]['passport'],'utf-8')) != 'BAO LANH')
                                    {
                                       $passport_error .= $check_traveller[$l]['passport'].',';
                                       $check = true;
                                    }
                                }
                            }
                            if($check == true){
                                $this->error('passport',$passport_error.'-'.Portal::language('cmt_ho_chieu_trung_nhau'));
                                return false;
                            }
                        }
                    }
                }
                
                /** End check trùng khi nhap  passport **/
                
                /** Oanh add Check khong chon phong, dua ra cảnh báo 
                
                if(DB::exists('select * from room where name =\''.$mi_value['mi_traveller_room_name'].'\' '))
                {
                   
                }
                else
                {
                     echo '<script type="text/javascript" language="javascript">
                            alert("Chưa chọn phòng cho khách !")
                          </script>';
                     return;
                }
                End Check khong chon phong, dua ra cảnh báo **/
                
       /** End oanh **/
                
				$sql = '
					SELECT   
						reservation_traveller.id
						,traveller.first_name
                        ,traveller.last_name 
                        ,traveller.gender 
						,traveller.birth_date
                        ,traveller.is_child
						,traveller.passport
                        ,traveller.member_code
                        ,traveller.competence
						,reservation_traveller.visa_number as visa
						,reservation_traveller.expire_date_of_visa
						,traveller.note 
						,traveller.phone 
                        ,traveller.fax 
                        ,traveller.address 
						,traveller.email 
						,traveller.nationality_id 
						,reservation_room.reservation_id
						,reservation_traveller.reservation_room_id
						,traveller.id as traveller_id,
                        --Oanh add
                        traveller.is_vn
					FROM
						reservation_traveller
						inner join traveller on traveller.id=reservation_traveller.traveller_id
						left outer join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
					WHERE
						reservation_traveller.reservation_id='.$id.'
						'.(Url::get('rr_id')?' and reservation_traveller.reservation_room_id='.Url::get('rr_id').'':'').'
				';
				$old_travellers = DB::fetch_all($sql);
				$title = ''; 
                $description = ''; 
                $customer_name='';
                //System::debug($_REQUEST);die;
				update_reservation_traveller($this, $id,$rr_id, $old_travellers, $title, $description, $customer_name,$change_status);
				//exit();
				if(Url::get('update'))
                {
					$tt = 'form.php?block_id='.Module::block_id().''.(Url::get('cmd')?'&cmd='.Url::get('cmd').'':'').'&r_id='.Url::get('r_id').''.(Url::get('rr_id')?'&rr_id='.Url::get('rr_id').'':'').'';
					echo '<script>window.location.href = \''.$tt.'\'</script>';
				}
                else
                {
					echo '<script>
                        var at_path = window.parent.location.toString();
                        if(at_path.indexOf("&adddd_guest=yes")>-1)
                        {
                            var ts_path = at_path.replace("&adddd_guest=yes","");
                            window.parent.location.replace(ts_path);
                        }
                        else
                    	   window.parent.location.reload();
                    </script>';
				}
			}
		}
	}
	function draw()
    {	
		$this->map = array();
		$row = array();
		$this->map['count_guest'] = '{}';
		if(Url::get('r_id')){
			$this->map['r_r_id_list'] = String::get_list(DB::fetch_all('select reservation_room.id
						,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END name
			from reservation_room
				inner join reservation on reservation_room.reservation_id = reservation.id
				inner join room on reservation_room.room_id  = room.id
			where reservation.id = '.Url::get('r_id').'
			'));	
		}
                
		if(Url::get('r_id')){
			$sql_r = '
					select
						reservation_room.id
						,reservation_room.status as old_status
						,reservation_room.status					
						,reservation_room.time_in
						,reservation_room.time_out
						,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time
						,to_char(reservation_room.arrival_time,\'DD\') as day_arrival_time
                        ,to_char(reservation_room.arrival_time,\'MM\') as month_arrival_time
                        ,to_char(reservation_room.arrival_time,\'YYYY\') as year_arrival_time
                        ,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time
                        ,to_char(reservation_room.departure_time,\'DD\') as day_departure_time
                        ,to_char(reservation_room.departure_time,\'MM\') as month_departure_time
                        ,to_char(reservation_room.departure_time,\'YYYY\') as year_departure_time
                        ,reservation_room.room_level_id 
						,room_level.brief_name as room_level
						,reservation_room.room_id
						,reservation_room.room_id AS room_id_old 
						,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
						,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
						,reservation_room.traveller_id
						,reservation_room.reservation_id 
					from
						reservation_room
						left outer join room on room.id=reservation_room.room_id
						left outer join room_level on room_level.id=reservation_room.room_level_id
						left outer join room_status on room_status.reservation_room_id=reservation_room.id
						left outer join payment_type on payment_type.id=reservation_room.payment_type_id
					where 1>0
						'.(URL::get('r_id')?' and reservation_room.reservation_id=\''.URL::get('r_id').'\'':'').'
					order by
						reservation_room.time_in asc';
			$mi_reservation_room = DB::fetch_all($sql_r);
			$_REQUEST['mi_reservation_room'] = $mi_reservation_room;
			foreach($mi_reservation_room as $k => $reservation){
				if(Url::get('rr_id') && $k == Url::get('rr_id')){
					$row = $reservation;
				}
			}
			if(!isset($_REQUEST['mi_traveller']))   
			{		
				$sql = '
					select
						reservation_traveller.id
						,reservation_traveller.id as reservation_traveller_id
						,reservation_traveller.pa18
						,traveller.first_name 
                        ,traveller.last_name 
                        ,traveller.gender  
						,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
                        ,traveller.is_child
						,traveller.passport 
                        ,traveller.visa 
                        ,reservation_traveller.special_request as note
						,traveller.phone 
                        ,traveller.fax 
                        ,traveller.address 
                        ,traveller.email
                        ,traveller.member_code
                        ,traveller.competence
						,country.code_1 as nationality_id
						,country.name_'.Portal::language().' as nationality_name
						,reservation_room.reservation_id
						,reservation_traveller.reservation_room_id
						,reservation_traveller.arrival_time
						,reservation_traveller.departure_time
                        ,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as room_arrival_time
						,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as room_departure_time
                        ,reservation_traveller.visa_number as visa
						,to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa
						,reservation_traveller.flight_code
						,reservation_traveller.flight_arrival_time
						,reservation_traveller.flight_code_departure
						,reservation_traveller.flight_departure_time
						,reservation_traveller.car_note_arrival
						,reservation_traveller.car_note_departure															
						,to_char(reservation_traveller.arrival_date,\'DD/MM/YYYY\') as traveller_arrival_date
						,to_char(reservation_traveller.departure_date,\'DD/MM/YYYY\') as traveller_departure_date
						,CASE WHEN reservation_room.room_id is not null THEN room.name ELSE reservation_room.temp_room END as mi_traveller_room_name
						,room_level.brief_name as room_level
                        ,CASE WHEN reservation_room.room_id is not null THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) END as traveller_room_id
						,DECODE(reservation_room.traveller_id,reservation_traveller.traveller_id,1,0) as traveller_id
						,traveller.id as traveller_id_
                        ,traveller.province_id
						,reservation_traveller.time_out
						,reservation_traveller.time_in
						,traveller.traveller_level_id
						,reservation_traveller.status
						,reservation_traveller.pickup
						,reservation_traveller.see_off
						,reservation_traveller.pickup_foc
						,reservation_traveller.see_off_foc
						,traveller.transit
                        ,reservation_traveller.to_judge
                        --Oanh add
                        ,traveller.is_vn
					from
						reservation_traveller
						inner join traveller on traveller.id=reservation_traveller.traveller_id
						left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
						left outer join room on reservation_room.room_id=room.id
                        left outer join room_level on reservation_room.room_level_id=room_level.id                        
						left outer join country on traveller.nationality_id=country.id
					where
						reservation_traveller.reservation_id='.Url::get('r_id')
						.(URL::get('rr_id')?' and reservation_room.id=\''.URL::get('rr_id').'\'':'').'
					order by
						reservation_traveller.reservation_room_id asc';
				$mi_travellers = DB::fetch_all($sql);
              
				foreach($mi_travellers as $k=>$mi_traveller)	
				{	
					$mi_travellers[$k]['old_reservation_room_id'] = $mi_traveller['reservation_room_id'];
					$mi_travellers[$k]['check_out'] = 0;
					if($mi_traveller['arrival_time'])
					{
						$mi_travellers[$k]['arrival_hour'] = date('H:i',$mi_traveller['arrival_time']);
					}
					if($mi_traveller['departure_time'])
					{
						$mi_travellers[$k]['departure_hour'] = date('H:i',$mi_traveller['departure_time']);				
					}
					if($mi_traveller['flight_arrival_time'])
					{
						$mi_travellers[$k]['flight_arrival_hour'] = date('H:i',$mi_traveller['flight_arrival_time']);	
						$mi_travellers[$k]['flight_arrival_date'] = date('d/m/Y',$mi_traveller['flight_arrival_time']);	
					}
					if($mi_traveller['flight_departure_time'])
					{
						$mi_travellers[$k]['flight_departure_hour'] = date('H:i',$mi_traveller['flight_departure_time']);	
						$mi_travellers[$k]['flight_departure_date'] = date('d/m/Y',$mi_traveller['flight_departure_time']);	
					}
					// Check xem khach nao da chuyeern phong 
					$mi_travellers[$k]['check_out'] = 0;
					if($mi_traveller['status'] =='CHECKOUT' || $mi_traveller['status'] =='CHANGE'){// khach da out
						$mi_travellers[$k]['check_out'] = 1;	
					}
				}
				$_REQUEST['mi_traveller'] = $mi_travellers;  
				//System::Debug($mi_travellers);  
			}
			$count_guest = DB::fetch_all('select reservation_room.id,count(reservation_traveller.id) as count
											FROM reservation_traveller
											inner join traveller on traveller.id=reservation_traveller.traveller_id
											inner join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
											inner join reservation on reservation_room.reservation_id = reservation.id
											where 
												reservation.id = '.Url::get('r_id').'
												AND reservation_traveller.status=\'CHECKIN\'
											GROUP by reservation_room.id
											');
			$this->map['count_guest'] = String::array2js($count_guest);
			//System::debug($count_guest); exit();
		}
		$this->map +=array(
			'payment_types'=>DB::fetch_all('select def_code as id, name_'.Portal::language().' as name from payment_type where '.IDStructure::direct_child_cond(ID_ROOT).' order by name'),
			'nationalities'=>DB::fetch_all('select code_1 as id, name_'.Portal::language().' as name from country where 1=1 order by name_'.Portal::language().''),
			'vip_card_list'=>array(''=>'')+String::get_list(DB::fetch_all('select code as id,discount_percent as name from vip_card order by code')),
			);
		$guest_types = DB::fetch_all('select id,name from guest_type order by position');
		$guest_type_options = '<option value="">'.mb_strtoupper(Portal::language('select'),'utf-8').'</option>';
		foreach($guest_types as $key=>$value){
			$guest_type_options .= '<option value="'.$key.'">'.mb_strtoupper($value['name'],'utf-8').'</option>';
		}				
		$this->map['traveller_level_options'] = $guest_type_options;
		//$this->map['reservation_rooms'] = $mi_reservation_room;
        /** oanh add **/
        $this->map['is_vn_list'] = array('0'=>Portal::language('Alien'),'1'=>Portal::language('Overseas_Vietnamese'),'2'=>Portal::language('Viet_nam'),'3'=>Portal::language('Viet_nam_in_foreign'));
        /** End oanh **/
        //list province
        $db_items = DB::select_all('province',false,'name');
		$province_options = '';
		foreach($db_items as $item)
		{
			$province_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}		
		$this->map['province_options'] = $province_options;
		$this->map['portals'] = Portal::get_portal_list(); 
		$this->parse_layout('list',$row+$this->map);
        /** oanh add **/
         if(Url::get('export_file_excel'))
         {
            $this->export_file_excel($row); 
            return false;
         }
        /** end oanh **/       
	}
   
   /** Oanh add export file excel **/
    function export_file_excel($row)
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'STT');
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Phòng');
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Họ, tên đệm (*)');
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Tên (*)');
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Hộ chiếu(CMND)');
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Quá cảnh (transit)');
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Visa');
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Hạn Visa (date)');
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Giới tính');
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Ngày sinh (date)');
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Quốc tịch');      
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Tỉnh');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Hạng khách(*)');
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Điện thoại cơ quan');
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Email');
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'Địa chỉ');
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Giờ checkin');
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Từ ngày (date)');  
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Giờ checkout');       
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Đến ngày(date)');
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, 'Đánh giá (Số từ 1->5)');
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, 'Ghi chú chung cho phòng');
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, 'Chuyến bay đến');
            $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'Giờ máy bay đến(H)');
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, 'Ngày máy bay đến(date)');
            $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, 'Ghi chú đón');
            $objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, 'Chuyến bay đi');
            $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, 'Giờ máy bay đi(H)');
            $objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, 'Ngày máy bay đi(date)');
            $objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, 'Ghi chú Tiễn');
            $objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, 'Pick up');
            $objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, 'Pickup foc');
            $objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, 'See off');
            $objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, 'See off up');
            $objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, 'Member');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, 'Là trẻ em (Is child)');
        $sql = 'select  
						reservation_traveller.id as id
						,reservation_traveller.id as reservation_traveller_id
						,reservation_traveller.pa18
						,traveller.first_name 
                        ,traveller.last_name 
                        ,traveller.gender 
						,to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
                        ,traveller.is_child
						,traveller.passport 
                        ,traveller.visa 
                        ,reservation_traveller.special_request as note
						,traveller.phone 
                        ,traveller.fax 
                        ,traveller.address 
                        ,traveller.competence
                        ,traveller.email
                        ,traveller.member_code
                        --,traveller.image_profile
						,country.code_1 as nationality_id
						,country.name_'.Portal::language().' as nationality_name
						,reservation_room.reservation_id
						,reservation_traveller.reservation_room_id
						,reservation_traveller.arrival_time
						,reservation_traveller.departure_time
                        ,reservation_traveller.visa_number as visa
						,to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa
						,reservation_traveller.flight_code
						,reservation_traveller.flight_arrival_time
						,reservation_traveller.flight_code_departure
						,reservation_traveller.flight_departure_time
						,reservation_traveller.car_note_arrival
						,reservation_traveller.car_note_departure															
						,to_char(reservation_traveller.arrival_date,\'DD/MM/YYYY\') as traveller_arrival_date
						,to_char(reservation_traveller.departure_date,\'DD/MM/YYYY\') as traveller_departure_date
						,CASE WHEN reservation_room.room_id is not null THEN room.name ELSE reservation_room.temp_room END as mi_traveller_room_name
						,room_level.brief_name as room_level
                        ,CASE WHEN reservation_room.room_id is not null THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) END as traveller_room_id
						,DECODE(reservation_room.traveller_id,reservation_traveller.traveller_id,1,0) as traveller_id
						,traveller.id as traveller_id
                        ,traveller.province_id
                        ,province.name as province_name
						,reservation_traveller.time_out
						,reservation_traveller.time_in
						,traveller.traveller_level_id
						,reservation_traveller.status
						,reservation_traveller.pickup
						,reservation_traveller.see_off
						,reservation_traveller.pickup_foc
						,reservation_traveller.see_off_foc
						,traveller.transit
                        ,reservation_traveller.to_judge
                        ,reservation_room.id as reservation_room_id
						,reservation_room.status as old_status
						,reservation_room.status					
						,reservation_room.time_in
						,reservation_room.time_out
						,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as r_arrival_time
						,to_char(reservation_room.arrival_time,\'DD\') as day_arrival_time
                        ,to_char(reservation_room.arrival_time,\'MM\') as month_arrival_time
                        ,to_char(reservation_room.arrival_time,\'YYYY\') as year_arrival_time
                        ,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as r_departure_time
                        ,to_char(reservation_room.departure_time,\'DD\') as day_departure_time
                        ,to_char(reservation_room.departure_time,\'MM\') as month_departure_time
                        ,to_char(reservation_room.departure_time,\'YYYY\') as year_departure_time
                        ,reservation_room.room_level_id 
						,room_level.brief_name as room_level
                        ,guest_type.name as guest_type_name
						,reservation_room.room_id
						,reservation_room.room_id AS room_id_old 
						,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
						,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
						,reservation_room.traveller_id
						,reservation_room.reservation_id 
					from
						reservation_traveller
						inner join traveller on traveller.id=reservation_traveller.traveller_id
						left outer join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
						left outer join room on reservation_room.room_id=room.id
                        left outer join room_level on reservation_room.room_level_id=room_level.id                        
						left outer join country on traveller.nationality_id=country.id
                        left join province on province.id= reservation_traveller.province_id
                        left join guest_type on guest_type.id = traveller.traveller_level_id
					where
						reservation_room.reservation_id='.Url::get('r_id')
						.(URL::get('rr_id')?' and reservation_room.id=\''.URL::get('rr_id').'\'':'').'
					order by
                        traveller.id asc                    
						--reservation_traveller.reservation_room_id asc
                        ';
           
        $export=DB::fetch_all($sql);  
        //System::debug($sql);
        //System::debug($export);
        
        //$export_new = array();
        $stt=1;
		foreach($export as $key_ex=>$value_ex)
		{	 
            $value_ex['arrival_time']=$value_ex['arrival_time']?date('H:i d/m/y',$value_ex['arrival_time']):'';
            $value_ex['departure_time']=$value_ex['departure_time']?date('H:i d/m/y',$value_ex['departure_time']):'';            
            $value_ex['arrival_hour']='';
            $value_ex['departure_hour']='';
            $value_ex['flight_arrival_hour'] = '';
            $value_ex['flight_arrival_date'] = '';
            $value_ex['flight_departure_hour'] = '';	
			$value_ex['flight_departure_date'] = ''; 

             //if($key_ex['passport']='?'){
             //     $value_ex['passport']='';
             //}
             
             $value_ex['gender']=$value_ex['gender']?'nam':'nu';
             $value_ex['transit']=$value_ex['transit']?'on':'off';
             $value_ex['pickup']=$value_ex['pickup']?'on':'off';
             $value_ex['pickup_foc']=$value_ex['pickup_foc']?'on':'off';
             $value_ex['see_off']=$value_ex['see_off']?'on':'off';
             $value_ex['see_off_foc']=$value_ex['see_off_foc']?'on':'off';
             $value_ex['member_code']=$value_ex['member_code']?'on':'off';
             $value_ex['is_child']=$value_ex['is_child']?'on':'off';
             
            //gan bien cho cac truong            
            $i++; 
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $stt++);                      
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value_ex['room_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value_ex['first_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value_ex['last_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value_ex['passport']);
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value_ex['transit']);
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value_ex['visa']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value_ex['expire_date_of_visa']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value_ex['gender']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value_ex['birth_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value_ex['nationality_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value_ex['province_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value_ex['guest_type_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $value_ex['phone']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $value_ex['email']);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $value_ex['address']); 
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value_ex['time_in'] = '');  
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $value_ex['arrival_time']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $value_ex['time_out'] = '');   
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value_ex['departure_time']='');  
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $value_ex['to_judge']); 
            $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $value_ex['note']); 
            $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $value_ex['flight_code']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $value_ex['flight_arrival_hour']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $value_ex['flight_arrival_date']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $value_ex['car_note_arrival']='');    
            $objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $value_ex['flight_code_departure']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $value_ex['flight_departure_hour']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $value_ex['flight_departure_date']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $value_ex['car_note_departure']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $value_ex['pickup']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $value_ex['pickup_foc']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, $value_ex['see_off']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, $value_ex['see_off_foc']=''); 
            $objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $value_ex['member_code']='');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, $value_ex['is_child']);       
        }
        //System::debug($export);exit();
        $fileName = "TravellerReservation_export".".xls";
       // System::debug($objPHPExcel); exit();
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
        
    }
}
?>