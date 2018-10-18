<?php
class EditTravellerForm extends Form
{
	function EditTravellerForm()
	{
		Form::Form('EditTravellerForm');
		$this->add('id',new IDType(true,'object_not_exists','traveller'));
		$this->add('first_name',new TextType(true,'invalid_first_name',0,255)); 
		$this->add('last_name',new TextType(true,'invalid_last_name',0,255)); 
		$this->add('gender',new SelectType(true,'invalid_gender',array('1'=>'male','0'=>'female'))); 
		$this->add('birth_date',new DateType(true,'invalid_birth_date')); 
		$this->add('passport',new TextType(true,'invalid_passport',0,255)); 
		$this->add('address',new TextType(false,'invalid_address',0,255)); 
		$this->add('email',new TextType(false,'invalid_email',0,255)); 
		$this->add('phone',new TextType(false,'invalid_phone',0,255)); 
		$this->add('fax',new TextType(false,'invalid_fax',0,255)); 
		$this->add('note',new TextType(false,'invalid_note',0,200000)); 
		$this->add('nationality_id',new IDType(true,'invalid_nationality_id','country'));
		
		$this->add('visa_number',new TextType(false,'invalid_visa_number',0,255)); 
		$this->add('expire_date_of_visa',new DateType(false,'expire_date_of_visa'));
		$this->add('entry_date',new DateType(false,'entry_date'));
		$this->add('port_of_entry',new TextType(false,'port_of_entry',0,255));
		$this->add('entry_form_number',new TextType(false,'entry_form_number',0,255));
		$this->add('back_date',new DateType(false,'back_date'));
		$this->add('occupation',new TextType(false,'occupation',0,255));
		$this->add('entry_target',new TextType(false,'entry_target',0,255));
		$this->add('go_to_office',new TextType(false,'go_to_office',0,255));
		$this->add('come_from',new IDType(false,'come_from','country'));
		
		$this->add('address',new TextType(false,'invalid_address',0,255)); 
		$this->add('email',new TextType(false,'invalid_email',0,255)); 
		$this->add('phone',new TextType(false,'invalid_phone',0,255)); 
		$this->add('fax',new TextType(false,'invalid_fax',0,255)); 
		$this->add('note',new TextType(false,'invalid_note',0,200000)); 
		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');		
		$this->link_js('packages/hotel/includes/js/ajax.js');
	}
	function on_submit()
	{
	   require_once 'packages/hotel/includes/member.php';
		$row = DB::select('traveller',Url::iget('id'));
        $confix_passport = DB::fetch_all("SELECT id FROM traveller WHERE passport='".Url::get('passport')."' AND id!=".Url::iget('id')."");
       
        if(sizeof($confix_passport)>0){
            $this->add('passport',new TextType(true,'Tr�ng s? CMND!',255,255));
        }
		//if($this->check())
		//{
	   //System::debug($_REQUEST);exit();
		  if(Url::get('create_member_code')){
		      $date = getdate(); $to_day = $date['mday']."/".$date['mon']."/".$date['year'];
              $to_day = Date_Time::to_orc_date($to_day);
		      $member_code = create_member_code();
              $password = create_password_radom();
              $member_level = DB::fetch("SELECT id FROM member_level WHERE min_point=0");
              DB::update('traveller',array('member_code'=>$member_code,'password'=>$password,'point'=>0,'point_user'=>0,'member_level_id'=>$member_level['id'],'member_create_date'=>$to_day),' traveller.id='.Url::iget('id').'');
              $full_name = Url::get('first_name')." ".Url::get('last_name');
                $content = "<h1>"."Xin chào ".$full_name."</h1><br />";
                $content .= "<h4>Thông tin đăng nhập của bạn:</h4><br />";
                $content .= "<p>Username:</p>".$member_code."<br />";
                $content .= "<p>Password:</p>".$password."<br />";
                $mail_member = Url::get('email');
                if(!filter_var($mail_member, FILTER_VALIDATE_EMAIL)){
                    echo "<script>";
                    echo "alert('is not email');";
                    echo "</script>";
                }else{
                    sent_mail_to($mail_member,$content);
                } 
		  }
			DB::update_id('traveller', 
				array(
					'nationality_id', 
					'first_name','last_name','gender','birth_date_correct',
					'birth_date'=>Date_Time::to_orc_date(URL::get('birth_date')),
					'passport', 'visa', 'address', 'email', 'phone', 'fax', 'note','province_id',
					'is_vn'=>Url::get('is_vn'),'last_update'=>Date_Time::convert_time_to_ora_date()
				),
				Url::iget('id')
			);
			$this->update_reservation_traveller(Url::iget('id'));
			echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> 
					Updating room status to server...</div>';
            if(Url::get('save')){
				echo '<script>
				if(window.opener && window.opener.night_audit)
				{
					window.opener.history.go(0);
					window.close();
				}
				'.(Url::get('reservation_id')?'window.setTimeout("location=\''.URL::build_current(array('cmd','reservation_id','id'=>Url::iget('id'))).'\'",1000);</script>':'window.setTimeout("location=\''.URL::build_current(array('just_edited_id'=>Url::iget('id'))).'\'",1000);</script>').'';
			     exit();
            }else{
                $full_name = Url::get('first_name')." ".Url::get('last_name');
                echo '<script>';
                echo 'window.location="'.Url::build('traveller',array('cmd'=>'list_member','full_name'=>$full_name)).'";';
                echo '</script>';
                exit();
            }
		//}
	}	
	function draw()
	{
		$this->map = array();
		DB::query('
			select 
				traveller.id,traveller.first_name,
				traveller.last_name,traveller.gender,traveller.passport,
				traveller.visa,traveller.address,
				traveller.email,traveller.phone,
				traveller.fax,traveller.note,
				traveller.birth_date_correct,
				traveller.tour_id,traveller.nationality_id,
                traveller.province_id,traveller.member_code,
				to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
				traveller.is_vn
			from 
			 	traveller
			where
				id = '.Url::iget('id').'
		');
		$row = DB::fetch();
		DB::query('
			select
				id, concat(country.code_2, concat(\' - \',country.name_'.Portal::language().')) as name
			from 
				country
			where 
				1=1
			order by 
				name_2
			'
		);
		$nationality_id_list =String::get_list(DB::fetch_all()); 
        
        //list province
        DB::query('
			select
				id, 
                concat(province.code, concat(\' - \',province.name)) as name
			from 
				province
			where 
				1=1
			order by 
				name
			'
		);
		$province_id_list =String::get_list(DB::fetch_all()); 
		$this->map['time_in'] = '';
		$this->map['time_out'] = '';
		$this->map['entry_date'] = '';
		$this->map['back_date'] = '';
		$this->map['input_date'] = '';
		$this->map['come_from'] = '';
		$this->map += $row;
		if($rooms = ListTravellerDB::get_reservation_room('reservation_traveller.traveller_id='.$row['id'].'')){
			foreach($rooms as $value){
				$this->map += $value;
				$this->map['time_in'] = date('d/m/Y',$value['time_in']);
				$this->map['time_out'] = $value['traveller_time_out']?date('d/m/Y',$value['traveller_time_out']):date('d/m/Y',$value['time_out']);
				$this->map['entry_date'] = $value['entry_date'];
				$this->map['back_date'] = $value['back_date'];;
				$this->map['input_date'] = $value['input_date'];
				$this->map['come_from'] = $value['come_from'];
				$this->map['visa_number'] = $value['visa_number'];
				$this->map['expire_date_of_visa'] = Date_Time::convert_orc_date_to_date($value['expire_date_of_visa'],'/');
				$this->map['entry_form_number'] = $value['entry_form_number'];		
				$this->map['occupation'] = $value['occupation'];		
			}
			$this->map['entry_date'] = Date_Time::convert_orc_date_to_date($this->map['entry_date'],'/');
			$this->map['back_date'] = Date_Time::convert_orc_date_to_date($this->map['back_date'],'/');
			$this->map['input_date'] = Date_Time::convert_orc_date_to_date($this->map['input_date'],'/');
			$this->map['reservation_room_id_list'] = String::get_list($rooms);
		}
        //System::debug($rooms);
		foreach($this->map as $key=>$value)
		{
			if(!isset($_REQUEST[$key]))
			{
				$_REQUEST[$key] = $value;
			}
		}
		$ports = array('XXX'=>Portal::language('not_update_yet'))+String::get_list(DB::fetch_all('select code as id,CONCAT(gate.code,CONCAT(\' - \',gate.name)) as name from gate'));
		$entry_target =String::get_list(DB::fetch_all('select code as id,CONCAT(entry_purposes.code,CONCAT(\' - \',entry_purposes.name)) as name from entry_purposes order by code ASC'));
		$is_vn = array(''=>'','0'=>'0-'.Portal::language('Alien'),'1'=>'1-'.Portal::language('Overseas_Vietnamese'),'2'=>'2-'.Portal::language('Viet_nam'));
        $this->map += array(
			'nationality_id_list'=>array(''=>Portal::language('select'))+$nationality_id_list,
            'province_id_list'=>array(''=>Portal::language('select'))+$province_id_list,
			'come_from_list'=>array(''=>Portal::language('select'))+$nationality_id_list,
			'port_of_entry_list'=>$ports,
			'entry_target_list'=>$entry_target,
            'is_vn_list' => $is_vn
		);
		if(Url::get('reservation_id')){
			$this->map['id_list'] = String::get_list(DB::fetch_all('SELECT traveller.id,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as name FROM traveller INNER JOIN reservation_traveller ON reservation_traveller.traveller_id = traveller.id WHERE reservation_traveller.reservation_id = '.Url::iget('reservation_id').' ORDER BY traveller.first_name'));
		}
        //System::debug($this->map);
		$this->parse_layout('edit',$this->map);
	}
	function update_reservation_traveller($id)
    {
		//if(Url::get('reservation_room_id') and DB::exists('select id from reservation_traveller where reservation_room_id='.Url::get('reservation_room_id').' and traveller_id='.$id.'')){
		if(DB::exists('select id from reservation_traveller where traveller_id='.$id.'')){
			DB::update('reservation_traveller',
			array(
				'entry_form_number',
				'visa_number'=>Url::get('visa_number'),
				'expire_date_of_visa'=>Date_Time::to_orc_date(Url::get('expire_date_of_visa')),
				'occupation',
				'entry_target'=>Url::get('entry_target'),
				'entry_date'=>Date_Time::to_orc_date(Url::get('entry_date')),
				'port_of_entry',
				'back_date'=>Date_Time::to_orc_date(Url::get('back_date')),
				'go_to_office',
				'provisional_residence',
				'hotel_name',
				'distrisct',
				'come_from',
				'time_out'=>Url::get('time_out')?Date_Time::to_time(Url::sget('time_out')):0
			),' traveller_id='.$id.'');//'reservation_room_id='.Url::get('reservation_room_id').'and 
		}
	}
}
?>