<?php
class AddTravellerForm extends Form
{
	function AddTravellerForm()
	{
		Form::Form('AddTravellerForm');
		$this->add('first_name',new TextType(true,'invalid_first_name',0,255)); 
		$this->add('last_name',new TextType(true,'invalid_last_name',0,255)); 
		$this->add('gender',new SelectType(true,'invalid_gender',array('1'=>'male','0'=>'female'))); 
		$this->add('birth_date',new DateType(true,'invalid_birth_date')); 
		$this->add('address',new TextType(false,'invalid_address',0,255)); 
		$this->add('email',new TextType(false,'invalid_email',0,255)); 
		$this->add('phone',new TextType(false,'invalid_phone',0,255)); 
		$this->add('fax',new TextType(false,'invalid_fax',0,255)); 
		$this->add('note',new TextType(false,'invalid_note',0,200000)); 
		$this->add('nationality_id',new IDType(true,'invalid_nationality_id','country'));
        $this->add('traveller.traveller_level_id',new TextType(true,'invalid_traveller_level',0,255)); 
		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');		
	}
	function on_submit()
	{
	   require_once 'packages/hotel/includes/member.php';
		$record = array(
                'FIRST_NAME'=>Url::get('first_name'),
                'LAST_NAME'=>Url::get('last_name'),
                'GENDER'=>Url::get('gender'),
                'BIRTH_DATE'=>Date_Time::to_orc_date(Url::get('birth_date')),
                'NATIONALITY_ID'=>Url::get('nationality_id'),
                'PASSPORT'=>Url::get('passport'),
                'ADDRESS'=>Url::get('address'),
                'EMAIL'=>Url::get('email'),
                'PHONE'=>Url::get('phone'),
                'NOTE'=>Url::get('note'),
                'IS_VN'=>Url::get('is_vn'),
                'TRAVELLER_LEVEL_ID'=>Url::get('traveller_level_id'),
                'PROVINCE_ID'=>Url::get('province_id')
        );
        if(Url::get('create_member_code'))
        {
            $to_day = Date_Time::to_orc_date(date('d/m/Y'));
            $member_code = create_member_code();
            $member_level = DB::fetch("SELECT id FROM member_level WHERE min_point=0");
            $password = create_password_radom();
            $record += array(
                'MEMBER_CODE'=>$member_code,'password'=>$password,
                'point'=>0,'point_user'=>0,'member_level_id'=>$member_level['id'],'member_create_date'=>$to_day
            );
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
        $id = DB::insert('traveller',$record);
        if(Url::get('site')=='mice')
        {
            echo "<script>window.opener.document.getElementById('traveller_id').value=".$id."; window.opener.document.getElementById('traveller_name').value='".Url::get('first_name')." ".Url::get('last_name')."';window.close();</script>";
        }
        if(Url::get("check_edit") AND (Url::get("check_edit")=='on'))
        {
            $tt = '?page=traveller&cmd=add';
        }
        else
        {
            $tt = '?page=traveller&cmd=list_member';
        }
        echo '<script>window.location.href = \''.$tt.'\'</script>';
	}	
	function draw()
	{	
		$nationality_list = DB::fetch_all("SELECT id,name_1 as name FROM country ORDER BY id");
        $this->map['nationality_id_list'] = array('1'=>Portal::language('select'))+String::get_list($nationality_list);
        $traveller_list = DB::fetch_all("SELECT id,name FROM guest_type ORDER BY id");
        $this->map['traveller_level_id_list'] = array(''=>Portal::language('select'))+String::get_list($traveller_list);
        $this->map['gender_list'] = array('1'=>Portal::language('male'),'0'=>Portal::language('female'));
        $province_list = DB::fetch_all("SELECT id,concat(concat(code,'-'),name) as name FROM province ORDER BY id");
        $this->map['province_id_list'] = array(''=>Portal::language('select'))+String::get_list($province_list);
        //$this->map['is_vn_list'] = array('0'=>Portal::language('Alien'),'1'=>Portal::language('Overseas_Vietnamese'),'2'=>Portal::language('Viet_nam'),'3'=>Portal::language('Viet_nam_in_foreign'));
        $this->map['is_vn_list'] = array('2'=>Portal::language('Viet_nam'),'3'=>Portal::language('Viet_nam_in_foreign'));
        $this->parse_layout('add',$this->map);
	}
}
?>